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

	checkLogin(false);

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Name     = IO::strValue("Name");
	$Email    = IO::strValue("Email");
	$Address  = IO::strValue("Address");
	$City     = IO::strValue("City");
	$State    = IO::strValue("State");
	$ZipCode  = IO::strValue("ZipCode");
	$Country  = IO::intValue("Country");
	$Phone    = IO::strValue("Phone");
	$Mobile   = IO::strValue("Mobile");
	$Username = IO::strValue("Username");
	$Password = IO::strValue("Password");

	$sError = "";

	if ($Name == "")
		$sError .= "- Name<br />";

	if ($Email == "")
		$sError .= "- Email Address<br />";
/*
	if ($City == "")
		$sError .= "- City<br />";

	if ($Country == 0)
		$sError .= "- Country<br />";

	else
	{
		$sSQL = "SELECT country FROM tbl_countries WHERE id='$Country'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
				$sError .= "- Invalid Country<br />";

		else
			$sCountry = $objDb->getField(0, 0);
	}
*/
	if ($Mobile == "")
		$sError .= "- Mobile<br />";

	if ($Username == "")
		$sError .= "- Username<br />";

	if ($Password == "")
		$sError .= "- Password<br />";

	if ($sError != "")
		backToForm($sError);

	if (md5(IO::strValue('SpamCode')) != $_SESSION['SpamCode'])
	{
		$_SESSION['Flag'] = "INVALID_SPAM_CODE";

		backToForm( );
	}


	$Username = str_replace(" ", ".", $Username);

	$sSQL = "SELECT username, email FROM tbl_users WHERE username='$Username' OR email='$Email'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 1)
		{
			if ($objDb->getField(0, 'username') == $Username)
				$_SESSION['Flag'] = "USERNAME_EXISTS";

			if ($objDb->getField(0, 'email') == $Email)
				$_SESSION['Flag'] = "EMAIL_EXISTS";
		}

		else
		{
			$sUserType = "MATRIX";
                        $sAuditorType = 0;
                        
			if (@strpos(@strtolower($Email), "@mgfsourcing.com") !== FALSE)
			{
				$sUserType      = "MGF";
				$Mobile         = "";
                                $sAuditorType   = 4;
			}
			
			else if (@strpos(@strtolower($Email), "@global-exports.com") !== FALSE)
			{
				$sUserType = "GLOBALEXPORTS";
				$Mobile    = "";
			}
			
			
			$iId = getNextId("tbl_users");

			$sSQL = "INSERT INTO tbl_users (id, name, email, address, city, state, zip_code, country_id, phone, mobile, user_type, auditor_type, username, password, date_time)
 			                        VALUES ('$iId', '$Name', '$Email', '$Address', '$City', '$State', '$ZipCode', '$Country', '$Phone', '$Mobile', '$sUserType', '$sAuditorType', '$Username', PASSWORD('$Password'), NOW( ))";

			if ($objDb->execute($sSQL) == true)
			{
				$sBody = @file_get_contents("emails/welcome.txt");

				$sBody = @str_replace("[Name]", $Name, $sBody);
				$sBody = @str_replace("[Email]", $Email, $sBody);

				$sBody = @str_replace("[Address]", $Address, $sBody);
				$sBody = @str_replace("[City]", $City, $sBody);
				$sBody = @str_replace("[State]", $State, $sBody);
				$sBody = @str_replace("[ZipCode]", $ZipCode, $sBody);
				$sBody = @str_replace("[Country]", (($Country > 0) ? getDbValue("country", "tbl_countries", "id='$Country'") : "N/A"), $sBody);

				$sBody = @str_replace("[Phone]", $Phone, $sBody);
				$sBody = @str_replace("[Mobile]", $Mobile, $sBody);

				$sBody = @str_replace("[Username]", $Username, $sBody);
				$sBody = @str_replace("[Password]", $Password, $sBody);

				$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
				$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


				$objEmail = new PHPMailer( );

//				$objEmail->From     = WELCOME_SENDER_EMAIL;
//				$objEmail->FromName = WELCOME_SENDER_NAME;
				$objEmail->Subject  = WELCOME_EMAIL_SUBJECT;

				$objEmail->MsgHTML($sBody);
				$objEmail->AddAddress($Email, $Name);
				$objEmail->addBCC("isaeed@apparelco.com", "Imran Saeed");
				$objEmail->addBCC("omer@3-tree.com", "Omer Rauf");
				$objEmail->addBCC("fahad@3-tree.com", "Fahad Bashir");
				$objEmail->addBCC(WELCOME_SENDER_EMAIL, WELCOME_SENDER_NAME);
				$objEmail->Send( );

				redirect(SITE_URL, "ACCOUNT_CREATED");
			}

			else
				$_SESSION['Flag'] = "DB_ERROR";
		}
	}

	else
		$_SESSION['Flag'] = "DB_ERROR";

	backToForm( );

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>