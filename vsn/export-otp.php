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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND sourcing='Y'");

	$OrderNo     = IO::strValue("OrderNo");
	$Vendor      = IO::intValue("Vendor");
	$Brand       = IO::intValue("Brand");
	$Status      = IO::strValue("Status");
	$ShFromDate  = IO::strValue("ShFromDate");
	$ShToDate    = IO::strValue("ShToDate");
	$EtdFromDate = IO::strValue("EtdFromDate");
	$EtdToDate   = IO::strValue("EtdToDate");
	$Region      = IO::intValue("Region");
	$sConditions = "";


	if ($OrderNo != "")
		$sConditions .= " AND order_no LIKE '%$OrderNo%' ";

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	else
		$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Region > 0)
		$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($Brand > 0)
		$sSQL = "SELECT po_id FROM tbl_po_colors WHERE style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand')";

	else
		$sSQL = "SELECT po_id FROM tbl_po_colors WHERE style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']}))";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$sPos   = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sPos .= (",".$objDb->getField($i, 0));

	if ($sPos != "")
		$sPos = substr($sPos, 1);

	$sConditions .= " AND id IN ($sPos) ";


	if ($ShFromDate != "" && $ShToDate != "")
	{
		$sSQL = "SELECT po_id FROM tbl_pre_shipment_detail WHERE handover_to_forwarder BETWEEN '$ShFromDate' AND '$ShToDate'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND id IN ($sPos) ";
	}

	if ($EtdFromDate != "" && $EtdToDate != "")
	{
		$sSQL = "SELECT po_id FROM tbl_po_colors WHERE etd_required BETWEEN '$EtdFromDate' AND '$EtdToDate'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND id IN ($sPos) ";
	}

	if ($Status != "")
	{
		if ($Status == "Delayed")
			$sSQL = "SELECT DISTINCT(pc.po_id) FROM tbl_pre_shipment_detail psd, tbl_po_colors pc WHERE pc.po_id=psd.po_id AND pc.etd_required < psd.handover_to_forwarder AND psd.handover_to_forwarder != ''";

		else if ($Status == "OnTime")
			$sSQL = "SELECT DISTINCT(pc.po_id) FROM tbl_pre_shipment_detail psd, tbl_po_colors pc WHERE pc.po_id=psd.po_id AND pc.etd_required >= psd.handover_to_forwarder AND psd.handover_to_forwarder != ''";

		else if ($Status == "UnShipped")
			$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE etd_required <= CURDATE( ) AND po_id NOT IN (SELECT DISTINCT(po_id) FROM tbl_pre_shipment_detail WHERE handover_to_forwarder!='0000-00-00')";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND id IN ($sPos) ";
	}

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));


	$sExcelFile = ($sBaseDir."temp/otp-report.csv");

	$hFile = @fopen($sExcelFile, 'w');
	@fwrite($hFile, ('"Order No","Order Status","Vendor","ETD Required","Order Qty","Shipping Date","Ship Qty"'."\n"));

	$sSQL = "SELECT id, order_no, order_status, vendor_id FROM tbl_po $sConditions ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId = $objDb->getField($i, 'id');

		$sLines = array( );

		$sLines[0][0] = ltrim($objDb->getField($i, 'order_no'), '0');
		$sLines[0][1] = $objDb->getField($i, 'order_status');
		$sLines[0][2] = $sVendorsList[$objDb->getField($i, 'vendor_id')];


		$sSQL = "SELECT DISTINCT(etd_required) FROM tbl_po_colors WHERE po_id='$iId'";

		if ($EtdFromDate != "" && $EtdToDate != "")
			$sSQL .= " AND (etd_required BETWEEN '$EtdFromDate' AND '$EtdToDate')";

		$objDb2->query($sSQL);

		$iEtdCount = $objDb2->getCount( );

		for ($j = 0; $j < $iEtdCount; $j ++)
		{
			$sEtdRequired = $objDb2->getField($j, 0);

			$sSQL = "SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id='$iId' AND color_id IN (SELECT id FROM tbl_po_colors WHERE po_id='$iId' AND etd_required='$sEtdRequired')";
			$objDb3->query($sSQL);

			$iQuantity = $objDb3->getField(0, 0);

			$sLines[$j][3] = formatDate($sEtdRequired);
			$sLines[$j][4] = $iQuantity;
		}


		$sSQL = "SELECT DISTINCT(handover_to_forwarder) FROM tbl_pre_shipment_detail WHERE po_id='$iId'";
		$objDb2->query($sSQL);

		$iShCount = $objDb2->getCount( );

		for ($j = 0; $j < $iShCount; $j ++)
		{
			$sShippingDate = $objDb2->getField($j, 0);

			$sSQL = "SELECT SUM(quantity) FROM tbl_pre_shipment_quantities WHERE po_id='$iId' AND ship_id IN (SELECT id FROM tbl_pre_shipment_detail WHERE po_id='$iId' AND handover_to_forwarder='$sShippingDate')";
			$objDb3->query($sSQL);

			$iQuantity = $objDb3->getField(0, 0);

			$sLines[$j][5] = formatDate($sShippingDate);
			$sLines[$j][6] = $iQuantity;
		}

		$iCount2 = (($iEtdCount > $iShCount) ? $iEtdCount : $iShCount);

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$sLine = ('"'.$sLines[$j][0].'","'.$sLines[$j][1].'","'.$sLines[$j][2].'","'.$sLines[$j][3].'","'.$sLines[$j][4].'","'.$sLines[$j][5].'","'.$sLines[$j][6].'"'."\n");

			@fwrite($hFile, $sLine);
		}
	}

	@fclose($hFile);

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
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