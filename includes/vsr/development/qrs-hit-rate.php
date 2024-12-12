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
	$sMonths = array('', 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');


	$sQrsTypes = "";

	$sSQL = "SELECT id FROM tbl_sampling_types WHERE type LIKE '%qrs%'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sQrsTypes .= (",".$objDb->getField($i, 0));

	if ($sQrsTypes != "")
		$sQrsTypes = substr($sQrsTypes, 1);


	for ($i = 11; $i >= 0; $i --)
	{
		$iYear  = date("Y", mktime(0, 0, 0, (date("m") - $i), date("d"), date("Y")));
		$iMonth = date("m", mktime(0, 0, 0, (date("m") - $i), date("d"), date("Y")));
		$iDays  = @cal_days_in_month(CAL_GREGORIAN, $iMonth, $iYear);


		$sSQL = "SELECT COUNT(*) FROM tbl_comment_sheets WHERE (DATE_FORMAT(modified, '%Y-%m-%d') BETWEEN '$iYear-$iMonth-01' AND '$iYear-$iMonth-$iDays') AND merchandising_id IN (SELECT id FROM tbl_merchandisings WHERE style_id IN ($sBrandStyles) AND sample_type_id IN ($sQrsTypes))";
		$objDb->query($sSQL);

		$iTotalQrs = $objDb->getField(0, 0);


		$sSQL = "SELECT COUNT(*) FROM tbl_comment_sheets WHERE (DATE_FORMAT(modified, '%Y-%m-%d') BETWEEN '$iYear-$iMonth-01' AND '$iYear-$iMonth-$iDays') AND merchandising_id IN (SELECT id FROM tbl_merchandisings WHERE status='Approved' AND style_id IN ($sBrandStyles) AND sample_type_id IN ($sQrsTypes))";
		$objDb->query($sSQL);

		$iApprovedQrs = $objDb->getField(0, 0);


		$sLabels[] = $sMonths[intval($iMonth)];
		$sData[]   = @round((($iApprovedQrs / $iTotalQrs) * 100), 2);
	}


	$objChart = new XYChart(296, 201);
	$objChart->setPlotArea(35, 20, 250, 150, 0xffffff, 0xffffff, 0x000000, $objChart->dashLineColor(0xcccccc, DotLine), $objChart->dashLineColor(0xcccccc, DotLine));

	$objLayer = $objChart->addLineLayer( );
	$objLayer->setLineWidth(2);
	$objLayer->setDataLabelFormat("{value}%");

	$objDataSet = $objLayer->addDataSet($sData);
	$objDataSet->setDataSymbol(SquareSymbol, 6);

	$objChart->xAxis->setLabels($sLabels);
	$objChart->yAxis->setLabelFormat("{value}%");

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("QrsHitRate");
?>
			              <div class="vsrChart">
			                <div class="chart"><img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" /></div>
			                <div class="title"><b>QRS Hit Rate</b></div>

			                <div id="Handle2" class="handle" style="display:block;" onclick="showSummary(2);"></div>

			                <div id="Summary2" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">QRS Hit Rate</div>
			                    <div class="handle" onclick="hideSummary(2);"></div>
			                  </div>
			                </div>
			              </div>
