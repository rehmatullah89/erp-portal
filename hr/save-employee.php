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
	@require_once("../requires/image-functions.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id                   = IO::intValue("Id");
	$Referer              = IO::strValue("Referer");
	$Name                 = IO::strValue("Name");
	$Gender               = IO::strValue("Gender");
	$Dob                  = IO::strValue("Dob");
	$Address              = IO::strValue("Address");
	$City                 = IO::strValue("City");
	$State                = IO::strValue("State");
	$ZipCode              = IO::strValue("ZipCode");
	$Country              = IO::strValue("Country");
	$Email                = IO::strValue("Email");
	$Phone                = IO::strValue("Phone");
	$Mobile               = IO::strValue("Mobile");
	$Organization         = IO::strValue("Organization");
	$JoiningDate          = IO::strValue("JoiningDate");
	$CardId               = IO::strValue("CardId");
	$Office               = IO::intValue("Office");
	$PhoneExt             = IO::strValue("PhoneExt");
	$Designation          = IO::intValue("Designation");
	$Auditor              = IO::strValue("Auditor");
	$RoutineActivities    = @utf8_encode(IO::strValue("RoutineActivities"));
	$NonRoutineActivities = @utf8_encode(IO::strValue("NonRoutineActivities"));
	$MaritalStatus        = IO::strValue("MaritalStatus");
	$NicNo                = IO::strValue("NicNo");
	$SpouseName           = IO::strValue("SpouseName");
	$Children             = IO::intValue("Children");
	$BloodGroup           = IO::strValue("BloodGroup");
	$EmergencyName        = IO::strValue("EmergencyName");
	$EmergencyPhone       = IO::strValue("EmergencyPhone");
	$EmergencyAddress     = IO::strValue("EmergencyAddress");
	$PersonalGoals        = IO::strValue("PersonalGoals");
	$TrainingsRequired    = IO::strValue("TrainingsRequired");
	$OldPicture           = IO::strValue("OldPicture");
	$sPicture             = "";
	$sPictureSql          = "";
	$sPasswordSql         = "";


	if ($Referer == "")
		$Referer = "employees.php";

	$Dob         = (($Dob == "") ? "0000-00-00" : $Dob);
	$JoiningDate = (($JoiningDate == "") ? "0000-00-00" : $JoiningDate);


	if ($_FILES['Picture']['name'] != "")
	{
		$sPicture = ($Id."-".IO::getFileName($_FILES['Picture']['name']));

		if (@move_uploaded_file($_FILES['Picture']['tmp_name'], ($sBaseDir.USERS_IMG_PATH.'originals/'.$sPicture)))
		{
			@createFixedSizeImage(($sBaseDir.USERS_IMG_PATH.'originals/'.$sPicture), ($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture), 156, 116);

			$sPictureSql = ", picture='$sPicture' ";
		}
	}


	$sSQL = "SELECT * FROM tbl_users WHERE email='$Email' AND id!='$Id'";

	if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
		redirect("edit-employee.php?Id={$Id}&Referer={$Referer}", "EMAIL_EXISTS");


	$objDb->execute("BEGIN");

	$sSQL = "UPDATE tbl_users SET name='$Name', gender='$Gender', dob='$Dob', address='$Address', city='$City', state='$State', country_id='$Country', email='$Email', phone='$Phone', mobile='$Mobile', organization='$Organization', joining_date='$JoiningDate', card_id='$CardId', office_id='$Office', phone_ext='$PhoneExt', designation_id='$Designation', auditor='$Auditor', routine_activities='$RoutineActivities', non_routine_activities='$NonRoutineActivities', nic_no='$NicNo', marital_status='$MaritalStatus', spouse_name='$SpouseName', children='$Children', blood_group='$BloodGroup', emergency_name='$EmergencyName', emergency_phone='$EmergencyPhone', emergency_address='$EmergencyAddress', personal_goals='$PersonalGoals', trainings_required='$TrainingsRequired' $sPictureSql WHERE id='$Id'";
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL = "SELECT COUNT(*) FROM tbl_user_evolutionary_profile WHERE user_id='$Id'";

		if ($objDb->query($sSQL) == true && $objDb->getField(0, 0) == 0)
		{
			$sSQL  = "INSERT INTO tbl_user_evolutionary_profile (user_id, date_time) VALUES ('$Id', NOW( ))";
			$bFlag = $objDb->execute($sSQL);
		}
	}

	if ($bFlag == true)
	{
		$sSQL = "SELECT COUNT(*) FROM tbl_user_responsibilities_score WHERE user_id='$Id'";

		if ($objDb->query($sSQL) == true && $objDb->getField(0, 0) == 0)
		{
			$iDepartment = getDbValue("department_id", "tbl_designations", "id='$Designation'");

			if (getDbValue("COUNT(1)", "tbl_user_responsibilities", "department_id='$iDepartment'") > 0)
				$sSQL = "INSERT INTO tbl_user_responsibilities_score (responsibility_id, user_id, comments, score) (SELECT id, '$Id', '', '0' FROM tbl_user_responsibilities WHERE department_id='$iDepartment')";

			else
				$sSQL = "INSERT INTO tbl_user_responsibilities_score (responsibility_id, user_id, comments, score) (SELECT id, '$Id', '', '0' FROM tbl_user_responsibilities WHERE department_id='0')";

			$bFlag = $objDb->execute($sSQL);
		}
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		if ($OldPicture != $sPicture && $OldPicture != "" && $sPicture != "")
		{
			@unlink($sBaseDir.USERS_IMG_PATH.'originals/'.$OldPicture);
			@unlink($sBaseDir.USERS_IMG_PATH.'thumbs/'.$OldPicture);
		}

		$_SESSION['Flag'] = "USER_ACCOUNT_UPDATED";

		header("Location: {$Referer}");
	}

	else
	{
		$objDb->execute("ROLLBACK");

		if ($OldPicture != $sPicture && $sPicture != "")
		{
			@unlink($sBaseDir.USERS_IMG_PATH.'originals/'.$sPicture);
			@unlink($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture);
		}

		$_SESSION['Flag'] = "DB_ERROR";

		header("Location: edit-employee.php?Id={$Id}&Referer=".urlencode($Referer));
	}

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>