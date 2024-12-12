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
	@require_once("../requires/image-functions.php");

	if ($sUserRights['Add'] != "Y" || $sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id                    = IO::intValue("Id");
	$Referer               = IO::strValue("Referer");
	$CoreResponsibilities  = IO::strValue("CoreResponsibilities");
	$CareerGrowthPotential = IO::strValue("CareerGrowthPotential");
	$DevelopmentGoals      = IO::strValue("DevelopmentGoals");
	$CompletedBy           = IO::strValue("CompletedBy");
	$How                   = IO::strValue("How");
	$SupervisorComments    = IO::strValue("SupervisorComments");
	$Dedication            = IO::strValue("Dedication");
	$Achievements          = IO::strValue("Achievements");
	$Reconciliation        = IO::strValue("Reconciliation");
	$MyGoals               = IO::strValue("MyGoals");
	$Training              = IO::strValue("Training");
	$Comments              = IO::strValue("Comments");
	$MidYearScore          = IO::intValue("MidYearScore");
	$AnnualScore           = IO::intValue("AnnualScore");
	$KpiQ1                 = IO::intValue("KpiQ1");
	$KpiQ2                 = IO::intValue("KpiQ2");
	$KpiQ3                 = IO::intValue("KpiQ3");
	$KpiQ4                 = IO::intValue("KpiQ4");


	$objDb->execute("BEGIN");

	$sSQL = "UPDATE tbl_user_evolutionary_profile SET core_responsibilities='$CoreResponsibilities', career_growth_potential='$CareerGrowthPotential', development_goals='$DevelopmentGoals', completed_by='$CompletedBy', how='$How', supervisor_comments='$SupervisorComments', dedication='$Dedication', achievements='$Achievements', reconciliation='$Reconciliation', my_goals='$MyGoals', training='$Training', comments='$Comments', mid_year_score='$MidYearScore', annual_score='$AnnualScore', kpi_q1='$KpiQ1', kpi_q2='$KpiQ2', kpi_q3='$KpiQ3', kpi_q4='$KpiQ4', date_time=NOW( ), modified_by='{$_SESSION['UserId']}' WHERE user_id='$Id'";
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$iResponsibilitiesCount = IO::intValue("ResponsibilitiesCount");

		for ($i = 0; $i < $iResponsibilitiesCount; $i ++)
		{
			$ResponsibilityId = IO::intValue("ResponsibilityId".$i);
			$Comments         = IO::strValue("Comments".$i);
			$Score            = IO::intValue("Score".$i);

			$sSQL  = "UPDATE tbl_user_responsibilities_score SET comments='$Comments', score='$Score' WHERE user_id='$Id' AND responsibility_id='$ResponsibilityId'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == false)
				break;
		}
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		$_SESSION['Flag'] = "USER_EVOLUTIONARY_PROFILE_UPDATED";

		header("Location: {$Referer}");
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";

		header("Location: edit-employee-evolutionary-profile.php?Id={$Id}&Referer=".urlencode($Referer));
	}

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>