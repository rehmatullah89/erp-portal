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

        $Id             = IO::strValue("Id");
	$AuditDate      = IO::strValue("AuditDate");
	$StartTime      = (IO::strValue("StartHour").":".IO::strValue("StartMinutes").":00");
	$EndTime        = (IO::strValue("EndHour").":".IO::strValue("EndMinutes").":00");
	$Brand          = IO::intValue("Brand");
        $Vendor         = IO::intValue("Vendor");
	$SampleSize     = IO::intValue("SampleSize");
	$StyleNo        = IO::intValue("StyleId");
        $OrderNo        = IO::getArray("OrderNo");
        $sPos           = implode(",", $OrderNo);
        $sBookingCode   = "B".str_pad($Id, 5, 0, STR_PAD_LEFT);
	$Colors         = @implode(",", IO::getArray("Colors"));
        $Sizes          = @implode(",", IO::getArray("Sizes"));
        $Commissions    = @implode(",", IO::getArray("Commissions"));
	$sError         = "";
	
        $sBrand        = getDbValue("brand", "tbl_brands", "id='$Brand'");
        
        if($AqlLevel == "")
		$AqlLevel = 2.5;

	$sSQL = "SELECT id FROM tbl_bookings WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Booking ID. Please select the proper booking to Edit.\n";
		exit( );
	}

	if (strtotime("{$AuditDate} {$EndTime}") <= strtotime("{$AuditDate} {$StartTime}"))
	{
		print "ERROR|-|Invalid Booking End Time. End Time should be greater than the Booking Start Time.\n";
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
	
	$iCountry = getDbValue("country_id", "tbl_vendors", "id='$Vendor'");
	$iHours   = getDbValue("hours", "tbl_countries", "id='$iCountry'");
	
	if ($iHours != 0)
	{
		$StartTime = date("H:i:s", (strtotime($StartTime) - ($iHours * 3600)));
		$EndTime   = date("H:i:s", (strtotime($EndTime) - ($iHours * 3600)));
	}
			

	if ((int)getDbValue("COUNT(1)", "tbl_bookings", "id!='$Id' AND user_id='{$_SESSION['UserId']}' AND audit_date='$AuditDate' AND (('$StartTime' BETWEEN start_time AND end_time) OR ('$EndTime' BETWEEN start_time AND end_time) OR (start_time BETWEEN '$StartTime' AND '$EndTime') OR (end_time BETWEEN '$StartTime' AND '$EndTime'))") > 0)
		$sError .= "- Invalid Audit Time, Time is overlapping with another Audit Schedule.\n";

        if ($StyleNo == 0)
		$sError .= "- Invalid Style No\n";
	
        if (empty($OrderNo))
		$sError .= "- Invalid Order No\n";
      
	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}

	$bFlag = $objDb->execute("BEGIN");


	$sCustomSample   = ((IO::intValue("SampleSize") == 0) ? "Y" : "N");
	$sInspectionType = ((IO::intValue("SampleSize") == 0) ? "F" : "V");
	$iBrand          = getDbValue("sub_brand_id", "tbl_styles", "id='$StyleNo'");
	
	
	if ($bFlag == true)
	{	            
            $sSQL  = "UPDATE tbl_bookings SET brand_id='$Brand', vendor_id='$Vendor', inspection_date='$AuditDate', start_time='$StartTime', end_time='$EndTime', style_id='$StyleNo', pos='$sPos', colors='$Colors', commissions='$Commissions', sizes='$Sizes', sample_size='$SampleSize', modified_by='{$_SESSION['UserId']}', modified_at=NOW( ) WHERE id='$Id'";
            $bFlag = $objDb->execute($sSQL);
	}	
        
        if ($bFlag == true && (int)getDbValue("COUNT(1)", "tbl_qa_reports", "booking_id='$Id'") > 0)
        {
            $iPoId    = "";
            $iFirstPo = 0;
            $sAdditionalPos = array();
            $iPOs = explode(",", "$sPos");

            foreach($iPOs as $iPo)
            {
                if($iFirstPo == 0)
                    $iPoId = $iPo;
                else
                    $sAdditionalPos[$iPo] = $iPo;

                $iFirstPo ++;
            }
            $sAdditionalPos = implode(",", $sAdditionalPos);

            $iPoQty = getDbValue("SUM(quantity)", "tbl_po_quantities", "FIND_IN_SET(po_id, '$sPos')");
            $iAQLSampleSize = getSampleSize($iPoQty, 0, 2, 1);
            
            $sSQL  = ("UPDATE tbl_qa_reports SET brand_id='$iBrand', vendor_id='$Vendor', audit_date='$AuditDate', start_time='$StartTime', end_time='$EndTime', style_id='$StyleNo', po_id='$iPoId', additional_pos='$sAdditionalPos', colors='$Colors', sizes='$Sizes', commissions='$Commissions', total_gmts='$iAQLSampleSize'  WHERE booking_id='$Id'");

                            $bFlag = $objDb->execute($sSQL);
        }
        
	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");
                
                $sSQL = "SELECT * FROM tbl_users WHERE id='{$_SESSION['UserId']}'";
                $objDb->query($sSQL);

                $sName   = $objDb->getField(0, "name");
                $sEmail  = $objDb->getField(0, "email");
                $sMobile = $objDb->getField(0, "mobile");
                $sAlerts = $objDb->getField(0, "email_alerts");
                
                $sSQL    = ("SELECT vendor, manager_rep_email, manager_rep FROM tbl_vendors WHERE id='$Vendor'");
                $objDb->query($sSQL);

                $sVendor        = $objDb->getField(0, 0);
                $sManagerEmail  = $objDb->getField(0, 1);
                $sManagerName   = $objDb->getField(0, 2);
                
                $sSQL = "SELECT SUM(pq.quantity)
                            FROM tbl_po_colors pc, tbl_po_quantities pq
                                    WHERE pc.po_id=pq.po_id AND pc.style_id='$StyleNo' AND pc.id=pq.color_id AND pc.po_id IN ($sPos) AND FIND_IN_SET(pc.color, '$Colors') AND pq.size_id IN ($Sizes)";

                if ($Commissions != "")
                        $sSQL .= " AND FIND_IN_SET(pc.line, '$Commissions') ";
                else
                        $Commissions = "No commission number selected.";
                
                $objDb->query($sSQL);

                $iQuantity = $objDb->getField(0, 0);

		$sSubject = "Booking Update Alert";
                
                $sBody = @file_get_contents("../../emails/booking.txt");

                $sBody = @str_replace("[BookingStatement]", "Booking# {$sBookingCode} has been updated against Factory: {$sVendor}", $sBody);
                $sBody = @str_replace("[Vendor]", $sVendor, $sBody);
                $sBody = @str_replace("[Brand]", getDbValue("brand", "tbl_brands", "id='$Brand'"), $sBody);
                $sBody = @str_replace("[AuditDate]", $AuditDate, $sBody);
                $sBody = @str_replace("[StartTime]", $StartTime, $sBody);
                $sBody = @str_replace("[EndTime]", $EndTime, $sBody);
                $sBody = @str_replace("[StyleNo]", getDbValue("style", "tbl_styles", "id='$StyleNo'"), $sBody);
                $sBody = @str_replace("[OrderNo]", getDbValue("GROUP_CONCAT(order_no SEPARATOR ', ')", "tbl_po", "id IN ($sPos)"), $sBody);
                $sBody = @str_replace("[Colors]", $Colors, $sBody);
                $sBody = @str_replace("[Sizes]", getDbValue("GROUP_CONCAT(size SEPARATOR ', ')", "tbl_sizes", "id IN ($Sizes)"), $sBody);
                $sBody = @str_replace("[Commissions]", $Commissions, $sBody);
                $sBody = @str_replace("[Quantity]", $iQuantity, $sBody);

                $sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
                $sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);
                        
		// email + sms to auditor
		$objEmail = new PHPMailer( );

		$objEmail->Subject = $sSubject;
                $objEmail->MsgHTML($sBody);
		//$objEmail->Body    = "Dear Reader, \n Booking#{$sBookingCode} has been updated against Factory: {$sVendor} from {$StartTime} to {$EndTime} on {$AuditDate}";

		$objEmail->IsHTML(false);

		if ($sEmail != "")
		{
                        $sBookingManagers = getList("tbl_users", "id", "name", "FIND_IN_SET(15, auditor_types)");
                        $sBookingMngEmails= getList("tbl_users", "id", "email", "FIND_IN_SET(15, auditor_types)");
                        $iAuditor         = (int)getDbValue("user_id", "tbl_qa_reports", "booking_id='$Id'");
                        
                        if($iAuditor > 0)
                        {
                            $sAuditor = getDbValue("CONCAT(name, ',' , email)", "tbl_users", "id='$iAuditor'");
                            $sAuditor = explode(",", $sAuditor);
                            
                            $sAuditorName = @$sAuditor[0];
                            $sAuditorEmail= @$sAuditor[1];
                            
                            if($sAuditorName && $sAuditorEmail != "")
                                $objEmail->AddAddress($sAuditorEmail, $sAuditorName);
                        }
                        
			if ($sAlerts == "Y")
				$objEmail->AddAddress($sEmail, $sName);

                        foreach($sBookingManagers as $iKey => $sManager)
                            $objEmail->AddAddress($sBookingMngEmails[$iKey], $sManager);
                        
                        if($sManagerEmail != "")
                            $objEmail->AddAddress($sManagerEmail, $sManagerName);

                        $objEmail->Send( );
		}

                
		print ("OK|-|$Id|-|<div>The selected Booking Code has been Updated successfully.</div>|-|$sName"."|-|$sBrand|-|$sVendor|-|".formatDate($AuditDate)."|-|$StartTime|-|$EndTime");
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