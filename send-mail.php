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

     if (!strstr($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']))
         die("Hacking Attempt Blocked");

	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Name    = IO::strValue("Name");
	$Email   = IO::strValue("Email");
	$Subject = IO::strValue("Subject");
	$Message = IO::strValue("Message");

	$sError = "";

	if ($Name == "")
		$sError .= "- Name<br />";

	if ($Email == "")
		$sError .= "- Email Address<br />";

	if ($Subject == "")
		$sError .= "- Subject<br />";

	if ($Message == "")
		$sError .= "- Message<br />";

	if ($sError != "")
		backToForm($sError);

	if (md5(IO::strValue('SpamCode')) != $_SESSION['SpamCode'])
	{
		$_SESSION['Flag'] = "INVALID_SPAM_CODE";

		backToForm( );
	}


	$iId = getNextId("tbl_web_messages");

	$sSQL = "INSERT INTO tbl_web_messages (id, name, email, subject, message, date_time) VALUES ('$iId', '$Name', '$Email', '$Subject', '$Message', NOW( ))";

	if ($objDb->execute($sSQL) == true)
	{
		$sBody = @file_get_contents("emails/contact-us.txt");

		$sBody = @str_replace("[Name]", $Name, $sBody);
		$sBody = @str_replace("[Email]", $Email, $sBody);
		$sBody = @str_replace("[Subject]", $Subject, $sBody);
		$sBody = @str_replace("[Message]", nl2br($Message), $sBody);
		$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
		$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


		$objEmail = new PHPMailer( );

//		$objEmail->From     = $Email;
//		$objEmail->FromName = $Name;
		$objEmail->Subject  = $Subject;

		$objEmail->MsgHTML($sBody);
		$objEmail->AddAddress(CONTACT_RECIPIENT_EMAIL, CONTACT_RECIPIENT_NAME);
		$objEmail->Send( );

		redirect(SITE_URL, "MESSAGE_SENT");
	}

	else
		$_SESSION['Flag'] = "DB_ERROR";

	backToForm( );

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>