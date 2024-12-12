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
	

	$iMainBrand   = 367;
	$iMainSeason  = 1897;
	$iSubSeason   = 1898;
	$iCategory    = 1;	
	$iDestination = 542;
	$iUserId      = 1;
	$sCsvFile    = "{$sBaseDir}mgf/vpo-list.csv";
	
	if (!@file_exists($sCsvFile))
		die("File Not found");

	
	$sBrandsList = getList("tbl_brands", "code", "id", "parent_id='$iMainBrand'");
	$sSizesList  = getList("tbl_sizes", "size", "id");
	$sPoColors   = array( );
	$hFile       = @fopen($sCsvFile, "r");
	$sRecord     = @fgetcsv($hFile, 10000);

	
	if (strtoupper(@implode(",", $sRecord)) == "SUPPLIER,MANUFACTURER,INDUSTRY,EDI_IND,PARTY_NAME,PROFOMA_PO,ORDER_NO,LINE_NO,COLOR_CODE,COLOR_DESC,SIZE_CODE,SIZE_DESC,ASSIGNED_QTY,SO_ID,SALES_ORDER_NO,ITEM_NO,BUSINESS,FACTORY_GAC_DATE,CUSTOMER_ID,STYLE_DESC,VENDOR_NAME,STATUS" || 
	    strtoupper(@implode(",", $sRecord)) == "SUPPLIER,MANUFACTURER,INDUSTRY,EDI_IND,PARTY_NAME,PROFOMA_PO,ORDER_NO,LINE_NO,COLOR_CODE,COLOR_DESC,SIZE_CODE,SIZE_DESC,ASSIGNED_QTY,SO_ID,SALES_ORDER_NO,ITEM_NO,BUSINESS,FACTORY_GAC_DATE,CUSTOMER_ID,STYLE_DESC,VENDOR_NAME")
	{
		$iIndex = 1;

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
			$sEtdRequired  = date("Y-m-d", strtotime(trim($sRecord[17])));
			$sStyleDesc    = addslashes(trim(stripslashes($sRecord[19])));
			$sVendor       = addslashes(trim(stripslashes($sRecord[20])));
			$sStatus       = addslashes(trim(stripslashes($sRecord[21])));
			
                       
			if ($sPerfomaNo == "" || $sStyle == "" || $iQuantity == 0)
				continue;

			$sStatus = ((strtoupper($sStatus) == 'RELEASED') ? 'R' : 'A');
			$sSize   = "{$sSizeCode} {$sSizeDesc}";
                        
			if($sColorCode != $sColorDesc)
				$sColor = "{$sColorCode} {$sColorDesc}";
			else
				$sColor = "{$sColorDesc}";

			
			$iFactory = (int)getDbValue("id", "tbl_vendors", "parent_id='0' AND code='$sSupplier' AND company_code='$sManufacturer'");
			
			if ($iFactory == 0)			
				$iFactory = (int)getDbValue("id", "tbl_vendors", "parent_id='0' AND vendor LIKE '$sFactory'");

			
			$sSubBrand = substr($sOrderNo, 0, 3);
			$iSubBrand = (int)$sBrandsList[$sSubBrand];
			$iStyle    = (int)getDbValue("id", "tbl_styles", "brand_id='$iMainBrand' AND sub_brand_id='$iSubBrand' AND sub_season_id='$iSubSeason' AND style LIKE '$sStyle'");
			$iSize     = (int)$sSizesList[$sSize];
			$fPrice    = 0;


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


			
			$bFlag  = $objDb->execute("BEGIN", false);
			$bFlag  = $objDb->execute("SET tx_isolation = 'READ-COMMITTED'", false);
			$bFlag  = $objDb->execute("SET GLOBAL tx_isolation = 'READ-COMMITTED'", false);
			$bFlag  = $objDb->execute("SET GLOBAL innodb_lock_wait_timeout=5000", false);
			$bFlag  = $objDb->execute("SET innodb_lock_wait_timeout=5000", false);

		
			$iPoId = (int)getDbValue("id", "tbl_po", "vendor_id='$iFactory' AND brand_id='$iSubBrand' AND order_no LIKE '$sPerfomaNo'");


			// Adding new PO
			if ($iPoId == 0)
			{
				$iPoId = getNextId("tbl_po");


				$sSQL  = "INSERT INTO tbl_po (id, vendor_id, brand_id, order_no, order_status, order_nature, customer_po_no, vpo_no, mgf_vendor, call_no, terms_of_delivery, place_of_departure, way_of_dispatch, terms_of_payment, size_set, lab_dips, photo_sample, pre_prod_sample, note, quantity, styles, sizes, destinations, shipping_dates, status, mgf_status, created, created_by, modified, modified_by)
									  VALUES ('$iPoId', '$iFactory', '$iSubBrand', '$sPerfomaNo', '', 'B', '$sSalesOrderNo', '$sOrderNo', '$sVendor', '', 'N/A', 'N/A', 'N/A', 'N/A', '', '', '', '', '', '$iQuantity', '$iStyle', '$iSize', '$iDestination', '$sEtdRequired', 'W', '$sStatus', NOW( ), '$iUserId', NOW( ), '$iUserId')";
				$bFlag = $objDb->execute($sSQL);

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
										  VALUES ('$iPoId', '$iStyle', '$fPrice', 'N', '$sEtdRequired', '$iDestination', '$sEtdRequired', NOW( ), '$iUserId', NOW( ), '$iUserId')";
					$bFlag = $objDb->execute($sSQL, false);
				}
			}
			
			else
			{
				$sSQL  = "UPDATE tbl_po SET vendor_id      = '$iFactory',
											brand_id       = '$iSubBrand',
											customer_po_no = '$sSalesOrderNo',
											vpo_no         = '$sOrderNo',
											mgf_vendor     = '$sVendor',
											shipping_dates = '$sEtdRequired',
                                            mgf_status     = '$sStatus',    
											modified       = NOW( ),
											modified_by    = '$iUserId'
					  WHERE id='$iPoId'";
				$bFlag = $objDb->execute($sSQL, false);
			}


			if ($bFlag == true && $iPoId > 0)
			{
				$iColorId = (int)getDbValue("id", "tbl_po_colors", "po_id='$iPoId' AND style_id='$iStyle' AND color LIKE '$sColor' AND line LIKE '$sLine' AND destination_id='$iDestination' AND etd_required='$sEtdRequired'");


				// Adding new Color
				if ($iColorId == 0)
				{
					$iColorId = getNextId("tbl_po_colors");

					$sSQL  = "INSERT INTO tbl_po_colors (id, po_id, color, line, price, style_id, destination_id, etd_required, order_qty, ontime_qty)
												 VALUES ('$iColorId', '$iPoId', '$sColor', '$sLine', '$fPrice', '$iStyle', '$iDestination', '$sEtdRequired', '$iQuantity', '0')";
					$bFlag = $objDb->execute($sSQL);
				}

				if ($bFlag == true)
				{
					if (getDbValue("COUNT(1)", "tbl_po_quantities", "po_id='$iPoId' AND color_id='$iColorId' AND size_id='$iSize'") > 0)
						$sSQL = "UPDATE tbl_po_quantities SET quantity='$iQuantity' WHERE po_id='$iPoId' AND color_id='$iColorId' AND size_id='$iSize'";

					else
						$sSQL = "INSERT INTO tbl_po_quantities (po_id, color_id, size_id, quantity) VALUES ('$iPoId', '$iColorId', '$iSize', '$iQuantity')";

					$bFlag = $objDb->execute($sSQL);
				}

				if ($bFlag == true)
				{
					$sSQL  = "UPDATE tbl_po_colors SET etd_required='$sEtdRequired', order_qty=(SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id='$iPoId' AND color_id='$iColorId') WHERE id='$iColorId'";
					$bFlag = $objDb->execute($sSQL);
				}

/*
				if (!@isset($sPoColors[$iPoId]) || !@is_array($sPoColors[$iPoId]))
					$sPoColors[$iPoId] = array( );

				$sPoColors[$iPoId][] = $iColorId;
*/
			}


//			if ($bFlag == false)
//				break;
			
			
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


			if ($bFlag == true)
			{
				$objDb->execute("COMMIT", false);

				// print "PO Imported Successfully";
			}

			else
			{
				$objEmail = new PHPMailer( );

				$objEmail->Subject  = "MGF Import - Import VPOs - Line # {$iIndex}";

				$objEmail->MsgHTML($sSQL."<br><br>".mysql_error( ));
				$objEmail->AddAddress("tahir@3-tree.com", "MT Shahzad");
				$objEmail->AddAddress("isaeed@3-tree.com", "Imran Saeed");
				$objEmail->Send( );

				
				print $sSQL."<br><br>".mysql_error( );

				$objDb->execute("ROLLBACK", false);
			}
			
			
			$iIndex ++;
			
			@ob_flush( );
		}
		
/*	
		if ($bFlag == true)
		{
			$sBrands = "0";
			
			foreach ($sBrandsList as $sCode => $iBrand)
			{
				$sBrands .= ",{$iBrand}";
			}
			
			
			$sSQL  = "UPDATE tbl_po SET quantity       = (SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id=tbl_po.id),
										shipping_dates = (SELECT GROUP_CONCAT(DISTINCT(etd_required) SEPARATOR ',') FROM tbl_po_colors WHERE po_id=tbl_po.id),
										styles         = (SELECT GROUP_CONCAT(DISTINCT(style_id) SEPARATOR ',') FROM tbl_po_colors WHERE po_id=tbl_po.id),
										sizes          = (SELECT GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',') FROM tbl_po_quantities WHERE po_id=tbl_po.id),
										destinations   = (SELECT GROUP_CONCAT(DISTINCT(destination_id) SEPARATOR ',') FROM tbl_po_colors WHERE po_id=tbl_po.id)
					 WHERE FIND_IN_SET(brand_id, '$sBrands') AND DATE(modified)=CURDATE( ) AND modified_by='$iUserId'";
			$bFlag = $objDb->execute($sSQL, false);
		}


		if ($bFlag == true)
		{
			$objDb->execute("COMMIT", false);

			print "Data Imported Successfully";
		}

		else
		{
			$objEmail = new PHPMailer( );

			$objEmail->Subject  = "MGF Import - Import VPOs";

			$objEmail->MsgHTML($sSQL."<br><br>".mysql_error( ));
			$objEmail->AddAddress("tahir@3-tree.com", "MT Shahzad");
			$objEmail->AddAddress("isaeed@3-tree.com", "Imran Saeed");
			$objEmail->Send( );

			
			print $sSQL."<br><br>".mysql_error( );

			$objDb->execute("ROLLBACK", false);
		}
*/
	}
	
	
	@fclose($hFile);
	
	
	print ("<hr />END: ".date("h:i A")."<br />");
	
	
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>