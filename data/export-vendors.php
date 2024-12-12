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
        

	$Vendor         = IO::strValue("Vendor");
	$Category       = IO::intValue("Category");
	$Country        = IO::strValue("Country");
        $iVendors       = getDbValue("vendors", "tbl_brands", "id='526'");
        

	$sConditions    = " WHERE parent_id != '0' AND id IN ($iVendors) ";
	
	if ($Vendor != "")
		$sConditions .= " AND vendor LIKE '%$Vendor%' ";
	
	if ($Category != "")
		$sConditions .= " AND category_id='$Category' ";

	if ($Country > 0)
		$sConditions .= " AND country_id='$Country' ";
	
        
        $sCountriesList = getList("tbl_countries", "id", "country");
        $sParentsList   = getList("tbl_vendors", "id", "vendor", "parent_id='0'");
        
        
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

									   
									   
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setTitle("Vendors Report");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("Vendors Report");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");


	// Create a first sheet
	$iRow = 1;

	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "Serial #");
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "Vendor");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "Account ID");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "Account Name");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, "Country");
	
        $objPHPExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , "A{$iRow}:E{$iRow}");
        
	$iRow ++;

	$sSQL = "SELECT parent_id, vendor, code, country_id FROM tbl_vendors $sConditions ORDER BY vendor";
        $objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iParent        = $objDb->getField($i, 'parent_id');
		$sVendor        = $objDb->getField($i, 'vendor');
		$sCode          = $objDb->getField($i, "code");
                $iCountry       = $objDb->getField($i, "country_id");
            		
		$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", ($i + 1));
		$objPHPExcel->getActiveSheet()->setCellValue("B{$iRow}", $sParentsList[$iParent]);
		$objPHPExcel->getActiveSheet()->setCellValue("C{$iRow}", $sCode);
		$objPHPExcel->getActiveSheet()->setCellValue("D{$iRow}", $sVendor);
		$objPHPExcel->getActiveSheet()->setCellValue("E{$iRow}", $sCountriesList[$iCountry]);
		
		
		$objPHPExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle , "A{$iRow}:E{$iRow}");

		$iRow ++;                
	}


	// Set column widths
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	
	

	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0);

	$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

	$objPHPExcel->getActiveSheet()->setTitle("Users Report");


	$sExcelFile = "Vendors.xlsx";

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save("php://output");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>