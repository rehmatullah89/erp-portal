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

	$sSQL = "SELECT type FROM tbl_defect_types WHERE id='$DefectType'";
	$objDb->query($sSQL);

	$sDefectType = $objDb->getField(0, 0);


	$sDefectCodes  = array( );
	$iDefectCodes  = array( );
	$iTotalDefects = array( );


	$sSQL = "SELECT dc.id, CONCAT(dc.defect, '   ', dc.`code`) AS _DefectCode, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND dt.id='$DefectType' $sConditions $sDefectsSql
			 GROUP BY dc.id
			 ORDER BY _Defects ASC, _DefectCode ASC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDefectCode = $objDb->getField($i, "id");
		$sDefectCode = $objDb->getField($i, "_DefectCode");
		$iDefects    = $objDb->getField($i, "_Defects");

		if (@in_array($iDefectCode, $iDefectCodes))
		{
			$iIndex = @array_search($iDefectCode, $iDefectCodes);

			$iTotalDefects[$iIndex] += $iDefects;
		}

		else
		{
			$iDefectCodes[]  = $iDefectCode;
			$sDefectCodes[]  = $sDefectCode;
			$iTotalDefects[] = $iDefects;
		}
	}


	$sColors = array( );

	for ($i = 0; $i < count($iDefectCodes); $i ++)
		$sColors[] = hexdec(substr($sDefectColors[$DefectType], 1));



	$objChart = new XYChart(920, 580);
	$objChart->setPlotArea(260, 60, 640, 480, 0xffffff, 0xffffff, 0x000000, $objChart->dashLineColor(0xcccccc, DotLine), $objChart->dashLineColor(0xcccccc, DotLine));

	$objChart->setColors2(8, $sColors);
	$objChart->swapXY( );

	$objChart->addTitle("{$sDefectType} Defects", "verdana.ttf", 20);

	$objBarLayer = $objChart->addBarLayer3($iTotalDefects);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setAggregateLabelStyle( );
	$objBarLayer->setAggregateLabelFormat("{value}");

	$objLabels = $objChart->xAxis->setLabels($sDefectCodes);
	$objChart->yAxis->setLabelFormat("{value}");

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("DefectCode");

	$objChart->addExtraField($iDefectCodes);

	$sImageMap = $objChart->getHTMLImageMap($_SERVER['PHP_SELF'], ("Region={$Region}&AuditStage={$AuditStage}&Defect[]=".@implode("&Defect[]=", $Defect)."&Brand[]=".@implode("&Brand[]=", $Brand)."&Vendor={$Vendor}&REport={$Report}&Line[]=".@implode("&Line[]=", $Line)."&Color={$Color}&FromDate={$FromDate}&ToDate={$ToDate}&DefectType={$DefectType}&DefectCode={field0}&Po={$Po}"), "title='{xLabel} = {value} Defects'");
	$sBackUrl  = ("{$_SERVER['PHP_SELF']}?Region={$Region}&AuditStage={$AuditStage}&Defect[]=".@implode("&Defect[]=", $Defect)."&Brand[]=".@implode("&Brand[]=", $Brand)."&Vendor={$Vendor}&Report={$Report}&Line[]=".@implode("&Line[]=", $Line)."&Color={$Color}&FromDate={$FromDate}&ToDate={$ToDate}&Po={$Po}");
?>
				  <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" vspace="10" border="0" usemap="#DefectCodeMap" />

				  <map name="DefectCodeMap">
					<?= $sImageMap ?>
				  </map>
