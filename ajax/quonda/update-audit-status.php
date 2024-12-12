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

	$AuditCode = IO::intValue("AuditCode");
	$Status    = IO::strValue("Status");

	$sSQL = "SELECT audit_code FROM tbl_qa_reports WHERE id='$AuditCode'";
	$objDb->query($sSQL);

	if ($AuditCode == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Audit ID. Please select the proper Audit to Edit.\n";
		exit( );
	}

	if ($Status == "" || !@in_array($Status, array("LP", "PF", "LF")))
	{
		print "ERROR|-|Invalid Audit Status. Please select the proper Audit Status.\n";
		exit( );
	}


	$sSQL = "UPDATE tbl_qa_reports SET status='$Status', status_by='{$_SESSION['UserId']}', status_at=NOW( ) WHERE id='$AuditCode'";

	if ($objDb->execute($sSQL) == true)
	{
		switch ($Status)
		{
			case "LP" : $sStatus = "Likely to Pass"; break;
			case "PF" : $sStatus = "Possible Failure"; break;
			case "LF" : $sStatus = "Likely to Fail"; break;
			default   : $sStatus = "Decision Pending";
		}

		print "OK|-|$AuditCode|-|{$sStatus}";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>