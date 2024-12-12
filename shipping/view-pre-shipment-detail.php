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
	$objDb3      = new Database( );

	$Id = IO::intValue('Id');
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

	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr bgcolor="#ffffff">
		  <td width="100%">
<?
	$sSQL = "SELECT *, 
	                (SELECT terms FROM tbl_terms_of_delivery WHERE id=tbl_pre_shipment_detail.terms_of_delivery_id) AS _TermsOfDelivery,
	                (SELECT name FROM tbl_users WHERE id=tbl_pre_shipment_detail.created_by) AS _CreatedBy,
	                (SELECT name FROM tbl_users WHERE id=tbl_pre_shipment_detail.modified_by) AS _ModifiedBy					
			 FROM tbl_pre_shipment_detail
			 WHERE po_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iShipments = $objDb->getCount( );
	$iShipments = (($iShipments == 0) ? 1 : $iShipments);

	for ($iIndex = 0; $iIndex < $iShipments; $iIndex ++)
	{
		$iShipId              = $objDb->getField($iIndex, 'id');
		$sTermsOfPayment      = $objDb->getField($iIndex, "terms_of_payment");
		$sTermsOfDelivery     = $objDb->getField($iIndex, "_TermsOfDelivery");
		$sModeOfTransport     = $objDb->getField($iIndex, "mode_of_transport");
		$sCartons             = $objDb->getField($iIndex, "cartons");
		$sHandoverToForwarder = formatDate($objDb->getField($iIndex, "handover_to_forwarder"));
		$sShippingDate        = formatDate($objDb->getField($iIndex, "shipping_date"));
		$sArrivalDate         = formatDate($objDb->getField($iIndex, "arrival_date"));
		$sInvoicePackingList  = $objDb->getField($iIndex, "invoice_packing_list");
		$sInvoiceNo           = $objDb->getField($iIndex, "invoice_no");
		$sLadingAirwayBill    = $objDb->getField($iIndex, "lading_airway_bill");
		$sCreatedAt           = $objDb->getField($iIndex, "created");
		$sCreatedBy           = $objDb->getField($iIndex, "_CreatedBy");
		$sModifiedAt          = $objDb->getField($iIndex, "modified");
		$sModifiedBy          = $objDb->getField($iIndex, "_ModifiedBy");		

		if ($sInvoicePackingList != "" && @file_exists($sBaseDir.PRE_SHIPMENT_DIR.$sInvoicePackingList))
	        $sInvoicePackingList = ('<a href="'.PRE_SHIPMENT_DIR.$sInvoicePackingList.'" target="_blank">'.$sInvoicePackingList.'</a>');
?>

		    <h2>Pre-Shipment Detail # <?= ($iIndex + 1) ?></h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="180">Terms of Payment</td>
			    <td width="20" align="center">:</td>
			    <td><?= $sTermsOfPayment ?></td>
			  </tr>

			  <tr>
			    <td>Terms of Delivery</td>
			    <td align="center">:</td>
			    <td><?= $sTermsOfDelivery ?></td>
			  </tr>

			  <tr>
			    <td>Mode of Transport</td>
			    <td align="center">:</td>
			    <td><?= $sModeOfTransport ?></td>
			  </tr>

			  <tr>
			    <td>Number of Cartons</td>
			    <td align="center">:</td>
			    <td><?= $sCartons ?></td>
			  </tr>

			  <tr>
			    <td>Handover to Forwarder</td>
			    <td align="center">:</td>
			    <td><?= $sHandoverToForwarder ?></td>
			  </tr>

			  <tr>
			    <td>Shipping Date</td>
			    <td align="center">:</td>
			    <td><?= $sShippingDate ?></td>
			  </tr>

			  <tr>
			    <td>Arrival Date</td>
			    <td align="center">:</td>
			    <td><?= $sArrivalDate ?></td>
			  </tr>

			  <tr>
			    <td>Invoice/Packing List</td>
			    <td align="center">:</td>
			    <td><?= $sInvoicePackingList ?></td>
			  </tr>

			  <tr>
			    <td>Vendor Invoice #</td>
			    <td align="center">:</td>
			    <td><?= $sInvoiceNo ?></td>
			  </tr>

			  <tr>
			    <td>Bill of Lading / Airway Bill</td>
			    <td align="center">:</td>
			    <td><?= $sLadingAirwayBill ?></td>
			  </tr>
		    </table>

		    <br />
		    <h2 style="margin-bottom:0px;">Shipment Quantities</h2>

<?
		$sCurrency		 = getDbValue("currency", "tbl_po", "id='$Id'");
		$sPoStyles       = array( );
		$sPoDestinations = array( );
		$sPoSizes        = array( );
		$iSubTotal       = 0;
		$iTotal          = 0;
		$sStyles         = "";

		$sSQL = "SELECT style_id FROM tbl_po_colors WHERE po_id='$Id'";
		$objDb2->query($sSQL);

		$iCount = $objDb2->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sStyles .= (",".$objDb2->getField($i, 0));

		if ($sStyles != "")
			$sStyles = substr($sStyles, 1);

		$sSQL = "SELECT id, style FROM tbl_styles WHERE id IN ($sStyles) ORDER BY style";
		$objDb2->query($sSQL);

		$iPoStylesCount = $objDb2->getCount( );

		for ($i = 0; $i < $iPoStylesCount; $i ++)
			$sPoStyles[$objDb2->getField($i, 0)] = $objDb2->getField($i, 1);

		$sSQL = "SELECT id, destination FROM tbl_destinations WHERE brand_id IN (SELECT brand_id FROM tbl_styles WHERE id IN ($sStyles)) ORDER BY destination";
		$objDb2->query($sSQL);

		$iPoDestinationsCount = $objDb2->getCount( );

		for ($i = 0; $i < $iPoDestinationsCount; $i ++)
			$sPoDestinations[$objDb2->getField($i, 0)] = $objDb2->getField($i, 1);

		$sSQL = "SELECT id, size FROM tbl_sizes WHERE id IN (SELECT DISTINCT(size_id) FROM tbl_po_quantities WHERE po_id='$Id') ORDER BY id";
		$objDb2->query($sSQL);

		$iPoSizesCount = $objDb2->getCount( );

		for ($i = 0; $i < $iPoSizesCount; $i ++)
		{
			$sPoSizes[$i][0] = $objDb2->getField($i, 0);
			$sPoSizes[$i][1] = $objDb2->getField($i, 1);
		}

		$sSQL = "SELECT id, color, line, price, style_id, destination_id, etd_required FROM tbl_po_colors WHERE po_id='$Id' ORDER BY id";
		$objDb2->query($sSQL);

		$iPoColorsCount = $objDb2->getCount( );

		for ($i = 0; $i < $iPoColorsCount; $i ++)
		{
			$iSubTotal      = 0;

			$iColorId       = $objDb2->getField($i, 'id');
			$sColor         = $objDb2->getField($i, 'color');
			$sLine          = $objDb2->getField($i, 'line');
			$fPrice         = $objDb2->getField($i, 'price');
			$iStyleId       = $objDb2->getField($i, 'style_id');
			$iDestinationId = $objDb2->getField($i, 'destination_id');
			$sEtdRequired   = $objDb2->getField($i, 'etd_required');
?>

		    <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
				  <td width="175"><b>Color</b></td>
				  <td width="90"><b>Line</b></td>
				  <td width="85"><b>Price (<?= $sCurrency ?>)</b></td>
				  <td width="100"><b>Style</b></td>
				  <td width="100"><b>ETD Required</b></td>
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
						$sSQL = "SELECT quantity FROM tbl_pre_shipment_quantities WHERE po_id='$Id' AND ship_id='$iShipId' AND color_id='$iColorId' AND size_id='{$sPoSizes[$j][0]}'";
						$objDb3->query($sSQL);

						$iQuantity = $objDb3->getField(0, 'quantity');
?>
		    	  <td align="center"><?= formatNumber($iQuantity) ?></td>
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
		if (@in_array($_SESSION['UserId'], array(1,3,26,32,92,319,552,1070)))
		{
?>
		    <h2 style="margin:4px 0px 1px 0px;">Entry Log</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="140">Created At</td>
			    <td width="20" align="center">:</td>
			    <td><?= formatDate($sCreatedAt, "d-M-Y h:i A") ?></td>
			  </tr>
			  
			  <tr>
			    <td>Created By</td>
			    <td align="center">:</td>
			    <td><?= $sCreatedBy ?></td>
			  </tr>

			  <tr>
			    <td>Last Modified</td>
			    <td align="center">:</td>
			    <td><?= formatDate($sModifiedAt, "d-M-Y h:i A") ?></td>
			  </tr>

			  <tr>
			    <td>Modified By</td>
			    <td align="center">:</td>
			    <td><?= $sModifiedBy ?></td>
			  </tr>
		    </table>
<?
		}
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
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>