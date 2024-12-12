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

	@session_start( );

	@ini_set('display_errors', 0);
	@ini_set('log_errors', 0);
	@error_reporting(0);
	
	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);


        $sBaseDir = "../";
	
	@require_once($sBaseDir."requires/configs.php");
	@require_once($sBaseDir."requires/db.class.php");
	@require_once($sBaseDir."requires/common-functions.php");
	@require_once($sBaseDir."requires/PHPMailer/class.phpmailer.php");

	@ini_set('max_execution_time', 0);
	@set_time_limit(0);
	
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	print ("START: ".date("h:i A")."<hr />");
        $bFlag  = $objDb->execute("BEGIN", false);
        $bFlag  = $objDb->execute("SET tx_isolation = 'READ-COMMITTED'", false);
        $bFlag  = $objDb->execute("SET GLOBAL tx_isolation = 'READ-COMMITTED'", false);
	
	$sSQL = "SELECT size, COUNT(*) FROM tbl_sizes GROUP BY size HAVING COUNT(*) > 1";
        //$sSQL = "SELECT size, COUNT(*) FROM tbl_sizes where size LIKE '0250 24 SHORT' GROUP BY size HAVING COUNT(*) > 1";
	$objDb2->query($sSQL);
		
	$iCount = $objDb2->getCount( );
		
        for ($i = 0; $i < $iCount; $i ++){
            
            $sGroupSize = $objDb2->getField($i, 0);
            $sSizeCount = $objDb2->getField($i, 1);
            
            
            $sSizesList = getList("tbl_sizes", "id", "id", "size LIKE '$sGroupSize'");
            $iFirstSize = array_shift($sSizesList);
            
            foreach ($sSizesList as $iSize)
            {
                $sSQL  = "UPDATE tbl_po_quantities SET size_id='{$iFirstSize}' WHERE size_id='$iSize'";
                $bFlag = $objDb->execute($sSQL);

                if ($bFlag == false)
                        break;
            }
            
            if ($bFlag == true)
            {
                $sSQL  = "DELETE FROM tbl_sizes WHERE id IN (". implode(",", $sSizesList).")";
                $bFlag = $objDb->execute($sSQL, false);
            }
            
        }

        if ($bFlag == true)
        {
                $objDb->execute("COMMIT", false);

                print "Sizes reversed Successfully";

        }

        else
        {
                print $sSQL."<br><br>".mysql_error( );

                $objDb->execute("ROLLBACK", false);
        }
	
	
	print ("<hr />END: ".date("h:i A")."<br />");
	
	
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>