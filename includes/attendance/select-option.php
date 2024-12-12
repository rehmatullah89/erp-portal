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
?>
  <div id="Welcome">
    <center><img src="images/attendance/why-leave-office.jpg" width="391" height="18" alt="" title="" /></center>
    <br />

    <table border="0" cellpadding="12" cellspacing="0" width="100%">
      <tr>
        <td width="30%" align="right"><img src="images/attendance/name.jpg" width="60" height="18" alt="" title="" /> &nbsp; &nbsp; <img src="images/attendance/colon.jpg" width="8" height="18" alt="" title="" /></td>
        <td width="40%"><?= $sName ?></td>

        <td width="30%" rowspan="4" valign="top">
		  <div id="ProfilePic" style="margin:0px 0px 0px auto;">
		    <div id="Pic"><img src="<?= (USERS_IMG_PATH.'thumbs/'.$sPicture) ?>" alt="" title="" /></div>
		  </div>
        </td>
      </tr>

      <tr>
        <td align="right"><img src="images/attendance/designation.jpg" width="139" height="18" alt="" title="" /> &nbsp; &nbsp; <img src="images/attendance/colon.jpg" width="8" height="18" alt="" title="" /></td>
        <td><?= $sDesignation ?></td>
      </tr>

      <tr>
        <td align="right"><img src="images/attendance/department.jpg" width="137" height="18" alt="" title="" /> &nbsp; &nbsp; <img src="images/attendance/colon.jpg" width="8" height="18" alt="" title="" /></td>
        <td><?= $sDepartment ?></td>
      </tr>

      <tr>
        <td height="50"></td>
        <td></td>
      </tr>
    </table>

    <br />

    <div style="text-align:center;">
      <input type="button" value="Day Off" class="button" onclick="document.location='<?= SITE_URL ?>attendance/day-off.php?UserId=<?= $iUserId ?>';" />
      <input type="button" value="Lunch" class="button" onclick="document.location='<?= SITE_URL ?>attendance/lunch.php?UserId=<?= $iUserId ?>';" />
      <input type="button" value="Visit" class="button" onclick="document.location='<?= SITE_URL ?>attendance/visit.php?UserId=<?= $iUserId ?>';" />
    </div>
  </div>

  <script type="text/javascript">
  <!--
  	setTimeout(function( ) { document.location = "<?= SITE_URL ?>attendance/"; }, <?= (ATTENDANCE_DELAY * 2) ?>);
  -->
  </script>
