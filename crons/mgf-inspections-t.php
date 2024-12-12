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
	
	@putenv("TZ=Asia/Karachi");
	@date_default_timezone_set("Asia/Karachi");
	@ini_set("date.timezone", "Asia/Karachi");

	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);

      
	$sBaseDir = "../";
	//$sBaseDir = "C:/wamp/www/portal/";

	@require_once($sBaseDir."requires/configs.php");
	@require_once($sBaseDir."requires/db.class.php");
	@require_once($sBaseDir."requires/common-functions.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	if (defined('STDIN'))
		$iReport = intval($argv[1]);
	
	else 
		$iReport = intval($_REQUEST['Report']);

	$iReport   = 14;//(($iReport == 0) ? 14 : $iReport);	
	//$sFromDate = date("Y-m-d", strtotime("last week"));
	//$sToDate   = date("Y-m-d");
	
	$sFromDate = "2018-05-07";
	$sToDate   = "2018-05-15";
	
	
	$sDir       = "";//(($iReport == 14) ? "prod/" : "test/");
	$sExcelFile = ($sBaseDir."mgf/{$sDir}INSPECTION_D.xlsx");
	
	@unlink($sExcelFile);
	

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

        require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';


	$sAuditorsList        = getList("tbl_users", "id", "name");
        $sAuditorsTypeList    = getList("tbl_users", "id", "auditor_type");
	$sVendorsList         = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND sourcing='Y'");
	$sAuditStagesList     = getList("tbl_audit_stages", "code", "stage");
	$sDestinationsList    = getList("tbl_destinations", "id", "destination");
	$sOriginsList         = getList("tbl_countries", "id", "code");
	$sVendorCountriesList = getList("tbl_vendors", "id", "country_id");
	$sCountryHoursList    = getList("tbl_countries", "id", "hours");	



	$sHeadingStyle = array('font' => array('bold' => true, 'size' => 11),
					   'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
					   'borders'   => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
										  'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
										  'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
										  'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)) );

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
	$objPHPExcel->getProperties()->setTitle("INSPECTION");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("INSPECTION");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");


	// Create a first sheet
	$iRow = 1;

	$objPHPExcel->setActiveSheetIndex(0);
	
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "Test_No");
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "Test_ID");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "ReTest_ID");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "INSPECTOR_NO");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, "Inspector_Name");
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, "Inspector Type");
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, "Inspection_Type");
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$iRow, "Inspection_Status");
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$iRow, "Inspection_Date");
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$iRow, "Inspection_Start_Time");
	$objPHPExcel->getActiveSheet()->setCellValue('K'.$iRow, "Inspection_End_Time");
	$objPHPExcel->getActiveSheet()->setCellValue('L'.$iRow, "Final Audit Date");
	$objPHPExcel->getActiveSheet()->setCellValue('M'.$iRow, "Style_No");
	$objPHPExcel->getActiveSheet()->setCellValue('N'.$iRow, "Style_Description");
	$objPHPExcel->getActiveSheet()->setCellValue('O'.$iRow, "AQL");
	$objPHPExcel->getActiveSheet()->setCellValue('P'.$iRow, "Inspection_Level");
	$objPHPExcel->getActiveSheet()->setCellValue('Q'.$iRow, "Sample_Size");
	$objPHPExcel->getActiveSheet()->setCellValue('R'.$iRow, "Max Allowable Defects");
	$objPHPExcel->getActiveSheet()->setCellValue('S'.$iRow, "Destination");
	$objPHPExcel->getActiveSheet()->setCellValue('T'.$iRow, "GAC Date");
	$objPHPExcel->getActiveSheet()->setCellValue('U'.$iRow, "Ship_Qty");
	$objPHPExcel->getActiveSheet()->setCellValue('V'.$iRow, "APP Sample");
	$objPHPExcel->getActiveSheet()->setCellValue('W'.$iRow, "Shade Band");
	$objPHPExcel->getActiveSheet()->setCellValue('X'.$iRow, "PP Meeting Minutes");
	$objPHPExcel->getActiveSheet()->setCellValue('Y'.$iRow, "Garment Test");
	$objPHPExcel->getActiveSheet()->setCellValue('Z'.$iRow, "Fabric Test");
	$objPHPExcel->getActiveSheet()->setCellValue('AA'.$iRow, "QA File");
	$objPHPExcel->getActiveSheet()->setCellValue('AB'.$iRow, "CAP_Others");
	$objPHPExcel->getActiveSheet()->setCellValue('AC'.$iRow, "QA_Comments");
	$objPHPExcel->getActiveSheet()->setCellValue('AD'.$iRow, "Fitting / Torque");
	$objPHPExcel->getActiveSheet()->setCellValue('AE'.$iRow, "Color Check");
	$objPHPExcel->getActiveSheet()->setCellValue('AF'.$iRow, "Accessories Check");
	$objPHPExcel->getActiveSheet()->setCellValue('AG'.$iRow, "Measurement Check");
	$objPHPExcel->getActiveSheet()->setCellValue('AH'.$iRow, "Measurement_Inspected_Qty");
	$objPHPExcel->getActiveSheet()->setCellValue('AI'.$iRow, "Measurement_Defective_Qty");
	$objPHPExcel->getActiveSheet()->setCellValue('AJ'.$iRow, "QA Type");
	$objPHPExcel->getActiveSheet()->setCellValue('AK'.$iRow, "# of GMTS Defective");
	$objPHPExcel->getActiveSheet()->setCellValue('AL'.$iRow, "Total Cartons Inspected");
	$objPHPExcel->getActiveSheet()->setCellValue('AM'.$iRow, "# of Cartons Rejected");
	$objPHPExcel->getActiveSheet()->setCellValue('AN'.$iRow, "% Defective");
	$objPHPExcel->getActiveSheet()->setCellValue('AO'.$iRow, "Acceptable Stardard");
	$objPHPExcel->getActiveSheet()->setCellValue('AP'.$iRow, "Attachment_Qty_Packing");
	$objPHPExcel->getActiveSheet()->setCellValue('AQ'.$iRow, "Attachment_Qty_Spec_Lab");
	$objPHPExcel->getActiveSheet()->setCellValue('AR'.$iRow, "Re-Screen Qty");
	$objPHPExcel->getActiveSheet()->setCellValue('AS'.$iRow, "Total Cartons Required");
	$objPHPExcel->getActiveSheet()->setCellValue('AT'.$iRow, "Total Cartons Shipped");
	$objPHPExcel->getActiveSheet()->setCellValue('AU'.$iRow, "Shipping Mark");
	$objPHPExcel->getActiveSheet()->setCellValue('AV'.$iRow, "Packing Check");
	$objPHPExcel->getActiveSheet()->setCellValue('AW'.$iRow, "Carton_Size_Length");
	$objPHPExcel->getActiveSheet()->setCellValue('AX'.$iRow, "Carton_Size_Width");
	$objPHPExcel->getActiveSheet()->setCellValue('AY'.$iRow, "Carton_Size_Height");
	$objPHPExcel->getActiveSheet()->setCellValue('AZ'.$iRow, "Carton_Size_UOM");
	$objPHPExcel->getActiveSheet()->setCellValue('BA'.$iRow, "Knitted %");
	$objPHPExcel->getActiveSheet()->setCellValue('BB'.$iRow, "Dyed %");
	$objPHPExcel->getActiveSheet()->setCellValue('BC'.$iRow, "Carton_No");
	$objPHPExcel->getActiveSheet()->setCellValue('BD'.$iRow, "Cutting");
	$objPHPExcel->getActiveSheet()->setCellValue('BE'.$iRow, "Finishing");
	$objPHPExcel->getActiveSheet()->setCellValue('BF'.$iRow, "Sewing");
	$objPHPExcel->getActiveSheet()->setCellValue('BG'.$iRow, "Packing");
	$objPHPExcel->getActiveSheet()->setCellValue('BH'.$iRow, "Created_DateTime");
	$objPHPExcel->getActiveSheet()->setCellValue('BI'.$iRow, "LastModified_DateTime");
	$objPHPExcel->getActiveSheet()->setCellValue('BJ'.$iRow, "Created_By");
	$objPHPExcel->getActiveSheet()->setCellValue('BK'.$iRow, "LastModified_By");
	$objPHPExcel->getActiveSheet()->setCellValue('BL'.$iRow, "VPO_No");
	$objPHPExcel->getActiveSheet()->setCellValue('BM'.$iRow, "Vendor/Factory Name");

	$objPHPExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , "A{$iRow}:BM{$iRow}");

	
	$iRow ++;
	
	
	$sReportDateTime = date("Y-m-d H:i:00", mktime(date("H"), (date("i") - 5), 0, date("m"), date("d"), date("Y")));	
	
	
	//$sSQL = "UPDATE tbl_global SET mgf_report_time='$sReportDateTime' WHERE id='1'";
	//$objDb->execute($sSQL);
	

	
	$sSQL = "SELECT qr.*,
					p.customer_po_no,
					p.vpo_no,
				   (SELECT style FROM tbl_styles where id=qr.style_id) AS _Style,
				   (SELECT CONCAT(style, ' ', style_name) FROM tbl_styles where id=qr.style_id) AS _StyleDesc,
				   (SELECT etd_required FROM tbl_po_colors WHERE po_id=p.id AND etd_required!='0000-00-00' ORDER BY etd_required LIMIT 1) AS _EtdRequired,
				   (SELECT destination_id FROM tbl_po_colors WHERE po_id=p.id LIMIT 1) AS _Destination
		     FROM tbl_po p, tbl_qa_reports qr
		     WHERE qr.po_id=p.id AND qr.report_id='$iReport' AND ((DATE(qr.created_at) BETWEEN '$sFromDate' AND '$sToDate') OR (DATE(qr.date_time) BETWEEN '$sFromDate' AND '$sToDate'))
			       AND qr.vendor_id!='246' AND qr.brand_id!='256'
				   AND qr.audit_date>='2016-11-10' AND qr.audit_result!='' AND NOT ISNULL(qr.audit_result) AND qr.qa_comments!='' AND qr.date_time<='$sReportDateTime'
			 ORDER BY qr.audit_date, qr.start_time";
			 
	$objDb->query($sSQL);

	$iCount         = $objDb->getCount( );
	$iTotals        = array( );
    $sLastAuditCode = "";

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iAudit           = $objDb->getField($i, "id");
		$sAuditCode       = $objDb->getField($i, "audit_code");
		$iAuditor         = $objDb->getField($i, "user_id");
		$sAuditDate       = $objDb->getField($i, "audit_date");
		$sFinalAuditDate  = $objDb->getField($i, "final_audit_date");
		$sAuditStatus     = $objDb->getField($i, "audit_status");
		$sAuditStage      = $objDb->getField($i, "audit_stage");
		$sEtdRequired     = $objDb->getField($i, "_EtdRequired");
		$iVendor          = $objDb->getField($i, "vendor_id");
		$iTotalGmts       = $objDb->getField($i, "total_gmts");
		$sStyle           = $objDb->getField($i, "_Style");
		$sStyleDesc       = $objDb->getField($i, "_StyleDesc");
		$iDestination     = $objDb->getField($i, "_Destination");
		$iShipQty         = $objDb->getField($i, "ship_qty");
		$sStartTime       = $objDb->getField($i, "start_time");
		$sEndTime         = $objDb->getField($i, "end_time");
		$sAppSample       = $objDb->getField($i, "approved_sample");
		$sAuditResult     = $objDb->getField($i, "audit_result");
		$sCustomerPo      = $objDb->getField($i, "customer_po_no");
		$sVpoNo           = $objDb->getField($i, "vpo_no");
		$sComments        = $objDb->getField($i, "qa_comments");
		$iDefectiveGmts   = $objDb->getField($i, "defective_gmts");
		$iTotalCartons    = $objDb->getField($i, "total_cartons");
		$iRejectedCartons = $objDb->getField($i, "rejected_cartons");
		$sStandard        = $objDb->getField($i, "standard");
		$iReScreenQty     = $objDb->getField($i, "re_screen_qty");
		$iCartonsRequired = $objDb->getField($i, "cartons_required");
		$iCartonsShipped  = $objDb->getField($i, "cartons_shipped");
		$sCartonsShipped  = $objDb->getField($i, "shipping_mark");
		$sPackingCheck    = $objDb->getField($i, "packing_check");
		$sCartonSize      = $objDb->getField($i, "carton_size");
		$fKnitted         = $objDb->getField($i, "knitted");
		$fDyed            = $objDb->getField($i, "dyed");
		$fCutting         = $objDb->getField($i, "cutting");
		$fFinishing       = $objDb->getField($i, "finishing");
		$fSewing          = $objDb->getField($i, "sewing");
		$fPacking         = $objDb->getField($i, "packing");
		$iMasterId        = $objDb->getField($i, "master_id");
		$sCreatedAt       = $objDb->getField($i, "created_at");
		$sModifiedAt      = $objDb->getField($i, "date_time");
		$iCreatedBy       = $objDb->getField($i, "created_by");
		$iModifiedBy      = $objDb->getField($i, "modified_by");
                $fAql             = $objDb->getField($i, "aql");


		$iCountry    = $sVendorCountriesList[$iVendor];
		$iHours      = $sCountryHoursList[$iCountry];
		
		$sStartTime  = date("H:i:s", (strtotime($sStartTime) + ($iHours * 3600)));
		$sEndTime    = date("H:i:s", (strtotime($sEndTime) + ($iHours * 3600)));
		$sCreatedAt  = date("Y-m-d H:i:s", (strtotime($sCreatedAt) + ($iHours * 3600)));
		$sModifiedAt = date("Y-m-d H:i:s", (strtotime($sModifiedAt) + ($iHours * 3600)));
				
		
		$iSpecSheets = 0;
		
		for ($j = 1 ; $j <= 10; $j ++)
		{
			$sSheet = $objDb->getField($i, "specs_sheet_{$j}");
			
			if ($sSheet != "" && $sSheet != NULL)
				$iSpecSheets ++;
		}

		
		if($sAuditResult == 'P')
			$sAuditResult = 'Accepted';
		
		else if($sAuditResult == 'F')
			$sAuditResult = 'Rejected';
		
		else
			$sAuditResult = 'Hold';

		

		$sVendor        = $sVendorsList[$iVendor];
		$fPercentDefect = (($iRejectedCartons/$iTotalCartons) * 100);
		$sCartonSizes   = explode('x', $sCartonSize);
		$iCartonLength  = @$sCartonSizes[0];
		$iCartonWidth   = @$sCartonSizes[1];
		$iCartonHeight  = @$sCartonSizes[2];
		$sCartonUom     = "";
		
		
		if (strpos($sCartonSize, 'in') !== false)
			$sCartonUom = "Inches";
		
		else if(strpos($sCartonSize, 'cm') !== false)
			$sCartonUom = "Centimeter";

		
		$sSQL = "SELECT * FROM tbl_mgf_reports WHERE audit_id='$iAudit'";
		$objDb2->query($sSQL);

		$sGarmentTest          = $objDb2->getField(0, "garment_test");
		$sShadeBand            = $objDb2->getField(0, "shade_band");
		$sQaFile               = $objDb2->getField(0, "qa_file");
		$sFabricTest           = $objDb2->getField(0, "fabric_test");
		$sPpMeeting            = $objDb2->getField(0, "pp_meeting");
		$sFittingTorque        = $objDb2->getField(0, "fitting_torque");
		$sColorCheck           = $objDb2->getField(0, "color_check");
		$sAccessoriesCheck     = $objDb2->getField(0, "accessories_check");
		$sMeasurementCheck     = $objDb2->getField(0, "measurement_check");
		$sCapOthers            = $objDb2->getField(0, "cap_others");
		$sCartonNo             = $objDb2->getField(0, "carton_no");
		$iMeasurementSampleQty = $objDb2->getField(0, "measurement_sample_qty");
		$iMeasurementDefectQty = $objDb2->getField(0, "measurement_defect_qty");


		$iPictures  = 0;

		if ($sLastAuditCode != $sAuditCode)
		{
			$sPictures = array( );
			
			@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

			$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/{$sAuditCode}_*.*");
			$sPictures = @array_map("strtoupper", $sPictures);
			$sPictures = @array_unique($sPictures);
		}

		
		if (count($sPictures) > 0)
		{
			foreach ($sPictures as $sPicture)
			{
				$sPicture = @basename($sPicture);
				
				if (strpos($sPicture, "{$sAuditCode}_PACK_") !== false)
					$iPictures ++;
			}
		}

                $sAuditorType = "";
                
                switch ($sAuditorsTypeList[$iAuditor])
		{
			case 1 : $sAuditorType = "MCA"; break;
			case 2 : $sAuditorType = "FCA"; break;
			case 3 : $sAuditorType = "3rd Party Auditor"; break;
			case 4 : $sAuditorType = "QMIP Auditor"; break;
			case 5 : $sAuditorType = "QMIP Corelation Auditor"; break;
                        case 14: $sAuditorType = "MGF 3rd Party"; break;
		}
		
		$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", $sAuditCode);
		$objPHPExcel->getActiveSheet()->setCellValue("B{$iRow}", $iMasterId);
		$objPHPExcel->getActiveSheet()->setCellValue("C{$iRow}", (($sAuditStatus == "") ? "1st" : $sAuditStatus));
		$objPHPExcel->getActiveSheet()->setCellValue("D{$iRow}", $iAuditor);
		$objPHPExcel->getActiveSheet()->setCellValue("E{$iRow}", $sAuditorsList[$iAuditor]);
		$objPHPExcel->getActiveSheet()->setCellValue("F{$iRow}", $sAuditorType);
		$objPHPExcel->getActiveSheet()->setCellValue("G{$iRow}", $sAuditStagesList[$sAuditStage]);
		$objPHPExcel->getActiveSheet()->setCellValue("H{$iRow}", $sAuditResult);
		$objPHPExcel->getActiveSheet()->setCellValue("I{$iRow}", $sAuditDate);
		$objPHPExcel->getActiveSheet()->setCellValue("J{$iRow}", $sStartTime);
		$objPHPExcel->getActiveSheet()->setCellValue("K{$iRow}", $sEndTime);
		$objPHPExcel->getActiveSheet()->setCellValue("L{$iRow}", $sFinalAuditDate);
		$objPHPExcel->getActiveSheet()->setCellValue("M{$iRow}", $sStyle);
		$objPHPExcel->getActiveSheet()->setCellValue("N{$iRow}", " {$sStyleDesc}");
		$objPHPExcel->getActiveSheet()->setCellValue("O{$iRow}", ($fAql > 0?$fAql:"2.5"));
		$objPHPExcel->getActiveSheet()->setCellValue("P{$iRow}", "II");
		$objPHPExcel->getActiveSheet()->setCellValue("Q{$iRow}", $iTotalGmts);
		$objPHPExcel->getActiveSheet()->setCellValue("R{$iRow}", $iAqlChart[$iTotalGmts]["2.5"]);
		$objPHPExcel->getActiveSheet()->setCellValue("S{$iRow}", $sDestinationsList[$iDestination]);
		$objPHPExcel->getActiveSheet()->setCellValue("T{$iRow}", (($sEtdRequired == '') ? '0000-00-00' : $sEtdRequired));
		$objPHPExcel->getActiveSheet()->setCellValue("U{$iRow}", $iShipQty);
		$objPHPExcel->getActiveSheet()->setCellValue("V{$iRow}", (($sAppSample == "Yes") ? "Y" : "N"));
		$objPHPExcel->getActiveSheet()->setCellValue("W{$iRow}", (($sShadeBand == "Y") ? "Y" : "N"));
		$objPHPExcel->getActiveSheet()->setCellValue("X{$iRow}", (($sPpMeeting == "Y") ? "Y" : "N"));
		$objPHPExcel->getActiveSheet()->setCellValue("Y{$iRow}", (($sGarmentTest == "Y") ? "Y" : "N"));
		$objPHPExcel->getActiveSheet()->setCellValue("Z{$iRow}", (($sFabricTest == "Y") ? "Y" : "N"));
		$objPHPExcel->getActiveSheet()->setCellValue("AA{$iRow}", (($sQaFile == "Y") ? "Y" : "N"));
		$objPHPExcel->getActiveSheet()->setCellValue("AB{$iRow}", " {$sCapOther}");
		$objPHPExcel->getActiveSheet()->setCellValue("AC{$iRow}", (($sComments == "N/A") ? "" : " {$sComments}"));
		$objPHPExcel->getActiveSheet()->setCellValue("AD{$iRow}", (($sFittingTorque == "Y") ? "Y" : "N"));
		$objPHPExcel->getActiveSheet()->setCellValue("AE{$iRow}", (($sColorCheck == "Y") ? "Y" : "N"));
		$objPHPExcel->getActiveSheet()->setCellValue("AF{$iRow}", (($sAccessoriesCheck == "Y") ? "Y" : "N"));
		$objPHPExcel->getActiveSheet()->setCellValue("AG{$iRow}", (($sMeasurementCheck == "Y") ? "Y" : "N"));
		$objPHPExcel->getActiveSheet()->setCellValue("AH{$iRow}", intval($iMeasurementSampleQty));
		$objPHPExcel->getActiveSheet()->setCellValue("AI{$iRow}", intval($iMeasurementDefectQty));
		$objPHPExcel->getActiveSheet()->setCellValue("AJ{$iRow}", "");
		$objPHPExcel->getActiveSheet()->setCellValue("AK{$iRow}", $iDefectiveGmts);
		$objPHPExcel->getActiveSheet()->setCellValue("AL{$iRow}", $iTotalCartons);
		$objPHPExcel->getActiveSheet()->setCellValue("AM{$iRow}", $iRejectedCartons);
		$objPHPExcel->getActiveSheet()->setCellValue("AN{$iRow}", $fPercentDefect);
		$objPHPExcel->getActiveSheet()->setCellValue("AO{$iRow}", $sStandard);
		$objPHPExcel->getActiveSheet()->setCellValue("AP{$iRow}", $iPictures);
		$objPHPExcel->getActiveSheet()->setCellValue("AQ{$iRow}", $iSpecSheets);
		$objPHPExcel->getActiveSheet()->setCellValue("AR{$iRow}", $iReScreenQty);
		$objPHPExcel->getActiveSheet()->setCellValue("AS{$iRow}", $iCartonsRequired);
		$objPHPExcel->getActiveSheet()->setCellValue("AT{$iRow}", $iCartonsShipped);
		$objPHPExcel->getActiveSheet()->setCellValue("AU{$iRow}", (($sCartonsShipped == "Y") ? "Y" : "N"));
		$objPHPExcel->getActiveSheet()->setCellValue("AV{$iRow}", (($sPackingCheck == "Y") ? "Y" : "N"));
		$objPHPExcel->getActiveSheet()->setCellValue("AW{$iRow}", $iCartonLength);
		$objPHPExcel->getActiveSheet()->setCellValue("AX{$iRow}", $iCartonWidth);
		$objPHPExcel->getActiveSheet()->setCellValue("AY{$iRow}", $iCartonHeight);
		$objPHPExcel->getActiveSheet()->setCellValue("AZ{$iRow}", $sCartonUom);
		$objPHPExcel->getActiveSheet()->setCellValue("BA{$iRow}", $fKnitted);
		$objPHPExcel->getActiveSheet()->setCellValue("BB{$iRow}", $fDyed);
		$objPHPExcel->getActiveSheet()->setCellValue("BC{$iRow}", " {$sCartonNo}");
		$objPHPExcel->getActiveSheet()->setCellValue("BD{$iRow}", $fCutting);
		$objPHPExcel->getActiveSheet()->setCellValue("BE{$iRow}", $fFinishing);
		$objPHPExcel->getActiveSheet()->setCellValue("BF{$iRow}", $fSewing);
		$objPHPExcel->getActiveSheet()->setCellValue("BG{$iRow}", $fPacking);
		$objPHPExcel->getActiveSheet()->setCellValue("BH{$iRow}", $sCreatedAt);
		$objPHPExcel->getActiveSheet()->setCellValue("BI{$iRow}", $sModifiedAt);
		$objPHPExcel->getActiveSheet()->setCellValue("BJ{$iRow}", $sAuditorsList[$iCreatedBy]);
		$objPHPExcel->getActiveSheet()->setCellValue("BK{$iRow}", $sAuditorsList[$iModifiedBy]);
		$objPHPExcel->getActiveSheet()->setCellValue("BL{$iRow}", $sVpoNo);
		$objPHPExcel->getActiveSheet()->setCellValue("BM{$iRow}", $sVendor);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle , "A{$iRow}:BM{$iRow}");

		$sLastAuditCode = $sAuditCode;

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
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AO')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AQ')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AS')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AT')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AU')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AV')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AW')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AX')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AY')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AZ')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BA')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BB')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BC')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BD')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BE')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BF')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BG')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BH')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BI')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BJ')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BK')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BL')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BM')->setAutoSize(true);


	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('INSPECTION');
	
	include 'PHPExcel/IOFactory.php';

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	
	$objWriter->setPreCalculateFormulas(false);
	$objWriter->save($sExcelFile);

	
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>