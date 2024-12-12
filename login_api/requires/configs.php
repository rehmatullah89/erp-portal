<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                              **
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

	// Database Configuration Values
	define("DB_SERVER",   "localhost");
	define("DB_NAME",     "test");
	define("DB_USER",     "root");
	define("DB_PASSWORD", "3tree");

	define("LOG_DB_TRANSACTIONS",   TRUE);
	define("DB_LOGS_DIR",           "C:/wamp/www/portal/logs/web/"); // Absolute Path
	define("LOG_SESSION_USER_ID",   "UserId");
	define("LOG_SESSION_USER_NAME", "Name");


	define("SITE_TITLE",  "Triple Tree Customer Portal");
	define("SITE_URL",    "http://portal.3-tree.com/");

	// Email Configuration Values
	define("SENDER_NAME",  "Triple Tree Customer Portal");
	define("SENDER_EMAIL", "portal@3-tree.com");

	// paging size
	define("PAGING_SIZE", 50);

	// Temp Dir
	define("TEMP_DIR", "temp/");

	// Absolute Path
	define("ABSOLUTE_PATH", "C:/wamp/www/portal/");
	define("API_CALLS_DIR", "C:/wamp/www/portal/logs/api/");

	define("ANDROID_APP_PATH", "api/android/");

	// Absolute Path
	define("QUONDA_PICS_DIR",  "files/quonda/");
	define("SPECS_SHEETS_DIR", "files/specs-sheet/");

	// User Pictures Dir
	define("USERS_IMG_PATH", "images/users/");

	// Styles Specification Directory
	define('STYLES_SPECS_DIR', 'files/styles/');
	define('STYLES_SKETCH_DIR', 'files/sketches/');

	// Sampling Pics Directory
	define('SAMPLING_PICS_DIR', 'files/sampling/');
	define('SAMPLING_360_DIR',  'files/360-images/');
	define('INLINE_AUDITS_PICS_DIR', 'files/inline-audits/');
	define('SAMPLING_SPECS_SHEETS_DIR', 'files/sampling-specs-sheet/');


	// SMS Server settings - Sending
	define('SMS_NOW_HOST',       '125.209.75.178');
	define('SMS_NOW_USERNAME',   'tahir.shahzad');
	define('SMS_NOW_PASSWORD',   'matrix101');
	define('SMS_NOW_PORT',       '8880');
?>