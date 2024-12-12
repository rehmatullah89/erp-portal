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

        $AuditId         = IO::intValue("AuditId");
        $BookingId      = IO::intValue("BookingId");
        $Vendor         = IO::intValue("Vendor");
        $iBrand          = IO::intValue("Brand");
        $AuditStage     = IO::strValue("AuditStage");
        $Auditor        = IO::intValue("Auditor");
        $AuditDate      = IO::strValue("AuditDate");
        $ShippingDate   = IO::strValue("ShippingDate");
        $Sizes          = @implode(",", IO::getArray("Sizes"));
        $Po             = IO::intValue("Po");
        $HohOrderNo     = IO::strValue("OrderNo");
        $StartTime      = (IO::strValue("StartHour").":".IO::strValue("StartMinutes").":00");
	$TotalGmts      = IO::intValue("SampleSize");
        $Report         = IO::intValue("Report");
        $SamplingPlan   = IO::intValue("SamplingPlan");        
        $iHours         = getDbValue("hours", "tbl_countries", "id='$iCountry'");	
	$StartTime      = date("H:i:s", (strtotime($StartTime) - ($iHours * 3600)));
	$AddMinutes     = $TotalGmts*2;            
        $EndTime        = date("H:i:s",strtotime("+{$AddMinutes} minutes",$StartTime));        
        $sCustomSample  = (($TotalGmts == 0) ? "Y" : "N");
        $sInspectionType= (($TotalGmts == 0) ? "F" : "V");
        $sPublished     = "Y";

        if($AqlLevel == "")
            $AqlLevel   = 2.5;
        if($SamplingPlan == 0)
            $SamplingPlan = 1;
        $sError          = "";
        
        $iCountry = getDbValue("country_id", "tbl_vendors", "id='$Vendor'");
	$iHours   = getDbValue("hours", "tbl_countries", "id='$iCountry'");
	
        
        if($Auditor > 0 && $AuditId >0)
        {
            $bFlag = $objDb->execute("BEGIN");
             
            $sCustomSample   = ((IO::intValue("SampleSize") == 0) ? "Y" : "N");
            $sInspectionType = ((IO::intValue("SampleSize") == 0) ? "F" : "V");
            $sPublished      = "Y";
        
            $sSQL  = "UPDATE tbl_qa_reports SET user_id='$Auditor', vendor_id='$Vendor', hoh_order_no='$HohOrderNo', brand_id='$iBrand', booking_id='$BookingId', report_id='$Report', audit_stage='$AuditStage', audit_date='$AuditDate', start_time='$StartTime', end_time='$EndTime', po_id='$Po', sizes='$Sizes', total_gmts='$TotalGmts', custom_sample='$sCustomSample', commission_type='$sInspectionType', published='$sPublished', check_level='$SamplingPlan', aql='$AqlLevel' WHERE id='$AuditId'";
            $bFlag = $objDb->execute($sSQL);
        }
        
        $sSQL = "SELECT status, supplier_id, services, ian, article, quantity, shipments, sample_pick, assigned_to from tbl_bookings where id='$BookingId'";
        $objDb->query($sSQL);

        $Status     = $objDb->getField(0, "status");
        $iSupplier  = $objDb->getField(0, "supplier_id");
        $iStages    = $objDb->getField(0, "services");
        $Article    = $objDb->getField(0, "article");
        $Ian        = $objDb->getField(0, "ian");
        $LotSize    = $objDb->getField(0, "quantity");
        $Shipments  = $objDb->getField(0, "shipments");
        $SamplePick = $objDb->getField(0, "sample_pick");
        $iPrevAuditor = $objDb->getField(0, "assigned_to");
            
        
        if($Status == 'A' /*&& $Auditor > 0 && ($iPrevAuditor != $Auditor || $iPrevAuditor == 0)*/)
        {
            $sSQL   = "UPDATE tbl_bookings SET assigned_to = '$Auditor', assigned_by = '{$_SESSION['UserId']}',  assigned_at = NOW() WHERE id='$BookingId'";
            $bFlag  = $objDb->execute($sSQL);            
            
                $sBooking   = "B".str_pad($BookingId, 5, '0', STR_PAD_LEFT);
                $sBuyer     = getDbValue("brand", "tbl_brands", "id='$iBrand'"); 
                $sFactory   = getDbValue("vendor", "tbl_vendors", "id = '$Vendor'");
                $sSupplier  = getDbValue("supplier", "tbl_suppliers", "id = '$iSupplier'");
                $sServices  = getDbValue("GROUP_CONCAT(service SEPARATOR ',')", "tbl_audit_services", "id IN ($iStages)");
                $sSiteLink  = "<a href='https://hohenstein.3-tree.com'>https://hohenstein.3-tree.com</a>";
                $sWebSite   = "<a href='http://www.3-tree.com'>Triple Tree Solutions</a>";
                
                $sSQL = "SELECT name, email from tbl_users where id='$Auditor'";
                $objDb->query($sSQL);

                $sName              = $objDb->getField(0, "name");
                $sEmail             = $objDb->getField(0, "email");
/*
                $sBody = ("Dear {$sName},<br /><br />
                    An Inspection has been Assigned to you For HOHENSTEIN/{$sBrand} in {$sFactory} on Date: ".date('d-M-Y')." for  $sStage. <br/><br/> Your Audit No is S{$AuditId} <br/><br/> Triple Tree Solutions");
*/
                $sBody = ("Dear {$sName},<br /><br />
                            An Inspection with Inspection Code # S{$AuditId} has been assigned to you with following details:<br /><br />
                            Booking Code: {$sBooking}<br/>
                            HI Order No: {$HohOrderNo}<br/>
                            Inspection Date: {$AuditDate}<br/>
                            Sample Size: {$TotalGmts}<br/><br/>
                                
                            Buyer: {$sBuyer}<br/>
                            Supplier: {$sSupplier}<br/>
                            Factory: {$sFactory}<br/>
                            Services: {$sServices}<br/>                            
                            Shipping Date: {$ShippingDate}<br/>
                            Article: {$Article}<br/>
                            IAN: {$Ian}<br/>
                            Lot Size: {$LotSize}<br/>
                            No of Shipments: {$Shipments}<br/>
                            Sample Pick: {$SamplePick}<br/>
                            <b>Hohenstein Inspection Portal</b><br/>{$sSiteLink}<br/><br/>
                            Powered By <b>Quonda</b>&reg; by {$sWebSite}.<br/><hr><br/>
                            ");
                            
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