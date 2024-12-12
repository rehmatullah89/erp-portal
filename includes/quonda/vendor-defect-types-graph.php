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

	$sDefectTypes  = array( );
	$iDefectTypes  = array( );
	$iTotalDefects = array( );

	$sSQL = "SELECT dt.id, dt.type AS _DefectType, COALESCE(SUM(qad.defects), 0) AS _Defects
		 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
		 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND qad.nature>'0' $sConditions
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
?>
				  <h2>Defects Classification (<?= $sStageTitle ?>)<br />From: <?= formatDate($FromDate) ?>  &nbsp; To:  <?= formatDate($ToDate) ?></h2>

				  <div class="tblSheet">
				    <div id="DefectClassChart">loading...</div>
				  </div>

				  <br />

					<script type="text/javascript">
					<!--
					var objChart = new FusionCharts("scripts/fusion-charts/charts/Pie2D.swf", "DefectClass", "100%", "420", "0", "1");

					objChart.setXMLData("<chart caption='' showPercentageInLabel='1' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' bgcolor='ffffff' bordercolor='ffffff' animation='1' labelDisplay='WRAP' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='defect-types'>" +
<?
	for ($i = 0; $i < count($iDefectTypes); $i ++)
	{
?>
										"<set color='<?= $sDefectColors[$iDefectTypes[$i]] ?>' tooltext='Type: <?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?>{br}Defects: <?= $iTotalDefects[$i] ?>' label='<?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?>' value='<?= $iTotalDefects[$i] ?>' link='javascript:showTypeGraph(<?= $iDefectTypes[$i] ?>)' />" +
<?
	}
?>

										"</chart>");


					objChart.render("DefectClassChart");
					-->
					</script>


					<div id="DefectType" style="display:none;">
					  <h2>Defects Codes (<?= $sStageTitle ?>)<br />From: <?= formatDate($FromDate) ?>  &nbsp; To:  <?= formatDate($ToDate) ?></h2>

					  <div class="tblSheet">
					    <div id="DefectCodeChart">loading...</div>
					  </div>

					  <br />
					</div>


					<div id="DefectCode" style="display:none;">
					  <h2>Defects Areas (<?= $sStageTitle ?>)<br />From: <?= formatDate($FromDate) ?>  &nbsp; To:  <?= formatDate($ToDate) ?></h2>

					  <div class="tblSheet">
					    <div id="DefectAreaChart">loading...</div>
					  </div>

					  <br />
					</div>
