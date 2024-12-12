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
	@require_once($sBaseDir."requires/chart.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$Region   = IO::intValue("Region");
	$Vendor   = IO::intValue("Vendor");
	$Brand    = IO::intValue("Brand");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$Season   = IO::intValue("Season");
	$Tab      = IO::strValue("Tab");

	$Tab = (($Tab == "") ? "Production" : $Tab);

	if ($FromDate == "" || $ToDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 7), date("Y")));
		$ToDate   = date("Y-m-d");
	}

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0'");

	if ($Brand > 0)
	{
		$iParent      = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$sSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iParent' AND parent_id>'0'");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
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
			    <h1>Deviation</h1>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <input type="hidden" id="Tab" name="Tab" value="<?= $Tab ?>" />

			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="52">Vendor</td>

			          <td width="200">
			            <select name="Vendor">
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

					  <td width="40">From</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center">To</td>
					  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
					  <td width="50">Region</td>

					  <td width="115">
					    <select name="Region">
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
				    </tr>
				  </table>
			    </div>
			    </form>

<?
	$sVendorPos   = "";
	$sBrandStyles = "";
	$sBrandPos    = "";
	$sSeasonSql   = "";
	$sRegionSql   = "";
	$sVendorsSql  = "";


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
		$sSQL = "SELECT id FROM tbl_po WHERE vendor_id IN ({$_SESSION['Vendors']}) $sVendorsSql";

	if ($Brand > 0)
		$sSQL = " AND brand_id='$Brand' ";

	else
		$sSQL = " AND brand_id IN ({$_SESSION['Brands']}) ";


	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sVendorPos .= (",".$objDb->getField($i, 0));

	if ($sVendorPos != "")
		$sVendorPos = substr($sVendorPos, 1);


	if ($Season > 0)
		$sSeasonSql = " AND sub_season_id='$Season' ";


	if ($Brand > 0)
		$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' $sSeasonSql";

	else
		$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']}) $sSeasonSql";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sBrandStyles .= (",".$objDb->getField($i, 0));

	if ($sBrandStyles != "")
		$sBrandStyles = substr($sBrandStyles, 1);


	$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE style_id IN ($sBrandStyles) AND (etd_required BETWEEN '$FromDate' AND '$ToDate')";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sBrandPos .= (",".$objDb->getField($i, 0));

	if ($sBrandPos != "")
		$sBrandPos = substr($sBrandPos, 1);



	$iTotalPos   = array( );
	$iDev02Pos   = array( );
	$iDev34Pos   = array( );
	$iDev5Pos    = array( );
	$fDeviations = array( );
	$sBrands     = array( );
	$iOrderQty   = array( );
	$iShipQty    = array( );


	$sSQL = "SELECT po.brand_id, SUM(psq.quantity) FROM tbl_pre_shipment_quantities psq, tbl_po_colors pc, tbl_po po WHERE po.id=pc.po_id AND po.status='C' AND psq.color_id=pc.id AND psq.po_id=pc.po_id AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate') AND pc.etd_required <= CURDATE( ) AND pc.po_id IN ($sVendorPos) AND pc.style_id IN ($sBrandStyles) GROUP BY po.brand_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId = $objDb->getField($i, 0);

		$iShipQty[$iBrandId] = $objDb->getField($i, 1);
	}


	$sSQL = "SELECT po.brand_id, SUM(pq.quantity) FROM tbl_po_quantities pq, tbl_po_colors pc, tbl_po po WHERE po.id=pc.po_id AND po.status='C' AND pq.color_id=pc.id AND pq.po_id=pc.po_id AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate') AND pc.etd_required <= CURDATE( ) AND pc.po_id IN ($sVendorPos) AND pc.style_id IN ($sBrandStyles) GROUP BY po.brand_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId             = $objDb->getField($i, 0);
		$iOrderQty[$iBrandId] = $objDb->getField($i, 1);

		$fDeviation = (@(($iShipQty[$iBrandId] / $iOrderQty[$iBrandId]) * 100) - 100);


		$fDeviations[$i] = @round($fDeviation, 2);
		$sBrands[$i]     = $sBrandsList[$iBrandId];


		$sSQL = "SELECT po.id, SUM(pq.quantity) FROM tbl_po_quantities pq, tbl_po_colors pc, tbl_po po WHERE po.id=pc.po_id AND po.status='C' AND pq.color_id=pc.id AND pq.po_id=pc.po_id AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate') AND pc.etd_required <= CURDATE( ) AND pc.po_id IN ($sVendorPos) AND pc.style_id IN ($sBrandStyles) AND po.brand_id='$iBrandId' GROUP BY po.id";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		$iTotalPos[$i] = $iCount2;
		$iDev2Pos[$i]  = 0;
		$iDev5Pos[$i]  = 0;

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iPoId          = $objDb2->getField($j, 0);
			$iOrderQuantity = $objDb2->getField($j, 1);


			$sSQL = "SELECT SUM(psq.quantity) FROM tbl_pre_shipment_quantities psq, tbl_po_colors pc, tbl_po po WHERE po.id=pc.po_id AND po.status='C' AND psq.color_id=pc.id AND psq.po_id=pc.po_id AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate') AND pc.etd_required <= CURDATE( ) AND pc.po_id='$iPoId' AND pc.style_id IN ($sBrandStyles)";
			$objDb3->query($sSQL);

			$iShipQuantity = $objDb3->getField(0, 0);


			$fDeviation = (@(($iShipQuantity / $iOrderQuantity) * 100) - 100);
			$fDeviation = @round($fDeviation, 2);


			if ($fDeviation >= -2 && $fDeviation < 0)
				$iDev02Pos[$i] ++;

			else if ($fDeviation >= -5 && $fDeviation < -2)
				$iDev34Pos[$i] ++;

			else if ($fDeviation < -5)
				$iDev5Pos[$i] ++;
		}
	}


	$objChart = new XYChart(920, 650);
	$objChart->setPlotArea(80, 70, 780, 420);

	$objChart->addTitle("Deviation Chart", "verdana.ttf", 20);


	$objLedgend = $objChart->addLegend(70, 40, false, "", 8);
	$objLedgend->setBackground(Transparent);

	$objChart->yAxis2->setLabelFormat("{value}%");

	$objLabels = $objChart->xAxis->setLabels($sBrands);
	$objLabels->setFontAngle(90);


	$objLineLayer = $objChart->addLineLayer2( );

	$objDataSet = $objLineLayer->addDataSet($fDeviations, 0xff00ff, "Overall Deviation");
	$objDataSet->setDataSymbol(DiamondSymbol, 9);

	$objLineLayer->setUseYAxis2( );



	$objBarLayer = $objChart->addBarLayer2(Side);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setBarGap(0.2, TouchBar);

	$objBarLayer->addDataSet($iTotalPos, 0x99ff99, "Total POs");
	$objBarLayer->addDataSet($iDev02Pos, 0xffff00, "POs less than -2% deviation");
	$objBarLayer->addDataSet($iDev34Pos, 0xeb8d8d, "POs within -2 to -5% deviation");
	$objBarLayer->addDataSet($iDev5Pos, 0xff3333, "POs with more than -5% deviation");

	$objBarLayer->setAggregateLabelStyle( );
	$objBarLayer->setAggregateLabelFormat("{value}");

	$objChart->yAxis->setLabelFormat("{value}");


	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);
	$objChart->yAxis2->setWidth(2);

	$sChart = $objChart->makeSession("Deviation");
?>
			    <div class="tblSheet">
			      <br />
			      <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" />
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
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>