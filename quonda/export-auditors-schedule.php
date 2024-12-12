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

	$Region   = IO::intValue("Region");
	$Vendor   = IO::intValue("Vendor");
	$Auditor  = IO::intValue("Auditor");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");

	$sRegionsList   = getList("tbl_countries", "id", "country", "matrix='Y'");
	$sVendorsList   = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sReportsList   = getList("tbl_reports", "id", "report");
	$sLocationsList = getList("tbl_visit_locations", "id", "location");
	$sAuditorsList  = getList("tbl_users", "id", "name");


	$sExcelFile = ($sBaseDir.TEMP_DIR."Visit Details.xlsx");

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setTitle("Factory Visit Details");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("Factory Visit Details");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");


	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getActiveSheet()->setCellValue('A2', "TRIPLE TREE SOLUTIONS");
	$objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(28);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->setCellValue('A3', ("From:   ".formatDate($FromDate)." to ".formatDate($ToDate)));
	$objPHPExcel->getActiveSheet()->mergeCells('A3:E3');

	$iRow = 4;

	if ($Region > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", "Region:  ".$sRegionsList[$Region]);
		$objPHPExcel->getActiveSheet()->mergeCells("A{$iRow}:E{$iRow}");

		$iRow ++;
	}

	if ($Vendor > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", "Vendor:  ".$sVendorsList[$Vendor]);
		$objPHPExcel->getActiveSheet()->mergeCells("A{$iRow}:E{$iRow}");

		$iRow ++;
	}

	if ($Auditor > 0)
	{
		$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", "Auditor:   ".$sAuditorsList[$Auditor]);
		$objPHPExcel->getActiveSheet()->mergeCells("A{$iRow}:E{$iRow}");

		$iRow ++;
	}


	$iRow += 2;

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "Date        ");
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "Auditor     ");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "Factories   ");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "Spent Time  ");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, "Inspections ");

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



	$sConditions = "";

	if ($Region > 0)
		$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region') ";

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	if ($Auditor > 0)
		$sConditions .= " AND user_id='$Auditor' ";


	$iAuditors = array( );

	$sSQL = "SELECT DISTINCT(user_id)
	         FROM tbl_qa_reports
	         WHERE audit_result!='' AND (audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
	                AND (group_id='0' OR (group_id>'0' AND group_id IN (SELECT id FROM tbl_auditor_groups WHERE FIND_IN_SET(tbl_qa_reports.user_id, users))))";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$iAuditors[] = $objDb->getField($i, 0);


	$sAuditors = @implode(",", $iAuditors);

	$sSQL = "SELECT DISTINCT(user_id)
	         FROM tbl_user_schedule
	         WHERE ((from_date BETWEEN '$FromDate' AND '$ToDate') OR (to_date BETWEEN '$FromDate' AND '$ToDate')) AND NOT FIND_IN_SET(user_id, '$sAuditors')";

	if ($Auditor > 0)
		$sSQL .= " AND user_id='$Auditor' ";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$iAuditors[] = $objDb->getField($i, 0);




	$sConditions = "";

	if ($Region > 0)
		$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region') ";

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";


	$iFromDate = strtotime($FromDate);
	$iToDate   = strtotime($ToDate);

	for ($iDate = $iFromDate; $iDate <= $iToDate; $iDate += 86400)
	{
		foreach ($iAuditors as $iAuditor)
		{
			$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", date("d-M-Y", $iDate));
			$objPHPExcel->getActiveSheet()->setCellValue("B{$iRow}", $sAuditorsList[$iAuditor]);


			$sDate      = date("Y-m-d", $iDate);
			$sFactories = "";
			$sLocations = "";
			$iTime      = 0;
			$iAudits    = 0;


			$sSQL = "SELECT GROUP_CONCAT(DISTINCT(vendor_id) SEPARATOR ','), SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time))), COUNT(*)
					 FROM tbl_qa_reports
					 WHERE audit_result!='' AND audit_date='$sDate' AND
					      (user_id='$iAuditor' OR (group_id>'0' AND group_id IN (SELECT id FROM tbl_auditor_groups WHERE FIND_IN_SET('$iAuditor', users))))
					      $sConditions";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1)
			{
				$sFactories = $objDb->getField(0, 0);
				$iTime      = $objDb->getField(0, 1);
				$iAudits    = $objDb->getField(0, 2);
			}


			$sSQL = "SELECT GROUP_CONCAT(location_id SEPARATOR ','), SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time))), COUNT(*)
					 FROM tbl_user_schedule
					 WHERE ('$sDate' BETWEEN from_date AND to_date) AND user_id='$iAuditor'";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1)
			{
				$sLocations = $objDb->getField(0, 0);
				$iTime     += $objDb->getField(0, 1);
				$iAudits   += $objDb->getField(0, 2);
			}


			if ($sFactories == "" && $sLocations == "")
				continue;



			$iFactories = @explode(",", $sFactories);
			$sFactories = "";

			for ($i = 0; $i < count($iFactories); $i ++)
			{
				if ($i > 0)
					$sFactories .= ", ";

				$sFactories .= $sVendorsList[$iFactories[$i]];
			}


			$iLocations = @explode(",", $sLocations);
			$sLocations = "";

			for ($i = 0; $i < count($iLocations); $i ++)
			{
				if ($i > 0)
					$sLocations .= ", ";

				$sLocations .= $sLocationsList[$iLocations[$i]];
			}

			if ($sLocations != "")
			{
				if ($sFactories != "")
					$sFactories .= ", ";

				$sFactories .= $sLocations;
			}

			$iTime    = @floor($iTime / 60);
			$iHours   = @floor($iTime / 60);
			$iMinutes = @floor($iTime % 60);


			$objPHPExcel->getActiveSheet()->setCellValue("C{$iRow}", $sFactories);
			$objPHPExcel->getActiveSheet()->setCellValue("D{$iRow}", "{$iHours}h {$iMinutes}m");
			$objPHPExcel->getActiveSheet()->setCellValue("E{$iRow}", $iAudits);

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
	$objPHPExcel->getActiveSheet()->setTitle('Visit Details');

	include 'PHPExcel/IOFactory.php';

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