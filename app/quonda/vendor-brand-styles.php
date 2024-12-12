<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree QUONDA App                                                                   **
	**  Version 3.0                                                                              **
	**                                                                                           **
	**  http://app.3-tree.com                                                                    **
	**                                                                                           **
	**  Copyright 2008-17 (C) Triple Tree                                                        **
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
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$User   = IO::strValue("User");
	$Vendor = IO::intValue("Vendor");
	$Brand  = IO::intValue("Brand");


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";

	if ($User == "" || $Vendor == 0 || $Brand == 0)
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, email, status, vendors, brands, style_categories, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser            = $objDb->getField(0, "id");
			$sName            = $objDb->getField(0, "name");
			$sEmail           = $objDb->getField(0, "email");
			$sBrands          = $objDb->getField(0, "brands");
			$sVendors         = $objDb->getField(0, "vendors");
			$sStyleCategories = $objDb->getField(0, "style_categories");
			$sGuest           = $objDb->getField(0, "guest");


			$sFromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 30), date("Y")));
			$sToDate   = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") + 90), date("Y")));

			$sStyles = getList("tbl_po po, tbl_po_colors pc, tbl_styles s", "DISTINCT(pc.style_id)", "s.style", "po.id=pc.po_id AND pc.style_id=s.id AND po.brand_id=s.sub_brand_id AND po.vendor_id='$Vendor' AND po.brand_id='$Brand' AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(s.category_id, '$sStyleCategories')", "s.style");

			$aResponse['Status'] = "OK";
			$aResponse['Styles'] = $sStyles;
		}
	}


	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($aResponse);

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>