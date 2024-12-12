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

	@session_start( );
	@ob_start( );

	@ini_set('display_errors', 0);
	@ini_set('log_errors', 0);
	@error_reporting(0);

	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);

	@putenv("TZ=Asia/Karachi");
	@date_default_timezone_set("Asia/Karachi");
	@ini_set("date.timezone", "Asia/Karachi");

	@header("Content-type: text/html; charset=utf-8");
	


	@list($iMicroSeconds, $iSeconds) = @explode(" ", @microtime( ));
	$iPageStartTime = ((float)$iMicroSeconds + (float)$iSeconds);

	$sPage    = substr($_SERVER['PHP_SELF'], (strrpos($_SERVER['PHP_SELF'], "/") + 1));
	$sBaseDir = "";

	if (@strpos($_SERVER['DOCUMENT_ROOT'], ":") == FALSE)
		$sPath = @explode("/", getcwd( ));

	else
		$sPath = @explode("\\", getcwd( ));

	$sCurDir = $sPath[(count($sPath) - 1)];

	$sModule = str_replace("-", " ", $sCurDir);
	$sModule = ucwords($sModule);

	if ($sModule == "Data")
		$sModule = "Data Entry";

	if (!@in_array($sCurDir, array("Portal", "portal", "www")))
		$sBaseDir = "../";

	@require_once("configs.php");
	@require_once("db.class.php");
	@require_once("sp-db.class.php");
	@require_once("io.class.php");
	@require_once("sms.class.php");
	@require_once("common-functions.php");
	@require_once("browser.class.php");
	@require_once("PHPMailer/class.phpmailer.php");
	
	
	if ($_SERVER['HTTPS'] != "on")
	{
		header("Location: ".(SITE_URL.substr($_SERVER['REQUEST_URI'], 1)));
		exit( );
	}
	

	$sUserRights = array( );

	$sUserRights['Add']    = "N";
	$sUserRights['Edit']   = "N";
	$sUserRights['Delete'] = "N";
	$sUserRights['View']   = "Y";

	if ($sCurDir == "admin")
	{
		checkLogin( );

		if ($_SESSION['Admin'] != "Y")
			redirect(SITE_URL, "ACCESS_DENIED");
	}

	else if (@in_array($sCurDir, array("attendance", "libs", "movies", "ajax", "scripts")))
	{
		// do nothing
	}

	else if (@in_array($sCurDir, array("data", "shipping", "quonda", "reports", "sampling", "vsn", "vsr", "qsn", "hr", "bta", "pcc", "crc", "yarn", "dropbox", "qmip", "bookings")) && $sPage == "index.php")
	{
		checkLogin( );
	}

	else if (!@in_array($sCurDir, array("Portal", "portal", "www", "crons-web", "dashboard", "flipbook")))
	{
		checkLogin( );

		$sUserRights = getUserRights( );

		if ($sUserRights['View'] != "Y")
			redirect(SITE_URL, "ACCESS_DENIED");
	}


	logSession( );
?>
