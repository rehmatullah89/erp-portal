<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	$Id          = IO::intValue("Id");
	$Region      = IO::strValue("Region");
	$Brand       = IO::intValue("Brand");
	$Destination = IO::strValue("Destination");
	$Type        = IO::strValue("Type");
        $Port        = IO::intValue("Port");
     	$BlockNo     = IO::intValue("BlockNo");
	$sError      = "";

	$sSQL = "SELECT id FROM tbl_destinations WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Destination ID. Please select the proper Destination to Edit.\n";
		exit( );
	}

	if ($Region == "")
		$sError .= "- Invalid Region\n";

	if ($Brand > 0)
	{
		$sSQL = "SELECT brand FROM tbl_brands WHERE id='$Brand'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Brand\n";

		else
			$sBrand = $objDb->getField(0, 0);
	}

	else
		$sError .= "- Invalid Brand\n";

	if ($Destination == "")
		$sError .= "- Invalid Destination\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}

	$sSQL  = "SELECT * FROM tbl_destinations WHERE destination LIKE '$Destination' AND region='$Region' AND brand_id='$Brand' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
                        
                        $sSQL = "UPDATE tbl_destinations SET region='$Region', brand_id='$Brand', destination='$Destination', type='$Type', port_id='$Port', block_no='$BlockNo' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
                            print ("OK|-|$Id|-|<div>The selected Destination has been Updated successfully.</div>|-|$Destination|-|$Region|-|$sBrand|-|".(($Type == "D") ? "Direct" : "Warehouse"));
                        else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Destination (with same Brand & Region) already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>