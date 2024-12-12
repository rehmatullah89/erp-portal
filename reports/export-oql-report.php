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
        $Customers   = @implode("','", IO::getArray('Customers'));
        $Styles   = @implode("','", IO::getArray('Styles'));
        $Year        = IO::intValue("Year");
        $Month       = IO::strValue("Month");
        
        $sJoinSQL = "";
        
        if ($Vendor != "")
                $sJoinSQL .= " AND qa.vendor_id IN ('$Vendor') ";
        
        if ($Customers != "")
                $sJoinSQL .= " AND p.customer IN ('".$Customers."') ";

        if ($Styles != "")
                $sJoinSQL .= " AND p.styles IN ('".$Styles."') ";

     
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

            

        
        if($Year > 0)
            $objPhpExcel = $objReader->load("../templates/oql-report.xlsx");
        else
            $objPhpExcel = $objReader->load("../templates/oql-full-report.xlsx");
        
        $objPhpExcel->getProperties()->setCreator("Triple Tree")
                                                                 ->setLastModifiedBy($_SESSION["Name"])
                                                                 ->setTitle("OQL Report")
                                                                 ->setSubject("OQL Report")
                                                                 ->setDescription("OQL Report Analysis")
                                                                 ->setKeywords("")
                                                                 ->setCategory("Reports");

        $objPhpExcel->setActiveSheetIndex(0);
        $objPhpExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(12);
        $objPhpExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPhpExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(30);

        
        $sReportIds = getDbValue("GROUP_CONCAT(DISTINCT report_id SEPARATOR ',')", "tbl_qa_reports", "brand_id='365'");
        
        if($Year > 0)
        {
            if($Month != "")
            {
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 2, date("F", mktime(0, 0, 0, (int)$Month, 10))."  {$Year}");
                $sJoinSQL .= " AND qa.audit_date LIKE '".$Year."-".$Month."%' ";
            }
            else
            {
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 2, "Year: {$Year}");
                $sJoinSQL .= " AND Year(qa.audit_date)='$Year' ";
            }
            
            $sSQL = "SELECT qa.audit_stage, YEAR(qa.audit_date) as _Year,                        
                                SUM(qrd.defects) AS _Defects,
                                SUM(qa.total_gmts) AS _TotalGmts,
                                COUNT(qa.id) AS _TotalInspections
                                 FROM tbl_qa_reports qa, tbl_qa_report_defects qrd, tbl_po p
                                 WHERE qrd.audit_id = qa.id AND  qa.audit_result != '' AND p.id = qa.po_id AND qa.report_id IN ($sReportIds) AND qa.audit_stage IN ('D','IL','F') $sJoinSQL
                                 GROUP BY qa.audit_stage, _Year
                                 ORDER BY _Year, FIELD(qa.audit_stage, 'D','IL','F')";

            $objDb->query($sSQL);
            $iCount       = $objDb->getCount( );
            $iRow         = 7;

            $iPrevYear = "";
            $iCol = 0;
            $iGrandTotalGmts = 0;
            $iGrandTotalDefects = 0;
            $iGrandTotalInspects = 0;
            $iTotalsAuditsList = array();

            for ($i = 0; $i < $iCount; $i ++)
            {
                $sAuditStage    = $objDb->getField($i, "audit_stage");
                $iYear          = $objDb->getField($i, "_Year");
                $iDefects       = $objDb->getField($i, "_Defects");
                $iTotalGmts     = $objDb->getField($i, "_TotalGmts");
                $iTotalInspects = $objDb->getField($i, "_TotalInspections");

                if($iPrevYear != $iYear && $iPrevYear != "")
                {
                    $iTotalsAuditsList['T'][$iPrevYear] = $iGrandTotalGmts;
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15+$iCol, $iRow+1, $iGrandTotalGmts);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15+$iCol, $iRow+2, $iGrandTotalInspects);

                    $iCol += 14;
                    $iGrandTotalGmts = 0;
                    $iGrandTotalDefects = 0;
                    $iGrandTotalInspects = 0;
                }

                if($sAuditStage == 'D')
                {
                    $iTotalsAuditsList['D'][$iYear] = $iTotalGmts;
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5+$iCol, $iRow+1, $iTotalGmts);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5+$iCol, $iRow+2, $iTotalInspects);
                }

                if($sAuditStage == 'IL')
                {
                    $iTotalsAuditsList['IL'][$iYear] = $iTotalGmts;
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8+$iCol, $iRow+1, $iTotalGmts);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8+$iCol, $iRow+2, $iTotalInspects);
                }

                if($sAuditStage == 'F')
                {
                    $iTotalsAuditsList['F'][$iYear] = $iTotalGmts;
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11+$iCol, $iRow+1, $iTotalGmts);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11+$iCol, $iRow+2, $iTotalInspects);
                }

                $iGrandTotalGmts += $iTotalGmts;
                $iGrandTotalDefects += $iDefects;
                $iGrandTotalInspects += $iTotalInspects;

                $iPrevYear = $iYear;
            }

            $iTotalsAuditsList['T'][$iYear] = $iGrandTotalGmts;
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15+$iCol, $iRow+1, $iGrandTotalGmts);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15+$iCol, $iRow+2, $iGrandTotalInspects);
            
            $sDefectIds = "";
            $sSQL = "SELECT qrd.code_id FROM tbl_qa_report_defects qrd, tbl_qa_reports qa, tbl_po p WHERE qa.id=qrd.audit_id AND p.id = qa.po_id AND qa.report_id IN ($sReportIds) $sJoinSQL";        
            $objDb->query($sSQL);

            $iCount = $objDb->getCount( );

            for ($i = 0; $i < $iCount; $i ++)
                $sDefectIds .= ($objDb->getField($i, "code_id").",");

            $sDefectIds = rtrim($sDefectIds, ",");

            $sSQL = "SELECT id, report_id, code, defect "
                    . "FROM tbl_defect_codes "
                    . "WHERE report_id IN ($sReportIds) AND id IN ($sDefectIds)"
                    . "ORDER BY report_id, code";

            $objDb->query($sSQL);

            $iCount       = $objDb->getCount( );
            $iRow         = 14;
            
            $iTotalDupro    = array();
            $iTotalInline   = array();
            $iTotalFinal    = array();
            $iTotalGrand    = array();
            
            for ($i = 0; $i < $iCount; $i ++)
            {
                    $iDefectCodeId  = $objDb->getField($i, "id");
                    $iReportId      = $objDb->getField($i, "report_id");
                    $sCode          = $objDb->getField($i, "code");
                    $sDefect        = $objDb->getField($i, "defect");

                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, $sCode);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $sDefect);

                    //////////****************Data for Selected Year Starts here ************//////////////
                    $sSQL = "SELECT qa.audit_stage,                        
                                SUM(IF(qrd.nature='2', qrd.defects, '0')) AS _Critical,
                                SUM(IF(qrd.nature='1', qrd.defects, '0')) AS _Major,
                                SUM(IF(qrd.nature='0', qrd.defects, '0')) AS _Minor
                                 FROM tbl_qa_reports qa, tbl_qa_report_defects qrd, tbl_po p
                                 WHERE qrd.audit_id = qa.id AND  qa.audit_result != '' AND p.id = qa.po_id AND qa.report_id = '$iReportId' AND qrd.code_id = '$iDefectCodeId' AND qa.audit_stage IN ('D','IL','F') $sJoinSQL
                                 GROUP BY qa.audit_stage, qrd.code_id
                                 ORDER BY qa.id DESC";

                    $objDb2->query($sSQL);

                    $iCount2 = $objDb2->getCount( );

                    $iTotalCr = 0;
                    $iTotalMj = 0;
                    $iTotalMi = 0;

                    for ($j = 0; $j < $iCount2; $j ++)
                    {
                        $sAuditStage        = $objDb2->getField($j, "audit_stage");
                        $iCritical          = $objDb2->getField($j, "_Critical");
                        $iMajor             = $objDb2->getField($j, "_Major");
                        $iMinor             = $objDb2->getField($j, "_Minor");

                        $iTotalCr += $iCritical;
                        $iTotalMj += $iMajor;
                        $iTotalMi += $iMinor;

                        if($sAuditStage == 'D')
                        {
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, $iCritical);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, $iMajor);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, $iMinor);

                            $iTotalDupro['cr'] += $iCritical;
                            $iTotalDupro['mj'] += $iMajor;
                            $iTotalDupro['mi'] += $iMinor;
                        }
                        else if($sAuditStage == 'IL')
                        {
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $iRow, $iCritical);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $iRow, $iMajor);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $iRow, $iMinor);

                            $iTotalInline['cr'] += $iCritical;
                            $iTotalInline['mj'] += $iMajor;
                            $iTotalInline['mi'] += $iMinor;
                        }
                        else if($sAuditStage == 'F')
                        {
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $iRow, $iCritical);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $iRow, $iMajor);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $iRow, $iMinor);

                            $iTotalFinal['cr'] += $iCritical;
                            $iTotalFinal['mj'] += $iMajor;
                            $iTotalFinal['mi'] += $iMinor;
                        }
                    }

                    $iTotalGrand['cr'] += $iTotalCr;
                    $iTotalGrand['mj'] += $iTotalMj;
                    $iTotalGrand['mi'] += $iTotalMi;

                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $iRow, $iTotalCr);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $iRow, $iTotalMj);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $iRow, $iTotalMi);
                    //////////****************Data for Year 2016 Ends ************//////////////

                    $iRow ++;
            }
            
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 12, $iTotalDupro['cr']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 12, $iTotalDupro['mj']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 12, $iTotalDupro['mi']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 12, $iTotalInline['cr']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 12, $iTotalInline['mj']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 12, $iTotalInline['mi']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 12, $iTotalFinal['cr']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 12, $iTotalFinal['mj']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 12, $iTotalFinal['mi']);        
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 12, $iTotalGrand['cr']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 12, $iTotalGrand['mj']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 12, $iTotalGrand['mi']);
            
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 7, ((($iTotalDupro['cr']+$iTotalDupro['mj']) / $iTotalsAuditsList['D'][$Year])));
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 7, ((($iTotalInline['cr']+$iTotalInline['mj']) / $iTotalsAuditsList['IL'][$Year])));
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 7, ((($iTotalFinal['cr']+$iTotalFinal['mj']) / $iTotalsAuditsList['F'][$Year])));
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 7, ((($iTotalGrand['cr']+$iTotalGrand['mj']) / $iTotalsAuditsList['T'][$Year])));
            
        }
        else
        {
            $sSQL = "SELECT qa.audit_stage, YEAR(qa.audit_date) as _Year,                        
                                SUM(qrd.defects) AS _Defects,
                                SUM(qa.total_gmts) AS _TotalGmts,
                                COUNT(qa.id) AS _TotalInspections
                                 FROM tbl_qa_reports qa, tbl_qa_report_defects qrd, tbl_po p
                                 WHERE qrd.audit_id = qa.id AND  qa.audit_result != '' AND p.id = qa.po_id AND qa.report_id IN ($sReportIds) AND qa.audit_stage IN ('D','IL','F') $sJoinSQL
                                 GROUP BY qa.audit_stage, _Year
                                 ORDER BY _Year, FIELD(qa.audit_stage, 'D','IL','F')";
            $objDb->query($sSQL);
            $iCount       = $objDb->getCount( );
            $iRow         = 7;

            $iPrevYear = "";
            $iCol = 0;
            $iGrandTotalGmts = 0;
            $iGrandTotalDefects = 0;
            $iGrandTotalInspects = 0;
            $iTotalsAuditsList = array();

            for ($i = 0; $i < $iCount; $i ++)
            {
                $sAuditStage    = $objDb->getField($i, "audit_stage");
                $iYear          = $objDb->getField($i, "_Year");
                $iDefects       = $objDb->getField($i, "_Defects");
                $iTotalGmts     = $objDb->getField($i, "_TotalGmts");
                $iTotalInspects = $objDb->getField($i, "_TotalInspections");

                if($iPrevYear != $iYear && $iPrevYear != "")
                {
                    $iTotalsAuditsList['T'][$iPrevYear] = $iGrandTotalGmts;
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15+$iCol, $iRow+1, $iGrandTotalGmts);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15+$iCol, $iRow+2, $iGrandTotalInspects);

                    $iCol += 14;
                    $iGrandTotalGmts = 0;
                    $iGrandTotalDefects = 0;
                    $iGrandTotalInspects = 0;
                }

                if($sAuditStage == 'D')
                {
                    $iTotalsAuditsList['D'][$iYear] = $iTotalGmts;
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5+$iCol, $iRow+1, $iTotalGmts);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5+$iCol, $iRow+2, $iTotalInspects);
                }

                if($sAuditStage == 'IL')
                {
                    $iTotalsAuditsList['IL'][$iYear] = $iTotalGmts;
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8+$iCol, $iRow+1, $iTotalGmts);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8+$iCol, $iRow+2, $iTotalInspects);
                }

                if($sAuditStage == 'F')
                {
                    $iTotalsAuditsList['F'][$iYear] = $iTotalGmts;
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11+$iCol, $iRow+1, $iTotalGmts);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11+$iCol, $iRow+2, $iTotalInspects);
                }

                $iGrandTotalGmts += $iTotalGmts;
                $iGrandTotalDefects += $iDefects;
                $iGrandTotalInspects += $iTotalInspects;

                $iPrevYear = $iYear;
            }

            $iTotalsAuditsList['T'][$iYear] = $iGrandTotalGmts;
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15+$iCol, $iRow+1, $iGrandTotalGmts);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15+$iCol, $iRow+2, $iGrandTotalInspects);

            $sDefectIds = "";
            $sSQL = "SELECT qrd.code_id FROM tbl_qa_report_defects qrd, tbl_qa_reports qa, tbl_po p WHERE qa.id=qrd.audit_id AND p.id = qa.po_id AND qa.report_id IN ($sReportIds) $sJoinSQL";        
            $objDb->query($sSQL);

            $iCount = $objDb->getCount( );

            for ($i = 0; $i < $iCount; $i ++)
                $sDefectIds .= ($objDb->getField($i, "code_id").",");

            $sDefectIds = rtrim($sDefectIds, ",");

            $sSQL = "SELECT id, report_id, code, defect "
                    . "FROM tbl_defect_codes "
                    . "WHERE report_id IN ($sReportIds) AND id IN ($sDefectIds)"
                    . "ORDER BY report_id, code";

            $objDb->query($sSQL);

            $iCount       = $objDb->getCount( );
            $iRow         = 14;

            $iTotalDupro2016 = array();
            $iTotalDupro2017 = array();
            $iTotalDupro2018 = array();

            $iTotalInline2016   = array();
            $iTotalInline2017   = array();
            $iTotalInline2018   = array();

            $iTotalFinal2016    = array();
            $iTotalFinal2016    = array();
            $iTotalFinal2016    = array();

            $iTotalGrand2016    = array();
            $iTotalGrand2017    = array();
            $iTotalGrand2018    = array();

            for ($i = 0; $i < $iCount; $i ++)
            {
                    $iDefectCodeId  = $objDb->getField($i, "id");
                    $iReportId      = $objDb->getField($i, "report_id");
                    $sCode          = $objDb->getField($i, "code");
                    $sDefect        = $objDb->getField($i, "defect");

                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, $sCode);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $sDefect);

                    //////////****************Data for Year 2016 Start ************//////////////
                    $sSQL = "SELECT qa.audit_stage,                        
                                SUM(IF(qrd.nature='2', qrd.defects, '0')) AS _Critical,
                                SUM(IF(qrd.nature='1', qrd.defects, '0')) AS _Major,
                                SUM(IF(qrd.nature='0', qrd.defects, '0')) AS _Minor
                                 FROM tbl_qa_reports qa, tbl_qa_report_defects qrd, tbl_po p
                                 WHERE qrd.audit_id = qa.id AND  qa.audit_result != '' AND p.id = qa.po_id AND qa.report_id = '$iReportId' AND qrd.code_id = '$iDefectCodeId' AND qa.audit_stage IN ('D','IL','F') AND (qa.audit_date BETWEEN '2016-01-01' AND '2016-12-31') $sJoinSQL
                                 GROUP BY qa.audit_stage, qrd.code_id
                                 ORDER BY qa.id DESC";

                    $objDb2->query($sSQL);

                    $iCount2 = $objDb2->getCount( );

                    $iTotalCr = 0;
                    $iTotalMj = 0;
                    $iTotalMi = 0;

                    for ($j = 0; $j < $iCount2; $j ++)
                    {
                        $sAuditStage        = $objDb2->getField($j, "audit_stage");
                        $iCritical          = $objDb2->getField($j, "_Critical");
                        $iMajor             = $objDb2->getField($j, "_Major");
                        $iMinor             = $objDb2->getField($j, "_Minor");

                        $iTotalCr += $iCritical;
                        $iTotalMj += $iMajor;
                        $iTotalMi += $iMinor;

                        if($sAuditStage == 'D')
                        {
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, $iCritical);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, $iMajor);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, $iMinor);

                            $iTotalDupro2016['cr'] += $iCritical;
                            $iTotalDupro2016['mj'] += $iMajor;
                            $iTotalDupro2016['mi'] += $iMinor;
                        }
                        else if($sAuditStage == 'IL')
                        {
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $iRow, $iCritical);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $iRow, $iMajor);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $iRow, $iMinor);

                            $iTotalInline2016['cr'] += $iCritical;
                            $iTotalInline2016['mj'] += $iMajor;
                            $iTotalInline2016['mi'] += $iMinor;
                        }
                        else if($sAuditStage == 'F')
                        {
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $iRow, $iCritical);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $iRow, $iMajor);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $iRow, $iMinor);

                            $iTotalFinal2016['cr'] += $iCritical;
                            $iTotalFinal2016['mj'] += $iMajor;
                            $iTotalFinal2016['mi'] += $iMinor;
                        }
                    }

                    $iTotalGrand2016['cr'] += $iTotalCr;
                    $iTotalGrand2016['mj'] += $iTotalMj;
                    $iTotalGrand2016['mi'] += $iTotalMi;

                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $iRow, $iTotalCr);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $iRow, $iTotalMj);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $iRow, $iTotalMi);
                    //////////****************Data for Year 2016 Ends ************//////////////

                    //////////****************Data for Year 2017 Start ************//////////////
                    $sSQL = "SELECT qa.audit_stage,                        
                                SUM(IF(qrd.nature='2', qrd.defects, '0')) AS _Critical,
                                SUM(IF(qrd.nature='1', qrd.defects, '0')) AS _Major,
                                SUM(IF(qrd.nature='0', qrd.defects, '0')) AS _Minor
                                 FROM tbl_qa_reports qa, tbl_qa_report_defects qrd, tbl_po p
                                 WHERE qrd.audit_id = qa.id AND  qa.audit_result!='' AND p.id = qa.po_id AND qa.report_id = '$iReportId' AND qrd.code_id = '$iDefectCodeId' AND qa.audit_stage IN ('D','IL','F') AND (qa.audit_date BETWEEN '2017-01-01' AND '2017-12-31') $sJoinSQL
                                 GROUP BY qa.audit_stage, qrd.code_id
                                 ORDER BY qa.id DESC";

                    $objDb2->query($sSQL);

                    $iCount2 = $objDb2->getCount( );

                    $iTotalCr = 0;
                    $iTotalMj = 0;
                    $iTotalMi = 0;

                    for ($j = 0; $j < $iCount2; $j ++)
                    {
                        $sAuditStage        = $objDb2->getField($j, "audit_stage");
                        $iCritical          = $objDb2->getField($j, "_Critical");
                        $iMajor             = $objDb2->getField($j, "_Major");
                        $iMinor             = $objDb2->getField($j, "_Minor");

                        $iTotalCr += $iCritical;
                        $iTotalMj += $iMajor;
                        $iTotalMi += $iMinor;

                        if($sAuditStage == 'D')
                        {
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $iRow, $iCritical);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $iRow, $iMajor);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $iRow, $iMinor);

                            $iTotalDupro2017['cr'] += $iCritical;
                            $iTotalDupro2017['mj'] += $iMajor;
                            $iTotalDupro2017['mi'] += $iMinor;
                        }
                        else if($sAuditStage == 'IL')
                        {
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $iRow, $iCritical);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $iRow, $iMajor);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $iRow, $iMinor);

                            $iTotalInline2017['cr'] += $iCritical;
                            $iTotalInline2017['mj'] += $iMajor;
                            $iTotalInline2017['mi'] += $iMinor;
                        }
                        else if($sAuditStage == 'F')
                        {
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(23, $iRow, $iCritical);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(24, $iRow, $iMajor);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(25, $iRow, $iMinor);

                            $iTotalFinal2017['cr'] += $iCritical;
                            $iTotalFinal2017['mj'] += $iMajor;
                            $iTotalFinal2017['mi'] += $iMinor;
                        }
                    }

                    $iTotalGrand2017['cr'] += $iTotalCr;
                    $iTotalGrand2017['mj'] += $iTotalMj;
                    $iTotalGrand2017['mi'] += $iTotalMi;

                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(27, $iRow, $iTotalCr);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(28, $iRow, $iTotalMj);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(29, $iRow, $iTotalMi);
                    //////////****************Data for Year 2017 Ends ************//////////////

                    //////////****************Data for Year 2018 Start ************//////////////
                    $sSQL = "SELECT qa.audit_stage,                        
                                SUM(IF(qrd.nature='2', qrd.defects, '0')) AS _Critical,
                                SUM(IF(qrd.nature='1', qrd.defects, '0')) AS _Major,
                                SUM(IF(qrd.nature='0', qrd.defects, '0')) AS _Minor
                                 FROM tbl_qa_reports qa, tbl_qa_report_defects qrd, tbl_po p
                                 WHERE qrd.audit_id = qa.id AND  qa.audit_result!='' AND p.id = qa.po_id AND qa.report_id = '$iReportId' AND qrd.code_id = '$iDefectCodeId' AND qa.audit_stage IN ('D','IL','F') AND (qa.audit_date BETWEEN '2018-01-01' AND '2018-12-31') $sJoinSQL
                                 GROUP BY qa.audit_stage, qrd.code_id
                                 ORDER BY qa.id DESC";

                    $objDb2->query($sSQL);

                    $iCount2 = $objDb2->getCount( );

                    $iTotalCr = 0;
                    $iTotalMj = 0;
                    $iTotalMi = 0;

                    for ($j = 0; $j < $iCount2; $j ++)
                    {
                        $sAuditStage        = $objDb2->getField($j, "audit_stage");
                        $iCritical          = $objDb2->getField($j, "_Critical");
                        $iMajor             = $objDb2->getField($j, "_Major");
                        $iMinor             = $objDb2->getField($j, "_Minor");

                        $iTotalCr += $iCritical;
                        $iTotalMj += $iMajor;
                        $iTotalMi += $iMinor;

                        if($sAuditStage == 'D')
                        {
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(31, $iRow, $iCritical);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(32, $iRow, $iMajor);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(33, $iRow, $iMinor);

                            $iTotalDupro2018['cr'] += $iCritical;
                            $iTotalDupro2018['mj'] += $iMajor;
                            $iTotalDupro2018['mi'] += $iMinor;
                        }
                        else if($sAuditStage == 'IL')
                        {
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(34, $iRow, $iCritical);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(35, $iRow, $iMajor);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(36, $iRow, $iMinor);

                            $iTotalInline2018['cr'] += $iCritical;
                            $iTotalInline2018['mj'] += $iMajor;
                            $iTotalInline2018['mi'] += $iMinor;
                        }
                        else if($sAuditStage == 'F')
                        {
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(37, $iRow, $iCritical);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(38, $iRow, $iMajor);
                            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(39, $iRow, $iMinor);

                            $iTotalFinal2018['cr'] += $iCritical;
                            $iTotalFinal2018['mj'] += $iMajor;
                            $iTotalFinal2018['mi'] += $iMinor;
                        }
                    }

                    $iTotalGrand2018['cr'] += $iTotalCr;
                    $iTotalGrand2018['mj'] += $iTotalMj;
                    $iTotalGrand2018['mi'] += $iTotalMi;

                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(41, $iRow, $iTotalCr);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(42, $iRow, $iTotalMj);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(43, $iRow, $iTotalMi);
                    //////////****************Data for Year 2018 Ends ************//////////////

                    $iRow ++;
            }

            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 12, $iTotalDupro2016['cr']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 12, $iTotalDupro2016['mj']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 12, $iTotalDupro2016['mi']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 12, $iTotalInline2016['cr']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 12, $iTotalInline2016['mj']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 12, $iTotalInline2016['mi']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 12, $iTotalFinal2016['cr']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 12, $iTotalFinal2016['mj']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 12, $iTotalFinal2016['mi']);        
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 12, $iTotalGrand2016['cr']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 12, $iTotalGrand2016['mj']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 12, $iTotalGrand2016['mi']);

            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(17, 12, $iTotalDupro2017['cr']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(18, 12, $iTotalDupro2017['mj']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(19, 12, $iTotalDupro2017['mi']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(20, 12, $iTotalInline2017['cr']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(21, 12, $iTotalInline2017['mj']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(22, 12, $iTotalInline2017['mi']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(23, 12, $iTotalFinal2017['cr']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(24, 12, $iTotalFinal2017['mj']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(25, 12, $iTotalFinal2017['mi']);        
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(27, 12, $iTotalGrand2017['cr']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(28, 12, $iTotalGrand2017['mj']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(29, 12, $iTotalGrand2017['mi']);

            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(31, 12, $iTotalDupro2018['cr']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(32, 12, $iTotalDupro2018['mj']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(33, 12, $iTotalDupro2018['mi']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(34, 12, $iTotalInline2018['cr']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(35, 12, $iTotalInline2018['mj']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(36, 12, $iTotalInline2018['mi']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(37, 12, $iTotalFinal2018['cr']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(38, 12, $iTotalFinal2018['mj']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(39, 12, $iTotalFinal2018['mi']);        
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(41, 12, $iTotalGrand2018['cr']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(42, 12, $iTotalGrand2018['mj']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(43, 12, $iTotalGrand2018['mi']);


            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 7, ((($iTotalDupro2016['cr']+$iTotalDupro2016['mj']) / $iTotalsAuditsList['D'][2016])));
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 7, ((($iTotalInline2016['cr']+$iTotalInline2016['mj']) / $iTotalsAuditsList['IL'][2016])));
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 7, ((($iTotalFinal2016['cr']+$iTotalFinal2016['mj']) / $iTotalsAuditsList['F'][2016])));
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 7, ((($iTotalGrand2016['cr']+$iTotalGrand2016['mj']) / $iTotalsAuditsList['T'][2016])));

            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(19, 7, ((($iTotalDupro2017['cr']+$iTotalDupro2017['mj']) / $iTotalsAuditsList['D'][2017])));
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(22, 7, ((($iTotalInline2017['cr']+$iTotalInline2017['mj']) / $iTotalsAuditsList['IL'][2017])));
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(25, 7, ((($iTotalFinal2017['cr']+$iTotalFinal2017['mj']) / $iTotalsAuditsList['F'][2017])));
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(29, 7, ((($iTotalGrand2017['cr']+$iTotalGrand2017['mj']) / $iTotalsAuditsList['T'][2017])));

            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(33, 7, ((($iTotalDupro2018['cr']+$iTotalDupro2018['mj']) / $iTotalsAuditsList['D'][2018])));
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(36, 7, ((($iTotalInline2018['cr']+$iTotalInline2018['mj']) / $iTotalsAuditsList['IL'][2018])));
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(39, 7, ((($iTotalFinal2018['cr']+$iTotalFinal2018['mj']) / $iTotalsAuditsList['F'][2018])));
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(43, 7, ((($iTotalGrand2018['cr']+$iTotalGrand2018['mj']) / $iTotalsAuditsList['T'][2018])));


            $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
            $objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B &R ');

            $objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
            $objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            $objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
            $objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.3);
            $objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
            $objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

            $objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        }


        $sExcelFile = "OQL Report.xlsx";


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