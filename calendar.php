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

	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$sEmployeesList = getList("tbl_users", "id", "name", "(email LIKE '%@apparelco.com%' OR email LIKE '%@3-tree.com%') AND status='A'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

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
			  <td width="585">
			    <h1>Matrix Calendar</h1>

			    <div class="tblSheet">
			      <img src="images/headers/calendar.jpg" width="581" height="205" alt="" title="" />

<?
	$sMode  = ((IO::strValue("Mode") == "") ? "Grid" : IO::strValue("Mode"));
	$iYear  = ((IO::intValue("Year") == "") ? date("Y") : IO::intValue("Year"));
	$iMonth = ((IO::intValue("Month") == "") ? date("n") : IO::intValue("Month"));
	$iDay   = date("j");

	$iMonthDays = date("t", @mktime(0, 0, 0, $iMonth, 1, $iYear));
	$iFirstDay  = date("w", @mktime(0, 0, 0, $iMonth, 1, $iYear));

	$iDays  = ($iFirstDay + $iMonthDays);
	$iWeeks = @ceil($iDays / 7);

	$iPreviousMonth = date("n", @mktime(0, 0, 0, ($iMonth - 1), 1, $iYear));
	$iPreviousYear  = date("Y", @mktime(0, 0, 0, ($iMonth - 1), 1, $iYear));
	$iNextMonth     = date("n", @mktime(0, 0, 0, ($iMonth + 1), 1, $iYear));
	$iNextYear      = date("Y", @mktime(0, 0, 0, ($iMonth + 1), 1, $iYear));


	$sConditions = "";

	if (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@3-tree.com") === FALSE)
		$sConditions = " AND private!='Y' ";
?>
			      <div id="Calendar">
			        <table border="0" cellpadding="0" cellspacing="0" width="100%">
					  <tr>
					    <td width="15%" align="left"><h1><a href="calendar.php?Month=<?= $iPreviousMonth ?>&Year=<?= $iPreviousYear ?>&Mode=<?= $sMode ?>">� Back</a></h1></td>
					    <td width="70%" align="center"><h1><?= date("F Y", @mktime(0, 0, 0, $iMonth, 1, $iYear)) ?></h1></td>
					    <td width="15%" align="right"><h1><a href="calendar.php?Month=<?= $iNextMonth ?>&Year=<?= $iNextYear ?>&Mode=<?= $sMode ?>">Next �</a></h1></td>
					  </tr>
					</table>
				  </div>

<?
	if ($sMode == "List")
	{
?>
				  <div id="ListMode">
<?
		$sStartDate = ($iYear."-".str_pad($iMonth, 2, '0', STR_PAD_LEFT)."-01");
		$sEndDate   = ($iYear."-".str_pad($iMonth, 2, '0', STR_PAD_LEFT)."-".$iMonthDays);

		$sSQL = "SELECT title, details, from_date, to_date, users
		         FROM tbl_calendar
		         WHERE (from_date BETWEEN '$sStartDate' AND '$sEndDate') OR (to_date BETWEEN '$sStartDate' AND '$sEndDate') $sConditions
		         ORDER BY from_date, title";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sTitle    = $objDb->getField($i, 'title');
			$sDetails  = $objDb->getField($i, 'details');
			$sFromDate = $objDb->getField($i, 'from_date');
			$sToDate   = $objDb->getField($i, 'to_date');
			$iUsers    = @explode(",", $objDb->getField($i, 'users'));

			$sEmployees = "";

			for ($j = 0; $j < count($iUsers); $j ++)
				$sEmployees .= (", ".$sEmployeesList[$iUsers[$j]]);

			if ($sEmployees != "")
				$sEmployees = substr($sEmployees, 2);
?>
				    <div class="tblSheet entry"<? if ($i > 0) { print 'style="margin-top:4px;"'; } ?>>
					  <h2><?= $sTitle ?></h2>

					  <table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
					    <tr>
					 	  <td width="15%" bgcolor="#f0f0f0">Dates</td>
						  <td width="85%" bgcolor="#f7f7f7"><?= formatDate($sFromDate) ?> &nbsp;to&nbsp; <?= formatDate($sToDate) ?></td>
					    </tr>

					    <tr>
						  <td bgcolor="#f0f0f0">Employee(s)</td>
						  <td bgcolor="#f7f7f7"><?= $sEmployees ?></td>
					    </tr>

					    <tr>
						  <td bgcolor="#f0f0f0">Details</td>
						  <td bgcolor="#f7f7f7"><?= $sDetails ?></td>
					    </tr>
					  </table>
				    </div>
<?
		}

		if ($iCount == 0)
		{
?>
				    <div class="tblSheet entry">
					  <h2>&nbsp;</h2>
					  <br />
					  <center>No Calendar Entry to show in this Month</center>
					  <br />
				    </div>
<?
		}
?>
				  </div>
<?
	}

	else if ($sMode == "Grid")
	{
?>

				  <div id="GridMode">
				    <table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
					  <tr class="header">
						<td width="14.2%">Sun</td>
						<td width="14.3%">Mon</td>
						<td width="14.3%">Tue</td>
						<td width="14.3%">Wed</td>
						<td width="14.3%">Thu</td>
						<td width="14.3%">Fri</td>
						<td width="14.3%">Sat</td>
					  </tr>

<?
		$iDay = 1;

		for ($i = 0; $i < $iWeeks; $i ++)
		{
?>
					  <tr>
<?
			for ($j = 0; $j < 7; $j ++)
			{
				if (($i == 0 && $j < $iFirstDay) || $iDay > $iMonthDays)
				{
?>
					    <td class="empty"></td>
<?
				}

				else
				{
					$sDate = ($iYear."-".str_pad($iMonth, 2, '0', STR_PAD_LEFT)."-".str_pad($iDay, 2, '0', STR_PAD_LEFT));

					$sSQL = "SELECT title, details, from_date, to_date, users
					         FROM tbl_calendar
					         WHERE ('$sDate' BETWEEN from_date AND to_date) $sConditions
					         ORDER BY title";
					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );
?>
					    <td>
					      <div>
<?
					if ($iCount == 0)
					{
?>
					        <div class="day"><?= $iDay ?></div>
<?
					}

					else
					{
?>
					        <div class="day entries" onmouseover="if ($('Day<?= $iDay ?>').style.display == 'none') { $('Day<?= $iDay ?>').show( ); }" onmouseout="$('Day<?= $iDay ?>').hide( );"><?= $iDay ?></div>

					        <div id="Day<?= $iDay ?>" class="dayEvents" style="display:none;" onmouseover="if ($('Day<?= $iDay ?>').style.display == 'none') { $('Day<?= $iDay ?>').show( ); }" onmouseout="$('Day<?= $iDay ?>').hide( );">
<?
						for ($k = 0; $k < $iCount; $k ++)
						{
							$sTitle    = $objDb->getField($k, 'title');
							$sDetails  = $objDb->getField($k, 'details');
							$sFromDate = $objDb->getField($k, 'from_date');
							$sToDate   = $objDb->getField($k, 'to_date');
							$iUsers    = @explode(",", $objDb->getField($k, 'users'));

							$sEmployees = "";

							for ($l = 0; $l < count($iUsers); $l ++)
								$sEmployees .= (", ".$sEmployeesList[$iUsers[$l]]);

							if ($sEmployees != "")
								$sEmployees = substr($sEmployees, 2);
?>
					          <h2><?= $sTitle ?></h2>

							  <table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
								<tr>
								  <td width="22%" bgcolor="#f0f0f0">Dates</td>
								  <td width="78%" bgcolor="#f7f7f7"><?= formatDate($sFromDate) ?> &nbsp;to&nbsp; <?= formatDate($sToDate) ?></td>
								</tr>

								<tr>
								  <td bgcolor="#f0f0f0">Employee(s)</td>
								  <td bgcolor="#f7f7f7"><?= $sEmployees ?></td>
								</tr>

								<tr>
								  <td bgcolor="#f0f0f0">Details</td>
								  <td bgcolor="#f7f7f7"><?= $sDetails ?></td>
								</tr>
							  </table>

<?
						}
?>
					        </div>
<?
					}
?>
					      </div>
					    </td>
<?
					$iDay ++;
				}
			}
?>
					  </tr>
<?
		}
?>
			        </table>
				  </div>
<?
	}
?>
			    </div>

			    <div class="tblSheet mode">
			      <table border="0" cellpadding="2" cellspacing="0" width="100%">
			        <tr bgcolor="#38414A">
			          <td width="12%"><h2 style="margin:0px;">MODE</h2></td>

			          <td width="88%">
			            <select onchange="document.location='<?= SITE_URL ?>calendar.php?Month=<?= $iMonth ?>&Year=<?= $iYear ?>&Mode=' + this.value;">
			              <option value="List"<?= (($sMode == "List") ? "selected" : "") ?>>List View</option>
			              <option value="Grid"<?= (($sMode == "Grid") ? "selected" : "") ?>>Grid View</option>
			            </select>
			          </td>
			        </tr>
			      </table>
			    </div>

<?
	if (IO::strValue("Mode") == "" && IO::intValue("Year") == 0 && IO::intValue("Month") == 0)
	{
		$sStartDate = date("Y-m-d");
		$sEndDate   = date("Y-m-d", @mktime(0, 0, 0, date("m"), (date("d") + 30), date("Y")));

		$sSQL = "SELECT title, details, from_date, to_date, users
		         FROM tbl_calendar
		         WHERE (from_date BETWEEN '$sStartDate' AND '$sEndDate') OR (to_date BETWEEN '$sStartDate' AND '$sEndDate') $sConditions
		         ORDER BY from_date, title";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 0)
		{
?>
			    <hr />

			    <h1>UPComing Events</h1>

			    <div id="ListMode">
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$sTitle    = $objDb->getField($i, 'title');
				$sDetails  = $objDb->getField($i, 'details');
				$sFromDate = $objDb->getField($i, 'from_date');
				$sToDate   = $objDb->getField($i, 'to_date');
				$iUsers    = @explode(",", $objDb->getField($i, 'users'));

				$sEmployees = "";

				for ($j = 0; $j < count($iUsers); $j ++)
					$sEmployees .= (", ".$sEmployeesList[$iUsers[$j]]);

				if ($sEmployees != "")
					$sEmployees = substr($sEmployees, 2);
?>
				  <div class="tblSheet entry"<? if ($i > 0) { print 'style="margin-top:4px;"'; } ?>>
				    <h2><?= $sTitle ?></h2>

				    <table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
					  <tr>
					    <td width="18%" bgcolor="#f0f0f0">Dates</td>
					    <td width="82%" bgcolor="#f7f7f7"><?= formatDate($sFromDate) ?> &nbsp;to&nbsp; <?= formatDate($sToDate) ?></td>
					  </tr>

					  <tr>
					    <td bgcolor="#f0f0f0">Employee(s)</td>
					    <td bgcolor="#f7f7f7"><?= $sEmployees ?></td>
					  </tr>

					  <tr>
					    <td bgcolor="#f0f0f0">Details</td>
					    <td bgcolor="#f7f7f7"><?= $sDetails ?></td>
					  </tr>
				    </table>
				  </div>
<?
			}
?>
				</div>
<?
		}

		$sStartDate = date("Y-m-d", @mktime(0, 0, 0, date("m"), (date("d") - 30), date("Y")));
		$sEndDate   = date("Y-m-d", @mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y")));

		$sSQL = "SELECT title, details, from_date, to_date, users
		         FROM tbl_calendar
		         WHERE (from_date BETWEEN '$sStartDate' AND '$sEndDate') OR (to_date BETWEEN '$sStartDate' AND '$sEndDate') $sConditions
		         ORDER BY from_date, title";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 0)
		{
?>
			    <hr />

			    <h1>Past Events</h1>

			    <div id="ListMode">
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$sTitle    = $objDb->getField($i, 'title');
				$sDetails  = $objDb->getField($i, 'details');
				$sFromDate = $objDb->getField($i, 'from_date');
				$sToDate   = $objDb->getField($i, 'to_date');
				$iUsers    = @explode(",", $objDb->getField($i, 'users'));

				$sEmployees = "";

				for ($j = 0; $j < count($iUsers); $j ++)
					$sEmployees .= (", ".$sEmployeesList[$iUsers[$j]]);

				if ($sEmployees != "")
					$sEmployees = substr($sEmployees, 2);
?>
				  <div class="tblSheet entry"<? if ($i > 0) { print 'style="margin-top:4px;"'; } ?>>
				    <h2><?= $sTitle ?></h2>

				    <table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
					  <tr>
					    <td width="18%" bgcolor="#f0f0f0">Dates</td>
					    <td width="82%" bgcolor="#f7f7f7"><?= formatDate($sFromDate) ?> &nbsp;to&nbsp; <?= formatDate($sToDate) ?></td>
					  </tr>

					  <tr>
					    <td bgcolor="#f0f0f0">Employee(s)</td>
					    <td bgcolor="#f7f7f7"><?= $sEmployees ?></td>
					  </tr>

					  <tr>
					    <td bgcolor="#f0f0f0">Details</td>
					    <td bgcolor="#f7f7f7"><?= $sDetails ?></td>
					  </tr>
				    </table>
				  </div>
<?
			}
?>
				</div>
<?
		}
	}
?>
			  </td>

			  <td width="5"></td>

			  <td>
<?
	@include($sBaseDir."includes/sign-in.php");
?>

			    <div style="height:5px;"></div>

<?
	@include($sBaseDir."includes/contact-info.php");
?>
			  </td>
			</tr>
		  </table>
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