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

	@session_start( );
	@ob_start( );

	@ini_set('display_errors', 0);
	@ini_set('log_errors', 0);
	@error_reporting(0);
	
	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);
	@set_time_limit(0);
	
	@putenv("TZ=Asia/Karachi");
	@date_default_timezone_set("Asia/Karachi");
	@ini_set("date.timezone", "Asia/Karachi");
	
	
	$sBaseDir = "C:/wamp/www/portal/";

	@require_once($sBaseDir."requires/configs.php");
	@require_once($sBaseDir."requires/db.class.php");
	@require_once($sBaseDir."requires/common-functions.php");
	@require_once($sBaseDir."requires/PHPMailer/class.phpmailer.php");
	
	
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );	


	print ("START: ".date("h:i A")."<hr />");
	
		
	$iMainBrand     = 367;
	$iMainSeason    = 1897;
	$iSubSeason     = 1898;
	$iCategory      = 1;	
	$iDestination   = 542;
	$iUserId        = 1;
	$iNewPos        = 0;
	$iExistingPos   = 0;
	
	$sBrandsList    = getList("tbl_brands", "code", "id", "parent_id='$iMainBrand'");
	$sSizesList     = getList("tbl_sizes", "LOWER(size)", "id");	
	$sSuppliersList = getList("tbl_vendors", "CONCAT(code, '-', company_code)", "id", "parent_id='0'");	
	$sFactoriesList = getList("tbl_vendors", "LOWER(vendor)", "id", "parent_id='0'");	
	$sNewVendors    = array( );
	$iCheckedPos    = array( );
	$sMcaUsers      = array(1, 2, 3, 319, 709);

	
	$sSQL = "SELECT id FROM tbl_users WHERE user_type='MGF' AND auditor_type='4' AND status='A'";
	$objDb->query($sSQL);
	
	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
		$sMcaUsers[] = $objDb->getField($i, "id");
	
	
	$sMcaUsers         = @implode(",", $sMcaUsers);
	$sLastSupplier     = "";
	$sLastManufacturer = "";
	$sLastFactory      = "";
	$sLastPerfomaNo    = "";
	$sLastStyle        = "";
	$sLastSize         = "";
	$sLastSubBrand     = "";
	$sLastLine         = "";
	$sLastColor        = "";
	$iPoColorSizes     = array( );
	$iPoId             = 0;
	$iColorId          = 0;

	
	$bFlag = $objDb->execute("BEGIN", false);
	$bFlag = $objDb->execute("SET tx_isolation = 'READ-COMMITTED'", false);
	$bFlag = $objDb->execute("SET GLOBAL tx_isolation = 'READ-COMMITTED'", false);
	$bFlag = $objDb->execute("SET GLOBAL innodb_lock_wait_timeout=5000", false);
	$bFlag = $objDb->execute("SET innodb_lock_wait_timeout=5000", false);	
	

	$sCsvFile = "{$sBaseDir}mgf/vpo-list.csv";
	
	if (!@file_exists($sCsvFile))
		die("File Not found");
	
	$hFile   = @fopen($sCsvFile, "r");
	$sRecord = @fgetcsv($hFile, 10000);	
	$iLine   = 1;
	
	
	while (($sRecord = @fgetcsv($hFile, 10000)) !== FALSE)
	{
		$sSupplier     = trim(stripslashes($sRecord[0]));
		$sManufacturer = trim(stripslashes($sRecord[1]));			
		$sFactory      = trim(stripslashes($sRecord[4]));
		$sPerfomaNo    = trim(stripslashes($sRecord[5]));
		$sOrderNo      = trim(stripslashes($sRecord[6]));				
		$sLine         = trim($sRecord[7]);
		$sColorCode    = trim(preg_replace('/(\'|&#0*39;)/', '', @utf8_encode($sRecord[8])));
		$sColorDesc    = preg_replace('/(\'|&#0*39;)/', '', @utf8_encode($sRecord[9]));
		$sColorDesc    = addslashes(trim(stripslashes($sColorDesc)));
		$sSizeCode     = trim($sRecord[10]);
		$sSizeDesc     = trim($sRecord[11]);
		$iQuantity     = intval(str_replace(array(",", " "), "", $sRecord[12]));
		$sSalesOrderNo = trim($sRecord[14]);
		$sStyle        = trim(stripslashes($sRecord[15]));
		$sEtdRequired  = trim($sRecord[17]);
                $sEtdRequired  = date("Y-m-d", strtotime($sEtdRequired));
		$sStyleDesc    = addslashes(trim(stripslashes($sRecord[19])));
		$sVendor       = addslashes(trim(stripslashes($sRecord[20])));
		$sStatus       = addslashes(trim(stripslashes($sRecord[21])));

			
		$sStatus       = ((strtoupper($sStatus) == 'RELEASED') ? 'R' : 'A');
		$sSize         = (($sSizeCode != $sSizeDesc) ? "{$sSizeCode} {$sSizeDesc}" : $sSizeDesc);
		$sColor        = (($sColorCode != $sColorDesc) ? "{$sColorCode} {$sColorDesc}" : $sColorDesc);
			
		
		if ($sSupplier == "" || $sManufacturer == "" || $sFactory == "" || $sOrderNo == "" || $sPerfomaNo == "" || $sEtdRequired == "" || $sStyle == "" || $sColor == "" || $sSize == "" || $iQuantity == 0)
		{
			$iLine ++;

			continue;
		}
		
		

		// Vendor Checking
		if ($sLastSupplier != $sSupplier || $sLastManufacturer != $sManufacturer || $sLastFactory != $sFactory)
		{
			$iFactory = intval($sSuppliersList["{$sSupplier}-{$sManufacturer}"]);
			
			if ($iFactory == 0)	
			{				
				$iFactory = intval($sFactoriesList[strtolower($sFactory)]);
				
				if ($iFactory > 0)
				{
					$sSQL  = "UPDATE tbl_vendors SET code         = '$sSupplier',
					                                 company      = '$sVendor',
													 company_code = '$sManufacturer'
						      WHERE id='$iFactory'";
					$bFlag = $objDb->execute($sSQL, false);
					
					
					$sSuppliersList["{$sSupplier}-{$sManufacturer}"] = $iFactory;
					
					print "{$iLine} - Factory Code Updated - {$sSupplier} - {$sVendor} - {$sManufacturer} - {$sFactory}<br />";
				}
			}
			
			
			if ($iFactory == 0)
			{
				$iFactory = getNextId("tbl_vendors");
				
				
				$sSQL  = "INSERT INTO tbl_vendors (id, sourcing, pcc, parent_id, vendor, code, company, company_code, mgf, levis, global) VALUES ('$iFactory', 'Y', 'N', '0', '$sFactory', '$sSupplier', '$sVendor', '$sManufacturer', 'Y', 'N', 'N')";
				$bFlag = $objDb->execute($sSQL, false);
				
				if ($bFlag == true)
				{
					$sSQL  = "UPDATE tbl_users SET vendors=CONCAT(vendors,',{$iFactory}') WHERE id IN ($sMcaUsers)";
					$bFlag = $objDb->execute($sSQL, false);					
				}
				
			
				$sSuppliersList["{$sSupplier}-{$sManufacturer}"] = $iFactory;
				$sFactoriesList[strtolower($sFactory)]           = $iFactory;
				
				$sNewVendors[] = $sFactory;
				
				print "{$iLine} - New Factory Added - {$sSupplier} - {$sVendor} - {$sManufacturer} - {$sFactory}<br />";
			}
		}
		
		
		if ($bFlag == false)
			break;
		
		
		
		// Brand Checking
		$sSubBrand = substr($sOrderNo, 0, 3);
		
		if ($sLastSubBrand != $sSubBrand)
			$iSubBrand = (int)$sBrandsList[$sSubBrand];
		
		if ($iSubBrand == 0)
		{
			print "{$iLine} - Brand Not Found: {$sSubBrand}<br />";
			
			$sLastSubBrand = $sSubBrand;
			
			continue;
		}
		
		
		
		// Style Checking
		if ($sLastSubBrand != $sSubBrand || $sLastStyle != $sStyle)
		{
			$sSQL = "SELECT id, style_name FROM tbl_styles WHERE brand_id='$iMainBrand' AND sub_brand_id='$iSubBrand' AND sub_season_id='$iSubSeason' AND style LIKE '$sStyle' LIMIT 1";
			$objDb->query($sSQL);
			
			if ($objDb->getCount( ) == 1)
			{
				$iStyle     = $objDb->getField(0, "id");
				$sStyleName = $objDb->getField(0, "style_name");
				
				if ($sStyleName != $sStyleDesc)
				{
					$sSQL  = "UPDATE tbl_styles SET style_name='$sStyleDesc' WHERE id='$iStyle'";
					$bFlag = $objDb->execute($sSQL, false);
				}
			}
			
			else
			{
				$iStyle = getNextId("tbl_styles");


				$sSQL  = "INSERT INTO tbl_styles (id, category_id, style, style_name, reference, brand_id, sub_brand_id, season_id, sub_season_id, program_id, design_no, design_name, carry_over_id, fabric_width, specs_file, sketch_file, measurement_points, sizes, created, created_by, modified, modified_by)
										  VALUES ('$iStyle', '$iCategory', '$sStyle', '$sStyleDesc', '', '$iMainBrand', '$iSubBrand', '$iMainSeason', '$iSubSeason', '1', '', '', '0', '0', '', '', '', '', NOW( ), '$iUserId', NOW( ), '$iUserId')";
				$bFlag = $objDb->execute($sSQL, false);
			}
		}
		
		
		if ($bFlag == false)
			break;		
		
		
		
		// Size Checking
		if ($sLastSize != $sSize)
		{
			$iSize = (int)$sSizesList[strtolower($sSize)];
		
			if ($iSize == 0)
			{
				$iSize = getNextId("tbl_sizes");
			
				$sSQL  = "INSERT INTO tbl_sizes (id, type, size, position) VALUES ('$iSize', 'Size', '$sSize', '$iSize')";
				$bFlag = $objDb->execute($sSQL, false);
				
				
				$sSizesList[strtolower($sSize)] = $iSize;
				
				
				print "{$iLine} - New Size Added - {$sSize}<br />";
			}
			
			
			if ($bFlag == false)
				break;
		}
		
		
		
		// PO Checking
		if ($sLastFactory != $sFactory || $sLastSubBrand != $sSubBrand || $sLastPerfomaNo != $sPerfomaNo)
		{
			if ($iPoId > 0)
			{
				$sSQL  = "DELETE FROM tbl_po_colors WHERE po_id='$iPoId' AND id NOT IN (SELECT DISTINCT(color_id) FROM tbl_po_quantities WHERE po_id='$iPoId')";
				$bFlag = $objDb->execute($sSQL, false);
			
				if ($bFlag == true)
				{
					$sSQL  = "UPDATE tbl_po_colors SET order_qty=(SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id='$iPoId' AND color_id=tbl_po_colors.id) WHERE po_id='$iPoId'";
					$bFlag = $objDb->execute($sSQL, false);
				}
				
				if ($bFlag == true)
				{	
					$sSQL = "SELECT SUM(quantity), GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',') FROM tbl_po_quantities WHERE po_id='$iPoId'";
					$objDb->query($sSQL);
					
					$iPoQuantity = $objDb->getField(0, 0);
					$sPoSizes    = $objDb->getField(0, 1);
					
					
					$sSQL = "SELECT GROUP_CONCAT(DISTINCT(etd_required) SEPARATOR ','), GROUP_CONCAT(DISTINCT(style_id) SEPARATOR ','), GROUP_CONCAT(DISTINCT(destination_id) SEPARATOR ',') FROM tbl_po_colors WHERE po_id='$iPoId'";
					$objDb->query($sSQL);
					
					$sPoDates        = $objDb->getField(0, 0);
					$sPoStyles       = $objDb->getField(0, 1);
					$sPoDestinations = $objDb->getField(0, 2);
					
					
					$sSQL  = "UPDATE tbl_po SET quantity       = '$iPoQuantity',
												shipping_dates = '$sPoDates',
												styles         = '$sPoStyles',
												sizes          = '$sPoSizes',
												destinations   = '$sPoDestinations',
												modified       = NOW( ),
												modified_by    = '$iUserId'
							 WHERE id='$iPoId'";
					$bFlag = $objDb->execute($sSQL, false);
				}
				
				if ($bFlag == false)
					break;
				
				print "Quantity:{$iPoQuantity} - Styles:{$sPoStyles} - Sizes:{$sPoSizes} - ETD:{$sPoDates}<br /><br />";
				
				
				$bFlag = $objDb->execute("COMMIT", false);
				$bFlag = $objDb->execute("BEGIN", false);
			}
			
			
			$iPoColorSizes      = array( );
			$iCheckedColorSizes = array( );
			$iPoId              = (int)getDbValue("id", "tbl_po", "vendor_id='$iFactory' AND brand_id='$iSubBrand' AND order_no LIKE '$sPerfomaNo'");
			
			
			if ($iPoId == 0)
			{
				$iPoId = getNextId("tbl_po");


				$sSQL  = "INSERT INTO tbl_po (id, vendor_id, brand_id, order_no, order_status, order_nature, customer_po_no, vpo_no, mgf_vendor, call_no, terms_of_delivery, place_of_departure, way_of_dispatch, terms_of_payment, size_set, lab_dips, photo_sample, pre_prod_sample, note, quantity, styles, sizes, destinations, shipping_dates, status, mgf_status, created, created_by, modified, modified_by)
									  VALUES ('$iPoId', '$iFactory', '$iSubBrand', '$sPerfomaNo', '', 'B', '$sSalesOrderNo', '$sOrderNo', '$sVendor', '', 'N/A', 'N/A', 'N/A', 'N/A', '', '', '', '', '', '$iQuantity', '$iStyle', '$iSize', '$iDestination', '$sEtdRequired', 'W', '$sStatus', NOW( ), '$iUserId', NOW( ), '$iUserId')";
				$bFlag = $objDb->execute($sSQL, false);

				if ($bFlag == true)
				{
					$sSQL = "INSERT INTO tbl_pre_shipment_advice (po_id, otp) VALUES ('$iPoId', '0')";
					$bFlag = $objDb->execute($sSQL, false);
				}
				
				
				$iCheckedPos[] = $iPoId;
				$iNewPos ++;
				
				
				print "Line: {$iLine} - New PO Added - {$sPerfomaNo}<br />";
			}
			
			else
			{
				if (!@in_array($iPoId, $iCheckedPos))
				{
					$sSQL  = "DELETE FROM tbl_po_quantities WHERE po_id='$iPoId'";
					$bFlag = $objDb->execute($sSQL, false);
/*
					if ($bFlag == true)
					{
						$sSQL  = "DELETE FROM tbl_po_colors WHERE po_id='$iPoId'";
						$bFlag = $objDb->execute($sSQL, false);
					}

					if ($bFlag == true)
					{
						$sSQL  = "UPDATE tbl_po SET quantity       = '0', 
													shipping_dates = '',
													styles         = '',
													sizes          = '',
													destinations   = ''
								  WHERE id='$iPoId'";
						$bFlag = $objDb->execute($sSQL, false);
					}
*/
					
					$iCheckedPos[] = $iPoId;
				}
				
				else
					$iPoColorSizes = getList("tbl_po_quantities", "CONCAT(color_id, '-', size_id)", "quantity", "po_id='$iPoId'");
				
				
				$iExistingPos ++;
				
				
				print "Line: {$iLine} - PO Updated - {$sPerfomaNo}<br />";
			}
			
			
			if ($bFlag == false)
				break;
		}
		
		
		
		// PO Color Checking
		if ($sLastFactory != $sFactory || $sLastSubBrand != $sSubBrand || $sLastPerfomaNo != $sPerfomaNo || $sLastStyle != $sStyle || $sLastColor != $sColor || $sLastLine != $sLine || $sLastEtdRequired != $sEtdRequired)
		{
/*
			if ($iPoId > 0 && $iColorId > 0)
			{
				$sSQL  = "UPDATE tbl_po_colors SET order_qty=(SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id='$iPoId' AND color_id='$iColorId') WHERE id='$iColorId' AND po_id='$iPoId'";
				$bFlag = $objDb->execute($sSQL, false);
				
				if ($bFlag == false)
					break;
			}
*/
			
			$iColorId = (int)getDbValue("id", "tbl_po_colors", "po_id='$iPoId' AND style_id='$iStyle' AND color LIKE '$sColor' AND line LIKE '$sLine' AND destination_id='$iDestination' AND etd_required='$sEtdRequired'");

			if ($iColorId == 0)
			{
				$iColorId = getNextId("tbl_po_colors");

				$sSQL  = "INSERT INTO tbl_po_colors (id, po_id, color, line, price, style_id, destination_id, etd_required, order_qty, ontime_qty)
											 VALUES ('$iColorId', '$iPoId', '$sColor', '$sLine', '0', '$iStyle', '$iDestination', '$sEtdRequired', '$iQuantity', '0')";
				$bFlag = $objDb->execute($sSQL, false);
				
				
				if ($bFlag == false)
					break;
			}
		}
		
		
		if (@array_key_exists("{$iColorId}-{$iSize}", $iPoColorSizes))
		{
			if ($iPoColorSizes["{$iColorId}-{$iSize}"] != $iQuantity)
			{			
				$sSQL  = "UPDATE tbl_po_quantities SET quantity='$iQuantity' WHERE po_id='$iPoId' AND color_id='$iColorId' AND size_id='$iSize'";
				$bFlag = $objDb->execute($sSQL, false);
			}
		}
		
		else
		{
			$sSQL  = "INSERT INTO tbl_po_quantities (po_id, color_id, size_id, quantity) VALUES ('$iPoId', '$iColorId', '$iSize', '$iQuantity')";
			$bFlag = $objDb->execute($sSQL, false);
			
			$iPoColorSizes["{$iColorId}-{$iSize}"] = $iQuantity;
		}
		
			
		if ($bFlag == false)
			break;
		
		
		$sLastSupplier     = $sSupplier;
		$sLastManufacturer = $sManufacturer;
		$sLastFactory      = $sFactory;
		$sLastPerfomaNo    = $sPerfomaNo;
		$sLastStyle        = $sStyle;
		$sLastSize         = $sSize;
		$sLastSubBrand     = $sSubBrand;
		$sLastLine         = $sLine;
		$sLastColor        = $sColor;
		
		$iLine ++;
    }
	
	
	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_po_colors WHERE po_id='$iPoId' AND id NOT IN (SELECT DISTINCT(color_id) FROM tbl_po_quantities WHERE po_id='$iPoId')";
		$bFlag = $objDb->execute($sSQL, false);
	}
	
	
	if ($bFlag == true)
	{
		$sSQL  = "UPDATE tbl_po_colors SET order_qty=(SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id='$iPoId' AND color_id=tbl_po_colors.id) WHERE po_id='$iPoId'";
		$bFlag = $objDb->execute($sSQL, false);
	}
	

	if ($bFlag == true)
	{
		$sSQL = "SELECT SUM(quantity), GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',') FROM tbl_po_quantities WHERE po_id='$iPoId'";
		$objDb->query($sSQL);
		
		$iPoQuantity = $objDb->getField(0, 0);
		$sPoSizes    = $objDb->getField(0, 1);
		
		
		$sSQL = "SELECT GROUP_CONCAT(DISTINCT(etd_required) SEPARATOR ','), GROUP_CONCAT(DISTINCT(style_id) SEPARATOR ','), GROUP_CONCAT(DISTINCT(destination_id) SEPARATOR ',') FROM tbl_po_colors WHERE po_id='$iPoId'";
		$objDb->query($sSQL);
		
		$sPoDates        = $objDb->getField(0, 0);
		$sPoStyles       = $objDb->getField(0, 1);
		$sPoDestinations = $objDb->getField(0, 2);
		
		
		$sSQL  = "UPDATE tbl_po SET quantity       = '$iPoQuantity',
									shipping_dates = '$sPoDates',
									styles         = '$sPoStyles',
									sizes          = '$sPoSizes',
									destinations   = '$sPoDestinations',
									modified       = NOW( ),
									modified_by    = '$iUserId'
				 WHERE id='$iPoId'";
		$bFlag = $objDb->execute($sSQL, false);
		
		
		print "Quantity:{$iPoQuantity} - Styles:{$sPoStyles} - Sizes:{$sPoSizes} - ETD:{$sPoDates}<br /><br />";
	}

	
	
	if ($bFlag == true)
	{
		$objDb->execute("COMMIT", false);

		print "<hr />";
		print "MGF VPOs File Imported successfully<br />";
		print "New POs : {$iNewPos}<br />";
		print "Revised POs : {$iExistingPos}<br />";
		
		
		$sSubBrands = "0";
		
		foreach ($sBrandsList as $sCode => $iBrand)
			$sSubBrands .= ",{$iBrand}";
		
		
		$sSQL = "SELECT order_no FROM tbl_po WHERE quantity='0' AND DATE(modified)=CURDATE( ) AND modified_by='$iUserId' AND brand_id IN ($sSubBrands)";
		$objDb->query($sSQL);
		
		$iCount = $objDb->getCount( );
		
		if ($iCount > 0)
		{
			print "<br /><b>POs with 0 Quantity:</b><br />";
			
			for ($i = 0; $i < $iCount; $i ++)
				print (($i + 1)."- ".$objDb->getField($i, 0)."<br />");
		}
		
		
		if (count($sNewVendors) > 0)
		{
			$sBody  = "Dear User!<br /><br />Following new MGF Vendors have been added in the Portal. Kindly correct the Vendor Country on the Portal.<br />";
			$sBody .= @implode("<br />- ", $sNewVendors);
			$sBody .= "<br />Thanks<br />Triple Tree Solutions Portal";


			$objEmail = new PHPMailer( );

			$objEmail->Subject  = "MGF New Vendors Alert";

			$objEmail->MsgHTML($sBody);
			$objEmail->AddAddress("isaeed@3-tree.com", "Imran Saeed");
			$objEmail->AddAddress("umair.baig@apparelco.com", "Mirza Umair Baig");
			$objEmail->Send( );
		}
	}

	else
	{
		print "Line: {$iLine}<br />";
		print ("<b>".$sSQL."</b><br><br>".mysql_error( ));

		$objDb->execute("ROLLBACK", false);
	}

	
	
	print ("<hr />END: ".date("h:i A")."<br />");
	
	$sBody = ob_get_contents();
	
	
	
	$objEmail = new PHPMailer( );

	$objEmail->Subject = ("MGF VPOs Import Status - ".date("Y-m-d H:i A"));

	$objEmail->MsgHTML($sBody);
	$objEmail->AddAddress("tahir@3-tree.com", "MT Shahzad");
	$objEmail->AddAddress("isaeed@3-tree.com", "Imran Saeed");
	$objEmail->Send( );

	
	@fclose($hFile);
	
	
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>