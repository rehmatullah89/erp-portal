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

	$bFlag = true;

	for ($i = 1; $i <= 5; $i ++)
	{
		$iId = getNextId("tbl_user_photos");

		$sPicture = "";

		if ($_FILES['Picture'.$i]['name'] != "")
		{
			$sPicture = ($iId."-".IO::getFileName($_FILES['Picture'.$i]['name']));

			if (!@move_uploaded_file($_FILES['Picture'.$i]['tmp_name'], ($sBaseDir.USER_PHOTOS_IMG_PATH."enlarged/".$sPicture)))
					$sPicture = "";

			else
				createThumb(($sBaseDir.USER_PHOTOS_IMG_PATH."enlarged/".$sPicture), ($sBaseDir.USER_PHOTOS_IMG_PATH."thumbs/".$sPicture), 160);
		}

		if ($sPicture != "")
		{
			$sSQL = ("INSERT INTO tbl_user_photos (id, album_id, caption, picture, date_time) VALUES ('$iId', '".IO::intValue("Album")."', '".IO::strValue("Caption".$i)."', '$sPicture', NOW( ))");
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == false)
			break;
	}


	if ($bFlag == true)
			redirect($_SERVER['HTTP_REFERER'], "USER_PHOTOS_ADDED");

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		@unlink($sBaseDir.USER_PHOTOS_IMG_PATH."enlarged/".$sPicture);
		@unlink($sBaseDir.USER_PHOTOS_IMG_PATH."thumbs/".$sPicture);
	}

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>