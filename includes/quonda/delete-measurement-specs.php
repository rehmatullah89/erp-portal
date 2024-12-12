<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	@require_once("../../requires/session.php");

	if ($sUserRights['Delete'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$AuditId        = IO::intValue('AuditId');
        $QaSampleId     = IO::intValue('QaSampleId');

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
		$_SESSION['Flag'] = "SAMPLE_SPECS_DELETED";
                $objDb->execute("COMMIT");
        }
	else
        {
		$_SESSION['Flag'] = "DB_ERROR";
                $objDb->execute("ROLLBACK");
        }

	header("Location: ../../quonda/edit-qa-report.php?Id={$AuditId}");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>