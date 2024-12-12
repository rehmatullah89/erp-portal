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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Email = IO::strValue("Email");

	if ($Email == "")
	{
		print "ERROR|-|Please provide the Login Email Address in order to change your password.";
		exit;
	}

	$sSQL = "SELECT id, name, password FROM tbl_users WHERE email='$Email'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 1)
		{
			$iUserId   = $objDb->getField(0, "id");
			$sName     = $objDb->getField(0, "name");
			$sPassword = $objDb->getField(0, "password");

			$sCode = substr($sPassword, -16);
			$sUrl  = (SITE_URL."change-password.php?uid={$iUserId}&email={$Email}&code={$sCode}");

			$sBody = @file_get_contents("../emails/get-password.txt");

			$sBody = @str_replace("[Name]", $sName, $sBody);
			$sBody = @str_replace("[URL]", $sUrl, $sBody);
			$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
			$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);

			$objEmail = new PHPMailer( );

//			$objEmail->From     = FP_SENDER_EMAIL;
//			$objEmail->FromName = FP_SENDER_NAME;
			$objEmail->Subject  = FP_SUBJECT;

			$objEmail->MsgHTML($sBody);
			$objEmail->AddAddress($Email, $sName);

			print "OK|-|";

			if ($objEmail->Send( ) == true)
			{
?>
	<b>An email has been sent at your Email Address.</b><br /><br />
	Please check the email and follow the instructions to change your Account Password.<br /><br />
<?
			}

			else
			{
?>
	<b class="error">Unable to send email</b><br /><br />
	<b>ERROR:</b> <?= $objEmail->ErrorInfo ?><br /><br />
<?
			}
		}

		else
		{
				print "OK|-|";
?>
	<b class="error">Invalid Email Address.</b><br /><br />
	Please provide correct Account Email Address in order to change your password.<br /><br />
<?
		}
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>