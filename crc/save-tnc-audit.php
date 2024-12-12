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

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2       = new Database( );


	$Vendor         = IO::intValue("Vendor");
        $FollowUpAudit  = IO::intValue("PreviousAudit");
	$Auditors       = IO::getArray("Auditors");
	$AuditDate      = IO::strValue("AuditDate");


	$sSQL = "SELECT * FROM tbl_tnc_audits WHERE vendor_id='$Vendor' AND audit_date='$AuditDate'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$objDb->execute("BEGIN");

		$iAudit = getNextId("tbl_tnc_audits");

		$sSQL  = ("INSERT INTO tbl_tnc_audits (id, vendor_id, auditors, audit_date, follow_up_audit, created, created_by, modified, modified_by)
		                               VALUES ('$iAudit', '$Vendor', '".@implode(",", $Auditors)."', '$AuditDate', '$FollowUpAudit', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')");
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$sSQL  = "INSERT INTO tbl_tnc_audit_details (audit_id, point_id, score, remarks) (SELECT '$iAudit', id, '-1', '' FROM tbl_tnc_points)";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			redirect("edit-tnc-audit.php?Id={$iAudit}&Step=1", "TNC_AUDIT_ADDED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION['Flag'] = "DB_ERROR";
		}
	}

	else
		$_SESSION['Flag'] = "TNC_AUDIT_EXISTS";


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>