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

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id      = IO::intValue("Id");
	$OldFile = IO::strValue("OldFile");
	$sDir    = "";

	switch (IO::strValue("Type"))
	{
		case "Image"         :  $sDir = "images/"; break;
		case "Pdf"           :  $sDir = "pdf/"; break;
		case "Video"         :  $sDir = "videos/"; break;
		case "Presentation"  :  $sDir = "ppt/"; break;
	}

	if ($_FILES['File']['name'] != "")
	{
		$sFile = ($Id."-".IO::getFileName($_FILES['File']['name']));

		if (!@move_uploaded_file($_FILES['File']['tmp_name'], ($sBaseDir.LIBRARY_FILES_DIR.$sDir.$sFile)))
				$sFile = "";
	}

	if ($sFile != "")
		$sFileSql = ", file='$sFile' ";


	$sSQL = ("UPDATE tbl_library SET title='".IO::strValue("Title")."', keywords='".IO::strValue("Keywords")."', parent_id='".IO::intValue("Parent")."', type='".IO::strValue("Type")."' $sFileSql WHERE id='$Id'");

	if ($objDb->execute($sSQL) == true)
	{
		if ($OldFile != "" && $sFile != "" && $sFile != $OldFile)
			@unlink($sBaseDir.LIBRARY_FILES_DIR.$sDir.$OldFile);

		redirect($_SERVER['HTTP_REFERER'], "LIBRARY_ITEM_UPDATED");
	}

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		if ($sFile != "" && $sFile != $OldFile)
			@unlink($sBaseDir.LIBRARY_FILES_DIR.$sDir.$sFile);
	}

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>