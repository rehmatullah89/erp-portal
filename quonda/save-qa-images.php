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
	@require_once("../requires/image-functions.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$AuditCode = IO::strValue('AuditCode');
	$AuditDate = IO::strValue('AuditDate');
	$Referer   = urlencode(IO::strValue('Referer'));
        
        $Id = getDbValue("id", "tbl_qa_reports", "audit_code='$AuditCode'");

	@list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);

	@mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear), 0777);
	@mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth), 0777);
	@mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);

	$sQuondaDir = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
	$sMessage   = "";

	for ($i = 1; $i <= 5; $i ++)
	{
		if ($_FILES["Image{$i}"]['name'] != "")
		{
                        $sType      = IO::strValue("Type{$i}");
			$Image      = IO::getFileName($_FILES["Image{$i}"]['name']);                        
                        $ext        = pathinfo($Image, PATHINFO_EXTENSION);
                        $ImageId    = getNextId("tbl_qa_report_images");
                        
                        if($sType == 'P')
                            $Image = ("{$AuditCode}_PACK_ImgNo_".$ImageId.'.'.$ext);
                        else
                            $Image = ("{$AuditCode}_MISC_ImgNo_".$ImageId.'.'.$ext);
                        
                        $sSQL  = "INSERT INTO tbl_qa_report_images SET id='$ImageId', audit_id='$Id', image='$Image', `type`='$sType'";
                        $bFlag = $objDb->execute($sSQL);
                        
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
                        else
                        {
                            $sSQL  = "DELETE FROM tbl_qa_report_images WHERE id='$ImageId'";
                            $bFlag = $objDb->execute($sSQL);
                        }
		}
	}


	$_SESSION['Message'] = $sMessage;

	header("Location: qa-report-images.php?AuditCode={$AuditCode}&Referer={$Referer}");
?>