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

	@require_once("../../requires/session.php");

	if ($sUserRights['Edit'] != "Y")
	{
		print "ERROR|-|You havn\'t enough Rights to modify the selected Data.\n";
		exit( );
	}

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id     = IO::intValue("Id");
	$Line   = IO::strValue("Line");
	$Vendor = IO::intValue("Vendor");
	$Unit   = IO::intValue("Unit");
	$Floor  = IO::intValue("Floor");
	$Type   = IO::intValue("Type");
	$sError = "";

	$sSQL = "SELECT id, vendor_id FROM tbl_lines WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Line ID. Please select the proper Line to Edit.\n";
		exit( );
	}
/*
	if ($Vendor == 13 && $objDb->getField(0, "vendor_id") != 13)
		$sError .= "- You cannot Create a Line in selected Vendor\n";
*/
	if ($Vendor > 0)
	{
		$sSQL = "SELECT vendor FROM tbl_vendors WHERE id='$Vendor'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Vendor\n";

		else
			$sVendor = $objDb->getField(0, 0);
	}

	if ($Unit > 0)
	{
		$sSQL = "SELECT vendor FROM tbl_vendors WHERE id='$Unit'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Unit\n";

		else
			$sUnit = $objDb->getField(0, 0);
	}

	if ($Floor > 0)
	{
		$sSQL = "SELECT floor FROM tbl_floors WHERE id='$Floor'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Floor\n";

		else
			$sFloor = $objDb->getField(0, 0);
	}

	if ($Type > 0)
	{
		$sSQL = "SELECT type FROM tbl_line_types WHERE id='$Type'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Type\n";

		else
			$sType = $objDb->getField(0, 0);
	}

	if ($Line == "")
		$sError .= "- Invalid Line\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}

	$sSQL  = "SELECT * FROM tbl_lines WHERE line LIKE '$Line' AND vendor_id='$Vendor' AND unit_id='$Unit' AND floor_id='$Floor' AND type_id='$Type' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_lines SET vendor_id='$Vendor', unit_id='$Unit', floor_id='$Floor', type_id='$Type', line='$Line' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
				print "OK|-|$Id|-|<div>The selected Line has been Updated successfully.</div>|-|$Line|-|$sVendor|-|$sUnit|-|$sFloor|-|$sType";

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Line (with same Vendor/Unit) already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>