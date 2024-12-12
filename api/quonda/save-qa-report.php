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


	$User           = IO::intValue('User');
	$AuditCode      = IO::strValue("AuditCode");
	$Po             = IO::strValue("Po");
	$Style          = IO::strValue("Style");
	$AuditStage     = IO::strValue("AuditStage");
	$AuditStatus    = IO::strValue("AuditStatus");
	$SampleSize     = IO::intValue("SampleSize");
	$Colors         = IO::strValue("Colors");
	$Sizes          = IO::strValue("Sizes");
	$sAdditionalPos = "";

	$iAuditCode = intval(substr($AuditCode, 1));


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
		$sUser   = getDbValue("name", "tbl_users", "id='$User'");
		$sStatus = getDbValue("status", "tbl_users", "id='$User'");


		$sAuditStagesList = getList("tbl_audit_stages", "code", "stage");
		$sAuditStages     = array( );

		foreach ($sAuditStagesList as $sCode => $sStage)
			$sAuditStages[] = $sCode;


		if ($sStatus != "A")
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "User Account is Disabled";
		}

		else if ($Po == "" || !@in_array($AuditStage, $sAuditStages) ||
		         !@in_array($AuditStatus, array("1st", "2nd", "3rd", "4th", "5th", "6th")) || $SampleSize == 0 || $Colors == "")
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
				if ($Style != "" && count($sOrders) > 0)
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
						}
					}
				}

				else if (count($sOrders) == 1)
				{
					$iPoId          = $sOrders[0]['Po'];
					$sAdditionalPos = $sOrders[0]['Additional'];
				}
			}


			if ($bFlag == true)
			{
				$objDb->execute("BEGIN");


				if ($iStyleId == 0)
					$iStyleId = getDbValue("style_id", "tbl_po_colors", "po_id='$iPoId'");

				$iReportId = getDbValue("report_id", "tbl_qa_reports", "id='$iAuditCode'");


				$sSQL = "SELECT style, sub_brand_id FROM tbl_styles WHERE id='$iStyleId'";
				$objDb->query($sSQL);

				$sStyle   = $objDb->getField(0, 0);
				$iBrandId = (int)$objDb->getField(0, 1);



				$sSizes = @explode(",", $Sizes);
				$iSizes = "";

				for ($i = 0; $i < count($sSizes); $i ++)
				{
					$sSQL = ("SELECT id FROM tbl_sizes WHERE size LIKE '".trim($sSizes[$i])."' LIMIT 1");
					$objDb->query($sSQL);

					if ($objDb->getCount( ) == 1)
						$iSizes .= ((($iSizes != "") ? "," : "").$objDb->getField(0, 0));
				}


				$sSQL  = "UPDATE tbl_qa_reports SET po_id='$iPoId', additional_pos='$sAdditionalPos', style_id='$iStyleId', brand_id='$iBrandId', audit_stage='$AuditStage', audit_status='$AuditStatus', total_gmts='$SampleSize', colors='$Colors', sizes='$iSizes', audit_result='W', date_time=NOW( ), audit_mode='1' WHERE id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);


				if ($bFlag == false)
				{
					$bFlag    = false;
					$sMessage = "Unable to Save the QA Report";
				}

				else
				{
					if ($iReportId == 7)
					{
						$sSQL  = ("UPDATE tbl_qa_reports SET standard='1.5', shipping_mark='".IO::strValue("ShippingMark")."' WHERE id='$iAuditCode'");
						$bFlag = $objDb->execute($sSQL, true, $User, $sUser);

						if ($bFlag == true)
						{
							$sSQL = "SELECT audit_id FROM tbl_ar_inspection_checklist WHERE audit_id='$iAuditCode'";
							$objDb->query($sSQL);

							if ($objDb->getCount( ) == 0)
								$sSQL  = ("INSERT INTO tbl_ar_inspection_checklist (audit_id, model_name, working_no, fabric_approval, counter_sample_appr, garment_washing_test, color_shade, appearance, handfeel, printing, embridery, fibre_content, country_of_origin, care_instruction, size_key, adi_comp, colour_size_qty, polybag, hangtag, ocl_upc)
								                                            VALUES ('$iAuditCode', '".IO::strValue("ModelName")."', '".IO::strValue("WorkingNo")."', '".IO::strValue("FabricApproval")."', '".IO::strValue("CounterSampleAppr")."', '".IO::strValue("GarmentWashingTest")."', '".IO::strValue("ColorShade")."', '".IO::strValue("Appearance")."', '".IO::strValue("Handfeel")."', '".IO::strValue("Printing")."', '".IO::strValue("Embridery")."', '".IO::strValue("FibreContent")."', '".IO::strValue("CountryOfOrigin")."', '".IO::strValue("CareInstruction")."', '".IO::strValue("SizeKey")."', '".IO::strValue("AdiComp")."', '".IO::strValue("ColourSizeQty")."', '".IO::strValue("Polybag")."', '".IO::strValue("Hangtag")."', '".IO::strValue("OclUpc")."')");

							else
								$sSQL  = ("UPDATE tbl_ar_inspection_checklist SET model_name='".IO::strValue("ModelName")."', working_no='".IO::strValue("WorkingNo")."', fabric_approval='".IO::strValue("FabricApproval")."', counter_sample_appr='".IO::strValue("CounterSampleAppr")."', garment_washing_test='".IO::strValue("GarmentWashingTest")."', color_shade='".IO::strValue("ColorShade")."', appearance='".IO::strValue("Appearance")."', handfeel='".IO::strValue("Handfeel")."', printing='".IO::strValue("Printing")."', embridery='".IO::strValue("Embridery")."', fibre_content='".IO::strValue("FibreContent")."', country_of_origin='".IO::strValue("CountryOfOrigin")."', care_instruction='".IO::strValue("CareInstruction")."', size_key='".IO::strValue("SizeKey")."', adi_comp='".IO::strValue("AdiComp")."', colour_size_qty='".IO::strValue("ColourSizeQty")."', polybag='".IO::strValue("Polybag")."', hangtag='".IO::strValue("Hangtag")."', ocl_upc='".IO::strValue("OclUpc")."' WHERE audit_id='$iAuditCode'");

							$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
						}
					}

					else if ($iReportId == 11)
					{
						$sSQL  = ("UPDATE tbl_qa_reports SET batch_size='".IO::strValue("BatchSize")."', packed_percent='".IO::floatValue("PackedPercent")."', description='".IO::strValue("Description")."', ship_qty='".IO::intValue("BatchSize")."' WHERE id='$iAuditCode'");
						$bFlag = $objDb->execute($sSQL, true, $User, $sUser);

						if ($bFlag == true)
						{
							$sSQL = "SELECT * FROM tbl_ms_qa_reports WHERE audit_id='$iAuditCode'";
							$objDb->query($sSQL);

							if ($objDb->getCount( ) == 0)
								$sSQL = "INSERT INTO tbl_ms_qa_reports SET audit_id='$iAuditCode', ";

							else
								$sSQL = "UPDATE tbl_ms_qa_reports SET ";

							$sSQL .= ("series     = '".IO::strValue("Series")."',
									   department = '".IO::strValue("Department")."'");

							if ($objDb->getCount( ) == 1)
								$sSQL .= " WHERE audit_id='$iAuditCode'";

							$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
						}
					}
				}
			}


			if ($bFlag == true)
			{
				if ($iReportId == 10)
					$iDefective = getDbValue("COUNT(1)", "tbl_qa_report_defects", "audit_id='$iAuditCode' AND nature='1'");

				else if ($iReportId == 11)
					$iDefective = getDbValue("COUNT(1)", "tbl_qa_report_defects", "audit_id='$iAuditCode' AND (nature='0' OR nature='2.5')");

				else
					$iDefective = getDbValue("COUNT(1)", "tbl_qa_report_defects", "audit_id='$iAuditCode' AND nature='0'");


				$sSQL = "UPDATE tbl_qa_reports SET defective_gmts='$iDefective' WHERE id='$iAuditCode'";
				$objDb->execute($sSQL, true, $User, $sUser);
			}


			if ($bFlag == true && ($AuditStage == "F" || $AuditResult == "P" || $AuditResult == "A" || $AuditResult == "B"))
			{
				$sSQL  = "UPDATE tbl_qa_reports SET status='$AuditResult' WHERE id='$iAuditCode' AND status=''";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true && $AuditStage == "F" && $AuditResult == "P")
			{
				$sSQL  = "UPDATE tbl_qa_reports SET approved_sample='Yes', shipping_mark='Y', packing_check='Y' WHERE id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);


				// Updating VSR
				if ($bFlag == true)
				{
					$sSQL = "SELECT audit_date, po_id, additional_pos FROM tbl_qa_reports WHERE id='$iAuditCode' AND audit_stage='F' AND audit_result='P'";
					$objDb->query($sSQL);

					if ($objDb->getCount( ) == 1)
					{
						$sDate       = $objDb->getField(0, 0);
						$sPos        = $objDb->getField(0, 1);
						$sAdditional = $objDb->getField(0, 2);

						if ($sAdditional != "")
							$sPos .= (",".$sAdditional);


						$sSQL  = "UPDATE tbl_vsr SET final_audit_date='$sDate' WHERE po_id IN ($sPos)";
						$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
					}
				}
			}


			if ($bFlag == true && $AuditResult != "")
			{
				$sAuditStage  = $AuditStage;
				$sAuditResult = $AuditResult;
				$sAuditCode   = $AuditCode;
				$iShipQty     = $ShipQty;


				$sBrand  = getDbValue("brand", "tbl_brands", "id='$iBrandId'");
				$sVendor = getDbValue("vendor", "tbl_vendors", "id='$iVendorId'");
				$sPO     = getDbValue("order_no", "tbl_po", "id='$iPoId'");

/*
				// Alter to Azfar
				if (@in_array($iBrandId, array(67, 75, 242, 244, 260)))
				{
					$objSms = new Sms( );

					$sResult     = (($sAuditResult == "A" || $sAuditResult == "B" || $sAuditResult == "P") ? "Pass" : (($sAuditResult == "F" || $sAuditResult == "C") ? "Fail" : "Hold"));
					$sSmsMessage = "A Report for Style No: {$sStyle}, PO No: {$sPO} at {$sVendor} against {$sBrand} has just been published on the Customer Portal using Quonda App v1. (Audit Result: {$sResult})";

					if ($iBrandId == 242 || $iBrandId == 244)
					{
//						$objSms->send("+923214300069", "Omer Rauf", "", $sSmsMessage);
						$objSms->send("+923008445912", "Azfar Hasan", "", $sSmsMessage);
					}

					if ($iBrandId == 67 || $iBrandId == 75)
					{
//						$objSms->send("+919810228115", "Franklin Benjamin", "", $sSmsMessage);
//						$objSms->send("+919873677723", "Avneesh Kumar", "", $sSmsMessage);
					}

					else if ($iBrandId == 242)
					{
						$objSms->send("+491732370864", "Adrian", "", $sSmsMessage);
						$objSms->send("+491726206900", "Rainer", "", $sSmsMessage);
					}

					else if ($iBrandId == 244)
						$objSms->send("+491728876474", "Monika", "", $sSmsMessage);

					else if ($iBrandId == 260)
					{
//						$objSms->send("+923214300069", "Omer Rauf", "", $sSmsMessage);
						$objSms->send("+94773827977", "Sanjaya Anuk Fernando", "", $sSmsMessage);
						$objSms->send("+94773156490", "Udaya Kaviratne", "", $sSmsMessage);
						$objSms->send("+94773902956", "Manoj Balachandran", "", $sSmsMessage);
					}


					$sSmsMessage = ("Click the below link to download the Audit Report: http://portal.3-tree.com/get-qa-report.php?Id=".@md5($sAuditCode)."&AuditCode={$sAuditCode}");

					if ($iBrandId == 242 || $iBrandId == 244)
					{
//						$objSms->send("+923214300069", "Omer Rauf", "", $sSmsMessage);
						$objSms->send("+923008445912", "Azfar Hasan", "", $sSmsMessage);
					}

					if ($iBrandId == 67 || $iBrandId == 75)
					{
//						$objSms->send("+919810228115", "Franklin Benjamin", "", $sSmsMessage);
//						$objSms->send("+919873677723", "Avneesh Kumar", "", $sSmsMessage);
					}

					else if ($iBrandId == 242)
					{
						$objSms->send("+491732370864", "Adrian", "", $sSmsMessage);
						$objSms->send("+491726206900", "Rainer", "", $sSmsMessage);
					}

					else if ($iBrandId == 244)
						$objSms->send("+491728876474", "Monika", "", $sSmsMessage);

					else if ($iBrandId == 260)
					{
//						$objSms->send("+923214300069", "Omer Rauf", "", $sSmsMessage);
						$objSms->send("+94773827977", "Sanjaya Anuk Fernando", "", $sSmsMessage);
						$objSms->send("+94773156490", "Udaya Kaviratne", "", $sSmsMessage);
						$objSms->send("+94773902956", "Manoj Balachandran", "", $sSmsMessage);

/*
						$sStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");
						$sLink  = ("http://portal.3-tree.com/get-qa-report.php?Id=".@md5($sAuditCode)."&AuditCode={$sAuditCode}");
						$sLine  = getDbValue("(SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id)", "tbl_qa_reports", "id='$iAuditCode'");

						$sBody = "Dear User,<br /><br />
								  Please <a href='{$sLink}'>click here</a> for a PDF version of the Audit Report for:<br />
								  Audit Code: {$sAuditCode}<br />
								  PO Number: {$sPO}<br />
								  Vendor: {$sVendor}<br />
								  Brand: {$sBrand}<br />
								  Style: {$sStyle}<br />
								  Line: {$sLine}<br />
								  Audit Stage: {$sStage}<br />
								  Result: {$sResult}<br />
								  <br />
								  <br />
								  Triple Tree Customer Portal";


						$objEmail = new PHPMailer( );

						$objEmail->Subject  = "Triple Tree Audit (Purchase Order No: {$sPO} Style No: {$sStyle}) Conducted for '{$sBrand}' at '{$sVendor}' - Result - '{$sResult}'";
						$objEmail->MsgHTML($sBody);

//						$objEmail->AddAddress("omer@3-tree.com", "Omer Rauf");
						$objEmail->AddAddress("sanfernando@mgfsourcing.com", "Sanjaya Anuk Fernando");
						$objEmail->addBCC("darho@mgfsourcing.com", "Ho, Darren");
						$objEmail->addBCC("STan@MGFSourcing.com", "Tan, Samuel");
						$objEmail->addBCC("switharana@mgfsourcing.com", "Shehan Witharana");
						$objEmail->addBCC("ASumanadasa@MGFSourcing.com", "Aruna Sumanadasa");
						$objEmail->AddAddress("ukaviratne@mgfsourcing.com", "Udaya Kaviratne");
						$objEmail->AddAddress("Balachandran@mgfsourcing.com", "Manoj Balachandran");
//						$objEmail->addBCC("rstephen@mgfsourcing.com", "Stephen Ranga");
						$objEmail->addBCC("Jjeyaram@mgfsourcing.com", "Jeyaram, Jeyadinesh");

						$objEmail->Send( );
*/
					}

					$objSms->close( );
				}
*/

				// Notifications
				if ($AuditStage == "F")
				{
					// Final Audit Conducted
					@include($sBaseDir."includes/sms/final-audit.php");


					// Final Audit Approval
					if ($sAuditResult == "P")
						@include($sBaseDir."includes/sms/final-audit-approval.php");
				}

				else if (!@in_array($sAuditResult, array("P", "A", "B")))
					@include($sBaseDir."includes/sms/inline-audit.php");
			}

/*
			if ($bFlag == true)
			{
				if ($iReportId == 10)
					$iDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditCode' AND nature='1'");

				else if ($iReportId == 11)
					$iDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditCode' AND (nature='0' OR nature='2.5')");

				else
					$iDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditCode' AND nature='0'");


				$iSampleSize = getDbValue("total_gmts", "tbl_qa_reports", "id='$iAuditCode'");
				$fDhu        = @round((($iDefects / $iSampleSize) * 100), 2);


				$sSQL  = "UPDATE tbl_qa_reports SET dhu='$fDhu' WHERE id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}
*/
			if ($bFlag == true)
			{
				$objDb->execute("COMMIT");

				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "QA Report Saved Successfully!";
			}

			else
			{
				$objDb->execute("ROLLBACK");

				$aResponse['Status'] = "ERROR";
				$aResponse["Error"]  = "$sMessage ";
			}
		}
	}

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($aResponse);

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