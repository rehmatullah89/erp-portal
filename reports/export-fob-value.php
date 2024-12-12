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

	$Region      = IO::intValue('Region');
	$Brands      = IO::getArray('Brand');
	$Category    = IO::intValue('Category');
	$FromDate    = IO::strValue("FromDate");
	$ToDate      = IO::strValue("ToDate");
	$sConditions = "";

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand");
	$sRegion      = getDbValue("country", "tbl_countries", "id='$Region'");


	$sExcelFile = ($sBaseDir.TEMP_DIR."FOB Value.xlsx");

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setTitle("FOB Value");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("FOB Value");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");


	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getActiveSheet()->setCellValue('A2', "MATRIX SOURCING");
	$objPHPExcel->getActiveSheet()->mergeCells('A2:I2');
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(28);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->setCellValue('A3', ("From:   ".formatDate($FromDate)." to ".formatDate($ToDate)));
	$objPHPExcel->getActiveSheet()->mergeCells('A3:I3');

	$objPHPExcel->getActiveSheet()->setCellValue('A4', "Region:  ".$sRegion);
	$objPHPExcel->getActiveSheet()->mergeCells('A4:I4');

	if (count($Brands) > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A5', "Brand:   ".getDbValue("GROUP_CONCAT(brand SEPARATOR ', ')", "tbl_brands", ("FIND_IN_SET(id, '".@implode(",", $Brands)."')")));
		$objPHPExcel->getActiveSheet()->mergeCells('A5:I5');
	}
	
	if ($Category > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A6', "Category:   ".getDbValue("category", "tbl_categories", "id='$Category'"));
		$objPHPExcel->getActiveSheet()->mergeCells('A6:I6');
	}


	$iRow = (($Category > 0) ? 8 : 7);

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "PO          ");
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "Vendor      ");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "Brand       ");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "Style       ");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, "Color       ");
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, "ETD Required");
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, "Quantity    ");
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$iRow, "Price       ");
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$iRow, "FOB Value   ");

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
			('A'.$iRow.':I'.$iRow)
	);

	$iRow ++;



	if (count($Brands) > 0)
		$sConditions .= (" AND po.brand_id IN (".@implode(",", $Brands).") ");

	if ($Region > 0)
		$sConditions .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";
	
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

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND pc.etd_required BETWEEN '$FromDate' AND '$ToDate' ";


	$sSQL = "SELECT CONCAT(po.order_no, ' ', po.order_status) AS _Po, po.vendor_id, po.brand_id, pc.color, pc.etd_required, pc.price, SUM(pq.quantity) AS _Quantity,
	                (SELECT style FROM tbl_styles WHERE id=pc.style_id) AS _Style
			 FROM tbl_po po, tbl_po_colors pc, tbl_po_quantities pq
			 WHERE po.id=pc.po_id AND po.id=pq.po_id AND pc.id=pq.color_id $sConditions
			 GROUP BY po.id, pc.id
			 ORDER BY pc.etd_required, po.id";
	$objDb->query($sSQL);

	$iCount    = $objDb->getCount( );
	$iQuantity = 0;
	$fFobValue = 0;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, $objDb->getField($i, "_Po"));
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, $sVendorsList[$objDb->getField($i, "vendor_id")]);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, $sBrandsList[$objDb->getField($i, "brand_id")]);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, $objDb->getField($i, "_Style"));
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, $objDb->getField($i, "color"));
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, formatDate($objDb->getField($i, "etd_required")));
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, formatNumber($objDb->getField($i, "_Quantity"), false));
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$iRow, formatNumber($objDb->getField($i, "price")));
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$iRow, formatNumber($objDb->getField($i, "_Quantity") * $objDb->getField($i, "price")));

		$iQuantity += $objDb->getField($i, "_Quantity");
		$fFobValue += ($objDb->getField($i, "_Quantity") * $objDb->getField($i, "price"));

		$iRow ++;
	}

	$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, "Total :");
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, formatNumber($iQuantity, false));
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$iRow, formatNumber($fFobValue));

	$objPHPExcel->getActiveSheet()->getStyle('F'.$iRow)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$iRow)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('I'.$iRow)->getFont()->setBold(true);


	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				)
			),
			('A8:I'.$iRow)
	);

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
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
			('A'.$iRow.':I'.$iRow)
	);

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


	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('FOB Value');

	include 'PHPExcel/IOFactory.php';

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($sExcelFile);


	$objDb->close( );
	$objDb2->close( );
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