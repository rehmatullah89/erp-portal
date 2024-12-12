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

	$Styles    = IO::getArray('Styles');
	$Sizes     = IO::getArray('Sizes');
	$iQuantity = 0;

	$objDb->execute("BEGIN");

	$ColorsCount = IO::intValue("ColorsCount");

	for ($i = 0; $i < $ColorsCount; $i ++)
	{
		$ColorId = IO::intValue("ColorId".$i);

		$sSQL = "UPDATE tbl_po_colors SET vsr_price='".IO::floatValue("Price".$i)."', vsr_etd_required='".IO::strValue("DateOfShipment".$i)."' WHERE id='$ColorId'";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == false)
			break;
	}

	if ($bFlag == true)
	{
		$sSQL  = "UPDATE tbl_po SET vsr_shipping_dates=(SELECT GROUP_CONCAT(DISTINCT(vsr_etd_required) SEPARATOR ',') FROM tbl_po_colors WHERE po_id='$Id' GROUP BY po_id) WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$iLogId = getNextId("tbl_po_log");

		$sSQL  = "INSERT INTO tbl_po_log (id, po_id, user_id, date_time, reason) VALUES ('$iLogId', '$Id', '{$_SESSION['UserId']}', NOW( ), 'VSR Data Revision')";
		$bFlag = $objDb->execute($sSQL);
	}


	if ($bFlag == true)
	{
		$_SESSION['Flag'] = "PO_SAVED";

		$objDb->execute("COMMIT");
	}

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		$objDb->execute("ROLLBACK");
	}
?>