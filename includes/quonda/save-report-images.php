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

	@require_once("../../requires/session.php");
		
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$Id         = IO::intValue('Id');
        $AuditDate  = IO::strValue('AuditDate');
        $sAuditCode = ("S".str_pad($Id, 4, 0, STR_PAD_LEFT));
        $SectionId  = IO::strValue('SectionId');
        $bFlag      =  true;
        $sBaseDir   = "../";
                
        $sSections  = array('L'=>'LAB', 'M'=>'MISC', 'P'=>'PACK', 'CW'=>'COLOR', 'PFV'=>'FRONT', 'PBV'=>'BACK');
                
        if(!empty($_FILES) && $SectionId != 'L')
        {            
                @list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);
                
                @mkdir(($sBaseDir.$sBaseDir.QUONDA_PICS_DIR.$sYear), 0777);
                @mkdir(($sBaseDir.$sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth), 0777);
                @mkdir(($sBaseDir.$sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);
        
                $sQuondaDir = ($sBaseDir.$sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
                
                foreach($_FILES["filePictures"]['name'] as $iFileIndex => $sFilePicture)
                {
                    if ($sFilePicture != "")
                    {
                            $sExtension = substr($sFilePicture, strrpos($sFilePicture, "."));

                            if(strtolower($sExtension) == '.jpg' || strtolower($sExtension) == '.jpeg')
                            {
                                    $iImageId = getNextId("tbl_qa_report_images");
                                    $Picture  = strtoupper("{$sAuditCode}_{$sSections[$SectionId]}_{$iImageId}{$sExtension}");

                                    if (@move_uploaded_file($_FILES["filePictures"]['tmp_name'][$iFileIndex], ($sBaseDir.$sBaseDir.TEMP_DIR.$Picture)))
                                    {
                                            @list($iWidth, $iHeight) = @getimagesize($sBaseDir.$sBaseDir.TEMP_DIR.$Picture);

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
                                                    makeImage(($sBaseDir.$sBaseDir.TEMP_DIR.$Picture), ($sQuondaDir.$Picture), $iWidth, $iHeight);

                                            else
                                                    @copy(($sBaseDir.$sBaseDir.TEMP_DIR.$Picture), ($sQuondaDir.$Picture));


                                            @unlink($sBaseDir.$sBaseDir.TEMP_DIR.$Picture);

                                            $sSQL  = "INSERT INTO tbl_qa_report_images SET id='$iImageId', audit_id='$Id', image='$Picture', `type`='$SectionId'";
                                            $bFlag = $objDb->execute($sSQL);


                                            if ($bFlag == false)
                                                    break;
                                    }
                            }
                    }
                }
        }
        else if(!empty($_FILES) && $SectionId == 'L')
        {
                @list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);
                
                @mkdir(($sBaseDir.$sBaseDir.SPECS_SHEETS_DIR.$sYear), 0777);
                @mkdir(($sBaseDir.$sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth), 0777);
                @mkdir(($sBaseDir.$sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);
        
                $sSpecSheetDir = ($sBaseDir.$sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/");
                
                foreach($_FILES["filePictures"]['name'] as $iFileIndex => $sFilePicture)
                {
                    if ($sFilePicture != "")
                    {
                            $sExtension = substr($sFilePicture, strrpos($sFilePicture, "."));

                            if(strtolower($sExtension) == '.jpg' || strtolower($sExtension) == '.jpeg')
                            {
                                    $iImageId = getNextId("tbl_qa_report_images");
                                    $Picture  = strtoupper("{$sAuditCode}_{$sSections[$SectionId]}_{$iImageId}{$sExtension}");

                                    if (@move_uploaded_file($_FILES["filePictures"]['tmp_name'][$iFileIndex], ($sBaseDir.$sBaseDir.TEMP_DIR.$Picture)))
                                    {
                                            @list($iWidth, $iHeight) = @getimagesize($sBaseDir.$sBaseDir.TEMP_DIR.$Picture);

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
                                                    makeImage(($sBaseDir.$sBaseDir.TEMP_DIR.$Picture), ($sSpecSheetDir.$Picture), $iWidth, $iHeight);

                                            else
                                                    @copy(($sBaseDir.$sBaseDir.TEMP_DIR.$Picture), ($sSpecSheetDir.$Picture));


                                            @unlink($sBaseDir.$sBaseDir.TEMP_DIR.$Picture);

                                            $sSQL  = "INSERT INTO tbl_qa_report_images SET id='$iImageId', audit_id='$Id', image='$Picture', `type`='$SectionId'";
                                            $bFlag = $objDb->execute($sSQL);


                                            if ($bFlag == false)
                                                    break;
                                    }
                            }
                    }
                }
        }

	
	if($bFlag == true)
	{
		$_SESSION['Flag'] = "SECTION_UPDATED";
		
		$objDb->execute("COMMIT");
                 
                header("Location: edit-report-images.php?AuditId={$Id}&AuditDate={$AuditDate}&Section={$SectionId}");
	}
	else
	{
		$_SESSION['Flag'] = "DB_ERROR";
		
		$objDb->execute("ROLLBACK");
		header("Location: edit-report-images.php?AuditId={$Id}&AuditDate={$AuditDate}&Section={$SectionId}");
	}

	
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>