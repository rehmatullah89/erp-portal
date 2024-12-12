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
	
	if ($Vendor != "")
	{
		if ($sFilters != "")
			$sFilters .= ", ";

		$sFilters .= ("Vendors: ".getDbValue("GROUP_CONCAT(vendor SEPARATOR ', ')", "tbl_vendors", "FIND_IN_SET(id, '$Vendor')"));		
	}
	
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
	
	if ($FromDate != "" && $ToDate != "" && $ReportType == 'QS')
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
            $sReportTypes  = implode(",",array_intersect(explode(",", $sReportTypes1),array(14,34)));;
            
            $sAuditStages1 = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");            
            $sAuditStages2 = getDbValue("stages", "tbl_reports", "id='14'");
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

/*            $sPos = "";

            for ($i = 0; $i < $iCount; $i ++)
                    $sPos .= (",".$objDb->getField($i, 0));

            if ($sPos != "")
                    $sPos = substr($sPos, 1);

            $sConditions .= " AND po.id IN ($sPos)";*/


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

            if ($Auditor > 0)
                    $sConditions .= " AND qa.user_id='$Auditor' ";

            if ($AuditResult != "")
                    $sConditions .= " AND qa.audit_result='$AuditResult'";

            if ($FromDate != "" && $ToDate != "")
                    $sConditions .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";
  
        if($ReportType == 'QS')
        {

                $objPhpExcel = $objReader->load("../templates/mgf-report.xlsx");
                $objPhpExcel->getProperties()->setCreator("Triple Tree")
                                                                         ->setLastModifiedBy($_SESSION["Name"])
                                                                         ->setTitle("MGF Report")
                                                                         ->setSubject("Inspection Summary Report")
                                                                         ->setDescription("Inspection Summary Report")
                                                                         ->setKeywords("")
                                                                         ->setCategory("Reports");
 
                $objPhpExcel->setActiveSheetIndex(0);
                $objPhpExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(12);
                $objPhpExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPhpExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(30);

                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "({$sFilters})");


                $sAuditorsList    = getList("tbl_users", "id", "name");
                $sVendorsList     = getList("tbl_vendors", "id", "vendor");
                $sBrandsList      = getList("tbl_brands", "id", "brand", "parent_id>'0'");
                $sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");
                $sReportsList     = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes')");

                $sMeasurementCodes = "S.01,S.02,S.03";
                $sWorkmanshipCodes = "G.01,G.02,G.03,G.04,G.05,G.06,G.07,G.08,G.09,G.10,G.11,G.12,G.13,G.14,G.15,G.16,G.17,G.18,G.19,G.20,G.21,G.22,G.23,G.24,G.25,G.26,G.27";
                $sMaterialCodes    = "M.01,M.02,M.03,M.04,M.05,M.06,M.07,M.08,M.09,M.10,M.11,M.12,M.13,M.14";

                $sMeasurementCodes = getDbValue("GROUP_CONCAT(id SEPARATOR ',')", "tbl_defect_codes", "FIND_IN_SET(code, '$sMeasurementCodes')");
                $sWorkmanshipCodes = getDbValue("GROUP_CONCAT(id SEPARATOR ',')", "tbl_defect_codes", "FIND_IN_SET(code, '$sWorkmanshipCodes')");
                $sMaterialCodes    = getDbValue("GROUP_CONCAT(id SEPARATOR ',')", "tbl_defect_codes", "FIND_IN_SET(code, '$sMaterialCodes')");

                $iMeasurementDefectsList = getList("tbl_qa_report_defects qrd, tbl_qa_reports qa", "qa.id", "COUNT(1)", "qa.id=qrd.audit_id AND qa.report_id IN (14,34) AND FIND_IN_SET(qrd.code_id, '$sMeasurementCodes') GROUP BY qa.id");
                $iWorkmanshipDefectsList = getList("tbl_qa_report_defects qrd, tbl_qa_reports qa", "qa.id", "COUNT(1)", "qa.id=qrd.audit_id AND qa.report_id IN (14,34) AND FIND_IN_SET(qrd.code_id, '$sWorkmanshipCodes') GROUP BY qa.id");
                $iMaterialDefectsList    = getList("tbl_qa_report_defects qrd, tbl_qa_reports qa", "qa.id", "COUNT(1)", "qa.id=qrd.audit_id AND qa.report_id IN (14,34) AND FIND_IN_SET(qrd.code_id, '$sMaterialCodes') GROUP BY qa.id");


                $sSQL = "SELECT qa.id,
                                                qa.po_id,
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
                                                qa.inspection_level,
                                                qa.aql,
                                                mr.measurement_sample_qty,
                                                mr.measurement_defect_qty,
                                                mr.reinspection,
                                                (SELECT style FROM tbl_styles WHERE id=qa.style_id) AS _Style,
                                                (SELECT parent_id FROM tbl_brands WHERE id=qa.brand_id) AS _ParentBrand,
                                                (SELECT name FROM tbl_users WHERE id=qa.user_id) AS _Auditor
                                 FROM tbl_qa_reports qa, tbl_mgf_reports mr
                                 WHERE mr.audit_id = qa.id AND  qa.audit_result!='' AND qa.audit_type='B' $sConditions
                                 ORDER BY qa.id DESC";    
                $objDb->query($sSQL);

                $iCount       = $objDb->getCount( );
                $iInline      = 0;
                $iFinal       = 0;
                $iFinalPassed = 0;
                $iTgi         = 0;
                $iTgr         = 0;
                $iRow         = 4;

                for ($i = 0; $i < $iCount; $i ++, $iRow ++)
                {
                        $iAudit             = $objDb->getField($i, "id");
                        $iPoId              = $objDb->getField($i, "po_id");
                        $sAdditionalPos     = $objDb->getField($i, "additional_pos");
                        $sStartTime         = $objDb->getField($i, "start_time");
                        $sEndTime           = $objDb->getField($i, "end_time");
                        $iStyle             = $objDb->getField($i, "style_id");
                        $sAuditDate         = $objDb->getField($i, "audit_date");
                        $iReport            = $objDb->getField($i, "report_id");
                        $sAuditStage        = $objDb->getField($i, "audit_stage");
                        $sAuditResult       = $objDb->getField($i, "audit_result");
                        $iBrand             = $objDb->getField($i, "brand_id");
                        $sStyle             = $objDb->getField($i, "_Style");
                        $iVendor            = $objDb->getField($i, "vendor_id");
                        $sAuditor           = $objDb->getField($i, "_Auditor");
                        $sColors            = $objDb->getField($i, "colors");
                        $iSampleSize        = $objDb->getField($i, "total_gmts");
                        $iParentBrand       = $objDb->getField($i, "_ParentBrand");
                        $iShipQty           = $objDb->getField($i, "ship_qty");
                        $iInspectionLevel   = $objDb->getField($i, "inspection_level");
                        $sReInspection      = $objDb->getField($i, "reinspection");
                        $iMeasureSampleQty  = $objDb->getField($i, "measurement_sample_qty");
                        $iMeasureDefectQty  = $objDb->getField($i, "measurement_defect_qty");
                        $fAql               = ($objDb->getField($i, "aql")>0?$objDb->getField($i, "aql"):"2.5");
                        
                        $fHours       = getDbValue("c.hours", "tbl_countries c, tbl_vendors v", "c.id = v.country_id AND v.id='$iVendor'");
                        $sStartTime   = date("H:i A", (strtotime($sStartTime) + ($fHours * 3600)));
                        $sEndTime     = date("H:i A", (strtotime($sEndTime) + ($fHours * 3600)));

                        $sLevel = "II";

                        switch ($iInspectionLevel)
                        {
                                case 1 : $sLevel = "I"; break;
                                case 2 : $sLevel = "II"; break;
                                case 3 : $sLevel = "III"; break;
                                case 4 : $sLevel = "S-1"; break;
                                case 5 : $sLevel = "S-2"; break;
                                case 6 : $sLevel = "S-3"; break;
                                case 7 : $sLevel = "S-4"; break;
                        }

                        if ($sAuditStage == "F")
                        {
                                $iFinal ++;

                                if ($sAuditResult == "P")
                                {
                                        $iFinalPassed ++;

                                        $iTgi += $iSampleSize;
                                        $iTgr += (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAudit'");
                                }				
                        }

                        else
                                $iInline ++;


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

                        $iOrderQty = getDbValue("SUM(quantity)", "tbl_po", "id IN ($sAllPOs)");
                        $sVpoNos   = getDbValue("GROUP_CONCAT(vpo_no SEPARATOR ',')", "tbl_po", "id IN ($sAllPOs)");

                        $iMeasurementDefects = $iMeasurementDefectsList[$iAudit];
                        $iWorkmanshipDefects = $iWorkmanshipDefectsList[$iAudit];
                        $iMaterialDefects    = $iMaterialDefectsList[$iAudit];


                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, $iAudit);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $sVpoNos);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, getDbValue("COUNT(1)", "tbl_qa_reports", "style_id='$iStyle'"));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, formatDate($sAuditDate));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, $sStartTime);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, $sEndTime);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $iRow, $sAuditStagesList[$sAuditStage]);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $iRow, $sStatus);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $iRow, $sBrandsList[$iBrand]);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $iRow, $sStyle);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $iRow, $sVendorsList[$iVendor]);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $iRow, $sAuditor);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $iRow, $sColors);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $iRow, $sLevel);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $iRow, formatNumber($fAql, true, 1));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $iRow, formatNumber($iMeasurementDefects, false));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $iRow, formatNumber($iWorkmanshipDefects, false));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $iRow, formatNumber($iMaterialDefects, false));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $iRow, formatNumber(($iMaterialDefects + $iWorkmanshipDefects), false));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $iRow, formatNumber($iShipQty, false));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $iRow, formatNumber($iSampleSize, false));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $iRow, formatNumber($iOrderQty, false));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $iRow, formatNumber($iMeasureSampleQty, false));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(23, $iRow, formatNumber($iMeasureDefectQty, false));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(24, $iRow, ($sReInspection == "Y"?"Yes":"No"));

                        $objPhpExcel->getActiveSheet()->getStyle("A{$iRow}:Y{$iRow}")->getAlignment( )->setWrapText(true);
                        $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, ("A{$iRow}:Y{$iRow}"));
                        
                }


                $fDr      = @round((($iTgr / $iTgi) * 100), 2);
                $sSummary = " Total No of Inspections: {$iCount}, Total No of Inline Inspection: {$iInline}, Total No of Final Inspection: {$iFinal}, Total No of Passed Final Inspections: {$iFinalPassed}, AOQL Based on Passed Final Inspections: {$fDr}%, Total Garments Inspected: {$iTgi}, Total Garments Rejected: {$iTgr}";

                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, $sSummary);
                $objPhpExcel->getActiveSheet()->mergeCells("A{$iRow}:Y{$iRow}");	
                $objPhpExcel->getActiveSheet()->getRowDimension($iRow)->setRowHeight(30);
                $objPhpExcel->getActiveSheet()->getStyle("A{$iRow}:Y{$iRow}")->applyFromArray($sHeadingStyle);	

                $objPhpExcel->getActiveSheet()->setAutoFilter('A3:Y3');


                $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
                $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B &R ');

                $objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                $objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

                $objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
                $objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.3);
                $objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
                $objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

                $objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);



                $sExcelFile = "Inspection Summary Report.xlsx";


                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
                header("Cache-Control: max-age=0");

                $objWriter = PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
                $objWriter->save("php://output");

        }
        else
        {
                $objPhpExcel = $objReader->load("../templates/mgf-auditors-report.xlsx");
                $objPhpExcel->getProperties()->setCreator("Triple Tree")
                                                                         ->setLastModifiedBy($_SESSION["Name"])
                                                                         ->setTitle("MGF Report")
                                                                         ->setSubject("Auditors Performance Report")
                                                                         ->setDescription("Auditors Performance Report")
                                                                         ->setKeywords("")
                                                                         ->setCategory("Reports");

                $objPhpExcel->setActiveSheetIndex(0);
                $objPhpExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(12);
                $objPhpExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPhpExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(30);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "({$sFilters})");
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 2, "({$sFilters2})");

                
                $sSQL = "SELECT COUNT(1) as _Audits, qa.user_id, qa.audit_stage,                                               
                                    (SELECT name FROM tbl_users WHERE id = qa.user_id) AS _Auditor
                                 FROM tbl_qa_reports qa, tbl_po po
                                 WHERE qa.po_id=po.id AND qa.brand_id = po.brand_id AND qa.audit_result != '' AND qa.audit_type='B' $sConditions
                                 GROUP BY qa.user_id, qa.audit_stage    
                                 ORDER BY qa.user_id DESC";

                $objDb->query($sSQL);

                $iCount       = $objDb->getCount( );
                $iInline      = 0;
                $iFinal       = 0;
                $iFinalPassed = 0;
                $iTgi         = 0;
                $iTgr         = 0;
                $iRow         = 5;
                $iPrevAuditor = "";
                
                for ($i = 0; $i < $iCount; $i ++)
                {
                        $iAudits            = $objDb->getField($i, "_Audits");
                        $sAuditStage        = $objDb->getField($i, "audit_stage");
                        $iAuditor           = $objDb->getField($i, "user_id");
                        $sAuditor           = $objDb->getField($i, "_Auditor");

                        if($i > 0 && $iPrevAuditor == $iAuditor)
                                $iRow --;
                        
                        if($sAuditStage == 'PR')
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $iAudits);
                        
                        else if($sAuditStage == 'II')
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, $iAudits);

                        else if($sAuditStage == 'ID')
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, $iAudits);
                        
                        else if($sAuditStage == 'F')
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, $iAudits);
                        
                        else if($sAuditStage == 'SE')
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, $iAudits);
                        
                        else if($sAuditStage == 'TP')
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $iRow, $iAudits);

                        if($iPrevAuditor != $iAuditor)   
                        {
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, $sAuditor);
                            
                            $objPhpExcel->getActiveSheet()->getStyle("A{$iRow}:E{$iRow}")->getAlignment( )->setWrapText(true);
                            
                            for ($j = 0; $j <= 6; $j ++)
                                $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, (getExcelCol($j + 65)."{$iRow}:".getExcelCol($j + 65)."{$iRow}"));
                        }

                        $iRow ++;
                        $iPrevAuditor = $iAuditor;
                }


                $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
                $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B &R ');

                $objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                $objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

                $objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
                $objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.3);
                $objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
                $objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

                $objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);


                $sExcelFile = "Auditors Performance Report.xlsx";


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