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

	$OldName   = (IO::strValue('OldName').".jpg");
	$NewName   = (IO::strValue('NewName').".jpg");
	$AuditDate = IO::strValue('AuditDate');

	@list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);

	if (rename(("../".$sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$OldName), ("../".$sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$NewName)))
		print IO::strValue('NewName');

	else
		print IO::strValue('OldName');

	$objDbGlobal->close( );

	@ob_end_flush( );
?>