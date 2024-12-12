<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree QUONDA App                                                                   **
	**  Version 3.0                                                                              **
	**                                                                                           **
	**  http://app.3-tree.com                                                                    **
	**                                                                                           **
	**  Copyright 2008-17 (C) Triple Tree                                                        **
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
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$User      = IO::strValue('User');
	$AuditCode = IO::strValue('AuditCode');
	$Brand     = IO::intValue('Brand');
	$Vendor    = IO::intValue('Vendor');
	$Audits    = IO::strValue('Audits');
	$DateRange = IO::strValue('DateRange');

	if ($User == "")
		die("Invalid Request");


	$sSQL = "SELECT id, vendors, brands, style_categories, report_types, audit_stages, status, guest FROM tbl_users WHERE MD5(id)='$User'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		die("Invalid User");

	else if ($objDb->getField(0, "status") != "A")
		die("User Account is Disabled");


	$iUser            = $objDb->getField(0, "id");
	$sBrands          = $objDb->getField(0, "brands");
	$sVendors         = $objDb->getField(0, "vendors");
	$sStyleCategories = $objDb->getField(0, "style_categories");
	$sReportTypes     = $objDb->getField(0, "report_types");
	$sAuditStages     = $objDb->getField(0, "audit_stages");
	$sGuest           = $objDb->getField(0, "guest");


	$sStages = array( );

	if ($AuditCode != "")
	{
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
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN"
    "http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">

<html lang="en">

<head>
<?
	@include("../includes/meta-tags.php");
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
		if( FusionCharts("AuditsGraph"))
			FusionCharts("AuditsGraph").dispose( );

		var objAuditsChart = new FusionCharts("scripts/fusion-charts/charts/Area2D.swf", "AuditsGraph", "100%", "300", "0", "1");

		$.post("<?= APP_URL ?>quonda/audit-progression-graph-xml.php",
		{
			User       :  "<?= $User ?>",
			AuditCode  :  "<?= $AuditCode ?>",
			Brand      :  "<?= $Brand ?>",
			Vendor     :  "<?= $Vendor ?>",
			DateRange  :  "<?= $DateRange ?>",
			Audits     :  "<?= $Audits ?>"
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


		$(document).ajaxStart(function( )
		{
			if (typeof Android != 'undefined')
				Android.showProgressBox( );
		});

		$(document).ajaxStop(function( )
		{
			if (typeof Android != 'undefined')
				Android.hideProgressBox( );
		});
	});

-->
</script>

</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>