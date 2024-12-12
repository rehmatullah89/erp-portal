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


	$sDesignationsList = getList("tbl_designations", "id", "designation");


	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';



	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree");
	$objPHPExcel->getProperties()->setTitle("Extensions List");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("Extensions List");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");

	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getActiveSheet()->setCellValue('A2', "M A T R I X    S O U R C I N G");
	$objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(28);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->setCellValue('A3', "Office Staff Telephone Extensions List");
	$objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(18);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


	$sSQL = "SELECT id, department FROM tbl_departments ORDER BY department";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iRow   = 5;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDepartment = $objDb->getField($i, 'id');
		$sDepartment = $objDb->getField($i, 'department');


		$sSQL = "SELECT name, designation_id, mobile, phone_ext
		         FROM tbl_users
		         WHERE country_id='162' AND phone_ext!='' AND status='A' AND designation_id IN (SELECT id FROM tbl_designations WHERE department_id='$iDepartment')
		         ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		if ($iCount2 > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "Employee Name    ");
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "Designation      ");
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "Department       ");
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "Mobile No ");
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, "Extension ");

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
					('A'.$iRow.':E'.$iRow)
			);

			$iRow ++;


			for ($j = 0; $j < $iCount2; $j ++)
			{
				$sName        = $objDb2->getField($j, 'name');
				$iDesignation = $objDb2->getField($j, 'designation_id');
				$sMobile      = $objDb2->getField($j, 'mobile');
				$sExtension   = $objDb2->getField($j, 'phone_ext');


				$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, $sName);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, $sDesignationsList[$iDesignation]);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, $sDepartment);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, " {$sMobile}");
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, " {$sExtension}");

				$objPHPExcel->getActiveSheet()->duplicateStyleArray(
						array(
							'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
							)
						),
						('A'.$iRow.':E'.$iRow)
				);

				$iRow ++;
			}


			$iRow ++;
		}
	}

	// Set column widths
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);


	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Extensions List');

	include 'PHPExcel/IOFactory.php';


	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename=\"matrix-extensions-list.xlsx\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save("php://output");



	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>