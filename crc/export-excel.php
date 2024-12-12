<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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


	$XmlFile = "";

	if ($_FILES['XmlFile']['name'] != "")
	{
		$XmlFile = IO::getFileName($_FILES['XmlFile']['name']);

		if (!@move_uploaded_file($_FILES['XmlFile']['tmp_name'], ($sBaseDir.TEMP_DIR.$XmlFile)))
				$XmlFile = "";
	}

	if ($XmlFile == "")
		redirect("era-converter.php", "NO_ERA_FILE");


	$sXml = xml2array(file_get_contents(($sBaseDir.TEMP_DIR.$XmlFile)));

	@unlink(($sBaseDir.TEMP_DIR.$XmlFile));



	$sExcelFile = ($sBaseDir.TEMP_DIR."ERA.xlsx");

	@set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	@require_once 'PHPExcel.php';
	@require_once 'PHPExcel/IOFactory.php';
	@require_once 'PHPExcel/RichText.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	$objReader   = PHPExcel_IOFactory::createReader('Excel2007');
	$objPHPExcel = $objReader->load($sBaseDir."templates/ERA.xlsx");

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree");
	$objPHPExcel->getProperties()->setTitle("ESH Rating Analysis");
	$objPHPExcel->getProperties()->setSubject("CRC");
	$objPHPExcel->getProperties()->setDescription("ESH Rating Analysis");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("CRC");

	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);


	$objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setWrapText(true);

	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 2, trim($sXml['my:myFields']['my:cls']['my:ratingdate']));
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, trim($sXml['my:myFields']['my:cls']['my:eraactivity']));
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, trim($sXml['my:myFields']['my:cls']['my:crcode']));
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 5, trim($sXml['my:myFields']['my:cls']['my:fctyname']));
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 6, trim($sXml['my:myFields']['my:cls']['my:fctycontactname']));

	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 2, ((trim($sXml['my:myFields']['my:cls']['my:nikeconductedaudit']) == "1") ? "Yes" : "No"));
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, trim($sXml['my:myFields']['my:cls']['my:nikeauditor']));
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, trim($sXml['my:myFields']['my:cls']['my:nikeauditor2']));
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 5, trim($sXml['my:myFields']['my:cls']['my:nikeauditor3']));


	// Styles
	$sRed    = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'FF0000')));
	$sOrange = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'660000')));
	$sYellow = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'FFFF00')));
	$sGreen  = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'00FF00')));
	$sWhite  = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'FFFFFF')));



	$iRow    = 9;
	$sFields = array("ChemicalManagement", "WorkerProtection", "Maintenance", "FireAndEmergencyAction", "Health");

	$sFields["ChemicalManagement"]     = array("ast", "airem", "asbestos", "hazmat", "hazwas", "pcb", "solwas", "ust", "waswat");
	$sFields["WorkerProtection"]       = array("ergo", "genwrkenv", "heatstress", "machineguarding", "nonionizing", "occexp", "occnoise", "ppegen", "pperespirator");
	$sFields["Maintenance"]            = array("conspaces", "contractorsafety", "energycontrol", "electricalsafety", "fall", "maintenancesafety", "pmv");
	$sFields["FireAndEmergencyAction"] = array("bloodborne", "emergencyaction", "firesafety", "medical");
	$sFields["Health"]                 = array("canteen", "childcare", "dormitory", "drinkingwater", "occhealth", "sanitation");

	for ($i = 0; $i < count($sFields); $i ++)
	{
		$sCategory = $sFields[$i];


		for ($j = 0; $j < count($sFields[$sCategory]); $j ++)
		{
			$sItem = $sFields[$sCategory][$j];


			$iPerformance = $sXml["my:myFields"]["my:{$sCategory}"]["my:{$sItem}performance"];
			$iRisk        = $sXml["my:myFields"]["my:{$sCategory}"]["my:{$sItem}severity"];
			$sComments    = trim($sXml["my:myFields"]["my:{$sCategory}"]["my:{$sItem}comment"]);
			$sFeedback    = trim($sXml["my:myFields"]["my:{$sCategory}"]["my:praction{$sItem}"]);

			if ($sItem == "hazwas" && $sComments == "")
				$sComments = trim($sXml["my:myFields"]["my:{$sCategory}"]["my:haswascomment"]);

			if ($iPerformance == "Array")
				$iPerformance = "";

			if ($iRisk == "Array")
				$iRisk = "";

			if ($sComments == "Array")
				$sComments = "";

			if ($sFeedback == "Array")
				$sFeedback = "";


			$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}:F{$iRow}")->applyFromArray($sWhite);
			$objPHPExcel->getActiveSheet()->getStyle("B{$iRow}:B{$iRow}")->applyFromArray((($iPerformance == 1) ? $sRed : (($iPerformance == 2) ? $sOrange : (($iPerformance == 3) ? $sYellow : (($iPerformance == 4) ? $sGreen : $sWhite)))));
			$objPHPExcel->getActiveSheet()->getStyle("C{$iRow}:C{$iRow}")->applyFromArray((($iRisk == 1) ? $sRed : (($iRisk == 2) ? $sOrange : (($iRisk == 3) ? $sYellow : (($iRisk == 4) ? $sGreen : $sWhite)))));

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, $sComments);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, $sFeedback);

			$objPHPExcel->getActiveSheet()->getRowDimension($iRow)->setRowHeight((@ceil(strlen($sComments) / 55) * 14) + 15);

			$iRow ++;
		}
	}


	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);


	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($sExcelFile);


	// forcing csv file to download
	$iSize = @filesize($sExcelFile);

	if(ini_get('zlib.output_compression'))
		@ini_set('zlib.output_compression', 'Off');

	header('Content-Description: File Transfer');
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
	header('Content-Type: application/force-download');
	header("Content-Type: application/download");
	header("Content-Type: text/xlsx");
	header("Content-Disposition: attachment; filename=\"".basename($sExcelFile)."\";");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: $iSize");

	@readfile($sExcelFile);
	@unlink($sExcelFile);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>