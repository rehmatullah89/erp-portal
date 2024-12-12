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


	$Auditor        = IO::intValue("Auditor");
	$Group          = IO::intValue("Group");
	$AuditDate      = IO::strValue("AuditDate");
	$StartTime      = (IO::strValue("StartHour").":".IO::strValue("StartMinutes").":00");
	$EndTime        = (IO::strValue("EndHour").":".IO::strValue("EndMinutes").":00");
	$Department     = IO::intValue("Department");
	$Vendor         = IO::intValue("Vendor");
	$Unit           = IO::intValue("Unit");
	$OrderNo        = IO::strValue("OrderNo");
	$Po             = IO::intValue("Po");
	$AdditionalPOs  = IO::strValue("AdditionalPO");
	$StyleNo        = IO::intValue("StyleNo");
	$StyleNos       = IO::getArray("StyleNo");
	$Maker          = IO::strValue("Maker");
	$LotNo          = IO::strValue("LotNo");
	$OfferedQty     = IO::intValue("OfferedQty");
	$TotalGmts      = IO::intValue("SampleSize");
	$InspectionLevel= IO::strValue("InspectionLevel");
	$AqlLevel       = IO::floatValue("AqlLevel");
	$CheckLevel     = IO::intValue("CheckLevel");
        $HohOrderNo     = IO::strValue("HohOrderNo");
        $AuditType      = (IO::intValue("AuditType") == 0?1:IO::intValue("AuditType"));
	$sOrders        = array( );

	if($InspectionLevel == "")
		$InspectionLevel = 2;

	if($AqlLevel == "")
		$AqlLevel = 2.5;
        
        if($CheckLevel == 0)
            $SamplingPlan = 1;

	if(IO::intValue("Report") == 38)
		$AqlLevel = 4;

	$iCountry = getDbValue("country_id", "tbl_vendors", "id='$Vendor'");
	$iHours   = getDbValue("hours", "tbl_countries", "id='$iCountry'");
	
	$StartTime = date("H:i:s", (strtotime($StartTime) - ($iHours * 3600)));
	$EndTime   = date("H:i:s", (strtotime($EndTime) - ($iHours * 3600)));


	
	if(@in_array(IO::intValue("Report"), array(28,37,38)))
		$TotalGmts = getSampleSize($OfferedQty, IO::intValue("Report"), $InspectionLevel, $CheckLevel);
        
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

	if ($_SESSION['Flag'] == "" && IO::intValue("Report")!='54' && $StyleNo == 0)
		$_SESSION['Flag'] = "INVALID_STYLE_NO";

	if ($_SESSION['Flag'] == "" && IO::intValue("Report")=='54' && count($StyleNos) == 0)
		$_SESSION['Flag'] = "INVALID_STYLE_NO";
	
	if ($_SESSION['Flag'] == "" && getDbValue("COUNT(1)", "tbl_po", "vendor_id='$Vendor' AND id='$Po'") == 0)
		$_SESSION['Flag'] = "INVALID_ORDER_NO";

	// if ($AdditionalPOs != "")
	
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
	$additionalStyleNo = "";

	if(count($StyleNos) > 0) {

		if(count($StyleNos) == 1){

			$StyleNo = implode(",", $StyleNos);
		
		} else {
		
			for($i=0;$i<count($StyleNos);$i++){

				if($i==0){
					$StyleNo = $StyleNos[$i];
				} else {
					$additionalStyleNo .= $StyleNos[$i].",";
				}
			}	

			$additionalStyleNo = rtrim($additionalStyleNo,",");
		}
	}

	if ($_SESSION['Flag'] == "")
	{
		$iBrand          = getDbValue("sub_brand_id", "tbl_styles", "id='$StyleNo'");
		$sCustomSample   = ((IO::intValue("SampleSize") == 0) ? "Y" : "N");
		$sInspectionType = ((IO::intValue("SampleSize") == 0) ? "F" : "V");
		$sPublished      = "Y"; //((IO::intValue("Report") == 14) ? "N" : "Y");
		$Report          = IO::intValue("Report");
		

		$bFlag = $objDb->execute("BEGIN");


		if ($bFlag == true)
		{
                    $sSQL  = ("INSERT INTO tbl_qa_reports (user_id, group_id, department_id, audit_type_id, vendor_id, brand_id, unit_id, po_id, style_id, colors, sizes, total_gmts, report_id, line_id, audit_stage, audit_date, start_time, end_time, approved, custom_sample, commission_type, date_time, created_at, status, additional_pos,additional_styles, published, cutting_lot_no, audit_quantity, inspection_level, aql, check_level)
										   VALUES ('$Auditor', '$Group', '$Department', '$AuditType', '$Vendor', '$iBrand', '$Unit', '$Po', '$StyleNo', '".@implode(",", IO::getArray("Colors"))."', '".@implode(",", IO::getArray("Sizes"))."', '$TotalGmts', '$Report', '".IO::intValue("Line")."', '".IO::strValue("AuditStage")."', '$AuditDate', '$StartTime', '$EndTime', 'Y', '$sCustomSample', '$sInspectionType', NOW( ), NOW( ), '', '$AdditionalPOs','$additionalStyleNo', '$sPublished', '$LotNo', '$OfferedQty', '$InspectionLevel', '$AqlLevel', '$CheckLevel')");
			$bFlag = $objDb->execute($sSQL);
		}
		
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

			
			$sSQL = "SELECT * FROM tbl_users WHERE id='$Auditor'";
			$objDb->query($sSQL);

			$sName   = $objDb->getField(0, "name");
			$sEmail  = $objDb->getField(0, "email");
			$sMobile = $objDb->getField(0, "mobile");
			$sAlerts = $objDb->getField(0, "email_alerts");
			$sPicture = $objDb->getField(0, "picture");
			
			if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
				$sPicture = "default.jpg";			


			$sSQL = ("SELECT vendor,address,latitude,longitude FROM tbl_vendors WHERE id='$Vendor'");
			$objDb->query($sSQL);

			$sVendor = $objDb->getField(0, "vendor");
			$sAddress = $objDb->getField(0, "address");
			$sLatitude = $objDb->getField(0, "latitude");
			$sLongitude = $objDb->getField(0, "longitude");

			$AuditStage = IO::strValue("AuditStage");

			$sSQL = ("SELECT line FROM tbl_lines WHERE id='".IO::intValue("Line")."'");
			$objDb->query($sSQL);

			$sLine = $objDb->getField(0, 0);

			$sSMSBody = "{$sAuditCode} is in {$sVendor} Line {$sLine} from {$StartTime} to {$EndTime} on {$AuditDate}";
			
			// $sSubject = "New Audit Job";

			// email + sms to auditor
			$objEmail = new PHPMailer( );

			$sBrand = getDbValue('brand', 'tbl_brands', "id='$iBrand'");
			$sAuditStage = getDbValue("stage", "tbl_audit_stages", "code='$AuditStage'");

			if($AuditType == "1")
				$sAuditType = "Production Audit";
			else
				$sAuditType = getDbValue("type", "tbl_audit_types", "id='$AuditType'");

			$sLocation = "https://www.google.com/maps/place/".$sLatitude.",".$sLongitude;

			$FooterImgURL = SITE_URL."images/email-footer-icon.jpg";
			$Thumbnail = (SITE_URL.USERS_IMG_PATH.'thumbs/'.$sPicture);



			$Day = date("l",strtotime($AuditDate));
			$MonthYear = date("F, Y",strtotime($AuditDate));
			$DayNumber = date("j",strtotime($AuditDate));
			$DaySuffix = date("S",strtotime($AuditDate));

			$SubjectDate = date("d/m/Y",strtotime($AuditDate));

	    $sBody = @file_get_contents($sBaseDir."emails/audit-schedule.txt");

	    $sBody = @str_replace("[Day]", $Day,$sBody);
	    $sBody = @str_replace("[MonthYear]", $MonthYear,$sBody);
	    $sBody = @str_replace("[DayNumber]", $DayNumber,$sBody);
	    $sBody = @str_replace("[DaySuffix]", $DaySuffix,$sBody);

	    $sBody = @str_replace("[Auditor]", $sName, $sBody);
	    $sBody = @str_replace("[Brand]", $sBrand, $sBody);
	    $sBody = @str_replace("[Manufacturer]", $sVendor, $sBody);
	    $sBody = @str_replace("[Address]", $sAddress, $sBody);
	    $sBody = @str_replace("[Location]", $sLocation, $sBody);
	    $sBody = @str_replace("[AuditCode]", $sAuditCode, $sBody);
	    $sBody = @str_replace("[OrderNo]", $OrderNo, $sBody);
	    $sBody = @str_replace("[StyleNo]", getDbValue("style", "tbl_styles", "id='$StyleNo'"), $sBody);
	    $sBody = @str_replace("[AuditType]", $sAuditType, $sBody);
	    $sBody = @str_replace("[AuditStage]", $sAuditStage, $sBody);
	    $sBody = @str_replace("[Unit]", getDbValue("vendor", "tbl_vendors", "id='$Unit' AND parent_id='$Vendor' AND sourcing='Y'"), $sBody);
	    $sBody = @str_replace("[Line]", $sLine, $sBody);
	    $sBody = @str_replace("[StartTime]", formatTime($StartTime), $sBody);
	    $sBody = @str_replace("[EndTime]", formatTime($EndTime), $sBody);
	    $sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
	    $sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);
	    $sBody = @str_replace("[FooterImgURL]", $FooterImgURL, $sBody);
	    $sBody = @str_replace("[Thumbnail]", $Thumbnail, $sBody);

	    $sSubject = "New ".$sAuditStage." Inspection for ".$sBrand." at ".$sVendor." on ".$SubjectDate;

			$objEmail->Subject = $sSubject;

			$objEmail->Body    = $sBody;

			$objEmail->IsHTML(true);

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

/*
			$objSms = new Sms( );
			$objSms->send($sMobile, "", $sSMSBody, $sSubject);
			$objSms->close( );
*/
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
