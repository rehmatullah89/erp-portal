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
	@require_once($sBaseDir."requires/fpdi/fpdi.php");
	@require_once($sBaseDir."requires/qrcode/qrlib.php");


	$sSQL = "SELECT *,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
	                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor
	         FROM tbl_qa_reports
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	$iReportId          = $objDb->getField(0, "report_id");
	$sVendor            = $objDb->getField(0, "_Vendor");
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
	$sSpecsSheet1       = $objDb->getField(0, 'specs_sheet_1');
	$sSpecsSheet2       = $objDb->getField(0, 'specs_sheet_2');
	$sSpecsSheet3       = $objDb->getField(0, 'specs_sheet_3');
	$sSpecsSheet4       = $objDb->getField(0, 'specs_sheet_4');
	$sSpecsSheet5       = $objDb->getField(0, 'specs_sheet_5');
	$sSpecsSheet6       = $objDb->getField(0, 'specs_sheet_6');
	$sSpecsSheet7       = $objDb->getField(0, 'specs_sheet_7');
	$sSpecsSheet8       = $objDb->getField(0, 'specs_sheet_8');
	$sSpecsSheet9       = $objDb->getField(0, 'specs_sheet_9');
	$sSpecsSheet10      = $objDb->getField(0, 'specs_sheet_10');
	$iLine              = $objDb->getField(0, "line_id");
	$fDhu               = $objDb->getField(0, "dhu");


	$sSpecsSheets = array( );

	for ($i = 1; $i <= 10; $i ++)
	{
		$sSpecsSheet = $objDb->getField(0, "specs_sheet_{$i}");

		if ($sSpecsSheet != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet))
			$sSpecsSheets[] = $sSpecsSheet;
	}


	@list($iLength, $iWidth, $iHeight, $sUnit) = @explode("x", $sCartonSize);

	if ($fPercentDecfective == 0)
		$fPercentDecfective = @round((($iCartonsRejected / $iTotalCartons) * 100), 2);


	$sAuditStage    = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");
	$iQuantity      = 0;
	$sAdditionalPos = "";
	$iDestination   = "";
	$sDestination   = "";
	$sCustomer      = "";
	$sSizeTitles    = "";


	if ($iPo > 0)
	{
		$iQuantity = getDbValue("SUM(quantity)", "tbl_po_quantities", "po_id='$iPo'");


		$sSQL = "SELECT CONCAT(order_no, ' ', order_status), quantity FROM tbl_po WHERE id IN ($iAdditionalPos) ORDER BY order_no";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sAdditionalPos .= (",".$objDb->getField($i, 0));
			$iQuantity      += $objDb->getField($i, 1);
		}


		$iDestination = getDbValue("destination_id", "tbl_po_colors", "po_id='$iPo'");
		$sDestination = getDbValue("destination", "tbl_destinations", "id='$iDestination'");
		$sCustomer    = getDbValue("customer", "tbl_po", "id='$iPo'");


		$sSQL = "SELECT size FROM tbl_sizes WHERE id IN ($sSizes) ORDER BY position";

		if ($objDb->query($sSQL) == true)
		{
			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
				$sSizeTitles .= (", ".$objDb->getField($i, 0));

			$sSizeTitles = substr($sSizeTitles, 2);
		}
	}


	$sSQL = "SELECT style, style_name, design_no, design_name, brand_id, sub_brand_id,
	                (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand,
	                (SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id) AS _Season,
	                (SELECT program FROM tbl_programs WHERE id=tbl_styles.program_id) AS _Program
	         FROM tbl_styles
	         WHERE id='$iStyle'";
	$objDb->query($sSQL);

	$sStyle       = $objDb->getField(0, "style");
	$sDescription = $objDb->getField(0, "style_name");
	$iParent      = $objDb->getField(0, "brand_id");
	$iBrand       = $objDb->getField(0, "sub_brand_id");
	$sBrand       = $objDb->getField(0, "_Brand");
	$sSeason      = $objDb->getField(0, '_Season');
	$sProgram     = $objDb->getField(0, '_Program');
	$sDesignNo    = $objDb->getField(0, 'design_no');
	$sDesignName  = $objDb->getField(0, 'design_name');



	$sCodes = array("0", "106", "113", "114", "115", "108", "109", "111", "112", "199",
					"0", "201", "202", "203", "204", "206", "208", "211", "216", "299",
					"0", "301", "305", "306", "390",
					"0", "401", "402", "403", "410", "412", "415", "416", "499",
					"0", "501", "502", "503", "599",
					"0", "601", "603", "604", "605", "699",
					"0", "703", "704", "706", "799",
					"0", "805", "806", "899");


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


	$iTotalPages  = 3;
	$iTotalPages += count($sSpecsSheets);
	$iTotalPages += @ceil(count($sPacking) / 4);
	$iTotalPages += @ceil(count($sDefects) / 4);
	$iTotalPages += @ceil(count($sMisc) / 4);


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	$objPdf =& new FPDI( );

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/sampling.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 11);

	$objPdf->Text(4, 35, "Page 1 of {$iTotalPages}");



	// Report Details
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->Text(20, 44.0, $sBrand);
	$objPdf->Text(81, 44.0, $sVendor);
	$objPdf->Text(145, 44.0, formatDate($sAuditDate));
	$objPdf->Text(183, 44.0, $sAuditor);

	$objPdf->Text(12, 50.8, ($sPo.$sAdditionalPos));
	$objPdf->Text(46.5, 50.8, $sStyle);
	$objPdf->Text(91, 50.8, $sDestination);
	$objPdf->Text(147, 50.8, formatNumber($iQuantity, false));
	$objPdf->Text(183, 50.8, formatNumber($iShipQty, false));


	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetXY(17, 55.5);
	$objPdf->MultiCell(186, 2.7, $sColors, 0);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->Text(22, 65.6, $sCustomer);
	$objPdf->Text(83, 65.6, $sSeason);
	$objPdf->Text(120.5, 65.6, $sProgram);

	$objPdf->SetXY(171, 63.6);
	$objPdf->MultiCell(33, 2.7, $sSizeTitles, 0);


	$objPdf->Text(22, 71.8, $sDesignNo);
	$objPdf->Text(94, 71.8, $sDesignName);
	$objPdf->Text(183.5, 71.3, formatNumber($fCartonsShipped, false));

	$objPdf->Text(25, 79.0, $sDescription);
	$objPdf->Text(115.5, 79.5, $sAuditStatus);
	$objPdf->Text(153, 79.5, strtoupper($sAuditStage));

	$objPdf->Text(30, 92, (($fKnitted > 0) ? $fKnitted : "N/A"));
	$objPdf->Text(54, 92, (($fDyed > 0) ? $fDyed : "N/A"));
	$objPdf->Text(76, 92, (($iCutting > 0) ? formatNumber($iCutting, false) : "N/A"));
	$objPdf->Text(99, 92, (($iSewing > 0) ? formatNumber($iSewing, false) : "N/A"));
	$objPdf->Text(136, 92, ((($iFinishing > 0) ? formatNumber($iFinishing, false) : "N/A")." / ".(($iPacking > 0) ? formatNumber($iPacking, false) : "N/A")));
	$objPdf->Text(175, 92, (($sFinalAuditDate != "0000-00-00") ? formatDate($sFinalAuditDate) : "N/A"));


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


	$fAql = getDbValue("aql", "tbl_brands", "id='$iParent'");

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


	$objPdf->Text(33, 97, $sStartTime);
	$objPdf->Text(78, 97, $sEndTime);
	$objPdf->Text(109, 97, $fAql);


	$objPdf->SetFont('Arial', 'B', 7);
	$objPdf->Text(198, 97, (($sAuditResult == "P") ? "PASS" : (($sAuditResult == "F") ? "FAIL" : "HOLD")));


	$objPdf->SetFont('Arial', '', 7);

	$iTotalMajors = 0;
	$iTotalMinors = 0;

	for ($i = 0; $i < count($sCodes); $i ++)
	{
		if ($sCodes[$i] == "0")
			continue;


		$iCode = (int)getDbValue("id", "tbl_defect_codes", "report_id='$iReportId' AND code='{$sCodes[$i]}'");

		if ($iCode == 0)
			continue;


		$iMajorDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id='$iCode' AND nature='1'");
		$iMinorDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id='$iCode' AND nature='0'");

		if ($iMajorDefects == 0 && $iMinorDefects == 0)
			continue;


		if ($iMajorDefects > 0)
			$objPdf->Text( (($i <= 33) ? 78.5 : 187), (($i <= 33) ? (106.9 + (3.47 * $i)) : (103.3 + (3.47 * ($i - 33)))), $iMajorDefects);

		if ($iMinorDefects > 0)
			$objPdf->Text( (($i <= 33) ? 90 : 199), (($i <= 33) ? (106.9 + (3.47 * $i)) : (103.3 + (3.47 * ($i - 33)))), $iMinorDefects);


		$iTotalMajors += $iMajorDefects;
		$iTotalMinors += $iMinorDefects;
	}


	$objPdf->Text(187, 219.5, $iTotalMajors);
	$objPdf->Text(199, 219.5, $iTotalMinors);


	$objPdf->Text(37, 228.5, (($sCustomSample == "Y") ? "CUSTOM ({$iTotalGmts})" : $iTotalGmts));
	$objPdf->Text(85, 228.5, (($iGmtsDefective > 0) ? $iGmtsDefective : $iDefects));
	$objPdf->Text(125, 228.5, $iMaxDefects);
	$objPdf->Text(165, 228.5, ($iTotalMajors + $iTotalMinors));
	$objPdf->Text(193, 228.5, @round(((($iTotalMajors + $iTotalMinors) / $iTotalGmts) * 100), 2));

	$objPdf->Text(45, 237, $iTotalCartons);
	$objPdf->Text(85, 237, $iCartonsRejected);
	$objPdf->Text(125, 237, $fStandard);
	$objPdf->Text(165, 237, $fPercentDecfective);
	$objPdf->Text(192, 237, @round((($fCartonsShipped / $fCartonsRequired) * 100), 2));


  	if ($sApprovedSample == "Yes")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 81, 239, 4);

  	else if ($sApprovedSample == "No")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 93, 239, 4);

	$objPdf->Text(136, 242.5, (($sShippingMark == "Y") ? "Yes" : "No"));
	$objPdf->Text(186, 242.5, (($sPackingCheck == "Y") ? "Yes" : "No"));

	$objPdf->Text(178, 246.5, $iLength);
	$objPdf->Text(190, 246.5, $iWidth);
	$objPdf->Text(200, 246.5, $iHeight);


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 2


	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/comments.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 11);

	$objPdf->Text(6, 29, "Page 2 of {$iTotalPages}");


	// Report Details
	$objPdf->SetFont('Arial', '', 7);

	$objPdf->Text(21, 38, $sBrand);
	$objPdf->Text(78, 38.5, $sVendor);
	$objPdf->Text(139, 38.5, formatDate($sAuditDate));
	$objPdf->Text(184, 38.2, $sAuditor);

	$objPdf->Text(13, 49, ($sPo.$sAdditionalPos));
	$objPdf->Text(83, 49, $sStyle);
	$objPdf->Text(140, 49, formatNumber($iQuantity, false));
	$objPdf->Text(185, 48.6, formatNumber($iShipQty, false));

	$objPdf->Text(24, 59.5, $sDestination);
	$objPdf->Text(195, 59.5, formatNumber($fCartonsShipped, false));

	$objPdf->SetXY(78, 57);
	$objPdf->MultiCell(22, 3.2, $sSizeTitles, 0);

	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetXY(116, 57);
	$objPdf->MultiCell(57, 3, $sColors, 0);

	$objPdf->SetFont('Arial', '', 7);
	$objPdf->Text(26, 70.5, $sDescription);
	$objPdf->Text(140, 72, $sAuditStatus);
	$objPdf->Text(175, 72, strtoupper($sAuditStage));


	$objPdf->Text(165, 135, $sAuditor);


	$objPdf->SetFont('Arial', '', 7);

	$objPdf->SetXY(6, 88.5);
	$objPdf->MultiCell(197, 3.5, $sComments, 0);



	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 3


	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/annexure.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 11);

	$objPdf->Text(6, 29, "Page 3 of {$iTotalPages}");


	// Report Details
	$objPdf->SetFont('Arial', '', 11);

	$objPdf->Text(115, 46.6, formatNumber($fAql, true, (($fAql < 1) ? 2 : 1)));



	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 4  -  DEFECT IMAGES


	$iCurrentPage = 4;

	if (count($sDefects) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		$iPages = @ceil(count($sDefects) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);



			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(6, 32, "Defect Images");

			$objPdf->SetFont('Arial', '', 10);
			$objPdf->Text(6, 37, "Page {$iCurrentPage} of {$iTotalPages}");



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


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 5  -  PACKING IMAGES


	if (count($sPacking) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		$iPages = @ceil(count($sPacking) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);



			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(6, 32, "Packing Images");

			$objPdf->SetFont('Arial', '', 10);
			$objPdf->Text(6, 37, "Page {$iCurrentPage} of {$iTotalPages}");


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


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 6  -  SPECS SHEETS


	if (count($sSpecsSheets) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		for ($i = 0; $i < count($sSpecsSheets); $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);



			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(6, 32, "Lab Reports / Specs Sheets");

			$objPdf->SetFont('Arial', '', 10);
			$objPdf->Text(6, 37, "Page {$iCurrentPage} of {$iTotalPages}");


			$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheets[$i]), 10, 47, 190);
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 7  -  MISC IMAGES


	if (count($sMisc) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		$iPages = @ceil(count($sMisc) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);



			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(6, 32, "Miscellaneous Images");

			$objPdf->SetFont('Arial', '', 10);
			$objPdf->Text(6, 37, "Page {$iCurrentPage} of {$iTotalPages}");



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