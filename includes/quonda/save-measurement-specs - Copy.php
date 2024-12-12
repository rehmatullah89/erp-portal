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
        $sSize      = getDbValue("size", "tbl_sizes", "id='$PoSize'");
        $iSampleNo  = (getDbValue("COALESCE(MAX(sample_no), '0')", "tbl_qa_report_samples", "audit_id='$AuditId' AND size_id='$SamplingSize' AND color LIKE '$Color'") + 1);
        
        $sSQL  = "INSERT INTO tbl_qa_report_samples (id, audit_id, size_id, po_size_id, size, color, sample_no, nature, date_time) VALUES ('$iSampleId', '$AuditId', '$SamplingSize', '$PoSize', '$sSize', '$Color', '$iSampleNo', '$Nature', NOW( ))";
        $bFlag = $objDb->execute($sSQL);
                    
        if($bFlag == true)
        {
            $_SESSION['Flag'] == "QA_REPORT_SAVED";
            $objDb->execute("COMMIT");
?>
<script>
    parent.parent.hideLightview();   
    parent.location.reload();
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