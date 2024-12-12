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
	@require_once("../requires/PHPExcel.php");
	
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
        

	$User          = IO::strValue("User");
	$UserType      = (IO::strValue("UserType") == "")?$_SESSION['UserType']:IO::strValue("UserType");
	$Country       = IO::intValue("Country");
	$Status        = IO::strValue("Status");
	$AuditorType   = IO::intValue("AuditorType");
	$ReportType    = IO::intValue("ReportType");
	$AuditsManager = IO::strValue("AuditsManager");
	$AppVersion    = IO::strValue("AppVersion");

        $sUserTypeList    = getList("tbl_user_types", "id", "`type`");
	
	$objCurl = @curl_init(SITE_URL."app/version.php");

	@curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($objCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

	$sResponse = @curl_exec($objCurl);

	@curl_close($objCurl);
	
	
	$sParams     = @json_decode($sResponse, true);
	$iAppVersion = $sParams["Code"];

	
	$sCountriesList = getList("tbl_countries", "id", "country", "id IN (SELECT DISTINCT(country_id) FROM tbl_users)");
	$sConditions    = "";
	
	if ($User != "")
		$sConditions .= " AND (name LIKE '%$User%' OR username LIKE '%$User%' OR email LIKE '%$User%'  OR mobile LIKE '%$User%') ";
	
	if ($UserType != "")
		$sConditions .= " AND user_type='$UserType' ";

	else if($_SESSION["UserType"] != "TRIPLETREE" && $_SESSION["UserType"] != "MATRIX")
			$sConditions .= " AND user_type='{$_SESSION["UserType"]}' ";
        
	if ($Country > 0)
		$sConditions .= " AND country_id='$Country' ";
	
	if ($Status != "")
		$sConditions .= " AND status='$Status' ";

	if ($AuditorType > 0)
		$sConditions .= " AND auditor_type='$AuditorType' ";
	
	if ($ReportType > 0)
		$sConditions .= " AND FIND_IN_SET('$ReportType', report_types) ";
	
	if ($AuditsManager != "")
		$sConditions .= " AND audits_manager='$AuditsManager' ";

	if ($AppVersion != "")
	{
		if ($AppVersion == "N")
			$sConditions .= " AND (device_id='' OR ISNULL(device_id)) AND app_version='0' ";
		
		else if ($AppVersion == "O")
			$sConditions .= " AND device_id!='' AND NOT ISNULL(device_id) AND app_version<'$iAppVersion' ";

		else if ($AppVersion == "U")
			$sConditions .= " AND device_id!='' AND NOT ISNULL(device_id) AND app_version='$iAppVersion' ";
	}
	
    if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	

	$sBorderStyle = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
					  'borders'  => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
										 'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
										 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
										 'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));

	$sBlockStyle = array('font'       => array('bold' => true, 'size' => 11),
					 'fill'       => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'DDDDDD')),
					 'alignment'  => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
					 'borders'    => array('top'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
										   'right'  => array('style' => PHPExcel_Style_Border::BORDER_THIN),
										   'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
	
									   'left'   => array('style' => PHPExcel_Style_Border::BORDER_THIN)));

									   
									   
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setTitle("Users Report");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("Users Report");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");


	// Create a first sheet
	$iRow = 1;

	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "Serial #");
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "Full Name");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "Country");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "Email Address");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, "User Name");
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, "Status");
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, "Audits Manager");
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$iRow, "Qa Auditor");
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$iRow, "Auditor Type");
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$iRow, "Report Types");
	$objPHPExcel->getActiveSheet()->setCellValue('K'.$iRow, "QUONDA App");
	$objPHPExcel->getActiveSheet()->setCellValue('L'.$iRow, "App Last Login");
	$objPHPExcel->getActiveSheet()->setCellValue('M'.$iRow, "Portal Last Login");
	
	if ($UserType == "MGF" && @in_array($_SESSION["UserId"], array(1,2,3)))
	{
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$iRow, "Created At");
		$objPHPExcel->getActiveSheet()->setCellValue('O'.$iRow, "Pool");
              
		$objPHPExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , "A{$iRow}:O{$iRow}");
	}
        else if($UserType == "MGF")
        {
                $objPHPExcel->getActiveSheet()->setCellValue('N'.$iRow, "Vendor Rights");
                $objPHPExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , "A{$iRow}:N{$iRow}"); 
        }
        else if ($UserType == "GLOBALEXPORTS")
        {
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$iRow, "Auditor Level");
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$iRow, "Auditor Code");
            $objPHPExcel->getActiveSheet()->setCellValue('P'.$iRow, "Auditor Phone");
            
            $objPHPExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , "A{$iRow}:P{$iRow}"); 
        }
	else
		$objPHPExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , "A{$iRow}:M{$iRow}");
	

	$iRow ++;


	$sSQL = "SELECT id, name, auditor_level, auditor_code, mobile, email, vendors, country_id, username, status, audits_manager, auditor, auditor_type, report_types, date_time, device_id, app_version, app_last_login, portal_last_login FROM tbl_users $sConditions ORDER BY date_time ASC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iUser          = $objDb->getField($i, 'id');
		$sFullName      = $objDb->getField($i, 'name');
		$sEmail         = $objDb->getField($i, "email");
                $sMobile        = $objDb->getField($i, "mobile");
		$iCountry       = $objDb->getField($i, "country_id");
		$sUserName      = $objDb->getField($i, "username");
		$sStatus        = $objDb->getField($i, "status");
		$sAuditsManager = $objDb->getField($i, "audits_manager");
		$sAuditor       = $objDb->getField($i, "auditor");
                $iVendors       = $objDb->getField($i, "vendors");
		$iAuditorType   = $objDb->getField($i, "auditor_type");
                $sAuditorLevel  = $objDb->getField($i, "auditor_level");
                $sAuditorCode   = $objDb->getField($i, "auditor_code");
		$iReportTypes   = $objDb->getField($i, "report_types");
		$sDateTime      = $objDb->getField($i, "date_time");
		$sDevice        = $objDb->getField($i, 'device_id');
		$iVersion       = $objDb->getField($i, 'app_version');
		$sAppLogin      = $objDb->getField($i, 'app_last_login');
		$sPortalLogin   = $objDb->getField($i, 'portal_last_login');

		
		$sPortalLogin   = (($sPortalLogin == "0000-00-00 00:00:00") ? getDbValue("login_date_time", "tbl_user_stats", "user_id='$iUser'", "id DESC") : $sPortalLogin);
		$sCountry       = $sCountriesList[$iCountry];
		$sAuditsManager = ($sAuditsManager == 'Y'?'Yes':'No');
		$sAuditor       = ($sAuditor == 'Y'?'Yes':'No');
		$sStatus        = ($sStatus == 'A'?'Active':'In-Active');

                switch ($sAuditorLevel)
		{
			case 'R' : $sAuditorLevel = "Red"; break;
			case 'G' : $sAuditorLevel = "Green"; break;
			case 'B' : $sAuditorLevel = "Blue"; break;
			case 'Y' : $sAuditorLevel = "Yellow"; break;
		}
                
		/*switch ($iAuditorType)
		{
			case 1 : $sAuditorType = "3rd Party Auditor"; break;
			case 2 : $sAuditorType = "QMIP Auditor"; break;
			case 3 : $sAuditorType = "QMIP Corelation Auditor"; break;
			case 4 : $sAuditorType = "MCA"; break;
			case 5 : $sAuditorType = "FCA"; break;
		}*/
                
                $sVendors       = getDbValue("GROUP_CONCAT(vendor SEPARATOR ', ')", "tbl_vendors", "id IN ($iVendors)"); 
		$sReportTypes   = getDbValue("GROUP_CONCAT(report SEPARATOR ', ')", "tbl_reports", "id IN ($iReportTypes)"); 
		$sPool        = "D";
		
		if ($i >= 200 && $i < 250)
			$sPool = "D+";
		
		else if ($i >= 250)
			$sPool = "D++";

		$sApp = "Not Installed";
		
		if ($sDevice != "" && $iVersion < $iAppVersion)
			$sApp = "Old Version";
		
		else if ($sDevice != "" && $iVersion == $iAppVersion)
			$sApp = "Up-to-date";
		
		else if ($iVersion > 0 || $sAppLogin != "0000-00-00 00:00:00")
			$sApp = "Old Version";

		
            		
		$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", ($i + 1));
		$objPHPExcel->getActiveSheet()->setCellValue("B{$iRow}", $sFullName);
		$objPHPExcel->getActiveSheet()->setCellValue("C{$iRow}", $sCountry);
		$objPHPExcel->getActiveSheet()->setCellValue("D{$iRow}", $sEmail);
		$objPHPExcel->getActiveSheet()->setCellValue("E{$iRow}", $sUserName);
		$objPHPExcel->getActiveSheet()->setCellValue("F{$iRow}", $sStatus);
		$objPHPExcel->getActiveSheet()->setCellValue("G{$iRow}", $sAuditsManager);
		$objPHPExcel->getActiveSheet()->setCellValue("H{$iRow}", $sAuditor);
		$objPHPExcel->getActiveSheet()->setCellValue("I{$iRow}", $sUserTypeList[$iAuditorType]);
		$objPHPExcel->getActiveSheet()->setCellValue("J{$iRow}", $sReportTypes);
		$objPHPExcel->getActiveSheet()->setCellValue("K{$iRow}", $sApp);
		$objPHPExcel->getActiveSheet()->setCellValue("L{$iRow}", formatDate($sAppLogin, "d-M-Y h:i A"));
		$objPHPExcel->getActiveSheet()->setCellValue("M{$iRow}", formatDate($sPortalLogin, "d-M-Y h:i A"));
		
		if ($UserType == "MGF" && @in_array($_SESSION["UserId"], array(1,2,3)))
		{
			$objPHPExcel->getActiveSheet()->setCellValue("N{$iRow}", formatDate($sDateTime));
			$objPHPExcel->getActiveSheet()->setCellValue("O{$iRow}", $sPool);
                        
			$objPHPExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle , "A{$iRow}:O{$iRow}");
		}
		else if ($UserType == "MGF")
                {
                        $objPHPExcel->getActiveSheet()->setCellValue("N{$iRow}", $sVendors);			
			$objPHPExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle , "A{$iRow}:N{$iRow}");
                }
                else if ($UserType == "GLOBALEXPORTS")
                {
                    $objPHPExcel->getActiveSheet()->setCellValue("N{$iRow}", $sAuditorLevel);
                    $objPHPExcel->getActiveSheet()->setCellValue("O{$iRow}", $sAuditorCode);
                    $objPHPExcel->getActiveSheet()->setCellValue("P{$iRow}", $sMobile);

                    $objPHPExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle , "A{$iRow}:P{$iRow}");
                }
		else
			$objPHPExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle , "A{$iRow}:M{$iRow}");


		if ($UserType == "MGF" && @in_array($_SESSION["UserId"], array(1,2,3)))
		{
			if ($i >= 200 && ($i % 50) == 0)
			{
				$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}:O{$iRow}")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '666666')),
																									'font' => array('bold' => true, 'color' => array('rgb' => 'FFFFFF'), 'size' => 11)) );			
			}
		}
		

		$iRow ++;                
	}


	// Set column widths
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
	
	if ($UserType == "MGF" && @in_array($_SESSION["UserId"], array(1,2,3)))
	{
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
	}
        else if($UserType == "MGF")
                $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        else if ($UserType == "GLOBALEXPORTS")
        {
                $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        }

	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0);

	$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

	$objPHPExcel->getActiveSheet()->setTitle("Users Report");


	$sExcelFile = "Users.xlsx";

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save("php://output");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>