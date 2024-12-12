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

	$Vendor = IO::intValue("Vendor");
	$Month  = IO::intValue("Month");
	$Year   = IO::intValue("Year");

	$sVendorsList = getList("tbl_vendors v", "v.id", "CONCAT(COALESCE((SELECT CONCAT(vendor, ' &raquo;&raquo; ') FROM tbl_vendors WHERE id=v.parent_id), ''), v.vendor)", "v.id IN ({$_SESSION['Vendors']}) AND (v.category_id='4' OR v.category_id='5') AND v.sourcing='Y'");
	$sMonthsList  = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/ot-analysis.js"></script>
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
			    <h1>Over Time Analysis</h1>


			    <form name="frmData" id="frmData" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" class="frmOutline" onsubmit="$('BtnSubmit').disabled=true;">
				<h2>OT Analysis</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="55">Vendor<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Vendor">
						<option value=""></option>
<?
	foreach ($sVendorsList as $sKey => $sValue)
	{
?>
	  	        		<option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Month<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Month">
						<option value=""></option>
<?
	for ($i = 1; $i <= 12; $i ++)
	{
?>
			            <option value="<?= $i ?>"<?= (($i == $Month) ? " selected" : "") ?>><?= $sMonthsList[($i - 1)] ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Year<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
			          <select name="Year">
			            <option value=""></option>
<?
	for ($i = 2010; $i <= date("Y"); $i ++)
	{
?>
			            <option value="<?= $i ?>"<?= (($i == $Year) ? " selected" : "") ?>><?= $i ?></option>
<?
	}
?>
		              </select>
					</td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSubmit" value="" class="btnSubmit" title="Submit" onclick="return validateForm( );" /></div>
			    </form>

<?
	if ($_GET)
	{
		$sClass      = array("evenRow", "oddRow");
		$iCategoryId = getDbValue("category_id", "tbl_vendors", "id='$Vendor'");

		if ($iCategoryId == 4)
		{
			$iTotalEmployees = getDbValue("SUM(employees)", "tbl_ot_apparel", "vendor_id='$Vendor' AND year='$Year' AND month='$Month'");
			$iTotalWeeks     = getDbValue("COUNT(DISTINCT(week))", "tbl_ot_apparel", "vendor_id='$Vendor' AND year='$Year' AND month='$Month'");
			$iEmployees      = @round($iTotalEmployees / $iTotalWeeks);


			$sSQL = "SELECT SUM(hrs_0), SUM(hrs_0_12), SUM(hrs_12_24), SUM(hrs_24), SUM(sunday_rest_days) FROM tbl_ot_apparel WHERE vendor_id='$Vendor' AND year='$Year' AND month='$Month'";
			$objDb->query($sSQL);

			$iZeroHrs = $objDb->getField(0, 0);
			$i12Hrs   = $objDb->getField(0, 1);
			$i1224Hrs = $objDb->getField(0, 2);
			$i24Hrs   = $objDb->getField(0, 3);
			$iRest    = $objDb->getField(0, 4);

			$fZeroHrs = @((($iZeroHrs / $iTotalWeeks) / $iEmployees) * 100);
			$f12Hrs   = @((($i12Hrs / $iTotalWeeks) / $iEmployees) * 100);
			$f1224Hrs = @((($i1224Hrs / $iTotalWeeks) / $iEmployees) * 100);
			$f24Hrs   = @((($i24Hrs / $iTotalWeeks) / $iEmployees) * 100);
			$fRest    = @((($iRest / $iTotalWeeks) / $iEmployees) * 100);
?>
				<br />

				<div class="tblSheet">
				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="20%" class="center">% of workers worked  in Zero Hrs Category</td>
				      <td width="20%" class="center">% of workers worked  for < 12 Hrs in a week</td>
				      <td width="20%" class="center">% of workers worked  for more than 12 Hrs   & < 24 Hrs in a week</td>
				      <td width="20%" class="center">% of workers worked for more than 24 hrs in a week</td>
				      <td width="20%" class="center">% of workers worked on weekly Rest</td>
				    </tr>

				    <tr class="evenRow">
				      <td class="center"><?= formatNumber($fZeroHrs) ?></td>
				      <td class="center"><?= formatNumber($f12Hrs) ?></td>
				      <td class="center"><?= formatNumber($f1224Hrs) ?></td>
				      <td class="center"><?= formatNumber($f24Hrs) ?></td>
				      <td class="center"><?= formatNumber($fRest) ?></td>
				    </tr>
				  </table>
				</div>

				<br />

				<div class="tblSheet">
				  <div id="OtAprlChart">loading...</div>

				  <script type="text/javascript">
				  <!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "Ot", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='OT Analysis (<?= $sMonthsList[($Month - 1)] ?> <?= $Year ?>)' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='2' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='<?= strtolower(str_replace(" ", "-", $sVendorsList[$Vendor])) ?>-<?= $Month ?>-<?= $Year ?>'>" +
									          "<set tooltext='' label='(=0)' value='<?= $fZeroHrs ?>' />" +
									          "<set tooltext='' label='0-12' value='<?= $f12Hrs ?>' />" +
									          "<set tooltext='' label='12-24' value='<?= $f1224Hrs ?>' />" +
									          "<set tooltext='' label='>24' value='<?= $f24Hrs ?>' />" +
									          "<set tooltext='' label='Sunday/Rest' value='<?= $fRest ?>' />" +
									        "</chart>");

				  	    objChart.render("OtAprlChart");
				  -->
				  </script>
				</div>

				<br />

				<div class="tblSheet">
				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="10%" class="left">Month</td>
				      <td width="7%" class="center">=0</td>
				      <td width="8%" class="center">=0 (%)</td>
				      <td width="7%" class="center">0-12</td>
				      <td width="8%" class="center">0-12 (%)</td>
				      <td width="7%" class="center">>12-24</td>
				      <td width="8%" class="center">>12-24 (%)</td>
				      <td width="7%" class="center">>24</td>
				      <td width="8%" class="center">>24 (%)</td>
				      <td width="11%" class="center">Sunday/Rest Days</td>
				      <td width="11%" class="center">Sunday/Rest Days (%)</td>
				      <td width="8%" class="center">Total Strength</td>
				    </tr>

<?
			$sAverage = array( );
			$iMonths  = 0;

			for ($i = -11; $i <= 0; $i ++)
			{
				$iMonth = ($Month + $i);
				$iYear  = $Year;

				if ($iMonth <= 0)
				{
					$iMonth += 12;
					$iYear --;
				}


				$iTotalEmployees = getDbValue("SUM(employees)", "tbl_ot_apparel", "vendor_id='$Vendor' AND year='$iYear' AND month='$iMonth'");
				$iTotalWeeks     = getDbValue("COUNT(DISTINCT(week))", "tbl_ot_apparel", "vendor_id='$Vendor' AND year='$iYear' AND month='$iMonth'");
				$iEmployees      = @round($iTotalEmployees / $iTotalWeeks);


				$sSQL = "SELECT SUM(hrs_0), SUM(hrs_0_12), SUM(hrs_12_24), SUM(hrs_24), SUM(sunday_rest_days) FROM tbl_ot_apparel WHERE vendor_id='$Vendor' AND year='$iYear' AND month='$iMonth'";
				$objDb->query($sSQL);

				$iZeroHrs = $objDb->getField(0, 0);
				$i12Hrs   = $objDb->getField(0, 1);
				$i1224Hrs = $objDb->getField(0, 2);
				$i24Hrs   = $objDb->getField(0, 3);
				$iRest    = $objDb->getField(0, 4);


				$fZeroHrs = @((($iZeroHrs / $iTotalWeeks) / $iEmployees) * 100);
				$f12Hrs   = @((($i12Hrs / $iTotalWeeks) / $iEmployees) * 100);
				$f1224Hrs = @((($i1224Hrs / $iTotalWeeks) / $iEmployees) * 100);
				$f24Hrs   = @((($i24Hrs / $iTotalWeeks) / $iEmployees) * 100);
				$fRest    = @((($iRest / $iTotalWeeks) / $iEmployees) * 100);


				if ($iTotalEmployees > 0)
				{
					$iMonths ++;

					$sAverage['iZeroHrs']  += ($iZeroHrs / $iTotalWeeks);
					$sAverage['fZeroHrs']  += $fZeroHrs;
					$sAverage['i12Hrs']    += ($i12Hrs / $iTotalWeeks);
					$sAverage['f12Hrs']    += $f12Hrs;
					$sAverage['i1224Hrs']  += ($i1224Hrs / $iTotalWeeks);
					$sAverage['f1224Hrs']  += $f1224Hrs;
					$sAverage['i24Hrs']    += ($i24Hrs / $iTotalWeeks);
					$sAverage['f24Hrs']    += $f24Hrs;
					$sAverage['iRest']     += ($iRest / $iTotalWeeks);
					$sAverage['fRest']     += $fRest;
					$sAverage['Employees'] += $iEmployees;
				}
?>
				    <tr class="<?= $sClass[(abs($i) % 2)] ?>">
				      <td class="left"><?= $sMonthsList[($iMonth - 1)] ?> <?= $iYear ?></td>
				      <td class="center"><?= formatNumber(($iZeroHrs / $iTotalWeeks), false) ?></td>
				      <td class="center"><?= formatNumber($fZeroHrs) ?></td>
				      <td class="center"><?= formatNumber(($i12Hrs / $iTotalWeeks), false) ?></td>
				      <td class="center"><?= formatNumber($f12Hrs) ?></td>
				      <td class="center"><?= formatNumber(($i1224Hrs / $iTotalWeeks), false) ?></td>
				      <td class="center"><?= formatNumber($f1224Hrs) ?></td>
				      <td class="center"><?= formatNumber(($i24Hrs / $iTotalWeeks), false) ?></td>
				      <td class="center"><?= formatNumber($f24Hrs) ?></td>
				      <td class="center"><?= formatNumber(($iRest / $iTotalWeeks), false) ?></td>
				      <td class="center"><?= formatNumber($fRest) ?></td>
				      <td class="center"><?= formatNumber($iEmployees, false) ?></td>
				    </tr>

<?
			}
?>
				    <tr class="footerRow">
				      <td class="left">Avg.</td>
				      <td class="center"><?= formatNumber(($sAverage['iZeroHrs'] / $iMonths), false) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['fZeroHrs'] / $iMonths)) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['i12Hrs'] / $iMonths), false) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['f12Hrs'] / $iMonths)) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['i1224Hrs'] / $iMonths), false) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['f1224Hrs'] / $iMonths)) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['i24Hrs'] / $iMonths), false) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['f24Hrs'] / $iMonths)) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['iRest'] / $iMonths), false) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['fRest'] / $iMonths)) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['Employees'] / $iMonths), false) ?></td>
				    </tr>
				  </table>
				</div>
<?
		}


		else if ($iCategoryId == 5)
		{
?>
				<br />

				<div class="tblSheet">
				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="10%" class="center">Week</td>
				      <td width="10%" class="center">#of Empl.</td>
				      <td width="10%" class="center">ee# wk hrs 61-72</td>
				      <td width="10%" class="center">Impact employees (%)</td>
				      <td width="10%" class="center">ee# wk hrs > 72</td>
				      <td width="10%" class="center">Impact employees (%)</td>
			          <td width="10%" class="center">ee# Sunday</td>
			          <td width="10%" class="center">Impact employees (%)</td>
			          <td width="10%" class="center">ee# lack 1 day off in 14</td>
			          <td width="10%" class="center">Impact employees (%)</td>
				    </tr>

<?
			$iWeeks   = getDbValue("COUNT(DISTINCT(week))", "tbl_ot_equipment", "vendor_id='$Vendor' AND year='$Year' AND month='$Month'");
			$sAverage = array( );

			for ($i = 1; $i <= $iWeeks; $i ++)
			{
				$iEmployees = getDbValue("SUM(employees)", "tbl_ot_equipment", "vendor_id='$Vendor' AND year='$Year' AND month='$Month' AND week='$i'");


				$sSQL = "SELECT SUM(week_hrs_60_72), SUM(week_hrs_72), SUM(sunday), SUM(d1_off_in_14) FROM tbl_ot_equipment WHERE vendor_id='$Vendor' AND year='$Year' AND month='$Month' AND week='$i'";
				$objDb->query($sSQL);

				$iWorkingHrs6172   = $objDb->getField(0, 0);
				$iWorkingHrs72     = $objDb->getField(0, 1);
				$iSunday           = $objDb->getField(0, 2);
				$iLacking1DayOff14 = $objDb->getField(0, 3);


				$fWorkingHrs6172   = @(($iWorkingHrs6172 / $iEmployees) * 100);
				$fWorkingHrs72     = @(($iWorkingHrs72 / $iEmployees) * 100);
				$fSunday           = @(($iSunday / $iEmployees) * 100);
				$fLacking1DayOff14 = @(($iLacking1DayOff14 / $iEmployees) * 100);

				$sAverage['Employees']         += $iEmployees;
				$sAverage['iWorkingHrs6172']   += $iWorkingHrs6172;
				$sAverage['fWorkingHrs6172']   += $fWorkingHrs6172;
				$sAverage['iWorkingHrs72']     += $iWorkingHrs72;
				$sAverage['fWorkingHrs72']     += $fWorkingHrs72;
				$sAverage['iSunday']           += $iSunday;
				$sAverage['fSunday']           += $fSunday;
				$sAverage['iLacking1DayOff14'] += $iLacking1DayOff14;
				$sAverage['fLacking1DayOff14'] += $fLacking1DayOff14;
?>
				    <tr class="<?= $sClass[(abs($i) % 2)] ?>">
				      <td class="center">Week <?= $i ?></td>
				      <td class="center"><?= formatNumber($iEmployees, false) ?></td>
				      <td class="center"><?= formatNumber($iWorkingHrs6172, false) ?></td>
				      <td class="center"><?= formatNumber($fWorkingHrs6172) ?></td>
				      <td class="center"><?= formatNumber($iWorkingHrs72, false) ?></td>
				      <td class="center"><?= formatNumber($fWorkingHrs72) ?></td>
				      <td class="center"><?= formatNumber($iSunday, false) ?></td>
				      <td class="center"><?= formatNumber($fSunday) ?></td>
				      <td class="center"><?= formatNumber($iLacking1DayOff14, false) ?></td>
				      <td class="center"><?= formatNumber($fLacking1DayOff14) ?></td>
				    </tr>

<?
			}
?>
				    <tr class="footerRow">
				      <td class="center">Avg.</td>
				      <td class="center"><?= formatNumber(($sAverage['Employees'] / $iWeeks), false) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['iWorkingHrs6172'] / $iWeeks), false) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['fWorkingHrs6172'] / $iWeeks)) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['iWorkingHrs72'] / $iWeeks), false) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['fWorkingHrs72'] / $iWeeks)) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['iSunday'] / $iWeeks), false) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['fSunday'] / $iWeeks)) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['iLacking1DayOff14'] / $iWeeks), false) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['fLacking1DayOff14'] / $iWeeks)) ?></td>
				    </tr>
				  </table>
				</div>

				<br />

				<div class="tblSheet">
				  <div id="OtEqupChart">loading...</div>

				  <script type="text/javascript">
				  <!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "Ot", "100%", "340", "0", "1");

						objChart.setXMLData("<chart caption='OT Analysis (<?= $sMonthsList[($Month - 1)] ?> <?= $Year ?>)' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='2' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='movies/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='<?= strtolower(str_replace(" ", "-", $sVendorsList[$Vendor])) ?>-<?= $Month ?>-<?= $Year ?>'>" +
									          "<set tooltext='' label='wk hrs 61-72' value='<?= formatNumber(($sAverage['fWorkingHrs6172'] / $iWeeks)) ?>' />" +
									          "<set tooltext='' label='wk hrs >72' value='<?= formatNumber(($sAverage['fWorkingHrs72'] / $iWeeks)) ?>' />" +
									          "<set tooltext='' label='Sunday' value='<?= formatNumber(($sAverage['fSunday'] / $iWeeks)) ?>' />" +
									          "<set tooltext='' label='1 Off in 14' value='<?= formatNumber(($sAverage['fLacking1DayOff14'] / $iWeeks)) ?>' />" +
									        "</chart>");

				  	    objChart.render("OtEqupChart");
				  -->
				  </script>
				</div>

				<br />

				<div class="tblSheet">
				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="10%" class="left">Month</td>
				      <td width="10%" class="center">#of Empl.</td>
				      <td width="10%" class="center">ee# wk hrs 61-72</td>
				      <td width="10%" class="center">Impact employees (%)</td>
				      <td width="10%" class="center">ee# wk hrs > 72</td>
				      <td width="10%" class="center">Impact employees (%)</td>
			          <td width="10%" class="center">ee# Sunday</td>
			          <td width="10%" class="center">Impact employees (%)</td>
			          <td width="10%" class="center">ee# lack 1 day off in 14</td>
			          <td width="10%" class="center">Impact employees (%)</td>
				    </tr>
<?
			$sAverage = array( );
			$iMonths  = 0;

			for ($i = -11; $i <= 0; $i ++)
			{
				$iMonth = ($Month + $i);
				$iYear  = $Year;

				if ($iMonth <= 0)
				{
					$iMonth += 12;
					$iYear --;
				}


				$iWeeks     = getDbValue("COUNT(DISTINCT(week))", "tbl_ot_equipment", "vendor_id='$Vendor' AND year='$iYear' AND month='$iMonth'");
				$iEmployees = getDbValue("SUM(employees)", "tbl_ot_equipment", "vendor_id='$Vendor' AND year='$iYear' AND month='$iMonth'");


				$sSQL = "SELECT SUM(week_hrs_60_72), SUM(week_hrs_72), SUM(sunday), SUM(d1_off_in_14) FROM tbl_ot_equipment WHERE vendor_id='$Vendor' AND year='$iYear' AND month='$iMonth'";
				$objDb->query($sSQL);

				$iWorkingHrs6172   = $objDb->getField(0, 0);
				$iWorkingHrs72     = $objDb->getField(0, 1);
				$iSunday           = $objDb->getField(0, 2);
				$iLacking1DayOff14 = $objDb->getField(0, 3);


				$fWorkingHrs6172   = @(($iWorkingHrs6172 / $iEmployees) * 100);
				$fWorkingHrs72     = @(($iWorkingHrs72 / $iEmployees) * 100);
				$fSunday           = @(($iSunday / $iEmployees) * 100);
				$fLacking1DayOff14 = @(($iLacking1DayOff14 / $iEmployees) * 100);


				if ($iEmployees > 0)
				{
					$iMonths ++;

					$sAverage['Employees']         += ($iEmployees / $iWeeks);
					$sAverage['iWorkingHrs6172']   += ($iWorkingHrs6172 / $iWeeks);
					$sAverage['fWorkingHrs6172']   += $fWorkingHrs6172;
					$sAverage['iWorkingHrs72']     += ($iWorkingHrs72 / $iWeeks);
					$sAverage['fWorkingHrs72']     += $fWorkingHrs72;
					$sAverage['iSunday']           += ($iSunday / $iWeeks);
					$sAverage['fSunday']           += $fSunday;
					$sAverage['iLacking1DayOff14'] += ($iLacking1DayOff14 / $iWeeks);
					$sAverage['fLacking1DayOff14'] += $fLacking1DayOff14;
				}
?>
				    <tr class="<?= $sClass[(abs($i) % 2)] ?>">
				      <td class="left"><?= substr($sMonthsList[($iMonth - 1)], 0, 3) ?> <?= $iYear ?></td>
				      <td class="center"><?= formatNumber(($iEmployees / $iWeeks), false) ?></td>
				      <td class="center"><?= formatNumber(($iWorkingHrs6172 / $iWeeks), false) ?></td>
				      <td class="center"><?= formatNumber(($fWorkingHrs6172 / $iWeeks)) ?></td>
				      <td class="center"><?= formatNumber(($iWorkingHrs72 / $iWeeks), false) ?></td>
				      <td class="center"><?= formatNumber(($fWorkingHrs72 / $iWeeks)) ?></td>
				      <td class="center"><?= formatNumber(($iSunday / $iWeeks), false) ?></td>
				      <td class="center"><?= formatNumber(($fSunday / $iWeeks)) ?></td>
				      <td class="center"><?= formatNumber(($iLacking1DayOff14 / $iWeeks), false) ?></td>
				      <td class="center"><?= formatNumber(($fLacking1DayOff14 / $iWeeks)) ?></td>
				    </tr>

<?
			}
?>
				    <tr class="footerRow">
				      <td class="left">Avg.</td>
				      <td class="center"><?= formatNumber(($sAverage['Employees'] / $iMonths), false) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['iWorkingHrs6172'] / $iMonths), false) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['fWorkingHrs6172'] / $iMonths)) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['iWorkingHrs72'] / $iMonths), false) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['fWorkingHrs72'] / $iMonths)) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['iSunday'] / $iMonths), false) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['fSunday'] / $iMonths)) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['fLacking1DayOff14'] / $iMonths), false) ?></td>
				      <td class="center"><?= formatNumber(($sAverage['fLacking1DayOff14'] / $iMonths)) ?></td>
				    </tr>
				  </table>
				</div>
<?
		}
	}
?>
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