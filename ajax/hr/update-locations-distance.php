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

	$sSQL  = ("SELECT * FROM tbl_visit_locations_distance WHERE from_location_id='".IO::intValue("FromLocation")."' AND to_location_id='".IO::intValue("ToLocation")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sSQL = ("UPDATE tbl_visit_locations_distance SET distance='".IO::floatValue('Distance')."' WHERE from_location_id='".IO::intValue("FromLocation")."' AND to_location_id='".IO::intValue("ToLocation")."'");
		$objDb->execute($sSQL);
	}

	$sSQL = ("SELECT distance FROM tbl_visit_locations_distance WHERE from_location_id='".IO::intValue("FromLocation")."' AND to_location_id='".IO::intValue("ToLocation")."'");
	$objDb->query($sSQL);

	print $objDb->getField(0, 0);

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>