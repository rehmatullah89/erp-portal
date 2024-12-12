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

	$sData      = array( );
	$sLabels    = array('Rejected', 'Accepted', 'Pending', 'Hold');
	$sStatus    = array("R", "A", "P", "H");
	$sSeasonSql = "";

	if ($Brand > 0)
		$sBrandSql = " AND brand_id='$Brand' ";

	else
		$sBrandSql = " AND brand_id IN ({$_SESSION['Brands']}) ";


	if ($Season > 0)
		$sSeasonSql = " AND rms_id IN (SELECT id FROM tbl_brand_rms WHERE season_id='$Season') ";

	for ($i = 0; $i < count($sStatus); $i ++)
	{
		$sSQL = "SELECT COUNT(*) FROM tbl_fabric WHERE result='{$sStatus[$i]}' AND (its_sent_date BETWEEN '$FromDate' AND '$ToDate') $sBrandSql $sSeasonSql";
		$objDb->query($sSQL);

		$sData[$i] = $objDb->getField(0, 0);
	}


	$objChart = new PieChart(296, 201);
	$objChart->setDonutSize(148, 100, 105, 0);
	$objChart->set3D(15);

	if (@array_sum($sData) > 0)
		$objChart->setData($sData, $sLabels);

	$objChart->setLabelStyle("", 8, Transparent);

	$objChart->addText(2, 182, ("Total = ".@array_sum($sData)), "tahoma.ttf", 8, 0x777777);

	$objLegend = $objChart->addLegend(10, 0, false, "tahoma.ttf", 8);
	$objLegend->setBackground(Transparent);

	$sChart = $objChart->makeSession("FabricStats");

	$sImageMap = $objChart->getHTMLImageMap($_SERVER['PHP_SELF'], "Tab=Fabric&Brand={$Brand}&FromDate={$FromDate}&ToDate={$ToDate}\" onclick=\"return false;", "title='{label} = {value}'");
?>
			              <div class="vsrChart">
			                <div class="chart"><img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" usemap="#FabricStatsMap" /></div>
			                <div class="title"><b>Statistics</b></div>

			                <div id="Handle1" class="handle" style="display:block;" onclick="showSummary(1);"></div>

			                <div id="Summary1" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">Statistics</div>
			                    <div class="handle" onclick="hideSummary(1);"></div>
			                  </div>
			                </div>

						    <map name="FabricStatsMap">
							  <?= $sImageMap ?>
						    </map>
			              </div>
