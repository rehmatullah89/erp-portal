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
	$Vendor   = IO::intValue("Vendor");
	$Brand    = IO::intValue("Brand");
	$Category = IO::strValue("Category");
	$PostId   = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Manager    = IO::intValue("Manager");
		$Vendors    = IO::getArray("Vendors");
		$Brands     = IO::getArray("Brands");
		$Categories = IO::getArray("Categories");
	}

	$sManagersList   = getList("tbl_users", "id", "name", "`status`='A' AND user_type='MATRIX'");//id IN (13,33,39,56,84,233,313)
	$sBrandsList     = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sVendorsList    = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND id IN ({$_SESSION['Vendors']})");
	$sCategoriesList = getList("tbl_style_categories", "id", "category", "FIND_IN_SET(id, '{$_SESSION['StyleCategories']}')");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/etd-managers.js"></script>
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
			    <h1>ETD Managers</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-etd-manager.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add ETD Manager</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
				    <td width="80">Manager<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>

				    <td>
					  <select id="Manager" name="Manager">
					    <option value=""></option>
<?
		foreach ($sManagersList as $sKey => $sValue)
		{
?>
	            	    <option value="<?= $sKey ?>"<?= (($sKey == $Manager) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
				    </td>
				  </tr>

				  <tr valign="top">
					<td>Vendors<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Vendors[]" id="Vendors" multiple size="8" style="min-width:200px;">
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Vendors)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Brands<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Brands[]" id="Brands" multiple size="8" style="min-width:200px;">
<?
		foreach ($sBrandsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Brands)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Categories<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Categories[]" id="Categories" multiple size="8" style="min-width:200px;">
<?
		foreach ($sCategoriesList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Categories)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
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
			          <td width="55">Vendor</td>

			          <td width="180">
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
			          </td>

			          <td width="45">Brand</td>

			          <td width="180">
			            <select name="Brand" id="Brand">
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

			          <td width="65">Category</td>

			          <td width="180">
			            <select name="Category" id="Category">
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

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
<?
	$sClass      = array("evenRow", "oddRow");
	$sColor      = array(EVEN_ROW_COLOR, ODD_ROW_COLOR);
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Vendor > 0)
		$sConditions .= " AND FIND_IN_SET('Vendor', vendors) ";

	if ($Brand > 0)
		$sConditions .= " AND FIND_IN_SET('Brand', brands) ";

	if ($Category > 0)
		$sConditions .= " AND FIND_IN_SET('Category', categories) ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_etd_managers", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT user_id, vendors, brands, categories FROM tbl_etd_managers $sConditions ORDER BY user_id LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="5%">#</td>
				      <td width="25%">Manager</td>
				      <td width="20%">Vendors</td>
				      <td width="20%">Brands</td>
				      <td width="20%">Categories</td>
				      <td width="10%" class="center">Options</td>
				    </tr>
			      </table>
<?
		}

		$iId         = $objDb->getField($i, 'user_id');
		$sVendors    = $objDb->getField($i, 'vendors');
		$sBrands     = $objDb->getField($i, 'brands');
		$sCategories = $objDb->getField($i, 'categories');

		$iVendors = @explode(",", $sVendors);
		$sVendors = "";

		foreach ($iVendors as $iVendor)
			$sVendors .= ($sVendorsList[$iVendor]."<br />");


		$iBrands = @explode(",", $sBrands);
		$sBrands = "";

		foreach ($iBrands as $iBrand)
			$sBrands .= ($sBrandsList[$iBrand]."<br />");


		$iCategories = @explode(",", $sCategories);
		$sCategories = "";

		foreach ($iCategories as $iCategory)
			$sCategories .= ($sCategoriesList[$iCategory]."<br />");
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td width="5%"><?= ($iStart + $i + 1) ?></td>
				      <td width="25%"><?= $sManagersList[$iId] ?></td>
				      <td width="20%"><span id="Vendors_<?= $iId ?>"><?= $sVendors ?></span></td>
				      <td width="20%"><span id="Brands_<?= $iId ?>"><?= $sBrands ?></span></td>
				      <td width="20%"><span id="Categories_<?= $iId ?>"><?= $sCategories ?></span></td>

				      <td width="10%" class="center">
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
				        <a href="data/delete-etd-manager.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this ETD Manager?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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
						  <td width="80">ETD Manager</td>
						  <td width="20" align="center">:</td>
						  <td><b><?= $sManagersList[$iId] ?></b></td>
						</tr>

					    <tr valign="top">
						  <td>Vendors</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Vendors" id="Vendors<?= $iId ?>" multiple size="8" style="min-width:200px;">
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $iVendors)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr valign="top">
						  <td>Brands</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Brands" id="Brands<?= $iId ?>" multiple size="8" style="min-width:200px;">
<?
		foreach ($sBrandsList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $iBrands)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr valign="top">
						  <td>Categories</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Categories" id="Categories<?= $iId ?>" multiple size="8" style="min-width:200px;">
<?
		foreach ($sCategoriesList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $iCategories)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
					      <td></td>
					      <td></td>

						  <td>
						    <input type="submit" value="SAVE" class="btnSmall" onclick="validateEditForm(<?= $iId ?>);" />
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
				      <td class="noRecord">No ETD Manager Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Vendor={$Vendor}&Brand={$Brand}&Category={$Category}");
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