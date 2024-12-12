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

	$fDhu    = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
	$sMonths = array('January','February','March','April','May','June','July','August','September','October','November','December');

	$iYear = (int)@substr($ToDate, 0, 4);

	if ($iYear == 0)
		$iYear = date("Y");

	
	$sDefectsSql = "";
	
	if (count($Defect) > 0 && @implode(",", $Defect) != "")
		$sDefectsSql = " AND nature IN (".@implode(",", $Defect).") ";


	if (count($Brand) == 1)
	{
		$sSQL = "SELECT DATE_FORMAT(qa.audit_date, '%c') AS _Month,
						SUM(qa.total_gmts) AS _TotalGmts,
						SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id $sDefectsSql)) AS _TotalDefects
				 FROM tbl_po po, tbl_qa_reports qa
				 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND (qa.audit_date BETWEEN '$iYear-01-01' AND '$iYear-12-31') $sAuditorSQL
				       AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";
	}

	else
	{
		$sSQL = "SELECT DATE_FORMAT(qa.audit_date, '%c') AS _Month, ROUND(AVG(qa.dhu), 2) AS _Dhu
				 FROM tbl_po po, tbl_qa_reports qa
				 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND (qa.audit_date BETWEEN '$iYear-01-01' AND '$iYear-12-31') $sAuditorSQL
				       AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";
	}

	if ($Po != "")
	{
		$sSubSQL = "SELECT id FROM tbl_po WHERE order_no='$Po'";
		$objDb->query($sSubSQL);

		$iCount = $objDb->getCount( );


		$sSQL .= " AND ( ";

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPoId = $objDb->getField($i, 0);

			if ($i > 0)
				$sSQL .= " OR ";

			$sSQL .= " qa.po_id='$iPoId' OR FIND_IN_SET('$iPoId', qa.additional_pos) ";
		}

		$sSQL .= " ) ";
	}

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if (count($Brand) > 0)
		$sSQL .= (" AND po.brand_id IN (".@implode(",", $Brand).") ");

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($AuditStage != "")
		$sSQL .= " AND qa.audit_stage='$AuditStage' ";

	if (@is_array($Line))
	{
		if (count($Line) > 0)
			$sSQL .= " AND qa.line_id IN (".@implode(",", $Line).") ";
	}

	else if ($Line > 0)
		$sSQL .= " AND qa.line_id='$Line' ";

	if ($Report > 0)
		$sSQL .= " AND qa.report_id='$Report' ";

	if ($Color != "")
		$sSQL .= " AND qa.po_id IN (SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE color='$Color') ";

	if ($DefectType > 0)
	{
		if ($DefectCode > 0)
			$sCodeSql = " AND qad.code_id='$DefectCode' ";


		$sSubSQL = "SELECT DISTINCT(qad.audit_id) FROM tbl_qa_report_defects qad, tbl_defect_codes dc WHERE qad.code_id=dc.id AND dc.type_id='$DefectType' $sCodeSql";
		$objDb->query($sSubSQL);

		$iCount       = $objDb->getCount( );
		$sKnitsAudits = "0";

		for ($i = 0; $i < $iCount; $i ++)
			$sKnitsAudits .= (",".$objDb->getField($i, 0));


		$sSQL .= " AND qa.id IN ($sKnitsAudits) ";
	}

	$sSQL .= " GROUP BY DATE_FORMAT(qa.audit_date, '%m')
	           ORDER BY _Month";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iMonth = $objDb->getField($i, "_Month");
		$iMonth --;


		if (count($Brand) == 1)
			$fDhu[$iMonth] = @round((($objDb->getField($i, "_TotalDefects") / $objDb->getField($i, "_TotalGmts")) * 100), 2);

		else
			$fDhu[$iMonth] = $objDb->getField($i, "_Dhu");
	}


	$objChart = new XYChart(920, 580);
	$objChart->setPlotArea(60, 70, 820, 470, 0xffffff, 0xffffff, 0x000000, $objChart->dashLineColor(0xcccccc, DotLine), $objChart->dashLineColor(0xcccccc, DotLine));

	$objChart->addTitle("Quality Analysis Report - {$iYear}", "verdana.ttf", 20);

	$objLayer = $objChart->addLineLayer( );
	$objLayer->setLineWidth(2);
	$objLayer->setDataLabelFormat("{value}%");

	$objDataSet = $objLayer->addDataSet($fDhu);
	$objDataSet->setDataSymbol(SquareSymbol, 7);

	$objChart->xAxis->setLabels($sMonths);
	$objChart->yAxis->setLabelFormat("{value}%");

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("DefectRate");
?>
			      <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" />
<?
	$fDhu   = array( );
	$iYears = array( );

	$iStartYear = 2008;
	$iEndYear   = date("Y");

	for ($i = $iStartYear; $i <= $iEndYear; $i ++)
	{
		$fDhu[]   = 0;
		$iYears[] = $i;
	}


	if (count($Brand) == 1)
	{
		$sSQL = "SELECT DATE_FORMAT(qa.audit_date, '%Y') AS _Year,
						SUM(qa.total_gmts) AS _TotalGmts,
						SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id $sDefectsSql) ) AS _TotalDefects
				 FROM tbl_po po, tbl_qa_reports qa
				 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND (qa.audit_date BETWEEN '$iStartYear-01-01' AND '$iEndYear-12-31') $sAuditorSQL
				       AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";
	}

	else
	{
		$sSQL = "SELECT DATE_FORMAT(qa.audit_date, '%Y') AS _Year, ROUND(AVG(qa.dhu), 2) AS _Dhu
				 FROM tbl_po po, tbl_qa_reports qa
				 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND (qa.audit_date BETWEEN '$iStartYear-01-01' AND '$iEndYear-12-31') $sAuditorSQL
				       AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";
	}

	if ($Po != "")
	{
		$sSubSQL = "SELECT id FROM tbl_po WHERE order_no='$Po'";
		$objDb->query($sSubSQL);

		$iCount = $objDb->getCount( );


		$sSQL .= " AND ( ";

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPoId = $objDb->getField($i, 0);

			if ($i > 0)
				$sSQL .= " OR ";

			$sSQL .= " qa.po_id='$iPoId' OR FIND_IN_SET('$iPoId', qa.additional_pos) ";
		}

		$sSQL .= " ) ";
	}

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if (count($Brand) > 0)
		$sSQL .= (" AND po.brand_id IN (".@implode(",", $Brand).") ");

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($AuditStage != "")
		$sSQL .= " AND qa.audit_stage='$AuditStage' ";

	if (@is_array($Line))
	{
		if (count($Line) > 0)
			$sSQL .= " AND qa.line_id IN (".@implode(",", $Line).") ";
	}

	else if ($Line > 0)
		$sSQL .= " AND qa.line_id='$Line' ";

	if ($Report > 0)
		$sSQL .= " AND qa.report_id='$Report' ";

	if ($Color != "")
		$sSQL .= " AND qa.po_id IN (SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE color='$Color') ";

	if ($DefectType > 0)
	{
		if ($DefectCode > 0)
			$sCodeSql = " AND qad.code_id='$DefectCode' ";


		$sSubSQL = "SELECT DISTINCT(qad.audit_id) FROM tbl_qa_report_defects qad, tbl_defect_codes dc WHERE qad.code_id=dc.id AND dc.type_id='$DefectType' $sCodeSql";
		$objDb->query($sSubSQL);

		$iCount       = $objDb->getCount( );
		$sKnitsAudits = "0";

		for ($i = 0; $i < $iCount; $i ++)
			$sKnitsAudits .= (",".$objDb->getField($i, 0));


		$sSQL .= " AND qa.id IN ($sKnitsAudits) ";
	}

	$sSQL .= " GROUP BY DATE_FORMAT(qa.audit_date, '%Y')
	           ORDER BY _Year";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iYear = $objDb->getField($i, "_Year");


		if (count($Brand) == 1)
			$fDhu[($iYear - $iStartYear)] = @round((($objDb->getField($i, "_TotalDefects") / $objDb->getField($i, "_TotalGmts")) * 100), 2);

		else
			$fDhu[($iYear - $iStartYear)] = $objDb->getField($i, "_Dhu");
	}


	$objChart = new XYChart(920, 580);
	$objChart->setPlotArea(60, 70, 820, 470, 0xffffff, 0xffffff, 0x000000, $objChart->dashLineColor(0xcccccc, DotLine), $objChart->dashLineColor(0xcccccc, DotLine));

	$objChart->addTitle("Quality Analysis Report", "verdana.ttf", 20);

	$objLayer = $objChart->addLineLayer( );
	$objLayer->setLineWidth(2);
	$objLayer->setDataLabelFormat("{value}%");
//	$objLayer->setDataLabelStyle("verdana.ttf", 9, 0x000000);

	$objDataSet = $objLayer->addDataSet($fDhu);
	$objDataSet->setDataSymbol(SquareSymbol, 7);

	$objChart->xAxis->setLabels($iYears);
//	$objChart->xAxis->setLabelStyle("verdana.ttf", 9, 0x000000);

//	$objChart->yAxis->setLinearScale(0, 10, 1);
	$objChart->yAxis->setLabelFormat("{value}%");
//	$objChart->yAxis->setLabelStyle("verdana.ttf", 9, 0x000000);

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("YearlyDefectRate");
?>
			      <br />
			      <br />
			      <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" />
