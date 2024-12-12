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

	if ($sUserRights['Add'] == "Y")
	{
		$PostId = IO::strValue("PostId");

		if ($PostId != "")
		{
			$_REQUEST = @unserialize($_SESSION[$PostId]);

			$Album       = IO::strValue("Album");
			$Description = IO::strValue("Description");
			$Picture     = IO::strValue("Picture");
		}
?>
			    <form name="frmAlbum" id="frmAlbum" method="post" action="hr/save-user-album.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSaveAlbum').disabled=true;">
				<h2>Add Photo Album</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="70">Album<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Album" value="<?= $Album ?>" size="33" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
					<td>Description</td>
					<td align="center">:</td>
					<td><textarea name="Description" rows="4" cols="30"><?= $Description ?></textarea></td>
				  </tr>

				  <tr>
					<td>Picture</td>
					<td align="center">:</td>
					<td><input type="file" name="Picture" value="" size="27" class="file" /> &nbsp; (Size: 160 x 160)</td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSaveAlbum" value="" class="btnSave" title="Save" onclick="return validateAlbumForm( );" /></div>
			    </form>

			    <br style="line-height:4px;" />
<?
	}
?>

			    <div class="tblSheet">
			      <h2>Photo Albums</h2>
<?
	$sSQL = "SELECT * FROM tbl_user_albums WHERE user_id='$Id' ORDER BY album ASC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount == 0)
	{
?>
				  <div class="noRecord">No Album Found!</div>
<?
	}

	else
	{
?>

				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
		for ($i = 0; $i < $iCount;)
		{
?>
	    			<tr valign="top">
<?
			for ($j = 0; $j < 5; $j ++, $i ++)
			{
				if ($i < $iCount)
				{
					$iId          = $objDb->getField($i, 'id');
					$sAlbum       = $objDb->getField($i, 'album');
					$sDescription = $objDb->getField($i, 'description');
					$sPicture     = $objDb->getField($i, 'picture');

					if ($sPicture == "" || !@file_exists($sBaseDir.USER_ALBUMS_IMG_PATH.'enlarged/'.$sPicture))
						$sPicture = "default.jpg";
?>
					  <td width="20%" align="center">
						<div class="albumPic">
						  <div><a href="<?= USER_ALBUMS_IMG_PATH.'enlarged/'.$sPicture ?>" class="lightview" title="<?= $sAlbum ?> :: :: topclose: true"><img src="<?= USER_ALBUMS_IMG_PATH.'thumbs/'.$sPicture ?>" alt="" title="" /></a></div>
						</div>

						<?= $sAlbum ?><br /><br />

						<div>
<?
					if ($sUserRights['Edit'] == "Y")
					{
?>
				          <a href="./" onclick="Effect.SlideDown('EditAlbum<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				          &nbsp;
<?
					}

					if ($sUserRights['Delete'] == "Y")
					{
?>
				          <a href="hr/delete-user-album.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Album? All Photos under this Album will also be Deleted.');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
					}
?>

						</div>
					  </td>
<?
				}

				else
				{
?>
	      			  <td width="20%"></td>
<?
				}
			}
?>
					</tr>

					<tr>
					  <td colspan="5">
<?
			$i -= 5;

			for ($j = 0; $j < 5; $j ++, $i ++)
			{
				if ($i < $iCount)
				{
					$iId          = $objDb->getField($i, 'id');
					$sAlbum       = $objDb->getField($i, 'album');
					$sDescription = $objDb->getField($i, 'description');
					$sPicture     = $objDb->getField($i, 'picture');

?>
					    <div id="EditAlbum<?= $iId ?>" style="display:none;">
						  <div style="padding:1px;">

						    <form name="frmAlbum<?= $iId ?>" id="frmAlbum<?= $iId ?>" method="post" action="hr/update-user-album.php" enctype="multipart/form-data" class="frmInlineEdit" onsubmit="$('BtnSaveAlbum<?= $iId ?>').disabled=true;">
						    <input type="hidden" name="Id" value="<?= $iId ?>" />
						    <input type="hidden" name="OldPicture" value="<?= $sPicture ?>" />

						    <table border="0" cellpadding="3" cellspacing="0" width="100%">
							  <tr>
							    <td width="70">Album<span class="mandatory">*</span></td>
							    <td width="20" align="center">:</td>
							    <td><input type="text" name="Album" value="<?= $sAlbum ?>" size="33" maxlength="100" class="textbox" /></td>
							  </tr>

							  <tr valign="top">
							    <td>Description</td>
							    <td align="center">:</td>
							    <td><textarea name="Description" rows="4" cols="30"><?= $sDescription ?></textarea></td>
							  </tr>

							  <tr>
							    <td>Picture</td>
							    <td align="center">:</td>
							    <td><input type="file" name="Picture" value="" size="27" class="file" /> &nbsp; (Size: 160 x 160)</td>
							  </tr>

							  <tr>
							    <td></td>
							    <td></td>

							    <td>
							  	  <input type="submit" id="BtnSaveAlbum<?= $iId ?>" value="SAVE" class="btnSmall" onclick="return validateEditAlbumForm(<?= $iId ?>);" />
								  <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('EditAlbum<?= $iId ?>');" />
							    </td>
							  </tr>
						    </table>
						    </form>

						  </div>
					    </div>
<?
				}
			}

			if ($i < ($iCount - 1))
			{
?>
					    <hr />
<?
			}

			else
			{
?>
					    <br />
<?
			}
?>
					  </td>
					</tr>
<?
		}
?>
	  			  </table>
<?
	}
?>

			    </div>

			    <hr />

<?
	$sAlbumsList = getList("tbl_user_albums", "id", "album", "user_id='{$_SESSION['UserId']}'");

	if ($sUserRights['Add'] == "Y")
	{
		$PostId  = IO::strValue("PostId");

		if ($PostId != "")
		{
			$_REQUEST = @unserialize($_SESSION[$PostId]);

			$Album = IO::intValue("Album");
		}
?>
			    <form name="frmPhoto" id="frmPhoto" method="post" action="hr/save-user-photos.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSavePhoto').disabled=true;">
			    <input type="hidden" name="Vendor" value="<?= $Id ?>" />

				<h2>Add Album Photos</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="60">Album<span class="mandatory">*</span></td>
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
				    <td>Photo # <?= $i ?></td>
				    <td align="center">:</td>
				    <td><input type="text" name="Caption<?= $i ?>" value="<?= IO::strValue('Caption'.$i) ?>" size="25" class="textbox" /></td>
				    <td><input type="file" name="Picture<?= $i ?>" size="40" class="file" /></td>
				  </tr>
<?
		}
?>
				</table>

				<br />
				<div class="buttonsBar"><input type="submit" id="BtnSavePhoto" value="" class="btnSave" title="Save" onclick="return validatePhotoForm( );" /></div>
			    </form>

<?
	}


	foreach ($sAlbumsList as $iAlbumId => $sAlbum)
	{
?>

			    <br style="line-height:4px;" />

			    <div class="tblSheet">
			      <h2><?= $sAlbum ?></h2>
<?
		$sSQL = "SELECT * FROM tbl_user_photos WHERE album_id='$iAlbumId' ORDER BY caption ASC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount == 0)
		{
?>
				  <div class="noRecord">No Photo Found!</div>
<?
		}

		else
		{
?>



				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
			for ($i = 0; $i < $iCount;)
			{
?>
	    			<tr valign="top">
<?
				for ($j = 0; $j < 5; $j ++, $i ++)
				{
					if ($i < $iCount)
					{
						$iId      = $objDb->getField($i, 'id');
						$iAlbum   = $objDb->getField($i, 'album_id');
						$sCaption = $objDb->getField($i, 'caption');
						$sPicture = $objDb->getField($i, 'picture');

						if ($sPicture == "" || !@file_exists($sBaseDir.USER_PHOTOS_IMG_PATH.'enlarged/'.$sPicture))
							$sPicture = "default.jpg";
?>
					  <td width="20%" align="center">
						<div class="albumPic">
						  <div><a href="<?= USER_PHOTOS_IMG_PATH.'enlarged/'.$sPicture ?>" class="lightview" title="<?= $sAlbum ?> :: :: topclose: true"><img src="<?= USER_PHOTOS_IMG_PATH.'thumbs/'.$sPicture ?>" alt="" title="" /></a></div>
						</div>

						<?= $sCaption ?><br /><br />

						<div>
<?
						if ($sUserRights['Edit'] == "Y")
						{
?>
				          <a href="./" onclick="Effect.SlideDown('EditPhoto<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				          &nbsp;
<?
						}

						if ($sUserRights['Delete'] == "Y")
						{
?>
				          <a href="hr/delete-user-photo.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Photo?.');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
						}
?>

						</div>
					  </td>
<?
					}

					else
					{
?>
	      			  <td width="20%"></td>
<?
					}
				}
?>
					</tr>

					<tr>
					  <td colspan="5">
<?
				$i -= 5;

				for ($j = 0; $j < 5; $j ++, $i ++)
				{
					if ($i < $iCount)
					{
						$iId      = $objDb->getField($i, 'id');
						$iAlbum   = $objDb->getField($i, 'album_id');
						$sCaption = $objDb->getField($i, 'caption');
						$sPicture = $objDb->getField($i, 'picture');
?>
					    <div id="EditPhoto<?= $iId ?>" style="display:none;">
						  <div style="padding:1px;">

						    <form name="frmPhoto<?= $iId ?>" id="frmPhoto<?= $iId ?>" method="post" action="hr/update-user-photo.php" enctype="multipart/form-data" class="frmInlineEdit" onsubmit="$('BtnSavePhoto<?= $iId ?>').disabled=true;">
						    <input type="hidden" name="Id" value="<?= $iId ?>" />
						    <input type="hidden" name="OldPicture" value="<?= $sPicture ?>" />

						    <table border="0" cellpadding="3" cellspacing="0" width="100%">
							  <tr>
							    <td width="70">Album<span class="mandatory">*</span></td>
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
							  	  <input type="submit" id="BtnSavePhoto<?= $iId ?>" value="SAVE" class="btnSmall" onclick="return validateEditPhotoForm(<?= $iId ?>);" />
								  <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('EditPhoto<?= $iId ?>');" />
							    </td>
							  </tr>
						    </table>
						    </form>

						  </div>
					    </div>
<?
					}
				}

				if ($i < ($iCount - 1))
				{
?>
					    <hr />
<?
				}

				else
				{
?>
					    <br />
<?
				}
?>
					  </td>
					</tr>
<?
			}
?>
	  			  </table>
<?
		}
?>
		        </div>
<?
	}
?>