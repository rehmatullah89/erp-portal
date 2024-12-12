<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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

	if (checkUserRights("purchase-orders.php", $sModule, "view"))
		header("Location: purchase-orders.php");

	else if (checkUserRights("po-commission.php", $sModule, "view"))
		header("Location: po-commission.php");

	else if (checkUserRights("countries.php", $sModule, "view"))
		header("Location: countries.php");

	else if (checkUserRights("categories.php", $sModule, "view"))
		header("Location: categories.php");

	else if (checkUserRights("vendors.php", $sModule, "view"))
		header("Location: vendors.php");

	else if (checkUserRights("brands.php", $sModule, "view"))
		header("Location: brands.php");

	else if (checkUserRights("seasons.php", $sModule, "view"))
		header("Location: seasons.php");

	else if (checkUserRights("destinations.php", $sModule, "view"))
		header("Location: destinations.php");

	else if (checkUserRights("sizes.php", $sModule, "view"))
		header("Location: sizes.php");

	else if (checkUserRights("styles.php", $sModule, "view"))
		header("Location: styles.php");

	else if (checkUserRights("programs.php", $sModule, "view"))
		header("Location: programs.php");

	else if (checkUserRights("forecasts.php", $sModule, "view"))
		header("Location: forecasts.php");

	else if (checkUserRights("revised-forecasts.php", $sModule, "view"))
		header("Location: revised-forecasts.php");

	else if (checkUserRights("vendor-profiles.php", $sModule, "view"))
		header("Location: vendor-profiles.php");

	else if (checkUserRights("fabric-library.php", $sModule, "view"))
		header("Location: fabric-library.php");

	else if (checkUserRights("blog-categories.php", $sModule, "view"))
		header("Location: blog-categories.php");

	else if (checkUserRights("blog.php", $sModule, "view"))
		header("Location: blog.php");

	else if (checkUserRights("etd-revision-reasons.php", $sModule, "view"))
		header("Location: etd-revision-reasons.php");

	else if (checkUserRights("etd-revision-requests.php", $sModule, "view"))
		header("Location: etd-revision-requests.php");

	else if (checkUserRights("brand-offices.php", $sModule, "view"))
		header("Location: brand-offices.php");

	else if (checkUserRights("videos.php", $sModule, "view"))
		header("Location: videos.php");

	else if (checkUserRights("products.php", $sModule, "view"))
		header("Location: products.php");

	else if (checkUserRights("flipbooks.php", $sModule, "view"))
		header("Location: flipbooks.php");

	else if (checkUserRights("cotton-rates.php", $sModule, "view"))
		header("Location: cotton-rates.php");

	else if (checkUserRights("yarn-rates.php", $sModule, "view"))
		header("Location: yarn-rates.php");

	else
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>