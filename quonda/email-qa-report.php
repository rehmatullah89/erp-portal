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
	$objDb3      = new Database( );

	$Id          = IO::intValue('Id');
	$Brand       = IO::intValue('Brand');
	$ReportId    = IO::intValue('ReportId');
	$Referer     = IO::strValue('Referer');
	$AuditStage  = IO::strValue('AuditStage');
	$Recipients  = IO::getArray("Recipients");
        $OtherEmails = IO::strValue('OtherEmails');
	
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

	else if ($ReportId == 13)
		@include($sBaseDir."includes/quonda/export-costco-report.php");

	else if (@in_array($ReportId, array(15, 16, 17, 18, 21, 22)))
		@include($sBaseDir."includes/quonda/export-qmip-report.php");

	else if ($ReportId == 20 || $ReportId == 23)
		@include($sBaseDir."includes/quonda/export-kik-report.php");

	else if ($ReportId == 24)
		@include($sBaseDir."includes/quonda/export-sampling-report.php");

    else if ($ReportId == 25)
    {
    	if ($AuditStage == "F")
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
	
    else
        @include($sBaseDir."includes/quonda/export-other-report.php");	
*/


	$sSQL = "SELECT *,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor
	         FROM tbl_qa_reports
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	$iAuditor       = $objDb->getField(0, "user_id");
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
	//$fDhu           = $objDb->getField(0, "dhu");
	$iTotalGmts     = $objDb->getField(0, "total_gmts");
	$iShipQty       = $objDb->getField(0, "ship_qty");
	$sComments      = $objDb->getField(0, "qa_comments");
	$iMasterId      = $objDb->getField(0, "master_id");
	
        $iDefects       = (int)getDbValue("SUM(IF(nature > 0, defects, 0))", "tbl_qa_report_defects", "audit_id='$Id'");
        $fDhu           = @round(( ($iDefects / $iTotalGmts) * 100), 2);

	$sAuditStage    = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");
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
	$sDefectsSummary = "<b>Defects details:</b><br />";
	$sSpecsSummary   = "";
	
	$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$Id' ORDER BY id";
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
				case 0 : $sNature = "Minor"; break;
				case 1 : $sNature = "Major"; break;
				case 2 : $sNature = "Critical"; break;
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
	
	
	$iSpecsSamples = getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id'");
	
	if ($iSpecsSamples > 0)
	{
		$iPointsEvaluated      = 0;
		$iPointsOutOfTolerance = 0;
		$sSizes                = getDbValue("GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',')", "tbl_qa_report_samples", "audit_id='$Id'");
		$iSizes                = @explode(",", $sSizes);
		$sSizeFindings         = array( );
			

		$sSQL = "SELECT qrs.size_id ,qrs.audit_id, qrs.sample_no, qrss.point_id, qrss.findings
				 FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
				 WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id
				 ORDER BY qrss.point_id, qrs.sample_no";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		
		for ($i = 0; $i < $iCount; $i ++)
		{
			$iSize      = $objDb->getField($i, 'size_id');
			$iPoint     = $objDb->getField($i, 'point_id');
			$iSampleNo  = $objDb->getField($i, 'sample_no');
			$sFindings  = $objDb->getField($i, 'findings');

			$sSizeFindings["{$iSize}-{$iSampleNo}-{$iPoint}"] = $sFindings;
		}


		foreach ($iSizes as $iSize)
		{
			$sSize           = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");					
			$iSizeSamples    = getDbValue('COUNT(DISTINCT sample_no)', 'tbl_qa_report_samples', "audit_id='$Id' AND size_id='$iSize'");
			$iSizePoints     = getDbValue("COUNT(point_id)", "tbl_style_specs", "style_id='$iStyle' AND size_id='$iSize' AND version='0' AND specs!='0' AND specs!=''");
			$iSamplesChecked = getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSize'");
			
			
			$iPointsEvaluated += ($iSizeSamples * $iSizePoints);
		
			if ($iSamplesChecked > 5)
				$iSamplesChecked = 5;


			$sSQL = "SELECT point_id, specs,
							(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance
					 FROM tbl_style_specs
					 WHERE style_id='$iStyle' AND size_id='$iSize' AND version='0' AND specs!='0' AND specs!=''
					 ORDER BY id";
			$objDb->query($sSQL);
			
			$iCount = $objDb->getCount( );				
			
			for ($i = 0; $i < $iCount; $i ++)
			{
				for ($j = 1; $j <= $iSamplesChecked; $j ++)
				{
					$iPoint     = $objDb->getField($i, 'point_id');
					$sSpecs     = $objDb->getField($i, 'specs');
					$sTolerance = $objDb->getField($i, '_Tolerance');
					
					$sFinding   = $sSizeFindings["{$iSize}-{$j}-{$iPoint}"];


					if ($sFinding != "" && strtolower($sFinding) != "ok" && strtolower($sFinding) != "0")
					{
						$fMeaseuredValue   = $sFinding;
						$fSpecValue        = ConvertToFloatValue($sSpecs);
						$fTolerance        = parseTolerance($sTolerance);

						$fNTolerance       = $fTolerance[0];
						$fPTolerance       = $fTolerance[1];

						$PositiveTolerance = $fSpecValue + abs($fPTolerance);
						$NegativeTolerance = $fSpecValue - abs($fNTolerance);

						if($fMeaseuredValue >= $NegativeTolerance && $fMeaseuredValue <= $PositiveTolerance)
							continue;

						$iPointsOutOfTolerance++;
					}
				}
			}
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
		$sResultText  = "<b style='color:#aaaf00;'>Accepted</b>";
	else if ($sAuditResult == "H")
		$sResultText  = "<b style='color:#fe7100;'>Hold</b>";
	else if ($sAuditResult == "R")
		$sResultText  = "<b style='color:#fe7100;'>Re-Inspection</b>";
	
        if ($_SESSION["UserType"] == "MGF")
        {

            switch ($sAuditResult)
            {
                    case "P" : $sAuditResult = "Accepted"; break;
                    case "F" : $sAuditResult = "Rejected"; break;
                    case "H" : $sAuditResult = "Hold"; break;
                    case "R" : $sAuditResult = "Re-Inspection"; break;
            }

        }
        else if ($_SESSION["UserType"] == "LEVIS")
        {

            switch ($sAuditResult)
            {
                    case "P" : $sAuditResult = "Pass"; break;
                    case "F" : $sAuditResult = "Fail"; break;
                    case "N" : $sAuditResult = "Fail-NV"; break;
                    case "E" : $sAuditResult = "Exception"; break;
                    case "R" : $sAuditResult = "Rescreen"; break;
            }

        }
        else{

            switch ($sAuditResult)
            {
                    case "A" : $sAuditResult = "Pass"; break;
                    case "B" : $sAuditResult = "Pass"; break;
                    case "C" : $sAuditResult = "Fail"; break;
                    case "P" : $sAuditResult = "Pass"; break;
                    case "F" : $sAuditResult = "Fail"; break;
                    case "H" : $sAuditResult = "Hold"; break;
                    case "R" : $sAuditResult = "Re-Inspection"; break;
            }                    
        }

	$sBody = @file_get_contents("{$sBaseDir}emails/qa-report-mgf.txt");
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
	$sBody = @str_replace("[SampleSize]", $iTotalGmts, $sBody);
	$sBody = @str_replace("[ShipQty]", $iShipQty, $sBody);
	$sBody = @str_replace("[MasterId]", $iMasterId, $sBody);

/*
	if ($ReportId == 28)
	{
		$sLanguages  = array('de', 'tr', 'en'); 
		
		foreach ($sLanguages as $sLanguage)
		{			
			$sSQL = ("SELECT name, email FROM tbl_qa_emails WHERE FIND_IN_SET(id,'".@implode(",", $Recipients)."') AND language='$sLanguage' ORDER BY name");
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
			
			if ($iCount > 0)
			{
				$objEmail = new PHPMailer( );

			//	$objEmail->FromName = "Triple Tree Customer Portal";
				$objEmail->Subject  = ("Found ".formatNumber($fDhu)."% DR at {$sAuditStage} in {$sLine} line");

				$objEmail->MsgHTML($sBody);

	
				for ($i = 0; $i < $iCount; $i ++)
				{
					$sName  = $objDb->getField($i, "name");
					$sEmail = $objDb->getField($i, "email");

					$objEmail->AddAddress($sEmail, $sName);
				}
				
				
				$sPdfFile = (ABSOLUTE_PATH.TEMP_DIR."S{$Id}-QA-Report-{$sLanguage}.pdf");
				
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
			$objEmail->Subject  = ("Inspection '{$sAuditResult}' at {$sAuditStage} for Style {$sStyle} - [{$sAuditResult}]");
		
		else
			$objEmail->Subject  = ("Found ".formatNumber($fDhu)."% DR for Style {$sStyle} at {$sAuditStage} - [{$sAuditResult}]");

		$objEmail->MsgHTML($sBody);

		
                if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
                    $sSQL = ("SELECT name, email FROM tbl_qa_emails WHERE FIND_IN_SET(id,'".@implode(",", $Recipients)."') ORDER BY name");   
                else
                {
                    if(trim($OtherEmails) != "")
                    {
                        $OtherEmails = str_replace(" ", "", $OtherEmails);
                        $OtherEmails = explode(",", $OtherEmails);
                        
                        foreach($OtherEmails as $sEmail)
                        {
                            $sEmail = @trim($sEmail);
                            
                            if (filter_var($sEmail, FILTER_VALIDATE_EMAIL))
                            {
                                $iEmail = explode("@", $sEmail);
                                $sName  = @$iEmail[0];

                                $objEmail->AddAddress($sEmail, $sName);
                            }
                        }
                    }
                    $sSQL = ("SELECT name, email FROM tbl_users WHERE FIND_IN_SET(id,'".@implode(",", $Recipients)."') ORDER BY name");   
                }
                $objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sName  = $objDb->getField($i, "name");
			$sEmail = $objDb->getField($i, "email");

			$objEmail->AddAddress($sEmail, $sName);
		}

//		$objEmail->AddAttachment($sPdfFile, @basename($sPdfFile));
		$objEmail->Send( );
		
		@unlink($sPdfFile);
	}

	
	redirect($Referer, "QA_EMAIL_SENT");


	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>