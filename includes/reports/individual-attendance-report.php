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


	$sSQL = "SELECT name, country_id, joining_date, designation_id,
	                (SELECT country FROM tbl_countries WHERE id=tbl_users.country_id) AS _Country
	         FROM tbl_users
	         WHERE id='$Employee'";
	$objDb->query($sSQL);

	$sName        = $objDb->getField(0, 'name');
	$iCountryId   = $objDb->getField(0, 'country_id');
	$sJoiningDate = $objDb->getField(0, "joining_date");
	$sCountry     = $objDb->getField(0, "_Country");
	$iDesignation = $objDb->getField(0, "designation_id");


	$sSQL = "SELECT designation, department_id FROM tbl_designations WHERE id='$iDesignation'";
	$objDb->query($sSQL);

	$sDesignation = $objDb->getField(0, 'designation');
	$iDepartment  = $objDb->getField(0, 'department_id');

	$sDepartment  = getDbValue("department", "tbl_departments", "id='$iDepartment'");



	$sExcelFile = ($sBaseDir."temp/".IO::getFileName($sName).".xlsx");

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
	$objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(28);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->setCellValue('A3', ("ATTENDANCE REPORT FROM ".formatDate($FromDate)." TO ".formatDate($ToDate).$sRegion));
	$objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(18);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->setCellValue('A5', $sName);
	$objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setSize(13);
	$objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('A6', $sDesignation);
	$objPHPExcel->getActiveSheet()->setCellValue('A7', $sDepartment);
	$objPHPExcel->getActiveSheet()->setCellValue('A8', $sCountry);

	$objPHPExcel->getActiveSheet()->mergeCells('A5:D5');
	$objPHPExcel->getActiveSheet()->mergeCells('A6:D6');
	$objPHPExcel->getActiveSheet()->mergeCells('A7:D7');
	$objPHPExcel->getActiveSheet()->mergeCells('A8:D8');


	$iRow = 10;

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "Date              ");
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "Time-In           ");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "Time-Out          ");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "Remarks           ");

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
			('A'.$iRow.':D'.$iRow)
	);

	$iRow ++;


	if (strtotime($FromDate) < strtotime(sJoiningDate))
		$FromDate = $sJoiningDate;

	$iCount = (((strtotime($ToDate) - strtotime($FromDate)) / 86400) + 1);

	@list($iYear, $iMonth, $iDay) = @explode("-", $FromDate);

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sDate    = date("Y-m-d", mktime(0, 0, 0, $iMonth, ($iDay + $i), $iYear));
		$iWeekDay = date("N", strtotime($sDate));

		if (strtotime($sDate) > strtotime(date("Y-m-d")))
			break;

		if (strtotime($sDate) < strtotime($sJoiningDate))
			continue;


		$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, formatDate($sDate));
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "");
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "");
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "");


		$sSQL = "SELECT leave_type_id FROM tbl_user_leaves WHERE user_id='$Employee' AND ('$sDate' BETWEEN from_date AND to_date)";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, getDbValue("type", "tbl_leave_types", ("id='".$objDb->getField(0, 0)."'")));

		else
		{
			$sSQL = "SELECT * FROM tbl_attendance WHERE user_id='$Employee' AND `date`='$sDate'";
			$objDb->query($sSQL);

			$iEntries = $objDb->getCount( );


			if ($iEntries == 0)
			{
				//if ( ($iCountryId == 18 && $iWeekDay != 5 && $iWeekDay != 6) || ($iCountryId != 18 && $iWeekDay < 6) )

				if ( (strtotime($sDate) <= strtotime("2010-06-18") && $iCountryId == 18 && $iWeekDay != 5 && $iWeekDay != 6) ||
					 (strtotime($sDate) > strtotime("2010-06-18") && $iCountryId == 18 && $iWeekDay < 6) ||
					 ($iCountryId != 18 && $iWeekDay < 6) )
				{
					$sSQL = "SELECT * FROM tbl_holidays WHERE `date`='$sDate' AND country_id='$iCountryId'";
					$objDb->query($sSQL);

					if ($objDb->getCount( ) == 0)
						$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "Absent");

					else
					{
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "");

						continue;
					}
				}

				else
				{
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "");

					continue;
				}
			}

			else
			{
				for ($j = 0; $j < $iEntries; $j ++)
				{
					$sTimeIn  = $objDb->getField($j, 'time_in');
					$sTimeOut = $objDb->getField($j, 'time_out');
					$sRemarks = $objDb->getField($j, 'remarks');

					$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, $sTimeIn);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, $sTimeOut);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, $sRemarks);

					if ($j < ($iEntries - 1))
						$iRow ++;
				}
			}
		}

		$iRow ++;
	}


	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				)
			),
			('A10:D'.$iRow)
	);

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
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
			('A'.$iRow.':D'.$iRow)
	);



	$iRow += 5;


	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "Working Days");
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "Total Working Hours");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "Actual Working Hours");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "Excessive Working Hours");

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
			('A'.$iRow.':D'.$iRow)
	);


	$iRow ++;



	$iActualWorkingHours  = getDbValue("SUM(TIME_TO_SEC(TIMEDIFF(time_out, time_in)))", "tbl_attendance", "user_id='$Employee' AND (`date` BETWEEN '$FromDate' AND '$ToDate') AND `date` < CURDATE( )");
	$iActualWorkingHours /= 3600;


	$iDays        = ((strtotime($ToDate) - strtotime($FromDate)) / 86400);
	$iWorkingDays = 0;

	@list($iYear, $iMonth, $iDay) = @explode("-", $FromDate);

	for ($k = 0; $k <= $iDays; $k ++)
	{
		$sDate = date("Y-m-d", mktime(0, 0, 0, $iMonth, ($iDay + $k), $iYear));

		if (strtotime($sDate) > strtotime(date("Y-m-d")))
			break;

		if (strtotime($sDate) < strtotime($sJoiningDate))
			continue;


		$iWeekDay = date("N", strtotime($sDate));

		if ($iWeekDay <= 5)
			$iWorkingDays ++;
	}


	$iTotalWorkingHours = ($iWorkingDays * 8.5);


	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, $iWorkingDays);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, formatNumber($iTotalWorkingHours));
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, formatNumber($iActualWorkingHours));
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, formatNumber($iActualWorkingHours - $iTotalWorkingHours));

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				)
			),
			('A'.$iRow.':D'.$iRow)
	);




	$iRow += 5;


	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "Days Worked");
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "AVG Time-In");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "AVG Time-Out");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "AVG Working Hours");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, "Late Days ");
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, "Leaves ");
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, "Absents ");

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


	$sSQL = "SELECT COUNT(DISTINCT(`date`)) AS _WorkingDays,
					SEC_TO_TIME(AVG(TIME_TO_SEC(time_in))) AS _AvgTimeIn,
					SEC_TO_TIME(AVG(TIME_TO_SEC(time_out))) AS _AvgTimeOut,
					SEC_TO_TIME(AVG(TIME_TO_SEC(TIMEDIFF(time_out, time_in)))) AS _AvgHours
			 FROM tbl_attendance
			 WHERE user_id='$Employee' AND (`date` BETWEEN '$FromDate' AND '$ToDate') AND `date` < CURDATE( )";
	$objDb->query($sSQL);

	$iWorkingDays = $objDb->getField(0, '_WorkingDays');
	$sAvgTimeIn   = $objDb->getField(0, '_AvgTimeIn');
	$sAvgTimeOut  = $objDb->getField(0, '_AvgTimeOut');
	$sAvgHours    = $objDb->getField(0, '_AvgHours');

	if (strlen($sAvgTimeIn) >= 5)
		$sAvgTimeIn = substr($sAvgTimeIn, 0, 5);

	if (strlen($sAvgTimeOut) >= 5)
		$sAvgTimeOut = substr($sAvgTimeOut, 0, 5);

	if (strlen($sAvgHours) >= 5)
		$sAvgHours = substr($sAvgHours, 0, 5);


	$sSQL = "SELECT COUNT(1) FROM tbl_attendance WHERE user_id='$Employee' AND (`date` BETWEEN '$FromDate' AND '$ToDate') AND `date` < CURDATE( ) AND `time_in` >= '09:30:00' AND `entry`='0'";
	$objDb->query($sSQL);

	$iLateDays = (int)$objDb->getField(0, 0);


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


		$sSQL = "SELECT * FROM tbl_user_leaves WHERE user_id='$Employee' AND ('$sDate' BETWEEN from_date AND to_date)";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$iLeaves ++;

			continue;
		}


		$sSQL = "SELECT * FROM tbl_attendance WHERE user_id='$Employee' AND `date`='$sDate'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			//if ( ($iCountryId == 18 && $iWeekDay != 5 && $iWeekDay != 6) || ($iCountryId != 18 && $iWeekDay < 6) )

			if ( (strtotime($sDate) <= strtotime("2010-06-18") && $iCountryId == 18 && $iWeekDay != 5 && $iWeekDay != 6) ||
				 (strtotime($sDate) > strtotime("2010-06-18") && $iCountryId == 18 && $iWeekDay < 6) ||
				 ($iCountryId != 18 && $iWeekDay < 6) )
			{
				$sSQL = "SELECT * FROM tbl_holidays WHERE `date`='$sDate' AND country_id='$iCountryId'";
				$objDb->query($sSQL);

				if ($objDb->getCount( ) == 0)
					$iAbsents ++;
			}
		}
	}

	$iRow ++;

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, $iWorkingDays);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, $sAvgTimeIn);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, $sAvgTimeOut);
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, $sAvgHours);
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, $iLateDays);
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, $iLeaves);
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, $iAbsents);

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				)
			),
			('A'.$iRow.':G'.$iRow)
	);






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
	$objPHPExcel->getActiveSheet()->setTitle('Attendance Report');

	include 'PHPExcel/IOFactory.php';

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($sExcelFile);
?>