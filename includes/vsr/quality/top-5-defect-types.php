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

	$sData    = array( );
	$sLabels  = array( );
	$sDefects = array( );

	$sSQL = "SELECT dt.type, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND qa.report_id='$iReportId' $sConditions
			 GROUP BY dc.type_id
			 ORDER BY _Defects DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sDefects[$objDb->getField($i, 0)] = $objDb->getField($i, 1);


	$sSQL = "SELECT dt.type, COALESCE(SUM(gfd.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_gf_report_defects gfd, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=gfd.audit_id AND gfd.code_id=dc.id AND dc.type_id=dt.id AND qa.report_id='$iReportId' $sConditions
			 GROUP BY dc.type_id
			 ORDER BY _Defects DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sDefects[$objDb->getField($i, 0)] += $objDb->getField($i, 1);


	@arsort($sDefects);


	$iIndex = 0;

	foreach ($sDefects AS $sDefect => $iDefects)
	{
		$sData[]   = $iDefects;
		$sLabels[] = $sDefect;

		$iIndex ++;

		if ($iIndex == 5)
			break;
	}


	$objChart = new XYChart(296, 201);
	$objChart->setPlotArea(35, 20, 245, 170);

	$objBarLayer = $objChart->addBarLayer3($sData);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setAggregateLabelStyle( );
	$objBarLayer->setAggregateLabelFormat("{value}");

	$objChart->yAxis->setLabelFormat("{value}");
	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("Top5Defects".$iReportId);

	$objChart->addExtraField($sLabels);

	$sImageMap = $objChart->getHTMLImageMap($_SERVER['PHP_SELF'], "Tab=Quality&Vendor={$Vendor}&Brand={$Brand}&FromDate={$FromDate}&ToDate={$ToDate}\" onclick=\"return false;", "title='{field0} = {value} Defects'");
?>
			              <div class="vsrChart">
			                <div class="chart"><img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" usemap="#Top5DefectsMap<?= $iReportId ?>" /></div>
			                <div class="title"><b>Top 5 <?= $sReport ?> Defects</b></div>

			                <div id="Handle<?= $iPosition ?>" class="handle" style="display:block;" onclick="showSummary(<?= $iPosition ?>);"></div>

			                <div id="Summary<?= $iPosition ?>" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">Top 5 <?= $sReport ?> Defect Types</div>
			                    <div class="handle" onclick="hideSummary(<?= $iPosition ?>);"></div>
			                  </div>
			                </div>

						    <map name="Top5DefectsMap<?= $iReportId ?>">
							  <?= $sImageMap ?>
						    </map>
			              </div>

