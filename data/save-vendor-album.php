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

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sSQL  = ("SELECT * FROM tbl_vendor_profile_albums WHERE album LIKE '".IO::strValue("Album")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_vendor_profile_albums");

		if ($_FILES['Picture']['name'] != "")
		{
			$sPicture = ($iId."-".IO::getFileName($_FILES['Picture']['name']));

			if (!@move_uploaded_file($_FILES['Picture']['tmp_name'], ($sBaseDir.VENDOR_ALBUMS_IMG_PATH.$sPicture)))
					$sPicture = "";
		}

		$sSQL = ("INSERT INTO tbl_vendor_profile_albums (id, album, description, picture) VALUES ('$iId', '".IO::strValue("Album")."', '".IO::strValue("Description")."', '$sPicture')");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "VENDOR_ALBUM_ADDED");

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

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