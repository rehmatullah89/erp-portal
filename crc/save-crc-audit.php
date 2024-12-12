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
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2       = new Database( );


	$Vendor         = IO::intValue("Vendor");
	$ScheduleId     = IO::intValue("ScheduleId");
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
        $SameCompound   = IO::strValue("SameCompound");
        $PeakSeason     = IO::strValue("PeakSeason");
        $Observations   = IO::strValue("Observations");
        $sPoints        = getDbValue("points", "tbl_crc_audits", "id='$ScheduleId'");

	$sSQL = "SELECT * FROM tbl_crc_audits WHERE vendor_id='$Vendor' AND id='$ScheduleId' AND total_score = '0'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$objDb->execute("BEGIN");

                $sSQL  = "INSERT INTO tbl_crc_audit_details (audit_id, point_id, score, remarks) (SELECT '$ScheduleId', id, '-1', '' FROM tbl_tnc_points where id IN ($sPoints))";
                $bFlag = $objDb->execute($sSQL);
	        
                $sPictureSql = "";
                if ($bFlag == true && !empty($_FILES))
                {                    
                    $AuditDate      = getDbValue("audit_date", "tbl_crc_audits", "id='$ScheduleId'");
            
                    @list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);

                    @mkdir(($sBaseDir.TNC_PICS_DIR.$sYear), 0777);
                    @mkdir(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth), 0777);
                    @mkdir(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);

                    $sTncDir  = ($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
                    
                    $sPictures = array();
                    foreach($_FILES["AuditPicture"]['name'] as $key => $sPicture)
                    {                    
                        $sPicture   = "{$ScheduleId}-{$sPicture}";

                        if (@move_uploaded_file($_FILES["AuditPicture"]['tmp_name'][$key], ($sTncDir.$sPicture)))
                            $sPictures["{$sPicture}"] = $sPicture;
                    }
                    
                    if(!empty($sPictures))
                    {
                        $sPictureSql .= " picture = '". implode(",", $sPictures)."', "; 
                        $bFlag        = true;
                    }
                }
                
                if ($bFlag == true)
		{
			$sSQL  = "UPDATE tbl_crc_audits SET total_score         = '1',
                                                            department          = '$Department',
                                                            language            = '$Language',
                                                            mgt_representative  = '$MgtRep',
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
                                                where id = '$ScheduleId'";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			redirect("edit-crc-audit.php?Id={$ScheduleId}&Step=1", "CRC_AUDIT_ADDED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION['Flag'] = "DB_ERROR";
		}
	}

	else
		$_SESSION['Flag'] = "CRC_AUDIT_EXISTS";


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>