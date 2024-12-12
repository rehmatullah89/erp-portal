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

	$iTotalPoints   = 0;
	$iTotalGivenQty = 0;
	$iTotalDefects  = 0;

	$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$PoId', group_id='".IO::intValue('Group')."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_result='".IO::strValue("AuditResult")."', sizes='".@implode(",", IO::getArray('Sizes'))."', dye_lot_no='".IO::strValue("DyeLotNo")."', acceptable_points_woven='".IO::strValue("AcceptablePointsWoven")."', inspection_type='".IO::strValue("InspectionType")."', cutable_fabric_width='".IO::strValue("CutableFabricWidth")."', stock_status='".IO::strValue("StockStatus")."', rolls_inspected='".IO::intValue("RollsInspected")."', no_of_rolls='".IO::intValue("Rolls")."', fabric_width='".IO::intValue("FabricWidth")."', ship_qty='".IO::intValue("ShipQty")."', re_screen_qty='".IO::intValue("ReScreenQty")."', qa_comments='".IO::strValue("Comments")."', date_time=NOW( ) WHERE id='$Id'");
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		for ($i = 0; $i < 5; $i ++)
		{
			$RollId   = IO::intValue("RollId_".$i);
			$RollNo   = IO::strValue("RollNo_".$i);
			$Ref_1    = IO::strValue("Ref_1_".$i);
			$Given_1  = IO::floatValue("Given_1_".$i);
			$Actual_1 = IO::floatValue("Actual_1_".$i);
			$Ref_2    = IO::strValue("Ref_2_".$i);
			$Given_2  = IO::floatValue("Given_2_".$i);
			$Actual_2 = IO::floatValue("Actual_2_".$i);
			$Ref_3    = IO::strValue("Ref_3_".$i);
			$Given_3  = IO::floatValue("Given_3_".$i);
			$Actual_3 = IO::floatValue("Actual_3_".$i);

			if ($RollNo != "")
			{
				$iTotalGivenQty += ($Given_1 + $Given_2 + $Given_3);

				if ($RollId > 0)
					$sSQL  = "UPDATE tbl_gf_rolls_info SET roll_no='$RollNo', ref_1='$Ref_1', given_1='$Given_1', actual_1='$Actual_1', ref_2='$Ref_2', given_2='$Given_2', actual_2='$Actual_2', ref_3='$Ref_3', given_3='$Given_3', actual_3='$Actual_3' WHERE id='$RollId'";

				else
				{
					$RollId = getNextId("tbl_gf_rolls_info");

					$sSQL  = ("INSERT INTO tbl_gf_rolls_info (id, audit_id, roll_no, ref_1, given_1, actual_1, ref_2, given_2, actual_2, ref_3, given_3, actual_3) VALUES ('$RollId', '$Id', '$RollNo', '$Ref_1', '$Given_1', '$Actual_1', '$Ref_2', '$Given_2', '$Actual_2', '$Ref_3', '$Given_3', '$Actual_3')");
				}

				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == false)
					break;
			}

			else
			{
				if ($RollId > 0)
				{
					$sSQL  = "DELETE FROM tbl_gf_rolls_info WHERE id='$RollId'";
					$bFlag = $objDb->execute($sSQL);
				}
			}

			if ($bFlag == false)
				break;
		}
	}

	if ($bFlag == true)
	{
		$iCount     = IO::intValue("Count");
		$sDefectIds = "";

		for ($i = 0; $i < $iCount; $i ++)
		{
			$DefectId = IO::intValue("DefectId".$i);
			$Roll     = IO::intValue("Roll".$i);
			$Panel    = IO::intValue("Panel".$i);
			$Code     = IO::intValue("Code".$i);
			$Grade    = IO::intValue("Grade".$i);
			$Defects  = IO::intValue("Defects".$i);
                        $Picture  = IO::getFileName($_FILES["Picture".$i]['name']);
                        
			if ($Defects > 0)
			{
				$iTotalPoints  += ($Grade * $Defects);
				$iTotalDefects += $Defects;


				if ($DefectId > 0)
					$sSQL  = "UPDATE tbl_gf_report_defects SET roll='$Roll', panel='$Panel', code_id='$Code', grade='$Grade', defects='$Defects', date_time=NOW() WHERE id='$DefectId'";

				else
				{
					$DefectId = getNextId("tbl_gf_report_defects");

					$sSQL  = ("INSERT INTO tbl_gf_report_defects (id, audit_id, roll, panel, code_id, grade, defects, date_time) VALUES ('$DefectId', '$Id', '$Roll', '$Panel', '$Code', '$Grade', '$Defects', NOW())");
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

                                    if ($_FILES["Picture{$i}"]['name'] != "" && $Code > 0)
                                    {
                                            $sDefectCode = getDbValue("code", "tbl_defect_codes", "id='$Code'");
                                            
                                            $sExtension  = substr($_FILES["Picture{$i}"]['name'], strrpos($_FILES["Picture{$i}"]['name'], "."));
                                            $sDefectPic  = ("{$sAuditCode}_{$sDefectCode}_"."{$Roll}_"."{$Panel}_".rand(1, 9999).$sExtension);

                                            if(strtolower($sExtension) == '.jpg' || strtolower($sExtension) == '.jpeg')
                                            {
                                                if (@file_exists($sQuondaDir.$sDefectPic))
                                                        $sDefectPic = ("{$sAuditCode}_{$sDefectCode}_"."{$Roll}_"."{$Panel}_".rand(1, 9999).$sExtension);

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
                                                
                                                        $PrevImage = getDbValue("picture", "tbl_gf_report_defects", "id='$DefectId'");
                                                
                                                        if(!empty($PrevImage) && @file_exists($sQuondaDir.$PrevImage))
                                                            @unlink($sQuondaDir.$PrevImage);
                                                
                                                        $sSQL  = "UPDATE tbl_gf_report_defects SET picture='$sDefectPic' WHERE id='$DefectId'";
                                                        $bFlag = $objDb->execute($sSQL);
                                                }                                               
                                            }
                                    }
                                }
			}

			else
			{
				if ($DefectId > 0)
				{
					$sSQL  = "DELETE FROM tbl_gf_report_defects WHERE id='$DefectId'";
					$bFlag = $objDb->execute($sSQL);
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
                        $DefectPictures = getList("tbl_gf_report_defects", "id", "picture", "id NOT IN ($sDefectIds) AND audit_id='$Id' AND picture!=''");
                    
                        foreach ($DefectPictures as $iDefect => $sPicture)
                        {
                            if(!empty($sPicture) && @file_exists($sQuondaDir.$sPicture))
                                @unlink($sQuondaDir.$sPicture);
                        }
                        
			$sSQL  = "DELETE FROM tbl_gf_report_defects WHERE id NOT IN ($sDefectIds) AND audit_id='$Id'";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true && $iCount == 0)
		{
			$sSQL  = "DELETE FROM tbl_gf_report_defects WHERE audit_id='$Id'";
			$bFlag = $objDb->execute($sSQL);
		}
	}

	if ($bFlag == true)
	{
		$sSQL = "SELECT audit_id FROM tbl_gf_inspection_checklist WHERE audit_id='$Id'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$sSQL  = ("INSERT INTO tbl_gf_inspection_checklist (audit_id, color_match, color_match_remarks, shading, shading_remarks, hand_feel, hand_feel_remarks, lab_testing, lab_testing_remarks) VALUES ('$Id', '".IO::strValue("ColorMatch")."', '".IO::strValue("ColorMatchRemarks")."', '".IO::strValue("Shading")."', '".IO::strValue("ShadingRemarks")."', '".IO::strValue("HandFeel")."', '".IO::strValue("HandFeelRemarks")."', '".IO::strValue("LabTesting")."', '".IO::strValue("LabTestingRemarks")."')");

		else
			$sSQL  = ("UPDATE tbl_gf_inspection_checklist SET color_match='".IO::strValue("ColorMatch")."', color_match_remarks='".IO::strValue("ColorMatchRemarks")."', shading='".IO::strValue("Shading")."', shading_remarks='".IO::strValue("ShadingRemarks")."', hand_feel='".IO::strValue("HandFeel")."', hand_feel_remarks='".IO::strValue("HandFeelRemarks")."', lab_testing='".IO::strValue("LabTesting")."', lab_testing_remarks='".IO::strValue("LabTestingRemarks")."' WHERE audit_id='$Id'");

		$bFlag = $objDb->execute($sSQL);
	}


	if (getDbValue("brand_id", "tbl_po", ("id='$PoId'")) == 77)
		$fDhu = round((($iTotalDefects * 39.37 * 100) / $iTotalGivenQty / IO::intValue("FabricWidth")), 2);

	else
		$fDhu = round(((($iTotalPoints * 3600) / $iTotalGivenQty) / IO::intValue("FabricWidth")), 2);
?>