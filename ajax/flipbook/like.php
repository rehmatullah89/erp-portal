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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Product = IO::intValue('Product');


	if (getDbValue("COUNT(*)", "tbl_fb_likes", "product_id='$Product' AND user_id='{$_SESSION['UserId']}'") == 0)
  	{
		$sSQL = "INSERT INTO tbl_fb_likes SET product_id='$Product', user_id='{$_SESSION['UserId']}', date_time=NOW( )";

		if ($objDb->execute($sSQL))
			print "LIKED";

		else
			print "ERROR";
	}

	else
	{
		$sSQL = "DELETE FROM tbl_fb_likes WHERE product_id='$Product' AND user_id='{$_SESSION['UserId']}'";

		if ($objDb->execute($sSQL))
			print "REMOVED";

		else
			print "ERROR";
	}

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>