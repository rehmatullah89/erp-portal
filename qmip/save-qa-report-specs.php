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


	$sSpecsSheet1     = "";
	$sSpecsSheet2     = "";
	$sSpecsSheet3     = "";
	$sSpecsSheet4     = "";
	$sSpecsSheet5     = "";
	$sSpecsSheet6     = "";
	$sSpecsSheet7     = "";
	$sSpecsSheet8     = "";
	$sSpecsSheet9     = "";
	$sSpecsSheet10    = "";
	$sSpecsSheet1Sql  = "";
	$sSpecsSheet1Sq2  = "";
	$sSpecsSheet1Sq3  = "";
	$sSpecsSheet1Sq4  = "";
	$sSpecsSheet1Sq5  = "";
	$sSpecsSheet1Sq6  = "";
	$sSpecsSheet1Sq7  = "";
	$sSpecsSheet1Sq8  = "";
	$sSpecsSheet1Sq9  = "";
	$sSpecsSheet1Sq10 = "";

	if ($_FILES['SpecsSheet1']['name'] != "")
	{
		$sSpecsSheet1 = ($Id."-1-".IO::getFileName($_FILES['SpecsSheet1']['name']));

		if (@move_uploaded_file($_FILES['SpecsSheet1']['tmp_name'], ($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet1)))
			$sSpecsSheet1Sql = ", specs_sheet_1='$sSpecsSheet1'";

		else
			$sSpecsSheet1 = "";
	}

	if ($_FILES['SpecsSheet2']['name'] != "")
	{
		$sSpecsSheet2 = ($Id."-2-".IO::getFileName($_FILES['SpecsSheet2']['name']));

		if (@move_uploaded_file($_FILES['SpecsSheet2']['tmp_name'], ($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet2)))
			$sSpecsSheet2Sql = ", specs_sheet_2='$sSpecsSheet2'";

		else
			$sSpecsSheet2 = "";
	}

	if ($_FILES['SpecsSheet3']['name'] != "")
	{
		$sSpecsSheet3 = ($Id."-3-".IO::getFileName($_FILES['SpecsSheet3']['name']));

		if (@move_uploaded_file($_FILES['SpecsSheet3']['tmp_name'], ($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet3)))
			$sSpecsSheet3Sql = ", specs_sheet_3='$sSpecsSheet3'";

		else
			$sSpecsSheet3 = "";
	}

	if ($_FILES['SpecsSheet4']['name'] != "")
	{
		$sSpecsSheet4 = ($Id."-4-".IO::getFileName($_FILES['SpecsSheet4']['name']));

		if (@move_uploaded_file($_FILES['SpecsSheet4']['tmp_name'], ($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet4)))
			$sSpecsSheet4Sql = ", specs_sheet_4='$sSpecsSheet4'";

		else
			$sSpecsSheet4 = "";
	}

	if ($_FILES['SpecsSheet5']['name'] != "")
	{
		$sSpecsSheet5 = ($Id."-5-".IO::getFileName($_FILES['SpecsSheet5']['name']));

		if (@move_uploaded_file($_FILES['SpecsSheet5']['tmp_name'], ($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet5)))
			$sSpecsSheet5Sql = ", specs_sheet_5='$sSpecsSheet5'";

		else
			$sSpecsSheet5 = "";
	}

	if ($_FILES['SpecsSheet6']['name'] != "")
	{
		$sSpecsSheet6 = ($Id."-6-".IO::getFileName($_FILES['SpecsSheet6']['name']));

		if (@move_uploaded_file($_FILES['SpecsSheet6']['tmp_name'], ($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet6)))
			$sSpecsSheet6Sql = ", specs_sheet_6='$sSpecsSheet6'";

		else
			$sSpecsSheet6 = "";
	}

	if ($_FILES['SpecsSheet7']['name'] != "")
	{
		$sSpecsSheet7 = ($Id."-7-".IO::getFileName($_FILES['SpecsSheet7']['name']));

		if (@move_uploaded_file($_FILES['SpecsSheet7']['tmp_name'], ($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet7)))
			$sSpecsSheet7Sql = ", specs_sheet_7='$sSpecsSheet7'";

		else
			$sSpecsSheet7 = "";
	}

	if ($_FILES['SpecsSheet8']['name'] != "")
	{
		$sSpecsSheet8 = ($Id."-8-".IO::getFileName($_FILES['SpecsSheet8']['name']));

		if (@move_uploaded_file($_FILES['SpecsSheet8']['tmp_name'], ($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet8)))
			$sSpecsSheet8Sql = ", specs_sheet_8='$sSpecsSheet8'";

		else
			$sSpecsSheet8 = "";
	}

	if ($_FILES['SpecsSheet9']['name'] != "")
	{
		$sSpecsSheet9 = ($Id."-9-".IO::getFileName($_FILES['SpecsSheet9']['name']));

		if (@move_uploaded_file($_FILES['SpecsSheet9']['tmp_name'], ($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet9)))
			$sSpecsSheet9Sql = ", specs_sheet_9='$sSpecsSheet9'";

		else
			$sSpecsSheet9 = "";
	}

	if ($_FILES['SpecsSheet10']['name'] != "")
	{
		$sSpecsSheet10 = ($Id."-10-".IO::getFileName($_FILES['SpecsSheet10']['name']));

		if (@move_uploaded_file($_FILES['SpecsSheet10']['tmp_name'], ($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet10)))
			$sSpecsSheet10Sql = ", specs_sheet_10='$sSpecsSheet10'";

		else
			$sSpecsSheet10 = "";
	}


	$sSQL = "UPDATE tbl_qa_reports SET approved='Y' $sSpecsSheet1Sql $sSpecsSheet2Sql $sSpecsSheet3Sql $sSpecsSheet4Sql $sSpecsSheet5Sql $sSpecsSheet6Sql $sSpecsSheet7Sql $sSpecsSheet8Sql $sSpecsSheet9Sql $sSpecsSheet10Sql WHERE id='$Id'";

	if ($objDb->execute($sSQL) == true)
	{
		$_SESSION['Flag'] = "REPORT_SAVED";


		if ($sSpecsSheet1 != "" && IO::strValue("OldSpecsSheet1") != "" && $sSpecsSheet1 != IO::strValue("OldSpecsSheet1"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.IO::strValue("OldSpecsSheet1"));

		if ($sSpecsSheet2 != "" && IO::strValue("OldSpecsSheet2") != "" && $sSpecsSheet2 != IO::strValue("OldSpecsSheet2"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.IO::strValue("OldSpecsSheet2"));

		if ($sSpecsSheet3 != "" && IO::strValue("OldSpecsSheet3") != "" && $sSpecsSheet3 != IO::strValue("OldSpecsSheet3"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.IO::strValue("OldSpecsSheet3"));

		if ($sSpecsSheet4 != "" && IO::strValue("OldSpecsSheet4") != "" && $sSpecsSheet4 != IO::strValue("OldSpecsSheet4"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.IO::strValue("OldSpecsSheet4"));

		if ($sSpecsSheet5 != "" && IO::strValue("OldSpecsSheet5") != "" && $sSpecsSheet5 != IO::strValue("OldSpecsSheet5"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.IO::strValue("OldSpecsSheet5"));

		if ($sSpecsSheet6 != "" && IO::strValue("OldSpecsSheet6") != "" && $sSpecsSheet6 != IO::strValue("OldSpecsSheet6"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.IO::strValue("OldSpecsSheet6"));

		if ($sSpecsSheet7 != "" && IO::strValue("OldSpecsSheet7") != "" && $sSpecsSheet7 != IO::strValue("OldSpecsSheet7"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.IO::strValue("OldSpecsSheet7"));

		if ($sSpecsSheet8 != "" && IO::strValue("OldSpecsSheet8") != "" && $sSpecsSheet8 != IO::strValue("OldSpecsSheet8"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.IO::strValue("OldSpecsSheet8"));

		if ($sSpecsSheet9 != "" && IO::strValue("OldSpecsSheet9") != "" && $sSpecsSheet9 != IO::strValue("OldSpecsSheet9"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.IO::strValue("OldSpecsSheet9"));

		if ($sSpecsSheet10 != "" && IO::strValue("OldSpecsSheet10") != "" && $sSpecsSheet10 != IO::strValue("OldSpecsSheet10"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.IO::strValue("OldSpecsSheet10"));
	}

	else
	{
		if ($sSpecsSheet1 != "" && $sSpecsSheet1 != IO::strValue("OldSpecsSheet1"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet1);

		if ($sSpecsSheet2 != "" && $sSpecsSheet2 != IO::strValue("OldSpecsSheet2"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet2);

		if ($sSpecsSheet3 != "" && $sSpecsSheet3 != IO::strValue("OldSpecsSheet3"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet3);

		if ($sSpecsSheet4 != "" && $sSpecsSheet4 != IO::strValue("OldSpecsSheet4"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet4);

		if ($sSpecsSheet5 != "" && $sSpecsSheet5 != IO::strValue("OldSpecsSheet5"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet5);

		if ($sSpecsSheet6 != "" && $sSpecsSheet6 != IO::strValue("OldSpecsSheet6"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet6);

		if ($sSpecsSheet7 != "" && $sSpecsSheet7 != IO::strValue("OldSpecsSheet7"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet7);

		if ($sSpecsSheet8 != "" && $sSpecsSheet8 != IO::strValue("OldSpecsSheet8"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet8);

		if ($sSpecsSheet9 != "" && $sSpecsSheet9 != IO::strValue("OldSpecsSheet9"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet9);

		if ($sSpecsSheet10 != "" && $sSpecsSheet10 != IO::strValue("OldSpecsSheet10"))
			@unlink($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet10);


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