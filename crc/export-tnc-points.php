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


        //ini_set('display_errors', '1');
	//if ($Referer == "")
	//	$Referer = $_SERVER['HTTP_REFERER'];
        
        $Vendor   = IO::intValue("VendorId");
        $Section  = IO::intValue("SectionId");
        $Parent   = IO::intValue("ParentId");
        $iAuditId  = IO::intValue("AuditId");
        
        $_Section      = "";
        $AuditVendors  = getList("tbl_tnc_audits", "id", "vendor_id");
        $sSectionsList = getList("tbl_tnc_sections", "id", "section");
        $sVendorsList  = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND id IN (".implode(',', $AuditVendors).") AND parent_id='0' AND sourcing='Y'");


        if($Parent == 0){
            $_Section = $sSectionsList[$Section];
            $sConditions .= " AND ts.parent_id='$Section' ";
        }else{
            $_Section     = $sSectionsList[$Parent];
            $sConditions .= " AND ts.id='$Section' ";
        }
            
	if ($Vendor > 0)
		$sConditions .= " AND ta.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";


        $objPhpExcel = new PHPExcel( );

	$objPhpExcel->getProperties()->setCreator("Matrix Sourcing")
								 ->setLastModifiedBy("Matrix Sourcing")
								 ->setTitle("TNC Points Report")
								 ->setSubject("TNC Points Analysis")
								 ->setDescription("TNC Points Report")
								 ->setKeywords("")
								 ->setCategory("TNC Audit Points");

	$objPhpExcel->setActiveSheetIndex(0);


	$objPhpExcel->getActiveSheet()->setCellValue("A1", $_Section);
	$objPhpExcel->getActiveSheet()->mergeCells("A1:O1");
	$objPhpExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(28);
	$objPhpExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);

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
        
        $sBorderStyleHighlight = array('font'       => array('bold' => true, 'size' => 11),
					 'fill'       => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFD579')),
					 'alignment'  => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
					 'borders'    => array('top'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
										   'right'  => array('style' => PHPExcel_Style_Border::BORDER_THIN),
										   'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
										   'left'   => array('style' => PHPExcel_Style_Border::BORDER_THIN)));

	$sBlockStyle = array('font'       => array('bold' => true, 'size' => 11),
                         'fill'       => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'DDDDDD')),
	                     'alignment'  => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
						 'borders'    => array('top'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											   'right'  => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											   'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
											   'left'   => array('style' => PHPExcel_Style_Border::BORDER_THIN)));


	$iRow = 2;

	$objPhpExcel->getActiveSheet()->setCellValue("A{$iRow}", "No.");
	$objPhpExcel->getActiveSheet()->setCellValue("B{$iRow}", "Point");
	$objPhpExcel->getActiveSheet()->setCellValue("C{$iRow}", "Score");
	$objPhpExcel->getActiveSheet()->setCellValue("D{$iRow}", "Remarks");

        $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , ("A{$iRow}:D{$iRow}"));


	$sSQL = "SELECT (Select category from tbl_tnc_categories where id=tp.category_id) as _Category,
                tp.point, tad.score, tad.remarks  
             FROM tbl_tnc_audits ta, tbl_tnc_audit_details tad, tbl_tnc_points tp, tbl_tnc_sections ts
             WHERE ta.id = tad.audit_id AND tad.point_id = tp.id AND ts.id=tp.section_id AND ta.id='$iAuditId' $sConditions Order By _Category";
        $objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
        $LastCategory = "";
	$iRow = 3;
         
	for ($i = 0; $i < $iCount; $i ++, $iRow ++)
	{
		$sCategory  = $objDb->getField($i, "_Category");
		$sPoint     = $objDb->getField($i, "point");
		$iScore     = $objDb->getField($i, "score");
		$sRemarks   = $objDb->getField($i, "remarks");
		
                if($LastCategory != $sCategory){
                    
                    $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, $sCategory);
                    $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyleHighlight, ("A{$iRow}:D{$iRow}"));
                    $iRow ++;
                }
		

		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $iRow, $i+1);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $iRow, $sPoint);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, $iScore);
		$objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, $sRemarks);

                $objPhpExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle, ("A{$iRow}:D{$iRow}"));		
                $LastCategory = $sCategory;
	}

        $iRow = $iRow +5;
        $sAuditDate = getDbValue("audit_date", "tbl_tnc_audits", "id='$iAuditId'");


	@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

	@mkdir(($sBaseDir.TNC_PICS_DIR.$sYear), 0777);
	@mkdir(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth), 0777);
	@mkdir(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);

	$sTncDir = (TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
        
        $sSQL = "SELECT id, title, point_id, picture FROM tbl_tnc_audit_pictures WHERE audit_id='$iAuditId'";
	$objDb->query($sSQL);

        $iCount = $objDb->getCount( );
        for ($i = 0; $i < $iCount; $i++)
        {
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setWorksheet($objPhpExcel->getActiveSheet());

            if ($i < $iCount)
            {
                $iImageId = $objDb->getField($i, "id");
                $iPointId = $objDb->getField($i, "point_id");
                $sTitle   = $objDb->getField($i, "title");
                $sPicture = $objDb->getField($i, "picture");

                if(@file_exists($sBaseDir.$sTncDir.$sPicture))
                {
                    $objDrawing->setName($sTitle);
                    $objDrawing->setDescription($sTitle);
                    $objDrawing->setPath($sBaseDir.$sTncDir.$sPicture);
                    $objDrawing->setCoordinates(getExcelCol(65).$iRow);
                    $objDrawing->setWidthAndHeight(100,100); // image width x height
                    $iRow+=6;
                }
            }
        }
        

	$objPhpExcel->getActiveSheet()->getColumnDimension("A")->setWidth(10);
	$objPhpExcel->getActiveSheet()->getColumnDimension("B")->setWidth(70);
	$objPhpExcel->getActiveSheet()->getColumnDimension("C")->setWidth(10);
	$objPhpExcel->getActiveSheet()->getColumnDimension("D")->setWidth(70);


	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('');
	$objPhpExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&L&B TNC Audit Points Report &R Generated on ".date("d-M-Y"));

	$objPhpExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	$objPhpExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	$objPhpExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
	$objPhpExcel->getActiveSheet()->getPageMargins()->setBottom(0);

	$objPhpExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

	$objPhpExcel->getActiveSheet()->setTitle("TNC Audit Points");


	$sExcelFile = "TNC Audit Points.xlsx";

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