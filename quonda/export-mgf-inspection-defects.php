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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
        $objDb3      = new Database( );
        
        $FromDate   = date("Y-m-d", strtotime("last week"));
	$ToDate     = date("Y-m-d");
        
	$sAuditorsList      = getList("tbl_users", "id", "name");
	        
	$sExcelFile = ($sBaseDir.TEMP_DIR."INSPECTION_DEFECT.xlsx");

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');
        

	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';
        require_once 'phpseclib0.3.0/Net/SFTP.php';
        
        $local_directory = $sBaseDir.TEMP_DIR;
        $remote_directory = '/TEST/Outgoing/Inspection/';

        /* FTP Connection */
        $sftp = new Net_SFTP('125.209.75.188');
        if (!$sftp->login('mgfsourcing', 'mgf2016#')) 
        {
            exit('Login Failed');
        } 

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
	$objPHPExcel->getProperties()->setTitle("INSPECTION_DEFECT");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("INSPECTION_DEFECT");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");


        


                                
	// Create a first sheet
	$iRow = 1;

        $objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "Test_No");
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "Row_No");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "Defect_Code");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "Defect_Description");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, "Defect_Category");
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, "No_Of_Defect");
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, "Defect_Type");
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$iRow, "Defect Area");
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$iRow, "Defect Cap");
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$iRow, "Attachment_Qty");
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$iRow, "Created_DateTime");
        $objPHPExcel->getActiveSheet()->setCellValue('L'.$iRow, "LastModified_DateTime");
        $objPHPExcel->getActiveSheet()->setCellValue('M'.$iRow, "Created_By");
        $objPHPExcel->getActiveSheet()->setCellValue('N'.$iRow, "LastModified_By");
        
        
        $objPHPExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , "A{$iRow}:N{$iRow}");

	$iRow ++;

        $sSQL = "SELECT qr.id, qr.user_id, qr.audit_code, qr.created_at, qr.date_time, qr.audit_date,
                        qrd.code_id, qrd.defects, qrd.nature, qrd.area_id, qrd.remarks, qrd.cap, qrd.area_id
                        FROM tbl_qa_reports qr, tbl_qa_report_defects qrd
			WHERE qr.id = qrd.audit_id AND qr.report_id = '14' AND (qr.audit_date BETWEEN '$FromDate' AND '$ToDate')";

        $objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$iTotals = array( );

        $sLastAuditCode = "";
        
	for ($i = 0; $i < $iCount; $i ++)
	{
                $iAuditId       = $objDb->getField($i, "id");
                $iReportId      = $objDb->getField($i, "report_id");
                $sAuditCode     = $objDb->getField($i, "audit_code");
                $sAuditDate     = $objDb->getField($i, "audit_date");
		$iAuditor       = $objDb->getField($i, "user_id");
                $sCreatedAt     = $objDb->getField($i, "created_at");
                $sModifiedAt    = $objDb->getField($i, "date_time");
                $iCodeId        = $objDb->getField($i, "code_id");
                $iDefects       = $objDb->getField($i, "defects");
                $iAreaId        = $objDb->getField($i, "area_id");
                $sDefectLevels  = $objDb->getField($i, "nature");
                $sCaps          = $objDb->getField($i, "cap");
                $sRemarks       = $objDb->getField($i, "remarks");
                $sAuditor       = @$sAuditorsList[$iAuditor];
                
                
                $sDefectLevels  = ($sDefectLevels == 2?"Critical":($sDefectLevels == 1?"Major":"Minor"));
                
                $sSQL2 = ("SELECT defect, code, (SELECT type from tbl_defect_types where tbl_defect_codes.type_id=tbl_defect_types.id) as _Type FROM tbl_defect_codes WHERE id='".$iCodeId."'");
                $objDb2->query($sSQL2);

                $sDefects     = $objDb2->getField(0, 0);
                $sDefectCode  = $objDb2->getField(0, 1);			
                $sDefectType  = $objDb2->getField(0, 2);			


                $sSQL3 = ("SELECT area FROM tbl_defect_areas WHERE id = (".$objDb->getField($i, 'area_id').")");
                $objDb3->query($sSQL3);

                $sDefectArea = $objDb3->getField(0, 0);
                
                $iPictures  = 0;

                if($sLastAuditCode != $sAuditCode)
                {
                    $iCounter = 1;
                    $sPictures   = array( );                
                    @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

                    $sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/{$sAuditCode}_*.*");
                    $sPictures = @array_map("strtoupper", $sPictures);
                    $sPictures = @array_unique($sPictures);
                }
                
                if (count($sPictures) > 0)
                {
                        foreach ($sPictures as $sPicture)
                        {
                            $sPicture = @basename($sPicture);
                                if (strpos($sPicture, "{$sAuditCode}_{$sDefectCode}_{$iAreaId}_") !== false)
                                     $iPictures++;
                        }
                }
                
                        
                $objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", $sAuditCode);
		$objPHPExcel->getActiveSheet()->setCellValue("B{$iRow}", $iCounter);
		$objPHPExcel->getActiveSheet()->setCellValue("C{$iRow}", $sDefectCode);
		$objPHPExcel->getActiveSheet()->setCellValue("D{$iRow}", $sDefects);
		$objPHPExcel->getActiveSheet()->setCellValue("E{$iRow}", $sDefectType);
		$objPHPExcel->getActiveSheet()->setCellValue("F{$iRow}", $iDefects);
		$objPHPExcel->getActiveSheet()->setCellValue("G{$iRow}", $sDefectLevels);
		$objPHPExcel->getActiveSheet()->setCellValue("H{$iRow}", $sDefectArea);
		$objPHPExcel->getActiveSheet()->setCellValue("I{$iRow}", $sCaps);
                $objPHPExcel->getActiveSheet()->setCellValue("J{$iRow}", $iPictures);
                $objPHPExcel->getActiveSheet()->setCellValue("K{$iRow}", $sCreatedAt);
                $objPHPExcel->getActiveSheet()->setCellValue("L{$iRow}", $sModifiedAt);
                $objPHPExcel->getActiveSheet()->setCellValue("M{$iRow}", $sAuditor);
                $objPHPExcel->getActiveSheet()->setCellValue("N{$iRow}", $sAuditor);
                
                $objPHPExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle , "A{$iRow}:N{$iRow}");
                
                $sLastAuditCode = $sAuditCode;
                
		$iRow ++;
                $iCounter ++;
	}


	// Set column widths
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
     
        

	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('INSPECTION_DEFECT');

	include 'PHPExcel/IOFactory.php';

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($sExcelFile);


        $sFileName = 'INSPECTION_DEFECT.xlsx';
        $sftp->put($remote_directory . $sFileName, 
                                $local_directory . $sFileName, 
                                 NET_SFTP_LOCAL_FILE);
        
	$objDb->close( );
	$objDb2->close( );
        $objDb3->close( );
        $objDbGlobal->close( );

	@ob_end_flush( );
?>