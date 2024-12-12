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


	$Id         = IO::intValue("Id");
	$Vendors    = IO::strValue("Vendors");
	$Brands     = IO::strValue("Brands");
	$Categories = IO::strValue("Categories");
	$sError     = "";

	$sSQL = "SELECT user_id FROM tbl_etd_managers WHERE user_id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Manager ID. Please select the proper Record to Edit.\n";
		exit( );
	}

	if ($Vendors == "")
		$sError .= "- Invalid Vendors Selection\n";

	if ($Brands == "")
		$sError .= "- Invalid Brands Selection\n";

	if ($Categories == "")
		$sError .= "- Invalid Categories Selection\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL = "UPDATE tbl_etd_managers SET vendors='$Vendors', brands='$Brands', categories='$Categories' WHERE user_id='$Id'";

	if ($objDb->execute($sSQL) == true)
	{
		$sVendorsList = getList("tbl_vendors", "id", "vendor");
		$iVendors     = @explode(",", $Vendors);
		$sVendors     = "";

		foreach ($iVendors as $iVendor)
			$sVendors .= ($sVendorsList[$iVendor]."<br />");


		$sBrandsList = getList("tbl_brands", "id", "brand");
		$iBrands     = @explode(",", $Brands);
		$sBrands     = "";

		foreach ($iBrands as $iBrand)
			$sBrands .= ($sBrandsList[$iBrand]."<br />");


		$sCategoriesList = getList("tbl_style_categories", "id", "category");
		$iCategories     = @explode(",", $Categories);
		$sCategories     = "";

		foreach ($iCategories as $iCategory)
			$sCategories .= ($sCategoriesList[$iCategory]."<br />");


		print "OK|-|$Id|-|<div>The selected ETD Manager has been Updated successfully.</div>|-|$sVendors|-|$sBrands|-|$sCategories";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>