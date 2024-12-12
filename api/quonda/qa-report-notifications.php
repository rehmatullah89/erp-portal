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
	$objDb3      = new Database( );

	$objSms = new Sms( );


	$AuditCode = IO::strValue("AuditCode");

	$iAuditCode = intval(substr($AuditCode, 1));
	$sAuditCode = $AuditCode;
	$iAuditId   = $iAuditCode;


	$sSQL = "SELECT style_id, vendor_id, audit_stage, audit_result, total_gmts, report_id FROM tbl_qa_reports WHERE id='$iAuditCode'";
	$objDb->query($sSQL);

	$iStyleId     = $objDb->getField(0, "style_id");
	$iVendorId    = $objDb->getField(0, "vendor_id");
	$sAuditStage  = $objDb->getField(0, "audit_stage");
	$sAuditResult = $objDb->getField(0, "audit_result");
	$iTotalGmts   = $objDb->getField(0, "total_gmts");
	$iReportId    = $objDb->getField(0, "report_id");


	$sSQL  = "SELECT line FROM tbl_lines WHERE id=(SELECT line_id FROM tbl_qa_reports WHERE id='$iAuditCode')";
	$objDb->query($sSQL);

	$sLine = $objDb->getField(0, 0);


	$sSQL = "SELECT style, sub_brand_id FROM tbl_styles WHERE id='$iStyleId'";
	$objDb->query($sSQL);

	$sStyle   = $objDb->getField(0, 0);
	$iBrandId = (int)$objDb->getField(0, 1);



	$sBrand   = getDbValue("brand", "tbl_brands", "id='$iBrandId'");
	$sVendor  = getDbValue("vendor", "tbl_vendors", "id='$iVendorId'");


	if ($iReportId == 10)
		$iDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditCode' AND nature='1'");

	else if ($iReportId == 11)
		$iDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditCode' AND (nature='0' OR nature='2.5')");

	else
		$iDefects = getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditCode' AND nature='0'");

	$fDhu = @round((($iDefects / $iTotalGmts) * 100), 2);


	// Updating DHU
	$sSQL = "UPDATE tbl_qa_reports SET dhu='$fDhu' WHERE id='$iAuditId'";
	$objDb->execute($sSQL);


	// DR Above Target Line
	@include($sBaseDir."includes/sms/dr-above-target-line.php");


	// Continuity Defect
	@include($sBaseDir."includes/sms/continuity-defect.php");



	$objSms->close( );

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>