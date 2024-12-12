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

	$Id      = IO::intValue('Id');
	$Referer = IO::strValue("Referer");

	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];


	$sSQL = "SELECT * FROM tbl_fb_products WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$Style          = $objDb->getField(0, "style");
		$Color          = $objDb->getField(0, "color");
		$Gender         = $objDb->getField(0, "gender");
		$Wash           = $objDb->getField(0, "wash");
		$Weight         = $objDb->getField(0, "weight");
		$Fabric         = $objDb->getField(0, "fabric");
		$FabricContents = $objDb->getField(0, "fabric_contents");
		$Price          = $objDb->getField(0, "price");
		$Description    = $objDb->getField(0, "description");
		$PictureLeft    = $objDb->getField(0, "picture_left");
		$PictureRight   = $objDb->getField(0, "picture_right");
	}

	else
		redirect($Referer, "DB_ERROR");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/edit-product.js"></script>
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
			    <h1><img src="images/h1/data/products.jpg" width="134" height="20" vspace="10" alt="" title="" /></h1>

				<form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="data/update-product.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="OldPictureLeft" value="<?= $PictureLeft ?>" />
			    <input type="hidden" name="OldPictureRight" value="<?= $PictureRight ?>" />

				<h2>Edit Product</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="110">Style<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Style" value="<?= $Style ?>" maxlength="50" size="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Color<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Color" value="<?= $Color ?>" maxlength="50" size="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Gender<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Gender" id="Gender">
					    <option value=""></option>
					    <option value="Male" <?= (($Gender == "Male") ? "selected" : "") ?>>Male</option>
					    <option value="Female" <?= (($Gender == "Female") ? "selected" : "") ?>>Female</option>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Fabric<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Fabric" value="<?= $Fabric ?>" maxlength="50" size="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Fabric Contents<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="FabricContents" value="<?= $FabricContents ?>" maxlength="50" size="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Weight<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Weight" value="<?= $Weight ?>" maxlength="50" size="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Wash<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Wash" value="<?= $Wash ?>" maxlength="50" size="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Price</td>
					<td align="center">:</td>
					<td><input type="text" name="Price" value="<?= $Price ?>" maxlength="20" size="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Description</td>
					<td align="center">:</td>
					<td><input type="text" name="Description" value="<?= $Description ?>" maxlength="50" size="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Picture Left</td>
					<td align="center">:</td>

					<td>
					  <input type="file" name="PictureLeft" value="" maxlength="50" size="25" class="textbox" />
<?
	if ($PictureLeft != "")
	{
?>
				      (<a href="<?= MDL_PRODUCTS_DIR.$PictureLeft ?>" class="lightview"><?= substr($PictureLeft, (strpos($PictureLeft, "{$Id}-left") + strlen("{$Id}-left"))) ?></a>)
<?
	}
?>
					</td>
				  </tr>

				  <tr>
					<td>Picture Right</td>
					<td align="center">:</td>

					<td>
					  <input type="file" name="PictureRight" value="" maxlength="50" size="25" class="textbox" />
<?
	if ($PictureRight != "")
	{
?>
				      (<a href="<?= MDL_PRODUCTS_DIR.$PictureRight ?>" class="lightview"><?= substr($PictureRight, (strpos($PictureRight, "{$Id}-right") + strlen("{$Id}-right"))) ?></a>)
<?
	}
?>
					</td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" />
				  <input type="button" value="" class="btnBack" onclick="document.location='<?= $Referer ?>';" />
				</div>
			    </form>

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