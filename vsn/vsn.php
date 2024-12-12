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

	$Mode        = IO::strValue("Mode");
	$Region      = IO::intValue("Region");
	$FromDate    = IO::strValue("FromDate");
	$ToDate      = IO::strValue("ToDate");
	$Year        = IO::intValue("Year");
	$Vendors     = IO::getArray("Vendors");
	$Brands      = IO::getArray("Brands");
	$Departments = IO::getArray("Departments");
	$PoType      = IO::strValue("PoType");

	// All Account Vendors & Brands .. skip PCC & Matrix Brands/Vendors if user is not Guest
	if ($_SESSION["Guest"] == "Y" || $_SESSION["Admin"] == "Y")
	{
		$sAllVendors = $_SESSION['Vendors'];
		$sAllBrands  = $_SESSION['Brands'];
	}

	else
	{
		$sTemp    = @explode(",", $_SESSION['Vendors']);
		$sVendors = array( );

		foreach ($sTemp as $iVendor)
		{
			if ($iVendor == 147)
				continue;

			$sVendors[] = $iVendor;
		}

		$sAllVendors = @implode(",", $sVendors);


		$sTemp   = @explode(",", $_SESSION['Brands']);
		$sBrands = array( );

		foreach ($sTemp as $iBrand)
		{
			if ($iBrand == 130) //  || $iBrand == 77 || $iBrand == 167
				continue;

			$sBrands[] = $iBrand;
		}

		$sAllBrands = @implode(",", $sBrands);
	}



	if (count($Vendors) == 0)
		$sVendors = $sAllVendors;

	else
		$sVendors = @implode(",", $Vendors);


	if (count($Brands) == 0)
		$sBrands = $sAllBrands;

	else
		$sBrands = @implode(",", $Brands);


	$Mode = (($Mode == "") ? "Vendors" : $Mode);


	if ($Mode == "Departments")
	{
		$sSQL = ("SELECT brands FROM tbl_departments WHERE id IN (".@implode(",", $Departments).") ORDER BY id");
		$objDb->query($sSQL);

		$iCount  = $objDb->getCount( );
		$iBrands = array( );

		for ($i = 0; $i < $iCount; $i ++)
			$iBrands = @array_unique(array_merge($iBrands, @explode(",", $objDb->getField($i, 0))));


		$Brands = array( );

		for ($i = 0; $i < count($iBrands); $i ++)
		{
			if (@in_array($iBrands[$i], @explode(",", $sAllBrands)))
				$Brands[] = $iBrands[$i];
		}


		$sBrands = @implode(",", $Brands);
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/vsn/vsn.js"></script>
  <script type="text/javascript" src="scripts/glider.js"></script>
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
			    <h1>Vendor Status Navigator</h1>

			    <form name="frmSearch" id="frmSearch" method="post" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="doSearch( );">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="40">Mode</td>

			          <td width="145">
			            <select id="Mode" name="Mode" onchange="refineSearch(this.value);">
			              <option value="Vendors"<?= (($Mode == "Vendors") ? " selected" : "") ?>>Vendors</option>
			              <option value="Brands"<?= (($Mode == "Brands") ? " selected" : "") ?>>Brands</option>
			              <option value="VendorsBrands"<?= (($Mode == "VendorsBrands") ? " selected" : "") ?>>Vendors & Brands</option>
<?
	if (@strpos($_SESSION['Email'], "apaprelco.com") !== FALSE)
	{
?>
			              <option value="Departments"<?= (($Mode == "Departments") ? " selected" : "") ?>>Departments</option>
<?
	}
?>
			            </select>
			          </td>

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

					  <td width="35">Year</td>

					  <td width="100">
					    <select name="Year" onchange="setYear(this.value);">
						  <option value="">All Years</option>
<?
	for ($i = 2008; $i <= (date("Y") + 1); $i ++)
	{
?>
	  	        		  <option value="<?= $i ?>"<?= (($i == $Year) ? " selected" : "") ?>><?= $i ?></option>
<?
	}
?>
					    </select>
					  </td>

					  <td width="40">From</td>
					  <td width="76"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:68px;" onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;" onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center">To</td>
					  <td width="76"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:68px;" onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;" onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="65" align="center">[ <a href="./" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;" style="color:#eeeeee;">Clear</a> ]</td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div class="tblSheet">
			      <div style="margin:0px 1px 1px 0px;">
			        <table border="0" cellpadding="0" cellspacing="0" width="100%">
			          <tr>
			            <td width="255"><h1 class="darkGray small" style="margin:0px;"><img src="images/h1/vsn/on-time-performance.jpg" width="227" height="15" vspace="8" alt="" title="" /></h1></td>
			            <td bgcolor="#888888"><b style="color:#ffffff; padding-left:10px;">REFINE YOUR SEARCH</b> &nbsp; <b>( <a href="./" onclick="checkAll( ); return false;" class="sheetLink">Check ALL</a> | <a href="./" onclick="clearAll( ); return false;" class="sheetLink">Clear ALL</a> )</b></td>

			            <td bgcolor="#888888" align="right">
			              <span style="color:#ffffff;">PO Type:</span>
			              <select name="PoType" id="PoType">
						    <option value="">All Types</option>
	  	        		    <option value="SDP"<?= (($PoType == "SDP") ? " selected" : "") ?>>SDP</option>
	  	        		    <option value="Non-SDP"<?= (($PoType == "Non-SDP") ? " selected" : "") ?>>Non-SDP</option>
			              </select>
			            </td>

			            <td bgcolor="#888888" width="5"></td>
			          </tr>

			          <tr valign="top">
			            <td>

			              <div style="padding:5px;">
			                <table border="0" cellpadding="5" cellspacing="0" width="100%">
<?
	$iYear  = date("Y");
	$iMonth = date("m");
	$iDays  = date("d");


	$sSQL = "SELECT COALESCE(SUM(pc.order_qty), 0), COALESCE(SUM(pc.ontime_qty), 0)
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
			 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
			 AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
			 AND po.vendor_id IN ($sVendors)
			 AND po.brand_id IN ($sBrands)
			 AND (pc.etd_required BETWEEN '$iYear-$iMonth-01' AND '$iYear-$iMonth-$iDays')
			 AND po.status='C'";

	if ($PoType != "")
		$sSQL .= " AND po.order_type='$PoType' ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	$objDb->query($sSQL);

	$iOrderQty  = $objDb->getField(0, 0);
	$iOnTimeQty = $objDb->getField(0, 1);


	$fOtp = @round((($iOnTimeQty / $iOrderQty) * 100), 2);
?>
			                  <tr>
			                    <td width="70%"><b>OTP for Running Month</b></td>
			                    <td width="30%"><b style="color:#ff0000;"><?= formatNumber($fOtp) ?>%</b></td>
			                  </tr>

<?
	$sToday    = date("Y-m-d");
	$sLastWeek = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 7), date("Y")));

	$sSQL = "SELECT COALESCE(SUM(pc.order_qty), 0), COALESCE(SUM(pc.ontime_qty), 0)
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
			 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
			 AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
			 AND po.vendor_id IN ($sVendors)
			 AND po.brand_id IN ($sBrands)
			 AND (pc.etd_required BETWEEN '$sLastWeek' AND '$sToday')
			 AND pc.etd_required <= CURDATE( ) AND po.status='C'";

	if ($PoType != "")
		$sSQL .= " AND po.order_type='$PoType' ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	$objDb->query($sSQL);

	$iOrderQty  = $objDb->getField(0, 0);
	$iOnTimeQty = $objDb->getField(0, 1);


	$fOtp = @round((($iOnTimeQty / $iOrderQty) * 100), 2);
?>
			                  <tr>
			                    <td><b>OTP for Last Week</b></td>
			                    <td><b style="color:#ff0000;"><?= formatNumber($fOtp) ?>%</b></td>
			                  </tr>

<?
	$sFirstDate = date("Y-m-d", mktime(0, 0, 0, (date("m") - 1), "01", date("Y")));

	$iLastMonth = ((date("m") == 1) ? 12 : (date("m") - 1));
	$iLastYear  = (($iLastMonth == 12) ? (date("Y") - 1) : date("Y"));
	$sLastMonth = str_pad($iLastMonth, 2, '0', STR_PAD_LEFT);
	$iDays      = @cal_days_in_month(CAL_GREGORIAN, $iLastMonth, $iLastYear);
	$sLastDate  = ($iLastYear."-".$sLastMonth."-".$iDays);

	$sSQL = "SELECT COALESCE(SUM(pc.order_qty), 0), COALESCE(SUM(pc.ontime_qty), 0)
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
			 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
			 AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
			 AND po.vendor_id IN ($sVendors)
			 AND po.brand_id IN ($sBrands)
			 AND (pc.etd_required BETWEEN '$sFirstDate' AND '$sLastDate')
			 AND pc.etd_required <= CURDATE( ) AND po.status='C'";

	if ($PoType != "")
		$sSQL .= " AND po.order_type='$PoType' ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	$objDb->query($sSQL);

	$iOrderQty  = $objDb->getField(0, 0);
	$iOnTimeQty = $objDb->getField(0, 1);

	$fOtp = @round((($iOnTimeQty / $iOrderQty) * 100), 2);
?>
			                  <tr>
			                    <td><b>OTP for Last Month</b></td>
			                    <td><b style="color:#ff0000;"><?= formatNumber($fOtp) ?>%</b></td>
			                  </tr>

<?
	if (@in_array(date("m"), array(1,2,3)))
	{
		$sFirstDate = ((date("Y") - 1)."-10-01");
		$sLastDate  = ((date("Y") - 1)."-12-31");
	}

	else if (@in_array(date("m"), array(4,5,6)))
	{
		$sFirstDate = (date("Y")."-01-01");
		$sLastDate  = (date("Y")."-03-31");
	}

	else if (@in_array(date("m"), array(7,8,9)))
	{
		$sFirstDate = (date("Y")."-04-01");
		$sLastDate  = (date("Y")."-06-30");
	}

	else if (@in_array(date("m"), array(10,11,12)))
	{
		$sFirstDate = (date("Y")."-07-01");
		$sLastDate  = (date("Y")."-09-30");
	}

	$sSQL = "SELECT COALESCE(SUM(pc.order_qty), 0), COALESCE(SUM(pc.ontime_qty), 0)
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
			 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
			 AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
			 AND po.vendor_id IN ($sVendors)
			 AND po.brand_id IN ($sBrands)
			 AND (pc.etd_required BETWEEN '$sFirstDate' AND '$sLastDate')
			 AND pc.etd_required <= CURDATE( ) AND po.status='C'";

	if ($PoType != "")
		$sSQL .= " AND po.order_type='$PoType' ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	$objDb->query($sSQL);

	$iOrderQty  = $objDb->getField(0, 0);
	$iOnTimeQty = $objDb->getField(0, 1);


	$fOtp = @round((($iOnTimeQty / $iOrderQty) * 100), 2);
?>
			                  <tr>
			                    <td><b>OTP for Last Quarter</b></td>
			                    <td><b style="color:#ff0000;"><?= formatNumber($fOtp) ?>%</b></td>
			                  </tr>

<?
	$sFirstDate = date("Y-m-d", mktime(0, 0, 0, (date("m") - 1), "01", date("Y")));

	$iLastMonth = ((date("m") == 1) ? 12 : (date("m") - 1));
	$iLastYear  = (($iLastMonth == 12) ? (date("Y") - 1) : date("Y"));
	$sLastMonth = str_pad($iLastMonth, 2, '0', STR_PAD_LEFT);
	$iDays      = @cal_days_in_month(CAL_GREGORIAN, $iLastMonth, $iLastYear);
	$sLastDate  = ($iLastYear."-".$sLastMonth."-".$iDays);

	$sSQL = "SELECT COALESCE(SUM(pc.order_qty), 0)
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
			 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
			 AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
			 AND po.vendor_id IN ($sVendors)
			 AND po.brand_id IN ($sBrands)
			 AND (pc.etd_required BETWEEN '$sFirstDate' AND '$sLastDate')";

	if ($PoType != "")
		$sSQL .= " AND po.order_type='$PoType' ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	$objDb->query($sSQL);

	$iOrderQty = $objDb->getField(0, 0);


	$sSQL = "SELECT COALESCE(SUM(psq.quantity), 0)
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
			 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B'
			 AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
			 AND po.vendor_id IN ($sVendors)
			 AND po.brand_id IN ($sBrands)
			 AND (pc.etd_required BETWEEN '$sFirstDate' AND '$sLastDate')";

	if ($PoType != "")
		$sSQL .= " AND po.order_type='$PoType' ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	$objDb->query($sSQL);

	$iShipQty = $objDb->getField(0, 0);

	$fDeviation = @((($iShipQty / $iOrderQty) * 100) - 100);
	$fDeviation = (($iOrderQty > 0) ? $fDeviation : 0);
?>
			                  <tr valign="top">
			                    <td><b>Deviation for Last Month</b></td>
			                    <td><b style="color:#ff0000;"><?= formatNumber($fDeviation) ?>%</b></td>
			                  </tr>

<?
	$sFirstDate = date("Y-m-d", mktime(0, 0, 0, (date("m") - 6), "01", date("Y")));

	$iLastMonth = ((date("m") == 1) ? 12 : (date("m") - 1));
	$iLastYear  = (($iLastMonth == 12) ? (date("Y") - 1) : date("Y"));
	$sLastMonth = str_pad($iLastMonth, 2, '0', STR_PAD_LEFT);
	$iDays      = @cal_days_in_month(CAL_GREGORIAN, $iLastMonth, $iLastYear);
	$sLastDate  = ($iLastYear."-".$sLastMonth."-".$iDays);

	$sSQL = "SELECT COALESCE(SUM(pc.order_qty), 0)
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
			 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
			 AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
			 AND po.vendor_id IN ($sVendors)
			 AND po.brand_id IN ($sBrands)
			 AND (pc.etd_required BETWEEN '$sFirstDate' AND '$sLastDate')";

	if ($PoType != "")
		$sSQL .= " AND po.order_type='$PoType' ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	$objDb->query($sSQL);

	$iOrderQty = $objDb->getField(0, 0);


	$sSQL = "SELECT COALESCE(SUM(psq.quantity), 0)
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
			 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B'
			 AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
			 AND po.vendor_id IN ($sVendors)
			 AND po.brand_id IN ($sBrands)
			 AND (pc.etd_required BETWEEN '$sFirstDate' AND '$sLastDate')";

	if ($PoType != "")
		$sSQL .= " AND po.order_type='$PoType' ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	$objDb->query($sSQL);

	$iShipQty = $objDb->getField(0, 0);


	$fDeviation = @((($iShipQty / $iOrderQty) * 100) - 100);
	$fDeviation = (($iOrderQty > 0) ? $fDeviation : 0);
?>
			                  <tr valign="top">
			                    <td><b>Deviation for Last 6 Months</b></td>
			                    <td><b style="color:#ff0000;"><?= formatNumber($fDeviation) ?>%</b></td>
			                  </tr>

<?
	if (@in_array(date("m"), array(1,2,3)))
	{
		$sFirstDate = ((date("Y") - 1)."-10-01");
		$sLastDate  = ((date("Y") - 1)."-12-31");
	}

	else if (@in_array(date("m"), array(4,5,6)))
	{
		$sFirstDate = (date("Y")."-01-01");
		$sLastDate  = (date("Y")."-03-31");
	}

	else if (@in_array(date("m"), array(7,8,9)))
	{
		$sFirstDate = (date("Y")."-04-01");
		$sLastDate  = (date("Y")."-06-30");
	}

	else if (@in_array(date("m"), array(10,11,12)))
	{
		$sFirstDate = (date("Y")."-07-01");
		$sLastDate  = (date("Y")."-09-30");
	}


	$sSQL = "SELECT COALESCE(SUM(pc.order_qty), 0)
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
			 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
			 AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
			 AND po.vendor_id IN ($sVendors)
			 AND po.brand_id IN ($sBrands)
			 AND (pc.etd_required BETWEEN '$sFirstDate' AND '$sLastDate')";

	if ($PoType != "")
		$sSQL .= " AND po.order_type='$PoType' ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	$objDb->query($sSQL);

	$iOrderQty = $objDb->getField(0, 0);


	$sSQL = "SELECT COALESCE(SUM(psq.quantity), 0)
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
			 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B'
			 AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
			 AND po.vendor_id IN ($sVendors)
			 AND po.brand_id IN ($sBrands)
			 AND (pc.etd_required BETWEEN '$sFirstDate' AND '$sLastDate')";

	if ($PoType != "")
		$sSQL .= " AND po.order_type='$PoType' ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	$objDb->query($sSQL);

	$iShipQty = $objDb->getField(0, 0);


	$fDeviation = @((($iShipQty / $iOrderQty) * 100) - 100);
	$fDeviation = (($iOrderQty > 0) ? $fDeviation : 0);
?>
			                  <tr valign="top">
			                    <td><b>Deviation for Last Quarter</b></td>
			                    <td><b style="color:#ff0000;"><?= formatNumber($fDeviation) ?>%</b></td>
			                  </tr>
			                </table>
			              </div>

			            </td>

			            <td bgcolor="#f6f6f6" colspan="3">
			              <div style="padding:10px;">

			                <table border="0" cellpadding="0" cellspacing="0" width="100%">
			                  <tr height="5">
			                    <td width="5" bgcolor="#494949"></td>
			                    <td width="8" bgcolor="#494949"></td>
			                    <td></td>
			                    <td width="8" bgcolor="#494949"></td>
			                    <td width="5" bgcolor="#494949"></td>
			                  </tr>

			                  <tr>
			                    <td width="5" bgcolor="#494949"></td>
			                    <td width="8"></td>

			                    <td>

			                      <div id="VendorsBlock" style="display:<?= (($Mode == "Vendors" || $Mode == "VendorsBrands") ? "block" : "none") ?>;">
			                        <div>
			                          <h4 style="padding-top:5px;">Vendors</h4>
			                          <table border="0" cellpadding="1" cellspacing="0" width="100%">
<?
	$sVendorsList     = array( );
	$sBrandsList      = array( );
	$sDepartmentsList = array( );


	$sSQL = "SELECT id, vendor FROM tbl_vendors WHERE FIND_IN_SET(id, '$sAllVendors') AND parent_id='0' AND sourcing='Y' ORDER BY vendor";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount;)
	{
?>
			                            <tr valign="top">
<?
		for ($j = 0; $j < 4; $j ++)
		{
			if ($i < $iCount)
			{
				$sKey   = $objDb->getField($i, "id");
				$sValue = $objDb->getField($i, "vendor");

				$sVendorsList[$sKey] = $sValue;
?>
			                              <td width="22"><input type="checkbox" class="vendors" name="Vendors[]" value="<?= $sKey ?>" <?= ((@in_array($sKey, $Vendors)) ? "checked" : "") ?> /></td>
			                              <td><?= $sValue ?></td>
<?
				 $i ++;
			}

			else
			{
?>
			                              <td width="22"></td>
			                              <td></td>
<?
			}
		}
?>
			                            </tr>
<?
	}
?>
			                          </table>
			                        </div>
			                      </div>


			                      <div id="BrandsBlock" style="display:<?= (($Mode == "Brands" || $Mode == "VendorsBrands") ? "block" : "none") ?>;">
			                        <div>
			                          <h4 style="padding-top:5px;">Brands</h4>
			                          <table border="0" cellpadding="1" cellspacing="0" width="100%">
<?
	$sSQL = "SELECT id, brand FROM tbl_brands WHERE parent_id>'0' AND FIND_IN_SET(id, '$sAllBrands') ORDER BY brand";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount;)
	{
?>
			                            <tr valign="top">
<?
		for ($j = 0; $j < 4; $j ++)
		{
			if ($i < $iCount)
			{
				$sKey   = $objDb->getField($i, "id");
				$sValue = $objDb->getField($i, "brand");

				$sBrandsList[$sKey] = $sValue;
?>
			                              <td width="22"><input type="checkbox" class="brands" name="Brands[]" value="<?= $sKey ?>" <?= ((@in_array($sKey, $Brands)) ? "checked" : "") ?> /></td>
			                              <td><?= $sValue ?></td>
<?
				 $i ++;
			}

			else
			{
?>
			                              <td width="22"></td>
			                              <td></td>
<?
			}
		}
?>
			                            </tr>
<?
	}
?>
			                          </table>
			                        </div>
			                      </div>


			                      <div id="DepartmentsBlock" style="display:<?= (($Mode == "Departments") ? "block" : "none") ?>;">
			                        <div>
			                          <h4 style="padding-top:5px;">Departments</h4>
			                          <table border="0" cellpadding="1" cellspacing="0" width="100%">
<?
	$sSQL = "SELECT id, department FROM tbl_departments WHERE brands!='' AND NOT ISNULL(brands) ORDER BY department";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount;)
	{
?>
			                            <tr valign="top">
<?
		for ($j = 0; $j < 3; $j ++)
		{
			if ($i < $iCount)
			{
				$sKey   = $objDb->getField($i, 0);
				$sValue = $objDb->getField($i, 1);

				$sDepartmentsList[$sKey] = $sValue;
?>
			                              <td width="22"><input type="checkbox" class="departments" name="Departments[]" value="<?= $sKey ?>" <?= ((@in_array($sKey, $Departments)) ? "checked" : "") ?> /></td>
			                              <td><?= $sValue ?></td>
<?
				 $i ++;
			}

			else
			{
?>
			                              <td width="22"></td>
			                              <td></td>
<?
			}
		}
?>
			                            </tr>
<?
	}
?>
			                          </table>
			                        </div>
			                      </div>
			                    </td>

			                    <td width="8"></td>
			                    <td width="5" bgcolor="#494949"></td>
			                  </tr>

			                  <tr height="5">
			                    <td width="5" bgcolor="#494949"></td>
			                    <td width="8" bgcolor="#494949"></td>
			                    <td></td>
			                    <td width="8" bgcolor="#494949"></td>
			                    <td width="5" bgcolor="#494949"></td>
			                  </tr>
			                </table>

			              </div>
			            </td>
			          </tr>
			       </table>
			     </div>
			    </div>
			    </form>

<?
	if ($_POST)
	{
		$iYear = (int)@substr($ToDate, 0, 4);

		if ($iYear == 0)
		{
			$iYear = date("Y");

			$sFromDate = (date("Y")."-01-01");
			$sToDate   = (date("Y")."-12-31");
		}

		else
		{
			$sFromDate = $FromDate;
			$sToDate   = $ToDate;
		}
?>
			    <br style="line-height:4px;" />

			    <div class="tblSheet">
                  <h1 class="darkGray small" style="margin:0px 1px 1px 0px;"><span>( Year : <?= $iYear ?> )</span><img src="images/h1/vsn/summary.jpg" width="98" height="15" vspace="8" alt="" title="" /></h1>

<?
		if ($Mode == "Vendors" || $Mode == "VendorsBrands")
		{
?>
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="150">

					    <table border="1" bordercolor="#ffffff" cellpadding="8" cellspacing="0" width="100%">
 						  <tr class="headerRow">
						    <td width="100%"><b>VENDORS</b></td>
						  </tr>

						  <tr bgcolor="#f6f6f6">
						    <td><b>PLACEMENTS</b></td>
						  </tr>

						  <tr bgcolor="#e6e6e6">
						    <td><b>FORECAST</b></td>
						  </tr>

						  <tr bgcolor="#f6f6f6">
						    <td><b>REVISED FORECAST</b></td>
						  </tr>

						  <tr class="footerRow">
						    <td><b>BALANCE</b></td>
						  </tr>
					    </table>

					  </td>

<?
			$iVendorsCount = @count($Vendors);

			if ($iVendorsCount > 0)
			{
?>
					  <td <?= (($iVendorsCount < 6) ? ('width="'.($iVendorsCount * 123).'"') : '') ?>>
						<div id="VendorsGlider">
						  <div class="scroller">
							<div class="content">
<?
				$iStats = array( );

				$sSQL = "SELECT vendor_id, COALESCE(SUM(quantity), 0)
				         FROM tbl_forecasts
				         WHERE vendor_id IN ($sVendors) AND (brand_id='0' OR brand_id IN ($sBrands)) AND year='$iYear'
				               AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$sBrands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

				if ($Region > 0)
					$sSQL .= " AND country_id='$Region' ";

				$sSQL .= " GROUP BY vendor_id";

				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					$iVendorId = $objDb->getField($i, 0);
					$iQuantity = $objDb->getField($i, 1);

					$iStats[$iVendorId]['Forecast'] = $iQuantity;
				}


				$sSQL = "SELECT vendor_id, COALESCE(SUM(quantity), 0)
				         FROM tbl_revised_forecasts
				         WHERE vendor_id IN ($sVendors) AND (brand_id='0' OR brand_id IN ($sBrands)) AND year='$iYear'
				               AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$sBrands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

				if ($Region > 0)
					$sSQL .= " AND country_id='$Region' ";

				$sSQL .= " GROUP BY vendor_id";

				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					$iVendorId = $objDb->getField($i, 0);
					$iQuantity = $objDb->getField($i, 1);

					$iStats[$iVendorId]['Revised'] = $iQuantity;
				}


				$sSQL = "SELECT po.vendor_id, COALESCE(SUM(pc.order_qty), 0)
						 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
						 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
						       AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
						       AND po.vendor_id IN ($sVendors)
						       AND po.brand_id IN ($sBrands)
						       AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')";

				if ($PoType != "")
					$sSQL .= " AND po.order_type='$PoType' ";

				if ($Region > 0)
					$sSQL .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

				$sSQL .= " GROUP BY po.vendor_id";

				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					$iVendorId = $objDb->getField($i, 0);
					$iQuantity = $objDb->getField($i, 1);

					$iStats[$iVendorId]['Placements'] = $iQuantity;
				}


				for ($i = 0; $i < $iVendorsCount; $i ++)
				{
					$iVendorId        = $Vendors[$i];
					$iPlacements      = $iStats[$iVendorId]['Placements'];
					$iForecast        = $iStats[$iVendorId]['Forecast'];
					$iRevisedForecast = $iStats[$iVendorId]['Revised'];
?>

							  <div class="section" id="section<?= $i ?>">
							    <table border="1" bordercolor="#ffffff" cellpadding="8" cellspacing="0" width="100%">
								  <tr class="headerRow">
								    <td width="100%"><b title="<?= $sVendorsList[$iVendorId] ?>"><?= substr($sVendorsList[$iVendorId], 0, 12) ?></b></td>
								  </tr>

								  <tr bgcolor="#f6f6f6">
								    <td><?= formatNumber($iPlacements, false) ?></td>
								  </tr>

								  <tr bgcolor="#e6e6e6">
								    <td><?= formatNumber($iForecast, false) ?></td>
								  </tr>

								  <tr bgcolor="#f6f6f6">
								    <td><?= formatNumber($iRevisedForecast, false) ?></td>
								  </tr>

								  <tr class="footerRow">
								    <td><b><?= formatNumber(($iForecast - $iPlacements), false) ?></b></td>
								  </tr>
							    </table>
							  </div>
<?
				}
?>
							</div>
						  </div>
						</div>

						<script type="text/javascript">
						<!--
							var objVendorsGlider = new Glider('VendorsGlider', { duration:1.0, maxDisplay:6 });
						-->
						</script>
					  </td>
<?
			}

			if ($iVendorsCount <= 6)
			{
?>
					  <td>

					    <table border="1" bordercolor="#ffffff" cellpadding="8" cellspacing="0" width="100%">
 						  <tr class="headerRow">
						    <td width="100%"><b>&nbsp;</b></td>
						  </tr>

						  <tr bgcolor="#f6f6f6">
						    <td>&nbsp;</td>
						  </tr>

						  <tr bgcolor="#e6e6e6">
						    <td>&nbsp;</td>
						  </tr>

						  <tr bgcolor="#f6f6f6">
						    <td>&nbsp;</td>
						  </tr>

						  <tr class="footerRow">
						    <td>&nbsp;</td>
						  </tr>
					    </table>

					  </td>
<?
			}

			if ($iVendorsCount > 6)
			{
?>
					  <td width="40">

					    <table border="1" bordercolor="#ffffff" cellpadding="8" cellspacing="0" width="100%">
 						  <tr class="headerRow">
						    <td width="100%" class="center"onclick="objVendorsGlider.next( ); return false;" style="cursor:pointer;"><a href="#" onclick="return false;" class="whiteLink">�</a></td>
						  </tr>

						  <tr bgcolor="#f6f6f6">
						    <td>&nbsp;</td>
						    <td></td>
						  </tr>

						  <tr bgcolor="#e6e6e6">
						    <td>&nbsp;</td>
						  </tr>

						  <tr bgcolor="#f6f6f6">
						    <td>&nbsp;</td>
						    <td></td>
						  </tr>

						  <tr class="footerRow">
						    <td class="center" onclick="objVendorsGlider.previous( ); return false;" style="cursor:pointer;"><a href="#" onclick="return false;" class="whiteLink">�</a></td>
						  </tr>
					    </table>

					  </td>
<?
			}
?>
					</tr>
				  </table>
<?
		}

		if ($Mode == "Brands" || $Mode == "VendorsBrands" || $Mode == "Departments")
		{
?>
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="150">

					    <table border="1" bordercolor="#ffffff" cellpadding="8" cellspacing="0" width="100%">
 						  <tr class="headerRow">
						    <td width="100%"><b>BRANDS</b></td>
						  </tr>

						  <tr bgcolor="#f6f6f6">
						    <td><b>PLACEMENTS</b></td>
						  </tr>

						  <tr bgcolor="#e6e6e6">
						    <td><b>FORECAST</b></td>
						  </tr>

						  <tr bgcolor="#f6f6f6">
						    <td><b>REVISED FORECAST</b></td>
						  </tr>

						  <tr class="footerRow">
						    <td><b>BALANCE</b></td>
						  </tr>
					    </table>

					  </td>

<?
			$iBrandsCount  = @count($Brands);

			if ($iBrandsCount > 0)
			{
?>
					  <td <?= (($iBrandsCount < 6) ? ('width="'.($iBrandsCount * 123).'"') : '') ?>>
						<div id="BrandsGlider">
						  <div class="scroller">
							<div class="content">
<?
				$iStats = array( );

				$sSQL = "SELECT brand_id, COALESCE(SUM(quantity), 0)
				         FROM tbl_forecasts
				         WHERE brand_id IN ($sBrands) AND (vendor_id='0' OR vendor_id IN ($sVendors)) AND year='$iYear'
				               AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$sBrands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

				if ($Region > 0)
					$sSQL .= " AND country_id='$Region' ";

				$sSQL .= " GROUP BY brand_id";

				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					$iBrandId  = $objDb->getField($i, 0);
					$iQuantity = $objDb->getField($i, 1);

					$iStats[$iBrandId]['Forecast'] = $iQuantity;
				}


				$sSQL = "SELECT brand_id, COALESCE(SUM(quantity), 0)
				         FROM tbl_revised_forecasts
				         WHERE brand_id IN ($sBrands) AND (vendor_id='0' OR vendor_id IN ($sVendors)) AND year='$iYear'
				               AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$sBrands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

				if ($Region > 0)
					$sSQL .= " AND country_id='$Region' ";

				$sSQL .= " GROUP BY brand_id";

				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					$iBrandId  = $objDb->getField($i, 0);
					$iQuantity = $objDb->getField($i, 1);

					$iStats[$iBrandId]['Revised'] = $iQuantity;
				}


				$sSQL = "SELECT po.brand_id, COALESCE(SUM(pc.order_qty), 0)
						 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
						 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
						 AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
						 AND po.vendor_id IN ($sVendors)
						 AND po.brand_id IN ($sBrands)
						 AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')";

				if ($PoType != "")
					$sSQL .= " AND po.order_type='$PoType' ";

				if ($Region > 0)
					$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

				$sSQL .= " GROUP BY po.brand_id";

				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					$iBrandId  = $objDb->getField($i, 0);
					$iQuantity = $objDb->getField($i, 1);

					$iStats[$iBrandId]['Placements'] = $iQuantity;
				}


				for ($i = 0; $i < $iBrandsCount; $i ++)
				{
					$iBrandId         = $Brands[$i];
					$iPlacements      = $iStats[$iBrandId]['Placements'];
					$iForecast        = $iStats[$iBrandId]['Forecast'];
					$iRevisedForecast = $iStats[$iBrandId]['Revised'];
?>

							  <div class="section" id="section<?= $i ?>">
							    <table border="1" bordercolor="#ffffff" cellpadding="8" cellspacing="0" width="100%">
								  <tr class="headerRow">
								    <td width="100%"><b title="<?= $sBrandsList[$iBrandId] ?>"><?= substr($sBrandsList[$iBrandId], 0, 12) ?></b></td>
								  </tr>

								  <tr bgcolor="#f6f6f6">
								    <td><?= formatNumber($iPlacements, false) ?></td>
								  </tr>

								  <tr bgcolor="#e6e6e6">
								    <td><?= formatNumber($iForecast, false) ?></td>
								  </tr>

								  <tr bgcolor="#f6f6f6">
								    <td><?= formatNumber($iRevisedForecast, false) ?></td>
								  </tr>

								  <tr class="footerRow">
								    <td><b><?= formatNumber(($iForecast - $iPlacements), false) ?></b></td>
								  </tr>
							    </table>
							  </div>
<?
				}
?>
							</div>
						  </div>
						</div>

						<script type="text/javascript">
						<!--
							var objBrandsGlider = new Glider('BrandsGlider', { duration:1.0, maxDisplay:6 });
						-->
						</script>
					  </td>
<?
			}

			if ($iBrandsCount <= 6)
			{
?>
					  <td>

					    <table border="1" bordercolor="#ffffff" cellpadding="8" cellspacing="0" width="100%">
 						  <tr class="headerRow">
						    <td width="100%"><b>&nbsp;</b></td>
						  </tr>

						  <tr bgcolor="#f6f6f6">
						    <td>&nbsp;</td>
						  </tr>

						  <tr bgcolor="#e6e6e6">
						    <td>&nbsp;</td>
						  </tr>

						  <tr bgcolor="#f6f6f6">
						    <td>&nbsp;</td>
						  </tr>

						  <tr class="footerRow">
						    <td>&nbsp;</td>
						  </tr>
					    </table>

					  </td>
<?
			}

			if ($iBrandsCount > 6)
			{
?>
					  <td width="40">

					    <table border="1" bordercolor="#ffffff" cellpadding="8" cellspacing="0" width="100%">
 						  <tr class="headerRow">
						    <td width="100%" class="center"onclick="objBrandsGlider.next( ); return false;" style="cursor:pointer;"><a href="#" onclick="return false;" class="whiteLink">�</a></td>
						  </tr>

						  <tr bgcolor="#f6f6f6">
						    <td>&nbsp;</td>
						    <td></td>
						  </tr>

						  <tr bgcolor="#e6e6e6">
						    <td>&nbsp;</td>
						  </tr>

						  <tr bgcolor="#f6f6f6">
						    <td>&nbsp;</td>
						    <td></td>
						  </tr>

						  <tr class="footerRow">
						    <td class="center" onclick="objBrandsGlider.previous( ); return false;" style="cursor:pointer;"><a href="#" onclick="return false;" class="whiteLink">�</a></td>
						  </tr>
					    </table>

					  </td>
<?
			}
?>
					</tr>
				  </table>
<?
		}
?>
			    </div>

			    <br style="line-height:4px;" />

			    <div class="tblSheet">
		          <h1 class="darkGray small" style="margin:0px 1px 1px 0px;"><span>( Year : <?= $iYear ?> )</span><img src="images/h1/vsn/month-wise-placements.jpg" width="249" height="15" vspace="8" alt="" title="" /></h1>

			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr valign="top">
			          <td>

			            <table border="1" bordercolor="#ffffff" cellpadding="8" cellspacing="0" width="100%">
			              <tr class="headerRow">
			                <td width="28%"><b>MONTH</b></td>
			                <td width="28%"><b>PLACEMENTS</b></td>
			                <td width="44%"><b>DETAIL</b></td>
			              </tr>
			            </table>

			            <table border="1" bordercolor="#ffffff" cellpadding="8" cellspacing="0" width="100%">
<?
		$sMonths = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		$sClass  = array("evenRow", "oddRow");

		$iBrands       = @explode(",", $sAllBrands);
		$iVendors      = @explode(",", $sAllVendors);
		$iBrandsCount  = @count($iBrands);
		$iVendorsCount = @count($iVendors);

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


		$sSQL = "SELECT DATE_FORMAT(pc.etd_required, '%m'), po.brand_id, po.vendor_id, COALESCE(SUM(pc.order_qty), 0)
				 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
				 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
				 AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (pc.etd_required BETWEEN '$iYear-01-01' AND '$iYear-12-31')";

		if ($PoType != "")
			$sSQL .= " AND po.order_type='$PoType' ";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

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



		if ($Mode == "Vendors")
			$sSQL = "SELECT month, COALESCE(SUM(quantity), 0)
			         FROM tbl_forecasts
			         WHERE (brand_id='0' OR brand_id IN ($sBrands)) AND vendor_id IN ($sVendors) AND year='$iYear'
			               AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$sBrands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

		else if ($Mode == "Brands" || $Mode == "Departments")
			$sSQL = "SELECT month, COALESCE(SUM(quantity), 0)
			         FROM tbl_forecasts
			         WHERE brand_id IN ($sBrands) AND (vendor_id='0' OR vendor_id IN ($sVendors)) AND year='$iYear'
			         AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$sBrands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

		else if ($Mode == "VendorsBrands")
			$sSQL = "SELECT month, COALESCE(SUM(quantity), 0)
			         FROM tbl_forecasts
			         WHERE brand_id IN ($sBrands) AND vendor_id IN ($sVendors) AND year='$iYear'
			         AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$sBrands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

		if ($Region > 0)
			$sSQL .= " AND country_id='$Region' ";

		$sSQL .= " GROUP BY month";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMonth    = $objDb->getField($i, 0);
			$iQuantity = $objDb->getField($i, 1);

			$iOriginalForecast[$iMonth] = $iQuantity;
		}



		if ($Mode == "Vendors")
			$sSQL = "SELECT month, COALESCE(SUM(quantity), 0)
			         FROM tbl_revised_forecasts
			         WHERE (brand_id='0' OR brand_id IN ($sBrands)) AND vendor_id IN ($sVendors) AND year='$iYear'
			         AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$sBrands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

		else if ($Mode == "Brands" || $Mode == "Departments")
			$sSQL = "SELECT month, COALESCE(SUM(quantity), 0)
			         FROM tbl_revised_forecasts
			         WHERE brand_id IN ($sBrands) AND (vendor_id='0' OR vendor_id IN ($sVendors)) AND year='$iYear'
			         AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$sBrands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

		else if ($Mode == "VendorsBrands")
			$sSQL = "SELECT month, COALESCE(SUM(quantity), 0)
			         FROM tbl_revised_forecasts
			         WHERE brand_id IN ($sBrands) AND vendor_id IN ($sVendors) AND year='$iYear'
			         AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$sBrands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

		if ($Region > 0)
			$sSQL .= " AND country_id='$Region' ";

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
				 FROM tbl_po po, tbl_po_colors pc, tbl_styles s, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B'
				 AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (psd.handover_to_forwarder BETWEEN '$iYear-01-01' AND '$iYear-12-31')";

		if ($PoType != "")
			$sSQL .= " AND po.order_type='$PoType' ";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

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
				 FROM tbl_po po, tbl_po_colors pc, tbl_styles s, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B'
				 AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (pc.etd_required BETWEEN '$iYear-01-01' AND '$iYear-12-31')";

		if ($PoType != "")
			$sSQL .= " AND po.order_type='$PoType' ";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

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



		$sSQL = "SELECT DATE_FORMAT(psd.handover_to_forwarder, '%m'), COALESCE(SUM(psq.quantity), 0)
		         FROM tbl_po po, tbl_po_colors pc, tbl_styles, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B'
				 AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (psd.handover_to_forwarder BETWEEN '".($iYear - 1)."-12-01' AND '$iYear-12-31')
				 AND DATE_FORMAT(psd.handover_to_forwarder, '%Y-%m') < DATE_FORMAT(pc.etd_required, '%Y-%m')";

		if ($PoType != "")
			$sSQL .= " AND po.order_type='$PoType' ";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

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
		         FROM tbl_po po, tbl_po_colors pc, tbl_styles s, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B'
				 AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (psd.handover_to_forwarder BETWEEN '$iYear-01-01' AND '$iYear-12-31')
				 AND DATE_FORMAT(psd.handover_to_forwarder, '%Y-%m') > DATE_FORMAT(pc.etd_required, '%Y-%m')";

		if ($PoType != "")
			$sSQL .= " AND po.order_type='$PoType' ";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

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
				 WHERE po.id=qa.po_id AND po.order_nature='B'
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (qa.audit_date BETWEEN '$iYear-01-01' AND '$iYear-12-31')
				 AND qa.audit_stage!=''
				 AND report_id!='6'";

		if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE)
			$sSQL .= " AND qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$sBrands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}'))	";

		else
			$sSQL .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$sBrands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))	";

		if ($PoType != "")
			$sSQL .= " AND po.order_type='$PoType' ";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

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
				 WHERE po.id=qa.po_id AND po.order_nature='B'
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (qa.audit_date BETWEEN '$iYear-01-01' AND '$iYear-12-31')
				 AND qa.audit_stage!=''
				 AND report_id='6'";

		if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE)
			$sSQL .= " AND qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$sBrands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}'))	";

		else
			$sSQL .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$sBrands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))	";

		if ($PoType != "")
			$sSQL .= " AND po.order_type='$PoType' ";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

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
			                  <span id="Vendor<?= $i ?>"><u>Vendors</u></span>
			                  -
			                  <span id="Brand<?= $i ?>"><u>Brands</u></span>
			                  -
			                  <span id="Report<?= $i ?>"><u>Reports</u></span>


							  <script type="text/javascript">
							  <!--
								  new Tip('Vendor<?= $i ?>',
								          "<?= $sVendorsTip ?>",
								          { title:'Vendors wise Placements', stem:'leftMiddle', hook:{ tip:'leftMiddle', mouse:true }, offset:{ x:1, y:1 } });

								  new Tip('Brand<?= $i ?>',
								          "<?= $sBrandsTip ?>",
								          { title:'Brands wise Placements', stem:'leftMiddle', hook:{ tip:'leftMiddle', mouse:true }, offset:{ x:1, y:1 } });

								  new Tip('Report<?= $i ?>',
								          "<?= $sReportsTip ?>",
								          { title:'Reports', stem:'leftMiddle', hook:{ tip:'leftMiddle', mouse:true }, offset:{ x:1, y:1 }, width:280 });
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
				 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
				 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
				 AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
				 AND po.vendor_id IN ($sVendors)
				 AND po.brand_id IN ($sBrands)
				 AND (pc.etd_required BETWEEN '$iLastYear-01-01' AND '$iLastYear-12-31')";

		if ($PoType != "")
			$sSQL .= " AND po.order_type='$PoType' ";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

		$objDb->query($sSQL);

		$iPlacements = $objDb->getField(0, 0);
?>
			              <tr class="footerRow">
			                <td width="30%"><b>Total (<?= $iLastYear ?>)</b></td>
			                <td width="30%"><b><?= formatNumber($iPlacements, false) ?></b></td>
			                <td width="40%"></td>
			              </tr>

			              <tr class="footerRow">
			                <td width="100%" colspan="3" height="120"></td>
			              </tr>
			            </table>

			          </td>

			          <td width="502">

			            <table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
			              <tr>
			                <td width="100%"><h1 class="green small"><img src="images/h1/vsn/quick-view.jpg" width="104" height="18" alt="" title="" style="margin-top:6px;" /></h1></td>
			              </tr>

			              <tr>
			                <td>
			                  <div id="MonthlyStats">
			                    <div id="MonthStatsChart">loading...</div>

			                    <script type="text/javascript">
			                    <!--
								    var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", "MonthStats", "500", "250", "0", "1");

                                    objChart.setXMLData("<chart caption='Month wise Statistics' formatNumberScale='0' showValues='0' showLabels='0' chartBottomMargin='5' legendPosition='BOTTOM'>" +
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
                                                        "<set value='<?= $iOriginalForecast[$i] ?>' link='javaScript:showYearlyStats(\"<?= $Mode ?>\", \"<?= $sBrands ?>\", \"<?= $sVendors ?>\", \"<?= $FromDate ?>\", \"<?= $ToDate ?>\", \"<?= $Region ?>\", \"<?= $PoType ?>\");' />" +
<?
  		}
?>
                                                        "</dataset>" +

                                                        "<dataset seriesName='Revised' renderAs='Line'>" +
<?
		for ($i = 1; $i <= 12; $i ++)
		{
?>
                                                        "<set value='<?= (($iYear == date("Y") && $i <= (date("n") + 1)) ? '' : $iRevisedForecast[$i]) ?>' />" +
<?
		}
?>
                                                        "</dataset>" +

                                                        "<dataset seriesName='Placements' renderAs='Line' color='#0000ff'>" +
<?
		for ($i = 1; $i <= 12; $i ++)
		{
?>
                                                        "<set value='<?= $iMonthPlacements[$i] ?>' />" +
<?
		}
?>
                                                        "</dataset>" +

                                                        "<dataset seriesName='Shipments' renderAs='Line' color='#b6e500'>" +
<?
		for ($i = 1; $i <= 12; $i ++)
		{
?>
                                                        "<set value='<?= $iOgacShipments[$i] ?>' />" +
<?
		}
?>
                                                        "</dataset>" +
                                                        "</chart>");


								    objChart.render("MonthStatsChart");
    						    -->
    						    </script>

							    <table border="0" cellpadding="0" cellspacing="0" width="100%">
								  <tr>
								    <td width="75"></td>
								    <td width="32" align="center"><span id="Month1_Statistics"><u>Jan</u></span></td>
								    <td width="32" align="center"><span id="Month2_Statistics"><u>Feb</u></span></td>
								    <td width="32" align="center"><span id="Month3_Statistics"><u>Mar</u></span></td>
								    <td width="32" align="center"><span id="Month4_Statistics"><u>Apr</u></span></td>
								    <td width="32" align="center"><span id="Month5_Statistics"><u>May</u></span></td>
								    <td width="32" align="center"><span id="Month6_Statistics"><u>Jun</u></span></td>
								    <td width="32" align="center"><span id="Month7_Statistics"><u>Jul</u></span></td>
								    <td width="32" align="center"><span id="Month8_Statistics"><u>Aug</u></span></td>
								    <td width="32" align="center"><span id="Month9_Statistics"><u>Sep</u></span></td>
								    <td width="32" align="center"><span id="Month10_Statistics"><u>Oct</u></span></td>
								    <td width="32" align="center"><span id="Month11_Statistics"><u>Nov</u></span></td>
								    <td width="32" align="center"><span id="Month12_Statistics"><u>Dec</u></span></td>
								    <td></td>
								  </tr>
							    </table>

							    <script type="text/javascript">
							    <!--
<?
		for ($i = 1; $i <= 12; $i ++)
		{
			$iMonth = str_pad($i, 2, '0', STR_PAD_LEFT);
			$iDays  = @cal_days_in_month(CAL_GREGORIAN, $iMonth, $iYear);
?>
					  				new Tip('Month<?= $i ?>_Statistics', { ajax: { url:'ajax/vsn/get-month-statistics.php', options: { method:'post', parameters:'Month=<?= $i ?>&Mode=<?= $Mode ?>&Region=<?= $Region ?>&FromDate=<?= $FromDate ?>&ToDate=<?= $ToDate ?>&Vendors=<?= $sVendors ?>&Brands=<?= $sBrands ?>&PoType=<?= $PoType ?>', onCreate: function( ) { showProcessing( ); }, onComplete: function( ) { hideProcessing( ); } } }, title:'<?= $sMonths[($i - 1)] ?> <?= substr($iYear, 2) ?> Statistics', stem:'topLeft', offset:{ x:1, y:1 }, width:720 });
<?
		}
?>
							    -->
							    </script>
							  </div>


							  <div id="YearlyStats" style="display:none;">
							    <div id="YearlyStatsChart">loading...</div>

							    <div style="height:13px; padding-left:8px;"><a href="#" onclick="showMainGraph( ); return false;"><b>� Back</b></a></div>
							  </div>


							  <div id="IndividualStats" style="display:none;">
							    <div id="IndividualStatsChart">loading...</div>

							    <div style="height:13px; padding-left:8px;"><a href="#" onclick="showYearlyGraph( ); return false;"><b>� Back</b></a></div>
							  </div>
			                </td>
			              </tr>


			              <tr>
			                <td>
<?
		$fOtp = array(0,0,0,0,0,0,0,0,0,0,0,0);

		$sSQL = "SELECT DATE_FORMAT(pc.etd_required, '%m'), COALESCE(SUM(pc.order_qty), 0), COALESCE(SUM(pc.ontime_qty), 0)
				 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
				 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
					   AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
					   AND po.vendor_id IN ($sVendors)
					   AND po.brand_id IN ($sBrands)
					   AND (pc.etd_required BETWEEN '$iYear-01-01' AND '$iYear-12-31') AND pc.etd_required <= CURDATE( )
					   AND po.status='C'";

		if ($PoType != "")
			$sSQL .= " AND po.order_type='$PoType' ";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

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
			                  <div id="MonthlyOtp">
			                    <div id="MonthOtpChart">loading...</div>

			                    <script type="text/javascript">
			                    <!--
								    var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "MonthOtp", "500", "250", "0", "1");

                                    objChart.setXMLData("<chart caption='Month wise OTP' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='0' decimals='1' numberSuffix='%' chartBottomMargin='5'>" +
<?
		for ($i = 0; $i < 12; $i ++)
		{
?>
                                                        "<set label='<?= $sLabels[$i] ?>' value='<?= $fOtp[$i] ?>' color='3e9393' link='javaScript:showLatePos(\"<?= $Mode ?>\", \"<?= $sBrands ?>\", \"<?= $sVendors ?>\", \"<?= $iYear ?>\", \"<?= ($i + 1) ?>\", \"<?= $Region ?>\", \"<?= $PoType ?>\");' />" +
<?
		}
?>
                                                        "</chart>");


								    objChart.render("MonthOtpChart");
    						    -->
    						    </script>

							    <table border="0" cellpadding="0" cellspacing="0" width="100%">
								  <tr>
								    <td></td>
								    <td width="35" align="center"><span id="Month1_OTP"><u>Jan</u></span></td>
								    <td width="35" align="center"><span id="Month2_OTP"><u>Feb</u></span></td>
								    <td width="35" align="center"><span id="Month3_OTP"><u>Mar</u></span></td>
								    <td width="35" align="center"><span id="Month4_OTP"><u>Apr</u></span></td>
								    <td width="35" align="center"><span id="Month5_OTP"><u>May</u></span></td>
								    <td width="35" align="center"><span id="Month6_OTP"><u>Jun</u></span></td>
								    <td width="35" align="center"><span id="Month7_OTP"><u>Jul</u></span></td>
								    <td width="35" align="center"><span id="Month8_OTP"><u>Aug</u></span></td>
								    <td width="35" align="center"><span id="Month9_OTP"><u>Sep</u></span></td>
								    <td width="35" align="center"><span id="Month10_OTP"><u>Oct</u></span></td>
								    <td width="35" align="center"><span id="Month11_OTP"><u>Nov</u></span></td>
								    <td width="35" align="center"><span id="Month12_OTP"><u>Dec</u></span></td>
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
					  				new Tip('Month<?= $i ?>_OTP', { ajax: { url:'ajax/vsn/get-month-otp.php', options: { method:'post', parameters:'Month=<?= $i ?>&Mode=<?= $Mode ?>&Region=<?= $Region ?>&FromDate=<?= $FromDate ?>&ToDate=<?= $ToDate ?>&Vendors=<?= $sVendors ?>&Brands=<?= $sBrands ?>&PoType=<?= $PoType ?>', onCreate: function( ) { showProcessing( ); }, onComplete: function( ) { hideProcessing( ); } } }, title:'<?= $sMonths[($i - 1)] ?> <?= substr($iYear, 2) ?> OTP', stem:'topLeft', offset:{ x:1, y:1 }, width:400 });
<?
		}
?>
							    -->
							    </script>
							  </div>


							  <div id="LatePos" style="display:none; padding-top:15px;">
							    <div id="Pos" style="width:500px; height:235px; overflow:auto;">loading...</div>

							    <div style="height:13px; padding-left:8px;"><a href="#" onclick="showOtpGraph( ); return false;"><b>� Back</b></a></div>
							  </div>
			                </td>
			              </tr>
			            </table>

			          </td>
			        </tr>
			      </table>
			    </div>
<?
		if (checkUserRights("reports-comparison.php", "VSN", "view"))
		{
?>

				<div class="buttonsBar" style="margin-top:4px;">
				  <input type="button" value="" class="btnCompare" title="Compare" onclick="document.location='<?= SITE_URL ?>vsn/reports-comparison.php?Region1=<?= $Region ?>&Year1=<?= $iYear ?>&Vendors1[]=<?= str_replace(',', '&Vendors1[]=', $sVendors) ?>&Brands1[]=<?= str_replace(',', '&Brands1[]=', $sBrands) ?>';" />
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