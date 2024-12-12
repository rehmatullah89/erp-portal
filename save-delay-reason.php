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

	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$PoId   = IO::intValue("PoId");
	$Reason = IO::intValue("Reason");
	$UserId = IO::intValue("UserId");


	$iId = getNextId("tbl_po_delay_reasons");

	$sSQL = "INSERT tbl_po_delay_reasons (id, po_id, reason_id, user_id, date_time) VALUES ('$iId', '$PoId', '$Reason', '$UserId', NOW( ))";

	if ($objDb->execute($sSQL) == true)
		$_SESSION['Flag'] = "DELAY_SEASON_SAVED";

	else
		$_SESSION['Flag'] = "DB_ERROR";

	redirect("./");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>