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

        $BookingId       = IO::intValue("BookingId");
        $AuditId         = IO::intValue("AuditId");
        $Factory         = IO::intValue("Factory");
        $AuditStage      = IO::strValue("AuditStage");
        $Auditor         = IO::intValue("Auditor");
        $Report          = IO::intValue("Report");
        $Brand           = IO::intValue("Brand");
	$Vendor          = IO::intValue("Vendor");
        $AuditDate       = IO::strValue("AuditDate");
        $HohOrderNo      = IO::strValue("HohOrderNo");
	$StartTime       = (IO::strValue("StartHour").":".IO::strValue("StartMinutes").":00");
	$EndTime         = (IO::strValue("EndHour").":".IO::strValue("EndMinutes").":00");
	$OrderNo         = IO::strValue("OrderNo");
	$Po              = IO::intValue("Po");
        $AdditionalPO    = IO::strValue("AdditionalPO");
	$StyleNo         = IO::intValue("StyleNo");
	$Colors          = @implode(",", IO::getArray("Colors"));
        $Sizes           = @implode(",", IO::getArray("Sizes"));
	$TotalGmts       = IO::intValue("SampleSize"); 
        $SamplingPlan   = IO::intValue("SamplingPlan");
        $AqlLevel       = IO::floatValue("AqlLevel");
        if($AqlLevel == "")
            $AqlLevel   = 2.5;
        if($SamplingPlan == 0)
            $SamplingPlan = 1;
        $sError          = "";
        
        $iCountry = getDbValue("country_id", "tbl_vendors", "id='$Vendor'");
	$iHours   = getDbValue("hours", "tbl_countries", "id='$iCountry'");
	
	if ($iHours != 0)
	{
		$StartTime = date("H:i:s", (strtotime($StartTime) - ($iHours * 3600)));
		$EndTime   = date("H:i:s", (strtotime($EndTime) - ($iHours * 3600)));
	}
        
        if($Auditor > 0 && $AuditId >0)
        {
            $bFlag = $objDb->execute("BEGIN");
             
            $sCustomSample   = ((IO::intValue("SampleSize") == 0) ? "Y" : "N");
            $sInspectionType = ((IO::intValue("SampleSize") == 0) ? "F" : "V");
            $iBrand          = getDbValue("sub_brand_id", "tbl_styles", "id='$StyleNo'");
            $sPublished      = "Y";
        
            $sSQL  = "UPDATE tbl_qa_reports SET user_id='$Auditor', vendor_id='$Vendor', hoh_order_no='$HohOrderNo', brand_id='$iBrand', booking_id='$BookingId', report_id='$Report', audit_stage='$AuditStage', audit_date='$AuditDate', start_time='$StartTime', end_time='$EndTime', po_id='$Po', style_id='$StyleNo', colors='$Colors', sizes='$Sizes', total_gmts='$TotalGmts', custom_sample='$sCustomSample', commission_type='$sInspectionType', additional_pos='$AdditionalPO', published='$sPublished', check_level='$SamplingPlan', aql='$AqlLevel' WHERE id='$AuditId'";
            $bFlag = $objDb->execute($sSQL);
        }
        
        $Status         = getDbValue("status", "tbl_bookings", "id='$BookingId'");
        $iPrevAuditor   = (int)getDbValue("assigned_to", "tbl_bookings", "id='$BookingId'");
        
        if($Status == 'A' /*&& $Auditor > 0 && ($iPrevAuditor != $Auditor || $iPrevAuditor == 0)*/)
        {
            $sSQL   = "UPDATE tbl_bookings SET assigned_to = '$Auditor', assigned_by = '{$_SESSION['UserId']}',  assigned_at = NOW() WHERE id='$BookingId'";
            $bFlag  = $objDb->execute($sSQL);            
            
                $sBooking   = "B".str_pad($BookingId, 5, '0', STR_PAD_LEFT);
                $sBrand     = getDbValue("brand", "tbl_brands", "id='$Brand'"); 
                $sFactory   = getDbValue("vendor", "tbl_vendors", "id = '$Vendor'");
                $iStages    = getDbValue("services", "tbl_bookings", "id = '$BookingId'");
                $sStage     = getDbValue("GROUP_CONCAT(service SEPARATOR ',')", "tbl_audit_services", "id IN ($iStages)");

                $sSQL = "SELECT name, email from tbl_users where id='$Auditor'";
                $objDb->query($sSQL);

                $sName              = $objDb->getField(0, "name");
                $sEmail             = $objDb->getField(0, "email");

                $sBody = ("Dear {$sName},<br /><br />
                    An Inspection has been Assigned to you For HOHENSTEIN/{$sBrand} in {$sFactory} on Date: ".date('d-M-Y')." for  $sStage. <br/><br/> Your Audit No is S{$AuditId} <br/><br/> Triple Tree Solutions");

                $objEmail = new PHPMailer( );

                $objEmail->Subject = "Inspection Audit #s{$AuditId} is Assigned";
                $objEmail->Body    = $sBody;
                $objEmail->IsHTML(true);
                $objEmail->AddAddress($sEmail, $sName);

                $objEmail->Send( );
        }
        
        
        if($bFlag == true || $_SESSION['Flag'] == "")
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

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>