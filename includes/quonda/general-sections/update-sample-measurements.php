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
               
        $bFlag = $objDb->execute("BEGIN");
        
        $sSQL = "SELECT point_id FROM tbl_style_specs WHERE style_id='$iStyle' AND size_id='$iSize' AND version='0' ORDER BY id";
        $objDb2->query($sSQL);

        $iCount = $objDb2->getCount( );

        for($i = 0; $i < $iCount; $i ++)
        {
                $iPoint = $objDb2->getField($i, 'point_id');

                $sFindings = IO::strValue("Specs{$iSize}_{$iColor}_{$iPoint}");
                $sReplaceSpecs = IO::strValue("ReplaceSpecs{$iSize}_{$iPoint}");

                if ($sFindings == "")
                        continue;

                $sSQL  = "INSERT INTO tbl_qa_report_sample_specs (sample_id, point_id, findings, specs) VALUES ('$SampleId', '$iPoint', '$sFindings', '$sReplaceSpecs') ON DUPLICATE KEY UPDATE point_id='$iPoint', findings='$sFindings', specs='$sReplaceSpecs'";
                $bFlag = $objDb->execute($sSQL);

                if ($bFlag == false)
                        break;

        }

        if($bFlag == true)
        {
            $_SESSION['Flag'] = "DATA_SAVED";
            $objDb->execute("COMMIT");
            
            header("Location: edit-sample-measurements.php?AuditId={$AuditId}&SampleId={$SampleId}");
            exit( );
        }
        else
        {           
            $_SESSION['Flag'] = "DB_ERROR";
            $objDb->execute("ROLLBACK");
        }
?>