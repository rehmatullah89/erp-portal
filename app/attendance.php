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


	$aResponse            = array( );
	$aResponse["Status"]  = "ERROR";
	$aResponse["Message"] = "";

	if ($User == "")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else
		{
			$iUser  = $objDb->getField(0, "id");
			$sName  = $objDb->getField(0, "name");
			$sGuest = $objDb->getField(0, "guest");


			$aResponse["Status"]  = "OK";
			$aResponse["TimeIn"]  = "00:00:00";
			$aResponse["TimeOut"] = "00:00:00";


			$sSQL = "SELECT time_in, time_out FROM tbl_attendance WHERE `date`=CURDATE( ) AND user_id='$iUser' ORDER BY `entry` DESC LIMIT 1";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1 && $objDb->getField(0, "time_out") == "00:00:00")
				$aResponse["TimeIn"] = formatTime($objDb->getField(0, "time_in"));
		}
	}


	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Attendance Check";
	$objEmail->Body    = @json_encode($aResponse);

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>