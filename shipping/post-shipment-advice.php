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

	$PageId   = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$OrderNo  = IO::strValue("OrderNo");
	$Vendor   = IO::intValue("Vendor");
	$Brand    = IO::intValue("Brand");
	$Region   = IO::intValue("Region");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
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
			    <h1><img src="images/h1/shipping/post-shipment-advice.jpg" width="306" height="20" vspace="10" alt="" title="" /></h1>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="35">PO #</td>
			          <td width="110"><input type="text" name="OrderNo" value="<?= $OrderNo ?>" class="textbox" maxlength="50" size="12" /></td>
			          <td width="52">Vendor</td>

			          <td width="170">
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

			          <td width="150">
			            <select name="Brand">
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
					  <td width="40">From</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center">To</td>
					  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td>[ <a href="#" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;">Clear</a> ]</td>
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
	$sConditions = " WHERE po.id=psa.po_id";

	if ($OrderNo != "")
		$sConditions .= " AND po.order_no LIKE '%$OrderNo%' ";

	if ($Vendor > 0)
		$sConditions .= " AND po.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Brand > 0)
		$sConditions .= " AND po.brand_id='$Brand' ";

	else
		$sConditions .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($Region != "")
	{
		$sSQL = "SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y'";
		$objDb->query($sSQL);

		$iCount   = $objDb->getCount( );
		$sVendors = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sVendors .= (",".$objDb->getField($i, 0));

		if ($sVendors != "")
			$sVendors = substr($sVendors, 1);

		$sConditions .= " AND po.vendor_id IN ($sVendors) ";
	}


	if ($FromDate != "" && $ToDate != "")
	{
		$sSQL = "SELECT po_id FROM tbl_post_shipment_detail WHERE shipping_date BETWEEN '$FromDate' AND '$ToDate'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND po.id IN ($sPos) ";
	}

	$sSQL = "SELECT COUNT(*) FROM tbl_po po, tbl_post_shipment_advice psa $sConditions";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo($sSQL, "", $iPageSize, $PageId);


	$sSQL = "SELECT psa.po_id, psa.quantity, po.order_no, po.order_status, po.quantity, po.vendor_id, po.shipping_dates,
	                (SELECT shipping_documents FROM tbl_post_shipment_detail WHERE po_id=psa.po_id LIMIT 1) AS _ShippingDocuments,
	                (SELECT style FROM tbl_styles WHERE id IN (po.styles) ORDER BY id LIMIT 1) AS _Style
	         FROM tbl_po po, tbl_post_shipment_advice psa
	         $sConditions
	         ORDER BY psa.po_id DESC
	         LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="8%">#</td>
				      <td width="14%">Order No</td>
				      <td width="16%">Vendor</td>
				      <td width="14%">Style No</td>
				      <td width="13%">PO ETD</td>
				      <td width="10%">Order Qty</td>
				      <td width="11%">Ship Qty</td>
				      <td width="14%" class="center">Options</td>
				    </tr>
<?
		}

		$iId                = $objDb->getField($i, 'po_id');
		$sShippingDocuments = $objDb->getField($i, '_ShippingDocuments');
?>


				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($iStart + $i + 1) ?></td>
<?
		if (checkUserRights("view-purchase-order.php", "Data Entry", "view"))
		{
?>
				      <td><a href="data/view-purchase-order.php?Id=<?= $iId ?>" class="lightview sheetLink" rel="iframe" title="PO # <?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?> :: :: width: 700, height: 550"><?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?></a></td>
<?
		}

		else
		{
?>
				      <td><?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?></td>
<?
		}
?>
				      <td><?= $sVendorsList[$objDb->getField($i, 'vendor_id')] ?></td>
				      <td><?= $objDb->getField($i, '_Style') ?></td>
				      <td><?= formatDate(substr($objDb->getField($i, 'shipping_dates'), 0, 10)) ?></td>
				      <td><?= formatNumber($objDb->getField($i, 'po.quantity'), false) ?></td>
				      <td><?= formatNumber($objDb->getField($i, 'psa.quantity'), false) ?></td>

				      <td class="center">
<?
		if ($sShippingDocuments != "" && @file_exists($sBaseDir.POST_SHIPMENT_DIR.$sShippingDocuments))
		{
?>
				        <a href="<?= POST_SHIPMENT_DIR.$sShippingDocuments ?>" target="_blank"><img src="images/icons/pdf.gif" width="16" height="16" alt="Shipping Documents" title="Shipping Documents" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="shipping/edit-post-shipment-detail.php?Id=<?= $iId ?>&PO=<?= urlencode($objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status')) ?>"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}
?>
				        <a href="shipping/view-post-shipment-detail.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="PO # <?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?> :: :: width: 700, height: 550"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
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
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&OrderNo={$OrderNo}&Vendor={$Vendor}&Brand={$Brand}&Region={$Region}&FromDate={$FromDate}&ToDate={$ToDate}");


	$sConditions = "";

	if ($OrderNo != "")
		$sConditions .= " AND order_no LIKE '%$OrderNo%' ";

	if ($Vendor != 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	else
		$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Brand > 0)
		$sConditions .= " AND brand_id='$Brand' ";

	else
		$sConditions .= " AND brand_id IN ({$_SESSION['Brands']}) ";

	if ($Region != "")
	{
		$sSQL = "SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y'";
		$objDb->query($sSQL);

		$iCount   = $objDb->getCount( );
		$sVendors = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sVendors .= (",".$objDb->getField($i, 0));

		if ($sVendors != "")
			$sVendors = substr($sVendors, 1);

		$sConditions .= " AND vendor_id IN ($sVendors) ";
	}

	if ($sConditions != "")
		$sConditions = (" WHERE  ".@substr($sConditions, 5));


	$sSQL = "SELECT id FROM tbl_po $sConditions";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$sPos   = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sPos .= (",".$objDb->getField($i, 0));

	if ($sPos != "")
		$sPos = substr($sPos, 1);


	if ($FromDate != "" && $ToDate != "")
		$sDateRange = " AND etd_required BETWEEN '$FromDate' AND '$ToDate' ";

	$sSQL = "SELECT id FROM tbl_po_colors WHERE po_id IN ($sPos) $sDateRange";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$sColors = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sColors .= (",".$objDb->getField($i, 0));

	if ($sColors != "")
		$sColors = substr($sColors, 1);


	$sSQL = "SELECT SUM(quantity) FROM tbl_po_quantities WHERE color_id IN ($sColors)";
	$objDb->query($sSQL);
?>
			    <div class="orderQty">Order Quantity: <?= formatNumber($objDb->getField(0, 0), false) ?></div>
<?
	$sConditions = "";
	$sOrderSql   = "";
	$sBrandSql   = "";
	$sRegionSql  = "";

	if ($OrderNo != "")
		$sOrderSql = " AND order_no LIKE '%$OrderNo%'";

	if ($Brand > 0)
		$sBrandSql = " AND brand_id='$Brand' ";

	else
		$sBrandSql = " AND brand_id IN ({$_SESSION['Brands']}) ";


	if ($Region != "")
	{
		$sSQL = "SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y'";
		$objDb->query($sSQL);

		$iCount   = $objDb->getCount( );
		$sVendors = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sVendors .= (",".$objDb->getField($i, 0));

		if ($sVendors != "")
			$sVendors = substr($sVendors, 1);

		$sRegionSql = " AND vendor_id IN ($sVendors) ";
	}

	if ($Vendor > 0)
		$sSQL = "SELECT id FROM tbl_po WHERE vendor_id='$Vendor' $sOrderSql $sBrandSql $sRegionSql";

	else
		$sSQL = "SELECT id FROM tbl_po WHERE vendor_id IN ({$_SESSION['Vendors']}) $sOrderSql $sBrandSql $sRegionSql";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$sPos   = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sPos .= (",".$objDb->getField($i, 0));

	if ($sPos != "")
		$sPos = substr($sPos, 1);


	$sConditions = " AND po_id IN ($sPos) ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND shipping_date BETWEEN '$FromDate' AND '$ToDate' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	$sSQL = "SELECT SUM(quantity) FROM tbl_post_shipment_detail $sConditions";
	$objDb->query($sSQL);
?>
			    <div class="shipQty">Shipment Quantity: <?= formatNumber($objDb->getField(0, 0), false) ?></div>

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