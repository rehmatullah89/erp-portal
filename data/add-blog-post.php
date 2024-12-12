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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$PostId = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Category = IO::intValue("Category");
		$Title    = IO::strValue("Title");
		$Post     = IO::getFormValue("Post");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/add-blog-post.js"></script>
  <script type="text/javascript" src="scripts/jquery.js"></script>
  <script type="text/javascript" src="scripts/ckeditor/ckeditor.js"></script>
  <script type="text/javascript" src="scripts/ckeditor/adapters/jquery.js"></script>
  <script type="text/javascript" src="scripts/ckfinder/ckfinder.js"></script>

  <script type="text/javascript">
  <!--
  	jQuery.noConflict( );

	jQuery(document).ready(function( )
	{
  		jQuery("#Post").ckeditor({ height:"400px" }, function( ) { CKFinder.setupCKEditor(this, (jQuery("base").attr("href") + "scripts/ckfinder/")); });
  	});
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
			    <h1>Add Blog Post</h1>

			    <form name="frmData" id="frmData" method="post" action="data/save-blog-post.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disable( );">
			    <h2>Blog Post</h2>

			    <table width="98%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="65">Post Title<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="Title" value="<?= $Title ?>" size="50" maxlength="255" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Category<span class="mandatory">*</span></td>
				    <td align="center">:</td>

				    <td>
					  <select name="Category">
					    <option value="">[ select category ]</option>
<?
	$sSQL = "SELECT id, category FROM tbl_blog_categories ORDER BY category";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
		            	<option value="<?= $iKey ?>"<?= (($Category == $iKey) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
				    </td>
				  </tr>

				  <tr>
				    <td>Picture<span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="file" name="Picture" size="25" class="textbox" /></td>
				  </tr>
				</table>

				<br />
			    <h2 style="margin:0px;">Post Details</h2>

			    <table width="100%" cellspacing="0" cellpadding="5" border="0">
				  <tr>
				    <td width="100%"><textarea name="Post" id="Post" style="width:100%; height:400px;"><?= $Post ?></textarea></td>
				  </tr>
				</table>

			    <div class="buttonsBar">
			      <input type="submit" id="BtnSave" value="" class="btnSave" onclick="return validateForm( );" />
			      <input type="button" value="" class="btnBack" onclick="document.location='data/blog.php';" />
			    </div>
			    </form>

			    <br />
			    <b>Note:</b> Fields marked with an asterisk (*) are required.<br/>
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