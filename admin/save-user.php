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
	$objDb2      = new Database( );

	$Id                      = IO::intValue("Id");
	$Referer                 = IO::strValue("Referer");
	$Name                    = IO::strValue("Name");
	$Gender                  = IO::strValue("Gender");
	$Dob                     = IO::strValue("Dob");
	$Address                 = IO::strValue("Address");
	$City                    = IO::strValue("City");
	$State                   = IO::strValue("State");
	$ZipCode                 = IO::strValue("ZipCode");
	$Country                 = IO::intValue("Country");
	$Email                   = IO::strValue("Email");
	$EmailAlerts             = IO::strValue("EmailAlerts");
	$Phone                   = IO::strValue("Phone");
	$Mobile                  = IO::strValue("Mobile");
	$Organization            = IO::strValue("Organization");
	$JoiningDate             = IO::strValue("JoiningDate");
	$CardId                  = IO::strValue("CardId");
	$Office                  = IO::intValue("Office");
	$PhoneExt                = IO::strValue("PhoneExt");
	$Designation             = IO::intValue("Designation");
	$MaritalStatus           = IO::strValue("MaritalStatus");
	$NicNo                   = IO::strValue("NicNo");
	$SpouseName              = IO::strValue("SpouseName");
	$Children                = IO::intValue("Children");
	$BloodGroup              = IO::strValue("BloodGroup");
	$EmergencyName           = IO::strValue("EmergencyName");
	$EmergencyPhone          = IO::strValue("EmergencyPhone");
	$EmergencyAddress        = IO::strValue("EmergencyAddress");
	$PersonalGoals           = IO::strValue("PersonalGoals");
	$TrainingsRequired       = IO::strValue("TrainingsRequired");
	$Username                = IO::strValue("Username");
	$Password                = IO::strValue("Password");
	$Status                  = IO::strValue("Status");
	$Language                = IO::strValue("Language");
	$Admin                   = IO::strValue("Admin");
	$Guest                   = IO::strValue("Guest");
	$Reset                   = IO::strValue("Reset");
	$SurveyAdmin             = IO::strValue("SurveyAdmin");
	$Attendance              = IO::strValue("Attendance");
	$EmailCollection         = IO::strValue("EmailCollection");
	$GrievancesManager       = IO::strValue("GrievancesManager");
	$AuditsManager           = IO::strValue("AuditsManager");
	$SkipImage               = IO::strValue("SkipImage");
	$ShowUserSchedules       = IO::strValue("ShowUserSchedules");
	$Auditor                 = IO::strValue("Auditor");
        $EtdManager              = IO::strValue("EtdManager");
	$AuditorLevel            = IO::strValue("AuditorLevel");
	$AuditorType             = IO::intValue("AuditorType");
	$AuditorCode             = IO::intValue("AuditorCode");
	$sNonProductionSchedules = IO::strValue("NonProductionSchedules");
	$UserType                = IO::strValue("UserType");
        $UserTypes               = implode(",", IO::getArray("UserTypes"));
        $AppSections             = implode(",", IO::getArray("AppSections"));
	$Brands                  = @implode(",", $_POST['Brands']);
	$Vendors                 = @implode(",", $_POST['Vendors']);
	$Suppliers               = @implode(",", $_POST['Suppliers']);
	$StyleCategories         = @implode(",", $_POST['StyleCategories']);
	$ReportTypes             = @implode(",", $_POST['ReportTypes']);
	$AuditStages             = @implode(",", $_POST['AuditStages']);
	$OldPicture              = IO::strValue("OldPicture");
	$OldSignature            = IO::strValue("OldSignature");
	$sPicture                = "";
	$sSignature              = "";
	$sPictureSql             = "";
	$sSignatureSql           = "";
	$sPasswordSql            = "";



	if ($Referer == "")
		$Referer = "users.php";

	
	$OldDesignation = getDbValue("designation_id", "tbl_users", "id='$Id'");
	$Dob            = (($Dob == "") ? "0000-00-00" : $Dob);
	$JoiningDate    = (($JoiningDate == "") ? "0000-00-00" : $JoiningDate);
	$Username       = str_replace(" ", ".", $Username);
	$sOldPassword   = getDbValue("password", "tbl_users", "id='$Id'");

	if ($Password != "" && $sOldPassword != getDbValue("PASSWORD('$Password')", "tbl_users", "id='$Id'"))
		$sPasswordSql = ", password=PASSWORD('$Password'), password_changed=NOW( ) ";

	if ($Auditor == "N")
		$AuditorType = 0;
	
	if ($Guest == "Y" && $Reset == "Y")
	{
		$Brands  = "130";
		$Vendors = "147";


		$sSQL = "SELECT * FROM tbl_vendors WHERE pcc='Y'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$Vendors .= (",".$objDb->getField($i, 0));
	}


	if ($_FILES['Picture']['name'] != "")
	{
		$sPicture = ($Id."-".IO::getFileName($_FILES['Picture']['name']));

		if (@move_uploaded_file($_FILES['Picture']['tmp_name'], ($sBaseDir.USERS_IMG_PATH.'originals/'.$sPicture)))
		{
			@createFixedSizeImage(($sBaseDir.USERS_IMG_PATH.'originals/'.$sPicture), ($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture), 156, 116);

			$sPictureSql = ", picture='$sPicture' ";
		}
	}

	if ($_FILES['Signature']['name'] != "")
	{
		$sSignature = ($Id."-".IO::getFileName($_FILES['Signature']['name']));

		if (@move_uploaded_file($_FILES['Signature']['tmp_name'], ($sBaseDir.USER_SIGNATURES_IMG_DIR.$sSignature)))
			$sSignatureSql = ", signature='$sSignature' ";
	}


	$sSQL = "SELECT * FROM tbl_users WHERE username='$Username' AND id!='$Id'";

	if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
		redirect("edit-user.php?Id={$Id}&Referer={$Referer}", "USERNAME_EXISTS");

	$sSQL = "SELECT * FROM tbl_users WHERE email='$Email' AND id!='$Id'";

	if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
		redirect("edit-user.php?Id={$Id}&Referer={$Referer}", "EMAIL_EXISTS");

	

	$objDb->execute("BEGIN");

	if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
	{
		$sSQL = "UPDATE tbl_users SET name='$Name', gender='$Gender', dob='$Dob', address='$Address', city='$City', state='$State', country_id='$Country', email='$Email', phone='$Phone', mobile='$Mobile', email_alerts='$EmailAlerts',
									  organization='$Organization', joining_date='$JoiningDate', card_id='$CardId', office_id='$Office', phone_ext='$PhoneExt', designation_id='$Designation', nic_no='$NicNo',
									  marital_status='$MaritalStatus', spouse_name='$SpouseName', children='$Children', blood_group='$BloodGroup', emergency_name='$EmergencyName', emergency_phone='$EmergencyPhone',
									  emergency_address='$EmergencyAddress', personal_goals='$PersonalGoals', trainings_required='$TrainingsRequired', username='$Username', status='$Status', language='$Language', admin='$Admin', guest='$Guest',
									  survey_admin='$SurveyAdmin', attendance='$Attendance', email_collection='$EmailCollection', grievances_manager='$GrievancesManager', etd_manager='$EtdManager', audits_manager='$AuditsManager',
									  show_user_schedules='$ShowUserSchedules', app_skip_image='$SkipImage', auditor='$Auditor', auditor_level='$AuditorLevel', auditor_type='$AuditorType', auditor_code='$AuditorCode', non_production_schedules='$sNonProductionSchedules', brands='$Brands', vendors='$Vendors', suppliers='$Suppliers',
									  style_categories='$StyleCategories', report_types='$ReportTypes', audit_stages='$AuditStages', user_type='$UserType', auditor_types='$UserTypes', app_sections='$AppSections', modified_at=NOW( ), modified_by='{$_SESSION['UserId']}' $sPictureSql $sSignatureSql $sPasswordSql WHERE id='$Id'";
	}
	
	else
	{
		$sSQL = "UPDATE tbl_users SET name='$Name', gender='$Gender', dob='$Dob', address='$Address', city='$City', state='$State', country_id='$Country', email='$Email', phone='$Phone', mobile='$Mobile', email_alerts='$EmailAlerts',
									  username='$Username', status='$Status', language='$Language', audits_manager='$AuditsManager',
									  auditor='$Auditor', auditor_level='$AuditorLevel', auditor_type='$AuditorType', auditor_code='$AuditorCode', brands='$Brands', vendors='$Vendors', suppliers='$Suppliers',
									  style_categories='$StyleCategories', report_types='$ReportTypes', audit_stages='$AuditStages', app_sections='$AppSections',  modified_at=NOW( ), modified_by='{$_SESSION['UserId']}' $sPictureSql $sSignatureSql $sPasswordSql WHERE id='$Id'";
	}
	
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_user_rights WHERE user_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		if ($Guest == "Y" && $Reset == "Y")
		{
			$sSQL = "SELECT id, module, section FROM tbl_pages WHERE FIND_IN_SET(id, '9,10,13,17,18,20,25,26,32,33,34,35,36,37,38,39,40,41,42,63,70,72,73,75,78,83,87,88,89,91,92,93,94,109,110,111,112,113,114,120') ORDER BY module, section";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iId      = $objDb->getField($i, "id");
				$sModule  = $objDb->getField($i, "module");
				$sSection = $objDb->getField($i, "section");


				$sSQL  = "INSERT INTO tbl_user_rights (user_id, page_id, `view`, `add`, `edit`, `delete`) VALUES ('$Id', '$iId', 'Y', 'N', 'N', 'N')";
				$bFlag = $objDb2->execute($sSQL);

				if ($bFlag == false)
					break;
			}
		}

		else
		{
			$iPageCount = IO::intValue("PageCount");

			for ($j = 0; $j < $iPageCount; $j ++)
			{
				$PageId = IO::intValue("Page".$j);
				$View   = IO::strValue("View".$j);
				$Add    = IO::strValue("Add".$j);
				$Edit   = IO::strValue("Edit".$j);
				$Delete = IO::strValue("Delete".$j);

				if ($View != "" || $Add != "" || $Edit != "" || $Delete != "")
				{
					$sSQL = "INSERT INTO tbl_user_rights (user_id, page_id, `view`, `add`, `edit`, `delete`) VALUES ('$Id', '$PageId', '$View', '$Add', '$Edit', '$Delete')";
					$bFlag = $objDb->execute($sSQL);

					if ($bFlag == false)
						break;
				}
			}
		}
	}

	
	if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR")))
	{
		if ($bFlag == true)
		{
			$sSQL = "SELECT COUNT(*) FROM tbl_user_evolutionary_profile WHERE user_id='$Id'";

			if ($objDb->query($sSQL) == true && $objDb->getField(0, 0) == 0)
			{
				$sSQL  = "INSERT INTO tbl_user_evolutionary_profile (user_id, date_time) VALUES ('$Id', NOW( ))";
				$bFlag = $objDb->execute($sSQL);
			}
		}

		if ($bFlag == true && $OldDesignation != $Designation)
		{
			$sSQL = "DELETE FROM tbl_user_responsibilities_score WHERE user_id='$Id'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true && $Designation > 0)
			{
				$iDepartment = getDbValue("department_id", "tbl_designations", "id='$Designation'");

				if (getDbValue("COUNT(1)", "tbl_user_responsibilities", "department_id='$iDepartment'") > 0)
					$sSQL = "INSERT INTO tbl_user_responsibilities_score (responsibility_id, user_id, comments, score) (SELECT id, '$Id', '', '0' FROM tbl_user_responsibilities WHERE department_id='$iDepartment')";

				else
					$sSQL = "INSERT INTO tbl_user_responsibilities_score (responsibility_id, user_id, comments, score) (SELECT id, '$Id', '', '0' FROM tbl_user_responsibilities WHERE department_id='0')";

				$bFlag = $objDb->execute($sSQL);
			}
		}
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");
		
		
		$sNewPassword = getDbValue("password", "tbl_users", "id='$Id'");
		
		
		if ($Password != "" && $sOldPassword != $sNewPassword)
		{
			$sSQL = "SELECT name, email, username FROM tbl_users WHERE id='$Id'";
			$objDb->query($sSQL);
			
			$sName     = $objDb->getField(0, "name");
			$sEmail    = $objDb->getField(0, "email");
			$sUsername = $objDb->getField(0, "username");
			
			
			$sBody = @file_get_contents("../emails/password-change.txt");

			$sBody = @str_replace("[Name]", $sName, $sBody);
			$sBody = @str_replace("[Email]", $sEmail, $sBody);
			$sBody = @str_replace("[Username]", $sUsername, $sBody);
			$sBody = @str_replace("[Password]", $Password, $sBody);

			$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
			$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

			$objEmail->Subject  = "Triple Tree Customer Portal - Password Change Alert";

			$objEmail->MsgHTML($sBody);
			$objEmail->AddAddress("portal@3-tree.com", "Portal");
			$objEmail->AddAddress($sEmail, $sName);
			$objEmail->Send( );
                        
                        @require_once("../update-support-user.php");
		}
		

		if ($OldPicture != $sPicture && $OldPicture != "" && $sPicture != "")
		{
			@unlink($sBaseDir.USERS_IMG_PATH.'originals/'.$OldPicture);
			@unlink($sBaseDir.USERS_IMG_PATH.'thumbs/'.$OldPicture);
		}

		if ($OldSignature != $sSignature && $OldSignature != "" && $sSignature != "")
			@unlink($sBaseDir.USER_SIGNATURES_IMG_DIR.$OldSignature);

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

		if ($OldSignature != $sSignature && $sSignature != "")
			@unlink($sBaseDir.USER_SIGNATURES_IMG_DIR.$sSignature);

		$_SESSION['Flag'] = "DB_ERROR";

		header("Location: edit-user.php?Id={$Id}&Referer=".urlencode($Referer));
	}


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>