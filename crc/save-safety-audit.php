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
	$objDb2       = new Database( );


	$Vendor         = IO::intValue("Vendor");
	$Auditors       = IO::getArray("Auditors");
	$Representative = IO::strValue("Representative");
	$AuditDate      = IO::strValue("AuditDate");
	$AuditHours     = IO::strValue("AuditHours");
	$AuditMinutes   = IO::strValue("AuditMinutes");


	$sSQL  = ("SELECT * FROM tbl_safety_audits WHERE vendor_id='$Vendor' AND audit_date='$AuditDate'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$objDb->execute("BEGIN");


		$iAudit = getNextId("tbl_safety_audits");

		$sSQL  = ("INSERT INTO tbl_safety_audits (id, vendor_id, auditors, representative, audit_date, audit_time, salaried_staff, contract_staff, male_staff, female_staff, created, created_by, modified, modified_by)
		                                      VALUES ('$iAudit', '$Vendor', '".@implode(",", $Auditors)."', '$Representative', '$AuditDate', '{$AuditHours}:{$AuditMinutes}:00', '".IO::intValue("SalariedStaff")."', '".IO::intValue("ContractStaff")."', '".IO::floatValue("MaleStaff")."', '".IO::floatValue("FemaleStaff")."', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')");
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$sSQL = "SELECT id FROM tbl_safety_questions ORDER BY position";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iQuestion = $objDb->getField($i, "id");


				$iEntry = getNextId("tbl_safety_audit_details");

				$sSQL  = "INSERT INTO tbl_safety_audit_details (id, audit_id, question_id, rating, comments) VALUES ('$iEntry', '$iAudit', '$iQuestion', '0', '')";
				$bFlag = $objDb2->execute($sSQL);
			}
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			redirect("edit-safety-audit.php?Id={$iAudit}&Step=1", "SAFETY_AUDIT_ADDED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION['Flag'] = "DB_ERROR";
		}
	}

	else
		$_SESSION['Flag'] = "SAFETY_AUDIT_EXISTS";


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>