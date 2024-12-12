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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$Id         = IO::intValue("Id");
	$AlertTypes = IO::getArray("AlertTypes");

	if (count($AlertTypes) == 0)
		$AlertTypes[] = "SMS";


	$iId = getNextId("tbl_audit_subscriptions");

	$sSQL = ("INSERT INTO tbl_audit_subscriptions (audit_id, user_id, alerts)
								           VALUES ('$Id', '{$_SESSION['UserId']}', '".@implode(",", $AlertTypes)."')");
	if ($objDb->execute($sSQL) == true)
	{


		$_SESSION['Flag'] = "AUDIT_SUBSCRIBED";
	}

	else
		$_SESSION['Flag'] = "DB_ERROR";

	redirect($_SERVER['HTTP_REFERER']);



	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>