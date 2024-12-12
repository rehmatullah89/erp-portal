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

	if ($_SESSION['UserId'] == "")
	{
?>
			    <div id="LoginArea" style="display:block;">
			      <div>
			        <h1 class="green"><b>Sign In Here</b></h1>

			        <div class="block">
			          <div class="blockBottom">
			            <div class="blockTop">
					      <div style="padding:8px;">Please provide your Username and Password to login into the secure area.</div>

					      <form name="frmLogin" id="frmLogin" method="post" action="do-sign-in.php" onsubmit="$('BtnSignIn').disable( );">
                                                  <br /><br />
					      <table border="0" cellpadding="0" cellspacing="0" width="100%">
					        <tr>
					          <td width="120" align="right">Username : &nbsp;</td>
					          <td><div class="textboxBg"><input type="text" name="Username" value="<?= ((IO::strValue('Username') != '') ? IO::strValue('Username') : $_COOKIE['PortalUsername']) ?>" maxlength="25" /></div></td>
					        </tr>

					        <tr>
					          <td align="right">Password : &nbsp;</td>
					          <td><div class="textboxBg"><input type="password" name="Password" value="<?= $_COOKIE['PortalPassword'] ?>" maxlength="25" /></div></td>
 					        </tr>

 					        <tr>
 					          <td colspan="2" height="2"></td>
 					        </tr>

						    <tr>
							  <td align="right"><input type="checkbox" name="Remember" value="Y" <?= (($_COOKIE['PortalUsername'] != "" && $_COOKIE['PortalPassword'] != "") ? 'checked="checked"' : "") ?> /> &nbsp;</td>
							  <td>&nbsp;Remember my Login Info</td>
 						    </tr>
					      </table>

                                              <br /><br /><br />

					      <table border="0" cellpadding="0" cellspacing="0" width="100%">
					        <tr class="grayBar">
					          <td align="center"><a href="create-account.php">Create Account</a> | <a href="./" onclick="showPasswordForm( ); return false;">Forgot Password?</a></td>
					          <td width="103"><input type="submit" id="BtnSignIn" value="" class="btnSignIn" onclick="return validateLoginForm( );" /></td>
					        </tr>
					      </table>
					      </form>
			            </div>
			          </div>
			        </div>
			      </div>
			    </div>

			    <div id="PasswordArea" style="display:none;">
			      <div>
			        <h1 class="green"><img src="images/h1/forgot-password.jpg" width="249" height="20" vspace="10" alt="" title="" /></h1>

			        <div class="block">
			          <div class="blockBottom">
			            <div class="blockTop">
					      <div style="padding:10px;">Please provide your Account Email Address in order to reset your password.</div>

					      <form name="frmPassword" id="frmPassword" onsubmit="return false;">
					      <table border="0" cellpadding="0" cellspacing="0" width="100%">
					        <tr>
					          <td width="120" align="right">Email Address : &nbsp;</td>
					          <td><div class="textboxBg"><input type="text" name="Email" value="" maxlength="100" /></div></td>
					        </tr>
					      </table>

					      <br />
					      <br />
					      <br />
					      <br />

					      <table border="0" cellpadding="0" cellspacing="0" width="100%">
					        <tr class="grayBar">
					          <td align="center"><a href="create-account.php">Create Account</a> | <a href="./" onclick="showLoginForm( ); return false;">Account Login</a></td>
					          <td width="103"><input type="submit" value="" class="btnSubmitCurve" onclick="validatePasswordForm( );" /></td>
					        </tr>
					      </table>
					      </form>
			            </div>
			          </div>
			        </div>
			      </div>
			    </div>

			    <div id="ResultArea" style="display:none;">
			      <div>
			        <h1 class="green"><img src="images/h1/forgot-password.jpg" width="249" height="20" vspace="10" alt="" title="" /></h1>

			        <div class="block">
			          <div class="blockBottom">
			            <div class="blockTop">
					      <div style="padding:10px; height:125px;" id="ResultMsg">System Message</div>

					      <table border="0" cellpadding="10" cellspacing="0" width="100%">
					        <tr class="grayBar">
					          <td width="100%" align="center"><a href="create-account.php">Create Account</a> | <a href="./" onclick="showLoginFromResult( ); return false;">Account Login</a> | <a href="./" onclick="showPasswordFromResult( ); return false;">Forgot Password?</a></td>
					        </tr>
					      </table>
			            </div>
			          </div>
			        </div>
			      </div>
			    </div>
<?
	}

	else
	{
?>
				<h1 class="green"><b>My Profile</b></h1>

				<div class="block">
				  <div class="blockBottom">
					<div class="blockTop">
<?
		@include($sBaseDir."includes/profile.php");
?>
					</div>
				  </div>
				</div>
<?
	}
?>