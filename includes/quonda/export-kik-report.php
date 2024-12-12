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


	$sDestination = getDbValue("destination", "tbl_destinations", "id='$iDestination'");


	if ($iReportId == 23)
	{
		$sCodes = array("106", "113", "114", "115", "108", "109", "111", "112", "199",
						"0", "201", "202", "203", "204", "205", "206", "208", "211", "216", "299",
						"0", "301", "305", "306", "390",
						"401", "402", "403", "410", "412", "415", "416", "499",
						"0", "501", "502", "503", "599",
						"0", "601", "602", "603", "604", "605", "699",
						"0", "703", "704", "706", "799",
						"0", "801", "802", "803", "804", "805", "899");
	}

	else
	{
		$sCodes = array("W01", "W02", "W03", "W04", "W05", "W06", "W07", "W08", "W09", "W10",
						"W11", "W12", "W13", "W14", "W15", "W16", "W17", "W18", "W19", "W20",
						"W21", "W22", "W23", "W24", "W25", "W26", "W27", "W28", "W29", "W30",
						"W31", "W32", "W33", "W34", "W35");
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


	$iTotalPages  = 4;
	$iTotalPages += getDbValue("COUNT(DISTINCT(CONCAT(size_id, '-', color)))", "tbl_qa_report_samples", "audit_id='$Id'");
	$iTotalPages += count($sSpecsSheets);
	$iTotalPages += @ceil(count($sPacking) / 4);
	$iTotalPages += @ceil(count($sDefects) / 4);
	$iTotalPages += @ceil(count($sMisc) / 4);


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	$objPdf =& new FPDI( );

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/kik-p1.pdf");
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
	$objPdf->Text(11, 36, "Page 1 of {$iTotalPages}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(140, 16.5, $sBrand);
	$objPdf->Text(140, 22, $sAuditStage);
	$objPdf->Text(140, 27.5, formatDate($sAuditDate));



	$sSQL = "SELECT * FROM tbl_kik_inspection_summary WHERE audit_id='$Id'";
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
	$objPdf->Text(160, 52, "MATRIX Sourcing");

	$objPdf->Text(160, 57, $sAuditor);

	$objPdf->Text(64, 62, getDbValue("city", "tbl_vendors", "id='$iVendor'"));
	$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sApprovedSample == "Yes" || $sApprovedSample == "Y") ? 172 : 193), 59, 4);

	$objPdf->Text(64, 67, formatDate($sAuditDate));
	$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sApprovedTrims == "Y") ? 172 : 193), 64, 4);

	$objPdf->Text(64, 72, formatNumber($iQuantity, false));
	$objPdf->Text(160, 72, $iQtyOfLots);

	$objPdf->Text(64, 77, formatNumber($iPresentedQty, false));
	$objPdf->Text(160, 77, $iQtyPerLot);

	$objPdf->Text(70, 97, (($sCustomSample == "Y") ? "CUSTOM ({$iTotalGmts})" : $iTotalGmts).(($sInspectionStatus != "") ? " ({$sInspectionStatus})" : ""));
	$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sAuditResult == "P") ? 14 : 46), 115.5, 5);


	if ($sShippingMarks != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sShippingMarks == "P") ? 109 : 122), 132.5, 4);

	$objPdf->Text(132, 136, $sShippingMarksRemarks);


	if ($sMaterialConformity != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sMaterialConformity == "P") ? 109 : 122), 137.5, 4);

	$objPdf->Text(132, 141, $sMaterialConformityRemarks);


	if ($sProductStyle != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sProductStyle == "P") ? 109 : 122), 142.5, 4);

	$objPdf->Text(132, 146, $sProductStyleRemarks);


	if ($sProductColour != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sProductColour == "P") ? 109 : 122), 147.5, 4);

	$objPdf->Text(132, 151, $sProductColourRemarks);


	if ($sExportCartonPacking != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sExportCartonPacking == "P") ? 109 : 122), 152.5, 4);

	$objPdf->Text(132, 156, $sExportCartonPackingRemarks);


	if ($sInnerCartonPacking != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sInnerCartonPacking == "P") ? 109 : 122), 157.5, 4);

	$objPdf->Text(132, 161, $sInnerCartonPackingRemarks);


	if ($sProductPackaging != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sProductPackaging == "P") ? 109 : 122), 162.5, 4);

	$objPdf->Text(132, 166, $sProductPackagingRemarks);


	if ($sAssortment != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sAssortment == "P") ? 109 : 122), 167.5, 4);

	$objPdf->Text(132, 171, $sAssortmentRemarks);


	if ($sLabeling != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sLabeling == "P") ? 109 : 122), 172.9, 4);

	$objPdf->Text(132, 176.3, $sLabelingRemarks);


	if ($sMarkings != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sMarkings == "P") ? 109 : 122), 178, 4);

	$objPdf->Text(132, 181.4, $sMarkingsRemarks);


	if ($sWorkmanship != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sWorkmanship == "P") ? 109 : 122), 183.1, 4);

	$objPdf->Text(132, 186.5, $sWorkmanshipRemarks);


	if ($sAppearance != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sAppearance == "P") ? 109 : 122), 188.2, 4);

	$objPdf->Text(132, 191.6, $sAppearanceRemarks);


	if ($sFunction != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sFunction == "P") ? 109 : 122), 193.3, 4);

	$objPdf->Text(132, 196.7, $sFunctionRemarks);


	if ($sPrintedMaterials != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sPrintedMaterials == "P") ? 109 : 122), 198.4, 4);

	$objPdf->Text(132, 201.8, $sPrintedMaterialsRemarks);


	if ($sWorkmanshipFinishing != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sWorkmanshipFinishing == "P") ? 109 : 122), 203.5, 4);

	$objPdf->Text(132, 206.9, $sWorkmanshipFinishingRemarks);


	if ($sMeasurement != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sMeasurement == "P") ? 109 : 122), 208, 4);

	$objPdf->Text(132, 211.5, $sMeasurementRemarks);


	if ($sFabricWeight != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sFabricWeight == "P") ? 109 : 122), 213, 4);

	if ($sCalibratedScales != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sCalibratedScales == "Y") ? 71 : 85), 213.3, 4);

	$objPdf->Text(132, 216.5, $sFabricWeightRemarks);


	if ($sCordNorm != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sCordNorm == "P") ? 109 : 122), 218, 4);

	$objPdf->Text(132, 221.5, $sCordNormRemarks);


	if ($sInspectionConditions != "")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sInspectionConditions == "P") ? 109 : 122), 223, 4);

	$objPdf->Text(132, 226.5, $sInspectionConditionsRemarks);

	$objPdf->Text(22, 246.5, $sRemarks1);
	$objPdf->Text(22, 251.5, $sRemarks2);
	$objPdf->Text(22, 256.5, $sRemarks3);
	$objPdf->Text(22, 261.5, $sRemarks4);


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 2


	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/kik-p2".(($iReportId == 23) ? "2" : "").".pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4", "pt");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 181, 10, 21.5);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(177, 33, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 36, "Page 2 of {$iTotalPages}");


	$objPdf->Text(140, 16.5, $sBrand);
	$objPdf->Text(140, 22, $sAuditStage);
	$objPdf->Text(140, 27.5, formatDate($sAuditDate));


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

	$sSQL = "SELECT size_color, size_qty, sample_qty FROM tbl_kik_samples_per_size WHERE audit_id='$Id'";
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



	// Defect Details
	$fTop = 146.08;

	for ($i = 0; $i <= 24; $i ++)
	{
		$fTop += 4.92;

		if ($sCodes[$i] == "0")
			continue;

		$iCode = (int)getDbValue("id", "tbl_defect_codes", "report_id='$iReportId' AND code='{$sCodes[$i]}'");

		if ($iCode == 0)
			continue;


		$iMinorDefects    = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id='$iCode' AND nature='0'");
		$iMajorDefects    = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id='$iCode' AND nature='1'");
		$iCriticalDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id='$iCode' AND nature='2'");

		if ($iMajorDefects == 0 && $iMinorDefects == 0 && $iCriticalDefects == 0)
			continue;


		if ($iCriticalDefects > 0)
			$objPdf->Text(146, $fTop, $iCriticalDefects);

		if ($iMajorDefects > 0)
			$objPdf->Text(167, $fTop, $iMajorDefects);

		if ($iMinorDefects > 0)
			$objPdf->Text(188, $fTop, $iMinorDefects);
	}



	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 3


	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/kik-p3".(($iReportId == 23) ? "3" : "").".pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 181, 10, 21.5);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(177, 33, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 36, "Page 3 of {$iTotalPages}");


	$objPdf->Text(140, 16.5, $sBrand);
	$objPdf->Text(140, 22, $sAuditStage);
	$objPdf->Text(140, 27.5, formatDate($sAuditDate));


	$fTop = 48.40;

	for ($i = 25; $i < count($sCodes); $i ++)
	{
		$fTop += 4.92;

		if ($sCodes[$i] == "0")
			continue;

		$iCode = (int)getDbValue("id", "tbl_defect_codes", "report_id='$iReportId' AND code='{$sCodes[$i]}'");

		if ($iCode == 0)
			continue;


		$iMinorDefects    = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id='$iCode' AND nature='0'");
		$iMajorDefects    = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id='$iCode' AND nature='1'");
		$iCriticalDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id='$iCode' AND nature='2'");

		if ($iMajorDefects == 0 && $iMinorDefects == 0 && $iCriticalDefects == 0)
			continue;


		if ($iCriticalDefects > 0)
			$objPdf->Text(149, $fTop, $iCriticalDefects);

		if ($iMajorDefects > 0)
			$objPdf->Text(169, $fTop, $iMajorDefects);

		if ($iMinorDefects > 0)
			$objPdf->Text(188.5, $fTop, $iMinorDefects);
	}


	$iMinorDefects    = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND nature='0'");
	$iMajorDefects    = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND nature='1'");
	$iCriticalDefects = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND nature='2'");

	$objPdf->Text(149, 230.5, $iCriticalDefects);
	$objPdf->Text(169, 230.5, $iMajorDefects);
	$objPdf->Text(188.5, 230.5, $iMinorDefects);


	$fAql = getDbValue("aql", "tbl_brands", "id='$iParent'");

	$objPdf->Text(149, 235.5, "0");
	$objPdf->Text(169, 235.5, $iAqlChart[$iTotalGmts]["2.5"]);
	$objPdf->Text(188.5, 235.5, $iAqlChart[$iTotalGmts]["4"]);

	$objPdf->Text(149, 240.5, (($sAuditResult == "P") ? "PASS" : "FAIL"));


	$objPdf->SetXY(12, 248.5);
	$objPdf->MultiCell(188, 4.8, $sComments, 0);


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 4


	$iCurrentPage = 4;
	$iPageCount   = $objPdf->setSourceFile($sBaseDir."templates/kik-p4.pdf");
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

			//$sQtyPerSize .= ("{$sSize} / {$sColor} (".getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize' AND color LIKE '$sColor'").")");
		}
	}


	foreach ($sColors as $sColor)
	{
		foreach ($iSizes as $iSize)
		{
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
			$objPdf->Text(11, 36, "Page {$iCurrentPage} of {$iTotalPages}");


			$objPdf->Text(140, 16.5, $sBrand);
			$objPdf->Text(140, 22, $sAuditStage);
			$objPdf->Text(140, 27.5, formatDate($sAuditDate));



			$sSize = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");


			$objPdf->SetFont('Arial', '', 8);

			$objPdf->Text(38, 47.5, $iSamplesMeasured);
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 160, 44, 4);

//			$objPdf->Text(38, 52.2, $sQtyPerSize);
			$objPdf->SetXY(37, 49.5);
			$objPdf->MultiCell(162, 4, $sQtyPerSize, 0, "L");


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(77, 205.5, $sSize);

			$objPdf->Text(111, 63.5, $sColor);


			if (strtotime($sAuditDate) < strtotime("2015-11-26"))
				$iSamplesChecked = getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize'");

			else
				$iSamplesChecked = getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize' AND color LIKE '$sColor'");
			
			
			if ($iSamplesChecked > 5)
				$iSamplesChecked = 5;
			
			

			$sSQL = "SELECT point_id, specs,
							(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
							(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point
					 FROM tbl_style_specs
					 WHERE style_id='$iStyle' AND size_id='$iSize' AND version='0' AND specs!='0' AND specs!=''
					 ORDER BY id
					 LIMIT 26";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
			$iOut   = 0;

			for($i = 0; $i < $iCount; $i ++)
			{
				$iPoint     = $objDb->getField($i, 'point_id');
				$sSpecs     = $objDb->getField($i, 'specs');
				$sPoint     = $objDb->getField($i, '_Point');
				$sTolerance = $objDb->getField($i, '_Tolerance');


				$objPdf->SetFont('Arial', '', 7);
				$objPdf->Text(13, (77.5 + ($i * 4.915)), ($i + 1));

				$objPdf->SetFont('Arial', '', 8);
				$objPdf->SetXY(20.5, (75 + ($i * 4.915)));
				$objPdf->MultiCell(70, 2.2, $sPoint, 0, "L");

				$objPdf->SetFont('Arial', '', 7);
				$objPdf->Text(97.5, (77.5 + ($i * 4.915)), $sSpecs);

				$objPdf->Text(187.6, (77.5 + ($i * 4.915)), $sTolerance);


				for ($j = 1; $j <= $iSamplesChecked; $j ++)
				{
					if ($sSizeFindings["{$j}-{$iPoint}"] != "" && strtolower($sSizeFindings["{$j}-{$iPoint}"]) != "ok" && $sSizeFindings["{$j}-{$iPoint}"] != "0")
					{
						$objPdf->SetFillColor(255, 255, 0);
						$objPdf->SetXY((98.5 + ($j * 9.8)), (73.8 + ($i * 4.915)));
						$objPdf->Cell(9.1, 4.415, $sSizeFindings["{$j}-{$iPoint}"], 0, 0, "C", true);

						$iOut ++;
					}

					else
						$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (100.5 + ($j * 9.8)), (74.2 + ($i * 4.915)), 4);
				}
			}


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(89, 205.5, ($iCount * $iSamplesChecked));
			$objPdf->Text(179, 205.5, $iOut);



			if ($sMeasurementResult == "P")
				$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 28, 228, 4);

			else if ($sMeasurementResult == "F")
				$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 77.5, 228, 4);

			else if ($sMeasurementResult == "H")
				$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 131, 228, 4);


			$objPdf->SetXY(12, 243);
			$objPdf->MultiCell(188, 4.8, $sMeasurementComments, 0);


			$objPdf->Text(13, 271, "{$sAuditor} / MATRIX Sourcing");
			$objPdf->Text(130, 265.5, formatDate($sAuditDate));


			$iCurrentPage ++;
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 5


	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/kik-annexure.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 181, 10, 21.5);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(177, 33, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(11, 36, "Page {$iCurrentPage} of {$iTotalPages}");


	$objPdf->Text(140, 16.5, $sBrand);
	$objPdf->Text(140, 22, $sAuditStage);
	$objPdf->Text(140, 27.5, formatDate($sAuditDate));


	// Report Details
	$objPdf->SetFont('Arial', '', 11);

	$objPdf->Text(121, 62, formatNumber($fAql, true, (($fAql < 1) ? 2 : 1)));


	$iCurrentPage ++;

	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 6  -  DEFECT IMAGES


	if (count($sDefects) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/kik-page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


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
			$objPdf->Text(10.3, 42, "Page {$iCurrentPage} of {$iTotalPages}");


			$objPdf->Text(140, 16.5, $sBrand);
			$objPdf->Text(140, 22, $sAuditStage);
			$objPdf->Text(140, 27.5, formatDate($sAuditDate));


			$objPdf->SetFont('Arial', 'B', 12);
			$objPdf->Text(10, 38, "Defect Images");



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
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/kik-page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


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
			$objPdf->Text(10.3, 42, "Page {$iCurrentPage} of {$iTotalPages}");


			$objPdf->Text(140, 16.5, $sBrand);
			$objPdf->Text(140, 22, $sAuditStage);
			$objPdf->Text(140, 27.5, formatDate($sAuditDate));


			$objPdf->SetFont('Arial', 'B', 12);
			$objPdf->Text(11, 38, "Packing Images");



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
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/kik-page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		for ($i = 0; $i < count($sSpecsSheets); $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			// QR Code
			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 181, 10, 21.5);


			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(177, 33, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 9);
			$objPdf->Text(10.3, 42, "Page {$iCurrentPage} of {$iTotalPages}");


			$objPdf->Text(140, 16.5, $sBrand);
			$objPdf->Text(140, 22, $sAuditStage);
			$objPdf->Text(140, 27.5, formatDate($sAuditDate));


			$objPdf->SetFont('Arial', 'B', 12);
			$objPdf->Text(11, 38, "Lab Reports / Specs Sheets");


			$objPdf->Image($sSpecsSheets[$i], 10, 47, 190);
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 9  -  MISC IMAGES


	if (count($sMisc) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/kik-page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


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
			$objPdf->Text(10.3, 42, "Page {$iCurrentPage} of {$iTotalPages}");

			$objPdf->Text(140, 16.5, $sBrand);
			$objPdf->Text(140, 22, $sAuditStage);
			$objPdf->Text(140, 27.5, formatDate($sAuditDate));


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