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

	$iDefects     = array( );
	$iDefectAreas = array( );
	$sDefectAreas = array( );

	$sSQL = "SELECT da.id, da.area, COALESCE(SUM(qad.defects), 0)
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt, tbl_defect_areas da
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND da.id=qad.area_id AND dc.id='$Code' $sConditions
			       AND IF(qa.report_id=10, qad.nature='1', TRUE)
			       AND IF(qa.report_id=11, qad.nature<'4', TRUE)
			 GROUP BY qad.area_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDefectAreas[] = $objDb->getField($i, 0);
		$sDefectAreas[] = $objDb->getField($i, 1);
		$iDefects[]     = $objDb->getField($i, 2);
	}

	$sBackUrl = (SITE_URL."api/quonda/graphs-s3.php?User={$User}&OrderNo={$OrderNo}&AuditCode={$AuditCode}&Brand={$Brand}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}&Line={$Line}&AuditStage={$AuditStage}&Type={$Type}");
?>
						<hr />
						<div id="DefectAreaChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "DefectArea", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='<?= htmlentities("{$sDefectCode} {$sDefectTile},  Line= {$sLineName}", ENT_QUOTES) ?>' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='ROTATE' slantLabels='1'>" +
<?
	for ($i = 0; $i < count($iDefects); $i ++)
	{
?>
											"<set tooltext='Area: <?= htmlentities($sDefectAreas[$i], ENT_QUOTES) ?>{br}Defects: <?= $iDefects[$i] ?>' label='<?= htmlentities($sDefectAreas[$i], ENT_QUOTES) ?>' value='<?= $iDefects[$i] ?>' link='<?= (SITE_URL."api/quonda/graphs-s5.php?User={$User}&OrderNo={$OrderNo}&AuditCode={$AuditCode}&Brand={$Brand}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}&AuditStage={$AuditStage}&Line={$Line}&Type={$Type}&Code={$Code}&DefectCode={$sDefectCode}&AreaCode={$iDefectAreas[$i]}&ReportId={$iReportId}") ?>' />" +
<?
	}
?>
										    "</chart>");


						objChart.render("DefectAreaChart");
						-->
						</script>
