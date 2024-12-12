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


	$XmlFile   = "";
	$ExcelFile = "";

	if ($_FILES['XmlFile']['name'] != "")
	{
		$XmlFile = IO::getFileName($_FILES['XmlFile']['name']);

		if (!@move_uploaded_file($_FILES['XmlFile']['tmp_name'], ($sBaseDir.TEMP_DIR.$XmlFile)))
				$XmlFile = "";
	}

	if ($_FILES['ExcelFile']['name'] != "")
	{
		$ExcelFile = IO::getFileName($_FILES['ExcelFile']['name']);

		if (!@move_uploaded_file($_FILES['ExcelFile']['tmp_name'], ($sBaseDir.TEMP_DIR.$ExcelFile)))
				$ExcelFile = "";
	}

	if ($XmlFile == "" || $ExcelFile == "")
		redirect("era-converter.php", "NO_ERA_FILE");


	$sXml = @file_get_contents(($sBaseDir.TEMP_DIR.$XmlFile));

	@unlink(($sBaseDir.TEMP_DIR.$XmlFile));



	$sXmlFile = ($sBaseDir.TEMP_DIR."ERA-test.xml");

	//$hFile = @fopen(($sBaseDir.TEMP_DIR."ERA-test.xml"), "r");



	@set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	@require_once 'PHPExcel.php';
	@require_once 'PHPExcel/IOFactory.php';

	$objExcelReader = PHPExcel_IOFactory::createReader('Excel2007');
	$objExcelReader->setReadDataOnly(true);

	$objExcel = $objExcelReader->load(($sBaseDir.TEMP_DIR.$ExcelFile));
	$objSheet = $objExcel->getSheet(0);


	$iRow    = 9;
	$sFields = array("ChemicalManagement", "WorkerProtection", "Maintenance", "FireAndEmergencyAction", "Health");

	$sFields["ChemicalManagement"]     = array("ast", "airem", "asbestos", "hazmat", "haswas", "pcb", "solwas", "ust", "waswat");
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


			$sComments = trim($objSheet->getCellByColumnAndRow(3, $iRow)->getValue( ));

			if (@strpos($sXml, "<my:{$sItem}comment>") === FALSE && $sComments == "")
			{
				$iRow ++;

				continue;
			}


			$iStart  = (strpos($sXml, "<my:{$sItem}comment>") + strlen("<my:{$sItem}comment>"));
			$iEnd    = strpos($sXml, "</my:{$sItem}comment>");
			$iLength = ($iEnd - $iStart);

			$sReplace  = @substr($sXml, $iStart, $iLength);
			$sComments = str_replace("&", "&amp;", $sComments);

			if (@strpos($sXml, "<my:{$sItem}comment>") === FALSE)
			{
				$sXml = @str_replace("<my:{$sCategory}>", "<my:{$sCategory}>\r\n<my:{$sItem}comment>{$sComments}</my:{$sItem}comment>", $sXml);

				if ($sItem == "haswas")
					$sItem = "hazwas";


				if (@strpos($sXml, "<my:{$sItem}na>") === FALSE)
					$sXml = @str_replace("<my:{$sCategory}>", "<my:{$sCategory}>\r\n<my:{$sItem}na></my:{$sItem}na>", $sXml);

				if (@strpos($sXml, "<my:{$sItem}performance>") === FALSE)
					$sXml = @str_replace("<my:{$sCategory}>", "<my:{$sCategory}>\r\n<my:{$sItem}performance></my:{$sItem}performance>", $sXml);

				if (@strpos($sXml, "<my:{$sItem}severity>") === FALSE)
					$sXml = @str_replace("<my:{$sCategory}>", "<my:{$sCategory}>\r\n<my:{$sItem}severity></my:{$sItem}severity>", $sXml);

				if (@strpos($sXml, "<my:praction{$sItem}>") === FALSE)
					$sXml = @str_replace("<my:{$sCategory}>", "<my:{$sCategory}>\r\n<my:praction{$sItem}></my:praction{$sItem}>", $sXml);

				if (@strpos($sXml, "<my:prduedate{$sItem}>") === FALSE)
					$sXml = @str_replace("<my:{$sCategory}>", "<my:{$sCategory}>\r\n<my:prduedate{$sItem} xsi:nil=\"true\"></my:prduedate{$sItem}>", $sXml);

				if (@strpos($sXml, "<my:pr{$sItem}>") === FALSE)
					$sXml = @str_replace("<my:{$sCategory}>", "<my:{$sCategory}>\r\n<my:pr{$sItem} xsi:nil=\"true\"></my:pr{$sItem}>", $sXml);
			}

			else
				$sXml = @str_replace("<my:{$sItem}comment>{$sReplace}</my:{$sItem}comment>", "<my:{$sItem}comment>{$sComments}</my:{$sItem}comment>", $sXml);


			$iRow ++;
		}
	}


	@unlink(($sBaseDir.TEMP_DIR.$ExcelFile));

	@file_put_contents(($sBaseDir.TEMP_DIR.$sXmlFile), $sXml);


	// forcing csv file to download
	$iSize = @filesize($sXmlFile);

	if(ini_get('zlib.output_compression'))
		@ini_set('zlib.output_compression', 'Off');

	header('Content-Description: File Transfer');
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
	header('Content-Type: application/force-download');
	header("Content-Type: application/download");
	header("Content-Type: text/xml");
	header("Content-Disposition: attachment; filename=\"".basename($sXmlFile)."\";");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: $iSize");

	@readfile($sXmlFile);
	@unlink($sXmlFile);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>