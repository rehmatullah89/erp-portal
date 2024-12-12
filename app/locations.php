<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree QUONDA App                                                                   **
	**  Version 3.0                                                                              **
	**                                                                                           **
	**  http://app.3-tree.com                                                                    **
	**                                                                                           **
	**  Copyright 2008-17 (C) Triple Tree                                                        **
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
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$User = IO::strValue("User");


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, status, vendors, brands, style_categories, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser            = $objDb->getField(0, "id");
			$sBrands          = $objDb->getField(0, "brands");
			$sVendors         = $objDb->getField(0, "vendors");
			$sStyleCategories = $objDb->getField(0, "style_categories");
			$sGuest           = $objDb->getField(0, "guest");


			$aResponse = array( );
			$sAuditors = array( );


			$sSQL = "SELECT name, latitude, longitude, location_time, location_address FROM tbl_users WHERE TIME_TO_SEC(TIMEDIFF(NOW( ), location_time)) <= '43200' AND status='A' ORDER BY name";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$sName      = $objDb->getField($i, "name");
				$sLatitude  = $objDb->getField($i, "latitude");
				$sLongitude = $objDb->getField($i, "longitude");
				$sDateTime  = $objDb->getField($i, "location_time");
				$sAddress   = $objDb->getField($i, "location_address");

				$sDateTime = formatDate($sDateTime, "h:i A");


				$sAuditors[] = array("Name"      => $sName,
									 "Latitude"  => $sLatitude,
									 "Longitude" => $sLongitude,
									 "DateTime"  => $sDateTime,
									 "Address"   => $sAddress);
			}



			$sSQL = "SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '$sBrands') AND FIND_IN_SET(category_id, '$sStyleCategories') ";
			$objDb->query($sSQL);

			$iCount  = $objDb->getCount( );
			$sStyles = "";

			for ($i = 0; $i < $iCount; $i ++)
				$sStyles .= (",".$objDb->getField($i, 0));

			if ($sStyles != "")
				$sStyles = substr($sStyles, 1);


			$sConditions = " audit_date=CURDATE( ) AND FIND_IN_SET(vendor_id, '$sVendors') AND approved='Y'
			                 AND (po_id='0' OR po_id IN (SELECT id FROM tbl_po WHERE FIND_IN_SET(brand_id, '$sBrands') AND FIND_IN_SET(vendor_id, '$sVendors')))";

			if (@strpos($_SESSION["Email"], "marksnspencer.com") === FALSE)
				$sConditions .= " AND (style_id='0' OR FIND_IN_SET(style_id, '$sStyles')) ";

			else
				$sConditions .= " AND FIND_IN_SET(style_id, '$sStyles') ";


			$sStats = array(
			                 "Total"      => getDbValue("COUNT(*)", "tbl_qa_reports", $sConditions),
			                 "Pakistan"   => getDbValue("COUNT(*)", "tbl_qa_reports", "{$sConditions} AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='162')"),
			                 "Bangladesh" => getDbValue("COUNT(*)", "tbl_qa_reports", "{$sConditions} AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='18')")
			               );


			$sVendors  = array( );

			$sSQL = "SELECT vendor, address, latitude, longitude FROM tbl_vendors WHERE latitude!='' AND longitude!='' AND sourcing='Y' ORDER BY id";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$sVendor    = $objDb->getField($i, "vendor");
				$sLatitude  = $objDb->getField($i, "latitude");
				$sLongitude = $objDb->getField($i, "longitude");
				$sAddress   = $objDb->getField($i, "address");

				$sVendors[$i] = array("Name"      => $sVendor,
									  "Latitude"  => $sLatitude,
									  "Longitude" => $sLongitude,
									  "Address"   => $sAddress);
			}


			$aResponse['Status']   = "OK";
			$aResponse['Vendors']  = $sVendors;
			$aResponse['Auditors'] = $sAuditors;
			$aResponse['Stats']    = $sStats;
		}
	}


	print @json_encode($aResponse);



	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>