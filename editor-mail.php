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

	$Subject = IO::strValue("Subject");
	$Message = IO::strValue("Message");

	$sError = "";

	if ($Subject == "")
		$sError .= "- Subject<br />";

	if ($Message == "")
		$sError .= "- Message<br />";



	$sBody = @file_get_contents("emails/editor.txt");

	$sBody = @str_replace("[Name]", $_SESSION["Name"], $sBody);
	$sBody = @str_replace("[Email]", $_SESSION["Email"], $sBody);
	$sBody = @str_replace("[Subject]", $Subject, $sBody);
	$sBody = @str_replace("[Message]", nl2br($Message), $sBody);
	$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
	$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


	$objEmail = new PHPMailer( );

//	$objEmail->From     = "portal@apparelco.com";
//	$objEmail->FromName = "Triple Tree Customer Portal";
	$objEmail->Subject  = $Subject;

	$objEmail->MsgHTML($sBody);
	$objEmail->AddAddress("editor@apparelco.com", "Editor");
	$objEmail->Send( );
?>