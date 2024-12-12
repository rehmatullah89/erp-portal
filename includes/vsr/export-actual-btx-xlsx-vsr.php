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

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Factory');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'S/cont. Fac.');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Label');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Order');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Style');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Style Name');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Season');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Total Pcs');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Item');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'ETD');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Revised ETD');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Price');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Mode');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Trims');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Yarn/Fabric');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Knitting');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Dyeing');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Cutting');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Print/Embroidery');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Sewing/Linking');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Washing');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Packing');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Final Audit');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Status');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Shipped Qty');
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

	$sSQL = "SELECT id, order_no, vendor_id, shipping_dates, quantity, styles FROM tbl_po WHERE id IN ($POs) ORDER BY LEFT(shipping_dates, 10)";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++, $iRow ++)
	{
		$iPoId = $objDb->getField($i, 'id');

		@list($iStyleId)     = explode(",", $objDb->getField($i, 'styles'));
		@list($sEtdRequired) = explode(",", $objDb->getField($i, 'shipping_dates'));

		if ($iStyleId == 0)
		{
			$sSQL = "SELECT style_id, price FROM tbl_po_colors WHERE po_id='$iPoId' LIMIT 1";
			$objDb2->query($sSQL);

			$iStyleId = $objDb2->getField(0, 0);
			$fPrice   = $objDb2->getField(0, 1);
		}

		else
			$fPrice = getDbValue("price", "tbl_po_colors", "po_id='$iPoId' AND style_id='$iStyleId'");


		$sSQL = "SELECT style, style_name, sub_brand_id, sub_season_id FROM tbl_styles WHERE id='$iStyleId'";
		$objDb2->query($sSQL);

		$sStyle     = $objDb2->getField(0, 0);
		$sStyleName = $objDb2->getField(0, 1);
		$iBrand     = $objDb2->getField(0, 2);
		$iSeason    = $objDb2->getField(0, 3);


		$sSQL = "SELECT SUM(quantity) FROM tbl_pre_shipment_quantities WHERE po_id='$iPoId'";
		$objDb2->query($sSQL);

		$iShippedQty = $objDb2->getField(0, 0);


		$sSQL = "SELECT * FROM tbl_vsr WHERE po_id='$iPoId'";
		$objDb2->query($sSQL);

		$iColumn = 65;

		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $sVendorsList[$objDb->getField($i, 'vendor_id')]);
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'sub_contractor')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $sBrandsList[$iBrand]);
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb->getField($i, 'order_no'));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $sStyle);
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $sStyleName);
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($sSeasonsList[$iSeason]));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb->getField($i, 'quantity'), false));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'item')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEtdRequired));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'revised_etd')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($fPrice));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'mode')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'trims')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'yarn_fabric')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, getBtxVsrValue($objDb2->getField(0, 'knitting')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, getBtxVsrValue($objDb2->getField(0, 'dyeing')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, getBtxVsrValue($objDb2->getField(0, 'cutting')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, getBtxVsrValue($objDb2->getField(0, 'print_embroidery')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, getBtxVsrValue($objDb2->getField(0, 'linking')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, getBtxVsrValue($objDb2->getField(0, 'washing')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, getBtxVsrValue($objDb2->getField(0, 'packing')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'final_audit_date')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'production_status')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($iShippedQty, false));
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