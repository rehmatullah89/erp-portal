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


	$User      = IO::strValue("User");
	$Latitude  = IO::strValue("Latitude");
	$Longitude = IO::strValue("Longitude");
	$Address   = IO::strValue("Address");
	$Time      = IO::strValue("Time");
	$Date      = IO::strValue("Date");
	
	$Date = (($Date == "") ? date("Y-m-d") : $Date);


	$aResponse            = array( );
	$aResponse["Status"]  = "ERROR";

	if ($User == "" || $Latitude == "" || $Longitude == "" || $Time == "")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, designation_id FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else
		{
			$iUser        = $objDb->getField(0, "id");
			$sName        = $objDb->getField(0, "name");
			$iDesignation = $objDb->getField(0, "designation_id");


			$iDepartment = getDbValue("department_id", "tbl_designations", "id='$iDesignation'");

			if (!@in_array($iDepartment, array(8, 15, 41, 31)))
				$aResponse["Message"] = "Only Quality Department Staff can use this Application.";

			else
			{
				if (getDbValue("COUNT(1)", "tbl_attendance", "`date`='$Date' AND user_id='$iUser' AND time_in='$Time'") == 0)
				{
					if ($Address == "" || $Address == "Address not found")
					{
						$sLocation = @json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng={$Latitude},{$Longitude}&sensor=false"), true);
						$Address   = "";

						if ($sLocation["results"][0]["address_components"][0]["long_name"] != "")
							$Address .= ($sLocation["results"][0]["address_components"][0]["long_name"]."\n");

						if ($sLocation["results"][0]["address_components"][1]["long_name"] != "")
							$Address .= ($sLocation["results"][0]["address_components"][1]["long_name"]."\n");

						if ($sLocation["results"][0]["address_components"][2]["long_name"] != "")
							$Address .= ($sLocation["results"][0]["address_components"][2]["long_name"]."\n");

						if ($sLocation["results"][0]["address_components"][3]["long_name"] != "")
							$Address .= ($sLocation["results"][0]["address_components"][3]["long_name"]."\n");
					}


					$iEntry = (int)getDbValue("COUNT(1)", "tbl_attendance", "`date`='$Date' AND user_id='$iUser'");

					$sSQL = "INSERT INTO tbl_attendance SET `date`      = '$Date',
															user_id     = '$iUser',
															`entry`     = '$iEntry',
															time_in     = '$Time',
															time_out    = '00:00:00',
															location_in = '{$Address}\n(Lat: {$Latitude}, Lng: {$Longitude})',
															date_time   = NOW( )";

					if ($objDb->execute($sSQL, true, $iUser, $sName) == true)
					{
						$aResponse["Status"]  = "OK";
						$aResponse["Message"] = "Your Attendance has been Marked successfully.";
						$aResponse["Time"]    = formatTime($Time);
					}

					else
						$aResponse["Message"] = "An ERROR occured while processing your request. Please re-try.";
				}
				
				else
				{
					$aResponse["Status"]  = "OK";
					$aResponse["Message"] = "Attendance already Marked.";
					$aResponse["Time"]    = formatTime($Time);
				}
			}
		}
	}


	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Attendance";
	$objEmail->Body    = @json_encode($aResponse);

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>