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


	$sConditions = "";
	
	if ($OrderNo != "")
	{
		if (@strpos($OrderNo, ",") === FALSE)
			$sConditions .= " AND po.order_no LIKE '%$OrderNo%' ";

		else
		{
			$sPOs = @explode(",", $OrderNo);

			$sConditions .= " AND (";

			for ($i = 0; $i < count($sPOs); $i ++)
			{
				if ($i > 0)
					$sConditions .= " OR ";

				$sConditions .= " po.order_no LIKE '%{$sPOs[$i]}%' ";
			}

			$sConditions .= ")";
		}
	}


	if ($Vendor > 0)
		$sConditions .= " AND po.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";


	if ($Region > 0)
		$sConditions .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";


	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate') ";


	/*if ($Brand > 0)
		$sConditions .= " AND po.brand_id='$Brand' ";

	else
		$sConditions .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";*/
        $subBrands = getDbValue("GROUP_CONCAT(id)", "tbl_brands", "parent_id='$Brand'");
        
        if ($Brand > 0 && $SubBrand == 0)
                $sConditions .= " AND po.brand_id IN($subBrands)";
        else if ($SubBrand > 0)
                $sConditions .= " AND po.brand_id='$SubBrand' ";
        else
                $sConditions .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($PoType != "")
		$sConditions .= " AND po.order_type='$PoType' ";

	if ($PoNature != "")
		$sConditions .= " AND po.order_nature='$PoNature' ";

	if ($Style != "" && $Season > 0)
		$sConditions .= " AND (s.style LIKE '%$Style%' AND s.sub_season_id='$Season') ";

	else if ($Style != "")
		$sConditions .= " AND s.style LIKE '%$Style%' ";

	else if ($Season > 0)
		$sConditions .= " AND s.sub_season_id='$Season' ";

	if ($Destination > 0)
		$sConditions .= " AND pc.destination_id='$Destination' ";

	
	
	$sSQL = "SELECT DISTINCT(size_id)
			 FROM tbl_po po, tbl_po_colors pc, tbl_po_quantities pq, tbl_styles s
			 WHERE po.id=pc.po_id AND po.id=pq.po_id AND pc.id=pq.color_id AND pc.style_id=s.id $sConditions";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iSizes = array( );

	for ($i = 0; $i < $iCount; $i ++)
		$iSizes[] = $objDb->getField($i, 0);
	
	
	$sSizes     = @implode(",", $iSizes);
	$sSizesList = getList("tbl_sizes", "id", "size", "id IN ($sSizes)");
	$sExcelFile = ($sBaseDir.TEMP_DIR."purchase-orders.csv");

	
	$hFile  = @fopen($sExcelFile, 'w');
	$sSizes = "";
	
	foreach ($iSizes as $iSize)
		$sSizes .= (',"'.$sSizesList[$iSize].'"');
	
	@fwrite($hFile, ('"Order No","Vendor","Style","Color","Brand","Customer","Call/SC No","Season","Destinations","ETD Required","Shipping Dates","Quantity"'.$sSizes.',"Currency","Price","FOB ($)"'."\n"));	
		

	
	$sSQL = "SELECT po.id, po.order_no, po.order_status, po.brand_id, po.customer, po.call_no, po.vendor_id, pc.style_id, po.currency,
			        pc.color,
					s.style, s.sub_season_id
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
			 WHERE po.id=pc.po_id AND pc.style_id=s.id $sConditions
	         GROUP BY po.id, pc.style_id, pc.color
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
		$sColor       = $objDb->getField($i, "color");
		$sCurrency    = $objDb->getField($i, "currency");
		$sCallNo      = $objDb->getField($i, "call_no");

		
		$sDestinations  = "";
		$sPrices        = "";
		$iPoQuantity    = 0;
		$fFobAmount     = 0;
		$sEtdRequired   = "";
		$sShippingDates = "";


		$sSQL = "SELECT DISTINCT(s.size)
		         FROM tbl_sizes s, tbl_po_colors pc, tbl_po_quantities pq
		         WHERE pc.id=pq.color_id AND pq.size_id=s.id AND pc.po_id=pq.po_id AND pc.po_id='$iPoId' AND pc.style_id='$iStyleId' AND pc.color='$sColor'
		         ORDER BY s.size";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
			$sSizes .= (", ".$objDb2->getField($j, 0));

		$sSizes = substr($sSizes, 2);



		$sSQL = "SELECT DISTINCT(d.destination) FROM tbl_destinations d, tbl_po_colors pc WHERE d.id=pc.destination_id AND pc.po_id='$iPoId' AND pc.style_id='$iStyleId' AND pc.color='$sColor' ORDER BY d.destination";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
			$sDestinations .= (", ".$objDb2->getField($j, 0));

		$sDestinations = substr($sDestinations, 2);



		$sSQL = "SELECT DISTINCT(etd_required) FROM tbl_po_colors WHERE po_id='$iPoId' AND style_id='$iStyleId' AND color='$sColor' ORDER BY etd_required";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
			$sEtdRequired .= (", ".$objDb2->getField($j, 0));

		$sEtdRequired = substr($sEtdRequired, 2);



		$sSQL = "SELECT DISTINCT(psd.shipping_date)
		         FROM tbl_po_colors pc, tbl_pre_shipment_detail psd
		         WHERE pc.po_id=psd.po_id AND pc.po_id='$iPoId' AND pc.style_id='$iStyleId' AND pc.color='$sColor'
		         ORDER BY psd.shipping_date";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
			$sShippingDates .= (", ".$objDb2->getField($j, 0));

		$sShippingDates = substr($sShippingDates, 2);



		$sSQL = ("SELECT SUM(pq.quantity), pq.size_id, pc.price
				  FROM tbl_po_colors pc, tbl_po_quantities pq
				  WHERE pc.po_id=pq.po_id AND pc.id=pq.color_id AND pc.po_id='$iPoId' AND pc.style_id='$iStyleId' AND pc.color='$sColor' ".str_replace("etd_required", "pc.etd_required", $sDateRange)."
		          GROUP BY pq.size_id, pc.price");
		$objDb2->query($sSQL);

		$iCount2  = $objDb2->getCount( );
		$sSizeQty = array( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iQuantity = $objDb2->getField($j, 0);
			$iSize     = $objDb2->getField($j, 1);
			$fPrice    = $objDb2->getField($j, 2);

			$fFobAmount  += ($fPrice * $iQuantity);
			$iPoQuantity += $iQuantity;
			
			
			$sSizeQty[$iSize] = $iQuantity;

			if (@strpos($sPrices, (", ".formatNumber($fPrice))) === FALSE)
				$sPrices .= (", ".formatNumber($fPrice));
		}

		
		$sPrices = substr($sPrices, 2);
		$sSizes  = "";

		
		foreach ($iSizes as $iSize)
			$sSizes .= (',"'.intval($sSizeQty[$iSize]).'"');


		$sLine = ('"'.
		          ltrim($sOrderNo, '0').'","'.
				  $sVendorsList[$iVendorId].'","'.
				  $sStyle.'","'.
				  $sColor.'","'.
				  $sBrandsList[$iBrandId].'","'.
				  $sCustomer.'","'.
				  $sCallNo.'","'.
				  $sSeasonsList[$iSeasonId].'","'.
				  $sDestinations.'","'.
				  $sEtdRequired.'","'.
				  $sShippingDates.'","'.
				  $iPoQuantity.'"'.
				  $sSizes.',"'.
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