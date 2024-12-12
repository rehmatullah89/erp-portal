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
	$Color = IO::strValue("Color");
	$AuditId = IO::intValue("AuditId");

	if ($Id == 0)
	{
		print "ERROR|-|Invalid Vendor. Please select the proper Vendor.\n";
		exit;
	}

	$sAdditionalPos = getDbValue ("additional_pos", "tbl_qa_reports", "id = '$AuditId' ");
	$sColors = getDbValue ("colors", "tbl_qa_reports", "id = '$AuditId' ");

	$sSQL = "SELECT pc.color, pc.color AS _TEXT
			 FROM tbl_po po, tbl_po_colors pc
			 WHERE po.id=pc.po_id AND (po.id IN($Pos) OR FIND_IN_SET(po.id, '$sAdditionalPos')) AND pc.style_id='$Id' AND '$sColors' LIKE CONCAT('%', REPLACE(pc.color, ',', ' '), '%') 
			 GROUP BY pc.color";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		print ("OK|-|".$Color);

		for ($i = 0; $i < $iCount; $i ++)
			print ("|-|".$objDb->getField($i, 0)."||".$objDb->getField($i, 1));
	}

	else
		print "ERROR|-|A Database Error occured. Please reload your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>