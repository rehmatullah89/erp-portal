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

	@require_once("../requires/session.php");

	if ($sUserRights['Delete'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
        $objDb       = new Database( );
        
	$File      = IO::strValue("File");
	$AuditDate = IO::strValue('AuditDate');
        $AuditId   = IO::strValue('AuditId');
        $ReportId  = IO::strValue('ReportId');
        $ImageId   = IO::intValue('ImageId');
	$Referer   = urldecode(IO::strValue("Referer"));

	@list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);

        if(@in_array($ReportId, array(6,7,10,11,19,20,23,25,26,28,29,30,31,32,33,35,36,37,38)) || strtotime($AuditDate) > strtotime('2017-02-15') || getDbValue("COUNT(1)", "tbl_qa_report_images", "audit_id='$AuditId' AND id='$ImageId'") > 0)
        {
            if($ImageId > 0)
            {
                $sSQL  = "DELETE FROM tbl_qa_report_images WHERE id='$ImageId' AND audit_id='$AuditId' AND `image` LIKE '$File'";
                $bFlag = $objDb->execute($sSQL);
            }
        }
        
	if (@unlink($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$File))
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


		redirect(($_SERVER['HTTP_REFERER']."&Referer=".urlencode($Referer)), "QA_IMAGE_DELETED");
	}

	else
		redirect(($_SERVER['HTTP_REFERER']."&Referer=".urlencode($Referer)), "ERROR");

        $objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>