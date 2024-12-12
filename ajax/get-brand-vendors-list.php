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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Brand	= IO::intValue("Id");
	$List   = IO::strValue("List");

	if ($Brand == 0)
	{
		print "ERROR|-|Invalid Brand. Please select the proper Brand.\n";
		exit;
	}

	
	$UserVendors   = $_SESSION['Vendors'];
	$sBrandVendors = getDbValue("COALESCE(vendors, '')", "tbl_brands", "id='$Brand'");        
	
        $sSQL = "SELECT GROUP_CONCAT(DISTINCT(vendor_id) SEPARATOR ',') FROM tbl_po WHERE brand_id = '$Brand' AND FIND_IN_SET(vendor_id, '$UserVendors')";
	
        if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
	{
            $iVendors = $objDb->getField($i, 0);

            if ($sBrandVendors != "" && @in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
            {
                    $iVendors = @explode(",", $iVendors);
                    $iVendors = @array_merge($iVendors, @explode(",", $sBrandVendors));
                    $iVendors = @array_unique($iVendors);

                    $sBrandVendors = @implode(",", $iVendors);
            }

            else
                    $sBrandVendors = $iVendors;
        }
        
		print ("OK|-|".$List);

                if (@in_array($_SESSION["UserType"], array("LEVIS")))
                    $Vendors = getList("tbl_vendors", "id", "CONCAT(code,' - ', vendor)", "id IN ($sBrandVendors)");
                else
                    $Vendors = getList("tbl_vendors", "id", "vendor", "id IN ($sBrandVendors)");
		
		foreach($Vendors as $iVendor => $sVendor)	
			print ("|-|".$iVendor."||".$sVendor);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>