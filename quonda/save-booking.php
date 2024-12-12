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

        $AuditDate      = IO::strValue("AuditDate");
	$StartTime      = (IO::strValue("StartHour").":".IO::strValue("StartMinutes").":00");
	$EndTime        = (IO::strValue("EndHour").":".IO::strValue("EndMinutes").":00");
	$Brand          = IO::intValue("Brand");
        $Vendor         = IO::intValue("Vendor");
	$SampleSize     = IO::intValue("SampleSize");
	$StyleNo        = IO::intValue("StyleId");
        $OrderNo        = IO::getArray("OrderNo");
        $sPos           = implode(",", $OrderNo);
	$Colors         = @implode(",", IO::getArray("Colors"));
        $Sizes          = @implode(",", IO::getArray("Sizes"));
        $Commissions    = @implode(",", IO::getArray("Commissions"));

	$iCountry = getDbValue("country_id", "tbl_vendors", "id='$Vendor'");
	$iHours   = getDbValue("hours", "tbl_countries", "id='$iCountry'");
	
	$StartTime = date("H:i:s", (strtotime($StartTime) - ($iHours * 3600)));
	$EndTime   = date("H:i:s", (strtotime($EndTime) - ($iHours * 3600)));
        

	if ((int)getDbValue("COUNT(1)", "tbl_bookings", "auditor_id='{$_SESSION['UserId']}' AND audit_date='$AuditDate' AND (('$StartTime' BETWEEN start_time AND end_time) OR ('$EndTime' BETWEEN start_time AND end_time) OR (start_time BETWEEN '$StartTime' AND '$EndTime') OR (end_time BETWEEN '$StartTime' AND '$EndTime'))") > 0)
		$_SESSION['Flag'] = "INVALID_AUDIT_TIME";

	else if (strtotime("{$AuditDate} {$EndTime}") <= strtotime("{$AuditDate} {$StartTime}"))
		$_SESSION['Flag'] = "INVALID_AUDIT_END_TIME";

	if ($_SESSION['Flag'] == "" && empty($OrderNo))
		$_SESSION['Flag'] = "INVALID_ORDER_NO";

	if ($_SESSION['Flag'] == "" && $StyleNo == 0)
		$_SESSION['Flag'] = "INVALID_STYLE_NO";
	

	if ($_SESSION['Flag'] == "")
	{
		$iBrand = getDbValue("sub_brand_id", "tbl_styles", "id='$StyleNo'");
		
		$bFlag  = $objDb->execute("BEGIN");
		
		if ($bFlag == true)
		{
                    $iId      = getNextId("tbl_bookings");
                    
                    $sSQL  = ("INSERT INTO tbl_bookings (id, brand_id, vendor_id, inspection_date, start_time, end_time, style_id, pos, colors, commissions, sizes, sample_size, created_by, created_at, modified_by, modified_at)
										   VALUES ('$iId', '$Brand', '$Vendor', '$AuditDate', '$StartTime', '$EndTime', '$StyleNo', '$sPos', '$Colors', '$Commissions', '$Sizes', '$SampleSize', '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}', NOW( ))");
                    $bFlag = $objDb->execute($sSQL);
		}
		
		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			$_SESSION['Flag'] = "BOOKING_ADDED";

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
                        
                        $sBookingManagers = getList("tbl_users", "id", "name", "FIND_IN_SET('15', auditor_types)");
                        $sBookingMngEmails= getList("tbl_users", "id", "email", "FIND_IN_SET('15', auditor_types)");
                        
                        $sSQL = "SELECT SUM(pq.quantity)
                            FROM tbl_po_colors pc, tbl_po_quantities pq
                                    WHERE pc.po_id=pq.po_id AND pc.style_id='$StyleNo' AND pc.id=pq.color_id AND pc.po_id IN ($sPos) AND FIND_IN_SET(pc.color, '$Colors') AND pq.size_id IN ($Sizes)";

                        if ($Commissions != "")
                            $sSQL .= " AND FIND_IN_SET(pc.line, '$Commissions') ";
                        else
                            $Commissions = "No commission number selected.";

                        $objDb->query($sSQL);

                        $iQuantity = $objDb->getField(0, 0);
                        
                        $sBookingCode =  "B".str_pad($iId, 5, 0, STR_PAD_LEFT);
			$sSubject = "New Booking Alert";

                        $sBody = @file_get_contents("../emails/booking.txt");

                        $sBody = @str_replace("[BookingStatement]", "A New Booking# {$sBookingCode} has been requested against Factory: {$sVendor}", $sBody);
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
                        
			// email to auditor
			$objEmail = new PHPMailer( );

			$objEmail->Subject = $sSubject;
			$objEmail->MsgHTML($sBody);
                        //$objEmail->Body    = "Dear Reader, \n A New Booking# {$sBookingCode} has been requested against Factory: {$sVendor} from {$StartTime} to {$EndTime} on {$AuditDate}";

			$objEmail->IsHTML(false);

                        foreach($sBookingManagers as $iKey => $sManager)
                            $objEmail->AddAddress($sBookingMngEmails[$iKey], $sManager);
                        
                        if($sManagerEmail != "")
                            $objEmail->AddAddress($sManagerEmail, $sManagerName);
                            
                        if ($sEmail != "" && $sAlerts == "Y")
                            $objEmail->AddAddress($sEmail, $sName);                            

                        $objEmail->Send( );
                        
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
