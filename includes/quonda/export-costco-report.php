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


	function setHeaderFooter($iPageIndex)
	{
		global $Id;
		global $iReportId;
		global $sVendor;
		global $sAuditor;
		global $iPo;
		global $iAdditionalPos;
		global $sPo;
		global $iStyle;
		global $sColors;
		global $sSizes;
		global $sAuditStatus;
		global $sAuditCode;
		global $sAuditDate;
		global $sStartTime;
		global $sEndTime;
		global $sAuditStage;
		global $sAuditResult;
		global $sCustomSize;
		global $iTotalGmts;
		global $iGmtsDefective;
		global $iMaxDefects;
		global $iTotalCartons;
		global $iCartonsRejected;
		global $fPercentDecfective;
		global $fStandard;
		global $fCartonsRequired;
		global $fCartonsShipped;
		global $iShipQty;
		global $sApprovedSample;
		global $sShippingMark;
		global $sPackingCheck;
		global $sCartonSize;
		global $sComments;
		global $iLine;
		global $fDhu;

		global $fKnitted;
		global $fDyed;
		global $iCutting;
		global $iSewing;
		global $iPacking;
		global $sFinalAuditDate;

		global $iLength;
		global $iWidth;
		global $iHeight;
		global $sUnit;

		global $iQuantity;
		global $sAdditionalPos;

		global $sStyle;
		global $sDescription;
		global $iParent;
		global $iBrand;
		global $sBrand;

		global $iDestination;
		global $sSizeTitles;
		global $sDestination;

		global $sBaseDir;

		global $objPdf;



		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(50, 50, 50);


		$objPdf->Text(25, 43.3, $sBrand);
		$objPdf->Text(80, 43.5, $sVendor);
		$objPdf->Text(122, 43.5, formatDate($sAuditDate));
		$objPdf->Text(185.5, 43.5, $sAuditor);

		$objPdf->Text(15, 50.5, ($sPo.$sAdditionalPos));
		$objPdf->Text(85, 50.5, $sStyle);
		$objPdf->Text(129, 50.5, formatNumber($iQuantity, false));
		$objPdf->Text(185, 50.5, formatNumber($iShipQty, false));

		$objPdf->Text(29, 57.5, $sDestination);
		$objPdf->Text(82, 57.5, $sSizeTitles);
		$objPdf->Text(125, 57.5, $sColors);
		$objPdf->Text(185, 57.5, formatNumber($fCartonsShipped, false));

		$objPdf->Text(28, 65.5, $sDescription);


		if ($sAuditStatus == "1st")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 140, 64.5, 4);

		else if ($sAuditStatus == "2nd")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 150, 64.5, 4);

		else if ($sAuditStatus == "3rd")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 160, 64.5, 4);

		else
			$objPdf->Text(167, 66, $sAuditStatus);


		$objPdf->Text(190, 66, strtoupper($sAuditStage));


		$objPdf->Text(33, 79, (($fKnitted > 0) ? $fKnitted : "N/A"));
		$objPdf->Text(54, 79, (($fDyed > 0) ? $fDyed : "N/A"));
		$objPdf->Text(77, 79, (($iCutting > 0) ? formatNumber($iCutting, false) : "N/A"));
		$objPdf->Text(99, 79, (($iSewing > 0) ? formatNumber($iSewing, false) : "N/A"));
		$objPdf->Text(137, 79, ((($iFinishing > 0) ? formatNumber($iFinishing, false) : "N/A")." / ".(($iPacking > 0) ? formatNumber($iPacking, false) : "N/A")));
		$objPdf->Text(177, 79, (($sFinalAuditDate != "0000-00-00") ? formatDate($sFinalAuditDate) : "N/A"));


		@list($iStartHour, $iStartMinutes) = @explode(":", $sStartTime);
		@list($iEndHour, $iEndMinutes)     = @explode(":", $sEndTime);

		if ($iStartHour >= 12)
		{
			if ($iStartHour > 12)
				$iStartHour -= 12;

			$sStartAmPm  = "PM";
		}

		else
			$sStartAmPm = "AM";


		if ($iEndHour >= 12)
		{
			if ($iEndHour > 12)
				$iEndHour -= 12;

			$sEndAmPm  = "PM";
		}

		else
			$sEndAmPm = "AM";

		$sStartTime = (str_pad($iStartHour, 2, '0', STR_PAD_LEFT).":".str_pad($iStartMinutes, 2, '0', STR_PAD_LEFT)." ".$sStartAmPm);
		$sEndTime   = (str_pad($iEndHour, 2, '0', STR_PAD_LEFT).":".str_pad($iEndMinutes, 2, '0', STR_PAD_LEFT)." ".$sEndAmPm);

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


		$objPdf->Text(33, 84, $sStartTime);
		$objPdf->Text(74, 84, $sEndTime);
		$objPdf->Text(110, 84, $fAql);

		if ($sAuditResult == "P")
			$objPdf->Text(200, 84, 'PASS');

		else if ($sAuditResult == "F")
			$objPdf->Text(200, 84, 'FAIL');

		else
			$objPdf->Text(200, 84, 'HOLD');


		if ($iPageIndex == 1)
		{
			$sCodes = array("0", "101", "102", "103", "104", "0", "105", "0", "106", "107", "108", "0", "109", "0", "110", "0", "111", "0", "112", "113",
							"0", "201", "0", "0", "202", "0", "0", "203", "0", "204", "0", "205", "0",
							"0", "301", "0", "302", "0", "0", "-1", "303", "0", "304", "0", "305", "0",
							"0", "401", "402", "403", "0", "404", "0", "405", "0", "406", "407",
							"0", "501", "502", "503", "504", "505", "506", "0", "507", "508", "509", "0", "510", "511", "0", "512", "513", "514", "0");
		}

		else if ($iPageIndex == 2)
		{
			$sCodes = array("515",
			                "0", "601", "602", "0", "603", "604", "605",
			                "0", "701", "702", "703", "704", "0", "705", "706", "707", "0", "708", "0", "709", "710", "711", "0", "712", "713", "714", "0", "715", "0", "716", "0",
							"0", "801", "802", "803", "804", "0", "805", "-1", "806", "807", "808",
							"0", "901", "902", "903", "904", "905", "906", "907", "908", "909", "910", "911", "0",
							"0", "1001", "1002", "1003", "0", "1004", "1005", "1006", "0",
							"0", "1101", "1102", "0", "1103", "1104", "1105", "1106", "1107", "0", "1108");
		}

		else if ($iPageIndex == 3)
		{
			$sCodes = array("1109", "1110", "0", "1111", "1112", "0", "1113", "0", "1114", "1115", "1116", "1117", "0", "1118", "1119",
							"0", "1201", "1202", "0",
							"0", "1301", "0", "1302", "1303", "0",
							"0", "1401", "0",
							"0", "1501", "0",
							"0", "1601", "0",
							"0", "1701",
							"0", "1801", "-1",
							"0", "1901",
							"0", "2001");
		}


		$iLeft = 77;
		$iTop  = 94.2;

		for ($i = 0; $i < count($sCodes); $i ++)
		{
			if ($sCodes[$i] == "0")
			{
				$iTop += 3.64;

				continue;
			}

			if ($sCodes[$i] == "-1")
			{
				$iLeft = 190;
				$iTop  = 94.2;

				continue;
			}



			$iCode = (int)getDbValue("id", "tbl_defect_codes", "report_id='$iReportId' AND code='{$sCodes[$i]}'");

			if ($iCode == 0)
				continue;


			$iMajorDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id='$iCode' AND nature='1'");
			$iMinorDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND code_id='$iCode' AND nature='0'");

			if ($iMajorDefects == 0 && $iMinorDefects == 0)
				continue;


			if ($iMajorDefects > 0)
				$objPdf->Text($iLeft, $iTop, $iMajorDefects);

			if ($iMinorDefects > 0)
				$objPdf->Text(($iLeft + 12), $iTop, $iMinorDefects);

			$iTop += 3.64;
		}

		$iDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id'");



		$objPdf->Text(40, 240.5, (($sCustomSample == "Y") ? "CUSTOM ({$iTotalGmts})" : $iTotalGmts));
		$objPdf->Text(88, 240.5, (($iGmtsDefective > 0) ? $iGmtsDefective : $iDefects));
		$objPdf->Text(128, 240.5, $iMaxDefects);
		$objPdf->Text(168, 240.5, $iDefects);
		$objPdf->Text(195, 240.5, @round((($iDefects / $iTotalGmts) * 100), 2));

		$objPdf->Text(48, 249, $iTotalCartons);
		$objPdf->Text(88, 249, $iCartonsRejected);
		$objPdf->Text(128, 249, $fStandard);
		$objPdf->Text(168, 249, $fPercentDecfective);
		$objPdf->Text(195, 249, @round((($fCartonsShipped / $fCartonsRequired) * 100), 2));


		if ($sApprovedSample == "Yes")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 80, 251, 4);

		else if ($sApprovedSample == "No")
			$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 93, 251, 4);

		$objPdf->Text(136, 254.7, (($sShippingMark == "Y") ? "Yes" : "No"));
		$objPdf->Text(188, 254.8, (($sPackingCheck == "Y") ? "Yes" : "No"));

		$objPdf->Text(181, 259, $iLength);
		$objPdf->Text(193, 259, $iWidth);
		$objPdf->Text(203, 259, $iHeight);


		$objPdf->Text(165, 302, $sAuditor);


		$objPdf->SetFont('Arial', '', 5.5);

		$objPdf->SetXY(8.5, 267);
		$objPdf->MultiCell(199, 2.2, $sComments, 0);
	}

	$objPdf =& new FPDI( );



	$iPageCount = $objPdf->setSourceFile($sBaseDir."templates/costco-01.pdf");

	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "Legal");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	setHeaderFooter(1);


	$iPageCount = $objPdf->setSourceFile($sBaseDir."templates/costco-02.pdf");

	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "Legal");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	setHeaderFooter(2);


	$iPageCount = $objPdf->setSourceFile($sBaseDir."templates/costco-03.pdf");

	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "Legal");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	setHeaderFooter(3);



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