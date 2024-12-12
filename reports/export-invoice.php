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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Total      = IO::strValue("Total");
	$Discount   = IO::floatValue("Discount");
	$Rate       = IO::floatValue("Rate");
	$Brand      = IO::getArray("Brand");
	$Vendor     = IO::getArray("Vendor");
	$Region     = IO::intValue("Region");
	$Currency   = IO::strValue("Currency");
	$BilledFrom = IO::strValue("BilledFrom");


	if ($Total != "")
	{
		@require_once($sBaseDir."requires/fpdf/fpdf.php");
		@require_once($sBaseDir."requires/fpdi/fpdi.php");


		$objPdf = new FPDI( );

		$iPageCount  = $objPdf->setSourceFile($sBaseDir.TEMP_DIR."{$_SESSION['UserId']}-TT-Invoice.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');

		$objPdf->addPage("P", "A4", "pt");
		$objPdf->useTemplate($iTemplateId, 0, 0);


		$objPdf->SetFont('Arial', '', 10);
		$objPdf->SetTextColor(50, 50, 50);

		$objPdf->Text(162, 136.8, "{$Currency} ".formatNumber($Total));


		$fDiscount = 0;

		if ($Discount > 0)
			$fDiscount = (($Total / 100) * $Discount);

		$fTotal = ($Total - $fDiscount);


		$objPdf->SetFont('Arial', '', 11);

		if ($Discount > 0)
		{
			$objPdf->Text(127, 170, ("Discount (".formatNumber($Discount)."%)"));
			$objPdf->Text(162, 170, ("{$Currency} ".formatNumber($fDiscount)));
		}


		$iTop = 0;
		
		if($BilledFrom == "Triple Tree")
		{
			$iTop = 3;
			$objPdf->Text(175, 123.8+$iTop, "({$Currency})");
		}
		
		$objPdf->Text(162, 176+$iTop, ("{$Currency} ".formatNumber($fTotal)));


		$objPdf->SetFont('Arial', '', 10);

		$objPdf->SetXY(7, 180+$iTop);
		$objPdf->MultiCell(195, 6, strtoupper("AMOUNT IN WORDS: ".currencyInWords(formatNumber($fTotal, true, 2, false), $Currency)), 0);


		// Audit Details
		$iPageCount = $objPdf->setSourceFile($sBaseDir.TEMP_DIR."{$_SESSION['UserId']}-TT-Details.pdf");

		for ($i = 1; $i <= $iPageCount; $i ++)
		{
			$iTemplateId = $objPdf->importPage($i, '/MediaBox');

			$objPdf->addPage("L", "A4", "pt");
			$objPdf->useTemplate($iTemplateId, 0, 0);
		}


		$sPdfFile = ($sBaseDir.TEMP_DIR."Invoice.pdf");

		$objPdf->Output(@basename($sPdfFile), 'D');


		@unlink($sBaseDir.TEMP_DIR."{$_SESSION['UserId']}-TT-Invoice.pdf");
		@unlink($sBaseDir.TEMP_DIR."{$_SESSION['UserId']}-TT-Details.pdf");
		@unlink($sBaseDir.TEMP_DIR.IO::strValue("Signatures"));
	}

	
	
	else
	{
		$FromDate     = IO::strValue("FromDate");
		$ToDate       = IO::strValue("ToDate");
		$Brand        = IO::getArray('Brand');
		$Vendor       = IO::getArray('Vendor');
		$Region       = IO::intValue('Region');
		$Rate         = IO::floatValue("Rate");
		$InvoiceNo    = IO::strValue('InvoiceNo');
		$InvoiceDate  = IO::strValue('InvoiceDate');
		$DueDate      = IO::strValue('DueDate');
		$BilledTo     = IO::strValue('BilledTo');
		$PaymentTerms = IO::strValue('PaymentTerms');
		$Description  = IO::strValue('Description');
		$Duplicate    = IO::strValue("Duplicate");
		$Inspections  = IO::strValue("Inspections");
		$Quantity     = IO::strValue("Quantity");
		$Terms        = IO::strValue('Terms');
		$NabilaMatrix = IO::strValue('NabilaMatrix');
		$Signatures   = "";

		
		$Vendors  = @implode(",", $Vendor);
		$Brands   = @implode(",", $Brand);
		
		$sBrands  = getDbValue("GROUP_CONCAT(brand SEPARATOR ', ')", "tbl_brands", "id in ({$Brands})");
		$sVendors = getDbValue("GROUP_CONCAT(vendor SEPARATOR ', ')", "tbl_vendors", "id in ({$Vendors})");

        
		if ($_FILES['Signatures']['name'] != "")
		{
			$Signatures = IO::getFileName($_FILES['Signatures']['name']);

			if (!@move_uploaded_file($_FILES['Signatures']['tmp_name'], ($sBaseDir.TEMP_DIR.$Signatures)))
					$Signatures = "";
		}

/*
		$iDays    = 0;
		$iDueDate = strtotime($InvoiceDate);

		while ($iDays < 15)
		{
			$iDueDate = ($iDueDate + 86400);
			$iDay     = date("N", $iDueDate);

			if ($iDay < 6)
				$iDays ++;
		}
*/


		@require_once($sBaseDir."requires/fpdf/fpdf.php");
		@require_once($sBaseDir."requires/fpdi/fpdi.php");


		$objPdf = new FPDI( );

		if($BilledFrom == "Triple Tree")
                {
                    $iTop = -8;
                    $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/tt-invoice2.pdf");
                }
		else{
                    $iTop = -12;
                    $iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/tt-invoice.pdf");
                }

		
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');

		$objPdf->addPage("P", "A4", "pt");
		$objPdf->useTemplate($iTemplateId, 0, 0);
		$objPdf->SetTextColor(50, 50, 50);
		$objPdf->SetFont('Arial', '', 10);
		
		$objPdf->Text(170, 73.5+$iTop, $InvoiceNo);
		$objPdf->Text(170, 82+$iTop, date("d-M-Y", strtotime($InvoiceDate)));
		$objPdf->Text(170, 90+$iTop, date("d-M-Y", strtotime($DueDate)));
		
		if (@strpos($BilledTo, "CWS-Boco Supply Chain Management GmbH") !== FALSE)
		{
			$objPdf->SetFont('Arial', '', 8);
			$objPdf->SetXY(32, 69.5+$iTop);
			$objPdf->MultiCell(108, 5, iconv('UTF-8', 'windows-1252', $BilledTo), 0);

			$objPdf->SetFont('Arial', '', 10);
		}

		else
		{
			$objPdf->SetXY(32, 69.5+$iTop);
			$objPdf->MultiCell(150, 5, iconv('UTF-8', 'windows-1252', $BilledTo), 0);
		}


		$objPdf->SetXY(39, 84+($BilledFrom == "Triple Tree"?0:2.5));
		$objPdf->MultiCell(108, 6, $PaymentTerms, 0);
			
		$objPdf->SetFont('Arial', '', 7);
		$objPdf->SetTextColor(50, 50, 50);
		
		$objPdf->Text(9, 109+$iTop, "Rate: {$Rate}%");
		$objPdf->Text(9, 117+$iTop, "Region: ".(($Region == '') ? '-' : getDbValue("country", "tbl_countries", "id='$Region'")));

		$objPdf->SetXY(50, 107+$iTop);
		$objPdf->MultiCell(145, 3.5, ("Brand".((count($Brands) != 1) ? "s" : "").":  {$sBrands}"), 0, "L");

		$objPdf->SetXY(50, 115+$iTop);
		$objPdf->MultiCell(145, 3.5, ("Vendor".((count($Vendors) != 1) ? "s" : "").":  {$sVendors}"), 0, "L");
                
				
		$objPdf->SetFont('Arial', '', 11);

		$objPdf->SetXY(9, 133+($BilledFrom == "Triple Tree"?-4:0));
		$objPdf->MultiCell(145, 7, $Description, 0, "L");

/*
		$objPdf->SetFont('Arial', '', 10);

		$objPdf->SetXY(99, 255);
		$objPdf->Cell(100, 6, "{$Name} ({$Designation})", 0, 2, "R");
*/
		if ($NabilaMatrix == "Y")
			$objPdf->Image(($sBaseDir."images/nabila-matrix-stamp.jpg"), 145, 228, 50);

		else if($NabilaMatrix == "Y2")
			$objPdf->Image(($sBaseDir."images/3tree-stamp.jpg"), 149, 225, 40);			

		else if ($Signatures != "")
			$objPdf->Image(($sBaseDir.TEMP_DIR.$Signatures), 145, 228, 50);


		$sPdfFile = ($sBaseDir.TEMP_DIR."{$_SESSION['UserId']}-TT-Invoice.pdf");

		$objPdf->Output($sPdfFile, 'F');





		$sReportTypes = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
		$sConditions  = " WHERE audit_result!='' AND audit_stage='F' AND FIND_IN_SET(report_id, '$sReportTypes') AND NOT FIND_IN_SET(report_id, '$sQmipReports') ";
		
		if ($$Duplicate == "N")
			$sConditions .= " AND audit_result='P' ";

		if (count($Vendor) > 0)
			$sConditions .= (" AND FIND_IN_SET(vendor_id, '".@implode(",", $Vendor)."') ");

		else
			$sConditions .= " AND FIND_IN_SET(vendor_id, '{$_SESSION['Vendors']}') ";

		if ($Region > 0)
			$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

		if ($Inspections == "Audit")
			$sConditions .= " AND (audit_date BETWEEN '$FromDate' AND '$ToDate') ";




		$sSQL = ("SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '".@implode(",", $Brand)."') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}') ");
		$objDb->query($sSQL);

		$iCount  = $objDb->getCount( );
		$sStyles = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sStyles .= (",".$objDb->getField($i, 0));

		if ($sStyles != "")
			$sStyles = substr($sStyles, 1);

		$sConditions .= " AND style_id IN ($sStyles) ";



		$sSQL = ("SELECT DISTINCT(po.id), po.currency FROM tbl_po po, tbl_po_colors pc WHERE po.id=pc.po_id AND FIND_IN_SET(po.brand_id, '".@implode(",", $Brand)."')");

		if (count($Vendor) > 0)
			$sSQL .= (" AND FIND_IN_SET(po.vendor_id, '".@implode(",", $Vendor)."') ");

		else
			$sSQL .= " AND FIND_IN_SET(po.vendor_id, '{$_SESSION['Vendors']}') ";
		
		if ($Inspections == "GAC")
			$sSQL .= " AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate') ";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND po_id IN ($sPos) ";
		
		if (@strpos($BilledTo, "MGF Sourcing Far East Limited") !== FALSE)
		{
			if ($Duplicate == "N")
				$sConditions .= " AND audit_result='P' ";
		}

		
		$sCurrency = $objDb->getField(0, 1);
		
		if ($sCurrency == 'GBP')
			$sSymbol = '£ ';
		
		else if ($sCurrency == 'EUR')
			$sSymbol = '€ ';
		
		else
			$sSymbol = '$ ';


		$sSQL = "SELECT id, group_id, brand_id, vendor_id, po_id, additional_pos, audit_code, colors, audit_quantity, total_gmts, audit_date, audit_mode, start_time, end_time, commission_type, style_id, ship_qty,
						(SELECT GROUP_CONCAT(DISTINCT(DATE_FORMAT(etd_required, '%d-%b-%Y')) SEPArATOR '\n') FROM tbl_po_colors WHERE po_id=tbl_qa_reports.po_id OR (tbl_qa_reports.additional_pos!='' AND FIND_IN_SET(po_id, tbl_qa_reports.additional_pos))) AS _GacDates,
						(SELECT style FROM tbl_styles WHERE id=tbl_qa_reports.style_id) AS _Style,
						(SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor
				 FROM tbl_qa_reports
				 $sConditions
				 ORDER BY vendor_id, id";
		$objDb->query($sSQL);

		$iCount         = $objDb->getCount( );
		$fTotal         = 0;
		$fFobTotal      = 0;
		$fQuantityTotal = 0;



		@require_once($sBaseDir."requires/html2pdf/html2pdf.class.php");

		$objHtml = new HTML2PDF("L", "A4", "en");

		$objHtml->setDefaultFont('Arial');

		if ($BilledFrom == "Triple Tree")
		{			
			$sHtml  = '<table border="0" cellspacing="0" cellpadding="0" width="100%">
			 <tr>
			   <td width="250">
				 <div style="border:solid 1px #444444; padding:8px;">
				   <b style="font-size:15px;">3 Tree Solutions</b><br />

				   <div style="padding-top:5px; font-size:12px;">
					7.5 Km, Raiwind Road<br />
					Lahore, 54000<br />
					Pakistan<br />
				   </div>
				 </div>
			   </td>

			   <td width="800" align="right"><img src="../images/triple-tree.jpg" height="110" alt="" title="" /></td>
			 </tr>
		   </table>';
			
			$sHtml .= '<br /><br /><h3>Audit Details</h3>';

			$sHtml .= '<table border="1" bordercolor="#aaaaaa" cellspacing="0" cellpadding="5" width="100%">
			 <tr>
			   <td bgcolor="#eeeeee" width="70">Audit Code</td>
			   <td bgcolor="#eeeeee" width="80">Date</td>
			   <td bgcolor="#eeeeee" width="120">Auditor</td>
			   <td bgcolor="#eeeeee" width="70">PO #</td>
			   <td bgcolor="#eeeeee" width="90">Style #</td>
			   <td bgcolor="#eeeeee" width="230">Colors</td>
			   <td bgcolor="#eeeeee" width="60">Quantity</td>
			   <td bgcolor="#eeeeee" width="80">Audit Type</td>
			   <td bgcolor="#eeeeee" width="80">FOB Value</td>
			   <td bgcolor="#eeeeee" width="80">Commission</td>
			 </tr>';
		}
		
		else
		{
			$sHtml  = '<table border="0" cellspacing="0" cellpadding="0" width="100%">
			 <tr>
			   <td width="250">
				 <div style="border:solid 1px #444444; padding:8px;">
				   <b style="font-size:15px;">NABILA MATRIX DMCC</b><br />

				   <div style="padding-top:5px; font-size:12px;">
					 UNIT NO. 3O-01-617, FLOOR NO. 1<br />
					 BUILDING NO. 3, PLOT NO. 550-554<br />
					 J&G, DMCC, DUBAI, UAE<br />
				   </div>
				 </div>
			   </td>

			   <td width="800" align="right"><img src="../images/nabila-matrix.png" height="110" alt="" title="" /></td>
			 </tr>
		   </table>';
			
			$sHtml .= '<br /><br /><h3>Audit Details</h3>';


			if (@strpos($BilledTo, "MGF Sourcing Far East Limited") !== FALSE)
			{
/*
				$sHtml .= '<table border="1" bordercolor="#aaaaaa" cellspacing="0" cellpadding="5" width="100%">
							 <tr>
							   <td bgcolor="#eeeeee" width="70">Audit Code</td>
							   <td bgcolor="#eeeeee" width="80">Audit Date</td>
							   <td bgcolor="#eeeeee" width="80">GAC Date</td>							   
							   <td bgcolor="#eeeeee" width="150">Auditor</td>
							   <td bgcolor="#eeeeee" width="100">VPO</td>
							   <td bgcolor="#eeeeee" width="90">Style #</td>
							   <td bgcolor="#eeeeee" width="200">Colors</td>
							   <td bgcolor="#eeeeee" width="65">Quantity</td>
							   <td bgcolor="#eeeeee" width="80">Audit Type</td>
							   <td bgcolor="#eeeeee" width="80">Commission</td>
							 </tr>';
*/
				$sHtml .= '<table border="1" bordercolor="#aaaaaa" cellspacing="0" cellpadding="5" width="100%">
							 <tr>
							   <td bgcolor="#eeeeee" width="70">Audit Code</td>
							   <td bgcolor="#eeeeee" width="80">Audit Date</td>
							   <td bgcolor="#eeeeee" width="80">GAC Date</td>							   
							   <td bgcolor="#eeeeee" width="165">Auditor</td>
							   <td bgcolor="#eeeeee" width="110">VPO</td>
							   <td bgcolor="#eeeeee" width="110">Style #</td>
							   <td bgcolor="#eeeeee" width="300">Colors</td>
							   <td bgcolor="#eeeeee" width="80">Audit Type</td>
							 </tr>';
			}
			
			else
			{
				$sHtml .= '<table border="1" bordercolor="#aaaaaa" cellspacing="0" cellpadding="5" width="100%">
							 <tr>
							   <td bgcolor="#eeeeee" width="70">Audit Code</td>
							   <td bgcolor="#eeeeee" width="80">Date</td>
							   <td bgcolor="#eeeeee" width="140">Auditor</td>
							   <td bgcolor="#eeeeee" width="90">PO #</td>
							   <td bgcolor="#eeeeee" width="80">Style #</td>
							   <td bgcolor="#eeeeee" width="180">Colors</td>
							   <td bgcolor="#eeeeee" width="65">Quantity</td>
							   <td bgcolor="#eeeeee" width="50">Man Hours</td>
							   <td bgcolor="#eeeeee" width="80">Audit Type</td>
							   <td bgcolor="#eeeeee" width="80">FOB Value</td>
							   <td bgcolor="#eeeeee" width="80">Commission</td>
							 </tr>';
			}
		}

		
		$iPos              = array( );
		$iLastVendor       = 0;
		$iVendorQty        = 0;
		$fVendorFobValue   = 0;
		$fVendorCommission = 0;

		
		for ($i = 0; $i < $iCount; $i ++)
		{
			$iAudit          = $objDb->getField($i, "id");
			$sAuditCode      = $objDb->getField($i, "audit_code");
			$sAuditDate      = $objDb->getField($i, "audit_date");
			$sGacDates       = $objDb->getField($i, "_GacDates");
			$sAuditor        = $objDb->getField($i, "_Auditor");
			$sStartTime      = $objDb->getField($i, "start_time");
			$sEndTime        = $objDb->getField($i, "end_time");
			$iAuditMode      = $objDb->getField($i, "audit_mode");
			$iGroup          = $objDb->getField($i, "group_id");
			$iPo             = $objDb->getField($i, "po_id");
			$iBrand          = $objDb->getField($i, "brand_id");
			$iVendor         = $objDb->getField($i, "vendor_id");
			$sAdditionalPos  = $objDb->getField($i, "additional_pos");
			$iStyle          = $objDb->getField($i, "style_id");
			$sStyle          = $objDb->getField($i, "_Style");
			$sColors 	     = $objDb->getField($i, "colors");
			$iShipmentQty    = $objDb->getField($i, "ship_qty");			
			$iOfferedQty     = $objDb->getField($i, "audit_quantity");
			$iInspectedQty   = $objDb->getField($i, "total_gmts");
			$sCommissionType = $objDb->getField($i, "commission_type");
			
			

			
			$iAdditionalPos = (($sAdditionalPos == "") ? array( ) : @explode(",", $sAdditionalPos));
			$iAuditPos      = array( );
			$iTempPos       = array( );
			$iTempPos[]     = $iPo;

			foreach ($iAdditionalPos as $iPo)
				$iTempPos[] = $iPo;


			foreach ($iTempPos as $iPo)
			{
				if (@strpos($BilledTo, "MGF Sourcing Far East Limited") !== FALSE && getDbValue("status", "tbl_po", "id='$iPo'") == "C")
					continue;
			
				if ($Duplicate == "N" && @in_array("{$iPo}|-|{$iStyle}", $iPos))
					continue;

				$iAuditPos[] = "{$iPo}|-|{$iStyle}";
				$iPos[]      = "{$iPo}|-|{$iStyle}";
			}

			if (count($iAuditPos) == 0)
				continue;

			
			if ($iLastVendor == 0)
			{
				$sHtml .= '<tr>
							 <td colspan="10" width="980"><b><big>'.getDbValue("vendor", "tbl_vendors", "id='$iVendor'").'</big></b></td>
						 </tr>';				
			}


			
			$bVendor = false;
			
			foreach ($iAuditPos as $sPoStyle)
			{
				@list($iPo, $iStyle) = @explode("|-|", $sPoStyle);
				
				
				$sPo            = getDbValue("CONCAT(order_no, ' ', order_status)", "tbl_po", "id='$iPo'");
				$iShipQty       = getDbValue("SUM(quantity)", "tbl_pre_shipment_quantities", "po_id='$iPo'");
				$iAuditors      = 1;
				$sColorQtyPrice = "";	
                                
				if ($BilledTo == "TIMEZONE ESCAPE CLOTHING GMBH Flintsbacher Str. 1, 83098 Brannenburg, GERMANY")
				{
					$iShipQty  = getDbValue("SUM(psq.quantity)", "tbl_pre_shipment_quantities psq, tbl_po_colors pc", "psq.po_id=pc.po_id AND psq.po_id='$iPo' AND psq.color_id=pc.id AND pc.style_id='$iStyle' AND FIND_IN_SET(pc.color, '$sColors')");

					
					$iColors   = @explode(",", $sColors);
					$iOrderQty = 0;
					$fFobValue = 0;
					
					foreach ($iColors as $sColor)
					{
						$fPrice = getDbValue("price", "tbl_po_colors", "color LIKE '$sColor' AND po_id='$iPo' AND style_id='$iStyle'");
						
						if ($Quantity == "O")
							$iQuantity = getDbValue("order_qty", "tbl_po_colors", "color LIKE '$sColor' AND po_id='$iPo' AND style_id='$iStyle'");
						
						else
							$iQuantity = getDbValue("SUM(psq.quantity)", "tbl_pre_shipment_quantities psq, tbl_po_colors pc", "psq.po_id=pc.po_id AND psq.po_id='$iPo' AND psq.color_id=pc.id AND pc.style_id='$iStyle' AND pc.color LIKE '$sColor'");

						$fFobValue += ($iQuantity * $fPrice);
						$iOrderQty += $iQuantity;

						if ($iQuantity > 0)
							$sColorQtyPrice .= ($sColor." - <span style='font-size:9px;'>(".$iQuantity." pcs"." @ ".$fPrice.")</span> ,");

					}
				}
					
				else if ($BilledTo == "Social Fashion Company GmbH, Thebaerstr.17, 50823 Cologne, GDR" || @strpos($BilledTo, "CWS-Boco Supply Chain Management GmbH") !== FALSE)
				{
					$iColors         = @explode(",", $sColors);
                    $sCommissionType = "V";
					$iOrderQty       = 0;
					$fFobValue       = 0;

					foreach ($iColors as $sColor)
					{
						$fPrice    = getDbValue("price", "tbl_po_colors", "color LIKE '$sColor' AND po_id='$iPo' AND style_id='$iStyle'");
						
						if (@strpos($BilledTo, "CWS-Boco Supply Chain Management GmbH") !== FALSE)
							$iQuantity = $iShipmentQty;

						else
							$iQuantity = getDbValue("quantity", "tbl_qa_color_quantities", "color LIKE '$sColor' AND audit_id='$iAudit'");

						$fFobValue += ($iQuantity * $fPrice);
						$iOrderQty += $iQuantity;
						
						if ($iQuantity > 0)
							$sColorQtyPrice .= $sColor." - <span style='font-size:9px;'>(".$iQuantity." pcs"." @ ".$fPrice.")</span> ,";
					}
					
					$iShipQty = $iOrderQty;
				}
                                
				else
				{
					$iOrderQty = getDbValue("quantity", "tbl_po", "id='$iPo'");

					if ($Quantity == "O")
						$fFobValue = getDbValue("SUM(price * order_qty)", "tbl_po_colors", "po_id='$iPo'");

					else
						$fFobValue = ($iShipQty * getDbValue("MAX(price)", "tbl_po_colors", "po_id='$iPo'"));                                                                                
				}
					

				if ($iGroup > 0)
				{
					$sUsers = getDbValue("users", "tbl_auditor_groups", "id='$iGroup'");
					$iUsers = @explode(",", $sUsers);

					$iAuditors = count($iUsers);
				}


				if (strtotime($sEndTime) > strtotime($sStartTime))
				{
					$iAuditTime     = (strtotime($sEndTime) - strtotime($sStartTime));
					$sStartDateTime = "{$sAuditDate} {$sStartTime}";
					$sEndDateTime   = "{$sAuditDate} {$sEndTime}";
				}

				else
				{
					$iAuditTime  = (strtotime($sStartTime) - strtotime($sEndTime));
					$iAuditTime += (60 * 60 * 24);

					$sNextDay       = date("Y-m-d", (strtotime($sAuditDate) + (60 * 60 * 24)));
					$sStartDateTime = "{$sAuditDate} {$sStartTime}";
					$sEndDateTime   = "{$sNextDay} {$sEndTime}";
				}


				$fHours = @round(($iAuditTime / (60 * 60)), 2);

				if ($sCommissionType == "V")
					$fCommission = (($fFobValue / 100) * $Rate);

				else
					$fCommission = (($fHours * 10) * $iAuditors);
				
				
				if ($iLastVendor > 0 && $iLastVendor != $iVendor && $bVendor == false)
				{
					if ($iVendorQty > 0 || $fVendorFobValue > 0 || $fVendorCommission > 0)
					{
						if ($BilledFrom == "Triple Tree")
						{
							$sHtml .= '<tr>
										 <td colspan="6"></td>
										 <td width="60">'.formatNumber($iVendorQty, false).'</td>
										 <td width="80"></td>
										 <td width="80">'.$sSymbol.formatNumber($fVendorFobValue).'</td>
										 <td width="80">'.$sSymbol.formatNumber($fVendorCommission).'</td>
									 </tr>';
						}
						
						else
						{
							if (@strpos($BilledTo, "MGF Sourcing Far East Limited") !== FALSE)
							{
/*
								$sHtml .= '<tr>
											 <td colspan="7"></td>
											 <td width="60">'.formatNumber($iVendorQty, false).'</td>
											 <td width="80">'.$sSymbol.formatNumber($fVendorFobValue).'</td>
											 <td width="80">'.$sSymbol.formatNumber($fVendorCommission).'</td>
										 </tr>';
*/
								$sHtml .= '<tr>
											 <td colspan="8" align="right"><b>Shipped Quantity: '.formatNumber($iVendorQty, false).'<br />Total FOB = '.$sSymbol.formatNumber($fVendorFobValue).'<br />Commission @ '.$Rate.'% = '.$sSymbol.formatNumber($fVendorCommission).'</b></td>
										   </tr>';
							}
							
							else
							{
								$sHtml .= '<tr>
											 <td colspan="6"></td>
											 <td width="65">'.formatNumber($iVendorQty, false).'</td>
											<td width="50"></td>
											<td width="80"></td>
											 <td width="80">'.$sSymbol.formatNumber($fVendorFobValue).'</td>
											 <td width="80">'.$sSymbol.formatNumber($fVendorCommission).'</td>
										 </tr>';
							}
						}
					}

					
					$sHtml .= '<tr>
								 <td colspan="10" width="980"><b><big>'.getDbValue("vendor", "tbl_vendors", "id='$iVendor'").'</big></b></td>
							 </tr>';
							 
							 
					$fVendorFobValue   = 0;
					$iVendorQty        = 0;
					$fVendorCommission = 0;
					$bVendor           = true;
				}


				if ($BilledFrom == "Triple Tree")
				{
					$sHtml .= '<tr>
								 <td width="70">'.$sAuditCode.'</td>
								 <td width="80">'.formatDate($sAuditDate).'</td>
								 <td width="120">'.$sAuditor.(($iAuditors > 1) ? "<br /><small>No of Auditors: {$iAuditors}</small>" : "").'</td>
								 <td width="70">'.$sPo.'</td>
								 <td width="110">'.$sStyle.'</td>
								 <td width="230">'.str_replace(",", "<br />", $sColorQtyPrice).'</td>
								 <td width="60">'.formatNumber((($Quantity == "O") ? $iOrderQty : $iShipQty), false).'</td>
								 <td width="80">'.(($sCommissionType == "V") ? "Commission on FOB" : "100% Inspection").'</td>
								 <td width="80">'.$sSymbol.formatNumber($fFobValue).'</td>
								 <td width="80">'.$sSymbol.formatNumber($fCommission).'</td>
							 </tr>';
				}
				
				else
				{
					if (@strpos($BilledTo, "MGF Sourcing Far East Limited") !== FALSE)
					{					
/*
						$sHtml .= '<tr>
									 <td width="70">'.$sAuditCode.'</td>
									 <td width="80">'.formatDate($sAuditDate).'</td>
									 <td width="80">'.$sGacDates.'</td>									 
									 <td width="150">'.$sAuditor.(($iAuditors > 1) ? "<br /><small>No of Auditors: {$iAuditors}</small>" : "").'</td>
									 <td width="100">'.$sPo.'</td>
									 <td width="90">'.$sStyle.'</td>
									 <td width="200">'.str_replace(",", "<br />", $sColors).'</td>
									 <td width="65">'.formatNumber((($Quantity == "O") ? $iOrderQty : $iShipQty), false).'</td>
									 <td width="80">'.(($sCommissionType == "V") ? "Commission on FOB" : "100% Inspection").'</td>
									 <td width="80">'.$sSymbol.formatNumber($fCommission).'</td>
								 </tr>';
*/
						$sHtml .= '<tr>
									 <td width="70">'.$sAuditCode.'</td>
									 <td width="80">'.formatDate($sAuditDate).'</td>
									 <td width="80">'.$sGacDates.'</td>									 
									 <td width="165">'.$sAuditor.(($iAuditors > 1) ? "<br /><small>No of Auditors: {$iAuditors}</small>" : "").'</td>
									 <td width="110">'.$sPo.'</td>
									 <td width="110">'.$sStyle.'</td>
									 <td width="300">'.str_replace(",", "<br />", $sColors).'</td>
									 <td width="80">'.(($sCommissionType == "V") ? "Commission on FOB" : "100% Inspection").'</td>
								 </tr>';
					}
					
					else
					{
						$sHtml .= '<tr>
									 <td width="70">'.$sAuditCode.'</td>
									 <td width="80">'.formatDate($sAuditDate).'</td>
									 <td width="140">'.$sAuditor.(($iAuditors > 1) ? "<br /><small>No of Auditors: {$iAuditors}</small>" : "").'</td>
									 <td width="90">'.$sPo.'</td>
									 <td width="80">'.$sStyle.'</td>
									 <td width="180">'.str_replace(",", "<br />", $sColors).'</td>
									 <td width="65">'.formatNumber((($Quantity == "O") ? $iOrderQty : $iShipQty), false).'</td>
									 <td width="50">'.($fHours * $iAuditors).'</td>
									 <td width="80">'.(($sCommissionType == "V") ? "Commission on FOB" : "100% Inspection").'</td>
									 <td width="80">'.$sSymbol.formatNumber($fFobValue).'</td>
									 <td width="80">'.$sSymbol.formatNumber($fCommission).'</td>
								 </tr>';
					}
				}
				

				$fFobTotal      += $fFobValue;
				$fQuantityTotal += (($Quantity == "O") ? $iOrderQty : $iShipQty);
				$fTotal         += $fCommission;
				
				
				$fVendorFobValue   += $fFobValue;
				$iVendorQty        += (($Quantity == "O") ? $iOrderQty : $iShipQty);
				$fVendorCommission += $fCommission;
			}
		
		
			$iLastVendor = $iVendor;
		}
		

		
		if ($iVendorQty > 0 || $fVendorFobValue > 0 || $fVendorCommission > 0)
		{
			if ($BilledFrom == "Triple Tree")
			{
				$sHtml .= '<tr>
							 <td colspan="6"></td>
							 <td width="60">'.formatNumber($iVendorQty, false).'</td>
							 <td width="80"></td>
							 <td width="80">'.$sSymbol.formatNumber($fVendorFobValue).'</td>
							 <td width="80">'.$sSymbol.formatNumber($fVendorCommission).'</td>
						 </tr>';
			}
			
			else
			{
				if (@strpos($BilledTo, "MGF Sourcing Far East Limited") !== FALSE)
				{
/*					
					$sHtml .= '<tr>
								 <td colspan="7"></td>
								 <td width="60">'.formatNumber($iVendorQty, false).'</td>
								 <td width="80"></td>
								 <td width="80">'.$sSymbol.formatNumber($fVendorCommission).'</td>
							 </tr>';
*/
					$sHtml .= '<tr>
								 <td colspan="8" align="right"><b>Shipped Quantity: '.formatNumber($iVendorQty, false).'<br />Total FOB = '.$sSymbol.formatNumber($fVendorFobValue).'<br />Commission @ '.$Rate.'% = '.$sSymbol.formatNumber($fVendorCommission).'</b></td>
							   </tr>';
				}
				
				else
				{
					$sHtml .= '<tr>
								 <td colspan="6"></td>
								 <td width="65">'.formatNumber($iVendorQty, false).'</td>
								<td width="50"></td>
								<td width="80"></td>
								 <td width="80">'.$sSymbol.formatNumber($fVendorFobValue).'</td>
								 <td width="80">'.$sSymbol.formatNumber($fVendorCommission).'</td>
							 </tr>';
				}
			}
		}


		if($BilledFrom == "Triple Tree")
		{			
			$sHtml .= '<tr>
			 <td colspan="6" align="right"><b>Total</b></td>
			 <td><b>'.formatNumber($fQuantityTotal).'</b></td>
			 <td>&nbsp;</td>
			 <td><b>'.$sSymbol.formatNumber($fFobTotal).'</b></td>
			 <td><b>'.$sSymbol.formatNumber($fTotal).'</b></td>
		   </tr>';
		}
		
		else
		{
			if (@strpos($BilledTo, "MGF Sourcing Far East Limited") !== FALSE)
			{
				$sHtml .= '<tr>
							 <td colspan="8" align="right"><b>Total Shipped Quantity: '.formatNumber($fQuantityTotal, false).'<br />Total FOB = '.$sSymbol.formatNumber($fFobTotal).'<br />Commission @ '.$Rate.'% = '.$sSymbol.formatNumber($fTotal).'</b></td>
						   </tr>';
/*
				$sHtml .= '<tr>
							 <td colspan="7" align="right"><b>Total</b></td>
							 <td><b>'.formatNumber($fQuantityTotal).'</b></td>
							 <td>&nbsp;</td>
							 <td><b>'.$sSymbol.formatNumber($fTotal).'</b></td>
						   </tr>';
*/
			}
			
			else
			{
				$sHtml .= '<tr>
							 <td colspan="6" align="right"><b>Total</b></td>
							 <td><b>'.formatNumber($fQuantityTotal).'</b></td>
							 <td colspan="2">&nbsp;</td>
							 <td><b>'.$sSymbol.formatNumber($fFobTotal).'</b></td>
							 <td><b>'.$sSymbol.formatNumber($fTotal).'</b></td>
						   </tr>';                    
			}				
		}

		$sHtml .= '</table>';

		
		
		$Terms = str_replace("[CommissionRate]", formatNumber($Rate), $Terms);

		$sHtml .= '<br /><br /><br />
		           <div style="border-top:solid 1px #444444; border-bottom:solid 1px #444444; padding:10px 0px 10px 0px;">
		           <b>Note:</b><br />
		           <small>'.nl2br($Terms).'</small>
		           </div>';

		$objHtml->writeHTML($sHtml, false);


		$sPdfFile = ($sBaseDir.TEMP_DIR."{$_SESSION['UserId']}-TT-Details.pdf");

		@unlink($sPdfFile);

		$objHtml->Output($sPdfFile, "F");


		redirect("export-invoice.php?Total={$fTotal}&Discount={$Discount}&Signatures={$Signatures}&Currency={$sCurrency}&BilledFrom={$BilledFrom}");
	}


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>