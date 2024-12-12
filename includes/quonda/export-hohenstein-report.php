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
        $sStyles            = $objDb->getField(0, "styles");
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
        
        $iTotalPages  = 14+(count($sAttachments)>0?1:0); //+($sChildLabourResult != 'P'?2:0)
	$iTotalPages += @ceil(getDbValue("COUNT(DISTINCT(CONCAT(size_id, '-', color)))", "tbl_qa_report_samples", "audit_id='$Id'")/5);        
	$iTotalPages += @ceil(count($sAllImages) / 9);
	/////////////////////////////////////////////////////page1///////////////////////////////////////////////////////////////
	$objPdf = new AlphaPDF( );

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

        //********* Main Header Starts *****************//
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
        //********* Main Header Ends *****************//
        
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

	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 2

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(2, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);
        //********* Main Header Starts *****************//
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 170, 13, 25.5);
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(171, 42, "Audit Code: {$sAuditCode}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 8, "Page 2 of {$iTotalPages}");

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
        //********* Main Header Ends *****************//

        //$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 14.8, 56.8, 2);
        $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 57, 56.7, 2);
        //$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 14.8, 62, 2);
        $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 57, 61.8, 2);
        
        $objPdf->SetXY(12, 82);
        $objPdf->MultiCell(185, 3.0, $sSignatureComments);
        
        if($iCheckLevel == 1)
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 54.5, 133.5, 2);
        else if($iCheckLevel == 2)
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 97, 133.2, 2);
        
        //$objPdf->Text(30, 136.1, "2.5");        
        //$objPdf->Text(28, 146.7, $fAql);
        //$objPdf->Text(35, 152, getDbValue("COUNT(1)", "tbl_qa_report_defects", "audit_id='$Id' AND nature='2'"));
        //$objPdf->Text(35, 157, getDbValue("COUNT(1)", "tbl_qa_report_defects", "audit_id='$Id' AND nature='1'"));
        //$objPdf->Text(35, 162.2, getDbValue("COUNT(1)", "tbl_qa_report_defects", "audit_id='$Id' AND nature='0'"));
        $objPdf->Text(38, 167.7, ((int)$iInspectedCartons));
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 3

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(3, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);
        //********* Main Header Starts *****************//
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 170, 13, 25.5);
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(171, 42, "Audit Code: {$sAuditCode}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 8, "Page 3 of {$iTotalPages}");

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
        //********* Main Header Ends *****************//

        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 4

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(4, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);
        //********* Main Header Starts *****************//
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 170, 13, 25.5);
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(171, 42, "Audit Code: {$sAuditCode}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 8, "Page 4 of {$iTotalPages}");

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
        //********* Main Header Ends *****************//

        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 5

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(5, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);
        //********* Main Header Starts *****************//
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 170, 13, 25.5);
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(171, 42, "Audit Code: {$sAuditCode}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 8, "Page 5 of {$iTotalPages}");

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
        //********* Main Header Ends *****************//
        
        if($iProductConformResult == 'P')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 19, 55, 3);
        else if($iProductConformResult == 'F')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 93.5, 55, 3);
        else
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 157.5, 55, 3);
        
        $sSQL = "SELECT *
	         FROM tbl_qa_product_conformity
	         WHERE audit_id='$Id'";
	$objDb->query($sSQL);

        $iCount = $objDb->getCount();
        $iTop = 70;
        
        for($i=0; $i<$iCount; $i++)
        {
            $iSerial = $objDb->getField($i, "serial");
            $sObservation = $objDb->getField($i, "observation");
            
            $objPdf->Text(14, $iTop, $iSerial);
            $objPdf->Text(25, $iTop, $sObservation);
            
            $iTop +=5;
        }
        
        $objPdf->SetFont('Arial', '', 7);
	$objPdf->SetXY(20, 225);
	$objPdf->MultiCell(188, 4.7, $sProductConformComments, 0);
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 6

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(6, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);
        //********* Main Header Starts *****************//
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 170, 13, 25.5);
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(171, 42, "Audit Code: {$sAuditCode}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 8, "Page 6 of {$iTotalPages}");

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
        //********* Main Header Ends *****************//
        $objPdf->SetFont('Arial', '', 6);
        
        if($iWorkmanshipResult == 'P')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 18.5, 56.2, 3);
        else if($iWorkmanshipResult == 'F')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 93, 56.2, 3);
        else
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 157, 56.2, 3);
        
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
	$fTop   = 76;
        
        $TotalMinor    = 0;
        $TotalMajor    = 0;
        $TotalCritical = 0;

        for($i = 0; $i < $iCount; $i ++)
	{
            $fTop += 4.50;
            
            $iCritical = $objDb->getField($i, "_Critical");
            $iMajor    = $objDb->getField($i, "_Major");
            $iMinor    = $objDb->getField($i, "_Minor");
            
            $TotalMinor    += $iMinor;
            $TotalMajor    += $iMajor;
            $TotalCritical += $iCritical;
            
            $sSQL2 = ("SELECT defect, code FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
            $objDb2->query($sSQL2);

            $sDefect     = $objDb2->getField(0, 0);		
            $sDefectCode = $objDb2->getField(0, 1);
            
            $objPdf->Text(14, $fTop, $sDefectCode);
            $objPdf->Text(28, $fTop, $sStyle);
            
            $objPdf->SetXY(46, ($fTop - 2.3));
            $objPdf->MultiCell(140, 2.2, $sDefect, 0);
            
            $objPdf->Text(171, $fTop, $iCritical);
            $objPdf->Text(182, $fTop, $iMajor);
            $objPdf->Text(193, $fTop, $iMinor);        
        }
        
        $objPdf->Text(171, 241, $TotalCritical);
	$objPdf->Text(182, 241, $TotalMajor);
        $objPdf->Text(193, 241, $TotalMinor);
        
        $objPdf->Text(171, 248, "0");
	$objPdf->Text(182, 248, $iAqlChart[$iTotalGmts]["2.5"]);
	$objPdf->Text(193, 248, $iAqlChart[$iTotalGmts]["4"]);
        
        $objPdf->SetFont('Arial', '', 7);
	$objPdf->SetXY(20, 254);
	$objPdf->MultiCell(188, 4.7, $sComments, 0);
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 7

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(7, '/MediaBox');

	$sSQL = "SELECT color, size, result FROM tbl_qa_report_samples WHERE audit_id='$Id'";
        $objDb->query($sSQL);
        
        $iCount = $objDb->getCount();
        $iTop = 72;
        
        for($i = 0; $i < $iCount; $i ++)
	{
            if($i==0 || $iTop > 230)
            {
                $iTop = 72;
                
                $objPdf->addPage("P", "A4", "pt");
                $objPdf->useTemplate($iTemplateId, 0, 0);
                //********* Main Header Starts *****************//
                $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 170, 13, 25.5);
                $objPdf->SetFont('Arial', '', 7);
                $objPdf->SetTextColor(50, 50, 50);

                $objPdf->Text(171, 42, "Audit Code: {$sAuditCode}");

                $objPdf->SetFont('Arial', '', 9);
                $objPdf->Text(11, 8, "Page 7 of {$iTotalPages}");

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
                //********* Main Header Ends *****************//

                if($iMeasurementResult == 'P')
                    $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 18.5, 56, 3);
                else if($iMeasurementResult == 'F')
                    $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 93, 56, 3);
                else
                    $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 157, 56, 3);

            }
            
            $sColor     = $objDb->getField($i, "color");
            $iSize      = $objDb->getField($i, "size");
            $sResult    = $objDb->getField($i, "result");
            
            $objPdf->Text(50, $iTop, $sColor);
            $objPdf->Text(140, $iTop, $iSize);            
            
            if($sResult == 'P')
                $objPdf->SetTextColor(0, 100, 0);
            else
                $objPdf->SetTextColor(255, 0, 0);
            
            $objPdf->Text(188, $iTop, ($sResult == 'P'?'Pass':'Fail'));
               
            $objPdf->SetTextColor(50, 50, 50);
            $iTop +=5;
        }
        
        $objPdf->SetFont('Arial', '', 7);
	$objPdf->SetXY(20, 240);
	$objPdf->MultiCell(188, 4.7, $sMeasurementComments, 0);
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 8
	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(8, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);
        //********* Main Header Starts *****************//
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 170, 13, 25.5);
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(171, 42, "Audit Code: {$sAuditCode}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 8, "Page 8 of {$iTotalPages}");

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
        //********* Main Header Ends *****************//
        
        if($iWeightConformResult == 'P')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 18.5, 56, 3);
        else if($iWeightConformResult == 'F')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 93, 56, 3);
        else
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 157, 56, 3);
                
        $sSQL = "SELECT * FROM tbl_qa_weight_conformity WHERE audit_id='$Id'";
        $objDb->query($sSQL);
        
        $iCount = $objDb->getCount();
        $iTop = 78;
        
        for($i = 0; $i < $iCount; $i ++)
	{
            $iNext      = 93;
            $sColor     = $objDb->getField($i, "color");
            $sResult    = $objDb->getField($i, "result");
            
            $objPdf->Text(30, $iTop, $sColor);
            
            $sSQL2 = "SELECT * FROM tbl_qa_weight_details WHERE audit_id='$Id' AND color LIKE '$sColor'";
            $objDb2->query($sSQL2);

            $iCount2 = $objDb2->getCount();
            for($j = 0; $j < $iCount2; $j ++)
            {
                $iWeight  = $objDb2->getField($j, "weight");                

                if($j == 0)
                    $objPdf->Text(108, $iTop, "160");
                
                $objPdf->Text($iNext + 30, $iTop, $iWeight);
                
                $iNext +=14.25;
            }
            
            if($sResult == 'P')
                $objPdf->SetTextColor(0, 100, 0);
            else
                $objPdf->SetTextColor(255, 0, 0);
            
            $objPdf->Text(191, $iTop, ($sResult == 'P'?'Pass':($sResult == 'F'?'Fail':'N/A')));
            
            $objPdf->SetTextColor(50, 50, 50);
            $iTop += 7;
        }
        
        $objPdf->SetFont('Arial', '', 7);
	$objPdf->SetXY(20, 237);
	$objPdf->MultiCell(188, 4.7, $sWeightConformComments, 0);
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 9
	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(9, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);
        //********* Main Header Starts *****************//
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 170, 13, 25.5);
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(171, 42, "Audit Code: {$sAuditCode}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 8, "Page 9 of {$iTotalPages}");

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
        //********* Main Header Ends *****************//
        
        if($iEanResult == 'P')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 18.5, 55.5, 3);
        else if($iEanResult == 'F')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 93, 55.5, 3);
        else
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 157, 55.5, 3);
        
        $sSQL = "SELECT * FROM tbl_qa_ean_codes WHERE audit_id='$Id'";
        $objDb->query($sSQL);
        
        $iCount = $objDb->getCount();
        $iTop = 71;
        
        for($i = 0; $i < $iCount; $i ++)
	{
            $iSerial    = $objDb->getField($i, "serial");
            $iSize      = $objDb->getField($i, "size_id");
            $sPosition  = $objDb->getField($i, "position");
            $sEanCode   = $objDb->getField($i, "code");
            $sResult    = $objDb->getField($i, "result");
            
            $objPdf->Text(14, $iTop, $iSerial);
            $objPdf->Text(40, $iTop, $sPosition);
            $objPdf->Text(112, $iTop, $sStyle);
            $objPdf->Text(140, $iTop, $iSize);
            $objPdf->Text(162, $iTop, $sEanCode);
            
            if($sResult == 'P')
                $objPdf->SetTextColor(0, 100, 0);
            else
                $objPdf->SetTextColor(255, 0, 0);
            
            $objPdf->Text(188, $iTop, ($sResult == 'P'?'Pass':'Fail'));
            
            $objPdf->SetTextColor(50, 50, 50);
            
            $iTop +=7;
        }
        
        if($sBarCodeFormat == 1)
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 55, 231.5, 3);
        else if($sBarCodeFormat == 2)
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 16, 231.5, 3);
        
        if($iScannerType != "")
            $objPdf->Text(80, 234, "DEVICE: [ {$iScannerType} ]");
        
        $objPdf->SetFont('Arial', '', 7);
	$objPdf->SetXY(20, 242);
	$objPdf->MultiCell(185, 4.7, $sEanComments, 0);
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 10

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(10, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);
        //********* Main Header Starts *****************//
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 170, 13, 25.5);
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(171, 42, "Audit Code: {$sAuditCode}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 8, "Page 10 of {$iTotalPages}");

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
        //********* Main Header Ends *****************//
        if($iPackaginResult == 'P')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 18, 55.5, 3);
        else if($iPackaginResult == 'F')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 92, 55.5, 3);
        else
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 156, 55.5, 3);
        
        $sSQL = "SELECT pd.defect, COUNT(1) as _Defects FROM tbl_packaging_defects pd, tbl_qa_packaging_defects qpd WHERE qpd.audit_id='$Id' AND qpd.defect_code_id=pd.id GROUP BY qpd.defect_code_id";
        $objDb->query($sSQL);
        
        $iTotalDefects = 0;
        $iCount = $objDb->getCount();
        $iTop = 72;
        
        for($i = 0; $i < $iCount; $i ++)
	{
            $sDefect    = $objDb->getField($i, "defect");
            $iDefects   = $objDb->getField($i, "_Defects");
            
            $iTotalDefects += $iDefects;
                    
            $objPdf->Text(13, $iTop, $i+1);
            $objPdf->Text(30, $iTop, $sDefect);
            $objPdf->Text(170, $iTop, $iDefects);
               
            $iTop +=5;
        }
        
        $objPdf->Text(70, 209, $iTotalDefects);
        $objPdf->Text(70, 216.5, $iTotalGmts);
        $objPdf->Text(70, 223, ceil($iTotalGmts*0.03));  
        
        $objPdf->SetFont('Arial', '', 7);
	$objPdf->SetXY(20, 233);
	$objPdf->MultiCell(185, 4.7, $sPackagingComments, 0);
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 11

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(11, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);
        //********* Main Header Starts *****************//
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 170, 13, 25.5);
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(171, 42, "Audit Code: {$sAuditCode}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 8, "Page 11 of {$iTotalPages}");

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
        //********* Main Header Ends *****************//
        if($iLabelingResult == 'P')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 18.2, 55, 3);
        else if($iLabelingResult == 'F')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 92.5, 55, 3);
        else
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 156.5, 55, 3);
        
        $sSQL = "SELECT pd.defect, COUNT(1) as _Defects FROM tbl_labeling_defects pd, tbl_qa_labeling_defects qpd WHERE qpd.audit_id='$Id' AND qpd.defect_code_id=pd.id GROUP BY qpd.defect_code_id";
        $objDb->query($sSQL);
        
        $iTotalDefects = 0;
        $iCount = $objDb->getCount();
        $iTop = 72;
        
        for($i = 0; $i < $iCount; $i ++)
	{
            $sDefect    = $objDb->getField($i, "defect");
            $iDefects   = $objDb->getField($i, "_Defects");
            
            $iTotalDefects += $iDefects;
                    
            $objPdf->Text(13, $iTop, $i+1);
            $objPdf->Text(30, $iTop, $sDefect);
            $objPdf->Text(170, $iTop, $iDefects);
               
            $iTop +=5;
        }
        
        $objPdf->Text(70, 209, $iTotalDefects);
        $objPdf->Text(70, 216.5, $iTotalGmts);
        $objPdf->Text(70, 223, ceil($iTotalGmts*0.03));  
        
        $objPdf->SetFont('Arial', '', 7);
	$objPdf->SetXY(20, 233);
	$objPdf->MultiCell(185, 4.7, $sLabelingComments, 0);
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 12

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(12, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);
        //********* Main Header Starts *****************//
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 170, 13, 25.5);
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(171, 42, "Audit Code: {$sAuditCode}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 8, "Page 12 of {$iTotalPages}");

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
        //********* Main Header Ends *****************//        
        if($iAssortmentResult == 'P')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 18.7, 57.5, 3);
        else if($iAssortmentResult == 'F')
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 93.2, 57.5, 3);
        else
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 157.2, 57.5, 3);
        
        $objPdf->Text(80, 208, $iWrongAssortmentCartons);
        $objPdf->Text(80, 215, $iTotalAssortmentCartons);
        $objPdf->Text(80, 222, ceil($iTotalAssortmentCartons*0.03));
                
        $objPdf->SetFont('Arial', '', 7);
	$objPdf->SetXY(20, 232);
	$objPdf->MultiCell(188, 4.7, $sAssortmentComments, 0);
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 13

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(13, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);
        //********* Main Header Starts *****************//
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 170, 13, 25.5);
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(171, 42, "Audit Code: {$sAuditCode}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 8, "Page 13 of {$iTotalPages}");

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
        //********* Main Header Ends *****************//
        
        $sSQL = "SELECT * FROM tbl_qa_master_cartons WHERE audit_id='$Id'";
        $objDb->query($sSQL);
        
        $iCount = $objDb->getCount();
        $iNext = 80;
        
        for($i = 0; $i < $iCount; $i ++)
	{
            $iGrossWeight           = $objDb->getField($i, "gross_weight");
            $iAssortmentLength      = $objDb->getField($i, "length");
            $iAssortmentWidth       = $objDb->getField($i, "width");
            $iAssortmentHeight      = $objDb->getField($i, "height");
            
            $objPdf->Text($iNext, 73, $iGrossWeight);
            $objPdf->Text($iNext, 78, $iAssortmentLength);
            $objPdf->Text($iNext, 83, $iAssortmentWidth);
            $objPdf->Text($iNext, 88, $iAssortmentHeight);
            
            $iNext += 22.5;
        }
        $objPdf->SetFont('Arial', '', 7);
	$objPdf->SetXY(20, 94);
	$objPdf->MultiCell(188, 4.7, $sAssortmentComments, 0);
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 14
        $iPageNo = 14;
        $sOldImageType = "";
        $Counter = 0;
        
        if (count($sAllImages) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
		$iTemplateId = $objPdf->importPage(14, '/MediaBox');

		$iPages = @ceil(count($sAllImages) / 9);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iPageNo ++)
		{
                    $objPdf->addPage("P", "A4", "pt");
                    $objPdf->useTemplate($iTemplateId, 0, 0);
                    //********* Main Header Starts *****************//
                    $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 170, 13, 25.5);
                    $objPdf->SetFont('Arial', '', 7);
                    $objPdf->SetTextColor(50, 50, 50);

                    $objPdf->Text(171, 42, "Audit Code: {$sAuditCode}");

                    $objPdf->SetFont('Arial', '', 9);
                    $objPdf->Text(11, 8, "Page $iPageNo of {$iTotalPages}");

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
                    //********* Main Header Ends *****************//
                        
			for ($j = 0; $j < 9 && $iIndex < count($sAllImages); $j ++, $iIndex ++)
			{
                                $sName      = @strtoupper($sAllImages[$iIndex]['image']);
                                $sImageType = @strtoupper($sAllImages[$iIndex]['type']);
                                
                                $iLeft = 15;
				$iTop  = 60;

				if ($j == 1 || $j == 4 || $j == 7)
					$iLeft = 79;
                                else if ($j == 2 || $j == 5 || $j == 8)
					$iLeft = 142;

				if ($j == 3 || $j == 4 || $j == 5)
					$iTop = 128;
                                else if ($j == 6 || $j == 7 || $j == 8)
					$iTop = 195.5;
                                
                                $sInfo = "";
                                $DefectLength = 0;
                                $Counter ++;
                                
                                if($sImageType != $sOldImageType)
                                    $Counter = 1;
                                
                                if($sImageType == 'D')
				{
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

                                   // $objPdf->SetFont('Arial', '', 6);
                                    
                                    if(strlen($sDefect)>15)
                                        $DefectLength = 1;
                                    
                                    $Counter = $sAllImages[$iIndex]['sample_no'];

                                    $sSection = "Section: Workmanship";
                                    $sInfo = "Type: {$sType}\n";
                                    $sInfo .= "Defect: {$sDefect}\n";
                                    $sInfo .= ("Area: ".getDbValue("area", "tbl_defect_areas", "id='$sAreaCode'")."\n");
                                }
                                else if($sImageType == 'L')
                                    $sSection = "Section: Lab Images\n";
                                else if($sImageType == 'P')
                                    $sSection = "Section: Packaging Images\n";
                                else if($sImageType == 'W')
                                {
                                    $sSerial    = @strtoupper($sAllImages[$iIndex]['serial']);
                                    $sPicColor  = @strtoupper($sAllImages[$iIndex]['color']); 
                                    
                                    $sSection = "Section: Weight Conformity\n";
                                    $sInfo = "Color: {$sPicColor}\n";
                                    
                                    $Counter = $sSerial;
                                }
                                else if($sImageType == 'AD' || $sImageType == 'M')
                                    $sSection = "Section: Additional Images/ Attachments \n";
                                else if($sImageType == 'PD')
                                    $sSection = "Section: Sales Packaging \n";
                                else if($sImageType == 'LD')
                                    $sSection = "Section: Sales Labeling \n";

                                $sOldImageType = $sImageType;
//                                else if($sImageType == 'M')
//                                    $sInfo = "Section: Miscellaneous\n";
                                
                                $objPdf->SetFont('Arial', 'B', 7);
                                $objPdf->Text($iLeft+5, ($iTop + 51.5), $sSection);

                                if($DefectLength == 1 && $sImageType == 'D')
                                    $objPdf->SetFont('Arial', '', 4);
                                else
                                    $objPdf->SetFont('Arial', '', 6);
                                
				$objPdf->SetXY($iLeft-3.5, ($iTop + 53));
				$objPdf->MultiCell(80, 3.6, $sInfo, 0, "L", false);
                                
                                $objPdf->SetFont('Arial', 'B', 10);
                                $objPdf->Text($iLeft+52, ($iTop + 59), $Counter);
                                 
                                $objPdf->Image($sAllImages[$iIndex]['image'], $iLeft, $iTop, 50, 45);      
			}                        
		}
	}	
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 15
/*
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
        $objPdf->MultiCell(125, 3, $iChildLabourDeadLine, 0, "L");*/
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 16
        /*if($sChildLabourResult != 'P')
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
        }*/
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 17
        /*if($sChildLabourResult != 'P')
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
        }*/
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 18

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(18, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);
        //********* Main Header Starts *****************//
        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 170, 12.7, 25.4);
        $objPdf->SetFont('Arial', '', 7);
        $objPdf->SetTextColor(50, 50, 50);

        $objPdf->Text(171, 42, "Audit Code: {$sAuditCode}");

        $objPdf->SetFont('Arial', '', 9);
        $objPdf->Text(11, 8, "Page {$iPageNo} of {$iTotalPages}");

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
        $iPageNo++;
        //********* Main Header Ends *****************//
         
        $objPdf->Text(26, 155, ":  ".$sSignatureInspector." / ".formatDate($sAuditDate));
        $objPdf->Text(128, 155, ":  ".$sSignatureManufacturer." / ".formatDate($sAuditDate));
        
        $objPdf->SetXY(12, 55);
        $objPdf->MultiCell(185, 3.0, $sSignatureComments);
        
        if($sInspectorSignature != "")
            $objPdf->Image($sInspectorSignature, 26, 112, 45);
        
        if($sManufactureSignature != "")
            $objPdf->Image($sManufactureSignature, 128, 112, 45);
        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 19
	$iPageCount   = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId  = $objPdf->importPage(19, '/MediaBox');

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

			$objPdf->addPage("P", "A4", "pt");
                        $objPdf->useTemplate($iTemplateId, 0, 0);
                        //********* Main Header Starts *****************//
                        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 170, 13, 25.5);
                        $objPdf->SetFont('Arial', '', 7);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(171, 42, "Audit Code: {$sAuditCode}");

                        $objPdf->SetFont('Arial', '', 9);
                        $objPdf->Text(11, 8, "Page {$iPageNo} of {$iTotalPages}");

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
                        $iPageNo++;
                        //********* Main Header Ends *****************//
			$sSize = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");

//			$objPdf->SetFont('Arial', '', 8);
			
                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->SetXY(132, 58);
			$objPdf->MultiCell(55, 3, $sColor, 0, "L");
                        $objPdf->Text(133, 63, "Size: ".$sSize);
			//$objPdf->SetXY(50, 55.3);
			//$objPdf->MultiCell(162, 4, "Qty/Size: [ ".$sQtyPerSize." ]", 0, "L");

			$objPdf->SetFont('Arial', '', 9);

			$sSQL = "SELECT ss.point_id, ss.specs, mp.tolerance as _Tolerance, mp.point_en as _Point
					 FROM tbl_hoh_style_specs ss, tbl_hoh_measurement_points mp
					 WHERE ss.point_id = mp.id AND (mp.style_id='$iStyle' OR mp.style_id IN ($sStyles)) AND ss.size_id='$iSize'
					 ORDER BY mp.id
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
				$objPdf->Text(13, (75.8 + ($i * 7.225)), ($i + 1));

				$objPdf->SetFont('Arial', '', 8);
				$objPdf->SetXY(20.5, (72.7 + ($i * 7.225)));
				$objPdf->MultiCell(85, 2.2, $sPoint, 0, "L");

				$objPdf->SetFont('Arial', '', 7);
                                $objPdf->Text(109, (75.8 + ($i * 7.225)), $sSpecs);
				$objPdf->Text(120, (75.8 + ($i * 7.225)), $sTolerance);
                                
                                $sResultList = array();

				for ($j = 1; $j <= $iSamplesChecked; $j ++)
				{                                        
					if ($sSizeFindings["{$j}-{$iPoint}"] != "" && strtolower($sSizeFindings["{$j}-{$iPoint}"]) != "ok" && $sSizeFindings["{$j}-{$iPoint}"] != "0" && $sSizeFindings["{$j}-{$iPoint}"] != "-")
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
							$objPdf->SetXY((123 + ($j * 10.5)), (73.1 + ($i * 7.225)));
							$objPdf->Cell(9.1, 4.415, $sSizeFindings["{$j}-{$iPoint}"], 0, 0, "C", true);
                                                        
                                                        if(@$sResultList["{$iPoint}"] == "")
                                                            $sResultList["{$iPoint}"] = "Pass";
                                                        
                                                        //pass
                                                        if($j == $iSamplesChecked)
                                                        {
                                                            $objPdf->SetTextColor(0, 100, 0);
                                                            if($sResultList["{$iPoint}"] == "Fail")
                                                                $objPdf->SetTextColor(255, 0, 0);
                                                    
                                                            $objPdf->Text(188.5, (75.8 + ($i * 7.225)), $sResultList["{$iPoint}"]);
                                                            $objPdf->SetTextColor(50, 50, 50);
                                                        }
						}
						
						else
						{							
							$objPdf->SetFillColor(255, 255, 0);
							$objPdf->SetXY((123 + ($j * 10.5)), (73.1 + ($i * 7.225)));
							$objPdf->Cell(9.1, 4.415, $sSizeFindings["{$j}-{$iPoint}"], 0, 0, "C", true);

                                                        if(@$sResultList["{$iPoint}"] == "Pass" || @$sResultList["{$iPoint}"] == "")
                                                            $sResultList["{$iPoint}"] = "Fail";
                                                        //fail
                                                        if($j == $iSamplesChecked)
                                                        {
                                                            $objPdf->SetTextColor(255, 0, 0);
                                                            if($sResultList["{$iPoint}"] == "Pass")
                                                                $objPdf->SetTextColor(0, 100, 0);
                                                    
                                                            $objPdf->Text(188.5, (75.8 + ($i * 7.225)), $sResultList["{$iPoint}"]);
                                                            $objPdf->SetTextColor(50, 50, 50);
                                                        }
                                                        
							$iOut ++;
						}
					}
					else
                                        {
						$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (125.5 + ($j * 10.5)), (73.1 + ($i * 7.225)), 4);
                                                
                                                if(@$sResultList["{$iPoint}"] == "")
                                                    $sResultList["{$iPoint}"] = "Pass";
                                                        
                                                //pass
                                                if($j == $iSamplesChecked)
                                                {
                                                    $objPdf->SetTextColor(0, 100, 0);
                                                    if($sResultList["{$iPoint}"] == "Fail")
                                                        $objPdf->SetTextColor(255, 0, 0);
                                                    
                                                    $objPdf->Text(188.5, (75.8 + ($i * 7.225)), $sResultList["{$iPoint}"]);
                                                    $objPdf->SetTextColor(50, 50, 50);                                                
                                                }
                                        }
				}
			}

			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(89, 254, ($iCount));//$iSamplesChecked
			$objPdf->Text(179, 254, $iOut);
		}
	}
        ///////////////////////////////////////////Page 20 ////////////////////////////////////////////////////////////////
        
        if(count($sAttachments) > 0)
        {
                $iPageCount   = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
                $iTemplateId  = $objPdf->importPage(20, '/MediaBox');
        
                $objPdf->addPage("P", "A4", "pt");
                $objPdf->useTemplate($iTemplateId, 0, 0);
                //********* Main Header Starts *****************//
                $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 170, 13, 25.5);
                $objPdf->SetFont('Arial', '', 7);
                $objPdf->SetTextColor(50, 50, 50);

                $objPdf->Text(171, 42, "Audit Code: {$sAuditCode}");

                $objPdf->SetFont('Arial', '', 9);
                $objPdf->Text(11, 8, "Page {$iPageNo} of {$iTotalPages}");

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
                $iPageNo++;
                //********* Main Header Ends *****************//
            
                $objPdf->SetFont('Arial', '', 9);
                $objPdf->Text(13, 50, "Additional Attachments");
                
                $iTop = 10;
                $Count = 1; 
                
                $objPdf->SetFont('Arial', '', 7);
                $objPdf->SetTextColor(6, 82, 195);

                foreach ($sAttachments as $iAttachment => $sAttachment)
                {
                    @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

                    if ($sAttachment != "" && @file_exists($sBaseDir.QUONDA_PICS_DIR.$sYear.'/'.$sMonth.'/'.$sDay.'/'.$sAttachment))
                    {
                        // Report Details
                        $objPdf->SetXY(15, $iTop);
                        $objPdf->Write(100, $Count." - ".$sAttachment, SITE_URL.QUONDA_PICS_DIR.$sYear.'/'.$sMonth.'/'.$sDay.'/'.$sAttachment);

                        $Count ++;
                        $iTop += 5;
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