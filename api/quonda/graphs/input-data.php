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

	$User      = IO::intValue("User");
	$Step      = IO::intValue("Step");
	$Sector    = IO::strValue("Sector");
	$Category  = IO::intValue("Category");
	$OrderNo   = IO::strValue("OrderNo");
	$AuditCode = IO::strValue("AuditCode");
	$Vendor    = IO::strValue("Vendor");
	$Brand     = IO::strValue("Brand");
	$FromDate  = IO::strValue("FromDate");
	$ToDate    = IO::strValue("ToDate");
	$Color     = IO::strValue("Color");


	if (@strpos($Vendor, ",") !== FALSE)
		$Vendor = 0;

	if (@strpos($Brand, ",") !== FALSE)
		$Brand = 0;


	$sUserVendors = getDbValue("vendors", "tbl_users", "id='$User'");
	$sUserBrands  = getDbValue("brands", "tbl_users", "id='$User'");


	if ($FromDate == "" || $ToDate == "")
	{
		if ($OrderNo == "" && $AuditCode == "" && $Vendor == "" && $Brand == "" && $Color == "")
		{
			$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 3), date("Y")));
			$ToDate   = date("Y-m-d");
		}

		else if ($OrderNo != "" || $AuditCode != "")
		{
			$FromDate = date("Y-m-d", mktime(0, 0, 0, (date("m") - 6), date("d"), date("Y")));
			$ToDate   = date("Y-m-d");
		}
	}


	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ($sUserVendors) AND parent_id='0'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ($sUserBrands)");


	if ($Vendor > 0)
	{
		$sSQL = "SELECT category_id FROM tbl_vendors WHERE id='$Vendor'";
		$objDb->query($sSQL);

		$Category = $objDb->getField(0, 0);
	}

	else if ($Brand > 0)
	{
		$Category = 0;
		$Color    = "";
	}
?>