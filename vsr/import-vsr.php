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

	$sVsrFile     = "";
	$iCategory    = IO::intValue("Category");
	$BtxDivision  = IO::strValue("BtxDivision");
	$AdidasReebok = IO::strValue("AdidasReebok");
	$Notify       = IO::strValue("Notify");

	if ($_FILES['VsrFile']['name'] != "")
	{
		$sVsrFile = IO::getFileName($_FILES['VsrFile']['name']);

		if (!@move_uploaded_file($_FILES['VsrFile']['tmp_name'], ($sBaseDir.TEMP_DIR.$sVsrFile)))
				$sVsrFile = "";
	}

	if ($sVsrFile == "")
		redirect("vsr-data.php", "NO_VSR_FILE");


	$sCategories = array( );

	$sSQL = "SELECT * FROM tbl_categories WHERE id='$iCategory'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sCategories['knitting']           = $objDb->getField(0, 'knitting');
		$sCategories['linking']            = $objDb->getField(0, 'linking');
		$sCategories['yarn']               = $objDb->getField(0, 'yarn');
		$sCategories['sizing']             = $objDb->getField(0, 'sizing');
		$sCategories['weaving']            = $objDb->getField(0, 'weaving');
		$sCategories['leather_import']     = $objDb->getField(0, 'leather_import');
		$sCategories['dyeing']             = $objDb->getField(0, 'dyeing');
		$sCategories['leather_inspection'] = $objDb->getField(0, 'leather_inspection');
		$sCategories['lamination']         = $objDb->getField(0, 'lamination');
		$sCategories['cutting']            = $objDb->getField(0, 'cutting');
		$sCategories['print_embroidery']   = $objDb->getField(0, 'print_embroidery');
		$sCategories['sorting']            = $objDb->getField(0, 'sorting');
		$sCategories['bladder_attachment'] = $objDb->getField(0, 'bladder_attachment');
		$sCategories['stitching']          = $objDb->getField(0, 'stitching');
		$sCategories['washing']            = $objDb->getField(0, 'washing');
		$sCategories['finishing']          = $objDb->getField(0, 'finishing');
		$sCategories['lab_testing']        = $objDb->getField(0, 'lab_testing');
		$sCategories['quality']            = $objDb->getField(0, 'quality');
		$sCategories['packing']            = $objDb->getField(0, 'packing');
	}

	if (substr($sVsrFile, -4) == ".xls" && $BtxDivision == "Y")
		@include($sBaseDir."includes/vsr/import-btx-xls-vsr.php");

	else if (substr($sVsrFile, -5) == ".xlsx" && $BtxDivision == "Y")
		@include($sBaseDir."includes/vsr/import-btx-xlsx-vsr.php");

	else if (substr($sVsrFile, -4) == ".xls" && $AdidasReebok == "Y")
		@include($sBaseDir."includes/vsr/import-ar-xls-vsr.php");

	else if (substr($sVsrFile, -5) == ".xlsx" && $AdidasReebok == "Y")
		@include($sBaseDir."includes/vsr/import-ar-xlsx-vsr.php");

	else if (substr($sVsrFile, -4) == ".xls")
		@include($sBaseDir."includes/vsr/import-xls-vsr.php");

	else if (substr($sVsrFile, -5) == ".xlsx")
		@include($sBaseDir."includes/vsr/import-xlsx-vsr.php");

	else
		redirect("vsr-data.php", "INVALID_VSR_FILE");

	@unlink($sBaseDir.TEMP_DIR.$sVsrFile);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/vsr/import-vsr.js"></script>
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
			    <h1><img src="images/h1/vsr/vsr-data.jpg" width="121" height="20" vspace="10" alt="" title="" /></h1>

<?
	if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="vsr/import-vsr.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnImport').disabled=true;">
				<h2>Import VSR File</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="90">Category<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Category">
						<option value=""></option>
<?
		$sSQL = "SELECT id, category FROM tbl_categories ORDER BY category";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sKey   = $objDb->getField($i, 0);
			$sValue = $objDb->getField($i, 1);
?>
	  	        		<option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>BTX Division<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="BtxDivision">
						<option value=""></option>
						<option value="Y">Yes</option>
	  	        		<option value="N">No</option>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Adidas/Reebok<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="AdidasReebok">
	  	        		<option value="N">No</option>
						<option value="Y">Yes</option>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td align="right"><input type="checkbox" name="Notify" value="Y" /></td>
					<td align="center">:</td>
					<td>Send VSR Update Notifications</td>
				  </tr>

				  <tr>
					<td>VSR File<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="file" name="VsrFile" value="" size="30" class="file" /></td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnImport" value="" class="btnImport" title="Import" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>

			    <div class="tblSheet">
			      <h2 style="margin-bottom:1px; margin-right:1px;">VSR Import Status</h2>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="6%" class="center">#</td>
				      <td width="20%">Vendor</td>
				      <td width="15%">Brand</td>
				      <td width="12%">Order #</td>
				      <td width="12%">Style #</td>
				      <td width="35%">Status</td>
				    </tr>

				    <?= $sOutput ?>
			      </table>
			    </div>

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