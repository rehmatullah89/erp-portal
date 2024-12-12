<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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


	$Pos            = IO::strValue("Pos");
        $sProductCodes  = getDbValue("GROUP_CONCAT(DISTINCT(product_code) SEPARATOR ', ')", "tbl_po", "id IN ($Pos)");
        $sPoCategory    = getDbValue("category", "tbl_po", "id IN ($Pos)");
        $iItemNumber    = getDbValue("GROUP_CONCAT(DISTINCT(item_number) SEPARATOR ', ')", "tbl_po", "id IN ($Pos)");
        
        print $sProductCodes.($sProductCategory != ""?"-".$sProductCategory:"")."|-|".$iItemNumber;

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>