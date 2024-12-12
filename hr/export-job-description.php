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


	$Id = IO::intValue("Id");


	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	@require_once 'PHPExcel.php';
	@require_once 'PHPExcel/RichText.php';
	@require_once 'PHPExcel/IOFactory.php';


	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	$objReader   = PHPExcel_IOFactory::createReader('Excel2007');
	$objPHPExcel = $objReader->load($sBaseDir."templates/job-description.xlsx");


	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree");
	$objPHPExcel->getProperties()->setTitle("Job Description");
	$objPHPExcel->getProperties()->setSubject("HR");
	$objPHPExcel->getProperties()->setDescription("Job Description");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("HR");

	$objPHPExcel->getActiveSheet()->setShowGridlines(false);


	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getDefaultStyle()->getFont()->setName("Book Antiqua");
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(11);



	$sSQL = "SELECT name, designation_id, routine_activities, non_routine_activities FROM tbl_users WHERE id='$Id'";
	$objDb->query($sSQL);

	$sName                 = $objDb->getField(0, "name");
	$iDesignation          = $objDb->getField(0, "designation_id");
	$sRoutineActivities    = $objDb->getField(0, 'routine_activities');
	$sNonRoutineActivities = $objDb->getField(0, 'non_routine_activities');


	$sSQL = "SELECT designation, department_id, reporting_to, job_description FROM tbl_designations WHERE id='$iDesignation'";
	$objDb->query($sSQL);

	$sDesignation    = $objDb->getField(0, 'designation');
	$iDepartment     = $objDb->getField(0, 'department_id');
	$iReportingTo    = $objDb->getField(0, 'reporting_to');
	$sJobDescription = $objDb->getField(0, 'job_description');

	$sDepartment  = getDbValue("department", "tbl_departments", "id='$iDepartment'");
	$sReportingTo = getDbValue("designation", "tbl_designations", "id='$iReportingTo'");



	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 2, strtoupper($sName));
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, $sDesignation);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, $sReportingTo);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, $sDepartment);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, formatDate(date("Y-m-d")));


	$iRow = 8;

	$sJobDescription = @str_replace("\r\n\r\n", "\n", $sJobDescription);
	$sJobDescription = @str_replace("\n\n", "\n", $sJobDescription);
	$sJobDescription = @str_replace("\r\n", "\n", $sJobDescription);
	$sJobDescription = @explode("\n", $sJobDescription);


	if (count($sJobDescription) > 0)
	{
		$iCount = count($sJobDescription);

		if ($iCount > 2)
			$objPHPExcel->getActiveSheet()->insertNewRowBefore(($iRow + 1), ($iCount - 2));


		for ($i = 0; $i < $iCount; $i ++)
		{
			$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->mergeCells("A{$iRow}:I{$iRow}");

			if ($iCount > 2)
				$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}:I{$iRow}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);


			$sText = trim($sJobDescription[$i]);

			if (substr($sText, 0, 2) == "h ")
			{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, substr($sText, 2));
				$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getRowDimension($iRow)->setRowHeight(25);
				$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
			}

			else
			{
				while (strpos($sText, '<u title=') !== FALSE)
				{
					$iTipStart    = strpos($sText, '<u title=');
					$iTipEnd      = strpos($sText, '</u>');
					$sTip         = substr($sText, $iTipStart, ($iTipEnd - $iTipStart + 4));

					$iWordStart   = ($iTipStart + 10);
					$iWordEnd     = strpos($sText, '">');
					$sWord        = substr($sText, $iWordStart, ($iWordEnd - $iWordStart));

					$iAbbrevStart = ($iWordEnd + 2);
					$iAbbrevEnd   = $iTipEnd;
					$sAbbrev      = substr($sText, $iAbbrevStart, ($iAbbrevEnd - $iAbbrevStart));

					$sText = str_replace($sTip, "{$sWord} ({$sAbbrev})", $sText);
				}

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, ((substr($sText, 0, 2) == "o ") ? @utf8_encode("          ° ".substr($sText, 2)) : @utf8_encode("» ".$sText)));
				$objPHPExcel->getActiveSheet()->getRowDimension($iRow)->setRowHeight((@ceil(strlen($sText) / 104) * 18));
				$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			}


			$iRow ++;
		}
	}



	$iRow += 3;

	$sRoutineActivities = @str_replace("\r\n\r\n", "\n", $sRoutineActivities);
	$sRoutineActivities = @str_replace("\n\n", "\n", $sRoutineActivities);
	$sRoutineActivities = @str_replace("\r\n", "\n", $sRoutineActivities);
	$sRoutineActivities = @explode("\n", $sRoutineActivities);


	if (count($sRoutineActivities) > 0)
	{
		$iCount = count($sRoutineActivities);

		if ($iCount > 2)
			$objPHPExcel->getActiveSheet()->insertNewRowBefore(($iRow + 1), ($iCount - 2));


		for ($i = 0; $i < $iCount; $i ++)
		{
			$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->mergeCells("A{$iRow}:I{$iRow}");

			if ($iCount > 2)
				$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}:I{$iRow}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);


			$sText = trim($sRoutineActivities[$i]);

			if (substr($sText, 0, 2) == "h ")
			{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, substr($sText, 2));
				$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getRowDimension($iRow)->setRowHeight(25);
				$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
			}

			else
			{
				while (strpos($sText, '<u title=') !== FALSE)
				{
					$iTipStart    = strpos($sText, '<u title=');
					$iTipEnd      = strpos($sText, '</u>');
					$sTip         = substr($sText, $iTipStart, ($iTipEnd - $iTipStart + 4));

					$iWordStart   = ($iTipStart + 10);
					$iWordEnd     = strpos($sText, '">');
					$sWord        = substr($sText, $iWordStart, ($iWordEnd - $iWordStart));

					$iAbbrevStart = ($iWordEnd + 2);
					$iAbbrevEnd   = $iTipEnd;
					$sAbbrev      = substr($sText, $iAbbrevStart, ($iAbbrevEnd - $iAbbrevStart));

					$sText = str_replace($sTip, "{$sWord} ({$sAbbrev})", $sText);
				}

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, ((substr($sText, 0, 2) == "o ") ? @utf8_encode("          ° ".substr($sText, 2)) : @utf8_encode("» ".$sText)));
				$objPHPExcel->getActiveSheet()->getRowDimension($iRow)->setRowHeight((@ceil(strlen($sText) / 104) * 18));
				$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			}


			$iRow ++;
		}
	}




	$iRow += 2;

	$sNonRoutineActivities = @str_replace("\r\n\r\n", "\n", $sNonRoutineActivities);
	$sNonRoutineActivities = @str_replace("\n\n", "\n", $sNonRoutineActivities);
	$sNonRoutineActivities = @str_replace("\r\n", "\n", $sNonRoutineActivities);
	$sNonRoutineActivities = @explode("\n", $sNonRoutineActivities);


	if (count($sNonRoutineActivities) > 0)
	{
		$iCount = count($sNonRoutineActivities);

		if ($iCount > 2)
			$objPHPExcel->getActiveSheet()->insertNewRowBefore(($iRow + 1), ($iCount - 2));


		for ($i = 0; $i < $iCount; $i ++)
		{
			$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->mergeCells("A{$iRow}:I{$iRow}");

			if ($iCount > 2)
				$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}:I{$iRow}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);


			$sText = trim($sNonRoutineActivities[$i]);

			if (substr($sText, 0, 2) == "h ")
			{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, substr($sText, 2));
				$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getRowDimension($iRow)->setRowHeight(25);
				$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
			}

			else
			{
				while (strpos($sText, '<u title=') !== FALSE)
				{
					$iTipStart    = strpos($sText, '<u title=');
					$iTipEnd      = strpos($sText, '</u>');
					$sTip         = substr($sText, $iTipStart, ($iTipEnd - $iTipStart + 4));

					$iWordStart   = ($iTipStart + 10);
					$iWordEnd     = strpos($sText, '">');
					$sWord        = substr($sText, $iWordStart, ($iWordEnd - $iWordStart));

					$iAbbrevStart = ($iWordEnd + 2);
					$iAbbrevEnd   = $iTipEnd;
					$sAbbrev      = substr($sText, $iAbbrevStart, ($iAbbrevEnd - $iAbbrevStart));

					$sText = str_replace($sTip, "{$sWord} ({$sAbbrev})", $sText);
				}

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, ((substr($sText, 0, 2) == "o ") ? @utf8_encode("          ° ".substr($sText, 2)) : @utf8_encode("» ".$sText)));
				$objPHPExcel->getActiveSheet()->getRowDimension($iRow)->setRowHeight((@ceil(strlen($sText) / 104) * 18));
				$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			}


			$iRow ++;
		}
	}



	$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(1);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0);

	$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);



	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	//$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	//$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle("Job Description");


	$sExcelFile = ($sBaseDir.TEMP_DIR."JD-{$sName}.xlsx");


	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($sExcelFile);

	$objDb->close( );
	$objDb2->close( );
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
?>