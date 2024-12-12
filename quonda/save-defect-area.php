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

	@require_once("../requires/session.php");

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sSQL  = ("SELECT * FROM tbl_defect_areas WHERE area LIKE '".IO::strValue("DefectArea")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_defect_areas");

		$sSQL = ("INSERT INTO tbl_defect_areas (id, area, area_zh, area_tr, area_de, area_ur, area_kh, area_ph, area_vn, area_id, stages, reports)
   		                                VALUES ('$iId', '".IO::strValue("DefectArea")."', '".IO::strValue("DefectAreaZh")."', '".IO::strValue("DefectAreaTr")."', '".IO::strValue("DefectAreaDe")."', '".IO::strValue("DefectAreaUr")."', '".IO::strValue("DefectAreaKh")."', '".IO::strValue("DefectAreaPh")."', '".IO::strValue("DefectAreaVn")."', '".IO::strValue("DefectAreaId")."', '". implode(",", IO::getArray("Stages"))."', '". implode(",", IO::getArray("Reports"))."')");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "DEFECT_AREA_ADDED");

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "DEFECT_AREA_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>