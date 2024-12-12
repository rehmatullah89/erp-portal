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

	@ini_set('display_errors', 0);
	@ini_set('log_errors', 0);
	@error_reporting(0);
	
	@putenv("TZ=Asia/Karachi");
	@date_default_timezone_set("Asia/Karachi");
	@ini_set("date.timezone", "Asia/Karachi");

	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);


    //$sBaseDir = "../";
	$sBaseDir = "C:/wamp/www/portal/";

	@require_once($sBaseDir."requires/configs.php");
	@require_once($sBaseDir."requires/db.class.php");
	@require_once($sBaseDir."requires/common-functions.php");
	@require_once($sBaseDir."requires/PHPMailer/class.phpmailer.php");

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
	$sCsvFile    = "{$sBaseDir}mgf/vpo-list.csv";
	$iUserId     = 1;
	
	if (!@file_exists($sCsvFile))
		die("File Not found");	

	
	$sBrandsList = getList("tbl_brands", "code", "id", "parent_id='$iMainBrand'");
	$sSizesList  = getList("tbl_sizes", "size", "id");
	$sNewVendors = array( );
	$hFile       = @fopen($sCsvFile, "r");
	$sRecord     = @fgetcsv($hFile, 10000);


	if (strtoupper(@implode(",", $sRecord)) == "SUPPLIER,MANUFACTURER,INDUSTRY,EDI_IND,PARTY_NAME,PROFOMA_PO,ORDER_NO,LINE_NO,COLOR_CODE,COLOR_DESC,SIZE_CODE,SIZE_DESC,ASSIGNED_QTY,SO_ID,SALES_ORDER_NO,ITEM_NO,BUSINESS,FACTORY_GAC_DATE,CUSTOMER_ID,STYLE_DESC,VENDOR_NAME,STATUS" || strtoupper(@implode(",", $sRecord)) == "SUPPLIER,MANUFACTURER,INDUSTRY,EDI_IND,PARTY_NAME,PROFOMA_PO,ORDER_NO,LINE_NO,COLOR_CODE,COLOR_DESC,SIZE_CODE,SIZE_DESC,ASSIGNED_QTY,SO_ID,SALES_ORDER_NO,ITEM_NO,BUSINESS,FACTORY_GAC_DATE,CUSTOMER_ID,STYLE_DESC,VENDOR_NAME")
	{
		$sMcaUsers = array(1, 2, 3, 319, 709);
		
		$sSQL = "SELECT id FROM tbl_users WHERE user_type='MGF' AND auditor_type='4' AND status='A'";
		$objDb->query($sSQL);
		
		$iCount = $objDb->getCount( );
		
		for ($i = 0; $i < $iCount; $i ++)
			$sMcaUsers[] = $objDb->getField($i, "id");
						
						
		
		$bFlag  = $objDb->execute("BEGIN", false);
		$bFlag  = $objDb->execute("SET tx_isolation = 'READ-COMMITTED'", false);
		$bFlag  = $objDb->execute("SET GLOBAL tx_isolation = 'READ-COMMITTED'", false);
		$bFlag  = $objDb->execute("SET GLOBAL innodb_lock_wait_timeout=5000", false);
		$bFlag  = $objDb->execute("SET innodb_lock_wait_timeout=5000", false);
		$iIndex = 0;

		while (($sRecord = @fgetcsv($hFile, 10000)) !== FALSE)
		{
			$sSupplier     = trim(stripslashes($sRecord[0]));
			$sManufacturer = trim(stripslashes($sRecord[1]));
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


			$iFactory = (int)getDbValue("id", "tbl_vendors", "parent_id='0' AND code='$sSupplier' AND company_code='$sManufacturer'");
			
			if ($iFactory == 0)			
				$iFactory = (int)getDbValue("id", "tbl_vendors", "parent_id='0' AND vendor LIKE '$sFactory'");

			$sSubBrand = substr($sOrderNo, 0, 3);
			$iSubBrand = (int)$sBrandsList[$sSubBrand];
			$iStyle    = (int)getDbValue("id", "tbl_styles", "brand_id='$iMainBrand' AND sub_brand_id='$iSubBrand' AND sub_season_id='$iSubSeason' AND style LIKE '$sStyle'");
			$sSize     = "{$sSizeCode} {$sSizeDesc}";
			$iSize     = (int)$sSizesList[$sSize];
			


			// Adding new Factory
			if ($iFactory == 0)
			{
				$iFactory = getNextId("tbl_vendors");
				
				$sSQL  = "INSERT INTO tbl_vendors (id, sourcing, pcc, parent_id, vendor, code, company, company_code) VALUES ('$iFactory', 'Y', 'N', '0', '$sFactory', '$sSupplier', '$sVendor', '$sManufacturer')";
				$bFlag = $objDb->execute($sSQL);
				
				if ($bFlag == false)
					break;
				

				$sNewVendors[] = $sFactory;
			
				foreach ($sMcaUsers as $iUser)
				{
					$sVendors = getDbValue("vendors", "tbl_users", "id='$iUser'");
				

					$sSQL  = "UPDATE tbl_users SET vendors='{$sVendors},{$iFactory}' WHERE id='$iUser'";
					$bFlag = $objDb->execute($sSQL);

					if ($bFlag == false)
						break;
				}
				
				if ($bFlag == false)
					break;
			}
			
			else
			{
				if (getDbValue("COUNT(1)", "tbl_vendors", "id='$iFactory' AND (code='' OR ISNULL(code) OR company='' OR ISNULL(company) OR company_code='' OR ISNULL(company_code))") == 1)
				{
					$sSQL  = "UPDATE tbl_vendors SET code         = '$sSupplier',
					                                 company      = '$sVendor',
													 company_code = '$sManufacturer'
						      WHERE id='$iFactory'";
					$bFlag = $objDb->execute($sSQL);
					
					if ($bFlag == false)
						break;
				}
			}
			
			if ($bFlag == false)
				break;

							
			// Adding new Size
			if ($iSize == 0)
			{
				$iSize 				= getNextId("tbl_sizes");
				$sSizesList[$sSize] = $iSize;
			
				$sSQL  = "INSERT INTO tbl_sizes (id, type, size, position) VALUES ('$iSize', 'Size', '$sSize', '$iSize')";
				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == false)
					break;
			}


			// Adding new Style
			if ($iStyle == 0 && $iSubBrand > 0)
			{
				$iStyle = getNextId("tbl_styles");


				$sSQL  = "INSERT INTO tbl_styles (id, category_id, style, style_name, reference, brand_id, sub_brand_id, season_id, sub_season_id, program_id, design_no, design_name, carry_over_id, fabric_width, specs_file, sketch_file, measurement_points, sizes, created, created_by, modified, modified_by)
										  VALUES ('$iStyle', '$iCategory', '$sStyle', '$sStyleDesc', '', '$iMainBrand', '$iSubBrand', '$iMainSeason', '$iSubSeason', '1', '', '', '0', '0', '', '', '', '', NOW( ), '$iUserId', NOW( ), '$iUserId')";
				$bFlag = $objDb->execute($sSQL);

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
				$sSQL  = "UPDATE tbl_styles SET style_name='$sStyleDesc' WHERE id='$iStyle'";
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
			
			if ($bFlag == false)
				break;



			$iPoId = (int)getDbValue("id", "tbl_po", "vendor_id='$iFactory' AND brand_id='$iSubBrand' AND order_no LIKE '$sPerfomaNo'");

			if ($iPoId > 0)
			{
				$sSQL  = "DELETE FROM tbl_po_quantities WHERE po_id='$iPoId'";
				$bFlag = $objDb->execute($sSQL, false);
				
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
			}
			
			if ($bFlag == false)
				break;
			
			$iIndex ++;
		}


		
		if ($bFlag == true)
		{
			$objDb->execute("COMMIT", false);

			print "Checked Successfully";
			
			
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
			$objEmail = new PHPMailer( );

			$objEmail->Subject  = "MGF Import - Check VPOs";

			$objEmail->MsgHTML($sSQL."<br><br>".mysql_error( ));
			$objEmail->AddAddress("tahir@3-tree.com", "MT Shahzad");
			$objEmail->AddAddress("isaeed@3-tree.com", "Imran Saeed");
			$objEmail->Send( );

				
			print $sSQL."<br><br>".mysql_error( );

			$objDb->execute("ROLLBACK", false);
		}
	}
	
	else
	{
		print "Invalid Format";
		exit( );
	}
	
	
	
	
	@fclose($hFile);
	
	
	print ("<hr />END: ".date("h:i A")."<br />");
	
	
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>