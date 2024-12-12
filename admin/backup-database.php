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
	$objTblDb    = new Database( );

	$sFile = BACKUP_FILE_NAME_FORMAT;
	$sFile = str_replace("%Y", date("Y"), $sFile);
	$sFile = str_replace("%m", date("m"), $sFile);
	$sFile = str_replace("%d", date("d"), $sFile);
	$sFile = str_replace("%H", date("H"), $sFile);
	$sFile = str_replace("%i", date("i"), $sFile);
	$sFile = str_replace("%s", date("s"), $sFile);

	$sSqlFile = ($sBaseDir.DB_BACKUP_PATH.$sFile);
	$hFile    = @fopen($sSqlFile, 'w');

	if (!@file_exists($sSqlFile))
		redirect($_SERVER['HTTP_REFERER'], "BACKUP_WRITE_ERROR");

	@fwrite($hFile, "\n-- \n");
	@fwrite($hFile, ("-- ".SITE_TITLE." SQL Dump\n"));
	@fwrite($hFile, ("-- ".SITE_URL."\n"));
	@fwrite($hFile, "-- \n");
	@fwrite($hFile, ("-- Host: ".DB_SERVER."\n"));
	@fwrite($hFile, ("-- Generation Time: ".date('l, jS F, Y   h:i A')."\n"));
	@fwrite($hFile, ("-- Server version: ".$_SERVER['SERVER_SOFTWARE']."\n"));
	@fwrite($hFile, ("-- MySQL Version: ".mysql_get_server_info( )."\n"));
	@fwrite($hFile, "-- \n");
	@fwrite($hFile, ("-- Database: `".DB_NAME."`\n"));
	@fwrite($hFile, "-- \n\n");
	@fwrite($hFile, "-- --------------------------------------------------------\n\n");

	$sSQL = ("SHOW TABLES FROM ".DB_NAME.";");

	if ($objTblDb->query($sSQL) == false)
	{
		@unlink($sSqlFile);

		redirect($_SERVER['HTTP_REFERER'], "DB_ERROR");
	}

	$iTblCount = $objTblDb->getCount( );

	for ($i = 0; $i < $iTblCount; $i ++)
	{
   		$sTable = $objTblDb->getField($i, 0);

   		// Table Structure
   		$sSQL = "SHOW CREATE TABLE $sTable;";

   		if ($objDb->query($sSQL) == false)
		{
			@unlink($sSqlFile);

			redirect($_SERVER['HTTP_REFERER'], "DB_ERROR");
		}

		@fwrite($hFile, "-- \n");
		@fwrite($hFile, "-- Table structure for table `".$sTable."`\n");
		@fwrite($hFile, "-- \n\n");

   		@fwrite($hFile, $objDb->getField(0, 1));
   		@fwrite($hFile, ";\n\n");

		@fwrite($hFile, "-- \n");
		@fwrite($hFile, "-- Dumping data for table `".$sTable."`\n");
		@fwrite($hFile, "-- \n\n");

   		// Table Data
   		$sSQL = "SELECT * FROM $sTable;";

   		if ($objDb->query($sSQL) == false)
		{
			@unlink($sSqlFile);

			redirect($_SERVER['HTTP_REFERER'], "DB_ERROR");
		}

		$iFieldsCount = $objDb->getFieldsCount( );
   		$iCount       = $objDb->getCount( );

   		for ($j = 0; $j < $iCount; $j ++)
   		{
   			$sRecord  = "INSERT INTO $sTable (";

   			// getting field names
   			for ($k = 0; $k < $iFieldsCount; $k ++)
   			{
   				$sRecord .= ("`".$objDb->getFieldName($k)."`");

   				if ($k < ($iFieldsCount - 1))
   					$sRecord .= ', ';
   			}

   			$sRecord .= ") VALUES (";

   			// getting field values
   			for ($k = 0; $k < $iFieldsCount; $k ++)
   			{
   				$sType  = $objDb->getFieldType($k);
   				$sValue = $objDb->getField($j, $k);

   				if (!isset($sValue))
   					$sRecord .= 'NULL';

   				else if ($sType == 'tinyint' || $sType == 'smallint' || $sType == 'mediumint' || $sType == 'int' ||
                         $sType == 'bigint'  ||$sType == 'timestamp')
                    $sRecord .= ("'".$sValue."'");

                else
                	$sRecord .= ("'".mysql_real_escape_string($sValue)."'");

   				if ($k < ($iFieldsCount - 1))
   					$sRecord .= ', ';
   			}

   			$sRecord .= ");\n";

   			@fwrite($hFile, $sRecord);
   		}

   		@fwrite($hFile, "\n-- --------------------------------------------------------\n\n");
	}

	@fclose($hFile);


	// Creating the zip file
	$objZip   = new ZipArchive( );
	$sZipFile = str_replace(".sql", ".zip", $sSqlFile);

	if ($objZip->open($sZipFile, ZIPARCHIVE::CREATE) === TRUE)
	{
		$objZip->addFile($sSqlFile, $sFile);
		$objZip->close( );

		@unlink($sSqlFile);
	}


	$_SESSION['Flag'] = "BACKUP_TAKEN";

	header("Location: {$_SERVER['HTTP_REFERER']}");

	$objDb->close( );
	$objTblDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>