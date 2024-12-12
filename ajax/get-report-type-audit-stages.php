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

	$Id	= IO::intValue("Id");
	$List   = IO::strValue("List");

	if ($Id == 0)
	{
		print "ERROR|-|Invalid Report Type. Please select the proper Report Type.\n";
		exit;
	}

	$sAuditStages = getDbValue ("stages", "tbl_reports", "id = '$Id'");
  
        if($sAuditStages == "")
          $sAuditStages = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
        

        $sAuditStagesList    = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')", "position");

        print ("OK|-|".$List);
        foreach ($sAuditStagesList as $sKey => $sValue)
          print ("|-|".$sKey."||".$sValue);
        
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>