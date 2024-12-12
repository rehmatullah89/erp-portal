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

        function num2alpha($n)
        {
            for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
                $r = chr($n%26 + 0x41) . $r;
            return $r;
        }

        $objDbGlobal = new Database( );
        $objDb       = new Database( );
        $objDb2      = new Database( );
        $objDb3      = new Database( );

        $Vendor    = @implode(",", IO::getArray('Vendor'));

        $StartDate = IO::strValue('FromDate');
        $EndDate   = IO::strValue('ToDate');

        $StartDate = explode("/", $StartDate);
        $EndDate   = explode("/", $EndDate);

        $FromDate = date(@$StartDate[1].'-'.@$StartDate[0].'-01');
        $ToDate   = date(@$EndDate[1].'-'.@$EndDate[0].'-t');

        $ts1 = strtotime($FromDate);
        $ts2 = strtotime($ToDate);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $TotalMonths = ((($year2 - $year1) * 12) + ($month2 - $month1)) + 1;

        $sFilters    = "";
        $sFilters2   = "";


        if ($Vendor != "")
        {
                if ($sFilters != "")
                        $sFilters .= ", ";

                $sFilters .= ("Vendors: ".getDbValue("GROUP_CONCAT(vendor SEPARATOR ', ')", "tbl_vendors", "FIND_IN_SET(id, '$Vendor')"));		
        }

        if ($FromDate != "" && $ToDate != "")
        {
                if ($sFilters != "")
                        $sFilters .= ", ";

                $sFilters .= ("Date Range: ". formatDate($FromDate)." / ".formatDate($ToDate));		
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


        $sBorderStyle = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                                                  'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                         'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                         'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                         'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'ffffff')));

        $sBorderBack = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                                              'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                     'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                     'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                                                     'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'D3D3D3')));

        $sVendors = implode(",", getList("tbl_vendors v", "v.id", "v.id", "FIND_IN_SET(v.id, '{$_SESSION['Vendors']}') AND v.mgf='N' AND v.levis='N'"));

        $sConditions .= " AND vendor_id IN ($sVendors) ";
                
        if($Vendor != "")
            $sConditions .= " AND vendor_id IN ($Vendor) ";

        $objPhpExcel = $objReader->load("../templates/jcrew-placement-report.xlsx");
        $objPhpExcel->getProperties()->setCreator("Triple Tree")
                                                                 ->setLastModifiedBy($_SESSION["Name"])
                                                                 ->setTitle("J.Crew Placement Report")
                                                                 ->setSubject("J.Crew Placement Report")
                                                                 ->setDescription("J.Crew Placement Report")
                                                                 ->setKeywords("")
                                                                 ->setCategory("Reports");

        $objPhpExcel->setActiveSheetIndex(0);
        $objPhpExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPhpExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPhpExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(30);

        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "({$sFilters})");

        $Col  = 2;
        $iRow = 5;
        $List = array(0=>"Quantity", 1=>"No. of Styles", 2=>"No. of POs");

        for($i=0; $i< $TotalMonths; $i++)
        {
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow($Col, $iRow, date('F Y', strtotime("+$i months", strtotime($FromDate))));
            $objPhpExcel->getActiveSheet()->mergeCells(num2alpha($Col).$iRow.':'.num2alpha($Col + 2).$iRow);

            for($j=0; $j<3; $j++)
            {
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow($Col, $iRow+1, $List[$j]);

                $Col ++;
            }
            $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderBack, (num2alpha($Col-3).($iRow+1).":".num2alpha($Col-1).($iRow+1)));                
        }

        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow($Col, $iRow, "Total");
        $objPhpExcel->getActiveSheet()->mergeCells(num2alpha($Col).$iRow.':'.num2alpha($Col + 2).$iRow);

        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow($Col++, $iRow+1, "Total Quantity");
        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow($Col++, $iRow+1, "Total Styles");
        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow($Col++, $iRow+1, "Total POs");
        $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderBack, (num2alpha($Col-3).($iRow+1).":".num2alpha($Col-1).($iRow+1))); 

        $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, ("A{$iRow}:".num2alpha($Col)."{$iRow}"));  

        $sSQL = "SELECT DISTINCT vendor_id,
                        (SELECT vendor from tbl_vendors WHERE id=tbl_po.vendor_id) as _Vendor,
                        (SELECT parent from tbl_factories WHERE FIND_IN_SET(tbl_po.vendor_id, vendors) LIMIT 1) as _Parent
                    FROM tbl_po
                    WHERE id>0 $sConditions
                    ORDER BY _Parent, _Vendor";

        $objDb->query($sSQL);

        $iCount = $objDb->getCount();
        $iRow   = 7;

        $GrandTotalQty = 0;
        $GrandTotalPos = 0;
        $GrandTotalStyles = 0;
        $sTotalsList = array();

        for ($i = 0; $i < $iCount; $i ++)
        {
                $iVendor   = $objDb->getField($i, "vendor_id");
                $sParent   = $objDb->getField($i, "_Parent");
                $sVendor   = $objDb->getField($i, "_Vendor");

                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, $sParent);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $sVendor);
                
                $Block = 0;

                $TotalQty = 0;
                $TotalPos = 0;
                $TotalStyles = 0;

                for($j=0; $j< $TotalMonths; $j++)
                {
                    $StartDate = date('Y-m-d', strtotime("+$j months", strtotime($FromDate)));                    
                    $EndDate   =  date('Y-m-t', strtotime($StartDate));

                    $sDateConditions = " AND (DATE_FORMAT(pc.etd_required, '%Y-%m-%d') BETWEEN '$StartDate' AND '$EndDate') ";

                    $sSQL = "SELECT COUNT(1) as _TotalPos, COUNT(DISTINCT pc.style_id) as TotalStyles,
                            SUM(pc.order_qty) AS _Quantity
                    FROM tbl_po p, tbl_po_colors pc
                    WHERE p.id=pc.po_id AND vendor_id='$iVendor' $sDateConditions
                    GROUP BY vendor_id";

                    $objDb2->query($sSQL);

                    $iTotalPos    = (int)$objDb2->getField(0, "_TotalPos");
                    $iTotalStyles = (int)$objDb2->getField(0, "TotalStyles");
                    $iQuantity    = (int)$objDb2->getField(0, "_Quantity");

                    $sTotalsList[$j]['TotalPos']      += $iTotalPos;
                    $sTotalsList[$j]['TotalStyles']   += $iTotalStyles;
                    $sTotalsList[$j]['Quantity']      += $iQuantity;

                    $TotalQty += $iQuantity;
                    $TotalPos += $iTotalPos;
                    $TotalStyles  += $iTotalStyles;

                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2+$Block, $iRow, $iQuantity);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3+$Block, $iRow, $iTotalStyles);
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4+$Block, $iRow, $iTotalPos);       

                    $Block += 3;
                }

                $GrandTotalQty += $TotalQty;
                $GrandTotalPos += $TotalPos;
                $GrandTotalStyles += $TotalStyles;

                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2+$Block, $iRow, $TotalQty);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3+$Block, $iRow, $TotalStyles);
                $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4+$Block, $iRow, $TotalPos);       

                $iRow ++;                                   
        }

        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, "Total");
        $iInc = 0;

        foreach($sTotalsList as $sTotals)
        {
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow($iInc+2, $iRow, $sTotals['Quantity']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow($iInc+3, $iRow, $sTotals['TotalStyles']);
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow($iInc+4, $iRow, $sTotals['TotalPos']);

            $iInc +=3;
        }

        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2+$Block, $iRow, $GrandTotalQty);
        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3+$Block, $iRow, $GrandTotalStyles);
        $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(4+$Block, $iRow, $GrandTotalPos);  

        $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderBack, ("A{$iRow}:".num2alpha($Col)."{$iRow}"));  

        for($i=1; $i<$Col; $i++)
        {
            if($i != 3)
            {
                $Column = num2alpha($i);
                $objPhpExcel->getActiveSheet()->getColumnDimension("$Column")->setAutoSize(true);
            }
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


        $sExcelFile = "J.Crew Placements Report ".date('Y-m-d')." .xlsx";

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