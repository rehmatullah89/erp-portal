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

	$User        = IO::strValue("User");
	$Status      = IO::strValue('Status');
	$Country     = IO::intValue('Country');
	$FromDate    = IO::strValue('FromDate');
	$ToDate      = IO::strValue('ToDate');
	$Vendor      = IO::intValue('Vendor');
	$Brand       = IO::intValue('Brand');
	$ReportType  = IO::strValue('ReportType');
	$AuditStage  = IO::strValue('AuditStage');
	$AuditResult = IO::strValue('AuditResult');
	$AuditCode   = IO::strValue('AuditCode');
	$DefectRate  = IO::floatValue('DefectRate');
	$AuditorType = IO::intValue('AuditorType');
	$Po          = IO::strValue('Po');
	$Style       = IO::strValue('Style');
	$StyleId     = IO::intValue('StyleId');
	$AuditorId   = IO::strValue('AuditorId');
	$Auditor     = IO::intValue('Auditor');
	$MasterId    = IO::intValue('MasterId');
	$PageId      = IO::intValue('PageId');


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, email, admin, status, vendors, brands, style_categories, report_types, audit_stages FROM tbl_users WHERE MD5(id)='$User'";
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
			$sAdmin           = $objDb->getField(0, "admin");
			$sBrands          = $objDb->getField(0, "brands");
			$sVendors         = $objDb->getField(0, "vendors");
			$sStyleCategories = $objDb->getField(0, "style_categories");
			$sReportTypes     = $objDb->getField(0, "report_types");
			$sAuditStages     = $objDb->getField(0, "audit_stages");



			$sAuditStagesList = getList("tbl_audit_stages", "code", "stage");
			$sStageColorsList = getList("tbl_audit_stages", "code", "color");


			$PageId  = (($PageId <= 0) ? 1 : $PageId);
			$iLimit  = 25;
			$iStart  = (($PageId * $iLimit) - $iLimit);
			$sAudits = array( );

			if ($Status == "")
				$sConditions .= " WHERE (user_id='$iUser' OR (group_id>'0' AND group_id IN (SELECT id FROM tbl_auditor_groups WHERE FIND_IN_SET('$iUser', users))))
				                        AND approved='Y'
				                        AND (audit_result='' OR ISNULL(audit_result))
				                        AND FIND_IN_SET(audit_stage, '$sAuditStages') AND FIND_IN_SET(report_id, '$sReportTypes') ";

			else
			{
				$sConditions .= " WHERE audit_result!='' AND FIND_IN_SET(report_id, '$sReportTypes') ";
				
				if ($sAdmin == "Y" && (@strpos($sEmail, "@apparelco.com") !== FALSE || @strpos($sEmail, "@3-tree.com") !== FALSE))
					$sConditions .= " AND (published='N' OR published='Y' OR user_id='$iUser' OR (group_id>'0' AND group_id IN (SELECT id FROM tbl_auditor_groups WHERE FIND_IN_SET('$iUser', users)))) ";
				
				else
					$sConditions .= " AND (published='Y' OR user_id='$iUser' OR (group_id>'0' AND group_id IN (SELECT id FROM tbl_auditor_groups WHERE FIND_IN_SET('$iUser', users)))) ";
			}	

			$sConditions .= " AND (po_id>'0' OR style_id>'0') ";

			if ($AuditorId != "")
				$sConditions .= " AND MD5(user_id)='$AuditorId' ";
			
			if ($Auditor > 0)
				$sConditions .= " AND user_id='$Auditor' ";
			
			if ($MasterId > 0)
				$sConditions .= " AND master_id='$MasterId' ";

			if ($AuditCode != "")
				$sConditions .= " AND audit_code LIKE '%$AuditCode%' ";

			if ($FromDate != "" && $ToDate != "")
				$sConditions .= " AND (audit_date BETWEEN '$FromDate' AND '$ToDate') ";

			if ($ReportType != "")
				$sConditions .= " AND FIND_IN_SET(report_id, '$ReportType') ";

			if ($AuditStage != "")
				$sConditions .= " AND FIND_IN_SET(audit_stage, '$AuditStage') ";

			if ($AuditResult != "")
				$sConditions .= " AND FIND_IN_SET(audit_result, '$AuditResult') ";

			if ($Country > 0)
				$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Country') ";

			if ($DefectRate > 0)
				$sConditions .= " AND dhu >= '$DefectRate' ";
			
			if ($AuditorType > 0)
				$sConditions .= " AND user_id IN (SELECT id FROM tbl_users WHERE auditor_type='$AuditorType') ";

			 if ($Vendor > 0)
				$sConditions .= " AND vendor_id='$Vendor' ";

			else
				$sConditions .= " AND vendor_id IN($sVendors) ";

/*
			else
			{
				if ($Brand > 0)
					$sConditions .= " AND vendor_id IN ($sVendors) ";

				else if (@strpos($sEmail, "@apparelco.com") !== FALSE || @strpos($sEmail, "@3-tree.com") !== FALSE)
					$sConditions .= " AND (po_id='0' OR vendor_id IN ($sVendors)) ";

				else
					$sConditions .= " AND (po_id='0' OR po_id IN (SELECT id FROM tbl_po WHERE vendor_id IN ($sVendors))) ";
			}
*/

			if ($Brand > 0)
				$sConditions .= " AND (brand_id='$Brand' OR po_id IN (SELECT id FROM tbl_po WHERE brand_id='$Brand')) ";

			else if ($Status != "")
			{
					$sConditions .= " AND brand_id IN ($sBrands) ";
/*
				if ($Vendor > 0)
					$sConditions .= " AND (po_id='0' OR po_id IN (SELECT id FROM tbl_po WHERE brand_id IN ($sBrands))) ";

				else if (@strpos($sEmail, "@apparelco.com") !== FALSE || @strpos($sEmail, "@3-tree.com") !== FALSE)
					$sConditions .= " AND (brand_id IN ($sBrands) OR po_id IN (SELECT id FROM tbl_po WHERE brand_id IN ($sBrands))) ";

				else
					$sConditions .= " AND (brand_id IN ($sBrands) OR po_id IN (SELECT id FROM tbl_po WHERE brand_id IN ($sBrands))) ";
*/
			}

			if ($Po != "")
			{
				$sConditions .= " AND (";


				$sSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '%{$Po}%'";

				 if ($Vendor > 0)
					$sSQL .= " AND vendor_id='$Vendor' ";

				else
					$sSQL .= " AND FIND_IN_SET(vendor_id, '$sVendors') ";

				if ($Brand > 0)
					$sSQL .= " AND brand_id='$Brand' ";

				else
					$sSQL .= " AND FIND_IN_SET(brand_id, '$sBrands') ";

				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					$iPoId = $objDb->getField($i, 0);

					if ($i > 0)
						$sConditions .= " OR ";

					$sConditions .= "po_id='$iPoId' OR FIND_IN_SET('$iPoId', additional_pos) ";
				}

				$sConditions .= ") ";
			}

			if ($Style != "")
			{
				$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE style_id IN (SELECT id FROM tbl_styles WHERE style LIKE '%$Style%'";

				if ($Brand > 0)
					$sSQL .= " AND sub_brand_id='$Brand') ";

				else
					$sSQL .= " AND FIND_IN_SET(sub_brand_id, '$sBrands')) ";

				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );
				$sPos   = "";

				for ($i = 0; $i < $iCount; $i ++)
					$sPos .= (",".$objDb->getField($i, 0));

				if ($sPos != "")
				{
					$sPos = substr($sPos, 1);

					$sConditions .= " AND (po_id IN ($sPos) OR style_id IN (SELECT id FROM tbl_styles WHERE style LIKE '%$Style%' ";

					if ($Brand > 0)
						$sConditions .= " AND sub_brand_id='$Brand')) ";

					else
						$sConditions .= " AND FIND_IN_SET(sub_brand_id, '$sBrands'))) ";
				}

				else
				{
					$sConditions .= " AND style_id IN (SELECT id FROM tbl_styles WHERE style LIKE '%$Style%' ";

					if ($Brand > 0)
						$sConditions .= " AND sub_brand_id='$Brand') ";

					else
						$sConditions .= " AND FIND_IN_SET(sub_brand_id, '$sBrands')) ";
				}
			}


			if ($StyleId > 0)
			{
				$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE style_id='$StyleId'";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );
				$sPos   = "";

				for ($i = 0; $i < $iCount; $i ++)
					$sPos .= (",".$objDb->getField($i, 0));

				if ($sPos != "")
				{
					$sPos = substr($sPos, 1);

					$sConditions .= " AND (po_id IN ($sPos) OR style_id='$StyleId') ";
				}

				else
					$sConditions .= " AND style_id='$StyleId' ";
			}


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


			$sSQL = ("SELECT id, user_id, audit_code, po_id, style_id, audit_date, start_time, end_time, audit_stage, audit_result, total_gmts, report_id, dhu, colors, published,
			                 (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
			                 IF(po_id='0', 'N/A', (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id)) AS _Po,
			                 (SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line,
			                 (SELECT ".(($Status == "") ? 'code' : 'report')." FROM tbl_reports WHERE id=tbl_qa_reports.report_id) AS _ReportType
			          FROM tbl_qa_reports
			          $sConditions
			          ORDER BY audit_date DESC, id DESC
			          LIMIT $iStart, $iLimit");
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iAuditCode   = $objDb->getField($i, 'id');
				$iAuditor     = $objDb->getField($i, 'user_id');
				$sAuditCode   = $objDb->getField($i, 'audit_code');
				$iPoId        = $objDb->getField($i, 'po_id');
				$sPo          = $objDb->getField($i, '_Po');
				$iStyle       = $objDb->getField($i, "style_id");
				$sColors      = $objDb->getField($i, "colors");
				$sVendor      = $objDb->getField($i, '_Vendor');
				$sAuditDate   = $objDb->getField($i, 'audit_date');
				$sStartTime   = $objDb->getField($i, 'start_time');
				$sEndTime     = $objDb->getField($i, 'end_time');
				$sAuditStage  = $objDb->getField($i, 'audit_stage');
				$sAuditResult = $objDb->getField($i, 'audit_result');
				$sReportType  = $objDb->getField($i, '_ReportType');
				$iReportId    = (int)$objDb->getField($i, 'report_id');
				$iSampleSize  = (int)$objDb->getField($i, "total_gmts");
				$fDhu         = (float)$objDb->getField($i, "dhu");
				$sLine        = $objDb->getField($i, "_Line");
				$sPublished   = $objDb->getField($i, "published");


				$sAuditDir = $sAuditDate;
				$iQuantity = 0;

				if ($Status == "")
				{
					@list($iStartHour, $iStartMinutes) = @explode(":", $sStartTime);
					@list($iEndHour, $iEndMinutes)     = @explode(":", $sEndTime);

					if ($iStartHour >= 12)
					{
						if ($iStartHour > 12)
							$iStartHour -= 12;

						$sStartAmPm  = "PM";
					}

					else
						$sStartAmPm = "AM";


					if ($iEndHour >= 12)
					{
						if ($iEndHour > 12)
							$iEndHour -= 12;

						$sEndAmPm  = "PM";
					}

					else
						$sEndAmPm = "AM";


					$sStartTime      = (str_pad($iStartHour, 2, '0', STR_PAD_LEFT).":".str_pad($iStartMinutes, 2, '0', STR_PAD_LEFT)." ".$sStartAmPm);
					$sEndTime        = (str_pad($iEndHour, 2, '0', STR_PAD_LEFT).":".str_pad($iEndMinutes, 2, '0', STR_PAD_LEFT)." ".$sEndAmPm);
					$sAuditStageText = "";
					$sAuditResult    = "";
					$iDefects        = 0;
					$sBrand          = "";
					$sPicture        = "";

					$sPo    = ((@strpos($sPo, "||") !== FALSE) ? substr($sPo, 0, strpos($sPo, "||")) : "");
					$sStyle = (($iStyle > 0) ? getDbValue("style", "tbl_styles", "id='$iStyle'") : "");

					if ($iPoId > 0)
						$iQuantity = formatNumber(getDbValue("quantity", "tbl_po", "id='$iPoId'"), false);
				}


				else
				{
					@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

					$sPicture       = "";
					$sPo            = str_replace("||", " ", $sPo);
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

						$sPicture = "";
						$sCode    = "";

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


							if ($sPicture == "" && @strpos($sDefectPicture, "_{$sCode}_") !== FALSE)
								$sPicture = $sDefectPicture;
						}


						if ($sPicture != "")
						{
							if (!@file_exists($sQuondaDir.'thumbs/'.@basename($sPicture)))
							{
								@mkdir($sQuondaDir.'thumbs/');

								createImage(($sQuondaDir.@basename($sPicture)), ($sQuondaDir.'thumbs/'.@basename($sPicture)), 240, 180);
							}


							$sPicture = (SITE_URL.str_ireplace(ABSOLUTE_PATH, "", $sQuondaDir).'thumbs/'.@basename($sPicture));
						}
					}


					if ($sPicture == "" && $iStyle > 0)
					{
						$sSketchFile = getDbValue("sketch_file", "tbl_styles", "id='$iStyle'");

						if ($sSketchFile != "" && @file_exists(ABSOLUTE_PATH.STYLES_SKETCH_DIR.$sSketchFile))
						{
							if (!@file_exists(ABSOLUTE_PATH.STYLES_SKETCH_DIR."thumbs/".$sSketchFile))
								createImage((ABSOLUTE_PATH.STYLES_SKETCH_DIR.$sSketchFile), (ABSOLUTE_PATH.STYLES_SKETCH_DIR."thumbs/".$sSketchFile), 160, 160);

							$sPicture = (SITE_URL.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile);
						}
					}


					$sStartTime   = "00:00:00";
					$sEndTime     = "00:00:00";
					$sEtdRequired = "";


					if ($iReportId == 7)
						$sReportType = "Adidas";

					else if ($iReportId == 11)
						$sReportType = "M&S";

					else if (@strpos($sReportType, " - ") !== FALSE)
						$sReportType = substr($sReportType, 0, strpos($sReportType, " - "));


					if ($iReportId == 6)
						$iDefects = (int)getDbValue("SUM(defects)", "tbl_gf_report_defects", "audit_id='$iAuditCode'");

					else
						$iDefects = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditCode'");


					if ($iPoId > 0)
					{
						if ($iStyle == 0)
							$iStyle = getDbValue("style_id", "tbl_po_colors", "po_id='$iPoId'");

							$sEtdRequired = getDbValue("etd_required", "tbl_po_colors", "po_id='$iPoId'");
					}


					$sSQL = "SELECT style, sub_brand_id, sub_season_id FROM tbl_styles WHERE id='$iStyle'";
					$objDb2->query($sSQL);

					$sStyle  = $objDb2->getField(0, 0);
					$iBrand  = $objDb2->getField(0, 1);
					$iSeason = $objDb2->getField(0, 2);


					$sBrand          = getDbValue("brand", "tbl_brands", "id='$iBrand'");
					$sSeason         = getDbValue("season", "tbl_seasons", "id='$iSeason'");
					$sAuditStageText = $sAuditStagesList[$sAuditStage];
				}


				$sAudits[] = array("AuditCode"      => $sAuditCode,
				                   "Vendor"         => $sVendor,
				                   "AuditDate"      => $sAuditDate,
				                   "StartTime"      => $sStartTime,
				                   "EndTime"        => $sEndTime,
				                   "ReportType"     => $sReportType,
				                   "AuditStage"     => $sAuditStageText,
				                   "AuditStageCode" => $sAuditStage,
				                   "AuditResult"    => $sAuditResult,
				                   "SampleSize"     => $iSampleSize,
				                   "Defects"        => $iDefects,
				                   "AuditorId"      => $iAuditor,
				                   "Po"             => $sPo,
				                   "Style"          => $sStyle,
				                   "Brand"          => $sBrand,
				                   "Season"         => $sSeason,
				                   "EtdRequired"    => (($sEtdRequired == "") ? "N/A" : formatDate($sEtdRequired)),
				                   "Dhu"            => $fDhu,
				                   "Picture"        => $sPicture,
				                   "Colors"         => $sColors,
				                   "Quantity"       => $iQuantity,
				                   "ReportId"       => $iReportId,
				                   "Line"           => $sLine,
				                   "AuditorMd5Id"   => @md5($iAuditor),
				                   "AuditDir"       => $sAuditDir,
				                   "Color"          => $sStageColorsList[$sAuditStage],
								   "Published"      => $sPublished);
			}


			$aResponse['Status'] = "OK";
			$aResponse['Audits'] = $sAudits;
		}
	}


	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($_REQUEST);

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>