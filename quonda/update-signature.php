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

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Id           = IO::intValue("Id");
	$OldSignature = IO::strValue("OldSignature");
	$Referer      = urlencode(IO::strValue("Referer"));


	$sSQL  = ("SELECT * FROM tbl_signatures WHERE name LIKE '".IO::strValue("Name")."' AND `type`='".IO::strValue("Type")."' AND brands='".@implode(",", IO::getArray("Brands"))."' AND vendors='".@implode(",", IO::getArray("Vendors"))."' AND id!='$Id'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$sSignatureSql = "";

		if ($_FILES['Signature']['name'] != "")
		{
			$sSignature = ($Id."-".IO::getFileName($_FILES['Signature']['name']));

			if (@move_uploaded_file($_FILES['Signature']['tmp_name'], ($sBaseDir.SIGNATURES_IMG_DIR.$sSignature)))
				$sSignatureSql = ", signature='$sSignature' ";
		}


		$sSQL  = ("UPDATE tbl_signatures SET name    = '".IO::strValue("Name")."',
		                                     type    = '".IO::strValue("Type")."',
		                                     brands  = '".@implode(",", IO::getArray("Brands"))."',
		                                     vendors = '".@implode(",", IO::getArray("Vendors"))."'
		                                     $sSignatureSql
		           WHERE id='$Id'");

		if ($objDb->execute($sSQL) == true)
		{
			if ($sSignature != "" && $OldSignature != "" && $sSignature != $OldSignature)
				@unlink($sBaseDir.SIGNATURES_IMG_DIR.$OldSignature);

			redirect(urldecode($Referer), "SIGNATURE_UPDATED");
		}

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

			if ($sSignature != "" && $sSignature != $OldSignature)
				@unlink($sBaseDir.SIGNATURES_IMG_DIR.$sSignature);
		}
	}

	else
		$_SESSION['Flag'] = "SIGNATURE_EXISTS";


	header("Location: edit-signature.php?Id={$Id}&Referer={$Referer}");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>