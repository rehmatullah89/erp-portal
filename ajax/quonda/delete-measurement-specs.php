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

        header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
        header('Cache-Control: no-cache');
        header('Pragma: no-cache');

        @require_once("../../requires/session.php");

        if ($sUserRights['Delete'] != "Y")
        {
                print "ERROR|-|You havn\'t enough Rights to modify the selected Data.\n";
                exit( );
        }

        $objDbGlobal = new Database( );
        $objDb       = new Database( );

        $AuditId        = IO::strValue('AuditId');
        $QaSampleId     = IO::strValue('SampleId');

        $objDb->execute("BEGIN");

        $sSQL = "DELETE FROM tbl_qa_report_sample_specs WHERE sample_id='$QaSampleId'";
        $bFlag = $objDb->execute($sSQL);

        if ($bFlag == true)
        {
            $sSQL = "DELETE FROM tbl_qa_report_samples WHERE audit_id='$AuditId' AND id='$QaSampleId'";
            $bFlag = $objDb->execute($sSQL);
        }

        if ($bFlag == true)
        {
            $objDb->execute("COMMIT");
            
            print "SUCCESS";
            $_SESSION['Flag'] = "SAMPLE_SPECS_DELETED";
        }
        else
        {
            $_SESSION['Flag'] = "DB_ERROR";
            $objDb->execute("ROLLBACK");
        }


        $objDb->close( );
        $objDbGlobal->close( );

        @ob_end_flush( );
?>