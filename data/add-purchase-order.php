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

	if ($sUserRights['Add'] != "Y" || (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@3-tree.com") === FALSE && 
	                                   @strpos($_SESSION["Email"], "@selimpex.com") === FALSE && @strpos($_SESSION["Email"], "@global-exports.com") === FALSE))
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$PostId = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Vendor              = IO::strValue("Vendor");
		$Brand               = IO::strValue("Brand");
		$OrderNo             = IO::strValue("OrderNo");
		$OrderStatus         = IO::strValue("OrderStatus");
		$OrderNature         = IO::strValue("OrderNature");
		$OrderType           = IO::strValue("OrderType");
		$StyleNo             = IO::strValue("StyleNo");
		$AdditionalStyles    = IO::getArray('AdditionalStyles');
		$ArticleNo           = IO::strValue("ArticleNo");
		$Category            = IO::strValue("Category");
		$Customer            = IO::strValue("Customer");
		$CustomerPoNo        = IO::strValue("CustomerPoNo");
		$CustomerShip        = IO::strValue("CustomerShip");
		$CallNo              = IO::strValue("CallNo");
		$Price               = IO::strValue("Price");
		$VasAdjustment       = IO::strValue("VasAdjustment");
		$Currency            = IO::strValue("Currency");
		$TermsOfDelivery     = IO::strValue("TermsOfDelivery");
		$PlaceOfDeparture    = IO::strValue("PlaceOfDeparture");
		$WayOfDispatch       = IO::strValue("WayOfDispatch");
		$TermsOfPayment      = IO::strValue("TermsOfPayment");
		$Destination         = IO::strValue("Destination");
		$DateOfShipment      = IO::strValue("DateOfShipment");
		$SampleSize          = IO::strValue("SampleSize");
		$LabDips             = IO::strValue("LabDips");
		$PhotoSample         = IO::strValue("PhotoSample");
		$PreProductionSample = IO::strValue("PreProductionSample");
		$Note                = IO::strValue("Note");
		$Sizes               = IO::getArray('Sizes');
	}

	$sCategoriesList = getList("tbl_categories", "id", "category");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/add-purchase-order.js"></script>
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
			    <h1>PO Entry Form</h1>

			    <form name="frmData" id="frmData" method="post" action="data/save-purchase-order.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Basic Purchase Order Info</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="140">Vendor<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Vendor" id="Vendor">
						<option value=""></option>
<?
	$sSQL = "SELECT id, vendor FROM tbl_vendors WHERE id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y' ORDER BY vendor";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	  	        		<option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Brand<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Brand" id="Brand" onchange="getStylesList('Brand', 'StyleNo', 'AdditionalStyles'); getListValues('Brand', 'Customer', 'BrandCustomers'); checkPoType( );">
						<option value=""></option>
<?
	$sSQL = "SELECT id, brand FROM tbl_brands WHERE id IN ({$_SESSION['Brands']}) ORDER BY brand";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	  	        		<option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Order No<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <input type="text" name="OrderNo" id="OrderNo" value="<?= $OrderNo ?>" maxlength="20" class="textbox" />

					  <select name="OrderStatus" id="OrderStatus">
						<option value=""></option>
						<option value="B">B</option>
						<option value="B *">B *</option>
						<option value="N">N</option>
						<option value="N *">N *</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.OrderStatus.value = "<?= $OrderStatus ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Order Nature<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="OrderNature" id="OrderNature">
						<option value="B"<?= (($OrderNature == "B" || $OrderNature == "") ? " selected" : "") ?>>Bulk</option>
						<option value="S"<?= (($OrderNature == "S") ? " selected" : "") ?>>SMS</option>
					  </select>
					</td>
				  </tr>
				</table>

				<div id="PoType" style="display:<?= (($Brand == 32) ? 'block' : 'none') ?>;">
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="140">PO Type<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="OrderType" id="OrderType">
	  	        		<option value="Non-SDP"<?= (($OrderType == "Non-SDP" || $OrderType == "") ? " selected" : "") ?>>Non-SDP</option>
	  	        		<option value="SDP"<?= (($OrderType == "SDP") ? " selected" : "") ?>>SDP</option>
					  </select>
					</td>
				  </tr>
				</table>
				</div>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="140">Style No<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="StyleNo" id="StyleNo" onchange="getListValues('StyleNo', 'Destination', 'Destinations');">
	  	        		<option value=""></option>
<?
	$sSQL = "SELECT id, style,
	                (SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id) AS _Season
	         FROM tbl_styles
	         WHERE sub_brand_id='$Brand'
	         ORDER BY style";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey    = $objDb->getField($i, 'id');
		$sValue  = $objDb->getField($i, 'style');
		$sSeason = $objDb->getField($i, '_Season');
?>
	  	        		<option value="<?= $sKey ?>"<?= (($sKey == $StyleNo) ? " selected" : "") ?>><?= $sValue ?> (<?= $sSeason ?>)</option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Additional Styles</td>
					<td align="center">:</td>

					<td>
					  <?= ((count($AdditionalStyles) == 0) ? "No Additional Style" : @implode(", ", $AdditionalStyles)) ?> &nbsp; ( <a href="#" onclick="Effect.toggle('StylesList', 'slide'); return false;">Add / Edit</a> )<br />

					  <div id="StylesList" style="padding-top:5px; display:<?= (($AdditionalStyles == '') ? 'none' : 'block') ?>;">
					    <div>
					      <select id="AdditionalStyles" name="AdditionalStyles[]" multiple size="15">
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey    = $objDb->getField($i, 'id');
		$sValue  = $objDb->getField($i, 'style');
		$sSeason = $objDb->getField($i, '_Season');
?>
	  	        		    <option value="<?= $sKey ?>" <?= ((@in_array($sKey, $AdditionalStyles)) ? 'selected' : '') ?>><?= $sValue ?> (<?= $sSeason ?>)</option>
<?
	}
?>
					      </select>
					    </div>
					  </div>
					</td>
				  </tr>
				  
				  <tr>
					<td>Article No</td>
					<td align="center">:</td>
					<td><input type="text" name="ArticleNo" value="<?= $ArticleNo ?>" maxlength="100" class="textbox" /></td>
				  </tr>				  
				  <tr>
					<td>Category</td>
					<td align="center">:</td>
					<td>
				    <select name="Category">
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
				  </tr>
				  <tr>
					<td>Customer</td>
					<td align="center">:</td>
					<td>
                                            <!--<input type="text" name="Customer" id="Customer" value="<? // $Customer ?>" maxlength="100" class="textbox" />-->
                                            <select name="Customer" id="Customer">
                                                <option value=""></option>
                                            </select>
                                        </td>
				  </tr>

				  <tr>
					<td>Customer PO #</td>
					<td align="center">:</td>
					<td><input type="text" name="CustomerPoNo" value="<?= $CustomerPoNo ?>" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Customer Ship</td>
					<td align="center">:</td>
					<td><input type="text" name="CustomerShip" value="<?= $CustomerShip ?>" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Call No / IC No</td>
					<td align="center">:</td>
					<td><input type="text" name="CallNo" value="<?= $CallNo ?>" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Currency</td>
					<td align="center">:</td>

					<td>
					  <select name="Currency" id="Currency">
<?
	$sCurrencies = array("USD", "EUR", "GBP");

	foreach ($sCurrencies as $sCurrency)
	{
?>
	  	        		<option value="<?= $sCurrency ?>"<?= (($sCurrency == $Currency) ? " selected" : "") ?>><?= $sCurrency ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Price</td>
					<td align="center">:</td>
					<td><input type="text" name="Price" value="<?= $Price ?>" maxlength="6" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>VAS Adjustment</td>
					<td align="center">:</td>
					<td><input type="text" name="VasAdjustment" value="<?= $VasAdjustment ?>" maxlength="6" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Terms of Delivery</td>
					<td align="center">:</td>
					<td><input type="text" name="TermsOfDelivery" value="<?= $TermsOfDelivery ?>" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Place of Departure</td>
					<td align="center">:</td>
					<td><input type="text" name="PlaceOfDeparture" value="<?= $PlaceOfDeparture ?>" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Way of Dispatch</td>
					<td align="center">:</td>
					<td><input type="text" name="WayOfDispatch" value="<?= $WayOfDispatch ?>" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Terms of Payment</td>
					<td align="center">:</td>
					<td><input type="text" name="TermsOfPayment" value="<?= $TermsOfPayment ?>" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Destination</td>
					<td align="center">:</td>

					<td>
					  <select name="Destination" id="Destination">
						<option value=""></option>
<?
	$sSQL = "SELECT id, destination FROM tbl_destinations WHERE brand_id=(SELECT DISTINCT(brand_id) FROM tbl_styles WHERE id='$StyleNo' LIMIT 1) ORDER BY destination";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	  	        		<option value="<?= $sKey ?>"<?= (($sKey == $Destination) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Date of Shipment</td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="DateOfShipment" id="DateOfShipment" value="<?= $DateOfShipment ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('DateOfShipment'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('DateOfShipment'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>
				</table>

				<br />

				<h2>Sample Requirements</h2>
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="140">Size Set</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="SampleSize" value="<?= $SampleSize ?>" size="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Lab Dips</td>
					<td align="center">:</td>
					<td><input type="text" name="LabDips" value="<?= $LabDips ?>" size="100" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Photo/Approval Sample</td>
					<td align="center">:</td>

					<td>
					  <select name="PhotoSample">
						<option value=""></option>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.PhotoSample.value = "<?= $PhotoSample ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Pre-Production Sample</td>
					<td align="center">:</td>
					<td><input type="text" name="PreProductionSample" value="<?= $PreProductionSample ?>" size="50" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
					<td>Note</td>
					<td align="center">:</td>
					<td><textarea name="Note" rows="5" cols="50" style="width:98%;"><?= $Note ?></textarea></td>
				  </tr>
				</table>

				<br />

				<h2>Size & Color Code Requirements</h2>
				<div id="Sizes" style="padding:5px;">
				  <h4>Sizes</h4>

				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
	$sSQL = "SELECT id, size FROM tbl_sizes WHERE type='Size' ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount;)
	{
?>
					<tr>
<?
		for ($j = 0; $j < 10; $j ++)
		{
			if ($i < $iCount)
			{
				$sKey   = $objDb->getField($i, 0);
				$sValue = $objDb->getField($i, 1);
?>
					  <td width="25"><input type="checkbox" class="sizes" name="Sizes[]" value="<?= $sKey ?>" <?= ((@in_array($sKey, $Sizes)) ? "checked" : "") ?> /></td>
					  <td><?= $sValue ?></td>
<?
				$i ++;
			}

			else
			{
?>
					  <td></td>
					  <td></td>
<?
			}
		}
?>
					</tr>
<?
	}
?>

				  </table>

				  <br />
				  <h4>Lengths</h4>

				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
	$sSQL = "SELECT id, size FROM tbl_sizes WHERE type='Length' ORDER BY position, size";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount;)
	{
?>
					<tr>
<?
		for ($j = 0; $j < 10; $j ++)
		{
			if ($i < $iCount)
			{
				$sKey   = $objDb->getField($i, 0);
				$sValue = $objDb->getField($i, 1);
?>
					  <td width="25"><input type="checkbox" class="sizes" name="Sizes[]" value="<?= $sKey ?>" <?= ((@in_array($sKey, $Sizes)) ? "checked" : "") ?> /></td>
					  <td width="50"><?= $sValue ?></td>
<?
				$i ++;
			}

			else
			{
?>
					  <td></td>
					  <td></td>
<?
			}
		}
?>
					</tr>
<?
	}
?>

				  </table>

				  <br />
				  <h4>Equipment :</h4>

				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
	$sSQL = "SELECT id, size FROM tbl_sizes WHERE type='Equipment' ORDER BY size, position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount;)
	{
?>
					<tr>
<?
		for ($j = 0; $j < 10; $j ++)
		{
			if ($i < $iCount)
			{
				$sKey   = $objDb->getField($i, 0);
				$sValue = $objDb->getField($i, 1);
?>
					  <td width="25"><input type="checkbox" class="sizes" name="Sizes[]" value="<?= $sKey ?>" <?= ((@in_array($sKey, $Sizes)) ? "checked" : "") ?> /></td>
					  <td width="50"><?= $sValue ?></td>
<?
				$i ++;
			}

			else
			{
?>
					  <td></td>
					  <td></td>
<?
			}
		}
?>
					</tr>
<?
	}
?>

				  </table>
				</div>

				<br />

				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" />
				  <input type="button" value="" class="btnCancel" title="Cancel" onclick="document.location='<?= SITE_URL ?>data/purchase-orders.php';" />
				</div>
			    </form>

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