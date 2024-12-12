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
	                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor,
	                (SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line
	         FROM tbl_qa_reports WHERE id='$Id'";
	$objDb->query($sSQL);

	$iReportId          = $objDb->getField(0, "report_id");
	$sVendor            = $objDb->getField(0, "_Vendor");
	$sAuditor           = $objDb->getField(0, "_Auditor");
	$iPo                = $objDb->getField(0, "po_id");
	$iAdditionalPos     = $objDb->getField(0, "additional_pos");
	$sPo                = $objDb->getField(0, "_Po");
	$iStyle             = $objDb->getField(0, "style_id");
	$sAuditCode         = $objDb->getField(0, "audit_code");
	$sAuditDate         = $objDb->getField(0, "audit_date");
	$sAuditStage        = $objDb->getField(0, "audit_stage");
	$sAuditResult       = $objDb->getField(0, "audit_result");
	$fStandard          = $objDb->getField(0, "standard");
	$sCustomSample      = $objDb->getField(0, "custom_sample");
	$iTotalGmts         = $objDb->getField(0, "total_gmts");
	$iShipQty           = $objDb->getField(0, "ship_qty");
	$sColors            = $objDb->getField(0, "colors");
	$sShippingMark      = $objDb->getField(0, "shipping_mark");
	$fKnitted           = $objDb->getField(0, "knitted");
	$fDyed              = $objDb->getField(0, "dyed");
	$iCutting           = $objDb->getField(0, "cutting");
	$iSewing            = $objDb->getField(0, "sewing");
	$iFinishing         = $objDb->getField(0, "finishing");
	$iPacking           = $objDb->getField(0, "packing");
	$iBeautifulProducts = $objDb->getField(0, "beautiful_products");
	$sFinalAuditDate    = $objDb->getField(0, "final_audit_date");
	$sComments          = $objDb->getField(0, "qa_comments");
	$iLine              = $objDb->getField(0, "line_id");
	$fDhu               = $objDb->getField(0, "dhu");
	$sLine              = $objDb->getField(0, "_Line");


	$sSpecsSheets = array( );

	for ($i = 1; $i <= 10; $i ++)
	{
		$sSpecsSheet = $objDb->getField(0, "specs_sheet_{$i}");

		if ($sSpecsSheet != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet))
			$sSpecsSheets[] = $sSpecsSheet;
	}


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


	$sSQL = "SELECT style, brand_id, sub_brand_id, (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand FROM tbl_styles WHERE id='$iStyle'";
	$objDb->query($sSQL);

	$sStyle  = $objDb->getField(0, "style");
	$iParent = $objDb->getField(0, "brand_id");
	$iBrand  = $objDb->getField(0, "sub_brand_id");
	$sBrand  = $objDb->getField(0, "_Brand");


	$sSQL = "SELECT customer_po_no FROM tbl_po WHERE id='$iPo'";
	$objDb->query($sSQL);

	$sCustomerNo = $objDb->getField(0, "customer_po_no");


	$sSQL = "SELECT etd_required FROM tbl_po_colors WHERE po_id='$iPo' ORDER BY id LIMIT 1";
	$objDb->query($sSQL);

	$sEtdRequired = $objDb->getField(0, 'etd_required');



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

		if (@stripos($sPic, "_pack_") !== FALSE || @stripos($sPic, "_001_") !== FALSE)
			$sPacking[] = $sPicture;

		else if (@stripos($sPic, "_misc_") !== FALSE || @stripos($sPic, "_00_") !== FALSE || @substr_count($sPic, "_") < 3)
			$sMisc[] = $sPicture;

		else
			$sDefects[] = $sPicture;
	}


	$iTotalPages  = 2;
	$iTotalPages += count($sSpecsSheets);
	$iTotalPages += @ceil(count($sPacking) / 4);
	$iTotalPages += @ceil(count($sDefects) / 4);
	$iTotalPages += @ceil(count($sMisc) / 4);



	$sSQL = "SELECT * FROM tbl_ar_inspection_checklist WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$sModelName          = $objDb->getField(0, "model_name");
	$sWorkingNo          = $objDb->getField(0, "working_no");
	$sFabricApproval     = $objDb->getField(0, "fabric_approval");
	$sCounterSampleAppr  = $objDb->getField(0, "counter_sample_appr");
	$sGarmentWashingTest = $objDb->getField(0, "garment_washing_test");
	$sSealingSampleAppr  = $objDb->getField(0, "sealing_sample_appr");
	$sMetalDetection     = $objDb->getField(0, "metal_detection");
	$sColorShade         = $objDb->getField(0, "color_shade");
	$sAppearance         = $objDb->getField(0, "appearance");
	$sHandfeel           = $objDb->getField(0, "handfeel");
	$sPrinting           = $objDb->getField(0, "printing");
	$sEmbridery          = $objDb->getField(0, "embridery");
	$sFibreContent       = $objDb->getField(0, "fibre_content");
	$sCountryOfOrigin    = $objDb->getField(0, "country_of_origin");
	$sCareInstruction    = $objDb->getField(0, "care_instruction");
	$sSizeKey            = $objDb->getField(0, "size_key");
	$sAdiComp            = $objDb->getField(0, "adi_comp");
	$sColourSizeQty      = $objDb->getField(0, "colour_size_qty");
	$sPolybag            = $objDb->getField(0, "polybag");
	$sHangtag            = $objDb->getField(0, "hangtag");
	$sOclUpc             = $objDb->getField(0, "ocl_upc");
	$sDecorativeLabel    = $objDb->getField(0, "decorative_label");
	$sCareLabel          = $objDb->getField(0, "care_label");
	$sSecurityLabel      = $objDb->getField(0, "security_label");
	$sAdditionalLabel    = $objDb->getField(0, "additional_label");
	$sPackingMode        = $objDb->getField(0, "packing_mode");
	$sCartonNoChecked    = $objDb->getField(0, "carton_no_checked");


	if ($sWorkingNo == "")
		$sWorkingNo = $sStyle;



	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	$objPdf =& new FPDI( );

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/adidas-p1.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");



	// Report Details
	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(75, 33.5, $sAuditStage);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->Text(53, 38.2, $sVendor);
	$objPdf->Text(162, 38.1, $sAuditor);

	$objPdf->Text(53, 42, $sModelName);
	$objPdf->Text(162, 42, formatDate($sEtdRequired));

	$objPdf->Text(53, 46.2, $sWorkingNo);
	$objPdf->Text(53, 50.5, ($sPo.$sAdditionalPos));
	$objPdf->Text(53, 54.5, $sColors);
	$objPdf->Text(53, 58.5, ($iQuantity.' pcs'));
	$objPdf->Text(53, 62.5, $sCustomerNo);


	$fAql = getDbValue("aql", "tbl_brands", "id='$iParent'");

	if (@isset($iAqlChart["{$iTotalGmts}"]["{$fAql}"]))
		$iMaxDefects = $iAqlChart["{$iTotalGmts}"]["{$fAql}"];

	else
	{
		foreach ($iAqlChart as $iSampleSize => $sAqlDetails)
		{
			if ($iTotalGmts >= $sAqlDetails["F"] && $iTotalGmts <= $sAqlDetails["T"])
			{
				$iMaxDefects = $iAqlChart["{$iSampleSize}"]["{$fAql}"];

				break;
			}
		}
	}


	$objPdf->Text(49, 70.6, formatNumber($fAql, true, (($fAql < 1) ? 2 : 1)));
	$objPdf->Text(101, 70.6, (($sCustomSample == "Y") ? "CUSTOM ({$iTotalGmts})" : $iTotalGmts));
	$objPdf->Text(143, 70.6, $iMaxDefects);
	$objPdf->Text(184, 70.6, ($iMaxDefects + 1));

	$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sFabricApproval == 'Y') ? 69 : 80), 71, 4);
	$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sSealingSampleAppr == 'Y') ? 136 : 146.5), 71, 4);
	$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sGarmentWashingTest == 'Y') ? 190 : 200), 71, 4);
	$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sMetalDetection == 'Y') ? 69 : 80), 76, 4);

	$objPdf->Text(65, 89.5, (($sColorShade == 'Y') ? 'Y' : 'N'));
	$objPdf->Text(65, 94.5, (($sAppearance == 'Y') ? 'Y' : 'N'));
	$objPdf->Text(65, 99, (($sHandfeel == 'Y') ? 'Y' : 'N'));

	$objPdf->Text(110, 89.5, (($sFibreContent == 'Y') ? 'Y' : 'N'));
	$objPdf->Text(110, 94.5, (($sCountryOfOrigin == 'Y') ? 'Y' : 'N'));
	$objPdf->Text(110, 99, (($sCareInstruction == 'Y') ? 'Y' : 'N'));
	$objPdf->Text(110, 103.9, (($sSizeKey == 'Y') ? 'Y' : 'N'));

	$objPdf->Text(154, 89.5, (($sDecorativeLabel == 'Y') ? 'Y' : 'N'));
	$objPdf->Text(154, 94.5, (($sCareLabel == 'Y') ? 'Y' : 'N'));
	$objPdf->Text(154, 99, (($sSecurityLabel == 'Y') ? 'Y' : 'N'));
	$objPdf->Text(154, 103.9, (($sAdditionalLabel == 'Y') ? 'Y' : 'N'));

	$objPdf->Text(197, 89.5, (($sOclUpc == 'Y') ? 'Y' : 'N'));
	$objPdf->Text(197, 94.5, (($sPackingMode == 'Y') ? 'Y' : 'N'));
	$objPdf->Text(197, 99, (($sPolybag == 'Y') ? 'Y' : 'N'));
	$objPdf->Text(197, 103.9, (($sHangtag == 'Y') ? 'Y' : 'N'));


	$iTypes = array(33 => "Fab", 26 => "W", 7 => "M", 5 => "CI", 27 => "Dec", 28 => "Acc", 29 => "Fin", 6 => "P");
	$iTotal = 0;
	$iTop   = 118.7;

	foreach ($iTypes as $iType => $sTypeAbbr)
	{
		$sSQL = "SELECT defects,
		                (SELECT defect FROM tbl_defect_codes WHERE id=tbl_qa_report_defects.code_id) AS _Code
		         FROM tbl_qa_report_defects
		         WHERE audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE type_id='$iType')
		         ORDER BY id";
		$objDb->query($sSQL);

		$iCount    = $objDb->getCount( );
		$iSubTotal = 0;
		$sDetails  = "";

		for($j = 0; $j < $iCount; $j ++)
		{
			$iDefects = $objDb->getField($j, 'defects');
			$sCode    = $objDb->getField($j, '_Code');

			if ($sDetails != "")
				$sDetails .= ", ";

			$sDetails .= "({$iDefects}) {$sCode}";

			$iTotal    += $iDefects;
			$iSubTotal += $iDefects;
		}

		if ($iSubTotal > 0)
		{
			$objPdf->Text(29.5, $iTop, $sTypeAbbr);
			$objPdf->Text(45.5, $iTop, $sDetails);
			$objPdf->Text(192, $iTop, $iSubTotal);

			$iTop += 4.85;
		}
	}


	$objPdf->SetFont('Arial', '', 10);
	$objPdf->Text(192, 161, $iTotal);

	$objPdf->SetFont('Arial', '', 7);
	$objPdf->Text(66, 169, $iShipQty);

	$sInlineDetails  = ("Cutting: ".(($iCutting == 0) ? "NA" : formatNumber($iCutting, false)));
	$sInlineDetails .= (", Stitching: ".(($iSewing == 0) ? "NA" : formatNumber($iSewing, false)));
	$sInlineDetails .= (", Finishing: ".(($iFinishing == 0) ? "NA" : formatNumber($iFinishing, false)));
	$sInlineDetails .= (", Packing: ".(($iPacking == 0) ? "NA" : formatNumber($iPacking, false)));

	$objPdf->SetXY(125, 166);
	$objPdf->Cell(82, 5, $sInlineDetails);

	$objPdf->Text(52, 173.8, $sCartonNoChecked);

	$objPdf->SetXY(23, 179);
	$objPdf->MultiCell(178, 3.5, $sComments);

	$objPdf->Text(80, 197.5, $iBeautifulProducts);


	$sSQL = "SELECT * FROM tbl_ar_beautiful_products WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		for ($i = 1; $i <= 9; $i ++)
			$objPdf->Text((66 + ($i * 11)), 219, $objDb->getField(0, "c{$i}"));
	}


	$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sAuditResult == 'P') ? 156.5 : 189.3), 263, 6);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->Text(84.5, 262, "Tahir Islam");
	$objPdf->Text(93, 271.5, formatDate($sAuditDate));

	$objPdf->Image(($sBaseDir.'files/signatures/tahir-islam.jpg'), 114, 261, 20);


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 2


	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/adidas-p2.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 10);

	$objPdf->Text(6, 35, "Page 2 of {$iTotalPages}");


	// Report Details
	$objPdf->SetFont('Arial', '', 11);

	$objPdf->Text(115, 49, formatNumber($fAql, true, 1));


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 3  -  DEFECT IMAGES


	$iCurrentPage = 3;

	if (count($sDefects) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/adidas-p3.pdf");
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
			$objPdf->Text(8.5, 37, "Defect Images");

			$objPdf->SetFont('Arial', '', 10);
			$objPdf->Text(8.5, 42, "Page {$iCurrentPage} of {$iTotalPages}");



			$objPdf->SetFont('Arial', '', 7);

			for ($j = 0; $j < 4 && $iIndex < count($sDefects); $j ++, $iIndex ++)
			{
				$sName  = @strtoupper($sDefects[$iIndex]);
				$sName  = @basename($sName, ".JPG");
				$sParts = @explode("_", $sName);

				$sDefectCode = $sParts[1];
				$sAreaCode   = $sParts[2];


				$sSQL = "SELECT defect, buyer_code,
								(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
						 FROM tbl_defect_codes dc
						 WHERE code='$sDefectCode' AND report_id='$iReportId'";
				$objDb->query($sSQL);

				$sDefect = $objDb->getField(0, "defect");
				$sCode   = $objDb->getField(0, "buyer_code");
				$sType   = $objDb->getField(0, "_Type");


				$iLeft = 5;
				$iTop  = 47;

				if ($j == 1 || $j == 3)
					$iLeft = 107;

				if ($j == 2 || $j == 3)
					$iTop = 155;


				$sInfo  = "Type: {$sType}\n";
				$sInfo .= "Code: {$sCode}\n";
				$sInfo .= "Defect: {$sDefect}\n";

				$objPdf->SetXY($iLeft, ($iTop + 90.5));
				$objPdf->MultiCell(98, 3.6, $sInfo, 1, "L", false);


				$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sDefects[$iIndex]), $iLeft, $iTop, 98, 90);
			}
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 4  -  PACKING IMAGES


	if (count($sPacking) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/adidas-p3.pdf");
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
			$objPdf->Text(8.5, 37, "Packing Images");

			$objPdf->SetFont('Arial', '', 10);
			$objPdf->Text(8.5, 42, "Page {$iCurrentPage} of {$iTotalPages}");


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


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 5  -  SPECS SHEETS


	if (count($sSpecsSheets) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/adidas-p3.pdf");
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
			$objPdf->Text(8.5, 37, "Lab Reports / Specs Sheets");

			$objPdf->SetFont('Arial', '', 10);
			$objPdf->Text(8.5, 42, "Page {$iCurrentPage} of {$iTotalPages}");


			$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheets[$i]), 10, 47, 190);
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 6  -  MISC IMAGES


	if (count($sMisc) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/adidas-p3.pdf");
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
			$objPdf->Text(8.5, 37, "Miscellaneous Images");

			$objPdf->SetFont('Arial', '', 10);
			$objPdf->Text(8.5, 42, "Page {$iCurrentPage} of {$iTotalPages}");



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