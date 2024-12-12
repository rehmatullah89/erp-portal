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

	$PageId    = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$WorkOrder = IO::strValue("WorkOrder");
	$Vendor    = IO::intValue("Vendor");
	$Brand     = IO::intValue("Brand");
	$Season    = IO::intValue("Season");

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
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
			    <h1>Work Orders</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="55">Vendor</td>

			          <td width="180">
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

			          <td width="95">Work Order #</td>
			          <td width="160"><input type="text" name="WorkOrder" value="<?= $WorkOrder ?>" class="textbox" maxlength="50" size="15" /></td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
			          <td width="55">Season</td>

			          <td width="180">
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

					  <td></td>
				    </tr>
				  </table>
			    </div>
			    </form>

<?
	$sClass      = array("evenRow", "evenRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = " WHERE ";

	if ($Vendor > 0)
		$sConditions .= " vendor_id='$Vendor' ";

	else
		$sConditions .= " vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Brand > 0)
		$sConditions .= " AND brand_id='$Brand' ";

	else
		$sConditions .= " AND brand_id IN ({$_SESSION['Brands']}) ";

	if ($Season > 0)
		$sConditions .= " AND season_id='$Season' ";

	if ($WorkOrder != "")
		$sConditions .= " AND work_order_no LIKE '%$WorkOrder%' ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_vsr2", $sConditions, $iPageSize, $PageId);

	$sSQL = "SELECT * FROM tbl_vsr2 $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>


			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="8%">#</td>
				      <td width="15%">Work Order</td>
				      <td width="20%">Vendor</td>
				      <td width="14%">Brand</td>
				      <td width="13%">Season</td>
				      <td width="10%">-</td>
				      <td width="10%">-</td>
				      <td width="10%" class="center">Options</td>
				    </tr>
<?
		}


		$iWorkOrder = $objDb->getField($i, 'id');
		$sWorkOrder = $objDb->getField($i, 'work_order_no');
		$iBrand     = $objDb->getField($i, 'brand_id');
		$iVendor    = $objDb->getField($i, 'vendor_id');
		$iSeason    = $objDb->getField($i, 'season_id');
?>


				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= $sWorkOrder ?></td>
				      <td><?= $sVendorsList[$iVendor] ?></td>
				      <td><?= $sBrandsList[$iBrand] ?></td>
				      <td><?= $sSeasonsList[$iSeason] ?></td>
				      <td>-</td>
				      <td>-</td>

				      <td class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="vsr/edit-work-order.php?Id=<?= $iWorkOrder ?>"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="vsr/delete-work-order.php?Id=<?= $iWorkOrder ?>" onclick="return confirm('Are you SURE, You want to Delete this Work Order?');"><img src="images/icons/delete.gif" width="16" height="16" hspace="4" alt="Delete" title="Delete" /></a>
<?
		}
?>
				        <a href="vsr/view-work-order.php?Id=<?= $iWorkOrder ?>" class="lightview" rel="iframe" title="Work Order # <?= $sWorkOrder ?> :: :: width: 850, height: 600"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
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
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&WorkOrder={$WorkOrder}&Vendor={$Vendor}&Brand={$Brand}&Season={$Season}");
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