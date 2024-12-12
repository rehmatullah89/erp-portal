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

	if ($sUserRights['Delete'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id = IO::strValue('Id');

	$objDb->execute("BEGIN");

	$sSQL  = "SELECT file, type FROM tbl_library WHERE id='$Id'";
	$bFlag = $objDb->query($sSQL);

	if ($bFlag == true && $objDb->getCount( ) == 1)
	{
		$sFile = $objDb->getField(0, 'file');
		$sType = $objDb->getField(0, 'type');


		$sSQL  = "DELETE FROM tbl_library WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true && $sType != "Category")
		{
			switch ($sType)
			{
				case "Image"  :  @unlink($sBaseDir.LIBRARY_FILES_DIR."images/".$sFile); break;
				case "Pdf"    :  @unlink($sBaseDir.LIBRARY_FILES_DIR."pdf/".$sFile); break;
				case "Video"  :  @unlink($sBaseDir.LIBRARY_FILES_DIR."videos/".$sFile); break;
			}
		}
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		$_SESSION['Flag'] = "LIBRARY_ITEM_DELETED";
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";
	}

	header("Location: {$_SERVER['HTTP_REFERER']}");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>