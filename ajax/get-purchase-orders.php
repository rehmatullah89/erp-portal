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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Vendor   = IO::intValue("Vendor");
	$Keywords = IO::strValue("Keywords");

	$sVendorSql = "";

	if ($Vendor > 0)
		$sVendorSql = " AND vendor_id='$Vendor' ";


	print "<ul>";

	$sSQL = "SELECT id, order_no FROM tbl_po WHERE order_no LIKE '%$Keywords%' $sVendorSql ORDER BY order_no LIMIT 25";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPo = $objDb->getField($i, 0);
		$sPo = $objDb->getField($i, 1);

		print ("<li id='{$iPo}'>{$sPo}</li>");
	}

	print "</ul>";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>