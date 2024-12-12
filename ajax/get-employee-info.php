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

	$UserId = IO::intValue("UserId");

	if ($UserId == 0)
	{
		print "ERROR|-|Invalid Employee. Please select the proper Employee.\n";
		exit;
	}

	$sSQL = "SELECT name, email, mobile, phone_ext, (SELECT designation FROM tbl_designations WHERE id=tbl_users.designation_id) AS _Designation, (SELECT country FROM tbl_countries WHERE id=tbl_users.country_id) AS _Country, (SELECT office FROM tbl_offices WHERE id=tbl_users.office_id) AS _Office, (SELECT phone FROM tbl_offices WHERE id=tbl_users.office_id) AS _Phone, (SELECT fax FROM tbl_offices WHERE id=tbl_users.office_id) AS _Fax FROM tbl_users WHERE id='$UserId'";

	if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
	{
		print "OK";
		print ("|-|".$objDb->getField($i, 'name'));
		print ("|-|".$objDb->getField($i, '_Designation'));
		print ("|-|".$objDb->getField($i, '_Country'));
		print ("|-|".$objDb->getField($i, '_Office'));
		print ("|-|".$objDb->getField($i, 'email'));
		print ("|-|".$objDb->getField($i, 'mobile'));
		print ("|-|".$objDb->getField($i, '_Phone'));
		print ("|-|".$objDb->getField($i, 'phone_ext'));
		print ("|-|".$objDb->getField($i, '_Fax'));
	}

	else
		print "ERROR|-|A Database Error occured. Please reload your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>