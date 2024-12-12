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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
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

	$sSQL  = "UPDATE tbl_tnc_points SET position='$CurOrder' WHERE position='$NewId'";
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL  = "UPDATE tbl_tnc_points SET position='$NewOrder' WHERE id='$CurId'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$_SESSION['Flag'] = "TNC_POINT_POSITION_UPDATED";

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