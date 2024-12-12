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
			$sUserVendors = getDbValue("vendors", "tbl_users", "id='$User'"); //smart search
			$sUserBrands  = getDbValue("brands", "tbl_users", "id='$User'");
			$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ($sUserVendors) AND parent_id='0'");
			$sBrandsList  = getList("tbl_brands", "id", "brand", "id IN ($sUserBrands)");
			
//			$yesterday = date("Y-m-d");
			$yesterday = "2013-06-10";
			
			$sSQL = ("SELECT order_no
			          FROM tbl_po
			          WHERE vendor_id IN ($sUserVendors) AND created > '$yesterday'
			          ORDER BY created DESC");
			          
//remove userid condition			          
				          //echo $sSQL; 	//exit(0);
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
//echo $iCount; exit(0);

			for ($i = 0; $i < $iCount; $i ++)
			{
				$aPoList[] = $objDb->getField($i, 'order_no');
				$aStyleList[] = $objDb->getField($i, 'styles');
			}
			
			$sPoList  = implode(",",$aPoList);
			$sUserStyleList  = implode(",",$aStyleList);
			
			//print_r($sPoList);exit(0);
			
//			$sUserStyleList  = getList("tbl_po", "styles", "vendor_id IN ($sUserVendors)");
			
			$sStyleList  = getList("tbl_styles", "style", "id IN ($sUserStyleList)"); //return valued
			
			$sReportsList = getList("tbl_reports", "id", "report");
			
			$sDRList[0]["id"] = 0; 
			$sDRList[0]["name"] = "DR Rate 3.0 +"; 
			$sDRList[0]["value"] = "3.0"; 

			$sDRList[1]["id"] = 0; 
			$sDRList[1]["name"] = "DR Rate 2.0 +"; 
			$sDRList[1]["value"] = "2.0"; 


			$sVendors = array( );
			$sBrands  = array( );
			$sReports = array( );

			$sVendors[]["id"] = "0";
			$sVendors[]["name"] = "All Vendors";
			
			$sBrands[]["id"]  = "0";
			$sBrands[]["name"]  = "All Brands";
			
			$sReports[]["id"] = "0";
			$sReports[]["name"] = "All Types";

			foreach ($sVendorsList as $sKey => $sValue){
				$sVendors[]["id"] = $sKey;
				$sVendors[]["name"] = $sValue;
				}

			foreach ($sBrandsList as $sKey => $sValue)
				$sBrands[] = "{$sKey}||{$sValue}";

			foreach ($sReportsList as $sKey => $sValue){
				$sReports[]["id"] = $sKey;
				$sReports[]["name"] = $sValue;
				}


			$aResponse['Status']  = "OK";
			$aResponse['Vendors'] = $sVendors;
			$aResponse['po'] = $aPoList;
			
			$aResponse['styles'] = $sStyleList;//sDRList
			$aResponse['DRList']  = $sDRList;
//			$aResponse['Reports'] = @implode("|-|", $sReports);
		}
	}


	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>