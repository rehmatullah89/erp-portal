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

	$sDepartmentsList = getList("tbl_departments", "id", "department");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body style="background:#2c2c2c;">

<div style="background:url('images/attendance.jpg') no-repeat; width:800px; height:600px;">
<?
	$iDepartments = array(39,1,18,11,31,8,7,5,27,15);
	$sAverages    = array( );

	for ($i = 0; $i < count($iDepartments); $i ++)
	{
		$sAverages[$i][0] = $sDepartmentsList[$iDepartments[$i]];

		$sSQL = "SELECT id FROM tbl_users WHERE designation_id IN (SELECT id FROM tbl_designations WHERE department_id='{$iDepartments[$i]}')";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sUsers = "";

		for ($j = 0; $j < $iCount; $j ++)
			$sUsers .= (",".$objDb->getField($j, 0));

		$sUsers = substr($sUsers, 1);


		$sSQL = "SELECT
						SEC_TO_TIME(AVG(TIME_TO_SEC(time_in))),
						SEC_TO_TIME(AVG(TIME_TO_SEC(time_out))),
						SEC_TO_TIME(AVG(TIME_TO_SEC(TIMEDIFF(time_out, time_in))))
				 FROM tbl_attendance WHERE `date` BETWEEN DATE_SUB(`date`, INTERVAL 7 DAY) AND CURDATE( ) AND user_id IN ($sUsers)";
		$objDb->query($sSQL);

		$sAverages[$i][1] = $objDb->getField(0, 0);
		$sAverages[$i][2] = $objDb->getField(0, 1);
		$sAverages[$i][3] = $objDb->getField(0, 2);
	}
?>
  <div style="position:absolute; left:28px; top:17px;">
    <table border="0" cellpadding="0" cellspacing="0" width="158">
<?
	for ($i = 0; $i < 5; $i ++)
	{
?>
      <tr>
 	    <td height="60"><b style="color:#ffffff;"><?= $sAverages[$i][0] ?></b></td>
      </tr>
<?
	}
?>
    </table>
  </div>

  <div style="position:absolute; left:615px; top:17px;">
    <table border="0" cellpadding="0" cellspacing="0" width="158">
<?
	for ($i = 5; $i < 10; $i ++)
	{
?>
      <tr>
 	    <td height="60"><b style="color:#ffffff;"><?= $sAverages[$i][0] ?></b></td>
      </tr>
<?
	}
?>
    </table>
  </div>

<?
	$sPositions = array( );

	$sPositions[0] = array(366, 78);
	$sPositions[1] = array(308, 202);
	$sPositions[2] = array(425, 202);
	$sPositions[3] = array(226, 338);
	$sPositions[4] = array(366, 338);
	$sPositions[5] = array(508, 338);
	$sPositions[6] = array(148, 472);
	$sPositions[7] = array(285, 472);
	$sPositions[8] = array(450, 472);
	$sPositions[9] = array(588, 472);

	for ($i = 0; $i < 10; $i ++)
	{
?>
  <div style="position:absolute; left:<?= $sPositions[$i][0] ?>px; top:<?= $sPositions[$i][1] ?>px; font-weight:bold; color:#ffffff; text-align:center;">
    Time-in<br /><?= formatTime($sAverages[$i][1]) ?><br />
    <div style="height:6px; line-height:6px;"></div>
    Time-out<br /><?= formatTime($sAverages[$i][2]) ?><br />
    <div style="height:6px; line-height:6px;"></div>
    Avg Hours<br /><?= $sAverages[$i][3] ?><br />
  </div>
<?
	}
?>
</div>

<script type="text/javascript">
<!--

	setTimeout( function( ) { document.location.reload( ); }, 60000);

-->
</script>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>