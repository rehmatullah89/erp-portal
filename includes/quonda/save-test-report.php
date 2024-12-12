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
        $Styles          = IO::getArray("styles");
        $Styles = implode(',', $Styles);

        $HIOrderNo = IO::strValue('HOHIONo');

        $HiOrderId = getDbValue('id', 'tbl_hoh_orders', "order_no='$HIOrderNo'");

	$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$HiOrderId', hoh_order_no='".$HIOrderNo."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_status='".IO::strValue("AuditStatus")."', audit_result='".IO::strValue("AuditResult")."', audit_type='".IO::strValue("AuditType")."', total_gmts='$TotalGmts', defective_gmts='".IO::floatValue("GmtsDefective")."', ship_qty='".IO::intValue("ShipQty")."', re_screen_qty='".IO::intValue("ReScreenQty")."', knitted='".IO::floatValue("Knitted")."', dyed='".IO::floatValue("Dyed")."', cutting='".IO::intValue("Cutting")."', sewing='".IO::intValue("Sewing")."', finishing='".IO::intValue("Finishing")."', packing='".IO::intValue("Packing")."', final_audit_date='".((IO::strValue("FinalAuditDate") == "") ? "0000-00-00" : IO::strValue("FinalAuditDate"))."', approved_sample='".IO::strValue("ApprovedSample")."', check_level='".IO::strValue("SamplingPlan")."', qa_comments='".IO::strValue("Comments")."', inspected_cartons='".IO::floatValue("inspectedCartons")."', total_cartons='".IO::intValue("totalCartons")."', styles='".$Styles."', date_time=NOW( ) WHERE id='$Id'");
	$bFlag = $objDb->execute($sSQL);

        if ($bFlag == true)
        {
            if(getDbValue("COUNT(1)", "tbl_qa_hohenstein", "audit_id='$Id'") > 0)
            {
              $sSQL  = ("UPDATE tbl_qa_hohenstein SET  packaging_result         = '".IO::strValue("PackingResult")."',
                                                       packaging_comments       = '".IO::strValue("PackingComments")."',
                                                       labeling_total_cartons   = '".IO::intValue("totalLabelingCartons")."',
                                                       labeling_sample_size     = '".IO::intValue("inspectedLabelingCartons")."',
                                                       labeling_result          = '".IO::strValue("LabelingResult")."',
                                                       labeling_comments        = '".IO::strValue("LabelingComments")."',
                                                       workmanship_result       = '".IO::strValue("WorkmanshipResult")."',
                                                       airway_bill_applicable   = '".IO::strValue("AirwayBill")."',
                                                       airway_bill_number       = '".IO::strValue("BillNumber")."',
                                                       airway_bill_comments     = '".IO::strValue("AirwayComments")."',                                                           
                                                       measurement_result       = '".IO::strValue("MeasurementConformityResult")."',
                                                       measurement_remarks      = '".IO::strValue("MeasurementConformityRemarks")."'
                                                       WHERE audit_id='$Id'");
            }
            else
            {
                $sSQL  = ("INSERT INTO tbl_qa_hohenstein SET audit_id       = '$Id',
                                                  packaging_result          = '".IO::strValue("PackingResult")."',
                                                  packaging_comments        = '".IO::strValue("PackingComments")."',
                                                  labeling_total_cartons    = '".IO::intValue("totalLabelingCartons")."',
                                                  labeling_sample_size      = '".IO::intValue("inspectedLabelingCartons")."',       
                                                  labeling_result           = '".IO::strValue("LabelingResult")."',
                                                  labeling_comments         = '".IO::strValue("LabelingComments")."',
                                                  workmanship_result        = '".IO::strValue("WorkmanshipResult")."',
                                                  airway_bill_applicable   = '".IO::strValue("AirwayBill")."',
                                                  airway_bill_number       = '".IO::strValue("BillNumber")."',
                                                  airway_bill_comments     = '".IO::strValue("AirwayComments")."',                                                               
                                                  measurement_result        = '".IO::strValue("MeasurementConformityResult")."',
                                                  measurement_remarks       = '".IO::strValue("MeasurementConformityRemarks")."'");
            }
            
            $bFlag = $objDb->execute($sSQL);
        }
        
	if ($bFlag == true)
	{
		$iCount = IO::intValue("Count");
                $defectStyles = IO::getArray("defectStyles");
                $defectSizes = IO::getArray("defectSizes");
                $defectColors = IO::getArray("defectColors");
                $defectSampleNumbers = IO::getArray("defectSampleNo");

		for ($i = 0; $i < $iCount; $i ++)
		{
			$DefectId = IO::intValue("DefectId".$i);
			$Code     = IO::intValue("Code".$i);
			$Defects  = IO::intValue("Defects".$i);
			$Area     = IO::intValue("Area".$i);
                        $LotNo    = IO::intValue("LotNo".$i);
			$Nature   = IO::floatValue("Nature".$i);
                        $Picture  = IO::getFileName($_FILES["Picture".$i]['name']);

                        $style = $defectStyles[$i];
                        $color = $defectColors[$i];
                        $size = $defectSizes[$i];
                        $sampleNo = $defectSampleNumbers[$i];

			$Defects = (($Defects <= 0) ? 1 : $Defects);

			if ($Nature > 0)
				$iTotalDefects += $Defects;

			if ($DefectId > 0)
			{
				$sSQL  = "UPDATE tbl_qa_report_defects SET code_id='$Code', defects='$Defects', area_id='$Area', lot_id='$LotNo', nature='$Nature', style_id='$style', size_id='$size',color='$color', sample_no='$sampleNo' WHERE id='$DefectId'";

				$bFlag = $objDb->execute($sSQL);
			}

			else
			{
				$DefectId = getNextId("tbl_qa_report_defects");

				$sSQL  = ("INSERT INTO tbl_qa_report_defects (id, audit_id, code_id, defects, area_id, lot_id, nature, picture, date_time, style_id, size_id, color, sample_no) VALUES ('$DefectId', '$Id', '$Code', '$Defects', '$Area', '$LotNo', '$Nature', '$Picture', NOW(), '$style', '$size','$color','$sampleNo')");

				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == false)
				break;
		}
	}
        
        if ($bFlag == true)
        {
            $sSQL = "DELETE FROM tbl_qa_packaging_defects WHERE audit_id='$Id'";
            $bFlag = $objDb->execute($sSQL);
        }
        
        if($bFlag == true)
        {
            $PDefectId              = getNextId("tbl_qa_packaging_defects");
            $iPackagingDefectRows   = IO::getArray("PDefectRows");
            $iDefects               = IO::getArray("PDefect");
            $iSampleIds             = IO::getArray("PSamples");
            $sPrevPictures          = IO::getArray("PPrevPicture");
            
            if(!empty($_FILES["PImages"]['name']))
            {
                @list($sPkYear, $sPkMonth, $sPkDay) = @explode("-", $sAuditDate);

                @mkdir(($sBaseDir.PACKAGING_PICS_DIR.$sPkYear), 0777);
                @mkdir(($sBaseDir.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth), 0777);
                @mkdir(($sBaseDir.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay), 0777);

                $sPackagingDir = ($sBaseDir.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
            }
                
            foreach($iPackagingDefectRows as $iKey => $sZero)
            {
                $iDefect        = $iDefects[$iKey];
                $iSampleId      = $iSampleIds[$iKey];
                $sPrevPicture   = $sPrevPictures[$iKey];
                $sPkPicture     = $_FILES["PImages"]['name'][$iKey];
               
                if($iDefect != "" && $iSampleId != "")
                {
                    $sPkPictureSql  = "";
                    
                    if($sPkPicture != "")
                    {                        
                        $sPkPicture = $Id."_".$iDefect."_PACKING_".$sPkPicture;
                        if (@move_uploaded_file($_FILES["PImages"]['tmp_name'][$iKey], ($sPackagingDir.$sPkPicture)))
                        {
                            $sPkPictureSql = $sPkPicture;
                        }
                    }
                    else if($sPrevPicture != "")
                        $sPkPictureSql = $sPrevPicture;

                    $sSQL  = ("INSERT INTO tbl_qa_packaging_defects SET id='$PDefectId', audit_id='$Id', defect_code_id='$iDefect', sample_no='$iSampleId', picture='$sPkPictureSql', date_time= NOW()");
                    $bFlag = $objDb->execute($sSQL);
                    
                    $PDefectId++;
                }
            }
        }

        if ($bFlag == true)
        {
            $sSQL = "DELETE FROM tbl_qa_labeling_defects WHERE audit_id='$Id'";
            $bFlag = $objDb->execute($sSQL);
        }
        
        if($bFlag == true)
        {
            $LDefectId              = getNextId("tbl_qa_labeling_defects");
            $iLabelingDefectRows   = IO::getArray("LDefectRows");
            $iDefects               = IO::getArray("LDefect");
            $iSampleIds             = IO::getArray("LSamples");
            $sPrevPictures          = IO::getArray("LPrevPicture");
            
            if(!empty($_FILES["LImages"]['name']))
            {
                @list($sPkYear, $sPkMonth, $sPkDay) = @explode("-", $sAuditDate);

                @mkdir(($sBaseDir.PACKAGING_PICS_DIR.$sPkYear), 0777);
                @mkdir(($sBaseDir.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth), 0777);
                @mkdir(($sBaseDir.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay), 0777);

                $sPackagingDir = ($sBaseDir.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
            }
                
            foreach($iLabelingDefectRows as $iKey => $sZero)
            {
                $iDefect        = $iDefects[$iKey];
                $iSampleId      = $iSampleIds[$iKey];
                $sPrevPicture   = $sPrevPictures[$iKey];
                $sLkPicture     = $_FILES["LImages"]['name'][$iKey];
               
                if($iDefect != "" && $iSampleId != "")
                {
                    $sPkPictureSql  = "";
                    
                    if($sLkPicture != "")
                    {                        
                        $sLkPicture = $Id."_".$iDefect."_LABELING_".$sLkPicture;
                        if (@move_uploaded_file($_FILES["LImages"]['tmp_name'][$iKey], ($sPackagingDir.$sLkPicture)))
                        {
                            $sPkPictureSql = $sLkPicture;
                        }
                    }
                    else if($sPrevPicture != "")
                        $sPkPictureSql = $sPrevPicture;

                    $sSQL  = ("INSERT INTO tbl_qa_labeling_defects SET id='$LDefectId', audit_id='$Id', defect_code_id='$iDefect', sample_no='$iSampleId', picture='$sPkPictureSql', date_time= NOW()");
                    $bFlag = $objDb->execute($sSQL);
                    
                    $LDefectId++;
                }
            }
        }
        
	$fDhu = round((($iTotalDefects / $TotalGmts) * 100), 2);        
?>