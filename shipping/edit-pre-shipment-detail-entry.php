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

	if ($sUserRights['Add'] != "Y" && $sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$Id      = IO::intValue('PoId');
	$ShipId  = IO::intValue('ShipId');
	$Po      = IO::strValue('PO');
	$Referer = urlencode(IO::strValue('Referer'));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/shipping/edit-pre-shipment-detail.js"></script>
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
			    <h1><img src="images/h1/shipping/pre-shipment-detail-entry-form.jpg" width="454" height="20" vspace="10" alt="" title="" /></h1>

<?
	$sForm = array( );


	$sSQL = "SELECT * FROM tbl_pre_shipment_detail WHERE po_id='$Id' AND id='$ShipId'";
	$objDb->query($sSQL);


	$sForm[0]['Label']  = "<b>Purchase Order</b>";
	$sForm[0]['Field']  = "PO";
	$sForm[0]['Value']  = ("<b>".IO::strValue("PO")."</b>");
	$sForm[0]['Type']   = "READONLY";

	$sForm[1]['Label']  = "Terms of Payment";
	$sForm[1]['Field']  = "TermsOfPayment";
	$sForm[1]['Value']  = $objDb->getField(0, "terms_of_payment");
	$sForm[1]['Script'] = "get-terms-of-payment.php";


	$iTerms = array( );
	$sTerms = array( );

	$sSQL = "SELECT id, terms FROM tbl_terms_of_delivery ORDER BY id";
	$objDb2->query($sSQL);

	$iCount2 = $objDb2->getCount( );

	for ($i = 0; $i < $iCount2; $i ++)
	{
		$iTerms[] = $objDb2->getField($i, 0);
		$sTerms[] = $objDb2->getField($i, 1);
	}

	$sForm[2]['Label']  = "Terms of Delivery";
	$sForm[2]['Field']  = "TermsOfDelivery";
	$sForm[2]['Value']  = (($objDb->getField(0, "terms_of_delivery_id") == 0) ? 1 : $objDb->getField(0, "terms_of_delivery_id"));
	$sForm[2]['Type']   = "DROPDOWN";
	$sForm[2]['Values'] = $iTerms;
	$sForm[2]['Labels'] = $sTerms;

	$sForm[3]['Label']  = "Mode of Transport";
	$sForm[3]['Field']  = "ModeOfTransport";
	$sForm[3]['Value']  = $objDb->getField(0, "mode_of_transport");

	$sForm[4]['Label']  = "Number of Cartons";
	$sForm[4]['Field']  = "Cartons";
	$sForm[4]['Value']  = $objDb->getField(0, "cartons");

	$sForm[5]['Label']  = "Handover to Forwarder";
	$sForm[5]['Field']  = "HandoverToForwarder";
	$sForm[5]['Value']  = $objDb->getField(0, "handover_to_forwarder");
	$sForm[5]['Type']   = "DATE";

	$sForm[6]['Label']  = "Shipping Date";
	$sForm[6]['Field']  = "ShippingDate";
	$sForm[6]['Value']  = $objDb->getField(0, "shipping_date");
	$sForm[6]['Type']   = "DATE";

	$sForm[7]['Label']  = "Arrival Date";
	$sForm[7]['Field']  = "ArrivalDate";
	$sForm[7]['Value']  = $objDb->getField(0, "arrival_date");
	$sForm[7]['Type']   = "DATE";

	$sForm[8]['Label']  = "Invoice/Packing List";
	$sForm[8]['Field']  = "InvoicePackingList";
	$sForm[8]['Value']  = $objDb->getField(0, "invoice_packing_list");
	$sForm[8]['Type']   = "FILE";

	$sForm[9]['Label']  = "Vendor Invoice #";
	$sForm[9]['Field']  = "InvoiceNo";
	$sForm[9]['Value']  = $objDb->getField(0, "invoice_no");

	$sForm[10]['Label'] = "Bill of Lading / Airway Bill";
	$sForm[10]['Field'] = "LadingAirwayBill";
	$sForm[10]['Value'] = $objDb->getField(0, "lading_airway_bill");
?>
			    <form name="frmData" id="frmData" method="post" action="shipping/save-pre-shipment-detail.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="ShipId" value="<?= $ShipId ?>">
			    <input type="hidden" name="PO" value="<?= IO::strValue('PO') ?>" />
			    <input type="hidden" name="OldInvoicePackingList" value="<?= $objDb->getField(0, 'invoice_packing_list') ?>">
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />

				<h2>Pre-Shipment Detail # <?= (0 + 1) ?></h2>

<?
		showForm($sForm, PRE_SHIPMENT_DIR);
?>

				<br />
				<h2 style="margin-bottom:5px;">Shipment Quantities</h2>

<?
	$sCurrency       = getDbValue("currency", "tbl_po", "id='$Id'");
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


	$sSQL = "SELECT id, size FROM tbl_sizes WHERE id IN (SELECT DISTINCT(size_id) FROM tbl_po_quantities WHERE po_id='$Id') ORDER BY position";
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
?>
				<input type="hidden" id="ColorsCount" name="ColorsCount" value="<?= $iPoColorsCount ?>" />
				<input type="hidden" id="SizesCount" name="SizesCount" value="<?= $iPoSizesCount ?>" />
<?
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


		$sSQL = "SELECT (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand FROM tbl_styles WHERE id='$iStyleId'";
		$objDb3->query($sSQL);

		$sBrand = $objDb3->getField(0, 0);
?>
			    <div style="margin:0px 4px 0px 4px;">
				  <input type="hidden" name="ColorId<?= $i ?>" value="<?= $iColorId ?>" />

				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="sdRowHeader">
					  <td width="240"><b>Color</b></td>
					  <td width="110"><b>Line</b></td>
					  <td width="95"><b>Price (<?= $sCurrency ?>)</b></td>
					  <td width="175"><b>Style</b></td>
					  <td width="100"><b>PO ETD</b></td>
					  <td><b>Destination</b></td>
				    </tr>

				    <tr class="sdRowColor">
					  <td><?= $sColor ?></td>
					  <td><?= $sLine ?></td>
					  <td><?= formatNumber($fPrice, true, 4) ?></td>
					  <td><?= $sPoStyles[$iStyleId] ?> (<?= $sBrand ?>)</td>
					  <td><?= formatDate($sEtdRequired) ?></td>
					  <td><?= $sPoDestinations[$iDestinationId] ?></td>
				    </tr>
				  </table>

				  <br style="line-height:5px;" />

			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
		for ($j = 0; $j < $iPoSizesCount;)
		{
?>
				    <tr valign="bottom">
<?
			for ($k = 0; $k < 15; $k ++)
			{
				if ($j < $iPoSizesCount)
				{
					$sSQL = "SELECT quantity FROM tbl_po_quantities WHERE po_id='$Id' AND color_id='$iColorId' AND size_id='{$sPoSizes[$j][0]}'";
					$objDb3->query($sSQL);

					$iOrderQty = $objDb3->getField(0, 'quantity');


					$sSQL = "SELECT quantity FROM tbl_pre_shipment_quantities WHERE po_id='$Id' AND ship_id='$ShipId' AND color_id='$iColorId' AND size_id='{$sPoSizes[$j][0]}'";
					$objDb3->query($sSQL);

					$iQuantity = $objDb3->getField(0, 'quantity');
?>
					  <td width="60" align="center">
					    <b><?= $sPoSizes[$j][1] ?></b> (<?= formatNumber($iOrderQty) ?>)<br />
					    <input type="hidden" name="Size<?= $i ?>_<?= $j ?>" value="<?= $sPoSizes[$j][0] ?>" />
					    <input type="text" id="Quantity<?= $i ?>_<?= $j ?>" name="Quantity<?= $i ?>_<?= $j ?>" value="<?= $iQuantity ?>" size="5" maxlength="10" class="textbox" onblur="UpdateTotal( );" />
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

<?
	}
?>
				<div class="poGrandTotal">
				  Grand Total: <span id="GrandTotal"><?= formatNumber($iTotal, false) ?></span>
				</div>

				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='<?= (SITE_URL.'shipping/edit-pre-shipment-detail.php?Id='.$Id.'&PO='.$sPo.'&Referer='.$Referer) ?>';" />
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
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>