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

	$Vendor   = IO::strValue("Vendor");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");

	if ($FromDate == "" || $ToDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 7), date("Y")));
		$ToDate   = date("Y-m-d");
	}

	$sVendorsList     = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND sourcing='Y'");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
  <script type="text/javascript" src="scripts/fusion-charts/FusionChartsExportComponent.js"></script>
</head>

<body style="margin:0px; background:#ffffff; padding:10px;">

<form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
<div id="SearchBar">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	  <td width="55">Vendor</td>

	  <td width="220">
		<select name="Vendor">
		  <option value="">All Vendors</option>
<?
	foreach ($sVendorsList as $sKey => $sValue)
	{
?>
		<option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
		</select>
	  </td>
	  <td width="75">From Date</td>
	  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
	  <td width="50"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
	  <td width="55">To Date</td>
	  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
	  <td width="50"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
	  <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
	</tr>
  </table>
</div>
</form>

<br />

<div>
<?
	$sConditions = " WHERE audit_type='B' AND audit_result!='' AND report_id!='6' AND (audit_date BETWEEN '$FromDate' AND '$ToDate') AND NOT FIND_IN_SET(report_id, '$sQmipReports') ";

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";


	$iAudits       = 0;
	$iFinalAudits  = 0;
	$iFailedAudits = 0;
	$iPassedAudits = 0;
	$iHolddAudits  = 0;
	$fFinalDhu     = 0;
	$fBatchDhu     = 0;
	$iMonthFinal   = array( );
	$iMonthPassed  = array( );
	$iMonthFailed  = array( );
	$sMonths       = array( );


	$sSQL = "SELECT COUNT(*), audit_stage, audit_result, DATE_FORMAT(audit_date, '%b %Y') AS _MonthYear
	         FROM tbl_qa_reports
	         $sConditions
	         GROUP BY audit_stage, audit_result, _MonthYear
	         ORDER BY audit_date";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iTempAudits = $objDb->getField($i, 0);
		$sTempStage  = $objDb->getField($i, 1);
		$sTempResult = $objDb->getField($i, 2);
		$sTempMonth  = $objDb->getField($i, 3);


		if (!@in_array($sTempMonth, $sMonths))
			$sMonths[] = $sTempMonth;

		$iIndex = @array_search($sTempMonth, $sMonths);


		$iAudits += $iTempAudits;

		if ($sTempStage == "F")
		{
			$iFinalAudits += $iTempAudits;

			if ($sTempResult == "P" || $sTempResult == "A" || $sTempResult == "B")
			{
				$iPassedAudits         += $iTempAudits;
				$iMonthPassed[$iIndex] += $iTempAudits;
			}

			else if ($sTempResult == "F" || $sTempResult == "C")
			{
				$iFailedAudits         += $iTempAudits;
				$iMonthFailed[$iIndex] += $iTempAudits;
			}

			else
				$iHoldAudits += $iTempAudits;


			$iMonthFinal[$iIndex] += $iTempAudits;
		}
	}



	$sSQL = "SELECT audit_stage, COALESCE(SUM(total_gmts), 0) AS _Gmts,
					SUM(
					     IF ( report_id=10,
					          (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND nature='1'),

					          IF ( report_id=11,
					               (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND nature<'4'),
					               (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id)
					             )
					        )
					   ) AS _Defects
			 FROM tbl_qa_reports
			 $sConditions
			 GROUP BY audit_stage";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$fDhu   = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sStage   = $objDb->getField($i, "audit_stage");
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");


		$fDhu[$sStage] = @round((($iDefects / $iGmts) * 100), 2);
	}
?>
  <h3>Statistics</h3>

  <table border="0" cellspacing="0" cellpadding="5" width="100%">
    <tr>
      <td width="200">No of Audits</td>
      <td><?= formatNumber($iAudits, false) ?></td>
    </tr>

    <tr>
      <td>No of Final Audits</td>
      <td><?= formatNumber($iFinalAudits, false) ?></td>
    </tr>

    <tr>
      <td>No of Final Audits (Passed)</td>
      <td><?= formatNumber($iPassedAudits, false) ?> (<?= formatNumber((($iPassedAudits / $iFinalAudits) * 100), false) ?>%)</td>
    </tr>

    <tr>
      <td>No of Final Audits (Failed)</td>
      <td><?= formatNumber($iFailedAudits, false) ?> (<?= formatNumber((($iFailedAudits / $iFinalAudits) * 100), false) ?>%)</td>
    </tr>

    <tr>
      <td>No of Final Audits (Hold)</td>
      <td><?= formatNumber($iHoldAudits, false) ?> (<?= formatNumber((($iHoldAudits / $iFinalAudits) * 100), false) ?>%)</td>
    </tr>

<?
	foreach ($fDhu as $sStage => $fDr)
	{
		$sStage = $sAuditStagesList[$sStage];
?>
    <tr>
      <td>DR (<?= $sStage ?>)</td>
      <td><?= formatNumber($fDr) ?>%</td>
    </tr>
<?
	}
?>
  </table>

  <br />


			                  <div id="FinalAuditsChart">loading...</div>

			                    <script type="text/javascript">
			                    <!--
								    var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", "FinalAudits", "100%", "420", "0", "1");

                                    objChart.setXMLData("<chart caption='Final Audits (Month wise Stats)' formatNumberScale='0' showValues='1' showLabels='1' chartBottomMargin='5' legendPosition='BOTTOM'>" +
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

                                                        "<dataset seriesName='Total'>" +
<?
  		for ($i = 0; $i < count($sMonths); $i ++)
  		{
?>
                                                        "<set value='<?= $iMonthFinal[$i] ?>' />" +
<?
  		}
?>
                                                        "</dataset>" +

                                                        "<dataset seriesName='Passed' color='#00ff00'>" +
<?
		for ($i = 0; $i < count($sMonths); $i ++)
		{
?>
                                                        "<set value='<?= $iMonthPassed[$i] ?>' />" +
<?
		}
?>
                                                        "</dataset>" +

                                                        "<dataset seriesName='Failed' color='#ff0000'>" +
<?
		for ($i = 0; $i < count($sMonths); $i ++)
		{
?>
                                                        "<set value='<?= $iMonthFailed[$i] ?>' />" +
<?
		}
?>
                                                        "</dataset>" +
                                                        "</chart>");


								    objChart.render("FinalAuditsChart");
    						    -->
    						    </script>

	<br />
	<h3>Top 3 Defects</h3>

	<table border="1" bordercolor="#eeeeee" cellspacing="0" cellpadding="4" width="100%">
	  <tr valign="top" bgcolor="#f6f6f6">
		<td width="15%"><b>Code</b></td>
		<td width="53%"><b>Defect Label</b></td>
		<td width="17%"><b>Defects</b></td>
		<td width="15%"><b>%tage</b></td>
	  </tr>

<?
	$sConditions = " AND qa.audit_type='B' AND qa.audit_result!='' AND qa.report_id!='6' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') AND NOT FIND_IN_SET(qa.report_id, '$sQmipReports') ";

	if ($Vendor > 0)
		$sConditions .= " AND qa.vendor_id='$Vendor' ";



	$sSQL = "SELECT COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad
			 WHERE qa.id=qad.audit_id $sConditions
			       AND IF(qa.report_id=10, qad.nature='1', TRUE)
			       AND IF(qa.report_id=11, qad.nature<'4', TRUE)";
	$objDb->query($sSQL);

	$iTotalDefects = $objDb->getField(0, 0);



	$sCodes = "";

	$sSQL = "SELECT dc.id, dc.defect, dc.code, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id $sConditions
			       AND IF(qa.report_id=10, qad.nature='1', TRUE)
			       AND IF(qa.report_id=11, qad.nature<'4', TRUE)
			 GROUP BY dc.id
			 ORDER BY _Defects DESC
			 LIMIT 3";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCode    = $objDb->getField($i, 0);
		$sDefect  = $objDb->getField($i, 1);
		$sCode    = $objDb->getField($i, 2);
		$iDefects = $objDb->getField($i, 3);

		$fPercentage = @round((($iDefects / $iTotalDefects) * 100), 2);
		$sCodes     .= ((($i > 0) ? "," : "").$iCode);
?>
	  <tr valign="top">
		<td><?= $sCode ?></td>
		<td><?= $sDefect ?></td>
		<td><?= formatNumber($iDefects, false) ?></td>
		<td><?= formatNumber($fPercentage) ?></td>
	  </tr>
<?
	}
?>
	</table>


	<br />

<?
	$sMonths  = array( );
	$sDefects = array( );

	$sSQL = "SELECT dc.defect, dc.code, COALESCE(SUM(qad.defects), 0) AS _Defects, DATE_FORMAT(qa.audit_date, '%b %Y') AS _MonthYear
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id $sConditions
			       AND IF(qa.report_id=10, qad.nature='1', TRUE)
			       AND IF(qa.report_id=11, qad.nature<'4', TRUE)
			       AND FIND_IN_SET(dc.id, '$sCodes')
			 GROUP BY dc.id, _MonthYear
			 ORDER BY qa.audit_date, _Defects DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sDefect  = $objDb->getField($i, 0);
		$sCode    = $objDb->getField($i, 1);
		$iDefects = $objDb->getField($i, 2);
		$sMonth   = $objDb->getField($i, 3);


		if (!@in_array($sMonth, $sMonths))
			$sMonths[] = $sMonth;

		$iIndex = @array_search($sMonth, $sMonths);

		$sDefects[$sDefect][$iIndex] = $iDefects;
	}
?>

			                  <div id="TopDefectsChart">loading...</div>

			                    <script type="text/javascript">
			                    <!--
								    var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", "TopDefects", "100%", "420", "0", "1");

                                    objChart.setXMLData("<chart caption='Top Defects (Month wise Stats)' formatNumberScale='0' showValues='1' showLabels='1' chartBottomMargin='5' legendPosition='BOTTOM'>" +
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
		foreach ($sDefects as $sCode => $iDefects)
		{
?>
                                                        "<dataset seriesName='<?= $sCode ?>'>" +
<?
	  		for ($i = 0; $i < count($sMonths); $i ++)
	  		{
?>
                                                        "<set value='<?= $iDefects[$i] ?>' />" +
<?
	  		}
?>
                                                        "</dataset>" +
<?
		}
?>
                                                        "</chart>");


								    objChart.render("TopDefectsChart");
    						    -->
    						    </script>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>