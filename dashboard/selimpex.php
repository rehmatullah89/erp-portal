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
	$AuditStage = IO::strValue("AuditStage");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");

	if ($FromDate != "" && $ToDate == "")
		$ToDate = $FromDate;

	if ($FromDate == "")
		$FromDate = date("Y-m-d");

	if ($ToDate == "")
		$ToDate = date("Y-m-d");
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
</head>

<body style="margin:0px; background:#ffffff;">

<form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;" style="margin:10px;">
<div id="SearchBar">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	  <td width="40">Date</td>
	  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
	  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
	  <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
	</tr>
  </table>
</div>
</form>

<div>
  <table border="0" cellspacing="0" cellpadding="10" width="100%">
    <tr valign="top">
      <td width="33.3%">
        <div class="tblSheet">
<?
	$sConditions = " AND FIND_IN_SET(qa.report_id, '$sQmipReports') ";

	if ($AuditStage != "")
		$sConditions .= " AND qa.audit_stage='$AuditStage' ";

	else
		$sConditions .= " AND qa.audit_stage!='' ";

	if ($Vendor > 0)
		$sConditions .= " AND qa.vendor_id='$Vendor' ";


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
			var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", "Lines", "100%", "440", "0", "1");

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
	    </div>
      </td>


      <td width="33.4%">
        <div class="tblSheet">
<?
	$sLines = array( );
	$sStats = array( );


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


		for ($j = 0; $j < 24; $j ++)
		{
			$sStats[$iLine][$j]['Samples'] = 0;
			$sStats[$iLine][$j]['Defects'] = 0;
		}



		$sSQL = "SELECT HOUR(TIME(qap.date_time)) AS _Hour,
						COUNT(1) AS _Samples
				 FROM tbl_qa_reports qa, tbl_qa_report_progress qap
				 WHERE qa.id=qap.audit_id AND qa.line_id='$iLine' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
				 GROUP BY _Hour";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iHour    = $objDb2->getField($j, "_Hour");
			$iSamples = $objDb2->getField($j, "_Samples");

			$sStats[$iLine][$iHour]['Samples'] = $iSamples;
		}



		$sSQL = "SELECT HOUR(TIME(qad.date_time)) AS _Hour,
						COUNT(1) AS _Defects
				 FROM tbl_qa_reports qa, tbl_qa_report_defects qad
				 WHERE qa.id=qad.audit_id AND qa.line_id='$iLine' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
				 GROUP BY _Hour";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iHour    = $objDb2->getField($j, "_Hour");
			$iDefects = $objDb2->getField($j, "_Defects");

			$sStats[$iLine][$iHour]['Defects'] = $iDefects;
		}
	}
?>
			<div id="ProgressChart">loading...</div>

			<script type="text/javascript">
			<!--
			var objChart = new FusionCharts("scripts/fusion-charts/charts/MSLine.swf", "Progress", "100%", "440", "0", "1");

			objChart.setXMLData("<chart caption='Hourly Progress' subCaption='' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fLineDhu) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='<?= strtolower(str_replace(" ", "-", $sStageTitle)) ?>'>" +
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

<?
	foreach ($sLines as $iLine => $sLine)
	{
?>
								"<dataset seriesName='<?= $sLine ?>'>" +
<?
		for ($i = 8; $i < 24; $i ++)
		{
			$fDr = @round((($sStats[$iLine][$i]['Defects'] / $sStats[$iLine][$i]['Samples']) * 100), 2);
?>
								"<set value='<?= formatNumber($fDr) ?>' tooltext='Line: <?= $sLine ?>{br}Samples: <?= $sStats[$iLine][$i]['Samples'] ?>{br}Defects: <?= $sStats[$iLine][$i]['Defects'] ?>{br}DR: <?= formatNumber($fDr) ?>' />" +
<?
		}
?>
								"</dataset>" +
<?
	}
?>

								"</chart>");


			objChart.render("ProgressChart");
			-->
			</script>
	    </div>
      </td>


      <td width="33.3%">
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
					var objChart = new FusionCharts("scripts/fusion-charts/charts/Pie2D.swf", "DefectClass", "100%", "440", "0", "1");

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
	    </div>
      </td>
    </tr>


    <tr valign="top">
      <td>
        <div class="tblSheet" style="min-height:440px;">
		  <h1>Best Performers</h1>

		  <div style="padding:25px; font-size:19px;">

<?
	$sSQL = "SELECT (SELECT name FROM tbl_users WHERE id=qa.user_id) AS _Auditor,
					ROUND(((
						SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id))
						/
						COALESCE(SUM(IF(qa.total_gmts='0', qa.checked_gmts, qa.total_gmts)), 0)
					) * 100), 2) AS _Dr,

					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects,
					COALESCE(SUM(IF(qa.total_gmts='0', qa.checked_gmts, qa.total_gmts)), 0) AS _Quantity
			 FROM tbl_qa_reports qa
			 WHERE (qa.checked_gmts>'0' OR qa.audit_result!='') AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
			 GROUP BY qa.user_id
			 HAVING _Quantity > '0'
			 ORDER BY _Dr
			 LIMIT 5";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAuditor  = $objDb->getField($i, "_Auditor");
		$fDr       = $objDb->getField($i, "_Dr");
		$iDefects  = $objDb->getField($i, "_Defects");
		$iQuantity = $objDb->getField($i, "_Quantity");

		print (($i + 1).". ".$sAuditor."<br /><small>(DR:".$fDr."% &nbsp;  Qty:".$iQuantity." &nbsp; Defects:".$iDefects.")</small><br><br>");
	}
?>
	      </div>
	    </div>
      </td>


      <td>
        <div class="tblSheet" style="min-height:440px;">
		  <h1>Employee of Month</h1>

		  <div style="padding:25px; font-size:19px;">
<?
	$StartDate = date("Y-m-01");
	$EndDate   = date("Y-m-t");

	$sReports = array("Cutting" => "15,21",
	                  "Output"  => "22",
	                  "Batch"   => "16,17",
	                  "Final"   => "18");

	foreach ($sReports as $sReportType => $sReportIds)
	{
		$sSQL = "SELECT (SELECT name FROM tbl_users WHERE id=qa.user_id) AS _Auditor,
						ROUND(((
							SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id))
							/
							COALESCE(SUM(IF(qa.total_gmts='0', qa.checked_gmts, qa.total_gmts)), 0)
						) * 100), 2) AS _Dr,

						SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects,
						COALESCE(SUM(IF(qa.total_gmts='0', qa.checked_gmts, qa.total_gmts)), 0) AS _Quantity
				 FROM tbl_qa_reports qa
				 WHERE (qa.checked_gmts>'0' OR qa.audit_result!='') AND FIND_IN_SET(qa.report_id, '$sReportIds')
					   AND (qa.audit_date BETWEEN '$StartDate' AND '$EndDate') $sConditions
				 GROUP BY qa.user_id
				 HAVING _Quantity > '0'
				 ORDER BY _Dr
				 LIMIT 1";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sAuditor  = $objDb->getField($i, "_Auditor");
			$fDr       = $objDb->getField($i, "_Dr");
			$iDefects  = $objDb->getField($i, "_Defects");
			$iQuantity = $objDb->getField($i, "_Quantity");

			print ($sAuditor." (".$sReportType.")<br /><small>(DR:".$fDr."% &nbsp;  Qty:".$iQuantity." &nbsp; Defects:".$iDefects.")</small><br><br>");
		}
	}
?>
	      </div>
	    </div>
      </td>


      <td>
        <div class="tblSheet">
<?
	$sPictures = array( );

	$sSQL = "SELECT DISTINCT(qa.audit_code), qa.audit_date
			 FROM tbl_qa_reports qa
			 WHERE qa.audit_type='B' AND qa.audit_result!='' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($AuditStage != "")
		$sSQL .= " AND qa.audit_stage='$AuditStage' ";

	else
		$sSQL .= " AND qa.audit_stage!='' ";

	if ($Vendor > 0)
		$sSQL .= " AND qa.vendor_id='$Vendor' ";

	$sSQL .= " ORDER BY RAND( )";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAuditCode = $objDb->getField($i, 0);
		$sAuditDate = $objDb->getField($i, 1);

		@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

		$sAuditPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*");
   		$sAuditPictures = @array_map("strtoupper", $sAuditPictures);
   		$sAuditPictures = @array_unique($sAuditPictures);

		$sTemp = array( );

		foreach ($sAuditPictures as $sPicture)
			$sTemp[] = $sPicture;

		$sAuditPictures = $sTemp;


		for ($j = 0; $j < count($sAuditPictures); $j ++)
		{
			$sName  = @strtoupper($sAuditPictures[$j]);
			$sName  = @basename($sName, ".JPG");
			$sName  = @basename($sName, ".GIF");
			$sName  = @basename($sName, ".PNG");
			$sName  = @basename($sName, ".BMP");
			$sParts = @explode("_", $sName);

			$sPictures[] = $sAuditPictures[$j];
		}
	}


	$iPictures = count($sPictures);

	if ($iPictures == 0)
	{
?>
				<div style="padding:10px;">
				  No Defect Image Found!<br />
				</div>
<?
	}

	else
	{
?>
				<div style="padding:10px; position:relative;" id="Glider">

				<div class="scroller" style="width:480px;">
				<div class="content">
<?
		$iIndex = 0;

		for ($iSlide = 1; $iSlide <= 5; $iSlide ++)
		{
?>
				<div class="section" id="section<?= $iSlide ?>" style="width:480px;">
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
			for ($i = 0; $i < 3; $i ++)
			{
?>
	    			<tr valign="top">
<?
				for ($j = 0; $j < 3; $j ++)
				{
					if ($iIndex < $iPictures)
					{
						$sName  = @strtoupper($sPictures[$iIndex]);
						$sName  = @basename($sName, ".JPG");
						$sName  = @basename($sName, ".GIF");
						$sName  = @basename($sName, ".PNG");
						$sName  = @basename($sName, ".BMP");
						$sParts = @explode("_", $sName);

						$sAuditCode   = $sParts[0];
						$sDefectCode  = $sParts[1];
						$sAreaCode    = $sParts[2];
						$sDefectTitle = "";


						$sSQL = "SELECT report_id,
										(SELECT vendor FROM tbl_vendors WHERE id=qa.vendor_id) AS _Vendor,
										(SELECT order_no FROM tbl_po WHERE id=qa.po_id) AS _PO,
										(SELECT style FROM tbl_styles WHERE id=(SELECT style_id FROM tbl_po_colors WHERE po_id=qa.po_id LIMIT 1)) AS _Style,
										(SELECT line FROM tbl_lines WHERE id=qa.line_id) AS _Line
								 FROM tbl_qa_reports qa
								 WHERE audit_code='$sAuditCode'";

						if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
						{
							$iReportId = $objDb->getField(0, 0);

							$sTitle  = $objDb->getField(0, 1);
							$sTitle .= (" <b>�</b> ".$objDb->getField(0, 2));
							$sTitle .= (" <b>�</b> ".$objDb->getField(0, 3));
							$sTitle .= (" <b>�</b> ".$objDb->getField(0, 4));


							$sSQL = "SELECT defect,
											(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
									 FROM tbl_defect_codes dc
									 WHERE code='$sDefectCode' AND report_id='$iReportId'";

							if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
							{
								$sDefect = $objDb->getField(0, 0);

								$sDefectTitle = $objDb->getField(0, 0);
								$sTitle      .= (" <b>�</b> ".$objDb->getField(0, 1));

								if ($iReportId != 4 && $iReportId != 6)
								{
									$sSQL = "SELECT area FROM tbl_defect_areas WHERE id='$sAreaCode'";

									if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
										$sTitle .= (" <b>�</b> ".$objDb->getField(0, 0));
								}

								$sTitle .= (" <b>�</b> ".$sDefect);
							}
						}

						else
							$sTitle = "<b>### Invalid File Name ###</b>";
?>
					  <td width="33.3%" align="center">
						<div class="qaPic" style="width:134px; height:113px;">
						  <div><a href="<?= $sPictures[$iIndex] ?>" class="lightview" rel="gallery[defects]" title="<?= $sTitle ?> :: :: topclose: true"><img src="<?= $sPictures[$iIndex] ?>" alt="" title="" style="width:130px; height:109px;" /></a></div>
						</div>

						<div style="overflow:hidden; line-height:13px; height:13px; text-align:center;"><?= $sDefectTitle ?></div>
					  </td>
<?
						$iIndex ++;
					}

					else
					{
?>
	      			  <td width="25%"></td>
<?
					}
				}
?>
					</tr>
<?
				if ($i < 3)
				{
?>
					<tr>
					  <td colspan="3" height="9"></td>
					</tr>
<?
				}
			}
?>
	  			  </table>
	  			</div>


<?
			if ($iIndex >= $iPictures)
				break;
		}
?>
				</div>
				</div>

				</div>

				<script type="text/javascript">
				<!--
					var objGlider = new Glider('Glider', { frequency:8, autoGlide:true });
				-->
				</script>
<?
	}
?>
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