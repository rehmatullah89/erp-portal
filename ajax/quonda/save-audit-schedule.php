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
	$Auditor    = IO::intValue("Auditor");
	$Group      = IO::intValue("Group");
	$Vendor     = IO::intValue("Vendor");
	$Report     = IO::intValue("Report");
	$Line       = IO::intValue("Line");
	$AuditDate  = IO::strValue("AuditDate");
	$StartTime  = (((IO::strValue("StartAmPm") == "PM" && IO::intValue("StartHour") < 12) ? (IO::intValue("StartHour") + 12) : str_pad(IO::strValue("StartHour"), 2, '0', STR_PAD_LEFT)).":".str_pad(IO::strValue("StartMinutes"), 2, '0', STR_PAD_LEFT).":00");
	$EndTime    = (((IO::strValue("EndAmPm") == "PM" && IO::intValue("EndHour") < 12) ? (IO::intValue("EndHour") + 12) : str_pad(IO::strValue("EndHour"), 2, '0', STR_PAD_LEFT)).":".str_pad(IO::strValue("EndMinutes"), 2, '0', STR_PAD_LEFT).":00");
	$OrderNo    = IO::intValue("OrderNo");
	$StyleNo    = IO::intValue("StyleNo");
	$Colors     = IO::strValue("Colors");
	$SampleSize = IO::intValue("SampleSize");
	$sError     = "";


	if ($Auditor > 0)
	{
		$sSQL = "SELECT name, email, mobile FROM tbl_users WHERE id='$Auditor'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Auditor\n";

		else
		{
			$sName   = $objDb->getField(0, "name");
			$sEmail  = $objDb->getField(0, "email");
			$sMobile = $objDb->getField(0, "mobile");
		}
	}


	if ($Group > 0)
	{
		$sSQL = "SELECT users FROM tbl_auditor_groups WHERE id='$Group'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Auditors Group\n";

		else
			$sGroup = $objDb->getField(0, 0);
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

	if ($AuditDate == "")
		$sError .= "- Invalid Audit Date\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}



	$iId = getNextId("tbl_audit_schedules");

	$sSQL = "INSERT INTO tbl_audit_schedules SET id          = '$iId',
	                                             user_id     = '$Auditor',
	                                             group_id    = '$Group',
	                                             vendor_id   = '$Vendor',
	                                             style_id    = '$StyleNo',
	                                             po_id       = '$OrderNo',
	                                             report_id   = '$Report',
	                                             line_id     = '$Line',
	                                             audit_date  = '$AuditDate',
	                                             start_time  = '$StartTime',
	                                             end_time    = '$EndTime',
	                                             colors      = '$Colors',
	                                             sample_size = '$SampleSize',
	                                             created     = NOW( ),
	                                             created_by  = '{$_SESSION['UserId']}',
	                                             modified    = NOW( ),
	                                             modified_by = '{$_SESSION['UserId']}'";
	if ($objDb->execute($sSQL) == true)
	{
		$StartTime = (IO::strValue("StartHour").":".IO::strValue("StartMinutes")." ".IO::strValue("StartAmPm"));
		$EndTime   = (IO::strValue("EndHour").":".IO::strValue("EndMinutes")." ".IO::strValue("EndAmPm"));


		$sPo    = getDbValue("CONCAT(order_no, ' ', order_status)", "tbl_po", "id='$OrderNo'");
		$iBrand = getDbValue("brand_id", "tbl_po", "id='$OrderNo'");

		if ($StyleNo > 0)
			$sStyle = (", Style:".getDbValue("style", "tbl_styles", "id='$StyleNo'"));


		$sBody     = "Audit Schedule Alert: PO: {$sPo} {$sStyle} in {$sVendor} on {$AuditDate}";
		$sSubject  = "Audit Schedule Alert";
		$sManagers = array( );


		// email + sms to auditor/managers
		$objEmail = new PHPMailer( );

		$objEmail->Subject = $sSubject;
		$objEmail->Body    = $sBody;

		$objEmail->IsHTML(false);

		if ($sEmail != "")
		{
			$objEmail->AddAddress($sEmail, $sName);
			$objEmail->AddAddress("adil@apparelco.com", "Adil Saleem");
			$objEmail->AddAddress("islam@apparelco.com", "Muhammad Islam");

			if ($sGroup != "")
			{
				$sSQL = "SELECT name, email FROM tbl_users WHERE id IN ($sGroup) AND id!='$Auditor' AND status='A' AND email_alerts='Y'";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					$sMemberName  = $objDb->getField($i, "name");
					$sMemberEmail = $objDb->getField($i, "email");

					$objEmail->AddAddress($sMemberEmail, $sMemberName);
				}
			}


			$sSQL = "SELECT u.name, u.email, u.mobile
			         FROM tbl_users u, tbl_departments d
			         WHERE u.status='A' AND u.AND email_alerts='Y' AND FIND_IN_SET(u.id, d.quality_managers) AND FIND_IN_SET('$iBrand', d.brands)
			               AND FIND_IN_SET('$iBrand', u.brands) AND FIND_IN_SET('$Vendor', u.vendors)
			         ORDER BY u.name";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$sManagerName   = $objDb->getField($i, "name");
				$sManagerEmail  = $objDb->getField($i, "email");
				$sManagerMobile = $objDb->getField($i, "mobile");

				$objEmail->AddAddress($sManagerEmail, $sManagerName);

				$sManagers[] = $sManagerMobile;
			}

			$objEmail->Send( );
		}


		$objSms = new Sms( );
		$objSms->send($sMobile, "", $sBody, $sSubject);
//		$objSms->send("923224970299", "", $sBody, $sSubject);
//		$objSms->send("923008458810", "", $sBody, $sSubject);

		foreach ($sManagers as $sMobile)
		{
			if ($sMobile != "+923008458810")
				$objSms->send($sMobile, "", $sBody, $sSubject);
		}

		$objSms->close( );


		print "OK|-|$Id|-|<div>The specified Audit Schedule has been Saved successfully.</div>";
	}

	else
		print "ERROR|-|{$sSQL} - ".mysql_error( )."A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>