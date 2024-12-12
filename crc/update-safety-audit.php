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
	@require_once("../requires/image-functions.php");

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id    = IO::intValue('Id');
	$Step  = IO::intValue('Step');
	$bFlag = true;


	$_SESSION['Flag'] = "";

	$objDb->execute("BEGIN");


	if ($Step == 0)
	{
		$sSQL  = ("SELECT * FROM tbl_safety_audits WHERE audit_date='".IO::strValue("AuditDate")."' AND vendor_id='".IO::intValue("Vendor")."' AND id!='$Id'");
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$sSQL  = ("UPDATE tbl_safety_audits SET audit_date     = '".IO::strValue("AuditDate")."',
													vendor_id      = '".IO::intValue("Vendor")."',
													auditors       = '".@implode(",", IO::getArray("Auditors"))."',
													representative = '".IO::strValue("Representative")."',
													audit_time     = '".IO::strValue("AuditHours").":".IO::strValue("AuditMinutes").":00',
													salaried_staff = '".IO::intValue("SalariedStaff")."',
													contract_staff = '".IO::intValue("Sweing")."',
													male_staff     = '".IO::floatValue("MaleStaff")."',
													female_staff   = '".IO::floatValue("FemaleStaff")."',
													modified       = NOW( ),
													modified_by    = '{$_SESSION['UserId']}'
					 WHERE id='$Id'");
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
				$Step ++;
		}

		else
			$_SESSION['Flag'] = "SAFETY_AUDIT_EXISTS";
	}

	else if ($Step > 0)
	{
		$Questions = IO::getArray("Question");
		$sPictures = array( );

		for ($i = 0; $i < count($Questions); $i ++)
		{
			$iQuestion = $Questions[$i];


			$sPictureSQL = "";

			if ($_FILES["Picture{$iQuestion}"]['name'] != "")
			{
				$PicField = IO::strValue("PicField{$iQuestion}");
				$iIndex   = str_replace("picture", "", $PicField);

				$Picture  = ($Id."-{$iQuestion}-{$iIndex}-".IO::getFileName($_FILES["Picture{$iQuestion}"]['name']));

				if (@move_uploaded_file($_FILES["Picture{$iQuestion}"]['tmp_name'], ($sBaseDir.SAFETY_AUDITD_DIR.$Picture)))
				{
					$sPictureSQL = ", {$PicField}='{$Picture}' ";

					$sPictures[] = $Picture;
				}
			}



			$sSQL  = ("UPDATE tbl_safety_audit_details SET rating   = '".IO::intValue("Rating{$iQuestion}")."',
														   comments = '".@utf8_encode(IO::strValue("Comments{$iQuestion}"))."'
														   {$sPictureSQL}
					   WHERE id='$iQuestion' AND audit_id='$Id'");
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == false)
				break;
		}


		if ($bFlag == true)
		{
			$Step ++;

			if (getDbValue("title", "tbl_safety_categories", "id='$Step'") == "")
				$Step = -1;
		}
	}

	if ($bFlag == true && $Step == -1)
	{
		$fAvgScore = @round(getDbValue("AVG(IF(rating='1', '80', IF(rating='2', '79', IF(rating='3', '60', '40'))))", "tbl_safety_audit_details", "audit_id='$Id'"), 2);


		$sSQL = "UPDATE tbl_safety_audits SET rating='$fAvgScore' WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}


	if ($_SESSION['Flag'] == "")
	{
		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			if ($Step == -1)
				redirect("safety-audits.php", "SAFETY_AUDIT_UPDATED");

			else
				redirect("edit-safety-audit.php?Id={$Id}&Step={$Step}", "SAFETY_AUDIT_UPDATED");
		}

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

			$objDb->execute("ROLLBACK");

			for($i = 0; $i < count($sPictures); $i ++)
				@unlink($sBaseDir.SAFETY_AUDITD_DIR.$sPictures[$i]);
		}
	}

	header("Location: edit-safety-audit.php?Id={$Id}&Step={$Step}");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>