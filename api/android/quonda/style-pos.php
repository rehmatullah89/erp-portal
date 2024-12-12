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

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$User   = IO::strValue("User");
	$Styles = IO::strValue("Styles");
	$Style  = IO::strValue("Style");
	$Brand  = IO::intValue("Brand");
	$Vendor = IO::intValue("Vendor");


	$aResponse            = array( );
	$aResponse['Status']  = "ERROR";
	$aResponse["Message"] = "";

	if ($User == "" || ($Styles == "0" && $Style == "" && $Brand == 0 && $Vendor == 0))
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status, vendors, brands FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser    = $objDb->getField(0, "id");
			$sName    = $objDb->getField(0, "name");
			$sBrands  = $objDb->getField(0, "brands");
			$sVendors = $objDb->getField(0, "vendors");


			$Styles = ((substr($Styles, 0, 2) == "0,") ? substr($Styles, 2) : $Styles);
			$iStyle = 0;

			if ($Style != "" && $Styles == "0")
			{
				$iStyle = getDbValue("id", "tbl_styles", "sub_brand_id='$Brand' AND style LIKE '$Style'");

				if ($iStyle == 0)
				{
					$aResponse['Status'] = "OK";
					$aResponse["Message"] = "No matching Style Found";
				}

				else
					$Styles = $iStyle;
			}


			if ($aResponse["Message"] == "")
			{
				$sConditions = "";

				if ($Vendor > 0)
					$sConditions .= " AND po.vendor_id='$Vendor' ";

				else
					$sConditions .= " AND FIND_IN_SET(po.vendor_id, '$sVendors') ";

				if ($Brand > 0)
					$sConditions .= " AND po.brand_id='$Brand' ";

				else
					$sConditions .= " AND FIND_IN_SET(po.brand_id, '$sBrands') ";


				$sPos    = getList("tbl_po po, tbl_po_colors pc", "DISTINCT(po.id)", "TRIM(CONCAT(po.order_no, ' ', po.order_status))", "po.id=pc.po_id AND FIND_IN_SET(pc.style_id, '$Styles') $sConditions", "po.order_no");
				$sColors = array( );

				$sSQL = "SELECT id, color FROM tbl_po_colors WHERE style_id='$Styles' GROUP BY color ORDER BY color";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
					$sColors[$objDb->getField($i, 0)] = $objDb->getField($i, 1);


				$aResponse['Status'] = "OK";
				$aResponse['Pos']    = $sPos;
				$aResponse['Colors'] = $sColors;
				$aResponse['Style']  = $iStyle;
			}
		}
	}


	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert - ".IO::strValue("Styles");
	$objEmail->Body    = @json_encode($aResponse)."<bR>".$sConditions;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>
