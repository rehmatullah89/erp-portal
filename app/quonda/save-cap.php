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
	$objDb2      = new Database( );


	$User      = IO::strValue('User');
	$AuditCode = IO::strValue("AuditCode");
	$Others    = IO::strValue("Others");
	$Cap       = stripslashes(IO::strValue("Cap"));


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S")
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
			$iReportId  = getDbValue("report_id", "tbl_qa_reports", "id='$iAuditCode'");
			$sCap       = json_decode($Cap, true);


			$bFlag = $objDb->execute("BEGIN", true, $iUser, $sName);


			if ($iReportId == 14 || $iReportId == 34)
			{
				if (getDbValue("COUNT(1)", "tbl_mgf_reports", "audit_id='$iAuditCode'") == 1)
					$sSQL  = "UPDATE tbl_mgf_reports SET cap_others='$Others' WHERE audit_id='$iAuditCode'";

				else
					$sSQL  = "INSERT INTO tbl_mgf_reports SET audit_id   = '$iAuditCode',
															  cap_others = '$Others'";

				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				foreach ($sCap as $sDefect)
				{
					$sDefectCap     = addslashes($sDefect['Cap']);
					$sDefectRemarks = addslashes($sDefect['Remarks']);

					
					if (strpos($sDefect['Id'], "-") !== FALSE)
						$sSQL  = "UPDATE tbl_qa_report_defects SET cap='$sDefectCap', remarks='$sDefectRemarks' WHERE date_time='{$sDefect['Id']}' AND audit_id='$iAuditCode'";

					else
						$sSQL  = "UPDATE tbl_qa_report_defects SET cap='$sDefectCap', remarks='$sDefectRemarks' WHERE id='{$sDefect['Id']}' AND audit_id='$iAuditCode'";

					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);

					if ($bFlag == false)
						break;
				}
			}


			if ($bFlag == true)
			{
				$objDb->execute("COMMIT", true, $iUser, $sName);

				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "Corrective Action Plan Saved Successfully!";
			}

			else
			{
				$aResponse["Message"] = "An ERROR occured, please try again.";

				$objDb->execute("ROLLBACK", true, $iUser, $sName);
			}
		}
	}

	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = $Cap."\n\n".@json_encode($aResponse)."<bR>".$sSQL;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>