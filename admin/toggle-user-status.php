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

	$Id     = IO::intValue("Id");
	$Status = IO::strValue("Status");

	$objDb = new Database( );

	$sSQL = "UPDATE tbl_users SET status='$Status' WHERE id='$Id'";

	if ($objDb->execute($sSQL) == true)
	{
		if ($Status == "A")
		{
			$sSQL = "SELECT * FROM tbl_users WHERE id='$Id'";
			$objDb->query($sSQL);

			$sName     = $objDb->getField(0, "name");
			$sEmail    = $objDb->getField(0, "email");
			$sUsername = $objDb->getField(0, "username");
			$iCountry  = $objDb->getField(0, "country_id");
			
			
			@require_once("../add-support-user.php");
			
			
			$sBody = @file_get_contents("../emails/account-activation.txt");

			$sBody = @str_replace("[Name]", $sName, $sBody);
			$sBody = @str_replace("[Email]", $sEmail, $sBody);
			$sBody = @str_replace("[Country]", getDbValue("name", "tbl_countries", "id='$iCountry'"), $sBody);
			$sBody = @str_replace("[Username]", $sUsername, $sBody);

			$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
			$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

//			$objEmail->From     = WELCOME_SENDER_EMAIL;
//			$objEmail->FromName = WELCOME_SENDER_NAME;
			$objEmail->Subject  = WELCOME_EMAIL_SUBJECT;

			$objEmail->MsgHTML($sBody);
			$objEmail->AddAddress($sEmail, $sName);
			$objEmail->addBCC("isaeed@apparelco.com", "Imran Saeed");
			$objEmail->addBCC("omer@apparelco.com", "Omer Rauf");
			$objEmail->addBCC(WELCOME_SENDER_EMAIL, WELCOME_SENDER_NAME);
			$objEmail->Send( );
		}
		
		else
        {
            $sSQL = "SELECT * FROM tbl_users WHERE id='$Id'";
			$objDb->query($sSQL);
			
			@require_once("../add-support-user.php");
        }

		
		$_SESSION['Flag'] = "USER_STATUS_UPDATED";
	}

	else
		$_SESSION['Flag'] = "DB_ERROR";

	
	header("Location: {$_SERVER['HTTP_REFERER']}");

	
	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );
?>