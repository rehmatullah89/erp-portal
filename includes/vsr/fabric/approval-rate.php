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
	$sLabels = array('1st','2nd','3rd','4th','5th','6th');

	for ($i = 1; $i <= 6; $i ++)
	{
		$sSQL = "SELECT COUNT(*) FROM tbl_fabric WHERE submission='$i' AND result='A' AND (its_sent_date BETWEEN '$FromDate' AND '$ToDate') $sBrandSql $sSeasonSql";
		$objDb->query($sSQL);

		$iAccepted = $objDb->getField(0, 0);


		$sSQL = "SELECT COUNT(*) FROM tbl_fabric WHERE submission='$i' AND result IN ('A', 'R') AND (its_sent_date BETWEEN '$FromDate' AND '$ToDate') $sBrandSql $sSeasonSql";
		$objDb->query($sSQL);

		$iTotal = $objDb->getField(0, 0);


		$sData[($i - 1)] = @round((($iAccepted / $iTotal) * 100), 2);

	}

	$objChart = new XYChart(296, 201);
	$objChart->setPlotArea(38, 20, 235, 150);

	$objBarLayer = $objChart->addBarLayer3($sData, array(0x999999, 0x999999, 0x999999, 0x999999, 0x999999, 0x999999));
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setBarWidth(20);
	$objBarLayer->setAggregateLabelStyle( );
	$objBarLayer->setAggregateLabelFormat("{value}%");

	$objChart->xAxis->setLabels($sLabels);
	$objChart->yAxis->setLabelFormat("{value}%");

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("FabricApprovalRate");
?>
			              <div class="vsrChart">
			                <div class="chart"><img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" /></div>
			                <div class="title"><b>Approval Rate</b></div>

			                <div id="Handle2" class="handle" style="display:block;" onclick="showSummary(2);"></div>

			                <div id="Summary2" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">Approval Rate</div>
			                    <div class="handle" onclick="hideSummary(2);"></div>
			                  </div>
			                </div>
			              </div>
