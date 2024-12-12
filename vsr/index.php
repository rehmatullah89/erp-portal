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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	if (checkUserRights("vsr.php", $sModule, "view"))
		header("Location: vsr.php");

	else if (checkUserRights("vsr-details.php", $sModule, "view"))
		header("Location: vsr-details.php");

	else if (checkUserRights("vsr-data.php", $sModule, "view"))
		header("Location: vsr-data.php");

	else if (checkUserRights("etd-revisions.php", $sModule, "view"))
		header("Location: etd-revisions.php");

	else if (checkUserRights("work-order-details.php", $sModule, "view"))
		header("Location: work-order-details.php");

	else if (checkUserRights("work-orders.php", $sModule, "view"))
		header("Location: work-orders.php");

	else
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>