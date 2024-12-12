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
	$Activity  = IO::intValue("Activity");
	$Details   = IO::strValue("Details");
	

	$aResponse            = array( );
	$aResponse["Status"]  = "ERROR";

	if ($User == "" || $Date == "" || $Time == "" || $Activity == 0)
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else
		{
			$iUser = $objDb->getField(0, "id");
			$sName = $objDb->getField(0, "name");
			
			
			$iAttendance = getDbValue("COUNT(1)", "tbl_attendance", "`date`=CURDATE( ) AND user_id='$iUser'");

			if ($iAttendance == 0)
				$aResponse["Message"] = "Please first Mark your Attendance.";

			else
			{
				if (getDbValue("COUNT(1)", "tbl_user_activities", "`date`='$Date' AND `time`='$Time' AND activity_id='$Activity' AND user_id='$iUser'") == 0)
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

					
					$iId = getNextId("tbl_user_activities");

					$sSQL = "INSERT INTO tbl_user_activities SET id          = '$iId',
																 activity_id = '$Activity',
																 user_id     = '$iUser',
					                                             `date`      = '$Date',																 
																 `time`      = '$Time',																 
																 details     = '$Details',
																 latitude    = '$Latitude',
																 longitude   = '$Longitude',
																 address     = '$Address',
																 date_time   = NOW( )";

					if ($objDb->execute($sSQL, true, $iUser, $sName) == true)
					{
						$aResponse["Status"]  = "OK";
						$aResponse["Message"] = "Your Activity has been Saved successfully.";
					}

					else
						$aResponse["Message"] = "An ERROR occured while processing your request. Please re-try.";
				}
				
				else
				{
					$aResponse["Status"]  = "OK";
					$aResponse["Message"] = "Activity already Saved.";
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