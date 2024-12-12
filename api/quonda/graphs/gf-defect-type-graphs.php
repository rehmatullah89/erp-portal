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

	$Month       = IO::intValue("Month");
	$Type        = IO::intValue("Type");
	$sDefectType = "";
	$sColorSQL   = "";
	$sAuditStage = getDbValue("code", "tbl_audit_stages", "id='$Sector'");


	$sBackUrl = (SITE_URL."api/quonda/graphs".(($Month > 0 && $Type > 0) ? "-s1": (($Month > 0 && $Type == 0) ? "-s1" : "")).".php?User={$User}&OrderNo={$OrderNo}&AuditCode={$AuditCode}&Brand={$Brand}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}");

	if ($Type > 0)
		$sBackUrl .= "Sector={$Sector}&Category={$Category}&Color={$Color}&Month={$Month}";

	else if ($Month > 0)
		$sBackUrl .= "Sector={$Sector}&Category={$Category}&Color={$Color}";


	$sData     = array( );
	$sLabels   = array( );
	$sFromDate = array( );
	$sToDate   = array( );
	$iMonths   = array( );
	$iYears    = array( );
	$sMonths   = array('', 'January','February','March','April','May','June','July','August','September','October','November','December');

	if ($Month == 0)
	{
		$sStartDate = date("Y-m-d", mktime(0, 0, 0, (date("m") - 11), "01", date("Y")));
		$iDays      = @cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));
		$sEndDate   = (date("Y-m")."-".$iDays);

		if ($FromDate != "" && $ToDate != "")
		{
			$sStartDate = $FromDate;
			$sEndDate   = $ToDate;
		}

		$sSQL = "SELECT DATE_FORMAT(qa.audit_date, '%c') AS _Month,
						DATE_FORMAT(qa.audit_date, '%Y') AS _Year,
						ROUND(AVG(qa.dhu), 1) AS _Dhu
				 FROM tbl_po po, tbl_qa_reports qa
				 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND report_id='6'
				 AND (qa.audit_date BETWEEN '$sStartDate' AND '$sEndDate')";

		if ($AuditCode != "")
			$sSubSql .= " AND qa.audit_code LIKE '%$AuditCode%' ";

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

		if ($Vendor > 0)
			$sSQL .= " AND po.vendor_id='$Vendor' ";

		else
			$sSQL .= " AND po.vendor_id IN ($sUserVendors) ";

		if ($Brand > 0)
			$sSQL .= " AND po.brand_id='$Brand' ";

		else
			$sSQL .= " AND po.brand_id IN ($sUserBrands) ";

		if ($sAuditStage != "")
			$sSQL .= " AND qa.audit_stage='$sAuditStage' ";

		else
			$sSQL .= " AND qa.audit_stage!='' ";

		if ($Color != "")
			$sSQL .= " AND qa.po_id IN (SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE color LIKE '$Color') ";

		$sSQL .= " GROUP BY DATE_FORMAT(qa.audit_date, '%m')
				   ORDER BY DATE_FORMAT(qa.audit_date, '%Y-%m')";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth = $objDb->getField($i, "_Month");
			$iYear  = $objDb->getField($i, "_Year");
			$fDhu   = $objDb->getField($i, "_Dhu");

			$sData[]     = $fDhu;
			$sLabels[]   = substr($sMonths[$iMonth], 0, 3);

			$sMonth = str_pad($iMonth, 2, '0', STR_PAD_LEFT);
			$iDays  = @cal_days_in_month(CAL_GREGORIAN, $sMonth, $iYear);

			$sFromDate[] = "$iYear-$sMonth-01";
			$sToDate[]   = "$iYear-$sMonth-$iDays";
			$iMonths[]   = $iMonth;
			$iYears[]    = $iYear;
		}
	}

	else
	{
		$iYear  = substr($FromDate, 0, 4);
		$iMonth = str_pad($Month, 2, '0', STR_PAD_LEFT);
		$iDays  = @cal_days_in_month(CAL_GREGORIAN, $iMonth, $iYear);


		$sStartDate = date("Y-m-d", mktime(0, 0, 0, (date("m") - 11), "01", date("Y")));
		$iDays      = @cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));
		$sEndDate   = (date("Y-m")."-".$iDays);



		$sSQL = "SELECT DATE_FORMAT(qa.audit_date, '%e') AS _Day,
						ROUND(AVG(qa.dhu), 1) AS _Dhu
				 FROM tbl_po po, tbl_qa_reports qa
				 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND report_id='6'
				 AND (qa.audit_date BETWEEN '$iYear-$iMonth-01' AND '$iYear-$iMonth-$iDays')";

		if ($AuditCode != "")
			$sSubSql .= " AND qa.audit_code LIKE '%$AuditCode%' ";

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

		if ($Vendor > 0)
			$sSQL .= " AND po.vendor_id='$Vendor' ";

		else
			$sSQL .= " AND po.vendor_id IN ($sUserVendors) ";

		if ($Brand > 0)
			$sSQL .= " AND po.brand_id='$Brand' ";

		else
			$sSQL .= " AND po.brand_id IN ($sUserBrands) ";

		if ($sAuditStage != "")
			$sSQL .= " AND qa.audit_stage='$sAuditStage' ";

		else
			$sSQL .= " AND qa.audit_stage!='' ";

		if ($Color != "")
			$sSQL .= " AND qa.po_id IN (SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE color LIKE '$Color') ";

		$sSQL .= " GROUP BY DATE_FORMAT(qa.audit_date, '%d')
				   ORDER BY DATE_FORMAT(qa.audit_date, '%m-%d')";

		$objDb->query($sSQL);


		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iDay = $objDb->getField($i, "_Day");
			$fDhu = $objDb->getField($i, "_Dhu");

			$sDay = str_pad($iDay, 2, '0', STR_PAD_LEFT);

			$sData[]     = $fDhu;
			$sLabels[]   = $sDay;
			$sFromDate[] = "$iYear-$iMonth-$sDay";
			$sToDate[]   = "$iYear-$iMonth-$sDay";
		}
	}
?>
						<div id="AvgPointsChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "AvgPoints", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='Average Points<?= (($Month > 0) ? "{$sMonths[$Month]} {$iYear}" : "") ?>' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='' chartBottomMargin='5' plotFillAlpha='95'>" +
<?
	for ($i = 0; $i < count($sData); $i ++)
	{
?>
											"<set tooltext='<?= (($Month == 0) ? "{$sLabels[$i]} {$iYears[$i]}" : "{$sLabels[$i]}/{$Month}/{$iYear}") ?>{br}Avg. Points: <?= $sData[$i] ?>' label='<?= $sLabels[$i] ?>' value='<?= $sData[$i] ?>' link='<?= (SITE_URL."api/quonda/graphs-s1.php?User={$User}&OrderNo={$OrderNo}&AuditCode={$AuditCode}&Brand={$Brand}&Vendor={$Vendor}&FromDate={$sFromDate[$i]}&ToDate={$sToDate[$i]}&Category={$Category}&Color={$Color}&Sector={$Sector}&Month=".(($Month == 0) ? $iMonths[$i] : $Month)) ?>' />" +
<?
	}
?>
										    "</chart>");


						objChart.render("AvgPointsChart");
						-->
						</script>
<?
	$sConditions = "";

	if ($Month > 0)
	{
		$sConditions = " qa.audit_type='B' AND qa.audit_result!='' AND qa.report_id='6' ";

		if ($FromDate != "" && $ToDate != "")
			$sConditions .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

		if ($OrderNo != "")
		{
			$sSQL = "SELECT id FROM tbl_po WHERE order_no='$OrderNo'";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );


			$sConditions .= " AND ( ";

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iPoId = $objDb->getField($i, 0);

				if ($i > 0)
					$sConditions .= " OR ";

				$sConditions .= " qa.po_id='$iPoId' OR FIND_IN_SET('$iPoId', qa.additional_pos) ";
			}

			$sConditions .= " ) ";
		}

		if ($Vendor > 0)
			$sConditions .= " AND qa.vendor_id='$Vendor' ";

		else
			$sConditions .= " AND qa.vendor_id IN ($sUserVendors) ";

		if ($sAuditStage != "")
			$sConditions .= " AND qa.audit_stage='$sAuditStage' ";

		else
			$sConditions .= " AND qa.audit_stage!='' ";

		if ($Brand > 0)
		{
			if ($Vendor > 0)
				$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id='$Brand' AND vendor_id='$Vendor') ";

			else
				$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id='$Brand' AND vendor_id IN ($sUserVendors)) ";
		}

		else
		{
			if ($Vendor > 0)
				$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id IN ($sUserBrands) AND vendor_id='$Vendor') ";

			else
				$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id IN ($sUserBrands) AND vendor_id IN ($sUserVendors)) ";
		}

		if ($AuditCode != "")
			$sConditions .= " AND qa.audit_code LIKE '%$AuditCode%' ";

		if ($Color != "")
			$sConditions .= " AND qa.po_id IN (SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE color LIKE '$Color') ";




		$sSQL = "SELECT SUM((given_1 + given_2 + given_3)) FROM tbl_gf_rolls_info WHERE audit_id IN (SELECT id FROM tbl_qa_reports qa WHERE $sConditions)";
		$objDb->query($sSQL);

		$iTotalYardage = $objDb->getField(0, 0);


		$sSQL = "SELECT COALESCE(SUM((defects * grade)), 0) FROM tbl_gf_report_defects WHERE audit_id IN (SELECT id FROM tbl_qa_reports qa WHERE $sConditions)";
		$objDb->query($sSQL);

		$iTotalPoints = $objDb->getField(0, 0);



		$iDefects     = array( );
		$sDefectTypes = array( );
		$iDefectTypes = array( );

		$sSQL = "SELECT dt.type, COALESCE(SUM(grd.defects), 0), dt.id
				 FROM tbl_qa_reports qa, tbl_gf_report_defects grd, tbl_defect_codes dc, tbl_defect_types dt
				 WHERE qa.id=grd.audit_id AND grd.code_id=dc.id AND dc.type_id=dt.id AND $sConditions
				 GROUP BY dc.type_id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sDefectTypes[] = $objDb->getField($i, 0);
			$iDefects[]     = $objDb->getField($i, 1);
			$iDefectTypes[] = $objDb->getField($i, 2);
		}

		$sDefectType = $sDefectTypes[@array_search($Type, $iDefectTypes)];
?>
						<hr />
						<div id="DefectClassChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Pie2D.swf", "DefectClass", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='Defect Classification (<?= formatDate($FromDate) ?> to <?= formatDate($ToDate) ?>){br}Total Yardage = <?= $iTotalYardage ?>, Total Points = <?= $iTotalPoints ?>' showPercentageInLabel='1' formatNumberScale='0' showValues='1' showLabels='0' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' bgcolor='ffffff' bordercolor='ffffff' animation='1'>" +
<?
		for ($i = 0; $i < count($iDefectTypes); $i ++)
		{
?>
											"<set tooltext='Type: <?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?>{br}Defects: <?= $iDefects[$i] ?>' label='<?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?>' value='<?= $iDefects[$i] ?>' link='<?= (SITE_URL."api/quonda/graphs-s2.php?User={$User}&OrderNo={$OrderNo}&AuditCode={$AuditCode}&Brand={$Brand}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}&Category={$Category}&Color={$Color}&Sector={$Sector}&Month={$Month}&Type={$iDefectTypes[$i]}") ?>' />" +
<?
		}
?>

										    "</chart>");


						objChart.render("DefectClassChart");
						-->
						</script>
<?
	}



	if ($Type > 0)
	{
		$iDefects     = array( );
		$sDefectCodes = array( );
		$sDefects     = array( );

		$sSQL = "SELECT dc.code, dc.defect, COALESCE(SUM(grd.defects), 0)
				 FROM tbl_qa_reports qa, tbl_gf_report_defects grd, tbl_defect_codes dc, tbl_defect_types dt
				 WHERE qa.id=grd.audit_id AND grd.code_id=dc.id AND dc.type_id=dt.id AND dt.id='$Type' AND $sConditions
				 GROUP BY dc.id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sDefectCodes[] = $objDb->getField($i, 0);
			$sDefects[]     = $objDb->getField($i, 1);
			$iDefects[]     = $objDb->getField($i, 2);
		}
?>
						<hr />
						<div id="DefectCodeChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "DefectCode", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='<?= $sDefectType ?>' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='' chartBottomMargin='5' plotFillAlpha='95'>" +
<?
		for ($i = 0; $i < count($iDefects); $i ++)
		{
?>
											"<set tooltext='<?= $sDefects[$i] ?>{br}Code: <?= $sDefectCodes[$i] ?>{br}Defects: <?= $iDefects[$i] ?>' label='<?= $sDefectCodes[$i] ?>' value='<?= $iDefects[$i] ?>' link='<?= (SITE_URL."api/quonda/graphs-s5.php?User={$User}&OrderNo={$OrderNo}&AuditCode={$AuditCode}&Brand={$Brand}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}&Category={$Category}&Color={$Color}&AuditStage={$Sector}&Month={$Month}&Type={$Type}&DefectCode={$sDefectCodes[$i]}") ?>' />" +
<?
		}
?>
										    "</chart>");


						objChart.render("DefectCodeChart");
						-->
						</script>
<?
	}
?>
				  <hr />
				  <div style="padding:0px 0px 10px 10px;"><input type="button" value=" Back " class="button" style="font-size:16px; padding:5px 10px 5px 10px;" onclick="document.location='<?= $sBackUrl ?>';" /></div>
