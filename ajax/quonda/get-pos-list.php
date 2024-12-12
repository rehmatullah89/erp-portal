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

	$Vendor = IO::intValue("Vendor");
	$Po     = IO::strValue("Po");
        $Report = IO::intValue("Report");
        $Stage  = IO::strValue("Stage");
	$sPos   = array( );


        if(@in_array($Report, array(34)) && $Stage == 'F')
            $sSQL = "SELECT id, CONCAT(order_no, ' ', order_status) AS _Po FROM tbl_po WHERE vendor_id='$Vendor' AND order_no LIKE '%{$Po}%' AND mgf_status='A' ORDER BY _Po LIMIT 100";
        else
            $sSQL = "SELECT id, CONCAT(order_no, ' ', order_status) AS _Po FROM tbl_po WHERE vendor_id='$Vendor' AND order_no LIKE '%{$Po}%' ORDER BY _Po LIMIT 100";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPo = $objDb->getField($i, 0);
		$sPo = $objDb->getField($i, 1);

		$sPos[] = array("id" => $iPo, "name" => $sPo);
	}

	print @json_encode($sPos);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>