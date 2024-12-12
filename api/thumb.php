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

	$sSrcFile   = strtolower($_REQUEST['url']);
	$iImgWidth  = $_REQUEST['width'];
	$iImgHeight = $_REQUEST['height'];


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

	switch($sExtension)
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
	{
		header("Content-ype: image/png");
		@imagepng($sThumb, null, 9);
	}

	else if ($sExtension == ".gif")
	{
		header("Content-ype: image/gif");
		@imagegif($sThumb);
	}

	else
	{
		header("Content-ype: image/jpeg");
		@imagejpeg($sThumb, null, 100);
	}


	@imagedestroy($sTemp);
	@imagedestroy($sThumb);
?>