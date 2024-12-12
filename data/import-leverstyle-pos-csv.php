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

	if (strtolower(@implode(",", $sRecord)) != "customer,sp_ord_no,supplier,article,ship_date,port,colour,cust_colour,size1,size2,size3,size4,size5,size6,size7,size8,size9,size10,size11,size12,size13,size14,size15,size16,size17,size18,size19,size20,size21,size22,size23,size24,size25,size26,size27,size28,size29,size30,size_des1,size_des2,size_des3,size_des4,size_des5,size_des6,size_des7,size_des8,size_des9,size_des10,size_des11,size_des12,size_des13,size_des14,size_des15,size_des16,size_des17,size_des18,size_des19,size_des20,size_des21,size_des22,size_des23,size_des24,size_des25,size_des26,size_des27,size_des28,size_des29,size_des30")
		redirect("pos-import.php", "INVALID_POS_CSV_FILE");

	else
	{
		$iMainBrand    = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$iMainSeason   = getDbValue("parent_id", "tbl_seasons", "id='$Season'");
		$iCategory     = getDbValue("category_id", "tbl_styles", "sub_brand_id='$Brand'", "id DESC");
		$iCategory     = (($iCategory == 0) ? 1 : $iCategory);
		$sPoColors     = array( );
		$sSizes        = array( );
		$sDestinations = array( );


		$bFlag = $objDb->execute("BEGIN", false);

		while (($sRecord = @fgetcsv($hFile, 10000)) !== FALSE)
		{
			$sBrand       = trim(stripslashes($sRecord[0]));
			$sOrderNo     = trim(stripslashes($sRecord[1]));				
			$sVendor      = trim(stripslashes($sRecord[2]));
			$sStyle       = trim(stripslashes($sRecord[3]));
			$sEtdRequired = date("Y-m-d", strtotime(trim($sRecord[4])));
			$sDestination = trim(stripslashes($sRecord[5]));
			$sColorCode   = trim($sRecord[6]);
			$sColorDesc   = addslashes(trim(stripslashes($sRecord[7])));

			$iQuantity    = intval(str_replace(array(",", " "), "", $sRecord[8]));
			$sSize        = trim($sRecord[38]);
		
			if ($sColorCode == $sColorDesc)
				$sColorDesc = "";
				
			$sColor       = trim("{$sColorCode} {$sColorDesc}");
			$sDestination = (($sDestination == "") ? "USA" : $sDestination);
				
			
			if (strlen($sBrand) != mb_strlen($sBrand, 'utf-8') || strlen($sVendor) != mb_strlen($sVendor, 'utf-8') || strlen($sColor) != mb_strlen($sColor, 'utf-8'))
			{
				// Multi-lingual characters
				continue;
			}			
			
			
			$iSubBrand = (int)getDbValue("id", "tbl_brands", "parent_id='$iMainBrand' AND brand LIKE '$sBrand'");


			if ($iSubBrand == 0 || $sOrderNo == "" || $sStyle == "")
				continue;


			$iVendor = (int)getDbValue("id", "tbl_vendors", "parent_id='0' AND vendor LIKE '$sVendor'");
			$iStyle  = (int)getDbValue("id", "tbl_styles", "sub_brand_id='$iSubBrand' AND sub_season_id='$Season' AND style LIKE '$sStyle'");


			if ($Delete != "Y")
			{
				// Adding new Size(s)
				for ($i = 0; $i < 30; $i ++)
				{
					$sSize = trim($sRecord[38 + $i]);

					if ($sSize == "" || @array_key_exists($sSize, $sSizes))
						continue;
								
					
					$iSize = (int)getDbValue("id", "tbl_sizes", "size LIKE '$sSize'");
						
					if ($iSize == 0)
					{
						$iSize = getNextId("tbl_sizes");

						$sSQL  = "INSERT INTO tbl_sizes (id, type, size, position) VALUES ('$iSize', 'Size', '$sSize', '$iSize')";
						$bFlag = $objDb->execute($sSQL, false);

						if ($bFlag == false)
							break;
					}
					
					$sSizes[$sSize] = $iSize;
				}
				
				
				// Add new Destination
				if ($sDestination != "" && !@array_key_exists($sDestination, $sDestinations))
				{
					$iDestination = (int)getDbValue("id", "tbl_destinations", "brand_id='$iMainBrand' AND destination LIKE '$sDestination'");
						
					if ($iDestination == 0)
					{
						$sUtf8Destination = @mb_convert_encoding($sDestination, "UTF-8");
						$iDestination     = getNextId("tbl_destinations");
						$sRegion          = ((@strpos($sDestination, "EUR") !== FALSE) ? "Europe" : "USA & Others");

						$sSQL  = "INSERT INTO tbl_destinations (id, brand_id, region, destination, type) VALUES ('$iDestination', '$iMainBrand', '$sRegion', '$sUtf8Destination', 'D')";
						$bFlag = $objDb->execute($sSQL, false);

						if ($bFlag == false)
							break;
					}
					
					
					$sDestinations[$sDestination] = (int)$iDestination;
				}


				// Adding new Style
				if ($iStyle == 0)
				{
					$iStyle = getNextId("tbl_styles");


					$sSQL  = "INSERT INTO tbl_styles (id, category_id, style, style_name, reference, brand_id, sub_brand_id, season_id, sub_season_id, program_id, design_no, design_name, carry_over_id, fabric_width, specs_file, sketch_file, measurement_points, sizes, created, created_by, modified, modified_by)
											  VALUES ('$iStyle', '$iCategory', '$sStyle', '', '', '$iMainBrand', '$iSubBrand', '$iMainSeason', '$Season', '1', '', '', '0', '0', '', '', '', '', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')";
					$bFlag = $objDb->execute($sSQL, false);

					if ($bFlag == true)
					{
						$iLogId = getNextId("tbl_style_log");

						$sSQL  = "INSERT INTO tbl_style_log (id, style_id, user_id, date_time, reason, remarks, specs_file) VALUES ('$iLogId', '$iStyle', '{$_SESSION['UserId']}', NOW( ), 'D', 'Style Entry', '')";
						$bFlag = $objDb->execute($sSQL, false);
					}

					if ($bFlag == false)
						break;
				}
			}


			if ($iVendor == 0 || $iStyle == 0)
				continue;



			$iPoId = getDbValue("id", "tbl_po", "vendor_id='$iVendor' AND brand_id='$iSubBrand' AND order_no LIKE '$sOrderNo'");


			if ($Delete == "Y" && $iPoId > 0)
			{
				$sSQL  = "DELETE FROM tbl_po WHERE id='$iPoId'";
				$bFlag = $objDb->execute($sSQL, false);

				if ($bFlag == true)
				{
					$sSQL  = "DELETE FROM tbl_po_colors WHERE po_id='$iPoId'";
					$bFlag = $objDb->execute($sSQL, false);
				}

				if ($bFlag == true)
				{
					$sSQL  = "DELETE FROM tbl_po_quantities WHERE po_id='$iPoId'";
					$bFlag = $objDb->execute($sSQL, false);
				}

				if ($bFlag == true)
				{
					$sSQL  = "DELETE FROM tbl_styles WHERE id='$iStyle'";
					$bFlag = $objDb->execute($sSQL, false);
				}

				if ($bFlag == true)
				{
					$sSQL  = "DELETE FROM tbl_style_log WHERE style_id='$iStyle'";
					$bFlag = $objDb->execute($sSQL, false);
				}

				if ($bFlag == true)
				{
					$sSQL  = "DELETE FROM tbl_pre_shipment_advice WHERE po_id='$iPoId'";
					$bFlag = $objDb->execute($sSQL, false);
				}

				if ($bFlag == true)
				{
					$sSQL  = "DELETE FROM tbl_post_shipment_advice WHERE po_id='$iPoId'";
					$bFlag = $objDb->execute($sSQL, false);
				}

				if ($bFlag == true)
				{
					$sSQL = "DELETE FROM tbl_vsr WHERE po_id='$iPoId'";
					$bFlag = $objDb->execute($sSQL, false);
				}
			}

			else if ($iPoId > 0)
			{
				$sSQL  = "DELETE FROM tbl_po_quantities WHERE po_id='$iPoId'";
				$bFlag = $objDb->execute($sSQL, false);
			}


			if ($bFlag == false)
				break;
		}



		if ($bFlag == true && $Delete != "Y")
		{
			@fseek($hFile, 0);


			while (($sRecord = @fgetcsv($hFile, 10000)) !== FALSE)
			{
				$sBrand       = trim(stripslashes($sRecord[0]));
				$sOrderNo     = trim(stripslashes($sRecord[1]));				
				$sVendor      = trim(stripslashes($sRecord[2]));
				$sStyle       = trim(stripslashes($sRecord[3]));
				$sEtdRequired = date("Y-m-d", strtotime(trim($sRecord[4])));
				$sDestination = trim(stripslashes($sRecord[5]));
				$sColorCode   = trim($sRecord[6]);
				$sColorDesc   = addslashes(trim(stripslashes($sRecord[7])));
				$sSize        = trim($sRecord[38]);

		
				if ($sColorCode == $sColorDesc)
					$sColorDesc = "";
				
				$sColor       = trim("{$sColorCode} {$sColorDesc}");
				$sDestination = (($sDestination == "") ? "USA" : $sDestination);
					
				
				if (strlen($sBrand) != mb_strlen($sBrand, 'utf-8') || strlen($sVendor) != mb_strlen($sVendor, 'utf-8') || strlen($sColor) != mb_strlen($sColor, 'utf-8'))
				{
					// Multi-lingual characters
					continue;
				}			
				
				
				$iSubBrand = (int)getDbValue("id", "tbl_brands", "parent_id='$iMainBrand' AND brand LIKE '$sBrand'");


				if ($iSubBrand == 0 || $sOrderNo == "" || $sStyle == "")
					continue;


				$sOutput .= "<tr class=\"{$sClass[($iIndex % 2)]}\">
							   <td class=\"center\">{$iIndex}</td>
							   <td>{$sVendor}</td>
							   <td>{$sOrderNo}</td>
							   <td>{$sStyle}</td>
							   <td>{$sColor}</td>
							   <td>{$sDestination}</td>
							   <td>[Result]</td>
							 </tr>";


				$iVendor = (int)getDbValue("id", "tbl_vendors", "parent_id='0' AND vendor LIKE '$sVendor'");
				$iStyle  = (int)getDbValue("id", "tbl_styles", "sub_brand_id='$iSubBrand' AND sub_season_id='$Season' AND style LIKE '$sStyle'");
				$fPrice  = 0;


				if ($iVendor == 0 || $iStyle == 0)
				{
					if ($iVendor == 0)
						$sOutput = str_replace("[Result]", '<span class="error">Vendor not found</span>', $sOutput);

					else if ($iStyle == 0)
						$sOutput = str_replace("[Result]", '<span class="error">Style not found</span>', $sOutput);

					$iIndex ++;

					continue;
				}



				$iPoId = getDbValue("id", "tbl_po", "vendor_id='$iVendor' AND brand_id='$iSubBrand' AND order_no LIKE '$sOrderNo'");


				// Adding new PO
				if ($iPoId == 0)
				{
					$iPoId = getNextId("tbl_po");


					$sSQL  = "INSERT INTO tbl_po (id, vendor_id, brand_id, order_no, order_status, order_nature, customer_po_no, call_no, terms_of_delivery, place_of_departure, way_of_dispatch, terms_of_payment, size_set, lab_dips, photo_sample, pre_prod_sample, note, quantity, styles, sizes, destinations, shipping_dates, status, created, created_by, modified, modified_by)
										  VALUES ('$iPoId', '$iVendor', '$iSubBrand', '$sOrderNo', '', 'B', '', '', 'N/A', 'N/A', 'N/A', 'N/A', '', '', '', '', '', '0', '$iStyle', '', '{$sDestinations[$sDestination]}', '$sEtdRequired', 'W', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')";
					$bFlag = $objDb->execute($sSQL, false);

					if ($bFlag == true)
					{
						$sSQL = "INSERT INTO tbl_pre_shipment_advice (po_id, otp) VALUES ('$iPoId', '0')";
						$bFlag = $objDb->execute($sSQL, false);
					}

					if ($bFlag == true)
					{
						$sSQL = "INSERT INTO tbl_post_shipment_advice (po_id) VALUES ('$iPoId')";
						$bFlag = $objDb->execute($sSQL, false);
					}

					if ($bFlag == true)
					{
						$sSQL = "DELETE FROM tbl_vsr WHERE po_id='$iPoId'";
						$bFlag = $objDb->execute($sSQL, false);
					}

					if ($bFlag == true)
					{
						$sSQL = "INSERT INTO tbl_vsr (po_id, style_id, price, variable, revised_etd, destination_id, final_audit_date, created, created_by, modified, modified_by)
											  VALUES ('$iPoId', '$iStyle', '$fPrice', 'N', '$sEtdRequired', '{$sDestinations[$sDestination]}', '$sEtdRequired', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')";
						$bFlag = $objDb->execute($sSQL, false);
					}
				}


				if ($bFlag == true && $iPoId > 0)
				{
					$iColorId = getDbValue("id", "tbl_po_colors", "po_id='$iPoId' AND style_id='$iStyle' AND color LIKE '$sColor' AND destination_id='{$sDestinations[$sDestination]}' AND etd_required='$sEtdRequired'");


					// Adding new Color
					if ($iColorId == 0)
					{
						$sColor   = @mb_convert_encoding($sColor, "UTF-8");
						$iColorId = getNextId("tbl_po_colors");

						$sSQL  = "INSERT INTO tbl_po_colors (id, po_id, color, line, price, style_id, destination_id, etd_required, order_qty, ontime_qty)
													 VALUES ('$iColorId', '$iPoId', '$sColor', '', '$fPrice', '$iStyle', '{$sDestinations[$sDestination]}', '$sEtdRequired', '0', '0')";
						$bFlag = $objDb->execute($sSQL, false);
					}

					if ($bFlag == true)
					{					
						for ($i = 0; $i < 30; $i ++)
						{
							$sSize     = trim($sRecord[38 + $i]);
							$iQuantity = intval(str_replace(array(",", " "), "", $sRecord[8 + $i]));

							if ($sSize == "" || $iQuantity == 0)
								continue;									
							

							if (getDbValue("COUNT(1)", "tbl_po_quantities", "po_id='$iPoId' AND color_id='$iColorId' AND size_id='{$sSizes[$sSize]}'") > 0)
								$sSQL = "UPDATE tbl_po_quantities SET quantity='$iQuantity' WHERE po_id='$iPoId' AND color_id='$iColorId' AND size_id='{$sSizes[$sSize]}'";

							else
								$sSQL = "INSERT INTO tbl_po_quantities (po_id, color_id, size_id, quantity) VALUES ('$iPoId', '$iColorId', '{$sSizes[$sSize]}', '$iQuantity')";

							$bFlag = $objDb->execute($sSQL, false);
							
							if ($bFlag == false)
								break;
						}
					}
				
					if ($bFlag == true)
					{
						$sSQL = "UPDATE tbl_po_colors SET order_qty=(SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id='$iPoId' AND color_id='$iColorId') WHERE id='$iColorId'";
						$bFlag = $objDb->execute($sSQL, false);
					}


					if (!@isset($sPoColors[$iPoId]) || !@is_array($sPoColors[$iPoId]))
						$sPoColors[$iPoId] = array( );

					$sPoColors[$iPoId][] = $iColorId;
				}


				if ($bFlag == false)
					break;

				$iIndex ++;

				$sOutput = str_replace("[Result]", '<span class="ok">Record added</span>', $sOutput);
			}
		}


		if ($bFlag == true)
		{
			foreach ($sPoColors as $iPoId => $iColors)
			{
				$sColors = @implode(",", $iColors);


				$sSQL  = "DELETE FROM tbl_po_colors WHERE po_id='$iPoId' AND NOT FIND_IN_SET(id, '$sColors')";
				$bFlag = $objDb->execute($sSQL, false);

				if ($bFlag == true)
				{
					$sSQL  = "UPDATE tbl_po SET quantity       = (SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id='$iPoId'),
												shipping_dates = (SELECT GROUP_CONCAT(DISTINCT(etd_required) SEPARATOR ',') FROM tbl_po_colors WHERE po_id='$iPoId'),
												styles         = (SELECT GROUP_CONCAT(DISTINCT(style_id) SEPARATOR ',') FROM tbl_po_colors WHERE po_id='$iPoId'),
												sizes          = (SELECT GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',') FROM tbl_po_quantities WHERE po_id='$iPoId'),
												destinations   = (SELECT GROUP_CONCAT(DISTINCT(destination_id) SEPARATOR ',') FROM tbl_po_colors WHERE po_id='$iPoId')
											WHERE id='$iPoId'";
					$bFlag = $objDb->execute($sSQL, false);
				}


				if ($bFlag == false)
					break;
			}
		}


		if ($bFlag == true)
		{
			$objDb->execute("COMMIT", false);

			$_SESSION["Flag"] = "POS_CSV_IMPORT_OK";
		}

		else
		{
			$sOutput = str_replace("[Result]", ('<span class="error">'.mysql_error( ).'</span>'), $sOutput);

			
			$objDb->execute("ROLLBACK", false);
			
			$_SESSION["Flag"] = "POS_CSV_IMPORT_ERROR";
		}
	}
?>