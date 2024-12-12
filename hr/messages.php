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

	$Id = IO::intValue('Id');

	$sSQL = "SELECT * FROM tbl_hr_messages WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect("board.php", "DB_ERROR");

	$iSenderId = $objDb->getField(0, "sender_id");
	$sSubject  = $objDb->getField(0, "subject");
	$sManager  = $objDb->getField(0, "manager");
	$sMessage  = $objDb->getField(0, "message");
	$iStatus   = $objDb->getField(0, "status");
	$sDateTime = $objDb->getField(0, "date_time");


	$sSQL = "SELECT name, picture FROM tbl_users WHERE id='$iSenderId'";
	$objDb->query($sSQL);

	$sName    = $objDb->getField(0, "name");
	$sPicture = $objDb->getField(0, "picture");

	if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
		$sPicture = "default.jpg";


	$iHrId = HR_MANAGER;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/hr/messages.js"></script>
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
			    <h1><img src="images/h1/hr/hr-board.jpg" width="120" height="20" vspace="10" alt="" title="" /></h1>

			    <table border="0" cellpadding="0" cellspacing="0" width="100%">
				  <tr bgcolor="#494949">
					<td width="86"><input type="button" value="" class="btnProfile" title="Profile" onclick="document.location='<?= SITE_URL ?>hr/board.php?Tab=Profile';" /></td>
					<td width="1"></td>
					<td width="136"><input type="button" value="" class="btnNotifications" title="Notifications" onclick="document.location='<?= SITE_URL ?>hr/board.php?Tab=Notifications';" /></td>
					<td width="1"></td>
					<td width="104"><input type="button" value="" class="btnCalendar" title="Calendar" onclick="document.location='<?= SITE_URL ?>hr/board.php?Tab=Calendar';" /></td>
					<td width="1"></td>
					<td width="107"><input type="button" value="" class="btnMessagesSelected" title="Messages" onclick="document.location='<?= SITE_URL ?>hr/board.php?Tab=Messages';" /></td>
					<td width="1"></td>
					<td width="84"><input type="button" value="" class="btnPhotos" title="Photos" onclick="document.location='<?= SITE_URL ?>hr/board.php?Tab=Photos';" /></td>
					<td width="1"></td>
					<td width="104"><input type="button" value="" class="btnSchedule<?= (($Tab == 'Schedule') ? 'Selected' : '') ?>" title="Schedule" onclick="document.location='<?= SITE_URL ?>hr/board.php?Tab=Schedule';" /></td>
					<td></td>
				  </tr>
				</table>

				<div class="tblSheet" style="margin-top:4px;">
				  <h2><?= (($sManager == "HR Manager") ? "Message" : "Grievance") ?> Details</h2>
				  <div style="background:#cccccc; padding:15px; margin:0px 10px 12px 10px; font-size:12px;"><b><?= $sSubject ?></b></div>

				  <div style="background:#eeeeee; padding:10px; margin:0px 10px 0px 10px;">
				    <table border="0" cellpadding="0" cellspacing="0" width="100%">
				      <tr valign="top">
				        <td width="162" align="center">
					      <div id="ProfilePic" style="margin:0px 0px 5px 0px;">
						    <div id="Pic"><img src="<?= USERS_IMG_PATH.'thumbs/'.$sPicture ?>" alt="<?= $sName ?>" title="<?= $sName ?>" /></div>
					      </div>

					      <i style="color:#999999;"><?= $sName ?></i><br />
				        </td>

				        <td width="20"></td>

				        <td>
				          <h4><?= formatDate($sDateTime, "l, jS F, Y   h:i A") ?></h4>
				          <?= nl2br($sMessage) ?><br />
				        </td>
				      </tr>
				    </table>
				  </div>

				  <br />
<?
	$sSQL = "SELECT * FROM tbl_hr_messages WHERE parent_id='$Id' ORDER BY id ASC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iSenderId = $objDb->getField($i, "sender_id");
		$sMessage  = $objDb->getField($i, "message");
		$sDateTime = $objDb->getField($i, "date_time");


		$sSQL = "SELECT name, picture FROM tbl_users WHERE id='$iSenderId'";
		$objDb2->query($sSQL);

		$sName    = $objDb2->getField(0, "name");
		$sPicture = $objDb2->getField(0, "picture");

		if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
			$sPicture = "default.jpg";
?>

				  <div style="background:#<?= ((($i % 2) == 1) ? 'eeeeee' : 'f9f9f9') ?>; padding:10px; margin:0px 10px 0px 10px;">
				    <table border="0" cellpadding="0" cellspacing="0" width="100%">
				      <tr valign="top">
				        <td width="162" align="center">
					      <div id="ProfilePic" style="margin:0px 0px 5px 0px;">
						    <div id="Pic"><img src="<?= USERS_IMG_PATH.'thumbs/'.$sPicture ?>" alt="<?= $sName ?>" title="<?= $sName ?>" /></div>
					      </div>

					      <i style="color:#999999;"><?= $sName ?></i><br />
				        </td>

				        <td width="20"></td>

				        <td>
				          <h4><?= formatDate($sDateTime, "l, jS F, Y   h:i A") ?></h4>
				          <?= nl2br($sMessage) ?><br />
				        </td>
				      </tr>
				    </table>
				  </div>

				  <br />
<?
	}
?>
				</div>

				<br />

<?
	if ($_POST['PostId'] != "")
		$_REQUEST = @unserialize($_SESSION[$_POST['PostId']]);

	$sManager = getDbValue("grievances_manager", "tbl_users", "id='{$_SESSION['UserId']}'");
?>
			    <form name="frmMessage" id="frmMessage" method="post" action="hr/save-message.php" class="frmOutline" onsubmit="$('BtnSave').disable( );">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Hr" value="<?= (($iHrId == $_SESSION['UserId'] || $sManager == "Y") ? 'Y' : '') ?>" />

			    <h2>Post A Reply</h2>
			    <table width="95%" cellspacing="0" cellpadding="4" border="0" align="center">
<?
	if ($iHrId == $_SESSION['UserId'] || $sManager == "Y")
	{
		$sEmployeesList = getList("tbl_users", "id", "CONCAT(name, ' &lt;', email, '&gt;')", "(email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@lulusar.com') AND status='A'");
?>
				  <tr valign="top">
				    <td width="55">Recipients<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>

				    <td>

				      <table width="100%" cellspacing="0" cellpadding="0" border="0">
				        <tr>
				          <td width="45%">
			                <select id="Recipients" name="Recipients[]" multiple size="10" style="width:358px;">
<?
		foreach ($sEmployeesList as $sKey => $sValue)
		{
			if ($sKey == $iSenderId)
			{
?>
			            	  <option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
			}
		}
?>
		              		</select>
		                  </td>

		                  <td width="10%" align="center">
						    <input type="button" value=" > " class="button" onclick="moveRight( );" /><br />
						    <br />
						    <input type="button" value=" < " class="button" onclick="moveLeft( );" /><br />
		                  </td>

		                  <td width="45%">
			                <select id="Employees" name="Employees[]" multiple size="10" style="width:358px;">
<?
		foreach ($sEmployeesList as $sKey => $sValue)
		{
			if ($sKey != $iSenderId)
			{
?>
			            	  <option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
			}
		}
?>
		              		</select>
		                  </td>
		                </tr>
		              </table>
				    </td>
				  </tr>
<?
	}
?>
				  <tr valign="top">
				    <td width="55">Message<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>
				    <td><textarea name="Message" style="width:100%; height:180px;"><?= IO::getFormValue('Message') ?></textarea></td>
				  </tr>
				</table>
			    </div>

				<br />

			    <div class="buttonsBar">
			      <input type="submit" id="BtnSave" value="" class="btnSave" onclick="return validateForm( );" />
			      <input type="button" value="" class="btnBack" onclick="document.location='<?= SITE_URL ?>hr/board.php?Tab=Messages';" />
			    </div>
			    </form>

			    <br />
			    <b>Note:</b> Fields marked with an asterisk (*) are required.<br/>
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
	if ($iHrId == $_SESSION['UserId'])
	{
		if ($iStatus == 0)
		{
			$sSQL = "UPDATE tbl_hr_messages SET status='1' WHERE id='$Id'";
			$objDb->execute($sSQL);
		}
	}

	else
	{
		if ($iStatus == 2)
		{
			$sSQL = "UPDATE tbl_hr_messages SET status='3' WHERE id='$Id'";
			$objDb->execute($sSQL);
		}
	}


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>