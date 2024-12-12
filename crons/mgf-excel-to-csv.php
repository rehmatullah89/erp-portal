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

	@session_start( );

	@ini_set('display_errors', 0);
	@ini_set('log_errors', 0);
	@error_reporting(0);
	
	@ini_set("max_execution_time", 0);


	print ("START: ".date("h:i A")."<hr />");
	
	
	$sBaseDir = "C:/wamp/www/portal/";

	@require_once($sBaseDir."requires/common-functions.php");
	@require_once($sBaseDir."requires/PHPExcel/IOFactory.php");
	
	$sVpoFileXlsx = "{$sBaseDir}mgf/vpo-list.xlsx";
	$sVpoFileCsv  = "{$sBaseDir}mgf/vpo-list.csv";
	
	@unlink($sVpoFileCsv);
	
	
	try
	{	
		$sFileType   = PHPExcel_IOFactory::identify($sVpoFileXlsx);
		$objReader   = PHPExcel_IOFactory::createReader($sFileType);
		$objPHPExcel = $objReader->load($sVpoFileXlsx);
		
	}
	
	catch (Exception $e)
	{
		die('Error loading file "' . pathinfo($sVpoFileXlsx, PATHINFO_BASENAME). '": ' . $e->getMessage());
	}

	
	$objWorkSheet = $objPHPExcel->getSheet(0);
	$iTotalRows   = $objWorkSheet->getHighestRow();
	
	
	$hFile = @fopen($sVpoFileCsv, "w"); 
	
	for ($iRow = 7; $iRow <= $iTotalRows; $iRow++) 
	{		
		$sSupplier     = trim(stripslashes($objWorkSheet->getCellByColumnAndRow(0, $iRow)->getValue()));
		$iManufacturer = trim(stripslashes($objWorkSheet->getCellByColumnAndRow(1, $iRow)->getValue()));
		$sIndustry     = trim(stripslashes($objWorkSheet->getCellByColumnAndRow(2, $iRow)->getValue()));
		$sEDI_Ind      = trim(stripslashes($objWorkSheet->getCellByColumnAndRow(3, $iRow)->getValue()));
		$sFactory      = trim(stripslashes($objWorkSheet->getCellByColumnAndRow(4, $iRow)->getValue()));
		$sPerfomaNo    = trim(stripslashes($objWorkSheet->getCellByColumnAndRow(5, $iRow)->getValue()));
		$sOrderNo      = trim(stripslashes($objWorkSheet->getCellByColumnAndRow(6, $iRow)->getValue()));				
		$sLine         = trim($objWorkSheet->getCellByColumnAndRow(7, $iRow)->getValue());
		$sColorCode    = trim($objWorkSheet->getCellByColumnAndRow(8, $iRow)->getValue());
		$sColorDesc    = addslashes(trim(stripslashes($objWorkSheet->getCellByColumnAndRow(9, $iRow)->getValue())));
		$sSizeCode     = trim($objWorkSheet->getCellByColumnAndRow(10, $iRow)->getValue());
		$sSizeDesc     = trim($objWorkSheet->getCellByColumnAndRow(11, $iRow)->getValue());
		$iQuantity     = trim($objWorkSheet->getCellByColumnAndRow(12, $iRow)->getValue());
		$iSo_Id        = trim($objWorkSheet->getCellByColumnAndRow(13, $iRow)->getValue());
		$sSalesOrderNo = trim($objWorkSheet->getCellByColumnAndRow(14, $iRow)->getValue());
		$sStyle        = trim(stripslashes($objWorkSheet->getCellByColumnAndRow(15, $iRow)->getValue()));
		$sBusiness     = trim(stripslashes($objWorkSheet->getCellByColumnAndRow(16, $iRow)->getValue()));
		$sEtdRequired  = trim($objWorkSheet->getCellByColumnAndRow(17, $iRow)->getValue());
		$iCustomer     = trim($objWorkSheet->getCellByColumnAndRow(18, $iRow)->getValue());
		$sStyleDesc    = addslashes(trim(stripslashes($objWorkSheet->getCellByColumnAndRow(19, $iRow)->getValue())));
		$sVendor       = addslashes(trim(stripslashes($objWorkSheet->getCellByColumnAndRow(20, $iRow)->getValue())));
		
		if ($iRow > 7)
			$sEtdRequired = parseDate($sEtdRequired);
		
		$sLine = array($sSupplier, $iManufacturer, $sIndustry, $sEDI_Ind, $sFactory, $sPerfomaNo, $sOrderNo, $sLine, $sColorCode, $sColorDesc, $sSizeCode, $sSizeDesc, $iQuantity, $iSo_Id, $sSalesOrderNo, $sStyle, $sBusiness, $sEtdRequired, $iCustomer, $sStyleDesc, $sVendor);
		
		@fputcsv($hFile, $sLine);			
    }
	
	@fclose($hFile);
	
	
	print ("<hr />END: ".date("h:i A")."<br />");
?>