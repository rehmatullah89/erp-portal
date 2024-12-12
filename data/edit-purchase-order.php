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

	$Id      = IO::intValue('Id');
	$Style   = IO::intValue("Style");
	$Price   = IO::floatValue("Price");
	$Referer = urldecode(IO::strValue("Referer"));
	$OrderNo = IO::strValue("OrderNo");

	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];


	$sPrefix = "";

	if (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@3-tree.com") === FALSE &&
	    @strpos($_SESSION["Email"], "@selimpex.com") === FALSE  && @strpos($_SESSION["Email"], "@global-exports.com") === FALSE)
		$sPrefix = "vsr_";
		
		
	
	if ($OrderNo != "")
	{
		$sSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '%$OrderNo%' AND vendor_id IN ({$_SESSION['Vendors']}) AND brand_id IN ({$_SESSION['Brands']}) ";
		$objDb->query($sSQL);
		
		if ($objDb->getCount( ) != 1)
			redirect("purchase-orders.php?OrderNo={$OrderNo}");
		
		$Id = $objDb->getField(0, "id");
	}


	$sSQL = "SELECT * FROM tbl_po WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$Vendor              = $objDb->getField(0, "vendor_id");
		$Brand               = $objDb->getField(0, "brand_id");
		$OrderNo             = $objDb->getField(0, "order_no");
		$OrderStatus         = $objDb->getField(0, "order_status");
		$OrderType           = $objDb->getField(0, "order_type");
		$OrderNature         = $objDb->getField(0, "order_nature");
		$ArticleNo           = $objDb->getField(0, "article_no");
		$Customer            = $objDb->getField(0, "customer");
		$Category            = $objDb->getField(0, "category_id");
		$VpoNo               = $objDb->getField(0, "vpo_no");
		$CustomerPoNo        = $objDb->getField(0, "customer_po_no");
		$CustomerShip        = $objDb->getField(0, "customer_ship");
		$CallNo              = $objDb->getField(0, "call_no");
		$TermsOfDelivery     = $objDb->getField(0, "terms_of_delivery");
		$PlaceOfDeparture    = $objDb->getField(0, "place_of_departure");
		$WayOfDispatch       = $objDb->getField(0, "way_of_dispatch");
		$TermsOfPayment      = $objDb->getField(0, "terms_of_payment");
		$Looms               = $objDb->getField(0, "looms");
		$SampleSize          = $objDb->getField(0, "size_set");
		$LabDips             = $objDb->getField(0, "lab_dips");
		$PhotoSample         = $objDb->getField(0, "photo_sample");
		$PreProductionSample = $objDb->getField(0, "pre_prod_sample");
		$Note                = $objDb->getField(0, "note");
		$Sizes               = $objDb->getField(0, "sizes");
		$Styles              = $objDb->getField(0, "styles");
		$Destination         = $objDb->getField(0, "destinations");
		$DateOfShipment      = $objDb->getField(0, "{$sPrefix}shipping_dates");
		$VasAdjustment       = $objDb->getField(0, "vas_adjustment");
		$Currency            = $objDb->getField(0, "currency");
		$BankDetails         = $objDb->getField(0, "bank_details");
		$ShippingAddress     = $objDb->getField(0, "shipping_address");
		$PoTerms             = $objDb->getField(0, "po_terms");
		$ItemNo              = $objDb->getField(0, "item_number");
		$ProductGroup        = $objDb->getField(0, "product_group");
		$Quality             = $objDb->getField(0, "quality");
		$SinglePacking       = $objDb->getField(0, "single_packing");
		$PackagingSize       = $objDb->getField(0, "packing_size");
		$PackagingColour     = $objDb->getField(0, "packing_color");
		$PackagingCarton     = $objDb->getField(0, "packing_carton");
		$HangingPacking      = $objDb->getField(0, "hanging_packing");
		$HsCode              = $objDb->getField(0, "hs_code");
		$ShippingFromDate    = $objDb->getField(0, "shipping_from_date");
		$ShippingToDate      = $objDb->getField(0, "shipping_to_date");
		$CartonInstructions  = $objDb->getField(0, "carton_instructions");
		$CartonLabeling      = $objDb->getField(0, "carton_labeling");
		$PdfFile             = $objDb->getField(0, "pdf");

		if($Category=="") {
			$Category = getDbValue("category_id", "tbl_vendors", "id='$Vendor'");
		}		

		if (@strpos($Destination, ",") !== FALSE)
		{
			$Destination = @explode(",", $Destination);
			$Destination = $Destination[0];
		}

		if (@strpos($DateOfShipment, ",") !== FALSE)
		{
			$DateOfShipment = @explode(",", $DateOfShipment);
			$DateOfShipment = $DateOfShipment[0];
		}
	}
	else
		redirect($Referer, "INVALID_PO");
        
        $sCustomersList = getList("tbl_customers", "customer", "customer");   
        $sCategoriesList = getList("tbl_categories", "id", "category");    
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/edit-purchase-order.js.php?Id=<?= $Id ?>&Price=<?= @round($Price, 3) ?>"></script>
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
			    <h1>po entry form</h1>

			    <form name="frmData" id="frmData" method="post" action="data/update-purchase-order.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;" enctype="multipart/form-data">
				<input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
			    <input type="hidden" name="Id" id="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />
				<input type="hidden" name="OldCartonLaebling" value="<?= $CartonLabeling ?>" />
				<input type="hidden" name="OldPdf" value="<?= $Pdf ?>" />

<?
	if (@strpos($_SESSION["Email"], "@apparelco.com") !== FALSE || @strpos($_SESSION["Email"], "@3-tree.com") !== FALSE || 
	    @strpos($_SESSION["Email"], "@selimpex.com") !== FALSE  || @strpos($_SESSION["Email"], "@global-exports.com") !== FALSE)
		@include($sBaseDir."includes/data/edit-po-matrix.php");

	else
		@include($sBaseDir."includes/data/edit-po-others.php");
?>

				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" />
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