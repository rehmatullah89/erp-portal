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
	**          PROJECT DEVELOPER:  Rehmat Ullah                                                 **
	**                                                                                           **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once($sBaseDir."requires/fpdf/fpdf.php");
	@require_once($sBaseDir."requires/fpdi/fpdi.php");
        @require_once($sBaseDir."requires/fpdi/Transparent.php");
	@require_once($sBaseDir."requires/qrcode/qrlib.php");

	
	function ConvertToFloatValue($str)
	{		
		$num = explode(' ', $str);
		
		if (strpos(@$num[0], '/') !== false)
		{
			$num1 = explode('/', @$num[0]);
			$num1 = @$num1[0] / @$num1[1];
		}
		
		else
			$num1= @$num[0];
		
		if (strpos(@$num[1], '/') !== false )
		{
			$num2 = explode('/', @$num[1]);
			$num2 = @$num2[0] / @$num2[1];
		}
		
		else
			$num2= @$num[1];
				
		return @number_format(($num1 + $num2),2);
	}

	
	function getMySampleSize($iQty)
	{
		$iReturned = 0;
		
		if($iQty <= 150)
		   $iReturned = 20;
		else if($iQty > 150 && $iQty < 280)
			$iReturned =  32;
		else if($iQty > 280 && $iQty < 500)
			$iReturned =  50;
		else if($iQty > 500 && $iQty < 1200)
			$iReturned =  80;
		else if($iQty > 1200 && $iQty < 3200)
			$iReturned =  125;
		else if($iQty > 3200 && $iQty < 10000)
			$iReturned =  200;
		else if($iQty > 10000 && $iQty < 35000)
			$iReturned =  315;
		else if($iQty > 35000)
			$iReturned =  500;      
		
		return $iReturned;
	}

        function getCartonSampleSize($iCartons)
        {
            $iCartonSampleSize = 0;
            
            if($iCartons >= 3 && $iCartons < 16)
                $iCartonSampleSize = 3;
            else if($iCartons >= 16 && $iCartons < 26)
                $iCartonSampleSize = 5;
            else if($iCartons >= 26 && $iCartons < 51)
                $iCartonSampleSize = 8;
            else if($iCartons >= 51 && $iCartons < 91)
                $iCartonSampleSize = 13;
            else if($iCartons >= 91 && $iCartons < 152)
                $iCartonSampleSize = 20;
            else if($iCartons >= 152 && $iCartons < 281)
                $iCartonSampleSize = 32;
            else if($iCartons >= 281 && $iCartons < 501)
                $iCartonSampleSize = 50;
            else if($iCartons >= 501 && $iCartons < 1201)
                $iCartonSampleSize = 80;
            else if($iCartons >= 1201)
                $iCartonSampleSize = 125;
            
            return $iCartonSampleSize;
        }
	
	$sSQL = "SELECT *,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
                        (SELECT signature from tbl_users where id=tbl_qa_reports.user_id) as _Signature,
	                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor,
                        (SELECT user_type FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _UserType
	         FROM tbl_qa_reports
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	$iReportId          = $objDb->getField(0, "report_id");
	$iVendor            = $objDb->getField(0, "vendor_id");
	$sVendor            = $objDb->getField(0, "_Vendor");
	$sAuditor           = $objDb->getField(0, "_Auditor");
	$sUserType          = $objDb->getField(0, "_UserType");
	$iPo                = $objDb->getField(0, "po_id");
	$iAdditionalPos     = $objDb->getField(0, "additional_pos");
	$sPo                = $objDb->getField(0, "_Po");
	$sAuditMode         = $objDb->getField(0, "audit_mode");
	$sSignature         = $objDb->getField(0,"_Signature");
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
	$iInspectedCartons  = $objDb->getField(0, "inspected_cartons");
	$iCartonsRejected   = $objDb->getField(0, "rejected_cartons");
	$fPercentDecfective = $objDb->getField(0, "defective_percent");
	$fStandard          = $objDb->getField(0, "standard");
	$fCartonsRequired   = $objDb->getField(0, "cartons_required");
	$fCartonsShipped    = $objDb->getField(0, "cartons_shipped");
	$iShipQty           = $objDb->getField(0, "ship_qty");
	$sApprovedSample    = $objDb->getField(0, "approved_sample");
	$sApprovedTrims     = $objDb->getField(0, "approved_trims");
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
        $CheckLevel         = $objDb->getField(0, "check_level");
        $TotalCartons       = $objDb->getField(0, "total_cartons");
        
	$sAllPos = $iPo;

	if($iAdditionalPos != "")
		$sAllPos = ($sAllPos.",".$iAdditionalPos);
        
	$sSpecsSheets = array( );
	
	@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

	
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
        
	
	$sSQL = "SELECT vendor, address, manager_rep, manager_rep_email, rep_picture, phone, fax, latitude, longitude FROM tbl_vendors WHERE id='$iVendor'";
	$objDb->query($sSQL);
	
	$sFactoryLatitude  = $objDb->getField(0,"latitude"); 
	$sFactoryLongitude = $objDb->getField(0,"longitude");
	$Factory           = $objDb->getField(0,"vendor");
	$FactoryPhone      = $objDb->getField(0,"phone");
	$FactoryFax        = $objDb->getField(0,"fax");
	$FactoryAddress    = $objDb->getField(0,"address");
	$FactoryRep        = $objDb->getField(0,"manager_rep");
	$FactoryRepPic     = $objDb->getField(0,"rep_picture");
	$FactoryRepEmail   = $objDb->getField(0,"manager_rep_email");

	
	@list($iLength, $iWidth, $iHeight, $sUnit) = @explode("x", $sCartonSize);

	if ($fPercentDecfective == 0)
		$fPercentDecfective = @round((($iCartonsRejected / $iTotalCartons) * 100), 2);


	$sAuditStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");


        $iAllPos = explode(",", $sAllPos);
        $iColors = explode(",", $sColors);
        $iPosColorQty = 0;
        
        foreach($iAllPos as $iPoNo)
        {
            foreach ($iColors as $sColor)
            {
                $iPosColorQty += ((int)getDbValue("order_qty", "tbl_po_colors", "po_id='$iPoNo' AND color LIKE '$sColor'"));
            }
        }
        
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

	
	$fAql         = getDbValue("aql", "tbl_brands", "id='$iParent'");
	$sDestination = getDbValue("destination", "tbl_destinations", "id='$iDestination'");
	$sPackagingImages = getList("tbl_qa_packaging_defects", "id", "CONCAT(defect_code_id, '~', picture)", "audit_id='$Id' AND picture LIKE '%.jpg'");

	
        @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

	$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_001_*.*");
	$sPictures = @array_merge($sPictures, @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*"));
	$sPictures = @array_map("strtoupper", $sPictures);
	$sPictures = @array_unique($sPictures);

	$sDefects = array( );
	$sPackings = array( );
	$sMisc    = array( );

	foreach ($sPictures as $sPicture)
	{
		$sPic = @basename($sPicture);

		if (@stripos($sPic, "_pack_") !== FALSE) // || @stripos($sPic, "_001_") !== FALSE)
			$sPackings[] = $sPicture;

		else if (@stripos($sPic, "_misc_") !== FALSE || @stripos($sPic, "_00_") !== FALSE || @substr_count($sPic, "_") < 3)
			$sMisc[] = $sPicture;

		else
			$sDefects[] = $sPicture;
	}

	$iTotalPages  = 11;
	$iTotalPages += getDbValue("COUNT(DISTINCT(CONCAT(size_id, '-', color)))", "tbl_qa_report_samples", "audit_id='$Id'");
	$iTotalPages += count($sSpecsSheets);
	$iTotalPages += @ceil(count($sPackagingImages) / 4);
	$iTotalPages += @ceil(count($sPackings) / 4);
	$iTotalPages += @ceil(count($sDefects) / 4);
	$iTotalPages += @ceil(count($sMisc) / 4);

	/////////////////////////////////////////////////////page1///////////////////////////////////////////////////////////////

	$objPdf = new AlphaPDF( );

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/triburg.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 14.5, 19);


	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(179, 35.5, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 10, "Page 1 of {$iTotalPages}");


	$objPdf->SetFont('Arial', '', 9);
        $objPdf->Text(135, 18, $sStyle);
	$objPdf->Text(135, 23.7, $sPo);
	$objPdf->Text(135, 29, $sAuditStage);
	$objPdf->Text(135, 34.5, formatDate($sAuditDate));


	$sSQL = "SELECT * FROM tbl_triburg_inspection_summary WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$sInspectionStatus              = $objDb->getField(0, "inspection_status");
	$sVisualAudit                   = $objDb->getField(0, "visual_audit");
	$sVisualAuditRemarks            = $objDb->getField(0, "visual_audit_remarks");
	$sShippingMarks                 = $objDb->getField(0, "shipping_marks");
	$sShippingMarksRemarks          = $objDb->getField(0, "shipping_marks_remarks");
	$sMaterialConformity            = $objDb->getField(0, "material_conformity");
	$sMaterialConformityRemarks     = $objDb->getField(0, "material_conformity_remarks");
	$sProductAppearance             = $objDb->getField(0, "product_apperance");
	$sProductAppearanceRemarks      = $objDb->getField(0, "product_apperance_remarks");
	$sProductColor                  = $objDb->getField(0, "product_color");
	$sProductColorRemarks           = $objDb->getField(0, "product_color_remarks");
	$sHandFeel                      = $objDb->getField(0, "hand_feel");
	$sHandFeelRemarks               = $objDb->getField(0, "hand_feel_remark");
	$sWearerTest                    = $objDb->getField(0, "wearer_test");
	$sWearerTestRemarks             = $objDb->getField(0, "wearer_test_remarks");
	$sPackingCount                  = $objDb->getField(0, "packing_count");
	$sPackingCountRemarks           = $objDb->getField(0, "packing_count_remarks");
	$sPackingFtp                    = $objDb->getField(0, "packing_ftp");
	$sPackingFtpRemarks             = $objDb->getField(0, "packing_ftp_remarks");
	$sPackingGtp                    = $objDb->getField(0, "packing_gtp");
	$sPackingGtpRemarks             = $objDb->getField(0, "packing_gtp_remarks");
	$sPacking                       = $objDb->getField(0, "packing");
	$sPackingRemarks                = $objDb->getField(0, "packing_remarks");
	$sCartonDropTest                = $objDb->getField(0, "carton_drop_test");
	$sCartonDropTestRemarks         = $objDb->getField(0, "carton_drop_remarks");
	$sShadeBand                     = $objDb->getField(0, "shade_band");
	$sShadeBandRemarks              = $objDb->getField(0, "shade_band_remarks");
	$sCartonQuality                 = $objDb->getField(0, "carton_quality");
	$sCartonQualityRemarks          = $objDb->getField(0, "carton_quality_remarks");
	$sCartonWeight                  = $objDb->getField(0, "carton_weight");
	$sCartonWeightRemarks           = $objDb->getField(0, "carton_weight_remarks");
	$sCartonDimension               = $objDb->getField(0, "carton_dimension");
	$sCartonDimensionRemarks        = $objDb->getField(0, "carton_dimension_remarks");
	$sBarcodeVerification           = $objDb->getField(0, "barcode_verification");
	$sBarcodeVerificationRemarks    = $objDb->getField(0, "barcode_verification_remarks");
	$sLabeling                      = $objDb->getField(0, "labeling");
	$sLabelingRemarks               = $objDb->getField(0, "labeling_remarks");
	$sMarkings                      = $objDb->getField(0, "markings");
	$sMarkingsRemarks               = $objDb->getField(0, "markings_remarks");
	$sWorkmanship                   = $objDb->getField(0, "workmanship");
	$sWorkmanshipRemarks            = $objDb->getField(0, "workmanship_remarks");
	$sAppearance                    = $objDb->getField(0, "appearance");
	$sAppearanceRemarks             = $objDb->getField(0, "appearance_remarks");
	$sFunction                      = $objDb->getField(0, "function");
	$sFunctionRemarks               = $objDb->getField(0, "function_remarks");
	$sPrintedMaterials              = $objDb->getField(0, "printed_materials");
	$sPrintedMaterialsRemarks       = $objDb->getField(0, "printed_materials_remarks");
	$sFinishing                     = $objDb->getField(0, "finishing");
	$sFinishingRemarks              = $objDb->getField(0, "finishing_remarks");
	$sFitting                       = $objDb->getField(0, "fitting");
	$sFittingRemarks                = $objDb->getField(0, "fitting_remarks");
	$sPpSample                      = $objDb->getField(0, "pp_sample");
	$sPpSampleRemarks               = $objDb->getField(0, "pp_sample_remarks");
	$sMetalDetectionTest            = $objDb->getField(0, "metal_detection_test");
	$sMetalDetectionTestRemarks     = $objDb->getField(0, "metal_detection_test_remarks");
	$sMeasurementResult             = $objDb->getField(0, "measurement_result");
	$sMeasurementResultRemarks      = $objDb->getField(0, "measurement_result_remarks");
	$sGarmentWeight                 = $objDb->getField(0, "garment_weight");
	$sGarmentWeightRemarks          = $objDb->getField(0, "garment_weight_remarks");
	$sCordNorm                      = $objDb->getField(0, "cords_norm");
	$sCordNormRemarks               = $objDb->getField(0, "cords_norm_remarks");
	$sInspectionConditions          = $objDb->getField(0, "inspection_conditions");
	$sInspectionConditionsRemarks   = $objDb->getField(0, "inspection_conditions_remarks");
	$sShipmentAudit                 = $objDb->getField(0, "shipment_audit");
	$sShipmentAuditRemarks          = $objDb->getField(0, "shipment_audit_remarks");
	$sRemarks                       = $objDb->getField(0, "remarks");
	$sCartonNos                     = $objDb->getField(0, "carton_numbers");


	// Report Details
	$objPdf->SetFont('Arial', '', 8);

	$objPdf->Text(60, 48.3, ($sPo.$sAdditionalPos));
	$objPdf->Text(60, 54.5, $sVendor);
	$objPdf->Text(60, 60.5, formatNumber($iPosColorQty, false));
	$objPdf->Text(60, 66.5, formatNumber($iShipQty, false));

	$objPdf->Text(140, 48.3, $sDescription);
	$objPdf->Text(140, 54.5, $sAuditor); 
	$objPdf->Text(140, 60.5, $iTotalCartons);
	$objPdf->Text(140, 66.5, $iInspectedCartons);
	$objPdf->Text(85, 85, ($CheckLevel == 1?'Single':($CheckLevel == 2?'Double':'')));        
        $objPdf->Text(85, 91, $iTotalGmts);
        $objPdf->Text(160, 85, "2.5");
        
	$objPdf->Text(60, 105.5, $Factory);
	$objPdf->Text(60, 111, $FactoryAddress);
	$objPdf->Text(60, 116, $iVendor);
	$objPdf->Text(60, 121, $sAuditDate);

	
	if ($sFactoryLatitude != "" && $sFactoryLongitude != "" && $sLatitude != "" && $sLongitude != "")
	{
		$sDistance = calculateDistance($sFactoryLatitude, $sFactoryLongitude, $sLatitude, $sLongitude);
		$objPdf->Text(31, 147.5, $sDistance);            
	}
  
	if ($sLatitude != "" && $sLongitude != "")
	{	
		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(6, 82, 195);

		if($sFactoryLatitude != "" && $sFactoryLongitude != "")
		{
			$objPdf->SetXY(99, 130);
			$objPdf->Write(5, "(". formatNumber($sFactoryLatitude, true, 8).",". formatNumber($sFactoryLongitude, true, 8).")", "http://maps.google.com/maps?q={$sFactoryLatitude},{$sFactoryLongitude}&z=12");
		}

		$objPdf->SetXY(99, 136);
		$objPdf->Write(5, "(". formatNumber($sLatitude, true, 8).",". formatNumber($sLongitude, true, 8).")", "http://maps.google.com/maps?q={$sLatitude},{$sLongitude}&z=12");
                
		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(50, 50, 50);
	  
		
		if($sFactoryLatitude != "" && $sFactoryLongitude != "")
			$map = file_get_contents("https://maps.googleapis.com/maps/api/staticmap?center=".$sLatitude.",".$sLongitude."&zoom=11&size=1000x450&markers=color:yellow|".$sLatitude.",".$sLongitude."&markers=color:red|".$sFactoryLatitude.",".$sFactoryLongitude."&key=AIzaSyDKes4Df5NgRtYcQ_muoqUadWiI3v6GURg"); 
		else
			$map = file_get_contents("https://maps.googleapis.com/maps/api/staticmap?center=".$sLatitude.",".$sLongitude."&zoom=13&size=1000x450&markers=color:yellow|".$sLatitude.",".$sLongitude."&key=AIzaSyDKes4Df5NgRtYcQ_muoqUadWiI3v6GURg"); 
		
		$image = imagecreatefromstring($map);
		$saved = imagejpeg($image, $sBaseDir."temp2/googlemapImage.jpg");
		unset($map);
		
		$objPdf->Image($sBaseDir.'temp2/googlemapImage.jpg', 68, 150, 120,68);
		unlink($sBaseDir.'temp2/googlemapImage.jpg');
	}
	
	else if($sFactoryLatitude != "" && $sFactoryLongitude != "")
	{
		$objPdf->SetFont('Arial', '', 6);
		$objPdf->Text(99, 140, ($sAuditMode == 2)?"Audit location/cordinates are un-available as deveice has not captured cordinates.":"Audit location/cordinates are not available as audit has been conducted via web-portal.");
	
		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(6, 82, 195);
                
		$objPdf->SetXY(99, 130);
		$objPdf->Write(5, "(". formatNumber($sFactoryLatitude, true, 8).",". formatNumber($sFactoryLongitude, true, 8).")", "http://maps.google.com/maps?q={$sFactoryLatitude},{$sFactoryLongitude}&z=12");
		
		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(50, 50, 50);
		
		$map = file_get_contents("https://maps.googleapis.com/maps/api/staticmap?center=".$sFactoryLatitude.",".$sFactoryLongitude."&zoom=13&size=1000x450&markers=color:red|".$sFactoryLatitude.",".$sFactoryLongitude."&key=AIzaSyDKes4Df5NgRtYcQ_muoqUadWiI3v6GURg"); 
		$image = imagecreatefromstring($map);
		$saved = imagejpeg($image, $sBaseDir."temp2/googlemapImage.jpg");
		unset($map);
		
		$objPdf->Image($sBaseDir.'temp2/googlemapImage.jpg', 68, 150, 120,68);
		unlink($sBaseDir.'temp2/googlemapImage.jpg');
    }
	
	else
		$objPdf->Text(99, 139, getDbValue("city", "tbl_vendors", "id='$iVendor'"));

	
	$objPdf->Text(36, 236.5, $FactoryRep);
	$objPdf->Text(142, 240, $FactoryRepEmail);
	$objPdf->Text(142, 248.5, $FactoryPhone);
	$objPdf->Text(142, 256.5, $FactoryFax);

	if ($sSignature != "" && @file_exists($sBaseDir.USER_SIGNATURES_IMG_DIR.$sSignature))
	{
		$objPdf->Image($sBaseDir.USER_SIGNATURES_IMG_DIR.$sSignature, 32, 242, 23, 17);
	}
        
	if($FactoryRepPic == "")
		$FactoryRepPic = "default.jpg";
	
	if (@file_exists($sBaseDir.'files/representative/'.$FactoryRepPic))
	{
		$objPdf->Image($sBaseDir.'files/representative/'.$FactoryRepPic, 78, 227, 32, 30);
	}
    	
	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 2


	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/triburg.pdf");
	$iTemplateId = $objPdf->importPage(2, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 14.5, 19);


	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(179, 35.5, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 10, "Page 2 of {$iTotalPages}");


        $objPdf->Text(135, 18, $sStyle);
	$objPdf->Text(135, 23.7, $sPo);
	$objPdf->Text(135, 29, $sAuditStage);
	$objPdf->Text(135, 34.5, formatDate($sAuditDate));


	// Quantity Details
	$objPdf->SetFont('Arial', '', 6);
        
        $sPackaginResult = getDbValue("COUNT(1)", "tbl_qa_packaging_details", "result!='P' AND audit_id='$Id'");
        
        $sOverAllResult = "P";
        if($sMeasurementResult != "P" || $sPackaginResult > 0 || $sAuditResult != "P")
            $sOverAllResult = "F";
        
        $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sOverAllResult == "P") ? 15.5 : 46.5), 50.5, 5);
        
        $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), ($sAuditResult == "P"?149:170), 45, 4);
        $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), ($sMeasurementResult == "P"?149:170), 51, 4);
        $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), ($sPackaginResult > 0?170:149), 57, 4);
        
        if ($sVisualAudit != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sVisualAudit == "P") ? 109 : 122), 73, 4);

        $objPdf->SetXY(130, 73);
	$objPdf->MultiCell(70, 2.6, $sVisualAuditRemarks, 0);

	if ($sShippingMarks != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sShippingMarks == "P") ? 109 : 122), 78, 4);

        $objPdf->SetXY(130, 78);
	$objPdf->MultiCell(70, 2.6, $sShippingMarksRemarks, 0);

	
	if ($sMaterialConformity != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sMaterialConformity == "P") ? 109 : 122), 83, 4);

        $objPdf->SetXY(130, 83);
	$objPdf->MultiCell(70, 2.6, $sMaterialConformityRemarks, 0);
        
        if ($sProductAppearance != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sProductAppearance == "P") ? 109 : 122), 88, 4);

        $objPdf->SetXY(130, 88);
	$objPdf->MultiCell(70, 2.6, str_replace("’","'",$sProductAppearanceRemarks), 0);

	if ($sProductColor != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sProductColor == "P") ? 109 : 122), 93, 4);

        $objPdf->SetXY(130, 93);
	$objPdf->MultiCell(70, 2.6, $sProductColorRemarks, 0);


	if ($sHandFeel != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sHandFeel == "P") ? 109 : 122), 98, 4);

        $objPdf->SetXY(130, 98);
	$objPdf->MultiCell(70, 2.6, $sHandFeelRemarks, 0);

	if ($sWearerTest != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sWearerTest == "P") ? 109 : 122), 103, 4);

        $objPdf->SetXY(130, 103);
	$objPdf->MultiCell(70, 2.6, $sWearerTestRemarks, 0);

	if ($sPackingCount != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sPackingCount == "P") ? 109 : 122), 108, 4);

        $objPdf->SetXY(130, 108);
	$objPdf->MultiCell(70, 2.6, $sPackingCountRemarks, 0);

	if ($sPackingFtp != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sPackingFtp == "P") ? 109 : 122), 113, 4);

        $objPdf->SetXY(130, 113);
	$objPdf->MultiCell(70, 2.6, $sPackingFtpRemarks, 0);
	
	if ($sPackingGtp != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sPackingGtp == "P") ? 109 : 122), 118, 4);

        $objPdf->SetXY(130, 118);
	$objPdf->MultiCell(70, 2.6, $sPackingGtpRemarks, 0);

	if ($sPacking != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sPacking == "P") ? 109 : 122), 123, 4);

        $objPdf->SetXY(130, 123);
	$objPdf->MultiCell(70, 2.6, $sPackingRemarks, 0);

	if ($sCartonDropTest != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sCartonDropTest == "P") ? 109 : 122), 128, 4);

        $objPdf->SetXY(130, 128);
	$objPdf->MultiCell(70, 2.6, $sCartonDropTestRemarks, 0);


	if ($sShadeBand != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sShadeBand == "P") ? 109 : 122), 133, 4);

        $objPdf->SetXY(130, 133);
	$objPdf->MultiCell(70, 2.6, $sShadeBandRemarks, 0);
	
	if ($sCartonQuality != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sCartonQuality == "P") ? 109 : 122), 138, 4);

        $objPdf->SetXY(130, 138);
	$objPdf->MultiCell(70, 2.6, $sCartonQualityRemarks, 0);

	if ($sCartonWeight != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sCartonWeight == "P") ? 109 : 122), 143, 4);

        $objPdf->SetXY(130, 143);
	$objPdf->MultiCell(70, 2.6, $sCartonWeightRemarks, 0);

        if ($sCartonDimension != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sCartonDimension == "P") ? 109 : 122), 148, 4);

        $objPdf->SetXY(130, 148);
	$objPdf->MultiCell(70, 2.6, $sCartonDimensionRemarks, 0);


	if ($sBarcodeVerification != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sBarcodeVerification == "P") ? 109 : 122), 153, 4);

        $objPdf->SetXY(130, 153);
	$objPdf->MultiCell(70, 2.6, $sBarcodeVerificationRemarks, 0);


	if ($sLabeling != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sLabeling == "P") ? 109 : 122), 158, 4);

        $objPdf->SetXY(130, 158);
	$objPdf->MultiCell(70, 2.6, $sLabelingRemarks, 0);

	if ($sMarkings != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sMarkings == "P") ? 109 : 122), 163, 4);

        $objPdf->SetXY(130, 163);
	$objPdf->MultiCell(70, 2.6, $sMarkingsRemarks, 0);

        if ($sWorkmanship != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sWorkmanship == "P") ? 109 : 122), 168, 4);

        $objPdf->SetXY(130, 168);
	$objPdf->MultiCell(70, 2.6, $sWorkmanshipRemarks, 0);
        
        if ($sAppearance != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sAppearance == "P") ? 109 : 122), 173, 4);

        $objPdf->SetXY(130, 173);
	$objPdf->MultiCell(70, 2.6, $sAppearanceRemarks, 0);

        if ($sFunction != "")
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sFunction == "P") ? 109 : 122), 179, 4);

        $objPdf->SetXY(130, 179);
	$objPdf->MultiCell(70, 2.6, $sFunctionRemarks, 0);
        
        if ($sPrintedMaterials != "")
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sPrintedMaterials == "P") ? 109 : 122), 184, 4);

        $objPdf->SetXY(130, 184);
	$objPdf->MultiCell(70, 2.6, $sPrintedMaterialsRemarks, 0);
        
        if ($sFinishing != "")
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sFinishing == "P") ? 109 : 122), 189, 4);

        $objPdf->SetXY(130, 189);
	$objPdf->MultiCell(70, 2.6, $sFinishingRemarks, 0);
        
        if ($sFitting != "")
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sFitting == "P") ? 109 : 122), 194, 4);

        $objPdf->SetXY(130, 194);
	$objPdf->MultiCell(70, 2.6, $sFittingRemarks, 0);
        
        if ($sPpSample != "")
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sPpSample == "P") ? 109 : 122), 199, 4);

        $objPdf->SetXY(130, 199);
	$objPdf->MultiCell(70, 2.6, $sPpSampleRemarks, 0);
        
        if ($sMetalDetectionTest != "")
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sMetalDetectionTest == "P") ? 109 : 122), 204, 4);

        $objPdf->SetXY(130, 204);
	$objPdf->MultiCell(70, 2.6, $sMetalDetectionTestRemarks, 0);
        
        if ($sShipmentAudit != "")
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sShipmentAudit == "P") ? 109 : 122), 209, 4);

        $objPdf->SetXY(130, 209);
	$objPdf->MultiCell(70, 2.6, $sShipmentAuditRemarks, 0);
       
        $objPdf->SetFont('Arial', '', 8);
        
        $objPdf->SetXY(12, 224);
	$objPdf->MultiCell(70, 2.6, $sRemarks, 0);
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE #3
        $iCurrentPage = 3;
                
	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/triburg.pdf");
	$iTemplateId = $objPdf->importPage(3, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 14.5, 19);


	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(179, 35.5, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 10, "Page {$iCurrentPage} of {$iTotalPages}");


        $objPdf->Text(135, 18, $sStyle);
	$objPdf->Text(135, 23.7, $sPo);
	$objPdf->Text(135, 29, $sAuditStage);
	$objPdf->Text(135, 34.5, formatDate($sAuditDate));


	// Ratio page
	$objPdf->SetFont('Arial', '', 6);
        

	$sQaQuantitiesList = getList("tbl_qa_report_quantities", "CONCAT(po_id, '-', size_id, '-', color)", "quantity", "audit_id='$Id'");
	 
	$sSQL = "SELECT po.id, pc.color, po.order_no,
				   s.id as _iSize, s.size,
				   SUM(pq.quantity) AS _Quantity
			FROM tbl_po po, tbl_po_colors pc, tbl_po_quantities pq, tbl_sizes s
			WHERE po.id=pc.po_id AND pc.po_id=pq.po_id AND pq.size_id=s.id AND pc.style_id='$iStyle' AND (pc.po_id='$iPo' OR FIND_IN_SET(pc.po_id, '$sAdditionalPos'))
				  AND pq.quantity>'0' AND FIND_IN_SET(s.id, '$sSizes') AND FIND_IN_SET(pc.color, '$sColors') AND pq.color_id=pc.id
			GROUP BY po.id, pc.color, s.id
			ORDER BY po.id, pc.color, s.position";
	$objDb->query($sSQL);

	$iCount     = $objDb->getCount( );
	$iTop       = 63;
	$sLastColor = "";
	$iTotalTQunatity = 0;
	$iTotalPQuantity = 0;
        $fPoPercent      = 0;
        $fPoDeviation    = 0;
        $fTotalPercent   = 0;
        $fTotalDeviation = 0;
        
        $iGrandTQuantity    = 0;
        $iGrandPQuantity    = 0;
        $iGrandPercent      = 0;
        $iGrandDeviation    = 0;
	
	for($i = 0; $i < $iCount; $i ++)
	{
		$iTPoId     = $objDb->getField($i, 'po.id');
		$sTOrderNo  = $objDb->getField($i, 'order_no');
		$sTColor    = $objDb->getField($i, 'color');
		$iTSize     = $objDb->getField($i, '_iSize');
		$sTSize     = $objDb->getField($i, 'size');
		$iTQunatity = $objDb->getField($i, '_Quantity');

		if ($sTColor != $sLastColor && $i > 0)
		{
			$objPdf->SetFont('Arial', 'B', 6);
			$fPoPercent     = ($iTotalPQuantity / $iTotalTQunatity)*100;
                        $fPoDeviation   = $iTotalPQuantity - $iTotalTQunatity;
                        
			$objPdf->Text(15, $iTop, "TOTAL");
			$objPdf->Text(38, $iTop, $sTColor);
			$objPdf->Text(110, $iTop, "");
			$objPdf->Text(125, $iTop, formatNumber($iTotalTQunatity, false));
			$objPdf->Text(145, $iTop, formatNumber($iTotalPQuantity, false));
			$objPdf->Text(165, $iTop, formatNumber($fPoPercent)." %");
			$objPdf->Text(185, $iTop,  formatNumber($fPoDeviation, false));
			
                        $iGrandTQuantity    += $iTotalTQunatity;
                        $iGrandPQuantity    += $iTotalPQuantity;
        
                        $iTotalTQunatity = 0;
                        $iTotalPQuantity = 0;
                        
			$iTop += 8.40;
			
			$objPdf->SetFont('Arial', '', 6);
		}		
		
		$iPQuantity       = $sQaQuantitiesList["{$iTPoId}-{$iTSize}-{$sTColor}"];
		
		$sLastColor         = $sTColor;		
                $iTotalTQunatity    += $iTQunatity;
		$iTotalPQuantity    += $iPQuantity;
		
		$objPdf->Text(15, $iTop, $sTOrderNo);
		$objPdf->Text(38, $iTop, $sTColor);
		$objPdf->Text(110, $iTop, $sTSize);
		$objPdf->Text(125, $iTop, formatNumber($iTQunatity, false));
		$objPdf->Text(145, $iTop, formatNumber($iPQuantity, false));
		$objPdf->Text(165, $iTop, formatNumber(($iPQuantity/$iTQunatity)*100)." %");
		$objPdf->Text(185, $iTop,  formatNumber(($iPQuantity-$iTQunatity), false));
		
		$iTop += 4.20;
	}
	
	
	$objPdf->SetFont('Arial', 'B', 6);
	
	$objPdf->Text(15, $iTop, "TOTAL");
	$objPdf->Text(38, $iTop, $sTColor);
	$objPdf->Text(110, $iTop, "");
	$objPdf->Text(125, $iTop, formatNumber($iTotalTQunatity, false));
	$objPdf->Text(145, $iTop, formatNumber($iTotalPQuantity, false));
	$objPdf->Text(165, $iTop, formatNumber(($iTotalPQuantity / $iTotalTQunatity)*100)." %");
	$objPdf->Text(185, $iTop,  formatNumber(($iTotalPQuantity - $iTotalTQunatity), false));
        
        if($iCount > 1)
        {
            $iGrandTQuantity    += $iTotalTQunatity;
            $iGrandPQuantity    += $iTotalPQuantity;
                        
            $objPdf->Text(110, $iTop+8.4, "G.Total");
            $objPdf->Text(125, $iTop+8.4, formatNumber($iGrandTQuantity, false));
            $objPdf->Text(145, $iTop+8.4, formatNumber($iGrandPQuantity, false));
            $objPdf->Text(165, $iTop+8.4, formatNumber(($iGrandPQuantity/$iGrandTQuantity)*100)." %");
            $objPdf->Text(185, $iTop+8.4, formatNumber(($iGrandPQuantity-$iGrandTQuantity), false));
        }
	
/*
	$iQunatitiesList   = array();
	$sOrderNosList     = array();
	$sSizesList        = array();

	for($i = 0; $i < $iCount; $i ++)
	{
		$iTPoId     = $objDb->getField($i, 'po.id');
		$iTOrderNo  = $objDb->getField($i, 'order_no');
		$sTColor    = $objDb->getField($i, 'color');
		$iTSize     = $objDb->getField($i, '_iSize');
		$sTSize     = $objDb->getField($i, 'size');
		$iTQunatity = $objDb->getField($i, '_Quantity');
		
		$sSizesList[$iTSize]    = $sTSize;
		$sOrderNosList[$iTPoId] = $iTOrderNo;
		$iQunatitiesList[$iTPoId][$iTSize]["{$sTColor}"] = $iTQunatity;
	}


	$sSQL = "SELECT * FROM tbl_qa_report_quantities WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	
        $iTop = 63;
        
	for($i = 0; $i < $iCount; $i ++)
        {
                $iMPo       = $objDb->getField($i, 'po_id');
                $iMSize     = $objDb->getField($i, 'size_id');
                $sMColor    = $objDb->getField($i, 'color');
                $iMQunatity = $objDb->getField($i, 'quantity');
                
                $sMSize      = $sSizesList[$iMSize];
                $sMOrderNo   = $sOrderNosList[$iTPoId];
                $iAQunatity  = $iQunatitiesList[$iMPo][$iMSize]["{$sMColor}"];
                
                $objPdf->Text(15, $iTop, $sMOrderNo);
                $objPdf->Text(38, $iTop, $sMColor);
                $objPdf->Text(110, $iTop, $sMSize);
                $objPdf->Text(125, $iTop, $iAQunatity);
                $objPdf->Text(145, $iTop, $iMQunatity);
                $objPdf->Text(165, $iTop, formatNumber(($iMQunatity/$iAQunatity)*100));
                $objPdf->Text(185, $iTop,  formatNumber($iMQunatity - $iAQunatity));
                
                $iTop += 4.15;
        }
*/
        $iCurrentPage ++;
        
        /////////////////////////////////////////Page #4 ///////////////////////////////////////////////
        $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/triburg.pdf");
	$iTemplateId = $objPdf->importPage(4, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 14.5, 19);


	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(179, 35.5, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 10, "Page {$iCurrentPage} of {$iTotalPages}");


        $objPdf->Text(135, 18, $sStyle);
	$objPdf->Text(135, 23.7, $sPo);
	$objPdf->Text(135, 29, $sAuditStage);
	$objPdf->Text(135, 34.5, formatDate($sAuditDate));

        // Defects Lodged
	$objPdf->SetFont('Arial', '', 6);
        
        $sSQL = "SELECT code_id, 
                        SUM(IF(nature='2', defects, '0')) AS _Critical,
                        SUM(IF(nature='1', defects, '0')) AS _Major,
                        SUM(IF(nature='0', defects, '0')) AS _Minor
                FROM tbl_qa_report_defects
                WHERE audit_id='$Id'
                GROUP BY code_id
                ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$fTop   = 48.5;
        
        $TotalMajor    = 0;
        $TotalMinor    = 0;
        $TotalCritical = 0;

        for($i = 0; $i < $iCount; $i ++)
	{
            $fTop     += 5.1;            
            $iCritical = $objDb->getField($i, "_Critical");
            $iMajor    = $objDb->getField($i, "_Major");
            $iMinor    = $objDb->getField($i, "_Minor");
            
            $TotalMajor    += $iMajor;
            $TotalMinor    += $iMinor;
            $TotalCritical += $iCritical;
        
            if($i <= 37)
            {                
            	$sSQL2 = ("SELECT defect, code, (SELECT type from tbl_defect_types where tbl_defect_codes.type_id=tbl_defect_types.id) as _Type FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
		$objDb2->query($sSQL2);

                $sDefect     = $objDb2->getField(0, 0);		
		$sDefectCode = $objDb2->getField(0, 1);
                $sDefectType = $objDb2->getField(0, 2);
            
                $objPdf->Text(17, $fTop, $sDefectCode);
                $objPdf->Text(35, $fTop, $sDefectType);
                $objPdf->Text(64, $fTop, $sDefect);
        
                $objPdf->Text(167, $fTop, $iCritical);
		$objPdf->Text(180, $fTop, $iMajor);
                $objPdf->Text(193, $fTop, $iMinor);
            }
        }
        
        $objPdf->Text(167, 213, $TotalCritical);
        $objPdf->Text(180, 213, $TotalMajor);  
        $objPdf->Text(193, 213, $TotalMinor);  
        
        $objPdf->Text(167, 217.5, "0");
        $objPdf->Text(180, 217.5, $iAqlChart[$iTotalGmts]["2.5"]);
	$objPdf->Text(193, 217.5, ($iAqlChart[$iTotalGmts]["2.5"])*3);

      	$objPdf->SetFont('Arial', 'B', 9);
        if($sAuditResult == "P")
            $objPdf->SetTextColor(0, 100, 0);
        else
            $objPdf->SetTextColor(100, 0, 0);
	$objPdf->Text(177, 222.5, (($sAuditResult == "P") ? "PASS" : "FAIL"));
        
        $objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);
        $objPdf->SetXY(12, 233.5);
	$objPdf->MultiCell(188, 4.7, $sComments, 0);
        $iCurrentPage++;
        
    /////////////////////////////////////////////////// Page 5 /////////////////////////	 
	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/triburg.pdf");
	$iTemplateId = $objPdf->importPage(5, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 14.5, 19);

	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(179, 35.5, "Audit Code: {$sAuditCode}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 10, "Page {$iCurrentPage} of {$iTotalPages}");

        $objPdf->Text(135, 18, $sStyle);
	$objPdf->Text(135, 23.7, $sPo);
	$objPdf->Text(135, 29, $sAuditStage);
	$objPdf->Text(135, 34.5, formatDate($sAuditDate));

	$objPdf->SetFont('Arial', '', 7);

	$sSizes  = getDbValue("GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',')", "tbl_qa_report_samples", "audit_id='$Id'");
	$iSizes  = @explode(",", $sSizes);
        $sColors = @explode(",", $sColors);

        $objPdf->Text(47, 57.5, formatNumber($iQuantity, false));
        $objPdf->Text(47, 62.5, count($iSizes));
        $objPdf->Text(47, 67, count($sColors));
        $objPdf->Text(47, 72, $iTotalGmts);
        
	//$objPdf->Text(45, 55, getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id'"));
	//$objPdf->Text(45, 60, getDbValue("GROUP_CONCAT(size SEPARATOR ',')", "tbl_sampling_sizes", "id IN ($sSizes)"));

	$objPdf->SetFont('Arial', '', 6);
	
	
	$sSizeFindings  = array( );
	$sSizeFindings2 = array( );
	$iPoints        = array( );
        
	$sSQL = "SELECT qrs.size_id, qrs.color, qrs.audit_id, qrs.sample_no, qrss.point_id, qrss.findings
			FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
			WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id
			ORDER BY qrss.point_id, qrs.sample_no";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	
	for($i = 0; $i < $iCount; $i ++)
	{
		$iSize          = $objDb->getField($i, 'size_id');
                $sSampleColor   = $objDb->getField($i, 'color');
		$iPoint         = $objDb->getField($i, 'point_id');
		$iSampleNo      = $objDb->getField($i, 'sample_no');
		$sFindings      = $objDb->getField($i, 'findings');
		
		$iSamplesCount   = (int)getDbValue("COUNT(1)", "tbl_style_specs", " point_id='$iPoint' AND style_id='$iStyle' AND version='0' AND specs!='0' AND specs!='' AND size_id IN ($sSizes)");
                $sSpecValue      =  getDbValue("specs", "tbl_style_specs", " point_id='$iPoint' AND style_id='$iStyle' AND size_id='$iSize'");
		$iSamplesChecked = (int)getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize'");
		$iSampleSize     = ($iSamplesCount * $iSamplesChecked);
		
		$sSizeFindings["{$i}-{$iSampleNo}-{$iPoint}"] = array('finiding' => $sFindings, 'sample_size' => $iSampleSize, 'size_id' => $iSize, 'color'=>$sSampleColor, 'spec_value'=> $sSpecValue);
		$sSizeFindings2["{$iSize}-{$iSampleNo}-{$iPoint}"] = $sFindings;
		
		$iPoints[] = $iPoint;
	}
	
	$sPoints        = @explode(",", $iPoints);
	$sToleranceList = getList("tbl_measurement_points", "id", "COALESCE(tolerance, '0')", "brand_id='$iBrand'", "id");
	$sPointList     = getList("tbl_measurement_points", "id", "point", "brand_id='$iBrand'", "id");
	
        $iLastPoint = 0;
        $sSpecArray = array();

        foreach ($sSizeFindings as $sSampleNPoint => $sFindings)
        {
                $sSamplePoint  = @explode("-", $sSampleNPoint);
                $iPoint        = @$sSamplePoint[2];

                $sFinding      = $sFindings['finiding'];
                $sSampleColor  = $sFindings['color'];
                $iSampleSize   = $sFindings['sample_size'];
                $sSpecs        = ConvertToFloatValue($sFindings['spec_value']);

                $sPoint        = @$sPointList[$iPoint];
                $sTolerance    = @$sToleranceList[$iPoint];

                $fMeaseuredValue = trim($sFinding);
                $fSpecValue      = trim($sSpecs);
                $fTolerance      = parseTolerance($sTolerance);

                $fNTolerance       = $fTolerance[0];
                $fPTolerance       = $fTolerance[1]; 

                $PositiveTolerance = ($fSpecValue + $fPTolerance);
                $NegativeTolerance = ($fSpecValue - $fNTolerance);
                
                if (trim($sFinding) == "" && strtolower($sFinding) == "ok" && $sFinding == "0")
                {
                        //$sFinding = 0;
                        continue;
                }
                
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

                        if ($fPercent > 10)
                                $MajorDefects++;

                        else if($fPercent > 0 && $fPercent < 10)
                                $MinorDefects++;

                        $TotalPercent = (($MajorDefects+$MinorDefects)/$iSampleSize)*100;
                        $sSpecArray[$iPoint] = array('point'=> $iPoint, 'major'=>$MajorDefects, 'minor'=>$MinorDefects, 'percent' => $TotalPercent, 'sample_size' => $iSampleSize);
                        $iLastPoint = $iPoint;
                }

        }

        foreach($sSpecArray as $k=>$v)
        {
            $sort['major'][$k] = $v['major'];
            $sort['minor'][$k] = $v['minor'];
        }
		
        array_multisort($sort['major'], SORT_DESC, $sort['minor'], SORT_DESC,$sSpecArray);
        
        $iTop               = 195;
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
                
                $objPdf->SetXY(11, ($iTop - 1.5));
                $objPdf->MultiCell(120, 1.3, @$sPointList[$iPoint], 0);

                $objPdf->Text(162, $iTop, $sDefectsArr['minor']);
                $objPdf->Text(177, $iTop, $sDefectsArr['major']);
                $objPdf->Text(189, $iTop, number_format($sDefectsArr['percent'],2));

                $iTop += 3.35;
            }   
			
            $limit++;
        }
        	
        $objPdf->Text(162, 259, formatNumber($iTotalMinor, false));
        $objPdf->Text(177, 259, formatNumber($iTotalMajor, false));
        
        //sizez box
        $objPdf->SetFont('Arial', 'B', 7);
        $objPdf->SetTextColor(50, 50, 50);
        $sQtyPerSize            = "";
        $iSizeTop               = 91.2;
        $iTotalCriticalPoints   = 0;
        $iTotalEvaluatedPoints  = 0;
        $iTotalDefectivePoints  = 0;
        
        /*$sSQL = "SELECT *
			FROM tbl_qa_report_quantities
			WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	for($i = 0; $i < $iCount; $i ++)*/
        foreach ($iSizes as $iSize)
        {
                //$iSize       = $objDb->getField($i, 'size_id');
                //$sMColor     = $objDb->getField($i, 'color');
                //$iMQunatity  = $objDb->getField($i, 'quantity');
                $sSize       = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");
                $iQaSize     = getDbValue("id","tbl_sizes","size LIKE '$sSize'");
                $sMColor     = getDbValue("color","tbl_qa_report_quantities","size_id='$iQaSize' AND audit_id='$Id'");
                $iMQunatity  = getDbValue("quantity","tbl_qa_report_quantities","size_id='$iQaSize' AND audit_id='$Id'");
            
                $iMSampleSize= getMySampleSize($iMQunatity);  
                
                if ($sQtyPerSize != "")
                        $sQtyPerSize .= ", ";

                $TotalInspections = getDbValue('COUNT(DISTINCT sample_no)', 'tbl_qa_report_samples', "audit_id='$Id' AND size_id='$iSize'");
                $iTotalMeaseurementPoints = getDbValue("COUNT(point_id)", "tbl_style_specs", "style_id='$iStyle' AND size_id='$iSize' AND version='0' AND specs!='0' AND specs!=''");
                $iSamplesChecked = getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize'");
			
                if($iTotalMeaseurementPoints <= 0)
                    continue;
                
                if ($iSamplesChecked > 5)
                        $iSamplesChecked = 5;
                
                $sSQL = "SELECT point_id, specs, nature,
                        (SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance
                FROM tbl_style_specs
                WHERE style_id='$iStyle' AND size_id='$iSize' AND version='0' AND specs!='0' AND specs!=''
                ORDER BY id";
                $objDb2->query($sSQL);
                $iCount2        = $objDb2->getCount( );
                $count          = 0;
                
                
                for($k=0; $k < $iCount2; $k++)
                {
                    for ($j = 1; $j <= $iSamplesChecked; $j ++)
                    {
                        $iPoint     = $objDb2->getField($k, 'point_id');
                        $sSpecs     = $objDb2->getField($k, 'specs');
                        $sTolerance = $objDb2->getField($k, '_Tolerance');
                        $sNature    = $objDb2->getField($k, 'nature');
                        $sFinding   = $sSizeFindings2["{$iSize}-{$j}-{$iPoint}"];
                        
                        if ($sFinding != "" && strtolower($sFinding) != "ok" && strtolower($sFinding) != "0")
                        {

                            $fMeaseuredValue  = $sFinding;
                            $fSpecValue       = ConvertToFloatValue($sSpecs);
                            $fTolerance       = parseTolerance($sTolerance);

                            $fNTolerance       = $fTolerance[0];
                            $fPTolerance       = $fTolerance[1];

                            $PositiveTolerance = $fSpecValue + abs($fPTolerance);
                            $NegativeTolerance = $fSpecValue - abs($fNTolerance);

                            if($fMeaseuredValue >= $NegativeTolerance && $fMeaseuredValue <= $PositiveTolerance)
                            {
                                continue;
                            }
                            else
                            {
                                if($sNature == 'C')
                                    $iTotalCriticalPoints++;
                                    
                                $count++;
                            }
                        }
                    }
                }
                
                $objPdf->Text(12, $iSizeTop, $sSize);
                $objPdf->Text(20, $iSizeTop, $sMColor);
                $objPdf->Text(93, $iSizeTop, ceil($iMSampleSize*0.1));
                $objPdf->Text(128, $iSizeTop, $iTotalMeaseurementPoints.' x '.$TotalInspections);
                $objPdf->Text(180, $iSizeTop, number_format($count));
                $iTotalDefectivePoints += $count;
                $iTempMeasure = $iTotalMeaseurementPoints * $TotalInspections;
                $iTotalEvaluatedPoints += $iTempMeasure;
                
                $iSizeTop +=4.8;
        }
		
		
        $objPdf->Text(170, 57.5, $iTotalEvaluatedPoints);
        $objPdf->Text(170, 62.5, $iTotalDefectivePoints);
        $objPdf->Text(170, 67.5, $iTotalCriticalPoints);
        $iGenealPercent = ($iTotalDefectivePoints/$iTotalEvaluatedPoints)*100;
        $objPdf->Text(189, 259, number_format(($iGenealPercent),2).'%');
        $objPdf->SetFont('Arial', 'B', 7);

/*        if($iGenealPercent > 20)
        {
           $objPdf->SetTextColor(255, 0, 0);
           $objPdf->Text(190, 55, 'Fail');
        }		
        else
        {
           $objPdf->SetTextColor(0, 100, 0);
           $objPdf->Text(190, 55, 'Pass');
        }*/
	$iCurrentPage++;
        
	//////////////////////////////////////////////// PAGE 6 /////////////////////////////////////////////////////  

        $iPageCount   = $objPdf->setSourceFile($sBaseDir."templates/triburg.pdf");
	$iTemplateId  = $objPdf->importPage(6, '/MediaBox');

	$sSizes  = getDbValue("GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',')", "tbl_qa_report_samples", "audit_id='$Id'");
	$iSizes  = @explode(",", $sSizes);
	//$sColors = @explode(",", $sColors);

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
                        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 14.5, 19);

                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(179, 35.5, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(11, 10, "Page {$iCurrentPage} of {$iTotalPages}");


                        $objPdf->Text(135, 18, $sStyle);
                        $objPdf->Text(135, 23.7, $sPo);
                        $objPdf->Text(135, 29, $sAuditStage);
                        $objPdf->Text(135, 34.5, formatDate($sAuditDate));
                        

                        $objPdf->Text(152, 50, "Inches");
			$sSize = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");


			$objPdf->SetFont('Arial', '', 8);
			$objPdf->Text(38, 50.5, $iSamplesMeasured);
			//$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 160, 47, 4);
                        //$objPdf->Text(38, 52.2, $sQtyPerSize);
                        
                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->SetXY(132, 63);
			$objPdf->MultiCell(55, 3, $sColor, 0, "L");

			$objPdf->SetXY(37, 52.5);
			$objPdf->MultiCell(162, 4, $sQtyPerSize, 0, "L");


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(77.5, 225, $sSize);


			$sSQL = "SELECT point_id, specs, nature,
							(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
							(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point
					 FROM tbl_style_specs
					 WHERE style_id='$iStyle' AND size_id='$iSize' AND version='0' AND specs!='0' AND specs!=''
					 ORDER BY id
					 LIMIT 27";
			$objDb->query($sSQL);

			$iCount          = $objDb->getCount( );
			$iOut            = 0;
			$iSamplesChecked = getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize' AND color LIKE '$sColor'");
			
			if ($iSamplesChecked > 5)
				$iSamplesChecked = 5;

			
			for($i = 0; $i < $iCount; $i ++)
			{
				$iPoint     = $objDb->getField($i, 'point_id');
				$sSpecs     = $objDb->getField($i, 'specs');
                                $sNature    = $objDb->getField($i, 'nature');
				$sPoint     = $objDb->getField($i, '_Point');
				$sTolerance = $objDb->getField($i, '_Tolerance');


				$objPdf->SetFont('Arial', '', 7);
				$objPdf->Text(13, (80.5 + ($i * 5.320)), ($i + 1));

                                if($sNature == 'C')
                                    $objPdf->SetTextColor(255, 0, 0);
                                
				$objPdf->SetXY(20.5, (77 + ($i * 5.320)));
				$objPdf->MultiCell(85, 2.2, $sPoint, 0, "L");
                                
                                $objPdf->SetTextColor(50, 50, 50);
                                
				$objPdf->Text(122.5, (80.5 + ($i * 5.320)), $sSpecs);

				$objPdf->Text(187.6, (80.5 + ($i * 5.320)), $sTolerance);

		
				for ($j = 1; $j <= $iSamplesChecked; $j ++)
				{
					if ($sSizeFindings["{$j}-{$iPoint}"] != "" && strtolower($sSizeFindings["{$j}-{$iPoint}"]) != "ok" && $sSizeFindings["{$j}-{$iPoint}"] != "0")
					{
						$fMeaseuredValue  = ConvertToFloatValue($sSizeFindings["{$j}-{$iPoint}"]);
						$fSpecs           = ConvertToFloatValue($sSpecs);
						$fDifferenceValue = ($fMeaseuredValue + $fSpecs);
                                                $fTolerance       = parseTolerance($sTolerance);

                                                $fNTolerance       = $fTolerance[0];
                                                $fPTolerance       = $fTolerance[1];
                            
						$fPositiveTolerance = ($fSpecs + $fPTolerance);
						$fNegativeTolerance = ($fSpecs - $fNTolerance);

						if ($fMeaseuredValue >= $fNegativeTolerance && $fMeaseuredValue <= $fPositiveTolerance)
						{
							$objPdf->SetFillColor(255, 255, 255);
							$objPdf->SetXY((122 + ($j * 10.5)), (76.8 + ($i * 5.320)));
							$objPdf->Cell(9.1, 4.415, $sSizeFindings["{$j}-{$iPoint}"], 0, 0, "C", false);
						}
						
						else
						{							
							$objPdf->SetFillColor(255, 255, 0);
							$objPdf->SetXY((122 + ($j * 10.5)), (76.8 + ($i * 5.320)));
							$objPdf->Cell(9.1, 4.415, $sSizeFindings["{$j}-{$iPoint}"], 0, 0, "C", false);

							$iOut ++;
						}
					}

					//else
					//	$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (125.5 + ($j * 10.5)), (77.5 + ($i * 5.320)), 4);
				}
			}

			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(89, 225.2, ($iCount * $iSamplesChecked));
			$objPdf->Text(179, 225.2, $iOut);

			if ($sMeasurementResult == "P")
				$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 28, 240, 4);

			else if ($sMeasurementResult == "F")
				$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 77.5, 240, 4);

			else if ($sMeasurementResult == "H")
				$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 131, 240, 4);


			$objPdf->SetXY(27, 247);
			$objPdf->MultiCell(188, 4.8, $sMeasurementComments, 0);


			$objPdf->Text(54, 263, "{$sAuditor} / {$sUserType}");
			$objPdf->Text(130, 263, formatDate($sAuditDate));


			$iCurrentPage ++;
		}
	}        
        /////////////////////////////////////////Page #7 ///////////////////////////////////////////////
        $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/triburg.pdf");
	$iTemplateId = $objPdf->importPage(7, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 14.5, 19);


	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(179, 35.5, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 10, "Page {$iCurrentPage} of {$iTotalPages}");


        $objPdf->Text(135, 18, $sStyle);
	$objPdf->Text(135, 23.7, $sPo);
	$objPdf->Text(135, 29, $sAuditStage);
	$objPdf->Text(135, 34.5, formatDate($sAuditDate));

        $objPdf->SetFont('Arial', '', 8);
        $objPdf->Text(12, 58, $sPo);
        $objPdf->Text(50, 58, formatNumber($iTotalCartons, false));
        $objPdf->Text(95, 58, formatNumber($iInspectedCartons, false));

	// cartons Details
	$objPdf->SetFont('Arial', '', 6);

        $sSQL = "SELECT carton_no, result FROM tbl_qa_packaging_details where audit_id='$Id'";
        $objDb->query($sSQL);

        $iCount = $objDb->getCount( );
	
        $iTop   = 73;
        $iLeft  = 20;
        $iApproved = 0;
        $iRejected = 0;
        
        for($i = 0; $i < $iCount; $i ++)
        {
            $sResult    = $objDb->getField($i, 'result');
            $sCartonNo  = $objDb->getField($i, 'carton_no');
            
            if($i == 27)
            {
                $iTop   = 73;
                $iLeft  = 116;
            }
            
            if($i>54)
                break;
            if($sResult == 'P')
                $iApproved += 1;
            else
                $iRejected += 1;
        
            $objPdf->Text($iLeft-7, $iTop, $i+1);
            $objPdf->Text($iLeft, $iTop, $sCartonNo);
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sResult == "P") ? ($iLeft+62) : ($iLeft+76)), $iTop-2.5, 4);
            
            $iTop += 6.95;
        }
	
        $objPdf->Text(140, 58, formatNumber($iApproved, false));
        $objPdf->Text(180, 58, formatNumber($iRejected, false));
        
        $iCurrentPage++;
        /////////////////////////////////////////Page #8 ///////////////////////////////////////////////
        
        $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/triburg.pdf");
	$iTemplateId = $objPdf->importPage(8, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 14.5, 19);

	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(179, 35.5, "Audit Code: {$sAuditCode}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 10, "Page {$iCurrentPage} of {$iTotalPages}");

        $objPdf->Text(135, 18, $sStyle);
	$objPdf->Text(135, 23.7, $sPo);
	$objPdf->Text(135, 29, $sAuditStage);
	$objPdf->Text(135, 34.5, formatDate($sAuditDate));


	// cartons Details
	$objPdf->SetFont('Arial', '', 6);

        $sSQL = "SELECT sample_no,
                        (SELECT code FROM tbl_packaging_defects where id=pd.defect_code_id) as _Code,
                        (SELECT defect FROM tbl_packaging_defects where id=pd.defect_code_id) as _Defect                        
                    FROM tbl_qa_packaging_defects pd
                    WHERE pd.audit_id='$Id'";
        $objDb->query($sSQL);

        $iCount = $objDb->getCount( );
	
        $iTop   = 53.5;
        $iLeft  = 14;
        
        for($i = 0; $i < $iCount; $i ++)
        {            
            $sCode      = $objDb->getField($i, '_Code');
            $sDefect    = $objDb->getField($i, '_Defect');
            $iSampleNo  = $objDb->getField($i, 'sample_no');
            $sCartonNo  = getDbValue("carton_no", "tbl_qa_packaging_details", "sample_no='$iSampleNo' AND audit_id='$Id'");
            
            $objPdf->Text($iLeft, $iTop, $sCode);
            $objPdf->Text($iLeft+22, $iTop, $sDefect);
            $objPdf->Text($iLeft+162, $iTop, $sCartonNo);
            
            $iTop += 5.4;
        }
        
        $objPdf->Text($iLeft+162, 237, $iCount);
        $objPdf->Text($iLeft+162, 242, ($iCount>0?"Fail":"Pass"));
        
        $iCurrentPage++;
        /////////////////////////////////////////Page #9 ///////////////////////////////////////////////
        
        $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/triburg.pdf");
	$iTemplateId = $objPdf->importPage(9, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 14.5, 19);

	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(179, 35.5, "Audit Code: {$sAuditCode}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 10, "Page {$iCurrentPage} of {$iTotalPages}");

        $objPdf->Text(135, 18, $sStyle);
	$objPdf->Text(135, 23.7, $sPo);
	$objPdf->Text(135, 29, $sAuditStage);
	$objPdf->Text(135, 34.5, formatDate($sAuditDate));

        
        $objPdf->SetAlpha(0.3);
        $objPdf->SetFillColor(255, 255, 40);
        
        $iTopAqlList = array(5=>93.6, 13=>109.7,20=>117.8,32=>125.5,50=>133.5,80=>141.5,125=>149.7,200=>157.7,315=>165.5);
        $iAqlTop     = $iTopAqlList[$iTotalGmts];
        
        if($iAqlTop > 0)
        {
            $objPdf->Rect(10, $iAqlTop, 24, 7.3, 'DF');
            $objPdf->Rect(34, ($CheckLevel == 1?$iAqlTop:($iAqlTop+3.6)), 71, 4, 'DF');
            $objPdf->Rect(105, $iAqlTop, 95, 7.3, 'DF');
        }
            
        $iTopsList         = array(3=>215.5,5=>220,8=>224,13=>228.8,20=>233,32=>237.5,50=>242,80=>246.5,125=>251);
        $sCartonSampleSize = getCartonSampleSize($TotalCartons);
        
        if($sCartonSampleSize > 0)
            $objPdf->Rect(10, $iTopsList[$sCartonSampleSize], 190, 4, 'DF');
         
        $objPdf->SetAlpha(1);
	// Annexure1
	$objPdf->SetFont('Arial', '', 6);
        $iCurrentPage++;
         /////////////////////////////////////////Page #10 ///////////////////////////////////////////////
        
        $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/triburg.pdf");
	$iTemplateId = $objPdf->importPage(10, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 14.5, 19);


	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(179, 35.5, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 10, "Page {$iCurrentPage} of {$iTotalPages}");


        $objPdf->Text(135, 18, $sStyle);
	$objPdf->Text(135, 23.7, $sPo);
	$objPdf->Text(135, 29, $sAuditStage);
	$objPdf->Text(135, 34.5, formatDate($sAuditDate));


	// Annexure2
	$objPdf->SetFont('Arial', '', 6);
        $iCurrentPage++;
       /////////////////////////////////////////Page #11 ///////////////////////////////////////////////
        
        $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/triburg.pdf");
	$iTemplateId = $objPdf->importPage(11, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 14.5, 19);

	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(179, 35.5, "Audit Code: {$sAuditCode}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 10, "Page {$iCurrentPage} of {$iTotalPages}");

        $objPdf->Text(135, 18, $sStyle);
	$objPdf->Text(135, 23.7, $sPo);
	$objPdf->Text(135, 29, $sAuditStage);
	$objPdf->Text(135, 34.5, formatDate($sAuditDate));

	// Annexure2part
	$objPdf->SetFont('Arial', '', 6);
        $iCurrentPage++;
        /////////////////////////////////////////Page #12 ///////////////////////////////////////////////
        
        $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/triburg.pdf");
	$iTemplateId = $objPdf->importPage(12, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 14.5, 19);


	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(179, 35.5, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 10, "Page {$iCurrentPage} of {$iTotalPages}");


        $objPdf->Text(135, 18, $sStyle);
	$objPdf->Text(135, 23.7, $sPo);
	$objPdf->Text(135, 29, $sAuditStage);
	$objPdf->Text(135, 34.5, formatDate($sAuditDate));


	// Annexure3
	$objPdf->SetFont('Arial', '', 6);
        $iCurrentPage++;
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 
/*
        $iCurrentPage = 4;
	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/triburg.pdf");
	$iTemplateId = $objPdf->importPage(4, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 14.5, 19);


	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(179, 35.5, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 10, "Page {$iCurrentPage} of {$iTotalPages}");


        $objPdf->Text(135, 18, $sStyle);
	$objPdf->Text(135, 23.7, $sPo);
	$objPdf->Text(135, 29, $sAuditStage);
	$objPdf->Text(135, 34.5, formatDate($sAuditDate));

	// Report Details
	$objPdf->SetFont('Arial', '', 11);

	$objPdf->Text(140, 55, formatNumber($fAql, true, (($fAql < 1) ? 2 : 1)));
        
        // set alpha to semi-transparency
        $objPdf->SetAlpha(0.3);
        $objPdf->SetFillColor(255, 255, 40);
        
        if($iTotalGmts == 2){
            
            $objPdf->Rect(21, 80, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,84,4,'F');
            else
                $objPdf->Circle(174,84,4,'F');
            
        }
        else if($iTotalGmts == 3){
            
            $objPdf->Rect(21, 87.5, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,91.5,4,'F');
            else
                $objPdf->Circle(174,91.5,4,'F');
        }
        else if($iTotalGmts == 5){
            
            $objPdf->Rect(21, 95, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,99,4,'F');
            else
                $objPdf->Circle(174,99,4,'F');
        }
        else if($iTotalGmts == 8){
            
            $objPdf->Rect(21, 102.5, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,106.5,4,'F');
            else
                $objPdf->Circle(174,106.5,4,'F');
        }
        else if($iTotalGmts == 13){
            
            $objPdf->Rect(21, 110, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,114,4,'F');
            else
                $objPdf->Circle(174,114,4,'F');
        }
        else if($iTotalGmts == 20){
            
            $objPdf->Rect(21, 117.5, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,121.5,4,'F');
            else
                $objPdf->Circle(174,121.5,4,'F');
        }
        else if($iTotalGmts == 32){
            
            $objPdf->Rect(21, 125, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,129,4,'F');
            else
                $objPdf->Circle(174,129,4,'F');
        }
        else if($iTotalGmts == 50){
            
            $objPdf->Rect(21, 132.5, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,136.5,4,'F');
            else
                $objPdf->Circle(174,136.5,4,'F');
        }
        else if($iTotalGmts == 80){
            
            $objPdf->Rect(21, 140, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,144,4,'F');
            else
                $objPdf->Circle(174,144,4,'F');
        }
        else if($iTotalGmts == 125){
            
            $objPdf->Rect(21, 147.5, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,151.5,4,'F');
            else
                $objPdf->Circle(174,151.5,4,'F');
        }
        else if($iTotalGmts == 200){
            
            $objPdf->Rect(21, 155, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,159,4,'F');
            else
                $objPdf->Circle(174,159,4,'F');
        }
        else if($iTotalGmts == 315){
            
            $objPdf->Rect(21, 162.5, 165, 7.5, 'DF');  
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,166.5,4,'F');
            else
                $objPdf->Circle(174,166.5,4,'F');
        }
        else if($iTotalGmts == 500){
            
            $objPdf->Rect(21, 170, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,174,4,'F');
            else
                $objPdf->Circle(174,174,4,'F');
        }
        else if($iTotalGmts == 800){
            
            $objPdf->Rect(21, 177.5, 165, 7.5, 'DF');  
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,181.5,4,'F');
            else
                $objPdf->Circle(174,181.5,4,'F');
        }
        else if($iTotalGmts == 1250){
            
            $objPdf->Rect(21, 185, 165, 7.6, 'DF');  
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,189,4,'F');
            else
                $objPdf->Circle(174,189,4,'F');
        }

        // restore full opacity
        $objPdf->SetAlpha(1);
        $objPdf->SetTextColor(50, 50, 50);

	$iCurrentPage ++;*/
    
	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 13  -  DEFECT IMAGES

	if (count($sDefects) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/triburg.pdf");
		$iTemplateId = $objPdf->importPage(13, '/MediaBox');


		$iPages = @ceil(count($sDefects) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			// QR Code
                        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 14.5, 19);


                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(179, 35.5, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(10.3, 10, "Page {$iCurrentPage} of {$iTotalPages}");


                        $objPdf->Text(135, 18, $sStyle);
                        $objPdf->Text(135, 23.7, $sPo);
                        $objPdf->Text(135, 29, $sAuditStage);
                        $objPdf->Text(135, 34.5, formatDate($sAuditDate));


			$objPdf->SetFont('Arial', 'B', 12);
			$objPdf->Text(11, 49.5, "Defect Images");

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


				$iLeft = 16;
				$iTop  = 59;

				if ($j == 1 || $j == 3)
					$iLeft = 113;

				if ($j == 2 || $j == 3)
					$iTop = 156.5;


				$sInfo  = "Type: {$sType}\n";
				$sInfo .= "Defect: {$sDefect}\n";
				$sInfo .= ("Area: ".getDbValue("area", "tbl_defect_areas", "id='$sAreaCode'")."\n");

				$objPdf->SetXY($iLeft, ($iTop + 78.5));
				$objPdf->MultiCell(98, 3.6, $sInfo, 0, "L", false);


				$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sDefects[$iIndex]), $iLeft, $iTop, 78, 72);
			}
		}
	}
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 14  -  PACKAGING DEFECT IMAGES
// //
	if (count($sPackagingImages) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/triburg.pdf");
		$iTemplateId = $objPdf->importPage(13, '/MediaBox');


		$iPages = @ceil(count($sPackagingImages) / 4);
		$iIndex = 0;
                
                $sPackImagesList = array();
                $sPackImagesCodesList = array();
                $sDefectCodesList = getList("tbl_packaging_defects", "id", "CONCAT(code,' - ',defect)");
                
                foreach($sPackagingImages as $iKey => $sValue)
                {
                    $sValue     = explode("~", $sValue);
                    $iCodeId    = $sValue[0];
                    $sImage     = $sValue[1];
                    
                    $sPackImagesList[] = $sImage;
                    $sPackImagesCodesList[] = $sDefectCodesList[$iCodeId];
                }

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			// QR Code
                        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 14.5, 19);


                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(179, 35.5, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(10.3, 10, "Page {$iCurrentPage} of {$iTotalPages}");


                        $objPdf->Text(135, 18, $sStyle);
                        $objPdf->Text(135, 23.7, $sPo);
                        $objPdf->Text(135, 29, $sAuditStage);
                        $objPdf->Text(135, 34.5, formatDate($sAuditDate));


			$objPdf->SetFont('Arial', 'B', 12);
			$objPdf->Text(11, 49.5, "Packaging Images");

			$objPdf->SetFont('Arial', '', 7);

			for ($j = 0; $j < 4 && $iIndex < count($sPackImagesList); $j ++, $iIndex ++)
			{
				$sName   = @strtoupper($sPackImagesList[$iIndex]);
				$sDefect = @strtoupper($sPackImagesCodesList[$iIndex]);
				
				$iLeft = 16;
				$iTop  = 59;

				if ($j == 1 || $j == 3)
					$iLeft = 113;

				if ($j == 2 || $j == 3)
					$iTop = 156.5;


				$sInfo = "{$sDefect}\n";
				
				$objPdf->SetXY($iLeft, ($iTop + 78.5));
				$objPdf->MultiCell(98, 3.6, $sInfo, 0, "L", false);


				$objPdf->Image($sBaseDir.PACKAGING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sName), $iLeft, $iTop, 78, 72);
			}
		}
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 15  -  PACKING IMAGES

	if (count($sPackings) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/triburg.pdf");
		$iTemplateId = $objPdf->importPage(14, '/MediaBox');

		$iPages = @ceil(count($sPackings) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			// QR Code
                        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 14.5, 19);


                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(179, 35.5, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(10.3, 10, "Page {$iCurrentPage} of {$iTotalPages}");


                        $objPdf->Text(135, 18, $sStyle);
                        $objPdf->Text(135, 23.7, $sPo);
                        $objPdf->Text(135, 29, $sAuditStage);
                        $objPdf->Text(135, 34.5, formatDate($sAuditDate));

			$objPdf->SetFont('Arial', 'B', 12);
			$objPdf->Text(11, 49.2, "Packing Images");



			for ($j = 0; $j < 4 && $iIndex < count($sPackings); $j ++, $iIndex ++)
			{
				$iLeft = 5;
				$iTop  = 55;

				if ($j == 1 || $j == 3)
					$iLeft = 107;

				if ($j == 2 || $j == 3)
					$iTop = 158;


				$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPackings[$iIndex]), $iLeft, $iTop, 98, 98);
			}
		}
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 16  -  SPECS SHEETS

	if (count($sSpecsSheets) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/triburg.pdf");
		$iTemplateId = $objPdf->importPage(14, '/MediaBox');


		for ($i = 0; $i < count($sSpecsSheets); $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


                        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 14.5, 19);


                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(179, 35.5, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(10.3, 10, "Page {$iCurrentPage} of {$iTotalPages}");


                        $objPdf->Text(135, 18, $sStyle);
                        $objPdf->Text(135, 23.7, $sPo);
                        $objPdf->Text(135, 29, $sAuditStage);
                        $objPdf->Text(135, 34.5, formatDate($sAuditDate));

			$objPdf->SetFont('Arial', 'B', 12);
			$objPdf->Text(11, 49.2, "Lab Reports / Specs Sheets");

			$objPdf->Image($sSpecsSheets[$i], 10, 55, 190);
		}
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 17  -  MISC IMAGES
	if (count($sMisc) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/triburg.pdf");
		$iTemplateId = $objPdf->importPage(14, '/MediaBox');


		$iPages = @ceil(count($sMisc) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			// QR Code
                        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 14.5, 19);


                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(179, 35.5, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(10.3, 10, "Page {$iCurrentPage} of {$iTotalPages}");

			$objPdf->SetFont('Arial', '', 9);
                        $objPdf->Text(135, 18, $sStyle);
                        $objPdf->Text(135, 23.7, $sPo);
                        $objPdf->Text(135, 29, $sAuditStage);
                        $objPdf->Text(135, 34.5, formatDate($sAuditDate));

			$objPdf->SetFont('Arial', 'B', 12);
			$objPdf->Text(11, 38, "Miscellaneous Images");


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