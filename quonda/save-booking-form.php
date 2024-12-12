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

        $iId = getNextId("tbl_bookings");

        $sSQL = ("INSERT INTO tbl_bookings (id, brand_id, supplier_id, factory_id, ports, article, ian, quantity, shipments, inspection_date, shipping_date, service_id, sample_for, notes, re_test, created_at, created_by, modified_at, modified_by) VALUES
                                          ('$iId', '".IO::intValue("Brand")."', '".IO::intValue("Vendor")."', '".IO::intValue("Factory")."', '". implode(",", IO::getArray("Ports"))."', '".IO::strValue("Article")."', '".IO::strValue("Ian")."', '".IO::intValue("LotSize")."', '".IO::intValue("Shipments")."', '".IO::strValue("ReqInspectionDate")."', '".IO::strValue("ShippingDate")."', '".IO::strValue("Service")."', '".IO::strValue("SamplePickFor")."', '".IO::strValue("Remarks")."', '".IO::strValue("ReTest")."', NOW(), '".$_SESSION['UserId']."', NOW(), '".$_SESSION['UserId']."')");
        $Flag = $objDb->execute($sSQL);
        
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
            $sSupplier  = getDbValue("supplier", "tbl_suppliers", "id = '".IO::intValue("Vendor")."'");
            $sFactory   = getDbValue("vendor", "tbl_vendors", "id = '".IO::intValue("Factory")."'");
            $sService   = getDbValue("stage", "tbl_audit_stages", "id='".IO::strValue("Service")."'");
            
            $sSQL = "SELECT name, email from tbl_users where id='{$_SESSION['UserId']}'";
            $objDb->query($sSQL);
            
            $sName  = $objDb->getField(0, 'name');
            $sEmail = $objDb->getField(0, 'email');
           
            $sBody = ("Dear {$sName},<br /><br />
                An Inspection Booking Request has been received For HOHENSTEIN/{$sSupplier} in {$sFactory} on Date: ".date('d-M-Y')." for  $sService. <br/><br/> Your Booking No is {$sBooking} <br/><br/> Triple Tree Solutions");
									   
            $objEmail = new PHPMailer( );

            $objEmail->Subject = "Inspection Booking Request# {$sBooking}";
            $objEmail->Body    = $sBody;
            $objEmail->IsHTML(true);
            $objEmail->AddAddress($sEmail, $sName);
            $objEmail->Send( );
        }
        
        if($Flag == true)
                redirect($_SERVER['HTTP_REFERER'], "BOOKING_ADDED");
        else
                $_SESSION['Flag'] = "DB_ERROR";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>