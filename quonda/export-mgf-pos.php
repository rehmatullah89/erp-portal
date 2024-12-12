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

        //error_reporting(E_ALL);
        //ini_set('display_errors', 1);

	//$FromDate = IO::strValue("FromDate");
	//$ToDate   = IO::strValue("ToDate");
        $FromDate   = "2016-08-01";
        $ToDate     = "2016-09-01";
        
	$sAuditorsList      = getList("tbl_users", "id", "name");
	$sVendorsList       = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
        $sAuditStagesList   = getList("tbl_audit_stages", "code", "stage");
        $sDestinationsList  = getList("tbl_destinations", "id", "destination");
        $sOriginsList       = getList("tbl_countries", "id", "code");
        $sSampleSizeList    = array('2'=>0,'3'=>0,'5'=>0,'8'=>0,'13'=>0,'20'=>1,'32'=>2,'50'=>3,'80'=>5,'125'=>7,'200'=>10,'315'=>14,'500'=>21,'800'=>21,'1250'=>21);
                
	$sExcelFile = ($sBaseDir.TEMP_DIR."MGF POs LIST.xlsx");

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');
        

	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';

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
	$objPHPExcel->getProperties()->setTitle("MGF POS LIST");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("MGF POS LIST");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");


	// Create a first sheet
	$iRow = 1;

        $objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, "Audit Code");
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, "Inspection Report Type");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, "Inspection Report Creation Date/Time");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, "VPO No");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, "Customer ID");
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, "Customer Name");
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, "Brand");
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$iRow, "Vendor ID");
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$iRow, "Vendor Name");
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$iRow, "Factory ID");
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$iRow, "Factory Name");
        $objPHPExcel->getActiveSheet()->setCellValue('L'.$iRow, "Origin");
        $objPHPExcel->getActiveSheet()->setCellValue('M'.$iRow, "Destination");
        $objPHPExcel->getActiveSheet()->setCellValue('N'.$iRow, "Factory GAC Date");
        $objPHPExcel->getActiveSheet()->setCellValue('O'.$iRow, "Customer Delivery Date");
        $objPHPExcel->getActiveSheet()->setCellValue('P'.$iRow, "Inspection Level");
        $objPHPExcel->getActiveSheet()->setCellValue('Q'.$iRow, "Inspector ID");
        $objPHPExcel->getActiveSheet()->setCellValue('R'.$iRow, "Inspector Name");
        $objPHPExcel->getActiveSheet()->setCellValue('S'.$iRow, "Sample Size");
        $objPHPExcel->getActiveSheet()->setCellValue('T'.$iRow, "Accept Level (Major Only)");
        $objPHPExcel->getActiveSheet()->setCellValue('U'.$iRow, "AQL");
        $objPHPExcel->getActiveSheet()->setCellValue('V'.$iRow, "Inspector Type?");
        $objPHPExcel->getActiveSheet()->setCellValue('W'.$iRow, "VPO Line No");
        $objPHPExcel->getActiveSheet()->setCellValue('X'.$iRow, "Customer Style");
        $objPHPExcel->getActiveSheet()->setCellValue('Y'.$iRow, "Style Desc");
        $objPHPExcel->getActiveSheet()->setCellValue('Z'.$iRow, "Color Code");
        $objPHPExcel->getActiveSheet()->setCellValue('AA'.$iRow, "Color Description");
        $objPHPExcel->getActiveSheet()->setCellValue('AB'.$iRow, "Total Order Qty");
        $objPHPExcel->getActiveSheet()->setCellValue('AC'.$iRow, "Total Ship Qty");
        $objPHPExcel->getActiveSheet()->setCellValue('AD'.$iRow, "Inspection Start Date/Time");
        $objPHPExcel->getActiveSheet()->setCellValue('AE'.$iRow, "Inspection End Date/Time");
        $objPHPExcel->getActiveSheet()->setCellValue('AF'.$iRow, "APP Sample");
        $objPHPExcel->getActiveSheet()->setCellValue('AG'.$iRow, "Shade Band");
        $objPHPExcel->getActiveSheet()->setCellValue('AH'.$iRow, "QA File");
        $objPHPExcel->getActiveSheet()->setCellValue('AI'.$iRow, "Garment Test");
        $objPHPExcel->getActiveSheet()->setCellValue('AJ'.$iRow, "Fabric Test");
        $objPHPExcel->getActiveSheet()->setCellValue('AK'.$iRow, "PP Meeting");
        $objPHPExcel->getActiveSheet()->setCellValue('AL'.$iRow, "Rejected Measurement - CR");
        $objPHPExcel->getActiveSheet()->setCellValue('AM'.$iRow, "Rejected Measurement - MA");
        $objPHPExcel->getActiveSheet()->setCellValue('AN'.$iRow, "Rejected Measurement - MI");
        $objPHPExcel->getActiveSheet()->setCellValue('AO'.$iRow, "Rejected Workmanship - CR");
        $objPHPExcel->getActiveSheet()->setCellValue('AP'.$iRow, "Rejected Workmanship - MA");
        $objPHPExcel->getActiveSheet()->setCellValue('AQ'.$iRow, "Rejected Workmanship - MI");
        $objPHPExcel->getActiveSheet()->setCellValue('AR'.$iRow, "Rejected Material - CR");
        $objPHPExcel->getActiveSheet()->setCellValue('AS'.$iRow, "Rejected Material - MA");
        $objPHPExcel->getActiveSheet()->setCellValue('AT'.$iRow, "Rejected Material - MI");
        $objPHPExcel->getActiveSheet()->setCellValue('AU'.$iRow, "Defect Code");
        $objPHPExcel->getActiveSheet()->setCellValue('AV'.$iRow, "Defect Description");
        $objPHPExcel->getActiveSheet()->setCellValue('AW'.$iRow, "Defect Severity");
        $objPHPExcel->getActiveSheet()->setCellValue('AX'.$iRow, "CAP");
        $objPHPExcel->getActiveSheet()->setCellValue('AY'.$iRow, "Comments");
        $objPHPExcel->getActiveSheet()->setCellValue('AZ'.$iRow, "CPO No");
        $objPHPExcel->getActiveSheet()->setCellValue('BA'.$iRow, "CPO Order Date");
        $objPHPExcel->getActiveSheet()->setCellValue('BB'.$iRow, "Inspection Summary - Fitting Torque");
        $objPHPExcel->getActiveSheet()->setCellValue('BC'.$iRow, "Inspection Summary - Color Check ");
        $objPHPExcel->getActiveSheet()->setCellValue('BD'.$iRow, "Inspection Summary - Accessories");
        $objPHPExcel->getActiveSheet()->setCellValue('BE'.$iRow, "Inspection Summary - Meaurement");
        $objPHPExcel->getActiveSheet()->setCellValue('BF'.$iRow, "Inspection Result");
        
        
        $objPHPExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , "A{$iRow}:BF{$iRow}");

	$iRow ++;

        $sMeasurementCodes = "S.01,S.02,S.03";
        $sWorkmanshipCodes = "G.01,G.02,G.03,G.04,G.05,G.06,G.07,G.08,G.09,G.10,G.11,G.12,G.13,G.14,G.15,G.16,G.17,G.18,G.19,G.20,G.21,G.22,G.23,G.24,G.25,G.26,G.27";
	$sMaterialCodes    = "M.01,M.02,M.03,M.04,M.05,M.06,M.07,M.08,M.09,M.10,M.11,M.12,M.13,M.14";

	$sSQL = "SELECT qr.id, qr.user_id, qr.report_id, qr.vendor_id, qr.audit_code, qr.audit_stage, qr.audit_date, qr.start_time, qr.end_time, qr.po_id, qr.total_gmts, qr.ship_qty, qr.approved_sample, qr.audit_result,
                          p.styles, p.customer_po_no,     
                         (Select  GROUP_CONCAT(DISTINCT(style_name) SEPARATOR ',') from tbl_styles where id IN (p.styles)) as _Styles,
                         (Select brand from tbl_brands where id=p.brand_id Limit 0,1) as _Brand,
                         (Select etd_required from tbl_po_colors where po_id=p.id Limit 0,1) as _ETD_DATE,
                         (Select GROUP_CONCAT(DISTINCT(color) SEPARATOR ',') from tbl_po_colors where po_id=p.id) as _POCOLOR,
                         (Select SUM(order_qty) from tbl_po_colors where po_id=p.id) as _POQTY,
                         (Select destination_id from tbl_po_colors where po_id=p.id Limit 0,1) as _DESTINATIONID,
                         (Select country_id from tbl_vendors where id=p.vendor_id) as _COUNTRYID
                         FROM tbl_po p,tbl_qa_reports qr
			 WHERE qr.po_id = p.id AND qr.report_id = '14' AND (qr.audit_date BETWEEN '$FromDate' AND '$ToDate')";

        $objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$iTotals = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
                $iAuditId       = $objDb->getField($i, "id");
                $sAuditCode     = $objDb->getField($i, "audit_code");
                $iReportId      = $objDb->getField($i, "report_id");
		$iAuditor       = $objDb->getField($i, "user_id");
                $iAuditDate     = $objDb->getField($i, "audit_date");
                $iAuditStage    = $objDb->getField($i, "audit_stage");
                $sAuditStage    = @$sAuditStagesList[$iAuditStage];
		$sAuditor       = @$sAuditorsList[$iAuditor];
                $iPo            = $objDb->getField($i, "po_id");
                $sETD_DATE      = $objDb->getField($i, "_ETD_DATE");
                $sBrand         = $objDb->getField($i, "_Brand");
                $sVendors       = $objDb->getField($i, "vendor_id");
                $sTotalGmts     = $objDb->getField($i, "total_gmts");
                $iStyles        = $objDb->getField($i, "styles");
                $sStyles        = $objDb->getField($i, "_Styles");
                $sPOCOLORS      = $objDb->getField($i, "_POCOLOR");
                $iCountry       = $objDb->getField($i, "_COUNTRYID");
                $sCountry       = @$sOriginsList[$iCountry];
                $iDestination   = $objDb->getField($i, "_DESTINATIONID");
                $sDestination   = @$sDestinationsList[$iDestination];
                $iShipQty       = $objDb->getField($i, "ship_qty");
                $iTotalQty      = $objDb->getField($i, "_POQTY");
                $sStartTime     = $objDb->getField($i, "start_time");
                $sEndTime       = $objDb->getField($i, "end_time");
                $sAppSample     = $objDb->getField($i, "approved_sample");
                $sAuditResult   = $objDb->getField($i, "audit_result");
                $sCustomerPo    = $objDb->getField($i, "customer_po_no");
                
                $sSQL2 = "SELECT * FROM tbl_mgf_reports WHERE audit_id='$iAuditId'";
                $objDb2->query($sSQL2);

                $sVpoNo                = $objDb2->getField(0, "vpo_no");
                $sArticleNo            = $objDb2->getField(0, "article_no");
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

                
                $sSQL3 = "SELECT SUM(IF(qrd.nature='0',1,0)) as _Minor, SUM(IF(qrd.nature='1',1,0)) as _Major, SUM(IF(qrd.nature='2',1,0)) as _Critical,GROUP_CONCAT(DISTINCT(dc.code) SEPARATOR ',') as _DEFECT_CODES, GROUP_CONCAT(DISTINCT(dc.defect) SEPARATOR ',') as _DEFECTS, GROUP_CONCAT(DISTINCT(qrd.cap) SEPARATOR ',') as _CAPS, GROUP_CONCAT(DISTINCT(qrd.remarks) SEPARATOR ',') as _REMARKS 
                          FROM tbl_qa_report_defects qrd,tbl_defect_codes dc 
                          WHERE qrd.code_id = dc.id AND qrd.audit_id='$iAuditId'";
                
                $objDb3->query($sSQL3);
                
                $iMinor         = $objDb3->getField(0, "_Minor");
                $iMajor         = $objDb3->getField(0, "_Major");
                $iCritical      = $objDb3->getField(0, "_Critical");
                $sSeverity      = "Mn:".$iMinor." Mj:".$iMajor." Cr:".$iCritical;
                $sDefectCodes   = $objDb3->getField(0, "_DEFECT_CODES");
                $sDefects       = $objDb3->getField(0, "_DEFECTS");
                $sCaps          = $objDb3->getField(0, "_CAPS");
                $sRemarks       = $objDb3->getField(0, "_REMARKS");
                
		$iVendors   = @explode(",", $sVendors);
		$sLocations = "";
                

		for ($j = 0; $j < count($iVendors); $j ++)
			$sLocations .= ((($j > 0) ? ", " : "").$sVendorsList[$iVendors[$j]]);

                $sColorCode = "";
                $sPoColor   = "";
                $sPOCOLORS  = explode(',', $sPOCOLORS);
                
                foreach($sPOCOLORS as $sPOCOLOR)
                {
                    $sPOCOLOR   = explode(' ', $sPOCOLOR, 2);
                    $sColorCode .= $sPOCOLOR[0]." ,";
                    $sPoColor   .= $sPOCOLOR[1]." ,";
                }

                $iMinorDefectsRm        = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditId' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sMeasurementCodes')) AND nature='0'");
                $iMajorDefectsRm        = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditId' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sMeasurementCodes')) AND nature='1'");
                $iCriticalDefectsRm     = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditId' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sMeasurementCodes')) AND nature='2'");

                $iMinorDefectsWms        = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditId' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sWorkmanshipCodes')) AND nature='0'");
                $iMajorDefectsWms        = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditId' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sWorkmanshipCodes')) AND nature='1'");
                $iCriticalDefectsWms     = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditId' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sWorkmanshipCodes')) AND nature='2'");

                $iMinorDefectsMt     = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditId' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sMaterialCodes')) AND nature='0'");
                $iMajorDefectsMt     = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditId' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sMaterialCodes')) AND nature='1'");
                $iCriticalDefectsMt  = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$iAuditId' AND code_id IN (SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND FIND_IN_SET(code, '$sMaterialCodes')) AND nature='2'");

                
		$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", $sAuditCode);
		$objPHPExcel->getActiveSheet()->setCellValue("B{$iRow}", $sAuditStage);
		$objPHPExcel->getActiveSheet()->setCellValue("C{$iRow}", $iAuditDate);
		$objPHPExcel->getActiveSheet()->setCellValue("D{$iRow}", $iPo);
		$objPHPExcel->getActiveSheet()->setCellValue("E{$iRow}", "");
		$objPHPExcel->getActiveSheet()->setCellValue("F{$iRow}", $sBrand);
		$objPHPExcel->getActiveSheet()->setCellValue("G{$iRow}", $sBrand);
		$objPHPExcel->getActiveSheet()->setCellValue("H{$iRow}", $sVendors);
		$objPHPExcel->getActiveSheet()->setCellValue("I{$iRow}", $sLocations);
                $objPHPExcel->getActiveSheet()->setCellValue("J{$iRow}", $sVendors);
                $objPHPExcel->getActiveSheet()->setCellValue("K{$iRow}", $sLocations);
                $objPHPExcel->getActiveSheet()->setCellValue("L{$iRow}", $sCountry);
                $objPHPExcel->getActiveSheet()->setCellValue("M{$iRow}", $sDestination);
                $objPHPExcel->getActiveSheet()->setCellValue("N{$iRow}", $sETD_DATE);
                $objPHPExcel->getActiveSheet()->setCellValue("O{$iRow}", $sETD_DATE);
                $objPHPExcel->getActiveSheet()->setCellValue("P{$iRow}", "II");
                $objPHPExcel->getActiveSheet()->setCellValue("Q{$iRow}", $iAuditor);
                $objPHPExcel->getActiveSheet()->setCellValue("R{$iRow}", $sAuditor);
                $objPHPExcel->getActiveSheet()->setCellValue("S{$iRow}", $sTotalGmts);
                $objPHPExcel->getActiveSheet()->setCellValue("T{$iRow}", $sSampleSizeList[$sTotalGmts]);
                $objPHPExcel->getActiveSheet()->setCellValue("U{$iRow}", "2.5");
                $objPHPExcel->getActiveSheet()->setCellValue("V{$iRow}", "");
                $objPHPExcel->getActiveSheet()->setCellValue("W{$iRow}", "");
                $objPHPExcel->getActiveSheet()->setCellValue("X{$iRow}", $iStyles);
                $objPHPExcel->getActiveSheet()->setCellValue("Y{$iRow}", $sStyles);
                $objPHPExcel->getActiveSheet()->setCellValue("Z{$iRow}", $sColorCode);
                $objPHPExcel->getActiveSheet()->setCellValue("AA{$iRow}", $sPoColor);
                $objPHPExcel->getActiveSheet()->setCellValue("AB{$iRow}", $iTotalQty);
                $objPHPExcel->getActiveSheet()->setCellValue("AC{$iRow}", $iShipQty);
                $objPHPExcel->getActiveSheet()->setCellValue("AD{$iRow}", $iAuditDate." ".$sStartTime);
                $objPHPExcel->getActiveSheet()->setCellValue("AE{$iRow}", $iAuditDate." ".$sEndTime);
                $objPHPExcel->getActiveSheet()->setCellValue("AF{$iRow}", $sAppSample);
                $objPHPExcel->getActiveSheet()->setCellValue("AG{$iRow}", ($sShadeBand == ""?"":$sShadeBand));
                $objPHPExcel->getActiveSheet()->setCellValue("AH{$iRow}", ($sQaFile == ""?"":$sQaFile));
                $objPHPExcel->getActiveSheet()->setCellValue("AI{$iRow}", ($sGarmentTest == ""?"":$sGarmentTest));
                $objPHPExcel->getActiveSheet()->setCellValue("AJ{$iRow}", ($sFabricTest == ""?"":$sFabricTest));
                $objPHPExcel->getActiveSheet()->setCellValue("AK{$iRow}", ($sPpMeeting == ""?"":$sPpMeeting));
                $objPHPExcel->getActiveSheet()->setCellValue("AL{$iRow}", $iCriticalDefectsRm);
                $objPHPExcel->getActiveSheet()->setCellValue("AM{$iRow}", $iMajorDefectsRm);
                $objPHPExcel->getActiveSheet()->setCellValue("AN{$iRow}", $iMinorDefectsRm);
                $objPHPExcel->getActiveSheet()->setCellValue("AO{$iRow}", $iCriticalDefectsWms);
                $objPHPExcel->getActiveSheet()->setCellValue("AP{$iRow}", $iMajorDefectsWms);
                $objPHPExcel->getActiveSheet()->setCellValue("AQ{$iRow}", $iMinorDefectsWms);
                $objPHPExcel->getActiveSheet()->setCellValue("AR{$iRow}", $iCriticalDefectsMt);
                $objPHPExcel->getActiveSheet()->setCellValue("AS{$iRow}", $iMajorDefectsMt);
                $objPHPExcel->getActiveSheet()->setCellValue("AT{$iRow}", $iMinorDefectsMt);
                $objPHPExcel->getActiveSheet()->setCellValue("AU{$iRow}", $sDefectCodes);
                $objPHPExcel->getActiveSheet()->setCellValue("AV{$iRow}", $sDefects);
                $objPHPExcel->getActiveSheet()->setCellValue("AW{$iRow}", $sSeverity);
                $objPHPExcel->getActiveSheet()->setCellValue("AX{$iRow}", $sCaps);
                $objPHPExcel->getActiveSheet()->setCellValue("AY{$iRow}", $sRemarks);
                $objPHPExcel->getActiveSheet()->setCellValue("AZ{$iRow}", $sCustomerPo);
                $objPHPExcel->getActiveSheet()->setCellValue("BA{$iRow}", "");
                $objPHPExcel->getActiveSheet()->setCellValue("BB{$iRow}", ($sFittingTorque == ""?"":$sFittingTorque));
                $objPHPExcel->getActiveSheet()->setCellValue("BC{$iRow}", ($sColorCheck == ""?"":$sColorCheck));
                $objPHPExcel->getActiveSheet()->setCellValue("BD{$iRow}", ($sAccessoriesCheck == ""?"":$sAccessoriesCheck));
                $objPHPExcel->getActiveSheet()->setCellValue("BE{$iRow}", ($sMeasurementCheck == ""?"":$sMeasurementCheck));
                $objPHPExcel->getActiveSheet()->setCellValue("BF{$iRow}", ($sAuditResult == "P"?"Accepted":"Rejected"));
                
                $objPHPExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle , "A{$iRow}:BF{$iRow}");
                
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
        

	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('MGF POs LIST');

	include 'PHPExcel/IOFactory.php';

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($sExcelFile);


	$objDb->close( );
	$objDb2->close( );
        $objDb3->close( );
	$objDbGlobal->close( );


	// forcing excel file to download
	$iSize = @filesize($sExcelFile);

	if(ini_get('zlib.output_compression'))
		@ini_set('zlib.output_compression', 'Off');

	header('Content-Description: File Transfer');
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
	header('Content-Type: application/force-download');
	header("Content-Type: application/download");
	header("Content-Type: text/xlsx");
	header("Content-Disposition: attachment; filename=\"".basename($sExcelFile)."\";");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: $iSize");

	@readfile($sExcelFile);
	@unlink($sExcelFile);

	@ob_end_flush( );
?>