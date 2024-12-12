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

        $Id = IO::intValue("Id");
	$AuditSections = explode(",", getDbValue ("sections", "tbl_reports", "id = '$ReportId'"));
        $sReportItems  = getDbValue("items", "tbl_reports", "id='$ReportId'");

        if(IO::strValue("FinalAuditDate") == "")
            $FinalAuditDateText = "";
        else
            $FinalAuditDateText = "final_audit_date='".IO::strValue("FinalAuditDate")."', ";
    
        if(IO::intValue("ShipQty") == 0 && IO::strValue("AuditResult") == 'P')
            $ShipQty = getDbValue("SUM(lot_size)", "tbl_qa_lot_sizes", "audit_id='$Id'");
        else
            $ShipQty = IO::intValue("ShipQty");
        
        $TotalGmts = (int)getDbValue("SUM(sample_size)", "tbl_qa_lot_sizes", "audit_id='$Id'");

        if($TotalGmts == 0)
            $TotalGmts = IO::intValue("TotalGmts");
        
	//$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$PoId', group_id='".IO::intValue('Group')."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_status='".IO::strValue("AuditStatus")."', audit_result='".IO::strValue("AuditResult")."', audit_type='".IO::strValue("AuditType")."', colors='".IO::strValue("Colors")."',  total_gmts='$TotalGmts', max_defects='$MaxDefects',   sizes='".@implode(",", IO::getArray('Sizes'))."', qa_comments='".IO::strValue("Comments")."',  ".$FinalAuditDateText." date_time=NOW( ) WHERE id='$Id'");
	$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$PoId', total_cartons='".IO::intValue('TotalCartons')."', inspected_cartons='".IO::intValue('InspectedCartons')."', group_id='".IO::intValue('Group')."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_status='".IO::strValue("AuditStatus")."', audit_result='".IO::strValue("AuditResult")."', audit_type='".IO::strValue("AuditType")."', colors='".IO::strValue("Colors")."', maker='".IO::strValue("Maker")."', inspection_type='".IO::strValue("InspecType")."', bundle='".IO::strValue("Bundle")."', sizes='".@implode(",", IO::getArray('Sizes'))."', workmanship_result='".IO::strValue("WorkmanshipResult")."', ship_qty='$ShipQty', re_screen_qty='".IO::intValue("ReScreenQty")."', total_gmts='$TotalGmts', max_defects='$MaxDefects', knitted='".IO::floatValue("Knitted")."', dyed='".IO::floatValue("Dyed")."', cutting='".IO::intValue("Cutting")."', sewing='".IO::intValue("Sewing")."', finishing='".IO::intValue("Finishing")."', packing='".IO::intValue("Packing")."', final_audit_date='".((IO::strValue("FinalAuditDate") == "") ? "0000-00-00" : IO::strValue("FinalAuditDate"))."', standard='".IO::floatValue("Standard")."', approved_sample='".IO::strValue("ApprovedSample")."', shipping_mark='".IO::strValue("ShippingMark")."', packing_check='".IO::strValue("PackingCheck")."', approved_trims='".IO::strValue("ApprovedTrims")."', shade_band='".IO::strValue("ShadeBand")."', emb_approval='".IO::strValue("EmbApproval")."', gsm_weight='".IO::strValue("GsmWeight")."', carton_size='$CartonSize', cutting_lot_no='".IO::strValue("LotNo")."', qa_comments='".IO::strValue("Comments")."', date_time=NOW( ) WHERE id='$Id'");
        $bFlag = $objDb->execute($sSQL);

        $iTotalDefects = 0;
        
        if($bFlag == true && $sReportItems != "")
        {
            $sSQL  = "DELETE FROM tbl_qa_checklist_results WHERE audit_id='$Id'";
            $bFlag = $objDb->execute($sSQL);
            
            if($bFlag == true)
            {
                $CheckFieldC = IO::getArray("CheckFieldC");
                $CheckFieldT = IO::getArray("CheckFieldT");

                foreach($CheckFieldC as $iCheckId => $sCheckValue)
                {
                    $sTextValue = $CheckFieldT[$iCheckId];

                    $sSQL  = ("INSERT INTO tbl_qa_checklist_results (audit_id, item_id, check_value, text_value) VALUES ('$Id', '$iCheckId', '$sCheckValue', '$sTextValue')");
                    $bFlag = $objDb->execute($sSQL);

                    unset($CheckFieldT[$iCheckId]);
                }

                foreach($CheckFieldT as $iTextId => $sTextValue)
                {
                    $sSQL  = ("INSERT INTO tbl_qa_checklist_results (audit_id, item_id, check_value, text_value) VALUES ('$Id', '$iTextId', '', '$sTextValue')");
                    $bFlag = $objDb->execute($sSQL);
                }
            }
            
        }
        
	/*if ($bFlag == true && $ReportId == 54)
	{
            $rowIds = IO::getArray("rowIds");
            $defectStyles = IO::getArray("defectStyles");
            $defectSizes = IO::getArray("defectSizes");
            $defectColors = IO::getArray("defectColors");
            $defectSampleNumbers = IO::getArray("defectSampleNo");

            $iCount     = IO::intValue("Count");
            $iTotalDefects = $iCount;
            $sDefectIds = "";

            for($i=0; $i<$iCount; $i++) 
            {
                $rowId = $rowIds[$i];

                $DefectId   = IO::intValue("DefectId".$i);
                $Code       = IO::strValue("Code".$i);
                $Defects    = IO::intValue("Defects".$i);
                $Area       = IO::intValue("Area".$i);
                $LotNo      = IO::intValue("LotNo".$i);
                $Nature     = IO::floatValue("Nature".$i);
                //$Picture    = IO::getFileName($_FILES["Picture".$i]['name']);

                foreach($_FILES["Picture{$i}"]['name'] as $iPicture => $sPicture){
                    $Picture    = $sPicture;
                    break;
                }
                        
                $style = $defectStyles[$i];
                $color = $defectColors[$i];
                $size = $defectSizes[$i];
                $sampleNo = $defectSampleNumbers[$i]; 

                $Defects = (($Defects <= 0) ? 1 : $Defects);

                if ($DefectId > 0)
                {
                    $sSQL  = "UPDATE tbl_qa_report_defects SET code_id='$Code', defects='$Defects', area_id='$Area', lot_no='$LotNo', nature='$Nature', style_id='$style', size_id='$size',color='$color', sample_no='$sampleNo' WHERE id='$DefectId'";

                    $bFlag = $objDb->execute($sSQL);
                }

                else
                {
                    $DefectId = getNextId("tbl_qa_report_defects");

                    $PictureName = ($Picture != '')?$DefectId."-".$Picture:'';

                    $sSQL  = ("INSERT INTO tbl_qa_report_defects (id, audit_id, code_id, defects, area_id, lot_no, nature, picture, date_time, style_id, size_id, color, sample_no) VALUES ('$DefectId', '$Id', '$Code', '$Defects', '$Area', '$LotNo', '$Nature', '$PictureName', NOW(), '$style', '$size','$color','$sampleNo')");

                    $bFlag = $objDb->execute($sSQL);
                }

                if ($bFlag == false)
                    break;

            }

	}*/
        
        if ($bFlag == true /*&& $ReportId != 54*/)
	{
		$iCount = IO::intValue("Count");
                
		for ($i = 0; $i < $iCount; $i ++)
		{
			$DefectId   = IO::intValue("DefectId".$i);
			$Code       = IO::intValue("Code".$i);
			$Defects    = IO::intValue("Defects".$i);
			$SampleNo   = IO::intValue("SampleNo".$i);
			$LotNo      = IO::intValue("LotNo".$i);
                        $Area       = IO::intValue("Area".$i);
			$Nature     = IO::floatValue("Nature".$i);
			$Remarks    = IO::strValue("Remarks".$i);
                        $Status     = IO::strValue("Status".$i);			
			$Defects    = (($Defects <= 0) ? 1 : $Defects);
                        
                        foreach($_FILES["Picture{$i}"]['name'] as $iPicture => $sPicture){
                            $Picture    = $sPicture;
                            break;
                        }

			
			if ($DefectId > 0)
			{
				$sSQL  = "UPDATE tbl_qa_report_defects SET code_id='$Code', defects='$Defects', sample_no='$SampleNo', area_id='$Area', lot_no='$LotNo', nature='$Nature', remarks='$Remarks', status='$Status', date_time=NOW() WHERE id='$DefectId'";
				$bFlag = $objDb->execute($sSQL);
			}

			else
			{
				$DefectId = getNextId("tbl_qa_report_defects");

                $PictureName = ($Picture != '')?$DefectId."-".$Picture:'';
                
				$sSQL  = ("INSERT INTO tbl_qa_report_defects (id, audit_id, code_id, defects, sample_no, area_id, lot_no, nature, remarks, picture, status, date_time) VALUES ('$DefectId', '$Id', '$Code', '$Defects', '$SampleNo', '$Area', '$LotNo', '$Nature', '$Remarks', '$PictureName', '$Status', NOW())");
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == false)
				break;
		}
	}

        /*if ($bFlag == true && @in_array(12, $AuditSections))
        {            
            $sSQL  = "DELETE FROM tbl_qa_report_samples WHERE audit_id='$Id'";
            $bFlag = $objDb->execute($sSQL);

            if ($bFlag == true)
            {
                    $Colors = @explode(",", getDbValue("colors", "tbl_qa_reports", "id='$Id'"));
                    $Sizes  = @explode(",", getDbValue("sizes", "tbl_qa_reports", "id='$Id'"));
                    $iColor = 0;

                    foreach ($Colors as $sColor)
                    {
                            foreach ($Sizes as $iSize)
                            {
                                    $sSize         = getDbValue("size", "tbl_sizes", "id='$iSize'");
                                    $iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");
                                    $iSamples      = array(0, 0, 0, 0, 0);


                                    $sSQL = "SELECT point_id FROM tbl_style_specs WHERE style_id='$Style' AND size_id='$iSamplingSize' AND version='0' AND specs!='0' AND specs!='' ORDER BY id";
                                    $objDb->query($sSQL);

                                    $iCount = $objDb->getCount( );

                                    for($i = 0; $i < $iCount; $i ++)
                                    {
                                            $iPoint = $objDb->getField($i, 'point_id');


                                            for ($j = 1; $j <= 5; $j ++)
                                            {
                                                    $sFindings = IO::strValue("Specs{$iSamplingSize}_{$iColor}_{$iPoint}_{$j}");

                                                    if ($sFindings != "" && $iSamples[($j - 1)] == 0)
                                                    {
                                                            $iSampleNo = (getDbValue("COALESCE(MAX(sample_no), '0')", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSamplingSize' AND color LIKE '$sColor'") + 1);
                                                            $iSampleId = getNextId("tbl_qa_report_samples");

                                                            $sSQL  = "INSERT INTO tbl_qa_report_samples (id, audit_id, size_id, color, sample_no, date_time) VALUES ('$iSampleId', '$Id', '$iSamplingSize', '$sColor', '$iSampleNo', NOW( ))";
                                                            $bFlag = $objDb2->execute($sSQL);

                                                            $iSamples[($j - 1)] = $iSampleId;
                                                    }

                                                    if ($bFlag == false)
                                                            break;
                                            }

                                    }

                                    if ($bFlag == false)
                                            break;


                                    for($i = 0; $i < $iCount; $i ++)
                                    {
                                            $iPoint = $objDb->getField($i, 'point_id');


                                            for ($j = 1; $j <= 5; $j ++)
                                            {
                                                    $sFindings = IO::strValue("Specs{$iSamplingSize}_{$iColor}_{$iPoint}_{$j}");

                                                    if ($sFindings == "")
                                                            continue;


                                                    $sSQL  = "INSERT INTO tbl_qa_report_sample_specs (sample_id, point_id, findings) VALUES ('{$iSamples[($j - 1)]}', '$iPoint', '$sFindings') ON DUPLICATE KEY UPDATE point_id='$iPoint', findings='$sFindings'";
                                                    $bFlag = $objDb2->execute($sSQL);

                                                    if ($bFlag == false)
                                                            break;
                                            }
                                    }

                                    if ($bFlag == false)
                                            break;
                            }


                            if ($bFlag == false)
                                    break;

                            $iColor ++;
                    }
            }
        }*/
        
        if ($bFlag == true && @in_array(10, $AuditSections))
        {
            $sSQL = "DELETE FROM tbl_qa_packaging_defects WHERE audit_id='$Id'";
            $bFlag = $objDb->execute($sSQL);
        
            if($bFlag == true)
            {
                $PDefectId              = getNextId("tbl_qa_packaging_defects");
                $iPackingDefectRows     = IO::getArray("PDefectRows");
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

                foreach($iPackingDefectRows as $iKey => $sZero)
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
                            $sPkPicture = $Id."_".$iDefect."_PACKAGING_".$sPkPicture;
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
        }
        
        if ($bFlag == true && @in_array(10, $AuditSections))
        {
            $LabelingTotalCartons = IO::intValue("LabelingTotalCartons");
            $LabelingInspectedCartons = IO::intValue("LabelingInspectedCartons");
            
            $sSQL = "UPDATE tbl_qa_report_details SET labeling_total_cartons='$LabelingTotalCartons', labeling_sample_size='$LabelingInspectedCartons' WHERE audit_id='$Id'";
            $bFlag = $objDb->execute($sSQL);
            
            if($bFlag == true)
            {
                $sSQL = "DELETE FROM tbl_qa_labeling_defects WHERE audit_id='$Id'";
                $bFlag = $objDb->execute($sSQL);
            }
        
            if($bFlag == true)
            {
                $LDefectId              = getNextId("tbl_qa_labeling_defects");
                $iLabelingDefectRows    = IO::getArray("LDefectRows");
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
        }
   
        $iDefectiveGmts = getDbValue("COUNT(DISTINCT(sample_no))", "tbl_qa_report_defects", "audit_id='$Id' AND nature>'0'");
       	$fDhu = round((($iDefectiveGmts / $TotalGmts) * 100), 2);


?>