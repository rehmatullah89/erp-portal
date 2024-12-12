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


	$User        = IO::strValue("User");
	$Latitude    = IO::strValue("Latitude");
	$Longitude   = IO::strValue("Longitude");
	$sAddress    = "";
	$sAddressSQL = "";


	$aResponse            = array( );
	$aResponse["Status"]  = "ERROR";

	if ($User == "" || $Latitude == "" || $Longitude == "")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, location_time, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else
		{
			$iUser         = $objDb->getField(0, "id");
			$sName         = $objDb->getField(0, "name");
			$sLocationTime = $objDb->getField(0, "location_time");
			$sGuest        = $objDb->getField(0, "guest");


			if (strtotime($sLocationTime) < (strtotime(date("Y-m-d H:i:s")) - 3600))
			{
				$sLocation = @json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng={$Latitude},{$Longitude}&sensor=false"), true);

				if ($sLocation["results"][0]["address_components"][0]["long_name"] != "")
					$sAddress .= ($sLocation["results"][0]["address_components"][0]["long_name"]."\n");

				if ($sLocation["results"][0]["address_components"][1]["long_name"] != "")
					$sAddress .= ($sLocation["results"][0]["address_components"][1]["long_name"]."\n");

				if ($sLocation["results"][0]["address_components"][2]["long_name"] != "")
					$sAddress .= ($sLocation["results"][0]["address_components"][2]["long_name"]."\n");

				if ($sLocation["results"][0]["address_components"][3]["long_name"] != "")
					$sAddress .= ($sLocation["results"][0]["address_components"][3]["long_name"]."\n");

				$sAddress .= "\n";


				$sAddressSQL = " , location_address='$sAddress' ";
			}


			$sSQL = "UPDATE tbl_users SET latitude='$Latitude', longitude='$Longitude', location_time=NOW() $sAddressSQL WHERE id='$iUser'";
			$objDb->execute($sSQL, true, $iUser, $sName);


			$aResponse["Status"]  = "OK";
			$aResponse["Address"] = $sAddress;
		}
	}


	print @json_encode($aResponse);



	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>