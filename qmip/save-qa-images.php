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
	@require_once("../requires/image-functions.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$AuditCode = IO::strValue('AuditCode');
	$AuditDate = IO::strValue('AuditDate');
	$Referer   = urlencode(IO::strValue('Referer'));

	@list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);

	@mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear), 0777);
	@mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth), 0777);
	@mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);

	$sQuondaDir = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
	$sMessage   = "";

/*
	$sImages  = array( );

	for ($i = 1; $i <= 5; $i ++)
	{
		$Image = "";

		if ($_FILES['Image'.$i]['name'] != "")
		{
			$Image = IO::getFileName($_FILES['Image'.$i]['name']);
			$Image = @str_replace("-", "_", $Image);

			if (@move_uploaded_file($_FILES['Image'.$i]['tmp_name'], ($sBaseDir.TEMP_DIR.$Image)))
			{
				@list($iWidth, $iHeight) = @getimagesize($sBaseDir.TEMP_DIR.$Image);

				if ($iWidth > 800 || $iHeight > 800)
				{
					$sMessage .= "- $Image dimensions are greater than 800px<br />";

					@unlink($sBaseDir.TEMP_DIR.$Image);
				}

				else if (@filesize($sBaseDir.TEMP_DIR.$Image) > 128000)
				{
					$sMessage .= "- $Image size is greater than 125Kb<br />";

					@unlink($sBaseDir.TEMP_DIR.$Image);
				}

				else
					$sImages[] = $Image;
			}
		}
	}

	if (count($sImages) > 0)
	{
		for ($i = 0; $i < count($sImages); $i ++)
		{
			$sFile = $sImages[$i];

			@list($sAuditCode) = @explode("_", $sFile);

			if (strtoupper($sAuditCode) != strtoupper($AuditCode))
				$sFile = ($AuditCode."_".$sFile);

			if (@copy(($sBaseDir.TEMP_DIR.$sImages[$i]), ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sFile)))
				$sMessage .= "<span style='color:#000000;'>- {$sImages[$i]} uploaded successfully.</span><br />";

			else
				$sMessage .= "- $Image uploading failed.<br />";

			@unlink($sBaseDir.TEMP_DIR.$sImages[$i]);
		}
	}
*/

	for ($i = 1; $i <= 5; $i ++)
	{
		if ($_FILES["Image{$i}"]['name'] != "")
		{
			$Image = IO::getFileName($_FILES["Image{$i}"]['name']);
			$Image = @str_replace("-", "_", $Image);

			@list($sAuditCode) = @explode("_", $Image);

			if (strtoupper($sAuditCode) != strtoupper($AuditCode))
				$Image = ($AuditCode."_".$Image);


			if (@move_uploaded_file($_FILES["Image{$i}"]['tmp_name'], ($sBaseDir.TEMP_DIR.$Image)))
			{
				@list($iWidth, $iHeight) = @getimagesize($sBaseDir.TEMP_DIR.$Image);

				$bResize = false;

				if ($iWidth > $iHeight && $iWidth > 800)
				{
					$bResize = true;
					$fRatio  = (800 / $iWidth);

					$iWidth  = 800;
					$iHeight = @ceil($fRatio * $iHeight);
				}

				else if ($iWidth < $iHeight && $iHeight > 800)
				{
					$bResize = true;
					$fRatio  = (800 / $iHeight);

					$iWidth  = @ceil($fRatio * $iWidth);
					$iHeight = 800;
				}


				if ($bResize == true)
				{
					makeImage(($sBaseDir.TEMP_DIR.$Image), ($sQuondaDir.$Image), $iWidth, $iHeight);

					$sMessage .= "<span style='color:#000000;'>- {$Image} uploaded successfully.</span><br />";
				}

				else
				{
					if (@copy(($sBaseDir.TEMP_DIR.$Image), ($sQuondaDir.$Image)))
						$sMessage .= "<span style='color:#000000;'>- {$Image} uploaded successfully.</span><br />";

					else
						$sMessage .= "- $Image uploading failed.<br />";
				}

				@unlink($sBaseDir.TEMP_DIR.$Image);
			}
		}
	}


	$_SESSION['Message'] = $sMessage;

	header("Location: qa-report-images.php?AuditCode={$AuditCode}&Referer={$Referer}");
?>