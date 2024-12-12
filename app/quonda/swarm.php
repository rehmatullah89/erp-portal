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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$User = IO::strValue("User");


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, status, report_types, audit_stages, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser        = $objDb->getField(0, "id");
			$sReportTypes = $objDb->getField(0, "report_types");
			$sAuditStages = $objDb->getField(0, "audit_stages");
			$sGuest       = $objDb->getField(0, "guest");


			$aResponse  = array( );
			$sLocations = array( );


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


				$sLocations[] = array("Name"      => $sName,
									  "Latitude"  => $sLatitude,
									  "Longitude" => $sLongitude,
									  "DateTime"  => $sDateTime,
									  "Address"   => $sAddress);
			}



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


				$sLocations[] = array("Name"      => $sName,
									  "Latitude"  => $sLatitude,
									  "Longitude" => $sLongitude,
									  "DateTime"  => $sDateTime,
									  "Address"   => $sAddress);
			}


			$sStats = array(
			                 "Total"      => getDbValue("COUNT(*)", "tbl_qa_reports", "audit_date=CURDATE( ) AND FIND_IN_SET(audit_stage, '$sAuditStages') AND FIND_IN_SET(report_id, '$sReportTypes')"),
			                 "Pakistan"   => getDbValue("COUNT(*)", "tbl_qa_reports", "audit_date=CURDATE( ) AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='162') AND FIND_IN_SET(audit_stage, '$sAuditStages') AND FIND_IN_SET(report_id, '$sReportTypes')"),
			                 "Bangladesh" => getDbValue("COUNT(*)", "tbl_qa_reports", "audit_date=CURDATE( ) AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='18') AND FIND_IN_SET(audit_stage, '$sAuditStages') AND FIND_IN_SET(report_id, '$sReportTypes')")
			               );


			$aResponse['Status']    = "OK";
			$aResponse['Locations'] = $sLocations;
			$aResponse['Stats']     = $sStats;
		}
	}


	print @json_encode($aResponse);



	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>