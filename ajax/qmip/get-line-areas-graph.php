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
	$Date       = IO::strValue("Date");
	$Report     = IO::intValue("Report");
	$AuditStage = IO::strValue("AuditStage");
	$Line       = IO::intValue("Line");
	$Hour       = IO::intValue("Hour");
	$DefectType = IO::intValue("DefectType");



	if ($Vendor > 0)
		$sVendorBrands = getDbValue("GROUP_CONCAT(DISTINCT(brand_id) SEPARATOR ',')", "tbl_po", "vendor_id='$Vendor'");

	else
		$sVendorBrands = getDbValue("GROUP_CONCAT(DISTINCT(brand_id) SEPARATOR ',')", "tbl_po", "FIND_IN_SET(vendor_id, '$sQmipVendors')");

	$sReportTypes  = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStages  = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");


	$sConditions  = " AND qa.audit_type='B' AND qa.audit_date='$Date' AND FIND_IN_SET(qa.report_id, '$sQmipReports') ";
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

	if ($Line > 0)
		$sConditions .= " AND qa.line_id='$Line' ";

	if ($Hour > 0)
		$sConditions .= " AND HOUR(TIME(qad.date_time))='$Hour' ";
/*
	if ($Vendor == 13)
		$sConditions .= " AND qa.unit_id='259' ";

	else if ($Vendor == 229)
		$sConditions .= " AND qa.unit_id='304' ";
*/
	if ($DefectType > 0)
		$sConditions .= " AND dt.id='$DefectType' ";



	$sDefectAreas  = array( );
	$iDefectAreas  = array( );
	$iTotalDefects = array( );

	$sSQL = "SELECT da.id, da.area AS _DefectArea, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt, tbl_defect_areas da
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.report_id=qa.report_id AND dc.type_id=dt.id AND da.id=qad.area_id
			       $sConditions
			 GROUP BY da.id
			 ORDER BY _Defects DESC, _DefectArea ASC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDefectArea = $objDb->getField($i, "id");
		$sDefectArea = $objDb->getField($i, "_DefectArea");
		$iDefects    = $objDb->getField($i, "_Defects");

		if (@in_array($iDefectArea, $iDefectAreas))
		{
			$iIndex = @array_search($iDefectArea, $iDefectAreas);

			$iTotalDefects[$iIndex] += $iDefects;
		}

		else
		{
			$iDefectAreas[]  = $iDefectArea;
			$sDefectAreas[]  = $sDefectArea;
			$iTotalDefects[] = $iDefects;
		}
	}


	$sLine        = getDbValue("line", "tbl_lines", "id='$Line'");
	$sDefectColors = getList("tbl_defect_types", "id", "color");
?>
		                     <chart caption='Defect Areas (<?= $sLine ?>)' showPercentageInLabel='1' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' bgcolor='ffffff' bordercolor='ffffff' animation='1' labelDisplay='WRAP' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='defect-classification'>
<?
	$iTotal = @array_sum($iTotalDefects);

	for ($i = 0; $i < count($iDefectAreas); $i ++)
	{
		$fPercent = @round((($iTotalDefects[$i] / $iTotal) * 100), 2);
?>
									<set color='<?= $sDefectColors[$iDefectAreas[$i]] ?>' tooltext='Area: <?= htmlentities($sDefectAreas[$i], ENT_QUOTES) ?>{br}Defects: <?= $iTotalDefects[$i] ?> (<?= formatNumber($fPercent) ?>%)' label='<?= htmlentities($sDefectAreas[$i], ENT_QUOTES) ?> [<?= $iTotalDefects[$i] ?>]' value='<?= $iTotalDefects[$i] ?>' />
<?
	}
?>
							</chart>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>