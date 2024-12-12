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

	@session_start( );

	@ini_set('display_errors', 0);
	@ini_set('log_errors', 0);
	@error_reporting(0);

	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);



	$sBaseDir = "C:/wamp/www/portal/";

	@require_once($sBaseDir."requires/configs.php");
	@require_once($sBaseDir."requires/db.class.php");
	@require_once($sBaseDir."requires/common-functions.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$sDate = date("Ymd", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y")));
	$sFile = (EMAIL_LOGS_DIR.$sDate.".log");

	$hFile = @fopen($sFile, "r");

	if ($hFile)
	{
		while (($sRecord = @fgetcsv($hFile, 10000, "\t")) !== FALSE)
		{
			$sDateTime  = $sRecord[14];
			$sRecipient = $sRecord[7];
			$sSender    = $sRecord[19];
			$sSubject   = $sRecord[18];

			if (strpos($sSender, "@") !== FALSE &&
				strpos($sRecipient, "@") !== FALSE &&
				$sRecipient != "ccmails@apparelco.com" &&
				$sRecipient != "postmaster@apparelco.com" &&
				$sSender != "postmaster@apparelco.com" &&
				$sRecipient != $sSender &&
				$sSender{0} != "-" &&
				$sSender{0} != "." &&
				$sSender{0} != "0" &&
				$sSender{0} != "1" &&
				$sSender{0} != "2" &&
				$sSender{0} != "3" &&
				$sSender{0} != "4" &&
				$sSender{0} != "5" &&
				$sSender{0} != "6" &&
				$sSender{0} != "7" &&
				$sSender{0} != "8" &&
				$sSender{0} != "9" &&
				$sSender{0} != "@" &&
				strlen($sSender) >= 5 &&
				stristr($sSender, "FROM_EMAIL") == FALSE &&
				stristr($sSender, "BOUNCE_FROMS") == FALSE &&
				stristr($sSender, "%") == FALSE &&
				stristr($sSender, "!") == FALSE &&
				stristr($sSender, "#") == FALSE &&
				stristr($sSender, "$") == FALSE &&
				stristr($sSender, "^") == FALSE &&
				stristr($sSender, "&") == FALSE &&
				stristr($sSender, "*") == FALSE &&
				stristr($sSender, "(") == FALSE &&
				stristr($sSender, ")") == FALSE &&
				stristr($sSender, "=") == FALSE &&
				stristr($sSender, "+") == FALSE &&
				stristr($sSender, "|") == FALSE &&
				stristr($sSender, "}") == FALSE &&
				stristr($sSender, "{") == FALSE &&
				stristr($sSender, "]") == FALSE &&
				stristr($sSender, "[") == FALSE &&
				stristr($sSender, "'") == FALSE &&
				stristr($sSender, '"') == FALSE &&
				stristr($sSender, ";") == FALSE &&
				stristr($sSender, ":") == FALSE &&
				stristr($sSender, "/") == FALSE &&
				stristr($sSender, "?") == FALSE &&
				stristr($sSender, ">") == FALSE &&
				stristr($sSender, "<") == FALSE &&
				stristr($sSender, ",") == FALSE &&
				stristr($sSender, " ") == FALSE &&
				stristr($sSender, "@sms.apparelco.com") == FALSE &&
				strlen($sDateTime) >= 18 &&
				(substr($sSubject, 0, 2) != "=?" && substr($sSubject, -2) != "=?") &&
				stristr($sSubject, "[PHISHING]") == FALSE &&
				stristr($sSubject, "MSG#") == FALSE &&
				stristr($sSubject, "MSG:") == FALSE &&
				stristr($sSubject, "MSG ID:") == FALSE &&
				stristr($sSubject, "xxx") == FALSE &&
				stristr($sSubject, "drug") == FALSE &&
				stristr($sSubject, "porn") == FALSE &&
				stristr($sSubject, "penis") == FALSE &&
				stristr($sSubject, "pill") == FALSE &&
				stristr($sSubject, "Cializ") == FALSE &&
				stristr($sSubject, "sexual") == FALSE &&
				stristr($sSubject, "[Spam]") == FALSE &&
				stristr($sSubject, "Rolex") == FALSE &&
				stristr($sSubject, "Watches") == FALSE &&
				stristr($sSubject, "Pharmacy") == FALSE &&
				stristr($sSubject, "Replica") == FALSE &&
				stristr($sSubject, "Rep1ica") == FALSE &&
				stristr($sSubject, "Viagra") == FALSE &&
				stristr($sSubject, "Medications") == FALSE &&
				stristr($sSubject, "Hydrocodone") == FALSE &&
				stristr($sSubject, "PRESCRIPTION") == FALSE &&
				stristr($sSubject, "Weight") == FALSE &&
				stristr($sSubject, "Dating ") == FALSE &&
				stristr($sSubject, "Message could not be delivered") == FALSE &&
				stristr($sSubject, "[Spam in Header]") == FALSE &&
				stristr($sSubject, "Delivery Status Notification (Failure)") == FALSE &&
				stristr($sSubject, "Mail System Error - Returned Mail") == FALSE)
			{
				$sDateTime = date("Y-m-d H:i:s", strtotime($sDateTime));
				$sSubject  = addslashes($sSubject);

				$sSQL = "SELECT recipients FROM tbl_email_stats WHERE sender='$sSender' AND subject='$sSubject' AND date_time='$sDateTime'";
				$objDb->query($sSQL);

				if ($objDb->getCount( ) == 0)
				{
					$sSQL = "INSERT INTO tbl_email_stats (sender, recipients, subject, date_time) VALUES ('$sSender', '$sRecipient', '$sSubject', '$sDateTime')";
					$objDb->execute($sSQL, false);
				}

				else
				{
					$sRecipients = $objDb->getField(0, 0);

					if (@stristr($sRecipients, $sRecipient) == FALSE)
					{
						$sSQL = "UPDATE tbl_email_stats SET recipients=CONCAT(recipients, ',', '$sRecipient') WHERE sender='$sSender' AND subject='$sSubject' AND date_time='$sDateTime'";
						$objDb->execute($sSQL, false);
					}
				}
			}
		}

		@fclose($hFile);



		$sSQL = array( );

		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%@%@%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%<%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%>%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '% %'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%=%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%|%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%[%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%]%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%,%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%/%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%?%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%\"%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%:%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%{%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%}%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%!%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%#%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%$%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%\%%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%^%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%&%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%*%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%(%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%)%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%\'%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE sender LIKE '%+%'";
		$sSQL[] = "DELETE FROM tbl_email_stats WHERE LENGTH(sender) < 5";

		for ($i = 0; $i < count($sSQL); $i ++)
			$objDb->execute($sSQL[$i], false);
	}



	$sDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 31), date("Y")));

	$sSQL = "DELETE FROM tbl_email_stats WHERE DATE_FORMAT(date_time, '%Y-%m-%d') <= '$sDate'";
	$objDb->execute($sSQL, false);


	$sDate = date("Ymd", mktime(0, 0, 0, date("m"), (date("d") - 31), date("Y")));

	@unlink((EMAIL_LOGS_DIR.$sDate.".log"));


	$objDb->close( );
	$objDbGlobal->close( );
?>