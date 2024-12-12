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

	authenticateUser( );

	if ($_SESSION['Country'] == 18)
		$sDateTime = @mktime((date("H") + 1), date("i"), date("s"), date("m"), date("d"), date("Y"));

	else
		$sDateTime = @mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));

	$sTime = date("H:i:s", $sDateTime);
	$sDate = date("Y-m-d", $sDateTime);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <link type="text/css" rel="stylesheet" href="css/attendance.css" />

  <script type="text/javascript" src="scripts/attendance/index.js"></script>
</head>

<body>

<!--  Message Section Starts Here  -->
<?
	@include($sBaseDir."includes/messages.php");
?>
<!--  Message Section Ends Here  -->

<div id="MainDiv">
  <input type="hidden" name="SignOffUrl" id="SignOffUrl" value="<?= SITE_URL ?>attendance/sign-off-all.php" />
  <input type="hidden" name="Country" id="Country" value="<?= $_SESSION['Country'] ?>" />

  <div id="DateTime">
<?
	@include($sBaseDir."includes/attendance/date-time.php");
?>
  </div>

<?
	if ($_POST && IO::strValue("CardId") != "")
	{
		$CardId = IO::strValue("CardId");

		if ($CardId == "")
			redirect((SITE_URL."attendance/"), "ERROR");

		$sSQL = "SELECT id, name, picture, status, designation_id FROM tbl_users WHERE card_id='$CardId' AND status='A'";

		if ($objDb->query($sSQL) == false)
			redirect((SITE_URL."attendance/"), "DB_ERROR");

		if ($objDb->getCount( ) != 1)
			@include($sBaseDir."includes/attendance/invalid-card.php");

		else
		{
			$iUserId      = $objDb->getField(0, 'id');
			$sName        = $objDb->getField(0, 'name');
			$iDesignation = $objDb->getField(0, 'designation_id');
			$sStatus      = $objDb->getField(0, 'status');
			$sPicture     = $objDb->getField(0, 'picture');


			$sSQL = "SELECT designation, department_id FROM tbl_designations WHERE id='$iDesignation'";
			$objDb->query($sSQL);

			$sDesignation = $objDb->getField(0, 'designation');
			$iDepartment  = $objDb->getField(0, 'department_id');

			$sDepartment  = getDbValue("department", "tbl_departments", "id='$iDepartment'");


			if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'originals/'.$sPicture))
				$sPicture = "default.jpg";

			if ($sStatus != "A")
				@include($sBaseDir."includes/attendance/account-disabled.php");

			else
			{
				$sSQL = "SELECT time_out FROM tbl_attendance WHERE `date`='$sDate' AND user_id='$iUserId'";

				if ($objDb->query($sSQL) == false)
					redirect((SITE_URL."attendance/"), "DB_ERROR");

				if ($objDb->getCount( ) == 0)
					@include($sBaseDir."includes/attendance/welcome-to-office.php");

				else if ($objDb->getField(0, 'time_out') != "00:00:00")
					@include($sBaseDir."includes/attendance/day-already-finished.php");

				else
				{
					$sSQL = "SELECT id, type, time_in FROM tbl_user_visits WHERE `date`='$sDate' AND user_id='$iUserId' ORDER BY id DESC LIMIT 1";

					if ($objDb->query($sSQL) == false)
						redirect((SITE_URL."attendance/"), "DB_ERROR");

					$iVisitId   = $objDb->getField(0, 'id');
					$sVisitType = $objDb->getField(0, 'type');
					$sTimeOut   = $objDb->getField(0, 'time_in');

					if ($objDb->getCount( ) == 1 && $sTimeOut == "00:00:00")
						@include($sBaseDir."includes/attendance/welcome-back.php");

					else
						@include($sBaseDir."includes/attendance/select-option.php");
				}
			}
		}
	}

	else
		@include($sBaseDir."includes/attendance/waiting-for-input.php");
?>

</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>