<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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

	$FirstName  = IO::strValue("FirstName");
	$LastName   = IO::strValue("LastName");
	$Email      = IO::strValue("Email");
	$Password   = IO::strValue("Password");

        $aResponse            = array( );
	$aResponse["Status"]  = "ERROR";

	if ($FirstName == "" || $LastName == "" || $Email == "" || $Password == "")
		$aResponse["Status"] = "Incomplete Registration Form Request";

	else
	{
		$sSQL = "SELECT email FROM tbl_users WHERE email LIKE '$Email'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
			$aResponse["Status"] = "User already exists!";

		else
		{
			$iUser = getNextId("tbl_users");

			$sSQL = "INSERT INTO tbl_users (id, first_name, last_name, email, password, picture, status, phone, date_time) VALUES ('$iUser', '$FirstName', '$LastName', '$Email', PASSWORD('$Password'), '', 'A', '', NOW( ))";

			if ($objDb->execute($sSQL) == true)
			{
                            $aResponse["Status"]  = "OK";
                            $aResponse['FirstName']   = ucfirst($FirstName);
                            $aResponse['LastName']    = ucfirst($LastName);
                            $aResponse['Email']       = $Email;
                            
                        }else
                            $aResponse["Status"]  = "Database Error";
		}
	}


	print @json_encode($aResponse);

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