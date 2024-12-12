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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sSQL  = ("SELECT * FROM tbl_notifications WHERE user_id='{$_SESSION['UserId']}' AND department_id='".IO::intValue("Department")."' AND trigger_id='".IO::intValue("Trigger")."' AND vendor_id='".IO::intValue("Vendor")."' AND brand_id='".IO::intValue("Brand")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_notifications");

		$sSQL = ("INSERT INTO tbl_notifications (id, user_id, department_id, trigger_id, vendor_id, brand_id, alert_types, status, date_time) VALUES ('$iId', '{$_SESSION['UserId']}', '".IO::intValue("Department")."', '".IO::intValue("Trigger")."', '".IO::intValue("Vendor")."', '".IO::intValue("Brand")."', '".@implode(",", IO::getArray("AlertTypes"))."', 'A', NOW( ))");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "NOTIFICATION_ADDED");

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "NOTIFICATION_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>