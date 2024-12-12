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

	if ($sUserRights['Delete'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id = IO::strValue('Id');

	$objDb->execute("BEGIN");

	$sCategoryPic = "";
	$sPictures    = array( );

	$sSQL  = "SELECT picture FROM tbl_fabric_categories WHERE id='$Id'";
	$bFlag = $objDb->query($sSQL);

	if ($bFlag == true && $objDb->getCount( ) == 1)
	{
		$sCategoryPic = $objDb->getField(0, 0);

		$sSQL = "DELETE FROM tbl_fabric_categories WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$sSQL = "SELECT picture FROM tbl_fabric_pictures WHERE category_id='$Id'";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
				$sPictures[] = $objDb->getField($i, 0);

			$sSQL = "DELETE FROM tbl_fabric_pictures WHERE category_id='$Id'";
			$bFlag = $objDb->execute($sSQL);
		}
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		@unlink($sBaseDir.FABRIC_CATEGORIES_IMG_PATH.$sCategoryPic);

		for ($i = 0; $i < count($sPictures); $i ++)
		{
			@unlink($sBaseDir.FABRIC_PICS_IMG_PATH."enlarged/".$sPictures[$i]);
			@unlink($sBaseDir.FABRIC_PICS_IMG_PATH."thumbs/".$sPictures[$i]);
		}

		$_SESSION['Flag'] = "FABRIC_CATEGORY_DELETED";
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";
	}

	header("Location: {$_SERVER['HTTP_REFERER']}");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>