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

	$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_001_*.*");
	$sPictures = @array_merge($sPictures, @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*"));
	$sPictures = @array_map("strtoupper", $sPictures);
	$sPictures = @array_unique($sPictures);

	$sDefects = array( );
	$sPacking = array( );
	$sMisc    = array( );

	$sDefectImages  = getList("tbl_qa_report_defects", "id", "UPPER(picture)", "audit_id='$Id'");
	$sPackingImages = getList("tbl_qa_report_images", "id", "UPPER(image)", "audit_id='$Id' AND `type`='P'");
	$sMiscImages    = getList("tbl_qa_report_images", "id", "UPPER(image)", "audit_id='$Id' AND `type`='M'");                   
	$sLabImages    = getList("tbl_qa_report_images", "id", "UPPER(image)", "audit_id='$Id' AND `type`='L'");
	
	foreach ($sDefectImages as $sImage)
	{
		if (@file_exists($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
			$sDefects[] = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);
	}
	
	foreach ($sPackingImages as $sImage)
	{
		if (@file_exists($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
			$sPacking[] = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);
	}

	foreach ($sMiscImages as $sImage)
	{
		if (@file_exists($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
			$sMisc[] = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);
	}	
	
	foreach ($sLabImages as $sImage)
	{
		if (@file_exists($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
			$sSpecsSheets[] = ($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);
	}	

/*	
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
*/

	$iTotalPages  = 4;
	$iTotalPages += getDbValue("COUNT(DISTINCT(CONCAT(size_id, '-', color)))", "tbl_qa_report_samples", "audit_id='$Id'");
	$iTotalPages += count($sSpecsSheets);
	$iTotalPages += @ceil(count($sPacking) / 4);
	$iTotalPages += @ceil(count($sDefects) / 4);
	$iTotalPages += @ceil(count($sMisc) / 4);


	/////////////////////////////////////////////////////page1///////////////////////////////////////////////////////////////


	$objPdf = new AlphaPDF( );

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 181, 10, 21.5);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(177, 33, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 10, "Page 1 of {$iTotalPages}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(125, 23, $sBrand);
	$objPdf->Text(125, 28, $sAuditStage);
	$objPdf->Text(125, 33, formatDate($sAuditDate));



	$sSQL = "SELECT * FROM tbl_arcadia_inspection_summary WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$iQtyOfLots                   = $objDb->getField(0, "qty_of_lots");
	$iQtyPerLot                   = $objDb->getField(0, "qty_per_lot");
	$sInspectionStatus            = $objDb->getField(0, "inspection_status");
	$sShippingMarks               = $objDb->getField(0, "shipping_marks");
	$sShippingMarksRemarks        = $objDb->getField(0, "shipping_marks_remarks");
	$sMaterialConformity          = $objDb->getField(0, "material_conformity");
	$sMaterialConformityRemarks   = $objDb->getField(0, "material_conformity_remarks");
	$sProductStyle                = $objDb->getField(0, "style");
	$sProductStyleRemarks         = $objDb->getField(0, "style_remarks");
	$sProductColour               = $objDb->getField(0, "colour");
	$sProductColourRemarks        = $objDb->getField(0, "colour_remarks");
	$sExportCartonPacking         = $objDb->getField(0, "export_carton_packing");
	$sExportCartonPackingRemarks  = $objDb->getField(0, "export_carton_packing_remarks");
	$sInnerCartonPacking          = $objDb->getField(0, "inner_carton_packing");
	$sInnerCartonPackingRemarks   = $objDb->getField(0, "inner_carton_packing_remarks");
	$sProductPackaging            = $objDb->getField(0, "product_packaging");
	$sProductPackagingRemarks     = $objDb->getField(0, "product_packaging_remarks");
	$sAssortment                  = $objDb->getField(0, "assortment");
	$sAssortmentRemarks           = $objDb->getField(0, "assortment_remarks");
	$sLabeling                    = $objDb->getField(0, "labeling");
	$sLabelingRemarks             = $objDb->getField(0, "labeling_remarks");
	$sMarkings                    = $objDb->getField(0, "markings");
	$sMarkingsRemarks             = $objDb->getField(0, "markings_remarks");
	$sWorkmanship                 = $objDb->getField(0, "workmanship");
	$sWorkmanshipRemarks          = $objDb->getField(0, "workmanship_remarks");
	$sAppearance                  = $objDb->getField(0, "appearance");
	$sAppearanceRemarks           = $objDb->getField(0, "appearance_remarks");
	$sFunction                    = $objDb->getField(0, "function");
	$sFunctionRemarks             = $objDb->getField(0, "function_remarks");
	$sPrintedMaterials            = $objDb->getField(0, "printed_materials");
	$sPrintedMaterialsRemarks     = $objDb->getField(0, "printed_materials_remarks");
	$sWorkmanshipFinishing        = $objDb->getField(0, "finishing");
	$sWorkmanshipFinishingRemarks = $objDb->getField(0, "finishing_remarks");
	$sMeasurement                 = $objDb->getField(0, "measurement");
	$sMeasurementRemarks          = $objDb->getField(0, "measurement_remarks");
	$sFabricWeight                = $objDb->getField(0, "fabric_weight");
	$sFabricWeightRemarks         = $objDb->getField(0, "fabric_weight_remarks");
	$sCalibratedScales            = $objDb->getField(0, "calibrated_scales");
	$sCalibratedScalesRemarks     = $objDb->getField(0, "calibrated_scales_remarks");
	$sCordNorm                    = $objDb->getField(0, "cords_norm");
	$sCordNormRemarks             = $objDb->getField(0, "cords_norm_remarks");
	$sInspectionConditions        = $objDb->getField(0, "inspection_conditions");
	$sInspectionConditionsRemarks = $objDb->getField(0, "inspection_conditions_remarks");
	$sRemarks1                    = $objDb->getField(0, "remarks_1");
	$sRemarks2                    = $objDb->getField(0, "remarks_2");
	$sRemarks3                    = $objDb->getField(0, "remarks_3");
	$sRemarks4                    = $objDb->getField(0, "remarks_4");
	$sCartonNos                   = $objDb->getField(0, "carton_nos");
	$iShipmentQtyUnits            = $objDb->getField(0, "shipment_units");
	$iShipmentQtyCtns             = $objDb->getField(0, "shipment_ctns");
	$iPresentedQty                = $objDb->getField(0, "presented_qty");
	$iUnitsPackedQty              = $objDb->getField(0, "packed_qty");
	$fUnitsPackedPercent          = $objDb->getField(0, "packed_percent");
	$iUnitsFinishedQty            = $objDb->getField(0, "finished_qty");
	$fUnitsFinishedPercent        = $objDb->getField(0, "finished_percent");
	$iUnitsNotFinishedQty         = $objDb->getField(0, "not_finished_qty");
	$fUnitsNotFinishedPercent     = $objDb->getField(0, "not_finished_percent");
	$sMeasurementResult           = $objDb->getField(0, "measurement_result");
	$sMeasurementComments         = $objDb->getField(0, "measurement_overall_remarks");


	// Report Details
	$objPdf->SetFont('Arial', '', 8);

	$objPdf->Text(64, 47, ($sPo.$sAdditionalPos));
	$objPdf->Text(160, 47, $sDescription);

	$objPdf->Text(64, 52, $sVendor);
	$objPdf->Text(160, 52, "Triple Tree Solutions");

	$objPdf->Text(160, 57, $sAuditor);


		
	if ($sLatitude != "" && $sLongitude != "")
	{	
		$sLocation = trim(trim(str_replace("\n", ", ", $sLocation)), ",");

		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(6, 82, 195);
		
		$objPdf->SetXY(42, 58.9);
		$objPdf->Write(5, "{$sLocation} ({$sLatitude},{$sLongitude})", "http://maps.google.com/maps?q={$sLatitude},{$sLongitude}&z=12");
	}
	
	else
		$objPdf->Text(64, 62, getDbValue("city", "tbl_vendors", "id='$iVendor'"));


	$objPdf->SetFont('Arial', '', 8);
	$objPdf->SetTextColor(50, 50, 50);
	
	$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sApprovedSample == "Yes" || $sApprovedSample == "Y") ? 172 : 193), 59, 4);

	$objPdf->Text(64, 67, formatDate($sAuditDate));
	$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sApprovedTrims == "Y") ? 172 : 193), 64, 4);

	$objPdf->Text(64, 72, formatNumber($iQuantity, false));
	$objPdf->Text(160, 72, $iQtyOfLots);

	$objPdf->Text(64, 77, formatNumber($iPresentedQty, false));
	$objPdf->Text(160, 77, $iQtyPerLot);

	$objPdf->Text(70, 97, (($sCustomSample == "Y") ? "CUSTOM ({$iTotalGmts})" : $iTotalGmts).(($sInspectionStatus != "") ? " ({$sInspectionStatus})" : ""));
	$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sAuditResult == "P") ? 18 : 50), 115.5, 5);


        $objPdf->SetFont('Arial', '', 6);
        
	if ($sShippingMarks != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sShippingMarks == "P") ? 109 : 122), 132.5, 4);

        $objPdf->SetXY(130, 132.2);
	$objPdf->MultiCell(70, 2.6, $sShippingMarksRemarks, 0);
	
	if ($sMaterialConformity != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sMaterialConformity == "P") ? 109 : 122), 137.5, 4);

        $objPdf->SetXY(130, 137.2);
	$objPdf->MultiCell(70, 2.6, $sMaterialConformityRemarks, 0);
	

	if ($sProductStyle != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sProductStyle == "P") ? 109 : 122), 142.5, 4);

        $objPdf->SetXY(130, 142.2);
	$objPdf->MultiCell(70, 2.6, $sProductStyleRemarks, 0);

	
	if ($sProductColour != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sProductColour == "P") ? 109 : 122), 147.5, 4);

        $objPdf->SetXY(130, 147.2);
	$objPdf->MultiCell(70, 2.6, $sProductColourRemarks, 0);


	if ($sExportCartonPacking != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sExportCartonPacking == "P") ? 109 : 122), 152.5, 4);

        $objPdf->SetXY(130, 152.2);
	$objPdf->MultiCell(70, 2.6, $sExportCartonPackingRemarks, 0);


	if ($sInnerCartonPacking != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sInnerCartonPacking == "P") ? 109 : 122), 157.5, 4);

        $objPdf->SetXY(130, 157.2);
	$objPdf->MultiCell(70, 2.6, $sInnerCartonPackingRemarks, 0);

	if ($sProductPackaging != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sProductPackaging == "P") ? 109 : 122), 162.5, 4);

        $objPdf->SetXY(130, 162.2);
	$objPdf->MultiCell(70, 2.6, $sProductPackagingRemarks, 0);

	if ($sAssortment != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sAssortment == "P") ? 109 : 122), 167.5, 4);

        $objPdf->SetXY(130, 167.2);
	$objPdf->MultiCell(70, 2.6, $sAssortmentRemarks, 0);

	if ($sLabeling != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sLabeling == "P") ? 109 : 122), 172.9, 4);

        $objPdf->SetXY(130, 172.2);
	$objPdf->MultiCell(70, 2.6, $sLabelingRemarks, 0);
	
	if ($sMarkings != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sMarkings == "P") ? 109 : 122), 178, 4);

        $objPdf->SetXY(130, 177.4);
	$objPdf->MultiCell(70, 2.6, $sMarkingsRemarks, 0);

	if ($sWorkmanship != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sWorkmanship == "P") ? 109 : 122), 183.1, 4);

        $objPdf->SetXY(130, 182.4);
	$objPdf->MultiCell(70, 2.6, $sWorkmanshipRemarks, 0);

	if ($sAppearance != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sAppearance == "P") ? 109 : 122), 188.2, 4);

        $objPdf->SetXY(130, 187.8);
	$objPdf->MultiCell(70, 2.6, $sAppearanceRemarks, 0);


	if ($sFunction != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sFunction == "P") ? 109 : 122), 193.3, 4);

        $objPdf->SetXY(130, 192.8);
	$objPdf->MultiCell(70, 2.6, $sFunctionRemarks, 0);
	
	if ($sPrintedMaterials != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sPrintedMaterials == "P") ? 109 : 122), 198.4, 4);

        $objPdf->SetXY(130, 197.9);
	$objPdf->MultiCell(70, 2.6, $sPrintedMaterialsRemarks, 0);

	if ($sWorkmanshipFinishing != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sWorkmanshipFinishing == "P") ? 109 : 122), 203.5, 4);

        $objPdf->SetXY(130, 202.9);
	$objPdf->MultiCell(70, 2.6, $sWorkmanshipFinishingRemarks, 0);


	if ($sMeasurement != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sMeasurement == "P") ? 109 : 122), 208, 4);

        $objPdf->SetXY(130, 207.9);
	$objPdf->MultiCell(70, 2.6, $sMeasurementRemarks, 0);


	if ($sFabricWeight != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sFabricWeight == "P") ? 109 : 122), 213, 4);

	if ($sCalibratedScales != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sCalibratedScales == "Y") ? 80 : 97), 213.3, 4);

        $objPdf->SetXY(130, 213.6);
	$objPdf->MultiCell(70, 2.6, $sFabricWeightRemarks, 0);


	if ($sCordNorm != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sCordNorm == "P") ? 109 : 122), 218, 4);

        $objPdf->SetXY(130, 217.7);
	$objPdf->MultiCell(70, 2.6, $sCordNormRemarks, 0);

	if ($sInspectionConditions != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sInspectionConditions == "P") ? 109 : 122), 223, 4);

        $objPdf->SetXY(130, 222.8);
	$objPdf->MultiCell(70, 2.6, $sInspectionConditionsRemarks, 0);

        $objPdf->SetFont('Arial', '', 8);
        
	$objPdf->Text(22, 246.5, $sRemarks1);
	$objPdf->Text(22, 251.5, $sRemarks2);
	$objPdf->Text(22, 256.5, $sRemarks3);
	$objPdf->Text(22, 261.5, $sRemarks4);


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 2


	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(2, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 181, 10, 21.5);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(177, 33, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 10, "Page 2 of {$iTotalPages}");


	$objPdf->Text(125, 23, $sBrand);
	$objPdf->Text(125, 28, $sAuditStage);
	$objPdf->Text(125, 33, formatDate($sAuditDate));


	// Quantity Details
	$objPdf->SetFont('Arial', '', 8);

	$objPdf->Text(13, 62, ($sPo.$sAdditionalPos));
	$objPdf->Text(35, 62, formatNumber($iQuantity, false));
	$objPdf->Text(55, 62, formatNumber($iShipmentQtyUnits, false));
	$objPdf->Text(70, 62, formatNumber($iShipmentQtyCtns, false));
	$objPdf->Text(90, 62, formatNumber($iPresentedQty, false));
	$objPdf->Text(115, 62, formatNumber($iUnitsPackedQty, false));
	$objPdf->Text(129, 62, formatNumber($fUnitsPackedPercent));
	$objPdf->Text(143, 62, formatNumber($iUnitsFinishedQty, false));
	$objPdf->Text(158, 62, formatNumber($fUnitsFinishedPercent));
	$objPdf->Text(173, 62, formatNumber($iUnitsNotFinishedQty, false));
	$objPdf->Text(188, 62, formatNumber($fUnitsNotFinishedPercent));

	$objPdf->Text(35, 66.7, formatNumber($iQuantity, false));
	$objPdf->Text(55, 66.7, formatNumber($iShipmentQtyUnits, false));
	$objPdf->Text(70, 66.7, formatNumber($iShipmentQtyCtns, false));
	$objPdf->Text(90, 66.7, formatNumber($iPresentedQty, false));
	$objPdf->Text(115, 66.7, formatNumber($iUnitsPackedQty, false));
	$objPdf->Text(129, 66.7, formatNumber($fUnitsPackedPercent));
	$objPdf->Text(143, 66.7, formatNumber($iUnitsFinishedQty, false));
	$objPdf->Text(158, 66.7, formatNumber($fUnitsFinishedPercent));
	$objPdf->Text(173, 66.7, formatNumber($iUnitsNotFinishedQty, false));
	$objPdf->Text(188, 66.7, formatNumber($fUnitsNotFinishedPercent));


	if ($sCartonNos != "")
	{
		$sCartonNos = @explode(",", $sCartonNos);

		for ($i = 1, $iIndex = 0; $i <= 7 && $iIndex < count($sCartonNos); $i ++)
		{
			for ($j = 1; $j <= 13 && $iIndex < count($sCartonNos); $j ++, $iIndex ++)
				$objPdf->Text((($j * 14.6) - 3), (71.5 + ($i * 5)), $sCartonNos[$iIndex]);
		}

		$objPdf->Text(83, 111.3, count($sCartonNos));
	}


	$iTotalSizeQty   = 0;
	$iTotalSampleQty = 0;

	$sSQL = "SELECT size_color, size_qty, sample_qty FROM tbl_arcadia_samples_per_size WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sSizeColor = $objDb->getField($i, "size_color");
		$iSizeQty   = $objDb->getField($i, "size_qty");
		$iSampleQty = $objDb->getField($i, "sample_qty");

		$objPdf->Text((34.8 + ($i * 14.6)), 121, $sSizeColor);
		$objPdf->Text((34.8 + ($i * 14.6)), 126, $iSizeQty);
		$objPdf->Text((34.8 + ($i * 14.6)), 131, $iSampleQty);

		$iTotalSizeQty   += $iSizeQty;
		$iTotalSampleQty += $iSampleQty;
	}

	$objPdf->Text(181.4, 126, $iTotalSizeQty);
	$objPdf->Text(181.4, 131, $iTotalSampleQty);

        $objPdf->SetFont('Arial', '', 6);
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
	$fTop   = 145.8;
        
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
        
            if($i <= 17){
                
            	$sSQL2 = ("SELECT defect, (SELECT type_code from tbl_defect_types where tbl_defect_codes.type_id=tbl_defect_types.id) as _Code FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
		$objDb2->query($sSQL2);

                $sDefect     = $objDb2->getField(0, 0);		
		$sDefectTypeCode = $objDb2->getField(0, 1);
            
                $objPdf->SetXY(11, ($fTop - 2.2));
		$objPdf->MultiCell(140, 2.2, ($sDefectTypeCode!=""?$sDefectTypeCode.'- ':'').$sDefect, 0);
                
                $objPdf->Text(146, $fTop, $iCritical);
		$objPdf->Text(167, $fTop, $iMajor);
                $objPdf->Text(188, $fTop, $iMinor);
                
            }
        }

        $objPdf->Text(146, 235, $TotalCritical);
	$objPdf->Text(167, 235, $TotalMajor);
        $objPdf->Text(188, 235, $TotalMinor);
        
        $objPdf->Text(146, 240, "0");
	$objPdf->Text(167, 240, $iAqlChart[$iTotalGmts]["2.5"]);
	$objPdf->Text(188, 240, $iAqlChart[$iTotalGmts]["4"]);

      	$objPdf->SetFont('Arial', 'B', 9);
        if($sAuditResult == "P")
            $objPdf->SetTextColor(0, 100, 0);
        else
            $objPdf->SetTextColor(100, 0, 0);
	$objPdf->Text(146, 245, (($sAuditResult == "P") ? "PASS" : "FAIL"));
        

        $objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);
        $objPdf->SetXY(12, 251.6);
	$objPdf->MultiCell(188, 4.7, $sComments, 0);


	 /////////////////////////////////////////////////// specs summary page /////////////////////////
	 
	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(5, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 181, 10, 21.5);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(177, 33, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 10, "Page 3 of {$iTotalPages}");


	$objPdf->Text(125, 23, $sBrand);
	$objPdf->Text(125, 28, $sAuditStage);
	$objPdf->Text(125, 33, formatDate($sAuditDate));

	
	
	$objPdf->SetFont('Arial', '', 7);

	$sSizes  = getDbValue("GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',')", "tbl_qa_report_samples", "audit_id='$Id'");
	$iSizes  = @explode(",", $sSizes);

	$objPdf->Text(45, 60, getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id'"));
	$objPdf->Text(45, 65, getDbValue("GROUP_CONCAT(size SEPARATOR ',')", "tbl_sampling_sizes", "id IN ($sSizes)"));

	$objPdf->SetFont('Arial', '', 6);
	
	
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
		$iSamplesChecked = (int)getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize'");
		$iSampleSize     = ($iSamplesCount * $iSamplesChecked);
		
		$sSizeFindings["{$i}-{$iSampleNo}-{$iPoint}"] = array('finiding' => $sFindings, 'sample_size' => $iSampleSize, 'size_id' => $iSize);
		$sSizeFindings2["{$iSize}-{$iSampleNo}-{$iPoint}"] = $sFindings;
		
		$iPoints[] = $iPoint;
	}
	
	
	
	$sPoints        = @explode(",", $iPoints);
	$sSpecsList     = getList("tbl_style_specs", "point_id", "specs", " style_id='$iStyle' AND version='0' AND specs!='' AND specs!='0'", "point_id");
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
		
		$sSpecs        = @$sSpecsList[$iPoint];
		$sPoint        = @$sPointList[$iPoint];
		$sTolerance    = @$sToleranceList[$iPoint];

		if (trim($sFinding) == "" && strtolower($sFinding) == "ok" && $sFinding == "0")
			$sFinding = 0;

		
		$fMeaseuredValue = ConvertToFloatValue($sFinding);
		$fSpecValue      = ConvertToFloatValue($sSpecs);
		$fTolerance      = ConvertToFloatValue($sTolerance);

		$DifferenceValue   = ($fSpecValue + $fMeaseuredValue); //$fSpecValue - $fMeaseuredValue
		$PositiveTolerance = ($fSpecValue + $fTolerance);
		$NegativeTolerance = ($fSpecValue - $fTolerance);
		
		
		if ($iPoint != $iLastPoint)
		{
			$TotalPercent  = 0;
			$MajorDefects  = 0;
			$MinorDefects  = 0;
			$iTotalSum     = 0;
		}
		
            
		if ($DifferenceValue >= $NegativeTolerance && $DifferenceValue <= $PositiveTolerance)
		{
			//continue;
		}

		else
		{
			$fPercent = (abs($fMeaseuredValue)/$fSpecValue)*100;
		  
			if ($fPercent > 10)
				$MajorDefects++;
			
			else if($fPercent > 0 && $fPercent < 10)
				$MinorDefects++;
			
			$TotalPercent = (($MajorDefects+$MinorDefects)/$iSampleSize)*100;
		}
		
		$sSpecArray[$iPoint] = array('point'=> $iPoint, 'major'=>$MajorDefects, 'minor'=>$MinorDefects, 'percent' => $TotalPercent, 'sample_size' => $iSampleSize);
		$iLastPoint = $iPoint;
	}
	
	
       
        $sort = array();
		
        foreach($sSpecArray as $k=>$v)
		{
            $sort['major'][$k] = $v['major'];
            $sort['minor'][$k] = $v['minor'];
        }
		
        array_multisort($sort['major'], SORT_DESC, $sort['minor'], SORT_DESC,$sSpecArray);
        
        $iTop               = 187;
        $iTotalMajor        = 0;
        $iTotalMinor        = 0;
        $iTotalPercent      = 0;
        $limit              = 0;
        $iTotalSampleSizes  = 0;
        
		
        foreach($sSpecArray as $iKey => $sDefectsArr){
            
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
        
		
        $objPdf->Text(162, 264, formatNumber($iTotalMinor, false));
        $objPdf->Text(177, 264, formatNumber($iTotalMajor, false));
        
        //sizez box
        $objPdf->SetFont('Arial', 'B', 7);
        $objPdf->SetTextColor(50, 50, 50);
        $sQtyPerSize            = "";
        $iSizeTop               = 79;
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
                
                
                for($i=0; $i < $iCount2; $i++){
                    
                    for ($j = 1; $j <= $iSamplesChecked; $j ++, $k ++)
                    {
                        $iPoint     = $objDb->getField($i, 'point_id');
                        $sSpecs     = $objDb->getField($i, 'specs');
                        $sTolerance = $objDb->getField($i, '_Tolerance');
                        $sFinding  = $sSizeFindings2["{$iSize}-{$j}-{$iPoint}"];
                        
                        if ($sFinding != "" && strtolower($sFinding) != "ok" && strtolower($sFinding) != "0")
                        {
                         
                            $fMeaseuredValue  = (ConvertToFloatValue($sFinding));
                            $fSpecValue       = (ConvertToFloatValue($sSpecs));
                            $DifferenceValue  = $fMeaseuredValue + $fSpecValue;
                            $fTolerance       = abs((ConvertToFloatValue($sTolerance))/2);

                            $PositiveTolerance = $fSpecValue + $fTolerance;
                            $NegativeTolerance = $fSpecValue - $fTolerance;

                            if($fMeaseuredValue >= $NegativeTolerance && $fMeaseuredValue <= $PositiveTolerance)
                            {
                                //continue;
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
		
		
        $objPdf->Text(105, 60, $iTotalEvaluatedPoints);
        $objPdf->Text(162, 60, $iTotalDefectivePoints);
        $iGenealPercent = ($iTotalDefectivePoints/$iTotalEvaluatedPoints)*100;
        $objPdf->Text(189, 264, number_format(($iGenealPercent),2).'%');
        $objPdf->SetFont('Arial', 'B', 7);

        if($iGenealPercent > 20)
		{

           $objPdf->SetTextColor(255, 0, 0);
           $objPdf->Text(190, 60, 'Fail');

        }
		
		else
		{

           $objPdf->SetTextColor(0, 100, 0);
           $objPdf->Text(190, 60, 'Pass');
        }
		
		
    /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 4


	$iCurrentPage = 4;
	$iPageCount   = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId  = $objPdf->importPage(4, '/MediaBox');

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

			//$sQtyPerSize .= ("{$sSize} / {$sColor} (".getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize' AND color LIKE '$sColor'").")");
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


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(11, 10, "Page {$iCurrentPage} of {$iTotalPages}");


			$objPdf->Text(125, 23, $sBrand);
                        $objPdf->Text(125, 28, $sAuditStage);
                        $objPdf->Text(125, 33, formatDate($sAuditDate));



			$sSize = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");


			$objPdf->SetFont('Arial', '', 8);

			$objPdf->Text(38, 50.5, $iSamplesMeasured);
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 160, 47, 4);

//			$objPdf->Text(38, 52.2, $sQtyPerSize);
			$objPdf->SetXY(37, 52.5);
			$objPdf->MultiCell(162, 4, $sQtyPerSize, 0, "L");


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(77, 225.2, $sSize);

			$objPdf->Text(132, 67, $sColor);



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
				$objPdf->Text(13, (80.5 + ($i * 5.320)), ($i + 1));

				$objPdf->SetFont('Arial', '', 8);
				$objPdf->SetXY(20.5, (77.5 + ($i * 5.320)));
				$objPdf->MultiCell(85, 2.2, $sPoint, 0, "L");

				$objPdf->SetFont('Arial', '', 7);
				$objPdf->Text(123.5, (80.5 + ($i * 5.320)), $sSpecs);

				$objPdf->Text(187.6, (80.5 + ($i * 5.320)), $sTolerance);

		
				for ($j = 1; $j <= $iSamplesChecked; $j ++)
				{
					if ($sSizeFindings["{$j}-{$iPoint}"] != "" && strtolower($sSizeFindings["{$j}-{$iPoint}"]) != "ok" && $sSizeFindings["{$j}-{$iPoint}"] != "0")
					{
						$fMeaseuredValue  = ConvertToFloatValue($sSizeFindings["{$j}-{$iPoint}"]);
						$fSpecs           = ConvertToFloatValue($sSpecs);
						$fDifferenceValue = ($fMeaseuredValue + $fSpecs);
						$fTolerance       = ConvertToFloatValue($sTolerance);

						$fPositiveTolerance = ($fSpecs + $fTolerance);
						$fNegativeTolerance = ($fSpecs - $fTolerance);

						if ($fDifferenceValue >= $fNegativeTolerance && $fDifferenceValue <= $fPositiveTolerance)
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


			$objPdf->Text(54, 266, "{$sAuditor} / MATRIX Sourcing");
			$objPdf->Text(130, 266, formatDate($sAuditDate));


			$iCurrentPage ++;
		}
	}

       /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 5


	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(3, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 181, 10, 21.5);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(177, 33, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 10, "Page {$iCurrentPage} of {$iTotalPages}");


	$objPdf->Text(125, 23, $sBrand);
	$objPdf->Text(125, 28, $sAuditStage);
	$objPdf->Text(125, 33, formatDate($sAuditDate));


	// Report Details
	$objPdf->SetFont('Arial', '', 11);

	$objPdf->Text(140, 56.6, formatNumber($fAql, true, (($fAql < 1) ? 2 : 1)));
        
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

	$iCurrentPage ++;

	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 6  -  DEFECT IMAGES


	if (count($sDefects) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
		$iTemplateId = $objPdf->importPage(6, '/MediaBox');


		$iPages = @ceil(count($sDefects) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			// QR Code
			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 181, 10, 21.5);


			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(177, 33, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(10.3, 10, "Page {$iCurrentPage} of {$iTotalPages}");


			$objPdf->Text(125, 23, $sBrand);
                        $objPdf->Text(125, 28, $sAuditStage);
                        $objPdf->Text(125, 33, formatDate($sAuditDate));


			$objPdf->SetFont('Arial', 'B', 12);
			$objPdf->Text(10, 42, "Defect Images");



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


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 7  -  PACKING IMAGES


	if (count($sPacking) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
		$iTemplateId = $objPdf->importPage(6, '/MediaBox');


		$iPages = @ceil(count($sPacking) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			// QR Code
			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 181, 10, 21.5);


			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(177, 33, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(10.3, 10, "Page {$iCurrentPage} of {$iTotalPages}");


                        $objPdf->Text(125, 23, $sBrand);
                        $objPdf->Text(125, 28, $sAuditStage);
                        $objPdf->Text(125, 33, formatDate($sAuditDate));


			$objPdf->SetFont('Arial', 'B', 12);
			$objPdf->Text(11, 42, "Packing Images");



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


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 8  -  SPECS SHEETS


	if (count($sSpecsSheets) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
		$iTemplateId = $objPdf->importPage(6, '/MediaBox');


		for ($i = 0; $i < count($sSpecsSheets); $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 181, 10, 21.5);


			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(177, 33, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(10.3, 10, "Page {$iCurrentPage} of {$iTotalPages}");


                        $objPdf->Text(125, 23, $sBrand);
                        $objPdf->Text(125, 28, $sAuditStage);
                        $objPdf->Text(125, 33, formatDate($sAuditDate));


			$objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(11, 42, "Lab Reports / Specs Sheets");

//			$objPdf->SetFont('Arial', '', 10);
//			$objPdf->Text(6, 37, "Page {$iCurrentPage} of {$iTotalPages}");


			$objPdf->Image($sSpecsSheets[$i], 10, 47, 190);
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 9  -  MISC IMAGES


	if (count($sMisc) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
		$iTemplateId = $objPdf->importPage(6, '/MediaBox');


		$iPages = @ceil(count($sMisc) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			// QR Code
			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 181, 10, 21.5);


			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(177, 33, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(10.3, 10, "Page {$iCurrentPage} of {$iTotalPages}");

			$objPdf->SetFont('Arial', '', 9);
                        $objPdf->Text(125, 23, $sBrand);
                        $objPdf->Text(125, 28, $sAuditStage);
                        $objPdf->Text(125, 33, formatDate($sAuditDate));


			$objPdf->SetFont('Arial', 'B', 12);
			$objPdf->Text(11, 42, "Miscellaneous Images");


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

        /////////////////////Annexure 2 page ///////////////////////////////////////////////////
/*       
	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/hohenstein.pdf");
	$iTemplateId = $objPdf->importPage(5, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 181, 10, 21.5);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(177, 33, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 10, "Page {$iCurrentPage} of {$iTotalPages}");


	$objPdf->Text(125, 23, $sBrand);
	$objPdf->Text(125, 28, $sAuditStage);
	$objPdf->Text(125, 33, formatDate($sAuditDate));
*/

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



	@unlink($sBaseDir.TEMP_DIR."{$sAuditCode}.png");


	$sPdfFile = ($sBaseDir.TEMP_DIR."S{$Id}-QA-Report.pdf");

	if (count($Recipients) > 0)
		$objPdf->Output($sPdfFile, 'F');

	else
		$objPdf->Output(@basename($sPdfFile), 'D');
?>