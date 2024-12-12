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

	authenticateUser( );

	if ($_SESSION['Country'] == 18)
		$sDateTime = @mktime((date("H") + 1), date("i"), date("s"), date("m"), date("d"), date("Y"));

	else
		$sDateTime = @mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));

	$sDate = date("Y-m-d", $sDateTime);


	$sSQL = "UPDATE tbl_attendance SET time_out='17:30:00', remarks='Auto Time-Out' WHERE `date`='$sDate' AND time_out='00:00:00'";
	$objDb->execute($sSQL);

	redirect(SITE_URL."attendance/");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>