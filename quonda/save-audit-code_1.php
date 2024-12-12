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

	@require_once("../requires/session.php");

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$_SESSION['Flag'] = "";


	$Auditor    = IO::intValue("Auditor");
	$Group      = IO::intValue("Group");
	$AuditDate  = IO::strValue("AuditDate");
	$StartTime  = (IO::strValue("StartHour").":".IO::strValue("StartMinutes").":00");
	$EndTime    = (IO::strValue("EndHour").":".IO::strValue("EndMinutes").":00");
	$Department = IO::intValue("Department");
	$Vendor     = IO::intValue("Vendor");
	$Unit       = IO::intValue("Unit");
	$OrderNo    = IO::strValue("OrderNo");
	$Po         = IO::intValue("Po");
	$StyleNo    = IO::intValue("StyleNo");
        $Maker      = IO::strValue("Maker");
        $InspecType = IO::strValue("InspecType");
	$sOrders    = array( );


	$sGroupAuditors = "";
	$iGroupAuditors = array( );

	if ($Group > 0)
	{
		$sGroupAuditors = getDbValue("users", "tbl_auditor_groups", "id='$Group'");
		$iGroupAuditors = @explode(",", $sGroupAuditors);
	}

	if ($Group > 0 && (count($iGroupAuditors) == 0 || !@in_array($Auditor, $iGroupAuditors)))
		$_SESSION['Flag'] = "INVALID_AUDIT_GROUP";

	else if ((int)getDbValue("COUNT(1)", "tbl_qa_reports", "user_id='$Auditor' AND audit_date='$AuditDate' AND (('$StartTime' BETWEEN start_time AND end_time) OR ('$EndTime' BETWEEN start_time AND end_time) OR (start_time BETWEEN '$StartTime' AND '$EndTime') OR (end_time BETWEEN '$StartTime' AND '$EndTime'))") > 0)
		$_SESSION['Flag'] = "INVALID_AUDIT_TIME";

	else if ((int)getDbValue("COUNT(1)", "tbl_user_schedule", "user_id='$Auditor' AND ('$AuditDate' BETWEEN from_date AND to_date) AND (('$StartTime' BETWEEN start_time AND end_time) OR ('$EndTime' BETWEEN start_time AND end_time) OR (start_time BETWEEN '$StartTime' AND '$EndTime') OR (end_time BETWEEN '$StartTime' AND '$EndTime'))") > 0)
			$_SESSION['Flag'] = "INVALID_AUDIT_TIME";

	else if (strtotime("{$AuditDate} {$EndTime}") <= strtotime("{$AuditDate} {$StartTime}"))
		$_SESSION['Flag'] = "INVALID_AUDIT_END_TIME";

	if ($_SESSION['Flag'] == "" && $Po == 0)
		$_SESSION['Flag'] = "INVALID_ORDER_NO";

	if ($_SESSION['Flag'] == "" && $StyleNo == 0)
		$_SESSION['Flag'] = "INVALID_STYLE_NO"; 
        
/*
	if ($_SESSION['Flag'] == "")
	{
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
				$_SESSION['Flag'] = "INVALID_ORDER_NO";
		}
	}

	if ($_SESSION['Flag'] == "")
	{
		if ($StyleNo != "" && count($sOrders) > 0)
		{
			$iStyles = array( );


			$sSQL = "SELECT id FROM tbl_styles WHERE style='{$StyleNo}'";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
				$iStyles[$i] = $objDb->getField($i, 0);

			if ($iCount == 0)
				$_SESSION['Flag'] = "INVALID_STYLE_NO";

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
					$_SESSION['Flag'] = "INVALID_PO_STYLE_NO";
			}
		}

		else if (count($sOrders) == 1)
			$iPoId = $sOrders[0]['Po'];
	}
*/

	if ($_SESSION['Flag'] == "")
	{
		$iBrand          = getDbValue("sub_brand_id", "tbl_styles", "id='$StyleNo'");
		$sCustomSample   = ((IO::intValue("SampleSize") == 0) ? "Y" : "N");
		$sInspectionType = ((IO::intValue("SampleSize") == 0) ? "F" : "V");


		$bFlag = $objDb->execute("BEGIN");

		$sSQL  = ("INSERT INTO tbl_qa_reports (user_id, group_id, department_id, vendor_id, brand_id, unit_id, po_id, style_id, colors, sizes, total_gmts, report_id, line_id, audit_stage, audit_date, start_time, end_time, approved, custom_sample, commission_type, date_time, created_at, status, inspection_type, maker)
		                               VALUES ('$Auditor', '$Group', '$Department', '$Vendor', '$iBrand', '$Unit', '$Po', '$StyleNo', '".@implode(",", IO::getArray("Colors"))."', '".@implode(",", IO::getArray("Sizes"))."', '".IO::intValue("SampleSize")."', '".IO::intValue("Report")."', '".IO::intValue("Line")."', '".IO::strValue("AuditStage")."', '$AuditDate', '$StartTime', '$EndTime', 'Y', '$sCustomSample', '$sInspectionType', NOW( ), NOW( ), '', '$InspecType', '$Maker')");
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$iId        = $objDb->getAutoNumber( );
			$sAuditCode = ("S".str_pad($iId, 4, 0, STR_PAD_LEFT));

			$sSQL  = "UPDATE tbl_qa_reports SET audit_code='$sAuditCode' WHERE id='$iId'";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			$_SESSION['Flag'] = "AUDIT_CODE_ADDED";

			$sSQL = "SELECT * FROM tbl_users WHERE id='Auditor'";
			$objDb->query($sSQL);

			$sName   = $objDb->getField(0, "name");
			$sEmail  = $objDb->getField(0, "email");
			$sMobile = $objDb->getField(0, "mobile");
			$sAlerts = $objDb->getField(0, "email_alerts");


			$sSQL = ("SELECT vendor FROM tbl_vendors WHERE id='$Vendor'");
			$objDb->query($sSQL);

			$sVendor = $objDb->getField(0, 0);


			$sSQL = ("SELECT line FROM tbl_lines WHERE id='".IO::intValue("Line")."'");
			$objDb->query($sSQL);

			$sLine = $objDb->getField(0, 0);


			$sBody    = "{$sAuditCode} is in {$sVendor} Line {$sLine} from {$StartTime} to {$EndTime} on {$AuditDate}";
			$sSubject = "New Audit Job";

			// email + sms to auditor
			$objEmail = new PHPMailer( );

			$objEmail->Subject = $sSubject;
			$objEmail->Body    = $sBody;

			$objEmail->IsHTML(false);

			if ($sEmail != "")
			{
				if ($sAlerts == "Y")
					$objEmail->AddAddress($sEmail, $sName);

				if ($Group > 0)
				{
					$sSQL = "SELECT name, email FROM tbl_users WHERE id IN ($sGroupAuditors) AND id!='$Auditor' AND status='A' AND email_alerts='Y'";
					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );

					for ($i = 0; $i < $iCount; $i ++)
					{
						$sMemberName  = $objDb->getField($i, "name");
						$sMemberEmail = $objDb->getField($i, "email");

						$objEmail->AddAddress($sMemberEmail, $sMemberName);
					}
				}


				if ($sAlerts == "Y" || ($Group > 0 && $iCount > 0))
					$objEmail->Send( );
			}


			$objSms = new Sms( );
			$objSms->send($sMobile, "", $sBody, $sSubject);
			$objSms->close( );

			header("Location: {$_SERVER['HTTP_REFERER']}");
			exit( );
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION['Flag'] = "DB_ERROR";
		}
	}


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>