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

	$Id  = IO::intValue("Id");
	$Dir = IO::strValue("Dir");

	$sSQL = "SELECT picture FROM tbl_blog WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect(SITE_URL, "DB_ERROR");

	$sPicture = $objDb->getField(0, "picture");

	@list($iWidth, $iHeight) = @getimagesize($sBaseDir.BLOG_IMG_PATH.'originals/'.$sPicture);

	if ($Dir == "Thumbs")
	{
		$iBoxWidth  = 290;
		$iBoxHeight = 205;
	}

	else
	{
		$iBoxWidth  = 585;
		$iBoxHeight = 205;
	}
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
					this.curCrop = new Cropper.Img('ProfileThumb', { minWidth:<?= $iBoxWidth ?>, minHeight:<?= $iBoxHeight ?>, maxWidth:<?= $iBoxWidth ?>, maxHeight:<?= $iBoxHeight ?>, onloadCoords:{x1:0, y1:0, x2:<?= $iBoxWidth ?>, y2:<?= $iBoxHeight ?>}, displayOnInit:true, onEndCrop:cropThumb } );

				else
				{
					this.removeCropper( );

					this.curCrop.initialize('ProfileThumb', { minWidth:<?= $iBoxWidth ?>, minHeight:<?= $iBoxHeight ?>, maxWidth:<?= $iBoxWidth ?>, maxHeight:<?= $iBoxHeight ?>, onloadCoords:{x1:0, y1:0, x2:<?= $iBoxWidth ?>, y2:<?= $iBoxHeight ?>}, displayOnInit:true, onEndCrop:cropThumb } );
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
	  <h2>Crop Picture</h2>
	  <form name="frmPicture" id="frmPicture" method="post" action="data/save-blog-picture.php" class="frmOutline" onsubmit="$('BtnSave').disable( );">
	  <input type="hidden" name="Id" value="<?= $Id ?>" />
	  <input type="hidden" name="Dir" value="<?= $Dir ?>" />
	  <input type="hidden" name="ThumbX1" id="ThumbX1" value="0" />
	  <input type="hidden" name="ThumbY1" id="ThumbY1" value="0" />
	  <input type="hidden" name="ThumbX2" id="ThumbX2" value="<?= $iBoxWidth ?>" />
	  <input type="hidden" name="ThumbY2" id="ThumbY2" value="<?= $iBoxHeight ?>" />
	  <input type="hidden" name="ThumbWidth" id="ThumbWidth" value="<?= $iBoxWidth ?>" />
	  <input type="hidden" name="ThumbHeight" id="ThumbHeight" value="<?= $iBoxHeight ?>" />

	  <div style="overflow:hidden; position:relative;">
	    <div style="position:relative; height:531px; overflow:auto;"><img src="<?= BLOG_IMG_PATH.'originals/'.$sPicture ?>" width="<?= $iWidth ?>" height="<?= $iHeight ?>" id="ProfileThumb" alt="" title="" /></div>
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
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>