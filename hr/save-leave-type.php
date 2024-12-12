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

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sSQL  = ("SELECT * FROM tbl_leave_types WHERE type LIKE '".IO::strValue("Type")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_leave_types");

		$sSQL = ("INSERT INTO tbl_leave_types (id, type) VALUES ('$iId', '".IO::strValue("Type")."')");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "LEAVE_TYPE_ADDED");

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "LEAVE_TYPE_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>