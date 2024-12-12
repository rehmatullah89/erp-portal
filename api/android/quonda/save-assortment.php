<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2008-15 (C) Triple Tree                                                        **
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
	**  ***************************************************************************************  **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$User       = IO::strValue('User');
	$AuditCode  = IO::strValue("AuditCode");
	$Quantities = IO::strValue("Quantities");
	$Cartons    = IO::strValue("Cartons");

	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else if ((int)getDbValue("COUNT(1)", "tbl_qa_reports", "audit_code='$AuditCode'") == 0)
			$aResponse["Message"] = "Invalid Request, The selected Audit Code has been Deleted.";

		else
		{
			$iUser = $objDb->getField(0, "id");
			$sName = $objDb->getField(0, "name");


			$iAuditCode = (int)substr($AuditCode, 1);
			
			$Quantities = substr($Quantities, 1, -1);
			$Quantities = @explode(", ", $Quantities);
			
			$Cartons    = substr($Cartons, 1, -1);
			$Cartons    = @explode(", ", $Cartons);

			
			$sDrawnCartonNos  = "";
			$sAssortmentCheck = "";
			
			foreach ($Cartons as $sCarton)
			{
				@list($sCartonNo, $sCheck) = @explode("|", $sCarton);
				
				$sDrawnCartonNos  .= (($sDrawnCartonNos != "") ? "," : "");
				$sDrawnCartonNos  .= $sCartonNo;
				
				$sAssortmentCheck .= (($sAssortmentCheck != "") ? "," : "");
				$sAssortmentCheck .= $sCheck;
			}



			$bFlag = $objDb->execute("BEGIN", true, $iUser, $sName);

			if (getDbValue("COUNT(1)", "tbl_gms_reports", "audit_id='$iAuditCode'") == 0)
			{
				$sSQL  = "INSERT INTO tbl_gms_reports (audit_id) VALUES ('$iAuditCode')";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "UPDATE tbl_gms_reports SET drawn_carton_no  = '$sDrawnCartonNos',
												     assortment_check = '$sAssortmentCheck'
						  WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_qa_color_quantities WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}
			
			if ($bFlag == true)
			{
				$sColors = getDbValue("colors", "tbl_qa_reports", "id='$iAuditCode'");
				$sColors = @explode(",", $sColors);
				
				foreach ($sColors as $sColor)
				{
					$iQuantity = getDbValue("COUNT(1)", "tbl_qa_report_progress", "audit_id='$iAuditCode' AND color='$sColor'");
					
					
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
				$sSQL  = "DELETE FROM tbl_qa_po_ship_quantities WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}			

			if ($bFlag == true)
			{
				foreach ($Quantities as $sPoQty)
				{
					@list($iPo, $iQuantity) = @explode("-", $sPoQty);
					
					
					$sSQL  = "INSERT INTO tbl_qa_po_ship_quantities SET audit_id = '$iAuditCode',
																		po_id    = '$iPo',
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
				$aResponse["Message"] = "Audit Quantity Saved Successfully!";
			}

			else
			{
				$aResponse["Message"] = $sSQL." - ".mysql_error()."An ERROR occured, please try again.";

				$objDb->execute("ROLLBACK", true, $iUser, $sName);
			}
		}
	}

	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = $Cap."\n\n".@json_encode($aResponse)."<bR>".$sSQL;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>