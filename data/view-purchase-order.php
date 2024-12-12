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

	$Id = IO::intValue('Id');

	$sSQL = "SELECT *,
	                (SELECT name FROM tbl_users WHERE id=tbl_po.created_by) AS _CreatedBy,
	                (SELECT name FROM tbl_users WHERE id=tbl_po.modified_by) AS _ModifiedBy,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_po.vendor_id) AS _Vendor,
	                (SELECT brand FROM tbl_brands WHERE id=tbl_po.brand_id) AS _Brand
			 FROM tbl_po
			 WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sVendor              = $objDb->getField(0, "_Vendor");
		$sBrand               = $objDb->getField(0, "_Brand");
		$iBrand               = $objDb->getField(0, "brand_id");
		$sOrderNo             = $objDb->getField(0, "order_no");
		$sOrderStatus         = $objDb->getField(0, "order_status");
		$OrderNature          = $objDb->getField(0, "order_nature");
		$OrderType            = $objDb->getField(0, "order_type");
		$sCustomer            = $objDb->getField(0, "customer");
		$sArticleNo           = $objDb->getField(0, "article_no");
		$sVpoNo               = $objDb->getField(0, "vpo_no");
		$sProductCode         = $objDb->getField(0, "product_code");
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
		$sBankDetails         = $objDb->getField(0, "bank_details");
		$sShippingAddress     = $objDb->getField(0, "shipping_address");
		$sPoTerms             = $objDb->getField(0, "po_terms");
		$sItemNo              = $objDb->getField(0, "item_number");
		$sProductGroup        = $objDb->getField(0, "product_group");
		$sQuality             = $objDb->getField(0, "quality");
		$sSinglePacking       = $objDb->getField(0, "single_packing");
		$iPackagingSize       = $objDb->getField(0, "packing_size");
		$iPackagingColour     = $objDb->getField(0, "packing_color");
		$iPackagingCarton     = $objDb->getField(0, "packing_carton");
		$sHangingPacking      = $objDb->getField(0, "hanging_packing");
		$sHsCode              = $objDb->getField(0, "hs_code");
		$sShippingFromDate    = $objDb->getField(0, "shipping_from_date");
		$sShippingToDate      = $objDb->getField(0, "shipping_to_date");
		$sCartonInstructions  = $objDb->getField(0, "carton_instructions");
		$sCartonLabeling      = $objDb->getField(0, "carton_labeling");
		$sPdfFile             = $objDb->getField(0, "pdf");		
		$sMgfStatus           = $objDb->getField(0, "mgf_status");
		$sStatus              = $objDb->getField(0, "status");
		$sCreatedAt           = $objDb->getField(0, "created");
		$sCreatedBy           = $objDb->getField(0, "_CreatedBy");
		$sModifiedAt          = $objDb->getField(0, "modified");
		$sModifiedBy          = $objDb->getField(0, "_ModifiedBy");
	}


	$sPrefix = "";

	if (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@3-tree.com") === FALSE &&
   	    @strpos($_SESSION["Email"], "@selimpex.com") === FALSE && @strpos($_SESSION["Email"], "@global-exports.com") === FALSE)
		$sPrefix = "vsr_";
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
			    <td>Article No</td>
			    <td align="center">:</td>
			    <td><?= $sArticleNo ?></td>
			  </tr>
			  
			  <tr>
			    <td>Customer</td>
			    <td align="center">:</td>
			    <td><?= $sCustomer ?></td>
			  </tr>
			  
<?
	if ($sVpoNo != "")
	{
?>
			  <tr>
			    <td>VPO No</td>
			    <td align="center">:</td>
			    <td><?= $sVpoNo ?></td>
			  </tr>
			  
			  <tr>
			    <td>Status</td>
			    <td align="center">:</td>
			    <td><?= (($sMgfStatus == "A") ? "Approved" : "Released") ?></td>
			  </tr>			  
<?
	}
	
	if ($sProductCode != "")
	{
?>
			  <tr>
			    <td>Product Code</td>
			    <td align="center">:</td>
			    <td><?= $sProductCode ?></td>
			  </tr>
<?
	}
?>

			  <tr>
			    <td>Customer PO</td>
			    <td align="center">:</td>
			    <td><?= $sCustomerPoNo ?></td>
			  </tr>

			  <tr>
			    <td>Customer Ship</td>
			    <td align="center">:</td>
			    <td><?= $sCustomerShip ?></td>
			  </tr>

			  <tr>
			    <td>Call No / IC No</td>
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
		  
			  <tr>
				<td>HS Code</td>
				<td align="center">:</td>
				<td><?= $sHsCode ?></td>
			  </tr>

			  <tr>
				<td>Shipping Window</td>
				<td align="center">:</td>
				
				<td>
				
				  <table border="0" cellpadding="0" cellspacing="0" width="116">
					<tr>
					  <td width="70">From Date</td>
					  <td width="100"><?= (($sShippingFromDate != "0000-00-00") ? $sShippingFromDate : "N/A") ?></td>
					  <td width="52">To Date</td>
					  <td width="82"><?= (($sShippingToDate != "0000-00-00") ? $sShippingToDate : "N/A") ?></td>
					</tr>
				  </table>
				
				</td>
			  </tr>

<?
	if ($sCartonLabeling != "")
	{
?>
			  <tr>
				<td>Carton Labeling Image</td>
				<td align="center">:</td>
				<td><img src="<?= (PO_DOCS_DIR.$sCartonLabeling) ?>" style="max-width:98%;" /></td>
			  </tr>
<?
	}
?>

			  <tr valign="top">
				<td>Packing/Carton Instructions</td>
				<td align="center">:</td>
				<td><?= nl2br($sCartonInstructions) ?></td>
			  </tr>

<?
	if ($sPdfFile != "")
	{
?>
			  <tr>
				<td>PO PDF File</td>
				<td align="center">:</td>
				<td><a href="<?= (PO_DOCS_DIR.$sPdfFile) ?>" target="_blank"><?= substr($sPdfFile, (strpos($sPdfFile, "{$Id}-") + strlen("{$Id}-"))) ?></a></td>
			  </tr>
<?
	}
?>
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
			</table>

			<br />
			<h2>Description / Note</h2>

			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr valign="top">
				<td width="140">Description / Note</td>
				<td width="20" align="center">:</td>
			    <td><?= nl2br($sNote) ?></td>
			  </tr>
		    </table>

<?
	if ($iBrand == 273)
	{
?>
			<br />
			<h2>PO Generation</h2>

			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr valign="top">
				<td width="140">Shipping Address</td>
				<td width="20" align="center">:</td>
				<td><?= nl2br($sShippingAddress) ?></td>
			  </tr>

			  <tr valign="top">
				<td>Bank Details</td>
				<td align="center">:</td>
				<td><?= nl2br($sBankDetails) ?></td>
			  </tr>

			  <tr valign="top">
				<td>Terms</td>
				<td align="center">:</td>
				<td><?= nl2br($sPoTerms) ?></td>
			  </tr>

			  <tr>
				<td>Item Number</td>
				<td align="center">:</td>
				<td><?= $sItemNo ?></td>
			  </tr>

			  <tr>
				<td>Product Group</td>
				<td align="center">:</td>
				<td><?= $sProductGroup ?></td>
			  </tr>

			  <tr>
				<td>Quality</td>
				<td align="center">:</td>
				<td><?= nl2br($sQuality) ?></td>
			  </tr>
			</table>

			<br />
			<h2>Packaging</h2>

			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="140">Single Packing</td>
				<td width="20" align="center">:</td>
				<td><?= (($sSinglePacking == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
				<td>Size</td>
				<td align="center">:</td>
				<td><?= (($iPackagingSize > 0) ? $iPackagingSize : "") ?></td>
			  </tr>

			  <tr>
				<td>Colour</td>
				<td align="center">:</td>
				<td><?= (($iPackagingColour > 0) ? $iPackagingColour : "") ?></td>
			  </tr>

			  <tr>
				<td>Carton</td>
				<td align="center">:</td>
				<td><?= (($iPackagingCarton > 0) ? $iPackagingCarton : "") ?></td>
			  </tr>

			  <tr>
				<td>Hanging Packing</td>
				<td align="center">:</td>
				<td><?= (($sHangingPacking == "Y") ? "Yes" : "No") ?></td>
			  </tr>
			</table>
<?
	}
?>

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


	$sSQL = "SELECT id, color, line, {$sPrefix}price, style_id, destination_id, etd_required, {$sPrefix}etd_required FROM tbl_po_colors WHERE po_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iPoColorsCount = $objDb->getCount( );


	for ($i = 0; $i < $iPoColorsCount; $i ++)
	{
		$iSubTotal      = 0;

		$iColorId       = $objDb->getField($i, 'id');
		$sColor         = $objDb->getField($i, 'color');
		$sLine          = $objDb->getField($i, 'line');
		$fPrice         = $objDb->getField($i, "{$sPrefix}price");
		$iStyleId       = $objDb->getField($i, 'style_id');
		$iDestinationId = $objDb->getField($i, 'destination_id');
		$sEtdRequired   = $objDb->getField($i, "{$sPrefix}etd_required");

		if ($sEtdRequired == "" || $sEtdRequired == "0000-00-00")
			$sEtdRequired = $objDb->getField($i, "etd_required");
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
				  <td><?= formatNumber($fPrice, true, 4) ?></td>
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
					$sSQL = "SELECT quantity FROM tbl_po_quantities WHERE po_id='$Id' AND color_id='$iColorId' AND size_id='{$sPoSizes[$j][0]}'";
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

		    <!--<div class="poRowTotal">Total: <?= formatNumber($iSubTotal, false) ?></div>-->

<?
	}

	if ($iPoColorsCount == 0)
	{
?>
	  	    <div class="noRecord">No PO Quantity Record Found!</div>
<?
	}
?>
	  	    <!--<div class="poGrandTotal">Grand Total: <?= formatNumber($iTotal, false) ?></div>-->

<?
	if (@in_array($_SESSION['UserId'], array(1,2,3,26,32,92,319,552,721,1070)))
	{
		$sClass = array("evenRow", "oddRow");
?>
		    <h2 style="margin:4px 0px 1px 0px;">PO Update Log</h2>

			<div class="tblSheet">
			<table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
			  <tr class="headerRow" style="background:#aaaaaa;">
				<td width="4%" class="center"><b>#</b></td>
				<td width="26%"><b>User</b></td>
				<td width="20%" class="center"><b>Date/Time</b></td>
				<td width="50%"><b>Reason</b></td>
			  </tr>

			  <tr class="<?= $sClass[1] ?>" valign="top">
			    <td class="center">1</td>
			    <td><?= $sCreatedBy ?></td>
			    <td class="center"><?= formatDate($sCreatedAt, "d-M-Y h:i A") ?></td>
			    <td>- PO Entry -</td>
			  </tr>

<?
		$sSQL = "SELECT date_time, reason,
		                (SELECT name FROM tbl_users WHERE id=tbl_po_log.user_id) AS _User
		         FROM tbl_po_log
		         WHERE po_id='$Id'
		         ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sUser     = $objDb->getField($i, '_User');
			$sDateTime = $objDb->getField($i, 'date_time');
			$sReason   = $objDb->getField($i, 'reason');
?>

			  <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
			    <td class="center"><?= ($i + 2) ?></td>
			    <td><?= $sUser ?></td>
			    <td class="center"><?= formatDate($sDateTime, "d-M-Y h:i A") ?></td>
			    <td><?= $sReason ?></td>
			  </tr>
<?
		}
?>

			  <tr class="<?= $sClass[0] ?>" valign="top">
			    <td class="center"><?= ($i + 2) ?></td>
			    <td><?= $sModifiedBy ?></td>
			    <td class="center"><?= formatDate($sModifiedAt, "d-M-Y h:i A") ?></td>
			    <td></td>
			  </tr>

			  <tr class="<?= $sClass[0] ?>" valign="top">
			    <td></td>
			    <td style="color:#ff0000;">PO Status</td>
			    <td colspan="2" style="color:#ff0000;"><?= (($sStatus == "C") ? "Closed" : "In-Progress") ?></td>
			  </tr>
		    </table>
		    </div>
<?
	}
?>

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