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

	$sConditions  = "";

	if ($Category > 0)
	{
		$sSQL = "SELECT id FROM tbl_vendors WHERE category_id='$Category' AND parent_id='0' AND sourcing='Y'";
		$objDb->query($sSQL);

		$iCount   = $objDb->getCount( );
		$sVendors = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sVendors .= (",".$objDb->getField($i, 0));

		if ($sVendors != "")
			$sVendors = substr($sVendors, 1);

		$sConditions .= " AND po.vendor_id IN ($sVendors) ";
	}


	if (count($Season) == 0)
	{
		if (count($Brand) > 0)
			$sConditions .= (" AND po.brand_id IN (".@implode(",", $Brand).") ");

		else
			$sConditions .= " AND po.brand_id IN ({$_SESSION['Brands']})";
	}

	else
	{
		if (count($Brand) > 0)
			$sSQL .= ("SELECT id FROM tbl_styles WHERE sub_brand_id IN (".@implode(",", $Brand).") ");

		else
			$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']})";

		if (count($Season) > 0)
			$sSQL .= (" AND sub_season_id IN (".@implode(",", $Season).") ");


		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		$sStyles = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sStyles .= (",".$objDb->getField($i, 0));

		if ($sStyles != "")
			$sStyles = substr($sStyles, 1);


		$sSQL = "SELECT po_id FROM tbl_po_colors WHERE style_id IN ($sStyles)";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		$sPos = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND po.id IN ($sPos)";
	}


	if (count($Vendor) > 0)
		$sConditions .= (" AND po.vendor_id IN (".@implode(",", $Vendor).")");

	else
		$sConditions .= " AND po.vendor_id IN ({$_SESSION['Vendors']})";


	if ($Region != "")
	{
		$sSQL = "SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		$sVendors = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sVendors .= (",".$objDb->getField($i, 0));

		if ($sVendors != "")
			$sVendors = substr($sVendors, 1);

		$sConditions .= " AND po.vendor_id IN ($sVendors)";
	}


	$sConditions2 = $sConditions;
	$sConditions  = substr(str_replace("po.", "", $sConditions), 5);

	if ($FromDate != "" && $ToDate != "")
		$sConditions2 .= " AND (psd.shipping_date BETWEEN '$FromDate' AND '$ToDate') ";


	$sCustomersSql = "";

	if (count($Customer) > 0)
		$sCustomersSql = (" AND customer IN ('".@implode("','", $Customer)."') ");



	$iParentBrand      = getDbValue("parent_id", "tbl_brands", "id='{$Brand[0]}'");
	$sDestinationsList = getList("tbl_destinations", "id", "destination", "brand_id='$iParentBrand'");






	$sHeadingStyle = array('font' => array('bold' => true, 'size' => 11),
						   'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
						   'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											  'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											  'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											  'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
						   'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'DDDDDD')) );


	$sBorderStyle = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
						  'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											 'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											 'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));




	$objPHPExcel->getActiveSheet()->setCellValue("A1", "Name of Agent: MATRIX Sourcing");
	$objPHPExcel->getActiveSheet()->mergeCells("A1:S1");
	$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	$objPHPExcel->getActiveSheet()->setCellValue("A2", ("Month/Year: ".((substr($FromDate, 0, 7) == substr($ToDate, 0, 7)) ? formatDate($FromDate, "M-Y") : (formatDate($FromDate, "M-Y").' - '.formatDate($ToDate, "M-Y")) )));
	$objPHPExcel->getActiveSheet()->mergeCells("A2:S2");
	$objPHPExcel->getActiveSheet()->getStyle("A2")->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle("A2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	$objPHPExcel->getActiveSheet()->setCellValue("A4", "Buyer: TOWN AND COUNTRY LIVING");
	$objPHPExcel->getActiveSheet()->mergeCells("A4:S4");
	$objPHPExcel->getActiveSheet()->getStyle("A4")->getFont()->setSize(17);
	$objPHPExcel->getActiveSheet()->getStyle("A4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


	$iRow    = 6;
	$iColumn = 0;

	$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", "Mth");
	$objPHPExcel->getActiveSheet()->setCellValue("B{$iRow}", "bo");
	$objPHPExcel->getActiveSheet()->setCellValue("C{$iRow}", "Buyer");
	$objPHPExcel->getActiveSheet()->setCellValue("D{$iRow}", "DivCode");
	$objPHPExcel->getActiveSheet()->setCellValue("E{$iRow}", "LIC");
	$objPHPExcel->getActiveSheet()->setCellValue("F{$iRow}", "IC No");
	$objPHPExcel->getActiveSheet()->setCellValue("G{$iRow}", "FtyCode");
	$objPHPExcel->getActiveSheet()->setCellValue("H{$iRow}", "ORG");
	$objPHPExcel->getActiveSheet()->setCellValue("I{$iRow}", "LC No");
	$objPHPExcel->getActiveSheet()->setCellValue("J{$iRow}", "INV");
	$objPHPExcel->getActiveSheet()->setCellValue("K{$iRow}", "Po No.");
	$objPHPExcel->getActiveSheet()->setCellValue("L{$iRow}", "Style No");
	$objPHPExcel->getActiveSheet()->setCellValue("M{$iRow}", "Color");
	$objPHPExcel->getActiveSheet()->setCellValue("N{$iRow}", "Sea");
	$objPHPExcel->getActiveSheet()->setCellValue("O{$iRow}", "Qty");
	$objPHPExcel->getActiveSheet()->setCellValue("P{$iRow}", "FobAmt");
	$objPHPExcel->getActiveSheet()->setCellValue("Q{$iRow}", "ShipDate");
	$objPHPExcel->getActiveSheet()->setCellValue("R{$iRow}", "Rate");
	$objPHPExcel->getActiveSheet()->setCellValue("S{$iRow}", "Cur");

	for ($i = 0; $i < 19; $i ++)
		$objPHPExcel->getActiveSheet()->duplicateStyleArray($sHeadingStyle, (getExcelCol($i + 65).$iRow.":".getExcelCol($i + 65).$iRow));

	$iRow ++;


	$sCustomers  = getList("tbl_po", "DISTINCT(customer)", "customer", "$sConditions AND currency='$Currency' $sCustomersSql");
	$sGrandTotal = array( );
	$sSubTotals  = array( );

	foreach ($sCustomers as $sCustomer)
	{
		$sSQL = "SELECT DISTINCT(psd.commission)
		         FROM tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_styles s
		         WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.currency='$Currency' AND po.customer='$sCustomer' $sConditions2";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount == 0)
			continue;


		$fCommissions = array( );

		for ($i = 0; $i < $iCount; $i ++)
			$fCommissions[] = $objDb->getField($i, 'commission');


		foreach ($fCommissions as $fCommission)
		{
			$sSQL = "SELECT po.id, po.order_no, po.vendor_id, po.call_no,
							pc.color, pc.price, pc.etd_required, pc.destination_id,
							psd.invoice_no, psd.shipping_date, psd.terms_of_payment, psd.commission_type,
							s.style, s.sub_season_id,
							(SELECT COALESCE(SUM(quantity), 0) FROM tbl_pre_shipment_quantities WHERE po_id=po.id AND color_id=pc.id AND ship_id=psd.id) AS _Quantity
					 FROM tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_styles s
					 WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.currency='$Currency' AND po.customer='$sCustomer' AND psd.commission='$fCommission' $sConditions2
					 GROUP BY po.id, pc.id, psd.id
					 ORDER BY po.id, pc.etd_required";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			if ($iCount == 0)
				continue;



			$sSubTotals = array( );
			$iRow ++;

			$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", ("{$sCustomer} - ".formatNumber($fCommission)."%"));
			$objPHPExcel->getActiveSheet()->mergeCells("A{$iRow}:S{$iRow}");
			$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getFont()->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


			for ($i = 0; $i < $iCount; $i ++)
			{
				$iPo             = $objDb->getField($i, "id");
				$sOrderNo        = $objDb->getField($i, "order_no");
				$iVendor         = $objDb->getField($i, "vendor_id");
				$sScNumber       = $objDb->getField($i, "call_no");
				$sColor          = $objDb->getField($i, "color");
				$fPrice          = $objDb->getField($i, "price");
				$iDestination    = $objDb->getField($i, "destination_id");
				$sEtdRequired    = $objDb->getField($i, "etd_required");
				$sInvoiceNo      = $objDb->getField($i, "invoice_no");
				$sShippingDate   = $objDb->getField($i, 'shipping_date');
				$sTermsOfPayment = $objDb->getField($i, 'terms_of_payment');
				$sCommissionType = $objDb->getField($i, 'commission_type');
				$sStyle          = $objDb->getField($i, 'style');
				$iSeason         = $objDb->getField($i, 'sub_season_id');
				$iQuantity       = $objDb->getField($i, '_Quantity');


				if ($iQuantity == 0)
					continue;


				$fAmount = ($fPrice * $iQuantity);

				if ($sCommissionType == "P")
					$fMatrixCommission = (($fAmount / 100) * $fCommission);

				else
					$fMatrixCommission = (($iQuantity * $fCommission) / 100);


				$iRow ++;

				$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", formatDate($sEtdRequired, "Ym"));
				$objPHPExcel->getActiveSheet()->setCellValue("B{$iRow}", "MATRIX Sourcing");
				$objPHPExcel->getActiveSheet()->setCellValue("C{$iRow}", "Town and Country Living");
				$objPHPExcel->getActiveSheet()->setCellValue("D{$iRow}", $sCustomer);
				$objPHPExcel->getActiveSheet()->setCellValue("E{$iRow}", $sDestinationsList[$iDestination]);
				$objPHPExcel->getActiveSheet()->setCellValue("F{$iRow}", $sScNumber);
				$objPHPExcel->getActiveSheet()->setCellValue("G{$iRow}", $sVendorsList[$iVendor]);
				$objPHPExcel->getActiveSheet()->setCellValue("H{$iRow}", "PAK");
				$objPHPExcel->getActiveSheet()->setCellValue("I{$iRow}", $sTermsOfPayment);
				$objPHPExcel->getActiveSheet()->setCellValue("J{$iRow}", $sInvoiceNo);
				$objPHPExcel->getActiveSheet()->setCellValue("K{$iRow}", $sOrderNo);
				$objPHPExcel->getActiveSheet()->setCellValue("L{$iRow}", $sStyle);
				$objPHPExcel->getActiveSheet()->setCellValue("M{$iRow}", $sColor);
				$objPHPExcel->getActiveSheet()->setCellValue("N{$iRow}", $sSeasonsList[$iSeason]);
				$objPHPExcel->getActiveSheet()->setCellValue("O{$iRow}", formatNumber($iQuantity, false));
				$objPHPExcel->getActiveSheet()->setCellValue("P{$iRow}", formatNumber($fAmount));
				$objPHPExcel->getActiveSheet()->setCellValue("Q{$iRow}", formatDate($sShippingDate, "d/M/Y"));
				$objPHPExcel->getActiveSheet()->setCellValue("R{$iRow}", formatNumber($fPrice));
				$objPHPExcel->getActiveSheet()->setCellValue("S{$iRow}", formatNumber($fMatrixCommission));

				for ($j = 0; $j < 19; $j ++)
					$objPHPExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, (getExcelCol($j + 65).$iRow.":".getExcelCol($j + 65).$iRow));


				$sGrandTotal["Quantity"]   += $iQuantity;
				$sGrandTotal["Amount"]     += $fAmount;
				$sGrandTotal["Price"]      += $fPrice;
				$sGrandTotal["Commission"] += $fMatrixCommission;


				$sSubTotals["Quantity"]   += $iQuantity;
				$sSubTotals["Amount"]     += $fAmount;
				$sSubTotals["Price"]      += $fPrice;
				$sSubTotals["Commission"] += $fMatrixCommission;
			}


			$iRow ++;

			$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", "Sub Total ({$sCustomer})");
			$objPHPExcel->getActiveSheet()->mergeCells("A{$iRow}:N{$iRow}");

			$objPHPExcel->getActiveSheet()->setCellValue("O{$iRow}", formatNumber($sSubTotals["Quantity"], false));
			$objPHPExcel->getActiveSheet()->setCellValue("P{$iRow}", formatNumber($sSubTotals["Amount"]));
			$objPHPExcel->getActiveSheet()->setCellValue("Q{$iRow}", "");
			$objPHPExcel->getActiveSheet()->setCellValue("R{$iRow}", formatNumber($sSubTotals["Price"]));
			$objPHPExcel->getActiveSheet()->setCellValue("S{$iRow}", formatNumber($sSubTotals["Commission"]));

			for ($i = 0; $i < 19; $i ++)
				$objPHPExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, (getExcelCol($i + 65).$iRow.":".getExcelCol($i + 65).$iRow));

			$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}:S{$iRow}")->getFont()->setBold(true);


			$iRow ++;
		}
	}


	$iRow ++;

	$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", "Grand Total");
	$objPHPExcel->getActiveSheet()->mergeCells("A{$iRow}:N{$iRow}");

	$objPHPExcel->getActiveSheet()->setCellValue("O{$iRow}", formatNumber($sGrandTotal["Quantity"], false));
	$objPHPExcel->getActiveSheet()->setCellValue("P{$iRow}", formatNumber($sGrandTotal["Amount"]));
	$objPHPExcel->getActiveSheet()->setCellValue("Q{$iRow}", "");
	$objPHPExcel->getActiveSheet()->setCellValue("R{$iRow}", formatNumber($sGrandTotal["Price"]));
	$objPHPExcel->getActiveSheet()->setCellValue("S{$iRow}", formatNumber($sGrandTotal["Commission"]));

	for ($i = 0; $i < 19; $i ++)
		$objPHPExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, (getExcelCol($i + 65).$iRow.":".getExcelCol($i + 65).$iRow));

	$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}:S{$iRow}")->getFont()->setBold(true);



	for ($i = 0; $i < 19; $i ++)
		$objPHPExcel->getActiveSheet()->getColumnDimension(getExcelCol($i + 65))->setAutoSize(true);


	$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
?>