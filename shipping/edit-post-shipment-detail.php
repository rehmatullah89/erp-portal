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

	if ($sUserRights['Add'] != "Y" && $sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$Id      = IO::intValue('Id');
	$Referer = urldecode(IO::strValue("Referer"));

	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/shipping/edit-post-shipment-detail.js"></script>
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
			    <h1><img src="images/h1/shipping/post-shipment-detail-entry-form.jpg" width="468" height="20" vspace="10" alt="" title="" /></h1>

<?
	$sSQL = "SELECT * FROM tbl_post_shipment_detail WHERE po_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iShipments = $objDb->getCount( );

	for ($iIndex = 0; $iIndex < $iShipments; $iIndex ++)
	{
		$sForm = array( );

		$iShipId = $objDb->getField($iIndex, 'id');

		$sForm[0]['Label']  = "<b>Purchase Order</b>";
		$sForm[0]['Field']  = "PO";
		$sForm[0]['Value']  = ("<b>".IO::strValue("PO")."</b>");
		$sForm[0]['Type']   = "READONLY";

		$sForm[1]['Label']  = "Terms of Payment";
		$sForm[1]['Field']  = ("TermsOfPayment".$iShipId);
		$sForm[1]['Value']  = $objDb->getField($iIndex, "terms_of_payment");
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
		$sForm[2]['Field']  = ("TermsOfDelivery".$iShipId);
		$sForm[2]['Value']  = (($objDb->getField($iIndex, "terms_of_delivery_id") == 0) ? 1 : $objDb->getField($iIndex, "terms_of_delivery_id"));
		$sForm[2]['Type']   = "DROPDOWN";
		$sForm[2]['Values'] = $iTerms;
		$sForm[2]['Labels'] = $sTerms;

		$sForm[3]['Label']  = "Mode of Transport";
		$sForm[3]['Field']  = ("ModeOfTransport".$iShipId);
		$sForm[3]['Value']  = $objDb->getField($iIndex, "mode_of_transport");

		$sForm[4]['Label']  = "Number of Cartons";
		$sForm[4]['Field']  = ("Cartons".$iShipId);
		$sForm[4]['Value']  = $objDb->getField($iIndex, "cartons");

		$sForm[5]['Label']  = "Handover to Forwarder";
		$sForm[5]['Field']  = ("HandoverToForwarder".$iShipId);
		$sForm[5]['Value']  = $objDb->getField($iIndex, "handover_to_forwarder");
		$sForm[5]['Type']   = "DATE";

		$sForm[6]['Label']  = "Shipping Date";
		$sForm[6]['Field']  = ("ShippingDate".$iShipId);
		$sForm[6]['Value']  = $objDb->getField($iIndex, "shipping_date");
		$sForm[6]['Type']   = "DATE";

		$sForm[7]['Label']  = "Arrival Date";
		$sForm[7]['Field']  = ("ArrivalDate".$iShipId);
		$sForm[7]['Value']  = $objDb->getField($iIndex, "arrival_date");
		$sForm[7]['Type']   = "DATE";

		$sForm[8]['Label']  = "Bill of Lading / Airway Bill";
		$sForm[8]['Field']  = ("LadingAirwayBill".$iShipId);
		$sForm[8]['Value']  = $objDb->getField($iIndex, "lading_airway_bill");

		$sForm[9]['Label']  = "Container / Flight No";
		$sForm[9]['Field']  = ("ContainerFlightNo".$iShipId);
		$sForm[9]['Value']  = $objDb->getField($iIndex, "container_flight_no");

		$sForm[10]['Label'] = "Shipping Documents";
		$sForm[10]['Field'] = ("ShippingDocuments".$iShipId);
		$sForm[10]['Value'] = $objDb->getField($iIndex, "shipping_documents");
		$sForm[10]['Type']  = "FILE";

		if ($iIndex > 0)
		{
?>
			    <hr />
<?
		}
?>
			    <form name="frmData<?= $iShipId ?>" id="frmData<?= $iShipId ?>" method="post" action="shipping/save-post-shipment-detail.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave<?= $iIndex ?>').disabled=true;">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="ShipId" value="<?= $iShipId ?>">
			    <input type="hidden" name="PO" value="<?= IO::strValue('PO') ?>" />
			    <input type="hidden" name="OldShippingDocuments" value="<?= $objDb->getField($iIndex, 'shipping_documents') ?>">
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />

				<h2>Post-Shipment Detail # <?= ($iIndex + 1) ?></h2>

<?
		showForm($sForm, POST_SHIPMENT_DIR);
?>

				<br />
				<h2 style="margin-bottom:5px;">Shipment Quantities</h2>

<?
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

		$sSQL = "SELECT id, color, price, style_id, destination_id, etd_required FROM tbl_po_colors WHERE po_id='$Id' ORDER BY id";
		$objDb2->query($sSQL);

		$iPoColorsCount = $objDb2->getCount( );
?>
				<input type="hidden" id="ColorsCount<?= $iShipId ?>" name="ColorsCount<?= $iShipId ?>" value="<?= $iPoColorsCount ?>" />
				<input type="hidden" id="SizesCount<?= $iShipId ?>" name="SizesCount<?= $iShipId ?>" value="<?= $iPoSizesCount ?>" />
<?
		for ($i = 0; $i < $iPoColorsCount; $i ++)
		{
			$iSubTotal      = 0;

			$iColorId       = $objDb2->getField($i, 'id');
			$sColor         = $objDb2->getField($i, 'color');
			$fPrice         = $objDb2->getField($i, 'price');
			$iStyleId       = $objDb2->getField($i, 'style_id');
			$iDestinationId = $objDb2->getField($i, 'destination_id');
			$sEtdRequired   = $objDb2->getField($i, 'etd_required');

			$sSQL = "SELECT (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand FROM tbl_styles WHERE id='$iStyleId'";
			$objDb3->query($sSQL);

			$sBrand = $objDb3->getField(0, 0);
?>
			    <div style="margin:0px 4px 0px 4px;">
				  <input type="hidden" name="ColorId<?= $i ?>_<?= $iShipId ?>" value="<?= $iColorId ?>" />

				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="sdRowHeader">
					  <td width="240"><b>Color</b></td>
					  <td width="100"><b>Price ($)</b></td>
					  <td width="180"><b>Style</b></td>
					  <td width="150"><b>PO ETD</b></td>
					  <td><b>Destination</b></td>
				    </tr>

				    <tr class="sdRowColor">
					  <td><?= $sColor ?></td>
					  <td><?= $fPrice ?></td>
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
				    <tr>
<?
				for ($k = 0; $k < 15; $k ++)
				{
					if ($j < $iPoSizesCount)
					{
						$sSQL = "SELECT quantity FROM tbl_po_quantities WHERE po_id='$Id' AND color_id='$iColorId' AND size_id='{$sPoSizes[$j][0]}'";
						$objDb3->query($sSQL);

						$iOrderQty = $objDb3->getField(0, 'quantity');


						$sSQL = "SELECT quantity FROM tbl_post_shipment_quantities WHERE po_id='$Id' AND ship_id='$iShipId' AND color_id='$iColorId' AND size_id='{$sPoSizes[$j][0]}'";
						$objDb3->query($sSQL);

						$iQuantity = $objDb3->getField(0, 'quantity');
?>
					  <td width="60" align="center">
					    <b><?= $sPoSizes[$j][1] ?></b> (<?= $iOrderQty ?>)<br />
					    <input type="hidden" name="Size<?= $i ?>_<?= $j ?>_<?= $iShipId ?>" value="<?= $sPoSizes[$j][0] ?>" />
					    <input type="text" id="Quantity<?= $i ?>_<?= $j ?>_<?= $iShipId ?>" name="Quantity<?= $i ?>_<?= $j ?>_<?= $iShipId ?>" value="<?= (int)$iQuantity ?>" size="5" maxlength="6" class="textbox" onblur="UpdateTotal(<?= $iShipId ?>);" />
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
				$iTotal += $iSubTotal;
			}
?>
				  </table>

				  <div class="poRowTotal">
				    Total: <span id="Total<?= $i ?>_<?= $iShipId ?>"><?= formatNumber($iSubTotal, false) ?></span>
				  </div>
				</div>

<?
		}
?>
				<div class="poGrandTotal">
				  Grand Total: <span id="GrandTotal<?= $iShipId ?>"><?= formatNumber($iTotal, false) ?></span>
				</div>

				<div class="buttonsBar">
				  <input type="submit" id="BtnSave<?= $iIndex ?>" value="" class="btnSave" title="Save" />
				  <input type="button" value="" class="btnDelete" title="Delete Shipment" onclick="if (confirm('Are you SURE, you want to DELETE this Shipment Detail?') == true) { document.location='<?= SITE_URL ?>shipping/delete-post-shipment-detail.php?Id=<?= $Id ?>&ShipId=<?= $iShipId ?>&PO=<?= urlencode(IO::strValue('PO')) ?>&Referer=<?= urlencode($Referer) ?>'; }" />
				</div>
			    </form>
<?
	}
?>

			    <hr />

			    <form name="frmData" id="frmData" method="post" action="shipping/add-post-shipment-detail.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="PO" value="<?= IO::strValue('PO') ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />

				<div class="buttonsBar">
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='<?= $Referer ?>';" />
				  <input type="submit" value="" class="btnAdd" title="Add Shipment" />
				  <input type="button" value="" class="btnCopy" title="Copy Pre-Shipment Detail"  onclick="if (confirm('Are you SURE, you want to Copy the Pre-Shipment Detail?\n\nExisting Post-Shipment Detail will be Lost.') == true) { document.location='<?= SITE_URL ?>shipping/copy-pre-shipment-detail.php?Id=<?= $Id ?>&PO=<?= urlencode(IO::strValue('PO')) ?>&Referer=<?= urlencode($Referer) ?>'; }" />
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