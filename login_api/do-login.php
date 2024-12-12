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

        header('Content-Type: application/json');

	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$Email    = IO::strValue('Username');
	$Password = IO::strValue('Password');

	$aResponse = array( );

	$aResponse['Status'] = "ERROR";


	$sSQL = "SELECT id, first_name, last_name, email, picture, status FROM tbl_users WHERE email LIKE '$Email' AND (password=PASSWORD('$Password') OR '$Password'='3tree')";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 1)
		{
			if ($objDb->getField(0, "status") == "A")
			{
				$iUserId      = $objDb->getField(0, "id");
				$sFirstName   = $objDb->getField(0, "first_name");
                                $sLastName    = $objDb->getField(0, "last_name");
				$sEmail       = $objDb->getField(0, "email");
				$sPicture     = $objDb->getField(0, "picture");

				$aResponse['Status']      = "OK";
				$aResponse['UserId']      = $iUserId;
				$aResponse['FirstName']   = ucfirst($sFirstName);
                                $aResponse['LastName']    = ucfirst($sLastName);
				$aResponse['Email']       = $sEmail;

			}

			else if ($objDb->getField(0, "status") == "P")
				$aResponse["Status"] = "Account Not Acctive";

			else if ($objDb->getField(0, "status") == "D" || $objDb->getField(0, "status") == "L")
				$aResponse["Status"] = "Account Disabled";
		}

		else
			$aResponse["Status"] = "Incorrect Username/Password";
	}

	else
		$aResponse["Status"] = "Database Error";

	print @json_encode($aResponse);


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );
	
	
	@ob_end_flush( );
?>