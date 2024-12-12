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


	$Vendor     = IO::strValue("Vendor");
	$AuditStage = IO::strValue("AuditStage");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");
	$Brand      = IO::getArray("Brand");
	$Type       = IO::intValue("Type");
	$Code       = IO::intValue("Code");


	$sReportTypes = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sConditions  = " AND qa.audit_type='B' AND qa.audit_result!='' AND qa.audit_stage='$AuditStage' AND qa.vendor_id='$Vendor' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') AND qa.report_id!='6' AND FIND_IN_SET(qa.report_id, '$sReportTypes') ";

	if (count($Brand) > 0 && $Brand[0] != "")
		$sConditions .= (" AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id IN (".@implode(",", $Brand).") AND vendor_id='$Vendor') ");



	$sDefectCode   = getDbValue("CONCAT(defect, '   (', `code`, ')')", "tbl_defect_codes", "type_id='$Type' AND id='$Code'");
	$sDefectColors = getList("tbl_defect_types", "id", "color");

	$sDefectAreas  = array( );
	$iDefectAreas  = array( );
	$iTotalDefects = array( );


	$sSQL = "SELECT da.id, da.area AS _DefectArea, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt, tbl_defect_areas da
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND da.id=qad.area_id AND dc.id='$Code' AND qad.nature>'0' $sConditions
			 GROUP BY qad.area_id
			 ORDER BY _Defects ASC, _DefectArea ASC";
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
?>
	<chart caption='<?= $sDefectCode ?>' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='' chartBottomMargin='5' plotFillAlpha='95' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='<?= strtolower(str_replace(" ", "-", $sDefectCode)) ?>'>
<?
	for ($i = 0; $i < count($iDefectAreas); $i ++)
	{
?>
		<set color='<?= $sDefectColors[$Type] ?>' tooltext='<?= htmlentities($iTotalDefects[$i], ENT_QUOTES) ?>{br}Area: <?= $sDefectAreas[$i] ?>{br}Defects: <?= $iTotalDefects[$i] ?>' label='<?= $sDefectAreas[$i] ?>' value='<?= $iTotalDefects[$i] ?>' />
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