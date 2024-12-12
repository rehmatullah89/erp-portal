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

	$Id = IO::intValue('Id');

	$sSQL = "SELECT * FROM tbl_fb_products WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$Style          = $objDb->getField(0, "style");
		$Color          = $objDb->getField(0, "color");
		$Gender         = $objDb->getField(0, "gender");
		$Wash           = $objDb->getField(0, "wash");
		$Weight         = $objDb->getField(0, "weight");
		$Fabric         = $objDb->getField(0, "fabric");
		$FabricContents = $objDb->getField(0, "fabric_contents");
		$Price          = $objDb->getField(0, "price");
		$Description    = $objDb->getField(0, "description");
		$PictureLeft    = $objDb->getField(0, "picture_left");
		$PictureRight   = $objDb->getField(0, "picture_right");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:494px; height:494px;">
	  <h2>Product Details</h2>

	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	    <tr>
		  <td width="100">Style</td>
		  <td width="20" align="center">:</td>
		  <td><?= $Style ?></td>
	    </tr>

	    <tr>
		  <td>Color</td>
		  <td align="center">:</td>
		  <td><?= $Color ?></td>
	    </tr>

	    <tr>
		  <td>Gender</td>
		  <td align="center">:</td>
		  <td><?= $Gender ?></td>
	    </tr>

	    <tr>
		  <td>Fabric</td>
		  <td align="center">:</td>
		  <td><?= $Fabric ?></td>
	    </tr>

	    <tr>
		  <td>Fabric Contents</td>
		  <td align="center">:</td>
		  <td><?= $FabricContents ?></td>
	    </tr>

	    <tr>
		  <td>Wash</td>
		  <td align="center">:</td>
		  <td><?= $Wash ?></td>
	    </tr>

	    <tr>
		  <td>Price</td>
		  <td align="center">:</td>
		  <td><?= $Price ?></td>
	    </tr>

	    <tr>
		  <td>Weight</td>
		  <td align="center">:</td>
		  <td><?= $Weight ?></td>
	    </tr>

	    <tr>
		  <td>Description</td>
		  <td align="center">:</td>
		  <td><?= $Description ?></td>
	    </tr>

	    <tr valign="top">
		  <td>Picture Left</td>
		  <td align="center">:</td>
		  <td><img src="<?= MDL_PRODUCTS_DIR.$PictureLeft ?>" width="100"  /></td>
	    </tr>

	    <tr valign="top">
		  <td>Picture Right</td>
		  <td align="center">:</td>
		  <td><img src="<?= MDL_PRODUCTS_DIR.$PictureRight ?>" width="100" /></td>
	    </tr>
	  </table>

	  <br />


	</div>
<!--  Body Section Ends Here  -->


  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>