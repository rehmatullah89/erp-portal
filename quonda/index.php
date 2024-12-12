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

	else if (checkUserRights("quonda-graphs.php", $sModule, "view"))
		header("Location: quonda-graphs.php");

	else if (checkUserRights("qa-reports.php", $sModule, "view"))
		header("Location: qa-reports.php");

	else if (checkUserRights("vendor-reports.php", $sModule, "view"))
		header("Location: vendor-reports.php");

	else if (checkUserRights("auditors-swarm.php", $sModule, "view"))
		header("Location: auditors-swarm.php");

	else if (checkUserRights("auditor-groups.php", $sModule, "view"))
		header("Location: auditor-groups.php");

	else if (checkUserRights("auditors-correlation.php", $sModule, "view"))
		header("Location: auditors-correlation.php");

	else if (checkUserRights("auditors-productivity.php", $sModule, "view"))
		header("Location: auditors-productivity.php");

	else if (checkUserRights("emails.php", $sModule, "view"))
		header("Location: emails.php");

	else if (checkUserRights("lines.php", $sModule, "view"))
		header("Location: lines.php");

	else if (checkUserRights("audit-codes.php", $sModule, "view"))
		header("Location: audit-codes.php");

	else if (checkUserRights("quonda-reports.php", $sModule, "view"))
		header("Location: quonda-reports.php");

	else if (checkUserRights("reports.php", $sModule, "view"))
		header("Location: reports.php");

	else if (checkUserRights("defect-types.php", $sModule, "view"))
		header("Location: defect-types.php");

	else if (checkUserRights("defect-codes.php", $sModule, "view"))
		header("Location: defect-codes.php");

	else if (checkUserRights("defect-areas.php", $sModule, "view"))
		header("Location: defect-areas.php");

	else if (checkUserRights("defects-catalogue.php", $sModule, "view"))
		header("Location: defects-catalogue.php");

	else if (checkUserRights("csc-audits.php", $sModule, "view"))
		header("Location: csc-audits.php");

	else if (checkUserRights("reports-comparison.php", $sModule, "view"))
		header("Location: reports-comparison.php");

	else if (checkUserRights("audit-schedules.php", $sModule, "view"))
		header("Location: audit-schedules.php");

	else if (checkUserRights("qa-reports-analysis.php", $sModule, "view"))
		header("Location: qa-reports-analysis.php");

	else if (checkUserRights("signatures.php", $sModule, "view"))
		header("Location: signatures.php");
        
        else if (checkUserRights("booking-form.php", $sModule, "view"))
		header("Location: booking-form.php");

	else
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>