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
	$objDb2      = new Database( );

	if ($_POST)
		@include("editor-mail.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/editor.js"></script>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:354px; height:354px;">
	  <h2>Write to Editor</h2>

	  <form name="frmEditor" id="frmEditor" method="post" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSubmit').disable( );">

<?
	if ($_POST && $sError != "")
	{
?>
		<div class="error">
		  <b>Please provide the valid values for following fields:</b><br />
		  <br style="line-height:5px;" />
		  <?= $sError ?><br />
		</div>

		<br />

<?
	}


	if ($_POST && $sError == "")
	{
?>
		<div style="padding:25px;">
		  Thank you, your message has been sent successfully.<br />
		</div>
<?
	}

	else
	{
?>

		<table width="100%" cellspacing="0" cellpadding="4" border="0">
		  <tr>
			<td width="50">Subject</td>
			<td width="20" align="center">:</td>
			<td><input type="text" name="Subject" value="<?= IO::strValue('Subject') ?>" maxlength="255" class="textbox" style="width:98%;" /></td>
		  </tr>

		  <tr valign="top">
			<td>Message</td>
			<td align="center">:</td>
			<td><textarea name="Message" style="width:98%; height:250px;"><?= IO::getFormValue('Message') ?></textarea></td>
		  </tr>
		</table>

		<div class="buttonsBar"><input type="submit" id="BtnSubmit" value="" class="btnSubmit" onclick="return validateForm( );" /></div>
<?
	}
?>
	  </form>
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