<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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
	@require_once("../requires/image-functions.php");

    checkLogin( );

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id      = IO::intValue("Id");
	$Referer = IO::strValue("Referer");

	$iId = getNextId("tbl_web_message_replies");

	$sSQL = ("INSERT INTO tbl_web_message_replies (id, message_id, message, user_id, date_time) VALUES ('$iId', '$Id', '".IO::strValue("Message")."', '{$_SESSION['UserId']}', NOW( ))");

	if ($objDb->execute($sSQL) == true)
	{
		$sSQL = "SELECT name, email, subject FROM tbl_web_messages WHERE id='$Id'";
		$objDb->query($sSQL);

		$sName    = $objDb->getField(0, "name");
		$sEmail   = $objDb->getField(0, "email");
		$sSubject = $objDb->getField(0, "subject");


		$objEmail = new PHPMailer( );

//		$objEmail->From     = CONTACT_SENDER_NAME;
//		$objEmail->FromName = CONTACT_SENDER_EMAIL;
		$objEmail->Subject  = ("Re: ".$sSubject);

		$objEmail->MsgHTML(nl2br(IO::strValue("Message")));
		$objEmail->AddAddress($sEmail, $sName);
		$objEmail->Send( );

		redirect($Referer, "MESSAGE_REPLY_POSTED");
	}

	else
		$_SESSION['Flag'] = "DB_ERROR";

	backToForm( );

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>