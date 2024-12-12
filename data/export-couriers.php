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

	$AirwayBill = IO::strValue("AirwayBill");
	$Company 	= IO::strValue("Company");
	$FromDate 	= IO::strValue("FromDate");
	$ToDate 	= IO::strValue("ToDate");


	$sEmployeesList = getList("tbl_users", "id", "name", "status='A'");
	$sCountriesList = getList("tbl_countries", "id", "country");


	$sConditions = "";

	if ($AirwayBill != "")
		$sConditions .= " AND awb_no LIKE '%$AirwayBill%' ";

	if ($Company != "")
		$sConditions .= " AND company='$Company' ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));


	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';



	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree");
	$objPHPExcel->getProperties()->setTitle("Couriers List");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("Couriers List");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");

	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getActiveSheet()->setCellValue('A2', "M A T R I X    S O U R C I N G");
	$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(28);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->setCellValue('A3', "Couriers Report");
	$objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(18);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


	$sSQL = "SELECT * FROM tbl_couriers $sConditions ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iRow   = 5;

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "AirwayBill # ");
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "Company ");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "Type      ");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "Sender/Reciever ");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, "Address         ");
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, "Country  ");
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, "Courier Date ");

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

	for ($i = 0; $i < $iCount; $i ++)
	{

		$sAirwayBill = $objDb->getField($i, 'awb_no');
		$sCompany    = $objDb->getField($i, 'company');
		$sType 		 = $objDb->getField($i, 'type');
		$iEmployee   = $objDb->getField($i, 'user_id');
		$iCountry    = $objDb->getField($i, 'country_id');
		$sAddress    = $objDb->getField($i, 'address');
		$sDate		 = $objDb->getField($i, 'date');

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, $sAirwayBill);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, $sCompany);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, $sType);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, ((@in_array($iEmployee, $sEmployeesList)) ? $sEmployeesList[$iEmployee] : getDbValue("name", "tbl_users", "id='$iEmployee'")));
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, $sCountriesList[$iCountry]);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, $sAddress);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, formatDate($sDate));

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
	$objPHPExcel->getActiveSheet()->setTitle('Couriers Report');

	include 'PHPExcel/IOFactory.php';


	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename=\"couriers-report.xlsx\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save("php://output");



	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>