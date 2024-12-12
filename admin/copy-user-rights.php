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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id      = IO::intValue("Id");
	$Referer = IO::strValue("Referer");
	$User    = IO::intValue("User");

	if ($Id == 0 || $User == 0)
		redirect($Referer, "ERROR");


	$sSQL = "SELECT brands, vendors, style_categories, report_types, audit_stages, suppliers FROM tbl_users WHERE id='$User'";
	$objDb->query($sSQL);

	$sBrands          = $objDb->getField(0, "brands");
	$sVendors         = $objDb->getField(0, "vendors");
	$sSuppliers       = $objDb->getField(0, "suppliers");
	$sStyleCategories = $objDb->getField(0, "style_categories");
	$sReportTypes     = $objDb->getField(0, "report_types");
	$sAuditStages     = $objDb->getField(0, "audit_stages");


	$bFlag = true;


	$objDb->execute("BEGIN");

	$sSQL = "UPDATE tbl_users SET brands='$sBrands', vendors='$sVendors', style_categories='$sStyleCategories', report_types='$sReportTypes', audit_stages='$sAuditStages', suppliers='$sSuppliers' WHERE id='$Id'";
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_user_rights WHERE user_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "INSERT INTO tbl_user_rights (user_id, page_id, `view`, `add`, `edit`, `delete`) (SELECT '$Id', page_id, `view`, `add`, `edit`, `delete` FROM tbl_user_rights WHERE user_id='$User')";
		$bFlag = $objDb->execute($sSQL);
	}


	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		redirect($Referer, "USER_ACCOUNT_UPDATED");
	}

	else
	{
		$objDb->execute("ROLLBACK");

		redirect("edit-user.php?Id={$Id}&Referer=".urlencode($Referer));
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>