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

	if ($sUserRights['Add'] != "Y" && $sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id      = IO::intValue('Id');
	$Po      = IO::strValue('PO');
	$Referer = urlencode(IO::strValue('Referer'));

	$objDb->execute("BEGIN");

	$sSQL  = "SELECT shipping_documents FROM tbl_post_shipment_detail WHERE po_id='$Id'";
	$bFlag = $objDb->query($sSQL);

	if ($bFlag == true)
	{
		$iDocumentsCount = $objDb->getCount( );

		for ($i = 0; $i < $iDocumentsCount; $i ++)
			$sShippingDocuments[] = $objDb->getField($i, 0);
	}

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_post_shipment_detail WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_post_shipment_quantities WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "SELECT * FROM tbl_pre_shipment_detail WHERE po_id='$Id' ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPreShipId  = $objDb->getField($i, "id");
			$iPostShipId = getNextId("tbl_post_shipment_detail");

			$sSQL = ("INSERT INTO tbl_post_shipment_detail (id, po_id, terms_of_payment, terms_of_delivery_id, mode_of_transport, cartons, handover_to_forwarder, shipping_date, arrival_date, lading_airway_bill, quantity, created, created_by, modified, modified_by)
						                            VALUES ('$iPostShipId', '$Id', '".$objDb->getField($i, "terms_of_payment")."', '".$objDb->getField($i, "terms_of_delivery_id")."', '".$objDb->getField($i, "mode_of_transport")."', '".$objDb->getField($i, "cartons")."', '".$objDb->getField($i, "handover_to_forwarder")."', '".$objDb->getField($i, "shipping_date")."', '".$objDb->getField($i, "arrival_date")."', '".$objDb->getField($i, "lading_airway_bill")."', '".$objDb->getField($i, "quantity")."', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')");

			$bFlag = $objDb2->execute($sSQL);

			if ($bFlag == true)
			{
				$sSQL  = "INSERT INTO tbl_post_shipment_quantities (po_id, ship_id, color_id, size_id, quantity) (SELECT '$Id', '$iPostShipId', color_id, size_id, quantity FROM tbl_pre_shipment_quantities WHERE po_id='$Id' AND ship_id='$iPreShipId')";
				$bFlag = $objDb2->execute($sSQL);
			}

			if ($bFlag == false)
				break;
		}
	}

	if ($bFlag == true)
	{
		$sSQL  = "UPDATE tbl_post_shipment_advice SET quantity=(SELECT SUM(quantity) FROM tbl_post_shipment_quantities WHERE po_id='$Id') WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		for ($i = 0; $i < $iDocumentsCount; $i ++)
			@unlink($sBaseDir.POST_SHIPMENT_DIR.$sShippingDocuments[$i]);

		$objDb->execute("COMMIT");

		$_SESSION['Flag'] = "SHIPMENT_DETAIL_COPIED";
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";
	}

	header("Location: edit-post-shipment-detail.php?Id={$Id}&PO=".urlencode($Po)."&Referer={$Referer}");

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>