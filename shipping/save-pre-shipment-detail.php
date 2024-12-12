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

	if ($sUserRights['Add'] != "Y" && $sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id                    = IO::intValue('Id');
	$ShipId                = IO::intValue('ShipId');
	$Po                    = urlencode(IO::strValue('PO'));
	$Referer               = IO::strValue('Referer');
	$OldInvoicePackingList = IO::strValue("OldInvoicePackingList");

	if ($_FILES['InvoicePackingList']['name'] != "")
	{
		$sInvoicePackingList = ($ShipId."-".IO::getFileName($_FILES['InvoicePackingList']['name']));

		if (!@move_uploaded_file($_FILES['InvoicePackingList']['tmp_name'], ($sBaseDir.PRE_SHIPMENT_DIR.$sInvoicePackingList)))
				$sInvoicePackingList = "";

		else
			$sInvoicePackingListSql = ", invoice_packing_list='$sInvoicePackingList' ";
	}

	$HandoverToForwarder = IO::strValue('HandoverToForwarder');
	$ShippingDate        = IO::strValue('ShippingDate');
	$ArrivalDate         = IO::strValue('ArrivalDate');

	$HandoverToForwarder = (($HandoverToForwarder == "") ? "0000-00-00" : $HandoverToForwarder);
	$ShippingDate        = (($ShippingDate == "") ? "0000-00-00" : $ShippingDate);
	$ArrivalDate         = (($ArrivalDate == "") ? "0000-00-00" : $ArrivalDate);


	$objDb->execute("BEGIN");

	$sSQL  = ("UPDATE tbl_pre_shipment_detail SET terms_of_payment='".IO::strValue('TermsOfPayment')."', terms_of_delivery_id='".IO::intValue('TermsOfDelivery')."', mode_of_transport='".IO::strValue('ModeOfTransport')."', cartons='".IO::intValue('Cartons')."', handover_to_forwarder='$HandoverToForwarder', shipping_date='$ShippingDate', arrival_date='$ArrivalDate', invoice_no='".IO::strValue('InvoiceNo')."', lading_airway_bill='".IO::strValue('LadingAirwayBill')."', modified=NOW( ), modified_by='{$_SESSION['UserId']}' $sInvoicePackingListSql WHERE po_id='$Id' AND id='$ShipId'");
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_pre_shipment_quantities WHERE po_id='$Id' AND ship_id='$ShipId'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$ColorsCount = IO::intValue("ColorsCount");
		$SizesCount  = IO::intValue("SizesCount");

		for ($i = 0; $i < $ColorsCount; $i ++)
		{
			$iColorId = IO::intValue("ColorId".$i);

			for ($j = 0; $j < $SizesCount; $j ++)
			{
				$Qty  = IO::floatValue("Quantity".$i."_".$j);
				$Size = IO::intValue("Size".$i."_".$j);

				if ($Qty > 0)
				{
					$sSQL = "INSERT INTO tbl_pre_shipment_quantities (po_id, ship_id, color_id, size_id, quantity) VALUES ('$Id', '$ShipId', '$iColorId', '$Size', '$Qty')";
					$bFlag = $objDb->execute($sSQL);

					if ($bFlag == false)
						break;
				}
			}

			if ($bFlag == false)
				break;
		}
	}

	if ($bFlag == true)
	{
		$sSQL  = "UPDATE tbl_pre_shipment_detail SET quantity=(SELECT SUM(quantity) FROM tbl_pre_shipment_quantities WHERE po_id='$Id' AND ship_id='$ShipId') WHERE po_id='$Id' AND id='$ShipId'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "UPDATE tbl_pre_shipment_advice SET quantity=(SELECT SUM(quantity) FROM tbl_pre_shipment_quantities WHERE po_id='$Id') WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "SELECT id, etd_required FROM tbl_po_colors WHERE po_id='$Id'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iColorId     = $objDb->getField($i, "id");
			$sEtdRequired = $objDb->getField($i, "etd_required");

			$iOrderQty  = getDbValue("COALESCE(SUM(quantity), 0)", "tbl_po_quantities", "po_id='$Id' AND color_id='$iColorId'");
			$iOnTimeQty = getDbValue("COALESCE(SUM(psq.quantity), 0)", "tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq", "psd.po_id=psq.po_id AND psd.po_id='$Id' AND psd.id=psq.ship_id AND psq.color_id='$iColorId' AND psd.handover_to_forwarder != '0000-00-00' AND NOT ISNULL(psd.handover_to_forwarder) AND psd.handover_to_forwarder<='$sEtdRequired'");

			$iOnTimeQty = (($iOnTimeQty > 0) ? $iOrderQty : 0);


			$sSQL  = "UPDATE tbl_po_colors SET ontime_qty='$iOnTimeQty' WHERE id='$iColorId'";
			$bFlag = $objDb2->execute($sSQL);

			if ($bFlag == false)
				break;
		}
	}

	if ($bFlag == true)
	{
		$sSQL = "SELECT COALESCE(SUM(order_qty), 0), COALESCE(SUM(ontime_qty), 0) FROM tbl_po_colors WHERE po_id='$Id' AND etd_required <= CURDATE( )";
		$objDb->query($sSQL);

		$iOrderQty  = $objDb->getField(0, 0);
		$iOnTimeQty = $objDb->getField(0, 1);

		$fOtp = @round((($iOnTimeQty / $iOrderQty) * 100), 2);


		$sSQL  = "UPDATE tbl_pre_shipment_advice SET otp='$fOtp' WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		if ($sInvoicePackingList != "" && $OldInvoicePackingList != "" && $sInvoicePackingList != $OldInvoicePackingList)
			@unlink($sBaseDir.PRE_SHIPMENT_DIR.$OldInvoicePackingList);

		$_SESSION['Flag'] = "DATA_SAVED";
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";

		if ($sInvoicePackingList != "" && $sInvoicePackingList != $OldInvoicePackingList)
			@unlink($sBaseDir.PRE_SHIPMENT_DIR.$sInvoicePackingList);
	}

	header("Location: edit-pre-shipment-detail.php?Id={$Id}&PO={$Po}&Referer=".urlencode($Referer));

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>