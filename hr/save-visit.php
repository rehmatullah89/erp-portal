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

	$Locations = "67";

	for ($i = 1; $i <= 8; $i ++)
	{
		if (IO::intValue("Location".$i) > 0)
			$Locations .= (",".IO::intValue("Location".$i));
	}


	$iId = getNextId("tbl_user_visits");

	$sSQL = ("INSERT INTO tbl_user_visits (id, user_id, `date`, time_out, time_in, type, locations, date_time) VALUES ('$iId', '".IO::intValue("Employee")."', '".IO::strValue("Date")."', '".IO::strValue("TimeOutHr").":".IO::strValue("TimeOutMin").":00', '".IO::strValue("TimeInHr").":".IO::strValue("TimeInMin").":00', '".IO::strValue("VisitType")."', '$Locations', NOW( ))");

	if ($objDb->execute($sSQL) == true)
		redirect($_SERVER['HTTP_REFERER'], "VISIT_ADDED");

	else
		$_SESSION['Flag'] = "DB_ERROR";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>