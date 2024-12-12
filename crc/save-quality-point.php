<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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
	$objDb2      = new Database( );


	$sSQL  = ("SELECT * FROM tbl_quality_points WHERE area_id='".IO::intValue("Area")."' AND section_id='".IO::intValue("Section")."' AND point LIKE '".IO::strValue("Point")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iPoint = getNextId("tbl_quality_points");

		$sSQL  = ("INSERT INTO tbl_quality_points (id, area_id, section_id, point, position) VALUES ('$iPoint', '".IO::intValue("Area")."', '".IO::intValue("Section")."', '".IO::strValue("Point")."', '$iPoint')");
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
			redirect($_SERVER['HTTP_REFERER'], "QUALITY_POINT_ADDED");

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "QUALITY_POINT_EXISTS";


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>