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
	$objDb4      = new Database( );
	$objDb5      = new Database( );
	$objDb6      = new Database( );

	$Brand    = IO::getArray('Brand');
	$Vendor   = IO::getArray('Vendor');
	$Category = IO::intValue('Category');
	$Season   = IO::getArray("Season");
	$Customer = IO::getArray("Customer");
	$Region   = IO::intValue('Region');
	$FromDate = IO::strValue('FromDate');
	$ToDate   = IO::strValue('ToDate');
	$Type     = IO::strValue("Type");
	$Currency = IO::strValue("Currency");


	$sExcelFile = ($sBaseDir.TEMP_DIR."commission-report.xlsx");

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	require_once 'PHPExcel.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setTitle("Commission Report");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("Commission Report");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");

	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);


	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sSeasonsList = getList("tbl_seasons", "id", "season");


	if (count($Brand) == 1 && $Brand[0] == 365)
		@include($sBaseDir."includes/reports/tnc-commission-report.php");

	else
	{
		$iDecimals = 2;

		if (@in_array(194, $Brand) || @in_array(236, $Brand))
			$iDecimals = 4;


		// Add a drawing to the worksheet
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Logo');
		$objDrawing->setDescription('Logo');
		$objDrawing->setPath($sBaseDir.'images/reports/commission-report.jpg');
		$objDrawing->setCoordinates('A1');
		$objDrawing->setHeight(110);
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet( ));


		if ($Type == "Destination")
			@include($sBaseDir."includes/reports/destination-wise-commission-report.php");

		else if ($Type == "Invoice")
			@include($sBaseDir."includes/reports/invoice-wise-commission-report.php");

		else if ($Type == "Region")
			@include($sBaseDir."includes/reports/region-wise-commission-report.php");

		else if ($Type == "Style")
			@include($sBaseDir."includes/reports/style-wise-commission-report.php");

		else if ($Type == "Line")
			@include($sBaseDir."includes/reports/line-wise-commission-report.php");
	}


	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(100);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.65);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0);


	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Commission Report');


	$sExcelFile = @basename($sExcelFile);

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save("php://output");


//	include 'PHPExcel/IOFactory.php';

//	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//	$objWriter->save($sExcelFile);

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDb4->close( );
	$objDb5->close( );
	$objDb6->close( );
	$objDbGlobal->close( );

/*
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
*/
	@ob_end_flush( );
?>