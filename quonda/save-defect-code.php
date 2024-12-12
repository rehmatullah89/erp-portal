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

	$sSQL  = ("SELECT * FROM tbl_defect_codes WHERE `code` LIKE '".IO::strValue("Code")."' AND report_id='".IO::intValue("Report")."' AND type_id='".IO::intValue("DefectType")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_defect_codes");

		$sSQL = ("INSERT INTO tbl_defect_codes (id, report_id, type_id, `code`, buyer_code, defect, defect_zh, defect_tr, defect_de, defect_ur, defect_kh, defect_ph, defect_vn, defect_id, stages) VALUES
                      		                   ('$iId', '".IO::intValue("Report")."', '".IO::intValue("DefectType")."', '".IO::strValue("Code")."', '".IO::strValue("BuyerCode")."', '".IO::strValue("Defect")."', '".IO::strValue("DefectZh")."', '".IO::strValue("DefectTr")."', '".IO::strValue("DefectDe")."', '".IO::strValue("DefectUr")."', '".IO::strValue("DefectKh")."', '".IO::strValue("DefectPh")."', '".IO::strValue("DefectVn")."', '".IO::strValue("DefectId")."', '". implode(",", IO::getArray("Stages"))."')");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "DEFECT_CODE_ADDED");

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "DEFECT_CODE_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>