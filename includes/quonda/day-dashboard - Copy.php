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
?>
				  <h2>
<?
	if (@strpos($_SESSION["Email"], "apparelco.com") === FALSE && @strpos($_SESSION["Email"], "3-tree.com") === FALSE)
	{
		if ($FromDate != date("Y-m-d"))
		{
?>
				    <span style="float:right; padding-right:5px;">[ <a href="quonda/dashboard.php?FromDate=<?= date("Y-m-d") ?>&ToDate=<?= date("Y-m-d") ?>" style="color:#ffff00;">Today's Audits</a> ]</span>
<?
		}

		else
		{
?>
				    <span style="float:right; padding-right:5px;">[ <a href="quonda/dashboard.php" style="color:#ffff00;">Recent Audits</a> ]</span>
<?
		}
	}
?>
				    Audits (<?= formatDate($FromDate) ?>)
				  </h2>

	  			  <div style="padding-left:5px;">
	  			    <table border="0" cellpadding="3" cellspacing="0" width="100%">
	  			      <tr>
<?
	if (@strpos($_SESSION["Email"], "apparelco.com") !== FALSE || @strpos($_SESSION["Email"], "3-tree.com") !== FALSE)
	{
?>
					    <td width="11"><div style="height:11px; width:11px; background:#b6e500;"></div></td>
					    <td width="80">Custom</td>
<?
	}
	
	
	$iIndex = 1;

	foreach ($sAuditStagesList as $sKey => $sValue)
	{
		if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE && $sValue == "Final")
			$sValue = "Firewall";

		if ( (@strpos($_SESSION["Email"], "pelknit.com") !== FALSE || @strpos($_SESSION["Email"], "fencepostproductions.com") !== FALSE) &&
			 !@in_array($sKey, array("B", "C", "O", "F")) )
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
	$sConditions  = " WHERE audit_date='$FromDate' AND FIND_IN_SET(report_id, '$sReportTypes') AND NOT FIND_IN_SET(report_id, '$sQmipReports') ";

	if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE)
		$sConditions .= " AND audit_stage='F' AND audit_result!='' ";

	else if (@strpos($_SESSION["Email"], "dkcompany.com") !== FALSE || @strpos($_SESSION["Email"], "hema.nl") !== FALSE)
		$sConditions .= " AND audit_stage='F' AND audit_result='P' ";


	if ($AuditStage != "")
		$sConditions .= " AND audit_stage='$AuditStage' ";

	else
		$sConditions .= " AND FIND_IN_SET(audit_stage, '$sAuditStages') ";


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
	{
		if (@strpos($_SESSION["Email"], "apparelco.com") === FALSE && @strpos($_SESSION["Email"], "3-tree.com") === FALSE)
			$sConditions .= " AND style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') $sStyleCategoriesSql)";

		else
			$sConditions .= " AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') $sStyleCategoriesSql))";
	}

	if ($Region > 0)
		$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region') ";

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	else
		$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";


	
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

	$iCount = $objDb->getCount( );
	$sCities = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sCity     = $objDb->getField($i, "city");
		$sCountry  = $objDb->getField($i, "_Country");
		$sVendors  = $objDb->getField($i, "_Vendors");
		
		
		$iAuditors = array( );
		
		$sSQL = "SELECT DISTINCT(user_id) FROM tbl_qa_reports $sConditions AND FIND_IN_SET(vendor_id, '$sVendors')";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
			$iAuditors[] = $objDb2->getField($j, 0);

		
		$sCities["{$sCity}, {$sCountry}"]["Vendors"]  = $sVendors;
		$sCities["{$sCity}, {$sCountry}"]["Auditors"] = @implode(",", $iAuditors);
	}


	if ($Brand == 0 && getDbValue("show_user_schedules", "tbl_users", "id='{$_SESSION['UserId']}'") == "Y" || $_SESSION["Admin"] == "Y")
	{
		$iLocations = array( );

		$sSQL = "SELECT DISTINCT(us.location_id)
				 FROM tbl_user_schedule us, tbl_users u
				 WHERE us.user_id=u.id AND ('$FromDate' BETWEEN us.from_date AND us.to_date) AND u.status='A' AND (u.email LIKE '%@apparelco.com' OR u.email LIKE '%@3-tree.com') AND
					   u.designation_id IN (SELECT id FROM tbl_designations WHERE department_id IN (8,15,31,41)) $sRegionSql";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$iLocations[] = $objDb->getField($i, 0);

		$sLocations = @implode(",", $iLocations);



		$sSQL = "SELECT city, country_id,
					   (SELECT code FROM tbl_countries WHERE id=tbl_visit_locations.country_id) AS _Country,
					   GROUP_CONCAT(id SEPARATOR ',') AS _Locations
				FROM tbl_visit_locations
				WHERE FIND_IN_SET(id, '$sLocations')
				GROUP BY city
				ORDER BY city, _Country";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sCity      = $objDb->getField($i, "city");
			$iCountry   = $objDb->getField($i, "country_id");
			$sCountry   = $objDb->getField($i, "_Country");
			$sLocations = $objDb->getField($i, "_Locations");
			
			
			$iAuditors = array( );

			$sSQL = "SELECT DISTINCT(user_id) FROM tbl_user_schedule WHERE ('$FromDate' BETWEEN from_date AND to_date) AND location_id IN (SELECT id FROM tbl_visit_locations WHERE country_id='$iCountry' AND city LIKE '$sCity')";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
				$iAuditors[] = $objDb2->getField($j, 0);			
		

			$sCities["{$sCity}, {$sCountry}"]["Locations"] = $sLocations;
			$sCities["{$sCity}, {$sCountry}"]["Auditors"] .= ((($sCities["{$sCity}, {$sCountry}"]["Auditors"] != "") ? "," : "").@implode(",", $iAuditors));
		}
	}
	

	
	if (@strpos($_SESSION["Email"], "apparelco.com") !== FALSE || @strpos($_SESSION["Email"], "3-tree.com") !== FALSE)
	{	
		$iAuditors = array( );

		$sSQL = "SELECT DISTINCT(user_id) FROM tbl_qa_reports $sConditions";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$iAuditors[] = $objDb->getField($i, 0);
		
		
		$sSQL = "SELECT DISTINCT(user_id) FROM tbl_user_schedule WHERE ('$FromDate' BETWEEN from_date AND to_date)";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$iAuditors[] = $objDb->getField($i, 0);

		$sAuditors = @implode(",", $iAuditors);


		
		$iAuditors = array( );
		

		$sSQL = "SELECT DISTINCT(user_id) FROM tbl_user_activities WHERE date='$FromDate' AND NOT FIND_IN_SET(user_id, '$sAuditors')";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$iAuditors[] = $objDb->getField($i, 0);

		
		$sActivityAuditors = @implode(",", $iAuditors);


		if ($sActivityAuditors != "")
		{
			$sSQL = "SELECT DISTINCT(country_id) FROM tbl_users WHERE FIND_IN_SET(id, '$sActivityAuditors')";
			$objDb->query($sSQL);

			$iCountries = $objDb->getCount( );

			for ($i = 0; $i < $iCountries; $i ++)
			{
				$iCountry = $objDb->getField($i, 0);		
				$sCountry = getDbValue("code", "tbl_countries", "id='$iCountry'");
				
				$sCities["Activities, {$sCountry}"]["Locations"] = $iCountry;
				$sCities["Activities, {$sCountry}"]["Vendors"]   = "0";
				$sCities["Activities, {$sCountry}"]["Auditors"]  = "0";
			}
		}
	}

	$iActiveAuditors = array( );
?>
				    <div style="position:relative;">
						<div style="position:relative;">
						  <div style="position:relative; right:0px; top:0px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							  <tr>
								<td width="150"></td>
								<td></td>

								<td width="768" style="border-bottom:solid 2px #666666;">

								  <table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
<?
	for ($i = 0; $i < 24; $i ++)
	{
?>
					          		  <td width="32"><small><?= substr(date("ga", mktime($i, 0, 0, date("m"), date("d"), date("Y"))), 0, -1) ?></small></td>
<?
	}
?>
									</tr>

									<tr>
<?
	for ($i = 0; $i < 24; $i ++)
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
	$sActivitiesList = getList("tbl_activities", "id", "name");
	$sActivityColors = getList("tbl_activities", "id", "color");
	$iSuperUsers     = array(1,2,3,10,19,92,240);
	$iCity           = 0;

	foreach ($sCities as $sCity => $sCityIds)
	{
		$iCity ++;


		$sCityVendors   = $sCityIds["Vendors"];
		$sCityLocations = $sCityIds["Locations"];
		$sCityAuditors  = $sCityIds["Auditors"];

		$iVendorsList   = @explode(",", $sCityVendors);
		$iLocationsList = @explode(",", $sCityLocations);
?>
						  <tr>
							<td align="right" bgcolor="#e6e6e6"><b><?= $sCity ?></b></td>
							<td style="border-right:solid 2px #666666;" height="35" bgcolor="#e6e6e6"></td>

							<td width="768" height="35">

							  <table border="0" cellpadding="0" cellspacing="0" width="100%" height="25">
								<tr>
								  <td width="100%" bgcolor="#f3f3f3">&nbsp;</td>
								</tr>
							  </table>

							</td>
						  </tr>
<?
		if (@strpos($sCity, "Activities") !== FALSE && (@strpos($_SESSION["Email"], "apparelco.com") !== FALSE || @strpos($_SESSION["Email"], "3-tree.com") !== FALSE))
		{		
			$sSQL = "SELECT id,
							activity_id AS location_id,
							details,
							'U' AS _Type,
							user_id,
							'0' AS group_id,
							'0' AS master_id,
							date AS from_date,
							date AS to_date,
							TIME_TO_SEC(time) AS _StartTime,
							TIME_TO_SEC(time) AS _EndTime,
							'' AS created_at,
							CONCAT('A', LPAD(id, 5, '0')) AS audit_code,
							'0' AS vendor_id,
							'0' AS brand_id,
							'0' AS style_id,
							'0' AS po_id,
							time AS start_time,
							time AS end_time,
							date AS audit_date,
							'0' AS report_id,
							'' AS audit_stage,
							'' AS audit_result,
							'N' AS custom_sample,
							'0' AS total_gmts,
							'' AS approved,
							'' AS status,
							'0' AS checked_gmts,
							'' AS _Line,
							'0' AS _Category,
							'0' AS _MinorDefects,
							'0' AS _MajorDefects,
							'0' AS _CriticalDefects,
							'0' AS _GfDefects,
							'0' AS _DefectPics,
							'0' AS _GfDefectPics,
							(SELECT name FROM tbl_users WHERE id=tbl_user_activities.user_id) AS _User,
							(SELECT auditor_level FROM tbl_users WHERE id=tbl_user_activities.user_id) AS _UserLevel,
							(SELECT auditor_type FROM tbl_users WHERE id=tbl_user_activities.user_id) AS _UserType
					 FROM tbl_user_activities
					 WHERE date='$FromDate' AND FIND_IN_SET(user_id, '$sActivityAuditors')
					 ORDER BY _User, user_id, start_time";
		}

		else
		{
			$sSQL = "SELECT id,
							'0' AS location_id,
							'' AS details,
							IF(group_id='0', 'A', 'G') AS _Type,
							user_id,
							group_id,
							master_id,
							'' AS from_date,
							'' AS to_date,
							TIME_TO_SEC(start_time) AS _StartTime,
							TIME_TO_SEC(end_time) AS _EndTime,
							created_at,
							audit_code,
							vendor_id,
							brand_id,
							style_id,
							po_id,
							start_time,
							end_time,
							audit_date,
							report_id,
							audit_stage,
							audit_result,
							custom_sample,
							total_gmts,
							approved,
							status,
							checked_gmts,
							(SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line,
							(SELECT category_id FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Category,
							(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND nature='0') AS _MinorDefects,
							(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND nature='1') AS _MajorDefects,
							(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND nature='2') AS _CriticalDefects,
							(SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=tbl_qa_reports.id) AS _GfDefects,
							(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id) AS _DefectPics,
							(SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=tbl_qa_reports.id) AS _GfDefectPics,
							IF(group_id='0', (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id), (SELECT name FROM tbl_auditor_groups WHERE id=tbl_qa_reports.group_id)) AS _User,
							IF(group_id='0', (SELECT auditor_level FROM tbl_users WHERE id=tbl_qa_reports.user_id), '') AS _UserLevel,
							(SELECT auditor_type FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _UserType
					 FROM tbl_qa_reports
					 $sConditions AND FIND_IN_SET(vendor_id, '$sCityVendors')";

			if (@strpos($_SESSION["Email"], "apparelco.com") !== FALSE || @strpos($_SESSION["Email"], "3-tree.com") !== FALSE)
			{
				$sSQL .= " UNION

						 SELECT id,
								activity_id AS location_id,
								details,
								'U' AS _Type,
								user_id,
								'0' AS group_id,
								'0' AS master_id,
								date AS from_date,
								date AS to_date,
								TIME_TO_SEC(time) AS _StartTime,
								TIME_TO_SEC(time) AS _EndTime,
								'' AS created_at,
								CONCAT('A', LPAD(id, 5, '0')) AS audit_code,
								'0' AS vendor_id,
								'0' AS brand_id,
								'0' AS style_id,
								'0' AS po_id,
								time AS start_time,
								time AS end_time,
								date AS audit_date,
								'0' AS report_id,
								'' AS audit_stage,
								'' AS audit_result,
								'N' AS custom_sample,
								'0' AS total_gmts,
								'' AS approved,
								'' AS status,
								'0' AS checked_gmts,
								'' AS _Line,
								'0' AS _Category,
								'0' AS _MinorDefects,
								'0' AS _MajorDefects,
								'0' AS _CriticalDefects,
								'0' AS _GfDefects,
								'0' AS _DefectPics,
								'0' AS _GfDefectPics,
								(SELECT name FROM tbl_users WHERE id=tbl_user_activities.user_id) AS _User,
								(SELECT auditor_level FROM tbl_users WHERE id=tbl_user_activities.user_id) AS _UserLevel,
								(SELECT auditor_type FROM tbl_users WHERE id=tbl_user_activities.user_id) AS _UserType
						 FROM tbl_user_activities
						 WHERE date='$FromDate' AND FIND_IN_SET(user_id, '$sCityAuditors')";

				if ($Brand == 0)
				{
					$sSQL .= "   UNION

								 SELECT id,
										location_id,
										details,
										'S' AS _Type,
										user_id,
										'0' AS group_id,
										'0' AS master_id,
										from_date,
										to_date,
										TIME_TO_SEC(start_time) AS _StartTime,
										TIME_TO_SEC(end_time) AS _EndTime,
										'' AS created_at,
										CONCAT('T', LPAD(id, 5, '0')) AS audit_code,
										'0' AS vendor_id,
										'0' AS brand_id,
										'0' AS style_id,
										'0' AS po_id,
										start_time,
										end_time,
										from_date AS audit_date,
										'0' AS report_id,
										'' AS audit_stage,
										'' AS audit_result,
										'N' AS custom_sample,
										'0' AS total_gmts,
										'' AS approved,
										'' AS status,
										'0' AS checked_gmts,
										'' AS _Line,
										'0' AS _Category,
										'0' AS _MinorDefects,
										'0' AS _MajorDefects,
										'0' AS _CriticalDefects,
										'0' AS _GfDefects,
										'0' AS _DefectPics,
										'0' AS _GfDefectPics,
										(SELECT name FROM tbl_users WHERE id=tbl_user_schedule.user_id) AS _User,
										(SELECT auditor_level FROM tbl_users WHERE id=tbl_user_schedule.user_id) AS _UserLevel,
										(SELECT auditor_type FROM tbl_users WHERE id=tbl_user_schedule.user_id) AS _UserType
								 FROM tbl_user_schedule
								 WHERE ('$FromDate' BETWEEN from_date AND to_date) AND FIND_IN_SET(location_id, '$sCityLocations')";
				}
			}
			
			$sSQL .= " ORDER BY _User, user_id, group_id, start_time";
		}

		
		$objDb->query($sSQL);
		
		$iCount       = $objDb->getCount( );
		$iLastAuditor = 0;
		$iPrevious    = 0;

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sType            = $objDb->getField($i, '_Type');
			$iAuditorId       = $objDb->getField($i, 'user_id');
			$sAuditor         = $objDb->getField($i, '_User');
			$sAuditorLevel    = $objDb->getField($i, '_UserLevel');
			$iAuditorType     = $objDb->getField($i, '_UserType');
			$iGroupId         = $objDb->getField($i, 'group_id');
			$sFromDate        = $objDb->getField($i, 'from_date');
			$sToDate          = $objDb->getField($i, 'to_date');
			$iStartTime       = $objDb->getField($i, '_StartTime');
			$iEndTime         = $objDb->getField($i, '_EndTime');
			$sStartTime       = $objDb->getField($i, 'start_time');
			$sEndTime         = $objDb->getField($i, 'end_time');
			$sAuditDate       = $objDb->getField($i, 'audit_date');

			$iScheduleId      = $objDb->getField($i, 'id');
			$iLocationId      = $objDb->getField($i, 'location_id');
			$sDetails         = $objDb->getField($i, 'details');
			
			$iActivityId      = $objDb->getField($i, 'id');
			$iActivityType    = $objDb->getField($i, 'location_id');

			$iAuditId         = $objDb->getField($i, 'id');
			$iMasterId        = $objDb->getField($i, 'master_id');
			$sAuditCode       = $objDb->getField($i, 'audit_code');
			$iVendorId        = $objDb->getField($i, 'vendor_id');
			$iBrandId         = $objDb->getField($i, 'brand_id');
			$iStyleId         = $objDb->getField($i, 'style_id');
			$iPoId            = $objDb->getField($i, 'po_id');
			$iReportId        = $objDb->getField($i, 'report_id');
			$sAuditStage      = $objDb->getField($i, 'audit_stage');
			$sAuditResult     = $objDb->getField($i, 'audit_result');
			$sStatus          = $objDb->getField($i, 'status');
			$sLine            = $objDb->getField($i, '_Line');
			$iCategoryId      = $objDb->getField($i, '_Category');
			$sCustom          = $objDb->getField($i, 'custom_sample');
			$iQuantity        = $objDb->getField($i, 'total_gmts');
			$iMinorDefects    = $objDb->getField($i, '_MinorDefects');
			$iMajorDefects    = $objDb->getField($i, '_MajorDefects');
			$iCriticalDefects = $objDb->getField($i, '_CriticalDefects');
			$iDefectPics      = $objDb->getField($i, '_DefectPics');
			$iDefectPics     += $objDb->getField($i, '_GfDefectPics');
			$sApproved        = $objDb->getField($i, 'approved');
			$iChecked         = $objDb->getField($i, 'checked_gmts');
			$sCreatedAt       = $objDb->getField($i, 'created_at');


			if ($sType != "S" && $sType != "U")
			{
				if (!@in_array($iAuditorId, $iActiveAuditors))
					$iActiveAuditors[] = $iAuditorId;
			}


			if (($iGroupId > 0 && $iLastAuditor != $iGroupId) || ($iGroupId == 0 && $iLastAuditor != $iAuditorId))
			{
				if ($i > 0)
				{
?>
								  <td></td>
								</tr>
							  </table>

							</td>
						  </tr>
<?
				}



				$sLatitude  = "31.3974864";
				$sLongitude = "74.2207633";
				$sLocation  = "";
				$sPicture   = "default.jpg";

				if ($sType == "A" || $sType == "S" || $sType == "U")
				{
					$sSQL = "SELECT picture, latitude, longitude, location_time FROM tbl_users WHERE id='$iAuditorId'";
					$objDb2->query($sSQL);

					$sPicture  = $objDb2->getField(0, 'picture');
					$sDateTime = $objDb2->getField(0, 'location_time');

					if ($objDb2->getField(0, 'latitude') != "")
						$sLatitude = $objDb2->getField(0, 'latitude');

					if ($objDb2->getField(0, 'longitude') != "")
						$sLongitude = $objDb2->getField(0, 'longitude');


					if ($sType == "A" && ($sDateTime == "0000-00-00 00:00:00" || $sDateTime == "" || (strtotime(date("Y-m-d H:i:s")) - strtotime($sDateTime)) > 43200))
					{
						$sSQL = "SELECT v.vendor, v.latitude, v.longitude
								 FROM tbl_qa_reports qa, tbl_vendors v
								 WHERE qa.vendor_id=v.id AND qa.audit_date='$FromDate' AND v.latitude!='' AND v.longitude!='' AND qa.user_id='$iAuditorId' AND qa.group_id='0'
								       AND FIND_IN_SET(qa.vendor_id, '$sCityVendors')
								 ORDER BY IF(qa.audit_result='',0,1) DESC, qa.id DESC
								 LIMIT 1";
						$objDb2->query($sSQL);

						if ($objDb2->getCount( ) == 1)
						{
							$sLocation  = (" - ".$objDb2->getField(0, 'vendor'));

							$sLatitude  = $objDb2->getField(0, 'latitude');
							$sLongitude = $objDb2->getField(0, 'longitude');
						}
					}
				}


				if ($sType == "G")
				{
					$sSQL = "SELECT v.vendor, v.latitude, v.longitude
							 FROM tbl_qa_reports qa, tbl_vendors v
							 WHERE qa.vendor_id=v.id AND qa.audit_date='$FromDate' AND v.latitude!='' AND v.longitude!='' AND qa.group_id='$iGroupId'
								   AND FIND_IN_SET(qa.vendor_id, '$sCityVendors')
							 ORDER BY IF(qa.audit_result='',0,1) DESC, qa.id DESC
							 LIMIT 1";
					$objDb2->query($sSQL);

					if ($objDb2->getCount( ) == 1)
					{
						$sLocation  = (" - ".$objDb2->getField(0, 'vendor'));

						$sLatitude  = $objDb2->getField(0, 'latitude');
						$sLongitude = $objDb2->getField(0, 'longitude');
					}
				}


				if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
					$sPicture = "default.jpg";

				$sPicture = (USERS_IMG_PATH.'thumbs/'.$sPicture);


				$sTooltip  = "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
				$sTooltip .= "  <tr valign='top'>";
				$sTooltip .= "    <td>";
				$sTooltip .= "      <center><div style='border:solid 1px #bbbbbb; padding:1px;'><img src='{$sPicture}' width='100' height='75' alt='' title='' /></div></center>";

				if (@in_array($_SESSION['UserId'], $iSuperUsers) && $iGroupId == 0 && $sAuditorLevel != "")
				{
					switch ($sAuditorLevel)
					{
						case "G" : $sQaLevel = "green"; break;
						case "B" : $sQaLevel = "blue"; break;
						case "Y" : $sQaLevel = "yellow"; break;
						case "R" : $sQaLevel = "red"; break;
					}

					$sTooltip .= "<center style='margin-top:8px;'><b>Certification Level</b><br /><img src='images/quonda/{$sQaLevel}.png' width='50%' alt='' title='' /></center>";
				}

				$sTooltip .= "    </td>";
				$sTooltip .= "    <td width='10'></td>";
				$sTooltip .= "    <td width='250'>";
				$sTooltip .= "      <div id='Map{$i}_{$iCity}' style='250px; height:180px;'></div>";
				$sTooltip .= "    </td>";
				$sTooltip .= "  </tr>";
				$sTooltip .= "  </table>";
				
				
				$sAuditorType = "";
				
				switch ($iAuditorType)
				{
					case 1 : $sAuditorType = "3rd Party Auditor"; break;
					case 2 : $sAuditorType = "QMIP Auditor"; break;
					case 3 : $sAuditorType = "QMIP Corelation Auditor"; break;
					case 4 : $sAuditorType = "MCA"; break;
					case 5 : $sAuditorType = "FCA"; break;
				}
?>
						  <tr>
							<td align="right">
							  <span id="Auditor<?= $i ?>_<?= $iCity ?>" style="display:block; height:15px; overflow:hidden;">
<?
				if (@in_array($_SESSION['UserId'], $iSuperUsers) && $iGroupId == 0)
				{
					switch ($sAuditorLevel)
					{
						case "G" : $sQaColor = "#00e202"; break;
						case "B" : $sQaColor = "#02a3fe"; break;
						case "Y" : $sQaColor = "#f8c600"; break;
						case "R" : $sQaColor = "#fe0000"; break;
						default  : $sQaColor = "#cccccc"; break;
					}
?>
							    <span style="display:inline-block; width:8px; height:8px; overflow:hidden; background:<?= $sQaColor ?>; -moz-border-radius:4px; border-radius:4px; -webkit-border-radius:4px; margin-right:2px;"></span>
<?
				}
?>
							    <?= $sAuditor ?>
							  </span>

							  <script type="text/javascript">
							  <!--
								  new Tip('Auditor<?= $i ?>_<?= $iCity ?>',
										  "<?= $sTooltip ?> ",
										  { title:'<?= $sAuditor ?><?= $sLocation ?> - <?= $sAuditorType ?>', stem:'topLeft', showOn:'mouseover', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:374, hideOn:{ element:'closeButton', event:'click' }, hideAfter:0.1  });

								  $('Auditor<?= $i ?>_<?= $iCity ?>').observe('prototip:shown', function( )
								  {
									  var objMap<?= $i ?>_<?= $iCity ?> = new GMap(document.getElementById("Map<?= $i ?>_<?= $iCity ?>"));

									  objMap<?= $i ?>_<?= $iCity ?>.addControl(new GLargeMapControl3D( ));
									  objMap<?= $i ?>_<?= $iCity ?>.addControl(new GMapTypeControl( ));
									  objMap<?= $i ?>_<?= $iCity ?>.setCenter(new GLatLng(<?= $sLatitude ?>, <?= $sLongitude ?>), 12);
									  objMap<?= $i ?>_<?= $iCity ?>.addOverlay(new GMarker(new GLatLng(<?= $sLatitude ?>, <?= $sLongitude ?>)));
								  });
							  -->
							  </script>
<?
				if ($sType == "G")
				{
?>
							  <div style="height:15px; overflow:hidden;">
<?
					$sSQL = "SELECT id, name, LEFT(NAME, (IF(LOCATE(' ', NAME), LOCATE(' ', NAME), LOCATE('-', NAME)) - 1)) AS _Name, auditor_level, picture, latitude, longitude, location_time
					         FROM tbl_users
					         WHERE FIND_IN_SET(id, (SELECT users FROM tbl_auditor_groups WHERE id='$iGroupId'))
					         ORDER BY name";
					$objDb2->query($sSQL);

					$iCount2 = $objDb2->getCount( );

					for ($j = 0; $j < $iCount2; $j ++)
					{
						$iUserId       = $objDb2->getField($j, 'id');
						$sName         = $objDb2->getField($j, 'name');
						$sShortName    = $objDb2->getField($j, '_Name');
						$sPicture      = $objDb2->getField($j, 'picture');
						$sSubLatitude  = $objDb2->getField($j, 'latitude');
						$sSubLongitude = $objDb2->getField($j, 'longitude');
						$sDateTime     = $objDb2->getField($j, 'location_time');
						$sAuditorLevel = $objDb2->getField($j, 'auditor_level');


						if (!@in_array($iUserId, $iActiveAuditors))
							$iActiveAuditors[] = $iUserId;

						if ($sDateTime == "0000-00-00 00:00:00" || $sDateTime == "" || (strtotime(date("Y-m-d H:i:s")) - strtotime($sDateTime)) > 43200)
						{
							$sSubLatitude  = $sLatitude;
							$sSubLongitude = $sLongitude;
						}

						if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
							$sPicture = "default.jpg";

						$sPicture = (USERS_IMG_PATH.'thumbs/'.$sPicture);


						$sTooltip  = "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
						$sTooltip .= "  <tr valign='top'>";
						$sTooltip .= "    <td>";
						$sTooltip .= "      <center><div style='border:solid 1px #bbbbbb; padding:1px;'><img src='{$sPicture}' width='100' height='75' alt='' title='' /></div></center><br />";

						if (@in_array($_SESSION['UserId'], $iSuperUsers) && $sAuditorLevel != "")
						{
							switch ($sAuditorLevel)
							{
								case "G" : $sQaLevel = "green"; break;
								case "B" : $sQaLevel = "blue"; break;
								case "Y" : $sQaLevel = "yellow"; break;
								case "R" : $sQaLevel = "red"; break;
							}

							$sTooltip .= "<center style='margin-top:8px;'><b>Certification Level</b><br /><img src='images/quonda/{$sQaLevel}.png' width='50%' alt='' title='' /></center>";
						}

						$sTooltip .= "    </td>";
						$sTooltip .= "    <td width='10'></td>";
						$sTooltip .= "    <td width='250'>";
						$sTooltip .= "      <div id='Map{$i}_{$j}_{$iCity}' style='250px; height:180px;'></div>";
						$sTooltip .= "    </td>";
						$sTooltip .= "  </tr>";
						$sTooltip .= "  </table>";
?>
								  <span id="Auditor<?= $i ?>_<?= $j ?>_<?= $iCity ?>" style="display:inline-block; height:10px; overflow:hidden; font-size:9px; color:#888888;"><?= $sShortName ?></span><?= (($j < ($iCount2 - 1)) ? ', ' : '') ?>

								  <script type="text/javascript">
								  <!--
									  new Tip('Auditor<?= $i ?>_<?= $j ?>_<?= $iCity ?>',
											  "<?= $sTooltip ?> ",
											  { title:'<?= $sName ?><?= $sLocation ?>', stem:'topLeft', showOn:'mouseover', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:374, hideOn:{ element:'closeButton', event:'click' }, hideAfter:0.1  });

									  $('Auditor<?= $i ?>_<?= $j ?>_<?= $iCity ?>').observe('prototip:shown', function( )
									  {
										  var objMap<?= $i ?>_<?= $j ?>_<?= $iCity ?> = new GMap(document.getElementById("Map<?= $i ?>_<?= $j ?>_<?= $iCity ?>"));

										  objMap<?= $i ?>_<?= $j ?>_<?= $iCity ?>.addControl(new GLargeMapControl3D( ));
										  objMap<?= $i ?>_<?= $j ?>_<?= $iCity ?>.addControl(new GMapTypeControl( ));
										  objMap<?= $i ?>_<?= $j ?>_<?= $iCity ?>.setCenter(new GLatLng(<?= $sSubLatitude ?>, <?= $sSubLongitude ?>), 12);
										  objMap<?= $i ?>_<?= $j ?>_<?= $iCity ?>.addOverlay(new GMarker(new GLatLng(<?= $sSubLatitude ?>, <?= $sSubLongitude ?>)));
									  });
								  -->
								  </script>
<?
					}
?>
							  </div>
<?
				}
?>
							</td>

							<td height="35" align="right" style="border-right:solid 2px #666666; overflow:hidden;">
							  <div style="width:3px; height:2px; background:#666666;"></div>
							</td>

							<td style="overflow:hidden; background:url('images/dashboard-timeline-bg.png');">

							  <table border="0" cellpadding="0" cellspacing="0" width="100%" height="25">
								<tr bgcolor="#fcfcfc">
<?
				$iPrevious    = 0;
				$iLastAuditor = (($iGroupId > 0) ? $iGroupId : $iAuditorId);
			}



			if ($Filter != "")
			{
				if ( ($Filter == "A" && $sType == "S") || ($Filter == "S" && $sType != "S") || ($Filter == "Q" && !@in_array($iReportId, $iQmipReports)) || ($Filter == "P" && ($sBrandTypesList[$iBrandId] != "P" || $sType == "S")) )
					continue;
			}


			
			
			if ($sType == "U")
			{
				$sDetails = str_replace("\r\n", "<br />", $sDetails);
				$sDetails = str_replace("\n", "<br />", $sDetails);

				if ($iStartTime > $iPrevious)
				{
					$iTime  = ($iStartTime - $iPrevious);
					$iTime /= 60;
					$iWidth = @round($iTime * 0.534);
					$iWidth = (($iWidth < 2) ? 2 : $iWidth);
?>
					          	<td width="<?= $iWidth ?>"></td>
<?
				}
				
				
				$iNextAuditor   = $objDb->getField(($i + 1), 'user_id');
				$iNextStartTime = $objDb->getField(($i + 1), '_StartTime');
				
				if ($iNextAuditor == $iAuditorId)
					$iEndTime = ($iNextStartTime - 300);
				
				else
					$iEndTime = ($iStartTime + (60 * 30));

				
				$iTime       = ($iEndTime - $iStartTime);
				$iTime      /= 60;
				$iWidth      = @round($iTime * 0.534);
				$iWidth      = (($iWidth < 2) ? 2 : $iWidth);
				$sBackground = $sActivityColors[$iActivityType];


				$sTooltip  = ("<b>Time:</b> ".formatTime($sStartTime, "h:ia")."<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>");
				$sTooltip .= str_replace("&lt;br /&gt;", "<br />", htmlentities($sDetails));
?>
								  <td width="<?= $iWidth ?>">
									<div id="Activity_<?= $iActivityId ?>" style="width:<?= $iWidth ?>px; background:<?= $sBackground ?>; border-left:dotted 1px #ffffff; height:25px; line-height:25px;"></div>

									<script type="text/javascript">
									<!--
										new Tip('Activity_<?= $iActivityId ?>',
												"<?= $sTooltip ?>",
												{ title:'<?= $sActivitiesList[$iActivityType] ?>', stem:'topLeft', showOn:'mouseover', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:300, hideOn:{ element:'closeButton', event:'click' }, hideAfter:0.1  });
									-->
									</script>
								  </td>
<?
			}
			
			else if ($sType == "S")
			{
				$sDetails = str_replace("\r\n", "<br />", $sDetails);
				$sDetails = str_replace("\n", "<br />", $sDetails);


				if (strtotime($FromDate) > strtotime($sFromDate) && strtotime($FromDate) <= strtotime($sToDate))
					$iStartTime = 0;

				if (strtotime($FromDate) >= strtotime($sFromDate) && strtotime($FromDate) < strtotime($sToDate))
					$iEndTime = 86399;


				if ($iStartTime > $iPrevious)
				{
					$iTime  = ($iStartTime - $iPrevious);
					$iTime /= 60;
					$iWidth = @round($iTime * 0.534);
					$iWidth = (($iWidth < 2) ? 2 : $iWidth);
?>
					          	<td width="<?= $iWidth ?>"></td>
<?
				}

				$iTime       = ($iEndTime - $iStartTime);
				$iTime      /= 60;
				$iWidth      = @round($iTime * 0.534);
				$iWidth      = (($iWidth < 2) ? 2 : $iWidth);
				$sBackground = "#b6e600";


				$sTooltip  = ("<b>ID:</b> ".$sAuditCode."<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>");
				$sTooltip .= ("<b>Start Date/Time:</b> ".formatDate($sFromDate)." ".formatTime($sStartTime, "h:ia")."<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>");
				$sTooltip .= ("<b>End Date/Time:</b> ".formatDate($sToDate)." ".formatTime($sEndTime, "h:ia")."<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>");
				$sTooltip .= str_replace("&lt;br /&gt;", "<br />", htmlentities($sDetails));
?>
								  <td width="<?= $iWidth ?>">
									<div id="Schedule_<?= $iScheduleId ?>" style="width:<?= $iWidth ?>px; background:<?= $sBackground ?>; border-left:dotted 1px #ffffff; height:25px; line-height:25px;"></div>

									<script type="text/javascript">
									<!--
										new Tip('Schedule_<?= $iScheduleId ?>',
												"<?= $sTooltip ?>",
												{ title:'<?= $sLocationsList[$iLocationId] ?>', stem:'topLeft', showOn:'mouseover', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:300, hideOn:{ element:'closeButton', event:'click' }, hideAfter:0.1  });
									-->
									</script>
								  </td>
<?
			}


			else
			{
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
					$iWidth = @round($iTime * 0.534);
					$iWidth = (($iWidth < 2) ? 2 : $iWidth);
?>
					          <td width="<?= $iWidth ?>"></td>
<?
				}


				$iAuditCode = substr($sAuditCode, 1);


				$iTime       = ($iEndTime - $iStartTime);
				$iTime      /= 60;
				$iWidth      = @round($iTime * 0.534);
				$iWidth      = (($iWidth < 5) ? 5 : $iWidth);
				$sBackground = "#dddddd";
				$sPictures   = array( );

				@list($sYear, $sMonth, $sDay) = @explode("-", $FromDate);

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
							if (substr(@basename($sPicture), 0, ($iLength + 4)) == "{$sAuditCode}_00_" ||
								@stripos(@basename($sPicture), "_pack_") !== FALSE ||
								@stripos(@basename($sPicture), "_lab_") !== FALSE ||
								@stripos(@basename($sPicture), "_misc_") !== FALSE ||
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

				if ($iReportId == 14 || $iReportId == 34)
				{
					$sTooltip .= ("<b>Master ID:</b> {$iMasterId}<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>");
					
					$sReInspection = ((getDbValue("reinspection", "tbl_mgf_reports", "audit_id='$iAuditId'") == "Y") ? "Yes" : "No");
					$sAuditorType  = getDbValue("auditor_type", "tbl_users", "id='$iAuditorId'");
                                        
                                        $iStageReports = (int)getDbValue("COUNT(1)", "tbl_qa_reports qa, tbl_mgf_reports mgf", "qa.id=mgf.audit_id AND mgf.reinspection='Y' AND qa.master_id='$iMasterId' AND qa.audit_stage='$sAuditStage' AND qa.created_at<= '$sCreatedAt' AND qa.user_id IN (SELECT id FROM tbl_users WHERE email LIKE '%@mgfsourcing.com' AND auditor_type='$sAuditorType')");
					$iStageReports = (($iStageReports == 0) ? 1 : ($iStageReports + 1));			
					
					$sTooltip .= ("<b>Re-Inspection:</b> {$sReInspection} ({$iStageReports})<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>");
				}
				
				else if ($sSeason != "")
					$sTooltip .= ("<b>Season:</b> {$sSeason}<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>");

				if ($sProgram != "" && $sProgram != "Unknown")
					$sTooltip .= ("<b>Program:</b> {$sProgram}<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>");

				if ($_SESSION['Guest'] != "Y")
					$sTooltip .= "<b>Report Type:</b> {$sReportsList[$iReportId]}<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>";

				if ($iReportId != 14 && $iReportId != 34)
					$sTooltip .= "<b>Line:</b> {$sLine}<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>";

				if ($iQuantity > 0)
					$sTooltip .= "<b>Sample Size:</b> {$iQuantity}";
				
				else
					$sTooltip .= "<b>Sample Size:</b> Custom";

				if ($sApproved == "N")
					$sTooltip .= "<span id='Approve{$iAuditCode}'><div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b align='right'><a href='#' onclick='return approveAudit($iAuditCode);'>Approve</a></b></span>";

				else // if ($sAuditResult == "" && strtotime($sAuditDate) == strtotime(date("Y-m-d")))
				{
					$sTooltip .= "<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>";
					$sTooltip .= "<span id='Live{$iAuditCode}'><b align='right'><a href='".SITE_URL."dashboard/progress.php?AuditCode={$sAuditCode}' target='_blank'>Live View</a></b></span>";

					if ($sAuditResult == "" && strtotime($sAuditDate) == strtotime(date("Y-m-d")) && getDbValue("COUNT(*)", "tbl_audit_subscriptions", "audit_id='$iAuditId' AND user_id='{$_SESSION['UserId']}'") == 0)
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

//					if ($sStatus == "")
						$sStatus = $sAuditResult;

					switch ($sStatus)
					{
						case "P"  :  $sStatus = "Pass"; break;
						case "F"  :  $sStatus = "Fail"; break;
						case "H"  :  $sStatus = "Hold"; break;
						case "A"  :  $sStatus = "Pass"; break;
						case "B"  :  $sStatus = "Pass"; break;
						case "C"  :  $sStatus = "Fail"; break;
						case "LP" :  $sStatus = ("Likely to Pass at ".((@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE) ? "Firewall" : "Final")." Audit"); break;
						case "PF" :  $sStatus = ("Possible Failure at ".((@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE) ? "Firewall" : "Final")." Audit"); break;
						case "LF" :  $sStatus = ("Likely to Fail at ".((@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE) ? "Firewall" : "Final")." Audit"); break;
					}


					if ($sStatus != "")
						$sTooltip .= "      <div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b>Audit Result:</b> {$sStatus}";
				}

				if ($iCriticalDefects > 0 || $iMajorDefects > 0 || $iMinorDefects > 0)
					$sTooltip .= "      <div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b>Defects:</b> Cr:{$iCriticalDefects}, Mj:{$iMajorDefects}, Mi:{$iMinorDefects}";

				if (count($sPictures) > 0)
					$sTooltip .= ("      <div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b>Defect Pictures:</b> ".count($sPictures));

//				if ($iDefectPics > 0)
//					$sTooltip .= "      <div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b>Pictures Required:</b> {$iDefectPics}";

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


				if ($sCustom == "Y" || @in_array($iReportId, $iQmipReports))
				{
					if ($iWidth < 100 && !@in_array($iReportId, $iQmipReports))
					{
						if ($sCustom != "Y")
							$iWidth = 80;

						else
							$iWidth = 100;
					}

					$sBorderColor     = "#0066ff";
					$sInspectionLabel = "100% Inspection";

					if ($iWidth == 100)
						$sInspectionLabel = "100%";

					else if ($iWidth <= 120)
						$sInspectionLabel = "100% Ins";

					if (@in_array($iReportId, $iQmipReports))
					{
						$sBorderColor     = "#00deff";
						$sInspectionLabel = "QMIP";

						if ($iWidth < 100)
						{
							$sInspectionLabel = "Q";
							$iWidth           = 62;
						}
					}
				}
?>
								  <td width="<?= $iWidth ?>">
									<div id="Audit_<?= $sAuditCode ?>"
<?
				if ($sCustom == "Y" || @in_array($iReportId, $iQmipReports))
				{
?>
									style="overflow:hidden; width:<?= $iWidth ?>px; background:<?= $sBackground ?><?= (($sAuditResult != '') ? ' url(images/icons/done.png) 4px 2px no-repeat' : '') ?>; border-top:solid 4px <?= $sBorderColor ?>; border-left:dotted 1px #ffffff; height:21px; line-height:21px;
<?
				}

				else
				{
?>
									style="overflow:hidden; width:<?= $iWidth ?>px; background:<?= $sBackground ?><?= (($sAuditResult != '') ? ' url(images/icons/done.png) 4px 4px no-repeat' : '') ?>; border-left:dotted 1px #ffffff; height:25px; line-height:25px;
<?
				}
?>
                                    "<?= (($bOnGoing == true) ? ' class="blink"' : '') ?>>
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
				                  	<center><img src="images/icons/pictures.gif" width="16" height="16" vspace="<?= (($sCustom == "Y" || @in_array($iReportId, $iQmipReports)) ? 3 : 5) ?>" alt="" title="" <?= (($sCustom == "Y" || @in_array($iReportId, $iQmipReports)) ? ' align="left" style="margin-left:28px;"' : '') ?> /><?= (($sCustom == "Y" || @in_array($iReportId, $iQmipReports)) ? ('<span style="float:right; font-size:10px; padding-right:5px;">'.$sInspectionLabel.'</span>') : '') ?></center>
<?
				}

				else if ($sCustom == "Y" || @in_array($iReportId, $iQmipReports))
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
												{ title:'<?= $sAuditCode ?> - <?= @htmlentities($sVendorsList[$iVendorId], ENT_QUOTES) ?> - <?= @htmlentities($sAllBrandsList[$iBrandId], ENT_QUOTES) ?>', stem:'topLeft', showOn:'mouseover', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:400, hideOn:{ element:'closeButton', event:'click' }, hideAfter:0.5  });
									-->
									</script>
								  </td>
<?
			}


			$iPrevious = $iEndTime;
		}


		if ($iCount > 0)
		{
?>
								  <td></td>
								</tr>
							  </table>

							</td>
						  </tr>
<?
		}
	}



	if ($iLastAuditor == 0)
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
	for ($i = 0; $i < 24; $i ++)
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
	for ($i = 0; $i < 24; $i ++)
	{
?>
					          	<td width="32"><small><?= substr(date("ga", mktime($i, 0, 0, date("m"), date("d"), date("Y"))), 0, -1) ?></small></td>
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
