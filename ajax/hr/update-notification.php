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
	$Department = IO::intValue("Department");
	$Trigger    = IO::intValue("Trigger");
	$Vendor     = IO::intValue("Vendor");
	$Brand      = IO::intValue("Brand");
	$AlertTypes = @implode(",", IO::getArray("AlertTypes"));
	$sError = "";

	$sSQL = "SELECT id FROM tbl_notifications WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Notification ID. Please select the proper Notification to Edit.\n";
		exit( );
	}

	if ($Department == 0)
		$sError .= "- Invalid Department\n";

	if ($Department > 0)
	{
		$sSQL = "SELECT department FROM tbl_departments WHERE id='$Department'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Department\n";

		else
			$sDepartment = $objDb->getField(0, 0);
	}

	if ($Trigger == 0)
		$sError .= "- Invalid Trigger\n";

	if ($Trigger > 0)
	{
		$sSQL = "SELECT `trigger` FROM tbl_notification_triggers WHERE id='$Trigger' AND department_id='$Department'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Trigger\n";

		else
			$sTrigger = $objDb->getField(0, 0);
	}

	if ($Vendor == 0 && $Brand == 0)
		$sError .= "- Invalid Vendor / Brand\n";

	if ($Vendor > 0)
	{
		$sSQL = "SELECT vendor FROM tbl_vendors WHERE id='$Vendor'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Vendor\n";

		else
			$sVendor = $objDb->getField(0, 0);
	}

	if ($Brand > 0)
	{
		$sSQL = "SELECT brand FROM tbl_brands WHERE id='$Brand'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Brand\n";

		else
			$sBrand = $objDb->getField(0, 0);
	}

	if ($AlertTypes == "")
		$sError .= "- Invalid Alert Types\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sAlertTypes = "";

	$sSQL = "SELECT `type` FROM tbl_notification_types WHERE id IN ($AlertTypes)";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sAlertTypes .= (", ".$objDb->getField($i, 0));

	$sAlertTypes = substr($sAlertTypes, 2);


	$sSQL  = "SELECT * FROM tbl_notifications WHERE user_id='{$_SESSION['UserId']}' AND department_id='$Department' AND trigger_id='$Trigger' AND vendor_id='$Vendor' AND brand_id='$Brand' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_notifications SET department_id='$Department', trigger_id='$Trigger', vendor_id='$Vendor', brand_id='$Brand', alert_types='$AlertTypes' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
				print "OK|-|$Id|-|<div>The selected Notification has been Updated successfully.</div>|-|$sDepartment|-|$sTrigger|-|$sVendor|-|$sBrand|-|$sAlertTypes";

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Notification already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>