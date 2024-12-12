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

	if ($Vendor > 0)
		$sVendorSql = " AND vendor_id='$Vendor' $sVendorsSql ";

	else
		$sVendorSql = " AND vendor_id IN ({$_SESSION['Vendors']}) $sVendorsSql ";


	if ($Brand > 0)
		$sBrandSql = " AND brand_id='$Brand' ";

	else
		$sBrandSql = " AND brand_id IN ({$_SESSION['Brands']}) ";


	if ($Season > 0)
		$sSeasonSql = " AND season_id='$Season' ";

	for ($i = 0; $i < count($sStatus); $i ++)
	{
		$sSQL = "SELECT COUNT(*) FROM tbl_lab_dips WHERE status='{$sStatus[$i]}' AND (date_requested BETWEEN '$FromDate' AND '$ToDate') $sVendorSql $sBrandSql $sSeasonSql";
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

	$sChart = $objChart->makeSession("LabDipStats");

	$sImageMap = $objChart->getHTMLImageMap($_SERVER['PHP_SELF'], "Tab=Production&Vendor={$Vendor}&Brand={$Brand}&FromDate={$FromDate}&ToDate={$ToDate}\" onclick=\"return false;", "title='{label} = {value}'");
?>
			              <div class="vsrChart">
			                <div class="chart"><img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" usemap="#LabDipStatsMap" /></div>
			                <div class="title"><b>Lab Dip Statistics</b></div>

			                <div id="Handle7" class="handle" style="display:block;" onclick="showSummary(7);"></div>

			                <div id="Summary7" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">Lab Dip Statistics</div>
			                    <div class="handle" onclick="hideSummary(7);"></div>
			                  </div>
			                </div>

						    <map name="LabDipStatsMap">
							  <?= $sImageMap ?>
						    </map>
			              </div>
