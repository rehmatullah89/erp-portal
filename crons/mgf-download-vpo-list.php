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

	@session_start( );

	@ini_set('display_errors', 0);
	@ini_set('log_errors', 0);
	@error_reporting(0);


	print ("START: ".date("h:i A")."<hr />");	
	
	
	$sBaseDir = "C:/wamp/www/portal/";
	$sVpoFile = "{$sBaseDir}mgf/vpo-list.xlsx";

	@unlink($sVpoFile);


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
		@ftp_chdir($hConn, "/TEST/Outgoing/VPO/");

		$sAllFiles   = @ftp_nlist($hConn, '');		
		$iLatestFile = 0;
		$sLatestFile = "";

		foreach ($sAllFiles as $sFile)
		{
			$iModifiedTime = @ftp_mdtm($hConn, $sFile);

			if ($iModifiedTime > $iLatestFile)
			{
				$iLatestFile = $iModifiedTime;
				$sLatestFile = $sFile;
			}
		}

		
		if ($sLatestFile != "")
		{
			if (@ftp_get($hConn, $sVpoFile, $sLatestFile, FTP_ASCII))
			{
				foreach ($sAllFiles as $sFile)
				{
					if (@ftp_delete($hConn, $sFile))
					{
						print "File Deleted from Server successfully - {$sFile}<br /><br />";
						error_log("File Deleted from Server successfully - {$sFile}", 0);
					}

					else
					{
						print "ERROR in Deleting File - {$sFile}<br /><br />";
						error_log("ERROR in Deleting File - {$sFile}", 0);
					}
				}
/*
				if (@ftp_delete($hConn, $sLatestFile))
				{
					print "File Deleted from Server successfully<br /><br />";
					error_log("File Deleted from Server successfully", 0);
				}

				else
				{
					print "ERROR in Deleting File<br /><br />";
					error_log("ERROR in Deleting File", 0);
				}			
*/
			}
			
			else
			{
				print "ERROR in Downloading File<br /><br />";
				error_log("ERROR in Downloading File", 0);
			}
		}
		
		else
		{
			print "No File Found on FTP Server<br /><br />";
			error_log("No File Found on FTP Server", 0);
		}

		
		@ftp_close($hConn);

		print "FTP Session Closed<br /><br />";
		error_log("FTP Session Closed", 0);
	}

	else
	{
		error_log("FTP Connection Error", 0);
		print "FTP Connection Error<br /><br />";
	}
	
	
	print ("<hr />END: ".date("h:i A")."<br />");
?>