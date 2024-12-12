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

        $iId             = getNextId("tbl_bookings");
        $sInspectionDate = IO::strValue("ReqInspectionDate");
        $sShippingDate   = IO::strValue("ShippingDate");
        $sArticle        = IO::strValue("Article");
        $sIan            = IO::strValue("Ian");
        $iLotSize        = IO::intValue("LotSize");
        $iShipments      = IO::intValue("Shipments");
        $iSamplePicks    = implode(",", IO::getArray("SamplePickFor"));
        $sPorts          = implode(",", IO::getArray("Ports"));
        $sRemarks        = IO::strValue("Remarks");
        
        $sSQL = ("INSERT INTO tbl_bookings (id, brand_id, supplier_id, factory_id, ports, article, ian, quantity, shipments, inspection_date, shipping_date, services, sample_pick, notes, re_test, cp_name, cp_phone, cp_email, cp_fax, destinations, fp_name, fp_phone, fp_email, fp_fax, created_at, created_by, modified_at, modified_by) VALUES
                                          ('$iId', '".IO::intValue("Brand")."', '".IO::intValue("Vendor")."', '".IO::intValue("Factory")."', '$sPorts', '$sArticle', '$sIan', '$iLotSize', '$iShipments', '$sInspectionDate', '$sShippingDate', '". implode(",", IO::getArray("Services"))."', '$iSamplePicks', '$sRemarks', '".IO::strValue("ReTest")."', '".IO::strValue("ContactPersonName")."', '".IO::strValue("ContactPersonPhone")."', '".IO::strValue("ContactPersonEmail")."', '".IO::strValue("ContactPersonFax")."', '".IO::strValue("Destinations")."', '".IO::strValue("FactoryPersonName")."', '".IO::strValue("FactoryPersonPhone")."', '".IO::strValue("FactoryPersonEmail")."', '".IO::strValue("FactoryPersonFax")."', NOW(), '".$_SESSION['UserId']."', NOW(), '".$_SESSION['UserId']."')");
        $Flag = $objDb->execute($sSQL);
        
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
                    $sSQL = "INSERT INTO tbl_booking_materials(id, booking_id, material, color, remarks) VALUES ('$iMaterialId', '$iId', '$sMaterial', '$sMaterialColor', '$sMaterialRemark')";
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
            
            @list($sYear, $sMonth, $sDay) = @explode("-", IO::strValue("ReqInspectionDate"));

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
                        $sPicture = ("BOOKING_".$iId."_".rand(1, 100).'.'.$extension);

                        if (@move_uploaded_file($_FILES["Attachments"]['tmp_name'][$iKey], ($sBaseDir.BOOKINGS_DIR.$sYear.'/'.$sMonth.'/'.$sDay.'/'.$sPicture)))
                        {
                                $FileId = getNextId("tbl_booking_files");
                                $sSQL   = "INSERT INTO tbl_booking_files SET id = '$FileId', booking_id='$iId', file = '$sPicture'";
                                $Flag   = $objDb->execute($sSQL);
                        }

                        if($Flag == false)
                            break;
                    }
                }
            }
                   
        }
        
        if($Flag == true)
        {
            $sBooking   = "B".str_pad($iId, 5, '0', STR_PAD_LEFT);
            $sBuyer     = getDbValue("brand", "tbl_brands", "id = '".IO::intValue("Brand")."'");
            $sSupplier  = getDbValue("supplier", "tbl_suppliers", "id = '".IO::intValue("Vendor")."'");
            $sFactory   = getDbValue("vendor", "tbl_vendors", "id = '".IO::intValue("Factory")."'");
            $sServices  = getDbValue("GROUP_CONCAT(service SEPARATOR ',')", "tbl_audit_services", "id IN (".implode(",", IO::getArray("Services")).")");
            $sWebSite   = "<a href='http://www.3-tree.com'>Triple Tree Solutions</a>";
            $sSiteLink  = "<a href='https://hohenstein.3-tree.com'>https://hohenstein.3-tree.com</a>";
            $sBookingForm  = ("<a href='".SITE_URL."bookings/export-booking-form.php?Id=".$iId."' target='_blank'>Download</a>");
            
            $sSQL = "SELECT name, email from tbl_users where id='{$_SESSION['UserId']}'";
            $objDb->query($sSQL);
            
            $sName  = $objDb->getField(0, 'name');
            $sEmail = $objDb->getField(0, 'email');
           
            $sBody = ("Dear {$sName},<br /><br />
                The summary of Booking Request #{$sBooking} is as follows.<br /><br />
                Buyer: {$sBuyer}<br/>
                Supplier: {$sSupplier}<br/>
                Factory: {$sFactory}<br/>
                Services: {$sServices}<br/>
                Inspection Date: {$sInspectionDate}<br/>
                Shipping Date: {$sShippingDate}<br/>
                Article: {$sArticle}<br/>
                IAN: {$sIan}<br/>
                Lot Size: {$iLotSize}<br/>
                No of Shipments: {$iShipments}<br/>
                Sample Pick: {$iSamplePicks}<br/>
                Status: Pending<br/><br/>
                <b>Remarks:</b><br/>{$sRemarks}<br/><br/><hr>
                PDF Booking Form: {$sBookingForm}<br/><br/>
                <b>Hohenstein Inspection Portal</b><br/>{$sSiteLink}<br/><br/>
                Powered By <b>Quonda</b>&reg; by {$sWebSite}.<br/><hr><br/>
                ");
									   
            $objEmail = new PHPMailer( );

            $objEmail->Subject = "Inspection Booking Request# {$sBooking}";
            $objEmail->Body    = $sBody;
            $objEmail->IsHTML(true);
            $objEmail->AddAddress($sEmail, $sName);
            
            $objEmail->Send( );
        }
        
        if($Flag == true)
                redirect("bookings.php", "BOOKING_ADDED");
        else
                $_SESSION['Flag'] = "DB_ERROR";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>