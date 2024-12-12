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

	if (checkUserRights("board.php", $sModule, "view"))
		header("Location: board.php");

	else if (checkUserRights("hrn.php", $sModule, "view"))
		header("Location: hrn.php");

	else if (checkUserRights("employees.php", $sModule, "view"))
		header("Location: employees.php");

	else if (checkUserRights("attendance.php", $sModule, "view"))
		header("Location: attendance.php");

	else if (checkUserRights("leaves.php", $sModule, "view"))
		header("Location: leaves.php");

	else if (checkUserRights("holidays.php", $sModule, "view"))
		header("Location: holidays.php");

	else if (checkUserRights("leave-types.php", $sModule, "view"))
		header("Location: leave-types.php");

	else if (checkUserRights("departments.php", $sModule, "view"))
		header("Location: departments.php");

	else if (checkUserRights("designations.php", $sModule, "view"))
		header("Location: designations.php");

	else if (checkUserRights("visit-locations.php", $sModule, "view"))
		header("Location: visit-locations.php");

	else if (checkUserRights("location-distances.php", $sModule, "view"))
		header("Location: location-distances.php");

	else if (checkUserRights("visits.php", $sModule, "view"))
		header("Location: visits.php");

	else if (checkUserRights("offices.php", $sModule, "view"))
		header("Location: offices.php");

	else if (checkUserRights("salaries.php", $sModule, "view"))
		header("Location: salaries.php");

	else if (checkUserRights("sms-attendance.php", $sModule, "view"))
		header("Location: sms-attendance.php");

	else if (checkUserRights("calendar.php", $sModule, "view"))
		header("Location: calendar.php");

	else if (checkUserRights("brand-placements.php", $sModule, "view"))
		header("Location: brand-placements.php");

	else if (checkUserRights("surveys.php", $sModule, "view"))
		header("Location: surveys.php");
	
	else if (checkUserRights("activities.php", $sModule, "view"))
		header("Location: activities.php");
	
	else if (checkUserRights("user-activities.php", $sModule, "view"))
		header("Location: user-activities.php");

	else
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>