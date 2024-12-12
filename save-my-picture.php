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

	$sSQL = "SELECT name, picture FROM tbl_users WHERE id='{$_SESSION['UserId']}'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect(SITE_URL, "DB_ERROR");

	$sName      = $objDb->getField(0, "name");
	$sPicture   = $objDb->getField(0, "picture");

	$iPosition  = @strrpos($sPicture, '.');
	$sExtension = @substr($sPicture, $iPosition);

	switch($sExtension)
	{
		case '.jpg'  : $objPicture = @imagecreatefromjpeg($sBaseDir.USERS_IMG_PATH.'originals/'.$sPicture);
					   break;

		case '.jpeg' : $objPicture = @imagecreatefromjpeg($sBaseDir.USERS_IMG_PATH.'originals/'.$sPicture);
					   break;

		case '.png'  : $objPicture = @imagecreatefrompng($sBaseDir.USERS_IMG_PATH.'originals/'.$sPicture);
					   break;

		case '.gif'  : $objPicture = @imagecreatefromgif($sBaseDir.USERS_IMG_PATH.'originals/'.$sPicture);
					   break;

		default      : $objPicture = @imagecreatefromgd2($sBaseDir.USERS_IMG_PATH.'originals/'.$sPicture);
					   break;
	}

	$ThumbWidth  = IO::intValue('ThumbWidth');
	$ThumbHeight = IO::intValue('ThumbHeight');
	$ThumbX1     = IO::intValue('ThumbX1');
	$ThumbY1     = IO::intValue('ThumbY1');
	$ImgWidth    = IO::intValue('ImgWidth');
	$ImgHeight   = IO::intValue('ImgHeight');

	$objTemp  = @imagecreatetruecolor($ThumbWidth, $ThumbHeight);
	$objThumb = @imagecreatetruecolor($ImgWidth, $ImgHeight);

	@imagecopy($objTemp, $objPicture, 0, 0, $ThumbX1, $ThumbY1, $ThumbWidth, $ThumbHeight);
	@imagecopyresampled($objThumb, $objTemp, 0, 0, 0, 0, $ImgWidth, $ImgHeight, $ThumbWidth, $ThumbHeight);
	@imagejpeg($objThumb, ($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture), 100);
	@imagedestroy($objThumb);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<script type="text/javascript">
<!--

	window.parent.document.getElementById('Pic').innerHTML = "<img src=\"<?= USERS_IMG_PATH.'thumbs/'.$sPicture ?>?<?= @uniqid(rand( )) ?>\" alt=\"<?= $sName ?>\" title=\"<?= $sName ?>\" />";
	window.parent.hideLightview( );

-->
</script>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>