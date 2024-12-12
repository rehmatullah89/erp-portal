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
	@require_once("../requires/image-functions.php");

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$iId = getNextId("tbl_vendor_certifications");

	$sCertificate = "";

	if ($_FILES['Certificate']['name'] != "")
	{
		$sCertificate = ($iId."-".IO::getFileName($_FILES['Certificate']['name']));

		if (!@move_uploaded_file($_FILES['Certificate']['tmp_name'], ($sBaseDir.VENDOR_CERTIFICATIONS_DIR.$sCertificate)))
				$sCertificate = "";
	}

	$sSQL = ("INSERT INTO tbl_vendor_certifications (id, vendor_id, certificate_id, certificate, from_date, to_date, date_time) VALUES
	                                                ('$iId', '".IO::intValue("Vendor")."', '".IO::intValue("Certification")."', '$sCertificate', '".((IO::strValue("FromDate") == "") ? "0000-00-00" : IO::strValue("FromDate"))."', '".((IO::strValue("ToDate") == "") ? "0000-00-00" : IO::strValue("ToDate"))."', NOW( ))");

	if ($objDb->execute($sSQL) == true)
		redirect($_SERVER['HTTP_REFERER'], "VENDOR_CERTIFICATION_ADDED");

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		@unlink($sBaseDir.VENDOR_CERTIFICATIONS_DIR.$sCertificate);
	}

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>