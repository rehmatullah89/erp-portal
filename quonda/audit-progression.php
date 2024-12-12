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
	$objDb3      = new Database( );

	
	$AuditCode = ("S".IO::intValue('Id'));
	
	
	$sSQL = "SELECT id, vendors, brands, style_categories, report_types, audit_stages, status FROM tbl_users WHERE id='{$_SESSION['UserId']}'";
	$objDb->query($sSQL);

	$sBrands          = $objDb->getField(0, "brands");
	$sVendors         = $objDb->getField(0, "vendors");
	$sStyleCategories = $objDb->getField(0, "style_categories");
	$sReportTypes     = $objDb->getField(0, "report_types");
	$sAuditStages     = $objDb->getField(0, "audit_stages");


	$sStages = array( );

	$sSQL = "SELECT po_id, style_id FROM tbl_qa_reports WHERE audit_code='$AuditCode'";
	$objDb->query($sSQL);

	$iPo    = $objDb->getField(0, "po_id");
	$iStyle = $objDb->getField(0, "style_id");

	if ($iStyle == 0)
		$iStyle = getDbValue("style_id", "tbl_po_colors", "po_id='$iPo'");


	$sSQL = "SELECT style, sub_brand_id, sub_season_id FROM tbl_styles WHERE id='$iStyle'";
	$objDb->query($sSQL);

	$sStyle  = $objDb->getField(0, 0);
	$iBrand  = $objDb->getField(0, 1);
	$iSeason = $objDb->getField(0, 2);


	$sBrand  = getDbValue("brand", "tbl_brands", "id='$iBrand'");
	$sSeason = getDbValue("season", "tbl_seasons", "id='$iSeason'");


	$sSQL = "SELECT audit_stage
			 FROM tbl_qa_reports
			 WHERE audit_result!='' AND style_id>'0' AND style_id='$iStyle'
			 ORDER BY audit_date DESC
			 LIMIT 10";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAuditStage = $objDb->getField($i, 'audit_stage');

		if (!@in_array($sAuditStage, $sStages))
			$sStages[] = $sAuditStage;
	}


	$sSQL = "SELECT SUM( IF ( tbl_qa_reports.report_id='6',
							  (SELECT COALESCE(SUM(actual_1 + actual_2 + actual_3), 0) FROM tbl_gf_rolls_info WHERE audit_id=tbl_qa_reports.id),
							  tbl_qa_reports.total_gmts
							)
					   ) AS _TotalGmts,

					SUM(
						  IF ( tbl_qa_reports.report_id='6',
							   (SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=tbl_qa_reports.id),
							   (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND nature>'0')
							 )
					   ) AS _TotalDefects
			 FROM tbl_qa_reports
			 WHERE audit_result!='' AND style_id>'0' AND style_id='$iStyle' AND audit_stage!='F'
				   AND FIND_IN_SET(audit_stage, '$sAuditStages') AND FIND_IN_SET(report_id, '$sReportTypes')";
	$objDb->query($sSQL);

	$fInlineDr = @round((($objDb->getField(0, 1) / $objDb->getField(0, 0)) * 100), 2);


	$sSQL = "SELECT SUM( IF ( tbl_qa_reports.report_id='6',
							  (SELECT COALESCE(SUM(actual_1 + actual_2 + actual_3), 0) FROM tbl_gf_rolls_info WHERE audit_id=tbl_qa_reports.id),
							  tbl_qa_reports.total_gmts
							)
					   ) AS _TotalGmts,

					SUM(
						  IF ( tbl_qa_reports.report_id='6',
							   (SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=tbl_qa_reports.id),
							   (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND nature>'0')
							 )
					   ) AS _TotalDefects
			 FROM tbl_qa_reports

			 WHERE audit_result!='' AND style_id>'0' AND style_id='$iStyle' AND audit_stage='F'
				   AND FIND_IN_SET(audit_stage, '$sAuditStages') AND FIND_IN_SET(report_id, '$sReportTypes')";
	$objDb->query($sSQL);

	$fFinalDr = @round((($objDb->getField(0, 1) / $objDb->getField(0, 0)) * 100), 2);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir.ANDROID_APP_PATH."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/fusion-charts/jquery.min.js"></script>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
</head>

<body style="background:#dedede; min-height:500px;">
  <header style="background:#7c7c7c; padding:10px 0px 10px 0px; height:auto; margin-bottom:10px;">
    <big style="color:#ffffff; font-size:20px;">Audit Progression</big><br />
    <big style="color:#ffffff; font-size:14px;">Style # <b style="color:#ffffff; font-size:14px;"><?= $sStyle ?></b> (<?= $sBrand ?>, <?= $sSeason ?>)</big><br />
  </header>

  <section>
    <div id="AuditsChart">loading...</div>

    <br />

    <table border="0" cellpadding="5" cellspacing="0" width="100%">
      <tr bgcolor="#7c7c7c">
        <td width="35%"><b style="color:#ffffff; font-size:13px;">Avg. DR at<br />INLINE Audits</b></td>
        <td width="15%" bgcolor="#ffffff" align="center"><b style="color:#7c7c7c; font-size:14px;"><?= formatNumber($fInlineDr) ?>%</b></td>
        <td width="35%"><b style="color:#ffffff; font-size:13px;">Avg. DR at<br />FINAL Audits</b></td>
        <td width="15%" bgcolor="#ffffff" align="center"><b style="color:#7c7c7c; font-size:14px;"><?= formatNumber($fFinalDr) ?>%</b></td>
      </tr>
    </table>

    <ul id="Ledends">
      <li><div style="background:#ffffff; border:solid 2px #83af00;"></div><span>Pass Audit</span></li>
      <li><div style="background:#ffffff; border:solid 2px #e40001;"></div><span>Fail Audit</span></li>
<?
	$sAuditStages = getList("tbl_audit_stages", "code", "stage");
	$sStageColors = getList("tbl_audit_stages", "code", "color");

	foreach ($sAuditStages as $sCode => $sAuditStage)
	{
		if (@in_array($sCode, $sStages))
		{
?>
	  <li><div style="background:<?= $sStageColors[$sCode] ?>;"></div><span><?= $sAuditStage ?></span></li>
<?
		}
	}
?>
    </ul>
  </section>
</body>

<script type="text/javascript">
<!--
	FusionCharts.setCurrentRenderer('javascript');


	function showAuditsGraph( )
	{
		var objAuditsChart = new FusionCharts("scripts/fusion-charts/charts/Area2D.swf", "AuditsGraph", "100%", "350", "0", "0");

		$.post("<?= (SITE_URL.ANDROID_APP_PATH) ?>quonda/audit-progression-graph-xml.php",
		{
			User       :  "<?= md5($_SESSION['UserId']) ?>",
			AuditCode  :  "<?= $AuditCode ?>",
			Brand      :  "",
			Vendor     :  "",
			DateRange  :  "",
			Audits     :  ""
		},

		function (sResponse)
		{
			objAuditsChart.setXMLData(sResponse);
			objAuditsChart.render("AuditsChart");
		},

		"text");
	}


	$(document).ready(function( )
	{
		showAuditsGraph( );
	});

-->
</script>

</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>