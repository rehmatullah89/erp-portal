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
	$sDefectTypes = array( );
	$iDefectTypes = array( );


	$sSQL = "SELECT dt.id, dt.type, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id $sConditions
			       AND IF(qa.report_id=10, qad.nature='1', TRUE)
			       AND IF(qa.report_id=11, qad.nature<'4', TRUE)
			 GROUP BY dc.type_id
			 ORDER BY dt.type";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDefectTypes[] = $objDb->getField($i, 0);
		$sDefectTypes[] = $objDb->getField($i, 1);
		$iDefects[]     = $objDb->getField($i, 2);
	}

	if ($Type == 0 && $iCount == 1)
		$Type = $iDefectTypes[0];

	$sDefectType = $sDefectTypes[@array_search($Type, $iDefectTypes)];


	$sBackUrl = (SITE_URL."api/quonda/graphs-s1.php?User={$User}&OrderNo={$OrderNo}&AuditCode={$AuditCode}&Brand={$Brand}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}&Line={$Line}&AuditStage={$AuditStage}");
?>
						<hr />
						<div id="DefectClassChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Pie2D.swf", "DefectClass", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='Defect Classification (Line: <?= $sLineName ?>,  DHU: <?= $fLineDhu ?>%){br}TGI = <?= $iLineGmts ?>, TGR = <?= $iLineDefects ?>' showPercentageInLabel='1' formatNumberScale='0' showValues='1' showLabels='0' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' bgcolor='ffffff' bordercolor='ffffff' animation='1'>" +
<?
	for ($i = 0; $i < count($iDefectTypes); $i ++)
	{
?>
											"<set tooltext='Type: <?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?>{br}Defects: <?= $iDefects[$i] ?>' label='<?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?>' value='<?= $iDefects[$i] ?>' link='<?= (SITE_URL."api/quonda/graphs-s3.php?User={$User}&OrderNo={$OrderNo}&AuditCode={$AuditCode}&Brand={$Brand}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}&AuditStage={$AuditStage}&Line={$Line}&Type={$iDefectTypes[$i]}") ?>' />" +
<?
	}
?>

										    "</chart>");


						objChart.render("DefectClassChart");
						-->
						</script>
