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
     ///ini_set('display_errors', 1);
     //error_reporting(E_ALL);
     
        @require_once($sBaseDir."requires/fpdf/fpdf.php");
	@require_once($sBaseDir."requires/fpdi/fpdi.php");
        @require_once($sBaseDir."requires/fpdi/Transparent.php");
	@require_once($sBaseDir."requires/qrcode/qrlib.php");

        
        function ConvertToFloatValue($str, $Sign="")
	{		
            if (strpos($str, '/') !== false)
            {
		$num = explode('/', trim($str));
		
                if(@$num[0] == '+')
                    return @abs(str_replace(" ", "", $num[1]));
                else 
                {
                    if($Sign == '-')
                       return @abs($num[0]);
                    else
                       return @abs($num[1]);
                }
            }
            else
                return abs($str);
	}
        
        function getFileContents($Url)
        {
            $arrContextOptions= array(
                            "ssl"=>array(
                                "verify_peer"=>false,
                                "verify_peer_name"=>false,
                            ),
                        ); 
            
            $response = file_get_contents("{$Url}", false, stream_context_create($arrContextOptions));
            
            return $response;
        }
        
        function convertPngToJpg($filePath, $quality=50)
        {
            $image = imagecreatefrompng($filePath);
            $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
            imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
            imagealphablending($bg, TRUE);
            imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
            imagedestroy($image);
            imagejpeg($bg, $filePath . ".jpg", $quality);
            imagedestroy($bg);
            
            return $filePath . ".jpg";
        }
        
	$sSQL = "SELECT *,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
                        (SELECT product_code FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _ProductCode,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
                        (SELECT code FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _VendorCode,
                        (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.unit_id) AS _Unit,
                        (SELECT latitude FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _VLatitude,
                        (SELECT longitude FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _VLongitude,
	                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor,
                        (SELECT picture FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _AuditorPicture
	         FROM tbl_qa_reports
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	$iReportId          = $objDb->getField(0, "report_id");
        $iAuditTypeId       = $objDb->getField(0, "audit_type_id");
        $sUnitId            = $objDb->getField(0, "_Unit");
	$iVendor            = $objDb->getField(0, "vendor_id");
	$sVendor            = $objDb->getField(0, "_Vendor");
        $sVendorCode        = $objDb->getField(0, "_VendorCode");
        $sProductCode       = $objDb->getField(0, "_ProductCode");
        $sVLatitude         = $objDb->getField(0, "_VLatitude");
        $sVLongitude        = $objDb->getField(0, "_VLongitude");
	$sAuditor           = $objDb->getField(0, "_Auditor");
        $sAuditorPciture    = $objDb->getField(0, "_AuditorPicture");
	$iPo                = $objDb->getField(0, "po_id");
	$iAdditionalPos     = $objDb->getField(0, "additional_pos");
	$sPo                = $objDb->getField(0, "_Po");
	$iStyle             = $objDb->getField(0, "style_id");
	$sColors            = $objDb->getField(0, "colors");
	$sSizes             = $objDb->getField(0, "sizes");
	$sAuditStatus       = $objDb->getField(0, "audit_status");
        $sAuditMode         = $objDb->getField(0, "audit_mode");
	$sAuditCode         = $objDb->getField(0, "audit_code");
	$sAuditDate         = $objDb->getField(0, "audit_date");
	$sStartTime         = $objDb->getField(0, "start_time");
	$sEndTime           = $objDb->getField(0, "end_time");
        $iAuditStage        = $objDb->getField(0, "audit_stage");
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
	$sLatitude          = $objDb->getField(0, "latitude");
	$sLongitude         = $objDb->getField(0, "longitude");
	$sLocation          = $objDb->getField(0, "location");

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


                if ($iAuditTypeId == 2 && (@in_array($iAuditStage, array('F','V','TG'))))
                {
                        if($iTotalGmts == 5 || $iTotalGmts == 13)
                            $sAuditStage = "Final";
                        else if(($iTotalGmts == 20 || $iTotalGmts == 32) && $iAuditStage == 'TG')
                        {
                            $sAuditStage = "Targeted";

                            if($iTotalGmts == 32)
                                $sAuditStage = "Validation";
                        }
                        else if($iTotalGmts == 32 && $iAuditStage != 'TG')
                        {
                            $sAuditStage = "Final";

                            $iDefectCount = getDbValue("COALESCE(SUM(defects), 0)", "tbl_qa_report_defects qrd", "audit_id='$Id' AND (sample_no > '5' AND sample_no <= '13')");

                            if($iDefectCount > 0)
                                $sAuditStage = "Validation";
                        }
                }
                else
                    $sAuditStage = getDbValue("stage", "tbl_audit_stages", "code='$iAuditStage'");


	$sSQL = "SELECT style, sketch_file, style_name, brand_id, sub_brand_id,
                        (SELECT category FROM tbl_style_categories WHERE id=tbl_styles.category_id) AS _Category,
                	(SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand,
			(SELECT season FROM tbl_seasons WHERE tbl_styles.sub_season_id = id  ORDER BY id LIMIT 1) AS _Season
			 FROM tbl_styles
			 WHERE id='$iStyle'";
        $objDb->query($sSQL);

        $sSketchFile  = $objDb->getField(0, 'sketch_file');
	$sStyle       = $objDb->getField(0, "style");
	$sStyleName   = $objDb->getField(0, "style_name");
        $sDescription = $sStyleName.' ('.$sStyle.')';
	$iParent      = $objDb->getField(0, "brand_id");
	$iBrand       = $objDb->getField(0, "sub_brand_id");
	$sBrand       = $objDb->getField(0, "_Brand");
	$sSeason      = $objDb->getField(0, "_Season");
        $sCategory    = $objDb->getField(0, "_Category");

        $sAuditTypes  = getList("tbl_audit_types", "id", "type");
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

	$sQaSignatures  = getDbValue("signature", "tbl_signatures", "name LIKE '$sAuditor' AND FIND_IN_SET('$iVendor', vendors) AND FIND_IN_SET('$iBrand', brands) AND type='M'");
	$sFtySignatures = getDbValue("signature", "tbl_signatures", "FIND_IN_SET('$iVendor', vendors) AND FIND_IN_SET('$iBrand', brands) AND type='F'");
	$sFtyAuditor    = getDbValue("name", "tbl_signatures", "FIND_IN_SET('$iVendor', vendors) AND FIND_IN_SET('$iBrand', brands) AND type='F'");

        $sSubQuery = ($iAdditionalPos!=''?("OR id IN ({$iAdditionalPos})"):"");    
        $iOrderedQty    = getDbValue("SUM(quantity)", "tbl_po", "id='$iPo' $sSubQuery");
	//////////////////////////////////////////////////////Page 1//////////////////////////////////////////////////////////////
	$objPdf = new AlphaPDF( );

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/levis.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 176, 11, 22);

        $objPdf->SetFont('Arial', '', 9);
        $objPdf->Text(10, 7, "Page #1");

	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);
	$objPdf->Text(177, 33.5, "Audit Code: {$sAuditCode}");

	// Report Details
	$objPdf->SetFont('Arial', '', 7);

	$objPdf->Text(45, 49, $sPo.($iAdditionalPos!=""?','. getDbValue("GROUP_CONCAT(CONCAT(order_no, ' ', order_status) SEPARATOR ',')", "tbl_po", "id IN ($iAdditionalPos)"):''));
        $objPdf->Text(45, 54.5, $sProductCode);
	$objPdf->Text(45, 59.5, "Levis");
        $objPdf->Text(45, 65, getDbValue("c.country", "tbl_countries c, tbl_vendors v", "c.id = v.country_id AND v.id='$iVendor'"));
        $objPdf->Text(45, 75, $sSeason);
        $objPdf->Text(45, 80.5, $sAuditDate);
        
        $objPdf->Text(140, 49, $sBrand);
        $objPdf->Text(140, 54.5, $sAuditCode);
        
        if (strlen("{$sVendorCode} ({$sVendor})") < 34)
                $objPdf->Text(140, 59.5, "{$sVendorCode} ({$sVendor})");        
        else
        {
                $objPdf->SetFont('Arial', '', 6.0);
                $objPdf->SetXY(139.5, 56.0);
                $objPdf->MultiCell(60, 3.0, "{$sVendorCode} ({$sVendor})", 0, "L");
        }
		
		$objPdf->SetFont('Arial', '', 7);
        $objPdf->Text(140, 70, $iOrderedQty);
        $objPdf->Text(140, 80.5, $sAuditor);
        
        
        $objPdf->Text(60, 102, ($iReportId == 44?"Tops":"Bottoms"));
        $objPdf->Text(60, 113, $sAuditTypes[$iAuditTypeId]);
        $objPdf->Text(60, 124, $sAuditStage);

        switch ($sAuditResult)
	{
		case "P" : $sAuditResult = "Pass"; break;
		case "F" : $sAuditResult = "Fail"; break;
		case "N" : $sAuditResult = "Fail-NV"; break;
		case "E" : $sAuditResult = "Exception"; break;
		case "R" : $sAuditResult = "Rescreen"; break;
	}
        
        $objPdf->Text(60, 136, $sAuditResult);
        
        if ($sSketchFile != "" && @file_exists($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile))
        {
            $objPdf->Image($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile, 145, 95, 45);
        }

        if ($sVLatitude != "" && $sVLongitude != "" && $sLatitude != "" && $sLongitude != "")
        {
            $sDistance = calculateDistance($sVLatitude, $sVLongitude, $sLatitude, $sLongitude);
            $objPdf->Text(30, 161.8, $sDistance);            
        }
            
        if ($sLatitude != "" && $sLongitude != "")
	{	
		//$sLocation = trim(trim(str_replace("\n", ", ", $sLocation)), ",");

                //$objPdf->Text(78, 145, $sLocation);
                
      		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(6, 82, 195);

                $objPdf->SetXY(92, 142);
		$objPdf->Write(5, "(". formatNumber($sLatitude, true, 8).",". formatNumber($sLongitude, true, 8).")", "http://maps.google.com/maps?q={$sLatitude},{$sLongitude}&z=12");
                
                $objPdf->SetFont('Arial', '', 7);
                $objPdf->SetTextColor(50, 50, 50);
                
                $objPdf->Text(128, 145.5, "(Click on the link to open location in Google Maps)");
                
                if($sVLatitude != "" && $sVLongitude != "")
                {
                    $objPdf->SetFont('Arial', '', 7);
                    $objPdf->SetTextColor(6, 82, 195);
                
                    $objPdf->SetXY(94, 147);
                    $objPdf->Write(5, "(". formatNumber($sVLatitude, true, 8).",". formatNumber($sVLongitude, true, 8).")", "http://maps.google.com/maps?q={$sVLatitude},{$sVLongitude}&z=12");

                    $objPdf->SetFont('Arial', '', 7);
                    $objPdf->SetTextColor(50, 50, 50);

                    $objPdf->Text(128, 150.5, "(Click on the link to open location in Google Maps)");
                
                    $map = getFileContents("https://maps.googleapis.com/maps/api/staticmap?center=".$sLatitude.",".$sLongitude."&zoom=11&size=1000x450&markers=color:red|".$sLatitude.",".$sLongitude."&markers=color:black|".$sVLatitude.",".$sVLongitude."&key=AIzaSyDKes4Df5NgRtYcQ_muoqUadWiI3v6GURg"); 
                }
                else
                    $map = getFileContents("https://maps.googleapis.com/maps/api/staticmap?center=".$sLatitude.",".$sLongitude."&zoom=13&size=1000x450&markers=color:red|".$sLatitude.",".$sLongitude."&key=AIzaSyDKes4Df5NgRtYcQ_muoqUadWiI3v6GURg"); 
                
                $image = imagecreatefromstring($map);
                $saved = imagejpeg($image, $sBaseDir."temp2/googlemapImage.jpg");
                unset($map);
                
                
                $objPdf->Image($sBaseDir.'temp2/googlemapImage.jpg', 65, 155, 120,68);
                unlink($sBaseDir.'temp2/googlemapImage.jpg');
	}
	else if($sVLatitude != "" && $sVLongitude != "")
        {                
                $objPdf->Text(30, 161.8, "N/A");
                
                $objPdf->Text(95, 145.5, ($sAuditMode == 2)?"Audit location/cordinates are un-available as device has not captured cordinates.":"Audit location/cordinates are not available as audit has been conducted via web-portal.");
            
                $objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(6, 82, 195);
                
                $objPdf->SetXY(94, 147);
                $objPdf->Write(5, "(". formatNumber($sVLatitude, true, 8).",". formatNumber($sVLongitude, true, 8).")", "http://maps.google.com/maps?q={$sVLatitude},{$sVLongitude}&z=12");

                $objPdf->SetFont('Arial', '', 7);
                $objPdf->SetTextColor(50, 50, 50);

                $objPdf->Text(128, 150.5, "(Click on the link to open location in Google Maps)");
                    
                $map = getFileContents("https://maps.googleapis.com/maps/api/staticmap?center=".$sVLatitude.",".$sVLongitude."&zoom=13&size=1000x450&markers=color:black|".$sVLatitude.",".$sVLongitude."&key=AIzaSyDKes4Df5NgRtYcQ_muoqUadWiI3v6GURg"); 
                $image = imagecreatefromstring($map);
                $saved = imagejpeg($image, $sBaseDir."temp2/googlemapImage.jpg");
                unset($map);
                
                $objPdf->Image($sBaseDir.'temp2/googlemapImage.jpg', 65, 155, 120,68);
                unlink($sBaseDir.'temp2/googlemapImage.jpg');
        }
	else
		$objPdf->Text(78, 145, getDbValue("city", "tbl_vendors", "id='$iVendor'"));

        $objPdf->SetFont('Arial', '', 7);
        $objPdf->SetTextColor(50, 50, 50);        
        
        $objPdf->Text(38, 232, $sAuditor);
        $objPdf->Text(38, 238, $sAuditDate);

        $sSignature = getDbValue("signature", "tbl_signatures", "FIND_IN_SET('$iVendor',vendors)");
        
        if ($sSignature != "" && @file_exists($sBaseDir.SIGNATURES_IMG_DIR.$sSignature))
        {
            $objPdf->Image($sBaseDir.SIGNATURES_IMG_DIR.$sSignature, 32, 244, 32);
        }
        
        if($sAuditorPciture == "")
            $sAuditorPciture = "default.jpg";
        
        if (@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sAuditorPciture))
        {
            $objPdf->Image($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sAuditorPciture, 77, 232, 32);
        }

        $TotalDefects = count($sDefects);
        $objPdf->Text(165, 232, $iTotalGmts);
        $objPdf->Text(165, 238, $iOrderedQty);
        $objPdf->Text(165, 243.8, $iOrderedQty);
        $objPdf->Text(165, 249.5, $iGmtsDefective);
        $objPdf->Text(165, 255.8, formatNumber(($iGmtsDefective/$iTotalGmts)*100, 2));
        $objPdf->Text(165, 262, formatNumber(($TotalDefects/$iTotalGmts)*100, 2));
                        
        //////////////////////////////////////////////Page 2//////////////////////////////////////////////////////////////////////
        
        $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/levis.pdf");
	$iTemplateId = $objPdf->importPage(2, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 175.5, 11, 22);


	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);
	$objPdf->Text(176.5, 33.5, "Audit Code: {$sAuditCode}");

	// Report Details
	$objPdf->SetFont('Arial', '', 7);
        $sReportTypesList = getList("tbl_defect_codes dc, tbl_defect_types dt", "DISTINCT(dc.type_id)", "dt.type", "dc.type_id=dt.id AND dc.report_id='$iReportId' AND dc.type_id IN (97,7,33,29)", "FIELD(dc.type_id, '97','7','33','29')");
        
        $iOldCategory = "";
        $iTop   = 40;
        $iNext  = 0;
        
        foreach($sReportTypesList as $iCategory => $sCategory)
        {
            if($iOldCategory != $iCategory)
            {
                $iTop += 8;
            }

                $sSQL = "SELECT dc.id, dc.defect, (SELECT COALESCE(SUM(qrd.defects), 0) FROM tbl_qa_report_defects qrd WHERE qrd.code_id = dc.id AND qrd.audit_id='$Id') as _TotalDefects
                    FROM tbl_defect_codes dc
                    WHERE dc.report_id='$iReportId' AND dc.type_id = '$iCategory'
                    ORDER BY dc.id";

                $objDb->query($sSQL);
                $iCount = $objDb->getCount();
                
                for($i=0; $i<$iCount; $i++)
                {
                    
                    if($i%2==0)
                    {
                        $iNext -=90;
                        $iTop += 9.20;
                    }
                    else
                        $iNext +=90;

                    $iTDefects = $objDb->getField($i, "_TotalDefects");
                    $objPdf->Text(177+$iNext, $iTop, $iTDefects);
                }                
        }
        
        $objPdf->SetFont('Arial', '', 9);
        $objPdf->Text(10, 7, "Page #2");
        ////////////////////////////////////////////////////Page 3////////////////////////////////////////////////////////////////
        
        $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/levis.pdf");
	$iTemplateId = $objPdf->importPage(3, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 175.5, 11, 22);


	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);
	$objPdf->Text(176.5, 33.5, "Audit Code: {$sAuditCode}");

	// Report Details
	$objPdf->SetFont('Arial', '', 7);
        
        $sReportTypesList2 = getList("tbl_defect_codes dc, tbl_defect_types dt", "DISTINCT(dc.type_id)", "dt.type", "dc.type_id=dt.id AND dc.report_id='$iReportId' AND dc.type_id IN (118,93)", "FIELD(dc.type_id, '118','93')");

        $iOldCategory = "";
        $iTop   = 35;
        $iNext  = 0;

        foreach($sReportTypesList2 as $iCategory => $sCategory)
        {
            if($iOldCategory != $iCategory)
            {
                $iTop += 7.5;
                
                if($iNext  < 0)
                    $iNext  = 0;
            }

                $sSQL = "SELECT dc.id, dc.defect, (SELECT COALESCE(SUM(qrd.defects), 0) FROM tbl_qa_report_defects qrd WHERE qrd.code_id = dc.id AND qrd.audit_id='$Id') as _TotalDefects
                    FROM tbl_defect_codes dc
                    WHERE dc.report_id='$iReportId' AND dc.type_id = '$iCategory'
                    ORDER BY dc.id";
                
                $objDb->query($sSQL);
                $iCount = $objDb->getCount();
               
                for($i=0; $i<$iCount; $i++)
                {
                    
                    if($i%2==0)
                    {
                        $iNext -=90;
                        $iTop += 9.20;
                    }
                    else
                        $iNext +=90;

                    $iTDefects = $objDb->getField($i, "_TotalDefects");
                    $objPdf->Text(179+$iNext, $iTop, $iTDefects);
                }                
        }
        
        $sSQL = "SELECT * FROM tbl_qa_levis_reports WHERE audit_id = '$Id'";
		$objDb->query($sSQL);

        $iSafety            = $objDb->getField(0, "safety");
        $iCFailure          = $objDb->getField(0, "critical_failure");
        $iSewing            = $objDb->getField(0, "sewing");
        $iAppearance        = $objDb->getField(0, "appearance");
        $iMeasures          = $objDb->getField(0, "measurements");
        $iSundriseMissing   = $objDb->getField(0, "sundries_missing");
        $iSundriseBroken    = $objDb->getField(0, "sundries_broken");
        $iAccuracy          = $objDb->getField(0, "accuracy");
        $iPhysicals         = $objDb->getField(0, "physicals");
        $iOthers            = $objDb->getField(0, "other");
        $iCartonSampled     = $objDb->getField(0, "cartons_sampled");
        $iCartonError       = $objDb->getField(0, "cartons_in_error");
        $iUnitSampled       = $objDb->getField(0, "units_sampled");
        $iUnitError         = $objDb->getField(0, "units_in_errors");
        $iOverAge           = $objDb->getField(0, "overage");
        $iShortage          = $objDb->getField(0, "shortage");
        $iWrongSize         = $objDb->getField(0, "wrong_size");
        $iWrongPc           = $objDb->getField(0, "wrong_pc");
        $iIrregular         = $objDb->getField(0, "irregulars");
        $iWrongSundrise     = $objDb->getField(0, "wrong_sundries");
        
        $objPdf->Text(89, 93, $iSafety);
        $objPdf->Text(89, 103, $iSewing);
        $objPdf->Text(89, 113, $iMeasures);
        $objPdf->Text(89, 122, $iAccuracy);
        $objPdf->Text(89, 131, $iOthers);
        
        $objPdf->Text(179, 93, $iCFailure);
        $objPdf->Text(179, 103, $iAppearance);
        $objPdf->Text(179, 113, $iSundriseBroken);
        $objPdf->Text(179, 122, $iPhysicals);
        
        
        $objPdf->SetFont('Arial', '', 9);
        $objPdf->Text(10, 7, "Page #3");
        ///////////////////////////////////////////Page 4 /////////////////////////////////////////////////////////////////////////
        
        $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/levis.pdf");
	$iTemplateId = $objPdf->importPage(4, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 175.5, 11, 22);


	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);
	$objPdf->Text(176.5, 33.5, "Audit Code: {$sAuditCode}");

	// Report Details
	$objPdf->SetFont('Arial', '', 7);
        
        $objPdf->Text(85, 52, $iCartonSampled);
        $objPdf->Text(85, 62, $iUnitSampled);
        $objPdf->Text(85, 71, $iOverAge);
        $objPdf->Text(85, 80, $iWrongSize);
        $objPdf->Text(85, 90, $iIrregular);
        
        $objPdf->Text(177, 52, $iCartonError);
        $objPdf->Text(177, 62, $iUnitError);
        $objPdf->Text(177, 71, $iShortage);
        $objPdf->Text(177, 80, $iWrongPc);
        $objPdf->Text(177, 90, $iWrongSundrise);
        
        $objPdf->SetXY(13, 178);
        $objPdf->MultiCell(180, 4, $sComments, 0, "L");
                        
        $objPdf->SetFont('Arial', '', 9);
        $objPdf->Text(10, 7, "Page #4");
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 5
	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/levis.pdf");
	$iTemplateId = $objPdf->importPage(5, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 175.5, 11, 22);


	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);
	$objPdf->Text(176.5, 33.5, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(10, 7, "Page #5");

	// Report Details
	$objPdf->SetFont('Arial', '', 11);

	$objPdf->Text(141, 54.8, formatNumber($fAql, true, (($fAql < 1) ? 2 : 1)));
        
        // set alpha to semi-transparency
        $objPdf->SetAlpha(0.3);
        $objPdf->SetFillColor(255, 255, 40);
        
        if($iTotalGmts == 2){
            
            $objPdf->Rect(21, 81, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,85,4,'F');
            else
                $objPdf->Circle(174,85,4,'F');
            
        }
        else if($iTotalGmts == 3){
            
            $objPdf->Rect(21, 88.5, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,92.5,4,'F');
            else
                $objPdf->Circle(174,92.5,4,'F');
        }
        else if($iTotalGmts == 5){
            
            $objPdf->Rect(21, 96, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,100,4,'F');
            else
                $objPdf->Circle(174,100,4,'F');
        }
        else if($iTotalGmts == 8){
            
            $objPdf->Rect(21, 103.5, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,107.5,4,'F');
            else
                $objPdf->Circle(174,107.5,4,'F');
        }
        else if($iTotalGmts == 13){
            
            $objPdf->Rect(21, 111, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,115,4,'F');
            else
                $objPdf->Circle(174,115,4,'F');
        }
        else if($iTotalGmts == 20){
            
            $objPdf->Rect(21, 118.5, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,122.5,4,'F');
            else
                $objPdf->Circle(174,122.5,4,'F');
        }
        else if($iTotalGmts == 32){
            
            $objPdf->Rect(21, 126, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,130,4,'F');
            else
                $objPdf->Circle(174,130,4,'F');
        }
        else if($iTotalGmts == 50){
            
            $objPdf->Rect(21, 133.5, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,137.5,4,'F');
            else
                $objPdf->Circle(174,137.5,4,'F');
        }
        else if($iTotalGmts == 80){
            
            $objPdf->Rect(21, 141, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,145,4,'F');
            else
                $objPdf->Circle(174,145,4,'F');
        }
        else if($iTotalGmts == 125){
            
            $objPdf->Rect(21, 148.5, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,152.5,4,'F');
            else
                $objPdf->Circle(174,152.5,4,'F');
        }
        else if($iTotalGmts == 200){
            
            $objPdf->Rect(21, 156, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,160,4,'F');
            else
                $objPdf->Circle(174,160,4,'F');
        }
        else if($iTotalGmts == 315){
            
            $objPdf->Rect(21, 163.5, 165, 7.5, 'DF');  
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,167.5,4,'F');
            else
                $objPdf->Circle(174,167.5,4,'F');
        }
        else if($iTotalGmts == 500){
            
            $objPdf->Rect(21, 171, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,175,4,'F');
            else
                $objPdf->Circle(174,175,4,'F');
        }
        else if($iTotalGmts == 800){
            
            $objPdf->Rect(21, 178.5, 165, 7.5, 'DF');  
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,182.5,4,'F');
            else
                $objPdf->Circle(174,182.5,4,'F');
        }
        else if($iTotalGmts == 1250){
            
            $objPdf->Rect(21, 186, 165, 7.6, 'DF');  
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,190,4,'F');
            else
                $objPdf->Circle(174,190,4,'F');
        }

        // restore full opacity
        $objPdf->SetAlpha(1);
        $objPdf->SetTextColor(50, 50, 50);
        $objPdf->SetFillColor(255,255,255);
        
         ///////////////////////////////////////////////////page 6 (specs summary) /////////////////////////        
            $iCurrentPage = 6;
            $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/levis.pdf");
            $iTemplateId = $objPdf->importPage(7, '/MediaBox');

            $objPdf->addPage("P", "A4");
            $objPdf->useTemplate($iTemplateId, 0, 0);


            // QR Code
            QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

            $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 175.5, 11, 22);

            $objPdf->SetFont('Arial', '', 6);
            $objPdf->SetTextColor(50, 50, 50);
            $objPdf->Text(176.5, 33.5, "Audit Code: {$sAuditCode}");

            $objPdf->SetFont('Arial', '', 9);
            $objPdf->Text(10, 7, "Page #{$iCurrentPage}");

            // Report Details
            $objPdf->SetFont('Arial', 'B', 6);

            $sSizes  = getDbValue("GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',')", "tbl_qa_report_samples", "audit_id='$Id'");
            $iSizes  = @explode(",", $sSizes);

            $objPdf->Text(45, 58.5, getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id IN ($sSizes)"));
            $objPdf->Text(45, 63.5, getDbValue("GROUP_CONCAT(size SEPARATOR ',')", "tbl_sampling_sizes", "id IN ($sSizes)"));

            $sSizeFindings  = array( );
            $sSizeFindings2 = array( );
            $iPoints        = array( );

            $sSQL = "SELECT qrs.size_id ,qrs.audit_id, qrs.sample_no, qrss.point_id, qrss.findings, specs
                            FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
                            WHERE qrs.id=qrss.sample_id AND qrs.audit_id='$Id'
                            ORDER BY qrss.point_id, qrs.sample_no";

            $objDb->query($sSQL);

            $iCount = $objDb->getCount( );

            for($i = 0; $i < $iCount; $i ++)
            {
                    $iSize      = $objDb->getField($i, 'size_id');
                    $iPoint     = $objDb->getField($i, 'point_id');
                    $iSampleNo  = $objDb->getField($i, 'sample_no');
                    $sFindings  = $objDb->getField($i, 'findings');
                    $sSpecs     = $objDb->getField($i, 'specs');

                    $iSamplesCount   = (int)getDbValue("COUNT(1)", "tbl_style_specs", " point_id='$iPoint' AND style_id='$iStyle' AND size_id='$iSize' AND version='0' AND size_id IN ($sSizes)");
                    $sPointId        =  getDbValue("mp.point_id", "tbl_style_specs ss, tbl_measurement_points mp", "mp.id=ss.point_id AND ss.point_id='$iPoint' AND ss.style_id='$iStyle' AND ss.size_id='$iSize'");
                    
                    if(@in_array($sPointId, array("INS1","INSEC")))
                        $sSpecValue = $sSpecs;
                    else
                        $sSpecValue      =  getDbValue("specs", "tbl_style_specs", " point_id='$iPoint' AND style_id='$iStyle' AND size_id='$iSize'");
                    
                    $iSamplesChecked = (int)getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize'");
                    $iSampleSize     = ($iSamplesCount * $iSamplesChecked);

                    $sSizeFindings["{$i}-{$iSampleNo}-{$iPoint}"] = array('finiding' => $sFindings, 'sample_size' => $iSampleSize, 'size_id' => $iSize, 'spec_value'=>$sSpecValue);
                    $sSizeFindings2["{$iSize}-{$iSampleNo}-{$iPoint}"] = $sFindings;

                    $iPoints[] = $iPoint;
            }

            $sToleranceList = getList("tbl_measurement_points", "id", "COALESCE(tolerance, '0')", "brand_id='$iBrand'", "id");
            $sPointList     = getList("tbl_measurement_points", "id", "point", "brand_id='$iBrand'", "id");

            $iLastPoint = 0;
            $sSpecArray = array();

            foreach ($sSizeFindings as $sSampleNPoint => $sFindings)
            {
                    $sSamplePoint  = @explode("-", $sSampleNPoint);
                    $iPoint        = @$sSamplePoint[2];

                    $sFinding      = $sFindings['finiding'];
                    $iSampleSize   = $sFindings['sample_size'];
                    $sSpecs        = $sFindings['spec_value'];
                    
                    $sPoint        = @$sPointList[$iPoint];
                    $sTolerance    = @$sToleranceList[$iPoint];

                    if (trim($sFinding) == "" && strtolower($sFinding) == "ok" && $sFinding == "0" && $sFinding == "-")
                    {
                            continue;
                    }

                    $fMeaseuredValue = ($sFinding);
                    $fSpecValue      = ($sSpecs);
                    $fTolerance      = parseTolerance($sTolerance);
                    
                    $PositiveTolerance = ($fSpecValue + $fTolerance[1] + 0.25);
                    $NegativeTolerance = ($fSpecValue - $fTolerance[0] - 0.25);

                    if ($iPoint != $iLastPoint)
                    {
                            $TotalPercent  = 0;
                            $MajorDefects  = 0;
                            $MinorDefects  = 0;
                            $iTotalSum     = 0;
                    }

                    if ($fMeaseuredValue >= $NegativeTolerance && $fMeaseuredValue <= $PositiveTolerance)
                    {
                            continue;
                    }
                    else
                    {
                            $fPercent = (abs($fMeaseuredValue)/$fSpecValue)*100;

                            if ($fPercent > 10 || $fSpecValue == 0)
                                    $MajorDefects++;

                            else if($fPercent > 0 && $fPercent < 10)
                                    $MinorDefects++;

                            $TotalPercent = (($MajorDefects+$MinorDefects)/$iSampleSize)*100;
                            
                            $sSpecArray[$iPoint] = array('point'=> $iPoint, 'major'=>$MajorDefects, 'minor'=>$MinorDefects, 'percent' => $TotalPercent, 'sample_size' => $iSampleSize);
                            $iLastPoint = $iPoint;
                    }
            }


            $sort = array();

            foreach($sSpecArray as $k=>$v)
            {
                $sort['major'][$k] = $v['major'];
                $sort['minor'][$k] = $v['minor'];
            }

            array_multisort($sort['major'], SORT_DESC, $sort['minor'], SORT_DESC,$sSpecArray);

            $iTop               = 185.5;
            $iTotalMajor        = 0;
            $iTotalMinor        = 0;
            $iTotalPercent      = 0;
            $limit              = 0;
            $iTotalSampleSizes  = 0;

            foreach($sSpecArray as $iKey => $sDefectsArr)
            {
                if($limit < 23 && $sDefectsArr['percent']>0)
                {                
                    $iPoint = $sDefectsArr['point'];

                    $iTotalMajor       += $sDefectsArr['major'];
                    $iTotalMinor       += $sDefectsArr['minor'];
                    $iTotalPercent     += $sDefectsArr['percent'];
                    $iTotalSampleSizes += $sDefectsArr['sample_size'];

                    $objPdf->SetXY(11, ($iTop - 2));
                    $objPdf->MultiCell(120, 1.3, @$sPointList[$iPoint], 0);

                    $objPdf->Text(162, $iTop, $sDefectsArr['minor']);
                    $objPdf->Text(177, $iTop, $sDefectsArr['major']);
                    $objPdf->Text(189, $iTop, number_format($sDefectsArr['percent'],2));

                    $iTop += 3.35;
                }   

                $limit++;
            }

            $objPdf->Text(162, 263, formatNumber($iTotalMinor, false));
            $objPdf->Text(177, 263, formatNumber($iTotalMajor, false));

            //sizez box
            $objPdf->SetFont('Arial', 'B', 7);
            $objPdf->SetTextColor(50, 50, 50);
            
            $sQtyPerSize            = "";
            $iSizeTop               = 78;
            $iTotalEvaluatedPoints  = 0;
            $iTotalDefectivePoints  = 0;

            foreach ($iSizes as $iSize)
            {
                    if ($sQtyPerSize != "")
                            $sQtyPerSize .= ", ";

                    $sSize = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");
                    $TotalInspections = getDbValue('COUNT(DISTINCT sample_no)', 'tbl_qa_report_samples', "audit_id='$Id' AND size_id='$iSize'");
                    $iTotalMeaseurementPoints = getDbValue("COUNT(point_id)", "tbl_style_specs", "style_id='$iStyle' AND size_id='$iSize' AND version='0'");
                    $iSamplesChecked = getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize'");

                    if ($iSamplesChecked > 5)
                            $iSamplesChecked = 5;

                    $sSQL = "SELECT point_id, specs,
                            (SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
                            (SELECT point_id FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _PointId
                    FROM tbl_style_specs
                    WHERE style_id='$iStyle' AND size_id='$iSize' AND version='0'
                    ORDER BY id";

                    $objDb->query($sSQL);
                    $iCount2        = $objDb->getCount( );
                    $count          = 0;

                    for($i=0; $i < $iCount2; $i++)
                    {
                        for ($j = 1; $j <= $iSamplesChecked; $j ++, $k ++)
                        {
                            $iPoint     = $objDb->getField($i, 'point_id');
                            $sPointId   = $objDb->getField($i, '_PointId');                            
                            $sTolerance = $objDb->getField($i, '_Tolerance');
                            $sFinding  = $sSizeFindings2["{$iSize}-{$j}-{$iPoint}"];
                            
                            if(@in_array($sPointId, array("INS1","INSEC")))
                            {
                                $sSpecs  = getDbValue("qrss.specs", "tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss", "qrs.audit_id='$Id' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSize' AND qrss.point_id='$iPoint'");
                            }
                            else
                                $sSpecs     = $objDb->getField($i, 'specs');

                            if ($sFinding != "" && strtolower($sFinding) != "ok" && strtolower($sFinding) != "0" && strtolower($sFinding) != "-")
                            {
                                $fMeaseuredValue  = $sFinding;
                                $fSpecValue       = ($sSpecs);
                                $fTolerance       = parseTolerance($sTolerance);
                    
                                $PositiveTolerance = $fSpecValue + $fTolerance[1] + 0.25;
                                $NegativeTolerance = $fSpecValue - $fTolerance[0] - 0.25;

                                if($fMeaseuredValue >= $NegativeTolerance && $fMeaseuredValue <= $PositiveTolerance)
                                {
                                    continue;
                                }
                                else
                                {
                                    $count++;
                                   // echo "S".$fSpecValue."M:".$fMeaseuredValue."NT:".$NegativeTolerance."PT:".$PositiveTolerance."<br/>";
                                }
                            }
                        }
                    }

                    $objPdf->Text(20, $iSizeTop, $sSize);
                    $objPdf->Text(55, $iSizeTop, $iTotalMeaseurementPoints.' x '.$TotalInspections);
                    $objPdf->Text(140, $iSizeTop, $count);
                    $iTotalDefectivePoints += $count;
                    $iTempMeasure = $iTotalMeaseurementPoints * $TotalInspections;
                    $iTotalEvaluatedPoints += $iTempMeasure;

                    $iSizeTop +=4.8;
            }

            $objPdf->Text(97, 58.5, $iTotalEvaluatedPoints);
            $objPdf->Text(170, 58.5, $iTotalDefectivePoints);
            $iGenealPercent = ($iTotalDefectivePoints/$iTotalEvaluatedPoints)*100;
            $objPdf->Text(189, 263, number_format(($iGenealPercent),2).'%');
            $objPdf->SetFont('Arial', 'B', 7);

            if($iGenealPercent > 20)
            {
               $objPdf->SetTextColor(255, 0, 0);
               $objPdf->Text(190, 58.5, 'Fail');
            }
            else
            {
               $objPdf->SetTextColor(0, 100, 0);
               $objPdf->Text(190, 58.5, 'Pass');
            }
            $iCurrentPage ++;
     	 ///////////////////////////////////////////////////page 7 (specs sheet page) /////////////////////////	 	
	$iPageCount   = $objPdf->setSourceFile($sBaseDir."templates/levis.pdf");
	$iTemplateId  = $objPdf->importPage(6, '/MediaBox');

        $sSizesList   = getList("tbl_sizes", "id", "size", "FIND_IN_SET(id, '$sSizes')", "size");
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
		foreach ($iSizes as $iSize)
		{
                            $sSQL = "SELECT qrs.sample_no, qrss.point_id, qrss.findings, qrs.nature, qrss.specs
                                             FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
                                             WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSize' AND qrs.color='$sColor'
                                             ORDER BY qrs.sample_no, qrss.point_id";

                            $objDb->query($sSQL);

                            $iCount         = $objDb->getCount( );
                            $sSizeFindings  = array( );
                            $sNatureFindings= array( );
                            $sSizeSpecs     = array( );
							
                            if ($iCount == 0)
                                continue;

							
                            for($i = 0; $i < $iCount; $i ++)
                            {
                                    $iSampleNo = $objDb->getField($i, 'sample_no');
                                    $iPoint    = $objDb->getField($i, 'point_id');
                                    $sFindings = $objDb->getField($i, 'findings');
                                    $sNature   = $objDb->getField($i, 'nature');
                                    $sSizeSpec = $objDb->getField($i, 'specs');

                                    $sSizeFindings["{$iSampleNo}-{$iPoint}"] = (($sFindings == '' || $sFindings == '0' || strtolower($sFindings) == 'ok')?'-':$sFindings);
                                    $sNatureFindings["{$iSampleNo}"] = (($sNature == 'C')?'(CBM)':"(FBM)");
                                    $sSizeSpecs["{$iPoint}"] = $sSizeSpec;
                            }


                            $objPdf->addPage("L", "A4");
                            $objPdf->useTemplate($iTemplateId, 0, 0);

                            // QR Code
                            QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

                            $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 268, 11, 22);


                            $objPdf->SetFont('Arial', '', 6);
                            $objPdf->SetTextColor(50, 50, 50);
                            $objPdf->Text(269, 33, "Audit Code: {$sAuditCode}");


                            $objPdf->SetFont('Arial', '', 9);
                            $objPdf->Text(10, 7, "Page #{$iCurrentPage}");

                            $sSize = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");

                            $objPdf->SetFont('Arial', '', 7);
                            $objPdf->SetXY(30, 43.2);
                            $objPdf->MultiCell(162, 4, $sSize, 0, "L");

                            $sSQL = "SELECT point_id, specs, nature,
                                                            (SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
                                                            (SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point,
                                                            (SELECT point_id FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _PointId
                                             FROM tbl_style_specs
                                             WHERE style_id='$iStyle' AND size_id='$iSize' AND version='0'
                                             ORDER BY FIELD(nature, 'C') DESC";
                            
                            $objDb->query($sSQL);
                            $iCount          = $objDb->getCount( );
                            
                            if ($iCount == 0 && $sSizesList[$iSize] == "XXL")
                            {
                                    $iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '2XL'");

                                    if ($iSamplingSize > 0)
                                    {
                                            $sSQL = "SELECT point_id, specs, nature,
                                                            (SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
                                                            (SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point,
                                                            (SELECT point_id FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _PointId
                                             FROM tbl_style_specs
                                             WHERE style_id='$iStyle' AND size_id='$iSamplingSize' AND version='0'
                                             ORDER BY FIELD(nature, 'C') DESC";
                                            $objDb->query($sSQL);

                                            $iCount = $objDb->getCount( );
                                    }
                            }

                            if ($iCount == 0 && strpos($sSizesList[$iSize], " ") !== FALSE)
                            {
                                    $sSize         = str_replace(" ", "", $sSizesList[$iSize]);
                                    $iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");

                                    if ($iSamplingSize == 0 && substr($sSizesList[$iSize], -2) == " S")
                                    {
                                            $sSize         = str_replace(" S", "W", $sSizesList[$iSize]);
                                            $iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");
                                    }

                                    if ($iSamplingSize > 0)
                                    {
                                            $sSQL = "SELECT point_id, specs, nature,
                                                            (SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
                                                            (SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point,
                                                            (SELECT point_id FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _PointId
                                             FROM tbl_style_specs
                                             WHERE style_id='$iStyle' AND size_id='$iSamplingSize' AND version='0'
                                             ORDER BY FIELD(nature, 'C') DESC";
                                            $objDb->query($sSQL);

                                            $iCount = $objDb->getCount( );
                                    }
                            }
                        
                            $iOut            = 0;
                            $iSamplesChecked = getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize' AND color LIKE '$sColor'");

                            if ($iSamplesChecked > 6)
                                    $iSamplesChecked = 6;

                            $sFlags = [0,0,0,0,0,0];
                            
                            for($i = 0, $LineNo = 0; $i < $iCount; $i ++)
                            {
                                    $iPoint     = $objDb->getField($i, 'point_id');                                    
                                    $sPoint     = $objDb->getField($i, '_Point');
                                    $sSpecs     = $objDb->getField($i, 'specs');
                                    $iPointId   = $objDb->getField($i, '_PointId');
                                    $sPNature   = $objDb->getField($i, 'nature');
                                    $sTolerance = $objDb->getField($i, '_Tolerance');
                                    
                                    
                                    if(@in_array($iPointId, array("INS1","INSEC")))
                                    {
                                        $sSpecs = (@$sSizeSpecs[$iPoint] != ""?$sSizeSpecs[$iPoint]:$sSpecs);
                                    }

                                    if($i>30)
                                        continue;
                                    
                                    $objPdf->SetFont('Arial', '', 6);
                                    $objPdf->Text(13, (66 + ($LineNo * 3.520)), ($LineNo + 1));
                                    
                                    if($sPNature == 'C')
                                        $objPdf->SetTextColor(255, 0, 0);
                                    
                                    $objPdf->Text(20.5, (66 + ($LineNo * 3.520)), $iPointId);
                                            
                                    if(strlen($sPoint) > 48)
                                    {
                                        $objPdf->SetFont('Arial', '', 5);
                                        $objPdf->SetXY(30.5, (63.8 + ($LineNo * 3.545)));
                                        $objPdf->MultiCell(80, 1.6, $sPoint, 0, "L");
                                        $objPdf->SetFont('Arial', '', 6);
                                    }
                                    else
                                    {
                                        $objPdf->SetXY(30.5, (64.2 + ($LineNo * 3.520)));
                                        $objPdf->MultiCell(85, 2.2, $sPoint, 0, "L");
                                    }
                                    
                                    $objPdf->SetTextColor(50, 50, 50);
                                    $objPdf->Text(115.5, (66 + ($LineNo * 3.560)), $sSpecs);
                                    $objPdf->Text(275, (66 + ($LineNo * 3.560)), $sTolerance);


                                    for ($j = 1; $j <= $iSamplesChecked; $j ++)
                                    {                 
                                            if($sFlags[$j] == 0)
                                            {
                                                $objPdf->Text((110 + ($j * 24.65)), (53 + ($LineNo * 3.560)), $sNatureFindings["$j"]);                                                
                                                $sFlags[$j] = 1;
                                            }
                                            
                                            $sSpecs = floatval($sSpecs);
                                            
                                            if ($sSizeFindings["{$j}-{$iPoint}"] != "" && strtolower($sSizeFindings["{$j}-{$iPoint}"]) != "ok" && $sSizeFindings["{$j}-{$iPoint}"] != "0" && $sSizeFindings["{$j}-{$iPoint}"] != "-") //&& floatval($sSizeFindings["{$j}-{$iPoint}"]) != $sSpecs
                                            {
                                                    $fMeaseuredValue  = floatval($sSizeFindings["{$j}-{$iPoint}"]);
                                                    $fDifference      = ($fMeaseuredValue - $sSpecs);
                                                    
//                                                    print $sSpecs. " - " . $fMeaseuredValue ." - ".$fDifference."<br>";
                                                    
                                                    $fTolerance       = parseTolerance($sTolerance);
                                                    $fNTolerance       = $fTolerance[0];
                                                    $fPTolerance       = $fTolerance[1];
                                                    
                                                    $fPositiveTolerance = ($sSpecs + $fPTolerance);
                                                    $fNegativeTolerance = ($sSpecs - $fNTolerance);
                                                    
                                                    $fBufferPositiveTolerance = ($fPositiveTolerance + 0.25);
                                                    $fBufferNegativeTolerance = ($fNegativeTolerance - 0.25);

                                                    if ($fMeaseuredValue >= $fNegativeTolerance && $fMeaseuredValue <= $fPositiveTolerance)//green block
                                                    {
                                                            $objPdf->SetTextColor(50, 50, 50);
                                                            $objPdf->SetXY((102.2 + ($j * 24.65)), (63.8 + ($LineNo * 3.560)));
                                                            $objPdf->Cell(9.1, 2.5, $sSizeFindings["{$j}-{$iPoint}"], 0, 0, "C", false);

                                                            $objPdf->SetTextColor(0, 100, 0);
                                                            $objPdf->SetXY((114.5 + ($j * 24.65)), (63.8 + ($LineNo * 3.560)));
                                                            $objPdf->Cell(9.1, 2.5, formatNumber($fDifference, true, 3), 0, 0, "C", false);
                                                            $objPdf->SetTextColor(50, 50, 50);  
                                                    }
                                                    else if ($fMeaseuredValue >= $fBufferNegativeTolerance && $fMeaseuredValue <= $fBufferPositiveTolerance)//orange block
                                                    {                                                            
                                                            
                                                            $objPdf->SetTextColor(50,50,50);
                                                            $objPdf->SetXY((102.2 + ($j * 24.65)), (63.8 + ($LineNo * 3.560)));
                                                            $objPdf->Cell(9.1, 2.5, $sSizeFindings["{$j}-{$iPoint}"], 0, 0, "C", false);

                                                            $objPdf->SetTextColor(255,140,0);
                                                            $objPdf->SetXY((114.5 + ($j * 24.65)), (63.8 + ($LineNo * 3.560)));
                                                            $objPdf->Cell(9.1, 2.5, formatNumber($fDifference, true, 3), 0, 0, "C", false);
                                                            $objPdf->SetTextColor(50,50,50);
                                                    }
                                                    else
                                                    {							//red block
                                                            $objPdf->SetTextColor(50, 50, 50);
                                                            $objPdf->SetXY((102.2 + ($j * 24.65)), (63.8 + ($LineNo * 3.560)));
                                                            $objPdf->Cell(9.1, 2.5, $sSizeFindings["{$j}-{$iPoint}"], 0, 0, "C", false);

                                                            $objPdf->SetTextColor(255, 0, 0);
                                                            $objPdf->SetXY((114.5 + ($j * 24.65)), (63.8 + ($LineNo * 3.560)));
                                                            $objPdf->Cell(9.1, 2.5, formatNumber($fDifference, true, 3), 0, 0, "C", false);
                                                            $objPdf->SetTextColor(50,50,50);
                                                            
                                                            $iOut ++;
                                                    }
                                            }
                                            else
                                            {       
                                                    //green block
                                                    $objPdf->SetTextColor(50, 50, 50);
                                                    $objPdf->SetXY((102.2 + ($j * 24.65)), (63.8 + ($LineNo * 3.560)));
                                                    $objPdf->Cell(9.1, 2.5, "N/A", 0, 0, "C", false);
                                                    
                                                    $objPdf->SetXY((114.5 + ($j * 24.65)), (63.8 + ($LineNo * 3.560)));
                                                    $objPdf->Cell(9.1, 2.5, "N/A", 0, 0, "C", false);
                                            }
                                    }
                                    
                                    $LineNo++;
                            }
                            

                            $objPdf->SetFont('Arial', '', 9);
                            $objPdf->SetTextColor(50, 50, 50);
                            $objPdf->Text(89, 177, ($iCount* 1));//$iSamplesChecked
                            $objPdf->Text(179, 177, $iOut);

                            if ($sMeasurementResult == "P")
                                    $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 28, 240, 4);

                            else if ($sMeasurementResult == "F")
                                    $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 77.5, 240, 4);

                            else if ($sMeasurementResult == "H")
                                    $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 131, 240, 4);

                            $iCurrentPage ++;
                    
		}
	}
        ///////////////////////////////////////////Page 8 (Csc Defects Summary)/////////////////////////////////////////////////////////////////////////
        if ($iAuditTypeId == 2 && (@in_array($iAuditStage, array('F','V','TG'))))
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/levis.pdf");
		$iTemplateId = $objPdf->importPage(8, '/MediaBox');

		
                $objPdf->addPage("P", "A4");
                $objPdf->useTemplate($iTemplateId, 0, 0);

                $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 175.5, 11, 22);
                $objPdf->SetFont('Arial', '', 6);
                $objPdf->SetTextColor(50, 50, 50);
                $objPdf->Text(176.5, 33.5, "Audit Code: {$sAuditCode}");

                $objPdf->SetFont('Arial', '', 9);
                $objPdf->Text(10, 7, "Page #{$iCurrentPage}");

                $objPdf->SetFont('Arial', '', 7);

                if($iTotalGmts == 5)
                {
                    $objPdf->Text(14, 54, "1");
                    $objPdf->Text(25, 54, "CSC + Final (Single Sampling Plan)");
                    $objPdf->Text(85, 54, "5");
                    
                    $iDefectCount = getDbValue("COALESCE(SUM(defects), 0)", "tbl_qa_report_defects qrd", "audit_id='$Id' AND sample_no <= '5'");
                    
                    $objPdf->Text(111.25, 54, $iDefectCount);
                    $objPdf->Text(151.1, 54, ($iDefectCount > 0?"Failed on Single Sampling Plan":"Passed on Single Sampling Plan"));
                }
                else if($iTotalGmts == 13)
                {
                    $objPdf->Text(14, 54, "1");
                    $objPdf->Text(25, 54, "CSC + Final (Single Sampling Plan)");
                    $objPdf->Text(85, 54, "5");
                    
                    $iDefectCount1  = (int)getDbValue("COALESCE(SUM(defects), 0)", "tbl_qa_report_defects", "audit_id='$Id' AND sample_no <= '5'");
                    
                    $objPdf->Text(111.25, 54, $iDefectCount1);
                    $objPdf->Text(151.1, 54, ($iDefectCount1 > 0?"Failed on Single Sampling Plan":"Passed on Single Sampling Plan"));
                    
                    if($iDefectNature1 != 2)
                    {
                        // next sample
                        $objPdf->Text(14, 60.5, "2");
                        $objPdf->Text(25, 60.5, "CSC + Final (Double Sampling Plan)");
                        $objPdf->Text(85, 60.5, "13");

                        $iDefectCount2  = (int)getDbValue("COALESCE(SUM(defects), 0)", "tbl_qa_report_defects qrd", "audit_id='$Id' AND (sample_no > '5' AND sample_no <= '13')");
                       
                        $objPdf->Text(111.25, 60.5, $iDefectCount1 + $iDefectCount2);
                        $objPdf->Text(151.1, 60.5, ($iDefectCount2 > 0?"Failed on Double Sampling Plan":"Passed on Double Sampling Plan"));
                    }
                }
                else if(($iTotalGmts == 20 || $iTotalGmts == 32) && $iAuditStage == 'TG')
                {
                    $objPdf->Text(14, 54, "1");
                    $objPdf->Text(25, 54, "CSC + Targeted");
                    $objPdf->Text(85, 54, "20");
                    
                    $iDefectCount1  = getDbValue("COALESCE(SUM(defects), 0)", "tbl_qa_report_defects qrd", "audit_id='$Id' AND sample_no <= '20'");
                    
                    $objPdf->Text(111.25, 54, $iDefectCount1);
                    $objPdf->Text(151.1, 54, ($iDefectCount1 > 1?"Failed on Single Sampling Plan":"Passed on Single Sampling Plan"));
                    
                    if($iTotalGmts == 32)
                    {
                        // next sample
                        $objPdf->Text(14, 60.5, "2");
                        $objPdf->Text(25, 60.5, "CSC + Validation");
                        $objPdf->Text(85, 60.5, "32");

                        $iDefectCount2 = getDbValue("COALESCE(SUM(defects), 0)", "tbl_qa_report_defects qrd", "audit_id='$Id' AND (sample_no > '20' AND sample_no <= '32')");
                        
                        $objPdf->Text(111.25, 60.5, $iDefectCount1+$iDefectCount2);
                        $objPdf->Text(151.1, 60.5, (($iDefectCount1 + $iDefectCount2) > 2?"Failed on Validation":"Passed on Validation"));
                    }
                }
                else if($iTotalGmts == 32 && $iAuditStage != 'TG')
                {
                    $objPdf->Text(14, 54, "1");
                    $objPdf->Text(25, 54, "CSC + Final (Single Sampling Plan)");
                    $objPdf->Text(85, 54, "5");
                    
                    $iDefectCount1 = getDbValue("COALESCE(SUM(defects), 0)", "tbl_qa_report_defects qrd", "audit_id='$Id' AND sample_no <= '5'");
                    
                    $objPdf->Text(111.25, 54, $iDefectCount1);
                    $objPdf->Text(151.1, 54, ($iDefectCount1 > 0?"Failed on Single Sampling Plan":"Passed on Single Sampling Plan"));
                    
                    if($iDefectNature1 != 2)
                    {
                        // 2nd next sample
                        $objPdf->Text(14, 60.5, "2");
                        $objPdf->Text(25, 60.5, "CSC + Final (Double Sampling Plan)");
                        $objPdf->Text(85, 60.5, "13");

                        $iDefectCount2 = getDbValue("COALESCE(SUM(defects), 0)", "tbl_qa_report_defects qrd", "audit_id='$Id' AND (sample_no > '5' AND sample_no <= '13')");
                        
                        $objPdf->Text(111.25, 60.5, $iDefectCount1+$iDefectCount2);
                        $objPdf->Text(151.1, 60.5, ($iDefectCount2 > 0?"Failed on Double Sampling Plan":"Passed on Double Sampling Plan"));

                        if($iDefectCount2 > 0)
                        {
                            // 3rd next sample
                            $objPdf->Text(14, 67, "3");
                            $objPdf->Text(25, 67, "CSC + Validation");
                            $objPdf->Text(85, 67, "32");

                            $iDefectCount3 = getDbValue("COALESCE(SUM(defects), 0)", "tbl_qa_report_defects qrd", "audit_id='$Id' AND (sample_no > '13' AND sample_no <= '32')");
                            
                            $objPdf->Text(111.25, 67, $iDefectCount1+$iDefectCount2+$iDefectCount3);
                            $objPdf->Text(151.1, 67, ($iDefectCount3 > 0?"Failed on Validation":"Passed on Validation"));                    
                        }
                    }
                }
			
		$iCurrentPage ++;
	}
        
        ///////////////////////////////////////////Page 9 (Defect Images)/////////////////////////////////////////////////////////////////////////
        if (count($sDefects) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/levis.pdf");
		$iTemplateId = $objPdf->importPage(9, '/MediaBox');

		$iPages = @ceil(count($sDefects) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);

			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 175.5, 11, 22);
                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);
                        $objPdf->Text(176.5, 33.5, "Audit Code: {$sAuditCode}");

			$objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(12, 44.5, "Defect Images");

                        $objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(10, 7, "Page #{$iCurrentPage}");
                        
			$objPdf->SetFont('Arial', '', 7);

			for ($j = 0; $j < 4 && $iIndex < count($sDefects); $j ++, $iIndex ++)
			{
                                $FilePath   = $sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sDefects[$iIndex]);                                
				$sName      = @strtoupper($sDefects[$iIndex]);
                                $exts       = explode('.', $sName);
                                $extension  = end($exts);                   
				$sName      = @basename($sName, ".JPG");
				$sParts     = @explode("_", $sName);

				$sDefectCode = $sParts[1];
				$sAreaCode   = $sParts[2];


				$sSQL = "SELECT defect,
								(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
						 FROM tbl_defect_codes dc
						 WHERE code='$sDefectCode' AND report_id='$iReportId'";
				$objDb->query($sSQL);

				$sDefect = $objDb->getField(0, "defect");
				$sType   = $objDb->getField(0, "_Type");


				$iLeft = 15;
				$iTop  = 53;

				if ($j == 1 || $j == 3)
					$iLeft = 111;

				if ($j == 2 || $j == 3)
					$iTop = 153;


				$sInfo  = "Type: {$sType}\n";
				$sInfo .= "Defect: {$sDefect}\n";
				$sInfo .= ("Area: ".getDbValue("area", "tbl_defect_areas", "id='$sAreaCode'")."\n");

				$objPdf->SetXY($iLeft, ($iTop + 77.5));
				$objPdf->MultiCell(98, 3.6, $sInfo, 0, "L", false);

                                if(filesize($FilePath) > 0)
                                {
                                    if($extension == 'PNG')
                                    {
                                        $FilePath = convertPngToJpg($FilePath);
                                        $objPdf->Image($FilePath, $iLeft, $iTop, 87, 70);
                                        unlink($FilePath);
                                    }
                                    else
                                        $objPdf->Image($FilePath, $iLeft, $iTop, 87, 70);
                                }
			}
		}
	}
        
        //////////////////////////////////////////////Page 10 (Misc Images)//////////////////////////////////////////////////////////////////////
        
        if (count($sMisc) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/levis.pdf");
		$iTemplateId = $objPdf->importPage(10, '/MediaBox');


		$iPages = @ceil(count($sMisc) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 175.5, 11, 22);
                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);
                        $objPdf->Text(176.5, 33.5, "Audit Code: {$sAuditCode}");

                        $objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(10, 7, "Page #{$iCurrentPage}");
                        
			$objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(12, 44.5, "Miscellaneous Images");


			for ($j = 0; $j < 4 && $iIndex < count($sMisc); $j ++, $iIndex ++)
			{
                            if(filesize($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sMisc[$iIndex])) > 0)
                            {
				$iLeft = 15;
				$iTop  = 55;

				if ($j == 1 || $j == 3)
					$iLeft = 111;

				if ($j == 2 || $j == 3)
					$iTop = 155;

                                $FilePath   = $sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sMisc[$iIndex]);                                
                                $exts       = explode('.', strtoupper($sMisc[$iIndex]));
                                $extension  = end($exts);                  
                                
                                if($extension == 'PNG')
                                {
                                    $FilePath = convertPngToJpg($FilePath);
                                    $objPdf->Image($FilePath, $iLeft, $iTop, 87, 85);
                                    unlink($FilePath);
                                }
                                else
                                    $objPdf->Image($FilePath, $iLeft, $iTop, 87, 85);
                            }
			}
		}
	}
        
        //////////////////////////////////////////////////////Page 11 (packing Images)//////////////////////////////////////////////////////////////
        if (count($sPacking) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/levis.pdf");
		$iTemplateId = $objPdf->importPage(10, '/MediaBox');


		$iPages = @ceil(count($sPacking) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 175.5, 11, 22);
                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);
                        $objPdf->Text(176.5, 33.5, "Audit Code: {$sAuditCode}");

                        $objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(10, 7, "Page #{$iCurrentPage}");
                        
			$objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(12, 44.5, "Packing Images");


			for ($j = 0; $j < 4 && $iIndex < count($sPacking); $j ++, $iIndex ++)
			{
                            if(filesize($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPacking[$iIndex])) > 0)
                            {
				$iLeft = 15;
				$iTop  = 55;

				if ($j == 1 || $j == 3)
					$iLeft = 111;

				if ($j == 2 || $j == 3)
					$iTop = 155;

                                $FilePath   = $sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPacking[$iIndex]);                                
                                $exts       = explode('.', strtoupper($sPacking[$iIndex]));
                                $extension  = end($exts);                  
                                
                                if($extension == 'PNG')
                                {                                    
                                    $FilePath = convertPngToJpg($FilePath);                                    
                                    if (@file_exists($FilePath))
                                    {
                                        $objPdf->Image($FilePath, $iLeft, $iTop, 87, 85);
                                        unlink($FilePath);
                                    }
                                }
                                else
                                    $objPdf->Image($FilePath, $iLeft, $iTop, 87, 85);
                            }
			}
		}
	}
        /////////////////////////////////////////////////////////Page 12(Specs Sheet)///////////////////////////////////////////////////////////
        if (count($sSpecsSheets) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/levis.pdf");
		$iTemplateId = $objPdf->importPage(11, '/MediaBox');


		for ($i = 0; $i < count($sSpecsSheets); $i ++, $iCurrentPage ++)
		{
                    if(filesize($sSpecsSheets[$i]) > 0)
                    {
                        $FilePath   = $sSpecsSheets[$i];                                
                        $exts       = explode('.', strtoupper($sSpecsSheets[$i]));
                        $extension  = end($exts); 
                        
                        if($extension == 'PNG')
                        {
                            $FilePath = convertPngToJpg($FilePath);                            
                            if (!file_exists($FilePath))
                            {
                                $iCurrentPage--;
                                continue;
                            }
                        }
                            
                        $objPdf->addPage("P", "A4");
                        $objPdf->useTemplate($iTemplateId, 0, 0);

                        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 175.5, 11, 22);

                        $objPdf->SetFont('Arial', '', 9);
                        $objPdf->Text(10, 7, "Page #{$iCurrentPage}");

                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(176.5, 33.5, "Audit Code: {$sAuditCode}");


                        $objPdf->SetFont('Arial', '', 11);
                        $objPdf->Text(12, 44.5, "Lab Reports / Specs Sheets");

                        if($extension == 'PNG')
                        {
                            $objPdf->Image($FilePath, $iLeft, $iTop, 87, 85);
                            unlink($FilePath);
                        }
                        else
                            $objPdf->Image($FilePath, 10, 47, 190, 190);
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