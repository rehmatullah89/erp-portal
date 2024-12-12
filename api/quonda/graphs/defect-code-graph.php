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
	$sDefectCodes = array( );
	$iDefectCodes = array( );
	$sDefects     = array( );

	$sSQL = "SELECT dc.id, dc.code, dc.defect, COALESCE(SUM(qad.defects), 0), qa.report_id
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND dt.id='$Type' $sConditions
			       AND IF(qa.report_id=10, qad.nature='1', TRUE)
			       AND IF(qa.report_id=11, qad.nature<'4', TRUE)
			 GROUP BY dc.id";
	$objDb->query($sSQL);

	$iCount    = $objDb->getCount( );
	$iReportId = $objDb->getField(0, 4);

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDefectCodes[] = $objDb->getField($i, 0);
		$sDefectCodes[] = $objDb->getField($i, 1);
		$sDefects[]     = $objDb->getField($i, 2);
		$iDefects[]     = $objDb->getField($i, 3);
	}

	$sDefectCode = $sDefectCodes[@array_search($Code, $iDefectCodes)];
	$sDefectTile = $sDefects[@array_search($Code, $iDefectCodes)];

	if ($Code == 0 && $iCount == 1)
		$Code = $iDefectCodes[0];


	$sBackUrl = (SITE_URL."api/quonda/graphs-s2.php?User={$User}&OrderNo={$OrderNo}&AuditCode={$AuditCode}&Brand={$Brand}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}&Line={$Line}&AuditStage={$AuditStage}&Type={$Type}");
?>
						<hr />
						<div id="DefectCodeChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "DefectCode", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='<?= "{$sDefectType},  Line= {$sLineName}" ?>' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='' chartBottomMargin='5' plotFillAlpha='95'>" +
<?
	for ($i = 0; $i < count($iDefects); $i ++)
	{
?>
											"<set tooltext='<?= htmlentities($sDefects[$i], ENT_QUOTES) ?>{br}Code: <?= $sDefectCodes[$i] ?>{br}Defects: <?= $iDefects[$i] ?>' label='<?= $sDefectCodes[$i] ?>' value='<?= $iDefects[$i] ?>' link='<?= (SITE_URL."api/quonda/graphs-s".(($iReportId == 4) ? 5 : 4).".php?User={$User}&OrderNo={$OrderNo}&AuditCode={$AuditCode}&Brand={$Brand}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}&AuditStage={$AuditStage}&Line={$Line}&Type={$Type}&".(($iReportId == 4) ? "DefectCode={$sDefectCodes[$i]}" : "Code={$iDefectCodes[$i]}")) ?>' />" +
<?
	}
?>
										    "</chart>");


						objChart.render("DefectCodeChart");
						-->
						</script>
