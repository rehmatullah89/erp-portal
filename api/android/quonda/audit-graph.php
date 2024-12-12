<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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
		}
	}
?>
<!DOCTYPE html>

<html lang="en">

<head>
<?
	@include("../includes/meta-tags.php");
?>

  <script type="text/javascript" src="scripts/fusion-charts/jquery.min.js"></script>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
</head>

<body>

<section id="AuditGraph">
  <div>
    <div id="AuditChart">loading...</div>
  </div>
</section>

</body>

<script type="text/javascript">
<!--
	FusionCharts.setCurrentRenderer('javascript');


	function showAuditGraph( )
	{
		if( FusionCharts("Graph"))
			FusionCharts("Graph").dispose( );

		var objChart = new FusionCharts("scripts/fusion-charts/charts/Pie2D.swf", "Graph", "100%", 145, "0", "1");


		$.post(("<?= (SITE_URL.ANDROID_APP_PATH."quonda/") ?>audit-graph-xml.php"),
		{
			User       :  "<?= $User ?>",
			AuditCode  :  "<?= $AuditCode ?>",
			Brand      :  $("#Brand").val( ),
			Vendor     :  $("#Vendor").val( )
		},

		function (sResponse)
		{
			objChart.setXMLData(sResponse);
			objChart.render("AuditChart");
		},

		"text");
	}


	$(document).ready(function( )
	{
		showAuditGraph( );
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