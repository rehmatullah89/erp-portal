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

	$objPHPExcel->getActiveSheet()->setCellValue('A11', 'Customer');
	$objPHPExcel->getActiveSheet()->setCellValue('B11', 'PO #');
	$objPHPExcel->getActiveSheet()->setCellValue('C11', 'Vendor');
	$objPHPExcel->getActiveSheet()->setCellValue('D11', 'Style #');
	$objPHPExcel->getActiveSheet()->setCellValue('E11', 'Season');
	$objPHPExcel->getActiveSheet()->setCellValue('F11', 'Color');
	$objPHPExcel->getActiveSheet()->setCellValue('G11', 'ETD Required');
	$objPHPExcel->getActiveSheet()->setCellValue('H11', 'Destination');
	$objPHPExcel->getActiveSheet()->setCellValue('I11', 'Ship Mode');
	$objPHPExcel->getActiveSheet()->setCellValue('J11', 'Ship Date');
	$objPHPExcel->getActiveSheet()->setCellValue('K11', 'ETA Destination');
	$objPHPExcel->getActiveSheet()->setCellValue('L11', 'Size');
	$objPHPExcel->getActiveSheet()->setCellValue('M11', 'Size Qty');
	$objPHPExcel->getActiveSheet()->setCellValue('N11', 'Quantity');
	$objPHPExcel->getActiveSheet()->setCellValue('O11', 'Ship Qty');
	$objPHPExcel->getActiveSheet()->setCellValue('P11', 'Deviation');
	$objPHPExcel->getActiveSheet()->setCellValue('Q11', 'Dev %');

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
			'A11:Q11'
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


	$iIndex            = 12;
	$iTotalOrderQty    = 0;
	$iTotalShipQty     = 0;
	$sVendorsList      = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND sourcing='Y'");
	$sBrandsList       = getList("tbl_brands", "id", "brand");
	$sDestinationsList = getList("tbl_destinations", "id", "destination");
	$sSizesList        = getList("tbl_sizes", "id", "size");
	$sTermsList        = getList("tbl_terms_of_delivery", "id", "terms");


	$sSQL = "SELECT DISTINCT(po_id) FROM tbl_pre_shipment_detail WHERE po_id!='0' $sConditions2 $sConditions ORDER BY po_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPoId = $objDb->getField($i, 0);


		$sSQL = "SELECT CONCAT(order_no, ' ', order_status) AS _Po, brand_id, vendor_id AS _Vendor FROM tbl_po WHERE id='$iPoId'";
		$objDb2->query($sSQL);

		$sPo     = $objDb2->getField(0, 0);
		$iBrand  = $objDb2->getField(0, 1);
		$iVendor = $objDb2->getField(0, 2);


		$sSQL = "SELECT id, color, style_id, destination_id, etd_required FROM tbl_po_colors WHERE po_id='$iPoId'";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iColor       = $objDb2->getField($j, 'id');
			$sColor       = $objDb2->getField($j, 'color');
			$iStyle       = $objDb2->getField($j, 'style_id');
			$iDestination = $objDb2->getField($j, 'destination_id');
			$sEtdRequired = $objDb2->getField($j, 'etd_required');


			$sSQL = "SELECT style, (SELECT season FROM tbl_seasons WHERE id=tbl_styles.season_id) FROM tbl_styles WHERE id='$iStyle'";
			$objDb3->query($sSQL);

			$sStyle  = $objDb3->getField(0, 0);
			$sSeason = $objDb3->getField(0, 1);


			$sSQL = "SELECT size_id, SUM(quantity) FROM tbl_po_quantities WHERE po_id='$iPoId' AND color_id='$iColor' GROUP BY size_id";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iSize     = $objDb3->getField($k, 0);
				$iQuantity = $objDb3->getField($k, 1);


				$sSQL = "SELECT id, shipping_date, terms_of_delivery_id, arrival_date FROM tbl_pre_shipment_detail WHERE po_id='$iPoId'";
				$objDb4->query($sSQL);

				$iCount4  = $objDb4->getCount( );
				$bShipped = false;
				$iSizeQty = 0;

				for ($l = 0; $l < $iCount4; $l ++)
				{
					$iShipId          = $objDb4->getField($l, 0);
					$sShippingDate    = $objDb4->getField($l, 1);
					$iTermsOfDelivery = $objDb4->getField($l, 2);
					$sArrivalDate     = $objDb4->getField($l, 3);


					$iShipQty = getDbValue("SUM(quantity)", "tbl_pre_shipment_quantities", "po_id='$iPoId' AND color_id='$iColor' AND ship_id='$iShipId' AND size_id='$iSize'");

					if ($iShipQty == 0 || strtotime($sShippingDate) < strtotime($FromDate) || strtotime($sShippingDate) > strtotime($ToDate))
						continue;

					$iSizeQty += $iShipQty;


					$objPHPExcel->getActiveSheet()->setCellValue('A'.$iIndex, $sBrandsList[$iBrand]);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$iIndex, $sPo);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$iIndex, $sVendorsList[$iVendor]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$iIndex, $sStyle);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$iIndex, $sSeason);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$iIndex, $sColor);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$iIndex, formatDate($sEtdRequired));
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$iIndex, $sDestinationsList[$iDestination]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$iIndex, $sTermsList[$iTermsOfDelivery]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$iIndex, formatDate($sShippingDate));
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$iIndex, formatDate($sArrivalDate));
					$objPHPExcel->getActiveSheet()->setCellValue('L'.$iIndex, $sSizesList[$iSize]);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$iIndex, (($bShipped == false) ? $iQuantity : 0));
					$objPHPExcel->getActiveSheet()->setCellValue('N'.$iIndex, $iQuantity);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.$iIndex, $iShipQty);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.$iIndex, ($iSizeQty - $iQuantity));
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.$iIndex, @round((100 - (($iSizeQty / $iQuantity) * 100)), 2));

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


					if ($bShipped == false)
						$iTotalOrderQty += $iQuantity;

					$iTotalShipQty  += $iShipQty;

					$iIndex ++;
					$bShipped = true;
				}
			}
		}
	}



	$objPHPExcel->getActiveSheet()->setCellValue('L'.$iIndex, "Order Total :");
	$objPHPExcel->getActiveSheet()->setCellValue('M'.$iIndex, $iTotalOrderQty);
	$objPHPExcel->getActiveSheet()->setCellValue('O'.$iIndex, $iTotalShipQty);
	$objPHPExcel->getActiveSheet()->setCellValue('P'.$iIndex, ($iTotalShipQty - $iTotalOrderQty));
	$objPHPExcel->getActiveSheet()->setCellValue('Q'.$iIndex, @round((100 - (($iTotalShipQty / $iTotalOrderQty) * 100)), 2));

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'font'    => array(
					'bold' => true,
					'size' => 10
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
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
			('A'.$iIndex.':Q'.$iIndex) );



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


	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Shipping Report');
?>