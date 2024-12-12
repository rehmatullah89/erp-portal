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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$Vendor     = IO::intValue("Vendor");
	$Brand      = IO::intValue("Brand");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");
	$Report     = IO::intValue("Report");
	$AuditStage = IO::strValue("AuditStage");
	$Lines      = IO::strValue("Line");


	if ($Vendor > 0)
		$sVendorBrands = getDbValue("GROUP_CONCAT(DISTINCT(brand_id) SEPARATOR ',')", "tbl_po", "vendor_id='$Vendor'");
	
	else
		$sVendorBrands = getDbValue("GROUP_CONCAT(DISTINCT(brand_id) SEPARATOR ',')", "tbl_po", "FIND_IN_SET(vendor_id, '$sQmipVendors')");

	$sReportTypes  = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStages  = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");

	if ($Vendor > 0)
		$sUnitsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='$Vendor' AND sourcing='Y'");
	
	else
		$sUnitsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND FIND_IN_SET(parent_id, '$sQmipVendors') AND sourcing='Y'");
	

	$sConditions  = " AND qa.audit_type='B' AND qa.audit_result!='' ";
	$sConditions .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '$sVendorBrands') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

	if ($Vendor > 0)
		$sConditions .= " AND qa.vendor_id='$Vendor' ";
	
	else
		$sConditions .= " AND FIND_IN_SET(qa.vendor_id, '$sQmipVendors') ";

	if ($AuditStage != "")
		$sConditions .= " AND qa.audit_stage='$AuditStage' ";

	else
		$sConditions .= " AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

	if ($Brand > 0)
		$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id='$Brand' AND vendor_id='$Vendor') ";

	if ($Report > 0)
		$sConditions .= " AND qa.report_id='$Report' ";

	else
		$sConditions .= " AND FIND_IN_SET(qa.report_id, '$sReportTypes') ";

	if ($Lines != "")
		$sConditions .= " AND FIND_IN_SET(qa.line_id, '$Lines') ";




	$f3rdPartyInlineDr       = array( );
	$i3rdPartyInlineGmts     = array( );
	$i3rdPartyInlineDefects  = array( );

	$f3rdPartyFinalDr        = array( );
	$i3rdPartyFinalGmts      = array( );
	$i3rdPartyFinalDefects   = array( );

	$fQmipInlineDr           = array( );
	$iQmipInlineGmts         = array( );
	$iQmipInlineDefects      = array( );

	$fQmipCoRelationDr       = array( );
	$iQmipCoRelationGmts     = array( );
	$iQmipCoRelationDefects  = array( );

	$fFinalAuditsDr          = array( );
	$iFinalAuditsGmts        = array( );
	$iFinalAuditsDefects     = array( );

	$fFinalPassAuditsDr      = array( );
	$iFinalPassAuditsGmts    = array( );
	$iFinalPassAuditsDefects = array( );



	$sSQL = "SELECT qa.unit_id,
					qa.audit_result,
					COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects
			 FROM tbl_qa_reports qa
			 WHERE qa.audit_stage='F' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
			 GROUP BY qa.unit_id, qa.audit_result";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iUnit    = $objDb->getField($i, "unit_id");
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");
		$sResult  = $objDb->getField($i, "audit_result");


		if ($sResult == "A" || $sResult == "B" || $sResult == "P")
		{
			$iFinalPassAuditsGmts[$iUnit]    += $iGmts;
			$iFinalPassAuditsDefects[$iUnit] += $iDefects;
			$fFinalPassAuditsDr[$iUnit]       = @round((($iFinalPassAuditsDefects[$iUnit] / $iFinalPassAuditsGmts[$iUnit]) * 100), 2);
		}


		$iFinalAuditsGmts[$iUnit]    += $iGmts;
		$iFinalAuditsDefects[$iUnit] += $iDefects;
		$fFinalAuditsDr[$iUnit]       = @round((($iFinalAuditsDefects[$iUnit] / $iFinalAuditsGmts[$iUnit]) * 100), 2);


		if ($iUnit == 0)
			$sUnitsList[0] = $sVendorsList[$Vendor];
	}


	$sSQL = "SELECT qa.unit_id,
					qa.audit_stage,
					COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects
			 FROM tbl_qa_reports qa, tbl_users u
			 WHERE qa.user_id=u.id AND u.auditor_type='1' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
			 GROUP BY qa.unit_id, qa.audit_stage";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iUnit    = $objDb->getField($i, "unit_id");
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");
		$sStage   = $objDb->getField($i, "audit_stage");


		if ($sStage == "F")
		{
			$i3rdPartyFinalGmts[$iUnit]    += $iGmts;
			$i3rdPartyFinalDefects[$iUnit] += $iDefects;
			$f3rdPartyFinalDr[$iUnit]      = @round((($i3rdPartyFinalDefects[$iUnit] / $i3rdPartyFinalGmts[$iUnit]) * 100), 2);
		}

		else
		{
			$i3rdPartyInlineGmts[$iUnit]    += $iGmts;
			$i3rdPartyInlineDefects[$iUnit] += $iDefects;
			$f3rdPartyInlineDr[$iUnit]     = @round((($i3rdPartyInlineDefects[$iUnit] / $i3rdPartyInlineGmts[$iUnit]) * 100), 2);
		}


		if ($iUnit == 0)
			$sUnitsList[0] = $sVendorsList[$Vendor];
	}



	$sSQL = "SELECT qa.unit_id,
					COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects
			 FROM tbl_qa_reports qa, tbl_users u
			 WHERE qa.user_id=u.id AND u.auditor_type='2' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
			 GROUP BY qa.unit_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iUnit    = $objDb->getField($i, "unit_id");
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");


		$iQmipInlineGmts[$iUnit]    = $iGmts;
		$iQmipInlineDefects[$iUnit] = $iDefects;
		$fQmipInlineDr[$iUnit]      = @round((($iDefects / $iGmts) * 100), 2);


		if ($iUnit == 0)
			$sUnitsList[0] = $sVendorsList[$Vendor];
	}



	$sSQL = "SELECT qa.unit_id,
					COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects
			 FROM tbl_qa_reports qa, tbl_users u
			 WHERE qa.user_id=u.id AND u.auditor_type='3' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
			 GROUP BY qa.unit_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iUnit    = $objDb->getField($i, "unit_id");
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");


		$iQmipCoRelationGmts[$iUnit]    = $iGmts;
		$iQmipCoRelationDefects[$iUnit] = $iDefects;
		$fQmipCoRelationDr[$iUnit]      = @round((($iDefects / $iGmts) * 100), 2);


		if ($iUnit == 0)
			$sUnitsList[0] = $sVendorsList[$Vendor];
	}


	$sQueryString = "FromDate={$sFromDate}&ToDate={$ToDate}&Vendor={$Vendor}";

	if ($AuditStage != "")
		$sQueryString .= "&AuditStage={$AuditStage}";

	if ($Brand > 0)
		$sQueryString .= "&Brand={$Brand}";

	if ($Report > 0)
		$sQueryString .= "&Report={$Report}";

	if ($Lines != "")
		$sQueryString .= "&Line={$Lines}";
?>
		                     <chart caption='Overall Unit Health' subCaption='' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fUnitDr) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='unit-health'>
								<categories>
<?
	foreach ($sUnitsList as $iUnit => $sUnit)
	{
?>
								<category label='<?= $sUnit ?>' />
<?
	}
?>
								</categories>


								<dataset seriesName='QMIP Inline Audits'>
<?
  	foreach ($sUnitsList as $iUnit => $sUnit)
  	{
		$sParams = $sQueryString;

		if ($sParams != "")
		{
			if (@strpos($sParams, "Unit={$iUnit}") === FALSE)
				$sParams .= "&Unit={$iUnit}";
		}
?>
								<set value='<?= $fQmipInlineDr[$iUnit] ?>' tooltext='Unit: <?= $sUnit ?>{br}DR: <?= formatNumber($fQmipInlineDr[$iUnit]) ?>%{br}TGI: <?= formatNumber($iQmipInlineGmts[$iUnit], false) ?>{br}TGR: <?= formatNumber($iQmipInlineDefects[$iUnit], false) ?>' link='javascript:showLinesGraph("<?= $sParams ?>")' />
<?
  	}
?>
								</dataset>


								<dataset seriesName='3rd Party Inline Audits'>
<?
	foreach ($sUnitsList as $iUnit => $sUnit)
	{
		$sParams = $sQueryString;

		if ($sParams != "")
		{
			if (@strpos($sParams, "Unit={$iUnit}") === FALSE)
				$sParams .= "&Unit={$iUnit}";
		}
?>
								<set value='<?= $f3rdPartyInlineDr[$iUnit] ?>'  tooltext='Unit: <?= $sUnit ?>{br}DR: <?= formatNumber($f3rdPartyInlineDr[$iUnit]) ?>%{br}TGI: <?= formatNumber($i3rdPartyInlineGmts[$iUnit], false) ?>{br}TGR: <?= formatNumber($i3rdPartyInlineDefects[$iUnit], false) ?>' link='javascript:showLinesGraph("<?= $sParams ?>")' />
<?
	}
?>
								</dataset>


								<dataset seriesName='QMIP Corelation Audits' renderAs='Line' color='#ffff00'>
<?
	foreach ($sUnitsList as $iUnit => $sUnit)
	{
?>
								<set value='<?= $fQmipCoRelationDr[$iUnit] ?>'  tooltext='Unit: <?= $sUnit ?>{br}DR: <?= formatNumber($fQmipCoRelationDr[$iUnit]) ?>%{br}TGI: <?= formatNumber($iQmipCoRelationGmts[$iUnit], false) ?>{br}TGR: <?= formatNumber($iQmipCoRelationDefects[$iUnit], false) ?>' />
<?
	}
?>
								</dataset>

								<dataset seriesName='3rd Party Final Audits' renderAs='Line' color='#0000ff'>
<?
	foreach ($sUnitsList as $iUnit => $sUnit)
	{
?>
								<set value='<?= $f3rdPartyFinalDr[$iUnit] ?>'  tooltext='Unit: <?= $sUnit ?>{br}DR: <?= formatNumber($f3rdPartyFinalDr[$iUnit]) ?>%{br}TGI: <?= formatNumber($i3rdPartyFinalGmts[$iUnit], false) ?>{br}TGR: <?= formatNumber($i3rdPartyFinalDefects[$iUnit], false) ?>' />
<?
	}
?>
								</dataset>

								<dataset seriesName='Final Audits' renderAs='Line' color='#00aa00'>
<?
	foreach ($sUnitsList as $iUnit => $sUnit)
	{
?>
								<set value='<?= $fFinalAuditsDr[$iUnit] ?>'  tooltext='Unit: <?= $sUnit ?>{br}DR: <?= formatNumber($fFinalAuditsDr[$iUnit]) ?>%{br}TGI: <?= formatNumber($iFinalAuditsGmts[$iUnit], false) ?>{br}TGR: <?= formatNumber($iFinalAuditsDefects[$iUnit], false) ?>' />
<?
	}
?>
								</dataset>

								<dataset seriesName='Passed Final Audits' renderAs='Line' color='#00ff00'>
<?
	foreach ($sUnitsList as $iUnit => $sUnit)
	{
?>
								<set value='<?= $fFinalPassAuditsDr[$iUnit] ?>'  tooltext='Unit: <?= $sUnit ?>{br}DR: <?= formatNumber($fFinalPassAuditsDr[$iUnit]) ?>%{br}TGI: <?= formatNumber($iFinalPassAuditsGmts[$iUnit], false) ?>{br}TGR: <?= formatNumber($iFinalPassAuditsDefects[$iUnit], false) ?>' />
<?
	}
?>
								</dataset>
							</chart>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>