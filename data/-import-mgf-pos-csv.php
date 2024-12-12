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

	if (strtoupper(@implode(",", $sRecord)) != "SUPPLIER,MANUFACTURER,INDUSTRY,EDI_IND,PARTY_NAME,PROFOMA_PO,ORDER_NO,LINE_NO,COLOR_CODE,COLOR_DESC,SIZE_CODE,SIZE_DESC,ASSIGNED_QTY,SO_ID,SALES_ORDER_NO,ITEM_NO,BUSINESS,FACTORY_GAC_DATE,CUSTOMER_ID,STYLE_DESC")
		redirect("pos-import.php", "INVALID_POS_CSV_FILE");
	
	else
	{
		$iMainBrand  = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$iMainSeason = getDbValue("parent_id", "tbl_seasons", "id='$Season'");
		$iCategory   = getDbValue("category_id", "tbl_styles", "sub_brand_id='$Brand'", "id DESC");
		$iCategory   = (($iCategory == 0) ? 1 : $iCategory);
		$sPoColors   = array( );



		$bFlag = $objDb->execute("BEGIN", false);

		while (($sRecord = @fgetcsv($hFile, 10000)) !== FALSE)
		{
			$sVendor       = trim(stripslashes($sRecord[4]));
			$sPerfomaNo    = trim(stripslashes($sRecord[5]));
			$sOrderNo      = trim(stripslashes($sRecord[6]));
			$sLine         = trim($sRecord[7]);
			$sColorCode    = trim($sRecord[8]);
			$sColorDesc    = addslashes(trim(stripslashes($sRecord[9])));
			$sSizeCode     = trim($sRecord[10]);
			$sSizeDesc     = trim($sRecord[11]);
			$iQuantity     = intval(str_replace(array(",", " "), "", $sRecord[12]));
			$sSalesOrderNo = trim($sRecord[14]);
			$sStyle        = trim(stripslashes($sRecord[15]));
			$sEtdRequired  = date("Y-m-d", strtotime(trim($sRecord[17])));
			$sStyleDesc    = addslashes(trim(stripslashes($sRecord[19])));
			
			
			if ($sPerfomaNo == "" || $sStyle == "" || $iQuantity == 0)
				continue;


			$iVendor = (int)getDbValue("id", "tbl_vendors", "parent_id='0' AND vendor LIKE '$sVendor'");
			$iStyle  = (int)getDbValue("id", "tbl_styles", "sub_brand_id='$Brand' AND sub_season_id='$Season' AND style LIKE '$sStyle'");
			$sSize   = "{$sSizeCode} {$sSizeDesc}";
			$iSize   = (int)getDbValue("id", "tbl_sizes", "size LIKE '$sSize'");


			if ($Delete != "Y")
			{
				// Adding new Size
				if ($iSize == 0)
				{
					$iSize = getNextId("tbl_sizes");

					$sSQL  = "INSERT INTO tbl_sizes (id, type, size, position) VALUES ('$iSize', 'Size', '$sSize', '$iSize')";
					$bFlag = $objDb->execute($sSQL, false);

					if ($bFlag == false)
						break;
				}


				// Adding new Style
				if ($iStyle == 0)
				{
					$iStyle = getNextId("tbl_styles");


					$sSQL  = "INSERT INTO tbl_styles (id, category_id, style, style_name, reference, brand_id, sub_brand_id, season_id, sub_season_id, program_id, design_no, design_name, carry_over_id, fabric_width, specs_file, sketch_file, measurement_points, sizes, created, created_by, modified, modified_by)
											  VALUES ('$iStyle', '$iCategory', '$sStyle', '$sStyleDesc', '', '$iMainBrand', '$Brand', '$iMainSeason', '$Season', '1', '', '', '0', '0', '', '', '', '', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')";
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

				else if ($iStyle > 0)
				{
					$sSQL  = "UPDATE tbl_styles SET style_name='$sStyleDesc' WHERE id='$iStyle'";
					$bFlag = $objDb->execute($sSQL, false);
				}
			}


			if ($iVendor == 0 || $iStyle == 0 || $iSize == 0)
				continue;



			$iPoId = getDbValue("id", "tbl_po", "vendor_id='$iVendor' AND brand_id='$Brand' AND order_no LIKE '$sPerfomaNo'");


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
					$sSQL  = "DELETE FROM tbl_vsr WHERE po_id='$iPoId'";
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
				$sVendor       = trim(stripslashes($sRecord[4]));
				$sPerfomaNo    =  trim(stripslashes($sRecord[5]));
				$sOrderNo      = trim(stripslashes($sRecord[6]));				
				$sLine         = trim($sRecord[7]);
				$sColorCode    = trim($sRecord[8]);
				$sColorDesc    = addslashes(trim(stripslashes($sRecord[9])));
				$sSizeCode     = trim($sRecord[10]);
				$sSizeDesc     = trim($sRecord[11]);
				$iQuantity     = intval(str_replace(array(",", " "), "", $sRecord[12]));
				$sSalesOrderNo = trim($sRecord[14]);
				$sStyle        = trim(stripslashes($sRecord[15]));
				$sEtdRequired  = date("Y-m-d", strtotime(trim($sRecord[17])));
				$sStyleDesc    = addslashes(trim(stripslashes($sRecord[19])));

				if ($sPerfomaNo == "" || $sStyle == "" || $iQuantity == 0)
					continue;


				$sSize  = "{$sSizeCode} {$sSizeDesc}";
				$sColor = "{$sColorCode} {$sColorDesc}";


				$sOutput .= "<tr class=\"{$sClass[($iIndex % 2)]}\">
							   <td class=\"center\">{$iIndex}</td>
							   <td>{$sVendor}</td>
							   <td>{$sPerfomaNo}</td>
							   <td>{$sStyle}</td>
							   <td>{$sColor}</td>
							   <td>{$sSize}</td>
							   <td>[Result]</td>
							 </tr>";


				$iVendor = (int)getDbValue("id", "tbl_vendors", "parent_id='0' AND vendor LIKE '$sVendor'");
				$iStyle  = (int)getDbValue("id", "tbl_styles", "sub_brand_id='$Brand' AND sub_season_id='$Season' AND style LIKE '$sStyle'");
				$iSize   = (int)getDbValue("id", "tbl_sizes", "size LIKE '$sSize'");
				$fPrice  = 0;


				if ($iVendor == 0 || $iStyle == 0 || $iSize == 0)
				{
					if ($iVendor == 0)
						$sOutput = str_replace("[Result]", '<span class="error">Vendor not found</span>', $sOutput);

					else if ($iSize == 0)
						$sOutput = str_replace("[Result]", '<span class="error">Size not found</span>', $sOutput);

					else if ($iStyle == 0)
						$sOutput = str_replace("[Result]", '<span class="error">Style not found</span>', $sOutput);

					$iIndex ++;

					continue;
				}



				$iPoId = getDbValue("id", "tbl_po", "vendor_id='$iVendor' AND brand_id='$Brand' AND order_no LIKE '$sPerfomaNo'");


				// Adding new PO
				if ($iPoId == 0)
				{
					$iPoId = getNextId("tbl_po");


					$sSQL  = "INSERT INTO tbl_po (id, vendor_id, brand_id, order_no, order_status, order_nature, customer_po_no, vpo_no, call_no, terms_of_delivery, place_of_departure, way_of_dispatch, terms_of_payment, size_set, lab_dips, photo_sample, pre_prod_sample, note, quantity, styles, sizes, destinations, shipping_dates, status, created, created_by, modified, modified_by)
										  VALUES ('$iPoId', '$iVendor', '$Brand', '$sPerfomaNo', '', 'B', '$sSalesOrderNo', '$sOrderNo', '', 'N/A', 'N/A', 'N/A', 'N/A', '', '', '', '', '', '$iQuantity', '$iStyle', '$iSize', '$Destination', '$sEtdRequired', 'W', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')";
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
											  VALUES ('$iPoId', '$iStyle', '$fPrice', 'N', '$sEtdRequired', '$Destination', '$sEtdRequired', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')";
						$bFlag = $objDb->execute($sSQL, false);
					}
				}
				
				else
				{
					$sSQL  = "UPDATE tbl_po SET vendor_id      = '$iVendor',
												customer_po_no = '$sSalesOrderNo',
												vpo_no         = '$sOrderNo',
												shipping_dates = '$sEtdRequired',
												modified       = NOW( ),
												modified_by    = '{$_SESSION['UserId']}'
						  WHERE id='$iPoId'";
					$bFlag = $objDb->execute($sSQL, false);
				}


				if ($bFlag == true && $iPoId > 0)
				{
					$iColorId = getDbValue("id", "tbl_po_colors", "po_id='$iPoId' AND style_id='$iStyle' AND color LIKE '$sColor' AND line LIKE '$sLine' AND destination_id='$Destination' AND etd_required='$sEtdRequired'");


					// Adding new Color
					if ($iColorId == 0)
					{
						$iColorId = getNextId("tbl_po_colors");

						$sSQL  = "INSERT INTO tbl_po_colors (id, po_id, color, line, price, style_id, destination_id, etd_required, order_qty, ontime_qty)
													 VALUES ('$iColorId', '$iPoId', '$sColor', '$sLine', '$fPrice', '$iStyle', '$Destination', '$sEtdRequired', '$iQuantity', '0')";
						$bFlag = $objDb->execute($sSQL, false);
					}

					if ($bFlag == true)
					{
						if (getDbValue("COUNT(1)", "tbl_po_quantities", "po_id='$iPoId' AND color_id='$iColorId' AND size_id='$iSize'") > 0)
							$sSQL = "UPDATE tbl_po_quantities SET quantity='$iQuantity' WHERE po_id='$iPoId' AND color_id='$iColorId' AND size_id='$iSize'";

						else
							$sSQL = "INSERT INTO tbl_po_quantities (po_id, color_id, size_id, quantity) VALUES ('$iPoId', '$iColorId', '$iSize', '$iQuantity')";

						$bFlag = $objDb->execute($sSQL, false);
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