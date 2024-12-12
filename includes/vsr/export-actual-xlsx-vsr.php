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

	$iColumn = 65;
	$iRow    = 10;

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Vendor');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Brand');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Order No');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Style');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Season');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Quantity');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Programme');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'PO Received Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Factory Work Order');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Material/Fabric');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Finish');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Original ETD');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Revised ETD');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Price');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Variable');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Mode');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Trims');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Yarn/Fabric');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'QRS Submit Date');

	if ($sCategories['knitting'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Knitting');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Knitting Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Knitting End Date');
	}

	if ($sCategories['linking'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Linking');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Linking Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Linking End Date');
	}

	if ($sCategories['yarn'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Yarn');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Yarn Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Yarn End Date');
	}

	if ($sCategories['sizing'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Sizing');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Sizing Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Sizing End Date');
	}

	if ($sCategories['weaving'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Weaving');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Weaving Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Weaving End Date');
	}

	if ($sCategories['leather_import'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Leather Import');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Leather Import Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Leather Import End Date');
	}

	if ($sCategories['dyeing'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Dyeing');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Dyeing Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Dyeing End Date');
	}

	if ($sCategories['leather_inspection'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Leather Inspection');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Leather Inspection Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Leather Inspection End Date');
	}

	if ($sCategories['lamination'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Lamination');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Lamination Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Lamination End Date');
	}

	if ($sCategories['cutting'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Cutting');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Cutting Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Cutting End Date');
	}

	if ($sCategories['print_embroidery'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Print/Embroidery');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Print/Embroidery Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Print/Embroidery End Date');
	}

	if ($sCategories['sorting'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Sorting');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Sorting Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Sorting End Date');
	}

	if ($sCategories['bladder_attachment'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Bladder Attachment');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Bladder Attachment Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Bladder Attachment End Date');
	}

	if ($sCategories['stitching'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Stitching');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Stitching Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Stitching End Date');
	}

	if ($sCategories['washing'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Washing');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Washing Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Washing End Date');
	}

	if ($sCategories['finishing'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Finishing');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Finishing Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Finishing End Date');
	}

	if ($sCategories['lab_testing'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Lab Testing');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Lab Testing Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Lab Testing End Date');
	}

	if ($sCategories['quality'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Quality');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Quality Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Quality End Date');
	}

	if ($sCategories['packing'] > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Packing');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Packing Start Date');
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Packing End Date');
	}

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Cut Off Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Final Audit Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Production Status');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'ETD CTG/ZIA');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'ETA Denmark');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Destination');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Remarks');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn).$iRow, 'Portal Comments');

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
			(getExcelCol(65).$iRow.':'.getExcelCol($iColumn).$iRow)
	);

	$iRow ++;

	$sSQL = "SELECT id, order_no, vendor_id, shipping_dates, quantity, styles, destinations FROM tbl_po WHERE id IN ($POs) ORDER BY LEFT(shipping_dates, 10)";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i++, $iRow++)
	{
		$iPoId = $objDb->getField($i, 'id');

		@list($iStyleId)       = explode(",", $objDb->getField($i, 'styles'));
		@list($iDestinationId) = explode(",", $objDb->getField($i, 'destinations'));
		@list($sEtdRequired)   = explode(",", $objDb->getField($i, 'shipping_dates'));

		if ($iStyleId == 0)
		{
			$sSQL = "SELECT style_id, destination_id, price FROM tbl_po_colors WHERE po_id='$iPoId' LIMIT 1";
			$objDb2->query($sSQL);

			$iStyleId = $objDb2->getField(0, 0);
			$fPrice   = $objDb2->getField(0, 2);

			if ($iDestinationId == 0)
				$iDestinationId = $objDb2->getField(0, 1);
		}

		else
			$fPrice = getDbValue("price", "tbl_po_colors", "po_id='$iPoId' AND style_id='$iStyleId'");


		$sSQL = "SELECT style, sub_brand_id, sub_season_id FROM tbl_styles WHERE id='$iStyleId'";
		$objDb2->query($sSQL);

		$sStyle  = $objDb2->getField(0, 0);
		$iBrand  = $objDb2->getField(0, 1);
		$iSeason = $objDb2->getField(0, 2);


		$sSQL = "SELECT destination FROM tbl_destinations WHERE id='$iDestinationId'";

		if ($objDb2->query($sSQL) == true && $objDb2->getCount( ) == 1)
			$sDestination = $objDb2->getField(0, 0);

		else
			$sDestination = "";


		$sSQL = "SELECT * FROM tbl_vsr WHERE po_id='$iPoId'";
		$objDb2->query($sSQL);


		$iColumn = 65;

		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $sVendorsList[$objDb->getField($i, 'vendor_id')]);
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $sBrandsList[$iBrand]);
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb->getField($i, 'order_no'));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $sStyle);
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $sSeasonsList[$iSeason]);
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb->getField($i, 'quantity'), false));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'programme')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'po_received_date')));
		//$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate('15-Aug-2013'));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'factory_work_order'));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'material_fabric')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'finish')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEtdRequired));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'revised_etd')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($fPrice));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, (($objDb2->getField(0, 'variable') == "Y") ? "Y" : "N"));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'mode')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'trims'), false)."%");
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'yarn_fabric'), false)."%");
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'qrs_submit_date')));


		if ($sCategories['knitting'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'knitting'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'knitting_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'knitting_end_date')));
		}

		if ($sCategories['linking'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'linking'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'linking_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'linking_end_date')));
		}

		if ($sCategories['yarn'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'yarn'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'yarn_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'yarn_end_date')));
		}

		if ($sCategories['sizing'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'sizing'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'sizing_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'sizing_end_date')));
		}

		if ($sCategories['weaving'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'weaving'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'weaving_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'weaving_end_date')));
		}

		if ($sCategories['leather_import'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'leather_import'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'leather_import_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'leather_import_end_date')));
		}

		if ($sCategories['dyeing'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'dyeing'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'dyeing_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'dyeing_end_date')));
		}

		if ($sCategories['leather_inspection'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'leather_inspection'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'leather_inspection_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'leather_inspection_end_date')));
		}

		if ($sCategories['lamination'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'lamination'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'lamination_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'lamination_end_date')));
		}

		if ($sCategories['cutting'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'cutting'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'cutting_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'cutting_end_date')));
		}

		if ($sCategories['print_embroidery'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'print_embroidery'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'print_embroidery_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'print_embroidery_end_date')));
		}

		if ($sCategories['sorting'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'sorting'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'sorting_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'sorting_end_date')));
		}

		if ($sCategories['bladder_attachment'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'bladder_attachment'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'bladder_attachment_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'bladder_attachment_end_date')));
		}

		if ($sCategories['stitching'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'stitching'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'stitching_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'stitching_end_date')));
		}

		if ($sCategories['washing'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'washing'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'washing_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'washing_end_date')));
		}

		if ($sCategories['finishing'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'finishing'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'finishing_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'finishing_end_date')));
		}

		if ($sCategories['lab_testing'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'lab_testing'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'lab_testing_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'lab_testing_end_date')));
		}

		if ($sCategories['quality'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'quality'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'quality_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'quality_end_date')));
		}

		if ($sCategories['packing'] > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'packing'), false)."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'packing_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'packing_end_date')));
		}

		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'cut_off_date')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'final_audit_date')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'production_status')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'etd_ctg_zia')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'eta_denmark')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $sDestination);
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'remarks')));

		$sPortalComments = "";

		$sSQL = "SELECT comments, date_time, (SELECT name FROM tbl_users WHERE id=tbl_vsr_comments.user_id) AS _Name FROM tbl_vsr_comments WHERE po_id='$iPoId' ORDER BY id DESC LIMIT 1";
		$objDb2->query($sSQL);

		if ($objDb2->getCount( ) == 1)
		{
			$sName     = $objDb2->getField(0, "_Name");
			$sComments = $objDb2->getField(0, "comments");
			$sDateTime = $objDb2->getField(0, "date_time");

			$sPortalComments = utf8_encode($sDateTime." » ".$sName." » ".$sComments);
		}

		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $sPortalComments);
		$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColumn - 1).$iRow)->getAlignment()->setWrapText(true);

		for ($j = 65; $j < $iColumn; $j ++)
			$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($j).$iRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	}

	// Set column widths
	for ($i = 65; $i < $iColumn; $i ++)
		$objPHPExcel->getActiveSheet()->getColumnDimension(getExcelCol($i))->setAutoSize(true);
?>