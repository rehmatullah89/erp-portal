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
  <script type="text/javascript" src="scripts/jquery.js"></script>
</head>

<body style="margin:0px; padding:20px; background:#ffffff;">
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
	$(function( )
	{
			var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", "Lines", "100%", ($(window).height( ) - 80), "0", "1");

			objChart.setXMLData("<chart caption='Line Progress' subCaption='Quantity: <?= formatNumber($iTotalGmts, false) ?>  Defects: <?= formatNumber($iTotalDefects, false) ?>  DR: <?= formatNumber($fAvgDhu) ?>%' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fLineDhu) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='<?= strtolower(str_replace(" ", "-", $sStageTitle)) ?>'>" +
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
	});
	-->
	</script>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>