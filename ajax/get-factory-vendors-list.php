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

	$Factory    = IO::intValue("Id");
	$List       = IO::strValue("List");

	if ($Factory == 0)
	{
		print "ERROR|-|Invalid Brand. Please select the proper Factory.\n";
		exit;
	}

	$UserVendors   = $_SESSION['Vendors'];
	$FactoryVendors = getDbValue("vendors", "tbl_factories", "id='$Factory'");
        
        $sVendors = "";
        $sSQL = "SELECT DISTINCT vendor_id FROM tbl_po WHERE FIND_IN_SET(vendor_id, '$UserVendors') AND FIND_IN_SET(vendor_id, '$FactoryVendors')";
	
        if ($objDb->query($sSQL) == true && $objDb->getCount( ) > 0)
	{
            for($i=0; $i<$objDb->getCount( ); $i++)
            {
                $iVendors = $objDb->getField($i, 0);
                $sVendors .= ($iVendors.",");          
            }
        }
        
        $sBrandVendors = rtrim($sVendors, ',');
        
        $Vendors = getList("tbl_vendors", "id", "vendor", "id IN ($sBrandVendors)");

        print ("OK|-|".$List);
        foreach($Vendors as $iVendor => $sVendor)	
                print ("|-|".$iVendor."||".$sVendor);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>