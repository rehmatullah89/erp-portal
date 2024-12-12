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
        $ReportType  = IO::strValue('ReportType');
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
                                                           'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'A50716')) );


        $sBorderStyle = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
                                                  'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                         'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                         'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                         'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));

            $sReportTypes1 = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
            $sReportTypes  = implode(",",array_intersect(explode(",", $sReportTypes1),array(44,45)));;
            
            $sAuditStages1 = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");            
            $sAuditStages2 = getDbValue("stages", "tbl_reports", "id IN (44,45)");
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


            if($ReportType == 'MR')
            {
                $objPhpExcel = $objReader->load("../templates/levis-measurement-report.xlsx");
                $objPhpExcel->getProperties()->setCreator("Triple Tree")
                                                                         ->setLastModifiedBy($_SESSION["Name"])
                                                                         ->setTitle("Levis Measurement Report")
                                                                         ->setSubject("Levis Measurement Report")
                                                                         ->setDescription("Levis Measurement Report")
                                                                         ->setKeywords("")
                                                                         ->setCategory("Reports");

                $objPhpExcel->setActiveSheetIndex(0);
                $objPhpExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
                $objPhpExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPhpExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(30);

                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "({$sFilters})");
                
                    $sSQL = "SELECT qa.id,
                                    qa.audit_code,
                                    qa.sizes,
                                    qa.style_id
                     FROM tbl_qa_reports qa
                     WHERE qa.audit_result!='' AND qa.audit_type='B' $sConditions
                     ORDER BY qa.id DESC";
                    
                    $objDb->query($sSQL);

                    $iCount = $objDb->getCount();
                    $iRow   = 4;
                    
                    for ($i = 0; $i < $iCount; $i ++)
                    {
                            $iAudit             = $objDb->getField($i, "id");
                            $sAuditCode         = $objDb->getField($i, "audit_code");
                            $sSizes             = $objDb->getField($i, "sizes");
                            $iStyle             = $objDb->getField($i, "style_id");

                            
                            $sSQL2 = "SELECT qrs.id, qrs.sample_no, qrs.size_id, qrs.color, qrs.size, qrs.nature
                                    FROM tbl_qa_report_samples qrs
                                    WHERE qrs.audit_id='$iAudit'                                        
                                    ORDER BY qrs.sample_no, qrs.size_id";
                            $objDb2->query($sSQL2);
                            
                            $iCount2 = $objDb2->getCount();
                            
                            for($j = 0; $j < $iCount2; $j ++)
                            {
                                $iSampleId      = $objDb2->getField($j, 'id');
				$iSampleNo      = $objDb2->getField($j, 'sample_no');
				$sSize          = $objDb2->getField($j, 'size');
				$sColor         = $objDb2->getField($j, 'color');
                                $iSamplingSize  = $objDb2->getField($j, 'size_id');
                                $sPointsNature  = $objDb2->getField($j, 'nature');
 
                                $sSQL3 = "SELECT qrs.sample_no, qrss.point_id, qrss.findings, qrss.specs
                                            FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
                                            WHERE qrs.audit_id='$iAudit' AND qrs.sample_no= '$iSampleNo' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSamplingSize' AND qrs.color='$sColor' AND (qrs.size='' OR qrs.size='$sSize')
                                            ORDER BY qrs.sample_no, qrss.point_id";
                                $objDb3->query($sSQL3);

                                $iCount3 = $objDb3->getCount();
                                
                                if($iCount3 == 0)
                                    continue;
                                
                                if($j == 0)
                                {
                                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, "Audit");
                                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, "Size");                                        
                                }
                                
                                $sSizeFindings  = array( );
                                $sSpecFindings  = array( );
                                
                                for($k = 0; $k < $iCount3; $k ++)
                                {
                                        $iSampleNo      = $objDb3->getField($k, 'sample_no');
                                        $iPoint         = $objDb3->getField($k, 'point_id');
                                        $sFindings      = $objDb3->getField($k, 'findings');
                                        $sSizeSpec      = $objDb3->getField($k, 'specs');

                                        $sSizeFindings["{$iPoint}"] = (($sFindings == '' || $sFindings == '0' || strtolower($sFindings) == 'ok' || strtolower($sFindings) == '-')?'-':$sFindings);
                                        $sSpecFindings["{$iPoint}"] = $sSizeSpec;
                                }
                                
                                $sSQL4 = "SELECT point_id, specs, nature,
							(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
							(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point,
                                                        (SELECT point_id FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _PointId
					 FROM tbl_style_specs
					 WHERE style_id='$iStyle' AND size_id='$iSamplingSize' AND version='0'
					 ORDER BY FIELD(nature, 'C') DESC";
            
                                $objDb3->query($sSQL4);

                                $iCount4 = $objDb3->getCount();
         
                                for($m = 0; $m < $iCount4; $m ++)
                                {
                                    $iPoint     = $objDb3->getField($m, 'point_id');
                                    $sNature    = $objDb3->getField($m, 'nature');
                                    $sPoint     = $objDb3->getField($m, '_Point');
                                    $sPointId   = $objDb3->getField($m, '_PointId');
                                    $sTolerance = $objDb3->getField($m, '_Tolerance');
                                    $sFindings  = @$sSizeFindings["{$iPoint}"];
                                    
                                    if(@in_array($sPointId, array("INS1","INSEC")))
                                        $sSpecs  = $sSpecFindings["{$iPoint}"];
                                    else
                                        $sSpecs  = $objDb3->getField($m, 'specs');  
                                    
                                    if($j == 0)
                                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2+$m, $iRow, $sPointId);
                                        
                                    if($m == 0)
                                    {
                                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow+1, $sAuditCode);
                                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow+1, $sSize);
                                    }
                                    
                                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2+$m, $iRow+1, ("F: ".(($sFindings == "" || $sFindings == "-") ?'na':"{$sFindings}")."/ S: ".($sSpecs == ""?'na':"{$sSpecs}")));                                        
                                    
                                }
                                
                                if($j == 0)
                                {
                                    $objPhpExcel->getActiveSheet()->getStyle("A{$iRow}:AZ{$iRow}")->getAlignment( )->setWrapText(true);
                                    $objPhpExcel->getActiveSheet()->duplicateStyleArray($sHeadingStyle, ("A{$iRow}:AZ{$iRow}")); 
                                }
                                    
                                    $iRow += 1;                                
                            }
                                    $iRow += 2;                            
                    }

                    $objPhpExcel->getActiveSheet()->setAutoFilter('A3:AZ3');

                    $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
                    $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B &R ');

                    $objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                    $objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

                    $objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
                    $objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.3);
                    $objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
                    $objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

                    $objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);


                    $sExcelFile = "Levis Measurements Report.xlsx";


                    header("Content-Type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
                    header("Cache-Control: max-age=0");

                    $objWriter = PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
                    $objWriter->save("php://output");
            }
            else
            {
                $objPhpExcel = $objReader->load("../templates/levis-report.xlsx");
                $objPhpExcel->getProperties()->setCreator("Triple Tree")
                                                                         ->setLastModifiedBy($_SESSION["Name"])
                                                                         ->setTitle("Levis Report")
                                                                         ->setSubject("Inspection Summary Report")
                                                                         ->setDescription("Inspection Summary Report")
                                                                         ->setKeywords("")
                                                                         ->setCategory("Reports");

                $objPhpExcel->setActiveSheetIndex(0);
                $objPhpExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
                $objPhpExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPhpExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(30);

                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "({$sFilters})");


                $sAuditorsList    = getList("tbl_users", "id", "name");
                $sVendorsList     = getList("tbl_vendors", "id", "CONCAT(`code`,'-', `vendor`)");    
                $sBrandsList      = getList("tbl_brands", "id", "brand", "parent_id>'0'");
                $sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");
                $sReportsList     = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes')");
                $sAuditTypes      = getList("tbl_audit_types", "id", "`type`");

                $sSQL = "SELECT     qa.id,
                                    qa.audit_code,
                                    qa.po_id,
                                    qa.sizes,
                                    qa.audit_type_id,
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
                                    (SELECT SUM(IF(nature='2', defects, '0')) From tbl_qa_report_defects Where audit_id=qa.id) AS _Critical,
                                    (SELECT SUM(IF(nature='1', defects, '0')) From tbl_qa_report_defects Where audit_id=qa.id) AS _Major,
                                    (SELECT GROUP_CONCAT(DISTINCT defect SEPARATOR ',') From tbl_defect_codes Where report_id IN (44,45) AND id IN (SELECT DISTINCT code_id From tbl_qa_report_defects Where audit_id=qa.id)) AS _DefectsDesc,
                                    (SELECT style FROM tbl_styles WHERE id=qa.style_id) AS _Style,
                                    (SELECT season FROM tbl_seasons WHERE id IN (SELECT sub_season_id FROM tbl_styles WHERE id IN (qa.style_id)) Limit 1) AS _Season,
                                    (SELECT country FROM tbl_countries WHERE id = (SELECT country_id From tbl_vendors Where id=qa.vendor_id)) AS _Country,
                                    (SELECT name FROM tbl_users WHERE id=qa.user_id) AS _Auditor
                     FROM tbl_qa_reports qa
                     WHERE qa.audit_result!='' AND qa.audit_type='B' $sConditions
                     ORDER BY qa.id DESC";    
                $objDb->query($sSQL);

                $iCount       = $objDb->getCount( );
                $iRow         = 4;

                for ($i = 0; $i < $iCount; $i ++, $iRow ++)
                {
                        $iAudit             = $objDb->getField($i, "id");
                        $sAuditCode         = $objDb->getField($i, "audit_code");
                        $iPoId              = $objDb->getField($i, "po_id");
                        $iSizes             = $objDb->getField($i, "sizes");
                        $iAuditType         = $objDb->getField($i, "audit_type_id");
                        $sAdditionalPos     = $objDb->getField($i, "additional_pos");
                        $sSeason            = $objDb->getField($i, "_Season");
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
                        $iCriticalDefects   = $objDb->getField($i, "_Critical");
                        $iMajorDefects      = $objDb->getField($i, "_Major");
                        $sDefectDesc        = $objDb->getField($i, "_DefectsDesc");
                        $sCountry           = $objDb->getField($i, "_Country");
                       
                        switch ($sAuditResult)
                        {
                                case "P"  :  $sAuditResult = "Pass"; break;
                                case "F"  :  $sAuditResult = "Fail"; break;
                                case "H"  :  $sAuditResult = "Hold"; break;
                                case "N"  :  $sAuditResult = "Fail-NV"; break;
                                case "E"  :  $sAuditResult = "Exception"; break;
                                case "R"  :  $sAuditResult = "Rescreen"; break;
                        }

                        $sAllPOs = $iPoId;

                        if(!empty($sAdditionalPos))
                            $sAllPOs .= (",".$sAdditionalPos);

                        $iOrderQty = getDbValue("SUM(quantity)", "tbl_po", "id IN ($sAllPOs)");
                        $sVpoNos   = getDbValue("GROUP_CONCAT(order_no SEPARATOR ',')", "tbl_po", "id IN ($sAllPOs)");

                       
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, $sAuditCode);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $sVpoNos);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, $sSeason);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, formatDate($sAuditDate));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, $sStartTime);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, $sEndTime);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $iRow, $sAuditTypes[$iAuditType]);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $iRow, $sAuditStagesList[$sAuditStage]);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $iRow, getDbValue("GROUP_CONCAT(size SEPARATOR ',')", "tbl_sizes", "id IN ($iSizes)"));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $iRow, $sColors);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $iRow, getDbValue("item_number", "tbl_po", "id='$iPoId'"));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $iRow, getDbValue("product_code", "tbl_po", "id='$iPoId'"));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $iRow, ($iReport == '44'?"Tops":"Bottoms"));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $iRow, formatNumber($iSampleSize, false));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $iRow, formatNumber($iOrderQty, false));
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $iRow, $sDefectDesc);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $iRow, "Critical: ".$iCriticalDefects.", Major: ".$iMajorDefects);//formatNumber($iWorkmanshipDefects, false)
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $iRow, formatNumber(($iCriticalDefects + $iMajorDefects), false));//formatNumber($iMaterialDefects, false)
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $iRow, $sBrandsList[$iBrand]);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $iRow, $sVendorsList[$iVendor]);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $iRow, $sCountry);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $iRow, $sAuditor);
                        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $iRow, $sAuditResult);

                        $objPhpExcel->getActiveSheet()->getStyle("A{$iRow}:W{$iRow}")->getAlignment( )->setWrapText(true);
                        $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, ("A{$iRow}:W{$iRow}"));                        
                        
                         
                }
                
                $objPhpExcel->getActiveSheet()->setAutoFilter('A3:W3');

                $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
                $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B &R ');

                $objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                $objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

                $objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
                $objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.3);
                $objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
                $objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

                $objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

                $sExcelFile = "Levis Inspection Reports.xlsx";


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