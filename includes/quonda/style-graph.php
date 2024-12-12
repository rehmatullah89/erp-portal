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

	$sStyles      = array( );
	$iAudits      = array( );
	$iStages      = array( );
	$iQuantities  = array( );
	$iStageAudits = array( );


	$sSQL = "SELECT qa.style_id, qa.audit_stage, qa.ship_qty,
					COUNT(DISTINCT(qa.id)) AS _TotalAudits
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND NOT ISNULL(qa.style_id) AND qa.style_id>'0' $sAuditorSQL
			       AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

	$sSQL .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

	if ($FromDate != "" && $ToDate != "")
		$sSQL .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($AuditCode != "")
		$sSQL .= " AND qa.audit_code LIKE '%$AuditCode%' ";

	if ($OrderNo != "")
	{
		$sSQL .= " AND (";

		$sSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '%$OrderNo%'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPoId = $objDb->getField($i, 0);

			if ($i > 0)
				$sSQL .= " OR ";

			$sSQL .= " qa.po_id='$iPoId' OR FIND_IN_SET('$iPoId', qa.additional_pos) ";
		}

		$sSQL .= ") ";
	}

	if ($StyleNo != "")
	{
		$sSQL .= " AND (";

		$sSubSQL = "SELECT id FROM tbl_styles WHERE style LIKE '%$StyleNo%'";
		$objDb->query($sSubSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iStyleId = $objDb->getField($i, 0);

			if ($i > 0)
				$sSQL .= " OR ";

			$sSQL .= " qa.style_id='$iStyleId' ";
		}

		$sSQL .= ") ";
	}

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($AuditStage != "''" && $AuditStage != "")
		$sSQL .= " AND qa.audit_stage IN ($AuditStage) ";

	$sSQL .= " GROUP BY qa.style_id, qa.audit_stage";
	$sSQL .= " ORDER BY qa.id, qa.style_id, qa.audit_stage";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iStyleId      = $objDb->getField($i, "style_id");
		$sAuditStage   = $objDb->getField($i, "audit_stage");
		$iTotalAudits  = $objDb->getField($i, "_TotalAudits");
		$iShipQty      = $objDb->getField($i, "ship_qty");


		if (!@in_array($iStyleId, $sStyles))
		{
			$sStyles[$iStyleId]     = getDbValue(("CONCAT(style, ' (', (SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id), ')')"), "tbl_styles", "id='$iStyleId'");
			$iQuantities[$iStyleId] = 0;
		}

		$iAudits[$iStyleId] += $iTotalAudits;
		$iStages[$iStyleId] ++;

		if (($iQuantities[$iStyleId] == 0 || $iShipQty > 0) && $sAuditStage == "F")
			$iQuantities[$iStyleId] = $iShipQty;
	}


	for ($i = 0; $i < $iCount; $i ++)
	{
		$iStyleId     = $objDb->getField($i, "style_id");
		$sAuditStage  = $objDb->getField($i, "audit_stage");
		$iTotalAudits = $objDb->getField($i, "_TotalAudits");

		$iStageAudits[$iStyleId][$sAuditStage] = $iTotalAudits;
	}


	$sStage = "";

	if (strlen($AuditStage) <= 2)
		$sStage = $sAuditStagesList[$AuditStage];

	if ($AuditStage == "F" && @strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE)
		$sStage = "Firewall";
?>
			    <div class="tblSheet">
				  <div id="StyleAuditsChart">loading...</div>

				  <script type="text/javascript">
				  <!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/StackedColumn3DLineDY.swf", "StyleAudits", "920", "500", "0", "1");

						objChart.setXMLData("<chart caption='<?= ("{$sStage} Style Audits from ".formatDate($FromDate)." to ".formatDate($ToDate)) ?>' PYAxisName='Audits' SYAxisName='Quantity' syncAxisLimits='0' numDivLines='10' formatNumberScale='0' showValues='0' showSum='1' showLabels='1' decimals='1' numberSuffix='' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='ROTATE' slantLabels='1' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='style-audits-graph'>" +

											"<categories>" +
<?
	foreach ($sStyles as $iStyle => $sStyle)
	{
?>
											"<category label='<?= htmlentities($sStyle, ENT_QUOTES) ?>' />" +
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
		foreach ($sStyles as $iStyle => $sStyle)
		{
?>
											"<set value='<?= $iStageAudits[$iStyle][$sCode] ?>' tooltext='Stage: <?= $sStage ?>{br}Style: <?= htmlentities($sStyle, ENT_QUOTES) ?>{br}Audits: <?= $iStageAudits[$iStyle][$sCode] ?>' />" +
<?
		}
?>
											"</dataset>" +
<?
	}
?>

											"<dataset seriesName='Ship Qty' color='0000bb' parentYAxis='S' showValues='1'>" +
<?
	foreach ($sStyles as $iStyle => $sStyle)
	{
?>
											"<set value='<?= $iQuantities[$iStyle] ?>' tooltext='Style: <?= htmlentities($sStyle, ENT_QUOTES) ?>{br}Quantity: <?= $iQuantities[$iStyle] ?>' />" +
<?
	}
?>
											"</dataset>" +
										"</chart>");


						objChart.render("StyleAuditsChart");
				  -->
				  </script>

				  <br />
			    </div>
