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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
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
	$objDb = new Database( );

	$File      = IO::strValue("File");
	$ImageId   = IO::intValue("ImageId");
	$AuditId   = IO::intValue('AuditId');
	$AuditDate = IO::strValue('AuditDate');
	$Referer   = urldecode(IO::strValue("Referer"));


	$sSQL = "DELETE FROM tbl_crc_audit_pictures WHERE id='$ImageId' AND audit_id='$AuditId'";

	if ($objDb->execute($sSQL) == true)
	{
		@list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);

		@unlink($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$File);


		redirect($_SERVER['HTTP_REFERER'], "TNC_AUDIT_IMAGE_DELETED");
	}

	else
		redirect($_SERVER['HTTP_REFERER'], "ERROR");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>