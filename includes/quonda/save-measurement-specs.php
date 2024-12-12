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

        
        
        $objDb->execute("BEGIN");
        
        $iSampleId  = getNextId("tbl_qa_report_samples");
        $sSize      = getDbValue("size", "tbl_sizes", "id='$Size'");
        $iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");
                
        $iSampleNo  = (getDbValue("COALESCE(MAX(sample_no), '0')", "tbl_qa_report_samples", "audit_id='$AuditId' AND size_id='$iSamplingSize' AND color LIKE '$Color'") + 1);
        
        $sSQL  = "INSERT INTO tbl_qa_report_samples (id, audit_id, size_id, color, sample_no, date_time) VALUES ('$iSampleId', '$AuditId', '$iSamplingSize', '$Color', '$iSampleNo', NOW( ))";
        $bFlag = $objDb2->execute($sSQL);
                    
        if($bFlag == true)
        {
            $sEditLink = "<a href='includes/quonda/edit-measurement-specs.php?QaSampleId={$iSampleId}&SizeId={$iSamplingSize}&Size={$sSize}&AuditId={$AuditId}&Color={$Color}&Style={$Style}&SampleNo={$iSampleNo}' class='lightview' rel='iframe' title='Measurement Specs for Audit#: {$AuditId} :: :: width: 900, height: 650'><img src='images/icons/edit.gif' width='16' height='16' hspace='1' alt='Edit Measurement Specs' title='Edit Measurement Specs' /></a>&nbsp";

            if ($sUserRights['Delete'] == "Y")
                $sEditLink .= "<img src='images/icons/delete.gif' onclick='DeleteMeasurementTableRow({$iSampleId}, {$AuditId}, {$iSampleNo})'  width='16' height='16 alt='Delete Measurement Specs' title='Delete Measurement Specs' style='cursor:pointer;' />&nbsp";
                                
            $_SESSION['Flag'] == "QA_REPORT_SAVED";
            $objDb->execute("COMMIT");
?>
<script>
    parent.parent.hideLightview();   
    //parent.location.reload();
     parent.AddMeasurementTableRow("MeasurementSpecsTable", "<?=$Color?>", "<?=$sSize?>", "<?=$iSampleNo?>", "<?=$sEditLink?>");
</script>
<?
            exit();
        }
        else
        {
            $_SESSION['Flag'] = "DB_ERROR";
            $objDb->execute("ROLLBACK");            
        }
?>