<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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

	$sSQL = "SELECT * FROM tbl_flipbooks WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$Title        = $objDb->getField(0, "title");
		$Color        = $objDb->getField(0, "color");
		$Background   = $objDb->getField(0, "background");
		$Width        = $objDb->getField(0, "width");
		$Left         = $objDb->getField(0, "left");
		$Top          = $objDb->getField(0, "top");
		$Products     = @explode(",", $objDb->getField(0, "products"));
		$Users        = @explode(",", $objDb->getField(0, "users"));
		$FrontPicture = $objDb->getField(0, "front_picture");
		$BackPicture  = $objDb->getField(0, "back_picture");
	}
	else
		redirect($Referer, "DB_ERROR");

	$sUsersList    = getList("tbl_users", "id", "name", "status='A'");
	$sProductsList = getList("tbl_fb_products", "id", "CONCAT(style, '|', picture_left)", "", "FIELD(id,".$objDb->getField(0, "products").")");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/edit-flipbook.js"></script>
  <script type="text/javascript" src="scripts/jscolor/jscolor.js"></script>
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
			    <h1><img src="images/h1/data/flipbooks.jpg" width="142" height="20" vspace="10" alt="" title="" /></h1>

				<form name="frmData" id="frmData" method="post" action="data/update-flipbook.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="OldFrontPicture" value="<?= $FrontPicture ?>" />
			    <input type="hidden" name="OldBackPicture" value="<?= $BackPicture ?>" />

				<h2>Edit Flipbook</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="90">Title<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td width="250"><input type="text" id="Title" name="Title" value="<?= $Title ?>" maxlength="50" size="25" class="textbox" style="width:220px;" /></td>
					<td width="50"></td>
					<td></td>
				  </tr>

				  <tr>
					<td>Color<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Color" value="<?= $Color ?>" maxlength="7" size="10" class="textbox color {required:true,hash:true,caps:false}" /></td>
					<td></td>
					<td></td>
				  </tr>

				  <tr>
					<td>Background<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Background" value="<?= $Background ?>" maxlength="7" size="10" class="textbox color {required:true,hash:true,caps:false}" /></td>
					<td></td>
					<td></td>
				  </tr>

				  <tr>
					<td>Box Position<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td colspan="3">
					  <input type="text" name="Left" value="<?= $Left ?>" maxlength="7" size="5" class="textbox" />
					  x
					  <input type="text" name="Top" value="<?= $Top ?>" maxlength="7" size="5" class="textbox" />
					  x
					  <input type="text" name="Width" value="<?= $Width ?>" maxlength="7" size="5" class="textbox" />
					  &nbsp; ( Left x Top x Width)
					</td>
				  </tr>

				  <tr>
					<td>Picture Left</td>
					<td align="center">:</td>

					<td colspan="3">
					  <input type="file" name="FrontPicture" value="" maxlength="50" size="25" class="textbox" />
<?
	if ($FrontPicture != "")
	{
?>
				      (<a href="<?= MDL_PRODUCTS_DIR.$FrontPicture ?>" class="lightview"><?= substr($FrontPicture, (strpos($FrontPicture, "{$Id}-front-") + strlen("{$Id}-front-"))) ?></a>)
<?
	}
?>
					</td>
				  </tr>

				  <tr>
					<td>Picture Right</td>
					<td align="center">:</td>

					<td colspan="3">
					  <input type="file" name="BackPicture" value="" maxlength="50" size="25" class="textbox" />
<?
	if ($BackPicture != "")
	{
?>
				      (<a href="<?= MDL_PRODUCTS_DIR.$BackPicture ?>" class="lightview"><?= substr($BackPicture, (strpos($BackPicture, "{$Id}-back-") + strlen("{$Id}-back-"))) ?></a>)
<?
	}
?>
					</td>
				  </tr>

				  <tr valign="top">
					<td>User(s)<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Users[]" id="Users" multiple size="10" style="width:225px;">
<?
	foreach ($sUsersList as $sKey => $sValue)
	{
		if (@in_array($sKey, $Users))
			continue;
?>
			            <option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
	}
?>
					  </select>
					  <br />
					  <br />
					  User Filter: <input type="text" value="" size="18" class="textbox" onkeyup="filterUsers(this.value);" />
					</td>

					<td width="50">
					  <input value=" > " class="button" onclick="moveUserRight( );" type="button"><br />
					  <br />
					  <input value=" < " class="button" onclick="moveUserLeft( );" type="button"><br />
					  <br />
					  <br />
					</td>

					<td valign="top">
					  <select name="SelectedUsers[]" id="SelectedUsers" multiple size="10" style="width:225px;">
<?
	foreach ($sUsersList as $sKey => $sValue)
	{
		if (!@in_array($sKey, $Users))
			continue;
?>
			            <option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

 				  <tr>
					<td valign="top">Products<span class="mandatory">*</span></td>
					<td valign="top" align="center">:</td>

					<td valign="top" width="250">
					  <select name="Products[]" id="Products" multiple size="10" style="width:225px;">
<?
	foreach ($sProductsList as $sKey => $sValue)
	{
		if (@in_array($sKey, $Products))
			continue;

		@list($sValue, $sPicture) = @explode("|", $sValue);
?>
			            <option value="<?= $sKey ?>" rel="<?= (SITE_URL.MDL_PRODUCTS_DIR.$sPicture) ?>" onclick="showImage('<?= (SITE_URL.MDL_PRODUCTS_DIR.$sPicture) ?>');"><?= $sValue ?></option>
<?
	}
?>
					  </select>
					  <br />
					  <br />
					  Product Filter: <input type="text" value="" size="17" class="textbox" onkeyup="filterProducts(this.value);" />
					</td>

					<td width="50">
					  <input value=" > " class="button" onclick="moveProductRight( );" type="button"><br />
					  <br />
					  <input value=" < " class="button" onclick="moveProductLeft( );" type="button"><br />
					  <br />
					  <br />
					</td>

					<td valign="top">
					  <div style="position:relative;">
					    <img id="Image" src="files/flipbooks/default.jpg" width="150" hspace="25" style="position:absolute; right:0px; top:0px; border:solid 1px #888888; padding:1px;" />

					    <select name="SelectedProducts[]" id="SelectedProducts" multiple size="10" style="width:225px;" onclick="showSelectedImage( );">
<?
	foreach ($sProductsList as $sKey => $sValue)
	{
		if (!@in_array($sKey, $Products))
			continue;

		@list($sValue, $sPicture) = @explode("|", $sValue);
?>
			              <option value="<?= $sKey ?>" rel="<?= (SITE_URL.MDL_PRODUCTS_DIR.$sPicture) ?>"><?= $sValue ?></option>
<?
	}
?>
					    </select>

					    <div style="clear:both; padding-top:13px;">
					      <input value="   Up   " class="button" onclick="moveUp( );" type="button">
					      <input value=" Down " class="button" onclick="moveDown( );" type="button">
					    </div>
					  </div>
					</td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" />
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