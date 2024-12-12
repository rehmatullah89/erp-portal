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

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Vendor      = IO::intValue("Vendor");
	$Brand       = IO::intValue("Brand");
	$OrderNo     = IO::strValue("OrderNo");
	$OrderStatus = IO::strValue("OrderStatus");
	$AuditDate   = IO::strValue("AuditDate");

	$sSQL  = "SELECT id FROM tbl_po WHERE order_no='$OrderNo' AND order_status='$OrderStatus' AND vendor_id='$Vendor' AND brand_id='$Brand'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$iPoId = $objDb->getField(0, 0);


		$iId = getNextId("tbl_csc_audits");

		$sSQL = ("INSERT INTO tbl_csc_audits (id, po_id, vendor_id, brand_id, audit_date, created, created_by) VALUES ('$iId', '$iPoId', '$Vendor', '$Brand', '$AuditDate', NOW( ), '{$_SESSION['UserId']}')");

		if ($objDb->execute($sSQL) == true)
		{
			$_SESSION['Flag'] = "CSC_AUDIT_SAVED";

			header("Location: edit-csc-audit.php?Id={$iId}");
			exit( );
		}

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "INVALID_PO";


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>