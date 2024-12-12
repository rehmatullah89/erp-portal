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

	$sAuditDate      = getDbValue("audit_date", "tbl_qa_reports", "id='$Id'");
	$sSpecsSheets    = array( );
	$sSpecsSheetsSql = "";
	
	@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
	
	
	function resizeSpecsSheet($sSpecsSheet)
	{
		global $sBaseDir;
		global $sYear;
		global $sMonth;
		global $sDay;
		

		@mkdir(($sBaseDir.SPECS_SHEETS_DIR.$sYear."/"), 0777);
		@mkdir(($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/"), 0777);
		@mkdir(($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/"), 0777);

			
		$sExtension = strtolower(@substr($sSpecsSheet, @strrpos($sSpecsSheet, '.')));

		
		if (!@in_array($sExtension, array(".jpg", ".jpeg", ".png", ".gif")))
			@copy(($sBaseDir.TEMP_DIR.$sSpecsSheet), ($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsSheet));
			
		else
		{
			@list($iWidth, $iHeight) = @getimagesize($sBaseDir.TEMP_DIR.$sSpecsSheet);


			$bResize = false;

			if ($iWidth > $iHeight && $iWidth > 1200)
			{
				$bResize = true;
				$fRatio  = (1200 / $iWidth);

				$iWidth  = 1200;
				$iHeight = @ceil($fRatio * $iHeight);
			}

			else if ($iWidth < $iHeight && $iHeight > 1200)
			{
				$bResize = true;
				$fRatio  = (1200 / $iHeight);

				$iWidth  = @ceil($fRatio * $iWidth);
				$iHeight = 1200;
			}

			
			if ($bResize == true)
				makeImage(($sBaseDir.TEMP_DIR.$sSpecsSheet), ($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsSheet), $iWidth, $iHeight);

			else
				@copy(($sBaseDir.TEMP_DIR.$sSpecsSheet), ($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsSheet));
		}
	}
	

	for ($i = 1; $i <= 10; $i ++)
	{
		$sSpecsSheets[$i] = "";
		
		if ($_FILES["SpecsSheet{$i}"]['name'] != "")
		{
			$sSpecsSheet = ($Id."-{$i}-".IO::getFileName($_FILES["SpecsSheet{$i}"]['name']));

			if (@move_uploaded_file($_FILES["SpecsSheet{$i}"]['tmp_name'], ($sBaseDir.TEMP_DIR.$sSpecsSheet)))
			{
				resizeSpecsSheet($sSpecsSheet);

				
				$sSpecsSheetsSql .= ", specs_sheet_{$i}='$sSpecsSheet'";
				
				$sSpecsSheets[$i] = $sSpecsSheet;
			}
		}
	}
	


	$sSQL = "UPDATE tbl_qa_reports SET approved='Y' $sSpecsSheetsSql WHERE id='$Id'";

	if ($objDb->execute($sSQL) == true)
	{
		$_SESSION['Flag'] = "REPORT_SAVED";

		for ($i = 1; $i <= 10; $i ++)
		{
			if ($sSpecsSheets[$i] != "" && IO::strValue("OldSpecsSheet{$i}") != "" && $sSpecsSheets[$i] != IO::strValue("OldSpecsSheet{$i}"))
			{
				@unlink($sBaseDir.SPECS_SHEETS_DIR.IO::strValue("OldSpecsSheet{$i}"));
				@unlink($sBaseDir.SPECS_SHEETS_DIR."thumbs/".IO::strValue("OldSpecsSheet{$i}"));
				@unlink($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".IO::strValue("OldSpecsSheet{$i}"));
				@unlink($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/thumbs/".IO::strValue("OldSpecsSheet{$i}"));
			}
		}
	}

	else
	{
		for ($i = 1; $i <= 10; $i ++)
		{
			if ($sSpecsSheets[$i] != "" && $sSpecsSheets[$i] != IO::strValue("OldSpecsSheet{$i}"))
			{
				@unlink($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheets[$i]);
				@unlink($sBaseDir.SPECS_SHEETS_DIR."thumbs/".$sSpecsSheets[$i]);
				@unlink($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsSheets[$i]);
				@unlink($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/thumbs/".$sSpecsSheets[$i]);
			}
		}

		$_SESSION['Flag'] = "DB_ERROR";
	}


	if ($Step == 3 || $Sms == 1)
		redirect("send-qa-report-notifications.php?Id={$Id}&Referer={$Referer}");

	else
		redirect("edit-qa-report.php?Id={$Id}&Referer={$Referer}", "SPECS_SHEETS_SAVED");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>