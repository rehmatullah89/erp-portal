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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Id         = IO::intValue('Id');
	$ShipId     = IO::intValue('ShipId');
	$Commission = IO::floatValue('Commission');

	$sCurrency       = getDbValue("currency", "tbl_po" ,"id='$Id'");
	$iBrand          = getDbValue("brand_id", "tbl_po" ,"id='$Id'");
	$sCommissionType = getDbValue("commission_type", "tbl_brands", "id='$iBrand'");


	$sSymbol = $sCurrency;

	if ($sCommissionType == "F" && $sCurrency == "USD")
		$sSymbol = "¢";


	if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y")
  	{
		$sSQL = "UPDATE tbl_pre_shipment_detail SET commission='$Commission', commission_type='$sCommissionType' WHERE po_id='$Id'";

		if ($ShipId > 0)
			$sSQL .= " AND id='$ShipId' ";

		$objDb->execute($sSQL);



		$sSQL = "UPDATE tbl_pre_shipment_advice SET commission='$Commission', commission_type='$sCommissionType' WHERE po_id='$Id'";
		$objDb->execute($sSQL);
	}


	$sSQL = "SELECT commission FROM tbl_pre_shipment_detail WHERE po_id='$Id'";

	if ($ShipId > 0)
		$sSQL .= " AND id='$ShipId' ";

	$objDb->query($sSQL);

	if ($objDb->getField(0, 0) > 0)
		print formatNumber($objDb->getField(0, 0), (($sSymbol == "¢") ? false : true));

	else
	{
		$sSQL = "SELECT commission FROM tbl_pre_shipment_advice WHERE po_id='$Id'";
		$objDb->query($sSQL);

		print formatNumber($objDb->getField(0, 0), (($sSymbol == "¢") ? false : true));
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>