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

	$sSQL  = ("SELECT * FROM tbl_qa_checklist WHERE `item` LIKE '".IO::strValue("Item")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_qa_checklist");

		$sSQL = ("INSERT INTO tbl_qa_checklist (id, item, field_type, mandatory, position) VALUES ('$iId', '".IO::strValue("Item")."', '".IO::strValue("FieldType")."', '".IO::strValue("Mandatory")."', '$iId')");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "QA_CHECKLIST_ADDED");
		else
			$_SESSION['Flag'] = "DB_ERROR";
	}
	else
		$_SESSION['Flag'] = "QA_CHECKLIST_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>