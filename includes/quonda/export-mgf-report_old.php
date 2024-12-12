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

	@require_once($sBaseDir."requires/fpdf/fpdf.php");
	@require_once($sBaseDir."requires/fpdf/chinese.php");
	@require_once($sBaseDir."requires/fpdi/fpdic.php");
	@require_once($sBaseDir."requires/qrcode/qrlib.php");


	$sSQL = "SELECT *,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
	                IF(unit_id>'0', (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.unit_id), '') AS _Factory,
	                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor
	         FROM tbl_qa_reports
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	$iReportId          = $objDb->getField(0, "report_id");
	$sVendor            = $objDb->getField(0, "_Vendor");
	$sFactory           = $objDb->getField(0, "_Factory");
	$sAuditor           = $objDb->getField(0, "_Auditor");
	$iPo                = $objDb->getField(0, "po_id");
	$iAdditionalPos     = $objDb->getField(0, "additional_pos");
	$sPo                = $objDb->getField(0, "_Po");
	$iStyle             = $objDb->getField(0, "style_id");
	$sColors            = $objDb->getField(0, "colors");
	$sSizes             = $objDb->getField(0, "sizes");
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

	@list($iLength, $iWidth, $iHeight, $sUnit) = @explode("x", $sCartonSize);

	if ($fPercentDecfective == 0)
		$fPercentDecfective = @round((($iCartonsRejected / $iTotalCartons) * 100), 2);


	$sSpecsSheets = array( );

	for ($i = 1; $i <= 10; $i ++)
	{
		$sSpecsSheet = $objDb->getField(0, "specs_sheet_{$i}");

		if ($sSpecsSheet != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet))
			$sSpecsSheets[] = $sSpecsSheet;
	}



	$sSQL = "SELECT * FROM tbl_mgf_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$sVpoNo                = $objDb->getField(0, "vpo_no");
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



	$sSQL = "SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id='$iPo'";
	$objDb->query($sSQL);

	$iQuantity = $objDb->getField(0, 0);


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


	$sSQL = "SELECT destination_id FROM tbl_po_colors WHERE po_id='$iPo' ORDER BY id LIMIT 1";
	$objDb->query($sSQL);

	$iDestination = $objDb->getField(0, 'destination_id');



	$sSizeTitles = "";

	$sSQL = "SELECT size FROM tbl_sizes WHERE id IN ($sSizes) ORDER BY position";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sSizeTitles .= (", ".$objDb->getField($i, 0));

		$sSizeTitles = substr($sSizeTitles, 2);
	}


	$fAql         = getDbValue("aql", "tbl_brands", "id='$iParent'");
	$sDestination = getDbValue("destination", "tbl_destinations", "id='$iDestination'");


	@list($iStartHour, $iStartMinutes) = @explode(":", $sStartTime);
	@list($iEndHour, $iEndMinutes)     = @explode(":", $sEndTime);

	if ($iStartHour >= 12)
	{
		if ($iStartHour > 12)
			$iStartHour -= 12;

		$sStartAmPm  = "PM";
	}

	else
		$sStartAmPm = "AM";


	if ($iEndHour >= 12)
	{
		if ($iEndHour > 12)
			$iEndHour -= 12;

		$sEndAmPm  = "PM";
	}

	else
		$sEndAmPm = "AM";

	$sStartTime = (str_pad($iStartHour, 2, '0', STR_PAD_LEFT).":".str_pad($iStartMinutes, 2, '0', STR_PAD_LEFT)." ".$sStartAmPm);
	$sEndTime   = (str_pad($iEndHour, 2, '0', STR_PAD_LEFT).":".str_pad($iEndMinutes, 2, '0', STR_PAD_LEFT)." ".$sEndAmPm);



	$sMeasurementCodes = "S.01,S.02,S.03";
	$sWorkmanshipCodes = "G.01,G.02,G.03,G.04,G.05,G.06,G.07,G.08,G.09,G.10,G.11,G.12,G.13,G.14,G.15,G.16,G.17,G.18,G.19,G.20,G.21,G.22,G.23,G.24,G.25,G.26,G.27";
	$sMaterialCodes    = "M.01,M.02,M.03,M.04,M.05,M.06,M.07,M.08,M.09,M.10,M.11,M.12,M.13,M.14";

	$iCode = (int)getDbValue("id", "tbl_defect_codes", "report_id='$iReportId' AND code='{$sCodes[$i]}'");


	$iMinorDefects        = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sMeasurementCodes')) AND nature='4'");
	$iMajorDefects        = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sMeasurementCodes')) AND nature='2.5'");
	$iCriticalDefects     = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sMeasurementCodes')) AND nature='0'");

	$sRejectedMeasurement = "CR:{$iCriticalDefects},  MA:{$iMajorDefects},  MI:{$iMinorDefects}";


	$iMinorDefects        = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sWorkmanshipCodes')) AND nature='4'");
	$iMajorDefects        = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sWorkmanshipCodes')) AND nature='2.5'");
	$iCriticalDefects     = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sWorkmanshipCodes')) AND nature='0'");

	$sRejectedWorkmanship = "CR:{$iCriticalDefects},  MA:{$iMajorDefects},  MI:{$iMinorDefects}";


	$iMinorDefects     = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sMaterialCodes')) AND nature='4'");
	$iMajorDefects     = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sMaterialCodes')) AND nature='2.5'");
	$iCriticalDefects  = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sMaterialCodes')) AND nature='0'");

	$sRejectedMaterial = "CR:{$iCriticalDefects},  MA:{$iMajorDefects},  MI:{$iMinorDefects}";



	$sCodes = array("M.01", "M.02", "M.03", "0", "M.04", "M.05", "M.06", "0", "M.07", "M.08", "M.09", "M.10", "0", "M.11", "M.12", "M.13", "0", "0", "M.14", "0", "0", "S.01", "S.02", "S.03",
					"G.01", "G.02", "G.03", "G.04", "G.05", "G.06", "G.07", "G.08", "G.09", "G.10", "G.11", "G.12", "G.13", "G.14", "G.15", "G.16", "G.17", "G.18", "G.19", "G.20", "G.21", "G.22", "G.23", "G.24", "G.25", "G.26", "G.27",
					"B.01", "B.02", "B.03", "B.04", "0", "0", "B.05", "B.06", "B.07", "B.08", "B.09", "0", "0", "K.01", "K.02", "K.03", "K.04", "K.05", "K.06", "0", "0", "P.01", "P.02", "P.03", "P.04");


    @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

	$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_001_*.*");
	$sPictures = @array_merge($sPictures, @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*"));
	$sPictures = @array_map("strtoupper", $sPictures);
	$sPictures = @array_unique($sPictures);

	$sDefects = array( );
	$sPacking = array( );
	$sMisc    = array( );

	foreach ($sPictures as $sPicture)
	{
		$sPic = @basename($sPicture);

		if (@stripos($sPic, "_pack_") !== FALSE || @stripos($sPic, "_001_") !== FALSE)
			$sPacking[] = $sPicture;

		else if (@stripos($sPic, "_misc_") !== FALSE || @stripos($sPic, "_00_") !== FALSE || @substr_count($sPic, "_") < 3)
			$sMisc[] = $sPicture;

		else
			$sDefects[] = $sPicture;
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$objPdf =& new FPDI( );

	$iPageCount = $objPdf->setSourceFile($sBaseDir."templates/mgf".(($iParent == 367) ? "-express" : "").".pdf");


	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->AddBig5Font();
	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 188, 1, 20);



	$objPdf->SetFont('Arial', '', 9);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(191.3, 22.3, $sAuditCode);


	// Report Details
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);


	if ($sAuditStage == "PR")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 41.5, 27.5, 4);

	if ($sAuditStage != "PR" && $sAuditStage != "F")
	{
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 68.5, 27.5, 4);

		if ($sAuditStage == "C" || $sAuditStage == "O")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 83, 25.2, 4);

		else
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 89, 25.2, 4);


		$objPdf->Text(96, 31, getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'"));
	}

	if ($sAuditStage == "F")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 127.5, 27.5, 4);


	if ($iParent == 367)
		$objPdf->Text(25, 39.0, ($sPo.$sAdditionalPos));

	else
	{
		$objPdf->Text(25, 39.0, $sVpoNo);
		$objPdf->Text(135.5, 39.0, ($sPo.$sAdditionalPos));
	}

	$objPdf->Text(33, 43.2, $sBrand);
	$objPdf->Text(78, 43.8, $sStyle);
	$objPdf->Text(135.5, 43.8, formatNumber($iQuantity, false));
	$objPdf->Text(175, 43.8, formatNumber($iShipQty, false));

	$objPdf->Text(25, 47.4, $sVendor);
	$objPdf->Text(135.5, 47.8, $sDestination);
	$objPdf->Text(175, 47.8, formatDate($sEtdRequired));

	$objPdf->Text(25, 51.8, (($sFactory != "") ? $sFactory : $sVendor));

	$objPdf->Text(135.5, 52.2, formatNumber($fAql, true, (($fAql < 1) ? 2 : 1)));
	$objPdf->Text(172, 52.2, "II");

	$objPdf->Text(25, 57, $sDescription);


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

	$objPdf->Text(25, 61.2, $sColors);

	$objPdf->SetXY(30.5, 63.6);
	$objPdf->MultiCell(26, 2.9, $sAuditor, 0, "L");

	$objPdf->Text(77.5, 66.2, $sArticleNo);
	$objPdf->Text(137, 66.2, (($sCustomSample == "Y") ? "CUSTOM" : $iTotalGmts));
	$objPdf->Text(167, 66.2, $iMaxDefects);

	$objPdf->Text(25, 70.8, formatDate($sAuditDate));
	$objPdf->Text(153.5, 71.3, $sRejectedMeasurement);

	$objPdf->Text(25, 74.3, $sStartTime);
	$objPdf->Text(153.5, 74.9, $sRejectedWorkmanship);

	$objPdf->Text(25, 78.5, $sEndTime);
	$objPdf->Text(153.5, 78.9, $sRejectedMaterial);


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


	$sInlineDetails  = ("Cutting: ".(($iCutting == 0) ? "NA" : formatNumber($iCutting, false)));
	$sInlineDetails .= (", Stitching: ".(($iSewing == 0) ? "NA" : formatNumber($iSewing, false)));
	$sInlineDetails .= (", Finishing: ".(($iFinishing == 0) ? "NA" : formatNumber($iFinishing, false)));
	$sInlineDetails .= (", Packing: ".(($iPacking == 0) ? "NA" : formatNumber($iPacking, false)));

	$objPdf->SetXY(14, 81.9);
	$objPdf->Cell(200, 0, $sInlineDetails, 0, 0);

	if ($iParent == 367)
		$objPdf->Text(52, 87.5, $sCartonNo);


	$objPdf->SetFont('Arial', '', 7);

	$fTop  = 98.8;
	$fLeft = 11.5;

	for ($i = 0; $i < count($sCodes); $i ++)
	{
		if ($i == 24)
			$fLeft = 70.5;

		else if ($i == 51)
			$fLeft = 133;


		if ($i == 24 || $i == 51)
			$fTop = 101.7;

		else
			$fTop += 2.815;


		if ($sCodes[$i] == "0")
			continue;


		$iCode = (int)getDbValue("id", "tbl_defect_codes", "report_id='$iReportId' AND code='{$sCodes[$i]}'");

		if ($iCode == 0)
			continue;


		$iMinorDefects    = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id='$iCode' AND nature='4'");
		$iMajorDefects    = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id='$iCode' AND nature='2.5'");
		$iCriticalDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id='$iCode' AND nature='0'");

		if ($iMajorDefects == 0 && $iMinorDefects == 0 && $iCriticalDefects == 0)
			continue;


		if ($iCriticalDefects > 0)
			$objPdf->Text( ($fLeft + 0), $fTop, $iCriticalDefects);

		if ($iMajorDefects > 0)
			$objPdf->Text( ($fLeft + 5), $fTop, $iMajorDefects);

		if ($iMinorDefects > 0)
			$objPdf->Text( ($fLeft + 10), $fTop, $iMinorDefects);
	}


	$sSQL = "SELECT cap,
	                (SELECT code FROM tbl_defect_codes WHERE report_id='$iReportId' AND id=tbl_qa_report_defects.code_id) AS _Code
	         FROM tbl_qa_report_defects
	         WHERE audit_id='$Id' AND cap!=''
	         LIMIT 5";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$fTop   = 186.5;

	for ($i = 0; $i < $iCount; $i ++, $fTop += 3.2)
	{
		$sCode = $objDb->getField($i, "_Code");
		$sCap  = $objDb->getField($i, "cap");

		$objPdf->SetFont('Arial', '', 7);
		$objPdf->Text(12, $fTop, $sCode);
		
		$objPdf->SetFont('Big5', '', 7);
		$objPdf->Text(29, $fTop, $sCap);

		if ($i == 4)
			$fTop += 3.2;
	}


	$objPdf->SetFont('Big5', '', 7);
	$objPdf->SetXY(27, 200);
	$objPdf->MultiCell(171, 3.4, $sCapOthers, 0);


	$objPdf->SetFont('Big5', '', 7);
	$objPdf->SetXY(9, 214.3);
	$objPdf->MultiCell(189, 3.5, ("                    ".$sComments), 0);


	$objPdf->SetFont('Arial', '', 6);

	if ($iParent == 367)
	{
		if ($sFittingTorque == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 60, 228.8, 4);

		if ($sColorCheck == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 92, 228.8, 4);

		if ($sAccessoriesCheck == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 149, 228.8, 4);

		if ($sMeasurementCheck == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 189, 228.8, 4);


		if ($sAuditResult == "P")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 60, 236.0, 4);

		else if ($sAuditResult == "F")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 105, 236.0, 4);

		else if ($sAuditResult == "R")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 167, 236.0, 4);


		if ($iMeasurementSampleQty > 0)
			$objPdf->Text(40, 243.8, $iMeasurementSampleQty);

		if ($iMeasurementDefectQty > 0)
			$objPdf->Text(102, 243.8, $iMeasurementDefectQty);
	}

	else
	{
		if ($sFittingTorque == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 60, 229.5, 4);

		if ($sColorCheck == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 92, 229.5, 4);

		if ($sAccessoriesCheck == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 149, 229.5, 4);

		if ($sMeasurementCheck == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 189, 229.5, 4);


		if ($sAuditResult == "P")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 60, 238.5, 4);

		else if ($sAuditResult == "F")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 105, 238.5, 4);

		else if ($sAuditResult == "R")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 167, 238.5, 4);
	}



	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 2  -  DEFECT IMAGES


	if (count($sDefects) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/mgf".(($iParent == 367) ? "-express" : "")."-page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		$iPages = @ceil(count($sDefects) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 188, 1, 20);


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(191.3, 22.3, $sAuditCode);


			$objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(6, 35, "Defect Images");



			$objPdf->SetFont('Arial', '', 7);

			for ($j = 0; $j < 4 && $iIndex < count($sDefects); $j ++, $iIndex ++)
			{
				$sName  = @strtoupper($sDefects[$iIndex]);
				$sName  = @basename($sName, ".JPG");
				$sParts = @explode("_", $sName);

				$sDefectCode = $sParts[1];
				$sAreaCode   = $sParts[2];


				$sSQL = "SELECT defect,
								(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
						 FROM tbl_defect_codes dc
						 WHERE code='$sDefectCode' AND report_id='$iReportId'";
				$objDb->query($sSQL);

				$sDefect = $objDb->getField(0, "defect");
				$sType   = $objDb->getField(0, "_Type");


				$iLeft = 5;
				$iTop  = 47;

				if ($j == 1 || $j == 3)
					$iLeft = 107;

				if ($j == 2 || $j == 3)
					$iTop = 155;


				$sInfo  = "Type: {$sType}\n";
				$sInfo .= "Defect: {$sDefect}\n";
				$sInfo .= ("Area: ".getDbValue("area", "tbl_defect_areas", "id='$sAreaCode'")."\n");

				$objPdf->SetXY($iLeft, ($iTop + 90.5));
				$objPdf->MultiCell(98, 3.6, $sInfo, 1, "L", false);


				$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sDefects[$iIndex]), $iLeft, $iTop, 98, 90);
			}
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 3  -  PACKING IMAGES


	if (count($sPacking) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/mgf".(($iParent == 367) ? "-express" : "")."-page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		$iPages = @ceil(count($sPacking) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 188, 1, 20);


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(191.3, 22.3, $sAuditCode);


			$objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(6, 35, "Packing Images");



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
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/mgf".(($iParent == 367) ? "-express" : "")."-page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		for ($i = 0; $i < count($sSpecsSheets); $i ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 188, 1, 20);


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(191.3, 22.3, $sAuditCode);


			$objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(6, 35, "Lab Reports / Specs Sheets");



			$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheets[$i]), 5, 47, 200);
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 5  -  MISC IMAGES


	if (count($sMisc) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/mgf".(($iParent == 367) ? "-express" : "")."-page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		$iPages = @ceil(count($sMisc) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 188, 1, 20);


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(191.3, 22.3, $sAuditCode);


			$objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(6, 35, "Miscellaneous Images");



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


	$sPdfFile = ($sBaseDir.TEMP_DIR."S{$Id}-QA-Report.pdf");

	if (count($Recipients) > 0)
		$objPdf->Output($sPdfFile, 'F');

	else
		$objPdf->Output(@basename($sPdfFile), 'D');
?>