<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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

        $AuditCode   = IO::strValue("AuditCode");
	$Report      = IO::intValue("Report");
	$Vendor      = IO::intValue("Vendor");
	$MasterId    = IO::strValue("MasterId");
	$ReportStatus= IO::strValue("ReportStatus");
	$Unit        = IO::strValue("Unit");
	$Floor       = IO::intValue("Floor");
	$Line        = IO::intValue("Line");
	$Color       = IO::strValue("Color");
	$OrderNo     = IO::strValue("OrderNo");
	$StyleNo     = IO::strValue("StyleNo");
	$Auditor     = IO::intValue("Auditor");
	$Brand       = IO::intValue("Brand");
	$AuditStage  = IO::strValue("AuditStage");
	$Region      = IO::intValue("Region");
	$FromDate    = IO::strValue("FromDate");
	$ToDate      = IO::strValue("ToDate");
	$AuditResult = IO::strValue("AuditResult");
	$Department  = IO::intValue("Department");
	$Customer    = IO::strValue("Customer");
	$Season      = IO::intValue("Season");
	$Program     = IO::intValue("Program");
	$DesignNo    = IO::strValue("DesignNo");
	$DesignName  = IO::strValue("DesignName");
	$AuditorType = IO::intValue("AuditorType");
        
        $sCountriesList   = getList("tbl_countries", "id", "country");
        $sReportTypes     = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
        $sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
        
	$sConditions = " WHERE audit_result!='' AND FIND_IN_SET(report_id, '$sReportTypes') ";

	if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE)
		$sConditions = " WHERE audit_result!='' AND status!='' ";

	if ($AuditCode != "")
		$sConditions .= " AND audit_code LIKE '%$AuditCode%' ";

	if ($Auditor > 0)
		$sConditions .= " AND user_id='$Auditor' ";

	if ($Report > 0)
		$sConditions .= " AND report_id='$Report' ";

	else
		$sConditions .= " AND FIND_IN_SET(report_id, '$sReportTypes') ";

	if ($AuditResult != "")
		$sConditions .= " AND audit_result='$AuditResult' ";

	if ($AuditStage != "")
		$sConditions .= " AND audit_stage='$AuditStage' ";

	else
		$sConditions .= " AND FIND_IN_SET(audit_stage, '$sAuditStages') ";

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	else
		$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";

	if($MasterId != "")        
		$sConditions .= " AND master_id = '$MasterId' ";
                
	if ($Unit > 0)
		$sConditions .= " AND unit_id='$Unit' ";

	if ($Floor > 0)
		$sConditions .= " AND line_id IN (SELECT id FROM tbl_lines WHERE floor_id='$Floor') ";

	if ($Line > 0)
		$sConditions .= " AND line_id='$Line' ";

	if ($Region > 0)
		$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Department > 0)
		$sConditions .= " AND department_id='$Department' ";


	if ($Brand > 0)
		$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}') ";

	else
		$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']}) AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}') ";

	if ($Season > 0)
		$sSQL .= " AND sub_season_id='$Season' ";

	if ($Program > 0)
		$sSQL .= " AND program_id='$Program' ";

	if ($DesignNo != "")
		$sSQL .= " AND design_no='$DesignNo' ";

	if ($DesignName != "")
		$sSQL .= " AND design_name='$DesignName' ";


	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$sStyles = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sStyles .= (",".$objDb->getField($i, 0));

	if ($sStyles != "")
		$sStyles = substr($sStyles, 1);

	if (@strpos($_SESSION["Email"], "apparelco.com") !== FALSE || @strpos($_SESSION["Email"], "3-tree.com") !== FALSE)
		$sConditions .= " AND (style_id='0' OR style_id IN ($sStyles)) ";

	else
		$sConditions .= " AND style_id IN ($sStyles) ";


	if ($OrderNo != "")
	{
		$sConditions .= " AND (";


		$sSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '%$OrderNo%'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPoId = $objDb->getField($i, 0);

			if ($i > 0)
				$sConditions .= " OR ";

			$sConditions .= "po_id='$iPoId' OR FIND_IN_SET('$iPoId', additional_pos) ";
		}

		$sConditions .= ") ";
	}

	if ($Brand > 0)
	{
		$sSQL = "SELECT id FROM tbl_po WHERE brand_id='$Brand'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND po_id IN ($sPos) ";
	}

	else
	{
		if ($Vendor > 0)
		{
			$sSQL = "SELECT id FROM tbl_po WHERE vendor_id='$Vendor' AND brand_id IN ({$_SESSION['Brands']})";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
			$sPos   = "";

			for ($i = 0; $i < $iCount; $i ++)
				$sPos .= (",".$objDb->getField($i, 0));

			if ($sPos != "")
				$sPos = substr($sPos, 1);

			$sConditions .= " AND po_id IN ($sPos) ";
		}

		else
			$sConditions .= " AND (po_id='0' OR po_id IN (SELECT id FROM tbl_po WHERE vendor_id IN ({$_SESSION['Vendors']}) AND brand_id IN ({$_SESSION['Brands']})))";
	}

	if ($StyleNo != "")
		$sConditions .= " AND style_id IN (SELECT id FROM tbl_styles WHERE style LIKE '%$StyleNo%' AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')) ";

	if ($Color != "")
	{
		$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE color='$Color'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND po_id IN ($sPos) ";
	}

	if($ReportStatus != "")
		$sConditions .= " AND published = '$ReportStatus' ";
		
	if ($AuditorType > 0)
		$sConditions .= " AND user_id IN (SELECT id from tbl_users WHERE status='A' AND auditor='Y' AND user_type='MGF' AND auditor_type='$AuditorType' ) ";



	$objPhpExcel = new PHPExcel( );

	$objPhpExcel->getProperties()->setCreator("Matrix Sourcing")
								 ->setLastModifiedBy("Matrix Sourcing")
								 ->setTitle("QA Reports")
								 ->setSubject("QA Reports")
								 ->setDescription("QA Reports Analysis")
								 ->setKeywords("")
								 ->setCategory("QA Reports");

	$objPhpExcel->setActiveSheetIndex(0);


	$objPhpExcel->getActiveSheet()->setCellValue("A1", "M G F  S O U R C I N G");
	$objPhpExcel->getActiveSheet()->mergeCells("A1:P1");
	$objPhpExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(28);
	$objPhpExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);

	$objPhpExcel->getActiveSheet()->setCellValue("A2", "QA Reports");
	$objPhpExcel->getActiveSheet()->mergeCells("A2:P2");
	$objPhpExcel->getActiveSheet()->getStyle("A2")->getFont()->setSize(16);
	$objPhpExcel->getActiveSheet()->getStyle("A2")->getFont()->setBold(true);

	$objPhpExcel->getActiveSheet()->setCellValue("A3", "As on: ".date("l, F d, Y h:i A"));
	$objPhpExcel->getActiveSheet()->mergeCells("A3:O3");
	$objPhpExcel->getActiveSheet()->getStyle("A3")->getFont()->setSize(11);


	$sHeadingStyle = array('font' => array('bold' => true, 'size' => 11),
						   'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
						   'borders'   => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											  'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											  'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											  'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)) );

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


	$iRow = 5;

	$objPhpExcel->getActiveSheet()->setCellValue("A{$iRow}", "Audit Code");
        $objPhpExcel->getActiveSheet()->setCellValue("B{$iRow}", "PO(s)");
        $objPhpExcel->getActiveSheet()->setCellValue("C{$iRow}", "Style");
        $objPhpExcel->getActiveSheet()->setCellValue("D{$iRow}", "Vendor");
        $objPhpExcel->getActiveSheet()->setCellValue("E{$iRow}", "Country of Origin");
	$objPhpExcel->getActiveSheet()->setCellValue("F{$iRow}", "Audit Date");
        $objPhpExcel->getActiveSheet()->setCellValue("G{$iRow}", "Start Time");
        $objPhpExcel->getActiveSheet()->setCellValue("H{$iRow}", "End Time");
	$objPhpExcel->getActiveSheet()->setCellValue("I{$iRow}", "Sample Size");
        $objPhpExcel->getActiveSheet()->setCellValue("J{$iRow}", "Inspection Ship Qty");        
        $objPhpExcel->getActiveSheet()->setCellValue("K{$iRow}", "Audit Stage");
      	$objPhpExcel->getActiveSheet()->setCellValue("L{$iRow}", "Audit Status");
	$objPhpExcel->getActiveSheet()->setCellValue("M{$iRow}", "Report Type");
      	$objPhpExcel->getActiveSheet()->setCellValue("N{$iRow}", "Auditor");
        $objPhpExcel->getActiveSheet()->setCellValue("O{$iRow}", "Inspector Type");
	$objPhpExcel->getActiveSheet()->setCellValue("P{$iRow}", "Published");

	//for ($i = 0; $i < 16; $i ++)
	//	$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , ((getExcelCol($i + 65))."{$iRow}:".(getExcelCol($i + 65)).$iRow));
        $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , "A{$iRow}:P{$iRow}");

	$sUsersList       = getList("tbl_users", "id", "name");
	$sVendorsList     = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage");
        $sReportsList     = getList("tbl_reports", "id", "report");


	$sSQL = "SELECT id, user_id, audit_code, po_id, additional_pos, vendor_id, start_time, end_time, ship_qty, total_gmts, audit_stage, audit_result, audit_date, report_id, published,
	                (SELECT style FROM tbl_styles WHERE id=tbl_qa_reports.style_id) AS _Style,
                        (SELECT auditor_type FROM tbl_users where id=tbl_qa_reports.user_id) _AuditorType,
                        (SELECT country_id FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Country
	         FROM tbl_qa_reports
	         $sConditions
	         ORDER BY id DESC";
        $objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$iRow   += 1;

	for ($i = 0; $i < $iCount; $i ++, $iRow ++)
	{
		$iId            = $objDb->getField($i, 'id');
		$iUserId        = $objDb->getField($i, 'user_id');
		$sAuditCode     = $objDb->getField($i, 'audit_code');
		$iPoId          = $objDb->getField($i, 'po_id');
                $sAdditionalPos = $objDb->getField($i, 'additional_pos');
		$sStyle         = $objDb->getField($i, '_Style');
		$iVendor        = $objDb->getField($i, 'vendor_id');
		$sAuditStage    = $objDb->getField($i, 'audit_stage');
		$sAuditResult   = $objDb->getField($i, 'audit_result');
		$sAuditDate     = $objDb->getField($i, 'audit_date');
		$iReportId      = $objDb->getField($i, 'report_id');
		$sPublished     = $objDb->getField($i, 'published');
                $iCountry       = $objDb->getField($i, '_Country');
                $sStartTime     = $objDb->getField($i, 'start_time');
                $sEndTime       = $objDb->getField($i, 'end_time');
                $iSampleSize    = $objDb->getField($i, 'total_gmts');
                $iShipQty       = $objDb->getField($i, 'ship_qty');
                $iAuditorType   = $objDb->getField($i, '_AuditorType');
		$sAllPos        = "";

		
		$fHours       = getDbValue("c.hours", "tbl_countries c, tbl_vendors v", "c.id = v.country_id AND v.id='$iVendor'");
		$sStartTime   = date("H:i A", (strtotime($sStartTime) + ($fHours * 3600)));
		$sEndTime     = date("H:i A", (strtotime($sEndTime) + ($fHours * 3600)));
		
                $sPoIds = $iPoId;
                if($sAdditionalPos != "")
                    $sPoIds .= (",".$sAdditionalPos);

		if ($iPoId > 0)
		{
			$sSQL = "SELECT CONCAT(order_no, ' ', order_status) AS _PO, brand_id FROM tbl_po WHERE id IN ($sPoIds)";
			$objDb2->query($sSQL);

                        for($j=0; $j < $objDb2->getCount(); $j++)
                            $sAllPos  .= ($objDb2->getField($j, '_PO').",");
                        
                        $sAllPos = rtrim($sAllPos, ",");
		}

                switch ($iAuditorType)
		{
			case 1 : $sAuditorType = "MCA"; break;
			case 2 : $sAuditorType = "FCA"; break;
			case 3 : $sAuditorType = "3rd Party Auditor"; break;
			case 4 : $sAuditorType = "QMIP Auditor"; break;
			case 5 : $sAuditorType = "QMIP Corelation Auditor"; break;
                        case 14: $sAuditorType = "MGF 3rd Party"; break;
		}
                
		if ($_SESSION["UserType"] == "MGF")
		{

                    switch ($sAuditResult)
                    {
                            case "P" : $sAuditResult = "Accepted"; break;
                            case "F" : $sAuditResult = "Rejected"; break;
                            case "H" : $sAuditResult = "Hold"; break;
                            case "R" : $sAuditResult = "Re-Inspection"; break;
                    }
                    
                }
                else if ($_SESSION["UserType"] == "LEVIS")
		{

                    switch ($sAuditResult)
                    {
                            case "P" : $sAuditResult = "Pass"; break;
                            case "F" : $sAuditResult = "Fail"; break;
                            case "N" : $sAuditResult = "Fail-NV"; break;
                            case "E" : $sAuditResult = "Exception"; break;
                            case "R" : $sAuditResult = "Rescreen"; break;
                    }
                    
                }
                else{
                    
                    switch ($sAuditResult)
                    {
                            case "A" : $sAuditResult = "Pass"; break;
                            case "B" : $sAuditResult = "Pass"; break;
                            case "C" : $sAuditResult = "Fail"; break;
                            case "P" : $sAuditResult = "Pass"; break;
                            case "F" : $sAuditResult = "Fail"; break;
                            case "H" : $sAuditResult = "Hold"; break;
                            case "R" : $sAuditResult = "Re-Inspection"; break;
                    }                    
                }

		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, $sAuditCode);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $sAllPos);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, $sStyle);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, $sVendorsList[$iVendor]);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, $sCountriesList[$iCountry]);                
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, formatDate($sAuditDate));
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $iRow, $sStartTime);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $iRow, $sEndTime);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $iRow, formatNumber($iSampleSize,false));
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $iRow, formatNumber($iShipQty,false));                
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $iRow, $sAuditStagesList[$sAuditStage]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $iRow, $sAuditResult);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $iRow, $sReportsList[$iReportId]);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $iRow, $sUsersList[$iUserId]);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $iRow, $sAuditorType);                
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $iRow, ($sPublished == 'Y'?'Yes':'No'));

		//for ($j = 0; $j < 16; $j ++)
		//	$objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, (getExcelCol($j + 65).$iRow.":".getExcelCol($j + 65).$iRow));
                
                $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle , "A{$iRow}:P{$iRow}");
	}

        $objPhpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(13);
        $objPhpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPhpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

	for ($i = 3; $i < 16; $i ++)
		$objPhpExcel->getActiveSheet()->getColumnDimension(getExcelCol($i + 65))->setAutoSize(true);



	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&L&B QA Report &R Generated on ".date("d-M-Y"));

	$objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	$objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	$objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

	$objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

	$objPhpExcel->getActiveSheet()->setTitle("QA Reports Data Analysis");


	$sExcelFile = "QA Reports.xlsx";

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
	$objWriter->save("php://output");



	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>