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
	@require_once("../requires/image-functions.php");

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sSQL  = ("SELECT * FROM tbl_user_albums WHERE album LIKE '".IO::strValue("Album")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_user_albums");

		if ($_FILES['Picture']['name'] != "")
		{
			$sPicture = ($iId."-".IO::getFileName($_FILES['Picture']['name']));

			if (!@move_uploaded_file($_FILES['Picture']['tmp_name'], ($sBaseDir.USER_ALBUMS_IMG_PATH.'enlarged/'.$sPicture)))
					$sPicture = "";

			else
				createThumb(($sBaseDir.USER_ALBUMS_IMG_PATH."enlarged/".$sPicture), ($sBaseDir.USER_ALBUMS_IMG_PATH."thumbs/".$sPicture), 160);
		}

		$sSQL = ("INSERT INTO tbl_user_albums (id, user_id, album, description, picture, date_time) VALUES ('$iId', '{$_SESSION['UserId']}', '".IO::strValue("Album")."', '".IO::strValue("Description")."', '$sPicture', NOW( ))");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "USER_ALBUM_ADDED");

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

			@unlink($sBaseDir.USER_ALBUMS_IMG_PATH."thumbs/".$sPicture);
			@unlink($sBaseDir.USER_ALBUMS_IMG_PATH."enlarged/".$sPicture);
		}
	}

	else
		$_SESSION['Flag'] = "USER_ALBUM_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>