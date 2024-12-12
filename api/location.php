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

	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$User        = IO::intValue("User");
	$Latitude    = IO::strValue("Latitude");
	$Longitude   = IO::strValue("Longitude");
	$sAddress    = "";
	$sAddressSQL = "";

	if ($User == 0 || $Latitude == "" || $Longitude == "")
		exit( );


	$sLocationTime = getDbValue("location_time", "tbl_users", "id='$User'");

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



	$sSQL = "UPDATE tbl_users SET latitude='$Latitude', longitude='$Longitude', location_time=NOW() $sAddressSQL WHERE id='$User'";
	$objDb->execute($sSQL, true, $User, getDbValue("name", "tbl_users", "id='$User'"));


/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Location Alert";
	$objEmail->Body    = $sSQL;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>