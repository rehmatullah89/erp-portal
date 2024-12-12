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
	$Pos    = IO::strValue("Pos");
	$Po     = IO::strValue("Po");
	$Brand  = IO::intValue("Brand");
	$Vendor = IO::intValue("Vendor");


	$aResponse            = array( );
	$aResponse['Status']  = "ERROR";
	$aResponse["Message"] = "";

	if ($User == "" || ($Pos == "0" && $Po == "" && $Brand == 0 && $Vendor == 0))
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


			$iPo = 0;

			if ($Po != "" && $Pos == "0")
			{
				$iPo = getDbValue("id", "tbl_po", "vendor_id='$Vendor' AND brand_id='$Brand' AND order_no LIKE '$Po'");

				if ($iPo == 0)
				{
					$aResponse['Status'] = "OK";
					$aResponse["Message"] = "No matching PO Found";
				}

				else
					$Pos = $iPo;
			}


			if ($aResponse["Message"] == "")
			{
				$Pos     = trim($Pos, ",");
				
				$sStyles = getList("tbl_styles s, tbl_po_colors pc", "DISTINCT(s.id)", "s.style", "s.id=pc.style_id AND FIND_IN_SET(s.category_id, '$sStyleCategories') AND FIND_IN_SET(pc.po_id, '$Pos') AND FIND_IN_SET(s.sub_brand_id, '$sBrands')", "s.style");
				$sSizes  = getDbValue("GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',')", "tbl_po_quantities", "FIND_IN_SET(po_id, '$Pos')");

				if ($sSizes == "")
					$sSizes = getDbValue("sizes", "tbl_po", "FIND_IN_SET(id, '$Pos')");

				$sSizes = getList("tbl_sizes", "id", "size", "FIND_IN_SET(id, '$sSizes')", "size");


				$sColors      = array( );
				$sStyleColors = array( );

				$sSQL = "SELECT id, style_id, color FROM tbl_po_colors WHERE po_id IN ($Pos) GROUP BY style_id, color ORDER BY color";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
					$sColors[$objDb->getField($i, 0)] = str_replace(",", " - ", $objDb->getField($i, 2));
				
				for ($i = 0; $i < $iCount; $i ++)
					$sStyleColors[$objDb->getField($i, 1)."-".$objDb->getField($i, 0)] = str_replace(",", " - ", $objDb->getField($i, 2));


				$aResponse['Status']      = "OK";
				$aResponse['Styles']      = $sStyles;
				$aResponse['Colors']      = $sColors;
				$aResponse['StyleColors'] = $sStyleColors;
				$aResponse['Sizes']       = $sSizes;
				$aResponse['Po']          = $iPo;
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
