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
	$objDb3      = new Database( );

	$PageId      = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$OrderNo     = IO::strValue("OrderNo");
	$Vendor      = IO::intValue("Vendor");
	$Brand       = IO::intValue("Brand");
	$Status      = IO::strValue("Status");
	$ShFromDate  = IO::strValue("ShFromDate");
	$ShToDate    = IO::strValue("ShToDate");
	$EtdFromDate = IO::strValue("EtdFromDate");
	$EtdToDate   = IO::strValue("EtdToDate");
	$Region      = IO::intValue("Region");
	$Season      = IO::intValue("Season");

	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];


	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");


	if (!$_GET && ($ShFromDate == "" && $ShToDate == "" && $EtdFromDate == "" && $EtdToDate == ""))
	{
		$EtdFromDate = date("Y-m-01");
		$EtdToDate   = date("Y-m-t");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/vsn/otp.js"></script>
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
			    <h1>On Time Performance</h1>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="35">PO #</td>
			          <td width="95"><input type="text" name="OrderNo" value="<?= $OrderNo ?>" class="textbox" maxlength="50" size="10" /></td>
			          <td width="52">Vendor</td>

			          <td width="205">
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

			          <td width="160">
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

					  <td width="50">Status</td>

					  <td>
					    <select name="Status">
						  <option value=""></option>
	  	        		  <option value="OnTime"<?= (($Status == "OnTime") ? " selected" : "") ?>>On Time</option>
	  	        		  <option value="Delayed"<?= (($Status == "Delayed") ? " selected" : "") ?>>Delayed</option>
	  	        		  <option value="UnShipped"<?= (($Status == "UnShipped") ? " selected" : "") ?>>Un Shipped</option>
					    </select>
					  </td>

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
					  <td width="95">Shipping Date</td>
					  <td width="78"><input type="text" name="ShFromDate" value="<?= $ShFromDate ?>" id="ShFromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ShFromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ShFromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20" align="center">-</td>
					  <td width="78"><input type="text" name="ShToDate" value="<?= $ShToDate ?>" id="ShToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ShToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="25"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ShToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="100">[ <a href="#" onclick="$('ShFromDate').value=''; $('ShToDate').value=''; return false;">Clear</a> ]</td>

					  <td width="95">ETD Required</td>
					  <td width="78"><input type="text" name="EtdFromDate" value="<?= $EtdFromDate ?>" id="EtdFromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('EtdFromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('EtdFromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20" align="center">-</td>
					  <td width="78"><input type="text" name="EtdToDate" value="<?= $EtdToDate ?>" id="EtdToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('EtdToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="25"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('EtdToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td>[ <a href="#" onclick="$('EtdFromDate').value=''; $('EtdToDate').value=''; return false;">Clear</a> ]</td>
				    </tr>
				  </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
					  <td width="50">Region</td>

					  <td width="150">
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

			          <td width="200">
			            <select name="Season" id="Season">
			              <option value="">All Seasons</option>
<?
	if ($Brand > 0)
	{
		$iParent      = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$sSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iParent' AND parent_id>'0'");

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

			          <td></td>
				    </tr>
				  </table>
			    </div>
			    </form>

			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($OrderNo != "")
		$sConditions .= " AND order_no LIKE '%$OrderNo%' ";

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	else
		$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Region > 0)
		$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($Brand > 0)
		$sConditions .= " AND brand_id='$Brand' ";

	else
		$sConditions .= " AND brand_id IN ({$_SESSION['Brands']}) ";


	$sSQL = "SELECT DISTINCT(pc.po_id)
	         FROM tbl_po po, tbl_po_colors pc, tbl_styles s
	         WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.vendor_id IN ({$_SESSION['Vendors']}) AND s.sub_brand_id IN ({$_SESSION['Brands']}) AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}') ";

	if ($Season > 0)
		$sSQL .= " AND s.sub_season_id='$Season' ";

	if ($EtdFromDate != "" && $EtdToDate != "")
		$sSQL .= " AND (pc.etd_required BETWEEN '$EtdFromDate' AND '$EtdToDate') ";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$sPos   = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sPos .= (",".$objDb->getField($i, 0));

	if ($sPos != "")
		$sPos = substr($sPos, 1);

	$sConditions .= " AND id IN ($sPos) ";


	if ($ShFromDate != "" && $ShToDate != "")
	{
		$sSQL = "SELECT DISTINCT(psd.po_id)
		         FROM tbl_pre_shipment_detail psd, tbl_po po
		         WHERE psd.po_id=po.id AND (psd.handover_to_forwarder BETWEEN '$ShFromDate' AND '$ShToDate')";

		if ($OrderNo != "")
			$sSQL .= " AND po.order_no LIKE '%$OrderNo%' ";

		if ($Vendor > 0)
			$sSQL .= " AND po.vendor_id='$Vendor' ";

		else
			$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

		if ($Brand > 0)
			$sSQL .= " AND po.brand_id='$Brand' ";

		else
			$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND id IN ($sPos) ";
	}


	if ($Status != "")
	{
		if ($Status == "Delayed")
		{
			$sSQL = "SELECT DISTINCT(pc.po_id)
			         FROM tbl_pre_shipment_detail psd, tbl_po_colors pc, tbl_po po
			         WHERE pc.po_id=psd.po_id AND po.id=pc.po_id AND po.id=psd.po_id AND pc.etd_required < psd.handover_to_forwarder AND psd.handover_to_forwarder != ''";
		}

		else if ($Status == "OnTime")
		{
			$sSQL = "SELECT DISTINCT(pc.po_id)
			         FROM tbl_pre_shipment_detail psd, tbl_po_colors pc, tbl_po po
			         WHERE pc.po_id=psd.po_id AND po.id=pc.po_id AND po.id=psd.po_id AND pc.etd_required >= psd.handover_to_forwarder AND psd.handover_to_forwarder != ''";
		}

		else if ($Status == "UnShipped")
		{
			$sSQL = "SELECT DISTINCT(px.po_id)
			         FROM tbl_po_colors pc, tbl_po po
			         WHERE pc.po_id=po.id AND pc.etd_required <= CURDATE( ) AND po_id NOT IN (SELECT DISTINCT(po_id) FROM tbl_pre_shipment_detail WHERE handover_to_forwarder!='0000-00-00')";
		}


		if ($OrderNo != "")
			$sSQL .= " AND po.order_no LIKE '%$OrderNo%' ";

		if ($Vendor > 0)
			$sSQL .= " AND po.vendor_id='$Vendor' ";

		else
			$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

		if ($Brand > 0)
			$sSQL .= " AND po.brand_id='$Brand' ";

		else
			$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

		if ($EtdFromDate != "" && $EtdToDate != "")
			$sSQL .= " AND (pc.etd_required BETWEEN '$EtdFromDate' AND '$EtdToDate') ";


		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND id IN ($sPos) ";
	}

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_po", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT id, CONCAT(order_no, ' ', order_status) AS _Po, vendor_id FROM tbl_po $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="8%">#</td>
				      <td width="16%">Order No</td>
				      <td width="21%">Vendor</td>
				      <td width="24%">ETD Required &nbsp;&nbsp;| Order Qty</td>
				      <td width="24%">Shipping Date &nbsp;&nbsp;| Ship Qty</td>
				      <td width="7%" class="center">Options</td>
				    </tr>
<?
		}

		$iId = $objDb->getField($i, 'id');

		$sBgColor = "";

		$sSQL = "SELECT MIN(etd_required) FROM tbl_po_colors WHERE po_id='$iId'";
		$objDb2->query($sSQL);

		$sEtdRequired = @strtotime($objDb2->getField(0, 0));


		$sSQL = "SELECT MIN(handover_to_forwarder) FROM tbl_pre_shipment_detail WHERE po_id='$iId' AND NOT ISNULL(handover_to_forwarder) AND handover_to_forwarder!='0000-00-00'";
		$objDb2->query($sSQL);

		$sShippingDate = @strtotime($objDb2->getField(0, 0));


		@list($iYear, $iMonth, $iDay) = @explode("-", $objDb2->getField(0, 0));

		$sDelayedDate = @date("Y-m-d", mktime(0, 0, 0, $iMonth, ($iDay - 7), $iYear));
		$sDelayedDate = @strtotime($sDelayedDate);

		$sToday = @date("Y-m-d");
		$sToday = @strtotime($sToday);

		if ($sToday >= $sEtdRequired)
		{
			if ($sShippingDate == 0)
				$sBgColor = 'style="background:#b69595;"';

			else if ($sDelayedDate > $sEtdRequired)
				$sBgColor = 'style="background:#eab7b7;"';

			else if ($sShippingDate > $sEtdRequired)
				$sBgColor = 'style="background:#ffeaea;"';
		}
?>

				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top" <?= $sBgColor ?>>
				      <td><?= ($iStart + $i + 1) ?></td>
<?
		if (checkUserRights("view-purchase-order.php", "Data Entry", "view"))
		{
?>
				      <td><a href="data/view-purchase-order.php?Id=<?= $iId ?>" class="lightview sheetLink" rel="iframe" title="PO # <?= $objDb->getField($i, '_Po') ?> :: :: width: 700, height: 550"><?= $objDb->getField($i, '_Po') ?></a></td>
<?
		}

		else
		{
?>
				      <td><?= $objDb->getField($i, '_Po') ?></td>
<?
		}
?>
				      <td><?= $sVendorsList[$objDb->getField($i, 'vendor_id')] ?></td>

				      <td style="padding:0px;">

				        <table border="1" bordercolor="#ffffff" cellpadding="6" cellspacing="0" width="100%">
<?
		$sSQL = "SELECT DISTINCT(etd_required) FROM tbl_po_colors WHERE po_id='$iId'";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$sEtdRequired = $objDb2->getField($j, 0);

			$sSQL = "SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id='$iId' AND color_id IN (SELECT id FROM tbl_po_colors WHERE po_id='$iId' AND etd_required='$sEtdRequired')";
			$objDb3->query($sSQL);

			$iQuantity = $objDb3->getField(0, 0);
?>
				          <tr>
				            <td width="50%"><?= formatDate($sEtdRequired) ?></td>
				            <td width="50%"><?= formatNumber($iQuantity, false) ?></td>
				          </tr>
<?
		}
?>
				        </table>

				      </td>

				      <td style="padding:0px;">

				        <table border="1" bordercolor="#ffffff" cellpadding="6" cellspacing="0" width="100%">
<?
		$sSQL = "SELECT DISTINCT(handover_to_forwarder) FROM tbl_pre_shipment_detail WHERE po_id='$iId'";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$sShippingDate = $objDb2->getField($j, 0);

			$sSQL = "SELECT SUM(quantity) FROM tbl_pre_shipment_quantities WHERE po_id='$iId' AND ship_id IN (SELECT id FROM tbl_pre_shipment_detail WHERE po_id='$iId' AND handover_to_forwarder='$sShippingDate')";
			$objDb3->query($sSQL);

			$iQuantity = $objDb3->getField(0, 0);
?>
				          <tr>
				            <td width="50%"><?= formatDate($sShippingDate) ?></td>
				            <td width="50%"><?= formatNumber($iQuantity, false) ?></td>
				          </tr>
<?
		}
?>
				        </table>

				      </td>

				      <td class="center">
<?
		if (checkUserRights("view-pre-shipment-detail.php", "Shipping", "view"))
		{
?>
				        <a href="shipping/view-pre-shipment-detail.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="PO # <?= $objDb->getField($i, '_Po') ?> :: :: width: 700, height: 550"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
<?
		}

		else
		{
?>
				        -
<?
		}
?>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&OrderNo={$OrderNo}&Vendor={$Vendor}&Brand={$Brand}&Status={$Status}&ShFromDate={$ShFromDate}&ShToDate={$ShToDate}&EtdFromDate={$EtdFromDate}&EtdToDate={$EtdToDate}&Region={$Region}");


	if ($_SESSION['Guest'] != "Y")
	{
?>

				<div class="buttonsBar" style="margin-top:4px;">
				  <input type="hidden" id="ExportUrl" name="ExportUrl" value="<?= (SITE_URL."vsn/export-otp.php?OrderNo={$OrderNo}&Vendor={$Vendor}&Brand={$Brand}&Status={$Status}&ShFromDate={$ShFromDate}&ShToDate={$ShToDate}&EtdFromDate={$EtdFromDate}&EtdToDate={$EtdToDate}&Region={$Region}") ?>" />
				  <input type="button" value="" id="BtnExport" class="btnExport" title="Export" onclick="exportReport( );" />
				</div>
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
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>