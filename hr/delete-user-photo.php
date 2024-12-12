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

	$sSQL  = "SELECT picture FROM tbl_user_photos WHERE id='$Id'";
	$bFlag = $objDb->query($sSQL);

	if ($bFlag == true && $objDb->getCount( ) == 1)
	{
		$sPicture = $objDb->getField(0, 0);


		$sSQL = "DELETE FROM tbl_user_photos WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			@unlink($sBaseDir.USER_PHOTOS_IMG_PATH."enlarged/".$sPicture);
			@unlink($sBaseDir.USER_PHOTOS_IMG_PATH."thumbs/".$sPicture);
		}
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		$_SESSION['Flag'] = "USER_PHOTO_DELETED";
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