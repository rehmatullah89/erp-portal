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


	$PoId = IO::intValue('PoId');
	$Date = IO::strValue('Date');

	if ($sUserRights['Edit'] == "Y")
	{
		$sSQL = "UPDATE tbl_vsr SET final_audit_date='$Date' WHERE po_id='$PoId'";

		if ($objDb->execute($sSQL) == true)
		{
			$iId      = getNextId("tbl_vsr_remarks");
			$sRemarks = "Final Audit Date Updated";


			$sSQL = "INSERT INTO tbl_vsr_remarks (id, po_id, user_id, remarks, date_time) VALUES ('$iId', '$PoId', '{$_SESSION['UserId']}', '$sRemarks', NOW( ))";
			$objDb->execute($sSQL);
		}
	}

	print "OK";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>