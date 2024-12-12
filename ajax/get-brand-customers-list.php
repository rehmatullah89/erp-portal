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

	$Id   = IO::intValue("Id");
	$List = IO::strValue("List");

	if ($Id == 0)
	{
		print "ERROR|-|Invalid Brand. Please select the proper Brand.\n";
		exit;
	}


	$iParent = getDbValue("parent_id", "tbl_brands", "id='$Id'");

	if ($iParent > 0)
		$Id = $iParent;


	$sSQL = "SELECT customer, customer FROM tbl_customers WHERE brand_id='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		print ("OK|-|".$List);

		for ($i = 0; $i < $iCount; $i ++)
			print ("|-|".$objDb->getField($i, 0)."||".$objDb->getField($i, 1));
	}

	else
		print "ERROR|-|A Database Error occured. Please reload your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>