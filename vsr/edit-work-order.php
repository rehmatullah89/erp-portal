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

	$Id   = IO::intValue('Id');
	$Step = IO::intValue("Step");

	$sSQL = "SELECT * FROM tbl_vsr2 WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$WorkOrder = $objDb->getField(0, "work_order_no");
		$Vendor    = $objDb->getField(0, "vendor_id");
		$Brand     = $objDb->getField(0, "brand_id");
		$Season    = $objDb->getField(0, "season_id");
		$Pos       = $objDb->getField(0, "pos");
		$Styles    = $objDb->getField(0, "styles");
		$Colors    = $objDb->getField(0, "colors");

		$Pos    = @explode(",", $Pos);
		$Styles = @explode(",", $Styles);
		$Colors = @explode(",", $Colors);
	}

	else
		redirect(SITE_URL, "ERROR");


	if ($_POST)
	{
		$WorkOrder = IO::strValue('WorkOrder');
		$Pos       = IO::getArray("Pos");
		$Styles    = IO::getArray("Styles");
	}

	$iCategory = getDbValue("category_id", "tbl_styles", "id='{$Styles[0]}'");
	$sStages   = getDbValue("stages", "tbl_style_categories", "id='$iCategory'");
	$iStages   = @explode(",", $sStages);

	$sStagesList = getList("tbl_production_stages", "id", "title", "", "position");
	$sStagesType = getList("tbl_production_stages", "id", "type", "", "position");


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
  <script type="text/javascript" src="scripts/vsr/edit-work-order.js"></script>
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
			    <h1><img src="images/h1/vsr/work-orders.jpg" width="189" height="20" vspace="10" alt="" title="" /></h1>

<?
	if ($Step == 0)
	{
		$iParentBrand = getDbValue("parent_id", "tbl_brands", "id='$Brand'");

		$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
		$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
		$sSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iParentBrand' AND parent_id>'0'");
		$sPosList     = getList("tbl_po po, tbl_po_colors pc", "po.id", "CONCAT(po.order_no, ' ', po.order_status)", "po.id=pc.po_id AND po.vendor_id='$Vendor' AND po.brand_id='$Brand' AND po.status!='C' AND po.order_nature='B' AND po.accepted='Y' AND pc.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND sub_season_id='$Season' AND category_id='$iCategory')");
		$sStylesList  = getList("tbl_styles", "id", "style", "sub_brand_id='$Brand' AND sub_season_id='$Season' AND category_id='$iCategory'");

		$sPos    = @implode(",", $Pos);
		$sStyles = @implode(",", $Styles);
?>
			    <form name="frmData" id="frmData" method="post" action="<?= $_SERVER['PHP_SELF'] ?>" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="Vendor" value="<?= $Vendor ?>" />
			    <input type="hidden" name="Brand" value="<?= $Brand ?>" />
			    <input type="hidden" name="Season" value="<?= $Season ?>" />
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Step" value="1" />

				<h2>Edit Work Order</h2>

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

			      <div class="buttonsBar">
			        <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm1( );" />
			        <input type="button" value="" class="btnBack" title="Back" onclick="document.location='vsr/work-orders.php';" />
			      </div>
			    </form>
<?
	}




	else if ($Step == 1)
	{
		$iParentBrand = getDbValue("parent_id", "tbl_brands", "id='$Brand'");

		$sVendorsList      = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
		$sBrandsList       = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
		$sSeasonsList      = getList("tbl_seasons", "id", "season", "brand_id='$iParentBrand' AND parent_id>'0'");
		$sPosList          = getList("tbl_po po, tbl_po_colors pc", "po.id", "CONCAT(po.order_no, ' ', po.order_status)", "po.id=pc.po_id AND po.vendor_id='$Vendor' AND po.brand_id='$Brand' AND po.status!='C' AND po.accepted='Y' AND pc.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND sub_season_id='$Season' AND category_id='$iCategory')");
		$sStylesList       = getList("tbl_styles", "id", "style", "sub_brand_id='$Brand' AND sub_season_id='$Season' AND category_id='$iCategory'");
		$sDestinationsList = getList("tbl_destinations", "id", "destination", "brand_id='$iParentBrand'");


		$sPos    = @implode(",", $Pos);
		$sStyles = @implode(",", $Styles);
?>
			    <form name="frmData" id="frmData" method="post" action="vsr/update-work-order.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Step" value="<?= $Step ?>" />
			    <input type="hidden" name="Vendor" value="<?= $Vendor ?>" />
			    <input type="hidden" name="Brand" value="<?= $Brand ?>" />
			    <input type="hidden" name="Season" value="<?= $Season ?>" />
			    <input type="hidden" name="WorkOrder" value="<?= $WorkOrder ?>" />
			    <input type="hidden" name="Pos" value="<?= $sPos ?>" />
			    <input type="hidden" name="Styles" value="<?= $sStyles ?>" />

			    <h2>Edit Work Order</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
				    <td width="70">Vendor</td>
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
					<td><?= $WorkOrder ?></td>
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
				 WHERE pc.id=pq.color_id AND pc.po_id=pq.po_id AND ((FIND_IN_SET(pc.po_id, '$sPos') AND FIND_IN_SET(pc.style_id, '$sStyles'))) AND s.id=pq.size_id
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
		$sSQL = "SELECT id, po_id, color, vsr_price, style_id, destination_id, {$sPrefix}etd_required FROM tbl_po_colors WHERE (FIND_IN_SET(po_id, '$sPos') AND FIND_IN_SET(style_id, '$sStyles')) ORDER BY id";
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

				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm2( );" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='vsr/edit-work-order.php?Id=<?= $Id ?>&Step=0';" />
				</div>
				</form>
<?
	}


	else if ($Step > 1)
	{
		$sColors = @implode(",", $Colors);

		$iParentBrand = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$sEtdRequired = getDbValue("MIN({$sPrefix}etd_required)", "tbl_po_colors", "FIND_IN_SET(id, '$sColors')");
		$iQuantity    = getDbValue("SUM(quantity)", "tbl_po_quantities", "FIND_IN_SET(color_id, '$sColors')");

		$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
		$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
		$sSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iParentBrand' AND parent_id>'0'");
		$sPosList     = getList("tbl_po po, tbl_po_colors pc", "po.id", "CONCAT(po.order_no, ' ', po.order_status)", "po.id=pc.po_id AND po.vendor_id='$Vendor' AND po.brand_id='$Brand' AND po.status!='C' AND po.order_nature='B' AND po.accepted='Y' AND pc.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND sub_season_id='$Season' AND category_id='$iCategory')");
		$sStylesList  = getList("tbl_styles", "id", "style", "sub_brand_id='$Brand' AND sub_season_id='$Season' AND category_id='$iCategory'");


		$sPos    = "";
		$sStyles = "";

		for ($i = 0; $i < count($Pos); $i ++)
			$sPos .= ((($i > 0) ? ", " : "").trim($sPosList[$Pos[$i]]));

		for ($i = 0; $i < count($Styles); $i ++)
			$sStyles .= ((($i > 0) ? ", " : "").$sStylesList[$Styles[$i]]);
?>
			    <form name="frmData" id="frmData" method="post" action="vsr/update-work-order.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Step" value="<?= $Step ?>" />


			    <h2>Work Order Details</h2>

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
					<td><?= $WorkOrder ?></td>
				  </tr>

				  <tr>
					<td>POs</td>
					<td align="center">:</td>
					<td><?= $sPos ?></td>
				  </tr>

				  <tr>
					<td>Styles</td>
					<td align="center">:</td>
					<td><?= $sStyles ?></td>
				  </tr>

				  <tr>
					<td>ETD Required</td>
					<td align="center">:</td>
					<td><?= formatDate($sEtdRequired) ?></td>
				  </tr>

				  <tr>
					<td>Quantity</td>
					<td align="center">:</td>
					<td><?= formatNumber($iQuantity, false) ?></td>
				  </tr>
				</table>

				<br />
<?
		$sSQL = "SELECT stage_id, start_date, end_date, completed FROM tbl_vsr_data WHERE work_order_id='$Id' AND color_id='{$Colors[0]}' ORDER BY color_id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sVsr   = array( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iStage     = $objDb->getField($i, "stage_id");
			$sStartDate = $objDb->getField($i, "start_date");
			$sEndDate   = $objDb->getField($i, "end_date");
			$iCompleted = $objDb->getField($i, "completed");

			$sVsr[$iStage] = array($sStartDate, $sEndDate, $iCompleted);
		}


		foreach ($sStagesList as $iStage => $sStage)
		{
			if (!@in_array($iStage, $iStages))
				continue;


			$sStartDate = "";
			$sEndDate   = "";
			$iCompleted = "";

			if (@is_array($sVsr[$iStage]))
			{
				$sStartDate = $sVsr[$iStage][0];
				$sEndDate   = $sVsr[$iStage][1];
				$iCompleted = $sVsr[$iStage][2];
			}
?>
				<h2 style="margin-bottom:0px;"><?= $sStage ?></h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="70">Start Date</td>
					<td width="20" align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="StartDate_<?= $iStage ?>" id="StartDate_<?= $iStage ?>" value="<?= (($sStartDate == '0000-00-00') ? '' : $sStartDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('StartDate_<?= $iStage ?>'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('StartDate_<?= $iStage ?>'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>

				  <tr>
					<td>End Date</td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="EndDate_<?= $iStage ?>" id="EndDate_<?= $iStage ?>" value="<?= (($sEndDate == '0000-00-00') ? '' : $sEndDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('EndDate_<?= $iStage ?>'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('EndDate_<?= $iStage ?>'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>

				  <tr>
					<td>Completed</td>
					<td align="center">:</td>
					<td><input type="text" name="Completed_<?= $iStage ?>" value="<?= $iCompleted ?>" size="8" maxlength="8" class="textbox" /> <?= (($sStagesType[$iStage] == "P") ? "%" : "Pcs") ?></td>
				  </tr>
				</table>
<?
		}
?>

				<h2 style="margin-bottom:0px;">Final Audit</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="70">Audit Date</td>
					<td width="20" align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
						  <td width="82"><input type="text" name="FinalAudit" id="FinalAudit" value="<?= getDbValue("MIN(final_date)", "tbl_vsr_details", "work_order_id='$Id'") ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FinalAudit'), 'yyyy-mm-dd', this);" /></td>
						  <td width="44"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FinalAudit'), 'yyyy-mm-dd', this);" /></td>
						  <td>[ <a href="#" onclick="$('FinalAudit').value=''; return false;">Clear</a> ]</td>
						</tr>
					  </table>

					</td>
				  </tr>
				</table>


				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='vsr/edit-work-order.php?Id=<?= $Id ?>&Step=1';" />
				</div>
				</form>
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
	$objDbGlobal->close( );

	@ob_end_flush( );
?>
