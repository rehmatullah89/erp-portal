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
	$objDb2      = new Database( );

	$User        = IO::intValue('User');
	$Status      = IO::strValue('Status');
	$FromDate    = IO::strValue('FromDate'); //smart search
	$ToDate      = IO::strValue('ToDate'); //smart search
	$Vendor      = IO::intValue('Vendor'); //smart search
	$Brand       = IO::intValue('Brand');
	$ReportType  = IO::strValue('ReportType');
	$AuditStage  = IO::strValue('AuditStage'); //smart search
	$AuditResult = IO::strValue('AuditResult');
	$AuditCode   = IO::strValue('AuditCode');
	$Po          = IO::strValue('Po'); //smart search
	$Style       = IO::strValue('Style'); //smart search
	
	$DR       = IO::strValue('DR'); //smart search
	$Location       = IO::strValue('Location'); //smart search: pakistan
	$Keywords       = IO::strValue('Keywords'); //smart search: for now look into qa_comments
	//print_r($_GET);

	$aResponse = array( );


	if ($User == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid User ID";
	}

	else
	{
		$sStatus = getDbValue("status", "tbl_users", "id='$User'");

		if ($sStatus != "A")
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "User Account is Disabled";
		}

		else
		{
			if ($Status  == "" && $FromDate == "" && $ToDate == "" && $Vendor == "" && $Brand == "" && $ReportType == "" && $AuditStage == "" && $AuditResult == "" && $AuditCode == "" && $Po == "" && $Style == "")
			{
				$FromDate = date("Y-m-d");
				$ToDate   = date("Y-m-d");
			}


			$sAudits     = array( );
			$sConditions = "WHERE ";

			if ($Status == "")
				//$sConditions .= " user_id='$User' AND approved='Y' AND (audit_result='' OR ISNULL(audit_result)) AND FIND_IN_SET(report_id, '1,2,3,4,5,6,7,8,11,12') ";
//				$sConditions .= "approved='Y' AND (audit_result='' OR ISNULL(audit_result)) AND FIND_IN_SET(report_id, '1,2,3,4,5,6,7,8,11,12') ";
				$sConditions .= "approved='Y' AND (audit_result IS NOT NULL) AND FIND_IN_SET(report_id, '1,2,3,4,5,6,7,8,11,12') ";
			else
				$sConditions .= " audit_result!=''";

			if ($AuditCode != "")
				$sConditions .= " AND audit_code LIKE '%$AuditCode%' ";

			if ($Keywords != "")
				$sConditions .= " AND qa_comments LIKE '%$Keywords%' ";

			if ($DR != "")
				$sConditions .= " AND dhu >= '$DR' ";

			if ($FromDate != "" && $ToDate != "")
				$sConditions .= " AND (audit_date BETWEEN '$FromDate' AND '$ToDate') ";
			//else
				//$sConditions .= " AND (audit_date BETWEEN '$FromDate' AND '$ToDate') ";

			 if ($Vendor > 0)
				$sConditions .= " AND vendor_id='$Vendor' ";

			else
			{
				/*$sVendors = getDbValue("vendors", "tbl_users", "id='$User'");

				if ($Brand > 0)
					$sConditions .= " AND vendor_id IN ($sVendors) ";

				else
					$sConditions .= " AND (po_id='0' OR vendor_id IN ($sVendors)) ";
				*/
			}

			if ($Brand > 0){
				//$sConditions .= " AND po_id IN (SELECT id FROM tbl_po WHERE brand_id='$Brand') ";
			}
			else if ($Status != "")
			{
				/*
				$sBrands  = getDbValue("brands", "tbl_users", "id='$User'");

				if ($Vendor > 0)
					$sConditions .= " AND po_id IN (SELECT id FROM tbl_po WHERE brand_id IN ($sBrands)) ";

				else
					$sConditions .= " AND (po_id='0' OR po_id IN (SELECT id FROM tbl_po WHERE brand_id IN ($sBrands))) ";
				*/	
			}

			//if ($ReportType != "")
			//	$sConditions .= " AND FIND_IN_SET(report_id, '$ReportType') ";

			if ($AuditStage != "")
				$sConditions .= " AND FIND_IN_SET(audit_stage, '$AuditStage') ";

			//if ($AuditResult != "")
				//$sConditions .= " AND FIND_IN_SET(audit_result, '$AuditResult') ";

			if ($Po != "")
			{
				$sConditions .= " AND (";


				$sSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '%$Po%'";
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
				/*
				$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE style_id IN (SELECT id FROM tbl_styles WHERE style LIKE '%$Style%')";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );
				$sPos   = "";

				for ($i = 0; $i < $iCount; $i ++)
					$sPos .= (",".$objDb->getField($i, 0));

				if ($sPos != "")
				{
					$sPos = substr($sPos, 1);

					$sConditions .= " AND (po_id IN ($sPos) OR style_id IN (SELECT id FROM tbl_styles WHERE style LIKE '%$Style%')) ";
				}

				else
					$sConditions .= " AND style_id IN (SELECT id FROM tbl_styles WHERE style LIKE '%$Style%') ";
					
					*/
				
				$sConditions .= " AND style_id IN (SELECT id FROM tbl_styles WHERE style LIKE '%$Style%') ";

			}

/*			$sSQL = ("SELECT id, user_id, audit_code, po_id, style_id, audit_date, start_time, end_time, audit_stage, audit_result, total_gmts, report_id, dhu, colors,
			                 (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
			                 (SELECT CONCAT(latitude,'||',longitude,'||',location_time,'||',location_address) FROM tbl_user WHERE id=tbl_qa_reports.user_id) AS _Position,
			                 (SELECT CONCAT(order_no, '||', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
			                 (SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line,
			                 (SELECT ".(($Status == "") ? 'code' : 'report')." FROM tbl_reports WHERE id=tbl_qa_reports.report_id) AS _ReportType
			          FROM tbl_qa_reports
			          $sConditions
			          ORDER BY audit_date DESC, id DESC");
*/

		$sSQL = ("SELECT id, user_id, vendor_id,brand_id, audit_code, po_id, style_id, audit_date, start_time, end_time, audit_stage, audit_result, total_gmts, report_id, dhu, colors,
			                 (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
			                 (SELECT CONCAT(latitude,'||',longitude,'||',location_time,'||',location_address) FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Position,
			                 (SELECT CONCAT(order_no, '||', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
			                 (SELECT tbl_brands.brand FROM tbl_po,tbl_brands WHERE tbl_po.id=tbl_qa_reports.po_id and tbl_brands.id=tbl_po.brand_id) AS _Brand,
			                 (SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line,
			                 (SELECT report FROM tbl_reports WHERE id=tbl_qa_reports.report_id) AS _ReportType,
			                 (SELECT sum(defects) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id) AS _DefectCount
			          FROM tbl_qa_reports
			          $sConditions
			          ORDER BY audit_date DESC, id DESC");
			          
//remove userid condition			          
			          //echo $sSQL; 	exit(0);
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
//echo $iCount; exit(0);
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iAuditCode   = $objDb->getField($i, 'id');
				$iAuditor     = $objDb->getField($i, 'user_id');
				$sAuditCode   = $objDb->getField($i, 'audit_code');
				$iPoId        = $objDb->getField($i, 'po_id');
				$sPo          = $objDb->getField($i, '_Po');
				$sPosition    = $objDb->getField($i, '_Position');
				$iStyle       = $objDb->getField($i, "style_id");
				
				$iVendor       = $objDb->getField($i, "vendor_id");
				$iBrand       = $objDb->getField($i, "brand_id");
				
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
				$sDefectCount        = $objDb->getField($i, "_DefectCount");
				$sBrand        = $objDb->getField($i, "_Brand");


				if ($sColors == "")
					$sColors = "N/A";

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


					$sAuditDate   = formatDate($sAuditDate);
					$sStartTime   = (str_pad($iStartHour, 2, '0', STR_PAD_LEFT).":".str_pad($iStartMinutes, 2, '0', STR_PAD_LEFT)." ".$sStartAmPm);
					$sEndTime     = (str_pad($iEndHour, 2, '0', STR_PAD_LEFT).":".str_pad($iEndMinutes, 2, '0', STR_PAD_LEFT)." ".$sEndAmPm);
					//$sAuditStage  = "N/A";
					//$sAuditResult = "N/A";
					$iDefects     = $sDefectCount;
					//$sBrand       = "N/A";
					//$sDefectPic   = "N/A";
					

					$sPo    = ((@strpos($sPo, "||") !== FALSE) ? substr($sPo, 0, strpos($sPo, "||")) : "N/A");
					$sStyle = (($iStyle > 0) ? getDbValue("style", "tbl_styles", "id='$iStyle'") : "N/A");
					$sStyle_image = (($iStyle > 0) ? getDbValue("sketch_file", "tbl_styles", "id='$iStyle'") : "N/A");
					
					if ($iReportId == 6)
						$iDefects = (int)getDbValue("SUM(defects)", "tbl_gf_report_defects", "audit_id='$iAuditCode'");

					else
						$iDefects = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditCode'");


					if ($iPoId > 0)
						$iQuantity = formatNumber(getDbValue("quantity", "tbl_po", "id='$iPoId'"), false);
				}


				else
				{
				/*
					$sDefectPic = "N/A";
					$sPo        = str_replace("||", " ", $sPo);


					@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

					$sAuditPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*");

					if (count($sAuditPictures) > 0)
					{
						$sPictures = array( );
						$iLength   = strlen($sAuditCode);

						foreach ($sAuditPictures as $sDefectPicture)
						{
							if (substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_001" ||
							    substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_00_" ||
							    strlen(@basename($sDefectPicture)) < ($iLength + 6))
								continue;

							$sParts = @explode("_", $sDefectPicture);
							$sCode  = trim($sParts[1]);

							if (@array_key_exists($sCode, $sPictures))
								$sPictures[$sCode] ++;

							else
								$sPictures[$sCode] = 1;
						}

						@arsort($sPictures);

						$sDefectPic = "";
						$sCode      = "";

						foreach ($sPictures as $sDefectCode => $iDefectCount)
						{
							if ($sCode == "")
								$sCode = $sDefectCode;
						}

						foreach ($sAuditPictures as $sDefectPicture)
						{
							if (substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_001" ||
							    substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_00_" ||
							    strlen(@basename($sDefectPicture)) < ($iLength + 6))
								continue;


							if ($sDefectPic == "" && @strpos($sDefectPicture, "_{$sCode}_") !== FALSE)
								$sDefectPic = $sDefectPicture;
						}


						if ($sDefectPic == "")
							$sDefectPic = "N/A";

						else
							$sDefectPic = (SITE_URL.str_replace('../', '', $sDefectPic));
					
					
					}




					$sAuditDate = formatDate($sAuditDate);
					$sStartTime = "00:00:00";
					$sEndTime   = "00:00:00";


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


					if ($iStyle == 0)
						$iStyle = getDbValue("style_id", "tbl_po_colors", "po_id='$iPoId'");


					$sSQL = "SELECT style, sub_brand_id FROM tbl_styles WHERE id='$iStyle'";
					$objDb2->query($sSQL);

					$sStyle = $objDb2->getField(0, 0);
					$iBrand = $objDb2->getField(0, 1);


					$sBrand = getDbValue("brand", "tbl_brands", "id='$iBrand'");

					switch ($sAuditStage)
					{
						case "B"  : $sAuditStage = "Batch"; break;
						case "C"  : $sAuditStage = "Cutting"; break;
						case "I"  : $sAuditStage = "In-Process"; break;
						case "P"  : $sAuditStage = "Pre-Final"; break;
						case "F"  : $sAuditStage = "Final"; break;
						case "O"  : $sAuditStage = "Output"; break;
						case "S"  : $sAuditStage = "Sorting"; break;
						case "ST" : $sAuditStage = "Stitching"; break;
						case "FI" : $sAuditStage = "Finishing"; break;
						case "OL" : $sAuditStage = "Off Loom"; break;
						case "SK" : $sAuditStage = "Stock"; break;
					}
				
				
				
				*/}


				$dDataObject["audit_code"] = $sAuditCode;
				$dDataObject["vendor_str"] = $sVendor;
				$dDataObject["vendor_id"] = $iVendor;
				$dDataObject["audit_date"] = $sAuditDate;
				$dDataObject["start_time"] = $sStartTime;
				$dDataObject["end_time"] = $sEndTime;
				
				$dDataObject["report_type_str"] = $sReportType;
				$dDataObject["report_type_id"] = $iReportId;
				
				$dDataObject["audit_stage_str"] = $sAuditStage;
				$dDataObject["audit_result_str"] = $sAuditResult;
				$dDataObject["sample_size_count"] = $iSampleSize;
				$dDataObject["defect_count"] = $iDefects;
				$dDataObject["auditor_id"] = $iAuditor;
				$dDataObject["po_str"] = $sPo;
				$dDataObject["brand_str"] = $sBrand;
				$dDataObject["brand_id"] = $iBrand;
				$dDataObject["style_str"] = $sStyle;
				$dDataObject["dhu_count"] = $fDhu;
				//$sStyle_image
				$dDataObject["style_image_url"] = SITE_URL.STYLES_SKETCH_DIR.$sStyle_image;
//				$dDataObject["style_image_url"] = $sDefectPic;
				$dDataObject["defect_image_url"] = $sDefectPic;
				$dDataObject["colors_str"] = $sColors;
				$dDataObject["quantity_count"] = $iQuantity;
				
				
				$position = explode("||",$sPosition);
				
				$dDataObject["auditor_latitude"] = $position[0];
				$dDataObject["auditor_longitude"] = $position[1];
				$dDataObject["auditor_location_time"] = $position[2];
				$dDataObject["auditor_location_address"] = $position[3];
				
				$dDataObject["production_line_str"] = $sLine;

				$sAudits[] = $dDataObject;

				//$sAudits[] = "{$sAuditCode}||{$sVendor}||{$sAuditDate}||{$sStartTime}||{$sEndTime}||{$sReportType}||{$sAuditStage}||{$sAuditResult}||{$iSampleSize}||{$iDefects}||{$iAuditor}||{$sPo}||{$sBrand}||{$sStyle}||{$fDhu}||{$sDefectPic}||{$sColors}||{$iQuantity}||{$iReportId}||{$sLine}";
			}


			$aResponse['Status'] = "OK";
			//$aResponse['Audits']  = ((count($sAudits) > 0) ? @implode("|-|", $sAudits) : "");
			$aResponse['Audits']  = $sAudits;
		}
	}

	print @json_encode($aResponse);


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>