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

	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	if ($_POST['PostId'] != "")
		$_REQUEST = @unserialize($_SESSION[$_POST['PostId']]);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/contact-us.js"></script>
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
			  <td width="585">
			    <h1>Contact Us</h1>

			    <form name="frmContact" id="frmContact" method="post" action="send-mail.php" class="frmOutline" onsubmit="$('BtnSubmit').disable( );">
			    <a href="http://www.google.com/maps/ms?source=s_q&hl=en&geocode=&ie=UTF8&t=k&msa=0&ll=31.40907,74.22654&spn=0.002791,0.004485&z=15&msid=106295231107374650613.000466a48e78b5e7477bc" target="_blank"><img src="images/headers/contact-us.jpg" width="581" height="205" alt="" title="" /></a><br />

			    <div style="padding:10px 10px 25px 10px;">
			      Please provide the required information below with your message details.<br />
			    </div>

<?
	if ($_POST["Error"] != "")
	{
?>
				<div class="error">
				  <b>Please provide the valid values of following fields:</b><br />
				  <br style="line-height:5px;" />
				  <?= $_POST["Error"] ?><br />
				</div>

				<br />

<?
	}
?>

			    <h2>Contact Form</h2>
			    <table width="88%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="90">Full Name<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="Name" value="<?= ((IO::strValue('Name') == "") ? $_SESSION['Name'] : IO::strValue('Name')) ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Email Address<span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="text" name="Email" value="<?= ((IO::strValue('Email') == "") ? $_SESSION['Email'] : IO::strValue('Email')) ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Subject<span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="text" name="Subject" value="<?= IO::strValue('Subject') ?>" maxlength="255" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr valign="top">
				    <td>Message<span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><textarea name="Message" style="width:99%; height:150px;"><?= IO::getFormValue('Message') ?></textarea></td>
				  </tr>
				</table>

			    <div style="padding:10px 0px 20px 40px;">
			      Please enter the Spam Protection code below in the box:<br />

			      <table width="100%" cellspacing="0" cellpadding="0" border="0" class="text">
				    <tr>
				      <td width="125"><img src="requires/captcha.php" width="120" height="22" vspace="5" alt="" title="" /></td>
				      <td><input type="text" name="SpamCode" maxlength="5" value="" size="15" autocomplete="off" class="textbox" style="padding:3px; height:14px;" /></td>
				    </tr>
			      </table>
			    </div>

			    <div class="buttonsBar">
			      <input type="submit" id="BtnSubmit" value="" class="btnSubmit" onclick="return validateForm( );" />
			      <input type="button" value="" class="btnCancel" onclick="document.location='./';" />
			    </div>
			    </form>

			    <br />
			    <b>Note:</b> Fields marked with an asterisk (<span class="mandatory">*</span>) are required.<br />
			  </td>

			  <td width="5"></td>

			  <td>
<?
	@include($sBaseDir."includes/sign-in.php");
?>

			    <div style="height:5px;"></div>

<?
	@include($sBaseDir."includes/contact-info.php");
?>
			  </td>
			</tr>
		  </table>
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