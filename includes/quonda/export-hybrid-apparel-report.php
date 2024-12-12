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
    @require_once($sBaseDir."requires/fpdi/Transparent.php");
	@require_once($sBaseDir."requires/qrcode/qrlib.php");

        function ConvertToFloatValue($str){
            
            $num = explode(' ', $str);
            
            if( strpos(@$num[0], '/') !== false ){
                $num1 = explode('/', @$num[0]);
                $num1 = @$num1[0] / @$num1[1];
            }else
                $num1= @$num[0];
            
            if( strpos(@$num[1], '/') !== false ){
                $num2 = explode('/', @$num[1]);
                $num2 = @$num2[0] / @$num2[1];
            }else
                $num2= @$num[1];
            
            return number_format(($num1 + $num2),2);
        }
        
		
		
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

        $sSQL = "SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id='$iPo'";
	$objDb->query($sSQL);

	$iQuantity = $objDb->getField(0, 0);
        
        $sSQL = "SELECT CONCAT(order_no, ' ', order_status), quantity FROM tbl_po WHERE id IN ($iAdditionalPos) ORDER BY order_no";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAdditionalPos .= (",".$objDb->getField($i, 0));
		$iQuantity      += $objDb->getField($i, 1);
	}

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


	$sCountry  = getDbValue("place_of_departure", "tbl_po", "id='$iPo'");
	$sShipMode = getDbValue("way_of_dispatch", "tbl_po", "id='$iPo'");
        $sEtdDate  = getDbValue("etd_required", "tbl_po_colors", "po_id='$iPo'");
	$fAql      = getDbValue("aql", "tbl_brands", "id='$iParent'");

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

		if (@stripos($sPic, "_pack_") !== FALSE) // || @stripos($sPic, "_001_") !== FALSE)
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
        
        $sSQL = "SELECT * FROM tbl_hybrid_apparel_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$iTotalCtn   = $objDb->getField(0, "total_ctns");
        $sFabric     = $objDb->getField(0, "fabric");
        $sContent    = $objDb->getField(0, "content");
        $sWeight     = $objDb->getField(0, "weight");
        $sRib        = $objDb->getField(0, "rib");
        $sLabelSize  = $objDb->getField(0, "label_size");
        $sThread     = $objDb->getField(0, "thread");
        $sMResult    = $objDb->getField(0, "measurement_result");
        $sMRemarks   = $objDb->getField(0, "measurement_remarks");
        
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Page 1


	$objPdf = new AlphaPDF( );

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hybrid-apparel.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 2, 24);


	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");


	// Report Details
	$objPdf->SetFont('Arial', '', 6);
        $objPdf->Text(48, 46, $sAuditDate);
        $objPdf->Text(48, 49, $sStartTime);
        $objPdf->Text(48, 52, $sStyle);
        $objPdf->Text(48, 55.2, $sPo);
        $objPdf->Text(48, 58.5, $sDescription);
        $objPdf->Text(48, 62, $sColors);
        $objPdf->Text(48, 65.2, $sShipMode);
        $objPdf->Text(48, 69, $iTotalCtn);
        $objPdf->Text(48, 72.2, $iQuantity);
        
        $objPdf->Text(155, 52, $sVendor);
        $objPdf->Text(155, 55.2, $sVendor);
        $objPdf->Text(155, 58.5, $sCountry);
        
       
        if($sAuditResult == 'P'){
            $objPdf->SetFont('Arial', 'B', 6);
            $objPdf->SetTextColor(0, 100, 0);
            $objPdf->Text(161, 69, 'Pass');
        }
        elseif($sAuditResult == 'F'){
            $objPdf->SetFont('Arial', 'B', 6);
            $objPdf->SetTextColor(255, 0, 0);
            $objPdf->Text(161, 69, 'Fail');
        }
        elseif($sAuditResult == 'H'){
            $objPdf->SetFont('Arial', 'B', 6);
            $objPdf->SetTextColor(0, 0, 255);
            $objPdf->Text(161, 69, 'Hold');
        }
        elseif($sAuditResult == 'R'){
            $objPdf->SetFont('Arial', 'B', 6);
            $objPdf->SetTextColor(0, 0, 255);
            $objPdf->Text(161, 69, 'Re-Inspection');
        }
            
               
        $objPdf->SetTextColor(50, 50, 50);
        $objPdf->SetFont('Arial', '', 6);
        $objPdf->Text(192, 81, $iTotalGmts);
        
         // set alpha to semi-transparency
        $objPdf->SetAlpha(0.4);
        $objPdf->SetFillColor(255, 255, 40);
        
        if($iTotalGmts>0 && $iTotalGmts<=5)
            $objPdf->Rect(8.8, 91.8, 99.5, 3.5, 'DF');
        else if($iTotalGmts>5 && $iTotalGmts<=13)
            $objPdf->Rect(8.8, 95.5, 99.5, 3.5, 'DF');
        else if($iTotalGmts>13 && $iTotalGmts<=20)
            $objPdf->Rect(8.8, 99.2, 99.5, 3.5, 'DF');
        else if($iTotalGmts>20 && $iTotalGmts<=32)
            $objPdf->Rect(8.8, 102.9, 99.5, 3.5, 'DF');
        else if($iTotalGmts>32 && $iTotalGmts<=50)
            $objPdf->Rect(8.8, 106.6, 99.5, 3.5, 'DF');
        else if($iTotalGmts>50 && $iTotalGmts<=80)
            $objPdf->Rect(8.8, 110.3, 99.5, 3.5, 'DF');
        else if($iTotalGmts>80 && $iTotalGmts<=125)
            $objPdf->Rect(8.8, 114, 99.5, 3.5, 'DF');
        else if($iTotalGmts>125 && $iTotalGmts<=200)
            $objPdf->Rect(8.8, 117.7, 99.5, 3.5, 'DF');
        else if($iTotalGmts>200 && $iTotalGmts<=315)
            $objPdf->Rect(8.8, 121.4, 99.5, 3.5, 'DF');
        else if($iTotalGmts>315)
            $objPdf->Rect(8.8, 124.6, 99.5, 3.5, 'DF');
            
        // restore full opacity
        $objPdf->SetAlpha(1);
        $objPdf->SetTextColor(50, 50, 50);

        //Defects Display
        $sSQL = "SELECT code_id, 
	                GROUP_CONCAT(DISTINCT(area_id) SEPARATOR ',') AS _Areas, 
	                SUM(IF(nature='0', defects, '0')) AS _Minor,
                        SUM(IF(nature='1', defects, '0')) AS _Major
                FROM tbl_qa_report_defects
                WHERE audit_id='$Id'
                GROUP BY code_id
                ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
        $iTop = 91.5;
        
        $iTotalMinor = 0;
        $iTotalMajor = 0;
        
	for($i = 0; $i < $iCount; $i ++)
	{
            $iMinor  = $objDb->getField($i, "_Minor");
            $iMajor  = $objDb->getField($i, "_Major");
            
            if($i <= 12){
            	$sSQL2 = ("SELECT defect, (SELECT type_code from tbl_defect_types where tbl_defect_codes.type_id=tbl_defect_types.id) as _Code FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
		$objDb2->query($sSQL2);

                $sDefect     = $objDb2->getField(0, 0);		
		$sDefectCode = $objDb2->getField(0, 1);

                $iTotalMinor += $iMinor;
                $iTotalMajor += $iMajor;

                $objPdf->SetXY(110, ($iTop - 2));
		$objPdf->MultiCell(40, 2, $sDefect, 0);
                
                $objPdf->Text(165, $iTop, $iMinor);
		$objPdf->Text(180, $iTop, $iMajor);
                $fPercantage = number_format((($iMinor + $iMajor)/$iTotalGmts)*100,2);
                $objPdf->Text(193, $iTop, $fPercantage);
                
                $iTop += 3.5;
                
            }
        }
        
        $objPdf->Text(165, 133.2, $iTotalMinor);
        $objPdf->Text(180, 133.2, $iTotalMajor);
        $fPercantageTotal = number_format((($iTotalMinor + $iTotalMajor)/$iTotalGmts)*100,2);
        $objPdf->Text(193, 133.2, $fPercantageTotal);
        
        $objPdf->Text(20, 176.5, $sFabric);
        $objPdf->Text(20, 179.5, $sContent);
        $objPdf->Text(20, 182.5, $sWeight);
        $objPdf->Text(20, 186, $sRib);
        $objPdf->Text(28, 189, $sLabelSize);
        $objPdf->Text(20, 192.2, $sThread);

        
        //specs 
        $objPdf->SetFont('Arial', '', 5);
        $sSizeFindings = array( );
        
        $sSQL = "SELECT qrs.audit_id, qrs.sample_no, qrss.point_id, qrss.findings
                FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
                WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id
                ORDER BY qrss.point_id, qrs.sample_no";
        $objDb->query($sSQL);
        $iCount        = $objDb->getCount( );
        $iLastPoint   = 0; 
        
        for($i = 0; $i < $iCount; $i ++)
        {
            $iPoint    = $objDb->getField($i, 'point_id');
            $iSampleNo = $objDb->getField($i, 'sample_no');
            $sFindings = $objDb->getField($i, 'findings');
            
            if($iLastPoint != $iPoint)
                $SampleCount = 0;
            
            $SampleCount++;
            $sSizeFindings["{$i}-{$iSampleNo}-{$iPoint}"] = array('finiding' => $sFindings, 'sample_size' => $SampleCount);
            $iLastPoint = $iPoint;
        }

        $sSpecsList       = getList("tbl_style_specs", "point_id", "specs", " style_id='$iStyle' AND version='0' AND specs!='' AND specs!='0'", "point_id");
        $sToleranceList   = getList("tbl_measurement_points", "id", "tolerance", "tolerance!=''", "id");
        $sPointList       = getList("tbl_measurement_points", "id", "point", "tolerance!=''", "id");
        
        $iLastPoint = 0;
        $sSpecArray = array();
            
        foreach ($sSizeFindings as $sSampleNPoint => $sFindings)
        {
            
            $sSamplePoint  = @explode("-", $sSampleNPoint);
            $sFinding      = $sFindings['finiding'];
            $iSampleSize   = $sFindings['sample_size'];
            $iPoint        = @$sSamplePoint[2];
            $sSpecs        = @$sSpecsList[$iPoint];
            $sPoint        = @$sPointList[$iPoint];
            $sTolerance    = @$sToleranceList[$iPoint];

            if ($sFinding == "" && strtolower($sFinding) == "ok")
                    $sFinding = $sSpecs;

            $fMeaseuredValue  = abs(ConvertToFloatValue($sFinding));
            $fSpecValue       = abs(ConvertToFloatValue($sSpecs));
            $fTolerance       = abs(ConvertToFloatValue($sTolerance));

            $DifferenceValue   = $fSpecValue - $fMeaseuredValue;
            $PositiveTolerance = $fSpecValue + $fTolerance;
            $NegativeTolerance = $fSpecValue - $fTolerance;
            
            if($iPoint != $iLastPoint){
                $TotalPercent  = 0;
                $MajorDefects  = 0;
                $MinorDefects  = 0;
                $iTotalSum     = 0;
            }
            
            if($fMeaseuredValue >= $NegativeTolerance && $fMeaseuredValue <= $PositiveTolerance)
            {
                //continue;
            }
            else
            {
                $fPercent = (abs($DifferenceValue)/$fSpecValue)*100;
              
                if($fPercent > 10)
                    $MajorDefects++;
                else if($fPercent > 0 && $fPercent < 10)
                    $MinorDefects++;
                
                $TotalPercent = (($MajorDefects+$MinorDefects)/$iSampleSize)*100;
            }
            
            $sSpecArray[$iPoint] = array('point'=> $iPoint, 'major'=>$MajorDefects, 'minor'=>$MinorDefects, 'percent' => $TotalPercent, 'sample_size' => $iSampleSize);
            $iLastPoint = $iPoint;
        }
        
        $sort = array();
        foreach($sSpecArray as $k=>$v) {
            $sort['major'][$k] = $v['major'];
            $sort['minor'][$k] = $v['minor'];
        }
        array_multisort($sort['major'], SORT_DESC, $sort['minor'], SORT_DESC,$sSpecArray);
        
        $iTop               = 144;
        $iTotalMajor        = 0;
        $iTotalMinor        = 0;
        $iTotalPercent      = 0;
        $limit              = 0;
        $iTotalSampleSizes  = 0;
        
        foreach($sSpecArray as $iKey => $sDefectsArr){
            
            if($limit < 8 && $sDefectsArr['percent']>0){
                
                $iPoint = $sDefectsArr['point'];
                        
                $iTotalMajor       += $sDefectsArr['major'];
                $iTotalMinor       += $sDefectsArr['minor'];
                $iTotalPercent     += $sDefectsArr['percent'];
                $iTotalSampleSizes += $sDefectsArr['sample_size'];
                
                $objPdf->SetXY(108, ($iTop - 2));
                $objPdf->MultiCell(50, 1.3, @$sPointList[$iPoint], 0);

                $objPdf->Text(165, $iTop, $sDefectsArr['minor']);
                $objPdf->Text(180, $iTop, $sDefectsArr['major']);
                $objPdf->Text(193, $iTop, number_format($sDefectsArr['percent'],2));

                $iTop += 3.15;

            }   
            $limit++;
        }
        $iGenealPercent = ((($iTotalMinor/$iTotalSampleSizes)*100)+(($iTotalMajor/$iTotalSampleSizes)*100))/2;
        $objPdf->Text(165, $iTop, number_format($iTotalMinor));
        $objPdf->Text(180, $iTop, number_format($iTotalMajor));
        $objPdf->Text(193, $iTop, number_format(($iGenealPercent),2).'%');
        
        if($iGenealPercent > 20){
            
            $objPdf->SetFont('Arial', 'B', 6);
            $objPdf->SetTextColor(255, 0, 0);
            $objPdf->Text(161, 72.2, 'Fail');
            
        }else{
            
            if($sMResult == 'P'){
                $objPdf->SetFont('Arial', 'B', 6);
                $objPdf->SetTextColor(0, 100, 0);
                $objPdf->Text(161, 72.2, 'Pass');
            }            
            elseif($sMResult == 'F'){
                $objPdf->SetFont('Arial', 'B', 6);
                $objPdf->SetTextColor(255, 0, 0);
                $objPdf->Text(161, 72.2, 'Fail');
            }            
            elseif($sMResult == 'H'){
                $objPdf->SetFont('Arial', 'B', 6);
                $objPdf->SetTextColor(0, 0, 255);
                $objPdf->Text(161, 72.2, 'Pending');
            }
        }
        
        
         $objPdf->SetFont('Arial', '', 6);
         $objPdf->SetTextColor(50, 50, 50);
        //Comments Display
        $objPdf->SetXY(10, 210.2);
	$objPdf->MultiCell(180, 3.1, $sComments, 0);
        $objPdf->Text(60, 259, $sAuditor);
    //--------------------------------------------------------------------------------------///
        
	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hybrid-apparel.pdf");
	$iTemplateId = $objPdf->importPage(2, '/MediaBox');

	$sSizes  = getDbValue("GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',')", "tbl_qa_report_samples", "audit_id='$Id'");
	$iSizes  = @explode(",", $sSizes);
        $sQtyPerSize      = "";

        
        $sColors  = @explode(",", $sColors);
        
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
                    
                    for($iSampleNum=1; $iSampleNum<=5; $iSampleNum++)
                    {
			$sSQL = "SELECT qrs.sample_no, qrss.point_id, qrss.findings
					 FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
					 WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSize' AND qrs.color='$sColor' AND qrs.sample_no='$iSampleNum'
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


			$sSize = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");

			
			$objPdf->SetFont('Arial', '', 6);
                        $objPdf->Text(20, 46, $iPo);
			$objPdf->Text(20, 49, $sStyle);
                        $objPdf->Text(20, 52, $sColor);
			$objPdf->Text(20, 55, $sSize);
			
			$objPdf->SetFont('Arial', '', 6);

			$sSQL = "SELECT point_id, specs,
							(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
							(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point
					 FROM tbl_style_specs
					 WHERE style_id='$iStyle' AND size_id='$iSize' AND version='0' AND specs!='' AND specs!='0'
					 ORDER BY id
					 LIMIT 26";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
			$iTop   = 65.2;

			for($i = 0; $i < $iCount; $i ++)
			{
				$iPoint     = $objDb->getField($i, 'point_id');
				$sSpecs     = $objDb->getField($i, 'specs');
				$sPoint     = $objDb->getField($i, '_Point');
				$sTolerance = $objDb->getField($i, '_Tolerance');


				$objPdf->SetFont('Arial', '', 7);
				$objPdf->Text(10, $iTop, ($i + 1).'-');

				$objPdf->SetXY(13, $iTop-1.5);
				$objPdf->MultiCell(130, 2, $sPoint, 0, "L");
                                
                                $objPdf->Text(141, $iTop, $sSpecs);
                                $objPdf->Text(188, $iTop, $sTolerance);

				$iLeft           = 6.9;
                                
				
				$sFindings = $sSizeFindings["{$iSampleNum}-{$iPoint}"];

				if ($sFindings == "" && strtolower($sFindings) == "ok")
					$sFindings = $sSpecs;
				
                                
                                $objPdf->Text(150+$iLeft, $iTop, $sFindings);

                                $MeaseuredValue    = ConvertToFloatValue($sFindings);
                                $PositiveTolerance = ConvertToFloatValue($sSpecs) + ConvertToFloatValue($sTolerance);
                                $NegativeTolerance = ConvertToFloatValue($sSpecs) - ConvertToFloatValue($sTolerance);
                                $DifferenceValue   = ConvertToFloatValue($sSpecs) - $MeaseuredValue;

                                $objPdf->SetFont('Arial', 'B', 7);

                                if($MeaseuredValue >= $NegativeTolerance && $MeaseuredValue <= $PositiveTolerance)
                                        $objPdf->SetTextColor(0, 100, 0);
                                else
                                        $objPdf->SetTextColor(200, 0, 0);

                                $objPdf->Text(172, $iTop, 0-$DifferenceValue);
                    
				$iLeft += 7.6;
                                        
                                $objPdf->SetFont('Arial', '', 7);
				$objPdf->SetTextColor(50, 50, 50);
                
				$iTop += 3.65;
			}

			$objPdf->Text(60, 260, "{$sAuditor} / MATRIX Sourcing");
			
			$iCurrentPage ++;
             }
		}
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 3  -  DEFECT IMAGES


	if (count($sDefects) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hybrid-apparel-page.pdf");
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
			$objPdf->Text(6, 38, "Defect Images");

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
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hybrid-apparel-page.pdf");
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
			$objPdf->Text(6, 38, "Packing Images");

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
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hybrid-apparel-page.pdf");
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


			$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheets[$i]), 10, 47, 190);
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 7  -  MISC IMAGES


	if (count($sMisc) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hybrid-apparel-page.pdf");
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
			$objPdf->Text(6, 38, "Miscellaneous Images");

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