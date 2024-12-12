<?
	@require_once("requires/session.php");

	@ini_set('max_execution_time', 0);
	@set_time_limit(0);
	
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	

	print ("START: ".date("h:i A")."<hr />");
	

	$iReport        = 23;
        $DefectCount    = 0;
        $MiscCount      = 0;
        $PackingCount   = 0;
        $sRemaningDefectImages = array();
        $sDangling      = array();
        
        $bFlag   = $objDb->execute("BEGIN", false);

        $sSQL2 = "SELECT id, audit_date, audit_code FROM tbl_qa_reports WHERE report_id='$iReport' AND id='341946'";
        $objDb2->query($sSQL2);
        
        $iCount     = $objDb2->getCount( );

        for ($i = 0; $i < $iCount; $i ++)
        {
                $iAudit     = $objDb2->getField($i, "id");
                $sAuditCode = $objDb2->getField($i, "audit_code");
                $sAuditDate = $objDb2->getField($i, "audit_date");
            
            	// All Pictures
                @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

                $sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*");
                $sPictures = @array_map("strtoupper", $sPictures);
                $sPictures = @array_unique($sPictures);
                $sTemp     = array( );
               
                foreach ($sPictures as $sPicture)
                        $sTemp[] = @basename($sPicture);

                $sPictures = $sTemp;
                $sQuondaDir = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");

                $sDefectImages  = array();
                $sPackingImages = array();
                $sMiscImages    = array();
                $sExtraImages   = array();
                
                foreach ($sPictures as $sPicture)
                {
                        if (@strpos($sPicture, "_PACK") !== FALSE || @strpos($sPicture, "_001_") !== FALSE)
                                $sPackingImages[] = $sPicture;

                        else if (@strpos($sPicture, "_MISC") !== FALSE)
                                $sMiscImages[] = $sPicture;

                        else
                                $sDefectImages[] = $sPicture;
                }

                $iExistingImages    = array();
                $iExistingDefectIds = array();
                $sExistingImages    = getList("tbl_qa_report_defects", "id", "picture", "audit_id='$iAudit' AND (picture IS NOT NULL OR picture != '')");         
               
                foreach($sExistingImages as $iDefect => $sImage)
                {
                    if(@file_exists($sQuondaDir.$sImage))
                    {
                        $iExistingImages[]      = strtoupper($sImage);
                        $iExistingDefectIds[]   = $iDefect;
                    }
                }
                $sDefectImages      = array_diff($sDefectImages, $iExistingImages);
                $sExistingDefectIds = implode(",", $iExistingDefectIds);
                
                foreach($sDefectImages as $sPicture)
                {
                    $iDefectImageParts  = explode("_", $sPicture);
                    $iDefectCode        = @$iDefectImageParts[1];
                    $iDefectArea        = @$iDefectImageParts[2];
                    $iDefectCodeId      = getDbValue("id", "tbl_defect_codes", "report_id='$iReport' AND code='$iDefectCode'");
                    
                    $sSubQuery = "";
                    if($sExistingDefectIds != "")
                        $sSubQuery = " AND id NOT IN ($sExistingDefectIds)";
                    
                    $iDefectId = getDbValue("id", "tbl_qa_report_defects", "(picture IS NULL OR picture = '') AND audit_id='$iAudit' AND code_id='$iDefectCodeId' AND area_id='$iDefectArea' {$sSubQuery}");

                    if($iDefectId > 0)
                    {                        
                        $sSQL  = "UPDATE tbl_qa_report_defects SET picture='$sPicture' WHERE id='$iDefectId'";
                        $bFlag = $objDb->execute($sSQL);

                        if($bFlag == false)
                            break;

                        $DefectCount ++;
                    }
                    else
                    {
                        $sExtraImages[] = $sPicture;
                        //$sRemaningDefectImages[$iAudit][] = $sPicture;
                    }
                }
                
                if($bFlag == true)
                {
                    foreach($sPackingImages as $sPicture)
                    {
                        if(getDbValue("count(1)", "tbl_qa_report_images", "audit_id='$iAudit' AND image LIKE '$sPicture' AND `type`='P'") == 0)
                        {
                            $ImageId= getNextId("tbl_qa_report_images");
                            $sSQL   = "INSERT INTO tbl_qa_report_images SET id='$ImageId', audit_id='$iAudit', image='$sPicture', `type`='P'";
                            $bFlag  = $objDb->execute($sSQL);     
                            
                            if($bFlag == false)
                                break;
                            
                            $PackingCount++;
                        }                        
                    }
                }
                
                if($bFlag == true)
                {
                    foreach($sMiscImages as $sPicture)
                    {
                        if(getDbValue("count(1)", "tbl_qa_report_images", "audit_id='$iAudit' AND image LIKE '$sPicture' AND `type`='M'") == 0)
                        {
                            $ImageId= getNextId("tbl_qa_report_images");
                            $sSQL   = "INSERT INTO tbl_qa_report_images SET id='$ImageId', audit_id='$iAudit', image='$sPicture', `type`='M'";
                            $bFlag  = $objDb->execute($sSQL);                        
                            
                            if($bFlag == false)
                                break;
                            
                            $MiscCount ++;
                        }                        
                    }
                
                }
                
                if($bFlag == true)
                {
                    foreach ($sExtraImages as $sPicture)
                    {
                        $iDefectId          = 0;
                        $iDefectImageParts  = explode("_", $sPicture);
                        $iDefectCode        = @$iDefectImageParts[1];
                        $iDefectArea        = @$iDefectImageParts[2];
                        $iDefectCodeId      = getDbValue("id", "tbl_defect_codes", "report_id='$iReport' AND code='$iDefectCode'");
                        
                        if($iDefectArea != "" && is_numeric($iDefectArea))
                            $iDefectId = getDbValue("id", "tbl_qa_report_defects", "(picture IS NULL OR picture = '') AND audit_id='$iAudit' AND code_id='$iDefectCodeId' AND ( area_id='$iDefectArea' OR area_id='0' )");
                        
                        if($iDefectId > 0)
                        {                        
                            $sSQL  = "UPDATE tbl_qa_report_defects SET picture='$sPicture' WHERE id='$iDefectId'";
                            $bFlag = $objDb->execute($sSQL);

                            if($bFlag == false)
                                break;

                            $DefectCount ++;
                        }
                        else
                        {
                            @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
                            
                            $ImageId    = getNextId("tbl_qa_report_images");
                            $NewName    = ("{$sAuditCode}_MISC_ImgNo_".$ImageId.'.JPG');
                            
                            if (rename(($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sPicture), ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$NewName)))
                            {
                                $sSQL   = "INSERT INTO tbl_qa_report_images SET id='$ImageId', audit_id='$iAudit', image='$NewName', `type`='M'";
                                $bFlag  = $objDb->execute($sSQL);                        

                                if($bFlag == false)
                                    break;

                                $MiscCount ++;
                            }
                            else
                                $sDangling[] = $sPicture;
                        }                        
                    }                            
                }
        }
  
        if ($bFlag == true)
        {
                $objDb->execute("COMMIT", false);

                print "Data Imported Successfully <br/>";
                print "Defect Images Uploaded: ".$DefectCount." <br/>";
                print "Packing Images Uploaded: ".$PackingCount." <br/>";
                print "Misc Images Uploaded: ".$MiscCount." <br/>";
                //print "Extra Images : <br/><pre>";
                //print_r($sRemaningDefectImages);
                print "Dangling Images : <br/>";
                print_r($sDangling);
        }

        else
        {
                print $sSQL."<br><br>".mysql_error( );

                $objDb->execute("ROLLBACK", false);
        }
	
	
	print ("<hr />END: ".date("h:i A")."<br />");
	
	
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>