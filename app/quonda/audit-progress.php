<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree QUONDA App                                                                   **
	**  Version 3.0                                                                              **
	**                                                                                           **
	**  http://app.3-tree.com                                                                    **
	**                                                                                           **
	**  Copyright 2008-17 (C) Triple Tree                                                        **
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
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$User          = IO::strValue('User');
	$AuditCode     = IO::strValue('AuditCode');
	$ReportId      = IO::intValue('ReportId');
	$Current       = IO::intValue('Current');
	$SampleSize    = IO::intValue('SampleSize');
	$AuditFinished = IO::strValue('AuditFinished');
	$StartDateTime = IO::strValue("StartDateTime");
	$EndDateTime   = IO::strValue("EndDateTime");

	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S" || $ReportId == 0)
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else if ((int)getDbValue("COUNT(1)", "tbl_qa_reports", "audit_code='$AuditCode'") == 0)
			$aResponse["Message"] = "Invalid Request, The selected Audit Code has been Deleted.";

		else
		{
			$iUser  = $objDb->getField(0, "id");
			$sName  = $objDb->getField(0, "name");
			$sGuest = $objDb->getField(0, "guest");


			$iAuditCode = (int)substr($AuditCode, 1);
			$iDefective = 0;
			$fDr        = 0;

			if ($Current > 0)
			{
				if ($ReportId == 6)
					$iDefective = (int)getDbValue("SUM(defects)", "tbl_gf_report_defects", "audit_id='$iAuditCode'");

				else
					$iDefective = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditCode' AND nature>'0'");


				if ($SampleSize > 0)
					$fDr = @round((($iDefective / $SampleSize) * 100), 2);
			}


			$bFlag               = $objDb->execute("BEGIN", true, $iUser, $sName);
			$sAuditStartDateTime = getDbValue("start_date_time", "tbl_qa_reports", "id='$iAuditCode'");

			if ($StartDateTime != "" && ($sAuditStartDateTime == "" || $sAuditStartDateTime == "0000-00-00 00:00:00"))
			{
				$sStartTime = substr($StartDateTime, 11);

				$sSQL  = "UPDATE tbl_qa_reports SET start_time='$sStartTime', start_date_time='$StartDateTime' WHERE id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$iDefective   = (int)getDbValue("COUNT(DISTINCT(sample_no))", "tbl_qa_report_defects", "audit_id='$iAuditCode'");
				$sAuditResult = "";

				if (($SampleSize > 0 && $Current >= $SampleSize) || $AuditFinished == "Y")
				{
					$iStyle          = getDbValue("style_id", "tbl_qa_reports", "id='$iAuditCode'");
					$iBrand          = getDbValue("brand_id", "tbl_styles", "id='$iStyle'");
					$fAql            = getDbValue("aql", "tbl_brands", "id='$iBrand'");
					$fAql            = (($fAql == 0) ? 2.5 : $fAql);
					$iDefectsAllowed = 0;
					$iDefects        = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditCode'");
					$sSampleSizeSql  = "";

					if ($AuditFinished == "Y" && $SampleSize == 0)
					{
						$sSampleSizeSql .= ", total_gmts='$Current' ";
						$SampleSize      = $Current;
					}

					if (@isset($iAqlChart["{$SampleSize}"]["{$fAql}"]))
						$iDefectsAllowed = $iAqlChart["{$SampleSize}"]["{$fAql}"];


					if ($iDefects <= $iDefectsAllowed)
						$sAuditResult = "P";

					else
						$sAuditResult = "F";


					if ($EndDateTime != "")
					{
						$sEndTime = substr($EndDateTime, 11);

						// , audit_result='$sAuditResult'
						$sSQL = "UPDATE tbl_qa_reports SET checked_gmts='$Current', dhu='$fDr', defective_gmts='$iDefective', end_time='$sEndTime', end_date_time='$EndDateTime' $sSampleSizeSql WHERE audit_code='$AuditCode'";
					}

					else
					{
						// , audit_result='$sAuditResult'
						$sSQL = "UPDATE tbl_qa_reports SET checked_gmts='$Current', dhu='$fDr', defective_gmts='$iDefective', end_time=CURTIME( ), end_date_time=NOW( ) $sSampleSizeSql WHERE audit_code='$AuditCode'";
					}

					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				else
				{
					$sSQL  = "UPDATE tbl_qa_reports SET checked_gmts='$Current', defective_gmts='$iDefective', dhu='$fDr', end_time=CURTIME( ), end_date_time=NOW( ) WHERE audit_code='$AuditCode'";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}
			}

/*
			if ($bFlag == true && getDbValue("COUNT(1)", "tbl_qa_report_progress", "audit_id='$iAuditCode' AND sample_no='$Current'") == 0)
			{
				$sSQL  = "INSERT INTO tbl_qa_report_progress SET audit_id  = '$iAuditCode',
															     sample_no = '$Current',
															     date_time = NOW( )";

				$bFlag = $objDb->execute($sSQL, false, $iUser, $sName);
			}
*/

			if ($bFlag == true)
			{
				$objDb->execute("COMMIT", true, $iUser, $sName);

				$aResponse['Status']       = "OK";
				$aResponse['Dr']           = $fDr;
				$aResponse['Result']       = $sAuditResult;
				$aResponse['Measurements'] = ((getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$iAuditCode'") >= 1) ? "Y" : "N");
			}

			else
			{
				$aResponse['Message'] = mysql_error( );//"An ERROR occured, please re-try";

				$objDb->execute("ROLLBACK", true, $iUser, $sName);
			}
		}
	}


	print @json_encode($aResponse);



/*
		$objEmail = new PHPMailer( );

		$objEmail->Subject = "Alert";
		$objEmail->Body    = @json_encode($aResponse)."\n\n".$sSQL."\n\n".mysql_error();

		$objEmail->IsHTML(false);

		$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
*/


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>