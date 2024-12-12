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
	@require_once($sBaseDir."requires/sms.class.php");
	@require_once($sBaseDir."requires/PHPMailer/class.phpmailer.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$objSms = new Sms( );

	$bDebug          = false;
	$sAlertTypesList = getList("tbl_notification_types", "id", "`type`");


    $sList = glob(SMS_NOW_IN_DIR."*.*");

	if (count($sList) == 0)
	{
		if ($bDebug == true)
			print "No New SMS found!";
	}

	else
	{
		foreach ($sList as $sFile)
		{
			$fFileSize = @filesize($sFile);

			if ($fFileSize > 0)
			{
				$sBackupDir = date("Y-m");

				@mkdir(($sBaseDir.SMS_BACKUP_DIR.$sBackupDir), 0777);

				$sTodayDir = ($sBackupDir."/".date("d-m-Y"));

				@mkdir($sDbLogDir, 0777);

				if (@file_exists($sBaseDir.SMS_BACKUP_DIR.$sTodayDir))
					$sSmsFile = ($sBaseDir.SMS_BACKUP_DIR.$sTodayDir."/".@basename($sFile));

				else
				{
					if (@mkdir(($sBaseDir.SMS_BACKUP_DIR.$sTodayDir), 0777) == TRUE)
						$sSmsFile = ($sBaseDir.SMS_BACKUP_DIR.$sTodayDir."/".@basename($sFile));

					else
						$sSmsFile = ($sBaseDir.SMS_BACKUP_DIR.@basename($sFile));
				}


				if (@copy($sFile, $sSmsFile) == true)
					@unlink($sFile);

				else
				{
					if ($bDebug == true)
						print "\r\nSMS File: {$sFile}\r\nERROR: Unable to download the Sms Input File";
				}


				$hFile = @fopen($sSmsFile, "r");

				if ($hFile)
				{
					$bFlag    = true;
					$sSender  = "";
					$sBody    = "";
					$sSubject = "--- Invalid SMS Entry ---";

					while (!@feof($hFile))
					{
						$sSms = @fgets($hFile);
						$sSms = trim($sSms);

						if (strtolower(substr($sSms, 0, 7)) == "sender=")
						{
							$sSender = trim(substr($sSms, 7));

							continue;
						}


						if (strtolower(substr($sSms, 0, 5)) == "data=")
						{
							$sAuditStagesList = getList("tbl_audit_stages", "code", "stage");
							$sAuditStages     = array( );

							foreach ($sAuditStagesList as $sCode => $sStage)
								$sAuditStages[] = $sCode;


							//if (@in_array($sSender, array("Telenor", "80004", "80002", "800", "345", "80100",  "80500", "81000")))
							if (strlen($sSender) <= 8)
							{
								@include($sBaseDir."includes/sms/alert.php");

								continue;
							}


							$sSms = trim(@substr($sSms, 5));

							if (strtolower(substr($sSms, 0, 7)) == "audit: ")
							{
								@include($sBaseDir."includes/sms/audit-schedule.php");

								continue;
							}

							else if (strlen($sSms) <= 9 && @in_array(strtoupper(substr($sSms, 0, strpos($sSms, " "))), array("IN", "OUT")))
							{
								@include($sBaseDir."includes/sms/attendance.php");

								continue;
							}


							if ($bDebug == true)
								print $sSms."\r\n";


							$sFields = @explode(",", $sSms);
							$sFields = @array_map("trim", $sFields);

							$iCount = count($sFields);


							if (strtoupper($sFields[0]) == "NKE")
								@include($sBaseDir."includes/sms/nike-qa-report.php");

							else if (strtoupper($sFields[0]) == "GF")
								@include($sBaseDir."includes/sms/gf-qa-report.php");

							else if (strtoupper($sFields[0]) == "JK")
								@include($sBaseDir."includes/sms/jako-qa-report.php");

							else if (strtoupper($sFields[0]) == "MS")
								@include($sBaseDir."includes/sms/ms-qa-report.php");

							else
								@include($sBaseDir."includes/sms/qa-report.php");
						}
					}

					@fclose( );
				}

				else
				{
					if ($bDebug == true)
						print "\r\nSMS File: {$sFile}\r\nERROR: Unable to open the Sms Input File";
				}


				if ($bDebug == true)
					print "\r\n";
			}

			else
			{
				if ($bDebug == true)
					print "\r\nSMS File: {$sFile}\r\nERROR: Unable to get the Size of Sms Input File";
			}
		}
	}


	// Clear Temp Folder
	$sFiles = @glob($sBaseDir.TEMP_DIR."*.*");

	foreach ($sFiles as $sFile)
	{
		if (@filemtime($sFile) < (time( ) - 300))
			@unlink($sFile);
	}


	$objSms->close( );

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );
?>