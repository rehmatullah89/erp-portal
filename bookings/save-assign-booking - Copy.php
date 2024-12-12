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

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

        $_SESSION['Flag'] = "";
         
        $BookingId      = IO::intValue("BookingId");
        $AuditStage     = IO::strValue("AuditStage");
        $Auditor        = IO::intValue("Auditor");
        $Brand          = IO::intValue("Brand");
        $Vendor         = IO::intValue("Vendor");
        $AuditDate      = IO::strValue("AuditDate");
        $HohOrderNo     = IO::strValue("HohOrderNo");
        $StartTime      = (IO::strValue("StartHour").":".IO::strValue("StartMinutes").":00");
	$EndTime        = (IO::strValue("EndHour").":".IO::strValue("EndMinutes").":00");
        $Po             = IO::intValue("Po");
	$AdditionalPOs  = IO::strValue("AdditionalPO");
        $StyleNo        = IO::intValue("StyleNo");
        $TotalGmts      = IO::intValue("SampleSize");
        $Report         = IO::intValue("Report");
        $SamplingPlan   = IO::intValue("SamplingPlan");
        $AqlLevel       = IO::floatValue("AqlLevel");
        $iCountry       = getDbValue("country_id", "tbl_vendors", "id='$Vendor'");
        $iHours         = getDbValue("hours", "tbl_countries", "id='$iCountry'");	
	$StartTime      = date("H:i:s", (strtotime($StartTime) - ($iHours * 3600)));
	$EndTime        = date("H:i:s", (strtotime($EndTime) - ($iHours * 3600)));     
        $sCustomSample  = (($TotalGmts == 0) ? "Y" : "N");
        $sInspectionType= (($TotalGmts == 0) ? "F" : "V");
        $sPublished     = "Y";
        if($AqlLevel == "")
            $AqlLevel   = 2.5;
        if($SamplingPlan == 0)
            $SamplingPlan = 1;
        $sError         = "";
        
        if ((int)getDbValue("COUNT(1)", "tbl_qa_reports", "user_id='$Auditor' AND audit_date='$AuditDate' AND (('$StartTime' BETWEEN start_time AND end_time) OR ('$EndTime' BETWEEN start_time AND end_time) OR (start_time BETWEEN '$StartTime' AND '$EndTime') OR (end_time BETWEEN '$StartTime' AND '$EndTime'))") > 0)
		$_SESSION['Flag'] = "INVALID_AUDIT_TIME";

	else if ((int)getDbValue("COUNT(1)", "tbl_user_schedule", "user_id='$Auditor' AND ('$AuditDate' BETWEEN from_date AND to_date) AND (('$StartTime' BETWEEN start_time AND end_time) OR ('$EndTime' BETWEEN start_time AND end_time) OR (start_time BETWEEN '$StartTime' AND '$EndTime') OR (end_time BETWEEN '$StartTime' AND '$EndTime'))") > 0)
			$_SESSION['Flag'] = "INVALID_AUDIT_TIME";

	else if (strtotime("{$AuditDate} {$EndTime}") <= strtotime("{$AuditDate} {$StartTime}"))
		$_SESSION['Flag'] = "INVALID_AUDIT_END_TIME";

	if ($_SESSION['Flag'] == "" && $Po == 0)
		$_SESSION['Flag'] = "INVALID_ORDER_NO";

	if ($_SESSION['Flag'] == "" && $StyleNo == 0)
		$_SESSION['Flag'] = "INVALID_STYLE_NO";
	
	if ($_SESSION['Flag'] == "" && getDbValue("COUNT(1)", "tbl_po", "vendor_id='$Vendor' AND id='$Po'") == 0)
		$_SESSION['Flag'] = "INVALID_ORDER_NO";
        
        if ($_SESSION['Flag'] == "")
	{        
            $bFlag = $objDb->execute("BEGIN");
            
            $sSQL   = "UPDATE tbl_bookings SET assigned_to = '$Auditor', assigned_by = '{$_SESSION['UserId']}',  assigned_at = NOW() WHERE id='$BookingId'";
            $bFlag   = $objDb->execute($sSQL);
        
            if($bFlag == true)
            {
                $sSQL  = ("INSERT INTO tbl_qa_reports (user_id, vendor_id, brand_id, po_id, style_id, booking_id, colors, sizes, total_gmts, report_id, audit_stage, audit_date, hoh_order_no, start_time, end_time, approved, custom_sample, commission_type, date_time, created_at, status, additional_pos, published, check_level, aql)
                                                                               VALUES ('$Auditor', '$Vendor', '$Brand', '$Po', '$StyleNo', '$BookingId', '".@implode(",", IO::getArray("Colors"))."', '".@implode(",", IO::getArray("Sizes"))."', '$TotalGmts', '$Report', '$AuditStage', '$AuditDate', '$HohOrderNo', '$StartTime', '$EndTime', 'Y', '$sCustomSample', '$sInspectionType', NOW( ), NOW( ), '', '$AdditionalPOs', '$sPublished', '$SamplingPlan', '$AqlLevel')");
                $bFlag = $objDb->execute($sSQL);
            }
            
            if ($bFlag == true)
            {
                    $iId        = $objDb->getAutoNumber( );
                    $sAuditCode = ("S".str_pad($iId, 4, 0, STR_PAD_LEFT));

                    $sSQL  = "UPDATE tbl_qa_reports SET audit_code='$sAuditCode' WHERE id='$iId'";
                    $bFlag = $objDb->execute($sSQL);
            }
        
            $Status =  getDbValue("status", "tbl_bookings", "id='$BookingId'");

            if($bFlag == true && $Status == 'A')
            {
                $sBooking   = "B".str_pad($BookingId, 5, '0', STR_PAD_LEFT);
                $sBrand     = getDbValue("brand", "tbl_brands", "id='$Brand'"); 
//                $sSupplier  = getDbValue("supplier", "tbl_suppliers", "id = '$Vendor'");
                $sFactory   = getDbValue("vendor", "tbl_vendors", "id = '$Vendor'");
                $iStages    = getDbValue("services", "tbl_bookings", "id = '$BookingId'");
                $sStage     = getDbValue("GROUP_CONCAT(service SEPARATOR ',')", "tbl_audit_services", "id IN ($iStages)");

                $sSQL = "SELECT name, email from tbl_users where id='$Auditor'";
                $objDb->query($sSQL);

                $sName              = $objDb->getField(0, "name");
                $sEmail             = $objDb->getField(0, "email");

                $sBody = ("Dear {$sName},<br /><br />
                    An Inspection has been Assigned to you For HOHENSTEIN/{$sBrand} in {$sFactory} on Date: ".date('d-M-Y')." for  $sStage. <br/><br/> Your Audit No is {$sAuditCode} <br/><br/> Triple Tree Solutions");

                $objEmail = new PHPMailer( );

                $objEmail->Subject = "Inspection Audit #{$sAuditCode} is Assigned";
                $objEmail->Body    = $sBody;
                $objEmail->IsHTML(true);
                $objEmail->AddAddress($sEmail, $sName);

                $objEmail->Send( );
            }
        
            if($bFlag == true)
            {
                $objDb->execute("COMMIT");
                $_SESSION['Flag'] = "AUDIT_CODE_ADDED";
            ?>
<script>
                parent.hideLightview( );
                parent.parent.location.reload();
</script>
<?
            exit( );
                //redirect($_SERVER['HTTP_REFERER'], "BOOKING_UPDATED");
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