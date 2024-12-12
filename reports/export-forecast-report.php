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

	$Year     = IO::intValue("Year");
	$Region   = IO::intValue("Region");
	$Category = IO::intValue("Category");
	$Vendor   = IO::intValue("Vendor");
	$Brand    = IO::getArray("Brand");

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id > 0 AND id IN ({$_SESSION['Brands']})");
	$sMonthsList  = array('January','February','March','April','May','June','July','August','September','October','November','December');

	$sConditions = "";

	if ($Year > 0)
		$sConditions .= " AND year='$Year' ";

	if ($Region > 0)
	{
		$sConditions .= " AND country_id='$Region' ";


		$sSQL = "SELECT country FROM tbl_countries WHERE id='$Region'";
		$objDb->query($sSQL);

		$sRegion = ("  (".$objDb->getField(0, 0).")");
	}

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
	}

	if (count($Brand) > 0)
		$sConditions .= (" AND FIND_IN_SET(brand_id, '".@implode(",", $Brand)."') ");

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));


	$iOriginals = array( );
	$iReviseds  = array( );

	$sSQL = "SELECT SUM(quantity) AS _Quantity, brand_id, vendor_id, month FROM tbl_forecasts $sConditions GROUP BY month, brand_id, vendor_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iMonth    = $objDb->getField($i, 'month');
		$iVendor   = $objDb->getField($i, 'vendor_id');
		$iBrand    = $objDb->getField($i, 'brand_id');
		$iQuantity = $objDb->getField($i, '_Quantity');

		$iOriginals[$iMonth][$iBrand][$iVendor] = $iQuantity;
	}


	 $sSQL = "SELECT SUM(quantity) AS _Quantity, brand_id, vendor_id, month FROM tbl_revised_forecasts $sConditions GROUP BY month, brand_id, vendor_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iMonth    = $objDb->getField($i, 'month');
		$iVendor   = $objDb->getField($i, 'vendor_id');
		$iBrand    = $objDb->getField($i, 'brand_id');
		$iQuantity = $objDb->getField($i, '_Quantity');

		$iReviseds[$iMonth][$iBrand][$iVendor] = $iQuantity;
	}


	$sExcelFile = ($sBaseDir.TEMP_DIR."forecast-report.xlsx");

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setTitle("Forecast Report");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("Forecast Report");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");

	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getActiveSheet()->setCellValue('A2', "TRIPLE TREE SOLUTIONS");
	$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(28);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->setCellValue('A3', "FORECAST REPORT OF YEAR $Year $sRegion");
	$objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(18);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


	$iTotalOriginal = 0;
	$iTotalRevised  = 0;
	$iRow           = 6;

	for ($i = 0; $i < 12; $i ++)
	{
		$iMonth = ($i + 1);

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, ($sMonthsList[($iMonth - 1)].' '.$Year));
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "Brand           ");
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "Vendor          ");
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "Original        ");
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, "Brand Original  ");
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, "Revised         ");
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, "Brand Revised   ");

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
				('A'.$iRow.':G'.$iRow)
		);

		$iRow ++;

		$iBrands      = @explode(",", $_SESSION['Brands']);
		$iBrandsCount = count($iBrands);
		$bMonth       = false;

		for ($j = 0; $j < $iBrandsCount; $j ++)
		{
			if (count($Brand) > 0 && !@in_array($iBrands[$j], $Brand))
				continue;

			$iVendors       = @explode(",", $_SESSION['Vendors']);
			$iVendorsCount  = count($iVendors);
			$bBrand         = false;
			$iBrandOriginal = 0;
			$iBrandRevised  = 0;

			for ($k = 0; $k < $iVendorsCount; $k ++)
			{
				if ($Vendor > 0 && $iVendors[$k] != $Vendor)
					continue;

				$iBrandOriginal += $iOriginals[$iMonth][$iBrands[$j]][$iVendors[$k]];
				$iBrandRevised  += $iReviseds[$iMonth][$iBrands[$j]][$iVendors[$k]];
			}

			$iBrandOriginal += $iOriginals[$iMonth][$iBrands[$j]][0];
			$iBrandRevised  += $iReviseds[$iMonth][$iBrands[$j]][0];

			if ($iBrandOriginal == 0 && $iBrandRevised == 0)
				continue;


			if ($iOriginals[$iMonth][$iBrands[$j]][0] > 0 || $iReviseds[$iMonth][$iBrands[$j]][0] > 0)
			{
				$iOriginal = $iOriginals[$iMonth][$iBrands[$j]][0];
				$iRevised  = $iReviseds[$iMonth][$iBrands[$j]][0];

				$iTotalOriginal += $iOriginal;
				$iTotalRevised  += $iRevised;

				$sBrand         = $sBrandsList[$iBrands[$j]];
				$sBrandOriginal = formatNumber($iBrandOriginal, false);
				$sBrandRevised  = formatNumber($iBrandRevised, false);
				$bBrand         = true;

				$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, $sBrand);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, $sVendorsList[$iVendors[$k]]);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, formatNumber($iOriginal, false));
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, $sBrandOriginal);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, formatNumber($iRevised, false));
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, $sBrandRevised);

				$objPHPExcel->getActiveSheet()->duplicateStyleArray(
						array(
							'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
							)
						),
						('A'.$iRow.':G'.$iRow)
				);

				$iRow ++;
			}


			for ($k = 0; $k < $iVendorsCount; $k ++)
			{
				if ($Vendor > 0 && $iVendors[$k] != $Vendor)
					continue;

				$iOriginal = $iOriginals[$iMonth][$iBrands[$j]][$iVendors[$k]];
				$iRevised  = $iReviseds[$iMonth][$iBrands[$j]][$iVendors[$k]];

				if ($iOriginal == 0 && $iRevised == 0)
					continue;

				$iTotalOriginal += $iOriginal;
				$iTotalRevised  += $iRevised;

				$sBrand         = "";
				$sBrandOriginal = "";
				$sBrandRevised  = "";

				if ($bBrand == false)
				{
					$sBrand         = $sBrandsList[$iBrands[$j]];
					$sBrandOriginal = formatNumber($iBrandOriginal, false);
					$sBrandRevised  = formatNumber($iBrandRevised, false);
					$bBrand         = true;
				}

				$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, $sBrand);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, $sVendorsList[$iVendors[$k]]);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, formatNumber($iOriginal, false));
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, $sBrandOriginal);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, formatNumber($iRevised, false));
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, $sBrandRevised);

				$objPHPExcel->getActiveSheet()->duplicateStyleArray(
						array(
							'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
							)
						),
						('A'.$iRow.':G'.$iRow)
				);

				$iRow ++;
			}
		}
	}


	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "Grand Total");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, formatNumber($iTotalOriginal, false));
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, formatNumber($iTotalRevised, false));

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
			('A'.$iRow.':G'.$iRow)
	);


	// Set column widths
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Forecast Report');

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