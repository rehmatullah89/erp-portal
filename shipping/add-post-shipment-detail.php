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

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id      = IO::intValue('Id');
	$Po      = IO::strValue('PO');
	$Referer = urlencode(IO::strValue('Referer'));

	$iShipId = getNextId("tbl_post_shipment_detail");

	$sSQL = "INSERT INTO tbl_post_shipment_detail (id, po_id, created, created_by, modified, modified_by) VALUES ('$iShipId', '$Id', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')";

	if ($objDb->execute($sSQL) == true)
		$_SESSION['Flag'] = "SHIPMENT_DETAIL_ADDED";

	else
		$_SESSION['Flag'] = "DB_ERROR";

	header("Location: edit-post-shipment-detail.php?Id={$Id}&PO=".urlencode($Po)."&Referer={$Referer}");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>