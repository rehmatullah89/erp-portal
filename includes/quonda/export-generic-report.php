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

	if ($userId == 0 || !isset($userId))
		$userId = (int)$_SESSION['UserId'];
	
	$userLanguage = "en";
	
	if ($userId > 0)
		$userLanguage = getDbValue ("language", "tbl_users", "id='$userId'");
		

	$sSQL = "SELECT *,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
	                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor,
	                (SELECT report FROM tbl_reports WHERE id=tbl_qa_reports.report_id) AS _ReportType
	         FROM tbl_qa_reports
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	$iReportId          = $objDb->getField(0, "report_id");
	$sReportType        = $objDb->getField(0, "_ReportType");
	$sVendor            = $objDb->getField(0, "_Vendor");
	$sAuditor           = $objDb->getField(0, "_Auditor");
	$iPo                = $objDb->getField(0, "po_id");
	$iAdditionalPos     = $objDb->getField(0, "additional_pos");
	$sAdditionalStyles  = $objDb->getField(0, "additional_styles");
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
	$iFinishing         = $objDb->getField(0, "finishing");
	$iPacking           = $objDb->getField(0, "packing");
	$sFinalAuditDate    = $objDb->getField(0, "final_audit_date");
	$sComments          = $objDb->getField(0, "qa_comments");
	$iLine              = $objDb->getField(0, "line_id");
	$fDhu               = $objDb->getField(0, "dhu");
	$sInspectionType    = $objDb->getField(0, "inspection_type");
	$sMaker             = $objDb->getField(0, 'maker');
	$iBrand            	= $objDb->getField(0, 'brand_id');
	$sAQL 							= $objDb->getField(0, "aql");
	$iInspectedCartons	= $objDb->getField(0, "inspected_cartons");
	$iRejectedCartons	  = $objDb->getField(0, "rejected_cartons");
	$sSignaturesManufacturer	  = $objDb->getField(0, "signatures_manufacturer");
	$sSignaturesInspector	  = $objDb->getField(0, "signatures_inspector");

	$sColors = str_replace(",",", ",$sColors);

  @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

	$sSpecsSheets = array( );

	$brandAQL = getDbValue("aql","tbl_brands","id='$iBrand'");
	$brandInspectionLevel = getDbValue("inspection_level","tbl_brands","id='$iBrand'");

	$sAllowedSections = getDbValue ("sections", "tbl_reports", "id = '$iReportId'");
	$AllowedSections = explode(",", $sAllowedSections);

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

  $sSQL2 = "SELECT qa.location, u.name AS _Auditor,v.latitude AS v_latitude,v.longitude AS v_longitude,qa.latitude AS qa_latitude,qa.longitude AS qa_longitude, v.vendor 
  FROM tbl_qa_reports qa, tbl_users u, tbl_vendors v, tbl_brands b 
  WHERE u.id=qa.user_id AND v.id=qa.vendor_id AND qa.id='$Id'";

  $objDb->query($sSQL2);

	$sAuditorName = $objDb->getField(0, "_Auditor");
	$sVendorLatitude = $objDb->getField(0, "v_latitude");
	$sVendorLongitude = $objDb->getField(0, "v_longitude");
	$sAuditLatitude = $objDb->getField(0, "qa_latitude");
	$sAuditLongitude = $objDb->getField(0, "qa_longitude");
	$sLocation          = $objDb->getField(0, "location");
	$sVendorName          = $objDb->getField(0, "vendor");

	$sLatitude = $sAuditLatitude;
	$sLongitude = $sAuditLongitude;

	$sBrandImage = getDbValue ("logo_png", "tbl_brands", "id = '$iBrand'");
        
        if($sBrandImage != "")
	{
            //$sBrandImagePath = "{$sBrandImagePath}";
	    $sBrandImagePath = "png/{$sBrandImage}";
        }
	else
        {
            $sBrandImage = getDbValue ("logo_jpg", "tbl_brands", "id = '$iBrand'");
            $sBrandImagePath = "jpg/{$sBrandImage}";
        }

	$PrettyAuditDate = date('l, d-M-Y ',strtotime($sAuditDate));

	$allPos = $iPo.','.$iAdditionalPos;
	$allPos = rtrim($allPos,",");

	$allStyles = $iStyle.','.$sAdditionalStyles;
	$allStyles = rtrim($allStyles,",");

	$allStylesArray = explode(",", $allStyles);
	$allStylesArray = array_unique($allStylesArray);
	$allStyles = implode(", ", $allStylesArray);

	$sAllPos = getDbValue ("GROUP_CONCAT(CONCAT(order_no, ' ', order_status) SEPARATOR ', ')", "tbl_po", "id IN ($allPos)");
	$sCumulativeOrderQuantity = getDbValue ("sum(quantity)", "tbl_po", "id IN ($allPos)");

	$sCumolativeLotSize = getDbValue ("sum(lot_size)", "tbl_qa_lot_sizes", "audit_id = '$Id'");
	$sCumolativeSampleSize = getDbValue ("sum(sample_size)", "tbl_qa_lot_sizes", "audit_id = '$Id'");
	
	if($sCumolativeSampleSize == "")
		$sCumolativeSampleSize = $iTotalGmts;

	// $sStyles = getDbValue ("GROUP_CONCAT(CONCAT(style_name, '-',style) SEPARATOR ', ')", "tbl_styles", "id IN ($allStyles)");
	$sStyles = getDbValue ("GROUP_CONCAT(style SEPARATOR ', ')", "tbl_styles", "id IN ($allStyles)");
	$sStylesName = getDbValue ("GROUP_CONCAT(style_name SEPARATOR ', ')", "tbl_styles", "id IN ($allStyles)");

	$allDestinations = getDbValue("GROUP_CONCAT(destinations)","tbl_po", "id IN ($allPos)");
	$allDestinationArray = @explode(",", $allDestinations);
	$allDestinationArray = @array_unique($allDestinationArray);
	$allDestinations = @implode(",", $allDestinationArray);

	$allDestinationString = getDbValue("GROUP_CONCAT(destination)","tbl_destinations","id IN($allDestinations)");

	$productQuantityComments = getDbValue("product_quantity_comments","tbl_qa_report_details","audit_id='$Id'");




	$sSQL = "SELECT size FROM tbl_sizes WHERE id IN ($sSizes) ORDER BY position";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sSizeTitles .= (", ".$objDb->getField($i, 0));

		$sSizeTitles = substr($sSizeTitles, 2);
	}	

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


	$sSQL = "SELECT style, style_name, design_name, design_no, brand_id, sub_brand_id, (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand FROM tbl_styles WHERE id='$iStyle'";
	$objDb->query($sSQL);

	$sStyle       = $objDb->getField(0, "style");
	$sStyleName   = $objDb->getField(0, "style_name");
	$sDesignName  = $objDb->getField(0, "design_name");
	$sDesignNo    = $objDb->getField(0, "design_no");
	$iParent      = $objDb->getField(0, "brand_id");
	$iBrand       = $objDb->getField(0, "sub_brand_id");
	$sBrand       = $objDb->getField(0, "_Brand");
	
	
	$sSizeTitles  = "";

	$sSQL = "SELECT size FROM tbl_sizes WHERE id IN ($sSizes) ORDER BY position";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sSizeTitles .= (", ".$objDb->getField($i, 0));

		$sSizeTitles = substr($sSizeTitles, 2);
	}	

	$sSQL = "SELECT id, picture, pictures FROM tbl_qa_report_defects WHERE audit_id = '$Id'";
	$objDb->query($sSQL);
	$iCount = $objDb->getCount( );

	$allImagesString = "";
	$sDefects = array();

	if($iCount > 0) {

		for($i=0; $i<$iCount; $i++) {

			$sDefectId     = $objDb->getField($i, "id");
			$sDBPicture     = $objDb->getField($i, "picture");
			$sDBPictures    = $objDb->getField($i, "pictures");

			$uniqueValue = $sDefectId."|-|".$sDBPicture;

			array_push($sDefects, $uniqueValue);
			
			if($sDBPictures != ""){

				$additionalImages = explode(",", $sDBPictures);

				foreach ($additionalImages as $additionalImage) {

					$uniqueAValue = $sDefectId."|-|".$additionalImage;

					array_push($sDefects, $uniqueAValue);
				}
			}
		}
	}

  $sSQL = "SELECT * FROM tbl_qa_report_images WHERE audit_id = '$Id' AND (type='M' OR type='MI') AND image LIKE '%.jpg'";

	$objDb->query($sSQL);
	$sMiscPictures = array();
	$iCountMisc = $objDb->getCount( );

	if ($iCountMisc > 0)
	{
		for($i=0; $i<$iCountMisc; $i++){

			$sPicture = $objDb->getField($i, 'image');
			$sPictureURL = "";

			if(@strpos($sPicture, '.pdf') !== false ||  @strpos($sPicture, '.PDF') !== false) {
				continue;
			}

			if($sPicture != "" && file_exists($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sPicture)){
				
				$sPictureURL = $sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sPicture;

				array_push($sMiscPictures, $sPictureURL);
			}

		}
	}

	$sMisc = count($sMiscPictures);

  $sSQL = "SELECT * FROM tbl_qa_report_images WHERE audit_id = '$Id' AND type='P' AND image LIKE '%.jpg'";

	$objDb->query($sSQL);
	$sPackPictures = array();
	$iCountPack = $objDb->getCount( );

	if ($iCountPack > 0)
	{
		for($i=0; $i<$iCountPack; $i++){

			$sPicture = $objDb->getField($i, 'image');
			$sPictureURL = "";

			if(@strpos($sPicture, '.pdf') !== false ||  @strpos($sPicture, '.PDF') !== false) {
				continue;
			}

			if($sPicture != "" && file_exists($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sPicture)){
				
				$sPictureURL = $sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sPicture;

				array_push($sPackPictures, $sPictureURL);
			}

		}
	}

	$sPacking = count($sPackPictures);

	$sSQL = "SELECT qrs.size_id FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
                       WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id 
                       GROUP BY qrs.size_id";
	$objDb->query($sSQL);
	$measurementSheetsCount = $objDb->getCount( );

	$sSQL = "SELECT * FROM tbl_qa_lot_sizes WHERE audit_id = '$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$DQOPReminder = $iCount%6;
	$DQOPPages = 0;

	$DQOPCount = $iCount-$DQOPReminder;
	
	if($DQOPCount > 0){

		$DQOPPages = $DQOPCount/6;
	}

	if($DQOPReminder != 0 && $DQOPReminder <6){
		$DQOPPages++;
	}

$sDefectTypesList = getList("tbl_defect_types", "id", "type", "id IN (SELECT DISTINCT(type_id) FROM tbl_defect_codes WHERE report_id = '$iReportId')");

$DCLLinesHeight = 0;

foreach ($sDefectTypesList as $id => $type) {
  
  $loopLenght = 20;

  $sDefectCodes = getList("tbl_defect_codes dc, tbl_defect_types dt ", "dc.code", "dc.defect", "dc.type_id = dt.id AND dc.type_id = '$id' AND dc.report_id = '$iReportId'");

  $defectCodeLength = count($sDefectCodes) * 3.6;
  $DCLLinesHeight += $defectCodeLength+$loopLenght; 
  
}

$DCLPages = @ceil($DCLLinesHeight / 350);

	//////////////////////////////////////////////////////Page#1//////////////////////////////////////////////////////////////


	$objPdf = new FPDI( );
	$objPdf->setPrintHeader(false);
	$objPdf->setPrintFooter(false);
	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/qa-report.pdf");

	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

// Page 1
	$iCurrentPage = 0;

	@list($iMajorDefectsAllowed,$iMinorDefectsAllowed) = getAqlDefects($iTotalGmts, $sAQL, $iReportId);
	// $Id = '506534';

	if($userLanguage != 'en'){

		$defectField = "dc.defect, dt.type, dc.defect_".$userLanguage.", dt.type_".$userLanguage;

	} else {

		$defectField = "dc.defect, dt.type";
	}

  $sSQL2 = "SELECT qad.code_id, qad.sample_no, qad.defects, qad.area_id, qad.picture,
          dc.type_id, dc.code, qad.nature, $defectField 
          FROM tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
          WHERE qad.audit_id='$Id' AND qad.code_id=dc.id AND dc.type_id=dt.id
          GROUP BY qad.code_id,qad.area_id
          ORDER BY dt.type,qad.code_id DESC";

	$objDb2->query($sSQL2);

	$iCount2 = $objDb2->getCount( );

  $criticalCount = 0;
  $majorCount = 0;
  $minorCount = 0;
  $calculateDefectiveGarments = array();

  $previousDefectType = "";
  $totalLines = "";
  $defectsPerPageCounter = 0;
  $totalDefectsPerPage = array();

  for($i=0; $i<$iCount2; $i++) {

			$iArea     = $objDb2->getField($i, "area_id");
			$iCode     = $objDb2->getField($i, "code_id");
			$iSampleNo = $objDb2->getField($i, "sample_no");

			if($userLanguage != 'en'){

	      $sDefect   = $objDb2->getField($i, "defect_{$userLanguage}");
	      $sType     = $objDb2->getField($i, "type_{$userLanguage}");
				
				if($sDefect == "" || $sType == ""){

		      $sDefect     = $objDb2->getField($i, "defect");
		      $sType     = $objDb2->getField($i, "type");
				}

			} else {

	      $sDefect     = $objDb2->getField($i, "defect");
	      $sType     = $objDb2->getField($i, "type");
			}

			if($previousDefectType != $sType){

				$previousDefectType = $sType;
				$totalLines++;
			}

			if(strlen($sDefect) > 75){
				$totalLines = $totalLines+2;
			} else {
				$totalLines++;
			}

			if($totalLines <= 19){
				$defectsPerPageCounter++;
			}

			if($totalLines == 19 || $i+1 == $iCount2){
				array_push($totalDefectsPerPage, $defectsPerPageCounter);
				$defectsPerPageCounter = 0;
				$totalLines = 0;
			}

      $sSQL3 = "SELECT 
      					(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id='$Id' AND code_id='$iCode' AND area_id = '$iArea' AND nature='0') AS _MinorDefects,
								(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id='$Id' AND code_id='$iCode' AND area_id = '$iArea' AND nature='1') AS _MajorDefects,
								(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id='$Id' AND code_id='$iCode' AND area_id = '$iArea' AND nature='2') AS _CriticalDefects 
								FROM tbl_qa_report_defects qad 
								WHERE  qad.audit_id='$Id' AND qad.code_id='$iCode' AND qad.area_id = '$iArea'
								GROUP BY qad.code_id";

			$objDb3->query($sSQL3);

			$CR = $objDb3->getField(0, "_CriticalDefects");
			$MJ = $objDb3->getField(0, "_MajorDefects");
			$MI = $objDb3->getField(0, "_MinorDefects");

			$criticalCount = $criticalCount + $CR;
			$majorCount = $majorCount + $MJ;
			$minorCount = $minorCount + $MI;

      if(!in_array($iSampleNo, $calculateDefectiveGarments)) {
        array_push($calculateDefectiveGarments, $iSampleNo);
      } 
	    	
  }

	  
	$countDefectiveGarments = count($calculateDefectiveGarments);

	// $DRInPercentage = ($countDefectiveGarments/$iTotalGmts)*100;
	// $DRInPercentage = round($DRInPercentage,2);
	$DRInPercentage = $fDhu;

	$defectsCountString = "Cr:(".$criticalCount.") Mj:(".$majorCount.") Mi:(".$minorCount.")";

	$workManPages = count($totalDefectsPerPage);
	$defectFound = 0;
	
	if($workManPages > 0){
		$defectFound = 1;
	}

  $sAttachments = getList("tbl_qa_report_images", "id", "UPPER(image)", "audit_id='$Id' AND image NOT LIKE '%.jpg'");

  $sAttachmentCount = 0;
  
  if(count($sAttachments) > 0)
  	$sAttachmentCount = 1;

	$iTotalPages  = 4+$DQOPPages+$workManPages;
	$iTotalPages += count($sSpecsSheets);
	$iTotalPages += @ceil($sPacking / 6);
	$iTotalPages += @ceil(count($sDefects) / 4);
	$iTotalPages += @ceil($sMisc / 6);
	$iTotalPages += $DCLPages;
	$iTotalPages += $sAttachmentCount;

	if(in_array('12', $AllowedSections)){

		$iTotalPages += $measurementSheetsCount;
		$iTotalPages++;

	}

	$PAndLDefects = array();

  $sSQL3 = "SELECT * FROM tbl_qa_labeling_defects WHERE audit_id='$Id'";
  $objDb3->query($sSQL3);

  $iLabelingCount = $objDb3->getCount( );

  if($iLabelingCount > 0){

  	for($r=0; $r<$iLabelingCount; $r++) {

			$iDefectCodeId = $objDb3->getField($r, "defect_code_id");
			$sPicture = $objDb3->getField($r, "picture");

			array_push($PAndLDefects, array("code_id"=>$iDefectCodeId, "picture"=>$sPicture, "type"=>"labeling"));

  	}
  }


  $sSQL3 = "SELECT * FROM tbl_qa_packaging_defects WHERE audit_id='$Id'";
  $objDb3->query($sSQL3);

  $iPackagingCount = $objDb3->getCount( );

  if($iPackagingCount > 0){

  	for($r=0; $r<$iPackagingCount; $r++) {

			$iDefectCodeId = $objDb3->getField($r, "defect_code_id");
			$sPicture = $objDb3->getField($r, "picture");

			array_push($PAndLDefects, array("code_id"=>$iDefectCodeId, "picture"=>$sPicture, "type"=>"packaging"));

  	}
  }

	if(count($PAndLDefects) > 0){

		$iTotalPages += @ceil(count($PAndLDefects) / 4);
	}

// /////////////////////////////////////////////////PAGE 1 - WORKMANSHIP DEFECTS ///////////////////////// 

	$defectCounter = 1;

	for($j=0; $j<count($totalDefectsPerPage); $j++) {

		$iCurrentPage++;

		if($j == 0){
			$startValue = 0;
			$endValue = $totalDefectsPerPage[$j]-1;
		} else {
			$startValue = $endValue+1;
			$endValue = $endValue+$totalDefectsPerPage[$j]+1;
		}

		$iTemplateId = $objPdf->importPage(1, '/MediaBox');
		$objPdf->addPage("P", "A4");
		$objPdf->useTemplate($iTemplateId, 0, 0);
		
		$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178.7, 9.5, 23);

		$objPdf->SetFont('Arial', '', 10);
		$objPdf->SetTextColor(255, 255, 255);

		$objPdf->Text(161, 19, "{$sAuditCode}");
		$objPdf->Text(66.5, 19, "{$sReportType}");

		$objPdf->SetFont('Arial', '', 9);
		$objPdf->SetTextColor(169, 169, 169);
		$objPdf->Text(162.5, 30.2, "{$iCurrentPage} of {$iTotalPages}");

		$objPdf->Image(($sBaseDir.BRANDS_IMG_DIR."{$sBrandImagePath}"), 8, 6.1, 29);

		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(0, 0, 0);

		$objPdf->Text(26, 41.5, $sVendor);
		$objPdf->Text(115, 41.5, $PrettyAuditDate);
		$objPdf->Text(170.5, 41.5, $sAuditor);

		$objPdf->Text(52, 47.5, $sCumulativeOrderQuantity);
		$objPdf->Text(113, 47.5, $sCumolativeLotSize);
		$objPdf->Text(181, 47.5, $sCumolativeSampleSize);

		$objPdf->Text(159.5, 124, $brandAQL);
		$objPdf->SetFont('Arial', '', 7);

		// production status

		$sStartTime = date("g:i a", strtotime($sStartTime));
		$sEndTime = date("g:i a", strtotime($sEndTime));

		$fKnitted = ($fKnitted>0)?$fKnitted:"N/A";
		$fDyed = ($fDyed>0)?$fDyed:"N/A";
		$iCutting = ($iCutting>0)?$iCutting:"N/A";
		$iSewing = ($iSewing>0)?$iSewing:"N/A";
		$iFinishing = ($iFinishing>0)?$iFinishing:"N/A";
		$iPacking = ($iPacking>0)?$iPacking:"N/A";
		$sFinalAuditDateText = ($sFinalAuditDate == '0000-00-00')?"Not Applicable":date('l, d-M-Y ',strtotime($sFinalAuditDate));

		$objPdf->Text(58, 139, $fKnitted);
		$objPdf->Text(85, 139, $fDyed);
		$objPdf->Text(110, 139, $iCutting);
		$objPdf->Text(137, 139, $iSewing);
		$objPdf->Text(163, 139, $iFinishing);
		$objPdf->Text(189, 139, $iPacking);

		$objPdf->Text(78, 144.5, $sStartTime);
		$objPdf->Text(78, 151, $sEndTime);
		$objPdf->Text(170, 144.5, $sFinalAuditDateText);

		$sAuditResultText = "";

		if($sAuditResult == "P"){
			$objPdf->SetFillColor(60,183,117);
			$sAuditResultText = "PASS";
		} else if($sAuditResult == "F"){
			$objPdf->SetFillColor(231,111,81);
			$sAuditResultText = "FAIL";
		} else if($sAuditResult == "H"){
			$objPdf->SetFillColor(236,170,65);
			$sAuditResultText = "HOLD";
		}

		$objPdf->rect(49.1, 120.2, 76.9, 11.4,'F');
		$objPdf->SetFont('Arial', 'B', 10);
		$objPdf->SetTextColor(255, 255, 255);		
		$objPdf->Text(81, 124, $sAuditResultText);  

		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(50, 50, 50);	

		$objPdf->Text(45, 247.5, $iTotalGmts);
		$objPdf->Text(80, 247.5, $iGmtsDefective);
		$objPdf->Text(115, 247.5, $iMajorDefectsAllowed);
		$objPdf->Text(141, 247.5, $defectsCountString);

		$dhu = ($iGmtsDefective/$iTotalGmts)*100;
		$dhu = round($dhu,2);
		$objPdf->Text(184, 247.5, $dhu);

		$objPdf->Text(45, 259, $iInspectedCartons);
		$objPdf->Text(80, 259, $iRejectedCartons);

		$iDefectedCartons = ($iRejectedCartons/$iInspectedCartons)*100;
		$objPdf->Text(115, 259, $iDefectedCartons);
		$objPdf->Text(149, 259, 0);
		$objPdf->Text(184, 259, '0');

		$objPdf->Text(35, 83, $allDestinationString);
		$objPdf->Text(183, 83, $iTotalCartons);

	  $sAuditStage = strtoupper($sAuditStage);

		$objPdf->Text(156, 88.2, $sAuditStage);
		$objPdf->Text(62, 88.2, $sAuditStatus);

		$objPdf->setCellHeightRatio(2);

		if(strlen($sAllPos) <= 230){
			$objPdf->SetFont('Arial', '', 7);
		} else if(strlen($sAllPos) <= 272){
			$objPdf->setCellHeightRatio(2.5);
			$objPdf->SetFont('Arial', '', 6);
		} else if(strlen($sAllPos) <= 320){
			$objPdf->setCellHeightRatio(3);
			$objPdf->SetFont('Arial', '', 5);
		} else {
			$objPdf->setCellHeightRatio(1.15);
			$objPdf->SetFont('Arial', '', 5);			
		}

	  $objPdf->SetXY(21, 50.5);
	  $objPdf->MultiCell(175, 5, $sAllPos, 0, "L");
		$objPdf->SetFont('Arial', '', 7);
		$objPdf->setCellHeightRatio(2);

		if(strlen($sStyles) <= 230){
			$objPdf->SetFont('Arial', '', 7);
		} else if(strlen($sStyles) <= 272){
			$objPdf->setCellHeightRatio(2.5);
			$objPdf->SetFont('Arial', '', 6);
		} else if(strlen($sStyles) <= 320){
			$objPdf->setCellHeightRatio(3);
			$objPdf->SetFont('Arial', '', 5);
		} else {
			$objPdf->setCellHeightRatio(1.15);
			$objPdf->SetFont('Arial', '', 5);			
		}

	  $objPdf->SetXY(26, 61);
	  $objPdf->MultiCell(175, 5, $sStyles, 0, "L");
		$objPdf->SetFont('Arial', '', 7);
		$objPdf->setCellHeightRatio(2);

		if(strlen($sStylesName) <= 230){
			$objPdf->SetFont('Arial', '', 7);
		} else if(strlen($sStylesName) <= 272){
			$objPdf->setCellHeightRatio(2.5);
			$objPdf->SetFont('Arial', '', 6);
		} else if(strlen($sStylesName) <= 320){
			$objPdf->setCellHeightRatio(3);
			$objPdf->SetFont('Arial', '', 5);
		} else {
			$objPdf->setCellHeightRatio(1.15);
			$objPdf->SetFont('Arial', '', 5);			
		}

	  $objPdf->SetXY(32, 71.5);
	  $objPdf->MultiCell(175, 5, $sStylesName, 0, "L");
		$objPdf->SetFont('Arial', '', 7);
		$objPdf->setCellHeightRatio(2);

		if(strlen($sColors) <= 230){
			$objPdf->SetFont('Arial', '', 7);
		} else if(strlen($sColors) <= 272){
			$objPdf->setCellHeightRatio(2.5);
			$objPdf->SetFont('Arial', '', 6);
		} else if(strlen($sColors) <= 320){
			$objPdf->setCellHeightRatio(3);
			$objPdf->SetFont('Arial', '', 5);
		} else {
			$objPdf->setCellHeightRatio(1.15);
			$objPdf->SetFont('Arial', '', 5);			
		}

		$objPdf->SetXY(26.5, 92.5);
		$objPdf->MultiCell(175, 5, $sColors, 0, "L");
		$objPdf->SetFont('Arial', '', 7);
		$objPdf->setCellHeightRatio(2);

		if(strlen($sSizeTitles) <= 230){
			$objPdf->SetFont('Arial', '', 7);
		} else if(strlen($sSizeTitles) <= 272){
			$objPdf->setCellHeightRatio(2.5);
			$objPdf->SetFont('Arial', '', 6);
		} else if(strlen($sSizeTitles) <= 320){
			$objPdf->setCellHeightRatio(3);
			$objPdf->SetFont('Arial', '', 5);
		} else {
			$objPdf->setCellHeightRatio(1.15);
			$objPdf->SetFont('Arial', '', 5);			
		}

		$objPdf->SetXY(21, 103);
		$objPdf->MultiCell(175, 5, $sSizeTitles, 0, "L");
		$objPdf->SetFont('Arial', '', 7);

		$objPdf->setCellHeightRatio(1);
		$objPdf->SetXY(25, 164.5);

		$previousDefectType = "";
		$totalDefects = 0;

		if($defectFound == 1){

				$multiline = false;
				$newType = false;

			for($i=$startValue; $i<=$endValue; $i++) {

				// if($defectCounter > $iCount2){
				// 	break;
				// }
				
	      $iSampleNo = $objDb2->getField($i, "sample_no");
	      $iDefects  = $objDb2->getField($i, "defects");
	      $iArea     = $objDb2->getField($i, "area_id");
	      $sPicture  = $objDb2->getField($i, "picture");
	      $iType     = $objDb2->getField($i, "type_id");
	      $sCode   = $objDb2->getField($i, "code");
	      $iNature   = $objDb2->getField($i, "nature");
	      $iCode     = $objDb2->getField($i, "code_id");

				if($userLanguage != 'en'){

		      $sDefect   = $objDb2->getField($i, "defect_{$userLanguage}");
		      $sType     = $objDb2->getField($i, "type_{$userLanguage}");
					$sArea = getDbValue("area_{$userLanguage}","tbl_defect_areas","id = '$iArea'");

					if($sDefect == ""){
			      $sDefect = $objDb2->getField($i, "defect");
					}
					if($sType == ""){
			      $sType = $objDb2->getField($i, "type");
					}
					if($sArea == ""){
			      $sArea = getDbValue("area","tbl_defect_areas","id = '$iArea'");
					}

				} else {

		      $sDefect     = $objDb2->getField($i, "defect");
		      $sType     = $objDb2->getField($i, "type");
		      $sArea = getDbValue("area","tbl_defect_areas","id = '$iArea'");
				}

	      if($previousDefectType != $sType){

	      	if($multiline){
	      		$y = $objPdf->GetY()-1.2;
	      	} else {
	      		$y = $objPdf->GetY()-0.4;
	      	}
					
					$objPdf->SetFont('Arial', 'B', 7);

					$objPdf->SetXY(25, $y);
					$objPdf->MultiCell(75, 3.5, $sType, 0, "L");	

					$previousDefectType = $sType;
					$newType = true;
					$multiline = false;

	      } else {

	      	if($multiline) {

						$y = $objPdf->GetY();
						$objPdf->SetY($y);	   
						
						$multiline = false;   		
	      	} else {
						$y = $objPdf->GetY();
						$objPdf->SetY($y-0.4);	      		
	      	}

	      }

				$objPdf->SetFont('Arial', '', 6);
				$y = $objPdf->GetY();

				if($newType){
					$y = $y+0.3;
				}

				if(strlen($sDefect) > 75){
					$objPdf->setCellHeightRatio(2);
					$objPdf->SetXY(28, $y-1.5);
					$objPdf->MultiCell(74, 3.4, $sDefect, 0, "L");
					$multiline = true;	
				} else {
					$objPdf->SetXY(28, $y);
					$objPdf->MultiCell(74, 3.4, $sDefect, 0, "L");	
				}
      
				$objPdf->setCellHeightRatio(1);

				if($multiline){
					$y = $y-0.2;
				}
				$objPdf->SetXY(11, $y);
				$objPdf->MultiCell(75, 3.4, $sCode, 0, "L");

				$objPdf->SetXY(102, $y);
				$objPdf->MultiCell(75, 3.4, $sArea, 0, "L");

				$objPdf->SetFont('Arial', '', 7);
	      
	      switch ($iNature)
	      {
	        case 0 : $minorCount++; $sNature="Minor";  break;         
	        case 1 : $majorCount++; $sNature="Major";  break;
	        case 2 : $criticalCount++; $sNature="Critical"; break;
	      }      

	      $sSQL3 = "SELECT 
	      					(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id='$Id' AND code_id='$iCode' AND area_id = '$iArea' AND nature='0') AS _MinorDefects,
									(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id='$Id' AND code_id='$iCode' AND area_id = '$iArea' AND nature='1') AS _MajorDefects,
									(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id='$Id' AND code_id='$iCode' AND area_id = '$iArea' AND nature='2') AS _CriticalDefects 
									FROM tbl_qa_report_defects qad 
									WHERE  qad.audit_id='$Id' AND qad.code_id='$iCode' AND qad.area_id = '$iArea'
									GROUP BY qad.code_id";

				$objDb3->query($sSQL3);

				$CR = $objDb3->getField(0, "_CriticalDefects");
				$MJ = $objDb3->getField(0, "_MajorDefects");
				$MI = $objDb3->getField(0, "_MinorDefects");

				$totalDefects += $CR;
				$totalDefects += $MJ;
				$totalDefects += $MI;

				$objPdf->setCellHeightRatio(0.8);

				$objPdf->SetXY(158, $y);
				$objPdf->MultiCell(75, 3.4, $CR, 0, "L");
				
				$objPdf->SetXY(176, $y);
				$objPdf->MultiCell(75, 3.4, $MJ, 0, "L");

				$objPdf->SetXY(194, $y);
				$objPdf->MultiCell(75, 3.4, $MI, 0, "L");

				$objPdf->setCellHeightRatio(1);

			if($multiline){

				$y = $objPdf->GetY();
				$objPdf->SetY($y+4.7);
	
			} else {
				$y = $objPdf->GetY();
				$objPdf->SetY($y+0.4);				
			}
				// $defectCounter++;           
			}
		}

		$objPdf->SetFont('Arial', '', 10);
		$objPdf->Text(175, 235, $totalDefects);

	  $countDefectiveGarments = count($calculateDefectiveGarments);

	  // $DRInPercentage = ($countDefectiveGarments/$iTotalGmts)*100;
	  // $DRInPercentage = round($DRInPercentage,2);
	   $DRInPercentage = $fDhu;
	}

// /////////////////////////////////////////////////PAGE 2 - OVERALL ANALYTICS ///////////////////////// 

	$iTemplateId = $objPdf->importPage(2, '/MediaBox');

	$iCurrentPage ++;

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178.7, 9.5, 23);

	$objPdf->SetFont('Arial', '', 10);
	$objPdf->SetTextColor(255, 255, 255);

	$objPdf->Text(161, 19.4, "{$sAuditCode}");
	$objPdf->Text(66.5, 19.4, "{$sReportType}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->SetTextColor(169, 169, 169);
	$objPdf->Text(162.5, 30.5, "{$iCurrentPage} of {$iTotalPages}");

	$objPdf->Image(($sBaseDir.BRANDS_IMG_DIR."{$sBrandImagePath}"), 8, 6.1, 29);

	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(0, 0, 0);

	$objPdf->Text(26, 41.8, $sVendor);
	$objPdf->Text(115, 41.8, $PrettyAuditDate);
	$objPdf->Text(170.5, 41.8, $sAuditor);

	$objPdf->Text(52, 47.8, $sCumulativeOrderQuantity);
	$objPdf->Text(113, 47.8, $sCumolativeLotSize);
	$objPdf->Text(181, 47.8, $sCumolativeSampleSize);

	$objPdf->Text(35, 83.3, $allDestinationString);
	$objPdf->Text(183, 83.3, $iTotalCartons);

  $sAuditStage = strtoupper($sAuditStage);

	$objPdf->Text(156, 88.5, $sAuditStage);
	$objPdf->Text(62, 88.5, $sAuditStatus);

	$objPdf->setCellHeightRatio(2);

	if(strlen($sAllPos) <= 230){
		$objPdf->SetFont('Arial', '', 7);
	} else if(strlen($sAllPos) <= 272){
		$objPdf->setCellHeightRatio(2.5);
		$objPdf->SetFont('Arial', '', 6);
	} else if(strlen($sAllPos) <= 320){
		$objPdf->setCellHeightRatio(3);
		$objPdf->SetFont('Arial', '', 5);
	} else {
		$objPdf->setCellHeightRatio(1.15);
		$objPdf->SetFont('Arial', '', 5);			
	}

  $objPdf->SetXY(21, 50.5);
  $objPdf->MultiCell(175, 5, $sAllPos, 0, "L");
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->setCellHeightRatio(2);

	if(strlen($sStyles) <= 230){
		$objPdf->SetFont('Arial', '', 7);
	} else if(strlen($sStyles) <= 272){
		$objPdf->setCellHeightRatio(2.5);
		$objPdf->SetFont('Arial', '', 6);
	} else if(strlen($sStyles) <= 320){
		$objPdf->setCellHeightRatio(3);
		$objPdf->SetFont('Arial', '', 5);
	} else {
		$objPdf->setCellHeightRatio(1.15);
		$objPdf->SetFont('Arial', '', 5);			
	}

  $objPdf->SetXY(26, 61);
  $objPdf->MultiCell(175, 5, $sStyles, 0, "L");
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->setCellHeightRatio(2);

	if(strlen($sStylesName) <= 230){
		$objPdf->SetFont('Arial', '', 7);
	} else if(strlen($sStylesName) <= 272){
		$objPdf->setCellHeightRatio(2.5);
		$objPdf->SetFont('Arial', '', 6);
	} else if(strlen($sStylesName) <= 320){
		$objPdf->setCellHeightRatio(3);
		$objPdf->SetFont('Arial', '', 5);
	} else {
		$objPdf->setCellHeightRatio(1.15);
		$objPdf->SetFont('Arial', '', 5);			
	}

  $objPdf->SetXY(32, 71.5);
  $objPdf->MultiCell(175, 5, $sStylesName, 0, "L");
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->setCellHeightRatio(2);

	if(strlen($sColors) <= 230){
		$objPdf->SetFont('Arial', '', 7);
	} else if(strlen($sColors) <= 272){
		$objPdf->setCellHeightRatio(2.5);
		$objPdf->SetFont('Arial', '', 6);
	} else if(strlen($sColors) <= 320){
		$objPdf->setCellHeightRatio(3);
		$objPdf->SetFont('Arial', '', 5);
	} else {
		$objPdf->setCellHeightRatio(1.15);
		$objPdf->SetFont('Arial', '', 5);			
	}

	$objPdf->SetXY(26.5, 92.5);
	$objPdf->MultiCell(175, 5, $sColors, 0, "L");
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->setCellHeightRatio(2);

	if(strlen($sSizeTitles) <= 230){
		$objPdf->SetFont('Arial', '', 7);
	} else if(strlen($sSizeTitles) <= 272){
		$objPdf->setCellHeightRatio(2.5);
		$objPdf->SetFont('Arial', '', 6);
	} else if(strlen($sSizeTitles) <= 320){
		$objPdf->setCellHeightRatio(3);
		$objPdf->SetFont('Arial', '', 5);
	} else {
		$objPdf->setCellHeightRatio(1.15);
		$objPdf->SetFont('Arial', '', 5);			
	}

	$objPdf->SetXY(21, 103);
	$objPdf->MultiCell(175, 5, $sSizeTitles, 0, "L");
	$objPdf->SetFont('Arial', '', 7);

	$objPdf->setCellHeightRatio(1);
	$objPdf->SetXY(25, 164.5);

	$objPdf->SetTextColor(50, 50, 50);

		//Code Start Here

  $sSQL = "SELECT qad.sample_no, qad.defects, qad.area_id, qad.picture,qad.pictures,
          dc.type_id, dc.code, dc.defect, qad.nature, qad.lot_no 
          FROM tbl_qa_report_defects qad, tbl_defect_codes dc
          WHERE qad.audit_id='$Id' AND qad.code_id=dc.id
          ORDER BY qad.defects DESC";

  $objDb->query($sSQL);

  $iCount = $objDb->getCount( );
  $criticalCount = 0;
  $majorCount = 0;
  $minorCount = 0;

  $calculateDefectiveGarments = array();
  $statsDefects   = array( );

  for ($i = 0; $i < $iCount; $i ++)
  {
    $iSampleNo = $objDb->getField($i, "sample_no");
    $iDefects  = $objDb->getField($i, "defects");
    $iArea     = $objDb->getField($i, "area_id");
    $sPicture  = $objDb->getField($i, "picture");
    $sPictures  = $objDb->getField($i, "pictures");
    $iType     = $objDb->getField($i, "type_id");
    $sCode     = $objDb->getField($i, "code");
    $sDefect   = $objDb->getField($i, "defect");
    $iNature   = $objDb->getField($i, "nature");
    $sLotNo   = $objDb->getField($i, "lot_no");

    switch ($iNature)
    {
      case 0 : $minorCount++; $sNature="Minor"; break;         
      case 1 : $majorCount++; $sNature="Major"; break;
      case 2 : $criticalCount++; $sNature="Critical"; break;
    }

    if(!in_array($iSampleNo." ".$sLotNo, $calculateDefectiveGarments) && ($iNature == 1 || $iNature == 2)) {
      array_push($calculateDefectiveGarments, $iSampleNo." ".$sLotNo);
    }

    $sPictureUrl = "";

    @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

    $sQuondaDir     = (ABSOLUTE_PATH.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");

    $sQuondaFoundDir = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");

    // if ($sPicture != "" && @file_exists($sQuondaDir.$sPicture))
    if ($sPicture != "" && @file_exists($sQuondaFoundDir.$sPicture))
        $sPictureUrl = strtolower(SITE_URL.str_ireplace(ABSOLUTE_PATH, "", $sQuondaDir).$sPicture);

    $iPosition = -1;

    for ($j = 0; $j < count($statsDefects); $j ++) {

        if ($statsDefects[$j]["Type"] == $sTypesList[$iType] && $statsDefects[$j]["Defect"] == $sDefect && $statsDefects[$j]["Code"] == $sCode && $statsDefects[$j]["Area"] == $sAreasList[$iArea]) {

        $iPosition = $j;
        break;

        }
    }

    if ($iPosition == -1) {

        $sDetails   = array( );
        $sDetails[] = array("Defects"  => $iDefects,
        "SampleNo" => $iSampleNo,
        "Picture"  => $sPictureUrl);

        if($sPictures != ""){

          $additionalPictures = explode(",", $sPictures);

          foreach ($additionalPictures as $additionalPicture) {
            
            $sAddPictureUrl = "";

            if ($additionalPicture != "" && @file_exists($sQuondaFoundDir.$additionalPicture))
            $sAddPictureUrl = strtolower(SITE_URL.str_ireplace(ABSOLUTE_PATH, "", $sQuondaDir).$additionalPicture);
            
            $sDetails[] = array("Defects"  => $iDefects,
            "SampleNo" => $iSampleNo,
            "Picture"  => $sAddPictureUrl);
     
          }
        }

        $statsDefects[] = array("Type"     => $sTypesList[$iType],
        "Defect"   => $sDefect,
        "Code"     => $sCode,
        "Area"     => $sAreasList[$iArea],
        "Defects"  => $iDefects,
        "Nature"   => $sNature,
        "OrderNo"  => $poOrderNumber,
        "Brand"=> $sBrand,
        "Vendor"=> $sVendor,
        "Style"=> $sStyle,
        "Details"  => $sDetails);

    } else {

        $statsDefects[$iPosition]["Defects"]  += $iDefects;
        $statsDefects[$iPosition]["Details"][] = array("Defects"  => $iDefects,
        "SampleNo" => $iSampleNo,
        "Picture"  => $sPictureUrl);
    }
  }

  $minorDefects = array();
  $majorDefects = array();
  $criticalDefects = array();

  for($g=0; $g<count($statsDefects); $g++) {

    if($statsDefects[$g]['Nature'] == 'Minor') {
      array_push($minorDefects, $statsDefects[$g]);
    } else if($statsDefects[$g]['Nature'] == 'Major') {
      array_push($majorDefects, $statsDefects[$g]);
    } else if($statsDefects[$g]['Nature'] == 'Critical') {
      array_push($criticalDefects, $statsDefects[$g]);
    }
  }

  $countDefectiveGarments = count($calculateDefectiveGarments);

  $remainingDRPercentage = 100-$DRInPercentage;

  $sSQL = "SELECT * FROM `tbl_qa_report_defects` WHERE audit_id='$Id' AND nature != '0'";
  $objDb->query($sSQL);

  $iCountD = $objDb->getCount( );

  $colors = array();
  $defectTypes = array();
  $defectTypeIds = array();
  $defectCodeIds = array();
  $totalCounts = array();
  $totalDefects = 0;

  for($d=0; $d<$iCountD; $d++) {

    $iDefectCode = $objDb->getField($d, "code_id");
    $totalCount = $objDb->getField($d, "total");

    $iDefectType = getDbValue("type_id", "tbl_defect_codes", "id='$iDefectCode'");
    $DefectType = getDbValue("type", "tbl_defect_types", "id='$iDefectType'");
    $DefectColor  = getDbValue("color", "tbl_defect_types", "id='$iDefectType'");

    $totalDefects++;
    
    if(!in_array($iDefectCode, $defectCodeIds)) {

      array_push($defectCodeIds, $iDefectCode);
    }     
    if(!in_array($iDefectType, $defectTypeIds)) {

      array_push($defectTypeIds, $iDefectType);
    }    
    if(!in_array($DefectType, $defectTypes)) {

      array_push($defectTypes, $DefectType);
    }  
    if(!in_array($DefectColor, $colors)) {

      array_push($colors, $DefectColor);
    }

    $totalCounts[$iDefectType] += 1 ; 

    $sDefectColors = $colors;
		$sDefectTypes = $defectTypes;
    
    $dataArray = array();

    for($e=0; $e<count($defectTypeIds); $e++) {

      $code = $defectTypeIds[$e];
      $per = ($totalCounts[$code] / $totalDefects)*100;
      $per = round($per,2);
      array_push($dataArray, $per);
    }

    $sDefectsData = $dataArray;
  }

//DR Chart

	$values = array($remainingDRPercentage,$DRInPercentage);
	$colors = array(hexToRGB("#ECAA41"),hexToRGB("#ACACAC"));
	$total = array_sum($values);
	$xc = 26;
	$yc = 173;
	$r = 15;
	$filledArea = 0;
	$counter = 1;

	foreach ($values as $key=>$value) {

		$calculatedArea = ($value*360)/100;
		
		$calculatedArea = round($calculatedArea);

		$calculatedAreaComb = $calculatedArea + $filledArea;

		if(count($values) == $counter){
			$calculatedAreaComb = 0;
		}

		$colorArray = $colors[$key];

		$objPdf->SetFillColor($colorArray[0], $colorArray[1], $colorArray[2]);

		$objPdf->PieSector($xc, $yc, $r, $filledArea, $calculatedAreaComb, 'F', false, 0, 2);

		$filledArea += $calculatedArea;

		$counter++;
	}

	$objPdf->SetFillColor(255, 255, 255);
	$objPdf->Circle($xc,$yc,$r-2, 0, 360, 'F');
	$objPdf->SetFont('Arial', '', 10);
	$objPdf->Text($xc-4, $yc-5, "DR");
	$objPdf->SetXY($xc-5, $yc-1);
	$objPdf->Cell(10,5,$DRInPercentage."%",0,0,'C');
	// $objPdf->Text($xc-5, $yc, $DRInPercentage."%");
	$objPdf->SetFont('Arial', '', 7);

// DEFECT CLACIFICATION

	$values = $sDefectsData;

	$xc = 68;
	$yc = 173;
	$r = 15;
	$filledArea = 0;
	$counter = 0;

	foreach ($values as $key=>$value) {

		$calculatedArea = ($value*360)/100;
		
		$calculatedArea = round($calculatedArea);

		$calculatedAreaComb = $calculatedArea + $filledArea;

		if(count($values) == $counter){
			$calculatedAreaComb = 0;
		}

		$color = $sDefectColors[$key];

		$color = ($color=='#ffffff')?$color="#ACACAC":$color;

		$colorArray = hexToRGB($color);

		$objPdf->SetFillColor($colorArray[0], $colorArray[1], $colorArray[2]);

		$objPdf->PieSector($xc, $yc, $r, $filledArea, $calculatedAreaComb, 'F', false, 0, 2);

		$filledArea += $calculatedArea;

		$counter++;
	}

	$objPdf->SetFillColor(255, 255, 255);
	$objPdf->Circle($xc,$yc,$r-2, 0, 360, 'F');
	$objPdf->SetFont('Arial', '', 10);
	$objPdf->Text($xc-4, $yc-5, "DR");
	$objPdf->SetXY($xc-5, $yc-1);
	// $objPdf->Text($xc-5, $yc, $DRInPercentage."%");
	$objPdf->Cell(10,5,$DRInPercentage."%",0,0,'C');
	
	if($brandInspectionLevel == '1')
		$brandInspectionLevelText = 'General Inspection Level I';
	else if($brandInspectionLevel == '2')
		$brandInspectionLevelText = 'General Inspection Level II';
	else if($brandInspectionLevel == '3')
		$brandInspectionLevelText = 'General Inspection Level III';
	else 
		$brandInspectionLevelText = '';

	$iNoOfLots = getDbValue("COUNT(1)", "tbl_qa_lot_sizes", "audit_id='$Id'");

	@list($iMajorDefectsAllowed,$iMinorDefectsAllowed) = getAqlDefects($iTotalGmts, $sAQL, $iReportId);

	$maximumDefectString = "0 Critical, ".$iMajorDefectsAllowed." Major (3 Minor Equal to 1 Major)";
	$DefectFoundString = $criticalCount." Critical, ".$majorCount." Major, ".$minorCount." Minor";

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(55.7, 125, $iNoOfLots);
	$objPdf->Text(55.7, 128.5, $iTotalGmts." (".$brandInspectionLevelText.")");
	$objPdf->Text(55.7, 132.5, $brandAQL);
	$objPdf->Text(55.7, 136.4, $maximumDefectString);
	$objPdf->Text(55.7, 140.3, $DefectFoundString);
	$objPdf->Text(55.7, 144, $countDefectiveGarments);

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->setCellHeightRatio(1);

	$objPdf->SetXY(8, 191);
	$objPdf->MultiCell(35, 3.8, "Based on No of", 0, "C");
	$y = $objPdf->GetY();
  $objPdf->SetXY(8, $y);	
	$objPdf->MultiCell(35, 3.8, "Defective", 0, "C");
	$y = $objPdf->GetY();
  $objPdf->SetXY(8, $y);		
	$objPdf->MultiCell(35, 3.8, "Garments", 0, "C");
	$y = $objPdf->GetY();
	$objPdf->SetFont('Arial', '', 6);
  $objPdf->SetXY(8, $y);		
	$objPdf->MultiCell(35, 3.7, "(Major Defects Only)", 0, "C");

	$objPdf->SetXY(50, 191);
	$objPdf->SetFont('Arial', '', 9);

	$sSQL2 = "SELECT dt.type,COUNT(dc.id) As total FROM tbl_qa_report_defects rd, tbl_defect_codes dc, tbl_defect_types dt WHERE audit_id = '$Id' AND rd.code_id = dc.id AND dt.id = dc.type_id AND rd.nature != '0' GROUP BY dc.type_id ORDER BY rd.nature DESC";

	$objDb2->query($sSQL2);

	$iCount2 = $objDb2->getCount( );

	for($d=0; $d<$iCount2; $d++){

		$type = $objDb2->getField($d, "type");
		$totaldefectCount = $objDb2->getField($d, "total");			

		$y = $objPdf->GetY();

	  $objPdf->SetXY(45, $y);

	  $text = $type." (".$totaldefectCount.")";

		$objPdf->MultiCell(45, 3.8, $text, 0, "C");

	}		

	$objPdf->setCellHeightRatio(1);
	$objPdf->SetFont('Arial', '', 7);

	//Sections CheckList

	$sSections = getDbValue ("sections", "tbl_reports", "id = '$iReportId'");

	$sSectionsList  = getList("tbl_qa_sections", "id", "section", "id IN ($sSections)", "position");

	$y = 151;
	$x = 90.5;

	$objPdf->SetXY($x, $y);
	$objPdf->SetFont('Arial', 'B', 8);

	foreach ($sSectionsList as $id => $Section) {

		$sResult = "";

		if(in_array($id, array(1,7,8,13))){
			continue;
		}

		if($id == 2){
			$sResult = getDbValue ("product_conformity_result", "tbl_qa_report_details", "audit_id = '$Id'");
		}

		if($id == 3){
			$sResult = getDbValue ("result", "tbl_qa_weight_conformity", "audit_id = '$Id'");
		}

		if($id == 4){
			$sResult = getDbValue ("ean_result", "tbl_qa_report_details", "audit_id = '$Id'");
		}

		if($id == 5){
			$sResult = getDbValue ("result", "tbl_qa_assortment", "audit_id = '$Id'");
		}

		if($id == 10){
			$packagingResult = getDbValue ("packaging_result", "tbl_qa_report_details", "audit_id = '$Id'");
			$labelingResult = getDbValue ("labeling_result", "tbl_qa_report_details", "audit_id = '$Id'");

			if($packagingResult == 'F' || $labelingResult == 'F'){
				$sResult = 'F';
			} else if($packagingResult == 'P' && $labelingResult == 'P'){
				$sResult = 'P';
			} else if(($packagingResult == 'P' && $labelingResult == '') || ($packagingResult == '' && $labelingResult == 'P')){
				$sResult = 'P';
			} 
		}

		if($id == 12){
			$sResult = getDbValue ("measurement_result", "tbl_qa_report_details", "audit_id = '$Id'");
		}

		if($id == 9){
			$sResult = getDbValue ("workmanship_result", "tbl_qa_reports", "id = '$Id'");
                        
                        if($sResult == '')
                                $sResult = $sAuditResult;
		}

		$y = $objPdf->GetY()+5.3;

		$objPdf->SetXY($x+7, $y);
		$objPdf->MultiCell(60,5,strtoupper($Section),0,"L");

		$objPdf->SetXY($x+53, $y-0.3);
		$objPdf->Cell(3, 3, 'PASS');
		$objPdf->SetXY($x+63.5, $y-1.5);
		$objPdf->Cell(3.7, 3.7, '', 1);

                
		if(strtolower($sResult) == 'p'){
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 63.9+$x, $y-1.2, 2.8);
		}

		$objPdf->SetXY($x+71, $y-0.3);
		$objPdf->Cell(3, 3, 'FAIL');
		$objPdf->SetXY($x+80, $y-1.5);
		$objPdf->Cell(3.7, 3.7, '', 1);

		if(strtolower($sResult) == 'f'){
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 80.5+$x, $y-1.2, 2.8);
		}

		$objPdf->SetXY($x+90, $y-0.3);
		$objPdf->Cell(3, 3, 'N/A');
		$objPdf->SetXY($x+97, $y-1.5);
		$objPdf->Cell(3.7, 3.7, '', 1);

		if(strtolower($sResult) != 'p' && strtolower($sResult) != 'f'){
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 97.5+$x, $y-1.2, 2.8);
		}

		$objPdf->SetXY($x+97, $y-0.3);
	}

	// Overall Result

		$y = $objPdf->GetY()+5.2;

		$objPdf->SetXY($x+7, $y);
		$objPdf->MultiCell(60,5,strtoupper('OverAll result'),0,"L");

		$objPdf->SetXY($x+53, $y-0.3);
		$objPdf->Cell(3, 3, 'PASS');
		$objPdf->SetXY($x+63.5, $y-1.5);
		$objPdf->Cell(3.7, 3.7, '', 1);

		if(strtolower($sAuditResult) == 'p'){
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 63.9+$x, $y-1.2, 2.8);
		}

		$objPdf->SetXY($x+71, $y-0.3);
		$objPdf->Cell(3, 3, 'FAIL');
		$objPdf->SetXY($x+80, $y-1.5);
		$objPdf->Cell(3.7, 3.7, '', 1);

		if(strtolower($sAuditResult) == 'f'){
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 80.5+$x, $y-1.2, 2.8);
		}

		$objPdf->SetXY($x+90, $y-0.3);
		$objPdf->Cell(3, 3, 'N/A');
		$objPdf->SetXY($x+97, $y-1.5);
		$objPdf->Cell(3.7, 3.7, '', 1);

		if(strtolower($sAuditResult) != 'p' && strtolower($sAuditResult) != 'f'){
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 97.2+$x, $y-0.2, 2.5);
		}

		$objPdf->SetFont('Arial', '', 7);

		// $Id = '511340';
		// $iStyle = '85578';

	  $pointsArray = array();
	  $sSampleSpecs  = array( );

	  $sSQL = "SELECT * FROM tbl_qa_report_samples WHERE audit_id='$Id'";
	  $objDb->query($sSQL);
	  
	  $iCount = $objDb->getCount( );
	  
	  for ($i = 0; $i < $iCount; $i ++)
	  {
	    $iSampleId     = $objDb->getField($i, "id");
	    $iStyleId        = $objDb->getField($i, "style_id");
	    $iSize         = $objDb->getField($i, "po_size_id");
	    $iSamplingSize = $objDb->getField($i, "size_id");
	    $sSize         = $objDb->getField($i, "size");
	    $sColor        = $objDb->getField($i, "color");
	    $sNature       = $objDb->getField($i, "nature");
	    $sResult       = $objDb->getField($i, "result");
	    $iSampleNo     = $objDb->getField($i, "sample_no");
	    
	    $sSQL = "SELECT * FROM tbl_qa_report_sample_specs WHERE sample_id='$iSampleId'";
	    $objDb2->query($sSQL);
	    
	    $iCount2  = $objDb2->getCount( );
	    $sDetails = array( );
	    
	    for ($j = 0; $j < $iCount2; $j ++)
	    {
	      $iPoint       = $objDb2->getField($j, "point_id");
	      $sFindings    = $objDb2->getField($j, "findings");
	      $sPointSpecs  = $objDb2->getField($j, 'specs');

	      if (strtolower($sFindings) == "ok")
	        $sFindings = "";
	      
	      if ($iStyleId == 0)
	        $iStyleId = $iStyle;
	      
	      $sSQL = "SELECT specs, nature FROM tbl_style_specs WHERE style_id='$iStyleId' AND size_id='$iSamplingSize' AND point_id='$iPoint' AND version='0'";
	      $objDb3->query($sSQL);
	      
	      $sPointNature = $objDb3->getField(0, 'nature');
	      $sSpecs       = $objDb3->getField(0, 'specs');
	      
	      if ($sNature == "C" && $sPointNature != $sNature)
	        continue;
	      
	      
	      $sSQL = "SELECT point, tolerance FROM tbl_measurement_points WHERE id='$iPoint'";
	      $objDb3->query($sSQL);
	      
	      $sPoint     = $objDb3->getField(0, "point");
	      $sTolerance = $objDb3->getField(0, "tolerance");
	      
	      if ($sPointSpecs != "")
	        $sSpecs = $sPointSpecs;

	      
	      @list($fMinusTolerance, $fPlusTolerance) = parseTolerance($sTolerance);
	      
	      
	      $sDetails[] = array("PointId"        => $iPoint,
	                  "Point"          => $sPoint,
	                "Value"          => $sFindings,
	                  "Specs"          => $sSpecs,
	                "Tolerance"      => $sTolerance,
	                "MinusTolerance" => $fMinusTolerance,
	                "PlusTolerance"  => $fPlusTolerance);
	    }
	    
	    $sSampleSpecs[] = $sDetails;
	  }

	  $totalSamples = count($sSampleSpecs);

	  foreach ($sSampleSpecs as $key => $sSampleSpec) {
	  
	    for($i=0; $i<count($sSampleSpec); $i++)
	    {
	      $PointId = $sSampleSpec[$i]['PointId'];
	      $sFindings = $sSampleSpec[$i]['Value'];
	      $sPoint = $sSampleSpec[$i]['Point'];
	      $sTolerance = $sSampleSpec[$i]['Tolerance'];
	      $minusTol = $sSampleSpec[$i]['MinusTolerance'];
	      $plusTol = $sSampleSpec[$i]['PlusTolerance'];  
	      $sSpecs = $sSampleSpec[$i]['Specs'];

	      $plusValue = $sFindings+$plusTol;
	      $minusValue = $sFindings-$minusTol;

	      if(!array_key_exists($PointId,$pointsArray)){
	        $pointsArray[$PointId] = array("point"=> $sPoint, "total"=> $totalSamples,"within"=>0, "match"=>0, "out"=>0);
	      }

	      if (@strpos($sFindings, "+") !== FALSE || @strpos($sFindings, "-") !== FALSE || @strpos($sFindings, "*") !== FALSE){

	        $filterValue = str_replace("*","",$sFindings);

	        if(@strpos($filterValue, "+") !== FALSE ){

	          $filterValue = trim(str_replace("+","",$filterValue));
	          $compareDBValue = $sSpecs + $filterValue;

	          $plusValue = $sSpecs + $plusTol;

	          if($compareDBValue <= $plusValue){
	            $withinTolerance++;
	            $pointsArray[$PointId]["within"]++;
	          } else if($compareDBValue == $plusValue){
	            $exactMatch++;
	            $pointsArray[$PointId]["match"]++;
	          } else {
	            $pointsArray[$PointId]["out"]++;
	            $outTolerance++;
	          }

	        } else if(@strpos($filterValue, "-") !== FALSE ){

	          $filterValue = trim(str_replace("-","",$filterValue));
	          $compareDBValue = $sSpecs - $filterValue;

	          $minusValue = $sSpecs - $plusTol;

	          if($compareDBValue >= $minusValue){
	            $withinTolerance++;
	            $pointsArray[$PointId]["within"]++;
	          } else if($compareDBValue == $plusValue){
	            $exactMatch++;
	            $pointsArray[$PointId]["match"]++;
	          } else {
	            $pointsArray[$PointId]["out"]++;
	            $outTolerance++;
	          }
	        }

	      } else if($sFindings == 'ok' || $sFindings == '' || $sFindings == $sSpecs){
	        $exactMatch++;
	        $pointsArray[$PointId]["match"]++;
	      } else if($sSpecs <= $plusValue && $sSpecs>=$minusValue) {
	        $withinTolerance++;
	        $pointsArray[$PointId]["within"]++;
	      } else {
	        $outTolerance++;
	        $pointsArray[$PointId]["out"]++;
	      }
	    }
	  }          

		if(count($pointsArray) > 0) {

			$objPdf->SetFont('Arial', '', 9);
			$objPdf->text(78, 215.3, 0);
			$objPdf->SetFont('Arial', '', 7);

			$objPdf->setCellHeightRatio(0.8);
			$objPdf->SetXY(11, 219.8);

			$y = 216.3;

			usort($pointsArray, function ($item1, $item2) {
			    if ($item1['out'] == $item2['out']) return 0;
			    return $item1['out'] > $item2['out'] ? -1 : 1;
			});

      usort($pointsArray, function ($item1, $item2) {
          if ($item1['out'] > $item2['out']) return 0;
          return ($item1['out'] == $item2['out'] && $item1['within'] > $item2['within']) ? -1 : 1;
      });

			for($u=0; $u<count($pointsArray); $u++){

				if($u > 7){
					continue;
				}

				$point = $pointsArray[$u]["point"];
				$out = $pointsArray[$u]["out"];
				$within = $pointsArray[$u]["within"];
				$match = $pointsArray[$u]["match"];
				$total = $pointsArray[$u]["total"];

				// $total = $out+$within+$match;

				if($u==0){
					$objPdf->SetFont('Arial', '', 9);
					$objPdf->text(190.5, 215.3, count($sSampleSpecs));
					$objPdf->SetFont('Arial', '', 7);					
				}
				
				$y = $y+5.7;
				$objPdf->SetXY(11, $y);
				$objPdf->MultiCell(60, 3.5, $point, 0, "L");

				$objPdf->text(75, $y, $out);

				$x = 80;
				$outLineLength = 0;
				$withinLineLength = 0;

				if($out > 0) {

					$outLineLength = ($out/$total)*113;

					$objPdf->SetFillColor(184,32,37);
					$objPdf->Rect($x, $y, $outLineLength, 2, 'F');
					$x = $x+$outLineLength;
				}

				if($within > 0) {

					$withinLineLength = ($within/$total)*113;
					
					$objPdf->SetFillColor(235,169,65);
					$objPdf->Rect($x, $y, $withinLineLength, 2, 'F');
					$x = $x+$withinLineLength;
				}

				$matchLineLength = 113-$outLineLength-$withinLineLength;

				$objPdf->SetFillColor(29,148,71);
				$objPdf->Rect($x, $y, $matchLineLength, 2, 'F');

			}
		} else {

			$objPdf->SetFillColor(255,255,255);
			$objPdf->Rect(5, 212, 200, 53, 'F');
			
		}

		$objPdf->setCellHeightRatio(1);

// /////////////////////////////////////////////////PAGE 3 - DESCRIPTION QUANTITY OF PRODUCT ///////////////////////// 

	$counter = 1;

	$sSQL = "SELECT * FROM tbl_qa_lot_sizes WHERE audit_id = '$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($j=0; $j<$DQOPPages; $j++) {

		$iCurrentPage++;

		if($j == 0){
			$startValue = 0;
			$endValue = 5;
		} else {
			$startValue = $startValue+5;
			$endValue = $endValue+5;
		}

		$iTemplateId = $objPdf->importPage(3, '/MediaBox');
		$objPdf->addPage("P", "A4");
		$objPdf->useTemplate($iTemplateId, 0, 0);

		$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178.7, 9.5, 23);

		$objPdf->SetFont('Arial', '', 10);
		$objPdf->SetTextColor(255, 255, 255);

		$objPdf->Text(161, 19.4, "{$sAuditCode}");
		$objPdf->Text(66.5, 19.4, "{$sReportType}");

		$objPdf->SetFont('Arial', '', 9);
		$objPdf->SetTextColor(169, 169, 169);
		$objPdf->Text(162.5, 30.5, "{$iCurrentPage} of {$iTotalPages}");

		$objPdf->Image(($sBaseDir.BRANDS_IMG_DIR."{$sBrandImagePath}"), 8, 6.1, 29);

		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(0, 0, 0);

		$objPdf->Text(26, 41.8, $sVendor);
		$objPdf->Text(115, 41.8, $PrettyAuditDate);
		$objPdf->Text(170.5, 41.8, $sAuditor);

		$objPdf->Text(52, 47.8, $sCumulativeOrderQuantity);
		$objPdf->Text(113, 47.8, $sCumolativeLotSize);
		$objPdf->Text(181, 47.8, $sCumolativeSampleSize);

		$objPdf->Text(35, 83.3, $allDestinationString);
		$objPdf->Text(183, 83.3, $iTotalCartons);

	  $sAuditStage = strtoupper($sAuditStage);

		$objPdf->Text(156, 88.5, $sAuditStage);
		$objPdf->Text(62, 88.5, $sAuditStatus);

		$objPdf->setCellHeightRatio(2);

    if(strlen($sAllPos) <= 230){
      $objPdf->SetFont('Arial', '', 7);
    } else if(strlen($sAllPos) <= 272){
      $objPdf->setCellHeightRatio(2.5);
      $objPdf->SetFont('Arial', '', 6);
    } else if(strlen($sAllPos) <= 320){
      $objPdf->setCellHeightRatio(3);
      $objPdf->SetFont('Arial', '', 5);
    } else {
      $objPdf->setCellHeightRatio(1.15);
      $objPdf->SetFont('Arial', '', 5);     
    }

    $objPdf->SetXY(21, 50.5);
    $objPdf->MultiCell(175, 5, $sAllPos, 0, "L");
    $objPdf->SetFont('Arial', '', 7);
    $objPdf->setCellHeightRatio(2);

    if(strlen($sStyles) <= 230){
      $objPdf->SetFont('Arial', '', 7);
    } else if(strlen($sStyles) <= 272){
      $objPdf->setCellHeightRatio(2.5);
      $objPdf->SetFont('Arial', '', 6);
    } else if(strlen($sStyles) <= 320){
      $objPdf->setCellHeightRatio(3);
      $objPdf->SetFont('Arial', '', 5);
    } else {
      $objPdf->setCellHeightRatio(1.15);
      $objPdf->SetFont('Arial', '', 5);     
    }

    $objPdf->SetXY(26, 61);
    $objPdf->MultiCell(175, 5, $sStyles, 0, "L");
    $objPdf->SetFont('Arial', '', 7);
    $objPdf->setCellHeightRatio(2);

		if(strlen($sStylesName) <= 230){
			$objPdf->SetFont('Arial', '', 7);
		} else if(strlen($sStylesName) <= 272){
			$objPdf->setCellHeightRatio(2.5);
			$objPdf->SetFont('Arial', '', 6);
		} else if(strlen($sStylesName) <= 320){
			$objPdf->setCellHeightRatio(3);
			$objPdf->SetFont('Arial', '', 5);
		} else {
			$objPdf->setCellHeightRatio(1.15);
			$objPdf->SetFont('Arial', '', 5);			
		}

	  $objPdf->SetXY(32, 71.5);
	  $objPdf->MultiCell(175, 5, $sStylesName, 0, "L");
		$objPdf->SetFont('Arial', '', 7);
		$objPdf->setCellHeightRatio(2);

    if(strlen($sColors) <= 230){
      $objPdf->SetFont('Arial', '', 7);
    } else if(strlen($sColors) <= 272){
      $objPdf->setCellHeightRatio(2.5);
      $objPdf->SetFont('Arial', '', 6);
    } else if(strlen($sColors) <= 320){
      $objPdf->setCellHeightRatio(3);
      $objPdf->SetFont('Arial', '', 5);
    } else {
      $objPdf->setCellHeightRatio(1.15);
      $objPdf->SetFont('Arial', '', 5);     
    }

    $objPdf->SetXY(26.5, 92.5);
    $objPdf->MultiCell(175, 5, $sColors, 0, "L");
    $objPdf->SetFont('Arial', '', 7);
    $objPdf->setCellHeightRatio(2);

    if(strlen($sSizeTitles) <= 230){
      $objPdf->SetFont('Arial', '', 7);
    } else if(strlen($sSizeTitles) <= 272){
      $objPdf->setCellHeightRatio(2.5);
      $objPdf->SetFont('Arial', '', 6);
    } else if(strlen($sSizeTitles) <= 320){
      $objPdf->setCellHeightRatio(3);
      $objPdf->SetFont('Arial', '', 5);
    } else {
      $objPdf->setCellHeightRatio(1.15);
      $objPdf->SetFont('Arial', '', 5);     
    }

    $objPdf->SetXY(21, 103);
    $objPdf->MultiCell(175, 5, $sSizeTitles, 0, "L");
    $objPdf->SetFont('Arial', '', 7);

		$objPdf->setCellHeightRatio(1);

	  $objPdf->SetFont('Arial', '', 7);
		$objPdf->SetXY(7, 232);
		$objPdf->MultiCell(205, 5, $productQuantityComments, 0, "L");

		$objPdf->SetXY(110, 130);

		if($iCount > 0) {
			
			for($i=$startValue; $i<=$endValue; $i++) {

				if($counter > $iCount){
					break;
				}

				$lotSize = $objDb->getField($i, 'lot_size');
				$sampleSize = $objDb->getField($i, 'sample_size');

				$style = $objDb->getField($i, 'styles');
				$sizes = $objDb->getField($i, 'sizes');

				$colorsString = $objDb->getField($i, 'colors');
				$sizes = rtrim($sizes,",");
				$sizeString = getDbValue("GROUP_CONCAT(size SEPARATOR ', ')","tbl_sizes","id IN($sizes)");
				// $styleString = getDbValue ("CONCAT(style_name, '-',style)", "tbl_styles", "id ='$style'");
				$styleString = getDbValue ("style", "tbl_styles", "id ='$style'");

				if($counter%2 == 0) {

					$y = $objPdf->GetY();

				  $objPdf->SetXY(110, $y);

				  $lotNoText = "Lot No ".$counter.": ";
				  $objPdf->MultiCell(50, 5, $lotNoText, 0, "L");

				  $y = $objPdf->GetY();
				  $objPdf->SetXY(130, $y);
				  $objPdf->Cell(30, 5, "Quantity:", 0);

				  $y = $objPdf->GetY();
				  $objPdf->Cell(30, 5, $lotSize, 0);
				  $objPdf->SetXY(130, $y+4);
				  $objPdf->Cell(30, 5, "Samples Extracted:", 0);
				  $objPdf->Cell(30, 5, $sampleSize, 0);

				  $y = $objPdf->GetY();
				  $objPdf->SetXY(130, $y+4);
				  $objPdf->Cell(30, 5, "Style:", 0);
				  $objPdf->Cell(30, 5, $styleString, 0);

				  $y = $objPdf->GetY();
				  $objPdf->SetXY(130, $y+4);
				  $objPdf->Cell(30, 5, "Color(s):", 0);
				  $objPdf->SetXY(160, $y+5.5);
				  $objPdf->MultiCell(40, 2, $colorsString, 0, "L");

				  $y = $objPdf->GetY();
				  $objPdf->SetXY(130, $y);
				  $objPdf->Cell(30, 5, "Sizes(s):", 0);
				  $objPdf->SetXY(160, $y+1.5);
				  $objPdf->MultiCell(40, 2, $sizeString , 0, "L");

					$y = $objPdf->GetY();
					$objPdf->SetXY(120, $y+4);

				} else {
					
					$y = $objPdf->GetY();
					$startY = $y;

				  $objPdf->SetXY(10, $y);

				  $lotNoText = "Lot No ".$counter.": ";
				  $objPdf->Cell(50, 5, $lotNoText, 0);

				  $y = $objPdf->GetY();
				  $objPdf->SetXY(30, $y+4);
				  $objPdf->Cell(30, 5, "Quantity:", 0);

				  $y = $objPdf->GetY();
				  $objPdf->Cell(30, 5, $lotSize, 0);
				  $objPdf->SetXY(30, $y+4);
				  $objPdf->Cell(30, 5, "Samples Extracted:", 0);
				  $objPdf->Cell(30, 5, $sampleSize, 0);

				  $y = $objPdf->GetY();
				  $objPdf->SetXY(30, $y+4);
				  $objPdf->Cell(30, 5, "Style:", 0);
				  $objPdf->Cell(30, 5, $styleString, 0);

				  $y = $objPdf->GetY();
				  $objPdf->SetXY(30, $y+4);
				  $objPdf->Cell(30, 5, "Color(s):", 0);
				  $objPdf->SetXY(60, $y+5.5);
				  $objPdf->MultiCell(40, 2, $colorsString, 0, "L");

				  $y = $objPdf->GetY();
				  $objPdf->SetXY(30, $y);
				  $objPdf->Cell(30, 5, "Sizes(s):", 0);
				  $objPdf->SetXY(60, $y+1.5);
				  $objPdf->MultiCell(40, 2, $sizeString , 0, "L");

				  $objPdf->SetXY(10, $startY);
				}

				$counter++;
			}
		}
	}

// /////////////////////////////////////////////////PAGE 4 - CHECKLIST ///////////////////////// 

		$iCurrentPage++;
		$iTemplateId = $objPdf->importPage(4, '/MediaBox');
		$objPdf->addPage("P", "A4");
		$objPdf->useTemplate($iTemplateId, 0, 0);

		$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178.7, 9.5, 23);

		$objPdf->SetFont('Arial', '', 10);
		$objPdf->SetTextColor(255, 255, 255);

		$objPdf->Text(161, 19.4, "{$sAuditCode}");
		$objPdf->Text(66.5, 19.4, "{$sReportType}");

		$objPdf->SetFont('Arial', '', 9);
		$objPdf->SetTextColor(169, 169, 169);
		$objPdf->Text(162.5, 30.6, "{$iCurrentPage} of {$iTotalPages}");

		$objPdf->Image(($sBaseDir.BRANDS_IMG_DIR."{$sBrandImagePath}"), 8, 6.1, 29);

		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(50, 50, 50);

		$objPdf->Text(26, 41.8, $sVendor);
		$objPdf->Text(115, 41.8, $PrettyAuditDate);
		$objPdf->Text(170.5, 41.8, $sAuditor);

		$objPdf->Text(52, 47.8, $sCumulativeOrderQuantity);
		$objPdf->Text(113, 47.8, $sCumolativeLotSize);
		$objPdf->Text(181, 47.8, $sCumolativeSampleSize);

		$objPdf->setCellHeightRatio(2);

    if(strlen($sAllPos) <= 230){
      $objPdf->SetFont('Arial', '', 7);
    } else if(strlen($sAllPos) <= 272){
      $objPdf->setCellHeightRatio(2.5);
      $objPdf->SetFont('Arial', '', 6);
    } else if(strlen($sAllPos) <= 320){
      $objPdf->setCellHeightRatio(3);
      $objPdf->SetFont('Arial', '', 5);
    } else {
      $objPdf->setCellHeightRatio(1.15);
      $objPdf->SetFont('Arial', '', 5);     
    }

    $objPdf->SetXY(21, 50.5);
    $objPdf->MultiCell(175, 5, $sAllPos, 0, "L");
    $objPdf->SetFont('Arial', '', 7);
    $objPdf->setCellHeightRatio(2);

    if(strlen($sStyles) <= 230){
      $objPdf->SetFont('Arial', '', 7);
    } else if(strlen($sStyles) <= 272){
      $objPdf->setCellHeightRatio(2.5);
      $objPdf->SetFont('Arial', '', 6);
    } else if(strlen($sStyles) <= 320){
      $objPdf->setCellHeightRatio(3);
      $objPdf->SetFont('Arial', '', 5);
    } else {
      $objPdf->setCellHeightRatio(1.15);
      $objPdf->SetFont('Arial', '', 5);     
    }

    $objPdf->SetXY(26, 61);
    $objPdf->MultiCell(175, 5, $sStyles, 0, "L");
    $objPdf->SetFont('Arial', '', 7);
    $objPdf->setCellHeightRatio(2);

		if(strlen($sStylesName) <= 230){
			$objPdf->SetFont('Arial', '', 7);
		} else if(strlen($sStylesName) <= 272){
			$objPdf->setCellHeightRatio(2.5);
			$objPdf->SetFont('Arial', '', 6);
		} else if(strlen($sStylesName) <= 320){
			$objPdf->setCellHeightRatio(3);
			$objPdf->SetFont('Arial', '', 5);
		} else {
			$objPdf->setCellHeightRatio(1.15);
			$objPdf->SetFont('Arial', '', 5);			
		}

	  $objPdf->SetXY(32, 71.5);
	  $objPdf->MultiCell(175, 5, $sStylesName, 0, "L");
		$objPdf->SetFont('Arial', '', 7);
		$objPdf->setCellHeightRatio(2);
		
    if(strlen($sColors) <= 230){
      $objPdf->SetFont('Arial', '', 7);
    } else if(strlen($sColors) <= 272){
      $objPdf->setCellHeightRatio(2.5);
      $objPdf->SetFont('Arial', '', 6);
    } else if(strlen($sColors) <= 320){
      $objPdf->setCellHeightRatio(3);
      $objPdf->SetFont('Arial', '', 5);
    } else {
      $objPdf->setCellHeightRatio(1.15);
      $objPdf->SetFont('Arial', '', 5);     
    }

    $objPdf->SetXY(26.5, 92.5);
    $objPdf->MultiCell(175, 5, $sColors, 0, "L");
    $objPdf->SetFont('Arial', '', 7);
    $objPdf->setCellHeightRatio(2);

    if(strlen($sSizeTitles) <= 230){
      $objPdf->SetFont('Arial', '', 7);
    } else if(strlen($sSizeTitles) <= 272){
      $objPdf->setCellHeightRatio(2.5);
      $objPdf->SetFont('Arial', '', 6);
    } else if(strlen($sSizeTitles) <= 320){
      $objPdf->setCellHeightRatio(3);
      $objPdf->SetFont('Arial', '', 5);
    } else {
      $objPdf->setCellHeightRatio(1.15);
      $objPdf->SetFont('Arial', '', 5);     
    }

    $objPdf->SetXY(21, 103);
    $objPdf->MultiCell(175, 5, $sSizeTitles, 0, "L");
    $objPdf->SetFont('Arial', '', 7);

		$objPdf->setCellHeightRatio(1);

		$objPdf->Text(35, 83.3, $allDestinationString);
		$objPdf->Text(183, 83.3, $iTotalCartons);

	  $sAuditStage = strtoupper($sAuditStage);

		$objPdf->Text(156, 88.5, $sAuditStage);
		$objPdf->Text(62, 88.5, $sAuditStatus);

		$objPdf->SetFont('Arial', '', 8);
		$objPdf->Text(27, 244.5, $sSignaturesInspector);
		$objPdf->Text(117, 244.5, $sSignaturesManufacturer);

		$objPdf->SetFont('Arial', '', 7);

		
		$sInspectorImg = '';
		$sManufacturerImg = '';

		if (@file_exists($sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/{$sAuditCode}_manufacturer.jpg"))
			$sManufacturerImg = $sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/{$sAuditCode}_manufacturer.jpg";
		
		if (@file_exists($sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/{$sAuditCode}_inspector.jpg"))
			$sInspectorImg = $sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/{$sAuditCode}_inspector.jpg";
		

		if($sInspectorImg)
		$objPdf->Image($sInspectorImg, 63, 240, 29);

		if($sManufacturerImg)
		$objPdf->Image($sManufacturerImg, 160, 240, 29);

	  $objPdf->SetFont('Arial', '', 7);
		$objPdf->SetXY(7, 200);

		if($sComments == 'N/A')
			$sComments = '';

		$objPdf->SetXY(7, 200);
		$objPdf->MultiCell(230, 3, $sComments, 0, "L");

		$items = getDbValue ("items", "tbl_reports", "id = '$iReportId'");

		$itemsArray = explode(",", $items);

		$sSQL = "SELECT * FROM tbl_qa_checklist WHERE id IN($items) ORDER BY field_type DESC";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		$y = 130;

		$objPdf->SetXY(7, $y);
		$objPdf->SetFont('Arial', 'B', 7);
		$objPdf->MultiCell(60,5,strtoupper('Approved Sample'),0,"L");
		$objPdf->SetFont('Arial', '', 6);

		$objPdf->SetXY(65, $y);
		$objPdf->Cell(3, 3, 'YES');
		$objPdf->SetXY(73, $y);
		$objPdf->Cell(3, 3, '', 1);

		if(strtolower($sApprovedSample) == 'yes' || strtolower($sApprovedSample) == 'y'){
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 73.2, $y+0.2, 2.5);
		}

		$objPdf->SetXY(78, $y);
		$objPdf->Cell(3, 3, 'NO');
		$objPdf->SetXY(85, $y);
		$objPdf->Cell(3, 3, '', 1);

		if(strtolower($sApprovedSample) == 'no' || strtolower($sApprovedSample) == 'n'){
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 85.2, $y+0.2, 2.5);
		}		

		$x = 0;
		$fieldCount = 1;
		$secondCol = 0;

		for ($i = 0; $i < $iCount; $i ++)
		{
			$itemId = $objDb->getField($i, "id");
			$itemName = $objDb->getField($i, "item");
			$fieldType = $objDb->getField($i, "field_type");

			$checkValue = getDbValue ("check_value", "tbl_qa_checklist_results", "item_id = '$itemId' AND audit_id = '$Id'");
			$textValue = getDbValue ("text_value", "tbl_qa_checklist_results", "item_id = '$itemId' AND audit_id = '$Id'");

			// if($fieldType == 'YN' || $fieldType == 'NF' || $fieldType == 'TF' || $fieldType == 'CB') {

			// 	$fieldCount++;

			// } else if( $fieldType == 'CC') {

			// 	$fieldCount = $fieldCount+2;
			// }

			// if($fieldCount > 10 && $secondCol == '0'){

			// 	$x = 99;
			// 	$y = 125;

			// 	$objPdf->SetXY($x, $y);

			// 	$secondCol = 1;
			// }

			$y = $objPdf->GetY();

			if($y > 170) {

				$x = 99;
				$y = 125;

				$objPdf->SetXY($x, $y);

				$secondCol = 1;				
			}

			if($fieldType == 'YN') {

				$y = $objPdf->GetY()+5.5;

				$objPdf->SetXY($x+7, $y+0.5);
				$objPdf->SetFont('Arial', 'B', 7);
				$objPdf->MultiCell(60,5,strtoupper($itemName),0,"L");

				$objPdf->SetFont('Arial', '', 6);
				$objPdf->SetXY($x+65, $y);

				$yText = "YES";

				if($iReportId == '55')
					$yText = "PASS";

				$objPdf->Cell(3, 3, $yText);
				$objPdf->SetXY($x+73, $y);
				$objPdf->Cell(3, 3, '', 1);

				if($checkValue == 'Y'){
					$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), $x+73.2, $y+0.2, 2.5);
				}

				$objPdf->SetXY($x+78, $y);

				$nText = "NO";

				if($iReportId == '55')
					$nText = "FAIL";

				$objPdf->Cell(3, 3, $nText);
				$objPdf->SetXY($x+85, $y);
				$objPdf->Cell(3, 3, '', 1);

				if($checkValue == 'N'){
					$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), $x+85.2, $y+0.2, 2.5);
				}

				$objPdf->SetXY($x+91, $y);
				$objPdf->Cell(3, 3, 'N/A');
				$objPdf->SetXY($x+97, $y);
				$objPdf->Cell(3, 3, '', 1);

				if($checkValue == 'NA'){
					$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), $x+97.2, $y+0.2, 2.5);
				}

			} else if($fieldType == 'CC') {

				$y = $objPdf->GetY()+5.5;

				$objPdf->SetXY($x+7, $y+0.5);
				$objPdf->SetFont('Arial', 'B', 7);
				$objPdf->MultiCell(60,5,strtoupper($itemName),0,"L");

				$objPdf->SetFont('Arial', '', 6);
				$objPdf->SetXY($x+65, $y);
				$objPdf->Cell(3, 3, 'YES');
				$objPdf->SetXY($x+73, $y);
				$objPdf->Cell(3, 3, '', 1);

				if($checkValue == 'Y'){
					$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), $x+73.2, $y+0.2, 2.5);
				}

				$objPdf->SetXY($x+78, $y);
				$objPdf->Cell(3, 3, 'NO');
				$objPdf->SetXY($x+85, $y);
				$objPdf->Cell(3, 3, '', 1);

				if($checkValue == 'N'){
					$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), $x+85.2, $y+0.2, 2.5);
				}

				$objPdf->SetXY($x+91, $y);
				$objPdf->Cell(3, 3, 'N/A');
				$objPdf->SetXY($x+97, $y);
				$objPdf->Cell(3, 3, '', 1);

				if($checkValue == 'NA'){
					$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), $x+97.2, $y+0.2, 2.5);
				}

				$textValue = str_replace("\n", ", ", $textValue);

				$objPdf->SetFont('Arial', '', 6);
				$objPdf->SetXY($x+66, $y+4);
				$objPdf->setCellPaddings(0.5,0.5,0.5,0.5);
				$objPdf->setCellHeightRatio(1.2);
				$objPdf->MultiCell(34, 1, $textValue, 1,"L", false);
				$objPdf->setCellHeightRatio(1);
				$objPdf->setCellPaddings(1,0,0,0);

				$y = $objPdf->GetY();
				$objPdf->SetY($y-3);

				// $fieldCount = $fieldCount+2;

			} else if($fieldType == 'NF' || $fieldType == 'TF') {

				$y = $objPdf->GetY()+5.5;

				$objPdf->SetXY(7, $y+0.5);
				$objPdf->SetFont('Arial', 'B', 7);
				$objPdf->MultiCell(60,5,strtoupper($itemName),0,"L");
				$objPdf->SetFont('Arial', '', 6);
				$objPdf->SetXY($x+66, $y);
				$objPdf->setCellPaddings(0.5,0.5,0.5,0.5);
				$objPdf->setCellHeightRatio(1.2);
				$objPdf->MultiCell(34, 1, $textValue, 1,"L", false);
				$objPdf->setCellHeightRatio(1);
				$objPdf->setCellPaddings(1,0,0,0);
				$objPdf->SetY($y+1);

			} else if($fieldType == 'CB') {

				$y = $objPdf->GetY()+5.5;

				$objPdf->SetXY($x+7, $y+0.5);
				$objPdf->SetFont('Arial', 'B', 7);
				$objPdf->MultiCell(60,5,strtoupper($itemName),0,"L");

				$objPdf->SetFont('Arial', '', 6);
				$objPdf->SetXY($x+65, $y);
				$objPdf->Cell(3, 3, 'YES');
				$objPdf->SetXY($x+73, $y);
				$objPdf->Cell(3, 3, '', 1);

				if($checkValue == 'Y'){
					$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), $x+73.2, $y+0.2, 2.5);
				}

				$objPdf->SetXY($x+78, $y);
				$objPdf->Cell(3, 3, 'NO');
				$objPdf->SetXY($x+85, $y);
				$objPdf->Cell(3, 3, '', 1);

				if($checkValue == 'N'){
					$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), $x+85.2, $y+0.2, 2.5);
				}			
			}

		}

// /////////////////////////////////////////////////PAGE 5 - MEASUREMENT SPECS SUMMARY ///////////////////////// 

if(in_array('12', $AllowedSections)){

	  $iCurrentPage++;
	  $iTemplateId = $objPdf->importPage(5, '/MediaBox');

	  $objPdf->addPage("P", "A4");
	  $objPdf->useTemplate($iTemplateId, 0, 0);

		$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178.5, 9.5, 23);

		$objPdf->SetFont('Arial', '', 10);
		$objPdf->SetTextColor(255, 255, 255);

		$objPdf->Text(161, 19.4, "{$sAuditCode}");
		$objPdf->Text(66.5, 19.4, "{$sReportType}");

		$objPdf->SetFont('Arial', '', 9);
		$objPdf->SetTextColor(169, 169, 169);
		$objPdf->Text(162.5, 30.6, "{$iCurrentPage} of {$iTotalPages}");

		$objPdf->Image(($sBaseDir.BRANDS_IMG_DIR."{$sBrandImagePath}"), 8, 6.1, 29);

		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(50, 50, 50);

    $sSizes  = getDbValue("GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',')", "tbl_qa_report_samples", "audit_id='$Id'");
    $iSizes  = @explode(",", $sSizes);

    $objPdf->Text(42, 55, getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id IN ($sSizes)"));
    $objPdf->Text(42, 60, getDbValue("GROUP_CONCAT(size SEPARATOR ',')", "tbl_sampling_sizes", "id IN ($sSizes)"));

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

    $iTop               = 182;
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

            $objPdf->SetXY(11, $iTop);
            $objPdf->MultiCell(120, 1.3, @$sPointList[$iPoint], 0, "L");

            $objPdf->Text(162, $iTop, $sDefectsArr['minor']);
            $objPdf->Text(177, $iTop, $sDefectsArr['major']);
            $objPdf->Text(189, $iTop, number_format($sDefectsArr['percent'],2));

            $iTop += 3.35;
        }   

        $limit++;
    }

    $objPdf->Text(162, 259.3, formatNumber($iTotalMinor, false));
    $objPdf->Text(177, 259.3, formatNumber($iTotalMajor, false));

    //sizez box
    $objPdf->SetFont('Arial', 'B', 7);
    $objPdf->SetTextColor(50, 50, 50);
    
    $sQtyPerSize            = "";
    $iSizeTop               = 74;
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

    $objPdf->Text(97, 55, $iTotalEvaluatedPoints);
    $objPdf->Text(170, 55, $iTotalDefectivePoints);
    $iGenealPercent = ($iTotalDefectivePoints/$iTotalEvaluatedPoints)*100;
    $objPdf->Text(189, 259.3, number_format(($iGenealPercent),2).'%');
    $objPdf->SetFont('Arial', 'B', 7);

    if($iGenealPercent > 20)
    {
       $objPdf->SetTextColor(255, 0, 0);
       $objPdf->Text(190, 55, 'Fail');
    }
    else
    {
       $objPdf->SetTextColor(0, 100, 0);
       $objPdf->Text(190, 55, 'Pass');
    }

    $objPdf->SetFont('Arial', '', 7);

// /////////////////////////////////////////////////PAGE 6 - MEASUREMENT SPECS SHEET ///////////////////////// 	

  $sSizesList   = getList("tbl_sizes", "id", "size", "FIND_IN_SET(id, '$sSizes')", "size");
	$sSizes  = getDbValue("GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',')", "tbl_qa_report_samples", "audit_id='$Id'");
	$iSizes  = @explode(",", $sSizes);
	$sColors = @explode(",", $sColors);

	$iSamplesMeasured = getDbValue("COUNT(size_id)", "tbl_qa_report_samples", "audit_id='$Id'");
	$sQtyPerSize      = "";

		foreach ($iSizes as $iSize)
		{
			if ($sQtyPerSize != "")
				$sQtyPerSize .= ", ";

			$sColor = trim($sColor);

			$sSize = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");

			$sQtyPerSize .= ("{$sSize} (".getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize'").")");
                        
		}

		foreach ($iSizes as $iSize)
		{
			$sColor = trim($sColor);

      $sSQL = "SELECT qrs.sample_no, qrss.point_id, qrss.findings, qrs.nature, qrss.specs
                       FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
                       WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSize'
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
              // $sNatureFindings["{$iSampleNo}"] = (($sNature == 'C')?'(CBM)':"(FBM)");
              $sSizeSpecs["{$iPoint}"] = $sSizeSpec;
      }

			$iCurrentPage++;
			$iTemplateId = $objPdf->importPage(6, '/MediaBox');

			$objPdf->addPage("L", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);

			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 265.6, 7, 23);


			$objPdf->SetFont('Arial', '', 10);
			$objPdf->SetTextColor(255, 255, 255);

			$objPdf->Text(247, 17, "{$sAuditCode}");
			$objPdf->Text(67, 16.9, "{$sReportType}");

			$objPdf->SetFont('Arial', '', 9);
			$objPdf->SetTextColor(169, 169, 169);
			$objPdf->Text(250, 28, "{$iCurrentPage} of {$iTotalPages}");

			$objPdf->Image(($sBaseDir.BRANDS_IMG_DIR."{$sBrandImagePath}"), 10, 5, 28);

                            $sSize = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");

		$objPdf->SetFont('Arial', '', 9);
		$objPdf->SetTextColor(50, 50, 50);		
		$objPdf->Text(26, 43.5, $sSize);

		$objPdf->SetFont('Arial', '', 7);

	    $sSQL = "SELECT point_id, specs, nature,
                      (SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
                      (SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point,
                      (SELECT point_id FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _PointId
	                     FROM tbl_style_specs
	                     WHERE style_id='$iStyle' AND size_id='$iSize' AND version='0'
	                     ORDER BY FIELD(nature, 'C') DESC";
	    
	    $objDb->query($sSQL);
	    $iCount          = $objDb->getCount( );
	    
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
	    $iSamplesChecked = getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize'");

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
	            $objPdf->Text(11, (64.5 + ($LineNo * 3.520)), ($LineNo + 1));
	            
	            if($sPNature == 'C')
	                $objPdf->SetTextColor(255, 0, 0);
	            
	            $objPdf->Text(20.5, (64.5 + ($LineNo * 3.520)), $iPointId);
	                    
	            if(strlen($sPoint) > 48)
	            {
	                $objPdf->SetFont('Arial', '', 5);
	                $objPdf->SetXY(30.5, (63.8 + ($LineNo * 3.545)));
	                $objPdf->MultiCell(80, 1.6, $sPoint, 0, "L");
	                $objPdf->SetFont('Arial', '', 6);
	            }
	            else
	            {
	                $objPdf->SetXY(29, (64.6 + ($LineNo * 3.520)));
	                $objPdf->MultiCell(85, 2.2, $sPoint, 0, "L");
	            }
	            
	            $objPdf->SetTextColor(50, 50, 50);
	            $objPdf->Text(112.5, (64.6 + ($LineNo * 3.560)), $sSpecs);
	            $objPdf->Text(275, (64.6 + ($LineNo * 3.560)), $sTolerance);


	            for ($j = 1; $j <= $iSamplesChecked; $j ++)
	            {                 
	                    // if($sFlags[$j] == 0)
	                    // {
	                    //     $objPdf->Text((110 + ($j * 24.65)), (53 + ($LineNo * 3.560)), $sNatureFindings["$j"]);                                                
	                    //     $sFlags[$j] = 1;
	                    // }
	                    
	                    $sSpecs = floatval($sSpecs);
	                    
	                    if ($sSizeFindings["{$j}-{$iPoint}"] != "" && strtolower($sSizeFindings["{$j}-{$iPoint}"]) != "ok" && $sSizeFindings["{$j}-{$iPoint}"] != "0" && $sSizeFindings["{$j}-{$iPoint}"] != "-") //&& floatval($sSizeFindings["{$j}-{$iPoint}"]) != $sSpecs
	                    {
	                            $fMeaseuredValue  = floatval($sSizeFindings["{$j}-{$iPoint}"]);
	                            $fDifference      = ($fMeaseuredValue - $sSpecs);

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
	                                    $objPdf->SetXY((100.2 + ($j * 24.65)), (64.3 + ($LineNo * 3.560)));
	                                    $objPdf->Cell(9.1, 2.5, $sSizeFindings["{$j}-{$iPoint}"], 0, 0, "C", false);

	                                    $objPdf->SetTextColor(0, 100, 0);
	                                    $objPdf->SetXY((113.5 + ($j * 24.65)), (64.3 + ($LineNo * 3.560)));
	                                    $objPdf->Cell(9.1, 2.5, formatNumber($fDifference, true, 2), 0, 0, "C", false);
	                                    $objPdf->SetTextColor(50, 50, 50);  
	                            }
	                            else if ($fMeaseuredValue >= $fBufferNegativeTolerance && $fMeaseuredValue <= $fBufferPositiveTolerance)//orange block
	                            {                                                            
	                                    
	                                    $objPdf->SetTextColor(50,50,50);
	                                    $objPdf->SetXY((101.2 + ($j * 24.65)), (64.3 + ($LineNo * 3.560)));
	                                    $objPdf->Cell(9.1, 2.5, $sSizeFindings["{$j}-{$iPoint}"], 0, 0, "C", false);

	                                    $objPdf->SetTextColor(255,140,0);
	                                    $objPdf->SetXY((113.5 + ($j * 24.65)), (64.3 + ($LineNo * 3.560)));
	                                    $objPdf->Cell(9.1, 2.5, formatNumber($fDifference, true, 2), 0, 0, "C", false);
	                                    $objPdf->SetTextColor(50,50,50);
	                            }
	                            else
	                            {							//red block
	                                    $objPdf->SetTextColor(50, 50, 50);
	                                    $objPdf->SetXY((101.2 + ($j * 24.65)), (64.3 + ($LineNo * 3.560)));
	                                    $objPdf->Cell(9.1, 2.5, $sSizeFindings["{$j}-{$iPoint}"], 0, 0, "C", false);

	                                    $objPdf->SetTextColor(255, 0, 0);
	                                    $objPdf->SetXY((113.5 + ($j * 24.65)), (64.3 + ($LineNo * 3.560)));
	                                    $objPdf->Cell(9.1, 2.5, formatNumber($fDifference, true, 2), 0, 0, "C", false);
	                                    $objPdf->SetTextColor(50,50,50);
	                                    
	                                    $iOut ++;
	                            }
	                    }
	                    else
	                    {       
	                            //Empty Case
	                            // $objPdf->SetTextColor(50, 50, 50);
	                            // $objPdf->SetXY((101.2 + ($j * 24.65)), (64.3 + ($LineNo * 3.560)));
	                            // $objPdf->Cell(9.1, 2.5, "N/A", 0, 0, "C", false);
	                            
	                            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (105.2 + ($j * 24.50)), (64.3 + ($LineNo * 3.560)), 2);

	                            $objPdf->SetXY((113.5 + ($j * 24.65)), (64.3 + ($LineNo * 3.560)));
	                            $objPdf->Cell(9.1, 2.5, "0", 0, 0, "C", false);
	                    }
	            }
	            
	            $LineNo++;
	    }
	    

	    $objPdf->SetFont('Arial', '', 9);
	    $objPdf->SetTextColor(50, 50, 50);

	    $objPdf->SetXY(80,174);
	    $objPdf->Cell(9.1, 2.5, ($iCount* 1), 0, 0, "C", false);

	    $objPdf->SetXY(175,174);
	    $objPdf->Cell(9.1, 2.5, $iOut, 0, 0, "C", false);

	    if ($sMeasurementResult == "P")
	            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 28, 240, 4);

	    else if ($sMeasurementResult == "F")
	            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 77.5, 240, 4);

	    else if ($sMeasurementResult == "H")
	            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 131, 240, 4);
                   
		}

// /////////////////////////////////////////////////PAGE 6 - MEASUREMENT SPECS SHEET ///////////////////////// 
/*
	$sSQL = "SELECT * FROM tbl_qa_report_samples WHERE audit_id='$Id' GROUP BY style_id,size_id,sample_no ORDER BY size_id";

	$objDb->query($sSQL);
	$iCount = $objDb->getCount( );

	$previousAddedSizes = array();
	$counter = 0;
	$countOutOfTol = 0;

	for($i=0; $i<$iCount; $i++) {

		$iSampleId = $objDb->getField($i, "id");
		$iSizeId = $objDb->getField($i, "size_id");
		$iStyleId = $objDb->getField($i, "style_id");
		$sSize = $objDb->getField($i, "size");
		$sSampleNo = $objDb->getField($i, "sample_no");
		$sResult = $objDb->getField($i, "result");

		if($sSize == ""){
			$sSize = getDbValue ("size", "tbl_sampling_sizes", "id = '$iSizeId'");
		}

		if(!@in_array($iSizeId, $previousAddedSizes)){

			$iCurrentPage++;
			$iTemplateId = $objPdf->importPage(6, '/MediaBox');

			$objPdf->addPage("L", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);

			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 265.6, 7, 23);


			$objPdf->SetFont('Arial', '', 10);
			$objPdf->SetTextColor(255, 255, 255);

			$objPdf->Text(247, 17, "{$sAuditCode}");
			$objPdf->Text(67, 16.9, "{$sReportType}");

			$objPdf->SetFont('Arial', '', 9);
			$objPdf->SetTextColor(169, 169, 169);
			$objPdf->Text(250, 28, "{$iCurrentPage} of {$iTotalPages}");

			$objPdf->Image(($sBaseDir.BRANDS_IMG_DIR."{$sBrandImagePath}"), 10, 5, 28);

			$newSize = true;
			$totalPointPerSize = 0;

		} else {
			$newSize = false;
		}
		
		$objPdf->SetFont('Arial', '', 9);
		$objPdf->SetTextColor(50, 50, 50);		
		$objPdf->Text(26, 43.5, $sSize);

		$objPdf->SetFont('Arial', '', 7);

		$sSQL2 = "SELECT rss.findings, s.specs, s.style_id, 
							(SELECT point FROM  tbl_measurement_points WHERE id= s.point_id) AS _Point,
							(SELECT point_id FROM  tbl_measurement_points WHERE id= s.point_id) AS _PointId,  
							(SELECT tolerance FROM  tbl_measurement_points WHERE id= s.point_id) AS _Tolerance 
							FROM tbl_qa_report_sample_specs rss, tbl_style_specs s 
							WHERE rss.point_id = s.point_id AND rss.sample_id='$iSampleId' AND s.size_id = '$iSizeId' AND s.specs <> '' GROUP BY rss.point_id";

		$objDb2->query($sSQL2);
		$iCount2 = $objDb2->getCount( );	
		
		$y = 61;

		@list($actualX,$differenceX) = @getSampleXPosition($sSampleNo);

		for($j=0; $j<$iCount2; $j++) {

			$sFindings = $objDb2->getField($j, "findings");
			$sPoint = $objDb2->getField($j, "_Point");
			$sPointId = $objDb2->getField($j, "_PointId");
			$sTolerance = $objDb2->getField($j, "_Tolerance");
			$sSpecs = $objDb2->getField($j, "specs");

			$totalPointPerSize++;
			$y = $y+3.54;

			$objPdf->SetTextColor(50, 50, 50);				
			
			if($newSize){
				
				$objPdf->Text(9, $y, $counter+1);
				$objPdf->Text(18, $y, $sPointId);
				$objPdf->Text(30, $y, $sPoint);
				$objPdf->Text(112, $y, $sSpecs);
				$objPdf->SetTextColor(50, 50, 50);
				$objPdf->Text(275, $y, $sTolerance);			
			}

			$objPdf->Text($actualX, $y, $sFindings);

			$result  = getTolerance($sFindings,$sSpecs,$sTolerance);

			if($result == 'fail'){

				$countOutOfTol++;
			}

			if($sFindings != 'ok' && $sFindings != '0'){

				$difference = $sFindings - $sSpecs;

				$difference = round($difference,3);
				
				if($result == 'fail')
					$objPdf->SetTextColor(236, 34, 39);

				$objPdf->Text($differenceX, $y, $difference);
			}

			$counter++;

		}	

		if($i+1 == $iCount) {

			$totalPoints = $counter;

			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(80, 174.5, $totalPoints);
			$objPdf->Text(175, 174.5, $countOutOfTol);
		
		} else {

			$iNextSizeId = $objDb->getField($i+1, "size_id");

			if($iSizeId != $iNextSizeId) {

				$totalPoints = $counter;

				$objPdf->SetFont('Arial', '', 9);
				$objPdf->Text(80, 174.5, $totalPoints);
				$objPdf->Text(175, 174.5, $countOutOfTol);					

				$countOutOfTol = 0;
			}

			$counter = 0;			
		}

		array_push($previousAddedSizes, $iSizeId);
	}
*/
}

// /////////////////////////////////////////////////PAGE 7 - AQL SAMPLE PLANS///////////////////////// 

	$iCurrentPage++;	
	
	$iTemplateId = $objPdf->importPage(7, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178.6, 9.5, 23);


	$objPdf->SetFont('Arial', '', 10);
	$objPdf->SetTextColor(255, 255, 255);

	$objPdf->Text(160, 19.5, "{$sAuditCode}");
	$objPdf->Text(66, 19.4, "{$sReportType}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->SetTextColor(169, 169, 169);
	$objPdf->Text(162, 30.7, "{$iCurrentPage} of {$iTotalPages}");

	$objPdf->Image(($sBaseDir.BRANDS_IMG_DIR."{$sBrandImagePath}"), 8, 6.1, 29);

	if($brandInspectionLevel == '1')
		$brandInspectionLevelText = 'I';
	else if($brandInspectionLevel == '2')
		$brandInspectionLevelText = 'II';
	else if($brandInspectionLevel == '3')
		$brandInspectionLevelText = 'III';
	else 
		$brandInspectionLevelText = '';

	$objPdf->SetFont('Arial', '', 10);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(21, 53.2, $brandInspectionLevelText);
	$objPdf->Text(17, 58.4, $brandAQL);

	$objPdf->SetXY(30, 50);
	
	$objPdf->SetAlpha(0.3);
	$objPdf->SetFillColor(100,0,0);

	$x= 0;
	$y=0;
	$letter = '';
	
	if($sCumolativeLotSize != ""){

		@list($x, $y, $letter) = getSampleCodePosition($sCumolativeLotSize, $brandInspectionLevel);

		$objPdf->Circle($x,$y,2,0,360,'F');

		$codeY = getSampleSizeByCode($letter);

		$objPdf->Rect(26.5, $codeY, 157, 4.2, 'DF');

		$AQLX = getAQLPosition($brandAQL);

		$objPdf->Rect($AQLX, 175.2, 11.5, 7, 'DF');

	} else {

		$codeY = getSampleSizeBySampleSize($iTotalGmts);

		$objPdf->Rect(26.5, $codeY, 157, 4.2, 'DF');

		$AQLX = getAQLPosition($brandAQL);

		$objPdf->Rect($AQLX, 175.2, 11.5, 7, 'DF');
		
	}


// /////////////////////////////////////////////////PAGE 8 - INSPECTION LOCATION///////////////////////// 

	$iCurrentPage++;
	$iTemplateId = $objPdf->importPage(8, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178.6, 9.5, 23);


	$objPdf->SetFont('Arial', '', 10);
	$objPdf->SetTextColor(255, 255, 255);

	$objPdf->Text(160, 19.5, "{$sAuditCode}");
	$objPdf->Text(66, 19.4, "{$sReportType}");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->SetTextColor(169, 169, 169);
	$objPdf->Text(162, 30.7, "{$iCurrentPage} of {$iTotalPages}");

	$objPdf->Image(($sBaseDir.BRANDS_IMG_DIR."{$sBrandImagePath}"), 8, 6.1, 29);

	$objPdf->SetTextColor(50, 50, 50);

	$auditText = "This Inspection as per Schedule has been conducted @ ".$sVendorName." with the following address information on the Inspection Portal.";

	$objPdf->SetXY(7,48);
	$objPdf->MultiCell(190, 5, $auditText, 0, "L");
	$objPdf->SetFont('Arial', '', 12);

	if ($sVendorLatitude != "" && $sVendorLongitude != "" && $sLatitude != "" && $sLongitude != "")
  {
      $sDistance = calculateDistance($sVendorLatitude, $sVendorLongitude, $sLatitude, $sLongitude);
			
			$sDistanceVal= floatval($sDistance);

			if($sDistanceVal < 1 || strpos($sDistance, 'Meter')){
				$objPdf->SetFillColor(60,183,117);
			} else if($sDistanceVal < 2) {
				$objPdf->SetFillColor(244,163,97);
			} else {
				$objPdf->SetFillColor(221,0,0);
			}

      $objPdf->rect(9.5, 212.4, 42.1, 9.5,'F');
      $objPdf->Text(22, 215, $sDistance);            
  }

  $objPdf->SetFont('Arial', '', 7);

  if($sLatitude == "" && $sLongitude == ""){

    $objPdf->Text(97, 71.3, "Location coordinates are not available.");

  }
  if($sVendorLatitude == "" && $sVendorLongitude == ""){

    $objPdf->Text(97, 80.2, "Location coordinates are not available.");

  }

  if ($sLatitude != "" && $sLongitude != "")
	{	
		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(6, 82, 195);

    $sLocation = trim(trim(str_replace("\n", ", ", $sLocation)), ",");
    
    $objPdf->SetXY(95.5, 69.7);
		$objPdf->Write(5, "{$sLocation} (". formatNumber($sLatitude, true, 8).",". formatNumber($sLongitude, true, 8).")", "http://maps.google.com/maps?q={$sLatitude},{$sLongitude}&z=12");
		$objPdf->SetXY(130.5, 69.5);
		$objPdf->SetTextColor(50,50,50);
    $objPdf->Write(5,"(Click on the link to open location in Google Maps)");

    $objPdf->SetFont('Arial', '', 7);
    $objPdf->SetTextColor(50, 50, 50);
   
    if($sVendorLatitude != "" && $sVendorLongitude != "")
    {
        $objPdf->SetFont('Arial', '', 7);
        $objPdf->SetTextColor(6, 82, 195);
    
        $objPdf->SetXY(93, 78.5);
        $objPdf->Write(5, "(". formatNumber($sVendorLatitude, true, 8).",". formatNumber($sVendorLongitude, true, 8).")", "http://maps.google.com/maps?q={$sVendorLatitude},{$sVendorLongitude}&z=12");

        $objPdf->SetFont('Arial', '', 7);
        $objPdf->SetXY(128, 78.5);
        $objPdf->SetTextColor(50, 50, 50);
				$objPdf->Write(5,"(Click on the link to open location in Google Maps)");
    
        $map = getFileContents("http://maps.googleapis.com/maps/api/staticmap?center=".$sLatitude.",".$sLongitude."&zoom=11&size=1000x450&markers=color:red|".$sLatitude.",".$sLongitude."&markers=color:black|".$sVendorLatitude.",".$sVendorLongitude."&key=AIzaSyBNvUKRlOI0Nzqv3MMA63P9_vAH3bYwtc8"); 
    }
    else
    {

      $map = getFileContents("http://maps.googleapis.com/maps/api/staticmap?center=".$sLatitude.",".$sLongitude."&zoom=11&size=1000x450&markers=color:red|".$sLatitude.",".$sLongitude."&key=AIzaSyBNvUKRlOI0Nzqv3MMA63P9_vAH3bYwtc8");
    }

	    $image = imagecreatefromstring($map);
	    $saved = imagejpeg($image, $sBaseDir."temp2/googlemapImage.jpg");
	    unset($map);
	                    
	    $objPdf->Image($sBaseDir.'temp2/googlemapImage.jpg',  9, 88.7,192,108.5);
	    unlink($sBaseDir.'temp2/googlemapImage.jpg');
	} 
  else if ($sVendorLatitude != "" && $sVendorLongitude != "")
	{	
		$sLocation = trim(trim(str_replace("\n", ", ", $sLocation)), ",");

		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(6, 82, 195);
		
		$objPdf->SetXY(92, 78.5);
		$objPdf->Write(5, "{$sLocation} ({$sVendorLatitude},{$sVendorLongitude})", "http://maps.google.com/maps?q={$sVendorLatitude},{$sVendorLongitude}&z=12");
                
      $map = getFileContents("https://maps.googleapis.com/maps/api/staticmap?center=".$sVendorLatitude.",".$sVendorLongitude."&zoom=11&size=1000x450&markers=color:black|".$sVendorLatitude.",".$sVendorLongitude."&key=AIzaSyDKes4Df5NgRtYcQ_muoqUadWiI3v6GURg"); 
      $image = imagecreatefromstring($map);
      $saved = imagejpeg($image, $sBaseDir."temp2/googlemapImage.jpg");
      unset($map);
      
      $objPdf->Image($sBaseDir.'temp2/googlemapImage.jpg',  9, 88.7, 192,108.5);
      unlink($sBaseDir.'temp2/googlemapImage.jpg');
	}

// /////////////////////////////////////////////////PAGE 9 - DEFECT CODE LIST///////////////////////// 

		$iCurrentPage++;
		$iTemplateId = $objPdf->importPage(9, '/MediaBox');
		$objPdf->addPage("P", "A4");
		$objPdf->useTemplate($iTemplateId, 0, 0);

		$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178.5, 9.5, 23);

		$objPdf->SetFont('Arial', '', 10);
		$objPdf->SetTextColor(255, 255, 255);

		$objPdf->Text(160, 19.5, "{$sAuditCode}");
		$objPdf->Text(66, 19.4, "{$sReportType}");

		$objPdf->SetFont('Arial', '', 9);
		$objPdf->SetTextColor(169, 169, 169);
		$objPdf->Text(162, 30.7, "{$iCurrentPage} of {$iTotalPages}");

		$objPdf->Image(($sBaseDir.BRANDS_IMG_DIR."{$sBrandImagePath}"), 8, 6.1, 29);

		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(0, 0, 0);

		$objPdf->SetXY(8,47);

		if($userLanguage != 'en'){

			$typeField = "type_".$userLanguage;

		} else {

			$typeField = "type";
		}

		$sDefectTypesList = getList("tbl_defect_types", "id", $typeField, "id IN (SELECT DISTINCT(type_id) FROM tbl_defect_codes WHERE report_id = '$iReportId') AND $typeField <> '' ");

		if($userLanguage != 'en' && count($sDefectTypesList) == 0){

			$sDefectTypesList = getList("tbl_defect_types", "id", "type", "id IN (SELECT DISTINCT(type_id) FROM tbl_defect_codes WHERE report_id = '$iReportId')");

		}

		$x = 8;
		$totalLength = 0;

		$typeIds = array();
		$types = array();

    foreach ($sDefectTypesList as $id => $type) {

      array_push($typeIds, $id);
      array_push($types, $type);
    }
		
		$previousType = '';
		$typeCounter = 0;

		$addedDefectCodes = array();
		$remainingDefectCodes = array();

    for($t=0; $t<count($typeIds); $t++){

			$id = $typeIds[$t];
			$type = $types[$t];

			if($userLanguage != 'en'){

				$defectField = "dc.defect_".$userLanguage;

			} else {

				$defectField = "dc.defect";
			}

			$remainingCodeString = "";
			$codeCondition = "";

			if(count($remainingDefectCodes) > 0 && $previousType == $type) {
				$remainingCodeString = implode("','", $remainingDefectCodes);
				$remainingDefectCodes = array();
			}

			if($remainingCodeString != ""){
				$codeCondition = " AND dc.code IN('".$remainingCodeString."')";
			}

			$sDefectCodes = getList("tbl_defect_codes dc, tbl_defect_types dt ", "dc.code", $defectField, "dc.type_id = dt.id AND dc.type_id = '$id' AND dc.report_id = '$iReportId' AND $defectField <> '' ".$codeCondition, "dc.code");

			if($userLanguage != 'en' && count($sDefectCodes) == 0){

				$sDefectCodes = getList("tbl_defect_codes dc, tbl_defect_types dt ", "dc.code", "dc.defect", "dc.type_id = dt.id AND dc.type_id = '$id' AND dc.report_id = '$iReportId' ".$codeCondition, "dc.code");

			}

			$y = $objPdf->GetY()+10;
			
			$lowLevel = $objPdf->GetY() + (count($sDefectCodes) * 3.6);

			if($lowLevel > 250) {
				$x = 110;
				$y = 57;
				$totalLength++;
			}

			if($totalLength == '2'){

				$totalLength = 0;	

				$iCurrentPage++;
				$iTemplateId = $objPdf->importPage(9, '/MediaBox');
				$objPdf->addPage("P", "A4");
				$objPdf->useTemplate($iTemplateId, 0, 0);

				$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178.5, 9.5, 23);

				$objPdf->SetFont('Arial', '', 10);
				$objPdf->SetTextColor(255, 255, 255);

				$objPdf->Text(160, 19.5, "{$sAuditCode}");
				$objPdf->Text(66, 19.4, "{$sReportType}");

				$objPdf->SetFont('Arial', '', 9);
				$objPdf->SetTextColor(169, 169, 169);
				$objPdf->Text(162, 30.7, "{$iCurrentPage} of {$iTotalPages}");

				$objPdf->Image(($sBaseDir.BRANDS_IMG_DIR."{$sBrandImagePath}"), 8, 6.1, 29);

				$objPdf->SetFont('Arial', '', 7);
				$objPdf->SetTextColor(0, 0, 0);

				// $objPdf->SetXY(8,47);	
				$x = 8;
				$y = 56.5;
						
			}

			$objPdf->SetFont('Arial', 'B',7);

			$objPdf->Text($x,$y,$type);

			$objPdf->SetXY($x,$y);

			$objPdf->setCellHeightRatio(1.5);

			$defectCodeCount = 0;

			foreach ($sDefectCodes as $code => $defect) {

				$y = $objPdf->GetY();

				$y = $y+3.6;

				$defectCodeCount++;

				if($defectCodeCount > 56) {

					array_push($remainingDefectCodes, $code);

					continue;
				
				} else {

					array_push($addedDefectCodes, $code);
					
				}

				$objPdf->SetFont('Arial', '',6);
				$objPdf->SetXY($x,$y);
				$objPdf->MultiCell(15, 3.6, $code, 1, "L", false);
				$objPdf->SetXY($x+15,$y);

				if(strlen($defect) >= '100'){

					$objPdf->setCellHeightRatio(0.8);
					$objPdf->SetFont('Arial', '',5);

				} else if(strlen($defect) >= '80'){

					$objPdf->SetFont('Arial', '',5);

				}  else {
					$objPdf->SetFont('Arial', '',6);
				}

					$objPdf->MultiCell(80, 3.6, $defect, 1, "L", false);
					$objPdf->SetFont('Arial', '',6);
					$objPdf->setCellHeightRatio(1.5);

					$objPdf->SetXY($x,$y);
			}

			$objPdf->setCellHeightRatio(1);

			if(count($remainingDefectCodes) > 0){

				$previousType = $type;
			}

			if($previousType == $type  && $typeCounter == '0') {
				$t = $t-1;
				$typeCounter++;
			} else {
				$addedDefectCodes = array();
			}		

		}

// /////////////////////////////////////////////////PAGE 10 - DEFECT IMAGES///////////////////////// 

	if (count($sDefects) > 0)
	{
		$iTemplateId = $objPdf->importPage(10, '/MediaBox');

		$iPages = @ceil(count($sDefects) / 4);
		$iIndex = 0;
		$counter = 0;

		for ($i = 0; $i < $iPages; $i ++)
		{

			if($i == 0){
				$startValue = 0;
				$endValue = 4;
			} else {
				// $startValue = $startValue;
				$endValue = $endValue+4;
			}

			$iCurrentPage ++;
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);

			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178.6, 9.6, 23);

			$objPdf->SetFont('Arial', '', 10);
			$objPdf->SetTextColor(255, 255, 255);

			$objPdf->Text(160, 19.5, "{$sAuditCode}");
			$objPdf->Text(66, 19.4, "{$sReportType}");

			$objPdf->SetFont('Arial', '', 9);
			$objPdf->SetTextColor(169, 169, 169);
			$objPdf->Text(162, 30.7, "{$iCurrentPage} of {$iTotalPages}");

			$objPdf->Image(($sBaseDir.BRANDS_IMG_DIR."{$sBrandImagePath}"), 8, 6.1, 29);

			$objPdf->SetTextColor(50, 50, 50);
			$objPdf->SetFont('Arial', '', 7);

			$loopCount = 0;

			for ($j = $startValue; $j < $endValue; $j ++)
			{

				$startValue++;

				if($counter == count($sDefects)){
					break;
				}

				$ImageValueArray = explode("|-|", $sDefects[$j]);

				$iDefectId = $ImageValueArray[0];
				$sPicture = $ImageValueArray[1];

				$iCode = getDbValue ("code_id", "tbl_qa_report_defects", "id = '$iDefectId'");
				$sAreaCode = getDbValue ("area_id", "tbl_qa_report_defects", "id = '$iDefectId'");
				$sDefectCode = getDbValue("code","tbl_defect_codes","id='$iCode'");
				$sSampleNo = getDbValue ("sample_no", "tbl_qa_report_defects", "id = '$iDefectId'");

			if($userLanguage != 'en'){

				$defectField = "defect_".$userLanguage;
				$typeField = "type_".$userLanguage;

			} else {

				$defectField = "defect";
				$typeField = "type";
			}

				$sSQL = "SELECT {$defectField} AS defect,
								(SELECT {$typeField} FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
						 FROM tbl_defect_codes dc
						 WHERE code='$sDefectCode' AND report_id='$iReportId'";

				$objDb->query($sSQL);

				$sDefect = $objDb->getField(0, "defect");
				$sType   = $objDb->getField(0, "_Type");

				if($sDefect == "" || $sType == "") {

					$sSQL = "SELECT defect,
									(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
							 FROM tbl_defect_codes dc
							 WHERE code='$sDefectCode' AND report_id='$iReportId'";

					$objDb->query($sSQL);

					$sDefect = $objDb->getField(0, "defect");
					$sType   = $objDb->getField(0, "_Type");
				}

				if($userLanguage != 'en'){

					$sArea = getDbValue("area_{$userLanguage}","tbl_defect_areas","id = '$sAreaCode'");
					
					if($sArea == ""){
			      $sArea = getDbValue("area","tbl_defect_areas","id = '$sAreaCode'");
					}
				} else {
		      $sArea = getDbValue("area","tbl_defect_areas","id = '$sAreaCode'");
				}

				$iLeft = 12;
				$iTop  = 56;

				if ($loopCount == 1 || $loopCount == 3)
					$iLeft = 108.5;

				if ($loopCount == 2 || $loopCount == 3)
					$iTop = 155;


				$sInfo  = "Type: {$sType}\n";
				$sInfo .= "Defect: {$sDefect}\n";
				$sInfo .= ("Area: {$sArea}\n");
				$sInfo .= ("Sample No: {$sSampleNo}\n");

				$objPdf->SetXY($iLeft, ($iTop + 78));
				$objPdf->setCellHeightRatio(1.2);
				$objPdf->MultiCell(90, 3.6, $sInfo, 0 , "L");
				$objPdf->setCellHeightRatio(1);
				$sPictureURL = "";

				if($sPicture != "" && file_exists($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sPicture))
					$sPictureURL = $sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sPicture;

				if($sPictureURL != "") {

          $MAX_WIDTH     = 90;
          $MAX_HEIGHT    = 70;

          @list($iWidth, $iHeight) = @getimagesize($sPictureURL);

          if ($iWidth > $iHeight)
          {
              $fRatio      = ($iWidth / $iHeight);
              $ImageWidth  = $MAX_WIDTH;
              $ImageHeight = @ceil($MAX_WIDTH / $fRatio);

          }
          else if ($iWidth < $iHeight)
          {
              $fRatio  = ($iHeight / $iWidth);
              $ImageHeight = $MAX_HEIGHT;
              $ImageWidth  = @ceil($MAX_HEIGHT / $fRatio);
          }
          else if($iWidth == $iHeight)
          {
              $ImageWidth  = 70;
              $ImageHeight = 70;
          }

          $objPdf->Image($sPictureURL, $iLeft, $iTop, $ImageWidth, $ImageHeight);

				} else {

					$objPdf->Text($iLeft, ($iTop + 35), "THE INSPECTOR CHOSE NOT TO ADD AN IMAGE AGAINST THIS DEFECT");
				}

				$loopCount++;
				$counter++;
			}
		}
	}

// /////////////////////////////////////////////////PAGE 11 - PACKAGING AND LABELING IMAGES///////////////////////// 

    if(count($PAndLDefects) > 0)
    {
			$iTemplateId = $objPdf->importPage(11, '/MediaBox');

			$iPages = @ceil(count($PAndLDefects) / 4);
			$iIndex = 0;
			$counter = 0;

			for ($i = 0; $i < $iPages; $i ++)
			{

				if($i == 0){
					$startValue = 0;
					
					if(count($PAndLDefects) > 3){
						$endValue = 4;
					} else {
						$endValue = count($PAndLDefects);
					}
				} else {
					$endValue = $endValue+4;
				}

				$iCurrentPage ++;
				$objPdf->addPage("P", "A4");
				$objPdf->useTemplate($iTemplateId, 0, 0);

				$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178.6, 9.6, 23);

				$objPdf->SetFont('Arial', '', 10);
				$objPdf->SetTextColor(255, 255, 255);

				$objPdf->Text(160, 19.5, "{$sAuditCode}");
				$objPdf->Text(66, 19.4, "{$sReportType}");

				$objPdf->SetFont('Arial', '', 9);
				$objPdf->SetTextColor(169, 169, 169);
				$objPdf->Text(162, 30.7, "{$iCurrentPage} of {$iTotalPages}");

				$objPdf->Image(($sBaseDir.BRANDS_IMG_DIR."{$sBrandImagePath}"), 8, 6.1, 29);

				$objPdf->SetTextColor(50, 50, 50);
				$objPdf->SetFont('Arial', '', 7);

				$loopCount = 0;

				for ($j = $startValue; $j < $endValue; $j ++)
				{

					$startValue++;

					$defectData = $PAndLDefects[$j];

					$iDefectCodeId = $defectData['code_id'];
					$sPicture = $defectData['picture'];
					$sType = $defectData['type'];

					if($userLanguage != 'en'){

						$defectField = "defect_".$userLanguage;
						$typeField = "type_".$userLanguage;

					} else {

						$defectField = "defect";
						$typeField = "type";
					}

						$sSQL = "SELECT {$defectField} AS defect,
										(SELECT {$typeField} FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
								 FROM tbl_defect_codes dc
								 WHERE id='$iDefectCodeId'";

						$objDb->query($sSQL);

						$sDefect = $objDb->getField(0, "defect");
						$sType   = $objDb->getField(0, "_Type");

						if($sDefect == "" || $sType == "") {

							$sSQL = "SELECT defect,
											(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
									 FROM tbl_defect_codes dc
									 WHERE code='$sDefectCode' AND report_id='$iReportId'";

							$objDb->query($sSQL);

							$sDefect = $objDb->getField(0, "defect");
							$sType   = $objDb->getField(0, "_Type");
						}

					$iLeft = 13;
					$iTop  = 57;

					if ($loopCount == 1 || $loopCount == 3)
						$iLeft = 108.5;

					if ($loopCount == 2 || $loopCount == 3)
						$iTop = 155;


					$sInfo  = "Type: {$sType}\n";
					$sInfo .= "Defect: {$sDefect}\n";

					$objPdf->SetXY($iLeft, ($iTop + 78));
					$objPdf->setCellHeightRatio(1.5);
					$objPdf->MultiCell(98, 3.6, $sInfo, 0 , "L");
					$objPdf->setCellHeightRatio(1);
					$sPictureURL = "";

					if($sPicture != "" && file_exists($sBaseDir.PACKAGING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sPicture))
						$sPictureURL = $sBaseDir.PACKAGING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sPicture;

					if($sPictureURL != "") {

	          $MAX_WIDTH     = 90;
	          $MAX_HEIGHT    = 70;

	          @list($iWidth, $iHeight) = @getimagesize($sPictureURL);

	          if ($iWidth > $iHeight)
	          {
	              $fRatio      = ($iWidth / $iHeight);
	              $ImageWidth  = $MAX_WIDTH;
	              $ImageHeight = @ceil($MAX_WIDTH / $fRatio);

	          }
	          else if ($iWidth < $iHeight)
	          {
	              $fRatio  = ($iHeight / $iWidth);
	              $ImageHeight = $MAX_HEIGHT;
	              $ImageWidth  = @ceil($MAX_HEIGHT / $fRatio);
	          }
	          else if($iWidth == $iHeight)
	          {
	              $ImageWidth  = 70;
	              $ImageHeight = 70;
	          }

	          $objPdf->Image($sPictureURL, $iLeft, $iTop, $ImageWidth, $ImageHeight);

					} else {

						$objPdf->Text($iLeft, ($iTop + 35), "THE INSPECTOR CHOSE NOT TO ADD AN IMAGE AGAINST THIS DEFECT");
					}

					$loopCount++;
					$counter++;
				}
			}

    }

// /////////////////////////////////////////////////PAGE 12 - MISC IMAGES///////////////////////// 

	if (count($sMiscPictures) > 0)
	{
		$iTemplateId = $objPdf->importPage(12, '/MediaBox');

		$iPages = @ceil(count($sMiscPictures) / 6);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++)
		{

			$iCurrentPage ++;

			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);

			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178.6, 9.6, 23);

			$objPdf->SetFont('Arial', '', 10);
			$objPdf->SetTextColor(255, 255, 255);

			$objPdf->Text(160, 19.5, "{$sAuditCode}");
			$objPdf->Text(66, 19.4, "{$sReportType}");

			$objPdf->SetFont('Arial', '', 9);
			$objPdf->SetTextColor(169, 169, 169);
			$objPdf->Text(162, 30.7, "{$iCurrentPage} of {$iTotalPages}");

			$objPdf->Image(($sBaseDir.BRANDS_IMG_DIR."{$sBrandImagePath}"), 8, 6.1, 29);

			for ($j = 0; $j < 6 && $iIndex < count($sMiscPictures); $j ++, $iIndex ++)
			{

				$sPictureURL = $sMiscPictures[$j];

				$iLeft = 18.5;
				$iTop  = 49.5;

				if ($j == 1 || $j == 3 || $j == 5)
					$iLeft = 108.5;

				if ($j == 2 || $j == 3)
					$iTop = 123;

				if ($j == 4 || $j == 5)
					$iTop = 197.3;

				if($sPictureURL != ""){

          $MAX_WIDTH     = 85;
          $MAX_HEIGHT    = 66;

          @list($iWidth, $iHeight) = @getimagesize($sPictureURL);

          if ($iWidth > $iHeight)
          {
              $fRatio      = ($iWidth / $iHeight);
              $ImageWidth  = $MAX_WIDTH;
              $ImageHeight = @ceil($MAX_WIDTH / $fRatio);

          }
          else if ($iWidth < $iHeight)
          {
              $fRatio  = ($iHeight / $iWidth);
              $ImageHeight = $MAX_HEIGHT;
              $ImageWidth  = @ceil($MAX_HEIGHT / $fRatio);
          }
          else if($iWidth == $iHeight)
          {
              $ImageWidth  = 65;
              $ImageHeight = 65;
          }

          $objPdf->Image($sPictureURL, $iLeft, $iTop, $ImageWidth, $ImageHeight);
				}
			}
		}
	}

// /////////////////////////////////////////////////PAGE 13 - PACKING IMAGES///////////////////////// 

	if (count($sPackPictures) > 0)
	{
		$iTemplateId = $objPdf->importPage(13, '/MediaBox');

		$iPages = @ceil(count($sPackPictures) / 6);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++)
		{
			$iCurrentPage ++;

			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);

			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178.6, 9.6, 23);

			$objPdf->SetFont('Arial', '', 10);
			$objPdf->SetTextColor(255, 255, 255);

			$objPdf->Text(160, 19.5, "{$sAuditCode}");
			$objPdf->Text(66, 19.4, "{$sReportType}");

			$objPdf->SetFont('Arial', '', 9);
			$objPdf->SetTextColor(169, 169, 169);
			$objPdf->Text(162, 30.7, "{$iCurrentPage} of {$iTotalPages}");

			$objPdf->Image(($sBaseDir.BRANDS_IMG_DIR."{$sBrandImagePath}"), 8, 6.1, 29);

			$objPdf->SetFont('Arial', '', 7);

			for ($j = 0; $j < 6 && $iIndex < count($sPackPictures); $j ++, $iIndex ++)
			{
			
				$sPictureURL = $sPackPictures[$j];

				$iLeft = 18.5;
				$iTop  = 49.5;

				if ($j == 1 || $j == 3 || $j == 5)
					$iLeft = 108.5;

				if ($j == 2 || $j == 3)
					$iTop = 123;

				if ($j == 4 || $j == 5)
					$iTop = 197.3;

          $MAX_WIDTH     = 85;
          $MAX_HEIGHT    = 66;

          @list($iWidth, $iHeight) = @getimagesize($sPictureURL);

          if ($iWidth > $iHeight)
          {
              $fRatio      = ($iWidth / $iHeight);
              $ImageWidth  = $MAX_WIDTH;
              $ImageHeight = @ceil($MAX_WIDTH / $fRatio);

          }
          else if ($iWidth < $iHeight)
          {
              $fRatio  = ($iHeight / $iWidth);
              $ImageHeight = $MAX_HEIGHT;
              $ImageWidth  = @ceil($MAX_HEIGHT / $fRatio);
          }
          else if($iWidth == $iHeight)
          {
              $ImageWidth  = 65;
              $ImageHeight = 65;
          }

          $objPdf->Image($sPictureURL, $iLeft, $iTop, $ImageWidth, $ImageHeight);
			}
		}
	}

// /////////////////////////////////////////////////PAGE 14 - SPECS SHEETS///////////////////////// 

	if (count($sSpecsSheets) > 0)
	{

		$iTemplateId = $objPdf->importPage(14, '/MediaBox');

		for ($i = 0; $i < count($sSpecsSheets); $i ++)
		{
			$iCurrentPage ++;

			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);

			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178.6, 9.6, 23);

			$objPdf->SetFont('Arial', '', 10);
			$objPdf->SetTextColor(255, 255, 255);

			$objPdf->Text(160, 19.5, "{$sAuditCode}");
			$objPdf->Text(66, 19.4, "{$sReportType}");

			$objPdf->SetFont('Arial', '', 9);
			$objPdf->SetTextColor(169, 169, 169);
			$objPdf->Text(162, 30.7, "{$iCurrentPage} of {$iTotalPages}");

			$objPdf->Image(($sBaseDir.BRANDS_IMG_DIR."{$sBrandImagePath}"), 8, 6.1, 29);

			$sPictureURL = $sSpecsSheets[$i];

      $MAX_WIDTH     = 190;
      $MAX_HEIGHT    = 210;

      @list($iWidth, $iHeight) = @getimagesize($sPictureURL);

      if ($iWidth > $iHeight)
      {
          $fRatio      = ($iWidth / $iHeight);
          $ImageWidth  = $MAX_WIDTH;
          $ImageHeight = @ceil($MAX_WIDTH / $fRatio);

      }
      else if ($iWidth < $iHeight)
      {
          $fRatio  = ($iHeight / $iWidth);
          $ImageHeight = $MAX_HEIGHT;
          $ImageWidth  = @ceil($MAX_HEIGHT / $fRatio);
      }
      else if($iWidth == $iHeight)
      {
          $ImageWidth  = $MAX_WIDTH;
          $ImageHeight = $MAX_WIDTH;
      }

      $objPdf->Image($sPictureURL, 10, 50, $ImageWidth, $ImageHeight);

		}
	}

	// /////////////////////////////////////////////////PAGE 15 - ATTACHMENTS///////////////////////// 
	
	if(count($sAttachments) > 0){

			$iCurrentPage ++;

			$iTemplateId = $objPdf->importPage(15, '/MediaBox');

			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);

			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178.6, 9.6, 23);

			$objPdf->SetFont('Arial', '', 10);
			$objPdf->SetTextColor(255, 255, 255);

			$objPdf->Text(160, 19.5, "{$sAuditCode}");
			$objPdf->Text(66, 19.4, "{$sReportType}");

			$objPdf->SetFont('Arial', '', 9);
			$objPdf->SetTextColor(169, 169, 169);
			$objPdf->Text(162, 30.7, "{$iCurrentPage} of {$iTotalPages}");

			$objPdf->Image(($sBaseDir.BRANDS_IMG_DIR."{$sBrandImagePath}"), 8, 6.1, 29);

      $objPdf->SetFont('Arial', '', 7);
      $objPdf->SetTextColor(6, 82, 195);

      $iTop = 10;
      $Count = 1; 

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

	// @unlink($sBaseDir.TEMP_DIR."{$sAuditCode}.png");


	$sPdfFile = ($sBaseDir.TEMP_DIR."S{$Id}-QA-Report.pdf");

	if (count($Recipients) > 0)
		$objPdf->Output($sPdfFile, 'F');

	else
		$objPdf->Output(@basename($sPdfFile), 'D');


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

  function getSampleCodePosition($sLotSize, $Level){

  	$x = 0;
  	$y = 0;
  	$letter = 0;

		if($Level == 1){
			$x = 75.8;
		} else if($Level == 2){
			$x = 92.6;
		} else if($Level == 3){
			$x = 109;
		}

		if($Level == 1){
			$letter = 'A';
		} else if($Level == 2){
			$letter = 'A';
		} else if($Level == 3){
			$letter = 'B';
		}

	  if($sLotSize <= 8){
	    $y = 90.8;
			if($Level == 1){
				$letter = 'A';
			} else if($Level == 2){
				$letter = 'A';
			} else if($Level == 3){
				$letter = 'B';
			}		    
	  } else if($sLotSize <= 15){
	    $y = 94.2;
			if($Level == 1){
				$letter = 'A';
			} else if($Level == 2){
				$letter = 'B';
			} else if($Level == 3){
				$letter = 'C';
			}			    
	  } else if($sLotSize <= 25){
	    $y = 97.6;
			if($Level == 1){
				$letter = 'B';
			} else if($Level == 2){
				$letter = 'C';
			} else if($Level == 3){
				$letter = 'D';
			}			    
	  } else if($sLotSize <= 50){
	    $y = 103.8;
			if($Level == 1){
				$letter = 'C';
			} else if($Level == 2){
				$letter = 'D';
			} else if($Level == 3){
				$letter = 'E';
			}			    
	  } else if($sLotSize <= 90){
	    $y = 107.4;
			if($Level == 1){
				$letter = 'C';
			} else if($Level == 2){
				$letter = 'E';
			} else if($Level == 3){
				$letter = 'F';
			}			    
	  } else if($sLotSize <= 150){
	    $y = 111.2;
			if($Level == 1){
				$letter = 'D';
			} else if($Level == 2){
				$letter = 'F';
			} else if($Level == 3){
				$letter = 'G';
			}			    
	  } else if($sLotSize <= 280){
	    $y = 117.2;
			if($Level == 1){
				$letter = 'E';
			} else if($Level == 2){
				$letter = 'G';
			} else if($Level == 3){
				$letter = 'H';
			}			    
	  } else if($sLotSize <= 500){
	    $y = 120.8;
			if($Level == 1){
				$letter = 'F';
			} else if($Level == 2){
				$letter = 'H';
			} else if($Level == 3){
				$letter = 'J';
			}			    
	  } else if($sLotSize <= 1200){
	    $y = 124.4;
			if($Level == 1){
				$letter = 'G';
			} else if($Level == 2){
				$letter = 'J';
			} else if($Level == 3){
				$letter = 'K';
			}			    
	  } else if($sLotSize <= 3200){
	    $y = 130.6;
			if($Level == 1){
				$letter = 'H';
			} else if($Level == 2){
				$letter = 'K';
			} else if($Level == 3){
				$letter = 'L';
			}			    
	  } else if($sLotSize <= 10000){
	    $y = 134.2;
			if($Level == 1){
				$letter = 'J';
			} else if($Level == 2){
				$letter = 'L';
			} else if($Level == 3){
				$letter = 'M';
			}			    
	  } else if($sLotSize <= 35000){
	    $y = 137.8;
			if($Level == 1){
				$letter = 'K';
			} else if($Level == 2){
				$letter = 'M';
			} else if($Level == 3){
				$letter = 'N';
			}			    
	  } else if($sLotSize <= 150000){
	    $y = 144;
			if($Level == 1){
				$letter = 'L';
			} else if($Level == 2){
				$letter = 'N';
			} else if($Level == 3){
				$letter = 'P';
			}			    
	  } else if($sLotSize <= 500000){
	    $y = 147.4;
			if($Level == 1){
				$letter = 'M';
			} else if($Level == 2){
				$letter = 'P';
			} else if($Level == 3){
				$letter = 'Q';
			}			    
	  } else if($sLotSize >= 500001){
	    $y = 151.2;
			if($Level == 1){
				$letter = 'N';
			} else if($Level == 2){
				$letter = 'Q';
			} else if($Level == 3){
				$letter = 'R';
			}			    
	  }

	  return array($x,$y,$letter);
  }

  function getSampleSizeByCode($code){

  	if($code == 'A'){
  		return '187.5';
  	} else if($code == 'B'){
  		return '191.3';
  	} else if($code == 'C'){
  		return '195.1';
  	} else if($code == 'D'){
  		return '198.9';
  	} else if($code == 'E'){
  		return '202.7';
  	} else if($code == 'F'){
  		return '206.5';
  	} else if($code == 'G'){
  		return '210.3';
  	} else if($code == 'H'){
  		return '214.1';
  	} else if($code == 'J'){
  		return '217.9';
  	} else if($code == 'K'){
  		return '221.5';
  	} else if($code == 'L'){
  		return '225.3';
  	} else if($code == 'M'){
  		return '229.3';
  	} else if($code == 'N'){
  		return '232.8';
  	} else if($code == 'P'){
  		return '236.2';
  	} else if($code == 'Q'){
  		return '240.1';
  	} else if($code == 'R'){
  		return '243.8';
  	}
  }

  function getSampleSizeBySampleSize($sampleSize){

  	if($sampleSize == '2'){
  		return '187.5';
  	} else if($sampleSize == '3'){
  		return '191.3';
  	} else if($sampleSize == '5'){
  		return '195.1';
  	} else if($sampleSize == '8'){
  		return '198.9';
  	} else if($sampleSize == '13'){
  		return '202.7';
  	} else if($sampleSize == '20'){
  		return '206.5';
  	} else if($sampleSize == '32'){
  		return '210.3';
  	} else if($sampleSize == '50'){
  		return '214.1';
  	} else if($sampleSize == '80'){
  		return '217.9';
  	} else if($sampleSize == '125'){
  		return '221.5';
  	} else if($sampleSize == '200'){
  		return '225.3';
  	} else if($sampleSize == '315'){
  		return '229.3';
  	} else if($sampleSize == '500'){
  		return '232.8';
  	} else if($sampleSize == '800'){
  		return '236.2';
  	} else if($sampleSize == '1250'){
  		return '240.1';
  	} else if($sampleSize == '2000'){
  		return '243.8';
  	}
  }

  function getAQLPosition($aql) {

  	if($aql == '0.065') {
  		return '54.6';
  	} else if($aql == '0.10') {
  		return '66.3';
  	} else if($aql == '0.15') {
  		return '78';
  	} else if($aql == '0.25') {
  		return '89.74';
  	} else if($aql == '0.40') {
  		return '101.44';
  	} else if($aql == '0.65') {
  		return '113.2';
  	} else if($aql == '1.0') {
  		return '124.9';
  	} else if($aql == '1.5') {
  		return '136.6';
  	} else if($aql == '2.5') {
  		return '148.24';
  	} else if($aql == '4.0') {
  		return '159.94';
  	} else if($aql == '6.5') {
  		return '171.6';
  	}
  }

  function getSampleXPosition($sample){

  	if($sample == '1'){
  		return array('126','138');
  	} else if($sample == '2') {
  		return array('150','162');
  	} else if($sample == '3') {
  		return array('174','186');
  	} else if($sample == '4') {
  		return array('198','210');
  	} else if($sample == '5') {
  		return array('222','234');
  	} else if($sample == '6') {
  		return array('246','258');
  	} else {
  		return array(0,0);
  	}

  }
function getTolerance($finding,$specs,$tolerance){

	if($finding == 'ok' || $finding == '0'){

		return 'pass';
	}

  $fSpecs           = ConvertToFloatValue($specs);
  $fTolerance       = parseTolerance($tolerance);

  $fNTolerance       = $fTolerance[0];
  $fPTolerance       = $fTolerance[1];

  $fPositiveTolerance = ($fSpecs + $fPTolerance);
  $fNegativeTolerance = ($fSpecs - $fNTolerance);

  if($finding > $fPositiveTolerance ||  $finding < $fNegativeTolerance){

      return 'fail';

  } else {

      return 'pass';
  }      

}

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

function hexToRGB($hex) {

	$hex = str_replace("#", "", $hex);

	list($r, $g, $b) = array_map('hexdec', str_split($hex, 2));

  return array($r,$g,$b);
}

?>