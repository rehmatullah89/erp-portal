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

	$iTotalDefects = 0;

    if(IO::strValue("FinalAuditDate") == "") {

        $FinalAuditDateText = "";
    
    } else {

        $FinalAuditDateText = "final_audit_date='".IO::strValue("FinalAuditDate")."', ";
    }

    $TotalGmts = getDbValue("SUM(sample_size)", "tbl_qa_lot_sizes", "audit_id='$Id'");

	$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$PoId', group_id='".IO::intValue('Group')."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_status='".IO::strValue("AuditStatus")."', audit_result='".IO::strValue("AuditResult")."', audit_type='".IO::strValue("AuditType")."', colors='".IO::strValue("Colors")."', maker='".IO::strValue("Maker")."', total_gmts='$TotalGmts', max_defects='$MaxDefects',  inspection_type='".IO::strValue("InspecType")."', sizes='".@implode(",", IO::getArray('Sizes'))."', qa_comments='".IO::strValue("Comments")."', workmanship_result='".IO::strValue("WorkmanshipResult")."', ".$FinalAuditDateText." date_time=NOW( ) WHERE id='$Id'");
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{

        $rowIds = IO::getArray("rowIds");
        $defectStyles = IO::getArray("defectStyles");
        $defectSizes = IO::getArray("defectSizes");
        $defectColors = IO::getArray("defectColors");
        $defectSampleNumbers = IO::getArray("defectSampleNo");

        // $iTotalDefects = count($rowIds);

        $iCount     = IO::intValue("Count");
        $iTotalDefects = $iCount;
        $sDefectIds = "";

        for($i=0; $i<$iCount; $i++) {

            $rowId = $rowIds[$i];

            $DefectId = IO::intValue("DefectId".$i);
            $Code = IO::strValue("Code".$i);
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

            if ($DefectId > 0)
            {
                $sSQL  = "UPDATE tbl_qa_report_defects SET code_id='$Code', defects='$Defects', area_id='$Area', lot_no='$LotNo', nature='$Nature', style_id='$style', size_id='$size',color='$color', sample_no='$sampleNo' WHERE id='$DefectId'";

                $bFlag = $objDb->execute($sSQL);
            }

            else
            {
                $DefectId = getNextId("tbl_qa_report_defects");

                $sSQL  = ("INSERT INTO tbl_qa_report_defects (id, audit_id, code_id, defects, area_id, lot_no, nature, picture, date_time, style_id, size_id, color, sample_no) VALUES ('$DefectId', '$Id', '$Code', '$Defects', '$Area', '$LotNo', '$Nature', '$Picture', NOW(), '$style', '$size','$color','$sampleNo')");

                $bFlag = $objDb->execute($sSQL);
            }

            if ($bFlag == false)
                break;
           
        }

	}


        // if($bFlag == false)
        // {
        //     echo $sSQL;exit;
        // }
        $iDefectiveGmts = getDbValue("COUNT(DISTINCT(sample_no))", "tbl_qa_report_defects", "audit_id='$Id'");
       	$fDhu = round((($iDefectiveGmts / $TotalGmts) * 100), 2);


?>