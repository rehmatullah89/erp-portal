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


	$sHtml = '<table border="0" cellpadding="2" cellspacing="0" width="100%">';

	if ($Mode == "Vendors" || $Mode == "VendorsBrands")
	{
		$sHtml .= '<tr valign="top">';
		$sHtml .= ('<td width="40%"><b>Vendor</b></td>');
		$sHtml .= ('<td width="18%"><b>Order Qty</b></td>');
		$sHtml .= ('<td width="24%"><b>OnTime Qty</b></td>');
		$sHtml .= ('<td width="18%"><b>OTP</b></td>');
		$sHtml .= '</tr>';


		$iOrderQty  = array( );
		$iOnTimeQty = array( );

		$sSQL = "SELECT po.vendor_id, COALESCE(SUM(pc.order_qty), 0), COALESCE(SUM(pc.ontime_qty), 0)
				 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
				 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
				 		AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
				 		AND po.vendor_id IN ($Vendors)
				 		AND po.brand_id IN ($Brands)
				 		AND (pc.etd_required BETWEEN '$iYear-$iMonth-01' AND '$iYear-$iMonth-$iDays') AND pc.etd_required <= CURDATE( )
				 		AND po.status='C'";

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

			$iOrderQty[$iVendorId]  = $objDb->getField($i, 1);
			$iOnTimeQty[$iVendorId] = $objDb->getField($i, 2);
		}


		foreach ($iOrderQty as $iVendorId => $iVendorQty)
		{
			$fOtp = @round((($iOnTimeQty[$iVendorId] / $iVendorQty) * 100), 2);

			$sHtml .= '<tr valign="top">';
			$sHtml .= ('<td width="40%">'.$sVendorsList[$iVendorId].'</td>');
			$sHtml .= ('<td width="20%">'.formatNumber($iVendorQty, false).'</td>');
			$sHtml .= ('<td width="20%">'.formatNumber($iOnTimeQty[$iVendorId], false).'</td>');
			$sHtml .= ('<td width="20%">'.formatNumber($fOtp).'%</td>');
			$sHtml .= '</tr>';
		}
	}


	if ($Mode == "Brands" || $Mode == "VendorsBrands" || $Mode == "Departments")
	{
		$sHtml .= '<tr valign="top">';
		$sHtml .= ('<td width="40%"><b>Brand</b></td>');
		$sHtml .= ('<td width="18%"><b>Order Qty</b></td>');
		$sHtml .= ('<td width="24%"><b>OnTime Qty</b></td>');
		$sHtml .= ('<td width="18%"><b>OTP</b></td>');
		$sHtml .= '</tr>';


		$iOrderQty  = array( );
		$iOnTimeQty = array( );

		$sSQL = "SELECT po.brand_id, COALESCE(SUM(pc.order_qty), 0), COALESCE(SUM(pc.ontime_qty), 0)
				 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
				 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
				 		AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
				 		AND po.vendor_id IN ($Vendors)
				 		AND po.brand_id IN ($Brands)
				 		AND (pc.etd_required BETWEEN '$iYear-$iMonth-01' AND '$iYear-$iMonth-$iDays') AND pc.etd_required <= CURDATE( )
				 		AND po.status='C'";

		if ($PoType != "")
			$sSQL .= " AND po.order_type='$PoType' ";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY po.brand_id";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iBrandId = $objDb->getField($i, 0);

			$iOrderQty[$iBrandId]  = $objDb->getField($i, 1);
			$iOnTimeQty[$iBrandId] = $objDb->getField($i, 2);
		}


		foreach ($iOrderQty as $iBrandId => $iBrandQty)
		{
			$fOtp = @round((($iOnTimeQty[$iBrandId] / $iBrandQty) * 100), 2);

			$sHtml .= '<tr valign="top">';
			$sHtml .= ('<td width="40%">'.$sBrandsList[$iBrandId].'</td>');
			$sHtml .= ('<td width="20%">'.formatNumber($iBrandQty, false).'</td>');
			$sHtml .= ('<td width="20%">'.formatNumber($iOnTimeQty[$iBrandId], false).'</td>');
			$sHtml .= ('<td width="20%">'.formatNumber($fOtp).'%</td>');
			$sHtml .= '</tr>';
		}
	}

	$sHtml .= '</table>';

	print $sHtml;

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>