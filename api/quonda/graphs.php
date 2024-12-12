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

	@include("graphs/input-data.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
</head>

<body style="background:#ffffff; padding:15px;">

<!-- Audit Stage wise Graph -->
<?
	$sAuditStagesList      = getList("tbl_audit_stages", "code", "stage");
	$sStageColorsList      = getList("tbl_audit_stages", "code", "color");
	$sVendorCategoriesList = getList("tbl_vendors", "id", "category_id", "id IN ($sUserVendors) AND parent_id='0' AND sourcing='Y'");


	$AuditStage = ("'".@implode("','", IO::getArray("AuditStage"))."'");

	$sVendors     = array( );
	$iCategories  = array( );
	$iGmts        = array( );
	$iDefects     = array( );
	$iAudits      = array( );
	$iStages      = array( );
	$iStageAudits = array( );
	$fStageDhu    = array( );


	$sSQL1 = "SELECT po.vendor_id AS _Vendor, qa.audit_stage AS _AuditStage,
					COUNT(DISTINCT(qa.id)) AS _TotalAudits,
					COALESCE(SUM(qa.total_gmts), 0) AS _TotalGmts,
					SUM(
					     IF (qa.report_id=10,
					         (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature='1'),
					         (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)
					        )
					   ) AS _TotalDefects
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND qa.report_id!='6'
			 AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate')";


	$sSQL2 = "SELECT po.vendor_id AS _Vendor, qa.audit_stage AS _AuditStage,
					COUNT(DISTINCT(qa.id)) AS _TotalAudits,
					SUM( (SELECT COALESCE(SUM(actual_1 + actual_2 + actual_3), 0) FROM tbl_gf_rolls_info WHERE audit_id=qa.id) ) AS _TotalGmts,
					SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=qa.id) ) AS _TotalDefects
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND qa.report_id='6'
			 AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate')";


	$sSubSql = "";

	if ($AuditCode != "")
		$sSubSql .= " AND qa.audit_code LIKE '%$AuditCode%' ";

	if ($OrderNo != "")
	{
		$sSubSql .= " AND (";

		$sSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '%$OrderNo%'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPoId = $objDb->getField($i, 0);

			if ($i > 0)
				$sSubSql .= " OR ";

			$sSubSql .= " qa.po_id='$iPoId' OR FIND_IN_SET('$iPoId', qa.additional_pos) ";
		}

		$sSubSql .= ") ";
	}


	if ($Vendor > 0)
		$sSubSql .= " AND po.vendor_id='$Vendor' ";

	else
		$sSubSql .= " AND FIND_IN_SET(po.vendor_id, '$sUserVendors') ";


	if ($Brand > 0)
		$sSubSql .= " AND po.brand_id='$Brand' ";

	else
		$sSubSql .= " AND FIND_IN_SET(po.brand_id, '$sUserBrands') ";


	if ($AuditStage != "''" && $AuditStage != "")
		$sSubSql .= " AND qa.audit_stage IN ($AuditStage) ";

	else
		$sSubSql .= " AND qa.audit_stage!='' ";

	$sSubSql .= " GROUP BY po.vendor_id, qa.audit_stage";


	$sSQL = "$sSQL1 $sSubSql UNION $sSQL2 $sSubSql ORDER BY _Vendor, _AuditStage";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iVendorId     = $objDb->getField($i, "_Vendor");
		$sAuditStage   = $objDb->getField($i, "_AuditStage");
		$iTotalAudits  = $objDb->getField($i, "_TotalAudits");
		$iTotalGmts    = $objDb->getField($i, "_TotalGmts");
		$iTotalDefects = $objDb->getField($i, "_TotalDefects");


		$sVendors[$iVendorId]  = $sVendorsList[$iVendorId];
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

	if (strlen($AuditStage) <= 2)
		$sStage = $sAuditStagesList[$AuditStage];

	if ($AuditStage == "F" && @strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE)
		$sStage = "Firewall";
?>

						<div id="AuditStageChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/StackedColumn3D.swf", "AuditStage", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='<?= ("{$sStage} Audits from ".formatDate($FromDate)." to ".formatDate($ToDate)) ?>' numDivLines='10' formatNumberScale='0' showValues='0' showSum='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='ROTATE' slantLabels='1'>" +

											"<categories>" +
<?
	foreach ($sVendors as $iVendor => $sVendor)
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
		foreach ($sVendors as $iVendor => $sVendor)
		{
?>
											"<set value='<?= $fStageDhu[$iVendor][$sCode] ?>' tooltext='Stage: <?= $sStage ?>{br}Vendor: <?= htmlentities($sVendor, ENT_QUOTES) ?>{br}Audits: <?= $iStageAudits[$iVendor][$sCode] ?>{br}DHU: <?= formatNumber($fStageDhu[$iVendor][$sCode]) ?>%' link='<?= SITE_URL ?>api/quonda/graphs-s1.php?User=<?= $User ?>&Vendor=<?= $iVendor ?>&OrderNo=<?= $OrderNo ?>&AuditCode=<?= $AuditCode ?>&Brand=<?= $Brand ?>&FromDate=<?= $FromDate ?>&ToDate=<?= $ToDate ?>&Category=<?= $sVendorCategoriesList[$iVendor] ?>&Sector=<?= $sStageIdsList[$sCode] ?>' />" +
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

						<hr />


<!-- Audit Status wise Graph -->
<?
	$iPass    = array( );
	$iFail    = array( );
	$iHold    = array( );
	$sVendors = array( );
	$iVendors = array( );

	$sSQL = "SELECT po.vendor_id, qa.audit_result,
					COUNT(DISTINCT(qa.id)) AS _TotalAudits
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!=''
			 AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate')";

	if ($AuditCode != "")
		$sSQL .= " AND qa.audit_code LIKE '%$AuditCode%' ";

	if ($OrderNo != "")
		$sSQL .= " AND po.order_no LIKE '%$OrderNo%' ";


	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSubSql .= " AND FIND_IN_SET(po.vendor_id, '$sUserVendors') ";


	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSubSql .= " AND FIND_IN_SET(po.brand_id, '$sUserBrands') ";


	if ($AuditStage != "''" && $AuditStage != "")
		$sSQL .= " AND qa.audit_stage IN ($AuditStage) ";

	else
		$sSQL .= " AND qa.audit_stage!='' ";

	$sSQL .= "GROUP BY po.vendor_id, qa.audit_result
	          ORDER BY po.vendor_id";

	$objDb->query($sSQL);


	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iVendorId    = $objDb->getField($i, "vendor_id");
		$sAuditResult = $objDb->getField($i, "audit_result");
		$iTotalAudits = $objDb->getField($i, "_TotalAudits");

		if (!@in_array($iVendorId, $iVendors))
		{
			$sVendors[] = $sVendorsList[$iVendorId];
			$iVendors[] = $iVendorId;

			$iPass[] = 0;
			$iFail[] = 0;
			$iHold[] = 0;
		}


		$iIndex = @array_search($iVendorId, $iVendors);

		switch ($sAuditResult)
		{
			case "P" : $iPass[$iIndex] = $iTotalAudits;  break;
			case "F" : $iFail[$iIndex] = $iTotalAudits;  break;
			case "H" : $iHold[$iIndex] = $iTotalAudits;  break;
		}
	}
?>

						<div id="AuditStatusChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/StackedColumn3D.swf", "AuditStatus", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='<?= ("{$sStage} Audits from ".formatDate($FromDate)." to ".formatDate($ToDate)) ?>' numDivLines='10' formatNumberScale='0' showValues='0' showSum='1' showLabels='1' decimals='1' numberSuffix='' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='ROTATE' slantLabels='1'>" +

											"<categories>" +
<?
	for ($i = 0; $i < count($iVendors); $i ++)
	{
?>
											"<category label='<?= $sVendorsList[$iVendors[$i]] ?>' />" +
<?
	}
?>
											"</categories>" +


											"<dataset seriesName='Pass' color='7fff7f'>" +
<?
	for ($i = 0; $i < count($iVendors); $i ++)
	{
?>
											"<set value='<?= $iPass[$i] ?>' link='<?= SITE_URL ?>api/quonda/graphs-s1.php?User=<?= $User ?>&Vendor=<?= $iVendors[$i] ?>&OrderNo=<?= $OrderNo ?>&AuditCode=<?= $AuditCode ?>&Brand=<?= $Brand ?>&FromDate=<?= $FromDate ?>&ToDate=<?= $ToDate ?>&Category=<?= $iCategories[$i] ?>' />" +
<?
	}
?>
											"</dataset>" +

											"<dataset seriesName='Fail' color='ff0000'>" +
<?
	for ($i = 0; $i < count($iVendors); $i ++)
	{
?>
											"<set value='<?= $iFail[$i] ?>' link='<?= SITE_URL ?>api/quonda/graphs-s1.php?User=<?= $User ?>&Vendor=<?= $iVendors[$i] ?>&OrderNo=<?= $OrderNo ?>&AuditCode=<?= $AuditCode ?>&Brand=<?= $Brand ?>&FromDate=<?= $FromDate ?>&ToDate=<?= $ToDate ?>&Category=<?= $iCategories[$i] ?>' />" +
<?
	}
?>
											"</dataset>" +

											"<dataset seriesName='Hold' color='fcbf04'>" +
<?
	for ($i = 0; $i < count($iVendors); $i ++)
	{
?>
											"<set value='<?= $iHold[$i] ?>' link='<?= SITE_URL ?>api/quonda/graphs-s1.php?User=<?= $User ?>&Vendor=<?= $iVendors[$i] ?>&OrderNo=<?= $OrderNo ?>&AuditCode=<?= $AuditCode ?>&Brand=<?= $Brand ?>&FromDate=<?= $FromDate ?>&ToDate=<?= $ToDate ?>&Category=<?= $iCategories[$i] ?>' />" +
<?
	}
?>
											"</dataset>" +

										"</chart>");


						objChart.render("AuditStatusChart");
						-->
						</script>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>