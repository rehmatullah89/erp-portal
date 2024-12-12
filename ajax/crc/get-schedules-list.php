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
**  Software Engineer:                                                                       **
**                                                                                           **
**      Name  :  Rehmat Ullah			                                             **
**      Email :  rehmatullah@3-tree.com		                                             **
**      Phone :  +92 344 404 3675                                                            **
**      URL   :  http://www.apparelco.com                                                    **
**                                                                                           **
***********************************************************************************************
\*********************************************************************************************/

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	if ($sUserRights['Edit'] != "Y")
	{
		print "ERROR|-|You havn\'t enough Rights to modify the selected Data.\n";
		exit( );
	}

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$VendorId   = IO::intValue("VendorId");
	$List       = IO::strValue("List");

	if ($VendorId == 0)
	{
		print "ERROR|-|Invalid Section. Please select the proper Vendor.\n";
		exit;
	}


	$sSQL = "SELECT id FROM tbl_crc_audits WHERE vendor_id='$VendorId' AND total_score = '0' ORDER By id DESC";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		print ("OK|-|".$List);

                if($iCount > 0)
                {
                    for ($i = 0; $i < $iCount; $i ++)
                            print ("|-|".$objDb->getField($i, 0)."||C".str_pad($objDb->getField($i, 0), 5, 0, STR_PAD_LEFT));
                }else
                    print ("|-|");
	}

	else
		print "ERROR|-|A Database Error occured. Please reload your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>