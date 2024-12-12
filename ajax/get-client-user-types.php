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
	***********************************************************************************************
	\*********************************************************************************************/

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id         = IO::strValue("Id");
	$List       = IO::strValue("List");

	if ($Id == "")
	{
		print "ERROR|-|Invalid Report. Please select the proper Report.\n";
		exit;
	}

	$sUserTypesList     = getList("tbl_user_types ut, tbl_clients c", "ut.id", "ut.type" , "FIND_IN_SET(ut.id, c.user_types) AND c.code='$Id'");               
        
	if (count($sUserTypesList) > 0)
	{
            print "OK|-|".$List;
            
            foreach ($sUserTypesList as $iUserType => $sUserType)
                print ("|-|".$iUserType."||".$sUserType);
	}
	else
		print "OK|-|".$List."|-|";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>