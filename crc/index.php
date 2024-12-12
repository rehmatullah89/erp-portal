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
	
	else if (checkUserRights("vmap.php", $sModule, "view"))
		header("Location: vmap.php");

	elseif (checkUserRights("ot-analysis.php", $sModule, "view"))
		header("Location: ot-analysis.php");

	else if (checkUserRights("ot-data.php", $sModule, "view"))
		header("Location: ot-data.php");

	else if (checkUserRights("era-converter.php", $sModule, "view"))
		header("Location: era-converter.php");

	else if (checkUserRights("categories.php", $sModule, "view"))
		header("Location: categories.php");

	else if (checkUserRights("weights.php", $sModule, "view"))
		header("Location: weights.php");

	else if (checkUserRights("reports.php", $sModule, "view"))
		header("Location: reports.php");

	else if (checkUserRights("safety-categories.php", $sModule, "view"))
		header("Location: safety-categories.php");

	else if (checkUserRights("safety-questions.php", $sModule, "view"))
		header("Location: safety-questions.php");

	else if (checkUserRights("safety-audits.php", $sModule, "view"))
		header("Location: safety-audits.php");

	else if (checkUserRights("quality-audits.php", $sModule, "view"))
		header("Location: quality-audits.php");

	else if (checkUserRights("quality-points.php", $sModule, "view"))
		header("Location: quality-points.php");

	else if (checkUserRights("compliance-types.php", $sModule, "view"))
		header("Location: compliance-types.php");

	else if (checkUserRights("compliance-categories.php", $sModule, "view"))
		header("Location: compliance-categories.php");

	else if (checkUserRights("compliance-questions.php", $sModule, "view"))
		header("Location: compliance-questions.php");

	else if (checkUserRights("compliance-audits.php", $sModule, "view"))
		header("Location: compliance-audits.php");

	else if (checkUserRights("production-categories.php", $sModule, "view"))
		header("Location: production-categories.php");

	else if (checkUserRights("production-questions.php", $sModule, "view"))
		header("Location: production-questions.php");

	else if (checkUserRights("production-audits.php", $sModule, "view"))
		header("Location: production-audits.php");

	else if (checkUserRights("certifications.php", $sModule, "view"))
		header("Location: certifications.php");

	else if (checkUserRights("vendor-certifications.php", $sModule, "view"))
		header("Location: vendor-certifications.php");

	else if (checkUserRights("tnc-dashboard.php", $sModule, "view"))
		header("Location: tnc-dashboard.php");
		
	else if (checkUserRights("tnc-audits.php", $sModule, "view"))
		header("Location: tnc-audits.php");

	else if (checkUserRights("tnc-sections.php", $sModule, "view"))
		header("Location: tnc-sections.php");

	else if (checkUserRights("tnc-categories.php", $sModule, "view"))
		header("Location: tnc-categories.php");

	else if (checkUserRights("tnc-points.php", $sModule, "view"))
		header("Location: tnc-points.php");
	
	else if (checkUserRights("chemicals-inventory.php", $sModule, "view"))
		header("Location: chemicals-inventory.php");
	
	else if (checkUserRights("chemical-compounds.php", $sModule, "view"))
		header("Location: chemical-compounds.php");
	
	else if (checkUserRights("chemical-types.php", $sModule, "view"))
		header("Location: chemical-types.php");
	
	else if (checkUserRights("chemical-locations.php", $sModule, "view"))
		header("Location: chemical-locations.php");
	
	else if (checkUserRights("chemical-location-type.php", $sModule, "view"))
		header("Location: chemical-location-type.php");

	else
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>