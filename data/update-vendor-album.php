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

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id         = IO::intValue("Id");
	$OldPicture = IO::strValue("OldPicture");

	$sSQL  = ("SELECT * FROM tbl_vendor_profile_albums WHERE album LIKE '".IO::strValue("Album")."' AND id!='$Id'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		if ($_FILES['Picture']['name'] != "")
		{
			$sPicture = ($Id."-".IO::getFileName($_FILES['Picture']['name']));

			if (!@move_uploaded_file($_FILES['Picture']['tmp_name'], ($sBaseDir.VENDOR_ALBUMS_IMG_PATH.$sPicture)))
					$sPicture = "";
		}

		if ($sPicture != "")
			$sPictureSql = ", picture='$sPicture' ";

		$sSQL = ("UPDATE tbl_vendor_profile_albums SET album='".IO::strValue("Album")."', description='".IO::strValue("Description")."' $sPictureSql WHERE id='$Id'");

		if ($objDb->execute($sSQL) == true)
		{
			if ($sPicture != "" && $OldPicture != "" && $sPicture != $OldPicture)
				@unlink($sBaseDir.VENDOR_ALBUMS_IMG_PATH.$OldPicture);

			redirect($_SERVER['HTTP_REFERER'], "VENDOR_ALBUM_UPDATED");
		}

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

			if ($sPicture != "" && $sPicture != $OldPicture)
				@unlink($sBaseDir.VENDOR_ALBUMS_IMG_PATH.$sPicture);
		}
	}

	else
		$_SESSION['Flag'] = "VENDOR_ALBUM_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>