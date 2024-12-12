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

	$Vendor   	= IO::intValue("Vendor");
	$Brand    	= IO::intValue("Brand");
	$Region   	= IO::intValue("Region");
	$Season   	= IO::intValue("Season");
	$Department = IO::intValue("Department");

	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");

	$Week_Start_Date = IO::strValue("Week_Start_Date");
	$Week_End_Date   = IO::strValue("Week_End_Date");

	if($Week_End_Date  == '' && $Week_End_Date ==''){

		$Week_Start_Date = $arrDates[1];
		$Week_End_Date 	= $arrDates[2];

	}


	$CurrentDate   = IO::strValue("CurrentDate");



	if ($FromDate == "" || $ToDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 30), date("Y")));
		$ToDate   = date("Y-m-d");
	}

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0'");


	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];

	if ($Brand > 0)
	{
		$iParent      = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$sSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iParent' AND parent_id>'0'");
	}

	//All Dates

	$CurrentDay = date('w', $CurrentDate==''?date('Y-m-d'):strtotime($CurrentDate));

/*	if($CurrentDay != 1)
		$CurrentMonday = date('Y-m-d',strtotime("last monday"));
	else
		$CurrentMonday = date('Y-m-d');
*/

	if($CurrentDay != 1)
		$CurrentMonday = date('Y-m-d',strtotime('last monday '.$CurrentDate));
	else
		$CurrentMonday = date('Y-m-d',strtotime($CurrentDate));

	if($CurrentMonday == date('Y-m-d',strtotime('last monday '.date('Y-m-d'))) ){
		$previousMonday = date('Y-m-d',strtotime($CurrentMonday.' -7 days '));
		$nextMonday = date('Y-m-d',strtotime($CurrentMonday.' +7 days '));
	}else{

		$previousMonday = date('Y-m-d',strtotime($CurrentMonday.' -7 days '));
		$nextMonday = date('Y-m-d',strtotime($CurrentMonday.' +7 days '));
	}

	$CurrentEnd = date('Y-m-d',strtotime($CurrentMonday." + 7 days"));

	$StartDate = date('Y-m-d',strtotime($CurrentMonday." - 7 days"));

	$EndDate = date('Y-m-d',strtotime($CurrentMonday." + 28 days"));


	//	$TerminalDate 			=  date('Y-m-d',strtotime($CurrentMonday." - 14 days"));
	$LastMonday  			= $CurrentMonday;
	$PreviousToLastMonday 	= date('Y-m-d',strtotime($CurrentMonday."  -7 days"));
	$NextMonday 			= date('Y-m-d',strtotime($CurrentMonday." + 7 days"));
	$NextToNextMonday 		= date('Y-m-d',strtotime($CurrentMonday." + 14 days"));

	//Assign in chronological order

	//$arrDates[] = $TerminalDate;
	//$arrDates[] = $PreviousToLastMonday;
	$arrDates[] = $LastMonday;

	$arrDates[] = $NextMonday;
	$arrDates[] = $NextToNextMonday;
	$arrDates[] = date('Y-m-d',strtotime($CurrentMonday." + 21 days"));

	$arrDates[] = date('Y-m-d',strtotime($CurrentMonday." + 28 days"));

	//$arrDates[] = date('Y-m-d',strtotime($CurrentMonday." + 35 days"));

//print_r($arrDates);
	//// Queries

	$sVendorPos   = "";
	$sBrandStyles = "";
	$sBrandPos    = "";
	$sVendorsSql  = "";
	$sSeasonSql   = "";
	$sRegionSql   = "";

		if ($Region > 0)
			{
				$sSQL = "SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y'";
				$objDb->query($sSQL);

				$iCount   = $objDb->getCount( );
				$sVendors = "";

				for ($i = 0; $i < $iCount; $i ++)
					$sVendors .= (",".$objDb->getField($i, 0));

				if ($sVendors != "")
					$sVendors = substr($sVendors, 1);

				$sVendorsSql = " AND vendor_id IN ($sVendors) ";
		}

		if ($Vendor > 0)
			$sSQL = "SELECT id FROM tbl_po WHERE vendor_id='$Vendor' $sVendorsSql";

		else
			$sSQL = "SELECT id FROM tbl_po WHERE vendor_id IN ({$_SESSION['Vendors']}) $sVendorsSql AND shipping_dates between '$arrDates[0]' and '$arrDates[4]'";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sVendorPos .= (",".$objDb->getField($i, 0));

		if ($sVendorPos != "")
			$sVendorPos = substr($sVendorPos, 1);

		if ($Season > 0)
			$sSeasonSql = " AND sub_season_id='$Season' ";

		if ($Brand > 0)
			$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}') $sSeasonSql";

		else
			$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']}) AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}') $sSeasonSql";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sBrandStyles .= (",".$objDb->getField($i, 0));

		if ($sBrandStyles != "")
			$sBrandStyles = substr($sBrandStyles, 1);

		//$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE style_id IN ($sBrandStyles) AND (etd_required BETWEEN '$FromDate' AND '$ToDate')";
		$sSQL = "SELECT id FROM tbl_po_colors WHERE style_id IN ($sBrandStyles) AND (etd_required BETWEEN '$StartDate' AND '$EndDate')";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sBrandPos .= (",".$objDb->getField($i, 0));

		if ($sBrandPos != "")
			$sBrandPos = substr($sBrandPos, 1);


	$ShortShipped 	= array();
	$VesselWeeks 	= array();
/*
	$sSQL =  "SELECT count(Distinct(po_id)) as total_pos ,sum(order_qty) as total_order, DATE_FORMAT(pc.etd_required,'%u') as week FROM tbl_po_colors as pc, tbl_po as po WHERE pc.etd_required between '$StartDate' and '$EndDate'  and pc.id in ($sBrandPos) and po.id=pc.po_id group by DATE_FORMAT(pc.etd_required,'%u') Order by DATE_FORMAT(pc.etd_required,'%u') ";

	//print $sSQL; exit;
	$objDb->query($sSQL);
	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{

		$po_id = $objDb->getField($i, 'po_id');

		$backLog = 0;


		$VesselWeeks[$objDb->getField($i, 'week')]["ORDERS"] = $objDb->getField($i, 'total_order');
		$VesselWeeks[$objDb->getField($i, 'week')]["POS"] = $objDb->getField($i, 'total_pos');

	}
*/

/*
	//print_r($VesselWeeks);

	$sSQL =  "SELECT count(distinct(pc.po_id)) as total_ontime_pos, sum(order_qty) as total_ontime_order, DATE_FORMAT(pc.etd_required,'%u') as week FROM tbl_po_colors as pc, tbl_po as po, tbl_vsr_details as vsr WHERE pc.etd_required between '$StartDate' and '$EndDate' and pc.id in ($sBrandPos)  and po.id=pc.po_id and pc.po_id=vsr.po_id and ( vsr.final_date <= pc.etd_required AND vsr.final_date IS NOT NULL AND  vsr.final_date != '0000-00-00' )  group by DATE_FORMAT(pc.etd_required,'%u') Order by DATE_FORMAT(pc.etd_required,'%u') ";

	//print $sSQL; exit;
	$objDb->query($sSQL);
	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{

		$po_id = $objDb->getField($i, 'po_id');

		$backLog = 0;

		$VesselWeeks[$objDb->getField($i, 'week')]["ontime_ORDERS"] = $objDb->getField($i, 'total_ontime_order');
		$VesselWeeks[$objDb->getField($i, 'week')]["ontime_POS"] = $objDb->getField($i, 'total_ontime_pos');

		$VesselWeeks[$objDb->getField($i, 'week')]["Late_POS"] = $VesselWeeks[$objDb->getField($i, 'week')]["POS"]-$VesselWeeks[$objDb->getField($i, 'week')]["ontime_POS"];
		$VesselWeeks[$objDb->getField($i, 'week')]["Late_ORDERS"] = $VesselWeeks[$objDb->getField($i, 'week')]["ORDERS"]-$VesselWeeks[$objDb->getField($i, 'week')]["ontime_ORDERS"];

	}
*/
	//$BL_Start_Date = date('Y-m-d',strtotime($StartDate." -7 days"));

	$sSQL =  "SELECT count(distinct(pc.po_id)) as total_bl_pos, sum(order_qty) as total_bl_order, DATE_FORMAT(pc.etd_required,'%u') as week FROM tbl_po_colors as pc, tbl_po as po, tbl_vsr_details as vsr
	WHERE pc.etd_required between '$StartDate' and '$EndDate' and pc.po_id in ($sBrandPos)  and po.id=pc.po_id and pc.po_id=vsr.po_id and ( vsr.final_date > pc.etd_required OR vsr.final_date IS NULL OR vsr.final_date = '0000-00-00' )  group by DATE_FORMAT(pc.etd_required,'%u') Order by DATE_FORMAT(pc.etd_required,'%u') ";

	//print $sSQL; exit;
	$objDb->query($sSQL);
	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{

		$po_id = $objDb->getField($i, 'po_id');

		$backLog = 0;

		$VesselWeeks[$objDb->getField($i, 'week')]["BL_POS"] = $objDb->getField($i, 'total_bl_pos');
		$VesselWeeks[$objDb->getField($i, 'week')]["BL_ORDERS"] = $objDb->getField($i, 'total_bl_order');
	}

/*
SELECT SUM(pc.order_qty)
FROM tbl_po_colors pc, tbl_po po
WHERE pc.po_id=po.id AND pc.etd_required between '$StartDate' and '$EndDate' and pc.id in ($sBrandPos)
      AND pc.production_status='1' AND po.id NOT IN ( SELECT DISTINCT(po.id)
                                                      FROM tbl_po_colors pc, tbl_po po
                                                      WHERE pc.po_id=po.id AND (pc.etd_required between '$StartDate' AND '$EndDate') and pc.id in ($sBrandPos) AND pc.production_status!='1' )
GROUP By DATE_FORMAT(pc.etd_required,'%u')



*/



	$sSQL = "SELECT
	sum( if(po.production_status =0,pc.order_qty,0)) as _NoStatus,
	sum( if(po.production_status =2,pc.order_qty,0)) as _late,
	sum( if(po.production_status =1,pc.order_qty,0)) as _ready,
	sum( if(po.production_status =3,pc.order_qty,0)) as _orange,

	DATE_FORMAT(pc.etd_required,'%u') as week
 FROM  tbl_po_colors as pc,
 tbl_po as po
 WHERE
 po.id=pc.po_id and
 pc.etd_required between '$StartDate' and '$EndDate' and pc.id in ($sBrandPos)
	group by DATE_FORMAT(pc.etd_required,'%u') order by DATE_FORMAT(pc.etd_required,'%u') ASC";


 	//print $sSQL; exit;
 	$objDb->query($sSQL);
	$iCount = $objDb->getCount( );

	//$VesselWeeks=array() ;

	for ($i = 0; $i < $iCount; $i ++)
	{

		$week = $objDb->getField($i, 'week');


		/*

		$VesselWeeks[$week]["Orders"]['nostatus'] = $objDb->getField($i, '_NoStatus');
		$VesselWeeks[$week]["Orders"]['late'] = $objDb->getField($i, '_late');
		$VesselWeeks[$week]["Orders"]['ready'] = $objDb->getField($i, '_ready');
		$VesselWeeks[$week]["Orders"]['orange'] = $objDb->getField($i, '_orange');

*/
		$sSQL = "

		SELECT SUM( IF(production_status =0,1,0)) AS _NoStatus,
		   SUM( IF(production_status =2,1,0)) AS _late,
		   SUM( IF(production_status =1,1,0)) AS _ready,
		   SUM( IF(production_status =3,1,0)) AS _orange
	FROM tbl_po
	WHERE id IN (SELECT DISTINCT(po_id)  FROM tbl_po_colors WHERE  etd_required BETWEEN '$StartDate' and '$EndDate' and DATE_FORMAT(etd_required,'%u') = $week  )
	AND brand_id IN ({$_SESSION['Brands']})  AND vendor_id IN ({$_SESSION['Vendors']});

		";

		$objDb2->query($sSQL);

		$VesselWeeks[$objDb->getField($i, 'week')]["Po"]['nostatus'] = $objDb2->getField(0, '_NoStatus');
		$VesselWeeks[$objDb->getField($i, 'week')]["Po"]['late'] = $objDb2->getField(0, '_late');
		$VesselWeeks[$objDb->getField($i, 'week')]["Po"]['ready'] = $objDb2->getField(0, '_ready');
		$VesselWeeks[$objDb->getField($i, 'week')]["Po"]['orange'] = $objDb2->getField(0, '_orange');


	}



	$sSQL = "SELECT SUM(pc.order_qty) as _ready, DATE_FORMAT(pc.etd_required,'%u') as week
	FROM tbl_po_colors pc, tbl_po po
	WHERE pc.po_id=po.id AND pc.etd_required between '$StartDate' and '$EndDate' and pc.id in ($sBrandPos)
	      AND pc.production_status='1' AND po.id NOT IN ( SELECT DISTINCT(po.id)
	                                                      FROM tbl_po_colors pc, tbl_po po
	                                                      WHERE pc.po_id=po.id AND (pc.etd_required between '$StartDate' AND '$EndDate') and pc.id in ($sBrandPos) AND pc.production_status!='1' )
	GROUP By DATE_FORMAT(pc.etd_required,'%u')";


	$objDb2->query($sSQL);

	$iCount = $objDb2->getCount( );


	for ($i = 0; $i < $iCount; $i ++)
	{
		$week = $objDb2->getField($i, 'week');
		$VesselWeeks[$week]["Orders"]['ready'] = $objDb2->getField($i, '_ready');

	}


	$sSQL = "SELECT SUM(pc.order_qty) as _late, DATE_FORMAT(pc.etd_required,'%u') as week
		FROM tbl_po_colors pc, tbl_po po
		WHERE pc.po_id=po.id AND pc.etd_required between '$StartDate' and '$EndDate' and pc.id in ($sBrandPos)
		      AND pc.production_status='2' AND po.id NOT IN ( SELECT DISTINCT(po.id)
		                                                      FROM tbl_po_colors pc, tbl_po po
		                                                      WHERE pc.po_id=po.id AND (pc.etd_required between '$StartDate' AND '$EndDate') and pc.id in ($sBrandPos) AND pc.production_status!='2' )
		GROUP By DATE_FORMAT(pc.etd_required,'%u')";


		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );


		for ($i = 0; $i < $iCount; $i ++)
		{
			$week = $objDb->getField($i, 'week');
			$VesselWeeks[$week]["Orders"]['late'] = $objDb->getField($i, '_late');


		}


	$sSQL = "SELECT SUM(pc.order_qty) as _orange, DATE_FORMAT(pc.etd_required,'%u') as week
			FROM tbl_po_colors pc, tbl_po po
			WHERE pc.po_id=po.id AND pc.etd_required between '$StartDate' and '$EndDate' and pc.id in ($sBrandPos)
			      AND pc.production_status='3' AND po.id NOT IN ( SELECT DISTINCT(po.id)
			                                                      FROM tbl_po_colors pc, tbl_po po
			                                                      WHERE pc.po_id=po.id AND (pc.etd_required between '$StartDate' AND '$EndDate') and pc.id in ($sBrandPos) AND pc.production_status!='3' )
			GROUP By DATE_FORMAT(pc.etd_required,'%u')";

			$objDb2->query($sSQL);

			$iCount = $objDb2->getCount( );


			for ($i = 0; $i < $iCount; $i ++)
			{
				$week = $objDb2->getField($i, 'week');
				$VesselWeeks[$week]["Orders"]['orange'] = $objDb2->getField($i, '_orange');

			}

	$sSQL = "SELECT SUM(pc.order_qty) as _nostatus, DATE_FORMAT(pc.etd_required,'%u') as week
				FROM tbl_po_colors pc, tbl_po po
				WHERE pc.po_id=po.id AND pc.etd_required between '$StartDate' and '$EndDate' and pc.id in ($sBrandPos)
				      AND pc.production_status='0' AND po.id NOT IN ( SELECT DISTINCT(po.id)
				                                                      FROM tbl_po_colors pc, tbl_po po
				                                                      WHERE pc.po_id=po.id AND (pc.etd_required between '$StartDate' AND '$EndDate') and pc.id in ($sBrandPos) AND pc.production_status!='0' )
				GROUP By DATE_FORMAT(pc.etd_required,'%u')";

				$objDb2->query($sSQL);

				$iCount = $objDb2->getCount( );


				for ($i = 0; $i < $iCount; $i ++)
				{
					$week = $objDb2->getField($i, 'week');
					$VesselWeeks[$week]["Orders"]['nostatus'] = $objDb2->getField($i, '_nostatus');
				}

	//print_r($VesselWeeks); exit;



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
	<link type="text/css" rel="stylesheet" href="css/vsrprogress.css" />

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
			    <h1>Vendor Status Report</h1>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">


			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="52">Vendor</td>

			          <td width="200">
			            <select name="Vendor" id="Vendor">
			              <option value="">All Vendors</option>
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

			          <td width="45">Brand</td>

			          <td width="180">
			            <select name="Brand" id="Brand" onchange="getListValues('Brand', 'Season', 'BrandSeasons');">
			              <option value="">All Brands</option>
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>

			          <td width="50">Region</td>

					  <td width="115">
					    <select name="Region" id="Region">
						  <option value="">All Regions</option>
<?
	$sSQL = "SELECT id, country FROM tbl_countries WHERE matrix='Y' ORDER BY country";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	  	        <option value="<?= $sKey ?>"<?= (($sKey == $Region) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
					  </td>

			          <td width="55">Season</td>

			          <td>
			            <select name="Season" id="Season">
			              <option value="">All Seasons</option>
<?
	if ($Brand > 0)
	{
		foreach ($sSeasonsList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Season) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
	}
?>
			            </select>
			          </td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <br style="line-height:4px;" />
			    <?php
					$selectedBrand = "";
					if($Brand !=""){

						$selectedBrand = $sBrandsList[$Brand];

					}else{

						$selectedBrand = "All Brands";

					}

					$width = (100/count($arrDates)-1);

				?>
		<h2 style="color:black;background-color:white;"><? echo $selectedBrand." ".date("M",strtotime($arrDates[0]))." - ".date("M  Y",strtotime($arrDates[count($arrDates)-1]));?></h2>

		<table width="100%" height="300px">
			<tr>
				<td>

				<div> <a href="vsr/new-vsr.php?CurrentDate=<?=$previousMonday;?>" >previous</a> </span><span style="float:right"><a href="vsr/new-vsr.php?CurrentDate=<?=$nextMonday;?>" >next</a></span> </div>

					<div id="container"> <br/><br/><br/><br/><br/><br/>

						<div id="bar" class="shadow" style ="height:22px;">
						<!--  4 weeks 4 divs-->
						<?php

						$j = 0;

						for($k=0; $k <count($arrDates); $k++){

							$week = date('W',strtotime($arrDates[$k])) - 1;

							//if()

							$year = date('Y',strtotime($arrDates[$k]));
							$week_start =  date('Y-m-d',strtotime($arrDates[$k]." - 7 days"));
							$week_end = date('Y-m-d',strtotime($arrDates[$k]. " -1 day")); // sunday

							$last_week_start =  date('Y-m-d',strtotime($arrDates[$k-1]." - 7 days"));
							$last_week_end = date('Y-m-d',strtotime($arrDates[$k-1]. " -1 day")); // sunday

							$nextWeek = date('W',strtotime($arrDates[$k+1]));

							$totalPo = $VesselWeeks[$week]["Po"]['ready'] + $VesselWeeks[$week]["Po"]['nostatus']
							 + $VesselWeeks[$week]["Po"]['late'] + $VesselWeeks[$week]["Po"]['orange'];

							 $totalOrders = $VesselWeeks[$week]["Orders"]['ready'] + $VesselWeeks[$week]["Orders"]['nostatus']
							 + $VesselWeeks[$week]["Orders"]['late'] + $VesselWeeks[$week]["Orders"]['orange'];


							//$late_not_in_wo = getDBValue("sum(order_qty)","tbl_po_colors","po_id in (".implode($pos_not_in_WO,",").") ");

							$completePercentage = @($VesselWeeks[$week]["Po"]['ready']/$totalPo)*100;

						?>

							<div id="container" name="<?=$nextVessel?>"  style ="<?=$week==date('W')?"background-color:rgba(225, 225, 225, .3)":""?>;
							border-left:1px dotted #cccccc;height:180px;position:relative;width:<?=$width?>%;float:left;" >
								<div onclick="fetchDepartment('<? print SITE_URL."ajax/vsr/fetch-departs.php?CurrentDate=$CurrentDate&Vendor=$Vendor&Brand=$Brand&Region=$Region&Season=$Season&Week_Start_Date=".$week_start."&Week_End_Date=".$week_end; ?>');" class="<?=$completePercentage==100?"shadowInnerComplete":"shadowInnerIncomplete";?>" style ="height:21px;width:<?=$completePercentage;?>%;position:static;float:left;background-color:#cccc00;"></div>
								<div class="arrow-up" ></div>
								<div class="arrow-down"></div>
									<div class="thumbnail" style="color:#7ea43d;">

									<span style="white-space:nowrap;font-size:8px;text-align:left;">
									<font color="grey">POs Due: <? echo number_format($totalPo + $VesselWeeks[$week]["BL_POS"])."   (".count($VesselWeeks[$week]["POS"])." C ,";?></font>
									<?
										if($VesselWeeks[$week-1]["BL_POS"]==0 or empty($VesselWeeks[$week-1]["BL_POS"])){
									?>
											0 BL ) <br/>
									<?  }else{  ?>
									<a href='vsr/view-backlog-pos.php?StartDate=<?=$last_week_start?>&EndDate=<?=$last_week_end?>&Week=<?=$week-1?>' class="lightview" rel="iframe" title="Back Log # :: :: width: 800, height: 550" style="font-size:8px;"><?=$VesselWeeks[$week-1]["BL_POS"]?$VesselWeeks[$week-1]["BL_POS"]:0?> BL </a>)<br/>
									<?  }
									?>
									Ready : <?=$VesselWeeks[$week]["Po"]["ready"];?></br>
									<font color="red">Late : <?=$VesselWeeks[$week]["Po"]["late"];?></font><br/>
									<font color="orange">Expected : <?=$VesselWeeks[$week]["Po"]["orange"];?></font><br />
									<!--PO's BL: <?=number_format(count($ShortShipped));?><br>
									On Time: <?=number_format($VesselTotal[$key]["TotalOnTimePerPO"])?>-->
									</span>

									 </div>
							</div>
							<div style="clear:none;"></div>
							<? if($j < count($arrDates)) { ?>
							<a class="thumbnailOntime" style="float:left;color:#7ea43d;"><span style="font-size:10px;text-align:left;text-color:#b9d05e;">
								<!--<? echo date("d-M",strtotime($arrDates[$k+2]));?> <br /> -->
								<font color="grey">Order: <?=number_format($totalOrders)?> Pcs </font></br>
								On-time: <?=number_format($VesselWeeks[$week]["Orders"]["ready"])?> Pcs </br>
								<font color="red">Delay: <?=number_format(($VesselWeeks[$week]["Orders"]["late"])?($VesselWeeks[$week]["Order"]["late"]):0)?> Pcs </font> <br/>
								<font color="red">No Status: <?=number_format($VesselWeeks[$week]["Orders"]["nostatus"])?> Pcs </font> <br/>
								<font color="orange">Risky: <?=number_format($VesselWeeks[$week]["Orders"]["orange"]?$VesselWeeks[$week]["Orders"]["orange"]:0)?> Pcs </font>
									</span>
									 </a>
									 <a class="thumbnailDate" style="float:left;color:#7ea43d;"><span style="font-size:10px;text-align:center;text-color:#b9d05e;">
								<? echo date("d-M",strtotime($arrDates[$k]));?>
									</span>
									 </a>
							<? }?>

						<?
							$j = $j+1;

						 } ?>


						 </div>
		</div>

				</td>
			</tr>

		</table>

		<table border="0" width="100%">

		<tr>
			<td>

				<div id="hiddenDiv">

				</div>
			</td>
		</tr>

		</table>

		<div id="hiddenDiv2">

				</div>
		<div id="hiddenDiv3">

						</div>


		<!--<table border="0" width="100%">

		<tr>
			<td>

				<div id="hiddenDiv2">

				</div>
			</td>
		</tr>

		</table>

		<table border="0" width="100%">

				<tr>
					<td>

						<div id="hiddenDiv3">

						</div>
					</td>
				</tr>

		</table>-->









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

<script type="text/javascript" src="scripts/vsr/new-vsr.js"></script>
<!--  Bottom Bar Section Ends Here  -->

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>