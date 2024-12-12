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

	$sSQL = "SELECT CONCAT('(', code,') ', defect) FROM tbl_defect_codes WHERE id='$DefectCode'";
	$objDb->query($sSQL);

	$sDefectCode = $objDb->getField(0, 0);


	$iDefects     = array( );
	$sDefectAreas = array( );

	$sSQL = "SELECT da.area, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt, tbl_defect_areas da
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND da.id=qad.area_id AND dt.id='$DefectType' AND dc.id='$DefectCode' $sConditions $sDefectsSql
			 GROUP BY qad.area_id
			 ORDER BY _Defects ASC";
	$objDb->query($sSQL);
	
	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDefects[]     = $objDb->getField($i, "_Defects");
		$sDefectAreas[] = $objDb->getField($i, "area");
	}


	$sColors = array( );

	for ($i = 0; $i < $iCount; $i ++)
		$sColors[] = hexdec(substr($sDefectColors[$DefectType], 1));



	$objChart = new XYChart(920, 580);
	$objChart->setPlotArea(120, 60, 760, 480, 0xffffff, 0xffffff, 0x000000, $objChart->dashLineColor(0xcccccc, DotLine), $objChart->dashLineColor(0xcccccc, DotLine));

	$objChart->setColors2(8, $sColors);
	$objChart->swapXY( );

	$objChart->addTitle("{$sDefectCode} Defects", "verdana.ttf", 20);

	$objBarLayer = $objChart->addBarLayer3($iDefects);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setAggregateLabelStyle( );
	$objBarLayer->setAggregateLabelFormat("{value}");

	$objLabels = $objChart->xAxis->setLabels($sDefectAreas);
	$objChart->yAxis->setLabelFormat("{value}");

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("DefectArea");

	$sBackUrl = ("{$_SERVER['PHP_SELF']}?Region={$Region}&AuditStage={$AuditStage}&Defect[]=".@implode("&Defect[]=", $Defect)."&Brand[]=".@implode("&Brand[]=", $Brand)."&Vendor={$Vendor}&Report={$Report}&Line=[]".@implode("&Line[]=", $Line)."&Color={$Color}&FromDate={$FromDate}&ToDate={$ToDate}&DefectType={$DefectType}&Po={$Po}");
?>
				  <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" vspace="10" border="0" />
