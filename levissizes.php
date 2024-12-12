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

        $sSizesList =  getList("tbl_sizes", "size", "id");
	
        $objDb->query("BEGIN");

        $bFlag        = true;
        $iInsertCount = 0;
        $sInsertedItems = array();

	$cfile = fopen('levis-pos.csv', 'r');
	while (($line = fgetcsv($cfile)) !== FALSE) 
	{        
            $sSize              = trim(str_replace(["-", "_"], '', $line[2]));                           
            $sSize              = trim(str_replace(["  "], ' ', $sSize));                           
            $iSizeCsv           = (int)getDbValue("id", "tbl_sizes", "size LIKE '$sSize' AND type='Size'");             

            if($iSizeCsv == 0 && $sSizesList[$sSize] == "")
            {
                $iSize = getNextId("tbl_sizes");               
                $sSizesList[$sSize] = $iSize;
                $sInsertedItems[$iSize] =  $sSize;
                
                $sSQL  = "INSERT INTO tbl_sizes (id, type, size, position) VALUES ('$iSize', 'Size', '$sSize', '$iSize')";
                $bFlag = $objDb->execute($sSQL);

                $iInsertCount ++;
            }
            
            if($bFlag == false)
                break;                
	}
        
        if($bFlag == true)
        {
            $objDb->query("COMMIT");
            echo "DONE COUNT".$iInsertCount."<br/><pre>";
            print_r($sInsertedItems);
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