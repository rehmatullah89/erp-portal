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
	$objDb3      = new Database( );


	$User             = IO::strValue('User');
	$AuditCode        = IO::strValue("AuditCode");
	$ShipQty          = IO::intValue("ShipQty");
	$ReScreenQty      = IO::intValue("ReScreenQty");
	$TotalCartons     = IO::intValue("TotalCartons");
	$CartonsInspected = IO::intValue("CartonsInspected");
	$CartonsShipped   = IO::intValue("CartonsShipped");
	$CartonSize       = IO::strValue("CartonSize");
	$ApprovedSample   = IO::strValue("ApprovedSample");
	$ShippingMark     = IO::strValue("ShippingMark");
	$PackingCheck     = IO::strValue("PackingCheck");
	$Bundle           = IO::strValue("Bundle");
	$EmbApproval      = IO::strValue("EmbApproval");
	$GsmWeight        = IO::strValue("GsmWeight");
	$ShadeBand        = IO::strValue("ShadeBand");
	$ApprovedTrims    = IO::strValue("ApprovedTrims");
	$ProductionStatus = IO::strValue("ProductionStatus");
	$InspectionStatus = IO::strValue("InspectionStatus");
	$InspectionType   = IO::strValue("InspectionType");
	$Maker            = IO::strValue("Maker");
	$AuditResult      = IO::strValue("AuditResult");
	$Comments         = IO::strValue("Comments");
	$DateTime         = IO::strValue("DateTime");


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S" || $AuditResult == "")
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


			$iAuditCode   = (int)substr($AuditCode, 1);
			$sAuditStatus = "1st";

			$CartonSize   = str_replace(" ", "", $CartonSize);
			$CartonSize   = str_replace(",", "x", $CartonSize);


			$sSQL = "SELECT style_id, po_id, vendor_id, report_id, audit_stage, total_gmts, checked_gmts, dhu,
			                (SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line
			         FROM tbl_qa_reports
			         WHERE id='$iAuditCode'";
			$objDb->query($sSQL);

			$iStyleId    = $objDb->getField(0, 'style_id');
			$iPoId       = $objDb->getField(0, 'po_id');
			$iReportId   = $objDb->getField(0, 'report_id');
			$iVendorId   = $objDb->getField(0, 'vendor_id');
			$sAuditStage = $objDb->getField(0, 'audit_stage');
			$iSampleSize = $objDb->getField(0, "total_gmts");
			$iChecked    = $objDb->getField(0, "checked_gmts");
			$fDhu        = $objDb->getField(0, 'dhu');
			$sLine       = $objDb->getField(0, "_Line");
			
			if ($iChecked < $iSampleSize)
				$iChecked = $iSampleSize;


			$sSQL = "SELECT style, brand_id, sub_brand_id FROM tbl_styles WHERE id='$iStyleId'";
			$objDb->query($sSQL);

			$sStyle   = $objDb->getField(0, "style");
			$iBrand   = $objDb->getField(0, "brand_id");
			$iBrandId = $objDb->getField(0, "sub_brand_id");


			$fAql            = getDbValue("aql", "tbl_brands", "id='$iBrandId'");
			$fAql            = (($fAql == 0) ? 2.5 : $fAql);
			$iDefectsAllowed = 0;
			$TotalCartons    = (($TotalCartons == 0) ? $CartonsInspected : $TotalCartons);

			if (@isset($iAqlChart["{$iSampleSize}"]["{$fAql}"]))
				$iDefectsAllowed = $iAqlChart["{$iSampleSize}"]["{$fAql}"];


//			$iDefective = (int)getDbValue("COUNT(DISTINCT(sample_no))", "tbl_qa_report_defects", "audit_id='$iAuditCode' AND nature>'0'");
			$iDefective = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditCode' AND nature>'0'");


			$iAuditsCount = (int)getDbValue("COUNT(1)", "tbl_qa_reports", "po_id='$iPoId' AND style_id='$iStyleId' AND vendor_id='$iVendorId' AND report_id='$iReportId' AND audit_stage='$sAuditStage' AND id!='$iAuditCode'");
			$iAuditsCount ++;

			$iRemainder   = (abs($iAuditsCount) % 10);
			$sExtension   = ((abs($iAuditsCount) % 100 < 21 && abs($iAuditsCount) %100 > 4) ? 'th' : (($iRemainder < 4) ? ($iRemainder < 3) ? ($iRemainder < 2) ? ($iRemainder < 1) ? 'th' : 'st' : 'nd' : 'rd' : 'th'));
			$sAuditStatus = ($iAuditsCount.$sExtension);
			
			
			$sEndDateTimeSQL = "";
			
			if ($DateTime != "" && ($iReportId == 14 || $iReportId == 34))
			{
				$sTime = substr($DateTime, -8);
				
				$sEndDateTimeSQL = ", end_date_time='$DateTime', end_time='$sTime' ";
			}
			
			if (@in_array($iReportId, array(28, 37)))
				$ShipQty = (int)getDbValue("SUM(quantity)", "tbl_qa_color_quantities", "audit_id='$iAuditCode'");


			$objDb->execute("BEGIN", true, $iUser, $sName);


			$sSQL  = ("UPDATE tbl_qa_reports SET audit_status='$sAuditStatus', ship_qty='$ShipQty', re_screen_qty='$ReScreenQty', total_cartons='$TotalCartons', inspected_cartons='$CartonsInspected', cartons_required='$CartonsShipped', cartons_shipped='$CartonsShipped', 
			                                     carton_size='$CartonSize', max_defects='$iDefectsAllowed', defective_gmts='".((IO::floatValue("GmtsDefective") > 0) ? IO::floatValue("GmtsDefective") : $iDefective)."', knitted='".IO::floatValue("Knitted")."', 
												 dyed='".IO::floatValue("Dyed")."', cutting='".IO::intValue("Cutting")."', sewing='".IO::intValue("Sewing")."', finishing='".IO::intValue("Finishing")."', packing='".IO::intValue("Packing")."', 
												 final_audit_date='".((IO::strValue("FinalAuditDate") == "") ? "0000-00-00" : IO::strValue("FinalAuditDate"))."', production_status='$ProductionStatus', inspection_status='$InspectionStatus', approved_sample='$ApprovedSample', 
												 shipping_mark='$ShippingMark', packing_check='$PackingCheck', bundle='$Bundle', emb_approval='$EmbApproval', gsm_weight='$GsmWeight', shade_band='$ShadeBand', approved_trims='$ApprovedTrims', 
												 inspection_type='$InspectionType', maker='$Maker', checked_gmts='$iChecked', qa_comments='$Comments', audit_result='$AuditResult', audit_mode='2', date_time=NOW( ), modified_by='$iUser'
												 $sEndDateTimeSQL WHERE id='$iAuditCode'");
			$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);

			if ($bFlag == true && ($iReportId == 7 || $iReportId == 19))
			{
				$sWorkingNo = IO::strValue("WorkingNo");

				if ($sWorkingNo == "")
					$sWorkingNo = getDbValue("style", "tbl_styles", "id='$iStyleId'");


				$sSQL  = ("UPDATE tbl_qa_reports SET ship_qty='".IO::intValue("PiecesAvailable")."', beautiful_products='".IO::intValue("BeautifulProducts")."' WHERE id='$iAuditCode'");
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);

				if ($bFlag == true)
				{
					$sSQL  = "DELETE FROM tbl_ar_inspection_checklist WHERE audit_id='$iAuditCode'";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					$sSQL  = ("INSERT INTO tbl_ar_inspection_checklist (audit_id, model_name, working_no, fabric_approval, counter_sample_appr, sealing_sample_appr, garment_washing_test, metal_detection, color_shade, appearance, handfeel, printing, embridery, fibre_content, country_of_origin, care_instruction, size_key, adi_comp, colour_size_qty, polybag, hangtag, ocl_upc, decorative_label, care_label, security_label, additional_label, packing_mode, carton_no_checked) VALUES
																	   ('$iAuditCode', '".IO::strValue("ModelName")."', '$sWorkingNo', '".IO::strValue("FabricApproval")."', '".IO::strValue("CounterSampleAppr")."', '".IO::strValue("SealingSampleAppr")."', '".IO::strValue("GarmentWashingTest")."', '".IO::strValue("MetalDetection")."', '".IO::strValue("ColorShade")."', '".IO::strValue("Appearance")."', '".IO::strValue("Handfeel")."', '".IO::strValue("Printing")."', '".IO::strValue("Embridery")."', '".IO::strValue("FibreContent")."', '".IO::strValue("CountryOfOrigin")."', '".IO::strValue("CareInstruction")."', '".IO::strValue("SizeKey")."', '".IO::strValue("AdiComp")."', '".IO::strValue("ColourSizeQty")."', '".IO::strValue("Polybag")."', '".IO::strValue("Hangtag")."', '".IO::strValue("OclUpc")."', '".IO::strValue("DecorativeLabel")."', '".IO::strValue("CareLabel")."', '".IO::strValue("SecurityLabel")."', '".IO::strValue("AdditionalLabel")."', '".IO::strValue("PackingMode")."', '".IO::strValue("CartonNoChecked")."')");
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					$sSQL = "DELETE FROM tbl_ar_beautiful_products WHERE audit_id='$iAuditCode'";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					$sSQL  = ("INSERT INTO tbl_ar_beautiful_products (audit_id, c1, c2, c3, c4, c5, c6, c7, c8, c9) VALUES
																	 ('$iAuditCode', '".IO::intValue("C1")."', '".IO::intValue("C2")."', '".IO::intValue("C3")."', '".IO::intValue("C4")."', '".IO::intValue("C5")."', '".IO::intValue("C6")."', '".IO::intValue("C7")."', '".IO::intValue("C8")."', '".IO::intValue("C9")."')");
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}
			}


			if ($bFlag == true && ($iReportId == 20 || $iReportId == 23))
			{
				if (getDbValue("COUNT(1)", "tbl_kik_inspection_summary", "audit_id='$iAuditCode'") == 0)
					$sSQL = ("INSERT INTO tbl_kik_inspection_summary (audit_id, qty_of_lots, qty_per_lot, inspection_status) VALUES ('$iAuditCode', '".IO::intValue("QtyOfLots")."', '".IO::intValue("QtyPerLot")."', '".IO::strValue("InspectionStatus")."')");

				else
					$sSQL = ("UPDATE tbl_kik_inspection_summary SET qty_of_lots='".IO::intValue("QtyOfLots")."', qty_per_lot='".IO::intValue("QtyPerLot")."', inspection_status='".IO::strValue("InspectionStatus")."' WHERE audit_id='$iAuditCode'");


				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}
			
			if ($bFlag == true && $iReportId == 35)
			{
				if (getDbValue("COUNT(1)", "tbl_timezone_inspection_summary", "audit_id='$iAuditCode'") == 0)
					$sSQL = ("INSERT INTO tbl_timezone_inspection_summary (audit_id, qty_of_lots, qty_per_lot, inspection_status) VALUES ('$iAuditCode', '".IO::intValue("QtyOfLots")."', '".IO::intValue("QtyPerLot")."', '".IO::strValue("InspectionStatus")."')");

				else
					$sSQL = ("UPDATE tbl_timezone_inspection_summary SET qty_of_lots='".IO::intValue("QtyOfLots")."', qty_per_lot='".IO::intValue("QtyPerLot")."', inspection_status='".IO::strValue("InspectionStatus")."' WHERE audit_id='$iAuditCode'");


				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}
						
			
			if ($bFlag == true && $iReportId == 32)
			{
				if (getDbValue("COUNT(1)", "tbl_arcadia_inspection_summary", "audit_id='$iAuditCode'") == 0)
					$sSQL = ("INSERT INTO tbl_arcadia_inspection_summary (audit_id, qty_of_lots, qty_per_lot, inspection_status) VALUES ('$iAuditCode', '".IO::intValue("QtyOfLots")."', '".IO::intValue("QtyPerLot")."', '".IO::strValue("InspectionStatus")."')");

				else
					$sSQL = ("UPDATE tbl_arcadia_inspection_summary SET qty_of_lots='".IO::intValue("QtyOfLots")."', qty_per_lot='".IO::intValue("QtyPerLot")."', inspection_status='".IO::strValue("InspectionStatus")."' WHERE audit_id='$iAuditCode'");


				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}			
			
			
			if ($bFlag == true && $iReportId == 25)
			{
				if (getDbValue("COUNT(1)", "tbl_bbg_reports", "audit_id='$iAuditCode'") == 1)
				{
					$sSQL = ("UPDATE tbl_bbg_reports SET   trim_access             = '".IO::strValue("TrimAccessoriesDetail")."',
														   test_report             = '".IO::strValue("TestReports")."',
														   label_method            = '".IO::strValue("Labels")."',
														   scw_detail              = '".IO::strValue("StylingDetails")."',
														   dw_test_record          = '".IO::strValue("DryWetRubTestRecord")."',
														   wrap                    = '".IO::strValue("WrapCertificate")."',
														   cspo_detail             = '".IO::strValue("CuttingSizeColorRatio")."',
														   pull_test_report        = '".IO::strValue("PullTestReport")."',
														   fty_approve             = '".IO::strValue("FtyApproved")."',
														   cq_detail               = '".IO::strValue("QaFileDetails")."',
														   wash_test_record        = '".IO::strValue("WashingTestRecord")."',
														   acc_lab                 = '".IO::strValue("AccreditedLab")."',
														   approved_sample         = '".IO::strValue("ApprovedSamples")."',
														   fabric_weight           = '".IO::strValue("FabricWeight")."',
														   pp_meeting_record       = '".IO::strValue("PpMeetingRecord")."',
														   actual_packing_list     = '".IO::strValue("ActualPackingList")."',
														   carton_drop_test_record = '".IO::strValue("CartonDropTestRecord")."',
														   carton_mdw              = '".IO::strValue("CartonMarking")."',
														   shipped_sbdc_ratio      = '".IO::strValue("ShippedSizeColorRatio")."',
														   needle_detect_record    = '".IO::strValue("NeedleDetectionRecord")."',
														   packing_method          = '".IO::strValue("PackingMethod")."',
														   cqnas_details           = '".IO::strValue("CqnasDetails")."',
														   packaging_trims         = '".IO::strValue("PackagingTrims")."'
							  WHERE audit_id='$iAuditCode'");
				}

				else
				{
					$sSQL = ("INSERT INTO tbl_bbg_reports SET  audit_id                = '$iAuditCode',
															   trim_access             = '".IO::strValue("TrimAccessoriesDetail")."',
															   test_report             = '".IO::strValue("TestReports")."',
															   label_method            = '".IO::strValue("Labels")."',
															   scw_detail              = '".IO::strValue("StylingDetails")."',
															   dw_test_record          = '".IO::strValue("DryWetRubTestRecord")."',
															   wrap                    = '".IO::strValue("WrapCertificate")."',
															   cspo_detail             = '".IO::strValue("CuttingSizeColorRatio")."',
															   pull_test_report        = '".IO::strValue("PullTestReport")."',
															   fty_approve             = '".IO::strValue("FtyApproved")."',
															   cq_detail               = '".IO::strValue("QaFileDetails")."',
															   wash_test_record        = '".IO::strValue("WashingTestRecord")."',
															   acc_lab                 = '".IO::strValue("AccreditedLab")."',
															   approved_sample         = '".IO::strValue("ApprovedSamples")."',
															   fabric_weight           = '".IO::strValue("FabricWeight")."',
															   pp_meeting_record       = '".IO::strValue("PpMeetingRecord")."',
															   actual_packing_list     = '".IO::strValue("ActualPackingList")."',
															   carton_drop_test_record = '".IO::strValue("CartonDropTestRecord")."',
															   carton_mdw              = '".IO::strValue("CartonMarking")."',
															   shipped_sbdc_ratio      = '".IO::strValue("ShippedSizeColorRatio")."',
															   needle_detect_record    = '".IO::strValue("NeedleDetectionRecord")."',
															   packing_method          = '".IO::strValue("PackingMethod")."',
															   cqnas_details           = '".IO::strValue("CqnasDetails")."',
															   packaging_trims         = '".IO::strValue("PackagingTrims")."'");
				}

				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);			
			}


			if ($bFlag == true && $iReportId == 11)
			{
				$sDescription = "";
				$sDepartment  = "";

				$sSQL  = ("UPDATE tbl_qa_reports SET batch_size='".IO::strValue("BatchSize")."', packed_percent='".IO::floatValue("PercentPacked")."', description='$sDescription', ship_qty='".IO::intValue("BatchSize")."' WHERE id='$iAuditCode'");
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);


				if (getDbValue("COUNT(1)", "tbl_ms_qa_reports", "audit_id='$iAuditCode'") == 1)
				{
					$sSQL = ("UPDATE tbl_ms_qa_reports SET series         = '".IO::strValue("Series")."',
														   department     = '$sDepartment',
														   big_products   = '".IO::intValue("BigProducts")."',
														   big_size       = '".IO::strValue("BigSize")."',
														   small_products = '".IO::intValue("SmallProducts")."',
														   small_size     = '".IO::strValue("SmallSize")."',
														   action         = '".IO::strValue("Action")."'
							  WHERE audit_id='$iAuditCode'");
				}

				else
				{
					$sSQL = ("INSERT INTO tbl_ms_qa_reports SET audit_id       = '$iAuditCode',
															    series         = '".IO::strValue("Series")."',
															    department     = '$sDepartment',
															    big_products   = '".IO::intValue("BigProducts")."',
															    big_size       = '".IO::strValue("BigSize")."',
															    small_products = '".IO::intValue("SmallProducts")."',
															    small_size     = '".IO::strValue("SmallSize")."',
															    action         = '".IO::strValue("Action")."'");
				}

				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}


			if ($bFlag == true && ($iReportId == 14 || $iReportId == 34))
			{
				if (getDbValue("COUNT(1)", "tbl_mgf_reports", "audit_id='$iAuditCode'") == 1)
				{
					$sSQL = ("UPDATE tbl_mgf_reports SET vpo_no                 = '".IO::strValue("VpoNo")."',
														 reinspection           = '".IO::strValue("ReInspection")."',
														 carton_no              = '".IO::strValue("CartonNo")."',
														 measurement_sample_qty = '".IO::intValue("MeasurementSampleQty")."',
														 measurement_defect_qty = '".IO::intValue("MeasurementDefectQty")."',
														 garment_test           = '".IO::strValue("GarmentTest")."',
														 shade_band             = '".IO::strValue("ShadeBand")."',
														 qa_file                = '".IO::strValue("QaFile")."',
														 fabric_test            = '".IO::strValue("FabricTest")."',
														 pp_meeting             = '".IO::strValue("PpMeeting")."',
														 fitting_torque         = '".IO::strValue("FittingTorque")."',
														 color_check            = '".IO::strValue("ColorCheck")."',
														 accessories_check      = '".IO::strValue("AccessoriesCheck")."',
														 measurement_check      = '".IO::strValue("MeasurementCheck")."'
							  WHERE audit_id='$iAuditCode'");

					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				else
				{
					$sSQL = ("INSERT INTO tbl_mgf_reports SET audit_id               = '$iAuditCode',
															  vpo_no                 = '".IO::strValue("VpoNo")."',
															  reinspection           = '".IO::strValue("ReInspection")."',
														 	  carton_no              = '".IO::strValue("CartonNo")."',
														  	  measurement_sample_qty = '".IO::intValue("MeasurementSampleQty")."',
														  	  measurement_defect_qty = '".IO::intValue("MeasurementDefectQty")."',
															  garment_test           = '".IO::strValue("GarmentTest")."',
															  shade_band             = '".IO::strValue("ShadeBand")."',
															  qa_file                = '".IO::strValue("QaFile")."',
															  fabric_test            = '".IO::strValue("FabricTest")."',
															  pp_meeting             = '".IO::strValue("PpMeeting")."',
															  fitting_torque         = '".IO::strValue("FittingTorque")."',
															  color_check            = '".IO::strValue("ColorCheck")."',
															  accessories_check      = '".IO::strValue("AccessoriesCheck")."',
															  measurement_check      = '".IO::strValue("MeasurementCheck")."'");

					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}
			}
			
			
			if ($bFlag == true && $iReportId == 33)
			{
				if (getDbValue("COUNT(1)", "tbl_gms_reports", "audit_id='$iAuditCode'") == 0)
				{
					$sSQL  = "INSERT INTO tbl_gms_reports (audit_id) VALUES ('$iAuditCode')";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}
			
				if ($bFlag == true)
				{
					$sSQL = ("UPDATE tbl_gms_reports SET fk_panel_qlty        = '',
														 hand_feel            = '".IO::strValue("M1HnadFeel")."',
														 color                = '".IO::strValue("M1Color")."',
														 shade_lot            = '".IO::strValue("M1Shade")."',
														 lining               = '".IO::strValue("M2")."',
														 trim_fabric          = '".IO::strValue("M3")."',
														 interlining          = '".IO::strValue("M4")."',
														 shoulder_pad         = '".IO::strValue("M5")."',
														 washing_effect       = '".IO::strValue("M6")."',
														 down_pouch           = '".IO::strValue("M7")."',
														 padding              = '".IO::strValue("M8")."',    
														 
														 main_label           = '".IO::strValue("T1")."',
														 washing_label        = '".IO::strValue("T2")."',
														 size_label           = '".IO::strValue("T3")."',
														 care_label           = '".IO::strValue("T4")."',
														 int_size_label       = '".IO::strValue("T5")."',
														 
														 price_tag            = '".IO::strValue("P1")."',
														 special_hangtag      = '".IO::strValue("P2")."',
														 tissue_stuffing      = '".IO::strValue("P3")."',
														 polybag              = '".IO::strValue("P4")."',
														 packing_method       = '".IO::strValue("P5")."',    
														 spare_button         = '".IO::strValue("P6")."',
														 info_sticker         = '".IO::strValue("P7")."',
														 packing_assortment   = '".IO::strValue("P8")."',
														 exp_carton_size      = '".IO::strValue("P9")."',
														 exp_carton_weight    = '".IO::strValue("P10")."',
														 carton_label         = '".IO::strValue("P11")."',
														 
														 untrimmed_thread     = '".IO::strValue("A1")."',
														 hand_feel2           = '".IO::strValue("A2")."',														 
														 fit_on_form          = '".IO::strValue("A3")."',
														 twisted              = '".IO::strValue("A4")."',

														 measurement          = '".IO::strValue("O1")."',
														 smell                = '".IO::strValue("O2")."',														 
														 mositure_test_result = '".IO::strValue("O3")."',
														 azo_report_no        = '".IO::strValue("O4")."',

														 please_specify       = '',
														 garment_measurement  = '".IO::strValue("GarmentInspection")."',    
														 moisture_measurement = '".IO::strValue("MoistureInspection")."'
							  WHERE audit_id='$iAuditCode'");
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}
				
				if ($bFlag == true)
				{
					$iShipQty = (int)getDbValue("SUM(quantity)", "tbl_qa_po_ship_quantities", "audit_id='$iAuditCode'");
					
					$sSQL  = "UPDATE tbl_qa_reports SET ship_qty='$iShipQty' WHERE id='$iAuditCode'";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}
			}			
			
			
			if ($bFlag == true && $iReportId == 31)
			{
				if (getDbValue("COUNT(1)", "tbl_hybrid_apparel_reports", "audit_id='$iAuditCode'") == 0)
					$sSQL = ("INSERT INTO tbl_hybrid_apparel_reports (audit_id, total_ctns, fabric, content, weight, rib, label_size, thread) VALUES ('$iAuditCode', '".IO::intValue("TotalCtns")."', '".IO::strValue("Fabric")."', '".IO::strValue("Content")."', '".IO::strValue("Weight")."', '".IO::strValue("Rib")."', '".IO::strValue("LabelSize")."', '".IO::strValue("Thread")."')");

				else
					$sSQL = ("UPDATE tbl_hybrid_apparel_reports SET total_ctns='".IO::intValue("TotalCartons")."', fabric='".IO::strValue("Fabric")."', content='".IO::strValue("Content")."', weight='".IO::strValue("Weight")."', rib='".IO::strValue("Rib")."', label_size='".IO::strValue("LabelSize")."', thread='".IO::strValue("Thread")."' WHERE audit_id='$iAuditCode'");


				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}
			
			
			if ($bFlag == true && $iReportId == 36)
			{
				$sSQL  = ("UPDATE tbl_hybrid_link_reports SET shipment_date       = '".((IO::strValue("ShipmentDate") == "") ? "0000-00-00" : IO::strValue("ShipmentDate"))."', 
															  assortment_qty      = '".IO::intValue("AssortmentCartonQty")."', 
															  assortment_qty_size = '".IO::intValue("AssortmentSizeQty")."', 
															  solid_size_qty      = '".IO::intValue("SolidSizeQty")."', 
															  is_box_full         = '".IO::strValue("SolidSizeType")."', 
															  workmanship_result  = '".IO::strValue("WorkmanshipResult")."'
		                   WHERE audit_id='$iAuditCode'");
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}


			if ($bFlag == true && $sAuditStage == "F" && $AuditResult == "P")
			{
/*
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


					$sSQL  = "UPDATE tbl_vsr SET final_audit_date='$sDate' WHERE po_id IN ($sPos)";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}
*/
			}


			if ($bFlag == true && ($sAuditStage == "F" || $AuditResult == "P" || $AuditResult == "A" || $AuditResult == "B"))
			{
				$sSQL  = "UPDATE tbl_qa_reports SET status='$AuditResult' WHERE id='$iAuditCode' AND status=''";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}


			if ($bFlag == true)
			{
				$objDb->execute("COMMIT", true, $iUser, $sName);

				$aResponse['Status']  = "OK";
				$aResponse['Defects'] = (int)getDbValue("COUNT(1)", "tbl_qa_report_defects", "audit_id='$iAuditCode'");
				$aResponse["Message"] = "Audit Completed Successfully!";


				if ($iReportId != 14 && $iReportId != 34)
				{
					$sAuditResult = $AuditResult;
					$sAuditCode   = $AuditCode;
					$iShipQty     = $ShipQty;



					$sBrand  = getDbValue("brand", "tbl_brands", "id='$iBrandId'");
					$sVendor = getDbValue("vendor", "tbl_vendors", "id='$iVendorId'");
					$sPO     = getDbValue("order_no", "tbl_po", "id='$iPoId'");


					// Alter to Azfar
					if (@in_array($iBrandId, array(67, 75, 242, 244, 260)))
					{
						$objSms = new Sms( );

						$sResult  = (($sAuditResult == "A" || $sAuditResult == "B" || $sAuditResult == "P") ? "Pass" : (($sAuditResult == "F" || $sAuditResult == "C") ? "Fail" : "Hold"));
						$sMessage = "A Report for Style No: {$sStyle}, PO No: {$sPO} at {$sVendor} against {$sBrand} has just been published on the Customer Portal. (Audit Result: {$sResult})";

						if ($iBrandId == 242 || $iBrandId == 244)
						{
//							$objSms->send("+923214300069", "Omer Rauf", "", $sMessage);
							$objSms->send("+923008445912", "Azfar Hasan", "", $sMessage);
						}

						if ((($AuditResult == "F" || $AuditResult == "C") && ($sAuditStage == "F" || $fDhu > 10)) && ($iBrandId == 67 || $iBrandId == 75))
						{
							$objSms->send("+919810228115", "Franklin Benjamin", "", $sMessage);
							$objSms->send("+919873677723", "Avneesh Kumar", "", $sMessage);
						}

						else if ($iBrandId == 242)
						{
							$objSms->send("+491732370864", "Adrian", "", $sMessage);
							$objSms->send("+491726206900", "Rainer", "", $sMessage);
						}

						else if ($iBrandId == 244)
							$objSms->send("+491728876474", "Monika", "", $sMessage);

						else if ($iBrandId == 260)
						{
//							$objSms->send("+923214300069", "Omer Rauf", "", $sMessage);
							$objSms->send("+94773827977", "Sanjaya Anuk Fernando", "", $sMessage);
							$objSms->send("+94773156490", "Udaya Kaviratne", "", $sMessage);
							$objSms->send("+94773902956", "Manoj Balachandran", "", $sMessage);
						}


						$sMessage = ("Click the below link to download the Audit Report: http://portal.3-tree.com/get-qa-report.php?Id=".@md5($AuditCode)."&AuditCode={$AuditCode}");

						if ($iBrandId == 242 || $iBrandId == 244)
						{
//							$objSms->send("+923214300069", "Omer Rauf", "", $sMessage);
							$objSms->send("+923008445912", "Azfar Hasan", "", $sMessage);
						}

						if ((($AuditResult == "F" || $AuditResult == "C") && ($sAuditStage == "F" || $fDhu > 10)) && ($iBrandId == 67 || $iBrandId == 75))
						{
							$objSms->send("+919810228115", "Franklin Benjamin", "", $sMessage);
							$objSms->send("+919873677723", "Avneesh Kumar", "", $sMessage);
						}

						else if ($iBrandId == 242)
						{
							$objSms->send("+491732370864", "Adrian", "", $sMessage);
							$objSms->send("+491726206900", "Rainer", "", $sMessage);
						}

						else if ($iBrandId == 244)
							$objSms->send("+491728876474", "Monika", "", $sMessage);

						else if ($iBrandId == 260)
						{
//							$objSms->send("+923214300069", "Omer Rauf", "", $sMessage);
							$objSms->send("+94773827977", "Sanjaya Anuk Fernando", "", $sMessage);
							$objSms->send("+94773156490", "Udaya Kaviratne", "", $sMessage);
							$objSms->send("+94773902956", "Manoj Balachandran", "", $sMessage);

/*
							$sStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");
							$sLink  = ("http://portal.3-tree.com/get-qa-report.php?Id=".@md5($AuditCode)."&AuditCode={$AuditCode}");

							$sBody = "Dear User,<br /><br />
									  Please <a href='{$sLink}'>click here</a> for a PDF version of the Audit Report for:<br />
									  Audit Code: {$AuditCode}<br />
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

//							$objEmail->AddAddress("omer@3-tree.com", "Omer Rauf");
							$objEmail->AddAddress("sanfernando@mgfsourcing.com", "Sanjaya Anuk Fernando");
							$objEmail->addBCC("darho@mgfsourcing.com", "Ho, Darren");
							$objEmail->addBCC("STan@MGFSourcing.com", "Tan, Samuel");
							$objEmail->addBCC("switharana@mgfsourcing.com", "Shehan Witharana");
							$objEmail->addBCC("ASumanadasa@MGFSourcing.com", "Aruna Sumanadasa");
							$objEmail->AddAddress("ukaviratne@mgfsourcing.com", "Udaya Kaviratne");
							$objEmail->AddAddress("Balachandran@mgfsourcing.com", "Manoj Balachandran");
//							$objEmail->addBCC("rstephen@mgfsourcing.com", "Stephen Ranga");
							$objEmail->addBCC("Jjeyaram@mgfsourcing.com", "Jeyaram, Jeyadinesh");

							$objEmail->Send( );
*/
						}

						$objSms->close( );
					}


					// Notifications
					if ($sAuditStage == "F")
					{
						// Final Audit Conducted
						@include(ABSOLUTE_PATH."includes/sms/final-audit.php");


						// Final Audit Approval
						if ($sAuditResult == "P" || $sAuditResult == "A")
							@include(ABSOLUTE_PATH."includes/sms/final-audit-approval.php");
					}

					else
					{
						if (!@in_array($sAuditResult, array("P", "A", "B")))
							@include(ABSOLUTE_PATH."includes/sms/inline-audit.php");
						
						// Inline Audit Alerts
						if ($sAuditStage == "I" || $sAuditStage == "IL" || $sAuditStage == "B")
							@include($sBaseDir."includes/sms/inline-audit-alert.php");
					}	
				}
			}

			else
			{
				$aResponse["Message"] = mysql_error(); //"An ERROR occured, please try again.";

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
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>