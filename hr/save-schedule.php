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

	$FromDate  = IO::strValue("FromDate");
	$ToDate    = IO::strValue("ToDate");
	$StartTime = (IO::intValue("StartHour").":".IO::strValue("StartMinutes").":00");
	$EndTime   = (IO::intValue("EndHour").":".IO::strValue("EndMinutes").":00");


	if (strtotime("{$ToDate} {$EndTime}") <= strtotime("{$FromDate} {$StartTime}"))
		$_SESSION['Flag'] = "INVALID_SCHEDULE_END_TIME";

	else if ((int)getDbValue("COUNT(1)", "tbl_qa_reports", "user_id='{$_SESSION['UserId']}' AND (audit_date BETWEEN '$FromDate' AND '$ToDate') AND (('$StartTime' BETWEEN start_time AND end_time) OR ('$EndTime' BETWEEN start_time AND end_time) OR (start_time BETWEEN '$StartTime' AND '$EndTime') OR (end_time BETWEEN '$StartTime' AND '$EndTime'))") > 0)
		$_SESSION['Flag'] = "INVALID_SCHEDULE_TIME";

	else if ((int)getDbValue("COUNT(1)", "tbl_user_schedule", "user_id='{$_SESSION['UserId']}' AND (('$FromDate' BETWEEN from_date AND to_date) OR ('$ToDate' BETWEEN from_date AND to_date)) AND (('$StartTime' BETWEEN start_time AND end_time) OR ('$EndTime' BETWEEN start_time AND end_time) OR (start_time BETWEEN '$StartTime' AND '$EndTime') OR (end_time BETWEEN '$StartTime' AND '$EndTime'))") > 0)
		$_SESSION['Flag'] = "INVALID_SCHEDULE_TIME";

	else
	{
		$iId = getNextId("tbl_user_schedule");

		$sSQL = ("INSERT INTO tbl_user_schedule (id, user_id, location_id, from_date, to_date, start_time, end_time, details, date_time) VALUES ('$iId', '{$_SESSION['UserId']}', '".IO::intValue("Location")."', '$FromDate', '$ToDate', '$StartTime', '$EndTime', '".IO::strValue("Details")."', NOW( ))");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "USER_SCHEDULE_ADDED");

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>