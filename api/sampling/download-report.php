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

	$RequestCode = IO::strValue('RequestCode');
	$Id          = intval(str_replace("M", "", $RequestCode));

	$sExcelFile = ("M".str_pad($Id, 5, '0', STR_PAD_LEFT).".xlsx");


	@require_once($sBaseDir.'requires/PHPExcel.php');
	@require_once($sBaseDir.'requires/PHPExcel/RichText.php');


	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Matrix Sourcing");
	$objPHPExcel->getProperties()->setLastModifiedBy("Matrix Sourcing");
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


	@include($sBaseDir.'requires/PHPExcel/IOFactory.php');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');


	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save("php://output");


	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>