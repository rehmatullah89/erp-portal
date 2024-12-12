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

	$sName                 = $objDb->getField(0, "name");
	$sGender               = $objDb->getField(0, "gender");
	$sDob                  = $objDb->getField(0, "dob");
	$sAddress              = $objDb->getField(0, "address");
	$sCity                 = $objDb->getField(0, "city");
	$sState                = $objDb->getField(0, "state");
	$sZipCode              = $objDb->getField(0, "zip_code");
	$iCountryId            = $objDb->getField(0, "country_id");
	$sEmail                = $objDb->getField(0, "email");
	$sPhone                = $objDb->getField(0, "phone");
	$sMobile               = $objDb->getField(0, "mobile");
	$sOrganization         = $objDb->getField(0, "organization");
	$sJoiningDate          = $objDb->getField(0, "joining_date");
	$sCardId               = $objDb->getField(0, "card_id");
	$iOfficeId             = $objDb->getField(0, "office_id");
	$sPhoneExt             = $objDb->getField(0, "phone_ext");
	$iDesignationId        = $objDb->getField(0, "designation_id");
	$sAuditor              = $objDb->getField(0, "auditor");
	$sRoutineActivities    = $objDb->getField(0, 'routine_activities');
	$sNonRoutineActivities = $objDb->getField(0, 'non_routine_activities');
	$sNicNo                = $objDb->getField(0, "nic_no");
	$sMaritalStatus        = $objDb->getField(0, "marital_status");
	$sSpouseName           = $objDb->getField(0, "spouse_name");
	$iChildren             = $objDb->getField(0, "children");
	$sBloodGroup           = $objDb->getField(0, "blood_group");
	$sEmergencyName        = $objDb->getField(0, "emergency_name");
	$sEmergencyPhone       = $objDb->getField(0, "emergency_phone");
	$sEmergencyAddress     = $objDb->getField(0, "emergency_address");
	$sPersonalGoals        = $objDb->getField(0, "personal_goals");
	$sTrainingsRequired    = $objDb->getField(0, "trainings_required");
	$sPicture              = $objDb->getField(0, "picture");

	$sJoiningDate = (($sJoiningDate == "0000-00-00") ? "" : $sJoiningDate);
	$sDob         = (($sDob == "0000-00-00") ? "" : $sDob);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/jquery.js"></script>
  <script type="text/javascript" src="scripts/jquery.autocomplete.js"></script>
  <script type="text/javascript" src="scripts/hr/edit-employee.js"></script>
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
			    <h1><img src="images/h1/admin/user-account.jpg" width="195" height="20" vspace="10" alt="" title="" /></h1>

			    <form name="frmData" id="frmData" method="post" action="hr/save-employee.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disable( );">
			    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $sReferer ?>" />
			    <input type="hidden" name="OldPicture" value="<?= $sPicture ?>" />

			    <div style="padding:10px 10px 25px 10px;">
			      Please change the necessary information below and click the "Save" button in order to update the selected User Account.<br />
			    </div>

			    <h2>Personal Information</h2>
			    <table width="95%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="120">Full Name<span class="mandatory">*</span></td>
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
				</table>

				<br />
			    <h2>Contact Information</h2>

			    <table width="95%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="120">Email Address<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="Email" value="<?= $sEmail ?>" size="30" maxlength="100" class="textbox" /></td>
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
			    <h2>Company Information</h2>

			    <table width="95%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="120">Organization</td>
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

				  <tr>
				    <td>QA Auditor</td>
				    <td align="center">:</td>

				    <td>
					  <select name="Auditor">
						<option value="N"<?= (($sAuditor == "N") ? " selected" : "") ?>>No</option>
						<option value="Y"<?= (($sAuditor == "Y") ? " selected" : "") ?>>Yes</option>
					  </select>
				    </td>
				  </tr>

				  <tr valign="top">
				    <td>Routine Activities</td>
				    <td align="center">:</td>
				    <td><textarea name="RoutineActivities" id="RoutineActivities" rows="7" style="width:98%;"><?= @utf8_decode($sRoutineActivities) ?></textarea></td>
				  </tr>

				  <tr valign="top">
				    <td>Non-Routine Activities</td>
				    <td align="center">:</td>
				    <td><textarea name="NonRoutineActivities" id="NonRoutineActivities" rows="7" style="width:98%;"><?= @utf8_decode($sNonRoutineActivities) ?></textarea></td>
				  </tr>
				</table>

				<br />
			    <h2>Personal Profile (for Company)</h2>

			    <table width="95%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="120">NIC No</td>
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