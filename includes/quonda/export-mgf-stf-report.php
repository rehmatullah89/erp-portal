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

	@require_once($sBaseDir."requires/tcpdf/tcpdf.php");
	@require_once($sBaseDir."requires/fpdi2/fpdi.php");
	@require_once($sBaseDir."requires/qrcode/qrlib.php");
        
	$sSQL = "SELECT *,
				(SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
                (SELECT etd_required FROM tbl_po_colors WHERE po_id=tbl_qa_reports.po_id ORDER BY id LIMIT 1) AS _GacDate,
				(SELECT mgf_vendor FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _MgfVendor,
				(SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
				(SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor
		 FROM tbl_qa_reports
		 WHERE id='$Id'";
	$objDb->query($sSQL);

	$iMasterId          = $objDb->getField(0, "master_id");
	$iReportId          = $objDb->getField(0, "report_id");
	$iVendor            = $objDb->getField(0, "vendor_id");
	$sVendor            = $objDb->getField(0, "_Vendor");
	$sMgfVendor         = $objDb->getField(0, "_MgfVendor");
	$iAuditor           = $objDb->getField(0, "user_id");
	$sAuditor           = $objDb->getField(0, "_Auditor");
	$iPo                = $objDb->getField(0, "po_id");
	$iAdditionalPos     = $objDb->getField(0, "additional_pos");
	$sPo                = $objDb->getField(0, "_Po");
	$iStyle             = $objDb->getField(0, "style_id");
	$sColors            = $objDb->getField(0, "colors");
	$sSizes             = $objDb->getField(0, "sizes");
	$sGacDate           = $objDb->getField(0, "_GacDate");
	$sAuditType         = $objDb->getField(0, "audit_type");
	$sAuditStatus       = $objDb->getField(0, "audit_status");
	$sAuditCode         = $objDb->getField(0, "audit_code");
	$sAuditDate         = $objDb->getField(0, "audit_date");
	$sStartTime         = $objDb->getField(0, "start_time");
	$sEndTime           = $objDb->getField(0, "end_time");
	$sAuditStage        = $objDb->getField(0, "audit_stage");
	$sAuditResult       = $objDb->getField(0, "audit_result");
	$sCustomSample      = $objDb->getField(0, "custom_sample");
	$iTotalGmts         = $objDb->getField(0, "total_gmts");
	$iGmtsDefective     = $objDb->getField(0, "defective_gmts");
	$iMaxDefects        = $objDb->getField(0, "max_defects");
	$iTotalCartons      = $objDb->getField(0, "total_cartons");
	$iCartonsRejected   = $objDb->getField(0, "rejected_cartons");
	$fPercentDecfective = $objDb->getField(0, "defective_percent");
	$fStandard          = $objDb->getField(0, "standard");
	$fCartonsRequired   = $objDb->getField(0, "cartons_required");
	$fCartonsShipped    = $objDb->getField(0, "cartons_shipped");
	$iShipQty           = $objDb->getField(0, "ship_qty");
	$sApprovedSample    = $objDb->getField(0, "approved_sample");
	$sShippingMark      = $objDb->getField(0, "shipping_mark");
	$sPackingCheck	    = $objDb->getField(0, "packing_check");
	$sCartonSize  	    = $objDb->getField(0, "carton_size");
	$fKnitted           = $objDb->getField(0, "knitted");
	$fDyed              = $objDb->getField(0, "dyed");
	$iCutting           = $objDb->getField(0, "cutting");
	$iSewing            = $objDb->getField(0, "sewing");
	$iFinishing         = $objDb->getField(0, "finishing");
	$iPacking           = $objDb->getField(0, "packing");
	$sFinalAuditDate    = $objDb->getField(0, "final_audit_date");
	$sComments          = $objDb->getField(0, "qa_comments");
	$iLine              = $objDb->getField(0, "line_id");
	$fDhu               = $objDb->getField(0, "dhu");
	$fAql               = $objDb->getField(0, "aql");
	$iInspectionLevel   = $objDb->getField(0, "inspection_level");
	$sCreatedAt         = $objDb->getField(0, "created_at");

	if ($iAdditionalPos != '')
		$sAllPos = ($iPo.','.$iAdditionalPos);

	else
		$sAllPos = $iPo;
	

	@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);


	$sSpecsSheets = array( );

	for ($i = 1; $i <= 10; $i ++)
	{
		$sSpecsSheet = $objDb->getField(0, "specs_sheet_{$i}");

		if ($sSpecsSheet != "")
		{
			if (@file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet))
				$sSpecsSheets[] = ($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet);
			
			else if (@file_exists($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsSheet))
				$sSpecsSheets[] = ($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsSheet);
		}
	}	

	$sSpecsDir  = ($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/");
	$sLabImages    = getList("tbl_qa_report_images", "id", "image", "audit_id='$Id' AND `type`='L'");
	foreach ($sLabImages as $sImage)
	{
			if (@file_exists($sSpecsDir.$sImage))
					$sSpecsSheets[] = ($sSpecsDir.$sImage);
	}

	
	$sAuditorType  = getDbValue("auditor_type", "tbl_users", "id='$iAuditor'");       
	$iStageReports = (int)getDbValue("COUNT(1)", "tbl_qa_reports qa, tbl_mgf_reports mgf", "qa.id=mgf.audit_id AND mgf.reinspection='Y' AND qa.master_id='$iMasterId' AND qa.audit_stage='$sAuditStage' AND qa.created_at <= '$sCreatedAt' AND qa.user_id IN (SELECT id FROM tbl_users WHERE user_type='MGF' AND auditor_type='$sAuditorType')");	
	$iStageReports = (($iStageReports == 0) ? 1 : ($iStageReports + 1));                                                                          

	

	$sSQL = "SELECT * FROM tbl_mgf_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$sVpoNo                = $objDb->getField(0, "vpo_no");
        $sReInspection         = $objDb->getField(0, "reinspection");
	$sArticleNo            = $objDb->getField(0, "article_no");
	$sGarmentTest          = $objDb->getField(0, "garment_test");
	$sShadeBand            = $objDb->getField(0, "shade_band");
	$sQaFile               = $objDb->getField(0, "qa_file");
	$sFabricTest           = $objDb->getField(0, "fabric_test");
	$sPpMeeting            = $objDb->getField(0, "pp_meeting");
	$sFittingTorque        = $objDb->getField(0, "fitting_torque");
	$sColorCheck           = $objDb->getField(0, "color_check");
	$sAccessoriesCheck     = $objDb->getField(0, "accessories_check");
	$sMeasurementCheck     = $objDb->getField(0, "measurement_check");
	$sCapOthers            = $objDb->getField(0, "cap_others");
	$sCartonNo             = $objDb->getField(0, "carton_no");
	$iMeasurementSampleQty = $objDb->getField(0, "measurement_sample_qty");
	$iMeasurementDefectQty = $objDb->getField(0, "measurement_defect_qty");


	$iQuantity      = getDbValue("SUM(quantity)", "tbl_po_quantities", "po_id='$iPo'");
        
        $iColorts = explode(",", $sColors);        
        if(count($iColorts) == 1)
            $sEtdRequired   = getDbValue("MIN(etd_required)", "tbl_po_colors", "po_id='$iPo' AND color LIKE '$sColors'");
        else
           $sEtdRequired   = getDbValue("MIN(etd_required)", "tbl_po_colors", "po_id='$iPo'");
        
	$sAdditionalPos = "";

	
	$sSQL = "SELECT CONCAT(order_no, ' ', order_status), quantity FROM tbl_po WHERE id IN ($iAdditionalPos) ORDER BY order_no";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAdditionalPos .= (",".$objDb->getField($i, 0));
		$iQuantity      += $objDb->getField($i, 1);
	}


	$sSQL = "SELECT style, style_name, brand_id, sub_brand_id, (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand FROM tbl_styles WHERE id='$iStyle'";
	$objDb->query($sSQL);

	$sStyle       = $objDb->getField(0, "style");
	$sDescription = $objDb->getField(0, "style_name");
	$iParent      = $objDb->getField(0, "brand_id");
	$iBrand       = $objDb->getField(0, "sub_brand_id");
	$sBrand       = $objDb->getField(0, "_Brand");



	$sSizeTitles = "";

	$sSQL = "SELECT size FROM tbl_sizes WHERE id IN ($sSizes) ORDER BY position";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sSizeTitles .= (", ".$objDb->getField($i, 0));

		$sSizeTitles = substr($sSizeTitles, 2);
	}


	$fHours       = getDbValue("c.hours", "tbl_countries c, tbl_vendors v", "c.id = v.country_id AND v.id='$iVendor'");
	$sStartTime   = date("H:i A", (strtotime($sStartTime) + ($fHours * 3600)));
	$sEndTime     = date("H:i A", (strtotime($sEndTime) + ($fHours * 3600)));

	// $sMeasurementCodes = "S.01,S.02,S.03,P.01,P.02,P.03,P.04";
	// $sWorkmanshipCodes = "G.01,G.02,G.03,G.04,G.05,G.06,G.07,G.08,G.09,G.10,G.11,G.12,G.13,G.14,G.15,G.16,G.17,G.18,G.19,G.20,G.21,G.22,G.23,G.24,G.25,G.26,G.27,B.01,B.02,B.03,B.04,B.05,B.06,B.07,B.08,B.09,K.01,K.02,K.03,K.04,K.05,K.06";
	// $sMaterialCodes    = "M.01,M.02,M.03,M.04,M.05,M.06,M.07,M.08,M.09,M.10,M.11,M.12,M.13,M.14";

	$sMeasurementCodes = "S.01.2.STF01,S.01.2.STF02,S.01.2.STF03,S.01.2.STF04,S.01.2.STF05,S.01.2.STF06,S.01.2.STF07,S.01.2.STF08,S.01.2.STF09,S.02.6.STF01,S.03.6.STF01,P.02.4.STF01,P.02.4.STF02,P.02.4.STF03,P.02.4.STF04,P.03.4.STF01,P.03.4.STF02,P.03.4.STF03,P.03.4.STF04,P.03.4.STF05,P.03.4.STF06,P.03.4.STF07,P.03.4.STF08,P.03.4.STF09,P.03.4.STF10";
	$sWorkmanshipCodes = "G.02.5.STF01,G.02.5.STF02,G.03.1.STF01,G.03.1.STF02,G.04.1.STF01,G.04.1.STF02,G.05.1.STF01,G.05.1.STF02,G.05.1.STF03,G.05.1.STF04,G.05.1.STF05,G.05.1.STF06,G.06.1.STF01,G.06.1.STF02,G.06.1.STF03,G.06.1.STF04,G.06.1.STF05,G.06.1.STF06,G.06.1.STF07,G.06.1.STF08,G.06.1.STF09,G.06.1.STF10,G.06.1.STF11,G.06.1.STF12,G.06.2.STF13,G.06.2.STF14,G.06.2.STF15,G.06.2.STF16,G.06.2.STF17,G.06.4.STF18,G.06.5.STF19,G.06.5.STF20,G.06.5.STF21,G.06.5.STF22,G.06.5.STF23,G.06.5.STF24,G.06.5.STF25,G.06.5.STF26,G.06.5.STF27,G.06.5.STF28,G.06.5.STF29,G.06.5.STF30,G.06.6.STF31,G.06.6.STF32,G.06.6.STF33,G.06.6.STF34,G.06.6.STF35,G.06.6.STF36,G.06.6.STF37,G.06.6.STF38,G.06.6.STF39,G.06.6.STF40,G.06.6.STF41,G.06.6.STF42,G.07.3.STF01,G.07.3.STF02,G.07.5.STF03,G.09.5.STF01,G.09.6.STF02,G.10.3.STF01,G.12.1.STF01,G.12.3.STF02,G.12.5.STF03,G.12.5.STF04,G.13.3.STF01,G.13.5.STF02,G.14.5.STF01,G.15.1.STF01,G.16.2.STF01,G.16.2.STF02,G.16.3.STF03,G.16.3.STF04,G.16.3.STF05,G.16.3.STF06,G.16.4.STF07,G.16.5.STF08,G.16.5.STF09,G.16.5.STF10,G.16.5.STF11,G.16.6.STF12,G.16.6.STF13,G.16.6.STF14,G.16.6.STF15,G.16.6.STF16,G.16.6.STF17,G.16.6.STF18,G.16.6.STF19,G.16.6.STF20,G.16.6.STF21,G.16.6.STF22,G.18.5.STF01,G.18.5.STF02,G.18.5.STF03,G.20.6.STF01,G.20.6.STF02,G.21.5.STF01,G.22.2.STF01,G.22.5.STF02,G.23.6.STF01,G.24.5.STF01,G.26.2.STF01,G.26.2.STF02,G.26.5.STF03,G.26.5.STF04,G.26.5.STF05,G.26.5.STF06,G.26.5.STF07,G.26.6.STF08,G.26.6.STF09,G.26.6.STF10,K.01.2.STF01,K.01.3.STF02,K.02.1.STF01,K.02.2.STF02,K.02.2.STF03,K.02.3.STF04,K.02.3.STF05,K.02.3.STF06,K.02.3.STF07,K.02.3.STF08,K.02.3.STF09,K.02.3.STF10,K.02.3.STF11,K.02.3.STF12,K.02.3.STF13,K.02.3.STF14,K.02.3.STF15,K.02.3.STF16,K.02.3.STF17,K.02.3.STF18,K.02.5.STF19,K.02.5.STF20,K.02.5.STF21,K.02.5.STF22,K.02.5.STF23,K.02.5.STF24,K.02.6.STF25,K.02.6.STF26,K.02.6.STF27,K.03.2.STF01,K.03.5.STF02,K.03.5.STF03,K.03.5.STF04,K.03.5.STF05,K.03.5.STF06,K.03.5.STF07,K.03.5.STF08,K.03.5.STF09,K.03.5.STF10,K.03.5.STF11,K.03.5.STF12,K.03.5.STF13,K.03.5.STF14,K.03.5.STF15,K.03.5.STF16,K.03.6.STF17,K.03.6.STF18,K.03.6.STF19,K.03.6.STF20,K.05.3.STF01,K.06.3.STF01";
	$sMaterialCodes    = "M.01.3.STF01,M.03.3.STF01,M.03.3.STF02,M.03.3.STF03,M.03.3.STF04,M.03.3.STF05,M.03.3.STF06,M.03.3.STF07,M.03.3.STF08,M.03.3.STF09,M.03.3.STF10,M.03.3.STF11,M.03.3.STF12,M.03.3.STF13,M.03.3.STF14,M.03.3.STF15,M.03.3.STF16,M.03.3.STF17,M.03.3.STF18,M.03.6.STF19,M.04.3.STF01,M.04.3.STF02,M.04.3.STF03,M.06.3.STF01,M.06.3.STF02,M.06.3.STF03,M.06.3.STF04,M.06.3.STF05,M.06.5.STF06,M.07.3.STF01,M.08.2.STF01,M.08.2.STF02,M.08.3.STF03,M.08.6.STF04,M.09.3.STF01,M.09.3.STF02,M.10.3.STF01,M.10.3.STF02,M.11.1.STF01,M.11.1.STF02,M.11.1.STF03,M.11.1.STF04,M.11.1.STF05,M.11.1.STF06,M.11.1.STF07,M.11.1.STF08,M.11.1.STF09,M.11.1.STF10,M.11.1.STF11,M.11.1.STF12,M.11.1.STF13,M.11.1.STF14,M.11.1.STF15,M.11.1.STF16,M.11.1.STF17,M.11.1.STF18,M.11.1.STF19,M.11.1.STF20,M.11.1.STF21,M.11.5.STF22,M.11.5.STF23,M.11.6.STF24,M.11.6.STF25,M.11.6.STF26,M.12.3.STF01";

	
	$iMinorDefects        = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sMeasurementCodes')) AND nature='0'");
	$iMajorDefects        = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sMeasurementCodes')) AND nature='1'");
	$iCriticalDefects     = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sMeasurementCodes')) AND nature='2'");

	$sRejectedMeasurement = "CR:{$iCriticalDefects},  MA:{$iMajorDefects},  MI:{$iMinorDefects}";


	$iMinorDefects        = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sWorkmanshipCodes')) AND nature='0'");
	$iMajorDefects        = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sWorkmanshipCodes')) AND nature='1'");
	$iCriticalDefects     = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sWorkmanshipCodes')) AND nature='2'");

	$sRejectedWorkmanship = "CR:{$iCriticalDefects},  MA:{$iMajorDefects},  MI:{$iMinorDefects}";


	$iMinorDefects     = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sMaterialCodes')) AND nature='0'");
	$iMajorDefects     = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sMaterialCodes')) AND nature='1'");
	$iCriticalDefects  = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sMaterialCodes')) AND nature='2'");

	$sRejectedMaterial = "CR:{$iCriticalDefects},  MA:{$iMajorDefects},  MI:{$iMinorDefects}";

	$sCodes = array("M.01", "M.02", "M.03", "0", "M.04", "M.05", "M.06", "0", "M.07", "M.08", "M.09", "M.10", "0", "M.11", "M.12", "M.13", "0", "0", "M.14", "0", "0", "S.01", "S.02", "S.03",
					"G.01", "G.02", "G.03", "G.04", "G.05", "G.06", "G.07", "G.08", "G.09", "G.10", "G.11", "G.12", "G.13", "G.14", "G.15", "G.16", "G.17", "G.18", "G.19", "G.20", "G.21", "G.22", "G.23", "G.24", "G.25", "G.26", "G.27",
					"B.01", "B.02", "B.03", "B.04", "0", "0", "B.05", "B.06", "B.07", "B.08", "B.09", "0", "0", "K.01", "K.02", "K.03", "K.04", "K.05", "K.06", "0", "0", "P.01", "P.02", "P.03", "P.04");


        $sDefectPictures = getList("tbl_qa_report_defects", "picture", "picture", "audit_id='$Id'");
//        $sDefectPictures = array_map('strtoupper', $sDefectPictures);
        
	$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_001_*.*");
	$sPictures = @array_merge($sPictures, @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*"));
//	$sPictures = @array_map("strtoupper", $sPictures);
	$sPictures = @array_unique($sPictures);

	$sDefects = array( );
	$sPacking = array( );
	$sMisc    = array( );

	foreach ($sPictures as $sPicture)
	{
		$sPic = @basename($sPicture);
                
		if (@in_array($sPic, $sDefectPictures))
			$sDefects[] = $sPicture;

		else if (@stripos($sPic, "_pack_") !== FALSE || @stripos($sPic, "_001_") !== FALSE)
			$sPacking[] = $sPicture;

		else /*if (@stripos($sPic, "_misc_") !== FALSE || @stripos($sPic, "_00_") !== FALSE || @substr_count($sPic, "_") < 3)*/
			$sMisc[] = $sPicture;
	}

	$iTotalPages  = 1;
	$iTotalPages += count($sSpecsSheets);
	$iTotalPages += @ceil(count($sPacking) / 4);
	$iTotalPages += @ceil(count($sDefects) / 4);
	$iTotalPages += @ceil(count($sMisc) / 4);


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$objPdf = new FPDI( );
	$objPdf->setPrintHeader(false);
	$objPdf->setPrintFooter(false);
	$objPdf->setSourceFile($sBaseDir."templates/mgf-stf.pdf");
	
	$iTemplate = $objPdf->importPage(1);

  $sSQL2 = "SELECT qad.code_id, qad.sample_no, qad.defects, qad.area_id, qad.picture,
          dc.type_id, dc.code, dc.defect, qad.nature, dt.type, qad.cap 
          FROM tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
          WHERE qad.audit_id='$Id' AND qad.code_id=dc.id AND dc.type_id=dt.id
          GROUP BY qad.code_id,qad.area_id
          ORDER BY dt.type,qad.code_id DESC";

	$objDb2->query($sSQL2);

	$iCount2 = $objDb2->getCount( );

	$workManReminder = $iCount2%9;
	$workManPages = 0;
	$defectsFound = 1;

	$workManCount = $iCount2-$workManReminder;
	
	if($workManCount > 0){

		$workManPages = $workManCount/9;
	}

	if($workManReminder != 0 && $workManReminder <9){
		$workManPages++;
	}

	if($workManPages == 0){
		$workManPages = 1;
		$defectsFound = 0;
	}

	$defectCounter = 1;

	for($j=0; $j<$workManPages; $j++) {

		$iCurrentPage++;

		if($j == 0){
			$startValue = 0;
			$endValue = 8;
		} else {
			$startValue = $startValue+8;
			$endValue = $endValue+8;
		}

		$objPdf->AddPage( );
		$objPdf ->useTemplate($iTemplate, null, null, 0, 0, true);

		$objPdf->SetFont('stsongstdlight', '', 7);
		$objPdf->SetTextColor(0, 0, 0);

		if($sComments == 'N/A')
			$sComments = '';

		$objPdf->SetXY(20, 210.7);
		$objPdf->setCellHeightRatio(1.7);
		$objPdf->MultiCell(185, 1, substr(preg_replace( "/\r|\n/", " ", trim($sComments)), 0, 820), 0, "L");

		// QR Code
		QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

		$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 188, 1, 20);

		// Report Details
		$objPdf->SetFont('helvetica', '', 7);
		$objPdf->SetTextColor(50, 50, 50);

		$objPdf->SetXY(182, 13);
		$objPdf->Cell(20, 21, $iMasterId);

		$objPdf->SetXY(182, 20);
		$objPdf->Cell(20, 21, $sAuditCode);

	  if ($sAuditStage == "SE")
		{
			$objPdf->SetXY(32.5, 28);
			$objPdf->write(0, $iStageReports);
		}
	        
		else if ($sAuditStage == "PR")
		{
			$objPdf->SetXY(53, 28);
			$objPdf->write(0, $iStageReports);
		}	        
		else if ($sAuditStage == "TP")
		{
			$objPdf->SetXY(146.3, 28);
			$objPdf->write(0, $iStageReports);
		}
		else if ($sAuditStage == "F")
		{
			$objPdf->SetXY(124, 28);
			$objPdf->write(0, $iStageReports);

			// $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 127, 28.5, 3);			
		}

	        else if ($sAuditStage == "ID" || $sAuditStage == "II")
		{
			$objPdf->SetXY(70.5, 28);
			$objPdf->write(0, $iStageReports);

			if($sAuditStage == "ID"){
				$stageText = "Inline (DPI)";
			} else {
				$stageText = "Inline (IPI)";
			}

			$objPdf->SetXY(98, 28);
			$objPdf->write(0, $stageText);			
		}
	        
		else
		{
			$objPdf->SetXY(69, 28);
			$objPdf->write(0, $iStageReports);

			$objPdf->SetXY(96, 28);
			$objPdf->write(0, getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'"));
		}


		$objPdf->SetXY(25, 36.0);
		$objPdf->write(0, ($sPo.$sAdditionalPos));

		$objPdf->SetXY(33, 40.2);
		$objPdf->write(0, $sBrand);

		$objPdf->SetXY(97, 40.8);
		$objPdf->write(0, $sStyle);

		$objPdf->SetXY(135, 40.8);
		$objPdf->write(0, formatNumber($iQuantity, false));

		$objPdf->SetXY(183, 40.8);
		$objPdf->write(0, (($iShipQty == 0) ? "N/A" : formatNumber($iShipQty, false)));
	   
	        
		$objPdf->SetXY(25, 44.4);
		$objPdf->write(0, $sMgfVendor);

		$objPdf->SetXY(175, 44.8);
		$objPdf->write(0, date('d-M-Y',strtotime($sGacDate)));

		$objPdf->SetXY(25, 48.8);
		$objPdf->write(0, $sVendor);

		$objPdf->SetXY(135.5, 49.2);
		$objPdf->write(0, formatNumber($fAql, true, (($fAql < 1) ? 2 : 1)));
		
		$sLevel = "II";
		
		switch ($iInspectionLevel)
		{
			case 1 : $sLevel = "I"; break;
			case 2 : $sLevel = "II"; break;
			case 3 : $sLevel = "III"; break;
			case 4 : $sLevel = "S-1"; break;
			case 5 : $sLevel = "S-2"; break;
			case 6 : $sLevel = "S-3"; break;
			case 7 : $sLevel = "S-4"; break;
		}
		
		$objPdf->SetXY(172, 49.2);
		$objPdf->write(0, $sLevel);

		$objPdf->SetXY(25, 53.5);
		$objPdf->write(0, $sDescription);


		if (@isset($iAqlChart["{$iTotalGmts}"]["{$fAql}"]))
			$iMaxDefects = $iAqlChart["{$iTotalGmts}"]["{$fAql}"];

		else
		{
			foreach ($iAqlChart as $iSampleSize => $sAqlDetails)
			{
				if ($iTotalGmts >= $sAqlDetails["F"] && $iTotalGmts <= $sAqlDetails["T"])
				{
					$iMaxDefects = $iAqlChart["{$iSampleSize}"]["{$fAql}"];

					break;
				}
			}
		}

		$objPdf->SetXY(18, 59.0);
		$objPdf->setCellHeightRatio(1.0);
		$objPdf->write(0, $sColors);

		
		$objPdf->setCellHeightRatio(1.30);
		$objPdf->SetXY(30.8, 62.8);
		$objPdf->write(0, $sAuditor);
        
	  if ($sReInspection == "Y")
	    $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 84, 63.8, 3);

		$objPdf->SetXY(125, 62.8);
		$objPdf->write(0, (($sCustomSample == "Y") ? "CUSTOM" : $iTotalGmts));

		$objPdf->SetXY(167, 62.8);
		$objPdf->write(0, $iMaxDefects);

		$objPdf->SetXY(25, 67);
		$objPdf->write(0, date('d-M-Y',strtotime($sAuditDate)));

		$objPdf->SetXY(153.5, 67);
		$objPdf->write(0, $sRejectedMeasurement);

		$objPdf->SetXY(31.5, 71.3);
		$objPdf->write(0, $sStartTime);

		$objPdf->SetXY(153.5, 71);
		$objPdf->write(0, $sRejectedWorkmanship);

		$objPdf->SetXY(31.5, 75.5);
		$objPdf->write(0, $sEndTime);

		$objPdf->SetXY(153.5, 75.4);
		$objPdf->write(0, $sRejectedMaterial);

		if ($sApprovedSample == "Yes")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 84.2, 67.3, 4);

		if ($sGarmentTest == "Y")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 112.6, 67.3, 4);

		if ($sShadeBand == "Y")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 84.2, 71.3, 4);

		if ($sFabricTest == "Y")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 112.6, 71.3, 4);

		if ($sQaFile == "Y")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 84.2, 75.3, 4);

		if ($sPpMeeting == "Y")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 112.6, 75.3, 4);


		$sInlineDetails  = ("% Cutting/Knitting: ".(($iCutting == 0) ? "NA" : formatNumber($iCutting, false)));
		$sInlineDetails .= (", % Sewing/Linking: ".(($iSewing == 0) ? "NA" : formatNumber($iSewing, false)));
		$sInlineDetails .= (", % Finishing: ".(($iFinishing == 0) ? "NA" : formatNumber($iFinishing, false)));
		$sInlineDetails .= (", % Packed: ".(($iPacking == 0) ? "NA" : formatNumber($iPacking, false)));

		$objPdf->SetXY(14, 79.9);
		$objPdf->Cell(200, 0, $sInlineDetails, 0, 0);

		$objPdf->SetFont('helvetica', '', 5);
	        
		$objPdf->SetXY(51, 83.7);
	  $objPdf->MultiCell(150, 3.2, $sCartonNo, 0, "L", false);

		$objPdf->SetFont('helvetica', '', 7);

		$objPdf->SetXY(27, 199.5);
		$objPdf->setCellHeightRatio(1.7);
		$objPdf->MultiCell(186, 1, substr(preg_replace( "/\r|\n/", " ", trim($sCapOthers)), 0, 820), 0, "L");

		$objPdf->SetFont('helvetica', '', 6);
			
		if ($sFittingTorque == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 60, 229, 4);

		if ($sColorCheck == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 92, 229, 4);

		if ($sAccessoriesCheck == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 149, 229, 4);

		if ($sMeasurementCheck == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 189, 229, 4);

		if ($sAuditResult == "P")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 66.5, 243.5, 4);

		else if ($sAuditResult == "F")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 106, 243.5, 4);

		else if ($sAuditResult == "H")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 140, 243.5, 4); 

      $objPdf->SetXY(85, 235);
			$objPdf->write(0, formatNumber($iMeasurementSampleQty, false));
       
      $objPdf->SetXY(150, 235);
			$objPdf->write(0, formatNumber($iMeasurementDefectQty,false));

		if($defectsFound == '1'){

				$objPdf->SetXY(5, 92);

				$capY = 184;
				$sCapCount = 1;

				for($i=$startValue; $i<$endValue; $i++) {

					if($defectCounter > $iCount2){
						break;
					}

		      $iSampleNo = $objDb2->getField($i, "sample_no");
		      $iDefects  = $objDb2->getField($i, "defects");
		      $iArea     = $objDb2->getField($i, "area_id");
		      $sPicture  = $objDb2->getField($i, "picture");
		      $iType     = $objDb2->getField($i, "type_id");
		      $sCode     = $objDb2->getField($i, "code");
		      $sDefect   = $objDb2->getField($i, "defect");
		      $iNature   = $objDb2->getField($i, "nature");
		      $sType     = $objDb2->getField($i, "type");
		      $iCode     = $objDb2->getField($i, "code_id");
		      $sCap     = $objDb2->getField($i, "cap");

		      $sArea = getDbValue("area","tbl_defect_areas","id = '$iArea'");

		      if($previousDefectType != $sType){

						$y = $objPdf->GetY()+3.7;
						$objPdf->Text(26, $y, $sType);

						$previousDefectType = $sType;
		      }

					$y = $y+3.7;
					$objPdf->Text(10, $y, $sCode);
					$objPdf->Text(30, $y, $sDefect);
					$objPdf->Text(112, $y, $sArea);

		      switch ($iNature)
		      {
		        case 0 : $minorCount++; $sNature="Minor";  break;         
		        case 1 : $majorCount++; $sNature="Major";  break;
		        case 2 : $criticalCount++; $sNature="Critical"; break;
		      }      

		      $sSQL3 = "SELECT 
		      					(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id='$Id' AND code_id='$iCode' AND area_id = '$iArea' AND nature='0') AS _MinorDefects,
										(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id='$Id' AND code_id='$iCode' AND area_id = '$iArea' AND nature='1') AS _MajorDefects,
										(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id='$Id' AND code_id='$iCode' AND area_id = '$iArea' AND nature='2') AS _CriticalDefects 
										FROM tbl_qa_report_defects qad 
										WHERE  qad.audit_id='$Id' AND qad.code_id='$iCode' AND qad.area_id = '$iArea'
										GROUP BY qad.code_id";

					$objDb3->query($sSQL3);

					$CR = $objDb3->getField(0, "_CriticalDefects");
					$MJ = $objDb3->getField(0, "_MajorDefects");
					$MI = $objDb3->getField(0, "_MinorDefects");

					$objPdf->Text(161, $y, $CR);
					$objPdf->Text(176, $y, $MJ);
					$objPdf->Text(191, $y, $MI);

					if($sCap != "" && $sCapCount < 6 ) {

						$objPdf->Text(10, $capY, $sCode);
						$objPdf->Text(27, $capY, $sCap);

						$capY = $capY+3.3;
						$sCapCount++;
					}

					$objPdf->SetXY(192, $y+0.4);

					$defectCounter++;           
			}
		}
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 2  -  DEFECT CODE LISTING
/*
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/mgf-page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');

		$objPdf->addPage("P", "A4");
		$objPdf->useTemplate($iTemplateId, 0, 0);

		QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

		$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 188, 1, 20);

		// Report Details
		$objPdf->SetFont('helvetica', '', 7);
		$objPdf->SetTextColor(50, 50, 50);

		$objPdf->SetXY(182, 13);
		$objPdf->Cell(20, 21, $iMasterId);

		$objPdf->SetXY(182, 20);
		$objPdf->Cell(20, 21, $sAuditCode);

		$objPdf->SetXY(5,25);

		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(0, 0, 0);

		$sDefectTypesList = getList("tbl_defect_types", "id", "type", "id IN (SELECT DISTINCT(type_id) FROM tbl_defect_codes WHERE report_id = '$iReportId')");

		$x = 5;
		$totalLength = 0;

		foreach ($sDefectTypesList as $id => $type) {

			$sDefectCodes = getList("tbl_defect_codes dc, tbl_defect_types dt ", "dc.code", "dc.defect", "dc.type_id = dt.id AND dc.type_id = '$id' AND dc.report_id = '$iReportId'","dc.code");

			$y = $objPdf->GetY()+10;
			
			$lowLevel = $objPdf->GetY() + (count($sDefectCodes) * 3.6);

			if($lowLevel > 250) {
				$x = 110;
				$y = 35;
				$totalLength++;
			}

			if($totalLength == '2'){

				$totalLength = 0;	

				$objPdf->addPage("P", "A4");
				$objPdf->useTemplate($iTemplateId, 0, 0);

				QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

				$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 188, 1, 20);

				$objPdf->SetFont('Arial', '', 7);
				$objPdf->SetTextColor(50, 50, 50);

				$objPdf->SetXY(182, 13);
				$objPdf->Cell(20, 21, $iMasterId);

				$objPdf->SetXY(182, 20);
				$objPdf->Cell(20, 21, $sAuditCode);

				$x = 5;
				$y = 35;
						
			}

			$objPdf->SetFont('Arial', 'B',7);

			$objPdf->Text($x,$y,$type);

			$objPdf->SetXY($x,$y+1.5);

			foreach ($sDefectCodes as $code => $defect) {

				$y = $objPdf->GetY();

				$y = $y+3.7;

				$objPdf->SetFont('Arial', '',5.5);
				$objPdf->SetXY($x,$y);
				$objPdf->MultiCell(20, 3.7, $code, 1, "L", false);
				$objPdf->SetXY($x+20,$y);
				$objPdf->MultiCell(75, 3.7, $defect, 1, "L", false);

				$objPdf->SetXY($x,$y);
			}
		}
*/
/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 2  -  DEFECT IMAGES


	if (count($sDefects) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/mgf-page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		$iPages = @ceil(count($sDefects) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 188, 1, 20);


			$objPdf->SetFont('helvetica', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->SetXY(182, 13);
			$objPdf->Cell(20, 21, $iMasterId);

			$objPdf->SetXY(182, 20);
			$objPdf->Cell(20, 21, $sAuditCode);


			$objPdf->SetFont('helvetica', '', 11);
			$objPdf->SetXY(6, 32);
			$objPdf->write(0, "Defect Images");



			$objPdf->SetFont('helvetica', '', 7);

			for ($j = 0; $j < 4 && $iIndex < count($sDefects); $j ++, $iIndex ++)
			{
				$sName  = @strtoupper($sDefects[$iIndex]);
				$sName  = @basename($sName, ".JPG");
				$sParts = @explode("_", $sName);

				$sDefectCode = $sParts[1];
				$sAreaCode   = $sParts[2];
				
				
				$sSQL = "SELECT dc.id, dc.defect,
								(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
						 FROM tbl_defect_codes dc
						 WHERE code='$sDefectCode' AND report_id='$iReportId'";
				$objDb->query($sSQL);

				$iDefect = $objDb->getField(0, "id");
				$sDefect = $objDb->getField(0, "defect");
				$sType   = $objDb->getField(0, "_Type");
				
				$sNature = getDbValue("nature", "tbl_qa_report_defects", "code_id='$iDefect' AND audit_id='$Id'");
				$sArea   = getDbValue("area", "tbl_defect_areas", "id='$sAreaCode'");

				$iLeft = 5;
				$iTop  = 47;

				if ($j == 1 || $j == 3)
					$iLeft = 107;

				if ($j == 2 || $j == 3)
					$iTop = 155;


				$sInfo  = "Type: {$sType}\n";
				$sInfo .= "Defect: {$sDefect}\n";
				$sInfo .= ("Area: ".($sArea == '00'?'N/A':$sArea)."\n");
				$sInfo .= ("Severity: ".($sNature == '2'?'Critical':($sNature == '1'?'Major':'Minor'))."\n");

                                $MAX_WIDTH     = 90;
                                $MAX_HEIGHT    = 98;

                                @list($iWidth, $iHeight) = @getimagesize($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sDefects[$iIndex]));

                                if ($iWidth > $iHeight)
                                {
                                    $fRatio      = ($iWidth / $iHeight);
                                    $ImageWidth  = $MAX_WIDTH;
                                    $ImageHeight = @ceil($MAX_WIDTH / $fRatio);

                                    $objPdf->SetXY($iLeft, ($iTop + $ImageHeight + 1));
                                    $objPdf->MultiCell($MAX_WIDTH, 3.6, $sInfo, 1, "L", false);
                                }
                                else if ($iWidth < $iHeight)
                                {
                                    $fRatio  = ($iHeight / $iWidth);
                                    $ImageHeight = $MAX_WIDTH;
                                    $ImageWidth  = @ceil($MAX_HEIGHT / $fRatio);

                                    $objPdf->SetXY($iLeft, ($iTop + 90.5));
                                    $objPdf->MultiCell($MAX_WIDTH, 3.6, $sInfo, 1, "L", false);
                                }
                                else if($iWidth == $iHeight)
                                {
                                    $ImageWidth  = $MAX_WIDTH;
                                    $ImageHeight = $MAX_WIDTH;

                                    $objPdf->SetXY($iLeft, ($iTop + $ImageHeight + 1));
                                    $objPdf->MultiCell($MAX_WIDTH, 3.6, $sInfo, 1, "L", false);
                                }

                                $objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sDefects[$iIndex]), $iLeft, $iTop, $ImageWidth, $ImageHeight);
                                
			}
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 3  -  PACKING IMAGES


	if (count($sPacking) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/mgf-page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		$iPages = @ceil(count($sPacking) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 188, 1, 20);


			$objPdf->SetFont('helvetica', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->SetXY(182, 13);
			$objPdf->Cell(20, 21, $iMasterId);

			$objPdf->SetXY(182, 20);
			$objPdf->Cell(20, 21, $sAuditCode);


			$objPdf->SetFont('helvetica', '', 11);
			$objPdf->SetXY(6, 32);
			$objPdf->write(0, "Packing Images");



			for ($j = 0; $j < 4 && $iIndex < count($sPacking); $j ++, $iIndex ++)
			{
				$iLeft = 5;
				$iTop  = 47;

				if ($j == 1 || $j == 3)
					$iLeft = 107;

				if ($j == 2 || $j == 3)
					$iTop = 150;


				$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPacking[$iIndex]), $iLeft, $iTop, 98, 98);
			}
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 4  -  SPECS SHEETS


    if (count($sSpecsSheets) > 0)
	{
		for ($i = 0; $i < count($sSpecsSheets); $i ++)
		{
			$sExtension = pathinfo($sSpecsSheets[$i], PATHINFO_EXTENSION);
			
			if (strtolower($sExtension) != 'pdf')
			{
				$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/mgf-page.pdf");
				$iTemplateId = $objPdf->importPage(1, '/MediaBox');

				
				$objPdf->addPage("P", "A4");
				$objPdf->useTemplate($iTemplateId, 0, 0);


				$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 188, 1, 20);


				$objPdf->SetFont('helvetica', '', 7);
				$objPdf->SetTextColor(50, 50, 50);

				$objPdf->SetXY(182, 13);
				$objPdf->Cell(20, 21, $iMasterId);

				$objPdf->SetXY(182, 20);
				$objPdf->Cell(20, 21, $sAuditCode);


				$objPdf->SetFont('helvetica', '', 11);
				$objPdf->SetXY(6, 32);
				$objPdf->write(0, "Lab Reports / Specs Sheets");

				$objPdf->Image($sSpecsSheets[$i], 5, 45, 200, 210);
			}
			
			else
			{
				try
				{
					$iPages = $objPdf->SetSourceFile($sSpecsSheets[$i]);
					
					for ($j = 1; $j <= $iPages; $j ++)
					{
						$objPdf->setSourceFile($sBaseDir."templates/mgf-page.pdf");
						$iTemplateId = $objPdf->importPage(1, '/MediaBox');
						
						$objPdf->addPage("P", "A4");
						$objPdf->useTemplate($iTemplateId, 0, 0);
						
						
						$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 188, 1, 20);


						$objPdf->SetFont('helvetica', '', 7);
						$objPdf->SetTextColor(50, 50, 50);

						$objPdf->SetXY(182, 13);
						$objPdf->Cell(20, 21, $iMasterId);

						$objPdf->SetXY(182, 20);
						$objPdf->Cell(20, 21, $sAuditCode);


						$objPdf->SetFont('helvetica', '', 11);
						$objPdf->SetXY(6, 32);
						$objPdf->write(0, "Lab Reports / Specs Sheets");						


						
						$objPdf->SetSourceFile($sSpecsSheets[$i]);						
						
						$iPage = $objPdf->importPage($j, '/CropBox');
						$objPdf->useTemplate($iPage, 23, 43, 160);
					}
				}
				
				catch (Exception $e)
				{
				    //echo 'Caught exception: ',  $e->getMessage(), "\n";
					//exit( );
				}
			}				
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 5  -  MISC IMAGES


	if (count($sMisc) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/mgf-page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		$iPages = @ceil(count($sMisc) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 188, 1, 20);


			$objPdf->SetFont('helvetica', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->SetXY(182, 13);
			$objPdf->Cell(20, 21, $iMasterId);

			$objPdf->SetXY(182, 20);
			$objPdf->Cell(20, 21, $sAuditCode);

			$objPdf->SetFont('helvetica', '', 11);
			$objPdf->SetXY(6, 32);
			$objPdf->write(0, "Miscellaneous Images");


			for ($j = 0; $j < 4 && $iIndex < count($sMisc); $j ++, $iIndex ++)
			{
				$iLeft = 5;
				$iTop  = 47;

				if ($j == 1 || $j == 3)
					$iLeft = 107;

				if ($j == 2 || $j == 3)
					$iTop = 150;


				$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sMisc[$iIndex]), $iLeft, $iTop, 98, 98);
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	@unlink($sBaseDir.TEMP_DIR."{$sAuditCode}.png");


	$sPdfFile = (ABSOLUTE_PATH.TEMP_DIR."S{$Id}-QA-Report.pdf");

	if (count($Recipients) > 0)
		$objPdf->Output($sPdfFile, 'F');

	else
		$objPdf->Output(@basename($sPdfFile), 'D');
?>