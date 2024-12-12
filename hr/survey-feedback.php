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
	@require_once($sBaseDir."requires/chart.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id          = IO::intValue("Id");
	$Region      = IO::intValue("Region");
	$Departments = IO::getArray("Departments");

	$sEmployeesList    = getList("tbl_users", "id", "name", "(email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@lulusar.com') AND status='A'");
	$sDepartmentsList  = getList("tbl_departments", "id", "department");
	$sDesignationsList = getList("tbl_designations", "id", "designation");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/hr/survey-feedback.js"></script>
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
			    <h1><img src="images/h1/hr/survey-feedback.jpg" width="242" height="20" vspace="10" alt="" title="" /></h1>

<?
	$sSQL = "SELECT title FROM tbl_surveys WHERE id='$Id' AND (user_id='{$_SESSION['UserId']}' OR '{$_SESSION['SurveyAdmin']}'='Y')";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		redirect($_SERVER['HTTP_REFERER'], "DB_ERROR");

	$sSurveyTitle = $objDb->getField(0, 0);
?>
			    <h2 style="margin-bottom:4px;"><?= $sSurveyTitle ?></h2>

			    <form name="frmSearch" id="frmSearch" method="post" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="doSearch( );">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />

			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
					  <td width="50">Region</td>

					  <td width="115">
					    <select name="Region">
						  <option value="">All Regions</option>
<?
	$sSQL = "SELECT id, country FROM tbl_countries WHERE matrix='Y' ORDER BY country";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	  	        		  <option value="<?= $sKey ?>"<?= (($sKey == $Region) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
					  </td>

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div class="tblSheet">
			      <div style="margin:0px 1px 1px 0px;">
			        <table border="0" cellpadding="0" cellspacing="0" width="100%">
			          <tr>
			            <td width="100%" height="30" bgcolor="#888888"><b style="color:#ffffff; padding-left:10px;">Filter Departments</b> &nbsp; <b>( <a href="./" onclick="checkAll( ); return false;" class="sheetLink">Check ALL</a> | <a href="./" onclick="clearAll( ); return false;" class="sheetLink">Clear ALL</a> )</b></td>
			          </tr>

			          <tr>
			            <td bgcolor="#f6f6f6">
			              <div style="padding:10px;">
						    <table border="0" cellpadding="1" cellspacing="0" width="100%">
<?
	$sSQL = "SELECT id, department FROM tbl_departments ORDER BY department";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount;)
	{
?>
							  <tr>
<?
		for ($j = 0; $j < 4; $j ++)
		{
			if ($i < $iCount)
			{
				$sKey   = $objDb->getField($i, 0);
				$sValue = $objDb->getField($i, 1);
?>
							    <td width="22"><input type="checkbox" class="departments" name="Departments[]" value="<?= $sKey ?>" <?= ((@in_array($sKey, $Departments)) ? "checked" : "") ?> /></td>
							    <td><?= $sValue ?></td>
<?
				 $i ++;
			}

			else
			{
?>
							    <td width="22"></td>
							    <td></td>
<?
			}
		}
?>
							  </tr>
<?
	}
?>
							</table>

			              </div>
			            </td>
			          </tr>
			        </table>
			      </div>
			    </div>
			    </form>


			    <div class="tblSheet" style="margin-top:4px;">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass      = array("evenRow", "oddRow");
	$sConditions = "WHERE survey_id='$Id'";

	if (count($Departments) > 0)
		$sConditions .= (" AND user_id IN (SELECT id FROM tbl_users WHERE designation_id IN (SELECT id FROM tbl_designations WHERE department_id IN (".@implode(",", $Departments)."))) ");

	if ($Region > 0)
		$sConditions .= " AND user_id IN (SELECT id FROM tbl_users WHERE country_id='$Region') ";


	$sSQL = "SELECT id, user_id, score, date_time,
	                (SELECT designation_id FROM tbl_users WHERE id=tbl_survey_feedback.user_id) AS _Designation
	         FROM tbl_survey_feedback
	         $sConditions
	         ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="5%">#</td>
				      <td width="22%">Employee</td>
				      <td width="22%">Designation</td>
				      <td width="20%">Department</td>
				      <td width="6%">Score</td>
				      <td width="16%">Date / Time</td>
				      <td width="9%" class="center">Options</td>
				    </tr>
<?
		}

		$iId          = $objDb->getField($i, 'id');
		$iUserId      = $objDb->getField($i, 'user_id');
		$iScore       = $objDb->getField($i, 'score');
		$iDesignation = $objDb->getField($i, '_Designation');
		$sDateTime    = $objDb->getField($i, 'date_time');

		$iDepartment  = getDbValue("department_id", "tbl_designations", "id='$iDesignation'");
?>

				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= $sEmployeesList[$iUserId] ?></td>
				      <td><?= $sDesignationsList[$iDesignation] ?></td>
				      <td><?= $sDepartmentsList[$iDepartment] ?></td>
				      <td><?= $iScore ?></td>
				      <td><?= formatDate($sDateTime, "d-M-Y h:i A") ?></td>

				      <td class="center">
<?
		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="hr/delete-survey-feedback.php?SurveyId=<?= $Id ?>&Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Survey Feedback?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
<?
		}
?>
				        <a href="hr/update-survey-feedback-score.php?SurveyId=<?= $Id ?>&Id=<?= $iId ?>"><img src="images/icons/restore.gif" width="16" height="16" alt="Re-Calculate Score" title="Re-Calculate Score" /></a>
				        &nbsp;
				        <a href="hr/view-survey-feedback.php?SurveyId=<?= $Id ?>&Id=<?= $iId ?>" class="lightview" rel="iframe" title="Survey Feedback # <?= $iId ?> :: :: width: 700, height: 550"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
				      </td>
				    </tr>

<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Survey Feedback Found!</td>
				    </tr>
<?
	}
?>
				  </table>
                </div>

<?
	if ($iCount > 0)
	{
		$iData       = array( );
		$sLabels     = array( );
		$iTotalScore = 0;

		$sSQL = "SELECT * FROM tbl_survey_questions WHERE survey_id='$Id' ORDER BY display_order ASC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId           = $objDb->getField($i, "id");
			$sQuestionType = $objDb->getField($i, "question_type");
			$sAnswerType   = $objDb->getField($i, "answer_type");

			$sLabels[$i] = ("Q-".($i + 1));
			$iMaxScore   = 0;
			$sAnswers    = array( );

			if ($sQuestionType == "Mcq")
			{
				$sMcqChoices = $objDb->getField($i, "mcq_choices");

				$sChoices    = @explode("\r\n", $sMcqChoices);
				$jCount      = count($sChoices);

				for ($j = 0; $j < $jCount; $j ++)
				{
					if (@strrpos($sChoices[$j], '(') === FALSE)
					{
						$sChoice    = $sChoices[$j];
						$iWeightage = 0;
					}

					else
					{
						$sChoice    = substr($sChoices[$j], 0, (@strrpos($sChoices[$j], '(') - 1));
						$iWeightage = (int)substr($sChoices[$j], (@strrpos($sChoices[$j], '(') + 1), -1);
					}


					if ($j == 0)
						$iMaxScore = $iWeightage;

					else if ($iWeightage > $iMaxScore)
						$iMaxScore = $iWeightage;


					$sAnswers['Score'][$j]  = $iWeightage;
					$sAnswers['Choice'][$j] = $sChoice;
				}


				$sSQL = "SELECT answer FROM tbl_survey_answers WHERE question_id='$iId' AND NOT ISNULL(answer) AND feedback_id IN (SELECT id FROM tbl_survey_feedback $sConditions)";
				$objDb2->query($sSQL);

				$jCount = $objDb2->getCount( );

				for ($j = 0; $j < $jCount; $j ++)
				{
					$sAnswer = $objDb2->getField($j, 0);

					for ($k = 0; $k < count($sAnswers['Choice']); $k ++)
					{
						if ($sAnswers['Choice'][$k] == $sAnswer)
							$iData[$k][$i] ++;
					}
				}


				$iTotalScore += $iMaxScore;
			}

			else if ($sQuestionType == "Open")
			{

			}

			else if ($sQuestionType == "Matrix")
			{
				$sColumnHeadings = $objDb->getField($i, "column_headings");
				$sRowHeadings    = $objDb->getField($i, "row_headings");

				$sColumnChoices  = @explode("\r\n", $sColumnHeadings);
				$cCount          = count($sColumnChoices);

				$sRowChoices     = @explode("\r\n", $sRowHeadings);
				$rCount          = count($sRowChoices);
			}
		}


		$sSQL = "SELECT ROUND(AVG(score), 2) FROM tbl_survey_feedback $sConditions";
		$objDb->query($sSQL);

		$fAvgScore = $objDb->getField(0, 0);


		$objChart = new XYChart(920, 500);
		$objChart->setPlotArea(40, 105, 860, 360, 0xffffff, 0xf8f8f8, Transparent, $objChart->dashLineColor( 0xcccccc, DotLine), $objChart->dashLineColor(0xcccccc, DotLine));

		$objTitle = $objChart->addTitle("\n{$sSurveyTitle}", "verdana.ttf", 17);
		$objTitle->setPos(0,0);

		$objLegend = $objChart->addLegend(70, 65, false);
		$objLegend->setBackground(Transparent);

		$objBarLayer = $objChart->addBarLayer2(Side);
		$objBarLayer->setBarShape(CircleShape);
		$objBarLayer->setBarGap(0.2, TouchBar);

		for ($i = 0; $i < count($iData); $i ++)
			$objBarLayer->addDataSet($iData[$i], -1, ("A".($i + 1)));

		$objChart->addText(5, 10, "Total Score = {$iTotalScore}", "verdanab.ttf", 10, 0x777777);
		$objChart->addText(5, 30, "Avg.  Score = {$fAvgScore}", "verdanab.ttf", 10, 0x333333);

		for ($i = 0; $i < count ($Departments); $i ++)
			$objChart->addText(740, (5 + ($i * 15)), ("- ".$sDepartmentsList[$Departments[$i]]), "verdana.ttf", 10, 0x444444);

		$objChart->xAxis->setLabels($sLabels);
		$objChart->yAxis->setLabelFormat("{value}");
		$objChart->xAxis->setWidth(2);
		$objChart->yAxis->setWidth(2);

		$sChart = $objChart->makeSession("Cumulative");
?>
			    <div class="tblSheet" style="margin-top:4px; padding:1px;">
	  			  <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" />
			    </div>
<?
	}
?>

			    <div class="tblSheet" style="margin-top:4px; padding:1px;">
				  <div class="buttonsBar"><input type="button" value="" class="btnBack" title="Back" onclick="document.location='<?= SITE_URL ?>hr/surveys.php';" /></div>
			    </div>
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