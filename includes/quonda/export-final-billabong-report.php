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
	$iVendor            = $objDb->getField(0, "vendor_id");
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
	$ssAuditStage       = $objDb->getField(0, "audit_stage");
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
        $FinalAuditDate     = $objDb->getField(0, "final_audit_date");
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


	@list($iLength, $iWidth, $iHeight, $sUnit) = @explode("x", $sCartonSize);

	if ($fPercentDecfective == 0)
		$fPercentDecfective = @round((($iCartonsRejected / $iTotalCartons) * 100), 2);


	$sAuditStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");


	$sSQL = "SELECT style, style_name, brand_id, sub_brand_id,
                        (SELECT category FROM tbl_style_categories WHERE id=tbl_styles.category_id) AS _Category,
                	(SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand,
			(SELECT season FROM tbl_seasons WHERE tbl_styles.sub_season_id = id  ORDER BY id LIMIT 1) AS _Season
			 FROM tbl_styles
			 WHERE id='$iStyle'";
        $objDb->query($sSQL);

	$sStyle       = $objDb->getField(0, "style");
	$sStyleName   = $objDb->getField(0, "style_name");
        $sDescription = $sStyleName.' ('.$sStyle.')';
	$iParent      = $objDb->getField(0, "brand_id");
	$iBrand       = $objDb->getField(0, "sub_brand_id");
	$sBrand       = $objDb->getField(0, "_Brand");
	$sSeason      = $objDb->getField(0, "_Season");
        $sCategory    = $objDb->getField(0, "_Category");


	$iDestination = getDbValue("destination_id", "tbl_po_colors", "po_id='$iPo'");
	$sDestination = getDbValue("destination", "tbl_destinations", "id='$iDestination'");
        $sEtdDate     = getDbValue("etd_required", "tbl_po_colors", "po_id='$iPo'");
	$fAql         = getDbValue("aql", "tbl_brands", "id='$iParent'");

	$sSizeTitles  = "";

	$sSQL = "SELECT size FROM tbl_sizes WHERE id IN ($sSizes) ORDER BY position";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sSizeTitles .= (", ".$objDb->getField($i, 0));

		$sSizeTitles = substr($sSizeTitles, 2);
	}



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

	
	$sSizesPages  = getDbValue("GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',')", "tbl_qa_report_samples", "audit_id='$Id'");
	$iSizePages   = @explode(",", $sSizesPages);
	$iColorPages  = @explode(",", $sColors);
/*
	$iSizePages   = count($iSizePages);
	$iColorPages  = count($iColorPages);

	if ($iSizePages >= $iColorPages)
		$iPages = $iSizePages;
	
	else if ($iColorPages >= $iSizePages)
		$iPages = $iColorPages;

	$iTotalPages  = 1;
	$iTotalPages += $iPages;
	$iTotalPages += count($sSpecsSheets);
	$iTotalPages += @ceil(count($sDefects) / 4);
	$iTotalPages += @ceil(count($sPacking) / 4);
	$iTotalPages += @ceil(count($sMisc) / 4);
*/


	$sQaSignatures  = getDbValue("signature", "tbl_signatures", "name LIKE '$sAuditor' AND FIND_IN_SET('$iVendor', vendors) AND FIND_IN_SET('$iBrand', brands) AND type='M'");
	$sFtySignatures = getDbValue("signature", "tbl_signatures", "FIND_IN_SET('$iVendor', vendors) AND FIND_IN_SET('$iBrand', brands) AND type='F'");
	$sFtyAuditor    = getDbValue("name", "tbl_signatures", "FIND_IN_SET('$iVendor', vendors) AND FIND_IN_SET('$iBrand', brands) AND type='F'");
	

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Page 1


	$objPdf =& new FPDI( );

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/billabong-final.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);


	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);
	$objPdf->Text(184, 27.5, "Audit Code: {$sAuditCode}");



	// Report Details
	$objPdf->SetFont('Arial', '', 5);

	$objPdf->Text(37.2, 44, $sBrand);
	$objPdf->Text(37.2, 50, date('d M Y',strtotime($sEtdDate)));
	$objPdf->Text(37.2, 56, "MATRIX Sourcing");
	
	$objPdf->Text(79.5, 44, $sStyle);
        $objPdf->Text(79.5, 50, $sCategory);
	$objPdf->Text(79.5, 56, $sVendor);	
	
	$objPdf->Text(116, 44, $sSeason);
	$objPdf->Text(116, 50, "Pakistan");	
	
	$objPdf->SetXY(115, 53);
	$objPdf->MultiCell(38, 2.2, $sDescription, 0, "L");	
	
	$objPdf->Text(180, 56, getDbValue("COUNT(1)", "tbl_qa_reports", "audit_stage!='F' AND style_id='$iStyle' AND audit_result!=''"));	

	

	$sSQL = "SELECT CONCAT(order_no, ' ', order_status), id FROM tbl_po WHERE id='$iPo' OR FIND_IN_SET(id, '$iAdditionalPos') ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	
	$iTop      = 68;
	$iPoCount  = 0;
	$sColors   = @explode(",", $sColors);
	$iShipment = 0;
	$iQuantity = 0;
	
	for ($i = 0; $i < $iCount; $i ++)
	{          
		$sPoNo = $objDb->getField($i, 0);
		$iPoId = $objDb->getField($i, 1);
		
		
		foreach ($sColors as $sColor)
		{
			$iColorQty = getDbValue("SUM(order_qty)", "tbl_po_colors", "po_id='$iPoId' AND color LIKE '$sColor'");
			
			$iQuantity += $iColorQty;


			$sSQL2 = "SELECT * FROM tbl_bbg_final_pos WHERE audit_id='$Id' AND po_id='$iPoId' AND color='$sColor'";
			$objDb2->query($sSQL2);
			
			$iShipment += $objDb2->getField(0, "shipment");
				
				
			if ($iPoCount <= 5)
			{
				$iDestinations = getDbValue("GROUP_CONCAT(DISTINCT(destination_id))", "tbl_po_colors", "po_id='$iPoId' AND color LIKE '$sColor'");
				$sDestinations = getDbValue("GROUP_CONCAT(destination SEPARATOR ',')", "tbl_destinations", "FIND_IN_SET(id, '$iDestinations')");			
				
				$objPdf->Text(17, $iTop, $sPoNo);
				$objPdf->Text(38, $iTop, $sDestinations);				
				$objPdf->Text(80, $iTop, formatNumber($iColorQty, false));
				$objPdf->Text(98, $iTop, $sColor);
				

				$objPdf->Text(138, $iTop, $objDb2->getField(0, "cutting"));
				$objPdf->Text(158, $iTop, $objDb2->getField(0, "shipment"));
				$objPdf->Text(178, $iTop, $objDb2->getField(0, "ex_fty"));
				
				$iTop += 6.5;
				$iPoCount ++;
			}
		}
	}

	
	$objPdf->Text(155, 44, formatNumber($iQuantity, false));
	$objPdf->Text(155, 50, formatNumber($iShipment, false));

        $objPdf->SetDrawColor(0, 0, 240);
        $objPdf->Line(187, 41.5, 182, 44.5);
        
        $objPdf->SetDrawColor(0, 0, 240);
        $objPdf->Line(187, 47.5, 182, 50.5);
	
	$sSQL = "SELECT * FROM tbl_bbg_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	// first column
	if($objDb->getField(0, 'trim_access') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 58, 110.5, 2);
        else if($objDb->getField(0, 'trim_access') == 'N')
			$objPdf->Text(65, 111.5, 'X');
	else if($objDb->getField(0, 'trim_access') == 'NA')
			$objPdf->Text(72, 111.5, 'N/A');

	if($objDb->getField(0, 'scw_detail') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 58, 116.0, 2);
      	else if($objDb->getField(0, 'scw_detail') == 'N')
			$objPdf->Text(65, 117.0, 'X');
	else if($objDb->getField(0, 'scw_detail') == 'NA')
			$objPdf->Text(72, 117.0, 'N/A');

	if($objDb->getField(0, 'shipped_sbdc_ratio') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 58, 121.0, 2);
        else if($objDb->getField(0, 'shipped_sbdc_ratio') == 'N')
			$objPdf->Text(65, 122.5, 'X');
	else if($objDb->getField(0, 'shipped_sbdc_ratio') == 'NA')
			$objPdf->Text(72, 122.5, 'N/A');

	if($objDb->getField(0, 'cqnas_details') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 58, 126.0, 2);
        else if($objDb->getField(0, 'cqnas_details') == 'N')
			$objPdf->Text(65, 128.0, 'X');
	else if($objDb->getField(0, 'cqnas_details') == 'NA')
			$objPdf->Text(72, 128.0, 'N/A');

	// Second column
	 if($objDb->getField(0, 'test_report') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 117, 110.5, 2);
        else if($objDb->getField(0, 'test_report') == 'N')
			$objPdf->Text(123, 111.5, 'X');
	else if($objDb->getField(0, 'test_report') == 'NA')
			$objPdf->Text(129, 111.5, 'N/A');

	if($objDb->getField(0, 'carton_drop_test_record') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 117, 116.0, 2);
        else if($objDb->getField(0, 'carton_drop_test_record') == 'N')
		$objPdf->Text(123, 117.0, 'X');
	else if($objDb->getField(0, 'carton_drop_test_record') == 'NA')
		$objPdf->Text(129, 117.0, 'N/A');

	if($objDb->getField(0, 'needle_detect_record') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 117, 121.0, 2);
        else if($objDb->getField(0, 'needle_detect_record') == 'N')
		$objPdf->Text(123, 122.5, 'X');
	else if($objDb->getField(0, 'needle_detect_record') == 'NA')
		$objPdf->Text(129, 122.5, 'N/A');

	if($objDb->getField(0, 'pull_test_report') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 117, 126.0, 2);
        else if($objDb->getField(0, 'pull_test_report') == 'N')
		$objPdf->Text(123, 128.0, 'X');
	else if($objDb->getField(0, 'pull_test_report') == 'NA')
		$objPdf->Text(129, 128.0, 'N/A');

	// Third column
	 if($objDb->getField(0, 'actual_packing_list') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 176, 110.5, 2);
        else if($objDb->getField(0, 'actual_packing_list') == 'N')
		$objPdf->Text(181, 111.5, 'X'); 
	else if($objDb->getField(0, 'actual_packing_list') == 'NA')
		$objPdf->Text(188, 111.5, 'N/A');

	if($objDb->getField(0, 'carton_mdw') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 175, 116.0, 2);
        else if($objDb->getField(0, 'carton_mdw') == 'N')
		$objPdf->Text(181, 117.0, 'X');
	else if($objDb->getField(0, 'carton_mdw') == 'NA')
		$objPdf->Text(188, 117.0, 'N/A');

	if($objDb->getField(0, 'packing_method') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 175, 121.0, 2);
        else if($objDb->getField(0, 'packing_method') == 'N')
		$objPdf->Text(181, 122.5, 'X');
	else if($objDb->getField(0, 'packing_method') == 'NA')
		$objPdf->Text(188, 122.5, 'N/A');

	if($objDb->getField(0, 'packaging_trims') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 175, 126.0, 2);
        else if($objDb->getField(0, 'packaging_trims') == 'N')
		$objPdf->Text(181, 128.0, 'X');
	else if($objDb->getField(0, 'packaging_trims') == 'NA')
		$objPdf->Text(188, 128.0, 'N/A');

	
	$MeasurementWashStatus = $objDb->getField(0, 'measurement_wash_status');
        
        if($sAuditStatus == '2nd')
        {
            $objPdf->Text(42, 146, $sAuditStatus);
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 19.3, 143, 3);
        }
        else
        {
            $objPdf->Text(42, 140, $sAuditStatus);
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 19.3, 137.2, 3);
        }
	
	if($sAuditResult == 'P')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 105.5, 148.5, 3);
	
	else if($sAuditResult == 'F')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 130.0, 148.5, 3);
	
	else if($sAuditResult == 'H')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 154.0, 148.5, 3);
	
	else if($sAuditResult == 'R')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 185.0, 148.5, 3);

	
  //Display Quantity Details Inspections
	$sSQL = "SELECT * FROM tbl_bbg_carton_details WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$objPdf->Text(80, 133, $iTotalGmts);
	$objPdf->Text(117, 133, $objDb->getField(0, 'carton_qty'));
	$objPdf->Text(155, 133, $objDb->getField(0, 'count_accuracy').' %');		
	$objPdf->Text(184, 133, (($objDb->getField(0, 'count_result') == 'P') ? 'Pass' : 'Fail'));
	
	$iLeft = 80;
	
	for ($i = 1; $i <= 12 ; $i++ )
	{
		$objPdf->Text($iLeft, 140, $objDb->getField(0, 'carton_no'.$i));
		$objPdf->Text($iLeft, 145.5, $objDb->getField(0, 'count_error'.$i));
		
		$iLeft += 9.5;
	}
	
	

	$objPdf->Text(39, 171, "0");
	$objPdf->Text(48, 171, "1");
	
	$objPdf->Text(39, 176, $iAqlChart[$iTotalGmts]["2.5"]);
	$objPdf->Text(48, 176, ($iAqlChart[$iTotalGmts]["2.5"] + 1));
	
	$objPdf->Text(58, 171, getDbValue("COUNT(1)", "tbl_qa_report_defects", "audit_id='$Id' AND nature='2'"));
	$objPdf->Text(58, 176, getDbValue("COUNT(1)", "tbl_qa_report_defects", "audit_id='$Id' AND nature='1'"));
   
	
	$sCriticalDefects = getList("tbl_defect_types", 
	                            "type_code", 
								"(SELECT SUM(qad.defects) FROM tbl_qa_report_defects qad, tbl_defect_codes dc WHERE dc.id=qad.code_id AND qad.audit_id='$Id' AND qad.nature='2' AND dc.type_id=tbl_defect_types.id)",
								"NOT ISNULL(type_code) AND type_code!=''",
								"type_code",
								"type_code");
	
	$objPdf->Text(69, 171, formatNumber($sCriticalDefects['S,H&C'], false));
	$objPdf->Text(79, 171, formatNumber($sCriticalDefects['F'], false));
	$objPdf->Text(89, 171, formatNumber($sCriticalDefects['ODC'], false));
	$objPdf->Text(99, 171, formatNumber($sCriticalDefects['C'], false));
	$objPdf->Text(108, 171, formatNumber($sCriticalDefects['W'], false));
	$objPdf->Text(118, 171, formatNumber($sCriticalDefects['P'], false));
	$objPdf->Text(127, 171, formatNumber($sCriticalDefects['E'], false));
	$objPdf->Text(136, 171, formatNumber($sCriticalDefects['L'], false));
	$objPdf->Text(146, 171, formatNumber($sCriticalDefects['M'], false));
	$objPdf->Text(156, 171, formatNumber($sCriticalDefects['T'], false));
	$objPdf->Text(165, 171, formatNumber($sCriticalDefects['I'], false));
	$objPdf->Text(175, 171, formatNumber($sCriticalDefects['PK'], false));
	$objPdf->Text(185, 171, formatNumber($sCriticalDefects['MF'], false));

		
	
	$sMajorDefects = getList("tbl_defect_types", 
	                         "type_code", 
							 "(SELECT SUM(qad.defects) FROM tbl_qa_report_defects qad, tbl_defect_codes dc WHERE dc.id=qad.code_id AND qad.audit_id='$Id' AND qad.nature='1' AND dc.type_id=tbl_defect_types.id)",
							 "NOT ISNULL(type_code) AND type_code!=''",
							 "type_code",
							 "type_code");
							 
	$objPdf->Text(69, 176, formatNumber($sMajorDefects['S,H&C'], false));
	$objPdf->Text(79, 176, formatNumber($sMajorDefects['F'], false));
	$objPdf->Text(89, 176, formatNumber($sMajorDefects['ODC'], false));
	$objPdf->Text(99, 176, formatNumber($sMajorDefects['C'], false));
	$objPdf->Text(108, 176, formatNumber($sMajorDefects['W'], false));
	$objPdf->Text(118, 176, formatNumber($sMajorDefects['P'], false));
	$objPdf->Text(127, 176, formatNumber($sMajorDefects['E'], false));
	$objPdf->Text(136, 176, formatNumber($sMajorDefects['L'], false));
	$objPdf->Text(146, 176, formatNumber($sMajorDefects['M'], false));
	$objPdf->Text(156, 176, formatNumber($sMajorDefects['T'], false));
	$objPdf->Text(165, 176, formatNumber($sMajorDefects['I'], false));
	$objPdf->Text(175, 176, formatNumber($sMajorDefects['PK'], false));
	$objPdf->Text(185, 176, formatNumber($sMajorDefects['MF'], false));
						 
			 

     //Defects Display
    $sSQL = "SELECT code_id, 
	                GROUP_CONCAT(DISTINCT(area_id) SEPARATOR ',') AS _Areas, 
	                GROUP_CONCAT(DISTINCT(cap) SEPARATOR '\n') AS _Cap, 
					SUM(IF(nature='2', defects, '0')) AS _Critical,
					SUM(IF(nature='1', defects, '0')) AS _Major,
					GROUP_CONCAT(DISTINCT(remarks) SEPARATOR '\n') AS _Remarks
      	     FROM tbl_qa_report_defects
			 WHERE audit_id='$Id'
			 GROUP BY code_id
			 ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iTop = 188;
	
	if ($iCount > 8)
		$iTotalPages += (@ceil($iCount / 8) - 1);
	
//	$objPdf->SetFont('Arial', '', 6);
//	$objPdf->Text(15, 32, "Page 1 of {$iTotalPages}");
	
	$objPdf->SetFont('Arial', '', 5);
	


	for($i = 0; $i < $iCount; $i ++)
	{
		$iCritical = $objDb->getField($i, "_Critical");
		$iMajor    = $objDb->getField($i, "_Major");
		
		
		$sSQL2 = ("SELECT defect, (SELECT type_code from tbl_defect_types where tbl_defect_codes.type_id=tbl_defect_types.id) as _Code FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
		$objDb2->query($sSQL2);

		$sDefect     = $objDb2->getField(0, 0);		
		$sDefectCode = $objDb2->getField(0, 1);

		
		$sSQL3 = ("SELECT GROUP_CONCAT(area SEPARATOR ', ') FROM tbl_defect_areas WHERE id IN (".$objDb->getField($i, '_Areas').")");
		$objDb3->query($sSQL3);
		
		$sDefectAreas = $objDb3->getField(0, 0);



		$objPdf->Text(18, $iTop, $sDefectCode);
		
		$objPdf->SetXY(37, ($iTop - 2.2));
		$objPdf->MultiCell(40, 2, $sDefect, 0, "L");
		
		$objPdf->SetXY(78, ($iTop - 2.2));
		$objPdf->MultiCell(27, 2, $sDefectAreas, 0, "L");		
		
		$objPdf->Text(108, $iTop, $iCritical);
		$objPdf->Text(117, $iTop, $iMajor);
		
		$objPdf->SetXY(125, ($iTop - 2.2));
		$objPdf->MultiCell(47, 2, $objDb->getField($i, '_Cap'), 0, "L");
		
		$objPdf->SetXY(172.5, ($iTop - 2.2));
		$objPdf->MultiCell(20, 2, $objDb->getField($i, '_Remarks'), 0, "L");

		
		$iTop += 6.6;
		
		if ($i == 7)
			break;
	}
	
	

	//Comments Display
	$objPdf->SetXY(16, 241.0);
	$objPdf->MultiCell(175, 2.2, $sComments, 0);
	
	$objPdf->Text(79, 265, $sAuditDate);
	
	
	if ($sQaSignatures != "" && @file_exists($sBaseDir.SIGNATURES_IMG_DIR.$sQaSignatures))
		$objPdf->Image(($sBaseDir.SIGNATURES_IMG_DIR.$sQaSignatures), 38.0, 252.9, 12);
	
	$objPdf->Text(37, 265, $sAuditor);
	
	
	if ($sFtySignatures != "" && @file_exists($sBaseDir.SIGNATURES_IMG_DIR.$sFtySignatures))
		$objPdf->Image(($sBaseDir.SIGNATURES_IMG_DIR.$sFtySignatures), 127.0, 252.9, 12);
	
	$objPdf->Text(124.5, 265, $sFtyAuditor);
        
        if($sAuditResult == 'F')
            $objPdf->Text(175, 265, ($FinalAuditDate == ''?'0000-00-00':$FinalAuditDate));
	

	$iCurrentPage = 2;

	
	// Adding Additional Defects Page
	if ($iCount >= 8)
	{
		$i ++;

		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/billabong-final-defects.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');

		
		for ($iTop = 56; $i < $iCount; $i ++, $iTop += 6.6)
		{
			if ($iTop == 56)
			{
				$objPdf->addPage("P", "A4");
				$objPdf->useTemplate($iTemplateId, 0, 0);

//				$objPdf->SetFont('Arial', '', 6);
//				$objPdf->Text(15, 32, "Page {$iCurrentPage} of {$iTotalPages}");


				if ($sQaSignatures != "" && @file_exists($sBaseDir.SIGNATURES_IMG_DIR.$sQaSignatures))
					$objPdf->Image(($sBaseDir.SIGNATURES_IMG_DIR.$sQaSignatures), 38.0, 252.9, 12);
				
				$objPdf->Text(37, 265, $sAuditor);
				
				
				if ($sFtySignatures != "" && @file_exists($sBaseDir.SIGNATURES_IMG_DIR.$sFtySignatures))
					$objPdf->Image(($sBaseDir.SIGNATURES_IMG_DIR.$sFtySignatures), 127.0, 252.9, 12);
				
				$objPdf->Text(124.5, 265, $sFtyAuditor);

				
				$objPdf->SetFont('Arial', '', 5);
				
				
				$objPdf->SetXY(16, 241.0);
				$objPdf->MultiCell(175, 2.2, $sComments, 0);
				
				$objPdf->Text(79, 256, $sAuditDate);

				
				$iCurrentPage ++;
			}
			
			
			$iCritical = $objDb->getField($i, "_Critical");
			$iMajor    = $objDb->getField($i, "_Major");
			
			
			$sSQL2 = ("SELECT defect, (SELECT type_code from tbl_defect_types where tbl_defect_codes.type_id=tbl_defect_types.id) as _Code FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
			$objDb2->query($sSQL2);
			
			$sDefect     = $objDb2->getField(0, 0);
			$sDefectCode = $objDb2->getField(0, 1);			

			
			$sSQL3 = ("SELECT GROUP_CONCAT(area SEPARATOR ', ') FROM tbl_defect_areas WHERE id IN (".$objDb->getField($i, '_Areas').")");
			$objDb3->query($sSQL3);
			
			$sDefectAreas = $objDb3->getField(0, 0);


			$objPdf->Text(18, $iTop, $sDefectCode);
			
			$objPdf->SetXY(37, ($iTop - 2.2));
			$objPdf->MultiCell(40, 2, $sDefect, 0);
			
			$objPdf->SetXY(78, ($iTop - 2.2));
			$objPdf->MultiCell(27, 2, $sDefectAreas, 0);		
			
			$objPdf->Text(108, $iTop, $iCritical);
			$objPdf->Text(117, $iTop, $iMajor);
			
			$objPdf->SetXY(125, ($iTop - 2.2));
			$objPdf->MultiCell(47, 2, $objDb->getField($i, '_Cap'), 0);
			
			$objPdf->SetXY(172.5, ($iTop - 2.2));
			$objPdf->MultiCell(20, 2, $objDb->getField($i, '_Remarks'), 0);

			
			if ($iTop >= 238)
				$iTop = 56;
		}
	}
	

 /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 2


	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/billabong-measurements.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$sSizes  = getDbValue("GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',')", "tbl_qa_report_samples", "audit_id='$Id'");
	$iSizes  = @explode(",", $sSizes);

	$iSamplesMeasured = getDbValue("COUNT(size_id)", "tbl_qa_report_samples", "audit_id='$Id'");
	$sQtyPerSize      = "";

	foreach ($sColors as $sColor)
	{
		foreach ($iSizes as $iSize)
		{
			if ($sQtyPerSize != "")
				$sQtyPerSize .= ", ";

			$sSize = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");

			$sQtyPerSize .= ("{$sSize} (".getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize' AND color LIKE '$sColor'").")");

		}
	}


	foreach ($sColors as $sColor)
	{
		foreach ($iSizes as $iSize)
		{
			$sSQL = "SELECT qrs.sample_no, qrss.point_id, qrss.findings
					 FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
					 WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSize' AND qrs.color='$sColor'
					 ORDER BY qrs.sample_no, qrss.point_id";
			$objDb->query($sSQL);

			$iCount        = $objDb->getCount( );
			$sSizeFindings = array( );

			for($i = 0; $i < $iCount; $i ++)
			{
				$iSampleNo = $objDb->getField($i, 'sample_no');
				$iPoint    = $objDb->getField($i, 'point_id');
				$sFindings = $objDb->getField($i, 'findings');

				$sSizeFindings["{$iSampleNo}-{$iPoint}"] = $sFindings;
			}


			if ($iCount == 0)
				continue;

			
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			// QR Code
			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 181, 10, 21.5);


			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(177, 33, "Audit Code: {$sAuditCode}");


//			$objPdf->SetFont('Arial', '', 10);
//			$objPdf->Text(5, 33, "Page {$iCurrentPage} of {$iTotalPages}");

			$sSize = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");

			
			$objPdf->SetFont('Arial', '', 6);
			$objPdf->Text(26, 37, $sBrand);
			$objPdf->Text(86, 37, $sStyle);
			$objPdf->Text(130, 37, $iPo);

			$objPdf->SetXY(165, 36);
			$objPdf->MultiCell(165, 0, $sQtyPerSize, 0, "L");
		    
			// $objPdf->Text(118, 37, $sSeason);
			$objPdf->Text(26, 44, $sColor);
			$objPdf->Text(86, 44, $sSize);
			$objPdf->Text(140, 44, $sDescription);			
			
			$objPdf->Text(26, 51, "MATRIX Sourcing");
			$objPdf->Text(85, 51, $sVendor);


			$objPdf->SetFont('Arial', '', 6);

			if($MeasurementWashStatus == 'B')
				$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 123, 49, 3);
			
			else if($MeasurementWashStatus == 'A')
				$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 145, 49, 3);
			
			else if($MeasurementWashStatus == 'N')
				$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 163, 49, 3);


			
			$iSamplesChecked = getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize' AND color LIKE '$sColor'");
				

			$sSQL = "SELECT point_id, specs,
							(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
							(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point
					 FROM tbl_style_specs
					 WHERE style_id='$iStyle' AND size_id='$iSize' AND version='0' AND specs!='0' AND specs!=''
					 ORDER BY id
					 LIMIT 26";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
			$iOut   = 0;
            $iTop   = 78.1;

			for($i = 0; $i < $iCount; $i ++)
			{
				$iPoint     = $objDb->getField($i, 'point_id');
				$sSpecs     = $objDb->getField($i, 'specs');
				$sPoint     = $objDb->getField($i, '_Point');
				$sTolerance = $objDb->getField($i, '_Tolerance');


				$objPdf->SetFont('Arial', '', 7);
				$objPdf->Text(13, $iTop, ($i + 1));

				$objPdf->SetXY(20.5, $iTop);
				$objPdf->MultiCell(70.5, 0, $sPoint, 0, "L");

				$objPdf->SetFont('Arial', '', 6);
				$objPdf->Text(106.3, $iTop, $sTolerance);

				$objPdf->SetFont('Arial', '', 7);
				$objPdf->Text(118, $iTop, $sSpecs);


                $iLeft           = 6.9;
				
				for ($j = 1; $j <= $iSamplesChecked; $j ++)
				{
					if ($sSizeFindings["{$j}-{$iPoint}"] != "" && strtolower($sSizeFindings["{$j}-{$iPoint}"]) != "ok" && $sSizeFindings["{$j}-{$iPoint}"] != "0")
					{
						$objPdf->SetFillColor(255, 255, 0);
						$objPdf->SetXY(119.8+$iLeft, $iTop-2.5);
						$objPdf->Cell(8.8, 4.7, $sSizeFindings["{$j}-{$iPoint}"], 0, 0, "C", true);

						$iOut ++;
					}

					else
					    $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 123.3+$iLeft, $iTop-2, 3);
                    
					$iLeft += 7.6;
				}
                
				$iTop += 5.18;
			}

			$objPdf->Text(24, 257, "{$sAuditor} / MATRIX Sourcing");
			$objPdf->Text(106, 257, formatDate($sAuditDate));


			$iCurrentPage ++;
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 3  -  DEFECT IMAGES


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

//			$objPdf->SetFont('Arial', '', 10);
//			$objPdf->Text(6, 37, "Page {$iCurrentPage} of {$iTotalPages}");



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

//			$objPdf->SetFont('Arial', '', 10);
//			$objPdf->Text(6, 37, "Page {$iCurrentPage} of {$iTotalPages}");


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

//			$objPdf->SetFont('Arial', '', 10);
//			$objPdf->Text(6, 37, "Page {$iCurrentPage} of {$iTotalPages}");


			$objPdf->Image($sSpecsSheets[$i], 10, 47, 190);
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

//			$objPdf->SetFont('Arial', '', 10);
//			$objPdf->Text(6, 37, "Page {$iCurrentPage} of {$iTotalPages}");



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