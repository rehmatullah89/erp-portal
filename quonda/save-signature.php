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


	$sSQL  = ("SELECT * FROM tbl_signatures WHERE name LIKE '".IO::strValue("Name")."' AND `type`='".IO::strValue("Type")."' AND brands='".@implode(",", IO::getArray("Brands"))."' AND vendors='".@implode(",", IO::getArray("Vendors"))."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_signatures");


		if ($_FILES['Signature']['name'] != "")
		{
			$sSignature = ($iId."-".IO::getFileName($_FILES['Signature']['name']));

			if (!@move_uploaded_file($_FILES['Signature']['tmp_name'], ($sBaseDir.SIGNATURES_IMG_DIR.$sSignature)))
					$sSignature = "";
		}



		$sSQL = ("INSERT INTO tbl_signatures (id, name, type, brands, vendors, signature)
	                                  VALUES ('$iId', '".IO::strValue("Name")."', '".IO::strValue("Type")."', '".@implode(",", IO::getArray("Brands"))."', '".@implode(",", IO::getArray("Vendors"))."', '$sSignature')");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "SIGNATURE_ADDED");

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

			@unlink($sBaseDir.SIGNATURES_IMG_DIR.$sSignature);
		}
	}

	else
		$_SESSION['Flag'] = "SIGNATURE_EXISTS";


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>