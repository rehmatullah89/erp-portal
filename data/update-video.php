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

	$Id       = IO::intValue("Id");
	$OldVideo = IO::strValue("OldVideo");

	if ($_FILES['Video']['name'] != "")
	{
		$sVideo = ($Id."-".IO::getFileName($_FILES['Video']['name']));

		if (!@move_uploaded_file($_FILES['Video']['tmp_name'], ($sBaseDir.VIDEO_FILES_DIR.$sVideo)))
				$sVideo = "";
	}

	if ($sVideo != "")
		$sVideoSql = ", video='$sVideo' ";


	$sSQL = ("UPDATE tbl_videos SET title='".IO::strValue("Title")."', description='".IO::strValue("Description")."' $sVideoSql WHERE id='$Id'");

	if ($objDb->execute($sSQL) == true)
	{
		if ($sVideo != "" && $OldVideo != "" && $sVideo != $OldVideo)
			@unlink($sBaseDir.VIDEO_FILES_DIR.$OldVideo);

		redirect($_SERVER['HTTP_REFERER'], "VIDEO_UPDATED");
	}

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		if ($sVideo != "" && $sVideo != $OldVideo)
			@unlink($sBaseDir.VIDEO_FILES_DIR.$sVideo);

		redirect($_SERVER['HTTP_REFERER'], "DB_ERROR");
	}

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>