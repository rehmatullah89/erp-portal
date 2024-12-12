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

	$Month    = IO::intValue("Month");
	$Year     = IO::intValue("Year");
	$Region   = IO::intValue("Region");
	$Category = IO::intValue("Category");
	$Vendor   = IO::intValue("Vendor");
	$Brand    = IO::intValue("Brand");

	$sVendorsList    = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sCategoriesList = getList("tbl_categories", "id", "category");

	if ($Year == 0)
		$Year = date("Y");

	$iYear = substr($Year, 2);


	$sExcelFile = ($sBaseDir."temp/forecasts.csv");

	$hFile = @fopen($sExcelFile, 'w');
	@fwrite($hFile, ('"Vendor","Category","Jan '.$iYear.'","Feb '.$iYear.'","Mar '.$iYear.'","Apr '.$iYear.'","May '.$iYear.'","Jun '.$iYear.'","Jul '.$iYear.'","Aug '.$iYear.'","Sep '.$iYear.'","Oct '.$iYear.'","Nov '.$iYear.'","Dec '.$iYear.'","Total"'."\n"));


	$sConditions = "";

	if ($Year > 0)
		$sConditions .= " AND year='$Year' ";

	if ($Region > 0)
		$sConditions .= " AND country_id='$Region' ";

	if ($Category > 0)
	{
		$sSQL = "SELECT id FROM tbl_vendors WHERE category_id='$Category' AND parent_id='0' AND sourcing='Y'";
		$objDb->query($sSQL);

		$iCount   = $objDb->getCount( );
		$sVendors = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sVendors .= (",".$objDb->getField($i, 0));

		if ($sVendors != "")
			$sVendors = substr($sVendors, 1);

		$sConditions .= " AND vendor_id IN ($sVendors) ";
	}

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	else
		$sConditions .= " AND (vendor_id IN ({$_SESSION['Vendors']}) OR vendor_id='0')";

	if ($Brand > 0)
		$sConditions .= " AND brand_id='$Brand' ";

	else
		$sConditions .= " AND (brand_id IN ({$_SESSION['Brands']}) OR brand_id='0')";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));


	$sSQL = "SELECT vendor_id, month, COALESCE(SUM(quantity), 0) AS _Quantity,
	                (SELECT category_id FROM tbl_vendors WHERE id=tbl_forecasts.vendor_id) AS _CategoryId
	         FROM tbl_forecasts
	         $sConditions
	         GROUP BY vendor_id, month
	         ORDER BY vendor_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount;)
	{
		$iVendor   = $objDb->getField($i, "vendor_id");
		$iCategory = $objDb->getField($i, "_CategoryId");

		$sVendor   = $sVendorsList[$iVendor];
		$sCategory = $sCategoriesList[$iCategory];
		$iForecast = array( );

		do
		{
			$iMonth    = $objDb->getField($i, "month");
			$iQuantity = $objDb->getField($i, "_Quantity");

			$iForecast[$iMonth] = $iQuantity;

			$i ++;
		}
		while($objDb->getField($i, "vendor_id") == $iVendor);

		$sLine = ('"'.
		          $sVendor.'","'.
		          $sCategory.'","'.
				  $iForecast[1].'","'.
				  $iForecast[2].'","'.
				  $iForecast[3].'","'.
				  $iForecast[4].'","'.
				  $iForecast[5].'","'.
				  $iForecast[6].'","'.
				  $iForecast[7].'","'.
				  $iForecast[8].'","'.
				  $iForecast[9].'","'.
				  $iForecast[10].'","'.
				  $iForecast[11].'","'.
				  $iForecast[12].'","'.
				  array_sum($iForecast).'"'.
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