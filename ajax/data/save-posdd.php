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


	$PoId    = IO::intValue('PoId');
	$ColorId = IO::intValue('ColorId');
	$PoSDD   = IO::strValue('PoSDD');
	$sPoSDD  = date("Y-m-d", strtotime($PoSDD));


	if ($sUserRights['Edit'] == "Y" && $sPoSDD != "1970-01-01")
	{
		$sSQL = "UPDATE tbl_po_colors SET posdd='$sPoSDD' WHERE po_id='$PoId' AND id='$ColorId'";
		$objDb->execute($sSQL);
	}


	$sSQL = "SELECT posdd FROM tbl_po_colors WHERE po_id='$PoId' AND id='$ColorId'";
	$objDb->query($sSQL);

	print $objDb->getField(0, 0);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>