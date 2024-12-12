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

	$User    = IO::strValue("User");
	$StyleNo = IO::strValue("StyleNo");
	$StyleId = IO::intValue("StyleId");
	$Brand   = IO::intValue("Brand");
	$Vendor  = IO::intValue("Vendor");


	$aResponse            = array( );
	$aResponse['Status']  = "ERROR";
	$aResponse["Message"] = "";

	if ($User == "" || $StyleNo == "" || $Brand == 0 || $Vendor == 0)
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status, vendors, brands, style_categories, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser            = $objDb->getField(0, "id");
			$sName            = $objDb->getField(0, "name");
			$sBrands          = $objDb->getField(0, "brands");
			$sVendors         = $objDb->getField(0, "vendors");
			$sStyleCategories = $objDb->getField(0, "style_categories");
			$sGuest           = $objDb->getField(0, "guest");

			if ($StyleNo != "" && $StyleId == 0)
			{
				$StyleId = (int)getDbValue("id", "tbl_styles", "sub_brand_id='$Brand' AND FIND_IN_SET(category_id, '$sStyleCategories') AND style LIKE '$StyleNo'");

				if ($StyleId == 0)
				{
					$aResponse['Status'] = "OK";
					$aResponse["Message"] = "No matching Style Found";
				}
			}


			if ($aResponse["Message"] == "" && (int)getDbValue("COUNT(1)", "tbl_po po, tbl_po_colors pc", "po.id=pc.po_id AND pc.style_id='$StyleId' AND po.vendor_id='$Vendor' AND po.brand_id='$Brand'") == 0)
			{
				$aResponse['Status'] = "OK";
				$aResponse["Message"] = "Style exists, No related Sizes/Colors Found";
			}


			if ($aResponse["Message"] == "")
			{
				$sSizes  = getDbValue("GROUP_CONCAT(DISTINCT(pq.size_id) SEPARATOR ',')", "tbl_po_quantities pq, tbl_po_colors pc", "pq.color_id=pc.id AND pc.style_id='$StyleId'");
				$sSizes  = getList("tbl_sizes", "id", "size", "FIND_IN_SET(id, '$sSizes')", "size");
				$sColors = array( );

				$sSQL = "SELECT id, color FROM tbl_po_colors WHERE style_id='$StyleId' GROUP BY color ORDER BY color";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
					$sColors[$objDb->getField($i, 0)] = $objDb->getField($i, 1);


				$aResponse['Status'] = "OK";
				$aResponse['Colors'] = $sColors;
				$aResponse['Sizes']  = $sSizes;
			}
		}
	}


	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($aResponse)."<bR>".$sSQL;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>