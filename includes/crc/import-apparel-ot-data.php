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


	if (trim($objSheet->getCellByColumnAndRow(0, 1)->getValue( )) != "OT Tracking Report" ||
		trim($objSheet->getCellByColumnAndRow(2, 1)->getValue( )) != "Month" ||
	    trim($objSheet->getCellByColumnAndRow(0, 2)->getValue( )) != "Department" ||
		trim($objSheet->getCellByColumnAndRow(1, 2)->getValue( )) != "Weeks" ||
		trim($objSheet->getCellByColumnAndRow(2, 2)->getValue( )) != "Total  # of Employees" ||
		trim($objSheet->getCellByColumnAndRow(3, 2)->getValue( )) != "Category" ||
		trim($objSheet->getCellByColumnAndRow(4, 2)->getValue( )) != "Data")
	{
		@unlink($sBaseDir.TEMP_DIR.$OtFile);

		redirect("ot-data.php", "INVALID_OT_FILE");
	}



	$objDb->execute("BEGIN");

	$bFlag = true;


	for ($i = 3; $i < $iRows;)
	{
		$sDepartment = trim(addslashes($objSheet->getCellByColumnAndRow(0, $i)->getValue( )));
		$sWeek       = trim(addslashes($objSheet->getCellByColumnAndRow(1, $i)->getValue( )));
		$iEmployees  = intval(addslashes($objSheet->getCellByColumnAndRow(2, $i)->getValue( )));

		if ($sDepartment == "Whole Factory")
		{
			$i ++;

			continue;
		}


		$iData       = array( );
		$iWeek       = intval(str_ireplace("Week ", "", $sWeek));
		$iDepartment = getDbValue("id", "tbl_ot_departments", "department='$sDepartment'");

		if ($iDepartment == 0)
		{
			$iDepartment = getNextId("tbl_ot_departments");

			$sSQL = "INSERT INTO tbl_ot_departments (id, department) VALUES ('$iDepartment', '$sDepartment')";
			$objDb->execute($sSQL);
		}



		for ($j = 0; $j < 5; $j ++, $i ++)
		{
			$sCategory = trim(addslashes($objSheet->getCellByColumnAndRow(3, $i)->getValue( )));
			$iOverTime = intval(addslashes($objSheet->getCellByColumnAndRow(4, $i)->getValue( )));

			$iData[$j] = $iOverTime;
/*
			switch ($sCategory)
			{
				case "Sunday/Rest Days"  : $iData[0] = $iOverTime; break;
				case "(=0)"              : $iData[1] = $iOverTime; break;
				case ">0-12"             : $iData[2] = $iOverTime; break;
				case ">12-24"            : $iData[3] = $iOverTime; break;
				case ">24"               : $iData[4] = $iOverTime; break;
			}
*/
		}


		$sSQL = "DELETE FROM tbl_ot_apparel WHERE vendor_id='$Vendor' AND year='$Year' AND month='$Month' AND week='$iWeek' AND department_id='$iDepartment'";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == false)
			break;


		$sSQL = "INSERT INTO tbl_ot_apparel SET vendor_id        = '$Vendor',
												year             = '$Year',
												month            = '$Month',
												week             = '$iWeek',
												department_id    = '$iDepartment',
												employees        = '$iEmployees',
												sunday_rest_days = '{$iData[0]}',
												hrs_0            = '{$iData[1]}',
												hrs_0_12         = '{$iData[2]}',
												hrs_12_24        = '{$iData[3]}',
												hrs_24           = '{$iData[4]}'";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == false)
			break;
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