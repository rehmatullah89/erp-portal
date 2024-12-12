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

	if ($sUserRights['Delete'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Id       = IO::strValue('Id');
	$Question = IO::strValue('Question');
	$Field    = IO::strValue('Field');


	$sSQL  = "SELECT {$Field} FROM tbl_compliance_audit_details WHERE id='$Question' AND audit_id='$Id'";
	$bFlag = $objDb->query($sSQL);

	if ($bFlag == true && $objDb->getCount( ) == 1)
	{
		$sPicture = $objDb->getField(0, 0);


		$sSQL = "UPDATE tbl_compliance_audit_details SET {$Field}='' WHERE id='$Question' AND audit_id='$Id'";
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
			@unlink($sBaseDir.COMPLIANCE_AUDITD_DIR.$sPicture);
	}

	if ($bFlag == true)
		$_SESSION['Flag'] = "COMPLIANCE_PICTURE_DELETED";

	else
		$_SESSION['Flag'] = "DB_ERROR";

	header("Location: {$_SERVER['HTTP_REFERER']}");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>