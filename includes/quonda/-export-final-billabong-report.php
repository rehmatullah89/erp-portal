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


	$sAuditStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");
	$iQuantity   = getDbValue("SUM(quantity)", "tbl_po_quantities", "po_id='$iPo'");


	$sSQL = "SELECT style, style_name, brand_id, sub_brand_id,
                	(SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand,
					(SELECT season FROM tbl_seasons WHERE tbl_seasons.brand_id=tbl_styles.brand_id order By tbl_styles.modified DESC Limit 0,1) AS _Season
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

	
	$sSizesPages  = getDbValue("GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',')", "tbl_qa_report_samples", "audit_id='$Id'");
	$iSizePages   = @explode(",", $sSizesPages);
	$iColorPages  = @explode(",", $sColors);
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


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Page 1


	$objPdf =& new FPDI( );

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/BBG_Final_Inspection_Report.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);


	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);
	$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");

	$objPdf->SetFont('Arial', '', 11);
	$objPdf->Text(5, 33, "Page 1 of {$iTotalPages}");


	// Report Details
	$objPdf->SetFont('Arial', '', 4);
	$objPdf->Text(74, 61, $sVendor);
	$objPdf->Text(112, 61, $sDescription);

	$objPdf->Text(34, 51, $sBrand);
	$objPdf->Text(34, 57, date('d M Y',strtotime($sEtdDate)));
	$objPdf->Text(34, 61, "MATRIX Sourcing");
	
	$objPdf->Text(75, 51, $sStyle);
	
	$objPdf->Text(114, 51, $sSeason);
	$objPdf->Text(114, 57, "Pakistan");	

	if(!empty($iAdditionalPos))
		$sTotalPos = $iPo.','.$iAdditionalPos;

    else
        $sTotalPos = $iPo;

    $sAdditionalPos = "";
	
	$sSQL = "SELECT CONCAT(order_no, ' ', order_status), quantity, id FROM tbl_po WHERE id IN ($sTotalPos)";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	
	$height = 78;
	$heights = 79;
	$count = 0;
	
	for ($i = 0; $i < $iCount; $i ++)
	{          
		$sThisPo       = $objDb->getField($i, 0);
		$iThisQuantity = $objDb->getField($i, 1);
		$cPo           =  $objDb->getField($i, 2);
		
		$sColorList = getList('tbl_po_colors', 'id', 'color', "po_id='$cPo'");
		
		foreach ($sColorList as $sColor)
		{
			if ($count < 3)
			{                    
				$objPdf->Text(63, $height, $sColor);
				$objPdf->Text(15, $height, $sThisPo);
				$objPdf->Text(33, $height, $sDestination);
				
				$objPdf->Text(47, $height, formatNumber($iThisQuantity, false));

				$sSQL2 = "SELECT * FROM tbl_bbg_final_pos WHERE audit_id = '$Id' AND  po_id = '$cPo' AND color='$sColor'";
				$objDb2->query($sSQL2);

				$objPdf->Text(75, $height, $objDb2->getField(0, "cutting"));
				$objPdf->Text(85, $height, $objDb2->getField(0, "shipment"));
				$objPdf->Text(95, $height, $objDb2->getField(0, "ex_fty"));

				$height += 7.5;
			}
			
			else if ($count >=3 && $count < 6)
			{                    
				$objPdf->Text(150, $heights, $sColor);
				$objPdf->Text(105, $heights, $sThisPo);
				$objPdf->Text(122, $heights, $sDestination);
				$objPdf->Text(135, $heights, formatNumber($iThisQuantity, false));

				$sSQL2 = "SELECT * FROM tbl_bbg_final_pos WHERE audit_id = '$Id' AND  po_id = '$cPo' AND color='$sColor'";
				$objDb2->query($sSQL2);

				$objPdf->Text(160, $heights, $objDb2->getField(0, "cutting"));
				$objPdf->Text(170, $heights, $objDb2->getField(0, "shipment"));
				$objPdf->Text(180, $heights, $objDb2->getField(0, "ex_fty"));

				$heights += 7.5;
			}
			
			$count ++;
		}
            
		$iQuantity += $objDb->getField($i, 1);
	}

	$objPdf->Text(154, 51, formatNumber($iQuantity, false));
	$objPdf->Text(154, 57, formatNumber($iShipQty, false));


	$sSQL = "SELECT * FROM tbl_bbg_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	// first column
	if($objDb->getField(0, 'trim_access') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 55, 100.0, 2);
	else if($objDb->getField(0, 'trim_access') == 'N')
			$objPdf->Text(66, 100, 'X');

	if($objDb->getField(0, 'scw_detail') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 55, 105.0, 2);
	else if($objDb->getField(0, 'scw_detail') == 'N')
			$objPdf->Text(66, 106, 'X');

	if($objDb->getField(0, 'shipped_sbdc_ratio') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 55, 111.0, 2);
	else if($objDb->getField(0, 'shipped_sbdc_ratio') == 'N')
			$objPdf->Text(66, 112, 'X');

	if($objDb->getField(0, 'cqnas_details') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 55, 118.0, 2);
	else if($objDb->getField(0, 'cqnas_details') == 'N')
			$objPdf->Text(66, 119, 'X');

	// Second column
	 if($objDb->getField(0, 'test_report') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 114, 101.0, 2);
	else if($objDb->getField(0, 'test_report') == 'N')
			$objPdf->Text(124, 102, 'X');

	if($objDb->getField(0, 'carton_drop_test_record') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 114, 105.0, 2);
	else if($objDb->getField(0, 'carton_drop_test_record') == 'N')
		$objPdf->Text(124, 107, 'X');

	if($objDb->getField(0, 'needle_detect_record') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 114, 111.0, 2);
	else if($objDb->getField(0, 'needle_detect_record') == 'N')
		$objPdf->Text(124, 113, 'X');

	if($objDb->getField(0, 'pull_test_report') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 114, 118.0, 2);
	else if($objDb->getField(0, 'pull_test_report') == 'N')
		$objPdf->Text(124, 120, 'X');

	// Third column
	 if($objDb->getField(0, 'actual_packing_list') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 173, 100.0, 2);
	else if($objDb->getField(0, 'actual_packing_list') == 'N')
		$objPdf->Text(184, 101, 'X');

	if($objDb->getField(0, 'carton_mdw') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 173, 105.0, 2);
	else if($objDb->getField(0, 'carton_mdw') == 'N')
		$objPdf->Text(184, 106, 'X');

	if($objDb->getField(0, 'packing_method') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 173, 111.0, 2);
	else if($objDb->getField(0, 'packing_method') == 'N')
		$objPdf->Text(184, 112, 'X');

	if($objDb->getField(0, 'packaging_trims') == 'Y')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 173, 118.0, 2);
	else if($objDb->getField(0, 'packaging_trims') == 'N')
		$objPdf->Text(184, 119, 'X');

	$MeasurementWashStatus = $objDb->getField(0, 'measurement_wash_status');

	$objPdf->Text(33, 131, $Id);
	$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 15, 129.0, 3);

	if($sAuditResult == 'P')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 103, 140.0, 3);
	else if($sAuditResult == 'F')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 126, 140.0, 3);
	else if($sAuditResult == 'H')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 146, 140.0, 3);
	else if($sAuditResult == 'R')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 176, 140.0, 3);

	
  //Display Quantity Details Inspections
	$sSQL = "SELECT * FROM tbl_bbg_carton_details WHERE audit_id='$Id'";
	$objDb->query($sSQL);

        $objPdf->Text(80, 125, $iTotalGmts);

        $objPdf->Text(34, 163, "0");
        $objPdf->Text(43, 163, "1");
		
        $objPdf->Text(34, 168, $iAqlChart[$iTotalGmts]["2.5"]);
        $objPdf->Text(43, 168, ($iAqlChart[$iTotalGmts]["2.5"] + 1));
		
        $objPdf->Text(53, 163, getDbValue("COUNT(1)", "tbl_qa_report_defects", "audit_id='$Id' AND nature='2'"));
		$objPdf->Text(53, 168, getDbValue("COUNT(1)", "tbl_qa_report_defects", "audit_id='$Id' AND nature='1'"));

		
        $objPdf->Text(115, 125, $objDb->getField(0, 'carton_qty'));
        $objPdf->Text(152, 125, $objDb->getField(0, 'count_accuracy'));
        
		if($objDb->getField(0, 'count_result') == 'P')
            $objPdf->Text(180, 125, 'Pass');
		
        else if($objDb->getField(0, 'count_result') == 'F')
            $objPdf->Text(180, 125, 'Fail');
        
		$ci_width = 78;
		
        for($ci = 1; $ci <= 12 ; $ci++ )
		{
            $objPdf->Text($ci_width, 131, $objDb->getField(0, 'carton_no'.$ci));
            $objPdf->Text($ci_width, 137, $objDb->getField(0, 'count_error'.$ci));
			
            $ci_width += 9.5;
        }

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
	$height2 = 181;

	$M_m    = 0;
	$M_c    = 0;
	$SHnC_m = 0;
	$SHnC_c = 0;
	$F_m    = 0;
	$F_c    = 0;
	$ODC_m  = 0;
	$ODC_c  = 0;
	$C_m    = 0;
	$C_c    = 0;
	$W_m    = 0;
	$W_c    = 0;
	$P_m    = 0;
	$P_c    = 0;
	$E_m    = 0;
	$E_c    = 0;
	$L_m    = 0;
	$L_c    = 0;
	$T_m    = 0;
	$T_c    = 0;
	$I_m    = 0;
	$I_c    = 0;
	$PK_m   = 0;
	$PK_c   = 0;
	$MF_m   = 0;
	$MF_c   = 0;

	for($i = 0; $i < $iCount; $i ++)
	{
		$iCritical = $objDb->getField($i, "_Critical");
		$iMajor    = $objDb->getField($i, "_Major");
		
		
		$sSQL2 = ("SELECT (SELECT type_code from tbl_defect_types where tbl_defect_codes.type_id=tbl_defect_types.id) as _Code, defect FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
		$objDb2->query($sSQL2);
		
		$sDefectCode = $objDb2->getField(0, 0);
		$sDefect     = $objDb2->getField(0, 1);

		
		$sSQL3 = ("SELECT GROUP_CONCAT(area SEPARATOR ', ') FROM tbl_defect_areas WHERE id IN (".$objDb->getField($i, '_Areas').")");
		$objDb3->query($sSQL3);
		
		$sDefectAreas = $objDb3->getField(0, 0);

		
		if($sDefectCode == 'M')
			$M_m += $iCritical;
		if($sDefectCode == 'S,H&C')
			$SHnC_m += $iCritical;
		if($sDefectCode == 'F')
			$F_m += $iCritical;
		if($sDefectCode == 'ODC')
			$ODC_m += $iCritical;
		if($sDefectCode == 'C')
			$C_m += $iCritical;
		if($sDefectCode == 'W')
			$W_m += $iCritical;
		if($sDefectCode == 'P')
			$P_m += $iCritical;
		if($sDefectCode == 'E')
			$E_m += $iCritical;
		if($sDefectCode == 'L')
			$L_m += $iCritical;
		if($sDefectCode == 'T')
			$T_m += $iCritical;
		if($sDefectCode == 'I')
			$I_m += $iCritical;
		if($sDefectCode == 'PK')
			$PK_m += $iCritical;
		if($sDefectCode == 'MF')
			$MF_m += $iCritical;



		if($sDefectCode == 'M')
			$M_c += $iMajor;
		if($sDefectCode == 'S,H&C')
			$SHnC_c += $iMajor;
		if($sDefectCode == 'F')
			$F_c += $iMajor;
		if($sDefectCode == 'ODC')
			$ODC_c += $iMajor;
		if($sDefectCode == 'C')
			$C_c += $iMajor;
		if($sDefectCode == 'W')
			$W_c += $iMajor;
		if($sDefectCode == 'P')
			$P_c += $iMajor;
		if($sDefectCode == 'E')
			$E_c += $iMajor;
		if($sDefectCode == 'L')
			$L_c += $iMajor;
		if($sDefectCode == 'T')
			$T_c += $iMajor;
		if($sDefectCode == 'I')
			$I_c += $iMajor;
		if($sDefectCode == 'PK')
			$PK_c += $iMajor;
		if($sDefectCode == 'MF')
			$MF_c += $iMajor;


		$objPdf->Text(18, $height2, $sDefectCode);
		
		$objPdf->SetXY(32, ($height2 - 3.5));
		$objPdf->MultiCell(41, 2, $sDefect, 0);
		
		$objPdf->SetXY(73, ($height2 - 3.5));
		$objPdf->MultiCell(27, 2, $sDefectAreas, 0);		
		
		$objPdf->Text(106, $height2, $iCritical);
		$objPdf->Text(115, $height2, $iMajor);
		
		$objPdf->SetXY(121, ($height2 - 3.5));
		$objPdf->MultiCell(47, 2, $objDb->getField($i, '_Cap'), 0);
		
		$objPdf->SetXY(168, ($height2 - 3.5));
		$objPdf->MultiCell(20, 2, $objDb->getField($i, '_Remarks'), 0);

		
		$height2 += 6.5;
		
		if ($i == 7)
			break;
	}
	

	$objPdf->Text(67, 168, $SHnC_m);
	$objPdf->Text(67, 163, $SHnC_c);
	$objPdf->Text(77, 168, $F_m);
	$objPdf->Text(77, 163, $F_c);
	$objPdf->Text(87, 168, $ODC_m);
	$objPdf->Text(87, 163, $ODC_c);
	$objPdf->Text(97, 168, $C_m);
	$objPdf->Text(97, 163, $C_c);
	$objPdf->Text(106, 168, $W_m);
	$objPdf->Text(106, 163, $W_c);
	$objPdf->Text(116, 168, $P_m);
	$objPdf->Text(116, 163, $P_c);
	$objPdf->Text(125, 168, $E_m);
	$objPdf->Text(125, 163, $E_c);
	$objPdf->Text(134, 168, $L_m);
	$objPdf->Text(134, 163, $L_c);
	$objPdf->Text(144, 168, $M_m);
	$objPdf->Text(144, 163, $M_c);
	$objPdf->Text(154, 168, $T_m);
	$objPdf->Text(154, 163, $T_c);
	$objPdf->Text(163, 168, $I_m);
	$objPdf->Text(163, 163, $I_c);
	$objPdf->Text(173, 168, $PK_m);
	$objPdf->Text(173, 163, $PK_c);
	$objPdf->Text(183, 168, $MF_m);
	$objPdf->Text(183, 163, $MF_c);

	//Comments Display
	$objPdf->SetXY(12, 232.5);
	$objPdf->MultiCell(175, 2, $sComments, 0);
	
	$objPdf->Text(75, 245, $sAuditDate);

 /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 2


	$iCurrentPage = 2;
	$iPageCount   = $objPdf->setSourceFile($sBaseDir."templates/BBG_Measurment_Chart.pdf");
	$iTemplateId  = $objPdf->importPage(1, '/MediaBox');

	$sSizes  = getDbValue("GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',')", "tbl_qa_report_samples", "audit_id='$Id'");
	$iSizes  = @explode(",", $sSizes);
	$sColors = @explode(",", $sColors);

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
		$iSizeIndex = 0;

		foreach ($iSizes as $iSize)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			// QR Code
			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 181, 10, 21.5);


			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(177, 33, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 10);
			$objPdf->Text(5, 33, "Page {$iCurrentPage} of {$iTotalPages}");

			$sSize = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");

                        $objPdf->SetFont('Arial', '', 7);
                        $objPdf->Text(26, 37, $sBrand);
                        $objPdf->Text(86, 37, $sStyle);
                        $objPdf->Text(130, 37, $iPo);
                        $objPdf->SetXY(165, 36);
			$objPdf->MultiCell(165, 0, $sQtyPerSize, 0, "L");
                       // $objPdf->Text(118, 37, $sSeason);
                        $objPdf->Text(140, 44, $sDescription);
                        $objPdf->Text(26, 44, $sColor);
                        $objPdf->Text(86, 44, $sSize);
                        $objPdf->SetFont('Arial', '', 5);
                        $objPdf->Text(85, 51, $sVendor);


			$objPdf->SetFont('Arial', '', 7);

                        if($MeasurementWashStatus == 'B')
                            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 123, 49, 3);
                        if($MeasurementWashStatus == 'A')
                            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 145, 49, 3);
                        if($MeasurementWashStatus == 'N')
                            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 163, 49, 3);


			if (strtotime($sAuditDate) < strtotime("2015-11-26"))
			{
				$sSQL = "SELECT qrs.sample_no, qrss.point_id, qrss.findings
						 FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
						 WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSize' AND (qrs.color='$sColor' OR qrs.color='')
						 ORDER BY qrs.sample_no, qrss.point_id";
			}

			else
			{
				$sSQL = "SELECT qrs.sample_no, qrss.point_id, qrss.findings
						 FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
						 WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSize' AND qrs.color='$sColor'
						 ORDER BY qrs.sample_no, qrss.point_id";
			}

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


			$sSQL = "SELECT point_id, specs,
							(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
							(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point
					 FROM tbl_style_specs
					 WHERE style_id='$iStyle' AND size_id='$iSize' AND version='0'
					 ORDER BY id
					 LIMIT 26";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
			$iOut   = 0;
                        $mHeight = 78.1;

			for($i = 0; $i < $iCount; $i ++)
			{
				$iPoint     = $objDb->getField($i, 'point_id');
				$sSpecs     = $objDb->getField($i, 'specs');
				$sPoint     = $objDb->getField($i, '_Point');
				$sTolerance = $objDb->getField($i, '_Tolerance');


				$objPdf->SetFont('Arial', '', 7);
				$objPdf->Text(13, $mHeight, ($i + 1));

				$objPdf->SetXY(20.5, $mHeight);
				$objPdf->MultiCell(70.5, 0, $sPoint, 0, "L");

                                $objPdf->SetFont('Arial', '', 6);
                                $objPdf->Text(106.3, $mHeight, $sTolerance);

                                $objPdf->SetFont('Arial', '', 7);
				$objPdf->Text(118, $mHeight, $sSpecs);


				if (strtotime($sAuditDate) < strtotime("2015-11-26"))
					$iSamplesChecked = getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize'");

				else
					$iSamplesChecked = getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize' AND color LIKE '$sColor'");

                                $mWidth = 6.9;
				for ($j = 1; $j <= $iSamplesChecked; $j ++)
				{
					if ($sSizeFindings["{$j}-{$iPoint}"] != "" && strtolower($sSizeFindings["{$j}-{$iPoint}"]) != "ok" && $sSizeFindings["{$j}-{$iPoint}"] != "0")
					{
						$objPdf->SetFillColor(255, 255, 0);
						$objPdf->SetXY(119.8+$mWidth, $mHeight-2.5);
						$objPdf->Cell(8.8, 4.7, $sSizeFindings["{$j}-{$iPoint}"], 0, 0, "C", true);

						$iOut ++;
					}

					else
					    $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 123.3+$mWidth, $mHeight-2, 3);
                                    $mWidth += 7.6;
				}
                            $mHeight += 5.18;
			}

			$objPdf->Text(24, 257, "{$sAuditor} / MATRIX Sourcing");
			$objPdf->Text(106, 257, formatDate($sAuditDate));


			$iCurrentPage ++;
			$iSizeIndex ++;

			if ($iSizeIndex == 8)
				break;
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 3  -  DEFECT IMAGES


	if (count($sDefects) > 0)
	{
            //$iCurrentPage ++;
            //$iTotalPages++;

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