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
	$objPHPExcel->getActiveSheet()->setCellValue('B12', 'Vendor');
	$objPHPExcel->getActiveSheet()->setCellValue('C12', 'PO #');
	$objPHPExcel->getActiveSheet()->setCellValue('D12', 'Style #');
	$objPHPExcel->getActiveSheet()->setCellValue('E12', 'Season');
	$objPHPExcel->getActiveSheet()->setCellValue('F12', 'Color');
	$objPHPExcel->getActiveSheet()->setCellValue('G12', 'Line');
	$objPHPExcel->getActiveSheet()->setCellValue('H12', 'PO ETD');
	$objPHPExcel->getActiveSheet()->setCellValue('I12', 'Handover to Forwarder');
	$objPHPExcel->getActiveSheet()->setCellValue('J12', 'Shipping Date');
	$objPHPExcel->getActiveSheet()->setCellValue('K12', 'Arrival Date');
	$objPHPExcel->getActiveSheet()->setCellValue('L12', 'Destination');
	$objPHPExcel->getActiveSheet()->setCellValue('M12', 'Shipping Mode');
	$objPHPExcel->getActiveSheet()->setCellValue('N12', 'Terms of Payment');
	$objPHPExcel->getActiveSheet()->setCellValue('O12', 'Airway/Ladding Bill');
	$objPHPExcel->getActiveSheet()->setCellValue('P12', 'Invoice #');
	$objPHPExcel->getActiveSheet()->setCellValue('Q12', 'Price');
	$objPHPExcel->getActiveSheet()->setCellValue('R12', 'Order Qty');
	$objPHPExcel->getActiveSheet()->setCellValue('S12', 'Ship Qty');
	$objPHPExcel->getActiveSheet()->setCellValue('T12', 'Amount');

	if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y")
	{
		$objPHPExcel->getActiveSheet()->setCellValue('U12', 'Rate');
		$objPHPExcel->getActiveSheet()->setCellValue('V12', 'Commission');

		$sColumn = "V";
	}

	else
		$sColumn = "T";

	
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


		$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE style_id IN ($sStyles)";
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
	$iTotalShipQty    = 0;
	$iTotalPcs        = 0;
	$fTotalPrice      = 0;
	$fTotalCommission = 0;
	$sTermsList       = getList("tbl_terms_of_delivery", "id", "terms");

	
	$sSQL = "SELECT DISTINCT(invoice_no) FROM tbl_pre_shipment_detail WHERE invoice_no != '' $sConditions2 $sConditions ORDER BY invoice_no";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sInvoiceNo = $objDb->getField($i, 0);


		$sSQL = "SELECT id, po_id, shipping_date, arrival_date, terms_of_payment, lading_airway_bill, handover_to_forwarder, commission, commission_type, terms_of_delivery_id,
		                (SELECT lading_airway_bill FROM tbl_post_shipment_detail WHERE id=tbl_pre_shipment_detail.id) AS _Bill
		         FROM tbl_pre_shipment_detail
		         WHERE invoice_no='$sInvoiceNo' $sConditions2 $sConditions
		         ORDER BY shipping_date";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		$iInvoiceQty        = 0;
		$iInvoicePcs        = 0;
		$fInvoicePrice      = 0;
		$fInvoiceCommission = 0;

		$iStartIndex = $iIndex;

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iPoId                = $objDb2->getField($j, "po_id");
			$iShipId              = $objDb2->getField($j, "id");
			$sShippingDate        = $objDb2->getField($j, 'shipping_date');
			$sArrivalDate         = $objDb2->getField($j, 'arrival_date');
			$sTermsOfPayment      = $objDb2->getField($j, 'terms_of_payment');
			$sLadingAirwayBill    = $objDb2->getField($j, 'lading_airway_bill');
			$sHandoverToForwarder = $objDb2->getField($j, 'handover_to_forwarder');
			$fCommission          = $objDb2->getField($j, 'commission');
			$sCommissionType      = $objDb2->getField($j, 'commission_type');
			$iTermsOfDelivery     = $objDb2->getField($j, 'terms_of_delivery_id');
			$sTermsOfDelivery     = $sTermsList[$iTermsOfDelivery];

			if ($sLadingAirwayBill == "")
				$sLadingAirwayBill = $objDb2->getField($j, '_Bill');


			$sSQL = "SELECT CONCAT(order_no, ' ', order_status) AS _OrderNo, vendor_id, vas_adjustment, destinations FROM tbl_po WHERE id='$iPoId'";
			$objDb3->query($sSQL);

			$sPo            = $objDb3->getField(0, "_OrderNo");
			$iVendorId      = $objDb3->getField(0, "vendor_id");
			$fVasAdjustment = $objDb3->getField(0, "vas_adjustment");
			$iDestinations  = $objDb3->getField(0, "destinations");
			$sDestinations  = getDbValue("GROUP_CONCAT(DISTINCT(destination) SEPARATOR '; ')", "tbl_destinations", "id IN ($iDestinations)");

			
			$sSQL = "SELECT id, color, line, price, style_id, etd_required, order_qty FROM tbl_po_colors WHERE po_id='$iPoId' ORDER BY color";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iColorId     = $objDb3->getField($k, 'id');
				$sColor       = $objDb3->getField($k, 'color');
				$sLine        = $objDb3->getField($k, 'line');
				$fPrice       = $objDb3->getField($k, 'price');
				$iStyleId     = $objDb3->getField($k, 'style_id');
				$sEtdRequired = $objDb3->getField($k, 'etd_required');
				$iOrderQty    = $objDb3->getField($k, 'order_qty');


				$sSQL = "SELECT style, season_id FROM tbl_styles WHERE id='$iStyleId'";
				$objDb4->query($sSQL);

				$sStyle  = $objDb4->getField(0, 0);
				$iSeason = $objDb4->getField(0, 1);


				$sSQL = "SELECT COALESCE(SUM(quantity), 0) FROM tbl_pre_shipment_quantities WHERE po_id='$iPoId' AND color_id='$iColorId' AND ship_id='$iShipId'";
				$objDb4->query($sSQL);

				$iQuantity = $objDb4->getField(0, 0);

				if ($iQuantity > 0)
				{
					$fAmount = ($fPrice * $iQuantity);

					if ($sCommissionType == "P")
						$fMatrixCommission = (($fAmount / 100) * $fCommission);

					else
						$fMatrixCommission = (($iQuantity * $fCommission) / 100);


					$sIcNo                = getIcNo($sInvoiceNo);
                                        $iInvoiceQty         += $iOrderQty;
					$iInvoicePcs         += $iQuantity;
					$fInvoicePrice       += $fAmount;
					$fInvoiceCommission  += $fMatrixCommission;

					$objPHPExcel->getActiveSheet()->setCellValue('A'.$iIndex, 'MS/'.$sIcNo);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$iIndex, $sVendorsList[$iVendorId]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$iIndex, $sPo);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$iIndex, $sStyle);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$iIndex, $sSeasonsList[$iSeason]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$iIndex, $sColor);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$iIndex, $sLine);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$iIndex, formatDate($sEtdRequired));
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$iIndex, formatDate($sHandoverToForwarder));
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$iIndex, formatDate($sShippingDate));
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$iIndex, formatDate($sArrivalDate));
					$objPHPExcel->getActiveSheet()->setCellValue('L'.$iIndex, $sDestinations);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$iIndex, $sTermsOfDelivery);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.$iIndex, $sTermsOfPayment);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.$iIndex, $sLadingAirwayBill);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.$iIndex, " $sInvoiceNo");
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.$iIndex, ("{$sLeftCurrency}".formatNumber($fPrice, true, $iDecimals)."{$sRightCurrency}"));
					$objPHPExcel->getActiveSheet()->setCellValue('R'.$iIndex, str_replace(',', '', formatNumber($iOrderQty, false)));
					$objPHPExcel->getActiveSheet()->setCellValue('S'.$iIndex, str_replace(',', '', formatNumber($iQuantity, false)));
					$objPHPExcel->getActiveSheet()->setCellValue('T'.$iIndex, ("{$sLeftCurrency}".str_replace(',', '', formatNumber($fAmount, true, $iDecimals))."{$sRightCurrency}"));

					if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y")
					{
						$objPHPExcel->getActiveSheet()->setCellValue('U'.$iIndex, formatNumber($fCommission, (($sCommissionType == "P") ? true : false)).(($sCommissionType == "P") ? "%" : @utf8_encode("�")));
						$objPHPExcel->getActiveSheet()->setCellValue('V'.$iIndex, ("{$sLeftCurrency}".formatNumber($fMatrixCommission)."{$sRightCurrency}"));
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
					$objPHPExcel->getActiveSheet()->getStyle('L'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('M'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('N'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('O'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('P'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('Q'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('R'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('S'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('T'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('U'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('V'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$iIndex ++;
				}
			}
		}


		if ($iInvoicePcs > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$iIndex, "Invoice Total :");
			$objPHPExcel->getActiveSheet()->mergeCells('P'.$iIndex.':Q'.$iIndex);

			$objPHPExcel->getActiveSheet()->setCellValue('R'.$iIndex, formatNumber($iInvoiceQty, false));
			$objPHPExcel->getActiveSheet()->setCellValue('S'.$iIndex, formatNumber($iInvoicePcs, false));
			$objPHPExcel->getActiveSheet()->setCellValue('T'.$iIndex, ("{$sLeftCurrency}".formatNumber($fInvoicePrice, true, $iDecimals)."{$sRightCurrency}"));

			if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y")
				$objPHPExcel->getActiveSheet()->setCellValue('V'.$iIndex, ("{$sLeftCurrency}".formatNumber($fInvoiceCommission, true, $iDecimals)."{$sRightCurrency}"));

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
								'argb' => 'FFD9D9D9'
							),
							'endcolor'   => array(
								'argb' => 'FFFFFFFF'
							)
						)
					),
					('A'.$iIndex.':'.$sColumn.$iIndex)
			);

			$objPHPExcel->getActiveSheet()->getStyle('P'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('R'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('S'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('T'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('V'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$iIndex += 2;
		}

		$iTotalShipQty    += $iInvoiceQty;
		$iTotalPcs        += $iInvoicePcs;
		$fTotalPrice      += $fInvoicePrice;
		$fTotalCommission += $fInvoiceCommission;
	}


	if ($iTotalPcs > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue('O'.$iIndex, "Grand Shipment Total :");
		$objPHPExcel->getActiveSheet()->mergeCells('O'.$iIndex.':Q'.$iIndex);

		$objPHPExcel->getActiveSheet()->setCellValue('R'.$iIndex, formatNumber($iTotalShipQty, false));
		$objPHPExcel->getActiveSheet()->setCellValue('S'.$iIndex, formatNumber($iTotalPcs, false));
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$iIndex, ("{$sLeftCurrency}".formatNumber($fTotalPrice, true, $iDecimals)."{$sRightCurrency}"));

		if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y")
			$objPHPExcel->getActiveSheet()->setCellValue('V'.$iIndex, ("{$sLeftCurrency}".formatNumber($fTotalCommission, true, $iDecimals)."{$sRightCurrency}"));

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

		$objPHPExcel->getActiveSheet()->getStyle('O'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('R'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('S'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('T'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('V'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
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
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);

	if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y")
	{
		$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
	}

/*
	if (!@in_array(67, $Brand) && !@in_array(75, $Brand))
	{
		$objPHPExcel->getActiveSheet()->removeColumn('H', 2);

		if (count($Vendor) > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('H9', "");
			$objPHPExcel->getActiveSheet()->setCellValue('I9', "Vendor:");
			$objPHPExcel->getActiveSheet()->setCellValue('J9', $sVendor);
		}
	}
*/
	if (!@in_array(43, $Brand) && !@in_array(32, $Brand))
	{
		$objPHPExcel->getActiveSheet()->removeColumn('G', 1);
		$objPHPExcel->getActiveSheet()->setCellValue('G8', $sBrand);

		if ($Category > 0)
			$objPHPExcel->getActiveSheet()->setCellValue('G9', $sCategory);
	}
?>