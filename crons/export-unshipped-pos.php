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


	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sVendorsList = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND sourcing='Y'");

	$Country     = IO::intValue("Country");
	$Brand       = IO::intValue("Brand");
	$sCountrySQL = "";

	if ($Country > 0)
		$sCountrySQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='162' AND parent_id='0' AND sourcing='Y') ";


	$sExcelFile = ($sBaseDir.TEMP_DIR."unshipped-pos.csv");

	$hFile = @fopen($sExcelFile, 'w');
	@fwrite($hFile, ('"PO","Brand","Vendor","Quantity","ETD Required"'."\n"));


	$sSQL = "SELECT po.order_no, po.brand_id, po.vendor_id, pc.etd_required, SUM(pc.order_qty) AS _Qty
			 FROM tbl_po po, tbl_po_colors pc
			 WHERE po.id=pc.po_id AND po.order_nature='B' AND po.status!='C'
		           AND (pc.etd_required BETWEEN DATE_SUB(CURDATE( ), INTERVAL 45 DAY) AND CURDATE( ))
		           AND po.id NOT IN (SELECT DISTINCT(po_id) FROM tbl_pre_shipment_detail WHERE NOT ISNULL(quantity) AND quantity > '0')
		           AND po.brand_id='$Brand'
		           AND NOT FIND_IN_SET(po.vendor_id, '194')
		           $sCountrySQL
			 GROUP BY po.id, pc.etd_required";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sOrderNo     = $objDb->getField($i, "order_no");
		$iVendor      = $objDb->getField($i, "vendor_id");
		$iBrand       = $objDb->getField($i, "brand_id");
		$sEtdRequired = $objDb->getField($i, "etd_required");
		$iQuantity    = $objDb->getField($i, "_Qty");


		$sLine = ('" '.$sOrderNo.'","'.
				  $sBrandsList[$iBrand].'","'.
				  $sVendorsList[$iVendor].'","'.
				  $iQuantity.'","'.
				  $sEtdRequired.'"'.
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