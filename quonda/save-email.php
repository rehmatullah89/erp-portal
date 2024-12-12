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


	$Name         = IO::strValue("Name");
	$Email        = IO::strValue("Email");
        $Language     = IO::strValue("Language");
	$Vendors      = @implode(",", IO::getArray("Vendors"));
	$AuditStages  = @implode(",", IO::getArray("AuditStages"));
	$AuditResults = @implode(",", IO::getArray("AuditResults"));

	$sSQL = "SELECT * FROM tbl_qa_emails WHERE vendors='$Vendors' AND audit_stages='$AuditStages' AND email LIKE '$Email'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_qa_emails");

		$sSQL = "INSERT INTO tbl_qa_emails (id, vendors, audit_stages, audit_results, name, email, language) VALUES
		                                   ('$iId', '$Vendors', '$AuditStages', '$AuditResults', '$Name', '$Email', '$Language')";

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "QA_EMAIL_ADDED");

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "QA_EMAIL_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>