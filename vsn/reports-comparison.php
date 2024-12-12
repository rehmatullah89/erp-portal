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
	@require_once($sBaseDir."requires/fusion-charts.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Region1  = IO::intValue("Region1");
	$Year1    = IO::intValue("Year1");
	$Vendors1 = IO::getArray("Vendors1");
	$Brands1  = IO::getArray("Brands1");

	if (count($Vendors1) == 0)
		$sVendors1 = $_SESSION['Vendors'];

	else
		$sVendors1 = @implode(",", $Vendors1);

	if (count($Brands1) == 0)
		$sBrands1 = $_SESSION['Brands'];

	else
		$sBrands1 = @implode(",", $Brands1);



	$Region2  = IO::intValue("Region2");
	$Year2    = IO::intValue("Year2");
	$Vendors2 = IO::getArray("Vendors2");
	$Brands2  = IO::getArray("Brands2");

	if ($Region2 == 0 && $Year2 == 0 && count($Vendors2) == 0 && count($Brands2) == 0)
	{
		$Region2   = $Region1;
		$Year2     = $Year1;
		$Vendors2  = $Vendors1;
		$Brands2   = $Brands1;
		$sVendors2 = $sVendors1;
		$sBrands2  = $sBrands1;
	}

	else
	{
		if (count($Vendors2) == 0)
			$sVendors2 = $_SESSION['Vendors'];

		else
			$sVendors2 = @implode(",", $Vendors2);

		if (count($Brands2) == 0)
			$sBrands2 = $_SESSION['Brands'];

		else
			$sBrands2 = @implode(",", $Brands2);
	}


	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/vsn/reports-comparison.js"></script>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
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
			    <h1>Report Comparison</h1>

			    <form name="frmSearch" id="frmSearch" method="post" action="<?= $_SERVER['PHP_SELF'] ?>"  onsubmit="doSearch( );">
			    <table border="0" cellpadding="0" cellspacing="0" width="100%">
			      <tr>
			        <td width="50%">
			          <div id="SearchBar" style="border-right:solid 1px #777777;">
					    <table border="0" cellpadding="0" cellspacing="0" width="100%">
						  <tr>
						    <td width="50">Region</td>

						    <td width="115">
							  <select name="Region1">
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
	  	        		  	    <option value="<?= $sKey ?>"<?= (($sKey == $Region1) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
							  </select>
						    </td>

						    <td width="35">Year</td>

						    <td>
							  <select name="Year1">
<?
	for ($i = 2008; $i <= (date("Y") + 1); $i ++)
	{
?>
	  	        		  		<option value="<?= $i ?>"<?= (($i == $Year1 || ($Year1 == 0 && $i == date("Y"))) ? " selected" : "") ?>><?= $i ?></option>
<?
	}
?>
							  </select>
						    </td>
						  </tr>
						</table>
			          </div>

					  <div id="SubSearchBar" style="border-right:solid 1px #777777; height:auto; padding:6px 4px;">
					    <table border="0" cellpadding="0" cellspacing="0" width="100%">
						  <tr valign="top">
						    <td width="54">Vendor</td>

						    <td width="180">
							  <select name="Vendors1[]" id="Vendors1" size="10" multiple style="width:170px;">
<?
	foreach ($sVendorsList as $sKey => $sValue)
	{
?>
			                    <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Vendors1)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
							  </select>

							  <div>[ <a href="./" onclick="selectAll('Vendors1'); return false;">All</a> | <a href="./" onclick="clearAll('Vendors1'); return false;">None</a> ]</div>
						    </td>

						    <td width="45">Brand</td>

						    <td>
							  <select name="Brands1[]" id="Brands1" size="10" multiple style="width:170px;">
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
			              	    <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Brands1)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
							  </select>

							  <div>[ <a href="./" onclick="selectAll('Brands1'); return false;">All</a> | <a href="./" onclick="clearAll('Brands1'); return false;">None</a> ]</div>
						    </td>
						  </tr>
					    </table>
					  </div>
			        </td>

			        <td width="50%">
			          <div id="SearchBar" style="border-left:solid 1px #777777;">
					    <table border="0" cellpadding="0" cellspacing="0" width="100%">
						  <tr>
						    <td width="50">Region</td>

						    <td width="115">
							  <select name="Region2">
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
	  	        		  	    <option value="<?= $sKey ?>"<?= (($sKey == $Region2) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
							  </select>
						    </td>

						    <td width="35">Year</td>

						    <td width="100">
							  <select name="Year2">
<?
	for ($i = 2008; $i <= (date("Y") + 1); $i ++)
	{
?>
	  	        		  		<option value="<?= $i ?>"<?= (($i == $Year2 || ($Year2 == 0 && $i == date("Y"))) ? " selected" : "") ?>><?= $i ?></option>
<?
	}
?>
							  </select>
						    </td>

						    <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
						  </tr>
						</table>
			          </div>

					  <div id="SubSearchBar" style="border-left:solid 1px #777777; height:auto; padding:6px 4px;">
					    <table border="0" cellpadding="0" cellspacing="0" width="100%">
						  <tr valign="top">
						    <td width="54">Vendor</td>

						    <td width="180">
							  <select name="Vendors2[]" id="Vendors2" size="10" multiple style="width:170px;">
<?
	foreach ($sVendorsList as $sKey => $sValue)
	{
?>
			                    <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Vendors2)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
							  </select>

							  <div>[ <a href="./" onclick="selectAll('Vendors2'); return false;">All</a> | <a href="./" onclick="clearAll('Vendors2'); return false;">None</a> ]</div>
						    </td>

						    <td width="45">Brand</td>

						    <td>
							  <select name="Brands2[]" id="Brands2" size="10" multiple style="width:170px;">
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
			              	    <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Brands2)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
							  </select>

							  <div>[ <a href="./" onclick="selectAll('Brands2'); return false;">All</a> | <a href="./" onclick="clearAll('Brands2'); return false;">None</a> ]</div>
						    </td>
						  </tr>
					    </table>
					  </div>
			        </td>
			      </tr>
			    </table>
			    </form>

<?
	if ($_POST || $_GET)
	{
		$sMonths = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		$sClass  = array("evenRow", "oddRow");

		$iBrands       = @explode(",", $_SESSION['Brands']);
		$iVendors      = @explode(",", $_SESSION['Vendors']);
		$iBrandsCount  = @count($iBrands);
		$iVendorsCount = @count($iVendors);
?>
			    <br style="line-height:4px;" />

			    <table border="0" cellpadding="0" cellspacing="0" width="100%">
			      <tr>
			        <td width="50%" style="border-right:solid 1px #777777;">
<?
		$iYear     = $Year1;
		$sFromDate = "$iYear-01-01";
		$sToDate   = "$iYear-12-31";
		$iRegion   = $Region1;
		$sVendors  = $sVendors1;
		$sBrands   = $sBrands1;

		$iOriginalForecast = array( );
		$iRevisedForecast  = array( );
		$iBrandPlacements  = array( );
		$iVendorPlacements = array( );
		$iMonthPlacements  = array( );
		$iMonthShipments   = array( );
		$iOgacShipments    = array( );
		$iPreShipments     = array( );
		$iPostShipments    = array( );
		$iShipmentDetails  = array( );
?>
					  <div class="tblSheet">
					    <h1 class="darkGray small" style="margin:0px 1px 1px 0px;"><span>( Year : <?= $iYear ?> )</span><img src="images/h1/vsn/month-wise-placements.jpg" width="249" height="15" vspace="8" alt="" title="" /></h1>

			            <table border="1" bordercolor="#ffffff" cellpadding="8" cellspacing="0" width="100%">
			              <tr class="headerRow">
			                <td width="28%"><b>MONTH</b></td>
			                <td width="28%"><b>PLACEMENTS</b></td>
			                <td width="44%"><b>DETAIL</b></td>
			              </tr>
			            </table>

			            <table border="1" bordercolor="#ffffff" cellpadding="8" cellspacing="0" width="100%">
<?
		$sSQL = "SELECT DATE_FORMAT(pc.etd_required, '%m'), po.brand_id, po.vendor_id, COALESCE(SUM(pc.order_qty), 0)
				 FROM tbl_po po, tbl_po_colors pc
				 WHERE po.id=pc.po_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (pc.etd_required BETWEEN '$iYear-01-01' AND '$iYear-12-31')";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY DATE_FORMAT(pc.etd_required, '%Y-%m'), po.brand_id, po.vendor_id";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = (int)$objDb->getField($i, 0);
			$iBrandId  = $objDb->getField($i, 1);
			$iVendorId = $objDb->getField($i, 2);
			$iQuantity = $objDb->getField($i, 3);

			if (!isset($iBrandPlacements[$iMonth][$iBrandId]))
				$iBrandPlacements[$iMonth][$iBrandId] = 0;

			if (!isset($iVendorPlacements[$iMonth][$iVendorId]))
				$iVendorPlacements[$iMonth][$iVendorId] = 0;

			if (!isset($iMonthPlacements[$iMonth]))
				$iMonthPlacements[$iMonth] = 0;

			$iBrandPlacements[$iMonth][$iBrandId]   += $iQuantity;
			$iVendorPlacements[$iMonth][$iVendorId] += $iQuantity;
			$iMonthPlacements[$iMonth]              += $iQuantity;
			$iShipmentDetails[$iMonth]               = array( );
		}



		$sSQL = "SELECT month, COALESCE(SUM(quantity), 0) FROM tbl_forecasts WHERE (brand_id='0' OR brand_id IN ($sBrands)) AND (vendor_id='0' OR vendor_id IN ($sVendors)) AND year='$iYear'";

		if ($iRegion > 0)
			$sSQL .= " AND country_id='$iRegion' ";

		$sSQL .= " GROUP BY month";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = $objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iOriginalForecast[$iMonth] = $iQuantity;
		}



		$sSQL = "SELECT month, COALESCE(SUM(quantity), 0) FROM tbl_revised_forecasts WHERE (brand_id='0' OR brand_id IN ($sBrands)) AND (vendor_id='0' OR vendor_id IN ($sVendors)) AND year='$iYear'";

		if ($iRegion > 0)
			$sSQL .= " AND country_id='$iRegion' ";

		$sSQL .= " GROUP BY month";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = $objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iRevisedForecast[$iMonth] = $iQuantity;
		}



		$sSQL = "SELECT DATE_FORMAT(psd.handover_to_forwarder, '%m'), COALESCE(SUM(psq.quantity), 0)
				 FROM tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (psd.handover_to_forwarder BETWEEN '$iYear-01-01' AND '$iYear-12-31')";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY DATE_FORMAT(psd.handover_to_forwarder, '%Y-%m')";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = (int)$objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iMonthShipments[$iMonth] = $iQuantity;
		}



		$sSQL = "SELECT DATE_FORMAT(pc.etd_required, '%m'), DATE_FORMAT(psd.handover_to_forwarder, '%b %Y'), COALESCE(SUM(psq.quantity), 0)
				 FROM tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (pc.etd_required BETWEEN '$iYear-01-01' AND '$iYear-12-31')";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY DATE_FORMAT(pc.etd_required, '%Y-%m'), DATE_FORMAT(psd.handover_to_forwarder, '%Y-%m')
		           ORDER BY DATE_FORMAT(pc.etd_required, '%Y-%m'), DATE_FORMAT(psd.handover_to_forwarder, '%Y-%m')";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth     = (int)$objDb->getField($i, 0);
			$sMonthYear = $objDb->getField($i, 1);
			$iQuantity  = $objDb->getField($i, 2);

			$iOgacShipments[$iMonth] += $iQuantity;

			$iShipmentDetails[$iMonth][$sMonthYear] = $iQuantity;
		}



		$sSQL = "SELECT DATE_FORMAT(psd.shipping_date, '%m'), COALESCE(SUM(psq.quantity), 0)
				 FROM tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (psd.shipping_date BETWEEN '$iYear-01-01' AND '$iYear-12-31')";

		if ($iRegion > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY DATE_FORMAT(psd.shipping_date, '%Y-%m')";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = (int)$objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iMonthActualShipments[$iMonth] = $iQuantity;
		}



		$sSQL = "SELECT DATE_FORMAT(psd.handover_to_forwarder, '%m'), COALESCE(SUM(psq.quantity), 0)
		         FROM tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (psd.handover_to_forwarder BETWEEN '".($iYear - 1)."-12-01' AND '$iYear-12-31')
				 AND DATE_FORMAT(psd.handover_to_forwarder, '%Y-%m') < DATE_FORMAT(pc.etd_required, '%Y-%m')";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY DATE_FORMAT(psd.handover_to_forwarder, '%Y-%m')";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = (int)$objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iPreShipments[(($iMonth == 12) ? 1 : $iMonth)] = $iQuantity;
		}



		$sSQL = "SELECT DATE_FORMAT(psd.handover_to_forwarder, '%m'), COALESCE(SUM(psq.quantity), 0)
		         FROM tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (psd.handover_to_forwarder BETWEEN '$iYear-01-01' AND '$iYear-12-31')
				 AND DATE_FORMAT(psd.handover_to_forwarder, '%Y-%m') > DATE_FORMAT(pc.etd_required, '%Y-%m')";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY DATE_FORMAT(psd.handover_to_forwarder, '%Y-%m')";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = (int)$objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iPostShipments[$iMonth] = $iQuantity;
		}



		$iTotalDefects = array( );
		$iTotalQty     = array( );

		$sSQL = "SELECT DATE_FORMAT(qa.audit_date, '%m'),
		                COALESCE(SUM(qa.total_gmts), 0),
		                SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id) )
				 FROM tbl_po po, tbl_qa_reports qa
				 WHERE po.id=qa.po_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (qa.audit_date BETWEEN '$iYear-01-01' AND '$iYear-12-31')
				 AND qa.audit_stage!=''
				 AND report_id!='6'";

		if ($iRegion > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY DATE_FORMAT(qa.audit_date, '%Y-%m')";


		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = (int)$objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);
			$iDefects  = $objDb->getField($i, 2);

			$iTotalQty[$iMonth]     = $iQuantity;
			$iTotalDefects[$iMonth] = $iDefects;
		}



		$sSQL = "SELECT DATE_FORMAT(qa.audit_date, '%m'),
		                SUM( (SELECT COALESCE(SUM(actual_1 + actual_2 + actual_3), 0) FROM tbl_gf_rolls_info WHERE audit_id=qa.id) ),
		                SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=qa.id) )
				 FROM tbl_po po, tbl_qa_reports qa
				 WHERE po.id=qa.po_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (qa.audit_date BETWEEN '$iYear-01-01' AND '$iYear-12-31')
				 AND qa.audit_stage!=''
				 AND report_id='6'";

		if ($iRegion > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY DATE_FORMAT(qa.audit_date, '%Y-%m')";


		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = (int)$objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);
			$iDefects  = $objDb->getField($i, 2);

			$iTotalQty[$iMonth]     += $iQuantity;
			$iTotalDefects[$iMonth] += $iDefects;
		}



		for ($i = 1; $i <= 12; $i ++)
		{
			if ($iMonthPlacements[$i] > 0)
			{
				$sVendorsTip = '<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">';

				for ($j = 0; $j < $iVendorsCount; $j ++)
				{
					if ($iVendorPlacements[$i][$iVendors[$j]] > 0)
					{
						$sVendorsTip .= '<tr valign=\"top\">';
						$sVendorsTip .= ('<td width=\"70%\">'.$sVendorsList[$iVendors[$j]].'</td>');
						$sVendorsTip .= ('<td width=\"30%\">'.formatNumber($iVendorPlacements[$i][$iVendors[$j]], false).'</td>');
						$sVendorsTip .= '</tr>';
					}
				}

				$sVendorsTip .= '</table>';


				$sBrandsTip = '<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">';

				for ($j = 0; $j < $iBrandsCount; $j ++)
				{
					if ($iBrandPlacements[$i][$iBrands[$j]] > 0)
					{
						$sBrandsTip .= '<tr valign=\"top\">';
						$sBrandsTip .= ('<td width=\"70%\">'.$sBrandsList[$iBrands[$j]].'</td>');
						$sBrandsTip .= ('<td width=\"30%\">'.formatNumber($iBrandPlacements[$i][$iBrands[$j]], false).'</td>');
						$sBrandsTip .= '</tr>';
					}
				}

				$sBrandsTip .= '</table>';


				$fDeviation = @((($iOgacShipments[$i] / $iMonthPlacements[$i]) * 100) - 100);
				$fDeviation = (($iMonthPlacements[$i] > 0) ? $fDeviation : 0);
				$fDefectRate = @round( (($iTotalDefects[$i] / $iTotalQty[$i]) * 100), 2);

				$sReportsTip = '<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">';
				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"70%\">Original Forecast</td>');
				$sReportsTip .= ('<td width=\"30%\">'.formatNumber($iOriginalForecast[$i], false).'</td>');
				$sReportsTip .= '</tr>';
				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"70%\">Revised Forecast</td>');
				$sReportsTip .= ('<td width=\"30%\">'.formatNumber($iRevisedForecast[$i], false).'</td>');
				$sReportsTip .= '</tr>';
				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"70%\">Ordered Qty</td>');
				$sReportsTip .= ('<td width=\"30%\">'.formatNumber($iMonthPlacements[$i], false).'</td>');
				$sReportsTip .= '</tr>';
				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"70%\">Shipped Qty</td>');
				$sReportsTip .= ('<td width=\"30%\">'.formatNumber($iOgacShipments[$i], false).'</td>');
				$sReportsTip .= '</tr>';
				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"70%\">Deviation</td>');
				$sReportsTip .= ('<td width=\"30%\">'.formatNumber($fDeviation).'%</td>');
				$sReportsTip .= '</tr>';
				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"70%\">Defect Rate</td>');
				$sReportsTip .= ('<td width=\"30%\">'.formatNumber($fDefectRate).'%</td>');
				$sReportsTip .= '</tr>';

				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"100%\" colspan=\"2\"><b style=\"display:block; padding:5px; background:#eeeeee;\">Shipment Details</b></td>');
				$sReportsTip .= '</tr>';
				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"70%\">'.$sMonths[($i - 1)].' Shipped Qty</td>');
				$sReportsTip .= ('<td width=\"30%\">'.formatNumber($iMonthShipments[$i], false).'</td>');
				$sReportsTip .= '</tr>';
				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"70%\">Pre-Ship Quantity</td>');
				$sReportsTip .= ('<td width=\"30%\">'.formatNumber($iPreShipments[$i], false).'</td>');
				$sReportsTip .= '</tr>';
				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"70%\">Carry Forward Shipment</td>');
				$sReportsTip .= ('<td width=\"30%\">'.formatNumber($iPostShipments[$i], false).'</td>');
				$sReportsTip .= '</tr>';

				if (@is_array($iShipmentDetails[$i]) && count($iShipmentDetails[$i]) > 0 && $iOgacShipments[$i] > 0)
				{
					$sReportsTip .= '<tr>';
					$sReportsTip .= ('<td width=\"100%\" colspan=\"2\"><b style=\"display:block; padding:5px; background:#eeeeee;\">Shipments of '.$sMonths[($i - 1)].' specific POs</b></td>');
					$sReportsTip .= '</tr>';

					$sReportsTip .= '<tr>';
					$sReportsTip .= '<td width=\"100%\" colspan=\"2\">';

					$sReportsTip .= '<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">';

					foreach($iShipmentDetails[$i] as $sMonthYear => $iShipQty)
					{
						$iPercentage = @round((($iShipQty / $iMonthPlacements[$i]) * 100), 2);
						$iPercentage = (($iPercentage > 100) ? 100 : $iPercentage);

						$sReportsTip .= '<tr>';
						$sReportsTip .= ('<td width=\"28%\">'.$sMonthYear.'</td>');
						$sReportsTip .= ('<td width=\"72%\"><span style=\"float:right; font-size:9px; padding-right:10px;\">'.formatNumber($iPercentage).'%</span><div style=\"width:104px; padding-top:2px;\"><div style=\"background:#f0f0f0; border:solid 1px #666666; padding:1px; height:6px;\"><div style=\"width:'.$iPercentage.'px; height:6px; background:#b6e600;\"></div></div></div></td>');
						$sReportsTip .= '</tr>';
					}


					if ($fDeviation > 0)
					{
						$iPercentage = $fDeviation;

						$sReportsTip .= '<tr>';
						$sReportsTip .= ('<td width=\"28%\">Deviation</td>');
						$sReportsTip .= ('<td width=\"72%\"><span style=\"float:right; font-size:9px; padding-right:10px;\">'.formatNumber($iPercentage).'%</span><div style=\"width:104px; padding-top:2px;\"><div style=\"background:#f0f0f0; border:solid 1px #666666; padding:1px; height:6px;\"><div style=\"width:'.$iPercentage.'px; height:6px; background:#ff0000;\"></div></div></div></td>');
						$sReportsTip .= '</tr>';
					}

					$sReportsTip .= '</table>';

					$sReportsTip .= '</td>';
					$sReportsTip .= '</tr>';
				}

				$sReportsTip .= '</table>';
			}
?>
			              <tr class="<?= $sClass[($i % 2)] ?>">
			                <td width="28%"><b><?= $sMonths[($i - 1)] ?></b></td>
			                <td width="28%"><?= formatNumber($iMonthPlacements[$i], false) ?></td>

			                <td width="44%">
<?
			if ($iMonthPlacements[$i] > 0)
			{
?>
			                  <span id="Vendor<?= $i ?>_1"><u>Vendors</u></span>
			                  -
			                  <span id="Brand<?= $i ?>_1"><u>Brands</u></span>
			                  -
			                  <span id="Report<?= $i ?>_1"><u>Reports</u></span>


							  <script type="text/javascript">
							  <!--
								  new Tip('Vendor<?= $i ?>_1',
								          "<?= $sVendorsTip ?>",
								          { title:'Vendors wise Placements', stem:'leftMiddle', hook:{ tip:'leftMiddle', mouse:true }, offset:{ x:1, y:1 } });

								  new Tip('Brand<?= $i ?>_1',
								          "<?= $sBrandsTip ?>",
								          { title:'Brands wise Placements', stem:'leftMiddle', hook:{ tip:'leftMiddle', mouse:true }, offset:{ x:1, y:1 } });

								  new Tip('Report<?= $i ?>_1',
								          "<?= $sReportsTip ?>",
								          { title:'Reports', stem:'leftMiddle', hook:{ tip:'leftMiddle', mouse:true }, offset:{ x:1, y:1 } });
							  -->
							  </script>
<?
			}
?>
			                </td>
			              </tr>

<?
		}
?>
			              <tr class="footerRow">
			                <td width="30%"><b>Total (<?= $iYear ?>)</b></td>
			                <td width="30%"><b><?= formatNumber(@array_sum($iMonthPlacements), false) ?></b></td>
			                <td width="40%"></td>
			              </tr>

<?
		$iLastYear = ($iYear - 1);

		$sSQL = "SELECT COALESCE(SUM(pc.order_qty), 0)
				 FROM tbl_po po, tbl_po_colors pc
				 WHERE po.id=pc.po_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (pc.etd_required BETWEEN '$iLastYear-01-01' AND '$iLastYear-12-31')";

		if ($iRegion > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$objDb->query($sSQL);

		$iPlacements = $objDb->getField(0, 0);
?>
			              <tr class="footerRow">
			                <td width="30%"><b>Total (<?= $iLastYear ?>)</b></td>
			                <td width="30%"><b><?= formatNumber($iPlacements, false) ?></b></td>
			                <td width="40%"></td>
			              </tr>
			            </table>


			            <table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
			              <tr>
			                <td width="100%"><h1 class="green small"><img src="images/h1/vsn/quick-view.jpg" width="104" height="18" alt="" title="" style="margin-top:6px;" /></h1></td>
			              </tr>

			              <tr>
			                <td>
			                  <div id="MonthStatsChart1">loading...</div>

			                  <script type="text/javascript">
			                  <!--
								  var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", "MonthStats1", "460", "250", "0", "1");

                                  objChart.setXMLData("<chart caption='Month wise Statistics' formatNumberScale='0' showValues='0' showLabels='0' chartBottomMargin='5'>" +
                                                      "<categories>" +
<?
		$sLabels = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

		for ($i = 0; $i < 12; $i ++)
		{
?>
                                                      "<category label='<?= $sLabels[$i] ?>' />" +
<?
		}
?>
                                                      "</categories>" +

                                                      "<dataset seriesName='Forecast'>" +
  <?
  		for ($i = 1; $i <= 12; $i ++)
  		{
  ?>
                                                      "<set value='<?= $iOriginalForecast[$i] ?>' />" +
  <?
  		}
?>
                                                      "</dataset>" +

                                                      "<dataset seriesName='Placements' renderAs='Line'>" +
<?
		for ($i = 1; $i <= 12; $i ++)
		{
?>
                                                      "<set value='<?= $iMonthPlacements[$i] ?>' />" +
<?
		}
?>
                                                      "</dataset>" +

                                                      "<dataset seriesName='OGAC' renderAs='Line'>" +
<?
		for ($i = 1; $i <= 12; $i ++)
		{
?>
                                                      "<set value='<?= $iMonthOgacShipments[$i] ?>' />" +
<?
		}
?>
                                                      "</dataset>" +

                                                      "<dataset seriesName='Shipments' renderAs='Line'>" +
<?
		for ($i = 1; $i <= 12; $i ++)
		{
?>
                                                      "<set value='<?= $iMonthActualShipments[$i] ?>' />" +
<?
		}
?>
                                                      "</dataset>" +

                                                      "<dataset seriesName='Revised' renderAs='Line'>" +
<?
		for ($i = 1; $i <= 12; $i ++)
		{
?>
                                                      "<set value='<?= $iRevisedForecast[$i] ?>' />" +
<?
		}
?>
                                                      "</dataset>" +
                                                      "</chart>");


								  objChart.render("MonthStatsChart1");
    						  -->
    						  </script>

							  <table border="0" cellpadding="0" cellspacing="0" width="100%">
							    <tr>
								  <td width="75"></td>
								  <td width="30" align="center"><span id="Month1_Statistics_1"><u>Jan</u></span></td>
								  <td width="30" align="center"><span id="Month2_Statistics_1"><u>Feb</u></span></td>
								  <td width="30" align="center"><span id="Month3_Statistics_1"><u>Mar</u></span></td>
								  <td width="30" align="center"><span id="Month4_Statistics_1"><u>Apr</u></span></td>
								  <td width="30" align="center"><span id="Month5_Statistics_1"><u>May</u></span></td>
								  <td width="30" align="center"><span id="Month6_Statistics_1"><u>Jun</u></span></td>
								  <td width="30" align="center"><span id="Month7_Statistics_1"><u>Jul</u></span></td>
								  <td width="30" align="center"><span id="Month8_Statistics_1"><u>Aug</u></span></td>
								  <td width="30" align="center"><span id="Month9_Statistics_1"><u>Sep</u></span></td>
								  <td width="30" align="center"><span id="Month10_Statistics_1"><u>Oct</u></span></td>
								  <td width="30" align="center"><span id="Month11_Statistics_1"><u>Nov</u></span></td>
								  <td width="30" align="center"><span id="Month12_Statistics_1"><u>Dec</u></span></td>
								  <td></td>
							    </tr>
							  </table>

							  <br style="line-height:5px;" />

							  <script type="text/javascript">
							  <!--
<?
		for ($i = 1; $i <= 12; $i ++)
		{
			$iMonth = str_pad($i, 2, '0', STR_PAD_LEFT);
			$iDays  = @cal_days_in_month(CAL_GREGORIAN, $iMonth, $iYear);
?>
					  				new Tip('Month<?= $i ?>_Statistics_1', { ajax: { url:'ajax/vsn/get-month-statistics.php', options: { method:'post', parameters:'Month=<?= $i ?>&Mode=VendorsBrands&Region=<?= $iRegion ?>&FromDate=<?= $sFromDate ?>&ToDate=<?= $sToDate ?>&Vendors=<?= $sVendors ?>&Brands=<?= $sBrands ?>', onCreate: function( ) { showProcessing( ); }, onComplete: function( ) { hideProcessing( ); } } }, title:'<?= $sMonths[($i - 1)] ?> <?= substr($iYear, 2) ?> Statistics', stem:'topLeft', offset:{ x:1, y:1 }, width:720 });
<?
		}
?>

							  -->
							  </script>
			                </td>
			              </tr>

			              <tr>
			                <td><br /></td>
			              </tr>

			              <tr>
			                <td>
<?
		$fOtp = array(0,0,0,0,0,0,0,0,0,0,0,0);

		$sSQL = "SELECT DATE_FORMAT(pc.etd_required, '%m'), COALESCE(SUM(pc.order_qty), 0), COALESCE(SUM(pc.ontime_qty), 0)
				 FROM tbl_po po, tbl_po_colors pc
				 WHERE po.id=pc.po_id
				 		AND po.vendor_id IN ($sVendors)
				 		AND po.brand_id IN ($sBrands)
				 		AND (pc.etd_required BETWEEN '$iYear-01-01' AND '$iYear-12-31') AND pc.etd_required <= CURDATE( )";

		if ($iRegion > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY DATE_FORMAT(pc.etd_required, '%Y-%m')";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth     = (int)$objDb->getField($i, 0);
			$iOrderQty  = $objDb->getField($i, 1);
			$iOnTimeQty = $objDb->getField($i, 2);

			$fOtp[($iMonth - 1)] = @round((($iOnTimeQty / $iOrderQty) * 100), 2);
		}
?>
			                  <div id="MonthOtpChart1">loading...</div>

			                  <script type="text/javascript">
			                  <!--
								  var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "MonthOtp", "460", "250", "0", "1");

                                  objChart.setXMLData("<chart caption='Month wise OTP' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='0' decimals='1' numberSuffix='%' chartBottomMargin='5'>" +
<?
		for ($i = 0; $i < 12; $i ++)
		{
?>
                                                      "<set label='<?= $sLabels[$i] ?>' value='<?= $fOtp[$i] ?>' />" +
<?
		}
?>
                                                      "</chart>");


								  objChart.render("MonthOtpChart1");
    						  -->
    						  </script>

							  <table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
								  <td width="40"></td>
								  <td width="32" align="center"><span id="Month1_OTP_1"><u>Jan</u></span></td>
								  <td width="32" align="center"><span id="Month2_OTP_1"><u>Feb</u></span></td>
								  <td width="32" align="center"><span id="Month3_OTP_1"><u>Mar</u></span></td>
								  <td width="32" align="center"><span id="Month4_OTP_1"><u>Apr</u></span></td>
								  <td width="32" align="center"><span id="Month5_OTP_1"><u>May</u></span></td>
								  <td width="32" align="center"><span id="Month6_OTP_1"><u>Jun</u></span></td>
								  <td width="32" align="center"><span id="Month7_OTP_1"><u>Jul</u></span></td>
								  <td width="32" align="center"><span id="Month8_OTP_1"><u>Aug</u></span></td>
								  <td width="32" align="center"><span id="Month9_OTP_1"><u>Sep</u></span></td>
								  <td width="32" align="center"><span id="Month10_OTP_1"><u>Oct</u></span></td>
								  <td width="32" align="center"><span id="Month11_OTP_1"><u>Nov</u></span></td>
								  <td width="32" align="center"><span id="Month12_OTP_1"><u>Dec</u></span></td>
								  <td></td>
								</tr>
							  </table>

							  <br style="line-height:5px;" />

							  <script type="text/javascript">
							  <!--
<?
		for ($i = 1; $i <= 12; $i ++)
		{
			$iMonth = str_pad($i, 2, '0', STR_PAD_LEFT);
			$iDays  = @cal_days_in_month(CAL_GREGORIAN, $iMonth, $iYear);
?>
					  				new Tip('Month<?= $i ?>_OTP_1', { ajax: { url:'ajax/vsn/get-month-otp.php', options: { method:'post', parameters:'Month=<?= $i ?>&Mode=VendorsBrands&Region=<?= $iRegion ?>&FromDate=<?= $sFromDate ?>&ToDate=<?= $sToDate ?>&Vendors=<?= $sVendors ?>&Brands=<?= $sBrands ?>', onCreate: function( ) { showProcessing( ); }, onComplete: function( ) { hideProcessing( ); } } }, title:'<?= $sMonths[($i - 1)] ?> <?= substr($iYear, 2) ?> OTP', stem:'topLeft', offset:{ x:1, y:1 }, width:400 });
<?
		}
?>

							  -->
							  </script>
			                </td>
			              </tr>
			            </table>
			    	  </div>
			        </td>



			        <td width="50%" style="border-left:solid 1px #777777;">
<?
		$iYear     = $Year2;
		$sFromDate = "$iYear-01-01";
		$sToDate   = "$iYear-12-31";
		$iRegion   = $Region2;
		$sVendors  = $sVendors2;
		$sBrands   = $sBrands2;

		$iOriginalForecast = array( );
		$iRevisedForecast  = array( );
		$iBrandPlacements  = array( );
		$iVendorPlacements = array( );
		$iMonthPlacements  = array( );
		$iMonthShipments   = array( );
		$iOgacShipments    = array( );
		$iPreShipments     = array( );
		$iPostShipments    = array( );
		$iShipmentDetails  = array( );
?>
					  <div class="tblSheet">
					    <h1 class="darkGray small" style="margin:0px 1px 1px 0px;"><span>( Year : <?= $iYear ?> )</span><img src="images/h1/vsn/month-wise-placements.jpg" width="249" height="15" vspace="8" alt="" title="" /></h1>

			            <table border="1" bordercolor="#ffffff" cellpadding="8" cellspacing="0" width="100%">
			              <tr class="headerRow">
			                <td width="28%"><b>MONTH</b></td>
			                <td width="28%"><b>PLACEMENTS</b></td>
			                <td width="44%"><b>DETAIL</b></td>
			              </tr>
			            </table>

			            <table border="1" bordercolor="#ffffff" cellpadding="8" cellspacing="0" width="100%">
<?
		$sSQL = "SELECT DATE_FORMAT(pc.etd_required, '%m'), po.brand_id, po.vendor_id, COALESCE(SUM(pc.order_qty), 0)
				 FROM tbl_po po, tbl_po_colors pc
				 WHERE po.id=pc.po_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (pc.etd_required BETWEEN '$iYear-01-01' AND '$iYear-12-31')";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY DATE_FORMAT(pc.etd_required, '%Y-%m'), po.brand_id, po.vendor_id";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = (int)$objDb->getField($i, 0);
			$iBrandId  = $objDb->getField($i, 1);
			$iVendorId = $objDb->getField($i, 2);
			$iQuantity = $objDb->getField($i, 3);

			if (!isset($iBrandPlacements[$iMonth][$iBrandId]))
				$iBrandPlacements[$iMonth][$iBrandId] = 0;

			if (!isset($iVendorPlacements[$iMonth][$iVendorId]))
				$iVendorPlacements[$iMonth][$iVendorId] = 0;

			if (!isset($iMonthPlacements[$iMonth]))
				$iMonthPlacements[$iMonth] = 0;

			$iBrandPlacements[$iMonth][$iBrandId]   += $iQuantity;
			$iVendorPlacements[$iMonth][$iVendorId] += $iQuantity;
			$iMonthPlacements[$iMonth]              += $iQuantity;
			$iShipmentDetails[$iMonth]               = array( );
		}



		$sSQL = "SELECT month, COALESCE(SUM(quantity), 0) FROM tbl_forecasts WHERE (brand_id='0' OR brand_id IN ($sBrands)) AND (vendor_id='0' OR vendor_id IN ($sVendors)) AND year='$iYear'";

		if ($iRegion > 0)
			$sSQL .= " AND country_id='$iRegion' ";

		$sSQL .= " GROUP BY month";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = $objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iOriginalForecast[$iMonth] = $iQuantity;
		}



		$sSQL = "SELECT month, COALESCE(SUM(quantity), 0) FROM tbl_revised_forecasts WHERE (brand_id='0' OR brand_id IN ($sBrands)) AND (vendor_id='0' OR vendor_id IN ($sVendors)) AND year='$iYear'";

		if ($iRegion > 0)
			$sSQL .= " AND country_id='$iRegion' ";

		$sSQL .= " GROUP BY month";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = $objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iRevisedForecast[$iMonth] = $iQuantity;
		}



		$sSQL = "SELECT DATE_FORMAT(psd.handover_to_forwarder, '%m'), COALESCE(SUM(psq.quantity), 0)
				 FROM tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (psd.handover_to_forwarder BETWEEN '$iYear-01-01' AND '$iYear-12-31')";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY DATE_FORMAT(psd.handover_to_forwarder, '%Y-%m')";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = (int)$objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iMonthShipments[$iMonth] = $iQuantity;
		}



		$sSQL = "SELECT DATE_FORMAT(pc.etd_required, '%m'), DATE_FORMAT(psd.handover_to_forwarder, '%b %Y'), COALESCE(SUM(psq.quantity), 0)
				 FROM tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (pc.etd_required BETWEEN '$iYear-01-01' AND '$iYear-12-31')";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY DATE_FORMAT(pc.etd_required, '%Y-%m'), DATE_FORMAT(psd.handover_to_forwarder, '%Y-%m')
		           ORDER BY DATE_FORMAT(pc.etd_required, '%Y-%m'), DATE_FORMAT(psd.handover_to_forwarder, '%Y-%m')";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth     = (int)$objDb->getField($i, 0);
			$sMonthYear = $objDb->getField($i, 1);
			$iQuantity  = $objDb->getField($i, 2);

			$iOgacShipments[$iMonth] += $iQuantity;

			$iShipmentDetails[$iMonth][$sMonthYear] = $iQuantity;
		}



		$sSQL = "SELECT DATE_FORMAT(psd.shipping_date, '%m'), COALESCE(SUM(psq.quantity), 0)
				 FROM tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (psd.shipping_date BETWEEN '$iYear-01-01' AND '$iYear-12-31')";

		if ($iRegion > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY DATE_FORMAT(psd.shipping_date, '%Y-%m')";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = (int)$objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iMonthActualShipments[$iMonth] = $iQuantity;
		}



		$sSQL = "SELECT DATE_FORMAT(psd.handover_to_forwarder, '%m'), COALESCE(SUM(psq.quantity), 0)
		         FROM tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (psd.handover_to_forwarder BETWEEN '".($iYear - 1)."-12-01' AND '$iYear-12-31')
				 AND DATE_FORMAT(psd.handover_to_forwarder, '%Y-%m') < DATE_FORMAT(pc.etd_required, '%Y-%m')";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY DATE_FORMAT(psd.handover_to_forwarder, '%Y-%m')";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = (int)$objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iPreShipments[(($iMonth == 12) ? 1 : $iMonth)] = $iQuantity;
		}



		$sSQL = "SELECT DATE_FORMAT(psd.handover_to_forwarder, '%m'), COALESCE(SUM(psq.quantity), 0)
		         FROM tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (psd.handover_to_forwarder BETWEEN '$iYear-01-01' AND '$iYear-12-31')
				 AND DATE_FORMAT(psd.handover_to_forwarder, '%Y-%m') > DATE_FORMAT(pc.etd_required, '%Y-%m')";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY DATE_FORMAT(psd.handover_to_forwarder, '%Y-%m')";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = (int)$objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iPostShipments[$iMonth] = $iQuantity;
		}



		$iTotalDefects = array( );
		$iTotalQty     = array( );

		$sSQL = "SELECT DATE_FORMAT(qa.audit_date, '%m'),
		                COALESCE(SUM(qa.total_gmts), 0),
		                SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id) )
				 FROM tbl_po po, tbl_qa_reports qa
				 WHERE po.id=qa.po_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (qa.audit_date BETWEEN '$iYear-01-01' AND '$iYear-12-31')
				 AND qa.audit_stage!=''
				 AND report_id!='6'";

		if ($iRegion > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY DATE_FORMAT(qa.audit_date, '%Y-%m')";


		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = (int)$objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);
			$iDefects  = $objDb->getField($i, 2);

			$iTotalQty[$iMonth]     = $iQuantity;
			$iTotalDefects[$iMonth] = $iDefects;
		}



		$sSQL = "SELECT DATE_FORMAT(qa.audit_date, '%m'),
		                SUM( (SELECT COALESCE(SUM(actual_1 + actual_2 + actual_3), 0) FROM tbl_gf_rolls_info WHERE audit_id=qa.id) ),
		                SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=qa.id) )
				 FROM tbl_po po, tbl_qa_reports qa
				 WHERE po.id=qa.po_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (qa.audit_date BETWEEN '$iYear-01-01' AND '$iYear-12-31')
				 AND qa.audit_stage!=''
				 AND report_id='6'";

		if ($iRegion > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY DATE_FORMAT(qa.audit_date, '%Y-%m')";


		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = (int)$objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);
			$iDefects  = $objDb->getField($i, 2);

			$iTotalQty[$iMonth]     += $iQuantity;
			$iTotalDefects[$iMonth] += $iDefects;
		}



		for ($i = 1; $i <= 12; $i ++)
		{
			if ($iMonthPlacements[$i] > 0)
			{
				$sVendorsTip = '<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">';

				for ($j = 0; $j < $iVendorsCount; $j ++)
				{
					if ($iVendorPlacements[$i][$iVendors[$j]] > 0)
					{
						$sVendorsTip .= '<tr valign=\"top\">';
						$sVendorsTip .= ('<td width=\"70%\">'.$sVendorsList[$iVendors[$j]].'</td>');
						$sVendorsTip .= ('<td width=\"30%\">'.formatNumber($iVendorPlacements[$i][$iVendors[$j]], false).'</td>');
						$sVendorsTip .= '</tr>';
					}
				}

				$sVendorsTip .= '</table>';


				$sBrandsTip = '<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">';

				for ($j = 0; $j < $iBrandsCount; $j ++)
				{
					if ($iBrandPlacements[$i][$iBrands[$j]] > 0)
					{
						$sBrandsTip .= '<tr valign=\"top\">';
						$sBrandsTip .= ('<td width=\"70%\">'.$sBrandsList[$iBrands[$j]].'</td>');
						$sBrandsTip .= ('<td width=\"30%\">'.formatNumber($iBrandPlacements[$i][$iBrands[$j]], false).'</td>');
						$sBrandsTip .= '</tr>';
					}
				}

				$sBrandsTip .= '</table>';


				$fDeviation = @((($iOgacShipments[$i] / $iMonthPlacements[$i]) * 100) - 100);
				$fDeviation = (($iMonthPlacements[$i] > 0) ? $fDeviation : 0);
				$fDefectRate = @round( (($iTotalDefects[$i] / $iTotalQty[$i]) * 100), 2);

				$sReportsTip = '<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">';
				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"70%\">Original Forecast</td>');
				$sReportsTip .= ('<td width=\"30%\">'.formatNumber($iOriginalForecast[$i], false).'</td>');
				$sReportsTip .= '</tr>';
				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"70%\">Revised Forecast</td>');
				$sReportsTip .= ('<td width=\"30%\">'.formatNumber($iRevisedForecast[$i], false).'</td>');
				$sReportsTip .= '</tr>';
				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"70%\">Ordered Qty</td>');
				$sReportsTip .= ('<td width=\"30%\">'.formatNumber($iMonthPlacements[$i], false).'</td>');
				$sReportsTip .= '</tr>';
				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"70%\">Shipped Qty</td>');
				$sReportsTip .= ('<td width=\"30%\">'.formatNumber($iOgacShipments[$i], false).'</td>');
				$sReportsTip .= '</tr>';
				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"70%\">Deviation</td>');
				$sReportsTip .= ('<td width=\"30%\">'.formatNumber($fDeviation).'%</td>');
				$sReportsTip .= '</tr>';
				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"70%\">Defect Rate</td>');
				$sReportsTip .= ('<td width=\"30%\">'.formatNumber($fDefectRate).'%</td>');
				$sReportsTip .= '</tr>';

				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"100%\" colspan=\"2\"><b style=\"display:block; padding:5px; background:#eeeeee;\">Shipment Details</b></td>');
				$sReportsTip .= '</tr>';
				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"70%\">'.$sMonths[($i - 1)].' Shipped Qty</td>');
				$sReportsTip .= ('<td width=\"30%\">'.formatNumber($iMonthShipments[$i], false).'</td>');
				$sReportsTip .= '</tr>';
				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"70%\">Pre-Ship Quantity</td>');
				$sReportsTip .= ('<td width=\"30%\">'.formatNumber($iPreShipments[$i], false).'</td>');
				$sReportsTip .= '</tr>';
				$sReportsTip .= '<tr>';
				$sReportsTip .= ('<td width=\"70%\">Carry Forward Shipment</td>');
				$sReportsTip .= ('<td width=\"30%\">'.formatNumber($iPostShipments[$i], false).'</td>');
				$sReportsTip .= '</tr>';

				if (@is_array($iShipmentDetails[$i]) && count($iShipmentDetails[$i]) > 0 && $iOgacShipments[$i] > 0)
				{
					$sReportsTip .= '<tr>';
					$sReportsTip .= ('<td width=\"100%\" colspan=\"2\"><b style=\"display:block; padding:5px; background:#eeeeee;\">Shipments of '.$sMonths[($i - 1)].' specific POs</b></td>');
					$sReportsTip .= '</tr>';

					$sReportsTip .= '<tr>';
					$sReportsTip .= '<td width=\"100%\" colspan=\"2\">';

					$sReportsTip .= '<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">';

					foreach($iShipmentDetails[$i] as $sMonthYear => $iShipQty)
					{
						$iPercentage = @round((($iShipQty / $iMonthPlacements[$i]) * 100), 2);
						$iPercentage = (($iPercentage > 100) ? 100 : $iPercentage);

						$sReportsTip .= '<tr>';
						$sReportsTip .= ('<td width=\"28%\">'.$sMonthYear.'</td>');
						$sReportsTip .= ('<td width=\"72%\"><span style=\"float:right; font-size:9px; padding-right:10px;\">'.formatNumber($iPercentage).'%</span><div style=\"width:104px; padding-top:2px;\"><div style=\"background:#f0f0f0; border:solid 1px #666666; padding:1px; height:6px;\"><div style=\"width:'.$iPercentage.'px; height:6px; background:#b6e600;\"></div></div></div></td>');
						$sReportsTip .= '</tr>';
					}


					if ($fDeviation > 0)
					{
						$iPercentage = $fDeviation;

						$sReportsTip .= '<tr>';
						$sReportsTip .= ('<td width=\"28%\">Deviation</td>');
						$sReportsTip .= ('<td width=\"72%\"><span style=\"float:right; font-size:9px; padding-right:10px;\">'.formatNumber($iPercentage).'%</span><div style=\"width:104px; padding-top:2px;\"><div style=\"background:#f0f0f0; border:solid 1px #666666; padding:1px; height:6px;\"><div style=\"width:'.$iPercentage.'px; height:6px; background:#ff0000;\"></div></div></div></td>');
						$sReportsTip .= '</tr>';
					}

					$sReportsTip .= '</table>';

					$sReportsTip .= '</td>';
					$sReportsTip .= '</tr>';
				}

				$sReportsTip .= '</table>';
			}
?>
			              <tr class="<?= $sClass[($i % 2)] ?>">
			                <td width="28%"><b><?= $sMonths[($i - 1)] ?></b></td>
			                <td width="28%"><?= formatNumber($iMonthPlacements[$i], false) ?></td>

			                <td width="44%">
<?
			if ($iMonthPlacements[$i] > 0)
			{
?>
			                  <span id="Vendor<?= $i ?>_2"><u>Vendors</u></span>
			                  -
			                  <span id="Brand<?= $i ?>_2"><u>Brands</u></span>
			                  -
			                  <span id="Report<?= $i ?>_2"><u>Reports</u></span>


							  <script type="text/javascript">
							  <!--
								  new Tip('Vendor<?= $i ?>_2',
								          "<?= $sVendorsTip ?>",
								          { title:'Vendors wise Placements', stem:'leftMiddle', hook:{ tip:'leftMiddle', mouse:true }, offset:{ x:1, y:1 } });

								  new Tip('Brand<?= $i ?>_2',
								          "<?= $sBrandsTip ?>",
								          { title:'Brands wise Placements', stem:'leftMiddle', hook:{ tip:'leftMiddle', mouse:true }, offset:{ x:1, y:1 } });

								  new Tip('Report<?= $i ?>_2',
								          "<?= $sReportsTip ?>",
								          { title:'Reports', stem:'leftMiddle', hook:{ tip:'leftMiddle', mouse:true }, offset:{ x:1, y:1 } });
							  -->
							  </script>
<?
			}
?>
			                </td>
			              </tr>

<?
		}
?>
			              <tr class="footerRow">
			                <td width="30%"><b>Total (<?= $iYear ?>)</b></td>
			                <td width="30%"><b><?= formatNumber(@array_sum($iMonthPlacements), false) ?></b></td>
			                <td width="40%"></td>
			              </tr>

<?
		$iLastYear = ($iYear - 1);

		$sSQL = "SELECT COALESCE(SUM(pc.order_qty), 0)
				 FROM tbl_po po, tbl_po_colors pc
				 WHERE po.id=pc.po_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (pc.etd_required BETWEEN '$iLastYear-01-01' AND '$iLastYear-12-31')";

		if ($iRegion > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$objDb->query($sSQL);

		$iPlacements = $objDb->getField(0, 0);
?>
			              <tr class="footerRow">
			                <td width="30%"><b>Total (<?= $iLastYear ?>)</b></td>
			                <td width="30%"><b><?= formatNumber($iPlacements, false) ?></b></td>
			                <td width="40%"></td>
			              </tr>
			            </table>


			            <table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
			              <tr>
			                <td width="100%"><h1 class="green small"><img src="images/h1/vsn/quick-view.jpg" width="104" height="18" alt="" title="" style="margin-top:6px;" /></h1></td>
			              </tr>

			              <tr>
			                <td>
			                  <div id="MonthStatsChart2">loading...</div>

			                  <script type="text/javascript">
			                  <!--
								  var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", "MonthStats2", "460", "250", "0", "1");

                                  objChart.setXMLData("<chart caption='Month wise Statistics' formatNumberScale='0' showValues='0' showLabels='0' chartBottomMargin='5'>" +
                                                      "<categories>" +
<?
		$sLabels = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

		for ($i = 0; $i < 12; $i ++)
		{
?>
                                                      "<category label='<?= $sLabels[$i] ?>' />" +
<?
		}
?>
                                                      "</categories>" +

                                                      "<dataset seriesName='Forecast'>" +
  <?
  		for ($i = 1; $i <= 12; $i ++)
  		{
  ?>
                                                      "<set value='<?= $iOriginalForecast[$i] ?>' />" +
  <?
  		}
?>
                                                      "</dataset>" +

                                                      "<dataset seriesName='Placements' renderAs='Line'>" +
<?
		for ($i = 1; $i <= 12; $i ++)
		{
?>
                                                      "<set value='<?= $iMonthPlacements[$i] ?>' />" +
<?
		}
?>
                                                      "</dataset>" +

                                                      "<dataset seriesName='OGAC' renderAs='Line'>" +
<?
		for ($i = 1; $i <= 12; $i ++)
		{
?>
                                                      "<set value='<?= $iMonthOgacShipments[$i] ?>' />" +
<?
		}
?>
                                                      "</dataset>" +

                                                      "<dataset seriesName='Shipments' renderAs='Line'>" +
<?
		for ($i = 1; $i <= 12; $i ++)
		{
?>
                                                      "<set value='<?= $iMonthActualShipments[$i] ?>' />" +
<?
		}
?>
                                                      "</dataset>" +

                                                      "<dataset seriesName='Revised' renderAs='Line'>" +
<?
		for ($i = 1; $i <= 12; $i ++)
		{
?>
                                                      "<set value='<?= $iRevisedForecast[$i] ?>' />" +
<?
		}
?>
                                                      "</dataset>" +
                                                      "</chart>");


								  objChart.render("MonthStatsChart2");
    						  -->
    						  </script>

							  <table border="0" cellpadding="0" cellspacing="0" width="100%">
							   <tr>
							 	  <td width="75"></td>
								  <td width="30" align="center"><span id="Month1_Statistics_2"><u>Jan</u></span></td>
								  <td width="30" align="center"><span id="Month2_Statistics_2"><u>Feb</u></span></td>
								  <td width="30" align="center"><span id="Month3_Statistics_2"><u>Mar</u></span></td>
								  <td width="30" align="center"><span id="Month4_Statistics_2"><u>Apr</u></span></td>
								  <td width="30" align="center"><span id="Month5_Statistics_2"><u>May</u></span></td>
								  <td width="30" align="center"><span id="Month6_Statistics_2"><u>Jun</u></span></td>
								  <td width="30" align="center"><span id="Month7_Statistics_2"><u>Jul</u></span></td>
								  <td width="30" align="center"><span id="Month8_Statistics_2"><u>Aug</u></span></td>
								  <td width="30" align="center"><span id="Month9_Statistics_2"><u>Sep</u></span></td>
								  <td width="30" align="center"><span id="Month10_Statistics_2"><u>Oct</u></span></td>
								  <td width="30" align="center"><span id="Month11_Statistics_2"><u>Nov</u></span></td>
								  <td width="30" align="center"><span id="Month12_Statistics_2"><u>Dec</u></span></td>
								  <td></td>
							    </tr>
							  </table>

							<br style="line-height:5px;" />

							<script type="text/javascript">
							<!--
<?
		for ($i = 1; $i <= 12; $i ++)
		{
			$iMonth = str_pad($i, 2, '0', STR_PAD_LEFT);
			$iDays  = @cal_days_in_month(CAL_GREGORIAN, $iMonth, $iYear);
?>
					  				new Tip('Month<?= $i ?>_Statistics_2', { ajax: { url:'ajax/vsn/get-month-statistics.php', options: { method:'post', parameters:'Month=<?= $i ?>&Mode=VendorsBrands&Region=<?= $iRegion ?>&FromDate=<?= $sFromDate ?>&ToDate=<?= $sToDate ?>&Vendors=<?= $sVendors ?>&Brands=<?= $sBrands ?>', onCreate: function( ) { showProcessing( ); }, onComplete: function( ) { hideProcessing( ); } } }, title:'<?= $sMonths[($i - 1)] ?> <?= substr($iYear, 2) ?> Statistics', stem:'topLeft', offset:{ x:1, y:1 }, width:720 });
<?
		}
?>

							  -->
							  </script>
			                </td>
			              </tr>

			              <tr>
			                <td><br /></td>
			              </tr>

			              <tr>
			                <td>
<?
		$fOtp = array(0,0,0,0,0,0,0,0,0,0,0,0);


		$sSQL = "SELECT DATE_FORMAT(pc.etd_required, '%m'), COALESCE(SUM(pc.order_qty), 0), COALESCE(SUM(pc.ontime_qty), 0)
				 FROM tbl_po po, tbl_po_colors pc
				 WHERE po.id=pc.po_id
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (pc.etd_required BETWEEN '$iYear-01-01' AND '$iYear-12-31') AND pc.etd_required <= CURDATE( )";

		if ($iRegion > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iRegion' AND parent_id='0' AND sourcing='Y') ";

		$sSQL .= " GROUP BY DATE_FORMAT(pc.etd_required, '%Y-%m')";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth     = (int)$objDb->getField($i, 0);
			$iOrderQty  = $objDb->getField($i, 1);
			$iOnTimeQty = $objDb->getField($i, 2);

			$fOtp[($iMonth - 1)] = @round((($iOnTimeQty / $iOrderQty) * 100), 2);
		}
?>
			                  <div id="MonthOtpChart2">loading...</div>

			                  <script type="text/javascript">
			                  <!--
								  var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "MonthOtp", "460", "250", "0", "1");

                                  objChart.setXMLData("<chart caption='Month wise OTP' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='0' decimals='1' numberSuffix='%' chartBottomMargin='5'>" +
<?
		for ($i = 0; $i < 12; $i ++)
		{
?>
                                                      "<set label='<?= $sLabels[$i] ?>' value='<?= $fOtp[$i] ?>' />" +
<?
		}
?>
                                                      "</chart>");


								  objChart.render("MonthOtpChart2");
    						  -->
    						  </script>

							  <table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
								  <td width="40"></td>
								  <td width="32" align="center"><span id="Month1_OTP_2"><u>Jan</u></span></td>
								  <td width="32" align="center"><span id="Month2_OTP_2"><u>Feb</u></span></td>
								  <td width="32" align="center"><span id="Month3_OTP_2"><u>Mar</u></span></td>
								  <td width="32" align="center"><span id="Month4_OTP_2"><u>Apr</u></span></td>
								  <td width="32" align="center"><span id="Month5_OTP_2"><u>May</u></span></td>
								  <td width="32" align="center"><span id="Month6_OTP_2"><u>Jun</u></span></td>
								  <td width="32" align="center"><span id="Month7_OTP_2"><u>Jul</u></span></td>
								  <td width="32" align="center"><span id="Month8_OTP_2"><u>Aug</u></span></td>
								  <td width="32" align="center"><span id="Month9_OTP_2"><u>Sep</u></span></td>
								  <td width="32" align="center"><span id="Month10_OTP_2"><u>Oct</u></span></td>
								  <td width="32" align="center"><span id="Month11_OTP_2"><u>Nov</u></span></td>
								  <td width="32" align="center"><span id="Month12_OTP_2"><u>Dec</u></span></td>
								  <td></td>
								</tr>
							  </table>

							  <br style="line-height:5px;" />

							  <script type="text/javascript">
							  <!--
<?
		for ($i = 1; $i <= 12; $i ++)
		{
			$iMonth = str_pad($i, 2, '0', STR_PAD_LEFT);
			$iDays  = @cal_days_in_month(CAL_GREGORIAN, $iMonth, $iYear);
?>
					  				new Tip('Month<?= $i ?>_OTP_2', { ajax: { url:'ajax/vsn/get-month-otp.php', options: { method:'post', parameters:'Month=<?= $i ?>&Mode=VendorsBrands&Region=<?= $iRegion ?>&FromDate=<?= $sFromDate ?>&ToDate=<?= $sToDate ?>&Vendors=<?= $sVendors ?>&Brands=<?= $sBrands ?>', onCreate: function( ) { showProcessing( ); }, onComplete: function( ) { hideProcessing( ); } } }, title:'<?= $sMonths[($i - 1)] ?> <?= substr($iYear, 2) ?> OTP', stem:'topLeft', offset:{ x:1, y:1 }, width:400 });
<?
		}
?>

							  -->
							  </script>
			                </td>
			              </tr>
			            </table>
			    	  </div>
			        </td>
			      </tr>
			    </table>
<?
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