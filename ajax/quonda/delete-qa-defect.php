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

	if ($sUserRights['Edit'] != "Y")
	{
		print "ERROR|-|You havn\'t enough Rights to modify the selected Data.\n";
		exit( );
	}

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$AuditId   = IO::intValue('AuditId');
	$DefectId  = IO::intValue('DefectId');
	$AuditDate = IO::strValue('AuditDate');


	if ($DefectId > 0)
	{
		$sPicture  = getDbValue("picture", "tbl_qa_report_defects", "audit_id='$AuditId' AND id='$DefectId'");
		
		
		$sSQL = "DELETE FROM tbl_qa_report_defects WHERE id='$DefectId' AND audit_id='$AuditId'";
		
		if ($objDb->execute($sSQL) == true)
		{
			@list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);		
			
			@unlink("../".$sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sPicture);

			
			print "DELETED";
		}
	}

	
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>