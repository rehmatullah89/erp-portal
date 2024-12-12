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
        $Year        = IO::intValue('Year');
        $NextYear    = ($Year + 1);
	$FromDate    = "{$Year}-02-01";
	$ToDate      = "{$NextYear}-01-31";
	$Auditor     = IO::intValue("Auditor");
        $ReportType  = IO::strValue('ReportType');
        
        $sParentsList   = getList("tbl_vendors", "id", "vendor", "parent_id='0'");
        
        if($ReportType != 'PS')
        {
            $FromDate  = IO::strValue('FromDate');
            $ToDate    = IO::strValue('ToDate');
        }
        
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
	
	/*if ($AuditStage != "")
	{
		if ($sFilters != "")
			$sFilters .= ", ";
	
	}*/
        
        $sFilters .= ("Stage: ".getDbValue("stage", "tbl_audit_stages", "code='F'"));
	
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
                                                           'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '808080')) );


        $sBorderStyle = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
                                                  'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                         'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                         'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                         'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'ffffff')));

            /*$sReportTypes1 = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
            $sReportTypes  = implode(",",array_intersect(explode(",", $sReportTypes1),array(44,45)));;
            
            $sAuditStages1 = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");            
            $sAuditStages2 = getDbValue("stages", "tbl_reports", "id IN (44,45)");
            $sAuditStages  = implode(",",array_intersect(explode(",", $sAuditStages1),explode(",", $sAuditStages2)));*/
            
            if($Brand == 500)
                $sConditions  = " AND qa.report_id='14' ";
            else
                $sConditions  = " AND qa.report_id='46' ";
            
/*            if ($Brand != "")
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

            $iCount = $objDb->getCount( );*/
      
            if ($Brand != "")
                    $sConditions .= " AND qa.brand_id='$Brand' ";

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

           /* if ($AuditStage != "")*/

            $sConditions .= " AND qa.audit_stage='F'";

            if ($Auditor > 0)
                    $sConditions .= " AND qa.user_id='$Auditor' ";

            if ($AuditResult != "")
                    $sConditions .= " AND qa.audit_result='$AuditResult'";

            $sDateConditions = "";
                    
            if ($FromDate != "" && $ToDate != "")
                    $sDateConditions = " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";


            if($ReportType == 'PS')
            {
                $objPhpExcel = $objReader->load("../templates/jcrew-ps-report.xlsx");
                $objPhpExcel->getProperties()->setCreator("Triple Tree")
                                                                         ->setLastModifiedBy($_SESSION["Name"])
                                                                         ->setTitle("J.Crew PS Quality Report")
                                                                         ->setSubject("J.Crew PS Quality Report")
                                                                         ->setDescription("J.Crew PS Quality Report")
                                                                         ->setKeywords("")
                                                                         ->setCategory("Reports");

                $objPhpExcel->setActiveSheetIndex(0);
                $objPhpExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
                $objPhpExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPhpExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(30);

                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "({$sFilters})");
				
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 5, "Quarter 1\n(Feb {$Year} - Apr {$Year})");
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 5, "Quarter 2\n(May {$Year} - Jul {$Year})");
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 5, "Quarter 3\n(Aug {$Year} - Oct {$Year})");
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 5, "Quarter 4\n(Nov {$Year} - Jan {$NextYear})");
				
                
                $sSQL = "SELECT COUNT(qa.id) as _TotalAudits, qa.vendor_id,
                                    (SELECT vendor from tbl_vendors WHERE id=qa.vendor_id) as _Vendor,
                                    GROUP_CONCAT(if(qa.additional_pos='',qa.po_id,CONCAT(qa.po_id,',',qa.additional_pos)) SEPARATOR ',') as _PoIds       
                            FROM tbl_qa_reports qa
                     WHERE qa.audit_result!='' $sConditions $sDateConditions
                     GROUP BY qa.vendor_id  
                     ORDER BY _Vendor";
               
                    $objDb->query($sSQL);

                    $iCount = $objDb->getCount();
                    
                    $sAllocations       = array();
                    $sAlocationOrder    = array();
                    
                    for ($i = 0; $i < $iCount; $i ++)
                    {
                        $iVendor  = $objDb->getField($i, "vendor_id");
                        $iPoIds   = $objDb->getField($i, "_PoIds");
                        
                        $sPoPrices      = getList("tbl_po_colors", "po_id", "price", "po_id IN ($iPoIds) AND order_qty> 0 AND price > 0");
                        $sPoQuantities  = getList("tbl_po_colors", "po_id", "order_qty", "po_id IN ($iPoIds) AND order_qty> 0 AND price > 0");

                        $iTotalFob = 0;

                        foreach($sPoQuantities as $iPo => $iQty)
                        {
                            $iPrice = $sPoPrices[$iPo];
                            $iTotalFob += ($iQty*$iPrice);
                        }
                        
                        $sAllocations[$iVendor] = $iTotalFob;
                    }

                    arsort($sAllocations);
                    
                    $iIndex = 1;
                    $sVendorsSorting = array();
                    foreach ($sAllocations as $iVendor => $sValue)
                    {
                        $sVendorsSorting[$iVendor] = $iVendor; 
                        $sAlocationOrder[$iVendor] = ($sValue > 0?$iIndex++:'-');
                    }
                    
                    $sVendorsSortingStr = implode(",", $sVendorsSorting); 
                    /****************************************************************/
                    $sSQL = "SELECT COUNT(qa.id) as _TotalAudits, qa.vendor_id,
                                    SUM(IF(qa.audit_result = 'P', 1,0)) AS _PassAudits,
                                    SUM(IF(qa.audit_result = 'F', 1,0)) AS _FailAudits,
                                    (SELECT vendor from tbl_vendors WHERE id=qa.vendor_id) as _Vendor,
                                    (SELECT parent from tbl_factories WHERE FIND_IN_SET(qa.vendor_id, vendors) LIMIT 1) as _Parent,
                                    GROUP_CONCAT(if(qa.additional_pos='',qa.po_id,CONCAT(qa.po_id,',',qa.additional_pos)) SEPARATOR ',') as _PoIds,
                                    (SELECT c.country from tbl_countries c, tbl_vendors v  WHERE v.country_id = c.id AND v.id=qa.vendor_id) as _Country
                     FROM tbl_qa_reports qa
                     WHERE qa.audit_result!='' $sConditions $sDateConditions
                     GROUP BY qa.vendor_id  
                     ORDER BY FIELD(qa.vendor_id, $sVendorsSortingStr), _Parent, _Vendor";

                    $objDb->query($sSQL);

                    $iCount = $objDb->getCount();                    
                    
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 6, "FOB in {$Year}");
                            
                    $iRow   = 7;
                    $iTotalQuarter1 = 0;
                    $iTotalQuarter2 = 0;
                    $iTotalQuarter3 = 0;
                    $iTotalQuarter4 = 0;
                    
                    $iTotalPassQuarter1 = 0;
                    $iTotalPassQuarter2 = 0;
                    $iTotalPassQuarter3 = 0;
                    $iTotalPassQuarter4 = 0;
                                        
                    for ($i = 0; $i < $iCount; $i ++)
                    {
                            $sParent        = $objDb->getField($i, "_Parent");
                            $iTotalAudit    = $objDb->getField($i, "_TotalAudits");
                            $iPassAudits    = $objDb->getField($i, "_PassAudits");
                            $iFailAudits    = $objDb->getField($i, "_FailAudits");
                            $iVendor        = $objDb->getField($i, "vendor_id");
                            $sVendor        = $objDb->getField($i, "_Vendor");
                            $iPoIds         = $objDb->getField($i, "_PoIds");
                            $sCountry       = $objDb->getField($i, "_Country");

                            $sPoPrices      = getList("tbl_po_colors", "po_id", "price", "po_id IN ($iPoIds) AND order_qty> 0 AND price > 0");
                            $sPoQuantities  = getList("tbl_po_colors", "po_id", "order_qty", "po_id IN ($iPoIds) AND order_qty> 0 AND price > 0");
                            
                            $iTotalFob = 0;
                            
                            foreach($sPoQuantities as $iPo => $iQty)
                            {
                                $iPrice = $sPoPrices[$iPo];
                                $iTotalFob += ($iQty*$iPrice);
                            }
                            
                            $AuditPercent = ($iPassAudits/$iTotalAudit)*100;
                            
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, $sParent);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $sVendor);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, $sCountry);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, $iTotalFob);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, $sAlocationOrder[$iVendor]);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, "");
                            
                            for($cnt = 3; $cnt<=12; $cnt += 3)
                            {
                                $FromDate = "{$Year}-".($cnt-1)."-01";
                                $ToDate   = date("Y-m-t", strtotime("+2 months", strtotime($FromDate)));
                                //$ToDate = "{$Year}-{$cnt}-31";

                                $sDateConditions = " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";
                                
                                $sSQL = "SELECT COUNT(qa.id) as _TotalAudits,
                                        SUM(IF(qa.audit_result = 'P', 1,0)) AS _PassAudits
                                FROM tbl_qa_reports qa
                                WHERE qa.audit_result!='' AND qa.vendor_id='$iVendor' $sConditions $sDateConditions
                                GROUP BY qa.vendor_id    
                                ORDER BY qa.vendor_id DESC, qa.id";
                                $objDb2->query($sSQL);

                                $iTotalSecAudit    = (int)$objDb2->getField(0, "_TotalAudits");
                                $iPassSecAudits    = (int)$objDb2->getField(0, "_PassAudits");
                                $AuditSecPercent   = (int)(($iPassSecAudits/$iTotalSecAudit)*100);
                                
                                if($cnt == 3)
                                {
                                    $iTotalQuarter1 += $iTotalSecAudit;
                                    $iTotalPassQuarter1 += $iPassSecAudits;
                                }
                                else if($cnt == 6)
                                {
                                    $iTotalQuarter2 += $iTotalSecAudit;
                                    $iTotalPassQuarter2 += $iPassSecAudits;
                                }
                                else if($cnt == 9)
                                { 
                                    $iTotalQuarter3 += $iTotalSecAudit;
                                    $iTotalPassQuarter3 += $iPassSecAudits;
                                }
                                else 
                                {
                                    $iTotalQuarter4 += $iTotalSecAudit;
                                    $iTotalPassQuarter4 += $iPassSecAudits;
                                }
                                    
                   
                                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3+$cnt, $iRow, $iTotalSecAudit);
                                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4+$cnt, $iRow, $iPassSecAudits);
                                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5+$cnt, $iRow, formatNumber($AuditSecPercent)."%");       
                            }
                            
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $iRow, formatNumber($AuditPercent)."%");
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $iRow, "");
                            
                            $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, ("A{$iRow}:T{$iRow}"));                        
                            
                            $iRow ++;                                   
                    }
                    
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, "Total");
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, "");
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, "");
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, "");
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, "");
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, "");
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $iRow, $iTotalQuarter1);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $iRow, $iTotalPassQuarter1);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $iRow, formatNumber(($iTotalPassQuarter1/$iTotalQuarter1)*100)."%");
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $iRow, $iTotalQuarter2);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $iRow, $iTotalPassQuarter2);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $iRow, formatNumber(($iTotalPassQuarter2/$iTotalQuarter2)*100)."%");
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $iRow, $iTotalQuarter3);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $iRow, $iTotalPassQuarter3);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $iRow, formatNumber(($iTotalPassQuarter3/$iTotalQuarter3)*100)."%");
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $iRow, $iTotalQuarter4);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $iRow, $iTotalPassQuarter4);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $iRow, formatNumber(($iTotalPassQuarter4/$iTotalQuarter4)*100)."%");
                    
                     $objPhpExcel->getActiveSheet()->duplicateStyleArray($sHeadingStyle, ("A{$iRow}:T{$iRow}"));
                    
                    $objPhpExcel->getActiveSheet()->setAutoFilter('A6:E6');

                    $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
                    $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B &R ');

                    $objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                    $objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

                    $objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
                    $objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.3);
                    $objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
                    $objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

                    $objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);


                    $sExcelFile = "J.Crew Quality KPI Report ".date('Y-m-d').".xlsx";


                    header("Content-Type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
                    header("Cache-Control: max-age=0");

                    $objWriter = PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
                    $objWriter->save("php://output");
            }
            else
            {
                 if ($FromDate != "" && $ToDate != "")
                        $sConditions .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";
                 
                $objPhpExcel = $objReader->load("../templates/jcrew-rr-report.xlsx");
                $objPhpExcel->getProperties()->setCreator("Triple Tree")
                                                                         ->setLastModifiedBy($_SESSION["Name"])
                                                                         ->setTitle("J.Crew Rejection Rate Report")
                                                                         ->setSubject("J.Crew Rejection Rate Report")
                                                                         ->setDescription("J.Crew Rejection Rate Report")
                                                                         ->setKeywords("")
                                                                         ->setCategory("Reports");

                $objPhpExcel->setActiveSheetIndex(0);
                $objPhpExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
                $objPhpExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPhpExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(30);

                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "({$sFilters})");
                
                    $sSQL = "SELECT COUNT(qa.id) as _TotalAudits,
                                    SUM(ship_qty) as _PresenterQty,
                                    SUM(total_gmts) as _InspectedQty,
                                    SUM(IF(qa.audit_result = 'P', 1,0)) AS _PassAudits,
                                    SUM(IF(qa.audit_result = 'F', 1,0)) AS _FailAudits,
                                    (SELECT vendor from tbl_vendors WHERE id=qa.vendor_id) as _Vendor,
                                    (SELECT parent from tbl_factories WHERE FIND_IN_SET(qa.vendor_id, vendors) LIMIT 1) as _Parent,
                                    (SELECT brand from tbl_brands WHERE id=qa.brand_id) as _Brand,
                                    (SELECT c.country from tbl_countries c, tbl_vendors v  WHERE v.country_id = c.id AND v.id=qa.vendor_id) as _Country
                     FROM tbl_qa_reports qa
                     WHERE qa.audit_result!='' $sConditions
                     GROUP BY qa.vendor_id    
                     ORDER BY _Parent, _Vendor";

                    $objDb->query($sSQL);

                    $iCount = $objDb->getCount();
                    $iRow   = 5;
                    
                    $iTotalInspections = 0;
                    $iTotalPresented   = 0;
                    $iTotalInspectedPcs= 0;
                    $iTotalAcceptedPcs = 0;
                    $iTotalRejectedPcs = 0;
                    
                    for ($i = 0; $i < $iCount; $i ++)
                    {
                            $iTotalAudit    = $objDb->getField($i, "_TotalAudits");
                            $iPassAudits    = $objDb->getField($i, "_PassAudits");
                            $iFailAudits    = $objDb->getField($i, "_FailAudits");
                            $iPresentedQty  = $objDb->getField($i, "_PresenterQty");
                            $iInspectedQty  = $objDb->getField($i, "_InspectedQty");
                            $sVendor        = $objDb->getField($i, "_Vendor");
                            $sParent        = $objDb->getField($i, "_Parent");
                            $sBrand         = $objDb->getField($i, "_Brand");
                            $sCountry       = $objDb->getField($i, "_Country");

                            
                            
                            $PassAuditsPercent = ($iPassAudits/$iTotalAudit)*100;
                            $FailAuditsPercent = ($iFailAudits/$iTotalAudit)*100;
                            
                            $iTotalInspections += $iTotalAudit;
                            $iTotalPresented   += $iPresentedQty;
                            $iTotalInspectedPcs+= $iInspectedQty;
                            $iTotalAcceptedPcs += $iPassAudits;
                            $iTotalRejectedPcs += $iFailAudits;
                            
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, $sParent);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $sVendor);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, $sCountry);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, $iTotalAudit);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, $iPresentedQty);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, $iInspectedQty);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $iRow, $iPassAudits);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $iRow, formatNumber($PassAuditsPercent)."%");
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $iRow, $iFailAudits);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $iRow, formatNumber($FailAuditsPercent)."%");
                            
                            $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, ("A{$iRow}:J{$iRow}"));                        
                            
                            $iRow ++;                                   
                    }

                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, "Total");
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, "");
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, "");
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, $iTotalInspections);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, $iTotalPresented);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, $iTotalInspectedPcs);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $iRow, $iTotalAcceptedPcs);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $iRow, "");
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $iRow, $iTotalRejectedPcs);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $iRow, "");
                            
                    $objPhpExcel->getActiveSheet()->duplicateStyleArray($sHeadingStyle, ("A{$iRow}:J{$iRow}"));    
                    //$objPhpExcel->getActiveSheet()->setAutoFilter('A1:U3');

                    $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
                    $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B &R ');

                    $objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                    $objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

                    $objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
                    $objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.3);
                    $objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
                    $objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

                    $objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);


                    $sExcelFile = "J.Crew Rejection Rate Report ".date('Y-m-d').".xlsx";


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