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
	@require_once("../requires/image-functions.php");

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$Id      = IO::intValue('Id');
	$Referer = urlencode(IO::strValue('Referer'));
	$Sms     = IO::intValue('Sms');
	$Step    = IO::intValue('Step');


	$objDb->execute("BEGIN");

	$ReportId       = IO::intValue("Report");
	$Pos            = IO::strValue("PO");
	$Style          = IO::intValue("Style");
	$iPos           = @explode(",", $Pos);
	$MaxDefects     = IO::floatValue("MaxDefects");
	$TotalGmts      = IO::floatValue("TotalGmts");
	$PoId           = $iPos[0];
	$sAdditionalPos = "";
	$fDhu           = 0;

	for ($i = 1; $i < count($iPos); $i ++)
		$sAdditionalPos .= ((($i > 1) ? "," : "").$iPos[$i]);

	if ($Style == 0)
		$Style = getDbValue("style_id", "tbl_po_colors", "po_id='$PoId'");

	$iBrand = getDbValue("brand_id", "tbl_styles", "id='$Style'");


	if ($MaxDefects == 0 && $Style > 0 && $TotalGmts > 0)
	{
		$fAql = getDbValue("aql", "tbl_brands", "id='$iBrand'");
		$fAql = (($fAql == 0) ? 2.5 : $fAql);

		if (@isset($iAqlChart["{$TotalGmts}"]["{$fAql}"]))
			$MaxDefects = $iAqlChart["{$TotalGmts}"]["{$fAql}"];
	}


	// Greige Fabric
	if ($ReportId == 6)
		@include($sBaseDir."includes/quonda/save-gf-report.php");

	// Adidas / Reebok
	else if ($ReportId == 7)
		@include($sBaseDir."includes/quonda/save-ar-report.php");

	// Adidas / Reebok ---------- New
	else if ($ReportId == 19)
		@include($sBaseDir."includes/quonda/save-adidas-report.php");

	// Yarn
	else if ($ReportId == 9)
		@include($sBaseDir."includes/quonda/save-yarn-report.php");

	// Jako
	else if ($ReportId == 10)
		@include($sBaseDir."includes/quonda/save-jako-report.php");

	// M&S
	else if ($ReportId == 11)
		@include($sBaseDir."includes/quonda/save-ms-report.php");

	// MGF
	else if ($ReportId == 14)
		@include($sBaseDir."includes/quonda/save-mgf-report.php");

	// KIK
	else if ($ReportId == 20 || $ReportId == 23)
		@include($sBaseDir."includes/quonda/save-kik-report.php");

	// Knits
	else
		@include($sBaseDir."includes/quonda/save-knits-report.php");


	if ($bFlag == true)
	{
		$sSQL = "SELECT audit_date, audit_code FROM tbl_qa_reports WHERE id='$Id'";
		$objDb->query($sSQL);

		$sAuditCode = $objDb->getField(0, "audit_code");
		$sAuditDate = $objDb->getField(0, "audit_date");


		@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

		@mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear), 0777);
		@mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth), 0777);
		@mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);

		$sQuondaDir = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");


		$iCount = IO::intValue("Count");

		for ($i = 0; $i < $iCount; $i ++)
		{
			$Code  = IO::intValue("Code".$i);
			$Area  = IO::intValue("Area".$i);
			$Roll  = IO::intValue("Roll".$i);
			$Panel = IO::intValue("Panel".$i);


			if ($_FILES["Picture{$i}"]['name'] != "" && $Code > 0)
			{
				$sDefectCode = getDbValue("code", "tbl_defect_codes", "id='$Code'");
				$sDefectArea = str_pad($Area, 2, '0', STR_PAD_LEFT);

				$sExtension  = substr($_FILES["Picture{$i}"]['name'], strrpos($_FILES["Picture{$i}"]['name'], "."));
				$sDefectPic  = ("{$sAuditCode}_{$sDefectCode}_".(($sDefectArea != "00") ? "{$sDefectArea}_" : "{$Roll}_{$Panel}_").rand(1, 9999).$sExtension);

				if (@file_exists($sQuondaDir.$sDefectPic))
					$sDefectPic = ("{$sAuditCode}_{$sDefectCode}_".(($sDefectArea != "00") ? "{$sDefectArea}_" : "{$Roll}_{$Panel}_").rand(1, 9999).$sExtension);


				if (@move_uploaded_file($_FILES["Picture{$i}"]['tmp_name'], ($sBaseDir.TEMP_DIR.$sDefectPic)))
				{
					@list($iWidth, $iHeight) = @getimagesize($sBaseDir.TEMP_DIR.$sDefectPic);


					$bResize = false;

					if ($iWidth > $iHeight && $iWidth > 800)
					{
						$bResize = true;
						$fRatio  = (800 / $iWidth);

						$iWidth  = 800;
						$iHeight = @ceil($fRatio * $iHeight);
					}

					else if ($iWidth < $iHeight && $iHeight > 800)
					{
						$bResize = true;
						$fRatio  = (800 / $iHeight);

						$iWidth  = @ceil($fRatio * $iWidth);
						$iHeight = 800;
					}


					if ($bResize == true)
						makeImage(($sBaseDir.TEMP_DIR.$sDefectPic), ($sQuondaDir.$sDefectPic), $iWidth, $iHeight);

					else
						@copy(($sBaseDir.TEMP_DIR.$sDefectPic), ($sQuondaDir.$sDefectPic));


					@unlink($sBaseDir.TEMP_DIR.$sDefectPic);
				}
			}
		}
	}


	if ($bFlag == true)
	{
		$sSQL  = "UPDATE tbl_qa_reports SET dhu='$fDhu' WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

/*
	if ($bFlag == true && (IO::strValue("AuditStage") == "F" || IO::strValue("AuditResult") == "P" || IO::strValue("AuditResult") == "A" || IO::strValue("AuditResult") == "B"))
	{
		$sSQL  = ("UPDATE tbl_qa_reports SET status='".IO::strValue("AuditResult")."' WHERE id='$Id' AND status=''");
		$bFlag = $objDb->execute($sSQL);
	}
*/

	if ($bFlag == true)
	{
		$_SESSION['Flag'] = "QA_REPORT_SAVED";


		$sSQL = "UPDATE tbl_qa_reports SET style_id='$Style' WHERE id='$Id'";
		$objDb->execute($sSQL);

		if (getDbValue("brand_id", "tbl_qa_reports", "id='$Id'") == 0)
		{
			$iSubBrand = getDbValue("sub_brand_id", "tbl_styles", "id='$Style'");

			$sSQL = "UPDATE tbl_qa_reports SET brand_id='$iSubBrand' WHERE id='$Id'";
			$objDb->execute($sSQL);
		}


		// Updating FAD in VSR
		$sSQL  = "SELECT audit_date, po_id, additional_pos FROM tbl_qa_reports WHERE id='$Id' AND audit_stage='F' AND audit_result IN ('P','A','B')";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$sDate       = $objDb->getField(0, 0);
			$sPos        = $objDb->getField(0, 1);
			$sAdditional = $objDb->getField(0, 2);

			if ($sAdditional != "")
				$sPos .= (",".$sAdditional);

			$sSQL = "UPDATE tbl_vsr SET final_audit_date='$sDate' WHERE po_id IN ($sPos)";
			$objDb->execute($sSQL);
		}

		$objDb->execute("COMMIT");
	}

	else
	{
		$objDb->execute("ROLLBACK");

		if ($_SESSION['Flag'] != "")
			$_SESSION['Flag'] = "DB_ERROR";

		backToForm( );
	}


	if ($_SESSION['Flag'] == "QA_REPORT_SAVED")
	{
		if ($Step == 1)
			redirect("edit-qa-report.php?Id={$Id}&Sms=1&Step=2&Referer={$Referer}");

		else
		{
			if ($Sms == 1)
				redirect("send-qa-report-notifications.php?Id={$Id}&Referer={$Referer}");

			else
				redirect("edit-qa-report.php?Id={$Id}&Referer={$Referer}");
		}
	}


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>