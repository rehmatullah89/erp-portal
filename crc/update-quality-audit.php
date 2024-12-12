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
	$objDb2      = new Database( );

	$Id   = IO::intValue('Id');
	$Step = IO::intValue('Step');


	$_SESSION['Flag'] = "";

	$objDb->execute("BEGIN");


	if ($Step == 0)
	{
		$sSQL  = ("SELECT * FROM tbl_quality_audits WHERE audit_date='".IO::strValue("AuditDate")."' AND vendor_id='".IO::intValue("Vendor")."' AND id!='$Id'");
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$sSQL  = ("UPDATE tbl_quality_audits SET audit_date  = '".IO::strValue("AuditDate")."',
													 vendor_id   = '".IO::intValue("Vendor")."',
													 auditors    = '".@implode(",", IO::getArray("Auditors"))."',
													 cutting     = '".IO::intValue("Cutting")."',
													 sewing      = '".IO::intValue("Sewing")."',
													 packing     = '".IO::intValue("Packing")."',
													 finishing   = '".IO::intValue("Finishing")."',
													 modified    = NOW( ),
													 modified_by = '{$_SESSION['UserId']}'
					 WHERE id='$Id'");
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
				$Step ++;
		}

		else
			$_SESSION['Flag'] = "QUALITY_AUDIT_EXISTS";
	}

	else if ($Step > 0)
	{
		$Points = IO::getArray("Point");

		for ($i = 0; $i < count($Points); $i ++)
		{
			$iPoint = $Points[$i];

			$sSQL  = ("UPDATE tbl_quality_audit_details SET rating  = '".IO::intValue("Rating{$iPoint}")."',
															remarks = '".IO::strValue("Remarks{$iPoint}")."'
					   WHERE id='$iPoint' AND audit_id='$Id'");
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == false)
				break;
		}


		if ($bFlag == true)
		{
			$Step ++;

			if (getDbValue("title", "tbl_quality_areas", "id='$Step'") == "")
				$Step = -1;
		}
	}


	if ($bFlag == true && $Step == -1)
	{
		$sSQL = "SELECT SUM(IF(rating='1', '1', '0')) AS _GradeA,
						SUM(IF(rating='2', '1', '0')) AS _GradeB,
						SUM(IF(rating='3', '1', '0')) AS _GradeC,
						SUM(IF(rating='4', '1', '0')) AS _GradeD
				 FROM tbl_quality_audit_details
				 WHERE audit_id='$Id'";
		$objDb->query($sSQL);

		$iGradeA = $objDb->getField(0, "_GradeA");
		$iGradeB = $objDb->getField(0, "_GradeB");
		$iGradeC = $objDb->getField(0, "_GradeC");
		$iGradeD = $objDb->getField(0, "_GradeD");

		$iTotalGrades = ($iGradeA + $iGradeB + $iGradeC + $iGradeD);

		$fGradeA = @round((($iGradeA / $iTotalGrades) * 100), 2);
		$fGradeB = @round((($iGradeB / $iTotalGrades) * 100), 2);
		$fGradeC = @round((($iGradeC / $iTotalGrades) * 100), 2);
		$fGradeD = @round((($iGradeD / $iTotalGrades) * 100), 2);

		$fOverallRating = @round(($fGradeA + $fGradeB), 2);


		$iMaxGrade = $iGradeA;

		if ($iMaxGrade < $iGradeB)
			$iMaxGrade = $iGradeB;

		if ($iMaxGrade < $iGradeC)
			$iMaxGrade = $iGradeC;

		if ($iMaxGrade < $iGradeD)
			$iMaxGrade = $iGradeD;


		if ($iMaxGrade == $iGradeA && $fGradeA >= 85)
			$sGrade = 'A';

		else if ($iMaxGrade == $iGradeA && $fGradeA < 85)
			$sGrade = 'B';

		else if ($iMaxGrade == $iGradeB)
			$sGrade = 'B';

		else if ($iMaxGrade == $iGradeC)
			$sGrade = 'C';

		else
			$sGrade = 'D';


		$sSQL = "UPDATE tbl_quality_audits SET rating='$fOverallRating', grade='$sGrade' WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}


	if ($_SESSION['Flag'] == "")
	{
		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			if ($Step == -1)
				redirect("quality-audits.php", "QUALITY_AUDIT_UPDATED");

			else
				redirect("edit-quality-audit.php?Id={$Id}&Step={$Step}", "QUALITY_AUDIT_UPDATED");
		}

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

			$objDb->execute("ROLLBACK");
		}
	}


	header("Location: edit-quality-audit.php?Id={$Id}&Step={$Step}");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>