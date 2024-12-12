<?
    @require_once("requires/session.php");

        @ini_set('display_errors', 0);
	@ini_set('log_errors', 0);
	@error_reporting(0);
	
	@putenv("TZ=Asia/Karachi");
	@date_default_timezone_set("Asia/Karachi");
	@ini_set("date.timezone", "Asia/Karachi");

	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);

	@ini_set('max_execution_time', 0);
	@set_time_limit(0);
        

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

        $iUpdatedCount = 0;
        $sPrevOrderNo = "";
        
        $objDb->query("BEGIN");

	$cfile = fopen('levis-pos.csv', 'r');
	while (($line = fgetcsv($cfile)) !== FALSE) 
	{            
            $iPoId              = 0;
            $sPurchaseOrderNo   = trim(stripslashes($line[0]));
            $iItemNo            = trim(stripslashes($line[1]));
            $sCode              = trim($line[5]);
            $sCategory          = trim($line[8]);
            
            if($iItemNo != "" && $sPrevOrderNo != $sPurchaseOrderNo)
            {
                $iPo = getDbValue("id", "tbl_po", "order_no='$sPurchaseOrderNo'");    
                
                if($iPo > 0)
                {
                    $sSQL = "UPDATE tbl_po SET item_number='$iItemNo' WHERE id='$iPo'";

                    $bFlag = $objDb->execute($sSQL);

                    if($bFlag == false)
                        break;                
                    else 
                        $iUpdatedCount ++;                    
                }
            }
           
            $sPrevOrderNo = $sPurchaseOrderNo;
	}
        
        if($bFlag == true)
        {
            $objDb->query("COMMIT");
            echo "DONE COUNT:".$iUpdatedCount.":POS UDPATED<br/>";
            exit;
        }
        else
        {
            $objDb->query("ROLLBACK");
            echo $sSQL;
            exit;
        }

	fclose($cfile);

	
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>