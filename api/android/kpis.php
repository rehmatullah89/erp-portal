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


	$User      = IO::strValue('User');
	$AuditCode = IO::strValue('AuditCode');
	$Brand     = IO::intValue('Brand');
	$Vendor    = IO::intValue('Vendor');
	$Audits    = IO::strValue('Audits');
	$DateRange = IO::strValue('DateRange');

	@list($FromDate, $ToDate) = @explode(":", $DateRange);

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
		$iPoId = getDbValue("po_id", "tbl_qa_reports", "audit_code='$AuditCode'");

		if ($iPoId > 0)
		{
			$sSQL = "SELECT vendor_id, brand_id FROM tbl_po WHERE id='$iPoId'";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1)
			{
				$Vendor = $objDb->getField(0, "vendor_id");
				$Brand  = $objDb->getField(0, "brand_id");
			}


			$sAuditDate = getDbValue("audit_date", "tbl_qa_reports", "audit_code='$AuditCode'");
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN"
    "http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">

<html lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>

  <script type="text/javascript" src="scripts/fusion-charts/jquery.min.js"></script>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
</head>

<body>

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

<section id="Quonda">
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




<section id="Protoware">
  <h1 class="gray">Protoware<sup>&reg;</sup></h1>

  <div>
    <div id="ProtowareChart">loading...</div>
  </div>
</section>

<section id="ProtowareBar">
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


</body>

<script type="text/javascript">
<!--
	FusionCharts.setCurrentRenderer('javascript');

	var iGraphHeight = "300";


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
			objQuondaChart.setXMLData(sResponse);
			objQuondaChart.render("QuondaChart");
		},

		"text");
	}


	function showProtowareGraph( )
	{
		if( FusionCharts("ProtowareGraph"))
			FusionCharts("ProtowareGraph").dispose( );

		var objProtowareChart = new FusionCharts("scripts/fusion-charts/charts/Doughnut2D.swf", "ProtowareGraph", "100%", iGraphHeight, "0", "1");


		$.post("<?= (SITE_URL.ANDROID_APP_PATH) ?>protoware/protoware-graph-xml.php",
		{
		    User       :  "<?= $User ?>",
			AuditCode  :  $("#AuditCode").val( ),
			Brand      :  $("#Brand").val( ),
			Vendor     :  $("#Vendor").val( ),
			DateRange  :  $("#ProtowareBar #DateRange").val( ),
			Audits     :  (($("#ProtowareBar #Inline").hasClass("selected") == true) ? "I" : (($("#ProtowareBar #Final").hasClass("selected") == true) ? "F" : ""))
		},

		function (sResponse)
		{
			objProtowareChart.setXMLData(sResponse);
			objProtowareChart.render("ProtowareChart");
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

			showQuondaGraph( );
		});


		$("#QuondaBar #Inline, #QuondaBar #Final").click(function( )
		{
			$("#AuditCode").val("");

			$("#QuondaBar #Inline").removeClass("selected");
			$("#QuondaBar #Final").removeClass("selected");
			$(this).addClass("selected");

			showQuondaGraph( );

			return false;
		});



		// Protoware

		showProtowareGraph( );


		$("#ProtowareBar #DateRange").change(function( )
		{
			$("#AuditCode").val("");

			showProtowareGraph( );
		});


		$("#ProtowareBar #Inline, #ProtowareBar #Final").click(function( )
		{
			$("#AuditCode").val("");

			$("#ProtowareBar #Inline").removeClass("selected");
			$("#ProtowareBar #Final").removeClass("selected");
			$(this).addClass("selected");

			showProtowareGraph( );

			return false;
		});


		// Common
		$("#Brand, #Vendor").change(function( )
		{
			$("#AuditCode").val("");

			showQuondaGraph( );
			showProtowareGraph( );
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