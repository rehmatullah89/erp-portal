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

	$sData   = array( );
	$sLabels = array( );

	$iYear      = date("Y", mktime(0, 0, 0, (date("m") - 11), date("d"), date("Y")));
	$iMonth     = date("m", mktime(0, 0, 0, (date("m") - 11), date("d"), date("Y")));
	$sStartDate = ($iYear."-".$iMonth."-01");


	$sSQL = "SELECT DATE_FORMAT(cs.created, '%b %y') AS _MonthYear, COUNT(*) AS _Total, SUM(CASE m.status WHEN 'A' THEN 1 ELSE 0 END) AS _Approved
	         FROM tbl_comment_sheets cs, tbl_merchandisings m
	         WHERE cs.merchandising_id=m.id AND (DATE_FORMAT(cs.created, '%Y-%m-%d') BETWEEN '$sStartDate' AND CURDATE( )) ";

	if ($Type > 0)
		$sSQL .= " AND m.sample_type_id='$Type' ";

	if ($Brand > 0)
		$sSQL .= " AND m.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')) ";

	else
		$sSQL .= " AND m.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']}) AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')) ";

	$sSQL .= "GROUP BY DATE_FORMAT(cs.created, '%Y-%m')
	          ORDER BY DATE_FORMAT(cs.created, '%Y-%m')";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sMonthYear = $objDb->getField($i, '_MonthYear');
		$iTotal     = $objDb->getField($i, '_Total');
		$iApproved  = $objDb->getField($i, '_Approved');


		$sLabels[] = $sMonthYear;
		$sData[]   = @round((($iApproved / $iTotal) * 100), 2);
	}


	$objChart = new XYChart(920, 500);
	$objChart->setPlotArea(60, 80, 820, 400, 0xffffff, 0xffffff, 0x000000, $objChart->dashLineColor(0xcccccc, DotLine), $objChart->dashLineColor(0xcccccc, DotLine));

	if ($Brand > 0)
		$objChart->addTitle("{$sSamplingTypesList[$Type]} Hit Rate ({$sBrandsList[$Brand]})", "verdana.ttf", 20);

	else
		$objChart->addTitle("Hit Rate", "verdana.ttf", 20);

	if ($sSubTitle != "")
		$objChart->addText(460, 35, $sSubTitle, "verdanab.ttf", 10, 0x555555, 8);

	$objLayer = $objChart->addLineLayer( );
	$objLayer->setLineWidth(2);
	$objLayer->setDataLabelFormat("{value}%");

	$objDataSet = $objLayer->addDataSet($sData);
	$objDataSet->setDataSymbol(SquareSymbol, 7);

	$objChart->xAxis->setLabels($sLabels);
	$objChart->yAxis->setLabelFormat("{value}%");

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("QrsHitRate");
?>
			      <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" />
