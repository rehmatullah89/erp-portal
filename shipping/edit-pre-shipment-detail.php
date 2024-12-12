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
	$sSQL = "SELECT order_no, order_status, call_no, customer_po_no, terms_of_delivery, place_of_departure, way_of_dispatch,terms_of_payment, quantity,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_po.vendor_id) AS _Vendor,
	                (SELECT brand FROM tbl_brands WHERE id=tbl_po.brand_id) AS _Brand
			 FROM tbl_po
			 WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sVendor           = $objDb->getField(0, "_Vendor");
		$sBrand            = $objDb->getField(0, "_Brand");
		$sOrderNo          = $objDb->getField(0, "order_no");
		$sOrderStatus      = $objDb->getField(0, "order_status");
		$sCustomerPoNo     = $objDb->getField(0, "customer_po_no");
		$sCallNo           = $objDb->getField(0, "call_no");
		$sTermsOfDelivery  = $objDb->getField(0, "terms_of_delivery");
		$sPlaceOfDeparture = $objDb->getField(0, "place_of_departure");
		$sWayOfDispatch    = $objDb->getField(0, "way_of_dispatch");
		$sTermsOfPayment   = $objDb->getField(0, "terms_of_payment");
		$iQuantity         = $objDb->getField(0, "quantity");
	}
?>
			    <div class="tblSheet">
			      <h2>Purchase Order</h2>

				  <table border="0" cellpadding="3" cellspacing="0" width="100%">
				    <tr>
					  <td width="120">Order No</td>
					  <td width="20" align="center">:</td>
					  <td><?= $sOrderNo ?> <?= $sOrderStatus ?></td>
				    </tr>

				    <tr>
				 	  <td>Vendor</td>
					  <td align="center">:</td>
					  <td><?= $sVendor ?></td>
				    </tr>

				    <tr>
					  <td>Brand</td>
					  <td align="center">:</td>
					  <td><?= $sBrand ?></td>
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

				    <tr>
					  <td>Quantity</td>
					  <td align="center">:</td>
					  <td><?= formatNumber($iQuantity, false) ?></td>
				    </tr>
				  </table>

				  <br />
			    </div>

			    <br />

			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass = array("evenRow", "oddRow");

	$sSQL = "SELECT id, invoice_no, quantity, invoice_packing_list, cartons, shipping_date, lading_airway_bill, created FROM tbl_pre_shipment_detail WHERE po_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="5%" class="center">#</td>
				      <td width="12%">Invoice No</td>
				      <td width="12%" class="center">Shipping Date</td>
				      <td width="18%">Lading/Airway Bil</td>
				      <td width="8%" class="center">Cartons</td>
				      <td width="9%" class="center">Quantity</td>
				      <td width="17%" class="center">Created</td>
				      <td width="19%" class="center">Options</td>
				    </tr>
<?
		}


		$iId                 = $objDb->getField($i, 'id');
		$sInvoicePackingList = $objDb->getField($i, 'invoice_packing_list');
?>

				    <tr class="<?= $sClass[($i % 2)] ?>" <?= $sColor ?>>
				      <td class="center"><?= ($i + 1) ?></td>
				      <td><?= $objDb->getField($i, 'invoice_no') ?></td>
				      <td class="center"><?= formatDate($objDb->getField($i, 'shipping_date')) ?></td>
				      <td><?= $objDb->getField($i, 'lading_airway_bill') ?></td>
				      <td class="center"><?= formatNumber($objDb->getField($i, 'cartons'), false) ?></td>
				      <td class="center"><?= formatNumber($objDb->getField($i, 'quantity')) ?></td>
				      <td class="center"><?= formatDate($objDb->getField($i, 'created'), "d-M-Y h:i A") ?></td>

				      <td class="right">
<?
		if ($objDb->getField($i, 'invoice_no') != "")
		{
			if (checkUserRights("invoice-report.php", "Reports", "view"))
			{
?>
				        <a href="reports/export-invoice-report.php?Invoice=<?= urlencode($objDb->getField($i, 'invoice_no')) ?>"><img src="images/icons/report.gif" width="16" height="16" alt="Invoice Report" title="Invoice Report" /></a>
				        &nbsp;
<?
			}

			if (checkUserRights("inspection-certificate.php", "Reports", "view"))
			{
?>
				        <a href="reports/export-inspection-certificate.php?Invoice=<?= urlencode($objDb->getField($i, 'invoice_no')) ?>&PoId=<?= $Id ?>"><img src="images/icons/certificate.gif" width="16" height="16" alt="Inspection Certificate" title="Inspection Certificate" /></a>
				        &nbsp;
<?
			}
		}

		if ($sInvoicePackingList != "" && @file_exists($sBaseDir.PRE_SHIPMENT_DIR.$sInvoicePackingList))
		{
?>
				        <a href="<?= PRE_SHIPMENT_DIR.$sInvoicePackingList ?>" target="_blank"><img src="images/icons/pdf.gif" width="16" height="16" alt="Invoice Packing List" title="Invoice Packing List" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="shipping/edit-pre-shipment-detail-entry.php?PoId=<?= $Id ?>&ShipId=<?= $iId ?>&PO=<?= urlencode($sOrderNo.' '.$sOrderStatus) ?>&Referer=<?= urlencode($Referer) ?>"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="shipping/delete-pre-shipment-detail.php?Id=<?= $Id ?>&ShipId=<?= $iId ?>&PO=<?= urlencode($sOrderNo.' '.$sOrderStatus) ?>&Referer=<?= urlencode($Referer) ?>" onclick="return confirm('Are you SURE, You want to Delete this PO Shipping Entry?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
<?
		}
?>
				        <a href="shipping/view-pre-shipment-detail-entry.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="PO # <?= $sOrderNo ?> <?= $sOrderStatus ?> :: :: width: 800, height: 550"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Shipment Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

			    <hr />

			    <form name="frmData" id="frmData" method="post" action="shipping/add-pre-shipment-detail.php" class="frmOutline">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="PO" value="<?= IO::strValue('PO') ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />

				<div class="buttonsBar">
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='<?= $Referer ?>';" />
				  <input type="submit" value="" class="btnAdd" title="Add Shipment" />
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