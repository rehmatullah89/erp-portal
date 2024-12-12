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

	checkLogin(false);

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	if ($_POST['PostId'] != "")
		$_REQUEST = @unserialize($_SESSION[$_POST['PostId']]);

	$UserId = IO::intValue('uid');
	$Email  = IO::strValue('email');
	$Code   = IO::strValue('code');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/change-password.js"></script>
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
			    <h1><img src="images/h1/change-password.jpg" width="255" height="20" vspace="10" alt="" title="" /></h1>
<?
	$sSQL = "SELECT name FROM tbl_users WHERE id='$UserId' AND email='$Email' AND RIGHT(password, 16)='$Code'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sName = $objDb->getField(0, 0);
?>
			    <form name="frmChangePassword" id="frmChangePassword" method="post" action="save-password.php" class="frmOutline" onsubmit="$('BtnChange').disable( );">
			    <input type="hidden" name="UserId" value="<?= $UserId ?>" />
			    <input type="hidden" name="Email" value="<?= $Email ?>" />
			    <input type="hidden" name="Code" value="<?= $Code ?>" />

			    <div style="padding:10px 10px 25px 10px;">
			      <b>Here you can reset your Account Password.</b><br />
			      <br />
			      Please provide the new password below in order to update your account password.<br />
			    </div>

			    <table width="88%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="115">Full Name</td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="Name" value="<?= $sName ?>" size="31" readonly class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Email Address</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Email" id="Email" value="<?= $Email ?>" size="31" readonly class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Password<span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="password" name="Password" value="<?= IO::strValue('Password') ?>" size="31" maxlength="25" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Re-type Password<span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="password" name="RetypePassword" value="<?= IO::strValue('RetypePassword') ?>" size="31" maxlength="25" class="textbox" /></td>
				  </tr>
			    </table>

			    <div style="padding:20px 0px 20px 40px;">
			      Please enter the Spam Protection code below in the box:<br />

			      <table width="100%" cellspacing="0" cellpadding="0" border="0" class="text">
				    <tr>
				      <td width="125"><img src="requires/captcha.php" width="120" height="22" vspace="5" alt="" title="" /></td>
				      <td><input type="text" name="SpamCode" maxlength="5" value="" size="15" autocomplete="off" class="textbox" style="padding:3px; height:14px;" /></td>
				    </tr>
			      </table>
			    </div>

			    <div class="buttonsBar">
			      <input type="submit" id="BtnChange" value="" class="btnSubmit" onclick="return validateForm( );" />
			      <input type="button" value="" class="btnCancel" onclick="document.location='./';" />
			    </div>
			    </form>

			    <br />
			    <b>Note:</b> Fields marked with an asterisk (*) are required.<br/>
<?
	}

	else
	{
?>
	            <span class="error">Invalid Password Change Request.</span><br />
	            <br />
	            Please follow the link emailed to you to change your Account Password. Or try the "Forgot Password?" option again.<br />
<?
	}
?>
			  </td>

			  <td width="5"></td>

			  <td>
<?
	@include($sBaseDir."includes/sign-in.php");
?>

			    <div style="height:5px;"></div>

<?
	@include($sBaseDir."includes/custom-feeds.php");
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

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>