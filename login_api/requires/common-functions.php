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

	function formatDate($sDate, $sFormat = "d-M-Y")
	{
		if ($sDate == "" || $sDate == "0000-00-00" || $sDate == "1970-01-01" || $sDate == "0000-00-00 00:00:00" || $sDate == "1970-01-01 00:00:00")
			return "";

		else
			return date($sFormat, strtotime($sDate));
	}


	function formatTime($sTime, $sFormat = "h:i A")
	{
		if ($sTime == "" || $sTime == "00:00:00")
			return "";

		else
			return date($sFormat, strtotime($sTime));
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
?>