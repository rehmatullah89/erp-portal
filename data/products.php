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

	$PageId = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Style  = IO::strValue("Style");
	$PostId = IO::strValue("PostId");


	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Style         	= IO::strValue("Style");
		$Color       	= IO::strValue("Color");
		$Gender 		= IO::strValue("Gender");
		$Fabric      	= IO::strValue("Fabric");
		$FabricContents = IO::strValue("FabricContents");
		$Weight   		= IO::strValue("Weight");
		$Wash      		= IO::strValue("Wash");
		$Price     		= IO::strValue("Price");
		$Description  	= IO::strValue("Description");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/products.js"></script>
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
			    <h1>Products</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
				<form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="data/save-product.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
				<h2>Add Product</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="110">Style<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" id="Style" name="Style" value="<?= $Style ?>" maxlength="50" size="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Color<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" id="Color" name="Color" value="<?= $Color ?>" maxlength="50" size="25" class="textbox" /></td>
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
					<td><input type="text" id="Fabric" name="Fabric" value="<?= $Fabric ?>" maxlength="50" size="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Fabric Contents<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" id="FabricContents" name="FabricContents" value="<?= $FabricContents ?>" maxlength="50" size="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Weight<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" id="Weight" name="Weight" value="<?= $Weight ?>" maxlength="50" size="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Wash<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" id="Wash" name="Wash" value="<?= $Wash ?>" maxlength="50" size="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Price</td>
					<td align="center">:</td>
					<td><input type="text" id="Price" name="Price" value="<?= $Price ?>" maxlength="20" size="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Description</td>
					<td align="center">:</td>
					<td><input type="text" id="Description" name="Description" value="<?= $Description ?>" maxlength="50" size="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Left Picture<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="file" name="PictureLeft" value="" maxlength="50" size="25" class="textbox" /></td>
				  </tr>
				  <tr>
					<td>Right Picture<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="file" name="PictureRight" value="" maxlength="50" size="25" class="textbox" /></td>
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
					  <td width="40">Style</td>
					  <td width="200"><input type="text" name="style" value="<?= $Style ?>" id="style" class="textbox" size="20" /></td>
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

	if ($Style !="")
		$sConditions .= " WHERE style LIKE '$Style' ";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_fb_products", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT id, style, color, wash, fabric FROM tbl_fb_products $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="8%">#</td>
				      <td width="20%">Style</td>
				      <td width="16%">Color</td>
				      <td width="16%">Wash</td>
				      <td width="25%">Fabric</td>
				      <td width="15%" class="center">Options</td>
				    </tr>
<?
		}

		$iId     = $objDb->getField($i, 'id');
		$sStyle  = $objDb->getField($i, 'style');
		$sColor  = $objDb->getField($i, 'color');
		$sWash   = $objDb->getField($i, 'wash');
		$sFabric = $objDb->getField($i, 'fabric');
?>

				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= $sStyle ?></td>
				      <td><?= $sColor ?></td>
				      <td><?= $sWash ?></td>
				      <td><?= $sFabric ?></td>

				      <td class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="data/edit-product.php?Id=<?= $iId ?>"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="data/delete-product.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Product?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
<?
		}
?>
				        <a href="data/view-product.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Product Preview :: :: width: 450, height: 500"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Product Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Style={$Style}");
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