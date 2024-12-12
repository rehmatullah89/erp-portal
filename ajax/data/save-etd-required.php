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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Id           = IO::intValue('Id');
	$EtdRequired  = IO::strValue('EtdRequired');
	$sEtdRequired = date("Y-m-d", strtotime($EtdRequired));


	if ($sUserRights['Edit'] == "Y" && $sEtdRequired != "1970-01-01")
	{
		$sSQL = "SELECT DISTINCT(etd_required) FROM tbl_po_colors WHERE po_id='$Id'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$sOriginalEtd = $objDb->getField(0, 0);


			$objDb->execute("BEGIN");

			$sSQL  = "UPDATE tbl_po_colors SET etd_required='$sEtdRequired' WHERE po_id='$Id'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
			{
				$sSQL  = "UPDATE tbl_po SET shipping_dates='$sEtdRequired', modified=NOW( ), modified_by='{$_SESSION['UserId']}' WHERE id='$Id'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$iId = getNextId("tbl_etd_revisions");

				$sSQL  = "INSERT INTO tbl_etd_revisions (id, po_id, original, revised, user_id, date_time) VALUES ('$iId', '$Id', '$sOriginalEtd', '$sEtdRequired', '{$_SESSION['UserId']}', NOW( ))";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$iLogId = getNextId("tbl_po_log");

				$sSQL  = "INSERT INTO tbl_po_log (id, po_id, user_id, date_time, reason) VALUES ('$iLogId', '$Id', '{$_SESSION['UserId']}', NOW( ), 'ETD Revision')";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
				$objDb->execute("COMMIT");

			else
				$objDb->execute("ROLLBACK");
		}
	}


	$sSQL = "SELECT etd_required FROM tbl_po_colors WHERE po_id='$Id' ORDER BY etd_required LIMIT 1";
	$objDb->query($sSQL);

	print formatDate($objDb->getField(0, 0));

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>