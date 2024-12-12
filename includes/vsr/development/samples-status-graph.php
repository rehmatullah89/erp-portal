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

	$sSQL = "SELECT COUNT(*) FROM tbl_comment_sheets $sConditions AND merchandising_id IN (SELECT id FROM tbl_merchandisings WHERE status='Rejected')";
	$objDb->query($sSQL);

	if ($objDb->getField(0, 0) > 0)
	{
		$sData[]   = $objDb->getField(0, 0);
		$sLabels[] = "Rejected";
	}

	$sSQL = "SELECT COUNT(*) FROM tbl_comment_sheets $sConditions AND merchandising_id IN (SELECT id FROM tbl_merchandisings WHERE status='Approved')";
	$objDb->query($sSQL);

	if ($objDb->getField(0, 0) > 0)
	{
		$sData[]   = $objDb->getField(0, 0);
		$sLabels[] = "Approved";
	}


	$sSQL = "SELECT COUNT(*) FROM tbl_comment_sheets $sConditions AND merchandising_id IN (SELECT id FROM tbl_merchandisings WHERE status='Waiting')";
	$objDb->query($sSQL);

	if ($objDb->getField(0, 0) > 0)
	{
		$sData[]   = $objDb->getField(0, 0);
		$sLabels[] = "Waiting";
	}


	$objChart = new PieChart(296, 201);
	$objChart->setDonutSize(148, 102, 105, 0);
	$objChart->set3D(15);
	$objChart->setData($sData, $sLabels);
	$objChart->setLabelStyle("", 8, Transparent);

	$objLegend = $objChart->addLegend(10, 0, false, "tahoma.ttf", 8);
	$objLegend->setBackground(Transparent);

	$sChart = $objChart->makeSession("SamplingStats");

	$sImageMap = $objChart->getHTMLImageMap($_SERVER['PHP_SELF'], "Tab=Development&Vendor={$Vendor}&Brand={$Brand}&FromDate={$FromDate}&ToDate={$ToDate}\" onclick=\"return false;", "title='{label} = {value} ({percent}%)'");
?>
			              <div class="vsrChart">
			                <div class="chart"><img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" usemap="#SamplingStatsMap" /></div>
			                <div class="title"><b>Sampling Status Statistics</b></div>

			                <div id="Handle2" class="handle" style="display:block;" onclick="showSummary(2);"></div>

			                <div id="Summary2" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">Sampling Status Statistics</div>
			                    <div class="handle" onclick="hideSummary(2);"></div>
			                  </div>
			                </div>

						    <map name="SamplingStatsMap">
							  <?= $sImageMap ?>
						    </map>
			              </div>
