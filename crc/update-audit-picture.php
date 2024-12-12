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

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id         = IO::intValue("Id");
	$OldPicture = IO::strValue("OldPicture");


	if ($_FILES['Picture']['name'] != "")
	{
		$sPicture = ($Id."-".IO::getFileName($_FILES['Picture']['name']));

		if (!@move_uploaded_file($_FILES['Picture']['tmp_name'], ($sBaseDir.CRC_AUDITS_IMG_PATH.$sPicture)))
				$sPicture = "";
	}

	if ($sPicture != "")
		$sPictureSql = ", picture='$sPicture' ";


	$sSQL = ("UPDATE tbl_audit_pictures SET vendor_id   = '".IO::strValue("Vendor")."',
										    category_id = '".IO::strValue("Category")."',
										    question_id = '".IO::strValue("Question")."',
										    title       = '".IO::strValue("Title")."',
										    `date`      = '".IO::strValue("AuditDate")."'
										    $sPictureSql
			  WHERE id='$Id'");

	if ($objDb->execute($sSQL) == true)
	{
		if ($sPicture != "" && $OldPicture != "" && $sPicture != $OldPicture)
			@unlink($sBaseDir.CRC_AUDITS_IMG_PATH.$OldPicture);

		redirect($_SERVER['HTTP_REFERER'], "AUDIT_PICTURE_UPDATED");
	}

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		if ($sPicture != "" && $sPicture != $OldPicture)
			@unlink($sBaseDir.CRC_AUDITS_IMG_PATH.$sPicture);
	}


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>