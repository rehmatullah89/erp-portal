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

	$aVendors     = array( );
	$iGmts        = array( );
	$iDefects     = array( );
	$iAudits      = array( );
	$iStages      = array( );
	$iStageAudits = array( );
	$fStageDhu    = array( );


	$sConditions = " AND NOT FIND_IN_SET(qa.report_id, '$sQmipReports') AND qa.audit_type='B' AND qa.audit_result!='' ";

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


	$sSQL = "SELECT po.vendor_id AS _Vendor, qa.audit_stage AS _AuditStage,
					COUNT(DISTINCT(qa.id)) AS _TotalAudits,
					COALESCE(SUM(qa.total_gmts), 0) AS _TotalGmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature>'0')) AS _TotalDefects
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.report_id!='6'
			       $sConditions
			 GROUP BY po.vendor_id, qa.audit_stage

			 UNION

	         SELECT po.vendor_id AS _Vendor, qa.audit_stage AS _AuditStage,
					COUNT(DISTINCT(qa.id)) AS _TotalAudits,
					SUM( (SELECT COALESCE(SUM(actual_1 + actual_2 + actual_3), 0) FROM tbl_gf_rolls_info WHERE audit_id=qa.id) ) AS _TotalGmts,
					SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=qa.id) ) AS _TotalDefects
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.report_id='6'
			       $sConditions
			 GROUP BY po.vendor_id, qa.audit_stage
			 ORDER BY _Vendor, _AuditStage";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iVendorId     = $objDb->getField($i, "_Vendor");
		$sAuditStage   = $objDb->getField($i, "_AuditStage");
		$iTotalAudits  = $objDb->getField($i, "_TotalAudits");
		$iTotalGmts    = $objDb->getField($i, "_TotalGmts");
		$iTotalDefects = $objDb->getField($i, "_TotalDefects");


		$aVendors[$iVendorId]  = $sVendorsList[$iVendorId];
		$iGmts[$iVendorId]    += $iTotalGmts;
		$iDefects[$iVendorId] += $iTotalDefects;
		$iAudits[$iVendorId]  += $iTotalAudits;
		$iStages[$iVendorId]  ++;
	}


	for ($i = 0; $i < $iCount; $i ++)
	{
		$iVendorId     = $objDb->getField($i, "_Vendor");
		$sAuditStage   = $objDb->getField($i, "_AuditStage");
		$iTotalAudits  = $objDb->getField($i, "_TotalAudits");
		$iTotalGmts    = $objDb->getField($i, "_TotalGmts");
		$iTotalDefects = $objDb->getField($i, "_TotalDefects");


		$fStgDhu = @round((($iTotalDefects / $iTotalGmts) * 100), 2);
		$fNetDhu = @round((($iDefects[$iVendorId] / $iGmts[$iVendorId]) * 100), 2);
		$fAvgDhu = @round(($fNetDhu / $iStages[$iVendorId]), 3);

		$fStageDhu[$iVendorId][$sAuditStage]    = @round((($fStgDhu / $fNetDhu) * $fAvgDhu), 2);
		$iStageAudits[$iVendorId][$sAuditStage] = $iTotalAudits;
	}


	$sStage = "";

	if ($AuditStage != "")
		$sStage = $sAuditStagesList[$AuditStage];

	if ($AuditStage == "F" && @strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE)
		$sStage = "Firewall";


	if ($Vendor > 0)
		$sCategory = getDbValue("category_id", "tbl_vendors", "id='$Vendor'");

	if ($sGraphTitle == "")
		$sGraphTitle = (($sStage != "") ? $sStage : "Overall ").(($Category == 8) ? 'Defective Points' : 'Defects Rate');

	if ($sFromDate == "" AND $sToDate == "" && $sLastAudits != "" && $sLastAudits != "0")
		$sGraphTitle .= " (Last 15 Audits)";
?>
				  <div id="AuditStageChart">loading...</div>

				  <script type="text/javascript">
				  <!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/StackedColumn3D.swf", "AuditStage", "100%", "<?= (($iHeight > 0) ? $iHeight : 440) ?>", "0", "1");

						objChart.setXMLData("<chart caption='<?= $sGraphTitle ?>' numDivLines='10' formatNumberScale='0' showValues='0' showSum='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='ROTATE' slantLabels='1' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='commulative-graph'>" +

											"<categories>" +
<?
	foreach ($aVendors as $iVendor => $sVendor)
	{
?>
											"<category label='<?= htmlentities($sVendor, ENT_QUOTES) ?>' />" +
<?
	}
?>
											"</categories>" +

<?
	foreach ($sAuditStagesList as $sCode => $sStage)
	{
		if ($AuditStage == "F" && @strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE)
			$sStage = "Firewall";
?>
											"<dataset seriesName='<?= $sStage ?>' color='<?= $sStageColorsList[$sCode] ?>'>" +
<?
		foreach ($aVendors as $iVendor => $sVendor)
		{
?>
											"<set value='<?= $fStageDhu[$iVendor][$sCode] ?>' tooltext='Stage: <?= $sStage ?>{br}Vendor: <?= htmlentities($sVendor, ENT_QUOTES) ?>{br}Audits: <?= $iStageAudits[$iVendor][$sCode] ?>{br}DHU: <?= formatNumber($fStageDhu[$iVendor][$sCode]) ?>%' link='<?= SITE_URL ?>dashboard/reports.php?Vendor=<?= $iVendor ?>&AuditStage=<?= $sCode ?>&Date=<?= $Date ?>' />" +
<?
		}
?>
											"</dataset>" +
<?
	}
?>
										"</chart>");


						objChart.render("AuditStageChart");
				  -->
				  </script>
