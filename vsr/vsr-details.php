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

	$SearchId = IO::strValue("SearchId");

	if ($SearchId != "")
	{
		$sSQL = "SELECT params FROM tbl_user_searches WHERE user_id='{$_SESSION['UserId']}' AND id='$SearchId'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$_POST = @unserialize($objDb->getField(0, 0));

			$_REQUEST = $_POST;
		}
	}

	$Mode     = IO::strValue("Mode");
	$Region   = IO::intValue("Region");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$Year     = IO::intValue("Year");
	$Brand    = IO::intValue("Brand");
	$Vendor   = IO::intValue("Vendor");
	$Category = IO::intValue("Category");
	$Mode     = (($Mode == "") ? "Vendors" : $Mode);


	$sCategoriesList = getList("tbl_categories", "id", "category");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/vsr/vsr-details.js"></script>
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
			    <h1>VSR Details</h1>

			    <form name="frmSearch" id="frmSearch" method="post" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="doSearch( );">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="40">Mode</td>

			          <td width="90">
			            <select id="Mode" name="Mode" onchange="refineSearch(this.value);">
			              <option value="Vendors"<?= (($Mode == "Vendors") ? " selected" : "") ?>>Vendors</option>
			              <option value="Brands"<?= (($Mode == "Brands") ? " selected" : "") ?>>Brands</option>
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

					  <td width="40">Year</td>

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
					  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center">To</td>
					  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="70" align="center">[ <a href="./" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;" style="color:#eeeeee;">Clear</a> ]</td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
					  <td width="65">Category</td>

			          <td>
					    <select name="Category">
						  <option value="">All Categories</option>
<?
	foreach ($sCategoriesList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Category) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>
				    </tr>
				  </table>
			    </div>


			    <div class="tblSheet">
			      <div style="margin:0px 1px 1px 0px;">
			        <table border="0" cellpadding="0" cellspacing="0" width="100%">
			          <tr>
			            <td width="255"><h1 class="darkGray small" style="margin:0px;"><img src="images/h1/vsr/core-statistics.jpg" width="161" height="15" vspace="8" alt="" title="" /></h1></td>
			            <td bgcolor="#888888"><b style="color:#ffffff; padding-left:10px;">REFINE YOUR SEARCH</b> &nbsp; <b>( <a href="./" onclick="checkAll( ); return false;" class="sheetLink">Check ALL</a> | <a href="./" onclick="clearAll( ); return false;" class="sheetLink">Clear ALL</a> )</b></td>
			          </tr>

			          <tr valign="top">
			            <td>

			              <div style="padding:5px;">
			                <table border="0" cellpadding="5" cellspacing="0" width="100%">
<?
	$sVendorsSql = "";
	$sAllPos     = "0";

	if (($Category > 0 || $Region > 0) && $Vendor == 0)
	{
		$sSQL = "SELECT id FROM tbl_vendors WHERE parent_id='0' AND sourcing='Y'";

		if ($Region > 0)
			$sSQL .= " AND country_id='$Region'";

		if ($Category > 0)
			$sSQL .= " AND category_id='$Category'";

		$objDb->query($sSQL);

		$iCount   = $objDb->getCount( );
		$sVendors = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sVendors .= (",".$objDb->getField($i, 0));

		if ($sVendors != "")
			$sVendors = substr($sVendors, 1);

		$sVendorsSql = " AND vendor_id IN ($sVendors) ";
	}


	$sVendors = @implode(",", IO::getArray('Vendors'));
	$sBrands  = @implode(",", IO::getArray('Brands'));

	if ($sBrands == "")
		$sBrands = $_SESSION['Brands'];

	if ($sVendors == "")
		$sVendors = $_SESSION['Vendors'];

	if ($Brand > 0)
		$sBrands = $Brand;

	if ($Vendor > 0)
		$sVendors = $Vendor;


	if ($FromDate != "" && $ToDate != "")
	{
		$sSQL = "SELECT DISTINCT(po.id)
		         FROM tbl_po po, tbl_po_colors pc
		         WHERE po.id=pc.po_id AND FIND_IN_SET(po.vendor_id, '$sVendors') AND FIND_IN_SET(po.brand_id, '$sBrands')
		               AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate')";

		$sSQL .= str_replace("vendor_id", "po.vendor_id", $sVendorsSql);
	}

	else
		$sSQL = "SELECT id FROM tbl_po WHERE FIND_IN_SET(vendor_id, '$sVendors') AND FIND_IN_SET(brand_id, '$sBrands') $sVendorsSql";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sAllPos .= (",".$objDb->getField($i, 0));



	$sSQL = "SELECT COUNT(*) FROM tbl_po WHERE id IN ($sAllPos) AND status='C'";
	$objDb->query($sSQL);

	$iCompletedOrders = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_po WHERE id IN ($sAllPos)";
	$objDb->query($sSQL);

	$iTotalOrders = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*)
	         FROM tbl_vsr vsr, tbl_po po
	         WHERE vsr.po_id=po.id AND po.status!='C' AND vsr.po_id IN ($sAllPos)
	               AND ((vsr.final_audit_date != '0000-00-00' AND vsr.final_audit_date != '') OR vsr.production_status != '')";
	$objDb->query($sSQL);

	$iWorkingOrders = $objDb->getField(0, 0);
?>
			                  <tr>
			                    <td width="65%"><b>Orders in Hand</b></td>
			                    <td width="35%"><b style="color:#ff0000;"><?= formatNumber($iTotalOrders, false) ?></b></td>
			                  </tr>

			                  <tr>
			                    <td><b>Completed Orders</b></td>
			                    <td><b style="color:#ff0000;"><?= formatNumber($iCompletedOrders, false) ?></b></td>
			                  </tr>

			                  <tr>
			                    <td><b>Running Orders</b></td>
			                    <td><b style="color:#ff0000;"><?= formatNumber($iWorkingOrders, false) ?></b></td>
			                  </tr>

			                  <tr>
			                    <td><b>Waiting for Induction</b></td>
			                    <td><b style="color:#ff0000;"><?= formatNumber(($iTotalOrders - $iCompletedOrders - $iWorkingOrders), false) ?></b></td>
			                  </tr>
			                </table>
			              </div>

			            </td>

			            <td bgcolor="#f6f6f6">
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

			                      <div id="VendorsBlock" style="display:<?= (($Mode == "Vendors") ? "block" : "none") ?>;">
			                        <div>
			                          <table border="0" cellpadding="1" cellspacing="0" width="100%">
<?
	$sVendorsList = array( );
	$sBrandsList  = array( );

	$sSQL = "SELECT id, vendor FROM tbl_vendors WHERE id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y' ORDER BY vendor";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount;)
	{
?>
			                            <tr>
<?
		for ($j = 0; $j < 4; $j ++)
		{
			if ($i < $iCount)
			{
				$sKey   = $objDb->getField($i, "id");
				$sValue = $objDb->getField($i, "vendor");

				$sVendorsList[$sKey] = $sValue;
?>
			                              <td width="22"><input type="checkbox" class="vendors" name="Vendors[]" value="<?= $sKey ?>" <?= ((@in_array($sKey, IO::getArray('Vendors'))) ? "checked" : "") ?> /></td>
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


	$sSQL = "SELECT id, brand FROM tbl_brands WHERE parent_id!=0 AND id IN ({$_SESSION['Brands']}) ORDER BY brand";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>
			                          </table>

			                          <br />
			                          <b>Brand: </b>
			                          <select name="Brand" id="Brand">
			                            <option value="">All Brands</option>
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, "id");
		$sValue = $objDb->getField($i, "brand");
?>
			                            <option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			                          </select>
			                        </div>
			                      </div>

			                      <div id="BrandsBlock" style="display:<?= (($Mode == "Brands") ? "block" : "none") ?>;">
			                        <div>
			                          <table border="0" cellpadding="1" cellspacing="0" width="100%">
<?
	for ($i = 0; $i < $iCount;)
	{
?>
			                            <tr>
<?
		for ($j = 0; $j < 4; $j ++)
		{
			if ($i < $iCount)
			{
				$sKey   = $objDb->getField($i, "id");
				$sValue = $objDb->getField($i, "brand");

				$sBrandsList[$sKey] = $sValue;
?>
			                              <td width="22"><input type="checkbox" class="brands" name="Brands[]" value="<?= $sKey ?>" <?= ((@in_array($sKey, IO::getArray('Brands'))) ? "checked" : "") ?> /></td>
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

			                          <br />
			                          <b>Vendor: </b>
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
		$sSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0'");
		$SubSearch    = IO::strValue('SubSearch');

		if ($Mode == "Vendors")
		{
			$Vendors = IO::getArray('Vendors');

			if (count($Vendors) == 0)
				$Vendors = @explode(",", $_SESSION['Vendors']);

			$iVendorBrandCount = count($Vendors);
		}

		else if ($Mode == "Brands")
		{
			$Brands = IO::getArray('Brands');

			if (count($Brands) == 0)
				$Brands = @explode(",", $_SESSION['Brands']);

			$iVendorBrandCount = count($Brands);
		}
?>
                <div class="tblSheet" style="margin:4px 0px 1px 0px; padding:1px;">
			      <div class="buttonsBar">
					<form name="frmSave" id="frmSave" onsubmit="return false;">
					  <input type="hidden" name="SubSearch" value="Y" />
					  <input type="hidden" name="Mode" value="<?= $Mode ?>" />
					  <input type="hidden" name="Region" value="<?= $Region ?>" />
					  <input type="hidden" name="FromDate" value="<?= $FromDate ?>" />
					  <input type="hidden" name="ToDate" value="<?= $ToDate ?>" />
					  <input type="hidden" name="Brand" value="<?= $Brand ?>" />
					  <input type="hidden" name="Vendor" value="<?= $Vendor ?>" />
<?
		for ($i = 0; $i < $iVendorBrandCount; $i ++)
		{
			if ($Mode == "Vendors")
				$iVbIndex = $Vendors[$i];

			else if ($Mode == "Brands")
				$iVbIndex = $Brands[$i];
?>
					<input type="hidden" name="OrderNo<?= $iVbIndex ?>" value="<?= (($SubSearch == '') ? '' : IO::strValue("OrderNo".$iVbIndex)) ?>" />
					<input type="hidden" name="FromDate<?= $iVbIndex ?>" value="<?= (($SubSearch == '') ? IO::strValue("FromDate") : IO::strValue("FromDate".$iVbIndex)) ?>" />
					<input type="hidden" name="ToDate<?= $iVbIndex ?>" value="<?= (($SubSearch == '') ? IO::strValue("ToDate") : IO::strValue("ToDate".$iVbIndex)) ?>" />
					<input type="hidden" name="Status<?= $iVbIndex ?>" value="<?= (($SubSearch == '') ? "All POs" : IO::strValue("Status".$iVbIndex)) ?>" />
<?
		}


		$Vendors = IO::getArray('Vendors');

		for ($i = 0; $i < count($Vendors); $i ++)
		{
?>
					<input type="hidden" name="Vendors[]" value="<?= $Vendors[$i] ?>" />
<?
		}


		$Brands = IO::getArray('Brands');

		for ($i = 0; $i < count($Brands); $i ++)
		{
?>
				    <input type="hidden" name="Brands[]" value="<?= $Brands[$i] ?>" />
<?
		}
?>
			        <table border="0" cellpadding="0" cellspacing="0" width="100%">
			          <tr>
			            <td align="right" style="color:#ffffff;"><b>SEARCH TITLE : &nbsp;</b></td>
					    <td width="210"><input type="text" name="Title" id="Title" value="" size="30" maxlength="100" class="textbox" /></td>
			            <td width="63"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save Search" onclick="saveSearch( );" /></td>
			          </tr>
			        </table>
			        </form>
			      </div>
			    </div>
<?
		for ($iIndex = 0; $iIndex < $iVendorBrandCount; $iIndex ++)
		{
			if ($Mode == "Vendors")
				$iVendorBrandIndex = $Vendors[$iIndex];

			else if ($Mode == "Brands")
				$iVendorBrandIndex = $Brands[$iIndex];

			if ($SubSearch == "Y")
			{
				$OrderNo  = IO::strValue("OrderNo".$iVendorBrandIndex);
				$FromDate = IO::strValue("FromDate".$iVendorBrandIndex);
				$ToDate   = IO::strValue("ToDate".$iVendorBrandIndex);
				$Status   = IO::strValue("Status".$iVendorBrandIndex);
			}

			else
			{
				$OrderNo  = "";
				$Status   = "All POs";
				$FromDate = IO::strValue("FromDate");
				$ToDate   = IO::strValue("ToDate");
			}
?>
			    <div class="tblSheet" style="margin:4px 0px 1px 0px; padding:1px;">
			    <form name="frmSearch<?= $iVendorBrandIndex ?>" id="frmSearch<?= $iVendorBrandIndex ?>" method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
			    <input type="hidden" name="SubSearch" value="Y" />
			    <input type="hidden" name="Mode" value="<?= $Mode ?>" />
			    <input type="hidden" name="Region" value="<?= $Region ?>" />
			    <input type="hidden" name="FromDate" value="<?= $FromDate ?>" />
			    <input type="hidden" name="ToDate" value="<?= $ToDate ?>" />
			    <input type="hidden" name="Vendor" value="<?= $Vendor ?>" />
			    <input type="hidden" name="Brand" value="<?= $Brand ?>" />
<?
			for ($i = 0; $i < $iVendorBrandCount; $i ++)
			{
				if ($Mode == "Vendors")
					$iVbIndex = $Vendors[$i];

				else if ($Mode == "Brands")
					$iVbIndex = $Brands[$i];

				if ($iVbIndex == $iVendorBrandIndex)
					continue;
?>
			    <input type="hidden" name="OrderNo<?= $iVbIndex ?>" value="<?= (($SubSearch == '') ? '' : IO::strValue("OrderNo".$iVbIndex)) ?>" />
			    <input type="hidden" name="FromDate<?= $iVbIndex ?>" value="<?= (($SubSearch == '') ? IO::strValue("FromDate") : IO::strValue("FromDate".$iVbIndex)) ?>" />
			    <input type="hidden" name="ToDate<?= $iVbIndex ?>" value="<?= (($SubSearch == '') ? IO::strValue("ToDate") : IO::strValue("ToDate".$iVbIndex)) ?>" />
			    <input type="hidden" name="Status<?= $iVbIndex ?>" value="<?= (($SubSearch == '') ? "All POs" : IO::strValue("Status".$iVbIndex)) ?>" />
<?
			}

			$Vendors = IO::getArray('Vendors');

			for ($i = 0; $i < count($Vendors); $i ++)
			{
?>
			    <input type="hidden" name="Vendors[]" value="<?= $Vendors[$i] ?>" />
<?
			}

			$Brands = IO::getArray('Brands');

			for ($i = 0; $i < count($Brands); $i ++)
			{
?>
			    <input type="hidden" name="Brands[]" value="<?= $Brands[$i] ?>" />
<?
			}
?>

			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr bgcolor="#aba7a6">
			          <td height="35"><h2 style="margin:1px; background:#aba7a6;"><?= (($Mode == "Vendors") ? $sVendorsList[$Vendors[$iIndex]] : $sBrandsList[$Brands[$iIndex]])?></h2></td>
					  <td width="30" style="color:#ffffff;">POs</td>
					  <td width="130"><input type="text" name="OrderNo<?= $iVendorBrandIndex ?>" value="<?= $OrderNo ?>" size="15" class="textbox" /></td>
					  <td width="40" style="color:#ffffff;">From</td>
					  <td width="78"><input type="text" name="FromDate<?= $iVendorBrandIndex ?>" value="<?= $FromDate ?>" id="FromDate<?= $iVendorBrandIndex ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate<?= $iVendorBrandIndex ?>'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate<?= $iVendorBrandIndex ?>'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center" style="color:#ffffff;">To</td>
					  <td width="78"><input type="text" name="ToDate<?= $iVendorBrandIndex ?>" value="<?= $ToDate ?>" id="ToDate<?= $iVendorBrandIndex ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate<?= $iVendorBrandIndex ?>'), 'yyyy-mm-dd', this);" /></td>
					  <td width="25"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate<?= $iVendorBrandIndex ?>'), 'yyyy-mm-dd', this);" /></td>
			          <td width="80">[ <a href="./" onclick="$('FromDate<?= $iVendorBrandIndex ?>').value=''; $('ToDate<?= $iVendorBrandIndex ?>').value=''; return false;" style="color:#eeeeee;">Clear</a> ]</td>
			          <td width="45" style="color:#ffffff;">Status</td>

			          <td width="130">
			            <select name="Status<?= $iVendorBrandIndex ?>">
			              <option value="Problematic POs"<?= (($Status == "Problematic POs") ? " selected" : "") ?>>Problematic POs</option>
			              <option value="All POs"<?= (($Status == "All POs") ? " selected" : "") ?>>All POs</option>
			            </select>
			          </td>

			          <td width="45"><input type="submit" id="BtnGo<?= $iVendorBrandIndex ?>" value="" class="btnGo" onclick="doSubSearch(<?= $iVendorBrandIndex ?>);" /></td>
			        </tr>
			      </table>
			    </form>
			    </div>

			    <div class="tblSheet">
			      <div style="border-right:solid 10px #666666;">
			        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				      <tr class="headerRow">
				        <td width="6%" class="center"><a href="#" onclick="checkAllPos(<?= $iVendorBrandIndex ?>); return false;">ALL</a></td>
				        <td width="6%">#</td>
				        <td width="16%">Order No</td>
				        <td width="16%">Vendor</td>
				        <td width="13%">Season</td>
				        <td width="14%">Style No</td>
				        <td width="12%">ETD Required</td>
				        <td width="8%">Quantity</td>
				        <td width="9%" class="center">Options</td>
				      </tr>
			        </table>
			      </div>

			      <div id="ScrollArea<?= $iVendorBrandIndex ?>" class="flexcroll vsr">
<!--
			        <form name="frmReport<?= $iVendorBrandIndex ?>" id="frmReport<?= $iVendorBrandIndex ?>" method="post" action="vsr/export-vsr.php">
-->
			        <form name="frmReport<?= $iVendorBrandIndex ?>" id="frmReport<?= $iVendorBrandIndex ?>" method="post" action="vsr/export-work-order-details.php">
			        <input type="hidden" name="Type" id="Type<?= $iVendorBrandIndex ?>" value="Actual" />
			        <input type="hidden" name="Mode" id="Mode<?= $iVendorBrandIndex ?>" value="<?= $Mode ?>" />
			        <input type="hidden" name="Format" id="Format<?= $iVendorBrandIndex ?>" value="xlsx" />
			        <input type="hidden" name="Vendor" value="<?= $Vendors[$iIndex] ?>" />
			        <input type="hidden" name="Brand" value="<?= $Brands[$iIndex] ?>" />
			        <input type="hidden" name="FromDate" value="<?= $FromDate ?>" />
			        <input type="hidden" name="ToDate" value="<?= $ToDate ?>" />

<?
			$sAllPos     = "0";
			$sOrderNoSql = "";


			if ($OrderNo != "")
			{
				$sPOs = @explode(",", $OrderNo);

				$sOrderNoSql = " AND (";

				for ($i = 0; $i < count($sPOs); $i ++)
				{
					if ($i > 0)
						$sOrderNoSql .= " OR ";

					$sOrderNoSql .= " order_no LIKE '%{$sPOs[$i]}%' ";
				}

				$sOrderNoSql .= ") ";
			}


			if ($FromDate != "" && $ToDate != "")
			{
				$sSQL = "SELECT po.id
						 FROM tbl_po po, tbl_po_colors pc
						 WHERE po.id=pc.po_id AND po.status!='C' AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate')";

				$sSQL .= str_replace("vendor_id", "po.vendor_id", $sVendorsSql);
				$sSQL .= str_replace("order_no", "po.order_no", $sOrderNoSql);

				if ($Mode == "Vendors")
					$sSQL .= " AND po.vendor_id='{$Vendors[$iIndex]}' AND FIND_IN_SET(po.brand_id, '{$_SESSION['Brands']}') ";

				else if ($Mode == "Brands")
					$sSQL .= " AND po.brand_id='{$Brands[$iIndex]}' AND FIND_IN_SET(po.vendor_id, '{$_SESSION['Vendors']}') ";

				if ($Brand > 0)
					$sSQL .= " AND po.brand_id='$Brand' ";

				if ($Vendor > 0)
					$sSQL .= " AND po.vendor_id='$Vendor' ";

				if ($Category > 0)
					$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE category_id='$Category') ";
			}

			else
			{
				$sSQL = "SELECT id FROM tbl_po WHERE status!='C' $sVendorsSql $sOrderNoSql";

				if ($Mode == "Vendors")
					$sSQL .= " AND vendor_id='{$Vendors[$iIndex]}' AND FIND_IN_SET(brand_id, '{$_SESSION['Brands']}') ";

				else if ($Mode == "Brands")
					$sSQL .= " AND brand_id='{$Brands[$iIndex]}' AND FIND_IN_SET(vendor_id, '{$_SESSION['Vendors']}') ";

				if ($Brand > 0)
					$sSQL .= " AND brand_id='$Brand' ";

				if ($Vendor > 0)
					$sSQL .= " AND vendor_id='$Vendor' ";

				if ($Category > 0)
					$sSQL .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE category_id='$Category') ";
			}

			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
				$sAllPos .= (",".$objDb->getField($i, 0));


			$sClass      = array("evenRow", "oddRow");
			$sConditions = "";
			$sDateRange  = "";

			if ($Status == "Problematic POs")
			{
				$sSQL = "SELECT po.id
						 FROM tbl_po po, tbl_vsr vsr
						 WHERE po.id=vsr.po_id AND po.id IN ($sAllPos) AND
							   (
								 (CURDATE( ) > vsr.dyeing_end_date AND vsr.dyeing < 100) OR
								 (CURDATE( ) > vsr.cutting_end_date AND vsr.cutting < 100) OR
								 (CURDATE( ) > vsr.stitching_end_date AND vsr.stitching < 100) OR
								 (CURDATE( ) > vsr.final_audit_date AND vsr.packing < 100) OR
								 (DATE_ADD(vsr.final_audit_date, INTERVAL 7 DAY) > DATE_ADD(DATE_FORMAT(LEFT(po.shipping_dates, 10), '%Y-%m-%d'), INTERVAL 2 DAY))
							   )";
				$objDb->query($sSQL);

				$iCount  = $objDb->getCount( );
				$sAllPos = "0";

				for ($i = 0; $i < $iCount; $i ++)
					$sAllPos .= (",".$objDb->getField($i, 0));
			}



			$sSQL = "SELECT id, order_no, order_status, quantity, vendor_id, shipping_dates, styles FROM tbl_po WHERE id IN ($sAllPos) ORDER BY id DESC";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iId          = $objDb->getField($i, 'id');
				$iVendorId    = $objDb->getField($i, 'vendor_id');
				$sStyles      = $objDb->getField($i, 'styles');
				$sEtdRequired = substr($objDb->getField($i, 'shipping_dates'), 0, 10);

				$sSQL = "SELECT style, sub_season_id, specs_file FROM tbl_styles WHERE id IN ($sStyles) ORDER BY id LIMIT 1";
				$objDb2->query($sSQL);

?>
			        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				      <tr class="<?= $sClass[($i % 2)] ?>">
				        <td width="6%" class="center"><input type="checkbox" name="Po[]" class="po<?= $iVendorBrandIndex ?>" value="<?= $iId ?>" /></td>
				        <td width="6%"><?= ($iStart + $i + 1) ?></td>
<?
				if (checkUserRights("view-purchase-order.php", "Data Entry", "view"))
				{
?>
				        <td width="16%"><a href="data/view-purchase-order.php?Id=<?= $iId ?>" class="lightview sheetLink" rel="iframe" title="PO # <?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?> :: :: width: 700, height: 550"><?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?></a></td>
<?
				}

				else
				{
?>
				        <td width="16%"><?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?></td>
<?
				}
?>
				        <td width="16%"><?= $sVendorsList[$iVendorId] ?></td>
				        <td width="13%"><?= $sSeasonsList[$objDb2->getField(0, 'sub_season_id')] ?></td>
<?
				$sSpecsFile = $objDb2->getField(0, 'specs_file');

				if ($sSpecsFile != "" && @file_exists($sBaseDir.STYLES_SPECS_DIR.$sSpecsFile))
				{
?>
				        <td width="14%"><a href="<?= STYLES_SPECS_DIR.$sSpecsFile ?>" target="_blank" class="sheetLink"><?= $objDb2->getField(0, 'style') ?></a></td>
<?
				}

				else
				{
?>
				        <td width="14%"><?= $objDb2->getField(0, 'style') ?></td>
<?
				}
?>
				        <td width="12%"><?= formatDate($sEtdRequired) ?></td>
				        <td width="8%"><?= formatNumber($objDb->getField($i, 'quantity'), false) ?></td>

				        <td width="9%" class="center">
<?
				if (checkUserRights("view-vsr-po.php", "VSR", "view"))
				{
?>
				          <a href="vsr/view-vsr-po.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="PO # <?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?> :: :: width: 520, height: 460"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
				          &nbsp;
<?
				}
?>
				          <a href="vsr/view-vsr-details.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="PO # <?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?> :: :: width: 940, height: 500"><img src="images/icons/deviation.gif" width="16" height="16" alt="Statistics" title="Statistics" /></a>
				        </td>
				      </tr>
			        </table>
<?
			}

			if ($iCount == 0)
			{
?>
				  	<div class="noRecord">No PO Record Found!</div>
<?
			}
?>

			        </form>
			      </div>
			    </div>

<?
			if ($iCount > 0)
			{
?>
				<div class="tblSheet" style="margin:4px 0px 1px 0px; padding:1px;">
				  <div class="buttonsBar">
				    <input type="button" value="" id="BtnExportActualVsr" class="btnExportActualVsr" title="Export" onclick="exportReport('Actual', <?= $iVendorBrandIndex ?>);" />
<?
				if ($sCategory != "BTX")
				{
?>
<!--
				    <input type="button" value="" id="BtnExportPlannedVsr" class="btnExportPlannedVsr" title="Export" onclick="exportReport('Planned', <?= $iVendorBrandIndex ?>);" />
				    <input type="button" value="" id="BtnExportComparativeVsr" class="btnExportComparativeVsr" title="Export" onclick="exportReport('Comparative', <?= $iVendorBrandIndex ?>);" />
-->
<?
				}
?>
				    <div class="options">
				      <input type="radio" name="FileFormat" id="FileFormatXlsx" value="xlsx" checked />
				      <b>Excel</b>
<!--
				      <input type="radio" name="FileFormat" id="FileFormatPdf" value="pdf" />
				      <b>PDF</b>
-->
				    </div>
				  </div>
				</div>
<?
			}
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