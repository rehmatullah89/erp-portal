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

	$sSQL = "SELECT * FROM tbl_merchandisings WHERE id='$Id'";
	$objDb->query($sSQL);

	$iWashId           = $objDb->getField(0, 'wash_id');
	$sStatus           = $objDb->getField(0, 'status');
	$sSampleSizes      = $objDb->getField(0, 'sample_sizes');
	$sSampleQuantities = $objDb->getField(0, 'sample_quantities');
	$iEntryTime        = @strtotime($objDb->getField(0, 'modified'));
	$iOrderTime        = @strtotime(date("2010-01-20 23:59:59"));
	$sOrderField       = "id";

	if ($iEntryTime > $iOrderTime)
		$sOrderField = "display_order";

	$sSizes       = "";
	$iSampleSizes = array( );

	$sSQL = "SELECT size FROM tbl_sampling_sizes WHERE id IN ($sSampleSizes) ORDER BY $sOrderField";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sSizes        .= (", ".$objDb->getField($i, 0));
			$iSampleSizes[] = $objDb->getField($i, 0);
		}

		$sSizes = substr($sSizes, 2);
	}

	$iSizesCount       = count($iSampleSizes);
	$iSampleQuantities = @explode(",", $sSampleQuantities);
	$iQuantitiesCount  = count($iSampleQuantities);


	$iRowNo = 8;
	$iColNo = 65;

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'borders' => array(
					'top'   => array('style' => PHPExcel_Style_Border::BORDER_THIN)
				)
			),
			'A'.$iRowNo.':K'.$iRowNo
	);

	for ($i = 1; $i <= 4; $i ++)
	{
		if ($i <= 2)
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$iRowNo.':D'.$iRowNo);

		else
			$objPHPExcel->getActiveSheet()->mergeCells('C'.$iRowNo.':D'.$iRowNo);

		if ($i == 4)
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$iRowNo.':B'.$iRowNo);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				),
				'A'.$iRowNo.':D'.$iRowNo
		);

		if ($i > 1)
		{
			$objPHPExcel->getActiveSheet()->duplicateStyleArray(
				array(
						'borders' => array(
							'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
						)
					),
					'A'.$iRowNo.':D'.$iRowNo
			);
		}


		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					)
				),
				'E'.$iRowNo.':E'.$iRowNo
		);

		if ( ($i % 2) == 0)
		{
			$objPHPExcel->getActiveSheet()->duplicateStyleArray(
				array(
						'borders' => array(
							'bottom'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						)
					),
					'E'.$iRowNo.':E'.$iRowNo
			);
		}


		$objPHPExcel->getActiveSheet()->mergeCells('F'.$iRowNo.':G'.$iRowNo);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					)
				),
				'F'.$iRowNo.':G'.$iRowNo
		);

		if ( ($i % 2) == 0)
		{
			$objPHPExcel->getActiveSheet()->duplicateStyleArray(
				array(
						'borders' => array(
							'bottom'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						)
					),
					'F'.$iRowNo.':G'.$iRowNo
			);
		}


		$objPHPExcel->getActiveSheet()->mergeCells('H'.$iRowNo.':I'.$iRowNo);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					)
				),
				'H'.$iRowNo.':I'.$iRowNo
		);

		if ( ($i % 2) == 0)
		{
			$objPHPExcel->getActiveSheet()->duplicateStyleArray(
				array(
						'borders' => array(
							'bottom'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						)
					),
					'H'.$iRowNo.':I'.$iRowNo
			);
		}


		$objPHPExcel->getActiveSheet()->mergeCells('J'.$iRowNo.':K'.$iRowNo);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					)
				),
				'J'.$iRowNo.':K'.$iRowNo
		);

		if ( ($i % 2) == 0)
		{
			$objPHPExcel->getActiveSheet()->duplicateStyleArray(
				array(
						'borders' => array(
							'bottom'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						)
					),
					'J'.$iRowNo.':K'.$iRowNo
			);
		}

		$iRowNo ++;
	}


	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'borders' => array(
					'left'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'right'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
				)
			),
			'B'.($iRowNo - 2).':B'.($iRowNo - 2)
	);

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'borders' => array(
					'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN)
				)
			),
			'B'.($iRowNo - 1).':B'.($iRowNo - 1)
	);

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'borders' => array(
					'top'   => array('style' => PHPExcel_Style_Border::BORDER_THIN)
				)
			),
			'A'.$iRowNo.':K'.$iRowNo
	);


	$objPHPExcel->getActiveSheet()->setCellValue('A'.($iRowNo - 4), 'STYLE NAME:');
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($iRowNo - 2), 'MSC:');
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($iRowNo - 1), 'CATEGORY:');
	$objPHPExcel->getActiveSheet()->setCellValue('C'.($iRowNo - 2), 'SOURCE TYPE CODE:');
	$objPHPExcel->getActiveSheet()->setCellValue('E'.($iRowNo - 4), 'DEV REGION:');
	$objPHPExcel->getActiveSheet()->setCellValue('E'.($iRowNo - 2), 'SIZE RANGE:');
	$objPHPExcel->getActiveSheet()->setCellValue('F'.($iRowNo - 4), 'DEVELOPER:');
	$objPHPExcel->getActiveSheet()->setCellValue('F'.($iRowNo - 2), 'TECH DESIGNER:');
	$objPHPExcel->getActiveSheet()->setCellValue('H'.($iRowNo - 4), 'FIELD MERCHANDISER:');
	$objPHPExcel->getActiveSheet()->setCellValue('H'.($iRowNo - 2), 'SEASON:');
	$objPHPExcel->getActiveSheet()->setCellValue('J'.($iRowNo - 4), 'STYLE #:');
	$objPHPExcel->getActiveSheet()->setCellValue('J'.($iRowNo - 2), 'BOM:');

	$objPHPExcel->getActiveSheet()->setCellValue('A'.($iRowNo - 3), $sStyleName);
	$objPHPExcel->getActiveSheet()->setCellValue('E'.($iRowNo - 1), $sSizes);
	$objPHPExcel->getActiveSheet()->setCellValue('H'.($iRowNo - 1), $sSeason);
	$objPHPExcel->getActiveSheet()->setCellValue('J'.($iRowNo - 3), $sStyle);

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'font' => array(
					'size'   => 9
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
			),
			'A'.($iRowNo - 4).':K'.$iRowNo
	);

	$objPHPExcel->getActiveSheet()->getStyle('J'.($iRowNo - 4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('J'.($iRowNo - 3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('J'.($iRowNo - 2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



	// Measurement Specs
	$iRowNo += 4;


	$sSQL = "SELECT ms.data, ms.tolerance, mp.point_id AS _PointId, mp.point AS _Point
	         FROM tbl_measurement_specs ms, tbl_measurement_points mp
	         WHERE ms.point_id=mp.id AND ms.merchandising_id='$Id'
	         ORDER BY ms.id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iIndex = 0;
	$iTop   = $iRowNo;

	for($i = 0; $i < $iCount; $i ++)
	{
		$sPointId   = $objDb->getField($i, '_PointId');
		$sPoint     = $objDb->getField($i, '_Point');
		$sTolerance = $objDb->getField($i, 'tolerance');
		$sData      = $objDb->getField($i, 'data');

		$sData = @explode(",", $sData);

		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo).$iRowNo, $sPointId);

		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + 1).$iRowNo, @utf8_encode($sPoint));
		$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo + 1).$iRowNo.':'.getExcelCol($iColNo + 4).$iRowNo);

		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + 6).$iRowNo, $sTolerance);

		$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo).$iRowNo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo + 1).$iRowNo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo + 6).$iRowNo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'left'    => array('style' => PHPExcel_Style_Border::BORDER_THICK),
						'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				),
				getExcelCol($iColNo).$iRowNo.':'.getExcelCol($iColNo).$iRowNo
		);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				),
				getExcelCol($iColNo + 1).$iRowNo.':'.getExcelCol($iColNo + 4).$iRowNo
		);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				),
				getExcelCol($iColNo + 5).$iRowNo.':'.getExcelCol($iColNo + 5).$iRowNo
		);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				),
				getExcelCol($iColNo + 6).$iRowNo.':'.getExcelCol($iColNo + 6).$iRowNo
		);


		$iIndex  = 7;
		$iColumn = 0;

		for ($j = 0; $j < $iSizesCount; $j ++)
		{
			$fBenchmark = $sData[$iColumn];

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + $iIndex).$iRowNo, $fBenchmark);
			$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo + $iIndex).$iRowNo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$objPHPExcel->getActiveSheet()->duplicateStyleArray(
				array(
						'borders' => array(
							'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN),
							'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
							'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
							'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
						)
					),
					getExcelCol($iColNo + $iIndex).$iRowNo.':'.getExcelCol($iColNo + $iIndex).$iRowNo
			);

			$iIndex ++;
			$iColumn ++;


			for ($k = 1; $k <= $iSampleQuantities[$j]; $k ++)
			{
				$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + $iIndex).$iRowNo, $sData[$iColumn]);
				$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo + $iIndex).$iRowNo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				$objPHPExcel->getActiveSheet()->duplicateStyleArray(
					array(
							'borders' => array(
								'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
							)
						),
						getExcelCol($iColNo + $iIndex).$iRowNo.':'.getExcelCol($iColNo + $iIndex).$iRowNo
				);

				$iIndex ++;
				$fDifference = "";

				if (strtolower($sData[$iColumn]) != "ok" && $sData[$iColumn] != "")
					$fDifference = (floatval($sData[$iColumn]) - floatval($fBenchmark));

				$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + $iIndex).$iRowNo, $fDifference);
				$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo + $iIndex).$iRowNo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				$objPHPExcel->getActiveSheet()->duplicateStyleArray(
					array(
							'borders' => array(
								'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
							)
						),
						getExcelCol($iColNo + $iIndex).$iRowNo.':'.getExcelCol($iColNo + $iIndex).$iRowNo
				);

				$iIndex ++;
				$iColumn ++;
			}
		}


		$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo + $iIndex).$iRowNo.':'.getExcelCol($iColNo + $iIndex + 3).$iRowNo);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THICK),
						'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				),
				getExcelCol($iColNo + $iIndex).$iRowNo.':'.getExcelCol($iColNo + $iIndex + 3).$iRowNo
		);

		$iRowNo ++;
	}

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
		array(
				'borders' => array(
					'left'    => array('style' => PHPExcel_Style_Border::BORDER_THICK),
					'top'     => array('style' => PHPExcel_Style_Border::BORDER_THICK),
					'right'   => array('style' => PHPExcel_Style_Border::BORDER_THICK),
					'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THICK)
				)
			),
			getExcelCol($iColNo).$iTop.':'.getExcelCol($iColNo + $iIndex + 3).($iRowNo - 1)
	);

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
		array(
				'borders' => array(
					'left'    => array('style' => PHPExcel_Style_Border::BORDER_THICK),
					'top'     => array('style' => PHPExcel_Style_Border::BORDER_THICK),
					'right'   => array('style' => PHPExcel_Style_Border::BORDER_THICK),
					'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THICK)
				)
			),
			getExcelCol($iColNo).($iTop - 1).':'.getExcelCol($iColNo + $iIndex + 3).($iTop - 1)
	);



	// header top
	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
		array(
				'borders' => array(
					'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN)
				)
			),
			getExcelCol($iColNo).($iTop - 3).':'.getExcelCol($iColNo + $iIndex + 3).($iTop - 1)
	);

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo).($iTop - 3), 'MEASURED BY:');
	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo).($iTop - 3).':'.getExcelCol($iColNo + 1).($iTop - 3));
	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo).($iTop - 2).':'.getExcelCol($iColNo + 1).($iTop - 2));

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + 2).($iTop - 3), 'MEASURER:');
	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo + 2).($iTop - 3).':'.getExcelCol($iColNo + 4).($iTop - 3));
	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo + 2).($iTop - 2).':'.getExcelCol($iColNo + 4).($iTop - 2));

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
		array(
				'borders' => array(
					'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN)
				)
			),
			getExcelCol($iColNo + 2).($iTop - 3).':'.getExcelCol($iColNo + 4).($iTop - 1)
	);

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + 5).($iTop - 3), 'FACTORY:');
	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo + 5).($iTop - 3).':'.getExcelCol($iColNo + 6).($iTop - 3));
	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo + 5).($iTop - 2).':'.getExcelCol($iColNo + 6).($iTop - 2));

	$sWash = getDbValue("wash", "tbl_sampling_washes", "id='$iWashId'");
	$sWash = (($sWash == "N/A") ? "" : ": {$sWash}");

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + 7).($iTop - 3), $sSampleType." ".$sWash);
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + 7).($iTop - 2), 'DATE: '.date("d-M-Y", $iEntryTime));
	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo + 7).($iTop - 3).':'.getExcelCol($iColNo + $iIndex - 1).($iTop - 3));
	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo + 7).($iTop - 2).':'.getExcelCol($iColNo + $iIndex - 1).($iTop - 2));

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
		array(
				'borders' => array(
					'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN)
				)
			),
			getExcelCol($iColNo + 7).($iTop - 3).':'.getExcelCol($iColNo + $iIndex - 1).($iTop - 1)
	);

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + $iIndex).($iTop - 3), 'COMMENTS BY:');
	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo + $iIndex).($iTop - 3).':'.getExcelCol($iColNo + $iIndex + 3).($iTop - 3));
	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo + $iIndex).($iTop - 2).':'.getExcelCol($iColNo + $iIndex + 3).($iTop - 2));



	// measurement header settings
	$sLabels = array( );

	for ($i = 0; $i < $iSizesCount; $i ++)
	{
		$sLabels[] = $iSampleSizes[$i];

		for ($j = 1; $j <= $iSampleQuantities[$i]; $j ++)
		{
			$sLabels[] = $j;
			$sLabels[] = "+/-";
		}
	}


	for ($i = ($iColNo + 5); $i <= ($iColNo + $iIndex); $i ++)
	{
		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					)
				),
				getExcelCol($i).($iTop - 1).':'.getExcelCol($i).($iTop - 1)
		);

		$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($i).($iTop - 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


		$sValue = "";

		if ($i >= ($iColNo + 7))
			$sValue = $sLabels[$i - 72];

		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($i).($iTop - 1), $sValue);
	}


	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo).($iTop - 1), 'MEAS. CODE');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + 1).($iTop - 1), 'POM DESCRIPTION');
	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo + 1).($iTop - 1).':'.getExcelCol($iColNo + 4).($iTop - 1));

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + 6).($iTop - 1), 'TOL +/-');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + $iIndex).($iTop - 1), 'MEASUREMENT COMMENTS');
	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo + $iIndex).($iTop - 1).':'.getExcelCol($iColNo + $iIndex + 3).($iTop - 1));

	$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo).($iTop - 1))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


	for ($i = $iColNo; $i <= ($iColNo + $iIndex + 3); $i ++)
		$objPHPExcel->getActiveSheet()->getColumnDimension(getExcelCol($i))->setWidth(12);



	$sSQL = "SELECT * FROM tbl_comment_sheets WHERE merchandising_id='$Id'";
	$objDb->query($sSQL);

	$sMerchComments   = $objDb->getField(0, "merch_comments");
	$sSpecComments    = $objDb->getField(0, "spec_comments");
	$sOtherComments   = $objDb->getField(0, "other_comments");
	$sFittingComments = $objDb->getField(0, "fitting_comments");
	$sNoteSuggestions = $objDb->getField(0, "note_suggestions");


	// Style Discussion
	$iRowNo += 2;

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRowNo, 'STYLE DISCUSSION');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$iRowNo)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$iRowNo.':K'.$iRowNo);

	$iRowNo ++;

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRowNo, 'PPC / FTY              PPC / FTY QUESTIONS                                                                                               HO RESPONSES');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$iRowNo)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$iRowNo.':K'.$iRowNo);

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'borders' => array(
					'bottom'   => array('style' => PHPExcel_Style_Border::BORDER_THICK)
				)
			),
			'A'.$iRowNo.':K'.$iRowNo
	);


	for ($i = 1; $i <= 8; $i ++)
	{
		$iRowNo ++;

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'left'    => array('style' => PHPExcel_Style_Border::BORDER_THICK),
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				),
				'A'.$iRowNo.':A'.$iRowNo
		);


		$objPHPExcel->getActiveSheet()->mergeCells('B'.$iRowNo.':F'.$iRowNo);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$iRowNo.':F'.$iRowNo)->getAlignment()->setWrapText(true);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				),
				'B'.$iRowNo.':F'.$iRowNo
		);


		$iLines = 1;

		if ($i == 1)
		{
			$sValue = wordwrap($sMerchComments, 60, "\r\n");
			$iLines = (substr_count($sValue, "\r\n") + 1);

			$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRowNo, utf8_encode($sMerchComments));
		}

		else if ($i == 2)
		{
			$sValue = wordwrap($sSpecComments, 60, "\r\n");
			$iLines = (substr_count($sValue, "\r\n") + 1);

			$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRowNo, utf8_encode($sSpecComments));
		}

		else if ($i == 3)
		{
			$sValue = wordwrap($sOtherComments, 65, "\r\n");
			$iLines = (substr_count($sValue, "\r\n") + 1);

			$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRowNo, utf8_encode($sOtherComments));
		}

		else if ($i == 4)
		{
			$sValue = wordwrap($sFittingComments, 60, "\r\n");
			$iLines = (substr_count($sValue, "\r\n") + 1);

			$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRowNo, utf8_encode($sFittingComments));
		}

		else if ($i == 5)
		{
			$sValue = wordwrap($sNoteSuggestions, 60, "\r\n");
			$iLines = (substr_count($sValue, "\r\n") + 1);

			$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRowNo, utf8_encode($sNoteSuggestions));
		}

		if ($i <= 5)
		{
			$objPHPExcel->getActiveSheet()->getRowDimension($iRowNo)->setRowHeight($iLines * 16);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$iRowNo.':F'.$iRowNo)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$iRowNo)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		}


		$objPHPExcel->getActiveSheet()->mergeCells('G'.$iRowNo.':K'.$iRowNo);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THICK),
						'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				),
				'G'.$iRowNo.':K'.$iRowNo
		);
	}


	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'borders' => array(
					'bottom'   => array('style' => PHPExcel_Style_Border::BORDER_THICK)
				)
			),
			'A'.$iRowNo.':K'.$iRowNo
	);



	// Prototype Comments
	$iRowNo += 2;

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRowNo, 'PROTOTYPE COMMENTS');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$iRowNo)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$iRowNo.':K'.$iRowNo);

	$iRowNo ++;

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRowNo, 'HO / PCC            FABRIC TRIMS');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$iRowNo)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$iRowNo.':K'.$iRowNo);

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'borders' => array(
					'bottom'   => array('style' => PHPExcel_Style_Border::BORDER_THICK)
				)
			),
			'A'.$iRowNo.':K'.$iRowNo
	);


	for ($i = 1; $i <= 2; $i ++)
	{
		$iRowNo ++;

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'left'    => array('style' => PHPExcel_Style_Border::BORDER_THICK),
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				),
				'A'.$iRowNo.':A'.$iRowNo
		);


		$objPHPExcel->getActiveSheet()->mergeCells('B'.$iRowNo.':K'.$iRowNo);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THICK),
						'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				),
				'B'.$iRowNo.':K'.$iRowNo
		);
	}


	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'borders' => array(
					'bottom'   => array('style' => PHPExcel_Style_Border::BORDER_THICK)
				)
			),
			'A'.$iRowNo.':K'.$iRowNo
	);



	$iRowNo += 2;

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRowNo, 'HO / PCC            GRAPHICS / LOGOS');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$iRowNo)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$iRowNo.':K'.$iRowNo);

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'borders' => array(
					'bottom'   => array('style' => PHPExcel_Style_Border::BORDER_THICK)
				)
			),
			'A'.$iRowNo.':K'.$iRowNo
	);


	for ($i = 1; $i <= 2; $i ++)
	{
		$iRowNo ++;

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'left'    => array('style' => PHPExcel_Style_Border::BORDER_THICK),
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				),
				'A'.$iRowNo.':A'.$iRowNo
		);


		$objPHPExcel->getActiveSheet()->mergeCells('B'.$iRowNo.':K'.$iRowNo);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THICK),
						'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				),
				'B'.$iRowNo.':K'.$iRowNo
		);
	}


	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'borders' => array(
					'bottom'   => array('style' => PHPExcel_Style_Border::BORDER_THICK)
				)
			),
			'A'.$iRowNo.':K'.$iRowNo
	);



	$iRowNo += 2;

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRowNo, 'HO / PCC            FIT / SPEC / CONSTRUCTION');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$iRowNo)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$iRowNo.':K'.$iRowNo);

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'borders' => array(
					'bottom'   => array('style' => PHPExcel_Style_Border::BORDER_THICK)
				)
			),
			'A'.$iRowNo.':K'.$iRowNo
	);


	for ($i = 1; $i <= 2; $i ++)
	{
		$iRowNo ++;

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'left'    => array('style' => PHPExcel_Style_Border::BORDER_THICK),
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				),
				'A'.$iRowNo.':A'.$iRowNo
		);


		$objPHPExcel->getActiveSheet()->mergeCells('B'.$iRowNo.':K'.$iRowNo);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THICK),
						'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				),
				'B'.$iRowNo.':K'.$iRowNo
		);
	}


	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'borders' => array(
					'bottom'   => array('style' => PHPExcel_Style_Border::BORDER_THICK)
				)
			),
			'A'.$iRowNo.':K'.$iRowNo
	);


	$iRowNo += 2;

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRowNo, 'REFER TO PROTO REQUEST PAGE FOR ACTION PLAN');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$iRowNo)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$iRowNo.':K'.$iRowNo);


	$sSQL = "SELECT (SELECT name FROM tbl_users WHERE id=tbl_comment_sheets.modified_by) AS _Person FROM tbl_comment_sheets WHERE merchandising_id='$Id'";
	$objDb->query($sSQL);

	$sPerson = $objDb->getField(0, "_Person");

	$iRowNo += 2;


	switch ($sStatus)
	{
		case "A" : $sStatus = "Approved"; break;
		case "R" : $sStatus = "Rejected"; break;
		case "W" : $sStatus = "Hold"; break;
	}

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRowNo, 'Status');
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($iRowNo + 1), $sStatus);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($iRowNo + 3), $sPerson);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$iRowNo)->getFont()->setBold(true);


	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(100);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0);
?>