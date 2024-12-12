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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );


	$sExcelFile = ($sBaseDir."temp/etd-revision-reasons.xlsx");

	@include($sBaseDir."includes/data/etd-revision-reasons.php");


	$User = IO::intValue("User");


	$sSQL = "SELECT name, email FROM tbl_users WHERE id='$User'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sName  = $objDb->getField(0, "name");
		$sEmail = $objDb->getField(0, "email");

		$sSubject = "";

		$sBody = @file_get_contents("../emails/etd-revision-reasons.txt");
		$sBody = @str_replace("[Name]", $sName, $sBody);
		$sBody = @str_replace("[Email]", $sEmail, $sBody);
		$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
		$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);
		$sBody = @str_replace("[SenderName]", $_SESSION['Name'], $sBody);
		$sBody = @str_replace("[SenderEmail]", $_SESSION['Email'], $sBody);


		$objEmail = new PHPMailer( );

//		$objEmail->From     = $_SESSION['Email'];
//		$objEmail->FromName = $_SESSION['Name'];
		$objEmail->Subject  = "ETD Revision Reasons List";

		$objEmail->MsgHTML($sBody);
		$objEmail->AddAddress($sEmail, $sName);
		$objEmail->AddAttachment($sExcelFile, 'etd-revision-reasons.xlsx');
		$objEmail->Send( );

		redirect($_SERVER['HTTP_REFERER'], "ETD_REVISION_FILE_SENT");
	}

	else
		redirect($_SERVER['HTTP_REFERER'], "DB_ERROR");

	@unlink($sExcelFile);


	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>