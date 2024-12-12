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

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/greige-fabric-inspection-report.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "Letter");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);


	$sSQL = "SELECT * FROM tbl_qa_reports WHERE id='$Id'";
	$objDb->query($sSQL);

	$sAuditCode             = $objDb->getField(0, "audit_code");
	$iReportId              = $objDb->getField(0, "report_id");
	$iVendor                = $objDb->getField(0, "vendor_id");
	$iPo                    = $objDb->getField(0, "po_id");
	$iStyle                 = $objDb->getField(0, "style_id");
	$sAuditStage            = $objDb->getField(0, "audit_stage");
	$sAuditResult           = $objDb->getField(0, "audit_result");
	$sAuditDate             = $objDb->getField(0, "audit_date");
	$sAuditStartTime        = $objDb->getField(0, "start_time");
	$sAuditEndTime          = $objDb->getField(0, "end_time");
	$sDyeLotNo              = $objDb->getField(0, "dye_lot_no");
	$sAcceptablePointsWoven = $objDb->getField(0, "acceptable_points_woven");
	$sInspectionType        = $objDb->getField(0, "inspection_type");
	$sCutableFabricWidth    = $objDb->getField(0, "cutable_fabric_width");
	$sStockStatus           = $objDb->getField(0, "stock_status");
	$iRollsInspected        = $objDb->getField(0, "rolls_inspected");
	$iRolls                 = $objDb->getField(0, "no_of_rolls");
	$iFabricWidth           = $objDb->getField(0, "fabric_width");
	$iShipQty               = $objDb->getField(0, "ship_qty");
	$sComments              = $objDb->getField(0, "qa_comments");
	$sSpecsSheet1           = $objDb->getField(0, 'specs_sheet_1');
	$sSpecsSheet2           = $objDb->getField(0, 'specs_sheet_2');
	$sSpecsSheet3           = $objDb->getField(0, 'specs_sheet_3');
	$sSpecsSheet4       	= $objDb->getField(0, 'specs_sheet_4');
	$sSpecsSheet5       	= $objDb->getField(0, 'specs_sheet_5');
	$sSpecsSheet6       	= $objDb->getField(0, 'specs_sheet_6');
	$sSpecsSheet7       	= $objDb->getField(0, 'specs_sheet_7');
	$sSpecsSheet8       	= $objDb->getField(0, 'specs_sheet_8');
	$sSpecsSheet9       	= $objDb->getField(0, 'specs_sheet_9');
	$sSpecsSheet10      	= $objDb->getField(0, 'specs_sheet_10');
	$iLine                  = $objDb->getField(0, "line_id");


	$sAuditStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");


	$sSQL = "SELECT order_no, note, brand_id FROM tbl_po WHERE id='$iPo'";
	$objDb->query($sSQL);

	$sPo    = $objDb->getField(0, 0);
	$sNote  = $objDb->getField(0, 1);
	$iBrand = $objDb->getField(0, 2);


	$sSQL = "SELECT vendor FROM tbl_vendors WHERE id='$iVendor'";
	$objDb->query($sSQL);

	$sVendor = $objDb->getField(0, 0);


	$sSQL = "SELECT color FROM tbl_po_colors WHERE po_id='$iPo' AND style_id='$iStyle' LIMIT 1";
	$objDb->query($sSQL);

	$sColor = $objDb->getField(0, 0);


	$sSQL = "SELECT style, sub_brand_id, (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand FROM tbl_styles WHERE id='$iStyle'";
	$objDb->query($sSQL);

	$sStyle = $objDb->getField(0, "style");
	$iBrand = $objDb->getField(0, "sub_brand_id");
	$sBrand = $objDb->getField(0, "_Brand");


	$sSQL = "SELECT SUM((given_1 + given_2 + given_3)) FROM tbl_gf_rolls_info WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$iGivenQty = $objDb->getField(0, 0);


	$objPdf->Text(36, 23.5, $sVendor);
	$objPdf->Text(36, 28.5, $sStyle);
	$objPdf->Text(36, 33.5, $sPo);
	$objPdf->Text(36, 38.9, $sDyeLotNo);
	$objPdf->Text(56, 49.3, $sAcceptablePointsWoven);
	$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sInspectionType == "G") ? 52 : 68), 51.0, 4);
	$objPdf->Text(40, 59.8, formatTime($sAuditStartTime));

	$objPdf->Text(102, 23.5, $sBrand);
	$objPdf->Text(102, 28.5, $sNote);

	if (strlen($sColor) <= 35)
		$objPdf->Text(95, 33.5, $sColor);

	else
	{
		$objPdf->SetFont('Arial', '', 5.5);
		$objPdf->SetXY(94, 30.2);
		$objPdf->MultiCell(40, 2.2, $sColor, 0);

		$objPdf->SetFont('Arial', '', 7);
	}

	$objPdf->Text(115, 38.9, $iFabricWidth);
	$objPdf->Text(115, 44.0, $sCutableFabricWidth);
	$objPdf->Text(102, 54.4, $sStockStatus);
	$objPdf->Text(102, 59.8, formatTime($sAuditEndTime));

	$objPdf->Text(163, 23.5, formatDate($sAuditDate));
	$objPdf->Text(163, 33.5, formatNumber($iShipQty, false));
	$objPdf->Text(164, 39.0, $iRolls);
	$objPdf->Text(164, 44.0, $sAuditStage);
	$objPdf->Text(164, 49.5, $iGivenQty);
	$objPdf->Text(164, 54.4, $iRollsInspected);

	$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), (($sAuditResult == "P") ? 158 : (($sAuditResult == "F") ? 171 : 185)), 61.0, 4);



	$sSQL = "SELECT * FROM tbl_gf_inspection_checklist WHERE audit_id='$Id'";
	$objDb->query($sSQL);


	if ($objDb->getField(0, "color_match") == "A")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 68, 70.5, 4);

	else if ($objDb->getField(0, "color_match") == "R")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 92, 70.5, 4);

	$objPdf->Text(118, 73.5, $objDb->getField(0, "color_match_remarks"));


	if ($objDb->getField(0, "shading") == "A")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 68, 75.8, 4);

	else if ($objDb->getField(0, "shading") == "R")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 92, 75.8, 4);

	$objPdf->Text(118, 78.8, $objDb->getField(0, "shading_remarks"));


	if ($objDb->getField(0, "hand_feel") == "A")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 68, 81.1, 4);

	else if ($objDb->getField(0, "hand_feel") == "R")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 92, 81.1, 4);

	$objPdf->Text(118, 84.1, $objDb->getField(0, "hand_feel_remarks"));


	if ($objDb->getField(0, "lab_testing") == "A")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 68, 85.4, 4);

	else if ($objDb->getField(0, "lab_testing") == "R")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 92, 85.4, 4);

	$objPdf->Text(118, 89.4, $objDb->getField(0, "lab_testing_remarks"));



	$sSQL = "SELECT (given_1 + given_2 + given_3), (actual_1 + actual_2 + actual_3) FROM tbl_gf_rolls_info WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$iTotalGivenQty  = 0;
	$iTotalActualQty = 0;
	$iTotalPoints    = 0;
	$iTotalDefects   = 0;
	$iDefects        = array( );
	$iTypes          = array(18, 19, 20, 21, 22, 23);
	$iPositions      = array(67.5, 79.5, 92.5, 110, 127, 147);

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iGivenQty  = (int)$objDb->getField($i, 0);
		$iActualQty = (int)$objDb->getField($i, 1);

		$objPdf->Text(30, (129 + ($i * 6.4)), $iGivenQty);
		$objPdf->Text(44, (129 + ($i * 6.4)), $iActualQty);
		$objPdf->Text(58, (129 + ($i * 6.4)), ($iGivenQty - $iActualQty));


		$iRollPoints  = 0;
		$iRollDefects = 0;
		$iRoll        = ($i + 1);

		for ($j = 0; $j < count($iTypes); $j ++)
		{
			$sSQL = "SELECT SUM((defects * grade)), SUM(defects) FROM tbl_gf_report_defects WHERE audit_id='$Id' AND roll='$iRoll' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND type_id='{$iTypes[$j]}')";
			$objDb3->query($sSQL);

			$objPdf->Text($iPositions[$j], (129 + ($i * 6.4)), $objDb3->getField(0, 0));


			$iRollPoints  += $objDb3->getField(0, 0);
			$iRollDefects += $objDb3->getField(0, 1);

			$iDefects[$j]  += $objDb3->getField(0, 0);
			$iTotalPoints  += $objDb3->getField(0, 0);
			$iTotalDefects += $objDb3->getField(0, 1);
		}

/*
		if ($iBrand == 77)
			$fDhu = @round((($iTotalDefects * 39.37 * 100) / $iGivenQty / $iFabricWidth), 2);

		else
*/
			$fDhu = @round(((($iRollPoints * 3600) / $iGivenQty) / $iFabricWidth), 2);


		$objPdf->Text(170, (129 + ($i * 6.4)), $iRollPoints);
		$objPdf->Text(183, (129 + ($i * 6.4)), formatNumber($fDhu, 2));

		$iTotalGivenQty  += $iGivenQty;
		$iTotalActualQty += $iActualQty;
	}

/*
	if ($iBrand == 77)
		$fDhu = @round((($iTotalDefects * 39.37 * 100) / $iTotalGivenQty / $iFabricWidth), 2);

	else
*/
		$fDhu = @round(((($iTotalPoints * 3600) / $iTotalGivenQty) / $iFabricWidth), 2);



	$objPdf->Text(30, (129 + (5 * 6.4)), $iTotalGivenQty);
	$objPdf->Text(44, (129 + (5 * 6.4)), $iTotalActualQty);
	$objPdf->Text(58, (129 + (5 * 6.4)), ($iTotalGivenQty - $iTotalActualQty));

	for ($i = 0; $i < count($iDefects); $i ++)
		$objPdf->Text($iPositions[$i], (129 + (5 * 6.4)), $iDefects[$i]);

	$objPdf->Text(170, (129 + (5 * 6.4)), $iTotalPoints);
	$objPdf->Text(183, (129 + (5 * 6.4)), formatNumber($fDhu, 2));

	$objPdf->Text(172, 59.8, formatNumber($fDhu, 2));


	$objPdf->SetXY(17.0, 182.0);
	$objPdf->MultiCell(172, 3, $sComments, 0);



	////////// Page 2

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/greige-fabric-inspection-report-2.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "Letter");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	$objPdf->SetFont('Arial', '', 6);

	$objPdf->Text(68, 21.7, $sVendor);
	$objPdf->Text(99, 21.7, $sPo);
	$objPdf->Text(183, 21.7, formatDate($sAuditDate));


	$sSQL = "SELECT * FROM tbl_gf_rolls_info WHERE audit_id='$Id' ORDER BY id LIMIT 1";
	$objDb->query($sSQL);

	$fGiven1  = $objDb->getField(0,  'given_1');
	$fActual1 = $objDb->getField(0,  'actual_1');


	$objPdf->Text(20, 31.5, $objDb->getField(0,  'roll_no'));

	$objPdf->Text(69, 31.5, $objDb->getField(0,  'ref_1'));
	$objPdf->Text(79, 31.5, $fGiven1);
	$objPdf->Text(86, 31.5, $fActual1);
	$objPdf->Text(93, 31.5, ($fActual1 - $fGiven1));

	$objPdf->Text(101, 31.5, $objDb->getField(0,  'ref_2'));
	$objPdf->Text(110, 31.5, $fGiven2);
	$objPdf->Text(118, 31.5, $fActual2);
	$objPdf->Text(127, 31.5, ($fActual2 - $fGiven2));

	$objPdf->Text(137, 31.5, $objDb->getField(0,  'ref_3'));
	$objPdf->Text(151.5, 31.5, $fGiven3);
	$objPdf->Text(159, 31.5, $fActual3);
	$objPdf->Text(166, 31.5, ($fActual3 - $fGiven3));

	$objPdf->Text(175, 31.5, ($fGiven1 + $fGiven2 + $fGiven3));
	$objPdf->Text(185, 31.5, ($fActual1 + $fActual2 + $fActual3));
	$objPdf->Text(193, 31.5, (($fActual1 + $fActual2 + $fActual3) - ($fGiven1 + $fGiven2 + $fGiven3)));



	$sCodes = array("101", "102", "103", "104", "105", "106", "107", "108", "109", "110", "111", "112", "199", "0",
					"201", "202", "203", "299", "0",
					"301", "302", "303", "304", "305", "306", "399", "0",
					"401", "402", "403", "404", "405", "406", "407", "408", "409", "410", "411", "499", "0",
					"501", "502", "503", "599", "0",
					"601", "602", "603", "604", "699", "0");


	$iPositions = array( );

	$iPositions[1][1] = 0;
	$iPositions[1][2] = 1;
	$iPositions[1][3] = 2;
	$iPositions[1][4] = 3;
	$iPositions[1][0] = 4;

	$iPositions[2][1] = 5;
	$iPositions[2][2] = 6;
	$iPositions[2][3] = 7;
	$iPositions[2][4] = 8;
	$iPositions[2][0] = 9;

	$iPositions[3][1] = 10;
	$iPositions[3][2] = 11;
	$iPositions[3][3] = 12;
	$iPositions[3][4] = 13;
	$iPositions[3][0] = 14;

	$iPositions[4][1] = 10;
	$iPositions[4][2] = 11;
	$iPositions[4][3] = 12;
	$iPositions[4][4] = 13;
	$iPositions[4][0] = 14;

	$iPositions[5][1] = 10;
	$iPositions[5][2] = 11;
	$iPositions[5][3] = 12;
	$iPositions[5][4] = 13;
	$iPositions[5][0] = 14;



	$iAllDefects = array( );
	$iCatDefects = array( );
	$iCodDefects = array( );

	for ($i = 0; $i < count($sCodes); $i ++)
	{
		if ($sCodes[$i] == "0")
		{
			$objPdf->Text(68, (42.3 + (3.44 * $i)), $iCatDefects[0]);
			$objPdf->Text(73.5, (42.3 + (3.44 * $i)), $iCatDefects[1]);
			$objPdf->Text(79.5, (42.3 + (3.44 * $i)), $iCatDefects[2]);
			$objPdf->Text(86, (42.3 + (3.44 * $i)), $iCatDefects[3]);
			$objPdf->Text(93, (42.3 + (3.44 * $i)), $iCatDefects[4]);

			$objPdf->Text(99.5, (42.3 + (3.44 * $i)), $iCatDefects[5]);
			$objPdf->Text(105, (42.3 + (3.44 * $i)), $iCatDefects[6]);
			$objPdf->Text(110, (42.3 + (3.44 * $i)), $iCatDefects[7]);
			$objPdf->Text(118, (42.3 + (3.44 * $i)), $iCatDefects[8]);
			$objPdf->Text(127, (42.3 + (3.44 * $i)), $iCatDefects[9]);

			$objPdf->Text(135, (42.3 + (3.44 * $i)), $iCatDefects[10]);
			$objPdf->Text(144, (42.3 + (3.44 * $i)), $iCatDefects[11]);
			$objPdf->Text(151.5, (42.3 + (3.44 * $i)), $iCatDefects[12]);
			$objPdf->Text(159, (42.3 + (3.44 * $i)), $iCatDefects[13]);
			$objPdf->Text(166, (42.3 + (3.44 * $i)), $iCatDefects[14]);

			$iCatDefects = array( );

			continue;
		}


		$iCode = (int)getDbValue("id", "tbl_defect_codes", "report_id='$iReportId' AND code='{$sCodes[$i]}'");

		if ($iCode == 0)
			continue;



		$sSQL = "SELECT SUM(defects), grade, panel FROM tbl_gf_report_defects WHERE audit_id='$Id' AND code_id='$iCode'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount == 0)
			continue;


		$iCodDefects = array( );

		for ($j = 0; $j < $iCount; $j ++)
		{
			$iDefects = $objDb->getField($j, 0);
			$iGrade   = $objDb->getField($j, 1);
			$iPanel   = $objDb->getField($j, 2);


			$iCodDefects[$iPositions[$iPanel][$iGrade]] += $iDefects;
			$iCatDefects[$iPositions[$iPanel][$iGrade]] += $iDefects;
			$iAllDefects[$iPositions[$iPanel][$iGrade]] += $iDefects;

			$iCodDefects[$iPositions[$iPanel][0]] += $iDefects;
			$iCatDefects[$iPositions[$iPanel][0]] += $iDefects;
			$iAllDefects[$iPositions[$iPanel][0]] += $iDefects;
		}


		$objPdf->Text(68, (42.3 + (3.44 * $i)), $iCodDefects[0]);
		$objPdf->Text(73.5, (42.3 + (3.44 * $i)), $iCodDefects[1]);
		$objPdf->Text(79.5, (42.3 + (3.44 * $i)), $iCodDefects[2]);
		$objPdf->Text(86, (42.3 + (3.44 * $i)), $iCodDefects[3]);
		$objPdf->Text(93, (42.3 + (3.44 * $i)), $iCodDefects[4]);

		$objPdf->Text(99.5, (42.3 + (3.44 * $i)), $iCodDefects[5]);
		$objPdf->Text(105, (42.3 + (3.44 * $i)), $iCodDefects[6]);
		$objPdf->Text(110, (42.3 + (3.44 * $i)), $iCodDefects[7]);
		$objPdf->Text(118, (42.3 + (3.44 * $i)), $iCodDefects[8]);
		$objPdf->Text(127, (42.3 + (3.44 * $i)), $iCodDefects[9]);

		$objPdf->Text(135, (42.3 + (3.44 * $i)), $iCodDefects[10]);
		$objPdf->Text(144, (42.3 + (3.44 * $i)), $iCodDefects[11]);
		$objPdf->Text(151.5, (42.3 + (3.44 * $i)), $iCodDefects[12]);
		$objPdf->Text(159, (42.3 + (3.44 * $i)), $iCodDefects[13]);
		$objPdf->Text(166, (42.3 + (3.44 * $i)), $iCodDefects[14]);
	}


	$objPdf->Text(67.5, (42.3 + (3.44 * $i)), $iAllDefects[0]);
	$objPdf->Text(73, (42.3 + (3.44 * $i)), $iAllDefects[1]);
	$objPdf->Text(79, (42.3 + (3.44 * $i)), $iAllDefects[2]);
	$objPdf->Text(85.5, (42.3 + (3.44 * $i)), $iAllDefects[3]);
	$objPdf->Text(92.5, (42.3 + (3.44 * $i)), $iAllDefects[4]);

	$objPdf->Text(99, (42.3 + (3.44 * $i)), $iAllDefects[5]);
	$objPdf->Text(104.5, (42.3 + (3.44 * $i)), $iAllDefects[6]);
	$objPdf->Text(109.5, (42.3 + (3.44 * $i)), $iAllDefects[7]);
	$objPdf->Text(117.5, (42.3 + (3.44 * $i)), $iAllDefects[8]);
	$objPdf->Text(126.5, (42.3 + (3.44 * $i)), $iAllDefects[9]);

	$objPdf->Text(134.5, (42.3 + (3.44 * $i)), $iAllDefects[10]);
	$objPdf->Text(143.5, (42.3 + (3.44 * $i)), $iAllDefects[11]);
	$objPdf->Text(151, (42.3 + (3.44 * $i)), $iAllDefects[12]);
	$objPdf->Text(158.5, (42.3 + (3.44 * $i)), $iAllDefects[13]);
	$objPdf->Text(165.5, (42.3 + (3.44 * $i)), $iAllDefects[14]);

	$i ++;

	if ($iAllDefects[0] > 0)
		$objPdf->Text(67.5, (42.3 + (3.44 * $i)), ($iAllDefects[0] * 1));

	if ($iAllDefects[1] > 0)
		$objPdf->Text(73, (42.3 + (3.44 * $i)), ($iAllDefects[1] * 2));

	if ($iAllDefects[2] > 0)
		$objPdf->Text(79, (42.3 + (3.44 * $i)), ($iAllDefects[2] * 3));

	if ($iAllDefects[3] > 0)
		$objPdf->Text(85.5, (42.3 + (3.44 * $i)), ($iAllDefects[3] * 4));

	if ($iAllDefects[1] > 0 || $iAllDefects[2] > 0 || $iAllDefects[3] > 0 || $iAllDefects[4] > 0)
		$objPdf->Text(92.5, (42.3 + (3.44 * $i)), ( ($iAllDefects[0] * 1) + ($iAllDefects[1] * 2) + ($iAllDefects[2] * 3) + ($iAllDefects[3] * 4)) );


	if ($iAllDefects[5] > 0)
		$objPdf->Text(99, (42.3 + (3.44 * $i)), ($iAllDefects[5] * 1));

	if ($iAllDefects[6] > 0)
		$objPdf->Text(104.5, (42.3 + (3.44 * $i)), ($iAllDefects[6] * 2));

	if ($iAllDefects[7] > 0)
		$objPdf->Text(109.5, (42.3 + (3.44 * $i)), ($iAllDefects[7] * 3));

	if ($iAllDefects[8] > 0)
		$objPdf->Text(117.5, (42.3 + (3.44 * $i)), ($iAllDefects[8] * 4));

	if ($iAllDefects[5] > 0 || $iAllDefects[6] > 0 || $iAllDefects[7] > 0 || $iAllDefects[8] > 0)
		$objPdf->Text(126.5, (42.3 + (3.44 * $i)), ( ($iAllDefects[5] * 1) + ($iAllDefects[6] * 2) + ($iAllDefects[7] * 3) + ($iAllDefects[8] * 4)) );


	if ($iAllDefects[10] > 0)
		$objPdf->Text(134.5, (42.3 + (3.44 * $i)), ($iAllDefects[10] * 1));

	if ($iAllDefects[11] > 0)
		$objPdf->Text(143.5, (42.3 + (3.44 * $i)), ($iAllDefects[11] * 2));

	if ($iAllDefects[12] > 0)
		$objPdf->Text(151, (42.3 + (3.44 * $i)), ($iAllDefects[12] * 3));

	if ($iAllDefects[13] > 0)
		$objPdf->Text(158.5, (42.3 + (3.44 * $i)), ($iAllDefects[13] * 4));

	if ($iAllDefects[10] > 0 || $iAllDefects[11] > 0 || $iAllDefects[12] > 0 || $iAllDefects[13] > 0)
		$objPdf->Text(165.5, (42.3 + (3.44 * $i)), ( ($iAllDefects[10] * 1) + ($iAllDefects[11] * 2) + ($iAllDefects[12] * 3) + ($iAllDefects[13] * 4)) );



	$i ++;

	$iDefects1 = ($iAllDefects[0] + $iAllDefects[1] + $iAllDefects[2] + $iAllDefects[3]);
	$iDefects2 = ($iAllDefects[5] + $iAllDefects[6] + $iAllDefects[7] + $iAllDefects[8]);
	$iDefects3 = ($iAllDefects[10] + $iAllDefects[11] + $iAllDefects[12] + $iAllDefects[13]);

	$iPoints1 = (($iAllDefects[0] * 1) + ($iAllDefects[1] * 2) + ($iAllDefects[2] * 3) + ($iAllDefects[3] * 4));
	$iPoints2 = (($iAllDefects[5] * 1) + ($iAllDefects[6] * 2) + ($iAllDefects[7] * 3) + ($iAllDefects[8] * 4));
	$iPoints3 = (($iAllDefects[10] * 1) + ($iAllDefects[11] * 2) + ($iAllDefects[12] * 3) + ($iAllDefects[13] * 4));


	if ($iPoints1 > 0)
		$objPdf->Text(79, (42.3 + (3.44 * $i)), $iPoints1);

	if ($iPoints2 > 0)
		$objPdf->Text(109.5, (42.3 + (3.44 * $i)), $iPoints2);

	if ($iPoints3 > 0)
		$objPdf->Text(151, (42.3 + (3.44 * $i)), $iPoints3);


	$i ++;


	if ($fActual1 > 0)
		$objPdf->Text(79, (42.6 + (3.44 * $i)), $fActual1);

	if ($fActual2 > 0)
		$objPdf->Text(109.5, (42.6 + (3.44 * $i)), $fActual2);

	if ($fActual3 > 0)
		$objPdf->Text(151, (42.6 + (3.44 * $i)), $fActual3);


	$i ++;

/*
	if ($iBrand == 77)
	{
		if ($iPoints1 > 0)
			$objPdf->Text(79, (42.7 + (3.44 * $i)), formatNumber(($iDefects1 *  39.37 * 100) / $fGiven1 / $iFabricWidth) );

		if ($iPoints2 > 0)
			$objPdf->Text(109.5, (42.7 + (3.44 * $i)), formatNumber(($iDefects2 * 39.37 * 100) / $fGiven2 / $iFabricWidth) );

		if ($iPoints3 > 0)
			$objPdf->Text(151, (42.7 + (3.44 * $i)), formatNumber(($iDefects3 * 39.37 * 100) / $fGiven3 / $iFabricWidth) );
	}

	else
*/
	{
		if ($iPoints1 > 0)
			$objPdf->Text(79, (42.7 + (3.44 * $i)), formatNumber((($iPoints1*3600)/$fActual1)/$iFabricWidth) );

		if ($iPoints2 > 0)
			$objPdf->Text(109.5, (42.7 + (3.44 * $i)), formatNumber((($iPoints2*3600)/$fActual2)/$iFabricWidth) );

		if ($iPoints3 > 0)
			$objPdf->Text(151, (42.7 + (3.44 * $i)), formatNumber((($iPoints3*3600)/$fActual3)/$iFabricWidth) );
	}


	$i += 2;

	$objPdf->Text(79, (42.8 + (3.44 * $i)), formatNumber(($iPoints1 + $iPoints2 + $iPoints3), false) );

	$i ++;

	$objPdf->Text(79, (42.9 + (3.44 * $i)), formatNumber(($fActual1 + $fActual2 + $fActual3), false) );

/*
	if ($iBrand == 77)
		$objPdf->Text(180, (41.6 + (3.44 * $i)), formatNumber((($iDefects1 + $iDefects2 + $iDefects3) *  39.37 * 100) / ($fGiven1 + $fGiven2 + $fGiven3) / $iFabricWidth) );

	else
*/
		$objPdf->Text(180, (41.6 + (3.44 * $i)), formatNumber(((($iPoints1 + $iPoints2 + $iPoints3) * 3600) / ($fActual1 + $fActual2 + $fActual3)) / $iFabricWidth) );




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