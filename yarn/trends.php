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
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr valign="top">
			  <td width="100%">
			    <h1><img src="images/h1/yarn/trends.jpg" width="100" height="20" vspace="10" alt="" title="" /></h1>

<?
	$iOrderQty = getDbValue("SUM(quantity)", "tbl_po", "brand_id='167'");
	$iShipQty  = getDbValue("SUM(psd.quantity)", "tbl_po po, tbl_pre_shipment_detail psd", "po.id=psd.po_id AND po.brand_id='167'");
?>
				<div class="tblSheet">
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
				    <tr valign="top">
				      <td width="20%" bgcolor="#ffffff">
					    <h2 style="background:#666666;">Cumulative Stats</h2>

					    <div style="padding:0px 0px 12px 8px;">
					    <table border="0" cellpadding="5" cellspacing="0" width="100%">
						  <tr>
						    <td width="70"><b>Order Qty</b></td>
						    <td><?= formatNumber($iOrderQty, false) ?></td>
						  <tr>

						  <tr>
						    <td><b>Ship Qty</b></td>
						    <td><?= formatNumber($iShipQty, false) ?></td>
						  <tr>
					    </table>
					    </div>
					  </td>

<?
	$sBackgrounds = array("#a6a6a6", "#b7b7b7", "#d0d0d0", "#e8e8e8");
	$sHeadings    = array("#303030", "#454545", "#585757", "#ff9000");

	$iMonth       = date("n");
	$iStart       = ((@ceil($iMonth / 3) * 3) - 2);


	for ($i = 0; $i < 4; $i ++)
	{
		$sFirstDate = date("Y-m-01", mktime(0, 0, 0, ($iStart - ((3 - $i) * 3)), 1, date("Y")));
		$sLastDate  = date("Y-m-t", mktime(0, 0, 0, ($iStart - ((3 - $i) * 3) + 2), 1, date("Y")));

		$iMonth     = date("n", strtotime($sLastDate));
		$iYear      = date("Y", strtotime($sLastDate));
		$iQuarter   = @ceil($iMonth / 3);

		switch ($iQuarter)
		{
			case 1 : $sQuarter = "I"; break;
			case 2 : $sQuarter = "II"; break;
			case 3 : $sQuarter = "III"; break;
			case 4 : $sQuarter = "IV"; break;
		}


		$iOrderQty = getDbValue("SUM(pc.order_qty)", "tbl_po po, tbl_po_colors pc", "po.id=pc.po_id AND po.brand_id='167' AND (pc.etd_required BETWEEN '$sFirstDate' AND '$sLastDate')");
		$iShipQty  = getDbValue("SUM(psq.quantity)", "tbl_po po, tbl_po_colors pc, tbl_pre_shipment_quantities psq", "po.id=psq.po_id AND po.id=pc.po_id AND psq.color_id=pc.id AND po.brand_id='167' AND (pc.etd_required BETWEEN '$sFirstDate' AND '$sLastDate')");
?>

				      <td width="20%" bgcolor="<?= $sBackgrounds[$i] ?>">
					    <div id="Quarter<?= $i ?>">
					      <h2 style="background:<?= $sHeadings[$i] ?>;"><?= $iYear ?> - Quarter <?= $sQuarter ?></h2>

					      <div style="padding:0px 0px 12px 8px;">
					      <table border="0" cellpadding="5" cellspacing="0" width="100%">
						    <tr>
						      <td width="70"><b>Order Qty</b></td>
						      <td><?= formatNumber($iOrderQty, false) ?></td>
						    <tr>

						    <tr>
						      <td><b>Ship Qty</b></td>
						      <td><?= formatNumber($iShipQty, false) ?></td>
						    <tr>
					      </table>
					      </div>
					    </div>

					    <script type="text/javascript">
					    <!--
							new Tip("Quarter<?= $i ?>",
									{
									   ajax:
									   {
										   url     : 'ajax/yarn/get-stats.php?FromDate=<?= $sFirstDate ?>&ToDate=<?= $sLastDate ?>',
										   options : { method:'get', onComplete: function( ) {  } }
									   },

									   title:'<?= $iYear ?> - Quarter <?= $sQuarter ?> Loom Plan', stem:'bottomLeft', closeButton:true, hideOthers:true, hook:{ tip:'bottomLeft', mouse:true }, hideAfter:2, hideOn: { element: '.close', event: 'click' }, offset:{ x:1, y:1 }, width:800
									});
					    -->
					    </script>
					  </td>
<?
	}
?>
					</tr>
				  </table>
				</div>


				<br />


				<script type="text/javascript" src="scripts/jquery-1.7.js"></script>
				<script type="text/javascript" src="scripts/jquery.fn.gantt.js"></script>

				<link type="text/css" rel="stylesheet" href="css/gantt.css" />

				<div class="tblSheet">
				  <h2 style="margin:0px;">Production Timeline & Order Fill</h2>

				  <div class="gantt"></div>
				</div>

				<script type="text/javascript">
				<!--
					var iInterval;

					jQuery.noConflict( );

					jQuery(document).ready(function($)
					{
						$(".gantt").gantt(
						{
							source:
									[
<?
	$sSQL = "SELECT po.id, po.order_no, po.status, po.looms, MIN(pc.etd_required) AS _EtdRequired, pc.style_id, SUM(pc.order_qty) AS _OrderQty
	         FROM tbl_po po, tbl_po_colors pc
	         WHERE po.id=pc.po_id AND po.brand_id='167' AND FIND_IN_SET(po.vendor_id, '{$_SESSION['Vendors']}') AND FIND_IN_SET(po.brand_id, '{$_SESSION['Brands']}')
	         GROUP BY po.id, pc.style_id
	         ORDER BY pc.etd_required DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPoId        = $objDb->getField($i, "id");
		$sOrderNo     = $objDb->getField($i, "order_no");
		$sEtdRequired = $objDb->getField($i, "_EtdRequired");
		$iStyle       = $objDb->getField($i, "style_id");
		$iQuantity    = $objDb->getField($i, "_OrderQty");
		$iLooms       = $objDb->getField($i, "looms");
		$sStatus      = $objDb->getField($i, "status");

		$sStyle        = getDbValue("style", "tbl_styles", "id='$iStyle'");
		$sConstruction = getDbValue("greige_construction", "tbl_gf_specs", "style_id='$iStyle'");
		$iCapacity     = getDbValue("capacity", "tbl_gf_specs", "style_id='$iStyle'");

		if ($iCapacity == 0)
			$iCapacity = 350;

		$iDays  = @ceil($iQuantity / ($iCapacity * $iLooms));
		$iDays += 6;

		$iStartTime = (strtotime($sEtdRequired) - (86400 * $iDays));
		$iEndTime   = strtotime($sEtdRequired);

		$sColor = "Orange";

		if (time( ) >= $iEndTime)
		{
			if ($sStatus == "C")
				$sColor = "Green";
			else
				$sColor = "Orange";
		}


		$iAudits        = getDbValue("COUNT(*)", "tbl_qa_reports", "(po_id='$iPoId' OR FIND_IN_SET('$iPoId', additional_pos)) AND style_id='$iStyle'");
		$iAuditQuantity = getDbValue("SUM(ship_qty)", "tbl_qa_reports", "audit_result='P' AND (po_id='$iPoId' OR FIND_IN_SET('$iPoId', additional_pos)) AND style_id='$iStyle'");
		$iAuditPercent  = @ceil(($iAuditQuantity / $iQuantity) * 100);
		$iAuditPercent  = (($iAuditPercent > 100) ? 100 : $iAuditPercent);

		$iShipQuantity = getDbValue("SUM(quantity)", "tbl_pre_shipment_quantities", "po_id='$iPoId' AND color_id IN (SELECT id FROM tbl_po_colors WHERE po_id='$iPoId' AND style_id='$iStyle')");

		$iShipPercent  = @ceil(($iShipQuantity / $iQuantity) * 100);
		$iShipPercent  = (($iShipPercent > 100) ? 100 : $iShipPercent);
?>
							{
					name: "<span class='po' rel='<?= $iPoId ?>'><?= $sOrderNo ?></span>",
					desc: "<span class='style' rel='<?= $iStyle ?>'><?= ((@strlen($sStyle) > 8) ? (substr($sStyle, 0, 8)."..") : $sStyle) ?></span><?= (($iAudits > 0) ? "<span class='audits' rel='{$iPoId}|{$iStyle}'><img src='images/icons/audits.png' height='15' hspace='5' align='absmiddle' style='cursor:pointer;' /></span>" : "") ?><?= (($iShipQuantity > 0) ? "<img src='images/icons/ship.png' height='20' hspace='0' align='absmiddle' title='".formatNumber($iShipQuantity, false)."' />" : "") ?><?= (($sStatus == "C") ? "<img src='images/icons/tick.png' height='14' hspace='5' title='Completed' align='absmiddle' />" : "") ?>",
					values: [{
						from: "/Date(<?= $iStartTime ?>000)/",
						to: "/Date(<?= $iEndTime ?>000)/",

						// label: "<div rel='<?= $iPoId ?>' style='position:absolute; left:1px; top:1px; opacity:0.50; -moz-opacity:0.50; -khtml-opacity:0.50; filter:alpha(opacity=50); z-index:10; background:#00ff00; height:16px; width:<?= $iAuditPercent ?>%;'></div><div style='z-index:11111;'><?= $sOrderNo ?><?= (($iStyle > 0) ? " (D # {$sStyle})" : "") ?></div>",

						label: "<div rel='<?= $iPoId ?>' style='position:absolute; left:1px; top:1px; opacity:0.50; -moz-opacity:0.50; -khtml-opacity:0.50; filter:alpha(opacity=50); z-index:10; background:#00ff00; height:16px; width:<?= $iShipPercent ?>%;'></div><div style='z-index:11111;'><?= $sOrderNo ?><?= (($iStyle > 0) ? " (D # {$sStyle})" : "") ?></div>",
						desc: "PO # <?= $sOrderNo ?><br /><?= (($iStyle > 0) ? "D # {$sStyle}" : "") ?><br />ETD: <?= formatDate($sEtdRequired) ?><br />Ordered Qty: <?= formatNumber($iQuantity, false) ?><br />Shipped Qty: <?= formatNumber($iShipQuantity, false) ?><br /><br />Construction:<br /><?= $sConstruction ?>",
						customClass: "gantt<?= $sColor ?>",
						dataObj: {"po":"<?= $iPoId ?>"}
					}]
				}<?= (($i < ($iCount - 1)) ? "," : "") ?>
<?
	}
?>
								],

							itemsPerPage:20,
							navigate: "scroll",
							scale: "weeks",
							minScale: "days",
							maxScale: "months",
							'scrollToToday':true,

							onItemClick: function(data)
							{
								Lightview.show({ href     : ("yarn/view-loom-plan.php?Id=" + data.po),
												 rel      : "iframe",
												 title    :  "PO Details",
												 caption  :  "",
												 options  :  { autosize:true, topclose:false, width:800, height:600 }
											   });
							},

							onAddClick: function(dt, rowId)
							{

							},

							onRender: function()
							{
								jQuery(".calWeek").mouseover(function( )
								{
									var sElementId = $(this).attr("id");
									var iElementNo = $(this).find("div").text( );
									var sIdParts   = sElementId.split("-");

									new Tip(sElementId,
											{
											   ajax:
											   {
												   url     : ('ajax/yarn/get-stats.php?Year=' + sIdParts[1] + '&Week=' + iElementNo),
												   options : { method:'get', onComplete: function( ) {  } }
											   },

											   title:('Week-' + iElementNo + ' Loom Plan'), stem:'bottomLeft', closeButton:true, hideOthers:true, hook:{ tip:'bottomLeft', mouse:true }, hideAfter:2, hideOn: { element: '.close', event: 'click' }, offset:{ x:1, y:1 }, width:800
											});
								});


								jQuery(".calMonth").mouseover(function( )
								{
									var sElementId = $(this).attr("id");
									var iElementNo = $(this).text( );
									var sIdParts   = sElementId.split("-");

									new Tip(sElementId,
											{
											   ajax:
											   {
												   url     : ('ajax/yarn/get-stats.php?Year=' + sIdParts[1] + '&Month=' + iElementNo),
												   options : { method:'get', onComplete: function( ) {  } }
											   },

											   title:'Monthly Loom Plan', stem:'bottomLeft', closeButton:true, hideOthers:true, hook:{ tip:'bottomLeft', mouse:true }, hideAfter:2, hideOn: { element: '.close', event: 'click' }, offset:{ x:1, y:1 }, width:800
											});
								});


								jQuery(".calMonthEn").mouseover(function( )
								{
									var sElementId = $(this).attr("id");
									var iMonth     = $(this).attr("month");
									var iYear      = $(this).attr("year");
									var sMonth     = $(this).text( );

									new Tip(sElementId,
											{
											   ajax:
											   {
												   url     : ('ajax/yarn/get-stats.php?Year=' + iYear + '&Month=' + iMonth),
												   options : { method:'get', onComplete: function( ) {  } }
											   },

											   title:(sMonth + ' ' + iYear + ' Loom Plan'), stem:'bottomLeft', closeButton:true, hideOthers:true, hook:{ tip:'bottomLeft', mouse:true }, hideAfter:2, hideOn: { element: '.close', event: 'click' }, offset:{ x:1, y:1 }, width:800
											});
								});


								jQuery(".calDay").mouseover(function( )
								{
									var sDayId = $(this).attr("id");
									var iDayNo = $(this).find("div").text( );
									var sDate  = ($(this).attr("year") + "-" + $(this).attr("month").lpad(2, '0') + "-" + $(this).attr("day").lpad(2, '0'));

									new Tip(sDayId,
											{
											   ajax:
											   {
												   url     : ('ajax/yarn/get-stats.php?Date=' + sDate),
												   options : { method:'get', onComplete: function( ) {  } }
											   },

											   title:'Daily Loom Plan', stem:'bottomLeft', closeButton:true, hideOthers:true, hook:{ tip:'bottomLeft', mouse:true }, hideAfter:2, hideOn: { element: '.close', event: 'click' }, offset:{ x:1, y:1 }, width:800
											});
								});
							}
						});


						jQuery(".audits").live("click", function( )
						{
							var sParams = jQuery(this).attr("rel").split("|");

							Lightview.show({ href     : ("yarn/view-audits.php?PoId=" + sParams[0] + "&StyleId=" + sParams[1]),
											 rel      : "iframe",
											 title    :  "QA Details",
											 caption  :  "",
											 options  :  { autosize:true, topclose:false, width:900, height:(jQuery(window).height( ) - 150) }
										   });
						});


						jQuery(".po").live("click", function( )
						{
							var iPoId = jQuery(this).attr("rel");

							Lightview.show({ href     : ("data/view-purchase-order.php?Id=" + iPoId),
											 rel      : "iframe",
											 title    :  "PO Details",
											 caption  :  "",
											 options  :  { autosize:true, topclose:false, width:800, height:600 }
										   });
						});


						jQuery(".style").live("click", function( )
						{
							var iStyleId = jQuery(this).attr("rel");

							Lightview.show({ href     : ("yarn/view-specs.php?Id=" + iStyleId),
											 rel      : "iframe",
											 title    :  "Style Specs",
											 caption  :  "",
											 options  :  { autosize:true, topclose:false, width:700, height:600 }
										   });
						});
					});
				-->
				</script>


		        <br />


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

			    <br />

			    <div class="tblSheet">
			      <h2 style="margin:0px;">Yarn</h2>
<?
	$sSQL = "SELECT * FROM tbl_yarn_rates ORDER BY day";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$sFromDate = $objDb->getField(0, 'day');
	$sToDate   = $objDb->getField(($iCount - 1), 'day');
?>

					<div id="YarnTrends">loading...</div>

					<script type="text/javascript">
					<!--
					var objChart = new FusionCharts("scripts/fusion-charts/charts/ZoomLine.swf", "Yarn", "100%", "500", "0", "1");

					objChart.setXMLData("<chart caption='Yarn Rates (<?= formatDate($sFromDate) ?> ... <?= formatDate($sToDate) ?>)' yAxisMinValue='800' yAxisMaxValue='2400' numDivLines='10' adjustDiv='1' legendPosition='BOTTOM' palette='1' numberPrefix='PKR ' decimals='0' formatNumberScale='0' showToolTip='1' labelDisplay='AUTO' chartBottomMargin='15' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='cotton-rates'>" +
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

										"<dataset seriesName='10cd' lineThickness='2' >" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iRate = $objDb->getField($i, 'cd10');
?>
										"  <set value='<?= formatNumber($iRate, false, 0, false) ?>' />" +
<?
	}
?>
										"</dataset>" +

										"<dataset seriesName='12cd' lineThickness='2' >" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iRate = $objDb->getField($i, 'cd12');
?>
										"  <set value='<?= formatNumber($iRate, false, 0, false) ?>' />" +
<?
	}
?>
										"</dataset>" +

										"<dataset seriesName='14cd' lineThickness='2' >" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iRate = $objDb->getField($i, 'cd14');
?>
										"  <set value='<?= formatNumber($iRate, false, 0, false) ?>' />" +
<?
	}
?>
										"</dataset>" +

										"<dataset seriesName='16cd' lineThickness='2' >" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iRate = $objDb->getField($i, 'cd16');
?>
										"  <set value='<?= formatNumber($iRate, false, 0, false) ?>' />" +
<?
	}
?>
										"</dataset>" +

										"<dataset seriesName='20cd' lineThickness='2' >" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iRate = $objDb->getField($i, 'cd20');
?>
										"  <set value='<?= formatNumber($iRate, false, 0, false) ?>' />" +
<?
	}
?>
										"</dataset>" +

										"<dataset seriesName='21cd' lineThickness='2' >" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iRate = $objDb->getField($i, 'cd21');
?>
										"  <set value='<?= formatNumber($iRate, false, 0, false) ?>' />" +
<?
	}
?>
										"</dataset>" +

										"<dataset seriesName='30cd' lineThickness='2' >" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iRate = $objDb->getField($i, 'cd30');
?>
										"  <set value='<?= formatNumber($iRate, false, 0, false) ?>' />" +
<?
	}
?>
										"</dataset>" +

										"<dataset seriesName='30cm cpt' lineThickness='2' >" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iRate = $objDb->getField($i, 'cm30_cpt');
?>
										"  <set value='<?= formatNumber($iRate, false, 0, false) ?>' />" +
<?
	}
?>
										"</dataset>" +

										"<dataset seriesName='40cm' lineThickness='2' >" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iRate = $objDb->getField($i, 'cm40');
?>
										"  <set value='<?= formatNumber($iRate, false, 0, false) ?>' />" +
<?
	}
?>
										"</dataset>" +

										"<dataset seriesName='12/1 CD +70 DN Spndx' lineThickness='2' >" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iRate = $objDb->getField($i, 'cd12_spndx');
?>
										"  <set value='<?= formatNumber($iRate, false, 0, false) ?>' />" +
<?
	}
?>
										"</dataset>" +

										"<dataset seriesName='16+70Dsp' lineThickness='2' >" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iRate = $objDb->getField($i, 'dsp16_70');
?>
										"  <set value='<?= formatNumber($iRate, false, 0, false) ?>' />" +
<?
	}
?>
										"</dataset>" +

										"</chart>");

					objChart.render("YarnTrends");
					-->
					</script>
			    </div>
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