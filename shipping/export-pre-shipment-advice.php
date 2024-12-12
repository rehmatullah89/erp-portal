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

	$OrderNo     = IO::strValue("OrderNo");
	$Vendor      = IO::intValue("Vendor");
	$Brand       = IO::intValue("Brand");
	$Region      = IO::intValue("Region");
	$ShFromDate  = IO::strValue("ShFromDate");
	$ShToDate    = IO::strValue("ShToDate");
	$EtdFromDate = IO::strValue("EtdFromDate");
	$EtdToDate   = IO::strValue("EtdToDate");
	$Status      = IO::strValue("Status");
	$FinalAudit  = IO::strValue("FinalAudit");
	$Season      = IO::intValue("Season");


	$sVendorsList = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND sourcing='Y'");
	$sSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0'");

	if ($Brand > 0)
	{
		$iParent      = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$sSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iParent' AND parent_id>'0'");
	}


	$sConditions = " WHERE po.id=pc.po_id ";

	$sSQL = "SELECT DISTINCT(po.id)
	         FROM tbl_po po, tbl_po_colors pc, tbl_styles s, tbl_pre_shipment_advice psa";

	if ( ($ShFromDate != "" && $ShToDate != "") || $Status == "Delayed")
		$sSQL .= ", tbl_pre_shipment_detail psd ";

	$sSQL .= " WHERE po.id=pc.po_id AND pc.style_id=s.id AND psa.po_id=po.id AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}') ";

	if ( ($ShFromDate != "" && $ShToDate != "") || $Status == "Delayed")
		$sSQL .= " AND psd.po_id=po.id ";

	if ($OrderNo != "")
		$sSQL .= " AND po.order_no LIKE '%$OrderNo%' ";

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($Season > 0)
		$sSQL .= " AND s.sub_season_id='$Season' ";

	if ($EtdFromDate != "" && $EtdToDate != "")
		$sSQL .= " AND (pc.etd_required BETWEEN '$EtdFromDate' AND '$EtdToDate') ";

	if ($ShFromDate != "" && $ShToDate != "")
		$sSQL .= " AND (psd.shipping_date BETWEEN '$ShFromDate' AND '$ShToDate') ";

	if ($Status != "")
	{
		if ($Status == "Delayed")
			$sSQL .= " AND (NOT ISNULL(psd.handover_to_forwarder) AND psd.handover_to_forwarder != '0000-00-00' AND psd.handover_to_forwarder > pc.etd_required";

		else if ($Status == "Short")
			$sSQL .= " AND (psa.quantity < po.quantity AND psa.quantity > '0' AND po.status!='C') ";

		else if ($Status == "UnShipped")
			$sSQL .= " AND (psa.quantity='0' AND po.status!='C') ";
	}

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$sPos   = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sPos .= (",".$objDb->getField($i, 0));

	if ($sPos != "")
		$sPos = substr($sPos, 1);

	$sConditions .= " AND po.id IN ($sPos) ";

	if ($FinalAudit != "")
	{
		$sSQL = "SELECT po_id, additional_pos FROM tbl_qa_reports WHERE audit_stage='F' AND audit_result IN ('P','A','B')";

		if ($Vendor > 0)
			$sSQL .= " AND vendor_id='$Vendor' ";

		else
			$sSQL .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";

		if ($Brand > 0)
			$sSQL .= " AND brand_id='$Brand' ";

		else
			$sSQL .= " AND brand_id IN ({$_SESSION['Brands']}) ";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sPos .= (",".$objDb->getField($i, 0));

			if ($objDb->getField($i, 1) != "")
				$sPos .= (",".$objDb->getField($i, 1));
		}

		if ($sPos != "")
			$sPos = substr($sPos, 1);


		if ($FinalAudit == "Y")
			$sConditions .= " AND po.id IN ($sPos) ";

		else if ($FinalAudit == "N")
			$sConditions .= " AND po.id NOT IN ($sPos) ";
	}

/*
	$sSQL = "SELECT psa.po_id, psa.quantity, po.order_no, po.order_status, po.quantity, po.vendor_id, po.shipping_dates, po.destinations, po.status,
	                (SELECT invoice_no FROM tbl_pre_shipment_detail WHERE po_id=psa.po_id LIMIT 1) AS _Invoice,
	                (SELECT invoice_packing_list FROM tbl_pre_shipment_detail WHERE po_id=psa.po_id LIMIT 1) AS _InvoicePackingList,
	                (SELECT style FROM tbl_styles WHERE id IN (po.styles) ORDER BY id LIMIT 1) AS _Style,
	                (SELECT sub_season_id FROM tbl_styles WHERE id IN (po.styles) ORDER BY id LIMIT 1) AS _Season,
	                (SELECT handover_to_forwarder FROM tbl_pre_shipment_detail WHERE po_id=po.id LIMIT 1) AS _HandoverToForwarder
	         FROM tbl_po po, tbl_pre_shipment_advice psa
	         $sConditions
	         ORDER BY psa.po_id DESC";
 
 */
	$sSQL = "SELECT po.order_no, pc.color, pc.order_qty, po.order_status, po.vendor_id, po.shipping_dates, po.destinations,
                    (SELECT SUM(quantity) FROM tbl_pre_shipment_quantities WHERE po.id=po_id AND pc.id=color_id) AS _ShipQty,
	                (SELECT style FROM tbl_styles WHERE id IN (po.styles) ORDER BY id LIMIT 1) AS _Style,
	                (SELECT sub_season_id FROM tbl_styles WHERE id IN (po.styles) ORDER BY id LIMIT 1) AS _Season,
					(SELECT GROUP_CONCAT(DISTINCT(tod.terms) SEPARATOR '; ') FROM tbl_pre_shipment_detail psd, tbl_terms_of_delivery tod WHERE tod.id=psd.terms_of_delivery_id AND psd.po_id = po.id) AS _ShipMode,
					(SELECT shipping_date FROM tbl_pre_shipment_detail WHERE po_id = po.id LIMIT 1) AS _ShipDate,
	                (SELECT handover_to_forwarder FROM tbl_pre_shipment_detail WHERE po_id=po.id LIMIT 1) AS _HandoverToForwarder
	         FROM tbl_po po, tbl_po_colors pc
	         $sConditions
	         ORDER BY po.id DESC";
        
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sExcelFile = ($sBaseDir.TEMP_DIR."pre-shipment-advice.csv");

	$hFile = @fopen($sExcelFile, 'w');
	@fwrite($hFile, ('"Order No","Color","Vendor","Style No","Season","PO ETD","Handover to Forwarder","Difference (Days)","Destination","Order Qty","Ship Qty","Vsl Sailing Date","Shipping Mode"'."\n"));


	for ($i = 0; $i < $iCount; $i ++)
	{
		$sEtdRequired         = substr($objDb->getField($i, 'shipping_dates'), 0, 10);
		$sHandoverToForwarder = $objDb->getField($i, '_HandoverToForwarder');
		$sDestinations        = "";

		$iDifference = (strtotime($sEtdRequired) - strtotime($sHandoverToForwarder));
		$iDifference = @($iDifference / 86400);


		$sSQL = ("SELECT destination FROM tbl_destinations WHERE id IN (".$objDb->getField($i, "destinations").") ORDER BY destination");

		if ($objDb2->query($sSQL) == true)
		{
			$iCount2 = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
				$sDestinations .= (", ".$objDb2->getField($j, 0));

			$sDestinations = substr($sDestinations, 2);
		}


		$sLine = ('"'.
		          ($objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status')).'","'.
                  ($objDb->getField($i, 'pc.color')).'","'.  
		          $sVendorsList[$objDb->getField($i, 'vendor_id')].'","'.
				  $objDb->getField($i, '_Style').'","'.
				  $sSeasonsList[$objDb->getField($i, '_Season')].'","'.
				  formatDate($sEtdRequired).'","'.
				  formatDate($sHandoverToForwarder).'","'.
				  formatNumber($iDifference, false).'","'.
				  $sDestinations.'","'.
				  formatNumber($objDb->getField($i, 'pc.order_qty'), false).'","'.
				  formatNumber($objDb->getField($i, '_ShipQty'), false).'","'.
				  formatDate($objDb->getField($i, '_ShipDate')).'","'.
				  ($objDb->getField($i, '_ShipMode')).'"'.  
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