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

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/Old-Jako.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage( );
	$objPdf->useTemplate($iTemplateId, 0, 0);

	$objPdf->SetFont('Arial', '', 11);
	$objPdf->SetTextColor(50, 50, 50);


	$sSQL = "SELECT *,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor
	         FROM tbl_qa_reports WHERE id='$Id'";
	$objDb->query($sSQL);

	$iReportId      = $objDb->getField(0, "report_id");
	$sVendor        = $objDb->getField(0, "_Vendor");
	$iAuditor       = $objDb->getField(0, "user_id");
	$iPo            = $objDb->getField(0, "po_id");
	$iAdditionalPos = $objDb->getField(0, "additional_pos");
	$sPo            = $objDb->getField(0, "_Po");
	$iStyle         = $objDb->getField(0, "style_id");
	$sAuditCode     = $objDb->getField(0, "audit_code");
	$sAuditDate     = $objDb->getField(0, "audit_date");
	$sAuditStage    = $objDb->getField(0, "audit_stage");
	$sAuditResult   = $objDb->getField(0, "audit_result");
	$fStandard      = $objDb->getField(0, "standard");
	$sCustomSample  = $objDb->getField(0, "custom_sample");
	$iTotalGmts     = $objDb->getField(0, "total_gmts");
	$sColors        = $objDb->getField(0, "colors");
	$sShippingMark  = $objDb->getField(0, "shipping_mark");
	$sComments      = $objDb->getField(0, "qa_comments");
	$sSpecsSheet1   = $objDb->getField(0, 'specs_sheet_1');
	$sSpecsSheet2   = $objDb->getField(0, 'specs_sheet_2');
	$sSpecsSheet3   = $objDb->getField(0, 'specs_sheet_3');
	$sSpecsSheet4   = $objDb->getField(0, 'specs_sheet_4');
	$sSpecsSheet5   = $objDb->getField(0, 'specs_sheet_5');
	$sSpecsSheet6   = $objDb->getField(0, 'specs_sheet_6');
	$sSpecsSheet7   = $objDb->getField(0, 'specs_sheet_7');
	$sSpecsSheet8   = $objDb->getField(0, 'specs_sheet_8');
	$sSpecsSheet9   = $objDb->getField(0, 'specs_sheet_9');
	$sSpecsSheet10  = $objDb->getField(0, 'specs_sheet_10');
	$iLine          = $objDb->getField(0, "line_id");
	$fDhu           = $objDb->getField(0, "dhu");

	$sAuditStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");


	$sSQL = "SELECT name, picture FROM tbl_users WHERE id='$iAuditor'";
	$objDb->query($sSQL);

	$sAuditor = $objDb->getField(0, "name");
	$sPicture = $objDb->getField(0, "picture");


	$sAdditionalPos = "";

	$sSQL = "SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id IN ($iAdditionalPos) ORDER BY order_no";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sAdditionalPos .= (",".$objDb->getField($i, 0));


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


	$sSQL = "SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id='$iPo'";
	$objDb->query($sSQL);

	$iQuantity = $objDb->getField(0, 0);



	$objPdf->Text(43, 38, $sAuditStage);
	$objPdf->Text(43, 43.5, '');
	$objPdf->Text(43, 49, $sVendor);
	$objPdf->Text(43, 61, $sColors);

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(43, 66, ($sPo.$sAdditionalPos));

	$objPdf->SetFont('Arial', '', 11);
	$objPdf->Text(43, 72, ($iQuantity.' pcs'));
	$objPdf->Text(43, 78, $sCustomerNo);

	$objPdf->Text(130, 38, "S{$Id}");
	$objPdf->Text(145, 43.5, $sAuditor);
	$objPdf->Text(168, 49, formatDate($sEtdRequired));

	$objPdf->Text(37, 90, getDbValue("aql", "tbl_brands", "id='$iParent'"));
	$objPdf->Text(93, 90, (($sCustomSample == "Y") ? "CUSTOM ({$iTotalGmts})" : $iTotalGmts));
	//$objPdf->Text(149, 90, $sAccepted[$iTotalGmts]);
	$objPdf->Text(180, 90, $iGmtsDefective);


	$objPdf->Text((($sShippingMark == 'Y') ? 180 : 192), 108, (($sShippingMark == 'Y') ? 'Y' : 'N'));


	$iTypes = array(1, 2, 3, 4, 5, 6, 7, 24);
	$iTotal = 0;

	for ($i = 0; $i < count($iTypes); $i ++)
	{
		$sSQL = "SELECT defects,
		                (SELECT buyer_code FROM tbl_defect_codes WHERE id=tbl_qa_report_defects.code_id) AS _Code
		         FROM tbl_qa_report_defects
		         WHERE audit_id='$Id' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE type_id='{$iTypes[$i]}') ORDER BY id";
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
			$objPdf->Text(63, (145 + (5.5 * $i)), $sDefects);
			$objPdf->Text(187, (145 + (5.5 * $i)), $iSubTotal);
		}
	}


	$objPdf->Text(190, 190.5, $iTotal);

	$objPdf->Text(73, 196, $iShipQty);


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(52, 201.5, "{$fCartonsShipped}    Inspected Cartons:{$iTotalCartons}    Rejected Cartons:{$iCartonsRejected}      % Defective:{$fPercentDecfective}     DHU:".@round(( ($fCartonsRejected / $fTotalCartons) * 100), 2)."%");


	$objPdf->SetFont('Arial', '', 11);
	$objPdf->SetXY(14, 209.5);
	$objPdf->MultiCell(188, 5.4, $sComments);

	if ($sAuditResult == "P")
		$objPdf->Text(117, 241, 'Yes');

	else
		$objPdf->Text(175, 241, 'Yes');


	$objPdf->Text(66, 262, formatDate($sAuditDate));
	$objPdf->Text(168, 262, formatDate($sAuditDate));


	if ($sPicture != "" && @file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
		$objPdf->Image(($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture), 139, 248, 25);



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