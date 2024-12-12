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
	$ShipId  = IO::intValue('ShipId');
	$Po      = IO::strValue('PO');
	$Referer = urlencode(IO::strValue('Referer'));

	$objDb->execute("BEGIN");

	$sSQL  = "SELECT invoice_packing_list FROM tbl_pre_shipment_detail WHERE id='$ShipId' AND po_id='$Id'";
	$bFlag = $objDb->query($sSQL);

	if ($bFlag == true && $objDb->getCount( ) == 1)
		$sInvoicePackingList = $objDb->getField(0, 0);

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_pre_shipment_detail WHERE id='$ShipId' AND po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_pre_shipment_quantities WHERE ship_id='$ShipId' AND po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "UPDATE tbl_pre_shipment_advice SET quantity=(SELECT SUM(quantity) FROM tbl_pre_shipment_quantities WHERE po_id='$Id') WHERE po_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		@unlink($sBaseDir.PRE_SHIPMENT_DIR.$sInvoicePackingList);

		$objDb->execute("COMMIT");

		$_SESSION['Flag'] = "SHIPMENT_DETAIL_DELETED";
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";
	}

	header("Location: edit-pre-shipment-detail.php?Id={$Id}&PO={$Po}&Referer={$Referer}");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>