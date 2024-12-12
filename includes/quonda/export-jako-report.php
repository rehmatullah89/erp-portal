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

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/Jako.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage( );
	$objPdf->useTemplate($iTemplateId, 0, 0);

	$objPdf->SetAutoPageBreak(TRUE, 0);
	$objPdf->SetFont('Arial', '', 9);
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
	$sCustomSample  = $objDb->getField(0, "custom_sample");
	$iTotalGmts     = $objDb->getField(0, "total_gmts");
	$iMaxDefects    = $objDb->getField(0, "max_defects");
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


	$sSQL = "SELECT name FROM tbl_users WHERE id='$iAuditor'";
	$objDb->query($sSQL);

	$sAuditor = $objDb->getField(0, "name");


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

	$iParent = $objDb->getField(0, "brand_id");
	$sStyle  = $objDb->getField(0, "style");
	$iBrand  = $objDb->getField(0, "sub_brand_id");
	$sBrand  = $objDb->getField(0, "_Brand");


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


	$sSQL = "SELECT * FROM tbl_jako_packing WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$sCarton    = $objDb->getField(0, "carton");
	$sPolybag   = $objDb->getField(0, "polybag");
	$sPackage   = $objDb->getField(0, "package");
	$sHangTag   = $objDb->getField(0, "hangtag");
	$sSizeLabel = $objDb->getField(0, "size_label");
	$sCareLabel = $objDb->getField(0, "care_label");
	$sProdLabel = $objDb->getField(0, "prod_label");


	$sSQL = "SELECT * FROM tbl_jako_qa_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$sEta         = $objDb->getField(0, "eta");
	$sWe          = $objDb->getField(0, "we");
	$fWashOff     = $objDb->getField(0, "wash_off");
	$fWashIn      = $objDb->getField(0, "wash_in");
	$fMeasureOff  = $objDb->getField(0, "measure_off");
	$fMeasureIn   = $objDb->getField(0, "measure_in");
	$iPcsMeasured = $objDb->getField(0, "pcs_measured");



	$objPdf->Text(23, 29.5, $sStyle);
	$objPdf->Text(54, 29.5, ($sPo.$sAdditionalPos));
	$objPdf->Text(99, 29.5, $iQuantity);
	$objPdf->Text(147, 29.5, (($sCustomSample == "Y") ? "CUSTOM ({$iTotalGmts})" : $iTotalGmts));

	$objPdf->Text(20, 37, $sEta);
	$objPdf->Text(51, 37, $sWe);
	$objPdf->Text(98, 37, $sVendor);
	$objPdf->Text(130, 37, "Pakistan");
	$objPdf->Text(175, 37, $sAuditStage);


	if ($sCarton == "Y")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 48, 43, 4);

	else if ($sCarton == "N")
		$objPdf->Image(($sBaseDir.'images/icons/cross.gif'), 48, 43, 3);


	if ($sPolybag == "Y")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 72, 43, 4);

	else if ($sPolybag == "N")
		$objPdf->Image(($sBaseDir.'images/icons/cross.gif'), 71.5, 43, 3);


	if ($sPackage == "Y")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 94, 43, 4);

	else if ($sPackage == "N")
		$objPdf->Image(($sBaseDir.'images/icons/cross.gif'), 94, 43, 3);


	if ($sHangTag == "Y")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 116, 43, 4);

	else if ($sHangTag == "N")
		$objPdf->Image(($sBaseDir.'images/icons/cross.gif'), 116, 43, 3);


	if ($sSizeLabel == "Y")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 142, 43, 4);

	else if ($sSizeLabel == "N")
		$objPdf->Image(($sBaseDir.'images/icons/cross.gif'), 142, 43, 3);


	if ($sCareLabel == "Y")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 169, 43, 4);

	else if ($sCareLabel == "N")
		$objPdf->Image(($sBaseDir.'images/icons/cross.gif'), 169, 43, 3);


	if ($sProdLabel == "Y")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 196, 43, 4);

	else if ($sProdLabel == "N")
		$objPdf->Image(($sBaseDir.'images/icons/cross.gif'), 196.5, 43, 3);


	$objPdf->SetFont('Arial', '', 7);

	$sSQL = "SELECT * FROM tbl_jako_audits WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$objPdf->Text(9.8, (61.6 + (5 * $i)), $objDb->getField($i,  'style_color'));

		if ($objDb->getField($i,  'design') == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 61, (58.8 + (4.8 * $i)), 4);

		else if ($objDb->getField($i,  'design') == "N")
			$objPdf->Image(($sBaseDir.'images/icons/cross.gif'), 61, (58.8 + (4.8 * $i)), 3);


		if ($objDb->getField($i,  'main_fab') == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 86, (58.8 + (4.8 * $i)), 4);

		else if ($objDb->getField($i,  'main_fab') == "N")
			$objPdf->Image(($sBaseDir.'images/icons/cross.gif'), 86, (58.8 + (4.8 * $i)), 3);


		if ($objDb->getField($i,  'trims') == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 105.5, (58.8 + (4.8 * $i)), 4);

		else if ($objDb->getField($i,  'trims') == "N")
			$objPdf->Image(($sBaseDir.'images/icons/cross.gif'), 105.5, (58.8 + (4.8 * $i)), 3);


		if ($objDb->getField($i,  'access') == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 128, (58.8 + (4.8 * $i)), 4);

		else if ($objDb->getField($i,  'access') == "N")
			$objPdf->Image(($sBaseDir.'images/icons/cross.gif'), 128, (58.8 + (4.8 * $i)), 3);


		if ($objDb->getField($i,  'logos') == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 148, (58.8 + (4.8 * $i)), 4);

		else if ($objDb->getField($i,  'logos') == "N")
			$objPdf->Image(($sBaseDir.'images/icons/cross.gif'), 148, (58.8 + (4.8 * $i)), 3);


		if ($objDb->getField($i,  'color') == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 168.5, (58.8 + (4.8 * $i)), 4);

		else if ($objDb->getField($i,  'color') == "N")
			$objPdf->Image(($sBaseDir.'images/icons/cross.gif'), 168.5, (58.8 + (4.8 * $i)), 3);


		if ($objDb->getField($i,  'tuv_test') == "Y")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 192.5, (58.8 + (4.8 * $i)), 4);

		else if ($objDb->getField($i,  'tuv_test') == "N")
			$objPdf->Image(($sBaseDir.'images/icons/cross.gif'), 192.5, (58.8 + (4.8 * $i)), 3);
	}


	$objPdf->SetFont('Arial', '', 9);


	$iCodes = array(676, 677, 678, 679, 680, 681,
	                682, 683,
	                684, 695, 685,
	                701,
	                708, 709, 710,
	                686, 687, 688, 689, 690, 691, 692, 711, 693, 702, 694,
	                703, 696, 697, 698, 699, 700,
	                704, 705, 706, 707);
	$iMajor = 0;
	$iMinor = 0;

	for ($i = 0; $i < count($iCodes); $i ++)
	{
		$iMajorDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "nature='1' AND audit_id='$Id' AND code_id='{$iCodes[$i]}'");
		$iMinorDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "nature='0' AND audit_id='$Id' AND code_id='{$iCodes[$i]}'");

		if ($iMajorDefects == 0 && $iMinorDefects == 0)
			continue;


		$objPdf->Text(155, (95.6 + (3.872 * $i)), $iMajorDefects);
		$objPdf->Text(184, (95.6 + (3.872 * $i)), $iMinorDefects);

		$iMajor += $iMajorDefects;
		$iMinor += $iMinorDefects;
	}


	$objPdf->Text(27, (95.5 + (3.872 * $i)), (($sCustomSample == "Y") ? "CUSTOM" : $iTotalGmts));
	$objPdf->Text(90, (95.5 + (3.872 * $i)), $iMaxDefects);
	$objPdf->Text(155, (95.5 + (3.872 * $i)), $iMajor);
	$objPdf->Text(184, (95.5 + (3.872 * $i)), $iMinor);


	$objPdf->Text(50, 242, $fWashOff);
	$objPdf->Text(85.8, 242, $fWashIn);

	$objPdf->Text(50, 250, $fMeasureOff);
	$objPdf->Text(78.2, 250, $fMeasureIn);
	$objPdf->Text(113.5, 250, $iPcsMeasured);

	$objPdf->Text(148, 250, $sAuditResult);

	$objPdf->Text(36.5, 259, formatDate($sAuditDate));
	$objPdf->Text(90, 259, $sAuditor);

	$objPdf->SetXY(9, 269);
	$objPdf->MultiCell(190, 3.5, $sComments);



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