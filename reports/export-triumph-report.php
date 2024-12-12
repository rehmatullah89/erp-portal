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
        @ob_start( );

        @ini_set('display_errors', 0);
        @ini_set('log_errors', 0);
        @error_reporting(0);

        @ini_set("max_execution_time", 0);
        @ini_set("mysql.connect_timeout", -1);
        @set_time_limit(0);

        @putenv("TZ=Asia/Karachi");
        @date_default_timezone_set("Asia/Karachi");
        @ini_set("date.timezone", "Asia/Karachi");
    
	@require_once("../requires/session.php");
	@require_once("../requires/PHPExcel.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$Brand       = @implode(",", IO::getArray('Brand'));
	$Vendor      = @implode(",", IO::getArray('Vendor'));
	$Region      = IO::intValue('Region');
	$AuditStage  = IO::strValue("AuditStage");
	$AuditResult = IO::strValue("AuditResult");
	$FromDate    = IO::strValue('FromDate');
	$ToDate      = IO::strValue('ToDate');
	$Auditor     = IO::intValue("Auditor");
        $ReportType  = IO::strValue("ReportType");
	$sFilters    = "";
        $sFilters2   = "";
	
	if ($Brand != "")
		$sFilters .= ("Brands: ".getDbValue("GROUP_CONCAT(brand SEPARATOR ', ')", "tbl_brands", "FIND_IN_SET(id, '$Brand')"));
	
	/*if ($Vendor != "")
	{
		if ($sFilters != "")
			$sFilters .= ", ";

		$sFilters .= ("Vendors: ".getDbValue("GROUP_CONCAT(vendor SEPARATOR ', ')", "tbl_vendors", "FIND_IN_SET(id, '$Vendor')"));		
	}*/
	
	if ($Region > 0)
	{
		if ($sFilters != "")
			$sFilters .= ", ";

		$sFilters .= ("Region: ".getDbValue("country", "tbl_countries", "id='$Region'"));		
	}
	
	if ($AuditStage != "")
	{
		if ($sFilters != "")
			$sFilters .= ", ";

		$sFilters .= ("Stage: ".getDbValue("stage", "tbl_audit_stages", "code='$AuditStage'"));		
	}
	
	if ($Auditor > 0)
	{
		if ($sFilters != "")
			$sFilters .= ", ";

		$sFilters .= ("Auditor: ".getDbValue("name", "tbl_users", "id='$Auditor'"));		
	}	
	
	if ($AuditResult != "")
	{
		if ($sFilters != "")
			$sFilters .= ", ";

		$sFilters .= ("Result: ".(($AuditResult == "P") ? "Pass" : (($AuditResult == "F") ? "Fail" : "Hold")));		
	}
	
	if ($FromDate != "" && $ToDate != "")
	{
		if ($sFilters != "")
			$sFilters .= ", ";

		$sFilters .= ("Date Range: ".formatDate($FromDate)." / ".formatDate($ToDate));		
	}
        else
            $sFilters2 = ("Date Range: ".formatDate($FromDate)." / ".formatDate($ToDate));		

     
        $objPhpExcel = new PHPExcel( );
        $objReader   = PHPExcel_IOFactory::createReader('Excel2007');
        
        $sHeadingStyle = array('font' => array('bold' => false, 'color' => array('rgb' => 'FFFFFF'), 'size' => 14),
                                                           'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
                                                           'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                                  'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                                  'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                                  'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
                                                           'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FF0000')) );


        $sBorderStyle = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
                                                  'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                         'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                         'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                         'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));

            $sReportTypes1 = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
            $sReportTypes  = implode(",",array_intersect(explode(",", $sReportTypes1),array(57)));;
            
            $sAuditStages1 = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");            
            $sAuditStages2 = getDbValue("stages", "tbl_reports", "id IN (57)");
            $sAuditStages  = implode(",",array_intersect(explode(",", $sAuditStages1),explode(",", $sAuditStages2)));
            
            $sConditions  = " AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') AND FIND_IN_SET(qa.report_id, '$sReportTypes') ";

            if ($Brand != "")
                    $sSQL = "SELECT id FROM tbl_po WHERE FIND_IN_SET(brand_id, '$Brand')";

            else
                    $sSQL = "SELECT id FROM tbl_po WHERE FIND_IN_SET(brand_id, '{$_SESSION['Brands']}')";

            if ($Vendor != "")
                    $sSQL .= " AND FIND_IN_SET(vendor_id, '$Vendor') ";

            else
                    $sSQL .= " AND FIND_IN_SET(vendor_id, '{$_SESSION['Vendors']}') ";

            if ($Region > 0)
                    $sSQL .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

            $objDb->query($sSQL);

            $iCount = $objDb->getCount( );

            if ($Brand != "")
                    $sConditions .= " AND FIND_IN_SET(qa.brand_id, '$Brand') ";
            
            if ($Vendor != "")
                    $sConditions .= " AND FIND_IN_SET(qa.vendor_id, '$Vendor') ";

            else
                    $sConditions .= " AND FIND_IN_SET(qa.vendor_id, '{$_SESSION['Vendors']}') ";

            if ($Region > 0)
            {
                    $sSQL = "SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y'";
                    $objDb->query($sSQL);

                    $iCount = $objDb->getCount( );

                    $sVendors = "";

                    for ($i = 0; $i < $iCount; $i ++)
                            $sVendors .= (",".$objDb->getField($i, 0));

                    if ($sVendors != "")
                            $sVendors = substr($sVendors, 1);

                    $sConditions .= " AND qa.vendor_id IN ($sVendors)";
            }

            if ($Auditor > 0)
                    $sConditions .= " AND qa.user_id='$Auditor' ";

            if ($AuditResult != "")
                    $sConditions .= " AND qa.audit_result='$AuditResult'";

            if ($FromDate != "" && $ToDate != "")
                    $sConditions .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";
  
                $objPhpExcel = $objReader->load("../templates/triumph-report.xlsx");
                $objPhpExcel->getProperties()->setCreator("Triple Tree")
                                                                         ->setLastModifiedBy($_SESSION["Name"])
                                                                         ->setTitle("Triumph Report")
                                                                         ->setSubject("Triumph Summary Report")
                                                                         ->setDescription("Triumph Summary Report")
                                                                         ->setKeywords("")
                                                                         ->setCategory("Reports");
 
                $objPhpExcel->setActiveSheetIndex(0);
                $objPhpExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(12);
                $objPhpExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPhpExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(30);

                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "({$sFilters})");


                $sAuditorsList    = getList("tbl_users", "id", "name");
                $sVendorsList     = getList("tbl_vendors", "id", "vendor");
                $sBrandsList      = getList("tbl_brands", "id", "brand", "parent_id>'0'");
                $sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");
                $sReportsList     = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes')");

                $sSQL = "SELECT qa.id,
                                                qa.po_id,
                                                qa.audit_code,
                                                qa.booking_id,
                                                qa.commissions,
                                                qa.additional_pos,
                                                qa.start_time,
                                                qa.end_time,
                                                qa.report_id,
                                                qa.audit_date,
                                                qa.ship_qty,
                                                qa.total_gmts,
                                                qa.audit_stage,
                                                qa.audit_result,
                                                qa.colors,
                                                qa.brand_id,
                                                qa.vendor_id,
                                                qa.style_id,
                                                (SELECT SUM(lot_size) FROM tbl_qa_lot_sizes WHERE audit_id = qa.id) AS _LotQty,
                                                (SELECT style FROM tbl_styles WHERE id=qa.style_id) AS _Style,
                                                (SELECT name FROM tbl_users WHERE id=qa.user_id) AS _Auditor
                                 FROM tbl_qa_reports qa
                                 WHERE qa.audit_result!='' $sConditions
                                 ORDER BY qa.id";   
                $objDb->query($sSQL);

                $iCount       = $objDb->getCount( );
                $iInline      = 0;
                $iFinal       = 0;
                $iFinalPassed = 0;
                $iTgi         = 0;
                $iTgr         = 0;
                $iRow         = 4;

                $sReInspections = array();
                
                for ($i = 0; $i < $iCount; $i ++, $iRow ++)
                {
                        $iBooking           = $objDb->getField($i, "booking_id");
                        $sAuditCode         = $objDb->getField($i, "audit_code");
                        $iAudit             = $objDb->getField($i, "id");
                        $iStyle             = $objDb->getField($i, "style_id");
                        $iPoId              = $objDb->getField($i, "po_id");                        
                        $sAdditionalPos     = $objDb->getField($i, "additional_pos");
                        $sCommissions       = $objDb->getField($i, "commissions");                        
                        $sAuditDate         = $objDb->getField($i, "audit_date");
                        $sStartTime         = $objDb->getField($i, "start_time");
                        $sEndTime           = $objDb->getField($i, "end_time");
                        $iReport            = $objDb->getField($i, "report_id");
                        $sAuditStage        = $objDb->getField($i, "audit_stage");
                        $sAuditResult       = $objDb->getField($i, "audit_result");
                        $iBrand             = $objDb->getField($i, "brand_id");
                        $iVendor            = $objDb->getField($i, "vendor_id");
                        $sStyle             = $objDb->getField($i, "_Style");                        
                        $sAuditor           = $objDb->getField($i, "_Auditor");
                        $sColors            = $objDb->getField($i, "colors");
                        $iSampleSize        = $objDb->getField($i, "total_gmts");
                        $iShipQty           = $objDb->getField($i, "ship_qty");
                        $sReInspection      = $objDb->getField($i, "reinspection");
                        $iLotQty            = $objDb->getField($i, "_LotQty");
                        
                        $sBookingCode   = "B".str_pad($iBooking, 5, 0, STR_PAD_LEFT);
                        $fHours       = getDbValue("c.hours", "tbl_countries c, tbl_vendors v", "c.id = v.country_id AND v.id='$iVendor'");
                        $sStartTime   = date("H:i A", (strtotime($sStartTime) + ($fHours * 3600)));
                        $sEndTime     = date("H:i A", (strtotime($sEndTime) + ($fHours * 3600)));

                        if($sAuditResult == "P")
                            $iFinalPassed += 1;
                        
                        switch ($sAuditResult)
                        {
                                case "P"  :  $sStatus = "Accepted"; break;
                                case "F"  :  $sStatus = "Rejected"; break;
                                case "H"  :  $sStatus = "Hold"; break;
                                case "R"  :  $sStatus = "Re-Inspection"; break;
                                case "A"  :  $sStatus = "Accepted"; break;
                                case "B"  :  $sStatus = "Accepted"; break;
                                case "C"  :  $sStatus = "Rejected"; break;
                        }

                        $sAllPOs = $iPoId;

                        if(!empty($sAdditionalPos))
                            $sAllPOs .= (",".$sAdditionalPos);
                        
                        $iTgi      += $iSampleSize;
                        $iOrderQty = getDbValue("SUM(quantity)", "tbl_po", "id IN ($sAllPOs)");
                        $sVpoNos   = getDbValue("GROUP_CONCAT(order_no SEPARATOR ',')", "tbl_po", "id IN ($sAllPOs)");
                        $sReInspections[$iStyle][$sVpoNos][$sCommissions] += 1;

                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, $sBookingCode);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $sAuditCode);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, $sStyle);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, $sVpoNos);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, $sCommissions);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, formatDate($sAuditDate));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $iRow, $sStartTime);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $iRow, $sEndTime);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $iRow, $sAuditStagesList[$sAuditStage]);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $iRow, $sStatus);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $iRow, $sBrandsList[$iBrand]);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $iRow, $sVendorsList[$iVendor]);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $iRow, $sAuditor);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $iRow, $sColors);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $iRow, "II");
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $iRow, formatNumber(getDbValue("aql", "tbl_brands", "id='$iBrand'"), true, 1));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $iRow, formatNumber($iLotQty, false));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $iRow, formatNumber($iShipQty, false));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $iRow, formatNumber($iSampleSize, false));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $iRow, formatNumber($iOrderQty, false));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $iRow, $sReInspections[$iStyle][$sVpoNos][$sCommissions]);

                        //$objPhpExcel->getActiveSheet()->getStyle("A{$iRow}:U{$iRow}")->getAlignment( )->setWrapText(true);
                        $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, ("A{$iRow}:U{$iRow}"));                        
                }

                $sSummary = " Total No of Inspections: {$iCount}, Total No of Passed Inspections: {$iFinalPassed} Total Garments Inspected: {$iTgi}";

                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, $sSummary);
                $objPhpExcel->getActiveSheet()->mergeCells("A{$iRow}:U{$iRow}");	
                $objPhpExcel->getActiveSheet()->getRowDimension($iRow)->setRowHeight(30);
                $objPhpExcel->getActiveSheet()->getStyle("A{$iRow}:U{$iRow}")->applyFromArray($sHeadingStyle);	

                $objPhpExcel->getActiveSheet()->setAutoFilter('A3:U3');

                // Set column widths
                $objPhpExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
                $objPhpExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);

                $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
                $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B &R ');

                $objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                $objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

                $objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
                $objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.3);
                $objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
                $objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

                $objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);



                $sExcelFile = "Triumph Report.xlsx";


                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
                header("Cache-Control: max-age=0");

                $objWriter = PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
                $objWriter->save("php://output");

        
        
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
        exit();
?>