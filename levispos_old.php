<?
    @require_once("requires/session.php");

        @ini_set('display_errors', 0);
	@ini_set('log_errors', 0);
	@error_reporting(0);
	
	@putenv("TZ=Asia/Karachi");
	@date_default_timezone_set("Asia/Karachi");
	@ini_set("date.timezone", "Asia/Karachi");

	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);

	@ini_set('max_execution_time', 0);
	@set_time_limit(0);
        

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

        $iInsertCount   = 0;
        $SizesInserted  = array();
        $VendorsInserted= array();
        $NotInserted    = array();
        $iCountries     = array();
        
        $iColorId       = 0;
        $sSizesList     =  getList("tbl_sizes", "size", "id");
        $sVendorsList   =  getList("tbl_vendors", "code", "id");
	$sCountries     =  getList("tbl_countries", "country", "id");
        
	foreach($sCountries as $sCountry => $iCountry)
	{
		$iCountries[strtolower($sCountry)] = $iCountry;
	}

	
        $objDb->query("BEGIN");

	$cfile = fopen('levis-pos-old.csv', 'r');
        
	while (($line = fgetcsv($cfile)) !== FALSE) 
	{            
            $iPoId              = 0;
            $sPurchaseOrderNo   = trim(stripslashes($line[0]));
            $iItemNo            = trim(stripslashes($line[1]));
            $sCategory          = trim($line[8]);
            $sSize              = trim(str_replace(["  "], ' ', $line[2]));
            $sSize              = trim(str_replace(["-", "_"], '', $sSize));           
            
            if($sCategory == 'BOTTOMS')
                $iSize              = (int)getDbValue("id", "tbl_sizes", "size LIKE '$sSize' AND `type`='Length'");             
            else
                $iSize              = (int)getDbValue("id", "tbl_sizes", "size LIKE '$sSize' AND `type`='Size'");
            
            $sCreatedDate       = date("Y-m-d", strtotime(trim($line[3])))." 00:00:00";
            $sEtdDate           = "2017-11-30";//date('Y-m-d', strtotime("+1 months", strtotime($sCreatedDate)));
            $sVendorCode        = trim($line[4]);            
            $sBrand             = trim($line[14]);
            $iQuantity          = (int)trim($line[18]);
            $sVendorCountry     = trim($line[7]);            
            $iVendor            = (int)getDbValue("id", "tbl_vendors", "code='$sVendorCode'");
            $sProductCode       = trim($line[5]);
            $sProductKey        = explode("-", trim($line[5]));
            $sStyle             = $sProductKey[0];
            $iCreatedBy         = 721;
            $iMainBrand         = 434; 
            $iSubBrand          = 436; 
            $iStyle             = (int)getDbValue("id", "tbl_styles", "brand_id='$iMainBrand' AND sub_brand_id IN (436,527,528,529) AND style LIKE '$sStyle'");
            
            if($sBrand != 'LEVIS' && $iStyle > 0)
            {
                $iNewSubBrand = 436;
                        
                if($sBrand == 'DENIZEN')
                    $iNewSubBrand = 527;
                else if($sBrand == 'DOCKERS')
                    $iNewSubBrand = 528;
                else if($sBrand == 'SIGNATURE')
                    $iNewSubBrand = 529;
                
                $sSQL = "UPDATE tbl_styles SET sub_brand_id='$iNewSubBrand' WHERE brand_id='$iMainBrand' AND sub_brand_id='$iSubBrand' AND style LIKE '$sStyle'";
                $bFlag = $objDb->execute($sSQL);
                
                $iSubBrand = $iNewSubBrand;
            }
            
            if($iSize == 0 && $sSizesList[$sSize] == "")
            {
                $iSize                  = getNextId("tbl_sizes");               
                $sSizesList[$sSize]     = $iSize;
                $SizesInserted[$iSize]  = $sSize;
                
                $sSQL  = "INSERT INTO tbl_sizes (id, type, size, position) VALUES ('$iSize', 'Size', '$sSize', '$iSize')";
                $bFlag = $objDb->execute($sSQL);                
            }
            else if($iSize == 0 && $sSizesList[$sSize] != "")
                $iSize = $sSizesList[$sSize];
            
            /*if($iVendor == 0 && $sVendorsList[$sVendorCode] == "")
            {
                $iVendor = getNextId("tbl_vendors");
                $sSQL = ("INSERT INTO tbl_vendors (id, sourcing, parent_id, vendor, code, city, category_id, country_id, daily_capacity, levis)
		                           VALUES ('$iVendor', 'Y', '0', 'New Vendor{$iVendor}', '".$sVendorCode."', 'Not Provided', '4', '".$iCountries[strtolower($sVendorCountry)]."', '1000', 'Y')");

                $bFlag = $objDb->execute($sSQL);
                
                if($bFlag == false)
                    break;
            }*/
            
            if($iStyle > 0 && $iSize > 0 && $iVendor>0)
            {
                $PoSize = getDbValue("sizes", "tbl_po", "order_no='$sPurchaseOrderNo'");    
                
                if($PoSize == "")
                {
                    $iPoId = getNextId("tbl_po");
                    $sSQL  = ("INSERT INTO tbl_po (id, product_code, vendor_id, category, brand_id, order_no, item_number, order_status, order_nature, quantity, styles, sizes, shipping_dates, created, created_by, modified, modified_by)
								   VALUES ('$iPoId', '$sProductCode', '$iVendor', '$sCategory', '$iSubBrand', '$sPurchaseOrderNo', '$iItemNo', '', 'B','$iQuantity', '$iStyle', '$iSize', '$sEtdDate', '$sCreatedDate', '$iCreatedBy', NOW( ), '$iCreatedBy')");
                    
                    $bFlag = $objDb->execute($sSQL);
                
                    if($bFlag == true && $iPoId > 0)
                    {
                        $iColorId = getNextId("tbl_po_colors");

                        $sSQL = ("INSERT INTO tbl_po_colors (id, po_id, color, style_id, order_qty, etd_required) VALUES ('$iColorId', '$iPoId', 'STANDARD', '$iStyle', '$iQuantity', '$sEtdDate')");
                        $bFlag = $objDb->execute($sSQL);
                    }
                    
                    if ($bFlag == true && $iPoId > 0)
                    {
                        $sSQL = "INSERT INTO tbl_po_quantities (po_id, color_id, size_id, quantity) VALUES ('$iPoId', '$iColorId', '$iSize', '$iQuantity')";
                        $bFlag = $objDb->execute($sSQL);
                    }
                                
                    if ($bFlag == true && $iPoId > 0)
                    {
                            $sSQL = "INSERT INTO tbl_pre_shipment_advice (po_id, otp) VALUES ('$iPoId', '0')";
                            $bFlag = $objDb->execute($sSQL);
                    }

                    if ($bFlag == true && $iPoId > 0)
                    {
                            $sSQL = "INSERT INTO tbl_post_shipment_advice (po_id) VALUES ('$iPoId')";
                            $bFlag = $objDb->execute($sSQL);
                    }

                    if ($bFlag == true && $iPoId > 0)
                    {
                            $sSQL = "INSERT INTO tbl_vsr (po_id, style_id, price, revised_etd, final_audit_date, created, created_by, modified, modified_by) VALUES ('$iPoId', '$iStyle', '1', '$sEtdDate', '$sEtdDate', NOW( ), '$iCreatedBy', NOW( ), '$iCreatedBy')";
                            $bFlag = $objDb->execute($sSQL);
                    }
                    
                    if($bFlag == false)
                        break;
                }
                else
                {
                    $iPoId          = getDbValue("id", "tbl_po", "order_no='$sPurchaseOrderNo'");
                    $iColorId       = (int)getDbValue("id", "tbl_po_colors", "color='STANDARD' AND po_id='$iPoId' AND style_id='$iStyle'");
                    $iPoQuantity    = (int)getDbValue("quantity", "tbl_po", "order_no='$sPurchaseOrderNo'");
                        
                    if (getDbValue("COUNT(1)", "tbl_po_quantities", "po_id='$iPoId' AND (color_id='$iColorId' OR color_id='0') AND size_id='$iSize'") > 0)
                            $sSQL = "UPDATE tbl_po_quantities SET quantity='$iQuantity', color_id='$iColorId' WHERE po_id='$iPoId' AND color_id='$iColorId' AND size_id='$iSize'";

                    else
                    {
                        if($iColorId == 0)
                        {
                            $iColorId = getNextId("tbl_po_colors");

                            $sSQL = ("INSERT INTO tbl_po_colors (id, po_id, color, style_id, order_qty, etd_required) VALUES ('$iColorId', '$iPoId', 'STANDARD', '$iStyle', '$iQuantity', '$sEtdDate')");
                            $bFlag = $objDb->execute($sSQL);
                        }
                        
                        $sSQL = "INSERT INTO tbl_po_quantities (po_id, color_id, size_id, quantity) VALUES ('$iPoId', '$iColorId', '$iSize', '$iQuantity')";
                    }
                    
                    $bFlag = $objDb->execute($sSQL);
                    
                    /*if ($bFlag == true && $iPoId > 0)
                    {
                        $SumQuantity = $iPoQuantity + $iQuantity;
                        $iPoSizes = ($PoSize.",".$iSize);

                        $sSQL = "UPDATE tbl_po SET sizes='$iPoSizes', quantity='$SumQuantity', product_code='$sProductCode', category='$sCategory', item_number='$iItemNo' WHERE order_no='$sPurchaseOrderNo'";
                        $bFlag = $objDb->execute($sSQL);
                        
                        //UPDATE tbl_po_colors SET order_qty=(SELECT COALESCE(SUM(quantity), 0) FROM tbl_po_quantities WHERE color_id=tbl_po_colors.id) WHERE order_qty='0';
                        //UPDATE tbl_po SET quantity=(SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id=tbl_po.id) WHERE quantity='0';
                        //UPDATE tbl_po SET quantity= (SELECT SUM(quantity) FROM tbl_po_quantities WHERE tbl_po.id=po_id) WHERE created_by ='721' AND modified LIKE '2017-11-03%' AND brand_id IN (436,527,528,529);
                    }*/
                    
                }
                
                if($bFlag == false)
                    break;                
                
                $iInsertCount ++;
            }
            else
            {
                $NotInserted[] = $sPurchaseOrderNo;
            }
         
	}
        
        if($bFlag == true)
        {
            $objDb->query("COMMIT");
            echo "DONE COUNT".$iInsertCount.":POS NOT INSERTED<br/>";
            print_r($NotInserted);
            echo "-------Sizes Inserted -----------<br/>";
            print_r($SizesInserted);
            exit;
        }
        else
        {
            $objDb->query("ROLLBACK");
            echo $sSQL;
            exit;
        }

	fclose($cfile);

	
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>