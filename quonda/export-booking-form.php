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
     //ini_set('display_errors', 1);
     //error_reporting(E_ALL);

        $sBaseDir = "../";
        require_once($sBaseDir."requires/session.php");
        require_once($sBaseDir."requires/fpdf/fpdf.php");
	require_once($sBaseDir."requires/fpdi/fpdi.php");
	require_once($sBaseDir."requires/qrcode/qrlib.php");
        
        $objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
        
        $Id = IO::intValue("Id");
        
        function convertPngToJpg($filePath, $quality=50)
        {
            $image = imagecreatefrompng($filePath);
            $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
            imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
            imagealphablending($bg, TRUE);
            imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
            imagedestroy($image);
            imagejpeg($bg, $filePath . ".jpg", $quality);
            imagedestroy($bg);
            
            return $filePath . ".jpg";
        }
        
	$sSQL = "SELECT *,
                        (SELECT brand from tbl_brands where id=tbl_bookings.brand_id) as _Brand,
                        (SELECT signature from tbl_users where id=tbl_bookings.created_by) as _Signature
	         FROM tbl_bookings
	         WHERE id='$Id'";
	$objDb->query($sSQL);

        $sBrand          	= $objDb->getField(0,"_Brand");
        $iBrand          	= $objDb->getField(0,"brand_id");
        $iFactory        	= $objDb->getField(0,"factory_id");
        $iSupplier         	= $objDb->getField(0,"supplier_id");
        $sArticle        	= $objDb->getField(0,"article");
        $sIan        		= $objDb->getField(0,"ian");
        $iLotSize        	= $objDb->getField(0,"quantity");
        $iShipments      	= $objDb->getField(0,"shipments");
        $sPlannedShippingDate   = $objDb->getField(0,"shipping_date");
        $sReqInspectionDate     = $objDb->getField(0,"inspection_date");
        $iService  		= $objDb->getField(0,"service_id");
        $sSamplePickFor         = $objDb->getField(0,"sample_for");
        $sRemarks      		= $objDb->getField(0,"notes");
        $sPorts      		= $objDb->getField(0,"ports");
        $sSignature    		= $objDb->getField(0,"_Signature");
        $sReTest    		= $objDb->getField(0,"re_test");
        $sCreatedAt    		= $objDb->getField(0,"created_at");
        $sAuditCode             = "B".str_pad($Id, 5, '0', STR_PAD_LEFT);

        $sSQL = "SELECT supplier, address, contact_person, person_email, phone, fax
	         FROM tbl_suppliers
	         WHERE id='$iSupplier'";
	$objDb->query($sSQL);
        
        $Supplier           = $objDb->getField(0,"supplier");
        $SupplierPhone      = $objDb->getField(0,"phone");
        $SupplierFax        = $objDb->getField(0,"fax");
        $SupplierAddress    = $objDb->getField(0,"address");
        $SupplierRep        = $objDb->getField(0,"contact_person");
        $SupplierRepEmail   = $objDb->getField(0,"person_email");
        
        
        $sSQL = "SELECT vendor, address, manager_rep, manager_rep_email, rep_picture, phone, fax, latitude, longitude
	         FROM tbl_vendors
	         WHERE id='$iFactory'";
	$objDb->query($sSQL);
        
        
        $sLatitude         = $objDb->getField(0,"latitude"); 
        $sLongitude        = $objDb->getField(0,"longitude");
        $Factory           = $objDb->getField(0,"vendor");
        $FactoryPhone      = $objDb->getField(0,"phone");
        $FactoryFax        = $objDb->getField(0,"fax");
        $FactoryAddress    = $objDb->getField(0,"address");
        $FactoryRep        = $objDb->getField(0,"manager_rep");
        $FactoryRepPic     = $objDb->getField(0,"rep_picture");
        $FactoryRepEmail   = $objDb->getField(0,"manager_rep_email");
        
	//////////////////////////////////////////////////////Page 1//////////////////////////////////////////////////////////////
	$objPdf = new FPDI( );

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/booking-form.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 176.5, 11, 22);

        $objPdf->SetFont('Arial', '', 9);
        $objPdf->Text(10, 7, "Page #1");

	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);
	$objPdf->Text(183, 33.5, "{$sAuditCode}");

	// Report Details
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->Text(54, 49, $Supplier);
        $objPdf->Text(54, 54, $SupplierAddress);
        $objPdf->Text(54, 59, $SupplierRep);
        $objPdf->Text(54, 64, $SupplierRepEmail);
        
        $objPdf->Text(158.5, 59, $SupplierPhone);
        $objPdf->Text(158.5, 64, $SupplierFax);
        
        $objPdf->Text(54, 70, $sBrand);
        $objPdf->Text(54, 75, getDbValue("GROUP_CONCAT(destination SEPARATOR ',')", "tbl_destinations", "id IN ($iDestinations)"));
        $objPdf->Text(54, 80.2, $sArticle);
        $objPdf->Text(54, 85.3, $sIan);
        $objPdf->Text(54, 90.3, $iLotSize);
        $objPdf->Text(54, 95.3, $iShipments);
        
        $objPdf->Text(67, 108.5, $Factory);
        $objPdf->Text(67, 113.5, $FactoryAddress);
        $objPdf->Text(67, 119, $iFactory);
        $objPdf->Text(67, 124, $sReqInspectionDate);
       
        $objPdf->Text(39, 136, $FactoryRep);
        $objPdf->Text(156, 138.3, $FactoryRepEmail);
        $objPdf->Text(156, 147, $FactoryPhone);
        $objPdf->Text(156, 155.3, $FactoryFax);

        if ($sSignature != "" && @file_exists($sBaseDir.USER_SIGNATURES_IMG_DIR.$sSignature))
        {
            $objPdf->Image($sBaseDir.USER_SIGNATURES_IMG_DIR.$sSignature, 32, 140, 23, 17);
        }
        
        if($FactoryRepPic == "")
            $FactoryRepPic = "default.jpg";
        
        if (@file_exists($sBaseDir.'files/representative/'.$FactoryRepPic))
        {
            $objPdf->Image($sBaseDir.'files/representative/'.$FactoryRepPic, 92, 127, 32, 30);
        }
        
        if ($sLatitude != "" && $sLongitude != "")
	{	
            $objPdf->SetFont('Arial', '', 7);
            $objPdf->SetTextColor(6, 82, 195);

            $objPdf->SetXY(96, 162);
            $objPdf->Write(5, "(". formatNumber($sLatitude, true, 8).",". formatNumber($sLongitude, true, 8).")", "http://maps.google.com/maps?q={$sLatitude},{$sLongitude}&z=12");

            $objPdf->SetFont('Arial', '', 7);
            $objPdf->SetTextColor(50, 50, 50);

            $objPdf->Text(90, 173, "(Click on the link above to open location in Google Maps)");

            $map = file_get_contents("https://maps.googleapis.com/maps/api/staticmap?center=".$sLatitude.",".$sLongitude."&zoom=15&size=1000x550&markers=color:red|".$sLatitude.",".$sLongitude."&key=AIzaSyDKes4Df5NgRtYcQ_muoqUadWiI3v6GURg"); 

            $image = imagecreatefromstring($map);
            $saved = imagejpeg($image, $sBaseDir."temp2/googlemapImage2.jpg");
            unset($map);

            $objPdf->Image($sBaseDir.'temp2/googlemapImage2.jpg', 65, 180, 120,68);
            unlink($sBaseDir.'temp2/googlemapImage2.jpg');
	}	
	else
            $objPdf->Text(95, 131, getDbValue("city", "tbl_vendors", "id='$iFactory'"));
       
        ///////////////////////////////////////////Page #2 ////////////////////////////////////////////////////////////////
        $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/booking-form.pdf");
	$iTemplateId = $objPdf->importPage(2, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 176.5, 11, 22);

        $objPdf->SetFont('Arial', '', 9);
        $objPdf->Text(10, 7, "Page #2");

        $objPdf->Text(20, 53.5, getDbValue("stage", "tbl_audit_stages", "id='$iService'"));
        
	$objPdf->SetFont('Arial', '', 6);
	$objPdf->SetTextColor(50, 50, 50);
	$objPdf->Text(183, 33.5, "{$sAuditCode}");

        // Report Details
	$objPdf->SetFont('Arial', '', 7);
        
        if($sSamplePickFor != "")
        {
            if($sSamplePickFor == "CHEM")
                $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 19, 75, 4);
            else if($sSamplePickFor == "FITTING")
                $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 90.5, 75, 4);
            else if($sSamplePickFor == "NONE")
                $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 162, 75, 4);            
        }
        
        $sPortsList     = getList("tbl_shipping_ports", "id", "port_name", "booking_form='Y' AND id IN ($sPorts)");
        
        $iTop   = 97;
        $Count  = 1;
        
        foreach($sPortsList as $key => $PortName)
        {
            //if ($sPortPics[$key] != "" && @file_exists($sBaseDir.SHIPPING_PORTS_DIR.$sPortPics[$key]))
            //    $objPdf->Image(($sBaseDir.SHIPPING_PORTS_DIR.$sPortPics[$key]), 19, $iTop, 4);
            
            $objPdf->Text(15, $iTop, $Count."-  ".$PortName);
            //$objPdf->Text(35, $iTop, "(".getDbValue("GROUP_CONCAT(destination SEPARATOR ', ')", "tbl_destinations", "port_id='$key'").")");
            
            $Count++;
            $iTop += 6.45;
        }
        
        $objPdf->SetXY(13, 124);
            $objPdf->MultiCell(150, 4, $sRemarks, 0, "L");
            
        ///////////////////////////////////////////Page #3 ////////////////////////////////////////////////////////////////
        $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/booking-form.pdf");
	$iTemplateId = $objPdf->importPage(3, '/MediaBox');

        $BookingAttachments = getList("tbl_booking_files", "id", "file", "booking_id = '$Id' AND file LIKE '%.jpg'");
        $PageNo = 3;
        
        if(count($BookingAttachments) > 0)
        {
            foreach ($BookingAttachments as $iAttachment => $sAttachment)
            {
                    @list($sYear, $sMonth, $sDay) = @explode("-", $sReqInspectionDate);
   
                    if ($sAttachment != "" && @file_exists($sBaseDir.BOOKINGS_DIR.$sYear.'/'.$sMonth.'/'.$sDay.'/'.$sAttachment) && filesize($sBaseDir.BOOKINGS_DIR.$sYear.'/'.$sMonth.'/'.$sDay.'/'.$sAttachment) > 0)
                    {
                        $objPdf->addPage("P", "A4");
                        $objPdf->useTemplate($iTemplateId, 0, 0);

                        // QR Code
                        QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

                        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 176.5, 11, 22);

                        $objPdf->SetFont('Arial', '', 9);
                        $objPdf->Text(10, 7, "Page #{$PageNo}");

                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->SetTextColor(50, 50, 50);
                        $objPdf->Text(183, 33.5, "{$sAuditCode}");

                        // Report Details
                        $objPdf->SetFont('Arial', '', 7);

                        try{
                            
                            $FilePath   = $sBaseDir.BOOKINGS_DIR.$sYear.'/'.$sMonth.'/'.$sDay.'/'.$sAttachment;  
                            $exts       = explode('.', strtolower($sAttachment));
                            $extension  = end($exts);
                    
                            list($width, $height, $type, $attr) = getimagesize($FilePath);

                            if($width>= 800 || $height >= 800)
                            {
                                if ($width > $height && $width >= 800)
                                {
                                    $iWidth  = 173;
                                    $iHeight = @ceil(($height/$width)*180);
                                }
                                else if ($width < $height && $height >= 800)
                                {
                                    $iHeight  = 180;
                                    $iWidth = @ceil(($width/$height)*173);
                                }
                                else
                                {
                                    $iWidth  = 173;
                                    $iHeight = 180;
                                }
                            }
                            else
                            {
                                $iWidth  = $width/4.62;
                                $iHeight = $height/4.62;
                            }

                            $objPdf->Image($FilePath, 17, 52, $iWidth, $iHeight);
                           
                            $PageNo ++;                                       
                        }
                        catch (Exception $e) {
                            //nothing to do
                        }
                    }
                }                        
        }        
        ///////////////////////////////////////////Page #4 ////////////////////////////////////////////////////////////////
        $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/booking-form.pdf");
	$iTemplateId = $objPdf->importPage(3, '/MediaBox');

        $BookingAttachments = getList("tbl_booking_files", "id", "file", "booking_id = '$Id' AND file NOT LIKE '%.jpg'");
        
        if(count($BookingAttachments) > 0)
        {
            $objPdf->addPage("P", "A4");
            $objPdf->useTemplate($iTemplateId, 0, 0);

            // QR Code
            QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

            $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 176.5, 11, 22);

            $objPdf->SetFont('Arial', '', 9);
            $objPdf->Text(10, 7, "Page #{$PageNo}");

            $objPdf->SetFont('Arial', '', 6);
            $objPdf->SetTextColor(50, 50, 50);
            $objPdf->Text(183, 33.5, "{$sAuditCode}");
                    
            $objPdf->SetFont('Arial', '', 7);
            $objPdf->SetTextColor(6, 82, 195);
            
            $iTop = 6;
            $Count = 1; 
            
            foreach ($BookingAttachments as $iAttachment => $sAttachment)
            {
                @list($sYear, $sMonth, $sDay) = @explode("-", $sReqInspectionDate);
                
                if ($sAttachment != "" && @file_exists($sBaseDir.BOOKINGS_DIR.$sYear.'/'.$sMonth.'/'.$sDay.'/'.$sAttachment))
                {
                    // Report Details
                    $objPdf->SetXY(15, $iTop);
                    $objPdf->Write(100, $Count." - ".$sAttachment, SITE_URL.BOOKINGS_DIR.$sYear.'/'.$sMonth.'/'.$sDay.'/'.$sAttachment);
                    
                    $Count ++;
                    $iTop += 5;
                }
            }
            
            $PageNo++;
        }        
        ///////////////////////////////////////////Page #5 ////////////////////////////////////////////////////////////////
        $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/booking-form.pdf");
	$iTemplateId = $objPdf->importPage(4, '/MediaBox');

        $objPdf->addPage("P", "A4");
        $objPdf->useTemplate($iTemplateId, 0, 0);

        // QR Code
        QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 175, 11, 22);

        $objPdf->SetFont('Arial', '', 9);
        $objPdf->Text(10, 7, "Page #{$PageNo}");

        $objPdf->SetFont('Arial', '', 6);
        $objPdf->SetTextColor(50, 50, 50);
        $objPdf->Text(181, 33.5, "{$sAuditCode}");
                    
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	@unlink($sBaseDir.TEMP_DIR."{$sAuditCode}.png");
	$sPdfFile = ($sBaseDir.TEMP_DIR."{$sAuditCode}-Booking-Form.pdf");

	if (count($Recipients) > 0)
		$objPdf->Output($sPdfFile, 'F');

	else
		$objPdf->Output(@basename($sPdfFile), 'D');
?>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>