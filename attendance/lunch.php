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
  <input type="hidden" name="Country" id="Country" value="<?= $_SESSION['Country'] ?>" />

  <div id="DateTime">
<?
	@include($sBaseDir."includes/attendance/date-time.php");
?>
  </div>

<?
	$UserId = IO::strValue("UserId");

	if ($UserId == 0)
		redirect((SITE_URL."attendance/"), "DB_ERROR");

	$sSQL = "SELECT * FROM tbl_user_visits WHERE `date`='$sDate' AND user_id='$UserId' AND type='Lunch'";

	if ($objDb->query($sSQL) == false)
		redirect((SITE_URL."attendance/"), "DB_ERROR");

	if ($objDb->getCount( ) == 1)
	{
?>
  <div id="Message">
    <center>
      <img src="images/attendance/already-taken-lunch.jpg" width="400" height="18" alt="" title="" /><br />
      <br />
      <br />
      <img src="images/attendance/contact-system-admin.jpg" width="488" height="18" alt="" title="" /><br />
    </center>
  </div>
<?
	}

	else
	{
		$iId = getNextId("tbl_user_visits");

		$sSQL = "INSERT INTO tbl_user_visits (id, user_id, `date`, time_out, time_in, type, locations) VALUES ('$iId', '$UserId', '$sDate', '$sTime', '00:00:00', 'Lunch', '')";

		if ($objDb->execute($sSQL) == false)
			redirect((SITE_URL."attendance/"), "DB_ERROR");
?>
  <div id="Message">
    <center><img src="images/attendance/nice-meal.jpg" width="198" height="18" alt="" title="" /></center>
  </div>
<?
	}
?>

  <script type="text/javascript">
  <!--
  	setTimeout(function( ) { document.location = "<?= SITE_URL ?>attendance/"; }, <?= (ATTENDANCE_DELAY * 2) ?>);
  -->
  </script>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>