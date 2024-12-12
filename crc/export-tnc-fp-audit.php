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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  mtahirshahzad@hotmail.com                                                   **
	**      Phone :  +92 344 40 43675                                                            **
	**      URL   :  http://www.mtshahzad.com                                                    **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

        //ini_set('display_errors', 1);
        //error_reporting(E_ALL);
    
        //if ($AuditCode == "")
        {
            @require_once("../requires/session.php");

            $objDbGlobal = new Database( );
            $objDb       = new Database( );
            $objDb2      = new Database( );

            $Id = IO::intValue('Id');
        }
        
   	@require_once($sBaseDir."requires/tcpdf/tcpdf.php");
	@require_once($sBaseDir."requires/fpdi2/fpdi.php");
	@require_once($sBaseDir."requires/qrcode/qrlib.php");
        
        $style1 = array('L' => array('width' => 100, 'color' => array(255, 0, 0)));

        $Id = IO::intValue('Id');

	$sSQL = "SELECT *
	         FROM tbl_crc_audits
	         WHERE id='$Id'";
        
	$objDb->query($sSQL);

	$sAuditDate     = $objDb->getField(0, "audit_date"); 
        $Vendor         = $objDb->getField(0, "vendor_id");
        $Unit           = $objDb->getField(0, "unit_id");
        $PrevAuditId    = $objDb->getField(0, "prev_audit_id");
        $Auditor        = $objDb->getField(0, "auditor_id");
        $PSectionId     = $objDb->getField(0, "section_id");
        $Brand          = $objDb->getField(0, "brand_id");
        $Points         = $objDb->getField(0, "points");
        $Language       = $objDb->getField(0, "language");
        $Department     = $objDb->getField(0, "department");
        $Shift1         = $objDb->getField(0, "shift1");
        $Shift2         = $objDb->getField(0, "shift2");
        $Shift3         = $objDb->getField(0, "shift3");
        $PermMen        = $objDb->getField(0, "perm_male");
        $PermWomen      = $objDb->getField(0, "perm_female"); 
        $PermYoung      = $objDb->getField(0, "perm_young"); 
        $TempMen        = $objDb->getField(0, "temp_male"); 
        $TempWomen      = $objDb->getField(0, "temp_female"); 
        $TempYoung      = $objDb->getField(0, "temp_young"); 
        $MgtRep         = $objDb->getField(0, "mgt_representative");
        $AuditTypeId    = $objDb->getField(0, "audit_type_id");
        $EndDate        = $objDb->getField(0, "audit_end_date");
        $MgtRepEmail    = $objDb->getField(0, "mgt_rep_email");
        $Observations   = $objDb->getField(0, "observations");
        $SameCompound   = $objDb->getField(0, "same_compound");
        $PeakSeason     = $objDb->getField(0, "peak_season");
        
        if($PrevAuditId > 0)
        {
            $sPrevAuditId   = $PrevAuditId;
            $sPrevAuditDate = getDbValue("audit_date", "tbl_crc_audits", "id='$PrevAuditId'");
            $iPrevAuditor   = getDbValue("auditor_id", "tbl_crc_audits", "id='$PrevAuditId'");
            $iPrevPoints    = getDbValue("points", "tbl_crc_audits", "id='$PrevAuditId'");
        }
        else
        {    
            $sPrevAuditId   = getDbValue("id", "tbl_crc_audits", "audit_date < '$sAuditDate' AND vendor_id = '$Vendor' AND unit_id = '$Unit' AND total_score != '0'", "id DESC"); 
            $sPrevAuditDate = getDbValue("audit_date", "tbl_crc_audits", "audit_date < '$sAuditDate' AND vendor_id = '$Vendor' AND unit_id = '$Unit' AND total_score != '0'", "id DESC"); 
            $iPrevAuditor   = getDbValue("auditor_id", "tbl_crc_audits", "audit_date < '$sAuditDate' AND vendor_id = '$Vendor' AND unit_id = '$Unit' AND total_score != '0'", "id DESC"); 
            $iPrevPoints    = getDbValue("points", "tbl_crc_audits", "audit_date < '$sAuditDate' AND vendor_id = '$Vendor' AND unit_id = '$Unit' AND total_score != '0'", "id DESC"); 
        }
        
        $sSQL = "SELECT vendor, address, production_steps, product_range, date_of_foundation
                FROM tbl_vendors
                WHERE id='$Vendor'";
        
	$objDb->query($sSQL);

        $sVendor                = $objDb->getField(0, "vendor"); 
	$sVendorAddress         = $objDb->getField(0, "address"); 
        $sVendorProductRange    = $objDb->getField(0, "product_range");
        $sVendorProductCap      = $objDb->getField(0, "production_steps");
        $sVendorFoundationDate  = $objDb->getField(0, "date_of_foundation");

        @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
        $sTncDir = ($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
        
        $sBrandPicture = getDbValue("logo_jpg", "tbl_brands", "id='$Brand'");     

        if ($sBrandPicture != "" && @file_exists($sBaseDir.'images/brands/jpg/'.$sBrandPicture))
                $sBrandLogo = $sBaseDir.'images/brands/jpg/'.$sBrandPicture;
        else
                $sBrandLogo = $sBaseDir."images/triple-tree.jpg";
        ////////////////////////////////////////////////////////////////Page 1////////////////////////////////////////////////////

        $fontname = TCPDF_FONTS::addTTFfont($sBaseDir."requires/tcpdf/fonts/arial.ttf", 'TrueTypeUnicode', '', 96);
        $fontbold = TCPDF_FONTS::addTTFfont($sBaseDir."requires/tcpdf/fonts/ariblk.ttf", 'TrueTypeUnicode', '', 96);
        
	$objPdf = new FPDI( );
	
	$objPdf->setPrintHeader(false);
	$objPdf->setPrintFooter(false);

	$objPdf->setSourceFile($sBaseDir."templates/tnc-fp-audit.pdf");
        $iTemplate = $objPdf->importPage(1);

	$iSize = $objPdf->getTemplateSize($iTemplate);

	$objPdf->AddPage( );
	$objPdf ->useTemplate($iTemplate, null, null, 0, 0, true);

	$objPdf->SetFont($fontname);
	$objPdf->SetTextColor(0, 0, 0);

	// QR Code
        $objPdf->Image($sBrandLogo, 15, 15, 20);
	QRcode::png("http://portal.3-tree.com/kpis/{$Id}/", ($sBaseDir.TEMP_DIR."{$Id}.png"));
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$Id}.png"), 177, 13.2, 21);
  	
	$objPdf->SetFont($fontname, '', 11);
	$objPdf->SetTextColor(128, 128, 128);
        
        $objPdf->SetXY(175, 260);
        $objPdf->Write(0, "Page #: 1");
        
        $objPdf->SetTextColor(50, 50, 50);
        $objPdf->SetFont($fontname, '', 6);

        $sAuditCode = "C".str_pad($Id, 5, 0, STR_PAD_LEFT);
        
        $objPdf->SetXY(178, 33);
        $objPdf->MultiCell(30, 3.5, "Audit #: {$sAuditCode}", 0, "L", false);


	// Report Details
	$objPdf->SetFont($fontname, '', 7);
        
        $objPdf->SetXY(38, 48.5);
        $objPdf->Write(0, $sVendor);
	
        $objPdf->SetXY(121, 48.5);
        $objPdf->Write(0, getDbValue("vendor", "tbl_vendors", "id='$Unit'"));
	
        $objPdf->SetXY(40, 53.5);
        $objPdf->Write(0, preg_replace( "/\r|\n/", " ", $sVendorAddress ));

        $objPdf->SetXY(65, 58.5);
        $objPdf->Write(0, $MgtRep." (".$MgtRepEmail.")");

        $objPdf->SetXY(32, 63.7);
        $objPdf->Write(0, getDbValue("department", "tbl_crc_departments", "id='$Department'"));
        
        $objPdf->SetXY(116, 63.7);
        $objPdf->Write(0, getDbValue("language", "tbl_languages", "id='$Language'"));
        
        $objPdf->SetXY(30, 69);
        $objPdf->Write(0, getDbValue("type", "tbl_crc_audit_types", "id='$AuditTypeId'"));
        
        $objPdf->SetXY(108, 69.3);
        $objPdf->Write(0, $sAuditDate);
        
        $objPdf->SetXY(150, 69);
        $objPdf->Write(0, getDbValue("name", "tbl_users", "id='$Auditor'"));

        
        $objPdf->SetXY(38, 74.2);
        $objPdf->Write(0, "C".str_pad($sPrevAuditId, 5, 0, STR_PAD_LEFT));
        
        $objPdf->SetXY(108, 74.2);
        $objPdf->Write(0, $sPrevAuditDate);

        $objPdf->SetXY(150, 74.2);
        $objPdf->Write(0, getDbValue("name", "tbl_users", "id='$iPrevAuditor'"));
        
        $objPdf->SetFont($fontname, '', 7);
        
        $objPdf->SetXY(12, 105);
        $objPdf->MultiCell(180, 3.5, $Observations, 0, "L", false);
	
        ///////////////////////////////////////////////////////page # 2/////////////////////////////////////////////////////////////  	  
 
        $objPdf->setSourceFile($sBaseDir."templates/tnc-fp-audit.pdf");
        $iTemplate    = $objPdf->importPage(2);
	$iSize        = $objPdf->getTemplateSize($iTemplate);
        $SubSectionNo = 1;
        $PageNo       = 2;
        
        $sFailedPoints = getList("tbl_crc_audit_details", "point_id", "point_id", "audit_id='$Id' AND score='0'");
        $sSectionsList = getList("tbl_tnc_sections s, tbl_tnc_points p", "s.id", "s.section", "s.id = p.section_id AND s.parent_id='$PSectionId' AND p.id IN (". implode(',', $sFailedPoints).")");

        
        foreach($sSectionsList as $iSection => $sSection)
        {
            $objPdf->AddPage( );
            $objPdf ->useTemplate($iTemplate, null, null, 0, 0, true);

            $objPdf->SetFont($fontname);
            $objPdf->SetTextColor(0, 0, 0);

            // QR Code
            $objPdf->Image($sBrandLogo, 15, 15, 20);
            QRcode::png("http://portal.3-tree.com/kpis/{$Id}/", ($sBaseDir.TEMP_DIR."{$Id}.png"));
            $objPdf->Image(($sBaseDir.TEMP_DIR."{$Id}.png"), 177, 13.2, 21);
                    
            $objPdf->SetFont($fontname, '', 11);
            $objPdf->SetTextColor(128, 128, 128);
                    
            $objPdf->SetXY(175, 260);
            $objPdf->Write(0, "Page #: ".($PageNo++)."");

            $objPdf->SetTextColor(50, 50, 50);
            $objPdf->SetFont($fontname, '', 6);

            $sAuditCode = "C".str_pad($Id, 5, 0, STR_PAD_LEFT);

            $objPdf->SetXY(178, 33);
            $objPdf->MultiCell(30, 3.5, "Audit #: {$sAuditCode}", 0, "L", false);
                    
            $objPdf->SetFont($fontbold, 'B', 8);
            
            $objPdf->SetXY(18, 50.5);
            $objPdf->MultiCell(80, 3, $sSection, 0, "L", false);
            
            
            $iTop = 60;
            $sOldCategory = "";            
            $sCategoriesList  = getList("tbl_tnc_categories c, tbl_tnc_points p", "c.id", "c.category", "c.id = p.category_id AND c.section_id='$iSection' AND p.id IN (".implode(',', $sFailedPoints).")", "c.position");            
            foreach ($sCategoriesList as $iCategory => $sCategory)
            {
                
                if($sOldCategory != $sCategory)
                {
                    $objPdf->SetFont($fontbold, 'B', 6);
                    
                    $objPdf->SetXY(10.5, $iTop);
                    $objPdf->Write(0, "3.".$SubSectionNo);
                            
                    $objPdf->SetXY(18, $iTop);
                    $objPdf->MultiCell(80, 3, $sCategory, 0, "L", false);
        
                    $sOldCategory = $sCategory;                    
                    $SubSectionNo ++;
                    
                    $iTop += 5;
                }
                
                // Report Data                    
                $objPdf->SetFont($fontname, '', 6);
                    
                $sSQL = "SELECT tp.id, cad.score, cad.remarks, tp.point, tp.point_no
                     FROM tbl_crc_audit_details cad, tbl_tnc_points tp
                     WHERE cad.point_id=tp.id AND cad.audit_id='$Id' AND tp.category_id='$iCategory' AND tp.id IN (". implode(',', $sFailedPoints).")
                     ORDER BY tp.position";
                $objDb->query($sSQL);

                $iCount = $objDb->getCount( );
                
                
                for ($i = 0; $i < $iCount; $i ++)
                {
                        $iPoint   = $objDb->getField($i, 'id');
                        $fPointNo = $objDb->getField($i, 'point_no');
                        $fPointNo = ($fPointNo == ""?$iPoint:$fPointNo);
                        $sPoint   = $objDb->getField($i, 'point');
                        $iScore   = $objDb->getField($i, 'score');
                        $sRemarks = $objDb->getField($i, 'remarks');
                    
                        $objPdf->SetXY(10, $iTop);
                        $objPdf->Write(0, $fPointNo);
                    
                        $objPdf->SetXY(19, $iTop);
                        $objPdf->MultiCell(79, 3, $sPoint, 0, "L", false);
                        
                        $height1 = $objPdf->getLastH();
                        
                        $objPdf->SetXY(100, $iTop);
                        $objPdf->Write(0, (($iScore == '0')?'No':($iScore == '1'?'Yes':'N/A')));
                        
                        $objPdf->SetXY(107, $iTop);
                        $objPdf->MultiCell(95, 3, $sRemarks, 0, "L", false);
                        
                        $height2 = $objPdf->getLastH();
                        
                        $MaxHeight = max($height1, $height2);
                        
                        $iTop += ($MaxHeight+2);
                        
                        if($iTop > 240)
                        {
                            $iTop = 60;
                            
                            $objPdf->AddPage( );
                            $objPdf ->useTemplate($iTemplate, null, null, 0, 0, true);

                            $objPdf->SetFont($fontname);
                            $objPdf->SetTextColor(0, 0, 0);

                            // QR Code
                            $objPdf->Image($sBrandLogo, 15, 15, 20);
                            QRcode::png("http://portal.3-tree.com/kpis/{$Id}/", ($sBaseDir.TEMP_DIR."{$Id}.png"));
                            $objPdf->Image(($sBaseDir.TEMP_DIR."{$Id}.png"), 177, 13.2, 21);

                            $objPdf->SetFont($fontname, '', 11);
                            $objPdf->SetTextColor(128, 128, 128);

                            $objPdf->SetXY(175, 260);
                            $objPdf->Write(0, "Page #: {$PageNo}");

                            $objPdf->SetTextColor(50, 50, 50);
                            $objPdf->SetFont($fontname, '', 6);

                            $sAuditCode = "C".str_pad($Id, 5, 0, STR_PAD_LEFT);
        
                            $objPdf->SetXY(178, 33);
                            $objPdf->MultiCell(30, 3.5, "Audit #: {$sAuditCode}", 0, "L", false);


                            // Report Data                    
                            $objPdf->SetFont($fontbold, 'B', 8);
                            
                            if($sOldCategory == $sCategory)
                                $SubSectionNo --;
                            
                            $objPdf->SetXY(10.5, 51.5);
                            $objPdf->Write(0, "3.".$SubSectionNo);
                    
                            $objPdf->SetXY(19, 51.5);
                            $objPdf->Write(0, $sSection);

                            $objPdf->SetFont($fontname, '', 6);                            
                            $SubSectionNo++;                            
                            $PageNo ++;
                        }
                }
            }
        }
        
        ///////////////////////////////////////////////////////////////////////////////////////////////
        @unlink($sBaseDir.TEMP_DIR."{$Id}.png");

        $sPdfFile = ($sBaseDir.TEMP_DIR."{$sAuditCode}-Non Complianced.pdf");

        $objPdf->Output(@basename($sPdfFile), 'D');


        //if ($AuditCode == "")
        {
            $objDb->close( );
            $objDb2->close( );
            $objDbGlobal->close( );

            @ob_end_flush( );
        }

?>