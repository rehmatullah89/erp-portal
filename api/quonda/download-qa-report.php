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

	$AuditCode = IO::strValue('AuditCode');

	$sSQL = "SELECT style_id, po_id, report_id, audit_stage FROM tbl_qa_reports WHERE audit_code='$AuditCode'";
	$objDb->query($sSQL);

	$ReportId   = $objDb->getField(0, "report_id");
	$Style      = $objDb->getField(0, "style_id");
	$Po         = $objDb->getField(0, "po_id");
	$AuditStage = $objDb->getField(0, "audit_stage");

	if ($Style > 0)
		$Brand = getDbValue("sub_brand_id", "tbl_styles", "id='$Style'");

	else
		$Brand = getDbValue("brand_id", "tbl_po", "id='$Po'");



	if ($ReportId == 4)
		@include($sBaseDir."includes/quonda/export-inflatable-report.php");

	else if ($ReportId == 6)
		@include($sBaseDir."includes/quonda/export-gf-report.php");

	else if ($ReportId == 7)
		@include($sBaseDir."includes/quonda/export-ar-report.php");

	else if ($ReportId == 19)
		@include($sBaseDir."includes/quonda/export-adidas-report.php");

	else if ($ReportId == 8)
		@include($sBaseDir."includes/quonda/export-apparel-report.php");

	else if ($ReportId == 9)
		@include($sBaseDir."includes/quonda/export-yarn-report.php");

	else if ($ReportId == 10)
		@include($sBaseDir."includes/quonda/export-jako-report.php");

	else if ($ReportId == 1 && @in_array($Brand, array(87, 119, 120, 121)))
		@include($sBaseDir."includes/quonda/export-old-jako-report.php");

	else if ($ReportId == 11)
		@include($sBaseDir."includes/quonda/export-ms-report.php");

	else if ($ReportId == 14)
		@include($sBaseDir."includes/quonda/export-mgf-report.php");

	else if (@in_array($ReportId, array(2, 5)))
		@include($sBaseDir."includes/quonda/export-woven-report.php");

	else if ($ReportId == 1 && ($Brand == 32 || $Brand == 111))
	{
		if ($AuditStage == "F" && $Brand == 32)
			@include($sBaseDir."includes/quonda/export-nike-final-audit-knits-report.php");

		else
			@include($sBaseDir."includes/quonda/export-nike-knits-report.php");
	}

	else if ($ReportId == 13)
		@include($sBaseDir."includes/quonda/export-costco-report.php");

	else if ($ReportId == 15)
		@include($sBaseDir."includes/quonda/export-vendor-cutting-report.php");

	else if ($ReportId == 16)
		@include($sBaseDir."includes/quonda/export-vendor-roving-end-report.php");

	else if ($ReportId == 17)
		@include($sBaseDir."includes/quonda/export-vendor-finishing-report.php");

	else if ($ReportId == 18)
		@include($sBaseDir."includes/quonda/export-vendor-final-report.php");

	else if ($ReportId == 20 || $ReportId == 23)
		@include($sBaseDir."includes/quonda/export-kik-report.php");

	else if ($ReportId == 21)
		@include($sBaseDir."includes/quonda/export-vendor-embellishment-report.php");

	else if ($ReportId == 22)
		@include($sBaseDir."includes/quonda/export-vendor-end-line-report.php");

	else if ($ReportId == 24)
		@include($sBaseDir."includes/quonda/export-sampling-report.php");

	else
		@include($sBaseDir."includes/quonda/export-knits-report.php");


	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>