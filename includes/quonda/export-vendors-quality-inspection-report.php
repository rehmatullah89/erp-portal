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
        $iUnitId            = $objDb->getField(0, "unit_id");
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
	$sApprovedTrims     = $objDb->getField(0, "approved_trims");
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


	$sSpecsSheets = array( );

	for ($i = 1; $i <= 10; $i ++)
	{
		$sSpecsSheet = $objDb->getField(0, "specs_sheet_{$i}");

		if ($sSpecsSheet != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet))
			$sSpecsSheets[] = $sSpecsSheet;
	}

        $sReportTypeName = getDbValue("report", "tbl_reports", "id='$iReportId'");
        $sUnitId  = getDbValue("vendor", "tbl_vendors", "id='$iUnitId'");
                
	@list($iLength, $iWidth, $iHeight, $sUnit) = @explode("x", $sCartonSize);

	if ($fPercentDecfective == 0)
		$fPercentDecfective = @round((($iCartonsRejected / $iTotalCartons) * 100), 2);


	$sAuditStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");


	$sSQL = "SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id='$iPo'";
	$objDb->query($sSQL);

	$iQuantity = $objDb->getField(0, 0);


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


	$sDestination = getDbValue("destination", "tbl_destinations", "id='$iDestination'");


	$sCodes = array("0", "104", "105", "109", "199",
	                "0", "201", "202", "203", "204", "205", "206", "207", "208", "209", "210", "211", "212", "213", "214", "215", "216", "217", "219", "299",
					"0", "301", "302", "303", "304", "305", "306", "307", "399",
					"0", "601", "602", "604", "605", "699",
					"0", "801", "802", "803", "804", "805", "806", "807", "808", "809", "810", "811", "812", "813", "814", "815", "816", "817", "899");


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

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/vendors_qa_report.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);

        
        $objPdf->SetFont('Arial', '', 11);
	$objPdf->SetTextColor(169, 169, 169);
	$objPdf->Text(94, 23.2, strtoupper($sReportTypeName));
        $objPdf->Text(6, 29, "Page 1 of {$iTotalPages}");
        
        $objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);
	$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");
        

	// Report Details
	$objPdf->SetFont('Arial', '', 7);

	$objPdf->Text(21, 38.2, $sBrand);
	$objPdf->Text(76, 38.2, $sVendor);
        $objPdf->Text(112, 38.2, $sUnitId);
	$objPdf->Text(149, 38.2, formatDate($sAuditDate));
	$objPdf->Text(183, 38.2, $sAuditor);

	$objPdf->Text(13, 46.5, ($sPo.$sAdditionalPos));
	$objPdf->Text(80, 46.5, $sStyle);
	$objPdf->Text(149, 46.5, formatNumber($iQuantity, false));
	$objPdf->Text(185, 46.5, formatNumber($iShipQty, false));

	$objPdf->Text(24, 53.5, $sDestination);
	$objPdf->Text(195, 63, formatNumber($fCartonsShipped, false));

	$objPdf->SetXY(73, 53);
	$objPdf->MultiCell(100, 2, $sSizeTitles, 0);

	$objPdf->SetXY(21, 60.5);
	$objPdf->MultiCell(110, 3.2, $sColors, 0);


	$objPdf->Text(26, 70.7, $sDescription);
	$objPdf->Text(140, 72, $sAuditStatus);
	$objPdf->Text(175, 72, strtoupper($sAuditStage));


	$objPdf->Text(33, 85, (($fKnitted > 0) ? $fKnitted : "N/A"));
	$objPdf->Text(54, 85, (($fDyed > 0) ? $fDyed : "N/A"));
	$objPdf->Text(77, 85, (($iCutting > 0) ? formatNumber($iCutting, false) : "N/A"));
	$objPdf->Text(99, 85, (($iSewing > 0) ? formatNumber($iSewing, false) : "N/A"));
	$objPdf->Text(137, 85, ((($iFinishing > 0) ? formatNumber($iFinishing, false) : "N/A")." / ".(($iPacking > 0) ? formatNumber($iPacking, false) : "N/A")));
	$objPdf->Text(177, 85, (($sFinalAuditDate != "0000-00-00") ? formatDate($sFinalAuditDate) : "N/A"));

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


	$objPdf->Text(33, 90.5, $sStartTime);
	$objPdf->Text(74, 90.5, $sEndTime);
	$objPdf->Text(112, 90.5, formatNumber($fAql, true, (($fAql < 1) ? 2 : 1)));

	$objPdf->SetFont('Arial', 'B', 7);
        $objPdf->SetTextColor(255,255,255);
	$objPdf->Text(177, 90.5, (($sAuditResult == "P") ? "PASS" : (($sAuditResult == "F") ? "FAIL" : "HOLD")));


	$objPdf->SetFont('Arial', '', 7);
        $objPdf->SetTextColor(0,0,0);
	$fTop          = 108;
	$iTotalDefects = 0;
        $iCriticalDefect = 0;
        $iMajorDefects   = 0;
        $iMinorDefects   = 0;
        
        $sSQL = "Select SUM(if(qrd.nature = '0',qrd.defects,0)) as Major, SUM(if(qrd.nature = '4',qrd.defects,0)) as Minor, qrd.area_id, dc.type_id, dc.code, dc.defect from tbl_qa_report_defects qrd, tbl_defect_codes dc where dc.id=qrd.code_id AND qrd.audit_id='$Id' Group By dc.id, qrd.area_id Order By dc.type_id,dc.code";
        $objDb->query($sSQL);
        $iCount = $objDb->getCount( );
        $iPreviousTypeId = 0;
        $newLine = 3.6;
        
        for ($i = 0; $i < $iCount; $i ++)
	{
            $iMajor      = $objDb->getField($i, "Major");
            $iMajorDefects += $iMajor;
            $iMinor      = $objDb->getField($i, "Minor");
            $iMinorDefects += $iMinor;
            $iDefects    = $iMajor + $iMinor;
            $iAreaId     = $objDb->getField($i, "area_id");
            $iTypeId     = $objDb->getField($i, "type_id");
            $sCode       = $objDb->getField($i, "code");
            $sDefect     = $objDb->getField($i, "defect");
            $sArea       = getDbValue("area", "tbl_defect_areas", "id='$iAreaId'");
            $sDefectType = getDbValue("type", "tbl_defect_types", "id='$iTypeId'");

            if($iPreviousTypeId != $iTypeId){
               $objPdf->SetFont('Arial', 'B', 7);
               $iPreviousTypeId = $iTypeId;
               $objPdf->Text(18, $fTop, $sDefectType);
               $fTop += $newLine; 
            }
            
            $objPdf->SetFont('Arial', '', 7);
            $objPdf->Text(8, $fTop, $sCode);
            $objPdf->Text(25, $fTop, $sDefect);            
            $objPdf->Text(120, $fTop, $sArea);            
          
            $objPdf->Text(178, $fTop, $iMajor);            
            $objPdf->Text(195, $fTop, $iMinor);            
            
            $fTop += 3.6;
            $iTotalDefects += $iDefects;
	}

	$objPdf->SetFont('Arial', '', 7);
	$objPdf->Text(199, 224.5, $iTotalDefects);

	$objPdf->Text(40, 234, (($sCustomSample == "Y") ? "CUSTOM ({$iTotalGmts})" : $iTotalGmts));
	$objPdf->Text(88, 234, (($iGmtsDefective > 0) ? $iGmtsDefective : $iTotalDefects));
	$objPdf->Text(128, 234, $iMaxDefects);
        $objPdf->Text(153, 234, 'Cr:('.$iCriticalDefect.') Mj:('.$iMajorDefects.') Mi('.$iMinorDefects.')');
	$objPdf->Text(195, 234, @round((($iTotalDefects / $iTotalGmts) * 100), 2));

	$objPdf->Text(48, 242.5, $iTotalCartons);
	$objPdf->Text(88, 242.5, $iCartonsRejected);
	$objPdf->Text(128, 242.5, $fStandard);
	$objPdf->Text(168, 242.5, $fPercentDecfective);
	$objPdf->Text(195, 242.5, @round((($fCartonsShipped / $fCartonsRequired) * 100), 2));


	if ($sApprovedSample == "Yes")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 78, 244, 4);

	else if ($sApprovedSample == "No")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 90, 244, 4);


	$objPdf->Text(133, 248, (($sShippingMark == "Y") ? "Yes" : "No"));
	$objPdf->Text(188, 248, (($sPackingCheck == "Y") ? "Yes" : "No"));


	$objPdf->Text(177, 252, $iLength);
	$objPdf->Text(189, 252, $iWidth);
	$objPdf->Text(199, 252, $iHeight);

/*
	if ($sApprovedTrims == "Y")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 133, 244, 4);

	else if ($sApprovedTrims == "N")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 145, 244, 4);


	$objPdf->Text(186, 248, ($iTotalGmts - (($iGmtsDefective > 0) ? $iGmtsDefective : $iTotalDefects)));
	$objPdf->Text(186, 251.8, (($iGmtsDefective > 0) ? $iGmtsDefective : $iTotalDefects));
*/

	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 2


	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/vendor-comments.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 11);
        $objPdf->SetTextColor(169, 169, 169);
	$objPdf->Text(94, 23.2, strtoupper($sReportTypeName));
	$objPdf->Text(6, 29, "Page 2 of {$iTotalPages}");

        
	// Report Details
        $objPdf->SetFont('Arial', '', 7);
        $objPdf->SetTextColor(0, 0, 0);

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

	$objPdf->SetXY(116, 57);
	$objPdf->MultiCell(57, 3.2, $sColors, 0);

	$objPdf->Text(26, 70.5, $sDescription);
	$objPdf->Text(140, 72, $sAuditStatus);
	$objPdf->Text(175, 72, strtoupper($sAuditStage));


	$objPdf->Text(165, 135, $sAuditor);


	$objPdf->SetFont('Arial', '', 7);

	$objPdf->SetXY(6, 88.5);
	$objPdf->MultiCell(197, 3.5, $sComments, 0);



	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 3


	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/vendor-annexure.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 11);
        $objPdf->SetTextColor(169, 169, 169);
	$objPdf->Text(94, 23.2, strtoupper($sReportTypeName));
	$objPdf->Text(6, 29, "Page 3 of {$iTotalPages}");

        // Report Details
	$objPdf->SetFont('Arial', '', 11);
        $objPdf->SetTextColor(0, 0, 0);
                        
	$objPdf->Text(115, 46.6, formatNumber($fAql, true, (($fAql < 1) ? 2 : 1)));



	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 4  -  DEFECT IMAGES


	$iCurrentPage = 4;

	if (count($sDefects) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/vendor-page.pdf");
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

			$objPdf->SetFont('Arial', '', 11);
                        $objPdf->SetTextColor(169, 169, 169);
                	$objPdf->Text(94, 23.2, strtoupper($sReportTypeName));
			$objPdf->Text(6, 37, "Page {$iCurrentPage} of {$iTotalPages}");



                        $objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(0, 0, 0);

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
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/vendor-page.pdf");
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

			$objPdf->SetFont('Arial', '', 11);
                        $objPdf->SetTextColor(169, 169, 169);
                        $objPdf->Text(94, 23.2, strtoupper($sReportTypeName));
			$objPdf->Text(6, 37, "Page {$iCurrentPage} of {$iTotalPages}");

                        $objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(0, 0, 0);
                        
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
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/vendor-page.pdf");
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

			$objPdf->SetFont('Arial', '', 11);
                        $objPdf->SetTextColor(169, 169, 169);
                        $objPdf->Text(94, 23.2, strtoupper($sReportTypeName));
			$objPdf->Text(6, 37, "Page {$iCurrentPage} of {$iTotalPages}");
                        
                        $objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(0, 0, 0);
                        
			$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheets[$i]), 10, 47, 190);
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 7  -  MISC IMAGES


	if (count($sMisc) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/vendor-page.pdf");
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

			$objPdf->SetFont('Arial', '', 11);
                        $objPdf->SetTextColor(169, 169, 169);
                        $objPdf->Text(94, 23.2, strtoupper($sReportTypeName));
			$objPdf->Text(6, 37, "Page {$iCurrentPage} of {$iTotalPages}");

                        
                        $objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(0, 0, 0);

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