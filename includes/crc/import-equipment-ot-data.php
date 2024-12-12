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

	@set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');
	@include 'PHPExcel/IOFactory.php';

	$objExcelReader = PHPExcel_IOFactory::createReader('Excel2007');
	$objExcelReader->setReadDataOnly(true);

	$objExcel = $objExcelReader->load($sBaseDir.TEMP_DIR.$OtFile);
	$objSheet = $objExcel->getSheet(0);

	$iRows    = $objSheet->getHighestRow();
	$sColumns = $objSheet->getHighestColumn();
	$iColumns = PHPExcel_Cell::columnIndexFromString($sColumns);


	if (trim($objSheet->getCellByColumnAndRow(0, 1)->getValue( ), ": ") != "Factory Name" ||
		trim($objSheet->getCellByColumnAndRow(0, 2)->getValue( ), ": ") != "Factory Code" ||
		trim($objSheet->getCellByColumnAndRow(0, 3)->getValue( ), ": ") != "Report Date" ||
		trim($objSheet->getCellByColumnAndRow(0, 5)->getValue( )) != "Department")
	{
		@unlink($sBaseDir.TEMP_DIR.$OtFile);

		redirect("ot-data.php", "INVALID_OT_FILE");
	}



	$objDb->execute("BEGIN");

	$bFlag = true;


	for ($i = 6; $i < ($iRows - 1); $i ++)
	{
		$iColumn     = 0;
		$iWeek       = 1;
		$sDepartment = trim(addslashes($objSheet->getCellByColumnAndRow($iColumn++, $i)->getValue( )));
		$iDepartment = getDbValue("id", "tbl_ot_departments", "department='$sDepartment'");

		if ($iDepartment == 0)
		{
			$iDepartment = getNextId("tbl_ot_departments");

			$sSQL = "INSERT INTO tbl_ot_departments (id, department) VALUES ('$iDepartment', '$sDepartment')";
			$objDb->execute($sSQL);
		}



		while ($iColumn <= $iColumns && $iWeek <= 5)
		{
			$iEmployees = intval(addslashes($objSheet->getCellByColumnAndRow($iColumn++, $i)->getValue( )));
			$iDailyOT3  = floatval(addslashes($objSheet->getCellByColumnAndRow($iColumn++, $i)->getValue( )));

			$iColumn++;

			$iWorkingHrs60 = floatval(addslashes($objSheet->getCellByColumnAndRow($iColumn++, $i)->getValue( )));

			$iColumn++;

			$iWorkingHrs6172 = floatval(addslashes($objSheet->getCellByColumnAndRow($iColumn++, $i)->getValue( )));

			$iColumn++;

			$iWorkingHrs72 = floatval(addslashes($objSheet->getCellByColumnAndRow($iColumn++, $i)->getValue( )));

			$iColumn++;

			$iSunday = floatval(addslashes($objSheet->getCellByColumnAndRow($iColumn++, $i)->getValue( )));

			$iColumn++;

			$iLacking1DayOff7 = floatval(addslashes($objSheet->getCellByColumnAndRow($iColumn++, $i)->getValue( )));

			$iColumn++;

			$iLacking1DayOff14 = floatval(addslashes($objSheet->getCellByColumnAndRow($iColumn++, $i)->getValue( )));

			$iColumn++;

			$iSwitchesHrs = floatval(addslashes($objSheet->getCellByColumnAndRow($iColumn++, $i)->getValue( )));

			$iColumn++;



			$sSQL = "DELETE FROM tbl_ot_equipment WHERE vendor_id='$Vendor' AND year='$Year' AND month='$Month' AND week='$iWeek' AND department_id='$iDepartment'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == false)
				break;


			$sSQL = "INSERT INTO tbl_ot_equipment SET vendor_id      = '$Vendor',
												      year           = '$Year',
													  month          = '$Month',
													  week           = '$iWeek',
													  department_id  = '$iDepartment',
													  employees      = '$iEmployees',
													  daily_hrs_03   = '$iDailyOT3',
													  week_hrs_60    = '$iWorkingHrs60',
													  week_hrs_60_72 = '$iWorkingHrs6172',
													  week_hrs_72    = '$iWorkingHrs72',
													  sunday         = '$iSunday',
													  d1_off_in_7    = '$iLacking1DayOff7',
													  d1_off_in_14   = '$iLacking1DayOff14',
													  switches_hrs   = '$iSwitchesHrs'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == false)
				break;

			$iWeek ++;
		}
	}


	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		$_SESSION["Flag"] = "OT_FILE_IMPORTED";
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION["Flag"] = "DB_ERROR";
	}
?>