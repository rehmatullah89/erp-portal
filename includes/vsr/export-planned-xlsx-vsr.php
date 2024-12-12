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


	$sSQL = "SELECT * FROM tbl_categories WHERE id='$iCategory'";
	$objDb->query($sSQL);

	$iKnitting          = $objDb->getField(0, 'knitting');
	$iLinking           = $objDb->getField(0, 'linking');
	$iYarn              = $objDb->getField(0, 'yarn');
	$iSizing            = $objDb->getField(0, 'sizing');
	$iWeaving           = $objDb->getField(0, 'weaving');
	$iLeatherImport     = $objDb->getField(0, 'leather_import');
	$iDyeing            = $objDb->getField(0, 'dyeing');
	$iLeatherInspection = $objDb->getField(0, 'leather_inspection');
	$iLamination        = $objDb->getField(0, 'lamination');
	$iCutting           = $objDb->getField(0, 'cutting');
	$iPrintEmbroidery   = $objDb->getField(0, 'print_embroidery');
	$iSorting           = $objDb->getField(0, 'sorting');
	$iBladderAttachment = $objDb->getField(0, 'bladder_attachment');
	$iStitching         = $objDb->getField(0, 'stitching');
	$iWashing           = $objDb->getField(0, 'washing');
	$iFinishing         = $objDb->getField(0, 'finishing');
	$iLabTesting        = $objDb->getField(0, 'lab_testing');
	$iQuality           = $objDb->getField(0, 'quality');
	$iPacking           = $objDb->getField(0, 'packing');

	$iTotalDays = 4;
	$iTotalDays += $iKnitting;
	$iTotalDays += $iLinking;
	$iTotalDays += $iYarn;
	$iTotalDays += $iSizing;
	$iTotalDays += $iWeaving;
	$iTotalDays += $iLeatherImport;
	$iTotalDays += $iDyeing;
	$iTotalDays += $iLeatherInspection;
	$iTotalDays += $iLamination;
	$iTotalDays += $iCutting;
	$iTotalDays += $iPrintEmbroidery;
	$iTotalDays += $iSorting;
	$iTotalDays += $iBladderAttachment;
	$iTotalDays += $iStitching;
	$iTotalDays += $iWashing;
	$iTotalDays += $iFinishing;
	$iTotalDays += $iLabTesting;
	$iTotalDays += $iQuality;
	$iTotalDays += $iPacking;


	$sSQL = "SELECT id, order_no, vendor_id, shipping_dates, quantity, styles, destinations FROM tbl_po WHERE id IN ($POs) ORDER BY LEFT(shipping_dates, 10)";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++, $iRow ++)
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
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb->getField($i, 'po_received_date')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'factory_work_order'));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'material_fabric')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'finish')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEtdRequired));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'revised_etd')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($fPrice));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, (($objDb2->getField(0, 'variable') == "Y") ? "Y" : "N"));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'mode')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'trims')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'yarn_fabric')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'qrs_submit_date')));


		$sStartDate = date("Y-m-d", (strtotime($sEtdRequired) - ($iTotalDays * 24 * 60 * 60)));


		if ($sCategories['knitting'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iKnitting - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iKnitting);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));

			$sStartDate  = date("Y-m-d", (strtotime($sStartDate) + ($iKnitting * 24 * 60 * 60)));
		}

		if ($sCategories['linking'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iLinking - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iLinking);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));

			$sStartDate  = date("Y-m-d", (strtotime($sStartDate) + ($iLinking * 24 * 60 * 60)));
		}

		if ($sCategories['yarn'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iYarn - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iYarn);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));

			$sStartDate  = date("Y-m-d", (strtotime($sStartDate) + ($iYarn * 24 * 60 * 60)));
		}

		if ($sCategories['sizing'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iSizing - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iSizing);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));

			$sStartDate  = date("Y-m-d", (strtotime($sStartDate) + ($iSizing * 24 * 60 * 60)));
		}

		if ($sCategories['weaving'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iWeaving - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iWeaving);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));

			$sStartDate  = date("Y-m-d", (strtotime($sStartDate) + ($iWeaving * 24 * 60 * 60)));
		}

		if ($sCategories['leather_import'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iLeatherImport - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iLeatherImport);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));

			$sStartDate  = date("Y-m-d", (strtotime($sStartDate) + ($iLeatherImport * 24 * 60 * 60)));
		}

		if ($sCategories['dyeing'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iDyeing - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iDyeing);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));

			$sStartDate  = date("Y-m-d", (strtotime($sStartDate) + ($iDyeing * 24 * 60 * 60)));
		}

		if ($sCategories['leather_inspection'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iLeatherInspection - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iLeatherInspection);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));

			$sStartDate  = date("Y-m-d", (strtotime($sStartDate) + ($iLeatherInspection * 24 * 60 * 60)));
		}

		if ($sCategories['lamination'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iLamination - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iLamination);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));

			$sStartDate  = date("Y-m-d", (strtotime($sStartDate) + ($iLamination * 24 * 60 * 60)));
		}

		if ($sCategories['cutting'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iCutting - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iCutting);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));

			$sStartDate  = date("Y-m-d", (strtotime($sStartDate) + ($iCutting * 24 * 60 * 60)));
		}

		if ($sCategories['print_embroidery'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iPrintEmbroidery - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iPrintEmbroidery);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));

			$sStartDate  = date("Y-m-d", (strtotime($sStartDate) + ($iPrintEmbroidery * 24 * 60 * 60)));
		}

		if ($sCategories['sorting'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iSorting - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iSorting);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));

			$sStartDate  = date("Y-m-d", (strtotime($sStartDate) + ($iSorting * 24 * 60 * 60)));
		}

		if ($sCategories['bladder_attachment'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iBladderAttachment - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iBladderAttachment);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));

			$sStartDate  = date("Y-m-d", (strtotime($sStartDate) + ($iBladderAttachment * 24 * 60 * 60)));
		}

		if ($sCategories['stitching'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iStitching - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iStitching);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));

			$sStartDate  = date("Y-m-d", (strtotime($sStartDate) + ($iStitching * 24 * 60 * 60)));
		}

		if ($sCategories['washing'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iWashing - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iWashing);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));

			$sStartDate  = date("Y-m-d", (strtotime($sStartDate) + ($iWashing * 24 * 60 * 60)));
		}

		if ($sCategories['finishing'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iFinishing - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iFinishing);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));

			$sStartDate  = date("Y-m-d", (strtotime($sStartDate) + ($iFinishing * 24 * 60 * 60)));
		}

		if ($sCategories['lab_testing'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iLabTesting - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iLabTesting);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));

			$sStartDate  = date("Y-m-d", (strtotime($sStartDate) + ($iLabTesting * 24 * 60 * 60)));
		}

		if ($sCategories['quality'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iQuality - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iQuality);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));

			$sStartDate  = date("Y-m-d", (strtotime($sStartDate) + ($iQuality * 24 * 60 * 60)));
		}

		if ($sCategories['packing'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($iPacking - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $iPacking);

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $iPercentage."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate));
		}

		$sCutOffDate     = date("Y-m-d", (strtotime($sEtdRequired) - (3 * 24 * 60 * 60)));
		$sFinalAuditDate = date("Y-m-d", (strtotime($sEtdRequired) - (2 * 24 * 60 * 60)));

		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sCutOffDate));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sFinalAuditDate));
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