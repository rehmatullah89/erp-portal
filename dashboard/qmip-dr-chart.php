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
	$Type       = IO::strValue("Type");

	if ($Type == "")
		$Type = "Daily";


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
<input type="hidden" name="Type" value="<?= $Type ?>" />
<div id="SearchBar">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
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
      <td width="20%" style="font-size:21px;">Type:<br /><b><?= $Type ?></b></td>
      <td width="20%" style="font-size:21px;">Vendor:<br /><b><?= getDbValue("vendor", "tbl_vendors", "id='$Vendor'") ?></b></td>
      <td width="20%" style="font-size:18px;">Unit:<br /><b><?= getDbValue("vendor", "tbl_vendors", "id='$Unit'") ?></b></td>
      <td width="20%" style="font-size:18px;"><?= (($Floor > 0) ? 'Floor:<br />' : '') ?><b><?= getDbValue("floor", "tbl_floors", "id='$Floor'") ?></b></td>
      <td width="20%" style="font-size:18px;"><?= (($Report > 0) ? 'Report Type:<br />' : '') ?><b><?= getDbValue("report", "tbl_reports", "id='$Report'") ?></b></td>
    </tr>
  </table>
</div>

<div style="padding:10px 10px 0px 10px;">
  <input type="button" class="button" style="font-size:16px; padding:10px; margin-right:10px;" value=" Hourly Chart " onclick="document.location='<?= $_SERVER['PHP_SELF'] ?>?Type=Hourly&Floor=<?= $Floor ?>&Report=<? $Report ?>'" />
  <input type="button" class="button" style="font-size:16px; padding:10px; margin-right:10px;" value=" Daily Chart " onclick="document.location='<?= $_SERVER['PHP_SELF'] ?>?Type=Daily&Floor=<?= $Floor ?>&Report=<? $Report ?>'" />
  <input type="button" class="button" style="font-size:16px; padding:10px; margin-right:10px;" value=" Weekly Chart " onclick="document.location='<?= $_SERVER['PHP_SELF'] ?>?Type=Weekly&Floor=<?= $Floor ?>&Report=<? $Report ?>'" />
  <input type="button" class="button" style="font-size:16px; padding:10px; margin-right:10px;" value=" Monthly Chart " onclick="document.location='<?= $_SERVER['PHP_SELF'] ?>?Type=Monthly&Floor=<?= $Floor ?>&Report=<? $Report ?>'" />
</div>

<div>
  <table border="0" cellspacing="0" cellpadding="10" width="100%">
    <tr valign="top">
      <td width="100%">
<?
	$sFromDate = date("Y-m-d");
	$sToDate   = date("Y-m-d");
	$iHour     = 0;

	if ($Type == "Hourly")
		$iHour = (date("H") - 2);

	else if ($Type == "Weekly")
		$sFromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 7), date("Y")));

	else if ($Type == "Monthly")
		$sFromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 30), date("Y")));


	$sVendorBrands = getDbValue("GROUP_CONCAT(DISTINCT(brand_id) SEPARATOR ',')", "tbl_po", "vendor_id='$Vendor'");

	$sConditions  = " AND qa.audit_type='B' AND qa.vendor_id='$Vendor' ";
	$sConditions .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '$sVendorBrands')))";
	$sConditions .= " AND FIND_IN_SET(qa.report_id, '$sQmipReports') ";
	$sConditions .= " AND (qa.audit_date BETWEEN '$sFromDate' AND '$sToDate') ";

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




	$sLines        = array( );
	$fLineDr       = array( );
	$iLineGmts     = array( );
	$iLineDefects  = array( );
	$iTotalGmts    = 0;
	$iTotalDefects = 0;
	$sHourlySQL    = "";


	if ($Type == "Hourly")
		$sHourlySQL = " AND HOUR(TIME(date_time))='$iHour' ";



	$sSQL = "SELECT l.line AS _Line,
					SUM((SELECT COUNT(1) FROM tbl_qa_report_progress WHERE audit_id=qa.id $sHourlySQL)) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id $sHourlySQL)) AS _Defects
			 FROM tbl_qa_reports qa, tbl_lines l
			 WHERE qa.line_id=l.id $sConditions
			 GROUP BY qa.line_id
			 ORDER BY _Line";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sLine    = $objDb->getField($i, "_Line");
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");


		$sLines[]       = $sLine;
		$iLineGmts[]    = $iGmts;
		$iLineDefects[] = $iDefects;
		$fLineDr[]      = @round((($iDefects / $iGmts) * 100), 2);

		$iTotalGmts    += $iGmts;
		$iTotalDefects += $iDefects;
	}


	$fAvgDr = @round((($iTotalDefects / $iTotalGmts) * 100), 2);
?>
					<div class="tblSheet" id="DrGraphDiv" style="margin-top:5px;">
					  <div id="DrChart">loading...</div>
					</div>


					<script type="text/javascript">
					<!--
					var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "Activities", "100%", "600", "0", "1");

					objChart.setXMLData("<chart caption='Defect Rate Chart (<?= $Type ?>)' subCaption='Total Garments Inspected: <?= formatNumber($iTotalGmts, false) ?>   -   Total Garments Rejected: <?= formatNumber($iTotalDefects, false) ?>   -   DR: <?= formatNumber($fAvgDr) ?>%' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fUnitDr) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='line-wise-health'>" +
<?
	for ($i = 0; $i < count($fLineDr); $i ++)
	{
?>
								"<set tooltext='Line: <?= str_replace("\n", "", $sLines[$i]) ?>{br}DR: <?= formatNumber($fLineDr[$i]) ?>%{br}TGI: <?= formatNumber($iLineGmts[$i], false) ?>{br}TGR: <?= formatNumber($iLineDefects[$i], false) ?>' label='<?= str_replace("\n", "", $sLines[$i]) ?>' value='<?= $fLineDr[$i] ?>' link='' />" +
<?
	}
?>

								"<trendlines>" +
								"  <line toolText='Average Line (<?= $fAvgDr ?>%)' startValue='<?= $fAvgDr ?>' displayValue='Average' color='0000ff' />" +
								"</trendlines>" +

								"</chart>");


					objChart.render("DrChart");
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