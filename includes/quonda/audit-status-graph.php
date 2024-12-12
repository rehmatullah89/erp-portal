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

	$iPass    = array( );
	$iFail    = array( );
	$iHold    = array( );
	$sVendors = array( );
	$iVendors = array( );

	$sSQL = "SELECT po.vendor_id, qa.audit_result,
					COUNT(DISTINCT(qa.id)) AS _TotalAudits
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' $sAuditorSQL
			       AND FIND_IN_SET(qa.report_id, '$sReportTypes')";

	$sSQL .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

	if ($FromDate != "" && $ToDate != "")
		$sSQL .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($AuditCode != "")
		$sSQL .= " AND qa.audit_code LIKE '%$AuditCode%' ";

	if ($OrderNo != "")
		$sSQL .= " AND po.order_no LIKE '%$OrderNo%' ";

	if ($StyleNo != "")
	{
		$sSQL .= " AND (";

		$sSubSQL = "SELECT id FROM tbl_styles WHERE style LIKE '%$StyleNo%'";
		$objDb->query($sSubSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iStyleId = $objDb->getField($i, 0);

			if ($i > 0)
				$sSQL .= " OR ";

			$sSQL .= " qa.style_id='$iStyleId' ";
		}

		$sSQL .= ") ";
	}

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($AuditStage != "''" && $AuditStage != "")
		$sSQL .= " AND qa.audit_stage IN ($AuditStage) ";

	else
		$sSQL .= " AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

	$sSQL .= "GROUP BY po.vendor_id, qa.audit_result
	          ORDER BY po.vendor_id";

	$objDb->query($sSQL);


	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iVendorId    = $objDb->getField($i, "vendor_id");
		$sAuditResult = $objDb->getField($i, "audit_result");
		$iTotalAudits = $objDb->getField($i, "_TotalAudits");

		if (!@in_array($iVendorId, $iVendors))
		{
			$sVendors[] = $sVendorsList[$iVendorId];
			$iVendors[] = $iVendorId;

			$iPass[] = 0;
			$iFail[] = 0;
			$iHold[] = 0;
		}


		$iIndex = @array_search($iVendorId, $iVendors);

		switch ($sAuditResult)
		{
			case "P" : $iPass[$iIndex] = $iTotalAudits;  break;
			case "F" : $iFail[$iIndex] = $iTotalAudits;  break;
			case "H" : $iHold[$iIndex] = $iTotalAudits;  break;
		}
	}
?>

			    <br />

			    <div class="tblSheet">
				  <div id="AuditStatusChart">loading...</div>

				  <script type="text/javascript">
				  <!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/StackedColumn3D.swf", "AuditStatus", "920", "500", "0", "1");

						objChart.setXMLData("<chart caption='<?= ("{$sStage} Audits from ".formatDate($FromDate)." to ".formatDate($ToDate)) ?>' numDivLines='10' formatNumberScale='0' showValues='0' showSum='1' showLabels='1' decimals='1' numberSuffix='' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='ROTATE' slantLabels='1' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='status-wise-graph'>" +

											"<categories>" +
<?
	for ($i = 0; $i < count($iVendors); $i ++)
	{
?>
											"<category label='<?= $sVendorsList[$iVendors[$i]] ?>' />" +
<?
	}
?>
											"</categories>" +


											"<dataset seriesName='Pass' color='7fff7f'>" +
<?
	for ($i = 0; $i < count($iVendors); $i ++)
	{
?>
											"<set value='<?= $iPass[$i] ?>' link='<?= SITE_URL ?>quonda/quonda-graphs.php?Vendor=<?= $iVendors[$i] ?>&OrderNo=<?= $OrderNo ?>&StyleNo=<?= $StyleNo ?>&AuditCode=<?= $AuditCode ?>&Brand=<?= $Brand ?>&FromDate=<?= $FromDate ?>&ToDate=<?= $ToDate ?>&Category=<?= $iCategories[$i] ?>&Step=1' />" +
<?
	}
?>
											"</dataset>" +

											"<dataset seriesName='Fail' color='ff0000'>" +
<?
	for ($i = 0; $i < count($iVendors); $i ++)
	{
?>
											"<set value='<?= $iFail[$i] ?>' link='<?= SITE_URL ?>quonda/quonda-graphs.php?Vendor=<?= $iVendors[$i] ?>&OrderNo=<?= $OrderNo ?>&StyleNo=<?= $StyleNo ?>&AuditCode=<?= $AuditCode ?>&Brand=<?= $Brand ?>&FromDate=<?= $FromDate ?>&ToDate=<?= $ToDate ?>&Category=<?= $iCategories[$i] ?>&Step=1' />" +
<?
	}
?>
											"</dataset>" +

											"<dataset seriesName='Hold' color='fcbf04'>" +
<?
	for ($i = 0; $i < count($iVendors); $i ++)
	{
?>
											"<set value='<?= $iHold[$i] ?>' link='<?= SITE_URL ?>quonda/quonda-graphs.php?Vendor=<?= $iVendors[$i] ?>&OrderNo=<?= $OrderNo ?>&StyleNo=<?= $StyleNo ?>&AuditCode=<?= $AuditCode ?>&Brand=<?= $Brand ?>&FromDate=<?= $FromDate ?>&ToDate=<?= $ToDate ?>&Category=<?= $iCategories[$i] ?>&Step=1' />" +
<?
	}
?>
											"</dataset>" +

										"</chart>");


						objChart.render("AuditStatusChart");
				  -->
				  </script>

				  <br />
			    </div>
