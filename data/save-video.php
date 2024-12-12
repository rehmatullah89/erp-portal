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

	$sSQL  = ("SELECT * FROM tbl_videos WHERE title LIKE '".IO::strValue("Title")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_videos");

		if ($_FILES['Video']['name'] != "")
		{
			$sVideo = ($iId."-".IO::getFileName($_FILES['Video']['name']));

			if (!@move_uploaded_file($_FILES['Video']['tmp_name'], ($sBaseDir.VIDEO_FILES_DIR.$sVideo)))
					$sVideo = "";
		}


		if ($sVideo == "")
			$_SESSION['Flag'] = "ERROR";

		else
		{
			$sSQL = ("INSERT INTO tbl_videos (id, title, description, video) VALUES ('$iId', '".IO::strValue("Title")."', '".IO::strValue("Description")."', '$sVideo')");

			if ($objDb->execute($sSQL) == true)
				redirect($_SERVER['HTTP_REFERER'], "VIDEO_ADDED");

			else
			{
				$_SESSION['Flag'] = "DB_ERROR";

				@unlink($sBaseDir.VIDEO_FILES_DIR.$sVideo);
			}
		}
	}

	else
		$_SESSION['Flag'] = "VIDEO_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>