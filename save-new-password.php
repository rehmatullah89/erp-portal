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

        $UserId         = IO::intValue('UserId');
        $sPassword      = IO::strValue('Password');
      	$sOldPassword   = IO::strValue('OldPassword');
        $Email          = IO::strValue('UserEmail');
        $sUserName      = IO::strValue('UserName');
       
        $iUsers  = getDbValue("COUNT(1)", "tbl_users", "id='$UserId' AND password=PASSWORD('$sOldPassword')");
        
        if($sPassword == $sOldPassword)
        {
            $_SESSION['Flag'] = "OLD_PASSWORD_CHANGE_ERROR";
    
            header("Location: new-password.php?User={$sUserName}");
        }
        else if($iUsers == 1)
        {
            $sSQL = "UPDATE tbl_users SET password=PASSWORD('$sPassword'), password_changed=NOW( )  WHERE id='$UserId' AND email='$Email'";

            if ($objDb->execute($sSQL) == true)
            {
                @require_once("update-support-user.php");

                $_SESSION['Flag'] = "PASSWORD_CHANGED";

                header("Location: ./");
            }			
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