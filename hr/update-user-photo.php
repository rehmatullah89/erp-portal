<?
	/****************************************************************************\
	******************************************************************************
	**                                                                          **
	**  Triple Tree Customer Portal                                                  **
	**  Version 2.0                                                             **
	**                                                                          **
	**  http://portal.apparelco.com                                             **
	**                                                                          **
	**  Copyright 2008-10 (C) Triple Tree                                   **
	**                                                                          **
	**  **********************************************************************  **
	**                                                                          **
	**  Developer Information:                                                  **
	**                                                                          **
	**      Name  :  Muhammad Tahir Shahzad                                     **
	**      Email :  mtahirshahzad@hotmail.com                                  **
	**      Phone :  +92 333 456 0482                                           **
	**      URL   :  http://mts.sw3solutions.com                                **
	**                                                                          **
	**  **********************************************************************  **
	**                                                                          **
	**                                                                          **
	**                                                                          **
	**                                                                          **
	******************************************************************************
	\****************************************************************************/

	@require_once("../requires/session.php");
	@require_once("../requires/image-functions.php");

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id         = IO::intValue("Id");
	$OldPicture = IO::strValue("OldPicture");

	if ($_FILES['Picture']['name'] != "")
	{
		$sPicture = ($Id."-".IO::getFileName($_FILES['Picture']['name']));

		if (!@move_uploaded_file($_FILES['Picture']['tmp_name'], ($sBaseDir.USER_PHOTOS_IMG_PATH."enlarged/".$sPicture)))
				$sPicture = "";

		else
			createThumb(($sBaseDir.USER_PHOTOS_IMG_PATH."enlarged/".$sPicture), ($sBaseDir.USER_PHOTOS_IMG_PATH."thumbs/".$sPicture), 160);
	}

	if ($sPicture != "")
		$sPictureSql = ", picture='$sPicture' ";

	$sSQL = ("UPDATE tbl_user_photos SET album_id='".IO::intValue("Album")."', caption='".IO::strValue("Caption")."' $sPictureSql WHERE id='$Id'");

	if ($objDb->execute($sSQL) == true)
	{
		if ($sPicture != "" && $OldPicture != "" && $sPicture != $OldPicture)
		{
			@unlink($sBaseDir.USER_PHOTOS_IMG_PATH."enlarged/".$OldPicture);
			@unlink($sBaseDir.USER_PHOTOS_IMG_PATH."thumbs/".$OldPicture);
		}

		redirect($_SERVER['HTTP_REFERER'], "USER_PHOTO_UPDATED");
	}

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		if ($sPicture != "" && $sPicture != $OldPicture)
		{
			@unlink($sBaseDir.USER_PHOTOS_IMG_PATH."enlarged/".$sPicture);
			@unlink($sBaseDir.USER_PHOTOS_IMG_PATH."thumbs/".$sPicture);
		}
	}

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>