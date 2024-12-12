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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/yarn/trends.js"></script>
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
			<h1>Cotton Features</h1>


<!--
			<script src="http://charts.wikinvest.com/wikinvest/wikichart/javascript/scripts.php" type="text/javascript"></script>

			<div id="wikichartContainer_90098659-E258-885B-67AB-BC26D41F697B">
			  <div style="width: 100%; text-align: center; vertical-align: center; margin-top: 100px;">
				<a href="http://get.adobe.com/flashplayer/">
				  <img src="http://cdn.wikinvest.com/wikinvest/images/adobe_flash_logo.gif" alt="Flash" style="border-width: 0px;"/><br/>
				  Flash Player 9 or higher is required to view the chart<br/>
				  <strong>Click here to download Flash Player now</strong>
				</a>
			  </div>
			</div>

			<script type="text/javascript">if (typeof(embedWikichart) != "undefined") {embedWikichart("http://charts.wikinvest.com/WikiChartMini.swf","wikichartContainer_90098659-E258-885B-67AB-BC26D41F697B","100%","500",{"embedCodeDate":"2011-1-25","rollingDate":"1 month","showAnnotations":"true","liveQuote":"true","ticker":"CT00"},{});}</script>
-->

			    <div class="tblSheet">
			      <h2 style="margin:0px;">Cotton</h2>

			      <div style="padding:10px;">
			        <table border="0" cellpadding="0" cellspacing="0" width="100%">
			          <tr>
			            <td width="80"><input type="button" value="3 Years" class="button" onclick="updateChart('<?= date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), (date('Y') - 3))) ?>', '<?= date('Y-m-d') ?>');" /></td>
			            <td width="75"><input type="button" value="1 Year" class="button" onclick="updateChart('<?= date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), (date('Y') - 1))) ?>', '<?= date('Y-m-d') ?>');" /></td>
			            <td width="90"><input type="button" value="6 Months" class="button" onclick="updateChart('<?= date('Y-m-d', mktime(0, 0, 0, (date('m') - 6), date('d'), date('Y'))) ?>', '<?= date('Y-m-d') ?>');" /></td>
			            <td width="90"><input type="button" value="3 Months" class="button" onclick="updateChart('<?= date('Y-m-d', mktime(0, 0, 0, (date('m') - 3), date('d'), date('Y'))) ?>', '<?= date('Y-m-d') ?>');" /></td>
			            <td width="90"><input type="button" value="1 Month" class="button" onclick="updateChart('<?= date('Y-m-d', mktime(0, 0, 0, (date('m') - 1), date('d'), date('Y'))) ?>', '<?= date('Y-m-d') ?>');" /></td>
			            <td></td>
			          </tr>
			        </table>
			      </div>
<?
	$sSQL = "SELECT * FROM tbl_cotton_rates ORDER BY day";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$sFromDate = $objDb->getField(0, 'day');
	$sToDate   = $objDb->getField(($iCount - 1), 'day');
?>

					<div id="CottonTrends">loading...</div>

					<script type="text/javascript">
					<!--
					var objChart = new FusionCharts("scripts/fusion-charts/charts/ZoomLine.swf", "Cotton", "100%", "500", "0", "1");

					objChart.setXMLData("<chart caption='Cotton Rates (<?= formatDate($sFromDate) ?> ... <?= formatDate($sToDate) ?>)' legendPosition='BOTTOM' palette='1' numberPrefix='$' decimals='3' formatNumberScale='3' showToolTip='1' labelDisplay='AUTO' chartBottomMargin='15' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='cotton-rates'>" +
										"<categories>" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$sDate = $objDb->getField($i, 'day');
?>
										"  <category label='<?= formatDate($sDate, "d-M-y") ?>' />" +
<?
	}
?>
										"</categories>" +

										"<dataset seriesName='PAK Cotton' color='AFD8F8' lineThickness='2' >" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$fPakCotton = $objDb->getField($i, 'pak_cotton');

		if ($fPakCotton == 0)
		{
			for ($j = ($i + 1); $j < $iCount; $j ++)
			{
				$fPakCotton = $objDb->getField($j, 'pak_cotton');

				if ($fPakCotton > 0)
					break;
			}
		}

		$fPakCotton /= 100;
?>
										"  <set value='<?= formatNumber($fPakCotton, true, 3) ?>' />" +
<?
	}
?>
										"</dataset>" +

										"<dataset seriesName='US Cotton (NY)' color='F6BD0F' lineThickness='2' >" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$fUsCotton = $objDb->getField($i, 'us_cotton');

		if ($fUsCotton == 0)
		{
			for ($j = ($i + 1); $j < $iCount; $j ++)
			{
				$fUsCotton = $objDb->getField($j, 'us_cotton');

				if ($fUsCotton > 0)
					break;
			}
		}

		$fUsCotton /= 100;
?>
										"  <set value='<?= formatNumber($fUsCotton, true, 3) ?>' />" +
<?
	}
?>
										"</dataset>" +

										"</chart>");

					objChart.render("CottonTrends");
					-->
					</script>
			    </div>

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