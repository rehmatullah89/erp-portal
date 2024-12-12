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

	@session_start( );

	@ini_set('display_errors', 0);
	@ini_set('log_errors', 0);
	@error_reporting(0);

	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);



	$sBaseDir = "C:/wamp/www/portal/";

	@require_once($sBaseDir."requires/configs.php");
	@require_once($sBaseDir."requires/db.class.php");
	@require_once($sBaseDir."requires/common-functions.php");
	@require_once($sBaseDir."requires/image-functions.php");
//	@require_once($sBaseDir."requires/PHPMailer/class.phpmailer.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	// Quonda Pics
	$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR."*.*");

	for ($i = 0; $i < count($sPictures); $i ++)
	{
		$sName  = @strtoupper($sPictures[$i]);
		$sName  = @basename($sName);
		$sParts = @explode("_", $sName);

		$sAuditCode = $sParts[0];

		if ($sAuditCode{0} != "S")
			$sAuditCode = ("S".$sAuditCode);

		$iAuditCode = substr($sAuditCode, 1);

//		print "{$sPictures[$i]}\r\n";


		if (@strpos($sName, "-LAB-") !== FALSE || @strpos($sName, "_LAB_") !== FALSE)
		{
			$sSpecsSheetSql = "";


			if (@copy($sPictures[$i], ($sBaseDir.SPECS_SHEETS_DIR.$sName)))
			{
				$sSQL = "SELECT * FROM tbl_qa_reports WHERE id='$iAuditCode'";
				$objDb->query($sSQL);

				if ($objDb->getCount( ) == 1)
				{
					$SpecsSheet1  = $objDb->getField(0, 'specs_sheet_1');
					$SpecsSheet2  = $objDb->getField(0, 'specs_sheet_2');
					$SpecsSheet3  = $objDb->getField(0, 'specs_sheet_3');
					$SpecsSheet4  = $objDb->getField(0, 'specs_sheet_4');
					$SpecsSheet5  = $objDb->getField(0, 'specs_sheet_5');
					$SpecsSheet6  = $objDb->getField(0, 'specs_sheet_6');
					$SpecsSheet7  = $objDb->getField(0, 'specs_sheet_7');
					$SpecsSheet8  = $objDb->getField(0, 'specs_sheet_8');
					$SpecsSheet9  = $objDb->getField(0, 'specs_sheet_9');
					$SpecsSheet10 = $objDb->getField(0, 'specs_sheet_10');


					if ($SpecsSheet1 == "")
						$sSpecsSheetSql = " specs_sheet_1='$sName'";

					else if ($SpecsSheet2 == "")
						$sSpecsSheetSql = " specs_sheet_2='$sName'";

					else if ($SpecsSheet3 == "")
						$sSpecsSheetSql = " specs_sheet_3='$sName'";

					else if ($SpecsSheet4 == "")
						$sSpecsSheetSql = " specs_sheet_4='$sName'";

					else if ($SpecsSheet5 == "")
						$sSpecsSheetSql = " specs_sheet_5='$sName'";

					else if ($SpecsSheet6 == "")
						$sSpecsSheetSql = " specs_sheet_6='$sName'";

					else if ($SpecsSheet7 == "")
						$sSpecsSheetSql = " specs_sheet_7='$sName'";

					else if ($SpecsSheet8 == "")
						$sSpecsSheetSql = " specs_sheet_8='$sName'";

					else if ($SpecsSheet9 == "")
						$sSpecsSheetSql = " specs_sheet_9='$sName'";

					else if ($SpecsSheet10 == "")
						$sSpecsSheetSql = " specs_sheet_10='$sName'";


					if ($sSpecsSheetSql != "")
					{
						$sSQL = "UPDATE tbl_qa_reports SET $sSpecsSheetSql WHERE id='$iAuditCode'";
						$objDb->execute($sSQL);
					}
				}

				//print "File Copied : {$sPictures[$i]}     &nbsp; -to- &nbsp;    $sFile\r\n\r\n";
			}


			if ($sSpecsSheetSql != "")
			{
				@unlink($sPictures[$i]);

				continue;
			}
		}



		$sSQL = "SELECT audit_date FROM tbl_qa_reports WHERE audit_code='$sAuditCode' LIMIT 1";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$sAuditDate = $objDb->getField(0, 0);

			//print "$sAuditCode - $sAuditDate\r\n";


			@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

			@mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear), 0777);
			@mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth), 0777);
			@mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);


			$sFile = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPictures[$i]));

			if (@copy($sPictures[$i], $sFile))
			{
				@unlink($sPictures[$i]);

				//print "File Copied : {$sPictures[$i]}     &nbsp; -to- &nbsp;    $sFile\r\n\r\n";
			}

			else
			{
				//print "Error Copying File : {$sPictures[$i]}\r\n\r\rn";
			}
		}
	}


/*
		$objEmail = new PHPMailer( );

		$objEmail->Subject  = ("[".date("d-M-Y H:i")."] Alert");

		$objEmail->MsgHTML($sSQL);
		$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
		$objEmail->Send( );

		print "#".$objEmail->ErrorInfo."#";
*/


	// Sampling Pics
	$sPictures = @glob($sBaseDir.SAMPLING_PICS_DIR."*.*");

	for ($i = 0; $i < count($sPictures); $i ++)
	{
		$sName  = @strtoupper($sPictures[$i]);
		$sName  = @basename($sName);
		$sParts = @explode("_", $sName);

		$sAuditCode = $sParts[0];

		if ($sAuditCode{0} != "M")
			$sAuditCode = ("M".$sAuditCode);

		$iAuditCode = substr($sAuditCode, 1);

		//print "{$sPictures[$i]}\r\n";


		$sSQL = "SELECT created FROM tbl_merchandisings WHERE id='$iAuditCode' LIMIT 1";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$sAuditDate = substr($objDb->getField(0, 0), 0, 10);

			//print "$sAuditCode - $sAuditDate\r\n";


			@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

			@mkdir(($sBaseDir.SAMPLING_PICS_DIR.$sYear), 0777);
			@mkdir(($sBaseDir.SAMPLING_PICS_DIR.$sYear."/".$sMonth), 0777);
			@mkdir(($sBaseDir.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);
			@mkdir(($sBaseDir.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/originals"), 0777);
			@mkdir(($sBaseDir.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/22x22"), 0777);


			$sFile = ($sBaseDir.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPictures[$i]));


			@list($iWidth, $iHeight) = resizeImage($sPictures[$i], 600);

			makeImage($sPictures[$i], $sFile, $iWidth, $iHeight);

			if (@file_exists($sFile))
			{
				$sFile = ($sBaseDir.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/22x22/".@basename($sPictures[$i]));
				createImage($sPictures[$i], $sFile, 22, 22);

				$sFile = ($sBaseDir.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/originals/".@basename($sPictures[$i]));
				@copy($sPictures[$i], $sFile);

				@unlink($sPictures[$i]);

				//print "File Copied : {$sPictures[$i]}     &nbsp; -to- &nbsp;    $sFile\r\n\r\n";
			}

			else
			{
				//print "Error Copying File : {$sPictures[$i]}\r\n\r\rn";
			}
		}
	}

/*
		$objEmail = new PHPMailer( );

		$objEmail->Subject  = "Quonda Pics";

		$objEmail->MsgHTML(date("Y-m-d H:i:s A");
		$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
		$objEmail->Send( );
*/

	$objDb->close( );
	$objDbGlobal->close( );
?>