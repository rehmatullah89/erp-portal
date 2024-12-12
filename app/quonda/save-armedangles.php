<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree QUONDA App                                                                   **
	**  Version 3.0                                                                              **
	**                                                                                           **
	**  http://app.3-tree.com                                                                    **
	**                                                                                           **
	**  Copyright 2008-17 (C) Triple Tree                                                        **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**  Project Manager:                                                                         **
	**                                                                                           **
	**      Name  :  Muhammad Tahir Shahzad                                                      **
	**      Email :  mtahirshahzad@hotmail.com                                                   **
	**      Phone :  +92 333 456 0482                                                            **
	**      URL   :  http://www.mtshahzad.com                                                    **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	logApiCall($_POST);

	$User       = IO::strValue('User');
	$AuditCode  = IO::strValue("AuditCode");
	$Quantities = IO::strValue("Quantities");
	$CartonNos  = IO::strValue("CartonNos");



	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else if ((int)getDbValue("COUNT(1)", "tbl_qa_reports", "audit_code='$AuditCode'") == 0)
			$aResponse["Message"] = "Invalid Request, The selected Audit Code has been Deleted.";

		else
		{
			$iUser  = $objDb->getField(0, "id");
			$sName  = $objDb->getField(0, "name");
			$sGuest = $objDb->getField(0, "guest");


			$iAuditCode = (int)substr($AuditCode, 1);
			
			$Quantities = substr($Quantities, 1, -1);
			$Quantities = @explode(", ", $Quantities);
			
			$CartonNos  = substr($CartonNos, 1, -1);
			$CartonNos  = @explode(", ", $CartonNos);
			
			
			$bFlag = $objDb->execute("BEGIN", true, $iUser, $sName);

			if ($bFlag == true)
			{
				foreach ($CartonNos as $sColorCartons)
				{
					@list($sColor, $sCartons) = @explode("|-|", $sColorCartons);
					
					if ($sCartons != "")
						$sCartons = substr($sCartons, 1, -1);
					
					
					$sSQL  = "INSERT INTO tbl_qa_report_cartons SET audit_id   = '$iAuditCode',
																	color      = '$sColor',
																	carton_nos = '$sCartons'";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
					
					if ($bFlag == false)
						break;
				}
			}
			
			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_qa_color_quantities WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}
			
			if ($bFlag == true)
			{
				foreach ($Quantities as $sColorQty)
				{
					@list($sColor, $iQuantity) = @explode("|-|", $sColorQty);
					
					$iQuantity = intval($iQuantity);
					
					
					$sSQL  = "INSERT INTO tbl_qa_color_quantities SET audit_id = '$iAuditCode',
																	  color    = '$sColor',
																	  quantity = '$iQuantity'";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);

					if ($bFlag == false)
						break;
				}
			}

			if ($bFlag == true)
			{
				$objDb->execute("COMMIT", true, $iUser, $sName);

				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "Shipment Qty & Carton Nos Saved Successfully!";
			}

			else
			{
				$aResponse["Message"] = "An ERROR occured, please try again.";

				$objDb->execute("ROLLBACK", true, $iUser, $sName);
			}
		}
	}

	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($aResponse)."<bR>".$sSQL;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>