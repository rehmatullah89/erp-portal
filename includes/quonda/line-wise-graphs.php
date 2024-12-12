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

			    <div class="tblSheet">
<?
	if (@strpos($_SESSION["Email"], "marksnspencer.com") === FALSE)
	{
		$fDhu   = array( );
		$sLines = array( );
		$iLines = array( );

		$iTotalGmts    = 0;
		$iTotalDefects = 0;


		if ($Category == 5)
			$sAuditStage = "S";

		else
			$sAuditStage = "C";


		$sStageTitle = $sAuditStagesList[$sAuditStage];
		$sStageDr    = getDbValue("dr_field", "tbl_audit_stages", "code='$sAuditStage'");
		$fTargetDhu  = getDbValue($sStageDr, "tbl_vendors", "id='$Vendor'");


		$sSQL = "SELECT qa.line_id,
						(SELECT line FROM tbl_lines WHERE id=qa.line_id) AS _Line,
						COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
						SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature>'0')) AS _Defects
				 FROM tbl_po po, tbl_qa_reports qa
				 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND qa.report_id!='6' AND qa.audit_stage='$sAuditStage' $sAuditorSQL
				       AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

		$sSQL .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

		if ($FromDate != "" && $ToDate != "")
			$sSQL .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

		if ($AuditCode != "")
			$sSQL .= " AND qa.audit_code LIKE '%$AuditCode%' ";

		if ($OrderNo != "")
		{
			$sSQL .= " AND (";

			$sSubSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '%$OrderNo%'";
			$objDb->query($sSubSQL);

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

		$sSQL .= " GROUP BY qa.line_id
				   ORDER BY _Line";

		$objDb->query($sSQL);


		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iLineId  = $objDb->getField($i, "line_id");
			$sLine    = $objDb->getField($i, "_Line");
			$iGmts    = $objDb->getField($i, "_Gmts");
			$iDefects = $objDb->getField($i, "_Defects");

			$sLines[] = $sLine;
			$iLines[] = $iLineId;
			$fDhu[]   = @round((($iDefects / $iGmts) * 100), 2);

			$iTotalGmts    += $iGmts;
			$iTotalDefects += $iDefects;
		}
?>
						<div id="<?= $sStageTitle ?>Chart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "<?= $sStageTitle ?>", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='Line Wise Progress Report (<?= $sStageTitle ?>)' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fDhu) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='<?= strtolower(str_replace(" ", "-", $sStageTitle)) ?>'>" +
<?
		for ($i = 0; $i < count($fDhu); $i ++)
		{
?>
											"<set tooltext='Line: <?= str_replace("\n", "", $sLines[$i]) ?>{br}DHU: <?= $fDhu[$i] ?>%' label='<?= str_replace("\n", "", $sLines[$i]) ?>' value='<?= $fDhu[$i] ?>' link='<?= ("{$_SERVER['PHP_SELF']}?OrderNo={$OrderNo}&StyleNo={$StyleNo}&AuditCode={$AuditCode}&Brand={$Brand}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}&AuditStage={$sAuditStage}&Line={$iLines[$i]}&Step=2") ?>' />" +
<?
		}
?>

											"<trendlines>" +
<?
		if (count($sLines) > 0 && $fTargetDhu > 0)
		{
?>
											"  <line toolText='Target Line (<?= $fTargetDhu ?>%)' startValue='<?= $fTargetDhu ?>' displayValue='Target' color='ff0000' />" +
<?
		}


		$fAvgDhu = @round((($iTotalDefects / $iTotalGmts) * 100), 2);

		if ($fAvgDhu > 0)
		{
?>
											"  <line toolText='Average Line (<?= $fAvgDhu ?>%)' startValue='<?= $fAvgDhu ?>' displayValue='Average' color='0000ff' />" +
<?
		}
?>
											"</trendlines>" +

										    "</chart>");


						objChart.render("<?= $sStageTitle ?>Chart");
						-->
						</script>
<?
		$fDhu   = array( );
		$sLines = array( );
		$iLines = array( );

		$iTotalGmts    = 0;
		$iTotalDefects = 0;


		if ($Category == 5)
			$sAuditStage = "ST";

		else
			$sAuditStage = "O";


		$sStageTitle = $sAuditStagesList[$sAuditStage];
		$sStageDr    = getDbValue("dr_field", "tbl_audit_stages", "code='$sAuditStage'");
		$fTargetDhu  = getDbValue($sStageDr, "tbl_vendors", "id='$Vendor'");



		$sSQL = "SELECT qa.line_id,
						(SELECT line FROM tbl_lines WHERE id=qa.line_id) AS _Line,
						COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
						SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature>'0')) AS _Defects
				 FROM tbl_po po, tbl_qa_reports qa
				 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND qa.report_id!='6' AND qa.audit_stage='$sAuditStage' $sAuditorSQL
				       AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

		$sSQL .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

		if ($FromDate != "" && $ToDate != "")
			$sSQL .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

		if ($AuditCode != "")
			$sSQL .= " AND qa.audit_code LIKE '%$AuditCode%' ";

		if ($OrderNo != "")
		{
			$sSQL .= " AND (";

			$sSubSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '%$OrderNo%'";
			$objDb->query($sSubSQL);

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

		$sSQL .= " GROUP BY qa.line_id
				   ORDER BY _Line";

		$objDb->query($sSQL);


		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iLineId  = $objDb->getField($i, "line_id");
			$sLine    = $objDb->getField($i, "_Line");
			$iGmts    = $objDb->getField($i, "_Gmts");
			$iDefects = $objDb->getField($i, "_Defects");

			$sLines[] = $sLine;
			$iLines[] = $iLineId;
			$fDhu[]   = @round((($iDefects / $iGmts) * 100), 2);

			$iTotalGmts    += $iGmts;
			$iTotalDefects += $iDefects;
		}
?>
						<hr />
						<div id="<?= $sStageTitle ?>Chart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "<?= $sStageTitle ?>", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='Line Wise Progress Report (<?= $sStageTitle ?>)' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fDhu) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='<?= strtolower(str_replace(" ", "-", $sStageTitle)) ?>'>" +
<?
		for ($i = 0; $i < count($fDhu); $i ++)
		{
?>
											"<set tooltext='Line: <?= str_replace("\n", "", $sLines[$i]) ?>{br}DHU: <?= $fDhu[$i] ?>%' label='<?= str_replace("\n", "", $sLines[$i]) ?>' value='<?= $fDhu[$i] ?>' link='<?= ("{$_SERVER['PHP_SELF']}?OrderNo={$OrderNo}&StyleNo={$StyleNo}&AuditCode={$AuditCode}&Brand={$Brand}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}&AuditStage={$sAuditStage}&Line={$iLines[$i]}&Step=2") ?>' />" +
<?
		}
?>

											"<trendlines>" +
<?
		if (count($sLines) > 0 && $fTargetDhu > 0)
		{
?>
											"  <line toolText='Target Line (<?= $fTargetDhu ?>%)' startValue='<?= $fTargetDhu ?>' displayValue='Target' color='ff0000' />" +
<?
		}


		$fAvgDhu = @round((($iTotalDefects / $iTotalGmts) * 100), 2);

		if ($fAvgDhu > 0)
		{
?>
											"  <line toolText='Average Line (<?= $fAvgDhu ?>%)' startValue='<?= $fAvgDhu ?>' displayValue='Average' color='0000ff' />" +
<?
		}
?>
											"</trendlines>" +

										    "</chart>");


						objChart.render("<?= $sStageTitle ?>Chart");
						-->
						</script>
<?
		$fDhu   = array( );
		$sLines = array( );
		$iLines = array( );

		$iTotalGmts    = 0;
		$iTotalDefects = 0;


		if ($Category == 5)
			$sAuditStage = "FI";

		else
			$sAuditStage = "B";


		$sStageTitle = $sAuditStagesList[$sAuditStage];
		$sStageDr    = getDbValue("dr_field", "tbl_audit_stages", "code='$sAuditStage'");
		$fTargetDhu  = getDbValue($sStageDr, "tbl_vendors", "id='$Vendor'");



		$sSQL = "SELECT qa.line_id,
						(SELECT line FROM tbl_lines WHERE id=qa.line_id) AS _Line,
						COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
						SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature>'0')) AS _Defects
				 FROM tbl_po po, tbl_qa_reports qa
				 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND qa.report_id!='6' AND qa.audit_stage='$sAuditStage' $sAuditorSQL
				       AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

		$sSQL .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

		if ($FromDate != "" && $ToDate != "")
			$sSQL .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

		if ($AuditCode != "")
			$sSQL .= " AND qa.audit_code LIKE '%$AuditCode%' ";

		if ($OrderNo != "")
		{
			$sSQL .= " AND (";

			$sSubSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '%$OrderNo%'";
			$objDb->query($sSubSQL);

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

		$sSQL .= " GROUP BY qa.line_id
				   ORDER BY _Line";

		$objDb->query($sSQL);


		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iLineId  = $objDb->getField($i, "line_id");
			$sLine    = $objDb->getField($i, "_Line");
			$iGmts    = $objDb->getField($i, "_Gmts");
			$iDefects = $objDb->getField($i, "_Defects");

			$sLines[] = $sLine;
			$iLines[] = $iLineId;
			$fDhu[]   = @round((($iDefects / $iGmts) * 100), 2);

			$iTotalGmts    += $iGmts;
			$iTotalDefects += $iDefects;
		}
?>
						<hr />
						<div id="<?= $sStageTitle ?>Chart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "<?= $sStageTitle ?>", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='Line Wise Progress Report (<?= $sStageTitle ?>)' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fDhu) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='<?= strtolower(str_replace(" ", "-", $sStageTitle)) ?>'>" +
<?
		for ($i = 0; $i < count($fDhu); $i ++)
		{
?>
											"<set tooltext='Line: <?= str_replace("\n", "", $sLines[$i]) ?>{br}DHU: <?= $fDhu[$i] ?>%' label='<?= str_replace("\n", "", $sLines[$i]) ?>' value='<?= $fDhu[$i] ?>' link='<?= ("{$_SERVER['PHP_SELF']}?OrderNo={$OrderNo}&StyleNo={$StyleNo}&AuditCode={$AuditCode}&Brand={$Brand}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}&AuditStage={$sAuditStage}&Line={$iLines[$i]}&Step=2") ?>' />" +
<?
		}
?>

											"<trendlines>" +
<?
		if (count($sLines) > 0 && $fTargetDhu > 0)
		{
?>
											"  <line toolText='Target Line (<?= $fTargetDhu ?>%)' startValue='<?= $fTargetDhu ?>' displayValue='Target' color='ff0000' />" +
<?
		}


		$fAvgDhu = @round((($iTotalDefects / $iTotalGmts) * 100), 2);

		if ($fAvgDhu > 0)
		{
?>
											"  <line toolText='Average Line (<?= $fAvgDhu ?>%)' startValue='<?= $fAvgDhu ?>' displayValue='Average' color='0000ff' />" +
<?
		}
?>
											"</trendlines>" +

										    "</chart>");


						objChart.render("<?= $sStageTitle ?>Chart");
						-->
						</script>
<?
	}


	$fDhu   = array( );
	$sLines = array( );
	$iLines = array( );

	$iTotalGmts    = 0;
	$iTotalDefects = 0;
	$sAuditStage   = "F";

	$sStageTitle = $sAuditStagesList[$sAuditStage];
	$sStageDr    = getDbValue("dr_field", "tbl_audit_stages", "code='$sAuditStage'");
	$fTargetDhu  = getDbValue($sStageDr, "tbl_vendors", "id='$Vendor'");

	if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE)
		$sStageTitle = "Firewall";


	$sSQL = "SELECT qa.line_id,
	                (SELECT line FROM tbl_lines WHERE id=qa.line_id) AS _Line,
					COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature>'0')) AS _Defects
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND qa.report_id!='6' AND qa.audit_stage='$sAuditStage' $sAuditorSQL
			       AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

	$sSQL .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

	if ($FromDate != "" && $ToDate != "")
		$sSQL .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($AuditCode != "")
		$sSQL .= " AND qa.audit_code LIKE '%$AuditCode%' ";

	if ($OrderNo != "")
	{
		$sSQL .= " AND (";

		$sSubSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '%$OrderNo%'";
		$objDb->query($sSubSQL);

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

	$sSQL .= " GROUP BY qa.line_id
	           ORDER BY _Line";

	$objDb->query($sSQL);


	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iLineId  = $objDb->getField($i, "line_id");
		$sLine    = $objDb->getField($i, "_Line");
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");

		$sLines[] = $sLine;
		$iLines[] = $iLineId;
		$fDhu[]   = @round((($iDefects / $iGmts) * 100), 2);

		$iTotalGmts    += $iGmts;
		$iTotalDefects += $iDefects;
	}
?>
						<hr />
						<div id="<?= $sStageTitle ?>Chart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "<?= $sStageTitle ?>", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='Line Wise Progress Report (<?= $sStageTitle ?>)' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fDhu) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='<?= strtolower(str_replace(" ", "-", $sStageTitle)) ?>'>" +
<?
	for ($i = 0; $i < count($fDhu); $i ++)
	{
?>
											"<set tooltext='Line: <?= str_replace("\n", "", $sLines[$i]) ?>{br}DHU: <?= $fDhu[$i] ?>%' label='<?= str_replace("\n", "", $sLines[$i]) ?>' value='<?= $fDhu[$i] ?>' link='<?= ("{$_SERVER['PHP_SELF']}?OrderNo={$OrderNo}&StyleNo={$StyleNo}&AuditCode={$AuditCode}&Brand={$Brand}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}&AuditStage={$sAuditStage}&Line={$iLines[$i]}&Step=2") ?>' />" +
<?
	}
?>

											"<trendlines>" +
<?
	if (count($sLines) > 0 && $fTargetDhu > 0)
	{
?>
											"  <line toolText='Target Line (<?= $fTargetDhu ?>%)' startValue='<?= $fTargetDhu ?>' displayValue='Target' color='ff0000' />" +
<?
	}


	$fAvgDhu = @round((($iTotalDefects / $iTotalGmts) * 100), 2);

	if ($fAvgDhu > 0)
	{
?>
											"  <line toolText='Average Line (<?= $fAvgDhu ?>%)' startValue='<?= $fAvgDhu ?>' displayValue='Average' color='0000ff' />" +
<?
	}
?>
											"</trendlines>" +

										    "</chart>");


						objChart.render("<?= $sStageTitle ?>Chart");
						-->
						</script>
<?
	if (@strpos($_SESSION["Email"], "marksnspencer.com") === FALSE && $Category != 5)
	{
		$fDhu   = array( );
		$sLines = array( );
		$iLines = array( );

		$iTotalGmts    = 0;
		$iTotalDefects = 0;
		$sAuditStage   = "P";

		$sStageTitle = $sAuditStagesList[$sAuditStage];
		$sStageDr    = getDbValue("dr_field", "tbl_audit_stages", "code='$sAuditStage'");
		$fTargetDhu  = getDbValue($sStageDr, "tbl_vendors", "id='$Vendor'");


		$sSQL = "SELECT qa.line_id,
						(SELECT line FROM tbl_lines WHERE id=qa.line_id) AS _Line,
						COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
						SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature>'0')) AS _Defects
				 FROM tbl_po po, tbl_qa_reports qa
				 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND qa.report_id!='6' AND qa.audit_stage='$sAuditStage' $sAuditorSQL
				       AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

		$sSQL .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

		if ($FromDate != "" && $ToDate != "")
			$sSQL .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

		if ($AuditCode != "")
			$sSQL .= " AND qa.audit_code LIKE '%$AuditCode%' ";

		if ($OrderNo != "")
		{
			$sSQL .= " AND (";

			$sSubSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '%$OrderNo%'";
			$objDb->query($sSubSQL);

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

		$sSQL .= " GROUP BY qa.line_id
				   ORDER BY _Line";

		$objDb->query($sSQL);


		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iLineId  = $objDb->getField($i, "line_id");
			$sLine    = $objDb->getField($i, "_Line");
			$iGmts    = $objDb->getField($i, "_Gmts");
			$iDefects = $objDb->getField($i, "_Defects");

			$sLines[] = $sLine;
			$iLines[] = $iLineId;
			$fDhu[]   = @round((($iDefects / $iGmts) * 100), 2);

			$iTotalGmts    += $iGmts;
			$iTotalDefects += $iDefects;
		}
?>
						<hr />
						<div id="<?= $sStageTitle ?>Chart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "<?= $sStageTitle ?>", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='Line Wise Progress Report (<?= $sStageTitle ?>)' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fDhu) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='<?= strtolower(str_replace(" ", "-", $sStageTitle)) ?>'>" +
<?
		for ($i = 0; $i < count($fDhu); $i ++)
		{
?>
											"<set tooltext='Line: <?= str_replace("\n", "", $sLines[$i]) ?>{br}DHU: <?= $fDhu[$i] ?>%' label='<?= str_replace("\n", "", $sLines[$i]) ?>' value='<?= $fDhu[$i] ?>' link='<?= ("{$_SERVER['PHP_SELF']}?OrderNo={$OrderNo}&StyleNo={$StyleNo}&AuditCode={$AuditCode}&Brand={$Brand}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}&AuditStage={$sAuditStage}&Line={$iLines[$i]}&Step=2") ?>' />" +
<?
		}
?>

											"<trendlines>" +
<?
		if (count($sLines) > 0 && $fTargetDhu > 0)
		{
?>
											"  <line toolText='Target Line (<?= $fTargetDhu ?>%)' startValue='<?= $fTargetDhu ?>' displayValue='Target' color='ff0000' />" +
<?
		}


		$fAvgDhu = @round((($iTotalDefects / $iTotalGmts) * 100), 2);

		if ($fAvgDhu > 0)
		{
?>
											"  <line toolText='Average Line (<?= $fAvgDhu ?>%)' startValue='<?= $fAvgDhu ?>' displayValue='Average' color='0000ff' />" +
<?
		}
?>
											"</trendlines>" +

										    "</chart>");


						objChart.render("<?= $sStageTitle ?>Chart");
						-->
						</script>
<?
	}
?>
			    </div>
