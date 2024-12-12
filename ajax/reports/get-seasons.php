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

	$Brands = IO::strValue("Brands");

	if ($Brands == "")
	{
		print "ERROR|-|Invalid Brand. Please select the proper Season.\n";
		exit;
	}


	$iBrands  = @explode(",", $Brands);
	$iParents = array( );

	foreach ($iBrands as $iBrand)
	{
		$iParents[] = getDbValue("parent_id", "tbl_brands", "id='$iBrand'");
	}

	$sBrands     = @implode(",", $iParents);
	$sBrandsList = getList("tbl_brands", "id", "brand", "parent_id='0'");


	$sSQL = "SELECT id, brand_id, season FROM tbl_seasons WHERE FIND_IN_SET(brand_id, '$sBrands') AND parent_id>'0'";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		print "OK";

		for ($i = 0; $i < $iCount; $i ++)
			print ("|-|".$objDb->getField($i, 0)."||".$sBrandsList[$objDb->getField($i, 1)].' > '.$objDb->getField($i, 2));
	}

	else
		print "ERROR|-|A Database Error occured. Please reload your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>