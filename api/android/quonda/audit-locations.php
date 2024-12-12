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

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$User   = IO::strValue("User");
	$Status = IO::strValue("Status");


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, status, vendors, brands, style_categories, report_types, audit_stages FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser            = $objDb->getField(0, "id");
			$sBrands          = $objDb->getField(0, "brands");
			$sVendors         = $objDb->getField(0, "vendors");
			$sStyleCategories = $objDb->getField(0, "style_categories");
			$sReportTypes     = $objDb->getField(0, "report_types");
			$sAuditStages     = $objDb->getField(0, "audit_stages");


			$sSQL = "SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '$sBrands') AND FIND_IN_SET(category_id, '$sStyleCategories') ";
			$objDb->query($sSQL);

			$iCount  = $objDb->getCount( );
			$sStyles = "";

			for ($i = 0; $i < $iCount; $i ++)
				$sStyles .= (",".$objDb->getField($i, 0));

			if ($sStyles != "")
				$sStyles = substr($sStyles, 1);



			$sConditions = " audit_date=CURDATE( ) AND FIND_IN_SET(vendor_id, '$sVendors') AND approved='Y'
			                 AND (po_id='0' OR po_id IN (SELECT id FROM tbl_po WHERE FIND_IN_SET(brand_id, '$sBrands') AND FIND_IN_SET(vendor_id, '$sVendors')))
			                 AND FIND_IN_SET(audit_stage, '$sAuditStages') AND FIND_IN_SET(report_id, '$sReportTypes') ";

			if ($Status == "Final")
				$sConditions .= " AND audit_stage='F' ";

			else if ($Status == "Fail")
				$sConditions .= " AND audit_result='F' ";

			else if ($Status == "Current")
				$sConditions .= " AND (audit_result='' OR ISNULL(audit_result)) AND (CURTIME( ) BETWEEN start_time AND end_time) ";

			if (@strpos($_SESSION["Email"], "marksnspencer.com") === FALSE)
				$sConditions .= " AND (style_id='0' OR FIND_IN_SET(style_id, '$sStyles')) ";

			else
				$sConditions .= " AND FIND_IN_SET(style_id, '$sStyles') ";


			$aResponse = array( );
			$sAuditors = array( );
			$sVendors  = array( );


			$sSQL = "SELECT vendor_id, audit_code, IF(ISNULL(audit_result), '', audit_result) AS _AuditResult,
			                IF (po_id>'0', (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id), 'N/A') AS _Po,
			                IF (user_id>'0', (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id), (SELECT name FROM tbl_auditor_groups WHERE id=tbl_qa_reports.group_id)) AS _Auditor
			         FROM tbl_qa_reports
			         WHERE $sConditions
			         ORDER BY vendor_id, id";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
			$iIndex = -1;
			$iLast  = 0;

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iVendor      = $objDb->getField($i, "vendor_id");
				$sAuditCode   = $objDb->getField($i, "audit_code");
				$sPo          = $objDb->getField($i, "_Po");
				$sAuditor     = $objDb->getField($i, "_Auditor");
				$sAuditResult = $objDb->getField($i, '_AuditResult');



				$sSQL = "SELECT vendor, address, latitude, longitude FROM tbl_vendors WHERE id='$iVendor'";
				$objDb2->query($sSQL);

				$sVendor    = $objDb2->getField(0, "vendor");
				$sLatitude  = $objDb2->getField(0, "latitude");
				$sLongitude = $objDb2->getField(0, "longitude");
				$sAddress   = $objDb2->getField(0, "address");

				if ($sLatitude == "")
					$sLatitude = "";

				if ($sLongitude == "")
					$sLongitude = "";


				if ($iLast != $iVendor)
				{
					$iIndex ++;

					$sAuditors[$iIndex] = array("Name"      => $sVendor,
												"Latitude"  => $sLatitude,
												"Longitude" => $sLongitude,
												"Address"   => $sAddress);
					$iLast = $iVendor;
				}

				$sAuditors[$iIndex]["Audits"][] = array("AuditCode"   => $sAuditCode,
				                                        "Auditor"     => $sAuditor,
				                                        "Po"          => $sPo,
				                                        "AuditResult" => $sAuditResult);
			}

			$sStats = array(
			                 "Total"      => getDbValue("COUNT(*)", "tbl_qa_reports", $sConditions),
			                 "Pakistan"   => getDbValue("COUNT(*)", "tbl_qa_reports", "{$sConditions} AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='162')"),
			                 "Bangladesh" => getDbValue("COUNT(*)", "tbl_qa_reports", "{$sConditions} AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='18')")
			               );


			$aResponse['Status']   = "OK";
			$aResponse['Vendors']  = array( );
			$aResponse['Auditors'] = $sAuditors;
			$aResponse['Stats']    = $sStats;
		}
	}


	print @json_encode($aResponse);


/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($aResponse);

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>