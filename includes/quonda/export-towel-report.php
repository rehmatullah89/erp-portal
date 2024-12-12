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
	$sInspectionType    = $objDb->getField(0, "inspection_type");
	$sMaker             = $objDb->getField(0, 'maker');


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

	$sAuditStage    = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");
	$iQuantity      = getDbValue("SUM(quantity)", "tbl_po_quantities", "po_id='$iPo'");
	$sAdditionalPos = "";

	$sSQL = "SELECT CONCAT(order_no, ' ', order_status), quantity FROM tbl_po WHERE id IN ($iAdditionalPos) ORDER BY order_no";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAdditionalPos .= (",".$objDb->getField($i, 0));
		$iQuantity      += $objDb->getField($i, 1);
	}


	$sSQL = "SELECT style, style_name, design_name, design_no, brand_id, sub_brand_id, (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand FROM tbl_styles WHERE id='$iStyle'";
	$objDb->query($sSQL);

	$sStyle       = $objDb->getField(0, "style");
	$sStyleName   = $objDb->getField(0, "style_name");
	$sDesignName  = $objDb->getField(0, "design_name");
	$sDesignNo    = $objDb->getField(0, "design_no");
	$iParent      = $objDb->getField(0, "brand_id");
	$iBrand       = $objDb->getField(0, "sub_brand_id");
	$sBrand       = $objDb->getField(0, "_Brand");
	
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


	$iTotalPages  = 1;
	$iTotalPages += count($sSpecsSheets);
	$iTotalPages += @ceil(count($sPacking) / 4);
	$iTotalPages += @ceil(count($sDefects) / 4);
	$iTotalPages += @ceil(count($sMisc) / 4);


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	$objPdf =& new FPDI( );

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/TIR.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	// QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));

	$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);


	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");


	$objPdf->SetFont('Arial', '', 11);

	$objPdf->Text(9, 38, "Page 1 of {$iTotalPages}");



	// Report Details
	$objPdf->SetFont('Arial', '', 7);

	if ($sInspectionType == 'G')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 65, 43.5, 4);

	else if ($sInspectionType == 'P' || $sInspectionType == 'D')
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 129, 43.5, 4);

	else
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 193, 43.5, 4);


	$objPdf->Text(20, 53, formatDate($sAuditDate));

	
	@list($iStartHour, $iStartMinutes) = @explode(":", $sStartTime);
	@list($iEndHour, $iEndMinutes)     = @explode(":", $sEndTime);

	if ($iStartHour >= 12)
	{
		if ($iStartHour > 12)
			$iStartHour -= 12;

		$sStartAmPm  = "PM";
	}

	else
		$sStartAmPm = "AM";


	if ($iEndHour >= 12)
	{
		if ($iEndHour > 12)
			$iEndHour -= 12;

		$sEndAmPm  = "PM";
	}

	else
		$sEndAmPm = "AM";

	$sStartTime = (str_pad($iStartHour, 2, '0', STR_PAD_LEFT).":".str_pad($iStartMinutes, 2, '0', STR_PAD_LEFT)." ".$sStartAmPm);
	$sEndTime   = (str_pad($iEndHour, 2, '0', STR_PAD_LEFT).":".str_pad($iEndMinutes, 2, '0', STR_PAD_LEFT)." ".$sEndAmPm);


	$objPdf->Text(60, 53, $sStartTime);
	$objPdf->Text(125, 53, $sEndTime);
	
	
	$objPdf->Text(35, 60, $sBrand);
	$objPdf->Text(35, 65.5, $sDesignName);
	$objPdf->Text(35, 71.5, $sStyle);	
	$objPdf->Text(35, 76.5, $sStyleName);

	$objPdf->SetXY(34, 80);
	$objPdf->MultiCell(70, 3.2, $sColors, 0);

	$objPdf->Text(35, 87.5, ($sPo.$sAdditionalPos));
	
	
	$objPdf->Text(156, 60, $sMaker);
	$objPdf->Text(156, 66, $sVendor);
	$objPdf->Text(156, 88, $sAuditor);	
	

	$objPdf->SetFont('Arial', '', 7);

        $sSQL = "SELECT * FROM tbl_towel_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

        $iCountFail    = 0;
	$fTicketMeters = 0;
	$fActualMeters = 0;
	$fWidth        = 0;
	$iHoles        = 0;
	$iSlub         = 0;
	$iStains       = 0;
	$iFly          = 0;
	$iOther        = 0;
        $iPcsInspected = 0;
        
	for($i = 0; $i < $iCount; $i ++)
	{
		$objPdf->Text(15, (110 + ($i * 3.15)), $objDb->getField($i, 'lot_no'));
		$objPdf->Text(30, (110 + ($i * 3.15)), $objDb->getField($i, 'roll_no'));
		$objPdf->Text(48, (110 + ($i * 3.15)), $objDb->getField($i, 'width'));
		$objPdf->Text(63, (110 + ($i * 3.15)), $objDb->getField($i, 'ticket_meters'));
		$objPdf->Text(80, (110 + ($i * 3.15)), $objDb->getField($i, 'actual_meters'));
                
                $iActualPcs = $objDb->getField($i, 'actual_meters');
                $iSampleSize     = 0;
                $iAllowedDefects = 0;
                
                if( $iActualPcs >= 2 && $iActualPcs <= 8 ){
                    $iSampleSize     = 2;
                    $iAllowedDefects = 0;
                }
                else if( $iActualPcs >= 9 && $iActualPcs <= 15 ){
                    $iSampleSize     = 3;
                    $iAllowedDefects = 0;
                }
                else if( $iActualPcs >= 16 && $iActualPcs <= 25 ){
                    $iSampleSize     = 5;
                    $iAllowedDefects = 0;
                }
                else if( $iActualPcs >= 26 && $iActualPcs <= 50 ){
                    $iSampleSize     = 8;
                    $iAllowedDefects = 0;
                }
                else if( $iActualPcs >= 51 && $iActualPcs <= 90 ){
                    $iSampleSize     = 13;
                    $iAllowedDefects = 0;
                }
                else if( $iActualPcs >= 91 && $iActualPcs <= 150 ){
                    $iSampleSize     = 20;
                    $iAllowedDefects = 1;
                }
                else if( $iActualPcs >= 151 && $iActualPcs <= 280 ){
                    $iSampleSize     = 32;
                    $iAllowedDefects = 2;
                }
                else if( $iActualPcs >= 281 && $iActualPcs <= 500 ){
                    $iSampleSize     = 50;
                    $iAllowedDefects = 3;
                }
                else if( $iActualPcs >= 501 && $iActualPcs <= 1200 ){
                    $iSampleSize     = 80;
                    $iAllowedDefects = 5;
                }
                else if( $iActualPcs >= 1201 && $iActualPcs <= 3200 ){
                    $iSampleSize     = 125;
                    $iAllowedDefects = 7;
                }
                else if( $iActualPcs >= 3201 && $iActualPcs <= 10000 ){
                    $iSampleSize     = 200;
                    $iAllowedDefects = 10;
                }
                else if( $iActualPcs >= 10001 && $iActualPcs <= 35000 ){
                    $iSampleSize     = 315;
                    $iAllowedDefects = 14;
                }
                else if( $iActualPcs >= 35001 && $iActualPcs <= 150000 ){
                    $iSampleSize     = 500;
                    $iAllowedDefects = 21;
                }
                else if( $iActualPcs >= 150001 && $iActualPcs <= 500000 ){
                    $iSampleSize     = 800;
                    $iAllowedDefects = 21;
                }
                else if( $iActualPcs >= 500000 ){
                    $iSampleSize     = 1250;
                    $iAllowedDefects = 21;
                }

                $iPcsInspected += $iSampleSize;
                $objPdf->Text(97, (110 + ($i * 3.15)), $iSampleSize);    
                $objPdf->Text(111, (110 + ($i * 3.15)), $objDb->getField($i, 'holes'));
		$objPdf->Text(121, (110 + ($i * 3.15)), $objDb->getField($i, 'slubs'));
		$objPdf->Text(132, (110 + ($i * 3.15)), $objDb->getField($i, 'stains'));
		$objPdf->Text(141, (110 + ($i * 3.15)), $objDb->getField($i, 'fly'));
                $objPdf->Text(151, (110 + ($i * 3.15)), $objDb->getField($i, 'other'));

		$fTicketMeters += $objDb->getField($i, 'ticket_meters');
		$fActualMeters += $objDb->getField($i, 'actual_meters');
		$fWidth        += $objDb->getField($i, 'width');
		$iHoles        += $objDb->getField($i, 'holes');
		$iSlub         += $objDb->getField($i, 'slubs');
		$iStains       += $objDb->getField($i, 'stains');
		$iFly          += $objDb->getField($i, 'fly');
		$iOther        += $objDb->getField($i, 'other');

		$iSubTotal      = $objDb->getField($i, 'holes') + $objDb->getField($i, 'slubs') + $objDb->getField($i, 'stains') + $objDb->getField($i, 'fly') + $objDb->getField($i, 'other');
		$iSubTotal      = ceil(($iSubTotal*3600)/($objDb->getField($i, 'width') * $objDb->getField($i, 'ticket_meters')));
		
                $Result = 'Pass';
                $iDefectivePcs = $objDb->getField($i, 'allowable_defects');
                if($iDefectivePcs > $iAllowedDefects){
                    $Result = 'Fail';
                    $iCountFail++;
                }
                
                $objPdf->Text(162, (110 + ($i * 3.15)), $iDefectivePcs);
                $objPdf->Text(178, (110 + ($i * 3.15)), $iAllowedDefects);
		$objPdf->Text(195, (110 + ($i * 3.15)), $Result);
	}

        $objPdf->Text(156, 71, $iPcsInspected);		
	//$objPdf->Text(156, 77, $iCount);
        $objPdf->Text(156, 82, "III"); //$fAql
	$objPdf->Text(65, 207.8, $iCount);
	$objPdf->Text(65, 212, $iCountFail);
	
	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetXY(11, 221);
	$objPdf->MultiCell(192, 3, $sComments, 0);


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 4  -  DEFECT IMAGES


	$iCurrentPage = 2;

	if (count($sDefects) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		$iPages = @ceil(count($sDefects) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);



			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(6, 32, "Defect Images");

			$objPdf->SetFont('Arial', '', 10);
			$objPdf->Text(6, 37, "Page {$iCurrentPage} of {$iTotalPages}");



			$objPdf->SetFont('Arial', '', 7);

			for ($j = 0; $j < 4 && $iIndex < count($sDefects); $j ++, $iIndex ++)
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


				$iLeft = 5;
				$iTop  = 47;

				if ($j == 1 || $j == 3)
					$iLeft = 107;

				if ($j == 2 || $j == 3)
					$iTop = 155;


				$sInfo  = "Type: {$sType}\n";
				$sInfo .= "Defect: {$sDefect}\n";
				$sInfo .= ("Area: ".getDbValue("area", "tbl_defect_areas", "id='$sAreaCode'")."\n");

				$objPdf->SetXY($iLeft, ($iTop + 90.5));
				$objPdf->MultiCell(98, 3.6, $sInfo, 1, "L", false);


				$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sDefects[$iIndex]), $iLeft, $iTop, 98, 90);
			}
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 5  -  PACKING IMAGES


	if (count($sPacking) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		$iPages = @ceil(count($sPacking) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);



			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(6, 32, "Packing Images");

			$objPdf->SetFont('Arial', '', 10);
			$objPdf->Text(6, 37, "Page {$iCurrentPage} of {$iTotalPages}");


			for ($j = 0; $j < 4 && $iIndex < count($sPacking); $j ++, $iIndex ++)
			{
				$iLeft = 5;
				$iTop  = 47;

				if ($j == 1 || $j == 3)
					$iLeft = 107;

				if ($j == 2 || $j == 3)
					$iTop = 150;


				$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPacking[$iIndex]), $iLeft, $iTop, 98, 98);
			}
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 6  -  SPECS SHEETS


	if (count($sSpecsSheets) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		for ($i = 0; $i < count($sSpecsSheets); $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);



			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(6, 32, "Lab Reports / Specs Sheets");

			$objPdf->SetFont('Arial', '', 10);
			$objPdf->Text(6, 37, "Page {$iCurrentPage} of {$iTotalPages}");


			$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheets[$i]), 10, 47, 190);
		}
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////  PAGE 7  -  MISC IMAGES


	if (count($sMisc) > 0)
	{
		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/page.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');


		$iPages = @ceil(count($sMisc) / 4);
		$iIndex = 0;

		for ($i = 0; $i < $iPages; $i ++, $iCurrentPage ++)
		{
			$objPdf->addPage("P", "A4");
			$objPdf->useTemplate($iTemplateId, 0, 0);


			$objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 183, 2, 24);



			$objPdf->SetFont('Arial', '', 7);
			$objPdf->SetTextColor(50, 50, 50);

			$objPdf->Text(181, 27.5, "Audit Code: {$sAuditCode}");


			$objPdf->SetFont('Arial', '', 11);
			$objPdf->Text(6, 32, "Miscellaneous Images");

			$objPdf->SetFont('Arial', '', 10);
			$objPdf->Text(6, 37, "Page {$iCurrentPage} of {$iTotalPages}");



			for ($j = 0; $j < 4 && $iIndex < count($sMisc); $j ++, $iIndex ++)
			{
				$iLeft = 5;
				$iTop  = 47;

				if ($j == 1 || $j == 3)
					$iLeft = 107;

				if ($j == 2 || $j == 3)
					$iTop = 150;


				$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sMisc[$iIndex]), $iLeft, $iTop, 98, 98);
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