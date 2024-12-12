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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$sCountriesList = getList("tbl_countries", "id", "country", "matrix='Y'");
	$sEmployeesList = getList("tbl_users", "id", "CONCAT(name, '  (', card_id, ')')", "(email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@lulusar.com' OR email LIKE '%@gaia.com.pk') AND status='A'");

	if ($_SESSION['CountryId'] == 18)
		$sEmployeesList = getList("tbl_users", "id", "CONCAT(name, '  (', card_id, ')')", "(email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com') AND status='A' AND country_id='18'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>

  <script type="text/javascript" src="scripts/reports/attendance-report.js"></script>
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
			    <h1>Attendance Report</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="reports/export-attendance-report.php" class="frmOutline" onsubmit="checkDoubleSubmission( );">
				<h2>Attendance Report</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="70">From Date<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
				    <td width="78"><input type="text" name="FromDate" value="<?= date('Y-m-01') ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
				    <td><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
				  </tr>

				  <tr>
					<td>To Date<span class="mandatory">*</span></td>
					<td align="center">:</td>
				    <td><input type="text" name="ToDate" value="<?= date('Y-m') ?>-<?= @cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')) ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
				    <td><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
				  </tr>

<?
	if ($_SESSION['CountryId'] != 18)
	{
?>
				  <tr>
					<td>Region</td>
					<td align="center">:</td>

					<td colspan="2">
					  <select name="Region" id="Region" onchange="getEmployeesList( );">
						<option value="">All Regions</option>
<?
		foreach ($sCountriesList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>
<?
	}
?>

				  <tr>
					<td>Employee</td>
					<td align="center">:</td>

					<td colspan="2">
			          <select name="Employee" id="Employee">
			            <option value="">All Employees</option>
<?
	foreach ($sEmployeesList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
	}
?>
		              </select>
					</td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar">
				  <input type="submit" value="" id="BtnExport" class="btnExport" title="Export" />
				</div>
			    </form>
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