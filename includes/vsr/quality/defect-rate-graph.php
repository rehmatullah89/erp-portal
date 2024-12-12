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
	$sMonths = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

	$iYear = (int)@substr($ToDate, 0, 4);

	if ($iYear == 0)
		$iYear = date("Y");

	for ($i = 1; $i <= 12; $i ++)
	{
		$iMonth = str_pad($i, 2, '0', STR_PAD_LEFT);
		$iDays  = @cal_days_in_month(CAL_GREGORIAN, $iMonth, $iYear);

		$sConditions2 = $sConditions;
		$sConditions2 = str_replace("'$FromDate' AND '$ToDate'", "'$iYear-$iMonth-01' AND '$iYear-$iMonth-$iDays'", $sConditions2);


		$sSQL = "SELECT DISTINCT(qa.id) FROM tbl_qa_reports qa WHERE qa.audit_stage!='' $sConditions2";
		$objDb->query($sSQL);

		$iCount      = $objDb->getCount( );
		$sAuditCodes = "";

		for ($j = 0; $j < $iCount; $j ++)
			$sAuditCodes .= (",".$objDb->getField($j, 0));

		if ($sAuditCodes != "")
			$sAuditCodes = substr($sAuditCodes, 1);


		$sSQL = "SELECT ROUND(AVG(dhu), 2) FROM tbl_qa_reports WHERE id IN ($sAuditCodes)";
		$objDb->query($sSQL);

		$fDhu = $objDb->getField(0, 0);

		$sLabels[] = $sMonths[($i - 1)];
		$sData[]   = $fDhu;
	}

	$objChart = new XYChart(296, 201);
	$objChart->setPlotArea(32, 20, 250, 150, 0xffffff, 0xffffff, 0x000000, $objChart->dashLineColor(0xcccccc, DotLine), $objChart->dashLineColor(0xcccccc, DotLine));

	$objLayer = $objChart->addLineLayer( );
	$objLayer->setLineWidth(2);
	$objLayer->setDataLabelFormat("{value}%");

	$objDataSet = $objLayer->addDataSet($sData);
	$objDataSet->setDataSymbol(SquareSymbol, 7);

	$objChart->xAxis->setLabels($sLabels);
	$objChart->yAxis->setLabelFormat("{value}%");

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("DefectRate");
?>
			              <div class="vsrChart">
			                <div class="chart"><img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" /></div>
			                <div class="title"><b>Defects Rate</b></div>

			                <div id="Handle3" class="handle" style="display:block;" onclick="showSummary(3);"></div>

			                <div id="Summary3" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">Defects Rate</div>
			                    <div class="handle" onclick="hideSummary(3);"></div>
			                  </div>
			                </div>
			              </div>
