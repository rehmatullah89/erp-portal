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
	
	
	$sTitle = IO::strValue("Title");

	$sSQL  = ("SELECT * FROM tbl_compliance_audit_type WHERE title LIKE '".$sTitle."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_compliance_audit_type");

		
		if ($sTitle == "")
			$_SESSION['Flag'] = "ERROR";

		else
		{
			$sSQL = ("INSERT INTO tbl_compliance_audit_type (id, title) VALUES ('$iId', '".$sTitle."')");

			if ($objDb->execute($sSQL) == true)
				redirect($_SERVER['HTTP_REFERER'], "VIDEO_ADDED");

			else
			{
				$_SESSION['Flag'] = "DB_ERROR";

				//@unlink($sBaseDir.VIDEO_FILES_DIR.$sVideo);
			}
		}
	}

	else
		$_SESSION['Flag'] = "COMPLIANCE_AUDIT_TYPE_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>