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

	$PageId      = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$OrderNo     = IO::strValue("OrderNo");
	$Vendor      = IO::intValue("Vendor");
	$Brand       = IO::intValue("Brand");
	$Region      = IO::intValue("Region");
	$ShFromDate  = IO::strValue("ShFromDate");
	$ShToDate    = IO::strValue("ShToDate");
	$EtdFromDate = IO::strValue("EtdFromDate");
	$EtdToDate   = IO::strValue("EtdToDate");

	$sVendorsList    = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList     = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
	$sCategoriesList = getList("tbl_categories", "id", "category");
	$sArBrands       = array( );


	$sSQL = "SELECT id FROM tbl_brands WHERE parent_id IN (66,74)";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sArBrands[] = $objDb->getField($i, 0);
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
			    <h1>PO Commission</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="35">PO #</td>
			          <td width="117"><input type="text" name="OrderNo" value="<?= $OrderNo ?>" class="textbox" maxlength="50" size="12" /></td>
			          <td width="52">Vendor</td>

			          <td width="190">
			            <select name="Vendor" style="width:180px;">
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

			          <td width="155">
			            <select name="Brand" style="width:140px;">
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

					  <td>
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
			    </form>

			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";


	$sSQL = "SELECT COUNT(DISTINCT(po.id)) FROM tbl_po po, tbl_po_colors pc WHERE po.id=pc.po_id";

	if ($OrderNo != "")
		$sSQL .= " AND po.order_no LIKE '%$OrderNo%' ";

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($EtdFromDate != "" && $EtdToDate != "")
		$sSQL .= " AND (pc.etd_required BETWEEN '$EtdFromDate' AND '$EtdToDate') ";

	if ($ShFromDate != "" && $ShToDate != "")
		$sSQL .= " AND po.id IN (SELECT DISTINCT(po_id) FROM tbl_pre_shipment_detail WHERE (shipping_date BETWEEN '$ShFromDate' AND '$ShToDate') AND shipping_date != '0000-00-00' AND NOT ISNULL(shipping_date))";

	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo($sSQL, $sConditions, $iPageSize, $PageId);



	$sSQL = "SELECT po.id, po.order_no, po.order_status, po.quantity, po.brand_id, po.vendor_id, po.currency,
	                (SELECT category_id FROM tbl_vendors WHERE id=po.vendor_id) AS _CategoryId
			 FROM tbl_po po, tbl_po_colors pc
			 WHERE po.id=pc.po_id";

	if ($OrderNo != "")
		$sSQL .= " AND po.order_no LIKE '%$OrderNo%' ";

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($EtdFromDate != "" && $EtdToDate != "")
		$sSQL .= " AND (pc.etd_required BETWEEN '$EtdFromDate' AND '$EtdToDate') ";

	if ($ShFromDate != "" && $ShToDate != "")
		$sSQL .= " AND po.id IN (SELECT DISTINCT(po_id) FROM tbl_pre_shipment_detail WHERE (shipping_date BETWEEN '$ShFromDate' AND '$ShToDate') AND shipping_date != '0000-00-00' AND NOT ISNULL(shipping_date))";

	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	$sSQL .= " GROUP BY po.id
	           ORDER BY po.id DESC
	           LIMIT $iStart, $iPageSize";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="6%">#</td>
				      <td width="13%">Order No</td>
				      <td width="15%">Vendor</td>
				      <td width="15%">Brand</td>
				      <td width="10%">Category</td>
				      <td width="9%">Order Qty</td>
				      <td width="7%">Ship Qty</td>
				      <td width="18%">Commission</td>
				      <td width="7%" class="center">Options</td>
				    </tr>
<?
		}

		$iId       = $objDb->getField($i, 'id');
		$iBrandId  = $objDb->getField($i, 'brand_id');
		$sCurrency = $objDb->getField($i, 'currency');


		$sSymbol         = $sCurrency;
		$sCommissionType = getDbValue("commission_type", "tbl_brands", "id='$iBrandId'");

		if ($sCommissionType == "F" && $sCurrency == "USD")
			$sSymbol = "�";


		$iCount2 = 0;

		if (@in_array($iBrandId, $sArBrands))
		{
			$sSQL = "SELECT id, quantity, commission FROM tbl_pre_shipment_detail WHERE po_id='$iId'";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );
		}


		if (@in_array($iBrandId, $sArBrands) && $iCount2 > 1)
		{
			for ($j = 0; $j < $iCount2; $j ++)
			{
?>

				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?></td>
				      <td><?= $sVendorsList[$objDb->getField($i, 'vendor_id')] ?></td>
				      <td><?= $sBrandsList[$iBrandId] ?></td>
				      <td><?= $sCategoriesList[$objDb->getField($i, '_CategoryId')] ?></td>
				      <td><?= formatNumber($objDb->getField($i, 'quantity'), false) ?></td>
				      <td><?= formatNumber($objDb2->getField($j, 'quantity'), false) ?></td>

				      <td>
				        <div class="commission">
				          <span id="Commission<?= $i ?>_<?= $j ?>"><?= formatNumber($objDb2->getField($j, 'commission'), (($sSymbol == "�") ? false : true)) ?></span>
<?
				if ($sUserRights['Edit'] == "Y")
				{
?>

						  <script type="text/javascript">
						  <!--
						      var objEditor<?= $i ?>_<?= $j ?> = new Ajax.InPlaceEditor('Commission<?= $i ?>_<?= $j ?>', 'ajax/data/save-po-commission.php', { cancelControl:'button', okText:'  Ok  ', cancelText:'Cancel', clickToEditText:'Click to Edit', externalControl:'Edit<?= $i ?>_<?= $j ?>', highlightcolor:'<?= HOVER_ROW_COLOR ?>', highlightendcolor:'<?= $sColor[($i % 2)] ?>', callback:function(form, value) { return 'Id=<?= $iId ?>&ShipId=<?= $objDb2->getField($j, 'id') ?>&Commission=' + encodeURIComponent(value) }, onEnterEditMode:function(form, value) { $('Commission<?= $i ?>_<?= $j ?>').focus( ); } });
						  -->
						  </script>
<?
				}
?>
				          <?= $sSymbol ?>
				        </div>
				      </td>

				      <td class="center">
<?
				if ($sUserRights['Edit'] == "Y")
				{
?>
				        <a href="./" id="Edit<?= $i ?>_<?= $j ?>" onclick="objEditor<?= $i ?>_<?= $j ?>.enterEditMode( ); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
				}
?>
				        <a href="data/view-purchase-order.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="PO # <?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?> :: :: width: 700, height: 550"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
				      </td>
				    </tr>
<?
			}
		}

		else
		{
			$sSQL = "SELECT quantity, commission FROM tbl_pre_shipment_advice WHERE po_id='$iId'";
			$objDb2->query($sSQL);

			$fQuantity   = $objDb2->getField(0, 0);
			$fCommission = $objDb2->getField(0, 1);


			if ($fQuantity > 0)
			{
				$sSQL = "SELECT COALESCE(SUM(quantity), 0), MAX(commission) FROM tbl_pre_shipment_detail WHERE po_id='$iId'";
				$objDb2->query($sSQL);

				$fQuantity   = $objDb2->getField(0, 0);
				$fCommission = $objDb2->getField(0, 1);
			}
?>

				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?></td>
				      <td><?= $sVendorsList[$objDb->getField($i, 'vendor_id')] ?></td>
				      <td><?= $sBrandsList[$iBrandId] ?></td>
				      <td><?= $sCategoriesList[$objDb->getField($i, '_CategoryId')] ?></td>
				      <td><?= formatNumber($objDb->getField($i, 'quantity'), false) ?></td>
				      <td><?= formatNumber($fQuantity, false) ?></td>

				      <td>
				        <div class="commission">
				          <span id="Commission<?= $i ?>"><?= formatNumber($fCommission, (($sSymbol == "�") ? false : true)) ?></span>
<?
			if ($sUserRights['Edit'] == "Y")
			{
?>

						  <script type="text/javascript">
						  <!--
						      var objEditor<?= $i ?> = new Ajax.InPlaceEditor('Commission<?= $i ?>', 'ajax/data/save-po-commission.php', { cancelControl:'button', okText:'  Ok  ', cancelText:'Cancel', clickToEditText:'Click to Edit', externalControl:'Edit<?= $i ?>', highlightcolor:'<?= HOVER_ROW_COLOR ?>', highlightendcolor:'<?= $sColor[($i % 2)] ?>', callback:function(form, value) { return 'Id=<?= $iId ?>&Commission=' + encodeURIComponent(value) }, onEnterEditMode:function(form, value) { $('Commission<?= $i ?>').focus( ); } });
						  -->
						  </script>
<?
			}
?>
				          <?= $sSymbol ?>
				        </div>
				      </td>

				      <td class="center">
<?
			if ($sUserRights['Edit'] == "Y")
			{
?>
				        <a href="./" id="Edit<?= $i ?>" onclick="objEditor<?= $i ?>.enterEditMode( ); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
			}
?>
				        <a href="data/view-purchase-order.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="PO # <?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?> :: :: width: 800, height: 550"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
				      </td>
				    </tr>
<?
		}
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No PO Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&OrderNo={$OrderNo}&Vendor={$Vendor}&Brand={$Brand}&Region={$Region}&ShFromDate={$ShFromDate}&ShToDate={$ShToDate}&EtdFromDate={$EtdFromDate}&EtdToDate={$EtdToDate}");
?>

			  </td>
			</tr>
		  </table>

<?
	$sSQL = "SELECT COALESCE(SUM(pq.quantity), 0)
			 FROM tbl_po po, tbl_po_quantities pq, tbl_po_colors pc
			 WHERE po.id=pq.po_id AND po.id=pc.po_id AND pc.id=pq.color_id";

	if ($OrderNo != "")
		$sSQL .= " AND po.order_no LIKE '%$OrderNo%' ";

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($EtdFromDate != "" && $EtdToDate != "")
		$sSQL .= " AND (pc.etd_required BETWEEN '$EtdFromDate' AND '$EtdToDate') ";

	if ($ShFromDate != "" && $ShToDate != "")
		$sSQL .= " AND po.id IN (SELECT DISTINCT(po_id) FROM tbl_pre_shipment_detail WHERE (shipping_date BETWEEN '$ShFromDate' AND '$ShToDate') AND shipping_date != '0000-00-00' AND NOT ISNULL(shipping_date))";

	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	$objDb->query($sSQL);

	$iOrderQty = $objDb->getField(0, 0);
?>
		  <div class="orderQty">Order Quantity: <?= formatNumber($iOrderQty, false) ?></div>

<?
	$sSQL = "SELECT COALESCE(SUM(psq.quantity), 0)
			 FROM tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
			 WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id";

	if ($OrderNo != "")
		$sSQL .= " AND po.order_no LIKE '%$OrderNo%' ";

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($EtdFromDate != "" && $EtdToDate != "")
		$sSQL .= " AND (pc.etd_required BETWEEN '$EtdFromDate' AND '$EtdToDate') ";

	if ($ShFromDate != "" && $ShToDate != "")
		$sSQL .= " AND (psd.shipping_date BETWEEN '$ShFromDate' AND '$ShToDate') AND psd.shipping_date != '0000-00-00' AND NOT ISNULL(psd.shipping_date)";

	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	$objDb->query($sSQL);

	$iShipQty = $objDb->getField(0, 0);
?>
		  <div class="shipQty">Shipment Quantity: <?= formatNumber($iShipQty, false) ?></div>

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