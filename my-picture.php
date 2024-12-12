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

	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sSQL = "SELECT picture FROM tbl_users WHERE id='{$_SESSION['UserId']}'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect(SITE_URL, "DB_ERROR");

	$sPicture = $objDb->getField(0, "picture");

	@list($iWidth, $iHeight) = @getimagesize($sBaseDir.USERS_IMG_PATH.'originals/'.$sPicture);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <link type="text/css" rel="stylesheet" href="css/cropper.css" />

  <script type="text/javascript" src="scripts/cropper.js"></script>

  <script type="text/javascript">
  <!--

		function cropThumb(coords, dimensions)
		{
			$('ThumbX1').value     = coords.x1;
			$('ThumbY1').value     = coords.y1;
			$('ThumbX2').value     = coords.x2;
			$('ThumbY2').value     = coords.y2;
			$('ThumbWidth').value  = dimensions.width;
			$('ThumbHeight').value = dimensions.height;
		}

		var ThumbCrop =
		{
			curCrop: null,

			attachCropper:function(e)
			{
				if (this.curCrop == null)
					this.curCrop = new Cropper.Img('ProfileThumb', { ratioDim:{ x:158, y:118 }, minWidth:158, minHeight:118, onloadCoords:{x1:0, y1:0, x2:158, y2:118}, displayOnInit:true, onEndCrop:cropThumb } );

				else
				{
					this.removeCropper( );

					this.curCrop.initialize('ProfileThumb', { ratioDim:{ x:158, y:118 }, minWidth:158, minHeight:118, onloadCoords:{x1:0, y1:0, x2:158, y2:118}, displayOnInit:true, onEndCrop:cropThumb } );
				}

				if (e != null)
					Event.stop(e);
			},

			removeCropper:function( )
			{
				if (this.curCrop != null)
					this.curCrop.remove( );
			},

			resetCropper:function( )
			{
				this.attachCropper( );
			}
		};

		Event.observe(window, 'load', function( ) { ThumbCrop.attachCropper( ); } );

  -->
  </script>
</head>

<body>

<div id="PopupDiv" style="width:auto;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body">
	  <h2>Edit Profile Picture</h2>
	  <form name="frmPicture" id="frmPicture" method="post" action="save-my-picture.php" class="frmOutline" onsubmit="$('BtnSave').disable( );">
	  <input type="hidden" name="ThumbX1" id="ThumbX1" value="0" />
	  <input type="hidden" name="ThumbY1" id="ThumbY1" value="0" />
	  <input type="hidden" name="ThumbX2" id="ThumbX2" value="158" />
	  <input type="hidden" name="ThumbY2" id="ThumbY2" value="118" />
	  <input type="hidden" name="ThumbWidth" id="ThumbWidth" value="158" />
	  <input type="hidden" name="ThumbHeight" id="ThumbHeight" value="118" />
	  <input type="hidden" name="ImgWidth" value="158" />
	  <input type="hidden" name="ImgHeight" value="118" />

	  <div style="overflow:hidden; position:relative;">
	    <div style="position:relative; height:531px; overflow:auto;"><img src="<?= USERS_IMG_PATH.'originals/'.$sPicture ?>" width="<?= $iWidth ?>" height="<?= $iHeight ?>" id="ProfileThumb" alt="" title="" /></div>
	  </div>

	  <div class="buttonsBar">
	    <input type="submit" id="BtnSave" value="" class="btnSave" />
	    <input type="button" value="" class="btnCancel" onclick="window.parent.hideLightview( );" />
	  </div>
	  </form>
	</div>
<!--  Body Section Ends Here  -->


  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbPicture->close( );

	@ob_end_flush( );
?>