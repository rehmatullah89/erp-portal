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

	$sSQL = "SELECT joining_date, designation_id FROM tbl_users WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sJoiningDate = $objDb->getField(0, "joining_date");
		$iDesignation = $objDb->getField(0, "designation_id");
	}


	$sSQL = "SELECT designation, department_id, reporting_to FROM tbl_designations WHERE id='$iDesignation'";
	$objDb->query($sSQL);

	$sDesignation = $objDb->getField(0, 'designation');
	$iDepartment  = $objDb->getField(0, 'department_id');
	$iReportingTo = $objDb->getField(0, 'reporting_to');

	$sDepartment  = getDbValue("department", "tbl_departments", "id='$iDepartment'");
	$sReportingTo = getDbValue("designation", "tbl_designations", "id='$iReportingTo'");


	$sSQL = "SELECT * FROM tbl_user_evolutionary_profile WHERE user_id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sCoreResponsibilities  = $objDb->getField(0, "core_responsibilities");
		$sCareerGrowthPotential = $objDb->getField(0, "career_growth_potential");
		$sDevelopmentGoals      = $objDb->getField(0, "development_goals");
		$sCompletedBy           = $objDb->getField(0, "completed_by");
		$sHow                   = $objDb->getField(0, "how");
		$sSupervisorComments    = $objDb->getField(0, "supervisor_comments");
		$sDedication            = $objDb->getField(0, "dedication");
		$sAchievements          = $objDb->getField(0, "achievements");
		$sReconciliation        = $objDb->getField(0, "reconciliation");
		$sMyGoals               = $objDb->getField(0, "my_goals");
		$sTraining              = $objDb->getField(0, "training");
		$sComments              = $objDb->getField(0, "comments");
		$iMidYearScore          = $objDb->getField(0, "mid_year_score");
		$iAnnualScore           = $objDb->getField(0, "annual_score");
		$iKpiQ1                 = $objDb->getField(0, "kpi_q1");
		$iKpiQ2                 = $objDb->getField(0, "kpi_q2");
		$iKpiQ3                 = $objDb->getField(0, "kpi_q3");
		$iKpiQ4                 = $objDb->getField(0, "kpi_q4");
	}
?>
				  <table border="0" cellpadding="3" cellspacing="0" width="95%" align="center">
				    <tr>
					  <td width="140">Career Growth Potential</td>
					  <td width="20" align="center">:</td>
					  <td><?= $sCareerGrowthPotential ?></td>
				    </tr>

				    <tr valign="top">
					  <td>Core Responsibilities</td>
					  <td align="center">:</td>
					  <td colspan="2"><?= nl2br($sCoreResponsibilities) ?></td>
				    </tr>
				  </table>

				  <br />

				  <div style="margin:0px 0px 0px 1px;">
				    <table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
					  <tr class="sdRowHeader">
					    <td width="40%"><b>Primary Responsibilities /<br />Major Activities</b></td>
					    <td width="32%"><b>Comments</b></td>
					    <td width="28%" class="center"><b>Scorecard<br />(Weight 10 points max)</b></td>
					  </tr>
<?
	$sSQL = "SELECT comments, score,
	                (SELECT responsibility FROM tbl_user_responsibilities WHERE id=tbl_user_responsibilities_score.responsibility_id) AS _Responsibility
	         FROM tbl_user_responsibilities_score
	         WHERE user_id='$Id'
	         ORDER BY responsibility_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sResponsibility = $objDb->getField($i, "_Responsibility");
		$sComments       = $objDb->getField($i, "comments");
		$iScore          = $objDb->getField($i, "score");
?>

					  <tr class="sdRowColor">
					    <td><?= ($i + 1) ?>). <?= $sResponsibility ?></td>
					    <td><?= $sComments ?></td>
					    <td class="center"><?= $iScore ?></td>
					  </tr>
<?
	}
?>
				    </table>
				  </div>

				  <h2>Individual Development Plan</h2>
				  &nbsp; Development Goals - Examples of Performance Behaviours - Success / Failures<br />
				  <br />


				  <table width="98%" cellspacing="0" cellpadding="5" border="0" align="center">
				    <tr class="sdRowHeader">
					  <td width="25%"><b>Development Goals</b></td>
					  <td width="25%"><b>To be Completed BY</b></td>
					  <td width="25%"><b>How?</b></td>
					  <td width="25%"><b>Supervisor Comments</b></td>
				    </tr>

				    <tr class="sdRowColor">
					  <td><?= nl2br($sDevelopmentGoals) ?></td>
					  <td><?= nl2br($sCompletedBy) ?></td>
					  <td><?= nl2br($sHow) ?></td>
					  <td><?= nl2br($sSupervisorComments) ?></td>
				    </tr>

				    <tr>
					  <td colspan="4" height="10"></td>
				    </tr>

				    <tr class="sdRowHeader">
					  <td><b>Dedication / Commitment to Matrix</b> - Incidents / Examples</td>
					  <td><b>Achievement of Critical Objectives</b> - Incidents / Examples</td>
					  <td><b>Reconciliation & Issue Ecalation Resolution</b> - Incidents / Examples</td>
					  <td><b>My Goals & Objectives</b><br />Supervisor Comments</td>
				    </tr>

				    <tr class="sdRowColor">
					  <td><?= nl2br($sDedication) ?></td>
					  <td><?= nl2br($sAchievements) ?></td>
					  <td><?= nl2br($sReconciliation) ?></td>
					  <td><?= nl2br($sMyGoals) ?></td>
				    </tr>
				  </table>

				  <br />
				  <h2>Recommendations:</h2>
				  &nbsp; <b>Related Experience Performance / Training Required ti Achieve Promotion /<br />
				  &nbsp; Not Achieve Promotion / Get Demoted:</b><br />
				  <br />

				  <table width="98%" cellspacing="0" cellpadding="4" border="0" align="center">
				    <tr>
					  <td width="210">Training</td>
					  <td width="20" align="center">:</td>
					  <td><?= $sTraining ?></td>
				    </tr>

				    <tr>
					  <td>Comments from Reporting Manager</td>
					  <td align="center">:</td>
					  <td><?= $sComments ?></td>
				    </tr>

				    <tr>
					  <td>Mid-Year Review Scores</td>
					  <td align="center">:</td>
					  <td><?= $iMidYearScore ?></td>
				    </tr>

				    <tr>
					  <td>Annual Review Scores</td>
					  <td align="center">:</td>
					  <td><?= $iAnnualScore ?></td>
				    </tr>
				  </table>

				  <br />

				  <div style="margin:0px 0px 0px 1px;">
				    <table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
					  <tr class="sdRowHeader">
					    <td width="20%" class="center"><b>KPI's</b></td>
					    <td width="20%" class="center"><b>Q1</b></td>
					    <td width="20%" class="center"><b>Q2</b></td>
					    <td width="20%" class="center"><b>Q3</b></td>
					    <td width="20%" class="center"><b>Q4</b></td>
					  </tr>

					  <tr class="sdRowColor">
					    <td class="center">Scores</td>
					    <td class="center"><?= $iKpiQ1 ?></td>
					    <td class="center"><?= $iKpiQ2 ?></td>
					    <td class="center"><?= $iKpiQ3 ?></td>
					    <td class="center"><?= $iKpiQ4 ?></td>
					  </tr>
				    </table>
				  </div>
