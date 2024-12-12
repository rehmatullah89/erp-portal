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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id         = IO::intValue("Id");
	$Published  = IO::strValue("Published");
        $AuditResult= IO::strValue("AuditResult");

        if(($AuditResult == 'H' || $AuditResult == 'Hold') && $Published == 'Y')
            $_SESSION['Flag'] = "PUBLISH_STATUS_ERROR";
        else
        {
            if ($Published == "Y")
                    $sSQL = "UPDATE tbl_qa_reports SET published='Y', published_at=NOW( ) WHERE id='$Id'";

            else
                    $sSQL = "UPDATE tbl_qa_reports SET published='N', published_at='0000-00-00 00:00:00' WHERE id='$Id'";

            if ($objDb->execute($sSQL) == true)
                    $_SESSION['Flag'] = "QA_STATUS_UPDATED";

            else
                    $_SESSION['Flag'] = "DB_ERROR";
        }


	header("Location: {$_SERVER['HTTP_REFERER']}");

	
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>