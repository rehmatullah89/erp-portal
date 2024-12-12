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

	$sData      = array( );
	$sDates     = array( );
	$sBrandsSQL = "";

	if (count($Brand) > 0)
		$sBrandsSQL = " AND po.brand_id IN (".@implode(",", $Brand).") AND po.vendor_id='$Vendor' ";


	$sSQL = "SELECT DATE_FORMAT(qa.audit_date, '%d-%b-%Y') AS _Day,
					qa.audit_stage AS _Stage,
					COALESCE(SUM(qa.total_gmts), 0) AS _TotalGmts,

					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature>'0')) AS _TotalDefects
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') AND qa.vendor_id='$Vendor'
			       AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages')
			       $sBrandsSQL $sAuditorSQL
			 GROUP BY qa.audit_stage, _Day
			 ORDER BY qa.audit_date, qa.audit_stage";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount == 0)
	{
		$sSQL = "SELECT DATE_FORMAT(qa.audit_date, '%d-%b-%Y') AS _Day,
		                qa.audit_stage AS _Stage,
		                ROUND(AVG(qa.dhu), 2) AS _Dhu
				 FROM tbl_po po, tbl_qa_reports qa
				 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') AND qa.vendor_id='$Vendor'
				       AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages')
				       $sBrandsSQL
				 GROUP BY qa.audit_stage, _Day
				 ORDER BY qa.audit_date, qa.audit_stage";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 0)
		{
			for ($i = 0; $i < $iCount; $i ++)
			{
				$sDay   = $objDb->getField($i, "_Day");
				$sStage = $objDb->getField($i, "_Stage");
				$fDhu   = $objDb->getField($i, "_Dhu");

				if (!@in_array($sDay, $sDates))
					$sDates[] = $sDay;

				$iIndex = @array_search($sDay, $sDates);

				$sData[$sStage][$iIndex] = $fDhu;
			}
?>
				  <h2>Average Points Analysis<br />From: <?= formatDate($FromDate) ?>  &nbsp; To:  <?= formatDate($ToDate) ?></h2>
<?
		}
	}

	else
	{
		for ($i = 0; $i < $iCount; $i ++)
		{
			$sDay     = $objDb->getField($i, "_Day");
			$sStage   = $objDb->getField($i, "_Stage");
			$iGmts    = $objDb->getField($i, "_TotalGmts");
			$iDefects = $objDb->getField($i, "_TotalDefects");

			if (!@in_array($sDay, $sDates))
				$sDates[] = $sDay;

			$iIndex = @array_search($sDay, $sDates);

			$sData[$sStage][$iIndex] = @round((($iDefects / $iGmts) * 100), 2);
		}
?>
				  <h2>Defect Rate Analysis<br />From: <?= formatDate($FromDate) ?>  &nbsp; To:  <?= formatDate($ToDate) ?></h2>
<?
	}


	if ($iCount > 0)
	{
?>

				  <div class="tblSheet">
						<div id="DefectRate">loading...</div>
				  </div>

				  <br />

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/MSLine.swf", "DefectRateGraph", "100%", "420", "0", "1");

						objChart.setXMLData("<chart caption='' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fLineDhu) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='dr-audit-stage'>" +

                                                        "<categories>" +
<?
		for ($i = 0; $i < count($sDates); $i ++)
		{
?>
                                                        "<category label='<?= $sDates[$i] ?>' />" +
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
			for ($i = 0; $i < count($sDates); $i ++)
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


						objChart.render("DefectRate");
						-->
						</script>
<?
	}
?>