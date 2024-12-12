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

	$CurId    = IO::intValue("CurId");
	$CurOrder = IO::intValue("CurOrder");
	$NewId    = IO::intValue("NewId");
	$NewOrder = IO::intValue("NewOrder");


	$objDb->execute("BEGIN");

	$sSQL  = "UPDATE tbl_seasons SET position='$CurOrder' WHERE id='$NewId'";
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL  = "UPDATE tbl_seasons SET position='$NewOrder' WHERE id='$CurId'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$_SESSION['Flag'] = "SEASON_POSITION_UPDATED";

		$objDb->execute("COMMIT");
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