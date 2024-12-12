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


//	if (IO::intValue("Vendor") == 13)
//		$_SESSION['Flag'] = "LINE_NO_ALLOWED";

//	else
	{
		$sSQL  = ("SELECT * FROM tbl_lines WHERE line LIKE '".IO::strValue("Line")."' AND vendor_id='".IO::intValue("Vendor")."' AND unit_id='".IO::intValue("Unit")."' AND floor_id='".IO::intValue("Floor")."' AND type_id='".IO::intValue("Type")."'");
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$iId = getNextId("tbl_lines");

			$sSQL = ("INSERT INTO tbl_lines (id, vendor_id, unit_id, floor_id, type_id, line) VALUES ('$iId', '".IO::intValue("Vendor")."', '".IO::intValue("Unit")."', '".IO::intValue("Floor")."', '".IO::intValue("Type")."', '".IO::strValue("Line")."')");

			if ($objDb->execute($sSQL) == true)
				redirect($_SERVER['HTTP_REFERER'], "LINE_ADDED");

			else
				$_SESSION['Flag'] = "DB_ERROR";
		}

		else
			$_SESSION['Flag'] = "LINE_EXISTS";
	}


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>
