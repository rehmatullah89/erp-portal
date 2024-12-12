<?
        @require_once("requires/session.php");

        
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
        
        $sAuditsList       = array();
	$sNonExistingFiles = array();
        
	$sSQL = "SELECT tbl_crc_audit_pictures.*,
                (SELECT audit_date from tbl_crc_audits WHERE id=tbl_crc_audit_pictures.audit_id) as _AuditDate
                from tbl_crc_audit_pictures 
                Order By id";
        $objDb->query($sSQL);
	
	$iCount = $objDb->getCount();
	
	for($i=0; $i<$iCount; $i++)
	{            
            $iId         = $objDb->getField($i, "id");
            $iAudit      = $objDb->getField($i, "audit_id");
            $sDoc        = $objDb->getField($i, "picture");
            $sAuditDate  = $objDb->getField($i, "_AuditDate");
            
            @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
            $sTncDir = ("files/tnc-audits/".$sYear."/".$sMonth."/".$sDay."/");
        
            if(!file_exists($sTncDir.$sDoc) || !(filesize($sTncDir.$sDoc) > 0))
            {
                $sAuditsList[$iAudit] = $iAudit;
                $sNonExistingFiles[$iId] = $sDoc;
            }
	}
        
	echo "<pre>";
	//print_r($sNonExistingFiles);
        print_r($sAuditsList);
        echo "------------------------------";
	echo implode(",", array_keys($sNonExistingFiles));
	exit;
	
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );
        
	@ob_end_flush( );
?>