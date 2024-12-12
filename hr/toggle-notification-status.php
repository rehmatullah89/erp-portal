<?
	/****************************************************************************\
	******************************************************************************
	**                                                                          **
	**  Triple Tree Customer Portal                                                  **
	**  Version 2.0                                                             **
	**                                                                          **
	**  http://portal.apparelco.com                                             **
	**                                                                          **
	**  Copyright 2008-10 (C) Triple Tree                                   **
	**                                                                          **
	**  **********************************************************************  **
	**                                                                          **
	**  Developer Information:                                                  **
	**                                                                          **
	**      Name  :  Muhammad Tahir Shahzad                                     **
	**      Email :  mtahirshahzad@hotmail.com                                  **
	**      Phone :  +92 333 456 0482                                           **
	**      URL   :  http://mts.sw3solutions.com                                **
	**                                                                          **
	**  **********************************************************************  **
	**                                                                          **
	**                                                                          **
	**                                                                          **
	**                                                                          **
	******************************************************************************
	\****************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id     = IO::intValue("Id");
	$Status = IO::strValue("Status");

	$sSQL = "UPDATE tbl_notifications SET status='$Status' WHERE id='$Id'";

	if ($objDb->execute($sSQL) == true)
		$_SESSION['Flag'] = "NOTIFICATION_STATUS_UPDATED";

	else
		$_SESSION['Flag'] = "DB_ERROR";

	header("Location: {$_SERVER['HTTP_REFERER']}");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>