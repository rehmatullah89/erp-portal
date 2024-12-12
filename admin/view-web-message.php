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

	$Id = IO::intValue('Id');

	$sSQL = "SELECT * FROM tbl_web_messages WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sName     = $objDb->getField(0, "name");
		$sEmail    = $objDb->getField(0, "email");
		$sSubject  = $objDb->getField(0, "subject");
		$sMessage  = $objDb->getField(0, "message");
		$sDateTime = $objDb->getField(0, "date_time");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="PopupDiv">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body">
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr bgcolor="#ffffff" valign="top">
		  <td width="100%" style="height:547px;">

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

			<br />
			<h2>Message Replies</h2>

			<div style="padding:10px;">

<?
	$sSQL = "SELECT * FROM tbl_web_message_replies WHERE message_id='$Id' ORDER BY id ASC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

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

	if ($iCount == 0)
	{
?>
			  <div class="noRecord">No Reply Posted Yet!</div>
<?
	}
?>

			</div>

			<br />
		  </td>
		</tr>
	  </table>

	</div>
<!--  Body Section Ends Here  -->


  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>