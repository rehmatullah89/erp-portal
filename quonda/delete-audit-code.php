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

	if ($sUserRights['Delete'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id                 = IO::intValue('Id');
	$sSpecsSheets       = array( );

	
	$objDb->execute("BEGIN");

	$sSQL = "SELECT * FROM tbl_qa_reports WHERE id='$Id'";
	$objDb->query($sSQL);

    $iReport       = $objDb->getField(0, "report_id");
    $sAuditResult  = $objDb->getField(0, "audit_result");


	if ($_SESSION["UserType"] == "JCREW" && $iReport != 46)
		redirect($_SERVER['HTTP_REFERER'], "ERROR");
		
        
    if ($objDb->getCount( ) == 1 && $sUserRights['Delete'] == "Y" && $sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y")
	{
		$sAuditDate = $objDb->getField(0, "audit_date");
		
		for ($i = 1; $i <= 10; $i ++)
		{
			$sSpecsSheet = $objDb->getField(0, "specs_sheet_{$i}");
			
			if ($sSpecsSheet != "")
				$sSpecsSheets[] = $sSpecsSheet;
		}


		$sSQL  = "DELETE FROM tbl_qa_reports WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$sSQL  = "DELETE FROM tbl_qa_report_defects WHERE audit_id='$Id'";
			$bFlag = $objDb->execute($sSQL);
		}
		
		if ($bFlag == true)
		{
			$sSQL  = "DELETE FROM tbl_qa_report_progress WHERE audit_id='$Id'";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true)
		{
			$sSQL  = "DELETE FROM tbl_audit_subscriptions WHERE audit_id='$Id'";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true)
		{
			$sSQL  = "DELETE FROM tbl_arcadia_inspection_summary WHERE audit_id='$Id'";
			$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
		}

		if ($bFlag == true)
		{
			$sSQL  = "DELETE FROM tbl_arcadia_samples_per_size WHERE audit_id='$Id'";
			$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
		}

		if ($bFlag == true)
		{
			$sSQL  = "DELETE FROM tbl_qa_report_sample_specs WHERE sample_id IN (SELECT id FROM tbl_qa_report_samples WHERE audit_id='$Id')";
			$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
		}

		if ($bFlag == true)
		{
			$sSQL  = "DELETE FROM tbl_qa_report_samples WHERE audit_id='$Id'";
			$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
		}

		if ($bFlag == true)
		{
			$sSQL  = "DELETE FROM tbl_mgf_reports WHERE audit_id='$Id'";
			$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
		}

		if ($bFlag == true)
		{
			$sSQL  = "DELETE FROM tbl_qa_report_images WHERE audit_id='$Id'";
			$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

/*
			@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

			$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?{$Id}_*.*");
			$sPictures = @array_map("strtoupper", $sPictures);
			$sPictures = @array_unique($sPictures);

			foreach ($sPictures as $sPicture)
			{
				@unlink($sPicture);
			}
			
			
			foreach ($sSpecsSheets as $sSpecsSheet)
			{
				@unlink($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet);
				@unlink($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsSheet);
				
				@unlink($sBaseDir.SPECS_SHEETS_DIR."thumbs/".$sSpecsSheet);
				@unlink($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/thumbs/".$sSpecsSheet);
			}
*/

			$_SESSION['Flag'] = "AUDIT_CODE_DELETED";
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION['Flag'] = "DB_ERROR";
		}
	}
	
	else
		$_SESSION['Flag'] = "ERROR";


	header("Location: {$_SERVER['HTTP_REFERER']}");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>