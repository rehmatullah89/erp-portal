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

	$Id  = $_SESSION['UserId'];
	$Tab = IO::strValue("Tab");

	$Tab = (($Tab == "") ? "Profile" : $Tab);

	$sClass    = array("evenRow", "oddRow");
	$iPageSize = PAGING_SIZE;

	$bSchedules = ((getDbValue("non_production_schedules", "tbl_users", "id='{$_SESSION['UserId']}'") == "Y") ? true : false);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/hr/board.js"></script>
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
			    <h1>Hr Board</h1>

			    <table border="0" cellpadding="0" cellspacing="0" width="100%">
				  <tr bgcolor="#494949">
					<td width="86"><input type="button" value="" class="btnProfile<?= (($Tab == 'Profile') ? 'Selected' : '') ?>" title="Profile" onclick="document.location='<?= SITE_URL ?>hr/board.php?Tab=Profile';" /></td>
					<td width="1"></td>
					<td width="136"><input type="button" value="" class="btnNotifications<?= (($Tab == 'Notifications') ? 'Selected' : '') ?>" title="Notifications" onclick="document.location='<?= SITE_URL ?>hr/board.php?Tab=Notifications';" /></td>
					<td width="1"></td>
					<td width="104"><input type="button" value="" class="btnCalendar<?= (($Tab == 'Calendar') ? 'Selected' : '') ?>" title="Calendar" onclick="document.location='<?= SITE_URL ?>hr/board.php?Tab=Calendar';" /></td>
					<td width="1"></td>
					<td width="107"><input type="button" value="" class="btnMessages<?= (($Tab == 'Messages') ? 'Selected' : '') ?>" title="Messages" onclick="document.location='<?= SITE_URL ?>hr/board.php?Tab=Messages';" /></td>
					<td width="1"></td>
					<td width="84"><input type="button" value="" class="btnPhotos<?= (($Tab == 'Photos') ? 'Selected' : '') ?>" title="Photos" onclick="document.location='<?= SITE_URL ?>hr/board.php?Tab=Photos';" /></td>
<?
	if ($bSchedules == true)
	{
?>
					<td width="1"></td>
					<td width="104"><input type="button" value="" class="btnSchedule<?= (($Tab == 'Schedule') ? 'Selected' : '') ?>" title="Schedule" onclick="document.location='<?= SITE_URL ?>hr/board.php?Tab=Schedule';" /></td>
<?
	}
?>
					<td width="1"></td>
					<td width="84"><input type="button" value="" class="btnPolicies<?= (($Tab == 'Policies') ? 'Selected' : '') ?>" title="Company Policies" onclick="document.location='<?= SITE_URL ?>hr/board.php?Tab=Policies';" /></td>
					<td></td>
				  </tr>
				</table>

			    <div style="padding-top:6px;">

<?
	if ($Tab == "Profile")
		@include($sBaseDir."includes/hr/board-profile.php");

	if ($Tab == "Notifications")
		@include($sBaseDir."includes/hr/board-notifications.php");
	
	if ($Tab == "Calendar")
		@include($sBaseDir."includes/hr/board-calendar.php");

	if ($Tab == "Messages")
		@include($sBaseDir."includes/hr/board-messages.php");

	if ($Tab == "Photos")
		@include($sBaseDir."includes/hr/board-photos.php");

	if ($Tab == "Schedule" && $bSchedules == true)
		@include($sBaseDir."includes/hr/board-schedule.php");

	if ($Tab == "Policies")
		@include($sBaseDir."includes/hr/board-policies.php");
?>
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