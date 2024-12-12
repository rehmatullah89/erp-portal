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

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$User      = IO::strValue('User');
	$AuditCode = IO::strValue("AuditCode");

	$iAuditCode = intval(substr($AuditCode, 1));


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $iAuditCode == 0 || $AuditCode{0} != "S")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser = $objDb->getField(0, "id");
			$sName = $objDb->getField(0, "name");


			$sSQL = "SELECT * FROM tbl_qa_reports WHERE id='$iAuditCode'";
			$objDb->query($sSQL);

			$sAuditDate   = $objDb->getField(0, "audit_date");
			$sSpecsSheets = array( );
			
			for ($i = 1; $i <= 10; $i ++)
			{
				$sSpecsSheet = $objDb->getField(0, "specs_sheet_{$i}");
				
				if ($sSpecsSheet != "")
					$sSpecsSheets[] = $sSpecsSheet;
			}
					

		
			$objDb->execute("BEGIN", true, $iUser, $sName);

			$sSQL  = "DELETE FROM tbl_qa_reports WHERE id='$iAuditCode'";
			$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_qa_report_defects WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}
			
			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_qa_report_progress WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_gf_inspection_checklist WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_gf_rolls_info WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_gf_report_defects WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_ar_inspection_checklist WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_ar_beautiful_products WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_jako_qa_reports WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_jako_packing WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_jako_audits WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_yarn_qa_reports WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_yarn_marking_label WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_yarn_packing WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_yarn_appearance WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_yarn_product_conformity WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_yarn_product_checks WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_ms_qa_reports WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_audit_subscriptions WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_kik_inspection_summary WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_kik_samples_per_size WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_arcadia_inspection_summary WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_arcadia_samples_per_size WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}
				
				if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_timezone_inspection_summary WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_timezone_samples_per_size WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}
		
			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_qa_report_sample_specs WHERE sample_id IN (SELECT id FROM tbl_qa_report_samples WHERE audit_id='$iAuditCode')";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_qa_report_samples WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_tnc_report_defects WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}
					
			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_bbg_reports WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_bbg_status WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_bbg_final_pos WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_bbg_carton_details WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}
			
			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_hybrid_apparel_reports WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}
			
			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_hybrid_link_reports WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_hybrid_link_report_check_details WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}
				
			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_mgf_reports WHERE audit_id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$objDb->execute("COMMIT", true, $iUser, $sName);


				@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

				$sPictures = @glob(ABSOLUTE_PATH.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?{$iAuditCode}_*.*");
				$sPictures = @array_map("strtoupper", $sPictures);
				$sPictures = @array_unique($sPictures);

				foreach ($sPictures as $sPicture)
				{
					@unlink($sPicture);
				}
					
				foreach ($sSpecsSheets as $sSpecsSheet)
				{
					@unlink(ABSOLUTE_PATH.SPECS_SHEETS_DIR.$sSpecsSheet);
					@unlink(ABSOLUTE_PATH.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsSheet);
					
					@unlink(ABSOLUTE_PATH.SPECS_SHEETS_DIR."thumbs/".$sSpecsSheet);
					@unlink(ABSOLUTE_PATH.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/thumbs/".$sSpecsSheet);
				}				


				$aResponse['Status']  = "OK";
				$aResponse['Message'] = "The selected Audit Code has been Deleted successfully.";
			}

			else
			{
				$objDb->execute("ROLLBACK", true, $iUser, $sName);

				$aResponse['Message'] = "An ERROR occured while processing your request.";
			}
		}
	}


	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>