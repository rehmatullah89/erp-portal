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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id   = IO::intValue("Id");
	$Pos = IO::strValue("Pos");
	$Size = IO::strValue("Size");
	$Colors = IO::strValue("Colors");
	$AuditId = IO::intValue("AuditId");

	if ($Id == 0)
	{
		print "ERROR|-|Invalid Vendor. Please select the proper Vendor.\n";
		exit;
	}

	$sAdditionalPos = getDbValue ("additional_pos", "tbl_qa_reports", "id = '$AuditId' ");

	$sSQL = "SELECT pq.size_id, (SELECT size FROM tbl_sizes WHERE id=pq.size_id) AS _TEXT
			 FROM tbl_po po, tbl_po_colors pc, tbl_po_quantities pq
			 WHERE po.id=pc.po_id AND po.id=pq.po_id AND (po.id IN($Pos) OR FIND_IN_SET(po.id, '$sAdditionalPos')) AND pc.style_id='$Id' AND '$Colors' LIKE CONCAT('%', REPLACE(pc.color, ',', ' '), '%') AND pq.quantity > 0 
			 GROUP BY pq.size_id";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		print ("OK|-|".$Size);

		for ($i = 0; $i < $iCount; $i ++)
			print ("|-|".$objDb->getField($i, 0)."||".$objDb->getField($i, 1));
	}

	else
		print "ERROR|-|A Database Error occured. Please reload your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>