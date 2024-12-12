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

	$sConditions = " AND FIND_IN_SET(qa.report_id, '$sReportTypes') ";

	if ($AuditCode != "")
		$sConditions .= " AND qa.audit_code LIKE '%$AuditCode%' ";

	if ($sAuditStage != "")
		$sConditions .= " AND qa.audit_stage='$sAuditStage' ";

	else
		$sConditions .= " AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

	if ($OrderNo != "")
	{
		$sConditions .= " AND (";

		$sSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '%$OrderNo%'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPoId = $objDb->getField($i, 0);

			if ($i > 0)
				$sConditions .= " OR ";

			$sConditions .= " qa.po_id='$iPoId' OR FIND_IN_SET('$iPoId', qa.additional_pos) ";
		}

		$sConditions .= ") ";
	}

	if ($StyleNo != "")
	{
		$sConditions .= " AND (";

		$sSQL = "SELECT id FROM tbl_styles WHERE style LIKE '%$StyleNo%'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iStyleId = $objDb->getField($i, 0);

			if ($i > 0)
				$sConditions .= " OR ";

			$sConditions .= " qa.style_id='$iStyleId' ";
		}

		$sConditions .= ") ";
	}

	if ($Vendor > 0)
		$sConditions .= " AND po.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Brand > 0)
		$sConditions .= " AND po.brand_id='$Brand' ";

	else
		$sConditions .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";




	$sTypes = array( );  // Label   Min  Std  Max  LowerLimit UpperLimit

	$sTypes[0] = array(  array("Count", 7.00, 7.10, 7.20, 6, 8),
						 array("Count CV%", "", 1.00, 1.50, 0, 3),
						 array("RKM", 16.00, 18.00, "", 14, 24),
						 array("Yarn Strength (SYS)", 1100, 1300, "", 500, 2000),
						 array("SYS CV%", "", 10.00, 13.00, 6, 15),
						 array("Elongation", 7.50, 8.00, 8.50, 6.5, 9.5),
						 array("Elongation CV%", "", 9.00, 11.00, 6, 12),
						 array("U%", "", "", ""),
						 array("U% CV", "", "", ""),
						 array("Thin -50", "", 10.00, 11.00, 8, 12),
						 array("Thick +50", "", "", ""),
						 array("Neps +200", "", "", ""),
						 array("Hairriness", "", 9.50, "", 7.5, 11),
						 array("Twist per inch (TPI)", 12.10, 12.30, 12.50, 11, 13.5) );

	$sTypes[1] = array(  array("Count", 14.02, 14.17, 14.32, 13.80, 14.6),
						 array("Count CV%", "", 1.00, 1.50, 0, 3),
						 array("RKM", 14.00, 15.00, "", 10, 20),
						 array("Yarn Strength (SYS)", 594, 636, "", 300, 1000),
						 array("SYS CV%", "", 10.00, 13.00, 6, 15),
						 array("Elongation", 9.15, 9.75, 10.35, 8.5, 11.5),
						 array("Elongation CV%", "", 9.00, 11.00, 7, 12),
						 array("U%", "", 10, 12, 7, 14),
						 array("U% CV", "", 3, 6, 0, 8),
						 array("Thin -50", "", 0, ""),
						 array("Thick +50", "", 2.00, "", 0, 5),
						 array("Neps +200", "", 2.00, "", 0, 5),
						 array("Hairriness", "", 9.50, "", 7.50, 11.50),
						 array("Twist per inch (TPI)", 16.50, 16.90, 17.30, 15, 18.5) );

	$sTypes[2] = array(  array("Count", 9.35, 9.45, 9.55, 9.1, 9.75),
						 array("Count CV%", "", 1.00, 1.50, 0, 3),
						 array("RKM", 14.00, 15.00, "", 10, 20),
						 array("Yarn Strength (SYS)", 920, 986, "", 500, 2000),
						 array("SYS CV%", "", 10.00, 13.00, 6, 15),
						 array("Elongation", 9.50, 10.00, 10.50, 8.5, 11.5),
						 array("Elongation CV%", "", 9.00, 11.00, 7, 12),
						 array("U%", "", 10, 12, 7, 14),
						 array("U% CV", "", 3, 6, 0, 8),
						 array("Thin -50", "", 0.00, ""),
						 array("Thick +50", "", 2.00, "", 0, 5),
						 array("Neps +200", "", 2.00, "", 0, 5),
						 array("Hairriness", "", 9.50, "", 7.5, 11.5),
						 array("Twist per inch (TPI)", 13.97, 14.20, 14.48, 12.5, 15.5) );

	$sTypes[3] = array(  array("Count", 7.00, 7.10, 7.20, 6.9, 7.3),
						 array("Count CV%", "", 1.00, 1.50, 0.5, 2.5),
						 array("RKM", 16.00, 18.00, "", 12, 22),
						 array("Yarn Strength (SYS)", 1100, 1300, "", 500, 2000),
						 array("SYS CV%", "", 10.00, 13.00, 7, 14),
						 array("Elongation", 7.00, 7.50, 8.00, 6.0, 9.0),
						 array("Elongation CV%", "", 9.00, 11.00, 7.0, 12.0),
						 array("U%", "", "", ""),
						 array("U% CV", "", "", ""),
						 array("Thin -50", "", 10.00, 11.00, 8, 12),
						 array("Thick +50", "", "", ""),
						 array("Neps +200", "", "", ""),
						 array("Hairriness", "", 9.50, "", 7.5, 11.5),
						 array("Twist per inch (TPI)", 12.10, 12.30, 12.50, 11, 13.5),
						 array("Tpi CV%", "", 3.00, 5.00, 0, 8) );
?>

			    <div class="tblSheet">
			      <div style="padding:10px;">
			        <b>Yarn Count: </b>
			        <select onchange="showGraphs(this.value);">
			          <option value="0">7.1/1 Carded Slub (88)</option>
			          <option value="1">14.17/1 Combed 70-D Lycra</option>
			          <option value="2">9.45/1 Carded 70-D Lycra</option>
			          <option value="3">7.1/1 Carded Slub (22)</option>
			        </select>
			      </div>

			      <script type="text/javascript">
			      <!--
			      		function showGraphs(iIndex)
			      		{
			      			for (var i = 0; i < 4; i ++)
			      				$("Type" + i).style.display = "none";

			      			$("Type" + iIndex).style.display = "block";
			      		}
			      -->
			      </script>

<?
	for ($i = 0; $i < count($sTypes); $i ++)
	{
		$sDates = array( );

		for ($j = 0; $j < count($sTypes[$i]); $j ++)
			$sTypes[$i][$j][6] = array( );

		switch ($i)
		{
			case 0 : $sType = "7.1/1 Carded Slub (88)"; break;
			case 1 : $sType = "14.17/1 Combed 70-D Lycra"; break;
			case 2 : $sType = "9.45/1 Carded 70-D Lycra"; break;
			case 3 : $sType = "7.1/1 Carded Slub (22)"; break;
		}

		$sSQL = "SELECT AVG(ypc.actual_count_p) AS _Count,
						AVG(ypc.cv_count_p) AS _CvCount,
						AVG(ypc.rkm_p) AS _Rkm,
						AVG(ypc.sy_str_p) AS _Sys,
						AVG(ypc.cv_p) AS _SysCv,
						AVG(ypc.elongation_p) AS _Elongation,
						AVG(ypc.elongation_cv_p) AS _ElongationCv,
						AVG(ypc.ucvm_p) AS _U,
						AVG(ypc.cv_bu_p) AS _Ucv,
						AVG(ypc.thin_p) AS _Thin,
						AVG(ypc.thick_p) AS _Thick,
						AVG(ypc.neps_p) AS _Neps,
						AVG(ypc.hairiness_p) AS _Hairiness,
						AVG(ypc.tpi_p) AS _Tpi,
						AVG(ypc.tpi_cv_p) AS _TpiCv,
						qa.audit_date AS _Date
				 FROM tbl_po po, tbl_qa_reports qa, tbl_yarn_product_checks ypc
				 WHERE po.id=qa.po_id AND qa.id=ypc.audit_id AND qa.audit_type='B' AND qa.audit_result!='' AND qa.report_id='9' AND ypc.yarn_count='$sType' $sConditions $sAuditorSQL
				 GROUP BY qa.audit_date
				 ORDER BY _Date";
		$objDb->query($sSQL);


		$iCount = $objDb->getCount( );

		for ($j = 0; $j < $iCount; $j ++)
		{
			$sDates[$j] = $objDb->getField($j, "_Date");

			for ($k = 0; $k < count($sTypes[$i]); $k ++)
				$sTypes[$i][$k][6][$j] = @round($objDb->getField($j, $k), 2);
		}
?>
			      <div id="Type<?= $i ?>" style="display:<?= (($i == 0) ? 'block' : 'none') ?>">
<?
		if ($iCount == 0)
		{
?>
			        <hr />

			        <div style="padding:25px;">
			          No Audit Done for <b>"<?= $sType ?>"</b>
			        </div>
			      </div>
<?
			continue;
		}


		for ($j = 0; $j < count($sTypes[$i]); $j ++)
		{
			$sChartId = $sTypes[$i][$j][0];
			$sChartId = str_replace(" ", "", $sChartId);
			$sChartId = str_replace("%", "", $sChartId);
			$sChartId = str_replace("+", "", $sChartId);
			$sChartId = str_replace("-", "", $sChartId);
?>
			        <hr />


					<div id="Chart<?= $i ?>_<?= $j ?>">loading...</div>

					<script type="text/javascript">
					<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Line.swf", "<?= $sChartId ?>", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='<?= $sTypes[$i][$j][0] ?>' yAxisMinValue='<?= $sTypes[$i][$j][4] ?>' yAxisMaxValue='<?= $sTypes[$i][$j][5] ?>' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='2' numberSuffix='<?= ((@strpos($sTypes[$i][$j][0], "%") !== FALSE) ? '%' : '') ?>' chartBottomMargin='5' rotateValues='1' valuePosition='auto' labelDisplay='ROTATE' slantLabels='1' bgcolor='ffffff' bordercolor='ffffff' animation='1' showAlternateHGridColor='1' AlternateHGridColor='ff5904' divLineColor='ff5904' divLineAlpha='20' alternateHGridAlpha='5' lineColor='FF5904' lineAlpha='85' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='<?= $sChartId ?>'>" +
<?
			for ($k = 0; $k < $iCount; $k ++)
			{
?>
											"<set tooltext='Date: <?= $sDates[$k] ?>{br}<?= $sTypes[$i][$j][0] ?>: <?= $sTypes[$i][$j][6][$k] ?><?= ((@strpos($sTypes[$i][$j][0], "%") !== FALSE) ? '%' : '') ?>' label='<?= $sDates[$k] ?>' value='<?= $sTypes[$i][$j][6][$k] ?>' />" +
<?
			}
?>

											"<trendlines>" +
<?
			if ($sTypes[$i][$j][1] != "")
			{
?>
											"  <line toolText='Minimum = <?= $sTypes[$i][$j][1] ?>' startValue='<?= $sTypes[$i][$j][1] ?>' displayValue='Min' color='1661f8' thickness='2' />" +
<?
			}

			if ($sTypes[$i][$j][2] != "")
			{
?>
											"  <line toolText='Standard = <?= $sTypes[$i][$j][2] ?>' startValue='<?= $sTypes[$i][$j][2] ?>' displayValue='Standard' color='12d41b' thickness='2' valueOnRight='1' />" +
<?
			}

			if ($sTypes[$i][$j][3] != "")
			{
?>
											"  <line toolText='Maximum = <?= $sTypes[$i][$j][3] ?>' startValue='<?= $sTypes[$i][$j][3] ?>' displayValue='Max' color='e20c16' thickness='2' />" +
<?
			}
?>
											"</trendlines>" +

										    "</chart>");

						objChart.render("Chart<?= $i ?>_<?= $j ?>");
					-->
					</script>
<?
		}
?>
				  </div>
<?
	}
?>
			    </div>
