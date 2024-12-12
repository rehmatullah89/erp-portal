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

	if ($sUserRights['Delete'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id      = IO::intValue('Id');
	$Referer = urldecode(IO::strValue("Referer"));

	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];


	$sSQL = "SELECT *,
	                (SELECT name FROM tbl_users WHERE id=tbl_po.created_by) AS _CreatedBy,
	                (SELECT name FROM tbl_users WHERE id=tbl_po.modified_by) AS _ModifiedBy,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_po.vendor_id) AS _Vendor,
	                (SELECT brand FROM tbl_brands WHERE id=tbl_po.brand_id) AS _Brand
			 FROM tbl_po
			 WHERE id='$Id'";
	$objDb->query($sSQL);

	$sVendor      = $objDb->getField(0, "_Vendor");
	$sBrand       = $objDb->getField(0, "_Brand");
	$sOrderNo     = $objDb->getField(0, "order_no");
	$sOrderStatus = $objDb->getField(0, "order_status");
	$OrderNature  = $objDb->getField(0, "order_nature");
	$OrderType    = $objDb->getField(0, "order_type");
	$sStyles      = $objDb->getField(0, "styles");
	$iQuantity    = $objDb->getField(0, "quantity");

	$sCreatedAt   = $objDb->getField(0, "created");
	$sCreatedBy   = $objDb->getField(0, "_CreatedBy");
	$sModifiedAt  = $objDb->getField(0, "modified");
	$sModifiedBy  = $objDb->getField(0, "_ModifiedBy");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/delete-purchase-order.js"></script>
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
			    <h1><img src="images/h1/data/purchase-orders-listing.jpg" width="140" height="20" vspace="10" alt="" title="" /></h1>

			    <form name="frmData" id="frmData" method="post" action="data/escrow-purchase-order.php" class="frmOutline" onsubmit="$('BtnDelete').disabled=true;">
			    <input type="hidden" name="Id" id="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />

				<h2>Purchase Order Details</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="80">Vendor</td>
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
					<td>Order Nature</td>
					<td align="center">:</td>
					<td><?= (($OrderNature == "B") ? "Bulk" : "SMS") ?></td>
				  </tr>

				  <tr>
					<td>Quantity</td>
					<td align="center">:</td>
					<td><?= formatNumber($iQuantity, false) ?></td>
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
?>

				  <tr>
					<td>Entry By</td>
					<td align="center">:</td>
					<td><?= $sCreatedBy ?> at <?= formatDate($sCreatedAt, "d-M-Y h:i A") ?></td>
				  </tr>

<?
	if ($sCreatedBy != $sModifiedBy)
	{
?>
				  <tr>
					<td>Modified By</td>
					<td align="center">:</td>
					<td><?= $sModifiedBy ?> at <?= formatDate($sModifiedAt, "d-M-Y h:i A") ?></td>
				  </tr>
<?
	}
?>
				</table>

				<br />
				<h2>Reason</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="80">Reason<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Reason">
						<option value=""></option>
					    <option value="Auditor">Auditor</option>
					    <option value="Buyer">Buyer</option>
					    <option value="Factory">Factory</option>
					    <option value="Merchandising">Merchandising</option>
					    <option value="Wrong Data Entry">Wrong Data Entry</option>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Details<br /><small>(if any)</small></td>
					<td align="center">:</td>
					<td><textarea name="Details" rows="5" cols="50" style="width:98%;"></textarea></td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar">
				  <input type="submit" id="BtnDelete" value="" class="btnDelete" title="Delete" onclick="return validateForm( );" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='<?= $Referer ?>';" />
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