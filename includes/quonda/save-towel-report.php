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

	$iTotalDefects = 0;

	$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$PoId', group_id='".IO::intValue('Group')."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_status='".IO::strValue("AuditStatus")."', audit_result='".IO::strValue("AuditResult")."', audit_type='".IO::strValue("AuditType")."', colors='".IO::strValue("Colors")."', maker='".IO::strValue("Maker")."', total_gmts='$TotalGmts', max_defects='$MaxDefects', final_audit_date='".((IO::strValue("FinalAuditDate") == "") ? "0000-00-00" : IO::strValue("FinalAuditDate"))."', inspection_type='".IO::strValue("InspecType")."', sizes='".@implode(",", IO::getArray('Sizes'))."', qa_comments='".IO::strValue("Comments")."', date_time=NOW( ) WHERE id='$Id'");
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$iCount     = IO::intValue("Count");
                $iTotalDefects = $iCount;
		$sDefectIds = "";

		for ($i = 0; $i < $iCount; $i ++)
		{
			$DefectId       = IO::intValue("DefectId".$i);
			$LotNo          = IO::intValue("LotNo".$i);
			$RollNo         = IO::intValue("RollNo".$i);
			$Width          = IO::intValue("Width".$i);
			$TicketMeters   = IO::floatValue("TicketMeters".$i);
			$ActualMeters   = IO::floatValue("ActualMeters".$i);
			$Holes          = IO::floatValue("Holes".$i);
			$Slubs          = IO::floatValue("Slubs".$i);
			$Stains         = IO::floatValue("Stains".$i);
			$Fly            = IO::floatValue("Fly".$i);
			$Other          = IO::floatValue("Other".$i);
                        $AllowedDefects = IO::intValue("AllowedDefects".$i);
                        $Picture        = IO::getFileName($_FILES["Picture".$i]['name']);

			if ($DefectId > 0)
				$sSQL  = "UPDATE tbl_towel_report_defects SET lot_no='$LotNo', roll_no='$RollNo', width='$Width', ticket_meters='$TicketMeters', actual_meters='$ActualMeters', holes='$Holes', slubs='$Slubs', stains='$Stains', fly='$Fly', other='$Other', allowable_defects='$AllowedDefects' WHERE id='$DefectId'";

			else
			{
                                $DefectId = getNextId("tbl_towel_report_defects");
				
				$sSQL  = ("INSERT INTO tbl_towel_report_defects (id, audit_id, lot_no, roll_no, width, ticket_meters, actual_meters, holes, slubs, stains, fly, other, allowable_defects) VALUES ('$DefectId', '$Id', '$LotNo', '$RollNo', '$Width', '$TicketMeters', '$ActualMeters', '$Holes', '$Slubs', '$Stains', '$Fly', '$Other', '$AllowedDefects')");
			}

			$bFlag = $objDb->execute($sSQL);
                        
                        if ($bFlag == true && $Picture != "")
                        {
                            $sSQL = "SELECT audit_date, audit_code FROM tbl_qa_reports WHERE id='$Id'";
                            $objDb->query($sSQL);

                            $sAuditCode = $objDb->getField(0, "audit_code");
                            $sAuditDate = $objDb->getField(0, "audit_date");

                            @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

                            @mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear), 0777);
                            @mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth), 0777);
                            @mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);

                            $sQuondaDir = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");

                            if ($_FILES["Picture{$i}"]['name'] != "" && $LotNo > 0 && $RollNo > 0)
                            {
                                    $sDefectCode = $LotNo;
                                    $sDefectArea = $RollNo;

                                    $sExtension  = substr($_FILES["Picture{$i}"]['name'], strrpos($_FILES["Picture{$i}"]['name'], "."));
                                    $sDefectPic  = ("{$sAuditCode}_{$sDefectCode}_"."{$sDefectArea}_".rand(1, 9999).$sExtension);

                                    if(strtolower($sExtension) == '.jpg' || strtolower($sExtension) == '.jpeg')
                                    {
                                        if (@file_exists($sQuondaDir.$sDefectPic))
                                                $sDefectPic = ("{$sAuditCode}_{$sDefectCode}_"."{$sDefectArea}_".rand(1, 9999).$sExtension);

                                        if (@move_uploaded_file($_FILES["Picture{$i}"]['tmp_name'], ($sBaseDir.TEMP_DIR.$sDefectPic)))
                                        {
                                                @list($iWidth, $iHeight) = @getimagesize($sBaseDir.TEMP_DIR.$sDefectPic);

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
                                                        makeImage(($sBaseDir.TEMP_DIR.$sDefectPic), ($sQuondaDir.$sDefectPic), $iWidth, $iHeight);

                                                else
                                                        @copy(($sBaseDir.TEMP_DIR.$sDefectPic), ($sQuondaDir.$sDefectPic));


                                                @unlink($sBaseDir.TEMP_DIR.$sDefectPic);

                                                $PrevImage = getDbValue("picture", "tbl_towel_report_defects", "id='$DefectId'");

                                                if(!empty($PrevImage) && @file_exists($sQuondaDir.$PrevImage))
                                                    @unlink($sQuondaDir.$PrevImage);

                                                $sSQL  = "UPDATE tbl_towel_report_defects SET picture='$sDefectPic' WHERE id='$DefectId'";
                                                $bFlag = $objDb->execute($sSQL);
                                        }                                               
                                    }
                            }
                        }

			if ($DefectId > 0)
			{
				if ($sDefectIds != "")
					$sDefectIds .= ",";

				$sDefectIds .= $DefectId;
			}

			if ($bFlag == false)
				break;
		}

		if ($bFlag == true && $sDefectIds != "")
		{
                        $sSQL = "SELECT audit_date, audit_code FROM tbl_qa_reports WHERE id='$Id'";
                        $objDb->query($sSQL);

                        $sAuditCode = $objDb->getField(0, "audit_code");
                        $sAuditDate = $objDb->getField(0, "audit_date");

                        @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

                        $sQuondaDir = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
                        $DefectPictures = getList("tbl_towel_report_defects", "id", "picture", "id NOT IN ($sDefectIds) AND audit_id='$Id' AND picture!=''");
                    
                        foreach ($DefectPictures as $iDefect => $sPicture)
                        {
                            if(!empty($sPicture) && @file_exists($sQuondaDir.$sPicture))
                                @unlink($sQuondaDir.$sPicture);
                        }
                        
			$sSQL  = "DELETE FROM tbl_towel_report_defects WHERE id NOT IN ($sDefectIds) AND audit_id='$Id'";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true && $iCount == 0)
		{
			$sSQL  = "DELETE FROM tbl_towel_report_defects WHERE audit_id='$Id'";
			$bFlag = $objDb->execute($sSQL);
		}
	}
       	$fDhu = round((($iTotalDefects / $TotalGmts) * 100), 2);
?>