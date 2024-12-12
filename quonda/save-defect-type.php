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

	$sSQL  = ("SELECT * FROM tbl_defect_types WHERE `type` LIKE '".IO::strValue("DefectType")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_defect_types");

		$sSQL = ("INSERT INTO tbl_defect_types (id, `type`, type_zh, type_tr, type_de, type_ur, type_kh, type_ph, type_vn, type_id, color, stages)
                        		        VALUES ('$iId', '".IO::strValue("DefectType")."', '".IO::strValue("DefectTypeZh")."', '".IO::strValue("DefectTypeTr")."', '".IO::strValue("DefectTypeDe")."', '".IO::strValue("DefectTypeUr")."', '".IO::strValue("DefectTypeKh")."', '".IO::strValue("DefectTypePh")."', '".IO::strValue("DefectTypeVn")."', '".IO::strValue("DefectTypeId")."', '".IO::strValue("Color")."', '". implode(",", IO::getArray("Stages"))."')");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "DEFECT_TYPE_ADDED");

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "DEFECT_TYPE_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>