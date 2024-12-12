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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0'");

	$OrderNo     = IO::strValue("OrderNo");
	$PoType      = IO::strValue("PoType");
	$PoNature    = IO::strValue("PoNature");
	$Vendor      = IO::intValue("Vendor");
	$Brand       = IO::intValue("Brand");
	$Season      = IO::intValue("Season");
	$Region      = IO::intValue("Region");
	$Style       = IO::strValue("Style");
	$Destination = IO::intValue("Destination");
	$FromDate    = IO::strValue("FromDate");
	$ToDate      = IO::strValue("ToDate");
	$sDateRange  = "";

	if ($FromDate != "" && $ToDate != "")
		$sDateRange = " AND etd_required BETWEEN '$FromDate' AND '$ToDate' ";


	$sExcelFile = ($sBaseDir.TEMP_DIR."purchase-orders.csv");

	$hFile = @fopen($sExcelFile, 'w');
	@fwrite($hFile, ('"Order No","Order Status","Vendor","Sizes","Style","Brand","Customer","Call/SC No","Season","Destinations","ETD Required","Shipping Dates","Quantity","Currency","Price","FOB ($)"'."\n"));


	$sSQL = "SELECT po.id, po.order_no, po.order_status, po.brand_id, po.customer, po.call_no, po.vendor_id, pc.style_id, s.style, s.sub_season_id, po.currency
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
			 WHERE po.id=pc.po_id AND pc.style_id=s.id";

	if ($OrderNo != "")
	{
		if (@strpos($OrderNo, ",") === FALSE)
			$sSQL .= " AND po.order_no LIKE '%$OrderNo%' ";

		else
		{
			$sPOs = @explode(",", $OrderNo);

			$sSQL .= " AND (";

			for ($i = 0; $i < count($sPOs); $i ++)
			{
				if ($i > 0)
					$sSQL .= " OR ";

				$sSQL .= " po.order_no LIKE '%{$sPOs[$i]}%' ";
			}

			$sSQL .= ")";
		}
	}


	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";


	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";


	if ($FromDate != "" && $ToDate != "")
		$sSQL .= " AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate') ";


	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($PoType != "")
		$sSQL .= " AND po.order_type='$PoType' ";

	if ($PoNature != "")
		$sSQL .= " AND po.order_nature='$PoNature' ";

	if ($Style != "" && $Season > 0)
		$sSQL .= " AND (s.style LIKE '%$Style%' AND s.sub_season_id='$Season') ";

	else if ($Style != "")
		$sSQL .= " AND s.style LIKE '%$Style%' ";

	else if ($Season > 0)
		$sSQL .= " AND s.sub_season_id='$Season' ";

	if ($Destination > 0)
		$sSQL .= " AND pc.destination_id='$Destination' ";


	$sSQL .= " GROUP BY po.id, pc.style_id
	           ORDER BY po.id DESC";

	$objDb->query($sSQL);


	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPoId        = $objDb->getField($i, "id");
		$iStyleId     = $objDb->getField($i, "style_id");
		$iVendorId    = $objDb->getField($i, "vendor_id");
		$iBrandId     = $objDb->getField($i, "brand_id");
		$sCustomer    = $objDb->getField($i, "customer");
		$iSeasonId    = $objDb->getField($i, "sub_season_id");
		$sOrderNo     = $objDb->getField($i, "order_no");
		$sOrderStatus = $objDb->getField($i, "order_status");
		$sStyle       = $objDb->getField($i, "style");
		$sCurrency    = $objDb->getField($i, "currency");
		$sCallNo      = $objDb->getField($i, "call_no");

		$sSizes         = "";
		$sDestinations  = "";
		$sPrices        = "";
		$iPoQuantity    = 0;
		$fFobAmount     = 0;
		$sEtdRequired   = "";
		$sShippingDates = "";


		$sSQL = "SELECT DISTINCT(s.size)
		         FROM tbl_sizes s, tbl_po_colors pc, tbl_po_quantities pq
		         WHERE pc.id=pq.color_id AND pq.size_id=s.id AND pc.po_id=pq.po_id AND pc.po_id='$iPoId' AND pc.style_id='$iStyleId'
		         ORDER BY s.size";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
			$sSizes .= (", ".$objDb2->getField($j, 0));

		$sSizes = substr($sSizes, 2);



		$sSQL = "SELECT DISTINCT(d.destination) FROM tbl_destinations d, tbl_po_colors pc WHERE d.id=pc.destination_id AND pc.po_id='$iPoId' AND pc.style_id='$iStyleId' ORDER BY d.destination";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
			$sDestinations .= (", ".$objDb2->getField($j, 0));

		$sDestinations = substr($sDestinations, 2);



		$sSQL = "SELECT DISTINCT(etd_required) FROM tbl_po_colors WHERE po_id='$iPoId' AND style_id='$iStyleId' ORDER BY etd_required";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
			$sEtdRequired .= (", ".$objDb2->getField($j, 0));

		$sEtdRequired = substr($sEtdRequired, 2);



		$sSQL = "SELECT DISTINCT(psd.shipping_date)
		         FROM tbl_po_colors pc, tbl_pre_shipment_detail psd
		         WHERE pc.po_id=psd.po_id AND pc.po_id='$iPoId' AND pc.style_id='$iStyleId'
		         ORDER BY psd.shipping_date";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
			$sShippingDates .= (", ".$objDb2->getField($j, 0));

		$sShippingDates = substr($sShippingDates, 2);



		$sSQL = "SELECT price,
		                (SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id=tbl_po_colors.po_id AND color_id=tbl_po_colors.id) AS _Quantity
		         FROM tbl_po_colors
		         WHERE po_id='$iPoId' AND style_id='$iStyleId' $sDateRange
		         ORDER BY id";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$fPrice    = $objDb2->getField($j, 0);
			$iQuantity = $objDb2->getField($j, 1);

			$fFobAmount  += ($fPrice * $iQuantity);
			$iPoQuantity += $iQuantity;

			if (@strpos($sPrices, (", ".formatNumber($fPrice))) === FALSE)
				$sPrices .= (", ".formatNumber($fPrice));
		}

		$sPrices = substr($sPrices, 2);



		$sLine = ('"'.
		          ltrim($sOrderNo, '0').'","'.
		          $sOrderStatus.'","'.
				  $sVendorsList[$iVendorId].'","'.
				  $sSizes.'","'.
				  $sStyle.'","'.
				  $sBrandsList[$iBrandId].'","'.
				  $sCustomer.'","'.
				  $sCallNo.'","'.
				  $sSeasonsList[$iSeasonId].'","'.
				  $sDestinations.'","'.
				  $sEtdRequired.'","'.
				  $sShippingDates.'","'.
				  $iPoQuantity.'","'.
				  $sCurrency.'","'.
				  $sPrices.'","'.
				  $fFobAmount.'"'.
				"\n");

		@fwrite($hFile, $sLine);
	}


	@fclose($hFile);

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );


	// forcing csv file to download
	$iSize = @filesize($sExcelFile);

	if(ini_get('zlib.output_compression'))
		@ini_set('zlib.output_compression', 'Off');

	header('Content-Description: File Transfer');
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
	header('Content-Type: application/force-download');
	header("Content-Type: application/download");
	header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=\"".basename($sExcelFile)."\";");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: $iSize");

	@readfile($sExcelFile);
	@unlink($sExcelFile);

	@ob_end_flush( );
?>