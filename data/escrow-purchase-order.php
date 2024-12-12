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

	$Id      = IO::intValue('Id');
	$Referer = urldecode(IO::strValue("Referer"));
	$Reason  = IO::strValue("Reason");
	$Details = IO::strValue("Details");

	if ($Referer == "")
		$Referer = "purchase-orders.php";



	$objDb->execute("BEGIN");


	$sSQL = "INSERT INTO tbl_escrow_po (id, vendor_id, brand_id, order_no, order_status, order_type, order_nature, customer, customer_po_no, call_no, terms_of_delivery, place_of_departure, way_of_dispatch, terms_of_payment, looms, size_set, lab_dips, photo_sample, pre_prod_sample, note, styles, sizes, destinations, shipping_dates, vsr_shipping_dates, quantity, currency, vas_adjustment, status, production_status, created, created_by, modified, modified_by, accepted, accepted_at, accepted_by, cancelled_at, cancelled_by, reason, details)
	                            (SELECT id, vendor_id, brand_id, order_no, order_status, order_type, order_nature, customer, customer_po_no, call_no, terms_of_delivery, place_of_departure, way_of_dispatch, terms_of_payment, looms, size_set, lab_dips, photo_sample, pre_prod_sample, note, styles, sizes, destinations, shipping_dates, vsr_shipping_dates, quantity, currency, vas_adjustment, status, production_status, created, created_by, modified, modified_by, accepted, accepted_at, accepted_by, NOW( ), '{$_SESSION['UserId']}', '$Reason', '$Details' FROM tbl_po WHERE id='$Id')";
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL = "INSERT INTO tbl_escrow_po_colors (id, po_id, color, line, price, style_id, destination_id, etd_required, order_qty, ontime_qty)
		                                   (SELECT id, po_id, color, line, price, style_id, destination_id, etd_required, order_qty, ontime_qty FROM tbl_po_colors WHERE po_id='$Id')";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "INSERT INTO tbl_escrow_po_quantities (po_id, color_id, size_id, quantity)
		                                       (SELECT po_id, color_id, size_id, quantity FROM tbl_po_quantities WHERE po_id='$Id')";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_po_delay_reasons WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_etd_revision_requests WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_etd_revisions WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_fad_revisions WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_pre_shipment_advice WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_pre_shipment_detail WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_pre_shipment_quantities WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_post_shipment_advice WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_post_shipment_detail WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_post_shipment_quantities WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_vsr WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_vsr_remarks WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_vsr_comments WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_vsr_data WHERE color_id IN (SELECT id FROM tbl_po_colors WHERE po_id='$Id')";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_vsr_log WHERE color_id IN (SELECT id FROM tbl_po_colors WHERE po_id='$Id')";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_vsr_details WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_po_log WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_po WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_po_quantities WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_po_colors WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		$_SESSION['Flag'] = "PO_DELETED";
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";
	}

	header("Location: {$Referer}");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>