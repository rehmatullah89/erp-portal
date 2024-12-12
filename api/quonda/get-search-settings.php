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

	$User = IO::intValue('User');


	$aResponse = array( );


	if ($User == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid User ID";
	}

	else
	{
		$sStatus = getDbValue("status", "tbl_users", "id='$User'");

		if ($sStatus != "A")
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "User Account is Disabled";
		}

		else
		{
			$sUserVendors = getDbValue("vendors", "tbl_users", "id='$User'");
			$sUserBrands  = getDbValue("brands", "tbl_users", "id='$User'");
			$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ($sUserVendors) AND parent_id='0'");
			$sBrandsList  = getList("tbl_brands", "id", "brand", "id IN ($sUserBrands)");
			$sReportsList = getList("tbl_reports", "id", "report");


			$sVendors = array( );
			$sBrands  = array( );
			$sReports = array( );

			$sVendors[] = "0||All Vendors";
			$sBrands[]  = "0||All Brands";
			$sReports[] = "0||All Types";

			foreach ($sVendorsList as $sKey => $sValue)
				$sVendors[] = "{$sKey}||{$sValue}";

			foreach ($sBrandsList as $sKey => $sValue)
				$sBrands[] = "{$sKey}||{$sValue}";

			foreach ($sReportsList as $sKey => $sValue)
				$sReports[] = "{$sKey}||{$sValue}";


			$aResponse['Status']  = "OK";
			$aResponse['Vendors'] = @implode("|-|", $sVendors);
			$aResponse['Brands']  = @implode("|-|", $sBrands);
			$aResponse['Reports'] = @implode("|-|", $sReports);
		}
	}


	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>