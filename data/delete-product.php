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


	$Id = IO::intValue('Id');

	$sSQL = "Select picture_left, picture_right FROM tbl_fb_products WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sPictureLeft  = $objDb->getField(0, "picture_left");
		$sPictureRight = $objDb->getField(0, "picture_right");
	}


	$sSQL = "DELETE FROM tbl_fb_products WHERE id='$Id'";

	if ($objDb->execute($sSQL) == true)
	{
		@unlink($sBaseDir.MDL_PRODUCTS_DIR.$sPictureLeft);
		@unlink($sBaseDir.MDL_PRODUCTS_DIR.$sPictureRight);

		$_SESSION['Flag'] = "MDL_PRODUCT_DELETED";
	}

	else
		$_SESSION['Flag'] = "DB_ERROR";

	header("Location: {$_SERVER['HTTP_REFERER']}");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>