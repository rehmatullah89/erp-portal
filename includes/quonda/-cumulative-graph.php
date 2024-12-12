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

	if (@is_array(IO::getArray("AuditStage")))
		$AuditStage = ("'".@implode("','", IO::getArray("AuditStage"))."'");

	$sVendors     = array( );
	$iCategories  = array( );
	$iGmts        = array( );
	$iDefects     = array( );
	$iAudits      = array( );
	$iStages      = array( );
	$iStageAudits = array( );
	$fStageDhu    = array( );


	$sSQL1 = "SELECT po.vendor_id AS _VendorId, qa.audit_stage AS _AuditStage,
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
			       AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND NOT FIND_IN_SET(qa.report_id, '$sQmipReports') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";


	$sSQL2 = "SELECT po.vendor_id AS _VendorId, qa.audit_stage AS _AuditStage,
					COUNT(DISTINCT(qa.id)) AS _TotalAudits,
					SUM( (SELECT COALESCE(SUM(actual_1 + actual_2 + actual_3), 0) FROM tbl_gf_rolls_info WHERE audit_id=qa.id) ) AS _TotalGmts,
					SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=qa.id) ) AS _TotalDefects
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND qa.report_id='6'
			       AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND NOT FIND_IN_SET(qa.report_id, '$sQmipReports') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";


	$sSubSql = " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

	if ($FromDate != "" && $ToDate != "")
		$sSubSql .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

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

	if ($StyleNo != "")
	{
		$sSubSql .= " AND (";

		$sSQL = "SELECT id FROM tbl_styles WHERE style LIKE '%$StyleNo%'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iStyleId = $objDb->getField($i, 0);

			if ($i > 0)
				$sSubSql .= " OR ";

			$sSubSql .= " qa.style_id='$iStyleId' ";
		}

		$sSubSql .= ") ";
	}

	if ($Vendor > 0)
		$sSubSql .= " AND po.vendor_id='$Vendor' ";

	else
		$sSubSql .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Brand > 0)
		$sSubSql .= " AND po.brand_id='$Brand' ";

	else
		$sSubSql .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($AuditStage != "''" && $AuditStage != "")
		$sSubSql .= " AND qa.audit_stage IN ($AuditStage) ";

	$sSubSql .= " GROUP BY po.vendor_id, qa.audit_stage";


	$sSQL = "$sSQL1 $sSubSql UNION $sSQL2 $sSubSql ORDER BY _VendorId, _AuditStage";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iVendorId     = $objDb->getField($i, "_VendorId");
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
		$iVendorId     = $objDb->getField($i, "_VendorId");
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
			    <div class="tblSheet">
				  <div id="AuditStageChart">loading...</div>

				  <script type="text/javascript">
				  <!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/StackedColumn3D.swf", "AuditStage", "920", "500", "0", "1");

						objChart.setXMLData("<chart caption='<?= ("{$sStage} Audits from ".formatDate($FromDate)." to ".formatDate($ToDate)) ?>' numDivLines='10' formatNumberScale='0' showValues='0' showSum='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='ROTATE' slantLabels='1' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='commulative-graph'>" +

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
											"<set value='<?= $fStageDhu[$iVendor][$sCode] ?>' tooltext='Stage: <?= $sStage ?>{br}Vendor: <?= htmlentities($sVendor, ENT_QUOTES) ?>{br}Audits: <?= $iStageAudits[$iVendor][$sCode] ?>{br}DHU: <?= formatNumber($fStageDhu[$iVendor][$sCode]) ?>%' link='<?= SITE_URL ?>quonda/quonda-graphs.php?Vendor=<?= $iVendor ?>&OrderNo=<?= $OrderNo ?>&StyleNo=<?= $StyleNo ?>&AuditCode=<?= $AuditCode ?>&Brand=<?= $Brand ?>&FromDate=<?= $FromDate ?>&ToDate=<?= $ToDate ?>&Category=<?= $sVendorCategoriesList[$iVendor] ?>&Sector=<?= $sStageIdsList[$sCode] ?>&Step=1' />" +
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

				  <br />
			    </div>
