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

	$objPHPExcel->getActiveSheet()->setCellValue('A12', 'IC #');
	$objPHPExcel->getActiveSheet()->setCellValue('B12', 'Division');
	$objPHPExcel->getActiveSheet()->setCellValue('C12', 'Vendor');
	$objPHPExcel->getActiveSheet()->setCellValue('D12', 'PO #');
	$objPHPExcel->getActiveSheet()->setCellValue('E12', 'Style #');
	$objPHPExcel->getActiveSheet()->setCellValue('F12', 'Season');
	$objPHPExcel->getActiveSheet()->setCellValue('G12', 'Color');
	$objPHPExcel->getActiveSheet()->setCellValue('H12', 'Line');
	$objPHPExcel->getActiveSheet()->setCellValue('I12', 'CR Date');
	$objPHPExcel->getActiveSheet()->setCellValue('J12', 'FCR Date');
	$objPHPExcel->getActiveSheet()->setCellValue('K12', 'Shipping Date');
	$objPHPExcel->getActiveSheet()->setCellValue('L12', 'Arrival Date');
	$objPHPExcel->getActiveSheet()->setCellValue('M12', 'Terms of Payment');
	$objPHPExcel->getActiveSheet()->setCellValue('N12', 'Airway/Ladding Bill');
	$objPHPExcel->getActiveSheet()->setCellValue('O12', 'Invoice #');
	$objPHPExcel->getActiveSheet()->setCellValue('P12', 'Price');
	$objPHPExcel->getActiveSheet()->setCellValue('Q12', 'Ship Qty');
	$objPHPExcel->getActiveSheet()->setCellValue('R12', 'Amount');

	if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y")
	{
		$objPHPExcel->getActiveSheet()->setCellValue('S12', 'Rate');
		$objPHPExcel->getActiveSheet()->setCellValue('T12', 'Commission');

		$sColumn = "T";
	}

	else
		$sColumn = "S";

	// Set style for header row using alternative method
	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'font'    => array(
					'bold'      => true,
					'size' => 11
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				),
				'borders' => array(
					'top'     => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				),
				'fill' => array(
					'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
					'rotation'   => 90,
					'startcolor' => array(
						'argb' => 'FFA6A6A6'
					),
					'endcolor'   => array(
						'argb' => 'FFFFFFFF'
					)
				)
			),
			'A12:'.$sColumn.'12'
	);


	$sConditions  = "";
	$sConditions2 = "";

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

		$sConditions .= " AND vendor_id IN ($sVendors) ";


		$sSQL = "SELECT category FROM tbl_categories WHERE id='$Category'";
		$objDb->query($sSQL);

		$sCategory = $objDb->getField(0, 0);

		$objPHPExcel->getActiveSheet()->setCellValue('F9', "Category :");
		$objPHPExcel->getActiveSheet()->setCellValue('G9', $sCategory);
	}


	if (count($Season) == 0)
	{
		if (count($Brand) > 0)
			$sConditions .= (" AND brand_id IN (".@implode(",", $Brand).") ");

		else
			$sConditions .= " AND brand_id IN ({$_SESSION['Brands']})";
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

		$sConditions .= " AND id IN ($sPos)";
	}

	if (count($Brand) > 0)
	{
		$sSQL = ("SELECT GROUP_CONCAT(brand SEPARATOR ', ') FROM tbl_brands WHERE id IN (".@implode(",", $Brand).")");
		$objDb->query($sSQL);

		$sBrand = $objDb->getField(0, 0);

		$objPHPExcel->getActiveSheet()->setCellValue('F8', 'Brand :');
		$objPHPExcel->getActiveSheet()->setCellValue('G8', $sBrand);
	}

	if (count($Vendor) > 0)
	{
		$sConditions .= (" AND vendor_id IN (".@implode(",", $Vendor).")");


		$sSQL = ("SELECT GROUP_CONCAT(vendor SEPARATOR ', ') FROM tbl_vendors WHERE id IN (".@implode(",", $Vendor).")");
		$objDb->query($sSQL);

		$sVendor = $objDb->getField(0, 0);


		$objPHPExcel->getActiveSheet()->setCellValue('I9', 'Vendor :');
		$objPHPExcel->getActiveSheet()->setCellValue('J9', $sVendor);
	}

	else
		$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']})";


	if ($Region > 0)
	{
		$sSQL = "SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		$sVendors = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sVendors .= (",".$objDb->getField($i, 0));

		if ($sVendors != "")
			$sVendors = substr($sVendors, 1);

		$sConditions .= " AND vendor_id IN ($sVendors)";

		$sSQL = "SELECT country FROM tbl_countries WHERE id='$Region'";
		$objDb->query($sSQL);

		if (!@in_array(67, $Brand) && !@in_array(75, $Brand))
		{
			$objPHPExcel->getActiveSheet()->setCellValue('K8', 'Region:');
			$objPHPExcel->getActiveSheet()->setCellValue('L8', $objDb->getField(0, 0));
		}

		else
		{
			$objPHPExcel->getActiveSheet()->setCellValue('I8', 'Region:');
			$objPHPExcel->getActiveSheet()->setCellValue('J8', $objDb->getField(0, 0));
		}
	}

	if ($FromDate != "" && $ToDate != "")
	{
		$sConditions2 .= " AND (shipping_date BETWEEN '$FromDate' AND '$ToDate') ";

		$objPHPExcel->getActiveSheet()->setCellValue('A8', 'Shipping Date :');
		$objPHPExcel->getActiveSheet()->mergeCells('B8:D8');
		$objPHPExcel->getActiveSheet()->setCellValue('B8', formatDate($FromDate).' / '.formatDate($ToDate));
	}


	$sConditions = substr($sConditions, 5);

	$sSQL = "SELECT id FROM tbl_po WHERE {$sConditions} AND currency='$Currency'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$sPos = "0";

	for ($i = 0; $i < $iCount; $i ++)
		$sPos .= (",".$objDb->getField($i, 0));



	$sConditions    = " AND po_id IN ($sPos) ";
	$sLeftCurrency  = (($Currency == "USD") ? "$ " : "");
	$sRightCurrency = (($Currency != "USD") ? " {$Currency}" : "");

	$iIndex           = 13;
	$iTotalPcs        = 0;
	$fTotalPrice      = 0;
	$fTotalCommission = 0;


	$sSQL = "SELECT id, category FROM tbl_style_categories ORDER BY category";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCategory = $objDb->getField($i, 0);
		$sCategory = $objDb->getField($i, 1);


		$iCategoryPcs        = 0;
		$fCategoryPrice      = 0;
		$fCategoryCommission = 0;


		$sSQL =" SELECT DISTINCT(pc.po_id)
		         FROM tbl_po_colors pc, tbl_styles s
		         WHERE pc.style_id=s.id AND s.category_id='$iCategory'";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		$sPos = "";

		for ($j = 0; $j < $iCount2; $j ++)
			$sPos .= (",".$objDb2->getField($j, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);


		$sSQL = "SELECT DISTINCT(invoice_no) FROM tbl_pre_shipment_detail WHERE po_id IN ($sPos) $sConditions2 $sConditions ORDER BY invoice_no";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$sInvoiceNo = $objDb2->getField($j, 0);

			$iInvoicePcs        = 0;
			$fInvoicePrice      = 0;
			$fInvoiceCommission = 0;


			$sSQL = "SELECT id, po_id, shipping_date, arrival_date, terms_of_payment, lading_airway_bill, handover_to_forwarder, commission,commission_type,
							(SELECT lading_airway_bill FROM tbl_post_shipment_detail WHERE id=tbl_pre_shipment_detail.id) AS _Bill
					 FROM tbl_pre_shipment_detail
					 WHERE invoice_no='$sInvoiceNo' $sConditions2 $sConditions
					 ORDER BY shipping_date";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			$iStartIndex = $iIndex;

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iPoId                = $objDb3->getField($k, "po_id");
				$iShipId              = $objDb3->getField($k, "id");
				$sShippingDate        = $objDb3->getField($k, 'shipping_date');
				$sArrivalDate         = $objDb3->getField($k, 'arrival_date');
				$sTermsOfPayment      = $objDb3->getField($k, 'terms_of_payment');
				$sLadingAirwayBill    = $objDb3->getField($k, 'lading_airway_bill');
				$sHandoverToForwarder = $objDb3->getField($k, 'handover_to_forwarder');
				$fCommission          = $objDb3->getField($k, 'commission');
				$sCommissionType      = $objDb3->getField($k, 'commission_type');


				if ($sLadingAirwayBill == "")
					$sLadingAirwayBill = $objDb3->getField($k, '_Bill');


				$sSQL = "SELECT CONCAT(order_no, ' ', order_status) AS _OrderNo, vendor_id, vas_adjustment FROM tbl_po WHERE id='$iPoId'";
				$objDb4->query($sSQL);

				$sPo            = $objDb4->getField(0, "_OrderNo");
				$iVendorId      = $objDb4->getField(0, "vendor_id");
				$fVasAdjustment = $objDb4->getField(0, "vas_adjustment");


				$sSQL = "SELECT pc.id, pc.color, pc.line, pc.price, pc.style_id, pc.etd_required
				         FROM tbl_po_colors pc, tbl_styles s
				         WHERE pc.style_id=s.id AND s.category_id='$iCategory' AND pc.po_id='$iPoId'
				         ORDER BY pc.color";
				$objDb4->query($sSQL);

				$iCount4 = $objDb4->getCount( );

				for ($l = 0; $l < $iCount4; $l ++)
				{
					$iColorId     = $objDb4->getField($l, 'id');
					$sColor       = $objDb4->getField($l, 'color');
					$sLine        = $objDb4->getField($l, 'line');
					$fPrice       = $objDb4->getField($l, 'price');
					$iStyleId     = $objDb4->getField($l, 'style_id');
					$sEtdRequired = $objDb4->getField($l, 'etd_required');


					$sSQL = "SELECT style, season_id FROM tbl_styles WHERE id='$iStyleId'";
					$objDb5->query($sSQL);

					$sStyle  = $objDb5->getField(0, 0);
					$iSeason = $objDb5->getField(0, 1);


					$sSQL = "SELECT COALESCE(SUM(quantity), 0) FROM tbl_pre_shipment_quantities WHERE po_id='$iPoId' AND color_id='$iColorId' AND ship_id='$iShipId'";
					$objDb5->query($sSQL);

					$iQuantity = $objDb5->getField(0, 0);


					if ($iQuantity > 0)
					{
						$fAmount = ($fPrice * $iQuantity);

						if ($sCommissionType == "P")
							$fMatrixCommission = (($fAmount / 100) * $fCommission);

						else
							$fMatrixCommission = (($iQuantity * $fCommission) / 100);


						$sIcNo = getIcNo($sInvoiceNo);

						$objPHPExcel->getActiveSheet()->setCellValue('A'.$iIndex, 'MS/'.$sIcNo);
						$objPHPExcel->getActiveSheet()->setCellValue('B'.$iIndex, $sCategory);
						$objPHPExcel->getActiveSheet()->setCellValue('C'.$iIndex, $sVendorsList[$iVendorId]);
						$objPHPExcel->getActiveSheet()->setCellValue('D'.$iIndex, $sPo);
						$objPHPExcel->getActiveSheet()->setCellValue('E'.$iIndex, $sStyle);
						$objPHPExcel->getActiveSheet()->setCellValue('F'.$iIndex, $sSeasonsList[$iSeason]);
						$objPHPExcel->getActiveSheet()->setCellValue('G'.$iIndex, $sColor);
						$objPHPExcel->getActiveSheet()->setCellValue('H'.$iIndex, $sLine);
						$objPHPExcel->getActiveSheet()->setCellValue('I'.$iIndex, formatDate($sEtdRequired));
						$objPHPExcel->getActiveSheet()->setCellValue('J'.$iIndex, formatDate($sHandoverToForwarder));
						$objPHPExcel->getActiveSheet()->setCellValue('K'.$iIndex, formatDate($sShippingDate));
						$objPHPExcel->getActiveSheet()->setCellValue('L'.$iIndex, formatDate($sArrivalDate));
						$objPHPExcel->getActiveSheet()->setCellValue('M'.$iIndex, $sTermsOfPayment);
						$objPHPExcel->getActiveSheet()->setCellValue('N'.$iIndex, $sLadingAirwayBill);
						$objPHPExcel->getActiveSheet()->setCellValue('O'.$iIndex, " $sInvoiceNo");
						$objPHPExcel->getActiveSheet()->setCellValue('P'.$iIndex, ("{$sLeftCurrency}".formatNumber($fPrice, true, $iDecimals)."{$sRightCurrency}"));
						$objPHPExcel->getActiveSheet()->setCellValue('Q'.$iIndex, formatNumber($iQuantity));
						$objPHPExcel->getActiveSheet()->setCellValue('R'.$iIndex, ("{$sLeftCurrency}".formatNumber($fAmount, true, $iDecimals)."{$sRightCurrency}"));

						if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y")
						{
							$objPHPExcel->getActiveSheet()->setCellValue('S'.$iIndex, formatNumber($fCommission, (($sCommissionType == "P") ? true : false)).(($sCommissionType == "P") ? "%" : @utf8_encode("¢")));
							$objPHPExcel->getActiveSheet()->setCellValue('T'.$iIndex, ("{$sLeftCurrency}".formatNumber($fMatrixCommission, true, $iDecimals)."{$sRightCurrency}"));
						}

						$objPHPExcel->getActiveSheet()->getStyle('A'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$objPHPExcel->getActiveSheet()->getStyle('B'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$objPHPExcel->getActiveSheet()->getStyle('C'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$objPHPExcel->getActiveSheet()->getStyle('D'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$objPHPExcel->getActiveSheet()->getStyle('E'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$objPHPExcel->getActiveSheet()->getStyle('F'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$objPHPExcel->getActiveSheet()->getStyle('G'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$objPHPExcel->getActiveSheet()->getStyle('H'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$objPHPExcel->getActiveSheet()->getStyle('K'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$objPHPExcel->getActiveSheet()->getStyle('L'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$objPHPExcel->getActiveSheet()->getStyle('M'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						$objPHPExcel->getActiveSheet()->getStyle('N'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						$objPHPExcel->getActiveSheet()->getStyle('O'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						$objPHPExcel->getActiveSheet()->getStyle('P'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						$objPHPExcel->getActiveSheet()->getStyle('Q'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						$objPHPExcel->getActiveSheet()->getStyle('R'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						$objPHPExcel->getActiveSheet()->getStyle('S'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						$objPHPExcel->getActiveSheet()->getStyle('T'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

						$iInvoicePcs        += $iQuantity;
						$fInvoicePrice      += $fAmount;
						$fInvoiceCommission += $fMatrixCommission;

						$iIndex ++;
					}
				}
			}


			if ($iInvoicePcs > 0)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('N'.$iIndex, "Invoice Total :");
				$objPHPExcel->getActiveSheet()->mergeCells('N'.$iIndex.':P'.$iIndex);

				$objPHPExcel->getActiveSheet()->setCellValue('Q'.$iIndex, formatNumber($iInvoicePcs, false));
				$objPHPExcel->getActiveSheet()->setCellValue('R'.$iIndex, ("{$sLeftCurrency}".formatNumber($fInvoicePrice, true, $iDecimals)."{$sRightCurrency}"));

				if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y")
					$objPHPExcel->getActiveSheet()->setCellValue('T'.$iIndex, ("{$sLeftCurrency}".formatNumber($fInvoiceCommission, true, $iDecimals)."{$sRightCurrency}"));

				$objPHPExcel->getActiveSheet()->duplicateStyleArray(
						array(
							'font'    => array(
								'bold' => true,
								'size' => 11
							),
							'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
							),
							'borders' => array(
								'top'     => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN
								)
							),
							'fill' => array(
								'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
								'rotation'   => 90,
								'startcolor' => array(
									'argb' => 'FFDEDEDE'
								),
								'endcolor'   => array(
									'argb' => 'FFFFFFFF'
								)
							)
						),
						('A'.$iIndex.':'.$sColumn.$iIndex)
				);

				$objPHPExcel->getActiveSheet()->getStyle('L'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle('O'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle('P'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle('R'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$iIndex += 2;
			}

			$iCategoryPcs        += $iInvoicePcs;
			$fCategoryPrice      += $fInvoicePrice;
			$fCategoryCommission += $fInvoiceCommission;
		}


		if ($iCategoryPcs > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$iIndex, "Division Total ({$sCategory}) :");
			$objPHPExcel->getActiveSheet()->mergeCells('N'.$iIndex.':P'.$iIndex);

			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$iIndex, formatNumber($iCategoryPcs, false));
			$objPHPExcel->getActiveSheet()->setCellValue('R'.$iIndex, ("{$sLeftCurrency}".formatNumber($fCategoryPrice, true, $iDecimals)."{$sRightCurrency}"));

			if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y")
				$objPHPExcel->getActiveSheet()->setCellValue('T'.$iIndex, ("{$sLeftCurrency}".formatNumber($fCategoryCommission, true, $iDecimals)."{$sRightCurrency}"));

			$objPHPExcel->getActiveSheet()->duplicateStyleArray(
					array(
						'font'    => array(
							'bold' => true,
							'size' => 11
						),
						'alignment' => array(
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
						),
						'borders' => array(
							'top'     => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN
							)
						),
						'fill' => array(
							'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
							'rotation'   => 90,
							'startcolor' => array(
								'argb' => 'FFDEDEDE'
							),
							'endcolor'   => array(
								'argb' => 'FFFFFFFF'
							)
						)
					),
					('A'.$iIndex.':'.$sColumn.$iIndex)
			);

			$objPHPExcel->getActiveSheet()->getStyle('N'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('Q'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('R'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('T'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$iIndex += 3;
		}

		$iTotalPcs        += $iCategoryPcs;
		$fTotalPrice      += $fCategoryPrice;
		$fTotalCommission += $fCategoryCommission;
	}


	if ($iTotalPcs > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$iIndex, "Grand Shipment Total :");
		$objPHPExcel->getActiveSheet()->mergeCells('N'.$iIndex.':P'.$iIndex);

		$objPHPExcel->getActiveSheet()->setCellValue('Q'.$iIndex, formatNumber($iTotalPcs, false));
		$objPHPExcel->getActiveSheet()->setCellValue('R'.$iIndex, ("{$sLeftCurrency}".formatNumber($fTotalPrice, true, $iDecimals)."{$sRightCurrency}"));

		if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y")
			$objPHPExcel->getActiveSheet()->setCellValue('T'.$iIndex, ("{$sLeftCurrency}".formatNumber($fTotalCommission, true, $iDecimals)."{$sRightCurrency}"));

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
				array(
					'font'    => array(
						'bold' => true,
						'size' => 11
					),
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
					),
					'borders' => array(
						'top'     => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					),
					'fill' => array(
						'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
						'rotation'   => 90,
						'startcolor' => array(
							'argb' => 'FFA6A6A6'
						),
						'endcolor'   => array(
							'argb' => 'FFFFFFFF'
						)
					)
				),
				('A'.$iIndex.':'.$sColumn.$iIndex)
		);

		$objPHPExcel->getActiveSheet()->getStyle('N'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('Q'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('R'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('T'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	}


	// Set column widths
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('r')->setAutoSize(true);

	if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y")
	{
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
	}


	if (!@in_array(67, $Brand) && !@in_array(75, $Brand))
	{
		$objPHPExcel->getActiveSheet()->removeColumn('I', 2);

		if (count($Vendor) > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('H9', "");
			$objPHPExcel->getActiveSheet()->setCellValue('I9', "Vendor:");
			$objPHPExcel->getActiveSheet()->setCellValue('J9', $sVendor);
		}
	}

	if (!@in_array(43, $Brand) && !@in_array(32, $Brand))
		$objPHPExcel->getActiveSheet()->removeColumn('H', 1);
?>