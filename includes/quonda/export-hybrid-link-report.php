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
                        (SELECT country_id FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _iCountry,
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
        $iCountry           = $objDb->getField(0, "_iCountry");
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
        $sAuditType         = $objDb->getField(0, "audit_type");
        $AuditQuantity      = $objDb->getField(0, "audit_quantity");
                
				
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
	

        $sSQL = "SELECT * FROM tbl_hybrid_link_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);
        
        $AssortmentQty      = $objDb->getField(0, "assortment_qty");
        $AssortmentQtySize  = $objDb->getField(0, "assortment_qty_size");
        $SolidSizeQty       = $objDb->getField(0, "solid_size_qty");
        $IsFullPacket       = $objDb->getField(0, "is_box_full");
        $ShipmentDate       = $objDb->getField(0, "shipment_date");
        $CartonNos          = $objDb->getField(0, "carton_nos");
        $MeasurementPoints  = $objDb->getField(0, "measurement_points");
        $MeasureSampleSize  = $objDb->getField(0, "measurement_sample_size");
        $TotalTolerance     = $objDb->getField(0, "total_tolerance_pts");
        $PackingResult      = $objDb->getField(0, "packing_result");
        $ConformityResult   = $objDb->getField(0, "conformity_result");

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


        $sCountry   = getDbValue("country", "tbl_countries", "id='$iCountry'");
	$sCity      = getDbValue("place_of_departure", "tbl_po", "id='$iPo'");
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
        
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Page 1


	$objPdf = new AlphaPDF( );

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hybrid-link.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 14, 21);


	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(181, 36, "Audit Code: {$sAuditCode}");


	// Report Details
	$objPdf->SetFont('Arial', '', 8);
        $objPdf->Text(38, 42, $sAuditDate);
        $objPdf->Text(38, 47.5, $sVendor);
        $objPdf->Text(38, 52.5, $sCountry);
        
        if($sAuditResult == 'P'){
            $objPdf->SetFont('Arial', 'B', 6);
            $objPdf->SetTextColor(0, 100, 0);
            $objPdf->Text(185, 47, 'Pass');
        }
        elseif($sAuditResult == 'F'){
            $objPdf->SetFont('Arial', 'B', 6);
            $objPdf->SetTextColor(255, 0, 0);
            $objPdf->Text(185, 47, 'Fail');
        }
        elseif($sAuditResult == 'H'){
            $objPdf->SetFont('Arial', 'B', 6);
            $objPdf->SetTextColor(0, 0, 255);
            $objPdf->Text(185, 47, 'Hold');
        }
        elseif($sAuditResult == 'R'){
            $objPdf->SetFont('Arial', 'B', 6);
            $objPdf->SetTextColor(0, 0, 255);
            $objPdf->Text(185, 47, 'Re-Inspection');
        }
        
        $objPdf->SetTextColor(50, 50, 50);
        $objPdf->SetFont('Arial', '', 8);
        
        
        $objPdf->Text(38, 65, $sPo);
        $objPdf->Text(38, 69.4, $sStyle);
        $objPdf->Text(38, 73.7, $sColors);
        $objPdf->Text(38, 77.7, $iQuantity);
        $objPdf->Text(38, 82, $iShipQty);
        $objPdf->Text(38, 86, $AssortmentQty);
        $objPdf->Text(80, 86, $AssortmentQtySize);
        $objPdf->Text(38, 90, $SolidSizeQty);
        $objPdf->Text(80, 90, ($IsFullPacket == 'Y')?'Full Packet':'Blank');
        $objPdf->Text(38, 94, ceil($iShipQty/($AssortmentQty+$SolidSizeQty)));
        $objPdf->Text(38, 98, $ShipmentDate);
        
        
        $objPdf->Text(142, 65, ($sAuditType == 'B')?'Bulk':($sAuditType == 'BG'?'B-Grade':'Sales Sample'));
        $objPdf->Text(142, 69.4, $AuditQuantity);
        $objPdf->Text(142, 73.7, $iTotalGmts);
        
        $CartonPulled = 0;
        if($IsFullPacket == 'N')
            $CartonPulled = ceil($iTotalGmts/12);
        else if($IsFullPacket == 'Y')
            $CartonPulled = ceil($iTotalGmts/6);
        
        $objPdf->Text(142, 90, $CartonPulled);
        
        $objPdf->SetXY(142, 93.5);
        $objPdf->MultiCell(50, 2.5, rtrim($CartonNos, ","), 0);
                                     
        $objPdf->SetTextColor(50, 50, 50);
        $objPdf->SetFont('Arial', '', 6);
        
        //Defects Display
        $sSQL = "SELECT code_id, 
	                GROUP_CONCAT(DISTINCT(area_id) SEPARATOR ',') AS _Areas, 
	                SUM(IF(nature='0', defects, '0')) AS _Minor,
                        SUM(IF(nature='1', defects, '0')) AS _Major,
                        SUM(IF(nature='2', defects, '0')) AS _Critical
                FROM tbl_qa_report_defects
                WHERE audit_id='$Id'
                GROUP BY code_id
                ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
        $iTop = 120;
        
        $iTotalCritical = 0;
        $iTotalMinor    = 0;
        $iTotalMajor    = 0;
        
	for($i = 0; $i < $iCount; $i ++)
	{
            $iMinor     = $objDb->getField($i, "_Minor");
            $iMajor     = $objDb->getField($i, "_Major");
            $iCritical  = $objDb->getField($i, "_Critical");
            
            
                    
            $sSQL2 = ("SELECT defect, (SELECT type_code from tbl_defect_types where tbl_defect_codes.type_id=tbl_defect_types.id) as _Code, (SELECT type FROM tbl_defect_types WHERE id=tbl_defect_codes.type_id) as _Type FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
            $objDb2->query($sSQL2);

            $sDefect     = $objDb2->getField(0, 0);		
            $sDefectCode = $objDb2->getField(0, 1);
            $sDefectType = $objDb2->getField(0, 2);
            
            $sSQL3 = ("SELECT GROUP_CONCAT(area SEPARATOR ', ') FROM tbl_defect_areas WHERE id IN (".$objDb->getField($i, '_Areas').")");
            $objDb3->query($sSQL3);

            $sDefectAreas = $objDb3->getField(0, 0);

            $iTotalMinor    += $iMinor;
            $iTotalMajor    += $iMajor;
            $iTotalCritical += $iCritical;
                    
            $objPdf->Text(8, $iTop, $sDefectType);
            
            if(strlen($sDefect) > 90)
            {
                $objPdf->SetFont('Arial', '', 4);
                $objPdf->SetXY(47, ($iTop - 3));
                $objPdf->MultiCell(90, 2, $sDefect, 0);
                $objPdf->SetFont('Arial', '', 6);
            }
            else{
                $objPdf->SetXY(47, ($iTop - 1.8));
                $objPdf->MultiCell(90, 2, $sDefect, 0);
            }
            
            $objPdf->Text(125, $iTop, $sDefectAreas);
            $objPdf->Text(160, $iTop, $iCritical);
            $objPdf->Text(175, $iTop, $iMajor);
            $objPdf->Text(190, $iTop, $iMinor);

            $iTop += 4.15;
            
        }
        
        $objPdf->Text(160, 225.5, $iTotalCritical);
        $objPdf->Text(175, 225.5, $iTotalMajor);
        $objPdf->Text(190, 225.5, $iTotalMinor);

        $objPdf->Text(160, 229.8, '0');
        $objPdf->Text(175, 229.8, @$iAqlChart[$iTotalGmts]['2.5']);
        $objPdf->Text(190, 229.8, @$iAqlChart[$iTotalGmts]['4']);
        
        $objPdf->SetFont('Arial', '', 8);
        
        if($iTotalCritical < 1 && $iTotalMajor <= @$iAqlChart[$iTotalGmts]['2.5'] && $iTotalMinor <= @$iAqlChart[$iTotalGmts]['4'])
            $sWorkMenShipResult = "Pass";
        else
            $sWorkMenShipResult = "Fail";
        
        $objPdf->Text(182, 234.5, $sWorkMenShipResult);
        
        $objPdf->Text(142, 77.8, $iTotalCritical);
        $objPdf->Text(142, 82.1, $iTotalMajor);
        $objPdf->Text(142, 86.2, $iTotalMinor);
        
        $objPdf->SetFont('Arial', '', 6);
        
        $objPdf->Text(50, 248, $MeasureSampleSize);
        $objPdf->Text(50, 254, $MeasurementPoints);
        $objPdf->Text(157, 248, $TotalTolerance);
        $objPdf->Text(157, 254, formatNumber($MeasurementPoints*0.04,2));
        $objPdf->Text(157, 260, ((($MeasurementPoints*0.04)>$TotalTolerance)?'Fail':'Pass'));
        
        /////////////// Page #2 //////////////////////////
        $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hybrid-link.pdf");
        $iTemplateId = $objPdf->importPage(2, '/MediaBox');
        
        $objPdf->addPage("P", "A4");
        $objPdf->useTemplate($iTemplateId, 0, 0);

        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 14, 21);

        $objPdf->SetFont('Arial', '', 7);
        $objPdf->SetTextColor(50, 50, 50);

        $objPdf->Text(179, 36, "Audit Code: {$sAuditCode}");

        $sCheckListP = getList("tbl_hybrid_link_report_checks", "id", "title", "type='P'", "id");
        $sCheckListG = getList("tbl_hybrid_link_report_checks", "id", "title", "type='G'", "id");
                    
        $objPdf->SetFont('Arial', '', 7);
        
        $iTop = 56.2;
        foreach($sCheckListP as $iCheck => $sCheck)
        {
            $result = getDbValue("result", "tbl_hybrid_link_report_check_details", "audit_id='$Id' AND check_id='$iCheck'");        
            
            if($result == 'NA')
                $result = 'Not Applicabl';
            else if($result == 'NC')
                $result = 'Not Conform';
            else if($result == 'C')
                $result = 'Conform';
            else 
                $result = '';

            $objPdf->Text(50, $iTop, $result);        
            $objPdf->Text(95, $iTop, getDbValue("remarks", "tbl_hybrid_link_report_check_details", "audit_id='$Id' AND check_id='$iCheck'"));        
            
            $iTop += 4.15; 
        }
        $objPdf->Text(100, 102.4, ($PackingResult == 'P')?'Pass':($PackingResult == 'F'? 'Fail' : ''));
        
        
        $iTop = 128;
        foreach($sCheckListG as $iCheck => $sCheck)
        {
            $result = getDbValue("result", "tbl_hybrid_link_report_check_details", "audit_id='$Id' AND check_id='$iCheck'");        
            
            if($result == 'NA')
                $result = 'Not Applicabl';
            else if($result == 'NC')
                $result = 'Not Conform';
            else if($result == 'C')
                $result = 'Conform';
            else 
                $result = '';
           
            $objPdf->Text(50, $iTop, $result);        
            $objPdf->Text(95, $iTop, getDbValue("remarks", "tbl_hybrid_link_report_check_details", "audit_id='$Id' AND check_id='$iCheck'"));        
            
            $iTop += 4.15; 
        }
        
        $objPdf->Text(95, 174.5, ($ConformityResult == 'P')?'Pass':($ConformityResult == 'F'? 'Fail' : ''));
        $objPdf->Text(50, 195, $sAuditor);
        $objPdf->Text(50, 201, $sAuditDate);
                
       /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 3  -  DEFECT IMAGES

	if (count($sDefects) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hybrid-link.pdf");
		$iTemplateId = $objPdf->importPage(4, '/MediaBox');

		$iPages = @ceil(count($sDefects) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 16, 21);



			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(180, 38, "Audit Code: {$sAuditCode}");

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


				$iLeft = 10;
				$iTop  = 50;

				if ($j == 1 || $j == 3)
					$iLeft = 107;

				if ($j == 2 || $j == 3)
					$iTop = 155;


				$sInfo  = "Type: {$sType}\n";
				$sInfo .= "Defect: {$sDefect}\n";
				$sInfo .= ("Area: ".getDbValue("area", "tbl_defect_areas", "id='$sAreaCode'")."\n");

				$objPdf->SetXY($iLeft, ($iTop + 90.5));
				$objPdf->MultiCell(90, 3.6, $sInfo, 1, "L", false);


				$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sDefects[$iIndex]), $iLeft, $iTop, 90, 90);
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


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 16, 21);



			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(179, 38, "Audit Code: {$sAuditCode}");
                        
                        $objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(10, 37, "Packing Images");
                        
			for ($j = 0; $j < 4 && $iIndex < count($sPacking); $j ++, $iIndex ++)
			{
				$iLeft = 10;
				$iTop  = 50;

				if ($j == 1 || $j == 3)
					$iLeft = 107;

				if ($j == 2 || $j == 3)
					$iTop = 150;

				$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPacking[$iIndex]), $iLeft, $iTop, 92, 92);
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


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 16, 21);

			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(180, 38, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(10, 37, "Lab Reports / Specs Sheets");

			$objPdf->Image($sSpecsSheets[$i], 10, 47, 190, 210);
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


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 16, 21);



			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(180, 38, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(10, 38, "Miscellaneous Images");

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