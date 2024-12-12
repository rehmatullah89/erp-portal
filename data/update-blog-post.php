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
	@require_once("../requires/image-functions.php");

    checkLogin( );

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id          = IO::intValue("Id");
	$OldPicture  = IO::strValue("Picture");
	$Referer     = urlencode(IO::strValue("Referer"));
	$sPicture    = "";
	$sPictureSql = "";

	if ($_FILES['Picture']['name'] != "")
	{
		$sPicture = ($Id."-".IO::getFileName($_FILES['Picture']['name']));

		if (@move_uploaded_file($_FILES['Picture']['tmp_name'], ($sBaseDir.BLOG_IMG_PATH.'originals/'.$sPicture)))
		{
			@createCenteredImage(($sBaseDir.BLOG_IMG_PATH.'originals/'.$sPicture), ($sBaseDir.BLOG_IMG_PATH.'medium/'.$sPicture), 585, 205, 0, 0, "240,240,240");
			@createCenteredImage(($sBaseDir.BLOG_IMG_PATH.'originals/'.$sPicture), ($sBaseDir.BLOG_IMG_PATH.'thumbs/'.$sPicture), 290, 205, 0, 0, "240,240,240");

			$sPictureSql = ", picture='$sPicture'";
		}
	}



	$sSQL = ("UPDATE tbl_blog SET category_id='".IO::intValue("Category")."', title='".IO::strValue("Title")."', post='".IO::strValue("Post")."', modified=NOW( ), modified_by='{$_SESSION['UserId']}' $sPictureSql WHERE id=$Id;");

	if ($objDb->execute($sSQL) == true)
	{
		$_SESSION['Flag'] = "BLOG_POST_UPDATED";

		if ($OldPicture != "" && $sPicture != "" && $OldPicture != $sPicture)
		{
			@unlink($sBaseDir.BLOG_IMG_PATH."thumbs/".$OldPicture);
			@unlink($sBaseDir.BLOG_IMG_PATH."medium/".$OldPicture);
			@unlink($sBaseDir.BLOG_IMG_PATH."originals/".$OldPicture);
		}

		header("Location: ".urldecode($Referer));
	}

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		if ($sPicture != "" && $OldPicture != $sPicture)
		{
			@unlink($sBaseDir.BLOG_IMG_PATH."thumbs/".$sPicture);
			@unlink($sBaseDir.BLOG_IMG_PATH."medium/".$sPicture);
			@unlink($sBaseDir.BLOG_IMG_PATH."originals/".$sPicture);
		}

		header("Location: edit-blog-post.php?Id={$Id}&Referer={$Referer}");
	}

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>