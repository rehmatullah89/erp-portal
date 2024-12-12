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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Vendor     = 13;
	$Unit       = 259;
	$Floor      = IO::intValue("Floor");
	$AuditStage = IO::strValue("AuditStage");
	$Report     = IO::strValue("Report");
	$Date       = IO::strValue("Date");

	if ($Date == "")
		$Date = date("Y-m-d");


	$sDefectColors = getList("tbl_defect_types", "id", "color");
	$sReportsList  = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sQmipReports')");
	$sFloorsList   = getList("tbl_floors", "id", "floor", "vendor_id='$Vendor' AND unit_id='$Unit'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
  <script type="text/javascript" src="scripts/fusion-charts/FusionChartsExportComponent.js"></script>

  <meta http-equiv="refresh" content="120" />
</head>

<body style="margin:0px; background:#ffffff;">

<form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;" style="margin:10px;">
<div id="SearchBar">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	  <td width="40">Date</td>
	  <td width="78"><input type="text" name="Date" value="<?= $Date ?>" id="Date" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
	  <td width="50"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
	  <td width="45">Floor</td>

	  <td width="120">
		<select name="Floor">
		  <option value="">All Floors</option>
<?
	foreach ($sFloorsList as $sKey => $sValue)
	{
?>
		<option value="<?= $sKey ?>"<?= (($sKey == $Floor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
		</select>
	  </td>
	  <td width="50">Report</td>

	  <td width="250">
		<select name="Report">
		  <option value="">All Reports</option>
<?
	foreach ($sReportsList as $sKey => $sValue)
	{
?>
		<option value="<?= $sKey ?>"<?= (($sKey == $Report) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
		</select>
	  </td>

	  <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
	</tr>
  </table>
</div>
</form>


<div style="background:#cccccc; padding:15px;">
  <table border="0" cellspacing="0" cellpadding="10" width="100%">
    <tr>
      <td width="20%" style="font-size:21px;">Date:<br /><b><?= formatDate($Date) ?></b></td>
      <td width="20%" style="font-size:21px;">Vendor:<br /><b><?= getDbValue("vendor", "tbl_vendors", "id='$Vendor'") ?></b></td>
      <td width="20%" style="font-size:18px;">Unit:<br /><b><?= getDbValue("vendor", "tbl_vendors", "id='$Unit'") ?></b></td>
      <td width="20%" style="font-size:18px;"><?= (($Floor > 0) ? 'Floor:<br />' : '') ?><b><?= getDbValue("floor", "tbl_floors", "id='$Floor'") ?></b></td>
      <td width="20%" style="font-size:18px;"><?= (($Report > 0) ? 'Report Type:<br />' : '') ?><b><?= getDbValue("report", "tbl_reports", "id='$Report'") ?></b></td>
    </tr>
  </table>
</div>


<div>
  <table border="0" cellspacing="0" cellpadding="10" width="100%">
    <tr valign="top">
      <td width="100%">
<?
	$sVendorBrands = getDbValue("GROUP_CONCAT(DISTINCT(brand_id) SEPARATOR ',')", "tbl_po", "vendor_id='$Vendor'");

	$sConditions  = " AND qa.audit_type='B' AND qa.vendor_id='$Vendor' ";
	$sConditions .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '$sVendorBrands')))";
	$sConditions .= " AND FIND_IN_SET(qa.report_id, '$sQmipReports') ";

	if ($AuditStage != "")
		$sConditions .= " AND qa.audit_stage='$AuditStage' ";

	if ($Brand > 0)
		$sConditions .= " AND (qa.brand_id='$Brand' OR qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id='$Brand' AND vendor_id='$Vendor')) ";

	if ($Report > 0)
		$sConditions .= " AND qa.report_id='$Report' ";

	if ($Unit > 0)
		$sConditions .= " AND qa.unit_id='$Unit' ";

	if ($Floor > 0)
		$sConditions .= " AND qa.line_id IN (SELECT id FROM tbl_lines WHERE vendor_id='$Vendor' AND unit_id='$Unit' AND floor_id='$Floor') ";



	$sFromDate = date("Y-m-d", (strtotime($Date) - (16 * 86400)));
	$sToDate   = date("Y-m-d", (strtotime($Date) - 86400));


	$sSQL = "SELECT COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects
			 FROM tbl_qa_reports qa
			 WHERE (qa.audit_date BETWEEN '$sFromDate' AND '$sToDate') $sConditions
			 GROUP BY qa.audit_date";
	$objDb->query($sSQL);

	$iCount        = $objDb->getCount( );
	$fMinDr        = 0;
	$fMaxDr        = 0;
	$iTotalGmts    = 0;
	$iTotalDefects = 0;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");

		$iTotalGmts    += $iGmts;
		$iTotalDefects += $iDefects;
		$fDr            = @round((($iDefects / $iGmts) * 100), 2);

		if ($i == 0)
		{
			$fMinDr = $fDr;
			$fMaxDr = $fDr;
		}

		else
		{
			if (($fDr < $fMinDr && $fMinDr > 0) || ($fMinDr == 0 && $fDr > 0))
				$fMinDr = $fDr;

			if ($fDr > $fMaxDr)
				$fMaxDr = $fDr;
		}
	}


	$fAvgDr = @round((($iTotalDefects / $iTotalGmts) * 100), 2);


	$sLines        = array( );
	$sStats        = array( );
	$iTotalGmts    = 0;
	$iTotalDefects = 0;


	$sSQL = "SELECT qa.line_id,
	                l.line AS _Line
			 FROM tbl_qa_reports qa, tbl_lines l
			 WHERE qa.line_id=l.id AND qa.audit_date='$Date' $sConditions
			 GROUP BY qa.line_id
	         ORDER BY _Line";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iLine = $objDb->getField($i, "line_id");
		$sLine = $objDb->getField($i, "_Line");

		$sLines[$iLine] = $sLine;


		for ($j = 0; $j < 24; $j ++)
		{
			$sStats[$iLine][$j]['Samples'] = 0;
			$sStats[$iLine][$j]['Defects'] = 0;
			$sStats[$iLine][$j]['Dr']      = 0;
		}



		$sSQL = "SELECT HOUR(TIME(qap.date_time)) AS _Hour,
						COUNT(1) AS _Samples
				 FROM tbl_qa_reports qa, tbl_qa_report_progress qap
				 WHERE qa.id=qap.audit_id AND qa.line_id='$iLine' AND qa.audit_date='$Date' $sConditions
				 GROUP BY _Hour";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iHour    = $objDb2->getField($j, "_Hour");
			$iSamples = $objDb2->getField($j, "_Samples");

			$sStats[$iLine][$iHour]['Samples'] = $iSamples;

			$iTotalGmts += $iSamples;
		}



		$sSQL = "SELECT HOUR(TIME(qad.date_time)) AS _Hour,
						COUNT(1) AS _Defects
				 FROM tbl_qa_reports qa, tbl_qa_report_defects qad
				 WHERE qa.id=qad.audit_id AND qa.line_id='$iLine' AND qa.audit_date='$Date' $sConditions
				 GROUP BY _Hour";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iHour    = $objDb2->getField($j, "_Hour");
			$iDefects = $objDb2->getField($j, "_Defects");


			$sStats[$iLine][$iHour]['Defects'] = $iDefects;

			$iTotalDefects += $iDefects;
		}
	}


	$fDr = @round((($iTotalDefects / $iTotalGmts) * 100), 2);
?>
					<div class="tblSheet" id="ActivityGraphDiv" style="margin-top:5px;">
					  <div id="ActivityChart">loading...</div>
					</div>


					<script type="text/javascript">
					<!--
					var objChart = new FusionCharts("scripts/fusion-charts/charts/MSLine.swf", "Activities", "100%", "600", "0", "1");

					objChart.setXMLData("<chart caption='Daily Activity Chart (<?= formatDate($Date) ?>)' subCaption='Total Garments Inspected: <?= formatNumber($iTotalGmts, false) ?>   -   Total Garments Rejected: <?= formatNumber($iTotalDefects, false) ?>   -   DR: <?= formatNumber($fDr) ?>%' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fUnitDr) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='line-wise-health'>" +
										"<categories>" +
<?
	for ($i = 8; $i < 24; $i ++)
	{
?>
								"<category label='<?= $i ?>-<?= (($i == 23) ? '0' : ($i + 1)) ?>' />" +
<?
	}
?>
								"</categories>" +

								"<trendlines>" +
								"<line startvalue='<?= $fMinDr ?>' endValue='<?= $fMaxDr ?>' displayValue=' ' color='BC9F3F' isTrendZone='1' showOnTop='0' alpha='25' valueOnRight='1' />" +
								"<line startvalue='<?= $fMinDr ?>' endValue='<?= $fMinDr ?>' displayValue='Min DR' color='894D1B' isTrendZone='0' showOnTop='1' alpha='50' valueOnRight='1' />" +
								"<line startvalue='<?= $fMaxDr ?>' endValue='<?= $fMaxDr ?>' displayValue='Max DR' color='894D1B' isTrendZone='0' showOnTop='1' alpha='50' valueOnRight='1' />" +
								"<line startvalue='<?= $fAvgDr ?>' endValue='<?= $fAvgDr ?>' displayValue='Avg DR' color='0000FF' isTrendZone='0' showOnTop='1' alpha='50' valueOnRight='1' />" +
								"<line startvalue='<?= $fDr ?>' endValue='<?= $fDr ?>' displayValue='DR' color='FF0000' isTrendZone='0' showOnTop='1' alpha='50' valueOnRight='0' />" +
								"</trendlines>" +

<?
	foreach ($sLines as $iLine => $sLine)
	{
		$sParams  = $sQueryString;
		$sParams .= "&Line={$iLine}";
?>
								"<dataset seriesName='<?= $sLine ?>'>" +
<?
		for ($i = 8; $i < 24; $i ++)
		{
			$fDr = @round((($sStats[$iLine][$i]['Defects'] / $sStats[$iLine][$i]['Samples']) * 100), 2);
?>
								"<set value='<?= (($fDr > 0 && $sStats[$iLine][$i]['Samples'] >= 20) ? formatNumber($fDr) : '') ?>' tooltext='Line: <?= $sLine ?>{br}Samples: <?= $sStats[$iLine][$i]['Samples'] ?>{br}Defects: <?= $sStats[$iLine][$i]['Defects'] ?>{br}DR: <?= formatNumber($fDr) ?>%' link='' />" +
<?
		}
?>
								"</dataset>" +
<?
	}
?>
								"</chart>");


					objChart.render("ActivityChart");
					-->
					</script>
      </td>
    </tr>
  </table>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>