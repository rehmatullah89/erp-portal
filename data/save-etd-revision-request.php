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

	$PoId    = IO::intValue("PoId");
	$Referer = IO::strValue('Referer');
	$iId     = getNextId("tbl_etd_revision_requests");

	$sSQL = ("INSERT INTO tbl_etd_revision_requests (id, po_id, revised_etd, reason_id, user_id, status, date_time) VALUES ('$iId', '$PoId', '".IO::strValue("RevisedEtd")."', '".IO::intValue("Reason")."', '".IO::intValue("Merchandiser")."', 'P', NOW( ))");

	if ($objDb->execute($sSQL) == true)
		redirect($Referer, "ETD_REVISION_REQUEST_SAVED");

	else
		$_SESSION['Flag'] = "DB_ERROR";

	header("Location: request-etd-revision.php?Id={$PoId}&Referer=".urlencode($Referer));

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>