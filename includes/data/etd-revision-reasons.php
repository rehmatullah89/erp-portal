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

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree");
	$objPHPExcel->getProperties()->setTitle("ETD Revision Reasons");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("ETD Revision Reasons");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");

	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getActiveSheet()->setCellValue('A2', "M A T R I X    S O U R C I N G");
	$objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(28);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->setCellValue('A3', "ETD REVISION REASONS");
	$objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(18);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


	$objPHPExcel->getActiveSheet()->setCellValue('A5', "Category");
	$objPHPExcel->getActiveSheet()->setCellValue('B5', "Sub-Category");
	$objPHPExcel->getActiveSheet()->setCellValue('C5', "Reason");
	$objPHPExcel->getActiveSheet()->setCellValue('D5', "Reason Code");

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
			('A5:D5')
	);



	$sSQL = "SELECT id, code, reason FROM tbl_etd_revision_reasons WHERE parent_id='0' ORDER BY reason";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iRow   = 6;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iSuperParentId     = $objDb->getField($i, 'id');
		$sSuperParentCode   = $objDb->getField($i, 'code');
		$sSuperParentReason = $objDb->getField($i, 'reason');

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, $sSuperParentReason);


		$sSQL = "SELECT id, code, reason FROM tbl_etd_revision_reasons WHERE parent_id='$iSuperParentId' ORDER BY reason";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iParentId     = $objDb2->getField($j, 'id');
			$sParentCode   = $objDb2->getField($j, 'code');
			$sParentReason = $objDb2->getField($j, 'reason');

			$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, $sParentReason);


			$sSQL = "SELECT code, reason FROM tbl_etd_revision_reasons WHERE parent_id='$iParentId' ORDER BY reason";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$sCode   = $objDb3->getField($k, 'code');
				$sReason = $objDb3->getField($k, 'reason');

				$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, $sReason);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "{$sSuperParentCode}{$sParentCode}{$sCode}");

				$objPHPExcel->getActiveSheet()->duplicateStyleArray(
						array(
							'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
							)
						),
						('A'.$iRow.':D'.$iRow)
				);

				$iRow ++;
			}

			$iRow ++;
		}

		$iRow ++;
	}

	// Set column widths
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);

	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('ETD Revision Reasons');

	include 'PHPExcel/IOFactory.php';

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($sExcelFile);
?>