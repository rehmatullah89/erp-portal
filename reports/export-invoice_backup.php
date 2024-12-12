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

	$Total    = IO::strValue("Total");
	$Discount = IO::floatValue("Discount");
	$Rate     = IO::floatValue("Rate");
	$Brand    = IO::getArray("Brand");
	$Vendor   = IO::getArray("Vendor");
	$Region   = IO::intValue("Region");
	
	
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

		$objPdf->Text(162, 136.8, "$ ".formatNumber($Total));


		$fDiscount = 0;

		if ($Discount > 0)
			$fDiscount = (($Total / 100) * $Discount);

		$fTotal = ($Total - $fDiscount);


		$objPdf->SetFont('Arial', '', 11);

		if ($Discount > 0)
		{
			$objPdf->Text(127, 170, ("Discount (".formatNumber($Discount)."%)"));
			$objPdf->Text(162, 170, ("$ ".formatNumber($fDiscount)));
		}

		$objPdf->Text(162, 176, ("$ ".formatNumber($fTotal)));


		$objPdf->SetFont('Arial', '', 10);

		$objPdf->SetXY(7, 180);
		$objPdf->MultiCell(195, 6, strtoupper(currencyInWords(formatNumber($fTotal, true, 2, false))), 0);


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

		$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/tt-invoice.pdf");
		$iTemplateId = $objPdf->importPage(1, '/MediaBox');

		$objPdf->addPage("P", "A4", "pt");
		$objPdf->useTemplate($iTemplateId, 0, 0);


		$objPdf->SetFont('Arial', '', 10);
		$objPdf->SetTextColor(50, 50, 50);


		$objPdf->Text(170, 73.5, $InvoiceNo);
		$objPdf->Text(170, 82, date("d-M-Y", strtotime($InvoiceDate)));
		$objPdf->Text(170, 90, date("d-M-Y", strtotime($DueDate)));

		$objPdf->SetXY(38, 69.5);
		$objPdf->MultiCell(108, 6, $BilledTo, 0);

		$objPdf->SetXY(38, 98);
		$objPdf->MultiCell(108, 6, $PaymentTerms, 0);

		$objPdf->Text(9, 109, "Rate: {$Rate}%");
		$objPdf->Text(9, 117, "Region: ".(($Region == '') ? '-' : getDbValue("country", "tbl_countries", "id='$Region'")));

		$objPdf->SetXY(50, 107);
		$objPdf->MultiCell(145, 3.5, ("Brand".((count($Brands) != 1) ? "s" : "").":  {$sBrands}"), 0, "L");

		$objPdf->SetXY(50, 115);
		$objPdf->MultiCell(145, 3.5, ("Vendor".((count($Vendors) != 1) ? "s" : "").":  {$sVendors}"), 0, "L");
                
				
		$objPdf->SetFont('Arial', '', 11);

		$objPdf->SetXY(9, 132);
		$objPdf->MultiCell(145, 7, $Description, 0, "L");

/*
		$objPdf->SetFont('Arial', '', 10);

		$objPdf->SetXY(99, 255);
		$objPdf->Cell(100, 6, "{$Name} ({$Designation})", 0, 2, "R");
*/
		if ($NabilaMatrix == "Y")
			$objPdf->Image(($sBaseDir."images/nabila-matrix-stamp.jpg"), 145, 228, 50);
			
		else if ($Signatures != "")
			$objPdf->Image(($sBaseDir.TEMP_DIR.$Signatures), 145, 228, 50);


		$sPdfFile = ($sBaseDir.TEMP_DIR."{$_SESSION['UserId']}-TT-Invoice.pdf");

		$objPdf->Output($sPdfFile, 'F');





		$sReportTypes = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
		$sConditions  = " WHERE audit_result!='' AND audit_stage='F' AND FIND_IN_SET(report_id, '$sReportTypes') AND NOT FIND_IN_SET(report_id, '$sQmipReports') ";

		if (count($Vendor) > 0)
			$sConditions .= (" AND FIND_IN_SET(vendor_id, '".@implode(",", $Vendor)."') ");

		else
			$sConditions .= " AND FIND_IN_SET(vendor_id, '{$_SESSION['Vendors']}') ";

		if ($Region > 0)
			$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

		if ($FromDate != "" && $ToDate != "")
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



		$sSQL = ("SELECT id FROM tbl_po WHERE FIND_IN_SET(brand_id, '".@implode(",", $Brand)."')");

		if (count($Vendor) > 0)
			$sSQL .= (" AND FIND_IN_SET(vendor_id, '".@implode(",", $Vendor)."') ");

		else
			$sSQL .= " AND FIND_IN_SET(vendor_id, '{$_SESSION['Vendors']}') ";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND po_id IN ($sPos) ";



		$sSQL = "SELECT group_id, brand_id, po_id, additional_pos, audit_code, colors, total_gmts, audit_date, audit_mode, start_time, end_time, commission_type,
						(SELECT style FROM tbl_styles WHERE id=tbl_qa_reports.style_id) AS _Style,
						(SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor
				 FROM tbl_qa_reports
				 $sConditions
				 ORDER BY id";
		$objDb->query($sSQL);

		$iCount         = $objDb->getCount( );
		$fTotal         = 0;
		$fFobTotal      = 0;
		$fQuantityTotal = 0;



		@require_once($sBaseDir."requires/html2pdf/html2pdf.class.php");

		$objHtml = new HTML2PDF("L", "A4", "en");

		$objHtml->setDefaultFont('Arial');


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


		$iPos = array( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sAuditCode      = $objDb->getField($i, "audit_code");
			$sAuditDate      = $objDb->getField($i, "audit_date");
			$sAuditor        = $objDb->getField($i, "_Auditor");
			$sStartTime      = $objDb->getField($i, "start_time");
			$sEndTime        = $objDb->getField($i, "end_time");
			$iAuditMode      = $objDb->getField($i, "audit_mode");
			$iGroup          = $objDb->getField($i, "group_id");
			$iPo             = $objDb->getField($i, "po_id");
			$iBrand          = $objDb->getField($i, "brand_id");
			$sAdditionalPos  = $objDb->getField($i, "additional_pos");
			$sStyle          = $objDb->getField($i, "_Style");
			$sColors 	     = $objDb->getField($i, "colors");
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
				if ($Duplicate == "N" && @in_array($iPo, $iPos))
					continue;

				$iAuditPos[] = $iPo;
				$iPos[]      = $iPo;
			}

			if (count($iAuditPos) == 0)
				continue;


			foreach ($iAuditPos as $iPo)
			{
				$sPo       = getDbValue("CONCAT(order_no, ' ', order_status)", "tbl_po", "id='$iPo'");
				$iOrderQty = getDbValue("quantity", "tbl_po", "id='$iPo'");
				$iShipQty  = getDbValue("SUM(quantity)", "tbl_pre_shipment_quantities", "po_id='$iPo'");
				$iAuditors = 1;

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

				if ($Quantity == "O")
					$fFobValue = getDbValue("SUM(price * order_qty)", "tbl_po_colors", "po_id='$iPo'");

				else
					$fFobValue = ($iShipQty * getDbValue("MAX(price)", "tbl_po_colors", "po_id='$iPo'"));


				if ($sCommissionType == "V")
					$fCommission = (($fFobValue / 100) * $Rate);

				else
					$fCommission = (($fHours * 10) * $iAuditors);


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
							 <td width="80">$'.formatNumber($fFobValue).'</td>
							 <td width="80">$'.formatNumber($fCommission).'</td>
						   </tr>';


				$fFobTotal      += $fFobValue;
				$fQuantityTotal += (($Quantity == "O") ? $iOrderQty : $iShipQty);
				$fTotal         += $fCommission;
			}
		}


		$sHtml .= '<tr>
					 <td colspan="6" align="right">Total</td>
                                         <td>'.formatNumber($fQuantityTotal).'</td>
                                         <td colspan="2">&nbsp;</td>
                                         <td>$'.formatNumber($fFobTotal).'</td>
					 <td>$'.formatNumber($fTotal).'</td>
				   </tr>';

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


		redirect("export-invoice.php?Total={$fTotal}&Discount={$Discount}&Signatures={$Signatures}");
	}


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>