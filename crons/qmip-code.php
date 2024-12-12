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

		@ini_set('max_execution_time', 0);
		@set_time_limit(0);
		
		$objDbGlobal = new Database( );
		$objDb       = new Database( );
		$objDb2      = new Database( );


		print ("START: ".date("h:i A")."<hr />");
		$bFlag  = $objDb->execute("BEGIN", false);
		$bFlag  = $objDb->execute("SET tx_isolation = 'READ-COMMITTED'", false);
		$bFlag  = $objDb->execute("SET GLOBAL tx_isolation = 'READ-COMMITTED'", false);
		
		
		 
		$Reports            = array(15=>'C', 16=>'RE', 17=>'FI', 18=>'F', 21=>'E', 22=>'EL'); // Report Ids V/s Stage Codes
		$sQMIPDefectCodes   = getList("tbl_defect_codes", "id", "code", "report_id='41'");
		
		
		foreach($Reports as $iReport => $sStage)
		{
                    $sSQL  = "UPDATE tbl_qa_reports SET report_id='41', audit_stage='{$sStage}' WHERE report_id='$iReport'";
                    $bFlag = $objDb->execute($sSQL);

                    if ($bFlag == false)
                            break;				
                    else
                    {
                            $sReportDefectCodes = getList("tbl_defect_codes", "code", "id", "report_id='$iReport'");

                            foreach($sQMIPDefectCodes as $iCode => $sCode)
                            {
                                    $DefectId = (int)$sReportDefectCodes[$sCode];

                                    if($DefectId != 0)
                                    {
                                            $sSQL  = "UPDATE tbl_qa_report_defects SET code_id='{$iCode}' WHERE code_id='$DefectId'";
                                            $bFlag = $objDb->execute($sSQL);
                                    }

                                    if ($bFlag == false)
                                            break;
                            }
                    }
                    
		}	
	

        if ($bFlag == true)
        {
                $objDb->execute("COMMIT", false);

                print "QMIP REPORTS UPDATED SUCCESSFULLy";

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