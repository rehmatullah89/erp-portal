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


	$User               = IO::strValue('User');
	$AuditCode          = IO::strValue("AuditCode");
	$PoColors           = IO::intValue("PoColors");
	
	$CartonQty          = IO::floatValue("CartonQty");
	$CountAccuracy      = IO::floatValue("CountAccuracy");
	$CountResult        = IO::strValue("CountResult");

	$RequiredWeight     = IO::strValue("RequiredWeight");
	$RequiredWeightUnit = IO::strValue("RequiredWeightUnit");
	$ActualWeight       = IO::strValue("ActualWeight");
	$ActualWeightUnit   = IO::strValue("ActualWeightUnit");
	$Variance           = IO::strValue("Variance");
	$VarianceUnit       = IO::strValue("VarianceUnit");
	$WeightResult       = IO::strValue("WeightResult");


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


			$iAuditCode  = (int)substr($AuditCode, 1);
			
			
			$sSQL = "SELECT report_id, audit_stage, vendor_id, colors, IF(additional_pos='', po_id, CONCAT(po_id, ',', additional_pos)) AS _Pos FROM tbl_qa_reports WHERE id='$iAuditCode'";
			$objDb->query($sSQL);
				
			$iReportId   = $objDb->getField(0, "report_id");
			$sAuditStage = $objDb->getField(0, "audit_stage");
			$iVendor     = $objDb->getField(0, "vendor_id");
			$sColors     = $objDb->getField(0, "colors");
			$sPos        = $objDb->getField(0, "_Pos");
			
			
			$bFlag = $objDb->execute("BEGIN", true, $iUser, $sName);


			if ($iReportId == 25)
			{
				if ($sAuditStage == "F")
				{
					$sSQL  = "DELETE FROM tbl_bbg_final_pos WHERE audit_id='$iAuditCode'";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
					
					if ($bFlag == true)
					{
						for ($i = 0; $i < $PoColors; $i ++)
						{
							$Production = stripslashes(IO::strValue("Production{$i}"));						
							$Production = @json_decode($Production, true);
							

							$iPo     = getDbValue("id", "tbl_po", "vendor_id='$iVendor' AND FIND_IN_SET(id, '$sPos') AND order_no LIKE '{$Production['Po']}'");
							$iStatus = getNextId("tbl_bbg_final_pos");
							
							$sSQL  = ("INSERT INTO tbl_bbg_final_pos SET id       = '$iStatus',
																		 audit_id = '$iAuditCode',
																		 po_id    = '$iPo',
																		 color    = '".$Production["Color"]."',
																		 cutting  = '".(float)$Production["Cutting"]."',
																		 shipment = '".(float)$Production["Shipment"]."',
																		 ex_fty   = '".(float)$Production["ExFactory"]."'");
							$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
							
							if ($bFlag == false)
								break;
						}
					}
					
					if ($bFlag == true)
					{
						$sSQL  = "DELETE FROM tbl_bbg_carton_details WHERE audit_id='$iAuditCode'";
						$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);						
					}
					
					if ($bFlag == true)
					{
						$sSQL  = ("INSERT INTO tbl_bbg_carton_details SET audit_id       = '$iAuditCode',
																		  carton_qty     = '$CartonQty',
																		  count_accuracy = '$CountAccuracy',
																		  count_result   = '$CountResult'");
						
						for ($i = 1; $i <= 12; $i ++)
						{
							$CartonNo    = IO::strValue("CartonNo{$i}");
							$CartonError = IO::strValue("CartonError{$i}");							
							
							$sSQL .= ", carton_no{$i}='$CartonNo', count_error{$i}='$CartonError' ";
						}
						
						$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
					}
				}
				
				else
				{
					$sSQL  = "DELETE FROM tbl_bbg_status WHERE audit_id='$iAuditCode'";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
					
					if ($bFlag == true)
					{						
						for ($i = 0; $i < $PoColors; $i ++)
						{
							$Production = stripslashes(IO::strValue("Production{$i}"));						
							$Production = @json_decode($Production, true);

							$iPo     = getDbValue("id", "tbl_po", "vendor_id='$iVendor' AND FIND_IN_SET(id, '$sPos') AND order_no LIKE '{$Production['Po']}'");
							$iStatus = getNextId("tbl_bbg_status");
							
							$sSQL  = ("INSERT INTO tbl_bbg_status SET  id                  = '$iStatus',
																	   audit_id            = '$iAuditCode',
																	   po_id               = '$iPo',
																	   color               = '".$Production["Color"]."',
																	   cutting             = '".(float)$Production["Cutting"]."',
																	   printing            = '".(float)$Production["Printing"]."',
																	   sewing              = '".(float)$Production["Sewing"]."',
																	   washing             = '".(float)$Production["Washing"]."',
																	   pressing            = '".(float)$Production["Pressing"]."',
																	   packaging           = '".(float)$Production["Packaging"]."',
																	   packing             = '".(float)$Production["Packing"]."',
																	   sample_size         = '".(float)$Production["SamplingSize"]."',
																	   semi_garment_bundle = '".$Production["Bundle"]."',
																	   remarks             = '".$Production["Remarks"]."'");
							$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
							
							if ($bFlag == false)
								break;							
						}
					}
				}
			}

			if ($bFlag == true)
			{
				$objDb->execute("COMMIT", true, $iUser, $sName);

				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "Production Status Saved Successfully!";
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