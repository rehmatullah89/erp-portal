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

        $Referer                = IO::strValue("Referer");
        $Id                     = IO::intValue("Id");
        $Brand          	= IO::intValue("Brand");
        $Factory        	= IO::intValue("Factory");
        $Ports                  = implode(",", IO::getArray("Ports"));
        $Vendor         	= IO::intValue("Vendor");
        $Article        	= IO::strValue("Article");
        $Ian        		= IO::strValue("Ian");
        $LotSize        	= IO::strValue("LotSize");
        $Shipments      	= IO::strValue("Shipments");
        $ReqInspectionDate      = IO::strValue("ReqInspectionDate");
        $ShippingDate           = IO::strValue("ShippingDate");
        $Services  		= implode(",", IO::getArray("Services"));
        $SamplePickFor          = implode(",", IO::getArray("SamplePickFor"));
        $Remarks      		= IO::strValue("Remarks");
        $StatusComments		= IO::strValue("StatusComments");
        $Status      		= IO::strValue("Status");
        $ReTest      		= (IO::strValue("ReTest") == 'Y'?'Y':'N');
        $Destinations           = IO::strValue("Destinations");
        $ContactPersonName      = IO::strValue("ContactPersonName");
        $ContactPersonPhone     = IO::strValue("ContactPersonPhone");
        $ContactPersonEmail     = IO::strValue("ContactPersonEmail");
        $ContactPersonFax       = IO::strValue("ContactPersonFax");	
        $FactoryPersonName      = IO::strValue("FactoryPersonName");
        $FactoryPersonPhone     = IO::strValue("FactoryPersonPhone");
        $FactoryPersonEmail     = IO::strValue("FactoryPersonEmail");
        $FactoryPersonFax       = IO::strValue("FactoryPersonFax");
	$sError                 = "";
        
        $sSubSql = "";        
        $sPrevStatus = getDbValue("status", "tbl_bookings", "id='$Id'");
        
        if($Status != $sPrevStatus && $Status == 'A')
            $sSubSql = " status='A', accepted_by='{$_SESSION['UserId']}', accepted_at = NOW(), ";             
        else if($Status != $sPrevStatus && $Status == 'R')
            $sSubSql = " status='R', rejected_by='{$_SESSION['UserId']}', rejected_at = NOW(), ";         
        else if($Status != $sPrevStatus && $Status == 'C')
            $sSubSql = " status='C', cancelled_by='{$_SESSION['UserId']}', cancelled_at = NOW(), ";
        else if($Status != $sPrevStatus && $Status == 'P')
            $sSubSql = " status='P', accepted_by='0', accepted_at = '0000-00-00 00:00:00', rejected_by ='0', rejected_at='0000-00-00 00:00:00', cancelled_by='0', cancelled_at='0000-00-00 00:00:00', assigned_to='0', ";    
            
        $sSQL   = "UPDATE tbl_bookings SET brand_id = '$Brand', supplier_id = '$Vendor', factory_id = '$Factory', ports='$Ports', article = '$Article', ian = '$Ian', quantity = '$LotSize', shipments = '$Shipments', inspection_date = '$ReqInspectionDate', shipping_date='$ShippingDate', services = '$Services', sample_pick = '$SamplePickFor', notes = '$Remarks', $sSubSql status_comments='$StatusComments', re_test='$ReTest', cp_name='$ContactPersonName', cp_phone='$ContactPersonPhone', cp_email='$ContactPersonEmail', cp_fax='$ContactPersonFax', fp_name='$FactoryPersonName', fp_phone='$FactoryPersonPhone', fp_email='$FactoryPersonEmail', fp_fax='$FactoryPersonFax', destinations='$Destinations', modified_at = NOW(), modified_by = '{$_SESSION['UserId']}' WHERE id='$Id'";
        $Flag   = $objDb->execute($sSQL);
        
        if($Flag == true)
        {
            $sSQL = "DELETE FROM tbl_booking_materials WHERE booking_id='$Id'";
            $Flag = $objDb->execute($sSQL);
        }
        
        if ($Flag == true)
        {
            $sMaterials         = IO::getArray("Material");
            $sMaterialColors    = IO::getArray("MaterialColor");
            $sMaterialRemarks   = IO::getArray("MaterialRemarks");
            
            $iMaterialId    = getNextId("tbl_booking_materials");

            foreach($sMaterials as $iKey => $sMaterial)
            {
                $sMaterialColor = $sMaterialColors[$iKey];
                $sMaterialRemark= $sMaterialRemarks[$iKey];
                
                if(trim($sMaterial) != "")
                {
                    $sSQL = "INSERT INTO tbl_booking_materials(id, booking_id, material, color, remarks) VALUES ('$iMaterialId', '$Id', '$sMaterial', '$sMaterialColor', '$sMaterialRemark')";
                    $Flag = $objDb->execute($sSQL);
                    
                    $iMaterialId++;
                    
                    if($Flag == false)
                        break;
                }
            }
        }
        
        if ($Flag == true)
        {
            $sAllFiles = $_FILES["Attachments"]['name'];
            
            @list($sYear, $sMonth, $sDay) = @explode("-", $ReqInspectionDate);

            @mkdir(($sBaseDir.BOOKINGS_DIR.$sYear), 0777);
            @mkdir(($sBaseDir.BOOKINGS_DIR.$sYear."/".$sMonth), 0777);
            @mkdir(($sBaseDir.BOOKINGS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);

            foreach($sAllFiles as $iKey => $sFileName)
            {
                if ($sFileName != "")
                {
                    $exts = explode('.', $sFileName);
                    $extension = end($exts);

                    if(@in_array(strtolower($extension), array('jpg','jpeg','gif','png','pdf','doc','docx','csv','xls','xlsx','txt','ppt','pptx','zip','rar')))
                    {
                        $sPicture = ("BOOKING_".$Id."_".rand(1, 100).'.'.$extension);

                        if (@move_uploaded_file($_FILES["Attachments"]['tmp_name'][$iKey], ($sBaseDir.BOOKINGS_DIR.$sYear.'/'.$sMonth.'/'.$sDay.'/'.$sPicture)))
                        {
                                $FileId = getNextId("tbl_booking_files");
                                $sSQL   = "INSERT INTO tbl_booking_files SET id = '$FileId', booking_id='$Id', file = '$sPicture'";
                                $Flag   = $objDb->execute($sSQL);
                        }

                        if($Flag == false)
                            break;
                    }
                }
            }
                   
        }
        
        if($Flag == true && $Status != $sPrevStatus && @in_array($Status, array('A','R','C','P')))
        {
            $sManagerName       = "";
            $sManagerEmail      = "";
            $iStatusChangedBy   = "";
                    
            if($Status == 'A')
                $iStatusChangedBy = getDbValue("accepted_by", "tbl_bookings", "id='$Id'");
            else if($Status == 'R')
                $iStatusChangedBy = getDbValue("rejected_by", "tbl_bookings", "id='$Id'");
            else if($Status == 'C')
                $iStatusChangedBy = getDbValue("cancelled_by", "tbl_bookings", "id='$Id'");
            else if($Status == 'P')
                $iStatusChangedBy = getDbValue("modified_by", "tbl_bookings", "id='$Id'");
            
            if($iStatusChangedBy != "")
            {
                $sManagerName  = getDbValue("name", "tbl_users", "id='$iStatusChangedBy'");
                $sManagerEmail = getDbValue("email", "tbl_users", "id='$iStatusChangedBy'");
            }
            
            switch ($Status)
            {
                case "A" : $Status = "Approved"; break;
                case "R" : $Status = "Rejected"; break;
                case "C" : $Status = "Cancelled"; break;
                case "P" : $Status = "set to Pending"; break;
            }
            
            $sBooking   = "B".str_pad($Id, 5, '0', STR_PAD_LEFT);
            $sBuyer     = getDbValue("brand", "tbl_brands", "id = '$Brand'");
            $sSupplier  = getDbValue("supplier", "tbl_suppliers", "id = '$Vendor'");
            $sFactory   = getDbValue("vendor", "tbl_vendors", "id = '$Factory'");
            $sServices  = getDbValue("GROUP_CONCAT(service SEPARATOR ',')", "tbl_audit_services", "id IN ($Services)");
            $iUser      = getDbValue("created_by", "tbl_bookings", "id='$Id'");
            
            $sWebSite   = "<a href='http://www.3-tree.com'>Triple Tree Solutions</a>";
            $sSiteLink  = "<a href='https://hohenstein.3-tree.com'>https://hohenstein.3-tree.com</a>";
            $sBookingForm  = ("<a href='".SITE_URL."bookings/export-booking-form.php?Id=".$iId."' target='_blank'>Download</a>");
            
            $sSQL = "SELECT name, email from tbl_users where id='$iUser'";
            $objDb->query($sSQL);
            
            $sName              = $objDb->getField(0, "name");
            $sEmail             = $objDb->getField(0, "email");
            
            /*$sBody = ("Dear {$sName},<br /><br />
                An Inspection Booking Request has been {$Status} For HOHENSTEIN/{$sSupplier} in {$sFactory} on Date: ".date('d-M-Y')." for  $sStage. <br/><br/> Your Booking No is {$sBooking} <br/><br/> Triple Tree Solutions");
            */
            $sBody = ("Dear {$sName},<br /><br />
               The status of the Booking Request # {$sBooking} has been changed, details as follows:<br /><br />
               Buyer: {$sBuyer}<br/>
               Supplier: {$sSupplier}<br/>
               Factory: {$sFactory}<br/>
               Services: {$sServices}<br/>
               Inspection Date: {$ReqInspectionDate}<br/>
               Shipping Date: {$ShippingDate}<br/>
               Article: {$Article}<br/>
               IAN: {$Ian}<br/>
               Lot Size: {$LotSize}<br/>
               No of Shipments: {$Shipments}<br/>
               Sample Pick: {$SamplePickFor}<br/>
               Status: {$Status}<br/><br/>
               <b>Remarks:</b><br/>{$Remarks}<br/><br/><hr>
               PDF Booking Form: {$sBookingForm}<br/><br/>
               <b>Hohenstein Inspection Portal</b><br/>{$sSiteLink}<br/><br/>
               Powered By <b>Quonda</b>&reg; by {$sWebSite}.<br/><hr><br/>
               ");
            
            $objEmail = new PHPMailer( );

            $objEmail->Subject = "Inspection Booking #{$sBooking} is {$Status}";
            $objEmail->Body    = $sBody;
            $objEmail->IsHTML(true);
            $objEmail->AddAddress($sEmail, $sName);
            
            if($sManagerName != "" && $sManagerEmail != "")
                $objEmail->AddAddress($sManagerEmail, $sManagerName);
            
            $objEmail->Send( );
        }
        
        if($Flag == true)
                redirect($Referer, "BOOKING_UPDATED");
        else
                $_SESSION['Flag'] = "DB_ERROR";
        
	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>