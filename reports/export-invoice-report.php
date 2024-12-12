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
	$objDb3      = new Database( );

	$Invoice    = urldecode(IO::strValue('Invoice'));
	$sExcelFile = ($sBaseDir."temp/invoice-report.xlsx");

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setTitle("Invoice Report");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("Invoice Report");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");

	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	// Add a drawing to the worksheet
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Logo');
	$objDrawing->setDescription('Logo');
	$objDrawing->setPath($sBaseDir.'images/reports/shipping-report.jpg');
	$objDrawing->setCoordinates('A1');
	$objDrawing->setHeight(90);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet( ));


	$sSQL = "SELECT DISTINCT(color_id) FROM tbl_pre_shipment_quantities WHERE ship_id IN (SELECT id FROM tbl_pre_shipment_detail WHERE invoice_no='$Invoice')";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$sColors   = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sColors .= (",".$objDb->getField($i, 0));

	if ($sColors != "")
		$sColors = substr($sColors, 1);


	$sSQL = "SELECT destination FROM tbl_destinations WHERE id IN (SELECT DISTINCT(destination_id) FROM tbl_po_colors WHERE id IN ($sColors)) LIMIT 1";
	$objDb->query($sSQL);

	$sDestination = $objDb->getField(0, 0);


	$sSQL = "SELECT shipping_date, arrival_date, mode_of_transport, lading_airway_bill, (SELECT terms FROM tbl_terms_of_delivery WHERE id=tbl_pre_shipment_detail.terms_of_delivery_id) AS _TermsOfDelivery, (SELECT lading_airway_bill FROM tbl_post_shipment_detail WHERE id=tbl_pre_shipment_detail.id) AS _Bill FROM tbl_pre_shipment_detail WHERE invoice_no='$Invoice' ORDER BY po_id LIMIT 1";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$iRow = 14;

	if ($iCount == 1)
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A8', "Shipment Information :");
		$objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setSize(11);
		$objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('A8:B8');

		$objPHPExcel->getActiveSheet()->setCellValue('A9', 'Vendor Invoice');
		$objPHPExcel->getActiveSheet()->setCellValue('B9', 'Airway/Ladding Bill');
		$objPHPExcel->getActiveSheet()->setCellValue('C9', 'Destination');
		$objPHPExcel->getActiveSheet()->setCellValue('D9', 'Shipping Date');
		$objPHPExcel->getActiveSheet()->setCellValue('E9', 'Arrival Date');
		$objPHPExcel->getActiveSheet()->setCellValue('F9', 'Mode of Transport');

		$objPHPExcel->getActiveSheet()->setCellValue('G9', 'Terms of Delivery');
		$objPHPExcel->getActiveSheet()->mergeCells('G9:H9');

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
							'argb' => 'FFD9D9D9'
						),
						'endcolor'   => array(
							'argb' => 'FFFFFFFF'
						)
					)
				),
				'A9:H9'
		);

		$objPHPExcel->getActiveSheet()->setCellValue('A10', $Invoice);
		$objPHPExcel->getActiveSheet()->setCellValue('B10', (($objDb->getField(0, '_Bill') != "") ? $objDb->getField(0, '_Bill') : $objDb->getField(0, 'lading_airway_bill')));
		$objPHPExcel->getActiveSheet()->setCellValue('C10', $sDestination);
		$objPHPExcel->getActiveSheet()->setCellValue('D10', formatDate($objDb->getField(0, 'shipping_date')));
		$objPHPExcel->getActiveSheet()->setCellValue('E10', formatDate($objDb->getField(0, 'arrival_date')));
		$objPHPExcel->getActiveSheet()->setCellValue('F10', $objDb->getField(0, "mode_of_transport"));
		$objPHPExcel->getActiveSheet()->setCellValue('G10', $objDb->getField(0, "_TermsOfDelivery"));
		$objPHPExcel->getActiveSheet()->mergeCells('G10:H10');

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
				array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
					)
				),
				'A10:H10'
		);


		$objPHPExcel->getActiveSheet()->setCellValue('A13', "Color and Size Break Down :");
		$objPHPExcel->getActiveSheet()->getStyle('A13')->getFont()->setSize(11);
		$objPHPExcel->getActiveSheet()->getStyle('A13')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('A13:B13');


		$sSQL = "SELECT DISTINCT(size_id), (SELECT size FROM tbl_sizes WHERE id=tbl_po_quantities.size_id) AS _Size FROM tbl_po_quantities WHERE color_id IN ($sColors) ORDER BY size_id";
		$objDb->query($sSQL);

		$iColumns = $objDb->getCount( );
		$iSizeQty = array( );
		$sSizes   = array( );

		for ($i = 0; $i < $iColumns; $i ++)
		{
			$sSizes[$i][0] = $objDb->getField($i, 0);
			$sSizes[$i][1] = $objDb->getField($i, 1);
		}


		$sSQL = "SELECT id, po_id FROM tbl_pre_shipment_detail WHERE invoice_no='$Invoice' ORDER BY po_id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		$sInfo   = array( );
		$iTotals = array( );

		$sInfo['Po'][0]         = "Order #";
		$sInfo['Style'][0]      = "Style #";
		$sInfo['Call'][0]       = "Call #";
		$sInfo['Color'][0]      = "Color Name";
		$sInfo['Price'][0]      = "Price";
		$sInfo['TotalQty'][0]   = "Ship Qty";
		$sInfo['TotalPrice'][0] = "Total Price     ";

		$iIndex = 1;

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iShipId = $objDb->getField($i, 'id');
			$iPoId   = $objDb->getField($i, 'po_id');

			$sSQL = "SELECT CONCAT(order_no, ' ', order_status) AS _Po, call_no FROM tbl_po WHERE id='$iPoId'";
			$objDb2->query($sSQL);

			$sPo   = $objDb2->getField(0, 0);
			$sCall = $objDb2->getField(0, 1);


			$sSQL = "SELECT id, color, price, (SELECT style FROM tbl_styles WHERE id=tbl_po_colors.style_id) AS _Style FROM tbl_po_colors WHERE po_id='$iPoId'";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
			{
				$iColorId = $objDb2->getField($j, 'id');
				$sStyle   = $objDb2->getField($j, '_Style');
				$sColor   = $objDb2->getField($j, 'color');
				$fPrice   = $objDb2->getField($j, 'price');

				$sInfo['Po'][$iIndex]    = $sPo;
				$sInfo['Style'][$iIndex] = $sStyle;
				$sInfo['Call'][$iIndex]  = $sCall;
				$sInfo['Color'][$iIndex] = $sColor;
				$sInfo['Price'][$iIndex] = $fPrice;

				$sSQL = "SELECT size_id, quantity FROM tbl_pre_shipment_quantities WHERE po_id='$iPoId' AND ship_id='$iShipId' AND color_id='$iColorId' ORDER BY size_id";
				$objDb3->query($sSQL);

				$iCount3 = $objDb3->getCount( );

				for ($k = 0; $k < $iCount3; $k ++)
				{
					$iSizeQty[$objDb3->getField($k, 0)][$iIndex]  = $objDb3->getField($k, 1);
					$iSizeQty[$objDb3->getField($k, 0)]['Total'] += $objDb3->getField($k, 1);

					$sInfo['TotalQty'][$iIndex] += $objDb3->getField($k, 1);
				}

				$sInfo['TotalPrice'][$iIndex] = ($sInfo['TotalQty'][$iIndex] * $fPrice);

				$iIndex ++;
			}
		}


		$iRow = 14;

		for ($i = 0; $i < $iIndex; $i ++)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, $sInfo['Po'][$i]);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, $sInfo['Style'][$i]);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, $sInfo['Call'][$i]);

			$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, $sInfo['Color'][$i]);
			$objPHPExcel->getActiveSheet()->mergeCells('D'.$iRow.':E'.$iRow);

			if ($i > 0)
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, formatNumber($sInfo['Price'][$i], true, 4));

			else
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, formatNumber($sInfo['Price'][$i], true, 4));

			$iCell = 71;


			if ($i > 0)
			{
				for ($j = 0; $j < $iColumns; $j ++, $iCell ++)
					$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iCell).$iRow, $iSizeQty[$sSizes[$j][0]][$i]);
			}

			else
			{
				for ($j = 0; $j < $iColumns; $j ++, $iCell ++)
					$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iCell).$iRow, $sSizes[$j][1]);
			}

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iCell).$iRow, formatNumber($sInfo['TotalQty'][$i], false));

			if ($i == 0)
				$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iCell).$iRow, $sInfo['TotalQty'][$i]);

			$iCell ++;

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iCell).$iRow, formatNumber($sInfo['TotalPrice'][$i], true, 4));

			if ($i == 0)
				$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iCell).$iRow, $sInfo['TotalPrice'][$i]);


			if ($i == 0)
			{
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
						('A'.$iRow.':'.getExcelCol($iCell).$iRow)
				);
			}

			else
			{
				$objPHPExcel->getActiveSheet()->duplicateStyleArray(
						array(
							'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
							)
						),
						('A'.$iRow.':'.getExcelCol($iCell).$iRow)
				);
			}

			$iRow++;
		}

	}


	$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, "Total");

	$iCell = 71;

	for ($i = 0; $i < $iColumns; $i ++, $iCell ++)
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iCell).$iRow, formatNumber($iSizeQty[$sSizes[$i][0]]['Total'], false));


	$fTotalQty = 0;

	for ($i = 0; $i < $iColumns; $i ++)
		$fTotalQty += (int)$iSizeQty[$sSizes[$i][0]]['Total'];

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iCell).$iRow, formatNumber($fTotalQty, false));

	$iCell ++;

	$fTotalPrice = 0;

	for ($i = 1; $i < $iIndex; $i ++)
		$fTotalPrice += $sInfo['TotalPrice'][$i];

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iCell).$iRow, formatNumber($fTotalPrice, true, 4));

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'font'     => array(
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
						'argb' => 'FFD9D9D9'
					),
					'endcolor'   => array(
						'argb' => 'FFFFFFFF'
					)
				)
			),
			('A'.$iRow.':'.getExcelCol($iCell).$iRow)
	);


	// Set column widths
	for ($i = 65; $i <= 71; $i ++)
		$objPHPExcel->getActiveSheet()->getColumnDimension(getExcelCol($i))->setAutoSize(true);

	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Invoice Report');

	include 'PHPExcel/IOFactory.php';

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($sExcelFile);

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	// forcing csv file to download
	$iSize = @filesize($sExcelFile);

	if(ini_get('zlib.output_compression'))
		@ini_set('zlib.output_compression', 'Off');

	header('Content-Description: File Transfer');
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
	header('Content-Type: application/force-download');
	header("Content-Type: application/download");
	header("Content-Type: text/xlsx");
	header("Content-Disposition: attachment; filename=\"".basename($sExcelFile)."\";");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: $iSize");

	@readfile($sExcelFile);
	@unlink($sExcelFile);

	@ob_end_flush( );
?>