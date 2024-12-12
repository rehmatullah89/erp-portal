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

	checkLogin( );

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$sSQL = "SELECT * FROM tbl_users WHERE id='{$_SESSION['UserId']}'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect(SITE_URL, "DB_ERROR");

	$sName              = $objDb->getField(0, "name");
	$sGender            = $objDb->getField(0, "gender");
	$sDob               = $objDb->getField(0, "dob");
	$sAddress           = $objDb->getField(0, "address");
	$sCity              = $objDb->getField(0, "city");
	$sState             = $objDb->getField(0, "state");
	$sZipCode           = $objDb->getField(0, "zip_code");
	$sCountry           = $objDb->getField(0, "country_id");
	$sEmail             = $objDb->getField(0, "email");
	$sPhone             = $objDb->getField(0, "phone");
	$sMobile            = $objDb->getField(0, "mobile");
	$sPicture           = $objDb->getField(0, "picture");
	$sSignature         = $objDb->getField(0, "signature");
	$sMaritalStatus     = $objDb->getField(0, "marital_status");
	$sSpouseName        = $objDb->getField(0, "spouse_name");
	$iChildren          = $objDb->getField(0, "children");
	$sBloodGroup        = $objDb->getField(0, "blood_group");
	$sEmergencyName     = $objDb->getField(0, "emergency_name");
	$sEmergencyPhone    = $objDb->getField(0, "emergency_phone");
	$sEmergencyAddress  = $objDb->getField(0, "emergency_address");
	$sPersonalGoals     = $objDb->getField(0, "personal_goals");
	$sTrainingsRequired = $objDb->getField(0, "trainings_required");

	@list($iYear, $iMonth, $iDay) = @explode("-", $sDob);

	if ($_POST['PostId'] != "")
	{
		$_REQUEST = @unserialize($_SESSION[$_POST['PostId']]);

		$sGender            = IO::strValue("Gender");
		$iMonth             = IO::intValue("Month");
		$iDay               = IO::intValue("Day");
		$iYear              = IO::intValue("Year");
		$sAddress           = IO::strValue("Address");
		$sCity              = IO::strValue("City");
		$sState             = IO::strValue("State");
		$sZipCode           = IO::strValue("ZipCode");
		$sCountry           = IO::strValue("Country");
		$sPhone             = IO::strValue("Phone");
		$sMobile            = IO::strValue("Mobile");
		$sMaritalStatus     = IO::strValue("MaritalStatus");
		$sSpouseName        = IO::strValue("SpouseName");
		$iChildren          = IO::intValue("Children");
		$sBloodGroup        = IO::strValue("BloodGroup");
		$sEmergencyName     = IO::strValue("EmergencyName");
		$sEmergencyPhone    = IO::strValue("EmergencyPhone");
		$sEmergencyAddress  = IO::strValue("EmergencyAddress");
		$sPersonalGoals     = IO::strValue("PersonalGoals");
		$sTrainingsRequired = IO::strValue("TrainingsRequired");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/my-account.js"></script>
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
			    <h1>My Account</h1>

			    <form name="frmAccount" id="frmAccount" method="post" action="save-my-account.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disable( );">
			    <input type="hidden" name="OldPicture" value="<?= $sPicture ?>" />
			    <input type="hidden" name="OldSignature" value="<?= $sSignature ?>" />

			    <div style="padding:10px 10px 25px 10px;">
			      Please change the necessary information below and click the "Save" button in order to update your Account.<br />
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

<?
	}
?>

			    <h2>Personal Information</h2>
			    <table width="88%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="120">Name</td>
				    <td width="20" align="center">:</td>
				    <td><b><?= $sName ?></b></td>
				  </tr>

				  <tr>
				    <td>Gender<span class="mandatory">*</span></td>
				    <td align="center">:</td>

				    <td>
					  <select name="Gender">
						<option value="">Choose one</option>
						<option value="Male"<?= (($sGender == "Male") ? " selected" : "") ?>>Male</option>
						<option value="Female"<?= (($sGender == "Female") ? " selected" : "") ?>>Female</option>
					  </select>
				    </td>
				  </tr>

				  <tr valign="top">
				    <td>Date of Birth<span class="mandatory">*</span></td>
				    <td align="center">:</td>

					<td>
					  <select name="Month" id="Month">
						<option value="">Month</option>
<?
	$sMonths = array('January','February','March','April','May','June','July','August','September','October','November','December');

	for ($i = 0; $i < count($sMonths); $i ++)
	{
?>
						<option value="<?= str_pad(($i + 1), 2, '0', STR_PAD_LEFT) ?>"<?= (($iMonth == ($i + 1)) ? " selected" : "") ?>><?= $sMonths[$i] ?></option>
<?
	}
?>
					  </select>

					  <select name="Day" id="Day">
						<option value="">Day</option>
<?
	for ($i = 1; $i <= 31; $i ++)
	{
?>
						<option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"<?= (($iDay == $i) ? " selected" : "") ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
<?
	}
?>
					  </select>

					  <select name="Year" id="Year">
						<option value="">Year</option>
<?
	for ($i = (date("Y") - 11); $i >= (date("Y") - 100); $i --)
	{
?>
						<option value="<?= $i ?>"<?= (($iYear == $i) ? " selected" : "") ?>><?= $i ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
				    <td>Address</td>
				    <td align="center">:</td>
				    <td><textarea name="Address" rows="3" cols="30"><?= $sAddress ?></textarea></td>
				  </tr>

				  <tr>
				    <td>City<span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="text" name="City" value="<?= $sCity ?>" maxlength="50" size="15" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>State/Province</td>
				    <td align="center">:</td>
				    <td><input type="text" name="State" value="<?= $sState ?>" maxlength="50" size="15" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Zip/Post Code</td>
				    <td align="center">:</td>
				    <td><input type="text" name="ZipCode" value="<?= $sZipCode ?>" maxlength="20" size="15" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Country<span class="mandatory">*</span></td>
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
		            	<option value="<?= $iId ?>"<?= (($sCountry == $iId) ? " selected" : "") ?>><?= $sName ?></option>
<?
	}
?>
					  </select>
				    </td>
				  </tr>

				  <tr>
				    <td>Picture</td>
				    <td align="center">:</td>

				    <td>
					  <input type="file" name="Picture" size="21" class="textbox" />
<?
	if ($sPicture != "" && @file_exists($sBaseDir.USERS_IMG_PATH.'originals/'.$sPicture))
	{
?>
                      &nbsp; ( <a href="<?= USERS_IMG_PATH.'originals/'.$sPicture ?>" class="lightview" title="<?= $_SESSION['Name'] ?>">view</a> )
<?
	}
?>
					</td>
				  </tr>

				  <tr>
				    <td>Signature</td>
				    <td align="center">:</td>

				    <td>
					  <input type="file" name="Signature" size="21" class="textbox" /><br />(Recommended Size: 300 x 150)
<?
	if ($sSignature != "" && @file_exists($sBaseDir.USER_SIGNATURES_IMG_DIR.$sSignature))
	{
?>
                      &nbsp; - ( <a href="<?= USER_SIGNATURES_IMG_DIR.$sSignature ?>" class="lightview" title="<?= $sName ?>">view</a> )
<?
	}
?>
					</td>
				  </tr>
				</table>

				<br />

			    <h2>Contact Information</h2>
			    <table width="88%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="120">Email Address</td>
				    <td width="20" align="center">:</td>
				    <td><b><?= $sEmail ?></b></td>
				  </tr>

				  <tr>
				    <td>Phone</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Phone" value="<?= $sPhone ?>" maxlength="25" size="15" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Mobile<span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="text" name="Mobile" value="<?= $sMobile ?>" maxlength="25" size="15" class="textbox" /></td>
				  </tr>
				</table>

				<br />

			    <h2>Personal Profile (for Company)</h2>
			    <table width="88%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="120">Marital Status<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>

				    <td>
					  <select name="MaritalStatus">
						<option value="">Choose one</option>
						<option value="Single"<?= (($sMaritalStatus == "Single") ? " selected" : "") ?>>Single</option>
						<option value="Married"<?= (($sMaritalStatus == "Married") ? " selected" : "") ?>>Married</option>
						<option value="Other"<?= (($sMaritalStatus == "Other") ? " selected" : "") ?>>Other</option>
					  </select>
				    </td>
				  </tr>

				  <tr>
				    <td>Spouse Name</td>
				    <td align="center">:</td>
				    <td><input type="text" name="SpouseName" value="<?= $sSpouseName ?>" maxlength="50" size="30" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>No of Children</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Children" value="<?= $iChildren ?>" maxlength="2" size="12" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Blood Group<span class="mandatory">*</span></td>
				    <td align="center">:</td>

				    <td>
					  <select name="BloodGroup">
						<option value="">Choose one</option>
						<option value="A+"<?= (($sBloodGroup == "A+") ? " selected" : "") ?>>A+</option>
						<option value="A-"<?= (($sBloodGroup == "A-") ? " selected" : "") ?>>A-</option>
						<option value="B+"<?= (($sBloodGroup == "B+") ? " selected" : "") ?>>B+</option>
						<option value="B-"<?= (($sBloodGroup == "B-") ? " selected" : "") ?>>B-</option>
						<option value="O+"<?= (($sBloodGroup == "O+") ? " selected" : "") ?>>O+</option>
						<option value="O-"<?= (($sBloodGroup == "O-") ? " selected" : "") ?>>O-</option>
						<option value="AB+"<?= (($sBloodGroup == "AB+") ? " selected" : "") ?>>AB+</option>
						<option value="AB-"<?= (($sBloodGroup == "AB-") ? " selected" : "") ?>>AB-</option>
					  </select>
				    </td>
				  </tr>

				  <tr>
				    <td>Emergency Name</td>
				    <td align="center">:</td>
				    <td><input type="text" name="EmergencyName" value="<?= $sEmergencyName ?>" maxlength="50" size="30" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Emergency Phone</td>
				    <td align="center">:</td>
				    <td><input type="text" name="EmergencyPhone" value="<?= $sEmergencyPhone ?>" maxlength="25" size="30" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
				    <td>Emergency Address</td>
				    <td align="center">:</td>
				    <td><textarea name="EmergencyAddress" rows="3" cols="30"><?= $sEmergencyAddress ?></textarea></td>
				  </tr>

				  <tr valign="top">
				    <td>Personal Goals / Objectives</td>
				    <td align="center">:</td>
				    <td><textarea name="PersonalGoals" rows="3" cols="30"><?= $sPersonalGoals ?></textarea></td>
				  </tr>

				  <tr valign="top">
				    <td>Trainings Required / Recomended</td>
				    <td align="center">:</td>
				    <td><textarea name="TrainingsRequired" rows="3" cols="30"><?= $sTrainingsRequired ?></textarea></td>
				  </tr>
				</table>

				<br />

			    <h2>Login Information</h2>
			    <table width="88%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="120">Username<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="Username" value="<?= $_SESSION['Username'] ?>" readonly size="30" class="textbox" /></td>
				  </td>

				  <tr>
				    <td>Password*</td>
				    <td align="center">:</td>
				    <td><input type="password" name="Password" value="" size="30" maxlength="25" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Re-type Password*</td>
				    <td align="center">:</td>
				    <td><input type="password" name="RetypePassword" value="" size="30" maxlength="25" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td colspan="3"><i>Leave password fields empty if you don't want to change your password.</i></td>
				  </tr>
			    </table>

				<br />

			    <div class="buttonsBar">
			      <input type="submit" id="BtnSave" value="" class="btnSave" onclick="return validateForm( );" />
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