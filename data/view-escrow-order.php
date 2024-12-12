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

	$Id = IO::intValue('Id');

	$sSQL = "SELECT *,
	                (SELECT name FROM tbl_users WHERE id=tbl_escrow_po.created_by) AS _CreatedBy,
	                (SELECT name FROM tbl_users WHERE id=tbl_escrow_po.modified_by) AS _ModifiedBy,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_escrow_po.vendor_id) AS _Vendor,
	                (SELECT brand FROM tbl_brands WHERE id=tbl_escrow_po.brand_id) AS _Brand
			 FROM tbl_escrow_po
			 WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sVendor              = $objDb->getField(0, "_Vendor");
		$sBrand               = $objDb->getField(0, "_Brand");
		$sOrderNo             = $objDb->getField(0, "order_no");
		$sOrderStatus         = $objDb->getField(0, "order_status");
		$OrderNature          = $objDb->getField(0, "order_nature");
		$OrderType            = $objDb->getField(0, "order_type");
		$sCustomer            = $objDb->getField(0, "customer");
		$sCustomerPoNo        = $objDb->getField(0, "customer_po_no");
		$sCustomerShip        = $objDb->getField(0, "customer_ship");
		$sCallNo              = $objDb->getField(0, "call_no");
		$sTermsOfDelivery     = $objDb->getField(0, "terms_of_delivery");
		$sPlaceOfDeparture    = $objDb->getField(0, "place_of_departure");
		$sWayOfDispatch       = $objDb->getField(0, "way_of_dispatch");
		$sTermsOfPayment      = $objDb->getField(0, "terms_of_payment");
		$sSampleSize          = $objDb->getField(0, "size_set");
		$sLabDips             = $objDb->getField(0, "lab_dips");
		$sPhotoSample         = $objDb->getField(0, "photo_sample");
		$sPreProductionSample = $objDb->getField(0, "pre_prod_sample");
		$sNote                = $objDb->getField(0, "note");
		$sSizes               = $objDb->getField(0, "sizes");
		$sStyles              = $objDb->getField(0, "styles");
		$fVasAdjustment       = $objDb->getField(0, "vas_adjustment");
		$sCurrency            = $objDb->getField(0, "currency");
		$sCreatedAt           = $objDb->getField(0, "created");
		$sCreatedBy           = $objDb->getField(0, "_CreatedBy");
		$sModifiedAt          = $objDb->getField(0, "modified");
		$sModifiedBy          = $objDb->getField(0, "_ModifiedBy");
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

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:544px; height:544px;">
	  <h2>Basic Purchase Order Info</h2>

	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr bgcolor="#ffffff">
		  <td width="100%">

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="140">Vendor</td>
			    <td width="20" align="center">:</td>
			    <td><?= $sVendor ?></td>
			  </tr>

			  <tr>
			    <td>Brand</td>
			    <td align="center">:</td>
			    <td><?= $sBrand ?></td>
			  </tr>

			  <tr>
			    <td>Order No</td>
			    <td align="center">:</td>
			    <td><?= $sOrderNo ?> <?= $sOrderStatus ?></td>
			  </tr>

			  <tr>
			    <td>Order Nature</td>
			    <td align="center">:</td>
			    <td><?= (($OrderNature == "B") ? "Bulk" : "SMS") ?></td>
			  </tr>

<?
	if ($OrderType != "")
	{
?>
			  <tr>
			    <td>Order Type</td>
			    <td align="center">:</td>
			    <td><?= $OrderType ?></td>
			  </tr>

<?
	}


	$sStyleNos = "";

	$sSQL = "SELECT style FROM tbl_styles WHERE id IN ($sStyles) ORDER BY style";

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
			    <td>Customer</td>
			    <td align="center">:</td>
			    <td><?= $sCustomer ?></td>
			  </tr>

			  <tr>
			    <td>Customer PO #</td>
			    <td align="center">:</td>
			    <td><?= $sCustomerPoNo ?></td>
			  </tr>

			  <tr>
			    <td>Customer Ship</td>
			    <td align="center">:</td>
			    <td><?= $sCustomerShip ?></td>
			  </tr>

			  <tr>
			    <td>Call No</td>
			    <td align="center">:</td>
			    <td><?= $sCallNo ?></td>
			  </tr>

			  <tr>
			    <td>Terms of Delivery</td>
			    <td align="center">:</td>
			    <td><?= $sTermsOfDelivery ?></td>
			  </tr>

			  <tr>
			    <td>Place of Departure</td>
			    <td align="center">:</td>
			    <td><?= $sPlaceOfDeparture ?></td>
			  </tr>

			  <tr>
			    <td>Way of Dispatch</td>
			    <td align="center">:</td>
			    <td><?= $sWayOfDispatch ?></td>
			  </tr>

			  <tr>
			    <td>Terms of Payment</td>
			    <td align="center">:</td>
			    <td><?= $sTermsOfPayment ?></td>
			  </tr>

			  <tr>
			    <td>VAS Adjustment</td>
			    <td align="center">:</td>
			    <td><?= formatNumber($fVasAdjustment) ?> <?= $sCurrency ?></td>
			  </tr>
		    </table>

		    <br />
		    <h2>Sample Requirements</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="140">Size Set</td>
			    <td width="20" align="center">:</td>
			    <td><?= $sSampleSize ?></td>
			  </tr>

			  <tr>
			    <td>Lab Dips</td>
			    <td align="center">:</td>
			    <td><?= $sLabDips ?></td>
			  </tr>

			  <tr>
			    <td>Photo/Approval Sample</td>
			    <td align="center">:</td>
			    <td><?= $sPhotoSample ?></td>
			  </tr>

			  <tr>
			    <td>Pre-Production Sample</td>
			    <td align="center">:</td>
			    <td><?= $sPreProductionSample ?></td>
			  </tr>

			  <tr valign="top">
			    <td>Note</td>
			    <td align="center">:</td>
			    <td><?= nl2br($sNote) ?></td>
			  </tr>
		    </table>

		    <br />
		    <h2 style="margin-bottom:0px;">Size & Color Code Requirements</h2>
<?
	$sPoStyles       = array( );
	$sPoDestinations = array( );
	$sPoSizes        = array( );
	$iSubTotal       = 0;
	$iTotal          = 0;

	$sSQL = "SELECT id, style FROM tbl_styles WHERE id IN ($sStyles) ORDER BY style";
	$objDb->query($sSQL);

	$iPoStylesCount = $objDb->getCount( );

	for ($i = 0; $i < $iPoStylesCount; $i ++)
		$sPoStyles[$objDb->getField($i, 0)] = $objDb->getField($i, 1);


	$sSQL = "SELECT id, destination FROM tbl_destinations WHERE brand_id IN (SELECT brand_id FROM tbl_styles WHERE id IN ($sStyles)) ORDER BY destination";
	$objDb->query($sSQL);

	$iPoDestinationsCount = $objDb->getCount( );

	for ($i = 0; $i < $iPoDestinationsCount; $i ++)
		$sPoDestinations[$objDb->getField($i, 0)] = $objDb->getField($i, 1);


	$sSQL = "SELECT id, size FROM tbl_sizes WHERE id IN ($sSizes) ORDER BY position";
	$objDb->query($sSQL);

	$iPoSizesCount = $objDb->getCount( );

	for ($i = 0; $i < $iPoSizesCount; $i ++)
	{
		$sPoSizes[$i][0] = $objDb->getField($i, 0);
		$sPoSizes[$i][1] = $objDb->getField($i, 1);
	}


	$sSQL = "SELECT id, color, line, price, style_id, destination_id, etd_required FROM tbl_escrow_po_colors WHERE po_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iPoColorsCount = $objDb->getCount( );


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
		    <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
			 	  <td width="170">&nbsp; <b>Color</b></td>
				  <td width="90"><b>Line</b></td>
				  <td width="85"><b>Price (<?= $sCurrency ?>)</b></td>
				  <td width="140"><b>Style</b></td>
				  <td width="95"><b>ETD Required</b></td>
				  <td><b>Destination</b></td>
			    </tr>

			    <tr class="sdRowColor" valign="top">
				  <td><?= $sColor ?></td>
				  <td><?= $sLine ?></td>
				  <td><?= formatNumber($fPrice) ?></td>
				  <td><?= $sPoStyles[$iStyleId] ?></td>
				  <td><?= formatDate($sEtdRequired) ?></td>
				  <td><?= $sPoDestinations[$iDestinationId] ?></td>
			    </tr>
			  </table>

			  <table border="1" bordercolor="#ffffff" cellpadding="3" cellspacing="0" width="100%">
<?
		for ($j = 0; $j < $iPoSizesCount;)
		{
?>
	      		<tr class="sizesRow">
<?
			for ($k = 0; $k < 15; $k ++)
			{
				if ($j < $iPoSizesCount)
				{
?>
		    	  <td align="center"><b><?= $sPoSizes[$j][1] ?></b></td>
<?
					$j ++;
				}

				else
				{
?>
		    	  <td>&nbsp;</td>
<?
				}
			}
		}
?>
	      	    </tr>

	      		<tr class="quantitiesRow">
<?
		for ($j = 0; $j < $iPoSizesCount;)
		{
			for ($k = 0; $k < 15; $k ++)
			{
				if ($j < $iPoSizesCount)
				{
					$sSQL = "SELECT quantity FROM tbl_escrow_po_quantities WHERE po_id='$Id' AND color_id='$iColorId' AND size_id='{$sPoSizes[$j][0]}'";
					$objDb2->query($sSQL);

					$iQuantity = $objDb2->getField(0, 'quantity');
?>
		    	  <td align="center"><?= (float)$iQuantity ?></td>
<?
					$j ++;

					$iSubTotal += $iQuantity;
				}

				else
				{
?>
		    	  <td>&nbsp;</td>
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
		    </div>

		    <div class="poRowTotal">Total: <?= formatNumber($iSubTotal, false) ?></div>

<?
	}

	if ($iPoColorsCount == 0)
	{
?>
	  	    <div class="noRecord">No PO Quantity Record Found!</div>
<?
	}
?>
	  	    <div class="poGrandTotal">Grand Total: <?= formatNumber($iTotal, false) ?></div>
		  </td>
	    </tr>
	  </table>

	  <br style="line-height:2px;" />
	</div>
<!--  Body Section Ends Here  -->

  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>