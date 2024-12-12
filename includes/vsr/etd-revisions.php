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

	$sData       = array( );
	$sLabels     = array( );
	$sReasons    = array( );
	$iReasons    = array( );
	$sConditions = "";


	if ($Vendor > 0)
		$sConditions .= " AND po.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";


	if ($Region > 0)
		$sConditions .= "AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";


	if ($Brand > 0)
		$sConditions .= " AND po.brand_id='$Brand' ";

	else
		$sConditions .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";


	if ($iChartLevel == 1)
		$sConditions .= " AND err.reason_id IN (SELECT id FROM tbl_etd_revision_reasons WHERE parent_id IN (SELECT id FROM tbl_etd_revision_reasons WHERE parent_id='$Parent')) ";

	else if ($iChartLevel == 2)
		$sConditions .= " AND err.reason_id IN (SELECT id FROM tbl_etd_revision_reasons WHERE parent_id='$Reason') ";



	$sSQL = "SELECT err.reason_id, COUNT(*)
			 FROM tbl_etd_revision_requests err, tbl_po po
			 WHERE err.po_id=po.id AND (DATE_FORMAT(err.date_time, '%Y-%m-%d') BETWEEN '$FromDate' AND '$ToDate') AND err.reason_id>'0' $sConditions
			 GROUP BY err.reason_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iReasonId = $objDb->getField($i, 0);
		$iRequests = $objDb->getField($i, 1);


		if ($iChartLevel <= 1)
		{
			if ($iChartLevel == 0)
				$sSQL = "SELECT parent_id FROM tbl_etd_revision_reasons WHERE id=(SELECT parent_id FROM tbl_etd_revision_reasons WHERE id='$iReasonId')";

			else if ($iChartLevel == 1)
				$sSQL = "SELECT parent_id FROM tbl_etd_revision_reasons WHERE id='$iReasonId'";

			$objDb2->query($sSQL);

			$iReasonId = $objDb2->getField(0, 0);
		}

		$sReasons[$iReasonId] += $iRequests;
	}


	foreach ($sReasons as $iReasonId => $iRequests)
	{
		$sSQL = "SELECT reason FROM tbl_etd_revision_reasons WHERE id='$iReasonId'";
		$objDb->query($sSQL);

		$iReasons[] = $iReasonId;
		$sData[]    = $iRequests;
		$sLabels[]  = $objDb->getField(0, 0);
	}


	if ($iChartLevel > 1)
	{
		$objChart = new XYChart(800, 340);
		$objChart->setPlotArea(280, 50, 400, 250);
		$objChart->swapXY( );
	}

	else
	{
		$objChart = new XYChart(454, 340);
		$objChart->setPlotArea(50, 50, 380, 250);
	}

	$objTitle = $objChart->addTitle("\n{$sChartTitle}", "verdanab.ttf", 10);

	$objBarLayer = $objChart->addBarLayer3($sData);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setAggregateLabelStyle( );
	$objBarLayer->setAggregateLabelFormat("{value}");

	if ($iChartLevel > 1)
		$objChart->xAxis->setLabelStyle("tahoma.ttf", 8);

	else
		$objChart->xAxis->setLabelStyle("tahoma.ttf", 7);


	$objLabels = $objChart->xAxis->setLabels($sLabels);

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession($sChartName);

	$objChart->addExtraField($sLabels);
	$objChart->addExtraField($iReasons);

	if ($iChartLevel == 0)
		$sImageMap = $objChart->getHTMLImageMap($_SERVER['PHP_SELF'], ("Vendor={$Vendor}&Brand={$Brand}&FromDate={$FromDate}&ToDate={$ToDate}&Region={$Region}&Parent={field1}&Step=1\""), "title='{field0} = {value} Requests'");

	else if ($iChartLevel == 1)
		$sImageMap = $objChart->getHTMLImageMap($_SERVER['PHP_SELF'], ("Vendor={$Vendor}&Brand={$Brand}&FromDate={$FromDate}&ToDate={$ToDate}&Region={$Region}&Parent={$Parent}&Reason={field1}&Step=".(($Step < 2) ? ($Step + 1) : $Step)."\"".(($iChartLevel <= 2) ? '' : ' onclick="return false;"')), "title='{field0} = {value} Requests'");

	else
		$sImageMap = $objChart->getHTMLImageMap($_SERVER['PHP_SELF'], "\" onclick=\"return false;\"", "title='{field0} = {value} Requests'");
?>
			            <img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" usemap="#EtdRevisionMap<?= $iChartLevel ?>" />

			            <map name="EtdRevisionMap<?= $iChartLevel ?>">
			              <?= $sImageMap ?>
			            </map>
