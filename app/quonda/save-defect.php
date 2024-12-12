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


	$User        = IO::strValue('User');
	$AuditCode   = IO::strValue("AuditCode");
	$ReportId    = IO::intValue('ReportId');
	$DefectCode  = IO::intValue("DefectCode");
	$DefectArea  = IO::intValue("DefectArea");
	$Nature      = IO::intValue("Nature");
	$Remarks     = IO::strValue("Remarks");
	$Picture     = IO::strValue("Picture");
	$SampleNo    = IO::intValue("SampleNo");
	$SampleColor = IO::strValue("SampleColor");
	$AuditorCode = IO::intValue("AuditorCode");
	$DateTime    = IO::strValue("DateTime");
	
	if ($DefectArea == -1)
		$DefectArea = 999;


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S" || $ReportId == 0 || $SampleNo == 0 || $DefectCode == 0 || $DefectArea == 0)
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser  = $objDb->getField(0, "id");
			$sName  = $objDb->getField(0, "name");
			$sGuest = $objDb->getField(0, "guest");


			$iAuditCode = (int)substr($AuditCode, 1);
			$DateTime   = (($DateTime == "") ? date("Y-m-d H:i:s") : $DateTime);


			if ($ReportId != 6)
			{
				if (getDbValue("COUNT(1)", "tbl_qa_report_defects", "audit_id='$iAuditCode' AND sample_no='$SampleNo' AND date_time='$DateTime'") == 0)
				{
					$iId  = getNextId("tbl_qa_report_defects");


					$sSQL = "INSERT INTO tbl_qa_report_defects (id, audit_id, sample_no, code_id, defects, area_id, nature, color, remarks, picture, auditor_id, date_time)
														VALUES ('$iId', '$iAuditCode', '$SampleNo', '$DefectCode', '1', '$DefectArea', '$Nature', '$SampleColor', '$Remarks', '$Picture', '$AuditorCode', '$DateTime')";

					if ($objDb->execute($sSQL, true, $iUser, $sName) == true)
					{
//						$iDefective = getDbValue("COUNT(DISTINCT(sample_no))", "tbl_qa_report_defects", "audit_id='$iAuditCode' AND nature>'0'");
						$iDefective = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditCode' AND nature>'0'");


						$iSampleSize = getDbValue("total_gmts", "tbl_qa_reports", "id='$iAuditCode'");
						$fDr         = @round((($iDefective / $iSampleSize) * 100), 2);





						$sSQL = "UPDATE tbl_qa_reports SET defective_gmts='$iDefective', dhu='$fDr', end_date_time=NOW( ), date_time=NOW( ) WHERE id='$iAuditCode'";
						$objDb->execute($sSQL, true, $iUser, $sName);


						$aResponse['Status']  = "OK";
						$aResponse["Message"] = "Defect Saved Successfully!";
					}

					else
						$aResponse["Message"] = "An ERROR occured, please try again.";
				}

				else
				{
					$aResponse['Status']  = "OK";
					$aResponse["Message"] = "Defect Already entered!";
				}
			}
		}
	}

	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>