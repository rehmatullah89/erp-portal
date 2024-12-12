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

	$Brand    = IO::strValue("Brand");
	$Category = IO::strValue("Category");

	if ($Brand == 0)
	{
		print "ERROR|-|Invalid Brand. Please select the Brand.\n";
		exit;
	}

	if ($Category == 0)
	{
		print "ERROR|-|Invalid Category. Please select the Category.\n";
		exit;
	}


	$sSQL = "SELECT id, point_id, point FROM tbl_measurement_points WHERE category_id='$Category' AND brand_id='$Brand' ORDER BY point";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		print "OK";

		for($i = 0; $i < $iCount; $i ++)
		{
			$sKey     = $objDb->getField($i, 0);
			$sPointId = $objDb->getField($i, 1);
			$sPoint   = $objDb->getField($i, 2);

			print ("|-|".$sKey."||".$sPointId." - ".$sPoint);
		}
	}

	else
		print "ERROR|-|A Database Error occured. Please reload your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>