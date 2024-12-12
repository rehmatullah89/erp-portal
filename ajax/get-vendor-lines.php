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

	$Id   = IO::intValue("Id");
	$List = IO::strValue("List");

	if ($Id == 0)
	{
		print "ERROR|-|Invalid Vendor. Please select the proper Vendor.\n";
		exit;
	}


	if ($Id == 13)
		$sUnitSQL = " AND unit_id='259' ";


	$sSQL = "SELECT id, line, unit_id FROM tbl_lines WHERE vendor_id='$Id' $sUnitSQL ORDER BY line";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );


		$sUnitsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='$Id' AND sourcing='Y'");

		print ("OK|-|".$List);

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iUnit = $objDb->getField($i, 2);

			print ("|-|".$objDb->getField($i, 0)."||".$objDb->getField($i, 1).(($iUnit > 0 && $Id != 13) ? " ({$sUnitsList[$iUnit]})" : ""));
		}
	}

	else
		print "ERROR|-|A Database Error occured. Please reload your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>