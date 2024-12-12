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

	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");

	$sAuditorsList = getList("tbl_users", "id", "name");
	$sVendorsList  = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");


	$sExcelFile = ($sBaseDir.TEMP_DIR."Audits Summary.xlsx");

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setTitle("Audits Summary");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("Audits Summary");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");


	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getActiveSheet()->setCellValue('A2', "TRIPLE TREE SOLUTIONS");
	$objPHPExcel->getActiveSheet()->mergeCells('A2:I2');
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(28);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->setCellValue('A3', ("From:   ".formatDate($FromDate)." to ".formatDate($ToDate)));
	$objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


	$iRow = 6;

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "Auditor   ");
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "Scheduled ");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "Completed ");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "Pending ");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, "Portal ");
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, "SMS ");
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, "QApp v1 ");
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$iRow, "QApp v2 ");
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$iRow, "Factory ");

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'font'    => array(
					'bold'      => true,
					'size' => 11
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
				'borders' => array(
					'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'bottom'     => array('style' => PHPExcel_Style_Border::BORDER_THIN)
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
			('A'.$iRow.':I'.$iRow)
	);

	$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle("I{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	$iRow ++;






	$sSQL = "SELECT user_id,
	                COUNT(1) AS _Total,
	                SUM(IF(audit_result!='', '1', '0')) AS _Completed,
	                SUM(IF(audit_result!='', IF(audit_mode='0', '1', '0'), '0')) AS _Portal,
	                SUM(IF(audit_result!='', IF(audit_mode='3', '1', '0'), '0')) AS _Sms,
	                SUM(IF(audit_result!='', IF(audit_mode='1', '1', '0'), '0')) AS _AppV1,
	                SUM(IF(audit_result!='', IF(audit_mode='2', '1', '0'), '0')) AS _AppV2,
	                GROUP_CONCAT(DISTINCT(vendor_id) SEPARATOR ',') AS _Vendors
			 FROM tbl_qa_reports
			 WHERE (audit_date BETWEEN '$FromDate' AND '$ToDate')
			 GROUP BY user_id";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$iTotals = array( );

	$iTotals["Total"]     = 0;
	$iTotals["Completed"] = 0;
	$iTotals["Pending"]   = 0;
	$iTotals["Portal"]    = 0;
	$iTotals["Sms"]       = 0;
	$iTotals["AppV1"]     = 0;
	$iTotals["AppV2"]     = 0;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iAuditor   = $objDb->getField($i, "user_id");
		$iTotal     = $objDb->getField($i, "_Total");
		$iCompleted = $objDb->getField($i, "_Completed");
		$iPortal    = $objDb->getField($i, "_Portal");
		$iSms       = $objDb->getField($i, "_Sms");
		$iAppV1     = $objDb->getField($i, "_AppV1");
		$iAppV2     = $objDb->getField($i, "_AppV2");
		$sVendors   = $objDb->getField($i, "_Vendors");


		$iVendors   = @explode(",", $sVendors);
		$sLocations = "";

		for ($j = 0; $j < count($iVendors); $j ++)
			$sLocations .= ((($j > 0) ? ", " : "").$sVendorsList[$iVendors[$j]]);


		$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", $sAuditorsList[$iAuditor]);
		$objPHPExcel->getActiveSheet()->setCellValue("B{$iRow}", $iTotal);
		$objPHPExcel->getActiveSheet()->setCellValue("C{$iRow}", $iCompleted);
		$objPHPExcel->getActiveSheet()->setCellValue("D{$iRow}", ($iTotal - $iCompleted));
		$objPHPExcel->getActiveSheet()->setCellValue("E{$iRow}", $iPortal);
		$objPHPExcel->getActiveSheet()->setCellValue("F{$iRow}", $iSms);
		$objPHPExcel->getActiveSheet()->setCellValue("G{$iRow}", $iAppV1);
		$objPHPExcel->getActiveSheet()->setCellValue("H{$iRow}", $iAppV2);
		$objPHPExcel->getActiveSheet()->setCellValue("I{$iRow}", $sLocations);

		$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle("B{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("C{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("D{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("E{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("F{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("G{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("H{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("I{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$iTotals["Total"]     += $iTotal;
		$iTotals["Completed"] += $iCompleted;
		$iTotals["Pending"]   += ($iTotal - $iCompleted);
		$iTotals["Portal"]    += $iPortal;
		$iTotals["Sms"]       += $iSms;
		$iTotals["AppV1"]     += $iAppV1;
		$iTotals["AppV2"]     += $iAppV2;

		$iRow ++;
	}


	$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", "Total");
	$objPHPExcel->getActiveSheet()->setCellValue("B{$iRow}", $iTotals["Total"]);
	$objPHPExcel->getActiveSheet()->setCellValue("C{$iRow}", $iTotals["Completed"]);
	$objPHPExcel->getActiveSheet()->setCellValue("D{$iRow}", $iTotals["Pending"]);
	$objPHPExcel->getActiveSheet()->setCellValue("E{$iRow}", $iTotals["Portal"]);
	$objPHPExcel->getActiveSheet()->setCellValue("F{$iRow}", $iTotals["Sms"]);
	$objPHPExcel->getActiveSheet()->setCellValue("G{$iRow}", $iTotals["AppV1"]);
	$objPHPExcel->getActiveSheet()->setCellValue("H{$iRow}", $iTotals["AppV2"]);


	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'font'    => array(
					'bold'      => true,
					'size' => 11
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
				'borders' => array(
					'bottom'     => array('style' => PHPExcel_Style_Border::BORDER_THIN)
				),
				'fill' => array(
					'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
					'rotation'   => 90,
					'startcolor' => array(
						'argb' => 'FFAAAAAA'
					),
					'endcolor'   => array(
						'argb' => 'FFFFFFFF'
					)
				)
			),
			('A'.$iRow.':I'.$iRow)
	);

	$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



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


	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Audits Summary');

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