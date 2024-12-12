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
	$objDb3      = new Database( );
	$objDb4      = new Database( );

	$User      = IO::strValue("User");
	$AuditCode = IO::strValue("AuditCode");
	$Users     = IO::strValue("Users");
	$Others    = IO::strValue("Others");


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S" || ($Users == "" && $Others == ""))
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, email, status, language, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser  = $objDb->getField(0, "id");
			$sName  = $objDb->getField(0, "name");
			$sEmail = $objDb->getField(0, "email");
			$sLang  = $objDb->getField(0, "language");
			$sGuest = $objDb->getField(0, "guest");


			$sSQL = "SELECT *,
							(SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
							(SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor
					 FROM tbl_qa_reports
					 WHERE audit_code='$AuditCode'";
			$objDb->query($sSQL);

			$iAuditor       = $objDb->getField(0, "user_id");
			$iId            = $objDb->getField(0, "id");
			$iReportId      = $objDb->getField(0, "report_id");
			$sVendor        = $objDb->getField(0, "_Vendor");
			$iPo            = $objDb->getField(0, "po_id");
			$iAdditionalPos = $objDb->getField(0, "additional_pos");
			$sPo            = $objDb->getField(0, "_Po");
			$iStyle         = $objDb->getField(0, "style_id");
			$sSizes         = $objDb->getField(0, "sizes");
			$sAuditCode     = $objDb->getField(0, "audit_code");
			$sAuditDate     = $objDb->getField(0, "audit_date");
			$sAuditStage    = $objDb->getField(0, "audit_stage");
			$sAuditResult   = $objDb->getField(0, "audit_result");
			$iLine          = $objDb->getField(0, "line_id");
			$fDhu           = $objDb->getField(0, "dhu");
			$sComments      = $objDb->getField(0, "qa_comments");


			
			$Recipients = @explode(",", $Users);
			$sBaseDir   = ABSOLUTE_PATH;
/*
			if ($ReportId == 4)
				@include($sBaseDir."includes/quonda/export-inflatable-report.php");

			else if ($ReportId == 6)
				@include($sBaseDir."includes/quonda/export-gf-report.php");

			else if ($ReportId == 7)
				@include($sBaseDir."includes/quonda/export-ar-report.php");

			else if ($ReportId == 19)
				@include($sBaseDir."includes/quonda/export-adidas-report.php");

			else if ($ReportId == 8)
				@include($sBaseDir."includes/quonda/export-apparel-report.php");

			else if ($ReportId == 9)
				@include($sBaseDir."includes/quonda/export-yarn-report.php");

			else if ($ReportId == 10)
				@include($sBaseDir."includes/quonda/export-jako-report.php");

			else if ($ReportId == 1 && @in_array($Brand, array(87, 119, 120, 121)))
				@include($sBaseDir."includes/quonda/export-old-jako-report.php");

			else if ($ReportId == 11)
				@include($sBaseDir."includes/quonda/export-ms-report.php");

			else if ($ReportId == 14 || $ReportId == 34)
				@include($sBaseDir."includes/quonda/export-mgf-report.php");

			else if (@in_array($ReportId, array(2, 5)))
				@include($sBaseDir."includes/quonda/export-woven-report.php");

			else if ($ReportId == 1 && ($Brand == 32 || $Brand == 111))
			{
				if ($AuditStage == "F" && $Brand == 32)
					@include($sBaseDir."includes/quonda/export-nike-final-audit-knits-report.php");

				else
					@include($sBaseDir."includes/quonda/export-nike-knits-report.php");
			}

			else if (@in_array($ReportId, array(15, 16, 17, 18, 21, 22)))
				@include($sBaseDir."includes/quonda/export-qmip-report.php");

			else if ($ReportId == 20 || $ReportId == 23)
				@include($sBaseDir."includes/quonda/export-kik-report.php");

			else if ($ReportId == 24)
				@include($sBaseDir."includes/quonda/export-sampling-report.php");

			else if ($ReportId == 25)
			{
				if ($AuditStage == 'F')
					@include($sBaseDir."includes/quonda/export-final-billabong-report.php");

				else
					@include($sBaseDir."includes/quonda/export-inline-billabong-report.php");
			}

			else if ($ReportId == 26)
				@include($sBaseDir."includes/quonda/export-tnc-report.php");

			else if ($ReportId == 28)
			{
				$sLanguages  = array('de', 'tr', 'en');

				foreach ($sLanguages as $sLanguage)
				{
					if (getDbValue("COUNT(1)", "tbl_qa_emails", ("FIND_IN_SET(id,'".@implode(",", $Recipients)."') AND language='$sLanguage'")) > 0)
						@include($sBaseDir."includes/quonda/export-controlist-report.php");
				}
			}
			
			else if ($ReportId == 29)
				@include($sBaseDir."includes/quonda/export-leverstyle-report.php");
			
			else if ($ReportId == 30)
				@include($sBaseDir."includes/quonda/export-towel-report.php");
			
			else if ($ReportId == 31)
				@include($sBaseDir."includes/quonda/export-hybrid-apparel-report.php");
			
			else if ($ReportId == 32)
				@include($sBaseDir."includes/quonda/export-arcadia-report.php");
			
			else if ($ReportId == 33)
				@include($sBaseDir."includes/quonda/export-gms-report.php");
			
			else if ($ReportId == 35)
				@include($sBaseDir."includes/quonda/export-timezone-report.php");
			
			else if ($ReportId == 36)
				@include($sBaseDir."includes/quonda/export-hybrid-link-report.php");
			
			else if ($ReportId == 37)
				@include($sBaseDir."includes/quonda/export-armedangels-report.php");

			else
				@include($sBaseDir."includes/quonda/export-other-report.php");
*/
			$sAdditionalPos = "";

			$sSQL = "SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id IN ($iAdditionalPos) ORDER BY order_no";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
				$sAdditionalPos .= (",".$objDb->getField($i, 0));
			

			$sSQL = "SELECT style, sub_brand_id, (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand FROM tbl_styles WHERE id='$iStyle'";
			$objDb->query($sSQL);

			$sStyle = $objDb->getField(0, "style");
			$iBrand = $objDb->getField(0, "sub_brand_id");
			$sBrand = $objDb->getField(0, "_Brand");
	
	
			$sLine           = getDbValue("line", "tbl_lines", "id='$iLine'");
			$sAuditStage     = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");
			$sDefectsSummary = "<b>Defects details:</b><br />";
			$sSpecsSummary   = "";


			if ($iReportId == 30)
			{
				$sSQL = "SELECT * FROM tbl_towel_report_defects WHERE audit_id='$iId' ORDER BY id";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				if ($iCount == 0)
					$sDefectsSummary .= "No Defect Found<br />";

				else
				{
					$sDefectsSummary .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">';
					$sDefectsSummary .= '<tr bgcolor="#eeeeee">';
					$sDefectsSummary .= '<td width="6%"><b>#</b></td>';
					$sDefectsSummary .= '<td width="17%"><b>Lot No</b></td>';
					$sDefectsSummary .= '<td width="17%"><b>Roll No</b></td>';
					$sDefectsSummary .= '<td width="12%"><b>Pcs Width</b></td>';
					$sDefectsSummary .= '<td width="12%"><b>Ticket Pcs</b></td>';
					$sDefectsSummary .= '<td width="12%"><b>Actual Pcs</b></td>';
					$sDefectsSummary .= '<td width="12%"><b>Defective Pcs</b></td>';
					$sDefectsSummary .= '<td width="12%"><b>Result</b></td>';
					$sDefectsSummary .= '</tr>';

					for($i = 0; $i < $iCount; $i ++)
					{
						$sLotNo     = $objDb->getField($i, 'lot_no');
						$sRollNo    = $objDb->getField($i, 'roll_no');
						$iWidth     = $objDb->getField($i, 'width');
						$iTicketPcs = $objDb->getField($i, 'ticket_meters');
						$iActualPcs = $objDb->getField($i, 'actual_meters');
						$iDefects   = $objDb->getField($i, 'allowable_defects');
						$sResult    = $objDb->getField($i, 'result');

						
						$sDefectsSummary .= '<tr>';
						$sDefectsSummary .= '<td>'.($i + 1).'</td>';
						$sDefectsSummary .= '<td>'.$sLotNo.'</td>';
						$sDefectsSummary .= '<td>'.$sRollNo.'</td>';
						$sDefectsSummary .= '<td>'.$iWidth.'</td>';
						$sDefectsSummary .= '<td>'.$iTicketPcs.'</td>';
						$sDefectsSummary .= '<td>'.$iActualPcs.'</td>';
						$sDefectsSummary .= '<td>'.$iDefects.'</td>';
						$sDefectsSummary .= '<td>'.(($sResult == "P") ? "Pass" : "Fail").'</td>';
						$sDefectsSummary .= '</tr>';
					}

					$sDefectsSummary .= '</table>';
				}
			}
			
			
			else if ($iReportId == 26)
			{
				$sSQL = "SELECT * FROM tbl_tnc_report_defects WHERE audit_id='$iId' ORDER BY id";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				if ($iCount == 0)
					$sDefectsSummary .= "No Defect Found<br />";

				else
				{
					$sDefectsSummary .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">';
					$sDefectsSummary .= '<tr bgcolor="#eeeeee">';
					$sDefectsSummary .= '<td width="6%"><b>#</b></td>';
					$sDefectsSummary .= '<td width="17%"><b>Lot No</b></td>';
					$sDefectsSummary .= '<td width="17%"><b>Roll No</b></td>';
					$sDefectsSummary .= '<td width="12%"><b>Width</b></td>';
					$sDefectsSummary .= '<td width="12%"><b>Ticket Yards</b></td>';
					$sDefectsSummary .= '<td width="12%"><b>Actual Yards</b></td>';
					$sDefectsSummary .= '<td width="12%"><b>Total Points</b></td>';
					$sDefectsSummary .= '<td width="12%"><b>Result</b></td>';
					$sDefectsSummary .= '</tr>';

					for($i = 0; $i < $iCount; $i ++)
					{
						$sLotNo       = $objDb->getField($i, 'lot_no');
						$sRollNo      = $objDb->getField($i, 'roll_no');
						$iWidth       = $objDb->getField($i, 'width');
						$iTicketYards = $objDb->getField($i, 'ticket_meters');
						$iActualYards = $objDb->getField($i, 'actual_meters');
						$sResult      = $objDb->getField($i, 'result');
						
						$iPoints = ($objDb->getField($i, 'holes') + $objDb->getField($i, 'slubs') + $objDb->getField($i, 'stains') + $objDb->getField($i, 'fly') + $objDb->getField($i, 'other'));
						$fPoints = @round((($iPoints * 3600)/($iWidth * $iTicketYards)), 2);

						
						$sDefectsSummary .= '<tr>';
						$sDefectsSummary .= '<td>'.($i + 1).'</td>';
						$sDefectsSummary .= '<td>'.$sLotNo.'</td>';
						$sDefectsSummary .= '<td>'.$sRollNo.'</td>';
						$sDefectsSummary .= '<td>'.$iWidth.'</td>';
						$sDefectsSummary .= '<td>'.$iTicketYards.'</td>';
						$sDefectsSummary .= '<td>'.$iActualYards.'</td>';
						$sDefectsSummary .= '<td>'.$fPoints.'</td>';
						$sDefectsSummary .= '<td>'.(($sResult == "P") ? "Pass" : "Fail").'</td>';
						$sDefectsSummary .= '</tr>';
					}

					$sDefectsSummary .= '</table>';
				}
			}
			
			else
			{			
				$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$iId' ORDER BY id";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				if ($iCount == 0)
					$sDefectsSummary .= "No Defect Found<br />";

				else
				{
					$sDefectsSummary .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">';
					$sDefectsSummary .= '<tr bgcolor="#eeeeee">';
					$sDefectsSummary .= '<td width="8%"><b>#</b></td>';
					$sDefectsSummary .= '<td width="52%"><b>Code - Defect</b></td>';
					$sDefectsSummary .= '<td width="20%"><b>Area</b></td>';
					$sDefectsSummary .= '<td width="10%"><b>Defects</b></td>';
					$sDefectsSummary .= '<td width="10%"><b>Nature</b></td>';
					$sDefectsSummary .= '</tr>';

					for($i = 0; $i < $iCount; $i ++)
					{
						$sSQL = ("SELECT code, defect FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
						$objDb2->query($sSQL);

						$sSQL = ("SELECT id, area FROM tbl_defect_areas WHERE id='".$objDb->getField($i, 'area_id')."'");
						$objDb3->query($sSQL);


						switch ($objDb->getField($i, "nature"))
						{
							case 2 : $sNature = "Critical"; break;
							case 1 : $sNature = "Major"; break;
							case 0 : $sNature = "Minor"; break;
						}


						$sDefectsSummary .= '<tr>';
						$sDefectsSummary .= '<td>'.($i + 1).'</td>';
						$sDefectsSummary .= '<td>'.$objDb2->getField(0, 0).' - '.$objDb2->getField(0, 1).'</td>';
						$sDefectsSummary .= '<td>'.$objDb3->getField(0, 0).' - '.$objDb3->getField(0, 1).'</td>';
						$sDefectsSummary .= '<td>'.$objDb->getField($i, 'defects').'</td>';
						$sDefectsSummary .= '<td>'.$sNature.'</td>';
						$sDefectsSummary .= '</tr>';
					}

					$sDefectsSummary .= '</table>';
				}
			}
			
			
			$iSpecsSamples = getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$iId'");
			
			if ($iSpecsSamples > 0)
			{
				$iPointsEvaluated = 0;
				$sSizes           = getDbValue("GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',')", "tbl_qa_report_samples", "audit_id='$iId'");
				$iSizes           = @explode(",", $sSizes);
				
				foreach ($iSizes as $iSize)
				{
					$sSize = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");
					
					$iSizeSamples = getDbValue('COUNT(DISTINCT sample_no)', 'tbl_qa_report_samples', "audit_id='$iId' AND size_id='$iSize'");
					$iSizePoints  = getDbValue("COUNT(point_id)", "tbl_style_specs", "style_id='$iStyle' AND size_id='$iSize' AND version='0' AND specs!='0' AND specs!=''");
					
					$iPointsEvaluated += ($iSizeSamples * $iSizePoints);
				}		


				if ($iReportId == 20 || $iReportId == 23)
				{
					$iPointsOutOfTolerance = getDbValue("COUNT(1)", 
														"tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss", 
														"qrs.audit_id='$Id' AND qrs.id=qrss.sample_id AND qrss.findings!='' AND LOWER(qrss.findings)!='ok' AND qrss.findings!='0'");

				}
				
				else
				{				
					$iPointsOutOfTolerance = getDbValue("COUNT(1)", 
														"tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss, tbl_style_specs ss, tbl_measurement_points mp", 
														"qrs.audit_id='$iId' AND qrs.id=qrss.sample_id AND ss.style_id='$iStyle' AND mp.brand_id='$iBrand'
														 AND ss.size_id=qrs.size_id AND ss.point_id=qrss.point_id AND ss.point_id=mp.id
														 AND ss.version='0' AND ss.specs!='' AND ss.specs!='0' 
														 AND qrss.findings!='' AND LOWER(qrss.findings)!='ok' AND qrss.findings!='0'
														 AND ( CAST(qrss.findings AS DECIMAL(10,6)) < CAST(CONCAT('-', TRIM(REPLACE(mp.tolerance, '+/-', ''))) AS DECIMAL(10,6))  OR  
															   CAST(qrss.findings AS DECIMAL(10,6)) > CAST(TRIM(REPLACE(mp.tolerance, '+/-', '')) AS DECIMAL(10,6)) )");
				}
				
				$fSpecsPercentage      = @round(($iPointsOutOfTolerance / $iPointsEvaluated) * 100);
				
				
				$sSpecsSummary   .= "<b>Spec Audit Summary</b><br />";
				
				$sSpecsSummary .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">';
				$sSpecsSummary .= '<tr>';
				$sSpecsSummary .= '<td width="280">Quantity Checked - PCS</td>';
				$sSpecsSummary .= '<td>'.$iSpecsSamples.'</td>';
				$sSpecsSummary .= '</tr>';

				$sSpecsSummary .= '<tr>';
				$sSpecsSummary .= '<td>No of Measurement Points Evaluated</td>';
				$sSpecsSummary .= '<td>'.$iPointsEvaluated.'</td>';
				$sSpecsSummary .= '</tr>';
				
				$sSpecsSummary .= '<tr>';
				$sSpecsSummary .= '<td>No of Points Out of Tolerance</td>';
				$sSpecsSummary .= '<td>'.$iPointsOutOfTolerance.'</td>';
				$sSpecsSummary .= '</tr>';
				
				$sSpecsSummary .= '<tr>';
				$sSpecsSummary .= '<td>Result</td>';
				$sSpecsSummary .= '<td>'.(($fSpecsPercentage > 20) ? '<span style="color:#e40001;">Fail</span>' : '<span style="color:#83af00">Pass</span>').'</td>';
				$sSpecsSummary .= '</tr>';
				$sSpecsSummary .= '</table>';
			}



			$sResultText = "<b style='color:#e40001;'>Fail</b>";

			if ($sAuditResult == "P" || $sAuditResult == "A" || $sAuditResult == "B")
			{
				$sAuditResult = "Pass";
				$sResultText  = "<b style='color:#aaaf00;'>Pass</b>";
			}

			else if ($sAuditResult == "H")
			{
				$sAuditResult = "Hold";
				$sResultText  = "<b style='color:#fe7100;'>Hold</b>";
			}

			else if ($sAuditResult == "R")
			{
				$sAuditResult = "Re-Inspection";
				$sResultText  = "<b style='color:#fe7100;'>Re-Inspection</b>";
			}

			else
				$sAuditResult = "Fail";


			$sBody = @file_get_contents("{$sBaseDir}emails/qa-report.txt");
			$sBody = @str_replace("[Brand]", $sBrand, $sBody);
			$sBody = @str_replace("[PO]", ($sPo.$sAdditionalPos), $sBody);
			$sBody = @str_replace("[AuditCode]", $sAuditCode, $sBody);
			$sBody = @str_replace("[Vendor]", $sVendor, $sBody);
			$sBody = @str_replace("[Style]", $sStyle, $sBody);
			$sBody = @str_replace("[Line]", $sLine, $sBody);
			$sBody = @str_replace("[AuditStage]", $sAuditStage, $sBody);
			$sBody = @str_replace("[DefectsSummary]", $sDefectsSummary, $sBody);
			$sBody = @str_replace("[SpecsSummary]", $sSpecsSummary, $sBody);
			$sBody = @str_replace("[Result]", $sResultText, $sBody);
			$sBody = @str_replace("[Comments]", nl2br($sComments), $sBody);
			$sBody = @str_replace("[Auditor]", getDbValue("name", "tbl_users", "id='$iAuditor'"), $sBody);
			$sBody = @str_replace("[PdfLink]", (SITE_URL."get-qa-report.php?Id=".md5($sAuditCode)."&AuditCode=".$sAuditCode), $sBody);
			$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
			$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);

/*
			if ($ReportId == 28)
			{
				$sLanguages  = array('de', 'tr', 'en');

				foreach ($sLanguages as $sLanguage)
				{
					$sSQL = ("SELECT name, email FROM tbl_qa_emails WHERE FIND_IN_SET(id,'$Users') AND language='$sLanguage' ORDER BY name");
					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );

					if ($iCount > 0 || ($sLanguage == "en" && $Others != ""))
					{
						$objEmail = new PHPMailer( );

					//	$objEmail->FromName = "Triple Tree Customer Portal";
						$objEmail->Subject  = ("Found ".formatNumber($fDhu)."% DR at {$sAuditStage} in {$sLine} line - [{$sAuditResult}]");

						$objEmail->MsgHTML($sBody);


						for ($i = 0; $i < $iCount; $i ++)
						{
							$sUserName  = $objDb->getField($i, "name");
							$sUserEmail = $objDb->getField($i, "email");

							$objEmail->AddAddress($sUserEmail, $sUserName);
						}


						if ($Users == "0" && $sLanguage == $sLang)
							$objEmail->AddAddress($sEmail, $sName);

						if ($sLanguage == "en")
						{
							$Others = str_replace(";", ",", $Others);
							$Others = str_replace(" ", "", $Others);

							if ($Others != "")
							{
								$Others = @explode(",", $Others);

								foreach ($Others as $sEmail)
									$objEmail->AddAddress($sEmail, $sEmail);
							}
						}


						$sPdfFile = (ABSOLUTE_PATH.TEMP_DIR."S{$iId}-QA-Report-{$sLanguage}.pdf");

						$objEmail->AddAttachment($sPdfFile, @basename($sPdfFile));
						$objEmail->Send( );

						@unlink($sPdfFile);
					}
				}
			}

			else
*/
			{
				$objEmail = new PHPMailer( );

			//	$objEmail->FromName = "Triple Tree Customer Portal";
			
				if ($iReportId == 26 || $iReportId == 30)
					$objEmail->Subject  = ("Inspection '{$sAuditResult}' at {$sAuditStage} in {$sLine} line");
			
				else
					$objEmail->Subject  = ("Found ".formatNumber($fDhu)."% DR at {$sAuditStage} in {$sLine} line - [{$sAuditResult}]");

				$objEmail->MsgHTML($sBody);


				if ($Users != "" && $Users != "0")
				{
					$sSQL = "SELECT name, email FROM tbl_qa_emails WHERE FIND_IN_SET(id,'$Users') ORDER BY name";
					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );

					for ($i = 0; $i < $iCount; $i ++)
					{
						$sUserName  = $objDb->getField($i, "name");
						$sUserEmail = $objDb->getField($i, "email");

						$objEmail->AddAddress($sUserEmail, $sUserName);
					}
				}

				if ($Users == "0")
					$objEmail->AddAddress($sEmail, $sName);

				$Others = str_replace(";", ",", $Others);
				$Others = str_replace(" ", "", $Others);

				if ($Others != "")
				{
					$Others = @explode(",", $Others);

					foreach ($Others as $sEmail)
						$objEmail->AddAddress($sEmail, $sEmail);
				}

//				$objEmail->AddAttachment($sPdfFile, @basename($sPdfFile));
				$bEmailed = $objEmail->Send( );

//				@unlink($sPdfFile);
			}


			if ($bEmailed == true)
			{
				$aResponse['Status']  = "OK";
				$aResponse['Message'] = "QA Report Email sent successfully.";
			}

			else
				$aResponse['Message'] = "An ERROR occured while emailing the QA Report.";
		}
	}


	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($aResponse);

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir@3-tree.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDb4->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>