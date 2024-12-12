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

	$objSms = new Sms( );


	$sReportFile = "";

	if (getDbValue("COUNT(*)", "tbl_notifications", "trigger_id='6' AND vendor_id='0' AND (brand_id='0' OR brand_id='$iBrandId') AND status='A'") > 0)
	{
		if ($iBrandId == 124)
		{
			$sReportFile = ($sBaseDir.TEMP_DIR."M".str_pad($Id, 6, '0', STR_PAD_LEFT)."-Report.pdf");

			@include($sBaseDir."sampling/export-ms-report.php");
		}

		else
		{
			$sReportFile = ($sBaseDir.TEMP_DIR."M".str_pad($Id, 6, '0', STR_PAD_LEFT)."-Report.xlsx");

			@include($sBaseDir."reports/export-measurements-report.php");
		}
	}



	$sSQL = "SELECT user_id, alert_types FROM tbl_notifications WHERE trigger_id='6' AND vendor_id='0' AND (brand_id='0' OR brand_id='$iBrandId') AND status='A' AND user_id IN (SELECT id FROM tbl_users WHERE status='A')";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iUserId     = $objDb->getField($i, 0);
		$iAlertTypes = @explode(",", $objDb->getField($i, 1));



		$sSQL = "SELECT name, email, mobile FROM tbl_users WHERE id='$iUserId'";
		$objDb2->query($sSQL);

		$sName   = $objDb2->getField(0, 'name');
		$sEmail  = $objDb2->getField(0, 'email');
		$sMobile = $objDb2->getField(0, 'mobile');


		$sStyle = getDbValue("style", "tbl_styles", "id='$iStyleId'");


		$sSubject = "*** Sampling Audit (Style: {$sStyle}) ***";
		$sBody    = ("Audit Code: ".("M".str_pad($Id, 6, '0', STR_PAD_LEFT)));
		$sBody   .= ("\r\nBrand: ".getDbValue("brand", "tbl_brands", "id='$iBrandId'"));
		$sBody   .= ("\r\nStyle: ".$sStyle);
		$sBody   .= ("\r\nType: ".getDbValue("type", "tbl_sampling_types", "id=(SELECT sample_type_id FROM tbl_merchandisings WHERE id='$Id')"));
		$sBody   .= ("\r\nSeason: ".getDbValue("season", "tbl_seasons", "id=(SELECT sub_season_id FROM tbl_styles WHERE id='$iStyleId')"));
		$sBody   .= ("\r\nStatus: ".((IO::strValue("Status") == "A") ? "Accepted" : "Rejected"));


		// SMS
		if (@in_array(1, $iAlertTypes))
			$objSms->send($sMobile, $sName, $sBody, $sSubject);

		// Email
		if (@in_array(2, $iAlertTypes))
		{
			$objEmail = new PHPMailer( );
			$objEmail->IsSMTP( );
			$objEmail->SMTPAuth = true;
			$objEmail->IsHTML(false);
			$objEmail->Subject = $sSubject;
			$objEmail->Body = $sBody;
			$objEmail->AddAddress($sEmail, $sName);
			$objEmail->AddAttachment($sReportFile, @basename($sReportFile));
			$objEmail->Send( );
		}

		// Portal
		if (@in_array(3, $iAlertTypes))
		{
			$iId = getNextId("tbl_user_notifications");

			$sSQL = "INSERT INTO tbl_user_notifications (id, user_id, subject, body, date_time, status) VALUES ('$iId', '$iUserId', '$sSubject', '$sBody', NOW( ), 'N')";
			$objDb3->execute($sSQL);
		}
	}


	if ($sReportFile != "")
		@unlink($sReportFile);


	$objSms->close( );
?>