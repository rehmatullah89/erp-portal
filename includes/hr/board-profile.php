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

	$sSQL = "SELECT name, email, card_id, picture, country_id, joining_date, date_time, designation_id, routine_activities, non_routine_activities,
					(SELECT office FROM tbl_offices WHERE id=tbl_users.office_id) AS _Office,
					(SELECT country FROM tbl_countries WHERE id=tbl_users.country_id) AS _Country
			 FROM tbl_users
			 WHERE id='$Id'";
	$objDb->query($sSQL);

	$sName                 = $objDb->getField(0, "name");
	$sEmail                = $objDb->getField(0, "email");
	$sCardId               = $objDb->getField(0, "card_id");
	$iCountryId            = $objDb->getField(0, "country_id");
	$iDesignation          = $objDb->getField(0, "designation_id");
	$sRoutineActivities    = $objDb->getField(0, 'routine_activities');
	$sNonRoutineActivities = $objDb->getField(0, 'non_routine_activities');
	$sOffice               = $objDb->getField(0, "_Office");
	$sCountry              = $objDb->getField(0, "_Country");
	$sPicture              = $objDb->getField(0, "picture");
	$sJoiningDate          = $objDb->getField(0, "joining_date");

	if ($sJoiningDate == "" || $sJoiningDate == "0000-00-00" || $sJoiningDate == "1970-01-01")
		$sJoiningDate = substr($objDb->getField(0, "date_time"), 0, 10);


	if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
		$sPicture = "default.jpg";


	$sSQL = "SELECT designation, department_id, reporting_to, job_description FROM tbl_designations WHERE id='$iDesignation'";
	$objDb->query($sSQL);

	$sDesignation    = $objDb->getField(0, 'designation');
	$iDepartment     = $objDb->getField(0, 'department_id');
	$iReportingTo    = $objDb->getField(0, 'reporting_to');
	$sJobDescription = $objDb->getField(0, 'job_description');

	$sDepartment  = getDbValue("department", "tbl_departments", "id='$iDepartment'");
	$sReportingTo = getDbValue("designation", "tbl_designations", "id='$iReportingTo'");
?>
			    <div class="tblSheet">
			      <div style="margin:0px 1px 1px 0px;">
			        <table border="0" cellpadding="0" cellspacing="0" width="100%">
			          <tr>
			            <td width="300"><h1 class="darkGray small" style="margin:0px;"><img src="images/h1/hr/basic-profile.jpg" width="140" height="15" vspace="8" alt="" title="" /></h1></td>
			            <td bgcolor="#888888"><b style="color:#ffffff; padding-left:10px;">JOB DESCRIPTION</b></td>
			            <td width="172" bgcolor="#494949"><b style="color:#ffffff; padding-left:10px;">MATRIX ID : <?= $sCardId ?></b></td>
			          </tr>

			          <tr valign="top">
			            <td>
			              <div style="padding:5px 0px 0px 5px;">
			                <table border="0" cellpadding="4" cellspacing="0" width="100%">
			                  <tr>
			                    <td width="90"><b>Name</b></td>
			                    <td><?= $sName ?></td>
			                  </tr>

			                  <tr>
			                    <td><b>Job Title</b></td>
			                    <td><?= $sDesignation ?></td>
			                  </tr>

			                  <tr>
			                    <td><b>Reporting To</b></td>
			                    <td><?= $sReportingTo ?></td>
			                  </tr>

			                  <tr>
			                    <td><b>Department</b></td>
			                    <td><?= $sDepartment ?></td>
			                  </tr>

			                  <tr>
			                    <td><b>Office</b></td>
			                    <td><?= $sOffice ?></td>
			                  </tr>

			                  <tr>
			                    <td><b>Country</b></td>
			                    <td><?= $sCountry ?></td>
			                  </tr>
			                </table>
			              </div>
			            </td>

			            <td bgcolor="#f9f9f9">
			              <div style="padding:10px;">
<?
	$sJobDescription = @utf8_decode($sJobDescription);
	$sJobDescription = @str_replace("\r\n\r\n", "\n", $sJobDescription);
	$sJobDescription = @str_replace("\n\n", "\n", $sJobDescription);
	$sJobDescription = @str_replace("\r\n", "\n", $sJobDescription);
	$sJobDescription = @explode("\n", $sJobDescription);

	if (count($sJobDescription) > 0)
	{
		$bStart     = true;
		$bSubBullet = false;
		$iCount     = count($sJobDescription);

		for ($i = 0; $i < $iCount; $i ++)
		{
			if (substr($sJobDescription[$i], 0, 2) == "o " && $bSubBullet == false)
			{
?>
			      			  <li style="list-style:none;">
			        		    <ul>
<?
				$bSubBullet = true;
			}

			else if (substr($sJobDescription[$i], 0, 2) != "o " && $bSubBullet == true)
			{
?>
			        		    </ul>
			      			  </li>
<?
				$bSubBullet = false;
			}

			if (substr($sJobDescription[$i], 0, 2) == "h ")
			{
				if ($i > 0)
				{
?>
			      		    </ul>
<?
				}
?>
			          	    <div style="padding:5px 0px 5px 0px;"><b><?= substr($sJobDescription[$i], 2) ?></b></div>
<?
				$bStart = true;
			}

			else
			{
				if ($bStart == true)
				{
?>
			      		    <ul class="hr">
<?
					$bStart = false;
				}
?>
			          		  <li><?= ((substr($sJobDescription[$i], 0, 2) == "o ") ? substr($sJobDescription[$i], 2) : $sJobDescription[$i]) ?></li>
<?
			}

			if ($i == ($iCount - 1) && $bSubBullet == true)
			{
?>
			        		    </ul>
			      			  </li>
<?
				$bSubBullet = false;
			}
		}
?>
			      		    </ul>
<?
	}
?>

			                <br />
			                <i style="font-size:10px; color:#777777;"><b>Disclaimer:</b> Matrix Sourcing reserves the right to change the respective job descriptions without any prior reason/justification and reserves the right to assign any employee an assignment/work order it deems necessary in the interest of the company.</i><br />
			        		<br />
			        		[ <a href="hr/export-jd.php?Id=<?= $_SESSION['UserId'] ?>">Export Job Description</a> ]
			              </div>
			            </td>

			            <td>
					      <div id="ProfilePic" style="margin:5px;">
						    <div id="Pic"><img src="<?= USERS_IMG_PATH.'thumbs/'.$sPicture ?>" alt="<?= $sName ?>" title="<?= $sName ?>" /></div>
					      </div>
			            </td>
			          </tr>
			        </table>

			        <br style="line-height:4px;" />

			        <table border="0" cellpadding="8" cellspacing="0" width="100%">
			          <tr>
			            <td width="50%" bgcolor="#666666"><b style="color:#ffffff; padding-left:10px;">ROUTINE ACTIVITIES</b></td>
			            <td width="50%" bgcolor="#888888"><b style="color:#ffffff; padding-left:10px;">NON-ROUTINE ACTIVITIES</b></td>
			          </tr>

			          <tr valign="top">
			            <td>
<?
	$sRoutineActivities = @utf8_decode($sRoutineActivities);
	$sRoutineActivities = @str_replace("\r\n\r\n", "\n", $sRoutineActivities);
	$sRoutineActivities = @str_replace("\n\n", "\n", $sRoutineActivities);
	$sRoutineActivities = @str_replace("\r\n", "\n", $sRoutineActivities);
	$sRoutineActivities = @explode("\n", $sRoutineActivities);

	if (count($sRoutineActivities) > 0)
	{
?>
			      		  <ul class="hr">
<?
		$bSubBullet = false;
		$iCount     = count($sRoutineActivities);

		for ($i = 0; $i < $iCount; $i ++)
		{
			if (substr($sRoutineActivities[$i], 0, 2) == "o " && $bSubBullet == false)
			{
?>
			      			<li style="list-style:none;">
			        		  <ul>
<?
				$bSubBullet = true;
			}

			else if (substr($sRoutineActivities[$i], 0, 2) != "o " && $bSubBullet == true)
			{
?>
			        		  </ul>
			      			</li>
<?
				$bSubBullet = false;
			}
?>
			          		<li><?= ((substr($sRoutineActivities[$i], 0, 2) == "o ") ? substr($sRoutineActivities[$i], 2) : $sRoutineActivities[$i]) ?></li>
<?
			if ($i == ($iCount - 1) && $bSubBullet == true)
			{
?>
			        		  </ul>
			      			</li>
<?
				$bSubBullet = false;
			}
		}
?>
			      		  </ul>
<?
	}
?>
			            </td>

			            <td bgcolor="#f9f9f9">
<?
	$sNonRoutineActivities = @utf8_decode($sNonRoutineActivities);
	$sNonRoutineActivities = @str_replace("\r\n\r\n", "\n", $sNonRoutineActivities);
	$sNonRoutineActivities = @str_replace("\n\n", "\n", $sNonRoutineActivities);
	$sNonRoutineActivities = @str_replace("\r\n", "\n", $sNonRoutineActivities);
	$sNonRoutineActivities = @explode("\n", $sNonRoutineActivities);

	if (count($sNonRoutineActivities) > 0)
	{
?>
			      		  <ul class="hr">
<?
		$bSubBullet = false;
		$iCount     = count($sNonRoutineActivities);

		for ($i = 0; $i < $iCount; $i ++)
		{
			if (substr($sNonRoutineActivities[$i], 0, 2) == "o " && $bSubBullet == false)
			{
?>
			      			<li style="list-style:none;">
			        		  <ul>
<?
				$bSubBullet = true;
			}

			else if (substr($sNonRoutineActivities[$i], 0, 2) != "o " && $bSubBullet == true)
			{
?>
			        		  </ul>
			      			</li>
<?
				$bSubBullet = false;
			}
?>
			          		<li><?= ((substr($sNonRoutineActivities[$i], 0, 2) == "o ") ? substr($sNonRoutineActivities[$i], 2) : $sNonRoutineActivities[$i]) ?></li>
<?
			if ($i == ($iCount - 1) && $bSubBullet == true)
			{
?>
			        		  </ul>
			      			</li>
<?
				$bSubBullet = false;
			}
		}
?>
			      		  </ul>
<?
	}
?>
			            </td>
			          </tr>
			        </table>
			      </div>
			    </div>

			    <br style="line-height:4px;" />

<?
	$sFromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 31), date("Y")));
	$sToDate   = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y")));

	$sSQL = "SELECT SUM(TIME_TO_SEC(TIMEDIFF(logout_date_time, login_date_time))) FROM tbl_user_stats WHERE user_id='$Id' AND (DATE_FORMAT(login_date_time, '%Y-%m-%d') BETWEEN '$sFromDate' AND '$sToDate')";
	$objDb->query($sSQL);

	$iTotalTime = $objDb->getField(0, 0);

	$iWorkingDays = getWorkingDays($sFromDate, $sToDate);

	$iAvgPortalTime = @ceil($iTotalTime / $iWorkingDays);
	$sAvgPortalTime = seconds2Time($iAvgPortalTime);


	$sSQL = "SELECT SEC_TO_TIME(AVG(TIME_TO_SEC(TIMEDIFF(time_out, time_in)))) FROM tbl_attendance WHERE user_id='$Id' AND (`date` BETWEEN '$sFromDate' AND '$sToDate') ";
	$objDb->query($sSQL);

	$sAvgOfficeTime = $objDb->getField(0, 0);


	$iYearLeaves   = 0;
	$iMonthLeaves  = 0;
	$iYearAbsents  = 0;
	$iMonthAbsents = 0;

	for ($i = 1; $i <= date("n"); $i ++)
	{
		$sYear      = date("Y");
		$sMonth     = str_pad($i, 2, '0', STR_PAD_LEFT);
		$iMonthDays = @cal_days_in_month(CAL_GREGORIAN, $i, $sYear);

		for ($j = 1; $j <= $iMonthDays; $j ++)
		{
			if ($sMonth == date("m") && $j >= date("j"))
				break;

			$sDate    = ($sYear."-".$sMonth."-".str_pad($j, 2, '0', STR_PAD_LEFT));
			$iWeekDay = date("N", strtotime($sDate));

			if (strtotime($sDate) < strtotime($sJoiningDate))
				continue;


			$sSQL = "SELECT * FROM tbl_user_leaves WHERE user_id='$Id' AND ('$sDate' BETWEEN from_date AND to_date)";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1)
			{
				$iYearLeaves ++;

				if ($sMonth == date("m"))
					$iMonthLeaves ++;

				continue;
			}

			$sSQL = "SELECT * FROM tbl_attendance WHERE user_id='$Id' AND `date`='$sDate'";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 0)
			{
				//if ( ($iCountryId == 18 && $iWeekDay != 5 && $iWeekDay != 6) || ($iCountryId != 18 && $iWeekDay < 6) )

				if ( (strtotime($sDate) <= strtotime("2010-06-18") && $iCountryId == 18 && $iWeekDay != 5 && $iWeekDay != 6) ||
					 (strtotime($sDate) > strtotime("2010-06-18") && $iCountryId == 18 && $iWeekDay < 6) ||
					 ($iCountryId != 18 && $iWeekDay < 6) )
				{
					$sSQL = "SELECT * FROM tbl_holidays WHERE `date`='$sDate' AND country_id='$iCountryId'";
					$objDb->query($sSQL);

					if ($objDb->getCount( ) == 0)
					{
						$iYearAbsents ++;

						if ($sMonth == date("m"))
							$iMonthAbsents ++;
					}
				}
			}
		}
	}


	$sSQL = "SELECT COUNT(*) FROM tbl_email_stats WHERE sender='$sEmail'";
	$objDb->query($sSQL);

	$iTotalEmails = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(DISTINCT(DATE_FORMAT(date_time, '%Y-%m-%d'))) FROM tbl_email_stats WHERE sender='$sEmail'";
	$objDb->query($sSQL);

	$iTotalDays = $objDb->getField(0, 0);

	$iAvgSentEmails = @ceil($iTotalEmails / $iTotalDays);


	$sSQL = "SELECT COUNT(*) FROM tbl_email_stats WHERE (recipients='$sEmail' OR recipients LIKE '%,{$sEmail}' OR recipients LIKE '{$sEmail},%' OR recipients LIKE '%,{$sEmail},%')";
	$objDb->query($sSQL);

	$iTotalEmails = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(DISTINCT(DATE_FORMAT(date_time, '%Y-%m-%d'))) FROM tbl_email_stats WHERE (recipients='$sEmail' OR recipients LIKE '%,{$sEmail}' OR recipients LIKE '{$sEmail},%' OR recipients LIKE '%,{$sEmail},%')";
	$objDb->query($sSQL);

	$iTotalDays = $objDb->getField(0, 0);

	$iAvgReceivedEmails = @ceil($iTotalEmails / $iTotalDays);
?>
			    <div class="tblSheet">
			      <div style="margin:0px 1px 1px 0px;">
			        <table border="0" cellpadding="0" cellspacing="0" width="100%">
			          <tr>
			            <td width="420"><h1 class="darkGray small" style="margin:0px;"><img src="images/h1/hr/basic-evaluations.jpg" width="190" height="15" vspace="8" alt="" title="" /></h1></td>
			            <td bgcolor="#666666"></td>
			          </tr>

			          <tr valign="top">
			            <td>
			              <div style="padding:5px 0px 0px 5px;">
			                <table border="0" cellpadding="4" cellspacing="0" width="100%">
			                  <tr>
			                    <td width="280"><b>Average Daily Hours on Portal</b></td>
			                    <td><b style="color:#ff0000;"><?= $sAvgPortalTime ?></b></td>
			                  </tr>

			                  <tr>
			                    <td><b>Average Daily Hours within the Office</b></td>
			                    <td><b style="color:#ff0000;"><?= $sAvgOfficeTime ?></b></td>
			                  </tr>

			                  <tr>
			                    <td><b>Total Leaves in Current Year</b></td>
			                    <td><b style="color:#ff0000;"><?= (int)$iYearLeaves ?></b></td>
			                  </tr>

			                  <tr>
			                    <td><b>Total Leaves in Current Month</b></td>
			                    <td><b style="color:#ff0000;"><?= (int)$iMonthLeaves ?></b></td>
			                  </tr>

			                  <tr>
			                    <td><b>Total Absents in Current Year</b></td>
			                    <td><b style="color:#ff0000;"><?= (int)$iYearAbsents ?></b></td>
			                  </tr>

			                  <tr>
			                    <td><b>Total Absents in Current Month</b></td>
			                    <td><b style="color:#ff0000;"><?= (int)$iMonthAbsents ?></b></td>
			                  </tr>

			                  <tr>
			                    <td><b>Average Daily Emails Sent</b></td>
			                    <td><b style="color:#ff0000;"><?= (int)$iAvgSentEmails ?></b></td>
			                  </tr>

			                  <tr>
			                    <td><b>Average Daily Emails Received</b></td>
			                    <td><b style="color:#ff0000;"><?= (int)$iAvgReceivedEmails ?></b></td>
			                  </tr>
			                </table>
			              </div>
			            </td>

			            <td align="center">
<?
	@include("attendance-graph.php");
?>
			            </td>
			          </tr>
			        </table>
			      </div>
			    </div>