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

	@require_once("../../requires/session.php");

	if ($sUserRights['Edit'] != "Y")
	{
		print "ERROR|-|You havn\'t enough Rights to modify the selected Data.\n";
		exit( );
	}

	$objDbGlobal = new Database( );


	$File      = IO::strValue("File");
	$AuditDate = IO::strValue('AuditDate');


	@list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);

	if (@unlink("../".$sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$File))
	{
		if (LOG_DB_TRANSACTIONS == TRUE)
		{
			$sDbLogDir  = (DB_LOGS_DIR.date("Y-m"));
			$sDbLogFile = ($sDbLogDir."/".date("d-m-Y").".sql");

			@mkdir($sDbLogDir, 0777);


			$hFile = @fopen($sDbLogFile, "a+");

			if ($hFile)
			{
				@fwrite($hFile, "\n-- \n");

				$iUser = $_SESSION[LOG_SESSION_USER_ID];
				$sUser = $_SESSION[LOG_SESSION_USER_NAME];

				if ($iUser > 0)
					@fwrite($hFile, ("-- User ID    : {$iUser}\n"));

				if ($sUser != "")
					@fwrite($hFile, ("-- User Name  : {$sUser}\n"));


				@fwrite($hFile, ("-- Query Time : ".date('h:i A')."\n"));
				@fwrite($hFile, ("-- IP Address : ".$_SERVER['REMOTE_ADDR']."\n"));
				@fwrite($hFile, ("-- Web Page   : ".$_SERVER['PHP_SELF']."\n"));
				@fwrite($hFile, ("-- Referer    : ".$_SERVER['HTTP_REFERER']."\n"));
				@fwrite($hFile, "-- \n\n");
				@fwrite($hFile, "DELETE QA IMG {$File}");
				@fwrite($hFile, "\n\n-- ----------------------------------------------------------------------------\n");

				@fclose($hFile);
			}
		}

		print "DELETED";
	}


	$objDbGlobal->close( );

	@ob_end_flush( );
?>