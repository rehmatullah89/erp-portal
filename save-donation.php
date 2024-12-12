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

	$Amount = IO::floatValue("Amount");

	$iId = getNextId("tbl_donation");

	$sSQL = "INSERT INTO tbl_donation (id, user_id, amount, date_time) VALUES ('$iId', '{$_SESSION['UserId']}', '$Amount', NOW( ))";

	if ($objDb->execute($sSQL) == true)
	{
		$sBody = @file_get_contents("emails/donation.txt");

		$sBody = @str_replace("[Name]", $_SESSION['Name'], $sBody);
		$sBody = @str_replace("[Amount]", formatNumber($Amount, false), $sBody);
		$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
		$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


		$objEmail = new PHPMailer( );

//		$objEmail->From     = SENDER_EMAIL;
//		$objEmail->FromName = SENDER_NAME;
		$objEmail->Subject  = "MATRIX Sourcing Flood Relief Fund";

		$objEmail->MsgHTML($sBody);
		$objEmail->AddAddress($_SESSION['Email'], $_SESSION['Name']);
		$objEmail->Send( );

		redirect("./", "DONATION_SAVED");
	}

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		backToForm( );
	}

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>