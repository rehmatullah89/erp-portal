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

	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$Id        = IO::strValue('Id');
	$AuditCode = IO::strValue('AuditCode');

	if ($Id != @md5($AuditCode))
		die("Invalid Request, cannot be processed.");
	
	

	$sSQL = "SELECT id, po_id, report_id, style_id, audit_stage FROM tbl_qa_reports WHERE audit_code='$AuditCode'";
	$objDb->query($sSQL);

	$Id         = $objDb->getField(0, "id");
	$Po         = $objDb->getField(0, "po_id");
	$ReportId   = $objDb->getField(0, "report_id");
	$Style      = $objDb->getField(0, "style_id");
	$AuditStage = $objDb->getField(0, "audit_stage");

	if ($Style == 0)
		$Brand = getDbValue("brand_id", "tbl_po", "id='$Po'");

	else
		$Brand = getDbValue("sub_brand_id", "tbl_styles", "id='$Style'");


	if ($ReportId == 4)
		@include("includes/quonda/export-inflatable-report.php");

	else if ($ReportId == 6)
		@include("includes/quonda/export-gf-report.php");

	else if ($ReportId == 7)
		@include("includes/quonda/export-ar-report.php");

	else if ($ReportId == 19)
		@include("includes/quonda/export-adidas-report.php");

	else if ($ReportId == 8)
		@include("includes/quonda/export-apparel-report.php");

	else if ($ReportId == 9)
		@include("includes/quonda/export-yarn-report.php");

	else if ($ReportId == 10)
		@include("includes/quonda/export-jako-report.php");

	else if ($ReportId == 1 && @in_array($Brand, array(87, 119, 120, 121)))
		@include("includes/quonda/export-old-jako-report.php");

	else if ($ReportId == 11)
		@include("includes/quonda/export-ms-report.php");

	else if ($ReportId == 14 || $ReportId == 34)
		@include("includes/quonda/export-mgf-report.php");

	else if (@in_array($ReportId, array(2, 5)))
		@include("includes/quonda/export-woven-report.php");

	else if ($ReportId == 1 && ($Brand == 32 || $Brand == 111))
	{
		if ($AuditStage == "F" && $Brand == 32)
			@include("includes/quonda/export-nike-final-audit-knits-report.php");

		else
			@include("includes/quonda/export-nike-knits-report.php");
	}

	else if ($ReportId == 13)
		@include("includes/quonda/export-costco-report.php");
	
	else if (@in_array($ReportId, array(15, 16, 17, 18, 21, 22, 41, 42)))
		@include($sBaseDir."includes/quonda/export-qmip-report.php");
/*
	else if ($ReportId == 18)
		@include("includes/quonda/export-vendor-final-report.php");

	else if ($ReportId == 15)
		@include("includes/quonda/export-vendor-cutting-report.php");

	else if ($ReportId == 16)
		@include("includes/quonda/export-vendor-roving-end-report.php");

	else if ($ReportId == 17)
		@include("includes/quonda/export-vendor-finishing-report.php");
	
	else if ($ReportId == 21)
		@include("includes/quonda/export-vendor-embellishment-report.php");

	else if ($ReportId == 22)
		@include("includes/quonda/export-vendor-end-line-report.php");
*/
	else if ($ReportId == 20 || $ReportId == 23)
		@include("includes/quonda/export-kik-report.php");

	else if ($ReportId == 24)
		@include("includes/quonda/export-sampling-report.php");

    else if ($ReportId == 25)
    {
    	if ($AuditStage == 'F')
			@include($sBaseDir."includes/quonda/export-final-billabong-report.php");

    	else
			@include($sBaseDir."includes/quonda/export-inline-billabong-report.php");
	}

    else if ($ReportId == 26)
		@include($sBaseDir."includes/quonda/export-tnc-report.php");
	
    else if ($ReportId == 28)
		@include($sBaseDir."includes/quonda/export-controlist-report.php");
	
	else if ($ReportId == 29)
		@include($sBaseDir."includes/quonda/export-leverstyle-report.php");
	
	else if ($ReportId == 30)
		@include($sBaseDir."includes/quonda/export-towel-report.php");
	
	else if ($ReportId == 31)
		@include($sBaseDir."includes/quonda/export-hybrid-apparel-report.php");
	
	else if ($ReportId == 32)
		@include($sBaseDir."includes/quonda/export-arcadia-report.php");
	
	else if ($ReportId == 33)
		@include($sBaseDir."includes/quonda/export-gms-report.php");
	
	else if ($ReportId == 35)
		@include($sBaseDir."includes/quonda/export-timezone-report.php");
	
	else if ($ReportId == 36)
		@include($sBaseDir."includes/quonda/export-hybrid-link-report.php");
	
	else if ($ReportId == 37)
		@include($sBaseDir."includes/quonda/export-armedangels-report.php");
	
    else if ($ReportId == 38)
		@include($sBaseDir."includes/quonda/export-tmclothing-report.php");
	
    else if ($ReportId == 39)
		@include($sBaseDir."includes/quonda/export-hohenstein-report.php");
	
    else if ($ReportId == 43)
		@include($sBaseDir."includes/quonda/export-imf-report.php");

        else if ($ReportId == 44 || $ReportId == 45)
		@include($sBaseDir."includes/quonda/export-levis-report.php");

//    else if ($ReportId == 1)
//		@include($sBaseDir."includes/quonda/export-knits-report.php");

	else
		@include("includes/quonda/export-knits-report.php");



	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>