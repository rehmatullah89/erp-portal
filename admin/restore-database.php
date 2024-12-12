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

	function removeSqlComments($sSQL)
	{
		return @preg_replace('/\n{2,}/', "\n", preg_replace('/^-- .*$/m', "\n", $sSQL));
	}

	function splitSqlFile($sSQL, $sDelimiter)
	{
		$sSQL  = str_replace("\r" , '', $sSQL);
		$sData = preg_split('/' . preg_quote($sDelimiter, '/') . '$/m', $sSQL);

		$sData = array_map('trim', $sData);

		$sEndData = end($sData);

		if (empty($sEndData))
			unset($sData[key($sData)]);

		return $sData;
	}

	$File     = IO::strValue("File");
	$bZipFlag = false;

	if (!@file_exists($sBaseDir.DB_BACKUP_PATH.$File))
		redirect($_SERVER['HTTP_REFERER'], "BACKUP_READ_ERROR");

	if (@substr($File, -4) == ".zip")
	{
		// Extracting the zip file
		$objZip = new ZipArchive( );

		if ($objZip->open($sBaseDir.DB_BACKUP_PATH.$File) === TRUE)
		{
			$objZip->extractTo($sBaseDir.DB_BACKUP_PATH);
			$objZip->close( );

			$File     = (substr($File, 0, -4).".sql");
			$bZipFlag = true;
		}
	}


	$sSqlData = @file_get_contents($sBaseDir.DB_BACKUP_PATH.$File);
	$sSqlData = @trim($sSqlData);
	$sSqlData = @removeSqlComments($sSqlData);
	$sSqlData = @splitSqlFile($sSqlData, ";");
	$bFlag    = true;

	$objDb->execute("BEGIN");

	$sSQL = ("SHOW TABLES FROM ".DB_NAME.";");

	if ($objDb->query($sSQL) == false)
	{
		if ($bZipFlag == true)
			@unlink($sBaseDir.DB_BACKUP_PATH.$File);

		redirect($_SERVER['HTTP_REFERER'], "DB_ERROR");
	}

	$iCount = $objDb->getCount( );

	// Deleting Previous Tables
	$sTables = "";

	for ($i = 0; $i < $iCount; $i ++)
	{
   		$sTables .= $objDb->getField($i, 0);

   		if ($i < ($iCount - 1))
   			$sTables .= ", ";
	}

	if ($iCount > 0)
	{
		$sSQL  = "DROP TABLE $sTables;";
		$bFlag = $objDb->execute($sSQL);
	}

	// Inserting New Tables & Data
	if ($bFlag == true)
	{
		$iCount = count($sSqlData);

		for ($i = 0; $i < $iCount; $i ++)
		{
			if ($objDb->execute($sSqlData[$i], false) == false)
			{
				$bFlag = false;
				break;
			}
		}
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		$_SESSION['Flag'] = "BACKUP_RESTORED";
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";
	}

	if ($bZipFlag == true)
		@unlink($sBaseDir.DB_BACKUP_PATH.$File);

	header("Location: {$_SERVER['HTTP_REFERER']}");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>