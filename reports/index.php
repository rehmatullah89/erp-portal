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

	if (checkUserRights("commission-report.php", $sModule, "view"))
		header("Location: commission-report.php");

	else if (checkUserRights("shipment-summary.php", $sModule, "view"))
		header("Location: shipment-summary.php");

	else if (checkUserRights("quality-report.php", $sModule, "view"))
		header("Location: quality-report.php");

	else if (checkUserRights("invoice-report.php", $sModule, "view"))
		header("Location: invoice-report.php");

	else if (checkUserRights("shipping-report.php", $sModule, "view"))
		header("Location: shipping-report.php");

	else if (checkUserRights("inspection-certificate.php", $sModule, "view"))
		header("Location: inspection-certificate.php");

	else if (checkUserRights("measurements-report.php", $sModule, "view"))
		header("Location: measurements-report.php");

	else if (checkUserRights("ss-commission-report.php", $sModule, "view"))
		header("Location: ss-commission-report.php");

	else if (checkUserRights("attendance-report.php", $sModule, "view"))
		header("Location: attendance-report.php");

	else if (checkUserRights("portal-usuage-report.php", $sModule, "view"))
		header("Location: portal-usuage-report.php");

	else if (checkUserRights("current-standing.php", $sModule, "view"))
		header("Location: current-standing.php");

	else if (checkUserRights("vsr-report.php", $sModule, "view"))
		header("Location: vsr-report.php");

	else if (checkUserRights("kpis-report.php", $sModule, "view"))
		header("Location: kpis-report.php");

	else if (checkUserRights("invoice-generator.php", $sModule, "view"))
		header("Location: invoice-generator.php");

	else if (checkUserRights("mgf-report.php", $sModule, "view"))
		header("Location: mgf-report.php");
        
        else if (checkUserRights("levis-report.php", $sModule, "view"))
		header("Location: levis-report.php");
        
        else if (checkUserRights("oql-report.php", $sModule, "view"))
		header("Location: oql-report.php");
        
        else if (checkUserRights("triumph-report.php", $sModule, "view"))
		header("Location: triumph-report.php");

	else
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>