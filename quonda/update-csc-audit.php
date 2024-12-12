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

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Id      = IO::intValue('Id');
	$Referer = urlencode(IO::strValue('Referer'));

	$objDb->execute("BEGIN");

	$sSQL  = ("UPDATE tbl_csc_audits SET audit_date='".IO::strValue("AuditDate")."', audit_result='".IO::strValue('AuditResult')."', sample_size='".IO::intValue("SampleSize")."', quantity='".IO::intValue("Quantity")."', modified=NOW( ), modified_by='{$_SESSION['UserId']}' WHERE id='$Id'");
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$iCount     = IO::intValue("Count");
		$sDefectIds = "0";

		for ($i = 0; $i < $iCount; $i ++)
		{
			$DefectId = IO::intValue("DefectId".$i);
			$Style    = IO::intValue("Style".$i);
			$Code     = IO::intValue("Code".$i);
			$Defects  = IO::intValue("Defects".$i);

			if ($Defects > 0)
			{
				if ($DefectId > 0)
					$sSQL  = "UPDATE tbl_csc_audit_defects SET code_id='$Code', defects='$Defects', style_id='$Style' WHERE id='$DefectId'";

				else
				{
					$DefectId = getNextId("tbl_csc_audit_defects");

					$sSQL = ("INSERT INTO tbl_csc_audit_defects (id, audit_id, code_id, defects, style_id) VALUES ('$DefectId', '$Id', '$Code', '$Defects', '$Style')");
				}

				$bFlag = $objDb->execute($sSQL);
			}

			else
			{
				if ($DefectId > 0)
				{
					$sSQL  = "DELETE FROM tbl_csc_audit_defects WHERE id='$DefectId'";
					$bFlag = $objDb->execute($sSQL);
				}
			}

			if ($DefectId > 0)
				$sDefectIds .= ",{$DefectId}";

			if ($bFlag == false)
				break;
		}

		if ($bFlag == true && $sDefectIds != "0")
		{
			$sSQL  = "DELETE FROM tbl_csc_audit_defects WHERE id NOT IN ($sDefectIds) AND audit_id='$Id'";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true && $iCount == 0)
		{
			$sSQL  = "DELETE FROM tbl_csc_audit_defects WHERE audit_id='$Id'";
			$bFlag = $objDb->execute($sSQL);
		}
	}

	if ($bFlag == true)
	{
		$_SESSION['Flag'] = "CSC_AUDIT_UPDATED";

		$objDb->execute("COMMIT");
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";
	}


	header("Location: edit-csc-audit.php?Id={$Id}&Referer={$Referer}");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>