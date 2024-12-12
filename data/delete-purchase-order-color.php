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

	if ($sUserRights['Delete'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id      = IO::intValue('Id');
	$ColorId = IO::intValue('ColorId');
	$Referer = IO::strValue('Referer');

	$sColor = addslashes(getDbValue("color", "tbl_po_colors", "po_id='$Id' AND id='$ColorId'"));


	$objDb->execute("BEGIN");

	$sSQL  = "DELETE FROM tbl_po_colors WHERE po_id='$Id' AND id='$ColorId'";
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_po_quantities WHERE po_id='$Id' AND color_id='$ColorId'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "UPDATE tbl_po SET quantity=(SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id='$Id'),
		                            shipping_dates=(SELECT GROUP_CONCAT(DISTINCT(etd_required) SEPARATOR ',') FROM tbl_po_colors WHERE po_id='$Id' GROUP BY po_id),
									styles=(SELECT GROUP_CONCAT(DISTINCT(style_id) SEPARATOR ',') FROM tbl_po_colors WHERE po_id='$Id' GROUP BY po_id),
									destinations=(SELECT GROUP_CONCAT(DISTINCT(destination_id) SEPARATOR ',') FROM tbl_po_colors WHERE po_id='$Id' GROUP BY po_id),
									sizes=(SELECT GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',') FROM tbl_po_quantities WHERE po_id='$Id' GROUP BY po_id)
		                        WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_pre_shipment_quantities WHERE po_id='$Id' AND color_id='$ColorId'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "UPDATE tbl_pre_shipment_advice SET quantity=(SELECT SUM(quantity) FROM tbl_pre_shipment_quantities WHERE po_id=tbl_pre_shipment_advice.po_id) WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "UPDATE tbl_pre_shipment_detail SET quantity=(SELECT SUM(quantity) FROM tbl_pre_shipment_quantities WHERE po_id=tbl_pre_shipment_detail.po_id AND ship_id=tbl_pre_shipment_detail.id) WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_post_shipment_quantities WHERE po_id='$Id' AND color_id='$ColorId'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "UPDATE tbl_post_shipment_advice SET quantity=(SELECT SUM(quantity) FROM tbl_pre_shipment_quantities WHERE po_id=tbl_post_shipment_advice.po_id) WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "UPDATE tbl_post_shipment_detail SET quantity=(SELECT SUM(quantity) FROM tbl_post_shipment_quantities WHERE po_id=tbl_post_shipment_detail.po_id AND ship_id=tbl_post_shipment_detail.id) WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$iLogId = getNextId("tbl_po_log");

		$sSQL  = "INSERT INTO tbl_po_log (id, po_id, user_id, date_time, reason) VALUES ('$iLogId', '$Id', '{$_SESSION['UserId']}', NOW( ), 'PO Color \'{$sColor}\' Deleted')";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		$_SESSION['Flag'] = "PO_SAVED";
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";
	}

	header("Location: edit-purchase-order.php?Id={$Id}&Referer={$Referer}");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>