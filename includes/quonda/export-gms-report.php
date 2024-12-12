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

	@require_once($sBaseDir."requires/fpdf/fpdf.php");
	@require_once($sBaseDir."requires/fpdi/fpdi.php");
	@require_once($sBaseDir."requires/qrcode/qrlib.php");


	$sSQL = "SELECT *,
                        (SELECT article_no FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _ArticalNo,    
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
	                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor
	         FROM tbl_qa_reports
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	$iReportId          = $objDb->getField(0, "report_id");
	$sVendor            = $objDb->getField(0, "_Vendor");
        $iArticleNo         = $objDb->getField(0, "_ArticalNo");
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
	$ssAuditStage       = $objDb->getField(0, "audit_stage");
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


	$sSpecsSheets = array( );

	for ($i = 1; $i <= 10; $i ++)
	{
		$sSpecsSheet = $objDb->getField(0, "specs_sheet_{$i}");

		if ($sSpecsSheet != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet))
			$sSpecsSheets[] = $sSpecsSheet;
	}


	@list($iLength, $iWidth, $iHeight, $sUnit) = @explode("x", $sCartonSize);

	if ($fPercentDecfective == 0)
		$fPercentDecfective = @round((($iCartonsRejected / $iTotalCartons) * 100), 2);


	$sAuditStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");


	$sSQL = "SELECT style, style_name, brand_id, sub_brand_id,
                	(SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand,
					(SELECT season FROM tbl_seasons WHERE tbl_seasons.brand_id=tbl_styles.brand_id order By tbl_styles.modified DESC Limit 0,1) AS _Season
			 FROM tbl_styles
			 WHERE id='$iStyle'";
        $objDb->query($sSQL);

	$sStyle       = $objDb->getField(0, "style");
	$sStyleName   = $objDb->getField(0, "style_name");
        $sDescription = $sStyleName.' ('.$sStyle.')';
	$iParent      = $objDb->getField(0, "brand_id");
	$iBrand       = $objDb->getField(0, "sub_brand_id");
	$sBrand       = $objDb->getField(0, "_Brand");
	$sSeason      = $objDb->getField(0, "_Season");


	$iDestination = getDbValue("destination_id", "tbl_po_colors", "po_id='$iPo'");
	$sDestination = getDbValue("destination", "tbl_destinations", "id='$iDestination'");
        $sEtdDate     = getDbValue("etd_required", "tbl_po_colors", "po_id='$iPo'");
	$fAql         = getDbValue("aql", "tbl_brands", "id='$iParent'");

	$sSizeTitles  = "";

	$sSQL = "SELECT size FROM tbl_sizes WHERE id IN ($sSizes) ORDER BY position";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sSizeTitles .= (", ".$objDb->getField($i, 0));

		$sSizeTitles = substr($sSizeTitles, 2);
	}



    @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

	$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_001_*.*");
	$sPictures = @array_merge($sPictures, @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*"));
	$sPictures = @array_map("strtoupper", $sPictures);
	$sPictures = @array_unique($sPictures);

	$sDefects = array( );
	$sPacking = array( );
	$sMisc    = array( );

	foreach ($sPictures as $sPicture)
	{
		$sPic = @basename($sPicture);

		if (@stripos($sPic, "_pack_") !== FALSE || @stripos($sPic, "_001_") !== FALSE)
			$sPacking[] = $sPicture;

		else if (@stripos($sPic, "_misc_") !== FALSE || @stripos($sPic, "_00_") !== FALSE || @substr_count($sPic, "_") < 3)
			$sMisc[] = $sPicture;

		else
			$sDefects[] = $sPicture;
	}

        $sSizesPages  = getDbValue("GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',')", "tbl_qa_report_samples", "audit_id='$Id'");
        $iSizePages   = @explode(",", $sSizesPages);
        $iColorPages  = @explode(",", $sColors);
        $iSizePages   = count($iSizePages);
        $iColorPages  = count($iColorPages);

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Page 1

	$objPdf =& new FPDI( );

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/GMS.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 164, 2, 24);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(162, 27.5, "Audit Code: {$sAuditCode}");


        //check list
        $objPdf->Text(99.5, 59.5, 'X');
        if (count($sPacking) > 0)
            $objPdf->Text(99.5, 66.5, 'X');
        if (count($sDefects) > 0)
            $objPdf->Text(99.5, 70.2, 'X');
        if (count($sSpecsSheets) > 0)
            $objPdf->Text(99.5, 74, 'X');
        
	// Report Details
	$objPdf->SetFont('Arial', '', 6);
        $objPdf->Text(50, 30.4, $sAuditStatus);
        $objPdf->Text(46, 39.5, $sVendor);
        $objPdf->Text(46, 44.5, $sStyle);
        
        $objPdf->SetFont('Arial', '', 5);
        $objPdf->SetXY(36, 47.5);
	$objPdf->MultiCell(56, 2, $sDescription, 0, 'L');
        
        $objPdf->SetFont('Arial', '', 6);
        $objPdf->Text(105, 44.5, $iArticleNo);
        $objPdf->Text(105, 49.2, $sBrand);
        
        $objPdf->SetFont('Arial', '', 5);
        $objPdf->Text(161, 43.5, $sDay); 
        $objPdf->Text(168, 43.5, date('F', strtotime($sAuditDate))); 
        $objPdf->Text(179, 43.5, $sYear); 
        
        $sSQL = "SELECT * FROM tbl_gms_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);
        
        
        $GarmentMeasurement = $objDb->getField(0, 'garment_measurement');
        $MoistureMeasurement= $objDb->getField(0, 'moisture_measurement');
        
        $DrawnCartonNo      = $objDb->getField(0, 'drawn_carton_no');
        $AssortmentCheck    = $objDb->getField(0, 'assortment_check');
        
        $DrawnCartonNos = explode(",", $DrawnCartonNo);
        $AssortmentChecks = explode(",", $AssortmentCheck);
        $iNext = 1.5;
        
        foreach($DrawnCartonNos as $key => $iCartonNo){
            
            if($iCartonNo != '')
            {
                $objPdf->Text(49.5+$iNext, 81.4, $iCartonNo);

                if($AssortmentChecks[$key] == 'Y')
                    $objPdf->Text(49.5+$iNext, 86.2, 'X');
                else
                    $objPdf->Text(49.5+$iNext, 86.2, 'N');

                $iNext += 9;
            }
        }
            
        // material column
        $objPdf->Text(58, 97.5, $objDb->getField(0, 'fk_panel_qlty'));
        $objPdf->Text(58, 101.5, $objDb->getField(0, 'hand_feel'));
        $objPdf->Text(58, 105.2, $objDb->getField(0, 'color'));
        $objPdf->Text(58, 109, $objDb->getField(0, 'shade_lot'));
        $objPdf->Text(58, 112.7, $objDb->getField(0, 'lining'));
        $objPdf->Text(58, 116.7, $objDb->getField(0, 'trim_fabric'));
        $objPdf->Text(58, 120.5, $objDb->getField(0, 'interlining'));
        $objPdf->Text(58, 124.5, $objDb->getField(0, 'shoulder_pad'));
        $objPdf->Text(58, 128.5, $objDb->getField(0, 'washing_effect'));
        $objPdf->Text(58, 132.2, $objDb->getField(0, 'down_pouch'));
        $objPdf->Text(58, 136, $objDb->getField(0, 'padding'));

        // trims column
        $objPdf->Text(99, 97.5, $objDb->getField(0, 'main_label'));
        $objPdf->Text(99, 101.5, $objDb->getField(0, 'washing_label'));
        $objPdf->Text(99, 105.2, $objDb->getField(0, 'size_label'));
        $objPdf->Text(99, 109, $objDb->getField(0, 'care_label'));
        $objPdf->Text(99, 112.7, $objDb->getField(0, 'int_size_label'));
        
        // packging column
        $objPdf->Text(140, 97.5, $objDb->getField(0, 'price_tag'));
        $objPdf->Text(140, 101.5, $objDb->getField(0, 'special_hangtag'));
        $objPdf->Text(140, 105.2, $objDb->getField(0, 'tissue_stuffing'));
        $objPdf->Text(140, 109, $objDb->getField(0, 'polybag'));
        $objPdf->Text(140, 112.7, $objDb->getField(0, 'packing_method'));
        $objPdf->Text(140, 116.7, $objDb->getField(0, 'spare_button'));
        $objPdf->Text(140, 120.5, $objDb->getField(0, 'info_sticker'));
        $objPdf->Text(140, 124.5, $objDb->getField(0, 'packing_assortment'));
        $objPdf->Text(140, 128.5, $objDb->getField(0, 'exp_carton_size'));
        $objPdf->Text(140, 132.2, $objDb->getField(0, 'exp_carton_weight'));
        $objPdf->Text(140, 136, $objDb->getField(0, 'carton_label'));
        
        // Appearance & other column
        $objPdf->Text(180, 97.5, $objDb->getField(0, 'untrimmed_thread'));
        $objPdf->Text(180, 101.5, $objDb->getField(0, 'hand_feel2'));
        $objPdf->Text(180, 105.2, $objDb->getField(0, 'fit_on_form'));
        $objPdf->Text(180, 109, $objDb->getField(0, 'twisted'));
        $objPdf->Text(180, 116.7, $objDb->getField(0, 'measurement'));
        $objPdf->Text(180, 120.5, $objDb->getField(0, 'smell'));
        $objPdf->Text(180, 124.5, $objDb->getField(0, 'mositure_test_result'));
        $objPdf->SetXY(176, 126);
	$objPdf->MultiCell(13, 1.5, $objDb->getField(0, 'azo_report_no'), 0, 'L');
        $objPdf->Text(180, 136, $objDb->getField(0, 'please_specify'));
        
        $sAdditionalPos = "";
	$sColorList     = @explode(",", $sColors);
        
	$sSQL = "SELECT CONCAT(order_no, ' ', order_status), id FROM tbl_po WHERE id='$iPo' OR FIND_IN_SET(id, '$iAdditionalPos')";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iTop = 66;
	
	for ($i = 0; $i < $iCount; $i ++)
	{

		$sPoNo = $objDb->getField($i, 0);
		$iPoId = $objDb->getField($i, 1);
			
		$iPoQty = getDbValue("SUM(order_qty)", "tbl_po_colors", "po_id='$iPoId'");
	        
                if($i<4)
                {
                    $objPdf->Text(112, $iTop, $sPoNo);
                    $objPdf->Text(140, $iTop, $iPoQty);
                    $objPdf->Text(170, $iTop, getDbValue("quantity", "tbl_qa_po_ship_quantities", "audit_id='$Id' AND po_id='$iPoId'"));
                    $iTop += 4;
                }
        }
                
        //Defects Display
        $iNext = 0;
        
        foreach ($sColorList as $sColor)
        {
            $objPdf->SetFont('Arial', '', 4);
            $objPdf->SetXY(86.2+$iNext, 142.1);
            $objPdf->MultiCell(14, 1.5, $sColor, 0, 'L');
            
            $objPdf->SetFont('Arial', '', 5);
            $objPdf->Text(91+$iNext, 149, getDbValue("quantity", "tbl_qa_color_quantities", "audit_id='$Id' AND color='$sColor'"));
            $iNext += 14;
        }

        $objPdf->Text(30, 149, $iAqlChart[$iTotalGmts]["2.5"]);
                        
        $sSQL = "SELECT code_id, color,
	                GROUP_CONCAT(DISTINCT(area_id) SEPARATOR ',') AS _Areas, 
                        SUM(IF(nature='0', defects, '0')) AS _Minor,
                        SUM(IF(nature='1', defects, '0')) AS _Major
                FROM tbl_qa_report_defects
                WHERE audit_id='$Id'
                GROUP BY code_id, color
                ORDER BY code_id";
        $objDb->query($sSQL);

	$iCount = $objDb->getCount( );
        
        $iNext = 0;
        $iTop  = 158.5;
        
        $iPrevCode   = 0;
        $iTotalMajor = 0;
        $iTotalMinor = 0;
        $ColorWiseMajor = array();
        $ColorWiseMinor = array();
        
	for($i = 0; $i < $iCount; $i ++)
	{
            $sDCode  = $objDb->getField($i, 'code_id');
            $sDColor = $objDb->getField($i, "color");
            $iMinor  = $objDb->getField($i, "_Minor");
            $iMajor  = $objDb->getField($i, "_Major");
            
            $iTotalMajor += $iMajor;
            $iTotalMinor += $iMinor;
            
            $ColorWiseMajor[$sDColor] += $iMajor;
            $ColorWiseMinor[$sDColor] += $iMinor;
        
            if($i <= 15){

                $sDefect     = getDbValue("defect", "tbl_defect_codes", "id='".$objDb->getField($i, 'code_id')."'");		
                
                if($iPrevCode == $sDCode)
                {
                    $iTop -= 4.75; 
                    $iNext = array_search($sDColor, $sColorList) * 14; 
                    
                    $objPdf->Text(90+$iNext, $iTop, $iMajor);
                    $objPdf->Text(97+$iNext, $iTop, $iMinor);
                    
                    $iTop += 4.75; 
                }
                else
                {
                    $iNext = array_search($sDColor, $sColorList) * 14; 
                    
                    $objPdf->SetXY(45.5, ($iTop - 2.2));
                    $objPdf->MultiCell(50, 2, $sDefect, 0);

                    $objPdf->Text(90+$iNext, $iTop, $iMajor);
                    $objPdf->Text(97+$iNext, $iTop, $iMinor);

                    $iTop += 4.75;                    
                }
                $iPrevCode = $sDCode;
            }
        }

        
        $iNext = 0;
        foreach($sColorList as $sColor){
            
            $objPdf->Text(90+$iNext, 229, $ColorWiseMajor[$sColor]);
            $objPdf->Text(97+$iNext, 229, $ColorWiseMinor[$sColor]);
            $iNext += 14;            
        }
        
        
        $objPdf->Text(30, 165, $iTotalMajor);
        $objPdf->Text(30, 175, $iTotalMinor);
        
        //Comments Display
	$objPdf->SetXY(67, 231.5);
	$objPdf->MultiCell(120, 2.5, $sComments, 0);
        $objPdf->Text(29, 273, $sAuditor);
        
        if($GarmentMeasurement == 'P')
            $objPdf->Text(92.4, 240.7, 'X');
        else if($GarmentMeasurement == 'F')
            $objPdf->Text(132.7, 240.7, 'X');
        
        if($sAuditResult == 'P')
            $objPdf->Text(92.4, 245.4, 'X');
        else if($sAuditResult == 'F')
            $objPdf->Text(132.7, 245.5, 'X');
            
        if($MoistureMeasurement == 'P')
            $objPdf->Text(92.4, 250.2, 'X');
        else if($MoistureMeasurement == 'F')
            $objPdf->Text(132.7, 250.2, 'X');

	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 3  -  DEFECT IMAGES


	if (count($sDefects) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/GMS.pdf");
		$iTemplateId = $objPdf->importPage(3, '/MediaBox');

		$iPages = @ceil(count($sDefects) / 6);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);

                        // QR Code
                        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 164, 2, 24);

                        $objPdf->SetFont('Arial', '', 7);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(162, 27.5, "Audit Code: {$sAuditCode}");
                        
                        $objPdf->Text(46, 39.5, $sVendor);
                        $objPdf->Text(46, 44.5, $sStyle);

                        $objPdf->SetFont('Arial', '', 5);
                        $objPdf->SetXY(36, 47.5);
                        $objPdf->MultiCell(56, 2, $sDescription, 0, 'L');

                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->Text(105, 44.5, $iArticleNo);
                        $objPdf->Text(105, 49.2, $sBrand);

                        $objPdf->SetFont('Arial', '', 5);
                        $objPdf->Text(161, 43.5, $sDay); 
                        $objPdf->Text(168, 43.5, date('F', strtotime($sAuditDate))); 
                        $objPdf->Text(179, 43.5, $sYear); 
                        
                    	for ($j = 0; $j < 6 && $iIndex < count($sDefects); $j ++, $iIndex ++)
			{
				$sName  = @strtoupper($sDefects[$iIndex]);
				$sName  = @basename($sName, ".JPG");
				$sParts = @explode("_", $sName);

				$sDefectCode = $sParts[1];
				$sAreaCode   = $sParts[2];


				$sSQL = "SELECT defect,
								(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
						 FROM tbl_defect_codes dc
						 WHERE code='$sDefectCode' AND report_id='$iReportId'";
				$objDb->query($sSQL);

				$sDefect = $objDb->getField(0, "defect");
				$sType   = $objDb->getField(0, "_Type");


				$iLeft = 26.5;
				$iTop  = 66;

				if ($j == 1 || $j == 3 || $j == 5)
					$iLeft = 109;

				if ($j == 2 || $j == 3)
					$iTop = 135.2;
                                
                                if ($j == 4 || $j == 5)
					$iTop = 204.7;


				$objPdf->Text(($iLeft + 17.4), ($iTop - 3.2), $sDefect);
                                $objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sDefects[$iIndex]), $iLeft, $iTop, 75, 59);
			}
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 3  -  PACKING IMAGES


	if (count($sPacking) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/GMS.pdf");
		$iTemplateId = $objPdf->importPage(2, '/MediaBox');


		$iPages = @ceil(count($sPacking) / 6);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			// QR Code
                        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 164, 2, 24);

                        $objPdf->SetFont('Arial', '', 7);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(162, 27.5, "Audit Code: {$sAuditCode}");
                        
                        $objPdf->Text(46, 39.5, $sVendor);
                        $objPdf->Text(46, 44.5, $sStyle);

                        $objPdf->SetFont('Arial', '', 5);
                        $objPdf->SetXY(36, 47.5);
                        $objPdf->MultiCell(56, 2, $sDescription, 0, 'L');

                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->Text(105, 44.5, $iArticleNo);
                        $objPdf->Text(105, 49.2, $sBrand);

                        $objPdf->SetFont('Arial', '', 5);
                        $objPdf->Text(161, 43.5, $sDay); 
                        $objPdf->Text(168, 43.5, date('F', strtotime($sAuditDate))); 
                        $objPdf->Text(179, 43.5, $sYear);      

			for ($j = 0; $j < 6 && $iIndex < count($sPacking); $j ++, $iIndex ++)
			{
				$iLeft = 26.5;
				$iTop  = 62;

				if ($j == 1 || $j == 3 || $j == 5)
					$iLeft = 109;

				if ($j == 2 || $j == 3)
					$iTop = 131.2;
                                
                                if ($j == 4 || $j == 5)
					$iTop = 200.7;


				$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPacking[$iIndex]), $iLeft, $iTop, 75, 63);
			}
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 6  -  SPECS SHEETS


	if (count($sSpecsSheets) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/GMS.pdf");
		$iTemplateId = $objPdf->importPage(4, '/MediaBox');


		for ($i = 0; $i < count($sSpecsSheets); $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);

                        // QR Code
                        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 164, 2, 24);

                        $objPdf->SetFont('Arial', '', 7);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(162, 27.5, "Audit Code: {$sAuditCode}");
                        
                        $objPdf->Text(46, 39.5, $sVendor);
                        $objPdf->Text(46, 44.5, $sStyle);

                        $objPdf->SetFont('Arial', '', 5);
                        $objPdf->SetXY(36, 47.5);
                        $objPdf->MultiCell(56, 2, $sDescription, 0, 'L');

                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->Text(105, 44.5, $iArticleNo);
                        $objPdf->Text(105, 49.2, $sBrand);

                        $objPdf->SetFont('Arial', '', 5);
                        $objPdf->Text(161, 43.5, $sDay); 
                        $objPdf->Text(168, 43.5, date('F', strtotime($sAuditDate))); 
                        $objPdf->Text(179, 43.5, $sYear);           
			
                        $objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheets[$i]), 25, 60.2, 159.5, 203);
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 7  -  MISC IMAGES


	if (count($sMisc) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/GMS.pdf");
		$iTemplateId = $objPdf->importPage(5, '/MediaBox');


		$iPages = @ceil(count($sMisc) / 6);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			 // QR Code
                        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 164, 2, 24);

                        $objPdf->SetFont('Arial', '', 7);
                        $objPdf->SetTextColor(50, 50, 50);

                        $objPdf->Text(162, 27.5, "Audit Code: {$sAuditCode}");
                        
                        $objPdf->Text(46, 39.5, $sVendor);
                        $objPdf->Text(46, 44.5, $sStyle);

                        $objPdf->SetFont('Arial', '', 5);
                        $objPdf->SetXY(36, 47.5);
                        $objPdf->MultiCell(56, 2, $sDescription, 0, 'L');

                        $objPdf->SetFont('Arial', '', 6);
                        $objPdf->Text(105, 44.5, $iArticleNo);
                        $objPdf->Text(105, 49.2, $sBrand);

                        $objPdf->SetFont('Arial', '', 5);
                        $objPdf->Text(161, 43.5, $sDay); 
                        $objPdf->Text(168, 43.5, date('F', strtotime($sAuditDate))); 
                        $objPdf->Text(179, 43.5, $sYear);      


			for ($j = 0; $j < 6 && $iIndex < count($sMisc); $j ++, $iIndex ++)
			{
				$iLeft = 26.5;
				$iTop  = 62;

				if ($j == 1 || $j == 3 || $j == 5)
					$iLeft = 109;

				if ($j == 2 || $j == 3)
					$iTop = 131.2;
                                
                                if ($j == 4 || $j == 5)
					$iTop = 200.7;


				$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sMisc[$iIndex]), $iLeft, $iTop, 75, 63);
			}
		}
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



	@unlink($sBaseDir.TEMP_DIR."{$sAuditCode}.png");

	$sPdfFile = ($sBaseDir.TEMP_DIR."S{$Id}-QA-Report.pdf");

	if (count($Recipients) > 0)
		$objPdf->Output($sPdfFile, 'F');

	else
		$objPdf->Output(@basename($sPdfFile), 'D');
?>