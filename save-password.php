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

	$UserId    = IO::intValue('UserId');
	$Email     = IO::strValue('Email');
	$Code      = IO::strValue('Code');
	$sPassword = IO::strValue('Password');

	if (md5(IO::strValue('SpamCode')) != $_SESSION['SpamCode'])
	{
		$_SESSION['Flag'] = "INVALID_SPAM_CODE";

		backToForm( );
	}

	$sSQL = "UPDATE tbl_users SET password=PASSWORD('$sPassword') WHERE id='$UserId' AND email='$Email' AND RIGHT(password, 16)='$Code'";

	if ($objDb->execute($sSQL) == true)
	{
            @require_once("update-support-user.php");
            
            $_SESSION['Flag'] = "PASSWORD_CHANGED";

            header("Location: ./");
	}

	else
	{
		$_SESSION['Flag'] = "PASSWORD_CHANGE_ERROR";

		backToForm( );
	}
        

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>