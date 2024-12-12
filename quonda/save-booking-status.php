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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id         = IO::intValue("Id");
        $Status     = IO::strValue("Status");
        $Auditor    = IO::strValue("Auditor");
        $sBookingCode= "B".str_pad($Id, 5, 0, STR_PAD_LEFT);
 
        $sSubSql    = "";        
        $sAuditCode = "";
        $sPrevStatus= getDbValue("status", "tbl_bookings", "id='$Id'");
        
		if($Status == 'A')
		{
			if($Status != $sPrevStatus)
				$sSubSql = " ,auditor_id='$Auditor' ,approved_by='{$_SESSION['UserId']}', approved_at = NOW(), assigned_by='{$_SESSION['UserId']}', assigned_at = NOW(), rejected_by ='0', rejected_at='0000-00-00 00:00:00' ";             
			
			else
				$sSubSql = " ,auditor_id='$Auditor' ,approved_by='{$_SESSION['UserId']}', approved_at = NOW(), assigned_by='{$_SESSION['UserId']}', assigned_at = NOW() ";             
		}
		
        else if($Status != $sPrevStatus && $Status == 'C')
            $sSubSql = " ,rejected_by='{$_SESSION['UserId']}', rejected_at = NOW(), approved_by ='0', approved_at='0000-00-00 00:00:00', assigned_by='0', assigned_at='0000-00-00 00:00:00' ";
        else if($Status != $sPrevStatus && $Status == 'P')
            $sSubSql = " ,approved_by='0', approved_at = '0000-00-00 00:00:00', rejected_by ='0', rejected_at='0000-00-00 00:00:00', assigned_by='0', assigned_at='0000-00-00 00:00:00' ";    

		
		$objDb->execute("BEGIN");
		
        $sSQL   = "UPDATE tbl_bookings SET status='$Status' $sSubSql  WHERE id='$Id'";
        $bFlag  = $objDb->query($sSQL);
		
        if ($bFlag == true)
        {        
                $sSQL = "SELECT * FROM tbl_bookings WHERE id='$Id'";
                $objDb->query($sSQL);

                $iBrand                 = $objDb->getField(0,"brand_id");
                $iVendor                = $objDb->getField(0,"vendor_id");
                $sAuditDate             = $objDb->getField(0,"inspection_date");
                $sStartTime             = $objDb->getField(0,"start_time");
                $sEndTime               = $objDb->getField(0,"end_time");
                $iStyleId               = $objDb->getField(0,"style_id");
                $sPos                   = $objDb->getField(0,"pos");
                $sColors                = $objDb->getField(0,"colors");
                $sCommissions           = $objDb->getField(0,"commissions");
                $sSizes                 = $objDb->getField(0,"sizes");
                $iSampleSize            = $objDb->getField(0,"sample_size");
                $iApprovedBy            = $objDb->getField(0,"approved_by");            
                $sApprovedAt            = $objDb->getField(0,"approved_at");
                $iAssignedBy            = $objDb->getField(0,"assigned_at");
                $sAssignedAt            = $objDb->getField(0,"assigned_by");
                $iRejectedBy            = $objDb->getField(0,"rejected_at");
                $sRejectedAt            = $objDb->getField(0,"rejected_by");
        }

        if ($bFlag == true)
        {
						
            if($Status == 'A')
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
					
                    $iId = (int)getDbValue("id", "tbl_qa_reports", "booking_id='$Id'");

                    if($iId == 0)
                    {
                            $iId            = getNextId("tbl_qa_reports");                                                
                            $sAuditCode     = ("S".str_pad($iId, 4, 0, STR_PAD_LEFT));
                            $sCustomSample  = "N"; 
                            $sInspectionType= (($TotalGmts == 0) ? "F" : "V");
                            $ShippingDate   = $sAuditDate;
                            $sPublished     = "A";
                            
                            $AqlLevel = getDbValue("aql", "tbl_brands", "id='$iBrand'");

                            if ($AqlLevel == 0)
                            {
                                    $iParent  = getDbValue("parent_id", "tbl_brands", "id='$iBrand'");
                                    $AqlLevel = getDbValue("aql", "tbl_brands", "id='$iParent'");
                            }
					
                            if($AqlLevel == 0)
                                $AqlLevel   = 2.5;

                            if($SamplingPlan == 0)
                                $SamplingPlan = 1;

                            $sSQL  = ("INSERT INTO tbl_qa_reports (id, audit_code, booking_id, user_id, vendor_id, brand_id, po_id, additional_pos, sizes, style_id, colors, commissions, total_gmts, report_id, audit_stage, audit_date, start_time, end_time, approved, custom_sample, commission_type, date_time, created_at, published, check_level, aql, shipment_date, published_at)
                                   VALUES ('$iId', '$sAuditCode', '$Id', '$Auditor', '$iVendor', '$iBrand', '$iPoId', '$sAdditionalPos', '$sSizes', '$iStyleId', '$sColors', '$sCommissions', '$iSampleSize', '57', 'F', '$sAuditDate', '$sStartTime', '$sEndTime', 'Y', '$sCustomSample', '$sInspectionType', NOW( ), NOW( ), '$sPublished', '$SamplingPlan', '$AqlLevel', '$ShippingDate', NOW())");

                            $bFlag = $objDb->execute($sSQL);
                    }
                    else
                    {
                            $sAuditCode = ("S".str_pad($iId, 4, 0, STR_PAD_LEFT));

                            $sSQL  = "UPDATE tbl_qa_reports SET user_id='$Auditor' WHERE id='$iId'";
                            $bFlag = $objDb->execute($sSQL);
                    }
            }
            else if($sPrevStatus == 'A' && $Status != 'A')
            {
                $sSQL  = "DELETE FROM tbl_qa_reports WHERE booking_id='$Id'";
                $bFlag = $objDb->execute($sSQL);
            }
            
            $sBooking   = "B".str_pad($Id, 5, '0', STR_PAD_LEFT);
     
            switch ($Status)
            {
                case "A" : $Status = "Approved"; break;
                case "C" : $Status = "Cancelled/ Rejected"; break;
                case "P" : $Status = "Set to Pending"; break;
            }
            
            $sSQL = "SELECT name, email, picture from tbl_users where id='{$_SESSION['UserId']}'";
            $objDb->query($sSQL);
            
            $sName    = $objDb->getField(0, "name");
            $sEmail   = $objDb->getField(0, "email");
            
            $sSQL = "SELECT name, email, picture from tbl_users where id='$Auditor'";
            $objDb->query($sSQL);
            
            $sAuditorName    = $objDb->getField(0, "name");
            $sAuditorEmail   = $objDb->getField(0, "email");
            $sPicture        = $objDb->getField(0, "picture");
            
            if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
                $sPicture = "default.jpg";  

            $Thumbnail = ($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture);
            
            $sSQL    = ("SELECT vendor, manager_rep_email, manager_rep FROM tbl_vendors WHERE id='$iVendor'");
            $objDb->query($sSQL);

            $sVendor        = $objDb->getField(0, 0);
            $sManagerEmail  = $objDb->getField(0, 1);
            $sManagerName   = $objDb->getField(0, 2);
            
            $sBookingManagers = getList("tbl_users", "id", "name", "FIND_IN_SET('15', auditor_types)");
            $sBookingMngEmails= getList("tbl_users", "id", "email", "FIND_IN_SET('15', auditor_types)");
            
            $sSQL = "SELECT SUM(pq.quantity)
	         FROM tbl_po_colors pc, tbl_po_quantities pq
			 WHERE pc.po_id=pq.po_id AND pc.style_id='$iStyleId' AND pc.id=pq.color_id AND pc.po_id IN ($sPos) AND FIND_IN_SET(pc.color, '$sColors') AND pq.size_id IN ($sSizes)";
			 
            if ($sCommissions != "")
                    $sSQL .= " AND FIND_IN_SET(pc.line, '$sCommissions') ";
            else
                $sCommissions = "No commission number selected.";

            $objDb->query($sSQL);

            $iQuantity = $objDb->getField(0, 0);

            
            $objEmail = new PHPMailer( );
            
            if($Status == 'Approved')
                $Statement    = "Booking# {$sBookingCode} status has been Approved and an Inspector: {$sAuditorName} has been assigned against Factory: {$sVendor} and Audit Code# is {$sAuditCode}";
            else
                $Statement    = "Booking# {$sBookingCode} has been {$Status} against Factory: {$sVendor}";    
                
            $sBody = @file_get_contents("../emails/booking".($Status == 'Approved'?'-approved':'').".txt");

            $sBody = @str_replace("[BookingStatement]", $Statement, $sBody);
            $sBody = @str_replace("[Thumbnail]", $Thumbnail, $sBody);
            $sBody = @str_replace("[Auditor]", $sAuditorName, $sBody);
            $sBody = @str_replace("[AuditCode]", $sAuditCode, $sBody);            
            $sBody = @str_replace("[Vendor]", $sVendor, $sBody);
            $sBody = @str_replace("[Brand]", getDbValue("brand", "tbl_brands", "id='$iBrand'"), $sBody);
            $sBody = @str_replace("[AuditDate]", $sAuditDate, $sBody);
            $sBody = @str_replace("[StartTime]", $sStartTime, $sBody);
            $sBody = @str_replace("[EndTime]", $sEndTime, $sBody);
            $sBody = @str_replace("[StyleNo]", getDbValue("style", "tbl_styles", "id='$iStyleId'"), $sBody);
            $sBody = @str_replace("[OrderNo]", getDbValue("GROUP_CONCAT(order_no SEPARATOR ', ')", "tbl_po", "id IN ($sPos)"), $sBody);
            $sBody = @str_replace("[Colors]", $sColors, $sBody);
            $sBody = @str_replace("[Sizes]", getDbValue("GROUP_CONCAT(size SEPARATOR ', ')", "tbl_sizes", "id IN ($sSizes)"), $sBody);
            $sBody = @str_replace("[Commissions]", $sCommissions, $sBody);
            $sBody = @str_replace("[AuditStage]", "Final", $sBody);
            $sBody = @str_replace("[Quantity]", $iQuantity, $sBody);

            $sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
            $sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);           
                
            $objEmail->Subject = "Inspection Booking #{$sBooking} is {$Status}";
            $objEmail->MsgHTML($sBody);
            
            $objEmail->IsHTML(true);
            $objEmail->AddAddress($sEmail, $sName);
            
            if($sAuditorEmail != "")
                $objEmail->AddAddress($sAuditorEmail, $sAuditorName);
            
            foreach($sBookingManagers as $iKey => $sManager)
                $objEmail->AddAddress($sBookingMngEmails[$iKey], $sManager);
                        
            if($sManagerEmail != "")
                $objEmail->AddAddress($sManagerEmail, $sManagerName);
            
          if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
                $objEmail->Send( );          
	}
	
            if($bFlag == true)
            {
                $objDb->execute("COMMIT");
				
                $_SESSION['Flag'] = "BOOKING_UPDATED";
?>
<script>
            parent.hideLightview( );
            parent.parent.location.reload();
</script>
<?                    
                exit( );
            }

        else
        {
            $objDb->execute("ROLLBACK");
			
            $_SESSION['Flag'] = "DB_ERROR";
        }
	
	header("Location: {$_SERVER['HTTP_REFERER']}");

	
	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );
?>