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

	if ($sUserRights['Edit'] != "Y")
	{
		print "ERROR|-|You havn\'t enough Rights to modify the selected Data.\n";
		exit( );
	}

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sSQL  = ("SELECT * FROM tbl_terms_of_delivery WHERE terms LIKE '".IO::strValue("Terms")."' AND id!='".IO::intValue('Id')."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$sSQL = ("UPDATE tbl_terms_of_delivery SET terms='".IO::strValue('Terms')."' WHERE id='".IO::intValue('Id')."'");
		$objDb->execute($sSQL);
	}

	$sSQL = ("SELECT terms FROM tbl_terms_of_delivery WHERE id='".IO::intValue('Id')."'");
	$objDb->query($sSQL);

	print $objDb->getField(0, 0);

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>