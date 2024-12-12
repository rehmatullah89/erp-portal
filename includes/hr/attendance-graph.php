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
?>
	  			  <table border="0" cellpadding="6" cellspacing="0" width="100%">
	  			    <tr>
	  			      <td width="200" align="right"><b style="font-size:12px;">Attendance Summary :</b></td>

	  			      <td>
					    <select onchange="showGraph(this.value);">
						  <option value="CurrentMonth">Current Month (<?= date("F, Y") ?>)</option>
						  <option value="LastMonth">Last Month (<?= date("F, Y", mktime(0, 0, 0, (date("m") - 1), date("d"), date("Y"))) ?>)</option>
						  <option value="CurrentYear">Current Year (<?= date("Y") ?>)</option>
						  <option value="LastYear">Last Year (<?= (date("Y") - 1) ?>)</option>
					    </select>

					    <input type="hidden" name="LastGraphId" id="LastGraphId" value="CurrentMonth" />
					  </td>
					</tr>
				  </table>

	  			  <table border="0" cellpadding="3" cellspacing="0" width="90%" align="center">
	  			    <tr>
	  			      <td width="18"></td>
	  			      <td width="11"><div style="height:11px; width:11px; background:#1d84e4;"></div></td>
	  			      <td width="52">Holiday</td>
	  			      <td width="11"><div style="height:11px; width:11px; background:#b6e500;"></div></td>
	  			      <td width="48">Leave</td>
	  			      <td width="11"><div style="height:11px; width:11px; background:#e8192b;"></div></td>
	  			      <td width="52">Absent</td>
	  			      <td width="11"><div style="height:11px; width:11px; background:#777777;"></div></td>
	  			      <td width="52">Present</td>
	  			      <td width="11"><div style="height:11px; width:11px; background:#dddddd;"></div></td>
	  			      <td>Place-holder</td>
	  			    </tr>
	  			  </table>

<?
	$sData   = array( );
	$sLabels = array( );
	$sColors = array( );

	$iMonthDays = @cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));

	for ($i = 1; $i <= $iMonthDays; $i ++)
	{
		$sDate    = (date("Y")."-".date("m")."-".str_pad($i, 2, '0', STR_PAD_LEFT));
		$iWeekDay = date("N", strtotime($sDate));

		if (strtotime($sDate) < strtotime($sJoiningDate))
		{
			$sData[]   = 8.5;
			$sLabels[] = $i;
			$sColors[] = 0xffffff;

			continue;
		}


		if ($i < date("d"))
		{
			$sSQL = "SELECT * FROM tbl_user_leaves WHERE user_id='$Id' AND ('$sDate' BETWEEN from_date AND to_date)";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) >= 1)
			{
				$sData[]   = 8.5;
				$sLabels[] = $i;
				$sColors[] = 0xb6e500;

				continue;
			}


			$sSQL = "SELECT SUM(TIME_TO_SEC(TIMEDIFF(time_out, time_in))) FROM tbl_attendance WHERE user_id='$Id' AND `date`='$sDate'";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1 && $objDb->getField(0, 0) > 0)
			{
				$fTime = $objDb->getField(0, 0);
				$fTime = ($fTime / 60);
				$fTime = ($fTime / 60);

				$sData[]   = @round($fTime, 1);
				$sLabels[] = $i;
				$sColors[] = 0x888888;
			}

			else
			{
				//if ( ($iCountryId == 18 && $iWeekDay != 5 && $iWeekDay != 6) || ($iCountryId != 18 && $iWeekDay < 6) )

				if ( (strtotime($sDate) <= strtotime("2010-06-18") && $iCountryId == 18 && $iWeekDay != 5 && $iWeekDay != 6) ||
				     (strtotime($sDate) > strtotime("2010-06-18") && $iCountryId == 18 && $iWeekDay < 6) ||
				     ($iCountryId != 18 && $iWeekDay < 6) )
				{
					$sSQL = "SELECT * FROM tbl_holidays WHERE `date`='$sDate' AND country_id='$iCountryId'";
					$objDb->query($sSQL);

					if ($objDb->getCount( ) == 1)
					{
						$sData[]   = 8.5;
						$sLabels[] = $i;
						$sColors[] = 0x1d84e4;
					}

					else
					{
						$sData[]   = 8.5;
						$sLabels[] = $i;
						$sColors[] = 0xe8192b;
					}
				}
			}
		}

		else
		{
			//if ( ($iCountryId == 18 && $iWeekDay != 5 && $iWeekDay != 6) || ($iCountryId != 18 && $iWeekDay < 6) )

			if ($iWeekDay < 6)
			{
				$sData[]   = 8.5;
				$sLabels[] = $i;
				$sColors[] = 0xffffff;
			}
		}
	}


	$objChart = new XYChart(462, 190);
	$objChart->setPlotArea(50, 10, 390, 150);

	$objBarLayer = $objChart->addBarLayer3($sData, $sColors);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setAggregateLabelFormat("{value}");
	$objBarLayer->setAggregateLabelStyle("tahoma.ttf", 7);

	$objChart->xAxis->setLabels($sLabels);
	$objChart->xAxis->setLabelStyle("tahoma.ttf", 7);

	$objChart->yAxis->setLabelFormat("{value}");
	$objChart->yAxis->setLabelStyle("tahoma.ttf", 7);

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("CurrentMonth");
?>

	  			  <div id="CurrentMonth" style="display:block;"><img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" /></div>

<?
	$sData   = array( );
	$sLabels = array( );
	$sColors = array( );

	$iLastMonth = ((date("m") == 1) ? 12 : (date("m") - 1));
	$iLastYear  = (($iLastMonth == 12) ? (date("Y") - 1) : date("Y"));
	$sLastMonth = str_pad($iLastMonth, 2, '0', STR_PAD_LEFT);
	$iMonthDays = @cal_days_in_month(CAL_GREGORIAN, $iLastMonth, $iLastYear);

	for ($i = 1; $i <= $iMonthDays; $i ++)
	{
		$sDate    = ($iLastYear."-".$sLastMonth."-".str_pad($i, 2, '0', STR_PAD_LEFT));
		$iWeekDay = date("N", strtotime($sDate));

		if (strtotime($sDate) < strtotime($sJoiningDate))
		{
			$sData[]   = 8.5;
			$sLabels[] = $i;
			$sColors[] = 0xffffff;

			continue;
		}


		$sSQL = "SELECT * FROM tbl_user_leaves WHERE user_id='$Id' AND ('$sDate' BETWEEN from_date AND to_date)";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$sData[]   = 8.5;
			$sLabels[] = $i;
			$sColors[] = 0xb6e500;

			continue;
		}

		$sSQL = "SELECT SUM(TIME_TO_SEC(TIMEDIFF(time_out, time_in))) FROM tbl_attendance WHERE user_id='$Id' AND `date`='$sDate'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1 && $objDb->getField(0, 0) > 0)
		{
			$fTime = $objDb->getField(0, 0);
			$fTime = ($fTime / 60);
			$fTime = ($fTime / 60);

			$sData[]   = @round($fTime, 1);
			$sLabels[] = $i;
			$sColors[] = 0x888888;
		}

		else
		{
			//if ( ($iCountryId == 18 && $iWeekDay != 5 && $iWeekDay != 6) || ($iCountryId != 18 && $iWeekDay < 6) )

			if ( (strtotime($sDate) <= strtotime("2010-06-18") && $iCountryId == 18 && $iWeekDay != 5 && $iWeekDay != 6) ||
				 (strtotime($sDate) > strtotime("2010-06-18") && $iCountryId == 18 && $iWeekDay < 6) ||
				 ($iCountryId != 18 && $iWeekDay < 6) )
			{
				$sSQL = "SELECT * FROM tbl_holidays WHERE `date`='$sDate' AND country_id='$iCountryId'";
				$objDb->query($sSQL);

				if ($objDb->getCount( ) == 1)
				{
					$sData[]   = 8.5;
					$sLabels[] = $i;
					$sColors[] = 0x1d84e4;
				}

				else
				{
					$sData[]   = 8.5;
					$sLabels[] = $i;
					$sColors[] = 0xe8192b;
				}
			}
		}
	}


	$objChart = new XYChart(462, 190);
	$objChart->setPlotArea(50, 10, 390, 150);

	$objBarLayer = $objChart->addBarLayer3($sData, $sColors);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setAggregateLabelFormat("{value}");
	$objBarLayer->setAggregateLabelStyle("tahoma.ttf", 7);

	$objChart->xAxis->setLabels($sLabels);
	$objChart->xAxis->setLabelStyle("tahoma.ttf", 7);

	$objChart->yAxis->setLabelFormat("{value}");
	$objChart->yAxis->setLabelStyle("tahoma.ttf", 7);

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("LastMonth");
?>
	  			  <div id="LastMonth" style="display:none;"><img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" /></div>

<?
	$sData   = array( );
	$sLabels = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

	for ($i = 1; $i <= 12; $i ++)
	{
		$iMonthDays = @cal_days_in_month(CAL_GREGORIAN, $i, date("Y"));

		$sStartDate = (date("Y")."-".str_pad($i, 2, '0', STR_PAD_LEFT)."-01");
		$sEndDate   = (date("Y")."-".str_pad($i, 2, '0', STR_PAD_LEFT)."-".$iMonthDays);

		$sSQL = "SELECT AVG(TIME_TO_SEC(TIMEDIFF(time_out, time_in))) FROM tbl_attendance WHERE user_id='$Id' AND (`date` BETWEEN '$sStartDate' AND '$sEndDate') AND `date`!=CURDATE( )";
		$objDb->query($sSQL);

		$sData[] = @round((($objDb->getField(0, 0) / 60) / 60), 1);
	}

	$objChart = new XYChart(462, 190);
	$objChart->setPlotArea(50, 10, 390, 150, 0xffffff, -1, -1, 0xcccccc, 0xcccccc);

	$objLayer = $objChart->addLineLayer( );
	$objLayer->setLineWidth(1);
	$objLayer->setDataLabelFormat("{value}", "tahoma.ttf", 7);

	$objDataSet = $objLayer->addDataSet($sData, 0x777777);
	$objDataSet->setDataSymbol(SquareSymbol, 6);

	$objChart->xAxis->setLabels($sLabels);
	$objChart->xAxis->setLabelStyle("tahoma.ttf", 7);

	$objChart->yAxis->setLabelFormat("{value}");
	$objChart->yAxis->setLabelStyle("tahoma.ttf", 7);

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("CurrentYear");
?>
	  			  <div id="CurrentYear" style="display:none;"><img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" /></div>

<?
	$sData   = array( );
	$sLabels = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

	for ($i = 1; $i <= 12; $i ++)
	{
		$iMonthDays = @cal_days_in_month(CAL_GREGORIAN, $i, (date("Y") - 1));

		$sStartDate = ((date("Y") - 1)."-".str_pad($i, 2, '0', STR_PAD_LEFT)."-01");
		$sEndDate   = ((date("Y") - 1)."-".str_pad($i, 2, '0', STR_PAD_LEFT)."-".$iMonthDays);

		$sSQL = "SELECT AVG(TIME_TO_SEC(TIMEDIFF(time_out, time_in))) FROM tbl_attendance WHERE user_id='$Id' AND (`date` BETWEEN '$sStartDate' AND '$sEndDate')";
		$objDb->query($sSQL);

		$sData[] = @round((($objDb->getField(0, 0) / 60) / 60), 1);
	}

	$objChart = new XYChart(462, 190);
	$objChart->setPlotArea(50, 10, 390, 150, 0xffffff, -1, -1, 0xcccccc, 0xcccccc);

	$objLayer = $objChart->addLineLayer( );
	$objLayer->setLineWidth(1);
	$objLayer->setDataLabelFormat("{value}", "tahoma.ttf", 7);

	$objDataSet = $objLayer->addDataSet($sData);
	$objDataSet->setDataSymbol(SquareSymbol, 6);

	$objChart->xAxis->setLabels($sLabels);
	$objChart->xAxis->setLabelStyle("tahoma.ttf", 7);

	$objChart->yAxis->setLabelFormat("{value}");
	$objChart->yAxis->setLabelStyle("tahoma.ttf", 7);

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("LastYear");
?>
	  			  <div id="LastYear" style="display:none;"><img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" /></div>
