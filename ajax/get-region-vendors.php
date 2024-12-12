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

	$Region = IO::intValue("Id");
	$List   = IO::strValue("List");

	if ($Region == 0)
	{
		print "ERROR|-|Invalid Region. Please select the proper Region.\n";
		exit;
	}

        $sJcrewVendors = getDbValue("vendors", "tbl_brands", "id='526'");

	$sSQL = "SELECT id, vendor FROM tbl_vendors WHERE id IN ($sJcrewVendors) AND id IN ({$_SESSION['Vendors']}) AND country_id='$Region' AND parent_id='0' AND sourcing='Y' ORDER BY vendor";
        
        if ($objDb->query($sSQL) == true)
        {
            $iCount = $objDb->getCount( );

            print ("OK|-|".$List);

            for ($i = 0; $i < $iCount; $i ++)
                    print ("|-|".$objDb->getField($i, 0)."||".$objDb->getField($i, 1));
        }
	else
		print "ERROR|-|A Database Error occured. Please reload your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>