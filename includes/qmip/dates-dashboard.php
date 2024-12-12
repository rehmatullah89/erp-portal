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
	
	if ($AuditStage != "")
		$sConditions .= " WHERE audit_stage='$AuditStage' ";

	else
		$sConditions = " WHERE audit_stage!='' ";


	if ($Customer != "")
	{
		if ($Brand > 0 && $Vendor > 0)
			$sConditions .= " AND po_id IN (SELECT id FROM tbl_po WHERE customer LIKE '$Customer' AND vendor_id='$Vendor' AND brand_id='$Brand') ";

		else if ($Brand > 0)
			$sConditions .= " AND po_id IN (SELECT id FROM tbl_po WHERE customer LIKE '$Customer' AND brand_id='$Brand' AND FIND_IN_SET(vendor_id, '{$_SESSION['Vendors']}')) ";

		else if ($Vendor > 0)
			$sConditions .= " AND po_id IN (SELECT id FROM tbl_po WHERE customer LIKE '$Customer' AND FIND_IN_SET(brand_id, '{$_SESSION['Brands']}') AND vendor_id='$Vendor') ";

		else
			$sConditions .= " AND po_id IN (SELECT id FROM tbl_po WHERE customer LIKE '$Customer' AND FIND_IN_SET(brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(vendor_id, '{$_SESSION['Vendors']}')) ";
	}


	$sConditions .= " AND FIND_IN_SET(report_id, '$sQmipReports') AND FIND_IN_SET(audit_stage, '$sAuditStages') ";


	if ($Brand > 0)
		$sConditions .= " AND style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' $sStyleCategoriesSql)";

	else
	{
		if (@strpos($_SESSION["Email"], "apparelco.com") === FALSE && @strpos($_SESSION["Email"], "3-tree.com") === FALSE)
			$sConditions .= " AND style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') $sStyleCategoriesSql)";

		else
			$sConditions .= " AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') $sStyleCategoriesSql))";
	}


	if ($Region > 0)
		$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region') ";

	if ($Auditor > 0)
		$sConditions .= " AND (user_id='$Auditor' OR group_id IN (SELECT id FROM tbl_auditor_groups WHERE FIND_IN_SET('$Auditor', users))) ";

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	else
		$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (audit_date BETWEEN '$FromDate' AND '$ToDate') ";
	

	if ($FromDate != "" && $ToDate != "")
	{
?>
				  <h2>Audits from <?= formatDate($FromDate) ?> to <?= formatDate($ToDate) ?></h2>
<?
	}

	else
	{
?>
				  <h2>Audits</h2>
<?
	}
?>


	  			  <div style="padding-left:5px;">
	  			    <table border="0" cellpadding="3" cellspacing="0" width="100%">
	  			      <tr>
<?
	$sStages = array( );

	$sSQL = "SELECT DISTINCT(audit_stage) FROM tbl_qa_reports $sConditions";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sStages[] = $objDb->getField($i, 0);
	
	
	$iIndex = 1;

	foreach ($sAuditStagesList as $sKey => $sValue)
	{
		if (!@in_array($sKey, $sStages))
			continue;		
?>
							<td width="11"><div style="height:11px; width:11px; background:<?= $sStageColorsList[$sKey] ?>;"></div></td>
							<td width="80"><?= $sValue ?></td>
<?
		$iIndex ++;

		if (($iIndex % 9) == 0)
		{
?>
							<td></td>
						  </tr>
						</table>

	  			    	<table border="0" cellpadding="3" cellspacing="0" width="100%">
						  <tr>
<?
		}
	}
?>
					    <td></td>
					  </tr>
				    </table>
				    </div>

				    <hr />

<?
	$sSQL = "SELECT DISTINCT(audit_date) AS _Date, GROUP_CONCAT(user_id SEPARATOR ',') AS _Auditors
	         FROM tbl_qa_reports
	         $sConditions
	         GROUP BY audit_date
	         ORDER BY audit_date DESC";

	if ($FromDate == "" && $ToDate == "")
		$sSQL .= " LIMIT 10";


	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$sDates = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sDate     = $objDb->getField($i, 0);
		$sAuditors = $objDb->getField($i, 1);

		$iAuditors = @explode(",", $sAuditors);
		$iAuditors = @array_unique($iAuditors);
		$sAuditors = @implode(",", $iAuditors);

		$sDates[] = array("Date" => $sDate, "Auditors" => $sAuditors);
	}

	$sLastDate = $sDate;



	$iVendors = array( );

	$sSQL = "SELECT DISTINCT(vendor_id) FROM tbl_qa_reports $sConditions";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$iVendors[] = $objDb->getField($i, 0);

	$sVendors = @implode(",", $iVendors);


	$sSQL = "SELECT city,
	               (SELECT code FROM tbl_countries WHERE id=tbl_vendors.country_id) AS _Country,
	               GROUP_CONCAT(id SEPARATOR ',') AS _Vendors
	        FROM tbl_vendors
	        WHERE FIND_IN_SET(id, '$sVendors')
	        GROUP BY city
	        ORDER BY city, _Country";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$sCities = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sCity    = $objDb->getField($i, "city");
		$sCountry = $objDb->getField($i, "_Country");
		$sVendors = $objDb->getField($i, "_Vendors");

		$sCities["{$sCity}, {$sCountry}"] = $sVendors;
	}
?>
				    <div style="position:relative;">
						<div style="position:relative;">
						  <div id="Grid" align="right" style="padding:21px 0px 20px 0px;">
							<table border="0" cellpadding="0" cellspacing="0" width="768">
<?
	$sSQL = "SELECT COUNT(1) FROM tbl_qa_reports $sConditions AND audit_date>='$sLastDate' GROUP BY vendor_id, audit_date, user_id";
	$objDb->query($sSQL);

	$iRows = $objDb->getCount( );


	$sSQL = "SELECT COUNT(1) FROM tbl_qa_reports $sConditions AND audit_date>='$sLastDate' GROUP BY vendor_id, audit_date";
	$objDb->query($sSQL);

	$iRows += $objDb->getCount( );


	$iRows += count($sCities);
	$iRows  = (($iRows == 0) ? 10 : $iRows);

	for ($i = 0; $i < $iRows; $i ++)
	{
?>
					  		<tr>
<?
		for ($j = 8; $j < 20; $j ++)
		{
?>
					    		<td width="32"><div style="height:34px; border-left:dotted 1px #999999; border-top:dotted 1px #999999;"></div></td>
<?
		}
?>
					  		</tr>
<?
	}
?>

					  		<tr>
<?
	for ($j = 8; $j < 20; $j ++)
	{
?>
					    		<td width="32"><div style="height:18px; border-left:dotted 1px #999999; border-top:dotted 1px #999999;"></div></td>
<?
	}
?>
							  </tr>
							</table>
						  </div>


						  <div style="position:absolute; right:0px; top:0px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							  <tr>
								<td width="150"></td>
								<td></td>

								<td width="768" style="border-bottom:solid 2px #666666;">

								  <table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
<?
	for ($i = 8; $i < 20; $i ++)
	{
?>
					          		  <td width="32"><small><?= date("ga", mktime($i, 0, 0, date("m"), date("d"), date("Y"))) ?></small></td>
<?
	}
?>
									</tr>

									<tr>
<?
	for ($i = 8; $i < 20; $i ++)
	{
?>
					          		<td width="32"><div style="width:2px; height:6px; background:#666666;"></div></td>
<?
	}
?>
									</tr>
								  </table>

								</td>
							  </tr>
<?
	$iActualRows  = 0;


	foreach ($sCities as $sCity => $sVendors)
	{
		$iVendorsList = @explode(",", $sVendors);
?>
						  <tr>
							<td align="right" bgcolor="#cccccc"><b><?= $sCity ?></b></td>
							<td style="border-right:solid 2px #666666;" height="35" bgcolor="#cccccc"></td>

							<td width="768" height="35">

							  <table border="0" cellpadding="0" cellspacing="0" width="100%" height="25">
								<tr>
								  <td width="100%" bgcolor="#e3e3e3">&nbsp;</td>
								</tr>
							  </table>

							</td>
						  </tr>
<?
		$iActualRows ++;


		for ($i = 0; $i < count($sDates); $i ++)
		{
			$sDate     = $sDates[$i]['Date'];
			$sAuditors = $sDates[$i]['Auditors'];
			$iAuditors = @explode(",", $sAuditors);


			$sConditions = " WHERE audit_date='$sDate' AND FIND_IN_SET(report_id, '$sQmipReports') AND FIND_IN_SET(audit_stage, '$sAuditStages') ";

			if ($AuditStage != "")
				$sConditions .= " AND audit_stage='$AuditStage' ";

			if ($Customer != "")
			{
				if ($Brand > 0 && $Vendor > 0)
					$sConditions .= " AND po_id IN (SELECT id FROM tbl_po WHERE customer LIKE '$Customer' AND vendor_id='$Vendor' AND brand_id='$Brand') ";

				else if ($Brand > 0)
					$sConditions .= " AND po_id IN (SELECT id FROM tbl_po WHERE customer LIKE '$Customer' AND brand_id='$Brand' AND FIND_IN_SET(vendor_id, '{$_SESSION['Vendors']}')) ";

				else if ($Vendor > 0)
					$sConditions .= " AND po_id IN (SELECT id FROM tbl_po WHERE customer LIKE '$Customer' AND FIND_IN_SET(brand_id, '{$_SESSION['Brands']}') AND vendor_id='$Vendor') ";

				else
					$sConditions .= " AND po_id IN (SELECT id FROM tbl_po WHERE customer LIKE '$Customer' AND FIND_IN_SET(brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(vendor_id, '{$_SESSION['Vendors']}')) ";
			}

			if ($Brand > 0)
				$sConditions .= " AND style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' $sStyleCategoriesSql)";

			else
				$sConditions .= " AND style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') $sStyleCategoriesSql)";

			if ($Region > 0)
				$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region') ";

			if ($Auditor > 0)
				$sConditions .= " AND (user_id='$Auditor' OR group_id IN (SELECT id FROM tbl_auditor_groups WHERE FIND_IN_SET('$Auditor', users))) ";

			else if ($sAuditors != "")
				$sConditions .= " AND user_id IN ($sAuditors) ";

			if ($Vendor > 0)
				$sConditions .= " AND vendor_id='$Vendor' ";

			else if ($sVendors != "")
				$sConditions .= " AND vendor_id IN ($sVendors) ";

			else
				$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";


			$sSQL = "SELECT id, user_id, TIME_TO_SEC(start_time) AS _StartTime, TIME_TO_SEC(end_time) AS _EndTime, audit_date, audit_code, vendor_id, brand_id, style_id, po_id,
							start_time, end_time, report_id, audit_stage, audit_result, custom_sample, total_gmts, approved, status, checked_gmts,
							(SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line,
							(SELECT category_id FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Category,
							(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id) AS _Defects,
							(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND code_id NOT IN ($sMeasurementCodes)) AS _DefectPics
					 FROM tbl_qa_reports
					 $sConditions
					 ORDER BY start_time";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			if ($iCount == 0)
				continue;


			$iActualRows ++;
?>
						  <tr>
							<td align="right" bgcolor="#f0f0f0"><b><?= formatDate($sDate) ?></b></td>
							<td style="border-right:solid 2px #666666;" height="35" bgcolor="#f0f0f0"></td>

							<td width="768" height="35">

							  <table border="0" cellpadding="0" cellspacing="0" width="100%" height="25">
								<tr>
								  <td width="100%" bgcolor="#f9f9f9">&nbsp;</td>
								</tr>
							  </table>

							</td>
						  </tr>
<?
			$iUsers = array( );

			for ($j = 0; $j < $iCount; $j ++)
			{
				$iUser = $objDb->getField($j, 'user_id');;

				if (!@in_array($iUser, $iUsers))
					$iUsers[] = $iUser;
			}


			foreach ($iAuditors as $iAuditor)
			{
				if (!@in_array($iAuditor, $iUsers))
					continue;
?>
						  <tr>
							<td align="right"><span><?= getDbValue("name", "tbl_users", "id='$iAuditor'") ?></span></td>

							<td height="35" align="right" style="border-right:solid 2px #666666; overflow:hidden;">
							  <div style="width:3px; height:2px; background:#666666;"></div>
							</td>

							<td style="overflow:hidden;">

							  <table border="0" cellpadding="0" cellspacing="0" width="100%" height="25">
								<tr bgcolor="#fcfcfc">
<?
				$iPrevious = 28800;

				for ($j = 0; $j < $iCount; $j ++)
				{
					$iUser = $objDb->getField($j, 'user_id');

					if ($iUser != $iAuditor)
						continue;


					$iAuditId     = $objDb->getField($j, 'id');
					$iStartTime   = $objDb->getField($j, '_StartTime');
					$iEndTime     = $objDb->getField($j, '_EndTime');
					$sAuditDate   = $objDb->getField($j, 'audit_date');
					$sAuditCode   = $objDb->getField($j, 'audit_code');
					$iVendorId    = $objDb->getField($j, 'vendor_id');
					$iBrandId     = $objDb->getField($j, 'brand_id');
					$iStyleId     = $objDb->getField($j, 'style_id');
					$iPoId        = $objDb->getField($j, 'po_id');
					$sStartTime   = $objDb->getField($j, 'start_time');
					$sEndTime     = $objDb->getField($j, 'end_time');
					$iReportId    = $objDb->getField($j, 'report_id');
					$sAuditStage  = $objDb->getField($j, 'audit_stage');
					$sAuditResult = $objDb->getField($j, 'audit_result');
					$sStatus      = $objDb->getField($j, 'status');
					$sLine        = $objDb->getField($j, '_Line');
					$iCategoryId  = $objDb->getField($j, '_Category');
					$sCustom      = $objDb->getField($j, 'custom_sample');
					$iQuantity    = $objDb->getField($j, 'total_gmts');
					$iDefects     = $objDb->getField($j, '_Defects');
					$iDefectPics  = $objDb->getField($j, '_DefectPics');
					$sApproved    = $objDb->getField($j, 'approved');
					$iChecked     = $objDb->getField($j, 'checked_gmts');


					$bOnGoing  = (($iChecked > 0 && $sAuditResult == "") ? true : false);


					$sSQL = "SELECT customer, order_no FROM tbl_po WHERE id='$iPoId'";
					$objDb2->query($sSQL);

					$sCustomer = $objDb2->getField(0, "customer");
					$sPo       = $objDb2->getField(0, "order_no");



					$sSQL = "SELECT style, design_no, design_name,
									(SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id) AS _Season,
									(SELECT program FROM tbl_programs WHERE id=tbl_styles.program_id) AS _Program
							 FROM tbl_styles
							 WHERE id='$iStyleId'";
					$objDb2->query($sSQL);

					$sStyle      = $objDb2->getField(0, "style");
					$sDesignNo   = $objDb2->getField(0, "design_no");
					$sDesignName = $objDb2->getField(0, "design_name");
					$sSeason     = $objDb2->getField(0, "_Season");
					$sProgram    = $objDb2->getField(0, "_Program");


					if ($iBrandId == 0)
					{
						if ($iStyleId > 0)
							$iBrandId = getDbValue("sub_brand_id", "tbl_styles", "id='$iStyleId'");

						else if ($iPoId > 0)
							$iBrandId = getDbValue("brand_id", "tbl_po", "id='$iPoId'");
					}

					if ($iStartTime > $iPrevious)
					{
						$iTime  = ($iStartTime - $iPrevious);
						$iTime /= 60;
						$iWidth = @round($iTime * 1.067);
						$iWidth = (($iWidth < 2) ? 2 : $iWidth);
?>
					          <td width="<?= $iWidth ?>"></td>
<?
					}

					$iAuditCode = substr($sAuditCode, 1);


					$iTime       = ($iEndTime - $iStartTime);
					$iTime      /= 60;
					$iWidth      = @round($iTime * 1.067);
					$iWidth      = (($iWidth < 5) ? 5 : $iWidth);
					$sBackground = "#dddddd";
					$sPictures   = array( );

					@list($sYear, $sMonth, $sDay) = @explode("-", $sDate);

					if ($sAuditStage != "")
					{
						$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*");
						$sPictures = @array_map("strtoupper", $sPictures);
						$sPictures = @array_unique($sPictures);

						if (count($sPictures) > 0)
						{
							$sTemp   = array( );
							$iLength = strlen($sAuditCode);

							foreach ($sPictures as $sPicture)
							{
								if (substr(@basename($sPicture), 0, ($iLength + 4)) == "{$sAuditCode}_001" ||
									substr(@basename($sPicture), 0, ($iLength + 4)) == "{$sAuditCode}_00_" ||
									strlen(@basename($sPicture)) < ($iLength + 6))
									continue;

								$sTemp[] = $sPicture;
							}

							$sPictures = $sTemp;
						}
					}


					$sTooltip  = "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
					$sTooltip .= "  <tr valign='top'>";
					$sTooltip .= "    <td>";
					$sTooltip .= ("     <b>Audit Time:</b> ".formatTime($sStartTime, "h:ia")." - ".formatTime($sEndTime, "h:ia")."<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>");

					if ($sCustomer != "")
						$sTooltip .= ("<b>Customer:</b> {$sCustomer}<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>");
					
					if ($sPo != "")
						$sTooltip .= ("<b>PO No:</b> {$sPo}<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>");
					
					if ($sStyle != "")
						$sTooltip .= ("<b>Style No:</b> {$sStyle}<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>");

					if ($sDesignNo != "")
						$sTooltip .= ("<b>Design No:</b> {$sDesignNo}<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>");

					if ($sDesignName != "")
						$sTooltip .= ("<b>Design Name:</b> {$sDesignName}<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>");

					if ($sSeason != "")
						$sTooltip .= ("<b>Season:</b> {$sSeason}<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>");

					if ($sProgram != "")
						$sTooltip .= ("<b>Program:</b> {$sProgram}<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>");

					if ($_SESSION['Guest'] != "Y")
						$sTooltip .= "      <b>Report Type:</b> {$sReportsList[$iReportId]}<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>";

					$sTooltip .= "      <b>Line:</b> {$sLine}";

					if ($iQuantity > 0)
						$sTooltip .= "      <div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b>Sample Size:</b> {$iQuantity}";

					if ($sApproved == "N")
						$sTooltip .= "<span id='Approve{$iAuditCode}'><div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b align='right'><a href='#' onclick='return approveAudit($iAuditCode);'>Approve</a></b></span>";

					else if ($sAuditResult == "" && strtotime($sAuditDate) == strtotime(date("Y-m-d")))
					{
						$sTooltip .= "<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>";
						$sTooltip .= "<span id='Live{$iAuditCode}'><b align='right'><a href='".SITE_URL."dashboard/progress.php?AuditCode={$sAuditCode}' target='_blank'>Live View</a></b></span>";

						if (getDbValue("COUNT(*)", "tbl_audit_subscriptions", "audit_id='$iAuditId' AND user_id='{$_SESSION['UserId']}'") == 0)
						{
							$sTooltip .= "&nbsp;|&nbsp;";
							$sTooltip .= "<span id='Subscribe{$iAuditCode}'><b align='right'><a href='quonda/subscribe-audit.php?Id={$iAuditId}' onclick='Tips.hideAll( );' class='lightview' rel='iframe' title='Subscribe Audit : {$sAuditCode} :: :: width: 500, height: 400'>Subscribe</a></b></span>";
						}
					}

					if ($sAuditResult != "" && (!@in_array($iReportId, array(3, 12)) || @in_array($iBrandId, array(32, 87, 119, 120, 121))))
						$sTooltip .= "<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b>QA Report:</b> <a href='quonda/export-qa-report.php?Id={$iAuditId}&ReportId={$iReportId}&Brand={$iBrandId}&AuditStage={$sAuditStage}' onclick='Tips.hideAll( );'>Download</a>";

					if ($sAuditStage != "")
					{
						if ($iStyleId == 0 || $iPoId == 0)
							$sBackground = "#dddddd";

						else
							$sBackground = $sStageColorsList[$sAuditStage];


						switch ($sAuditResult)
						{
							case "P"  :  $sStatus = "Pass"; break;
							case "F"  :  $sStatus = "Fail"; break;
							case "H"  :  $sStatus = "Hold"; break;
							case "A"  :  $sStatus = "Pass"; break;
							case "B"  :  $sStatus = "Pass"; break;
							case "C"  :  $sStatus = "Fail"; break;
						}


						if ($sStatus != "")
							$sTooltip .= "      <div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b>Audit Result:</b> {$sStatus}";
					}

					if ($iDefects > 0)
						$sTooltip .= "      <div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b>Total Defects:</b> {$iDefects}";

					if (count($sPictures) > 0)
						$sTooltip .= ("      <div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b>Defect Pictures:</b> ".count($sPictures));

					if ($iDefectPics > 0)
						$sTooltip .= "      <div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b>Pictures Required:</b> {$iDefectPics}";

					$sTooltip .= "    </td>";


					if (count($sPictures) > 0)
					{
						$sTooltip .= "    <td width='10'></td>";

						$sTooltip .= "    <td width='190'>";
						$sTooltip .= "      <table border='0' cellpadding='0' cellspacing='0' width='190'>";

						for ($k = 0; $k < 4;)
						{
							$sTooltip .= "        <tr valign='top'>";

							for ($l = 0; $l < 2; $l ++, $k ++)
							{
								$sTooltip .= "          <td width='95'>";

								if ($k < count($sPictures))
								{
									if (checkUserRights("qa-reports.php", "Quonda", "view"))
										$sTooltip .= ("<a href='quonda/qa-report-images.php?AuditCode=".$sAuditCode."' target='_blank'><img src='".(QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPictures[$k]))."' width='90' height='90' alt='' title='' style='border:solid 1px #888888;' /></a>");

									else
										$sTooltip .= ("<img src='".(QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPictures[$k]))."' width='90' height='90' alt='' title='' style='border:solid 1px #888888;' />");
								}

								$sTooltip .= "          </td>";
							}

							$sTooltip .= "        </tr>";

							if ($k < 4)
							{
								$sTooltip .= "        <tr>";
								$sTooltip .= "          <td colspan='3' height='4'></td>";
								$sTooltip .= "        </tr>";
							}
						}

						$sTooltip .= "      </table>";
						$sTooltip .= "    </td>";
					}

					$sTooltip .= "  </tr>";
					$sTooltip .= "  </table>";


					if ($sCustom == "Y")
					{
						if ($iWidth < 50)
							$iWidth = 50;

						$sBorderColor     = "#0066ff";
						$sInspectionLabel = "100% Inspection";

						if ($iWidth == 50)
							$sInspectionLabel = "100%";

						else if ($iWidth <= 80)
							$sInspectionLabel = "100% Ins";


					}
?>
					          <td width="<?= $iWidth ?>">
					            <div id="Audit_<?= $sAuditCode ?>" style="overflow:hidden; width:<?= $iWidth ?>px; background:<?= $sBackground ?><?= (($sAuditResult != '') ? ' url(images/icons/done.png) 4px 4px no-repeat' : '') ?>; border-left:dotted 1px #ffffff; height:25px; line-height:25px;"<?= (($bOnGoing == true) ? ' class="blink"' : '') ?>>
<?
					if ($sApproved == "N")
					{
?>
				                  <center><b id='Status<?= $iAuditCode ?>'>?</b></center>
<?
					}

					else if (count($sPictures) > 0)
					{
?>
				                  <center><img src="images/icons/pictures.gif" width="16" height="16" vspace="<?= (($sCustom == "Y") ? 3 : 5) ?>" alt="" title="" <?= (($sCustom == "Y") ? ' align="left" style="margin-left:28px;"' : '') ?> /><?= (($sCustom == "Y") ? ('<span style="float:right; font-size:10px; padding-right:5px;">'.$sInspectionLabel.'</span>') : '') ?></center>
<?
					}

					else if ($sCustom == "Y")
					{
?>
				                  <span style="float:right; padding-right:5px; font-size:10px;"><?= $sInspectionLabel ?></span>
<?
					}
?>
					            </div>

							    <script type="text/javascript">
							    <!--
								    new Tip('Audit_<?= $sAuditCode ?>',
								            "<?= $sTooltip ?>",
								            { title:'<?= $sAuditCode ?> - <?= $sVendorsList[$iVendorId] ?> - <?= $sAllBrandsList[$iBrandId] ?>', stem:'topLeft', showOn:'mouseover', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:400, hideOn:{ element:'closeButton', event:'click' }, hideAfter:0.5  });
 							    -->
							    </script>
					          </td>
<?
					$iPrevious = $iEndTime;
				}
?>
					          <td></td>
					        </tr>
					      </table>

					    </td>
					  </tr>
<?
				$iActualRows ++;
			}
		}
	}


	if (count($sDates) == 0)
	{
		for ($i = 0; $i < 10; $i ++)
		{
?>
						  <tr>
							<td width="145" align="right">&nbsp;</td>
							<td style="border-right:solid 2px #666666;" height="35"></td>

							<td width="768">

							  <table border="0" cellpadding="0" cellspacing="0" width="100%" height="25">
								<tr bgcolor="#fdfdfd">
								  <td width="100%" height="25"></td>
								</tr>
							  </table>

							</td>
						  </tr>
<?
		}
	}
?>
						  <tr>
							<td align="right"></td>
							<td style="border-right:solid 2px #666666;" height="5"></td>
							<td height="5"></td>
						  </tr>

						  <tr>
							<td></td>
							<td></td>

							<td style="border-top:solid 2px #666666;">

							  <table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
<?
	for ($i = 8; $i < 20; $i ++)
	{
?>
					          	<td width="32"><div style="width:2px; height:6px; background:#666666;"></div></td>
<?
	}
?>
								</tr>
							  </table>

							  <table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
<?
	for ($i = 8; $i < 20; $i ++)
	{
?>
					          	<td width="32"><small><?= date("ga", mktime($i, 0, 0, date("m"), date("d"), date("Y"))) ?></small></td>
<?
	}
?>
								</tr>
							  </table>

							</td>
							</tr>
							</table>
						  </div>
						</div>

						<br />

				    </div>

				    <br />

					<style type="text/css">
					<!--
						#Grid
						{
						  height    :  <?= ((($iActualRows == 0) ? 10 : $iActualRows) * 35) ?>px;
						  overflow  :  hidden;
						}
					-->
					</style>
