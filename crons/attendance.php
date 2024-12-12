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

	@session_start( );

	@ini_set('display_errors', 0);
	@ini_set('log_errors', 0);
	@error_reporting(0);

	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);



	$sBaseDir = "C:/wamp/www/portal/";

	@require_once($sBaseDir."requires/configs.php");
	@require_once($sBaseDir."requires/db.class.php");
	@require_once($sBaseDir."requires/common-functions.php");
	@require_once($sBaseDir."requires/PHPMailer/class.phpmailer.php");


	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$sToday        = date("Y-m-d");
	$sDesignations = getDbValue("GROUP_CONCAT(id SEPARATOR ',')", "tbl_designations", "FIND_IN_SET(department_id, '15,41,31')");
	$sQuondaUsers  = getDbValue("GROUP_CONCAT(id SEPARATOR ',')", "tbl_users", "FIND_IN_SET(designation_id, '$sDesignations') AND status='A' AND NOT FIND_IN_SET(id, '20,27,81,61,25,48') AND (email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com')");


	$sSQL = "UPDATE tbl_attendance SET time_out='17:30:00', remarks='Auto Time-Out' WHERE `date`<='$sToday' AND (ISNULL(time_out) OR time_out='00:00:00' OR time_out='') AND NOT FIND_IN_SET(user_id, '$sQuondaUsers')";
	$objDb->execute($sSQL);


	$sSQL = "UPDATE tbl_attendance SET time_in='09:00:00', remarks='Auto Time-In' WHERE `date`<='$sToday' AND (ISNULL(time_in) OR time_in='00:00:00' OR time_in='') AND NOT FIND_IN_SET(user_id, '$sQuondaUsers')";
	$objDb->execute($sSQL);



	$sActiveUsers = getDbValue("GROUP_CONCAT(user_id SEPARATOR ',')", "tbl_attendance", "`date`='$sToday' AND (ISNULL(time_out) OR time_out='00:00:00' OR time_out='') AND FIND_IN_SET(user_id, '$sQuondaUsers')");


	$sSQL = "UPDATE tbl_attendance SET time_out='23:59:59', date_time=NOW( ) WHERE `date`='$sToday' AND (ISNULL(time_out) OR time_out='00:00:00' OR time_out='') AND FIND_IN_SET(user_id, '$sQuondaUsers')";
	$objDb->execute($sSQL);


	$sSQL = "INSERT INTO tbl_attendance (`date`, user_id, `entry`, time_in, time_out, date_time) (SELECT DATE_ADD('$sToday', INTERVAL 1 DAY), id, '0', '00:00:01', '00:00:00', NOW( ) FROM tbl_users WHERE FIND_IN_SET(id, '$sActiveUsers'))";
	$objDb->execute($sSQL);


	// Email to HR the list of Users with 24 Hrs Attendance
	$s24HrUsers = getDbValue("GROUP_CONCAT(user_id SEPARATOR ',')", "tbl_attendance", "`date`='$sToday' AND time_in='00:00:01' AND time_out='23:59:59'");
	$sUsers     = "";

	$sSQL = "SELECT name, email FROM tbl_users WHERE FIND_IN_SET(id, '$s24HrUsers')";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sName  = $objDb->getField($i, "name");
		$sEmail = $objDb->getField($i, "email");

		$sUsers .= "{$sName} - {$sEmail}<br />";
	}


	if ($sUsers != "")
	{
		$objEmail = new PHPMailer( );

		$objEmail->Subject  = ("Employees List with 24 Working Hours (".formatDate($sToday).")");
		$objEmail->MsgHTML("Please find the list of employees who worked 24 Hrs today: <br /><br />{$sUsers}");
		$objEmail->AddAddress("ummara.mushtaq@apparelco.com", "Ummara Mushtaq");
		$objEmail->Send( );
	}



	$objDb->close( );
	$objDbGlobal->close( );
?>