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
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

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

        function cleanString($text) 
        {
            $utf8 = array(
                '/[áàâãªä]/u'   =>   'a',
                '/[ÁÀÂÃÄ]/u'    =>   'A',
                '/[ÍÌÎÏ]/u'     =>   'I',
                '/[íìîï]/u'     =>   'i',
                '/[éèêë]/u'     =>   'e',
                '/[ÉÈÊË]/u'     =>   'E',
                '/[óòôõºö]/u'   =>   'o',
                '/[ÓÒÔÕÖ]/u'    =>   'O',
                '/[úùûü]/u'     =>   'u',
                '/[ÚÙÛÜ]/u'     =>   'U',
                '/ç/'           =>   'c',
                '/Ç/'           =>   'C',
                '/ñ/'           =>   'n',
                '/Ñ/'           =>   'N',
                '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
                '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
                '/[“”«»„]/u'    =>   ' ', // Double quote
                '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
            );
            return preg_replace(array_keys($utf8), array_values($utf8), $text);
        }
	
	
	$sSQL = "SELECT *,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
	                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor
	         FROM tbl_qa_reports
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	$iReportId          = $objDb->getField(0, "report_id");
	$iVendor            = $objDb->getField(0, "vendor_id");
        $iBooking           = $objDb->getField(0, "booking_id");
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
        $iCheckLevel        = $objDb->getField(0, "check_level");
	$sLatitude          = $objDb->getField(0, "latitude");
	$sLongitude         = $objDb->getField(0, "longitude");
	$sLocation          = $objDb->getField(0, "location");
        $sHohIONo           = $objDb->getField(0, "hoh_order_no");
        
        $sSQL = "SELECT *
                    FROM tbl_qa_hohenstein
	         WHERE audit_id='$Id'";
        
	$objDb->query($sSQL);

	$iProductConformResult      = $objDb->getField(0, "product_conformity_result");
        $iWorkmanshipResult         = $objDb->getField(0, "workmanship_result");
	$sProductConformComments    = $objDb->getField(0, "product_conformity_comments");
        $iMeasurementResult         = $objDb->getField(0, "measurement_result");
        $sMeasurementComments       = $objDb->getField(0, "measurement_remarks");
        $iWeightConformResult       = $objDb->getField(0, "weight_conformity_result");
        $sWeightConformComments     = $objDb->getField(0, "weight_conformity_comments");
        $sBarCodeFormat             = $objDb->getField(0, "barcode_format");
        $iScannerType               = $objDb->getField(0, "scanner_type");
        $iEanResult                 = $objDb->getField(0, "ean_result");
        $sEanComments               = $objDb->getField(0, "ean_comments");
        $iPackaginResult            = $objDb->getField(0, "packaging_result");
        $sPackagingComments         = $objDb->getField(0, "packaging_comments");
        $iLabelingResult            = $objDb->getField(0, "labeling_result");
        $sLabelingComments          = $objDb->getField(0, "labeling_comments");        
        $iChildLabourConformance    = $objDb->getField(0, "child_labour_conformance");
        $iChildLabourNonConformance = $objDb->getField(0, "child_labour_non_conformance");
        $sChilLabourComments        = $objDb->getField(0, "child_labour_comments");
        $sChildLabourRecommendation = $objDb->getField(0, "child_labour_recommendations");
        $iChildLabourDeadLine       = $objDb->getField(0, "child_labour_deadline");
        $sChildLabourResult         = $objDb->getField(0, "child_labour_result");
        $sSignatureComments         = $objDb->getField(0, "signatures_comments");
        $sSignatureInspector        = $objDb->getField(0, "signatures_inspector");
        $sSignatureManufacturer     = $objDb->getField(0, "signatures_manufacturer");
        $sMasterCartonResult        = $objDb->getField(0, "master_cartons_result");
        $sAirwayBillNo              = $objDb->getField(0, "airway_bill_number");
        $sAirwayBillApplicable      = $objDb->getField(0, "airway_bill_applicable");
        $sSiteName                  = $objDb->getField(0, "child_labour_site_name");
        $sSiteFax                   = $objDb->getField(0, "child_labour_site_fax");
        $sSiteAddress               = $objDb->getField(0, "child_labour_site_address");
        $sSiteEmail                 = $objDb->getField(0, "child_labour_site_email");
        $sSitePhone                 = $objDb->getField(0, "child_labour_site_phone");
        $sSitePerson                = $objDb->getField(0, "child_labour_site_person");
	
        
                
        $sSQL = "SELECT *
                    FROM tbl_qa_assortment
	         WHERE audit_id='$Id'";
        
	$objDb->query($sSQL);
        
	$iTotalAssortmentCartons   = $objDb->getField(0, "total_cartons_tested");
	$iWrongAssortmentCartons   = $objDb->getField(0, "wrong_assorted_cartons");
        $iAssortmentResult         = $objDb->getField(0, "result");
        $sAssortmentComments       = $objDb->getField(0, "comments");
        
        $sServicesRequested = "";
        if($iBooking > 0)
        {        
            $sSQL = "SELECT * FROM tbl_bookings WHERE id='$iBooking'";
            $objDb->query($sSQL);

            $iSupplier         	= $objDb->getField(0,"supplier_id");
            $sContactPersonName = $objDb->getField(0,"cp_name");
            $sArticle        	= $objDb->getField(0,"article");
            $sContactPersonPhone= $objDb->getField(0,"cp_phone");
            $sContactPersonEmail= $objDb->getField(0,"cp_email");
            $iServices          = $objDb->getField(0,"services");
            
            $sServicesRequested = getDbValue("GROUP_CONCAT(service SEPARATOR ',')", "tbl_audit_services", "id IN ({$iServices})");
        }
                
        @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
        
	$sSpecsSheets = array( );

	for ($i = 1; $i <= 10; $i ++)
	{
		$sSpecsSheet = $objDb->getField(0, "specs_sheet_{$i}");

		if ($sSpecsSheet != "")
		{
			if (@file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet) && @filesize($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet))
				$sSpecsSheets[] = ($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet);
			
			else if (@file_exists($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsSheet) && @filesize($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsSheet))
				$sSpecsSheets[] = ($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsSheet);
		}
	}


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

        $fAql         = getDbValue("aql", "tbl_brands", "id='$iParent'");
	$sDestination = getDbValue("destination", "tbl_destinations", "id='$iDestination'");

        @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

/*	$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_001_*.*");
	$sPictures = @array_merge($sPictures, @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*"));
	$sPictures = @array_map("strtoupper", $sPictures);
	$sPictures = @array_unique($sPictures);*/

        $sInspectorSignature = "";
        $sManufactureSignature = "";
        
        if (@file_exists($sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/"."{$sAuditCode}_inspector.jpg") && @filesize($sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/"."{$sAuditCode}_inspector.jpg"))
                $sInspectorSignature = ($sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/"."{$sAuditCode}_inspector.jpg");
        
        if (@file_exists($sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/"."{$sAuditCode}_manufacturer.jpg") && @filesize($sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/"."{$sAuditCode}_manufacturer.jpg"))
                $sManufactureSignature = ($sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/"."{$sAuditCode}_manufacturer.jpg");
        
        $sAllImages = array( );
	$sDefects   = array( );
	$sPacking   = array( );
	$sMisc      = array( );
        $sAdditional= array( );
        $sPackingDefects = array( );
        $sLabelingDefects = array( );
        $sWeightImages = array( );

	$sDefectImages          = getList("tbl_qa_report_defects", "id", "UPPER(picture)", "audit_id='$Id' AND picture LIKE '%.jpg'");
        $sDefectSamples         = getList("tbl_qa_report_defects", "id", "sample_no", "audit_id='$Id' AND picture LIKE '%.jpg'");
	$sPackingImages         = getList("tbl_qa_report_images", "id", "UPPER(image)", "audit_id='$Id' AND `type`='P' AND image LIKE '%.jpg'");
	$sMiscImages            = getList("tbl_qa_report_images", "id", "UPPER(image)", "audit_id='$Id' AND `type`='M' AND image LIKE '%.jpg'");                   
	$sLabImages             = getList("tbl_qa_report_images", "id", "UPPER(image)", "audit_id='$Id' AND `type`='L' AND image LIKE '%.jpg'");
        $sAdditionalImages      = getList("tbl_qa_report_images", "id", "UPPER(image)", "audit_id='$Id' AND (`type`='AD' OR `type`='AT') AND image LIKE '%.jpg'");
        $sWeightDetailPictures  = getList("tbl_qa_weight_pictures", "picture", "picture", "audit_id='$Id' AND picture LIKE '%.jpg'", "serial");
        $sWeightDetailSerials   = getList("tbl_qa_weight_pictures", "picture", "serial", "audit_id='$Id' AND picture LIKE '%.jpg'", "serial");
        $sWeightDetailColors    = getList("tbl_qa_weight_pictures", "picture", "color", "audit_id='$Id' AND picture LIKE '%.jpg'", "serial");
        $sPackingDefectPictures = getList("tbl_qa_packaging_defects", "id", "picture", "audit_id='$Id' AND picture LIKE '%.jpg'");
        $sLabelingDefectPictures= getList("tbl_qa_labeling_defects", "id", "picture", "audit_id='$Id' AND picture LIKE '%.jpg'");
	
	foreach ($sDefectImages as $iDefectId => $sImage)
	{
		if (@file_exists($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
                {
			$sDefects[]    = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);
                        $sAllImages[]  = array('image'=>($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage), 'type'=>'D', 'sample_no'=> (int)$sDefectSamples[$iDefectId]);
                }
	}
	
	foreach ($sPackingImages as $sImage)
	{
		if (@file_exists($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
                {
			$sPacking[]     = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);
                        $sAllImages[]   = array('image'=>($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage), 'type'=>'P');
                }
	}
        
        foreach ($sPackingDefectPictures as $sImage)
	{
		if (@file_exists($sBaseDir.PACKAGING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.PACKAGING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
                {
			$sPackingDefects[]  = ($sBaseDir.PACKAGING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);
                        $sAllImages[]       = array('image'=>($sBaseDir.PACKAGING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage), 'type'=>'PD');
                }
	}

        foreach ($sLabelingDefectPictures as $sImage)
	{
		if (@file_exists($sBaseDir.PACKAGING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.PACKAGING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
                {
			$sLabelingDefects[]  = ($sBaseDir.PACKAGING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);
                        $sAllImages[]       = array('image'=>($sBaseDir.PACKAGING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage), 'type'=>'LD');
                }
	}
        
        foreach ($sAdditionalImages as $sImage)
	{
		if (@file_exists($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
                {
			$sAdditional[]  = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);
                        $sAllImages[]   = array('image'=>($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage), 'type'=>'AD');
                }
	}

	foreach ($sMiscImages as $sImage)
	{
		if (@file_exists($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
                {
			$sMisc[]        = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);
                        $sAllImages[]   = array('image'=>($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage), 'type'=>'M');
                }
	}	
	
	foreach ($sLabImages as $sImage)
	{
		if (@file_exists($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
                {
			$sSpecsSheets[] = ($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);
                        $sAllImages[]   = array('image'=>($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage), 'type'=>'L');
                }
	}	

        foreach ($sWeightDetailPictures as $sImage)
	{
		if (@file_exists($sBaseDir.CARTONS_PICS_DIR."/".$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.CARTONS_PICS_DIR."/".$sYear."/".$sMonth."/".$sDay."/".$sImage))
                {
                    $sWeightImages[] = ($sBaseDir.CARTONS_PICS_DIR."/".$sYear."/".$sMonth."/".$sDay."/".$sImage);        
                    $sAllImages[]    = array('image'=>($sBaseDir.CARTONS_PICS_DIR."/".$sYear."/".$sMonth."/".$sDay."/".$sImage), 'type'=>'W', 'serial' => $sWeightDetailSerials["{$sImage}"], 'color'=>$sWeightDetailColors["{$sImage}"]);     
                }
	}
        
        $sSuppliersList  = getList("tbl_suppliers", "id", "supplier");
        
        if($iVendor > 0)
        {
            $sSQL = "SELECT vendor, code, address, manager_rep, manager_rep_email, phone, city, latitude, longitude FROM tbl_vendors WHERE id='$iVendor'";
            $objDb->query($sSQL);
            
            $sVendor            = $objDb->getField(0,"vendor");
            $sVendorCode        = $objDb->getField(0,"code");
            $sVendorCity        = $objDb->getField(0,"city");
            $sVendorAddress     = $objDb->getField(0,"address");
            $sRepName           = $objDb->getField(0,"manager_rep");
            $sRepEmail          = $objDb->getField(0,"manager_rep_email");
            $sRepPhone          = $objDb->getField(0,"phone");            
            $sVendorLatitude    = $objDb->getField(0,"latitude");
            $sVendorLongitude   = $objDb->getField(0,"longitude");
        }
        
        $sAttachments = getList("tbl_qa_report_images", "id", "UPPER(image)", "audit_id='$Id' AND image NOT LIKE '%.jpg'");
        
        $iTotalPages  = 15+($sChildLabourResult != 'P'?2:0)+(count($sAttachments)>0?1:0);
	$iTotalPages += getDbValue("COUNT(DISTINCT(CONCAT(size_id, '-', color)))", "tbl_qa_report_samples", "audit_id='$Id'");        
	$iTotalPages += @ceil(count($sAllImages) / 9);

	/////////////////////////////////////////////////////page1///////////////////////////////////////////////////////////////
	$objPdf = new AlphaPDF( );
/*
	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

        //********* Main Header Starts *****************
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 170, 13, 25.5);
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(171, 42, "Audit Code: {$sAuditCode}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 8, "Page 1 of {$iTotalPages}");

        $objPdf->SetFont('Arial', '', 7);
        $objPdf->Text(37, 20.5, ($sPo.$sAdditionalPos));
	$objPdf->Text(37, 24, $sServicesRequested);
        $objPdf->Text(37, 27.5, $sHohIONo);
        $objPdf->Text(37, 31, "1");
        $objPdf->Text(37, 34.6, formatDate($sAuditDate));
        $objPdf->Text(37, 38.2, formatDate($sAuditDate));
        
        $objPdf->Text(118, 20.5, formatNumber($iQuantity, false));
	$objPdf->Text(118, 24, formatNumber($iQuantity, false));        
        $objPdf->Text(118, 27.5, "1");
        $objPdf->Text(118, 31, ""); // in%        
        $objPdf->Text(118, 34.6, formatNumber($iTotalGmts, false));        
        $objPdf->Text(118, 38.2, "1 of 1");
        $objPdf->Text(118, 42, $sAuditor);
        //********* Main Header Ends *****************
        
        $objPdf->Text(50, 55.5, $sBrand);
	$objPdf->Text(50, 60.5,  @$sSuppliersList[$iSupplier]);
        $objPdf->Text(50, 66, $sVendor);
        $objPdf->Text(50, 71, @$sArticle);
        $objPdf->Text(50, 76.5, $sServicesRequested);
        $objPdf->Text(50, 82, ($sAirwayBillApplicable == 'Y'?$sAirwayBillNo:'N/A'));

        $objPdf->Text(148, 55.5, @$sContactPersonName);
	$objPdf->Text(148, 60.5, "");//address
        $objPdf->Text(148, 66, @$sContactPersonPhone);
        $objPdf->Text(148, 71, @$sContactPersonEmail);
        $objPdf->Text(148, 76.5, $sAuditor);
        
        //Restuls
        if($iProductConformResult == 'P')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 107, 95, 3);
        else if($iProductConformResult == 'F')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 125.5, 95, 3);
        else
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 154, 95, 3);
        
        if($iWorkmanshipResult == 'P')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 107, 100, 3);
        else if($iWorkmanshipResult == 'F')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 125.5, 100, 3);
        else
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 154, 100, 3);

        if($iMeasurementResult == 'P')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 107, 104.5, 3);
        else if($iMeasurementResult == 'F')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 125.5, 104.5, 3);
        else
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 154, 104.5, 3);

        if($iWeightConformResult == 'P')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 107, 109, 3);
        else if($iWeightConformResult == 'F')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 125.5, 109, 3);
        else
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 154, 109, 3);

        if($iEanResult == 'P')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 107, 113.5, 3);
        else if($iEanResult == 'F')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 125.5, 113.5, 3);
        else
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 154, 113.5, 3);
        
        if($iPackaginResult == 'P')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 107, 118.5, 3);
        else if($iPackaginResult == 'F')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 125.5, 118.5, 3);
        else
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 154, 118.5, 3);
        
        if($iAssortmentResult == 'P')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 107, 123, 3);
        else if($iAssortmentResult == 'F')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 125.5, 123, 3);
        else
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 154, 123, 3);
        
        if($sMasterCartonResult == 'P')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 107, 127.5, 3);
        else if($sMasterCartonResult == 'F')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 125.5, 127.5, 3);
        else
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 154, 127.5, 3);

        if($sAuditResult == 'P')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 18.5, 142, 4);
        else if($sAuditResult == 'F')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 97, 142, 4);
        else
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 154, 142, 4);

        $objPdf->SetFont('Arial', '', 7);
	$objPdf->SetXY(20, 150);
	$objPdf->MultiCell(188, 4.7, $sComments, 0);

        $objPdf->SetFont('Arial', '', 7);
        //factory Info
        $objPdf->Text(62, 173, $sVendor);
        $objPdf->Text(62, 177, $sVendorAddress);
        $objPdf->Text(62, 181.5, $sVendorCode);
        
        $objPdf->Text(151, 173, $sRepName);
        $objPdf->Text(151, 177, $sRepEmail);
        $objPdf->Text(151, 181.5, $sRepPhone);

			
	if ($sVendorLatitude != "" && $sVendorLongitude != "" && $sLatitude != "" && $sLongitude != "")
        {
            $sDistance = calculateDistance($sVendorLatitude, $sVendorLongitude, $sLatitude, $sLongitude);
            $objPdf->Text(12, 200, "Distance: ".$sDistance);            
        }

        if ($sLatitude != "" && $sLongitude != "")
	{	
      		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(6, 82, 195);

                $sLocation = trim(trim(str_replace("\n", ", ", $sLocation)), ",");
                
                $objPdf->SetXY(96, 191);
		$objPdf->Write(5, "{$sLocation} (". formatNumber($sLatitude, true, 8).",". formatNumber($sLongitude, true, 8).")", "http://maps.google.com/maps?q={$sLatitude},{$sLongitude}&z=12");
                
                $objPdf->SetFont('Arial', '', 7);
                $objPdf->SetTextColor(50, 50, 50);
                
                //$objPdf->Text(128, 145.5, "(Click on the link to open location in Google Maps)");
                
                if($sVendorLatitude != "" && $sVendorLongitude != "")
                {
                    $objPdf->SetFont('Arial', '', 7);
                    $objPdf->SetTextColor(6, 82, 195);
                
                    $objPdf->SetXY(94, 184.5);
                    $objPdf->Write(5, "(". formatNumber($sVendorLatitude, true, 8).",". formatNumber($sVendorLongitude, true, 8).")", "http://maps.google.com/maps?q={$sVendorLatitude},{$sVendorLongitude}&z=12");

                    $objPdf->SetFont('Arial', '', 7);
                    $objPdf->SetTextColor(50, 50, 50);

                   // $objPdf->Text(128, 150.5, "(Click on the link to open location in Google Maps)");
                
                    $map = getFileContents("http://maps.googleapis.com/maps/api/staticmap?center=".$sLatitude.",".$sLongitude."&zoom=11&size=1000x450&markers=color:yellow|".$sLatitude.",".$sLongitude."&markers=color:red|".$sVendorLatitude.",".$sVendorLongitude."&key=AIzaSyBNvUKRlOI0Nzqv3MMA63P9_vAH3bYwtc8"); 
                }
                else
                    $map = getFileContents("http://maps.googleapis.com/maps/api/staticmap?center=".$sLatitude.",".$sLongitude."&zoom=13&size=1000x450&markers=color:red|".$sLatitude.",".$sLongitude."&key=AIzaSyBNvUKRlOI0Nzqv3MMA63P9_vAH3bYwtc8");

                $image = imagecreatefromstring($map);
                $saved = imagejpeg($image, $sBaseDir."temp2/googlemapImage.jpg");
                unset($map);
                                
                $objPdf->Image($sBaseDir.'temp2/googlemapImage.jpg',  65, 196, 120,68);
                unlink($sBaseDir.'temp2/googlemapImage.jpg');
	}
	else if ($sVendorLatitude != "" && $sVendorLongitude != "")
	{	
		$sLocation = trim(trim(str_replace("\n", ", ", $sLocation)), ",");

		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(6, 82, 195);
		
		$objPdf->SetXY(94, 184.5);
		$objPdf->Write(5, ": {$sLocation} ({$sVendorLatitude},{$sVendorLongitude})", "http://maps.google.com/maps?q={$sVendorLatitude},{$sVendorLongitude}&z=12");
                
                $map = getFileContents("https://maps.googleapis.com/maps/api/staticmap?center=".$sVendorLatitude.",".$sVendorLongitude."&zoom=13&size=1000x450&markers=color:red|".$sVendorLatitude.",".$sVendorLongitude."&key=AIzaSyDKes4Df5NgRtYcQ_muoqUadWiI3v6GURg"); 
                $image = imagecreatefromstring($map);
                $saved = imagejpeg($image, $sBaseDir."temp2/googlemapImage.jpg");
                unset($map);
                
                $objPdf->Image($sBaseDir.'temp2/googlemapImage.jpg', 65, 196, 120,68);
                unlink($sBaseDir.'temp2/googlemapImage.jpg');
	}
	
	else
		$objPdf->Text(95, 185, ": ".getDbValue("city", "tbl_vendors", "id='$iVendor'"));
*/	
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 15

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(15, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);
        
        $objPdf->SetFont('Arial', '', 7);
        $iPageNo++;
        
        $objPdf->Text(150, 41.2, formatDate($sAuditDate));
        $objPdf->Text(172, 41.2, $sAuditor);
        $objPdf->Text(193, 41.2, "1");
        
        $objPdf->Text(55, 52, $sBrand);
        $objPdf->Text(168, 52, $sSitePerson);
        $objPdf->Text(55, 57.5,  (@$sSuppliersList[$iSupplier]));
        
        $objPdf->Text(73, 68, $sSiteName);
        $objPdf->Text(73, 73, $sSiteAddress);
        $objPdf->Text(73, 78, $sSitePhone);
        $objPdf->Text(73, 83.6, $sSiteFax);
        $objPdf->Text(73, 88.6, $sSiteEmail);
        $objPdf->Text(73, 94.5, $sSitePerson);
        
        $objPdf->Text(55, 110, $sAuditCode);
        $objPdf->Text(55, 115.5, $sAuditor);
        $objPdf->Text(55, 121, formatDate($sAuditDate));
        
        if($sChildLabourResult == 'P')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 70, 122.5, 3);        
        else if($sChildLabourResult == 'I')
           $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 70, 128, 3);
        else if($sChildLabourResult == 'F')
           $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 70, 134, 3);
        
        $objPdf->SetXY(78, 205);
        $objPdf->MultiCell(125, 3, $iChildLabourConformance, 0, "L");
        
        $objPdf->SetXY(78, 212);
        $objPdf->MultiCell(125, 3, $iChildLabourNonConformance, 0, "L");
        
        $objPdf->SetXY(78, 219);
        $objPdf->MultiCell(125, 3, $sChilLabourComments, 0, "L");
        
        $objPdf->SetXY(78, 226);
        $objPdf->MultiCell(125, 3, $sChildLabourRecommendation, 0, "L");

        $objPdf->SetXY(78, 233);
        $objPdf->MultiCell(125, 3, $iChildLabourDeadLine, 0, "L");
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 16
        if($sChildLabourResult != 'P')
        {
            $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
            $iTemplateId = $objPdf->importPage(16, '/MediaBox');

            $objPdf->addPage("P", "A4", "pt");
            $objPdf->useTemplate($iTemplateId, 0, 0);
            
            $objPdf->SetFont('Arial', '', 7);
            $iPageNo++;
            
            $objPdf->Text(150, 41.2, formatDate($sAuditDate));
            $objPdf->Text(172, 41.2, $sAuditor);
            $objPdf->Text(193, 41.2, "2");
        
            $sChildLabourQuestions = getList("tbl_child_labour_questions", "id", "CONCAT(id, ' - ', question)", "status='A'", "position");
            $sChildLabourResults   = getList("tbl_qa_child_labour_details", "question_id", "answer", "audit_id='$Id'");
            $sChildLabourRemarks   = getList("tbl_qa_child_labour_details", "question_id", "remarks", "audit_id='$Id'");

            $iTop = 65;
            $objPdf->SetFont('Arial', '', 5);

            foreach($sChildLabourQuestions as $iQuestion => $sQuestion)
            {
                $sAnswer = $sChildLabourResults[$iQuestion];
                $sRemarks = $sChildLabourRemarks[$iQuestion];

                $objPdf->SetXY(10, $iTop);
                $objPdf->MultiCell(78, 3, cleanString($sQuestion), 0, "L");

                $H = $objPdf->GetY();
                $iHeight = $H-$iTop;

                $objPdf->Text(($sAnswer == 'Y'?90:97), $iTop+2, ($sAnswer == 'Y'?'Yes':'No'));

                $objPdf->SetXY(105, $iTop);
                $objPdf->MultiCell(100, 3, $sRemarks, 0, "L");

                $iTop = $H + 4;
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 17
	if($sChildLabourResult != 'P')
	{
            $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
            $iTemplateId = $objPdf->importPage(17, '/MediaBox');

            $objPdf->addPage("P", "A4", "pt");
            $objPdf->useTemplate($iTemplateId, 0, 0);
            
            $objPdf->SetFont('Arial', '', 7);
            $iPageNo++;
            
            $objPdf->Text(150, 41.2, formatDate($sAuditDate));
            $objPdf->Text(172, 41.2, $sAuditor);
            $objPdf->Text(193, 41.2, "3");
            
            $sSQL = "SELECT * FROM tbl_qa_child_labour WHERE audit_id='$Id'";
            $objDb->query($sSQL);

            $iTop  = 88.5;
            $iCount =  $objDb->getCount();

            for($i=0; $i<$iCount; $i++)
            {
                $iPersonNo          = $objDb->getField($i, "person_no");
                $sPersonName        = $objDb->getField($i, "name");
                $iBirthMonth        = $objDb->getField($i, "birth_month");
                $iBirthYear         = $objDb->getField($i, "birth_year");
                $iAttenedSchool     = $objDb->getField($i, "attended_school");
                $iSchoolSeason      = $objDb->getField($i, "school_lessons");
                $iNonHazerdiousArea = $objDb->getField($i, "non_hazaradous_areas");
                $iEducation         = $objDb->getField($i, "education");
                $iJoiningMonth      = $objDb->getField($i, "joining_month");
                $iJoiningYear       = $objDb->getField($i, "joining_year");
                $iWorkUnderIlo      = $objDb->getField($i, "working_under_ilo");
                $iChildComments     = $objDb->getField($i, "comments");

                $objPdf->Text(12, $iTop, $i+1);
                $objPdf->Text(19, $iTop, $sPersonName);
                $objPdf->Text(58, $iTop, $iBirthMonth."/".$iBirthYear);
                $objPdf->Text(74, $iTop, ($iAttenedSchool == 'Y'?'Yes':'No'));
                $objPdf->Text(90, $iTop, ($iSchoolSeason == 'Y'?'Yes':'No'));
                $objPdf->Text(105, $iTop, ($iNonHazerdiousArea == 'Y'?'Yes':'No'));
                $objPdf->Text(120, $iTop, ($iEducation == 'Y'?'Yes':'No'));
                $objPdf->Text(133, $iTop, $iJoiningMonth."/".$iJoiningYear);
                $objPdf->Text(150, $iTop, ($iWorkUnderIlo == 'Y'?'Yes':'No'));
                $objPdf->SetXY(160, $iTop-5);
                $objPdf->MultiCell(40, 3, $iChildComments, 0, "L");

                $iTop += 11.5; 
            }
        }        
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	@unlink($sBaseDir.TEMP_DIR."{$sAuditCode}.png");

	$sPdfFile = ($sBaseDir.TEMP_DIR."S{$Id}-CL-Report.pdf");

	if (count($Recipients) > 0)
		$objPdf->Output($sPdfFile, 'F');

	else
		$objPdf->Output(@basename($sPdfFile), 'D');
?>