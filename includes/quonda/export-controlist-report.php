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
	**  ***************************************************************************************  **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

   //ini_set('display_errors', 1);
    //error_reporting(E_ALL);

	@require_once($sBaseDir."requires/tcpdf/tcpdf.php");
	@require_once($sBaseDir."requires/fpdi2/fpdi.php");
	@require_once($sBaseDir."requires/qrcode/qrlib.php");


	$sSQL = "SELECT *,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
	                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor
	         FROM tbl_qa_reports
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	$iReportId          = $objDb->getField(0, "report_id");
	$sVendor            = $objDb->getField(0, "_Vendor");
	$sAuditor           = $objDb->getField(0, "_Auditor");
	$iPo                = $objDb->getField(0, "po_id");
	$iAdditionalPos     = $objDb->getField(0, "additional_pos");
	$sPo                = $objDb->getField(0, "_Po");
	$iStyle             = $objDb->getField(0, "style_id");
	$sColors            = $objDb->getField(0, "colors");
	$sSizes             = $objDb->getField(0, "sizes");
	$sAuditStatus       = $objDb->getField(0, "audit_status");
	$sAuditCode         = $objDb->getField(0, "audit_code");
	$sAuditDate         = $objDb->getField(0, "audit_date");
	$sStartTime         = $objDb->getField(0, "start_time");
	$sEndTime           = $objDb->getField(0, "end_time");
	$sAuditStage        = $objDb->getField(0, "audit_stage");
	$sAuditResult       = $objDb->getField(0, "audit_result");
	$sCustomSample      = $objDb->getField(0, "custom_sample");
	$iTotalGmts         = $objDb->getField(0, "total_gmts");
	$iGmtsDefective     = $objDb->getField(0, "defective_gmts");
	$iMaxDefects        = $objDb->getField(0, "max_defects");
	$iTotalCartons      = $objDb->getField(0, "total_cartons");
	$iCartonsRejected   = $objDb->getField(0, "rejected_cartons");
	$fPercentDecfective = $objDb->getField(0, "defective_percent");
	$fStandard          = $objDb->getField(0, "standard");
	$fCartonsRequired   = $objDb->getField(0, "cartons_required");
	$fCartonsShipped    = $objDb->getField(0, "cartons_shipped");
	$iShipQty           = $objDb->getField(0, "ship_qty");
	$sApprovedSample    = $objDb->getField(0, "approved_sample");
	$sShippingMark      = $objDb->getField(0, "shipping_mark");
	$sPackingCheck	    = $objDb->getField(0, "packing_check");
	$sCartonSize  	    = $objDb->getField(0, "carton_size");
	$fKnitted           = $objDb->getField(0, "knitted");
	$fDyed              = $objDb->getField(0, "dyed");
	$iCutting           = $objDb->getField(0, "cutting");
	$iSewing            = $objDb->getField(0, "sewing");
	$iFinishing         = $objDb->getField(0, "finishing");
	$iPacking           = $objDb->getField(0, "packing");
	$sFinalAuditDate    = $objDb->getField(0, "final_audit_date");
	$sComments          = $objDb->getField(0, "qa_comments");
	$iLine              = $objDb->getField(0, "line_id");
	$fDhu               = $objDb->getField(0, "dhu");


    @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
	
	
	$sSpecsSheets = array( );

	for ($i = 1; $i <= 10; $i ++)
	{
		$sSpecsSheet = $objDb->getField(0, "specs_sheet_{$i}");

		if ($sSpecsSheet != "")
		{
			if (@file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet))
				$sSpecsSheets[] = ($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet);
			
			else if (@file_exists($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsSheet))
				$sSpecsSheets[] = ($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsSheet);
		}
	}

    $sReportTypeName = getDbValue("report", "tbl_reports", "id='$iReportId'");
	@list($iLength, $iWidth, $iHeight, $sUnit) = @explode("x", $sCartonSize);

	if ($fPercentDecfective == 0)
		$fPercentDecfective = @round((($iCartonsRejected / $iTotalCartons) * 100), 2);


	$sAuditStage    = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");
		
        $sTotalPos = $iPo;
        if($iAdditionalPos != "")
            $sTotalPos .= ','.$iAdditionalPos;
        
        $iTotalColorQty = 0;
        $iColors = @explode(",", $sColors);
        
        foreach ($iColors as $sColor)
        {                
                $iColorQty = getDbValue("order_qty", "tbl_po_colors", "po_id IN ($sTotalPos) AND color LIKE '$sColor' AND style_id='$iStyle'");

                $iTotalColorQty += $iColorQty;
        }
       
	   
	$sSQL = "SELECT style, style_name, brand_id, sub_brand_id, (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand FROM tbl_styles WHERE id='$iStyle'";
	$objDb->query($sSQL);

	$sStyle       = $objDb->getField(0, "style");
	$sDescription = $objDb->getField(0, "style_name");
	$iParent      = $objDb->getField(0, "brand_id");
	$iBrand       = $objDb->getField(0, "sub_brand_id");
	$sBrand       = $objDb->getField(0, "_Brand");


	$iDestination = getDbValue("destination_id", "tbl_po_colors", "po_id='$iPo'");
	$sDestination = getDbValue("destination", "tbl_destinations", "id='$iDestination'");
	$sSizeTitles  = "";

	$sSQL = "SELECT size FROM tbl_sizes WHERE id IN ($sSizes) ORDER BY position";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sSizeTitles .= (", ".$objDb->getField($i, 0));

		$sSizeTitles = substr($sSizeTitles, 2);
	}


	$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*");
	$sPictures = @array_map("strtoupper", $sPictures);
	$sPictures = @array_unique($sPictures);

	$sDefects = array( );
	$sPacking = array( );
	$sMisc    = array( );
	$iImageNumbers = array();

	foreach ($sPictures as $sPicture)
	{
		$sPic = @basename($sPicture);

		if (@stripos($sPic, "_pack_") !== FALSE || @stripos($sPic, "_001_") !== FALSE)
			$sPacking[] = $sPicture;

		else if (@stripos($sPic, "_misc_") !== FALSE || @stripos($sPic, "_00_") !== FALSE || @substr_count($sPic, "_") < 3)
			$sMisc[] = $sPicture;

		else
		{
			$sDefects[] = $sPicture;
                        
			$iPicture = explode("_", $sPicture);

			if(array_key_exists($iPicture[1], $iImageNumbers))
					$iImageNumbers[$iPicture[1]] += 1;
			else
					$iImageNumbers[$iPicture[1]] = 1;
		}
	}

	$iTotalPages  = 2;
	$iTotalPages += count($sSpecsSheets);
	$iTotalPages += @ceil(count($sPacking) / 4);
	$iTotalPages += @ceil(count($sDefects) / 4);
	$iTotalPages += @ceil(count($sMisc) / 4);

	////////////////////////////////////////////////////////////////Page 1////////////////////////////////////////////////////

	$objPdf = new FPDI( );
	
	$objPdf->setPrintHeader(false);
	$objPdf->setPrintFooter(false);

	if ($sLanguage == "")
		$sLanguage  = getDbValue("language", "tbl_users", "id='{$_SESSION['UserId']}'");
	
	if ($sLanguage == 'de')
	{
		$Page              = 'Seite';
		$templateFile      = 'controlist-de';
		$DefectImagePage   = 'Fehlerbilder';
		$PackingImagesPage = "Verpackung Images";
		$SpecSheetPage     = "Laborberichte / Specs Sheets";
		$MiscImagesPage    = "Verschiedene Bilder";         
	}
	
	else if ($sLanguage == 'tr')
	{
		$Page              = 'Sayfa';
		$templateFile      = 'controlist-tr';
		$DefectImagePage   = 'Arıza Görüntüleri';
		$PackingImagesPage = "Ambalaj Görüntüler";
		$SpecSheetPage     = "Laboratuvar Raporları / Özellikler Levhalar";
		$MiscImagesPage    = "Çeşitli Görüntüler";
	}
	
	else
	{
		$Page              = 'Page';
		$templateFile      = 'controlist-en';
		$DefectImagePage   = 'Defect Images';
		$PackingImagesPage = "Packing Images";
		$SpecSheetPage     = "Lab Reports / Specs Sheets";
		$MiscImagesPage    = "Miscellaneous Images";
	}
		
	$objPdf->setSourceFile($sBaseDir."templates/{$templateFile}.pdf");
        $iTemplate = $objPdf->importPage(1);

	$iSize = $objPdf->getTemplateSize($iTemplate);

	$objPdf->AddPage( );
	$objPdf ->useTemplate($iTemplate, null, null, 0, 0, true);

	$objPdf->SetFont('DejaVuSans');
	$objPdf->SetTextColor(0, 0, 0);

	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));
	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 181, 14, 21);
  	
	$objPdf->SetFont('DejaVuSans', '', 11);
	$objPdf->SetTextColor(50, 50, 50);
        
        if ($sLanguage == 'de')
        {
            $objPdf->SetXY(12, 35);
            $objPdf->Write(0, "{$Page} 1 of {$iTotalPages}");
        }
        else
        {
            $objPdf->SetXY(12, 27);
            $objPdf->Write(0, "{$Page} 1 of {$iTotalPages}");
        }
        
        $objPdf->SetFont('DejaVuSans', '', 6);

        $objPdf->SetXY(177, 33);
        $objPdf->MultiCell(30, 3.5, "Audit Code: {$sAuditCode}", 0, "L", false);


	// Report Details
	$objPdf->SetFont('DejaVuSans', '', 7);

	$objPdf->SetXY(42, 40);
	$objPdf->Write(0, $sVendor);

        $objPdf->SetXY(42, 44);
	$objPdf->Write(0, $sStyle);
        
	$objPdf->SetXY(42, 48);
	$objPdf->Write(0, ($sPo));

        $objPdf->SetXY(145, 40);
	$objPdf->Write(0, formatNumber($iTotalColorQty, false));
        
        $objPdf->SetXY(145, 44);
	$objPdf->Write(0, formatNumber($iShipQty, false));

        $objPdf->SetXY(145, 48);
	$objPdf->Write(0, formatNumber($iTotalGmts, false));

        $Top = 0;
        $sColorList = explode(",", $sColors);
        foreach ($sColorList as $sColor)
        { 
            $sCartonNos = getDbValue("carton_nos", "tbl_qa_report_cartons", "audit_id = '$Id' AND color Like '$sColor'");
            if($sCartonNos != "")
            {
                $objPdf->SetXY(160, 44+$Top);
                $objPdf->MultiCell(45, 5,  trim($sCartonNos, ","),0, "L", false);
                $Top += 5;
            }
        }
	
	$objPdf->SetFont('DejaVuSans', '', 6);
        
        if ($sLanguage == 'de')
        {
            $objPdf->SetXY(95, 105);
            $objPdf->MultiCell(90, 3.5, $sColors,0, "L", false);
        }
        else
        {
            $objPdf->SetXY(126, 105);
            $objPdf->MultiCell(70, 3.5, $sColors,0, "L", false);
        }
        
        $fAql = getDbValue("aql", "tbl_brands", "id='$iParent'");
	if (@isset($iAqlChart["{$iTotalGmts}"]["{$fAql}"]))
		$iMaxDefects = $iAqlChart["{$iTotalGmts}"]["{$fAql}"];

	else
	{
		foreach ($iAqlChart as $iSampleSize => $sAqlDetails)
		{
			if ($iTotalGmts >= $sAqlDetails["F"] && $iTotalGmts <= $sAqlDetails["T"])
			{
				$iMaxDefects = $iAqlChart["{$iSampleSize}"]["{$fAql}"];

				break;
			}
		}
	}

	$objPdf->SetFont('DejaVuSans', '', 7);
	$objPdf->SetTextColor(0,0,0);

	$fTop          = 114.8;
	$iTotalDefects = 0;
	$iCriticalDefect = 0;
	$iMajorDefects   = 0;
	$iMinorDefects   = 0;
        $iPicCounter     = 1;
        $iNextTotal      = 0;
        
	$sSQL = "Select dc.defect, SUM(if(qrd.nature='1',qrd.defects,0)) as _Major, SUM(if(qrd.nature='0',qrd.defects,0)) as _Minor, dc.code, GROUP_CONCAT(qrd.remarks SEPARATOR '\n') AS _Remarks
         	 from tbl_qa_report_defects qrd, tbl_defect_codes dc
			 where dc.id=qrd.code_id AND qrd.audit_id='$Id' 
			 Group By dc.id
			 Order By dc.code";
	$objDb->query($sSQL);
	
	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
                $sDefect     = $objDb->getField($i, "defect");
                $iMajor      = $objDb->getField($i, "_Major");
		$iMinor      = $objDb->getField($i, "_Minor");
		$sCode       = $objDb->getField($i, "code");
		$sRemarks    = $objDb->getField($i, "_Remarks");

		$iDefects    = $iMajor + $iMinor;			
		$iMajorDefects += $iMajor;
		$iMinorDefects += $iMinor;			

                $sPicNumbers = "";
                $iNextTotal  += @$iImageNumbers[$sCode];
                for($j=$iPicCounter; $j<=$iNextTotal; $j++,$iPicCounter++)
                    $sPicNumbers .= $j.",";
                
                if ($sLanguage == 'de')
                {
                    $objPdf->SetXY(11, $fTop);
                    $objPdf->Write(0 , $sCode);
                
                    $objPdf->SetXY(22, $fTop);
                    $objPdf->Write(0 ,rtrim($sPicNumbers,','));
                    
                    $objPdf->SetXY(80, $fTop);
                    $objPdf->Write(0, $iDefects);

                    $objPdf->SetXY(184, $fTop);
                    if($iMajor > 0)
                        $objPdf->Write(0 , $iMajor);
                    else
                        $objPdf->Write(0 , "");

                    $objPdf->SetXY(193, $fTop);
                    if($iMinor > 0)
                        $objPdf->Write(0 , $iMinor);
                    else
                        $objPdf->Write(0 , "");

                    if(strlen($sRemarks.$sDefect) > 75)
                    {
                        $objPdf->SetFont('DejaVuSans', '', 5);
                        $objPdf->SetXY(100, $fTop - 1.2);
                        $objPdf->MultiCell(80, 2, $sDefect.(!empty(trim($sRemarks))?' ('.preg_replace( "/\r|\n/", ", ", $sRemarks).')':''), 0, "L", false);
                        $objPdf->SetFont('DejaVuSans', '', 7);

                    }else
                    {
                        $objPdf->SetXY(100, $fTop);
                        $objPdf->Write(0 , $sDefect.(!empty(trim($sRemarks))?' ('.preg_replace( "/\r|\n/", ", ", $sRemarks).')':''));
                    }
                }
                else 
                {
                    $objPdf->SetXY(12, $fTop);
                    $objPdf->Write(0 , $sCode);
                
                    $objPdf->SetXY(28, $fTop);
                    $objPdf->Write(0 , rtrim($sPicNumbers,','));

                    $objPdf->SetXY(44, $fTop);
                    $objPdf->Write(0 ,$iDefects);

                    $objPdf->SetXY(184, $fTop);
                    if($iMajor > 0)
                        $objPdf->Write(0 , $iMajor);
                    else
                        $objPdf->Write(0 , "");

                    $objPdf->SetXY(193, $fTop);
                    if($iMinor > 0)
                        $objPdf->Write(0 , $iMinor);
                    else
                        $objPdf->Write(0 , "");

                    if(strlen($sRemarks.$sDefect) > 100)
                    {
                        $objPdf->SetFont('DejaVuSans', '', 5);
                        $objPdf->SetXY(52, $fTop - 1.2);
                        $objPdf->MultiCell(120, 2, $sDefect.(!empty(trim($sRemarks))?' ('.preg_replace( "/\r|\n/", ", ", $sRemarks).')':''), 0, "L", false);
                        $objPdf->SetFont('DejaVuSans', '', 7);

                    }else
                    {
                        $objPdf->SetXY(52, $fTop);
                        $objPdf->Write(0 , $sDefect.(!empty(trim($sRemarks))?' ('.preg_replace( "/\r|\n/", ", ", $sRemarks).')':''));
                    }
                }

		$fTop += 4.7;
		$iTotalDefects += $iDefects;
	}

	
	$objPdf->SetFont('DejaVuSans', '', 7);

	$objPdf->SetXY(185, 216.5);
	$objPdf->Write(0 , $iMajorDefects);

	$objPdf->SetXY(195, 216.5);
	$objPdf->Write(0, $iMinorDefects);

        $objPdf->SetFont('DejaVuSans', '', 6);
        $objPdf->SetTextColor(50, 50, 50);
        
	$objPdf->SetXY(142.6, 52);
	$objPdf->Write(0, "HF:(".$iMajorDefects.")".", NF:(".$iMinorDefects.")");

        $objPdf->SetFont('DejaVuSans', '', 7);
	$objPdf->SetTextColor(0,0,0);
        
	$objPdf->SetXY(65, 238);
	$objPdf->MultiCell(30, 3.5, $sAuditor, 0, "L", false);
	
	$objPdf->SetXY(69, 249);
	$objPdf->Write(0, formatDate($sAuditDate));

	$objPdf->SetXY(115, 238);
	$objPdf->Write(0 , $sVendor);

	if ($sAuditResult == "P")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 92, 256, 4);
	else if ($sAuditResult == "F")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 140, 256, 4);
	else
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 192, 256, 4);

	if($sAuditStage == 'Inline')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 49, 31, 4);
	else if($sAuditStage == 'Final')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 100, 31, 4);
	else
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 152, 31, 4);

        /////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 2

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/{$templateFile}.pdf");
	$iTemplateId = $objPdf->importPage(2, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);

	$objPdf->SetFont('DejaVuSans', '', 7);
        $objPdf->SetTextColor(50, 50, 50);

        if ($sLanguage == 'de')
        {
            $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178, 2, 24);
            
            $objPdf->SetXY(173, 25);
            $objPdf->MultiCell(30, 3.5, "Audit Code: {$sAuditCode}", 0, "L", false);
	
            $objPdf->SetFont('DejaVuSans', '', 11);
            $objPdf->SetXY(11, 28);
            $objPdf->Write(0, "{$Page} 2 of {$iTotalPages}");
        }
        else
        {
            $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);
            
            $objPdf->SetXY(177, 25);
            $objPdf->MultiCell(30, 3.5, "Audit Code: {$sAuditCode}", 0, "L", false);
	
            $objPdf->SetFont('DejaVuSans', '', 11);
            $objPdf->SetXY(8, 26);
            $objPdf->Write(0, "{$Page} 2 of {$iTotalPages}");
        }
        
	// Report Details
	$objPdf->SetFont('DejaVuSans', '', 7);
	$objPdf->SetTextColor(0, 0, 0);

	$objPdf->SetXY(21, 36);
	$objPdf->Write(0, $sBrand);

	$objPdf->SetXY(78, 36.5);
	$objPdf->Write(0, $sVendor);

	$objPdf->SetXY(130, 36.5);
	$objPdf->Write(0, formatDate($sAuditDate));

	$objPdf->SetXY(171, 36.2);
	$objPdf->MultiCell(30, 3.5, $sAuditor, 0, "L", false);

	$objPdf->SetXY(78, 47);
	$objPdf->Write(0, $sStyle);

	$objPdf->SetXY(140, 47);
	$objPdf->Write(0, formatNumber($iTotalColorQty, false));

        if ($sLanguage == 'de')
        {
            $objPdf->SetXY(15, 47);
            $objPdf->Write(0, ($sPo.$sAdditionalPos));
        
            $objPdf->SetXY(186, 47);
            $objPdf->Write(0, formatNumber($iShipQty, false));
            
            $objPdf->SetXY(9, 98);
            $objPdf->MultiCell(200, 5, $sComments, 0, "L", false);
            
            $objPdf->SetXY(165, 146);
            $objPdf->Write(0, $sAuditor);
            
            $objPdf->SetXY(29, 69);
            $objPdf->Write(0, $sDescription);
        
        }
        else if ($sLanguage == 'tr')
        {
            $objPdf->SetXY(13, 47);
            $objPdf->Write(0, ($sPo.$sAdditionalPos));
        
            $objPdf->SetXY(186, 47);
            $objPdf->Write(0, formatNumber($iShipQty, false));
            
            $objPdf->SetXY(6, 98);
            $objPdf->MultiCell(200, 5, $sComments, 0, "L", false);
            
            $objPdf->SetXY(165, 146);
            $objPdf->Write(0, $sAuditor);
            
            $objPdf->SetXY(26, 69);
            $objPdf->Write(0, $sDescription);
        }
        else{
            $objPdf->SetXY(13, 47);
            $objPdf->Write(0, ($sPo.$sAdditionalPos));
        
            $objPdf->SetXY(180, 46.6);
            $objPdf->Write(0, formatNumber($iShipQty, false));
            
            $objPdf->SetXY(6, 97);
            $objPdf->MultiCell(200, 5, $sComments, 0, "L", false);
            
            $objPdf->SetXY(165, 144);
            $objPdf->Write(0, $sAuditor);
            
            $objPdf->SetXY(26, 69);
            $objPdf->Write(0, $sDescription);
        }
	
	$objPdf->SetXY(24, 57);
	$objPdf->Write(0, $sDestination);

	$objPdf->SetXY(65, 57);
	$objPdf->MultiCell(70, 3.5, $sColors, 0, "L", false);

        $objPdf->SetXY(187, 57);
	$objPdf->Write(0, formatNumber($fCartonsShipped, false));

	$objPdf->SetXY(138, 70);
	$objPdf->Write(0, $sAuditStatus);

	$objPdf->SetXY(175, 70);
	$objPdf->Write(0, strtoupper($sAuditStage));

        $objPdf->SetXY(22, 80);
	$objPdf->MultiCell(160, 3.5, $sSizeTitles, 0, "L", false);
        
       	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 3

	/*$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/{$templateFile}.pdf");
	$iTemplateId = $objPdf->importPage(3, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);


	$objPdf->SetFont('DejaVuSans', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->SetXY(177, 25);
        $objPdf->MultiCell(30, 3.5, "Audit Code: {$sAuditCode}", 0, "L", false);


	$objPdf->SetFont('DejaVuSans', '', 11);
	$objPdf->SetXY(8, 26);
	$objPdf->Write(0, "{$Page} 3 of {$iTotalPages}");


	// Report Details
	$objPdf->SetFont('DejaVuSans', '', 11);
	$objPdf->SetTextColor(0, 0, 0);
	
	if($sLanguage == 'de')
		$objPdf->SetXY(108, 43);
	else
		$objPdf->SetXY(116, 43);
	
	$objPdf->Write(0, formatNumber($fAql, true, (($fAql < 1) ? 2 : 1)));*/

	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 4  -  DEFECT IMAGES

	$iCurrentPage = 3;

	if (count($sDefects) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/{$templateFile}.pdf");

                if($sLanguage == 'de')
                    $iTemplateId = $objPdf->importPage(3, '/MediaBox');
                else
                    $iTemplateId = $objPdf->importPage(4, '/MediaBox');

		if ($sLanguage == 'de')
		{
			$Type       = 'Art';
                        $Code       = 'Fehlercode';
			$Defect     = 'Defekt';
			$Area       = 'Bereich';
                        $Nature     = 'Schwere';
                        $Critical   = 'Kritisch';
                        $Major      = 'Haupt';
                        $Minor      = 'Geringer';
                        $Remarks    = 'Bemerkungen';
		}

		else if($sLanguage == 'tr')
		{
			$Type       = 'Tip';
                        $Code       = 'Arıza Kod';
			$Defect     = 'Kusur';
			$Area       = 'Alan';
                        $Nature     = 'şiddet';
                        $Critical   = 'Kritik';
                        $Major      = 'Majör';
                        $Minor      = 'Küçük';
                        $Remarks    = 'Yorumlar';
		}

		else
		{
			$Type       = 'Type';
                        $Code       = 'Defect Code';
			$Defect     = 'Defect';
			$Area       = 'Area';
                        $Nature     = 'Severity';
                        $Critical   = 'Critical';
                        $Major      = 'Major';
                        $Minor      = 'Minor';
                        $Remarks    = 'Comments';
		}
		
				
		$iPages = @ceil(count($sDefects) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);

                        $objPdf->SetFont('DejaVuSans', '', 7);
			$objPdf->SetTextColor(50, 50, 50);
                        
                        if ($sLanguage == 'de')
                        {
                            $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178, 2, 24);

                            $objPdf->SetXY(173, 25);
                            $objPdf->MultiCell(30, 3.5, "Audit Code: {$sAuditCode}", 0, "L", false);

                            $objPdf->SetFont('DejaVuSans', '', 11);
                            $objPdf->SetXY(11, 35);
                            $objPdf->Write(0, $DefectImagePage);
                        
                            $objPdf->SetXY(11, 28);
                            $objPdf->Write(0, "{$Page} {$iCurrentPage} of {$iTotalPages}");
                        }
                        else
                        {
                            $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);

                            $objPdf->SetXY(177, 25);
                            $objPdf->MultiCell(30, 3.5, "Audit Code: {$sAuditCode}", 0, "L", false);

                            $objPdf->SetFont('DejaVuSans', '', 11);
                            $objPdf->SetXY(6, 30);
                            $objPdf->Write(0, $DefectImagePage);
                        
                            $objPdf->SetXY(8, 26);
                            $objPdf->Write(0, "{$Page} {$iCurrentPage} of {$iTotalPages}");
                        }

			$objPdf->SetFont('DejaVuSans', '', 7);

			for ($j = 0; $j < 4 && $iIndex < count($sDefects); $j ++, $iIndex ++)
			{
				$sName  = @strtoupper($sDefects[$iIndex]);
				$sName  = @basename($sName, ".JPG");
				$sParts = @explode("_", $sName);

				$sDefectCode = $sParts[1];
				$sAreaCode   = $sParts[2];

				$sDefectQuery   = ($sLanguage == 'en'?'defect':"defect_".$sLanguage);
				$sTypeQuery     = ($sLanguage == 'en'?'type':"type_".$sLanguage);
				$sAreaQuery     = ($sLanguage == 'en'?'area':"area_".$sLanguage);
                                
                                	
				$sSQL = "SELECT id, {$sDefectQuery},
                                (SELECT {$sTypeQuery} FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
						 FROM tbl_defect_codes dc
						 WHERE dc.code='$sDefectCode' AND dc.report_id='$iReportId'";
				$objDb->query($sSQL);

				$iDefect = $objDb->getField(0, 0);
				$sDefect = $objDb->getField(0, 1);
				$sType   = $objDb->getField(0, 2);
                                
				$sArea   = getDbValue("{$sAreaQuery}", "tbl_defect_areas", "id='$sAreaCode'");
				$sNature = getDbValue("nature", "tbl_qa_report_defects", "code_id='$iDefect' AND audit_id='$Id'");
				$sRemarks= getDbValue("remarks", "tbl_qa_report_defects", "code_id='$iDefect' AND audit_id='$Id'");
				
				$iLeft = 5;
				$iTop  = 47;

				if ($j == 1 || $j == 3)
					$iLeft = 107;

				if ($j == 2 || $j == 3)
					$iTop = 155;

				//$sInfo  = "{$Type}: {$sType}\n";
                                $sInfo  = "{$Code}: {$sDefectCode}\n";
				$sInfo .= "{$Defect}: {$sDefect}\n";
				$sInfo .= ("{$Area}: ".$sArea."\n");
				$sInfo .= ("$Nature: ".($sNature == '2'?$Critical:($sNature == '1'?$Major:$Minor))."\n");
				$sInfo .= "{$Remarks}: {$sRemarks}\n";

                                 if ($sLanguage == 'de')
                                 {
                                    $objPdf->SetXY($iLeft+3, ($iTop + 90.5));
                                    $objPdf->MultiCell(92, 3.6, $sInfo, 1, "L", false);
                                    $objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sDefects[$iIndex]), $iLeft+3, $iTop, 92, 90);
                                 }
                                 else
                                 {
                                    $objPdf->SetXY($iLeft, ($iTop + 90.5));
                                    $objPdf->MultiCell(98, 3.6, $sInfo, 1, "L", false);
                                    $objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sDefects[$iIndex]), $iLeft, $iTop, 98, 90);
                                 }
			}
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 5  -  PACKING IMAGES


	if (count($sPacking) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/{$templateFile}.pdf");
                
		if($sLanguage == 'de')
                    $iTemplateId = $objPdf->importPage(3, '/MediaBox');
                else
                    $iTemplateId = $objPdf->importPage(4, '/MediaBox');


		$iPages = @ceil(count($sPacking) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);
                        
			$objPdf->SetFont('DejaVuSans', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

                        if ($sLanguage == 'de')
                        {
                            $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178, 2, 24);

                            $objPdf->SetXY(173, 25);
                            $objPdf->MultiCell(30, 3.5, "Audit Code: {$sAuditCode}", 0, "L", false);

                            $objPdf->SetFont('DejaVuSans', '', 11);
                            $objPdf->SetXY(11, 35);
                            $objPdf->Write(0, $PackingImagesPage);
                        
                            $objPdf->SetXY(11, 28);
                            $objPdf->Write(0, "{$Page} {$iCurrentPage} of {$iTotalPages}");
                        }
                        else
                        {
                            $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);

                            $objPdf->SetXY(177, 25);
                            $objPdf->MultiCell(30, 3.5, "Audit Code: {$sAuditCode}", 0, "L", false);

                            $objPdf->SetFont('DejaVuSans', '', 11);
                            $objPdf->SetXY(6, 30);
                            $objPdf->Write(0, $PackingImagesPage);
                        
                            $objPdf->SetXY(8, 26);
                            $objPdf->Write(0, "{$Page} {$iCurrentPage} of {$iTotalPages}");
                        }
                        
			for ($j = 0; $j < 4 && $iIndex < count($sPacking); $j ++, $iIndex ++)
			{
				$iLeft = 5;
				$iTop  = 47;

				if ($j == 1 || $j == 3)
					$iLeft = 107;

				if ($j == 2 || $j == 3)
					$iTop = 150;


                                if ($sLanguage == 'de')
                                    $objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPacking[$iIndex]), $iLeft+3, $iTop, 92, 94);
                                else
                                    $objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPacking[$iIndex]), $iLeft, $iTop, 98, 98);
			}
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 6  -  SPECS SHEETS


	if (count($sSpecsSheets) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/{$templateFile}.pdf");
		
                if($sLanguage == 'de')
                    $iTemplateId = $objPdf->importPage(3, '/MediaBox');
                else
                    $iTemplateId = $objPdf->importPage(4, '/MediaBox');


		for ($i = 0; $i < count($sSpecsSheets); $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);
                        
                        
                         if ($sLanguage == 'de')
                        {
                            $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178, 2, 24);

                            $objPdf->SetXY(173, 25);
                            $objPdf->MultiCell(30, 3.5, "Audit Code: {$sAuditCode}", 0, "L", false);

                            $objPdf->SetFont('DejaVuSans', '', 11);
                            $objPdf->SetXY(11, 35);
                            $objPdf->Write(0, $SpecSheetPage);
                        
                            $objPdf->SetXY(11, 28);
                            $objPdf->Write(0, "{$Page} {$iCurrentPage} of {$iTotalPages}");
                        }
                        else
                        {
                            $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);

                            $objPdf->SetXY(177, 25);
                            $objPdf->MultiCell(30, 3.5, "Audit Code: {$sAuditCode}", 0, "L", false);

                            $objPdf->SetFont('DejaVuSans', '', 11);
                            $objPdf->SetXY(6, 30);
                            $objPdf->Write(0, $SpecSheetPage);
                        
                            $objPdf->SetXY(8, 26);
                            $objPdf->Write(0, "{$Page} {$iCurrentPage} of {$iTotalPages}");
                        }
                        
                       $objPdf->Image($sSpecsSheets[$i], 10, 47, 190, 210);
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 7  -  MISC IMAGES


	if (count($sMisc) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/{$templateFile}.pdf");
		
                if($sLanguage == 'de')
                    $iTemplateId = $objPdf->importPage(3, '/MediaBox');
                else
                    $iTemplateId = $objPdf->importPage(4, '/MediaBox');


		$iPages = @ceil(count($sMisc) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);
                       
			$objPdf->SetFont('DejaVuSans', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

                        if ($sLanguage == 'de')
                        {
                            $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178, 2, 24);

                            $objPdf->SetXY(173, 25);
                            $objPdf->MultiCell(30, 3.5, "Audit Code: {$sAuditCode}", 0, "L", false);

                            $objPdf->SetFont('DejaVuSans', '', 11);
                            $objPdf->SetXY(11, 35);
                            $objPdf->Write(0, $MiscImagesPage);
                        
                            $objPdf->SetXY(11, 28);
                            $objPdf->Write(0, "{$Page} {$iCurrentPage} of {$iTotalPages}");
                        }
                        else
                        {
                            $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);

                            $objPdf->SetXY(177, 25);
                            $objPdf->MultiCell(30, 3.5, "Audit Code: {$sAuditCode}", 0, "L", false);

                            $objPdf->SetFont('DejaVuSans', '', 11);
                            $objPdf->SetXY(6, 30);
                            $objPdf->Write(0, $MiscImagesPage);
                        
                            $objPdf->SetXY(8, 26);
                            $objPdf->Write(0, "{$Page} {$iCurrentPage} of {$iTotalPages}");
                        }
                        
			for ($j = 0; $j < 4 && $iIndex < count($sMisc); $j ++, $iIndex ++)
			{
				$iLeft = 5;
				$iTop  = 47;

				if ($j == 1 || $j == 3)
					$iLeft = 107;

				if ($j == 2 || $j == 3)
					$iTop = 150;

                                if ($sLanguage == 'de')
                                    $objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sMisc[$iIndex]), $iLeft+3, $iTop, 92, 94);
                                else
                                    $objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sMisc[$iIndex]), $iLeft, $iTop, 98, 98);
			}
		}
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	@unlink($sBaseDir.TEMP_DIR."{$sAuditCode}.png");

	$sPdfFile = (ABSOLUTE_PATH.TEMP_DIR."S{$Id}-QA-Report-{$sLanguage}.pdf");

	if (count($Recipients) > 0)
		$objPdf->Output($sPdfFile, 'F');

	else
		$objPdf->Output(@basename($sPdfFile), 'D');
?>