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

	$objDb->execute("BEGIN");

	$sSQL  = "SELECT picture FROM tbl_blog WHERE id=$Id;";
	$bFlag = $objDb->query($sSQL);

	if ($bFlag == true && $objDb->getCount( ) == 1)
		$sPicture = $objDb->getField(0, 0);

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_blog_comments WHERE post_id='$Id'";
		$objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_blog WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		@unlink($sBaseDir.BLOG_IMG_PATH.'thumbs/'.$sPicture);
		@unlink($sBaseDir.BLOG_IMG_PATH.'medium/'.$sPicture);
		@unlink($sBaseDir.BLOG_IMG_PATH.'original/'.$sPicture);

		$_SESSION['Flag'] = "BLOG_POST_DELETED";
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";
	}

	header("Location: {$_SERVER['HTTP_REFERER']}");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>