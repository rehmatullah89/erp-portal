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


	$Brands   = IO::getArray("Brands");

	$sBrands  = @implode(",", $Brands);
	$sParents = getDbValue("GROUP_CONCAT(parent_id SEPARATOR ',')", "tbl_brands", "FIND_IN_SET(id, '$sBrands')");
	$sVendors = getDbValue("GROUP_CONCAT(vendors SEPARATOR ',')", "tbl_brands", "FIND_IN_SET(id, '$sBrands') OR FIND_IN_SET(id, '$sParents')");


	$sSQL = "SELECT v.id, CONCAT(COALESCE((SELECT CONCAT(vendor, ' --> ') FROM tbl_vendors WHERE id=v.parent_id), ''), v.vendor) AS _Vendor
	         FROM tbl_vendors v
	         WHERE v.id > '0' ";

	if ($sBrands != "")
		$sSQL .= " AND FIND_IN_SET(v.id, '$sVendors') ";

	$sSQL .= " ORDER BY _Vendor ";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			if ($i > 0)
				print "|-|";

			print ($objDb->getField($i, 0)."||".$objDb->getField($i, 1));
		}
	}

	else
		print "ERROR|-|A Database Error occured. Please reload your webpage and try again.{$sSQL}";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>