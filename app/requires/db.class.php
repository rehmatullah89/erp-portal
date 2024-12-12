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

    /* A PHP class to access MySQL Database with convenient Methods
    *
    * @version  1.5
    * @author   Muhammad Tahir Shahzad
    */

	class Database
	{
		var $sServer;
		var $sDatabase;
		var $sUserName;
		var $sPassword;

		var $dbConnection;
		var $dbResultSet;

		var $iCount;
		var $iFieldsCount;
		var $iAutoNumber;
		var $sError;

		function Database( )
		{
			$this->sServer   = DB_SERVER;
			$this->sDatabase = DB_NAME;
			$this->sUserName = DB_USER;
			$this->sPassword = DB_PASSWORD;

			$this->dbConnection = NULL;
			$this->dbResultSet  = NULL;

			$this->iCount       = 0;
			$this->iAutoNumber  = 0;
			$this->sError       = NULL;

			if (!$this->dbConnection)
				$this->connect( );
		}

		function connect( )
		{
			$this->dbConnection = @mysql_connect(($this->sServer.((@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE) ? ":3300" : "")), $this->sUserName, $this->sPassword);

			if (!$this->dbConnection)
			{
  				print "Error: Unable to connect to the database Server.";

  				exit( );
			}

			if (!@mysql_select_db($this->sDatabase, $this->dbConnection))
			{
				print "Error: Unable to locate the Database.";

  				exit( );
			}

			@mysql_query("SET NAMES 'utf8'");
			@mysql_query("SET SESSION time_zone='+05:00'");
		}


		function query($sQuery, $bFlag = false)
		{
			if ($bFlag == true && LOG_DB_TRANSACTIONS == TRUE)
			{
				$sDbLogDir  = (DB_LOGS_DIR.date("Y-m"));
				$sDbLogFile = ($sDbLogDir."/".date("d-m-Y").".sql");

				@mkdir($sDbLogDir, 0777);

				$hFile = @fopen($sDbLogFile, "a+");

				if ($hFile)
				{
					@fwrite($hFile, "\n-- \n");
					@fwrite($hFile, ("-- Query Time : ".date('h:i A')."\n"));
					@fwrite($hFile, ("-- IP Address : ".$_SERVER['REMOTE_ADDR']."\n"));
					@fwrite($hFile, ("-- Web Page   : ".$_SERVER['PHP_SELF']."\n"));
					@fwrite($hFile, ("-- Referer    : ".$_SERVER['HTTP_REFERER']."\n"));
					@fwrite($hFile, "-- \n\n");
					@fwrite($hFile, "{$sQuery};");
					@fwrite($hFile, "\n\n-- ----------------------------------------------------------------------------\n");

					@fclose($hFile);
				}
			}

			@mysql_free_result($this->dbResultSet);

			$this->dbResultSet = @mysql_query($sQuery, $this->dbConnection);

			if (!$this->dbResultSet)
			{
				$this->sError       = @mysql_error( );
				$this->iCount       = 0;
				$this->iFieldsCount = 0;

				return false;
			}

			else
			{
				$this->iCount       = @mysql_num_rows($this->dbResultSet);
				$this->iFieldsCount = @mysql_num_fields($this->dbResultSet);

				return true;
			}
		}


		function getCount( )
		{
			return $this->iCount;
		}


		function getFieldsCount( )
		{
			return $this->iFieldsCount;
		}


		function getAutoNumber( )
		{
			return $this->iAutoNumber;
		}


		function getFieldName($iIndex)
		{
			return @mysql_field_name($this->dbResultSet, $iIndex);
		}

		function getFieldType($iIndex)
		{
			return @mysql_field_type($this->dbResultSet, $iIndex);
		}

		function getField($iIndex, $sField)
		{
			if (strtolower(@mysql_result($this->dbResultSet, $iIndex, $sField)) == "test vendor" ||
				strtolower(@mysql_result($this->dbResultSet, $iIndex, $sField)) == "test brand")
				return @mysql_result($this->dbResultSet, $iIndex, $sField);
			
			
			global $sGuest;
			
			if ($sGuest == "Y" && !@is_numeric($sField))
			{
				if ($sField == "_User" || $sField == "_Auditor")
					return ("User ".(($iIndex > 0) ? " - {$iIndex}" : "X"));

				else if ($sField == "_Brand" || $sField == "brand" || $sField == "Brand" || $sField == "b.brand")
					return ("Brand ".(($iIndex > 0) ? " - {$iIndex}" : "X"));

				else if ($sField == "_Vendor" || $sField == "vendor" || $sField == "Vendor" || $sField == "v.vendor")
					return ("Vendor ".(($iIndex > 0) ? " - {$iIndex}" : "X"));

			}

			
			return @mysql_result($this->dbResultSet, $iIndex, $sField);
		}


		function execute($sQuery, $bFlag = true, $iUser = 0, $sUser = "")
		{
			if ($bFlag == true && LOG_DB_TRANSACTIONS == TRUE)
			{
				$sDbLogDir  = (DB_LOGS_DIR.date("Y-m"));
				$sDbLogFile = ($sDbLogDir."/".date("d-m-Y").".sql");

				@mkdir($sDbLogDir, 0777);

				$hFile = @fopen($sDbLogFile, "a+");

				if ($hFile)
				{
					@fwrite($hFile, "\n-- \n");

					if ($iUser > 0)
						@fwrite($hFile, ("-- User ID    : {$iUser}\n"));

					if ($sUser != "")
						@fwrite($hFile, ("-- User Name  : {$sUser}\n"));

					@fwrite($hFile, ("-- Query Time : ".date('h:i A')."\n"));
					@fwrite($hFile, ("-- IP Address : ".$_SERVER['REMOTE_ADDR']."\n"));
					@fwrite($hFile, ("-- Web Page   : ".$_SERVER['PHP_SELF']."\n"));
					@fwrite($hFile, ("-- Referer    : ".$_SERVER['HTTP_REFERER']."\n"));
					@fwrite($hFile, "-- \n\n");
					@fwrite($hFile, "{$sQuery};");
					@fwrite($hFile, "\n\n-- ----------------------------------------------------------------------------\n");

					@fclose($hFile);
				}
			}

			@mysql_free_result($this->dbResultSet);

			if (!@mysql_query($sQuery, $this->dbConnection))
			{
				$this->sError       = @mysql_error( );
				$this->iCount       = 0;
				$this->iFieldsCount = 0;

				return false;
			}

			else
			{
				$this->iAutoNumber  = @mysql_insert_id( );
				$this->iCount       = 0;
				$this->iFieldsCount = 0;
			}

			return true;
		}


		function close( )
		{
			@mysql_free_result($this->dbResultSet);
			@mysql_close($this->dbConnection);
		}


		function error( )
		{
			return $this->sError;
		}
	}
?>