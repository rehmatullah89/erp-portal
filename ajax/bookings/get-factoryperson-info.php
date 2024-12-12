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
        
        $Id = IO::intValue("Id");
        
        if ($Id > 0)
        {
            $sSQL = "SELECT manager_rep, phone, manager_rep_email, fax from tbl_vendors WHERE id='$Id'";
            $objDb->query($sSQL);
            
            $sCpName    = $objDb->getField(0, "manager_rep");
            $sCpPhone   = $objDb->getField(0, "phone");
            $sCpEmail   = $objDb->getField(0, "manager_rep_email");
            $sCpFax     = $objDb->getField(0, "fax");
            
            print "$sCpName|-|$sCpEmail|-|$sCpPhone|-|$sCpFax";
        }

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>