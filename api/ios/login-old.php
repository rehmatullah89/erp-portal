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

	$start = time();
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$Username = IO::strValue('Username');
	$Password = IO::strValue('Password');

	$aResponse = array( );

	$aResponse['Status'] = "ERROR";


	$sSQL = "SELECT id, name, designation_id, picture, status, vendors, brands FROM tbl_users WHERE username='$Username' AND password=PASSWORD('$Password')";



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

				if ($sPicture == "" || !@file_exists("../".USERS_IMG_PATH.'thumbs/'.$sPicture))
					$sPicture = "default.jpg";


				$sSQL = "SELECT designation, department_id FROM tbl_designations WHERE id='$iDesignation'";
				$objDb->query($sSQL);

				$sDesignation = $objDb->getField(0, 'designation');
				$iDepartment  = $objDb->getField(0, 'department_id');

				$sDepartment  = getDbValue("department", "tbl_departments", "id='$iDepartment'");


				$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ($sVendors) AND parent_id='0'");
				$sBrandsList  = getList("tbl_brands", "id", "brand", "id IN ($sBrands)");
				$sReportsList = getList("tbl_reports", "id", "report");
				$sAreasList   = getList("tbl_defect_areas", "id", "CONCAT(LPAD(id, 3, '0'), ' - ', area)", "", "area");
				$sReportsList = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '1,2,3,4,5,6,7,8,11')", "id");


				$sVendors      = array( );
				$sBrands       = array( );
				$sReports      = array( );
				$sSchedule     = array( );
				$sAuditStages  = array( );
				$sAuditResults = array( );
				$sDefectCodes  = array( );
				$sDefectArea   = array( );

//print_r($sVendorsList); exit(0);
				//$dVendors = $sVendorsList;

				/*foreach ($sBrandsList as $sKey => $sValue)
					$sBrands[] = "{$sKey}||{$sValue}";*/
				//$dBrands = $sBrand

				$d=0;
				foreach ($sReportsList as $sKey => $sValue)
				{
					$sReports[]  = "{$sKey}||{$sValue}";
					$sSchedule[] = "{$sKey}||{$sValue}";
					$sDefectCodes[$d]["Report"][$sKey]=$sValue;

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

							$sDefectCodes[$d]["Defect"][$sType][$iCodeId] = "{$sCode} - {$sDefect}";
						}
					}

					//$sDefectCodes[$d]["Defect"][$sType] = @implode("|-|", $sCodes);
//					$sDefectCodes[] = @implode("|-|", $sCodes);
					$d++;
				}

				foreach ($sAreasList as $sKey => $sValue)
					$sDefectAreas[$sKey] = $sValue;


				$sAuditStages["B"] = "Batch";
				$sAuditStages["C"] = "Cutting";
				$sAuditStages["I"] = "In-Process";
				$sAuditStages["P"] = "Pre-Final";
				$sAuditStages["F"] = "Final";
				$sAuditStages["O"] = "Output";
				$sAuditStages["S"] = "Sorting";
				$sAuditStages["ST"] = "Stitching";
				$sAuditStages["FI"] = "Finishing";
				$sAuditStages["OL"] = "Off Loom";
				$sAuditStages["SK"] = "Stock";

				$sAuditResults["P"] = "Pass";
				$sAuditResults["F"] = "Fail";
				$sAuditResults["H"] = "Hold";
				$sAuditResults["A"] = "Grade A";
				$sAuditResults["B"] = "Grade B";
				$sAuditResults["C"] = "Grade C";


				$aResponse['Status']      = "OK";
				$aResponse['UserId']      = $iUserId;
				$aResponse['Name']        = $sName;
				$aResponse['Picture']     = (SITE_URL.USERS_IMG_PATH.'thumbs/'.$sPicture);

				$aResponse['Vendors']     = $sVendorsList;


				$aResponse['Brands']      = $sBrandsList;

				$aResponse['Reports']     = $sReportsList;
				$aResponse['Schedule']    = $sSchedule;
				$aResponse['Stages']      = $sAuditStages;
				$aResponse['Results']     = $sAuditResults;
				$aResponse['Designation'] = $sDesignation;
				$aResponse['Department']  = $sDepartment;

				$aResponse['DefectCodes'] = $sDefectCodes;

//				$aResponse['DefectAreas'] = @implode("|-|", $sDefectAreas);
				$aResponse['DefectAreas'] = $sDefectAreas;

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

	//echo time()-$start;
//	echo "<pre>";

	//echo "<pre>";
	//print_r($aResponse);
	print_r(json_encode($aResponse));
	//echo "</pre>";


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