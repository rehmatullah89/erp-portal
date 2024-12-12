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
	$objChart->setPlotArea(80, 70, 780, 380);

	$objChart->addTitle("Quonda Defects Classification", "verdana.ttf", 20);
	$objChart->setColors2(8, $sColors);


	$objLineLayer = $objChart->addLineLayer2( );

	$objLineData = new ArrayMath($iTotalDefects);
	$objLineData->acc( );

	$fScaleFactor = (100 / $objLineData->max( ));
	$objLineData->mul2($fScaleFactor);

	$objDataSet = $objLineLayer->addDataSet($objLineData->result( ), 0xff0000);
	$objDataSet->setDataSymbol(SquareSymbol, 6);

	$objLineLayer->setLineWidth(2);
	$objLineLayer->setDataLabelFormat("{value|2}%");


	$objBarLayer = $objChart->addBarLayer3($iTotalDefects);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setAggregateLabelStyle( );
	$objBarLayer->setAggregateLabelFormat("{value}");

	$objBarLayer->setUseYAxis2( );

	$objLabels = $objChart->xAxis->setLabels($sDefectTypes);
	$objLabels->setFontAngle(90);

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);
	$objChart->yAxis->setLinearScale(0, 100, 10);
	$objChart->yAxis->setLabelFormat("{value}%");
	$objChart->yAxis2->setLabelFormat("{value|0}");
	$objChart->syncYAxis(1 / $fScaleFactor);

	$sChart = $objChart->makeSession("DefectClassBar");
?>

			      <br />
			      <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" />
