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
		$iId = getNextId("tbl_fabric_pictures");

		$sPicture = "";

		if ($_FILES['Picture'.$i]['name'] != "")
		{
			$sPicture = ($iId."-".IO::getFileName($_FILES['Picture'.$i]['name']));

			if (!@move_uploaded_file($_FILES['Picture'.$i]['tmp_name'], ($sBaseDir.FABRIC_PICS_IMG_PATH."enlarged/".$sPicture)))
					$sPicture = "";

			else
				createSlideshowThumb(($sBaseDir.FABRIC_PICS_IMG_PATH."enlarged/".$sPicture), ($sBaseDir.FABRIC_PICS_IMG_PATH."thumbs/".$sPicture));
		}

		if ($sPicture != "")
		{
			$sSQL = ("INSERT INTO tbl_fabric_pictures (id, category_id, caption, picture) VALUES ('$iId', '".IO::intValue("Category")."', '".IO::strValue("Caption".$i)."', '$sPicture')");
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == false)
			break;
	}


	if ($bFlag == true)
			redirect($_SERVER['HTTP_REFERER'], "FABRIC_CATEGORY_PICS_ADDED");

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		@unlink($sBaseDir.FABRIC_PICS_IMG_PATH."enlarged/".$sPicture);
		@unlink($sBaseDir.FABRIC_PICS_IMG_PATH."thumbs/".$sPicture);
	}

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>