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

	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id = IO::intValue('Id');


	$iCommentId = getNextId("tbl_measurement_comments");


	$objDb->execute("BEGIN");

	$sSQL  = "INSERT INTO tbl_measurement_comments (id, merchandising_id, office_id, comments, status, ip_address, user_id, date_time)
		                                    VALUES ('$iCommentId', '$Id', '".IO::intValue("Office")."', '".IO::strValue("Comments")."', '".IO::strValue("Status")."', '{$_SESSION['REMOTE_ADDR']}', '0', NOW( ))";
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true && IO::strValue("Status") != "")
	{
		$sStatus = getDbValue("status", "tbl_comment_sheets", "merchandising_id='$Id'");

		if ($sStatus != "R")
		{
			$sSQL  = ("UPDATE tbl_comment_sheets SET status='".IO::strValue("Status")."' WHERE merchandising_id='$Id'");
			$bFlag = $objDb->execute($sSQL);
		}
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		redirect($_SERVER['HTTP_REFERER'], "SAMPLING_COMMENTS_SAVED");
	}

	else
	{
		$objDb->execute("ROLLBACK");

		redirect($_SERVER['HTTP_REFERER'], "DB_ERROR");
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>