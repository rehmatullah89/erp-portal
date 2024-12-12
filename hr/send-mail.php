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

     if (!strstr($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']))
         die("Hacking Attempt Blocked");


	$objDb = new Database( );

	$To      = IO::strValue("To");
	$Subject = IO::strValue("Subject");
	$Message = IO::strValue("Message");

	$sError = "";

	if ($To == "")
		$sError .= "- Recipient<br />";

	if ($Subject == "")
		$sError .= "- Subject<br />";

	if ($Message == "")
		$sError .= "- Message<br />";

	if ($sError != "")
		backToForm($sError);


	$sSenderName  = $_SESSION['Name'];
	$sSenderEmail = $_SESSION['Email'];

	if ($To == "HR Manager")
	{
		//$sHrId  = str_pad(HR_MANAGER, 3, '0', STR_PAD_LEFT);

		$iHrId = getDbValue("id", "tbl_users", "designation_id='126'");
		$sHrId  = str_pad($iHrId, 3, '0', STR_PAD_LEFT);


		$iId = getNextId("tbl_hr_messages");

		$sSQL = "INSERT INTO tbl_hr_messages (id, parent_id, sender_id, recipients, manager, subject, message, status, date_time)
									  VALUES ('$iId', '0', '{$_SESSION['UserId']}', '$sHrId', 'HR Manager', '$Subject', '$Message', '0', NOW( ))";

		if ($objDb->execute($sSQL) == true)
		{
			$sBody = @file_get_contents("../emails/message-to-hr.txt");

			$sBody = @str_replace("[Name]", $sSenderName, $sBody);
			$sBody = @str_replace("[Email]", $sSenderEmail, $sBody);
			$sBody = @str_replace("[Subject]", $Subject, $sBody);
			$sBody = @str_replace("[Message]", nl2br($Message), $sBody);
			$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
			$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

//			$objEmail->From     = $sSenderEmail;
//			$objEmail->FromName = $sSenderName;
			$objEmail->Subject  = $Subject;

			$objEmail->MsgHTML($sBody);
			$objEmail->AddAddress("hr@apparelco.com", "Matrix HR Group");


			$sSQL = ("SELECT name, email FROM tbl_users WHERE id='$iHrId' AND status='A'");
			$objDb->query($sSQL);

			$sName  = $objDb->getField(0, 'name');
			$sEmail = $objDb->getField(0, 'email');


			$objEmail->AddAddress($sEmail, $sName);
			$objEmail->Send( );

			redirect($_SERVER['HTTP_REFERER'], "HR_MESSAGE_SENT");
		}

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
	{
		$objEmail = new PHPMailer( );

//		$objEmail->From     = $sSenderEmail;
//		$objEmail->FromName = $sSenderName;
		$objEmail->Subject  = $Subject;

		$sSQL = "SELECT id, name, email FROM tbl_users WHERE grievances_manager='Y' AND status='A'";
		$objDb->query($sSQL);

		$iCount      = $objDb->getCount( );
		$sRecipients = "";

		if ($iCount == 0)
		{
			$sRecipients = str_pad(HR_MANAGER, 3, '0', STR_PAD_LEFT);

			$objEmail->AddAddress("hr@apparelco.com", "Matrix HR Group");
		}


		for ($i = 0; $i < $iCount; $i ++)
		{
			$iUser  = $objDb->getField($i, 'id');
			$sName  = $objDb->getField($i, 'name');
			$sEmail = $objDb->getField($i, 'email');

			$sRecipients .= (($sRecipients != "") ? "," : "");
			$sRecipients .= $iUser;

			$objEmail->AddAddress($sEmail, $sName);
		}


		$iId = getNextId("tbl_hr_messages");

		$sSQL = "INSERT INTO tbl_hr_messages (id, parent_id, sender_id, recipients, manager, subject, message, status, date_time)
									  VALUES ('$iId', '0', '{$_SESSION['UserId']}', '$sRecipients', 'Grievances Manager', '$Subject', '$Message', '0', NOW( ))";

		if ($objDb->execute($sSQL) == true)
		{
			$sBody = @file_get_contents("../emails/message-to-grievance.txt");

			$sBody = @str_replace("[Name]", $sSenderName, $sBody);
			$sBody = @str_replace("[Email]", $sSenderEmail, $sBody);
			$sBody = @str_replace("[Subject]", $Subject, $sBody);
			$sBody = @str_replace("[Message]", nl2br($Message), $sBody);
			$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
			$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);

			$objEmail->MsgHTML($sBody);
			$objEmail->Send( );

			redirect($_SERVER['HTTP_REFERER'], "GRIEVENCE_MESSAGE_SENT");
		}

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}


	backToForm( );

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>