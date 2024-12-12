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

	$AuditCode  = IO::strValue('AuditCode');
	$Defects    = IO::strValue('Defects');
	$iAuditCode = intval(substr($AuditCode, 1));

	$aResponse = array( );



	if ($iAuditCode == 0 || strlen($AuditCode) == 0 || $AuditCode{0} != "S")
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Audit Code";
	}

	else
	{
		$sSQL = "SELECT audit_date FROM tbl_qa_reports WHERE id='$iAuditCode'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "No QA Report Found!";
		}

		else
		{
			$sAuditDate = $objDb->getField(0, 0);

			@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);


			$sDefectPics = array( );

			$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$iAuditCode' AND FIND_IN_SET(id, '$Defects')";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$sDefectCode = getDbValue("code", "tbl_defect_codes", ("id='".$objDb->getField($i, 'code_id')."'"));
				$sAreaCode   = $objDb->getField($i, 'area_id');


				$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($AuditCode, 1)."_{$sDefectCode}_{$sAreaCode}*.*");
				$sPictures = @array_map("strtoupper", $sPictures);
				$sPictures = @array_unique($sPictures);

				$sDefectPics = @array_merge($sDefectPics, $sPictures);
			}



			$bFlag = $objDb->execute("BEGIN", true, $User, $sUser);

			if ($iCount > 0)
			{
				$sSQL  = "DELETE FROM tbl_qa_report_defects WHERE audit_id='$iAuditCode' AND FIND_IN_SET(id, '$Defects')";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);

				if ($bFlag == true)
				{
					$iTotalGmts = getDbValue("total_gmts", "tbl_qa_reports", "id='$iAuditCode'");
					$iDefects   = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditCode'");
					$fDhu       = @round((($iDefects / $iTotalGmts) * 100), 2);
				}
			}

			else
			{
				$sSQL = "SELECT * FROM tbl_gf_report_defects WHERE audit_id='$iAuditCode' AND FIND_IN_SET(id, '$Defects')";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					$sDefectCode = getDbValue("code", "tbl_defect_codes", ("id='".$objDb->getField($i, 'code_id')."'"));


					$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($AuditCode, 1)."_{$sDefectCode}*.*");
					$sPictures = @array_map("strtoupper", $sPictures);
					$sPictures = @array_unique($sPictures);

					$sDefectPics = @array_merge($sDefectPics, $sPictures);
				}

				$sSQL  = "DELETE FROM tbl_gf_report_defects WHERE audit_id='$iAuditCode' AND FIND_IN_SET(id, '$Defects')";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);

				if ($bFlag == true)
				{
					$iTotalDefects  = getDbValue("SUM(defects)", "tbl_gf_report_defects", "audit_id='$iAuditCode'");
					$iTotalPoints   = getDbValue("SUM((defects * grade))", "tbl_gf_report_defects", "audit_id='$iAuditCode'");
					$iTotalGivenQty = getDbValue("SUM((given_1 + given_2 + given_3))", "tbl_gf_rolls_info", "audit_id='$iAuditCode'");
					$iPoId          = getDbValue("po_id", "tbl_qa_reports", "id='$iAuditCode'");
					$iFabricWidth   = getDbValue("fabric_width", "tbl_qa_reports", "id='$iAuditCode'");


					if (getDbValue("brand_id", "tbl_po", "id='$iPoId'") == 77)
						$fDhu = round((($iTotalDefects * 39.37 * 100) / $iTotalGivenQty / $iFabricWidth), 2);

					else
						$fDhu = round(((($iTotalPoints * 3600) / $iTotalGivenQty) / $iFabricWidth), 2);
				}
			}


			if ($bFlag == true)
			{
				$sSQL  = "UPDATE tbl_qa_reports SET dhu='$fDhu' WHERE id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true)
			{
				$objDb->execute("COMMIT", true, $User, $sUser);


				for ($i = 0; $i < count($sDefectPics); $i ++)
					@unlink($sDefectPics[$i]);


				$aResponse['Status']  = "OK";
				$aResponse['Message'] = "Defects Deleted";
			}

			else
			{
				$objDb->execute("ROLLBACK", true, $User, $sUser);

				$aResponse['Status'] = "ERROR";
				$aResponse['Error']  = "An ERROR occured.";
			}
		}
	}


	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>