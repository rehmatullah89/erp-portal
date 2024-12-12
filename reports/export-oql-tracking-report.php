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
        
        @ini_set('display_errors', 0);
	@ini_set('log_errors', 0);
	@error_reporting(0);
	
	@putenv("TZ=Asia/Karachi");
	@date_default_timezone_set("Asia/Karachi");
	@ini_set("date.timezone", "Asia/Karachi");

	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);

	@ini_set('max_execution_time', 0);
	@set_time_limit(0);
        
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

        $Vendor      = @implode(",", IO::getArray('Vendor'));
        $Brand       = IO::intValue("Brand");
        $StartDate   = IO::strValue("FromDate");
        $EndDate     = IO::strValue("ToDate");
        
        $sUsersList     = getList("tbl_users", "id", "name", "status='A'");
        $sVendorsList   = getList("tbl_vendors", "id", "vendor");
        $sBrandsList    = getList("tbl_brands", "id", "brand");
        $sBrandsList    = getList("tbl_brands", "id", "brand");
        $sProgramsList  = getList("tbl_programs", "id", "program");
        $sStagesList    = getList("tbl_audit_stages", "code", "stage");
        $sSeasonsList   = getList("tbl_seasons", "id", "season");
        $sNatureList    = getList("tbl_tnc_defects_nature", "code_id", "nature", "report_id='54'");
                
        $sReportIds   = 54;
        //$sReportIds = getDbValue("GROUP_CONCAT(DISTINCT report_id SEPARATOR ',')", "tbl_qa_reports", "brand_id='$Brand'");
        
        $sJoinSQL = "  WHERE FIND_IN_SET(qa.report_id, '$sReportIds') AND qa.audit_result != '' ";
        
        if($StartDate != "" && $EndDate != "")
            $sJoinSQL .= " AND qa.audit_date BETWEEN '$StartDate' AND '$EndDate' ";
            
        if ($Vendor != "")
                $sJoinSQL .= " AND FIND_IN_SET(qa.vendor_id, '$Vendor') ";
        
        if ($Brand != "")
                $sJoinSQL .= " AND qa.brand_id = '$Brand' ";

     
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


        $objPhpExcel = $objReader->load("../templates/oql-tracking.xlsx");        
        $objPhpExcel->getProperties()->setCreator("Triple Tree")
                                                                 ->setLastModifiedBy($_SESSION["Name"])
                                                                 ->setTitle("OQL Tracking Report")
                                                                 ->setSubject("OQL Tracking Report")
                                                                 ->setDescription("OQL Tracking Report Analysis")
                                                                 ->setKeywords("")
                                                                 ->setCategory("Reports");

        $objPhpExcel->setActiveSheetIndex(0);
        $objPhpExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(12);
        $objPhpExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPhpExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(30);

        $sSQL = "SELECT dc.id, dc.code, dc.defect, dt.type, dt.id as _TypeId
                             FROM tbl_defect_codes dc, tbl_defect_types dt
                             WHERE dc.type_id = dt.id AND  dc.report_id IN ('$sReportIds')
                             ORDER BY FIELD(_TypeId, 6,33,131,111,132), dc.code";

        $objDb->query($sSQL);
        $iCount       = $objDb->getCount( );

        $iCol         = 24;
        $sOldeType    = "";
        $sDefectsList = array();
        
        for ($i = 0; $i < $iCount; $i ++)
        {
            $sType      = $objDb->getField($i, "type");
            $sCode      = $objDb->getField($i, "code");
            $sDefect    = $objDb->getField($i, "defect");
            $iDefect    = $objDb->getField($i, "id");
            
            $sDefectsList[] = $iDefect;
            
            if($sOldeType != $sType)
            {
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow($iCol, 1, strtoupper($sType));
                $objPhpExcel->getActiveSheet()->mergeCells(PHPExcel_Cell::stringFromColumnIndex($iCol).'1:'.PHPExcel_Cell::stringFromColumnIndex($iCol+4).'1');
            }
            
            $column = PHPExcel_Cell::stringFromColumnIndex($iCol);
            $cell = $column.'3';
            $range = $cell.':'.$cell;

            if($sNatureList[$iDefect] == 'Critical'){
              $objPhpExcel->getActiveSheet()->getStyle($range)->getFill()->applyFromArray(array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array(
                         'rgb' => 'E6B8B7'
                    )
                ));
            }
            else if($sNatureList[$iDefect] == 'Minor')    {
                $objPhpExcel->getActiveSheet()->getStyle($range)->getFill()->applyFromArray(array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array(
                         'rgb' => 'D8E4BC'
                    )
                ));
            }

            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow($iCol, 3, $sCode." - ".$sDefect." \n (".(($sNatureList[$iDefect] == 'Critical')?'CRIT':($sNatureList[$iDefect] == 'Major'?'MAJ':'MIN')).") ");            
            
            $iCol ++;    
            $sOldeType = $sType;
        }
        
        $sSQL = "SELECT qa.id, qa.audit_code, qa.audit_date, qa.colors, qa.ship_qty, qa.audit_stage, qa.audit_result, qa.total_gmts, qa.brand_id, qa.vendor_id, qa.user_id, 
                            (SELECT style from tbl_styles WHERE id=qa.style_id) as _Style,
                            (SELECT style_name from tbl_styles WHERE id=qa.style_id) as _StyleName,                            
                            (SELECT program_id from tbl_styles WHERE id=qa.style_id) as _ProgramId,
                            (SELECT season_id from tbl_styles WHERE id=qa.style_id) as _SeasonId,
                            (SELECT customer from tbl_po WHERE id=qa.po_id) as _Customer,
                            (SELECT order_no from tbl_po WHERE id=qa.po_id) as _OrderNo,
                            (SELECT etd_required from tbl_po_colors WHERE po_id=qa.po_id ORDER BY id LIMIT 1) as _ShipDate,
                            (SELECT SUM(quantity) from tbl_po_quantities WHERE po_id=qa.po_id) _OrderQty,
                            (SELECT COALESCE(SUM(defects), 0) from tbl_qa_report_defects WHERE audit_id=qa.id) _TotalDefects,
                            (SELECT COALESCE(SUM(defects), 0) from tbl_qa_report_defects WHERE audit_id=qa.id AND nature='2') _CriticalDefects,
                            (SELECT COALESCE(SUM(defects), 0) from tbl_qa_report_defects WHERE audit_id=qa.id AND nature='1') _MajorDefects,
                            (SELECT COALESCE(SUM(defects), 0) from tbl_qa_report_defects WHERE audit_id=qa.id AND nature='0') _MinorDefects
                            FROM tbl_qa_reports qa 
                           $sJoinSQL  
                            ORDER BY qa.id DESC";

        $objDb->query($sSQL);
        $iCount = $objDb->getCount( );
        
        
        $iRow = 4;
        $iPrevYear = "";
        $iGrandTotalGmts = 0;
        $iGrandTotalDefects = 0;
        $iGrandTotalInspects = 0;
        $iTotalsAuditsList = array();

        for ($i = 0; $i < $iCount; $i ++)
        {
            $iAuditId       = $objDb->getField($i, "id");
            $sAuditCode     = $objDb->getField($i, "audit_code");
            $sAuditDate     = $objDb->getField($i, "audit_date");
            $sCustomer      = $objDb->getField($i, "_Customer");
            $sShipDate      = $objDb->getField($i, "_ShipDate");
            $sColors        = $objDb->getField($i, "colors");
            $iShipQuantity  = $objDb->getField($i, "ship_qty");
            $iAuditStage    = $objDb->getField($i, "audit_stage");
            $sAuditResult   = $objDb->getField($i, "audit_result");
            $iTotalGmts     = $objDb->getField($i, "total_gmts");
            $iBrandId       = $objDb->getField($i, "brand_id");
            $iVendorId      = $objDb->getField($i, "vendor_id");
            $iUserId        = $objDb->getField($i, "user_id");
            $sStyle         = $objDb->getField($i, "_Style");
            $sStyleName     = $objDb->getField($i, "_StyleName");
            $iProgramId     = $objDb->getField($i, "_ProgramId");
            $iSeasonId      = $objDb->getField($i, "_SeasonId");
            $sPoNo          = $objDb->getField($i, "_OrderNo");
            $iOrderQty      = $objDb->getField($i, "_OrderQty");
            $iTotalDefects  = $objDb->getField($i, "_TotalDefects");
            $iCriticalDfcts = $objDb->getField($i, "_CriticalDefects");
            $iMajorDefects  = $objDb->getField($i, "_MajorDefects");
            $iMinorDefects  = $objDb->getField($i, "_MinorDefects");
      
            $sAuditStage = $sAuditStages[$iAuditStage];
            $sBrand      = $sBrandsList[$iBrandId];
            $sVendor     = $sVendorsList[$iVendorId];
            $sUser       = $sUsersList[$iUserId];
            $sProgram    = $sProgramsList[$iProgramId];
            $sSeason     = $sSeasonsList[$iSeasonId];
            $sStage      = $sStagesList[$iAuditStage];
            
            switch ($sAuditResult)
            {
                    case "P"  :  $sAuditResult = "Pass"; break;
                    case "F"  :  $sAuditResult = "Fail"; break;
                    case "H"  :  $sAuditResult = "Hold"; break;
                    case "R"  :  $sAuditResult = "Re-Inspection"; break;
                    default   :  $sAuditResult = "-"; break;
            }
            
            $iOql = formatNumber(($iCriticalDfcts+$iMajorDefects)/$iTotalGmts);
            
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, $sAuditDate);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $sShipDate);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, $sCustomer);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, $sProgram);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, $sBrand);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, $sSeason);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $iRow, $sStyle);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $iRow, $sStyleName);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $iRow, $sColors);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $iRow, $sPoNo);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $iRow, $iOrderQty);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $iRow, $iShipQuantity);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $iRow, $sStage);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $iRow, "");
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $iRow, "");
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $iRow, $sVendor);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $iRow, $sUser);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $iRow, $sAuditResult);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $iRow, $iTotalGmts);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $iRow, $iTotalDefects);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $iRow, $iCriticalDfcts);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $iRow, $iMajorDefects);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $iRow, $iMinorDefects);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(23, $iRow, $iOql);
            
            $iCol = 24;
            $iTotal = 0;
            $sDefects  = getList("tbl_qa_report_defects", "code_id", "SUM(defects)", "audit_id='$iAuditId' GROUP BY code_id");
            
            foreach($sDefectsList as $iKey => $iCodeId)
            {
                $iTotal += (int)($sDefects[$iCodeId]);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow($iCol++, $iRow, (int)($sDefects[$iCodeId]));            
            }
            
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow($iCol+1, $iRow, $iTotal);    
            
            $iRow ++;
        }

        /*for ($i = 0; $i < 193; $i ++)
		$objPhpExcel->getActiveSheet()->getColumnDimension(getExcelCol($i))->setAutoSize(true);*/
        
        $sExcelFile = "OQL Tracking Report.xlsx";


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