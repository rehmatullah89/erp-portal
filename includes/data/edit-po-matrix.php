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
?>
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
	  	        		<option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
	}
?>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.Vendor.value = "<?= $Vendor ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Brand<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Brand" id="Brand" onchange="getStylesList('Brand', '', 'Styles'); getListValues('Brand', 'Customer', 'BrandCustomers'); checkPoType( );">
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
						<option value="B">Bulk</option>
						<option value="S">SMS</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.OrderNature.value = "<?= $OrderNature ?>";
					  -->
					  </script>
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
						<option value=""></option>
	  	        		<option value="SDP"<?= (($OrderType == "SDP") ? " selected" : "") ?>>SDP</option>
	  	        		<option value="Non-SDP"<?= (($OrderType == "Non-SDP") ? " selected" : "") ?>>Non-SDP</option>
					  </select>
					</td>
				  </tr>
				</table>
				</div>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="140">Style No<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
<?
	$sStyleNos = "";

	$sSQL = "SELECT style FROM tbl_styles WHERE id IN ($Styles) ORDER BY style";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sStyleNos .= (", ".$objDb->getField($i, 0));

		$sStyleNos = substr($sStyleNos, 2);
	}

	else
		$sStyleNos = "No Style Selected";
?>
					<td>
					  <div id="PoStyles"><?= $sStyleNos ?> &nbsp; ( <a href="#" onclick="Effect.toggle('StylesList', 'slide'); return false;">Add / Edit</a> )</div>

					  <div id="StylesList" style="padding-top:5px; display:none;">
					    <div>
					      <select id="Styles" name="Styles[]" multiple size="15" onchange="updateStyles( );">
<?
	$StylesList = @explode(",", $Styles);

	$sSQL = "SELECT id, style,
	                (SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id) AS _SubSeason
	         FROM tbl_styles
	         WHERE sub_brand_id='$Brand'
	         ORDER BY style";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey       = $objDb->getField($i, 'id');
		$sValue     = $objDb->getField($i, 'style');
		$sSubSeason = $objDb->getField($i, '_SubSeason');
?>
	  	        		    <option value="<?= $sKey ?>" <?= ((@in_array($sKey, $StylesList)) ? 'selected' : '') ?>><?= $sValue ?> (<?= $sSubSeason ?>)</option>
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
					<td>Category<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td>
				    <select name="Category">
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
					<td><!--<input type="text" name="Customer" value="<?// $Customer ?>" maxlength="100" class="textbox" />-->
                                            <select name="Customer" id="Customer">
											<option value=""></option>
<?
                                                foreach($sCustomersList as $sCustomer => $sCustomer)
                                                {
?>
                                                <option value="<?=$sCustomer?>" <?=($sCustomer == $Customer?'selected':'')?>><?=$sCustomer?></option>
<?
                                                }
?>
                                            </select>
                                        </td>
				  </tr>				  

				  <tr>
					<td>VPO No</td>
					<td align="center">:</td>
					<td><input type="text" name="VpoNo" value="<?= $VpoNo ?>" maxlength="50" class="textbox" /></td>
				  </tr>
				  
				  <tr>
					<td>Customer PO</td>
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

					<td>
					  <div>
					    <input type="text" name="TermsOfPayment" id="TermsOfPayment" value="<?= $TermsOfPayment ?>" maxlength="50" class="textbox" autocomplete="off" />

					    <div id="Choices_TermsOfPayment" class="autocomplete"></div>

					    <script type="text/javascript">
					    <!--
						    new Ajax.Autocompleter("TermsOfPayment", "Choices_TermsOfPayment", "ajax/get-terms-of-payment.php", { paramName:"Keywords", minChars:3 } );
					    -->
					    </script>
					  </div>
					</td>
				  </tr>
<?
	if ($Brand == 167)
	{
?>
				  <tr>
					<td>No of Looms</td>
					<td align="center">:</td>
					<td><input type="text" name="Looms" value="<?= $Looms ?>" maxlength="3" class="textbox" /></td>
				  </tr>
<?
	}
?>

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
					<td>VAS Adjustment</td>
					<td align="center">:</td>
					<td><input type="text" name="VasAdjustment" value="<?= $VasAdjustment ?>" maxlength="6" class="textbox" /></td>
				  </tr>
				  
				  <tr>
					<td>HS Code</td>
					<td align="center">:</td>
					<td><input type="text" name="HsCode" value="<?= $HsCode ?>" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Shipping Window</td>
					<td align="center">:</td>
					
					<td>
					
					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="70">From Date</td>
						  <td width="82"><input type="text" name="ShippingFromDate" id="ShippingFromDate" value="<?= (($ShippingFromDate != "0000-00-00") ? $ShippingFromDate : "") ?>" readonly class="textbox" style="width:70px;" onclick="displayCalendar($('ShippingFromDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="70"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ShippingFromDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="52">To Date</td>
						  <td width="82"><input type="text" name="ShippingToDate" id="ShippingToDate" value="<?= (($ShippingToDate != "0000-00-00") ? $ShippingToDate : "") ?>" readonly class="textbox" style="width:70px;" onclick="displayCalendar($('ShippingToDate'), 'yyyy-mm-dd', this);" /></td>
						  <td><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ShippingToDate'), 'yyyy-mm-dd', this);" /></td>						  
						</tr>
					  </table>
					
					</td>
				  </tr>
				  
				  <tr>
					<td>Carton Labeling Image</td>
					<td align="center">:</td>
					
				    <td>
				      <input type="file" name="CartonLabeling" value="" size="30" class="file" />
<?
	if ($CartonLabeling != "")
	{
?>
				      <a href="<?= PO_DOCS_DIR.$CartonLabeling ?>" class="lightview">(<?= substr($CartonLabeling, (strpos($CartonLabeling, "{$Id}-") + strlen("{$Id}-"))) ?></a>)
<?
	}
?>
				    </td>
				  </tr>

				  <tr valign="top">
					<td>Packing/Carton Instructions</td>
					<td align="center">:</td>
					<td><textarea name="CartonInstructions" rows="5" cols="50" style="width:98%;"><?= $CartonInstructions ?></textarea></td>
				  </tr>

				  <tr>
					<td>PO PDF File</td>
					<td align="center">:</td>

				    <td>
				      <input type="file" name="PdfFile" value="" size="30" class="file" />
<?
	if ($PdfFile != "")
	{
?>
				      <a href="<?= PO_DOCS_DIR.$PdfFile ?>" target="_blank">(<?= substr($PdfFile, (strpos($PdfFile, "{$Id}-") + strlen("{$Id}-"))) ?></a>)
<?
	}
?>
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
				</table>

				<br />
				<h2>Description / Note</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="140">Description / Note</td>
					<td width="20" align="center">:</td>
					<td><textarea name="Note" rows="5" cols="50" style="width:98%;"><?= $Note ?></textarea></td>
				  </tr>
				</table>

<?
	if ($Brand == 273)
	{
?>
				<br />
				<h2>PO Generation</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="140">Shipping Address</td>
					<td width="20" align="center">:</td>
					<td><textarea name="ShippingAddress" rows="3" cols="50" style="width:98%;"><?= $ShippingAddress ?></textarea></td>
				  </tr>

				  <tr valign="top">
					<td>Bank Details</td>
					<td align="center">:</td>
					<td><textarea name="BankDetails" rows="3" cols="50" style="width:98%;"><?= $BankDetails ?></textarea></td>
				  </tr>

				  <tr valign="top">
					<td>Terms</td>
					<td align="center">:</td>
					<td><textarea name="PoTerms" rows="5" cols="50" style="width:98%;"><?= $PoTerms ?></textarea></td>
				  </tr>

				  <tr>
					<td>Item Number</td>
					<td align="center">:</td>
					<td><input type="text" name="ItemNo" value="<?= $ItemNo ?>" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Product Group</td>
					<td align="center">:</td>
					<td><input type="text" name="ProductGroup" value="<?= $ProductGroup ?>" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
					<td>Quality</td>
					<td align="center">:</td>
					<td><textarea name="Quality" rows="3" cols="50" style="width:98%;"><?= $Quality ?></textarea></td>
				  </tr>
				</table>

				<br />
				<h2>Packaging</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="140">Single Packing</td>
					<td width="20" align="center">:</td>
					<td><input type="checkbox" name="SinglePacking" value="Y" <?= (($SinglePacking == "Y") ? "checked" : "") ?> /></td>
				  </tr>

				  <tr>
					<td>Size</td>
					<td align="center">:</td>
					<td><input type="text" name="PackagingSize" value="<?= (($PackagingSize > 0) ? $PackagingSize : "") ?>" maxlength="5" size="5" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Colour</td>
					<td align="center">:</td>
					<td><input type="text" name="PackagingColour" value="<?= (($PackagingColour > 0) ? $PackagingColour : "") ?>" maxlength="5" size="5" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Carton</td>
					<td align="center">:</td>
					<td><input type="text" name="PackagingCarton" value="<?= (($PackagingCarton > 0) ? $PackagingCarton : "") ?>" maxlength="5" size="5" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Hanging Packing</td>
					<td align="center">:</td>
					<td><input type="checkbox" name="HangingPacking" value="Y" <?= (($HangingPacking == "Y") ? "checked" : "") ?> /></td>
				  </tr>
				</table>
<?
	}
?>

				<br />
				<h2 id="SizeRequirements">Size & Color Code Requirements</h2>
<?
	$sSizeTitles = "";

	$sSQL = "SELECT size FROM tbl_sizes WHERE id IN ($Sizes) ORDER BY position";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sSizeTitles .= (", ".$objDb->getField($i, 0));

		$sSizeTitles = substr($sSizeTitles, 2);
	}

	else
		$sSizeTitles = "No Size Selected";


	$SizesList = @explode(",", $Sizes);
?>
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="50">Sizes<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><?= $sSizeTitles ?> &nbsp; ( <a href="#" onclick="showSizeList( ); return false;">Add / Edit</a> )</td>
				  </tr>
				</table>

				<div id="SizesList" style="display:none;">
				  <div style="padding:5px;">
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
					    <td width="25"><input type="checkbox" class="sizes" name="Sizes[]" value="<?= $sKey ?>" <?= ((@in_array($sKey, $SizesList)) ? "checked" : "") ?> /></td>
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
	$sSQL = "SELECT id, size FROM tbl_sizes WHERE type='Length' ORDER BY position,size";
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
					    <td width="25"><input type="checkbox" class="sizes" name="Sizes[]" value="<?= $sKey ?>" <?= ((@in_array($sKey, $SizesList)) ? "checked" : "") ?> /></td>
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
					    <td width="25"><input type="checkbox" class="sizes" name="Sizes[]" value="<?= $sKey ?>" <?= ((@in_array($sKey, $SizesList)) ? "checked" : "") ?> /></td>
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
				</div>

				<br />

				<div id="PoColors">

<?
	if (count($SizesList) > 0)
	{
		$sPoStyles       = array( );
		$sPoDestinations = array( );
		$sPoSizes        = array( );
		$iSubTotal       = 0;
		$iTotal          = 0;

		$sSQL = "SELECT id, style, (SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id) AS _SubSeason FROM tbl_styles WHERE id IN ($Styles) ORDER BY style";
		$objDb->query($sSQL);

		$iPoStylesCount = $objDb->getCount( );

		for ($i = 0; $i < $iPoStylesCount; $i ++)
		{
			$sPoStyles[$i][0] = $objDb->getField($i, 0);
			$sPoStyles[$i][1] = $objDb->getField($i, 1);
			$sPoStyles[$i][2] = $objDb->getField($i, 2);
		}

		$sSQL = "SELECT id, destination FROM tbl_destinations WHERE brand_id IN (SELECT brand_id FROM tbl_styles WHERE id IN ($Styles)) ORDER BY destination";
		$objDb->query($sSQL);

		$iPoDestinationsCount = $objDb->getCount( );

		for ($i = 0; $i < $iPoDestinationsCount; $i ++)
		{
			$sPoDestinations[$i][0] = $objDb->getField($i, 0);
			$sPoDestinations[$i][1] = $objDb->getField($i, 1);
		}

		$sSQL = "SELECT id, size FROM tbl_sizes WHERE id IN ($Sizes) ORDER BY position, size";
		$objDb->query($sSQL);

		$iPoSizesCount = $objDb->getCount( );

		for ($i = 0; $i < $iPoSizesCount; $i ++)
		{
			$sPoSizes[$i][0] = $objDb->getField($i, 0);
			$sPoSizes[$i][1] = $objDb->getField($i, 1);
		}


		$sSQL = "SELECT id, color, line, price, style_id, destination_id, etd_required FROM tbl_po_colors WHERE po_id='$Id' ORDER BY id";
		$objDb->query($sSQL);

		$iCount         = $objDb->getCount( );
		$iPoColorsCount = (($iCount == 0) ? 1 : $iCount);
?>
				<input type="hidden" id="RecordCount" name="RecordCount" value="<?= $iPoColorsCount ?>" />
				<input type="hidden" id="ColorsCount" name="ColorsCount" value="<?= $iPoColorsCount ?>" />
				<input type="hidden" id="SizesCount" name="SizesCount" value="<?= $iPoSizesCount ?>" />
<?
		for ($i = 0; $i < $iPoColorsCount; $i ++)
		{
			$iSubTotal      = 0;

			$iColorId       = $objDb->getField($i, 'id');
			$sColor         = $objDb->getField($i, 'color');
			$sLine          = $objDb->getField($i, 'line');
			$fPrice         = $objDb->getField($i, "price");
			$iStyleId       = $objDb->getField($i, 'style_id');
			$iDestinationId = $objDb->getField($i, 'destination_id');
			$sEtdRequired   = $objDb->getField($i, "etd_required");
?>
				<div id="ColorRecord<?= $i ?>" style="margin:0px 4px 0px 4px;">
				  <div>
				    <input type="hidden" name="ColorId<?= $i ?>" id="ColorId<?= $i ?>" value="<?= $iColorId ?>" />

				    <table border="0" cellpadding="0" cellspacing="0" width="100%">
				      <tr class="poRowHeader">
				        <td width="230">&nbsp; <b>Color</b></td>
				        <td width="110"><b><?=(($Brand == 588 || $Brand == 597)?'Commission#':'Line')?></b></td>
				        <td width="95"><b>Price (<?= $Currency ?>)</b></td>
				        <td width="190"><b>Style</b></td>
				        <td width="140"><b>Date of Shipment</b></td>
				        <td><b>Destination</b></td>
				      </tr>

				      <tr class="poRowColor">
				        <td>
				          <div>
				            <input type="text" id="Color<?= $i ?>" name="Color<?= $i ?>" value="<?= htmlentities($sColor, ENT_QUOTES) ?>" size="30" maxlength="250" autocomplete="off" class="textbox" style="width:95%;" />
						    <div id="Choices_<?= $i ?>" class="autocomplete"></div>

						    <script type="text/javascript">
						    <!--
							   new Ajax.Autocompleter("Color<?= $i ?>", "Choices_<?= $i ?>", "ajax/get-purchase-order-colors.php", { paramName:"Keywords", minChars:3 } );
						    -->
						    </script>
						  </div>
				        </td>

				        <td><input type="text" id="Line<?= $i ?>" name="Line<?= $i ?>" value="<?= $sLine ?>" size="12" maxlength="50" class="textbox" /></td>
				        <td><input type="text" id="Price<?= $i ?>" name="Price<?= $i ?>" value="<?= (($fPrice == 0) ? @round($Price, 4) : @round($fPrice, 4)) ?>" size="8" maxlength="6" class="textbox" /></td>

				        <td>
				          <select id="Style<?= $i ?>" name="Style<?= $i ?>">
				            <option value=""></option>
<?
			for ($j = 0; $j < $iPoStylesCount; $j ++)
			{
?>
				            <option value="<?= $sPoStyles[$j][0] ?>"><?= $sPoStyles[$j][1] ?> (<?= $sPoStyles[$j][2] ?>)</option>
<?
			}
?>
				          </select>

					      <script type="text/javascript">
					      <!--
							  document.frmData.Style<?= $i ?>.value = "<?= (($iStyleId == 0) ? $Style : $iStyleId) ?>";
					      -->
					      </script>
				        </td>

				        <td>

					      <table border="0" cellpadding="0" cellspacing="0" width="116">
						    <tr>
						      <td width="82"><input type="text" name="DateOfShipment<?= $i ?>" id="DateOfShipment<?= $i ?>" value="<?= (($sEtdRequired == '' || $sEtdRequired == '0000-00-00') ? (($DateOfShipment != "0000-00-00") ? $DateOfShipment : "") : $sEtdRequired) ?>" readonly class="textbox" style="width:70px;" onclick="displayCalendar($('DateOfShipment<?= $i ?>'), 'yyyy-mm-dd', this);" /></td>
						      <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('DateOfShipment<?= $i ?>'), 'yyyy-mm-dd', this);" /></td>
						    </tr>
					      </table>

				        </td>

				        <td>
				          <select id="Destination<?= $i ?>" name="Destination<?= $i ?>">
				            <option value=""></option>
<?
			for ($j = 0; $j < $iPoDestinationsCount; $j ++)
			{
?>
				            <option value="<?= $sPoDestinations[$j][0] ?>"><?= $sPoDestinations[$j][1] ?></option>
<?
			}
?>
				          </select>

					      <script type="text/javascript">
					      <!--
						  	  document.frmData.Destination<?= $i ?>.value = "<?= (($iDestinationId == 0) ? $Destination : $iDestinationId) ?>";
					      -->
					      </script>
				        </td>
				      </tr>
				    </table>

				    <br style="line-height:5px;" />

				    <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?

			for ($j = 0; $j < $iPoSizesCount;)
			{
?>
				      <tr>
<?
				for ($k = 0; $k < 10; $k ++)
				{
					if ($j < $iPoSizesCount)
					{
						$sSQL = "SELECT quantity FROM tbl_po_quantities WHERE po_id='$Id' AND color_id='$iColorId' AND size_id='{$sPoSizes[$j][0]}' Order By size_id";
						$objDb2->query($sSQL);

						$iQuantity = $objDb2->getField(0, 'quantity');
?>
				        <td width="85" align="left">
				          <b><?= $sPoSizes[$j][1] ?></b><br />
				          <input type="text" id="Quantity<?= $i ?>_<?= $j ?>" name="Quantity<?= $i ?>_<?= $sPoSizes[$j][0] ?>" value="<?= (float)$iQuantity ?>" size="8" maxlength="10" class="textbox" onblur="UpdateTotal( );" />
				        </td>
<?
						$iSubTotal += $iQuantity;

						$j ++;
					}

					else
					{
?>
				        <td width="100"></td>
<?
					}
				}
?>
				      </tr>
<?
			}

			$iTotal += $iSubTotal;
?>
				    </table>

				    <div class="poRowTotal">
<?
			if ($i < $iPoColorsCount && $i > 0)
			{
?>
				      <div><a href="data/delete-purchase-order-color.php?Id=<?= $Id ?>&ColorId=<?= $iColorId ?>&Referer=<?= urlencode($Referer) ?>"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a></div>
<?
			}
?>
				      Total: <span id="Total<?= $i ?>"><?= formatNumber($iSubTotal, false) ?></span>
				    </div>
				  </div>
				</div>

<?
		}
?>
				</div>

				<div class="poGrandTotal">
				  <div>
				    <input type="button" value="" class="btnAdd" title="Add Color" onclick="addColor( );" /><input type="button" value="" class="btnDelete" title="Delete Color" onclick="deleteColor( );" />
				  </div>

				  Grand Total: <span id="GrandTotal"><?= formatNumber($iTotal, false) ?></span>
				</div>
<?
	}


	if ($Referer != "add-purchase-order.php")
	{
?>

				<br />

				<h2>PO Update Reason</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="50">User</td>
					<td width="20" align="center">:</td>
					<td><?= $_SESSION['Name'] ?></td>
				  </tr>

				  <tr>
					<td>Reason</td>
					<td align="center">:</td>
					<td><input type="text" name="Reason" value="" size="30" maxlength="100" class="textbox" /></td>
				  </tr>
				</table>

				<br />
<?
	}
?>