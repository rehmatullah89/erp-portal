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

	@session_start( );

	@ini_set('display_errors', 0);
	@ini_set('log_errors', 0);
	@error_reporting(0);
	
	@putenv("TZ=Asia/Karachi");
	@date_default_timezone_set("Asia/Karachi");
	@ini_set("date.timezone", "Asia/Karachi");

	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);


	$sBaseDir = "../";
	//$sBaseDir = "C:/wamp/www/portal/";

        
	@require_once($sBaseDir."requires/configs.php");
	@require_once($sBaseDir."requires/db.class.php");
	@require_once($sBaseDir."requires/common-functions.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	
	if (defined('STDIN'))
		$iReport = intval($argv[1]);
	
	else 
		$iReport = intval($_REQUEST['Report']);
	
	$iReport   = (($iReport == 0) ? 14 : $iReport);	
	$sFromDate = date("Y-m-d", strtotime("last week"));
	$sToDate   = date("Y-m-d");

	$sAuditorsList        = getList("tbl_users", "id", "name");
	$sVendorCountriesList = getList("tbl_vendors", "id", "country_id");
	$sCountryHoursList    = getList("tbl_countries", "id", "hours");	

	
	//$sDir       = (($iReport == 14) ? "prod/" : "test/");
	$sExcelFile = ($sBaseDir."mgf/test2/INSPECTION_DEFECT.xlsx");

	@unlink($sExcelFile);



	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');


	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';

	$sBorderStyle = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
						  'borders'  => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											 'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											 'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));

	$sBlockStyle = array('font'       => array('bold' => true, 'size' => 11),
						 'fill'       => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'DDDDDD')),
						 'alignment'  => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
						 'borders'    => array('top'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											   'right'  => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											   'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											   'left'   => array('style' => PHPExcel_Style_Border::BORDER_THIN)));

											   
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setTitle("INSPECTION_DEFECT");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("INSPECTION_DEFECT");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");



	// Create a first sheet
	$iRow = 1;

	$objPHPExcel->setActiveSheetIndex(0);
	
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "Test_No");
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "Row_No");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "Defect_Code");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "Defect_Description");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, "Defect_Category");
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, "No_Of_Defect");
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, "Defect_Type");
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$iRow, "Defect Area");
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$iRow, "Defect Cap");
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$iRow, "Attachment_Qty");
	$objPHPExcel->getActiveSheet()->setCellValue('K'.$iRow, "Created_DateTime");
	$objPHPExcel->getActiveSheet()->setCellValue('L'.$iRow, "LastModified_DateTime");
	$objPHPExcel->getActiveSheet()->setCellValue('M'.$iRow, "Created_By");
	$objPHPExcel->getActiveSheet()->setCellValue('N'.$iRow, "LastModified_By");


	$objPHPExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , "A{$iRow}:N{$iRow}");

	$iRow ++;

	
	$sReportDateTime = getDbValue("mgf_report_time", "tbl_global", "id='1'");

	
	$sSQL = "SELECT qr.user_id, qr.audit_code, qr.created_at, qr.date_time, qr.audit_date, qr.created_by, qr.vendor_id, qr.modified_by,
					qrd.code_id, qrd.defects, qrd.nature, qrd.area_id, qrd.remarks, qrd.cap, qrd.area_id
					FROM tbl_qa_reports qr, tbl_qa_report_defects qrd
		WHERE qr.id=qrd.audit_id AND qr.id IN (376327,373883,372977,371412,371387,371344,371250,368982,366008,363341,361637,317494,316622,313674,308955,308585,307240,301497,296103,295473,295352,295037,294939,294456,294446,293789,291897,290563,290335,289814,288882,288524,287108,286362,286228,286225,286058,285799,285790,285342,285279,285271,285242,285225,285179,285101,285098,285082,285061,285059,285052,285014,284997,284645,284637,284621,284532,284191,284160,284106,283106,283100,283082,283045,283021,283017,282560,282476,281940,281685,281621,281418,281305,281274,280671,280664,280656,280653,280647,280639,280637,280559,280528,280428,280318,280199,280164,279964,279678,277996,277842,277694,276630,276590,275700,275275,275251,275033,274972,274130)";
	$objDb->query($sSQL);

	$iCount         = $objDb->getCount( );
	$iTotals        = array( );
    $sLastAuditCode = "";

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAuditCode  = $objDb->getField($i, "audit_code");
		$sAuditDate  = $objDb->getField($i, "audit_date");
		$iAuditor    = $objDb->getField($i, "user_id");
		$iVendor     = $objDb->getField($i, "vendor_id");
		$sCreatedAt  = $objDb->getField($i, "created_at");
		$sModifiedAt = $objDb->getField($i, "date_time");
		$iCode       = $objDb->getField($i, "code_id");
		$iDefects    = $objDb->getField($i, "defects");
		$iArea       = $objDb->getField($i, "area_id");
		$iNature     = $objDb->getField($i, "nature");
		$sCaps       = $objDb->getField($i, "cap");
		$sRemarks    = $objDb->getField($i, "remarks");
		$iCreatedBy  = $objDb->getField($i, "created_by");
		$iModifiedBy = $objDb->getField($i, "modified_by");

	
		$iCountry    = $sVendorCountriesList[$iVendor];
		$iHours      = $sCountryHoursList[$iCountry];
		
		$sCreatedAt  = date("Y-m-d H:i:s", (strtotime($sCreatedAt) + ($iHours * 3600)));
		$sModifiedAt = date("Y-m-d H:i:s", (strtotime($sModifiedAt) + ($iHours * 3600)));

		
		$sSQL = "SELECT defect, code, (SELECT type FROM tbl_defect_types WHERE tbl_defect_codes.type_id=tbl_defect_types.id) AS _Type FROM tbl_defect_codes WHERE id='$iCode'";
		$objDb2->query($sSQL);

		$sDefect     = $objDb2->getField(0, 0);
		$sDefectCode = $objDb2->getField(0, 1);
		$sDefectType = $objDb2->getField(0, 2);


		$sDefectArea   = getDbValue("area", "tbl_defect_areas", "id='$iArea'");
		$sDefectLevels = (($iNature == 2) ? "Critical" : (($iNature == 1) ? "Major" : "Minor"));
		$iPictures     = 0;

		if ($sLastAuditCode != $sAuditCode)
		{
			$iCounter  = 1;
			$sPictures = array( );
			
			@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

			$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/{$sAuditCode}_*.*");
			$sPictures = @array_map("strtoupper", $sPictures);
			$sPictures = @array_unique($sPictures);
		}

		
		if (count($sPictures) > 0)
		{
			foreach ($sPictures as $sPicture)
			{
				$sPicture = @basename($sPicture);
				
				if (strpos($sPicture, "{$sAuditCode}_{$sDefectCode}_{$iArea}_") !== false)
					$iPictures ++;
			}
		}


		$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", $sAuditCode);
		$objPHPExcel->getActiveSheet()->setCellValue("B{$iRow}", $iCounter);
		$objPHPExcel->getActiveSheet()->setCellValue("C{$iRow}", $sDefectCode);
		$objPHPExcel->getActiveSheet()->setCellValue("D{$iRow}", $sDefect);
		$objPHPExcel->getActiveSheet()->setCellValue("E{$iRow}", $sDefectType);
		$objPHPExcel->getActiveSheet()->setCellValue("F{$iRow}", $iDefects);
		$objPHPExcel->getActiveSheet()->setCellValue("G{$iRow}", $sDefectLevels);
		$objPHPExcel->getActiveSheet()->setCellValue("H{$iRow}", $sDefectArea);
		$objPHPExcel->getActiveSheet()->setCellValue("I{$iRow}", $sCaps);
		$objPHPExcel->getActiveSheet()->setCellValue("J{$iRow}", $iPictures);
		$objPHPExcel->getActiveSheet()->setCellValue("K{$iRow}", $sCreatedAt);
		$objPHPExcel->getActiveSheet()->setCellValue("L{$iRow}", $sModifiedAt);
		$objPHPExcel->getActiveSheet()->setCellValue("M{$iRow}", $sAuditorsList[$iCreatedBy]);
		$objPHPExcel->getActiveSheet()->setCellValue("N{$iRow}", $sAuditorsList[$iModifiedBy]);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle , "A{$iRow}:N{$iRow}");

		$sLastAuditCode = $sAuditCode;

		$iRow ++;
		$iCounter ++;
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
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);



	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('INSPECTION_DEFECT');

	include 'PHPExcel/IOFactory.php';

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($sExcelFile);


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>