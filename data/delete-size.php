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

	$Id    = IO::intValue('Id');
	$sPo   = "";
	$bFlag = true;

	$objDb->execute("BEGIN");

	$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_quantities WHERE size_id='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sPo .= (",".$objDb->getField($i, 0));

		if ($sPo != "")
			$sPo = substr($sPo, 1);
	}

	if ($sPo != "")
	{
		$sSQL = "UPDATE tbl_po SET quantity=(SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id=tbl_po.id GROUP BY po_id) WHERE id IN ($sPo)";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_po_quantities WHERE size_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_sizes WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		$_SESSION['Flag'] = "SIZE_DELETED";
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