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

	//checkLogin( );

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sSQL = "UPDATE tbl_user_stats SET logout_date_time=NOW( ), status='0' WHERE id='{$_SESSION['StatsId']}' AND user_id='{$_SESSION['UserId']}'";
	$objDb->execute($sSQL);

	$_SESSION = array( );
	@session_destroy( );

	header("Location: ./");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>