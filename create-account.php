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

	checkLogin(false);

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	if ($_POST['PostId'] != "")
		$_REQUEST = @unserialize($_SESSION[$_POST['PostId']]);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/create-account.js"></script>
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
			    <h1>Create Account</h1>

			    <form name="frmAccount" id="frmAccount" method="post" action="do-create-account.php" class="frmOutline" onsubmit="$('BtnCreate').disable( );">

			    <div style="padding:10px 10px 25px 10px;">
			      <b>Welcome to Triple Tree Customer Portal</b><br />
			      <br />
			      Please provide the required information below in order to create an account.<br />
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

			    <h2>Personal Information</h2>
			    <table width="88%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="120">Full Name<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="Name" value="<?= IO::strValue('Name') ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
				    <td>Address</td>
				    <td align="center">:</td>
				    <td><textarea name="Address" rows="3" cols="30"><?= IO::strValue('Address') ?></textarea></td>
				  </tr>

				  <tr>
				    <td>City</td>
				    <td align="center">:</td>
				    <td><input type="text" name="City" value="<?= IO::strValue('City') ?>" maxlength="50" size="15" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>State/Province</td>
				    <td align="center">:</td>
				    <td><input type="text" name="State" value="<?= IO::strValue('State') ?>" maxlength="50" size="15" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Zip/Post Code</td>
				    <td align="center">:</td>
				    <td><input type="text" name="ZipCode" value="<?= IO::strValue('ZipCode') ?>" maxlength="20" size="15" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Country</td>
				    <td align="center">:</td>

				    <td>
					  <select name="Country">
					    <option value="">[ select country ]</option>
<?
	$sSQL = "SELECT id, country FROM tbl_countries ORDER BY country";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId   = $objDb->getField($i, 0);
		$sName = $objDb->getField($i, 1);
?>
		            	<option value="<?= $iId ?>"<?= ((IO::intValue("Country") == $iId) ? " selected" : "") ?>><?= $sName ?></option>
<?
	}
?>
					  </select>
				    </td>
				  </tr>
				</table>

				<br />

			    <h2>Contact Information</h2>
			    <table width="88%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="120">Email Address<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="Email" id="Email" value="<?= IO::strValue('Email') ?>" maxlength="100" size="30" class="textbox" onblur="checkEmail( );" /> <span id="EmailResult"></span></td>
				  </tr>

				  <tr>
				    <td>Phone</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Phone" value="<?= IO::strValue('Phone') ?>" maxlength="25" size="15" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Mobile<span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="text" name="Mobile" value="<?= IO::strValue('Mobile') ?>" maxlength="25" size="15" class="textbox" /></td>
				  </tr>
				</table>

				<br />

			    <h2>Login Information</h2>
			    <table width="88%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="120">Username<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="Username" id="Username" value="<?= IO::strValue('Username') ?>" maxlength="25" size="30" class="textbox" onblur="checkUsername( );" /> <span id="UsernameResult"></span></td>
				  </td>

				  <tr>
				    <td>Password<span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="password" name="Password" value="<?= IO::strValue('Password') ?>" size="30" maxlength="25" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Re-type Password<span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="password" name="RetypePassword" value="<?= IO::strValue('RetypePassword') ?>" size="30" maxlength="25" class="textbox" /></td>
				  </tr>
			    </table>

				<br />

			    <h2>&nbsp;</h2>

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
			      <input type="submit" id="BtnCreate" value="" class="btnSubmit" onclick="return validateForm( );" />
			      <input type="button" value="" class="btnCancel" onclick="document.location='./';" />
			    </div>
			    </form>

			    <br />
			    <b>Note:</b> Fields marked with an asterisk (*) are required.<br/>
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