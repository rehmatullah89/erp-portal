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

	$PageId      = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$OrderNo     = IO::strValue("OrderNo");
	$PoType      = IO::strValue("PoType");
	$PoNature    = IO::strValue("PoNature");
	$Vendor      = IO::intValue("Vendor");
	$Brand       = IO::intValue("Brand");
	$Region      = IO::intValue("Region");
	$Style       = IO::strValue("Style");
	$Season      = IO::intValue("Season");
	$Destination = IO::intValue("Destination");
	$FromDate    = IO::strValue("FromDate");
	$ToDate      = IO::strValue("ToDate");

	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];

	$sRegionsList      = getList("tbl_countries", "id", "country", "matrix='Y'");
	$sVendorsList      = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList       = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sSeasonsList      = getList("tbl_seasons", "id", "season", "parent_id>'0'");
	$sDestinationsList = array( );

	if ($Brand > 0)
	{
		$iParent           = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$sSeasonsList      = getList("tbl_seasons", "id", "season", "brand_id='$iParent' AND parent_id>'0'");
		$sDestinationsList = getList("tbl_destinations", "id", "destination", "brand_id='$iParent'");
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
			    <h1>ESCROW Orders</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="35">PO #</td>
			          <td width="115"><input type="text" name="OrderNo" value="<?= $OrderNo ?>" class="textbox" maxlength="50" size="12" /></td>
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
			            <select name="Brand" id="Brand" style="width:140px;" onchange="getListValues('Brand', 'Season', 'BrandSeasons'); getListValues('Brand', 'Destination', 'BrandDestinations');">
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
	foreach ($sRegionsList as $sKey => $sValue)
	{
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
			          <td width="40">Style</td>
			          <td width="115"><input type="text" name="Style" value="<?= $Style ?>" class="textbox" maxlength="50" size="12" /></td>
			          <td width="55">Season</td>

			          <td width="210">
			            <select name="Season" id="Season" style="width:200px;">
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

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
			          <td width="80">Destination</td>

			          <td width="220">
			            <select name="Destination" id="Destination" style="width:200px;">
			              <option value="">All Destinations</option>
<?
	if ($Brand > 0)
	{
		foreach ($sDestinationsList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Destination) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
	}
?>
			            </select>
			          </td>

			          <td width="60">PO Type</td>

			          <td width="100">
			            <select name="PoType" id="PoType">
						  <option value="">All</option>
	  	        		  <option value="SDP"<?= (($PoType == "SDP") ? " selected" : "") ?>>SDP</option>
	  	        		  <option value="Non-SDP"<?= (($PoType == "Non-SDP") ? " selected" : "") ?>>Non-SDP</option>
			            </select>
			          </td>

			          <td width="70">PO Nature</td>

			          <td width="85">
			            <select name="PoNature" id="PoNature">
						  <option value="">All</option>
	  	        		  <option value="S"<?= (($PoNature == "S") ? " selected" : "") ?>>SMS</option>
	  	        		  <option value="B"<?= (($PoNature == "B") ? " selected" : "") ?>>Bulk</option>
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


	$sSQL = "SELECT COUNT(DISTINCT(po.id))
			 FROM tbl_escrow_po po, tbl_escrow_po_colors pc, tbl_styles s
			 WHERE po.id=pc.po_id AND pc.style_id=s.id AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}') ";

	if ($OrderNo != "")
	{
		if (@strpos($OrderNo, ",") === FALSE)
			$sSQL .= " AND po.order_no LIKE '%$OrderNo%' ";

		else
		{
			$sPOs = @explode(",", $OrderNo);

			$sSQL .= " AND (";

			for ($i = 0; $i < count($sPOs); $i ++)
			{
				if ($i > 0)
					$sSQL .= " OR ";

				$sSQL .= " po.order_no LIKE '%{$sPOs[$i]}%' ";
			}

			$sSQL .= ")";
		}
	}

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($FromDate != "" && $ToDate != "")
		$sSQL .= " AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($PoType != "")
		$sSQL .= " AND po.order_type='$PoType' ";

	if ($PoNature != "")
		$sSQL .= " AND po.order_nature='$PoNature' ";

	if ($Style != "" && $Season > 0)
		$sSQL .= " AND (s.style LIKE '%$Style%' AND s.sub_season_id='$Season') ";

	else if ($Style != "")
		$sSQL .= " AND s.style LIKE '%$Style%' ";

	else if ($Season > 0)
		$sSQL .= " AND s.sub_season_id='$Season' ";

	if ($Destination > 0)
		$sSQL .= " AND pc.destination_id='$Destination' ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo($sSQL, $sConditions, $iPageSize, $PageId);



	$sSQL = "SELECT po.id, po.order_no, po.order_status, po.quantity, po.vendor_id, po.shipping_dates, po.status, po.reason, po.details,
	                (SELECT style FROM tbl_styles WHERE id IN (po.styles) ORDER BY id LIMIT 1) AS _Style,
	                (SELECT sub_season_id FROM tbl_styles WHERE id IN (po.styles) ORDER BY id LIMIT 1) AS _Season
			 FROM tbl_escrow_po po, tbl_escrow_po_colors pc, tbl_styles s
			 WHERE po.id=pc.po_id AND pc.style_id=s.id";

	if ($OrderNo != "")
	{
		if (@strpos($OrderNo, ",") === FALSE)
			$sSQL .= " AND po.order_no LIKE '%$OrderNo%' ";

		else
		{
			$sPOs = @explode(",", $OrderNo);

			$sSQL .= " AND (";

			for ($i = 0; $i < count($sPOs); $i ++)
			{
				if ($i > 0)
					$sSQL .= " OR ";

				$sSQL .= " po.order_no LIKE '%{$sPOs[$i]}%' ";
			}

			$sSQL .= ")";
		}
	}

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($FromDate != "" && $ToDate != "")
		$sSQL .= " AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($PoType != "")
		$sSQL .= " AND po.order_type='$PoType' ";

	if ($PoNature != "")
		$sSQL .= " AND po.order_nature='$PoNature' ";

	if ($Style != "" && $Season > 0)
		$sSQL .= " AND (s.style LIKE '%$Style%' AND s.sub_season_id='$Season') ";

	else if ($Style != "")
		$sSQL .= " AND s.style LIKE '%$Style%' ";

	else if ($Season > 0)
		$sSQL .= " AND s.sub_season_id='$Season' ";

	if ($Destination > 0)
		$sSQL .= " AND pc.destination_id='$Destination' ";

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
				      <td width="8%">#</td>
				      <td width="14%">Order No</td>
				      <td width="16%">Vendor</td>
				      <td width="13%">Season</td>
				      <td width="14%">Style No</td>
				      <td width="13%">ETD Required</td>
				      <td width="9%">Quantity</td>
				      <td width="13%" class="center">Options</td>
				    </tr>
<?
		}

		$iId          = $objDb->getField($i, 'id');
		$sStatus      = $objDb->getField($i, 'status');
		$sReason      = $objDb->getField($i, 'reason');
		$sDetails     = $objDb->getField($i, 'details');

		$sEtdRequired = formatDate(substr($objDb->getField($i, "shipping_dates"), 0, 10));
?>


				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?></td>
				      <td><?= $sVendorsList[$objDb->getField($i, 'vendor_id')] ?></td>
				      <td><?= $sSeasonsList[$objDb->getField($i, '_Season')] ?></td>
				      <td><?= $objDb->getField($i, '_Style') ?></td>

				      <td>
						<div class="etdRequired">
						  <span id="EtdRequired_<?= $i ?>"><?= (($sEtdRequired == "") ? 'N/A' : $sEtdRequired) ?></span>
						</div>
				      </td>

				      <td><?= formatNumber($objDb->getField($i, 'quantity'), false) ?></td>

				      <td class="center">
				        <img id="ToolTip<?= $iId ?>" src="images/icons/more.gif" width="16" height="16" alt="" title="" />
				        &nbsp;

						<script type="text/javascript">
						<!--
							new Tip('ToolTip<?= $iId ?>',
									"<?= $sReason ?><?= (($sDetails != "") ? ("<br /><br />".str_replace("\r\n", "", nl2br($sDetails))) : "") ?>",
									{ title:'Cancellation Reason', stem:'topLeft', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:300 });
							-->
						</script>

				        <a href="data/restore-escrow-order.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Restore this PO?');"><img src="images/icons/restore.gif" width="16" height="16" alt="Restore" title="Restore" /></a>
				        &nbsp;
				        <a href="data/view-escrow-order.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="PO # <?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?> :: :: width: 800, height: 550"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Escrow PO Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&OrderNo={$OrderNo}&Vendor={$Vendor}&Brand={$Brand}&Region={$Region}&Season={$Season}&Style={$Style}&FromDate={$FromDate}&ToDate={$ToDate}&PoType={$PoType}&PoNature={$PoNature}");

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