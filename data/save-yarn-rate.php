<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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

	@require_once("../requires/session.php");

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sSQL  = ("SELECT * FROM tbl_yarn_rates WHERE day='".IO::strValue("Date")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$sSQL = ("INSERT INTO tbl_yarn_rates SET day        = '".IO::strValue("Date")."',
		                                         s10        = '".IO::floatValue("S10")."',
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
		                                         dsp16_70   = '".IO::floatValue("Dsp1670")."'");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "YARN_RATE_ADDED");

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "YARN_RATE_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>