<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2008-15 (C) Triple Tree                                                        **
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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Mode     = IO::strValue("Mode");
	$Region   = IO::intValue("Region");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$Month    = IO::intValue("Month");
	$Vendors  = IO::strValue("Vendors");
	$Brands   = IO::strValue("Brands");
	$PoType   = IO::strValue("PoType");

	$iYear = (int)@substr($ToDate, 0, 4);

	if ($iYear == 0)
		$iYear = date("Y");

	$iMonth = str_pad($Month, 2, '0', STR_PAD_LEFT);
	$iDays  = @cal_days_in_month(CAL_GREGORIAN, $iMonth, $iYear);

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})");

	$sHtml = "";

	if ($Mode == "Vendors" || $Mode == "VendorsBrands")
	{
		$sHtml .= '<table border="0" cellpadding="2" cellspacing="0" width="100%">';
		$sHtml .= '<tr valign="top">';
		$sHtml .= ('<td width="28%"><b>Vendor</b></td>');
		$sHtml .= ('<td width="24%"><b>Shipments</b></td>');
		$sHtml .= ('<td width="11%"><b>Original<br />Forecast</b></td>');
		$sHtml .= ('<td width="11%"><b>Revised<br />Forecast</b></td>');
		$sHtml .= ('<td width="13%"><b>Placements</b></td>');
		$sHtml .= ('<td width="13%"><b>Shipments</b></td>');
		$sHtml .= '</tr>';


		$iPlacements = array( );
		$iShipments  = array( );
		$iForecast   = array( );
		$iRevised    = array( );

		$sSQL = "SELECT po.vendor_id, COALESCE(SUM(pc.order_qty), 0)
				 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
				 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
				 		AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
				 		AND po.vendor_id IN ($Vendors)
				 		AND po.brand_id IN ($Brands)
				 		AND (pc.etd_required BETWEEN '$iYear-$iMonth-01' AND '$iYear-$iMonth-$iDays')";

		if ($PoType != "")
			$sSQL .= " AND po.order_type='$PoType' ";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY po.vendor_id";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iVendorId = $objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iPlacements[$iVendorId] = $iQuantity;
		}



		$sSQL = "SELECT po.vendor_id, COALESCE(SUM(psq.quantity), 0)
				 FROM tbl_po po, tbl_po_colors pc, tbl_styles s, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B'
				 		AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
				 		AND po.vendor_id IN ($Vendors)
				 		AND po.brand_id IN ($Brands)
				 		AND (pc.etd_required BETWEEN '$iYear-$iMonth-01' AND '$iYear-$iMonth-$iDays')";

		if ($PoType != "")
			$sSQL .= " AND po.order_type='$PoType' ";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY po.vendor_id";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iVendorId = $objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iShipments[$iVendorId] = $iQuantity;
		}



		$sSQL = "SELECT vendor_id, COALESCE(SUM(quantity), 0)
		         FROM tbl_forecasts
		         WHERE (brand_id='0' OR brand_id IN ($Brands)) AND vendor_id IN ($Vendors) AND month='$Month' AND year='$iYear'
		               AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$Brands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

		if ($Region > 0)
			$sSQL .= " AND country_id='$Region' ";

		$sSQL .= " GROUP BY vendor_id";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iVendorId = $objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iForecast[$iVendorId] = $iQuantity;
		}



		$sSQL = "SELECT vendor_id, COALESCE(SUM(quantity), 0)
		         FROM tbl_revised_forecasts
		         WHERE (brand_id='0' OR brand_id IN ($Brands)) AND vendor_id IN ($Vendors) AND month='$Month' AND year='$iYear'
		               AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$Brands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

		if ($Region > 0)
			$sSQL .= " AND country_id='$Region' ";

		$sSQL .= " GROUP BY vendor_id";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iVendorId = $objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iRevised[$iVendorId] = $iQuantity;
		}


		$sVendors = explode(",", $Vendors);

		foreach ($sVendors as $iVendorId)
		{
			if ($iPlacements[$iVendorId] > 0 || $iShipments[$iVendorId] > 0 || $iForecast[$iVendorId] > 0 || $iRevised[$iVendorId] > 0)
			{
				$iPercentage = @round((($iShipments[$iVendorId] / $iPlacements[$iVendorId]) * 100));
				$iPercentage = (($iPercentage > 100) ? 100 : $iPercentage);

				$sHtml .= '<tr valign="top">';
				$sHtml .= ('<td width="28%">'.$sVendorsList[$iVendorId].'</td>');
				$sHtml .= ('<td width="24%"><span style="float:right; font-size:9px; padding-right:10px;">'.formatNumber($iPercentage, false).'%</span><div style="width:104px; padding-top:2px;"><div style="background:#f0f0f0; border:solid 1px #666666; padding:1px; height:6px;"><div style="width:'.$iPercentage.'px; height:6px; background:#b6e600;"></div></div></div></td>');
				$sHtml .= ('<td width="11%">'.formatNumber($iForecast[$iVendorId], false).'</td>');
				$sHtml .= ('<td width="11%">'.formatNumber($iRevised[$iVendorId], false).'</td>');
				$sHtml .= ('<td width="13%">'.formatNumber($iPlacements[$iVendorId], false).'</td>');
				$sHtml .= ('<td width="13%">'.formatNumber($iShipments[$iVendorId], false).'</td>');
				$sHtml .= '</tr>';
			}
		}

		$sHtml .= '<tr valign="top">';
		$sHtml .= ('<td width="28%"><b>Total</b></td>');
		$sHtml .= ('<td width="24%"></td>');
		$sHtml .= ('<td width="11%"><b>'.formatNumber(@array_sum($iForecast), false).'</b></td>');
		$sHtml .= ('<td width="11%"><b>'.formatNumber(@array_sum($iRevised), false).'</b></td>');
		$sHtml .= ('<td width="13%"><b>'.formatNumber(@array_sum($iPlacements), false).'</b></td>');
		$sHtml .= ('<td width="13%"><b>'.formatNumber(@array_sum($iShipments), false).'</b></td>');
		$sHtml .= '</tr>';
		$sHtml .= '</table>';
	}


	if ($sHtml != "")
		$sHtml .= "<br /><br />";


	if ($Mode == "Brands" || $Mode == "VendorsBrands" || $Mode == "Departments")
	{
		$sHtml .= '<table border="0" cellpadding="2" cellspacing="0" width="100%">';
		$sHtml .= '<tr valign="top">';
		$sHtml .= ('<td width="28%"><b>Brand</b></td>');
		$sHtml .= ('<td width="24%"><b>Shipments</b></td>');
		$sHtml .= ('<td width="11%"><b>Original<br />Forecast</b></td>');
		$sHtml .= ('<td width="11%"><b>Revised<br />Forecast</b></td>');
		$sHtml .= ('<td width="13%"><b>Placements</b></td>');
		$sHtml .= ('<td width="13%"><b>Shipments</b></td>');
		$sHtml .= '</tr>';


		$iPlacements = array( );
		$iShipments  = array( );
		$iForecast   = array( );
		$iRevised    = array( );

		$sSQL = "SELECT po.brand_id, COALESCE(SUM(pc.order_qty), 0)
				 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
				 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
				 		AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
				 		AND po.vendor_id IN ($Vendors)
				 		AND po.brand_id IN ($Brands)
				 		AND (pc.etd_required BETWEEN '$iYear-$iMonth-01' AND '$iYear-$iMonth-$iDays')";

		if ($PoType != "")
			$sSQL .= " AND po.order_type='$PoType' ";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY po.brand_id";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iBrandId  = $objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iPlacements[$iBrandId] = $iQuantity;
		}



		$sSQL = "SELECT po.brand_id, COALESCE(SUM(psq.quantity), 0)
				 FROM tbl_po po, tbl_po_colors pc, tbl_styles s, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B'
				 		AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
				 		AND po.vendor_id IN ($Vendors)
				 		AND po.brand_id IN ($Brands)
				 		AND (pc.etd_required BETWEEN '$iYear-$iMonth-01' AND '$iYear-$iMonth-$iDays')";

		if ($PoType != "")
			$sSQL .= " AND po.order_type='$PoType' ";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY po.brand_id";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iVendorId = $objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iShipments[$iVendorId] = $iQuantity;
		}



		$sSQL = "SELECT brand_id, COALESCE(SUM(quantity), 0)
		         FROM tbl_forecasts
		         WHERE brand_id IN ($Brands) AND (vendor_id='0' OR vendor_id IN ($Vendors)) AND month='$Month' AND year='$iYear'
		         AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$Brands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

		if ($Region > 0)
			$sSQL .= " AND country_id='$Region' ";

		$sSQL .= " GROUP BY brand_id";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iBrandId  = $objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iForecast[$iBrandId] = $iQuantity;
		}



		$sSQL = "SELECT brand_id, COALESCE(SUM(quantity), 0)
		         FROM tbl_revised_forecasts
		         WHERE brand_id IN ($Brands) AND (vendor_id='0' OR vendor_id IN ($Vendors)) AND month='$Month' AND year='$iYear'
		         AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$Brands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

		if ($Region > 0)
			$sSQL .= " AND country_id='$Region' ";

		$sSQL .= " GROUP BY brand_id";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iBrandId  = $objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iRevised[$iBrandId] = $iQuantity;
		}


		$sBrands = explode(",", $Brands);

		foreach ($sBrands as $iBrandId)
		{
			if ($iPlacements[$iBrandId] > 0 || $iShipments[$iBrandId] > 0 || $iForecast[$iBrandId] > 0 || $iRevised[$iBrandId] > 0)
			{
				$iPercentage = @round((($iShipments[$iBrandId] / $iPlacements[$iBrandId]) * 100));
				$iPercentage = (($iPercentage > 100) ? 100 : $iPercentage);

				$sHtml .= '<tr valign="top">';
				$sHtml .= ('<td width="28%">'.$sBrandsList[$iBrandId].'</td>');
				$sHtml .= ('<td width="24%"><span style="float:right; font-size:9px; padding-right:10px;">'.formatNumber($iPercentage, false).'%</span><div style="width:104px; padding-top:2px;"><div style="background:#f0f0f0; border:solid 1px #666666; padding:1px; height:6px;"><div style="width:'.$iPercentage.'px; height:6px; background:#b6e600;"></div></div></div></td>');
				$sHtml .= ('<td width="11%">'.formatNumber($iForecast[$iBrandId], false).'</td>');
				$sHtml .= ('<td width="11%">'.formatNumber($iRevised[$iBrandId], false).'</td>');
				$sHtml .= ('<td width="13%">'.formatNumber($iPlacements[$iBrandId], false).'</td>');
				$sHtml .= ('<td width="13%">'.formatNumber($iShipments[$iBrandId], false).'</td>');
				$sHtml .= '</tr>';
			}
		}

		$sHtml .= '<tr valign="top">';
		$sHtml .= ('<td width="28%"><b>Total</b></td>');
		$sHtml .= ('<td width="24%"></td>');
		$sHtml .= ('<td width="11%"><b>'.formatNumber(@array_sum($iForecast), false).'</b></td>');
		$sHtml .= ('<td width="11%"><b>'.formatNumber(@array_sum($iRevised), false).'</b></td>');
		$sHtml .= ('<td width="13%"><b>'.formatNumber(@array_sum($iPlacements), false).'</b></td>');
		$sHtml .= ('<td width="13%"><b>'.formatNumber(@array_sum($iShipments), false).'</b></td>');
		$sHtml .= '</tr>';
		$sHtml .= '</table>';
	}

	print $sHtml;

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>