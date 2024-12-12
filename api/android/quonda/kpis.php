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

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$User      = IO::strValue('User');
	$AuditCode = IO::strValue('AuditCode');
	$Brand     = IO::intValue('Brand');
	$Vendor    = IO::intValue('Vendor');
	$Audits    = IO::strValue('Audits');
	$DateRange = IO::strValue('DateRange');
	$App       = IO::strValue('App');

	if ($User == "")
		die("Invalid Request");


	$sSQL = "SELECT id, vendors, brands, style_categories, status FROM tbl_users WHERE MD5(id)='$User'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		die("Invalid User");

	else if ($objDb->getField(0, "status") != "A")
		die("User Account is Disabled");


	$iUser            = $objDb->getField(0, "id");
	$sBrands          = $objDb->getField(0, "brands");
	$sVendors         = $objDb->getField(0, "vendors");
	$sStyleCategories = $objDb->getField(0, "style_categories");

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


	switch ($sAuditResult)
	{
		case "P"  : $sAuditColor = "#83ae00"; break;
		case "F"  : $sAuditColor = "#ff0000"; break;
		case "H"  : $sAuditColor = "#eebb22"; break;
		case "A"  : $sAuditColor = "#83ae00"; break;
		case "B"  : $sAuditColor = "#83ae00"; break;
		case "C"  : $sAuditColor = "#ff0000"; break;
		default   : $sAuditColor = "#898989";
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

<body style="background:#ffffff;">

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

<section id="Quonda"<?= (($App == "IOS") ? 'style="height:240px;"' : '') ?>>
  <h1>Quonda<sup>&reg;</sup></h1>

  <div>
    <div id="QuondaChart">loading...</div>
  </div>
</section>

<section id="QuondaBar">
<?
	$sToday       = date("Y-m-d");
	$sYesterday   = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y")));
	$sLastWeek    = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 7), date("Y")));
	$sLastMonth   = date("Y-m-d", mktime(0, 0, 0, (date("m") - 1), date("d"), date("Y")));
	$sLastQuarter = date("Y-m-d", mktime(0, 0, 0, (date("m") - 3), date("d"), date("Y")));
?>
  <select id="DateRange">
<?
	if ($sAuditDate != "")
	{
?>
	<option value="<?= $sAuditDate ?>:<?= $sAuditDate ?>"><?= $sAuditDate ?></option>
<?
	}
?>
	<option value="<?= $sToday ?>:<?= $sToday ?>"<?= (($DateRange == "{$sToday}:{$sToday}") ? " selected" : "") ?>>Today</option>
	<option value="<?= $sYesterday ?>:<?= $sYesterday ?>"<?= (($DateRange == "{$sYesterday}:{$sYesterday}") ? " selected" : "") ?>>Yesterday</option>
	<option value="<?= $sLastWeek ?>:<?= $sToday ?>"<?= (($DateRange == "{$sLastWeek}:{$sToday}") ? " selected" : "") ?>>Last Week</option>
	<option value="<?= $sLastMonth ?>:<?= $sToday ?>"<?= (($DateRange == "{$sLastMonth}:{$sToday}") ? " selected" : "") ?>>Last Month</option>
	<option value="<?= $sLastQuarter ?>:<?= $sToday ?>"<?= (($DateRange == "{$sLastQuarter}:{$sToday}") ? " selected" : "") ?>>Last Quarter</option>
  </select>

  <div class="fRight">
    Inspection Type:&nbsp;
    <a href="<?= (SITE_URL.substr($_SERVER['REQUEST_URI'], 1)) ?>" id="Inline">INLINE</a>
    &nbsp;|&nbsp;
    <a href="<?= (SITE_URL.substr($_SERVER['REQUEST_URI'], 1)) ?>" id="Final">FINAL</a>
  </div>
</section>


<section id="Actions">
  <table border="3" bordercolor="#ffffff" cellspacing="0" cellpadding="10" width="100%" style="border-collapse:collapse; border-spacing:0; table-layout:fixed;">
    <tr>
      <td width="70%" bgcolor="#4b7100" align="center" style="font-size:18px; color:#ffffff;" onclick="showStatsImages( );" id="KeyStats">KEY STATS & IMAGES</td>
      <td width="30%" bgcolor="<?= $sAuditColor ?>" align="center" style="font-size:60px; color:#ffffff; border-left:solid 3px #ffffff;" id="AuditResult"><?= (($sAuditResult == "") ? "&nbsp;" : $sAuditResult) ?></td>
    </tr>

    <tr>
      <td width="100%" colspan="2" bgcolor="#83ae00" align="center" style="font-size:18px; color:#ffffff; border-top:solid 4px #ffffff; padding:19px 0px 19px 0px;" id="AuditProgression" onclick="showAuditProgressionGraph( );">AUDIT PROGRESSION HISTORY</td>
    </tr>
  </table>

  <table border="3" bordercolor="#ffffff" cellspacing="0" cellpadding="15" width="100%" style="border-collapse:collapse; border-spacing:0; table-layout:fixed; border-top:none;">
    <tr>
      <td width="35%" bgcolor="#ff7900" align="center" style="font-size:<?= (($App == "IOS") ? "27" : "36") ?>px; color:#ffffff;" id="Dr"><?= formatNumber($fDr, true, 1) ?>%</td>
      <td width="65%" bgcolor="#7a7a7a" align="center" style="font-size:18px; color:#ffffff; border-left:solid 3px #ffffff;" id="BrandProgression" onclick="showBrandProgressionGraph( );">BRAND PROGRESSION AT VENDOR</td>
    </tr>
  </table>
</section>


</body>

<script type="text/javascript">
<!--
	FusionCharts.setCurrentRenderer('javascript');

	var iGraphHeight = "300";


	function showStatsImages( )
	{
		if (typeof Android != 'undefined')
			Android.showDefectImages($("#Brand").val( ), $("#Vendor").val( ), $("#QuondaBar #DateRange").val( ), (($("#QuondaBar #Inline").hasClass("selected") == true) ? "I" : (($("#QuondaBar #Final").hasClass("selected") == true) ? "F" : "")), $("#AuditCode").val( ));
	}


	function showAuditProgressionGraph( )
	{
		if (typeof Android != 'undefined' && $("#AuditCode").val( ) != "")
			Android.showAuditProgression($("#Brand").val( ), $("#Vendor").val( ), $("#QuondaBar #DateRange").val( ), (($("#QuondaBar #Inline").hasClass("selected") == true) ? "I" : (($("#QuondaBar #Final").hasClass("selected") == true) ? "F" : "")), $("#AuditCode").val( ));
	}

	function showBrandProgressionGraph( )
	{
		if (typeof Android != 'undefined' && $("#Brand").val( ) != "")
			Android.showBrandProgression($("#Brand").val( ), $("#Vendor").val( ), $("#QuondaBar #DateRange").val( ), (($("#QuondaBar #Inline").hasClass("selected") == true) ? "I" : (($("#QuondaBar #Final").hasClass("selected") == true) ? "F" : "")), $("#AuditCode").val( ));
	}

	function showDefectTypesGraph( )
	{
		if (typeof Android != 'undefined')
			Android.showDefectTypes($("#Brand").val( ), $("#Vendor").val( ), $("#QuondaBar #DateRange").val( ), (($("#QuondaBar #Inline").hasClass("selected") == true) ? "I" : (($("#QuondaBar #Final").hasClass("selected") == true) ? "F" : "")), $("#AuditCode").val( ));
	}


	function showQuondaGraph( )
	{
		if( FusionCharts("QuondaGraph"))
			FusionCharts("QuondaGraph").dispose( );

		var objQuondaChart = new FusionCharts("scripts/fusion-charts/charts/Doughnut2D.swf", "QuondaGraph", "100%", iGraphHeight, "0", "1");


		$.post("<?= (SITE_URL.ANDROID_APP_PATH) ?>quonda/defect-rate-graph-xml.php",
		{
			User       :  "<?= $User ?>",
			AuditCode  :  $("#AuditCode").val( ),
			Brand      :  $("#Brand").val( ),
			Vendor     :  $("#Vendor").val( ),
			DateRange  :  $("#QuondaBar #DateRange").val( ),
			Audits     :  (($("#QuondaBar #Inline").hasClass("selected") == true) ? "I" : (($("#QuondaBar #Final").hasClass("selected") == true) ? "F" : ""))
		},

		function (sResponse)
		{
			try
			{
				var sDr = sResponse.substr(sResponse.indexOf("DR "), 8);

				sDr = sDr.replace("DR ", "");
				sDr = sDr.trim( );

				$("#Dr").text(sDr);
			}

			catch (err)
			{
			}


			objQuondaChart.setXMLData(sResponse);
			objQuondaChart.render("QuondaChart");
		},

		"text");
	}


	$(document).ready(function( )
	{
		if ($(window).width( ) <= 400)
			iGraphHeight = "180";


		// Quonda
		showQuondaGraph( );


		$("#QuondaBar #DateRange").change(function( )
		{
			$("#AuditCode").val("");
			$("#AuditResult").css("background", "#898989");
			$("#AuditResult").html("&nbsp;");

			showQuondaGraph( );
		});


		$("#QuondaBar #Inline, #QuondaBar #Final").click(function( )
		{
			$("#AuditCode").val("");
			$("#AuditResult").css("background", "#898989");
			$("#AuditResult").html("&nbsp;");

			$("#QuondaBar #Inline").removeClass("selected");
			$("#QuondaBar #Final").removeClass("selected");
			$(this).addClass("selected");

			showQuondaGraph( );

			return false;
		});



		// Common
		$("#Brand, #Vendor").change(function( )
		{
			$("#AuditCode").val("");

			showQuondaGraph( );
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