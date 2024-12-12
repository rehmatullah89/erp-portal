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


	$sDefectTypes  = array( );
	$iDefectTypes  = array( );
	$iTotalDefects = array( );

	$sSQL = "SELECT dt.id, dt.type AS _DefectType, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND qad.nature>'0' $sConditions
			 GROUP BY dc.type_id

			 UNION

	         SELECT dt.id, dt.type AS _DefectType, COALESCE(SUM(gfd.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_gf_report_defects gfd, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=gfd.audit_id AND gfd.code_id=dc.id AND dc.type_id=dt.id AND qa.report_id='6' $sConditions
			 GROUP BY dc.type_id

			 ORDER BY _Defects DESC, _DefectType ASC
			 LIMIT 5";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDefectType = $objDb->getField($i, "id");
		$sDefectType = $objDb->getField($i, "_DefectType");
		$iDefects    = $objDb->getField($i, "_Defects");

		if (@in_array($iDefectType, $iDefectTypes))
		{
			$iIndex = @array_search($iDefectType, $iDefectTypes);

			$iTotalDefects[$iIndex] += $iDefects;
		}

		else
		{
			$iDefectTypes[]  = $iDefectType;
			$sDefectTypes[]  = $sDefectType;
			$iTotalDefects[] = $iDefects;
		}
	}


	$sDefectColors = getList("tbl_defect_types", "id", "color");


	if ($sGraphTitle == "")
		$sGraphTitle = ((($sFromDate == $sToDate) ? "Daily" : "Monthly")." Defective Pie");

	if ($sFromDate == "" AND $sToDate == "" && $sLastAudits != "" && $sLastAudits != "0")
		$sGraphTitle .= " (Last 15 Audits)";
?>
					<div id="DefectClass<?= $iIndex ?>Chart">loading...</div>

					<script type="text/javascript">
					<!--
					var objChart = new FusionCharts("scripts/fusion-charts/charts/Pie2D.swf", "DefectClass<?= $iIndex ?>", "100%", "<?= (($iHeight > 0) ? $iHeight : 440) ?>", "0", "1");

					objChart.setXMLData("<chart caption='<?= $sGraphTitle ?>' showPercentageInLabel='1' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' bgcolor='ffffff' bordercolor='ffffff' animation='1' labelDisplay='WRAP' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='defect-classification'>" +
<?
	$iTotal = @array_sum($iTotalDefects);

	for ($i = 0; $i < count($iDefectTypes); $i ++)
	{
		$fPercent = @round((($iTotalDefects[$i] / $iTotal) * 100), 2);
?>
										"<set color='<?= $sDefectColors[$iDefectTypes[$i]] ?>' tooltext='Type: <?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?>{br}Defects: <?= $iTotalDefects[$i] ?> (<?= formatNumber($fPercent) ?>%)' label='<?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?> [<?= $iTotalDefects[$i] ?>]' value='<?= $iTotalDefects[$i] ?>' />" +
<?
	}
?>

										"</chart>");


					objChart.render("DefectClass<?= $iIndex ?>Chart");
					-->
					</script>
