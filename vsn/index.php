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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	if (checkUserRights("vsn.php", $sModule, "view"))
		header("Location: vsn.php");

	else if (checkUserRights("reports-comparison.php", $sModule, "view"))
		header("Location: reports-comparison.php");

	else if (checkUserRights("otp.php", $sModule, "view"))
		header("Location: otp.php");

	else if (checkUserRights("seasons-otp.php", $sModule, "view"))
		header("Location: seasons-otp.php");

	else
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>