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
	$sStageDr      = getDbValue("dr_field", "tbl_audit_stages", "code='$AuditStage'");
	$fTargetDhu    = getDbValue($sStageDr, "tbl_vendors", "id='$iVendorId'");


	$sSQL = "SELECT qa.line_id,
	                l.line AS _Line,
					COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature>'0' AND NOT FIND_IN_SET(code_id, '$sExcludedDefects'))) AS _Defects
			 FROM tbl_po po, tbl_qa_reports qa, tbl_lines l
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
						<div id="VendorLinesChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "VendorLines", "100%", "420", "0", "1");

						objChart.setXMLData("<chart caption='TGI: <?= formatNumber($iTotalGmts, false) ?>  TGR: <?= formatNumber($iTotalDefects, false) ?>  DHU: <?= formatNumber($fAvgDhu) ?>%' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fLineDhu) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='<?= strtolower(str_replace(" ", "-", $sStageTitle)) ?>'>" +
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


						objChart.render("VendorLinesChart");
						-->
						</script>


						<br />
						<h3>Top 3 Defects</h3>

					    <table border="1" bordercolor="#eeeeee" cellspacing="0" cellpadding="4" width="100%">
						  <tr valign="top" bgcolor="#f6f6f6">
						    <td width="15%"><b>Code</b></td>
						    <td width="53%"><b>Defect Label</b></td>
						    <td width="17%"><b>Defects</b></td>
						    <td width="15%"><b>DHU</b></td>
						  </tr>

<?
	$sSQL = "SELECT dc.defect, dc.code, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt, tbl_lines l
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND qa.line_id=l.id AND qad.nature>'0' $sConditions
			       AND NOT FIND_IN_SET(dc.id, '$sExcludedDefects')
			 GROUP BY dc.id
			 ORDER BY _Defects DESC, dc.code";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0, $j = 1; $i < $iCount; $i ++, $j ++)
	{
		$sDefect  = $objDb->getField($i, 0);
		$sCode    = $objDb->getField($i, 1);
		$iDefects = $objDb->getField($i, 2);


		$sTop3Codes   = $sCode;
		$sTop3Defects = $sDefect;
		$iTop3Defects = $iDefects;

		while(($i + 1) < $iCount && $iDefects == $objDb->getField(($i + 1), 2))
		{
			$sTop3Codes   .= ("<br />".$objDb->getField(($i + 1), 1));
			$sTop3Defects .= ("<br />".$objDb->getField(($i + 1), 0));
			$iTop3Defects += $objDb->getField(($i + 1), 2);

			$i ++;
		}

		//$fPercentage = @round((($iTop3Defects / $iTotalDefects) * 100), 2);
		$fTop3Dhu = @round((($iTop3Defects / $iTotalGmts) * 100), 2);
?>
						  <tr valign="top">
						    <td><?= $sTop3Codes ?></td>
						    <td><?= $sTop3Defects ?></td>
						    <td><?= formatNumber($iTop3Defects, false) ?></td>
						    <!--<td><?= formatNumber($fPercentage) ?></td>-->
						    <td><?= formatNumber($fTop3Dhu) ?></td>
						  </tr>
<?
		if ($j == 3)
			break;
	}
?>
						</table>


						<br />
						<h3>Top 3 Lines</h3>

					    <table border="1" bordercolor="#eeeeee" cellspacing="0" cellpadding="4" width="100%">
						  <tr valign="top" bgcolor="#f6f6f6">
						    <td width="55%"><b>Line</b></td>
						    <td width="15%"><b>TGI</b></td>
						    <td width="15%"><b>TGR</b></td>
						    <td width="15%"><b>DHU</b></td>
						  </tr>
<?
	@array_multisort($fLineDhu, SORT_DESC, $iLineGmts, $iLineDefects, $iLines, $sLines);


	$sTop3Lines   = array( );
	$iTop3Lines   = array( );
	$iTop3Defects = array( );

	for ($i = 0, $j = 0; $i < count($fLineDhu); $i ++, $j ++)
	{
		$sTopLines   = $sLines[$i];
		$iTopGmts    = $iLineGmts[$i];
		$iTopDefects = $iLineDefects[$i];
		$fTopDhu     = $fLineDhu[$i];

		$iTop3Lines[$j]   = array( );
		$iTop3Lines[$j][] = $iLines[$i];

		while($fLineDhu[$i] == $fLineDhu[$i + 1] && ($i + 1) < count($fLineDhu))
		{
			$sTopLines      .= (", ".$sLines[$i + 1]);
			$iTopGmts       += $iLineGmts[$i + 1];
			$iTopDefects    += $iLineDefects[$i + 1];
			$iTop3Lines[$j][] = $iLines[$i + 1];

			$i ++;
		}

		$sTop3Lines[$j]   = $sTopLines;
		$iTop3Defects[$j] = $iTopGmts;
?>
						  <tr valign="top">
						    <td><?= $sTopLines ?></td>
						    <td><?= formatNumber($iTopGmts, false) ?></td>
						    <td><?= formatNumber($iTopDefects, false) ?></td>
						    <td><?= formatNumber($fTopDhu) ?></td>
						  </tr>
<?
		if (count($sTop3Lines) == 3)
			break;
	}
?>
						</table>


<?
	for( $i = 0; $i < count($iTop3Lines); $i ++)
	{
?>
						<br />
						<h3>Top 3 Defects in Line <?= $sTop3Lines[$i] ?></h3>

					    <table border="1" bordercolor="#eeeeee" cellspacing="0" cellpadding="4" width="100%">
						  <tr valign="top" bgcolor="#f6f6f6">
						    <td width="15%"><b>Code</b></td>
						    <td width="53%"><b>Defect Label</b></td>
						    <td width="17%"><b>Defects</b></td>
						    <td width="15%"><b>%tage</b></td>
						  </tr>

<?
		$iTopEntries = array( );
		$sTopLines   = @implode(",", $iTop3Lines[$i]);

		$sSQL = "SELECT dc.defect, dc.code, COALESCE(SUM(qad.defects), 0) AS _Defects
				 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt, tbl_lines l
				 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND qa.line_id=l.id AND qad.nature>'0' $sConditions
					   AND FIND_IN_SET(l.id, '$sTopLines')
					   AND NOT FIND_IN_SET(dc.id, '$sExcludedDefects')
				 GROUP BY qad.code_id
				 ORDER BY _Defects DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($j = 0; $j < $iCount; $j ++)
		{
			$sDefect  = $objDb->getField($j, 0);
			$sCode    = $objDb->getField($j, 1);
			$iDefects = $objDb->getField($j, 2);

			$fPercentage = @round((($iDefects / $iTop3Defects[$i]) * 100), 2);


			if (!@in_array($iDefects, $iTopEntries))
				$iTopEntries[] = $iDefects;

			if (count($iTopEntries) == 4)
				break;
?>
						  <tr valign="top">
						    <td><?= $sCode ?></td>
						    <td><?= $sDefect ?></td>
						    <td><?= formatNumber($iDefects, false) ?></td>
						    <td><?= formatNumber($fPercentage) ?></td>
						  </tr>
<?
		}
?>
						</table>
<?
	}
?>