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
     //ini_set('display_errors', 1);
     //error_reporting(E_ALL);
        
        @require_once($sBaseDir."requires/tcpdf/tcpdf.php");
	@require_once($sBaseDir."requires/fpdi2/fpdi.php");
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
				
		return @number_format(($num1 + $num2),3);
	}

	
	$sSQL = "SELECT *,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
                        (SELECT parent from tbl_factories WHERE FIND_IN_SET(tbl_qa_reports.vendor_id, vendors) LIMIT 1) as _Parent,
                        (SELECT signature from tbl_users where id=tbl_qa_reports.user_id) as _Signature,
	                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor,
                        (SELECT user_type FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _UserType
	         FROM tbl_qa_reports
	         WHERE id='$Id'";
        
	$objDb->query($sSQL);

	$iReportId          = $objDb->getField(0, "report_id");
	$iVendor            = $objDb->getField(0, "vendor_id");
	$sVendor            = $objDb->getField(0, "_Vendor");
        $sParent            = $objDb->getField(0, "_Parent");
	$sAuditor           = $objDb->getField(0, "_Auditor");
        $sUserType          = $objDb->getField(0, "_UserType");
	$iPo                = $objDb->getField(0, "po_id");
	$iAdditionalPos     = $objDb->getField(0, "additional_pos");
	$sPo                = $objDb->getField(0, "_Po");
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
        $sMapPicture        = $objDb->getField(0, "map_image");
        
	$sSpecsSheets = array( );
        
        @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
        
	for ($i = 1; $i <= 10; $i ++)
	{
		$sSpecsSheet = $objDb->getField(0, "specs_sheet_{$i}");

		if ($sSpecsSheet != "")
		{
			if (@file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet))
				$sSpecsSheets[$sSpecsSheet] = ($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet);
			
			else if (@file_exists($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsSheet))
				$sSpecsSheets[$sSpecsSheet] = ($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsSheet);
		}
	}
        
        $sSQL = "SELECT vendor, address, manager_rep, manager_rep_email, rep_picture, phone, fax, latitude, longitude
         FROM tbl_vendors
         WHERE id='$iVendor'";
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

        @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
        
        $sDefects = array( );
	$sPacking = array( );
	$sMisc    = array( );
        $sLabs    = array( );
        
        $sFront   = array( );
        $sBack    = array( );
        $sColorWay= array( );
        
        
        $sDefectImages  = getList("tbl_qa_report_defects", "id", "picture", "audit_id='$Id' AND picture LIKE '%.jpg'");// defect images
	$sPackingImages = getList("tbl_qa_report_images", "id", "image", "audit_id='$Id' AND `type`='P' AND image LIKE '%.jpg'");//packing	
	$sLabImages     = getList("tbl_qa_report_images", "id", "image", "audit_id='$Id' AND `type`='L' AND image LIKE '%.jpg'");//lab
        $sFrontImages   = getList("tbl_qa_report_images", "id", "image", "audit_id='$Id' AND `type`='PFV' AND image LIKE '%.jpg'"); //front
        $sBackImages    = getList("tbl_qa_report_images", "id", "image", "audit_id='$Id' AND `type`='PBV' AND image LIKE '%.jpg'"); //back
        $sColorWayImages= getList("tbl_qa_report_images", "id", "image", "audit_id='$Id' AND `type`='CW'  AND image LIKE '%.jpg'"); //colorway
        $sMiscImages    = getList("tbl_qa_report_images", "id", "image", "audit_id='$Id' AND `type`='M' AND image LIKE '%.jpg'");//misc
        
        foreach ($sDefectImages as $iDefectId => $sImage)
		if (@file_exists($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
			$sDefects[]    = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);
	
	foreach ($sPackingImages as $sImage)
		if (@file_exists($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
			$sPacking[]     = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);

        foreach ($sFrontImages as $sImage)
		if (@file_exists($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
			$sFront[]       = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);
         
        foreach ($sBackImages as $sImage)
		if (@file_exists($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
			$sBack[]        = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);
                
        foreach ($sColorWayImages as $sImage)
		if (@file_exists($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
			$sColorWay[]    = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);        
                
        foreach ($sMiscImages as $sImage)
		if (@file_exists($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
			$sMisc[]        = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);
                
        foreach ($sLabImages as $sImage)
		if (@file_exists($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
			$sSpecsSheets[$sImage]   = ($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);
        
        $sSpecsSheets = explode(",", implode(",", $sSpecsSheets));        
        $sSpecsSheets = ($sSpecsSheets[0] == ""?array():$sSpecsSheets);    
        
	$iTotalPages  = 6;
	$iTotalPages += getDbValue("COUNT(DISTINCT(CONCAT(size_id, '-', color)))", "tbl_qa_report_samples", "audit_id='$Id'");
	$iTotalPages += count($sSpecsSheets);
	$iTotalPages += @ceil(count($sPacking) / 4);
	$iTotalPages += @ceil(count($sDefects) / 4);
	$iTotalPages += @ceil(count($sFront) / 4);
        $iTotalPages += @ceil(count($sBack) / 4);
        $iTotalPages += @ceil(count($sColorWay) / 4);
        $iTotalPages += @ceil(count($sMisc) / 4);


	/////////////////////////////////////////////////////page1///////////////////////////////////////////////////////////////


	$objPdf = new AlphaPDF( );

        $objPdf->setPrintHeader(false);
	$objPdf->setPrintFooter(false);
	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/jcrew.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 12, 19);


	$objPdf->SetFont('helvetica', '', 6);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(178.5, 31, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('helvetica', '', 9);
	$objPdf->Text(11, 10, "Page 1 of {$iTotalPages}");


        $objPdf->Text(135, 12.7, $sStyle);
	$objPdf->Text(135, 18, $sPo);
	$objPdf->Text(135, 23.5, $sAuditStage);
	$objPdf->Text(135, 29, formatDate($sAuditDate));


	$sSQL = "SELECT * FROM tbl_arcadia_inspection_summary WHERE audit_id='$Id'";
	$objDb->query($sSQL);
        
        $sProductStyle                   = $objDb->getField(0, "style");
        $sProductStyleRemarks            = $objDb->getField(0, "style_remarks");
        $sProductColour                  = $objDb->getField(0, "colour");
        $sProductColourRemarks           = $objDb->getField(0, "colour_remarks");
        $sAssortment                     = $objDb->getField(0, "assortment");
        $sAssortmentRemarks              = $objDb->getField(0, "assortment_remarks");
        $sFabricGauge                    = $objDb->getField(0, "fabric_weight");
        $sFabricGaugeRemarks             = $objDb->getField(0, "fabric_weight_remarks");
        $sLining                         = $objDb->getField(0, "lining");
        $sLiningRemarks                  = $objDb->getField(0, "lining_remarks");
        $sLabeling                       = $objDb->getField(0, "labeling_main");
        $sLabelingRemarks                = $objDb->getField(0, "labeling_main_remarks");
        $sLabelingOther                  = $objDb->getField(0, "labeling_others");
        $sLabelingOtherRemarks           = $objDb->getField(0, "labeling_others_remarks");
        $sHangTag                        = $objDb->getField(0, "hangtag_others");
        $sHangTagRemarks                 = $objDb->getField(0, "hangtag_others_remarks");
        $sPriceTicket                    = $objDb->getField(0, "price_ticket");
        $sPriceTicketRemarks             = $objDb->getField(0, "price_ticket_remarks");
        $sExportCartonDimension          = $objDb->getField(0, "export_carton_packing");
        $sExportCartonDimensionRemarks   = $objDb->getField(0, "export_carton_packing_remarks");
        $sAsnLabel                       = $objDb->getField(0, "ans_label");
        $sAsnLabelRemarks                = $objDb->getField(0, "ans_label_remarks");
        $sPackaging                      = $objDb->getField(0, "product_packaging");
        $sPackagingRemarks               = $objDb->getField(0, "product_packaging_remarks");
        $sInnerCartonAppearance          = $objDb->getField(0, "appearance");
        $sInnerCartonAppearanceRemarks   = $objDb->getField(0, "appearance_remarks");
        $sPolybagQuality                 = $objDb->getField(0, "polybag_quality_size");
        $sPolybagQualityRemarks          = $objDb->getField(0, "polybag_quality_size_remarks");
        $sPolybagSticker                 = $objDb->getField(0, "polybag_sticker");
        $sPolybagStickerRemarks          = $objDb->getField(0, "polybag_sticker_remarks");
        $sHanger                         = $objDb->getField(0, "hanger");
        $sHangerRemarks                  = $objDb->getField(0, "hanger_remarks");
        $sEmbroidery                     = $objDb->getField(0, "embroidery");
        $sEmbroideryRemarks              = $objDb->getField(0, "embroidery_remarks");
        $sButtoning                      = $objDb->getField(0, "buttoning");
        $sButtoningRemarks               = $objDb->getField(0, "buttoning_remarks");
        $sWashEffect                     = $objDb->getField(0, "wash_effect");
        $sWashEffectRemarks              = $objDb->getField(0, "wash_effect_remarks");
        $sFitDummy                       = $objDb->getField(0, "dummy_fit");
        $sFitDummyRemarks                = $objDb->getField(0, "dummy_fit_remarks");
        $sPullTesting                    = $objDb->getField(0, "product_safety");
        $sPullTestingRemarks             = $objDb->getField(0, "product_safety_remarks");
        $sRemarks1                       = $objDb->getField(0, "remarks_1");
        $sRemarks2                       = $objDb->getField(0, "remarks_2");
        $sRemarks3                       = $objDb->getField(0, "remarks_3");
        $sRemarks4                       = $objDb->getField(0, "remarks_4");
        $sCartonNos                      = $objDb->getField(0, "carton_nos");
        $sMeasurementResult              = $objDb->getField(0, "measurement_result");
        $sMeasurementComments            = $objDb->getField(0, "measurement_overall_remarks");

	// Report Details
	$objPdf->SetFont('helvetica', '', 8);

        $sAllPos = $sPo.$sAdditionalPos;
        
        if(strlen($sAllPos) > 30)
        {
            $objPdf->SetFont('helvetica', '', 6);
            $objPdf->SetXY(56, 44.5);
            $objPdf->setCellHeightRatio(1.0);
            $objPdf->MultiCell(40, 2.5, $sAllPos, 0, "L");
            
            $objPdf->SetFont('helvetica', '', 8);
        }
        else
            $objPdf->Text(58, 45.3, ($sAllPos));

        if(strlen($sParent) > 25)
        {
            $objPdf->SetFont('helvetica', '', 6);
            
            $objPdf->SetXY(56, 50.5);
            $objPdf->setCellHeightRatio(1.0);
            $objPdf->MultiCell(40, 2.5, mb_convert_encoding($sParent, 'ISO-8859-1', 'UTF-8'), 0, "L");
            
            $objPdf->SetFont('helvetica', '', 8);
        }
        else
            $objPdf->Text(58, 51.5, mb_convert_encoding($sParent, 'ISO-8859-1', 'UTF-8'));
        
        
        $objPdf->Text(58, 57.5, formatNumber($iQuantity, false));
        $objPdf->Text(58, 63.5, formatNumber($iShipQty, false));
        
        if(strlen($sDescription) > 45)
        {
            $objPdf->SetFont('helvetica', '', 5);
            
            $objPdf->SetXY(136, 45);
            $objPdf->setCellHeightRatio(1.0);
            $objPdf->MultiCell(60, 2.5, $sDescription, 0, "L");
            
            $objPdf->SetFont('helvetica', '', 8);
        }else
            $objPdf->Text(138, 45.3, $sDescription);
        
        $objPdf->Text(138, 51.5, $sAuditor);
        $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sApprovedSample == "Yes" || $sApprovedSample == "Y") ? 157 : 188), 57, 4);
        $objPdf->Text(80, 88, $iTotalGmts);
        
        $objPdf->Text(60, 102.5, $Factory);
        $objPdf->Text(60, 108, $FactoryAddress);
        $objPdf->Text(60, 113, $iVendor);
        $objPdf->Text(60, 118, $sAuditDate);
       
        if ($sFactoryLatitude != "" && $sFactoryLongitude != "" && $sLatitude != "" && $sLongitude != "")
        {
            $sDistance = calculateDistance($sFactoryLatitude, $sFactoryLongitude, $sLatitude, $sLongitude);
            $objPdf->Text(31, 141.7, $sDistance);            
        }
        
        if ($sLatitude != "" && $sLongitude != "")
	{
		$objPdf->SetFont('helvetica', '', 7);
		$objPdf->SetTextColor(6, 82, 195);

                if($sFactoryLatitude != "" && $sFactoryLongitude != "")
                {
                    $objPdf->SetXY(99, 124.8);
                    $objPdf->Write(5, "(". formatNumber($sFactoryLatitude, true, 8).",". formatNumber($sFactoryLongitude, true, 8).")", "http://maps.google.com/maps?q={$sFactoryLatitude},{$sFactoryLongitude}&z=12");
                }
                
                $objPdf->SetXY(99, 130.8);
		$objPdf->Write(5, "(". formatNumber($sLatitude, true, 8).",". formatNumber($sLongitude, true, 8).")", "http://maps.google.com/maps?q={$sLatitude},{$sLongitude}&z=12&key=".GOOGLE_MAPS_KEY);
                
                $objPdf->SetFont('helvetica', '', 7);
                $objPdf->SetTextColor(50, 50, 50);
              
                if($sMapPicture != "" && @file_exists($sBaseDir.'files/maps/'.$sMapPicture))
                    $objPdf->Image($sBaseDir."files/maps/".$sMapPicture, 68, 143, 120,68);
                else
                {
                    if($sFactoryLatitude != "" && $sFactoryLongitude != "")
                        $map = file_get_contents("https://maps.googleapis.com/maps/api/staticmap?center=".$sLatitude.",".$sLongitude."&zoom=11&size=1000x450&markers=color:yellow|".$sLatitude.",".$sLongitude."&markers=color:red|".$sFactoryLatitude.",".$sFactoryLongitude."&key=".GOOGLE_MAPS_KEY); 
                    else
                        $map = file_get_contents("https://maps.googleapis.com/maps/api/staticmap?center=".$sLatitude.",".$sLongitude."&zoom=13&size=1000x450&markers=color:yellow|".$sLatitude.",".$sLongitude."&key=".GOOGLE_MAPS_KEY); 
                        

                    $image = imagecreatefromstring($map);
                    $saved = imagejpeg($image, $sBaseDir."files/maps/googlemapImage-{$sAuditCode}.jpg");
                    unset($map);
                    
                    $objPdf->Image($sBaseDir."files/maps/googlemapImage-{$sAuditCode}.jpg", 68, 143, 120,68);
                    
                    $sSQL = "UPDATE tbl_qa_reports SET map_image='"."googlemapImage-{$sAuditCode}.jpg"."' WHERE id='$Id'";
                    $objDb->query($sSQL);  
                }
	}
        else if($sFactoryLatitude != "" && $sFactoryLongitude != "")
        {
                $objPdf->SetFont('helvetica', '', 7);
		$objPdf->SetTextColor(6, 79, 195);
                
                $objPdf->SetXY(99, 124.5);
                $objPdf->Write(5, "(". formatNumber($sFactoryLatitude, true, 8).",". formatNumber($sFactoryLongitude, true, 8).")", "http://maps.google.com/maps?q={$sFactoryLatitude},{$sFactoryLongitude}&z=12");

                $objPdf->SetFont('helvetica', '', 7);
                $objPdf->SetTextColor(50, 50, 50);
                
                $objPdf->Text(99, 132, "Audit location/cordinates are un-available.");
                
                if($sMapPicture != "" && @file_exists($sBaseDir.'files/maps/'.$sMapPicture))
                    $objPdf->Image($sBaseDir."files/maps/".$sMapPicture, 68, 143, 120,68);
                else
                {
                    $map = file_get_contents("https://maps.googleapis.com/maps/api/staticmap?center=".$sFactoryLatitude.",".$sFactoryLongitude."&zoom=13&size=1000x450&markers=color:red|".$sFactoryLatitude.",".$sFactoryLongitude."&key=".GOOGLE_MAPS_KEY); 
                    $image = imagecreatefromstring($map);
                    $saved = imagejpeg($image, $sBaseDir."files/maps/googlemapImage-{$sAuditCode}.jpg");
                    unset($map);

                    $objPdf->Image($sBaseDir."files/maps/googlemapImage-{$sAuditCode}.jpg", 68, 143, 120,68);
                    
                    $sSQL = "UPDATE tbl_qa_reports SET map_image='"."googlemapImage-{$sAuditCode}.jpg"."' WHERE id='$Id'";
                    $objDb->query($sSQL);
                }
        }
	else
            $objPdf->Text(99, 132, getDbValue("city", "tbl_vendors", "id='$iVendor'"));
        
        $objPdf->Text(36, 228.5, $FactoryRep);
        $objPdf->Text(142, 232, $FactoryRepEmail);
        $objPdf->Text(142, 240, $FactoryPhone);
        $objPdf->Text(142, 248, $FactoryFax);

        if ($sSignature != "" && @file_exists($sBaseDir.USER_SIGNATURES_IMG_DIR.$sSignature))
            $objPdf->Image($sBaseDir.USER_SIGNATURES_IMG_DIR.$sSignature, 32, 234, 23, 17);
        
        if($FactoryRepPic == "")
            $FactoryRepPic = "default.jpg";
        
        if (@file_exists($sBaseDir.'files/representative/'.$FactoryRepPic))
            $objPdf->Image($sBaseDir.'files/representative/'.$FactoryRepPic, 78, 222.5, 32, 30);
        
	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 2

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/jcrew-new.pdf");
	$iTemplateId = $objPdf->importPage(2, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 12, 19);

	$objPdf->SetFont('helvetica', '', 6);
	$objPdf->SetTextColor(50, 50, 50);
	$objPdf->Text(178.5, 31, "Audit Code: {$sAuditCode}");

	$objPdf->SetFont('helvetica', '', 9);
	$objPdf->Text(11, 10, "Page 2 of {$iTotalPages}");

	$objPdf->Text(135, 12.7, $sStyle);
	$objPdf->Text(135, 18, $sPo);
	$objPdf->Text(135, 23.5, $sAuditStage);
	$objPdf->Text(135, 29, formatDate($sAuditDate));

	// Quantity Details
	$objPdf->SetFont('helvetica', '', 6);
        
        $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sAuditResult == "P") ? 17.5 : 49), 46, 5);
        
        if ($sProductStyle != "" && @in_array($sProductStyle, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sProductStyle == "P") ? 109 : 122), 63, 4);
        $objPdf->SetXY(130, 63);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sProductStyleRemarks, 0, "L");
	
	if ($sProductColour != "" && @in_array($sProductColour, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sProductColour == "P") ? 109 : 122), 68, 4);
        $objPdf->SetXY(130, 68);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sProductColourRemarks, 0, "L");

	if ($sAssortment != "" && @in_array($sAssortment, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sAssortment == "P") ? 109 : ($sAssortment == "F"? 122 : 126)), 73, 4);
        $objPdf->SetXY(130, 73);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sAssortmentRemarks, 0, "L");

	if ($sFabricGauge != "" && @in_array($sFabricGauge, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sFabricGauge == "P") ? 109 : 122), 78, 4);
        $objPdf->SetXY(130, 78);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sFabricGaugeRemarks, 0, "L");
        
        if ($sLining != "" && @in_array($sLining, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sLining == "P") ? 109 : 122), 83, 4);
        $objPdf->SetXY(130, 83);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, str_replace("’","'",$sLiningRemarks), 0, "L");

	if ($sLabeling != "" && @in_array($sLabeling, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sLabeling == "P") ? 109 : 122), 88, 4);
        $objPdf->SetXY(130, 88);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sLabelingRemarks, 0, "L");


	if ($sLabelingOther != "" && @in_array($sLabelingOther, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sLabelingOther == "P") ? 109 : 122), 93, 4);
        $objPdf->SetXY(130, 93);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sLabelingOtherRemarks, 0, "L");

	if ($sHangTag != "" && @in_array($sHangTag, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sHangTag == "P") ? 109 : 122), 98, 4);
        $objPdf->SetXY(130, 98);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sHangTagRemarks, 0, "L");

	if ($sPriceTicket != "" && @in_array($sPriceTicket, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sPriceTicket == "P") ? 109 : 122), 103, 4);
        $objPdf->SetXY(130, 103);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sPriceTicketRemarks, 0, "L");

	if ($sExportCartonDimension != "" && @in_array($sExportCartonDimension, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sExportCartonDimension == "P") ? 109 : 122), 108, 4);
        $objPdf->SetXY(130, 108);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sExportCartonDimensionRemarks, 0, "L");
	
	if ($sAsnLabel != "" && @in_array($sAsnLabel, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sAsnLabel == "P") ? 109 : 122), 113, 4);
        $objPdf->SetXY(130, 113);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sAsnLabelRemarks, 0, "L");

	if ($sPackaging != "" && @in_array($sPackaging, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sPackaging == "P") ? 109 : 122), 118, 4);
        $objPdf->SetXY(130, 118);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sPackagingRemarks, 0, "L");
        
	if ($sInnerCartonAppearance != "" && @in_array($sInnerCartonAppearance, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sInnerCartonAppearance == "P") ? 109 : 122), 123, 4);
        $objPdf->SetXY(130, 123);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sInnerCartonAppearanceRemarks, 0, "L");

	if ($sPolybagQuality != "" && @in_array($sPolybagQuality, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sPolybagQuality == "P") ? 109 : 122), 128, 4);
        $objPdf->SetXY(130, 128);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sPolybagQualityRemarks, 0, "L");
        
	if ($sPolybagSticker != "" && @in_array($sPolybagSticker, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sPolybagSticker == "P") ? 109 : 122), 133, 4);
        $objPdf->SetXY(130, 133);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sPolybagStickerRemarks, 0, "L");

	if ($sHanger != "" && @in_array($sHanger, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sHanger == "P") ? 109 : 122), 138, 4);
        $objPdf->SetXY(130, 138);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sHangerRemarks, 0, "L");

        if ($sEmbroidery != "" && @in_array($sEmbroidery, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sEmbroidery == "P") ? 109 : 122), 143, 4);
        $objPdf->SetXY(130, 143);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sEmbroideryRemarks, 0, "L");


	if ($sButtoning != "" && @in_array($sButtoning, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sButtoning == "P") ? 109 : 122), 148, 4);
        $objPdf->SetXY(130, 148);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sButtoningRemarks, 0, "L");


	if ($sWashEffect != "" && @in_array($sWashEffect, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sWashEffect == "P") ? 109 : 122), 154, 4);
        $objPdf->SetXY(130, 154);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sWashEffectRemarks, 0, "L");

	if ($sFitDummy != "" && @in_array($sFitDummy, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sFitDummy == "P") ? 109 : 122), 159, 4);
        $objPdf->SetXY(130, 159);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sFitDummyRemarks, 0, "L");
        
        if ($sPullTesting != "" && @in_array($sPullTesting, array('P','F')))
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sPullTesting == "P") ? 109 : 122), 164, 4);
        $objPdf->SetXY(130, 164);
        $objPdf->setCellHeightRatio(1.0);
        $objPdf->MultiCell(70, 2.5, $sPullTestingRemarks, 0, "L");


        $objPdf->SetFont('stsongstdlight', '', 8);
        
	$objPdf->Text(12, 180, $sRemarks1);
	$objPdf->Text(12, 185, $sRemarks2);
	$objPdf->Text(12, 190, $sRemarks3);
	$objPdf->Text(12, 195, $sRemarks4);
        
        /////////////////////////////////////////Page #3 ///////////////////////////////////////////////
        $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/jcrew.pdf");
	$iTemplateId = $objPdf->importPage(3, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 12, 19);


	$objPdf->SetFont('helvetica', '', 6);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(178.5, 31, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('helvetica', '', 9);
	$objPdf->Text(11, 10, "Page 3 of {$iTotalPages}");


	$objPdf->Text(135, 12.7, $sStyle);
	$objPdf->Text(135, 18, $sPo);
	$objPdf->Text(135, 23.5, $sAuditStage);
	$objPdf->Text(135, 29, formatDate($sAuditDate));


	// Quantity Details
	$objPdf->SetFont('helvetica', '', 6);

	if ($sCartonNos != "")
	{
		$sCartonNos = @explode(",", $sCartonNos);

		for ($i = 1, $iIndex = 0; $i <= 7 && $iIndex < count($sCartonNos); $i ++)
		{
			for ($j = 1; $j <= 6 && $iIndex < count($sCartonNos); $j ++, $iIndex ++)
				$objPdf->Text((($j * 31) - 17), (46 + ($i * 5)), $sCartonNos[$iIndex]);
		}

		$objPdf->Text(80, 85.5, count($sCartonNos));
	}

        $objPdf->SetFont('helvetica', '', 6);
        //Defects Display
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
	$fTop   = 102;
        
        $TotalMinor    = 0;
        $TotalMajor    = 0;
        $TotalCritical = 0;

        for($i = 0; $i < $iCount; $i ++)
	{
            $fTop += 4.90;
            
            $iCritical = $objDb->getField($i, "_Critical");
            $iMajor    = $objDb->getField($i, "_Major");
            $iMinor    = $objDb->getField($i, "_Minor");
            
            $TotalMinor    += $iMinor;
            $TotalMajor    += $iMajor;
            $TotalCritical += $iCritical;
        
            if($i <= 17)
            {  
            	$sSQL2 = ("SELECT defect, (SELECT type_code from tbl_defect_types where tbl_defect_codes.type_id=tbl_defect_types.id) as _Code FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
		$objDb2->query($sSQL2);

                $sDefect     = $objDb2->getField(0, 0);		
		$sDefectTypeCode = $objDb2->getField(0, 1);
            
                $objPdf->SetXY(11, ($fTop - 1.0));
                $objPdf->setCellHeightRatio(1.0);
                $objPdf->MultiCell(140, 1, ($sDefectTypeCode!=""?$sDefectTypeCode.'- ':'').$sDefect, 0, "L");
                
                $objPdf->Text(146, $fTop, $iCritical);
		$objPdf->Text(167, $fTop, $iMajor);
                $objPdf->Text(188, $fTop, $iMinor);
                
            }
        }

        $objPdf->Text(146, 229, $TotalCritical);
	$objPdf->Text(167, 229, $TotalMajor);
        $objPdf->Text(188, 229, $TotalMinor);
        
        $objPdf->Text(146, 234, "0");
	$objPdf->Text(167, 234, $iAqlChart[$iTotalGmts]["2.5"]);
	$objPdf->Text(188, 234, $iAqlChart[$iTotalGmts]["4"]);

      	$objPdf->SetFont('helvetica', 'B', 9);
        if($sAuditResult == "P")
            $objPdf->SetTextColor(0, 100, 0);
        else
            $objPdf->SetTextColor(100, 0, 0);
	$objPdf->Text(146, 239, (($sAuditResult == "P") ? "PASS" : "FAIL"));
        
        $objPdf->SetFont('stsongstdlight', '', 7);
	$objPdf->SetTextColor(50, 50, 50);
        
        $objPdf->SetXY(12, 248.5);
        $objPdf->setCellHeightRatio(1.5);
        $objPdf->MultiCell(188, 3.5, $sComments, 0, "L");
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 4

        $iCurrentPage = 4;
	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/jcrew.pdf");
	$iTemplateId = $objPdf->importPage(4, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 12, 19);


	$objPdf->SetFont('helvetica', '', 6);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(178.5, 31, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('helvetica', '', 9);
	$objPdf->Text(11, 10, "Page {$iCurrentPage} of {$iTotalPages}");


	$objPdf->Text(135, 12.7, $sStyle);
	$objPdf->Text(135, 18, $sPo);
	$objPdf->Text(135, 23.5, $sAuditStage);
	$objPdf->Text(135, 29, formatDate($sAuditDate));

	// Report Details
	$objPdf->SetFont('helvetica', '', 11);

	$objPdf->Text(140, 51, formatNumber($fAql, true, (($fAql < 1) ? 2 : 1)));
        
        // set alpha to semi-transparency
        $objPdf->SetAlpha(0.3);
        $objPdf->SetFillColor(255, 255, 40);
        
        if($iTotalGmts == 2){
            
            $objPdf->Rect(21, 79.5, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,83.5,4,'F');
            else
                $objPdf->Circle(174,83.5,4,'F');
        }
        else if($iTotalGmts > 2 && $iTotalGmts <= 3){
            
            $objPdf->Rect(21, 87, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,91,4,'F');
            else
                $objPdf->Circle(174,91,4,'F');
        }
        else if($iTotalGmts > 3 && $iTotalGmts <= 5){
            
            $objPdf->Rect(21, 94.5, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,98.5,4,'F');
            else
                $objPdf->Circle(174,98.5,4,'F');
        }
        else if($iTotalGmts > 5 && $iTotalGmts <= 8){
            
            $objPdf->Rect(21, 102, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,106,4,'F');
            else
                $objPdf->Circle(174,106,4,'F');
        }
        else if($iTotalGmts > 8 && $iTotalGmts <= 13){
            
            $objPdf->Rect(21, 109.5, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,113.5,4,'F');
            else
                $objPdf->Circle(174,113.5,4,'F');
        }
        else if($iTotalGmts > 13 && $iTotalGmts <= 20){
            
            $objPdf->Rect(21, 117, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,121,4,'F');
            else
                $objPdf->Circle(174,121,4,'F');
        }
        else if($iTotalGmts > 20 && $iTotalGmts <= 32){
            
            $objPdf->Rect(21, 124.5, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,128.5,4,'F');
            else
                $objPdf->Circle(174,128.5,4,'F');
        }
        else if($iTotalGmts > 32 && $iTotalGmts <= 50){
            
            $objPdf->Rect(21, 132, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,136,4,'F');
            else
                $objPdf->Circle(174,136,4,'F');
        }
        else if($iTotalGmts > 50 && $iTotalGmts <= 80){
            
            $objPdf->Rect(21, 139.5, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,143.5,4,'F');
            else
                $objPdf->Circle(174,143.5,4,'F');
        }
        else if($iTotalGmts > 80 && $iTotalGmts <= 125){
            
            $objPdf->Rect(21, 147, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,151,4,'F');
            else
                $objPdf->Circle(174,151,4,'F');
        }
        else if($iTotalGmts > 125 && $iTotalGmts <= 200){
            
            $objPdf->Rect(21, 154.5, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,158.5,4,'F');
            else
                $objPdf->Circle(174,158.5,4,'F');
        }
        else if($iTotalGmts > 200 && $iTotalGmts <= 315){
            
            $objPdf->Rect(21, 162, 165, 7.5, 'DF');  
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,166,4,'F');
            else
                $objPdf->Circle(174,166,4,'F');
        }
        else if($iTotalGmts > 315 && $iTotalGmts <= 500){
            
            $objPdf->Rect(21, 169.5, 165, 7.5, 'DF');
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,173.5,4,'F');
            else
                $objPdf->Circle(174,173.5,4,'F');
        }
        else if($iTotalGmts > 500 && $iTotalGmts <= 800){
            
            $objPdf->Rect(21, 177, 165, 7.5, 'DF');  
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,181,4,'F');
            else
                $objPdf->Circle(174,181,4,'F');
        }
        else if($iTotalGmts > 800 && $iTotalGmts <= 1250){
            
            $objPdf->Rect(21, 184.5, 165, 7.6, 'DF');  
            
            $objPdf->SetFillColor(100,0,0);
            if($fAql == 2.5)
                $objPdf->Circle(151,188.5,4,'F');
            else
                $objPdf->Circle(174,188.5,4,'F');
        }

        // restore full opacity
        $objPdf->SetAlpha(1);
        $objPdf->SetTextColor(50, 50, 50);

	$iCurrentPage ++;
        
	 /////////////////////////////////////////////////// Page 5 /////////////////////////
	 
	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/jcrew.pdf");
	$iTemplateId = $objPdf->importPage(5, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 12, 19);


	$objPdf->SetFont('helvetica', '', 6);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(178.5, 31, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('helvetica', '', 9);
	$objPdf->Text(11, 10, "Page {$iCurrentPage} of {$iTotalPages}");


	$objPdf->Text(135, 12.7, $sStyle);
	$objPdf->Text(135, 18, $sPo);
	$objPdf->Text(135, 23.5, $sAuditStage);
	$objPdf->Text(135, 29, formatDate($sAuditDate));

	
	$objPdf->SetFont('helvetica', '', 7);

	$sSizes  = getDbValue("GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',')", "tbl_qa_report_samples", "audit_id='$Id'");
	$iSizes  = @explode(",", $sSizes);

	$objPdf->Text(45, 52.5, getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id'"));
	$objPdf->Text(45, 57.5, getDbValue("GROUP_CONCAT(size SEPARATOR ',')", "tbl_sampling_sizes", "id IN ($sSizes)"));

	$objPdf->SetFont('helvetica', '', 6);
	
	
	$sSizeFindings  = array( );
	$sSizeFindings2 = array( );
	$iPoints        = array( );
        
	$sSQL = "SELECT qrs.size_id ,qrs.audit_id, qrs.sample_no, qrss.point_id, qrss.findings
			FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
			WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id
			ORDER BY qrss.point_id, qrs.sample_no";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	
	for($i = 0; $i < $iCount; $i ++)
	{
		$iSize      = $objDb->getField($i, 'size_id');
		$iPoint     = $objDb->getField($i, 'point_id');
		$iSampleNo  = $objDb->getField($i, 'sample_no');
		$sFindings  = $objDb->getField($i, 'findings');
		
		
		$iSamplesCount   = (int)getDbValue("COUNT(1)", "tbl_style_specs", " point_id='$iPoint' AND style_id='$iStyle' AND version='0' AND specs!='0' AND specs!='' AND size_id IN ($sSizes)");
                $sSpecValue      =  getDbValue("specs", "tbl_style_specs", " point_id='$iPoint' AND style_id='$iStyle' AND size_id='$iSize'");
		$iSamplesChecked = (int)getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize'");
		$iSampleSize     = ($iSamplesCount * $iSamplesChecked);
		
		$sSizeFindings["{$i}-{$iSampleNo}-{$iPoint}"] = array('finiding' => $sFindings, 'sample_size' => $iSampleSize, 'size_id' => $iSize, 'spec_value'=> $sSpecValue);
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
                $iSampleSize   = $sFindings['sample_size'];
                $sSpecs        = ConvertToFloatValue($sFindings['spec_value']);//@$sSpecsList[$iPoint];

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
                    continue;
                
                if ($iPoint != $iLastPoint)
                {
                        $TotalPercent  = 0;
                        $MajorDefects  = 0;
                        $MinorDefects  = 0;
                        $iTotalSum     = 0;
                }

                if ($fMeaseuredValue >= $NegativeTolerance && $fMeaseuredValue <= $PositiveTolerance)
                    continue;
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
        
        $iTop               = 180.5;
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
                
                $objPdf->SetXY(11, ($iTop - 0.5));
                $objPdf->setCellHeightRatio(1.0);
                $objPdf->MultiCell(120, 1.3, @$sPointList[$iPoint], 0, "L");

                $objPdf->Text(162, $iTop, $sDefectsArr['minor']);
                $objPdf->Text(177, $iTop, $sDefectsArr['major']);
                $objPdf->Text(189, $iTop, number_format($sDefectsArr['percent'],2));

                $iTop += 3.35;
            }   
			
            $limit++;
        }
        
		
        $objPdf->Text(162, 257.5, formatNumber($iTotalMinor, false));
        $objPdf->Text(177, 257.5, formatNumber($iTotalMajor, false));
        
        //sizez box
        $objPdf->SetFont('helvetica', 'B', 7);
        $objPdf->SetTextColor(50, 50, 50);
        $sQtyPerSize            = "";
        $iSizeTop               = 71.5;
        $iTotalEvaluatedPoints  = 0;
        $iTotalDefectivePoints  = 0;
        
        foreach ($iSizes as $iSize)
        {
                if ($sQtyPerSize != "")
                        $sQtyPerSize .= ", ";

                $sSize = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");
                $TotalInspections = getDbValue('COUNT(DISTINCT sample_no)', 'tbl_qa_report_samples', "audit_id='$Id' AND size_id='$iSize'");
                $iTotalMeaseurementPoints = getDbValue("COUNT(point_id)", "tbl_style_specs", "style_id='$iStyle' AND size_id='$iSize' AND version='0' AND specs!='0' AND specs!=''");
                $iSamplesChecked = getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize'");
			
                if ($iSamplesChecked > 5)
                        $iSamplesChecked = 5;
                
                $sSQL = "SELECT point_id, specs,
                        (SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance
                FROM tbl_style_specs
                WHERE style_id='$iStyle' AND size_id='$iSize' AND version='0' AND specs!='0' AND specs!=''
                ORDER BY id";
                
                $objDb->query($sSQL);
                $iCount2        = $objDb->getCount( );
                $count          = 0;
                
                
                for($i=0; $i < $iCount2; $i++)
                {
                    for ($j = 1; $j <= $iSamplesChecked; $j ++, $k ++)
                    {
                        $iPoint     = $objDb->getField($i, 'point_id');
                        $sSpecs     = $objDb->getField($i, 'specs');
                        $sTolerance = $objDb->getField($i, '_Tolerance');
                        $sFinding  = $sSizeFindings2["{$iSize}-{$j}-{$iPoint}"];

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
                                $count++;
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
		
		
        $objPdf->Text(105, 52.5, $iTotalEvaluatedPoints);
        $objPdf->Text(162, 52.5, $iTotalDefectivePoints);
        $iGenealPercent = ($iTotalDefectivePoints/$iTotalEvaluatedPoints)*100;
        $objPdf->Text(189, 257, number_format(($iGenealPercent),2).'%');
        $objPdf->SetFont('helvetica', 'B', 7);

        if($sMeasurementResult != "")
        {
            if($sMeasurementResult == "F")
            {
               $objPdf->SetTextColor(255, 0, 0);
               $objPdf->Text(190, 52.5, 'Fail');
            }		
            else
            {
               $objPdf->SetTextColor(0, 100, 0);
               $objPdf->Text(190, 52.5, 'Pass');
            }
        }
        else
        {
            if($iGenealPercent > 20)
            {
               $objPdf->SetTextColor(255, 0, 0);
               $objPdf->Text(190, 52.5, 'Fail');
            }		
            else
            {
               $objPdf->SetTextColor(0, 100, 0);
               $objPdf->Text(190, 52.5, 'Pass');
            }
        }	
		
    /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 6

        $iCurrentPage = 6;
	$iPageCount   = $objPdf->setSourceFile($sBaseDir."templates/jcrew.pdf");
	$iTemplateId  = $objPdf->importPage(6, '/MediaBox');

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
			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 12, 19);

                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(178.5, 31, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(11, 10, "Page {$iCurrentPage} of {$iTotalPages}");

			$objPdf->Text(135, 12.7, $sStyle);
                        $objPdf->Text(135, 18, $sPo);
                        $objPdf->Text(135, 23.5, $sAuditStage);
                        $objPdf->Text(135, 29, formatDate($sAuditDate));

			$sSize = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");

			$objPdf->SetFont('Arial', '', 8);
			$objPdf->Text(38, 48, $iSamplesMeasured);
			
                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->SetXY(132, 63);
                        $objPdf->setCellHeightRatio(1.0);
                        $objPdf->MultiCell(55, 2.5, $sColor, 0, "L");
        

			$objPdf->SetXY(37, 52.5);
                        $objPdf->setCellHeightRatio(1.0);
                        $objPdf->MultiCell(162, 3, $sQtyPerSize, 0, "L");

			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(77.5, 222, $sSize);

			$sSQL = "SELECT point_id, specs,
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
				$sPoint     = $objDb->getField($i, '_Point');
				$sTolerance = $objDb->getField($i, '_Tolerance');


				$objPdf->SetFont('Arial', '', 7);
				$objPdf->Text(13, (78 + ($i * 5.320)), ($i + 1));

				$objPdf->SetFont('Arial', '', 8);
				$objPdf->SetXY(20.5, (77.5 + ($i * 5.320)));
                                $objPdf->setCellHeightRatio(1.0);
                                $objPdf->MultiCell(85, 2.2, $sPoint, 0, "L");

				$objPdf->SetFont('Arial', '', 7);
				$objPdf->Text(122.5, (78 + ($i * 5.320)), $sSpecs);

				$objPdf->Text(187.6, (78 + ($i * 5.320)), $sTolerance);

		
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
							$objPdf->Cell(9.1, 4.415, $sSizeFindings["{$j}-{$iPoint}"], 0, 0, "C", true);
						}
						
						else
						{							
							$objPdf->SetFillColor(255, 255, 0);
							$objPdf->SetXY((122 + ($j * 10.5)), (76.8 + ($i * 5.320)));
							$objPdf->Cell(9.1, 4.415, $sSizeFindings["{$j}-{$iPoint}"], 0, 0, "C", true);

							$iOut ++;
						}
					}

					else
						$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (125.5 + ($j * 10.5)), (77.5 + ($i * 5.320)), 4);
				}
			}

			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(89, 222.3, ($iCount * $iSamplesChecked));
			$objPdf->Text(179, 222.3, $iOut);

			if ($sMeasurementResult == "P")
				$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 28, 240, 4);

			else if ($sMeasurementResult == "F")
				$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 77.5, 240, 4);

			else if ($sMeasurementResult == "H")
				$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 131, 240, 4);


			$objPdf->SetXY(27, 247);
                        $objPdf->setCellHeightRatio(1.0);
                        $objPdf->MultiCell(188, 3, $sMeasurementComments, 0, "L");


			$objPdf->Text(54, 260.5, "{$sAuditor} / {$sUserType}");
			$objPdf->Text(130, 260.5, formatDate($sAuditDate));

			$iCurrentPage ++;
		}
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 7  -  DEFECT IMAGES

	if (count($sDefects) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/jcrew.pdf");
		$iTemplateId = $objPdf->importPage(8, '/MediaBox');


		$iPages = @ceil(count($sDefects) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			// QR Code
			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 12, 19);
                        $objPdf->SetFont('helvetica', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(178.5, 31, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('helvetica', '', 9);
			$objPdf->Text(10.3, 10, "Page {$iCurrentPage} of {$iTotalPages}");


			$objPdf->Text(135, 12.7, $sStyle);
                        $objPdf->Text(135, 18, $sPo);
                        $objPdf->Text(135, 23.5, $sAuditStage);
                        $objPdf->Text(135, 29, formatDate($sAuditDate));


			$objPdf->SetFont('helvetica', 'B', 12);
			$objPdf->Text(10, 35.5, "Defect Images");

			$objPdf->SetFont('helvetica', '', 7);

			for ($j = 0; $j < 4 && $iIndex < count($sDefects); $j ++, $iIndex ++)
			{
				$sName      = @strtoupper($sDefects[$iIndex]);
                                $sFileName  = @basename($sName);
                               
                                $sSQL = "SELECT qrd.area_id, dc.defect,
                                                (SELECT `type` FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
						 FROM tbl_qa_report_defects qrd, tbl_defect_codes dc
						 WHERE qrd.code_id=dc.id AND dc.report_id='$iReportId' AND qrd.audit_id='$Id' AND qrd.picture LIKE '$sFileName'";
				$objDb->query($sSQL);
                                
				$sAreaCode  = $objDb->getField(0, "area_id");                                
                                $sDefect    = $objDb->getField(0, "defect");
				$sType      = $objDb->getField(0, "_Type");

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
                                $objPdf->setCellHeightRatio(1.5);
                                $objPdf->MultiCell(98, 3, $sInfo, 1, "L");

				$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sDefects[$iIndex]), $iLeft, $iTop, 98, 90);
			}
		}
	} 
	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 8  -  PACKING IMAGES

	if (count($sPacking) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/jcrew.pdf");
		$iTemplateId = $objPdf->importPage(8, '/MediaBox');


		$iPages = @ceil(count($sPacking) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			// QR Code
			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 12, 19);

                        $objPdf->SetFont('helvetica', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(178.5, 31, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('helvetica', '', 9);
			$objPdf->Text(10.3, 10, "Page {$iCurrentPage} of {$iTotalPages}");


                        $objPdf->Text(135, 12.7, $sStyle);
                        $objPdf->Text(135, 18, $sPo);
                        $objPdf->Text(135, 23.5, $sAuditStage);
                        $objPdf->Text(135, 29, formatDate($sAuditDate));

			$objPdf->SetFont('helvetica', 'B', 12);
			$objPdf->Text(11, 35.5, "Packing Images");

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
	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 9  -  SPECS SHEETS

	if (count($sSpecsSheets) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/jcrew.pdf");
		$iTemplateId = $objPdf->importPage(8, '/MediaBox');


		for ($i = 0; $i < count($sSpecsSheets); $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 12, 19);


                        $objPdf->SetFont('helvetica', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(178.5, 31, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('helvetica', '', 9);
			$objPdf->Text(10.3, 10, "Page {$iCurrentPage} of {$iTotalPages}");


                        $objPdf->Text(135, 12.7, $sStyle);
                        $objPdf->Text(135, 18, $sPo);
                        $objPdf->Text(135, 23.5, $sAuditStage);
                        $objPdf->Text(135, 29, formatDate($sAuditDate));

			$objPdf->SetFont('helvetica', '', 11);
			$objPdf->Text(11, 35.5, "Lab Reports / Specs Sheets");


			$objPdf->Image($sSpecsSheets[$i], 10, 47, 180, 200);
		}
	}
        
	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 10  -  Product Front View Images

	if (count($sFront) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/jcrew.pdf");
		$iTemplateId = $objPdf->importPage(8, '/MediaBox');

		$iPages = @ceil(count($sFront) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);

			// QR Code
			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 12, 19);

                        $objPdf->SetFont('helvetica', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(178.5, 31, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('helvetica', '', 9);
			$objPdf->Text(10.3, 10, "Page {$iCurrentPage} of {$iTotalPages}");

			$objPdf->SetFont('helvetica', '', 9);
                        $objPdf->Text(135, 12.7, $sStyle);
                        $objPdf->Text(135, 18, $sPo);
                        $objPdf->Text(135, 23.5, $sAuditStage);
                        $objPdf->Text(135, 29, formatDate($sAuditDate));

			$objPdf->SetFont('helvetica', 'B', 12);
			$objPdf->Text(11, 35.5, "Production Front View Images");


			for ($j = 0; $j < 4 && $iIndex < count($sFront); $j ++, $iIndex ++)
			{
				$iLeft = 5;
				$iTop  = 47;

				if ($j == 1 || $j == 3)
					$iLeft = 107;

				if ($j == 2 || $j == 3)
					$iTop = 150;


				$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sFront[$iIndex]), $iLeft, $iTop, 98, 98);
			}
		}
	}
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 11  -  Product Back View

	if (count($sBack) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/jcrew.pdf");
		$iTemplateId = $objPdf->importPage(8, '/MediaBox');

		$iPages = @ceil(count($sBack) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);

			// QR Code
			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 12, 19);

                        $objPdf->SetFont('helvetica', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(178.5, 31, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('helvetica', '', 9);
			$objPdf->Text(10.3, 10, "Page {$iCurrentPage} of {$iTotalPages}");

			$objPdf->SetFont('helvetica', '', 9);
                        $objPdf->Text(135, 12.7, $sStyle);
                        $objPdf->Text(135, 18, $sPo);
                        $objPdf->Text(135, 23.5, $sAuditStage);
                        $objPdf->Text(135, 29, formatDate($sAuditDate));

			$objPdf->SetFont('helvetica', 'B', 12);
			$objPdf->Text(11, 35.5, "Production Back View Images");


			for ($j = 0; $j < 4 && $iIndex < count($sBack); $j ++, $iIndex ++)
			{
				$iLeft = 5;
				$iTop  = 47;

				if ($j == 1 || $j == 3)
					$iLeft = 107;

				if ($j == 2 || $j == 3)
					$iTop = 150;

				$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sBack[$iIndex]), $iLeft, $iTop, 98, 98);
			}
		}
	}
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 12  -  Colorways

	if (count($sColorWay) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/jcrew.pdf");
		$iTemplateId = $objPdf->importPage(8, '/MediaBox');

		$iPages = @ceil(count($sColorWay) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);

			// QR Code
			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 12, 19);

                        $objPdf->SetFont('helvetica', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(178.5, 31, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('helvetica', '', 9);
			$objPdf->Text(10.3, 10, "Page {$iCurrentPage} of {$iTotalPages}");

			$objPdf->SetFont('helvetica', '', 9);
                        $objPdf->Text(135, 12.7, $sStyle);
                        $objPdf->Text(135, 18, $sPo);
                        $objPdf->Text(135, 23.5, $sAuditStage);
                        $objPdf->Text(135, 29, formatDate($sAuditDate));

			$objPdf->SetFont('helvetica', 'B', 12);
			$objPdf->Text(11, 35.5, "Colorway of production Images");


			for ($j = 0; $j < 4 && $iIndex < count($sColorWay); $j ++, $iIndex ++)
			{
				$iLeft = 5;
				$iTop  = 47;

				if ($j == 1 || $j == 3)
					$iLeft = 107;

				if ($j == 2 || $j == 3)
					$iTop = 150;


				$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sColorWay[$iIndex]), $iLeft, $iTop, 98, 98);
			}
		}
	}
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 13  -  MISC Images

	if (count($sMisc) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/jcrew.pdf");
		$iTemplateId = $objPdf->importPage(8, '/MediaBox');

		$iPages = @ceil(count($sMisc) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);

			// QR Code
			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 12, 19);

                        $objPdf->SetFont('helvetica', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(178.5, 31, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('helvetica', '', 9);
			$objPdf->Text(10.3, 10, "Page {$iCurrentPage} of {$iTotalPages}");

			$objPdf->SetFont('helvetica', '', 9);
                        $objPdf->Text(135, 12.7, $sStyle);
                        $objPdf->Text(135, 18, $sPo);
                        $objPdf->Text(135, 23.5, $sAuditStage);
                        $objPdf->Text(135, 29, formatDate($sAuditDate));

			$objPdf->SetFont('helvetica', 'B', 12);
			$objPdf->Text(11, 35.5, "Miscellaneous Images");


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
       /////////////////////Annexure 14 page ///////////////////////////////////////////////////

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/jcrew.pdf");
	$iTemplateId = $objPdf->importPage(7, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 179.5, 12, 19);

        $objPdf->SetFont('helvetica', '', 6);
        $objPdf->SetTextColor(50, 50, 50);

        $objPdf->Text(178.5, 31, "Audit Code: {$sAuditCode}");


        $objPdf->SetFont('helvetica', '', 9);
        $objPdf->Text(10.3, 10, "Page {$iCurrentPage} of {$iTotalPages}");

        $objPdf->SetFont('helvetica', '', 9);
        $objPdf->Text(135, 12.7, $sStyle);
	$objPdf->Text(135, 18, $sPo);
	$objPdf->Text(135, 23.5, $sAuditStage);
	$objPdf->Text(135, 29, formatDate($sAuditDate));

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	@unlink($sBaseDir.TEMP_DIR."{$sAuditCode}.png");

	$sPdfFile = ($sBaseDir.TEMP_DIR."S{$Id}-QA-Report.pdf");

	if (count($Recipients) > 0)
		$objPdf->Output($sPdfFile, 'F');

	else
		$objPdf->Output(@basename($sPdfFile), 'D');
?>