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


	$Id        = IO::intValue('Id');
	$Referer   = urlencode(IO::strValue('Referer'));
	$Sms       = IO::intValue('Sms');
	$Step      = IO::intValue('Step');
	$Published = IO::strValue("Published");


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
			$sExtension = substr($_FILES["Packing{$i}"]['name'], strrpos($_FILES["Packing{$i}"]['name'], "."));
			

			if(strtolower($sExtension) == '.jpg' || strtolower($sExtension) == '.jpeg')
			{
				$iImageId = getNextId("tbl_qa_report_images");
				$Picture  = "{$sAuditCode}_PACK_{$iImageId}{$sExtension}";

				if (@move_uploaded_file($_FILES["Packing{$i}"]['tmp_name'], ($sBaseDir.TEMP_DIR.$Picture)))
				{
					@list($iWidth, $iHeight) = @getimagesize($sBaseDir.TEMP_DIR.$Picture);

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


					if ($bResize == true && ($iWidth > 1200 || $iHeight > 1200))
						makeImage(($sBaseDir.TEMP_DIR.$Picture), ($sQuondaDir.$Picture), $iWidth, $iHeight);

					else
						@copy(($sBaseDir.TEMP_DIR.$Picture), ($sQuondaDir.$Picture));


					@unlink($sBaseDir.TEMP_DIR.$Picture);
					
					$sSQL  = "INSERT INTO tbl_qa_report_images SET id='$iImageId', audit_id='$Id', image='$Picture', `type`='P'";
					$bFlag = $objDb->execute($sSQL);
					
					
					if ($bFlag == false)
						break;
				}
			}
		}
	}


	redirect(("edit-qa-report.php?Id={$Id}&Sms={$Sms}&Step=3&Referer={$Referer}".(($Published == "N") ? ("&Options=".@md5($Id)) : "")), "PACKING_IMAGES_SAVED");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>