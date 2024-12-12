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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$objSms = new Sms( );


	$Offline               = IO::strValue("Offline");
	$User                  = IO::intValue('User');
	$AuditCode             = IO::strValue("AuditCode");
	$Po                    = IO::strValue("Po");
	$Style                 = IO::strValue("Style");
	$AuditStage            = IO::strValue("AuditStage");
	$InspectionType        = IO::strValue("InspectionType");
	$DyeLotNo              = IO::strValue("DyeLotNo");
	$AcceptablePointsWoven = IO::strValue("AcceptablePointsWoven");
	$AuditResult           = IO::strValue("AuditResult");
	$ShipQty               = IO::intValue("ShipQty");
	$ReScreenQty           = IO::intValue("ReScreenQty");
	$CutableFabricWidth    = IO::strValue("CutableFabricWidth");
	$StockStatus           = IO::strValue("StockStatus");
	$RollsInspected        = IO::intValue("RollsInspected");
	$Comments              = IO::strValue("Comments");
	$ColorMatch            = IO::strValue("ColorMatch");
	$Shading               = IO::strValue("Shading");
	$HandFeel              = IO::strValue("HandFeel");
	$LabTesting            = IO::strValue("LabTesting");
	$FabricWidth           = IO::intValue("FabricWidth");
	$NoOfRolls             = IO::intValue("NoOfRolls");


	$iAuditCode     = intval(substr($AuditCode, 1));
	$sAdditionalPos = "";
	$iTotalGivenQty = 0;


	$aResponse = array( );


	if ($User == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid User ID";
	}

	else if ($iAuditCode == 0 || strlen($AuditCode) == 0 || $AuditCode{0} != "S")
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Audit Code";
	}

	else
	{
		$sStatus = getDbValue("status", "tbl_users", "id='$User'");

		if ($sStatus != "A")
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "User Account is Disabled";
		}

		else if ($Offline == "" && ($Po == "" || !@in_array($AuditStage, array("OL", "SK", "F")) || !@in_array($AuditResult, array("F", "H", "P", "A", "B", "C"))))
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "Invalid QA Request - Fill All Fields";
		}

		else
		{
			$bFlag    = true;
			$sMessage = "";
			$iPoId    = 0;
			$iStyleId = 0;
			$sOrders  = array( );


			// audit code validation
			$sSQL = "SELECT vendor_id FROM tbl_qa_reports WHERE id='{$iAuditCode}'";

			if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
				$iVendorId = $objDb->getField(0, 0);

			else
			{
				$bFlag    = false;
				$sMessage = "Invalid Audit Code";
			}


			if ($bFlag == true)
			{
				// PO validation
				if (@strpos($Po, ",") !== FALSE)
				{
					$sPos = @explode(",", $Po);

					for ($i = 0; $i < count($sPos); $i ++)
					{
						$sSQL = "SELECT id, styles FROM tbl_po WHERE (order_no='{$sPos{$i}}' OR CONCAT(order_no, ' ', order_status)='{$sPos{$i}}') AND vendor_id='$iVendorId'";

						if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
						{
							if ($i == 0)
							{
								$sOrders[0]['Po']     = $objDb->getField($i, 0);
								$sOrders[0]['Styles'] = $objDb->getField($i, 1);
							}

							else
							{
								if ($sOrders[0]['Additional'] != "")
									$sOrders[0]['Additional'] .= ",";

								$sOrders[0]['Additional'] .= $objDb->getField(0, 0);
							}
						}

						else
						{
							$bFlag    = false;
							$sMessage = (($objDb->getCount( ) > 1) ? "Multiple POs with same Order No" : "Invalid PO No");

							break;
						}
					}
				}

				else
				{
					$sSQL = "SELECT id, styles FROM tbl_po WHERE (order_no='{$Po}' OR CONCAT(order_no, ' ', order_status)='{$Po}') AND vendor_id='$iVendorId'";
					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );

					for ($i = 0; $i < $iCount; $i ++)
					{
						$sOrders[$i]['Po']         = $objDb->getField($i, 0);
						$sOrders[$i]['Styles']     = $objDb->getField($i, 1);
						$sOrders[$i]['Additional'] = "";
					}

					if ($iCount == 0)
					{
						$bFlag    = false;
						$sMessage = "Invalid PO No";
					}
				}
			}


			if ($bFlag == true)
			{
				if ($Style == "")
				{
					if (count($sOrders) == 1)
					{
						$iPoId          = $sOrders[0]['Po'];
						$sAdditionalPos = $sOrders[0]['Additional'];
						$iStyleId       = getDbValue("style_id", "tbl_po_colors", "po_id='$iPoId'");
					}
				}

				else if ($Style != "" && count($sOrders) > 0)
				{
					$iStyles = array( );


					$sSQL = "SELECT id FROM tbl_styles WHERE style='{$Style}'";
					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );

					for ($i = 0; $i < $iCount; $i ++)
						$iStyles[$i] = $objDb->getField($i, 0);

					if ($iCount == 0)
					{
						$bFlag    = false;
						$sMessage = "Invalid Style No";
					}

					else
					{
						for ($i = 0; $i < count($sOrders); $i ++)
						{
							for ($j = 0; $j < count($iStyles); $j ++)
							{
								$sSQL = "SELECT * FROM tbl_po_colors WHERE style_id='{$iStyles[$j]}' AND FIND_IN_SET(style_id, '{$sOrders[$i]['Styles']}') AND po_id='{$sOrders[$i]['Po']}'";
								$objDb->query($sSQL);

								if ($objDb->getCount( ) > 0)
								{
									$iPoId          = $sOrders[$i]['Po'];
									$sAdditionalPos = $sOrders[$i]['Additional'];
									$iStyleId       = $iStyles[$j];

									break;
								}
							}

							if ($iPoId > 0)
								break;
						}

						if ($iPoId == 0)
						{
							$bFlag    = false;
							$sMessage = "Invalid PO - Style No Combination";

							if (count($sOrders) == 1)
							{
								$iPoId          = $sOrders[0]['Po'];
								$sAdditionalPos = $sOrders[0]['Additional'];
							}
						}
					}
				}

				else if (count($sOrders) == 1)
				{
					$iPoId          = $sOrders[0]['Po'];
					$sAdditionalPos = $sOrders[0]['Additional'];
				}
			}


			if ($iPoId > 0 && $iStyleId == 0)
				$iStyleId = getDbValue("style_id", "tbl_po_colors", "po_id='$iPoId'");


			$sSQL = "SELECT style, sub_brand_id FROM tbl_styles WHERE id='$iStyleId'";
			$objDb->query($sSQL);

			$sStyle   = $objDb->getField(0, 0);
			$iBrandId = (int)$objDb->getField(0, 1);


			// Forcing system to upload report for offline version
			$bFlag= true;

			$objDb->execute("BEGIN");


			if ($bFlag == true)
			{
				$sUser     = getDbValue("name", "tbl_users", "id='$User'");
				$iReportId = getDbValue("report_id", "tbl_qa_reports", "id='$iAuditCode'");


				$sSQL  = "UPDATE tbl_qa_reports SET po_id='$iPoId', additional_pos='$sAdditionalPos', style_id='$iStyleId', brand_id='$iBrandId', audit_stage='$AuditStage', audit_status='1st', audit_result='$AuditResult', ship_qty='$ShipQty', re_screen_qty='$ReScreenQty', dye_lot_no='$DyeLotNo', acceptable_points_woven='$AcceptablePointsWoven', inspection_type='$InspectionType', cutable_fabric_width='$CutableFabricWidth', stock_status='$StockStatus', rolls_inspected='$RollsInspected', no_of_rolls='$NoOfRolls', fabric_width='$FabricWidth', qa_comments='$Comments', date_time=NOW( ), audit_mode='1' WHERE id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);

				if ($bFlag == false)
				{
					$bFlag    = false;
					$sMessage = "Unable to Save the QA Report";
				}

				else
				{
					$sSQL = "SELECT id FROM tbl_gf_rolls_info WHERE audit_id='$iAuditCode' ORDER BY id";
					$objDb->query($sSQL);

					$iRolls = $objDb->getCount( );


					for ($i = 1; $i <= 5; $i ++)
					{
						$RollNo   = IO::strValue("RollNo{$i}");
						$Ref_1    = IO::strValue("RollNo{$i}_1");
						$Given_1  = IO::floatValue("Given{$i}_1");
						$Actual_1 = IO::floatValue("Actual{$i}_1");
						$Ref_2    = IO::strValue("RollNo{$i}_2");
						$Given_2  = IO::floatValue("Given{$i}_2");
						$Actual_2 = IO::floatValue("Actual{$i}_2");
						$Ref_3    = IO::strValue("RollNo{$i}_3");
						$Given_3  = IO::floatValue("Given{$i}_3");
						$Actual_3 = IO::floatValue("Actual{$i}_3");


						$RollId = 0;

						if ($i <= $iRolls)
							$RollId = $objDb->getField(($i - 1), "id");


						if ($RollNo != "")
						{
							$iTotalGivenQty += ($Given_1 + $Given_2 + $Given_3);

							if ($RollId > 0)
								$sSQL  = "UPDATE tbl_gf_rolls_info SET roll_no='$RollNo', ref_1='$Ref_1', given_1='$Given_1', actual_1='$Actual_1', ref_2='$Ref_2', given_2='$Given_2', actual_2='$Actual_2', ref_3='$Ref_3', given_3='$Given_3', actual_3='$Actual_3' WHERE id='$RollId'";

							else
							{
								$RollId = getNextId("tbl_gf_rolls_info");

								$sSQL  = ("INSERT INTO tbl_gf_rolls_info (id, audit_id, roll_no, ref_1, given_1, actual_1, ref_2, given_2, actual_2, ref_3, given_3, actual_3)
								                                  VALUES ('$RollId', '$iAuditCode', '$RollNo', '$Ref_1', '$Given_1', '$Actual_1', '$Ref_2', '$Given_2', '$Actual_2', '$Ref_3', '$Given_3', '$Actual_3')");
							}

							$bFlag = $objDb2->execute($sSQL, true, $User, $sUser);

							if ($bFlag == false)
								break;
						}

						else
						{
							if ($RollId > 0)
							{
								$sSQL  = "DELETE FROM tbl_gf_rolls_info WHERE id='$RollId'";
								$bFlag = $objDb2->execute($sSQL, true, $User, $sUser);
							}
						}


						if ($bFlag == false)
							break;
					}
				}

				if ($bFlag == true)
				{
					$sSQL = "SELECT audit_id FROM tbl_gf_inspection_checklist WHERE audit_id='$iAuditCode'";
					$objDb->query($sSQL);

					if ($objDb->getCount( ) == 0)
						$sSQL = ("INSERT INTO tbl_gf_inspection_checklist (audit_id, color_match, color_match_remarks, shading, shading_remarks, hand_feel, hand_feel_remarks, lab_testing, lab_testing_remarks)
						                                            VALUES ('$iAuditCode', '$ColorMatch', '', '$Shading', '', '$HandFeel', '', '$LabTesting', '')");

					else
						$sSQL = "UPDATE tbl_gf_inspection_checklist SET color_match='$ColorMatch', shading='$Shading', hand_feel='$HandFeel', lab_testing='$LabTesting' WHERE audit_id='$iAuditCode'";

					$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
				}
			}



			if ($bFlag == true && $AuditStage == "F" && $AuditResult == "P")
			{
				$sSQL  = "UPDATE tbl_qa_reports SET approved_sample='Yes', shipping_mark='Y', packing_check='Y' WHERE id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);


				// Updating VSR
				$sSQL = "SELECT audit_date, po_id, additional_pos FROM tbl_qa_reports WHERE id='$iAuditCode' AND audit_stage='F' AND audit_result='P'";
				$objDb->query($sSQL);

				if ($objDb->getCount( ) == 1)
				{
					$sDate       = $objDb->getField(0, 0);
					$sPos        = $objDb->getField(0, 1);
					$sAdditional = $objDb->getField(0, 2);

					if ($sAdditional != "")
						$sPos .= (",".$sAdditional);


					$sSQL = "UPDATE tbl_vsr SET final_audit_date='$sDate' WHERE po_id IN ($sPos)";
					$objDb->execute($sSQL, true, $User, $sUser);
				}
			}


			if ($bFlag == true)
			{
				// Notifications
				if ($AuditStage == "F")
				{
					$sAuditStage  = $AuditStage;
					$sAuditResult = $AuditResult;
					$sAuditCode   = $AuditCode;
					$iShipQty     = $ShipQty;


					$sBrand  = getDbValue("brand", "tbl_brands", "id='$iBrandId'");
					$sVendor = getDbValue("vendor", "tbl_vendors", "id='$iVendorId'");
					$sPO     = getDbValue("order_no", "tbl_po", "id='$iPoId'");


					// Final Audit Conducted
					@include($sBaseDir."includes/sms/final-audit.php");


					// Final Audit Approval
					if ($sAuditResult == "P")
						@include($sBaseDir."includes/sms/final-audit-approval.php");
				}
			}

			if ($objDb->execute($sSQL, true, $User, $sUser) == true)
			{
				$iTotalDefects = getDbValue("SUM(defects)", "tbl_gf_report_defects", "audit_id='$iAuditCode'");
				$iTotalPoints  = getDbValue("SUM((defects * grade))", "tbl_gf_report_defects", "audit_id='$iAuditCode'");


				if (getDbValue("brand_id", "tbl_po", "id='$iPoId'") == 77)
					$fDhu = round((($iTotalDefects * 39.37 * 100) / $iTotalGivenQty / $FabricWidth), 2);

				else
					$fDhu = round(((($iTotalPoints * 3600) / $iTotalGivenQty) / $FabricWidth), 2);


				$sSQL = "UPDATE tbl_qa_reports SET dhu='$fDhu' WHERE id='$iAuditCode'";
				$objDb->execute($sSQL, true, $User, $sUser);
			}


			if ($bFlag == true)
			{
				$objDb->execute("COMMIT");

				$aResponse['Status']  = "OK";
				$aResponse["Message"] = (($sMessage != "") ? "Report Saved ({$sMessage})" : "Report Saved");
			}

			else
			{
				$objDb->execute("ROLLBACK");

				$aResponse['Status'] = "ERROR";
				$aResponse["Error"]  = "$sMessage ";
			}

			$objDb->execute("ROLLBACK");
		}
	}



/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($aResponse)."<br><br>".$sSQL."<br><br>".mysql_error();

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/


	print @json_encode($aResponse);


	$objSms->close( );

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>