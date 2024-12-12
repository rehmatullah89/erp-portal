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

	function createThumb($sSrcFile, $sDestFile, $iThumbSize)
	{
		@list($iWidth, $iHeight, $sType, $sAttributes) = @getimagesize($sSrcFile);

		if($iWidth > $iHeight)
		{
			$x      = @ceil(($iWidth - $iHeight) / 2);
			$iWidth = $iHeight;
		}

		else if($iHeight > $iWidth)
		{
			$y       = @ceil(($iHeight - $iWidth) / 2);
			$iHeight = $iWidth;
		}

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

		$sThumb = @imagecreatetruecolor($iThumbSize, $iThumbSize);

		@imagecopyresampled($sThumb, $sSource, 0, 0, $x, $y, $iThumbSize, $iThumbSize, $iWidth, $iHeight);
		@imagejpeg($sThumb, $sDestFile, 100);
	}

	function resizeImage($sImage, $iMaxWidth)
	{
		@list($iWidth, $iHeight, $sType, $sAttributes) = @getimagesize($sImage);

		$fRatio = ($iMaxWidth / $iWidth);

		$iNewWidth  = $iMaxWidth;
		$iNewHeight = @ceil($fRatio * $iHeight);

		return array($iNewWidth, $iNewHeight);
	}

	function makeImage($sSrcFile, $sDestFile, $iDestWidth, $iDestHeight)
	{
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

		@list($iSrcWidth, $iSrcHeight) = @getimagesize($sSrcFile);

		$sImage = @imagecreatetruecolor($iDestWidth, $iDestHeight);

		@imagecopyresized($sImage, $sSource, 0, 0, 0, 0, $iDestWidth, $iDestHeight, $iSrcWidth, $iSrcHeight);
		@imagejpeg($sImage, $sDestFile, 100);

		@imagedestroy($sImage);
		@imagedestroy($sSource);
	}

	function createFixedSizeImage($sSrcFile, $sDestFile, $iImgWidth, $iImgHeight, $sColor="221,221,221")
	{
		$iNewWidth  = $iImgWidth;
		$iNewHeight = $iImgHeight;

		@list($iWidth, $iHeight, $sType, $sAttributes) = @getimagesize($sSrcFile);

		$fRatio = ($iWidth / $iHeight);

		if (($iNewWidth / $iNewHeight) > $fRatio)
		   $iNewWidth = ($iNewHeight * $fRatio);

		else
		   $iNewHeight = ($iNewWidth / $fRatio);

		$iLeft = 0;
		$iTop  = 0;

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

		$sThumb = @imagecreatetruecolor($iImgWidth, $iImgHeight);

		$sColor   = @explode(",", $sColor);
		$sBgColor = @imagecolorallocate($sThumb, $sColor[0], $sColor[1], $sColor[2]);

		@imagefill($sThumb, 0, 0, $sBgColor);
		@imagecopy($sThumb, $sTemp, $iLeft, $iTop, 0, 0, $iNewWidth, $iNewHeight);
		@imagejpeg($sThumb, $sDestFile, 100);

		@imagedestroy($sTemp);
		@imagedestroy($sThumb);
	}

	function createCenteredImage($sSrcFile, $sDestFile, $iImgWidth, $iImgHeight, $iCenterX = 0, $iCenterY = 0, $sColor = "221,221,221")
	{
		@list($iWidth, $iHeight, $sType, $sAttributes) = @getimagesize($sSrcFile);

		$iDestLeft = 0;
		$iDestTop  = 0;

		if ($iCenterX == 0 && $iCenterY == 0)
		{
			$iSrcLeft = 0;
			$iSrcTop  = 0;

			$iDestLeft = (($iImgWidth - $iWidth) / 2);
			$iDestTop  = (($iImgHeight - $iHeight) / 2);
		}

		else
		{
			$iSrcLeft = round($iCenterX - ($iImgWidth / 2));
			$iSrcTop  = round($iCenterY - ($iImgHeight / 2));

			if ($iSrcLeft < 0)
				$iSrcLeft = 0;

			if ($iSrcTop < 0)
				$iSrcTop = 0;
		}

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

		$sThumb = @imagecreatetruecolor($iImgWidth, $iImgHeight);

		$sColor   = @explode(",", $sColor);
		$sBgColor = @imagecolorallocate($sThumb, $sColor[0], $sColor[1], $sColor[2]);

		@imagefill($sThumb, 0, 0, $sBgColor);
		@imagecopy($sThumb, $sSource, $iDestLeft, $iDestTop, $iSrcLeft, $iSrcTop, $iWidth, $iHeight);
		@imagejpeg($sThumb, $sDestFile, 100);

		@imagedestroy($sTemp);
		@imagedestroy($sThumb);
	}


	// Thumbs for Slideshow Gallery
	function createSlideshowThumb($sSrcFile, $sDestFile)
	{
		$iNewWidth  = 96;
		$iNewHeight = 72;

		@list($iWidth, $iHeight, $sType, $sAttributes) = @getimagesize($sSrcFile);

		$fRatio = ($iWidth / $iHeight);

		if ($iNewWidth / $iNewHeight > $fRatio)
		   $iNewWidth = ($iNewHeight * $fRatio);

		else
		   $iNewHeight = ($iNewWidth / $fRatio);

		$iLeft = 0;
		$iTop  = 0;

		if ($iNewWidth < $iThumbSize)
			$iLeft = @ceil(($iThumbSize - $iNewWidth) / 2);

		if ($iNewHeight < $iThumbSize)
			$iTop = @ceil(($iThumbSize - $iNewHeight) / 2);

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

		//header('Content-type: image/jpeg');

		$sTemp = @imagecreatetruecolor($iNewWidth, $iNewHeight);
		@imagecopyresampled($sTemp, $sSource, 0, 0, 0, 0, $iNewWidth, $iNewHeight, $iWidth, $iHeight);

		$sThumb   = @imagecreatetruecolor($iNewWidth, $iNewHeight);
		$sBgColor = @imagecolorallocate($sThumb, 255, 255, 255);

		@imagefill($sThumb, 0, 0, $sBgColor);
		@imagecopy($sThumb, $sTemp, $iLeft, $iTop, 0, 0, $iNewWidth, $iNewHeight);
		@imagejpeg($sThumb, $sDestFile, 100);
	}
?>