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

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id          = IO::intValue("Id");
	$OldLeaveApp = IO::strValue("OldLeaveApp");

	$sSQL  = ("SELECT * FROM tbl_user_leaves WHERE user_id='".IO::intValue("Employee")."' AND from_date='".IO::strValue("FromDate")."' AND to_date='".IO::strValue("ToDate")."' AND id!='$Id'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		if ($_FILES['LeaveApp']['name'] != "")
		{
			$sLeaveApp = ($Id."-".IO::getFileName($_FILES['LeaveApp']['name']));

			if (!@move_uploaded_file($_FILES['LeaveApp']['tmp_name'], ($sBaseDir.LEAVE_APPS_DIR.$sLeaveApp)))
					$sLeaveApp = "";
		}

		if ($sLeaveApp != "")
			$sLeaveAppSql = ", leave_app='$sLeaveApp' ";

		$sSQL = ("UPDATE tbl_user_leaves SET user_id='".IO::intValue("Employee")."', leave_type_id='".IO::intValue("LeaveType")."', from_date='".IO::strValue("FromDate")."', to_date='".IO::strValue("ToDate")."', details='".IO::strValue("Details")."', modified=NOW( ), modified_by='{$_SESSION['UserId']}' $sLeaveAppSql WHERE id='$Id'");

		if ($objDb->execute($sSQL) == true)
		{
			if ($sLeaveApp != "" && $OldLeaveApp != "" && $sLeaveApp != $OldLeaveApp)
				@unlink($sBaseDir.LEAVE_APPS_DIR.$OldLeaveApp);

			redirect($_SERVER['HTTP_REFERER'], "LEAVE_UPDATED");
		}

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

			if ($sLeaveApp != "" && $sLeaveApp != $OldLeaveApp)
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