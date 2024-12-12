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
	$objDb2      = new Database( );


	$User      = IO::strValue('User');
	$AuditCode = IO::strValue("AuditCode");


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else if ((int)getDbValue("COUNT(1)", "tbl_qa_reports", "audit_code='$AuditCode'") == 0)
			$aResponse["Message"] = "Invalid Request, The selected Audit Code has been Deleted.";

		else
		{
			$iUser = $objDb->getField(0, "id");
			$sName = $objDb->getField(0, "name");


			$iAuditCode = (int)substr($AuditCode, 1);
			$iReportId  = getDbValue("report_id", "tbl_qa_reports", "id='$iAuditCode'");


			$bFlag = $objDb->execute("BEGIN", true, $iUser, $sName);
			
			if ($iReportId == 25)
			{
				if (getDbValue("COUNT(1)", "tbl_bbg_reports", "audit_id='$iAuditCode'") == 0)
					$sSQL = ("INSERT INTO tbl_bbg_reports (audit_id, measurement_result, measurement_overall_remarks, measurement_wash_status) VALUES ('$iAuditCode', '".IO::strValue("Result")."', '".IO::strValue("Remarks")."', '".IO::strValue("Wash")."')");

				else
					$sSQL = ("UPDATE tbl_bbg_reports SET measurement_result='".IO::strValue("Result")."', measurement_overall_remarks='".IO::strValue("Remarks")."', measurement_wash_status='".IO::strValue("Wash")."' WHERE audit_id='$iAuditCode'");

				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			else if ($iReportId == 20 || $iReportId == 23)
			{
				if (getDbValue("COUNT(1)", "tbl_kik_inspection_summary", "audit_id='$iAuditCode'") == 0)
					$sSQL = ("INSERT INTO tbl_kik_inspection_summary (audit_id, measurement_result, measurement_overall_remarks) VALUES ('$iAuditCode', '".IO::strValue("Result")."', '".IO::strValue("Remarks")."')");

				else
					$sSQL = ("UPDATE tbl_kik_inspection_summary SET measurement_result='".IO::strValue("Result")."', measurement_overall_remarks='".IO::strValue("Remarks")."' WHERE audit_id='$iAuditCode'");

				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}
			
			else if ($iReportId == 32)
			{
				if (getDbValue("COUNT(1)", "tbl_arcadia_inspection_summary", "audit_id='$iAuditCode'") == 0)
					$sSQL = ("INSERT INTO tbl_arcadia_inspection_summary (audit_id, measurement_result, measurement_overall_remarks) VALUES ('$iAuditCode', '".IO::strValue("Result")."', '".IO::strValue("Remarks")."')");

				else
					$sSQL = ("UPDATE tbl_arcadia_inspection_summary SET measurement_result='".IO::strValue("Result")."', measurement_overall_remarks='".IO::strValue("Remarks")."' WHERE audit_id='$iAuditCode'");

				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}
			
			else if ($iReportId == 31)
			{
				if (getDbValue("COUNT(1)", "tbl_hybrid_apparel_reports", "audit_id='$iAuditCode'") == 0)
					$sSQL = ("INSERT INTO tbl_hybrid_apparel_reports (audit_id, measurement_result, measurement_remarks) VALUES ('$iAuditCode', '".IO::strValue("Result")."', '".IO::strValue("Remarks")."')");

				else
					$sSQL = ("UPDATE tbl_hybrid_apparel_reports SET measurement_result='".IO::strValue("Result")."', measurement_remarks='".IO::strValue("Remarks")."' WHERE audit_id='$iAuditCode'");

				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}			
			
			if ($bFlag == true)
			{
				$objDb->execute("COMMIT", true, $iUser, $sName);

				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "Measurement Result Saved Successfully!";
			}

			else
			{
				$objDb->execute("ROLLBACK", true, $iUser, $sName);

				$aResponse["Message"] = "An ERROR occured, please try again.";
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