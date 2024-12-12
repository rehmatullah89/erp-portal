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
        
        $Id                 = IO::intValue("Id");
        
        if ($Id > 0)
        {
            $sSQL = "SELECT contact_person, person_phone, person_email, person_fax, port_required from tbl_suppliers WHERE id='$Id'";
            $objDb->query($sSQL);
            
            $sCpName    = $objDb->getField(0, "contact_person");
            $sCpPhone   = $objDb->getField(0, "person_phone");
            $sCpEmail   = $objDb->getField(0, "person_email");
            $sCpFax     = $objDb->getField(0, "person_fax");
            $sCpPortReq = $objDb->getField(0, "port_required");
            
            print "$sCpName|-|$sCpEmail|-|$sCpPhone|-|$sCpFax|-|$sCpPortReq";
        }

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>