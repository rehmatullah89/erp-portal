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


	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$Vendors  = @implode(",", IO::getArray("Vendor"));


	$sVendorsList = getList("tbl_vendors", "id", "vendor", "brandix='Y'");
	$sConditions  = "";

	if (count($Vendors) > 0)
		$sConditions .= " AND po.vendor_id IN ($sVendors) ";




	$sExcelFile = "Loom-Plan.xlsx";

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Matrix Sourcing");
	$objPHPExcel->getProperties()->setLastModifiedBy("Matrix Sourcing");
	$objPHPExcel->getProperties()->setTitle("Loom Plan");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("Loom Plan");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");

	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);


	$objPHPExcel->getActiveSheet()->setCellValue('A2', "M A T R I X    S O U R C I N G");
	$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(28);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->setCellValue('A3', "Master Loom Plan");
	$objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(18);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



	$iColumn = 0;

	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "D:No  ");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "Status");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "Project No");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "Supplier");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "PI No  ");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "Loom Type");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "Quality");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "Reeds  ");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "RPM  ");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "EFF  ");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "Per day production/ Loom");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "Contracted Qty(m)");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "Dispatched Qty(m)");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "Balanced Qty to be shipped(m)");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "To date stock");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "Order Status");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "Expected No of Days delay");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "Action plan to recover delay");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "Planned ETD/QTY");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, "Actual ETD/QTY");

	for ($iDate = strtotime($FromDate); $iDate <= strtotime($ToDate); $iDate += 86400)
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, 5, date("j-M", $iDate));


	$objPHPExcel->getActiveSheet()->duplicateStyleArray
	(
			array(
				'font'    => array(
					'bold'      => true,
					'size' => 11
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
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
			('A5:'.getExcelCol(64 + $iColumn).'5')
	);




	$sSQL = "SELECT po.id, po.vendor_id, CONCAT(po.order_no, ' ', po.order_status) AS _Po, lp.looms, po.status, po.quantity, po.styles
	 		 FROM tbl_loom_plan AS lp, tbl_po AS po
			 WHERE po.id=lp.po_id AND ((lp.from_date BETWEEN '$FromDate' AND '$ToDate') OR (lp.to_date BETWEEN '$FromDate' AND '$ToDate'))
			 ORDER BY lp.from_date";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iRow   = 6;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPo       = $objDb->getField($i, 'id');
		$iVendor   = $objDb->getField($i, 'vendor_id');
		$sPo       = $objDb->getField($i, '_Po');
		$iOrderQty = $objDb->getField($i, 'quantity');
		$sStyles   = $objDb->getField($i, 'styles');
		$sLooms    = $objDb->getField($i, 'looms');
		$sPoStatus = $objDb->getField($i, 'status');

		$sStyle       = getDbValue("style", "tbl_styles", "FIND_IN_SET(id, '$sStyles')");
		$iShipQty     = getDbValue("SUM(quantity)", "tbl_pre_shipment_quantities", "po_id='$iPo'");
		$sEtdRequired = getDbValue("MIN(etd_required)", "tbl_po_colors", "po_id='$iPo'");
		$sEndDate     = getDbValue("MAX(date)", "tbl_loom_plan_details", "po_id='$iPo'");


		$sSQL = "SELECT lt.type, lt.capacity, l.efficiency
				 FROM tbl_looms AS l, tbl_loom_types AS lt
				 WHERE l.loom_type_id=lt.id AND l.vendor_id='$iVendor' AND FIND_IN_SET(l.id,'$sLooms')
				 ORDER BY lt.type";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$sLoomType   = $objDb2->getField($j, 'type');
			$sCapacity 	 = $objDb2->getField($j, 'capacity');
			$sEfficiency = $objDb2->getField($j, 'efficiency');

			$sLoomType   = (($sLoomType == "") ? "-" : $sLoomType);
			$iCapacity   = intval($sCapacity);
			$iEfficiency = intval($sEfficiency);
			$iColumn     = 0;

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, $sStyle);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, ((strtotime($sEndDate) <= strtotime(date("Y-m-d"))) ? "Off" : "On"));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, $sPo);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, $sVendorsList[$iVendor]);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, "");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, $sLoomType);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, "");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, "");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, $iCapacity);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, "{$iEfficiency}%");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, "");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, formatNumber($iOrderQty, false));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, formatNumber($iShipQty, false));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, formatNumber(($iOrderQty - $iShipQty), false));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, "");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, (($sPoStatus != "C") ? "Open" : "Closed"));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, "");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, "");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, "");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, formatDate($sEtdRequired));


			$sPlan = getList("tbl_loom_plan_details", "date", "COUNT(*)", "po_id='$iPo' AND production>'0' AND (`date` BETWEEN '$FromDate' AND '$ToDate')", "`date`", "`date`");

			for ($iDate = strtotime($FromDate); $iDate <= strtotime($ToDate); $iDate += 86400)
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iColumn++, $iRow, $sPlan[date("Y-m-d", $iDate)]);


			$iRow ++;
		}
	}



	for ($i = 0; $i < $iColumn; $i ++)
		$objPHPExcel->getActiveSheet()->getColumnDimension(getExcelCol(65 + $i))->setAutoSize(true);



	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Loom Plan');


	include 'PHPExcel/IOFactory.php';


	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save("php://output");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>