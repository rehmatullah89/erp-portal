<?
	@require_once("requires/session.php");

	@ini_set('max_execution_time', 0);
	@set_time_limit(0);
	
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	print ("START: ".date("h:i A")."<hr />");
	

	$iMainSeason = 1897;
	$iSubSeason  = 1898;
	$iCategory   = 1;	
	$iMainBrand  = 367;
	$sCsvFile    = "vpo-list.csv";
	$iUserId     = 1;

	
	$sBrandsList = getList("tbl_brands", "code", "id", "parent_id='$iMainBrand'");
	$sSizesList  = getList("tbl_sizes", "size", "id");
	$hFile       = @fopen($sCsvFile, "r");
	$sRecord     = @fgetcsv($hFile, 10000);


	if (strtoupper(@implode(",", $sRecord)) != "SUPPLIER,MANUFACTURER,INDUSTRY,EDI_IND,PARTY_NAME,PROFOMA_PO,ORDER_NO,LINE_NO,COLOR_CODE,COLOR_DESC,SIZE_CODE,SIZE_DESC,ASSIGNED_QTY,SO_ID,SALES_ORDER_NO,ITEM_NO,BUSINESS,FACTORY_GAC_DATE,CUSTOMER_ID,STYLE_DESC,VENDOR_NAME")
	{
		print "Invalid Format";
		exit( );
	}
	
	else
	{
		$bFlag  = $objDb->execute("BEGIN", false);
		$iIndex = 0;

		while (($sRecord = @fgetcsv($hFile, 10000)) !== FALSE)
		{
			$sFactory      = trim(stripslashes($sRecord[4]));
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
			$sStyleDesc    = addslashes(utf8_encode(trim(stripslashes($sRecord[19]))));
			$sVendor       = addslashes(utf8_encode(trim(stripslashes($sRecord[20]))));
			
			
			if ($sPerfomaNo == "" || $sStyle == "" || $iQuantity == 0)
				continue;


			$iFactory  = (int)getDbValue("id", "tbl_vendors", "parent_id='0' AND vendor LIKE '$sFactory'");
			$sSubBrand = substr($sOrderNo, 0, 3);
			$iSubBrand = (int)$sBrandsList[$sSubBrand];
			$iStyle    = (int)getDbValue("id", "tbl_styles", "sub_brand_id='$iSubBrand' AND sub_season_id='$iSubSeason' AND style LIKE '$sStyle'");
			$sSize     = "{$sSizeCode} {$sSizeDesc}";
			$iSize     = (int)$sSizesList[$sSize];
			


			// Adding new Factory
			if ($iFactory == 0)
			{
				$iFactory = getNextId("tbl_vendors");
				
				$sSQL  = "INSERT INTO tbl_vendors (id, sourcing, parent_id, vendor) VALUES ('$iFactory', 'Y', '0', '$sFactory')";
				$bFlag = $objDb->execute($sSQL, false);
				
				if ($bFlag == false)
					break;
			}

							
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
			if ($iStyle == 0 && $iSubBrand > 0)
			{
				$iStyle = getNextId("tbl_styles");


				$sSQL  = "INSERT INTO tbl_styles (id, category_id, style, style_name, reference, brand_id, sub_brand_id, season_id, sub_season_id, program_id, design_no, design_name, carry_over_id, fabric_width, specs_file, sketch_file, measurement_points, sizes, created, created_by, modified, modified_by)
										  VALUES ('$iStyle', '$iCategory', '$sStyle', '$sStyleDesc', '', '$iMainBrand', '$iSubBrand', '$iMainSeason', '$iSubSeason', '1', '', '', '0', '0', '', '', '', '', NOW( ), '$iUserId', NOW( ), '$iUserId')";
				$bFlag = $objDb->execute($sSQL, false);

				if ($bFlag == true)
				{
					$iLogId = getNextId("tbl_style_log");

					$sSQL  = "INSERT INTO tbl_style_log (id, style_id, user_id, date_time, reason, remarks, specs_file) VALUES ('$iLogId', '$iStyle', '$iUserId', NOW( ), 'D', 'Style Entry', '')";
					$bFlag = $objDb->execute($sSQL, false);
				}

				if ($bFlag == false)
					break;
			}

			else if ($iStyle > 0 && $iSubBrand > 0)
			{
				$sSQL  = "UPDATE tbl_styles SET sub_brand_id='$iSubBrand', style_name='$sStyleDesc' WHERE id='$iStyle'";
				$bFlag = $objDb->execute($sSQL, false);
			}


			
			if ($iFactory == 0)
			{
				print "{$iIndex} - Factory: {$sFactory} Not found<br />";
				
				continue;
			}
			
			if ($iSubBrand == 0)
			{
				print "{$iIndex} - Brand: {$sSubBrand} Not found<br />";
				
				continue;
			}			
			
			if ($iStyle == 0)
			{
				print "{$iIndex} - Style: {$sStyle} Not found<br />";
				
				continue;
			}			
		
			if ($iSize == 0)
			{
				print "{$iIndex} - Size: {$sSize} Not found<br />";
				
				continue;
			}



			$iPoId = (int)getDbValue("id", "tbl_po", "vendor_id='$iFactory' AND brand_id='$iSubBrand' AND order_no LIKE '$sPerfomaNo'");

			if ($iPoId > 0)
			{
				$sSQL  = "DELETE FROM tbl_po_quantities WHERE po_id='$iPoId'";
				$bFlag = $objDb->execute($sSQL, false);
			}

			if ($bFlag == false)
				break;
			
			$iIndex ++;
		}


		
		if ($bFlag == true)
		{
			$objDb->execute("COMMIT", false);

			print "Checked Successfully";
		}

		else
		{
			print $sSQL."<br><br>".mysql_error( );

			$objDb->execute("ROLLBACK", false);
		}
	}
	
	
	@fclose($hFile);
	
	
	print ("<hr />END: ".date("h:i A")."<br />");
	
	
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>