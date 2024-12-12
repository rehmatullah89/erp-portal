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

	$User      = IO::intValue('User');
	$AuditCode = IO::strValue("AuditCode");

	$iAuditCode = intval(substr($AuditCode, 1));


	$aResponse = array( );


	if ($User == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid User ID";
	}

	else if ($iAuditCode == 0 || strlen($AuditCode) == 0 || $AuditCode{0} != "S")
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Audit Code";
	}

	else
	{
		$sStatus = getDbValue("status", "tbl_users", "id='$User'");

		if ($sStatus != "A")
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "User Account is Disabled";
		}

		else
		{
			$sUser      = getDbValue("name", "tbl_users", "id='$User'");
			$sAuditDate = getDbValue("audit_date", "tbl_qa_reports", "id='$iAuditCode'");


			$objDb->execute("BEGIN");

			$sSQL  = "DELETE FROM tbl_qa_reports WHERE id='$iAuditCode'";
			$bFlag = $objDb->execute($sSQL, true, $User, $sUser);

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_qa_report_defects WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_gf_inspection_checklist WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_gf_rolls_info WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_gf_report_defects WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_ar_inspection_checklist WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_jako_qa_reports WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_jako_packing WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_jako_audits WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_yarn_qa_reports WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_yarn_marking_label WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_yarn_packing WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_yarn_appearance WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_yarn_product_conformity WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_yarn_product_checks WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_ms_qa_reports WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true)
			{
				$objDb->execute("COMMIT");

/*
				@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

				$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?{$iAuditCode}_*.*");
				$sPictures = @array_map("strtoupper", $sPictures);
				$sPictures = @array_unique($sPictures);

				foreach ($sPictures as $sPicture)
				{
					@unlink($sPicture);
				}
*/

				print "Deleted";
			}

			else
				$objDb->execute("ROLLBACK");
		}
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>