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


	$Id        = IO::intValue("Id");
	$Date      = IO::strValue("Date");
	$UsCotton  = IO::floatValue("UsCotton");
	$PakCotton = IO::floatValue("PakCotton");
	$sError    = "";

	$sSQL = "SELECT * FROM tbl_cotton_rates WHERE day='$Date'";
	$objDb->query($sSQL);

	if ($Date == "" || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Date. Please select the proper Rates record to Edit.\n";
		exit( );
	}

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL = "UPDATE tbl_cotton_rates SET us_cotton='$UsCotton', pak_cotton='$PakCotton' WHERE day='$Date'";

	if ($objDb->execute($sSQL) == true)
		print ("OK|-|$Id|-|<div>The selected Day Rates Record has been Updated successfully.</div>|-|".formatNumber($UsCotton)."|-|".formatNumber($PakCotton));

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>