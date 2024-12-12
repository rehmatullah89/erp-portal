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


	if (@strpos($Id, ",") === FALSE)
	{
		$iParent = getDbValue("parent_id", "tbl_brands", "id='$Id'");

		if ($iParent > 0)
			$Id = $iParent;


		$sSQL = "SELECT id, season FROM tbl_seasons WHERE brand_id='$Id' AND parent_id>'0'";

		if ($objDb->query($sSQL) == true)
		{
			$iCount = $objDb->getCount( );

			print ("OK|-|".$List);

			for ($i = 0; $i < $iCount; $i ++)
				print ("|-|".$objDb->getField($i, 0)."||".$objDb->getField($i, 1));
		}

		else
			print "ERROR|-|A Database Error occured. Please reload your webpage and try again.";
	}


	else
	{
		$sSQL = "SELECT DISTINCT(id), brand FROM tbl_brands WHERE parent_id='0' AND (FIND_IN_SET(id, '$Id') OR id IN (SELECT parent_id FROM tbl_brands WHERE FIND_IN_SET(id, '$Id'))) ORDER by brand";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 0)
		{
			print "OK";

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iBrand = $objDb->getField($i, 0);
				$sBrand = $objDb->getField($i, 1);


				$sSQL = "SELECT id, season FROM tbl_seasons WHERE brand_id='$iBrand' AND parent_id>'0'";
				$objDb2->query($sSQL);

				$iCount2 = $objDb2->getCount( );

				for ($j = 0; $j < $iCount2; $j ++)
					print ("|-|".$objDb2->getField($j, 0)."||{$sBrand} > ".$objDb2->getField($j, 1));
			}
		}
	}


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>