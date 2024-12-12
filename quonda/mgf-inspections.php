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
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

                
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
        
        //$FromDate = IO::strValue("FromDate");
	//$ToDate   = IO::strValue("ToDate");
        $FromDate   = "2016-08-01";
        $ToDate     = "2016-09-01";
        
	$sAuditorsList      = getList("tbl_users", "id", "name");
	$sVendorsList       = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND sourcing='Y'");
        $sAuditStagesList   = getList("tbl_audit_stages", "code", "stage");
        $sDestinationsList  = getList("tbl_destinations", "id", "destination");
        $sOriginsList       = getList("tbl_countries", "id", "code");
        $sSampleSizeList    = array('2'=>0,'3'=>0,'5'=>0,'8'=>0,'13'=>0,'20'=>1,'32'=>2,'50'=>3,'80'=>5,'125'=>7,'200'=>10,'315'=>14,'500'=>21,'800'=>21,'1250'=>21);
                
	$sExcelFile = ($sBaseDir.TEMP_DIR."INSPECTION.xlsx");

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');
        

	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';
        require_once 'phpseclib0.3.0/Net/SFTP.php';
        
        $local_directory = $sBaseDir.TEMP_DIR;
        $remote_directory = '/TEST/Outgoing/Inspection/';

        /* FTP Connection */
        $sftp = new Net_SFTP('125.209.75.188');
        if (!$sftp->login('mgfsourcing', 'mgf2016#')) 
        {
            exit('Login Failed');
        } 

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
        
        
        $objPHPExcel->getActiveSheet()->duplicateStyleArray($sBlockStyle , "A{$iRow}:BK{$iRow}");

	$iRow ++;

        $sSQL = "SELECT qr.*,
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
        
        $sLastAuditCode = "";

	for ($i = 0; $i < $iCount; $i ++)
	{
                $iAuditId       = $objDb->getField($i, "id");
                $sAuditCode     = $objDb->getField($i, "audit_code");
                $iReportId      = $objDb->getField($i, "report_id");
		$iAuditor       = $objDb->getField($i, "user_id");
                $sAuditDate     = $objDb->getField($i, "audit_date");
                $sFinalAuditDate= $objDb->getField($i, "final_audit_date");
                $iAuditStatus   = $objDb->getField($i, "audit_status");
                $iAuditStage    = $objDb->getField($i, "audit_stage");
                $iPo            = $objDb->getField($i, "po_id");
                $sETD_DATE      = $objDb->getField($i, "_ETD_DATE");
                $sBrand         = $objDb->getField($i, "_Brand");
                $sVendors       = $objDb->getField($i, "vendor_id");
                $iTotalGmts     = $objDb->getField($i, "total_gmts");
                $iStyles        = $objDb->getField($i, "styles");
                $sStyles        = $objDb->getField($i, "_Styles");
                $sPOCOLORS      = $objDb->getField($i, "_POCOLOR");
                $iCountry       = $objDb->getField($i, "_COUNTRYID");
                $iDestination   = $objDb->getField($i, "_DESTINATIONID");
                $iShipQty       = $objDb->getField($i, "ship_qty");
                $iTotalQty      = $objDb->getField($i, "_POQTY");
                $sStartTime     = $objDb->getField($i, "start_time");
                $sEndTime       = $objDb->getField($i, "end_time");
                $iAppSample     = $objDb->getField($i, "approved_sample");
                $sAuditResult   = $objDb->getField($i, "audit_result");
                $sCustomerPo    = $objDb->getField($i, "customer_po_no");
                $sQaComments    = $objDb->getField($i, "qa_comments");
                $iDefectGmts    = $objDb->getField($i, "defective_gmts");
                $iTotalCartons  = $objDb->getField($i, "total_cartons");
                $iRejCartons    = $objDb->getField($i, "rejected_cartons");
                $iStandard      = $objDb->getField($i, "standard");
                $iReScreen      = $objDb->getField($i, "re_screen_qty");
                $iCartonsReq    = $objDb->getField($i, "cartons_required");
                $iCartonsShip   = $objDb->getField($i, "cartons_shipped");
                $iShippingMark  = $objDb->getField($i, "shipping_mark");
                $iPackingCheck  = $objDb->getField($i, "packing_check");
                $sCartonSizes   = $objDb->getField($i, "carton_size");
                $fKnitted       = $objDb->getField($i, "knitted");
                $fDyed          = $objDb->getField($i, "dyed");
                $fCutting       = $objDb->getField($i, "cutting");
                $fFinishing     = $objDb->getField($i, "finishing");
                $fSewing        = $objDb->getField($i, "sewing");
                $fPacking       = $objDb->getField($i, "packing");
                $sCreatedAt     = $objDb->getField($i, "created_at");
                $sModifiedAt    = $objDb->getField($i, "date_time");
                
                $iSpecSheets = 0;
                for($j =1 ; $j<= 10; $j++){
                    $sSheet  = $objDb->getField($i, "specs_sheet_".$j);	
                    if($sSheet != "" && $sSheet != NULL)
                        $iSpecSheets ++;
                }
                
                $sAuditStage    = @$sAuditStagesList[$iAuditStage];
		$sAuditor       = @$sAuditorsList[$iAuditor];
                $sCountry       = @$sOriginsList[$iCountry];
                $sDestination   = @$sDestinationsList[$iDestination];
                $fPercentDefect = ($iRejCartons/$iTotalCartons)*100;
                $sCartonSize    = explode('x', $sCartonSizes);
                $iCartonLength  = @$sCartonSize[0];
                $iCartonWidth   = @$sCartonSize[1];
                $iCartonHeight  = @$sCartonSize[2];
                
                $CartonUOM = "";
                if (strpos($sCartonSizes, 'in') !== false) 
                    $CartonUOM = "Inches";
                else if(strpos($sCartonSizes, 'cm') !== false)
                    $CartonUOM = "Centimeter";    
                
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
                
                $iPictures  = 0;

                if($sLastAuditCode != $sAuditCode)
                {
                    $sPictures   = array( );                
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
                                     $iPictures++;
                        }
                }

                $objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", $sAuditCode);
		$objPHPExcel->getActiveSheet()->setCellValue("B{$iRow}", "");
		$objPHPExcel->getActiveSheet()->setCellValue("C{$iRow}", ($iAuditStatus == ""?"1st":$iAuditStatus));
		$objPHPExcel->getActiveSheet()->setCellValue("D{$iRow}", $iAuditor);
                $objPHPExcel->getActiveSheet()->setCellValue("E{$iRow}", $sAuditor);
		$objPHPExcel->getActiveSheet()->setCellValue("F{$iRow}", "");
		$objPHPExcel->getActiveSheet()->setCellValue("G{$iRow}", $sAuditStage);
		$objPHPExcel->getActiveSheet()->setCellValue("H{$iRow}", ($sAuditResult == "P"?"Accepted":($sAudtiResult == "F"?"Rejected":"Hold")));
		$objPHPExcel->getActiveSheet()->setCellValue("I{$iRow}", $sAuditDate);
		$objPHPExcel->getActiveSheet()->setCellValue("J{$iRow}", $sStartTime);
                $objPHPExcel->getActiveSheet()->setCellValue("K{$iRow}", $sEndTime);
                $objPHPExcel->getActiveSheet()->setCellValue("L{$iRow}", $sFinalAuditDate);
                $objPHPExcel->getActiveSheet()->setCellValue("M{$iRow}", $iStyles);
                $objPHPExcel->getActiveSheet()->setCellValue("N{$iRow}", $sStyles);
                $objPHPExcel->getActiveSheet()->setCellValue("O{$iRow}", "2.5");
                $objPHPExcel->getActiveSheet()->setCellValue("P{$iRow}", "II");
                $objPHPExcel->getActiveSheet()->setCellValue("Q{$iRow}", $iTotalGmts);
                $objPHPExcel->getActiveSheet()->setCellValue("R{$iRow}", $sSampleSizeList[$iTotalGmts]);
                $objPHPExcel->getActiveSheet()->setCellValue("S{$iRow}", $sDestination);
                $objPHPExcel->getActiveSheet()->setCellValue("T{$iRow}", $sETD_DATE);
                $objPHPExcel->getActiveSheet()->setCellValue("U{$iRow}", $iShipQty);
                $objPHPExcel->getActiveSheet()->setCellValue("V{$iRow}", (($iAppSample == "Yes")?"Y":"N"));
                $objPHPExcel->getActiveSheet()->setCellValue("W{$iRow}", (($sShadeBand == "Y")?"Y":"N"));
                $objPHPExcel->getActiveSheet()->setCellValue("X{$iRow}", (($sPpMeeting == "Y")?"Y":"N"));
                $objPHPExcel->getActiveSheet()->setCellValue("Y{$iRow}", (($sGarmentTest == "Y")?"Y":"N"));
                $objPHPExcel->getActiveSheet()->setCellValue("Z{$iRow}", (($sFabricTest == "Y")?"Y":"N"));
                $objPHPExcel->getActiveSheet()->setCellValue("AA{$iRow}", (($sQaFile == "Y")?"Y":"N"));
                $objPHPExcel->getActiveSheet()->setCellValue("AB{$iRow}", ($sCapOthers == ""?"":$sCapOthers));
                $objPHPExcel->getActiveSheet()->setCellValue("AC{$iRow}", $sQaComments);
                $objPHPExcel->getActiveSheet()->setCellValue("AD{$iRow}", (($sFittingTorque == "Y")?"Y":"N"));
                $objPHPExcel->getActiveSheet()->setCellValue("AE{$iRow}", (($sColorCheck == "Y")?"Y":"N"));
                $objPHPExcel->getActiveSheet()->setCellValue("AF{$iRow}", (($sAccessoriesCheck == "Y")?"Y":"N"));
                $objPHPExcel->getActiveSheet()->setCellValue("AG{$iRow}", (($sMeasurementCheck == "Y")?"Y":"N"));
                $objPHPExcel->getActiveSheet()->setCellValue("AH{$iRow}", intval($iMeasurementSampleQty));
                $objPHPExcel->getActiveSheet()->setCellValue("AI{$iRow}", intval($iMeasurementDefectQty));
                $objPHPExcel->getActiveSheet()->setCellValue("AJ{$iRow}", "");
                $objPHPExcel->getActiveSheet()->setCellValue("AK{$iRow}", $iDefectGmts);
                $objPHPExcel->getActiveSheet()->setCellValue("AL{$iRow}", $iTotalCartons);
                $objPHPExcel->getActiveSheet()->setCellValue("AM{$iRow}", $iRejCartons);
                $objPHPExcel->getActiveSheet()->setCellValue("AN{$iRow}", $fPercentDefect);
                $objPHPExcel->getActiveSheet()->setCellValue("AO{$iRow}", $iStandard);
                $objPHPExcel->getActiveSheet()->setCellValue("AP{$iRow}", $iPictures);
                $objPHPExcel->getActiveSheet()->setCellValue("AQ{$iRow}", $iSpecSheets);
                $objPHPExcel->getActiveSheet()->setCellValue("AR{$iRow}", $iReScreen);
                $objPHPExcel->getActiveSheet()->setCellValue("AS{$iRow}", $iCartonsReq);
                $objPHPExcel->getActiveSheet()->setCellValue("AT{$iRow}", $iCartonsShip);
                $objPHPExcel->getActiveSheet()->setCellValue("AU{$iRow}", ($iShippingMark == "Y")?"Y":"N");
                $objPHPExcel->getActiveSheet()->setCellValue("AV{$iRow}", ($iPackingCheck == "Y")?"Y":"N");
                $objPHPExcel->getActiveSheet()->setCellValue("AW{$iRow}", $iCartonLength);
                $objPHPExcel->getActiveSheet()->setCellValue("AX{$iRow}", $iCartonWidth);
                $objPHPExcel->getActiveSheet()->setCellValue("AY{$iRow}", $iCartonHeight);
                $objPHPExcel->getActiveSheet()->setCellValue("AZ{$iRow}", (!empty($sCartonSize)?$CartonUOM:""));
                $objPHPExcel->getActiveSheet()->setCellValue("BA{$iRow}", $fKnitted);
                $objPHPExcel->getActiveSheet()->setCellValue("BB{$iRow}", $fDyed);
                $objPHPExcel->getActiveSheet()->setCellValue("BC{$iRow}", $sCartonNo);
                $objPHPExcel->getActiveSheet()->setCellValue("BD{$iRow}", $fCutting);
                $objPHPExcel->getActiveSheet()->setCellValue("BE{$iRow}", $fFinishing);
                $objPHPExcel->getActiveSheet()->setCellValue("BF{$iRow}", $fSewing);
                $objPHPExcel->getActiveSheet()->setCellValue("BG{$iRow}", $fPacking);
                $objPHPExcel->getActiveSheet()->setCellValue("BH{$iRow}", $sCreatedAt);
                $objPHPExcel->getActiveSheet()->setCellValue("BI{$iRow}", $sModifiedAt);
                $objPHPExcel->getActiveSheet()->setCellValue("BJ{$iRow}", $sAuditor);
                $objPHPExcel->getActiveSheet()->setCellValue("BK{$iRow}", $sAuditor);
                
                $objPHPExcel->getActiveSheet()->duplicateStyleArray($sBorderStyle , "A{$iRow}:BK{$iRow}");
                
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
        

	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('INSPECTION');

	include 'PHPExcel/IOFactory.php';

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($sExcelFile);

        
        $sFileName = 'INSPECTION.xlsx';
        $sftp->put($remote_directory . $sFileName, 
                                $local_directory . $sFileName, 
                                 NET_SFTP_LOCAL_FILE);
                

	$objDb->close( );
	$objDb2->close( );
        $objDbGlobal->close( );

	@ob_end_flush( );
?>