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

	$sAlbumPhoto = "";
	$sPhotos     = array( );

	$sSQL  = "SELECT picture FROM tbl_user_albums WHERE user_id='{$_SESSION['UserId']}' AND id='$Id'";
	$bFlag = $objDb->query($sSQL);

	if ($bFlag == true && $objDb->getCount( ) == 1)
	{
		$sAlbumPhoto = $objDb->getField(0, 0);


		$sSQL = "DELETE FROM tbl_user_albums WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$sSQL = "SELECT picture FROM tbl_user_photos WHERE album_id='$Id'";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
				$sPhotos[] = $objDb->getField($i, 0);

			$sSQL = "DELETE FROM tbl_user_photos WHERE album_id='$Id'";
			$bFlag = $objDb->execute($sSQL);
		}
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		@unlink($sBaseDir.USER_ALBUMS_IMG_PATH.'thumbs/'.$sAlbumPhoto);
		@unlink($sBaseDir.USER_ALBUMS_IMG_PATH.'enlarged/'.$sAlbumPhoto);

		for ($i = 0; $i < count($sPhotos); $i ++)
		{
			@unlink($sBaseDir.USER_PHOTOS_IMG_PATH."enlarged/".$sPhotos[$i]);
			@unlink($sBaseDir.USER_PHOTOS_IMG_PATH."thumbs/".$sPhotos[$i]);
		}

		$_SESSION['Flag'] = "USER_ALBUM_DELETED";
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