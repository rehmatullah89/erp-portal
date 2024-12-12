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
	@require_once("../requires/PHPExcel.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
        
	$AuditCode  = IO::strValue("AuditCode");
	$Auditor    = IO::intValue("Auditor");
	$Group      = IO::intValue("Group");
	$Brand      = IO::intValue("Brand");
	$Vendor     = IO::intValue("Vendor");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");
	$Region     = IO::intValue("Region");
	$Department = IO::intValue("Department");
	$Completed  = IO::strValue("Completed");
        
		
	$sReportTypes = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sConditions  = " WHERE FIND_IN_SET(report_id, '$sReportTypes') ";
	
	if ($Completed == "Y")
		$sConditions .= " AND audit_result!='' ";
	
	else if ($Completed == "N")
		$sConditions .= " AND (audit_result='' OR ISNULL(audit_result)) ";
	
	if ($AuditCode != "")
		$sConditions .= " AND audit_code LIKE '%$AuditCode%' ";

	if ($Auditor > 0)
	{
		if ($Group == 0)
			$sConditions .= " AND (user_id='$Auditor' OR (group_id>'0' AND group_id IN (SELECT id FROM tbl_auditor_groups WHERE FIND_IN_SET('$Auditor', users)))) ";

		else
			$sConditions .= " AND user_id='$Auditor' ";
	}

	if ($Group > 0)
		$sConditions .= " AND group_id='$Group' ";

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	else
		$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Region > 0)
		$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Department > 0)
		$sConditions .= " AND department_id='$Department' ";


	
	$sAuditorsList      = getList("tbl_users", "id", "name");
	$sVendorsList       = getList("tbl_vendors", "id", "vendor");
	$sRegionsList       = getList("tbl_countries c, tbl_vendors v", "v.id", "c.country", "c.id = v.country_id AND c.matrix='Y'");
	$sBrandsList        = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})");
	$sStylesList        = getList("tbl_styles", "id", "style");
	$sAuditStagesList   = getList("tbl_audit_stages", "code", "stage");
	

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
	$objPHPExcel->getProperties()->setTitle("Audit Codes");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("Audit Codes ");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");


	// Create a first sheet
	$iRow = 1;

    $objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "Region");
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "Auditor");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "Audit Code");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "Brand");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, "Style");
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, "Audit Stage");
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, "Vendor/Factory");
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$iRow, "Audit Date");

	
	$objPHPExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , "A{$iRow}:H{$iRow}");

	$iRow ++;

	$sSQL = "SELECT  user_id, audit_code, audit_date, audit_stage, brand_id, vendor_id, style_id, po_id FROM tbl_qa_reports $sConditions";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iAuditCode     = $objDb->getField($i, "audit_code");
		$iVendor        = $objDb->getField($i, "vendor_id");
		$iAuditor       = $objDb->getField($i, "user_id");
		$iPo            = $objDb->getField($i, "po_id");
		$iStyle         = $objDb->getField($i, "style_id");
		$iBrand         = $objDb->getField($i, "brand_id");
		$sAuditDate     = $objDb->getField($i, "audit_date");
		$iAuditStage    = $objDb->getField($i, "audit_stage");
		
		$sVendor        = $sVendorsList[$iVendor];
		$sAuditor       = $sAuditorsList[$iAuditor];
		$sRegion        = $sRegionsList[$iVendor]; 
		$sStyle         = $sStylesList[$iStyle];
		$sBrand         = $sBrandsList[$iBrand];
		$sAuditStage    = $sAuditStagesList[$iAuditStage];
		
		$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", $sRegion);
		$objPHPExcel->getActiveSheet()->setCellValue("B{$iRow}", $sAuditor);
		$objPHPExcel->getActiveSheet()->setCellValue("C{$iRow}", $iAuditCode);
		$objPHPExcel->getActiveSheet()->setCellValue("D{$iRow}", $sBrand);
		$objPHPExcel->getActiveSheet()->setCellValue("E{$iRow}", $sStyle);
		$objPHPExcel->getActiveSheet()->setCellValue("F{$iRow}", $sAuditStage);
		$objPHPExcel->getActiveSheet()->setCellValue("G{$iRow}", $sVendor);
		$objPHPExcel->getActiveSheet()->setCellValue("H{$iRow}", $sAuditDate);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle , "A{$iRow}:H{$iRow}");

		$iRow ++;
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

	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0);

	$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
	$objPHPExcel->getActiveSheet()->setTitle("Audit Codes");


	$sExcelFile = "Audit Codes.xlsx";

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save("php://output");

        
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>