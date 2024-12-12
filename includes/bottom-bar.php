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

/*
	if ($_SESSION['UserId'] != "")
	{
?>
<div id="TabsBar">
  <div id="TabsArea">
	<ul>
	  <li id="NotificationsTab" onclick="showTab('Notifications', '<?= date("Y-m-d H:i:s") ?>');"><img src="images/icons/notifications.gif" alt="" title="" />Notifications (<b><?= (int)$iNotifications ?></b>)</li>
<?
		if (@strpos($_SESSION['Email'], "@apparelco.com") !== FALSE || @strpos($_SESSION['Email'], "@3-tree.com") !== FALSE)
		{
			$sSQL = "SELECT DISTINCT(user_id) FROM tbl_user_stats WHERE DATE_FORMAT(login_date_time, '%Y-%m-%d')=CURDATE( ) AND TIME_TO_SEC(TIMEDIFF(NOW( ), logout_date_time)) <= '1800' AND status='1' AND user_id!='{$_SESSION['UserId']}' ORDER BY id DESC";
			$objDb->query($sSQL);

			$iOnlineUsers = $objDb->getCount( );
			$sOnlineUsers = "";

			for ($i = 0; $i < $iOnlineUsers; $i ++)
				$sOnlineUsers .= (",".$objDb->getField($i, 0));

			$sOnlineUsers = substr($sOnlineUsers, 1);
?>
	  <li id="ChatTab" onclick="showTab('Chat', '');"><img src="images/icons/chat.gif" alt="" title="" />Chat (<b id="OnlineUsers"><?= (int)$iOnlineUsers ?></b>)</li>
<!--
	  <li id="ChatWinTab" class="selected"><img src="images/icons/chat-user.png" alt="" title="" />User Name</li>
-->
<?
		}
?>
	</ul>


	<div id="Notifications" style="display:none;">
	  <div>
		<h2><img src="images/icons/close.gif" alt="Close" title="Close" onclick="hideTab('Notifications');" />Notifications</h2>

		<div id="NotificationsArea">
<?
		$sSQL = "SELECT * FROM tbl_user_notifications WHERE user_id='{$_SESSION['UserId']}' ORDER BY id DESC LIMIT 10";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sSubject  = $objDb->getField($i, 'subject');
			$sBody     = $objDb->getField($i, 'body');
			$sDateTime = $objDb->getField($i, 'date_time');

			$sSubject = str_replace("***", "", $sSubject);
?>
		  <div class="<?= ((($i % 2) == 0) ? 'even' : 'odd') ?>Notification">
		    <b><?= $sSubject ?></b>

		    <div>
		      <i><?= formatDate($sDateTime, "F d, Y h:i A") ?></i>
		      <?= nl2br($sBody) ?><br />
		    </div>
		  </div>
<?
		}
?>
		</div>
	  </div>
	</div>


<?
		if (@strpos($_SESSION['Email'], "@apparelco.com") !== FALSE || @strpos($_SESSION['Email'], "@3-tree.com") !== FALSE)
		{
?>
	<div id="Chat" style="display:none;">
	  <div>
		<h2><img src="images/icons/close.gif" alt="Close" title="Close" onclick="hideTab('Chat');" />Chat</h2>

		<div id="ChatContents">
		  <div id="ChatArea">
<?
			$sSQL = "SELECT id, department FROM tbl_departments WHERE department!='Support Services' ORDER BY department";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iDepartmentId = $objDb->getField($i, 'id');
				$sDepartment   = $objDb->getField($i, 'department');


				$sSQL = "SELECT id, name
				         FROM tbl_users
				         WHERE status='A' AND designation_id IN (SELECT id FROM tbl_designations WHERE department_id='$iDepartmentId') AND id IN ($sOnlineUsers)
				         ORDER BY name";
				$objDb2->query($sSQL);

				$iCount2 = $objDb2->getCount( );
?>
			<b class="<?= (($iCount2 == 0) ? 'close' : 'open') ?>"><?= $sDepartment ?></b>
			<ul>
<?
				for ($j = 0; $j < $iCount2; $j ++)
				{
					$iUserId = $objDb2->getField($j, 'id');
					$sName   = $objDb2->getField($j, 'name');
?>
			  <li onclick="showChatWin('<?= $iUserId ?>');"><img src="images/icons/online.png" alt="" title="" /><?= $sName ?></li>
<?
				}
?>
			</ul>
<?
			}
?>
		  </div>
		</div>
	  </div>
	</div>
<?
		}
?>

  </div>
</div>
<?
	}
*/
?>


<div id="Processing" style="display:none;">
  <img src="images/loading.gif" alt="Processing..." title="Processing..." />
  Processing your request...
</div>

<div id="UserMessage" style="display:none;">
  User Message
</div>
