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

	if ($sUserRights['Delete'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id       = IO::strValue('Id');
	$sPicture = "";

	$sSQL  = "SELECT picture FROM tbl_audit_pictures WHERE id='$Id'";
	$bFlag = $objDb->query($sSQL);

	if ($bFlag == true && $objDb->getCount( ) == 1)
		$sPicture = $objDb->getField(0, 0);


	$sSQL = "DELETE FROM tbl_audit_pictures WHERE id='$Id'";

	if ($objDb->execute($sSQL) == true)
	{
		@unlink($sBaseDir.CRC_AUDITS_IMG_PATH.$sPicture);

		$_SESSION['Flag'] = "AUDIT_PICTURE_DELETED";
	}

	else
		$_SESSION['Flag'] = "DB_ERROR";

	header("Location: {$_SERVER['HTTP_REFERER']}");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>