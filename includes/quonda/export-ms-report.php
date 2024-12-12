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

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/Marks-Spencer.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage( );
	$objPdf->useTemplate($iTemplateId, 0, 0);



	$sSQL = "SELECT *,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
	                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor
	         FROM tbl_qa_reports WHERE id='$Id'";
	$objDb->query($sSQL);

	$iReportId       = $objDb->getField(0, "report_id");
	$iVendor         = $objDb->getField(0, "vendor_id");
	$sVendor         = $objDb->getField(0, "_Vendor");
	$sAuditor        = $objDb->getField(0, "_Auditor");
	$iPo             = $objDb->getField(0, "po_id");
	$iAdditionalPos  = $objDb->getField(0, "additional_pos");
	$sPo             = $objDb->getField(0, "_Po");
	$iStyle          = $objDb->getField(0, "style_id");
	$sAuditCode      = $objDb->getField(0, "audit_code");
	$sAuditDate      = $objDb->getField(0, "audit_date");
	$sAuditStage     = $objDb->getField(0, "audit_stage");
	$sAuditResult    = $objDb->getField(0, "audit_result");
	$sCustomSample   = $objDb->getField(0, "custom_sample");
	$iTotalGmts      = $objDb->getField(0, "total_gmts");
	$iGmtsDefective  = $objDb->getField(0, "defective_gmts");
	$iMaxDefects     = $objDb->getField(0, "max_defects");
	$sColors         = $objDb->getField(0, "colors");
	$sDescription    = $objDb->getField(0, "description");
	$sBatchSize      = $objDb->getField(0, "batch_size");
	$fPackedPercent  = $objDb->getField(0, "packed_percent");
	$fCartonsShipped = $objDb->getField(0, "cartons_shipped");
	$sComments       = $objDb->getField(0, "qa_comments");
	$sSpecsSheet1    = $objDb->getField(0, 'specs_sheet_1');
	$sSpecsSheet2    = $objDb->getField(0, 'specs_sheet_2');
	$sSpecsSheet3    = $objDb->getField(0, 'specs_sheet_3');
	$sSpecsSheet4    = $objDb->getField(0, 'specs_sheet_4');
	$sSpecsSheet5    = $objDb->getField(0, 'specs_sheet_5');
	$sSpecsSheet6    = $objDb->getField(0, 'specs_sheet_6');
	$sSpecsSheet7    = $objDb->getField(0, 'specs_sheet_7');
	$sSpecsSheet8    = $objDb->getField(0, 'specs_sheet_8');
	$sSpecsSheet9    = $objDb->getField(0, 'specs_sheet_9');
	$sSpecsSheet10   = $objDb->getField(0, 'specs_sheet_10');
	$iLine           = $objDb->getField(0, "line_id");
	$fDhu            = $objDb->getField(0, "dhu");

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


	$sSQL = "SELECT customer_po_no FROM tbl_po WHERE id='$iPo'";
	$objDb->query($sSQL);

	$sCustomerNo = $objDb->getField(0, "customer_po_no");


	$sSQL = "SELECT cap_no FROM tbl_vendors WHERE id='$iVendor'";
	$objDb->query($sSQL);

	$sCapNo = $objDb->getField(0, "cap_no");


	$sSQL = "SELECT style, sub_brand_id, (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand FROM tbl_styles WHERE id='$iStyle'";
	$objDb->query($sSQL);

	$sStyle = $objDb->getField(0, "style");
	$iBrand = $objDb->getField(0, "sub_brand_id");
	$sBrand = $objDb->getField(0, "_Brand");


	$sSQL = "SELECT etd_required FROM tbl_po_colors WHERE po_id='$iPo' ORDER BY id LIMIT 1";
	$objDb->query($sSQL);

	$sEtdRequired = $objDb->getField(0, 'etd_required');


	$sSQL = "SELECT style FROM tbl_styles WHERE id=(SELECT style_id FROM tbl_po_colors WHERE po_id='$iPo' LIMIT 1)";
	$objDb->query($sSQL);

	$sStyle = $objDb->getField(0, 0);


	$sSQL = "SELECT * FROM tbl_ms_qa_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$sSeries        = $objDb->getField(0, 'series');
	$sDepartment    = $objDb->getField(0, 'department');
	$iBigProducts   = $objDb->getField(0, 'big_products');
	$sBigSize       = $objDb->getField(0, 'big_size');
	$iSmallProducts = $objDb->getField(0, 'small_products');
	$sSmallSize     = $objDb->getField(0, 'small_size');
	$sAction        = $objDb->getField(0, 'action');


	if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE && $sAuditStage == "Final")
	{
		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(50, 50, 50);

		$objPdf->Text(98, 16, "FIREWALL AUDIT");
	}


	$objPdf->SetFont('Arial', '', 4.5);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(33, 18.8, $sVendor);
	$objPdf->Text(33, 21.3, $sStyle);
	$objPdf->Text(33, 23.8, "");
	$objPdf->Text(33, 26.3, ($sPo.$sAdditionalPos));
	$objPdf->Text(33, 28.8, formatNumber($iQuantity, false));
	$objPdf->Text(33, 31.4, $sBatchSize);
	$objPdf->Text(33, 33.9, formatDate($sAuditDate));
	$objPdf->Text(33, 36.5, $sCapNo);

	$objPdf->Text(113, 18.8, $sSeries);
	$objPdf->Text(113, 21.3, $sDepartment);
	$objPdf->Text(113, 23.8, $sColors);
	$objPdf->Text(148, 18.8, $sVendor);
	$objPdf->Text(148, 21.3, $sDescription);
	$objPdf->Text(148, 23.8, "Pakistan");
	$objPdf->Text(135, 26.3, formatDate($sEtdRequired));
	$objPdf->Text(135, 28.8, $fPackedPercent);
	$objPdf->Text(113, 31.4, (($sCustomSample == "Y") ? "CUSTOM ({$iTotalGmts})" : $iTotalGmts));
	$objPdf->Text(148, 31.4, $fCartonsShipped);
	$objPdf->Text(135, 33.9, $sAuditor);




	$sCodes = array("101", "102", "103", "104", "105", "106", "123", "199",
					"201", "202", "203", "204", "205", "206", "207", "299",
					"301", "302", "303", "304", "305", "399",
					"401", "402", "403", "404", "405", "406", "407", "408", "412", "413", "414", "415", "416", "417", "499",
					"501", "502", "503", "504", "505", "506", "507", "508", "509", "510", "511", "512", "513", "514", "515", "530", "599",
					"601", "602", "603", "604", "605", "699");

	$iCritical = 0;
	$iMajor    = 0;
	$iMinor    = 0;

	for ($i = 0; $i < count($sCodes); $i ++)
	{
		$iCode = (int)getDbValue("id", "tbl_defect_codes", "report_id='$iReportId' AND code='{$sCodes[$i]}'");

		if ($iCode == 0)
			continue;


		$iCriticalDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "nature='2' AND audit_id='$Id' AND code_id='$iCode'");
		$iMajorDefects    = getDbValue("SUM(defects)", "tbl_qa_report_defects", "nature='1' AND audit_id='$Id' AND code_id='$iCode'");
		$iMinorDefects    = getDbValue("SUM(defects)", "tbl_qa_report_defects", "nature='0' AND audit_id='$Id' AND code_id='$iCode'");


		$sPosition = "";

		$sSQL = "SELECT area FROM tbl_defect_areas WHERE id IN (SELECT area_id FROM tbl_qa_report_defects WHERE audit_id='$Id' AND code_id='$iCode')";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($j = 0; $j < $iCount; $j ++)
		{
			if ($sPosition != "")
				$sPosition .= ", ";

			$sPosition .= $objDb->getField($j, 0);
		}


		if ($iCriticalDefects == 0 && $iMajorDefects == 0 && $iMinorDefects == 0)
			continue;


		$objPdf->Text(104, (49.5 + (2.5 * $i)), $iCriticalDefects);
		$objPdf->Text(116, (49.5 + (2.5 * $i)), $iMajorDefects);
		$objPdf->Text(126.5, (49.5 + (2.5 * $i)), $iMinorDefects);
		$objPdf->Text(135, (49.5 + (2.5 * $i)), $sPosition);

		$iCritical += $iCriticalDefects;
		$iMajor    += $iMajorDefects;
		$iMinor    += $iMinorDefects;
	}

	$i ++;

	$objPdf->Text(104, (49 + (2.5 * $i)), $iCritical);
	$objPdf->Text(116, (49 + (2.5 * $i)), $iMajor);
	$objPdf->Text(126.5, (49 + (2.5 * $i)), $iMinor);

	$i ++;

	$iAQL = array('13'  => array(1, 1),
	              '20'  => array(1, 2),
	              '32'  => array(2, 3),
	              '50'  => array(3, 5),
	              '80'  => array(5, 7),
	              '125' => array(7, 10),
	              '200' => array(10, 14),
	              '315' => array(14, 21),
	              '500' => array(21, 21) );

	$objPdf->Text(104, (49 + (2.5 * $i)), 0);
	$objPdf->Text(116, (49 + (2.5 * $i)), $iAQL[$iTotalGmts][0]);
	$objPdf->Text(126.5, (49 + (2.5 * $i)), $iAQL[$iTotalGmts][1]);


	$objPdf->Text(116, 213.2, $iBigProducts);
	$objPdf->Text(136, 213.2, $sBigSize);

	$objPdf->Text(116, 215.7, $iSmallProducts);
	$objPdf->Text(136, 215.7, $sSmallSize);


	$objPdf->SetXY(31, 237);
	$objPdf->MultiCell(168, 2, $sComments, 0);

	$objPdf->SetXY(31, 254);
	$objPdf->MultiCell(168, 2, $sAction, 0);


	$objPdf->Text(126, 224, ($iTotalGmts - ($iMajor + $iCritical)));
	$objPdf->Text(126, 226.3, ($iMajor + $iCritical));
	$objPdf->Text(126, 228.6, $iTotalGmts);
	$objPdf->Text(126, 233.5, (($sAuditResult == "P") ? 'Pass' : (($sAuditResult == "F") ? "Fail" : "Hold")));



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