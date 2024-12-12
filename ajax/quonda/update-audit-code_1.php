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
	$Department = IO::intValue("Department");
	$Vendor     = IO::intValue("Vendor");
	$Unit       = IO::intValue("Unit");
	$Report     = IO::intValue("Report");
	$Line       = IO::intValue("Line");
	$AuditStage = IO::strValue("AuditStage");
	$AuditDate  = IO::strValue("AuditDate");
	$StartTime  = (IO::strValue("StartHour").":".IO::strValue("StartMinutes").":00");
	$EndTime    = (IO::strValue("EndHour").":".IO::strValue("EndMinutes").":00");
	$Approved   = IO::strValue("Approved");
	$OrderNo    = IO::strValue("OrderNo");
	$Po         = IO::intValue("Po");
	$StyleNo    = IO::intValue("StyleNo");
    $Maker      = IO::strValue("Maker");
    $InspecType = IO::strValue("InspecType");
	$sError     = "";
	$sGroup     = "";

	$sSQL = "SELECT id, audit_code FROM tbl_qa_reports WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Audit ID. Please select the proper Audit to Edit.\n";
		exit( );
	}

	else
		$sAuditCode = $objDb->getField(0, 0);

	if (strtotime("{$AuditDate} {$EndTime}") <= strtotime("{$AuditDate} {$StartTime}"))
	{
		print "ERROR|-|Invalid Audit End Time. End Time should be greater than the Audit Start Time.\n";
		exit( );
	}

	if ($Auditor > 0)
	{
		$sSQL = "SELECT name, email, mobile, email_alerts FROM tbl_users WHERE id='$Auditor'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Auditor\n";

		else
		{
			$sName   = $objDb->getField(0, "name");
			$sEmail  = $objDb->getField(0, "email");
			$sMobile = $objDb->getField(0, "mobile");
			$sAlerts = $objDb->getField(0, "email_alerts");
		}
	}


	if ($Group > 0)
	{
		$sSQL = "SELECT users FROM tbl_auditor_groups WHERE id='$Group'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Auditors Group\n";

		else
		{
			$sGroup         = $objDb->getField(0, 0);
			$iGroupAuditors = @explode(",", $sGroup);

			if ((count($iGroupAuditors) == 0 || !@in_array($Auditor, $iGroupAuditors)))
				$sError .= "- Invalid Auditors Group, selected Auditor not found in Group.\n";
		}
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

	if ($Unit > 0)
	{
		$sSQL = "SELECT vendor FROM tbl_vendors WHERE id='$Unit'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Unit\n";

		else
			$sUnit = $objDb->getField(0, 0);
	}

	if ($Department > 0)
	{
		$sSQL = "SELECT department FROM tbl_departments WHERE id='$Department'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Department\n";

		else
			$sDepartment = $objDb->getField(0, 0);
	}

	if ($Line > 0)
	{
		$sSQL = "SELECT line FROM tbl_lines WHERE vendor_id='$Vendor' AND unit_id='$Unit' AND id='$Line'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Line\n";

		else
			$sLine = $objDb->getField(0, 0);
	}

	if ((int)getDbValue("COUNT(1)", "tbl_qa_reports", "id!='$Id' AND user_id='$Auditor' AND audit_date='$AuditDate' AND (('$StartTime' BETWEEN start_time AND end_time) OR ('$EndTime' BETWEEN start_time AND end_time) OR (start_time BETWEEN '$StartTime' AND '$EndTime') OR (end_time BETWEEN '$StartTime' AND '$EndTime'))") > 0)
		$sError .= "- Invalid Audit Time, Time is overlapping with another Audit Schedule.\n";

	else if ((int)getDbValue("COUNT(1)", "tbl_user_schedule", "user_id='$Auditor' AND ('$AuditDate' BETWEEN from_date AND to_date) AND (('$StartTime' BETWEEN start_time AND end_time) OR ('$EndTime' BETWEEN start_time AND end_time) OR (start_time BETWEEN '$StartTime' AND '$EndTime') OR (end_time BETWEEN '$StartTime' AND '$EndTime'))") > 0)
		$sError .= "- Invalid Time, Start/End Time is overlapping with another Schedule Entry.\n";

	if ($AuditDate == "")
		$sError .= "- Invalid Audit Date\n";

	if ($AuditStage == "")
		$sError .= "- Invalid Audit Stage\n";

	if ($Po == 0)
		$sError .= "- Invalid Order No\n";

	if ($StyleNo == 0)
		$sError .= "- Invalid Style No\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}

/*
	$iPoId    = 0;
	$iStyleId = 0;
	$sOrders  = array( );


	if ($OrderNo != "")
	{
		$sSQL = "SELECT id, styles FROM tbl_po WHERE order_no LIKE '$OrderNo' AND vendor_id='$Vendor'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sOrders[$i]['Po']     = $objDb->getField($i, 0);
			$sOrders[$i]['Styles'] = $objDb->getField($i, 1);
		}

		if ($iCount == 0)
		{
			print "ERROR|-|Invalid PO No";
			exit( );
		}
	}



	if ($StyleNo != "" && count($sOrders) > 0)
	{
		$iStyles = array( );


		$sSQL = "SELECT id FROM tbl_styles WHERE style='{$StyleNo}'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$iStyles[$i] = $objDb->getField($i, 0);

		if ($iCount == 0)
		{
			print "ERROR|-|Invalid Style No";
			exit( );
		}

		else
		{
			for ($i = 0; $i < count($sOrders); $i ++)
			{
				for ($j = 0; $j < count($iStyles); $j ++)
				{
					$sSQL = "SELECT * FROM tbl_po_colors WHERE style_id='{$iStyles[$j]}' AND FIND_IN_SET(style_id, '{$sOrders[$i]['Styles']}') AND po_id='{$sOrders[$i]['Po']}'";
					$objDb->query($sSQL);

					if ($objDb->getCount( ) > 0)
					{
						$iPoId    = $sOrders[$i]['Po'];
						$iStyleId = $iStyles[$j];

						break;
					}
				}

				if ($iPoId > 0)
					break;
			}

			if ($iPoId == 0)
			{
				print "ERROR|-|Invalid PO - Style No Combination";
				exit( );
			}
		}
	}

	else if (count($sOrders) == 1)
		$iPoId = $sOrders[0]['Po'];
*/

	$sCustomSample   = ((IO::intValue("SampleSize") == 0) ? "Y" : "N");
	$sInspectionType = ((IO::intValue("SampleSize") == 0) ? "F" : "V");
	$iBrand          = getDbValue("sub_brand_id", "tbl_styles", "id='$StyleNo'");


	$sSQL = "UPDATE tbl_qa_reports SET user_id='$Auditor', group_id='$Group', department_id='$Department', vendor_id='$Vendor', brand_id='$iBrand', unit_id='$Unit', report_id='$Report', line_id='$Line', audit_stage='$AuditStage', audit_date='$AuditDate', start_time='$StartTime', end_time='$EndTime', po_id='$Po', style_id='$StyleNo', colors='".@implode(",", IO::getArray("Colors"))."', sizes='".@implode(",", IO::getArray("Sizes"))."', total_gmts='".IO::intValue("SampleSize")."', custom_sample='$sCustomSample', commission_type='$sInspectionType', approved='$Approved', maker='$Maker', inspection_type='$InspecType' WHERE id='$Id'";
        echo $sSQL;
	if ($objDb->execute($sSQL) == true)
	{
            	$sBody    = "$sAuditCode is in $sVendor Line $sLine from $StartTime to $EndTime on $AuditDate";
		$sSubject = "Audit Job Updates";

		// email + sms to auditor
		$objEmail = new PHPMailer( );

		$objEmail->Subject = $sSubject;
		$objEmail->Body    = $sBody;

		$objEmail->IsHTML(false);

		if ($sEmail != "")
		{
			if ($sAlerts == "Y")
				$objEmail->AddAddress($sEmail, $sName);

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

			if ($sAlerts == "Y" || ($sGroup != "" && $iCount > 0))
				$objEmail->Send( );
		}


		$objSms = new Sms( );
		$objSms->send($sMobile, "", $sBody, $sSubject);
		$objSms->close( );


		$sAuditDate = formatDate($AuditDate);
		$StartTime  = substr($StartTime, 0, 5);
		$EndTime    = substr($EndTime, 0, 5);

		print ("OK|-|$Id|-|<div>The selected Audit Code has been Updated successfully.</div>|-|$sName".(($Group > 0) ? " (G)" : "")."|-|$sVendor|-|$sLine|-|$sAuditDate|-|$StartTime|-|$EndTime|-|$Approved|-|$Maker|-|$InspecType");
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>