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
	
	if (@strpos($_SERVER['HTTP_HOST'], "localhost") === FALSE)
	{
		define("DB_SERVER",   "localhost");
		define("DB_NAME",     "dbPortal");
		define("DB_USER",     "root");
		define("DB_PASSWORD", "3tree");

		define("SITE_URL",    "https://portal.3-tree.com/");
	}

	else
	{
		define("DB_SERVER",   "localhost");
		define("DB_NAME",     "dbPortal");
		define("DB_USER",     "root");
		define("DB_PASSWORD", "");

		define("SITE_URL",    "http://localhost/Portal/");
	}



	define("LOG_DB_TRANSACTIONS",   TRUE);
	define("DB_LOGS_DIR",           "C:/wamp/www/portal/logs/web/"); // Absolute Path
	define("LOG_SESSION_USER_ID",   "UserId");
	define("LOG_SESSION_USER_NAME", "Name");
	define("API_CALLS_DIR",         "C:/wamp/www/portal/logs/api/");
	define("ANDROID_APP_PATH",      "api/android/");

	// Email Configuration Values
	define("SENDER_NAME",  "Triple Tree Customer Portal");
	define("SENDER_EMAIL", "portal@3-tree.com");

	// Forgot Password Email
	define("FP_SENDER_NAME",  "Triple Tree Customer Portal");
	define("FP_SENDER_EMAIL", "portal@3-tree.com");
	define("FP_SUBJECT",      "Triple Tree Customer Portal Account Password");

	// Welcome Email Configuration
	define("WELCOME_SENDER_NAME",   "Triple Tree Customer Portal");
	define("WELCOME_SENDER_EMAIL",  "portal@3-tree.com");
	define("WELCOME_EMAIL_SUBJECT", "Welcome to Triple Tree Customer Portal");

	// Contact Us Messaging Configuration
	define('CONTACT_SENDER_NAME',  "Triple Tree Customer Portal");
	define('CONTACT_SENDER_EMAIL', "portal@3-tree.com");

	define('CONTACT_RECIPIENT_NAME',  "Triple Tree Customer Portal");
	define('CONTACT_RECIPIENT_EMAIL', "portal@3-tree.com");

	// Meta Tags
	define("SITE_TITLE",  "Triple Tree Customer Portal");
	define("COPYRIGHT",   "Muhammad Tahir Shahzad");
	define("SITE_EMAIL",  "portal@3-tree.com");

	// SMS Server settings - Sending
	define('SMS_NOW_HOST',       '125.209.75.179');
	define('SMS_NOW_USERNAME',   'tahir');
	define('SMS_NOW_PASSWORD',   'matrix101');
	define('SMS_NOW_PORT',       '8080');

	// SMS Server settings for Quonda - Reading
	define('SMS_HOST',       '125.209.75.178');
	define('SMS_USERNAME',   'administrator');
	define('SMS_PASSWORD',   'matrix101');
	define('SMS_DIR',        '/sms');
	define('SMS_BACKUP_DIR', 'backups/sms/');
	define('SMS_NOW_IN_DIR', 'C:\\Program Files\\NowSMS\\SMS-IN\\');

	// Attendance System Messages Delay
	define('ATTENDANCE_DELAY', 2500);

	// HR Manager ID on Portal
	define('HR_MANAGER', 170);

	// paging size
	define("PAGING_SIZE", 50);

	// Temp Dir
	define("TEMP_DIR", "temp/");

	// Absolute Path
	define("ABSOLUTE_PATH", "C:/wamp/www/portal/");

	// Signatures Dir
	define("SIGNATURES_DIR", "signatures/");

	// Email Logs Dir
	define("EMAIL_LOGS_DIR",  "C:\\wamp\\www\\portal\\logs\\emails\\");


	// PO/Styles Specification Directory
	define("PO_DOCS_DIR",      "files/pos/");
	define('STYLES_SPECS_DIR', 'files/styles/');
	define('STYLES_SKETCH_DIR', 'files/sketches/');
    define('SHIPPING_PORTS_DIR', 'files/destinations/');

	// Pre-Shipment Specification Directory
	define('PRE_SHIPMENT_DIR', 'files/pre-shipment/');

	// Post-Shipment Specification Directory
	define('POST_SHIPMENT_DIR', 'files/post-shipment/');

	// Sampling Pics Directory
	define('SAMPLING_PICS_DIR',         'files/sampling/');
	define('SAMPLING_360_DIR',          'files/360-images/');
	define('INLINE_AUDITS_PICS_DIR',    'files/inline-audits/');
	define('SAMPLING_SPECS_SHEETS_DIR', 'files/sampling-specs-sheet/');

	// Quonda Pics Directory
	define('QUONDA_PICS_DIR',    'files/quonda/');
	define('SPECS_SHEETS_DIR',   'files/specs-sheet/');
	define('PACKAGING_PICS_DIR', 'files/packaging/');

	// Leave Applications Dir
	define("LEAVE_APPS_DIR", "files/leave-apps/");

	// Library Files Dir
	define("LIBRARY_FILES_DIR", "files/library/");

	// Lab Dips Directory
	define('LAB_DIPS_DIR', 'files/lab-dips/');

	// Fabric Files Dir
	define("FABRIC_FILES_DIR", "files/fabrics/");

	// User Pictures Dir
	define("USERS_IMG_PATH", "images/users/");

	// Blog Pictures Path
	define("BLOG_IMG_PATH", "images/blog/");

	// Videos
	define("VIDEO_FILES_DIR", "files/videos/");

	// T&C Audits
	define("TNC_PICS_DIR", "files/tnc-audits/");

	// CRC Reports
	define("CRC_REPORTS_DIR",       "files/crc-reports/");
	define("COMPLIANCE_AUDITD_DIR", "files/compliance-audits/");
	define("SAFETY_AUDITD_DIR",     "files/safety-audits/");
	define("CRC_AUDITS_IMG_PATH",   "files/crc-audits/");

	// Vendor Albums Path
	define("VENDOR_ALBUMS_IMG_PATH", "images/vendors/albums/");
	define("VENDOR_PICS_IMG_PATH", "images/vendors/pictures/");

	define("VENDOR_CERTIFICATIONS_DIR", "files/certifications/");

	// Fabric Category Path
	define("FABRIC_CATEGORIES_IMG_PATH", "images/fabric-library/categories/");

	// Fabric Pictures Path
	define("FABRIC_PICS_IMG_PATH", "images/fabric-library/pictures/");

	// User Albums Path
	define("USER_ALBUMS_IMG_PATH", "images/users/albums/");

	// User Photos Path
	define("USER_PHOTOS_IMG_PATH", "images/users/album-photos/");

	// Product Pictures Path
	define("PRODUCTS_IMG_DIR", "files/products/");
	define("PRODUCTS_360_DIR", "movies/products/");

	// PCC Galleries Path
	define("PCC_GALLERIES_IMG_PATH", "images/pcc/galleries/");
	define("PCC_PICS_IMG_PATH",      "images/pcc/pictures/");

	define("PCC_COMPANIES_IMG_PATH",      "images/pcc/companies/");
	define("PCC_BOARD_TYPES_IMG_PATH",    "images/pcc/board-types/");
	define("PCC_BOARDS_IMG_PATH",         "images/pcc/boards/");
	define("PCC_MARKETS_IMG_PATH",        "images/pcc/markets/");
	define("PCC_SEASONS_IMG_PATH",        "images/pcc/seasons/");
	define("PCC_PHOTOS_IMG_PATH",         "images/pcc/photos/");
	define("PCC_FABRICS_IMG_PATH",        "images/pcc/fabrics/");
	define("PCC_CATEGORIES_IMG_PATH",     "images/pcc/categories/");
	define("PCC_PRODUCT_LEVELS_IMG_PATH", "images/pcc/product-levels/");
	define("PCC_STYLES_IMG_PATH",         "images/pcc/styles/");
	define("PCC_SAMPLES_IMG_PATH",        "images/pcc/samples/");
	define("PCC_STYLES_PDF_DIR",          "files/pcc-styles/");
	define("PCC_COLORS_IMG_PATH",         "images/pcc/colors/");

	// Dropbox
	define('DROPBOX_FILES_DIR',  'files/dropbox/');

	// Flipbooks
	define('MDL_PRODUCTS_DIR', 'files/flipbooks/');

	// Bookings
	define("BOOKINGS_DIR",         "files/bookings/");

	// Signatures
	define('SIGNATURES_IMG_DIR',      'files/signatures/');
	define('USER_SIGNATURES_IMG_DIR', 'files/user-signatures/');

	// Database Backup Config
	define('DB_BACKUP_PATH',          'backups/db/');
	define('BACKUP_FILE_NAME_FORMAT', 'db-portal-%Y-%m-%d-%H-%i-%s.sql');

	// Data Displaying Grid Table Colors
	define('BORDER_COLOR',     '#ffffff');
	define('HEADER_ROW_COLOR', '#666666');
	define('EVEN_ROW_COLOR',   '#f6f4f5');
	define('ODD_ROW_COLOR',    '#dcdcdc');
	define('HOVER_ROW_COLOR',  '#f1edcd');



	$iAqlChart         = array( );
	$iAqlChart["2"]    = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 0, "4" => 0, "F" => 2, "T" => 8);
	$iAqlChart["3"]    = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 0, "4" => 0, "F" => 9, "T" => 15);
	$iAqlChart["5"]    = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 0, "4" => 0, "F" => 16, "T" => 25);
	$iAqlChart["8"]    = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 0, "4" => 0, "F" => 26, "T" => 50);
	$iAqlChart["13"]   = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 0, "4" => 1, "F" => 51, "T" => 90);
	$iAqlChart["20"]   = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 1, "4" => 2, "F" => 91, "T" => 150);
	$iAqlChart["32"]   = array("0.65" => 0, "1" => 0, "1.5" => 1, "2.5" => 2, "4" => 3, "F" => 151, "T" => 280);
	$iAqlChart["50"]   = array("0.65" => 0, "1" => 1, "1.5" => 2, "2.5" => 3, "4" => 5, "F" => 281, "T" => 500);
	$iAqlChart["80"]   = array("0.65" => 1, "1" => 2, "1.5" => 3, "2.5" => 5, "4" => 7, "F" => 501, "T" => 1200);
	$iAqlChart["125"]  = array("0.65" => 2, "1" => 3, "1.5" => 5, "2.5" => 7, "4" => 10, "F" => 1201, "T" => 3200);
	$iAqlChart["200"]  = array("0.65" => 3, "1" => 5, "1.5" => 7, "2.5" => 10, "4" => 14, "F" => 3201, "T" => 10000);
	$iAqlChart["315"]  = array("0.65" => 5, "1" => 7, "1.5" => 10, "2.5" => 14, "4" => 21, "F" => 10001, "T" => 35000);
	$iAqlChart["500"]  = array("0.65" => 7, "1" => 10, "1.5" => 14, "2.5" => 21, "4" => 21, "F" => 35001, "T" => 150000);
	$iAqlChart["800"]  = array("0.65" => 10, "1" => 14, "1.5" => 21, "2.5" => 21, "4" => 21, "F" => 150001, "T" => 500000);
	$iAqlChart["1250"] = array("0.65" => 14, "1" => 21, "1.5" => 21, "2.5" => 21, "4" => 21, "F" => 500001, "T" => 50000000);


	$iQmipReports = array(15,16,17,18,21,22,41,42);
	$sQmipReports = @implode(",", $iQmipReports);

	$iQmipVendors = array(13,229);
	$sQmipVendors = @implode(",", $iQmipVendors);
?>