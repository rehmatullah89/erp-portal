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

	$sData   = array( );
	$sMonths = array( );
	$sDate   = date("Y-m-d", mktime(0, 0, 0, (date("n") - 12), 1, date("Y")));


	$sSQL = "SELECT DATE_FORMAT(qa.audit_date, '%b %Y') AS _MonthYear,
					qa.audit_stage AS _Stage,
					COALESCE(SUM(qa.total_gmts), 0) AS _TotalGmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature>'0')) AS _TotalDefects
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND qa.audit_date>='$sDate' AND qa.report_id!='6' AND qa.vendor_id='$Vendor'
			       AND FIND_IN_SET(qa.audit_stage, 'B,C,O,F') AND NOT FIND_IN_SET(qa.report_id, '$sQmipReports')
			 GROUP BY qa.audit_stage, _MonthYear
			 ORDER BY qa.audit_date, qa.audit_stage";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sMonthYear = $objDb->getField($i, "_MonthYear");
		$sStage     = $objDb->getField($i, "_Stage");
		$iGmts      = $objDb->getField($i, "_TotalGmts");
		$iDefects   = $objDb->getField($i, "_TotalDefects");

		if (!@in_array($sMonthYear, $sMonths))
			$sMonths[] = $sMonthYear;

		$iIndex = @array_search($sMonthYear, $sMonths);

		$sData[$sStage][$iIndex] = @round((($iDefects / $iGmts) * 100), 2);
	}
?>
						<div id="DefectRateYearly">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/MSLine.swf", "VendorLines<?= $iUnit ?><?= $iShift ?>", "100%", "420", "0", "1");

						objChart.setXMLData("<chart caption='' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fLineDhu) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='dr-audit-stage'>" +

                                                        "<categories>" +
<?
	for ($i = 0; $i < count($sMonths); $i ++)
	{
?>
                                                        "<category label='<?= $sMonths[$i] ?>' />" +
<?
	}
?>
                                                        "</categories>" +
<?
	foreach ($sData as $sStage => $sDhu)
	{
?>
                                                        "<dataset seriesName='<?= $sAuditStagesList[$sStage] ?>' color='<?= $sStageColorsList[$sStage] ?>'>" +
<?
		for ($i = 0; $i < count($sMonths); $i ++)
		{
?>
                                                        "<set value='<?= formatNumber($sDhu[$i]) ?>' />" +
<?
		}
?>
                                                        "</dataset>" +
<?
	}
?>
										    "</chart>");


						objChart.render("DefectRateYearly");
						-->
						</script>
