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

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$Id      = IO::intValue('Id');
	$Referer = urlencode(IO::strValue('Referer'));
	$Sms     = IO::intValue('Sms');
	$Step    = IO::intValue('Step');


	$sSQL = "SELECT audit_date, audit_code FROM tbl_qa_reports WHERE id='$Id'";
	$objDb->query($sSQL);

	$sAuditCode = $objDb->getField(0, "audit_code");
	$sAuditDate = $objDb->getField(0, "audit_date");


	@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

	@mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear), 0777);
	@mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth), 0777);
	@mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);

	$sQuondaDir = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");


	// Packing Images
	for ($i = 1; $i <= 5; $i ++)
	{
		if ($_FILES["Packing{$i}"]['name'] != "")
		{
			$sExtension  = substr($_FILES["Packing{$i}"]['name'], strrpos($_FILES["Packing{$i}"]['name'], "."));
			$sPackingPic = $_FILES["Packing{$i}"]['name'];

			if (@stripos($sPackingPic, "{$sAuditCode}_") !== FALSE || @stripos($sPackingPic, $sAuditCode) !== FALSE)
			{
				$sPackingPic = str_replace("{$sAuditCode}_", "", $sPackingPic);
				$sPackingPic = str_replace($sAuditCode, "", $sPackingPic);
			}

			$sPackingPic = ("{$sAuditCode}_PACK_".$sPackingPic);


			if (@move_uploaded_file($_FILES["Packing{$i}"]['tmp_name'], ($sBaseDir.TEMP_DIR.$sPackingPic)))
			{
				@list($iWidth, $iHeight) = @getimagesize($sBaseDir.TEMP_DIR.$sPackingPic);


				$bResize = false;

				if ($iWidth > $iHeight && $iWidth > 1200)
				{
					$bResize = true;
					$fRatio  = (1200 / $iWidth);

					$iWidth  = 1200;
					$iHeight = @ceil($fRatio * $iHeight);
				}

				else if ($iWidth < $iHeight && $iHeight > 1200)
				{
					$bResize = true;
					$fRatio  = (1200 / $iHeight);

					$iWidth  = @ceil($fRatio * $iWidth);
					$iHeight = 1200;
				}


				if ($bResize == true)
					makeImage(($sBaseDir.TEMP_DIR.$sPackingPic), ($sQuondaDir.$sPackingPic), $iWidth, $iHeight);

				else
					@copy(($sBaseDir.TEMP_DIR.$sPackingPic), ($sQuondaDir.$sPackingPic));


				@unlink($sBaseDir.TEMP_DIR.$sPackingPic);
			}
		}
	}


	redirect("edit-qa-report.php?Id={$Id}&Sms={$Sms}&Step=3&Referer={$Referer}", "PACKING_IMAGES_SAVED");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>