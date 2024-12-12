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

	$Id        = IO::intValue("Id");
	$Auditor   = IO::intValue("Auditor");
	$Group     = IO::intValue("Group");
	$Vendor    = IO::intValue("Vendor");
	$Report    = IO::intValue("Report");
	$Line      = IO::intValue("Line");
	$AuditDate = IO::strValue("AuditDate");
	$StartTime = (((IO::strValue("StartAmPm") == "PM" && IO::intValue("StartHour") < 12) ? (IO::intValue("StartHour") + 12) : IO::strValue("StartHour")).":".IO::strValue("StartMinutes").":00");
	$EndTime   = (((IO::strValue("EndAmPm") == "PM" && IO::intValue("EndHour") < 12) ? (IO::intValue("EndHour") + 12) : IO::strValue("EndHour")).":".IO::strValue("EndMinutes").":00");
	$sError    = "";
	$sGroup    = "";

	$sSQL = "SELECT id, po_id, style_id FROM tbl_audit_schedules WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Schedule ID. Please select the proper Audit to Edit.\n";
		exit( );
	}

	else
	{
		$iPoId    = $objDb->getField(0, "po_id");
		$iStyleId = $objDb->getField(0, "style_id");
	}


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

	if ($Line > 0)
	{
		$sSQL = "SELECT line FROM tbl_lines WHERE vendor_id='$Vendor' AND id='$Line'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Line\n";

		else
			$sLine = $objDb->getField(0, 0);
	}

	if ($AuditDate == "")
		$sError .= "- Invalid Audit Date\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}



	$sSQL = "UPDATE tbl_audit_schedules SET user_id='$Auditor', group_id='$Group', vendor_id='$Vendor', report_id='$Report', line_id='$Line', audit_date='$AuditDate', start_time='$StartTime', end_time='$EndTime', colors='".IO::strValue("Colors")."', sample_size='".IO::intValue("SampleSize")."' WHERE id='$Id'";

	if ($objDb->execute($sSQL) == true)
	{
		$StartTime = (IO::strValue("StartHour").":".IO::strValue("StartMinutes")." ".IO::strValue("StartAmPm"));
		$EndTime   = (IO::strValue("EndHour").":".IO::strValue("EndMinutes")." ".IO::strValue("EndAmPm"));


		$sPo = getDbValue("CONCAT(order_no, ' ', order_status)", "tbl_po", "id='$iPoId'");

		if ($iStyleId > 0)
			$sStyle = (", Style:".getDbValue("style", "tbl_styles", "id='$iStyleId'"));



		$sBody    = "Audit Schedule Revised: PO: {$sPo} {$sStyle} in $sVendor Line $sLine from $StartTime to $EndTime on $AuditDate";
		$sSubject = "Audit Schedule Revised";

		// email + sms to auditor
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

			$objEmail->Send( );
		}


		$objSms = new Sms( );
		$objSms->send($sMobile, "", $sBody, $sSubject);
//		$objSms->send("923224970299", "", $sBody, $sSubject);
//		$objSms->send("923008458810", "", $sBody, $sSubject);
		$objSms->close( );


		$sAuditDate = formatDate($AuditDate);

		print "OK|-|$Id|-|<div>The selected Audit Schedule has been Updated successfully.</div>|-|$sName|-|$sVendor|-|$sLine|-|$sAuditDate|-|$StartTime";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>