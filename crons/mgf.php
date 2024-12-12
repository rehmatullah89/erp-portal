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

	@ini_set('display_errors', 0);
	@ini_set('log_errors', 0);
	@error_reporting(0);

	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);



	$sBaseDir = "C:/wamp/www/portal/";

	@require_once($sBaseDir."requires/configs.php");
	@require_once($sBaseDir."requires/db.class.php");
	@require_once($sBaseDir."requires/PHPMailer/class.phpmailer.php");
	@require_once($sBaseDir."requires/common-functions.php");
	@require_once($sBaseDir."requires/chart.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );


	// Bangladesh - Aeropostale
	$Recipients = array("ukaviratne@mgfsourcing.com"   => "Udaya Kaviratne",
	                    "Balachandran@mgfsourcing.com" => "Manoj Balachandran",
	                    "darho@mgfsourcing.com"        => "Ho, Darren",
	                    "STan@MGFSourcing.com"         => "Tan, Samuel",
	                    "switharana@mgfsourcing.com"   => "Shehan Witharana",
	                    "ASumanadasa@MGFSourcing.com"  => "Aruna Sumanadasa",
	                    "Jjeyaram@mgfsourcing.com"     => "Jeyaram, Jeyadinesh");


	$sSQL = "SELECT id, report_id FROM tbl_qa_reports WHERE audit_result!='' AND report_id='14' AND brand_id='260' AND published='Y' AND TIMESTAMPDIFF(HOUR, published_at, NOW( ))='6'";
	$objDbGlobal->query($sSQL);

	$iReports = $objDbGlobal->getCount( );

	for ($iAudit = 0; $iAudit < $iReports; $iAudit ++)
	{
		$Id       = $objDbGlobal->getField($iAudit, "id");
		$ReportId = $objDbGlobal->getField($iAudit, "report_id");


		@include($sBaseDir."includes/quonda/export-mgf-report.php");


		$sLine   = getDbValue("line", "tbl_lines", "id='$iLine'");
		$sResult = (($sAuditResult == "A" || $sAuditResult == "B" || $sAuditResult == "P") ? "Pass" : (($sAuditResult == "F" || $sAuditResult == "C") ? "Fail" : "Hold"));


		$sBody = "Dear User,<br /><br />
				  Please find attached PDF version of the Audit Report for:<br />
				  Audit Code: {$sAuditCode}<br />
				  PO Number: {$sPo}<br />
				  Vendor: {$sVendor}<br />
				  Brand: {$sBrand}<br />
				  Style: {$sStyle}<br />
				  Line: {$sLine}<br />
				  Audit Stage: {$sAuditStage}<br />
				  Result: {$sResult}<br />
				  <br />
				  <br />
				  Triple Tree Customer Portal";


		$objEmail = new PHPMailer( );

		$objEmail->Subject  = "Triple Tree Audit (Purchase Order No: {$sPo} Style No: {$sStyle}) Conducted for '{$sBrand}' at '{$sVendor}' - Result - '{$sResult}'";
		$objEmail->MsgHTML($sBody);


		foreach ($Recipients as $sEmail => $sName)
		{
			$objEmail->AddAddress($sEmail, $sName);
		}

		$objEmail->AddAttachment($sPdfFile, "{$sAuditCode}.pdf");
		$objEmail->Send( );


		@unlink($sPdfFile);
	}




	// Bangladesh - Talbot
/*
	$Recipients = array("AChan@MGFSourcing.com"   => "AChan",
	                    "lCchan@mgfsourcing.com"  => "Chan, Leo",
	                    "hahong@mgfsourcing.com"  => "Hahong",
	                    "dyang@mgfsourcing.com"   => "Dyang",
	                    "gmorrow@mgfsourcing.com" => "Morrow, Gerry");
*/
	$Recipients = array("AHui@MGFSourcing.com"   => "AHui",
	                    "jepark@mgfsourcing.com" => "jepark",
	                    "hhong@mgfsourcing.com"  => "hhong",
	                    "KSeo@MGFSourcing.com"   => "KSeo",
	                    "KYan@MGFSourcing.com"   => "KYan");


	$sSQL = "SELECT id, report_id FROM tbl_qa_reports WHERE audit_result!='' AND report_id='14' AND brand_id='374' AND published='Y' AND TIMESTAMPDIFF(HOUR, published_at, NOW( ))='6'";
	$objDbGlobal->query($sSQL);

	$iReports = $objDbGlobal->getCount( );

	for ($iAudit = 0; $iAudit < $iReports; $iAudit ++)
	{
		$Id       = $objDbGlobal->getField($iAudit, "id");
		$ReportId = $objDbGlobal->getField($iAudit, "report_id");


		@include($sBaseDir."includes/quonda/export-mgf-report.php");


		$sLine   = getDbValue("line", "tbl_lines", "id='$iLine'");
		$sResult = (($sAuditResult == "A" || $sAuditResult == "B" || $sAuditResult == "P") ? "Pass" : (($sAuditResult == "F" || $sAuditResult == "C") ? "Fail" : "Hold"));


		$sBody = "Dear User,<br /><br />
				  Please find attached PDF version of the Audit Report for:<br />
				  Audit Code: {$sAuditCode}<br />
				  PO Number: {$sPo}<br />
				  Vendor: {$sVendor}<br />
				  Brand: {$sBrand}<br />
				  Style: {$sStyle}<br />
				  Line: {$sLine}<br />
				  Audit Stage: {$sAuditStage}<br />
				  Result: {$sResult}<br />
				  <br />
				  <br />
				  Triple Tree Customer Portal";


		$objEmail = new PHPMailer( );

		$objEmail->Subject  = "Triple Tree Audit (Purchase Order No: {$sPo} Style No: {$sStyle}) Conducted for '{$sBrand}' at '{$sVendor}' - Result - '{$sResult}'";
		$objEmail->MsgHTML($sBody);


		foreach ($Recipients as $sEmail => $sName)
		{
			$objEmail->AddAddress($sEmail, $sName);
		}

		$objEmail->AddAttachment($sPdfFile, "{$sAuditCode}.pdf");
		$objEmail->Send( );


		@unlink($sPdfFile);
	}




	// China - Express
	$Recipients = array("lCchan@mgfsourcing.com" => "Chan, Leo",
	                    "epoon@mgfsourcing.com"  => "Poon, Edward");

	$sChinaVendors = getDbValue("GROUP_CONCAT(id SEPARATOR ',')", "tbl_vendors", "country_id='44'");


	$sSQL = "SELECT id, report_id FROM tbl_qa_reports WHERE audit_result!='' AND report_id='14' AND brand_id='368' AND FIND_IN_SET(vendor_id, '$sChinaVendors') AND published='Y' AND TIMESTAMPDIFF(HOUR, published_at, NOW( ))='6'";
	$objDbGlobal->query($sSQL);

	$iReports = $objDbGlobal->getCount( );

	for ($iAudit = 0; $iAudit < $iReports; $iAudit ++)
	{
		$Id       = $objDbGlobal->getField($iAudit, "id");
		$ReportId = $objDbGlobal->getField($iAudit, "report_id");


		@include($sBaseDir."includes/quonda/export-mgf-report.php");


		$sLine   = getDbValue("line", "tbl_lines", "id='$iLine'");
		$sResult = (($sAuditResult == "A" || $sAuditResult == "B" || $sAuditResult == "P") ? "Pass" : (($sAuditResult == "F" || $sAuditResult == "C") ? "Fail" : "Hold"));


		$sBody = "Dear User,<br /><br />
				  Please find attached PDF version of the Audit Report for:<br />
				  Audit Code: {$sAuditCode}<br />
				  PO Number: {$sPo}<br />
				  Vendor: {$sVendor}<br />
				  Brand: {$sBrand}<br />
				  Style: {$sStyle}<br />
				  Line: {$sLine}<br />
				  Audit Stage: {$sAuditStage}<br />
				  Result: {$sResult}<br />
				  <br />
				  <br />
				  Triple Tree Customer Portal";


		$objEmail = new PHPMailer( );

		$objEmail->Subject  = "Triple Tree Audit (Purchase Order No: {$sPo} Style No: {$sStyle}) Conducted for '{$sBrand}' at '{$sVendor}' - Result - '{$sResult}'";
		$objEmail->MsgHTML($sBody);


		foreach ($Recipients as $sEmail => $sName)
		{
			$objEmail->AddAddress($sEmail, $sName);
		}

		$objEmail->AddAttachment($sPdfFile, "{$sAuditCode}.pdf");
		$objEmail->Send( );


		@unlink($sPdfFile);
	}



	// SAN Fernando
	$Recipients = array("SANFernando@MGFSourcing.com" => "SAN Fernando");


	$sSQL = "SELECT id, report_id FROM tbl_qa_reports WHERE audit_result!='' AND report_id='14' AND brand_id='260' AND vendor_id='249' AND published='Y' AND TIMESTAMPDIFF(HOUR, published_at, NOW( ))='6'";
	$objDbGlobal->query($sSQL);

	$iReports = $objDbGlobal->getCount( );

	for ($iAudit = 0; $iAudit < $iReports; $iAudit ++)
	{
		$Id       = $objDbGlobal->getField($iAudit, "id");
		$ReportId = $objDbGlobal->getField($iAudit, "report_id");


		@include($sBaseDir."includes/quonda/export-mgf-report.php");


		$sLine   = getDbValue("line", "tbl_lines", "id='$iLine'");
		$sResult = (($sAuditResult == "A" || $sAuditResult == "B" || $sAuditResult == "P") ? "Pass" : (($sAuditResult == "F" || $sAuditResult == "C") ? "Fail" : "Hold"));


		$sBody = "Dear User,<br /><br />
				  Please find attached PDF version of the Audit Report for:<br />
				  Audit Code: {$sAuditCode}<br />
				  PO Number: {$sPo}<br />
				  Vendor: {$sVendor}<br />
				  Brand: {$sBrand}<br />
				  Style: {$sStyle}<br />
				  Line: {$sLine}<br />
				  Audit Stage: {$sAuditStage}<br />
				  Result: {$sResult}<br />
				  <br />
				  <br />
				  Triple Tree Customer Portal";


		$objEmail = new PHPMailer( );

		$objEmail->Subject  = "Triple Tree Audit (Purchase Order No: {$sPo} Style No: {$sStyle}) Conducted for '{$sBrand}' at '{$sVendor}' - Result - '{$sResult}'";
		$objEmail->MsgHTML($sBody);


		foreach ($Recipients as $sEmail => $sName)
		{
			$objEmail->AddAddress($sEmail, $sName);
		}

		$objEmail->AddAttachment($sPdfFile, "{$sAuditCode}.pdf");
		$objEmail->Send( );


		@unlink($sPdfFile);
	}



	// mcheung
	$Recipients = array("mcheung@mgfsourcing.com" => "Mcheung");


	$sSQL = "SELECT id, report_id FROM tbl_qa_reports WHERE audit_result!='' AND report_id='14' AND brand_id='260' AND FIND_IN_SET(vendor_id, '247,248,251') AND published='Y' AND TIMESTAMPDIFF(HOUR, published_at, NOW( ))='6'";
	$objDbGlobal->query($sSQL);

	$iReports = $objDbGlobal->getCount( );

	for ($iAudit = 0; $iAudit < $iReports; $iAudit ++)
	{
		$Id       = $objDbGlobal->getField($iAudit, "id");
		$ReportId = $objDbGlobal->getField($iAudit, "report_id");


		@include($sBaseDir."includes/quonda/export-mgf-report.php");


		$sLine   = getDbValue("line", "tbl_lines", "id='$iLine'");
		$sResult = (($sAuditResult == "A" || $sAuditResult == "B" || $sAuditResult == "P") ? "Pass" : (($sAuditResult == "F" || $sAuditResult == "C") ? "Fail" : "Hold"));


		$sBody = "Dear User,<br /><br />
				  Please find attached PDF version of the Audit Report for:<br />
				  Audit Code: {$sAuditCode}<br />
				  PO Number: {$sPo}<br />
				  Vendor: {$sVendor}<br />
				  Brand: {$sBrand}<br />
				  Style: {$sStyle}<br />
				  Line: {$sLine}<br />
				  Audit Stage: {$sAuditStage}<br />
				  Result: {$sResult}<br />
				  <br />
				  <br />
				  Triple Tree Customer Portal";


		$objEmail = new PHPMailer( );

		$objEmail->Subject  = "Triple Tree Audit (Purchase Order No: {$sPo} Style No: {$sStyle}) Conducted for '{$sBrand}' at '{$sVendor}' - Result - '{$sResult}'";
		$objEmail->MsgHTML($sBody);


		foreach ($Recipients as $sEmail => $sName)
		{
			$objEmail->AddAddress($sEmail, $sName);
		}

		$objEmail->AddAttachment($sPdfFile, "{$sAuditCode}.pdf");
		$objEmail->Send( );


		@unlink($sPdfFile);
	}



	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>