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

	@require_once("../requires/session.php");


	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$AuditCode    = IO::strValue('AuditCode');
        $ImageName    = IO::strValue('ImageName');
        $ImageType    = IO::strValue('ImageType');
        $Index        = IO::intValue('Index');

        $iAudit       = getDbValue("id", "tbl_qa_reports", "audit_code='$AuditCode'");
        
        if($iAudit > 0)
        {
                if ($ImageType == 'L')
                    $sSQL = "UPDATE tbl_qa_reports SET specs_sheet_{$Index}='' WHERE id='$iAudit' AND specs_sheet_{$Index}='$ImageName'";
                else 
                    $sSQL = "DELETE from tbl_qa_report_images WHERE audit_id='$iAudit' AND `type`='L' AND image LIKE '$ImageName'";

                if ($objDb->execute($sSQL) == true)
		{
			@unlink("../".SPECS_SHEETS_DIR.$ImageName);

			print "DELETED";
		} 
        }


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>