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


	$User           = IO::strValue("User");
	$RegistrationId = IO::strValue("RegistrationId");


	$aResponse            = array( );
	$aResponse["Status"]  = "ERROR";

	if ($User == "" || $RegistrationId == "")
		$aResponse["Message"] = "Invalid Device Registration Request";

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


			$sSQL = "UPDATE tbl_users SET device_id='$RegistrationId' WHERE id='$iUser'";
			$objDb->execute($sSQL, true, $iUser, $sName);


			$aResponse["Status"]  = "OK";
			$aResponse["Message"] = "Device Registered successfully for Push Notifications.";
		}
	}


	print @json_encode($aResponse);



	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>