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

        $sVendors = array();
	$UserVendors   = $_SESSION['Vendors'];
	
        $sSQL = "SELECT DISTINCT vendor_id FROM tbl_po WHERE brand_id = '$Brand' AND FIND_IN_SET(vendor_id, '$UserVendors')";
	
        if ($objDb->query($sSQL) == true && $objDb->getCount( ) > 0)
	{
            for($i=0; $i<$objDb->getCount( ); $i++)
            {
                $iVendor = $objDb->getField($i, 0);
                $sVendors[$iVendor] = $iVendor;          
            }
        }
       
        $Factories = array();
        print ("OK|-|".$List);
        
        foreach($sVendors as $iVendor)
        {
            $sSQL = "SELECT id, parent FROM tbl_factories WHERE FIND_IN_SET('$iVendor', vendors) ORDER BY parent";
            $objDb->query($sSQL);
            
            if($objDb->getField(0, 1) != "")
                $Factories[$objDb->getField(0, 0)] = $objDb->getField(0, 1);              
        }
       
        foreach($Factories as $iFactory => $sFactory)
            print ("|-|".$iFactory."||".$sFactory);  

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>