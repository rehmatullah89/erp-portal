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

    /* A PHP class to access MySQL Database with convenient Methods using MySQLi
    *
    * @version  2.0
    * @author   Muhammad Tahir Shahzad
    */

	class SpDatabase
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

		function SpDatabase( )
		{
			$this->sServer   = DB_SERVER;
			$this->sDatabase = DB_NAME;
			$this->sUserName = DB_USER;
			$this->sPassword = DB_PASSWORD;

			$this->dbConnection = NULL;
			$this->dbResultSet  = NULL;

			$this->iCount      = 0;
			$this->iAutoNumber = 0;
			$this->sError      = NULL;

			if (!$this->dbConnection)
				$this->connect( );
		}

		function connect( )
		{
			$this->dbConnection = @mysqli_connect($this->sServer, $this->sUserName, $this->sPassword, $this->sDatabase);

			if (@mysqli_connect_errno( ))
			{
  				print "Error: Unable to connect to the database Server.<br />";
  				print "Error No: ".@mysqli_connect_errno( )."<br />";
  				print "Error Details: ".@mysqli_connect_error( );
  				exit( );
			}

			@mysqli_autocommit($this->dbConnection, TRUE);
		}


		function query($sQuery, $bFlag = true)
		{
			if ($bFlag == true)
			{
				if (@strpos($sQuery, "INSERT") !== FALSE || @strpos($sQuery, "DELETE") !== FALSE || @strpos($sQuery, "UPDATE") !== FALSE)
				{
					$sDbLogDir  = (DB_LOGS_DIR.date("Y-m"));
					$sDbLogFile = ($sDbLogDir."/".date("d-m-Y").".sql");

					@mkdir($sDbLogDir, 0777);

					if (LOG_DB_TRANSACTIONS == TRUE)
					{
						$hFile = @fopen($sDbLogFile, "a+");

						if ($hFile)
						{
							@fwrite($hFile, "\n-- \n");

							if (LOG_SESSION_USER_ID != "")
								@fwrite($hFile, ("-- User ID    : ".$_SESSION[LOG_SESSION_USER_ID]."\n"));

							if (LOG_SESSION_USER_NAME != "")
								@fwrite($hFile, ("-- User Name  : ".$_SESSION[LOG_SESSION_USER_NAME]."\n"));

							@fwrite($hFile, ("-- Query Time : ".date('h:i A')."\n"));
							@fwrite($hFile, ("-- IP Address : ".$_SERVER['REMOTE_ADDR']."\n"));
							@fwrite($hFile, ("-- Web Page   : ".$_SERVER['PHP_SELF']."\n"));
							@fwrite($hFile, ("-- Referer    : ".$_SERVER['HTTP_REFERER']."\n"));
							@fwrite($hFile, "-- \n\n");
							@fwrite($hFile, $sQuery);
							@fwrite($hFile, "\n\n-- ----------------------------------------------------------------------------\n");

							@fclose($hFile);
						}
					}
				}
			}

			@mysqli_free_result($this->dbResultSet);

			if (@in_array($sQuery, array("COMMIT", "BEGIN", "ROLLBACK")))
			{
				if ($sQuery == "BEGIN")
					@mysqli_autocommit($this->dbConnection, FALSE);

				else if ($sQuery == "COMMIT")
				{
					@mysqli_commit($this->dbConnection);
					@mysqli_autocommit($this->dbConnection, TRUE);
				}

				else if ($sQuery == "ROLLBACK")
				{
					@mysqli_rollback($this->dbConnection);
					@mysqli_autocommit($this->dbConnection, TRUE);
				}
			}

			else
			{
				@mysqli_next_result($this->dbConnection);
				@mysqli_free_result($this->dbResultSet);

				$this->dbResultSet = @mysqli_query($this->dbConnection, $sQuery);

				if (!$this->dbResultSet)
				{
					$this->sError       = @mysqli_error($this->dbConnection);
					$this->iCount       = 0;
					$this->iFieldsCount = 0;

					return false;
				}

				else
				{
					$this->iCount       = @mysqli_num_rows($this->dbResultSet);
					$this->iFieldsCount = @mysqli_num_fields($this->dbResultSet);

					return true;
				}
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
			$objFieldInfo = @mysqli_fetch_field_direct($this->dbResultSet, $iIndex);

			return $objFieldInfo->name;
		}


		function getFieldType($iIndex)
		{
			$objFieldInfo = @mysqli_fetch_field_direct($this->dbResultSet, $iIndex);

			switch ($objFieldInfo->type)
			{
				case MYSQLI_TYPE_DECIMAL     : $sType = 'DECIMAL'; break;
				case MYSQLI_TYPE_NEWDECIMAL  : $sType = 'DECIMAL'; break;
				case MYSQLI_TYPE_TINY        : $sType = 'TINYINT'; break;
				case MYSQLI_TYPE_SHORT       : $sType = 'INT'; break;
				case MYSQLI_TYPE_LONG        : $sType = 'INT'; break;
				case MYSQLI_TYPE_FLOAT       : $sType = 'FLOAT'; break;
				case MYSQLI_TYPE_DOUBLE      : $sType = 'DOUBLE'; break;
				case MYSQLI_TYPE_TIMESTAMP   : $sType = 'TIMESTAMP'; break;
				case MYSQLI_TYPE_LONGLONG    : $sType = 'BIGINT'; break;
				case MYSQLI_TYPE_INT24       : $sType = 'MEDIUMINT'; break;
				case MYSQLI_TYPE_DATE        : $sType = 'DATE'; break;
				case MYSQLI_TYPE_TIME        : $sType = 'TIME'; break;
				case MYSQLI_TYPE_DATETIME    : $sType = 'DATETIME'; break;
				case MYSQLI_TYPE_YEAR        : $sType = 'YEAR'; break;
				case MYSQLI_TYPE_NEWDATE     : $sType = 'DATE'; break;
				case MYSQLI_TYPE_ENUM        : $sType = 'ENUM'; break;
				case MYSQLI_TYPE_SET         : $sType = 'SET'; break;
				case MYSQLI_TYPE_TINY_BLOB   : $sType = 'TINYBLOB'; break;
				case MYSQLI_TYPE_MEDIUM_BLOB : $sType = 'MEDIUMBLOB'; break;
				case MYSQLI_TYPE_LONG_BLOB   : $sType = 'LONGBLOB'; break;
				case MYSQLI_TYPE_BLOB        : $sType = 'BLOB'; break;
				case MYSQLI_TYPE_VAR_STRING  : $sType = 'VARCHAR'; break;
				case MYSQLI_TYPE_STRING      : $sType = 'CHAR'; break;
				case MYSQLI_TYPE_GEOMETRY    : $sType = 'GEOMETRY'; break;
			}

			return $sType;
		}


		function getField($iIndex, $sField)
		{
			@mysqli_data_seek($this->dbResultSet, $iIndex);

			$sResultRow = @mysqli_fetch_array($this->dbResultSet, MYSQLI_BOTH);


			if ($_SESSION['Guest'] == "Y" && !@is_numeric($sField))
			{
				if ($sField == "_Brand" || $sField == "brand")
					return ("Brand ".(($iIndex > 0) ? " - {$iIndex}" : "X"));

				else if ($sField == "_Vendor" || $sField == "vendor")
					return ("Vendor ".(($iIndex > 0) ? " - {$iIndex}" : "X"));

			}

			return $sResultRow[$sField];
		}


		function execute($sQuery, $bFlag = true)
		{
			if ($bFlag == true)
			{
				$sDbLogDir  = (DB_LOGS_DIR.date("Y-m"));
				$sDbLogFile = ($sDbLogDir."/".date("d-m-Y").".sql");

				@mkdir($sDbLogDir, 0777);

				if (LOG_DB_TRANSACTIONS == TRUE)
				{
					$hFile = @fopen($sDbLogFile, "a+");

					if ($hFile)
					{
						@fwrite($hFile, "\n-- \n");

						if (LOG_SESSION_USER_ID != "")
							@fwrite($hFile, ("-- User ID    : ".$_SESSION[LOG_SESSION_USER_ID]."\n"));

						if (LOG_SESSION_USER_NAME != "")
							@fwrite($hFile, ("-- User Name  : ".$_SESSION[LOG_SESSION_USER_NAME]."\n"));

						@fwrite($hFile, ("-- Query Time : ".date('h:i A')."\n"));
						@fwrite($hFile, ("-- IP Address : ".$_SERVER['REMOTE_ADDR']."\n"));
						@fwrite($hFile, ("-- Web Page   : ".$_SERVER['PHP_SELF']."\n"));
						@fwrite($hFile, ("-- Referer    : ".$_SERVER['HTTP_REFERER']."\n"));
						@fwrite($hFile, "-- \n\n");
						@fwrite($hFile, $sQuery);
						@fwrite($hFile, "\n\n-- ----------------------------------------------------------------------------\n");

						@fclose($hFile);
					}
				}
			}

			@mysqli_free_result($this->dbResultSet);

			if (@in_array($sQuery, array("COMMIT", "BEGIN", "ROLLBACK")))
			{
				if ($sQuery == "BEGIN")
					@mysqli_autocommit($this->dbConnection, FALSE);

				else if ($sQuery == "COMMIT")
				{
					@mysqli_commit($this->dbConnection);
					@mysqli_autocommit($this->dbConnection, TRUE);
				}

				else if ($sQuery == "ROLLBACK")
				{
					@mysqli_rollback($this->dbConnection);
					@mysqli_autocommit($this->dbConnection, TRUE);
				}
			}

			else
			{
				if (!@mysqli_query($this->dbConnection, $sQuery))
				{
					@mysqli_next_result($this->dbConnection);
					@mysqli_free_result($this->dbResultSet);

					$this->sError       = @mysqli_error($this->dbConnection);
					$this->iCount       = 0;
					$this->iFieldsCount = 0;

					return false;
				}

				else
				{
					$this->iAutoNumber  = @mysqli_insert_id($this->dbConnection);
					$this->iCount       = 0;
					$this->iFieldsCount = 0;
				}
			}

			return true;
		}


		function close( )
		{
			@mysqli_free_result($this->dbResultSet);
			@mysqli_close($this->dbConnection);
		}


		function error( )
		{
			return $this->sError;
		}
	}
?>