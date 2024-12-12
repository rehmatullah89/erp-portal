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

	$iOrderQty = array( );
	$iShipQty  = array( );


	$sSQL = "SELECT po.brand_id, SUM(pq.quantity)
			 FROM tbl_po po, tbl_po_quantities pq, tbl_po_colors pc, tbl_styles s
			 WHERE po.id=pq.po_id AND po.id=pc.po_id AND pc.id=pq.color_id AND po.status='C' AND pc.style_id=s.id AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}') ";

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


	$sSQL .= " AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate') AND pc.etd_required <= CURDATE( )
			   GROUP BY po.brand_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 1);

		$iOrderQty[$iBrandId] = $iQuantity;
	}



	$sSQL = "SELECT po.brand_id, SUM(psq.quantity)
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
			 WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.status='C'
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


	$sSQL .= " AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate') AND pc.etd_required <= CURDATE( )
			   GROUP BY po.brand_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 1);

		$iShipQty[$iBrandId] = $iQuantity;
	}


	$fDeviation = (@((@array_sum($iShipQty) / @array_sum($iOrderQty)) * 100) - 100);



	$objChart = new AngularMeter(306, 201);

	$objChart->setMeter(153, 98, 80, -135, 135);
	$objChart->setScale(-100, 30, 10, 5, 1);
	$objChart->setLineWidth(0, 2, 1);

	$objChart->addRing(0, 85, metalColor(0xcccccc));
	$objChart->addRing(83, 85, 0x888888);

	$objChart->addZone(-100, -50, 0xff3333);
	$objChart->addZone(-50, 0, 0xffff00);
	$objChart->addZone(0, 10, 0x99ff99);
	$objChart->addZone(10, 30, 0x59ca59);

	$objChart->addText(153, 145, "DEV", "arialbd.ttf", 14, 0x555555, Center);
	$objChart->addText(2, 182, ("Order Qty = ".@array_sum($iOrderQty)), "tahoma.ttf", 8, 0x777777);
	$objChart->addText(190, 182, ("Ship Qty  = ".@array_sum($iShipQty)), "tahoma.ttf", 8, 0x777777);

	$objTextbox = $objChart->addText(155, 165, $objChart->formatValue($fDeviation, "2"), "verdana", 9, 0xffffff, Center);
	$objTextbox->setBackground(0x000000, 0x000000, 0);

	$objChart->addPointer($fDeviation, 0x40333399);

	$sChart = $objChart->makeSession("Deviation");
?>
			              <div class="vsrChart">
			                <div class="chart">
<?
	if (checkUserRights("deviation.php", "VSR", "view"))
	{
?>
			                  <a href="vsr/deviation.php?Vendor=<?= $Vendor ?>&Brand=<?= $Brand ?>&Region=<?= $Region ?>&Season=<?= $Season ?>&FromDate=<?= $FromDate ?>&ToDate=<?= $ToDate ?>"><img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" /></a>
<?
	}

	else
	{
?>
			                  <img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" />
<?
	}
?>
			                </div>

			                <div class="title"><b>Deviation</b></div>

			                <div id="Handle3" class="handle" style="display:block;" onclick="showSummary(3);"></div>

			                <div id="Summary3" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">
			                      <b>Deviation</b><br />
			                      <br />

			                      <div style="overflow:auto; height:170px; _height:195px; #height:195px;">
			                        <table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="100%" style="font-size:10px;">
			                          <tr bgcolor="#d6d6d6">
			                            <td width="40%"><b>Brand</b></td>
			                            <td width="20%"><b>Orderd</b></td>
			                            <td width="22%"><b>Shiped</b></td>
			                            <td width="18%"><b>Dev</b></td>
			                          </tr>
<?
	$sData  = array( );
	$iIndex = 0;

	foreach ($iOrderQty AS $iBrandId => $iQuantity)
	{
		$sData['Brand'][$iIndex]     = $sBrandsList[$iBrandId];
		$sData['OrderQty'][$iIndex]  = $iQuantity;
		$sData['ShipQty'][$iIndex]   = $iShipQty[$iBrandId];
		$sData['Deviation'][$iIndex] = @round((( ($sData['ShipQty'][$iIndex] / $sData['OrderQty'][$iIndex]) * 100) - 100), 2);

		$iIndex ++;
	}

	@array_multisort($sData['Deviation'], SORT_DESC,
					 $sData['Brand'],
					 $sData['OrderQty'],
					 $sData['ShipQty']);


	for ($i = 0; $i < count($sData['Brand']); $i ++)
	{
		if ($sData['Deviation'][$i] < -50)
			$sColor = "#ff3333";

		else if ($sData['Deviation'][$i] < 0)
			$sColor = "#ffff00";

		else if ($sData['Deviation'][$i] < 10)
			$sColor = "#99ff99";

		else
			$sColor = "#59ca59";
?>

			                          <tr bgcolor="<?= $sColor ?>">
			                            <td><?= $sData['Brand'][$i] ?></td>
			                            <td><?= formatNumber($sData['OrderQty'][$i], false) ?></td>
			                            <td><?= formatNumber($sData['ShipQty'][$i], false) ?></td>
			                            <td><?= formatNumber($sData['Deviation'][$i]) ?></td>
			                          </tr>
<?
	}
?>
			                        </table>
			                      </div>
			                    </div>

			                    <div class="handle" onclick="hideSummary(3);"></div>
			                  </div>
			                </div>
			              </div>
