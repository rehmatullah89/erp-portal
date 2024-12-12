<?php
	@ini_set('log_errors', 1);
	@ini_set('display_errors', 1);
	@error_reporting(E_ALL);

	@putenv("TZ=Asia/Karachi");
	@date_default_timezone_set("Asia/Karachi");
	@ini_set("date.timezone", "Asia/Karachi");

	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);


	$sZipDir   = "C:/wamp/www/portal/mgf/";
	$sFiles    = array("INSPECTION.xlsx", "INSPECTION_DEFECT.xlsx", "INSPECTION_AUDIT_SIZE.xlsx", "INSPECTION_COLOR.xlsx", "INSPECTION_VPO.xlsx");
	$sFileName = ("QUONDA_MGF_".date("Ymd_Hs").".zip");
	$sZipFile  = "{$sZipDir}{$sFileName}";

	@unlink($sZipFile);



 	$objZip = new ZipArchive( );

 	if ($objZip && $objZip->open($sZipFile, ZIPARCHIVE::CREATE) === TRUE)
	{
		foreach ($sFiles as $sFile)
		{
			if (@file_exists("{$sZipDir}{$sFile}"))
				$objZip->addFile("{$sZipDir}{$sFile}", $sFile);
		}

		$objZip->close( );



		$hConn = @ftp_connect("125.209.75.188", 31);

		if ($hConn)
		{
			if (@ftp_login($hConn, "tahir", "matrix101"))
			{
				print "FTP Session Started<br />";
				error_log("FTP Session Started", 0);
			}

			else
			{
				error_log("FTP Session FAILED", 0);
				print "FTP Session FAILED<br />";
			}

			@ftp_pasv($hConn, true);
			@ftp_chdir($hConn, "/TEST/Incoming/Inspection/");

/*
			if (@ftp_delete($hConn, @basename($sZipFile)))
			{
				print "Old File Deleted<br />";
				error_log("Old File Deleted", 0);
			}

			else
			{
				print "Old File Delete Failed<br />";
				error_log("Old File Delete Failed", 0);
			}
*/

			if (!@ftp_put($hConn, $sFileName, $sZipFile, FTP_BINARY))
			{
				error_log("File Upload failed", 0);
				print "Upload Failed<br />";
			}

			else
			{
				print "ZIP FILE UPLOADED<br />";
				error_log("Zip File Uploaded successfully", 0);
			}

			
			@ftp_close($hConn);

			print "FTP Session Closed<br /><br />";
			error_log("FTP Session Closed", 0);
			
			
			@unlink($sZipFile);
		}

		else
		{
			error_log("FTP Connection Error", 0);
			print "FTP Connection Error<br /><br />";
		}


/*
		$bUploaded = copy($sZipFile, "ftp://tahir:matrix101@125.209.75.188:31/TEST/Outgoing/Inspection/{$sFileName}");

		if (!$bUploaded)
		{
			error_log("Uploading Copy Failed", 0);
			print "Uploading Copy Failed";
		}

		else
		{
			error_log("File Copied", 0);
			print "File Copied";
		}
*/
	}

	else
		print "Zip File Error";
?>