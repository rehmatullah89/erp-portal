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


	$OrderNo    = IO::strValue("OrderNo");
	$AuditCode  = IO::strValue("AuditCode");
	$Vendor     = IO::intValue("Vendor");
	$Brand      = IO::intValue("Brand");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");
	$StyleNo    = IO::strValue("StyleNo");


	if ($FromDate == "" || $ToDate == "")
	{
		if (@strpos($_SESSION["Email"], "marksnspencer.com") === FALSE && @strpos($_SESSION["Email"], "dkcompany.com") === FALSE && @strpos($_SESSION["Email"], "hema.nl") === FALSE &&
			@strpos($_SESSION["Email"], "kcmtar.com") === FALSE && @strpos($_SESSION["Email"], "mister-lady.com") === FALSE)
		{
			if ($OrderNo == "" && $AuditCode == "" && $Vendor == "" && $Brand == "" && $Color == "")
			{
				$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 3), date("Y")));
				$ToDate   = date("Y-m-d");
			}

			else if ($OrderNo != "" || $AuditCode != "")
			{
				$FromDate = date("Y-m-d", mktime(0, 0, 0, (date("m") - 6), date("d"), date("Y")));
				$ToDate   = date("Y-m-d");
			}
		}
	}

	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];


	$sReportTypes     = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");


	$sInlineStages  = array( );
	$iInlineGmts    = array( );
	$iInlineDefects = array( );
	$iInlineAudits  = array( );
	$fInlineDhu     = array( );

	$iFinalGmts     = 0;
	$iFinalDefects  = 0;
	$iFinalAudits   = 0;

	$iPassGmts      = 0;
	$iPassDefects   = 0;
	$iPassAudits    = 0;


	$sSQL1 = "SELECT qa.audit_stage AS _AuditStage, qa.audit_result AS _AuditResult,
					COUNT(DISTINCT(qa.id)) AS _TotalAudits,
					COALESCE(SUM(qa.total_gmts), 0) AS _TotalGmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature>'0')) AS _TotalDefects
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND qa.report_id!='6'
			       AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND NOT FIND_IN_SET(qa.report_id, '$sQmipReports') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";


	$sSQL2 = "SELECT qa.audit_stage AS _AuditStage, qa.audit_result AS _AuditResult,
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

	$sSubSql .= " GROUP BY qa.audit_stage, qa.audit_result";


	$sSQL = "$sSQL1 $sSubSql UNION $sSQL2 $sSubSql ORDER BY _AuditStage";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAuditStage   = $objDb->getField($i, "_AuditStage");
		$sAuditResult  = $objDb->getField($i, "_AuditResult");
		$iTotalAudits  = $objDb->getField($i, "_TotalAudits");
		$iTotalGmts    = $objDb->getField($i, "_TotalGmts");
		$iTotalDefects = $objDb->getField($i, "_TotalDefects");


		if ($sAuditStage != "F")
		{
			$iInlineGmts[$sAuditStage]    += $iTotalGmts;
			$iInlineDefects[$sAuditStage] += $iTotalDefects;
			$iInlineAudits[$sAuditStage]  += $iTotalAudits;

			if (!@in_array($sAuditStage, $sInlineStages))
				$sInlineStages[$sAuditStage] = $sAuditStagesList[$sAuditStage];
		}

		else
		{
			$iFinalGmts    += $iTotalGmts;
			$iFinalDefects += $iTotalDefects;
			$iFinalAudits  += $iTotalAudits;

			if ($sAuditResult == "P" || $sAuditResult == "A" || $sAuditResult == "B")
			{
				$iPassGmts    += $iTotalGmts;
				$iPassDefects += $iTotalDefects;
				$iPassAudits  += $iTotalAudits;
			}
		}
	}


	foreach ($sInlineStages as $sCode => $sStage)
	{
		$fInlineDhu[$sCode] = @round((($iInlineDefects[$sCode] / $iInlineGmts[$sCode]) * 100), 2);
	}


	$fFinalDhu = @round((($iFinalDefects / $iFinalGmts) * 100), 2);
	$fPassDhu  = @round((($iPassDefects / $iPassGmts) * 100), 2);
?>
						                     <chart caption='Inline Stages Summary' numDivLines='10' formatNumberScale='0' showValues='1' showSum='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='ROTATE' slantLabels='1' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='inline-stages-graph'>

											 <categories>
<?
	foreach ($sInlineStages as $sCode => $sStage)
	{
?>
											<category label='<?= htmlentities($sStage, ENT_QUOTES) ?>' />
<?
	}
?>
											</categories>


											<dataset seriesName='Inline Stages' color=''>
<?
	foreach ($sInlineStages as $sCode => $sStage)
	{
?>
											<set value='<?= floatval($fInlineDhu[$sCode]) ?>' tooltext='Stage: <?= htmlentities($sStage, ENT_QUOTES) ?>{br}Audits: <?= intval($iInlineAudits[$sCode]) ?>{br}Quantity: <?= intval($iInlineGmts[$sCode]) ?>{br}Defects: <?= intval($iInlineDefects[$sCode]) ?>{br}DR: <?= formatNumber($fInlineDhu[$sCode]) ?>%' link='' />
<?
	}
?>
											</dataset>


											<dataset seriesName='Final Audits' renderAs='Line' color='0000ff'>
<?
	foreach ($sInlineStages as $sCode => $sStage)
	{
?>
											  <set value='<?= floatval($fFinalDhu) ?>' tooltext='Final Audits{br}{br}Audits: <?= intval($iFinalAudits) ?>{br}Quantity: <?= intval($iFinalGmts) ?>{br}Defects: <?= intval($iFinalDefects) ?>{br}DR: <?= formatNumber($fFinalDhu) ?>%' link='' />
<?
	}
?>
											</dataset>

											<dataset seriesName='Passed Final Audits' renderAs='Line' color='00ff00'>
<?
	foreach ($sInlineStages as $sCode => $sStage)
	{
?>
											  <set value='<?= floatval($fPassDhu) ?>' tooltext='Passed Pass Audits{br}Vendor: <?= htmlentities($sVendor, ENT_QUOTES) ?>{br}Audits: <?= intval($iPassAudits) ?>{br}Quantity: <?= intval($iPassGmts) ?>{br}Defects: <?= intval($iPassDefects) ?>{br}DR: <?= formatNumber($fPassDhu) ?>%' link='' />
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