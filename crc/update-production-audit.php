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
	//@require_once("../requires/image-functions.php");

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id   = IO::intValue('Id');
	$Step = IO::intValue('Step');


	$_SESSION['Flag'] = "";

	$objDb->execute("BEGIN");


	if ($Step == 0)
	{
		$sSQL  = ("SELECT * FROM tbl_production_audits WHERE audit_date='".IO::strValue("AuditDate")."' AND vendor_id='".IO::intValue("Vendor")."' AND id!='$Id'");
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$sSQL  = ("UPDATE tbl_production_audits SET audit_date     = '".IO::strValue("AuditDate")."',
														vendor_id      = '".IO::intValue("Vendor")."',
														auditors       = '".@implode(",", IO::getArray("Auditors"))."',
														representative = '".IO::strValue("Representative")."',
														audit_time     = '".IO::strValue("AuditHours").":".IO::strValue("AuditMinutes").":00',
														modified       = NOW( ),
														modified_by    = '{$_SESSION['UserId']}'
					 WHERE id='$Id'");
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
				$Step ++;
		}

		else
			$_SESSION['Flag'] = "PRODUCTION_AUDIT_EXISTS";
	}

	else if ($Step > 0)
	{
		$Questions = IO::getArray("Question");

		for ($i = 0; $i < count($Questions); $i ++)
		{
			$iQuestion = $Questions[$i];

			if (@is_array($_REQUEST["Weightage{$iQuestion}"]))
			{
				$Weightage = @array_sum($_REQUEST["Weightage{$iQuestion}"]);
				$Details   = @implode("|-|", $_REQUEST["Weightage{$iQuestion}"]);
			}

			else
			{
				$Weightage = IO::intValue("Weightage{$iQuestion}");
				$Details   = "";
			}


			$sSQL  = ("UPDATE tbl_production_audit_details SET weightage='$Weightage', details='$Details' WHERE id='$iQuestion' AND audit_id='$Id'");
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == false)
				break;
		}


		if ($bFlag == true)
		{
			$Step ++;

			if (getDbValue("title", "tbl_production_categories", "id='$Step'") == "")
				$Step = -1;
		}
	}


	if ($bFlag == true && $Step == -1)
	{
		$iGreen  = getDbValue("COUNT(*)", "tbl_production_audit_details", "weightage='5' AND audit_id='$Id'");
		$iYellow = getDbValue("COUNT(*)", "tbl_production_audit_details", "weightage='3' AND audit_id='$Id'");
		$iRed    = getDbValue("COUNT(*)", "tbl_production_audit_details", "weightage='1' AND audit_id='$Id'");

		$iTotalGrades = ($iGreen + $iYellow + $iRed);


		$fGreen  = @round((($iGreen / $iTotalGrades) * 100), 2);
		$fYellow = @round((($iYellow / $iTotalGrades) * 100), 2);
		$fRed    = @round((($iRed / $iTotalGrades) * 100), 2);

		$fOverallRating = @round(($fGreen + $fYellow), 2);


		$sSQL = "UPDATE tbl_production_audits SET rating='$fOverallRating' WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}


	if ($_SESSION['Flag'] == "")
	{
		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			if ($Step == -1)
				redirect("production-audits.php", "PRODUCTION_AUDIT_UPDATED");

			else
				redirect("edit-production-audit.php?Id={$Id}&Step={$Step}", "PRODUCTION_AUDIT_UPDATED");
		}

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

			$objDb->execute("ROLLBACK");


		}
	}

	header("Location: edit-production-audit.php?Id={$Id}&Step={$Step}");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>