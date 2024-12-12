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
	$objDb3      = new Database( );

	$PO          = IO::strValue('PO');
	$Style       = IO::strValue('Style');
	$Vendor      = IO::intValue('Vendor');
	$Brand       = IO::intValue('Brand');
	$Customers   = @implode("','", IO::getArray('Customers'));
	$Region      = IO::intValue('Region');
	$Line        = IO::intValue("Line");
	$Nature      = IO::strValue("Nature");
	$AuditStage  = IO::strValue("AuditStage");
	$Report      = IO::intValue('Report');
	$AuditStatus = IO::strValue("AuditStatus");
	$AuditResult = IO::strValue("AuditResult");
	$FromDate    = IO::strValue('FromDate');
	$ToDate      = IO::strValue('ToDate');
	$Types       = IO::strValue("Types");


	// Create new PHPExcel object
	$objPhpExcel = new PHPExcel();

	// Set properties
	$objPhpExcel->getProperties()->setCreator("Triple Tree Solutions");
	$objPhpExcel->getProperties()->setLastModifiedBy("Triple Tree Solutions");
	$objPhpExcel->getProperties()->setTitle("Quality Summary Report");
	$objPhpExcel->getProperties()->setSubject("");
	$objPhpExcel->getProperties()->setDescription("Quality Summary Report");
	$objPhpExcel->getProperties()->setKeywords("");
	$objPhpExcel->getProperties()->setCategory("Reports");

	// Create a first sheet
	$objPhpExcel->setActiveSheetIndex(0);

	// Add a drawing to the worksheet
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Logo');
	$objDrawing->setDescription('Logo');
	$objDrawing->setPath($sBaseDir.'images/reports/quality-report.jpg');
	$objDrawing->setCoordinates('A1');
	$objDrawing->setHeight(90);
	$objDrawing->setWorksheet($objPhpExcel->getActiveSheet( ));



	$sReportTypes = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStages = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");

	$sConditions  = " AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') AND FIND_IN_SET(qa.report_id, '$sReportTypes') "; // AND NOT FIND_IN_SET(qa.report_id, '$sQmipReports')

  if ($Customers != "")
    $sConditions .= " AND po.customer IN ('".$Customers."') ";

	if ($PO != "")
	{
		$sConditions .= " AND (";

		$sSubSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '%$PO%'";
		$objDb->query($sSubSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPoId = $objDb->getField($i, 0);

			if ($i > 0)
				$sConditions .= " OR ";

			$sConditions .= " qa.po_id='$iPoId' OR FIND_IN_SET('$iPoId', qa.additional_pos) ";
		}

		$sConditions .= ") ";
	}

	if ($Style != "")
	{
		$sSQL = "SELECT po_id FROM tbl_po_colors WHERE style_id IN (SELECT id FROM tbl_styles WHERE style='$Style')";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		$sPos = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND po.id IN ($sPos)";
	}


	if ($Brand > 0)
		$sSQL = "SELECT id FROM tbl_po WHERE brand_id='$Brand'";

	else
		$sSQL = "SELECT id FROM tbl_po WHERE FIND_IN_SET(brand_id, '{$_SESSION['Brands']}')";

	if ($Vendor > 0)
		$sSQL .= " AND vendor_id='$Vendor' ";

	else
		$sSQL .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Region > 0)
		$sSQL .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$sPos = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sPos .= (",".$objDb->getField($i, 0));

	if ($sPos != "")
		$sPos = substr($sPos, 1);

	$sConditions .= " AND po.id IN ($sPos)";

	if ($Vendor > 0)
		$sConditions .= " AND qa.vendor_id='$Vendor'";

	else
		$sConditions .= " AND qa.vendor_id IN ({$_SESSION['Vendors']})";

	if ($Region > 0)
	{
		$sSQL = "SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		$sVendors = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sVendors .= (",".$objDb->getField($i, 0));

		if ($sVendors != "")
			$sVendors = substr($sVendors, 1);

		$sConditions .= " AND qa.vendor_id IN ($sVendors)";
	}

	if ($Line > 0)
		$sConditions .= " AND qa.line_id='$Line'";

	if ($AuditStage != "")
		$sConditions .= " AND qa.audit_stage='$AuditStage'";

	if ($Report > 0)
		$sConditions .= " AND qa.report_id='$Report'";

	if ($AuditStatus != "")
		$sConditions .= " AND qa.audit_status='$AuditStatus'";

	if ($AuditResult != "")
		$sConditions .= " AND qa.audit_result='$AuditResult'";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";
	
	
	$sSubConditions = "";

	if ($Nature != "")
		$sSubConditions .= " AND (SELECT SUM(defects) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature='$Nature') > '0' ";



	$sAuditorsList    = getList("tbl_users", "id", "name");
	$sVendorsList     = getList("tbl_vendors", "id", "vendor");
	$sBrandsList      = getList("tbl_brands", "id", "brand", "parent_id>'0'");
	$sSeasonsList     = getList("tbl_seasons", "id", "season", "parent_id>'0'");
	$sProgramsList    = getList("tbl_programs", "id", "program");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage");
	$iTotals          = array( );

	$sSQL = "SELECT qa.id,
					po.id,
					qa.report_id,
					qa.additional_pos,
					qa.audit_code,
					qa.audit_date,
					qa.ship_qty,
					qa.total_gmts,
					qa.max_defects,
					qa.audit_result,
					qa.re_screen_qty,
					qa.beautiful_products,
					qa.start_time,
					qa.audit_stage,
					qa.audit_status,
					qa.colors,
					po.order_no,
					po.quantity,
					po.brand_id,
					qa.style_id,
					po.styles,
					po.customer,
					qa.vendor_id,
					qa.dhu,
					(SELECT line FROM tbl_lines WHERE id=qa.line_id) AS _Line,
					(SELECT etd_required FROM tbl_po_colors WHERE po_id=po.id AND style_id=qa.style_id ORDER BY etd_required ASC LIMIT 1) AS _EtdRequired,
					qa.user_id,
					(SELECT SUM(defects) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature='0') AS _MinorDefects,
					(SELECT SUM(defects) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature='1') AS _MajorDefects,
					(SELECT SUM(defects) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature='2') AS _CriticalDefects
			 FROM tbl_qa_reports qa, tbl_po po
			 WHERE qa.po_id=po.id AND qa.audit_result!=''  $sConditions $sSubConditions
			 ORDER BY qa.id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount >= 1)
	{
		$iReportId = $objDb->getField(0, "report_id");


		$objPhpExcel->getActiveSheet()->setCellValue('A8', 'Vendor');
		$objPhpExcel->getActiveSheet()->setCellValue('B8', 'Brand');
		$objPhpExcel->getActiveSheet()->setCellValue('C8', 'Style');
		$objPhpExcel->getActiveSheet()->setCellValue('D8', 'Date (From - To)');
		$objPhpExcel->getActiveSheet()->setCellValue('F8', 'Audit Stage');
		$objPhpExcel->getActiveSheet()->setCellValue('G8', 'Audit Status');
		$objPhpExcel->getActiveSheet()->setCellValue('H8', 'Line');
		$objPhpExcel->getActiveSheet()->setCellValue('I8', 'Defects');

		$objPhpExcel->getActiveSheet()->mergeCells('D8:E8');

		$objPhpExcel->getActiveSheet()->duplicateStyleArray(
				array(
				'font'    => array(
					'bold'      => true,
					'size' => 10
					),
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
					),
					'borders' => array(
						'top'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
					'fill' => array(
						'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
						'rotation'   => 90,
						'startcolor' => array(
							'argb' => 'FFD9D9D9'
						),
						'endcolor'   => array(
							'argb' => 'FFFFFFFF'
						)
					)
				),
				'A8:I8'
		);



		if ($Vendor > 0)
			$objPhpExcel->getActiveSheet()->setCellValue('A9', $sVendorsList[$Vendor]);

		if ($Brand > 0)
			$objPhpExcel->getActiveSheet()->setCellValue('B9', $sBrandsList[$Brand]);

		$objPhpExcel->getActiveSheet()->setCellValue('C9', $Style);
		$objPhpExcel->getActiveSheet()->setCellValue('D9', formatDate($FromDate));
		$objPhpExcel->getActiveSheet()->setCellValue('E9', formatDate($ToDate));
		$objPhpExcel->getActiveSheet()->setCellValue('F9', $AuditStage);
		$objPhpExcel->getActiveSheet()->setCellValue('G9', $AuditResult);
		$objPhpExcel->getActiveSheet()->setCellValue('I9', (($Nature == "") ? "ALL" : (($Nature == "0") ? "Minor" : (($Nature == "1") ? "Major" : "Critical"))));

		$sSQL = "SELECT line FROM tbl_lines WHERE id='$Line'";
		$objDb2->query($sSQL);

		if ($objDb2->getField(0, 0))
			$objPhpExcel->getActiveSheet()->setCellValue('H9', $objDb2->getField(0, 0));


		$objPhpExcel->getActiveSheet()->duplicateStyleArray(
				array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
					)
				),
				'A9:I9'
		);



		$objPhpExcel->getActiveSheet()->setCellValue('A12', "Insp Date");
		$objPhpExcel->getActiveSheet()->setCellValue('B12', "Factory");
		$objPhpExcel->getActiveSheet()->setCellValue('C12', "Brand");
		$objPhpExcel->getActiveSheet()->setCellValue('D12', "Customer");
		$objPhpExcel->getActiveSheet()->setCellValue('E12', "Style");
		$objPhpExcel->getActiveSheet()->setCellValue('F12', "P/O #");
		$objPhpExcel->getActiveSheet()->setCellValue('G12', "Color");
		$objPhpExcel->getActiveSheet()->setCellValue('H12', "Program");
		$objPhpExcel->getActiveSheet()->setCellValue('I12', "Destination");
		$objPhpExcel->getActiveSheet()->setCellValue('J12', "Lot Units");
		$objPhpExcel->getActiveSheet()->setCellValue('K12', "Ship Qty");
		$objPhpExcel->getActiveSheet()->setCellValue('L12', "Sample Unit");
		$objPhpExcel->getActiveSheet()->setCellValue('M12', "Accepted");
		$objPhpExcel->getActiveSheet()->setCellValue('N12', "Rejected");
		$objPhpExcel->getActiveSheet()->setCellValue('O12', "Critical");
		$objPhpExcel->getActiveSheet()->setCellValue('P12', "Major");
		$objPhpExcel->getActiveSheet()->setCellValue('Q12', "Minor");
		$objPhpExcel->getActiveSheet()->setCellValue('R12', "Total Dfects");


		$sSQL = "SELECT id, type FROM tbl_defect_types WHERE FIND_IN_SET(id, '$Types') ORDER BY type";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );
		$iCell   = 83;

		for ($i = 0; $i < $iCount2; $i ++)
			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).'12', $objDb2->getField($i, 1));

		$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).'12', "P/F");
		$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).'12', "SY");

		for ($i = 1; $i <= 20; $i ++)
		{
			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).'12', 'CMT '.$i);
			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).'12', 'QTY '.$i);
		}

		$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).'12', "Auditor");
		$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).'12', "Audit Code");
		$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).'12', "Pieces Available for Inspection");
		$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).'12', "Rescreen Qty");
		$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).'12', "ETD Required");
		$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).'12', "Beautiful Products");

		for ($i = 1; $i <= 9; $i ++)
			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).'12', "C {$i}  ");

		$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).'12', "Audit Time");
		$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).'12', "Line #");
		$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).'12', "Audit Stage");
		$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell).'12', "Audit Status");
		
		if ($_SESSION['UserType'] == "TRIPLETREE")
			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol(++$iCell).'12', "DR");

		// Set style for header row using alternative method
		$objPhpExcel->getActiveSheet()->duplicateStyleArray(
				array(
					'font'    => array(
						'bold'      => true,
						'size' => 10
					),
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
					),
				'borders' => array(
					'top'     => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				),
				'fill' => array(
					'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
					'rotation'   => 90,
					'startcolor' => array(
						'argb' => 'FFA6A6A6'
					),
					'endcolor'   => array(
						'argb' => 'FFFFFFFF'
					)
				)
				),
				('A12:'.getExcelCol($iCell).'12')
		);


		$iRow = 13;

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iAuditId       = $objDb->getField($i, "qa.id");
			$iPoId          = $objDb->getField($i, "po.id");
			$sCustomer      = $objDb->getField($i, "po.customer");
			$sAdditionalPos = $objDb->getField($i, "additional_pos");
			$iReportId      = $objDb->getField($i, "report_id");
			$iMinors        = $objDb->getField($i, "_MinorDefects");
			$iMajors        = $objDb->getField($i, "_MajorDefects");
			$iCriticals     = $objDb->getField($i, "_CriticalDefects");

			$iAuditDefects  = ($iMinors + $iMajors + $iCriticals);


			if ($Nature != "")
			{
				$sSQL = "SELECT dt.id, qad.nature, SUM(qad.defects)
						 FROM tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
						 WHERE qad.audit_id='$iAuditId' AND qad.code_id=dc.id AND dc.type_id=dt.id AND qad.nature='$Nature'
						 GROUP BY dt.id
						 ORDER BY dt.id";
			}
			
			else
			{
				$sSQL = "SELECT dt.id, qad.nature, SUM(qad.defects)
						 FROM tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
						 WHERE qad.audit_id='$iAuditId' AND qad.code_id=dc.id AND dc.type_id=dt.id
						 GROUP BY dt.id, qad.nature
						 ORDER BY dt.id";
				
			}
			
			
			$objDb3->query($sSQL);

			$iCount3      = $objDb3->getCount( );
			$sTypeDefects = array( );

			for ($j = 0; $j < $iCount3; $j ++)
			{
				$iType    = $objDb3->getField($j, 0);
				$fNature  = $objDb3->getField($j, 1);
				$iDefects = $objDb3->getField($j, 2);


				$iTotals[$iType] += $objDb3->getField($j, 2);

				if ($sTypeDefects[$iType] != "")
					$sTypeDefects[$iType] .= ",  ";


				if ($Nature != "")
					$sTypeDefects[$iType] .= $iDefects;
				
				else
				{
					if ($fNature == 2)
						$sTypeDefects[$iType] .= "CR ({$iDefects})";

					else if ($fNature == 1)
						$sTypeDefects[$iType] .= "MJ ({$iDefects})";

					else if ($fNature == 0)
						$sTypeDefects[$iType] .= "MI ({$iDefects})";
				}
			}


			$sPos    = "";
			$iPosQty = $objDb->getField($i, 'quantity');
			
			if ($sAdditionalPos != "")
			{
				$sSQL = "SELECT order_no, quantity FROM tbl_po WHERE id IN ($sAdditionalPos)";
				$objDb3->query($sSQL);

				$iCount3 = $objDb3->getCount( );

				for ($j = 0; $j < $iCount3; $j ++)
				{
					$sPos    .= (", ".$objDb3->getField($j, 0));
					$iPosQty += $objDb3->getField($j, 1);
				}
			}

			
			$iShipQty = $objDb->getField($i, 'ship_qty');			
			
			if ($iReportId == 20 || $iReportId == 23)
			{
				$sSQL = "SELECT qty_of_lots, qty_per_lot FROM tbl_kik_inspection_summary WHERE audit_id='$iAuditId'";
				$objDb3->query($sSQL);

				$iQtyOfLots = $objDb3->getField(0, "qty_of_lots");
				$iQtyPerLot = $objDb3->getField(0, "qty_per_lot");
				
				$iShipQty = ($iQtyOfLots * $iQtyPerLot);
			}
				
			
			
			$iStyle = $objDb->getField($i, 'style_id');

			if ($iStyle == 0 && $objDb->getField($i, 'styles') != "")
				@list($iStyle) = @explode(",", $objDb->getField($i, 'styles'));

			$sSQL = "SELECT style, program_id FROM tbl_styles WHERE id='$iStyle'";
			$objDb3->query($sSQL);

			$sStyle   = $objDb3->getField(0, "style");
			$iProgram = $objDb3->getField(0, "program_id");


			$iDestination = getDbValue("destination_id", "tbl_po_colors", "po_id='$iPoId' AND style_id='$iStyle'");
			$sDestination = getDbValue("CONCAT(destination, ' (', IF(type='D', 'Direct', 'Warehouse'), ')')", "tbl_destinations", "id='$iDestination'");


			$objPhpExcel->getActiveSheet()->setCellValue('A'.$iRow, $objDb->getField($i, 'audit_date'));
			$objPhpExcel->getActiveSheet()->setCellValue('B'.$iRow, $sVendorsList[$objDb->getField($i, 'vendor_id')]);
			$objPhpExcel->getActiveSheet()->setCellValue('C'.$iRow, $sBrandsList[$objDb->getField($i, 'brand_id')]);
			$objPhpExcel->getActiveSheet()->setCellValue('D'.$iRow, $sCustomer);
			$objPhpExcel->getActiveSheet()->setCellValue('E'.$iRow, $sStyle);
			$objPhpExcel->getActiveSheet()->setCellValue('F'.$iRow, $objDb->getField($i, 'order_no').$sPos);
			$objPhpExcel->getActiveSheet()->setCellValue('G'.$iRow, $objDb->getField($i, 'colors'));
			$objPhpExcel->getActiveSheet()->setCellValue('H'.$iRow, $sProgramsList[$iProgram]);
			$objPhpExcel->getActiveSheet()->setCellValue('I'.$iRow, $sDestination);
			$objPhpExcel->getActiveSheet()->setCellValue('J'.$iRow, $iPosQty);
			$objPhpExcel->getActiveSheet()->setCellValue('K'.$iRow, $iShipQty);
			$objPhpExcel->getActiveSheet()->setCellValue('L'.$iRow, $objDb->getField($i, 'total_gmts'));
			$objPhpExcel->getActiveSheet()->setCellValue('M'.$iRow, $objDb->getField($i, 'max_defects'));
			$objPhpExcel->getActiveSheet()->setCellValue('N'.$iRow, ($objDb->getField($i, 'max_defects') + 1));
			$objPhpExcel->getActiveSheet()->setCellValue('O'.$iRow, intval($iCriticals));
			$objPhpExcel->getActiveSheet()->setCellValue('P'.$iRow, intval($iMajors));
			$objPhpExcel->getActiveSheet()->setCellValue('Q'.$iRow, intval($iMinors));
			$objPhpExcel->getActiveSheet()->setCellValue('R'.$iRow, intval($iAuditDefects));


			$iCell = 83;

			for ($j = 0; $j < $iCount2; $j ++)
				$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).$iRow, $sTypeDefects[$objDb2->getField($j, 0)]);

			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).$iRow, $objDb->getField($i, 'audit_result'));
			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).$iRow, $sSeasonsList[getDbValue("sub_season_id", "tbl_styles", "id='$iStyle'")]);


			if ($Nature != "")
				$sSQL = "SELECT (SELECT code FROM tbl_defect_codes WHERE id=tbl_qa_report_defects.code_id), defects FROM tbl_qa_report_defects WHERE audit_id='$iAuditId' AND nature='$Nature' ORDER BY id";
			
			else
				$sSQL = "SELECT (SELECT code FROM tbl_defect_codes WHERE id=tbl_qa_report_defects.code_id), defects FROM tbl_qa_report_defects WHERE audit_id='$iAuditId' ORDER BY id";
			
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($j = 0; $j < 20; $j ++)
			{
				if ($objDb3->getField($j, 0))
					$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell).$iRow, $objDb3->getField($j, 0));

				$iCell ++;

				if ($objDb3->getField($j, 1))
					$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell).$iRow, $objDb3->getField($j, 1));

				$iCell ++;
			}

			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).$iRow, $sAuditorsList[$objDb->getField($i, 'user_id')]);
			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).$iRow, $objDb->getField($i, "audit_code"));
			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).$iRow, getDbValue("SUM(lot_size)", "tbl_qa_lot_sizes", "audit_id='$iAuditId'"));//$objDb->getField($i, 'ship_qty')
			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).$iRow, (int)getDbValue("text_value", "tbl_qa_checklist_results", "audit_id='$iAuditId' AND item_id='8'"));//$objDb->getField($i, "re_screen_qty")
			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).$iRow, $objDb->getField($i, "_EtdRequired"));
			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).$iRow, $objDb->getField($i, "beautiful_products"));

			if ($iReportId == 19)
			{
				$sSQL = "SELECT * FROM tbl_ar_beautiful_products WHERE audit_id='$iAuditId'";
				$objDb3->query($sSQL);

				for ($j = 1; $j <= 9; $j ++)
					$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).$iRow, intval($objDb3->getField(0, "c{$j}")));
			}

			else
				$iCell += 9;

			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).$iRow, substr($objDb->getField($i, "start_time"), 0, 5));
			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).$iRow, $objDb->getField($i, "_Line"));
			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).$iRow, $sAuditStagesList[$objDb->getField($i, "audit_stage")]);
			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell).$iRow, $objDb->getField($i, "audit_status"));
			
			if ($_SESSION['UserType'] == "TRIPLETREE")
				$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol(++ $iCell).$iRow, $objDb->getField($i, "dhu"));


			$objPhpExcel->getActiveSheet()->duplicateStyleArray(
					array(
						'alignment' => array(
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
						)
					),
					('A'.$iRow.':'.getExcelCol($iCell).$iRow)
			);

			$iRow ++;

			$iTotals['LotUnits']          += $iPosQty;
			$iTotals['ShipQty']           += $objDb->getField($i, 'ship_qty');
			$iTotals['BeautifulProducts'] += $objDb->getField($i, 'beautiful_products');
			$iTotals['SampleUnits']       += $objDb->getField($i, 'total_gmts');
			$iTotals['Acc']               += $objDb->getField($i, 'max_defects');
			$iTotals['Reg']               += ($objDb->getField($i, 'max_defects') + 1);

			$iTotals['Criticals']         += $iCriticals;
			$iTotals['Majors']            += $iMajors;
			$iTotals['Minors']            += $iMinors;
			$iTotals['TotalDefects']      += $iAuditDefects;

			if ($iReportId == 19)
			{
				for ($j = 1; $j <= 9; $j ++)
					$iTotals["C{$j}"] += intval($objDb3->getField(0, "c{$j}"));
			}
		}


		$objPhpExcel->getActiveSheet()->setCellValue('A'.$iRow, "Grand Total : ");
		$objPhpExcel->getActiveSheet()->mergeCells('A'.$iRow.':H'.$iRow);

		$objPhpExcel->getActiveSheet()->setCellValue('J'.$iRow, $iTotals['LotUnits']);
		$objPhpExcel->getActiveSheet()->setCellValue('K'.$iRow, $iTotals['ShipQty']);
		$objPhpExcel->getActiveSheet()->setCellValue('L'.$iRow, $iTotals['SampleUnits']);
		$objPhpExcel->getActiveSheet()->setCellValue('M'.$iRow, $iTotals['Acc']);
		$objPhpExcel->getActiveSheet()->setCellValue('N'.$iRow, $iTotals['Reg']);
		$objPhpExcel->getActiveSheet()->setCellValue('O'.$iRow, $iTotals['Criticals']);
		$objPhpExcel->getActiveSheet()->setCellValue('P'.$iRow, $iTotals['Majors']);
		$objPhpExcel->getActiveSheet()->setCellValue('Q'.$iRow, $iTotals['Minors']);
		$objPhpExcel->getActiveSheet()->setCellValue('R'.$iRow, $iTotals['TotalDefects']);

		$iCell = 83;

		for ($j = 0; $j < $iCount2; $j ++)
			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).$iRow, (int)$iTotals[$objDb2->getField($j, 0)]);


		$iCell += 47;

		$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).$iRow, $iTotals['BeautifulProducts']);

		for ($i = 1; $i <= 9; $i ++)
			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).$iRow, $iTotals["C{$i}"]);

		$iCell += 3;


		$objPhpExcel->getActiveSheet()->duplicateStyleArray(
				array(
					'font'    => array(
						'bold'      => true,
						'size' => 10
					),
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
					),
					'borders' => array(
						'top'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
					'fill' => array(
						'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
						'rotation'   => 90,
						'startcolor' => array(
							'argb' => 'FFD9D9D9'
						),
						'endcolor'   => array(
							'argb' => 'FFFFFFFF'
						)
					)
				),
				('A'.$iRow.':'.getExcelCol($iCell).$iRow)
		);

		$iRow += 2;


		$objPhpExcel->getActiveSheet()->setCellValue('A'.$iRow, "Percentage (%) ");
		$objPhpExcel->getActiveSheet()->mergeCells('A'.$iRow.':M'.$iRow);

		$objPhpExcel->getActiveSheet()->setCellValue('N'.$iRow, @round((($iTotals['TotalDefects'] / $iTotals['SampleUnits']) * 100), 3).'%');

		$iCell = 83;

		for ($j = 0; $j < $iCount2; $j ++)
			$objPhpExcel->getActiveSheet()->setCellValue(getExcelCol($iCell ++).$iRow, @round((((int)$iTotals[$objDb2->getField($j, 0)] / (int)$iTotals['TotalDefects']) * 100), 3).'%');


		$iCell += 60;

		$objPhpExcel->getActiveSheet()->duplicateStyleArray(
				array(
					'font'    => array(
						'bold'      => true,
						'size' => 10
					),
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
					),
					'borders' => array(
						'top'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
					'fill' => array(
						'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
						'rotation'   => 90,
						'startcolor' => array(
							'argb' => 'FFD9D9D9'
						),
						'endcolor'   => array(
							'argb' => 'FFFFFFFF'
						)
					)
				),
				('A'.$iRow.':'.getExcelCol($iCell).$iRow)
		);


		// Set column widths
		for ($i = 65; $i <= $iCell; $i ++)
			$objPhpExcel->getActiveSheet()->getColumnDimension(getExcelCol($i))->setAutoSize(true);
	}


	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPhpExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPhpExcel->getActiveSheet()->setTitle('Quality Summary Report');



	$sExcelFile = "Quality Summary Report.xlsx";

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
	$objWriter->save("php://output");



	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>