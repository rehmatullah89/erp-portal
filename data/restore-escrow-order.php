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

	@require_once("../requires/session.php");

	if ($sUserRights['Delete'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$Id = IO::intValue('Id');


	$sSQL = "SELECT * FROM tbl_escrow_po_colors WHERE po_id='$Id' ORDER BY id LIMIT 1";
	$objDb->query($sSQL);

	$iStyleNo     = $objDb->getField(0, "style_id");
	$fPrice       = $objDb->getField(0, "price");
	$sEtdRequired = $objDb->getField(0, "etd_required");
	$iDestination = $objDb->getField(0, "destination_id");



	$objDb->execute("BEGIN");


	$sSQL = "INSERT INTO tbl_po (id, vendor_id, brand_id, order_no, order_status, order_type, order_nature, customer, customer_po_no, call_no, terms_of_delivery, place_of_departure, way_of_dispatch, terms_of_payment, looms, size_set, lab_dips, photo_sample, pre_prod_sample, note, styles, sizes, destinations, shipping_dates, vsr_shipping_dates, quantity, currency, vas_adjustment, status, production_status, created, created_by, modified, modified_by, accepted, accepted_at, accepted_by)
	                            (SELECT id, vendor_id, brand_id, order_no, order_status, order_type, order_nature, customer, customer_po_no, call_no, terms_of_delivery, place_of_departure, way_of_dispatch, terms_of_payment, looms, size_set, lab_dips, photo_sample, pre_prod_sample, note, styles, sizes, destinations, shipping_dates, vsr_shipping_dates, quantity, currency, vas_adjustment, status, production_status, created, created_by, modified, modified_by, accepted, accepted_at, accepted_by FROM tbl_escrow_po WHERE id='$Id')";
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL = "INSERT INTO tbl_po_colors (id, po_id, color, line, price, style_id, destination_id, etd_required, order_qty, ontime_qty)
		                                   (SELECT id, po_id, color, line, price, style_id, destination_id, etd_required, order_qty, ontime_qty FROM tbl_escrow_po_colors WHERE po_id='$Id')";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "INSERT INTO tbl_po_quantities (po_id, color_id, size_id, quantity)
		                                       (SELECT po_id, color_id, size_id, quantity FROM tbl_escrow_po_quantities WHERE po_id='$Id')";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_escrow_po WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_escrow_po_colors WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_escrow_po_quantities WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "INSERT INTO tbl_pre_shipment_advice (po_id) VALUES ('$Id')";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "INSERT INTO tbl_post_shipment_advice (po_id) VALUES ('$Id')";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "INSERT INTO tbl_vsr (po_id, style_id, price, variable, revised_etd, destination_id, created, created_by, modified, modified_by) VALUES ('$Id', '$iStyleNo', '$fPrice', 'N', '$sEtdRequired', '$iDestination', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "UPDATE tbl_po SET vsr_shipping_dates=(SELECT GROUP_CONCAT(DISTINCT(vsr_etd_required) SEPARATOR ',') FROM tbl_po_colors WHERE po_id='$Id' GROUP BY po_id) WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$iLogId = getNextId("tbl_po_log");

		$sSQL  = "INSERT INTO tbl_po_log (id, po_id, user_id, date_time, reason) VALUES ('$iLogId', '$Id', '{$_SESSION['UserId']}', NOW( ), 'PO Restoration')";
		$bFlag = $objDb->execute($sSQL);
	}


	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		$_SESSION['Flag'] = "PO_RESTORED";
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";
	}

	header("Location: escrow-orders.php");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>