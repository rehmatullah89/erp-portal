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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id       = IO::intValue('Id');
	$sReferer = $_SERVER['HTTP_REFERER'];

	$sSQL = "SELECT * FROM tbl_web_messages WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect($sReferer, "DB_ERROR");

	$sName     = $objDb->getField(0, "name");
	$sEmail    = $objDb->getField(0, "email");
	$sSubject  = $objDb->getField(0, "subject");
	$sMessage  = $objDb->getField(0, "message");
	$sDateTime = $objDb->getField(0, "date_time");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/admin/reply-web-message.js"></script>
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
			    <h1>Reply Web Messages</h1>

			    <form name="frmData" id="frmData" method="post" action="admin/save-web-message-reply.php" class="frmOutline" onsubmit="$('BtnSave').disable( );">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $sReferer ?>" />

				<h2>Web Message</h2>
				<table border="0" cellpadding="3" cellspacing="0" width="95%" align="center">
				  <tr>
					<td width="70">Date / Time</td>
					<td width="20" align="center">:</td>
					<td><?= formatDate($sDateTime, "l, jS F, Y   h:i A") ?></td>
				  </tr>

				  <tr>
					<td>Name</td>
					<td align="center">:</td>
					<td><?= $sName ?></td>
				  </tr>

				  <tr>
					<td>Email</td>
					<td align="center">:</td>
					<td><?= $sEmail ?></td>
				  </tr>

				  <tr>
					<td>Subject</td>
					<td align="center">:</td>
					<td><?= $sSubject ?></td>
				  </tr>

				  <tr valign="top">
					<td>Message</td>
					<td align="center">:</td>
					<td><?= nl2br($sMessage) ?></td>
				  </tr>
				</table>

<?
	$sSQL = "SELECT * FROM tbl_web_message_replies WHERE message_id='$Id' ORDER BY id ASC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
?>
				<br />
				<h2>Message Replies</h2>

				<div style="padding:10px;">

<?
		for ($i = 0; $i < $iCount; $i ++)
		{
?>
				  <div style="background:#f6f6f6; padding:10px;">
					<h4><?= formatDate($objDb->getField($i, "date_time"), "l, jS F, Y   h:i A") ?></h4>
					<?= nl2br($objDb->getField($i, "message")) ?>
				  </div>

<?
			if ($i < ($iCount - 1))
			{
?>
			  	  <hr />
<?
			}
		}
?>

				</div>
<?
	}
?>

				<br />
<?
	if ($_POST['PostId'] != "")
		$_REQUEST = @unserialize($_SESSION[$_POST['PostId']]);
?>
			    <h2>Web Message Reply</h2>
			    <table width="95%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr valign="top">
				    <td width="55">Message<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>
				    <td><textarea name="Message" style="width:100%; height:150px;"><?= IO::getFormValue('Message') ?></textarea></td>
				  </tr>
				</table>
			    </div>

				<br />

			    <div class="buttonsBar">
			      <input type="submit" id="BtnSave" value="" class="btnSave" onclick="return validateForm( );" />
			      <input type="button" value="" class="btnBack" onclick="document.location='<?= $sReferer ?>';" />
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
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>