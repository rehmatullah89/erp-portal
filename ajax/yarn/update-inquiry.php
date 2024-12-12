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

	$Id       = IO::intValue("Id");
	$Quantity = IO::strValue("Quantity");
	$sError   = "";

	$sSQL = "SELECT style_id FROM tbl_yarn_inquiries WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Inquiry ID. Please select the proper Inquiry to Edit.\n";
		exit( );
	}

	else
		$iStyle = $objDb->getField(0, 0);

	if ($Quantity == "")
		$sError .= "- Invalid Quantity\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$objDb->execute("BEGIN");

	$sSQL = "UPDATE tbl_yarn_inquiries SET quantity='$Quantity' WHERE id='$Id'";
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$iBrand   = getDbValue("sub_brand_id", "tbl_styles", "id='$iStyle'");
		$sVendors = getDbValue("vendors", "tbl_brands", "id='$iBrand'");
		$iVendors = @explode(",", $sVendors);

		foreach ($iVendors as $iVendor)
		{
			$sSQL = ("UPDATE tbl_yarn_inquiry_details SET pxp_price     = '".IO::floatValue("Price{$iVendor}_pxp")."',
			                                              uxu_price     = '".IO::floatValue("Price{$iVendor}_uxu")."',
			                                              pxu_price     = '".IO::floatValue("Price{$iVendor}_pxu")."',
			                                              uxp_price     = '".IO::floatValue("Price{$iVendor}_uxp")."',
			                                              response_time = '".IO::strValue("ResponseTime{$iVendor}")."',
			                                              shipment_date = '".IO::strValue("ShipmentDate{$iVendor}")."'
					  WHERE inquiry_id='$Id' AND vendor_id='$iVendor'");
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == false)
				break;
		}
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		print "OK|-|$Id|-|<div>The selected Inquiry has been Updated successfully.</div>|-|$Quantity";
	}

	else
	{
		$objDb->execute("ROLLBACK");

		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>