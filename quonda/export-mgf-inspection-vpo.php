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
        
        $FromDate   = date("Y-m-d", strtotime("last week"));
	$ToDate     = date("Y-m-d");
        
	$sAuditorsList      = getList("tbl_users", "id", "name");
	
        $sExcelFile = ($sBaseDir.TEMP_DIR."INSPECTION_VPO.xlsx");

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
	$objPHPExcel->getProperties()->setTitle("MGF INSPECTIONS VPO LIST");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("MGF INSPECTIONS VPO LIST");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");


	// Create a first sheet
	$iRow = 1;

        $objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "Test_No");
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "VPO_No");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "Created_DateTime");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "Created_By");
	
        
        $objPHPExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , "A{$iRow}:D{$iRow}");

	$iRow ++;

	$sSQL = "SELECT qr.id, qr.po_id, qr.user_id, qr.audit_code, qr.created_at, qr.additional_pos
                         FROM tbl_qa_reports qr
			 WHERE qr.report_id = '14' AND (qr.audit_date BETWEEN '$FromDate' AND '$ToDate')";

        $objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$iTotals = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
                $iAuditId       = $objDb->getField($i, "id");
                $iReportId      = $objDb->getField($i, "audit_code");
		$iAuditor       = $objDb->getField($i, "user_id");
                $sAuditor       = @$sAuditorsList[$iAuditor];
                $iVPo           = getDbValue("customer_po_no", "tbl_po", "id = {$objDb->getField($i, "po_id")}") ;
                $iAdditionlPos  = getList("tbl_po", "id", "customer_po_no", "id IN ({$objDb->getField($i, "additional_pos")})");
                $sCreatedAt     = $objDb->getField($i, "created_at");
                
                $objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", $iReportId);
                $objPHPExcel->getActiveSheet()->setCellValue("B{$iRow}", $iVPo);
                $objPHPExcel->getActiveSheet()->setCellValue("C{$iRow}", $sCreatedAt);
                $objPHPExcel->getActiveSheet()->setCellValue("D{$iRow}", $sAuditor);

                $objPHPExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle , "A{$iRow}:D{$iRow}");

                $iRow ++;

                foreach($iAdditionlPos as $sAdditionalPo){

                        $objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", $iReportId);
                        $objPHPExcel->getActiveSheet()->setCellValue("B{$iRow}", $sAdditionalPo);
                        $objPHPExcel->getActiveSheet()->setCellValue("C{$iRow}", $sCreatedAt);
                        $objPHPExcel->getActiveSheet()->setCellValue("D{$iRow}", $sAuditor);

                        $objPHPExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle , "A{$iRow}:D{$iRow}");

                        $iRow ++;

                }
                
	}


	// Set column widths
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('INSPECTION_VPO');

	include 'PHPExcel/IOFactory.php';

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($sExcelFile);


        $sFileName = 'INSPECTION_VPO.xlsx';
        $sftp->put($remote_directory . $sFileName, 
                                $local_directory . $sFileName, 
                                 NET_SFTP_LOCAL_FILE);
        
	$objDb->close( );
	$objDb2->close( );
        $objDbGlobal->close( );

        @ob_end_flush( );
?>