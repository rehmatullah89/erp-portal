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


	$iId = getNextId("tbl_audit_pictures");

	if ($_FILES['Picture']['name'] != "")
	{
		$sPicture = ($iId."-".IO::getFileName($_FILES['Picture']['name']));

		if (!@move_uploaded_file($_FILES['Picture']['tmp_name'], ($sBaseDir.CRC_AUDITS_IMG_PATH.$sPicture)))
				$sPicture = "";
	}


	$sSQL = ("INSERT INTO tbl_audit_pictures SET id          = '$iId',
	                                             vendor_id   = '".IO::strValue("Vendor")."',
	                                             category_id = '".IO::strValue("Category")."',
	                                             question_id = '".IO::strValue("Question")."',
	                                             title       = '".IO::strValue("Title")."',
	                                             `date`      = '".IO::strValue("AuditDate")."',
	                                             picture     = '$sPicture'");

	if ($objDb->execute($sSQL) == true)
		redirect($_SERVER['HTTP_REFERER'], "AUDIT_PICTURE_ADDED");

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		@unlink($sBaseDir.CRC_AUDITS_IMG_PATH.$sPicture);
	}


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>