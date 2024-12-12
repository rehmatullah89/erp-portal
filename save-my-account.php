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
	@require_once("requires/image-functions.php");

    checkLogin( );

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Gender            = IO::strValue("Gender");
	$Month             = IO::intValue("Month");
	$Day               = IO::intValue("Day");
	$Year              = IO::intValue("Year");
	$Address           = IO::strValue("Address");
	$City              = IO::strValue("City");
	$State             = IO::strValue("State");
	$ZipCode           = IO::strValue("ZipCode");
	$Country           = IO::strValue("Country");
	$Phone             = IO::strValue("Phone");
	$Mobile            = IO::strValue("Mobile");
	$Password          = IO::strValue("Password");
	$MaritalStatus     = IO::strValue("MaritalStatus");
	$SpouseName        = IO::strValue("SpouseName");
	$Children          = IO::intValue("Children");
	$BloodGroup        = IO::strValue("BloodGroup");
	$EmergencyName     = IO::strValue("EmergencyName");
	$EmergencyPhone    = IO::strValue("EmergencyPhone");
	$EmergencyAddress  = IO::strValue("EmergencyAddress");
	$PersonalGoals     = IO::strValue("PersonalGoals");
	$TrainingsRequired = IO::strValue("TrainingsRequired");
	$OldPicture        = IO::strValue("OldPicture");
	$OldSignature      = IO::strValue("OldSignature");
	$sPicture          = "";
	$sSignature        = "";
	$sPictureSql       = "";
	$sSignatureSql     = "";
	$sPasswordSql      = "";

	$sError = "";

	if ($Gender == "")
		$sError .= "- Gender<br />";

	if ($Month == "")
		$sError .= "- Month<br />";

	if ($Day == "")
		$sError .= "- Day<br />";

	if ($Year == "")
		$sError .= "- Year<br />";

	if ($City == "")
		$sError .= "- City<br />";

	if ($Country == 0)
		$sError .= "- Country<br />";

	else
	{
		$sSQL = "SELECT country FROM tbl_countries WHERE id='$Country'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
				$sError .= "- Invalid Country<br />";
	}

//	if ($Mobile == "")
//		$sError .= "- Mobile<br />";

	if ($sError != "")
		backToForm($sError);

	if ($Password != "")
		$sPasswordSql = ", password=PASSWORD('$Password') ";

	if ($_FILES['Picture']['name'] != "")
	{
		$sPicture = ($_SESSION['UserId']."-".IO::getFileName($_FILES['Picture']['name']));

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


	
	$sOldPassword = getDbValue("password", "tbl_users", "id='{$_SESSION['UserId']}'");
	
	
	$sSQL = "UPDATE tbl_users SET gender='$Gender', dob='$Year-$Month-$Day', address='$Address', city='$City', state='$State', country_id='$Country', phone='$Phone', mobile='$Mobile', marital_status='$MaritalStatus', spouse_name='$SpouseName', children='$Children', blood_group='$BloodGroup', emergency_name='$EmergencyName', emergency_phone='$EmergencyPhone', emergency_address='$EmergencyAddress', personal_goals='$PersonalGoals', trainings_required='$TrainingsRequired' $sPictureSql $sSignatureSql $sPasswordSql WHERE id='{$_SESSION['UserId']}'";

	if ($objDb->execute($sSQL) == true)
	{
		$sNewPassword = getDbValue("password", "tbl_users", "id='{$_SESSION['UserId']}'");
		
		
		if ($Password != "" && $sOldPassword != $sNewPassword)
		{
			$sSQL = "SELECT name, email, username FROM tbl_users WHERE id='{$_SESSION['UserId']}'";
			$objDb->query($sSQL);
			
			$sName     = $objDb->getField(0, "name");
			$sEmail    = $objDb->getField(0, "email");
			$sUsername = $objDb->getField(0, "username");
			
			
			$sBody = @file_get_contents("emails/password-change.txt");

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
                        
                        @require_once("update-support-user.php");
		}
		
		
		
		if ($OldPicture != $sPicture && $OldPicture != "" && $sPicture != "")
		{
			@unlink($sBaseDir.USERS_IMG_PATH.'originals/'.$OldPicture);
			@unlink($sBaseDir.USERS_IMG_PATH.'thumbs/'.$OldPicture);
		}
		

		$_SESSION['Flag'] = "ACCOUNT_UPDATED";

		header("Location: ./");
	}

	else
	{
		if ($OldPicture != $sPicture && $sPicture != "")
		{
			@unlink($sBaseDir.USERS_IMG_PATH.'originals/'.$sPicture);
			@unlink($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture);
		}

		$_SESSION['Flag'] = "DB_ERROR";

		backToForm( );
	}

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>