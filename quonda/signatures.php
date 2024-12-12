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

	$PageId    = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Type      = IO::strValue("Type");
	$Signature = IO::strValue("Signature");
	$Vendor    = IO::intValue("Vendor");
	$Brand     = IO::intValue("Brand");
	$PostId    = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Type   = IO::strValue("Type");
		$Name   = IO::strValue("Name");
		$Vendor = IO::intValue("Vendor");
		$Brands = IO::getArray("Brand");
	}

	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/signatures.js"></script>
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
			    <h1>signatures</h1>
<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="quonda/save-signature.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="MAX_FILE_SIZE" value="104857600" />

				<h2>Add Signature</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="90">Person Name</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Name" value="<?= $Name ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Type</td>
					<td align="center">:</td>

					<td>
					  <select id="Type" name="Type" style="width:230px;">
			            <option value="F"<?= (($Type == "F") ? " selected" : "") ?>>Factory Representative</option>
			            <option value="M"<?= (($Type == "M") ? " selected" : "") ?>>Merchandiser</option>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Vendor(s)</td>
					<td align="center">:</td>

					<td>
					  <select name="Vendors[]" multiple size="10" style="width:230px;">
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
					<td>Brand(s)</td>
					<td align="center">:</td>

					<td>
					  <select name="Brands[]" multiple size="10" style="width:230px;">
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

				  <tr>
					<td>Signature</td>
					<td align="center">:</td>
					<td><input type="file" name="Signature" value="" size="30" class="file" /> (Recommended Size: 300 x 150)</td>
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
			          <td width="70">Keywords</td>
			          <td width="130"><input type="text" name="Signature" value="<?= $Signature ?>" class="textbox" size="15" maxlength="50" /></td>

			          <td width="120">
					    <select id="Type" name="Type">
			              <option value="">All Types</option>
			              <option value="F"<?= (($Type == "F") ? " selected" : "") ?>>Factory</option>
			              <option value="M"<?= (($Type == "M") ? " selected" : "") ?>>Merchandiser</option>
					    </select>
			          </td>

			          <td width="55">Brand</td>

			          <td width="180">
					    <select name="Brand" id="Brands" onchange="getListValues('Brands', 'Vendors', 'BrandVendors');">
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

			          <td width="52">Vendor</td>

			          <td width="180">
					    <select name="Vendor" id="Vendors">
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

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Signature != "")
		$sConditions .= " AND name LIKE '%$Signature%' ";

	if ($Type != "")
		$sConditions .= " AND type='$Type' ";

	if ($Brand > 0)
		$sConditions .= " AND FIND_IN_SET('$Brand', brands) ";

	if ($Vendor > 0)
		$sConditions .= " AND FIND_IN_SET('$Vendor', vendors) ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_signatures", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_signatures $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="8%">#</td>
				      <td width="20%">Person</td>
				      <td width="16%">Type</td>
				      <td width="22%">Brands</td>
				      <td width="24%">Vendors</td>
				      <td width="10%" class="center">Options</td>
				    </tr>
<?
		}


		$iId        = $objDb->getField($i, 'id');
		$sName      = $objDb->getField($i, 'name');
		$sType      = $objDb->getField($i, 'type');
		$sBrands    = $objDb->getField($i, 'brands');
		$sVendors   = $objDb->getField($i, 'vendors');
		$sSignature = $objDb->getField($i, 'signature');

		$iBrands = @explode(",", $sBrands);
		$sBrands = "";

		foreach ($iBrands as $iBrand)
			$sBrands .= ($sBrandsList[$iBrand]."<br />");


		$iVendors = @explode(",", $sVendors);
		$sVendors = "";

		foreach ($iVendors as $iVendor)
			$sVendors .= ($sVendorsList[$iVendor]."<br />");
?>

				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= $sName ?></td>
				      <td><?= (($sType == "F") ? "Factory" : "Merchandiser") ?></td>
				      <td><?= $sBrands ?></td>
				      <td><?= $sVendors ?></td>

				      <td class="center">
<?
		if ($sSignature != "" && @file_exists($sBaseDir.SIGNATURES_IMG_DIR.$sSignature))
		{
?>
				        <a href="<?= SIGNATURES_IMG_DIR.$sSignature ?>" class="lightview"><img src="images/icons/thumb.gif" width="16" height="16" hspace="2" alt="Signature" title="Signature" /></a>
<?
		}

		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="quonda/edit-signature.php?Id=<?= $iId ?>"><img src="images/icons/edit.gif" width="16" height="16" hspace="2" alt="Edit" title="Edit" /></a>
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="quonda/delete-signature.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Signature?');"><img src="images/icons/delete.gif" width="16" height="16" hspace="2" alt="Delete" title="Delete" /></a>
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
				      <td class="noRecord">No Signature Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Signature={$Signature}&Brand={$Brand}&Vendor={$Vendor}&Type={$Type}");
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