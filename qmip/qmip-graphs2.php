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


	$Vendor     = IO::intValue("Vendor");
	$Brand      = IO::intValue("Brand");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");
	$Report     = IO::intValue("Report");
	$AuditStage = IO::strValue("AuditStage");
	$Lines      = IO::strValue("Lines");
	$iLines     = $Lines;
	$Lines      = @explode(",", $Lines);


	if ($FromDate == "" || $ToDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 28), date("Y")));
		$ToDate   = date("Y-m-d");
	}

	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];


	$sVendorBrands    = getDbValue("GROUP_CONCAT(DISTINCT(brand_id) SEPARATOR ',')", "tbl_po", "vendor_id='$Vendor'");
	$sReportTypes     = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");

	$sVendorsList     = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sUnitsList       = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='$Vendor' AND sourcing='Y'");
	$sBrandsList      = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']}) AND id IN ($sVendorBrands)");
	$sReportsList     = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes')");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/qmip/qmip-graphs2.js"></script>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
  <script type="text/javascript" src="scripts/fusion-charts/FusionChartsExportComponent.js"></script>
</head>

<body>

<div id="MainDiv">
  <div id="PageLeftBorder">
    <div id="PageRightBorder">

<!--  Message Section Starts Here  -->
<?
	@include($sBaseDir."includes/messages.php");
?>
<!--  Message Section Ends Here  -->

      <div id="PageContents">

<!--  Header Section Starts Here  -->
<?
	@include($sBaseDir."includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Navigation Section Starts Here  -->
<?
	@include($sBaseDir."includes/navigation.php");
?>
<!--  Navigation Section Ends Here  -->


<!--  Body Section Starts Here  -->
	    <div id="Body">
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr valign="top">
			  <td width="100%">
			    <h1><img src="images/h1/qmip/qmip-graphs.jpg" width="180" height="23" alt="" title="" style="margin:9px 0px 8px 0px;" /></h1>

			    <form name="frmSearch" id="frmSearch" onsubmit="return false;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="55">Vendor</td>

			          <td width="190">
			            <select name="Vendor" id="VVendor" onchange="getListValues('VVendor', 'VBrand', 'VendorBrands'); getLines('VVendor', 'VLine');">
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

			          <td width="45">Brand</td>

			          <td width="160">
			            <select name="Brand" id="VBrand" style="width:150px;">
			              <option value="">All Brands</option>
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>

			          <td width="50">Report</td>

			          <td width="160">
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

			          <td width="45">Stage</td>

			          <td width="120">
			            <select name="AuditStage">
			              <option value="">All Stages</option>
<?
	foreach ($sAuditStagesList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $AuditStage) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" onclick="updateUnitsGraph( );" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar" style="height:auto; padding-top:5px; padding-bottom:5px;">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr valign="top">
			          <td width="55" style="line-height:18px;">Line(s)</td>

			          <td width="250">
			            <select id="VLine" name="Line" multiple size="10" style="min-width:230px;">
<?
	$sSQL = "SELECT id, line, unit_id FROM tbl_lines WHERE vendor_id='$Vendor' ORDER BY line";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iLine = $objDb->getField($i, 0);
		$sLine = $objDb->getField($i, 1);
		$iUnit = $objDb->getField($i, 2);
?>
	  	        		  <option value="<?= $iLine ?>"<?= ((@in_array($iLine, $Lines)) ? " selected" : "") ?>><?= $sLine ?><?= (($iUnit > 0) ? " ({$sUnitsList[$iUnit]})" : "") ?></option>
<?
	}
?>
			            </select>
			          </td>

					  <td width="40" style="line-height:18px;">From</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center" style="line-height:18px;">To</td>
					  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="50"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td style="line-height:18px;">[ <a href="#" onclick="clearDates( ); return false;">Clear Dates</a> ]</td>
				    </tr>
				  </table>
			    </div>
			    </form>

<?
	$sConditions  = " AND qa.audit_type='B' AND qa.audit_result!='' AND qa.vendor_id='$Vendor' ";
	$sConditions .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '$sVendorBrands') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

	if ($AuditStage != "")
		$sConditions .= " AND qa.audit_stage='$AuditStage' ";

	else
		$sConditions .= " AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

	if ($Brand > 0)
		$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id='$Brand' AND vendor_id='$Vendor') ";

	if ($Report > 0)
		$sConditions .= " AND qa.report_id='$Report' ";

	else
		$sConditions .= " AND FIND_IN_SET(qa.report_id, '$sReportTypes') ";

	if ($iLines != "")
		$sConditions .= " AND FIND_IN_SET(qa.line_id, '$iLines') ";



	$f3rdPartyInlineDr       = array( );
	$i3rdPartyInlineGmts     = array( );
	$i3rdPartyInlineDefects  = array( );

	$f3rdPartyFinalDr        = array( );
	$i3rdPartyFinalGmts      = array( );
	$i3rdPartyFinalDefects   = array( );

	$fQmipInlineDr           = array( );
	$iQmipInlineGmts         = array( );
	$iQmipInlineDefects      = array( );

	$fQmipCoRelationDr       = array( );
	$iQmipCoRelationGmts     = array( );
	$iQmipCoRelationDefects  = array( );

	$fFinalAuditsDr          = array( );
	$iFinalAuditsGmts        = array( );
	$iFinalAuditsDefects     = array( );

	$fFinalPassAuditsDr      = array( );
	$iFinalPassAuditsGmts    = array( );
	$iFinalPassAuditsDefects = array( );



	$sSQL = "SELECT qa.unit_id,
					qa.audit_result,
					COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects
			 FROM tbl_qa_reports qa
			 WHERE qa.audit_stage='F' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
			 GROUP BY qa.unit_id, qa.audit_result";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iUnit    = $objDb->getField($i, "unit_id");
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");
		$sResult  = $objDb->getField($i, "audit_result");


		if ($sResult == "A" || $sResult == "B" || $sResult == "P")
		{
			$iFinalPassAuditsGmts[$iUnit]    += $iGmts;
			$iFinalPassAuditsDefects[$iUnit] += $iDefects;
			$fFinalPassAuditsDr[$iUnit]       = @round((($iFinalPassAuditsDefects[$iUnit] / $iFinalPassAuditsGmts[$iUnit]) * 100), 2);
		}


		$iFinalAuditsGmts[$iUnit]    += $iGmts;
		$iFinalAuditsDefects[$iUnit] += $iDefects;
		$fFinalAuditsDr[$iUnit]       = @round((($iFinalAuditsDefects[$iUnit] / $iFinalAuditsGmts[$iUnit]) * 100), 2);


		if ($iUnit == 0)
			$sUnitsList[0] = $sVendorsList[$Vendor];
	}


	$sSQL = "SELECT qa.unit_id,
					qa.audit_stage,
					COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects
			 FROM tbl_qa_reports qa, tbl_users u
			 WHERE qa.user_id=u.id AND u.auditor_type='1' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
			 GROUP BY qa.unit_id, qa.audit_stage";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iUnit    = $objDb->getField($i, "unit_id");
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");
		$sStage   = $objDb->getField($i, "audit_stage");


		if ($sStage == "F")
		{
			$i3rdPartyFinalGmts[$iUnit]    += $iGmts;
			$i3rdPartyFinalDefects[$iUnit] += $iDefects;
			$f3rdPartyFinalDr[$iUnit]      = @round((($i3rdPartyFinalDefects[$iUnit] / $i3rdPartyFinalGmts[$iUnit]) * 100), 2);
		}

		else
		{
			$i3rdPartyInlineGmts[$iUnit]    += $iGmts;
			$i3rdPartyInlineDefects[$iUnit] += $iDefects;
			$f3rdPartyInlineDr[$iUnit]     = @round((($i3rdPartyInlineDefects[$iUnit] / $i3rdPartyInlineGmts[$iUnit]) * 100), 2);
		}


		if ($iUnit == 0)
			$sUnitsList[0] = $sVendorsList[$Vendor];
	}



	$sSQL = "SELECT qa.unit_id,
					COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects
			 FROM tbl_qa_reports qa, tbl_users u
			 WHERE qa.user_id=u.id AND u.auditor_type='2' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
			 GROUP BY qa.unit_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iUnit    = $objDb->getField($i, "unit_id");
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");


		$iQmipInlineGmts[$iUnit]    = $iGmts;
		$iQmipInlineDefects[$iUnit] = $iDefects;
		$fQmipInlineDr[$iUnit]      = @round((($iDefects / $iGmts) * 100), 2);


		if ($iUnit == 0)
			$sUnitsList[0] = $sVendorsList[$Vendor];
	}



	$sSQL = "SELECT qa.unit_id,
					COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects
			 FROM tbl_qa_reports qa, tbl_users u
			 WHERE qa.user_id=u.id AND u.auditor_type='3' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
			 GROUP BY qa.unit_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iUnit    = $objDb->getField($i, "unit_id");
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");


		$iQmipCoRelationGmts[$iUnit]    = $iGmts;
		$iQmipCoRelationDefects[$iUnit] = $iDefects;
		$fQmipCoRelationDr[$iUnit]      = @round((($iDefects / $iGmts) * 100), 2);


		if ($iUnit == 0)
			$sUnitsList[0] = $sVendorsList[$Vendor];
	}



	$sQueryString = $_SERVER['QUERY_STRING'];

	if ($sQueryString == "")
	{
		$sQueryString = "FromDate={$sFromDate}&ToDate={$ToDate}&Vendor={$Vendor}";

		if ($AuditStage != "")
			$sQueryString .= "&AuditStage={$AuditStage}";

		if ($Brand > 0)
			$sQueryString .= "&Brand={$Brand}";

		if ($Report > 0)
			$sQueryString .= "&Report={$Report}";
	}
?>
			    <div class="tblSheet">
					<div id="UnitsChart">loading...</div>
				</div>

			    <div class="tblSheet" id="LineGraphDiv" style="display:none; margin-top:15px;">
				  <div id="LinesChart">loading...</div>
				</div>

			    <div class="tblSheet">
					<div id="UnitsChartFixed">loading...</div>
				</div>

			    <div class="tblSheet" id="LineGraphDivFixed" style="display:none; margin-top:15px;">
				  <div id="LinesChartFixed">loading...</div>
				</div>

				<script type="text/javascript">
				<!--
					var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", "Units", "100%", "440", "0", "1");

					objChart.setXMLData("<chart caption='Overall Unit Health' subCaption='' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fUnitDr) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='unit-health'>" +
										"<categories>" +
<?
	foreach ($sUnitsList as $iUnit => $sUnit)
	{
?>
								"<category label='<?= $sUnit ?>' />" +
<?
	}
?>
								"</categories>" +


								"<dataset seriesName='QMIP Inline Audits'>" +
<?
  	foreach ($sUnitsList as $iUnit => $sUnit)
  	{
		$sParams = $sQueryString;

		if ($sParams != "")
		{
			if (@strpos($sParams, "Unit={$iUnit}") === FALSE)
				$sParams .= "&Unit={$iUnit}";
		}
?>
								"<set value='<?= $fQmipInlineDr[$iUnit] ?>' tooltext='Unit: <?= $sUnit ?>{br}DR: <?= formatNumber($fQmipInlineDr[$iUnit]) ?>%{br}TGI: <?= formatNumber($iQmipInlineGmts[$iUnit], false) ?>{br}TGR: <?= formatNumber($iQmipInlineDefects[$iUnit], false) ?>' link='javascript:showLinesGraph(\"<?= $sParams ?>\")' />" +
<?
  	}
?>
								"</dataset>" +


								"<dataset seriesName='3rd Party Inline Audits'>" +
<?
	foreach ($sUnitsList as $iUnit => $sUnit)
	{
		$sParams = $sQueryString;

		if ($sParams != "")
		{
			if (@strpos($sParams, "Unit={$iUnit}") === FALSE)
				$sParams .= "&Unit={$iUnit}";
		}
?>
								"<set value='<?= $f3rdPartyInlineDr[$iUnit] ?>'  tooltext='Unit: <?= $sUnit ?>{br}DR: <?= formatNumber($f3rdPartyInlineDr[$iUnit]) ?>%{br}TGI: <?= formatNumber($i3rdPartyInlineGmts[$iUnit], false) ?>{br}TGR: <?= formatNumber($i3rdPartyInlineDefects[$iUnit], false) ?>' link='javascript:showLinesGraph(\"<?= $sParams ?>\")' />" +
<?
	}
?>
								"</dataset>" +


								"<dataset seriesName='QMIP Corelation Audits' renderAs='Line' color='#ffff00'>" +
<?
	foreach ($sUnitsList as $iUnit => $sUnit)
	{
?>
								"<set value='<?= $fQmipCoRelationDr[$iUnit] ?>'  tooltext='Unit: <?= $sUnit ?>{br}DR: <?= formatNumber($fQmipCoRelationDr[$iUnit]) ?>%{br}TGI: <?= formatNumber($iQmipCoRelationGmts[$iUnit], false) ?>{br}TGR: <?= formatNumber($iQmipCoRelationDefects[$iUnit], false) ?>' />" +
<?
	}
?>
								"</dataset>" +

								"<dataset seriesName='3rd Party Final Audits' renderAs='Line' color='#0000ff'>" +
<?
	foreach ($sUnitsList as $iUnit => $sUnit)
	{
?>
								"<set value='<?= $f3rdPartyFinalDr[$iUnit] ?>'  tooltext='Unit: <?= $sUnit ?>{br}DR: <?= formatNumber($f3rdPartyFinalDr[$iUnit]) ?>%{br}TGI: <?= formatNumber($i3rdPartyFinalGmts[$iUnit], false) ?>{br}TGR: <?= formatNumber($i3rdPartyFinalDefects[$iUnit], false) ?>' />" +
<?
	}
?>
								"</dataset>" +

								"<dataset seriesName='Final Audits' renderAs='Line' color='#00aa00'>" +
<?
	foreach ($sUnitsList as $iUnit => $sUnit)
	{
?>
								"<set value='<?= $fFinalAuditsDr[$iUnit] ?>'  tooltext='Unit: <?= $sUnit ?>{br}DR: <?= formatNumber($fFinalAuditsDr[$iUnit]) ?>%{br}TGI: <?= formatNumber($iFinalAuditsGmts[$iUnit], false) ?>{br}TGR: <?= formatNumber($iFinalAuditsDefects[$iUnit], false) ?>' />" +
<?
	}
?>
								"</dataset>" +

								"<dataset seriesName='Passed Final Audits' renderAs='Line' color='#00ff00'>" +
<?
	foreach ($sUnitsList as $iUnit => $sUnit)
	{
?>
								"<set value='<?= $fFinalPassAuditsDr[$iUnit] ?>'  tooltext='Unit: <?= $sUnit ?>{br}DR: <?= formatNumber($fFinalPassAuditsDr[$iUnit]) ?>%{br}TGI: <?= formatNumber($iFinalPassAuditsGmts[$iUnit], false) ?>{br}TGR: <?= formatNumber($iFinalPassAuditsDefects[$iUnit], false) ?>' />" +
<?
	}
?>
								"</dataset>" +

								"</chart>");


					objChart.render("UnitsChart");




					objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", "UnitsFixed", "100%", "440", "0", "1");

					objChart.setXMLData("<chart caption='Overall Unit Health' subCaption='' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fUnitDr) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='overall-unit-health'>" +
										"<categories>" +
<?
	foreach ($sUnitsList as $iUnit => $sUnit)
	{
?>
								"<category label='<?= $sUnit ?>' />" +
<?
	}
?>
								"</categories>" +


								"<dataset seriesName='QMIP Inline Audits'>" +
<?
  	foreach ($sUnitsList as $iUnit => $sUnit)
  	{
		$sParams = $sQueryString;

		if ($sParams != "")
		{
			if (@strpos($sParams, "Unit={$iUnit}") === FALSE)
				$sParams .= "&Unit={$iUnit}";
		}
?>
								"<set value='<?= $fQmipInlineDr[$iUnit] ?>' tooltext='Unit: <?= $sUnit ?>{br}DR: <?= formatNumber($fQmipInlineDr[$iUnit]) ?>%{br}TGI: <?= formatNumber($iQmipInlineGmts[$iUnit], false) ?>{br}TGR: <?= formatNumber($iQmipInlineDefects[$iUnit], false) ?>' link='javascript:showLinesGraph2(\"<?= $sParams ?>\")' />" +
<?
  	}
?>
								"</dataset>" +


								"<dataset seriesName='3rd Party Inline Audits'>" +
<?
	foreach ($sUnitsList as $iUnit => $sUnit)
	{
		$sParams = $sQueryString;

		if ($sParams != "")
		{
			if (@strpos($sParams, "Unit={$iUnit}") === FALSE)
				$sParams .= "&Unit={$iUnit}";
		}
?>
								"<set value='<?= $f3rdPartyInlineDr[$iUnit] ?>'  tooltext='Unit: <?= $sUnit ?>{br}DR: <?= formatNumber($f3rdPartyInlineDr[$iUnit]) ?>%{br}TGI: <?= formatNumber($i3rdPartyInlineGmts[$iUnit], false) ?>{br}TGR: <?= formatNumber($i3rdPartyInlineDefects[$iUnit], false) ?>' link='javascript:showLinesGraph2(\"<?= $sParams ?>\")' />" +
<?
	}
?>
								"</dataset>" +


								"<dataset seriesName='QMIP Corelation Audits' renderAs='Line' color='#ffff00'>" +
<?
	foreach ($sUnitsList as $iUnit => $sUnit)
	{
?>
								"<set value='<?= $fQmipCoRelationDr[$iUnit] ?>'  tooltext='Unit: <?= $sUnit ?>{br}DR: <?= formatNumber($fQmipCoRelationDr[$iUnit]) ?>%{br}TGI: <?= formatNumber($iQmipCoRelationGmts[$iUnit], false) ?>{br}TGR: <?= formatNumber($iQmipCoRelationDefects[$iUnit], false) ?>' />" +
<?
	}
?>
								"</dataset>" +

								"<dataset seriesName='3rd Party Final Audits' renderAs='Line' color='#0000ff'>" +
<?
	foreach ($sUnitsList as $iUnit => $sUnit)
	{
?>
								"<set value='<?= $f3rdPartyFinalDr[$iUnit] ?>'  tooltext='Unit: <?= $sUnit ?>{br}DR: <?= formatNumber($f3rdPartyFinalDr[$iUnit]) ?>%{br}TGI: <?= formatNumber($i3rdPartyFinalGmts[$iUnit], false) ?>{br}TGR: <?= formatNumber($i3rdPartyFinalDefects[$iUnit], false) ?>' />" +
<?
	}
?>
								"</dataset>" +

								"<dataset seriesName='Final Audits' renderAs='Line' color='#00aa00'>" +
<?
	foreach ($sUnitsList as $iUnit => $sUnit)
	{
?>
								"<set value='<?= $fFinalAuditsDr[$iUnit] ?>'  tooltext='Unit: <?= $sUnit ?>{br}DR: <?= formatNumber($fFinalAuditsDr[$iUnit]) ?>%{br}TGI: <?= formatNumber($iFinalAuditsGmts[$iUnit], false) ?>{br}TGR: <?= formatNumber($iFinalAuditsDefects[$iUnit], false) ?>' />" +
<?
	}
?>
								"</dataset>" +

								"<dataset seriesName='Passed Final Audits' renderAs='Line' color='#00ff00'>" +
<?
	foreach ($sUnitsList as $iUnit => $sUnit)
	{
?>
								"<set value='<?= $fFinalPassAuditsDr[$iUnit] ?>'  tooltext='Unit: <?= $sUnit ?>{br}DR: <?= formatNumber($fFinalPassAuditsDr[$iUnit]) ?>%{br}TGI: <?= formatNumber($iFinalPassAuditsGmts[$iUnit], false) ?>{br}TGR: <?= formatNumber($iFinalPassAuditsDefects[$iUnit], false) ?>' />" +
<?
	}
?>
								"</dataset>" +

								"</chart>");


					objChart.render("UnitsChartFixed");
				-->
				</script>


			    <hr />

<?
	$Vendor     = IO::intValue("Vendor2");
	$Brand      = IO::intValue("Brand2");
	$Date       = IO::strValue("Date2");
	$Report     = IO::intValue("Report2");
	$AuditStage = IO::strValue("AuditStage2");
	$Lines      = IO::strValue("Lines2");
	$iLines     = $Lines;
	$Lines      = @explode(",", $Lines);
?>
			    <form name="frmSubSearch" id="frmSubSearch" onsubmit="return false;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="55">Vendor</td>

			          <td width="190">
			            <select id="Vendor" name="Vendor" onchange="getListValues('Vendor', 'Brand', 'VendorBrands'); getLines('Vendor', 'Line');">
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

			          <td width="45">Brand</td>

			          <td width="160">
			            <select id="Brand" name="Brand" style="width:150px;">
			              <option value="">All Brands</option>
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>

			          <td width="50">Report</td>

			          <td width="160">
					    <select name="Report">
						  <option value="">All Reports</option>
<?
	foreach ($sReportsList as $sKey => $sValue)
	{
		if (!@in_array($sKey, $iQmipReports))
			continue;
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Report) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

			          <td width="45">Stage</td>

			          <td width="120">
			            <select name="AuditStage">
			              <option value="">All Stages</option>
<?
	foreach ($sAuditStagesList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $AuditStage) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

			          <td align="right"><input type="submit" id="BtnSearch2" value="" class="btnSearch" title="Search" onclick="updateLinesGraph( );" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar" style="height:auto; padding-top:5px; padding-bottom:5px;">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr valign="top">
			          <td width="55" style="line-height:18px;">Line(s)</td>

			          <td width="250">
			            <select id="Line" name="Line" multiple size="10" style="min-width:230px;">
<?
	$sUnitSQL = "";

	if ($Vendor == 13)
	{
		$sUnitSQL     = " AND unit_id='259' ";
		$sConditions .= " AND qa.unit_id='259' ";
	}

	$sConditions .= " AND FIND_IN_SET(qa.report_id, '$sQmipReports') ";


	$sSQL = "SELECT id, line, unit_id FROM tbl_lines WHERE vendor_id='$Vendor' $sUnitSQL ORDER BY line";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iLine = $objDb->getField($i, 0);
		$sLine = $objDb->getField($i, 1);
		$iUnit = $objDb->getField($i, 2);
?>
	  	        		  <option value="<?= $iLine ?>"<?= ((@in_array($iLine, $Lines)) ? " selected" : "") ?>><?= $sLine ?><?= (($iUnit > 0 && $Vendor != 13) ? " ({$sUnitsList[$iUnit]})" : "") ?></option>
<?
	}
?>
			            </select>
			          </td>

					  <td width="35" style="line-height:18px;">Date</td>
					  <td width="78"><input type="text" name="Date" value="<?= $Date ?>" id="Date" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
					  <td></td>
				    </tr>
				  </table>
			    </div>
			    </form>
<?
	$sFromDate = date("Y-m-d", (strtotime($Date) - (16 * 86400)));
	$sToDate   = date("Y-m-d", (strtotime($Date) - 86400));


	$sConditions  = " AND qa.audit_type='B' AND qa.vendor_id='$Vendor' AND FIND_IN_SET(qa.report_id, '$sQmipReports') ";
	$sConditions .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '$sVendorBrands') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

	if ($AuditStage != "")
		$sConditions .= " AND qa.audit_stage='$AuditStage' ";

	else
		$sConditions .= " AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

	if ($Brand > 0)
		$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id='$Brand' AND vendor_id='$Vendor') ";

	if ($Report > 0)
		$sConditions .= " AND qa.report_id='$Report' ";

	else
		$sConditions .= " AND FIND_IN_SET(qa.report_id, '$sReportTypes') ";

	if ($iLines != "")
		$sConditions .= " AND FIND_IN_SET(qa.line_id, '$iLines') ";

	if ($Vendor == 13)
		$sConditions .= " AND qa.unit_id='259' ";



	$sSQL = "SELECT COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects
			 FROM tbl_qa_reports qa
			 WHERE (qa.audit_date BETWEEN '$sFromDate' AND '$sToDate') $sConditions
			 GROUP BY qa.audit_date";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$fMinDr = 0;
	$fMaxDr = 0;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");

		$fDr = @round((($iDefects / $iGmts) * 100), 2);

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


	$sLines = array( );
	$sStats = array( );


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
		}
	}
?>
			    <div class="tblSheet" id="ActivityGraphDiv" style="margin-top:5px;">
				  <div id="ActivityChart">loading...</div>
				</div>

				<br />

			    <div class="tblSheet" id="ActivityGraphDivFixed" style="margin-top:5px;">
				  <div id="ActivityChartFixed">loading...</div>
				</div>

				<script type="text/javascript">
				<!--
					var objChart = new FusionCharts("scripts/fusion-charts/charts/MSLine.swf", "Activities", "100%", "500", "0", "1");

					objChart.setXMLData("<chart caption='Daily Activity Chart (<?= formatDate($Date) ?>)' subCaption='' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fUnitDr) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='line-wise-health'>" +
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
								"</trendlines>" +

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
								"<set value='<?= (($fDr > 0 && $sStats[$iLine][$i]['Samples'] >= 20) ? formatNumber($fDr) : '') ?>' tooltext='Line: <?= $sLine ?>{br}Samples: <?= $sStats[$iLine][$i]['Samples'] ?>{br}Defects: <?= $sStats[$iLine][$i]['Defects'] ?>{br}DR: <?= formatNumber($fDr) ?>%' />" +
<?
		}
?>
								"</dataset>" +
<?
	}
?>
								"</chart>");


					objChart.render("ActivityChart");





					objChart = new FusionCharts("scripts/fusion-charts/charts/MSLine.swf", "ActivitiesFixed", "100%", "500", "0", "1");

					objChart.setXMLData("<chart caption='Daily Activity Chart (<?= formatDate($Date) ?>)' subCaption='' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fUnitDr) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='line-wise-health'>" +
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
								"</trendlines>" +

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
								"<set value='<?= (($fDr > 0 && $sStats[$iLine][$i]['Samples'] >= 20) ? formatNumber($fDr) : '') ?>' tooltext='Line: <?= $sLine ?>{br}Samples: <?= $sStats[$iLine][$i]['Samples'] ?>{br}Defects: <?= $sStats[$iLine][$i]['Defects'] ?>{br}DR: <?= formatNumber($fDr) ?>%' />" +
<?
		}
?>
								"</dataset>" +
<?
	}
?>
								"</chart>");


					objChart.render("ActivityChartFixed");
				-->
				</script>
			  </td>
			</tr>
		  </table>

<?
	@include($sBaseDir."includes/my-profile.php");
?>
        </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include($sBaseDir."includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

      </div>
    </div>
  </div>
</div>

<!--  Bottom Bar Section Starts Here  -->
<?
	@include($sBaseDir."includes/bottom-bar.php");
?>
<!--  Bottom Bar Section Ends Here  -->

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>