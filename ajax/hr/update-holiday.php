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

	$Id      = IO::intValue("Id");
	$Date    = IO::strValue("Date");
	$Day     = IO::strValue("Day");
	$Country = IO::intValue("Country");
	$sError  = "";

	if ($Date == "")
		$sError .="- Invalid Date\n";

	if ($Day == "")
		$sError .= "- Invalid Designation\n";


	$sSQL = "SELECT country FROM tbl_countries WHERE id='$Country'";
	$objDb->query($sSQL);

	if ($Country == 0 || $objDb->getCount( ) != 1)
		$sError .="- Invalid Country\n";


	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL = "UPDATE tbl_holidays SET day='$Day' WHERE `date`='$Date' AND country_id='$Country'";

	if ($objDb->execute($sSQL) == true)
		print ("OK|-|$Id|-|<div>The selected Holiday has been Updated successfully.</div>|-|".formatDate($Date)."|-|".stripslashes($Day));

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>