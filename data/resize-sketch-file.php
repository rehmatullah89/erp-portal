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

	$Id      = IO::intValue('Id');
	$Referer = IO::strValue('Referer');

	$sSQL = "SELECT * FROM tbl_styles WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect($Referer, "DB_ERROR");

	$sStyle      = $objDb->getField(0, 'style');
	$sSketchFile = $objDb->getField(0, 'sketch_file');


	@list($iWidth, $iHeight) = @getimagesize($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

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
					this.curCrop = new Cropper.Img('StyleThumb', { ratioDim:{ x:600, y:600 }, minWidth:300, minHeight:300, onloadCoords:{x1:0, y1:0, x2:600, y2:600}, displayOnInit:true, onEndCrop:cropThumb } );

				else
				{
					this.removeCropper( );

					this.curCrop.initialize('StyleThumb', { ratioDim:{ x:600, y:600 }, minWidth:300, minHeight:300, onloadCoords:{x1:0, y1:0, x2:600, y2:600}, displayOnInit:true, onEndCrop:cropThumb } );
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
			    <h1><img src="images/h1/data/styles-listing.jpg" width="196" height="20" vspace="10" alt="" title="" /></h1>

			    <form name="frmData" id="frmData" method="post" action="data/save-sketch-file.php" class="frmOutline" onsubmit="$('BtnSave').disable( );">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />

			    <input type="hidden" name="ThumbX1" id="ThumbX1" value="0" />
			    <input type="hidden" name="ThumbY1" id="ThumbY1" value="0" />
			    <input type="hidden" name="ThumbX2" id="ThumbX2" value="600" />
			    <input type="hidden" name="ThumbY2" id="ThumbY2" value="600" />
			    <input type="hidden" name="ThumbWidth" id="ThumbWidth" value="600" />
			    <input type="hidden" name="ThumbHeight" id="ThumbHeight" value="600" />
			    <input type="hidden" name="ImgWidth" value="600" />
			    <input type="hidden" name="ImgHeight" value="600" />

			    <h2 style="margin-bottom:0px;"><?= $sStyle ?> Sketch/Image</h2>

			    <div style="overflow:hidden; position:relative;">
				  <div style="position:relative; overflow:auto;"><img src="<?= STYLES_SKETCH_DIR.$sSketchFile ?>" width="<?= $iWidth ?>" height="<?= $iHeight ?>" id="StyleThumb" alt="" title="" /></div>
			    </div>

			    <div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" /></div>
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