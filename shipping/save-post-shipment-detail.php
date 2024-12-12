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

	$Id                   = IO::intValue('Id');
	$ShipId               = IO::intValue('ShipId');
	$Po                   = urlencode(IO::strValue('PO'));
	$Referer              = IO::strValue('Referer');
	$OldShippingDocuments = IO::strValue("OldShippingDocuments".$ShipId);

	if ($_FILES['ShippingDocuments'.$ShipId]['name'] != "")
	{
		$sShippingDocuments = ($ShipId."-".IO::getFileName($_FILES['ShippingDocuments'.$ShipId]['name']));

		if (!@move_uploaded_file($_FILES['ShippingDocuments'.$ShipId]['tmp_name'], ($sBaseDir.POST_SHIPMENT_DIR.$sShippingDocuments)))
				$sShippingDocuments = "";

		else
			$sShippingDocumentsSql = ", shipping_documents='$sShippingDocuments' ";
	}

	$HandoverToForwarder = IO::strValue('HandoverToForwarder'.$ShipId);
	$ShippingDate        = IO::strValue('ShippingDate'.$ShipId);
	$ArrivalDate         = IO::strValue('ArrivalDate'.$ShipId);

	$HandoverToForwarder = (($HandoverToForwarder == "") ? "0000-00-00" : $HandoverToForwarder);
	$ShippingDate        = (($ShippingDate == "") ? "0000-00-00" : $ShippingDate);
	$ArrivalDate         = (($ArrivalDate == "") ? "0000-00-00" : $ArrivalDate);

	$objDb->execute("BEGIN");

	$sSQL  = ("UPDATE tbl_post_shipment_detail SET terms_of_payment='".IO::strValue('TermsOfPayment'.$ShipId)."', terms_of_delivery_id='".IO::intValue('TermsOfDelivery'.$ShipId)."', mode_of_transport='".IO::strValue('ModeOfTransport'.$ShipId)."', cartons='".IO::intValue('Cartons'.$ShipId)."', handover_to_forwarder='$HandoverToForwarder', shipping_date='$ShippingDate', arrival_date='$ArrivalDate', lading_airway_bill='".IO::strValue('LadingAirwayBill'.$ShipId)."', container_flight_no='".IO::strValue('ContainerFlightNo'.$ShipId)."', modified=NOW( ), modified_by='{$_SESSION['UserId']}' $sShippingDocumentsSql WHERE po_id='$Id' AND id='$ShipId'");
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_post_shipment_quantities WHERE po_id='$Id' AND ship_id='$ShipId'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$ColorsCount = IO::intValue("ColorsCount".$ShipId);
		$SizesCount  = IO::intValue("SizesCount".$ShipId);

		for ($i = 0; $i < $ColorsCount; $i ++)
		{
			$iColorId = IO::intValue("ColorId".$i."_".$ShipId);

			for ($j = 0; $j < $SizesCount; $j ++)
			{
				$Qty  = IO::intValue("Quantity".$i."_".$j."_".$ShipId);
				$Size = IO::intValue("Size".$i."_".$j."_".$ShipId);

				if ($Qty > 0)
				{
					$sSQL = "INSERT INTO tbl_post_shipment_quantities (po_id, ship_id, color_id, size_id, quantity) VALUES ('$Id', '$ShipId', '$iColorId', '$Size', '$Qty')";
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
		$sSQL  = "UPDATE tbl_post_shipment_detail SET quantity=(SELECT SUM(quantity) FROM tbl_post_shipment_quantities WHERE po_id='$Id' AND ship_id='$ShipId') WHERE po_id='$Id' AND id='$ShipId'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "UPDATE tbl_post_shipment_advice SET quantity=(SELECT SUM(quantity) FROM tbl_post_shipment_quantities WHERE po_id='$Id') WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		if ($sShippingDocuments != "" && $OldShippingDocuments != "" && $sShippingDocuments != $OldShippingDocuments)
			@unlink($sBaseDir.POST_SHIPMENT_DIR.$OldShippingDocuments);

		$_SESSION['Flag'] = "DATA_SAVED";
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";

		if ($sShippingDocuments != "" && $sShippingDocuments != $OldShippingDocuments)
			@unlink($sBaseDir.POST_SHIPMENT_DIR.$sShippingDocuments);
	}

	header("Location: edit-post-shipment-detail.php?Id={$Id}&PO={$Po}&Referer=".urlencode($Referer));

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>