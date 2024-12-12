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

	@require_once("../requires/session.php");
	@require_once("../requires/chart.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id = IO::intValue('Id');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body">
<?
	$sSQL = "SELECT sizes FROM tbl_po WHERE id='$Id'";
	$objDb->query($sSQL);

	$sSizes = $objDb->getField(0, 0);


	$iOrderData = array( );
	$iShipData  = array( );
	$sLabels    = array( );

	$sSQL = "SELECT size,
	                (SELECT COALESCE(SUM(quantity), 0) FROM tbl_po_quantities WHERE po_id='$Id' AND size_id=tbl_sizes.id) AS _OrderQty,
	                (SELECT COALESCE(SUM(quantity), 0) FROM tbl_pre_shipment_quantities WHERE po_id='$Id' AND size_id=tbl_sizes.id) AS _ShipQty
	         FROM tbl_sizes
	         WHERE id IN ($sSizes)
	         ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sLabels[]    = $objDb->getField($i, 'size');
		$iOrderData[] = (int)$objDb->getField($i, '_OrderQty');
		$iShipData[]  = (int)$objDb->getField($i, '_ShipQty');
	}

	$fDeviation = (@((@array_sum($iShipData) / @array_sum($iOrderData)) * 100) - 100);

	$objChart = new XYChart(696, 420);

	$objChart->addTitle(("PO Deviation Report (".formatNumber($fDeviation)."%)"), "verdana.ttf", 20);
	$objChart->setPlotArea(50, 75, 610, 320);

	$objBarLayer = $objChart->addBarLayer2(Side);
	$objBarLayer->addDataSet($iOrderData, 0xff0000, 'Order Qty');
	$objBarLayer->addDataSet($iShipData, 0x00ff00, 'Shipped Qty');
	$objBarLayer->setAggregateLabelStyle( );

	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setBarGap(0.25, TouchBar);

	$objLegend = $objChart->addLegend(50, 45, false, "verdana.ttf", 8);
	$objLegend->setBackground(Transparent);

	$objChart->xAxis->setLabels($sLabels);
	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("Deviation");
?>
	  <center><img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" vspace="12" /></center>
	  <br style="line-height:2px;" />
    </div>
<!--  Body Section Ends Here  -->

  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>