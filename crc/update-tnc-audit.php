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
		$sSQL = ("SELECT * FROM tbl_tnc_audits WHERE audit_date='".IO::strValue("AuditDate")."' AND vendor_id='".IO::intValue("Vendor")."' AND id!='$Id'");
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{

			$sSQL  = ("UPDATE tbl_tnc_audits SET audit_date  = '".IO::strValue("AuditDate")."',
												 vendor_id   = '".IO::intValue("Vendor")."',
												 auditors    = '".@implode(",", IO::getArray("Auditors"))."',
												 modified    = NOW( ),
												 modified_by = '{$_SESSION['UserId']}'
					  WHERE id='$Id'");
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
				$Step ++;
		}

		else
			$_SESSION['Flag'] = "TNC_AUDIT_EXISTS";
	}


	else if ($Step > 0)
	{
		$Points = IO::getArray("Point");

		foreach ($Points as $Point)
		{
			$Score   = IO::intValue("Score{$Point}");
			$Remarks = IO::strValue("Remarks{$Point}");


			$sSQL  = "UPDATE tbl_tnc_audit_details SET score   = '$Score',
					                                   remarks = '$Remarks'
					 WHERE id='$Point' AND audit_id='$Id'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == false)
				break;
		}

		if ($bFlag == true)
		{
			$sSQL  = "UPDATE tbl_tnc_audits SET total_score=(SELECT COUNT(1) FROM tbl_tnc_audit_details WHERE audit_id='$Id' AND score>='0') WHERE id='$Id'";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true)
		{
			$sSQL  = "UPDATE tbl_tnc_audits SET score=(SELECT COUNT(1) FROM tbl_tnc_audit_details WHERE audit_id='$Id' AND score='1') WHERE id='$Id'";
			$bFlag = $objDb->execute($sSQL);
		}


		if ($bFlag == true)
		{
			$Step ++;

			if (getDbValue("section", "tbl_tnc_sections", "id='$Step'") == "")
				$Step = -1;
		}
	}


	if ($_SESSION['Flag'] == "")
	{
		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			if ($Step == -1)
				redirect("tnc-audits.php", "TNC_AUDIT_UPDATED");

			else
				redirect("edit-tnc-audit.php?Id={$Id}&Step={$Step}", "TNC_AUDIT_UPDATED");
		}

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

			$objDb->execute("ROLLBACK");
		}
	}

	header("Location: edit-tnc-audit.php?Id={$Id}&Step={$Step}");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>