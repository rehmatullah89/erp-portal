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

	$objChart = new XYChart(920, 650);
	$objChart->setPlotArea(80, 90, 780, 380);

	if ($Brand > 0)
		$objChart->addTitle("Sampling Defects Classification ({$sBrandsList[$Brand]})", "verdana.ttf", 20);

	else
		$objChart->addTitle("Sampling Defects Classification", "verdana.ttf", 20);

	if ($sSubTitle != "")
		$objChart->addText(460, 35, $sSubTitle, "verdanab.ttf", 10, 0x555555, 8);

	if ($iCount > 0)
	{
		$objLineLayer = $objChart->addLineLayer2( );

		$objLineData = new ArrayMath($sData);
		$objLineData->acc( );

		$fScaleFactor = (100 / $objLineData->max( ));
		$objLineData->mul2($fScaleFactor);

		$objDataSet = $objLineLayer->addDataSet($objLineData->result( ), 0xff0000);
		$objDataSet->setDataSymbol(SquareSymbol, 6);

		$objLineLayer->setLineWidth(2);
		$objLineLayer->setDataLabelFormat("{value|2}%");


		$objBarLayer = $objChart->addBarLayer3($sData);
		$objBarLayer->setBarShape(CircleShape);
		$objBarLayer->setAggregateLabelStyle( );
		$objBarLayer->setAggregateLabelFormat("{value}");

		$objBarLayer->setUseYAxis2( );

		$objLabels = $objChart->xAxis->setLabels($sLabels);
		$objLabels->setFontAngle(90);

		$objChart->xAxis->setWidth(2);
		$objChart->yAxis->setWidth(2);
		$objChart->yAxis->setLinearScale(0, 100, 10);
		$objChart->yAxis->setLabelFormat("{value}%");
		$objChart->yAxis2->setLabelFormat("{value|0}");
		$objChart->syncYAxis(1 / $fScaleFactor);
	}

	$sChart = $objChart->makeSession("DefectClassBar");

	$objChart->addExtraField($iDefectType);

	$sImageMap = $objChart->getHTMLImageMap($_SERVER['PHP_SELF'], "Brand={$Brand}&Type={$Type}&FromDate={$FromDate}&ToDate={$ToDate}&DefectType={field0}", "title='{label} = {value} Defects ({percent}%)'");
?>

			      <br />
			      <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" usemap="#DefectClassBarMap" />

				  <map name="DefectClassBarMap">
				    <?= $sImageMap ?>
				  </map>
