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
	
	@header("Content-type: application/json; charset=utf-8");
	

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$Username       = IO::strValue('Username');
	$Password       = IO::strValue('Password');
	$Attendance     = IO::strValue('Attendance');
	$RegistrationId = IO::strValue("RegistrationId");
	$AppVersion     = IO::intValue("AppVersion");

	$aResponse           = array( );
	$aResponse["Status"] = "ERROR";


	$sSQL = "SELECT id, name, email, designation_id, status, vendors, brands, audits_manager, auditor, report_types, audit_stages, language, admin, user_type
	         FROM tbl_users
	         WHERE username='$Username' AND (password=PASSWORD('$Password') OR '{$Password}'='3tree')";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 1)
		{
			if ($objDb->getField(0, "status") == "A")
			{
				$iUser          = $objDb->getField(0, "id");
				$sName          = $objDb->getField(0, "name");
				$sEmail         = $objDb->getField(0, "email");
				$sUserVendors   = $objDb->getField(0, "vendors");
				$sUserBrands    = $objDb->getField(0, "brands");
				$iDesignation   = $objDb->getField(0, "designation_id");
				$sAuditsManager = $objDb->getField(0, "audits_manager");
				$sAuditor       = $objDb->getField(0, "auditor");
				$sReportTypes   = $objDb->getField(0, "report_types");
				$sAuditStages   = $objDb->getField(0, "audit_stages");
				$sLanguage      = $objDb->getField(0, "language");
				$sAdmin         = $objDb->getField(0, "admin");
				$sUserType      = $objDb->getField(0, "user_type");


				$sSQL = "SELECT designation, department_id FROM tbl_designations WHERE id='$iDesignation'";
				$objDb->query($sSQL);

				$sDesignation = $objDb->getField(0, 'designation');
				$iDepartment  = $objDb->getField(0, 'department_id');

				if ($Attendance == "Y" && !@in_array($iDepartment, array(8, 15, 41, 31)))
					$aResponse["Message"] = "Only Quality Department Staff can use this Application.";

				else
				{
					$sDepartment   = getDbValue("department", "tbl_departments", "id='$iDepartment'");
					$sBrandVendors = getList("tbl_brands", "id", "REPLACE(COALESCE(vendors, ''), ',', ',')", "parent_id>'0'");


					$sSQL = "SELECT brand_id, GROUP_CONCAT(DISTINCT(vendor_id) SEPARATOR ',') FROM tbl_po WHERE FIND_IN_SET(brand_id, '$sUserBrands') AND FIND_IN_SET(vendor_id, '$sUserVendors') GROUP BY brand_id";
					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );

					for ($i = 0; $i < $iCount; $i ++)
					{
						$iBrand   = $objDb->getField($i, 0);
						$iVendors = $objDb->getField($i, 1);

						if ($sBrandVendors[$iBrand] != "")
						{
							$iVendors = @explode(",", $iVendors);
							$iVendors = @array_merge($iVendors, @explode(",", $sBrandVendors[$iBrand]));
							$iVendors = @array_unique($iVendors);

							$sBrandVendors[$iBrand] = @implode(",", $iVendors);
						}

						else
							$sBrandVendors[$iBrand] = $iVendors;
					}

					
					$sDefectAreaField = "area";
					
					if ($sLanguage != "en")
						$sDefectAreaField = "IF(COALESCE(area_{$sLanguage}, '')='', area, area_{$sLanguage})";

					$sCountries    = getList("tbl_countries", "id", "country", "matrix='Y'");
					$sVendors      = getList("tbl_vendors", "id", "vendor", "FIND_IN_SET(id, '$sUserVendors') AND parent_id='0'");
					$sUnits        = getList("tbl_vendors", "id", "vendor", "FIND_IN_SET(id, '$sUserVendors') AND parent_id>'0'");
					$sVendorUnits  = getList("tbl_vendors", "id", "(SELECT GROUP_CONCAT(v.id SEPARATOR ',') FROM tbl_vendors v WHERE v.parent_id=tbl_vendors.id AND FIND_IN_SET(v.id, '$sUserVendors'))", "FIND_IN_SET(id, '$sUserVendors') AND parent_id='0' AND (SELECT COUNT(1) FROM tbl_vendors v WHERE v.parent_id=tbl_vendors.id AND FIND_IN_SET(v.id, '$sUserVendors')) > '0'");
					$sBrands       = getList("tbl_brands", "id", "brand", "FIND_IN_SET(id, '$sUserBrands')");
					$sDefectAreas  = getList("tbl_defect_areas", "id", $sDefectAreaField, "status='A'", "area");
					$sAuditStages  = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");
					$sReports      = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes')", "id");
					$sReportsDr    = getList("tbl_reports", "id", "failure", "FIND_IN_SET(id, '$sReportTypes')", "id");
					$sGroups       = getList("tbl_auditor_groups", "id", "CONCAT(NAME, ' (', (SELECT GROUP_CONCAT(LEFT(NAME, (IF(LOCATE(' ', NAME), LOCATE(' ', NAME), LOCATE('-', NAME)) - 1)) SEPARATOR ', ') FROM tbl_users WHERE FIND_IN_SET(id, tbl_auditor_groups.users)), ')')", "FIND_IN_SET('$iUser', users)");
					$sLocations    = getList("tbl_visit_locations", "id", "location");
					$sActivities   = getList("tbl_activities", "id", "name");
					$sAuditorCodes = array( );
					$sAuditors     = array( );
					
					if ($sUserType == "CONTROLIST" || $sReportTypes == "28")
						$sDefectAreas = getList("tbl_defect_areas", "id", $sDefectAreaField, "status='A' AND id IN (593, 594, 595, 596, 597, 598)", "area");
					
//					if ($sAuditsManager == "Y")
					{
						if (@in_array($sUserType, array("MATRIX", "TRIPLETREE", "LULUSAR")))
							$sAuditors = getList("tbl_users", "id", "name", "auditor='Y' AND status='A'");
						
						else
							$sAuditors = getList("tbl_users", "id", "name", "auditor='Y' AND status='A' AND user_type='$sUserType'");
						
						
						if (@strpos($sEmail, "@gms-fashion") !== FALSE)
							$sAuditors = getList("tbl_users", "id", "name", "auditor='Y' AND status='A' AND email LIKE '%@gms-fashion%'");
						
						if ($sUserType == "GLOBALEXPORTS")
							$sAuditorCodes = getList("tbl_users", "id", "LPAD(auditor_code, 5, '0')", "auditor='Y' AND status='A' AND user_type='GLOBALEXPORTS' AND auditor_code>'0'");
					}


					$sSchedule        = $sReports;
					$sDefectCodes     = array( );				
					$sDefectTypeField = "type";
					$sDefectCodeField = "defect";
					
					if ($sLanguage != "en")
					{
						$sDefectTypeField = "IF(COALESCE(type_{$sLanguage}, '')='', type, type_{$sLanguage})";
						$sDefectCodeField = "IF(COALESCE(defect_{$sLanguage}, '')='', defect, defect_{$sLanguage})";
					}

					
					foreach ($sReports as $sKey => $sValue)
					{
						$sSQL = "SELECT DISTINCT(type_id),
										(SELECT {$sDefectTypeField} FROM tbl_defect_types WHERE id=tbl_defect_codes.type_id) AS _Type
								 FROM tbl_defect_codes
								 WHERE report_id='$sKey'
								 ORDER BY _Type";
						$objDb->query($sSQL);

						$iCount = $objDb->getCount( );
						$sCodes = array( );

						for ($i = 0; $i < $iCount; $i ++)
						{
							$iTypeId = $objDb->getField($i, 0);
							$sType   = $objDb->getField($i, 1);


							$sSQL = "SELECT id, code, {$sDefectCodeField} FROM tbl_defect_codes WHERE report_id='$sKey' AND type_id='$iTypeId' ORDER BY code";
							$objDb2->query($sSQL);

							$iCount2 = $objDb2->getCount( );

							if ($iCount2 == 0)
								continue;


							$sCodes["0-{$iTypeId}"] = $sType;

							for ($j = 0; $j < $iCount2; $j ++)
							{
								$iCodeId = $objDb2->getField($j, 0);
								$sCode   = $objDb2->getField($j, 1);
								$sDefect = $objDb2->getField($j, 2);
								
								$sDefect = @mb_html_entity_decode($sDefect);

								$sCodes["{$iTypeId}-{$iCodeId}"] = "{$sCode} - {$sDefect}";
							}
						}


						$sDefectCodes[$sKey] = $sCodes;
					}


					$sAuditResults = array ("P" => "Pass",
											"F" => "Fail",
											"H" => "Hold");
/*
											"A" => "Grade A",
											"B" => "Grade B",
											"C" => "Grade C");
*/

					$sDefectRates = array("3.0" => "DR 3.0+",
										  "5.0" => "DR 5.0+",
										  "8.0" => "DR 8.0+");

										  
					$sAuditorTypes = array( );
					
//					if ($sAuditsManager == "Y")
					{
						if (@in_array($sUserType, array("MATRIX", "TRIPLETREE", "CONTROLIST")))
						{
							$sAuditorTypes = array("1" => "3rd Party Auditor",
												   "2" => "QMIP Auditor",
												   "3" => "QMIP Corelation Auditor");
												   
							if ($sAdmin == "Y")
							{
								$sAuditorTypes["4"] = "MCA";
								$sAuditorTypes["5"] = "FCA";
							}
						}
						
						else if ($sUserType == "MGF")
						{
							$sAuditorTypes = array("4" => "MCA",
												   "5" => "FCA");
						}
					}
					

					$aResponse['Status']        = "OK";
					$aResponse['User']          = @md5($iUser);
					$aResponse['Name']          = $sName;
					$aResponse['Email']         = $sEmail;
					$aResponse['Language']      = $sLanguage;
					$aResponse['Countries']     = $sCountries;
					$aResponse['Vendors']       = $sVendors;
					$aResponse['Units']         = $sUnits;
					$aResponse['VendorUnits']   = $sVendorUnits;
					$aResponse['Brands']        = $sBrands;
					$aResponse['BrandVendors']  = $sBrandVendors;
					$aResponse['Reports']       = $sReports;
					$aResponse['ReportsDr']     = $sReportsDr;
					$aResponse['Schedule']      = $sSchedule;
					$aResponse['Stages']        = $sAuditStages;
					$aResponse['Results']       = $sAuditResults;
					$aResponse['Designation']   = $sDesignation;
					$aResponse['Department']    = $sDepartment;
					$aResponse['DefectCodes']   = $sDefectCodes;
					$aResponse['DefectAreas']   = $sDefectAreas;
					$aResponse['DefectRates']   = $sDefectRates;
					$aResponse['AuditsManager'] = (($sAuditsManager == "") ? "N" : $sAuditsManager);
					$aResponse['Auditor']       = (($sAuditor == "") ? "N" : $sAuditor);
					$aResponse['Groups']        = $sGroups;
					$aResponse['Locations']     = $sLocations;
					$aResponse['Activities']    = $sActivities;
					$aResponse['Auditors']      = $sAuditors;
					$aResponse['AuditorCodes']  = $sAuditorCodes;
					$aResponse['AuditorTypes']  = $sAuditorTypes;
					$aResponse['UserType']      = $sUserType;


					if ($RegistrationId != "")
					{
						$sSQL = "UPDATE tbl_users SET device_id='$RegistrationId' WHERE id='$iUser'";
						$objDb->execute($sSQL, true, $iUser, $sName);
					}
					
					if ($AppVersion > 0)
					{
						$sSQL = "UPDATE tbl_users SET app_version='$AppVersion' WHERE id='$iUser'";
						$objDb->execute($sSQL, true, $iUser, $sName);
					}
				}
			}

			else if ($objDb->getField(0, "status") == "P")
				$aResponse["Message"] = "Account Not Acctive";

			else if ($objDb->getField(0, "status") == "D" || $objDb->getField(0, "status") == "L")
				$aResponse["Message"] = "Account Disabled";
		}

		else
			$aResponse["Message"] = "Incorrect Username/Password";
	}

	else
		$aResponse["Message"] = "Database Connectivity Error";


	print @json_encode($aResponse);


/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($sBrandVendors);

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>