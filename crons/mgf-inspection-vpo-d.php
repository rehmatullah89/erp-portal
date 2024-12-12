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
//	$sBaseDir = "C:/wamp/www/portal/";
	
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
        //$sFromDate = date("Y-m-d", strtotime("last week"));
	//$sToDate   = date("Y-m-d");
        $sFromDate = "2017-02-20";
        $sToDate   = "2017-08-10";
        
	$sAuditorsList        = getList("tbl_users", "id", "name");
	$sVendorCountriesList = getList("tbl_vendors", "id", "country_id");
	$sCountryHoursList    = getList("tbl_countries", "id", "hours");	
	

	//$sDir       = (($iReport == 14) ? "prod/" : "test/");
	//$sExcelFile = ($sBaseDir."mgf/{$sDir}INSPECTION_VPO.xlsx");
        $sExcelFile   = ($sBaseDir."mgf/INSPECTION_VPO_D.xlsx");
        
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
	$objPHPExcel->getProperties()->setTitle("MGF INSPECTIONS VPO LIST");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("MGF INSPECTIONS VPO LIST");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");


	// Create a first sheet
	$iRow = 1;

	$objPHPExcel->setActiveSheetIndex(0);
	
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "Test_No");
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "VPO_No");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "Created_DateTime");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "Created_By");

	$objPHPExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , "A{$iRow}:D{$iRow}");

	
	$iRow ++;
	
	
	$sReportDateTime = getDbValue("mgf_report_time", "tbl_global", "id='1'");
	

	
	$sSQL = "SELECT po_id, user_id, audit_code, created_at, additional_pos, vendor_id
             FROM tbl_qa_reports
			 WHERE report_id='$iReport' AND ((DATE(created_at) BETWEEN '$sFromDate' AND '$sToDate') OR (DATE(date_time) BETWEEN '$sFromDate' AND '$sToDate'))
			       AND vendor_id!='246' AND brand_id!='256'
				   AND audit_date>='2016-11-10' AND audit_result!='' AND NOT ISNULL(audit_result) AND qa_comments!='' AND date_time<='$sReportDateTime'
			 ORDER BY audit_date, start_time";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$iTotals = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAuditCode     = $objDb->getField($i, "audit_code");
		$iAuditor       = $objDb->getField($i, "user_id");
		$iVendor        = $objDb->getField($i, "vendor_id");
		$iPo            = $objDb->getField($i, "po_id");
		$sAdditionalPos = $objDb->getField($i, "additional_pos");
		$sCreatedAt     = $objDb->getField($i, "created_at");
		
		$iCountry   = $sVendorCountriesList[$iVendor];
		$iHours     = $sCountryHoursList[$iCountry];
		$sCreatedAt = date("Y-m-d H:i:s", (strtotime($sCreatedAt) + ($iHours * 3600)));

		$sVpoNo           = getDbValue("vpo_no", "tbl_po", "id='$iPo'");
		$sAdditionlVpoNos = getList("tbl_po", "id", "vpo_no", "FIND_IN_SET(id, '$sAdditionalPos')");
		

		$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", $sAuditCode);
		$objPHPExcel->getActiveSheet()->setCellValue("B{$iRow}", $sVpoNo);
		$objPHPExcel->getActiveSheet()->setCellValue("C{$iRow}", $sCreatedAt);
		$objPHPExcel->getActiveSheet()->setCellValue("D{$iRow}", $sAuditorsList[$iAuditor]);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle , "A{$iRow}:D{$iRow}");

		$iRow ++;

		
		foreach ($sAdditionlVpoNos as $iPo => $sVpoNo)
		{
			$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", $sAuditCode);
			$objPHPExcel->getActiveSheet()->setCellValue("B{$iRow}", $sVpoNo);
			$objPHPExcel->getActiveSheet()->setCellValue("C{$iRow}", $sCreatedAt);
			$objPHPExcel->getActiveSheet()->setCellValue("D{$iRow}", $sAuditorsList[$iAuditor]);

			$objPHPExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle , "A{$iRow}:D{$iRow}");

			$iRow ++;
		}
	}


	// Set column widths
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('INSPECTION_VPO');

	include 'PHPExcel/IOFactory.php';

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($sExcelFile);


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>