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

	$sBrandSql  = "";
	$sSeasonSql = "";

	if ($Brand > 0)
		$sBrandSql = " sub_brand_id='$Brand' ";

	else
		$sBrandSql = " sub_brand_id IN ({$_SESSION['Brands']}) ";

	if ($Season > 0)
		$sSeasonSql = " AND sub_season_id='$Season' ";


	$sSQL = "SELECT COUNT(DISTINCT(style_id))
	         FROM tbl_merchandisings
	         WHERE (sent_2_sampling BETWEEN '$FromDate' AND '$ToDate') AND style_id IN (SELECT id FROM tbl_styles WHERE $sBrandSql $sSeasonSql AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}'))";
	$objDb->query($sSQL);

	$iTotalSamples  = $objDb->getField(0, 0);



	$sSQL = "SELECT COUNT(DISTINCT(pc.style_id))
	         FROM tbl_styles s, tbl_po_colors pc, tbl_merchandisings m
	         WHERE s.id=pc.style_id AND s.id=m.style_id AND (m.sent_2_sampling BETWEEN '$FromDate' AND '$ToDate') AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')";

	if ($Brand > 0)
		$sSQL .= " AND s.sub_brand_id='$Brand' ";

	else
		$sSQL .= " AND s.sub_brand_id IN ({$_SESSION['Brands']}) ";

	if ($Season > 0)
		$sSQL .= " AND s.sub_season_id='$Season' ";

	$objDb->query($sSQL);

	$iSampleOrders  = $objDb->getField(0, 0);


	$fPerformance = @(($iSampleOrders / $iTotalSamples) * 100);



	$objChart = new AngularMeter(306, 201);

	$objChart->setMeter(153, 98, 80, -135, 135);
	$objChart->setScale(0, 100, 10, 5, 1);
	$objChart->setLineWidth(0, 2, 1);

	$objChart->addRing(0, 85, metalColor(0xcccccc));
	$objChart->addRing(83, 85, 0x888888);

	$objChart->addZone(0, 20, 0xff3333);
	$objChart->addZone(20, 50, 0xeb8d8d);
	$objChart->addZone(50, 80, 0xffff00);
	$objChart->addZone(80, 100, 0x99ff99);

	$objChart->addText(153, 145, "OCP", "arialbd.ttf", 14, 0x555555, Center);
	$objChart->addText(2, 182, "Samples = {$iTotalSamples}", "tahoma.ttf", 8, 0x777777);
	$objChart->addText(210, 182, "Orders  = {$iSampleOrders}", "tahoma.ttf", 8, 0x777777);

	$objTextbox = $objChart->addText(155, 165, $objChart->formatValue($fPerformance, "2"), "verdana", 9, 0xffffff, Center);
	$objTextbox->setBackground(0x000000, 0x000000, 0);

	$objChart->addPointer($fPerformance, 0x40333399);

	$sChart = $objChart->makeSession("OrderConfirmation");
?>
			              <div class="vsrChart">
			                <div class="chart"><img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" /></div>
			                <div class="title"><b>Order Confirmation Performance</b></div>

			                <div id="Handle4" class="handle" style="display:block;" onclick="showSummary(4);"></div>

			                <div id="Summary4" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">
			                      <b>Order Confirmation Performance</b><br />
			                      This refers to the time factory takes to confirm bulk orders, should not be more than 48 hours from receipt of bulk buy.<br />
			                    </div>

			                    <div class="handle" onclick="hideSummary(4);"></div>
			                  </div>
			                </div>
			              </div>
