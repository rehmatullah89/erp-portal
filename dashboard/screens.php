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

	$sVendorsList     = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND sourcing='Y'");
	$sBrandsList      = getList("tbl_brands", "id", "brand", "parent_id>'0'");
	$sDefectColors    = getList("tbl_defect_types", "id", "color");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage");
	$sStageColorsList = getList("tbl_audit_stages", "code", "color");
	$sStageIdsList    = getList("tbl_audit_stages", "code", "id");

	$sDays  = IO::strValue("Days");
	$sAudit = IO::strValue("Audit");

	if ($sDays == "")
		$sDays = "7";


	$sFromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 7), date("Y")));
	$sToDate   = date("Y-m-d");

	if ($sDays == "15")
		$sFromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 15), date("Y")));
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
  <script type="text/javascript" src="scripts/jquery.jcarousellite.js"></script>
<!--
  <meta http-equiv="refresh" content="120; ,URL=<?= $_SERVER['PHP_SELF'] ?>" />
-->
</head>

<body style="min-width:1400px; margin:0px; background:#ffffff;">

<?
	if ($sAudit == "Sampling")
	{
		@include($sBaseDir."includes/dashboard/sampling-summary.php");
		@include($sBaseDir."includes/dashboard/sampling-audits.php");
	}

	else
	{
		@include($sBaseDir."includes/dashboard/quality-summary.php");
?>

  <table border="0" cellspacing="0" cellpadding="10" width="100%">
    <tr valign="top">
      <td>
<?
		@include($sBaseDir."includes/dashboard/audits-summary.php");

		if ($sAudit == "" || $sAudit == "Recent")
			@include($sBaseDir."includes/dashboard/recent-audit.php");

		else if ($sAudit == "Held")
			@include($sBaseDir."includes/dashboard/held-audit.php");

		else if ($sAudit == "Fail")
			@include($sBaseDir."includes/dashboard/fail-audit.php");
?>
      </td>

      <td width="760">
        <div style="padding:10px;">
	      <div class="step" id="Slide1" style="display:block;">
<?
		$AuditStage  = "";
		$iHeight     = 620;
		$sGraphTitle = "Overall DR";

		@include($sBaseDir."includes/dashboard/cumulative-graph.php");
?>
	      </div>

	      <div class="step" id="Slide2" style="display:none;">
<?
		$iIndex      = 1;
		$sGraphTitle = "Top 5 Defect Types";

		@include($sBaseDir."includes/dashboard/defect-types-graph.php");
?>
	      </div>

	      <div class="step" id="Slide3" style="display:none;">
<?
		$iIndex      = 1;
		$sGraphTitle = "Top 5 Defect Codes";

		@include($sBaseDir."includes/dashboard/defect-code-graph.php");
?>
	      </div>

	      <div class="step" id="Slide4" style="display:none;">
<?
		@include($sBaseDir."includes/dashboard/defect-images2.php");
?>
	      </div>
	    </div>
      </td>
    </tr>
  </table>
<?
	}
?>
</div>

<div style="font-size:14px; background:#595959; text-align:center; color:#ffffff; padding:8px; margin:10px 0px 0px 0px;">COPYRIGHTS TRIPLE TREE, INFORMATION IS PROVIDED FOR INTERNAL PURPOSES ONLY - THIS SERVICE IS PROVIDED BY THE CREATIVE AND IT DIVISION AT TRIPLE TREE SOLUTIONS</div>

<?
	$sLocation   = substr($_SERVER['PHP_SELF'], 1);
	$sNextParams = array( );

	if ($_SERVER['QUERY_STRING'] != "")
	{
		$sPrevParams = @explode("&", $_SERVER['QUERY_STRING']);

		foreach ($sPrevParams as $sParam)
		{
			@list($sKey, $sValue) = @explode("=", $sParam);

			$sNextParams[$sKey] = $sValue;
		}
	}


	if (@strpos($_SERVER['QUERY_STRING'], "Days") === FALSE)
		$sNextParams["Days"] = "7";


	if ($sNextParams["Audit"] == "" || $sNextParams["Audit"] == "Recent")
		$sNextParams["Audit"] = "Held";

	else if ($sNextParams["Audit"] == "Held")
		$sNextParams["Audit"] = "Fail";

	else if ($sNextParams["Audit"] == "Fail")
	{
		if ($bSampling == true)
			$sNextParams["Audit"] = "Sampling";

		else
		{
			$sNextParams["Audit"] = "Recent";

			if ($sNextParams["Days"] == "" || $sNextParams["Days"] == "7")
				$sNextParams["Days"] = "15";

			else
				$sNextParams["Days"] = "7";
		}
	}

	else if ($sNextParams["Audit"] == "Sampling")
	{
		$sNextParams["Audit"] = "Recent";

		if ($sNextParams["Days"] == "" || $sNextParams["Days"] == "7")
			$sNextParams["Days"] = "15";

		else
			$sNextParams["Days"] = "7";
	}


	foreach ($sNextParams as $sKey => $sValue)
		$sLocation .= (((strpos($sLocation, "?") === FALSE) ? "?" : "&")."{$sKey}={$sValue}");
?>
<script type="text/javascript">
<!--
	var iIndex = 2;

	jQuery.noConflict( );

<?
	if ($sAudit == "Sampling")
	{
?>
	jQuery(document).ready(function($)
	{
		setInterval(function( )
		{
			document.location = "<?= (SITE_URL.$sLocation) ?>";
		},

		60000);
<?
	}

	else
	{
?>
	jQuery(document).ready(function($)
	{
		setInterval(function( )
		{
			$(".step").hide('blind');
			$("#Slide" + iIndex).show('blind');

			iIndex ++;

			if (iIndex == 6)
				document.location = "<?= (SITE_URL.$sLocation) ?>";
		},

		30000);
<?
	}
?>
	});
-->
</script>

</body>
</html>
