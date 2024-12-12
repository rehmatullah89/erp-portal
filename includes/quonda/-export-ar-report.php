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


	$objPdf =& new FPDI( );

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/Adidas.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage( );
	$objPdf->useTemplate($iTemplateId, 0, 0);

	$objPdf->SetFont('Arial', '', 10);
	$objPdf->SetTextColor(50, 50, 50);


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
	$sSpecsSheet1       = $objDb->getField(0, 'specs_sheet_1');
	$sSpecsSheet2       = $objDb->getField(0, 'specs_sheet_2');
	$sSpecsSheet3       = $objDb->getField(0, 'specs_sheet_3');
	$sSpecsSheet4       = $objDb->getField(0, 'specs_sheet_4');
	$sSpecsSheet5       = $objDb->getField(0, 'specs_sheet_5');
	$sSpecsSheet6       = $objDb->getField(0, 'specs_sheet_6');
	$sSpecsSheet7       = $objDb->getField(0, 'specs_sheet_7');
	$sSpecsSheet8       = $objDb->getField(0, 'specs_sheet_8');
	$sSpecsSheet9       = $objDb->getField(0, 'specs_sheet_9');
	$sSpecsSheet10      = $objDb->getField(0, 'specs_sheet_10');
	$iLine              = $objDb->getField(0, "line_id");
	$fDhu               = $objDb->getField(0, "dhu");
	$sLine              = $objDb->getField(0, "_Line");

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


	$sSQL = "SELECT style, sub_brand_id, (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand FROM tbl_styles WHERE id='$iStyle'";
	$objDb->query($sSQL);

	$sStyle = $objDb->getField(0, "style");
	$iBrand = $objDb->getField(0, "sub_brand_id");
	$sBrand = $objDb->getField(0, "_Brand");


	$sSQL = "SELECT customer_po_no FROM tbl_po WHERE id='$iPo'";
	$objDb->query($sSQL);

	$sCustomerNo = $objDb->getField(0, "customer_po_no");


	$sSQL = "SELECT etd_required FROM tbl_po_colors WHERE po_id='$iPo' ORDER BY id LIMIT 1";
	$objDb->query($sSQL);

	$sEtdRequired = $objDb->getField(0, 'etd_required');


	$sSQL = "SELECT * FROM tbl_ar_inspection_checklist WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$sModelName          = $objDb->getField(0, "model_name");
	$sWorkingNo          = $objDb->getField(0, "working_no");
	$sFabricApproval     = $objDb->getField(0, "fabric_approval");
	$sCounterSampleAppr  = $objDb->getField(0, "counter_sample_appr");
	$sGarmentWashingTest = $objDb->getField(0, "garment_washing_test");
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
	$sCartonNoChecked    = $objDb->getField(0, "carton_no_checked");

	if ($sWorkingNo == "")
		$sWorkingNo = $sStyle;


	$objPdf->Text(67, 38, $sAuditStage);
	$objPdf->Text(43, 43.5, '');
	$objPdf->Text(43, 49.4, "{$sVendor}     (Line: {$sLine})");
	$objPdf->Text(43, 55, $sModelName);
	$objPdf->Text(43, 61, $sColors);

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(43, 66, ($sPo.$sAdditionalPos));

	$objPdf->SetFont('Arial', '', 10);
	$objPdf->Text(43, 72, ($iQuantity.' pcs'));
	$objPdf->Text(43, 78, $sCustomerNo);

	$objPdf->Text(145, 43.5, $sAuditor);
	$objPdf->Text(168, 49, formatDate($sEtdRequired));
	$objPdf->Text(158, 55, $sWorkingNo);

	$sAccepted = array(
	                    "2"   => 0,
	                    "3"   => 0,
	                    "5"   => 0,
	                    "8"   => 0,
	                    "13"  => 0,
	                    "20"  => 0,
	                    "32"  => 0,
	                    "50"  => 1,
	                    "80"  => 2,
	                    "125" => 3,
	                    "200" => 5,
	                    "315" => 7,
	                    "500" => 10
	                  );

	$sRejected = array(
	                    "2"   => 1,
	                    "3"   => 1,
	                    "5"   => 1,
	                    "8"   => 1,
	                    "13"  => 1,
	                    "20"  => 1,
	                    "32"  => 1,
	                    "50"  => 2,
	                    "80"  => 3,
	                    "125" => 4,
	                    "200" => 6,
	                    "315" => 8,
	                    "500" => 11
	                  );

	$objPdf->Text(37, 90, "1.0");
	$objPdf->Text(93, 90, $iTotalGmts);
	$objPdf->Text(149, 90, $sAccepted[$iTotalGmts]);
	$objPdf->Text(180, 90, $sRejected[$iTotalGmts]);

	$objPdf->Text((($sFabricApproval == 'Y') ? 46 : 58), 97, (($sFabricApproval == 'Y') ? 'Y' : 'N'));
	$objPdf->Text((($sCounterSampleAppr == 'Y') ? 106 : 118), 97, (($sCounterSampleAppr == 'Y') ? 'Y' : 'N'));
	$objPdf->Text((($sGarmentWashingTest == 'Y') ? 168 : 180), 97, (($sGarmentWashingTest == 'Y') ? 'Y' : 'N'));

	$objPdf->Text((($sColorShade == 'Y') ? 46 : 58), 108, (($sColorShade == 'Y') ? 'Y' : 'N'));
	$objPdf->Text((($sAppearance == 'Y') ? 46 : 58), 113.5, (($sAppearance == 'Y') ? 'Y' : 'N'));
	$objPdf->Text((($sHandfeel == 'Y') ? 46 : 58), 119, (($sHandfeel == 'Y') ? 'Y' : 'N'));
	$objPdf->Text((($sPrinting == 'Y') ? 46 : 58), 124.5, (($sPrinting == 'Y') ? 'Y' : 'N'));
	$objPdf->Text((($sEmbridery == 'Y') ? 46 : 58), 130, (($sEmbridery == 'Y') ? 'Y' : 'N'));

	$objPdf->Text((($sFibreContent == 'Y') ? 105 : 117), 108, (($sFibreContent == 'Y') ? 'Y' : 'N'));
	$objPdf->Text((($sCountryOfOrigin == 'Y') ? 105 : 117), 113.5, (($sCountryOfOrigin == 'Y') ? 'Y' : 'N'));
	$objPdf->Text((($sCareInstruction == 'Y') ? 105 : 117), 119, (($sCareInstruction == 'Y') ? 'Y' : 'N'));
	$objPdf->Text((($sSizeKey == 'Y') ? 105 : 117), 124.5, (($sSizeKey == 'Y') ? 'Y' : 'N'));
	$objPdf->Text((($sAdiComp == 'Y') ? 105 : 117), 130, (($sAdiComp == 'Y') ? 'Y' : 'N'));

	$objPdf->Text((($sShippingMark == 'Y') ? 180 : 192), 108, (($sShippingMark == 'Y') ? 'Y' : 'N'));
	$objPdf->Text((($sColourSizeQty == 'Y') ? 180 : 192), 113.5, (($sColourSizeQty == 'Y') ? 'Y' : 'N'));
	$objPdf->Text((($sPolybag == 'Y') ? 180 : 192), 119, (($sPolybag == 'Y') ? 'Y' : 'N'));
	$objPdf->Text((($sHangtag == 'Y') ? 180 : 192), 124.5, (($sHangtag == 'Y') ? 'Y' : 'N'));
	$objPdf->Text((($sOclUpc == 'Y') ? 180 : 192), 130, (($sOclUpc == 'Y') ? 'Y' : 'N'));


	$iTypes = array(25, 26, 7, 5, 27, 28, 29, 6);
	$iTotal = 0;

	for ($i = 0; $i < count($iTypes); $i ++)
	{
		$sSQL = "SELECT defects,
		                (SELECT buyer_code FROM tbl_defect_codes WHERE id=tbl_qa_report_defects.code_id) AS _Code
		         FROM tbl_qa_report_defects WHERE audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE type_id='{$iTypes[$i]}') ORDER BY id";
		$objDb->query($sSQL);

		$iCount    = $objDb->getCount( );
		$iSubTotal = 0;
		$sDefects  = "";

		for($j = 0; $j < $iCount; $j ++)
		{
			$iDefects = $objDb->getField($j, 'defects');
			$sCode    = $objDb->getField($j, '_Code');

			if ($sDefects != "")
				$sDefects .= ", ";

			$sDefects .= ($sCode." (".$iDefects.")");

			$iTotal    += $iDefects;
			$iSubTotal += $iDefects;
		}

		if ($iSubTotal > 0)
		{
			$objPdf->Text(52, (145 + (5.5 * $i)), $sDefects);
			$objPdf->Text(190, (145 + (5.5 * $i)), $iSubTotal);
		}
	}


	$objPdf->Text(190, 190.5, $iTotal);
	$objPdf->Text(68, 196, $iShipQty);

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(55, 201.5, $sCartonNoChecked);


	$sInlineDetails  = ("Cutting: ".(($iCutting == 0) ? "NA" : formatNumber($iCutting, false)));
	$sInlineDetails .= (", Stitching: ".(($iSewing == 0) ? "NA" : formatNumber($iSewing, false)));
	$sInlineDetails .= (", Finishing: ".(($iFinishing == 0) ? "NA" : formatNumber($iFinishing, false)));
	$sInlineDetails .= (", Packing: ".(($iPacking == 0) ? "NA" : formatNumber($iPacking, false)));

	$objPdf->SetXY(51, 195);
	$objPdf->Cell(150, 0, "Production Status: {$sInlineDetails}", 0, 0, "R");

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(58, 207.3, $iBeautifulProducts);


	$objPdf->SetFont('Arial', '', 10);
	$objPdf->SetXY(15, 214.9);
	$objPdf->MultiCell(188, 5.4, $sComments);


	$objPdf->SetFont('Arial', 'B', 10);

  	if ($sAuditResult == "P")
		$objPdf->Text(115, 241, 'Yes');

  	else
		$objPdf->Text(175, 241, 'Yes');


	$objPdf->SetFont('Arial', '', 11);

	$objPdf->Text(66, 263, formatDate($sAuditDate));
	$objPdf->Text(94, 263, $sAuditor);
	$objPdf->Text(168, 263, formatDate($sAuditDate));



	if ($sSpecsSheet1 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet1))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet1), 10, 10, 190);
	}

	if ($sSpecsSheet2 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet2))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet2), 10, 10, 190);
	}

	if ($sSpecsSheet3 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet3))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet3), 10, 10, 190);
	}

	if ($sSpecsSheet4 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet4))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet4), 10, 10, 190);
	}

	if ($sSpecsSheet5 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet5))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet5), 10, 10, 190);
	}

	if ($sSpecsSheet6 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet6))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet6), 10, 10, 190);
	}

	if ($sSpecsSheet7 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet7))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet7), 10, 10, 190);
	}

	if ($sSpecsSheet8 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet8))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet8), 10, 10, 190);
	}

	if ($sSpecsSheet9 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet9))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet9), 10, 10, 190);
	}

	if ($sSpecsSheet10 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet10))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet10), 10, 10, 190);
	}



    @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

	$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_001_*.*");
	$sPictures = @array_merge($sPictures, @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*"));
	$sPictures = @array_map("strtoupper", $sPictures);
	$sPictures = @array_unique($sPictures);

	$sTemp = array( );

	foreach ($sPictures as $sPicture)
		$sTemp[] = $sPicture;

	$sPictures = $sTemp;



	$objPdf->SetFillColor(255, 255, 0);
	$objPdf->SetFont('Arial', '', 7);


	for ($i = 0; $i < count($sPictures); $i ++)
	{
		$sName  = @strtoupper($sPictures[$i]);
		$sName  = @basename($sName, ".JPG");
		$sParts = @explode("_", $sName);

		$sDefectCode = $sParts[1];
		$sAreaCode   = $sParts[2];


		$sSQL = "SELECT defect, buyer_code,
						(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
				 FROM tbl_defect_codes dc
				 WHERE buyer_code='$sDefectCode' AND report_id='$iReportId'";

		$objDb->query($sSQL);

		$sDefect     = $objDb->getField(0, 0);
		$sDefectCode = $objDb->getField(0, 1);
		$sType       = $objDb->getField(0, 2);


		if (($i % 6) == 0)
			$objPdf->addPage( );


		$iLeft = 5;
		$iTop  = 20;

		if (($i % 6) == 1 || ($i % 6) == 3 || ($i % 6) == 5)
			$iLeft = 107;

		if (($i % 6) == 2 || ($i % 6) == 3)
			$iTop = 115;

		else if (($i % 6) == 4 || ($i % 6) == 5)
			$iTop = 210;



		$sInfo  = "Defect Group: {$sType}\n";
		$sInfo .= "Defect Code: {$sDefectCode}\n";
		$sInfo .= "Defect: {$sDefect}\n";

		$objPdf->SetXY($iLeft, ($iTop - 11));
		$objPdf->MultiCell(98, 3.5, $sInfo, 1, "L", true);


		$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPictures[$i]), $iLeft, $iTop, 98);
	}


	$sPdfFile = ($sBaseDir.TEMP_DIR."S{$Id}-QA-Report.pdf");

	if (count($Recipients) > 0)
		$objPdf->Output($sPdfFile, 'F');

	else
		$objPdf->Output(@basename($sPdfFile), 'D');
?>