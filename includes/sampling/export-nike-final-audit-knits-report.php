<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/nike-knits-final-audit-old.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "Legal");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);


	$sSQL = "SELECT *,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_inline_audits.vendor_id) AS _Vendor,
	                (SELECT name FROM tbl_users WHERE id=tbl_inline_audits.user_id) AS _Auditor
	         FROM tbl_inline_audits
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	$iReportId          = $objDb->getField(0, "report_id");
	$sVendor            = $objDb->getField(0, "_Vendor");
	$sAuditor           = $objDb->getField(0, "_Auditor");
	$iStyle             = $objDb->getField(0, "style_id");
	$sColors            = $objDb->getField(0, "colors");
	$sAuditCode         = $objDb->getField(0, "audit_code");
	$sAuditDate         = $objDb->getField(0, "audit_date");
	$sAuditResult       = $objDb->getField(0, "audit_result");
	$iTotalGmts         = $objDb->getField(0, "total_gmts");
	$iShipQty           = $objDb->getField(0, "ship_qty");
	$iGmtsDefective     = $objDb->getField(0, "defective_gmts");
	$fCartonsShipped    = $objDb->getField(0, "cartons_shipped");
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
	$sAuditStage        = "Final";



	$sSQL = "SELECT style, brand_id, (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand FROM tbl_styles WHERE id='$iStyle'";
	$objDb->query($sSQL);

	$sStyle = $objDb->getField(0, "style");
	$iBrand = $objDb->getField(0, "brand_id");
	$sBrand = $objDb->getField(0, "_Brand");



	$objPdf->Text(180, 47, formatDate($sAuditDate));

	$objPdf->Text(22, 52.3, "");
	$objPdf->Text(95, 52.3, $sAuditor);
	$objPdf->Text(180, 52.3, $sAuditCode);

	$objPdf->Text(180, 57.0, $sVendor);

	$objPdf->Text(84, 61.9, $sStyle);
	$objPdf->Text(180, 61.9, formatNumber($iShipQty, false));


	$sCodes = array("0", "106", "113", "114", "115", "108", "109", "111", "112", "199", "0",
					"0", "201", "202", "203", "204", "206", "208", "211", "216", "299", "0", "0",
					"0", "301", "305", "306", "390", "0",
					"0", "401", "402", "403", "410", "412", "415", "416", "499", "0",
					"0", "501", "502", "503", "599", "0",
					"0", "601", "603", "604", "605", "699",
					"0", "703", "704", "706", "799", "0",
					"0", "N804", "N805", "N806", "N807", "N808", "N809", "N810", "N811", "N812", "N814", "N816", "N818", "N820", "N822", "N825", "N826", "N827", "N829", "N832", "N834", "N835", "NK813", "NK811", "899");


	for ($i = 0; $i < count($sCodes); $i ++)
	{
		if ($sCodes[$i] == "0")
			continue;


		$iCode = (int)getDbValue("id", "tbl_sampling_defect_codes", "brand_id='$iBrand' AND report_id='$iReportId' AND code='{$sCodes[$i]}'");

		if ($iCode == 0)
			continue;


		$iDefects = getDbValue("SUM(defects)", "tbl_inline_audit_defects", "audit_id='$Id' AND code_id='$iCode'");

		if ($iDefects == 0)
			continue;


		$objPdf->Text( (($i <= 50) ? 77 : 190), (($i <= 50) ? (71.0 + (4.18 * $i)) : (67.2 + (4.18 * ($i - 50)))), $iDefects);
	}


	$iDefects = getDbValue("SUM(defects)", "tbl_inline_audit_defects", "audit_id='$Id'");

	if ($iGmtsDefective == 0)
		$iGmtsDefective = $iDefects;



	$objPdf->Text(30, 289, $fCartonsShipped);
	$objPdf->Text(87, 289, $iTotalGmts);

	if ($sAuditResult == "P")
		$objPdf->Text(141, 289, 'PASS');

	else if ($sAuditResult == "F")
		$objPdf->Text(141, 289, 'FAIL');

	else
		$objPdf->Text(141, 289, 'HOLD');


	$objPdf->Text(188, 285, formatNumber(($iTotalGmts - $iGmtsDefective), false));
	$objPdf->Text(188, 290, $iGmtsDefective);



	$objPdf->SetFont('Arial', '', 5.5);

	$objPdf->SetXY(10, 300);
	$objPdf->MultiCell(199, 2.2, $sComments, 0);



	if ($sSpecsSheet1 != "" && @file_exists($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet1))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet1), 10, 10, 190);
	}

	if ($sSpecsSheet2 != "" && @file_exists($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet2))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet2), 10, 10, 190);
	}

	if ($sSpecsSheet3 != "" && @file_exists($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet3))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet3), 10, 10, 190);
	}

	if ($sSpecsSheet4 != "" && @file_exists($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet4))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet4), 10, 10, 190);
	}

	if ($sSpecsSheet5 != "" && @file_exists($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet5))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet5), 10, 10, 190);
	}

	if ($sSpecsSheet6 != "" && @file_exists($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet6))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet6), 10, 10, 190);
	}

	if ($sSpecsSheet7 != "" && @file_exists($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet7))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet7), 10, 10, 190);
	}

	if ($sSpecsSheet8 != "" && @file_exists($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet8))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet8), 10, 10, 190);
	}

	if ($sSpecsSheet9 != "" && @file_exists($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet9))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet9), 10, 10, 190);
	}

	if ($sSpecsSheet10 != "" && @file_exists($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet10))
	{
		$objPdf->addPage( );
		$objPdf->Image(($sBaseDir.SAMPLING_SPECS_SHEETS_DIR.$sSpecsSheet10), 10, 10, 190);
	}


    @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

	$sPictures = @glob($sBaseDir.INLINE_AUDITS_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_001_*.*");
	$sPictures = @array_merge($sPictures, @glob($sBaseDir.INLINE_AUDITS_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*"));
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
						(SELECT type FROM tbl_sampling_defect_types WHERE id=dc.type_id) AS _Type
				 FROM tbl_sampling_defect_codes dc
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


		$objPdf->Image($sBaseDir.INLINE_AUDITS_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPictures[$i]), $iLeft, $iTop, 98);
	}



	$sPdfFile = ($sBaseDir.TEMP_DIR."M{$Id}-Nike-Knits-QA-Report.pdf");

	$objPdf->Output(@basename($sPdfFile), 'D');
?>