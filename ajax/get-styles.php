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

	$Brand  = IO::intValue("Brand");
	$Season = IO::intValue("Season");
	$List   = IO::strValue("List");

	if ($Brand == 0)
	{
		print "ERROR|-|Invalid Brand. Please select the proper Brand.\n";
		exit;
	}
/*
	if ($Season == 0)
	{
		print "ERROR|-|Invalid Season. Please select the proper Season.\n";
		exit;
	}
*/

	if ($Season == 0)
	{
		$sSQL = "SELECT id, style,
						(SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id) AS _Season
				 FROM tbl_styles
				 WHERE sub_brand_id='$Brand'
				 ORDER BY style";

		if ($objDb->query($sSQL) == true)
		{
			$iCount = $objDb->getCount( );

			print ("OK|-|".$List);

			for ($i = 0; $i < $iCount; $i ++)
				print ("|-|".$objDb->getField($i, 0)."||".$objDb->getField($i, 1)." (".$objDb->getField($i, 2).")");
		}

		else
			print "ERROR|-|A Database Error occured. Please reload your webpage and try again.";
	}

	else
	{
		$sSQL = "SELECT id, style FROM tbl_styles WHERE sub_brand_id='$Brand' AND sub_season_id='$Season' ORDER BY style";

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

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>