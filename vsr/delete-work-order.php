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

	if ($sUserRights['Delete'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Id = IO::intValue('Id');


	$objDb->execute("BEGIN");


	$sSQL  = "DELETE FROM tbl_vsr2 WHERE id='$Id'";
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_vsr_details WHERE work_order_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_vsr_data WHERE work_order_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		$_SESSION['Flag'] = "WORK_ORDER_DELETED";
	}

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		$objDb->execute("ROLLBACK");
	}

	header("Location: {$_SERVER['HTTP_REFERER']}");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>