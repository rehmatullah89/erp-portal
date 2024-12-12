<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	$objPHPExcel->getActiveSheet()->setCellValue('A11', 'Vendor');
	$objPHPExcel->getActiveSheet()->setCellValue('B11', 'IC #');
	$objPHPExcel->getActiveSheet()->setCellValue('C11', 'Style #');
	$objPHPExcel->getActiveSheet()->setCellValue('D11', 'Season');
	$objPHPExcel->getActiveSheet()->setCellValue('E11', 'Color');
	$objPHPExcel->getActiveSheet()->setCellValue('F11', 'Shipping Date');
	$objPHPExcel->getActiveSheet()->setCellValue('G11', 'Total Cartons');
	$objPHPExcel->getActiveSheet()->setCellValue('H11', 'Ship Qty');
	$objPHPExcel->getActiveSheet()->setCellValue('I11', 'Terms of Payment');
	$objPHPExcel->getActiveSheet()->setCellValue('J11', 'Airway/Ladding Bill');
	$objPHPExcel->getActiveSheet()->setCellValue('K11', 'Invoice');
	$objPHPExcel->getActiveSheet()->setCellValue('L11', 'Destination');
	$objPHPExcel->getActiveSheet()->setCellValue('M11', 'Terms of Delivery');
	$objPHPExcel->getActiveSheet()->setCellValue('N11', 'Mode of Transport');

	// Set style for header row using alternative method
	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'font'    => array(
					'bold'      => true,
					'size' => 10
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
			'A11:N11'
	);


	$sConditions  = "";
	$sConditions2 = "";

	if ($Category != "")
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

		$objPHPExcel->getActiveSheet()->setCellValue('G8', "Category :");
		$objPHPExcel->getActiveSheet()->setCellValue('H8', $objDb->getField(0, 0));
	}


	if ($Brand != "")
		$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand'";

	else
		$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']})";

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


	if ($Brand != "")
	{
		$sSQL = "SELECT brand FROM tbl_brands WHERE id='$Brand'";
		$objDb->query($sSQL);

		$objPHPExcel->getActiveSheet()->setCellValue('G7', 'Brand :');
		$objPHPExcel->getActiveSheet()->setCellValue('H7', $objDb->getField(0, 0));
	}


	if ($Vendor != "")
	{
		$sConditions .= " AND vendor_id='$Vendor'";

		$sSQL = "SELECT vendor FROM tbl_vendors WHERE id='$Vendor'";
		$objDb->query($sSQL);

		$objPHPExcel->getActiveSheet()->setCellValue('A8', 'Vendor :');
		$objPHPExcel->getActiveSheet()->setCellValue('B8', $objDb->getField(0, 0));
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

		$objPHPExcel->getActiveSheet()->setCellValue('J7', 'Region :');
		$objPHPExcel->getActiveSheet()->setCellValue('K7', $objDb->getField(0, 0));
	}

	if ($TermsOfDelivery != "")
		$sConditions2 .= " AND terms_of_delivery_id='$TermsOfDelivery'";


	if ($sConditions != "")
	{
		$sConditions = substr($sConditions, 5);
		$sConditions = (" AND po_id IN (SELECT id FROM tbl_po WHERE ".$sConditions.")");
	}


	if ($FromDate != "" && $ToDate != "")
	{
		$sConditions2 .= " AND (shipping_date BETWEEN '$FromDate' AND '$ToDate') ";

		$objPHPExcel->getActiveSheet()->setCellValue('A7', 'Shipping Date :');
		$objPHPExcel->getActiveSheet()->mergeCells('B7:E7');
		$objPHPExcel->getActiveSheet()->setCellValue('B7', 'From:'.formatDate($FromDate).'   To:'.formatDate($ToDate));
	}


	$iIndex    = 12;
	$iTotalQty = 0;

	$iQuantities = array( );

	$sSQL = "SELECT DISTINCT(po_id) FROM tbl_pre_shipment_detail WHERE po_id != 0 AND quantity > 0 $sConditions2 $sConditions ORDER BY po_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPoId = $objDb->getField($i, 0);

		$sSQL = "SELECT CONCAT(order_no, ' ', order_status) AS _Po, (SELECT vendor FROM tbl_vendors WHERE id=tbl_po.vendor_id) AS _Vendor FROM tbl_po WHERE id='$iPoId'";
		$objDb2->query($sSQL);

		$sPo     = $objDb2->getField(0, 0);
		$sVendor = $objDb2->getField(0, 1);


		$objPHPExcel->getActiveSheet()->setCellValue('A'.$iIndex, ("Order # ".$sPo));
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$iIndex.':M'.$iIndex);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
				array(
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
				('A'.$iIndex.':N'.$iIndex)
		);

		$iIndex ++;


		$sSQL = "SELECT id, invoice_no, shipping_date, cartons, terms_of_payment, mode_of_transport, lading_airway_bill, (SELECT terms FROM tbl_terms_of_delivery WHERE id=tbl_pre_shipment_detail.terms_of_delivery_id) AS _TermsOfDelivery, (SELECT lading_airway_bill FROM tbl_post_shipment_detail WHERE id=tbl_pre_shipment_detail.id) AS _Bill FROM tbl_pre_shipment_detail WHERE po_id='$iPoId' AND quantity > 0 $sConditions2 $sConditions";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );
		$iPoQty  = 0;

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iShipId           = $objDb2->getField($j, "id");
			$sInvoice          = $objDb2->getField($j, 'invoice_no');
			$sShippingDate     = $objDb2->getField($j, 'shipping_date');
			$sCartons          = $objDb2->getField($j, 'cartons');
			$sModeOfTransport  = $objDb2->getField($j, 'mode_of_transport');
			$sTermsOfPayment   = $objDb2->getField($j, 'terms_of_payment');
			$sTermsOfDelivery  = $objDb2->getField($j, '_TermsOfDelivery');
			$sLadingAirwayBill = $objDb2->getField($j, 'lading_airway_bill');

			if ($sLadingAirwayBill == "")
				$sLadingAirwayBill = $objDb2->getField($j, '_Bill');


			$sSQL = "SELECT id, color, style_id, destination_id FROM tbl_po_colors WHERE po_id='$iPoId'";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );


			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iColorId       = $objDb3->getField($k, 'id');
				$sColor         = $objDb3->getField($k, 'color');
			    $iStyleId       = $objDb3->getField($k, 'style_id');
			    $iDestinationId = $objDb3->getField($k, 'destination_id');


				$sSQL = "SELECT style FROM tbl_styles WHERE id='$iStyleId'";
				$objDb4->query($sSQL);

				$sStyle = $objDb4->getField(0, 0);


				$sSQL = "SELECT season FROM tbl_seasons WHERE id=(SELECT season_id FROM tbl_styles WHERE id='$iStyleId')";
				$objDb4->query($sSQL);

				$sSeason = $objDb4->getField(0, 0);


				$sSQL = "SELECT destination FROM tbl_destinations WHERE id='$iDestinationId'";
				$objDb4->query($sSQL);

				$sDestination = $objDb4->getField(0, 0);


				$sSQL = "SELECT COALESCE(SUM(quantity), 0) FROM tbl_pre_shipment_quantities WHERE po_id='$iPoId' AND color_id='$iColorId' AND ship_id='$iShipId'";
				$objDb4->query($sSQL);

				$iQuantity = $objDb4->getField(0, 0);

				if ($iQuantity > 0)
				{
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$iIndex, $sVendor);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$iIndex, "MS/".getIcNo($sInvoice));
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$iIndex, $sStyle);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$iIndex, $sSeason);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$iIndex, $sColor);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$iIndex, formatDate($sShippingDate));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$iIndex, $sCartons);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$iIndex, formatNumber($iQuantity, false));
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$iIndex, $sTermsOfPayment);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$iIndex, $sLadingAirwayBill);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$iIndex, $sInvoice );
					$objPHPExcel->getActiveSheet()->setCellValue('L'.$iIndex, $sDestination);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$iIndex, $sTermsOfDelivery);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.$iIndex, $sModeOfTransport);


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
					$objPHPExcel->getActiveSheet()->getStyle('M'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$objPHPExcel->getActiveSheet()->getStyle('N'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

					$iQuantities["$sTermsOfDelivery"] += $iQuantity;

					$iPoQty    += $iQuantity;
					$iTotalQty += $iQuantity;

					$iIndex ++;
				}
			}
		}

		if ($iPoQty > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$iIndex, "PO Total :");
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$iIndex, formatNumber($iPoQty, false));

			$objPHPExcel->getActiveSheet()->duplicateStyleArray(
					array(
						'font'    => array(
							'bold' => true,
							'size' => 10
						),
						'alignment' => array(
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
						),
						'borders' => array(
							'top'     => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN
							)
						),
					),
					('A'.$iIndex.':N'.$iIndex)
			);

			$iIndex += 2;
		}
	}


	if ($iTotalQty > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$iIndex, "Order Total :");
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$iIndex, formatNumber($iTotalQty, false));

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
				array(
					'font'    => array(
						'bold' => true,
						'size' => 10
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
				('A'.$iIndex.':N'.$iIndex)
		);

		if ($TermsOfDelivery == "")
		{
			$iIndex += 5;

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$iIndex, "Terms of Delivery");
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$iIndex, "Quantity");

			$objPHPExcel->getActiveSheet()->mergeCells('A'.$iIndex.':'.'B'.$iIndex);
			$objPHPExcel->getActiveSheet()->mergeCells('C'.$iIndex.':'.'D'.$iIndex);

			$objPHPExcel->getActiveSheet()->duplicateStyleArray(
					array(
						'font'    => array(
							'bold' => true,
							'size' => 10
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
					('A'.$iIndex.':D'.$iIndex)
			);

			foreach ($iQuantities as $sTerms => $iQuantity)
			{
				$iIndex ++;

				$objPHPExcel->getActiveSheet()->setCellValue('A'.$iIndex, $sTerms);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$iIndex, formatNumber($iQuantity, false));

				$objPHPExcel->getActiveSheet()->mergeCells('A'.$iIndex.':'.'B'.$iIndex);
				$objPHPExcel->getActiveSheet()->mergeCells('C'.$iIndex.':'.'D'.$iIndex);
			}
		}
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


	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Shipping Report');
?>