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

	$User    = IO::strValue("User");
	$Auditor = IO::intValue("Auditor");
	$Vendor  = IO::intValue("Vendor");
	
	
//	logApiCall($_POST);
	

	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, email, status, vendors, brands, style_categories, report_types, audit_stages, user_type FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser            = $objDb->getField(0, "id");
			$sName            = $objDb->getField(0, "name");
			$sEmail           = $objDb->getField(0, "email");
			$sBrands          = $objDb->getField(0, "brands");
			$sVendors         = $objDb->getField(0, "vendors");
			$sStyleCategories = $objDb->getField(0, "style_categories");
			$sReportTypes     = $objDb->getField(0, "report_types");
			$sAuditStages     = $objDb->getField(0, "audit_stages");
			$sUserType        = $objDb->getField(0, "user_type");

			
			$sAuditStagesList     = getList("tbl_audit_stages", "code", "stage");
			$sStageColorsList     = getList("tbl_audit_stages", "code", "color");
			$sVendorCountriesList = getList("tbl_vendors", "id", "country_id");
			$sCountryHoursList    = getList("tbl_countries", "id", "hours");			

			
			$bMgf = false;
				
			if ($sUserType == "MGF")
				$bMgf = true;

/*
			$sConditions = " WHERE approved='Y' AND audit_date=CURDATE( )
			                       AND (po_id='0' OR vendor_id IN ($sVendors))
			                       AND (po_id='0' OR po_id IN (SELECT id FROM tbl_po WHERE brand_id IN ($sBrands))) ";
*/
			$sConditions = " WHERE approved='Y' AND audit_date=CURDATE( )
			                       AND (po_id>'0' OR style_id>'0')
			                       AND vendor_id IN ($sVendors)
			                       AND (po_id='0' OR po_id IN (SELECT id FROM tbl_po WHERE brand_id IN ($sBrands) AND vendor_id IN ($sVendors)))
			                       AND FIND_IN_SET(report_id, '$sReportTypes')
			                       AND FIND_IN_SET(audit_stage, '$sAuditStages') ";

			$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id IN ($sBrands) AND FIND_IN_SET(category_id, '$sStyleCategories') ";
			$objDb->query($sSQL);

			$iCount  = $objDb->getCount( );
			$sStyles = "";

			for ($i = 0; $i < $iCount; $i ++)
				$sStyles .= (",".$objDb->getField($i, 0));

			if ($sStyles != "")
				$sStyles = substr($sStyles, 1);

//			if (@strpos($_SESSION["Email"], "marksnspencer.com") === FALSE)
//				$sConditions .= " AND (style_id='0' OR style_id IN ($sStyles)) ";

//			else
				$sConditions .= " AND style_id IN ($sStyles) ";


			$sConditions2 = $sConditions;


			if ($Auditor > 0)
				$sConditions .= " AND (user_id='$Auditor' OR group_id IN (SELECT id FROM tbl_auditor_groups WHERE FIND_IN_SET('$Auditor', users))) ";

			if ($Vendor > 0)
				$sConditions .= " AND vendor_id='$Vendor' ";





			$sSQL = "SELECT audit_code, style_id, user_id, po_id, report_id, vendor_id, total_gmts, checked_gmts, audit_date, start_time, end_time, audit_stage, audit_result, dhu, TIME_TO_SEC(start_time) AS _StartTime,
			                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
			                (SELECT etd_required FROM tbl_po_colors WHERE po_id=tbl_qa_reports.po_id ORDER BY etd_required LIMIT 1) AS _EtdRequired,
			                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
			                (SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line,
			                (SELECT report FROM tbl_reports WHERE id=tbl_qa_reports.report_id) AS _ReportType,
			                (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id) AS _Defects,
			                (SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=tbl_qa_reports.id) AS _GfDefects,
			                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor,
			                IF(group_id='0', '', (SELECT name FROM tbl_auditor_groups WHERE id=tbl_qa_reports.group_id)) AS _Group
			         FROM tbl_qa_reports
			         $sConditions
			         ORDER BY start_time";
			$objDb->query($sSQL);

			$iCount     = $objDb->getCount( );
			$iCompleted = 0;
			$sAudits    = array( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$sAuditCode   = $objDb->getField($i, 'audit_code');
				$sAuditDate   = $objDb->getField($i, 'audit_date');
				$sStartTime   = $objDb->getField($i, 'start_time');
				$sEndTime     = $objDb->getField($i, 'end_time');
				$sAuditStage  = $objDb->getField($i, 'audit_stage');
				$sAuditResult = $objDb->getField($i, 'audit_result');
				$iStyle       = $objDb->getField($i, 'style_id');
				$iPo          = $objDb->getField($i, 'po_id');
				$sPo          = $objDb->getField($i, '_Po');
				$iReport      = $objDb->getField($i, 'report_id');
				$sReportType  = $objDb->getField($i, '_ReportType');
				$iVendorId    = $objDb->getField($i, 'vendor_id');
				$sVendor      = $objDb->getField($i, '_Vendor');
				$sLine        = $objDb->getField($i, '_Line');
				$sEtdRequired = $objDb->getField($i, '_EtdRequired');
				$iSampleSize  = $objDb->getField($i, 'total_gmts');
				$iChecked     = $objDb->getField($i, 'checked_gmts');
				$iDefects     = $objDb->getField($i, '_Defects');
				$fDr          = $objDb->getField($i, 'dhu');
				$iStartTime   = $objDb->getField($i, '_StartTime');
				$sAuditor     = $objDb->getField($i, '_Auditor');
				$sGroup       = $objDb->getField($i, '_Group');


//				if ($bMgf == true)
				{
					$iCountry = $sVendorCountriesList[$iVendorId];
					$iHours   = $sCountryHoursList[$iCountry];
					
					$iStartTime += ($iHours * 3600);
				}
				
				if ($iReport == 6)
					$iDefects = $objDb->getField($i, '_GfDefects');

				if ($iStyle == 0 && $iPo > 0)
					$iStyle = getDbValue("style_id", "tbl_po_colors", "po_id='$iPo'");

				if ($sAuditResult == "")
					$sAuditResult = "";

				$sOnGoing = (($iChecked > 0 && $sAuditResult == "") ? "Y" : "N");
				$iDefects = intval($iDefects);


				$sSQL = "SELECT style, brand_id, sketch_file,
								(SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand,
								(SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id) AS _Season
						 FROM tbl_styles
						 WHERE id='$iStyle'";
				$objDb2->query($sSQL);

				$sStyle      = $objDb2->getField(0, 'style');
				$iBrand      = $objDb2->getField(0, 'brand_id');
				$sBrand      = $objDb2->getField(0, '_Brand');
				$sSeason     = $objDb2->getField(0, '_Season');
				$sSketchFile = $objDb2->getField(0, 'sketch_file');


				@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

				$sPicture       = "";
				$sQuondaDir     = (ABSOLUTE_PATH.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
				$sAuditPictures = @glob($sQuondaDir."?".substr($sAuditCode, 1)."_*.*");

				if (count($sAuditPictures) > 0)
				{
					$sPictures = array( );
					$iLength   = strlen($sAuditCode);

					foreach ($sAuditPictures as $sDefectPicture)
					{
						if (substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_001" ||
							substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_00_" ||
							strlen(@basename($sDefectPicture)) < ($iLength + 6) || @strpos($sDefectPicture, "_pack_") !== FALSE)
							continue;

						$sParts = @explode("_", $sDefectPicture);
						$sCode  = trim($sParts[1]);

						if (@array_key_exists($sCode, $sPictures))
							$sPictures[$sCode] ++;

						else
							$sPictures[$sCode] = 1;
					}

					@arsort($sPictures);

					$sCode = "";

					foreach ($sPictures as $sDefectCode => $iDefectCount)
					{
						if ($sCode == "")
							$sCode = $sDefectCode;
					}

					foreach ($sAuditPictures as $sDefectPicture)
					{
						if (substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_001" ||
							substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_00_" ||
							strlen(@basename($sDefectPicture)) < ($iLength + 6) || @strpos($sDefectPicture, "_pack_") !== FALSE)
							continue;


						if (@strpos($sDefectPicture, "_{$sCode}_") !== FALSE)
						{
							if (!@file_exists($sQuondaDir.'thumbs/'.@basename($sDefectPicture)))
							{
								@mkdir($sQuondaDir.'thumbs/');

								createImage(($sQuondaDir.@basename($sDefectPicture)), ($sQuondaDir.'thumbs/'.@basename($sDefectPicture)), 240, 180);
							}


							$sPicture = (SITE_URL.str_ireplace(ABSOLUTE_PATH, "", $sQuondaDir).'thumbs/'.@basename($sDefectPicture));

							break;
						}
					}
				}


				if ($sPicture == "" && $sSketchFile != "")
				{
					if (@file_exists(ABSOLUTE_PATH.STYLES_SKETCH_DIR.$sSketchFile))
					{
						if (!@file_exists(ABSOLUTE_PATH.STYLES_SKETCH_DIR."thumbs/".$sSketchFile))
							createImage((ABSOLUTE_PATH.STYLES_SKETCH_DIR.$sSketchFile), (ABSOLUTE_PATH.STYLES_SKETCH_DIR."thumbs/".$sSketchFile), 160, 160);

						$sPicture = (SITE_URL.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile);
					}
				}


				$sColor          = $sStageColorsList[$sAuditStage];
				$sAuditStageText = $sAuditStagesList[$sAuditStage];

				if ($sColor == "")
					$sColor = "#cccccc";

				if ($sAuditStageText == "")
					$sAuditStageText = "-";


				switch ($sAuditResult)
				{
					case "P"  :  $sAuditResult = "Pass"; break;
					case "F"  :  $sAuditResult = "Fail"; break;
					case "H"  :  $sAuditResult = "Hold"; break;
					case "A"  :  $sAuditResult = "Pass"; break;
					case "B"  :  $sAuditResult = "Pass"; break;
					case "C"  :  $sAuditResult = "Fail"; break;
					default   :  $sAuditResult = "-"; break;
				}


				if ($iStartTime >= (2 * 3600) && $iStartTime < (4 * 3600))
					$iBlock = 1;

				else if ($iStartTime >= (4 * 3600) && $iStartTime < (6 * 3600))
					$iBlock = 2;

				else if ($iStartTime >= (6 * 3600) && $iStartTime < (8 * 3600))
					$iBlock = 3;

				else if ($iStartTime >= (8 * 3600) && $iStartTime < (10 * 3600))
					$iBlock = 4;

				else if ($iStartTime >= (10 * 3600) && $iStartTime < (12 * 3600))
					$iBlock = 5;

				else if ($iStartTime >= (12 * 3600) && $iStartTime < (14 * 3600))
					$iBlock = 6;

				else if ($iStartTime >= (14 * 3600) && $iStartTime < (16 * 3600))
					$iBlock = 7;

				else if ($iStartTime >= (16 * 3600) && $iStartTime < (18 * 3600))
					$iBlock = 8;

				else if ($iStartTime >= (18 * 3600) && $iStartTime < (20 * 3600))
					$iBlock = 9;

				else if ($iStartTime >= (20 * 3600) && $iStartTime < (22 * 3600))
					$iBlock = 10;

				else if ($iStartTime >= (22 * 3600) && $iStartTime < (24 * 3600))
					$iBlock = 11;

				else
					$iBlock = 0;



				$sAudits[] = array("AuditCode"      => $sAuditCode,
				                   "Vendor"         => $sVendor,
				                   "BrandId"        => $iBrand,
				                   "Brand"          => (($sBrand != "") ? $sBrand : "-"),
				                   "Po"             => (($sPo != "") ? $sPo : "-"),
				                   "Style"          => (($sStyle != "") ? $sStyle : "-"),
				                   "Season"         => (($sSeason != "") ? $sSeason : "-"),
				                   "EtdRequired"    => (($sEtdRequired != "") ? formatDate($sEtdRequired) : "-"),
				                   "ReportType"     => $sReportType,
				                   "AuditStage"     => (($sAuditStage != "") ? $sAuditStage : "?"),
				                   "AuditStageText" => $sAuditStageText,
				                   "AuditTime"      => (formatTime($sStartTime, "h:i A", $bMgf)." - ".formatTime($sEndTime, "h:i A", $bMgf)),
				                   "SampleSize"     => $iSampleSize,
				                   "Line"           => $sLine,
				                   "Defects"        => ((($iDefects > 0 || $sAuditResult != "-") ? $iDefects : "-").(($sAuditResult != "-") ? (" (DR:".formatNumber($fDr)."%)") : "")),
				                   "AuditResult"    => $sAuditResult,
				                   "Picture"        => $sPicture,
				                   "Auditor"        => $sAuditor,
				                   "Group"          => $sGroup,
				                   "OnGoing"        => $sOnGoing,
				                   "Color"          => $sColor,
				                   "Block"          => $iBlock);

				if ($sAuditStage != "" && $sAuditResult != "-")
					$iCompleted ++;
			}




//			$sAuditors = getList("tbl_users u, tbl_qa_reports qa", "u.id", "u.name", "(u.id=qa.user_id OR (qa.qroup_id>'0' AND u.id IN (SELECT users FROM tbl_auditor_groups WHERE id=qa.group_id))) $sConditions2)");
			$sSQL = "SELECT user_id, (SELECT users FROM tbl_auditor_groups WHERE id=tbl_qa_reports.group_id) FROM tbl_qa_reports $sConditions2";
			$objDb->query($sSQL);
			
			$iCount    = $objDb->getCount( );
			$sAuditors = "0";
			
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iAuditor = $objDb->getField($i, 0);
				$sGroup   = $objDb->getField($i, 1);
				
				
				$sAuditors .= ",{$iAuditor}";
				
				if ($sGroup != "")
					$sAuditors .= ",{$sGroup}";
			}
			
			
			$sAuditors = getList("tbl_users", "DISTINCT(id)", "name", "id IN ($sAuditors)");
			$sVendors  = getList("tbl_vendors", "id", "vendor", "id IN (SELECT DISTINCT(vendor_id) FROM tbl_qa_reports $sConditions2)");


			$sToday = array("Day"       => date("j"),
			                "Planned"   => $iCount,
			                "Completed" => $iCompleted,
			                "Pending"   => ($iCount - $iCompleted));


			$aResponse['Status']   = "OK";
			$aResponse['Today']    = $sToday;
			$aResponse['Audits']   = $sAudits;
			$aResponse['Auditors'] = $sAuditors;
			$aResponse['Vendors']  = $sVendors;
		}
	}


	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($aResponse);

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>