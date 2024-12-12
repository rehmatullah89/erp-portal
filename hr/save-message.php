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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$ParentId   = IO::intValue("Id");
	$Message    = IO::strValue("Message");
	$Recipients = IO::getArray('Recipients');


	$iHrId = HR_MANAGER;


	$sSQL = "SELECT name, email FROM tbl_users WHERE id='$iHrId'";
	$objDb->query($sSQL);

	$sHrName  = $objDb->getField(0, 0);
	$sHrEmail = $objDb->getField(0, 1);


	$sManager = getDbValue("grievances_manager", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sError   = "";

	if ($iHrId == $_SESSION['UserId'] || $sManager == "Y")
	{
		if (count($Recipients) == 0)
			$sError .= "- No Recipient Selected<br />";
	}

	if ($Message == "")
		$sError .= "- Message<br />";

	if ($sError != "")
		backToForm($sError);


	$sRecipients = "";
	$sEmployees  = array( );

	if ($iHrId == $_SESSION['UserId'] || $sManager == "Y")
	{
		$sSQL = ("SELECT id, name, email FROM tbl_users WHERE id IN (".@implode(",", $Recipients).")");
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sEmployees[$i]['Name']  = $objDb->getField($i, "name");
			$sEmployees[$i]['Email'] = $objDb->getField($i, "email");

			$sRecipients .= (",".str_pad($objDb->getField($i, "id"), 3, '0', STR_PAD_LEFT));
		}

		$sRecipients = substr($sRecipients, 1);
	}

	else
	{
		$sRecipients = str_pad($iHrId, 3, '0', STR_PAD_LEFT);

		$sEmployees[0]['Name']  = $sHrName;
		$sEmployees[0]['Email'] = $sHrEmail;
	}

	$iSenderId    = $_SESSION['UserId'];
	$sSenderName  = $_SESSION['Name'];
	$sSenderEmail = $_SESSION['Email'];


	$sSQL = "SELECT subject FROM tbl_hr_messages WHERE id='$ParentId'";
	$objDb->query($sSQL);

	$sSubject = $objDb->getField(0, 0);


	$iId = getNextId("tbl_hr_messages");

	$sSQL = "INSERT INTO tbl_hr_messages (id, parent_id, sender_id, recipients, subject, message, status, date_time)
	                              VALUES ('$iId', '$ParentId', '$iSenderId', '$sRecipients', 'Re: {$sSubject}', '$Message', '0', NOW( ))";

	if ($objDb->execute($sSQL) == true)
	{
		$sBody = @file_get_contents("../emails/hr-message-reply.txt");

		$sBody = @str_replace("[Name]", $sSenderName, $sBody);
		$sBody = @str_replace("[Email]", $sSenderEmail, $sBody);
		$sBody = @str_replace("[Subject]", $sSubject, $sBody);
		$sBody = @str_replace("[Message]", nl2br($Message), $sBody);
		$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
		$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


		$objEmail = new PHPMailer( );

//		$objEmail->From     = $sSenderEmail;
//		$objEmail->FromName = $sSenderName;
		$objEmail->Subject  = "Re: $sSubject";

		$objEmail->MsgHTML($sBody);

		for ($i = 0; $i < count($sEmployees); $i ++)
			$objEmail->AddAddress($sEmployees[$i]['Email'], $sEmployees[$i]['Name']);

		$objEmail->Send( );


		if ($iHrId == $_SESSION['UserId'] || $sManager == "Y")
		{
			$sSQL = "UPDATE tbl_hr_messages SET status='2' WHERE id='$Id'";
			$objDb->execute($sSQL);
		}

		else
		{
			$sSQL = "UPDATE tbl_hr_messages SET status='0' WHERE id='$Id'";
			$objDb->execute($sSQL);
		}

		redirect($_SERVER['HTTP_REFERER'], "HR_REPLY_SENT");
	}

	else
		$_SESSION['Flag'] = "DB_ERROR";

	backToForm( );

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>