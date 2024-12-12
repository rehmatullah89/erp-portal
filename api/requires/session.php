<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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

	//@header("Content-type: application/json; charset=utf-8");


	@require_once("configs.php");
	@require_once("db.class.php");
	@require_once("io.class.php");
	@require_once("sms.class.php");
	@require_once("common-functions.php");
	@require_once("PHPMailer/class.phpmailer.php");

	$sUserRights = array( );

	$sUserRights['Add']    = "N";
	$sUserRights['Edit']   = "N";
	$sUserRights['Delete'] = "N";
	$sUserRights['View']   = "Y";

	$sBaseDir = "../../";



	$iAqlChart         = array( );
	$iAqlChart["13"]   = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 0, "4" => 1);
	$iAqlChart["20"]   = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 1, "4" => 2);
	$iAqlChart["32"]   = array("0.65" => 0, "1" => 0, "1.5" => 1, "2.5" => 2, "4" => 3);
	$iAqlChart["50"]   = array("0.65" => 0, "1" => 1, "1.5" => 2, "2.5" => 3, "4" => 5);
	$iAqlChart["80"]   = array("0.65" => 1, "1" => 2, "1.5" => 3, "2.5" => 5, "4" => 7);
	$iAqlChart["125"]  = array("0.65" => 2, "1" => 3, "1.5" => 5, "2.5" => 7, "4" => 10);
	$iAqlChart["200"]  = array("0.65" => 3, "1" => 5, "1.5" => 7, "2.5" => 10, "4" => 14);
	$iAqlChart["315"]  = array("0.65" => 5, "1" => 7, "1.5" => 10, "2.5" => 14, "4" => 21);
	$iAqlChart["500"]  = array("0.65" => 7, "1" => 10, "1.5" => 14, "2.5" => 21, "4" => 21);
	$iAqlChart["800"]  = array("0.65" => 10, "1" => 14, "1.5" => 21, "2.5" => 21, "4" => 21);
	$iAqlChart["1250"] = array("0.65" => 14, "1" => 21, "1.5" => 21, "2.5" => 21, "4" => 21);
?>