<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	$sSQL = "UPDATE tbl_user_visits SET time_in='$sTime' WHERE `date`='$sDate' AND user_id='$iUserId' AND id='$iVisitId'";

	if ($objDb->execute($sSQL) == false)
		redirect((SITE_URL."attendance/"), "DB_ERROR");
?>
  <div id="Welcome">
<?
	if ($sVisitType == "Lunch")
	{
?>
    <center><img src="images/attendance/back-from-lunch.jpg" width="500" height="18" alt="" title="" /></center>
<?
	}

	else
	{
?>
    <center><img src="images/attendance/back-from-visit.jpg" width="459" height="18" alt="" title="" /></center>
<?
	}
?>
    <br />

    <table border="0" cellpadding="12" cellspacing="0" width="100%">
      <tr>
        <td width="52%" align="right"><img src="images/attendance/name.jpg" width="60" height="18" alt="" title="" /> &nbsp; &nbsp; <img src="images/attendance/colon.jpg" width="8" height="18" alt="" title="" /></td>
        <td width="48%"><?= $sName ?></td>
      </tr>

      <tr>
        <td align="right"><img src="images/attendance/designation.jpg" width="139" height="18" alt="" title="" /> &nbsp; &nbsp; <img src="images/attendance/colon.jpg" width="8" height="18" alt="" title="" /></td>
        <td><?= $sDesignation ?></td>
      </tr>

      <tr>
        <td align="right"><img src="images/attendance/department.jpg" width="137" height="18" alt="" title="" /> &nbsp; &nbsp; <img src="images/attendance/colon.jpg" width="8" height="18" alt="" title="" /></td>
        <td><?= $sDepartment ?></td>
      </tr>
    </table>

    <br />

	<div id="ProfilePic" style="margin:0px auto 0px auto;">
	  <div id="Pic"><img src="<?= (USERS_IMG_PATH.'thumbs/'.$sPicture) ?>" alt="" title="" /></div>
	</div>
  </div>

  <script type="text/javascript">
  <!--
  	setTimeout(function( ) { document.location = "<?= SITE_URL ?>attendance/"; }, <?= (ATTENDANCE_DELAY * 2) ?>);
  -->
  </script>
