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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Parent   = IO::intValue("Brand");
        $Vendor   = IO::intValue("Vendor");
	$Keywords = IO::strValue("Keywords");

        $Brand     = (int)getDbValue("id", "tbl_brands", "parent_id='$Parent'");
	
        $sSubSql = "";
	if ($Brand > 0)
		$sSubSql .= " AND brand_id='$Brand' ";
        
        if ($Vendor > 0)
		$sSubSql .= " AND vendor_id='$Vendor' ";


	print "<ul>";

	$sSQL = "SELECT DISTINCT styles,
                        (SELECT style FROM tbl_styles WHERE id=tbl_po.styles LIMIT 0,1) as _Style    
                            FROM tbl_po WHERE styles IN (SELECT id FROM tbl_styles WHERE style LIKE '%$Keywords%' AND sub_brand_id='$Parent')  $sSubSql ORDER BY _Style LIMIT 25";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iStyle = $objDb->getField($i, 0);
		$sStyle = $objDb->getField($i, 1);

		print ("<li id='{$iStyle}'>{$sStyle}</li>");
	}

	print "</ul>";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>