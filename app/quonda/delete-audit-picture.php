<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree QUONDA App                                                                   **
	**  Version 3.0                                                                              **
	**                                                                                           **
	**  http://app.3-tree.com                                                                    **
	**                                                                                           **
	**  Copyright 2008-17 (C) Triple Tree                                                        **
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
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$User      = IO::strValue('User');
	$AuditCode = IO::strValue("AuditCode");
	$Picture   = IO::strValue("Picture");
	$Type      = IO::strValue("Type");

	$iAuditCode = intval(substr($AuditCode, 1));
	$Picture    = substr($Picture, (strrpos($Picture, "/") + 1));


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $iAuditCode == 0 || $AuditCode{0} != "S" || $Picture == "" || $Type == "")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser  = $objDb->getField(0, "id");
			$sName  = $objDb->getField(0, "name");
			$sGuest = $objDb->getField(0, "guest");


			$sAuditDate = getDbValue("audit_date", "tbl_qa_reports", "id='$iAuditCode'");
			
			@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
			
			$sQuondaDir = (ABSOLUTE_PATH.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
			
			
			if ($Type != "LAB")
			{
				if (@unlink(ABSOLUTE_PATH.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$Picture))
				{
					if (ABSOLUTE_PATHLOG_DB_TRANSACTIONS == TRUE)
					{
						$sDbLogDir  = (ABSOLUTE_PATH.DB_LOGS_DIR.date("Y-m"));
						$sDbLogFile = ($sDbLogDir."/".date("d-m-Y").".sql");

						@mkdir($sDbLogDir, 0777);


						$hFile = @fopen($sDbLogFile, "a+");

						if ($hFile)
						{
							@fwrite($hFile, "\n-- \n");
							@fwrite($hFile, ("-- User ID    : {$iUser}\n"));
							@fwrite($hFile, ("-- User Name  : {$sName}\n"));
							@fwrite($hFile, ("-- Query Time : ".date('h:i A')."\n"));
							@fwrite($hFile, ("-- IP Address : ".$_SERVER['REMOTE_ADDR']."\n"));
							@fwrite($hFile, ("-- Web Page   : ".$_SERVER['PHP_SELF']."\n"));
							@fwrite($hFile, ("-- Referer    : ".$_SERVER['HTTP_REFERER']."\n"));
							@fwrite($hFile, "-- \n\n");
							@fwrite($hFile, "DELETE QA IMG {$Picture}");
							@fwrite($hFile, "\n\n-- ----------------------------------------------------------------------------\n");
							@fclose($hFile);
						}
					}

					
					$aResponse['Status']  = "OK";
					$aResponse['Message'] = "The selected Picture has been Deleted successfully.";
				}
				
				else
					$aResponse['Message'] = "An ERROR occured while processing your request.";
			}
			
			else
			{
				$bDeleted = false;
				
				
				$sSQL = "SELECT * FROM tbl_qa_reports WHERE id='$iAuditCode'";
				$objDb->query($sSQL);		
				
				for ($i = 1; $i <= 10; $i ++)
				{
					$sSpecsFile = $objDb->getField(0, "specs_sheet_{$i}");					
					
					if ($sSpecsFile == $Picture)
					{
						$sSQL = "UPDATE tbl_qa_reports SET specs_sheet_{$i}='' WHERE id='$iAuditCode'";
						
						if ($objDb->execute($sSQL, true, $iUser, $sName) == true)
						{
							@unlink(ABSOLUTE_PATH.SPECS_SHEETS_DIR.$sSpecsFile);
							@unlink(ABSOLUTE_PATH.SPECS_SHEETS_DIR."thumbs/".$sSpecsFile);
							
							@unlink(ABSOLUTE_PATH.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsFile);
							@unlink(ABSOLUTE_PATH.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/thumbs/".$sSpecsFile);
							
							
							$aResponse['Status']  = "OK";
							$aResponse['Message'] = "The selected Lab Report has been Deleted successfully.";
							
							$bDeleted = true;
						}
						
						
						break;
					}
				}
				
				
				if ($bDeleted == false)
					$aResponse['Message'] = "An ERROR occured while processing your request.";
			}
		}
	}


	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>