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

	$sSQL  = ("SELECT * FROM tbl_categories WHERE category LIKE '".IO::strValue("Category")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_categories");

		$sSQL = ("INSERT INTO tbl_categories (id, category, lead_time_1000pcs, lead_time_2500pcs, lead_time_5000pcs, knitting, linking, dyeing, cutting, print_embroidery, stitching, washing, packing, weaving, leather_import, leather_inspection,	lamination, sorting, bladder_attachment, finishing, yarn, quality, sizing, lab_testing) VALUES ('$iId', '".IO::strValue("Category")."', '".IO::intValue("LeadTime1000")."', '".IO::intValue("LeadTime2500")."', '".IO::intValue("LeadTime5000")."', '".IO::intValue("Knitting")."', '".IO::intValue("Linking")."', '".IO::intValue("Dyeing")."', '".IO::intValue("Cutting")."', '".IO::intValue("PrintEmbroidery")."', '".IO::intValue("Stitching")."', '".IO::intValue("Washing")."', '".IO::intValue("Packing")."', '".IO::intValue("Weaving")."', '".IO::intValue("LeatherImport")."', '".IO::intValue("LeatherInspection")."', '".IO::intValue("Lamination")."', '".IO::intValue("Sorting")."', '".IO::intValue("BladderAttachment")."', '".IO::intValue("Finishing")."', '".IO::intValue("Yarn")."', '".IO::intValue("Quality")."', '".IO::intValue("Sizing")."', '".IO::intValue("LabTesting")."')");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "CATEGORY_ADDED");

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "CATEGORY_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>