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
				<h2>Basic Purchase Order</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="140">Vendor</td>
					<td width="20" align="center">:</td>
					<td><?= getDbValue("vendor", "tbl_vendors", "id='$Vendor'") ?></td>
				  </tr>

				  <tr>
					<td>Brand</td>
					<td align="center">:</td>
					<td><?= getDbValue("brand", "tbl_brands", "id='$Brand'") ?></td>
				  </tr>

				  <tr>
					<td>Order No</td>
					<td align="center">:</td>
					<td><?= $OrderNo ?> <?= $OrderStatus ?></td>
				  </tr>

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
				  <tr valign="top">
					<td>Style No</td>
					<td align="center">:</td>
					<td><?= $sStyleNos ?></td>
				  </tr>
				  
				  <tr>
					<td>Article No</td>
					<td align="center">:</td>
					<td><?= $ArticleNo ?></td>
				  </tr>				  

				  <tr>
					<td>Customer PO #</td>
					<td align="center">:</td>
					<td><?= $CustomerPoNo ?></td>
				  </tr>

				  <tr>
					<td>Call No / IC No</td>
					<td align="center">:</td>
					<td><?= $CallNo ?></td>
				  </tr>

				  <tr>
					<td>Terms of Delivery</td>
					<td align="center">:</td>
					<td><?= $TermsOfDelivery ?></td>
				  </tr>

				  <tr>
					<td>Place of Departure</td>
					<td align="center">:</td>
					<td><?= $PlaceOfDeparture ?></td>
				  </tr>

				  <tr>
					<td>Way of Dispatch</td>
					<td align="center">:</td>
					<td><?= $WayOfDispatch ?></td>
				  </tr>

				  <tr>
					<td>Terms of Payment</td>
					<td align="center">:</td>
					<td><?= $TermsOfPayment ?></td>
				  </tr>
				</table>

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
				<div id="PoColors">

<?
	if (count($SizesList) > 0)
	{
		$sPoSizes  = array( );
		$iSubTotal = 0;
		$iTotal    = 0;


		$sSQL = "SELECT id, size FROM tbl_sizes WHERE id IN ($Sizes) ORDER BY position";
		$objDb->query($sSQL);

		$iPoSizesCount = $objDb->getCount( );

		for ($i = 0; $i < $iPoSizesCount; $i ++)
		{
			$sPoSizes[$i][0] = $objDb->getField($i, 0);
			$sPoSizes[$i][1] = $objDb->getField($i, 1);
		}


		$sSQL = "SELECT id, color, line, vsr_price, style_id, destination_id, vsr_etd_required, etd_required FROM tbl_po_colors WHERE po_id='$Id' ORDER BY id";
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
			$fPrice         = $objDb->getField($i, "vsr_price");
			$iStyleId       = $objDb->getField($i, 'style_id');
			$iDestinationId = $objDb->getField($i, 'destination_id');
			$sEtdRequired   = $objDb->getField($i, "vsr_etd_required");

			if ($sEtdRequired == "" || $sEtdRequired == "0000-00-00")
				$sEtdRequired = $objDb->getField($i, "etd_required");
?>
				<div id="ColorRecord<?= $i ?>" style="margin:0px 4px 0px 4px;">
				  <div>
				    <input type="hidden" name="ColorId<?= $i ?>" id="ColorId<?= $i ?>" value="<?= $iColorId ?>" />

				    <table border="0" cellpadding="0" cellspacing="0" width="100%">
				      <tr class="poRowHeader">
				        <td width="230">&nbsp; <b>Color</b></td>
				        <td width="110"><b>Line</b></td>
				        <!--<td width="95"><b>Price (<?= $Currency ?>)</b></td>-->
				        <td width="190"><b>Style</b></td>
				        <td width="140"><b>Date of Shipment</b></td>
				        <td><b>Destination</b></td>
				      </tr>

				      <tr class="poRowColor">
				        <td><?= $sColor ?></td>
				        <td><?= $sLine ?></td>
				        <!--<td><input type="text" id="Price<?= $i ?>" name="Price<?= $i ?>" value="<?= (($fPrice == 0) ? @round($Price, 4) : @round($fPrice, 4)) ?>" size="8" maxlength="6" class="textbox" /></td>-->
				        <td><?= getDbValue("style", "tbl_styles", "id='".(($iStyleId == 0) ? $Style : $iStyleId)."'") ?></td>

				        <td>

					      <table border="0" cellpadding="0" cellspacing="0" width="116">
						    <tr>
						      <td width="82"><input type="text" name="DateOfShipment<?= $i ?>" id="DateOfShipment<?= $i ?>" value="<?= (($sEtdRequired == '' || $sEtdRequired == '0000-00-00') ? $DateOfShipment : $sEtdRequired) ?>" readonly class="textbox" style="width:70px;" onclick="displayCalendar($('DateOfShipment<?= $i ?>'), 'yyyy-mm-dd', this);" /></td>
						      <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('DateOfShipment<?= $i ?>'), 'yyyy-mm-dd', this);" /></td>
						    </tr>
					      </table>

				        </td>

				        <td><?= getDbValue("destination", "tbl_destinations", "id='".(($iDestinationId == 0) ? $Destination : $iDestinationId)."'") ?></td>
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
				for ($k = 0; $k < 15; $k ++)
				{
					if ($j < $iPoSizesCount)
					{
						$sSQL = "SELECT quantity FROM tbl_po_quantities WHERE po_id='$Id' AND color_id='$iColorId' AND size_id='{$sPoSizes[$j][0]}'";
						$objDb2->query($sSQL);

						$iQuantity = $objDb2->getField(0, 'quantity');
?>
				        <td width="60" align="center">
				          <b><?= $sPoSizes[$j][1] ?></b><br />
						  <?= (float)$iQuantity ?>
				        </td>
<?
						$iSubTotal += $iQuantity;

						$j ++;
					}

					else
					{
?>
				        <td width="60"></td>
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
				      Total: <span id="Total<?= $i ?>"><?= formatNumber($iSubTotal, false) ?></span>
				    </div>
				  </div>
				</div>

<?
		}
?>
				</div>

				<div class="poGrandTotal">
				  Grand Total: <span id="GrandTotal"><?= formatNumber($iTotal, false) ?></span>
				</div>
<?
	}
?>