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

	$sAccepted = array( );
	$sRejected = array( );
	$sLabels   = array('1-14','14-28','29-42','42+');

	for ($i = 0; $i < count($sLabels); $i ++)
	{
		@list($iStart, $iEnd) = @explode("-", $sLabels[$i]);

		if ($iStart == "42+")
			$sSubSql = " AND DATEDIFF(due_date, start_date) > '$iStart' ";

		else
			$sSubSql = " AND (DATEDIFF(due_date, start_date) BETWEEN '$iStart' AND '$iEnd') ";


		$sSQL = "SELECT COUNT(*) FROM tbl_fabric WHERE result='A' AND (due_date BETWEEN '$FromDate' AND '$ToDate') $sBrandSql $sSubSql $sSeasonSql";
		$objDb->query($sSQL);

		$sAccepted[$i] = $objDb->getField(0, 0);


		$sSQL = "SELECT COUNT(*) FROM tbl_fabric WHERE result='R' AND (due_date BETWEEN '$FromDate' AND '$ToDate') $sBrandSql $sSubSql $sSeasonSql";
		$objDb->query($sSQL);

		$sRejected[$i] = $objDb->getField(0, 0);
	}

	$objChart = new XYChart(296, 201);
	$objChart->setPlotArea(35, 30, 250, 140);

	$objBarLayer = $objChart->addBarLayer2(Stack);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->addDataSet($sAccepted, 0xeeeeee, "Accepted");
	$objBarLayer->addDataSet($sRejected, 0x999999, "Rejected");

	$objBarLayer->setAggregateLabelStyle("tahoma.ttf", 7, 0x000000);
	$objBarLayer->setDataLabelStyle("tahoma.ttf", 7, 0x000000);

	$objChart->xAxis->setLabels($sLabels);
	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$objLegend = $objChart->addLegend(50, 2, false, "tahoma.ttf", 8);
	$objLegend->setBackground(Transparent);

	$sChart = $objChart->makeSession("FabricTurnAroundTime");
?>
			              <div class="vsrChart">
			                <div class="chart"><img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" /></div>
			                <div class="title"><b>Turn Around Time</b></div>

			                <div id="Handle3" class="handle" style="display:block;" onclick="showSummary(3);"></div>

			                <div id="Summary3" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">Turn Around Time</div>
			                    <div class="handle" onclick="hideSummary(3);"></div>
			                  </div>
			                </div>
			              </div>
