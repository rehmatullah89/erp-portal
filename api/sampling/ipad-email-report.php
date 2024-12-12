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


	$Id         = IO::intValue('Id');
	$Recipients = IO::getArray("Recipients");
	$Others     = IO::strValue('Others');

	if ($Others != "")
		$Others = @explode(",", IO::strValue('Others'));

	else
		$Others = array( );


	@include($sBaseDir."sampling/export-sampling-report.php");


	$sBody = @file_get_contents("{$sBaseDir}emails/sampling-report.txt");
	$sBody = @str_replace("[Brand]", $sBrand, $sBody);
	$sBody = @str_replace("[Style]", $sStyle, $sBody);
	$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
	$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


	$objEmail = new PHPMailer( );

//	$objEmail->FromName = "Matrix Customer Portal";
	$objEmail->Subject  = "Sampling Report: {$sStyle} / {$sSeason} / {$sBrand}";

	$objEmail->MsgHTML($sBody);


	$sSQL = ("SELECT name, email FROM tbl_users WHERE FIND_IN_SET(id,'".@implode(",", $Recipients)."') ORDER BY name");
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sName  = $objDb->getField($i, "name");
		$sEmail = $objDb->getField($i, "email");

		$objEmail->AddAddress($sEmail, $sName);
	}

	for ($i = 0; $i < count($Others); $i ++)
	{
		$sEmail = trim($Others[$i]);

		if ($sEmail != "")
			$objEmail->AddAddress($sEmail, $sEmail);
	}


	$objEmail->AddAttachment($sPdfFile, @basename($sPdfFile));
	$objEmail->Send( );

	@unlink($sPdfFile);



	$aResponse = array( );

	$aResponse['Status']  = "OK";
	$aResponse['Message'] = "Report Sent";

	print @json_encode($aResponse);


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>