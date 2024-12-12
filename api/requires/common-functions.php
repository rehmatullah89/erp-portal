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

	function formatDate($sDate, $sFormat = "d-M-Y", $bMgf = false)
	{
		if ($sDate == "" || $sDate == "0000-00-00" || $sDate == "1970-01-01" || $sDate == "0000-00-00 00:00:00" || $sDate == "1970-01-01 00:00:00")
			return "";

		else
		{
			if (strlen($sDate) == 19 && $bMgf == true)
				return date($sFormat, (strtotime($sDate) + 10800));

			return date($sFormat, strtotime($sDate));
		}
	}


	function formatTime($sTime, $sFormat = "h:i A", $bMgf = false)
	{
		if ($sTime == "" || $sTime == "00:00:00")
			return "";

		else
		{
			if ($bMgf == true)
				return date($sFormat, (strtotime($sTime) + 10800));

			return date($sFormat, strtotime($sTime));
		}
	}


	function formatNumber($sNumber, $bFlag = true, $iDecimals = 2)
	{
		if ($bFlag == false)
			$iDecimals = 0;

		return @number_format($sNumber, $iDecimals, '.', ',');
	}


	function getNextId($sTable)
	{
		global $objDbGlobal;

		if (!$objDbGlobal)
			$objDbGlobal = new Database( );

		$sSQL = "SELECT MAX(id) FROM $sTable";
		$objDbGlobal->query($sSQL);

		return ($objDbGlobal->getField(0, 0) + 1);
	}


	function getList($sTable, $sKey, $sValue, $sConditions = "", $sOrderBy = "", $sLimit = "")
	{
		global $objDbGlobal;
		$sList = array( );

		if (!$objDbGlobal)
			$objDbGlobal = new Database( );

		if ($sConditions != "")
			$sConditions = (" WHERE ".$sConditions);

		if ($sOrderBy == "")
			$sOrderBy = $sValue;

		if ($sLimit != "")
			$sLimit = " LIMIT $sLimit ";


		$sSQL = "SELECT $sKey, $sValue FROM $sTable $sConditions ORDER BY $sOrderBy $sLimit";
		$objDbGlobal->query($sSQL);

		$iCount = $objDbGlobal->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sList[$objDbGlobal->getField($i, 0)] = $objDbGlobal->getField($i, 1);

		return $sList;
	}


	function getDbValue($sField, $sTable, $sConditions, $sOrderBy = "", $sLimit = "")
	{
		if (!$objDbGlobal)
			$objDbGlobal = new Database( );

		if ($sConditions != "")
			$sConditions = " WHERE $sConditions";

		if ($sOrderBy != "")
			$sOrderBy = " ORDER BY $sOrderBy ";

		if ($sLimit != "")
			$sLimit = " LIMIT $sLimit ";


		$sSQL = "SELECT {$sField} FROM {$sTable} $sConditions $sOrderBy $sLimit";
		$objDbGlobal->query($sSQL);

		return $objDbGlobal->getField(0, 0);
	}


	function showRelativeTime($sDateTime, $sFormat = "F d, Y h:i A")
	{
		if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
			$iDifference = (time( ) - strtotime($sDateTime));

		else
			$iDifference = (time( ) - strtotime($sDateTime) + 18000);


		if ($iDifference < 60)
			return "less than a minute ago";

		$iDifference = @round($iDifference / 60);

		if ($iDifference < 60)
			return ($iDifference." minute".(($iDifference != 1) ? "s" : "")." ago");

		$iDifference = @round($iDifference / 60);

		if ($iDifference < 24)
			return ($iDifference." hour".(($iDifference != 1) ? "s" : "")." ago");

		$iDifference = @round($iDifference / 24);

		if ($iDifference < 7)
			return ($iDifference." day".(($iDifference != 1) ? "s" : "")." ago");

		$iDifference = @round($iDifference / 7);

		if ($iDifference < 4)
			return ($iDifference." week".(($iDifference != 1) ? "s" : "")." ago");


		return ("on ".formatDate($sDateTime, $sFormat));
	}


	function redirect($sPage)
	{
		header("Location: $sPage");
		exit( );
	}


	function createImage($sSrcFile, $sDestFile, $iImgWidth, $iImgHeight)
	{
		@list($iWidth, $iHeight, $sType, $sAttributes) = @getimagesize($sSrcFile);

		$fRatio     = @($iWidth / $iHeight);
		$iNewWidth  = $iImgWidth;
		$iNewHeight = $iImgHeight;
		$iLeft      = 0;
		$iTop       = 0;

		if (@($iNewWidth / $iNewHeight) > $fRatio)
		   $iNewWidth = ($iNewHeight * $fRatio);

		else
		   $iNewHeight = @($iNewWidth / $fRatio);


		if ($iNewWidth < $iImgWidth)
			$iLeft = @ceil(($iImgWidth - $iNewWidth) / 2);

		if ($iNewHeight < $iImgHeight)
			$iTop = @ceil(($iImgHeight - $iNewHeight) / 2);

		$iPosition  = @strrpos($sSrcFile, '.');
		$sExtension = @substr($sSrcFile, $iPosition);

		switch(strtolower($sExtension))
		{
			case '.jpg'  : $sSource = @imagecreatefromjpeg($sSrcFile);
						   break;

			case '.jpeg' : $sSource = @imagecreatefromjpeg($sSrcFile);
						   break;

			case '.png'  : $sSource = @imagecreatefrompng($sSrcFile);
						   break;

			case '.gif'  : $sSource = @imagecreatefromgif($sSrcFile);
						   break;
		}

		$sTemp = @imagecreatetruecolor($iNewWidth, $iNewHeight);
		@imagecopyresampled($sTemp, $sSource, 0, 0, 0, 0, $iNewWidth, $iNewHeight, $iWidth, $iHeight);

		$sThumb   = @imagecreatetruecolor($iImgWidth, $iImgHeight);
		$sBgColor = @imagecolorallocate($sThumb, 255, 255, 255);

		@imagefill($sThumb, 0, 0, $sBgColor);
		@imagecopy($sThumb, $sTemp, $iLeft, $iTop, 0, 0, $iNewWidth, $iNewHeight);


		if ($sExtension == ".png")
			@imagepng($sThumb, $sDestFile, 9);

		else if ($sExtension == ".gif")
			@imagegif($sThumb, $sDestFile);

		else
			@imagejpeg($sThumb, $sDestFile, 100);


		@imagedestroy($sTemp);
		@imagedestroy($sThumb);
		@imagedestroy($sSource);
	}


	function getExcelCol($iIndex)
	{
		$iIndex -= 64;

		$iQuotient  = @floor($iIndex / 26);
		$iRemainder = @($iIndex % 26);

		if ($iRemainder == 0 && $iQuotient > 0)
		{
			$iQuotient --;
			$iRemainder = 26;
		}

		$sCol = "";

		if ($iQuotient > 0)
			$sCol = chr($iQuotient + 64);

		$sCol .= chr($iRemainder + 64);

		return $sCol;
	}


	function authenticateUser( )
	{
		if (!isset($_SERVER['PHP_AUTH_USER']))
		{
			header('WWW-Authenticate: Basic realm="QUONDA® App Notifications"');
			header('HTTP/1.0 401 Unauthorized');

			print  "Sorry, you don't have the rights to access this System.";

			exit( );
		}

		else
		{
			if (!$objDbGlobal)
				$objDbGlobal = new Database( );

			$Username = $_SERVER['PHP_AUTH_USER'];
			$Password = $_SERVER['PHP_AUTH_PW'];

			$sSQL = "SELECT id, name, email, status FROM tbl_users WHERE username='$Username' AND password=PASSWORD('$Password')";

			if ($objDbGlobal->query($sSQL) == false || $objDbGlobal->getCount( ) != 1 || $objDbGlobal->getField(0, "status") != "A")
			{
				header('WWW-Authenticate: Basic realm="QUONDA® App Notifications"');
				header('HTTP/1.0 401 Unauthorized');

				print  "Sorry, you don't have the rights to access this System.";

				exit( );
			}
		}
	}


	if (!function_exists('mb_html_entity_decode'))
	{
		function mb_html_entity_decode($string, $flags = null, $encoding = 'UTF-8')
		{
			return html_entity_decode($string, ($flags === NULL) ? ENT_COMPAT | ENT_HTML401 : $flags, $encoding);
		}
	}


	function logApiCall($sParams)
	{
		$sLogDir = (API_CALLS_DIR.date("Y")."/");

		if (!@file_exists($sLogDir))
		{
			@mkdir($sLogDir, 0777);
			@chmod($sLogDir, 0777);
		}

		$sLogDir .= (strtolower(date("M"))."/");

		if (!@file_exists($sLogDir))
		{
			@mkdir($sLogDir, 0777);
			@chmod($sLogDir, 0777);
		}

		$sLogFile = ($sLogDir.date("d-M-Y").".txt");


		$hFile = @fopen($sLogFile, "a+");

		if ($hFile)
		{
			@flock($hFile, LOCK_EX);
			@fwrite($hFile, "\n-- \n");
			@fwrite($hFile, ("-- Query Time : ".date('h:i A')."\n"));
			@fwrite($hFile, ("-- IP Address : ".$_SERVER['REMOTE_ADDR']."\n"));
			@fwrite($hFile, ("-- API Call   : ".$_SERVER['PHP_SELF']."\n"));
			@fwrite($hFile, "-- \n\n");

			foreach ($sParams as $sKey => $sValue)
			{
				@fwrite($hFile, "{$sKey} = {$sValue}\n");
			}

			@fwrite($hFile, "\n\n-- ----------------------------------------------------------------------------\n");
			@flock($hFile, LOCK_UN);
			@fclose($hFile);
		}
	}


	function getSampleSize($iQuantity, $iReport = 0, $iInspectionLevel = 2, $iCheckLevel = 1)
	{
		$iSampleSize = $iQuantity;
		$sAqlChart   = array( );

		if ($iReport == 28)
		{
			$sAqlChart[] = array("Min" => 2,    "Max" => 90,    "Samples" => 0);
			$sAqlChart[] = array("Min" => 91,   "Max" => 150,   "Samples" => 20);
			$sAqlChart[] = array("Min" => 151,  "Max" => 280,   "Samples" => 32);
			$sAqlChart[] = array("Min" => 281,  "Max" => 500,   "Samples" => 50);
			$sAqlChart[] = array("Min" => 501,  "Max" => 1200,  "Samples" => 80);
			$sAqlChart[] = array("Min" => 1201, "Max" => 3200,  "Samples" => 125);
			$sAqlChart[] = array("Min" => 3201, "Max" => 10000, "Samples" => 200);
		}

		else if ($iReport == 37)
		{
			if ($iInspectionLevel == 1)
			{
				$sAqlChart[] = array("Min" => 2,   "Max" => 8,   "Samples" => 2);
				$sAqlChart[] = array("Min" => 9,   "Max" => 15,  "Samples" => 2);
				$sAqlChart[] = array("Min" => 16,  "Max" => 25,  "Samples" => 3);
				$sAqlChart[] = array("Min" => 26,  "Max" => 50,  "Samples" => 5);
				$sAqlChart[] = array("Min" => 51,  "Max" => 90,  "Samples" => 5);
				$sAqlChart[] = array("Min" => 91,  "Max" => 150, "Samples" => 8);
				$sAqlChart[] = array("Min" => 151, "Max" => 280, "Samples" => 13);
			}

			else
			{
				$sAqlChart[] = array("Min" => 2,    "Max" => 90,    "Samples" => 0);
				$sAqlChart[] = array("Min" => 91,   "Max" => 150,   "Samples" => 20);
				$sAqlChart[] = array("Min" => 151,  "Max" => 280,   "Samples" => 32);
				$sAqlChart[] = array("Min" => 281,  "Max" => 500,   "Samples" => 50);
				$sAqlChart[] = array("Min" => 501,  "Max" => 1200,  "Samples" => 80);
				$sAqlChart[] = array("Min" => 1201, "Max" => 3200,  "Samples" => 125);
				$sAqlChart[] = array("Min" => 3201, "Max" => 10000, "Samples" => 200);
			}
		}

		else if ($iReport == 38)
		{
			if ($CheckLevel == 2)
			{
				$sAqlChart[] = array("Min" => 1,     "Max" => 15,    "Samples" => 0);
				$sAqlChart[] = array("Min" => 16,    "Max" => 25,    "Samples" => 6);
				$sAqlChart[] = array("Min" => 26,    "Max" => 50,    "Samples" => 10);
				$sAqlChart[] = array("Min" => 51,    "Max" => 90,    "Samples" => 16);
				$sAqlChart[] = array("Min" => 91,    "Max" => 150,   "Samples" => 26);
				$sAqlChart[] = array("Min" => 151,   "Max" => 280,   "Samples" => 40);
				$sAqlChart[] = array("Min" => 281,   "Max" => 500,   "Samples" => 64);
				$sAqlChart[] = array("Min" => 501,   "Max" => 1200,  "Samples" => 100);
				$sAqlChart[] = array("Min" => 1201,  "Max" => 3200,  "Samples" => 160);
				$sAqlChart[] = array("Min" => 3201,  "Max" => 10000, "Samples" => 250);
				$sAqlChart[] = array("Min" => 10001, "Max" => 35000, "Samples" => 400);
			}

			else
			{
				$sAqlChart[] = array("Min" => 1,     "Max" => 15,    "Samples" => 0);
				$sAqlChart[] = array("Min" => 16,    "Max" => 25,    "Samples" => 3);
				$sAqlChart[] = array("Min" => 26,    "Max" => 50,    "Samples" => 5);
				$sAqlChart[] = array("Min" => 51,    "Max" => 90,    "Samples" => 8);
				$sAqlChart[] = array("Min" => 91,    "Max" => 150,   "Samples" => 13);
				$sAqlChart[] = array("Min" => 151,   "Max" => 280,   "Samples" => 20);
				$sAqlChart[] = array("Min" => 281,   "Max" => 500,   "Samples" => 32);
				$sAqlChart[] = array("Min" => 501,   "Max" => 1200,  "Samples" => 50);
				$sAqlChart[] = array("Min" => 1201,  "Max" => 3200,  "Samples" => 80);
				$sAqlChart[] = array("Min" => 3201,  "Max" => 10000, "Samples" => 125);
				$sAqlChart[] = array("Min" => 10001, "Max" => 35000, "Samples" => 200);
			}
		}

		else
		{
			$sAqlChart[] = array("Min" => 1,      "Max" => 1,       "Samples" => 1);
			$sAqlChart[] = array("Min" => 2,      "Max" => 8,       "Samples" => 2);
			$sAqlChart[] = array("Min" => 9,      "Max" => 15,      "Samples" => 3);
			$sAqlChart[] = array("Min" => 16,     "Max" => 25,      "Samples" => 5);
			$sAqlChart[] = array("Min" => 26,     "Max" => 50,      "Samples" => 8);
			$sAqlChart[] = array("Min" => 51,     "Max" => 90,      "Samples" => 13);
			$sAqlChart[] = array("Min" => 91,     "Max" => 150,     "Samples" => 20);
			$sAqlChart[] = array("Min" => 151,    "Max" => 280,     "Samples" => 32);
			$sAqlChart[] = array("Min" => 281,    "Max" => 500,     "Samples" => 50);
			$sAqlChart[] = array("Min" => 501,    "Max" => 1200,    "Samples" => 80);
			$sAqlChart[] = array("Min" => 1201,   "Max" => 3200,    "Samples" => 125);
			$sAqlChart[] = array("Min" => 3201,   "Max" => 10000,   "Samples" => 200);
			$sAqlChart[] = array("Min" => 10001,  "Max" => 35000,   "Samples" => 315);
			$sAqlChart[] = array("Min" => 35001,  "Max" => 150000,  "Samples" => 500);
			$sAqlChart[] = array("Min" => 150001, "Max" => 500000,  "Samples" => 800);
			$sAqlChart[] = array("Min" => 500001, "Max" => 9999999, "Samples" => 1250);
		}


		foreach ($sAqlChart as $iChartRow)
		{
			if ($iQuantity >= $iChartRow['Min'] && $iQuantity <= $iChartRow['Max'])
			{
				$iSampleSize = (($iChartRow['Samples'] == 0) ? $iQuantity : $iChartRow['Samples']);

				break;
			}
		}


		return $iSampleSize;
	}



	function getAqlDefects($iSampleSize, $fAql, $iReport = 0, $iInspectionLevel = 2)
	{
		$iMajor = 0;
		$iMinor = 0;


		if ($iReport == 28)
		{
			$sAqlChart        = array( );
			$sAqlChart["0"]   = array("2.5" => array(0, 0));
			$sAqlChart["20"]  = array("2.5" => array(1, 2));
			$sAqlChart["32"]  = array("2.5" => array(2, 3));
			$sAqlChart["50"]  = array("2.5" => array(3, 5));
			$sAqlChart["80"]  = array("2.5" => array(5, 7));
			$sAqlChart["125"] = array("2.5" => array(7, 10));
			$sAqlChart["200"] = array("2.5" => array(10, 14));


			if (@isset($sAqlChart["{$iSampleSize}"]["{$fAql}"]))
				@list($iMajor, $iMinor) = $sAqlChart["{$iSampleSize}"]["{$fAql}"];
		}

		else if ($iReport == 37)
		{
			$sAqlChart = array( );

			if ($iInspectionLevel == 1)
			{
				$sAqlChart["2"]  = array("2.5" => array(0, 0));
				$sAqlChart["3"]  = array("2.5" => array(0, 0));
				$sAqlChart["5"]  = array("2.5" => array(0, 0));
				$sAqlChart["8"]  = array("2.5" => array(0, 1));
				$sAqlChart["13"] = array("2.5" => array(1, 2));
			}

			else
			{
				$sAqlChart["0"]   = array("2.5" => array(0, 0));
				$sAqlChart["20"]  = array("2.5" => array(1, 2));
				$sAqlChart["32"]  = array("2.5" => array(2, 3));
				$sAqlChart["50"]  = array("2.5" => array(3, 5));
				$sAqlChart["80"]  = array("2.5" => array(5, 7));
				$sAqlChart["125"] = array("2.5" => array(7, 10));
				$sAqlChart["200"] = array("2.5" => array(10, 14));
			}

			if (@isset($sAqlChart["{$iSampleSize}"]["{$fAql}"]))
				@list($iMajor, $iMinor) = $sAqlChart["{$iSampleSize}"]["{$fAql}"];
		}

		else
		{
			$sAqlChart         = array( );
			$sAqlChart["2"]    = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 0, "4" => 0, "F" => 2, "T" => 8);
			$sAqlChart["3"]    = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 0, "4" => 0, "F" => 9, "T" => 15);
			$sAqlChart["5"]    = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 0, "4" => 0, "F" => 16, "T" => 25);
			$sAqlChart["8"]    = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 0, "4" => 0, "F" => 26, "T" => 50);
			$sAqlChart["13"]   = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 0, "4" => 1, "F" => 51, "T" => 90);
			$sAqlChart["20"]   = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 1, "4" => 2, "F" => 91, "T" => 150);
			$sAqlChart["32"]   = array("0.65" => 0, "1" => 0, "1.5" => 1, "2.5" => 2, "4" => 3, "F" => 151, "T" => 280);
			$sAqlChart["50"]   = array("0.65" => 0, "1" => 1, "1.5" => 2, "2.5" => 3, "4" => 5, "F" => 281, "T" => 500);
			$sAqlChart["80"]   = array("0.65" => 1, "1" => 2, "1.5" => 3, "2.5" => 5, "4" => 7, "F" => 501, "T" => 1200);
			$sAqlChart["125"]  = array("0.65" => 2, "1" => 3, "1.5" => 5, "2.5" => 7, "4" => 10, "F" => 1201, "T" => 3200);
			$sAqlChart["200"]  = array("0.65" => 3, "1" => 5, "1.5" => 7, "2.5" => 10, "4" => 14, "F" => 3201, "T" => 10000);
			$sAqlChart["315"]  = array("0.65" => 5, "1" => 7, "1.5" => 10, "2.5" => 14, "4" => 21, "F" => 10001, "T" => 35000);
			$sAqlChart["500"]  = array("0.65" => 7, "1" => 10, "1.5" => 14, "2.5" => 21, "4" => 21, "F" => 35001, "T" => 150000);
			$sAqlChart["800"]  = array("0.65" => 10, "1" => 14, "1.5" => 21, "2.5" => 21, "4" => 21, "F" => 150001, "T" => 500000);
			$sAqlChart["1250"] = array("0.65" => 14, "1" => 21, "1.5" => 21, "2.5" => 21, "4" => 21, "F" => 500001, "T" => 50000000);

			if (@isset($sAqlChart["{$iSampleSize}"]["{$fAql}"]))
				$iMajor = $sAqlChart["{$iSampleSize}"]["{$fAql}"];
		}


		return array($iMajor, $iMinor);
	}
?>