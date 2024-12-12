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
    
        if ($AuditCode == "")
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
        ////////////////////////////////////////////////////////////////Page 1////////////////////////////////////////////////////

        $fontname = TCPDF_FONTS::addTTFfont($sBaseDir."requires/tcpdf/fonts/arial.ttf", 'TrueTypeUnicode', '', 96);
        $fontbold = TCPDF_FONTS::addTTFfont($sBaseDir."requires/tcpdf/fonts/ariblk.ttf", 'TrueTypeUnicode', '', 96);
        
	$objPdf = new FPDI( );
	
	$objPdf->setPrintHeader(false);
	$objPdf->setPrintFooter(false);

	$objPdf->setSourceFile($sBaseDir."templates/tnc-audit.pdf");
        $iTemplate = $objPdf->importPage(1);

	$iSize = $objPdf->getTemplateSize($iTemplate);

	$objPdf->AddPage( );
	$objPdf ->useTemplate($iTemplate, null, null, 0, 0, true);

	$objPdf->SetFont($fontname);
	$objPdf->SetTextColor(0, 0, 0);

	// QR Code
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
        $objPdf->Write(0, $sVendorAddress);

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
        
        $sSQL = "SELECT *
	         FROM tbl_crc_attendance
	         WHERE audit_id='$Id'";
        
	$objDb->query($sSQL);

	$sOpeningDate   = $objDb->getField(0, "opening_date"); 
        $sOpeningTime   = $objDb->getField(0, "opening_time");
        $sClosingDate   = $objDb->getField(0, "closing_date");
        $sClosingTime   = $objDb->getField(0, "closing_time");

        $objPdf->SetXY(18, 95.5);
        $objPdf->Write(0, $sOpeningDate);
        
        $objPdf->SetXY(66, 95.5);
        $objPdf->Write(0, $sOpeningTime);
        
        $objPdf->SetXY(116, 95.5);
        $objPdf->Write(0, $sClosingDate);
        
        $objPdf->SetXY(163, 95.5);
        $objPdf->Write(0, $sClosingTime);
        
        $objPdf->SetFont($fontname, '', 6);
        
        $sSQL = "SELECT *
	         FROM tbl_crc_attendance_details
	         WHERE audit_id='$Id' Order By meeting_type DESC";
        
	$objDb->query($sSQL);
        $iCount = $objDb->getCount();
        
        $iTop            = 106;
        $iFlag           = 0;
        
        for($i=0; $i < $iCount; $i++)
        {
            if($i <= 60)
            {
                $sAttendee      = $objDb->getField($i, "attendee"); 
                $sDesignation   = $objDb->getField($i, "designation"); 
                $sMeetingType   = $objDb->getField($i, "meeting_type");

                if($sMeetingType == 'O')
                {
                    $objPdf->SetXY(12, $iTop);
                    $objPdf->Write(0, $sAttendee);

                    $objPdf->SetXY(41, $iTop);
                    $objPdf->Write(0, $sDesignation);

                }else
                {
                    if($iFlag == 0)
                    {
                        $iFlag ++;
                        $iTop = 106;
                    }
                    $objPdf->SetXY(109, $iTop);
                    $objPdf->Write(0, $sAttendee);

                    $objPdf->SetXY(138, $iTop);
                    $objPdf->Write(0, $sDesignation);
                }

                $iTop += 5.15;
            }
        }
	
        ///////////////////////////////////////////////////////page # 2/////////////////////////////////////////////////////////////  	  

        $objPdf->setSourceFile($sBaseDir."templates/tnc-audit.pdf");
        $iTemplate = $objPdf->importPage(2);

	$iSize = $objPdf->getTemplateSize($iTemplate);

	$objPdf->AddPage( );
	$objPdf ->useTemplate($iTemplate, null, null, 0, 0, true);

	$objPdf->SetFont($fontname);
	$objPdf->SetTextColor(0, 0, 0);

	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$Id}/", ($sBaseDir.TEMP_DIR."{$Id}.png"));
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$Id}.png"), 177, 13.2, 21);
  	
	$objPdf->SetFont($fontname, '', 11);
	$objPdf->SetTextColor(128, 128, 128);
        
      
        $objPdf->SetXY(175, 257);
        $objPdf->Write(0, "Page #: 2");
        
        $objPdf->SetTextColor(50, 50, 50);
        $objPdf->SetFont($fontname, '', 6);

        $sAuditCode = "C".str_pad($Id, 5, 0, STR_PAD_LEFT);
        
        $objPdf->SetXY(178, 33);
        $objPdf->MultiCell(30, 3.5, "Audit #: {$sAuditCode}", 0, "L", false);

	// Report Details
	$objPdf->SetFont($fontname, '', 7);
        
        $objPdf->SetXY(96, 64);
        $objPdf->Write(0, $sVendorFoundationDate);
        
        $objPdf->SetXY(96, 75.5);
        $objPdf->Write(0, $sVendorAddress);
        
        $objPdf->SetXY(96, 86);
        $objPdf->MultiCell(105, 3.5, preg_replace( "/\r|\n/", "", $SameCompound),0, "L", false);
         
        $objPdf->SetXY(96, 96.5);
        $objPdf->Write(0, $PeakSeason);
        
        $objPdf->SetXY(96, 106);
        $objPdf->Write(0, "Shift 1 : ".($Shift1 == ''?'N/A':$Shift1).", Shift 2 : ".($Shift2 == ''?'N/A':$Shift2).", Shift 3 : ".($Shift3 == ''?'N/A':$Shift3));
        
        $objPdf->SetXY(120, 116);
        $objPdf->Write(0, $PermMen);
        
        $objPdf->SetXY(120, 119.5);
        $objPdf->Write(0, $PermWomen);
        
        $objPdf->SetXY(120, 123);
        $objPdf->Write(0, $PermYoung);
    
        $objPdf->SetXY(120, 126.8);
        $objPdf->Write(0, $TempMen);
    
        $objPdf->SetXY(120, 130.5);
        $objPdf->Write(0, $TempWomen);
    
        $objPdf->SetXY(120, 134);
        $objPdf->Write(0, $TempYoung);
       
        $objPdf->SetXY(96, 138.4);
        $objPdf->MultiCell(105, 3.5, preg_replace( "/\r|\n/", "", $sVendorProductRange),0, "L", false);
        
        $objPdf->SetXY(96, 143);
        $objPdf->MultiCell(105, 3.5, preg_replace( "/\r|\n/", "", $sVendorProductCap),0, "L", false);
    
        $sSQL = "SELECT * FROM tbl_crc_audit_supply_chain Where audit_id = '$Id'";
	$objDb->query($sSQL);

        $cIsAnotherPSite        = $objDb->getField(0, 'is_another_production_site');
        $sProductionTiers       = $objDb->getField(0, 'production_tier');
        $sProductionSiteTypes   = $objDb->getField(0, 'production_site_type');
        $sProductionSiteNames   = $objDb->getField(0, 'production_site_name');
        $sProductionSitesAddress= $objDb->getField(0, 'production_site_address');
        $cIsAnotherCompany      = $objDb->getField(0, 'is_another_company');
        $sCompanyTiers          = $objDb->getField(0, 'company_tier');
        $sCompanyTypes          = $objDb->getField(0, 'company_type');
        $sCompanyNames          = $objDb->getField(0, 'company_name');
        $sCompanyAddresses      = $objDb->getField(0, 'company_address');
        $iNoOfBuildings         = $objDb->getField(0, 'no_of_buildings');
        $sBuildingPurposes       = $objDb->getField(0, 'building_purpose'); 
        $sBuildingFloors        = $objDb->getField(0, 'building_floors');
        $sFireCertificates      = $objDb->getField(0, 'fire_certificate');
        $sBuildingApprovals     = $objDb->getField(0, 'building_approvals'); 
        $iTotalFarms            = $objDb->getField(0, 'total_farms');
        $iCustomerTurnOver      = $objDb->getField(0, 'customers_turn_over');
        $sLastOrderDate         = $objDb->getField(0, 'last_order_date');
        $sOtherFactoryInfo      = $objDb->getField(0, 'other_factory_info');
        
        if($iNoOfBuildings > 0 && $iNoOfBuildings <=5)
        {
            $iNoOfBuildings = 5;
        }
        else if($iNoOfBuildings > 5 && $iNoOfBuildings <=10)
        {
            $iNoOfBuildings = 10;
        }
        else if($iNoOfBuildings > 10 && $iNoOfBuildings <=15)
        {
            $iNoOfBuildings = 15;
        }
        else if($iNoOfBuildings > 15 && $iNoOfBuildings <=20)
        {
            $iNoOfBuildings = 20;
        }

        
        if($cIsAnotherPSite == 'Y')
        {
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 166.5, 175, 4);
            
            $iProductionTiers       = explode("|-|", $sProductionTiers);
            $iProductionSiteTypes   = explode("|-|", $sProductionSiteTypes);
            $iProductionSiteNames   = explode("|-|", $sProductionSiteNames);
            $iProductionSitesAddress= explode("|-|", $sProductionSitesAddress);

            $iTop = 187.5;
            
            for($j=0; $j<4; $j++)
            {
                $sProductionTier       = $iProductionTiers[$j];
                $sProductionSiteType   = $iProductionSiteTypes[$j];
                $sProductionSiteName   = $iProductionSiteNames[$j];
                $sProductionSiteAddress= $iProductionSitesAddress[$j];
                
                $objPdf->SetXY(12, $iTop);
                $objPdf->Write(0, $sProductionTier);

                $objPdf->SetXY(28, $iTop);
                $objPdf->Write(0, $sProductionSiteType);
                
                $objPdf->SetXY(65, $iTop);
                $objPdf->Write(0, $sProductionSiteName);
                
                $objPdf->SetXY(110, $iTop);
                $objPdf->Write(0, $sProductionSiteAddress);
                
                $iTop += 4.8;
            }
            
        }else
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 185, 175, 4);
        
        
        if($cIsAnotherCompany == 'Y')
        {
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 167, 225, 4);
            
            $iCompanyTiers      = explode("|-|", $sCompanyTiers);
            $iCompanyTypes      = explode("|-|", $sCompanyTypes);
            $iCompanyNames      = explode("|-|", $sCompanyNames);
            $iCompanyAddresses  = explode("|-|", $sCompanyAddresses);

            $iTop = 237.5;
            
            for($j=0; $j<4; $j++)
            {
                $sCompanyTier   = $iCompanyTiers[$j];
                $sCompanyType   = $iCompanyTypes[$j];
                $sCompanyName   = $iCompanyNames[$j];
                $sCompanyAddress= $iCompanyAddresses[$j];
                
                $objPdf->SetXY(12, $iTop);
                $objPdf->Write(0, $sCompanyTier);

                $objPdf->SetXY(28, $iTop);
                $objPdf->Write(0, $sCompanyType);
                
                $objPdf->SetXY(65, $iTop);
                $objPdf->Write(0, $sCompanyName);
                
                $objPdf->SetXY(110, $iTop);
                $objPdf->Write(0, $sCompanyAddress);
                
                $iTop += 4.8;
            }
            
        }else
            $objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 185, 225, 4);
        
        ///////////////////////////////////////////////////////page # 3/////////////////////////////////////////////////////////////  	  

        $objPdf->setSourceFile($sBaseDir."templates/tnc-audit.pdf");
        $iTemplate = $objPdf->importPage(3);

	$iSize = $objPdf->getTemplateSize($iTemplate);

	$objPdf->AddPage( );
	$objPdf ->useTemplate($iTemplate, null, null, 0, 0, true);

	$objPdf->SetFont($fontname);
	$objPdf->SetTextColor(0, 0, 0);

	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$Id}/", ($sBaseDir.TEMP_DIR."{$Id}.png"));
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$Id}.png"), 177, 13.2, 21);
  	
	$objPdf->SetFont($fontname, '', 11);
	$objPdf->SetTextColor(128, 128, 128);
        
      
        $objPdf->SetXY(175, 260);
        $objPdf->Write(0, "Page #: 3");
        
        $objPdf->SetTextColor(50, 50, 50);
        $objPdf->SetFont($fontname, '', 6);

        $sAuditCode = "C".str_pad($Id, 5, 0, STR_PAD_LEFT);
        
        $objPdf->SetXY(178, 33);
        $objPdf->MultiCell(30, 3.5, "Audit #: {$sAuditCode}", 0, "L", false);


	// Report Details
	$objPdf->SetFont($fontname, '', 7);
        
        if($iNoOfBuildings > 0)
        {
            $objPdf->SetXY(182, 43);
            $objPdf->Write(0, ($iNoOfBuildings -5)." to ".($iNoOfBuildings));
            
            $iBuildingPurposes   = explode("|-|", $sBuildingPurposes);
            $iBuildingFloors     = explode("|-|", $sBuildingFloors);
            $iFireCertificates   = explode("|-|", $sFireCertificates);
            $iBuildingApprovals  = explode("|-|", $sBuildingApprovals);
            
            $iTop = 62;
            
            for($j=0; $j < $iNoOfBuildings; $j++)
            {
                $sBuildingPurpose  = @$iBuildingPurposes[$j];
                $sBuildingFloor    = @$iBuildingFloors[$j];
                $sFireCertificate  = @$iFireCertificates[$j];
                $sBuildingApproval = @$iBuildingApprovals[$j];
                
                $objPdf->SetXY(21, $iTop);
                $objPdf->Write(0, $sBuildingPurpose);
            
                $objPdf->SetXY(95, $iTop);
                $objPdf->Write(0, $sBuildingFloor);
                
                $objPdf->SetXY(110, $iTop);
                $objPdf->Write(0, $sFireCertificate);
                
                $objPdf->SetXY(156, $iTop);
                $objPdf->Write(0, $sBuildingApproval);
                
                $iTop += 5.75;
            }            
        }
                
        $objPdf->SetXY(60, 186);
        $objPdf->Write(0, $iCustomerTurnOver);
        
        $objPdf->SetXY(138, 198);
        $objPdf->Write(0, $iTotalFarms);
        
        $objPdf->SetXY(100, 208);
        $objPdf->Write(0, $sLastOrderDate);
        
        $objPdf->SetXY(70, 219.5);
        $objPdf->Write(0, $sOtherFactoryInfo);
        
        ///////////////////////////////////////////////////////page # 4/////////////////////////////////////////////////////////////  	  

        $objPdf->setSourceFile($sBaseDir."templates/tnc-audit.pdf");
        $iTemplate = $objPdf->importPage(4);

	$iSize = $objPdf->getTemplateSize($iTemplate);

	$objPdf->AddPage( );
	$objPdf ->useTemplate($iTemplate, null, null, 0, 0, true);

	$objPdf->SetFont($fontname);
	$objPdf->SetTextColor(0, 0, 0);

	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$Id}/", ($sBaseDir.TEMP_DIR."{$Id}.png"));
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$Id}.png"), 177, 12, 21);
  	
	$objPdf->SetFont($fontname, '', 11);
	$objPdf->SetTextColor(128, 128, 128);
        
      
        $objPdf->SetXY(175, 260);
        $objPdf->Write(0, "Page #: 4");
        
        $objPdf->SetTextColor(50, 50, 50);
        $objPdf->SetFont($fontname, '', 6);

        $sAuditCode = "C".str_pad($Id, 5, 0, STR_PAD_LEFT);
        
        $objPdf->SetXY(178, 31.5);
        $objPdf->MultiCell(30, 3.5, "Audit #: {$sAuditCode}", 0, "L", false);

        // Report Details
	$objPdf->SetFont($fontname, '', 7);
        
        $sCriticalSections      = array();
        $sZeroTolerantCategories= array();
        $sCrSectionsArray       = array(1,2,4,5,6,9,10,11,12,13,14);
        
        //previous audit details
        if($PrevAuditId > 0)
        {
            $sPrevCriticalSections    = array();
            
            //Previous critical categories
            $sSQL = "SELECT tp.section_id, COUNT(DISTINCT tp.point_no) as _Points
                        FROM tbl_crc_audit_details cad, tbl_tnc_points tp
                        WHERE cad.point_id = tp.id AND cad.audit_id='$PrevAuditId' AND cad.score = '1' AND tp.id IN ($iPrevPoints) AND tp.section_id IN (".  implode(",", $sCrSectionsArray).") AND tp.nature = 'C'
                        Group By tp.section_id
                        Order By tp.position, tp.section_id";

            $objDb->query($sSQL);
            $iCount = $objDb->getCount();

            for($i=0; $i<$iCount; $i ++)
            {
                $iSection  = $objDb->getField($i, "section_id"); 
                $iPoints    = $objDb->getField($i, "_Points"); 

                $sPrevCriticalSections[$iSection] = $iPoints;
            }

        }
        
        //critical categories
        $sSQL = "SELECT tp.section_id, COUNT(DISTINCT tp.point_no) as _Points
                    FROM tbl_crc_audit_details cad, tbl_tnc_points tp
                    WHERE cad.point_id = tp.id AND cad.audit_id='$Id' AND cad.score = '1' AND tp.id IN ($Points) AND tp.section_id IN (".  implode(",", $sCrSectionsArray).") AND tp.nature = 'C'
                    Group By tp.section_id
                    Order By tp.position, tp.section_id";
        
        $objDb->query($sSQL);
        $iCount = $objDb->getCount();

        for($i=0; $i<$iCount; $i ++)
        {
            $iSection  = $objDb->getField($i, "category_id"); 
            $iPoints    = $objDb->getField($i, "_Points"); 
            
            $sCriticalSections[$iSection] = $iPoints;
        }
        
        $iTop = 58;
        
        if($AuditTypeId == 4)
        {
            foreach($sCrSectionsArray as $iCategoryId)
            {
                $iScore     = (int)$sCriticalSections[$iCategoryId];
                $iPrevScore = (int)$sPrevCriticalSections[$iCategoryId];
                
                
                $objPdf->SetXY(121, $iTop);
                $objPdf->Write(0, ($iPrevScore>0?"Yes":"No"));
                
                $objPdf->SetXY(151, $iTop);
                $objPdf->Write(0, ($iScore>0?"Yes":"No"));
                
                $objPdf->SetXY(181, $iTop);
                $objPdf->Write(0, "N/A");

                $iTop += 10.75;
            }
            
        }else if($AuditTypeId == 5)
        {
            foreach($sCrSectionsArray as $iCategoryId)
            {
                $iScore     = (int)$sCriticalSections[$iCategoryId];
                $iPrevScore = (int)$sPrevCriticalSections[$iCategoryId];
                
                
                $objPdf->SetXY(121, $iTop);
                $objPdf->Write(0, ($iPrevScore>0?"Yes":"No"));
                
                $objPdf->SetXY(151, $iTop);
                $objPdf->Write(0, "N/A");
                
                $objPdf->SetXY(181, $iTop);
                $objPdf->Write(0, ($iScore>0?"Yes":"No"));

                $iTop += 10.75;
            }
        }
        else
        {
            foreach($sCrSectionsArray as $iCategoryId)
            {
                $iScore = (int)$sCriticalSections[$iCategoryId];
                $objPdf->SetXY(121, $iTop);
                $objPdf->Write(0, ($iScore>0?"Yes":"No"));
                
                $objPdf->SetXY(151, $iTop);
                $objPdf->Write(0, "N/A");
                
                $objPdf->SetXY(181, $iTop);
                $objPdf->Write(0, "N/A");

                $iTop += 10.75;
            }

            
        }
        
        ///////////////////////////////////////////////////////page # 5/////////////////////////////////////////////////////////////  	  

        $objPdf->setSourceFile($sBaseDir."templates/tnc-audit.pdf");
        $iTemplate = $objPdf->importPage(5);

	$iSize = $objPdf->getTemplateSize($iTemplate);

	$objPdf->AddPage( );
	$objPdf ->useTemplate($iTemplate, null, null, 0, 0, true);

	$objPdf->SetFont($fontname);
	$objPdf->SetTextColor(0, 0, 0);

	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$Id}/", ($sBaseDir.TEMP_DIR."{$Id}.png"));
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$Id}.png"), 177, 11.2, 21);
  	
	$objPdf->SetFont($fontname, '', 11);
	$objPdf->SetTextColor(128, 128, 128);
     
        $objPdf->SetXY(175, 255);
        $objPdf->Write(0, "Page #: 5");
        
        $objPdf->SetTextColor(50, 50, 50);
        $objPdf->SetFont($fontname, '', 6);

        $sAuditCode = "C".str_pad($Id, 5, 0, STR_PAD_LEFT);
        
        $objPdf->SetXY(178, 30.5);
        $objPdf->MultiCell(30, 3.5, "Audit #: {$sAuditCode}", 0, "L", false);

        

        $sSQL = "SELECT tp.section_id,
                SUM(IF(cad.score='1','1','0')) AS _YesCount,
                SUM(IF(cad.score='0','1','0')) AS _NoCount,
                SUM(IF(cad.score='-1','1','0')) AS _NACount,
                COUNT(tp.id) AS _CatPoints
                FROM tbl_crc_audit_details cad, tbl_tnc_points tp
                WHERE cad.point_id = tp.id AND tp.id IN ($Points) AND cad.audit_id='$Id' AND tp.section_id IN (1,2,3,4,5,6,9,10,11,12,13,14,15,16,18)
                Group By tp.section_id
                Order By tp.section_id";

        $objDb->query($sSQL);
        $iCount = $objDb->getCount();

        $iTop = 64.5;
        
        for($i=0; $i<$iCount; $i ++)
        {
            $iSection    = $objDb->getField($i, "section_id"); 
            $iYesCount   = $objDb->getField($i, "_YesCount"); 
            $iNoCount    = $objDb->getField($i, "_NoCount"); 
            $iNACount    = $objDb->getField($i, "_NACount"); 
            $iTotalPoints= $objDb->getField($i, "_CatPoints"); 
            
            $objPdf->SetXY(110, $iTop);
            $objPdf->Write(0, $iNoCount." / ".$iTotalPoints);

            $objPdf->SetXY(150, $iTop);
            $objPdf->Write(0, $iNACount." / ".$iTotalPoints);

            $objPdf->SetXY(190, $iTop);
            $objPdf->Write(0, $iYesCount." / ".$iTotalPoints);

            $iTop += 5.2;
        }

        ///////////////////////////////////////////////////////page # 6/////////////////////////////////////////////////////////////  	  

        $objPdf->setSourceFile($sBaseDir."templates/tnc-audit.pdf");
        $iTemplate = $objPdf->importPage(6);

	$iSize = $objPdf->getTemplateSize($iTemplate);

	$objPdf->AddPage( );
	$objPdf ->useTemplate($iTemplate, null, null, 0, 0, true);

	$objPdf->SetFont($fontname);
	$objPdf->SetTextColor(0, 0, 0);

	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$Id}/", ($sBaseDir.TEMP_DIR."{$Id}.png"));
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$Id}.png"), 177, 13.2, 21);
  	
	$objPdf->SetFont($fontname, '', 11);
	$objPdf->SetTextColor(128, 128, 128);
        
      
        $objPdf->SetXY(175, 260);
        $objPdf->Write(0, "Page #: 6");
        
        $objPdf->SetTextColor(50, 50, 50);
        $objPdf->SetFont($fontname, '', 6);

        $sAuditCode = "C".str_pad($Id, 5, 0, STR_PAD_LEFT);
        
        $objPdf->SetXY(178, 33);
        $objPdf->MultiCell(30, 3.5, "Audit #: {$sAuditCode}", 0, "L", false);


	// Report Details
	$objPdf->SetFont($fontname, '', 7);
        
        $Certifications         = getList("tbl_certification_types", "id", "certification", "id>0", "id");
        $CertificationApply     = getList("tbl_crc_audit_certifications ac, tbl_certification_types ct", "ct.id", "ac.apply", "ct.id = ac.certification_id AND ac.audit_id='$Id'");
        $CertificationComment   = getList("tbl_crc_audit_certifications ac, tbl_certification_types ct", "ct.id", "ac.comments", "ct.id = ac.certification_id AND ac.audit_id='$Id'");
        
        
        $iTop = 58;
        foreach($Certifications  as $iCertificate => $sCertificate)
        {
            
            $Apply      = $CertificationApply[$iCertificate];
            $Comments   = $CertificationComment[$iCertificate];
            
            $objPdf->SetXY(89, $iTop);
            $objPdf->Write(0, ($Apply == 'Y'?'Yes':'No'));
            
            $objPdf->SetXY(104, $iTop);
            $objPdf->Write(0, $Comments);
            
            $iTop += 5.20;
        }
        
         ///////////////////////////////////////////////////////page # 7/////////////////////////////////////////////////////////////  	  

        $objPdf->setSourceFile($sBaseDir."templates/tnc-audit.pdf");
        $iTemplate  = $objPdf->importPage(7);
	$iSize      = $objPdf->getTemplateSize($iTemplate);
        $PageNo     = 7;
        $SubSectionNo=1;
        
        $sMultiAudits = "";
        
        if($PrevAuditId > 0)
            $sMultiAudits = getDbValue ("GROUP_CONCAT(id SEPARATOR ',')", "tbl_crc_audits", "prev_audit_id='$PrevAuditId' OR id='$Id' OR id='$PrevAuditId'");
        else
            $sMultiAudits = getDbValue ("GROUP_CONCAT(id SEPARATOR ',')", "tbl_crc_audits", "prev_audit_id='$Id' OR id='$Id'");
        
        $sAuditPoints   = getList("tbl_crc_audit_details", "point_id", "point_id", "FIND_IN_SET(audit_id, '$sMultiAudits')");        
        $sSectionsList  = getList("tbl_tnc_sections s, tbl_tnc_points p", "s.id", "s.section", "s.id = p.section_id AND s.parent_id='$PSectionId' AND p.id IN (".implode(",", $sAuditPoints).")");

        foreach($sSectionsList as $iSection => $sSection)
        {
            //echo $sSection
            $sOldCategory = "";
            $sCategoriesList  = getList("tbl_tnc_categories c, tbl_tnc_points p", "c.id", "c.category", "c.id = p.category_id AND c.section_id='$iSection' AND p.id IN (".implode(",", $sAuditPoints).")", "c.position");
            foreach ($sCategoriesList as $iCategory => $sCategory)
            {
                //echo $sCategory
                if($sOldCategory != $sCategory)
                {
                    $objPdf->AddPage( );
                    $objPdf ->useTemplate($iTemplate, null, null, 0, 0, true);

                    $objPdf->SetFont($fontname);
                    $objPdf->SetTextColor(0, 0, 0);

                    // QR Code
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
                    
                    $objPdf->SetXY(10, 51.5);
                    $objPdf->Write(0, "7.".$SubSectionNo);
                            
                    $objPdf->SetXY(19, 51.5);
                    $objPdf->Write(0, $sCategory);
        
                    $sOldCategory = $sCategory;                    
                    $SubSectionNo ++;
                    $PageNo ++;
                }
                
                // Report Data                    
                $objPdf->SetFont($fontname, '', 6);
                    
                $sSQL = "SELECT tp.id, cad.score, cad.remarks, tp.point, tp.point_no
                     FROM tbl_crc_audit_details cad, tbl_tnc_points tp
                     WHERE cad.point_id=tp.id AND FIND_IN_SET(cad.audit_id, '$sMultiAudits') AND tp.category_id='$iCategory'
                     ORDER BY tp.position";
                $objDb->query($sSQL);

                $iCount = $objDb->getCount( );
                
                $iTop = 60;
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
                            
                            $objPdf->SetXY(10, 51.5);
                            $objPdf->Write(0, "7.".$SubSectionNo);
                    
                            $objPdf->SetXY(19, 51.5);
                            $objPdf->Write(0, $sCategory);

                            $objPdf->SetFont($fontname, '', 6);                            
                            $SubSectionNo++;                            
                            $PageNo ++;
                        }
                }
            }
        }
        
        ///////////////////////////////////////////////////////page # 8/////////////////////////////////////////////////////////////  	  

        $objPdf->setSourceFile($sBaseDir."templates/tnc-audit.pdf");
        $iTemplate    = $objPdf->importPage(8);
	$iSize        = $objPdf->getTemplateSize($iTemplate);
        $SubSectionNo = 1;
        
        $sFailedPoints = getList("tbl_crc_audit_details", "point_id", "point_id", "audit_id='$Id' AND score='0'");
        $sSectionsList = getList("tbl_tnc_sections s, tbl_tnc_points p", "s.id", "s.section", "s.id = p.section_id AND s.parent_id='$PSectionId' AND p.id IN (". implode(',', $sFailedPoints).")");

        foreach($sSectionsList as $iSection => $sSection)
        {
            //echo $sSection
            $sOldCategory = "";
            $sCategoriesList  = getList("tbl_tnc_categories c, tbl_tnc_points p", "c.id", "c.category", "c.id = p.category_id AND c.section_id='$iSection' AND p.id IN (".implode(',', $sFailedPoints).")", "c.position");            
            foreach ($sCategoriesList as $iCategory => $sCategory)
            {
                //echo $sCategory
                if($sOldCategory != $sCategory)
                {
                    $objPdf->AddPage( );
                    $objPdf ->useTemplate($iTemplate, null, null, 0, 0, true);

                    $objPdf->SetFont($fontname);
                    $objPdf->SetTextColor(0, 0, 0);

                    // QR Code
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
                    
                    $objPdf->SetXY(10, 51.5);
                    $objPdf->Write(0, "8.".$SubSectionNo);
                            
                    $objPdf->SetXY(19, 51.5);
                    $objPdf->Write(0, $sCategory);
        
                    $sOldCategory = $sCategory;                    
                    $SubSectionNo ++;
                    $PageNo ++;
                }
                
                // Report Data                    
                $objPdf->SetFont($fontname, '', 6);
                    
                $sSQL = "SELECT tp.id, cad.score, cad.remarks, tp.point, tp.point_no
                     FROM tbl_crc_audit_details cad, tbl_tnc_points tp
                     WHERE cad.point_id=tp.id AND cad.audit_id='$Id' AND tp.category_id='$iCategory' AND tp.id IN (". implode(',', $sFailedPoints).")
                     ORDER BY tp.position";
                $objDb->query($sSQL);

                $iCount = $objDb->getCount( );
                
                $iTop = 60;
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
                            
                            $objPdf->SetXY(10, 51.5);
                            $objPdf->Write(0, "8.".$SubSectionNo);
                    
                            $objPdf->SetXY(19, 51.5);
                            $objPdf->Write(0, $sCategory);

                            $objPdf->SetFont($fontname, '', 6);                            
                            $SubSectionNo++;                            
                            $PageNo ++;
                        }
                }
            }
        }
        
        ///////////////////////////////////////////////////////page # 9/////////////////////////////////////////////////////////////  	  
        
        $objPdf->setSourceFile($sBaseDir."templates/tnc-audit.pdf");
        $iTemplate    = $objPdf->importPage(9);
	$iSize        = $objPdf->getTemplateSize($iTemplate);
        $SubSectionNo = 1;
        
        $sFailedPoints = getList("tbl_crc_audit_details", "point_id", "point_id", "audit_id='$Id' AND score='0' AND (due_date != '' OR corrective_action != '')");
        $sSectionsList = getList("tbl_tnc_sections s, tbl_tnc_points p", "s.id", "s.section", "s.id = p.section_id AND s.parent_id='$PSectionId' AND p.id IN (". implode(',', $sFailedPoints).")");

        foreach($sSectionsList as $iSection => $sSection)
        {
            //echo $sSection
            $sOldCategory = "";
            $sCategoriesList  = getList("tbl_tnc_categories c, tbl_tnc_points p", "c.id", "c.category", "c.id = p.category_id AND c.section_id='$iSection' AND p.id IN (".implode(',', $sFailedPoints).")", "c.position");            
            foreach ($sCategoriesList as $iCategory => $sCategory)
            {
                //echo $sCategory
                if($sOldCategory != $sCategory)
                {
                    $objPdf->AddPage( );
                    $objPdf ->useTemplate($iTemplate, null, null, 0, 0, true);

                    $objPdf->SetFont($fontname);
                    $objPdf->SetTextColor(0, 0, 0);

                    // QR Code
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
                    
                    $objPdf->SetXY(10, 51.5);
                    $objPdf->Write(0, "8.".$SubSectionNo);
                            
                    $objPdf->SetXY(19, 51.5);
                    $objPdf->Write(0, $sCategory);
        
                    $sOldCategory = $sCategory;                    
                    $SubSectionNo ++;
                    $PageNo ++;
                }
                
                // Report Data                    
                $objPdf->SetFont($fontname, '', 6);
                    
                $sSQL = "SELECT tp.id, cad.score, cad.remarks, tp.point, tp.point_no, cad.due_date, cad.corrective_action
                     FROM tbl_crc_audit_details cad, tbl_tnc_points tp
                     WHERE cad.point_id=tp.id AND cad.audit_id='$Id' AND tp.category_id='$iCategory' AND tp.id IN (". implode(',', $sFailedPoints).")
                     ORDER BY tp.position";
                $objDb->query($sSQL);

                $iCount = $objDb->getCount( );
                
                $iTop = 60;
                for ($i = 0; $i < $iCount; $i ++)
                {
                        $iPoint   = $objDb->getField($i, 'id');
                        $fPointNo = $objDb->getField($i, 'point_no');
                        $fPointNo = ($fPointNo == ""?$iPoint:$fPointNo);
                        $sPoint   = $objDb->getField($i, 'point');
                        $iScore   = $objDb->getField($i, 'score');
                        $sDueDate = $objDb->getField($i, 'due_date');
                        $sRemarks = $objDb->getField($i, 'corrective_action');
                    
                        $objPdf->SetXY(10, $iTop);
                        $objPdf->Write(0, $fPointNo);
                    
                        $objPdf->SetXY(18, $iTop);
                        $objPdf->MultiCell(74, 3, $sPoint, 0, "L", false);
                        
                        $height1 = $objPdf->getLastH();
                        
                        $objPdf->SetXY(93, $iTop);
                        $objPdf->Write(0, (($iScore == '0')?'No':($iScore == '1'?'Yes':'N/A')));
                        
                        $objPdf->SetXY(105, $iTop);
                        $objPdf->Write(0, $sDueDate);
                        
                        $objPdf->SetXY(120, $iTop);
                        $objPdf->MultiCell(75, 3, $sRemarks, 0, "L", false);
                        
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
                            
                            $objPdf->SetXY(10, 51.5);
                            $objPdf->Write(0, "8.".$SubSectionNo);
                    
                            $objPdf->SetXY(19, 51.5);
                            $objPdf->Write(0, $sCategory);

                            $objPdf->SetFont($fontname, '', 6);                            
                            $SubSectionNo++;                            
                            $PageNo ++;
                        }
                }
            }
        }
        
        
         ///////////////////////////////////////////////////////page # 10/////////////////////////////////////////////////////////////  	  

        $sPointNos   = getList("tbl_tnc_points", "id", "point_no");
        $sPointsList = getList("tbl_tnc_points tp, tbl_crc_audit_details cad", "tp.id", "tp.point", "tp.id = cad.point_id AND cad.audit_id = '$Id'");        
        $sPicPointId = getList("tbl_crc_audit_pictures", "picture", "point_id", "audit_id='$Id' AND (picture LIKE '%.jpg%' OR picture LIKE '%.jpeg%' OR picture LIKE '%.png%' OR picture LIKE '%.gif%')");
        $iPictures   = getDbValue("GROUP_CONCAT(DISTINCT(picture) SEPARATOR '|-|')", "tbl_crc_audit_pictures", "audit_id='$Id' AND (picture LIKE '%.jpg%' OR picture LIKE '%.png%' OR picture LIKE '%.gif%') AND certification_id = '0'");
        $sPictures   = explode("|-|", $iPictures);
        
        if(count($sPictures) > 0)
        {
            $objPdf->setSourceFile($sBaseDir."templates/tnc-audit.pdf");
            $iTemplate  = $objPdf->importPage(10);
            $iSize      = $objPdf->getTemplateSize($iTemplate);

            $iPages = @ceil(count($sPictures) / 4);
            $iIndex = 0;
            
            for ($i = 0; $i < $iPages; $i ++)
            {
                $objPdf->AddPage( );
                $objPdf ->useTemplate($iTemplate, null, null, 0, 0, true);

                $objPdf->SetFont($fontname);
                $objPdf->SetTextColor(0, 0, 0);

                // QR Code
                QRcode::png("http://portal.3-tree.com/kpis/{$Id}/", ($sBaseDir.TEMP_DIR."{$Id}.png"));
                $objPdf->Image(($sBaseDir.TEMP_DIR."{$Id}.png"), 177, 12, 21);

                $objPdf->SetFont($fontname, '', 11);
                $objPdf->SetTextColor(128, 128, 128);


                $objPdf->SetXY(175, 260);
                $objPdf->Write(0, "Page #: {$PageNo}");

                $objPdf->SetTextColor(50, 50, 50);
                $objPdf->SetFont($fontname, '', 6);

                $sAuditCode = "C".str_pad($Id, 5, 0, STR_PAD_LEFT);
        
                $objPdf->SetXY(178, 31.5);
                $objPdf->MultiCell(30, 3.5, "Audit #: {$sAuditCode}", 0, "L", false);

                // Report Details
                $objPdf->SetFont($fontname, '', 7);
                $PageNo++;
                
                for ($j = 0; $j < 4 && $iIndex < count($sPictures); $j ++, $iIndex ++)
                {
                    $iLeft = 15;
                    $iTop  = 53;

                    if ($j == 1 || $j == 3)
                            $iLeft = 111;

                    if ($j == 2 || $j == 3)
                            $iTop = 152;
                    
                    $objPdf->Image($sTncDir.@basename($sPictures[$iIndex]), $iLeft, $iTop, 82, 74);
                    $iPoint = @$sPicPointId[@$sPictures[$iIndex]];
                    
                    $objPdf->SetXY($iLeft, ($iTop + 78));
                    $objPdf->MultiCell(80, 3.5, "{$sPointNos[$iPoint]}: {$sPointsList[$iPoint]}", 0, "L", false);
                }
            }
            
        }
        
        ///////////////////////////////////////////////////////page # 11/////////////////////////////////////////////////////////////  	  

        $objPdf->setSourceFile($sBaseDir."templates/tnc-audit.pdf");
        $iTemplate = $objPdf->importPage(11);

	$iSize = $objPdf->getTemplateSize($iTemplate);

	$objPdf->AddPage( );
	$objPdf ->useTemplate($iTemplate, null, null, 0, 0, true);

	$objPdf->SetFont($fontname);
	$objPdf->SetTextColor(0, 0, 0);

	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$Id}/", ($sBaseDir.TEMP_DIR."{$Id}.png"));
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$Id}.png"), 178, 13.2, 21);
  	
	$objPdf->SetFont($fontname, '', 11);
	$objPdf->SetTextColor(128, 128, 128);
        
      
        $objPdf->SetXY(175, 260);
        $objPdf->Write(0, "Page #: {$PageNo}");
        
        $objPdf->SetTextColor(50, 50, 50);
        $objPdf->SetFont($fontname, '', 6);

        $sAuditCode = "C".str_pad($Id, 5, 0, STR_PAD_LEFT);
        
        $objPdf->SetXY(178, 33);
        $objPdf->MultiCell(30, 3.5, "Audit #: {$sAuditCode}", 0, "L", false);


	// Report Details
	$objPdf->SetFont($fontname, '', 7);
        
        $objPdf->SetXY(12, 55);
        $objPdf->MultiCell(180, 3.5, $Observations, 0, "L", false);
        $PageNo++;
        
        ///////////////////////////////////////////////////////////////////////////////////////////////
        @unlink($sBaseDir.TEMP_DIR."{$Id}.png");

        $sPdfFile = ($sBaseDir.TEMP_DIR."{$sAuditCode}.pdf");

        $objPdf->Output(@basename($sPdfFile), 'D');


        if ($AuditCode == "")
        {
            $objDb->close( );
            $objDb2->close( );
            $objDbGlobal->close( );

            @ob_end_flush( );
        }

?>