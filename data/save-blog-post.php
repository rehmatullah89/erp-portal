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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$iPostId = getNextId("tbl_blog");

	if ($_FILES['Picture']['name'] != "")
	{
		$sPicture = ($iPostId."-".IO::getFileName($_FILES['Picture']['name']));

		if (@move_uploaded_file($_FILES['Picture']['tmp_name'], ($sBaseDir.BLOG_IMG_PATH.'originals/'.$sPicture)))
		{
			@createCenteredImage(($sBaseDir.BLOG_IMG_PATH.'originals/'.$sPicture), ($sBaseDir.BLOG_IMG_PATH.'medium/'.$sPicture), 585, 205, 0, 0, "240,240,240");
			@createCenteredImage(($sBaseDir.BLOG_IMG_PATH.'originals/'.$sPicture), ($sBaseDir.BLOG_IMG_PATH.'thumbs/'.$sPicture), 290, 205, 0, 0, "240,240,240");
		}
	}


	$sSQL = ("INSERT INTO tbl_blog (id, category_id, title, post, picture, display_order, created, created_by, modified, modified_by)
	                        VALUES ('$iPostId', '".IO::intValue("Category")."', '".IO::strValue("Title")."', '".IO::strValue("Post")."', '$sPicture', '$iPostId', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')");

	if ($objDb->execute($sSQL) == true)
		redirect($_SERVER['HTTP_REFERER'], "BLOG_POST_ADDED");

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		@unlink($sBaseDir.BLOG_IMG_PATH."thumbs/".$sPicture);
		@unlink($sBaseDir.BLOG_IMG_PATH."medium/".$sPicture);
		@unlink($sBaseDir.BLOG_IMG_PATH."originals/".$sPicture);
	}

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>