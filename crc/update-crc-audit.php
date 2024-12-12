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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
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

	$Id             = IO::intValue('Id');
	$Language       = IO::intValue("Language"); 
	$Shift1         = IO::strValue("Shift1"); 
	$Shift2         = IO::strValue("Shift2"); 
	$Shift3         = IO::strValue("Shift3");  
	$Department     = IO::intValue("Department");        
	$PermMen        = IO::intValue("PermMen"); 
	$PermWomen      = IO::intValue("PermWomen"); 
	$PermYoung      = IO::intValue("PermYoung"); 
	$TempMen        = IO::intValue("TempMen"); 
	$TempWomen      = IO::intValue("TempWomen"); 
	$TempYoung      = IO::intValue("TempYoung");
	$MgtRep         = IO::strValue("MgtRep");
	$EndDate        = (IO::strValue("EndDate") == ""?"0000-00-00":IO::strValue("EndDate"));
	$MgtRepEmail    = IO::strValue("MgtRepEmail"); 
	$PeakSeason     = IO::strValue("PeakSeason");
	$SameCompound   = IO::strValue("SameCompound");
	$Observations   = IO::strValue("Observations");
        $NewAuditDate   = IO::strValue("AuditDate");
	$Step           = IO::intValue('Step');
        $SectionId      = IO::intValue("SectionId");
	$CategoryId     = IO::intValue("CategoryId");
	$bFlag          = true;


	$_SESSION['Flag'] = "";

	$objDb->execute("BEGIN");

        if($Step == 1)
        {
            
            $sPictureSql = "";
            if (!empty($_FILES))
            {
                $AuditDate      = getDbValue("audit_date", "tbl_crc_audits", "id='$Id'");
                $PrevPics       = getDbValue("picture", "tbl_crc_audits", "id='$Id'");
                $PrevPicsList   = getList("tbl_crc_audit_pictures", "id", "picture", "audit_id='$Id'");
                
                if($NewAuditDate != $AuditDate)
                {
                    @list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);
                    @list($sNewYear, $sNewMonth, $sNewDay) = @explode("-", $NewAuditDate);
                    
                    @mkdir(($sBaseDir.TNC_PICS_DIR.$sNewYear), 0777);
                    @mkdir(($sBaseDir.TNC_PICS_DIR.$sNewYear."/".$sNewMonth), 0777);
                    @mkdir(($sBaseDir.TNC_PICS_DIR.$sNewYear."/".$sNewMonth."/".$sNewDay), 0777);
                
                
                    $iPrevPics = explode(",", $PrevPics);
                    
                    foreach($iPrevPics as $sPicture)
                        rename(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sPicture), ($sBaseDir.TNC_PICS_DIR.$sNewYear."/".$sNewMonth."/".$sNewDay."/".$sPicture));

                    foreach($PrevPicsList as $iPicture => $sPicture)
                        rename(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sPicture), ($sBaseDir.TNC_PICS_DIR.$sNewYear."/".$sNewMonth."/".$sNewDay."/".$sPicture));
                    
                    $AuditDate = $NewAuditDate;
                }

                @list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);
                
                @mkdir(($sBaseDir.TNC_PICS_DIR.$sYear), 0777);
                @mkdir(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth), 0777);
                @mkdir(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);
                    
                $sTncDir  = ($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");

                $sPictures = array();
                foreach($_FILES["AuditPicture"]['name'] as $key => $sPicture)
                {                    
                    $sPicture   = "{$Id}-{$sPicture}";

                    if (@move_uploaded_file($_FILES["AuditPicture"]['tmp_name'][$key], ($sTncDir.$sPicture)))
                        $sPictures["{$sPicture}"] = $sPicture;
                }

                if(!empty($sPictures))
                {
                    $Pictures     = implode(",", $sPictures);
                    
                    if($PrevPics != "")
                        $Pictures .= (",".$PrevPics);
                            
                    $sPictureSql .= " picture = '".$Pictures."', "; 
                    $bFlag        = true;
                }
            }
            
            $sSQL  = "UPDATE tbl_crc_audits SET     department          = '$Department',
                                                    language            = '$Language',
                                                    mgt_representative  = '$MgtRep',
                                                    audit_date          = '$NewAuditDate',
                                                    mgt_rep_email       = '$MgtRepEmail',
                                                    audit_end_date      = '$EndDate',
                                                    shift1              = '$Shift1',
                                                    shift2              = '$Shift2',
                                                    shift3              = '$Shift3',
                                                    perm_male           = '$PermMen',
                                                    perm_female         = '$PermWomen',
                                                    perm_young          = '$PermYoung',
                                                    temp_male           = '$TempMen',
                                                    temp_female         = '$TempWomen',
                                                    temp_young          = '$TempYoung',
                                                    same_compound       = '$SameCompound',
                                                    peak_season         = '$PeakSeason',
                                                    observations        = '$Observations',   
                                                        $sPictureSql
                                                    modified_at         = NOW( ),
                                                    modified_by         = '{$_SESSION['UserId']}'
                        where id = '$Id'";
                                                    
            $bFlag = $objDb->execute($sSQL);            
        }
		
        else
        {
            $Points         = IO::getArray("Point");

            $AuditDate      = getDbValue("audit_date", "tbl_crc_audits", "id='$Id'");
            
            @list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);

            @mkdir(($sBaseDir.TNC_PICS_DIR.$sYear), 0777);
            @mkdir(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth), 0777);
            @mkdir(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);

            $sTncDir  = ($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");

            foreach ($Points as $Point)
            {
                    $Score   = IO::intValue("Score{$Point}");
                    $Remarks = IO::strValue("Remarks{$Point}");
					
					
					if (getDbValue("COUNT(1)", "tbl_crc_audit_details", "audit_id='$Id' AND point_id='$Point'") == 0)
					{
						$iObservation = getNextId("tbl_crc_audit_details");
						
						$sSQL  = "INSERT INTO tbl_crc_audit_details SET id                = '$iObservation',
										                                audit_id          = '$Id',
																		point_id          = '$Point',
						                                                score             = '$Score',
																        remarks           = '$Remarks',
																		corrective_action = '',
																		review_score      = '$Score'";
						
						$bFlag = $objDb->execute($sSQL);
					}

					else
					{
						$sSQL  = "UPDATE tbl_crc_audit_details SET score   = '$Score',
																   remarks = '$Remarks'
								  WHERE audit_id='$Id' AND point_id='$Point'";                    
						$bFlag = $objDb->execute($sSQL);
					}

                   
                    if($bFlag == true && !empty($_FILES["files".$Point]['name']))
                    {

                        foreach($_FILES["files".$Point]['name'] as $iFile => $sFileName)
                        {        
                            if ($sFileName != "")
                            {
                                    $time = strtotime(date('Y-m-d h:i:s'));
                                   
                                    $sPicture   = "{$Id}-{$Point}-{$sFileName}";

                                    if (@move_uploaded_file($_FILES["files".$Point]['tmp_name'][$iFile], ($sTncDir.$sPicture)))
                                    {

                                            $iPicture = getNextId("tbl_crc_audit_pictures");

                                           $sSQL = "INSERT INTO tbl_crc_audit_pictures (id, audit_id, point_id, title, picture)
                                                                                VALUES ('$iPicture', '$Id', '$Point', '$sFileName', '$sPicture')";

                                            $bFlag = $objDb->execute($sSQL);
                                    }                                
                            }

                        }
                    }

                    if ($bFlag == false)
                            break;
            }

            if ($bFlag == true)
            {
                    $sSQL  = "UPDATE tbl_crc_audits SET total_score=(SELECT COUNT(1) FROM tbl_crc_audit_details WHERE audit_id='$Id' AND score>='0') WHERE id='$Id'";
                    $bFlag = $objDb->execute($sSQL);
            }

            if ($bFlag == true)
            {
                    $sSQL  = "UPDATE tbl_crc_audits SET score=(SELECT COUNT(1) FROM tbl_crc_audit_details WHERE audit_id='$Id' AND score='1') WHERE id='$Id'";
                    $bFlag = $objDb->execute($sSQL);
            }
        }
		
		
	if ($_SESSION['Flag'] == "")
	{
		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			redirect("edit-crc-audit.php?Id={$Id}&Step=1", "CRC_AUDIT_UPDATED");
        	//	redirect("crc-audits.php", "CRC_AUDIT_UPDATED");
		}

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

			$objDb->execute("ROLLBACK");
		}
	}

	header("Location: edit-crc-audit.php?Id={$Id}&SectionId={$SectionId}&$CategoryId={$CategoryId}");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>