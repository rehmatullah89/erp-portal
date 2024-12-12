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

	$iTotalOrders   = array(0);
	$iOnTimeOrders  = array(0);
	$iOffTimeOrders = array(0);
	$sLabels        = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

	$iYear = (int)@substr($FromDate, 0, 4);

	if ($iYear == 0)
		$iYear = date("Y");


	$sSQL = "SELECT COUNT(DISTINCT(po.id)) AS _Orders, DATE_FORMAT(pc.etd_required, '%m') AS _Month
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
			 WHERE po.id=pc.po_id AND (pc.etd_required BETWEEN '$iYear-01-01' AND '$iYear-12-31')
			       AND pc.style_id=s.id AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')";

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";


	if ($Region > 0)
		$sSQL .= "AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";


	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";


	if ($Season > 0)
		$sSQL .= " AND s.sub_season_id='$Season' ";

	$sSQL .= " GROUP BY DATE_FORMAT(pc.etd_required, '%Y-%m') ";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iOrders = $objDb->getField($i, "_Orders");
		$sMonth  = $objDb->getField($i, "_Month");

		$iMonth = (intval($sMonth) - 1);

		$iTotalOrders[$iMonth] = $iOrders;
	}



	$sSQL = "SELECT COUNT(DISTINCT(po.id)) AS _Orders, DATE_FORMAT(pc.etd_required, '%m') AS _Month
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s, tbl_pre_shipment_detail psd
			 WHERE po.id=pc.po_id AND po.id=psd.po_id AND (pc.etd_required BETWEEN '$iYear-01-01' AND '$iYear-12-31')
			       AND psd.handover_to_forwarder <= pc.etd_required AND psd.handover_to_forwarder != '0000-00-00' AND NOT ISNULL(psd.handover_to_forwarder)
			       AND pc.style_id=s.id AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}') ";

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";


	if ($Region > 0)
		$sSQL .= "AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";


	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";


	if ($Season > 0)
		$sSQL .= " AND s.sub_season_id='$Season' ";

	$sSQL .= " GROUP BY DATE_FORMAT(pc.etd_required, '%Y-%m') ";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iOrders = $objDb->getField($i, "_Orders");
		$sMonth  = $objDb->getField($i, "_Month");

		$iMonth = (intval($sMonth) - 1);

		$iOnTimeOrders[$iMonth] = $iOrders;
	}


	for ($i = 0; $i < 12; $i ++)
		$iOffTimeOrders[$i] = ($iTotalOrders[$i] - $iOnTimeOrders[$i]);



	$objChart = new XYChart(296, 201);
	$objChart->setPlotArea(35, 30, 250, 140);

	$objBarLayer = $objChart->addBarLayer2(Stack);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->addDataSet($iOnTimeOrders, 0xeeeeee, "On-Time Orders");
	$objBarLayer->addDataSet($iOffTimeOrders, 0x999999, "Off-Time Orders");

	$objBarLayer->setAggregateLabelStyle("tahoma.ttf", 7, 0x000000);
	$objBarLayer->setDataLabelStyle("tahoma.ttf", 7, 0x000000);

	$objChart->xAxis->setLabels($sLabels);
	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$objLegend = $objChart->addLegend(50, 2, false, "tahoma.ttf", 8);
	$objLegend->setBackground(Transparent);

	$sChart = $objChart->makeSession("LateDelivery");
?>
			              <div class="vsrChart">
			                <div class="chart"><img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" /></div>
			                <div class="title"><b>Late Delivery</b></div>

			                <div id="Handle6" class="handle" style="display:block;" onclick="showSummary(6);"></div>

			                <div id="Summary6" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">Late Delivery</div>
			                    <div class="handle" onclick="hideSummary(6);"></div>
			                  </div>
			                </div>
			              </div>
