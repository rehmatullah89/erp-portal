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


	$User                  = IO::strValue('User');
	$AuditCode             = IO::strValue("AuditCode");
	$CartonNos             = IO::strValue("CartonNos");
	$MeasurementSampleSize = IO::intValue("MeasurementSampleSize");
	$OutOfTolerance        = IO::intValue("OutOfTolerance");
	$Pom                   = IO::intValue("Pom");
	$MeasurementResult     = IO::strValue("MeasurementResult");
	$PackingResult         = IO::strValue("PackingResult");
	$GarmentResult         = IO::strValue("GarmentResult");


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
			$iReportId  = getDbValue("report_id", "tbl_qa_reports", "id='$iAuditCode'");
			
			$CartonNos = substr($CartonNos, 1, -1);
			$CartonNos = str_replace(", ", ",", $CartonNos);			
			
			
			$bFlag = $objDb->execute("BEGIN", true, $iUser, $sName);

			if ($iReportId == 36)
			{				
				$sSQL = ("INSERT INTO tbl_hybrid_link_reports SET audit_id                = '$iAuditCode',
																  carton_nos              = '$CartonNos',
																  measurement_points      = '$Pom',
																  measurement_sample_size = '$MeasurementSampleSize',
																  total_tolerance_pts     = '$OutOfTolerance',
																  measurement_result      = '$MeasurementResult',
																  packing_result          = '$PackingResult',
																  conformity_result       = '$GarmentResult'");
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);			
				
				if ($bFlag == true)
				{
					$sCheckList = array("1"  => "Assortment",
					                    "2"  => "MainLabel",
					                    "3"  => "CareLabel",
					                    "4"  => "Hangtag",
					                    "5"  => "Polybag",
					                    "6"  => "Sticker",
					                    "7"  => "Price",
					                    "8"  => "Hanger",
					                    "9"  => "CartonDimension",
					                    "10" => "ShippingMarks",
					                    "11" => "PackingOthers",
					                    "12" => "Construction",
					                    "13" => "Appearance",
					                    "14" => "Handfeel",
					                    "15" => "Thread",
					                    "16" => "Stitches",
					                    "17" => "Print",
					                    "18" => "ColorCombo",
					                    "19" => "Components",
					                    "20" => "Trims",
					                    "21" => "Accessories",
					                    "22" => "GarmentOthers");
					
					
					foreach ($sCheckList as $iCheck => $sCheck)
					{
						$sSQL = ("INSERT INTO tbl_hybrid_link_report_check_details SET  audit_id = '$iAuditCode',
																						check_id = '$iCheck',
																						result   = '".IO::strValue($sCheck)."',
																						remarks  = '".IO::strValue("{$sCheck}Remarks")."'");
						$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
					
						if ($bFlag == false)
							break;
					}
				}
			}

			if ($bFlag == true)
			{
				$objDb->execute("COMMIT", true, $iUser, $sName);

				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "Audit Conformity Data Saved Successfully!";
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