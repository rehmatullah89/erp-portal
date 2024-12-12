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

	if ($Date != "")
		$sConditions .= " AND qa.audit_date='$Date' ";



	$sSQL = "SELECT qa.line_id,
	                l.line AS _Line
			 FROM tbl_qa_reports qa, tbl_lines l
			 WHERE qa.line_id=l.id AND qa.audit_date='$Date' $sConditions
			 GROUP BY qa.line_id
	         ORDER BY _Line";
	$objDb->query($sSQL);

	$iLines = $objDb->getCount( );

	for ($iIndex = 0; $iIndex < $iLines; $iIndex ++)
	{
		$iLine = $objDb->getField($iIndex, "line_id");
		$sLine = $objDb->getField($iIndex, "_Line");
?>
      <td width="<?= @round(100 / $iLines) ?>%">
        <h2><?= $sLine ?></h2>


<?
		$iTotalGmts = getDbValue("COUNT(1)", "tbl_qa_reports qa, tbl_qa_report_progress qap", "qa.id=qap.audit_id AND qa.line_id='$iLine' AND qa.audit_date='$Date' $sConditions");


		$sDefectTypes  = array( );
		$iDefectTypes  = array( );
		$iTotalDefects = array( );

		$sSQL = "SELECT dt.id, dt.type AS _DefectType, COALESCE(SUM(qad.defects), 0) AS _Defects
				 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
				 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id
					   AND IF(qa.report_id=10, qad.nature='1', TRUE)
					   $sConditions AND qa.line_id='$iLine'
				 GROUP BY dc.type_id
				 ORDER BY _Defects DESC, _DefectType ASC";
		$objDb2->query($sSQL);

		$iCount = $objDb2->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iDefectType = $objDb2->getField($i, "id");
			$sDefectType = $objDb2->getField($i, "_DefectType");
			$iDefects    = $objDb2->getField($i, "_Defects");

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


		$fDr = @round(((@array_sum($iTotalDefects) / $iTotalGmts) * 100), 2);
?>
        <div class="tblSheet">
		  <div id="DefectsChart<?= $iLine ?>">loading...</div>
		</div>

		<script type="text/javascript">
		<!--
		var objChart = new FusionCharts("scripts/fusion-charts/charts/Pie2D.swf", "Areas<?= $iLine ?>", "100%", "500", "0", "1");

		objChart.setXMLData("<chart caption='Defect Types' subCaption='TGI: <?= formatNumber($iTotalGmts, false) ?>   -   TGR: <?= formatNumber(@array_sum($iTotalDefects), false) ?>   -   DR: <?= formatNumber($fDr) ?>%' showPercentageInLabel='1' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' bgcolor='ffffff' bordercolor='ffffff' animation='1' labelDisplay='WRAP' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='defect-classification'>" +
<?
		$iTotal = @array_sum($iTotalDefects);

		for ($i = 0; $i < count($iDefectTypes); $i ++)
		{
			$fPercent = @round((($iTotalDefects[$i] / $iTotal) * 100), 2);
?>
						"<set color='<?= $sDefectColors[$iDefectTypes[$i]] ?>' tooltext='Type: <?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?>{br}Defects: <?= $iTotalDefects[$i] ?> (<?= formatNumber($fPercent) ?>%)' label='<?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?> [<?= $iTotalDefects[$i] ?>]' value='<?= $iTotalDefects[$i] ?>' link='' />" +
<?
		}
?>
					"</chart>");


			objChart.render("DefectsChart<?= $iLine ?>");
		-->
		</script>

		<br />

<?
		$sDefectAreas  = array( );
		$iDefectAreas  = array( );
		$iTotalDefects = array( );

		$sSQL = "SELECT da.id, da.area AS _DefectArea, COALESCE(SUM(qad.defects), 0) AS _Defects
				 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt, tbl_defect_areas da
				 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.report_id=qa.report_id AND dc.type_id=dt.id AND da.id=qad.area_id
					   AND IF(qa.report_id=10, qad.nature='1', TRUE)
					   $sConditions AND qa.line_id='$iLine'
				 GROUP BY da.id
				 ORDER BY _Defects DESC, _DefectArea ASC";
		$objDb2->query($sSQL);

		$iCount = $objDb2->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iDefectArea = $objDb2->getField($i, "id");
			$sDefectArea = $objDb2->getField($i, "_DefectArea");
			$iDefects    = $objDb2->getField($i, "_Defects");

			if (@in_array($iDefectArea, $iDefectAreas))
			{
				$iIndex = @array_search($iDefectArea, $iDefectAreas);

				$iTotalDefects[$iIndex] += $iDefects;
			}

			else
			{
				$iDefectAreas[]  = $iDefectArea;
				$sDefectAreas[]  = $sDefectArea;
				$iTotalDefects[] = $iDefects;
			}
		}
?>
        <div class="tblSheet">
		  <div id="AreasChart<?= $iLine ?>">loading...</div>
		</div>

		<script type="text/javascript">
		<!--
		var objChart = new FusionCharts("scripts/fusion-charts/charts/Pie2D.swf", "Defects<?= $iLine ?>", "100%", "500", "0", "1");

		objChart.setXMLData("<chart caption='Defect Areas'  subCaption='TGI: <?= formatNumber($iTotalGmts, false) ?>   -   TGR: <?= formatNumber(@array_sum($iTotalDefects), false) ?>   -   DR: <?= formatNumber($fDr) ?>%' showPercentageInLabel='1' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' bgcolor='ffffff' bordercolor='ffffff' animation='1' labelDisplay='WRAP' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='defect-areas'>" +
<?
		$iTotal = @array_sum($iTotalDefects);

		for ($i = 0; $i < count($iDefectTypes); $i ++)
		{
			$fPercent = @round((($iTotalDefects[$i] / $iTotal) * 100), 2);
?>
						"<set color='<?= $sDefectColors[$iDefectAreas[$i]] ?>' tooltext='Area: <?= htmlentities($sDefectAreas[$i], ENT_QUOTES) ?>{br}Defects: <?= $iTotalDefects[$i] ?> (<?= formatNumber($fPercent) ?>%)' label='<?= htmlentities($sDefectAreas[$i], ENT_QUOTES) ?> [<?= $iTotalDefects[$i] ?>]' value='<?= $iTotalDefects[$i] ?>' />" +
<?
		}
?>
					"</chart>");


			objChart.render("AreasChart<?= $iLine ?>");
		-->
		</script>

		<br />
		<h2>Total Garments Inspected: <?= formatNumber($iTotalGmts, false) ?></h2>
		<h2>Total Garments Rejected: <?= formatNumber($iTotal, false) ?></h2>
		<h2>DR: <?= formatNumber($fDr) ?>%</h2>
      </td>
<?
	}


	if ($iLines == 0)
	{
?>
      <td>
	    <div style="font-size:24px; padding:40px; border:solid 2px #aaaaaa;">No Line Found!</div>
      </td>
<?
	}
?>
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