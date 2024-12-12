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
	

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ($sVendors) AND parent_id='0'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "id IN ($sBrands)");


	if ($AuditCode != "")
	{
		$sSQL = "SELECT po_id, dhu, audit_result, audit_date FROM tbl_qa_reports WHERE audit_code='$AuditCode'";
		$objDb->query($sSQL);

		$iPoId        = $objDb->getField(0, "po_id");
		$sAuditDate   = $objDb->getField(0, "audit_date");
		$sAuditResult = $objDb->getField(0, "audit_result");
		$fDr          = $objDb->getField(0, "dhu");

		if ($iPoId > 0)
		{
			$sSQL = "SELECT vendor_id, brand_id FROM tbl_po WHERE id='$iPoId'";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1)
			{
				$Vendor = $objDb->getField(0, "vendor_id");
				$Brand  = $objDb->getField(0, "brand_id");
			}
		}
	}



	$sStages = array("F");

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
			 WHERE (audit_result='P' OR audit_result='A' OR audit_result='B') AND audit_stage!='F' AND style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand')
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
			 WHERE (audit_result='P' OR audit_result='A' OR audit_result='B') AND audit_stage='F' AND style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand')
			        AND FIND_IN_SET(audit_stage, '$sAuditStages') AND FIND_IN_SET(report_id, '$sReportTypes')";
	$objDb->query($sSQL);

	$fFinalDr = @round((($objDb->getField(0, 1) / $objDb->getField(0, 0)) * 100), 2);
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

<header>
  <label for="Brand"><span>Pick </span>Brand</label>

  <select name="Brand" id="Brand">
    <option value="">All</option>
<?
	foreach ($sBrandsList as $iBrand => $sBrand)
	{
?>
    <option value="<?= $iBrand ?>"<?= (($iBrand == $Brand) ? " selected" : "") ?>><?= $sBrand ?></option>
<?
	}
?>
  </select>


  <label for="Vendor"><span>Pick </span>Vendor</label>

  <select name="Vendor" id="Vendor">
    <option value="">All</option>
<?
	foreach ($sVendorsList as $iVendor => $sVendor)
	{
?>
    <option value="<?= $iVendor ?>"<?= (($iVendor == $Vendor) ? " selected" : "") ?>><?= $sVendor ?></option>
<?
	}
?>
  </select>

  <input type="hidden" name="AuditCode" id="AuditCode" value="<?= $AuditCode ?>" />
</header>


<header style="background:#7c7c7c; padding:10px 0px 10px 0px; height:auto; margin-bottom:10px;">
  <big style="color:#ffffff; font-size:20px;">Brand Progression</big><br />
</header>

<section style="padding-top:10px;">
    <div id="BrandChart">loading...</div>

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

	function showBrandGraph( )
	{
		if( FusionCharts("BrandGraph"))
			FusionCharts("BrandGraph").dispose( );

		var objBrandChart = new FusionCharts("scripts/fusion-charts/charts/Area2D.swf", "BrandGraph", "100%", "300", "0", "1");

		$.post("<?= APP_URL ?>quonda/brand-progression-graph-xml.php",
		{
			User       :  "<?= $User ?>",
			AuditCode  :  $("#AuditCode").val( ),
			Brand      :  $("#Brand").val( ),
			Vendor     :  $("#Vendor").val( ),
			DateRange  :  "<?= $DateRange ?>",
			Audits     :  "<?= $Audits ?>"
		},

		function (sResponse)
		{
			objBrandChart.setXMLData(sResponse);
			objBrandChart.render("BrandChart");
		},

		"text");
	}


	$(document).ready(function( )
	{
		showBrandGraph( );


		$("#Brand, #Vendor").change(function( )
		{
			showBrandGraph( );
		});


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