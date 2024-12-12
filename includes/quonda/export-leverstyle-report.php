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
                        (SELECT customer FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Customer,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
	                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor
	         FROM tbl_qa_reports
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	$iReportId          = $objDb->getField(0, "report_id");
	$sVendor            = $objDb->getField(0, "_Vendor");
        $sCustomer          = $objDb->getField(0, "_Customer");
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
	$iWashing           = $objDb->getField(0, "washing");
	$iPressing          = $objDb->getField(0, "pressing");
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

	
	$sAuditStage    = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");
	$iQuantity      = getDbValue("SUM(quantity)", "tbl_po_quantities", "po_id='$iPo'");
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
	
	$fAql = getDbValue("aql", "tbl_brands", "id='$iParent'");



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


	$iTotalPages  = 2;
	$iTotalPages += count($sSpecsSheets);
	$iTotalPages += @ceil(count($sPacking) / 4);
	$iTotalPages += @ceil(count($sDefects) / 4);
	$iTotalPages += @ceil(count($sMisc) / 4);


	/////////////////////////////////////////////////////////Page #1///////////////////////////////////////////////////////////

	$objPdf = new FPDI( );
	$objPdf->setPrintHeader(false);
	$objPdf->setPrintFooter(false);
	
	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/lever-style-p1.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 174, 6, 24);


	$objPdf->SetFont('stsongstdlight', '', 8);
	$objPdf->SetTextColor(50, 50, 50);
	$objPdf->SetXY(172, 30);
	$objPdf->MultiCell(30, 3, "Audit Code: {$sAuditCode}",0, "L", false);

	// Report Details
	$objPdf->SetFont('stsongstdlight', '', 8);

	$objPdf->SetXY(58, 40);
	$objPdf->write(0, $sVendor);

	$objPdf->SetXY(58, 46);
	$objPdf->write(0, $sStyle);
	
	$objPdf->SetXY(58, 52);
	$objPdf->write(0, $sAuditStage);
	
	$objPdf->SetXY(58, 58);
	$objPdf->write(0, formatDate($sAuditDate));
	
	$objPdf->SetXY(58, 64);
	$objPdf->write(0, $sDescription);
	
	$objPdf->SetXY(58, 70);
	$objPdf->write(0, ($sPo.$sAdditionalPos));

	$objPdf->SetXY(158, 46);
	$objPdf->write(0, $sAuditCode);
	
	$objPdf->SetXY(158, 58);
	$objPdf->write(0, formatNumber($iQuantity, false));
	
	$objPdf->SetFont('stsongstdlight', '', 6);
	$objPdf->SetXY(157, 68.5);
	$objPdf->MultiCell(40, 2.5, $sColors,0, "L", false);

    
	$objPdf->SetFont('stsongstdlight', '', 8);	
	$objPdf->SetXY(158, 40);
	$objPdf->write(0, $sBrand);
        
	
	$objPdf->SetXY(27, 92);
	$objPdf->write(0, (($iCutting > 0) ? formatNumber($iCutting, false) : "N/A"));
	
	$objPdf->SetXY(68, 92);
	$objPdf->write(0, (($iSewing > 0) ? formatNumber($iSewing, false) : "N/A"));
   
	$objPdf->SetXY(100, 92);
	$objPdf->write(0, (($iWashing > 0) ? formatNumber($iWashing, false) : "N/A"));
	
	$objPdf->SetXY(139, 92);
	$objPdf->write(0, (($iPressing > 0) ? formatNumber($iPressing, false) : "N/A"));
	
	$objPdf->SetXY(175, 92);
	$objPdf->write(0, (($iPacking > 0) ? formatNumber($iPacking, false) : "N/A"));
        
	
	if ($sAuditResult == "P")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 112, 100, 4);
	
	else if ($sAuditResult == "F")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 173, 100, 4);
	
	else
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 140, 100, 4);

	
	$objPdf->SetXY(40, 110);
	$objPdf->setCellHeightRatio(1.8);
	$objPdf->MultiCell(160, 6, $sComments, 0, "L", false);
	
        
	$sSQL = "SELECT * FROM tbl_leverstyle_inspections WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$CApprovedSample   = $objDb->getField(0, "approved_sample");
	$PrintingSwatche   = $objDb->getField(0, "printing_swatche");
	$ProductLabels     = $objDb->getField(0, "product_labels");
	$FitApprovedSample = $objDb->getField(0, "fit_approved_sample");
	$ProductRemarks    = $objDb->getField(0, "product_remarks");

	
	if($CApprovedSample == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 19, 136.5, 3);

	if($PrintingSwatche == 'Y')    
		 $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 106, 136.5, 3);

	 if($ProductLabels == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 19, 142, 3);

	if($FitApprovedSample == 'Y')    
		 $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 106, 142, 3);
        
	
	$objPdf->SetXY(37, 152);
	$objPdf->MultiCell(160, 6, $ProductRemarks, 0, "L", false);
        
		
	$objPdf->SetXY(82, 180.2);
	$objPdf->write(0, formatNumber($fAql, true, 1));
	
        
	$fTop             = 201;
	$iTotalDefects    = 0;
	$iCriticalDefect  = 0;
	$iMajorDefects    = 0;
	$iMinorDefects    = 0;
	$iCriticalDefects = 0;

	$objPdf->SetFont('stsongstdlight', '', 8);
	
	$sSQL = "Select SUM(if(qrd.nature = '1',qrd.defects,0)) as Major, SUM(if(qrd.nature='0',qrd.defects,0)) as Minor, SUM(if(qrd.nature = '2',qrd.defects,0)) as Critical, qrd.area_id, qrd.cap ,dc.type_id, dc.code, dc.defect, dc.defect_zh
           	 from tbl_qa_report_defects qrd, tbl_defect_codes dc
			 where dc.id=qrd.code_id AND qrd.audit_id='$Id'
			 Group By dc.id, qrd.area_id
			 Order By dc.type_id,dc.code";
	$objDb->query($sSQL);
	
	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iMajor      = $objDb->getField($i, "Major");
		$iMinor      = $objDb->getField($i, "Minor");
		$iCritical   = $objDb->getField($i, "Critical");
		
		$iMajorDefects    += $iMajor;
		$iMinorDefects    += $iMinor;
		$iCriticalDefects += $iCritical;

		$iDefects    = ($iMajor + $iMinor + $iCritical);

		
		if ($i < 5)
		{
			$sCap        = $objDb->getField($i, "cap");
			$sDefectEn   = $objDb->getField($i, "defect");
			$sDefect     = $objDb->getField($i, "defect_zh");

			$objPdf->SetXY(14, $fTop - 3);
			$objPdf->write(0, $sDefect);

			$objPdf->SetXY(14, $fTop + 1.2);
			$objPdf->write(0, $sDefectEn);

			$objPdf->SetXY(85, $fTop - 3);
			$objPdf->MultiCell(60, 2.5, $sCap, 0, "L", false);

			$objPdf->SetXY(155, $fTop);
			$objPdf->write(0, $iCritical);
			
			$objPdf->SetXY(170, $fTop);
			$objPdf->write(0, $iMajor);
			
			$objPdf->SetXY(183, $fTop);
			$objPdf->write(0, $iMinor);

			$fTop += 9;
			$iTotalDefects += $iDefects;
		}
	}
        
		
	$objPdf->SetXY(155, 245);
	$objPdf->write(0, $iCriticalDefects);
	
	$objPdf->SetXY(170, 245);
	$objPdf->write(0, $iMajorDefects);
	
	$objPdf->SetXY(183, 245);
	$objPdf->write(0, $iMinorDefects);
	
	
	$objPdf->SetXY(155, 250);
	$objPdf->write(0, "0");
	
	$objPdf->SetXY(170, 250);
	$objPdf->write(0, $iAqlChart[$iTotalGmts][formatNumber($fAql, true, 1)]);
	
	$objPdf->SetXY(183, 250);
	$objPdf->write(0, "");
	

	$objPdf->SetXY(170, 255);
	$objPdf->write(0, $iTotalGmts);
	
	$objPdf->SetXY(170, 260);
	$objPdf->write(0, (($sAuditResult == "P") ? 'Pass' : (($sAuditResult == "R") ? 'Rejected' : 'Fail')));
 
	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 2


	$sMeasurementSheet = getDbValue("measurement_sheet", "tbl_leverstyle_inspections", "audit_id='$Id'");
	
	if ($sMeasurementSheet != "")
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/lever-style-p2.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');

		$objPdf->addPage("P", "A4");
		$objPdf->useTemplate($iTemplateId, 0, 0);

		QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));


		$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 174, 6, 24);


		$objPdf->SetFont('stsongstdlight', '', 8);
		$objPdf->SetTextColor(50, 50, 50);
		$objPdf->SetXY(172, 30);
		$objPdf->MultiCell(30, 3, "Audit Code: {$sAuditCode}",0, "L", false);
		
		
		$objPdf->SetFont('stsongstdlight', '', 11);
		$objPdf->SetTextColor(169, 169, 169);
		$objPdf->SetXY(11, 18);
	//	$objPdf->write(0, "Page 2 of {$iTotalPages}");


		// Report Details
		$objPdf->SetFont('stsongstdlight', '', 8);
		$objPdf->SetTextColor(0, 0, 0);

		$objPdf->SetXY(180, 47);
		$objPdf->write(0, (($sAuditResult == "P") ? 'Pass' : (($sAuditResult == "R") ? 'Rejected' : 'Fail')));

		$objPdf->SetXY(70, 40);
		$objPdf->write(0, $iTotalGmts);

	//	$objPdf->SetXY(40, 45.4);
	//	$objPdf->MultiCell(160, 9, $sComments, 0, "L", false);

		
	
		$objPdf->Image(($sBaseDir.QUONDA_PICS_DIR.'leverstyle/'.$sMeasurementSheet), 15, 84, 180, 100);
	}
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 3  -  SPECS SHEETS


	if (count($sSpecsSheets) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/lever-style-p3.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		for ($i = 0; $i < count($sSpecsSheets); $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);

			QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 174, 6, 24);


			$objPdf->SetFont('stsongstdlight', '', 8);
			$objPdf->SetTextColor(50, 50, 50);
			$objPdf->SetXY(172, 30);
			$objPdf->MultiCell(30, 3, "Audit Code: {$sAuditCode}",0, "L", false);

			$objPdf->SetFont('stsongstdlight', '', 11);
			$objPdf->SetTextColor(169, 169, 169);
			$objPdf->SetXY(11, 18);
//			$objPdf->write(0, "Page {$iCurrentPage} of {$iTotalPages}");



			$objPdf->SetTextColor(50, 50, 50);
			$objPdf->SetFont('stsongstdlight', '', 11);
			$objPdf->SetXY(11, 25);
//			$objPdf->write(0, "Lab Reports / Specs Sheets");

			$objPdf->SetFont('stsongstdlight', '', 7);
			$objPdf->SetTextColor(0, 0, 0);
			
			$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheets[$i]), 15, 84, 180, 100);
		}
	}	


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 4  -  DEFECT IMAGES


	$iCurrentPage = 3;
        
        
	$sLanguage  = getDbValue("language", "tbl_users", "id='{$_SESSION['UserId']}'");
        
        if ($sLanguage == "")
            $sLanguage == "en";
                
	if (count($sDefects) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/lever-style-p3.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');
                
                if ($sLanguage == 'zh')
		{
			$Type    = '类型';
			$Defect = '缺陷';
			$Area   = '区';
		}
                else
		{
			$Type = 'Type';
			$Defect = 'Defect';
			$Area   = 'Area';
		}

		$iPages = @ceil(count($sDefects) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);

			QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 174, 6, 24);


			$objPdf->SetFont('stsongstdlight', '', 8);
			$objPdf->SetTextColor(50, 50, 50);
			$objPdf->SetXY(172, 30);
			$objPdf->MultiCell(30, 3, "Audit Code: {$sAuditCode}",0, "L", false);
				
			
			$objPdf->SetFont('stsongstdlight', '', 11);
			$objPdf->SetTextColor(169, 169, 169);
			$objPdf->SetXY(11, 18);
//			$objPdf->write(0, "Page {$iCurrentPage} of {$iTotalPages}");
			

			$objPdf->SetTextColor(50, 50, 50);
			$objPdf->SetFont('stsongstdlight', '', 11);
			$objPdf->SetXY(11, 25);
//			$objPdf->write(0, "Defect Images");

			$objPdf->SetFont('stsongstdlight', '', 7);
			$objPdf->SetTextColor(0, 0, 0);

			for ($j = 0; $j < 4 && $iIndex < count($sDefects); $j ++, $iIndex ++)
			{
				$sName  = @strtoupper($sDefects[$iIndex]);
				$sName  = @basename($sName, ".JPG");
				$sParts = @explode("_", $sName);

				$sDefectCode = $sParts[1];
				$sAreaCode   = $sParts[2];


				$sSQL = "SELECT IF('$sLanguage'='en', defect, defect_{$sLanguage}),
								(SELECT IF('$sLanguage'='en', type, type_{$sLanguage}) FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
						 FROM tbl_defect_codes dc
						 WHERE dc.code='$sDefectCode' AND dc.report_id='$iReportId'";
				$objDb->query($sSQL);

				$sDefect = $objDb->getField(0, 0);
				$sType   = $objDb->getField(0, 1);
                                $sArea   = getDbValue("IF('$sLanguage'='en', area, area_{$sLanguage})", "tbl_defect_areas", "id='$sAreaCode'");

				$iLeft = 5;
				$iTop  = 47;

				if ($j == 1 || $j == 3)
					$iLeft = 107;

				if ($j == 2 || $j == 3)
					$iTop = 155;


				$sInfo  = "{$Type}: {$sType}\n";
				$sInfo .= "{$Defect}: {$sDefect}\n";
				$sInfo .= ("{$Area}: ".$sArea."\n");

				$objPdf->SetXY($iLeft, ($iTop + 90.5));
				$objPdf->MultiCell(98, 3.6, $sInfo, 1, "L", false);


				$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sDefects[$iIndex]), $iLeft, $iTop, 98, 90);
			}
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 5  -  PACKING IMAGES


	if (count($sPacking) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/lever-style-p3.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		$iPages = @ceil(count($sPacking) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);

			QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 174, 6, 24);


			$objPdf->SetFont('stsongstdlight', '', 8);
			$objPdf->SetTextColor(50, 50, 50);
			$objPdf->SetXY(172, 30);
			$objPdf->MultiCell(30, 3, "Audit Code: {$sAuditCode}",0, "L", false);
			
			
			$objPdf->SetFont('stsongstdlight', '', 11);
			$objPdf->SetTextColor(169, 169, 169);
			$objPdf->SetXY(11, 18);
//			$objPdf->write(0, "Page {$iCurrentPage} of {$iTotalPages}");

			
			$objPdf->SetTextColor(50, 50, 50);
			$objPdf->SetFont('stsongstdlight', '', 11);
			$objPdf->SetXY(11, 25);
//			$objPdf->write(0, "Packing Images");

			$objPdf->SetFont('stsongstdlight', '', 7);
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


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 7  -  MISC IMAGES


	if (count($sMisc) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/lever-style-p3.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		$iPages = @ceil(count($sMisc) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));
			
			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 174, 6, 24);


			$objPdf->SetFont('stsongstdlight', '', 8);
			$objPdf->SetTextColor(50, 50, 50);
			$objPdf->SetXY(172, 30);
			$objPdf->MultiCell(30, 3, "Audit Code: {$sAuditCode}",0, "L", false);
			
			
			$objPdf->SetFont('stsongstdlight', '', 11);
			$objPdf->SetTextColor(169, 169, 169);
			$objPdf->SetXY(11, 18);
//			$objPdf->write(0, "Page {$iCurrentPage} of {$iTotalPages}");

			$objPdf->SetTextColor(50, 50, 50);
			$objPdf->SetFont('stsongstdlight', '', 11);
			$objPdf->SetXY(11, 25);
//			$objPdf->write(0, "Miscellaneous Images");

			$objPdf->SetFont('stsongstdlight', '', 7);
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