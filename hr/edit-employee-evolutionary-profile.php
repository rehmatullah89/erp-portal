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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id       = IO::intValue('Id');
	$sReferer = $_SERVER['HTTP_REFERER'];

	$sSQL = "SELECT name, joining_date, designation_id FROM tbl_users WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect($sReferer, "DB_ERROR");

	$sName        = $objDb->getField(0, "name");
	$sJoiningDate = $objDb->getField(0, "joining_date");
	$iDesignation = $objDb->getField(0, "designation_id");
	$sOffice      = $objDb->getField(0, "_Office");
	$sCountry     = $objDb->getField(0, "_Country");


	$sSQL = "SELECT designation, department_id, reporting_to FROM tbl_designations WHERE id='$iDesignation'";
	$objDb->query($sSQL);

	$sDesignation  = $objDb->getField(0, 'designation');
	$iDepartment   = $objDb->getField(0, 'department_id');
	$iReportingTo  = $objDb->getField(0, 'reporting_to');

	$sDepartment  = getDbValue("department", "tbl_departments", "id='$iDepartment'");
	$sReportingTo = getDbValue("designation", "tbl_designations", "id='$iReportingTo'");



	$sSQL = "SELECT * FROM tbl_user_evolutionary_profile WHERE user_id='$Id'";

	if ($objDb->query($sSQL) == false)
		redirect($sReferer, "DB_ERROR");

	if ($objDb->getCount( ) == 0)
	{
		$sSQL  = "INSERT INTO tbl_user_evolutionary_profile (user_id, date_time) VALUES ('$Id', NOW( ))";
		$objDb->execute($sSQL);
	}


	$sSQL = "SELECT COUNT(*) FROM tbl_user_responsibilities_score WHERE user_id='$Id'";

	if ($objDb->query($sSQL) == true && $objDb->getField(0, 0) == 0)
	{
		if (getDbValue("COUNT(1)", "tbl_user_responsibilities", "department_id='$iDepartment'") > 0)
			$sSQL = "INSERT INTO tbl_user_responsibilities_score (responsibility_id, user_id, comments, score) (SELECT id, '$Id', '', '0' FROM tbl_user_responsibilities WHERE department_id='$iDepartment')";

		else
			$sSQL = "INSERT INTO tbl_user_responsibilities_score (responsibility_id, user_id, comments, score) (SELECT id, '$Id', '', '0' FROM tbl_user_responsibilities WHERE department_id='0')";

		$bFlag = $objDb->execute($sSQL);
	}


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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="MainDiv">
  <div id="PageLeftBorder">
    <div id="PageRightBorder">

<!--  Message Section Starts Here  -->
<?
	@include($sBaseDir."includes/messages.php");
?>
<!--  Message Section Ends Here  -->

      <div id="PageContents">

<!--  Header Section Starts Here  -->
<?
	@include($sBaseDir."includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Navigation Section Starts Here  -->
<?
	@include($sBaseDir."includes/navigation.php");
?>
<!--  Navigation Section Ends Here  -->


<!--  Body Section Starts Here  -->
	    <div id="Body">
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr valign="top">
			  <td width="100%">
			    <h1><img src="images/h1/hr/employee-evolutionary-profile.jpg" width="447" height="20" vspace="10" alt="" title="" /></h1>

			    <form name="frmData" id="frmData" method="post" action="hr/save-employee-evolutionary-profile.php" class="frmOutline" onsubmit="$('BtnSave').disable( );">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $sReferer ?>" />

			    <h2>Employee Information</h2>
			    <table width="95%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="140">Employee Name</td>
				    <td width="20" align="center">:</td>
				    <td><b><?= $sName ?></b></td>
				  </tr>

				  <tr>
					<td>Joining Date</td>
					<td align="center">:</td>
					<td><?= formatDate($sJoiningDate) ?></td>
				  </tr>

				  <tr>
					<td>Designation</td>
					<td align="center">:</td>
					<td><?= $sDesignation ?></td>
				  </tr>

				  <tr>
					<td>Department</td>
					<td align="center">:</td>
					<td><?= $sDepartment ?></td>
				  </tr>

				  <tr>
					<td>Reporting To</td>
					<td align="center">:</td>
					<td><?= $sReportingTo ?></td>
				  </tr>

				  <tr valign="top">
				    <td>Core Responsibilities</td>
				    <td align="center">:</td>
				    <td><textarea name="CoreResponsibilities" rows="4" style="width:95%;"><?= $sCoreResponsibilities ?></textarea></td>
				  </tr>

				  <tr>
				    <td>Career Growth Potential</td>
				    <td align="center">:</td>
				    <td><input type="text" name="CareerGrowthPotential" value="<?= $sCareerGrowthPotential ?>" maxlength="20" size="15" class="textbox" /> (max 30%)</td>
				  </tr>
				</table>

				<br />

<?
	$sClass = array("evenRow", "oddRow");

	$sSQL = "SELECT responsibility_id, comments, score,
	                (SELECT responsibility FROM tbl_user_responsibilities WHERE id=tbl_user_responsibilities_score.responsibility_id) AS _Responsibility
	         FROM tbl_user_responsibilities_score
	         WHERE user_id='$Id'
	         ORDER BY responsibility_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>
			    <input type="hidden" name="ResponsibilitiesCount" value="<?= $iCount ?>" />

			    <div class="tblSheet" style="border:none; padding:0px;">
			      <table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
				    <tr class="headerRow">
				      <td width="36%">Primary Responsibilities / Major Activities</td>
				      <td width="36%">&nbsp; Comments</td>
				      <td width="28%" class="center">Scorecard (Weight 10 points max)</td>
				    </tr>
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iResponsibilityId = $objDb->getField($i, "responsibility_id");
		$sResponsibility   = $objDb->getField($i, "_Responsibility");
		$sComments         = $objDb->getField($i, "comments");
		$iScore            = $objDb->getField($i, "score");
?>

				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td>
				        <input type="hidden" name="ResponsibilityId<?= $i ?>" value="<?= $iResponsibilityId ?>" />
				        <span id="Responsibility<?= $i ?>"><?= ($i + 1) ?>). <?= $sResponsibility ?></span>
<?
/*
		if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y")
		{
?>

						<script type="text/javascript">
						<!--
						    var objEditor<?= $i ?> = new Ajax.InPlaceEditor('Responsibility<?= $i ?>', 'ajax/hr/update-employee-responsibility.php', { cancelControl:'button', okText:'  Ok  ', cancelText:'Cancel', clickToEditText:'Click to Edit', externalControl:'Edit<?= $i ?>', highlightcolor:'<?= HOVER_ROW_COLOR ?>', highlightendcolor:'<?= $sColor[($i % 2)] ?>', callback:function(form, value) { return 'UserId=<?= $Id ?>&ResponsibilityId=<?= $iResponsibilityId ?>&Responsibility=' + encodeURIComponent(value) }, onEnterEditMode:function(form, value) { $('Responsibility<?= $i ?>').focus( ); } });
						-->
						</script>
<?
		}
*/
?>
				      </td>

				      <td class="center"><input type="text" name="Comments<?= $i ?>" value="<?= $sComments ?>" maxlength="255" size="48" class="textbox" /></td>

				      <td class="center">
				        <select name="Score<?= $i ?>">
				          <option value=""></option>
<?
		for ($j = 1; $j <= 10; $j ++)
		{
?>
				          <option value="<?= $j ?>"<?= (($j == $iScore) ? ' selected' : '') ?>><?= $j ?></option>
<?
		}
?>
				        </select>
				      </td>
				    </tr>
<?
	}
?>
				  </table>
			    </div>

			    <h2>Individual Development Plan</h2>
			    &nbsp; Development Goals - Examples of Performance Behaviours - Success / Failures<br />
			    <br />


			    <table width="98%" cellspacing="0" cellpadding="2" border="0" align="center">
				  <tr>
				    <td width="25%"><b>Development Goals</b></td>
				    <td width="25%"><b>To be Completed BY</b></td>
				    <td width="25%"><b>How?</b></td>
				    <td width="25%"><b>Supervisor Comments</b></td>
				  </tr>

				  <tr>
				    <td><textarea name="DevelopmentGoals" rows="5" style="width:95%;"><?= $sDevelopmentGoals ?></textarea></td>
				    <td><textarea name="CompletedBy" rows="5" style="width:95%;"><?= $sCompletedBy ?></textarea></td>
				    <td><textarea name="How" rows="5" style="width:95%;"><?= $sHow ?></textarea></td>
				    <td><textarea name="SupervisorComments" rows="5" style="width:95%;"><?= $sSupervisorComments ?></textarea></td>
				  </tr>

				  <tr>
				    <td colspan="4" height="10"></td>
				  </tr>

				  <tr>
				    <td><b>Dedication / Commitment to Matrix</b> - Incidents / Examples</td>
				    <td><b>Achievement of Critical Objectives</b> - Incidents / Examples</td>
				    <td><b>Reconciliation & Issue Ecalation Resolution</b> - Incidents / Examples</td>
				    <td><b>My Goals & Objectives</b><br />Supervisor Comments</td>
				  </tr>

				  <tr>
				    <td><textarea name="Dedication" rows="3" style="width:95%;"><?= $sDedication ?></textarea></td>
				    <td><textarea name="Achievements" rows="3" style="width:95%;"><?= $sAchievements ?></textarea></td>
				    <td><textarea name="Reconciliation" rows="3" style="width:95%;"><?= $sReconciliation ?></textarea></td>
				    <td><textarea name="MyGoals" rows="3" style="width:95%;"><?= $sMyGoals ?></textarea></td>
				  </tr>
				</table>

				<br />
				<h2>Recommendations:</h2>
				&nbsp; <b>Related Experience Performance / Training Required ti Achieve Promotion / Not Achieve Promotion / Get Demoted:</b><br />
				<br />

			    <table width="98%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="210">Training</td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="Training" value="<?= $sTraining ?>" maxlength="255" size="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Comments from Reporting Manager</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Comments" value="<?= $sComments ?>" maxlength="255" size="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Mid-Year Review Scores</td>
				    <td align="center">:</td>

				    <td>
					  <select name="MidYearScore">
					    <option value=""></option>
<?
	for ($i = 1; $i <= 10; $i ++)
	{
?>
				        <option value="<?= $i ?>"<?= (($i == $iMidYearScore) ? ' selected' : '') ?>><?= $i ?></option>
<?
	}
?>
					  </select>
				    </td>
				  </tr>

				  <tr>
				    <td>Annual Review Scores</td>
				    <td align="center">:</td>

				    <td>
					  <select name="AnnualScore">
					    <option value=""></option>
<?
	for ($i = 1; $i <= 10; $i ++)
	{
?>
				        <option value="<?= $i ?>"<?= (($i == $iAnnualScore) ? ' selected' : '') ?>><?= $i ?></option>
<?
	}
?>
					  </select>
				    </td>
				  </tr>
				</table>

				<br />

			    <div class="tblSheet" style="border:none; padding:0px;">
			      <table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
				    <tr class="headerRow">
				      <td width="20%" class="center">KPI's</td>
				      <td width="20%" class="center">Q1</td>
				      <td width="20%" class="center">Q2</td>
				      <td width="20%" class="center">Q3</td>
				      <td width="20%" class="center">Q4</td>
				    </tr>

				    <tr class="evenRow">
				      <td class="center">Scores</td>

				      <td class="center">
				        <select name="KpiQ1">
				          <option value=""></option>
<?
	for ($i = 1; $i <= 10; $i ++)
	{
?>
				        <option value="<?= $i ?>"<?= (($i == $iKpiQ1) ? ' selected' : '') ?>><?= $i ?></option>
<?
	}
?>
				        </select>
				      </td>

				      <td class="center">
				        <select name="KpiQ2">
				          <option value=""></option>
<?
	for ($i = 1; $i <= 10; $i ++)
	{
?>
				        <option value="<?= $i ?>"<?= (($i == $iKpiQ2) ? ' selected' : '') ?>><?= $i ?></option>
<?
	}
?>
				        </select>
				      </td>

				      <td class="center">
				        <select name="KpiQ3">
				          <option value=""></option>
<?
	for ($i = 1; $i <= 10; $i ++)
	{
?>
				        <option value="<?= $i ?>"<?= (($i == $iKpiQ3) ? ' selected' : '') ?>><?= $i ?></option>
<?
	}
?>
				        </select>
				      </td>

				      <td class="center">
				        <select name="KpiQ4">
				          <option value=""></option>
<?
	for ($i = 1; $i <= 10; $i ++)
	{
?>
				        <option value="<?= $i ?>"<?= (($i == $iKpiQ4) ? ' selected' : '') ?>><?= $i ?></option>
<?
	}
?>
				        </select>
				      </td>
				    </tr>
				  </table>
				</div>

			    <div class="buttonsBar">
			      <input type="submit" id="BtnSave" value="" class="btnSave" />
			      <input type="button" value="" class="btnBack" onclick="document.location='<?= $sReferer ?>';" />
			    </div>
			    </form>

			    <br />
			    <b>Note:</b> Fields marked with an asterisk (*) are required.<br/>
			  </td>
			</tr>
		  </table>

<?
	@include($sBaseDir."includes/my-profile.php");
?>
        </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include($sBaseDir."includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

      </div>
    </div>
  </div>
</div>

<!--  Bottom Bar Section Starts Here  -->
<?
	@include($sBaseDir."includes/bottom-bar.php");
?>
<!--  Bottom Bar Section Ends Here  -->

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>