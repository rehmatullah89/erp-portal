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
	$objDb2      = new Database( );


	$User        = IO::strValue('User');
	$Brand       = IO::intValue("Brand");
	$Vendor      = IO::intValue("Vendor");
	$Po          = IO::strValue("Po");
	$StyleNo     = IO::strValue("StyleNo");
	$Style       = IO::intValue("StyleId");
	$Colors      = IO::strValue("Colors");
	$ColorIds    = IO::strValue("ColorIds");
	$Sizes       = IO::strValue("Sizes");
	$SizeIds     = IO::strValue("SizeIds");
	$EtdRequired = IO::strValue("EtdRequired");


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $Brand == 0 || $Vendor == 0 || $Po == "" || $StyleNo == "" || ($Colors == "" && $ColorIds == "") || ($Sizes == "" && $SizeIds == "") || $EtdRequired == "")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, email, style_categories, status FROM tbl_users WHERE MD5(id)='$User'";
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
			$sStyleCategories = $objDb->getField(0, "style_categories");


			$iPo = getDbValue("id", "tbl_po", "order_no='$Po' AND vendor_id='$Vendor' AND brand_id='$Brand'");

			if ($iPo > 0)
				$aResponse["Message"] = "PO already exists";

			else
			{
				$sBrand  = getDbValue("brand", "tbl_brands", "id='$Brand'");
				$sVendor = getDbValue("vendor", "tbl_vendors", "id='$Vendor'");

				$objDb->execute("BEGIN", true, $iUser, $sName);


				if ($Style == 0 && $StyleNo != "")
				{
					$iBrand     = (int)getDbValue("parent_id", "tbl_brands", "id='$Brand'");
					$iSubSeason = (int)getDbValue("id", "tbl_seasons", "parent_id>'0' AND brand_id='$iBrand' AND ('$EtdRequired' BETWEEN start_date AND end_date)");

					$Style = (int)getDbValue("id", "tbl_styles", "sub_brand_id='$Brand' AND sub_season_id='$iSubSeason' AND FIND_IN_SET(category_id, '$sStyleCategories') AND style LIKE '$StyleNo'");

					if ($Style == 0)
						$Style = (int)getDbValue("id", "tbl_styles", "sub_brand_id='$Brand' AND FIND_IN_SET(category_id, '$sStyleCategories') AND style LIKE '$StyleNo'");

					if ($Style == 0)
					{
						$iSeason = (int)getDbValue("parent_id", "tbl_seasons", "id='$iSubSeason'");
						$Style   = getNextId("tbl_styles");

						$sSQL  = "INSERT INTO tbl_styles (id, category_id, style, style_name, reference, brand_id, sub_brand_id, season_id, sub_season_id, program_id, carry_over_id, fabric_width, specs_file, sketch_file, measurement_points, sizes, created, created_by, modified, modified_by)
												  VALUES ('$Style', '1', '$StyleNo', '$StyleNo', '', '$iBrand', '$Brand', '$iSeason', '$iSubSeason', '1', '0', '0', '', '', '', '', NOW( ), '$iUser', NOW( ), '$iUser')";
						$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);

						if ($bFlag == true)
						{
							$iLogId = getNextId("tbl_style_log");

							$sSQL  = "INSERT INTO tbl_style_log (id, style_id, user_id, date_time, reason, remarks, specs_file)
														 VALUES ('$iLogId', '$Style', '$iUser', NOW( ), 'D', 'Style Entry', '')";
							$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
						}


						if ($bFlag == true)
						{
							$objEmail = new PHPMailer( );

							$objEmail->Subject = "Style Entry Alert";
							$objEmail->Body    = "A new Style ({$StyleNo}) of {$sBrand} has been entered on Portal with missing information, please review and complete the Style Entry.\n\nAuditor: {$sName}\nEmail: {$sEmail}\n\nQuonda App";

							$objEmail->AddAddress("umair.baig@apparelco.com", "Mirza Umair Baig");
							$objEmail->AddAddress("salman@3-tree.com", "Irfan Ahmad");
							
							$objEmail->Send( );
						}
					}
				}


				if ($Colors == "" && $ColorIds != "")
				{
					$iColors = @explode(",", $ColorIds);
					$sColors = "";

					foreach ($iColors as $iColor)
					{
						if ($sColors != "")
							$sColors .= ",";

						$sColors .= getDbValue("color", "tbl_po_colors", "id='$iColor'");
					}

					$Colors = $sColors;
				}


				if ($Sizes != "" && $SizeIds == "")
				{
					$sSizes = @explode(",", $Sizes);
					$iSizes = "";

					foreach ($sSizes as $sSize)
					{
						$sSize = trim($sSize);

						if ($sSize == "")
							continue;


						$iSize = (int)getDbValue("id", "tbl_sizes", "size LIKE '$sSize'");

						if ($iSize == 0)
							continue;


						if ($iSizes != "")
							$iSizes .= ",";

						$iSizes .= $iSize;
					}

					$SizeIds = $iSizes;
				}


				$iPo = getNextId("tbl_po");

				$sSQL = "INSERT INTO tbl_po (id, vendor_id, brand_id, order_no, order_status, order_nature, styles, sizes, shipping_dates, currency, status, created, created_by, modified, modified_by)
								     VALUES ('$iPo', '$Vendor', '$Brand', '$Po', '', 'B', '$Style', '$SizeIds', '$EtdRequired', 'USD', 'W', NOW( ), '$iUser', NOW( ), '$iUser')";

				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);

				if ($bFlag == true)
				{
					$sColors = @explode(",", $Colors);

					foreach ($sColors as $sColor)
					{
						$sColor = trim($sColor);

						if ($sColor == "")
							continue;


						$iColor = getNextId("tbl_po_colors");

						$sSQL  = "INSERT INTO tbl_po_colors (id, po_id, color, line, price, vsr_price, style_id, destination_id, etd_required, vsr_etd_required)
						                             VALUES ('$iColor', '$iPo', '$sColor', '', '0', '0', '$Style', '0', '$EtdRequired', '$EtdRequired')";
						$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);

						if ($bFlag == true && $SizeIds != "")
						{
							$iSizes = @explode(",", $SizeIds);

							for ($i = 0; $i < count($iSizes); $i ++)
							{
								$sSQL = "INSERT INTO tbl_po_quantities (po_id, color_id, size_id, quantity)
								                                VALUES ('$iPo', '$iColor', '$iSizes[$i]', '0')";
								$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);

								if ($bFlag == false)
									break;
							}
						}

						if ($bFlag == false)
							break;
					}
				}

				if ($bFlag == true)
				{
					$sSQL = "INSERT INTO tbl_pre_shipment_advice (po_id) VALUES ('$iPo')";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					$sSQL = "INSERT INTO tbl_post_shipment_advice (po_id) VALUES ('$iPo')";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					$sSQL = "INSERT INTO tbl_vsr (po_id, style_id, price, variable, revised_etd, destination_id, created, created_by, modified, modified_by)
					                      VALUES ('$iPo', '$Style', '0', 'N', '$EtdRequired', '0', NOW( ), '$iUser', NOW( ), '$iUser')";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					$objDb->execute("COMMIT", true, $iUser, $sName);

					$aResponse['Status']  = "OK";
					$aResponse["Message"] = "PO Entry created successfully";
					$aResponse['Po']      = $iPo;


					$objEmail = new PHPMailer( );

					$objEmail->Subject = "PO Entry Alert";
					$objEmail->Body    = "A new PO ({$Po}) of {$sBrand} at {$sVendor} has been entered on Portal with missing information, please review and complete the PO Entry.\n\nAuditor: {$sName}\nEmail: {$sEmail}\n\nQuonda App";

					$objEmail->AddAddress("umair.baig@apparelco.com", "Mirza Umair Baig");
					$objEmail->AddAddress("salman@3-tree.com", "Irfan Ahmad");
					
					$objEmail->Send( );
				}

				else
				{
					$aResponse["Message"] = "An ERROR occured, please try again.";

					$objDb->execute("ROLLBACK", true, $iUser, $sName);
				}
			}
		}
	}

	print @json_encode($aResponse);


/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($_REQUEST)."<br><bR>".$sSQL."<br><br>".$error."<br><br>".$SizeIds;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>