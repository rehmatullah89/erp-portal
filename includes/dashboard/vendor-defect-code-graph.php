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

	$sLines       = array( );
	$iLines       = array( );
	$fLineDhu     = array( );
	$iLineGmts    = array( );
	$iLineDefects = array( );

	$iTotalGmts    = 0;
	$iTotalDefects = 0;


	$sSQL = "SELECT qa.line_id,
	                l.line AS _Line,
					COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature>'0' AND FIND_IN_SET(code_id, '$sDefectCodes'))) AS _Defects
			 FROM tbl_po po, tbl_qa_reports qa, tbl_lines l, tbl_defect_types dt
			 WHERE po.id=qa.po_id AND qa.line_id=l.id $sConditions
			 GROUP BY qa.line_id
	         ORDER BY _Line";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iLineId  = $objDb->getField($i, "line_id");
		$sLine    = $objDb->getField($i, "_Line");
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");


		$sLines[]       = $sLine;
		$iLines[]       = $iLineId;
		$iLineGmts[]    = $iGmts;
		$iLineDefects[] = $iDefects;
		$fLineDhu[]     = @round((($iDefects / $iGmts) * 100), 2);

		$iTotalGmts    += $iGmts;
		$iTotalDefects += $iDefects;
	}


	$fAvgDhu = @round((($iTotalDefects / $iTotalGmts) * 100), 2);
?>
						<div id="VendorLinesCodesChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "VendorLinesCodes", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='TGI: <?= formatNumber($iTotalGmts, false) ?>  TGR: <?= formatNumber($iTotalDefects, false) ?>  DHU: <?= formatNumber($fAvgDhu) ?>%' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fLineDhu) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='defect-code-'>" +
<?
	for ($i = 0; $i < count($fLineDhu); $i ++)
	{
?>
											"<set tooltext='Line: <?= str_replace("\n", "", $sLines[$i]) ?>{br}DHU: <?= formatNumber($fLineDhu[$i]) ?>%{br}TGI: <?= formatNumber($iLineGmts[$i], false) ?>{br}TGR: <?= formatNumber($iLineDefects[$i], false) ?>' label='<?= str_replace("\n", "", $sLines[$i]) ?>' value='<?= $fLineDhu[$i] ?>' link='' />" +
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


	if ($fAvgDhu > 0)
	{
?>
											"  <line toolText='Average Line (<?= $fAvgDhu ?>%)' startValue='<?= $fAvgDhu ?>' displayValue='Average' color='0000ff' />" +
<?
	}
?>
											"</trendlines>" +

										    "</chart>");


						objChart.render("VendorLinesCodesChart");
						-->
						</script>
