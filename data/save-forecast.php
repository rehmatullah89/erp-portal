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

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$iId = getNextId("tbl_forecasts");

	$sSQL = ("INSERT INTO tbl_forecasts (id, month, year, country_id, vendor_id, brand_id, season_id, style_id, quantity) VALUES ('$iId', '".IO::intValue("Month")."', '".IO::intValue("Year")."', '".IO::intValue("Region")."', '".IO::intValue("Vendor")."', '".IO::intValue("Brand")."', '".IO::intValue("Season")."', '".IO::intValue("StyleNo")."', '".IO::intValue("Quantity")."')");

	if ($objDb->execute($sSQL) == true)
		redirect($_SERVER['HTTP_REFERER'], "FORECAST_ADDED");

	else
		$_SESSION['Flag'] = "DB_ERROR";


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>