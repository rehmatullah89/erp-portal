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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$POs    = @implode(",", IO::getArray("Po"));
	$Type   = IO::strValue("Type");
	$Mode   = IO::strValue("Mode");
	$Format = IO::strValue("Format");

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']})");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id!='0'");
	$sSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0'");


	$sSQL = "SELECT category_id, vendor, btx_division FROM tbl_vendors WHERE id=(SELECT vendor_id FROM tbl_po WHERE id IN ($POs) LIMIT 1)";
	$objDb->query($sSQL);

	$iCategory    = $objDb->getField(0, 0);
	$sVendor      = $objDb->getField(0, 1);
	$sBtxDivision = $objDb->getField(0, 2);


	$sSQL = "SELECT category FROM tbl_categories WHERE id='$iCategory'";
	$objDb->query($sSQL);

	$sCategory = $objDb->getField(0, 0);


	$sSQL = "SELECT etd_required, style_id FROM tbl_po_colors WHERE po_id IN ($POs) ORDER BY etd_required ASC LIMIT 1";
	$objDb->query($sSQL);

	$sFromDate = $objDb->getField(0, 0);
	$iStyleId  = $objDb->getField(0, 1);


	$sSQL = "SELECT sub_brand_id FROM tbl_styles WHERE id='$iStyleId'";
	$objDb->query($sSQL);

	$iBrandId = $objDb->getField(0, 0);


	if ($Mode == "Brands")
	{
		$sSQL = "SELECT brand FROM tbl_brands WHERE id='$iBrandId'";
		$objDb->query($sSQL);

		$sBrand = $objDb->getField(0, 0);
	}


	$sSQL = "SELECT etd_required FROM tbl_po_colors WHERE po_id IN ($POs) ORDER BY etd_required DESC LIMIT 1";
	$objDb->query($sSQL);

	$sToDate = $objDb->getField(0, 0);


	$sCategories = array( );

	$sSQL = "SELECT * FROM tbl_categories WHERE id='$iCategory'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sCategories['knitting']           = $objDb->getField(0, 'knitting');
		$sCategories['linking']            = $objDb->getField(0, 'linking');
		$sCategories['yarn']               = $objDb->getField(0, 'yarn');
		$sCategories['sizing']             = $objDb->getField(0, 'sizing');
		$sCategories['weaving']            = $objDb->getField(0, 'weaving');
		$sCategories['leather_import']     = $objDb->getField(0, 'leather_import');
		$sCategories['dyeing']             = $objDb->getField(0, 'dyeing');
		$sCategories['leather_inspection'] = $objDb->getField(0, 'leather_inspection');
		$sCategories['lamination']         = $objDb->getField(0, 'lamination');
		$sCategories['cutting']            = $objDb->getField(0, 'cutting');
		$sCategories['print_embroidery']   = $objDb->getField(0, 'print_embroidery');
		$sCategories['sorting']            = $objDb->getField(0, 'sorting');
		$sCategories['bladder_attachment'] = $objDb->getField(0, 'bladder_attachment');
		$sCategories['stitching']          = $objDb->getField(0, 'stitching');
		$sCategories['washing']            = $objDb->getField(0, 'washing');
		$sCategories['finishing']          = $objDb->getField(0, 'finishing');
		$sCategories['lab_testing']        = $objDb->getField(0, 'lab_testing');
		$sCategories['quality']            = $objDb->getField(0, 'quality');
		$sCategories['packing']            = $objDb->getField(0, 'packing');
	}


	if ($Format == "pdf")
	{
			@include($sBaseDir."includes/crc/export-actual-btx-pdf-vsr.php");
/*
		if ($sBtxDivision == "Y" && $Type == "Actual")
			@include($sBaseDir."includes/crc/export-actual-btx-pdf-vsr.php");

		else if ($Type == "Planned")
			@include($sBaseDir."includes/crc/export-planned-pdf-vsr.php");

		else if ($Type == "Comparative")
			@include($sBaseDir."includes/crc/export-comparative-pdf-vsr.php");

		else if ($Type == "Actual")
			@include($sBaseDir."includes/crc/export-actual-pdf-vsr.php");
*/
	}


	else if ($Format == "xlsx")
	{
		$sExcelFile = ($sBaseDir.TEMP_DIR.strtolower($Type)."-vsr.xlsx");

		@set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

		@require_once 'PHPExcel.php';
		@require_once 'PHPExcel/RichText.php';

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set properties
		$objPHPExcel->getProperties()->setCreator("Triple Tree");
		$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree");
		$objPHPExcel->getProperties()->setTitle("Sales VSR Report");
		$objPHPExcel->getProperties()->setSubject("");
		$objPHPExcel->getProperties()->setDescription("VSR Report");
		$objPHPExcel->getProperties()->setKeywords("");
		$objPHPExcel->getProperties()->setCategory("Reports");

		// Create a first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		// Add a drawing to the worksheet
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Logo');
		$objDrawing->setDescription('Logo');
		$objDrawing->setPath($sBaseDir.'images/reports/'.strtolower($Type).'-vsr.jpg');
		$objDrawing->setCoordinates('A1');
		$objDrawing->setHeight(90);
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet( ));


		$objPHPExcel->getActiveSheet()->setCellValue("A6", 'Merchandiser :  ');
		$objPHPExcel->getActiveSheet()->setCellValue("B6", $_SESSION['Name']);
		$objPHPExcel->getActiveSheet()->mergeCells('B6:D6');

		if ($Mode == "Vendors")
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A8', "Vendor :");
			$objPHPExcel->getActiveSheet()->setCellValue('B8', $sVendor);
		}


		$objPHPExcel->getActiveSheet()->setCellValue('E8', "Category :");
		$objPHPExcel->getActiveSheet()->setCellValue('F8', $sCategory);


		if ($Mode == "Brands")
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A8', "Brand :");
			$objPHPExcel->getActiveSheet()->setCellValue('B8', $sBrand);
		}


		$objPHPExcel->getActiveSheet()->setCellValue('H8', "Date Range :");
		$objPHPExcel->getActiveSheet()->setCellValue('I8', 'From: '.formatDate($sFromDate).'      To: '.formatDate($sToDate));
		$objPHPExcel->getActiveSheet()->mergeCells('I8:M8');


		if ($sBtxDivision == "Y" && $Type == "Actual")
			@include($sBaseDir."includes/crc/export-actual-btx-xlsx-vsr.php");

		else if ($Type == "Planned" && ($iBrandId == 67 || $iBrandId == 75))
			@include($sBaseDir."includes/crc/export-ar-planned-xlsx-vsr.php");

		else if ($Type == "Planned")
			@include($sBaseDir."includes/crc/export-planned-xlsx-vsr.php");

		else if ($Type == "Comparative" && ($iBrandId == 67 || $iBrandId == 75))
			@include($sBaseDir."includes/crc/export-ar-comparative-xlsx-vsr.php");

		else if ($Type == "Comparative")
			@include($sBaseDir."includes/crc/export-comparative-xlsx-vsr.php");

		else if ($Type == "Actual" && ($iBrandId == 67 || $iBrandId == 75))
			@include($sBaseDir."includes/crc/export-ar-actual-xlsx-vsr.php");

		else if ($Type == "Actual")
			@include($sBaseDir."includes/crc/export-actual-xlsx-vsr.php");


		// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		// Rename sheet
		$objPHPExcel->getActiveSheet()->setTitle($Type.' VSR');

		include 'PHPExcel/IOFactory.php';

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save($sExcelFile);


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
	}

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>