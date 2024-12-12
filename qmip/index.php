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

	if (checkUserRights("dashboard.php", $sModule, "view"))
		header("Location: dashboard.php");

	else if (checkUserRights("qmip-graphs.php", $sModule, "view"))
		header("Location: qmip-graphs.php");

	else if (checkUserRights("operators.php", $sModule, "view"))
		header("Location: operators.php");

	else if (checkUserRights("operations.php", $sModule, "view"))
		header("Location: operations.php");

	else if (checkUserRights("machine-types.php", $sModule, "view"))
		header("Location: machine-types.php");

	else if (checkUserRights("machines.php", $sModule, "view"))
		header("Location: machines.php");

	else if (checkUserRights("qa-reports.php", $sModule, "view"))
		header("Location: qa-reports.php");

	else if (checkUserRights("qa-reports-analysis.php", $sModule, "view"))
		header("Location: qa-reports-analysis.php");

	else if (checkUserRights("brands.php", $sModule, "view"))
		header("Location: brands.php");

	else
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>