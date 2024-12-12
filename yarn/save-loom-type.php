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

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sSQL  = ("SELECT * FROM tbl_loom_types WHERE type LIKE '".IO::strValue("LoomType")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_loom_types");

		$sSQL = ("INSERT INTO tbl_loom_types (id, type, capacity) VALUES ('$iId', '".IO::strValue("LoomType")."', '".IO::floatValue("Capacity")."')");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "LOOM_TYPE_ADDED");

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "LOOM_TYPE_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>