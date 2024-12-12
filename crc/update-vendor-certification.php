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

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id             = IO::intValue("Id");
	$OldCertificate = IO::strValue("OldCertificate");

	if ($_FILES['Certificate']['name'] != "")
	{
		$sCertificate = ($Id."-".IO::getFileName($_FILES['Certificate']['name']));

		if (!@move_uploaded_file($_FILES['Certificate']['tmp_name'], ($sBaseDir.VENDOR_CERTIFICATIONS_DIR.$sCertificate)))
				$sCertificate = "";

			else
				$sCertificateSql = ", certificate='$sCertificate' ";
	}



	$sSQL = ("UPDATE tbl_vendor_certifications SET vendor_id='".IO::intValue("Vendor")."', certificate_id='".IO::intValue("Certification")."', from_date='".((IO::strValue("FromDate") == "") ? "0000-00-00" : IO::strValue("FromDate"))."', to_date='".((IO::strValue("ToDate") == "") ? "0000-00-00" : IO::strValue("ToDate"))."' $sCertificateSql WHERE id='$Id'");

	if ($objDb->execute($sSQL) == true)
	{
		if ($OldCertificate != "" && $sCertificate != "" && $sCertificate != $OldCertificate)
			@unlink($sBaseDir.VENDOR_CERTIFICATIONS_DIR.$OldCertificate);

		redirect($_SERVER['HTTP_REFERER'], "VENDOR_CERTIFICATION_UPDATED");
	}

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		if ($sCertificate != "" && $sCertificate != $OldCertificate)
			@unlink($sBaseDir.VENDOR_CERTIFICATIONS_DIR.$sCertificate);
	}

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>