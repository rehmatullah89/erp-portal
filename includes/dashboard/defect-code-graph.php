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

	$sConditions = " AND qa.audit_type='B' AND qa.audit_result!='' AND NOT FIND_IN_SET(qa.report_id, '$sQmipReports') ";

	if ($iDepartment > 0)
		$sConditions .= " AND qa.department_id='$iDepartment' ";

	if ($sBrands != "")
		$sConditions .= " AND FIND_IN_SET(qa.brand_id, '$sBrands') ";

	if ($iBrand > 0)
		$sConditions .= " AND qa.brand_id='$iBrand' ";

	if ($AuditStage != "")
		$sConditions .= " AND qa.audit_stage='$AuditStage' ";

	else
		$sConditions .= " AND qa.audit_stage!='' ";

	if ($sVendors != "")
		$sConditions .= " AND FIND_IN_SET(qa.vendor_id, '$sVendors') ";

	if ($iVendor > 0)
		$sConditions .= " AND qa.vendor_id='$iVendor' ";

	if ($Vendor > 0)
		$sConditions .= " AND qa.vendor_id='$Vendor' ";

	if ($sFromDate != "" AND $sToDate != "")
		$sConditions .= " AND (qa.audit_date BETWEEN '$sFromDate' AND '$sToDate') ";

	else if ($sLastAudits != "" && $sLastAudits != "0")
		$sConditions .= " AND FIND_IN_SET(qa.id, '$sLastAudits') ";


	$sDefectCodes = array( );
	$iDefectCodes = array( );
	$sDefects     = array( );
	$iDefects     = array( );
	$sColors      = array( );

	$sSQL = "SELECT dc.id AS _Id, dc.code AS _Code, dc.defect AS _Defect, COALESCE(SUM(qad.defects), 0) AS _Defects, dt.id AS _Type
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND qa.report_id!='6' AND qad.nature>'0' $sConditions
			 GROUP BY dc.id

	         UNION

	         SELECT dc.id AS _Id, dc.code AS _Code, dc.defect AS _Defect, COALESCE(SUM(gfd.defects), 0) AS _Defects, dt.id AS _Type
			 FROM tbl_qa_reports qa, tbl_gf_report_defects gfd, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=gfd.audit_id AND gfd.code_id=dc.id AND dc.type_id=dt.id AND qa.report_id='6' $sConditions
			 GROUP BY dc.id

			 ORDER BY _Defects DESC
			 LIMIT 5";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDefectCode   = $objDb->getField($i, "_Id");
		$sDefectCode   = $objDb->getField($i, "_Code");
		$sDefectName   = $objDb->getField($i, "_Defect");
		$iDefectsCount = $objDb->getField($i, "_Defects");
		$iDefectType   = $objDb->getField($i, "_Type");

		if (@in_array($iDefectCode, $iDefectCodes))
		{
			$iIndex = @array_search($iDefectCode, $iDefectCodes);

			$iDefects[$iIndex] += $iDefectsCount;
		}

		else
		{
			$iDefectCodes[] = $iDefectCode;
			$sDefectCodes[] = $sDefectCode;
			$sDefects[]     = $sDefectName;
			$iDefects[]     = $iDefectsCount;
			$sColors[]      = $sDefectColors[$iDefectType];
		}
	}

	$sDefectCode = $sDefectCodes[@array_search($Code, $iDefectCodes)];
	$sDefectTile = $sDefects[@array_search($Code, $iDefectCodes)];


	$iMaxDefects = 0;

	for ($i = 0; $i < count($iDefects); $i ++)
	{
		if ($iDefects[$i] > $iMaxDefects)
			$iMaxDefects = $iDefects[$i];
	}

	$iMaxDefects += @ceil($iMaxDefects * 0.2);


	if ($sGraphTitle == "")
		$sGraphTitle = ((($sFromDate == $sToDate) ? "Daily" : "Monthly")." Top 5 Defects");

	if ($sFromDate == "" AND $sToDate == "" && $sLastAudits != "" && $sLastAudits != "0")
		$sGraphTitle .= " (Last 15 Audits)";
?>
						<div id="DefectCode<?= $iIndex ?>Chart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "DefectCode<?= $iIndex ?>", "100%", "<?= (($iHeight > 0) ? $iHeight : 440) ?>", "0", "1");

						objChart.setXMLData("<chart caption='<?= $sGraphTitle ?>' yAxisMinValue='0' yAxisMaxValue='<?= $iMaxDefects ?>' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='' chartBottomMargin='5' plotFillAlpha='95' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='<?= strtolower(str_replace(" ", "-", $sDefectType)) ?>'>" +
<?
	for ($i = 0; $i < count($iDefects); $i ++)
	{
?>
											"<set color='<?= $sColors[$i] ?>' tooltext='<?= htmlentities($sDefects[$i], ENT_QUOTES) ?>{br}Code: <?= $sDefectCodes[$i] ?>{br}Defects: <?= $iDefects[$i] ?>' label='<?= htmlentities($sDefects[$i], ENT_QUOTES) ?>' value='<?= $iDefects[$i] ?>' />" +
<?
	}
?>
										    "</chart>");


						objChart.render("DefectCode<?= $iIndex ?>Chart");
						-->
						</script>
