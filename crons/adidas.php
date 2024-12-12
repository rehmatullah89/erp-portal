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


	$Recipients = array("adidas@apparelco.com" => "Adidas Group");
/*
	$Recipients = array("avneesh.kumar@adidas-group.com"     => "Avneesh Kumar",
	                    "franklin.benjamin@adidas-Group.com" => "Franklin Benjamin",
	                    "adidas@apparelco.com"               => "Adidas Group",
	                    "kumudu@styletextile.com"            => "Kumudu",
	                    "islam@apparelco.com"                => "Muhammad Islam",
	                    "adil@apparelco.com"                 => "Adil Saleem",
	                    "tahir@apparelco.com"                => "Tahir Islam");
*/

	$sSQL = "SELECT id, report_id FROM tbl_qa_reports WHERE audit_result!='' AND (report_id='7' OR report_id='19') AND TIMESTAMPDIFF(HOUR, CONCAT(audit_date, ' ', end_time), NOW( ))='24'";
	$objDbGlobal->query($sSQL);

	$iReports = $objDbGlobal->getCount( );

	for ($iAudit = 0; $iAudit < $iReports; $iAudit ++)
	{
		$Id       = $objDbGlobal->getField($iAudit, "id");
		$ReportId = $objDbGlobal->getField($iAudit, "report_id");


		if ($ReportId == 7)
			@include($sBaseDir."includes/quonda/export-ar-report.php");

		else
			@include($sBaseDir."includes/quonda/export-adidas-report.php");


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