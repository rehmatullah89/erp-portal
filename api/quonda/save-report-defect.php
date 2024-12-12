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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$User         = IO::intValue('User');
	$AuditCode    = IO::strValue("AuditCode");
	$DefectCode   = IO::strValue("DefectCode");
	$DefectArea   = IO::strValue("DefectArea");
	$Defects      = IO::intValue("Defects");
	$DefectNature = IO::floatValue("DefectNature");
	$RollNo       = IO::intValue("RollNo");
	$PanelNo      = IO::intValue("PanelNo");
	$Grade        = IO::intValue("Grade");

	$iAuditCode = intval(substr($AuditCode, 1));
	$Defects    = (($Defects == 0) ? 1 : $Defects);


	$aResponse = array( );


	if ($User == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid User ID";
	}

	else if ($iAuditCode == 0 || strlen($AuditCode) == 0 || $AuditCode{0} != "S")
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Audit Code";
	}

	else
	{
		$sStatus = getDbValue("status", "tbl_users", "id='$User'");

		if ($sStatus != "A")
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "User Account is Disabled";
		}

		else if ($DefectCode == 0 || $Defects == 0)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "Invalid Defect Saving Request";
		}

		else
		{
			$sUser = getDbValue("name", "tbl_users", "id='$User'");


			if ($RollNo > 0 && $PanelNo > 0 && $Grade > 0)
			{
				$iId  = getNextId("tbl_gf_report_defects");


				$sSQL = "INSERT INTO tbl_gf_report_defects (id, audit_id, roll, panel, code_id, grade, defects)
				                                    VALUES ('$iId', '$iAuditCode', '$RollNo', '$PanelNo', '$DefectCode', '$Grade', '$Defects')";

				if ($objDb->execute($sSQL, true, $User, $sUser) == true)
				{
					$iTotalDefects  = getDbValue("SUM(defects)", "tbl_gf_report_defects", "audit_id='$iAuditCode'");
					$iTotalPoints   = getDbValue("SUM((defects * grade))", "tbl_gf_report_defects", "audit_id='$iAuditCode'");
					$iTotalGivenQty = getDbValue("SUM((given_1 + given_2 + given_3))", "tbl_gf_rolls_info", "audit_id='$iAuditCode'");


					$sSQL = "SELECT po_id, fabric_width FROM tbl_qa_reports WHERE id='$iAuditCode'";
					$objDb->query($sSQL);

					$iPoId        = $objDb->getField(0, 'po_id');
					$iFabricWidth = $objDb->getField(0, 'fabric_width');



					if (getDbValue("brand_id", "tbl_po", "id='$iPoId'") == 77)
						$fDhu = round((($iTotalDefects * 39.37 * 100) / $iTotalGivenQty / $iFabricWidth), 2);

					else
						$fDhu = round(((($iTotalPoints * 3600) / $iTotalGivenQty) / $iFabricWidth), 2);


					$sSQL = "UPDATE tbl_qa_reports SET dhu='$fDhu', date_time=NOW( ) WHERE id='$iAuditCode'";
					$objDb->execute($sSQL, true, $User, $sUser);


					$aResponse['Status']  = "OK";
					$aResponse["Message"] = "Defect Saved Successfully!";
				}

				else
				{
					$aResponse['Status'] = "ERROR";
					$aResponse["Error"]  = "An ERROR occured, please try again.";
				}

			}

			else
			{
				$iId  = getNextId("tbl_qa_report_defects");


				$sSQL = "INSERT INTO tbl_qa_report_defects (id, audit_id, code_id, defects, area_id, nature)
				                                    VALUES ('$iId', '$iAuditCode', '$DefectCode', '$Defects', '$DefectArea', '$DefectNature')";

				if ($objDb->execute($sSQL, true, $User, $sUser) == true)
				{
					$iReportId   = getDbValue("report_id", "tbl_qa_reports", "id='$iAuditCode'");
					$iSampleSize = getDbValue("total_gmts", "tbl_qa_reports", "id='$iAuditCode'");

					if ($iReportId == 10)
						$iDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditCode' AND nature='1'");

					else if ($iReportId == 11)
						$iDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditCode' AND (nature='0' OR nature='2.5')");

					else
						$iDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditCode' AND nature='0'");


					$fDhu = @round((($iDefects / $iSampleSize) * 100), 2);


					$sSQL = "UPDATE tbl_qa_reports SET dhu='$fDhu', defective_gmts='$iDefects' WHERE id='$iAuditCode'";
					$objDb->execute($sSQL, true, $User, $sUser);


					$aResponse['Status']  = "OK";
					$aResponse["Message"] = "Defect Saved Successfully!";
				}

				else
				{
					$aResponse['Status'] = "ERROR";
					$aResponse["Error"]  = "An ERROR occured, please try again.";
				}
			}
		}
	}

	print @json_encode($aResponse);


/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($aResponse)." - {$iSampleSize} - {$iDefects} <br><br>".$sSQL;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>