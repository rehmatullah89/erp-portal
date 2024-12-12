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

	$sData = array( );

	$sSQL = "SELECT po.brand_id, DATEDIFF(MAX(etd.revised), MIN(etd.original))
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s, tbl_etd_revisions etd
			 WHERE po.id=pc.po_id AND po.id=etd.po_id AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate')
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

	$sSQL .= "GROUP BY etd.po_id
	          HAVING DATEDIFF(MAX(etd.revised), MIN(etd.original)) > 0";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId    = $objDb->getField($i, 0);
		$iDifference = $objDb->getField($i, 1);

		if (!isset($sData['Week1'][$iBrandId]) && !isset($sData['Week2'][$iBrandId]) && !isset($sData['Week3'][$iBrandId]) && !isset($sData['Week3p'][$iBrandId]))
		{
			$sData['Brands'][$iBrandId] = $sBrandsList[$iBrandId];
			$sData['Week1'][$iBrandId]  = 0;
			$sData['Week2'][$iBrandId]  = 0;
			$sData['Week3'][$iBrandId]  = 0;
			$sData['Week3p'][$iBrandId] = 0;
		}


		if ($iDifference >= 1 && $iDifference <= 7)
			$sData['Week1'][$iBrandId] ++;

		else if ($iDifference >= 8 && $iDifference <= 14)
			$sData['Week2'][$iBrandId] ++;

		else if ($iDifference >= 15 && $iDifference <= 21)
			$sData['Week3'][$iBrandId] ++;

		else if ($iDifference >= 22)
			$sData['Week3p'][$iBrandId] ++;
	}


	$sBrands = array( );
	$iWeek1  = array( );
	$iWeek2  = array( );
	$iWeek3  = array( );
	$iWeek3p = array( );
	$iIndex  = 0;

	foreach ($sData['Brands'] as $iBrandId => $sBrand)
	{
		if ($sData['Week1'][$iBrandId] > 0 || $sData['Week2'][$iBrandId] > 0 || $sData['Week3'][$iBrandId] > 0 || $sData['Week3p'][$iBrandId] > 0)
		{
			$sBrands[$iIndex] = $sBrand;
			$iWeek1[$iIndex]  = $sData['Week1'][$iBrandId];
			$iWeek2[$iIndex]  = $sData['Week2'][$iBrandId];
			$iWeek3[$iIndex]  = $sData['Week3'][$iBrandId];
			$iWeek3p[$iIndex] = $sData['Week3p'][$iBrandId];

			$iIndex ++;
		}
	}


	$objChart = new XYChart(296, 201);
	$objChart->setPlotArea(25, 30, 260, 120);

	$objBarLayer = $objChart->addBarLayer2(Stack);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->addDataSet($iWeek1, 0x99ff99, "1 Week");
	$objBarLayer->addDataSet($iWeek2, 0xffff00, "2 Week");
	$objBarLayer->addDataSet($iWeek3, 0xeb8d8d, "3 Week");
	$objBarLayer->addDataSet($iWeek3p, 0xff3333, "3+ Week");

	$objBarLayer->setAggregateLabelStyle("tahoma.ttf", 7, 0x000000);
	$objBarLayer->setDataLabelStyle("tahoma.ttf", 7, 0x000000);

	$objChart->xAxis->setLabelStyle("tahoma.ttf", 7);
	$objLabels = $objChart->xAxis->setLabels($sBrands);
	$objLabels->setFontAngle(70);


	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$objLegend = $objChart->addLegend(2, 2, false, "tahoma.ttf", 8);
	$objLegend->setBackground(Transparent);

	$sChart = $objChart->makeSession("EtdRevisions");
?>
			              <div class="vsrChart">
			                <div class="chart"><img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" /></div>
			                <div class="title"><b>ETD Revisions</b></div>

			                <div id="Handle5" class="handle" style="display:block;" onclick="showSummary(5);"></div>

			                <div id="Summary5" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">
			                      <b>ETD Revisions</b><br />
			                      <br />

			                      <div style="overflow:auto; height:170px; _height:195px; #height:195px;">
			                        <table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="100%" style="font-size:10px;">
			                          <tr bgcolor="#d6d6d6">
			                            <td width="40%"><b>Brand</b></td>
			                            <td width="12%"><b>POs</b></td>
			                            <td width="12%"><b>1W</b></td>
			                            <td width="12%"><b>2W</b></td>
			                            <td width="12%"><b>3W</b></td>
			                            <td width="12%"><b>3+W</b></td>
			                          </tr>
<?
	foreach ($sData['Brands'] as $iBrandId => $sBrand)
	{
?>

			                          <tr bgcolor="#fcfcfc">
			                            <td><?= $sBrand ?></td>
			                            <td><?= formatNumber(($sData['Week1'][$iBrandId] + $sData['Week2'][$iBrandId] + $sData['Week3'][$iBrandId] + $sData['Week3p'][$iBrandId]), false) ?></td>
			                            <td><?= formatNumber($sData['Week1'][$iBrandId], false) ?></td>
			                            <td><?= formatNumber($sData['Week2'][$iBrandId], false) ?></td>
			                            <td><?= formatNumber($sData['Week3'][$iBrandId], false) ?></td>
			                            <td><?= formatNumber($sData['Week3p'][$iBrandId], false) ?></td>
			                          </tr>
<?
	}
?>
			                        </table>
			                      </div>
			                    </div>

			                    <div class="handle" onclick="hideSummary(5);"></div>
			                  </div>
			                </div>
			              </div>
