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

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id         = IO::intValue("Id");
	$OldPicture = IO::strValue("OldPicture");

	if ($_FILES['Picture']['name'] != "")
	{
		$sPicture = ($Id."-".IO::getFileName($_FILES['Picture']['name']));

		if (!@move_uploaded_file($_FILES['Picture']['tmp_name'], ($sBaseDir.VENDOR_PICS_IMG_PATH."enlarged/".$sPicture)))
				$sPicture = "";

		else
			createSlideshowThumb(($sBaseDir.VENDOR_PICS_IMG_PATH."enlarged/".$sPicture), ($sBaseDir.VENDOR_PICS_IMG_PATH."thumbs/".$sPicture));
	}

	if ($sPicture != "")
		$sPictureSql = ", picture='$sPicture' ";

	$sSQL = ("UPDATE tbl_vendor_profile_pictures SET album_id='".IO::intValue("Album")."', caption='".IO::strValue("Caption")."' $sPictureSql WHERE id='$Id'");

	if ($objDb->execute($sSQL) == true)
	{
		if ($sPicture != "" && $OldPicture != "" && $sPicture != $OldPicture)
		{
			@unlink($sBaseDir.VENDOR_PICS_IMG_PATH."enlarged/".$OldPicture);
			@unlink($sBaseDir.VENDOR_PICS_IMG_PATH."thumbs/".$OldPicture);
		}

		redirect($_SERVER['HTTP_REFERER'], "VENDOR_ALBUM_PIC_UPDATED");
	}

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		if ($sPicture != "" && $sPicture != $OldPicture)
		{
			@unlink($sBaseDir.VENDOR_PICS_IMG_PATH."enlarged/".$sPicture);
			@unlink($sBaseDir.VENDOR_PICS_IMG_PATH."thumbs/".$sPicture);
		}
	}

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>