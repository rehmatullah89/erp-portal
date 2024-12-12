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
	$Month    = IO::intValue("Month");
	$Year     = IO::intValue("Year");
	$Region   = IO::intValue("Region");
	$Category = IO::intValue("Category");
	$Vendor   = IO::intValue("Vendor");
	$Brand    = IO::intValue("Brand");
	$PostId   = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Month    = IO::intValue("Month");
		$Year     = IO::intValue("Year");
		$Region   = IO::intValue("Region");
		$Vendor   = IO::intValue("Vendor");
		$Brand    = IO::intValue("Brand");
		$Season   = IO::intValue("Season");
		$StyleNo  = IO::intValue("StyleNo");
		$Quantity = IO::intValue("Quantity");
	}

	$sVendorsList    = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList     = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sSeasonsList    = getList("tbl_seasons", "id", "season", "parent_id>'0'");
	$sCategoriesList = getList("tbl_categories", "id", "category");
	$sRegionsList    = getList("tbl_countries", "id", "country", "matrix='Y'");
	$sMonthsList     = array('January','February','March','April','May','June','July','August','September','October','November','December');

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
  <script type="text/javascript" src="scripts/data/revised-forecasts.js"></script>
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
			    <h1>Revised Forecasts</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-revised-forecast.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Forecast</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="60">Month<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Month">
						<option value=""></option>
<?
		for ($i = 1; $i <= 12; $i ++)
		{
?>
			            <option value="<?= $i ?>"<?= (($Month == $i) ? " selected" : "") ?>><?= $sMonthsList[($i - 1)] ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Year<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Year">
						<option value=""></option>
<?
		for ($i = 2009; $i <= (date("Y") + 1); $i ++)
		{
?>
			            <option value="<?= $i ?>"<?= (($i == $Year) ? " selected" : "") ?>><?= $i ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Region<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Region">
						<option value=""></option>
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
				  </tr>

				  <tr>
					<td>Vendor*</td>
					<td align="center">:</td>

					<td>
					  <select name="Vendor">
						<option value=""></option>
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
				  </tr>

				  <tr>
					<td>Brand*</td>
					<td align="center">:</td>

					<td>
					  <select name="Brand" id="Brand" onchange="getStylesList('Brand', 'Season', 'StyleNo'); getListValues('Brand', 'Season', 'BrandSeasons');">
						<option value=""></option>
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
				  </tr>

				  <tr>
					<td>Season<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Season" id="Season" onchange="getStylesList('Brand', 'Season', 'StyleNo');">
						<option value=""></option>
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

				  <tr>
					<td>Style No</td>
					<td align="center">:</td>

					<td>
					  <select name="StyleNo" id="StyleNo">
	  	        		<option value=""></option>
<?
		$sSQL = "SELECT id, style FROM tbl_styles WHERE sub_brand_id='$Brand' AND sub_season_id='$Season' ORDER BY style";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sKey   = $objDb->getField($i, 'id');
			$sValue = $objDb->getField($i, 'style');
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $StyleNo) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Quantity<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Quantity" value="<?= $Quantity ?>" size="18" maxlength="10" class="textbox" /></td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
					  <td width="48">Month</td>

					  <td width="110">
					    <select name="Month">
						  <option value="">All Months</option>
<?
	for ($i = 1; $i <= 12; $i ++)
	{
?>
			              <option value="<?= $i ?>"<?= (($Month == $i) ? " selected" : "") ?>><?= $sMonthsList[($i - 1)] ?></option>
<?
	}
?>
					    </select>
					  </td>

			          <td width="40">Year</td>

			          <td width="100">
					    <select name="Year">
						  <option value="">All Years</option>
<?
	for ($i = 2009; $i <= (date("Y") + 1); $i ++)
	{
?>
			              <option value="<?= $i ?>"<?= (($i == $Year) ? " selected" : "") ?>><?= $i ?></option>
<?
	}
?>
					    </select>
					  </td>

			          <td width="65">Category</td>

			          <td width="130">
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

			          <td width="52">Region</td>

			          <td width="130">
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
			          <td width="55">Vendor</td>

			          <td width="200">
					    <select name="Vendor" style="width:190px;">
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

			          <td width="200">
					    <select name="Brand" style="width:190px;">
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
				    </tr>
				  </table>
			    </div>
			    </form>


			    <div class="tblSheet">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Month > 0)
		$sConditions .= " AND month='$Month' ";

	if ($Year > 0)
		$sConditions .= " AND year='$Year' ";

	if ($Region > 0)
		$sConditions .= " AND country_id='$Region' ";

	if ($Category > 0)
	{
		$sSQL = "SELECT id FROM tbl_vendors WHERE category_id='$Category' AND parent_id='0' AND sourcing='Y'";
		$objDb->query($sSQL);

		$iCount   = $objDb->getCount( );
		$sVendors = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sVendors .= (",".$objDb->getField($i, 0));

		if ($sVendors != "")
			$sVendors = substr($sVendors, 1);

		$sConditions .= " AND vendor_id IN ($sVendors) ";
	}

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	else
		$sConditions .= " AND (vendor_id IN ({$_SESSION['Vendors']}) OR vendor_id='0')";

	if ($Brand > 0)
		$sConditions .= " AND brand_id='$Brand' ";

	else
		$sConditions .= " AND (brand_id IN ({$_SESSION['Brands']}) OR brand_id='0') ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_revised_forecasts", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT *,
	                (SELECT category_id FROM tbl_vendors WHERE id=tbl_revised_forecasts.vendor_id) AS _CategoryId
	         FROM tbl_revised_forecasts
	         $sConditions
	         ORDER BY month, year, vendor_id, brand_id DESC
	         LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="6%">#</td>
				      <td width="12%">Month/Year</td>
				      <td width="11%">Category</td>
				      <td width="14%">Vendor</td>
				      <td width="11%">Brand</td>
				      <td width="11%">Season</td>
				      <td width="11%">Style</td>
				      <td width="7%">Quantity</td>
				      <td width="7%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId       = $objDb->getField($i, 'id');
		$iMonth    = $objDb->getField($i, 'month');
		$iYear     = $objDb->getField($i, 'year');
		$iRegion   = $objDb->getField($i, 'country_id');
		$iVendor   = $objDb->getField($i, 'vendor_id');
		$iBrand    = $objDb->getField($i, 'brand_id');
		$iSeason   = $objDb->getField($i, 'season_id');
		$iStyleId  = $objDb->getField($i, 'style_id');
		$iQuantity = $objDb->getField($i, 'quantity');
		$iCategory = $objDb->getField($i, '_CategoryId');
		$sStyle    = "";

		if ($iStyleId > 0)
			$sStyle = getDbValue("style", "tbl_styles", "id='$iStyleId'");
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="6%"><?= ($iStart + $i + 1) ?></td>
				      <td width="12%"><span id="Month<?= $iId ?>"><?= $sMonthsList[($iMonth - 1)] ?></span> <span id="Year<?= $iId ?>"><?= $iYear ?></span></td>
				      <td width="11%"><span id="Category<?= $iId ?>"><?= $sCategoriesList[$iCategory] ?></span></td>
				      <td width="14%"><span id="Vendor<?= $iId ?>"><?= $sVendorsList[$iVendor] ?></span></td>
				      <td width="11%"><span id="Brand<?= $iId ?>"><?= $sBrandsList[$iBrand] ?></span></td>
				      <td width="11%"><span id="Season<?= $iId ?>"><?= $sSeasonsList[$iSeason] ?></span></td>
				      <td width="11%"><span id="Style<?= $iId ?>"><?= $sStyle ?></span></td>
				      <td width="7%"><span id="Quantity<?= $iId ?>"><?= $iQuantity ?></span></td>

				      <td width="7%" class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="./" onclick="Effect.SlideDown('Edit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="data/delete-revised-forecast.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Revised Forecast Entry?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="60">Month<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Month">
							  <option value=""></option>
<?
		for ($j = 1; $j <= 12; $j ++)
		{
?>
			            	  <option value="<?= $j ?>"<?= (($iMonth == $j) ? " selected" : "") ?>><?= $sMonthsList[($j - 1)] ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Year<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="Year">
							  <option value=""></option>
<?
		for ($j = 2009; $j <= (date("Y") + 1); $j ++)
		{
?>
			            	  <option value="<?= $j ?>"<?= (($j == $iYear) ? " selected" : "") ?>><?= $j ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Region<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="Region">
							  <option value=""></option>
<?
		foreach ($sRegionsList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $iRegion) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Vendor*</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Vendor">
							  <option value=""></option>
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $iVendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Brand*</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Brand" id="Brand_<?= $iId ?>" onchange="getStylesList('Brand_<?= $iId ?>', 'Season_<?= $iId ?>', 'StyleNo<?= $iId ?>'); getListValues('Brand_<?= $iId ?>', 'Season_<?= $iId ?>', 'BrandSeasons');">
							  <option value=""></option>
<?
		foreach ($sBrandsList as $sKey => $sValue)
		{
?>
			           		  <option value="<?= $sKey ?>"<?= (($sKey == $iBrand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Season<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="Season" id="Season_<?= $iId ?>" onchange="getStylesList('Brand_<?= $iId ?>', 'Season_<?= $iId ?>', 'StyleNo<?= $iId ?>');">
							  <option value=""></option>
<?
		if ($Brand > 0)
			$sEditSeasonsList = $sSeasonsList;

		else
		{
			$iParent          = getDbValue("parent_id", "tbl_brands", "id='$iBrand'");
			$sEditSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iParent' AND parent_id>'0'");
		}

		foreach ($sEditSeasonsList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $iSeason) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Style No</td>
						  <td align="center">:</td>

						  <td>
						    <select name="StyleNo" id="StyleNo<?= $iId ?>">
							  <option value=""></option>
<?
		$sSQL = "SELECT id, style FROM tbl_styles WHERE sub_brand_id='$iBrand' AND sub_season_id='$iSeason' ORDER BY style";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$sKey   = $objDb2->getField($j, 'id');
			$sValue = $objDb2->getField($j, 'style');
?>
	  	        			  <option value="<?= $sKey ?>"<?= (($sKey == $iStyleId) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Quantity<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Quantity" value="<?= $iQuantity ?>" size="18" maxlength="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td></td>
						  <td></td>

						  <td>
						    <input type="submit" id="BtnSave<?= $iId ?>" value="SAVE" class="btnSmall" onclick="return validateEditForm(<?= $iId ?>);" />
						    <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $iId ?>');" />
						  </td>
					    </tr>
					  </table>
					  </form>

				    </div>
				  </div>

				  <div id="Msg<?= $iId ?>" class="msgOk" style="display:none;"></div>

<?
	}

	if ($iCount == 0)
	{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr>
				      <td class="noRecord">No Revised Forecast Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Month={$Month}&Year={$Year}&Region={$Region}&Category={$Category}&Vendor={$Vendor}&Brand={$Brand}");

	$sSQL = "SELECT SUM(quantity) FROM tbl_revised_forecasts $sConditions";
	$objDb->query($sSQL);
?>

				<div class="buttonsBar" style="margin-top:4px;">
				  <span style="float:right; color:#ffffff; font-weight:bold; font-size:12px; padding:8px 10px 0px 0px;">Revised Forecast Quantity: <?= formatNumber($objDb->getField(0, 0), false) ?></span>
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
	$objDbGlobal->close( );

	@ob_end_flush( );
?>