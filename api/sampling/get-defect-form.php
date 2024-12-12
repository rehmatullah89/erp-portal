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

	$RequestCode  = IO::strValue("RequestCode");
	$iRequestCode = intval(substr($RequestCode, 1));

	$aResponse = array( );


	if ($iRequestCode == 0 || strlen($RequestCode) == 0 || $RequestCode{0} != "M")
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Request Code";
	}

	else
	{
		$iStyleId     = getDbValue("style_id", "tbl_merchandisings", "id='$iRequestCode'");
		$iBrandId     = getDbValue("brand_id", "tbl_styles", "id='$iStyleId'");
		$iReportId    = getDbValue("report_id", "tbl_comment_sheets", "merchandising_id='$iRequestCode'");
		$sAreas       = getList("tbl_sampling_defect_areas", "id", "CONCAT(LPAD(id, 3, '0'), ' - ', area)", "", "area");
		$sDefectCodes = array( );
		$sDefectArea  = array( );


		$sSQL = "SELECT DISTINCT(type_id),
		                (SELECT type FROM tbl_sampling_defect_types WHERE id=tbl_sampling_defect_codes.type_id) AS _Type
		         FROM tbl_sampling_defect_codes
		         WHERE report_id='$iReportId' AND brand_id='$iBrandId'
		         ORDER BY _Type";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iTypeId = $objDb->getField($i, 0);
			$sType   = $objDb->getField($i, 1);

			$sDefectCodes[] = "0||{$sType} ";


			$sSQL = "SELECT id, code, defect FROM tbl_sampling_defect_codes WHERE report_id='$iReportId' AND brand_id='$iBrandId' AND type_id='$iTypeId' ORDER BY code";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
			{
				$iCodeId = $objDb2->getField($j, 0);
				$sCode   = $objDb2->getField($j, 1);
				$sDefect = $objDb2->getField($j, 2);

				$sDefectCodes[] = "{$iCodeId}||{$sCode} - {$sDefect}";
			}
		}


		foreach ($sAreas as $sKey => $sValue)
			$sDefectAreas[] = "{$sKey}||{$sValue}";


		$aResponse['Status']      = "OK";
		$aResponse['DefectCodes'] = @implode("|-|", $sDefectCodes);
		$aResponse['DefectAreas'] = @implode("|-|", $sDefectAreas);
	}


	print @json_encode($aResponse);


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>