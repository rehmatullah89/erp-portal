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
	$objDb2      = new Database( );


	$sSQL  = ("SELECT * FROM tbl_quality_audits WHERE vendor_id='".IO::intValue("Vendor")."' AND audit_date='".IO::strValue("AuditDate")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$objDb->execute("BEGIN");


		$iAudit = getNextId("tbl_quality_audits");

		$sSQL  = ("INSERT INTO tbl_quality_audits (id, audit_date, vendor_id, auditors, grade, cutting, sewing, packing, finishing, created, created_by, modified, modified_by)
		                                   VALUES ('$iAudit', '".IO::strValue("AuditDate")."', '".IO::intValue("Vendor")."', '".@implode(",", IO::getArray("Auditors"))."', '', '".IO::intValue("Cutting")."', '".IO::intValue("Sewing")."', '".IO::intValue("Packing")."', '".IO::intValue("Finishing")."', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')");
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$sSQL = "SELECT id FROM tbl_quality_points ORDER BY position";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iPoint = $objDb->getField($i, "id");


				$iQuestion = getNextId("tbl_quality_audit_details");

				$sSQL  = "INSERT INTO tbl_quality_audit_details (id, audit_id, point_id, rating, remarks) VALUES ('$iQuestion', '$iAudit', '$iPoint', '0', '')";
				$bFlag = $objDb2->execute($sSQL);
			}
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			redirect("edit-quality-audit.php?Id={$iAudit}&Step=1", "QUALITY_AUDIT_ADDED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION['Flag'] = "DB_ERROR";
		}
	}

	else
		$_SESSION['Flag'] = "QUALITY_AUDIT_EXISTS";


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>