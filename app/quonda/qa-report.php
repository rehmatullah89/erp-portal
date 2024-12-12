<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree QUONDA App                                                                   **
	**  Version 3.0                                                                              **
	**                                                                                           **
	**  http://app.3-tree.com                                                                    **
	**                                                                                           **
	**  Copyright 2008-17 (C) Triple Tree                                                        **
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
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$User      = IO::strValue("User");
	$AuditCode = IO::strValue("AuditCode");


	if ($User != "" && $AuditCode != "" && $AuditCode{0} == "S")
	{
		$sSQL = "SELECT id, name, status, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1 && $objDb->getField(0, "status") == "A")
		{
			$iUser  = $objDb->getField(0, "id");
			$sName  = $objDb->getField(0, "name");
			$sGuest = $objDb->getField(0, "guest");


			$sSQL = "SELECT id, report_id, brand_id, audit_stage, po_id FROM tbl_qa_reports WHERE audit_code='$AuditCode'";
			$objDb->query($sSQL);

			$Id         = $objDb->getField(0, "id");
			$ReportId   = $objDb->getField(0, "report_id");
			$Brand      = $objDb->getField(0, "brand_id");
			$AuditStage = $objDb->getField(0, "audit_stage");
			$PoId       = $objDb->getField(0, "po_id");



			if ($Brand == 0)
				$Brand = getDbValue("brand_id", "tbl_po", "id='$PoId'");

			$sBaseDir = ABSOLUTE_PATH;


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

			else if ($ReportId == 14 || $ReportId == 34)
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
					
			else if (@in_array($ReportId, array(15, 16, 17, 18, 21, 22)))
				@include($sBaseDir."includes/quonda/export-qmip-report.php");
/*
			else if ($ReportId == 15)
				@include($sBaseDir."includes/quonda/export-vendor-cutting-report.php");

			else if ($ReportId == 16)
				@include($sBaseDir."includes/quonda/export-vendor-roving-end-report.php");

			else if ($ReportId == 17)
				@include($sBaseDir."includes/quonda/export-vendor-finishing-report.php");

			else if ($ReportId == 18)
				@include($sBaseDir."includes/quonda/export-vendor-final-report.php");
			
			else if ($ReportId == 21)
				@include($sBaseDir."includes/quonda/export-vendor-embellishment-report.php");

			else if ($ReportId == 22)
				@include($sBaseDir."includes/quonda/export-vendor-end-line-report.php");
*/

			else if ($ReportId == 20 || $ReportId == 23)
				@include($sBaseDir."includes/quonda/export-kik-report.php");

			else if ($ReportId == 24)
				@include($sBaseDir."includes/quonda/export-sampling-report.php");

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

			else
				@include($sBaseDir."includes/quonda/export-other-report.php");
		}
	}


	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>