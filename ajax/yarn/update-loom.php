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

	$Id         = IO::intValue("Id");
	$Vendor     = IO::intValue("Vendor");
	$Type       = IO::intValue("Type");
	$Loom       = IO::strValue("Loom");
	$Efficiency = IO::intValue("Efficiency");
	$sError     = "";

	$sSQL = "SELECT id FROM tbl_looms WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Loom ID. Please select the proper Loom to Edit.\n";
		exit( );
	}

	if ($Vendor > 0)
	{
		$sSQL = "SELECT vendor FROM tbl_vendors WHERE id='$Vendor'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Vendor\n";

		else
			$sVendor = $objDb->getField(0, 0);
	}

	if ($Type > 0)
	{
		$sSQL = "SELECT type FROM tbl_loom_types WHERE id='$Type'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Loom Type\n";

		else
			$sType = $objDb->getField(0, 0);
	}

	if ($Loom == "")
		$sError .= "- Invalid Loom\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}

	$sSQL  = "SELECT * FROM tbl_looms WHERE loom LIKE '$Loom' AND loom_type_id='$Type' AND vendor_id='$Vendor' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_looms SET loom_type_id='$Type', vendor_id='$Vendor', loom='$Loom', efficiency='$Efficiency' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
				print "OK|-|$Id|-|<div>The selected Loom has been Updated successfully.</div>|-|$Loom|-|$sType|-|$sVendor|-|{$Efficiency}%";

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Vendor/Type/Loom already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>