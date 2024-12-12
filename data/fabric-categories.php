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
	$Category = IO::strValue("Category");
	$PostId   = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Parent      = IO::intValue("Parent");
		$Category    = IO::strValue("Category");
		$Description = IO::strValue("Description");
		$Picture     = IO::strValue("Picture");
	}

	$sCategoriesList = getList("tbl_fabric_categories", "id", "category");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/fabric-categories.js"></script>
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
			    <h1>Fabric Categories</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-fabric-category.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Fabric Category</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
				    <td width="70">Parent</td>
				    <td width="20" align="center">:</td>

				    <td>
					  <select name="Parent">
					    <option value=""></option>
<?
		$sSQL = "SELECT id, category FROM tbl_fabric_categories WHERE parent_id='0' ORDER BY category";
		$objDb->query($sSQL);

		$iCount =$objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId       = $objDb->getField($i, 'id');
			$sCategory = $objDb->getField($i, 'category');
?>
					    <option value="<?= $iId ?>"<?= (($iId == $Parent) ? " selected" : "") ?>><?= $sCategory ?></option>
<?
		}
?>
					  </select>
				    </td>
				  </tr>

				  <tr>
					<td>Category<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Category" value="<?= $Category ?>" size="33" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
					<td>Description</td>
					<td align="center">:</td>
					<td><textarea name="Description" rows="4" cols="30"><?= $Description ?></textarea></td>
				  </tr>

				  <tr>
					<td>Picture</td>
					<td align="center">:</td>
					<td><input type="file" name="Picture" value="" size="27" class="file" /> &nbsp; (Size: 160 x 120)</td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="65">Category</td>
			          <td width="160"><input type="text" name="Category" value="<?= $Category ?>" class="textbox" maxlength="50" /></td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Category != "")
		$sConditions .= " WHERE category LIKE '%$Category%' ";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_fabric_categories", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_fabric_categories $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="8%">#</td>
				      <td width="18%">Category</td>
				      <td width="18%">Parent</td>
				      <td width="50%">Description</td>
				      <td width="12%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId          = $objDb->getField($i, 'id');
		$iParent      = $objDb->getField($i, 'parent_id');
		$sCategory    = $objDb->getField($i, 'category');
		$sDescription = $objDb->getField($i, 'description');
		$sPicture     = $objDb->getField($i, 'picture');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="18%"><?= $sCategory ?></td>
				      <td width="18%"><?= $sCategoriesList[$iParent] ?></td>
				      <td width="50%"><?= $sDescription ?></td>

				      <td width="12%" class="center">
<?
		if ($sPicture != "" && @file_exists($sBaseDir.FABRIC_CATEGORIES_IMG_PATH.$sPicture))
		{
?>
				        <a href="<?= FABRIC_CATEGORIES_IMG_PATH.$sPicture ?>" class="lightview" title="<?= $sCategory ?>"><img src="images/icons/thumb.gif" width="16" height="16" alt="Picture" title="Picture" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="./" onclick="Effect.SlideDown('Edit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="data/delete-fabric-category.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Category? All Pictures under this Category will also be Deleted.');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
<?
		}
?>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" method="post" action="data/update-fabric-category.php" enctype="multipart/form-data" class="frmInlineEdit" onsubmit="$('BtnSave<?= $i ?>').disabled=true;">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />
					  <input type="hidden" name="OldPicture" value="<?= $sPicture ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="70">Parent</td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Parent">
							  <option value=""></option>
<?
		$sSQL = "SELECT id, category FROM tbl_fabric_categories WHERE parent_id='0' AND id!='$iId' ORDER BY category";
		$objDb2->query($sSQL);

		$iCount2 =$objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCatId = $objDb2->getField($j, 'id');
			$sCat   = $objDb2->getField($j, 'category');
?>
					  	  	  <option value="<?= $iCatId ?>"<?= (($iCatId == $iParent) ? " selected" : "") ?>><?= $sCat ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Category<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Category" value="<?= $sCategory ?>" size="33" maxlength="100" class="textbox" /></td>
					    </tr>

					    <tr valign="top">
						  <td>Description</td>
						  <td align="center">:</td>
						  <td><textarea name="Description" rows="4" cols="30"><?= $sDescription ?></textarea></td>
					    </tr>

					    <tr>
						  <td>Picture</td>
						  <td align="center">:</td>
						  <td><input type="file" name="Picture" value="" size="27" class="file" /> &nbsp; (Size: 160 x 120)</td>
					    </tr>

					    <tr>
						  <td></td>
						  <td></td>

						  <td>
						    <input type="submit" id="BtnSave<?= $i ?>" value="SAVE" class="btnSmall" onclick="return validateEditForm(<?= $iId ?>);" />
						    <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $iId ?>');" />
						  </td>
					    </tr>
					  </table>
					  </form>

				    </div>
				  </div>

<?
	}

	if ($iCount == 0)
	{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr>
				      <td class="noRecord">No Fabric Category Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Category={$Category}");
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