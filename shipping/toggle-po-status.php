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

	$Id     = IO::intValue("Id");
	$Status = IO::strValue("Status");
	$PO     = IO::getArray("PO");


	$objDb->execute("BEGIN");


	if ($Id > 0 && $Status != "")
		$sSQL = "UPDATE tbl_po SET status='$Status', modified=NOW( ), modified_by='{$_SESSION['UserId']}' WHERE id='$Id'";

	else
		$sSQL = ("UPDATE tbl_po SET status='C', modified=NOW( ), modified_by='{$_SESSION['UserId']}' WHERE id IN (".@implode(",", $PO).")");

	$bFlag = $objDb->execute($sSQL);


	if ($bFlag == true)
	{
		$iLogId = getNextId("tbl_po_log");

		$sSQL  = ("INSERT INTO tbl_po_log (id, po_id, user_id, date_time, reason) VALUES ('$iLogId', '$Id', '{$_SESSION['UserId']}', NOW( ), 'PO Status Changes - ".(($Status == "C") ? "Closed" : "In-Progress")."')");
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		$_SESSION['Flag'] = "PO_STATUS_UPDATED";
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";
	}

	header("Location: {$_SERVER['HTTP_REFERER']}");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>