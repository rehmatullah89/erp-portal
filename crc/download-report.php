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

     if (!strstr($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']))
         die("Hacking Attempt Blocked");


	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sFile = ($sBaseDir.CRC_REPORTS_DIR.IO::strValue("File"));
	$iSize = @filesize($sFile);

	$sName = @basename($sFile);
	$sName = substr($sName, (strpos($sName, "-") + 1));


	if(ini_get('zlib.output_compression'))
		@ini_set('zlib.output_compression', 'Off');

	header('Content-Description: File Transfer');
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
	header('Content-Type: application/force-download');
	header("Content-Type: application/download");
	header("Content-Type: image/jpg");
	header("Content-Disposition: attachment; filename=\"{$sName}\";");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: $iSize");

	@readfile($sFile);

	@ob_end_flush( );


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>