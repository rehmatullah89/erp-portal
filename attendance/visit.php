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
  <script type="text/javascript" src="scripts/attendance/visit.js"></script>
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

	$sSQL = "SELECT * FROM tbl_visit_locations ORDER BY location";

	if ($objDb->query($sSQL) == false)
		redirect((SITE_URL."attendance/"), "DB_ERROR");

	$iCount = $objDb->getCount( );
?>
  <form name="frmVisit" id="frmVisit" method="post" action="attendance/save-visit-details.php" onsubmit="$('BtnSave').disabled=true;">
  <input type="hidden" name="UserId" value="<?= $UserId ?>" />

  <div id="Welcome">
    <center><img src="images/attendance/visit-details.jpg" width="371" height="18" alt="" title="" /></center>
    <br />

<?
	for ($i = 1; $i <= 8; $i ++)
	{
?>
    <div id="Block<?= $i ?>" style="display:<?= (($i == 1) ? 'block' : 'none') ?>;">
      <table border="0" cellpadding="6" cellspacing="0" width="100%">
        <tr>
          <td width="11%" align="right"><img src="images/attendance/from.jpg" width="59" height="18" alt="" title="" align="absmiddle" /> &nbsp; <img src="images/attendance/colon.jpg" width="8" height="18" alt="" title="" align="absmiddle" /></td>
          <td width="39%"><span id="VisitLocation<?= $i ?>"><?= (($i == 1) ? 'MATRIX SOURCING' : '-') ?></span></td>

          <td width="11%" align="right"><img src="images/attendance/to.jpg" width="26" height="18" alt="" title="" align="absmiddle" /> &nbsp; <img src="images/attendance/colon.jpg" width="8" height="18" alt="" title="" align="absmiddle" /></td>

          <td width="39%">
            <select id="Location<?= $i ?>" name="Location<?= $i ?>" onchange="setLocation(<?= $i ?>);">
              <option value=""></option>
<?
		for ($j = 0; $j < $iCount; $j ++)
		{
			$iId       = $objDb->getField($j, 'id');
			$sLocation = $objDb->getField($j, 'location');
?>
              <option value="<?= $iId ?>"><?= $sLocation ?></option>
<?
		}
?>
            </select>
          </td>
        </tr>
      </table>
    </div>
<?
	}
?>

    <br />
    <br />

    <div style="text-align:center;">
      <input type="submit" id="BtnSave" value=" Done!" class="button" />
      <input type="button" value="Cancel" class="button" onclick="document.location='<?= SITE_URL ?>attendance/';" />
    </div>
  </div>
  </form>

  <script type="text/javascript">
  <!--
  	setTimeout(function( ) { document.location = "<?= SITE_URL ?>attendance/"; }, <?= (ATTENDANCE_DELAY * 30) ?>);
  -->
  </script>


  <div id="Alphabets">
    <table border="1" bordercolor="#222222" cellpadding="10" cellspacing="0" width="100%">
      <tr>
<?
	for ($i = 65; $i <= 90; $i ++)
	{
?>
        <td onclick="filterList('<?= chr($i) ?>');"><?= chr($i) ?></td>
<?
	}
?>
      </tr>
    </table>
  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>