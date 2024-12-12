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

	$sSQL  = ("SELECT * FROM tbl_user_leaves WHERE user_id='".IO::intValue("Employee")."' AND from_date='".IO::strValue("FromDate")."' AND to_date='".IO::strValue("ToDate")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_user_leaves");

		if ($_FILES['LeaveApp']['name'] != "")
		{
			$sLeaveApp = ($iId."-".IO::getFileName($_FILES['LeaveApp']['name']));

			if (!@move_uploaded_file($_FILES['LeaveApp']['tmp_name'], ($sBaseDir.LEAVE_APPS_DIR.$sLeaveApp)))
					$sLeaveApp = "";
		}

		$sSQL = ("INSERT INTO tbl_user_leaves (id, user_id, leave_type_id, from_date, to_date, details, leave_app, created, created_by, modified, modified_by) VALUES ('$iId', '".IO::intValue("Employee")."', '".IO::intValue("LeaveType")."', '".IO::strValue("FromDate")."', '".IO::strValue("ToDate")."', '".IO::strValue("Details")."', '$sLeaveApp', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "LEAVE_ADDED");

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

			@unlink($sBaseDir.LEAVE_APPS_DIR.$sLeaveApp);
		}
	}

	else
		$_SESSION['Flag'] = "LEAVE_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>