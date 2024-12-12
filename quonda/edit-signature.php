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

	$Id      = IO::intValue('Id');
	$Referer = IO::strValue("Referer");

	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];

	$sSQL = "SELECT * FROM tbl_signatures WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect($Referer, "DB_ERROR");

	$sName      = $objDb->getField(0, 'name');
	$sType      = $objDb->getField(0, 'type');
	$sBrands    = $objDb->getField(0, 'brands');
	$sVendors   = $objDb->getField(0, 'vendors');
	$sSignature = $objDb->getField(0, 'signature');


	$iBrands  = @explode(",", $sBrands);
	$iVendors = @explode(",", $sVendors);


	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/edit-signature.js"></script>
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
			    <h1>signatures</h1>

			    <form name="frmData" id="frmData" method="post" action="quonda/update-signature.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disable( );">
			    <input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />
			    <input type="hidden" name="OldSignature" value="<?= $sSignature ?>" />

			    <h2>Edit Signature</h2>

			    <table width="98%" cellspacing="0" cellpadding="3" border="0" align="center">
				  <tr>
				    <td width="90">Person Name</td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="Name" value="<?= $sName ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Type</td>
					<td align="center">:</td>

					<td>
					  <select id="Type" name="Type" style="width:230px;">
			            <option value="F"<?= (($sType == "F") ? " selected" : "") ?>>Factory Representative</option>
			            <option value="M"<?= (($sType == "M") ? " selected" : "") ?>>Merchandiser</option>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Vendor(s)</td>
					<td align="center">:</td>

					<td>
					  <select name="Vendors[]" multiple size="10" style="width:230px;">
<?
	foreach ($sVendorsList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $iVendors)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Brand(s)</td>
					<td align="center">:</td>

					<td>
					  <select name="Brands[]" multiple size="10" style="width:230px;">
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $iBrands)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
				    <td>Signature</td>
				    <td align="center">:</td>

				    <td>
				      <input type="file" name="Signature" value="" size="30" class="file" /> (Recommended Size: 300 x 150)
<?
	if ($sSignature != "")
	{
?>
				      &nbsp; - (<a href="<?= SIGNATURES_IMG_DIR.$sSignature ?>" class="lightview"><?= substr($sSignature, (strpos($sSignature, "{$Id}-") + strlen("{$Id}-"))) ?></a>)
<?
	}
?>
				    </td>
				  </tr>
				</table>

				<br />

			    <div class="buttonsBar">
			      <input type="submit" id="BtnSave" value="" class="btnSave" onclick="return validateForm( );" />
			      <input type="button" value="" class="btnBack" onclick="document.location='<?= $Referer ?>';" />
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