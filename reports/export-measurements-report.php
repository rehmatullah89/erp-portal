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

	@ini_set("display_errors", "0");

	if ($sReportFile == "")
	{
		@require_once("../requires/session.php");

		$objDbGlobal = new Database( );
		$objDb       = new Database( );

		$Id = IO::intValue("Id");
	}


	$sExcelFile = ($sBaseDir."temp/measurements-report.xlsx");

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setTitle("Measurements Report");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("Measurements Report");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");

	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	// Add a drawing to the worksheet
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Logo');
	$objDrawing->setDescription('Logo');
	$objDrawing->setPath($sBaseDir.'images/reports/sampling-report.jpg');
	$objDrawing->setCoordinates('A1');
	$objDrawing->setHeight(110);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet( ));


	$sSQL = "SELECT style_id, `status`, (SELECT type FROM tbl_sampling_types WHERE id=tbl_merchandisings.sample_type_id) AS _SampleType, (SELECT wash FROM tbl_sampling_washes WHERE id=tbl_merchandisings.wash_id) AS _Wash FROM tbl_merchandisings WHERE id='$Id'";
	$objDb->query($sSQL);

	$iStyleId    = $objDb->getField(0, "style_id");
	$sStatus     = $objDb->getField(0, "status");
	$sSampleType = $objDb->getField(0, "_SampleType");
	$sWash       = $objDb->getField(0, "_Wash");


	$sSQL = "SELECT style, style_name, brand_id, (SELECT brand FROM tbl_brands WHERE id=tbl_styles.brand_id) AS _Brand, (SELECT season FROM tbl_seasons WHERE id=tbl_styles.season_id) AS _Season FROM tbl_styles WHERE id='$iStyleId'";
	$objDb->query($sSQL);

	$sStyle     = $objDb->getField(0, 'style');
	$sStyleName = $objDb->getField(0, 'style_name');
	$iBrand     = $objDb->getField(0, 'brand_id');
	$sBrand     = $objDb->getField(0, '_Brand');
	$sSeason    = $objDb->getField(0, '_Season');


	if ($iBrand == 31)
		@include($sBaseDir."includes/reports/nike-measurement-report.php");

	else
		@include($sBaseDir."includes/reports/others-measurement-report.php");


	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Measurement Report');

	include 'PHPExcel/IOFactory.php';

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');


	if ($sReportFile == "")
		$objWriter->save($sExcelFile);

	else
		$objWriter->save($sReportFile);


	if ($sReportFile == "")
	{
		$objDb->close( );
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
	}
?>