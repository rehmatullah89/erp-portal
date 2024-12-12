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

	$PageId  = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Id      = IO::intValue("Id");
	$Album   = IO::intValue("Album");
	$Caption = IO::strValue("Caption");
	$PostId  = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Album = IO::intValue("Album");
	}

	$sAlbumsList = getList("tbl_vendor_profile_albums", "id", "album");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/vendor-pictures.js"></script>
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
			    <h1><img src="images/h1/data/vendor-profile-pictures.jpg" width="352" height="20" vspace="10" alt="" title="" /></h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-vendor-pictures.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="Vendor" value="<?= $Id ?>" />

				<h2>Add Vendor Pictures</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="70">Vendor</td>
					<td width="20" align="center">:</td>
					<td width="180"><?= getDbValue("vendor", "tbl_vendors", "id='$Id'") ?></td>
					<td></td>
				  </tr>

				  <tr>
					<td width="70">Album<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td width="180">
					  <select name="Album">
						<option value=""></option>
<?
		foreach ($sAlbumsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Parent) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>

					<td></td>
				  </tr>
<?
		for ($i = 1; $i <= 5; $i ++)
		{
?>

				  <tr>
				    <td>Picture # <?= $i ?></td>
				    <td align="center">:</td>
				    <td><input type="text" name="Caption<?= $i ?>" value="<?= IO::strValue('Caption'.$i) ?>" size="25" class="textbox" /></td>
				    <td><input type="file" name="Picture<?= $i ?>" size="40" class="file" /></td>
				  </tr>
<?
		}
?>
				</table>

				<br />
				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" />
				  <input type="button" id="BtnBack" value="" class="btnBack" title="Back" onclick="document.location='<?= SITE_URL ?>data/vendor-profiles.php';" />
				</div>
			    </form>

			    <hr />
<?
	}
?>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <input type="hidden" name="Id" value="<?= $Id ?>" />

			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="55">Picture</td>
			          <td width="170"><input type="text" name="Caption" value="<?= $Caption ?>" class="textbox" maxlength="50" /></td>
			          <td width="50">Album</td>

			          <td width="200">
					    <select name="Album">
						  <option value="">All Albums</option>
<?
	foreach ($sAlbumsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Album) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

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
	$sConditions = "WHERE vendor_id='$Id'";

	if ($Caption != "")
		$sConditions .= " AND caption LIKE '%$Caption%' ";

	if ($Album != "")
		$sConditions .= " AND album_id='$Album' ";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_vendor_profile_pictures", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_vendor_profile_pictures $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
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
				      <td width="50%">Picture</td>
				      <td width="30%">Album</td>
				      <td width="12%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId      = $objDb->getField($i, 'id');
		$sCaption = $objDb->getField($i, 'caption');
		$iAlbum   = $objDb->getField($i, 'album_id');
		$sPicture = $objDb->getField($i, 'picture');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="50%"><?= $sCaption ?></td>
				      <td width="30%"><?= $sAlbumsList[$iAlbum] ?></td>

				      <td width="12%" class="center">
<?
		if ($sPicture != "" && @file_exists($sBaseDir.VENDOR_PICS_IMG_PATH."enlarged/".$sPicture))
		{
?>
				        <a href="<?= VENDOR_PICS_IMG_PATH."enlarged/".$sPicture ?>" class="lightview" title="<?= $sCaption ?>"><img src="images/icons/thumb.gif" width="16" height="16" alt="Picture" title="Picture" /></a>
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
				        <a href="data/delete-vendor-picture.php?Vendor=<?= $Id ?>&Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Picture?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
<?
		}
?>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" method="post" action="data/update-vendor-picture.php" enctype="multipart/form-data" class="frmInlineEdit" onsubmit="$('BtnSave<?= $i ?>').disabled=true;">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />
					  <input type="hidden" name="OldPicture" value="<?= $sPicture ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="50">Album<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Album">
							  <option value=""></option>
<?
		foreach ($sAlbumsList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $iAlbum) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Caption<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Caption" value="<?= $sCaption ?>" size="33" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Picture</td>
						  <td align="center">:</td>
						  <td><input type="file" name="Picture" value="" size="27" class="file" /></td>
					    </tr>

					    <tr>
						  <td></td>
						  <td></td>

						  <td>
						    <input type="submit" value="SAVE" class="btnSmall" onclick="validateEditForm(<?= $iId ?>);" />
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
				      <td class="noRecord">No Vendor Picture Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Id={$Id}&Caption={$Caption}&Album={$Album}");
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