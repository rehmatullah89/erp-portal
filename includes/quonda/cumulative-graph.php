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

	$sVendors       = array( );
	$iVendorStages  = array( );
	$iGmts          = array( );
	$iDefects       = array( );
	$iAudits        = array( );
	$iStages        = array( );
	$iStageAudits   = array( );
	$fStageDhu      = array( );

	$iInlineGmts    = array( );
	$iInlineDefects = array( );
	$iInlineAudits  = array( );
	$fInlineDhu     = array( );

	$iFinalGmts     = array( );
	$iFinalDefects  = array( );
	$iFinalAudits   = array( );
	$fFinalDhu      = array( );

	$iPassGmts      = array( );
	$iPassDefects   = array( );
	$iPassAudits    = array( );
	$fPassDhu       = array( );


	$sSQL1 = "SELECT po.vendor_id AS _VendorId, qa.audit_stage AS _AuditStage, qa.audit_result AS _AuditResult,
					COUNT(DISTINCT(qa.id)) AS _TotalAudits,
					COALESCE(SUM(qa.total_gmts), 0) AS _TotalGmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature>'0')) AS _TotalDefects
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' $sAuditorSQL
			       AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

	$sSubSql = " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}'))) ";

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

	$sSubSql .= " GROUP BY po.vendor_id, qa.audit_stage, qa.audit_result";


	$sSQL = "$sSQL1 $sSubSql ORDER BY _VendorId, _AuditStage";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iVendorId     = $objDb->getField($i, "_VendorId");
		$sAuditStage   = $objDb->getField($i, "_AuditStage");
		$sAuditResult  = $objDb->getField($i, "_AuditResult");
		$iTotalAudits  = $objDb->getField($i, "_TotalAudits");
		$iTotalGmts    = $objDb->getField($i, "_TotalGmts");
		$iTotalDefects = $objDb->getField($i, "_TotalDefects");


		$iGmts[$iVendorId]    += $iTotalGmts;
		$iDefects[$iVendorId] += $iTotalDefects;
		$iAudits[$iVendorId]  += $iTotalAudits;

		if (!@in_array($iVendorId, $sVendors))
			$sVendors[$iVendorId] = $sVendorsList[$iVendorId];

		if (!@in_array("{$iVendorId}-{$sAuditStage}", $iVendorStages))
		{
			$iVendorStages["{$iVendorId}-{$sAuditStage}"] = 1;

			$iStages[$iVendorId] ++;
		}


		if ($sAuditStage == "F" || $sAuditStage == "FR")
		{
			$iFinalGmts[$iVendorId]    += $iTotalGmts;
			$iFinalDefects[$iVendorId] += $iTotalDefects;
			$iFinalAudits[$iVendorId]  += $iTotalAudits;

			if ($sAuditResult == "P" || $sAuditResult == "A" || $sAuditResult == "B")
			{
				$iPassGmts[$iVendorId]    += $iTotalGmts;
				$iPassDefects[$iVendorId] += $iTotalDefects;
				$iPassAudits[$iVendorId]  += $iTotalAudits;
			}
		}

		else
		{
			$iInlineGmts[$iVendorId]    += $iTotalGmts;
			$iInlineDefects[$iVendorId] += $iTotalDefects;
			$iInlineAudits[$iVendorId]  += $iTotalAudits;
		}
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


	foreach ($sVendors as $iVendor => $sVendor)
	{
		$fInlineDhu[$iVendor] = @round((($iInlineDefects[$iVendor] / $iInlineGmts[$iVendor]) * 100), 2);
		$fFinalDhu[$iVendor]  = @round((($iFinalDefects[$iVendor] / $iFinalGmts[$iVendor]) * 100), 2);
		$fPassDhu[$iVendor]   = @round((($iPassDefects[$iVendor] / $iPassGmts[$iVendor]) * 100), 2);
	}


	$sStage = "";

	if (strlen($AuditStage) <= 2)
		$sStage = $sAuditStagesList[$AuditStage];
?>
			    <div class="tblSheet">
				  <div id="FinalAuditsChart">loading...</div>

				  <script type="text/javascript">
				  <!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", "FinalAudits", "920", "500", "0", "1");

						objChart.setXMLData("<chart caption='<?= ("{$sStage} Audits from ".formatDate($FromDate)." to ".formatDate($ToDate)) ?>' numDivLines='10' formatNumberScale='0' showValues='1' showSum='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='ROTATE' slantLabels='1' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='commulative-graph'>" +

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


											"<dataset seriesName='Inline Audits' color=''>" +
<?
	foreach ($sVendors as $iVendor => $sVendor)
	{
		$sParams = $_SERVER['QUERY_STRING'];

		if ($sParams != "")
		{
			if (@strpos($sParams, "Vendor={$iVendor}") === FALSE)
				$sParams .= "&Vendor={$iVendor}";
		}
?>
											"<set value='<?= floatval($fInlineDhu[$iVendor]) ?>' tooltext='Inline Audits{br}Vendor: <?= htmlentities($sVendor, ENT_QUOTES) ?>{br}Audits: <?= intval($iInlineAudits[$iVendor]) ?>{br}Quantity: <?= intval($iInlineGmts[$iVendor]) ?>{br}Defects: <?= intval($iInlineDefects[$iVendor]) ?>{br}DR: <?= formatNumber($fInlineDhu[$iVendor]) ?>%' link='javascript:showInlinesGraph(\"<?= $sParams ?>\")' />" +
<?
	}
?>
											"</dataset>" +

											"<dataset seriesName='Final Audits' renderAs='Line' color='#0000ff'>" +
<?
	foreach ($sVendors as $iVendor => $sVendor)
	{
?>
											"<set value='<?= floatval($fFinalDhu[$iVendor]) ?>' tooltext='Final Audits{br}Vendor: <?= htmlentities($sVendor, ENT_QUOTES) ?>{br}Audits: <?= intval($iFinalAudits[$iVendor]) ?>{br}Quantity: <?= intval($iFinalGmts[$iVendor]) ?>{br}Defects: <?= intval($iFinalDefects[$iVendor]) ?>{br}DR: <?= formatNumber($fFinalDhu[$iVendor]) ?>%' link='' />" +
<?
	}
?>
											"</dataset>" +

											"<dataset seriesName='Passed Final Audits' renderAs='Line' color='#00ff00'>" +
<?
	foreach ($sVendors as $iVendor => $sVendor)
	{
?>
											"<set value='<?= floatval($fPassDhu[$iVendor]) ?>' tooltext='Passed Pass Audits{br}Vendor: <?= htmlentities($sVendor, ENT_QUOTES) ?>{br}Audits: <?= intval($iPassAudits[$iVendor]) ?>{br}Quantity: <?= intval($iPassGmts[$iVendor]) ?>{br}Defects: <?= intval($iPassDefects[$iVendor]) ?>{br}DR: <?= formatNumber($fPassDhu[$iVendor]) ?>%' link='' />" +
<?
	}
?>
											"</dataset>" +

										"</chart>");


						objChart.render("FinalAuditsChart");
				  -->
				  </script>

				  <br />
			    </div>


			    <div class="tblSheet" id="InlineGraphDiv" style="display:none; margin-top:15px;">
				  <div id="InlinesChart">loading...</div>

				  <br />
				</div>