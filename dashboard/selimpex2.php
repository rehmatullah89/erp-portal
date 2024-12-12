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
	$Brand      = IO::getArray("Brand");
	$AuditStage = IO::getArray("AuditStage");
	$Report     = IO::getArray("Report");
	$Line       = IO::getArray("Line");
	$Unit       = IO::intValue("Unit");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");

	if ($FromDate == "")
		$FromDate = date("Y-m-d");

	if ($ToDate == "")
		$ToDate = date("Y-m-d");


	$sBrands  = getDbValue("GROUP_CONCAT(DISTINCT(brand_id) SEPARATOR ',')", "tbl_po", "vendor_id='$Vendor'");
	$sReports = getDbValue("GROUP_CONCAT(DISTINCT(report_id) SEPARATOR ',')", "tbl_qa_reports", "vendor_id='$Vendor'");
	$sStages  = getDbValue("GROUP_CONCAT(DISTINCT(audit_stage) SEPARATOR ',')", "tbl_qa_reports", "vendor_id='$Vendor'");
	$sLines   = getDbValue("GROUP_CONCAT(DISTINCT(line_id) SEPARATOR ',')", "tbl_qa_reports", "vendor_id='$Vendor'");

	$sUnitsList       = getList("tbl_vendors", "id", "vendor", "parent_id='$Vendor' AND sourcing='Y'");
	$sBrandsList      = getList("tbl_brands", "id", "brand", "id IN ($sBrands)");
	$sReportsList     = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReports')");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sStages')");
	$sLinesList       = getList("tbl_lines", "id", "line", "FIND_IN_SET(id, '$sLines')");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
  <script type="text/javascript" src="scripts/fusion-charts/FusionChartsExportComponent.js"></script>
  <script type="text/javascript" src="scripts/glider.js"></script>

  <script type="text/javascript">
  <!--
	function selectAll(sList)
	{
		var iLength = $(sList).length;

		for (var i = 0; i < iLength; i++ )
			$(sList).options[i].selected = true;
	}


	function clearAll(sList)
	{
		var iLength = $(sList).length;

		for (var i = 0; i < iLength; i++ )
			$(sList).options[i].selected = false;

		$(sList).selectedIndex = -1;
	}
  -->
  </script>
</head>

<body style="margin:0px; background:#ffffff;">

<form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;" style="margin:10px;">
<div id="SearchBar">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
  	  <td width="40">Unit</td>

	  <td width="210">
		<select name="Unit" style="width:190px;">
		  <option value="">All Units</option>
<?
	foreach ($sUnitsList as $sKey => $sValue)
	{
?>
		  <option value="<?= $sKey ?>"<?= (($sKey == $Unit) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
		</select>
	  </td>

	  <td width="40">From</td>
	  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
	  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
	  <td width="40" align="center">To</td>
	  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
	  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
	  <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
	</tr>
  </table>
</div>

<div id="SearchBar" style="height:auto; margin-top:10px; padding-top:10px; padding-bottom:10px;">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr valign="top">
	  <td width="100" style="line-height:18px;">
	    Brand(s)<br />
	    <br />
	    [ <a href="./" onclick="selectAll('Brand'); return false;">All</a> | <a href="./" onclick="clearAll('Brand'); return false;">None</a> ]<br />
	  </td>

	  <td width="210">
		<select name="Brand[]" id="Brand" style="width:190px;" multiple size="10">
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
		  <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Brand) || count($Brand) == 0) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
		</select>
	  </td>

	  <td width="100" style="line-height:18px;">
	    Stage(s)<br />
	    <br />
	    [ <a href="./" onclick="selectAll('AuditStage'); return false;">All</a> | <a href="./" onclick="clearAll('AuditStage'); return false;">None</a> ]<br />
	  </td>

	  <td width="160">
		<select name="AuditStage[]" id="AuditStage" style="width:140px;" multiple size="10">
<?
	foreach ($sAuditStagesList as $sKey => $sValue)
	{
?>
		  <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $AuditStage) || count($AuditStage) == 0) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
		</select>
	  </td>

	  <td width="100" style="line-height:18px;">
	    Report(s)<br />
	    <br />
	    [ <a href="./" onclick="selectAll('Report'); return false;">All</a> | <a href="./" onclick="clearAll('Report'); return false;">None</a> ]<br />
	  </td>

	  <td width="160">
		<select name="Report[]" id="Report" style="width:140px;" multiple size="10">
<?
	foreach ($sReportsList as $sKey => $sValue)
	{
?>
		  <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Report) || count($Report) == 0) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
		</select>
	  </td>

	  <td width="100" style="line-height:18px;">
	    Line(s)<br />
	    <br />
	    [ <a href="./" onclick="selectAll('Line'); return false;">All</a> | <a href="./" onclick="clearAll('Line'); return false;">None</a> ]<br />
	  </td>

	  <td width="160">
		<select name="Line[]" id="Line" style="width:140px;" multiple size="10">
<?
	foreach ($sLinesList as $sKey => $sValue)
	{
?>
		  <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Line) || count($Line) == 0) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
		</select>
	  </td>

	  <td align="right"></td>
	</tr>
  </table>
</div>
</form>

<div>
  <table border="0" cellspacing="0" cellpadding="10" width="100%">
    <tr valign="top">
      <td width="50%">
        <div class="tblSheet">
<?
	$sConditions = " AND FIND_IN_SET(qa.report_id, '$sQmipReports') AND qa.audit_stage!='' ";

	if (count($Brand) > 0)
		$sConditions .= (" AND FIND_IN_SET(qa.brand_id, '".@implode(",", $Brand)."') ");

	if (count($AuditStage) > 0)
		$sConditions .= (" AND FIND_IN_SET(qa.audit_stage, '".@implode(",", $AuditStage)."') ");

	if (count($Report) > 0)
		$sConditions .= (" AND FIND_IN_SET(qa.report_id, '".@implode(",", $Report)."') ");

	if (count($Line) > 0)
		$sConditions .= (" AND FIND_IN_SET(qa.line_id, '".@implode(",", $Line)."') ");

	if ($Vendor > 0)
		$sConditions .= " AND qa.vendor_id='$Vendor' ";

	if ($Unit > 0)
		$sConditions .= " AND qa.unit_id='$Unit' ";


	$sLines            = array( );

	$fLineDhuType1     = array( );
	$iLineGmtsType1    = array( );
	$iLineDefectsType1 = array( );

	$fLineDhuType2     = array( );
	$iLineGmtsType2    = array( );
	$iLineDefectsType2 = array( );

	$fLineDhuType3     = array( );
	$iLineGmtsType3    = array( );
	$iLineDefectsType3 = array( );

	$iTotalGmts        = 0;
	$iTotalDefects     = 0;
	$sLineIds          = "0";


	$sSQL = "SELECT qa.line_id,
	                l.line AS _Line
			 FROM tbl_qa_reports qa, tbl_lines l
			 WHERE qa.line_id=l.id AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
			 GROUP BY qa.line_id
	         ORDER BY _Line";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iLine = $objDb->getField($i, "line_id");
		$sLine = $objDb->getField($i, "_Line");

		$sLines[$iLine] = $sLine;
		$sLineIds      .= ",{$iLine}";
	}



	$sSQL = "SELECT qa.line_id,
					COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects
			 FROM tbl_qa_reports qa, tbl_users u
			 WHERE qa.user_id=u.id AND u.auditor_type='1' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
			       AND FIND_IN_SET(qa.line_id, '$sLineIds')
			 GROUP BY qa.line_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iLine    = $objDb->getField($i, "line_id");
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");


		$iLineGmtsType1[$iLine]    = $iGmts;
		$iLineDefectsType1[$iLine] = $iDefects;
		$fLineDhuType1[$iLine]     = @round((($iDefects / $iGmts) * 100), 2);
	}



	$sSQL = "SELECT qa.line_id,
					COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects
			 FROM tbl_qa_reports qa, tbl_users u
			 WHERE qa.user_id=u.id AND u.auditor_type='2' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
			       AND FIND_IN_SET(qa.line_id, '$sLineIds')
			 GROUP BY qa.line_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iLine    = $objDb->getField($i, "line_id");
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");


		$iLineGmtsType2[$iLine]    = $iGmts;
		$iLineDefectsType2[$iLine] = $iDefects;
		$fLineDhuType2[$iLine]     = @round((($iDefects / $iGmts) * 100), 2);


		$iTotalGmts    += $iGmts;
		$iTotalDefects += $iDefects;
	}



	$sSQL = "SELECT qa.line_id,
					COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects
			 FROM tbl_qa_reports qa, tbl_users u
			 WHERE qa.user_id=u.id AND u.auditor_type='3' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
			       AND FIND_IN_SET(qa.line_id, '$sLineIds')
			 GROUP BY qa.line_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iLine    = $objDb->getField($i, "line_id");
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");


		$iLineGmtsType3[$iLine]    = $iGmts;
		$iLineDefectsType3[$iLine] = $iDefects;
		$fLineDhuType3[$iLine]     = @round((($iDefects / $iGmts) * 100), 2);
	}



	$fAvgDhu = @round((($iTotalDefects / $iTotalGmts) * 100), 2);
?>
			<div id="LinesChart">loading...</div>

			<script type="text/javascript">
			<!--
			var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", "Lines", "100%", "600", "0", "1");

			objChart.setXMLData("<chart caption='Line Progress' subCaption='QMIP Auditors: Quantity: <?= formatNumber($iTotalGmts, false) ?>  Defects: <?= formatNumber($iTotalDefects, false) ?>  DR: <?= formatNumber($fAvgDhu) ?>%' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fLineDhuType2) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='<?= strtolower(str_replace(" ", "-", $sStageTitle)) ?>'>" +
								"<categories>" +
<?
	foreach ($sLines as $iLine => $sLine)
	{
?>
								"<category label='<?= $sLine ?>' />" +
<?
	}
?>
								"</categories>" +


								"<dataset seriesName='QMIP Auditors'>" +
<?
  	foreach ($sLines as $iLine => $sLine)
  	{
?>
								"<set value='<?= $fLineDhuType2[$iLine] ?>' tooltext='Line: <?= $sLine ?>{br}DR: <?= formatNumber($fLineDhuType2[$iLine]) ?>%{br}TGI: <?= formatNumber($iLineGmtsType2[$iLine], false) ?>{br}TGR: <?= formatNumber($iLineDefectsType2[$iLine], false) ?>' link='' />" +
<?
  	}
?>
								"</dataset>" +


								"<dataset seriesName='3rd Party Auditors' renderAs='Line' color='#0000ff'>" +
<?
	foreach ($sLines as $iLine => $sLine)
	{
?>
								"<set value='<?= $fLineDhuType1[$iLine] ?>'  tooltext='Line: <?= $sLine ?>{br}DR: <?= formatNumber($fLineDhuType1[$iLine]) ?>%{br}TGI: <?= formatNumber($iLineGmtsType1[$iLine], false) ?>{br}TGR: <?= formatNumber($iLineDefectsType1[$iLine], false) ?>' />" +
<?
	}
?>
								"</dataset>" +


								"<dataset seriesName='QMIP Corelation Auditors' renderAs='Line' color='#ffff00'>" +
<?
	foreach ($sLines as $iLine => $sLine)
	{
?>
								"<set value='<?= $fLineDhuType3[$iLine] ?>'  tooltext='Line: <?= $sLine ?>{br}DR: <?= formatNumber($fLineDhuType3[$iLine]) ?>%{br}TGI: <?= formatNumber($iLineGmtsType3[$iLine], false) ?>{br}TGR: <?= formatNumber($iLineDefectsType3[$iLine], false) ?>' />" +
<?
	}
?>
								"</dataset>" +
								"</chart>");


			objChart.render("LinesChart");
			-->
			</script>

			<br />
	    </div>
      </td>


      <td width="50%">
        <div class="tblSheet">
<?
	$sDefectTypes  = array( );
	$iDefectTypes  = array( );
	$iTotalDefects = array( );

	$sSQL = "SELECT dt.id, dt.type AS _DefectType, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND IF(qa.report_id=10, qad.nature='1', TRUE)
			       AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
			 GROUP BY dc.type_id
			 ORDER BY _Defects DESC, _DefectType ASC
			 LIMIT 5";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDefectType = $objDb->getField($i, "id");
		$sDefectType = $objDb->getField($i, "_DefectType");
		$iDefects    = $objDb->getField($i, "_Defects");

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


	$sDefectColors = getList("tbl_defect_types", "id", "color");
?>
					<div id="DefectClassChart">loading...</div>

					<script type="text/javascript">
					<!--
					var objChart = new FusionCharts("scripts/fusion-charts/charts/Pie2D.swf", "DefectClass", "100%", "600", "0", "1");

					objChart.setXMLData("<chart caption='Top Defect Types' showPercentageInLabel='1' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' bgcolor='ffffff' bordercolor='ffffff' animation='1' labelDisplay='WRAP' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='defect-classification'>" +
<?
	$iTotal = @array_sum($iTotalDefects);

	for ($i = 0; $i < count($iDefectTypes); $i ++)
	{
		$fPercent = @round((($iTotalDefects[$i] / $iTotal) * 100), 2);
?>
										"<set color='<?= $sDefectColors[$iDefectTypes[$i]] ?>' tooltext='Type: <?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?>{br}Defects: <?= $iTotalDefects[$i] ?> (<?= formatNumber($fPercent) ?>%)' label='<?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?> [<?= $iTotalDefects[$i] ?>]' value='<?= $iTotalDefects[$i] ?>' />" +
<?
	}
?>

										"</chart>");


					objChart.render("DefectClassChart");
					-->
					</script>

					<br />
	    </div>
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