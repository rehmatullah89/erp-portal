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

	$Id       = IO::intValue('Id');
	$sReferer = $_SERVER['HTTP_REFERER'];

	$sSQL = "SELECT * FROM tbl_users WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect($sReferer, "DB_ERROR");

	$sName                   = $objDb->getField(0, "name");
	$sGender                 = $objDb->getField(0, "gender");
	$sDob                    = $objDb->getField(0, "dob");
	$sAddress                = $objDb->getField(0, "address");
	$sCity                   = $objDb->getField(0, "city");
	$sState                  = $objDb->getField(0, "state");
	$sZipCode                = $objDb->getField(0, "zip_code");
	$iCountryId              = $objDb->getField(0, "country_id");
	$sEmail                  = $objDb->getField(0, "email");
	$sPhone                  = $objDb->getField(0, "phone");
	$sMobile                 = $objDb->getField(0, "mobile");
	$sOrganization           = $objDb->getField(0, "organization");
	$sJoiningDate            = $objDb->getField(0, "joining_date");
	$sCardId                 = $objDb->getField(0, "card_id");
	$iOfficeId               = $objDb->getField(0, "office_id");
	$sPhoneExt               = $objDb->getField(0, "phone_ext");
	$iDesignationId          = $objDb->getField(0, "designation_id");
	$sNicNo                  = $objDb->getField(0, "nic_no");
	$sMaritalStatus          = $objDb->getField(0, "marital_status");
	$sSpouseName             = $objDb->getField(0, "spouse_name");
	$iChildren               = $objDb->getField(0, "children");
	$sBloodGroup             = $objDb->getField(0, "blood_group");
	$sEmergencyName          = $objDb->getField(0, "emergency_name");
	$sEmergencyPhone         = $objDb->getField(0, "emergency_phone");
	$sEmergencyAddress       = $objDb->getField(0, "emergency_address");
	$sPersonalGoals          = $objDb->getField(0, "personal_goals");
	$sTrainingsRequired      = $objDb->getField(0, "trainings_required");
	$sSignature              = $objDb->getField(0, "signature");
	$sPicture                = $objDb->getField(0, "picture");
	$sUsername               = $objDb->getField(0, "username");
	$sStatus                 = $objDb->getField(0, "status");
	$sAdmin                  = $objDb->getField(0, "admin");
	$sGuest                  = $objDb->getField(0, "guest");
	$sSurveyAdmin            = $objDb->getField(0, "survey_admin");
	$sAttendance             = $objDb->getField(0, "attendance");
	$sEmailCollection        = $objDb->getField(0, "email_collection");
	$sGrievancesManager      = $objDb->getField(0, "grievances_manager");
        $sEtdManager             = $objDb->getField(0, "etd_manager");
	$sAuditsManager          = $objDb->getField(0, "audits_manager");
	$sSkipImage              = $objDb->getField(0, "app_skip_image");
	$sShowUserSchedules      = $objDb->getField(0, "show_user_schedules");
	$sAuditor                = $objDb->getField(0, "auditor");
	$sAuditorLevel           = $objDb->getField(0, "auditor_level");
	$iAuditorType            = $objDb->getField(0, "auditor_type");
	$iAuditorCode            = $objDb->getField(0, "auditor_code");
	$sNonProductionSchedules = $objDb->getField(0, "non_production_schedules");
	$sEmailAlerts            = $objDb->getField(0, "email_alerts");
	$sBrands                 = $objDb->getField(0, "brands");
	$sVendors                = $objDb->getField(0, "vendors");
	$sSuppliers              = $objDb->getField(0, "suppliers");
	$sStyleCategories        = $objDb->getField(0, "style_categories");
	$sReportTypes            = $objDb->getField(0, "report_types");
	$sAuditStages            = $objDb->getField(0, "audit_stages");
        $sAuditServices          = $objDb->getField(0, "audit_services");
	$sLanguage               = $objDb->getField(0, "language");
	$sUserType               = $objDb->getField(0, "user_type");
        $sUserTypes              = $objDb->getField(0, "auditor_types");
        $sAppSections            = $objDb->getField(0, "app_sections");


	$sJoiningDate       = (($sJoiningDate == "0000-00-00") ? "" : $sJoiningDate);
	$sDob               = (($sDob == "0000-00-00") ? "" : $sDob);
	$sBrands            = @explode(",", $sBrands);
	$sVendors           = @explode(",", $sVendors);
	$sSuppliers         = @explode(",", $sSuppliers);
	$sStyleCategories   = @explode(",", $sStyleCategories);
	$sReportTypes       = @explode(",", $sReportTypes);
	$sAuditStages       = @explode(",", $sAuditStages);
	$sAuditServices     = @explode(",", $sAuditServices);
	
	$sLanguages         = getList("tbl_languages", "code", "language", "id>0", "position");
	$sClientsList       = getList("tbl_clients", "code", "title");
	$sUserTypesList     = getList("tbl_user_types ut, tbl_clients c", "ut.id", "ut.type" , "FIND_IN_SET(ut.id, c.user_types) AND c.code='$sUserType'");               
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/admin/edit-user.js"></script>
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
			    <h1>User Account</h1>

<?
	if ($sStatus == "A" && @in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
	{
?>
			    <form name="frmCopy" id="frmCopy" method="post" action="admin/copy-user-rights.php" class="frmOutline" onsubmit="$('BtnCopy').disabled=true;">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $sReferer ?>" />

				<h2>Copy User Rights</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="80">From User<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="User">
						<option value=""></option>
<?
		$sUsersList = getList("tbl_users", "id", "CONCAT(name, ' &lt;', email, '&gt;')", "status='A' AND id!='$Id'");

		foreach ($sUsersList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnCopy" value="" class="btnCopy" title="Copy" onclick="return validateCopyForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>

			    <form name="frmData" id="frmData" method="post" action="admin/save-user.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disable( );">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $sReferer ?>" />
			    <input type="hidden" name="OldPicture" value="<?= $sPicture ?>" />
			    <input type="hidden" name="OldSignature" value="<?= $sSignature ?>" />

			    <h2>Personal Information</h2>
			    <table width="95%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="150">Full Name<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="Name" value="<?= $sName ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Gender</td>
				    <td align="center">:</td>

				    <td>
					  <select name="Gender">
						<option value="">Choose one</option>
						<option value="Male"<?= (($sGender == "Male") ? " selected" : "") ?>>Male</option>
						<option value="Female"<?= (($sGender == "Female") ? " selected" : "") ?>>Female</option>
					  </select>
				    </td>
				  </tr>

				  <tr>
				    <td>Date of Birth</td>
				    <td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="Dob" id="Dob" value="<?= $sDob ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('Dob'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('Dob'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>

				  <tr valign="top">
				    <td>Address</td>
				    <td align="center">:</td>
				    <td><textarea name="Address" rows="3" cols="30"><?= $sAddress ?></textarea></td>
				  </tr>

				  <tr>
				    <td>City</td>
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
				    <td>Country</td>
				    <td align="center">:</td>

				    <td>
					  <select name="Country" id="Country">
					    <option value=""></option>
<?
	$sSQL = "SELECT id, country FROM tbl_countries ORDER BY country";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
		            	<option value="<?= $iKey ?>"<?= (($iKey == $iCountryId) ? " selected" : "") ?>><?= $sValue ?></option>
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
                      &nbsp; ( <a href="<?= USERS_IMG_PATH.'originals/'.$sPicture ?>" class="lightview" title="<?= $sName ?>">view</a> )
<?
	}
?>
					</td>
				  </tr>

				  <tr>
				    <td>Signature</td>
				    <td align="center">:</td>

				    <td>
					  <input type="file" name="Signature" size="21" class="textbox" /> (Recommended Size: 300 x 150)
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

			    <table width="95%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="150">Email Address<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="Email" value="<?= $sEmail ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

<?
	if ($_SESSION["UserType"] == "GLOBALEXPORTS")
	{
?>
				  <input type="hidden" name="EmailAlerts" value="Y" />
<?
	}
	
	else
	{
?>
				  <tr>
				    <td>Email Alerts</td>
				    <td align="center">:</td>

				    <td>
					  <select name="EmailAlerts">
						<option value="N"<?= (($sEmailAlerts == "N") ? " selected" : "") ?>>No</option>
						<option value="Y"<?= (($sEmailAlerts == "Y") ? " selected" : "") ?>>Yes</option>
					  </select>
				    </td>
				  </tr>
<?
	}
	
	
	if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
	{
?>
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
<?
	}
?>
				</table>

<?
	if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
	{
?>
				<br />
			    <h2>Company Information</h2>

			    <table width="95%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="150">Organization</td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="Organization" value="<?= $sOrganization ?>" maxlength="50"  size="30" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Joining Date</td>
				    <td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="JoiningDate" id="JoiningDate" value="<?= $sJoiningDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('JoiningDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('JoiningDate'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>

				  <tr>
				    <td>Attendance Card ID</td>
				    <td align="center">:</td>
				    <td><input type="text" name="CardId" value="<?= $sCardId ?>" maxlength="10" size="16" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Office</td>
				    <td align="center">:</td>

				    <td>
					  <select name="Office">
					    <option value=""></option>
<?
		$sSQL = "SELECT id, office FROM tbl_offices ORDER BY office";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iKey   = $objDb->getField($i, 0);
			$sValue = $objDb->getField($i, 1);
?>
		            	<option value="<?= $iKey ?>"<?= (($iKey == $iOfficeId) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
				    </td>
				  </tr>

				  <tr>
				    <td>Phone Ext</td>
				    <td align="center">:</td>
				    <td><input type="text" name="PhoneExt" value="<?= $sPhoneExt ?>" maxlength="5" size="16" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Designation</td>
				    <td align="center">:</td>

				    <td>
					  <select name="Designation">
					    <option value=""></option>
<?
		$sDepartmentsList  = getList("tbl_departments", "id", "department", "", "position");

		foreach ($sDepartmentsList as $iDepartment => $sDepartment)
		{
?>
					    <optgroup label="<?= $sDepartment ?>">
<?
			$sSQL = "SELECT id, designation FROM tbl_designations WHERE department_id='$iDepartment' ORDER BY designation";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iDesignation = $objDb->getField($i, 'id');
				$sDesignation = $objDb->getField($i, 'designation');
?>
						  <option value="<?= $iDesignation ?>"<?= (($iDesignation == $iDesignationId) ? " selected" : "") ?>><?= $sDesignation ?></option>
<?
			}
?>
					    </optgroup>
<?
		}
?>
					  </select>
				    </td>
				  </tr>
				</table>

				<br />
			    <h2>Personal Profile (for Company)</h2>

			    <table width="95%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="150">NIC No</td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="NicNo" value="<?= $sNicNo ?>" maxlength="20" size="30" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Marital Status</td>
				    <td align="center">:</td>

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
				    <td>Blood Group</td>
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
<?
	}
?>

				<br />
			    <h2>Login Information</h2>

			    <table width="95%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="150">Username<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="Username" value="<?= $sUsername ?>" size="30" maxlength="25" class="textbox" /></td>
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
				    <td colspan="3"><i>Leave password fields empty if you don't want to change the password.</i></td>
				  </tr>
			    </table>

				<br />
			    <h2>Access Details</h2>

			    <table width="95%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="150">Status<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>

				    <td>
					  <select name="Status">
						<option value="P"<?= (($sStatus == "P") ? " selected" : "") ?>>Not Approved</option>
						<option value="A"<?= (($sStatus == "A") ? " selected" : "") ?>>Active</option>
						<option value="D"<?= (($sStatus == "D") ? " selected" : "") ?>>Disabled</option>
						<option value="L"<?= (($sStatus == "L") ? " selected" : "") ?>>Left</option>
					  </select>
				    </td>
				  </tr>
				  
				  <tr>
				    <td>Language</td>
				    <td align="center">:</td>

				    <td>
					  <select name="Language">
<?
	foreach($sLanguages as $LangCode => $Language) 
	{
?>
						<option value="<?=$LangCode;?>"<?= (($sLanguage == $LangCode) ? " selected" : "") ?>><?=$Language;?></option>
<?
	}
?>
					  </select>
				    </td>
				  </tr>				  

<?
	if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
	{
?>
				  <tr>
				    <td>Client Type</td>
				    <td align="center">:</td>

				    <td>
					  <select name="UserType" id="UserType" onchange="getListValues('UserType', 'AuditorTypes', 'ClientUserTypes');">
					    <option value="">Select a Client Type</option>
<?
		foreach($sClientsList as $sCode => $sClient)       
		{
?>
					    <option value="<?=$sCode?>" <?=($sUserType == $sCode)?'selected':''?>><?=$sClient?></option>
<?
		}   
?>
					  </select>
				    </td>
				  </tr>
				
				  <tr>
				    <td>User Types</td>
				    <td align="center">:</td>

				    <td>
					  <select name="UserTypes[]" id="AuditorTypes" style="width:190px;" multiple>                                            
<?
		foreach($sUserTypesList as $key => $sType)       
		{
?>
					    <option value="<?=$key?>" <?=in_array($key, explode(",", $sUserTypes))?'selected':''?>><?=$sType?></option>
<?
		}   
?>
					  </select>
				    </td>
				  </tr>
                                
                                <tr>
				    <td>Quonda App Sections</td>
				    <td align="center">:</td>
				    <td>
					  <select name="AppSections[]" id="AppSections" style="width:190px;" multiple>                                            
					    <option value="quonda" <?=in_array('quonda', explode(",", $sAppSections))?'selected':''?>>Quonda</option>
                                            <option value="protoware" <?=in_array('protoware', explode(",", $sAppSections))?'selected':''?>>Protoware</option>
                                            <option value="vsn" <?=in_array('vsn', explode(",", $sAppSections))?'selected':''?>>Vsn</option>
					  </select>
				    </td>
				  </tr>
				  
				  <tr>
				    <td>Guest Account</td>
				    <td align="center">:</td>
				    <td><input type="checkbox" name="Guest" value="Y" <?= (($sGuest == "Y") ? "checked" : "") ?> /></td>
				  </tr>

				  <tr>
				    <td>Reset Guest Account</td>
				    <td align="center">:</td>
				    <td><input type="checkbox" name="Reset" value="Y" /> (Assign PCC Brands/Vendors and Guest Account Rights)</td>
				  </tr>

				  <tr>
				    <td>Administrator</td>
				    <td align="center">:</td>

				    <td>
					  <select name="Admin">
						<option value="N"<?= (($sAdmin == "N") ? " selected" : "") ?>>No</option>
						<option value="Y"<?= (($sAdmin == "Y") ? " selected" : "") ?>>Yes</option>
					  </select>
				    </td>
				  </tr>

				  <tr>
				    <td>Grievances Manager</td>
				    <td align="center">:</td>

				    <td>
					  <select name="GrievancesManager">
						<option value="N"<?= (($sGrievancesManager == "N") ? " selected" : "") ?>>No</option>
						<option value="Y"<?= (($sGrievancesManager == "Y") ? " selected" : "") ?>>Yes</option>
					  </select>
				    </td>
				  </tr>
                                
                                <tr>
				    <td>ETD Manager</td>
				    <td align="center">:</td>

				    <td>
					  <select name="EtdManager">
						<option value="N"<?= (($sEtdManager == "N") ? " selected" : "") ?>>No</option>
						<option value="Y"<?= (($sEtdManager == "Y") ? " selected" : "") ?>>Yes</option>
					  </select>
				    </td>
				  </tr>

				  <tr>
				    <td>Survey Admin</td>
				    <td align="center">:</td>

				    <td>
					  <select name="SurveyAdmin">
						<option value="N"<?= (($sSurveyAdmin == "N") ? " selected" : "") ?>>No</option>
						<option value="Y"<?= (($sSurveyAdmin == "Y") ? " selected" : "") ?>>Yes</option>
					  </select>
				    </td>
				  </tr>

				  <tr>
				    <td>Attendance System</td>
				    <td align="center">:</td>

				    <td>
					  <select name="Attendance">
						<option value="N"<?= (($sAttendance == "N") ? " selected" : "") ?>>No</option>
						<option value="Y"<?= (($sAttendance == "Y") ? " selected" : "") ?>>Yes</option>
					  </select>
				    </td>
				  </tr>

				  <tr>
				    <td>Email Collections</td>
				    <td align="center">:</td>
				    <td><input type="checkbox" name="EmailCollection" value="Y" <?= (($sEmailCollection == "Y") ? "checked" : "") ?> /> (Check this option If you want to allow this user to email PCC Collections)</td>
				  </tr>
<?
	}
?>
				  <tr>
				    <td>Audits Manager</td>
				    <td align="center">:</td>

				    <td>
					  <select name="AuditsManager">
						<option value="N"<?= (($sAuditsManager == "N") ? " selected" : "") ?>>No</option>
						<option value="Y"<?= (($sAuditsManager == "Y") ? " selected" : "") ?>>Yes</option>
					  </select>
				    </td>
				  </tr>

				  <tr>
				    <td>Auditor</td>
				    <td align="center">:</td>

				    <td>
					  <select name="Auditor" onchange="if(this.value=='Y') { $('AuditorLevel').style.display='inline-block'; $('AuditorType').style.display='inline-block'; } else { $('AuditorLevel').style.display='none'; $('AuditorType').style.display='none'; }">
						<option value="N"<?= (($sAuditor == "N") ? " selected" : "") ?>>No</option>
						<option value="Y"<?= (($sAuditor == "Y") ? " selected" : "") ?>>Yes</option>
					  </select>

					  <select name="AuditorLevel" id="AuditorLevel" style="display:<?= (($sAuditor == "Y") ? 'inline-block' : 'none') ?>;">
						<option value="">Auditor Level</option>
						<option value="G"<?= (($sAuditorLevel == "G") ? " selected" : "") ?>>Green</option>
						<option value="B"<?= (($sAuditorLevel == "B") ? " selected" : "") ?>>Blue</option>
						<option value="Y"<?= (($sAuditorLevel == "Y") ? " selected" : "") ?>>Yellow</option>
						<option value="R"<?= (($sAuditorLevel == "R") ? " selected" : "") ?>>Red</option>
					  </select>

					  <select name="AuditorType" id="AuditorType" style="display:<?= (($sAuditor == "Y") ? 'inline-block' : 'none') ?>;">
						<option value="">Auditor Type</option>
<?
	foreach($sUserTypesList as $key => $sAuditorType)
	{
?>
						<option value="<?=$key?>" <?= (($iAuditorType == $key) ? " selected" : "") ?>><?=$sAuditorType?></option>
<?
	}
?>
					  </select>
				    </td>
				  </tr>
				  				  

<?
	if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
	{
?>
				  <tr>
				    <td>Skip Image (APP)</td>
				    <td align="center">:</td>

				    <td>
					  <select name="SkipImage">
						<option value="N"<?= (($sSkipImage == "N") ? " selected" : "") ?>>No</option>
						<option value="Y"<?= (($sSkipImage == "Y") ? " selected" : "") ?>>Yes</option>
					  </select>
				    </td>
				  </tr>

				  <tr>
				    <td>Non-Prod. Schedules</td>
				    <td align="center">:</td>

				    <td>
					  <select name="NonProductionSchedules">
						<option value="N"<?= (($sNonProductionSchedules == "N") ? " selected" : "") ?>>No</option>
						<option value="Y"<?= (($sNonProductionSchedules == "Y") ? " selected" : "") ?>>Yes</option>
					  </select>
				    </td>
				  </tr>

				  <tr>
				    <td>User Schedules</td>
				    <td align="center">:</td>

				    <td>
					  <select name="ShowUserSchedules">
						<option value="N"<?= (($sShowUserSchedules == "N") ? " selected" : "") ?>>No</option>
						<option value="Y"<?= (($sShowUserSchedules == "Y") ? " selected" : "") ?>>Yes</option>
					  </select>

					  <small>(Show Non-Production Schedules on Dashboard)</small>
				    </td>
				  </tr>
<?
	}
	
	
	if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
		$sBrandsList = getList("tbl_brands b", "b.id", "CONCAT(COALESCE((SELECT brand FROM tbl_brands WHERE id=b.parent_id), 'DELETED'), ' &raquo;&raquo; ', b.brand) AS _Brand", "b.parent_id > '0' AND b.qmip!='Y'", "_Brand");
	
	else
		$sBrandsList = getList("tbl_brands b", "b.id", "CONCAT(COALESCE((SELECT brand FROM tbl_brands WHERE id=b.parent_id), 'DELETED'), ' &raquo;&raquo; ', b.brand) AS _Brand", "b.parent_id > '0' AND b.qmip!='Y' AND FIND_IN_SET(b.id, '{$_SESSION['Brands']}')", "_Brand");
	
	if (count($sBrandsList) > 0)
	{
?>

				  <tr valign="top">
				    <td>
				      Brands<br />
				      <br />
					  - <a href="./" onclick="filterVendors('Y', 'B'); return false;">Brand Vendors</a><br />
<?
		if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
		{
?>
					  - <a href="./" onclick="filterVendors('Y', 'C'); return false;">Country Vendors</a><br />
					  - <a href="./" onclick="filterVendors('Y', 'BC'); return false;">Brand/Country Vendors</a><br />
<?
		}
?>
					  - <a href="./" onclick="filterVendors('N', ''); return false;">Clear Filter</a><br />
				    </td>

				    <td align="center">:</td>

				    <td>
					  <select id="Brands" name="Brands[]" multiple size="10" style="min-width:200px;">
<?
		foreach ($sBrandsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $sBrands)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>

					  <br />
					  <br style="line-height:3px;" />
					  [ <a href="./" onclick="selectAll('Brands'); return false;">Select All</a> | <a href="./" onclick="clearAll('Brands'); return false;">Clear</a> ]<br />
				    </td>
				  </tr>

<?
	}
	
	
	if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GLOBALEXPORTS", "GAIA")))
	{
?>
				  <tr valign="top">
				    <td>QMIP Brands</td>
				    <td align="center">:</td>

				    <td>
					  <select id="QmipBrands" name="Brands[]" multiple size="10" style="min-width:200px;">
<?
		if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
			$sBrandsList = getList("tbl_brands b", "b.id", "CONCAT(COALESCE((SELECT brand FROM tbl_brands WHERE id=b.parent_id), 'DELETED'), ' &raquo;&raquo; ', b.brand) AS _Brand", "b.parent_id>'0' AND b.qmip='Y'", "_Brand");
		
		else
			$sBrandsList = getList("tbl_brands b", "b.id", "CONCAT(COALESCE((SELECT brand FROM tbl_brands WHERE id=b.parent_id), 'DELETED'), ' &raquo;&raquo; ', b.brand) AS _Brand", "b.parent_id > '0' AND b.qmip='Y' AND FIND_IN_SET(b.id, '{$_SESSION['Brands']}')", "_Brand");

		foreach ($sBrandsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $sBrands)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>

					  <br />
					  <br style="line-height:3px;" />
					  [ <a href="./" onclick="selectAll('QmipBrands'); return false;">Select All</a> | <a href="./" onclick="clearAll('QmipBrands'); return false;">Clear</a> ]<br />
				    </td>
				  </tr>
<?
	}
?>

				  <tr valign="top">
				    <td>Vendors</td>
				    <td align="center">:</td>

				    <td>
					  <select id="Vendors" name="Vendors[]" multiple size="10" style="min-width:200px;">
<?
		if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
			$sVendorsList = getList("tbl_vendors v", "v.id", "CONCAT(COALESCE((SELECT CONCAT(vendor, ' &raquo;&raquo; ') FROM tbl_vendors WHERE id=v.parent_id), ''), v.vendor) AS _Vendor", "v.mgf='N' AND v.levis='N'", "_Vendor");
		
		else
			$sVendorsList = getList("tbl_vendors v", "v.id", "CONCAT(COALESCE((SELECT CONCAT(vendor, ' &raquo;&raquo; ') FROM tbl_vendors WHERE id=v.parent_id), ''), v.vendor) AS _Vendor", "FIND_IN_SET(v.id, '{$_SESSION['Vendors']}') AND v.mgf='N' AND v.levis='N'", "_Vendor");

		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $sVendors)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>

					  <br />
					  <br style="line-height:3px;" />
					  [ <a href="./" onclick="selectAll('Vendors'); return false;">Select All</a> | <a href="./" onclick="clearAll('Vendors'); return false;">Clear</a> ]<br />
				    </td>
				  </tr>
				  

				  <tr valign="top">
				    <td>Style Categories</td>
				    <td align="center">:</td>

				    <td>
					  <select id="StyleCategories" name="StyleCategories[]" multiple size="10" style="min-width:200px;">
<?
	$sCategoriesList = getList("tbl_style_categories", "id", "category");

	foreach ($sCategoriesList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $sStyleCategories)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>

					  <br />
					  <br style="line-height:3px;" />
					  [ <a href="./" onclick="selectAll('StyleCategories'); return false;">Select All</a> | <a href="./" onclick="clearAll('StyleCategories'); return false;">Clear</a> ]<br />
				    </td>
				  </tr>

				  <tr valign="top">
				    <td>Report Types</td>
				    <td align="center">:</td>

				    <td>
					  <select id="ReportTypes" name="ReportTypes[]" multiple size="10" style="min-width:200px;">
<?
	if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
		$sReportsList = getList("tbl_reports", "id", "report");
	
	else
	{
		$sUserReports = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
		$sReportsList = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sUserReports')");
	}

	foreach ($sReportsList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $sReportTypes)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>

					  <br />
					  <br style="line-height:3px;" />
					  [ <a href="./" onclick="selectAll('ReportTypes'); return false;">Select All</a> | <a href="./" onclick="clearAll('ReportTypes'); return false;">Clear</a> ]<br />
				    </td>
				  </tr>

				  <tr valign="top">
				    <td>Audit Stages</td>
				    <td align="center">:</td>

				    <td>
					  <select id="AuditStages" name="AuditStages[]" multiple size="10" style="min-width:200px;">
<?
	if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
		$sStagesList = getList("tbl_audit_stages", "`code`", "stage");
	
	else
	{
		$sUserStages = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
		$sStagesList = getList("tbl_audit_stages", "`code`", "stage", "FIND_IN_SET(`code`, '$sUserStages')");
	}

	foreach ($sStagesList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $sAuditStages)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>

					  <br />
					  <br style="line-height:3px;" />
					  [ <a href="./" onclick="selectAll('AuditStages'); return false;">Select All</a> | <a href="./" onclick="clearAll('AuditStages'); return false;">Clear</a> ]<br />
				    </td>
				  </tr>
			    </table>

<?
	$sClass = array("evenRow", "oddRow");

	if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
		$sSQL = "SELECT id, module, section FROM tbl_pages ORDER BY module, section";
	
	else
	{
		$sAddRights    = getList("tbl_user_rights", "page_id", "`add`", "user_id='{$_SESSION['UserId']}'");
		$sEditRights   = getList("tbl_user_rights", "page_id", "`edit`", "user_id='{$_SESSION['UserId']}'");
		$sDeleteRights = getList("tbl_user_rights", "page_id", "`delete`", "user_id='{$_SESSION['UserId']}'");
		
		$sSQL = "SELECT id, module, section FROM tbl_pages WHERE id IN (SELECT DISTINCT(page_id) FROM tbl_user_rights WHERE user_id='{$_SESSION['UserId']}') ORDER BY module, section";
	}

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>
			    <input type="hidden" name="PageCount" value="<?= $iCount ?>" />

			    <div class="tblSheet" style="margin:20px;">
			      <table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
				    <tr class="headerRow">
				      <td width="25%">Website Module</td>
				      <td width="25%">Section / Page</td>
				      <td width="10%" class="center">VIEW</td>
				      <td width="10%" class="center">ADD</td>
				      <td width="10%" class="center">EDIT</td>
				      <td width="10%" class="center">DELETE</td>
				      <td width="10%" class="center">ALL</td>
				    </tr>
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId      = $objDb->getField($i, "id");
		$sModule  = $objDb->getField($i, "module");
		$sSection = $objDb->getField($i, "section");

		$sSQL = "SELECT `view`, `add`, `edit`, `delete` FROM tbl_user_rights WHERE user_id='$Id' AND page_id='$iId'";
		$objDb2->query($sSQL);

		$sView   = $objDb2->getField(0, 'view');
		$sAdd    = $objDb2->getField(0, 'add');
		$sEdit   = $objDb2->getField(0, 'edit');
		$sDelete = $objDb2->getField(0, 'delete');
		
		
		$sDisableAdd    = "";
		$sDisableEdit   = "";
		$sDisableDelete = "";
		
		if (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@3-tree.com") === FALSE)
		{
			if ($sAddRights[$iId] != "Y")
				$sDisableAdd = " disabled";
			
			if ($sEditRights[$iId] != "Y")
				$sDisableEdit = " disabled";
			
			if ($sDeleteRights[$iId] != "Y")
				$sDisableDelete = " disabled";
		}
?>

				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><input type="hidden" name="Page<?= $i ?>" value="<?= $iId ?>" /><?= $sModule ?></td>
				      <td><?= $sSection ?></td>
				      <td class="center"><input type="checkbox" name="View<?= $i ?>" id="View<?= $i ?>" value="Y" <?= (($sView == "Y") ? "checked" : "") ?> onclick="resetPageRights(<?= $i ?>);" /></td>
				      <td class="center"><input type="checkbox" name="Add<?= $i ?>" id="Add<?= $i ?>" value="Y" <?= (($sAdd == "Y") ? "checked" : "") ?> onclick="resetPageRights(<?= $i ?>);" <?= $sDisableAdd ?> /></td>
				      <td class="center"><input type="checkbox" name="Edit<?= $i ?>" id="Edit<?= $i ?>" value="Y" <?= (($sEdit == "Y") ? "checked" : "") ?> onclick="resetPageRights(<?= $i ?>);" <?= $sDisableEdit ?> /></td>
				      <td class="center"><input type="checkbox" name="Delete<?= $i ?>" id="Delete<?= $i ?>" value="Y" <?= (($sDelete == "Y") ? "checked" : "") ?> onclick="resetPageRights(<?= $i ?>);" <?= $sDisableDelete ?> /></td>
				      <td class="center"><input type="checkbox" name="All<?= $i ?>" id="All<?= $i ?>" value="Y" <?= (($sView == "Y" && $sAdd == "Y" && $sEdit == "Y" && $sDelete == "Y") ? "checked" : "") ?> onclick="checkAllPageRights(<?= $i ?>);" /></td>
				    </tr>
<?
	}
?>
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