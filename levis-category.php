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

	
        $objDb->query("BEGIN");

        $bFlag        = true;
        $iInsertCount = 0;
        $sOldPurchaseOrderNo = "";
        
	$cfile = fopen('levis-pos.csv', 'r');
	while (($line = fgetcsv($cfile)) !== FALSE) 
	{   
            $sPurchaseOrderNo   = trim(stripslashes($line[0]));
            $sCategory        = trim($line[8]);         
            
            if($sOldPurchaseOrderNo != $sPurchaseOrderNo && $sCategory != "")
            {
                $sSQL = "UPDATE tbl_po SET category='$sCategory' WHERE order_no='$sPurchaseOrderNo' AND brand_id IN (436,527,528,529)";
                $bFlag = $objDb->execute($sSQL);

                $sOldPurchaseOrderNo = $sPurchaseOrderNo;
                $iInsertCount ++;
            }
            
            if($bFlag == false)
                break;                
	}
        
        if($bFlag == true)
        {
            $objDb->query("COMMIT");
            echo "DONE COUNT".$iInsertCount."<br/><pre>";
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