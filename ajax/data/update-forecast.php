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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	if ($sUserRights['Edit'] != "Y")
	{
		print "ERROR|-|You havn\'t enough Rights to modify the selected Data.\n";
		exit( );
	}

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sMonthsList = array('January','February','March','April','May','June','July','August','September','October','November','December');

	$Id       = IO::intValue("Id");
	$Month    = IO::intValue("Month");
	$Year     = IO::intValue("Year");
	$Region   = IO::intValue("Region");
	$Vendor   = IO::intValue("Vendor");
	$Brand    = IO::intValue("Brand");
	$Season   = IO::intValue("Season");
	$StyleNo  = IO::intValue("StyleNo");
	$Quantity = IO::intValue("Quantity");
	$sError   = "";

	$sSQL = "SELECT id FROM tbl_forecasts WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Forecast ID. Please select the proper Forecast Entry to Edit.\n";
		exit( );
	}

	if ($Month == 0)
		$sError .= "- Invalid Month\n";

	else
		$sMonth = $sMonthsList[($Month - 1)];

	if ($Year == 0)
		$sError .= "- Invalid Year\n";

	if ($Region == 0)
		$sError .= "- Invalid Region\n";

	if ($Vendor == 0 && $Brand == 0)
		$sError .= "- Invalid Vendor / Brand\n";

	if ($Vendor > 0)
	{
		$sSQL = "SELECT vendor, (SELECT category FROM tbl_categories WHERE id=tbl_vendors.category_id) AS _Category FROM tbl_vendors WHERE id='$Vendor'";
		$objDb->query($sSQL);

		$sVendor   = $objDb->getField(0, 0);
		$sCategory = $objDb->getField(0, 1);
	}

	if ($Brand > 0)
	{
		$sSQL = "SELECT brand FROM tbl_brands WHERE id='$Brand'";
		$objDb->query($sSQL);

		$sBrand = $objDb->getField(0, 0);
	}

	if ($Season == 0)
		$sError .= "- Invalid Season\n";

	else
	{
		$sSQL = "SELECT season FROM tbl_seasons WHERE id='$Season'";
		$objDb->query($sSQL);

		$sSeason = $objDb->getField(0, 0);
	}

	if ($StyleNo > 0)
	{
		$sSQL = "SELECT style FROM tbl_styles WHERE id='$StyleNo'";
		$objDb->query($sSQL);

		$sStyle = $objDb->getField(0, 0);
	}

	if ($Quantity == 0)
		$sError .= "- Invalid Quantity\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL = "UPDATE tbl_forecasts SET month='$Month', year='$Year', country_id='$Region', vendor_id='$Vendor', brand_id='$Brand', season_id='$Season', style_id='$StyleNo', quantity='$Quantity' WHERE id='$Id'";

	if ($objDb->execute($sSQL) == true)
		print "OK|-|$Id|-|<div>The selected Forecast Entry has been Updated successfully.</div>|-|$sMonth|-|$Year|-|$sCategory|-|$sVendor|-|$sBrand|-|$sSeason|-|$sStyle|-|$Quantity";

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>