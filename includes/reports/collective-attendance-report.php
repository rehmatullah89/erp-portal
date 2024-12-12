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

	$sDesignationsList = getList("tbl_designations", "id", "designation");

	$sRegionSql = "";
	$sRegion    = "";

	if ($Region > 0)
	{
		$sRegionSql = " AND country_id='$Region' ";

		$sSQL = "SELECT country FROM tbl_countries WHERE id='$Region'";
		$objDb->query($sSQL);

		$sRegion = ("  (".$objDb->getField(0, 0).")");
	}

	$sExcelFile = ($sBaseDir."temp/attendance-report.xlsx");

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Matrix Sourcing");
	$objPHPExcel->getProperties()->setLastModifiedBy("Matrix Sourcing");
	$objPHPExcel->getProperties()->setTitle("Attendance Report");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("Attendance Report");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");

	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getActiveSheet()->setCellValue('A2', "M A T R I X    S O U R C I N G");
	$objPHPExcel->getActiveSheet()->mergeCells('A2:K2');
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(28);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->setCellValue('A3', ("ATTENDANCE REPORT FROM ".formatDate($FromDate)." TO ".formatDate($ToDate).$sRegion));
	$objPHPExcel->getActiveSheet()->mergeCells('A3:K3');
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(18);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


	$sSQL = "SELECT id, department FROM tbl_departments ORDER BY department";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$iRow = 5;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDepartmentId = $objDb->getField($i, 'id');
		$sDepartment   = $objDb->getField($i, 'department');

		$sSQL = "SELECT id, name, designation_id, country_id, date_time
		         FROM tbl_users
		         WHERE status='A' AND designation_id IN (SELECT id FROM tbl_designations WHERE department_id='$iDepartmentId') $sRegionSql ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		if ($iCount2 > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "Department       ");
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "Designation      ");
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "Employee Name    ");
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "Working Days ");
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, "AVG Time-In ");
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, "AVG Time-Out ");
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, "AVG Working Hours");
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$iRow, "Late Days ");
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$iRow, "Leaves ");
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$iRow, "Absents ");
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$iRow, "Remarks            ");

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
					('A'.$iRow.':K'.$iRow)
			);

			$iRow ++;


			for ($j = 0; $j < $iCount2; $j ++)
			{
				$iUserId      = $objDb2->getField($j, 'id');
				$sName        = $objDb2->getField($j, 'name');
				$iDesignation = $objDb2->getField($j, 'designation_id');
				$iCountryId   = $objDb2->getField($j, 'country_id');
				$sJoiningDate = $objDb2->getField($j, "date_time");


				$sSQL = "SELECT COUNT(DISTINCT(`date`)) AS _WorkingDays,
				                SEC_TO_TIME(AVG(TIME_TO_SEC(time_in))) AS _AvgTimeIn,
				                SEC_TO_TIME(AVG(TIME_TO_SEC(time_out))) AS _AvgTimeOut,
				                SEC_TO_TIME(AVG(TIME_TO_SEC(TIMEDIFF(time_out, time_in)))) AS _AvgHours
				         FROM tbl_attendance
				         WHERE user_id='$iUserId' AND (`date` BETWEEN '$FromDate' AND '$ToDate') AND `date` < CURDATE( )";
				$objDb3->query($sSQL);

				$iWorkingDays = $objDb3->getField(0, '_WorkingDays');
				$sAvgTimeIn   = $objDb3->getField(0, '_AvgTimeIn');
				$sAvgTimeOut  = $objDb3->getField(0, '_AvgTimeOut');
				$sAvgHours    = $objDb3->getField(0, '_AvgHours');

				if (strlen($sAvgTimeIn) >= 5)
					$sAvgTimeIn = substr($sAvgTimeIn, 0, 5);

				if (strlen($sAvgTimeOut) >= 5)
					$sAvgTimeOut = substr($sAvgTimeOut, 0, 5);

				if (strlen($sAvgHours) >= 5)
					$sAvgHours = substr($sAvgHours, 0, 5);


				$sSQL = "SELECT COUNT(1) FROM tbl_attendance WHERE user_id='$iUserId' AND (`date` BETWEEN '$FromDate' AND '$ToDate') AND `time_in` >= '09:30:00' AND `date` < CURDATE( ) AND `time_in` >= '09:30:00' AND `entry`='0'";
				$objDb3->query($sSQL);

				$iLateDays = $objDb3->getField(0, 0);


				$sSQL = "SELECT DISTINCT(remarks) FROM tbl_attendance WHERE user_id='$iUserId' AND (`date` BETWEEN '$FromDate' AND '$ToDate') AND `date` < CURDATE( )";
				$objDb3->query($sSQL);

				$iCount3  = $objDb3->getCount( );
				$sRemarks = "";

				for ($k = 0; $k < $iCount3; $k ++)
				{
					if ($objDb3->getField($k, 0) != "Auto Time-Out")
						$sRemarks .= (" ".$objDb3->getField($k, 0));
				}


				$iLeaves  = 0;
				$iAbsents = 0;
				$iDays    = ((strtotime($ToDate) - strtotime($FromDate)) / 86400);

				@list($iYear, $iMonth, $iDay) = @explode("-", $FromDate);

				for ($k = 0; $k <= $iDays; $k ++)
				{
					$sDate    = date("Y-m-d", mktime(0, 0, 0, $iMonth, ($iDay + $k), $iYear));
					$iWeekDay = date("N", strtotime($sDate));

					if (strtotime($sDate) > strtotime(date("Y-m-d")))
						break;

					if (strtotime($sDate) < strtotime($sJoiningDate))
						continue;


					$sSQL = "SELECT * FROM tbl_user_leaves WHERE user_id='$iUserId' AND ('$sDate' BETWEEN from_date AND to_date)";
					$objDb3->query($sSQL);

					if ($objDb3->getCount( ) == 1)
					{
						$iLeaves ++;

						continue;
					}


					$sSQL = "SELECT * FROM tbl_attendance WHERE user_id='$iUserId' AND `date`='$sDate'";
					$objDb3->query($sSQL);

					if ($objDb3->getCount( ) == 0)
					{
						//if ( ($iCountryId == 18 && $iWeekDay != 5 && $iWeekDay != 6) || ($iCountryId != 18 && $iWeekDay < 6) )

						if ( (strtotime($sDate) <= strtotime("2010-06-18") && $iCountryId == 18 && $iWeekDay != 5 && $iWeekDay != 6) ||
							 (strtotime($sDate) > strtotime("2010-06-18") && $iCountryId == 18 && $iWeekDay < 6) ||
							 ($iCountryId != 18 && $iWeekDay < 6) )
						{
							$sSQL = "SELECT * FROM tbl_holidays WHERE `date`='$sDate' AND country_id='$iCountryId'";
							$objDb3->query($sSQL);

							if ($objDb3->getCount( ) == 0)
								$iAbsents ++;
						}
					}
				}


				$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, $sDepartment);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, $sDesignationsList[$iDesignation]);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, $sName);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, $iWorkingDays);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, $sAvgTimeIn);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, $sAvgTimeOut);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, $sAvgHours);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$iRow, $iLateDays);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$iRow, $iLeaves);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$iRow, $iAbsents);
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$iRow, $sRemarks);

				$objPHPExcel->getActiveSheet()->duplicateStyleArray(
						array(
							'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
							)
						),
						('A'.$iRow.':K'.$iRow)
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
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);

	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Attendance Report');

	include 'PHPExcel/IOFactory.php';

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($sExcelFile);
?>