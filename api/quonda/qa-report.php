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

	$AuditCode  = IO::strValue("AuditCode");
	$iAuditCode = intval(substr($AuditCode, 1));

	$sSQL = "SELECT *,
					(SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
					(SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor,
					(SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
					(SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line,
					(SELECT report FROM tbl_reports WHERE id=tbl_qa_reports.report_id) AS _ReportType
			 FROM tbl_qa_reports
			 WHERE id='$iAuditCode'";
	$objDb->query($sSQL);

	$iReportId      = $objDb->getField(0, "report_id");
	$sVendor        = $objDb->getField(0, "_Vendor");
	$sLine          = $objDb->getField(0, "_Line");
	$sAuditor       = $objDb->getField(0, "_Auditor");
	$iPoId          = $objDb->getField(0, "po_id");
	$sPo            = $objDb->getField(0, "_Po");
	$sAdditionalPos = $objDb->getField(0, "additional_pos");
	$iStyle         = $objDb->getField(0, "style_id");
	$sColors        = $objDb->getField(0, 'colors');
	$sAuditDate     = $objDb->getField(0, "audit_date");
	$sStartTime     = $objDb->getField(0, "start_time");
	$sEndTime       = $objDb->getField(0, "end_time");
	$sAuditStage    = $objDb->getField(0, "audit_stage");
	$iSampleSize    = $objDb->getField(0, "total_gmts");
	$sAuditResult   = $objDb->getField(0, "audit_result");
	$iSampleSize    = $objDb->getField(0, "total_gmts");
	$iShipQty       = $objDb->getField(0, "ship_qty");
	$iReScreenQty   = $objDb->getField(0, "re_screen_qty");
	$sComments      = $objDb->getField(0, "qa_comments");
	$fDhu           = $objDb->getField(0, "dhu");


	$sSQL = "SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id IN ($sAdditionalPos) ORDER BY order_no";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sPo .= (", ".$objDb->getField($i, 0));


	if ($iStyle == 0)
		$iStyle = getDbValue("style_id", "tbl_po_colors", "po_id='$iPoId'");


	$sSQL = "SELECT style, sub_brand_id FROM tbl_styles WHERE id='$iStyle'";
	$objDb->query($sSQL);

	$sStyle = $objDb->getField(0, 0);
	$iBrand = $objDb->getField(0, 1);


	$sBrand  = getDbValue("brand", "tbl_brands", "id='$iBrand'");
	$sReport = getDbValue("report", "tbl_reports", "id='$iReportId'");


	@list($iStartHour, $iStartMinutes) = @explode(":", $sStartTime);
	@list($iEndHour, $iEndMinutes)     = @explode(":", $sEndTime);

	if ($iStartHour >= 12)
	{
		if ($iStartHour > 12)
			$iStartHour -= 12;

		$sStartAmPm  = "PM";
	}

	else
		$sStartAmPm = "AM";


	if ($iEndHour >= 12)
	{
		if ($iEndHour > 12)
			$iEndHour -= 12;

		$sEndAmPm  = "PM";
	}

	else
		$sEndAmPm = "AM";

	$sStartTime  = (str_pad($iStartHour, 2, '0', STR_PAD_LEFT).":".str_pad($iStartMinutes, 2, '0', STR_PAD_LEFT)." ".$sStartAmPm);
	$sEndTime    = (str_pad($iEndHour, 2, '0', STR_PAD_LEFT).":".str_pad($iEndMinutes, 2, '0', STR_PAD_LEFT)." ".$sEndAmPm);
	$sAuditStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");


	switch ($sAuditResult)
	{
		case "P" : $sAuditResult = "Pass"; break;
		case "F" : $sAuditResult = "Fail"; break;
		case "H" : $sAuditResult = "Hold"; break;
		default  : $sAuditResult = "Grade {$sAuditResult}";
	}


	if ($iReportId == 6)
		$iDefects = getDbValue("SUM(defects)", "tbl_gf_report_defects", "audit_id='$iAuditCode'");

	else
		$iDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditCode'");


	$iOrderQty = getDbValue("quantity", "tbl_po", "id='$iPoId'");

	if ($sAdditionalPos != "")
		$iOrderQty += getDbValue("SUM(quantity)", "tbl_po", "id IN ($sAdditionalPos)");

	$sOrderQty = formatNumber($iOrderQty, false);
	$sComments = (($sComments == "") ? "No comments given" : $sComments);

	$sEtdRequired = getDbValue("etd_required", "tbl_po_colors", "style_id='$iStyle' AND po_id='$iPoId'", "etd_required");
	$iDaysToShip  = ((strtotime($sEtdRequired) - strtotime($sAuditDate)) / 86400);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=0.5, maximum-scale=2.0">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <script type="text/javascript" src="api/quonda/scripts/iscroll.js"></script>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>

  <style type="text/css">
  <!--
    .white td { color:#ffffff; }
  -->
  </style>
</head>

<body style="background:#ffffff url('api/images/bg.jpg') 0px 30px; margin:0px; padding:0px; width:102%;">
  <h1 style="background:#a60800; color:#ffffff; padding:0px 10px 0px 10px; font-size:16px; text-transform:none; height:auto; line-height:auto; margin:0px;">
    <span style="float:right; font-size:14px;"><?= ((@strpos($sReport, " - ") !== FALSE) ? substr($sReport, 0, strpos($sReport, " - ")) : $sReport) ?></span>
    <?= $AuditCode ?> - <?= $sVendor ?>
  </h1>

<!-- Audit Summary -->
  <div style="padding:5px; clear:both;">
  <table border="0" cellpadding="2" cellspacing="0" width="100%" class="white">
	<tr>
	  <td width="90">Auditor</td>
	  <td width="20" align="center">:</td>
	  <td><?= $sAuditor ?></td>
	</tr>

	<tr valign="top">
	  <td>PO(s)</td>
	  <td align="center">:</td>
	  <td><?= $sPo ?></td>
	</tr>

	<tr>
	  <td>Brand / Style</td>
	  <td align="center">:</td>
	  <td><?= "{$sBrand} / {$sStyle}" ?> &nbsp; <?= (($sColors != "") ? "(Color: {$sColors})" : "") ?></td>
	</tr>

	<tr>
	  <td>Audit Date</td>
	  <td align="center">:</td>
	  <td><?= formatDate($sAuditDate) ?> &nbsp; (<?= $sStartTime ?> - <?= $sEndTime ?>) &nbsp; <span style="color:#<?= (($iDaysToShip > 0) ? '00ff00' : 'ff0000') ?>;">(<?= $iDaysToShip ?> Day<?= (($iDaysToShip == 1) ? '' : 's') ?> to Ship)</span></td>
	</tr>

	<tr>
	  <td>Audit Stage</td>
	  <td align="center">:</td>
	  <td><?= $sAuditStage ?> &nbsp; (Line: <?= $sLine ?>)</td>
	</tr>

	<tr>
	  <td>Sample Size</td>
	  <td align="center">:</td>
	  <td><?= $iSampleSize ?> &nbsp; (Defects: <?= (int)$iDefects ?>)</td>
	</tr>

	<tr>
	  <td>Audit Result</td>
	  <td align="center">:</td>
	  <td><?= $sAuditResult ?></td>
	</tr>

	<tr>
	  <td>Order Quantity</td>
	  <td align="center">:</td>
	  <td><?= formatNumber($iOrderQty, false) ?></td>
	</tr>

    <tr>
      <td>Ship Qty</td>
	  <td align="center">:</td>
	  <td><?= formatNumber($iShipQty, false) ?><?= (($iReScreenQty > 0) ? (" &nbsp; (Re-Screen Qty: ".formatNumber($iReScreenQty, false).")") : "") ?></td>
	</tr>

    <tr>
	  <td>D.H.U</td>
	  <td align="center">:</td>
	  <td><?= formatNumber($fDhu) ?>%</td>
    </tr>

    <tr valign="top">
	  <td>QA Comments</td>
	  <td align="center">:</td>
	  <td>
	    <div id="Summary" style="height:15px; color:#ffffff; overflow:hidden; width:90%; background:url('<?= SITE_URL ?>images/icons/down.gif') 98% 5px no-repeat;" onclick="showComplete( );">
	      <?= nl2br($sComments) ?>
	    </div>

	    <div id="Complete" style="color:#ffffff; width:90%; display:none;  background:url('<?= SITE_URL ?>images/icons/up.gif') 98% 5px no-repeat;" onclick="showSummary( );">
	      <?= nl2br($sComments) ?>
	    </div>
	  </td>
    </tr>
  </table>
  </div>

     <script type="text/javascript">
     <!--
 		function showComplete( )
 		{
 			document.getElementById("Summary").style.display  = "none";
 			document.getElementById("Complete").style.display = "block";
 		}

 		function showSummary( )
 		{
 			document.getElementById("Complete").style.display = "none";
 			document.getElementById("Summary").style.display  = "block";
 		}
     -->
     </script>


<!-- Audit Graph -->
  <div style="background:#ffffff; padding:5px;">
<?
	$Type       = IO::intValue("Type");
	$Code       = IO::intValue("Code");
	$DefectCode = IO::intValue("DefectCode");
	$AreaCode   = IO::strValue("AreaCode");


	// Defect Area
	if ($Code > 0)
	{
		$iDefects     = array( );
		$iDefectAreas = array( );
		$sDefectAreas = array( );

		$sSQL = "SELECT da.id, da.area, COALESCE(SUM(qad.defects), 0)
				 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt, tbl_defect_areas da
				 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND da.id=qad.area_id AND dc.id='$Code'
				       AND qa.audit_type='B' AND qa.audit_result!='' AND qa.audit_code='$AuditCode' AND dt.id='$Type'
					   AND IF(qa.report_id=10, qad.nature='1', TRUE)
					   AND IF(qa.report_id=11, qad.nature<'4', TRUE)
				 GROUP BY qad.area_id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iDefectAreas[] = $objDb->getField($i, 0);
			$sDefectAreas[] = $objDb->getField($i, 1);
			$iDefects[]     = $objDb->getField($i, 2);
		}

		$sDefectCode = getDbValue("code", "tbl_defect_codes", "id='$Code'");
		$sDefectTile = getDbValue("defect", "tbl_defect_codes", "id='$Code'");
?>
						<div id="DefectAreaChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "DefectArea", "100%", "260", "0", "1");

						objChart.setXMLData("<chart caption='<?= htmlentities("[{$sDefectCode}] {$sDefectTile}", ENT_QUOTES) ?>' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='' chartBottomMargin='5' plotFillAlpha='95'>" +
<?
		for ($i = 0; $i < count($iDefects); $i ++)
		{
?>
											"<set tooltext='Area: <?= htmlentities($sDefectAreas[$i], ENT_QUOTES) ?>{br}Defects: <?= $iDefects[$i] ?>' label='<?= htmlentities($sDefectAreas[$i], ENT_QUOTES) ?>' value='<?= $iDefects[$i] ?>' link='<?= (SITE_URL."api/quonda/qa-report.php?AuditCode={$AuditCode}&Type={$Type}&Code={$Code}&AreaCode={$iDefectAreas[$i]}") ?>' />" +
<?
		}
?>
										    "</chart>");


						objChart.render("DefectAreaChart");
						-->
						</script>
<?
	}


	// Defect Classification
	else if ($Type > 0)
	{
		$iDefects     = array( );
		$sDefectCodes = array( );
		$iDefectCodes = array( );
		$sDefects     = array( );

		$sSQL = "SELECT dc.id, dc.code, dc.defect, COALESCE(SUM(qad.defects), 0)
				 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
				 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND dt.id='$Type'
				       AND qa.audit_type='B' AND qa.audit_result!='' AND qa.audit_code='$AuditCode'
					   AND IF(qa.report_id=10, qad.nature='1', TRUE)
					   AND IF(qa.report_id=11, qad.nature<'4', TRUE)
				 GROUP BY dc.id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iDefectCodes[] = $objDb->getField($i, 0);
			$sDefectCodes[] = $objDb->getField($i, 1);
			$sDefects[]     = $objDb->getField($i, 2);
			$iDefects[]     = $objDb->getField($i, 3);
		}

		$sDefectType = getDbValue("type", "tbl_defect_types", "id='$Type'");
?>
						<div id="DefectCodeChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "DefectCode", "100%", "260", "0", "1");

						objChart.setXMLData("<chart caption='<?= "{$sDefectType} Defects" ?>' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='' chartBottomMargin='5' plotFillAlpha='95'>" +
<?
		for ($i = 0; $i < count($iDefects); $i ++)
		{
?>
											"<set tooltext='<?= htmlentities($sDefects[$i], ENT_QUOTES) ?>{br}Code: <?= $sDefectCodes[$i] ?>{br}Defects: <?= $iDefects[$i] ?>' label='<?= $sDefectCodes[$i] ?>' value='<?= $iDefects[$i] ?>' link='<?= (SITE_URL."api/quonda/qa-report.php?AuditCode={$AuditCode}&Type={$Type}&".(($iReportId == 4) ? "DefectCode={$sDefectCodes[$i]}" : "Code={$iDefectCodes[$i]}")) ?>' />" +
<?
		}
?>
										    "</chart>");


						objChart.render("DefectCodeChart");
						-->
						</script>
<?
	}


	// Defect Classification
	else
	{
		$iDefects     = array( );
		$sDefectTypes = array( );
		$iDefectTypes = array( );

		$sSQL = "SELECT dt.id, dt.type, COALESCE(SUM(qad.defects), 0) AS _Defects
				 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
				 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id
				       AND qa.audit_type='B' AND qa.audit_result!='' AND qa.audit_code='$AuditCode'
					   AND IF(qa.report_id=10, qad.nature='1', TRUE)
					   AND IF(qa.report_id=11, qad.nature<'4', TRUE)
				 GROUP BY dc.type_id
				 ORDER BY dt.type";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iDefectTypes[] = $objDb->getField($i, 0);
			$sDefectTypes[] = $objDb->getField($i, 1);
			$iDefects[]     = $objDb->getField($i, 2);
		}
?>
						<div id="DefectClassChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Pie2D.swf", "DefectClass", "100%", "260", "0", "1");

						objChart.setXMLData("<chart caption='Defect Classification' showPercentageInLabel='1' formatNumberScale='0' showValues='1' showLabels='0' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' bgcolor='ffffff' bordercolor='ffffff' animation='1'>" +
<?
		for ($i = 0; $i < count($iDefectTypes); $i ++)
		{
?>
											"<set tooltext='Type: <?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?>{br}Defects: <?= $iDefects[$i] ?>' label='<?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?>' value='<?= $iDefects[$i] ?>' link='<?= (SITE_URL."api/quonda/qa-report.php?AuditCode={$AuditCode}&Type={$iDefectTypes[$i]}") ?>' />" +
<?
		}
?>

										    "</chart>");


						objChart.render("DefectClassChart");
						-->
						</script>
<?
	}
?>
  </div>


<!-- Audit Images -->
  <div id="DefectImages" style="position:absolute; z-index:1; left:5px; right:5px; bottom:0px; height:110px; width:100%;">
    <div id="Scroller" style="position:absolute; z-index:1; width:100%; height:86px; padding:0px; margin:0px;">
      <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr valign="top">
<?
	@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

	$sAuditPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($AuditCode, 1)."_*.*");
	$sAuditPictures = @array_map("strtoupper", $sAuditPictures);
	$iLength        = strlen($AuditCode);

	for ($i = 0; $i < count($sAuditPictures); $i ++)
	{
		$sName  = @strtoupper($sAuditPictures[$i]);
		$sName  = @basename($sName, ".JPG");
		$sName  = @basename($sName, ".GIF");
		$sName  = @basename($sName, ".PNG");
		$sName  = @basename($sName, ".BMP");
		$sParts = @explode("_", $sName);

		if (substr(@basename($sAuditPictures[$i]), 0, ($iLength + 4)) == "{$AuditCode}_001" ||
		    substr(@basename($sAuditPictures[$i]), 0, ($iLength + 4)) == "{$AuditCode}_00_" ||
		    strlen(@basename($sAuditPictures[$i])) < ($iLength + 6))
			continue;


		if ($Type > 0 || $Code > 0 || $DefectCode > 0 || $AreaCode > 0)
		{
			if ($Type > 0 && $Code > 0 && $AreaCode > 0 && intval($sParts[2]) == $AreaCode && $sParts[1] == $sDefectCode)
				$sPictures[] = $sAuditPictures[$i];

			else if ($Type > 0 && $Code == 0 && $iReportId == 4 && $sParts[1] == $DefectCode)
				$sPictures[] = $sAuditPictures[$i];

			else if ($Type > 0 && $Code > 0 && $iReportId != 4 && $sParts[1] == $sDefectCode)
				$sPictures[] = $sAuditPictures[$i];

			else if ($Type > 0 && @in_array($sParts[1], $sDefectCodes))
				$sPictures[] = $sAuditPictures[$i];
		}

		else
			$sPictures[] = $sAuditPictures[$i];
	}



	if (count($sPictures) == 0)
	{
?>
	      <td><div style="color:#ffffff; font-size:13px; font-weight:bold;">No Defect Image Found!</div></td>
<?
	}

	else
	{
		for ($i = 0; $i < count($sPictures); $i ++)
		{
?>
		  <td width="105"><a href="<?= SITE_URL ?>api/quonda/qa-image.php?AuditCode=<?= $AuditCode ?>&Picture=<?= @basename($sPictures[$i]) ?>" style="background:#ffffff;"><img src="<?= SITE_URL.str_replace('../', '', $sPictures[$i]) ?>" width="100" height="75" alt="" title="" style="border:solid 1px #ffffff;" /></a></td>
<?
		}
	}
?>
          <td></td>
        </tr>
      </table>
<?
	if (count($sPictures) > 0)
	{
?>
     <script type="text/javascript">
     <!--
		var myScroll;

		function loaded( )
		{
			myScroll = new iScroll('Scroller', { hScroll:true, hScrollbar:true, vScroll:true, vScrollbar:false, hideScrollbar:false });
		}

		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
		document.addEventListener('DOMContentLoaded', loaded, false);
     -->
     </script>
<?
	}
?>
    </div>

    <h1 style="position:absolute; width:100%; top:86px; background:#a60800; color:#ffffff; height:25px; line-height:25px; font-size:13px;">Defect Images</h1>
  </div>


<?
	if ($Type > 0 || $Code > 0 || $AreaCode != "")
	{
		if ($AreaCode != "")
			$AreaCode = "";

		else if ($Code > 0)
			$Code = 0;

		else if ($Type > 0)
			$Type = 0;
?>
     <div style="padding:10px 0px 10px 10px;">
       <input type="button" value=" Back " class="button" style="font-size:16px; padding:5px 10px 5px 10px;" onclick="document.location='<?= (SITE_URL."api/quonda/qa-report.php?AuditCode={$AuditCode}&Type={$Type}&Code={$Code}") ?>';" />
     </div>
<?
	}
?>

  <br />
</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>