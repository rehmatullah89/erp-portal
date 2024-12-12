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

	$fCvCount    = array( );
	$fClsp       = array( );
	$fCvStrength = array( );
	$fUcvm       = array( );
	$fHairiness  = array( );
	$fIpiValue   = array( );
	$sDates      = array( );

	$sSQL = "SELECT qa.audit_date AS _Date,
	                AVG(CAST(ypc.cv_count_p AS DECIMAL(5,3))) AS _CvCount,
	                AVG(CAST(ypc.clsp_p AS UNSIGNED)) AS _Clsp,
	                AVG(CAST(ypc.cv_strength_p AS DECIMAL(5,3))) AS _CvStrength,
	                AVG(CAST(ypc.ucvm_p AS DECIMAL(5,3))) AS _Ucvm,
	                AVG(CAST(ypc.hairiness_p AS DECIMAL(5,3))) AS _Hairiness,
	                AVG(CAST(ypc.ipi_value_p AS UNSIGNED)) AS _IpiValue
			 FROM tbl_po po, tbl_qa_reports qa, tbl_yarn_product_checks ypc
			 WHERE po.id=qa.po_id AND qa.id=ypc.audit_id AND qa.audit_type='B' AND qa.audit_result!='' AND qa.report_id='9' AND
			       (qa.audit_date BETWEEN '$FromDate' AND '$ToDate')";

	if ($AuditCode != "")
		$sSQL .= " AND qa.audit_code LIKE '%$AuditCode%' ";

	if ($sAuditStage != "")
		$sSQL .= " AND qa.audit_stage='$sAuditStage' ";

	if ($OrderNo != "")
	{
		$sSQL .= " AND (";

		$sSubSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '%$OrderNo%'";
		$objDb->query($sSubSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPoId = $objDb->getField($i, 0);

			if ($i > 0)
				$sSQL .= " OR ";

			$sSQL .= " qa.po_id='$iPoId' OR FIND_IN_SET('$iPoId', qa.additional_pos) ";
		}

		$sSQL .= ") ";
	}

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ($sUserVendors) ";

	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ($sUserBrands) ";

	$sSQL .= " GROUP BY qa.audit_date
	           ORDER BY _Date";

	$objDb->query($sSQL);


	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$fCvCount[]    = $objDb->getField($i, "_CvCount");
		$fClsp[]       = $objDb->getField($i, "_Clsp");
		$fCvStrength[] = $objDb->getField($i, "_CvStrength");
		$fUcvm[]       = $objDb->getField($i, "_Ucvm");
		$fHairiness[]  = $objDb->getField($i, "_Hairiness");
		$fIpiValue[]   = $objDb->getField($i, "_IpiValue");
		$sDates[]      = $objDb->getField($i, "_Date");
	}
?>
						<div id="YarnCountCvChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Line.swf", "YarnCountCv", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='7/1 Yarn Count CV%' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='2' numberSuffix='%' chartBottomMargin='5' rotateValues='1' valuePosition='auto' labelDisplay='ROTATE' slantLabels='1' bgcolor='ffffff' bordercolor='ffffff' animation='1' showAlternateHGridColor='1' AlternateHGridColor='ff5904' divLineColor='ff5904' divLineAlpha='20' alternateHGridAlpha='5' lineColor='FF5904' lineAlpha='85'>" +
<?
	for ($i = 0; $i < count($fCvCount); $i ++)
	{
?>
											"<set tooltext='Date: <?= $sDates[$i] ?>{br}CV Count: <?= $fCvCount[$i] ?>%' label='<?= $sDates[$i] ?>' value='<?= $fCvCount[$i] ?>' />" +
<?
	}
?>

											"<trendlines>" +
											"  <line toolText='Standard = 1%' startValue='1' displayValue='Standard' color='0000ff' />" +
											"</trendlines>" +

										    "</chart>");


						objChart.render("YarnCountCvChart");
						-->
						</script>


					    <hr />
						<div id="YarnClspChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Line.swf", "YarnClsp", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='7/1 Yarn C.L.S.P' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='0' numberSuffix='' chartBottomMargin='5' rotateValues='1' valuePosition='auto' labelDisplay='ROTATE' slantLabels='1' bgcolor='ffffff' bordercolor='ffffff' animation='1' showAlternateHGridColor='1' AlternateHGridColor='ff5904' divLineColor='ff5904' divLineAlpha='20' alternateHGridAlpha='5' lineColor='FF5904' lineAlpha='85'>" +
<?
	for ($i = 0; $i < count($fClsp); $i ++)
	{
?>
											"<set tooltext='Date: <?= $sDates[$i] ?>{br}C.L.S.P: <?= $fClsp[$i] ?>' label='<?= $sDates[$i] ?>' value='<?= $fClsp[$i] ?>' />" +
<?
	}
?>

											"<trendlines>" +
											"  <line toolText='Standard = 2516' startValue='2516' displayValue='Standard' color='0000ff' />" +
											"</trendlines>" +

										    "</chart>");


						objChart.render("YarnClspChart");
						-->
						</script>

					    <hr />
						<div id="YarnCvStrengthChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Line.swf", "YarnCvStrength", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='7/1 Yarn CV % Strength' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='2' numberSuffix='' chartBottomMargin='5' rotateValues='1' valuePosition='auto' labelDisplay='ROTATE' slantLabels='1' bgcolor='ffffff' bordercolor='ffffff' animation='1' showAlternateHGridColor='1' AlternateHGridColor='ff5904' divLineColor='ff5904' divLineAlpha='20' alternateHGridAlpha='5' lineColor='FF5904' lineAlpha='85'>" +
<?
	for ($i = 0; $i < count($fCvStrength); $i ++)
	{
?>
											"<set tooltext='Date: <?= $sDates[$i] ?>{br}CV % Strength: <?= $fCvStrength[$i] ?>' label='<?= $sDates[$i] ?>' value='<?= $fCvStrength[$i] ?>' />" +
<?
	}
?>

											"<trendlines>" +
											"  <line toolText='Standard = 5' startValue='5' displayValue='Standard' color='0000ff' />" +
											"</trendlines>" +

										    "</chart>");


						objChart.render("YarnCvStrengthChart");
						-->
						</script>

					    <hr />
						<div id="YarnUcvmChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Line.swf", "YarnUcvm", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='7/1 Yarn U %' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='2' numberSuffix='' chartBottomMargin='5' rotateValues='1' valuePosition='auto' labelDisplay='ROTATE' slantLabels='1' bgcolor='ffffff' bordercolor='ffffff' animation='1' showAlternateHGridColor='1' AlternateHGridColor='ff5904' divLineColor='ff5904' divLineAlpha='20' alternateHGridAlpha='5' lineColor='FF5904' lineAlpha='85'>" +
<?
	for ($i = 0; $i < count($fUcvm); $i ++)
	{
?>
											"<set tooltext='Date: <?= $sDates[$i] ?>{br}U %: <?= $fUcvm[$i] ?>' label='<?= $sDates[$i] ?>' value='<?= $fUcvm[$i] ?>' />" +
<?
	}
?>

											"<trendlines>" +
											"  <line toolText='Standard = 11' startValue='11' displayValue='Standard' color='0000ff' />" +
											"</trendlines>" +

										    "</chart>");


						objChart.render("YarnUcvmChart");
						-->
						</script>

					    <hr />
						<div id="YarnHairinessChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Line.swf", "YarnHairiness", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='7/1 Yarn Hairiness' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='2' numberSuffix='' chartBottomMargin='5' rotateValues='1' valuePosition='auto' labelDisplay='ROTATE' slantLabels='1' bgcolor='ffffff' bordercolor='ffffff' animation='1' showAlternateHGridColor='1' AlternateHGridColor='ff5904' divLineColor='ff5904' divLineAlpha='20' alternateHGridAlpha='5' lineColor='FF5904' lineAlpha='85'>" +
<?
	for ($i = 0; $i < count($fHairiness); $i ++)
	{
?>
											"<set tooltext='Date: <?= $sDates[$i] ?>{br}Hairiness: <?= $fHairiness[$i] ?>' label='<?= $sDates[$i] ?>' value='<?= $fHairiness[$i] ?>' />" +
<?
	}
?>

											"<trendlines>" +
											"  <line toolText='Standard = 9.5' startValue='9.5' displayValue='Standard' color='0000ff' />" +
											"</trendlines>" +

										    "</chart>");


						objChart.render("YarnHairinessChart");
						-->
						</script>

					    <hr />
						<div id="YarnIpiValueChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Line.swf", "YarnIpiValue", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='7/1 Yarn IPI' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='2' numberSuffix='' chartBottomMargin='5' rotateValues='1' valuePosition='auto' labelDisplay='ROTATE' slantLabels='1' bgcolor='ffffff' bordercolor='ffffff' animation='1' showAlternateHGridColor='1' AlternateHGridColor='ff5904' divLineColor='ff5904' divLineAlpha='20' alternateHGridAlpha='5' lineColor='FF5904' lineAlpha='85'>" +
<?
	for ($i = 0; $i < count($fIpiValue); $i ++)
	{
?>
											"<set tooltext='Date: <?= $sDates[$i] ?>{br}IPI: <?= $fIpiValue[$i] ?>' label='<?= $sDates[$i] ?>' value='<?= $fIpiValue[$i] ?>' />" +
<?
	}
?>

											"<trendlines>" +
											"  <line toolText='Standard = 581' startValue='581' displayValue='Standard' color='0000ff' />" +
											"</trendlines>" +

										    "</chart>");


						objChart.render("YarnIpiValueChart");
						-->
						</script>

				  <hr />
				  <div style="padding:0px 0px 10px 10px;"><input type="button" value=" Back " class="button" style="font-size:16px; padding:5px 10px 5px 10px;" onclick="document.location='<?= (SITE_URL."api/quonda/graphs.php?User={$User}&OrderNo={$OrderNo}&AuditCode={$AuditCode}&Brand={$Brand}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}") ?>';" /></div>
