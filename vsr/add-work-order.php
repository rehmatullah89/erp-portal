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

	$PostId = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$FinalAudit  = IO::strValue("FinalAudit");
		$VslDate     = IO::strValue("VslDate");
		$PoIssueDate = IO::strValue("PoIssueDate");
		$PoRef       = IO::strValue("PoRef");
		$Fabric      = IO::strValue("Fabric");
		$Notes       = IO::strValue("Notes");
		$Colors      = IO::getArray("Color");
		$Pos         = @explode(",", IO::strValue("Pos"));
		$Styles      = @explode(",", IO::strValue("Styles"));
	}

	else
	{
		$Pos    = IO::getArray("Pos");
		$Styles = IO::getArray("Styles");
	}


	$Vendor    = IO::intValue("Vendor");
	$Brand     = IO::intValue("Brand");
	$Season    = IO::intValue("Season");
	$FromDate  = IO::strValue("FromDate");
	$ToDate    = IO::strValue("ToDate");
	$WorkOrder = IO::strValue("WorkOrder");
	$Category  = IO::intValue("Category");

	$sVendorsList    = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList     = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
	$sCategoriesList = getList("tbl_style_categories", "id", "category", "FIND_IN_SET(id, '{$_SESSION['StyleCategories']}')");
	$sSeasonsList    = array( );


	$sPrefix = "";

	if (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@3-tree.com") === FALSE)
		$sPrefix = "vsr_";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/vsr/add-work-order.js"></script>
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
			    <h1>Add Work Order Details</h1>

<?
	if (!$_POST)
	{
		if ($Vendor == 0 && $Brand == 0 && $Season == 0 && $Category == 0)
		{
			$Vendor   = $_SESSION["ADD_WO_VENDOR"];
			$Brand    = $_SESSION["ADD_WO_BRAND"];
			$Season   = $_SESSION["ADD_WO_SEASON"];
			$Category = $_SESSION["ADD_WO_CATEGORY"];
			$FromDate = $_SESSION["ADD_WO_FROMDATE"];
			$ToDate   = $_SESSION["ADD_WO_TODATE"];
		}
?>
			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="65">Vendor*</td>

			          <td width="180">
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

			          <td width="55">Brand*</td>

			          <td width="180">
			            <select name="Brand" id="Brand" onchange="getListValues('Brand', 'Season', 'BrandSeasons');">
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

			          <td width="65">Season*</td>

			          <td width="180">
			            <select name="Season" id="Season">
			              <option value=""></option>
<?
		if ($Brand > 0)
		{
			$iParentBrand = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
			$sSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iParentBrand' AND parent_id>'0'");

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

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
			          <td width="75">Category*</td>

			          <td width="130">
			            <select name="Category" id="Category">
			              <option value=""></option>
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


					  <td width="70">ETD From</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center">To</td>
					  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td></td>
				    </tr>
				  </table>
			    </div>
			    </form>
<?
	}




	if ($Vendor > 0 && $Brand > 0 && $Season > 0 && $Category > 0)
	{
		$_SESSION["ADD_WO_VENDOR"]   = $Vendor;
		$_SESSION["ADD_WO_BRAND"]    = $Brand;
		$_SESSION["ADD_WO_SEASON"]   = $Season;
		$_SESSION["ADD_WO_CATEGORY"] = $Category;
		$_SESSION["ADD_WO_FROMDATE"] = $FromDate;
		$_SESSION["ADD_WO_TODATE"]   = $ToDate;


		if (!$_POST)
		{
?>
			    <hr />
<?
		}


		$sEtdSQL      = "";
		$iParentBrand = getDbValue("parent_id", "tbl_brands", "id='$Brand'");

		if ($FromDate != "" && $ToDate != "")
			$sEtdSQL = " AND (pc.{$sPrefix}etd_required BETWEEN '$FromDate' AND '$ToDate') ";

		$sPosList          = getList("tbl_po po, tbl_po_colors pc", "po.id", "CONCAT(po.order_no, ' ', po.order_status)", "po.id=pc.po_id AND po.vendor_id='$Vendor' AND po.brand_id='$Brand' AND po.status!='C' AND po.order_nature='B' AND po.accepted='Y' AND pc.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND sub_season_id='$Season') $sEtdSQL");
		$sStylesList       = getList("tbl_styles", "id", "style", "sub_brand_id='$Brand' AND sub_season_id='$Season' AND category_id='$Category'");
		$sDestinationsList = getList("tbl_destinations", "id", "destination", "brand_id='$iParentBrand'");
		$sSeasonsList      = getList("tbl_seasons", "id", "season", "brand_id='$iParentBrand' AND parent_id>'0'");


		if ($_POST)
		{
			$sPos    = @implode(",", $Pos);
			$sStyles = @implode(",", $Styles);
?>
			    <form name="frmData" id="frmData" method="post" action="vsr/save-work-order.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="Vendor" value="<?= $Vendor ?>" />
			    <input type="hidden" name="Brand" value="<?= $Brand ?>" />
			    <input type="hidden" name="Season" value="<?= $Season ?>" />
			    <input type="hidden" name="Pos" value="<?= $sPos ?>" />
			    <input type="hidden" name="Styles" value="<?= $sStyles ?>" />
			    <input type="hidden" name="Category" value="<?= $Category ?>" />

				<h2>Add Work Order - Step 2</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
				    <td width="85">Vendor</td>
				    <td width="20" align="center">:</td>
				    <td><?= $sVendorsList[$Vendor] ?></td>
				  </tr>

				  <tr>
				    <td>Brand</td>
				    <td align="center">:</td>
				    <td><?= $sBrandsList[$Brand] ?></td>
				  </tr>

				  <tr>
				    <td>Season</td>
				    <td align="center">:</td>
				    <td><?= $sSeasonsList[$Season] ?></td>
				  </tr>

				  <tr>
					<td>Work Order</td>
					<td align="center">:</td>
					<td><input type="text" name="WorkOrder" value="<?= $WorkOrder ?>" size="20" maxlength="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>PO Ref</td>
					<td align="center">:</td>
					<td><input type="text" name="PoRef" value="<?= $PoRef ?>" size="20" maxlength="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Fabric</td>
					<td align="center">:</td>
					<td><input type="text" name="Fabric" value="<?= $Fabric ?>" size="20" maxlength="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>VSL Date</td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="VslDate" id="VslDate" value="<?= $VslDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('VslDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('VslDate'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>

				  <tr>
					<td>Po Issue Date</td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="PoIssueDate" id="PoIssueDate" value="<?= $PoIssueDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('PoIssueDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('PoIssueDate'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>

				  <tr>
					<td>Final Audit</td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="FinalAudit" id="FinalAudit" value="<?= $FinalAudit ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FinalAudit'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FinalAudit'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>

				  <tr valign="top">
					<td>Notes</td>
					<td align="center">:</td>
					<td><textarea name="Notes" rows="3" cols="50" style="width:98%;"><?= $Notes ?></textarea></td>
				  </tr>
				</table>

				<br />
				<h2 style="margin-bottom:0px;">Work Order Details</h2>

			    <div style="overflow:auto;">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			        <tr class="sdRowHeader">
			 	      <td width="25" align="center"><input type="checkbox" id="All" onclick="checkAll( );" /></td>
				      <td width="140"><b>PO</b></td>
				      <td width="140"><b>Style</b></td>
			 	      <td width="250"><b>Color</b></td>
				      <td width="80" align="center"><b>Price ($)</b></td>
				      <td width="110" align="center"><b>ETD Required</b></td>
				      <td width="180"><b>Destination</b></td>
<?
			$sSQL = "SELECT DISTINCT(pq.size_id), s.size
			         FROM tbl_po_colors pc, tbl_po_quantities pq, tbl_sizes s
			         WHERE pc.id=pq.color_id AND pc.po_id=pq.po_id AND (FIND_IN_SET(pc.po_id, '$sPos') AND FIND_IN_SET(pc.style_id, '$sStyles')) AND s.id=pq.size_id
			         ORDER BY s.position";
			$objDb->query($sSQL);

			$iCount     = $objDb->getCount( );
			$sSizesList =  array( );

			for ($i = 0; $i < $iCount; $i ++)
				$sSizesList[$objDb->getField($i, 0)] = $objDb->getField($i, 1);


			foreach ($sSizesList as $iSize => $sSize)
			{
?>
		    	    <td align="center" width="50"><b><?= $sSize ?></b></td>
<?
			}
?>
			        </tr>
<?
			$sSQL = "SELECT id, po_id, color, vsr_price, style_id, destination_id, {$sPrefix}etd_required
			         FROM tbl_po_colors
			         WHERE (FIND_IN_SET(po_id, '$sPos') AND FIND_IN_SET(style_id, '$sStyles'))
			         ORDER BY id";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );


			if (count($sPosList) == 0)
				$iCount = 0;


			for ($i = 0; $i < $iCount; $i ++)
			{
				$iColor       = $objDb->getField($i, 'id');
				$iPo          = $objDb->getField($i, 'po_id');
				$sColor       = $objDb->getField($i, 'color');
				$fPrice       = $objDb->getField($i, 'vsr_price');
				$iStyle       = $objDb->getField($i, 'style_id');
				$iDestination = $objDb->getField($i, 'destination_id');
				$sEtdRequired = $objDb->getField($i, "{$sPrefix}etd_required");


				if ($sPosList[$iPo] == "")
				{
					$sPo = getDbValue("CONCAT(order_no, ' ', order_status)", "tbl_po", "id='$iPo' AND po.accepted='Y'");

					if ($sPo == "")
						continue;

					$sPosList[$iPo] = $sPo;
				}
?>
					<tr class="sdRowColor" valign="top">
					  <td align="center"><input type="checkbox" class="poColor" name="Color[]" value="<?= $iColor ?>" <?= ((@in_array($iColor, $Colors)) ? "checked" : "") ?> onclick="reCheckSelection( );" /></td>
					  <td><?= $sPosList[$iPo] ?></td>
					  <td><?= $sStylesList[$iStyle] ?></td>
					  <td><?= $sColor ?></td>
					  <td align="center"><?= formatNumber($fPrice) ?></td>
					  <td align="center"><?= formatDate($sEtdRequired) ?></td>
					  <td><?= $sDestinationsList[$iDestination] ?></td>
<?
				foreach ($sSizesList as $iSize => $sSize)
				{
					$iQuantity = getDbValue("quantity", "tbl_po_quantities", "po_id='$iPo' AND color_id='$iColor' AND size_id='$iSize'");
?>
		    	  	  <td align="center"><?= (float)$iQuantity ?></td>
<?
				}
?>
					</tr>
<?
			}
?>
				  </table>
				</div>

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm2( );" /></div>
				</form>
<?
		}


		else
		{
?>
			    <form name="frmData" id="frmData" method="post" action="<?= $_SERVER['PHP_SELF'] ?>" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="Vendor" value="<?= $Vendor ?>" />
			    <input type="hidden" name="Brand" value="<?= $Brand ?>" />
			    <input type="hidden" name="Season" value="<?= $Season ?>" />
			    <input type="hidden" name="Category" value="<?= $Category ?>" />

				<h2>Add Work Order - Step 1</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
				    <td width="75">Vendor</td>
				    <td width="20" align="center">:</td>
				    <td><?= $sVendorsList[$Vendor] ?></td>
				  </tr>

				  <tr>
				    <td>Brand</td>
				    <td align="center">:</td>
				    <td><?= $sBrandsList[$Brand] ?></td>
				  </tr>

				  <tr>
				    <td>Season</td>
				    <td align="center">:</td>
				    <td><?= $sSeasonsList[$Season] ?></td>
				  </tr>

				  <tr>
					<td>Work Order</td>
					<td align="center">:</td>
					<td><input type="text" name="WorkOrder" value="<?= $WorkOrder ?>" size="20" maxlength="25" class="textbox" /></td>
				  </tr>
				</table>


			    <div style="padding:8px 0px 12px 5px;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr valign="top">
			          <td width="24%">
			            <div class="multiSelect">
			              <label class="title"><b>POs</b> <span>(<a href="#" onclick="selectAll('po'); return false;">All</a> | <a href="#" onclick="clearAll('po'); return false;">None</a>)</span></label>

			              <div style="height:200px;">
			                <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
			foreach ($sPosList as $sKey => $sValue)
			{
?>
			                  <tr>
			                    <td width="25"><input type="checkbox" class="po" name="Pos[]" id="Po<?= $sKey ?>" value="<?= $sKey ?>" <?= ((@in_array($sKey, $Pos)) ? "checked" : "") ?> /></td>
			                    <td><label for="Po<?= $sKey ?>"><?= $sValue ?></label></td>
			                  </tr>
<?
			}
?>
			                </table>
			              </div>
			            </div>
			          </td>

			          <td width="2%"></td>

			          <td width="24%">
			            <div class="multiSelect">
			              <label class="title"><b>Styles</b> <span>(<a href="#" onclick="selectAll('style'); return false;">All</a> | <a href="#" onclick="clearAll('style'); return false;">None</a>)</span></label>

			              <div style="height:200px;">
			                <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
			foreach ($sStylesList as $sKey => $sValue)
			{
?>
			                  <tr>
			                    <td width="25"><input type="checkbox" class="style" name="Styles[]" id="Style<?= $sKey ?>" value="<?= $sKey ?>" <?= ((@in_array($sKey, $Styles)) ? "checked" : "") ?> /></td>
			                    <td><label for="Style<?= $sKey ?>"><?= $sValue ?></label></td>
			                  </tr>
<?
			}
?>
			                </table>
			              </div>
			            </div>
			          </td>

			          <td width="50%"></td>
			        </tr>
			      </table>
			      </div>

			      <div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm1( );" /></div>
			    </form>
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
