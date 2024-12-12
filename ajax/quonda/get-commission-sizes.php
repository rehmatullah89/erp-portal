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

	$Po         = IO::getArray("Po");
        $Commission = IO::getArray("Commission");
        $sPo        = implode(",", $Po);
        $sCommission= implode(",", $Commission);

	if ($sPo == "")
	{
            print "ERROR|-|Invalid PO. Please select the proper PO.\n";
            exit;
        }
        else
            $Po = $sPo;
	
        $sSubSQL = "";
        
        if($sCommission != "")
            $sSubSQL = " AND FIND_IN_SET(pc.line, '$sCommission') ";

	$sSQL = "SELECT id, size FROM tbl_sizes WHERE id IN (SELECT DISTINCT size_id FROM tbl_po_quantities pq, tbl_po_colors pc WHERE pc.po_id=pq.po_id AND FIND_IN_SET(pq.po_id, '$Po') AND pc.id=pq.color_id AND pq.quantity > 0 $sSubSQL) ORDER BY position";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		print "OK";

		for ($i = 0; $i < $iCount; $i ++)
			print ("|-|".$objDb->getField($i, 0)."|".$objDb->getField($i, 1));
	}

	else
		print "ERROR|-|A Database Error occured. Please reload your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>