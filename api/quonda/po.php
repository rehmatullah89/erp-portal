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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body style="background:#ffffff;">

<div id="PopupDiv" style="width:100%; min-width:680px; background:#ffffff;">
<?
	$PoId      = IO::intValue('PoId');
	$OrderNo   = IO::strValue('OrderNo');
	$AuditCode = IO::strValue('AuditCode');

	if ($PoId > 0)
		$iPoId = $PoId;

	else
	{
		$iAuditId  = substr($AuditCode, 1);
		$iVendorId = getDbValue("vendor_id", "tbl_qa_reports", "id='$iAuditId'");


		$sSQL = "SELECT id, CONCAT(order_no, ' ', order_status) AS _Po,
						(SELECT brand FROM tbl_brands WHERE id=tbl_po.brand_id) AS _Brand
				 FROM tbl_po
				 WHERE vendor_id='$iVendorId' AND order_no LIKE '$OrderNo'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 1)
		{
?>
	  			  <h2>Select PO</h2>

			      <div style="padding:15px;">
			        <ol>
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iPoId  = $objDb->getField($i, 'id');
				$sPo    = $objDb->getField($i, '_Po');
				$sBrand = $objDb->getField($i, '_Brand');
?>
			          <li><a href="api/quonda/po.php?PoId=<?= $iPoId ?>"><?= $sPo ?></a> - <b><?= $sBrand ?></b></li>
<?
			}
?>
			        </ol>
			      </div>
<?
		}

		else
			$iPoId = $objDb->getField(0, 0);
	}




	if ($iPoId > 0)
	{

		$sSQL = "SELECT order_no, order_status, call_no, customer_po_no, terms_of_delivery, place_of_departure, way_of_dispatch,terms_of_payment, size_set, lab_dips, photo_sample, pre_prod_sample, note, sizes, styles, created, modified,
						(SELECT name FROM tbl_users WHERE id=tbl_po.created_by) AS _CreatedBy,
						(SELECT name FROM tbl_users WHERE id=tbl_po.modified_by) AS _ModifiedBy,
						(SELECT vendor FROM tbl_vendors WHERE id=tbl_po.vendor_id) AS _Vendor,
						(SELECT brand FROM tbl_brands WHERE id=tbl_po.brand_id) AS _Brand
				 FROM tbl_po
				 WHERE id='$iPoId'";
		$objDb->query($sSQL);

		$sVendor              = $objDb->getField(0, "_Vendor");
		$sBrand               = $objDb->getField(0, "_Brand");
		$sOrderNo             = $objDb->getField(0, "order_no");
		$sOrderStatus         = $objDb->getField(0, "order_status");
		$sCustomerPoNo        = $objDb->getField(0, "customer_po_no");
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
?>
		    <h2>Purchase Order Info</h2>

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

<?
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
			    <td>Customer PO #</td>
			    <td align="center">:</td>
			    <td><?= $sCustomerPoNo ?></td>
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
		    <h2 style="margin:0px;">Size & Color Code Requirements</h2>
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


		$sSQL = "SELECT id, color, line, price, style_id, destination_id, etd_required FROM tbl_po_colors WHERE po_id='$iPoId' ORDER BY id";
		$objDb->query($sSQL);

		$iPoColorsCount = $objDb->getCount( );


		for ($i = 0; $i < $iPoColorsCount; $i ++)
		{
			$iSubTotal      = 0;

			$iColorId       = $objDb->getField($i, 'id');
			$sColor         = $objDb->getField($i, 'color');
			$sLine          = $objDb->getField($i, 'line');
			$fPrice         = $objDb->getField($i, 'price');
			$iStyleId       = $objDb->getField($i, 'style_id');
			$iDestinationId = $objDb->getField($i, 'destination_id');
			$sEtdRequired   = $objDb->getField($i, 'etd_required');
?>
		    <div style="margin:1px 0px 0px 1px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
			 	  <td width="170"><b>Color</b></td>
				  <td width="90"><b>Line</b></td>
				  <td width="75"><b>Price ($)</b></td>
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
						$sSQL = "SELECT quantity FROM tbl_po_quantities WHERE po_id='$iPoId' AND color_id='$iColorId' AND size_id='{$sPoSizes[$j][0]}'";
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
				$iTotal += $iSubTotal;
			}
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
<?
	}
?>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>