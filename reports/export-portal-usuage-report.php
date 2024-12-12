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

	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");
	$Department = IO::intValue('Department');
	$Region     = IO::intValue('Region');

	$sDepartmentsList  = getList("tbl_departments", "id", "department");
	$sDesignationsList = getList("tbl_designations", "id", "designation");

	$sDepartmentSql = "";
	$sRegionSql     = "";
	$sRegion        = "";

	if ($Department > 0)
		$sDepartmentSql = " WHERE id='$Department' ";

	if ($Region > 0)
	{
		$sRegionSql = " AND country_id='$Region' ";

		$sSQL = "SELECT country FROM tbl_countries WHERE id='$Region'";
		$objDb->query($sSQL);

		$sRegion = ("  (".$objDb->getField(0, 0).")");
	}

	$sExcelFile = ($sBaseDir."temp/portal-usuage-report.xlsx");

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setTitle("Portal Usuage Report");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("Portal Usuage Report");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");

	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getActiveSheet()->setCellValue('A2', "TRIPLE TREE SOLUTIONS");
	$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(28);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->setCellValue('A3', ("PORTAL USUAGE REPORT FROM ".formatDate($FromDate)." TO ".formatDate($ToDate).$sRegion));
	$objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(18);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


	$sSQL = "SELECT id, department FROM tbl_departments $sDepartmentSql ORDER BY department";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$iRow = 5;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDepartmentId = $objDb->getField($i, 'id');
		$sDepartment   = $objDb->getField($i, 'department');

		$sSQL = "SELECT id, name, designation_id
		         FROM tbl_users
		         WHERE status='A' AND designation_id IN (SELECT id FROM tbl_designations WHERE department_id='$iDepartmentId') $sRegionSql
		         ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		if ($iCount2 > 0)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "Department               ");
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "Designation              ");
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "Employee Name            ");
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "Last Login Date    ");
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, "Last Login Time    ");
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, "Last Session Time  ");
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, "AVG Daily Time on Portal ");

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


			for ($j = 0; $j < $iCount2; $j ++)
			{
				$iUserId      = $objDb2->getField($j, 'id');
				$sName        = $objDb2->getField($j, 'name');
				$iDesignation = $objDb2->getField($j, 'designation_id');

				$sLoginDate   = "";
				$sLoginTime   = "";
				$sSessionTime = "";
				$sAvgHours    = "";


				$sSQL = "SELECT login_date_time, TIMEDIFF(logout_date_time, login_date_time) FROM tbl_user_stats WHERE user_id='$iUserId' ORDER BY id DESC LIMIT 1";
				$objDb3->query($sSQL);

				if ($objDb3->getCount( ) == 1)
				{
					$sLastLogin   = $objDb3->getField(0, 0);
					$sSessionTime = $objDb3->getField(0, 1);

					$sLoginDate = substr($sLastLogin, 0, 10);
					$sLoginTime = substr($sLastLogin, 11);
				}


				$sSQL = "SELECT SUM(TIME_TO_SEC(TIMEDIFF(logout_date_time, login_date_time)))
				         FROM tbl_user_stats
				         WHERE user_id='$iUserId' AND (DATE_FORMAT(login_date_time, '%Y-%m-%d') BETWEEN '$FromDate' AND '$ToDate')";
				$objDb3->query($sSQL);

				if ($objDb3->getCount( ) == 1)
				{
					$iTotalTime = $objDb3->getField(0, 0);

					$iWorkingDays = getWorkingDays($FromDate, $ToDate);

					$iAvgPortalTime = @ceil($iTotalTime / $iWorkingDays);
					$sAvgPortalTime = seconds2Time($iAvgPortalTime);
				}


				$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, $sDepartment);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, $sDesignationsList[$iDesignation]);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, $sName);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, formatDate($sLoginDate));
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, formatTime($sLoginTime));
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, $sSessionTime);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, $sAvgPortalTime);

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

	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Portal Usuage Report');

	include 'PHPExcel/IOFactory.php';

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($sExcelFile);

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
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