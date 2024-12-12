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


	if (getDbValue("COUNT(*)", "tbl_pre_shipment_detail", "po_id='$Id'") > 0)
		$fCommission = (float)getDbValue("commission", "tbl_pre_shipment_detail", "po_id='$Id'", "commission DESC", 1);

	else
		$fCommission = (float)getDbValue("commission", "tbl_pre_shipment_advice", "po_id='$Id'");


	$iShipId = getNextId("tbl_pre_shipment_detail");

	$sSQL = "INSERT INTO tbl_pre_shipment_detail (id, po_id, commission, created, created_by, modified, modified_by) VALUES ('$iShipId', '$Id', '$fCommission', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')";

	if ($objDb->execute($sSQL) == true)
		$_SESSION['Flag'] = "SHIPMENT_DETAIL_ADDED";

	else
		$_SESSION['Flag'] = "DB_ERROR";

	header("Location: edit-pre-shipment-detail.php?Id={$Id}&PO=".urlencode($Po)."&Referer={$Referer}");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>