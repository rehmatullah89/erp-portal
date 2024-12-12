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

	$iOrderQty  = array( );
	$iOnTimeQty = array( );
	$sFromDate  = date("Y-m-d");
	$sToDate    = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") + 14), date("Y")));


	$sSQL = "SELECT po.brand_id, SUM(pc.order_qty)
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
			 WHERE po.id=pc.po_id AND pc.style_id=s.id AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}') AND po.order_nature='B'";

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) AND po.vendor_id!='194' ";


	if ($Region > 0)
		$sSQL .= "AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";


	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) AND NOT FIND_IN_SET(po.brand_id, '130,77,167') ";


	if ($Season > 0)
		$sSQL .= " AND s.sub_season_id='$Season' ";


	$sSQL .= " AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')
			   GROUP BY po.brand_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 1);

		$iOrderQty[$iBrandId] = $iQuantity;
	}


	$sSQL = "SELECT po.brand_id, SUM(pc.order_qty)
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s, tbl_vsr vsr
			 WHERE po.id=pc.po_id AND po.id=vsr.po_id AND pc.style_id=s.id AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}') AND po.order_nature='B'";

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) AND po.vendor_id!='194' ";


	if ($Region > 0)
		$sSQL .= "AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";


	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) AND NOT FIND_IN_SET(po.brand_id, '130,77,167') ";


	if ($Season > 0)
		$sSQL .= " AND s.sub_season_id='$Season' ";


	$sSQL .= " AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')
	           AND vsr.final_audit_date != '0000-00-00' AND NOT ISNULL(vsr.final_audit_date)
	           AND pc.etd_required >= vsr.final_audit_date
			   GROUP BY po.brand_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 1);

		$iOnTimeQty[$iBrandId] = $iQuantity;
	}


	$fOtp = @round(( (@array_sum($iOnTimeQty) / @array_sum($iOrderQty)) * 100), 2);



	$objChart = new AngularMeter(306, 201);

	$objChart->setMeter(153, 98, 80, -135, 135);
	$objChart->setScale(0, 100, 10, 5, 1);
	$objChart->setLineWidth(0, 2, 1);

	$objChart->addRing(0, 85, metalColor(0xcccccc));
	$objChart->addRing(83, 85, 0x888888);

	$objChart->addZone(0, 50, 0xff3333);
	$objChart->addZone(50, 80, 0xffff00);
	$objChart->addZone(80, 100, 0x99ff99);

	$objChart->addText(153, 145, "OTP", "arialbd.ttf", 14, 0x555555, Center);
	$objChart->addText(2, 182, ("Order Qty = ".@array_sum($iOrderQty)), "tahoma.ttf", 8, 0x777777);
	$objChart->addText(175, 182, ("OnTime Qty  = ".@array_sum($iOnTimeQty)), "tahoma.ttf", 8, 0x777777);

	$objTextbox = $objChart->addText(155, 165, $objChart->formatValue($fOtp, "2"), "verdana", 9, 0xffffff, Center);
	$objTextbox->setBackground(0x000000, 0x000000, 0);

	$objChart->addPointer($fOtp, 0x40333399);

	$sChart = $objChart->makeSession("OtpFortnight");
?>
			              <div class="vsrChart">
			                <div class="chart">
<?
	if (checkUserRights("current-standing.php", "Reports", "view"))
	{
?>
			                  <a href="reports/current-standing.php?FromDate=<?= $sFromDate ?>&ToDate=<?= $sToDate ?>"><img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" /></a>
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

			                <div class="title"><b>OTP of next Fortnight</b></div>

			                <div id="Handle2" class="handle" style="display:block;" onclick="showSummary(2);"></div>

			                <div id="Summary2" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">
			                      <b>On-Time Performance of next 15 days</b><br />
			                      <br />

			                      <div style="overflow:auto; height:170px; _height:195px; #height:195px;">
			                        <table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="100%" style="font-size:10px;">
			                          <tr bgcolor="#d6d6d6">
			                            <td width="40%"><b>Brand</b></td>
			                            <td width="20%"><b>Qty</b></td>
			                            <td width="22%"><b>On-Time</b></td>
			                            <td width="18%"><b>OTP</b></td>
			                          </tr>
<?
	$sData  = array( );
	$iIndex = 0;

	foreach ($iOrderQty AS $iBrandId => $iQuantity)
	{
		$sData['Brand'][$iIndex]     = $sBrandsList[$iBrandId];
		$sData['OrderQty'][$iIndex]  = $iQuantity;
		$sData['OnTimeQty'][$iIndex] = $iOnTimeQty[$iBrandId];
		$sData['OTP'][$iIndex]       = @round(( ($sData['OnTimeQty'][$iIndex] / $sData['OrderQty'][$iIndex]) * 100), 2);
		$sData['BrandId'][$iIndex]   = $iBrandId;

		$iIndex ++;
	}

	@array_multisort($sData['OTP'], SORT_DESC,
					 $sData['Brand'],
					 $sData['OrderQty'],
					 $sData['OnTimeQty'],
					 $sData['BrandId']);


	for ($i = 0; $i < count($sData['Brand']); $i ++)
	{
		if ($sData['OTP'][$i] > 80 && $sData['OTP'][$i] <= 100)
			$sColor = "#99ff99";

		else if ($sData['OTP'][$i] > 50 && $sData['OTP'][$i] <= 80)
			$sColor = "#ffff00";

		else
			$sColor = "#ff3333";
?>

			                          <tr bgcolor="<?= $sColor ?>">
			                            <td><?= $sData['Brand'][$i] ?></td>
			                            <td><?= formatNumber($sData['OrderQty'][$i], false) ?></td>
			                            <td><?= formatNumber($sData['OnTimeQty'][$i], false) ?></td>
			                            <td><a href="reports/current-standing.php?Vendor=&Brand=<?= $sData['BrandId'][$i] ?>&FromDate=<?= $sFromDate ?>&ToDate=<?= $sToDate ?>&Filter=Late" target="_blank"><?= formatNumber($sData['OTP'][$i]) ?></a></td>
			                          </tr>
<?
	}
?>
			                        </table>
			                      </div>
			                    </div>

			                    <div class="handle" onclick="hideSummary(2);"></div>
			                  </div>
			                </div>
			              </div>
