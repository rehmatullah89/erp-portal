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


        $DateStart = date('Y-m-d', strtotime('-2 months'));
        $DateEnd   = date('Y-m-d', strtotime('+2 months'));
        
	$MGFVendors   = getDbValue("vendors", "tbl_users", "email LIKE '%matyuen@mgfsourcing.com%'");
	$sVendorsList = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND sourcing='Y' AND id IN ($MGFVendors)");
        

	$sExcelFile = ($sBaseDir.TEMP_DIR."mgf-blank-pos.csv");

	$hFile = @fopen($sExcelFile, 'w');
	@fwrite($hFile, ('"PO","Vendor","ETD Required"'."\n"));


	$sSQL = "SELECT po.order_no, po.vendor_id, pc.etd_required
			 FROM tbl_po po, tbl_po_colors pc
			 WHERE po.id=pc.po_id 
                            AND po.sizes IS NULL 
                            AND po.vendor_id IN ($MGFVendors) 
                            AND (pc.etd_required BETWEEN '$DateStart' AND '$DateEnd')";
        
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sOrderNo     = $objDb->getField($i, "order_no");
		$iVendor      = $objDb->getField($i, "vendor_id");
		$sEtdRequired = $objDb->getField($i, "etd_required");


		$sLine = ('" '.$sOrderNo.'","'.
				  $sVendorsList[$iVendor].'","'.
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