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

	$Vendor     = IO::intValue('Vendor');
	$Style      = IO::intValue('StyleNo');
	$Brand      = IO::intValue('Brand');
	$InvoiceNo  = IO::strValue('InvoiceNo');
	$Region     = IO::intValue('Region');
	$ArFromDate = IO::strValue('ArFromDate');
	$ArToDate   = IO::strValue('ArToDate');
	$ShFromDate = IO::strValue('ShFromDate');
	$ShToDate   = IO::strValue('ShToDate');

	$sExcelFile = ($sBaseDir."temp/ss-commission-report.xlsx");

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setTitle("Sales Samples Commission Report");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("Sales Samples Commission Report");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");

	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	// Add a drawing to the worksheet
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Logo');
	$objDrawing->setDescription('Logo');
	$objDrawing->setPath($sBaseDir.'images/reports/commission-report.jpg');
	$objDrawing->setCoordinates('A1');
	$objDrawing->setHeight(90);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet( ));


	$objPHPExcel->getActiveSheet()->setCellValue('A10', 'Vendor');
	$objPHPExcel->getActiveSheet()->setCellValue('B10', 'Style #');
	$objPHPExcel->getActiveSheet()->setCellValue('C10', 'Season');
	$objPHPExcel->getActiveSheet()->setCellValue('D10', 'Finish');
	$objPHPExcel->getActiveSheet()->setCellValue('E10', 'Shipping Date');
	$objPHPExcel->getActiveSheet()->setCellValue('F10', 'Arrival Date');
	$objPHPExcel->getActiveSheet()->setCellValue('G10', 'Terms of Payment');
	$objPHPExcel->getActiveSheet()->setCellValue('H10', 'Airway/Ladding Bill');
	$objPHPExcel->getActiveSheet()->setCellValue('I10', 'Invoice #');
	$objPHPExcel->getActiveSheet()->setCellValue('J10', 'Price');
	$objPHPExcel->getActiveSheet()->setCellValue('K10', 'Quantity');
	$objPHPExcel->getActiveSheet()->setCellValue('L10', 'Amount');

	if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y")
	{
		$objPHPExcel->getActiveSheet()->setCellValue('M10', 'Rate');
		$objPHPExcel->getActiveSheet()->setCellValue('N10', 'Commission');

		$sColumn = "N";
	}

	else
		$sColumn = "L";

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
			'A10:'.$sColumn.'10'
	);


	$sConditions = "";

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

//	else
//		$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Vendor > 0)
	{
		$sSQL = "SELECT vendor FROM tbl_vendors WHERE id='$Vendor'";
		$objDb->query($sSQL);

		$objPHPExcel->getActiveSheet()->setCellValue('A8', 'Vendor :');
		$objPHPExcel->getActiveSheet()->setCellValue('B8', $objDb->getField(0, 0));
	}


	if ($Style > 0)
	{
		$sConditions .= " AND style_id='$Style' ";

		$sSQL = "SELECT style FROM tbl_styles WHERE id='$Style'";
		$objDb->query($sSQL);

		$objPHPExcel->getActiveSheet()->setCellValue('A7', "Style :");
		$objPHPExcel->getActiveSheet()->setCellValue('B7', $objDb->getField(0, 0));
	}

	if ($Brand > 0)
		$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand'";

	else
		$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']})";

	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$sStyles = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sStyles .= (",".$objDb->getField($i, 0));

	if ($sStyles != "")
		$sStyles = substr($sStyles, 1);

	$sConditions .= " AND style_id IN ($sStyles) ";

	if ($Brand > 0)
	{
		$sSQL = "SELECT brand FROM tbl_brands WHERE id='$Brand'";
		$objDb->query($sSQL);

		$objPHPExcel->getActiveSheet()->setCellValue('D7', 'Brand :');
		$objPHPExcel->getActiveSheet()->setCellValue('E7', $objDb->getField(0, 0));
	}

	if ($Region > 0)
	{
		$sSQL = "SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y'";
		$objDb->query($sSQL);

		$iCount   = $objDb->getCount( );
		$sVendors = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sVendors .= (",".$objDb->getField($i, 0));

		if ($sVendors != "")
			$sVendors = substr($sVendors, 1);

		$sConditions .= " AND vendor_id IN ($sVendors) ";


		$sSQL = "SELECT country FROM tbl_countries WHERE id='$Region'";
		$objDb->query($sSQL);

		$objPHPExcel->getActiveSheet()->setCellValue('H8', 'Region :');
		$objPHPExcel->getActiveSheet()->mergeCells('I8:J8');
		$objPHPExcel->getActiveSheet()->setCellValue('I8', $objDb->getField(0, 0));
	}

	if ($InvoiceNo != "")
	{
		$sConditions .= " AND invoice_no='$InvoiceNo' ";

		$objPHPExcel->getActiveSheet()->setCellValue('H7', 'Invoice #');
		$objPHPExcel->getActiveSheet()->mergeCells('I7:J7');
		$objPHPExcel->getActiveSheet()->setCellValue('I7', $objDb->getField(0, 0));
	}

	if ($ArFromDate != "" && $ArToDate != "")
	{
		$sConditions .= " AND (arrival_date BETWEEN '$ArFromDate' AND '$ArToDate') ";

		$objPHPExcel->getActiveSheet()->setCellValue('A8', 'Arrival Date :');
		$objPHPExcel->getActiveSheet()->mergeCells('B8:C8');
		$objPHPExcel->getActiveSheet()->setCellValue('B8', 'From:'.formatDate($ArFromDate).'   To:'.formatDate($ArToDate));
	}

	if ($ShFromDate != "" && $ShToDate != "")
	{
		$sConditions .= " AND (shipping_date BETWEEN '$ShFromDate' AND '$ShToDate') ";

		$objPHPExcel->getActiveSheet()->setCellValue('D8', 'Shipping Date :');
		$objPHPExcel->getActiveSheet()->mergeCells('E8:F8');
		$objPHPExcel->getActiveSheet()->setCellValue('E8', 'From:'.formatDate($ShFromDate).'   To:'.formatDate($ShToDate));
	}

	$iIndex           = 11;
	$iTotalQuantity   = 0;
	$fTotalPrice      = 0;
	$fTotalCommission = 0;

	$sSQL = "SELECT DISTINCT(invoice_no) FROM tbl_sales_samples WHERE invoice_no!='' $sConditions ORDER BY invoice_no";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sInvoiceNo = $objDb->getField($i, 0);

		$sSQL = "SELECT vendor_id, style_id, finish, shipping_date, arrival_date, terms_of_payment, lading_airway_bill, price, quantity, commission FROM tbl_sales_samples WHERE invoice_no='$sInvoiceNo' $sConditions ORDER BY id DESC";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		$iInvoicePcs        = 0;
		$fInvoicePrice      = 0;
		$fInvoiceCommission = 0;

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iVendor           = $objDb2->getField($j, "vendor_id");
			$iStyleId          = $objDb2->getField($j, "style_id");
			$sFinish           = $objDb2->getField($j, 'finish');
			$sShippingDate     = $objDb2->getField($j, 'shipping_date');
			$sArrivalDate      = $objDb2->getField($j, 'arrival_date');
			$sTermsOfPayment   = $objDb2->getField($j, 'terms_of_payment');
			$sLadingAirwayBill = $objDb2->getField($j, 'lading_airway_bill');
			$fPrice            = $objDb2->getField($j, "price");
			$iQuantity         = $objDb2->getField($j, "quantity");
			$fCommission       = $objDb2->getField($j, "commission");

			$fAmount           = ($fPrice * $iQuantity);
			$fMatrixCommission = (($fAmount / 100) * $fCommission);


			$sSQL = "SELECT vendor FROM tbl_vendors WHERE id='$iVendor'";
			$objDb3->query($sSQL);

			$sVendor = $objDb3->getField(0, 0);


			$sSQL = "SELECT style, (SELECT season FROM tbl_seasons WHERE id=tbl_styles.season_id) AS _Season FROM tbl_styles WHERE id='$iStyleId'";
			$objDb3->query($sSQL);

			$sStyle  = $objDb3->getField(0, 0);
			$sSeason = $objDb3->getField(0, 1);

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$iIndex, $sVendor);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$iIndex, $sStyle);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$iIndex, $sSeason);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$iIndex, $sFinish);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$iIndex, formatDate($sShippingDate));
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$iIndex, formatDate($sArrivalDate));
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$iIndex, $sTermsOfPayment);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$iIndex, $sLadingAirwayBill);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$iIndex, $sInvoiceNo);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$iIndex, '$ '.formatNumber($fPrice));
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$iIndex, formatNumber($iQuantity, false));
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$iIndex, '$ '.formatNumber($fAmount));

			if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y")
			{
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$iIndex, formatNumber($fCommission).'%');
				$objPHPExcel->getActiveSheet()->setCellValue('N'.$iIndex, '$ '.formatNumber($fMatrixCommission));
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
			$objPHPExcel->getActiveSheet()->getStyle('J'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('K'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('L'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('M'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('N'.$iIndex)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$iInvoicePcs         += $iQuantity;
			$fInvoicePrice       += $fAmount;
			$fInvoiceCommission  += $fMatrixCommission;

			$iIndex ++;
		}

		if ($iInvoicePcs > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$iIndex, "Total :");
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$iIndex, formatNumber($iInvoicePcs, false));
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$iIndex, '$ '.formatNumber($fInvoicePrice));
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$iIndex, '$ '.formatNumber($fInvoiceCommission));

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
					('A'.$iIndex.':'.$sColumn.$iIndex)
			);
		}

		$iTotalQuantity   += $iInvoicePcs;
		$fTotalPrice      += $fInvoicePrice;
		$fTotalCommission += $fInvoiceCommission;

		$iIndex += 2;
	}


	if ($iCount > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$iIndex, "Total :");
		$objPHPExcel->getActiveSheet()->setCellValue('K'.$iIndex, formatNumber($iTotalQuantity, false));
		$objPHPExcel->getActiveSheet()->setCellValue('L'.$iIndex, '$ '.formatNumber($fTotalPrice));
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$iIndex, '$ '.formatNumber($fTotalCommission));

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
				('A'.$iIndex.':'.$sColumn.$iIndex)
		);
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


	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('SS Commission Report');

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