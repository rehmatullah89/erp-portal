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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
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


	$sSQL  = ("SELECT * FROM tbl_chemical_compounds WHERE type_id='".IO::intValue("Type")."' AND compound LIKE '".IO::strValue("Compound")."' AND cas_no LIKE '".IO::strValue("CasNo")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_chemical_compounds");

		$sSQL = ("INSERT INTO tbl_chemical_compounds (id, type_id, compound, cas_no) VALUES ('$iId', '".IO::intValue("Type")."', '".IO::strValue("Compound")."', '".IO::strValue("CasNo")."')");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "CHEMICAL_COMPOUND_ADDED");

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "CHEMICAL_COMPOUND_EXISTS";


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>