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

	$Id              = IO::intValue("Id");
	$Auditor         = IO::intValue("Auditor");
	$Group           = IO::intValue("Group");
	$Department      = IO::intValue("Department");
	$Vendor          = IO::intValue("Vendor");
	$Unit            = IO::intValue("Unit");
	$Report          = IO::intValue("Report");
	$Line            = IO::intValue("Line");
	$AuditStage      = IO::strValue("AuditStage");
	$AuditDate       = IO::strValue("AuditDate");
	$StartTime       = (IO::strValue("StartHour").":".IO::strValue("StartMinutes").":00");
	$EndTime         = (IO::strValue("EndHour").":".IO::strValue("EndMinutes").":00");
	$Approved        = IO::strValue("Approved");
	$OrderNo         = IO::strValue("OrderNo");
	$Po              = IO::intValue("Po");
	$StyleNo         = IO::intValue("StyleNo");
	$Maker           = IO::strValue("Maker");
	$InspecType      = IO::strValue("InspecType");
	$AdditionalPO    = IO::strValue("AdditionalPO");
	$OfferedQty      = IO::intValue("OfferedQty");
	$TotalGmts       = IO::intValue("SampleSize");
	$CheckLevel      = IO::intValue("CheckLevel");
	$InspectionLevel = IO::intValue("InspectionLevel");
	$AqlLevel        = IO::floatValue("AqlLevel");
	$AuditType       = (IO::intValue("AuditType") == 0?1:IO::intValue("AuditType"));
	$sError          = "";
	$sGroup          = "";

	if(@in_array($_SESSION["UserType"], array("JCREW")))
	{
		$StyleNo        = IO::intValue("StyleId");
		$OrderNo        = IO::getArray("OrderNo");
		$Po             = (int)@$OrderNo[0];
		$AdditionalPO   = implode(",", array_diff($OrderNo, array(@$OrderNo[0])));
	}
        
	if($InspectionLevel == "")
        $InspectionLevel = 2;

	if($CheckLevel == "")
        $CheckLevel = 1;
        
	if($AqlLevel == "")
		$AqlLevel = 2.5;

	if(@in_array($Report, array(28,37,38)))
		$TotalGmts = getSampleSize($OfferedQty, $Report, $InspectionLevel, $CheckLevel);
	
	$sSQL = "SELECT id, audit_code FROM tbl_qa_reports WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Audit ID. Please select the proper Audit to Edit.\n";
		exit( );
	}
	else
		$sAuditCode = $objDb->getField(0, 0);

	if (!in_array($Report, array(14,34)) && (strtotime("{$AuditDate} {$EndTime}") <= strtotime("{$AuditDate} {$StartTime}")))
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
	
	
	$iCountry = getDbValue("country_id", "tbl_vendors", "id='$Vendor'");
	$iHours   = getDbValue("hours", "tbl_countries", "id='$iCountry'");
	
	if ($iHours != 0)
	{
		$StartTime = date("H:i:s", (strtotime($StartTime) - ($iHours * 3600)));
		$EndTime   = date("H:i:s", (strtotime($EndTime) - ($iHours * 3600)));
	}
			

	if ((int)getDbValue("COUNT(1)", "tbl_qa_reports", "id!='$Id' AND user_id='$Auditor' AND audit_date='$AuditDate' AND (('$StartTime' BETWEEN start_time AND end_time) OR ('$EndTime' BETWEEN start_time AND end_time) OR (start_time BETWEEN '$StartTime' AND '$EndTime') OR (end_time BETWEEN '$StartTime' AND '$EndTime'))") > 0)
		$sError .= "- Invalid Audit Time, Time is overlapping with another Audit Schedule.\n";

	else if ((int)getDbValue("COUNT(1)", "tbl_user_schedule", "user_id='$Auditor' AND ('$AuditDate' BETWEEN from_date AND to_date) AND (('$StartTime' BETWEEN start_time AND end_time) OR ('$EndTime' BETWEEN start_time AND end_time) OR (start_time BETWEEN '$StartTime' AND '$EndTime') OR (end_time BETWEEN '$StartTime' AND '$EndTime'))") > 0)
		$sError .= "- Invalid Time, Start/End Time is overlapping with another Schedule Entry.\n";

	if (!in_array($Report, array(14,34)) && $AuditDate == "")
		$sError .= "- Invalid Audit Date\n";

	if ($AuditStage == "")
		$sError .= "- Invalid Audit Stage\n";

	if ($Po == 0)
		$sError .= "- Invalid Order No\n";

	if ($StyleNo == 0)
		$sError .= "- Invalid Style No\n";
	
	if ($Po > 0 && getDbValue("COUNT(1)", "tbl_po", "vendor_id='$Vendor' AND id='$Po'") == 0)
		$sError .= "- Invalid Order No\n";

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

	$bFlag = $objDb->execute("BEGIN");


	$sCustomSample   = ((IO::intValue("SampleSize") == 0) ? "Y" : "N");
	$sInspectionType = ((IO::intValue("SampleSize") == 0) ? "F" : "V");
	$iBrand          = getDbValue("sub_brand_id", "tbl_styles", "id='$StyleNo'");
	$sPublished      = "Y"; //((IO::intValue("Report") == 14) ? "N" : "Y");
	$iMasterId       = 0;
	
	if ($Report == 14 || $Report == 34)
	{
		$sTable = (($Report == 34) ? "_test" : "");
		$aPos   = array( );
		$aPos[] = $Po;
		
		if ($AdditionalPO != "")
			$aPos = @array_merge($aPos, @explode(",", $AdditionalPO));
		
		
		$sPos   = @implode(",", $aPos);
		$aDates = getList("tbl_po_colors", "DISTINCT(etd_required)", "etd_required", "FIND_IN_SET(po_id, '$sPos')");
		$sDates = @implode(",", $aDates);

		
		if (getDbValue("COUNT(1)", "tbl_mgf{$sTable}_master_ids", "vendor_id='$Vendor' AND style_id='$StyleNo'") > 0)
		{
			$sSQL = "SELECT m.id
					 FROM tbl_mgf{$sTable}_master_ids m, tbl_mgf{$sTable}_master_pos po, tbl_mgf{$sTable}_master_dates d
					 WHERE m.id=po.master_id AND m.id=d.master_id AND po.master_id=d.master_id
						   AND m.vendor_id='$Vendor' AND m.style_id='$StyleNo'
						   AND (FIND_IN_SET(po.po_id, '$sPos') OR FIND_IN_SET(d.etd_required, '$sDates'))";
			$objDb->query($sSQL);
			
			if ($objDb->getCount( ) >= 1)
				$iMasterId = $objDb->getField(0, 0);
		}
		
		
		if ($iMasterId == 0)
		{
			$iMasterId = getNextId("tbl_mgf{$sTable}_master_ids");
			
			$sSQL  = "INSERT INTO tbl_mgf{$sTable}_master_ids (id, vendor_id, style_id) VALUES ('$iMasterId', '$Vendor', '$StyleNo')";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true)
		{
			foreach ($aPos as $iPo)
			{
				if (getDbValue("COUNT(1)", "tbl_mgf{$sTable}_master_pos", "master_id='$iMasterId' AND po_id='$iPo'") == 0)
				{
					$sSQL  = "INSERT INTO tbl_mgf{$sTable}_master_pos (master_id, po_id) VALUES ('$iMasterId', '$iPo')";
					$bFlag = $objDb->execute($sSQL);						
				}
			}
		}
		
		if ($bFlag == true)
		{
			foreach ($aDates as $sDate)
			{
				if (getDbValue("COUNT(1)", "tbl_mgf{$sTable}_master_dates", "master_id='$iMasterId' AND etd_required='$sDate'") == 0)
				{
					$sSQL  = "INSERT INTO tbl_mgf{$sTable}_master_dates (master_id, etd_required) VALUES ('$iMasterId', '$sDate')";
					$bFlag = $objDb->execute($sSQL);						
				}
			}
		}
	}

	
	if ($bFlag == true)
	{	
            if(@in_array($Report, array(14,34)))
		$sSQL  = "UPDATE tbl_qa_reports SET group_id='$Group', department_id='$Department', vendor_id='$Vendor', brand_id='$iBrand', master_id='$iMasterId', unit_id='$Unit', report_id='$Report', line_id='$Line', audit_stage='$AuditStage', po_id='$Po', style_id='$StyleNo', colors='".@implode(",", IO::getArray("Colors"))."', sizes='".@implode(",", IO::getArray("Sizes"))."', total_gmts='$TotalGmts', custom_sample='$sCustomSample', commission_type='$sInspectionType', approved='$Approved', maker='$Maker', inspection_type='$InspecType', additional_pos='$AdditionalPO', published='$sPublished', audit_quantity = '$OfferedQty', inspection_level = '$InspectionLevel', check_level = '$CheckLevel', aql='$AqlLevel' WHERE id='$Id'";
            else
                $sSQL  = "UPDATE tbl_qa_reports SET audit_type_id = '$AuditType', user_id='$Auditor', group_id='$Group', department_id='$Department', vendor_id='$Vendor', brand_id='$iBrand', master_id='$iMasterId', unit_id='$Unit', report_id='$Report', line_id='$Line', audit_stage='$AuditStage', audit_date='$AuditDate', start_time='$StartTime', end_time='$EndTime', po_id='$Po', style_id='$StyleNo', colors='".@implode(",", IO::getArray("Colors"))."', sizes='".@implode(",", IO::getArray("Sizes"))."', total_gmts='$TotalGmts', custom_sample='$sCustomSample', commission_type='$sInspectionType', approved='$Approved', maker='$Maker', inspection_type='$InspecType', additional_pos='$AdditionalPO', published='$sPublished', audit_quantity = '$OfferedQty', inspection_level = '$InspectionLevel', check_level = '$CheckLevel', aql='$AqlLevel' WHERE id='$Id'";

            $bFlag = $objDb->execute($sSQL);
	}	
        
	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");
		
		
		if ($Report == 14 || $Report == 34)
			$sBody = "$sAuditCode is in $sVendor from $StartTime to $EndTime on $AuditDate";
		
		else
			$sBody = "$sAuditCode is in $sVendor Line $sLine from $StartTime to $EndTime on $AuditDate";
		
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

		if ($iHours != 0)
		{
			$StartTime = date("H:i", (strtotime($StartTime) + ($iHours * 3600)));
			$EndTime   = date("H:i", (strtotime($EndTime) + ($iHours * 3600)));
		}		

                $Published = getDbValue("published", "tbl_qa_reports", "id='$Id'");
                
		print ("OK|-|$Id|-|<div>The selected Audit Code has been Updated successfully.</div>|-|$sName".(($Group > 0) ? " (G)" : "")."|-|$sVendor|-|$sLine|-|$sAuditDate|-|$StartTime|-|$EndTime|-|$Approved|-|$Maker|-|$InspecType|-|$Published");
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