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

	$Id     = IO::intValue("Id");
	$Date   = IO::strValue("Date");
	$sError = "";

	$sSQL = "SELECT * FROM tbl_yarn_rates WHERE day='$Date'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Date. Please select the proper Rates record to Edit.\n";
		exit( );
	}

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL = "UPDATE tbl_yarn_rates SET s10        = '".IO::floatValue("S10")."',
									   s20        = '".IO::floatValue("S20")."',
									   s30        = '".IO::floatValue("S30")."',
									   cd10       = '".IO::floatValue("Cd10")."',
									   cd12       = '".IO::floatValue("Cd12")."',
									   cd14       = '".IO::floatValue("Cd14")."',
									   cd16       = '".IO::floatValue("Cd16")."',
									   cd20       = '".IO::floatValue("Cd20")."',
									   cd21       = '".IO::floatValue("Cd21")."',
									   cd30       = '".IO::floatValue("Cd30")."',
									   cm30_cpt   = '".IO::floatValue("Cm30Cpt")."',
									   cm40       = '".IO::floatValue("Cm40")."',
									   cd12_spndx = '".IO::floatValue("Cd12Spndx")."',
									   dsp16_70   = '".IO::floatValue("Dsp1670")."'
		     WHERE day='$Date'";

	if ($objDb->execute($sSQL) == true)
		print ("OK|-|$Id|-|<div>The selected Day Rates Record has been Updated successfully.</div>|-|".formatNumber(IO::floatValue("Cd10"))."|-|".formatNumber(IO::floatValue("Cd12"))."|-|".formatNumber(IO::floatValue("Cd14"))."|-|".formatNumber(IO::floatValue("Cd16"))."|-|".formatNumber(IO::floatValue("Cd20"))."|-|".formatNumber(IO::floatValue("Cd21")));

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>