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


	$sDefectTypes  = array( );
	$iDefectTypes  = array( );
	$iTotalDefects = array( );

	$sSQL = "SELECT dt.id, dt.type AS _DefectType, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id
			       $sConditions
			 GROUP BY dc.type_id
			 ORDER BY _Defects DESC, _DefectType ASC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDefectType = $objDb->getField($i, "id");
		$sDefectType = $objDb->getField($i, "_DefectType");
		$iDefects    = $objDb->getField($i, "_Defects");

		if (@in_array($iDefectType, $iDefectTypes))
		{
			$iIndex = @array_search($iDefectType, $iDefectTypes);

			$iTotalDefects[$iIndex] += $iDefects;
		}

		else
		{
			$iDefectTypes[]  = $iDefectType;
			$sDefectTypes[]  = $sDefectType;
			$iTotalDefects[] = $iDefects;
		}
	}


	$sLine        = getDbValue("line", "tbl_lines", "id='$Line'");
	$sDefectColors = getList("tbl_defect_types", "id", "color");
?>
		                     <chart caption='Defect Types (<?= $sLine ?>)' showPercentageInLabel='1' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' bgcolor='ffffff' bordercolor='ffffff' animation='1' labelDisplay='WRAP' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='defect-classification'>
<?
	$iTotal = @array_sum($iTotalDefects);

	for ($i = 0; $i < count($iDefectTypes); $i ++)
	{
		$fPercent = @round((($iTotalDefects[$i] / $iTotal) * 100), 2);
?>
									<set color='<?= $sDefectColors[$iDefectTypes[$i]] ?>' tooltext='Type: <?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?>{br}Defects: <?= $iTotalDefects[$i] ?> (<?= formatNumber($fPercent) ?>%)' label='<?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?> [<?= $iTotalDefects[$i] ?>]' value='<?= $iTotalDefects[$i] ?>' link='javascript:showAreasGraph("Vendor=<?= $Vendor ?>&Brand=<?= $Brand ?>&Date=<?= $Date ?>&Report=<?= $Report ?>&AuditStage=<?= $AuditStage ?>&Line=<?= $Line ?>&Hour=<?= $Hour ?>&DefectType=<?= $iDefectTypes[$i] ?>")' />
<?
	}
?>
							</chart>
	|-|Vendor=<?= $Vendor ?>&Brand=<?= $Brand ?>&Date=<?= $Date ?>&Report=<?= $Report ?>&AuditStage=<?= $AuditStage ?>&Line=<?= $Line ?>&Hour=<?= $Hour ?>|-|

    <div class="tblSheet">
	  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
		<tr class="headerRow">
		  <td width="20%">Auditor</td>
		  <td width="10%">Audit Code</td>
		  <td width="14%">PO</td>
		  <td width="13%">Brand</td>
		  <td width="13%">Style</td>
		  <td width="12%">Audit Stage</td>
		  <td width="18%">Report Type</td>
		</tr>
<?

	$sSQL = "SELECT DISTINCT(qa.audit_code) AS _AuditCode, qa.user_id, qa.report_id, qa.brand_id, qa.audit_stage, qa.po_id, qa.style_id
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad
			 WHERE qa.id=qad.audit_id $sConditions";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$sClass = array("evenRow", "oddRow");

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAuditCode   = $objDb->getField($i, '_AuditCode');
		$iUser        = $objDb->getField($i, 'user_id');
		$iPo          = $objDb->getField($i, 'po_id');
		$iBrand       = $objDb->getField($i, 'brand_id');
		$iStyle       = $objDb->getField($i, 'style_id');
		$sAuditStage  = $objDb->getField($i, 'audit_stage');
		$iReport      = $objDb->getField($i, 'report_id');
?>
	    <tr class="<?= $sClass[($i % 2)] ?>">
		  <td><?= getDbValue("name", "tbl_users", "id='$iUser'") ?></td>
		  <td><a href="<?= SITE_URL ?>dashboard/progress.php?AuditCode=<?= $sAuditCode ?>" target="_blank"><?= $sAuditCode ?></a></td>
		  <td><a href="<?= SITE_URL ?>dashboard/po-progress.php?PO=<?= $iPo ?>&Style=<?= $iStyle ?>" target="_blank"><?= getDbValue("CONCAT(order_no, ' ', order_status)", "tbl_po", "id='$iPo'") ?></a></td>
		  <td><?= getDbValue("brand", "tbl_brands", "id='$iBrand'") ?></td>
		  <td><?= getDbValue("style", "tbl_styles", "id='$iStyle'") ?></td>
		  <td><?= getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'") ?></td>
		  <td><?= getDbValue("report", "tbl_reports", "id='$iReport'") ?></td>
	    </tr>
<?
	}
?>
	  </table>
	</div>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>