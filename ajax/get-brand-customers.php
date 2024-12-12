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
	$objDb2      = new Database( );

	$Id   = IO::strValue("Id");
	$List = IO::strValue("List");

	if ($Id == "0" || $Id == "")
	{
		print "ERROR|-|Invalid Brand. Please select the proper Brand.\n";
		exit;
	}


	$sSQL = "SELECT DISTINCT(customer) FROM tbl_po WHERE NOT ISNULL(customer) AND customer!='' AND FIND_IN_SET(brand_id, '$Id') ORDER BY customer";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		print "OK";

		for ($i = 0; $i < $iCount; $i ++)
			print ("|-|".$objDb->getField($i, 0)."||".$objDb->getField($i, 0));
	}

	else
		print "ERROR|-|A Database Error occured. Please reload your webpage and try again.";


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>