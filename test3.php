<?
        @require_once("requires/session.php");

        
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
        
        $sAuditsList    = array();
	$sMovedFiles    = array();
        
	$sSQL = "SELECT audit_id from tbl_qa_report_images WHERE `type`='L' AND image LIKE '%_L_%' AND image NOT LIKE '%_LAB_%' AND audit_id IN (SELECT id FROM `tbl_qa_reports` WHERE report_id='46') GROUP BY audit_id";
        $objDb->query($sSQL);
	
	$iCount = $objDb->getCount();
	
        $sAuditsList = array();
        for($i=0; $i<$iCount; $i++)
            $sAuditsList[$objDb->getField($i, "audit_id")] = $objDb->getField($i, "audit_id");
        
        $sAudits     = implode(",", $sAuditsList);
        $sAuditDates = getList("tbl_qa_reports", "id", "audit_date", "id IN ($sAudits)");
        
        $sSQL = "SELECT * from tbl_qa_report_images WHERE `type`='L' AND image LIKE '%_L_%' AND image NOT LIKE '%_LAB_%' AND audit_id IN (SELECT id FROM `tbl_qa_reports` WHERE report_id='46') ORDER BY audit_id";
        $objDb->query($sSQL);
	
	$iCount = $objDb->getCount();
        
	for($i=0; $i<$iCount; $i++)
	{          
            $iId         = $objDb->getField($i, "id");
            $iAudit      = $objDb->getField($i, "audit_id");
            $sImage      = $objDb->getField($i, "image");
            $sAuditDate  = $sAuditDates[$iAudit];
            
            @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
            $sQuondaDir = ("files/quonda/".$sYear."/".$sMonth."/".$sDay."/");
            
            @mkdir((SPECS_SHEETS_DIR.$sYear), 0777);
            @mkdir((SPECS_SHEETS_DIR.$sYear."/".$sMonth), 0777);
            @mkdir((SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);
            $sSpecsDir  = ("files/specs-sheet/".$sYear."/".$sMonth."/".$sDay."/");
        
            //if(file_exists($sQuondaDir.$sImage))
            {
                $sMovedFiles[$iId] = $sImage;
                rename(($sQuondaDir.$sImage), ($sSpecsDir.$sImage));
            }
 
	}
        
	echo "<pre>";
	print_r($sMovedFiles);
        echo "---------------------------------";
        echo implode(",", array_keys($sMovedFiles));
        exit;
	
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );
        
	@ob_end_flush( );
?>