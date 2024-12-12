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

    checkLogin( );

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$PostId   = IO::strValue("PostId");
	$Comments = IO::strValue("Comments");

	$sError = "";

	if ($PostId == 0)
		$sError .= "- Invalid Blog Post<br />";

	if ($Comments == "")
		$sError .= "- Post Comments<br />";

	if ($sError != "")
		backToForm($sError);

	$iId = getNextId("tbl_blog_comments");

	$sSQL = "INSERT INTO tbl_blog_comments (id, post_id, user_id, comments, date_time) VALUES ('$iId', '$PostId', '{$_SESSION['UserId']}', '$Comments', NOW( ))";

	if ($objDb->execute($sSQL) == true)
	{
		$_SESSION['Flag'] = "BLOG_COMMENTS_SAVED";

		header("Location: {$_SERVER['HTTP_REFERER']}");
	}

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		backToForm( );
	}

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>