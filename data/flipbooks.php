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

	$PageId   = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Flipbook = IO::strValue("Flipbook");
	$PostId   = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Title            = IO::strValue("Title");
		$Color            = IO::strValue("Color");
		$Background       = IO::strValue("Background");
		$Left             = IO::strValue("Left");
		$Top              = IO::strValue("Top");
		$Width            = IO::strValue("Width");
		$Users            = IO::getArray("Users");
		$SelectedUsers    = IO::getArray("SelectedUsers");
		$Products         = IO::getArray("Products");
		$SelectedProducts = IO::getArray("SelectedProducts");
	}

	$sUsersList    = getList("tbl_users", "id", "name", "status='A'");
	$sProductsList = getList("tbl_fb_products", "id", "CONCAT(style, '|', picture_left)");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/flipbooks.js"></script>
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
			    <h1>FlipBooks</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
				<form name="frmData" id="frmData" method="post" action="data/save-flipbook.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
				<h2>Add Flipbook</h2>

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
					<td><input type="text" name="Color" value="<?= (($Color == '') ? "#ffffff" : $Color) ?>" maxlength="7" size="10" class="textbox color {required:true,hash:true,caps:false}" /></td>
					<td></td>
					<td></td>
				  </tr>

				  <tr>
					<td>Background<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Background" value="<?= (($Background == '') ? "#000000" : $Background) ?>" maxlength="7" size="10" class="textbox color {required:true,hash:true,caps:false}" /></td>
					<td></td>
					<td></td>
				  </tr>

				  <tr>
					<td>Box Position<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td colspan="3">
					  <input type="text" name="Left" value="<?= (($Left == 0) ? 150 : $Left) ?>" maxlength="7" size="5" class="textbox" />
					  x
					  <input type="text" name="Top" value="<?= (($Top == 0) ? 105 : $Top) ?>" maxlength="7" size="5" class="textbox" />
					  x
					  <input type="text" name="Width" value="<?= (($Width == 0) ? 250 : $Width) ?>" maxlength="7" size="5" class="textbox" />
					  &nbsp; ( Left x Top x Width)
					</td>
				  </tr>

				  <tr>
					<td>Front Picture<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="file" name="FrontPicture" value="" maxlength="50" size="25" class="textbox" /></td>
					<td></td>
					<td></td>
				  </tr>

				  <tr>
					<td>Back Picture<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="file" name="BackPicture" value="" maxlength="50" size="25" class="textbox" /></td>
					<td></td>
					<td></td>
				  </tr>

				  <tr valign="top">
					<td>User(s)<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Users[]" id="Users" multiple size="10" style="width:225px;">
<?
		foreach ($sUsersList as $sKey => $sValue)
		{
			if (@in_array($sKey, $SelectedUsers))
				continue;
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Users)) ? " selected" : "") ?>><?= $sValue ?></option>
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
			if (!@in_array($sKey, $SelectedUsers))
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
			if (@in_array($sKey, $SelectedProducts))
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
			if (!@in_array($sKey, $SelectedProducts))
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

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			         <td width="52">Flipbook</td>
			          <td width="180" align="center"><input type="text" name="Flipbook" value="" /></td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </div>

			    </form>


			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Flipbook !="")
		$sConditions = " WHERE title LIKE '%$Flipbook%' ";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_flipbooks", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT id, title, products, users FROM tbl_flipbooks $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="5%" class="center">#</td>
				      <td width="29%">Flipbook Title</td>
				      <td width="28%">Products</td>
				      <td width="28%">Users</td>
				      <td width="10%" class="center">Options</td>
				    </tr>
<?
		}


		$iId       = $objDb->getField($i, 'id');
		$sTitle    = $objDb->getField($i, 'title');
		$sProducts = $objDb->getField($i, 'products');
		$sUsers    = $objDb->getField($i, 'users');

		$iProducts = @explode(",", $sProducts);
		$sProducts = "";

		for ($j = 0; $j < count($iProducts); $j ++)
		{
			@list($sProduct, $sPicture) = @explode("|", $sProductsList[$iProducts[$j]]);

			$sProducts .= "- {$sProduct}<br />";
		}


		$iUsers = @explode(",", $sUsers);
		$sUsers = "";

		for ($j = 0; $j < count($iUsers); $j ++)
			$sUsers .= ("- ".$sUsersList[$iUsers[$j]]."<br />");
?>

				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td class="center"><?= ($iStart + $i + 1) ?></td>
				      <td><?= $sTitle ?></td>
				      <td><?= $sProducts ?></td>
				      <td><?= $sUsers ?></td>

				      <td class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="data/edit-flipbook.php?Id=<?= $iId ?>"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="data/delete-flipbook.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Flipbook?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Flipbook Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Flipbook={$Flipbook}");
?>

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