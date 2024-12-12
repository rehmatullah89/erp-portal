<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2008-15 (C) Triple Tree                                                        **
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

	$iId = getNextId("tbl_library");

	$sFile = "";
	$sDir  = "";

	switch (IO::strValue("Type"))
	{
		case "Image"         :  $sDir = "images/"; break;
		case "Pdf"           :  $sDir = "pdf/"; break;
		case "Video"         :  $sDir = "videos/"; break;
		case "Presentation"  :  $sDir = "ppt/"; break;
	}

	if ($_FILES['File']['name'] != "")
	{
		$sFile = ($iId."-".IO::getFileName($_FILES['File']['name']));

		if (!@move_uploaded_file($_FILES['File']['tmp_name'], ($sBaseDir.LIBRARY_FILES_DIR.$sDir.$sFile)))
				$sFile = "";
	}

	$sSQL = ("INSERT INTO tbl_library (id, parent_id, type, title, keywords, file, date_time) VALUES ('$iId', '".IO::intValue("Parent")."', '".IO::strValue("Type")."', '".IO::strValue("Title")."', '".IO::strValue("Keywords")."', '$sFile', NOW( ))");

	if ($objDb->execute($sSQL) == true)
		redirect($_SERVER['HTTP_REFERER'], "LIBRARY_ITEM_ADDED");

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		@unlink($sBaseDir.LIBRARY_FILES_DIR.$sDir.$sFile);
	}

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>