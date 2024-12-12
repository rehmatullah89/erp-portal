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

	$OrderNo     = IO::strValue("OrderNo");
	$OrderStatus = IO::strValue("OrderStatus");
	$Vendor      = IO::intValue("Vendor");
	$Style       = IO::intValue("Style");
	$Id          = IO::intValue("Id");

	$sSQL = "SELECT * FROM tbl_po WHERE order_no='$OrderNo' AND order_status='$OrderStatus' AND vendor_id='$Vendor' AND brand_id IN (SELECT sub_brand_id FROM tbl_styles WHERE id IN ($Style))";

	if ($Id > 0)
		$sSQL .= " AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		if ($iCount == 0)
			print "OK";

		else
			print ("EXISTS|-|".($OrderNo."-".chr($iCount + 64))."|-|".$objDb->getField(0, 0));
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>