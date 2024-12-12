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

	$sSQL = "SELECT month, year, salary FROM tbl_user_salaries WHERE user_id='$Id' ORDER BY year, month";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sData[]   = $objDb->getField($i, "salary");
		$sLabels[] = ($sMonths[$objDb->getField($i, "month")]." ".substr($objDb->getField($i, "year"), 2));
	}

	$objChart = new XYChart(462, 240);
	$objChart->setPlotArea(50, 59, 390, 150, 0xffffff, -1, -1, 0xcccccc, 0xcccccc);

	$objTitle = $objChart->addTitle("\nSalary Increments History", "verdanab.ttf", 10);
	$objTitle->setPos(0,0);

	$objLayer = $objChart->addLineLayer( );
	$objLayer->setLineWidth(1);
	$objLayer->setDataLabelFormat("{value}", "tahoma.ttf", 7);

	$objDataSet = $objLayer->addDataSet($sData);
	$objDataSet->setDataSymbol(SquareSymbol, 6);

	$objChart->xAxis->setLabels($sLabels);
	$objChart->xAxis->setLabelStyle("tahoma.ttf", 7);

	$objChart->yAxis->setLabelFormat("{value}");
	$objChart->yAxis->setLabelStyle("tahoma.ttf", 7);

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("Salary");
?>

	  			  <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" />
