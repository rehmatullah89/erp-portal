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

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/inflatable.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "Letter");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);


	$sSQL = "SELECT *,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
	                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor
	         FROM tbl_qa_reports
	         WHERE id='$Id'";
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
	$sAuditResult       = $objDb->getField(0, "audit_result");
	$sAuditStage        = $objDb->getField(0, "audit_stage");
	$sCustomSample      = $objDb->getField(0, "custom_sample");
	$iTotalGmts         = $objDb->getField(0, "total_gmts");
	$iShipQty           = $objDb->getField(0, "ship_qty");
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


	$sSQL = "SELECT style, style_name, sub_brand_id, (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand FROM tbl_styles WHERE id='$iStyle'";
	$objDb->query($sSQL);

	$sStyle     = $objDb->getField(0, "style");
	$sStyleName = $objDb->getField(0, "style_name");
	$iBrand     = $objDb->getField(0, "sub_brand_id");
	$sBrand     = $objDb->getField(0, "_Brand");


	$sEtdRequired = getDbValue("etd_required", "tbl_po_colors", "po_id='$iPo' AND style_id='$iStyle'");
	$sDestination = getDbValue("destination", "tbl_destinations", "id IN (SELECT destination_id FROM tbl_po_colors WHERE po_id='$iPo' AND style_id='$iStyle')");



	$objPdf->Text(40, 29.5, $sStyle);
	$objPdf->Text(40, 33.1, $sStyleName);
	$objPdf->Text(67, 37.0, ($sPo.$sAdditionalPos));
	$objPdf->Text(40, 41.0, $sDestination);
	$objPdf->Text(67, 41.0, formatDate($sEtdRequired));
	$objPdf->Text(40, 44.8, $sVendor);

	$objPdf->Text(125, 29.5, formatDate($sAuditDate));
	$objPdf->Text(125, 37.0, formatNumber($iQuantity, false));
	$objPdf->Text(125, 41.0, (($sCustomSample == "Y") ? "CUSTOM ({$iTotalGmts})" : $iTotalGmts));
	$objPdf->Text(165, 37.0, formatNumber($iShipQty, false));
//	$objPdf->Text(165, 41.0, $iGmtsDefective);

  	if ($sAuditResult == "P")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 40, 229.0, 4);

  	else
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 94, 229.0, 4);

//	$objPdf->Text(176, 232.0, $iGmtsDefective);
	$objPdf->Text(25, 241.5, $sAuditor);


	$sCodes = array("0", "0", "101", "102", "103", "104", "105", "106", "107", "108", "0", "110", "111", "112", "0",
					"0", "0", "201", "202", "203", "204", "205", "206", "207", "208", "0",
					"0", "0", "301", "302", "303", "0", "305", "306", "0",
					"0", "0", "401", "402", "403", "404", "405", "406", "407", "408", "409", "0",
					"0", "0", "501", "502", "0", "0", "0", "0", "0", "508", "0",
					"0", "0", "601", "602", "603", "604", "605", "606", "0",
					"0", "0", "701", "702", "703", "704", "705", "706", "707", "0",
					"0", "0", "801", "802", "803", "804", "0", "0", "0", "0", "0", "810");


	$iTotalDefects = 0;

	for ($i = 0; $i < count($sCodes); $i ++)
	{
		if ($sCodes[$i] == "0")
			continue;


		$iCode = (int)getDbValue("id", "tbl_defect_codes", "report_id='$iReportId' AND code='{$sCodes[$i]}'");

		if ($iCode == 0)
			continue;


		$iDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id='$iCode'");

		if ($iDefects == 0)
			continue;


		$objPdf->Text( (($i <= 46) ? 92 : 180), (($i <= 46) ? (50.3 + (3.81 * $i)) : (46.4 + (3.81 * ($i - 46)))), $iDefects);

		$iTotalDefects += $iDefects;
	}



	$objPdf->Text(165, 41.0, $iTotalDefects);
	$objPdf->Text(176, 232.0, $iTotalDefects);


	$objPdf->SetFont('Arial', '', 5.5);

	$objPdf->SetXY(18.5, 253.5);
	$objPdf->MultiCell(167, 2.2, $sComments, 0);




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


		$sSQL = "SELECT defect, code,
						(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
				 FROM tbl_defect_codes dc
				 WHERE code='$sDefectCode' AND report_id='$iReportId'";

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