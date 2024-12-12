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
	$objDb3      = new Database( );

	$Region      = IO::intValue('Region');
        $Vendor      = @implode(",", IO::getArray('Vendor'));	
	$AuditStage  = IO::strValue("AuditStage");
        $Year        = IO::strValue('Year');
        $Month       = IO::intValue('Month');
	$FromDate    = "{$Year}-".(IO::intValue('Month') == ""?1:IO::intValue('Month'))."-01";
	$ToDate      = "{$Year}-".(IO::intValue('Month') == ""?12:IO::intValue('Month'))."-31";
        $ReportType  = IO::strValue('ReportType');
        
        $sDefectsList = getList("tbl_defect_codes", "id", "defect", "report_id='46'");
        
	$sFilters    = "";
        $sFilters2   = "";
	
        if ($Region > 0)
	{
		if ($sFilters != "")
			$sFilters .= ", ";

		$sFilters .= ("Region: ".getDbValue("country", "tbl_countries", "id='$Region'"));		
	}
        
	if ($Vendor != "")
	{
		if ($sFilters != "")
			$sFilters .= ", ";

		$sFilters .= ("Vendors: ".getDbValue("GROUP_CONCAT(vendor SEPARATOR ', ')", "tbl_vendors", "FIND_IN_SET(id, '$Vendor')"));		
	}
	
	
        if($AuditStage != "")
            $sFilters .= (",".("Stage: ".getDbValue("stage", "tbl_audit_stages", "code='$AuditStage'")));
	
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
                                                           'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '808080')) );


            $sBorderStyle = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
                                                  'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                         'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                         'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                         'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'ffffff')));

            $sReportTypes1 = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
            $sReportTypes  = implode(",",array_intersect(explode(",", $sReportTypes1),array(44,45)));;
            
            /*$sAuditStages1 = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");            
            $sAuditStages2 = getDbValue("stages", "tbl_reports", "id IN (44,45)");
            $sAuditStages  = implode(",",array_intersect(explode(",", $sAuditStages1),explode(",", $sAuditStages2)));*/
            
            $sConditions  = " AND qa.report_id='46' ";

            $sSQL = "SELECT id FROM tbl_po WHERE FIND_IN_SET(brand_id, '{$_SESSION['Brands']}')";

            if ($Vendor != "")
                    $sSQL .= " AND FIND_IN_SET(vendor_id, '$Vendor') ";

            else
                    $sSQL .= " AND FIND_IN_SET(vendor_id, '{$_SESSION['Vendors']}') ";

            if ($Region > 0)
                    $sSQL .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

            $objDb->query($sSQL);

            $iCount = $objDb->getCount( );
      

            if ($Vendor > 0)
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

            if ($AuditStage != "")
                $sConditions .= " AND qa.audit_stage='$AuditStage'";

            $sDateConditions = "";
                    
            if ($FromDate != "" && $ToDate != "")
                    $sDateConditions = " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";


            if($ReportType == 'DP')
            {
                $objPhpExcel = $objReader->load("../templates/jcrew-dp-report.xlsx");
                $objPhpExcel->getProperties()->setCreator("Triple Tree")
                                                                         ->setLastModifiedBy($_SESSION["Name"])
                                                                         ->setTitle("J.Crew Defects Percentage Report")
                                                                         ->setSubject("J.Crew Defects Percentage Report")
                                                                         ->setDescription("J.Crew Defects Percentage Report")
                                                                         ->setKeywords("")
                                                                         ->setCategory("Reports");

                $objPhpExcel->setActiveSheetIndex(0);
                $objPhpExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
                $objPhpExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPhpExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(30);

                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "({$sFilters})");
                
               
                    $sSQL = "SELECT vendor_id, (SELECT vendor from tbl_vendors WHERE id=qa.vendor_id) as _Vendor,
                                    (SELECT parent from tbl_factories WHERE FIND_IN_SET(qa.vendor_id, vendors) LIMIT 1) as _Parent,
                                    (SELECT c.country from tbl_countries c, tbl_vendors v  WHERE v.country_id = c.id AND v.id=qa.vendor_id) as _Country
                     FROM tbl_qa_reports qa
                     WHERE qa.audit_result!='' $sConditions
                     GROUP BY _Country, _Parent, _Vendor    
                     ORDER BY _Country, _Parent, _Vendor";

                    $objDb->query($sSQL);
                    $iCount = $objDb->getCount();                    
                            
                    $iRow   = 6;
                                        
                    for ($i = 0; $i < $iCount; $i ++)
                    {
                            $sParent        = $objDb->getField($i, "_Parent");
                            $iVendor        = $objDb->getField($i, "vendor_id");
                            $sVendor        = $objDb->getField($i, "_Vendor");
                            $sCountry       = $objDb->getField($i, "_Country");
                            
                            $sSubCondition = "";
                            if ($AuditStage != "")
                                $sSubCondition = " AND qa.audit_stage='$AuditStage'";
                            
                            $sDateConditions = " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";
                                
                            $sSQL = "SELECT SUM(qrd.defects) as _Defects, qrd.code_id as _DefectCode
                                FROM tbl_qa_reports qa, tbl_qa_report_defects qrd
                                WHERE qa.id=qrd.audit_id AND qa.vendor_id='$iVendor' $sSubCondition $sDateConditions
                                GROUP BY _DefectCode    
                                ORDER BY _Defects DESC, _DefectCode";
                            
                            $objDb2->query($sSQL);
                            $iCount2 = $objDb2->getCount();
                                
                            $DefectsList = array();
                            
                            for($j = 0; $j< $iCount2; $j++)
                               $DefectsList[$objDb2->getField($j, "_DefectCode")] = $objDb2->getField($j, "_Defects");
                                    
                            $iTotalDefects = array_sum($DefectsList);
                            
                            $iInc = 1;
                            $iDefectsPercent = array();
                            foreach($DefectsList as $iCode => $iDefects)
                            {
                                $iPercent = ($iDefects/$iTotalDefects)*100;
                                $iDefectsPercent[$iCode] = formatNumber($iPercent);
                                
                                $iInc ++;
                                
                                if($iInc > 5)
                                    break;
                            }
                            
                            foreach ($iDefectsPercent as $iCode => $sPercent)                                
                            {
                                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, $sCountry);
                                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $sParent);                            
                                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, $sVendor);

                                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, $sDefectsList[$iCode]); 
                                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, $sPercent."%"); 
                                
                                $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, ("A{$iRow}:E{$iRow}"));                 
                                $iRow ++;                                   
                            }
                            
                    }
                    
                    $objPhpExcel->getActiveSheet()->setAutoFilter('A5:E5');

                    $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
                    $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B &R ');

                    $objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                    $objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

                    $objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
                    $objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.3);
                    $objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
                    $objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

                    $objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);


                    $sExcelFile = "J.Crew Defects Percentage Report ".date('Y-m-d').".xlsx";


                    header("Content-Type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
                    header("Cache-Control: max-age=0");

                    $objWriter = PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
                    $objWriter->save("php://output");
            }
            else
            {
                 $objPhpExcel = $objReader->load("../templates/jcrew-dc-report.xlsx");
                $objPhpExcel->getProperties()->setCreator("Triple Tree")
                                                                         ->setLastModifiedBy($_SESSION["Name"])
                                                                         ->setTitle("J.Crew Defects By Category Report")
                                                                         ->setSubject("J.Crew Defects  By Category Report")
                                                                         ->setDescription("J.Crew Defects By Category Report")
                                                                         ->setKeywords("")
                                                                         ->setCategory("Reports");

                $objPhpExcel->setActiveSheetIndex(0);
                $objPhpExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
                $objPhpExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPhpExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(30);

                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "({$sFilters})");
                
                $sTypeList = getList("tbl_defect_types", "id", "`type`", "id!=''", "FIELD(id, 33, 3, 123, 122, 97, 124, 125, 93, 57, 58, 59, 126, 127)");
               
                    $sSQL = "SELECT vendor_id, (SELECT vendor from tbl_vendors WHERE id=qa.vendor_id) as _Vendor,
                                    (SELECT parent from tbl_factories WHERE FIND_IN_SET(qa.vendor_id, vendors) LIMIT 1) as _Parent,
                                    (SELECT c.country from tbl_countries c, tbl_vendors v  WHERE v.country_id = c.id AND v.id=qa.vendor_id) as _Country
                     FROM tbl_qa_reports qa
                     WHERE qa.audit_result!='' $sConditions
                     GROUP BY _Country, _Parent, _Vendor    
                     ORDER BY _Country, _Parent, _Vendor";

                    $objDb->query($sSQL);
                    $iCount = $objDb->getCount();                    
                    
                    $iCol = 3;
                    foreach($sTypeList as $iTypeId => $sType)
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow($iCol++, 5, $sType);
                    
                    $iRow   = 6;
                                        
                    for ($i = 0; $i < $iCount; $i ++)
                    {
                            $sParent        = $objDb->getField($i, "_Parent");
                            $iVendor        = $objDb->getField($i, "vendor_id");
                            $sVendor        = $objDb->getField($i, "_Vendor");
                            $sCountry       = $objDb->getField($i, "_Country");
                            
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, $sCountry);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $sParent);                            
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, $sVendor);
                            
                            $sSubCondition = "";
                            if ($AuditStage != "")
                                $sSubCondition = " AND qa.audit_stage='$AuditStage'";
                            
                            $sDateConditions = " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";
                                
                            $sSQL = "SELECT SUM(qrd.defects) as _Defects, dt.id as _DefectType
                                FROM tbl_qa_reports qa, tbl_qa_report_defects qrd, tbl_defect_codes dc, tbl_defect_types dt 
                                WHERE qa.id=qrd.audit_id AND qrd.code_id=dc.id AND dc.type_id=dt.id AND qa.vendor_id='$iVendor' $sSubCondition $sDateConditions
                                GROUP BY _DefectType    
                                ORDER BY _DefectType";
                            
                            $objDb2->query($sSQL);
                            $iCount2 = $objDb2->getCount();
                                
                            $DefectsList = array();
                            
                            for($j = 0; $j< $iCount2; $j++)
                               $DefectsList[$objDb2->getField($j, "_DefectType")] = $objDb2->getField($j, "_Defects");
                                    
                            $iTotalDefects = array_sum($DefectsList);
                            
                            $iDefectsPercent = array();
                            foreach($DefectsList as $iType => $iDefects)
                            {
                                $iPercent = ($iDefects/$iTotalDefects)*100;
                                $iDefectsPercent[$iType] = formatNumber($iPercent);
                            }
                            
                            $iCol = 3;
                            foreach($sTypeList as $iTypeId => $sType)
                                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow($iCol++, $iRow, (float)$iDefectsPercent[$iTypeId]."%"); 
                            
                            $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, ("A{$iRow}:P{$iRow}"));  
                            
                            $iRow ++;
                    }
                    
                    $objPhpExcel->getActiveSheet()->setAutoFilter('A5:E5');

                    $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
                    $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B &R ');

                    $objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                    $objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

                    $objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
                    $objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.3);
                    $objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
                    $objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

                    $objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);


                    $sExcelFile = "J.Crew Defects By Category Report ".date('Y-m-d').".xlsx";


                    header("Content-Type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
                    header("Cache-Control: max-age=0");

                    $objWriter = PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
                    $objWriter->save("php://output");
            }

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
        exit();
?>