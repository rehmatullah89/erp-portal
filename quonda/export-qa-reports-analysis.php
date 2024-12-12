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

	@require_once("../requires/session.php");
	@require_once("../requires/PHPExcel.php");


	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$AuditCode   = IO::strValue("AuditCode");
	$Report      = IO::strValue("Report");
	$Vendor      = IO::strValue("Vendor");
	$Brand       = IO::intValue("Brand");
	$AuditStage  = IO::strValue("AuditStage");
	$Region      = IO::intValue("Region");
	$FromDate    = IO::strValue("FromDate");
	$ToDate      = IO::strValue("ToDate");
	$AuditResult = IO::strValue("AuditResult");


	$sReportTypes     = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sReportsList     = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes') AND NOT FIND_IN_SET(id, '$sQmipReports')");

	$sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");


	$sConditions = " WHERE audit_result!='' ";
	$sDateSql    = "";

	if ($AuditCode != "")
		$sConditions .= " AND audit_code LIKE '%$AuditCode%' ";

	if ($Report > 0)
		$sConditions .= " AND report_id='$Report' ";

	else
		$sConditions .= " AND FIND_IN_SET(report_id, '$sReportTypes') AND NOT FIND_IN_SET(report_id, '$sQmipReports') ";

	if ($AuditResult != "")
		$sConditions .= " AND audit_result='$AuditResult' ";

	if ($AuditStage != "")
		$sConditions .= " AND audit_stage='$AuditStage' ";

	else
		$sConditions .= " AND FIND_IN_SET(audit_stage, '$sAuditStages') ";

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	else
		$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Region > 0)
		$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($FromDate != "" && $ToDate != "")
		$sDateSql .= " AND (audit_date BETWEEN '$FromDate' AND '$ToDate') ";



	$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']}) AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}') ";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$sStyles = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sStyles .= (",".$objDb->getField($i, 0));

	if ($sStyles != "")
		$sStyles = substr($sStyles, 1);

	if (@strpos($_SESSION["Email"], "apparelco.com") !== FALSE || @strpos($_SESSION["Email"], "3-tree.com") !== FALSE)
		$sConditions .= " AND (style_id='0' OR style_id IN ($sStyles)) ";

	else
		$sConditions .= " AND style_id IN ($sStyles) ";



	if ($Brand > 0)
	{
		$sSQL = "SELECT id FROM tbl_po WHERE brand_id='$Brand'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND po_id IN ($sPos) ";
	}

	else
	{
		if ($Vendor > 0)
		{
			$sSQL = "SELECT id FROM tbl_po WHERE vendor_id='$Vendor' AND brand_id IN ({$_SESSION['Brands']})";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
			$sPos   = "";

			for ($i = 0; $i < $iCount; $i ++)
				$sPos .= (",".$objDb->getField($i, 0));

			if ($sPos != "")
				$sPos = substr($sPos, 1);

			$sConditions .= " AND po_id IN ($sPos) ";
		}

		else
			$sConditions .= " AND (po_id='0' OR po_id IN (SELECT id FROM tbl_po WHERE vendor_id IN ({$_SESSION['Vendors']}) AND brand_id IN ({$_SESSION['Brands']})))";
	}





	$objPhpExcel = new PHPExcel( );

	$objPhpExcel->getProperties()->setCreator("Triple Tree Solutions")
								 ->setLastModifiedBy("Triple Tree Solutions")
								 ->setTitle("Data Entry Analysis Report")
								 ->setSubject("Data Entry Analysis")
								 ->setDescription("Data Entry Analysis Report")
								 ->setKeywords("")
								 ->setCategory("Data Entry Analysis");

	$objPhpExcel->setActiveSheetIndex(0);


	$objPhpExcel->getActiveSheet()->setCellValue("A1", "TRIPLE TREE SoLUTIONS");
	$objPhpExcel->getActiveSheet()->mergeCells("A1:M1");
	$objPhpExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(28);
	$objPhpExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);

	$objPhpExcel->getActiveSheet()->setCellValue("A2", "QA Data Gap Analysis Report");
	$objPhpExcel->getActiveSheet()->mergeCells("A2:M2");
	$objPhpExcel->getActiveSheet()->getStyle("A2")->getFont()->setSize(16);
	$objPhpExcel->getActiveSheet()->getStyle("A2")->getFont()->setBold(true);

	$objPhpExcel->getActiveSheet()->setCellValue("A3", "As on: ".date("l, F d, Y h:i A"));
	$objPhpExcel->getActiveSheet()->mergeCells("A3:M3");
	$objPhpExcel->getActiveSheet()->getStyle("A3")->getFont()->setSize(11);


	$sSectionStyle = array('font' => array('bold' => true, 'size' => 11),
						   'fill'       => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'c4bd97')),
						   'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
						   'borders'   => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											  'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											  'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											  'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)) );

	$sHeadingStyle = array('font' => array('bold' => true, 'size' => 11),
						   'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
						   'borders'   => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											  'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											  'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											  'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)) );

	$sBorderStyle = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
						  'borders'  => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											 'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											 'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));

	$sBlockStyle = array('font'       => array('bold' => true, 'size' => 11),
                         'fill'       => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'DDDDDD')),
	                     'alignment'  => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
						 'borders'    => array('top'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											   'right'  => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											   'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											   'left'   => array('style' => PHPExcel_Style_Border::BORDER_THIN)));


	$iRow = 5;

	$objPhpExcel->getActiveSheet()->setCellValue("A{$iRow}", "Data GAP Analysis Report");
	$objPhpExcel->getActiveSheet()->mergeCells("A{$iRow}:M{$iRow}");
	$objPhpExcel->getActiveSheet()->getStyle("A{$iRow}")->getFont()->setSize(20);
	$objPhpExcel->getActiveSheet()->getStyle("A{$iRow}")->getFont()->setBold(true);
	$objPhpExcel->getActiveSheet()->duplicateStyleArray($sSectionStyle , "A{$iRow}:M{$iRow}");


	$iRow ++;

	$objPhpExcel->getActiveSheet()->setCellValue("A{$iRow}", "Date");
	$objPhpExcel->getActiveSheet()->setCellValue("B{$iRow}", "Auditor");
	$objPhpExcel->getActiveSheet()->setCellValue("C{$iRow}", "Quality Manager(s)");
	$objPhpExcel->getActiveSheet()->setCellValue("D{$iRow}", "Vendor");
	$objPhpExcel->getActiveSheet()->setCellValue("E{$iRow}", "Audit Code");
	$objPhpExcel->getActiveSheet()->setCellValue("F{$iRow}", "Audit Stage");
	$objPhpExcel->getActiveSheet()->setCellValue("G{$iRow}", "Audit Result");
	$objPhpExcel->getActiveSheet()->setCellValue("H{$iRow}", "No of Defects");
	$objPhpExcel->getActiveSheet()->setCellValue("I{$iRow}", "Defect Pictures");
	$objPhpExcel->getActiveSheet()->setCellValue("J{$iRow}", "Lab/Specs Reports");
	$objPhpExcel->getActiveSheet()->setCellValue("K{$iRow}", "Packing Images");
	$objPhpExcel->getActiveSheet()->setCellValue("L{$iRow}", "Audit Mode");
	$objPhpExcel->getActiveSheet()->setCellValue("M{$iRow}", "Remarks");

	for ($i = 0; $i < 13; $i ++)
		$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , ((getExcelCol($i + 65))."{$iRow}:".(getExcelCol($i + 65)).$iRow));



	$sUsersList   = getList("tbl_users", "id", "name");
	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");


	$sSQL = "SELECT id, report_id, po_id, style_id, audit_code, audit_date, user_id, vendor_id, audit_stage, audit_result, audit_mode, start_date_time, end_date_time,
	                specs_sheet_1, specs_sheet_2, specs_sheet_3, specs_sheet_4, specs_sheet_5, specs_sheet_6, specs_sheet_7, specs_sheet_8, specs_sheet_9, specs_sheet_10
	         FROM tbl_qa_reports
	         $sConditions $sDateSql
	         ORDER BY id";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$iRow   += 1;

	for ($i = 0; $i < $iCount; $i ++, $iRow ++)
	{
		$iAudit         = $objDb->getField($i, "id");
		$iReport        = $objDb->getField($i, "report_id");
		$sAuditCode     = $objDb->getField($i, "audit_code");
		$sAuditDate     = $objDb->getField($i, "audit_date");
		$iAuditor       = $objDb->getField($i, "user_id");
		$iVendor        = $objDb->getField($i, "vendor_id");
		$sAuditStage    = $objDb->getField($i, "audit_stage");
		$sAuditResult   = $objDb->getField($i, "audit_result");
   		$iAuditMode     = $objDb->getField($i, "audit_mode");
   		$iStyle         = $objDb->getField($i, "style_id");
   		$iPo            = $objDb->getField($i, "po_id");
   		$sStartDateTime = $objDb->getField($i, "start_date_time");
   		$sEndDateTime   = $objDb->getField($i, "end_date_time");

		$sManager    = "";
   		$iSpecSheets = 0;


		if ($iStyle == 0)
			$iStyle = getDbValue("style_id", "tbl_po_colors", "po_id='$iPo'");

		if ($iStyle > 0)
		{
			$iBrand    = getDbValue("sub_brand_id", "tbl_styles", "id='$iStyle'");
			$sManagers = getDbValue("quality_managers", "tbl_departments", "FIND_IN_SET('$iBrand', brands)");

			if ($sManagers != "")
			{
				$iManagers = @explode(",", $sManagers);

				foreach ($iManagers as $iManager)
				{
					if (!@in_array($iManager, array(19, 28)))
						$sManager .= ((($sManager != "") ? ", " : "").$sUsersList[$iManager]);
				}
			}
		}


   		for ($j = 1; $j <= 10; $j ++)
   		{
   			$sSpecsSheet = $objDb->getField($i, "specs_sheet_{$j}");

   			if ($sSpecsSheet != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet))
   				$iSpecSheets ++;
   		}

		switch ($iAuditMode)
		{
			case 0  :  $sAuditMode = "Portal"; break;
			case 1  :  $sAuditMode = "App v1"; break;
			case 2  :  $sAuditMode = "App v2"; break;
		}

		switch ($sAuditResult)
		{
			case "P"  :  $sAuditResult = "Pass"; break;
			case "F"  :  $sAuditResult = "Fail"; break;
			case "H"  :  $sAuditResult = "Hold"; break;
			case "A"  :  $sAuditResult = "Pass"; break;
			case "B"  :  $sAuditResult = "Pass"; break;
			case "C"  :  $sAuditResult = "Fail"; break;
		}


		@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);


		if ($iReport == 6)
		{
			$sSQL = "SELECT gfrd.defects, dc.code,
							(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
					 FROM tbl_gf_report_defects gfrd, tbl_defect_codes dc
					 WHERE gfrd.audit_id='$iAudit' AND gfrd.code_id=dc.id
					 ORDER BY gfrd.id";
		}

		else
		{
			$sSQL = "SELECT qard.defects, dc.code,
							(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
					 FROM tbl_qa_report_defects qard, tbl_defect_codes dc
					 WHERE qard.audit_id='$iAudit' AND qard.code_id=dc.id
					 ORDER BY qard.id";
		}

		$objDb2->query($sSQL);

		$iCount2       = $objDb2->getCount( );
		$iPictures     = 0;
   		$iTotalDefects = 0;
   		$iPacking      = 0;
		$sRequired     = "";
		$sTypes        = array( );

		for($j = 0; $j < $iCount2; $j ++)
		{
			$sCode    = $objDb2->getField($j, 'code');
			$sType    = $objDb2->getField($j, '_Type');
			$iDefects = $objDb2->getField($j, 'defects');

			$iTotalDefects += $iDefects;

			if ($sType == "Measurement")
				continue;


			$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/{$sAuditCode}_{$sCode}_*.*");

			if ($iDefects > count($sPictures))
			{
				if (!@array_key_exists($sType, $sTypes))
					$sTypes[$sType] = 0;

				$sTypes[$sType] += ($iDefects - count($sPictures));
			}

			$iPictures += count($sPictures);
		}


		if ($iStyle == 0)
			$sRequired .= "Style #";

		if ($iPo == 0)
		{
			$sRequired .= (($sRequired != "") ? ", " : "");
			$sRequired .= "PO #";
		}

		foreach ($sTypes as $sType => $iDefects)
		{
			$sRequired .= (($sRequired != "") ? ", " : "");
			$sRequired .= "{$sType} ({$iDefects})";
		}


		$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/{$sAuditCode}*_001_*.*");
		$iPacking  = count($sPictures);

		$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/{$sAuditCode}*_PACK_*.*");
		$iPacking += count($sPictures);


		$sAuditStage = $sAuditStagesList[$sAuditStage];

		if ($sAuditStage == "Final" && $sAuditResult == "Pass" && $iPacking == 0)
		{
			$sRequired .= (($sRequired != "") ? ", " : "");
			$sRequired .= "Packing Images";
		}

		if ($sAuditStage == "Final" && $sAuditResult == "Pass" && $iSpecSheets == 0)
		{
			$sRequired .= (($sRequired != "") ? ", " : "");
			$sRequired .= "Specs Sheet";
		}


		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, formatDate($sAuditDate));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $sUsersList[$iAuditor]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, $sManager);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, $sVendorsList[$iVendor]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, $sAuditCode);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, $sAuditStage);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $iRow, $sAuditResult);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $iRow, $iTotalDefects);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $iRow, $iPictures);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $iRow, $iSpecSheets);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $iRow, $iPacking);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $iRow, $sAuditMode);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $iRow, $sRequired);

		if ($sRequired != "")
			$objPhpExcel->getActiveSheet()->getStyle("A{$iRow}:O{$iRow}")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');

		for ($j = 0; $j < 13; $j ++)
			$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, (getExcelCol($j + 65).$iRow.":".getExcelCol($j + 65).$iRow));
	}



	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



	$iRow += 5;

	$objPhpExcel->getActiveSheet()->setCellValue("A{$iRow}", "Report Finalization on Next Day");
	$objPhpExcel->getActiveSheet()->getStyle("A{$iRow}")->getFont()->setSize(20);
	$objPhpExcel->getActiveSheet()->getStyle("A{$iRow}")->getFont()->setBold(true);
	$objPhpExcel->getActiveSheet()->mergeCells("A{$iRow}:J{$iRow}");
	$objPhpExcel->getActiveSheet()->duplicateStyleArray($sSectionStyle , "A{$iRow}:J{$iRow}");


	$iRow ++;

	$objPhpExcel->getActiveSheet()->setCellValue("A{$iRow}", "Audit Date");
	$objPhpExcel->getActiveSheet()->setCellValue("B{$iRow}", "Auditor");
	$objPhpExcel->getActiveSheet()->setCellValue("C{$iRow}", "Vendor");
	$objPhpExcel->getActiveSheet()->setCellValue("D{$iRow}", "Audit Code");
	$objPhpExcel->getActiveSheet()->setCellValue("E{$iRow}", "Audit Stage");
	$objPhpExcel->getActiveSheet()->setCellValue("F{$iRow}", "Audit Result");
	$objPhpExcel->getActiveSheet()->setCellValue("G{$iRow}", "Audit Mode");
	$objPhpExcel->getActiveSheet()->setCellValue("H{$iRow}", "Scheduled At");
	$objPhpExcel->getActiveSheet()->setCellValue("I{$iRow}", "Completed At");
	$objPhpExcel->getActiveSheet()->setCellValue("J{$iRow}", "Last Modified At");

	for ($i = 0; $i < 10; $i ++)
		$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , ((getExcelCol($i + 65))."{$iRow}:".(getExcelCol($i + 65)).$iRow));



	$sSQL = "SELECT audit_code, audit_date, user_id, vendor_id, audit_stage, audit_result, audit_mode, created_at, date_time, end_date_time
	         FROM tbl_qa_reports
	         $sConditions $sDateSql AND (DATE(date_time) > DATE(created_at) OR (DATE(date_time) > DATE(end_date_time) AND end_date_time != '0000-00-00 00:00:00'))
	         ORDER BY id";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$iRow   += 1;

	for ($i = 0; $i < $iCount; $i ++, $iRow ++)
	{
		$sAuditCode   = $objDb->getField($i, "audit_code");
		$sAuditDate   = $objDb->getField($i, "audit_date");
		$iAuditor     = $objDb->getField($i, "user_id");
		$iVendor      = $objDb->getField($i, "vendor_id");
		$sAuditStage  = $objDb->getField($i, "audit_stage");
		$sAuditResult = $objDb->getField($i, "audit_result");
   		$iAuditMode   = $objDb->getField($i, "audit_mode");
   		$sCreatedAt   = $objDb->getField($i, "created_at");
   		$sEndDateTime = $objDb->getField($i, "end_date_time");
   		$sDateTime    = $objDb->getField($i, "date_time");


		switch ($iAuditMode)
		{
			case 0  :  $sAuditMode = "Portal"; break;
			case 1  :  $sAuditMode = "App v1"; break;
			case 2  :  $sAuditMode = "App v2"; break;
		}

		switch ($sAuditResult)
		{
			case "P"  :  $sAuditResult = "Pass"; break;
			case "F"  :  $sAuditResult = "Fail"; break;
			case "H"  :  $sAuditResult = "Hold"; break;
			case "A"  :  $sAuditResult = "Pass"; break;
			case "B"  :  $sAuditResult = "Pass"; break;
			case "C"  :  $sAuditResult = "Fail"; break;
		}


		$sAuditStage = $sAuditStagesList[$sAuditStage];
		$sCompletion = $sDateTime;
		$sModified   = $sDateTime;

		if ($iAuditMode == 2)
			$sCompletion = $sEndDateTime;


		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, formatDate($sAuditDate));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $sUsersList[$iAuditor]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, $sVendorsList[$iVendor]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, $sAuditCode);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, $sAuditStage);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, $sAuditResult);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $iRow, $sAuditMode);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $iRow, $sCreatedAt);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $iRow, $sCompletion);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $iRow, $sModified);

		for ($j = 0; $j < 10; $j ++)
			$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, (getExcelCol($j + 65).$iRow.":".getExcelCol($j + 65).$iRow));
	}



	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



	$iRow += 5;

	$objPhpExcel->getActiveSheet()->setCellValue("A{$iRow}", "Back Dated Entries");
	$objPhpExcel->getActiveSheet()->getStyle("A{$iRow}")->getFont()->setSize(20);
	$objPhpExcel->getActiveSheet()->getStyle("A{$iRow}")->getFont()->setBold(true);
	$objPhpExcel->getActiveSheet()->mergeCells("A{$iRow}:J{$iRow}");
	$objPhpExcel->getActiveSheet()->duplicateStyleArray($sSectionStyle , "A{$iRow}:J{$iRow}");


	$iRow ++;

	$objPhpExcel->getActiveSheet()->setCellValue("A{$iRow}", "Audit Date");
	$objPhpExcel->getActiveSheet()->setCellValue("B{$iRow}", "Auditor");
	$objPhpExcel->getActiveSheet()->setCellValue("C{$iRow}", "Vendor");
	$objPhpExcel->getActiveSheet()->setCellValue("D{$iRow}", "Audit Code");
	$objPhpExcel->getActiveSheet()->setCellValue("E{$iRow}", "Audit Stage");
	$objPhpExcel->getActiveSheet()->setCellValue("F{$iRow}", "Audit Result");
	$objPhpExcel->getActiveSheet()->setCellValue("G{$iRow}", "Audit Mode");
	$objPhpExcel->getActiveSheet()->setCellValue("H{$iRow}", "Scheduled At");
	$objPhpExcel->getActiveSheet()->setCellValue("I{$iRow}", "Completed At");
	$objPhpExcel->getActiveSheet()->setCellValue("J{$iRow}", "Last Modified At");

	for ($i = 0; $i < 10; $i ++)
		$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , ((getExcelCol($i + 65))."{$iRow}:".(getExcelCol($i + 65)).$iRow));



	$sSQL = "SELECT audit_code, audit_date, user_id, vendor_id, audit_stage, audit_result, audit_mode, created_at, date_time, end_date_time
	         FROM tbl_qa_reports
	         $sConditions AND audit_date < '$FromDate' AND ((DATE(date_time) BETWEEN '$FromDate' AND '$ToDate') OR (DATE(created_at) BETWEEN '$FromDate' AND '$ToDate') OR (DATE(end_date_time) BETWEEN '$FromDate' AND '$ToDate'))
	         ORDER BY id";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$iRow   += 1;

	for ($i = 0; $i < $iCount; $i ++, $iRow ++)
	{
		$sAuditCode   = $objDb->getField($i, "audit_code");
		$sAuditDate   = $objDb->getField($i, "audit_date");
		$iAuditor     = $objDb->getField($i, "user_id");
		$iVendor      = $objDb->getField($i, "vendor_id");
		$sAuditStage  = $objDb->getField($i, "audit_stage");
		$sAuditResult = $objDb->getField($i, "audit_result");
   		$iAuditMode   = $objDb->getField($i, "audit_mode");
   		$sCreatedAt   = $objDb->getField($i, "created_at");
   		$sEndDateTime = $objDb->getField($i, "end_date_time");
   		$sDateTime    = $objDb->getField($i, "date_time");


		switch ($iAuditMode)
		{
			case 0  :  $sAuditMode = "Portal"; break;
			case 1  :  $sAuditMode = "App v1"; break;
			case 2  :  $sAuditMode = "App v2"; break;
		}

		switch ($sAuditResult)
		{
			case "P"  :  $sAuditResult = "Pass"; break;
			case "F"  :  $sAuditResult = "Fail"; break;
			case "H"  :  $sAuditResult = "Hold"; break;
			case "A"  :  $sAuditResult = "Pass"; break;
			case "B"  :  $sAuditResult = "Pass"; break;
			case "C"  :  $sAuditResult = "Fail"; break;
		}


		$sAuditStage = $sAuditStagesList[$sAuditStage];
		$sCompletion = $sDateTime;
		$sModified   = $sDateTime;

		if ($iAuditMode == 2)
			$sCompletion = $sEndDateTime;


		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, formatDate($sAuditDate));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $sUsersList[$iAuditor]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, $sVendorsList[$iVendor]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, $sAuditCode);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, $sAuditStage);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, $sAuditResult);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $iRow, $sAuditMode);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $iRow, $sCreatedAt);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $iRow, $sCompletion);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $iRow, $sModified);

		for ($j = 0; $j < 10; $j ++)
			$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, (getExcelCol($j + 65).$iRow.":".getExcelCol($j + 65).$iRow));
	}





	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



	$iRow += 5;

	$objPhpExcel->getActiveSheet()->setCellValue("A{$iRow}", "Audits Performed under SMV Values");
	$objPhpExcel->getActiveSheet()->getStyle("A{$iRow}")->getFont()->setSize(20);
	$objPhpExcel->getActiveSheet()->getStyle("A{$iRow}")->getFont()->setBold(true);
	$objPhpExcel->getActiveSheet()->mergeCells("A{$iRow}:I{$iRow}");
	$objPhpExcel->getActiveSheet()->duplicateStyleArray($sSectionStyle , "A{$iRow}:I{$iRow}");


	$iRow ++;

	$objPhpExcel->getActiveSheet()->setCellValue("A{$iRow}", "Audit Date");
	$objPhpExcel->getActiveSheet()->setCellValue("B{$iRow}", "Auditor");
	$objPhpExcel->getActiveSheet()->setCellValue("C{$iRow}", "Vendor");
	$objPhpExcel->getActiveSheet()->setCellValue("D{$iRow}", "Audit Code");
	$objPhpExcel->getActiveSheet()->setCellValue("E{$iRow}", "Audit Stage");
	$objPhpExcel->getActiveSheet()->setCellValue("F{$iRow}", "Audit Result");
	$objPhpExcel->getActiveSheet()->setCellValue("G{$iRow}", "No of Defects");
	$objPhpExcel->getActiveSheet()->setCellValue("H{$iRow}", "Sample Size");
	$objPhpExcel->getActiveSheet()->setCellValue("I{$iRow}", "Audit Time");

	for ($i = 0; $i < 9; $i ++)
		$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , ((getExcelCol($i + 65))."{$iRow}:".(getExcelCol($i + 65)).$iRow));



	$sSQL = "SELECT id, report_id, audit_code, audit_date, user_id, vendor_id, audit_stage, audit_result, total_gmts, start_date_time, end_date_time
	         FROM tbl_qa_reports
	         $sConditions $sDateSql AND audit_mode='2' AND TIMESTAMPDIFF(MINUTE, start_date_time, end_date_time) < total_gmts
	         ORDER BY id";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$iRow   += 1;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iAudit         = $objDb->getField($i, "id");
		$iReport        = $objDb->getField($i, "report_id");
		$sAuditCode     = $objDb->getField($i, "audit_code");
		$sAuditDate     = $objDb->getField($i, "audit_date");
		$iAuditor       = $objDb->getField($i, "user_id");
		$iVendor        = $objDb->getField($i, "vendor_id");
		$sAuditStage    = $objDb->getField($i, "audit_stage");
		$sAuditResult   = $objDb->getField($i, "audit_result");
		$iSampleSize    = $objDb->getField($i, "total_gmts");
   		$sStartDateTime = $objDb->getField($i, "start_date_time");
   		$sEndDateTime   = $objDb->getField($i, "end_date_time");


		switch ($sAuditResult)
		{
			case "P"  :  $sAuditResult = "Pass"; break;
			case "F"  :  $sAuditResult = "Fail"; break;
			case "H"  :  $sAuditResult = "Hold"; break;
			case "A"  :  $sAuditResult = "Pass"; break;
			case "B"  :  $sAuditResult = "Pass"; break;
			case "C"  :  $sAuditResult = "Fail"; break;
		}


		$sAuditStage   = $sAuditStagesList[$sAuditStage];
		$iTotalDefects = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAudit'");

		if ($iReport == 6)
			$iTotalDefects = (int)getDbValue("SUM(defects)", "tbl_gf_report_defects", "audit_id='$iAudit'");


		$iAuditTime = (strtotime($sEndDateTime) - strtotime($sStartDateTime));
		$iAuditTime /= 60;
		$iAuditTime = @ceil($iAuditTime);


		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, formatDate($sAuditDate));
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $sUsersList[$iAuditor]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, $sVendorsList[$iVendor]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, $sAuditCode);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, $sAuditStage);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, $sAuditResult);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $iRow, $iTotalDefects);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $iRow, $iSampleSize);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $iRow, "{$iAuditTime} Mins");

		for ($j = 0; $j < 9; $j ++)
			$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, (getExcelCol($j + 65).$iRow.":".getExcelCol($j + 65).$iRow));

		$iRow ++;
	}







	for ($i = 0; $i < 13; $i ++)
		$objPhpExcel->getActiveSheet()->getColumnDimension(getExcelCol($i + 65))->setAutoSize(true);



	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&L&B Quonda GAP Analysis Report &R Generated on ".date("d-M-Y H:i A"));

	$objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	$objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	$objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

	$objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

	$objPhpExcel->getActiveSheet()->setTitle("QA Reports Data Analysis");


	$sExcelFile = "Quonda GAP Analysis.xlsx";

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
	$objWriter->save("php://output");



	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>