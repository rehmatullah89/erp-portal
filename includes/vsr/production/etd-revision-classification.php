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

	$sData       = array( );
	$sLabels     = array( );
	$sReasons    = array( );
	$iReasons    = array( );
	$sConditions = "";


	if ($Vendor > 0)
		$sConditions .= " AND po.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";


	if ($Region > 0)
		$sConditions .= "AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";


	$sSQL = "SELECT pc.po_id
	         FROM tbl_po_colors pc, tbl_styles s
	         WHERE pc.style_id=s.id AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}') ";

	if ($Brand > 0)
		$sSQL .= " AND s.sub_brand_id='$Brand' ";

	else
		$sSQL .= " AND s.sub_brand_id IN ({$_SESSION['Brands']}) ";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$sPos   = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sPos .= (",".$objDb->getField($i, 0));

	if ($sPos != "")
		$sPos = substr($sPos, 1);

	$sConditions .= " AND po.id IN ($sPos) ";



	$sSQL = "SELECT err.reason_id, COUNT(*)
			 FROM tbl_etd_revision_requests err, tbl_po po
			 WHERE err.po_id=po.id AND (DATE_FORMAT(err.date_time, '%Y-%m-%d') BETWEEN '$FromDate' AND '$ToDate') AND err.reason_id>'0' $sConditions
			 GROUP BY err.reason_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iReasonId = $objDb->getField($i, 0);
		$iRequests = $objDb->getField($i, 1);


		$sSQL = "SELECT parent_id FROM tbl_etd_revision_reasons WHERE id=(SELECT parent_id FROM tbl_etd_revision_reasons WHERE id='$iReasonId')";
		$objDb2->query($sSQL);

		$iReasonId = $objDb2->getField(0, 0);


		$sReasons[$iReasonId] += $iRequests;
	}


	foreach ($sReasons as $iReasonId => $iRequests)
	{
		$sSQL = "SELECT reason FROM tbl_etd_revision_reasons WHERE id='$iReasonId'";
		$objDb->query($sSQL);

		$iReasons[] = $iReasonId;
		$sData[]    = $iRequests;
		$sLabels[]  = $objDb->getField(0, 0);
	}


	$objChart = new XYChart(296, 201);
	$objChart->setPlotArea(35, 20, 245, 160);

	$objBarLayer = $objChart->addBarLayer3($sData);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setAggregateLabelStyle( );
	$objBarLayer->setAggregateLabelFormat("{value}");

	$objChart->xAxis->setLabelStyle("tahoma.ttf", 7);
	$objLabels = $objChart->xAxis->setLabels($sLabels);

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("EtdRevisionClassification");

	$objChart->addExtraField($sLabels);
	$objChart->addExtraField($iReasons);

	if (checkUserRights("etd-revisions.php", "VSR", "view"))
		$sImageMap = $objChart->getHTMLImageMap("vsr/etd-revisions.php", ("Vendor={$Vendor}&Brand={$Brand}&FromDate={$FromDate}&ToDate={$ToDate}&Region={$Region}&Parent={field1}&Step=1\""), "title='{field0} = {value} Requests'");
?>
			              <div class="vsrChart">
			                <div class="chart"><img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" usemap="#EtdRevisionMap" /></div>
			                <div class="title"><b>ETD Revision Classification</b></div>

			                <div id="Handle6" class="handle" style="display:block;" onclick="showSummary(6);"></div>

			                <div id="Summary6" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">ETD Revision Classification</div>
			                    <div class="handle" onclick="hideSummary(6);"></div>
			                  </div>
			                </div>

						    <map name="EtdRevisionMap">
							  <?= $sImageMap ?>
						    </map>
			              </div>
