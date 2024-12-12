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

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sSQL  = ("SELECT * FROM tbl_po WHERE order_no='".IO::strValue("OrderNo")."' AND order_status='".IO::strValue("OrderStatus")."' AND vendor_id='".IO::intValue("Vendor")."' AND brand_id='".IO::intValue("Brand")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$sStyles = IO::getArray('AdditionalStyles');

		if (!@in_array($_POST['StyleNo'], $sStyles))
			$sStyles[] = $_POST['StyleNo'];


		$iMtarBrands    = @explode(",", getDbValue("brands", "tbl_departments", "id='33'"));
		$Brand          = IO::intValue("Brand");
		$DateOfShipment = IO::strValue("DateOfShipment");
		$DateOfShipment = (($DateOfShipment == "") ? "0000-00-00" : $DateOfShipment);



		$objDb->execute("BEGIN");

		if (!@in_array($Brand, $iMtarBrands))
		{
			$sSQL  = ("INSERT INTO tbl_po (vendor_id, brand_id, order_no, order_status, order_type, order_nature, article_no, customer, customer_po_no, call_no, terms_of_delivery, place_of_departure, way_of_dispatch, terms_of_payment, size_set, lab_dips, photo_sample, pre_prod_sample, note, styles, sizes, destinations, shipping_dates, vsr_shipping_dates, vas_adjustment, currency, status, created, created_by, modified, modified_by)
								   VALUES ('".IO::intValue("Vendor")."', '$Brand', '".IO::strValue("OrderNo")."', '".IO::strValue("OrderStatus")."', '".(($Brand == 32) ? IO::strValue("OrderType") : '')."', '".IO::strValue("OrderNature")."', '".IO::strValue("ArticleNo")."', '".IO::strValue("Customer")."', '".IO::strValue("CustomerPoNo")."', '".IO::strValue("CallNo")."', '".IO::strValue("TermsOfDelivery")."', '".IO::strValue("PlaceOfDeparture")."', '".IO::strValue("WayOfDispatch")."', '".IO::strValue("TermsOfPayment")."', '".IO::strValue("SampleSize")."', '".IO::strValue("LabDips")."', '".IO::strValue("PhotoSample")."', '".IO::strValue("PreProductionSample")."', '".IO::strValue("Note")."', '".@implode(",", $sStyles)."', '".@implode(",", IO::getArray('Sizes'))."', '".IO::intValue("Destination")."', '$DateOfShipment', '$DateOfShipment', '".IO::floatValue('VasAdjustment')."', '".IO::strValue('Currency')."', 'W', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')");
		}

		else
		{
			$sSQL  = ("INSERT INTO tbl_po (vendor_id, brand_id, order_no, order_status, order_type, order_nature, article_no, customer, customer_po_no, call_no, terms_of_delivery, place_of_departure, way_of_dispatch, terms_of_payment, size_set, lab_dips, photo_sample, pre_prod_sample, note, styles, sizes, destinations, shipping_dates, vas_adjustment, currency, status, created, created_by, modified, modified_by)
								   VALUES ('".IO::intValue("Vendor")."', '$Brand', '".IO::strValue("OrderNo")."', '".IO::strValue("OrderStatus")."', '".(($Brand == 32) ? IO::strValue("OrderType") : '')."', '".IO::strValue("OrderNature")."', '".IO::strValue("ArticleNo")."', '".IO::strValue("Customer")."', '".IO::strValue("CustomerPoNo")."', '".IO::strValue("CallNo")."', '".IO::strValue("TermsOfDelivery")."', '".IO::strValue("PlaceOfDeparture")."', '".IO::strValue("WayOfDispatch")."', '".IO::strValue("TermsOfPayment")."', '".IO::strValue("SampleSize")."', '".IO::strValue("LabDips")."', '".IO::strValue("PhotoSample")."', '".IO::strValue("PreProductionSample")."', '".IO::strValue("Note")."', '".@implode(",", $sStyles)."', '".@implode(",", IO::getArray('Sizes'))."', '".IO::intValue("Destination")."', '$DateOfShipment', '".IO::floatValue('VasAdjustment')."', '".IO::strValue('Currency')."', 'W', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')");
		}

		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$iPoId    = $objDb->getAutoNumber( );
			$iColorId = getNextId("tbl_po_colors");

			if (!@in_array($Brand, $iMtarBrands))
				$sSQL = ("INSERT INTO tbl_po_colors (id, po_id, color, line, price, vsr_price, style_id, destination_id, etd_required, posdd, vsr_etd_required) VALUES ('$iColorId', '$iPoId', '', '', '".IO::floatValue("Price")."', '".IO::floatValue("Price")."', '".IO::strValue("StyleNo")."', '".IO::intValue("Destination")."', '$DateOfShipment', '$DateOfShipment', '$DateOfShipment')");

			else
				$sSQL = ("INSERT INTO tbl_po_colors (id, po_id, color, line, price, style_id, destination_id, etd_required, posdd) VALUES ('$iColorId', '$iPoId', '', '', '".IO::floatValue("Price")."', '".IO::strValue("StyleNo")."', '".IO::intValue("Destination")."', '$DateOfShipment', '$DateOfShipment')");

			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true)
		{
			$sSQL = "INSERT INTO tbl_pre_shipment_advice (po_id) VALUES ('$iPoId')";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true)
		{
			$sSQL = "INSERT INTO tbl_post_shipment_advice (po_id) VALUES ('$iPoId')";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true)
		{
			$sSQL = "INSERT INTO tbl_vsr (po_id, style_id, price, variable, revised_etd, destination_id, created, created_by, modified, modified_by) VALUES ('$iPoId', '".IO::strValue("StyleNo")."', '".IO::floatValue("Price")."', 'N', '$DateOfShipment', '".IO::intValue("Destination")."', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			redirect("edit-purchase-order.php?Id={$iPoId}&Style={$_POST['StyleNo']}&Price={$_POST['Price']}&Referer=add-purchase-order.php", "PO_SAVED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION['Flag'] = "DB_ERROR";
		}
	}

	else
		$_SESSION['Flag'] = "ORDER_NO_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>